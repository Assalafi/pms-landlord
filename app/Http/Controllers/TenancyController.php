<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Activity;

class TenancyController extends Controller
{
    // Display tenants list
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login First'); // Redirect to the login page
        }
        $data = Activity::orderBy('start_date', 'DESC')->get();
        $page = 'tenancy';
        return view('index', compact('data', 'page'));
    }
}
