<div class="container-fluid">

    {{-- Back Action with arrow and text --}}
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('profile.index') }}" class="btn btn-primary"><i class="ti ti-arrow-left"></i> Back</a>
    </div>
    <div class="card">
        <div class="card-body">

            <div class="container" id="main">
                <!-- Property Filter -->
                <form method="GET" action="#">
                    <div class="form-group mb-4">
                        <label for="property">Select Property:</label>
                        <select name="property" id="property" class="form-control" onchange="this.form.submit()">
                            <option value="">Select Property</option>
                            @foreach ($properties as $property)
                                <option value="{{ $property->id }}"
                                    {{ $selectedProperty == $property->id ? 'selected' : '' }}>
                                    {{ $property->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <!-- Permissions Checkboxes -->
                <div class="row">
                    <!-- Property Section -->
                    <div class="form-group col-md-2">
                        <h5 class="card-title fw-semibold mb-2">Property</h5>
                        <input type="checkbox" class="permission-checkbox" data-entity="property" data-action="add"
                            {{ in_array('add', $permissions['property'] ?? []) ? 'checked' : '' }}>
                        <label>Add</label><br>
                        <input type="checkbox" class="permission-checkbox" data-entity="property" data-action="edit"
                            {{ in_array('edit', $permissions['property'] ?? []) ? 'checked' : '' }}>
                        <label>Edit</label><br>
                        <input type="checkbox" class="permission-checkbox" data-entity="property" data-action="delete"
                            {{ in_array('delete', $permissions['property'] ?? []) ? 'checked' : '' }}>
                        <label>Delete</label><br>
                    </div>

                    <!-- Unit Section -->
                    <div class="form-group col-md-2">
                        <h5 class="card-title fw-semibold mb-2">Unit</h5>
                        <input type="checkbox" class="permission-checkbox" data-entity="unit" data-action="add"
                            {{ in_array('add', $permissions['unit'] ?? []) ? 'checked' : '' }}>
                        <label>Add</label><br>
                        <input type="checkbox" class="permission-checkbox" data-entity="unit" data-action="edit"
                            {{ in_array('edit', $permissions['unit'] ?? []) ? 'checked' : '' }}>
                        <label>Edit</label><br>
                        <input type="checkbox" class="permission-checkbox" data-entity="unit" data-action="delete"
                            {{ in_array('delete', $permissions['unit'] ?? []) ? 'checked' : '' }}>
                        <label>Delete</label><br>
                    </div>

                    <!-- Tenant Section -->
                    <div class="form-group col-md-2">
                        <h5 class="card-title fw-semibold mb-2">Tenant</h5>
                        <input type="checkbox" class="permission-checkbox" data-entity="tenant" data-action="add"
                            {{ in_array('add', $permissions['tenant'] ?? []) ? 'checked' : '' }}>
                        <label>Add</label><br>
                        <input type="checkbox" class="permission-checkbox" data-entity="tenant" data-action="edit"
                            {{ in_array('edit', $permissions['tenant'] ?? []) ? 'checked' : '' }}>
                        <label>Edit</label><br>
                        <input type="checkbox" class="permission-checkbox" data-entity="tenant" data-action="vacate"
                            {{ in_array('vacate', $permissions['tenant'] ?? []) ? 'checked' : '' }}>
                        <label>Vacate</label><br>
                        <input type="checkbox" class="permission-checkbox" data-entity="tenant" data-action="delete"
                            {{ in_array('delete', $permissions['tenant'] ?? []) ? 'checked' : '' }}>
                        <label>Delete</label><br>
                    </div>

                    <!-- Payment/Invoice Section -->
                    <div class="form-group col-md-3">
                        <h5 class="card-title fw-semibold mb-2">Payment/Invoice</h5>
                        <input type="checkbox" class="permission-checkbox" data-entity="invoice" data-action="add"
                            {{ in_array('add', $permissions['invoice'] ?? []) ? 'checked' : '' }}>
                        <label>Add</label><br>
                        <input type="checkbox" class="permission-checkbox" data-entity="invoice" data-action="edit"
                            {{ in_array('edit', $permissions['invoice'] ?? []) ? 'checked' : '' }}>
                        <label>Edit</label><br>
                        <input type="checkbox" class="permission-checkbox" data-entity="invoice" data-action="delete"
                            {{ in_array('delete', $permissions['invoice'] ?? []) ? 'checked' : '' }}>
                        <label>Delete</label><br>
                    </div>

                    <!-- Repair and Maintenance Section -->
                    <div class="form-group col-md-3">
                        <h5 class="card-title fw-semibold mb-2">Repair and Maintenance</h5>
                        <input type="checkbox" class="permission-checkbox" data-entity="repair" data-action="edit"
                            {{ in_array('edit', $permissions['repair'] ?? []) ? 'checked' : '' }}>
                        <label>Update</label><br>
                    </div>
                </div>
            </div>

            <div id="wait" style="display: none">
                <center>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </center>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.permission-checkbox').change(function() {
            const entity = $(this).data('entity');
            const action = $(this).data('action');
            const checked = $(this).prop('checked') ? 1 : 0; // 1 for checked, 0 for unchecked
            // get the value of property by id
            const property_id = document.getElementById('property').value;
            // if property_id is empty, alert the user
            if (property_id == '' || property_id == null) {
                alert('Please select a property');
                return;
            }
            // get the value of user by id
            var main = document.getElementById('main');
            var wait = document.getElementById('wait');
            main.style.display = 'none';
            wait.style.display = 'block';

            // Send the change to the server using AJAX
            $.ajax({
                url: '{{ route('permission.update.ajax') }}', // AJAX route
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    entity: entity,
                    action: action,
                    checked: checked,
                    user_id: '{{ $user_id }}',
                    property_id: property_id
                },
                success: function(response) {
                    //alert(response.message); // Show success message
                    main.style.display = 'block';
                    wait.style.display = 'none';

                },
                error: function(xhr, status, error) {
                    alert('There was an error updating the permission.');
                    main.style.display = 'block';
                    wait.style.display = 'none';
                }
            });
        });
    });
</script>
