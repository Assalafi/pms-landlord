<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Landlord | Sublime Rent</title>
  <link rel="shortcut icon" type="image/png" href="{{ url('assets/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ url('assets/css/styles.min.css') }}" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="/" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  {{-- <img src="{{ url('assets/images/logos/dark-logo.svg') }}" width="180" alt=""> --}}
                  <h3>Sublime Rent</h3>
                  <center>
                    <p class="text-muted m-0">Forgot Password</p>
                  </center>
                </a>

                <!-- Display validation errors -->
                @if ($errors->any())
                  <div class="alert alert-danger alert-sm">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <!-- Display success message -->
                @if(session('success'))
                  <div class="alert alert-success">
                    {{ session('success') }}
                  </div>
                @endif

                <!-- Login form -->
                <form id="forgot-password-form" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                    <div id="error-message-container" style="margin-top: 10px; color: red; display: none;"></div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ url('assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script>
    document.querySelector('#forgot-password-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const errorMessageContainer = document.getElementById('error-message-container');

        try {
            const response = await fetch('/forgot-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ email }),
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire({
                    title: 'Success',
                    text: result.message,
                    icon: 'success',
                });
            } else {
                errorMessageContainer.textContent = result.error || 'An error occurred';
                errorMessageContainer.style.display = 'block';
            }
        } catch (error) {
            errorMessageContainer.textContent = 'An unexpected error occurred. Please try again.';
            errorMessageContainer.style.display = 'block';
        }
    });
</script>
</body>

</html>
