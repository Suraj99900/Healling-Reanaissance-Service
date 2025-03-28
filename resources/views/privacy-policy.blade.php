
@include('CDN_Header')
@include('navbar')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

<div class="main-content">
    <div class="container">
        <h1 class="mt-5">Privacy Policy</h1>
        <p>Welcome to the Healing Renaissance Service app and website. Your privacy is important to us. This privacy policy explains how we collect, use, and protect your information.</p>

        <!-- Search Bar -->
        <div class="mb-4">
            <input type="text" id="searchPolicy" class="form-control" placeholder="Search Privacy Policy...">
        </div>

        <!-- Privacy Policy Content -->
        <div id="privacyPolicyContent">
            <h2>Information We Collect</h2>
            <p>We may collect the following personal information:</p>
            <ul>
                <li>Name</li>
                <li>Email address</li>
                <li>Phone number</li>
                <li>Usage data</li>
            </ul>

            <h2>How We Use Your Information</h2>
            <p>Your information may be used for the following purposes:</p>
            <ul>
                <li>To provide and maintain our services</li>
                <li>To notify you about changes to our services</li>
                <li>To provide customer support</li>
                <li>To improve our services through analysis</li>
                <li>To monitor service usage</li>
                <li>To detect and address technical issues</li>
            </ul>

            <h2>Data Security</h2>
            <p>We take the security of your personal information seriously and implement appropriate measures to protect it.</p>

            <h2>Changes to This Privacy Policy</h2>
            <p>We may update this privacy policy from time to time. Changes will be posted on this page.</p>

            <h2>Contact Us</h2>
            <p>If you have any questions about this privacy policy, please contact us:</p>
            <ul>
                <li>Email: support@healingrenaissance.com</li>
                <li>Phone: +91 7387997294</li>
            </ul>
        </div>
    </div>
</div>

<script>
    // Search functionality for the privacy policy
    document.getElementById('searchPolicy').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const content = document.getElementById('privacyPolicyContent');
        const sections = content.querySelectorAll('h2, p, ul');

        sections.forEach(section => {
            const text = section.textContent.toLowerCase();
            section.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>

@include('CDN_Footer')