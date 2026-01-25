<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    protected $primaryKey = 'notification_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'notification_id',
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    public static function getForUser($userId, $limit = 10)
    {
        $limit = (int) $limit;
        return DB::select("
            SELECT * FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT {$limit}
        ", [$userId]);
    }

    public static function getUnreadCount($userId)
    {
        $result = DB::selectOne("
            SELECT COUNT(*) as count FROM notifications
            WHERE user_id = ? AND is_read = 0
        ", [$userId]);

        return $result->count ?? 0;
    }

    public static function create($userId, $type, $title, $message = null, $link = null)
    {
        DB::insert("
            INSERT INTO notifications (user_id, type, title, message, link)
            VALUES (?, ?, ?, ?, ?)
        ", [$userId, $type, $title, $message, $link]);
    }

    public static function markAsRead($notificationId, $userId)
    {
        DB::update("
            UPDATE notifications
            SET is_read = 1
            WHERE notification_id = ? AND user_id = ?
        ", [$notificationId, $userId]);
    }

    public static function markAllAsRead($userId)
    {
        DB::update("
            UPDATE notifications
            SET is_read = 1
            WHERE user_id = ? AND is_read = 0
        ", [$userId]);
    }
}
