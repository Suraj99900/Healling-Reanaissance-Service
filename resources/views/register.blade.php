@include('CDN_Header')
@include('navbar')

<!-- main Content start -->
<div class="main-content">

    <!-- home section start -->
    <section class="upload section " id="upload">

        <!-- <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb pt-4">
                <li class="breadcrumb-item"><a href="Dashboard.php"> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Registration</li>
            </ol>
        </nav> -->
        <div class="container-fluid padd-15">

            <!-- upload Section form  start-->
            <div class="row px-5 p-lg-5 p-md-5 p-sm-3">
                <div class="section-title padd-15 mt-5">
                    <h2>Registration</h2>

                    <!-- Alert Message Container -->
                    <div id="alertMessage" class="alert d-none mt-3" role="alert"></div>
                </div>
            </div>

            <div class="container upload-btn-section shadow-lg p-lg-5 p-sm-5 p-md-5 mb-5  rounded flex"
                style="position: relative;">
                <form id="registrationForm">
                    <!-- Email and OTP Generation -->
                    <div class="row mb-3">
                        <div class="col-8">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                        </div>
                        <div class="col-4 d-flex align-items-end">
                            <button type="button" class="btnWAN btn-secondary w-100" id="generateOTP">Generate
                                OTP</button>
                        </div>
                    </div>
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="username" placeholder="Enter User Name" required>
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
                    <button type="submit" class="btn btn-primary w-100 mb-5" id="submitRegistration">Submit</button>

                    <div class="mt-3 text-center my-5">
                        <a href="{{ url('/login') }}" class="mx-3 my-5">User Login</a>
                        <a href="{{ url('/forgotPasswordScreen') }}" class="mx-3 my-5">Forget password</a>
                    </div>

                </form>
                <!-- Additional Links -->



            </div>

        </div>
    </section>
    <!-- home section end -->


</div>





<!-- include footer section -->
@include('CDN_Footer')

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