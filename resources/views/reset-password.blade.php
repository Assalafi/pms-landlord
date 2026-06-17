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
                
                <!-- Display error message -->
                @if(session('error'))
                  <div class="alert alert-danger">
                    {{ session('error') }}
                  </div>
                @endif
                
                <!-- Show email for reference -->
                <div class="mb-3">
                    <p class="text-muted">Resetting password for: <strong>{{ $email }}</strong></p>
                </div>
                <!-- Login form -->
                <form id="reset-password-form" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="mb-4">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">New Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
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
    document.querySelector('#reset-password-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(document.getElementById('reset-password-form'));
        const errorMessageContainer = document.getElementById('error-message-container');

        try {
            const response = await fetch('/reset-password', {
                method: 'POST',
                body: formData,
            });

            const result = await response.json();

            if (response.ok) {
                alert('Success: ' + result.message);
                window.location.href = '/login'; // Redirect to login page
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
