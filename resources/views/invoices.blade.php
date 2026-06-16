<!-- Blade Template (index.blade.php) -->
@php
    if (isset($_GET['tenant'])) {
        $invoices = $invoices->where('tenant_id', $_GET['tenant']);
    } else {
        $invoices = $invoices;
    }

@endphp
<style>
    .suggestions-box {
        position: absolute;
        background: white;
        border: 1px solid #ddd;
        width: 100%;
        max-height: 150px;
        overflow-y: auto;
        z-index: 1000;
    }

    .suggestion {
        padding: 8px;
        cursor: pointer;
    }

    .suggestion:hover {
        background-color: #f0f0f0;
    }
</style>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Invoices</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#payModal">
                Pay
            </button>
            <br>
            <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">#</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Invoice No.</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Tenant</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Address</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Due Date</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Amount</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Status</h6>
                            </th>
                            {{-- <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Action</h6>
                                </th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $index => $invoice)
                            <tr>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal">
                                        {{ str_pad($invoice->invoice_no, 5, '0', STR_PAD_LEFT) }}</p>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-1">
                                        <a href="{{ route('tenants.show', $invoice->tenant->id) }}">
                                            {{ $invoice->tenant->first_name }} {{ $invoice->tenant->last_name }}
                                        </a>
                                    </h6>
                                    <a href="mailto:{{ $invoice->tenant->email }}"
                                        class="fw-normal">{{ $invoice->tenant->email }}</a>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ $invoice->unit->name }},
                                        {{ $invoice->unit->property->name }},
                                        {{ $invoice->unit->property->address }}</p>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal">
                                        {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') }}</p>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ number_format($invoice->amount, 2) }}</p>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <span
                                            class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'pending' ? 'warning' : 'danger') }} rounded-3 fw-semibold">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </div>
                                </td>
                                {{-- <td class="border-bottom-0">
                                        <a href="{{ route('invoices.show', $invoice->id) }}"
                                            class="btn btn-outline-info">View</a>
                                    </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-4">
                    {{ $invoices->appends(['receipts_page' => $receipts->currentPage()])->links('pagination::bootstrap-5') }}
                    {{-- This will paginate only the invoices --}}
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Payment History</h5>
            <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">#</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Invoice No.</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Tenant</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Address</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Date</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Amount</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Status</h6>
                            </th>
                            {{-- <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Action</h6>
                                </th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($receipts as $index => $invoice)
                            <tr>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal">
                                        {{ str_pad($invoice->receipt_no, 5, '0', STR_PAD_LEFT) }}</p>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-1">
                                        <a href="{{ route('tenants.show', $invoice->invoice->tenant->id) }}">
                                            {{ $invoice->invoice->tenant->first_name }}
                                            {{ $invoice->invoice->tenant->last_name }}
                                        </a>
                                    </h6>
                                    <a href="mailto:{{ $invoice->invoice->tenant->email }}"
                                        class="fw-normal">{{ $invoice->invoice->tenant->email }}</a>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ $invoice->invoice->unit->name }},
                                        {{ $invoice->invoice->unit->property->name }},
                                        {{ $invoice->invoice->unit->property->address }}</p>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal">
                                        {{ \Carbon\Carbon::parse($invoice->created_at)->format('d M, Y') }}</p>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ number_format($invoice->amount, 2) }}</p>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <span
                                            class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'pending' ? 'warning' : 'danger') }} rounded-3 fw-semibold">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </div>
                                </td>
                                {{-- <td class="border-bottom-0">
                                        <a href="{{ route('invoices.show', $invoice->id) }}"
                                            class="btn btn-outline-info">View</a>
                                    </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination for Receipts --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $receipts->appends(['invoices_page' => $invoices->currentPage()])->links('pagination::bootstrap-5') }}
                    {{-- This will paginate only the receipts --}}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Pay Modal -->
