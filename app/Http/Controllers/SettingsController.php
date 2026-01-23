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
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $nameData = $this->parseFullName($user->full_name ?? '');
        
        return view('Pages.settings', [
            'firstName' => $nameData['firstName'],
            'middleName' => $nameData['middleName'],
            'lastName' => $nameData['lastName']
        ]);
    }

    private function parseFullName($fullName)
    {
        $nameParts = array_values(array_filter(explode(' ', trim($fullName))));
        $count = count($nameParts);
        
        $firstName = '';
        $middleName = '';
        $lastName = '';
        
        if ($count == 1) {
            $firstName = $nameParts[0];
        } elseif ($count == 2) {
            $firstName = $nameParts[0];
            $lastName = $nameParts[1];
        } elseif ($count == 3) {
            $firstName = $nameParts[0];
            $middleName = $nameParts[1];
            $lastName = $nameParts[2];
        } elseif ($count == 4) {
            $firstName = $nameParts[0] . ' ' . $nameParts[1];
            $middleName = $nameParts[2];
            $lastName = $nameParts[3];
        } elseif ($count >= 5) {
            $firstName = $nameParts[0] . ' ' . $nameParts[1];
            $lastName = $nameParts[$count - 1];
            $middleNameParts = array_slice($nameParts, 2, $count - 3);
            $middleName = implode(' ', $middleNameParts);
        }
        
        return [
            'firstName' => $firstName,
            'middleName' => $middleName,
            'lastName' => $lastName
        ];
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $userId = $user->user_id;

            $emailCheck = DB::selectOne(
                "SELECT user_id FROM users WHERE email = ? AND user_id != ? LIMIT 1",
                [$request->input('email'), $userId]
            );

            $rules = [
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'nullable|min:6|confirmed',
            ];

            $messages = [
                'first_name.required' => 'First name is required',
                'last_name.required' => 'Last name is required',
                'email.required' => 'Email address is required',
                'email.email' => 'Please enter a valid email address',
                'password.min' => 'Password must be at least 6 characters',
                'password.confirmed' => 'Passwords do not match',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($emailCheck) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => ['email' => ['This email is already taken']]
                ], 422);
            }

            $firstName = trim($request->input('first_name'));
            $middleName = trim($request->input('middle_name'));
            $lastName = trim($request->input('last_name'));
            
            $fullName = $firstName;
            if (!empty($middleName)) {
                $fullName .= ' ' . $middleName;
            }
            $fullName .= ' ' . $lastName;

            if ($request->filled('password')) {
                $updated = DB::update(
                    "UPDATE users 
                     SET full_name = ?, email = ?, password = ?, updated_at = NOW() 
                     WHERE user_id = ?",
                    [$fullName, $request->input('email'), Hash::make($request->input('password')), $userId]
                );
            } else {
                $updated = DB::update(
                    "UPDATE users 
                     SET full_name = ?, email = ?, updated_at = NOW() 
                     WHERE user_id = ?",
                    [$fullName, $request->input('email'), $userId]
                );
            }

            if ($updated === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save user information'
                ], 500);
            }

            $updatedUser = DB::selectOne(
                "SELECT * FROM users WHERE user_id = ? LIMIT 1",
                [$userId]
            );

            if ($updatedUser) {
                $userModel = new \App\Models\User();
                $userModel->exists = true;
                foreach ((array)$updatedUser as $key => $value) {
                    $userModel->{$key} = $value;
                }
                Auth::setUser($userModel);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'data' => [
                    'full_name' => $fullName,
                    'email' => $request->input('email'),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Settings update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating your profile. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}