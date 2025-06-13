@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserType = $sessionManager->iUserType;
    $bIsLogin = $sessionManager->isLoggedIn();
    $iActive = request()->query('iActive', '');
@endphp

<header class="fixed top-0 left-0 w-full z-50">
    <div class="bg-gradient-to-r from-indigo-700 via-purple-700 to-pink-600 bg-opacity-90 backdrop-blur-md shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            {{-- Logo + Brand --}}
            <a href="{{ url('/') }}"
                class="flex items-center space-x-3 hover:scale-105 transition-transform duration-300">
                <img src="{{ asset('/img/LifeHealer/logo_healer.png') }}" alt="Kvita's Healing Renaissance"
                    class="h-8 w-auto md:h-10" />
                <div class="hidden md:flex flex-col leading-tight">
                    <span class="text-lg md:text-xl font-extrabold text-white">LifeHealer Kvita's</span>
                    <span class="text-sm md:text-base text-gray-200">Healling Renaissance</span>
                </div>
            </a>

            {{-- Desktop Links --}}
            <nav class="hidden md:flex space-x-8 text-sm font-medium text-white">
                <a href="{{ url('/') }}" class="relative group flex items-center space-x-1 transition-colors duration-200
             {{ request()->is('/') ? 'text-pink-300' : 'hover:text-pink-300' }}">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                    <span class="absolute left-0 bottom-[-2px] h-0.5 w-0 bg-pink-300 scale-x-0 group-hover:scale-x-100
                       {{ request()->is('/') ? 'scale-x-100' : '' }} transition-transform origin-left"></span>
                </a>
                <a href="https://play.google.com/store/apps/details?id=com.healingrenaissance.app" target="_blank"
                    class="relative group flex items-center space-x-1 transition-colors duration-200 hover:text-pink-300">
                    <i class="fa fa-download"></i>
                    <span>Download App</span>
                    <span class="absolute left-0 bottom-[-2px] h-0.5 w-0 bg-pink-300 scale-x-0 group-hover:scale-x-100
                       transition-transform origin-left"></span>
                </a>

                @if ($bIsLogin)
                    @if ($iUserType == 1)
                        <a href="{{ url('dashboard') }}"
                            class="relative group flex items-center space-x-1 transition-colors duration-200 hover:text-pink-300">
                            <i class="fa-solid fa-chart-line"></i>
                            <span>Dashboard</span>
                            <span class="absolute left-0 bottom-[-2px] h-0.5 w-0 bg-pink-300 scale-x-0 group-hover:scale-x-100
                                   transition-transform origin-left"></span>
                        </a>
                    @endif
                    <a href="{{ url('home') }}"
                        class="relative group flex items-center space-x-1 transition-colors duration-200 hover:text-pink-300">
                        <i class="fa-solid fa-film"></i>
                        <span>Video Category</span>
                        <span class="absolute left-0 bottom-[-2px] h-0.5 w-0 bg-pink-300 scale-x-0 group-hover:scale-x-100
                             transition-transform origin-left"></span>
                    </a>

                    <button id="logoutDesktop"
                        class="flex items-center space-x-1 hover:text-pink-300 transition-colors duration-200 focus:outline-none">
                        <i class="fa fa-sign-out-alt"></i>
                        <span>Log Out</span>
                    </button>
                @else
                    <a href="{{ url('login') }}" class="relative group transition-colors duration-200 hover:text-pink-300">
                        <span>Login</span>
                        <span class="absolute left-0 bottom-[-2px] h-0.5 w-0 bg-pink-300 scale-x-0 group-hover:scale-x-100
                             transition-transform origin-left"></span>
                    </a>
                    <a href="{{ url('register') }}"
                        class="relative group transition-colors duration-200 hover:text-pink-300">
                        <span>Register</span>
                        <span class="absolute left-0 bottom-[-2px] h-0.5 w-0 bg-pink-300 scale-x-0 group-hover:scale-x-100
                             transition-transform origin-left"></span>
                    </a>
                @endif
            </nav>

            {{-- Mobile Hamburger --}}
            <button id="mobileMenuBtn"
                class="md:hidden text-white focus:outline-none transition-transform transform hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Slide-in Menu --}}
    <div id="mobileMenu" class="fixed inset-y-0 right-0 w-64 bg-gradient-to-b from-purple-700 to-pink-600 text-white
              transform translate-x-full transition-transform duration-300 ease-in-out shadow-xl z-50">
        <div class="px-6 py-4 flex items-center justify-between">
            <span class="text-2xl font-bold">Menu</span>
            <button id="mobileMenuClose"
                class="focus:outline-none transition-transform transform hover:rotate-90 duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="px-6 py-4 space-y-4 text-base font-medium">
            <a href="{{ url('/') }}"
                class="flex items-center space-x-2 hover:text-yellow-300 transition-colors duration-200">
                <i class="fa fa-home"></i>
                <span>Home</span>
            </a>
            <a href="https://play.google.com/store/apps/details?id=com.healingrenaissance.app" target="_blank"
                class="flex items-center space-x-2 hover:text-yellow-300 transition-colors duration-200">
                <i class="fa fa-download"></i>
                <span>Download App</span>
            </a>

            @if ($bIsLogin)
                @if ($iUserType == 1)
                    <a href="{{ url('dashboard') }}"
                        class="flex items-center space-x-2 hover:text-yellow-300 transition-colors duration-200">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                @endif
                <a href="{{ url('home') }}"
                    class="flex items-center space-x-2 hover:text-yellow-300 transition-colors duration-200">
                    <i class="fa-solid fa-film"></i>
                    <span>Video Category</span>
                </a>
                <button id="logoutMobile"
                    class="flex items-center space-x-2 hover:text-yellow-300 transition-colors duration-200 focus:outline-none">
                    <i class="fa fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </button>
            @else
                <a href="{{ url('login') }}" class="hover:text-yellow-300 transition-colors duration-200">Login</a>
                <a href="{{ url('register') }}" class="hover:text-yellow-300 transition-colors duration-200">Register</a>
            @endif
        </nav>
    </div>

    {{-- Overlay for Mobile Menu --}}
    <div id="mobileOverlay"
        class="fixed inset-0 bg-black bg-opacity-50 opacity-0 pointer-events-none transition-opacity duration-300 z-40">
    </div>
