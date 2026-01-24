<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    protected $primaryKey = 'event_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'org_id',
        'title',
        'description',
        'event_date',
        'event_duration',
        'venue',
        'status',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getEventsByOrg($orgId)
    {
        return DB::select(
            "SELECT * FROM events WHERE org_id = ? ORDER BY event_date DESC",
            [$orgId]
        );
    }

    public static function getUpcomingEvents($orgId = null)
    {
        if ($orgId) {
            return DB::select(
                "SELECT * FROM events 
                 WHERE org_id = ? AND event_date >= NOW() AND status IN ('Pending', 'Upcoming')
                 ORDER BY event_date ASC",
                [$orgId]
            );
        }

        return DB::select(
            "SELECT * FROM events 
             WHERE event_date >= NOW() AND status IN ('Pending', 'Upcoming')
             ORDER BY event_date ASC"
        );
    }

    public static function getPastEvents($orgId = null)
    {
        if ($orgId) {
            return DB::select(
                "SELECT * FROM events 
                 WHERE org_id = ? AND status IN ('Done', 'Cancelled')
                 ORDER BY event_date DESC",
                [$orgId]
            );
        }

        return DB::select(
            "SELECT * FROM events 
             WHERE status IN ('Done', 'Cancelled')
             ORDER BY event_date DESC"
        );
    }

    public static function findById($eventId)
    {
        return DB::selectOne(
            "SELECT * FROM events WHERE event_id = ? LIMIT 1",
            [$eventId]
        );
    }

    public static function createEvent($data)
    {
        DB::insert(
            "INSERT INTO events (org_id, title, description, event_date, event_duration, venue, status, created_by, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            [
                $data['org_id'],
                $data['title'],
                $data['description'] ?? null,
                $data['event_date'],
                $data['event_duration'] ?? 4,
                $data['venue'] ?? null,
                $data['status'] ?? 'Pending',
                $data['created_by']
            ]
        );

        return DB::selectOne(
            "SELECT * FROM events WHERE org_id = ? AND title = ? ORDER BY created_at DESC LIMIT 1",
            [$data['org_id'], $data['title']]
        );
    }

    public static function updateEvent($eventId, $data)
    {
        $fields = [];
        $values = [];

        if (isset($data['title'])) {
            $fields[] = 'title = ?';
            $values[] = $data['title'];
        }

        if (isset($data['description'])) {
            $fields[] = 'description = ?';
            $values[] = $data['description'];
        }

        if (isset($data['event_date'])) {
            $fields[] = 'event_date = ?';
            $values[] = $data['event_date'];
        }

        if (isset($data['event_duration'])) {
            $fields[] = 'event_duration = ?';
            $values[] = $data['event_duration'];
        }

        if (isset($data['venue'])) {
            $fields[] = 'venue = ?';
            $values[] = $data['venue'];
        }

        if (isset($data['status'])) {
            $fields[] = 'status = ?';
            $values[] = $data['status'];
        }

        $values[] = $eventId;

        if (!empty($fields)) {
            DB::update(
                "UPDATE events SET " . implode(', ', $fields) . " WHERE event_id = ?",
                $values
            );
        }

        return self::findById($eventId);
    }

    public static function deleteEvent($eventId)
    {
        return DB::delete(
            "DELETE FROM events WHERE event_id = ?",
            [$eventId]
        );
    }

    public static function getEventAttendees($eventId)
    {
        return DB::select(
            "SELECT u.*, ea.status, ea.remarks
             FROM event_attendance ea
             INNER JOIN users u ON ea.user_id = u.user_id
             WHERE ea.event_id = ?
             ORDER BY u.full_name ASC",
            [$eventId]
        );
    }

    public static function getEventRSVPCount($eventId)
    {
        $result = DB::selectOne(
            "SELECT COUNT(*) as count FROM event_attendance 
             WHERE event_id = ? AND status = 'RSVP'",
            [$eventId]
        );

        return $result ? $result->count : 0;
    }

    public static function getEventAttendedCount($eventId)
    {
        $result = DB::selectOne(
            "SELECT COUNT(*) as count FROM event_attendance 
             WHERE event_id = ? AND status = 'Present'",
            [$eventId]
        );

        return $result ? $result->count : 0;
    }

    public static function getTotalAttendeesCount($eventId)
    {
        $result = DB::selectOne(
            "SELECT COUNT(*) as count FROM event_attendance 
             WHERE event_id = ?",
            [$eventId]
        );

        return $result ? $result->count : 0;
    }
}
