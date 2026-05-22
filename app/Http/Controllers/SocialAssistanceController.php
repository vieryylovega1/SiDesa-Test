<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\SocialAssistanceCategory;
use App\Models\SocialAssistanceHistory;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SocialAssistanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('cari')->toString();

        $recipients = SocialAssistanceRecipient::with(['resident', 'category'])
            ->withCount('histories')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('resident', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('kk', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->where('social_assistance_category_id', $request->integer('category')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('social-assistance.index', [
            'recipients' => $recipients,
            'categories' => SocialAssistanceCategory::orderBy('name')->get(),
            'filters' => $request->only(['cari', 'category', 'status']),
        ]);
    }

    public function create()
    {
        return view('social-assistance.create', [
            'residents' => Resident::orderBy('name')->get(),
            'categories' => SocialAssistanceCategory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'resident_id' => ['required', 'exists:residents,id'],
            'social_assistance_category_id' => [
                'required',
                'exists:social_assistance_categories,id',
                Rule::unique('social_assistance_recipients')->where(fn ($query) => $query
                    ->where('resident_id', $request->integer('resident_id'))
                    ->where('social_assistance_category_id', $request->integer('social_assistance_category_id'))),
            ],
            'status' => ['required', 'in:active,inactive,suspended'],
            'registered_at' => ['required', 'date'],
            'eligibility_note' => ['required', 'string', 'max:1000'],
        ], [
            'social_assistance_category_id.unique' => 'Penduduk ini sudah terdaftar pada kategori bantuan yang sama.',
            'eligibility_note.required' => 'Catatan kelayakan wajib diisi untuk validasi penerima.',
        ]);

        $data['created_by'] = auth()->id();

        SocialAssistanceRecipient::create($data);

        return redirect()->route('social-assistance.index')->with('success', 'Penerima bantuan berhasil ditambahkan.');
    }

    public function show(SocialAssistanceRecipient $socialAssistance)
    {
        return view('social-assistance.show', [
            'recipient' => $socialAssistance->load(['resident.familyCard', 'category', 'creator', 'histories.recorder']),
        ]);
    }

    public function edit(SocialAssistanceRecipient $socialAssistance)
    {
        return view('social-assistance.edit', [
            'recipient' => $socialAssistance,
            'residents' => Resident::orderBy('name')->get(),
            'categories' => SocialAssistanceCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, SocialAssistanceRecipient $socialAssistance)
    {
        $data = $request->validate([
            'resident_id' => ['required', 'exists:residents,id'],
            'social_assistance_category_id' => [
                'required',
                'exists:social_assistance_categories,id',
                Rule::unique('social_assistance_recipients')->ignore($socialAssistance->id)->where(fn ($query) => $query
                    ->where('resident_id', $request->integer('resident_id'))
                    ->where('social_assistance_category_id', $request->integer('social_assistance_category_id'))),
            ],
            'status' => ['required', 'in:active,inactive,suspended'],
            'registered_at' => ['required', 'date'],
            'eligibility_note' => ['required', 'string', 'max:1000'],
        ]);

        $socialAssistance->update($data);

        return redirect()->route('social-assistance.show', $socialAssistance)->with('success', 'Data penerima bantuan berhasil diperbarui.');
    }

    public function destroy(SocialAssistanceRecipient $socialAssistance)
    {
        $socialAssistance->delete();

        return redirect()->route('social-assistance.index')->with('success', 'Data penerima bantuan berhasil dihapus.');
    }

    public function storeHistory(Request $request, SocialAssistanceRecipient $socialAssistance)
    {
        $data = $request->validate([
            'distributed_at' => ['required', 'date'],
            'period' => ['nullable', 'string', 'max:50'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:disalurkan,ditunda,dibatalkan'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['recorded_by'] = auth()->id();

        $socialAssistance->histories()->create($data);

        return back()->with('success', 'Riwayat bantuan berhasil ditambahkan.');
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:social_assistance_categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['required', 'boolean'],
        ]);

        $data['slug'] = str($data['name'])->slug()->toString();

        SocialAssistanceCategory::create($data);

        return back()->with('success', 'Kategori bantuan berhasil ditambahkan.');
    }
}
