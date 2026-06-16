<br><br><br>
<div class="container">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Edit Tenant</h5>
            <form method="POST" action="{{ route('tenants.update', $tenant->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $tenant->first_name }}" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $tenant->last_name }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $tenant->email }}" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $tenant->phone }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Tenant</button>
            </form>
        </div>
    </div>
</div>
