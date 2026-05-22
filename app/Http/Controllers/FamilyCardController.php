<?php

namespace App\Http\Controllers;

use App\Models\FamilyCard;
use App\Models\Resident;
use Illuminate\Http\Request;

class FamilyCardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('cari')->toString();

        $familyCards = FamilyCard::query()
            ->withCount('residents')
            ->when($search, function ($query) use ($search) {
                $query->where('number', 'like', "%{$search}%")
                    ->orWhere('head_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('family-cards.index', compact('familyCards', 'search'));
    }

    public function create()
    {
        return view('family-cards.create');
    }

    public function store(Request $request)
    {
        FamilyCard::create($this->validatedData($request));

        return redirect()->route('family-cards.index')->with('success', 'Kartu keluarga berhasil ditambahkan.');
    }

    public function show(FamilyCard $familyCard)
    {
        return view('family-cards.show', [
            'familyCard' => $familyCard->load(['residents' => fn ($query) => $query->orderByRaw("family_relationship = 'Kepala Keluarga' DESC")->orderBy('name')]),
        ]);
    }

    public function edit(FamilyCard $familyCard)
    {
        return view('family-cards.edit', compact('familyCard'));
    }

    public function update(Request $request, FamilyCard $familyCard)
    {
        $oldNumber = $familyCard->number;

        $familyCard->update($this->validatedData($request, $familyCard));

        Resident::where('kk', $oldNumber)->update([
            'kk' => $familyCard->number,
            'address' => $familyCard->address,
            'rt' => $familyCard->rt,
            'rw' => $familyCard->rw,
            'hamlet' => $familyCard->hamlet,
        ]);

        return redirect()->route('family-cards.show', $familyCard)->with('success', 'Kartu keluarga berhasil diperbarui.');
    }

    public function destroy(FamilyCard $familyCard)
    {
        if ($familyCard->residents()->exists()) {
            return back()->withErrors(['kk' => 'KK tidak bisa dihapus karena masih memiliki anggota keluarga.']);
        }

        $familyCard->delete();

        return redirect()->route('family-cards.index')->with('success', 'Kartu keluarga berhasil dihapus.');
    }

    public function sync()
    {
        Resident::query()
            ->orderByRaw("family_relationship = 'Kepala Keluarga' DESC")
            ->orderBy('name')
            ->get()
            ->groupBy('kk')
            ->each(function ($members, string $number) {
                $head = $members->firstWhere('family_relationship', 'Kepala Keluarga') ?: $members->first();

                FamilyCard::updateOrCreate(
                    ['number' => $number],
                    [
                        'head_name' => $head->name,
                        'address' => $head->address,
                        'rt' => $head->rt,
                        'rw' => $head->rw,
                        'hamlet' => $head->hamlet,
                    ]
                );
            });

        return redirect()->route('family-cards.index')->with('success', 'Data KK berhasil disinkronkan dari data penduduk.');
    }

    private function validatedData(Request $request, ?FamilyCard $familyCard = null): array
    {
        return $request->validate([
            'number' => ['required', 'digits:16', 'unique:family_cards,number,' . $familyCard?->id],
            'head_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'rt' => ['nullable', 'max:3'],
            'rw' => ['nullable', 'max:3'],
            'hamlet' => ['nullable', 'string', 'max:100'],
        ], [
            'number.digits' => 'Nomor KK wajib 16 digit.',
            'number.unique' => 'Nomor KK sudah terdaftar.',
        ]);
    }
}
