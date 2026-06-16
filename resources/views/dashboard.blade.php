@php
    use App\Models\Unit;
    use App\Models\Landlord;
    use App\Models\Support;
    use App\Models\Invoice;
    use App\Models\Property;
    use App\Models\Repair;
    use App\Models\Receipt;

    // dd(session('user_type').' '.session('user_id'));

    if(session('acc_type') == 'landlord'){
        $profile = Landlord::where('user_id', session('user_id'))->first();
    }else{
        $profile = Support::where('user_id', session('user_id'))->first();
    }

    $annualInvoice = Invoice::where(['landlord_id' => session('user_id'), 'status' => 'paid'])->sum('amount');
    $due = Invoice::where(['landlord_id' => session('user_id'), 'status' => 'pending'])->sum('amount');
    $dueIds = Invoice::where(['landlord_id' => session('user_id'), 'status' => 'pending'])->pluck('id');
    $payment = Receipt::where(['landlord_id' => session('user_id'), 'status' => 'paid'])->whereIn('invoice_id', $dueIds)->sum('amount');
    $due = $due - $payment;
    $ppt = Property::where(['landlord_id' => session('user_id')])->count();
    $pptIds = Property::where(['landlord_id' => session('user_id')])->pluck('id');
    $repair = Repair::whereIn('property_id',$pptIds)->where(['status' => 'pending'])->count();
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-9 fw-semibold">Update Account Details</h4>
                <h6 class="fw-smeibold mb-3">Subtitle</h6>
                <div class="row d-flex align-items-center pb-1">
                    <div class="col-md-2" style="margin-top: 6px;">
                        <div class="btn btn-secondary btn-sm w-100">0 of 2</div>
                    </div>
                    <div class="col-md-8" style="margin-top: 6px;">
                        <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="70"
                            aria-valuemin="0" aria-valuemax="100" style="margin-top: 6px;">
                            <div class="progress-bar" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="col-md-2" style="margin-top: 6px;">
                        <a type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Update Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-3">
            <!-- Annual Earnings -->
            <div class="card">
                <div class="card-body">
                    <div class="row alig n-items-start">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold"> Annual Earnings </h5>
                            <h5 class="fw-semibold mb-3">N{{ number_format($annualInvoice, 2) }}</h5>
                            <div class="d-flex align-items-center pb-1">
                                <span
                                    class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-down-right text-danger"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div
                                    class="text-white bg-success rounded-circle p-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-currency-dollar fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <!-- Rents Due Earnings -->
            <div class="card">
                <div class="card-body">
                    <div class="row alig n-items-start">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold"> Rent Payments Due </h5>
                            <h5 class="fw-semibold mb-3">N{{ number_format($due, 2) }}</h5>
                            <div class="d-flex align-items-center pb-1">
                                <span
                                    class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-down-right text-danger"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div
                                    class="text-white bg-danger rounded-circle p-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-currency-dollar fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <!-- Properties -->
            <div class="card">
                <div class="card-body">
                    <div class="row alig n-items-start">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold"> Properties </h5>
                            <h5 class="fw-semibold mb-3">{{ $ppt }}</h5>
                            <div class="d-flex align-items-center pb-1">
                                <span
                                    class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-down-right text-danger"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div
                                    class="text-white bg-primary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-building fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <!-- Requests -->
            <div class="card">
                <div class="card-body">
                    <div class="row alig n-items-start">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold"> Requests </h5>
                            <h5 class="fw-semibold mb-3">{{ $repair }}</h5>
                            <div class="d-flex align-items-center pb-1">
                                <span
                                    class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-down-right text-danger"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div
                                    class="text-white bg-warning rounded-circle p-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-home-question fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end w-100" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasRightLabel">Account Verification</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">

        <div id="demo">
            <ul id="multistepform-progressbar">
                <li class="active">Personal Info</li>
                <li>BVN</li>
                <li>Bank Details</li>
            </ul>
            <div class="form">
                <form action="">
                    <h2 class="fs-title">Personal Info</h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control"
                                value="{{ $profile->first_name }}" required disabled>
                            @error('first_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control"
                                value="{{ $profile->last_name }}" required disabled>
                            @error('last_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ $profile->email }}" required disabled>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="number" name="phone" id="phone" class="form-control"
                                value="{{ $profile->phone }}" required>
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="address" class="form-label">Contact Address</label>
                            <textarea name="address" id="address" class="form-control" rows="5" required>{{ $profile->address }}</textarea>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </form>
                <input type="button" name="next" class="next button" value="Next">
            </div>
            <div class="form">
                <form action="">
                    <h2 class="fs-title">BVN</h2>
                    <h3 class="fs-subtitle">Verification</h3>
                    <input type="number" name="bvn" class="form-control" placeholder="Enter BVN" required>
                    <input type="button" name="previous" class="previous button" value="Previous">
                    <input type="button" name="next" class="next button" value="Next">
                </form>
            </div>
            <div class="form">
                <h2 class="fs-title">Bank Details</h2>
                <form id="bank-form" action="{{ url('/verify-bank') }}" method="POST">
                    @csrf
                    <!-- Bank Dropdown -->
                    <div class="mb-3">
                        <label for="bank_code" class="form-label">Select Bank</label>
                        <select id="bank_code" name="bank_code" class="form-control" required>
                            <option value="">-- Select Bank --</option>
                            @foreach ($banks['data'] as $bank)
                                <option value="{{ $bank['code'] }}">{{ $bank['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Account Number -->
                    <div class="mb-3">
                        <label for="account_number" class="form-label">Account Number</label>
                        <input type="text" oninput="getNumber()" id="account_number" name="account_number"
                            class="form-control" required maxlength="10" minlength="10">
                        <div id="validfeedback" style="display: none" class="invalid-feedback">
                        </div>
                    </div>

                    <!-- Account Name -->
                    <div class="mb-3">
                        <label for="account_name" class="form-label">Account Name</label>
                        <input type="text" id="account_name" name="account_name" class="form-control" readonly>
                    </div>

                    <input type="button" name="previous" class="previous button" value="Previous">
                    <input type="button" style="display: none" name="submit" id="finish" class="next button" value="Finish">
                </form>

            </div>
        </div>
    </div>
</div>
