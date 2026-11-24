<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::query()
            ->when($request->aksi, fn ($q, $a) => $q->where('aksi', $a))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('dinas.log', compact('logs'));
    }
}