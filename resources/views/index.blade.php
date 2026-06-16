<!doctype html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('head')
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="/home" class="text-nowrap logo-img">
                        <img src="../assets/images/logos/dark-logo.svg" width="180" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                @include('nav')
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            @include('header')


            <!--  Header End -->
            @include($page)
        </div>
    </div>
    @include('script')
    <script>
        @if (session('info'))
            Swal.fire({
                icon: "info",
                title: "Info",
                text: "{{ session('info') }}"
            });
        @endif
        @if (session('success'))
            Swal.fire({
                title: "Success",
                text: "{{ session('success') }}",
                icon: "success"
            });
        @endif
        @if (session('error'))
            Swal.fire({
                title: "Error",
                text: "{{ session('error') }}",
                icon: "error"
            });
        @endif
        @if (session('status') == 0 && session('acc_type') != 'landlord')
            Swal.fire({
                title: "Change Password",
                html: `
        <form id="change-password-form">
            <div class="form-group">
                <label for="old-password">Old Password</label>
                <input type="password" class="form-control" id="old-password" name="old_password" required>
            </div>
            <div class="form-group">
                <label for="new-password">New Password</label>
                <input type="password" class="form-control" id="new-password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" name="new_password_confirmation" required>
            </div>
            <div id="error-message-container" style="margin-top: 10px; color: red; display: none;"></div>
            <button type="submit" class="btn btn-primary" id="submit-button">Submit</button>
            <div id="loading-spinner" style="display: none; margin-top: 10px;">
                <div class="spinner-border" role="status">
                    <span class="sr-only"></span>
                </div>
            </div>
        </form>`,
                icon: "info",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false, // Prevent closing by clicking outside
                allowEscapeKey: false, // Prevent closing by pressing Escape key
                stopKeydownPropagation: true, // Stop keydown events from affecting the modal
                didOpen: () => {
                    // Add event listener to handle form submission
                    document.querySelector('#change-password-form').addEventListener('submit', async (e) => {
                        e.preventDefault(); // Prevent default form submission

                        // Hide inputs and button, show loading spinner
                        const inputs = document.querySelectorAll('.form-group input');
                        const submitButton = document.getElementById('submit-button');
                        const loadingSpinner = document.getElementById('loading-spinner');
                        const errorMessageContainer = document.getElementById(
                            'error-message-container');

                        inputs.forEach(input => input.style.display = 'none');
                        submitButton.style.display = 'none';
                        loadingSpinner.style.display = 'block';

                        // Clear any previous error messages
                        errorMessageContainer.textContent = '';
                        errorMessageContainer.style.display = 'none';

                        // Collect form data
                        const formData = {
                            old_password: document.getElementById('old-password').value,
                            new_password: document.getElementById('new-password').value,
                            new_password_confirmation: document.getElementById(
                                'confirm-password').value,
                        };

                        try {
                            // Retrieve CSRF token
                            const csrfTokenElement = document.querySelector(
                                'meta[name="csrf-token"]');
                            if (!csrfTokenElement) {
                                throw new Error(
                                    "CSRF token not found. Please refresh the page and try again."
                                );
                            }
                            const csrfToken = csrfTokenElement.getAttribute('content');

                            // Send the request to the server
                            const response = await fetch('/update-password', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                                body: JSON.stringify(formData),
                            });

                            const result = await response.json();

                            if (response.ok) {
                                // Success: Show success message and enable OK button
                                Swal.update({
                                    html: `<p style="color: green;">Password updated successfully!</p><button id="ok-button" class="btn btn-success">OK</button>`,
                                });

                                // Add event listener to close the modal on OK button click
                                document.getElementById('ok-button').addEventListener('click',
                                    () => {
                                        Swal.close();
                                    });
                            } else {
                                // Error: Show error message and restore inputs
                                errorMessageContainer.textContent = result.error ||
                                    'An error occurred';
                                errorMessageContainer.style.display = 'block';

                                inputs.forEach(input => input.style.display = 'block');
                                submitButton.style.display = 'block';
                                loadingSpinner.style.display = 'none';
                            }
                        } catch (error) {
                            // Network or other errors
                            errorMessageContainer.textContent = error.message ||
                                'An unexpected error occurred. Please try again.';
                            errorMessageContainer.style.display = 'block';

                            inputs.forEach(input => input.style.display = 'block');
                            submitButton.style.display = 'block';
                            loadingSpinner.style.display = 'none';
                        }
                    });
                },
                preConfirm: () => {
                    // Block the default preConfirm behavior to prevent premature closure
                    return new Promise((resolve, reject) => {
                        reject(new Error('Form submission is handled manually'));
                    });
                },
            });
        @endif
    </script>
</body>

</html>
