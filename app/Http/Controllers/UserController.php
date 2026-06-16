<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\AccountPasswordSupport;
use App\Models\Landlord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Permission;
use App\Models\User;
use App\Models\Support;
use App\Models\Property;

class UserController extends Controller
{
    public function store(Request $request)
    {
        //dd($request);
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            // Validate permissions
            'property' => 'nullable|array',
            'unit' => 'nullable|array',
            'tenant' => 'nullable|array',
            'invoice' => 'nullable|array',
            'repair' => 'nullable|array',
            'MagicSuggest' => 'nullable|array',
        ]);

        // Create the user (assuming you have a User model)

        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz@&*()$';
        $password = substr(str_shuffle(str_repeat($characters, 8)), 0, 8);

        // dd($validated['MagicSuggest']);

        $user = User::create([
            'email' => $validated['email'],
            'acc_type' => 'user',
            'property' => $validated['MagicSuggest'] ? json_encode($validated['MagicSuggest']) : [],
            'password' => Hash::make($password),
        ]);

        $support = Support::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'user_id' => $user->id,
            'landlord_id' => session('user_id'),
        ]);

        // Define the permissions as pages and their associated actions
        $permissions = [
            'property' => $validated['property'] ?? [],
            'unit' => $validated['unit'] ?? [],
            'tenant' => $validated['tenant'] ?? [],
            'invoice' => $validated['invoice'] ?? [],
            'repair' => $validated['repair'] ?? [],
        ];
        $property = '';

        // Store the permissions in the permissions table
        foreach ($validated['MagicSuggest'] as $pem) {
            foreach ($permissions as $page => $actions) {
                if (count($actions) > 0) {
                    // Convert actions array to a comma-separated string
                    $actionsStr = implode(',', $actions);

                    // Store the permission in the database
                    Permission::create([
                        'user_id' => $user->id,
                        'landlord_id' => session('user_id'),
                        'page' => $page,
                        'action' => json_encode($actions),
                        'property_id' => $pem
                    ]);
                }
            }
            if ($pem != '') {
                $property .= (Property::where('id', $pem)->first()->name) . ',';
            }

            $address = Property::where('id', $pem)->first()->address;
        }

        $tenants = $validated['first_name'] . ' ' . $validated['last_name'];
        $units = '';
        $landlord = Landlord::where('user_id', session('user_id'))->first()->first_name . ' ' . Landlord::where('user_id', session('user_id'))->first()->last_name;

        Mail::to($validated['email'])->send(new AccountPasswordSupport($tenants, $landlord, $units, $property, $address, $password));

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function edit(Request $request, $user_id)
    {
        $selectedProperty = $request->input('property') ?? 1; // Default property ID

        // Fetch all properties
        $properties = Property::where('landlord_id', session('user_id'))->get();

        // Fetch permissions for the user filtered by property
        $permissions = Permission::where('user_id', $user_id)
            ->where('property_id', $selectedProperty)
            ->get()
            ->groupBy('page')
            ->map(function ($group) {
                return $group->pluck('action')->toArray();
            });
        $permissions = collect($permissions)->map(function ($actions) {
            return array_map('trim', json_decode($actions[0], true));
        });
        //dd($user_id);
        $page = 'profile edit';

        return view('index', compact('properties', 'permissions', 'selectedProperty', 'page', 'user_id'));
    }
    public function updatePermissionAjax(Request $request)
    {
        $user_id = auth()->id();  // Or get the user ID however you prefer

        // Validate incoming request
        $request->validate([
            'entity' => 'required|string',
            'action' => 'required|string',
            'checked' => 'required|boolean',
        ]);

        $page = $request->input('entity');
        $action = $request->input('action');
        $checked = $request->input('checked');
        $user_id = $request->input('user_id');
        $property_id = $request->input('property_id');

        // If checked is 1, add the permission, else remove it
        if ($checked) {

            // i have a action column that stores data like this ["add","edit","vacate"] so i need to add or remove the action from the array
            $permission = Permission::where('user_id', $user_id)
                ->where('page', $page)
                ->where('property_id', $property_id)
                ->first();

            if ($permission) {
                $actions = json_decode($permission->action, true);
                if (!in_array($action, $actions)) {
                    $actions[] = $action;
                }
                $permission->action = json_encode($actions);
                $permission->save();
            } else {
                $permission = new Permission();
                $permission->user_id = $user_id;
                $permission->page = $page;
                $permission->action = json_encode([$action]);
                $permission->property_id = $property_id;
                $permission->landlord_id = session('user_id');
                $permission->save();
            }

            return response()->json(['message' => 'Add ' . $action]);
        } else {
            $permission = Permission::where('user_id', $user_id)
                ->where('page', $page)
                ->where('property_id', $property_id)
                ->first();

            if ($permission) {
                $actions = json_decode($permission->action, true);
                if (($key = array_search($action, $actions)) !== false) {
                    unset($actions[$key]);
                }
                $permission->action = json_encode($actions);
                $permission->save();
            }
            return response()->json(['message' => 'Remove']);
        }
        //return response()->json(['message' => 'Permission updated successfully!']);

    }
}
