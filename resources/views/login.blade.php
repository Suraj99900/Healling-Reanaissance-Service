@include('CDN_Header')
@include('NavBar')



<div class="main-content">



    <section class="upload section px-5" id="upload">

        <!-- <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb pt-4">
                <li class="breadcrumb-item"><a href="Dashboard.php"> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Login</li>
            </ol>
        </nav> -->
        <div class="container-fluid padd-15">

            <!-- upload Section form  start-->
            <div class="row px-5 p-lg-5 p-md-5 p-sm-3">
                <div class="section-title padd-15 mt-5">
                    <h2> Login</h2>
                    <div id="alertMessage" class="alert d-none" role="alert"></div>
                </div>
            </div>
            <!-- <h3 class="contact-title padd-15">Login </h3> -->
            <div class="container p-lg-5  rounded" style="position: relative;">
                <form class="login-form" id="loginForm">
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

                    <div class="d-grid mb-5">

                        <!-- Additional Options -->
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

<!-- include footer section -->
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
                type: "get",     // Using POST for login
                data: {
                    email: email,
                    password: password,
                },
                success: function (response) {
                    console.log(response['body']);
                    var aData = response['body'];
                    if (aData.user_type == 1) {
                        $("#alertMessage").removeClass("d-none").addClass("alert-success").text(response.message || "Login Successful!");
                        // Redirect after 2 seconds (adjust URL as needed)
                        setTimeout(function () {
                            window.location.href = "/dashboard";
                        }, 2000);
                    }else{
                        $("#alertMessage").removeClass("d-none").addClass("alert-danger").text("Only admin can access.");
                    }
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