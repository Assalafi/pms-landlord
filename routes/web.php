<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TenantsController;
use App\Http\Controllers\TenancyController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BVNVerificationController;
use App\Models\Invoice;
use App\Http\Controllers\BankController;
use App\Services\PaystackService;

//Route::post('/verify-bank-account', [BankController::class, 'verifyBankAccount']);
Route::get('/verify-bank-form', [BankController::class, 'showVerifyBankForm']);
Route::post('/verify-bank', [BankController::class, 'verifyBankAccount']);

Route::get('/verify-bvn', [BVNVerificationController::class, 'verifyBVN']);
// Public routes
Route::get('/', function () {
    return view('login');
})->name('login');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/create-user', [AuthController::class, 'createUser'])->name('createUser');
Route::post('/login', [AuthController::class, 'loginUser'])->name('loginUser');
Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('updatePassword');

// Forgot Password Request
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot.password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail']);

// Password Reset
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Route::get('/home', function () {
//     if (!Auth::check()) {
//         return redirect()->route('login')->with('success', 'Please Login First');
//     }
//     return view('index', ['page' => 'dashboard']);
// })->name('home');

Route::get('/home', function (PaystackService $paystackService) {
    if (!Auth::check()) {
        return redirect()->route('login')->with('success', 'Please Login First');
    }

    // Fetch the list of Nigerian banks
    $banks = $paystackService->getNigerianBanks();
    //dd($banks);

    return view('index', ['page' => 'dashboard', 'banks' => $banks]);
})->name('home');

Route::get('/confirm-email/{id}', [AuthController::class, 'confirmEmail'])->name('confirm.email');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// Protected routes
Route::middleware('checkSession')->group(function () {
    // Route::get('/home', function () {
    //     return view('index', ['page' => 'dashboard']);
    // })->name('home');

    // Route for logging out the user
});

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile-update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/tenants', [TenantsController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/{id}', [TenantsController::class, 'show'])->name('tenants.show');
    Route::resource('tenants', TenantsController::class);


    Route::get('/tenancy', [TenancyController::class, 'index'])->name('tenancy.index');

    Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');


    Route::get('/mail', [PropertyController::class, 'mail'])->name('properties.mail');


    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');

    Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

    // Route::post('/units/{unit}/assignTenant', [PropertyController::class, 'assignTenant'])->name('units.assignTenant');
    Route::post('/units/{property}', [PropertyController::class, 'addUnit'])->name('units.show');
    Route::post('/tenants/{property}', [PropertyController::class, 'addUnit'])->name('tenants.show');
    Route::post('/units/{property}/add', [PropertyController::class, 'addUnit'])->name('units.add');
    Route::put('/units/{unit}/update', [PropertyController::class, 'updateUnit'])->name('units.update');
    Route::delete('/units/{unit}/delete', [PropertyController::class, 'deleteUnit'])->name('units.delete');

    Route::get('/search-tenants', [PropertyController::class, 'searchTenants']);
    Route::post('/units/{unit}/assignTenant', [PropertyController::class, 'assignTenant'])->name('units.assignTenant');
    Route::post('/tenants/{unit}/assignTenant', [PropertyController::class, 'addTenant'])->name('tenants.addTenant');

    Route::put('/units/{unit}/vacateTenant', [PropertyController::class, 'vacateTenant'])->name('units.vacateTenant');

    Route::get('/units/{id}', [UnitController::class, 'show'])->name('units.show');
    Route::get('/search-tenantss', [UnitController::class, 'searchTenants'])->name('search.tenants');
    Route::get('/get-units', [UnitController::class, 'getUnitsByProperty']);

    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/get-active-activities/{tenantId}', [InvoiceController::class, 'getActiveActivities']);
    Route::post('/invoices/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');


    Route::get('/repairs', [RepairController::class, 'index'])->name('repair.index');
    Route::get('/repairs/{id}', [RepairController::class, 'show'])->name('repair.show');
    Route::post('/repairs', [RepairController::class, 'store'])->name('repair.store');
    Route::post('/repairs/{property}', [RepairController::class, 'update'])->name('repair.update');
    Route::delete('/repairs/{property}', [RepairController::class, 'destroy'])->name('repair.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/permission/{property}', [UserController::class, 'edit'])->name('permission.edit');
    Route::post('/permission/{property}', [UserController::class, 'update'])->name('permission.update');
    Route::post('/permission/update/ajax', [UserController::class, 'updatePermissionAjax'])->name('permission.update.ajax');
    Route::delete('/users/{property}', [UserController::class, 'destroy'])->name('users.destroy');
});
