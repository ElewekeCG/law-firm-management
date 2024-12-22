<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Notifications extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(5);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['read_at' => now()]);

        // Redirect to associated URL
        return redirect($notification->data['url'] ?? route('dashboard'));
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->update(['read_at' => now()]);
        return back()->with('success', 'All notifications marked as read');
    }

    public function getUnreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications->count()
        ]);
    }
}
