<?php

namespace App\Http\Controllers;

use App\Mail\RepairUpdate;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Tenant;
use App\Models\Activity;
use App\Models\Receipt;
use App\Models\Comment;
use App\Models\Repair;
use App\Models\Property;
use App\Models\UploadFile;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    // Show all invoices
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login First'); // Redirect to the login page
        }
        // Fetch all invoices with tenant, unit, and property relationships
        $ppt = Property::where('landlord_id', session('landlord_id'))->pluck('id');

        $data = Repair::whereIn('property_id', $ppt)->orderBy('id', 'desc')->get();
        $page = 'repairs';

        return view('index', compact('page', 'data'));
    }

    // Show a specific invoice

    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success', 'Please Login First');
        }
        $data = Repair::findOrFail($id);

        // Fetch associated files for the repair record
        $files = UploadFile::where('table_name', 'repair')
            ->where('row_id', $id)
            ->get();
        $page = 'repair';
        return view('index', compact('data', 'files', 'page'));
    }

    public function update(Request $request, $id)
    {
        Repair::where('id', $id)->update([
            'status' => $request->status,
        ]);

        if($request->comment) {
            Comment::create([
                'table_name' => 'repair',
                'row_id' => $id,
                'comment' => $request->comment,
                'status' => '1'
            ]);
        }
        Mail::to($request->email)->send(new RepairUpdate($request->tenant, $request->ref));

        return redirect()->route('repair.index')->with('success', 'Request updated successfully!');
    }
}
