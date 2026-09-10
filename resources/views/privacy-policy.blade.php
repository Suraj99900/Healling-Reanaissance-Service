{{-- resources/views/privacy-policy.blade.php --}}
@include('CDN_Header')
@include('navbar')

<div class="min-h-screen bg-[#F8FAFC] text-slate-800 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="text-center mb-10 max-w-2xl mx-auto">
            <span class="inline-flex items-center space-x-2 bg-indigo-50 text-indigo-700 text-xs font-semibold px-3.5 py-1.5 rounded-full border border-indigo-100 mb-3">
                <i class="fa-solid fa-shield-halved text-xs text-indigo-600"></i>
                <span>Privacy & Trust</span>
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-3">Privacy Policy</h1>
            <p class="text-sm text-slate-500">Your privacy is important to us. Search or browse our policy details below.</p>
        </div>

        {{-- Search Bar --}}
        <div class="max-w-xl mx-auto mb-10">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" id="searchPolicy"
                    class="w-full bg-white border border-slate-200/90 rounded-2xl pl-11 pr-4 py-3 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 shadow-sm transition"
                    placeholder="Search Privacy Policy topics..." />
            </div>
        </div>

        {{-- Policy Content Cards --}}
        <div id="privacyPolicyContent" class="space-y-6">
            
            <div class="policy-card bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-xs">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">1. Information We Collect</h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                    We collect personal information that you provide to us when registering for an account or using our application services:
                </p>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-medium text-slate-700">
                    <li class="flex items-center space-x-2 bg-slate-50 border border-slate-200/60 p-3 rounded-xl">
                        <i class="fa-solid fa-check text-emerald-500 text-xs"></i>
                        <span>Full Name</span>
                    </li>
                    <li class="flex items-center space-x-2 bg-slate-50 border border-slate-200/60 p-3 rounded-xl">
                        <i class="fa-solid fa-check text-emerald-500 text-xs"></i>
                        <span>Email Address</span>
                    </li>
                    <li class="flex items-center space-x-2 bg-slate-50 border border-slate-200/60 p-3 rounded-xl">
                        <i class="fa-solid fa-check text-emerald-500 text-xs"></i>
                        <span>Contact Phone Number</span>
                    </li>
                    <li class="flex items-center space-x-2 bg-slate-50 border border-slate-200/60 p-3 rounded-xl">
                        <i class="fa-solid fa-check text-emerald-500 text-xs"></i>
                        <span>Service Usage & Playback Data</span>
                    </li>
                </ul>
            </div>

            <div class="policy-card bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-xs">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-gears"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">2. How We Use Your Information</h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                    Your personal information is processed strictly for legitimate service operation and user support:
                </p>
                <ul class="space-y-2 text-xs sm:text-sm text-slate-600">
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-angle-right text-indigo-500 text-xs mt-1"></i>
                        <span>To provide and maintain our manifestation and healing video streaming services.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-angle-right text-indigo-500 text-xs mt-1"></i>
                        <span>To manage user course access and program enrollments.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-angle-right text-indigo-500 text-xs mt-1"></i>
                        <span>To provide customer assistance and technical support.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-angle-right text-indigo-500 text-xs mt-1"></i>
                        <span>To detect, prevent, and address security incidents or server vulnerabilities.</span>
                    </li>
                </ul>
            </div>

            <div class="policy-card bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-xs">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">3. Data Security & Encryption</h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    We enforce strict technical and organizational measures to safeguard your personal data. All video playback streams, user credentials, and session data are protected with SSL/TLS encryption.
                </p>
            </div>

            <div class="policy-card bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-xs">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">4. Contact Us</h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                    If you have questions, feedback, or requests regarding this Privacy Policy, please reach out to our team:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="mailto:support@healingrenaissance.com" class="flex items-center space-x-3 p-4 rounded-xl bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 transition group">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Support Email</span>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">support@healingrenaissance.com</span>
                        </div>
                    </a>
                    <a href="tel:+917387997294" class="flex items-center space-x-3 p-4 rounded-xl bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 transition group">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Phone Contact</span>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">+91 7387997294</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    document.getElementById('searchPolicy').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.policy-card');

        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>

@include('CDN_Footer')