<div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="payForm" method="POST" action="{{ route('invoices.pay') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payModalLabel">Pay Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tenantSearchInput">Tenant</label>
                        <input type="text" id="tenantSearchInput" class="form-control"
                            placeholder="Start typing tenant name or email...">
                        <input type="hidden" id="tenantId" name="tenant_id">
                        <div id="suggestionsList" class="suggestions-box"></div>
                    </div>
                    <div class="mb-3">
                        <label for="activity" class="form-label">Invoice</label>
                        <select name="activity_id" id="activity" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="text" name="amount" id="amount" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="submit" class="btn btn-primary">Save Payment</button> --}}


                    <button type="submit" id="submitt" onclick="submittt()" class="btn btn-primary">Save
                        Payment</button>

                    <button id="loadingg" style="display: none" class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        <span role="status">Save Payment</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function submittt() {
        var name = document.getElementById('amount').value;
        //alert(name);
        if (name > 0) {
            document.getElementById('submitt').style.display = 'none';
            document.getElementById('loadingg').style.display = 'block';
        }

    }
    document.addEventListener('DOMContentLoaded', function() {

        // Trigger the initial fetch when the modal is shown
        const payModal = document.getElementById('payModal');
        payModal.addEventListener('show.bs.modal', function() {
            tenantSelect.dispatchEvent(new Event('change'));
        });


    });

    const tenantSelect = document.getElementById('tenant');
    const activitySelect = document.getElementById('activity');

    function fetchActivitiesForTenant(tenantId) {
        if (tenantId) {
            document.getElementById('tenantId').value = tenantId;

            // Fetch active activities for the selected tenant
            fetch(`/get-active-activities/${tenantId}`)
                .then(response => response.json())
                .then(data => {
                    const activitySelect = document.getElementById('activity');
                    activitySelect.innerHTML = ''; // Clear existing options

                    if (data.activities && Array.isArray(data.activities)) {
                        data.activities.forEach(activity => {
                            const option = document.createElement('option');

                            // Set the value as the activity ID
                            option.value = activity.id;

                            // Check if invoice exists and set the amount and remaining balance as data attributes
                            const invoice = activity.invoice || {};
                            const amount = invoice.amount || 0;
                            const remainingBalance = invoice.remaining_balance || 0;
                            const invoiceId = invoice.invoice_no || 'No Invoice';

                            option.setAttribute('data-amount', remainingBalance);
                            //option.setAttribute('data-remaining-balance', remainingBalance);

                            // Format the text as "invoice_id: activity_name"
                            const unitName = activity.unit ? activity.unit.name : 'Unknown Unit';
                            const pptName = activity.unit.property ? activity.unit.property.name :
                                'Unknown Property';

                            // Add remaining balance to the option text
                            option.text =
                                `${invoiceId}: ${unitName} ${pptName}`;

                            // Append the option to the select dropdown
                            activitySelect.appendChild(option);
                        });
                    } else {
                        alert("No activities found for the selected tenant.");
                    }
                })
                .catch(error => {
                    console.error('Error fetching activities:', error);
                    alert("There was an issue fetching activities. Please try again.");
                });


        }
    }

    // Event listener for activity selection
    document.addEventListener('DOMContentLoaded', function() {
        const activitySelect = document.getElementById('activity');
        const amountInput = document.getElementById('amount');

        activitySelect.addEventListener('change', function() {
            const selectedOption = activitySelect.options[activitySelect.selectedIndex];

            // Retrieve the amount from the data attribute and set it in the amount input field
            const amount = selectedOption.getAttribute('data-amount');
            amountInput.value = amount || 0; // Default to 0 if no amount is set
        });
    });



    document.addEventListener('DOMContentLoaded', function() {
        const tenantInput = document.getElementById(
            'tenantSearchInput'); // Make sure your input has this ID
        const suggestionsList = document.getElementById(
            'suggestionsList'); // This should be a container for suggestions
        //alert('Hi');
        tenantInput.addEventListener('input', function() {
            const query = tenantInput.value;

            //alert('Hiii');
            // If the query is empty, hide suggestions
            if (query === '') {
                suggestionsList.innerHTML = '';
                suggestionsList.style.display = 'none';
                return;
            }

            // Fetch matching tenants
            fetch(`/search-tenantss?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    // Clear previous suggestions
                    suggestionsList.innerHTML = '';

                    // Populate new suggestions
                    data.forEach(tenant => {
                        const suggestionItem = document.createElement('div');
                        suggestionItem.classList.add('suggestion-item');
                        suggestionItem.innerHTML = `
                        <strong>${tenant.first_name} ${tenant.last_name}</strong><br>
                        <small>${tenant.email}</small>`;

                        // Event listener for clicking on a suggestion
                        suggestionItem.addEventListener('click', function() {
                            var iddd = tenant.id;
                            //alert('Hii');
                            fetchActivitiesForTenant(iddd);
                            tenantInput.value =
                                `${tenant.first_name} ${tenant.last_name}`;
                            tenantInput.setAttribute('data-tenant-id',
                                tenant.id); // Store tenant ID if needed
                            suggestionsList.innerHTML = '';
                            suggestionsList.style.display = 'none';
                        });

                        suggestionsList.appendChild(suggestionItem);
                    });

                    // Show suggestions list
                    suggestionsList.style.display = 'block';
                })
                .catch(error => console.error('Error fetching tenants:', error));
        });

        // Hide suggestions if clicked outside
        document.addEventListener('click', function(event) {
            if (!suggestionsList.contains(event.target) && event.target !== tenantInput) {
                suggestionsList.style.display = 'none';
            }
        });
    });
</script>
