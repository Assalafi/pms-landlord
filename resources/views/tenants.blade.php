@php
    use App\Models\Unit;
    use App\Models\Property;
    use App\Models\Permission;
    $pages = Permission::where(['user_id' => session('user_id'), 'page' => 'tenant'])
        ->select('action')
        ->value('action');
    $pages = json_decode($pages) != null ? json_decode($pages) : [];
@endphp
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Tenants</h5>
            <div class="row mb-3">
                <div class="col offset-md-10">
                    @if (in_array('add', $pages) || session('acc_type') == 'landlord')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addTenantModal">
                            <i class="ti ti-user-plus"></i> Add Tenant
                        </button>
                    @endif
                </div>
            </div>
            <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th>#</th>
                            <th>Tenant</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenants as $index => $tenant)
                            @php
                                $address = Unit::where('tenant_id', $tenant->id)->value('name');
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <h6 class="fw-semibold mb-1">
                                        <a href="{{ route('tenants.show', $tenant->id) }}">
                                            {{ $tenant->first_name }} {{ $tenant->last_name }}
                                        </a>

                                    </h6>
                                    <a href="mailto:{{ $tenant->email }}" class="fw-normal">{{ $tenant->email }}</a>
                                </td>
                                <td>{{ $address }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if (in_array('edit', $pages) || session('acc_type') == 'landlord')
                                            <a href="{{ route('tenants.edit', $tenant->id) }}"
                                                class="btn btn-outline-warning m-1">
                                                <i class="ti ti-pencil fs-6"></i>
                                            </a>
                                        @endif
                                        @if (in_array('delete', $pages) || session('acc_type') == 'landlord')
                                            <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger m-1">
                                                    <i class="ti ti-trash fs-6"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Tenant Modal -->
<div class="modal fade" id="addTenantModalss" tabindex="-1" aria-labelledby="addTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('tenants.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTenantModalLabel">Add New Tenant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="number" class="form-control" id="phone" name="phone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Tenant</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="addTenantModal" tabindex="-1" aria-labelledby="assignTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('tenants.addTenant', '1' ?? '') }}" method="POST" id="assignTenantForm">
            @csrf
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


                    <div class="form-group">
                        <label for="property">Property</label>
                        <select name="property_id" id="property" class="form-control" required>
                            <option value="">Select Property</option>
                            @foreach (Property::where('name', '!=', '')->get() as $property)
                                <option value="{{ $property->id }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <select name="unit_id" id="unit" class="form-control" required>
                            <option value="">Select Unit</option>
                        </select>
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

<script>
    function submittt() {
        var name = document.getElementById('tenant_first_name').value;
        if (name != '') {
            document.getElementById('submitt').style.display = 'none';
            document.getElementById('loadingg').style.display = 'block';
        }

    }

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

    document.getElementById('property').addEventListener('change', function() {
        const propertyId = this.value;
        const unitDropdown = document.getElementById('unit');
        unitDropdown.innerHTML = '<option value="">Loading...</option>';

        if (propertyId) {
            fetch(`/get-units?property_id=${propertyId}`)
                .then(response => response.json())
                .then(data => {
                    unitDropdown.innerHTML = '<option value="">Select Unit</option>';
                    if (data.length > 0) {
                        data.forEach(unit => {
                            const option = document.createElement('option');
                            option.value = unit.id;
                            option.textContent = unit.name;
                            unitDropdown.appendChild(option);
                        });
                    } else {
                        unitDropdown.innerHTML = '<option value="">No units available</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching units:', error);
                    unitDropdown.innerHTML = '<option value="">Error loading units</option>';
                });
        } else {
            unitDropdown.innerHTML = '<option value="">Select Unit</option>';
        }
    });
</script>
