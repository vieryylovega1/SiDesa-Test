<?php

namespace App\Http\Controllers;

use App\Models\FamilyCard;
use App\Models\Resident;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['cari', 'gender', 'education', 'status', 'rt', 'rw']);

        $residents = $this->filteredResidents($request)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('residents.index', [
            'residents' => $residents,
            'filters' => $filters,
            'filterOptions' => $this->filterOptions(),
        ]);
    }

    private function filteredResidents(Request $request)
    {
        $search = $request->string('cari')->toString();

        return Resident::query()
            ->with('familyCard')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('kk', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('gender'), fn ($query) => $query->where('gender', $request->string('gender')))
            ->when($request->filled('education'), fn ($query) => $query->where('education', $request->string('education')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('rt'), fn ($query) => $query->where('rt', $request->string('rt')))
            ->when($request->filled('rw'), fn ($query) => $query->where('rw', $request->string('rw')));
    }

    private function filterOptions(): array
    {
        return [
            'genders' => ['Laki-laki', 'Perempuan'],
            'educations' => ['Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK', 'D1/D2/D3', 'S1', 'S2', 'S3'],
            'statuses' => ['Aktif', 'Pindah', 'Meninggal'],
            'rt' => Resident::query()->select('rt')->distinct()->orderBy('rt')->pluck('rt'),
            'rw' => Resident::query()->select('rw')->distinct()->orderBy('rw')->pluck('rw'),
        ];
    }

    public function create()
    {
        return view('residents.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('residents', 'public');
        }

        unset($data['photo']);

        $resident = Resident::create($data);
        $this->syncFamilyCard($resident);

        return redirect()->route('residents.index')->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    private function validatedData(Request $request, ?Resident $resident = null): array
    {
        $residentId = $resident?->id;

        return $request->validate([
            'nik' => ['required', 'digits:16', 'unique:residents,nik'],
            'kk' => ['required', 'digits:16'],
            'family_relationship' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'birth_place' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date'],
            'religion' => ['required', 'string', 'max:50'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'education' => ['required', 'string', 'max:100'],
            'marital_status' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'max:3'],
            'rw' => ['required', 'max:3'],
            'hamlet' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ], [
            'nik.unique' => 'NIK sudah terdaftar.',
            'nik.digits' => 'NIK wajib 16 digit.',
            'kk.digits' => 'Nomor KK wajib 16 digit.',
            'photo.image' => 'Foto harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);
    }

    private function syncFamilyCard(Resident $resident): void
    {
        FamilyCard::updateOrCreate(
            ['number' => $resident->kk],
            [
                'head_name' => $resident->family_relationship === 'Kepala Keluarga'
                    ? $resident->name
                    : (FamilyCard::where('number', $resident->kk)->value('head_name') ?: $resident->name),
                'address' => $resident->address,
                'rt' => $resident->rt,
                'rw' => $resident->rw,
                'hamlet' => $resident->hamlet,
            ]
        );
    }

    private function validatedUpdateData(Request $request, Resident $resident): array
    {
        $data = $this->validatedData($request, $resident);
        $data['nik'] = $request->validate([
            'nik' => ['required', 'digits:16', 'unique:residents,nik,' . $resident->id],
        ])['nik'];

        return $data;
    }

    public function show(Resident $penduduk)
    {
        return view('residents.show', ['resident' => $penduduk->load('letterRequests')]);
    }

    public function edit(Resident $penduduk)
    {
        return view('residents.edit', ['resident' => $penduduk]);
    }

    public function update(Request $request, Resident $penduduk)
    {
        $data = $request->validate([
            'nik' => ['required', 'digits:16', 'unique:residents,nik,' . $penduduk->id],
            'kk' => ['required', 'digits:16'],
            'family_relationship' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'birth_place' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date'],
            'religion' => ['required', 'string', 'max:50'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'education' => ['required', 'string', 'max:100'],
            'marital_status' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'max:3'],
            'rw' => ['required', 'max:3'],
            'hamlet' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            if ($penduduk->photo_path) {
                Storage::disk('public')->delete($penduduk->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('residents', 'public');
        }

        unset($data['photo']);

        $penduduk->update($data);
        $this->syncFamilyCard($penduduk);

        return redirect()->route('residents.show', $penduduk)->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function destroy(Resident $penduduk)
    {
        if ($penduduk->photo_path) {
            Storage::disk('public')->delete($penduduk->photo_path);
        }

        $penduduk->delete();

        return redirect()->route('residents.index')->with('success', 'Data penduduk berhasil dihapus.');
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $fileName = 'data-penduduk-' . now()->format('Ymd-His') . '.csv';
        $residents = $this->filteredResidents($request)->orderBy('name')->get();

        return response()->streamDownload(function () use ($residents) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['NIK', 'No KK', 'Hubungan Keluarga', 'Nama', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat', 'RT', 'RW', 'Agama', 'Status Nikah', 'Pekerjaan', 'Pendidikan', 'Status']);

            foreach ($residents as $resident) {
                fputcsv($handle, [
                    $resident->nik,
                    $resident->kk,
                    $resident->family_relationship,
                    $resident->name,
                    $resident->gender,
                    $resident->birth_place,
                    $resident->birth_date->format('Y-m-d'),
                    $resident->address,
                    $resident->rt,
                    $resident->rw,
                    $resident->religion,
                    $resident->marital_status,
                    $resident->occupation,
                    $resident->education,
                    $resident->status,
                ]);
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportPdf(Request $request)
    {
        return view('residents.export-pdf', [
            'residents' => $this->filteredResidents($request)->orderBy('name')->get(),
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $imported = 0;
        $errors = [];
        $rowNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            $data = array_combine($header, $row);

            if (! $data || empty($data['NIK'])) {
                continue;
            }

            $payload = [
                'nik' => preg_replace('/\D/', '', $data['NIK']),
                'kk' => preg_replace('/\D/', '', $data['No KK'] ?? ''),
                'family_relationship' => $data['Hubungan Keluarga'] ?? 'Anggota Keluarga',
                'name' => $data['Nama'] ?? '',
                'gender' => $data['Jenis Kelamin'] ?? 'Laki-laki',
                'birth_place' => $data['Tempat Lahir'] ?? '',
                'birth_date' => $data['Tanggal Lahir'] ?? now()->format('Y-m-d'),
                'address' => $data['Alamat'] ?? '',
                'rt' => str_pad((string) ($data['RT'] ?? '000'), 3, '0', STR_PAD_LEFT),
                'rw' => str_pad((string) ($data['RW'] ?? '000'), 3, '0', STR_PAD_LEFT),
                'religion' => $data['Agama'] ?? 'Islam',
                'marital_status' => $data['Status Nikah'] ?? 'Belum Kawin',
                'occupation' => $data['Pekerjaan'] ?? null,
                'education' => $data['Pendidikan'] ?? 'Tidak Diisi',
                'status' => $data['Status'] ?? 'Aktif',
            ];

            $validator = Validator::make($payload, [
                'nik' => ['required', 'digits:16'],
                'kk' => ['required', 'digits:16'],
                'name' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'in:Laki-laki,Perempuan'],
                'birth_place' => ['required', 'string', 'max:100'],
                'birth_date' => ['required', 'date'],
                'address' => ['required', 'string', 'max:255'],
                'rt' => ['required', 'max:3'],
                'rw' => ['required', 'max:3'],
                'education' => ['required', 'string', 'max:100'],
                'status' => ['required', 'string', 'max:50'],
            ]);

            if ($validator->fails()) {
                $errors[] = 'Baris ' . $rowNumber . ': ' . $validator->errors()->first();
                continue;
            }

            $resident = Resident::updateOrCreate(
                ['nik' => $payload['nik']],
                $payload
            );

            $this->syncFamilyCard($resident);
            $imported++;
        }

        fclose($handle);

        AuditLogger::log('imported', "{$imported} data penduduk berhasil diimport.", null, null, ['errors' => $errors]);

        return redirect()
            ->route('residents.index')
            ->with('success', "{$imported} data penduduk berhasil diimport." . (count($errors) ? ' ' . count($errors) . ' baris gagal divalidasi.' : ''))
            ->with('import_errors', $errors);
    }
}
