<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmAccount;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Landlord;
use App\Models\Support;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent!'], 200);
        } else {
            return response()->json(['error' => 'Email not found'], 422);
        }
    }

    // Show Reset Password Form
    public function showResetPasswordForm($token)
    {
        return view('reset-password', ['token' => $token]); // Create this view
    }

    // Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'status' => 1, // Set status to 1 after resetting password
                ])->save();

                $user->setRememberToken(Str::random(60));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully'], 200);
        } else {
            return response()->json(['error' => 'Invalid token'], 422);
        }
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
