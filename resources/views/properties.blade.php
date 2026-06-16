@php
    use App\Models\Permission;
    use App\Models\Unit;
    use App\Models\Invoice;
    use App\Models\Receipt;
    $rent = Invoice::where(['landlord_id' => session('user_id'), 'status' => 'pending'])->sum('amount');
    $dueIds = Invoice::where(['landlord_id' => session('user_id'), 'status' => 'pending'])->pluck('id');
    $payment = Receipt::where(['landlord_id' => session('user_id'), 'status' => 'paid'])->whereIn('invoice_id', $dueIds)->sum('amount');
    $rent = $rent - $payment;
    $total_units = Unit::where('landlord_id', session('user_id'))->count();
    $free_units = Unit::where('landlord_id', session('user_id'))->where('status', 'vacant')->count();
    $occupied_units = $total_units - $free_units;
    $pages = Permission::where(['user_id' => session('user_id'), 'page' => 'property'])
        ->select('action')
        ->value('action');
    $pages = json_decode($pages) != null ? json_decode($pages) : [];

@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3">
            <!-- Total Units -->
            <div class="card">
                <div class="card-body">
                    <div class="row alig n-items-start">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold"> Total Units </h5>
                            <h5 class="fw-semibold mb-3">{{ $total_units }}</h5>
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
                                    <i class="ti ti-building fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <!-- Occupied Units -->
            <div class="card">
                <div class="card-body">
                    <div class="row alig n-items-start">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold"> Occupied Units </h5>
                            <h5 class="fw-semibold mb-3">{{ $occupied_units }}</h5>
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
                                    class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-building fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <!-- Rents Due -->
            <div class="card">
                <div class="card-body">
                    <div class="row alig n-items-start">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold"> Rents Due </h5>
                            <h5 class="fw-semibold mb-3">N{{ number_format($rent, 2) }}</h5>
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
                                    <i class="ti ti-currency-dollar fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <!-- Vacant units -->
            <div class="card">
                <div class="card-body">
                    <div class="row alig n-items-start">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold"> Vacant Units </h5>
                            <h5 class="fw-semibold mb-3">{{ $free_units }}</h5>
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
                                    <i class="ti ti-building fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Properties</h5>
            <div class="row">
                <div class="col offset-md-10 w-100">
                    <!-- Add Property Button Trigger -->
                    @if (in_array('add', $pages) || session('acc_type') == 'landlord')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addPropertyModal">
                            <i class="ti ti-building"></i> Add Property
                        </button>
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
                                <h6 class="fw-semibold mb-0">Property</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Address</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Actions</h6>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($properties as $property)
                            <tr>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $loop->iteration }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-1">{{ $property->name }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ $property->address }}</p>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('properties.show', $property->id) }}"
                                            class="btn btn-outline-info m-1">
                                            <i class="ti ti-eye fs-6"></i>
                                        </a>
                                        <!-- Edit Button Trigger -->
                                        @if (in_array('edit', $pages))
                                            <button type="button" class="btn btn-outline-warning m-1"
                                                data-bs-toggle="modal" data-bs-target="#editPropertyModal"
                                                data-id="{{ $property->id }}" data-name="{{ $property->name }}"
                                                data-address="{{ $property->address }}">
                                                <i class="ti ti-pencil fs-6"></i>
                                            </button>
                                        @endif
                                        <!-- Delete Button Trigger -->
                                        @if (in_array('delete', $pages))
                                            <button type="button" class="btn btn-outline-danger m-1"
                                                data-bs-toggle="modal" data-bs-target="#deletePropertyModal"
                                                data-id="{{ $property->id }}" data-name="{{ $property->name }}">
                                                <i class="ti ti-trash fs-6"></i>
                                            </button>
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

<!-- Add Property Modal -->
<div class="modal fade" id="addPropertyModal" tabindex="-1" aria-labelledby="addPropertyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('properties.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addPropertyModalLabel">Add New Property</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="propertyName" class="form-label">Property Name</label>
                        <input type="text" class="form-control" name="name" id="propertyName" required>
                    </div>
                    <div class="mb-3">
                        <label for="propertyAddress" class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" id="propertyAddress" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Property</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Property Modal -->
<div class="modal fade" id="editPropertyModal" tabindex="-1" aria-labelledby="editPropertyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="POST" id="editPropertyForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editPropertyModalLabel">Edit Property</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editPropertyName" class="form-label">Property Name</label>
                        <input type="text" class="form-control" name="name" id="editPropertyName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPropertyAddress" class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" id="editPropertyAddress" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Property Modal -->
<div class="modal fade" id="deletePropertyModal" tabindex="-1" aria-labelledby="deletePropertyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="POST" id="deletePropertyForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePropertyModalLabel">Delete Property</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deletePropertyName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Edit Property Modal: Fill in the data when the modal is triggered
    var editPropertyModal = document.getElementById('editPropertyModal');
    editPropertyModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');
        var address = button.getAttribute('data-address');
        var form = document.getElementById('editPropertyForm');
        form.action = '/properties/' + id;
        form.querySelector('#editPropertyName').value = name;
        form.querySelector('#editPropertyAddress').value = address;
    });

    // Delete Property Modal: Set the property name to be deleted
    var deletePropertyModal = document.getElementById('deletePropertyModal');
    deletePropertyModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');
        var form = document.getElementById('deletePropertyForm');
        form.action = '/properties/' + id;
        document.getElementById('deletePropertyName').textContent = name;
    });
</script>
