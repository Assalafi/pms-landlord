<!-- resources/views/tenants/show.blade.php -->
@php
    use App\Models\Unit;
    use App\Models\Repair;
    use App\Models\Property;
    use App\Models\Activity;
    use App\Models\Invoice;
    $currentInvoice = Invoice::where('tenant_id', $tenant->id)
        ->orderBy('created_at', 'desc')
        ->first();
    $totalPaidInvoice = Invoice::where('tenant_id', $tenant->id)
        ->where('status', 'paid')
        ->sum('amount');
    $activities = Activity::where('tenant_id', $tenant->id)
        ->orderBy('created_at', 'desc')
        ->limit(1)
        ->get();
    $activity = Activity::where('tenant_id', $tenant->id)
        ->orderBy('created_at', 'desc')
        ->get();
    $activityNo = Activity::where('tenant_id', $tenant->id)->count();
    $no = 0;
@endphp
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">{{ $tenant->first_name }} {{ $tenant->last_name }}</h5>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <!-- Tenant Details -->
                                <div class="col-md-3">
                                    <p class="text-dark me-1 fs-3 mb-0 fw-semibold"><i class="ti ti-mail"></i> Email</p>
                                    <a href="mailto:{{ $tenant->email }}"
                                        class="text-dark me-1 fs-3 mb-0">{{ $tenant->email }}</a>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-dark me-1 fs-3 mb-0 fw-semibold"><i class="ti ti-phone"></i> Phone
                                        Number</p>
                                    <a href="tel:{{ $tenant->phone }}"
                                        class="text-dark me-1 fs-3 mb-0">{{ $tenant->phone }}</a>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-dark me-1 fs-3 mb-0 fw-semibold"><i class="ti ti-calendar"></i>
                                        Tenant Since</p>
                                    <p class="text-dark me-1 fs-3 mb-0">{{ $tenant->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-dark me-1 fs-3 mb-0 fw-semibold"><i class="ti ti-loader"></i> Status
                                    </p>
                                    <span
                                        class="badge bg-{{ Unit::where('tenant_id', $tenant->id)->exists() ? 'success' : 'danger' }} rounded-3 fw-semibold">
                                        {{ Unit::where('tenant_id', $tenant->id)->exists() ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Tenancy -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-4">Current Tenancy</h6>
                            @forelse ($activities as $item)
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-dark me-1 fs-3 mb-0 fw-semibold"><i class="ti ti-map-pin"></i>
                                            Address
                                        </p>
                                        <p class="text-dark me-1 fs-3 mb-0">
                                            {{ Unit::where('id', $item->unit_id)->first()->name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-dark me-1 fs-3 mb-0 fw-semibold"><i class="ti ti-coin"></i>
                                            Annual Rent</p>
                                        <p class="text-dark me-1 fs-3 mb-0">{{ number_format($item->amount, 2) }}</p>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-dark me-1 fs-3 mb-0 fw-semibold"><i class="ti ti-calendar"></i>
                                            Start</p>
                                        <p class="text-dark me-1 fs-3 mb-0">
                                            {{ date('M d, Y', strtotime($item->start_date)) }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-dark me-1 fs-3 mb-0 fw-semibold"><i class="ti ti-calendar"></i>
                                            End</p>
                                        <p class="text-dark me-1 fs-3 mb-0">
                                            {{ date('M d, Y', strtotime($item->end_date)) }}</p>
                                    </div>
                                </div>
                                @if ($activityNo > 1)
                                    <br>
                                    <a href="{{ route('tenancy.index', ['tenancy' => $tenant->id]) }}"
                                        class="text-dark me-1 fs-3 mb-0">View All</a>
                                @endif

                            @empty
                                <p class="text-muted">No current tenancy information available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Collection Section -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-4">Collection</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-dark me-1 fs-3 mb-0 fw-semibold"><i class="ti ti-report"></i> Current
                                        Invoice</p>
                                    {{ $currentInvoice->invoice_no }}
                                    <br>
                                    <a href="{{ route('invoices.index', ['tenant' => $tenant->id]) }}"
                                        class="text-dark me-1 fs-3 mb-0">View All Invoices</a>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-dark me-1 fs-3 mb-0 fw-semibold"><i class="ti ti-coin"></i> Total
                                        Rent Collected</p>
                                    <p class="text-dark me-1 fs-3 mb-0">
                                        N{{ number_format($totalPaidInvoice ? $totalPaidInvoice : 0, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tenancy and Message History -->
            <div class="row">
                <!-- Tenancy History -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-4">Tenancy History</h6>
                            <ul class="timeline-widget mb-0 position-relative mb-n5" id="tenancyHistory">
                                @php $showCount = 3; @endphp
                                @forelse ($activity as $index => $act)
                                    @php
                                        $no++;
                                        $address = Unit::where('id', $act->unit_id);
                                        $property = Property::where('id', $address->value('property_id'));
                                    @endphp

                                    <li class="timeline-item d-flex position-relative overflow-hidden{{ $index >= $showCount ? ' d-none' : '' }}">
                                        <div class="timeline-time text-dark flex-shrink-0 text-end">
                                            {{ date('M d, Y', strtotime($act->start_date)) }}
                                        </div>
                                        <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                            <span class="timeline-badge border-2 border border-{{ $index == 0 ? 'info' : ($index == $activityNo - 1 ? 'success' : 'info') }} flex-shrink-0 my-8"></span>
                                            <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                        </div>
                                        <div class="timeline-desc fs-3 text-dark mt-n1 fw-semibold">
                                            {{ $address->value('name') }}
                                            <a href="javascript:void(0)" class="text-primary d-block fw-normal">{{ $property->value('name') }}</a>
                                        </div>
                                    </li>
                                @empty
                                    <p class="text-muted">No tenancy history available.</p>
                                @endforelse
                            </ul>

                            <!-- Expand / Close Button -->
                            @if ($activityNo > $showCount)
                            <br>
                                <div class="text-center mt-3">
                                    <button id="toggleHistory" class="btn btn-link text-primary fs-3 fw-semibold" onclick="toggleTenancyHistory()">Expand</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- Message History (Optional Section) -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-4">Message History</h6>
                            <!-- Populate with message history data as available -->
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                        <tr>
                                            <th>#</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $repair = Repair::where('tenant_id', $tenant->id)->orderBy('created_at', 'desc')->limit(5)->get();
                                        @endphp
                                        @foreach ($repair as $index => $row)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $row->subject }}</td>
                                                <td>{{ ucfirst($row->status) }}</td>
                                                <td>{{ date('M d, Y', strtotime($row->created_at)) }}</td>
                                                <td>
                                                    <a href="{{ route('repair.show', $row->id) }}" class="btn btn-primary"> <i class="ti ti-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function toggleTenancyHistory() {
        const tenancyItems = document.querySelectorAll('#tenancyHistory .timeline-item');
        const toggleButton = document.getElementById('toggleHistory');

        tenancyItems.forEach((item, index) => {
            if (index >= {{ $showCount }}) {
                item.classList.toggle('d-none');
            }
        });

        toggleButton.textContent = toggleButton.textContent === 'Expand' ? 'Close' : 'Expand';
    }
</script>
