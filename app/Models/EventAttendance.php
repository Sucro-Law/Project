<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EventAttendance extends Model
{
    protected $table = 'event_attendance';
    
    protected $primaryKey = 'attendance_id';
    
    public $incrementing = false;
    
    protected $keyType = 'string';
    
    public $timestamps = false;
    
    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'remarks',
    ];

    public static function createRSVP($eventId, $userId, $status = 'RSVP', $remarks = null)
    {
        // Check if RSVP already exists
        $existing = DB::selectOne(
            "SELECT * FROM event_attendance WHERE event_id = ? AND user_id = ?",
            [$eventId, $userId]
        );
        
        if ($existing) {
            // Update existing RSVP
            DB::update(
                "UPDATE event_attendance SET status = ?, remarks = ? WHERE attendance_id = ?",
                [$status, $remarks, $existing->attendance_id]
            );
            
            return DB::selectOne(
                "SELECT * FROM event_attendance WHERE attendance_id = ?",
                [$existing->attendance_id]
            );
        }
        
        // Create new RSVP
        DB::insert(
            "INSERT INTO event_attendance (event_id, user_id, status, remarks) 
             VALUES (?, ?, ?, ?)",
            [$eventId, $userId, $status, $remarks]
        );
        
        return DB::selectOne(
            "SELECT * FROM event_attendance WHERE event_id = ? AND user_id = ? LIMIT 1",
            [$eventId, $userId]
        );
    }

    public static function markAttendance($eventId, $userId, $status = 'Present', $remarks = null)
    {
        $existing = DB::selectOne(
            "SELECT * FROM event_attendance WHERE event_id = ? AND user_id = ?",
            [$eventId, $userId]
        );
        
        if ($existing) {
            DB::update(
                "UPDATE event_attendance 
                 SET status = ?, remarks = ? 
                 WHERE attendance_id = ?",
                [$status, $remarks, $existing->attendance_id]
            );
        } else {
            DB::insert(
                "INSERT INTO event_attendance (event_id, user_id, status, remarks) 
                 VALUES (?, ?, ?, ?)",
                [$eventId, $userId, $status, $remarks]
            );
        }
        
        return DB::selectOne(
            "SELECT * FROM event_attendance WHERE event_id = ? AND user_id = ? LIMIT 1",
            [$eventId, $userId]
        );
    }

    public static function getUserRSVP($eventId, $userId)
    {
        return DB::selectOne(
            "SELECT * FROM event_attendance WHERE event_id = ? AND user_id = ? LIMIT 1",
            [$eventId, $userId]
        );
    }

    public static function cancelRSVP($eventId, $userId)
    {
        return DB::delete(
            "DELETE FROM event_attendance WHERE event_id = ? AND user_id = ?",
            [$eventId, $userId]
        );
    }

    public static function getUserAttendedEvents($userId)
    {
        return DB::select(
            "SELECT e.*, o.org_name, ea.status, ea.remarks
             FROM event_attendance ea
             INNER JOIN events e ON ea.event_id = e.event_id
             INNER JOIN organizations o ON e.org_id = o.org_id
             WHERE ea.user_id = ? AND ea.status = 'Present'
             ORDER BY e.event_date DESC",
            [$userId]
        );
    }

    public static function getUserUpcomingEvents($userId)
    {
        return DB::select(
            "SELECT e.*, o.org_name, ea.status
             FROM event_attendance ea
             INNER JOIN events e ON ea.event_id = e.event_id
             INNER JOIN organizations o ON e.org_id = o.org_id
             WHERE ea.user_id = ? 
             AND ea.status = 'RSVP'
             AND e.event_date >= NOW()
             AND e.status IN ('Pending', 'Upcoming')
             ORDER BY e.event_date ASC",
            [$userId]
        );
    }
    
    public static function getUserEventHistory($userId)
    {
        return DB::select(
            "SELECT e.*, o.org_name, ea.status, ea.remarks
             FROM event_attendance ea
             INNER JOIN events e ON ea.event_id = e.event_id
             INNER JOIN organizations o ON e.org_id = o.org_id
             WHERE ea.user_id = ?
             ORDER BY e.event_date DESC",
            [$userId]
        );
    }
}