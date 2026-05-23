<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('cari')->toString();

        return view('audit-logs.index', [
            'logs' => AuditLog::with('user')
                ->when($search, fn ($query) => $query->where('description', 'like', "%{$search}%")->orWhere('event', 'like', "%{$search}%"))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'filters' => $request->only('cari'),
        ]);
    }
}
