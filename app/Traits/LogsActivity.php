<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public function logActivity($action, $targetType = null, $targetId = null, $description = null)
    {
        ActivityLog::create([
            'id' => Str::uuid(),
            'user_id' => Auth::user()->id,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
            'created_at' => now(),
        ]);
    }
}
