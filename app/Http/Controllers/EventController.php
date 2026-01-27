<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use App\Http\Controllers\Traits\HasSidebarData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    use HasSidebarData;

    private function parseUserName($fullName)
    {
        $nameParts = explode(' ', trim($fullName));

        if (count($nameParts) === 1) {
            return [
                'first_name' => $nameParts[0],
                'middle_name' => '',
                'last_name' => ''
            ];
        } elseif (count($nameParts) === 2) {
            return [
                'first_name' => $nameParts[0],
                'middle_name' => '',
                'last_name' => $nameParts[1]
            ];
        } else {
            return [
                'first_name' => $nameParts[0],
                'middle_name' => implode(' ', array_slice($nameParts, 1, -1)),
                'last_name' => $nameParts[count($nameParts) - 1]
            ];
        }
    }

    public function index()
    {
        // Check if user is officer or adviser
        $isOfficerOrAdviser = false;
        if (Auth::check()) {
            $user = Auth::user();
            $isOfficer = DB::selectOne(
                "SELECT m.membership_id FROM memberships m
                 WHERE m.user_id = ? AND m.membership_role = 'Officer' AND m.status = 'Active' LIMIT 1",
                [$user->user_id]
            );
            $isAdviser = DB::selectOne(
                "SELECT adviser_id FROM org_advisers WHERE user_id = ? LIMIT 1",
                [$user->user_id]
            );
            $isOfficerOrAdviser = $isOfficer || $isAdviser || $user->account_type === 'Faculty';
        }

        // Get all upcoming events (include Pending for officers/advisers)
        // Only show events from organizations the user is a member of
        $statusFilter = $isOfficerOrAdviser ? "IN ('Pending', 'Upcoming')" : "= 'Upcoming'";

        if (Auth::check()) {
            $user = Auth::user();
            $upcomingEvents = DB::select("
                SELECT
                    e.*,
                    o.org_name,
                    o.org_id,
                    u.full_name as creator_name,
                    (SELECT COUNT(*) FROM event_attendance ea WHERE ea.event_id = e.event_id AND ea.status = 'RSVP') as rsvp_count,
                    (SELECT COUNT(*) FROM event_likes el WHERE el.event_id = e.event_id) as likes_count,
                    m.membership_id as is_member_of_org
                FROM events e
                INNER JOIN organizations o ON e.org_id = o.org_id
                LEFT JOIN users u ON e.created_by = u.user_id
                LEFT JOIN memberships m ON e.org_id = m.org_id AND m.user_id = ? AND m.status = 'Active'
                WHERE e.event_date >= NOW()
                AND e.status {$statusFilter}
                ORDER BY e.created_at ASC
            ", [$user->user_id]);
        } else {
            $upcomingEvents = [];
        }

        // Get past events (only from user's organizations)
        if (Auth::check()) {
            $user = Auth::user();
            $pastEvents = DB::select("
                SELECT
                    e.*,
                    o.org_name,
                    o.org_id,
                    u.full_name as creator_name,
                    (SELECT COUNT(*) FROM event_attendance ea WHERE ea.event_id = e.event_id AND ea.status IN ('RSVP', 'Present')) as rsvp_count
                FROM events e
                INNER JOIN organizations o ON e.org_id = o.org_id
                LEFT JOIN users u ON e.created_by = u.user_id
                INNER JOIN memberships m ON e.org_id = m.org_id AND m.user_id = ? AND m.status = 'Active'
                WHERE e.status IN ('Done', 'Cancelled')
                ORDER BY e.event_date DESC
                LIMIT 10
            ", [$user->user_id]);
        } else {
            $pastEvents = [];
        }

        // Format events
        foreach (array_merge($upcomingEvents, $pastEvents) as $event) {
            // Format date
            $event->formatted_date = date('m/d/y', strtotime($event->event_date));
            $event->formatted_full_date = date('F j, Y', strtotime($event->event_date));

            // Generate org acronym
            preg_match_all('/\b([A-Z])/u', $event->org_name, $matches);
            $acronym = implode('', $matches[1]);
            $event->org_short_name = !empty($acronym) && strlen($acronym) >= 2
                ? $acronym
                : strtoupper(substr($event->org_name, 0, 3));

            // Check if user has RSVP'd and liked
            if (Auth::check()) {
                $userRsvp = EventAttendance::getUserRSVP($event->event_id, Auth::id());
                $event->user_rsvp_status = $userRsvp ? $userRsvp->status : null;
                $userLike = DB::selectOne("SELECT like_id FROM event_likes WHERE event_id = ? AND user_id = ?", [$event->event_id, Auth::user()->user_id]);
                $event->user_liked = $userLike ? true : false;
            } else {
                $event->user_rsvp_status = null;
                $event->user_liked = false;
            }
        }

        $sidebarData = $this->getSidebarData();

        // Parse user name for the form
        $firstName = '';
        $middleName = '';
        $lastName = '';

        if (Auth::check()) {
            $parsedName = $this->parseUserName(Auth::user()->full_name);
            $firstName = $parsedName['first_name'];
            $middleName = $parsedName['middle_name'];
            $lastName = $parsedName['last_name'];
        }

        $query = '';
        return view('pages.events', compact('upcomingEvents', 'pastEvents', 'sidebarData', 'firstName', 'middleName', 'lastName', 'isOfficerOrAdviser', 'query'));
    }

    public function show($eventId)
    {
        $event = DB::selectOne("
            SELECT 
                e.*,
                o.org_name,
                o.org_id,
                u.full_name as creator_name,
                u.email as creator_email
            FROM events e
            INNER JOIN organizations o ON e.org_id = o.org_id
            LEFT JOIN users u ON e.created_by = u.user_id
            WHERE e.event_id = ?
        ", [$eventId]);

        if (!$event) {
            abort(404, 'Event not found');
        }

        // Get event statistics
        $stats = [
            'rsvp_count' => Event::getEventRSVPCount($eventId),
            'attended_count' => Event::getEventAttendedCount($eventId),
            'total_attendees' => Event::getTotalAttendeesCount($eventId)
        ];

        // Get attendees
        $attendees = Event::getEventAttendees($eventId);

        // Check user's RSVP status
        $userRsvp = null;
        if (Auth::check()) {
            $userRsvp = EventAttendance::getUserRSVP($eventId, Auth::id());
        }

        $sidebarData = $this->getSidebarData();

        return view('pages.event-detail', compact('event', 'stats', 'attendees', 'userRsvp', 'sidebarData'));
    }

    public function rsvp(Request $request, $eventId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to RSVP', 'redirect' => route('login')], 401);
        }

        $event = Event::findById($eventId);
        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        if (!in_array($event->status, ['Pending', 'Upcoming'])) {
            return response()->json(['success' => false, 'message' => 'Cannot RSVP to this event. Event has already started or ended.'], 400);
        }

        $user = Auth::user();

        $isMember = DB::selectOne("
            SELECT membership_id FROM memberships
            WHERE user_id = ? AND org_id = ? AND status = 'Active'
        ", [$user->user_id, $event->org_id]);

        if (!$isMember) {
            return response()->json(['success' => false, 'message' => 'You must be a member of this organization to RSVP'], 403);
        }

        try {
            EventAttendance::createRSVP($eventId, $user->user_id, 'RSVP');
            return response()->json(['success' => true, 'message' => 'Successfully RSVP\'d to the event!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to RSVP: ' . $e->getMessage()], 500);
        }
    }

    public function cancelRsvp($eventId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        try {
            $user = Auth::user();

            $result = EventAttendance::cancelRSVP($eventId, $user->user_id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'RSVP cancelled successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No RSVP found to cancel'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel RSVP: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createEvent(Request $request, $orgId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please login first');
        }

        $user = Auth::user();

        // Check if user is officer or adviser of the organization
        $isOfficer = DB::selectOne(
            "SELECT membership_id FROM memberships 
             WHERE org_id = ? AND user_id = ? AND status = 'Active' AND membership_role = 'Officer'
             LIMIT 1",
            [$orgId, $user->user_id]
        );

        $isAdviser = DB::selectOne(
            "SELECT adviser_id FROM org_advisers WHERE org_id = ? AND user_id = ? LIMIT 1",
            [$orgId, $user->user_id]
        );

        if (!$isOfficer && !$isAdviser && $user->account_type !== 'Faculty') {
            return back()->with('error', 'You do not have permission to create events');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'event_date' => 'required|date|after:now',
            'event_duration' => 'nullable|integer|min:1|max:24',
            'venue' => 'nullable|string|max:100',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('event_image')) {
                $imagePath = $request->file('event_image')->store('events', 'public');
            }

            $eventData = [
                'org_id' => $orgId,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'event_date' => $validated['event_date'],
                'event_duration' => $validated['event_duration'] ?? 4,
                'venue' => $validated['venue'] ?? null,
                'created_by' => $user->user_id
            ];

            // The trigger will automatically set status based on user type
            Event::createEvent($eventData);

            return back()->with('success', 'Event created successfully! ' .
                ($user->account_type === 'Faculty' ? 'Event is now live.' : 'Waiting for adviser approval.'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create event: ' . $e->getMessage());
        }
    }

    public function updateEvent(Request $request, $eventId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please login first');
        }

        $event = Event::findById($eventId);
        if (!$event) {
            return back()->with('error', 'Event not found');
        }

        $user = Auth::user();

        // Check permissions
        $isOfficer = DB::selectOne(
            "SELECT membership_id FROM memberships 
             WHERE org_id = ? AND user_id = ? AND status = 'Active' AND membership_role = 'Officer'
             LIMIT 1",
            [$event->org_id, $user->user_id]
        );

        $isAdviser = DB::selectOne(
            "SELECT adviser_id FROM org_advisers WHERE org_id = ? AND user_id = ? LIMIT 1",
            [$event->org_id, $user->user_id]
        );

        if (!$isOfficer && !$isAdviser && $event->created_by !== $user->user_id) {
            return back()->with('error', 'You do not have permission to update this event');
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'event_duration' => 'nullable|integer|min:1|max:24',
            'venue' => 'nullable|string|max:100',
            'status' => 'nullable|in:Pending,Upcoming,Ongoing,Done,Cancelled'
        ]);

        try {
            Event::updateEvent($eventId, $validated);
            return back()->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update event: ' . $e->getMessage());
        }
    }

    public function deleteEvent($eventId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please login first');
        }

        $event = Event::findById($eventId);
        if (!$event) {
            return back()->with('error', 'Event not found');
        }

        $user = Auth::user();

        // Check permissions
        $isOfficer = DB::selectOne(
            "SELECT membership_id FROM memberships 
             WHERE org_id = ? AND user_id = ? AND status = 'Active' AND membership_role = 'Officer'
             LIMIT 1",
            [$event->org_id, $user->user_id]
        );

        $isAdviser = DB::selectOne(
            "SELECT adviser_id FROM org_advisers WHERE org_id = ? AND user_id = ? LIMIT 1",
            [$event->org_id, $user->user_id]
        );

        if (!$isOfficer && !$isAdviser && $event->created_by !== $user->user_id) {
            return back()->with('error', 'You do not have permission to delete this event');
        }

        try {
            Event::deleteEvent($eventId);
            return back()->with('success', 'Event cancelled successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel event: ' . $e->getMessage());
        }
    }

    public function approveEvent(Request $request, $eventId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please login first');
        }

        $event = Event::findById($eventId);
        if (!$event) {
            return back()->with('error', 'Event not found');
        }

        $user = Auth::user();

        // Only advisers can approve events
        $isAdviser = DB::selectOne(
            "SELECT adviser_id FROM org_advisers WHERE org_id = ? AND user_id = ? LIMIT 1",
            [$event->org_id, $user->user_id]
        );

        if (!$isAdviser && $user->account_type !== 'Faculty') {
            return back()->with('error', 'Only advisers can approve events');
        }

        try {
            Event::updateEvent($eventId, ['status' => 'Upcoming']);
            return back()->with('success', 'Event approved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve event: ' . $e->getMessage());
        }
    }

    public function rejectEvent(Request $request, $eventId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please login first');
        }

        $event = Event::findById($eventId);
        if (!$event) {
            return back()->with('error', 'Event not found');
        }

        $user = Auth::user();

        // Only advisers can reject events
        $isAdviser = DB::selectOne(
            "SELECT adviser_id FROM org_advisers WHERE org_id = ? AND user_id = ? LIMIT 1",
            [$event->org_id, $user->user_id]
        );

        if (!$isAdviser && $user->account_type !== 'Faculty') {
            return back()->with('error', 'Only advisers can reject events');
        }

        try {
            Event::updateEvent($eventId, ['status' => 'Cancelled']);
            return back()->with('success', 'Event rejected successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject event: ' . $e->getMessage());
        }
    }

    public function likeEvent($eventId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        $user = Auth::user();

        try {
            // Check if already liked
            $existingLike = DB::selectOne(
                "SELECT like_id FROM event_likes WHERE event_id = ? AND user_id = ?",
                [$eventId, $user->user_id]
            );

            if ($existingLike) {
                // Unlike
                DB::delete("DELETE FROM event_likes WHERE event_id = ? AND user_id = ?", [$eventId, $user->user_id]);
                $liked = false;
            } else {
                // Like
                DB::insert("INSERT INTO event_likes (event_id, user_id) VALUES (?, ?)", [$eventId, $user->user_id]);
                $liked = true;
            }

            // Get new like count
            $likeCount = DB::selectOne("SELECT COUNT(*) as count FROM event_likes WHERE event_id = ?", [$eventId])->count;

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likeCount' => $likeCount
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $sidebarData = $this->getSidebarData();

        // Check if user is officer or adviser
        $isOfficerOrAdviser = false;
        if (Auth::check()) {
            $user = Auth::user();
            $isOfficer = DB::selectOne(
                "SELECT m.membership_id FROM memberships m
                 WHERE m.user_id = ? AND m.membership_role = 'Officer' AND m.status = 'Active' LIMIT 1",
                [$user->user_id]
            );
            $isAdviser = DB::selectOne(
                "SELECT adviser_id FROM org_advisers WHERE user_id = ? LIMIT 1",
                [$user->user_id]
            );
            $isOfficerOrAdviser = $isOfficer || $isAdviser || $user->account_type === 'Faculty';
        }

        $statusFilter = $isOfficerOrAdviser ? "IN ('Pending', 'Upcoming')" : "= 'Upcoming'";

        // Only show events from organizations the user is a member of
        if (Auth::check()) {
            $user = Auth::user();
            $upcomingEvents = DB::select("
                SELECT
                    e.*,
                    o.org_name,
                    o.org_id,
                    u.full_name as creator_name,
                    (SELECT COUNT(*) FROM event_attendance ea WHERE ea.event_id = e.event_id AND ea.status = 'RSVP') as rsvp_count,
                    (SELECT COUNT(*) FROM event_likes el WHERE el.event_id = e.event_id) as likes_count
                FROM events e
                INNER JOIN organizations o ON e.org_id = o.org_id
                LEFT JOIN users u ON e.created_by = u.user_id
                INNER JOIN memberships m ON e.org_id = m.org_id AND m.user_id = ? AND m.status = 'Active'
                WHERE e.event_date >= NOW()
                AND e.status {$statusFilter}
                AND (e.title LIKE ? OR e.description LIKE ? OR o.org_name LIKE ?)
                ORDER BY e.event_date ASC
            ", [$user->user_id, "%{$query}%", "%{$query}%", "%{$query}%"]);
        } else {
            $upcomingEvents = [];
        }

        // Format events
            foreach ($upcomingEvents as $event) {
            $event->formatted_date = date('m/d/y', strtotime($event->event_date));
            $event->formatted_full_date = date('F j, Y', strtotime($event->event_date));

            preg_match_all('/\b([A-Z])/u', $event->org_name, $matches);
            $acronym = implode('', $matches[1]);
            $event->org_short_name = !empty($acronym) && strlen($acronym) >= 2
                ? $acronym
                : strtoupper(substr($event->org_name, 0, 3));

            if (Auth::check()) {
                $userRsvp = EventAttendance::getUserRSVP($event->event_id, Auth::id());
                $event->user_rsvp_status = $userRsvp ? $userRsvp->status : null;
                $userLike = DB::selectOne("SELECT like_id FROM event_likes WHERE event_id = ? AND user_id = ?", [$event->event_id, Auth::user()->user_id]);
                $event->user_liked = $userLike ? true : false;
            } else {
                $event->user_rsvp_status = null;
                $event->user_liked = false;
            }
        }

        $pastEvents = [];
        $firstName = $middleName = $lastName = '';

        if (Auth::check()) {
            $parsedName = $this->parseUserName(Auth::user()->full_name);
            $firstName = $parsedName['first_name'];
            $middleName = $parsedName['middle_name'];
            $lastName = $parsedName['last_name'];
        }

        return view('pages.events', compact('upcomingEvents', 'pastEvents', 'sidebarData', 'firstName', 'middleName', 'lastName', 'isOfficerOrAdviser', 'query'));
    }
}
