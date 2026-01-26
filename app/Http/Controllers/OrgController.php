<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Membership;
use App\Models\Notification;
use App\Http\Controllers\Traits\HasSidebarData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrgController extends Controller
{
    use HasSidebarData;

    public function index()
    {
        $organizations = DB::select("
            SELECT 
                o.org_id,
                o.org_name,
                o.description,
                o.status,
                o.created_at,
                o.updated_at,
                COUNT(m.membership_id) as member_count
            FROM organizations o
            LEFT JOIN memberships m ON o.org_id = m.org_id AND m.status = 'Active'
            WHERE o.status = 'Active'
            GROUP BY o.org_id, o.org_name, o.description, o.status, o.created_at, o.updated_at
            ORDER BY o.created_at DESC
            LIMIT 2
        ");

        foreach ($organizations as $org) {
            preg_match_all('/\b([A-Z])/u', $org->org_name, $matches);
            $acronym = implode('', $matches[1]);
            $org->short_name = !empty($acronym) && strlen($acronym) >= 2
                ? $acronym
                : strtoupper(substr($org->org_name, 0, 3));

            $org->year = date('Y', strtotime($org->created_at));
        }

        // Get upcoming events for dashboard
        $events = DB::select("
            SELECT 
                e.*,
                o.org_name,
                o.org_id,
                (SELECT COUNT(*) FROM event_attendance ea WHERE ea.event_id = e.event_id AND ea.status = 'RSVP') as rsvp_count
            FROM events e
            INNER JOIN organizations o ON e.org_id = o.org_id
            WHERE e.event_date >= NOW() 
            AND e.status IN ('Pending', 'Upcoming')
            ORDER BY e.event_date ASC
            LIMIT 2
        ");

        // Format events
        foreach ($events as $event) {
            $event->formatted_date = date('m/d/y', strtotime($event->event_date));
            preg_match_all('/\b([A-Z])/u', $event->org_name, $matches);
            $acronym = implode('', $matches[1]);
            $event->org_short_name = !empty($acronym) && strlen($acronym) >= 2
                ? $acronym
                : strtoupper(substr($event->org_name, 0, 3));
        }

        $sidebarData = $this->getSidebarData();

        return view('pages.dashboard', compact('organizations', 'events', 'sidebarData'));
    }

    public function organization()
    {
        $results = DB::select("
            SELECT 
                o.org_id,
                o.org_name,
                o.description,
                o.status,
                o.created_at,
                o.updated_at,
                COUNT(DISTINCT CASE WHEN m.status = 'Active' THEN m.membership_id END) as member_count,
                COUNT(DISTINCT CASE WHEN m.status = 'Active' AND m.membership_role = 'Officer' THEN m.membership_id END) as officers_count
            FROM organizations o
            LEFT JOIN memberships m ON o.org_id = m.org_id
            WHERE o.status = 'Active'
            GROUP BY o.org_id, o.org_name, o.description, o.status, o.created_at, o.updated_at
            ORDER BY o.created_at DESC
        ");

        $organizations = collect($results)->map(function ($org) {
            preg_match_all('/\b([A-Z])/u', $org->org_name, $matches);
            $acronym = implode('', $matches[1]);
            $short_name = !empty($acronym) && strlen($acronym) >= 2
                ? $acronym
                : strtoupper(substr($org->org_name, 0, 3));

            $year = date('Y', strtotime($org->created_at));

            return [
                'org_id' => $org->org_id,
                'name' => $org->org_name,
                'short_name' => $short_name,
                'description' => $org->description ?? 'No description available',
                'status' => $org->status,
                'year' => $year,
                'members' => $org->member_count ?? 0,
                'officers_count' => $org->officers_count ?? 0,
            ];
        });

        $sidebarData = $this->getSidebarData();

        return view('pages.organization', compact('organizations', 'sidebarData'));
    }

    // Replace your existing show() method in OrgController with this:

    public function show($id)
    {
        $organization = DB::selectOne("
        SELECT 
            o.org_id,
            o.org_name,
            o.description,
            o.status,
            o.created_at,
            o.updated_at,
            COUNT(DISTINCT CASE WHEN m.status = 'Active' THEN m.membership_id END) as member_count,
            COUNT(DISTINCT CASE WHEN m.status = 'Active' AND m.membership_role = 'Officer' THEN m.membership_id END) as officers_count
        FROM organizations o
        LEFT JOIN memberships m ON o.org_id = m.org_id
        WHERE o.org_id = ?
        GROUP BY o.org_id, o.org_name, o.description, o.status, o.created_at, o.updated_at
    ", [$id]);

        if (!$organization) {
            abort(404, 'Organization not found');
        }

        preg_match_all('/\b([A-Z])/u', $organization->org_name, $matches);
        $acronym = implode('', $matches[1]);
        $organization->short_name = !empty($acronym) && strlen($acronym) >= 2
            ? $acronym
            : strtoupper(substr($organization->org_name, 0, 3));
        $organization->year = date('Y', strtotime($organization->created_at));

        $activeMemberships = DB::select("
        SELECT m.*, u.full_name, u.email, u.school_id, u.account_type
        FROM memberships m
        INNER JOIN users u ON m.user_id = u.user_id
        WHERE m.org_id = ? AND m.status = 'Active' AND m.membership_role = 'Member'
        ORDER BY u.full_name ASC
    ", [$id]);

        $officers = DB::select("
        SELECT 
            m.*, 
            u.full_name, 
            u.email, 
            u.school_id, 
            u.account_type,
            oo.position,
            oo.term_start,
            oo.term_end
        FROM memberships m
        INNER JOIN users u ON m.user_id = u.user_id
        LEFT JOIN org_officers oo ON m.membership_id = oo.membership_id
        WHERE m.org_id = ? 
        AND m.status = 'Active' 
        AND m.membership_role = 'Officer'
        ORDER BY 
            CASE 
                WHEN LOWER(oo.position) LIKE '%president%' AND LOWER(oo.position) NOT LIKE '%vice%' THEN 1
                WHEN LOWER(oo.position) LIKE '%vice%president%' THEN 2
                WHEN LOWER(oo.position) LIKE '%secretary%' THEN 3
                WHEN LOWER(oo.position) LIKE '%treasurer%' THEN 4
                ELSE 5
            END,
            u.full_name ASC
    ", [$id]);

        $pendingMemberships = DB::select("
        SELECT m.*, u.full_name, u.email, u.school_id, u.account_type
        FROM memberships m
        INNER JOIN users u ON m.user_id = u.user_id
        WHERE m.org_id = ? AND m.status = 'Pending'
        ORDER BY m.joined_at DESC
    ", [$id]);

        $alumniMembers = DB::select("
        SELECT m.*, u.full_name, u.email, u.school_id, u.account_type
        FROM memberships m
        INNER JOIN users u ON m.user_id = u.user_id
        WHERE m.org_id = ? AND m.status = 'Alumni'
        ORDER BY u.full_name ASC
    ", [$id]);

        $adviser = DB::selectOne("
        SELECT u.*, oa.assigned_at
        FROM org_advisers oa
        INNER JOIN users u ON oa.user_id = u.user_id
        WHERE oa.org_id = ?
        LIMIT 1
    ", [$id]);

        $organization->activeMemberships = $activeMemberships;
        $organization->officers = $officers;
        $organization->pendingMemberships = $pendingMemberships;
        $organization->alumniMembers = $alumniMembers;
        $organization->adviser = $adviser;

        $user = Auth::user();
        $role = 'guest';

        if ($user) {
            $isAdviser = DB::selectOne(
                "SELECT adviser_id FROM org_advisers WHERE org_id = ? AND user_id = ? LIMIT 1",
                [$id, $user->user_id]
            );

            if ($isAdviser) {
                $role = 'adviser';
            } else {
                $userMembership = DB::selectOne("
                SELECT * FROM memberships 
                WHERE org_id = ? AND user_id = ?
                LIMIT 1
            ", [$id, $user->user_id]);

                if ($userMembership && $userMembership->status === 'Active') {
                    $role = strtolower($userMembership->membership_role);
                }
            }
        }

        $isMember = false;
        $userMembership = null;
        $isOfficer = false;

        if ($user) {
            $userMembership = DB::selectOne("
            SELECT * FROM memberships 
            WHERE org_id = ? AND user_id = ?
            LIMIT 1
        ", [$id, $user->user_id]);

            if ($userMembership) {
                $isMember = $userMembership->status === 'Active';
                $isOfficer = $isMember && $userMembership->membership_role === 'Officer';
            }
        }

        // Get pending events for this organization (for officers/advisers)
        $pendingEvents = [];
        if ($role === 'officer' || $role === 'adviser') {
            $pendingEvents = DB::select("
            SELECT 
                e.*,
                u.full_name as submitted_by
            FROM events e
            LEFT JOIN users u ON e.created_by = u.user_id
            WHERE e.org_id = ? 
            AND e.status = 'Pending'
            ORDER BY e.created_at DESC
        ", [$id]);

            // Format dates
            foreach ($pendingEvents as $event) {
                $event->formatted_date = date('m/d/y', strtotime($event->event_date));
                $event->formatted_full_date = date('F j, Y', strtotime($event->event_date));
            }
        }

        // Get approved/upcoming events for the Events tab
        $organizationEvents = DB::select("
        SELECT 
            e.*,
            u.full_name as author_name,
            (SELECT COUNT(*) FROM event_attendance ea WHERE ea.event_id = e.event_id AND ea.status = 'RSVP') as likes_count
        FROM events e
        LEFT JOIN users u ON e.created_by = u.user_id
        WHERE e.org_id = ?
        AND e.status IN ('Upcoming', 'Ongoing', 'Done')
        ORDER BY e.event_date DESC
    ", [$id]);

        foreach ($organizationEvents as $event) {
            $event->formatted_date = date('m/d/y', strtotime($event->event_date));
            $event->is_upcoming = in_array($event->status, ['Upcoming']);
            $event->is_ended = $event->status === 'Done';

            $event->attendees = DB::select("
                SELECT u.full_name, u.school_id, ea.status as attendance_status
                FROM event_attendance ea
                INNER JOIN users u ON ea.user_id = u.user_id
                WHERE ea.event_id = ?
                ORDER BY u.full_name ASC
            ", [$event->event_id]);
        }

        $sidebarData = $this->getSidebarData();

        return view('pages.orgdetail', compact(
            'organization',
            'role',
            'isMember',
            'userMembership',
            'isOfficer',
            'sidebarData',
            'pendingEvents',
            'organizationEvents'
        ));
    }

    public function joinOrganization(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to join an organization');
        }

        $user = Auth::user();

        if ($user->account_type !== 'Student') {
            return back()->with('error', 'Only students can join organizations');
        }

        $organization = DB::selectOne("SELECT * FROM organizations WHERE org_id = ?", [$id]);
        if (!$organization) {
            abort(404, 'Organization not found');
        }

        $existingMembership = DB::selectOne("
            SELECT * FROM memberships 
            WHERE org_id = ? AND user_id = ?
            LIMIT 1
        ", [$id, $user->user_id]);

        if ($existingMembership) {
            if ($existingMembership->status === 'Active') {
                return back()->with('info', 'You are already a member of this organization');
            } elseif ($existingMembership->status === 'Pending') {
                return back()->with('info', 'Your membership request is pending approval');
            } elseif ($existingMembership->status === 'Rejected' || $existingMembership->status === 'Alumni') {
                DB::update("
                    UPDATE memberships 
                    SET status = 'Pending', 
                        joined_at = CURRENT_DATE, 
                        academic_year = ?
                    WHERE membership_id = ?
                ", [$this->getCurrentAcademicYear(), $existingMembership->membership_id]);

                $message = $existingMembership->status === 'Alumni'
                    ? 'Welcome back! Your membership request has been submitted.'
                    : 'Membership request resubmitted successfully!';

                return back()->with('success', $message);
            }
        }

        try {
            DB::insert("
                INSERT INTO memberships (user_id, org_id, academic_year, membership_role, status, joined_at)
                VALUES (?, ?, ?, 'Member', 'Pending', CURRENT_DATE)
            ", [$user->user_id, $id, $this->getCurrentAcademicYear()]);

            return back()->with('success', 'Membership request submitted successfully! Please wait for approval.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit membership request. Please try again.');
        }
    }

    public function submitMembershipForm(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = Auth::user();

        $validated = $request->validate([
            'school_number' => 'required|string',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'position' => 'required|in:member,officer',
            'role' => 'nullable|string|required_if:position,officer'
        ]);

        $organization = DB::selectOne("SELECT * FROM organizations WHERE org_id = ?", [$id]);
        if (!$organization) {
            return back()->with('error', 'Organization not found');
        }

        $targetUser = DB::selectOne(
            "SELECT * FROM users WHERE school_id = ?",
            [$validated['school_number']]
        );

        if (!$targetUser) {
            return back()->with('error', 'No user found with this school number. Please ensure you have an account.');
        }

        if ($targetUser->user_id !== $user->user_id) {
            return back()->with('error', 'School number does not match your account.');
        }

        $existingMembership = DB::selectOne(
            "SELECT * FROM memberships WHERE org_id = ? AND user_id = ?",
            [$id, $user->user_id]
        );

        if ($existingMembership) {
            if ($existingMembership->status === 'Active') {
                return back()->with('info', 'You are already a member of this organization');
            } elseif ($existingMembership->status === 'Pending') {
                return back()->with('info', 'Your membership request is already pending approval');
            }
        }

        try {
            $membershipRole = ucfirst($validated['position']);

            if ($existingMembership) {
                DB::update(
                    "UPDATE memberships 
                     SET status = 'Pending', 
                         membership_role = ?,
                         academic_year = ?,
                         joined_at = CURRENT_DATE
                     WHERE membership_id = ?",
                    [$membershipRole, $this->getCurrentAcademicYear(), $existingMembership->membership_id]
                );
                $membershipId = $existingMembership->membership_id;
            } else {
                DB::insert(
                    "INSERT INTO memberships (user_id, org_id, academic_year, membership_role, status, joined_at)
                     VALUES (?, ?, ?, ?, 'Pending', CURRENT_DATE)",
                    [$user->user_id, $id, $this->getCurrentAcademicYear(), $membershipRole]
                );

                $membershipId = DB::selectOne(
                    "SELECT membership_id FROM memberships WHERE user_id = ? AND org_id = ? ORDER BY joined_at DESC LIMIT 1",
                    [$user->user_id, $id]
                )->membership_id;
            }

            if ($validated['position'] === 'officer' && !empty($validated['role'])) {
                DB::delete("DELETE FROM org_officers WHERE membership_id = ?", [$membershipId]);
                DB::insert(
                    "INSERT INTO org_officers (membership_id, org_id, position, term_start, term_end)
                     VALUES (?, ?, ?, NULL, NULL)",
                    [$membershipId, $id, $validated['role']]
                );
            }

            return back()->with('success', 'Membership application submitted successfully! Please wait for approval.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit application: ' . $e->getMessage());
        }
    }

    public function leaveOrganization($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = Auth::user();

        $membership = DB::selectOne("
            SELECT * FROM memberships 
            WHERE org_id = ? AND user_id = ? AND status = 'Active'
            LIMIT 1
        ", [$id, $user->user_id]);

        if (!$membership) {
            return back()->with('error', 'You are not a member of this organization');
        }

        if ($membership->membership_role === 'Officer') {
            return back()->with('error', 'Officers must resign from their position before leaving the organization');
        }

        DB::update("
            UPDATE memberships 
            SET status = 'Alumni'
            WHERE membership_id = ?
        ", [$membership->membership_id]);

        return back()->with('success', 'You have successfully left the organization');
    }

    public function cancelMembershipRequest($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = Auth::user();

        $membership = DB::selectOne("
            SELECT * FROM memberships 
            WHERE org_id = ? AND user_id = ? AND status = 'Pending'
            LIMIT 1
        ", [$id, $user->user_id]);

        if (!$membership) {
            return back()->with('error', 'No pending membership request found');
        }

        DB::delete("DELETE FROM memberships WHERE membership_id = ?", [$membership->membership_id]);

        return back()->with('success', 'Membership request cancelled');
    }

    public function approveMember($orgId, $membershipId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = Auth::user();

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

        if (!$isOfficer && !$isAdviser) {
            return back()->with('error', 'You do not have permission to approve members');
        }

        $membership = DB::selectOne(
            "SELECT m.user_id, m.membership_role, o.org_name FROM memberships m
             JOIN organizations o ON m.org_id = o.org_id
             WHERE m.membership_id = ?",
            [$membershipId]
        );

        DB::update(
            "UPDATE memberships SET status = 'Active' WHERE membership_id = ?",
            [$membershipId]
        );

        if ($membership && $membership->membership_role === 'Officer') {
            DB::update(
                "UPDATE org_officers SET term_start = CURRENT_DATE, term_end = DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR) WHERE membership_id = ?",
                [$membershipId]
            );
        }

        if ($membership) {
            Notification::create(
                $membership->user_id,
                'membership_approved',
                'Membership Approved',
                "Your membership to {$membership->org_name} has been approved!",
                route('orgDetail', ['id' => $orgId])
            );
        }

        return back()->with('success', 'Member approved successfully!');
    }

    public function rejectMember($orgId, $membershipId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = Auth::user();

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

        if (!$isOfficer && !$isAdviser) {
            return back()->with('error', 'You do not have permission to reject members');
        }

        $membership = DB::selectOne(
            "SELECT m.user_id, o.org_name FROM memberships m
             JOIN organizations o ON m.org_id = o.org_id
             WHERE m.membership_id = ?",
            [$membershipId]
        );

        DB::update(
            "UPDATE memberships SET status = 'Rejected' WHERE membership_id = ?",
            [$membershipId]
        );

        if ($membership) {
            Notification::create(
                $membership->user_id,
                'membership_rejected',
                'Membership Request Rejected',
                "Your membership request to {$membership->org_name} was not approved.",
                null
            );
        }

        return back()->with('success', 'Member request rejected');
    }

    public function addMember(Request $request, $orgId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = Auth::user();

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

        if (!$isOfficer && !$isAdviser) {
            return back()->with('error', 'You do not have permission to add members');
        }

        $validated = $request->validate([
            'school_id' => 'required|string',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'member_type' => 'required|in:Member,Officer',
            'position' => 'nullable|string|required_if:member_type,Officer'
        ]);

        $targetUser = DB::selectOne(
            "SELECT * FROM users WHERE school_id = ?",
            [$validated['school_id']]
        );

        if (!$targetUser) {
            return back()->with('error', 'User with this school ID not found');
        }

        $existingMembership = DB::selectOne(
            "SELECT * FROM memberships WHERE org_id = ? AND user_id = ?",
            [$orgId, $targetUser->user_id]
        );

        if ($existingMembership && $existingMembership->status === 'Active') {
            return back()->with('error', 'User is already an active member');
        }

        try {
            if ($existingMembership) {
                DB::update(
                    "UPDATE memberships 
                     SET status = 'Active', 
                         membership_role = ?,
                         academic_year = ?,
                         joined_at = CURRENT_DATE
                     WHERE membership_id = ?",
                    [$validated['member_type'], $this->getCurrentAcademicYear(), $existingMembership->membership_id]
                );
                $membershipId = $existingMembership->membership_id;
            } else {
                DB::insert(
                    "INSERT INTO memberships (user_id, org_id, academic_year, membership_role, status, joined_at)
                     VALUES (?, ?, ?, ?, 'Active', CURRENT_DATE)",
                    [$targetUser->user_id, $orgId, $this->getCurrentAcademicYear(), $validated['member_type']]
                );

                $membershipId = DB::selectOne(
                    "SELECT membership_id FROM memberships WHERE user_id = ? AND org_id = ? ORDER BY joined_at DESC LIMIT 1",
                    [$targetUser->user_id, $orgId]
                )->membership_id;
            }

            if ($validated['member_type'] === 'Officer' && !empty($validated['position'])) {
                $existingOfficer = DB::selectOne(
                    "SELECT * FROM org_officers WHERE membership_id = ?",
                    [$membershipId]
                );

                if ($existingOfficer) {
                    DB::update(
                        "UPDATE org_officers SET position = ? WHERE officer_id = ?",
                        [$validated['position'], $existingOfficer->officer_id]
                    );
                } else {
                    DB::insert(
                        "INSERT INTO org_officers (membership_id, org_id, position, term_start)
                         VALUES (?, ?, ?, CURRENT_DATE)",
                        [$membershipId, $orgId, $validated['position']]
                    );
                }
            }

            return back()->with('success', 'Member added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to add member: ' . $e->getMessage());
        }
    }

public function createEvent(Request $request, $orgId)
{
    if (!Auth::check()) {
        return back()->with('error', 'Please login first');
    }

    try {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'venue' => 'required|string|max:255',
            'event_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $eventId = 'EVT-' . str_pad(rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        $status = 'Pending';
        $imagePath = null;

        if ($request->hasFile('event_image')) {
            $image = $request->file('event_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/events'), $filename);
            $imagePath = 'uploads/events/' . $filename;
        }

        // INSERT with image_path included
        DB::insert(
            "INSERT INTO events 
            (event_id, org_id, title, description, event_date, event_duration, venue, status, image_path, created_by, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            [
                $eventId,
                $orgId,
                $validated['title'],
                $validated['description'],
                $validated['event_date'],
                4,
                $validated['venue'],
                $status,
                $imagePath,  // Include image path in initial insert
                $user->user_id
            ]
        );

        return back()->with('success', 'Event submitted successfully and is pending approval.');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to create event: ' . $e->getMessage());
    }
}


    public function approveEvent($orgId, $eventId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please login first');
        }

        $user = Auth::user();

        // Only advisers can approve events
        $isAdviser = DB::selectOne(
            "SELECT adviser_id FROM org_advisers WHERE org_id = ? AND user_id = ? LIMIT 1",
            [$orgId, $user->user_id]
        );

        if (!$isAdviser) {
            return back()->with('error', 'Only advisers can approve events');
        }

        try {
            $event = DB::selectOne(
                "SELECT e.created_by, e.title, o.org_name FROM events e
                 JOIN organizations o ON e.org_id = o.org_id
                 WHERE e.event_id = ?",
                [$eventId]
            );

            DB::update(
                "UPDATE events SET status = 'Upcoming', updated_at = NOW() WHERE event_id = ?",
                [$eventId]
            );

            if ($event && $event->created_by) {
                Notification::create(
                    $event->created_by,
                    'event_approved',
                    'Event Approved',
                    "Your event \"{$event->title}\" has been approved!",
                    route('orgDetail', ['id' => $orgId])
                );
            }

            return back()->with('success', 'Event approved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve event: ' . $e->getMessage());
        }
    }

    public function rejectEvent($orgId, $eventId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please login first');
        }

        $user = Auth::user();

        // Only advisers can reject events
        $isAdviser = DB::selectOne(
            "SELECT adviser_id FROM org_advisers WHERE org_id = ? AND user_id = ? LIMIT 1",
            [$orgId, $user->user_id]
        );

        if (!$isAdviser) {
            return back()->with('error', 'Only advisers can reject events');
        }

        try {
            $event = DB::selectOne(
                "SELECT e.created_by, e.title FROM events e WHERE e.event_id = ?",
                [$eventId]
            );

            DB::update(
                "UPDATE events SET status = 'Cancelled', updated_at = NOW() WHERE event_id = ?",
                [$eventId]
            );

            if ($event && $event->created_by) {
                Notification::create(
                    $event->created_by,
                    'event_rejected',
                    'Event Rejected',
                    "Your event \"{$event->title}\" was not approved.",
                    null
                );
            }

            return back()->with('success', 'Event rejected successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject event: ' . $e->getMessage());
        }
    }

    public function cancelEvent($orgId, $eventId)
{
    if (!Auth::check()) {
        return back()->with('error', 'Please login first');
    }

    $user = Auth::user();

    // Get the event details
    $event = DB::selectOne(
        "SELECT * FROM events WHERE event_id = ? AND org_id = ?",
        [$eventId, $orgId]
    );

    if (!$event) {
        return back()->with('error', 'Event not found');
    }

    // Check if user is the creator of the event
    if ($event->created_by !== $user->user_id) {
        return back()->with('error', 'You can only cancel events you created');
    }

    // Check if event is still pending
    if ($event->status !== 'Pending') {
        return back()->with('error', 'Only pending events can be cancelled');
    }

    try {
        // Delete the event from database
        DB::delete(
            "DELETE FROM events WHERE event_id = ?",
            [$eventId]
        );

        // Optionally delete the event image if it exists
        if (!empty($event->image_path) && file_exists(public_path($event->image_path))) {
            unlink(public_path($event->image_path));
        }

        return back()->with('success', 'Event submission cancelled successfully!');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to cancel event: ' . $e->getMessage());
    }
}
}