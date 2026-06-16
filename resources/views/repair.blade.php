@php
    use App\Models\Unit;
    use App\Models\Property;
    use App\Models\Tenant;
    use App\Models\Comment;
    $property = Property::where('id', $data->property_id)->value('name');
    $unit = Unit::where('id', $data->unit_id)->value('name');
    $tenant = Tenant::where('id', $data->tenant_id);
    $comment = Comment::where(['table_name' => 'repair', 'row_id' => $data->id])->get();
    $commentNo = Comment::where(['table_name' => 'repair', 'row_id' => $data->id])->count();
    $no = 0;

@endphp
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Repair and Maintenance Details</h5>

            <div class="row">

                <div class="col-md-4">
                    <h6 class="fw-bold">Property:</h6>
                    <p>{{ $property }}</p>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-bold">Unit:</h6>
                    <p>{{ $unit }}</p>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-bold">Tenant:</h6>
                    <p>{{ $tenant->value('first_name') }} {{ $tenant->value('last_name') }} <br>
                        <i>{{ $tenant->value('email') }}</i>
                    </p>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-bold">Subject:</h6>
                    <p>{{ $data->subject }}</p>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-bold">Status:</h6>
                    <p>{{ ucfirst($data->status) ?? 'Pending' }}</p>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-bold">Date Created:</h6>
                    <p>{{ $data->created_at->format('d-m-Y') }}</p>
                </div>

                <div class="col-md-12">
                    <h6 class="fw-bold">Description:</h6>
                    <p>{{ $data->description }}</p>
                </div>

                @if (count($comment) > 0)
                    <div class="col-md-12">
                        <hr>
                        <h6 class="fw-bold"><strong>Comment:</strong> </h6>

                        <ul class="timeline-widget mb-0 position-relative mb-n5">
                            @foreach ($comment as $item)
                                @php
                                    $no++;
                                @endphp
                                <li class="timeline-item d-flex position-relative overflow-hidden">
                                    <div class="timeline-time text-dark flex-shrink-0 text-end">
                                        {{ date('d M, Y', strtotime($item->created_at)) }}</div>
                                    <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                        <span
                                            class="timeline-badge border-2 border border-info flex-shrink-0 my-8"></span>
                                        @if ($no != $commentNo && $commentNo != 1)
                                            <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                        @endif
                                    </div>
                                    <div class="timeline-desc fs-3 mt-n1">
                                        {{ $item->comment }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <br><br>
                @endif


                <div class="mt-12">
                    <br>
                    <hr>
                    <h6 class="fw-bold">Attached Files: </h6>
                    <div class="row">
                        @forelse ($files as $file)
                            <div class="col-md-2">
                                <a href="http://localhost:8001{{ $file->file_name }}" class="text-primary"><img
                                        src="http://localhost:8001{{ $file->file_name }}" alt="File" width="100"
                                        height="120"></a>
                            </div>

                        @empty
                            <p>No files attached.</p>
                        @endforelse

                    </div>
                </div>

            </div>
            <a href="{{ route('repair.index') }}" class="btn btn-secondary mt-4">Back to Repairs</a>
            <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal"
                data-bs-target="#addPropertyModal">
                <i class="ti ti-edit"></i> Action
            </button>
        </div>
    </div>
</div>


<div class="modal fade" id="addPropertyModal" tabindex="-1" aria-labelledby="addPropertyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('repair.update', $data->id) }}" method="POST">
                @csrf
                <input type="hidden" name="tenant" value="{{ $tenant->value('first_name') }} {{ $tenant->value('last_name') }}">
                <input type="hidden" name="email" value="{{ $tenant->value('email') }}">
                <input type="hidden" name="ref" value="{{ $data->ref }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPropertyModalLabel">Request Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-control" id="status" required>
                            <option value="ongoing">Ongoing</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea name="comment" class="form-control" id="comment" cols="30" rows="10" placeholder="Optional"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
