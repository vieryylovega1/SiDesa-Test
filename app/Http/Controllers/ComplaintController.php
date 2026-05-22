<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $search = $request->string('cari')->toString();

        $complaints = Complaint::with(['reporter', 'replier'])
            ->when(! $this->canSeeAll($user), fn ($query) => $query->where('user_id', $user->id))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('reporter_name', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('complaints.index', [
            'complaints' => $complaints,
            'filters' => $request->only(['cari', 'status', 'category']),
            'categories' => $this->categories(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function create(Request $request)
    {
        return view('complaints.create', [
            'categories' => $this->categories(),
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reporter_name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'max:3072'],
        ], [
            'description.required' => 'Isi laporan wajib dijelaskan.',
            'photo.image' => 'Foto laporan harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 3 MB.',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('complaints', 'public');
        }

        $data['user_id'] = $request->user()->id;
        $data['ticket_number'] = $this->ticketNumber();
        $data['status'] = 'baru';

        $complaint = Complaint::create($data);

        return redirect()->route('complaints.show', $complaint)->with('success', 'Laporan berhasil dikirim. Nomor tiket: ' . $complaint->ticket_number);
    }

    public function show(Request $request, Complaint $complaint)
    {
        $this->authorizeComplaint($request, $complaint);

        return view('complaints.show', [
            'complaint' => $complaint->load(['reporter', 'replier']),
            'statuses' => $this->statuses(),
        ]);
    }

    public function updateResponse(Request $request, Complaint $complaint)
    {
        $data = $request->validate([
            'status' => ['required', 'in:baru,diproses,selesai,ditolak'],
            'admin_reply' => ['nullable', 'string', 'max:2000'],
        ]);

        $data['replied_by'] = $request->user()->id;
        $data['replied_at'] = now();

        $complaint->update($data);

        return back()->with('success', 'Status dan balasan laporan berhasil diperbarui.');
    }

    private function ticketNumber(): string
    {
        do {
            $ticket = 'LPR-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
        } while (Complaint::where('ticket_number', $ticket)->exists());

        return $ticket;
    }

    private function authorizeComplaint(Request $request, Complaint $complaint): void
    {
        if ($this->canSeeAll($request->user()) || $complaint->user_id === $request->user()->id) {
            return;
        }

        abort(403, 'Anda tidak memiliki izin untuk melihat laporan ini.');
    }

    private function canSeeAll($user): bool
    {
        return $user->canAccess('complaints.manage') || $user->hasRole('kepala_desa');
    }

    private function categories(): array
    {
        return ['Infrastruktur', 'Kebersihan', 'Keamanan', 'Pelayanan', 'Sosial', 'Lainnya'];
    }

    private function statuses(): array
    {
        return [
            'baru' => 'Baru',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ];
    }
}
