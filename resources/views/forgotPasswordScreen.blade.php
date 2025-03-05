@include('CDN_Header')
@include('navbar')

<!-- Main Content Start -->
<div class="main-content">
    <!-- Forgot Password Section -->
    <section class="forgot-password section" id="forgot-password">
        <div class="container-fluid padd-15">
            <div class="row px-5 p-lg-5 p-md-5 p-sm-3">
                <div class="section-title padd-15 mt-5">
                    <h2>Forgot Password</h2>
                </div>
            </div>
            <div class="container shadow-lg p-lg-5 p-sm-5 p-md-5 mb-5 rounded" style="position: relative;">
                <form id="forgotPasswordForm">
                    @csrf
                    <div class="row align-items-center p-3">
                        <div class="col-12">
                            <label for="email" class="form-label"><i class="fa-solid fa-envelope"></i> Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter your email" required>
                        </div>
                    </div>
                    <div class="row align-items-center p-3">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary w-100" id="generateOtpBtn">Generate
                                OTP</button>
                        </div>
                    </div>
                    <div class="row align-items-center p-3">
                        <div class="col-12">
                            <label for="otp" class="form-label"><i class="fa-solid fa-key"></i> OTP</label>
                            <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP"
                                required>
                        </div>
                    </div>
                    <div class="row align-items-center p-3">
                        <div class="col-12">
                            <label for="password" class="form-label"><i class="fa-solid fa-lock"></i> New
                                Password</label>
                            <input type="password" class="form-control" id="newPassword" name="password"
                                placeholder="Enter your new password" required>
                        </div>
                    </div>
                    <div class="flex search-btn mt-5">
                        <button type="submit" class="btnWAN mb-4">Submit</button>
                    </div>

                    <div class="mt-3 text-center my-5">
                        <a href="{{ url('/login') }}" class="mx-3 my-5">User Login</a>
                        <a href="{{ url('/forgotPasswordScreen') }}" class="mx-3 my-5">Forget password</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@include('CDN_Footer')

<script>

        $(document).ready(function() {
            // Generate OTP
            $('#generateOtpBtn').click(function () {
                let email = $('#email').val().trim();

                if (email === '') {
                    alert('Please enter an email address.');
                    return;
                }

                $('#generateOtpBtn').prop('disabled', true).text('Generating OTP...');

                // AJAX request to generate OTP
                $.ajax({
                    url: "api/email",
                    method: 'POST',
                    data: { user_email_id: email, _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        alert(response.message);
                    },
                    error: function (err) {
                        alert('Error generating OTP. Please try again.');
                    },
                    complete: function () {
                        $('#generateOtpBtn').prop('disabled', false).text('Generate OTP');
                    }
                });
            });

        // Handle Forgot Password Form Submission
        $('#forgotPasswordForm').submit(function(e) {
            e.preventDefault();

        let email = $('#email').val().trim();
        let otp = $('#otp').val().trim();
        let newPassword = $('#newPassword').val().trim();

        if (email === '' || otp === '' || newPassword === '') {
            alert('Please fill all fields.');
        return;
        }

        $('.btnWAN').prop('disabled', true).text('Processing...');

        // AJAX request to reset password
        $.ajax({
            url: 'api/password',
        method: 'put',
        data: $(this).serialize(),
        success: function(response) {
            alert('Password reset successfully. Redirecting to login...');
        window.location.href = "/";
            },
        error: function(err) {
            alert('Error resetting password. Please check your OTP.');
            },
        complete: function() {
            $('.btnWAN').prop('disabled', false).text('Submit');
            }
        });
    });
});
</script>

</script>