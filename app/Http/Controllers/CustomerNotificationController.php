<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerNotificationController extends Controller
{
    // Display customer notifications
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    // Mark one notification as read
    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return redirect()
            ->back()
            ->with('success', 'Notification marked as read.');
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        auth()->user()
            ->unreadNotifications()
            ->update(['read_at' => now()]);

        return redirect()
            ->back()
            ->with('success', 'All notifications marked as read.');
    }
}
