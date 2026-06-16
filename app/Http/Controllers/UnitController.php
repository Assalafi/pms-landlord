<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Receipt;
use App\Models\Invoice;

class UnitController extends Controller
{
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login First'); // Redirect to the login page
        }
        $unit = Unit::with(['property', 'tenant', 'activities', 'invoices'])->findOrFail($id);
        $property = $unit->property;
        $tenant = $unit->tenant;
        $invoices = $unit->invoices;
        $latestPendingInvoice = $invoices->where('status', 'pending')->sortByDesc('due_date')->first();
        // if there is no pending invoice, get the last due invoice and add 1 year to the due date
        if (!$latestPendingInvoice) {
            $latestPendingInvoice = $invoices->sortByDesc('due_date')->first();
            if ($latestPendingInvoice) {
                $latestPendingInvoice->due_date = date('Y-m-d', strtotime($latestPendingInvoice->due_date . ' + 1 year'));
            }
        }
        // if no tenant on the unit, display N/A
        if (!$tenant) {
            $tenant = 'N/A';
        }
        // for the previous tenants, dont include the active tenant that is currently on the unit, so first get the active tenant
        $activeTenant = $unit->tenant;
        // then get the previous tenants
        $previousTenants = $unit->previousTenants;
        // remove the active tenant from the previous tenants
        $previousTenants = $previousTenants->whereNotIn('id', [$activeTenant->id]);
        // get the unique tenants from the previous tenants

        //$previousTenants = $unit->previousTenants;
        $uniqueTenants = $previousTenants->unique('id');
        $totalIncome = $unit->invoices->sum('amount');
        $invoicesIds = $invoices->pluck('id')->toArray();
        $receipts = Receipt::whereIn('invoice_id', $invoicesIds)->where(['status' => 'paid'])->get();

        $page = "unit";

        return view('index', compact('unit', 'property', 'tenant', 'latestPendingInvoice', 'invoices', 'receipts', 'page', 'uniqueTenants', 'previousTenants', 'totalIncome'));
    }

    public function searchTenants(Request $request)
    {
        $search = $request->query('q');
        $tenants = Tenant::where('first_name', 'LIKE', "%{$search}%")
            ->orWhere('last_name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->get(['id', 'first_name', 'last_name', 'email']);

        return response()->json($tenants);
    }

    public function getUnitsByProperty(Request $request)
    {
        $propertyId = $request->input('property_id');

        if (!$propertyId) {
            return response()->json(['error' => 'Property ID is required'], 400);
        }

        $units = Unit::where('property_id', $propertyId)->where('status', 'vacant')->get(['id', 'name']);

        return response()->json($units);
    }
}
