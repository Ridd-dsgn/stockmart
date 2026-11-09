<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->q, fn ($query, $s) => $query->where(
                fn ($w) => $w->where('action', 'like', "%{$s}%")->orWhere('description', 'like', "%{$s}%")
            ))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('activity-logs.index', compact('logs'));
    }
}