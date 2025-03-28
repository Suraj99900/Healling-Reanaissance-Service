<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function show()
    {
        // Retrieve the privacy policy content
        $privacyPolicyContent = [
            'title' => 'Privacy Policy',
            'content' => 'This privacy policy outlines the types of personal information that is received and collected by our app and website and how it is used. We are committed to safeguarding the privacy of our users. This policy applies to both our Android app and our website.',
            'details' => [
                'Information Collection' => 'We collect personal information when you register on our site, place an order, subscribe to our newsletter, or interact with our services.',
                'Usage of Information' => 'The information we collect may be used to personalize your experience, improve our website, process transactions, and send periodic emails.',
                'Data Protection' => 'We implement a variety of security measures to maintain the safety of your personal information when you place an order or enter, submit, or access your personal information.',
                'Cookies' => 'Our website may use "cookies" to enhance user experience. Users can choose to set their web browser to refuse cookies or to alert them when cookies are being sent.',
                'Third-Party Services' => 'We do not sell, trade, or otherwise transfer to outside parties your Personally Identifiable Information unless we provide users with advance notice.',
                'Changes to Our Privacy Policy' => 'We may update this privacy policy from time to time. We will notify you about significant changes in the way we treat personal information by sending a notice to the primary email address specified in your account or by placing a prominent notice on our site.'
            ]
        ];

        return view('privacy-policy', compact('privacyPolicyContent'));
    }
} 