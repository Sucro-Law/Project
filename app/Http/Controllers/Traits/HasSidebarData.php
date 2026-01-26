<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait HasSidebarData
{
    protected function getSidebarData()
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();

        // 1. Calculate Initials for Sidebar Avatar
        $nameParts = explode(' ', trim($user->full_name));
        $initials = '';
        if (count($nameParts) >= 2) {
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
        } else {
            $initials = strtoupper(substr($user->full_name, 0, 2));
        }

        // 2. Fetch standard Active Memberships (Students/Officers)
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
        
        // Process standard memberships
        foreach ($userOrganizations as $org) {
            $organizations[] = [
                'org_id' => $org->org_id,
                'org_name' => $org->org_name,
                'membership_role' => $org->membership_role,
                'display_position' => ($org->membership_role === 'Officer' && !empty($org->position))
                    ? $org->position
                    : $org->membership_role,
                'academic_year' => $org->academic_year,
                'formatted_joined_at' => date('F j, Y', strtotime($org->joined_at))
            ];
        }

        // 3. If User is Faculty, fetch Advised Organizations
        if ($user->account_type === 'Faculty') {
            $advisedOrgs = DB::select("
                SELECT
                    o.org_id,
                    o.org_name,
                    oa.assigned_at as joined_at
                FROM org_advisers oa
                INNER JOIN organizations o ON oa.org_id = o.org_id
                WHERE oa.user_id = ?
                ORDER BY oa.assigned_at DESC
            ", [$user->user_id]);

            foreach ($advisedOrgs as $org) {
                $organizations[] = [
                    'org_id' => $org->org_id,
                    'org_name' => $org->org_name,
                    'membership_role' => 'Adviser',
                    'display_position' => 'Adviser',
                    'academic_year' => 'N/A', // Advisers typically aren't tied to an AY in memberships
                    'formatted_joined_at' => date('F j, Y', strtotime($org->joined_at))
                ];
            }
        }

        return [
            'initials' => $initials,
            'full_name' => $user->full_name,
            'organizations' => $organizations
        ];
    }

    protected function getCurrentAcademicYear()
    {
        $currentMonth = date('n');
        $currentYear = date('Y');

        if ($currentMonth >= 8) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }
}