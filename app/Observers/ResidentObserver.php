<?php

namespace App\Observers;

use App\Models\Resident;
use App\Services\AuditLogger;

class ResidentObserver
{
    public function created(Resident $resident): void
    {
        AuditLogger::log('created', 'Menambahkan data penduduk ' . $resident->name, $resident, null, $resident->toArray());
    }

    public function updated(Resident $resident): void
    {
        AuditLogger::log('updated', 'Memperbarui data penduduk ' . $resident->name, $resident, $resident->getOriginal(), $resident->getChanges());
    }

    public function deleted(Resident $resident): void
    {
        AuditLogger::log('deleted', 'Menghapus data penduduk ' . $resident->name, $resident, $resident->toArray(), null);
    }
}
