<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Tenant;
use App\Models\Activity; // Assuming an Activity model exists to check tenant activities
use Illuminate\Http\Request;

class TenantsController extends Controller
{
    // Display tenants list
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login First'); // Redirect to the login page
        }
        $tenants = Tenant::all();
        $page = 'tenants';
        return view('index', compact('tenants', 'page'));
    }

    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login First'); // Redirect to the login page
        }
        // Retrieve tenant with related data
        $tenant = Tenant::with(['units', 'invoices' => function ($query) {
            $query->where('status', 'pending'); // Only fetch pending invoices
        }])->findOrFail($id);

        $page = 'tenant';

        // Pass data to the view
        return view('index', compact('tenant', 'page'));
    }

    // Store a new tenant
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'phone' => 'required|numeric',
        ]);

        Tenant::create($validated);

        return redirect()->route('tenants.index')->with('success', 'Tenant added successfully.');
    }

    // Edit tenant form
    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login First'); // Redirect to the login page
        }
        $tenant = Tenant::findOrFail($id);
        $page = 'edit tenant';
        return view('index', compact('tenant', 'page'));
    }

    // Update tenant details
    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email,' . $id,
            'phone' => 'required|numeric',
        ]);

        $tenant->update($validated);

        return redirect()->route('tenants.index')->with('success', 'Tenant updated successfully.');
    }

    // Delete a tenant if no activities are linked
    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);

        // Check if the tenant has any active activities
        $hasActivities = Activity::where('tenant_id', $id)->exists();
        if ($hasActivities) {
            return redirect()->route('tenants.index')->withErrors('Cannot delete tenant with active activities.');
        }

        $tenant->delete();
        return redirect()->route('tenants.index')->with('success', 'Tenant deleted successfully.');
    }
}
