<?php

namespace App\Http\Controllers;

use App\Models\LetterRequest;
use App\Models\Resident;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRMarkupSVG;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LetterRequestController extends Controller
{
    private const LETTER_TYPES = [
        'domisili' => ['name' => 'Surat Keterangan Domisili', 'code' => 'SKD', 'classification' => '470'],
        'usaha' => ['name' => 'Surat Keterangan Usaha', 'code' => 'SKU', 'classification' => '510'],
        'kematian' => ['name' => 'Surat Keterangan Kematian', 'code' => 'SKM', 'classification' => '474.3'],
        'pindah' => ['name' => 'Surat Keterangan Pindah', 'code' => 'SKP', 'classification' => '475'],
        'tidak_mampu' => ['name' => 'Surat Keterangan Tidak Mampu', 'code' => 'SKTM', 'classification' => '460'],
    ];

    public function index(Request $request)
    {
        $search = $request->string('cari')->toString();

        $letters = LetterRequest::with('resident')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('applicant_name', 'like', "%{$search}%")
                        ->orWhere('letter_type', 'like', "%{$search}%")
                        ->orWhere('letter_number', 'like', "%{$search}%")
                        ->orWhereHas('resident', function ($query) use ($search) {
                            $query->where('nik', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('letters.index', [
            'letters' => $letters,
            'filters' => $request->only(['cari', 'status']),
            'statuses' => ['Diajukan', 'Verifikasi', 'Diproses', 'Selesai', 'Ditolak'],
        ]);
    }

    public function create()
    {
        return view('letters.create', [
            'residents' => Resident::orderBy('name')->get(),
            'letterTypes' => self::LETTER_TYPES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'resident_id' => ['nullable', 'exists:residents,id'],
            'applicant_name' => ['nullable', 'required_without:resident_id', 'string', 'max:255'],
            'letter_code' => ['required', 'in:' . implode(',', array_keys(self::LETTER_TYPES))],
            'purpose' => ['required', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'string', 'max:50'],
            'requested_at' => ['required', 'date'],
        ]);

        $resident = isset($data['resident_id'])
            ? Resident::with('familyCard')->find($data['resident_id'])
            : null;

        $type = self::LETTER_TYPES[$data['letter_code']];
        $data['letter_type'] = $type['name'];
        $data['applicant_name'] = $resident?->name ?: $data['applicant_name'];
        $data['letter_number'] = $this->generateLetterNumber($data['letter_code']);
        $data['verification_code'] = strtoupper(Str::random(10));
        $data['template_data'] = $this->buildTemplateData($resident, $data);
        $data['signed_by'] = auth()->id();
        $data['signed_at'] = now();
        $data['digital_signature'] = $this->digitalSignature($data);

        LetterRequest::create($data);

        return redirect()->route('letters.index')->with('success', 'Permohonan surat berhasil ditambahkan.');
    }

    public function show(LetterRequest $layanan_surat)
    {
        return view('letters.show', ['letter' => $layanan_surat->load(['resident', 'signer'])]);
    }

    public function print(LetterRequest $letter)
    {
        return view('letters.print', $this->letterViewData($letter));
    }

    public function pdf(LetterRequest $letter)
    {
        $pdf = Pdf::loadView('letters.pdf', $this->letterViewData($letter))
            ->setPaper('a4');

        return $pdf->stream(Str::slug($letter->letter_type . '-' . $letter->applicant_name) . '.pdf');
    }

    public function verify(string $code)
    {
        $letter = LetterRequest::with(['resident', 'signer'])
            ->where('verification_code', $code)
            ->firstOrFail();

        return view('letters.verify', compact('letter'));
    }

    private function generateLetterNumber(string $letterCode): string
    {
        $type = self::LETTER_TYPES[$letterCode];
        $sequence = LetterRequest::where('letter_code', $letterCode)
            ->whereYear('created_at', now()->year)
            ->count() + 1;

        return $type['classification'] . '/' . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT) . '/' . $type['code'] . '/' . now()->translatedFormat('m/Y');
    }

    private function buildTemplateData(?Resident $resident, array $data): array
    {
        return [
            'resident' => [
                'nik' => $resident?->nik,
                'kk' => $resident?->kk,
                'name' => $resident?->name ?: $data['applicant_name'],
                'birth_place' => $resident?->birth_place,
                'birth_date' => $resident?->birth_date?->format('Y-m-d'),
                'gender' => $resident?->gender,
                'religion' => $resident?->religion,
                'occupation' => $resident?->occupation,
                'education' => $resident?->education,
                'marital_status' => $resident?->marital_status,
                'address' => $resident?->address,
                'rt' => $resident?->rt,
                'rw' => $resident?->rw,
            ],
            'purpose' => $data['purpose'],
        ];
    }

    private function digitalSignature(array $data): string
    {
        return hash('sha256', implode('|', [
            $data['letter_number'],
            $data['verification_code'],
            $data['applicant_name'],
            $data['letter_code'],
            $data['requested_at'],
        ]));
    }

    private function letterViewData(LetterRequest $letter): array
    {
        $letter->load(['resident.familyCard', 'signer']);
        $this->ensureDigitalFields($letter);
        $verificationUrl = route('letters.verify', $letter->verification_code);

        return [
            'letter' => $letter,
            'resident' => $letter->resident,
            'qrCode' => $this->qrCode($verificationUrl),
            'verificationUrl' => $verificationUrl,
            'body' => $this->letterBody($letter),
        ];
    }

    private function ensureDigitalFields(LetterRequest $letter): void
    {
        if ($letter->letter_code && $letter->letter_number && $letter->verification_code && $letter->digital_signature) {
            return;
        }

        $letterCode = $letter->letter_code ?: $this->inferLetterCode($letter->letter_type);
        $data = [
            'letter_code' => $letterCode,
            'letter_number' => $letter->letter_number ?: $this->generateLetterNumber($letterCode),
            'verification_code' => $letter->verification_code ?: strtoupper(Str::random(10)),
            'applicant_name' => $letter->applicant_name,
            'requested_at' => $letter->requested_at?->format('Y-m-d') ?: now()->format('Y-m-d'),
        ];

        $letter->forceFill([
            'letter_code' => $data['letter_code'],
            'letter_number' => $data['letter_number'],
            'verification_code' => $data['verification_code'],
            'template_data' => $letter->template_data ?: $this->buildTemplateData($letter->resident, [
                'applicant_name' => $letter->applicant_name,
                'purpose' => $letter->purpose,
            ]),
            'signed_by' => $letter->signed_by ?: auth()->id(),
            'signed_at' => $letter->signed_at ?: now(),
            'digital_signature' => $letter->digital_signature ?: $this->digitalSignature($data),
        ])->save();
    }

    private function inferLetterCode(string $letterType): string
    {
        return collect(self::LETTER_TYPES)
            ->search(fn (array $type) => $type['name'] === $letterType) ?: 'domisili';
    }

    private function qrCode(string $content): string
    {
        $options = new QROptions([
            'outputInterface' => QRMarkupSVG::class,
            'outputBase64' => true,
            'scale' => 4,
        ]);

        return (new QRCode($options))->render($content);
    }

    private function letterBody(LetterRequest $letter): string
    {
        $name = $letter->applicant_name;
        $purpose = $letter->purpose;

        return match ($letter->letter_code) {
            'usaha' => "Nama tersebut benar merupakan warga Desa Sukamaju dan berdasarkan keterangan yang bersangkutan memiliki kegiatan usaha. Surat ini dibuat untuk {$purpose}.",
            'kematian' => "Berdasarkan data dan keterangan yang ada, nama tersebut tercatat dalam administrasi Desa Sukamaju untuk keperluan administrasi kematian. Surat ini dibuat untuk {$purpose}.",
            'pindah' => "Nama tersebut benar merupakan warga Desa Sukamaju dan mengajukan keterangan pindah domisili. Surat ini dibuat untuk {$purpose}.",
            'tidak_mampu' => "Nama tersebut benar merupakan warga Desa Sukamaju dan berdasarkan keterangan lingkungan termasuk keluarga yang membutuhkan dukungan administrasi. Surat ini dibuat untuk {$purpose}.",
            default => "Nama tersebut benar berdomisili di Desa Sukamaju, Kecamatan Harmoni, Kabupaten Sentosa. Surat ini dibuat untuk {$purpose}.",
        };
    }
}
