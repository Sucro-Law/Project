<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.signin');
    }

    public function showSignup()
    {
        return view('auth.signup');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:student,faculty,admin'
        ]);

        $accountType = ucfirst($request->role);
        
        $user = DB::selectOne(
            "SELECT * FROM users 
             WHERE (school_id = ? OR email = ?) 
             AND account_type = ? 
             LIMIT 1",
            [$request->username, $request->username, $accountType]
        );

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        $userModel = new \App\Models\User();
        $userModel->exists = true;
        foreach ((array)$user as $key => $value) {
            $userModel->{$key} = $value;
        }
        
        Auth::login($userModel);

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'redirect' => route('dashboard')
        ]);
    }

    public function signup(Request $request)
    {
        $request->validate([
            'role' => 'required|in:student,faculty',
            'school_id' => 'required|string',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $existingSchoolId = DB::selectOne(
            "SELECT user_id FROM users WHERE school_id = ? LIMIT 1",
            [$request->school_id]
        );

        if ($existingSchoolId) {
            return response()->json([
                'success' => false,
                'message' => 'School ID already exists.'
            ], 422);
        }

        $existingEmail = DB::selectOne(
            "SELECT user_id FROM users WHERE email = ? LIMIT 1",
            [$request->email]
        );

        if ($existingEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Email already exists.'
            ], 422);
        }

        $schoolId = $request->school_id;

        $fullName = trim($request->first_name . ' ' . 
                        ($request->middle_name ? $request->middle_name . ' ' : '') . 
                        $request->last_name);

        $accountType = ucfirst($request->role);
        $plainPassword = $request->password;
        $hashedPassword = Hash::make($plainPassword);

        DB::insert(
            "INSERT INTO users (school_id, full_name, email, password, account_type, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
            [$schoolId, $fullName, $request->email, $hashedPassword, $accountType]
        );

        $user = DB::selectOne(
            "SELECT * FROM users WHERE email = ? LIMIT 1",
            [$request->email]
        );

        try {
            Mail::send('emails.registration', [
                'user' => $user,
                'userId' => $user->user_id,
                'schoolId' => $schoolId,
                'plainPassword' => $plainPassword,
                'accountType' => $accountType
            ], function ($message) use ($user) {
                $message->to($user->email, $user->full_name)
                        ->subject('Welcome to Student Organization Management - Your Account Details');
            });
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Your credentials have been sent to your email. Please login to continue.',
            'user_id' => $user->user_id,
            'school_id' => $schoolId,
            'redirect' => route('login')
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = DB::selectOne(
            "SELECT * FROM users WHERE email = ? LIMIT 1",
            [$request->email]
        );

        if (!$user) {
            return response()->json([
                'success' => true,
                'message' => 'If an account exists with this email, you will receive a password reset link shortly.'
            ]);
        }

        DB::delete(
            "DELETE FROM password_reset_tokens WHERE email = ?",
            [$request->email]
        );

        $token = Str::random(64);

        DB::insert(
            "INSERT INTO password_reset_tokens (email, token, created_at) VALUES (?, ?, NOW())",
            [$request->email, Hash::make($token)]
        );

        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($request->email));
        $expiryMinutes = config('auth.passwords.users.expire', 60);

        try {
            Mail::send('emails.password-reset', [
                'user' => $user,
                'resetUrl' => $resetUrl,
                'expiryMinutes' => $expiryMinutes
            ], function ($message) use ($user) {
                $message->to($user->email, $user->full_name)
                        ->subject('Reset Your Password - Student Organization Management');
            });
        } catch (\Exception $e) {
            Log::error('Password reset email failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reset email. Please try again later.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'If an account exists with this email, you will receive a password reset link shortly.'
        ]);
    }

    public function showResetPassword(Request $request, $token)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('login')->with('error', 'Invalid password reset link.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $tokenRecord = DB::selectOne(
            "SELECT * FROM password_reset_tokens WHERE email = ? LIMIT 1",
            [$request->email]
        );

        if (!$tokenRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired password reset link.'
            ], 400);
        }

        $expiryMinutes = config('auth.passwords.users.expire', 60);
        $tokenCreatedAt = \Carbon\Carbon::parse($tokenRecord->created_at);

        if ($tokenCreatedAt->addMinutes($expiryMinutes)->isPast()) {
            DB::delete(
                "DELETE FROM password_reset_tokens WHERE email = ?",
                [$request->email]
            );

            return response()->json([
                'success' => false,
                'message' => 'Password reset link has expired. Please request a new one.'
            ], 400);
        }

        if (!Hash::check($request->token, $tokenRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password reset link.'
            ], 400);
        }

        $user = DB::selectOne(
            "SELECT * FROM users WHERE email = ? LIMIT 1",
            [$request->email]
        );

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        DB::update(
            "UPDATE users SET password = ?, updated_at = NOW() WHERE email = ?",
            [Hash::make($request->password), $request->email]
        );

        DB::delete(
            "DELETE FROM password_reset_tokens WHERE email = ?",
            [$request->email]
        );

        return response()->json([
            'success' => true,
            'message' => 'Your password has been reset successfully. You can now login with your new password.',
            'redirect' => route('login')
        ]);
    }
}