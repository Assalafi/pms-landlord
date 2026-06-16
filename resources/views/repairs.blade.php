@php
    use App\Models\Unit;
    use App\Models\Property;
    use App\Models\Tenant;
    if (isset($_GET['tenancy'])) {
        $data = $data->where('unit_id', $_GET['tenancy']);
    } else {
        $data = $data;
    }
    $ppt_id = Unit::where('tenant_id', session('tenant_id'))->pluck('property_id');
    $property = Property::whereIn('id', $ppt_id)->get();

@endphp
<div class="container-fluid">
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
                        @foreach ($data as $index => $row)
                        @php
                            $tenant = Tenant::find($row->tenant_id);
                        @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $tenant->first_name . ' ' . $tenant->last_name }} <br> <i>{{ $tenant -> email }}</i></td>
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
