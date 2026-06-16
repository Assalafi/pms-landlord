@php
    use App\Models\Unit;
    use App\Models\Tenant;
    if (isset($_GET['tenancy'])) {
        $data = $data->where('unit_id', $_GET['tenancy']);
    }else{
        $data = $data;
    }

@endphp
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Tenancy</h5>
            <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th>#</th>
                            <th>Tenant</th>
                            <th>Address</th>
                            <th>Amount</th>
                            <th>Start</th>
                            <th>End</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $row)
                        @php
                            $address = Unit::where('id', $row->unit_id);
                            $info = Tenant::where('id', $row->tenant_id);
                        @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $info->value('first_name') }} {{ $info->value('last_name') }} <br>
                                    <i>{{ $info->value('email') }}</i>
                                </td>
                                <td>{{ $address->value('name') }}</td>
                                <td>N{{ number_format($row -> amount,2) }}</td>
                                <td>{{ date('M d, Y', strtotime($row->start_date)) }}</td>
                                <td>{{ date('M d, Y', strtotime($row->end_date)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
