<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration | User Access</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts (Cairo) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: url('/assets/images/register.png');
            background-size: cover;
        }

        .registration-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 10px 10px 20px rgba(0, 0, 0, 0.2);
        }

        .logo {
            display: block;
            margin: 0 auto 20px auto;
            max-width: 200px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 col-lg-6">
                <div class="registration-container">
                    <h3 class="text-center mb-4">Registration User</h3>
                    <form id="registrationForm">
                        <!-- Email and OTP Generation -->
                        <div class="row mb-3">
                            <div class="col-8">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter your email"
                                    required>
                            </div>
                            <div class="col-4 d-flex align-items-end">
                                <button type="button" class="btn btn-secondary w-100" id="generateOTP">Generate
                                    OTP</button>
                            </div>
                        </div>
                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">User Name</label>
                            <input type="text" class="form-control" id="username" placeholder="Enter User Name"
                                required>
                        </div>
                        <!-- OTP -->
                        <div class="mb-3">
                            <label for="otp" class="form-label">OTP</label>
                            <input type="text" class="form-control" id="otp" placeholder="Enter OTP" required>
                        </div>
                        <!-- Key -->
                        <div class="mb-3">
                            <label for="key" class="form-label">Key</label>
                            <input type="text" class="form-control" id="key" placeholder="Enter your Key" required>
                        </div>
                        <!-- User Type Dropdown -->
                        <div class="mb-3">
                            <label for="user_type" class="form-label">User Type</label>
                            <select class="form-select" id="user_type" required>
                                <option value="">Select User Type</option>
                                <option value="1">Supper-Admin</option>
                                <option value="2">App-user</option>
                                <option value="3">Developer</option>
                                <option value="4">Admin</option>
                            </select>
                        </div>
                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter your password"
                                required>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100" id="submitRegistration">Submit</button>
                    </form>
                    <!-- Additional Links -->
                    <div class="mt-3 text-center">
                        <a href="{{ url('/login') }}" class="btn btn-link">User Login</a>
                        <a href="{{ url('/forgetpassword') }}" class="btn btn-link">Forget password</a>
                    </div>
                    <!-- Alert Message Container -->
                    <div id="alertMessage" class="alert d-none mt-3" role="alert"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Function to display alert messages
            function showAlert(message, type) {
                var alertBox = $('#alertMessage');
                alertBox.removeClass('d-none')
                    .removeClass('alert-success alert-danger')
                    .addClass('alert-' + type)
                    .text(message);
            }

            // Handle OTP generation
            $('#generateOTP').click(function () {
                var email = $('#email').val();
                if (email === '') {
                    showAlert('Please enter your email to generate OTP', 'danger');
                    return;
                }
                $(this).prop('disabled', true).text('Generating...');
                $.ajax({
                    url: '/api/email', // Adjust endpoint if needed
                    type: 'POST',
                    data: { user_email_id: email },
                    success: function (response) {
                        showAlert(response.message, 'success');
                        $('#generateOTP').prop('disabled', false).text('Generate OTP');
                    },
                    error: function (xhr) {
                        var err = xhr.responseJSON.error || 'Error generating OTP';
                        showAlert(err, 'danger');
                        $('#generateOTP').prop('disabled', false).text('Generate OTP');
                    }
                });
            });

            // Handle Registration Form Submission
            $('#registrationForm').submit(function (e) {
                e.preventDefault();
                var formData = {
                    email: $('#email').val(),
                    userName: $('#username').val(),
                    otp: $('#otp').val(),
                    secretkey: $('#key').val(),
                    userType: $('#user_type').val(),
                    password: $('#password').val()
                };

                $.ajax({
                    url: '/api/users', // Adjust endpoint if needed
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        showAlert(response.message, 'success');
                        // Redirect to login page after 2 seconds (adjust URL if needed)
                        setTimeout(function () {
                            window.location.href = '/login';
                        }, 2000);
                    },
                    error: function (xhr) {
                        var err = xhr.responseJSON.error || 'Registration failed';
                        showAlert(err, 'danger');
                    }
                });
            });
        });
    </script>
    <!-- Bootstrap JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>