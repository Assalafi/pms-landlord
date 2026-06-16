@php
    use App\Models\Unit;
    use App\Models\Property;

@endphp
<div class="container-fluid">
    <div class="card">
        <div class="card-body">

            <div style="padding-left: 2em; padding-right: 2em;">
                <ul class="nav nav-line nav-fill nav-color-default" id="settingsTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link btn btn-light btn-block active" id="account-tab" data-bs-toggle="tab"
                            data-bs-target="#account" type="button" role="tab" aria-controls="account"
                            aria-selected="true">Account</button>
                    </li>
                    @if (session('acc_type') == 'landlord')
                    <li class="nav-item">
                        <button class="nav-link btn btn-light btn-block" id="users-tab" data-bs-toggle="tab"
                            data-bs-target="#users" type="button" role="tab" aria-controls="users"
                            aria-selected="true">Users and Permissions</button>
                    </li>
                    @endif
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <br>
                        <form action="/profile-update" enctype="multipart/form-data" class="row g-3" method="post">
                            @csrf
                            <h5 class="card-title fw-semibold mb-4">My Profile</h5>
                            <br>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <input type="hidden" name="id" value="{{ $profile->id }}">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control"
                                        value="{{ $profile->first_name }}" required>
                                    @error('first_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control"
                                        value="{{ $profile->last_name }}" required>
                                    @error('last_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ $profile->email }}" required readonly>
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

                            <hr>
                            <div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="users" role="tabpanel" aria-labelledby="users-tab">
                        <br>
                        <div class="row">
                            <div class="col-md-2 offset-md-10">
                                <button class="btn btn-primary w-100" data-bs-toggle="modal"
                                    data-bs-target="#addUnitModal"><i class="ti ti-user-plus"></i> Add
                                    User</button>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table class="table text-nowrap mb-0 align-middle">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">#</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">User</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Role</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Action</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">{{ 1 }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">{{ $profile->first_name }} {{ $profile->last_name }}</h6>
                                            <a href="#" class="fw-normal">{{ $profile->email }}</a>
                                        </td>
                                        <td class="border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ 'Landlord' }}</p>
                                        </td>
                                        <td class="border-bottom-0">

                                        </td>
                                    </tr>
                                    @foreach ($support as $sup)
                                    <tr>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">{{ $loop->iteration + 1 }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">{{ $sup -> first_name }} {{ $sup -> last_name }}</h6>
                                            <a href="#" class="fw-normal">{{ $sup -> email }}</a>
                                        </td>
                                        <td class="border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ 'Support' }}</p>
                                        </td>
                                        <td class="border-bottom-0">
                                            <a href="{{ route('permission.update', $sup -> user_id) }}" type="button" class="btn btn-success"><i
                                                    class="ti ti-edit"></i></a>
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

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUnitModalLabel">Create User and Permissions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="card-title fw-semibold mb-4">Users</h5>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <hr>
                    <h5 class="card-title fw-semibold mb-4">Permissions</h5>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <div id="example" class="form-control"></div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col-md-2">
                            <h5 class="card-title fw-semibold mb-2">Property</h5>
                            <input type="checkbox" id="add1" name="property[]" value="add">
                            <label for="add1">Add</label><br>
                            <input type="checkbox" id="edit1" name="property[]" value="edit">
                            <label for="edit1">Edit</label><br>
                            <input type="checkbox" id="delete1" name="property[]" value="delete">
                            <label for="delete1">Delete</label><br>
                        </div>
                        <div class="form-group col-md-2">
                            <h5 class="card-title fw-semibold mb-2">Unit</h5>
                            <input type="checkbox" id="add2" name="unit[]" value="add">
                            <label for="add2">Add</label><br>
                            <input type="checkbox" id="edit2" name="unit[]" value="edit">
                            <label for="edit2">Edit</label><br>
                            <input type="checkbox" id="delete2" name="unit[]" value="delete">
                            <label for="delete2">Delete</label><br>
                        </div>
                        <div class="form-group col-md-2">
                            <h5 class="card-title fw-semibold mb-2">Tenant</h5>
                            <input type="checkbox" id="add3" name="tenant[]" value="add">
                            <label for="add3">Add</label><br>
                            <input type="checkbox" id="edit3" name="tenant[]" value="edit">
                            <label for="edit3">Edit</label><br>
                            <input type="checkbox" id="vacate3" name="tenant[]" value="vacate">
                            <label for="vacate3">Vacate</label><br>
                            <input type="checkbox" id="delete3" name="tenant[]" value="delete">
                            <label for="delete3">Delete</label><br>
                        </div>
                        <div class="form-group col-md-3">
                            <h5 class="card-title fw-semibold mb-2">Payment/Invoice</h5>
                            <input type="checkbox" id="add4" name="invoice[]" value="add">
                            <label for="add4">Add</label><br>
                            <input type="checkbox" id="edit4" name="invoice[]" value="edit">
                            <label for="edit4">Edit</label><br>
                            <input type="checkbox" id="delete4" name="invoice[]" value="delete">
                            <label for="delete4">Delete</label><br>
                        </div>
                        <div class="form-group col-md-3">
                            <h5 class="card-title fw-semibold mb-2">Repair and Maintenance</h5>
                            <input type="checkbox" id="edit5" name="repair[]" value="edit">
                            <label for="edit5">Update</label><br>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>
