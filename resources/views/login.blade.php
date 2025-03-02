<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Admin Access</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: url("{{ asset('assets/images/login.png') }}") no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .login-logo {
            text-align: center;
            margin-top: 10vh;
            margin-bottom: 20px;
        }

        .login-logo img {
            width: 40%;
            max-width: 300px;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 10px 10px 20px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin: 0 auto;
            width: 90%;
            max-width: 600px;
            min-height: 60vh;
        }

        .login-title {
            color: rgba(34, 13, 2, 0.68);
            font-weight: bold;
            margin-bottom: 20px;
        }

        .btn-login {
            background: #0d6efd;
            color: #000;
        }

        .btn-login:hover {
            background: #0b5ed7;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Logo Section -->
        <div class="login-logo">
            <img src="{{ asset('assets/images/new_logo.png') }}" alt="Logo">
        </div>
        <!-- Login Form Container -->
        <div class="login-container mx-auto">
            <h3 class="text-center login-title">User Login</h3>
            <form id="loginForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter your password"
                        required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-login" id="loginButton">
                        <span id="loginButtonText">Log in</span>
                        <span id="loginSpinner" class="spinner-border spinner-border-sm d-none" role="status"
                            aria-hidden="true"></span>
                    </button>
                </div>
            </form>
            <!-- Alert Message -->
            <div id="alertMessage" class="alert d-none" role="alert"></div>
            <!-- Additional Options -->
            <div class="d-flex justify-content-between">
                <a href="{{ url('/register') }}" class="btn btn-link">Register User</a>
                <a href="{{ url('/forgetpassword') }}" class="btn btn-link">Forget password</a>
            </div>
        </div>
    </div>

    <!-- jQuery Script to Handle Login -->
    <script>
        $(document).ready(function () {
            $("#loginForm").submit(function (event) {
                event.preventDefault();
                // Clear any previous alerts
                $("#alertMessage").addClass("d-none").removeClass("alert-success alert-danger").text("");
                // Get form values
                let email = $("#email").val().trim();
                let password = $("#password").val().trim();

                // Basic validation (fields are required)
                if (email === "" || password === "") {
                    $("#alertMessage").removeClass("d-none").addClass("alert-danger").text("Please fill in all fields.");
                    return;
                }

                // Show spinner and disable button
                $("#loginButton").prop("disabled", true);
                $("#loginButtonText").text("Logging in...");
                $("#loginSpinner").removeClass("d-none");

                $.ajax({
                    url: "/api/login", // Ensure this endpoint matches your API
                    type: "get",     // Using POST for login
                    data: {
                        email: email,
                        password: password
                    },
                    success: function (response) {
                        $("#alertMessage").removeClass("d-none").addClass("alert-success").text(response.message || "Login Successful!");
                        // Redirect after 2 seconds (adjust URL as needed)
                        setTimeout(function () {
                            window.location.href = "/dashboard";
                        }, 2000);
                    },
                    error: function (xhr) {
                        let errorMessage = "Invalid email or password!";
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        $("#alertMessage").removeClass("d-none").addClass("alert-danger").text(errorMessage);
                    },
                    complete: function () {
                        // Re-enable button and hide spinner
                        $("#loginButton").prop("disabled", false);
                        $("#loginButtonText").text("Log in");
                        $("#loginSpinner").addClass("d-none");
                    }
                });
            });
        });
    </script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>