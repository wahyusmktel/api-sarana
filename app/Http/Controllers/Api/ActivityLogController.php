<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        return ActivityLog::with('user:id,username,email')
            ->latest('created_at')
            ->limit(50)
            ->get();
    }
}
