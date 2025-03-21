@include('CDN_Header')
@include('navbar')

<div class="main-content">
    <section class="upload section px-5" id="upload">
        <div class="container-fluid padd-15">
            <div class="row px-5 p-lg-5 p-md-5 p-sm-3">
                <div class="section-title padd-15 mt-5">
                    <h2>Login</h2>
                    <div id="alertMessage" class="alert d-none" role="alert"></div>
                </div>
            </div>
            <div class="container p-lg-5 rounded" style="position: relative;">
                <form class="login-form" id="loginForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-login" id="loginButton">
                            <span id="loginButtonText">Log in</span>
                            <span id="loginSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="d-grid mb-5">
                        <div class="d-flex justify-content-between my-5">
                            <a href="{{ url('/register') }}" class=" ">Register User</a>
                            <a href="{{ url('/forgotPasswordScreen') }}" class="">Forget password</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Modal for user type 1 -->
<div class="modal fade bg-dark" id="userTypeModal" tabindex="-1" aria-labelledby="userTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userTypeModalLabel">Choose Destination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Where would you like to go?</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btnWAN btn-primary" id="goToDashboard">Admin Dashboard</button>
                    <button type="button" class="btnWAN btn-secondary" id="goToHome">Home</button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('CDN_Footer')

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
                type: "GET",     // Using POST for login
                data: {
                    email: email,
                    password: password,
                },
                success: function (response) {
                    console.log(response['body']);
                    var aData = response['body'];
                    console.log(aData);
                    
                     // Make another Ajax request for session.php
                     $.ajax({
                            url: "setSession",
                            method: "POST",
                            headers:{
                                'X-CSRF-TOKEN': $('#csrfid').val()
                            },
                            data: {
                                "id": aData.id,
                                "user_type": aData.user_type,
                                "username": aData.user_name,
                                "email": aData.email,
                                "password": "",  // Assuming password is needed for session.php
                                "login": 1,
                            },
                            dataType: "json",
                            success: function (sessionData) {
                                if (sessionData.aSessionData) {
                                    if (aData.user_type == 1) {
                                        $("#alertMessage").removeClass("d-none").addClass("alert-success").text(response.message || "Login Successful!");
                                        // Show the modal
                                        $('#userTypeModal').modal('show');
                                    } else {
                                        $("#alertMessage").removeClass("d-none").addClass("alert-success").text(response.message || "Login Successful!");
                                        setTimeout(function () {
                                            window.location.href = "home";
                                        }, 2000);
                                    }
                                } else {
                                    responsePop('Error', 'Failed to log in', 'error', 'ok');
                                }
                            },
                            error: function (error) {
                                // Handle Ajax error for session.php
                                responsePop('Error', 'Failed to log in', 'error', 'ok');
                            }
                        });
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

        // Handle modal button clicks
        $("#goToDashboard").click(function () {
            window.location.href = "dashboard";
        });

        $("#goToHome").click(function () {
            window.location.href = "home";
        });
    });
</script>
<!-- Bootstrap Bundle with Popper -->