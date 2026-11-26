<?php

namespace App\Support;

use App\Models\User;
use App\Notifications\AdminAlertNotification;

class AdminNotificationService
{
    public static function send(
        string $title,
        string $message,
        string $type = 'general',
        ?string $url = null
    ): void {
        $admins = User::role('Super Admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(
                new AdminAlertNotification(
                    $title,
                    $message,
                    $type,
                    $url
                )
            );
        }
    }
}
