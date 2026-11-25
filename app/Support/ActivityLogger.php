<?php

namespace App\Support;

use App\Models\ActivityLog;

class ActivityLogger
{
    public static function log(
        string $action,
        string $description,
        $subject = null
    ): void {
        $user = auth()->user();

        ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'user_email' => $user?->email,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->getKey(),
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}
