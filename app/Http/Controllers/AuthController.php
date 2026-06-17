<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmAccount;
use App\Mail\ForgotPassword;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Landlord;
use App\Models\Support;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{

    public function createUser(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users,email',
            'password'   => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create user in the users table
        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'status' => '0'
        ]);

        // Create landlord in the landlords table
        Landlord::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'user_id'    => $user->id,
            'email'      => $request->email,
        ]);

        $landlord = $request->first_name . ' ' . $request->last_name;

        Mail::to($request->email)->send(new ConfirmAccount($landlord, $user->id));

        // Redirect or send response after successful registration
        return redirect()->route('login')->with('success', 'Registration successful! Please check your email to verify your account.');
    }
    public function loginUser(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Attempt to log the user in
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            // Login successful, store user details in session
            $user = Auth::user();
            session([
                'user_id' => $user->id,
                'email' => $user->email,
                'status' => $user->acc_status,
                'acc_type' => $user->acc_type,
                'properties' => $user->property != null ? $user->property : '["0"]',
                'landlord_id' => $user->acc_type == 'landlord' ? $user->id : Support::where('email', $user->email)->first()->landlord_id,
            ]);

            if ($user->status == '0') {

                // Clear the session data
                $request->session()->flush();

                // Log the user out
                Auth::logout();

                // Invalidate and regenerate session token
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Please verify your email address.'])->withInput();
            }

            // Redirect to home (dashboard) after login
            return redirect()->intended('home')->with('success', 'Login successful!');
        }

        // Login failed, redirect back with error
        return back()->withErrors(['email' => 'Invalid email or password'])->withInput();
    }

    public function confirmEmail(Request $request, $id)
    {
        User::where('id', $id)->update(['status' => '1']);

        // Clear the session data
        $request->session()->flush();
        // Log the user out
        Auth::logout();

        // Invalidate and regenerate session token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Account verified successfully. Please log in.');
    }
    public function updatePassword(Request $request)
    {
        // Validate the input
        // check if new password lenght is greater than 6
        if (strlen($request->new_password) < 6) {
            return response()->json(['error' => 'Password must be at least 6 characters'], 422);
        }

        if ($request->new_password != $request->new_password_confirmation) {
            return response()->json(['error' => 'New password and confirmation password do not match'], 422);
        }

        // Get the authenticated user
        $user = User::where('id', session('user_id'))->first();

        // Check if the old password matches the stored password
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'Old password does not match'], 422);
        }

        // Update the user's password
        $user->password = Hash::make($request->new_password);
        $user->acc_status = 1; // Set status to 1
        $user->save();

        // update session('status') to 1
        session(['status' => 1]);

        return response()->json(['message' => 'Password updated successfully']);
    }

    // Show Forgot Password Form
    public function showForgotPasswordForm()
    {
        return view('forgot-password'); // Create this view
    }

    // Send Reset Link Email
    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid email format'], 422);
        }

        try {
            $user = User::where('email', $request->email)->first();
        } catch (\Exception $e) {
            \Log::error('Database query failed: ' . $e->getMessage());
            return response()->json(['error' => 'Database error. Please try again.'], 422);
        }
        
        if (!$user) {
            return response()->json(['error' => 'Email not found'], 422);
        }

        // Delete any existing reset tokens for this user
        try {
            DB::table('password_resets')->where('email', $request->email)->delete();
        } catch (\Exception $e) {
            \Log::error('Failed to delete existing tokens: ' . $e->getMessage());
            return response()->json(['error' => 'Database error. Please try again.'], 422);
        }

        // Generate token
        try {
            $token = Str::random(60);
        } catch (\Exception $e) {
            \Log::error('Token generation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Token generation failed. Please try again.'], 422);
        }

        // Store token in password_resets table
        try {
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to store reset token: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to store reset token. Please try again.'], 422);
        }

        // Get user name
        try {
            if ($user->acc_type == 'landlord') {
                $landlord = Landlord::where('user_id', $user->id)->first();
                $user_name = $landlord ? $landlord->first_name . ' ' . $landlord->last_name : $user->email;
            } else {
                $user_name = $user->email;
            }
        } catch (\Exception $e) {
            \Log::error('Failed to get user name: ' . $e->getMessage());
            $user_name = $user->email; // Fallback to email
        }

        // Send email
        try {
            Mail::to($request->email)->send(new ForgotPassword($user_name, $token));
            return response()->json(['message' => 'Password reset link sent!'], 200);
        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('Forgot password email failed: ' . $e->getMessage());
            \Log::error('Email being sent to: ' . $request->email);
            \Log::error('Error trace: ' . $e->getTraceAsString());
            
            // Return more specific error message
            if (strpos($e->getMessage(), 'connection') !== false) {
                return response()->json(['error' => 'Mail server connection failed. Please check mail configuration.'], 422);
            } elseif (strpos($e->getMessage(), 'authentication') !== false) {
                return response()->json(['error' => 'Mail authentication failed. Please check mail credentials.'], 422);
            } else {
                return response()->json(['error' => 'Failed to send reset link: ' . $e->getMessage()], 422);
            }
        }
    }

    // Show Reset Password Form
    public function showResetPasswordForm($token)
    {
        // Get email from token
        $resetRecord = DB::table('password_resets')
            ->where('token', $token)
            ->first();
            
        if (!$resetRecord) {
            return redirect('/forgot-password')->with('error', 'Invalid or expired reset link.');
        }
        
        return view('reset-password', ['token' => $token, 'email' => $resetRecord->email]);
    }

    // Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        // Find the reset token
        $resetRecord = DB::table('password_resets')
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return response()->json(['error' => 'Invalid token'], 422);
        }

        // Check if token is expired (60 minutes)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('token', $request->token)->delete();
            return response()->json(['error' => 'Token has expired'], 422);
        }

        // Find user and update password
        $user = User::where('email', $resetRecord->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 422);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->status = 1; // Set status to 1 after resetting password
        $user->save();

        // Delete the reset token
        DB::table('password_resets')->where('token', $request->token)->delete();

        return response()->json(['message' => 'Password reset successfully'], 200);
    }

    public function logout(Request $request)
    {
        // Clear the session data
        $request->session()->flush();

        // Log the user out
        Auth::logout();

        // Invalidate and regenerate session token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
