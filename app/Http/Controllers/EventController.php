<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Helper method to get sidebar data for authenticated users
     */
    private function getSidebarData()
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();

        $nameParts = explode(' ', $user->full_name);
        $initials = '';
        if (count($nameParts) >= 2) {
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts) - 1], 0, 1));
        } else {
            $initials = strtoupper(substr($user->full_name, 0, 2));
        }

        $userOrganizations = DB::select("
            SELECT 
                o.org_id,
                o.org_name,
                m.membership_id,
                m.membership_role,
                m.joined_at,
                m.academic_year,
                oo.position
            FROM memberships m
            INNER JOIN organizations o ON m.org_id = o.org_id
            LEFT JOIN org_officers oo ON m.membership_id = oo.membership_id
            WHERE m.user_id = ? 
            AND m.status = 'Active'
            ORDER BY m.joined_at DESC
        ", [$user->user_id]);

        $organizations = [];
        foreach ($userOrganizations as $org) {
            $organizations[] = [
                'org_id' => $org->org_id,
                'org_name' => $org->org_name,
                'membership_role' => $org->membership_role,
                'display_position' => $org->membership_role === 'Officer' && !empty($org->position)
                    ? $org->position
                    : $org->membership_role,
                'academic_year' => $org->academic_year,
                'formatted_joined_at' => date('F j, Y', strtotime($org->joined_at))
            ];
        }

        return [
            'initials' => $initials,
            'full_name' => $user->full_name,
            'organizations' => $organizations
        ];
    }

    /**
     * Helper to parse user's name from full_name field
     */
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
        // Get all upcoming events
        $upcomingEvents = DB::select("
            SELECT 
                e.*,
                o.org_name,
                o.org_id,
                u.full_name as creator_name,
                (SELECT COUNT(*) FROM event_attendance ea WHERE ea.event_id = e.event_id AND ea.status = 'RSVP') as rsvp_count
            FROM events e
            INNER JOIN organizations o ON e.org_id = o.org_id
            LEFT JOIN users u ON e.created_by = u.user_id
            WHERE e.event_date >= NOW() 
            AND e.status IN ('Pending', 'Upcoming')
            ORDER BY e.event_date ASC
        ");

        // Get past events
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
            WHERE e.status IN ('Done', 'Cancelled')
            ORDER BY e.event_date DESC
            LIMIT 10
        ");

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

            // Check if user has RSVP'd
            if (Auth::check()) {
                $userRsvp = EventAttendance::getUserRSVP($event->event_id, Auth::id());
                $event->user_rsvp_status = $userRsvp ? $userRsvp->status : null;
            } else {
                $event->user_rsvp_status = null;
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

        return view('Pages.events', compact('upcomingEvents', 'pastEvents', 'sidebarData', 'firstName', 'middleName', 'lastName'));
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

        return view('Pages.event-detail', compact('event', 'stats', 'attendees', 'userRsvp', 'sidebarData'));
    }

    public function rsvp(Request $request, $eventId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to RSVP for this event'
            ], 401);
        }

        $event = Event::findById($eventId);
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        // Check if event is still upcoming
        if (!in_array($event->status, ['Pending', 'Upcoming'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot RSVP to this event. Event has already started or ended.'
            ], 400);
        }

        try {
            $user = Auth::user();

            // Create or update RSVP
            EventAttendance::createRSVP($eventId, $user->user_id, 'RSVP');

            return response()->json([
                'success' => true,
                'message' => 'Successfully RSVP\'d to the event!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to RSVP: ' . $e->getMessage()
            ], 500);
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
            return redirect()->route('events')->with('success', 'Event deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete event: ' . $e->getMessage());
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
}
