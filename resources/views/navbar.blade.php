@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserType = $sessionManager->iUserType;
    $bIsLogin = $sessionManager->isLoggedIn();
    $iActive = request()->query('iActive', '');
@endphp

<header class="fixed top-0 left-0 w-full z-50 transition-all duration-300">
    <div class="bg-white/95 backdrop-blur-md border-b border-slate-200/90 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            {{-- Logo + Brand --}}
            <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                <div class="p-1.5 rounded-xl bg-slate-950 border border-slate-800 shadow-md group-hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('/img/LifeHealer/logo_healer.png') }}" alt="Kvita's Healing Renaissance"
                        class="h-8 w-auto md:h-9 object-contain" />
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="text-base md:text-lg font-extrabold tracking-tight text-slate-900 group-hover:text-indigo-600 transition-colors">
                        LifeHealer <span class="text-indigo-600">Kvita's</span>
                    </span>
                    <span class="text-[10px] font-bold text-slate-500 tracking-wider uppercase">Healing Renaissance</span>
                </div>
            </a>

            {{-- Desktop Links (Pill Buttons) --}}
            <nav class="hidden md:flex items-center space-x-2 font-semibold text-slate-700 text-xs">
                <a href="{{ url('/') }}" 
                   class="px-4 py-2 rounded-full border transition-all duration-200 flex items-center space-x-2 {{ request()->is('/') ? 'bg-indigo-50 border-indigo-200 text-indigo-700 font-bold shadow-xs' : 'bg-white border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-900 shadow-2xs' }}">
                    <i class="fa fa-home text-xs text-indigo-600"></i>
                    <span>Home</span>
                </a>

                <a href="https://play.google.com/store/apps/details?id=com.healingrenaissance.app" target="_blank"
                   class="px-4 py-2 rounded-full bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-900 shadow-2xs transition-all duration-200 flex items-center space-x-2">
                    <i class="fa fa-download text-xs text-purple-600"></i>
                    <span>App</span>
                </a>

                @if ($bIsLogin)
                    @if ($iUserType == 1)
                        <a href="{{ url('dashboard') }}"
                           class="px-4 py-2 rounded-full border transition-all duration-200 flex items-center space-x-2 {{ request()->is('dashboard*') ? 'bg-indigo-50 border-indigo-200 text-indigo-700 font-bold shadow-xs' : 'bg-white border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-900 shadow-2xs' }}">
                            <i class="fa-solid fa-chart-pie text-xs text-indigo-600"></i>
                            <span>Dashboard</span>
                        </a>
                    @endif

                    <a href="{{ url('home') }}"
                       class="px-4 py-2 rounded-full border transition-all duration-200 flex items-center space-x-2 {{ request()->is('home*') || request()->is('videos*') ? 'bg-indigo-50 border-indigo-200 text-indigo-700 font-bold shadow-xs' : 'bg-white border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-900 shadow-2xs' }}">
                        <i class="fa-solid fa-layer-group text-xs text-pink-600"></i>
                        <span>Categories</span>
                    </a>

                    <div class="h-4 w-[1px] bg-slate-200 mx-1"></div>

                    <button id="logoutDesktop"
                        class="px-4 py-2 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold transition-all duration-200 shadow-2xs flex items-center space-x-2">
                        <i class="fa fa-sign-out-alt text-xs"></i>
                        <span>Log Out</span>
                    </button>
                @else
                    <a href="{{ url('login') }}" class="px-4 py-2 rounded-full bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-50 transition shadow-2xs">Login</a>
                    <a href="{{ url('register') }}" class="px-5 py-2 rounded-full bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-bold transition shadow-md shadow-pink-500/20">Register</a>
                @endif
            </nav>

            {{-- Mobile Hamburger --}}
            <button id="mobileMenuBtn"
                class="md:hidden p-2 rounded-xl bg-white text-slate-700 hover:text-slate-900 focus:outline-none border border-slate-200 shadow-2xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Slide-in Menu --}}
    <div id="mobileMenu" class="fixed inset-y-0 right-0 w-72 bg-white border-l border-slate-200 text-slate-800
              transform translate-x-full transition-transform duration-300 ease-in-out shadow-2xl z-50 flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <span class="text-base font-bold text-slate-900 tracking-wide">Menu Navigation</span>
            <button id="mobileMenuClose"
                class="p-2 rounded-lg bg-slate-200/60 text-slate-600 hover:text-slate-900 focus:outline-none transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="p-6 space-y-3 text-xs font-bold flex-1">
            <a href="{{ url('/') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-full bg-slate-50 border border-slate-200 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all">
                <i class="fa fa-home w-5 text-indigo-600"></i>
                <span>Home</span>
            </a>
            <a href="https://play.google.com/store/apps/details?id=com.healingrenaissance.app" target="_blank"
                class="flex items-center space-x-3 px-4 py-3 rounded-full bg-slate-50 border border-slate-200 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all">
                <i class="fa fa-download w-5 text-purple-600"></i>
                <span>Download App</span>
            </a>

            @if ($bIsLogin)
                @if ($iUserType == 1)
                    <a href="{{ url('dashboard') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                        <i class="fa-solid fa-chart-pie w-5"></i>
                        <span>Dashboard</span>
                    </a>
                @endif
                <a href="{{ url('home') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-full bg-slate-50 border border-slate-200 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all">
                    <i class="fa-solid fa-layer-group w-5 text-pink-600"></i>
                    <span>Video Categories</span>
                </a>
                <div class="pt-4 border-t border-slate-100">
                    <button id="logoutMobile"
                        class="w-full flex items-center justify-center space-x-2 px-4 py-3 rounded-full bg-rose-50 text-rose-700 border border-rose-200 font-bold hover:bg-rose-100 transition-all">
                        <i class="fa fa-sign-out-alt"></i>
                        <span>Log Out</span>
                    </button>
                </div>
            @else
                <div class="pt-4 space-y-2 border-t border-slate-100">
                    <a href="{{ url('login') }}" class="block w-full text-center px-4 py-3 rounded-full bg-slate-100 text-slate-900 font-bold">Login</a>
                    <a href="{{ url('register') }}" class="block w-full text-center px-4 py-3 rounded-full bg-gradient-to-r from-pink-500 to-rose-500 text-white font-bold shadow-md shadow-pink-500/20">Register</a>
                </div>
            @endif
        </nav>
    </div>

    {{-- Overlay for Mobile Menu --}}
    <div id="mobileOverlay"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-300 z-40">
    </div>
</header>

<div class="h-20"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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

        const logoutDesktop = document.getElementById('logoutDesktop');
        const logoutMobile = document.getElementById('logoutMobile');

        function performLogout() {
            $.ajax({
                url: "{{ url('logout') }}",
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function () {
                    window.location.href = "{{ url('/') }}";
                }
            });
        }

        if (logoutDesktop) logoutDesktop.addEventListener('click', performLogout);
        if (logoutMobile) logoutMobile.addEventListener('click', performLogout);
    });
</script>