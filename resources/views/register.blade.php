@include('CDN_Header')
@include('navbar')

<!-- Main Content -->
<div class="main-content bg-gray-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-lg p-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">User Registration</h2>
            <p class="text-sm text-gray-500 mt-2">Register your account to access the platform</p>
        </div>

        <!-- Alert Message -->
        <div id="alertMessage"
            class="hidden px-4 py-3 rounded-md text-sm font-medium mb-6"
            role="alert"></div>

        <!-- Registration Form -->
        <form id="registrationForm" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                <!-- Email -->
                <div class="sm:col-span-8">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Enter your email" required>
                </div>

                <!-- Generate OTP -->
                <div class="sm:col-span-4 flex items-end">
                    <button type="button" id="generateOTP"
                        class="w-full inline-block bg-gradient-to-r from-yellow-400 to-pink-500 text-white  font-medium py-2 px-4 rounded-md transition shadow">
                        Generate OTP
                    </button>
                </div>
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter your name" required>
            </div>

            <!-- OTP -->
            <div>
                <label for="otp" class="block text-sm font-medium text-gray-700">OTP</label>
                <input type="text" id="otp"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter OTP" required>
            </div>

            <!-- Key -->
            <div>
                <label for="key" class="block text-sm font-medium text-gray-700">Key</label>
                <input type="text" id="key"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter your key" required>
            </div>

            <!-- User Type -->
            <div>
                <label for="user_type" class="block text-sm font-medium text-gray-700">User Type</label>
                <select id="user_type"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required>
                    <option value="">Select User Type</option>
                    <option value="1">Super-Admin</option>
                    <option value="2">App-User</option>
                    <option value="3">Developer</option>
                    <option value="4">Admin</option>
                </select>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter password" required>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full inline-block bg-gradient-to-r from-yellow-400 to-pink-500   text-white font-semibold py-2 px-4 rounded-md transition shadow">
                    Register
                </button>
            </div>

            <!-- Links -->
            <div class="flex justify-center items-center space-x-6 text-sm text-gray-600 pt-6 border-t">
                <a href="{{ url('/login') }}" class="hover:text-indigo-600 font-medium transition">User Login</a>
                <span>|</span>
                <a href="{{ url('/forgotPasswordScreen') }}"
                    class="hover:text-indigo-600 font-medium transition">Forgot Password?</a>
            </div>
        </form>
    </div>
</div>

@include('CDN_Footer')

<!-- JavaScript Section -->
<script>
    $(document).ready(function () {
        // Show Alert
        function showAlert(message, type) {
            const alertBox = $('#alertMessage');
            const typeClass = type === 'success' ? 'bg-green-100 text-green-800 border-green-300' :
                'bg-red-100 text-red-800 border-red-300';
            alertBox.removeClass().addClass(`block px-4 py-3 rounded-md text-sm font-medium mb-6 border ${typeClass}`)
                .text(message);
        }

        // OTP Generator
        $('#generateOTP').click(function () {
            const email = $('#email').val();
            if (!email) {
                showAlert('Please enter your email to generate OTP', 'error');
                return;
            }

            $(this).prop('disabled', true).text('Generating...');
            $.ajax({
                url: '/api/email',
                type: 'POST',
                data: { user_email_id: email },
                success: function (response) {
                    showAlert(response.message, 'success');
                    $('#generateOTP').prop('disabled', false).text('Generate OTP');
                },
                error: function (xhr) {
                    const err = xhr.responseJSON?.error || 'Error generating OTP';
                    showAlert(err, 'error');
                    $('#generateOTP').prop('disabled', false).text('Generate OTP');
                }
            });
        });

        // Registration Submit
        $('#registrationForm').submit(function (e) {
            e.preventDefault();
            const formData = {
                email: $('#email').val(),
                userName: $('#username').val(),
                otp: $('#otp').val(),
                secretkey: $('#key').val(),
                userType: $('#user_type').val(),
                password: $('#password').val()
            };

            $.ajax({
                url: '/api/users',
                type: 'POST',
                data: formData,
                success: function (response) {
                    showAlert(response.message, 'success');
                    setTimeout(() => window.location.href = '/login', 2000);
                },
                error: function (xhr) {
                    const err = xhr.responseJSON?.error || 'Registration failed';
                    showAlert(err, 'error');
                }
            });
        });
    });
</script>