</header>

{{-- Spacer so fixed navbar won’t cover content --}}
<div class="h-20"></div>

{{-- ----------------------------
Corrected JavaScript
----------------------------- --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ----- Mobile menu toggle -----
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const mobileMenuClose = document.getElementById('mobileMenuClose');

        if (mobileMenuBtn && mobileMenu && mobileOverlay && mobileMenuClose) {
            mobileMenuBtn.addEventListener('click', function () {
                mobileMenu.classList.remove('translate-x-full');
                mobileOverlay.classList.remove('opacity-0', 'pointer-events-none');
            });

            mobileMenuClose.addEventListener('click', closeMobileMenu);
            mobileOverlay.addEventListener('click', closeMobileMenu);

            function closeMobileMenu() {
                mobileMenu.classList.add('translate-x-full');
                mobileOverlay.classList.add('opacity-0', 'pointer-events-none');
            }
        }

        // ----- Logout buttons (only present if logged in) -----
        const logoutDesktop = document.getElementById('logoutDesktop');
        const logoutMobile = document.getElementById('logoutMobile');

        if (logoutDesktop) {
            logoutDesktop.addEventListener('click', function () {
                $.ajax({
                    url: "{{ url('logout') }}",
                    type: 'POST',
                    data: {
                        _token
                            : '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Handle successful logout, e.g., redirect to home
                        window.location.href = "{{ url('/') }}";
                    },
                });
            });
        }
        if (logoutMobile) {
            logoutMobile.addEventListener('click', function () {
                $.ajax({
                    url: "{{ url('logout') }}",
                    type: 'POST',
                    data: {
                        _token
                            : '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Handle successful logout, e.g., redirect to home
                        window.location.href = "{{ url('/') }}";
                    },
                });
            });
        }
    });
</script>