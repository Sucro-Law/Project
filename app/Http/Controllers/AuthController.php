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
    /**
     * Show the login form
     */
    public function showLogin()
    {
        return view('authfolder.signin');
    }

    /**
     * Show the signup form
     */
    public function showSignup()
    {
        return view('authfolder.signup');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:student,faculty,admin'
        ]);

        // Determine account type based on role
        $accountType = ucfirst($request->role);
        
        // Try to find user by school_id or email
        $user = User::where(function($query) use ($request) {
            $query->where('school_id', $request->username)
                  ->orWhere('email', $request->username);
        })
        ->where('account_type', $accountType)
        ->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Login the user
        Auth::login($user);

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'redirect' => route('dashboard')
        ]);
    }

    /**
     * Generate unique user ID with PN- prefix
     */
    private function generateUserId()
    {
        // Get the last user_id
        $lastUser = User::orderBy('user_id', 'desc')->first();
        
        if (!$lastUser) {
            // First user, start from 1
            return 'PN-00000001';
        }
        
        // Extract the numeric part from the last user_id (e.g., PN-00000001 -> 1)
        $lastNumber = (int) str_replace('PN-', '', $lastUser->user_id);
        
        // Increment by 1
        $newNumber = $lastNumber + 1;
        
        // Format with leading zeros (8 digits)
        return 'PN-' . str_pad($newNumber, 8, '0', STR_PAD_LEFT);
    }

    /**
     * Handle signup request
     */
    public function signup(Request $request)
    {
        $request->validate([
            'role' => 'required|in:student,faculty',
            'school_id' => 'required|string|unique:users,school_id',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate unique user ID (PN-)
        $userId = $this->generateUserId();
        
        // Use the school_id from user input (not auto-generated)
        $schoolId = $request->school_id;

        // Create full name
        $fullName = trim($request->first_name . ' ' . 
                        ($request->middle_name ? $request->middle_name . ' ' : '') . 
                        $request->last_name);

        // Determine account type
        $accountType = ucfirst($request->role);

        // Store plain password for email (will be sent once then discarded)
        $plainPassword = $request->password;

        // Create user
        $user = User::create([
            'user_id' => $userId,
            'school_id' => $schoolId,
            'full_name' => $fullName,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'account_type' => $accountType,
        ]);

        // Send email with credentials
        try {
            Mail::send('emails.registration', [
                'user' => $user,
                'userId' => $userId,
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

        // Don't auto-login after signup - redirect to login page

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Your credentials have been sent to your email. Please login to continue.',
            'user_id' => $userId,
            'school_id' => $schoolId,
            'redirect' => route('login')
        ]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPassword()
    {
        return view('authfolder.forgot-password');
    }

    /**
     * Send password reset link via email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Don't reveal if email exists or not for security
            return response()->json([
                'success' => true,
                'message' => 'If an account exists with this email, you will receive a password reset link shortly.'
            ]);
        }

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Generate token
        $token = Str::random(64);

        // Store token in database
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        // Create reset URL
        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($request->email));

        // Get expiry time from config (default 60 minutes)
        $expiryMinutes = config('auth.passwords.users.expire', 60);

        // Send email
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

    /**
     * Show the password reset form
     */
    public function showResetPassword(Request $request, $token)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('login')->with('error', 'Invalid password reset link.');
        }

        return view('authfolder.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        // Find the token record
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired password reset link.'
            ], 400);
        }

        // Check if token is expired (default 60 minutes)
        $expiryMinutes = config('auth.passwords.users.expire', 60);
        $tokenCreatedAt = \Carbon\Carbon::parse($tokenRecord->created_at);

        if ($tokenCreatedAt->addMinutes($expiryMinutes)->isPast()) {
            // Delete expired token
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json([
                'success' => false,
                'message' => 'Password reset link has expired. Please request a new one.'
            ], 400);
        }

        // Verify token
        if (!Hash::check($request->token, $tokenRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password reset link.'
            ], 400);
        }

        // Find user and update password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Your password has been reset successfully. You can now login with your new password.',
            'redirect' => route('login')
        ]);
    }
}