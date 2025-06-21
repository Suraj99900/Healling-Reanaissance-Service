@include('CDN_Header')
@include('navbar')

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Title -->
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Log In
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Or
                <a href="{{ url('/register') }}" class="font-medium text-violet-600 hover:text-violet-500">
                    Register a new user
                </a>
            </p>
        </div>

        <!-- Alert Message -->
        <div id="alertMessage" class="hidden text-center text-white p-3 rounded text-sm" role="alert"
            style="background-color: #e11d48;"></div>

        <!-- Form Card -->
        <div class="bg-white py-8 px-6 shadow-lg rounded-lg">
            <form id="loginForm" class="space-y-6 p-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input id="email" name="email" type="email" required
                            class="block w-full pl-10 pr-3 p-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-violet-500 focus:border-violet-500 sm:text-sm"
                            placeholder="you@example.com" />
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input id="password" name="password" type="password" required
                            class="block w-full pl-10 p-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-violet-500 focus:border-violet-500 sm:text-sm"
                            placeholder="••••••••" />
                    </div>
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" id="loginButton"
                        class="w-full flex justify-center py-2 px-4 border-transparent rounded-md shadow-sm text-sm font-medium inline-block bg-gradient-to-r from-blue-500 to-cyan-500 text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="loginButtonText">Log In</span>
                        <svg id="loginSpinner" class="hidden animate-spin ml-2 h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Links -->
                <div class="flex items-center justify-between">
                    <a href="{{ url('/forgotPasswordScreen') }}"
                        class="text-sm font-medium text-violet-600 hover:text-violet-500">Forgot your password?</a>
                    <a href="{{ url('/register') }}"
                        class="text-sm font-medium text-violet-600 hover:text-violet-500">Register User</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Slide-Over Panel -->
<div id="userTypePanel"
    class="fixed inset-y-0 right-0 z-50 w-full max-w-sm transform translate-x-full bg-white shadow-2xl overflow-y-auto transition-transform duration-300 ease-in-out rounded-l-lg"
    role="dialog" aria-modal="true">
    <div class="flex items-center justify-between bg-violet-600 px-6 py-4 rounded-tl-lg">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-location-dot text-black text-xl"></i>
            </div>
            <h2 id="userTypePanelLabel" class="text-lg font-semibold text-white">Choose Destination</h2>
        </div>
        <button id="userTypePanelClose" class="text-white hover:text-gray-200 focus:outline-none" aria-label="Close panel">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="p-6 space-y-6">
        <p class="text-gray-700 text-center">Where would you like to go?</p>
        <div class="space-y-4">
            <button id="goToDashboard"
                class="w-full flex items-center justify-center px-4 py-3 text-white rounded-lg bg-gradient-to-r from-blue-500 to-cyan-500 font-medium shadow hover:from-blue-600 hover:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition">
                <i class="fa-solid fa-chart-line mr-3 text-lg"></i>
                Admin Dashboard
            </button>
            <button id="goToHome"
                class="w-full flex items-center justify-center px-4 py-3 text-white rounded-lg bg-gradient-to-r from-purple-500 to-pink-500 font-medium shadow hover:from-purple-600 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition">
                <i class="fa-solid fa-film mr-3 text-lg"></i>
                Video Category
            </button>
        </div>
    </div>
</div>

@include('CDN_Footer')

<!-- Script Section -->
<script>
    $(document).ready(function () {
        $("#loginForm").submit(function (event) {
            event.preventDefault();
            $("#alertMessage")
                .addClass("hidden")
                .removeClass("bg-green-100 text-green-700 bg-rose-100 text-rose-700")
                .text("");

            let email = $("#email").val().trim();
            let password = $("#password").val().trim();

            if (email === "" || password === "") {
                $("#alertMessage")
                    .removeClass("hidden")
                    .addClass("bg-rose-500 text-white")
                    .text("Please fill in all fields.");
                return;
            }

            $("#loginButton").prop("disabled", true);
            $("#loginButtonText").text("Logging in...");
            $("#loginSpinner").removeClass("hidden");

            $.ajax({
                url: "/api/login",
                type: "GET",
                data: { email: email, password: password },
                success: function (response) {
                    const aData = response["body"];
                    $.ajax({
                        url: "setSession",
                        method: "POST",
                        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                        data: {
                            id: aData.id,
                            user_type: aData.user_type,
                            username: aData.user_name,
                            email: aData.email,
                            password: "",
                            login: 1,
                        },
                        dataType: "json",
                        success: function (sessionData) {
                            if (sessionData.aSessionData) {
                                $("#alertMessage")
                                    .removeClass("hidden")
                                    .addClass("bg-emerald-500 text-white")
                                    .text(response.message || "Login Successful!");
                                if (aData.user_type == 1) {
                                    $("#userTypePanel").removeClass("translate-x-full");
                                    if (!$("#userTypeOverlay").length) {
                                        $("body").append(`<div id="userTypeOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>`);
                                        $("#userTypeOverlay").click(closeUserTypePanel);
                                    }
                                } else {
                                    setTimeout(() => window.location.href = "home", 2000);
                                }
                            } else {
                                alert("Error: Failed to log in.");
                            }
                        },
                        error: () => alert("Error: Failed to log in."),
                    });
                },
                error: function (xhr) {
                    let errorMessage = "Invalid email or password!";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    $("#alertMessage")
                        .removeClass("hidden")
                        .addClass("bg-rose-500 text-white")
                        .text(errorMessage);
                },
                complete: function () {
                    $("#loginButton").prop("disabled", false);
                    $("#loginButtonText").text("Log in");
                    $("#loginSpinner").addClass("hidden");
                },
            });
        });

        function closeUserTypePanel() {
            $("#userTypePanel").addClass("translate-x-full");
            $("#userTypeOverlay").remove();
        }

        $("#userTypePanelClose").click(closeUserTypePanel);
        $("#goToDashboard").click(() => window.location.href = "dashboard");
        $("#goToHome").click(() => window.location.href = "home");
    });
</script>
