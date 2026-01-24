<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $user = Auth::user();
        $notifications = Notification::getForUser($user->user_id, 20);
        $unreadCount = Notification::getUnreadCount($user->user_id);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    public function markAsRead(Request $request, $notificationId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $user = Auth::user();
        Notification::markAsRead($notificationId, $user->user_id);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $user = Auth::user();
        Notification::markAllAsRead($user->user_id);

        return response()->json(['success' => true]);
    }
}
