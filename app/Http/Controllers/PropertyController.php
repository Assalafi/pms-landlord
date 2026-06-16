<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\AccountPasswordMail;
use App\Mail\AssignUnit;
use Illuminate\Support\Facades\Mail;


use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Activity;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Landlord;

class PropertyController extends Controller
{
    function password($length = 8) {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz@&*()$';
        return substr(str_shuffle(str_repeat($characters, $length)), 0, $length);
    }

    public function mail()
    {
        $userEmail = "triplea139@gmail.com";
        $tenant = "Abubakar Assalafi";
        $landlord = "Tahir Ahidjo";
        $unit = "B12";
        $property = "T Estate";
        $address = "Abuja";
        $password = "12345678";
        Mail::to($userEmail)->send(new AccountPasswordMail($tenant, $landlord, $unit, $property, $address, $password));
        return 'Password email sent successfully!';
    }
    function sendPasswordEmail($userEmail, $tenant, $landlord, $unit, $property, $address, $password)
    {
        Mail::to($userEmail)->send(new AccountPasswordMail($tenant, $landlord, $unit, $property, $address, $password));
        return 'Password email sent successfully!';
    }
    // Display a list of properties
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login First'); // Redirect to the login page
        }
        $properties = Property::where('landlord_id', Auth::id())->orWhereIn('id', json_decode(session('properties')))->get(); // Only show properties belonging to logged-in landlord
        $page = 'properties';
        return view('index', compact('properties', 'page'));
    }

    // Store a new property
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        Property::create([
            'name' => $request->name,
            'address' => $request->address,
            'landlord_id' => Auth::id(),
        ]);

        return redirect()->route('properties.index')->with('success', 'Property added successfully!');
    }

    // Update an existing property
    public function update(Request $request, Property $property)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $property->update([
            'name' => $request->name,
            'address' => $request->address,
        ]);

        return redirect()->route('properties.index')->with('success', 'Property updated successfully!');
    }

    // Delete a property
    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->route('properties.index')->with('success', 'Property deleted successfully!');
    }
    public function show(Property $property)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login First'); // Redirect to the login page
        }
        $page = 'property';
        $units = Unit::where('property_id', $property->id)->orderBy('name', 'asc')->get();
        $income = Unit::where('property_id', $property->id)->sum('amount');
        $tenants = Unit::where(['property_id' => $property->id, 'status' => 'occupied'])->get();
        return view('index', compact('property', 'page', 'units', 'tenants', 'income'));
    }
    // AJAX method to search tenants by email
    public function searchTenants(Request $request)
    {
        $tenants = Tenant::where('email', 'like', '%' . $request->email . '%')->get();

        return response()->json($tenants);
    }
    public function assignTenant(Request $request, $unitId)
    {
        try {
            // Validate request data
            $request->validate([
                'tenant_email' => 'required|email',
                'tenant_first_name' => 'required|string',
                'tenant_last_name' => 'required|string',
                'start_date' => 'required|date',
            ]);

            $tenant = Tenant::firstOrCreate(
                ['email' => $request->tenant_email],
                [
                    'first_name' => $request->tenant_first_name,
                    'last_name' => $request->tenant_last_name,
                ]
            );

            $unit = Unit::findOrFail($unitId);

            if (empty($unit->amount)) {
                throw new \Exception('Unit amount is missing');
            }

            $unit->tenant_id = $tenant->id;
            $unit->status = 'occupied';
            $unit->save();

            $activity = Activity::create([
                'tenant_id' => $tenant->id,
                'unit_id' => $unit->id,
                'status' => 'assigned',
                'start_date' => date('Y-m-d', strtotime($request->start_date)),
                'end_date' => date('Y-m-d', strtotime('+1 year', strtotime($request->start_date))),
                'amount' => $unit->amount,
            ]);

            $lastInvoice = Invoice::latest('id')->first();

            if ($lastInvoice) {
                $lastInvoiceNo = intval($lastInvoice->invoice_no);
                $newInvoiceNo = str_pad($lastInvoiceNo + 1, 7, '0', STR_PAD_LEFT);
            } else {
                // No invoices yet, start with '0000001'
                $newInvoiceNo = '0000001';
            }

            Invoice::create([
                'activity_id' => $activity->id,
                'unit_id' => $unit->id,
                'landlord_id' => $unit->landlord_id,
                'tenant_id' => $tenant->id,
                'first_name' => $tenant->first_name,
                'last_name' => $tenant->last_name,
                'email' => $tenant->email,
                'amount' => $unit->amount,
                'due_date' => date('Y-m-d', strtotime($request->start_date)),
                'status' => 'pending',
                'ref' => $newInvoiceNo,
                'invoice_no' => $newInvoiceNo,
            ]);



            $userEmail = $request->tenant_email;
            $tenants = ($request->tenant_first_name) . ' ' . ($request->tenant_last_name);
            $landlord = $request->landlord_name;
            $units = $unit->name;
            $property = $request->ppt_name;
            $address = $request->ppt_address;
            $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz@&*()$';
            $password = substr(str_shuffle(str_repeat($characters, 8)), 0, 8);


            $checkUserExist = User::where('email', $request->tenant_email)->first();

            if ($checkUserExist) {
                Mail::to($userEmail)->send(new AssignUnit($tenants, $landlord, $units, $property, $address, $password));
            } else {
                $new = User::firstOrCreate(
                    ['email' => $request->tenant_email],
                    [
                        'password' => Hash::make($password),
                        'acc_type' => 'tenant',
                    ]
                );

                Mail::to($userEmail)->send(new AccountPasswordMail($tenants, $landlord, $units, $property, $address, $password));
            }

            return redirect()->back()->with('success', 'Tenant assigned and invoice created successfully.');
        } catch (\Exception $e) {
            dd($e);
            // Handle any errors and log them
            \Log::error('Error assigning tenant and creating invoice: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while assigning the tenant. Please try again.');
        }
    }
    public function addTenant(Request $request, $idd)
    {
        try {
            // Validate request data
            $request->validate([
                'tenant_email' => 'required|email',
                'tenant_first_name' => 'required|string',
                'tenant_last_name' => 'required|string',
                'start_date' => 'required|date',
            ]);
            $unitId = $request->unit_id;

            $tenant = Tenant::firstOrCreate(
                ['email' => $request->tenant_email],
                [
                    'first_name' => $request->tenant_first_name,
                    'last_name' => $request->tenant_last_name,
                ]
            );

            $unit = Unit::findOrFail($unitId);

            $landlords = Landlord::where('user_id', $unit->landlord_id)->first();
            //dd($landlords);

            if (empty($unit->amount)) {
                throw new \Exception('Unit amount is missing');
            }

            $unit->tenant_id = $tenant->id;
            $unit->status = 'occupied';
            $unit->save();

            $activity = Activity::create([
                'tenant_id' => $tenant->id,
                'unit_id' => $unit->id,
                'status' => 'assigned',
                'start_date' => date('Y-m-d', strtotime($request->start_date)),
                'end_date' => date('Y-m-d', strtotime('+1 year', strtotime($request->start_date))),
                'amount' => $unit->amount,
            ]);

            $lastInvoice = Invoice::latest('id')->first();

            if ($lastInvoice) {
                $lastInvoiceNo = intval($lastInvoice->invoice_no);
                $newInvoiceNo = str_pad($lastInvoiceNo + 1, 7, '0', STR_PAD_LEFT);
            } else {
                // No invoices yet, start with '0000001'
                $newInvoiceNo = '0000001';
            }

            Invoice::create([
                'activity_id' => $activity->id,
                'unit_id' => $unit->id,
                'landlord_id' => $unit->landlord_id,
                'tenant_id' => $tenant->id,
                'first_name' => $tenant->first_name,
                'last_name' => $tenant->last_name,
                'email' => $tenant->email,
                'amount' => $unit->amount,
                'due_date' => date('Y-m-d', strtotime($request->start_date)),
                'status' => 'pending',
                'ref' => $newInvoiceNo,
                'invoice_no' => $newInvoiceNo,
            ]);

            $userEmail = $tenant->email;
            $tenants = ($tenant->first_name) . ' ' . ($tenant->last_name);
            $landlord = $landlords->first_name . ' ' . $landlords->last_name;
            $units = $unit->name;
            $property = $unit->property->ppt_name;
            $address = $unit->property->ppt_address;
            $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz@&*()$';
            $password = substr(str_shuffle(str_repeat($characters, 8)), 0, 8);
            //dd($userEmail);


            $checkUserExist = User::where('email', $tenant->email)->first();

            if ($checkUserExist) {
                Mail::to($userEmail)->send(new AssignUnit($tenants, $landlord, $units, $property, $address, $password));
            } else {
                $new = User::firstOrCreate(
                    ['email' => $request->tenant_email],
                    [
                        'password' => Hash::make($password),
                        'acc_type' => 'tenant',
                    ]
                );

                Mail::to($userEmail)->send(new AccountPasswordMail($tenants, $landlord, $units, $property, $address, $password));
            }

            return redirect()->back()->with('success', 'Tenant assigned and invoice created successfully.');
        } catch (\Exception $e) {
            dd($e);
            // Handle any errors and log them
            \Log::error('Error assigning tenant and creating invoice: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while assigning the tenant. Please try again.');
        }
    }


    // Add a new unit
    public function addUnit(Request $request, $propertyId)
    {
        $request->validate([
            'name' => 'required|string',
            'no_of_rooms' => 'required|integer',
            'no_of_baths' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        Unit::create([
            'property_id' => $propertyId,
            'landlord_id' => auth()->id(),
            'name' => $request->name,
            'no_of_rooms' => $request->no_of_rooms,
            'no_of_baths' => $request->no_of_baths,
            'amount' => $request->amount,
            'status' => 'vacant',
        ]);

        return redirect()->back()->with('success', 'Unit added successfully!');
    }

    // Update an existing unit
    public function updateUnit(Request $request, $unitId)
    {
        $request->validate([
            'name' => 'required|string',
            'no_of_rooms' => 'required|integer',
            'no_of_baths' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        $unit = Unit::find($unitId);
        $unit->update([
            'name' => $request->name,
            'no_of_rooms' => $request->no_of_rooms,
            'no_of_baths' => $request->no_of_baths,
            'amount' => $request->amount,
        ]);

        return redirect()->back()->with('success', 'Unit updated successfully!');
    }

    public function vacateTenant($unitId)
    {
        // Find the unit
        $unit = Unit::findOrFail($unitId);

        if ($unit->tenant_id) {
            // Log the vacating activity
            Activity::create([
                'tenant_id' => $unit->tenant_id,
                'unit_id' => $unit->id,
                'status' => 'vacated',
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d'),
                'amount' => $unit->amount,
            ]);

            // Update the unit to be vacant and remove the tenant
            $unit->tenant_id = null;
            $unit->status = 'vacant';
            $unit->save();

            return redirect()->back()->with('success', 'Tenant vacated successfully and unit is now vacant.');
        }

        return redirect()->back()->with('error', 'No tenant found in this unit.');
    }

    // Delete a unit
    public function deleteUnit($unitId)
    {
        $unit = Unit::find($unitId);

        if ($unit->status === 'vacant') {
            $unit->delete();
            return redirect()->back()->with('success', 'Unit deleted successfully!');
        }

        return redirect()->back()->with('error', 'Cannot delete an occupied unit.');
    }
}
