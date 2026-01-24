<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->account_type !== 'Admin') {
            return redirect()->route('login')->with('error', 'Admin access required');
        }

        $organizations = DB::select("
            SELECT
                o.*,
                u.full_name as adviser_name,
                u.school_id as adviser_school_id,
                COUNT(DISTINCT m.membership_id) as member_count
            FROM organizations o
            LEFT JOIN org_advisers oa ON o.org_id = oa.org_id
            LEFT JOIN users u ON oa.user_id = u.user_id
            LEFT JOIN memberships m ON o.org_id = m.org_id AND m.status = 'Active'
            GROUP BY o.org_id, o.org_name, o.description, o.status, o.created_at, o.updated_at, u.full_name, u.school_id
            ORDER BY o.org_name ASC
        ");

        foreach ($organizations as $org) {
            preg_match_all('/\b([A-Z])/u', $org->org_name, $matches);
            $acronym = implode('', $matches[1]);
            $org->short_name = !empty($acronym) && strlen($acronym) >= 2
                ? $acronym
                : strtoupper(substr($org->org_name, 0, 3));
            $org->year = date('Y', strtotime($org->created_at));
        }

        return view('Layout.admin', compact('organizations'));
    }

    public function createOrganization(Request $request)
    {
        if (!Auth::check() || Auth::user()->account_type !== 'Admin') {
            return response()->json(['success' => false, 'message' => 'Admin access required'], 403);
        }

        $validated = $request->validate([
            'org_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        try {
            DB::insert("
                INSERT INTO organizations (org_name, description, status, created_at)
                VALUES (?, ?, ?, NOW())
            ", [$validated['org_name'], $validated['description'], $validated['status']]);

            return redirect()->back()->with('success', 'Organization created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create organization: ' . $e->getMessage());
        }
    }

    public function updateOrganization(Request $request, $orgId)
    {
        if (!Auth::check() || Auth::user()->account_type !== 'Admin') {
            return response()->json(['success' => false, 'message' => 'Admin access required'], 403);
        }

        $validated = $request->validate([
            'org_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        try {
            DB::update("
                UPDATE organizations
                SET org_name = ?, description = ?, status = ?, updated_at = NOW()
                WHERE org_id = ?
            ", [$validated['org_name'], $validated['description'], $validated['status'], $orgId]);

            return redirect()->back()->with('success', 'Organization updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update organization: ' . $e->getMessage());
        }
    }

    public function deleteOrganization($orgId)
    {
        if (!Auth::check() || Auth::user()->account_type !== 'Admin') {
            return response()->json(['success' => false, 'message' => 'Admin access required'], 403);
        }

        try {
            DB::delete("DELETE FROM organizations WHERE org_id = ?", [$orgId]);
            return redirect()->back()->with('success', 'Organization deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete organization: ' . $e->getMessage());
        }
    }
}
