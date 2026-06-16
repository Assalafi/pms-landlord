<script src="{{ url('assets/libs/jquery/dist/jquery.min.js') }}"></script>

<script src="http://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>
<script src="{{ url('assets/form-wizard/jquery-multi-step-form.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('assets/input-tags/magicsuggest.js') }}"></script>
<script src="{{ url('assets/js/sidebarmenu.js') }}"></script>
<script src="{{ url('assets/js/app.min.js') }}"></script>
<script src="{{ url('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
<script src="{{ url('assets/libs/simplebar/dist/simplebar.js') }}"></script>
<script src="{{ url('assets/js/dashboard.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    use App\Models\Property;
    $properties = Property::where('landlord_id', session('user_id'))->get();
@endphp

<script>
    function getNumber() {
        var accountNumber = document.getElementById('account_number').value;
        var bankCode = document.getElementById('bank_code').value;
        var accountNumberField = document.getElementById('account_number');
        var validfeedback = document.getElementById('validfeedback');
        var finish = document.getElementById('finish');
        finish.style.display = 'none';

        // Clear any previous error state

        if (accountNumber.length === 10 && bankCode) {
            document.getElementById('account_name').value = 'Please Wait...';

            $.ajax({
                url: '{{ url('/verify-bank') }}', // Ensure this points to your verification endpoint
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Include CSRF token
                    account_number: accountNumber,
                    bank_code: bankCode,
                },
                success: function(response) {
                    if (response.status) {
                        // Populate account name field

                        accountNumberField.classList.remove('is-invalid');
                        validfeedback.style.display = 'none';
                        finish.style.display = 'inline';
                        document.getElementById('account_name').value = response.data.account_name;
                    } else {
                        accountNumberField.classList.add('is-invalid'); // Add invalid class on error
                        document.getElementById('account_name').value = ''; // Clear if not successful
                        //alert(response.message || 'Failed to retrieve account name.');
                        validfeedback.style.display = 'block';
                        validfeedback.innerHTML = response.message || 'Failed to retrieve account name.';
                    }
                },
                error: function(xhr) {
                    accountNumberField.classList.add('is-invalid'); // Add invalid class if AJAX fails
                    document.getElementById('account_name').value = '';
                    //alert('An error occurred while verifying the account.');
                    validfeedback.style.display = 'block';
                        validfeedback.innerHTML = 'An error occurred while verifying the account.';
                }
            });
        } else {
            accountNumberField.classList.add('is-invalid'); // Add invalid class if input isn't valid
            document.getElementById('account_name').value = ''; // Clear the account name if input is invalid
        }
    }
</script>


<script>
    var myData = {};
    @foreach ($properties as $property)
        myData['{{ $property->id }}'] = {
            id: '{{ $property->id }}',
            name: '{{ $property->name }}',
            address: '{{ $property->address }}'
        };
    @endforeach

    $(function() {
        var instance = $('#example').magicSuggest({
            data: myData,
            renderer: function(data) {
                return '<div style="padding: 5px; overflow:hidden;">' +
                    '<div style="float: left; margin-left: 5px">' +
                    '<div style="font-weight: bold; color: #333; font-size: 10px; line-height: 11px">' +
                    data.name + '</div>' +
                    '<div style="color: #999; font-size: 9px">' + data.address + '</div>' +
                    '</div>' +
                    '</div><div style="clear:both;"></div>'; // make sure we have closed our dom stuff
            }
        });
    });
</script>
