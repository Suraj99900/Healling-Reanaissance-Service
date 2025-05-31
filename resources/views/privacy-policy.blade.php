{{-- resources/views/privacy-policy.blade.php --}}
@include('CDN_Header')
@include('navbar')

<style>
    /* Frosted‐glass backdrop blur */
    .backdrop-blur-md {
        backdrop-filter: blur(12px);
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-400 to-yellow-300 text-gray-800 py-10">
    <div class="container mx-auto px-4">

        {{-- Page Header --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-white drop-shadow-lg">Privacy Policy</h1>
            <p class="mt-2 text-white/90">Your privacy is important to us. Search the policy below.</p>
        </div>

        {{-- Search Bar --}}
        <div class="flex justify-center mb-8">
            <input type="text" id="searchPolicy"
                class="w-full max-w-2xl bg-white/80 backdrop-blur-md border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-400 shadow-lg"
                placeholder="Search Privacy Policy..." />
        </div>

        {{-- Policy Content --}}
        <div id="privacyPolicyContent"
            class="bg-white/60 backdrop-blur-md rounded-lg shadow-lg border border-white/20 p-8 space-y-6">
            <section class="space-y-4">
                <h2 class="text-2xl font-semibold text-gray-900">Information We Collect</h2>
                <p class="text-gray-800">We may collect the following personal information:</p>
                <ul class="list-disc list-inside text-gray-800 space-y-2">
                    <li>Name</li>
                    <li>Email address</li>
                    <li>Phone number</li>
                    <li>Usage data</li>
                </ul>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-semibold text-gray-900">How We Use Your Information</h2>
                <p class="text-gray-800">Your information may be used for the following purposes:</p>
                <ul class="list-disc list-inside text-gray-800 space-y-2">
                    <li>To provide and maintain our services</li>
                    <li>To notify you about changes to our services</li>
                    <li>To provide customer support</li>
                    <li>To improve our services through analysis</li>
                    <li>To monitor service usage</li>
                    <li>To detect and address technical issues</li>
                </ul>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-semibold text-gray-900">Data Security</h2>
                <p class="text-gray-800">
                    We take the security of your personal information seriously and implement appropriate measures to
                    protect it.
                </p>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-semibold text-gray-900">Changes to This Privacy Policy</h2>
                <p class="text-gray-800">
                    We may update this privacy policy from time to time. Changes will be posted on this page.
                </p>
            </section>

            <section class="space-y-4">
                <h2 class="text-2xl font-semibold text-gray-900">Contact Us</h2>
                <p class="text-gray-800">If you have any questions about this privacy policy, please contact us:</p>
                <ul class="list-none text-gray-800 space-y-2">
                    <li>Email: <a href="mailto:support@healingrenaissance.com"
                            class="text-purple-600 hover:underline">support@healingrenaissance.com</a></li>
                    <li>Phone: <a href="tel:+917387997294" class="text-purple-600 hover:underline">+91 7387997294</a>
                    </li>
                </ul>
            </section>
        </div>

    </div>
</div>

<script>
    // Search functionality for the privacy policy
    document.getElementById('searchPolicy').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const content = document.getElementById('privacyPolicyContent');
        const sections = content.querySelectorAll('h2, p, ul, li');

        sections.forEach(section => {
            const text = section.textContent.toLowerCase();
            section.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>

@include('CDN_Footer')