@php
    use App\Models\Tenant;
    use App\Models\Property;
    use App\Models\Repair;
    use App\Models\Landlord;
    use App\Models\Permission;
    $pages = Permission::where(['user_id' => session('user_id'), 'page' => 'unit'])
        ->select('action')
        ->value('action');
    $pages = json_decode($pages) != null ? json_decode($pages) : [];
    $tenant_pages = Permission::where(['user_id' => session('user_id'), 'page' => 'tenant'])
        ->select('action')
        ->value('action');
    $tenant_pages = json_decode($tenant_pages) != null ? json_decode($tenant_pages) : [];
    $repair = Repair::where(['property_id' => $property->id])->get();
    $repair_count = $repair->where('status', 'pending')->count();
    $landlord_name = Landlord::where('user_id', $property->landlord_id)->first();

@endphp
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Quick Actions</h5>
            <p class="mb-0">What would you like to do next? </p>
            <br>
            <div class="row">
                <div class="col-lg-4">
                    <!-- New Tenancy Button -->
                    <button type="button" class="btn btn-outline-dark m-1 w-100" data-bs-toggle="modal"
                        data-bs-target="#assignTenantModal">
                        <i class="ti ti-ballpen fs-6"></i> New Tenancy
                    </button>
                </div>
                <div class="col-lg-4">
                    <!-- New Unit Button -->
                    <button type="button" class="btn btn-outline-dark m-1 w-100" data-bs-toggle="modal"
                        data-bs-target="#addUnitModal">
                        <i class="ti ti-building fs-6"></i> New Unit
                    </button>
                </div>
                <div class="col-lg-4">
                    <button type="button" class="btn btn-outline-dark m-1 w-100">
                        <i class="ti ti-settings fs-6"></i> Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Property Information with Tabs -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">{{ $property->name }}</h5>
        </div>
        <div style="padding-left: 2em; padding-right: 2em;">
            <ul class="nav nav-line nav-fill nav-color-default" id="propertyTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link btn btn-light btn-block active" id="overview-tab" data-bs-toggle="tab"
                        data-bs-target="#overview" type="button" role="tab" aria-controls="overview"
                        aria-selected="true">Overview</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link btn btn-light btn-block" id="units-tab" data-bs-toggle="tab"
                        data-bs-target="#units" type="button" role="tab" aria-controls="units"
                        aria-selected="true">Units</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link btn btn-light btn-block" id="reports-tab" data-bs-toggle="tab"
                        data-bs-target="#reports" type="button" role="tab" aria-controls="reports"
                        aria-selected="true">Reports</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link btn btn-light btn-block position-relative" id="requests-tab"
                        data-bs-toggle="tab" data-bs-target="#requests" type="button" role="tab"
                        aria-controls="requests" aria-selected="true">Requests<span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $repair_count }}</span></button>
                </li>
            </ul>

            <!-- Tab Contents -->
            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-start">
                                        <div class="col-8">
                                            <h5 class="card-title mb-9 fw-semibold"> Address </h5>
                                            <p class="text-dark me-1 fs-3 mb-0">{{ $property->address }}</p>
                                            <!-- Dynamic Address -->
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
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-start">
                                        <div class="col-8">
                                            <h5 class="card-title mb-9 fw-semibold"> Total Units </h5>
                                            <h4 class="fw-semibold mb-3">{{ $units->count() }}</h4>
                                            <!-- Dynamic Unit Count -->
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex justify-content-end">
                                                <div
                                                    class="text-white bg-dark rounded-circle p-6 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-building fs-6"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row alig n-items-start">
                                        <div class="col-8">
                                            <h5 class="card-title mb-9 fw-semibold"> Total Tenants </h5>
                                            <h4 class="fw-semibold mb-3">{{ $tenants->count() }}</h4>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex justify-content-end">
                                                <div
                                                    class="text-white bg-info rounded-circle p-6 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-users fs-6"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row alig n-items-start">
                                        <div class="col-8">
                                            <h5 class="card-title mb-9 fw-semibold"> Annual Income </h5>
                                            <h4 class="fw-semibold mb-3">₦{{ number_format($income, 2) }}</h4>
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
                        <!-- Additional Overview Data such as tenants, income can go here -->
                    </div>
                </div>

                <!-- Units Tab -->
                <div class="tab-pane" id="units" role="tabpanel" aria-labelledby="units-tab">
                    <br>
                    <div class="row">
                        <div class="col-md-2 offset-md-10">
                            @if (in_array('add', $pages) || session('acc_type') == 'landlord')
                                <button class="btn btn-primary w-100" data-bs-toggle="modal"
                                    data-bs-target="#addUnitModal"><i class="ti ti-building"></i> Add Unit</button>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">#</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Unit</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">No. of Rooms</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">No. of Baths</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Tenant</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Rent</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Status</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Action</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $unit)
                                    <tr>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">{{ $loop->iteration }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">{{ $unit->name }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $unit->no_of_rooms }}</p>
                                        </td>
                                        <td class="border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $unit->no_of_baths }}</p>
                                        </td>
                                        <td class="border-bottom-0">
                                            @if ($unit->tenant)
                                                <a href="{{ route('tenants.show', $unit->tenant->id) }}"
                                                    class="mb-0 fw-normal">
                                                    {{ $unit->tenant->first_name }} {{ $unit->tenant->last_name }}
                                                </a>
                                            @else
                                                @if (in_array('add', $pages) || session('acc_type') == 'landlord')
                                                    <button class="btn btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#assignTenantModal"
                                                        data-unit-id="{{ $unit->id }}"><i
                                                            class="ti ti-user-plus"></i> Add Tenant</button>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ number_format($unit->amount, 2) }}</p>
                                        </td>
                                        <td class="border-bottom-0">
                                            <span
                                                class="badge bg-{{ $unit->status == 'occupied' ? 'success' : 'danger' }} rounded-3 fw-semibold">
                                                {{ ucfirst($unit->status) }}
                                            </span>
                                        </td>
                                        <td class="border-bottom-0">
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="{{ route('units.show', $unit->id) }}"
                                                    class="btn btn-outline-info m-1">
                                                    <i class="ti ti-eye fs-6"></i>
                                                </a>

                                                @if (in_array('edit', $pages) || session('acc_type') == 'landlord')
                                                    <button type="button" class="btn btn-success m-1"
                                                        data-bs-toggle="modal" data-bs-target="#editUnitModal"
                                                        data-unit-id="{{ $unit->id }}"
                                                        data-unit-name="{{ $unit->name }}"
                                                        data-rooms="{{ $unit->no_of_rooms }}"
                                                        data-baths="{{ $unit->no_of_baths }}"
                                                        data-amount="{{ $unit->amount }}"><i
                                                            class="ti ti-edit fs-6"></i></button>
                                                @endif

                                                <!-- Vacate Tenant Button -->
                                                @if ($unit->tenant)
                                                    @if (in_array('vacate', $tenant_pages) || session('acc_type') == 'landlord')
                                                        <button type="button" class="btn btn-warning m-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#confirmVacateModal"
                                                            data-unit-id="{{ $unit->id }}"
                                                            data-unit-name="{{ $unit->name }}">
                                                            <i class="ti ti-user-minus fs-6"></i>
                                                        </button>
                                                    @endif
                                                @endif

                                                <!-- Only show delete button if the unit has no activities -->
                                                @if ($unit->activities->count() == 0)
                                                    @if (in_array('delete', $pages) || session('acc_type') == 'landlord')
                                                        <form action="{{ route('units.delete', $unit->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger m-1"><i
                                                                    class="ti ti-trash fs-6"></i></button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- Reports Tab -->
                <div class="tab-pane" id="reports" role="tabpanel" aria-labelledby="reports-tab">
                    <br>Reports
                </div>

                <!-- Requests Tab -->
                <div class="tab-pane" id="requests" role="tabpanel" aria-labelledby="requests-tab">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-4">Repair and Maintenance</h5>
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                        <tr>
                                            <th>#</th>
                                            <th>Tenant</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($repair as $index => $row)
                                            @php
                                                $tenant = Tenant::find($row->tenant_id);
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $tenant->first_name . ' ' . $tenant->last_name }} <br>
                                                    <i>{{ $tenant->email }}</i>
                                                </td>
                                                <td>{{ $row->subject }}</td>
                                                <td>{{ ucfirst($row->status) }}</td>
                                                <td>{{ date('M d, Y', strtotime($row->created_at)) }}</td>
                                                <td>
                                                    <a href="{{ route('repair.show', $row->id) }}"
                                                        class="btn btn-primary"> <i class="ti ti-eye"></i></a>
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

