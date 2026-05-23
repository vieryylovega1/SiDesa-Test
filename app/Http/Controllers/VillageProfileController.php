<?php

namespace App\Http\Controllers;

use App\Models\VillageProfile;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class VillageProfileController extends Controller
{
    public function edit()
    {
        return view('settings.village-profile', [
            'profile' => VillageProfile::current(),
        ]);
    }

    public function update(Request $request)
    {
        $profile = VillageProfile::current();
        $oldValues = $profile->toArray();

        $data = $request->validate([
            'village_name' => ['required', 'string', 'max:120'],
            'district' => ['required', 'string', 'max:120'],
            'regency' => ['required', 'string', 'max:120'],
            'province' => ['required', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120'],
            'website' => ['nullable', 'url', 'max:160'],
            'head_name' => ['required', 'string', 'max:120'],
            'head_nip' => ['nullable', 'string', 'max:50'],
        ]);

        $profile->update($data);

        AuditLogger::log('updated', 'Memperbarui profil desa', $profile, $oldValues, $profile->fresh()->toArray());

        return back()->with('success', 'Profil desa berhasil diperbarui.');
    }
}
