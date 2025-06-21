@include('CDN_Header')
@include('navbar')

<!-- Main Content Start -->
<div class="main-content py-10 bg-gray-50 min-h-screen">
    <!-- Forgot Password Section -->
    <section class="forgot-password section" id="forgot-password">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Forgot Password</h2>
            </div>

            <div class="max-w-xl mx-auto bg-white shadow-2xl rounded-2xl p-8">
                <form id="forgotPasswordForm">
                    @csrf
                    <!-- Email -->
                    <div class="mb-5">
                        <label for="email" class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-solid fa-envelope mr-1"></i> Email
                        </label>
                        <input type="email" id="email" name="email"
                               class="w-full px-4 py-3 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-400"
                               placeholder="Enter your email" required>
                    </div>

                    <!-- Generate OTP Button -->
                    <div class="mb-5">
                        <button type="button" id="generateOtpBtn"
                                class="inline-block w-full py-3 font-semibold text-white rounded-lg bg-gradient-to-r from-yellow-400 to-pink-500 hover:opacity-90 transition">
                            Generate OTP
                        </button>
                    </div>

                    <!-- OTP -->
                    <div class="mb-5">
                        <label for="otp" class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-solid fa-key mr-1"></i> OTP
                        </label>
                        <input type="text" id="otp" name="otp"
                               class="w-full px-4 py-3 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400"
                               placeholder="Enter OTP" required>
                    </div>

                    <!-- New Password -->
                    <div class="mb-5">
                        <label for="newPassword" class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-solid fa-lock mr-1"></i> New Password
                        </label>
                        <input type="password" id="newPassword" name="password"
                               class="w-full px-4 py-3 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400"
                               placeholder="Enter new password" required>
                    </div>

                    <!-- Submit -->
                    <div class="mb-6">
                        <button type="submit"
                                class="inline-block w-full py-3 font-semibold text-white rounded-lg bg-gradient-to-r from-yellow-400 to-pink-500 hover:opacity-90 transition btnWAN">
                            Submit
                        </button>
                    </div>

                    <!-- Links -->
                    <div class="text-center space-x-4 text-sm">
                        <a href="{{ url('/login') }}" class="text-blue-600 hover:underline">User Login</a>
                        <a href="{{ url('/forgotPasswordScreen') }}" class="text-blue-600 hover:underline">Forget Password</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@include('CDN_Footer')

<script>
    $(document).ready(function () {
        // Generate OTP
        $('#generateOtpBtn').click(function () {
            let email = $('#email').val().trim();
            if (email === '') {
                alert('Please enter an email address.');
                return;
            }

            $(this).prop('disabled', true).text('Generating OTP...');

            $.ajax({
                url: "api/email",
                method: 'POST',
                data: { user_email_id: email, _token: '{{ csrf_token() }}' },
                success: function (response) {
                    alert(response.message);
                },
                error: function () {
                    alert('Error generating OTP. Please try again.');
                },
                complete: function () {
                    $('#generateOtpBtn').prop('disabled', false).text('Generate OTP');
                }
            });
        });

        // Submit Forgot Password Form
        $('#forgotPasswordForm').submit(function (e) {
            e.preventDefault();

            let email = $('#email').val().trim();
            let otp = $('#otp').val().trim();
            let newPassword = $('#newPassword').val().trim();

            if (email === '' || otp === '' || newPassword === '') {
                alert('Please fill all fields.');
                return;
            }

            $('.btnWAN').prop('disabled', true).text('Processing...');

            $.ajax({
                url: 'api/password',
                method: 'put',
                data: $(this).serialize(),
                success: function () {
                    alert('Password reset successfully. Redirecting to login...');
                    window.location.href = "/";
                },
                error: function () {
                    alert('Error resetting password. Please check your OTP.');
                },
                complete: function () {
                    $('.btnWAN').prop('disabled', false).text('Submit');
                }
            });
        });
    });
</script>
