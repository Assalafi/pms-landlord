<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Landlord;
use App\Models\Support;

class ProfileController extends Controller
{
    // Display tenants list
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login First');
        }

        if (session('acc_type') == 'landlord') {
            $profile = Landlord::where('user_id', session('user_id'))->first();
        } else {
            $profile = Support::where('user_id', session('user_id'))->first();
        }
        //$profile = Landlord::where('user_id', session('user_id'))->first();
        $support = Support::where('landlord_id', session('user_id'))->get();
        $page = 'profile';
        return view('index', compact('profile', 'page', 'support'));
    }

    // Update tenant details
    public function update(Request $request)
    {
        if (session('acc_type') == 'landlord') {
            $profile = Landlord::findOrFail($request->id);
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|numeric',
                'address' => 'required|string',
            ]);
        } else {
            $profile = Support::findOrFail($request->id);
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|numeric',
                'address' => 'required|string',
            ]);
        }

        $profile->update($validated);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }
}
