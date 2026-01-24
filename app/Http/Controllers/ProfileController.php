<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
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

        // Get user's initials
        $nameParts = explode(' ', $user->full_name);
        $initials = '';
        if (count($nameParts) >= 2) {
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts) - 1], 0, 1));
        } else {
            $initials = strtoupper(substr($user->full_name, 0, 2));
        }

        // Get user's organizations
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

        // Format organizations data
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

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your profile');
        }

        $user = Auth::user();

        // Get user's organizations with membership details
        $organizations = DB::select("
            SELECT 
                o.org_id,
                o.org_name,
                m.membership_id,
                m.membership_role,
                m.status,
                m.joined_at,
                m.academic_year,
                oo.position,
                oo.term_start,
                oo.term_end
            FROM memberships m
            INNER JOIN organizations o ON m.org_id = o.org_id
            LEFT JOIN org_officers oo ON m.membership_id = oo.membership_id
            WHERE m.user_id = ? 
            AND m.status = 'Active'
            ORDER BY m.joined_at DESC
        ", [$user->user_id]);

        // Add short names to organizations
        foreach ($organizations as $org) {
            preg_match_all('/\b([A-Z])/u', $org->org_name, $matches);
            $acronym = implode('', $matches[1]);
            $org->short_name = !empty($acronym) && strlen($acronym) >= 2 
                ? $acronym 
                : strtoupper(substr($org->org_name, 0, 3));
            
            $org->formatted_joined = date('M j, Y', strtotime($org->joined_at));
            
            if ($org->membership_role === 'Officer' && !empty($org->position)) {
                $org->display_position = $org->position;
            } else {
                $org->display_position = $org->membership_role;
            }
        }

        // Get events the user has attended
        $attendedEvents = DB::select("
            SELECT 
                e.event_id,
                e.title,
                e.description,
                e.event_date,
                e.venue as location,
                e.status,
                o.org_name,
                o.org_id,
                ea.status as attendance_status,
                ea.remarks
            FROM event_attendance ea
            INNER JOIN events e ON ea.event_id = e.event_id
            INNER JOIN organizations o ON e.org_id = o.org_id
            WHERE ea.user_id = ? 
            AND ea.status = 'Present'
            AND e.status = 'Done'
            ORDER BY e.event_date DESC
            LIMIT 10
        ", [$user->user_id]);

        foreach ($attendedEvents as $event) {
            preg_match_all('/\b([A-Z])/u', $event->org_name, $matches);
            $acronym = implode('', $matches[1]);
            $event->org_short_name = !empty($acronym) && strlen($acronym) >= 2 
                ? $acronym 
                : strtoupper(substr($event->org_name, 0, 3));
            
            $event->formatted_date = date('d', strtotime($event->event_date));
            $event->formatted_month = strtoupper(date('M', strtotime($event->event_date)));
            $event->formatted_full_date = date('M j, Y', strtotime($event->event_date));
        }

        // Get upcoming events
        $upcomingEvents = DB::select("
            SELECT 
                e.event_id,
                e.title,
                e.description,
                e.event_date,
                e.venue as location,
                e.status,
                o.org_name,
                o.org_id,
                ea.status as attendance_status
            FROM event_attendance ea
            INNER JOIN events e ON ea.event_id = e.event_id
            INNER JOIN organizations o ON e.org_id = o.org_id
            WHERE ea.user_id = ? 
            AND ea.status = 'RSVP'
            AND e.event_date >= NOW()
            AND e.status IN ('Pending', 'Upcoming')
            ORDER BY e.event_date ASC
            LIMIT 10
        ", [$user->user_id]);

        foreach ($upcomingEvents as $event) {
            preg_match_all('/\b([A-Z])/u', $event->org_name, $matches);
            $acronym = implode('', $matches[1]);
            $event->org_short_name = !empty($acronym) && strlen($acronym) >= 2 
                ? $acronym 
                : strtoupper(substr($event->org_name, 0, 3));
            
            $event->formatted_date = date('d', strtotime($event->event_date));
            $event->formatted_month = strtoupper(date('M', strtotime($event->event_date)));
            $event->formatted_full_date = date('M j, Y', strtotime($event->event_date));
        }

        $stats = [
            'organizations_count' => count($organizations),
            'events_attended_count' => count($attendedEvents),
            'upcoming_events_count' => count($upcomingEvents),
        ];

        $nameParts = explode(' ', $user->full_name);
        $initials = '';
        if (count($nameParts) >= 2) {
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts) - 1], 0, 1));
        } else {
            $initials = strtoupper(substr($user->full_name, 0, 2));
        }

        $memberSince = date('F j, Y', strtotime($user->created_at));
        $currentAcademicYear = $this->getCurrentAcademicYear();
        $campus = 'Polytechnic University of the Philippines - Sta. Mesa';

        $sidebarData = $this->getSidebarData();

        return view('Pages.profile', compact(
            'user',
            'organizations',
            'attendedEvents',
            'upcomingEvents',
            'stats',
            'initials',
            'memberSince',
            'currentAcademicYear',
            'campus',
            'sidebarData'
        ));
    }

    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
        ]);

        try {
            $updateFields = [];
            $updateValues = [];

            if (isset($validated['full_name']) && !empty($validated['full_name'])) {
                $updateFields[] = 'full_name = ?';
                $updateValues[] = $validated['full_name'];
            }

            if (isset($validated['email']) && !empty($validated['email'])) {
                $updateFields[] = 'email = ?';
                $updateValues[] = $validated['email'];
            }

            if (!empty($updateFields)) {
                $updateValues[] = $user->user_id;
                
                DB::update(
                    "UPDATE users SET " . implode(', ', $updateFields) . ", updated_at = NOW() WHERE user_id = ?",
                    $updateValues
                );

                return back()->with('success', 'Profile updated successfully!');
            }

            return back()->with('info', 'No changes were made.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    private function getCurrentAcademicYear()
    {
        $currentMonth = date('n');
        $currentYear = date('Y');
        
        if ($currentMonth >= 8) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }

    public function show($userId = null)
    {
        if ($userId) {
            $user = DB::selectOne("SELECT * FROM users WHERE user_id = ?", [$userId]);
            
            if (!$user) {
                abort(404, 'User not found');
            }
        } else {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Please login to view your profile');
            }
            
            return $this->index();
        }
    }
}