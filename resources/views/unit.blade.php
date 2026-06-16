@php
    use App\Models\Permission;
    use App\Models\Invoice;
    $pages = Permission::where(['user_id' => session('user_id'), 'page' => 'property'])
        ->select('action')
        ->value('action');
    $pages = json_decode($pages) != null ? json_decode($pages) : [];

@endphp
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Unit: {{ $unit->name }}, Property: {{ $property->name }}</h5>
            <div class="row">
                <!-- Address Section -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold"> Address </h5>
                                    <p class="text-dark me-1 fs-3 mb-0">{{ $property->address }}</p>
                                    <!-- Use property address here -->
                                    <p class="text-dark me-1 fs-3 mb-0">{{ $property->state }}</p>
                                    <!-- Add more address details if needed -->
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-map-pin fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Annual Income Section -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold"> Annual Income </h5>
                                    <h4 class="fw-semibold mb-3">₦{{ number_format($unit->amount, 2) }}</h4>
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

                <!-- Total Income Section -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold"> Total Income </h5>
                                    <h4 class="fw-semibold mb-3">₦{{ number_format($totalIncome, 2) }}</h4>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="text-white bg-info rounded-circle p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-currency-dollar fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Current Tenant Section -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold"> Current Tenant </h5>
                                    @if ($tenant)
                                        <h4 class="fw-semibold mb-3">
                                            <a href="{{ route('tenants.show', $tenant->id) }}">
                                                {{ $tenant->first_name }} {{ $tenant->last_name }}
                                            </a>
                                        </h4>
                                    @else
                                        <h4 class="fw-semibold mb-3">No Tenant Assigned</h4>
                                    @endif
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="text-white bg-primary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-user fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Due Date Section -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold"> Next Due Date </h5>
                                    <h4 class="fw-semibold mb-3">
                                        {{ $latestPendingInvoice -> due_date ? date('d M, Y', strtotime($latestPendingInvoice -> due_date)) : 'N/A' }}
                                    </h4>
                                    <!-- Use end_datedate from activities -->
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="text-white bg-primary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-user fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History Section -->

            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-4">Payment History</h5>
                            <br>
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                        <tr>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">#</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Invoice No.</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Received On</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Amount</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($receipts as $invoice)
                                        @php
                                            $invoice_no = Invoice::where('id', $invoice->invoice_id)->first();
                                        @endphp
                                            <tr>
                                                <td class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">{{ $loop->iteration }}</h6>
                                                </td>
                                                <td class="border-bottom-0">
                                                    <p class="mb-0 fw-normal">{{ $invoice_no -> invoice_no}}</p>
                                                </td>
                                                <td class="border-bottom-0">
                                                    <p class="mb-0 fw-normal">
                                                        {{ date('d M, Y', strtotime($invoice->created_at)) }}</p>
                                                </td>
                                                <td class="border-bottom-0">
                                                    <p class="mb-0 fw-normal">{{ number_format($invoice->amount, 2) }}
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Previous Tenants Section -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-4">Previous Tenants</h5>

                            @if ($previousTenants->isEmpty())
                                <p>No previous tenants found.</p>
                            @else
                                @php
                                    // Ensure the tenants are unique based on a unique identifier like 'id' or 'email'
                                    $uniqueTenants = $previousTenants->unique('id'); // or use 'email' if it's unique
                                @endphp

                                <ul class="timeline-widget mb-0 position-relative mb-n5" id="tenantList">
                                    @foreach ($uniqueTenants->take(4) as $index => $tenant)
                                        <li class="timeline-item d-flex position-relative overflow-hidden">
                                            <div class="timeline-time text-dark flex-shrink-0 text-end">
                                                @if ($tenant->profile_picture)
                                                    <img src="{{ $tenant->profile_picture }}"
                                                        alt="{{ $tenant->first_name }} {{ $tenant->last_name }}"
                                                        width="35" height="35" class="rounded-circle">
                                                @else
                                                <img src="{{ url('assets/images/profile/user-1.jpg') }}" alt="" width="35" height="35" class="rounded-circle">
                                                @endif
                                            </div>
                                            <div class="timeline-desc fs-3 text-dark mt-n1">
                                                {{ $tenant->first_name }} {{ $tenant->last_name }}
                                                <a href="mailto:{{ $tenant->email }}"
                                                    class="text-primary d-block fw-normal">{{ $tenant->email }}</a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                                <!-- Show "More" button if there are more than four tenants -->
                                @if ($uniqueTenants->count() > 4)

                                    <div style="display: none;" id="brr">
                                        <br><br>
                                    </div>
                                    <!-- Hidden list of remaining tenants -->
                                    <ul class="timeline-widget mb-0 position-relative mb-n5" id="moreTenants"
                                        style="display: none;">
                                        @foreach ($uniqueTenants->slice(4) as $index => $tenant)
                                            <li class="timeline-item d-flex position-relative overflow-hidden">
                                                <div class="timeline-time text-dark flex-shrink-0 text-end">
                                                    @if ($tenant->profile_picture)
                                                        <img src="{{ $tenant->profile_picture }}"
                                                            alt="{{ $tenant->first_name }} {{ $tenant->last_name }}"
                                                            width="35" height="35" class="rounded-circle">
                                                    @else
                                                        <div class="initial-circle bg-{{ ['primary', 'secondary', 'success', 'danger'][$index % 4] }}"
                                                            style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; color: white;">
                                                            {{ strtoupper(substr($tenant->first_name, 0, 1)) }}{{ strtoupper(substr($tenant->last_name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="timeline-desc fs-3 text-dark mt-n1">
                                                    {{ $tenant->first_name }} {{ $tenant->last_name }}
                                                    <a href="mailto:{{ $tenant->email }}"
                                                        class="text-primary d-block fw-normal">{{ $tenant->email }}</a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <br>
                                    <div class="text-center mt-3">
                                        <a href="javascript:void(0)" id="showMoreButton" class="text-primary">View
                                            more</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- JavaScript to handle the "Show More" functionality -->
<script>
    document.getElementById('showMoreButton').addEventListener('click', function() {
        var moreTenants = document.getElementById('moreTenants');
        if (moreTenants.style.display === 'none') {
            moreTenants.style.display = 'block';
            this.innerText = 'View less';
            document.getElementById('brr').style.display = 'block';
        } else {
            moreTenants.style.display = 'none';
            this.innerText = 'View more';
            document.getElementById('brr').style.display = 'none';
        }
    });
</script>
