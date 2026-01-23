<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrgController extends Controller
{

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

        return view('Pages.dashboard', compact('organizations'));
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

        $organizations = collect($results)->map(function($org) {
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
                'description' => $org->description ?? 'An organization is a group of people who work together, like a neighborhood association, a charity, a union, or a corporation.',
                'status' => $org->status,
                'year' => $year,
                'members' => $org->member_count ?? 0,
                'officers_count' => $org->officers_count ?? 0,
            ];
        });

        return view('Pages.organization', compact('organizations'));
    }

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
            WHERE m.org_id = ? AND m.status = 'Active'
            ORDER BY m.joined_at DESC
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
            ORDER BY m.joined_at ASC
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
                $role = strtolower($user->account_type);
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
                
                if ($isOfficer) {
                    $role = 'officer';
                }
            }
        }

        return view('Pages.orgdetail', compact('organization', 'role', 'isMember', 'userMembership', 'isOfficer'));
    }

    public function events()
    {
        return view('Pages.events');
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
            // Insert will trigger auto-generation of membership_id
            DB::insert("
                INSERT INTO memberships (user_id, org_id, academic_year, membership_role, status, joined_at)
                VALUES (?, ?, ?, 'Member', 'Pending', CURRENT_DATE)
            ", [$user->user_id, $id, $this->getCurrentAcademicYear()]);

            return back()->with('success', 'Membership request submitted successfully! Please wait for approval.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit membership request. Please try again.');
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

        DB::update(
            "UPDATE memberships SET status = 'Active' WHERE membership_id = ?",
            [$membershipId]
        );

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

        DB::update(
            "UPDATE memberships SET status = 'Rejected' WHERE membership_id = ?",
            [$membershipId]
        );

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
}