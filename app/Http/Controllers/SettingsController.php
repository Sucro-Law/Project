<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
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
            return redirect()->route('login')->with('error', 'Please login to view settings');
        }

        $user = Auth::user();

        // Parse user's full name into parts
        // Expected format: "FirstName [MiddleName/Initial] LastName"
        $nameParts = explode(' ', trim($user->full_name));
        $firstName = '';
        $middleName = '';
        $lastName = '';

        if (count($nameParts) == 1) {
            // Only one name part
            $firstName = $nameParts[0];
        } elseif (count($nameParts) == 2) {
            // Format: FirstName LastName
            $firstName = $nameParts[0];
            $lastName = $nameParts[1];
        } elseif (count($nameParts) == 3) {
            // Format: FirstName MiddleName LastName
            $firstName = $nameParts[0];
            $middleName = $nameParts[1];
            $lastName = $nameParts[2];
        } elseif (count($nameParts) >= 4) {
            // Format: FirstName1 FirstName2 MiddleName LastName
            // Last part is always last name
            $lastName = $nameParts[count($nameParts) - 1];

            // Second to last is middle name (could be initial with period)
            $middleName = $nameParts[count($nameParts) - 2];

            // Everything else is first name
            $firstNameParts = array_slice($nameParts, 0, count($nameParts) - 2);
            $firstName = implode(' ', $firstNameParts);
        }

        $sidebarData = $this->getSidebarData();

        return view('Pages.settings', compact('user', 'sidebarData', 'firstName', 'middleName', 'lastName'));
    }

    public function update(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        $user = Auth::user();

        try {
            $validated = $request->validate([
                'first_name' => 'nullable|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
                'password' => 'nullable|string|min:6|confirmed',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $updateFields = [];
            $updateValues = [];

            // Handle name update from individual fields
            if (!empty($validated['first_name']) || !empty($validated['middle_name']) || !empty($validated['last_name'])) {
                // Build full name from parts
                $nameParts = array_filter([
                    trim($validated['first_name'] ?? ''),
                    trim($validated['middle_name'] ?? ''),
                    trim($validated['last_name'] ?? '')
                ]);

                if (!empty($nameParts)) {
                    $fullName = implode(' ', $nameParts);
                    $updateFields[] = 'full_name = ?';
                    $updateValues[] = $fullName;
                }
            }

            // Update email
            if (isset($validated['email']) && !empty($validated['email']) && $validated['email'] !== $user->email) {
                $updateFields[] = 'email = ?';
                $updateValues[] = $validated['email'];
            }

            // Update password if provided
            if (!empty($validated['password'])) {
                $updateFields[] = 'password = ?';
                $updateValues[] = Hash::make($validated['password']);
            }

            if (!empty($updateFields)) {
                $updateValues[] = $user->user_id;

                DB::update(
                    "UPDATE users SET " . implode(', ', $updateFields) . ", updated_at = NOW() WHERE user_id = ?",
                    $updateValues
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Settings updated successfully!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No changes were made.'
            ]);
        } catch (\Exception $e) {
            Log::error('Settings update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage()
            ], 500);
        }
    }
}