<!-- Add Tenant Modal -->
<div class="modal fade" id="assignTenantModal" tabindex="-1" aria-labelledby="assignTenantModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('units.assignTenant', $unit->id ?? '') }}" method="POST" id="assignTenantForm">
            @csrf
            <input type="hidden" name="ppt_name" value="{{ $property->name }}">
            <input type="hidden" name="ppt_address" value="{{ $property->address }}">
            <input type="hidden" name="landlord_name"
                value="{{ $landlord_name->first_name . ' ' . $landlord_name->last_name }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignTenantModalLabel">Assign Tenant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tenant_email">Tenant Email</label>
                        <input type="email" class="form-control" id="tenant_email" name="tenant_email" required
                            autocomplete="off">
                        <ul id="tenant_email_suggestions" class="list-group" style="display: none;"></ul>
                        <!-- Suggestions for tenant email -->
                    </div>
                    <div class="form-group">
                        <label for="tenant_first_name">First Name</label>
                        <input type="text" class="form-control" id="tenant_first_name" name="tenant_first_name"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="tenant_last_name">Last Name</label>
                        <input type="text" class="form-control" id="tenant_last_name" name="tenant_last_name"
                            required>
                    </div>

                    <!-- Start Date -->
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submitt" onclick="submittt()" class="btn btn-primary">Assign
                        Tenant</button>

                    <button id="loadingg" style="display: none" class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        <span role="status">Assign Tenant</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('units.add', $property->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUnitModalLabel">Add Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="unit_name">Unit Name</label>
                        <input type="text" class="form-control" id="unit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="no_of_rooms">Number of Rooms</label>
                        <input type="number" class="form-control" id="no_of_rooms" name="no_of_rooms" required>
                    </div>
                    <div class="form-group">
                        <label for="no_of_baths">Number of Baths</label>
                        <input type="number" class="form-control" id="no_of_baths" name="no_of_baths" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Rent Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Unit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Unit Modal -->
<div class="modal fade" id="editUnitModal" tabindex="-1" aria-labelledby="editUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('units.update', '') }}" method="POST" id="editUnitForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUnitModalLabel">Edit Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_unit_name">Unit Name</label>
                        <input type="text" class="form-control" id="edit_unit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_no_of_rooms">Number of Rooms</label>
                        <input type="number" class="form-control" id="edit_no_of_rooms" name="no_of_rooms"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="edit_no_of_baths">Number of Baths</label>
                        <input type="number" class="form-control" id="edit_no_of_baths" name="no_of_baths"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="edit_amount">Rent Amount</label>
                        <input type="number" class="form-control" id="edit_amount" name="amount" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Unit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Confirmation Modal for Vacating Tenant -->
<div class="modal fade" id="confirmVacateModal" tabindex="-1" aria-labelledby="confirmVacateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmVacateModalLabel">Confirm Vacate Tenant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to vacate the tenant from <strong id="unitName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <form id="vacateTenantForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Vacate Tenant</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Script to Handle Dynamic Data -->
<script>
    function submittt() {
        var name = document.getElementById('tenant_first_name').value;
        if (name != '') {
            document.getElementById('submitt').style.display = 'none';
            document.getElementById('loadingg').style.display = 'block';
        }

    }
    // Handle dynamic unit assignment form for tenant
    var assignTenantModal = document.getElementById('assignTenantModal');

    assignTenantModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var unitId = button.getAttribute('data-unit-id');
        var form = document.getElementById('assignTenantForm');
        form.action = '/units/' + unitId + '/assignTenant';
    });

    // Handle dynamic data for editing unit modal
    var editUnitModal = document.getElementById('editUnitModal');
    editUnitModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var unitId = button.getAttribute('data-unit-id');
        var name = button.getAttribute('data-unit-name');
        var rooms = button.getAttribute('data-rooms');
        var baths = button.getAttribute('data-baths');
        var amount = button.getAttribute('data-amount');

        var form = document.getElementById('editUnitForm');
        form.action = '/units/' + unitId + '/update';
        form.querySelector('#edit_unit_name').value = name;
        form.querySelector('#edit_no_of_rooms').value = rooms;
        form.querySelector('#edit_no_of_baths').value = baths;
        form.querySelector('#edit_amount').value = amount;
    });

    document.getElementById('tenant_email').addEventListener('input', function() {
        let query = this.value;

        if (query.length > 2) {
            fetch('/search-tenants?email=' + query)
                .then(response => response.json())
                .then(data => {
                    let suggestionsList = document.getElementById('tenant_email_suggestions');
                    suggestionsList.innerHTML = '';
                    if (data.length > 0) {
                        suggestionsList.style.display = 'block';
                        data.forEach(tenant => {
                            let suggestionItem = document.createElement('li');
                            suggestionItem.classList.add('list-group-item');
                            suggestionItem.textContent = tenant.email;
                            suggestionItem.addEventListener('click', function() {
                                // Populate the form fields with tenant data
                                document.getElementById('tenant_email').value = tenant
                                    .email;
                                document.getElementById('tenant_first_name').value = tenant
                                    .first_name;
                                document.getElementById('tenant_last_name').value = tenant
                                    .last_name;
                                suggestionsList.style.display = 'none';
                            });
                            suggestionsList.appendChild(suggestionItem);
                        });
                    } else {
                        // No tenant found, use text in the form fields
                        suggestionsList.style.display = 'none';
                    }
                });
        } else {
            document.getElementById('tenant_email_suggestions').style.display = 'none';
        }
    });

    // Handle opening the confirmation modal and setting the unit ID and name
    var confirmVacateModal = document.getElementById('confirmVacateModal');
    confirmVacateModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var unitId = button.getAttribute('data-unit-id');
        var unitName = button.getAttribute('data-unit-name');

        var form = document.getElementById('vacateTenantForm');
        var unitNameSpan = document.getElementById('unitName');

        form.action = '/units/' + unitId + '/vacateTenant'; // Set the form action dynamically
        unitNameSpan.textContent = unitName; // Update the unit name in the confirmation message
    });
</script>
