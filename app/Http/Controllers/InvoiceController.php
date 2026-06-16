<?php

namespace App\Http\Controllers;

use App\Mail\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\Invoice;
use App\Models\Tenant;
use App\Models\Activity;
use App\Models\Receipt;
use App\Models\Unit;
use App\Models\Landlord;
use App\Models\Property;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Show all invoices
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login First'); // Redirect to the login page
        }

        // Fetch paginated invoices with tenant, unit, and property relationships
        $invoices = Invoice::where('landlord_id', session('landlord_id'))
            ->with(['tenant', 'unit.property'])
            ->orderBy('due_date', 'DESC')
            ->paginate(5, ['*'], 'invoices_page'); // Custom page name for invoices

        // Fetch paginated receipts, ensuring each receipt is associated with its invoice
        $receipts = Receipt::with('invoice')
            ->orderBy('created_at', 'DESC')
            ->paginate(5, ['*'], 'receipts_page'); // Custom page name for receipts

        $tenants = Tenant::all();
        $page = 'invoices';

        return view('index', compact('invoices', 'page', 'tenants', 'receipts'));
    }

    public function getActiveActivities($tenantId)
    {
        // Fetch activities with related unit, property, and invoice records where the invoice status is 'pending'
        $activities = Activity::where('tenant_id', $tenantId)
            ->whereHas('invoice', function ($query) {
                $query->where('status', 'pending');
            })
            ->with([
                'unit:id,name,property_id', // Load unit data (id, name, and property_id fields)
                'unit.property:id,name', // Load property data (id and name fields)
                'invoice' // Load invoice data related to activity
            ])
            ->get(['id', 'unit_id']); // Select relevant fields from the activities table

        // Add remaining balance calculation for each invoice
        $activities->each(function ($activity) {
            if ($activity->invoice) {
                // Calculate the remaining balance by subtracting the total paid amount from the invoice amount
                $paidAmount = $activity->invoice->receipts->sum('amount');
                $activity->invoice->remaining_balance = $activity->invoice->amount - $paidAmount;
            }
        });

        return response()->json(['activities' => $activities]);
    }



    // function getPaidAmountForInvoice($invoiceId) {
    //     return DB::table('receipts')
    //         ->where('invoice_id', $invoiceId)
    //         ->where('status', 'paid') // Assuming 'status' indicates payment status
    //         ->sum('amount');
    // }
    public function getRemainingBalance($invoiceId)
    {
        $totalPaid = DB::table('receipts')
            ->where('invoice_id', $invoiceId)
            ->where('status', 'paid') // Assuming 'status' indicates payment status
            ->sum('amount');

        $invoice = DB::table('invoices')->where('id', $invoiceId)->first();
        $totalInvoiceAmount = $invoice->amount;

        $remainingBalance = $totalInvoiceAmount - $totalPaid;

        return response()->json(['remaining_balance' => $remainingBalance]);
    }


    public function pay(Request $request)
    {
        $tenantId = $request->tenant_id;
        $activityId = $request->activity_id;
        $amount = $request->amount;

        // Fetch activity details
        $activity = Activity::find($activityId);
        if (!$activity) {
            return redirect()->back()->with('error', 'Activity not found.');
        }

        $unit_id = $activity->unit_id;
        $amount_due = $activity->amount;
        $date_due = $activity->end_date;

        // Fetch tenant details
        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            return redirect()->back()->with('error', 'Tenant not found.');
        }

        $first_name = $tenant->first_name;
        $last_name = $tenant->last_name;
        $email = $tenant->email;

        // Fetch unit and landlord details
        $landlord_id = Unit::where('id', $unit_id)->value('landlord_id');
        $property_id = Unit::where('id', $unit_id)->value('property_id');
        $unit_name = Unit::where('id', $unit_id)->value('name');

        // Check if a pending invoice exists or create one
        $invoice = Invoice::firstOrCreate(
            ['activity_id' => $activityId, 'status' => 'pending'],
            [
                'amount' => $amount_due,
                'due_date' => $date_due,
                'status' => 'pending',
                'tenant_id' => $tenantId,
                'landlord_id' => $landlord_id,
                'unit_id' => $unit_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'ref' => uniqid(),
                'invoice_no' => uniqid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create the receipt
        $ref = uniqid();
        Receipt::create([
            'invoice_id' => $invoice->id,
            'tenant_id' => $tenantId,
            'landlord_id' => $landlord_id,
            'amount' => $amount,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'status' => 'paid',
            'ref' => $ref,
            'receipt_no' => $ref,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Calculate total receipts for the invoice
        $totalReceipts = Receipt::where('invoice_id', $invoice->id)->sum('amount');

        // Update invoice status if fully paid
        if ($totalReceipts >= $invoice->amount) {
            $invoice->update(['status' => 'paid']);
        }

        // Fetch landlord and property details for email notification
        $landlord_data = Landlord::where('user_id', $landlord_id)->first();
        $property_data = Property::find($property_id);

        $tenantName = $first_name . ' ' . $last_name;
        $landlordName = $landlord_data->first_name . ' ' . $landlord_data->last_name;
        $unit = $unit_name;
        $property = $property_data->name;
        $address = $property_data->address;
        $payment = 'Landlord Payment';
        $date = now();

        // Send email notification to the landlord
        Mail::to($email)->send(new Payment($tenantName, $landlordName, $unit, $property, $address, $amount, $payment, $date, $ref));

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }


    // Show a specific invoice
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login First'); // Redirect to the login page
        }
        // Fetch the invoice by its ID
        $invoice = Invoice::with(['tenant', 'unit.property'])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }
}
