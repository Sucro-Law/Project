<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasSidebarData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    use HasSidebarData;

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
