<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LegalDocument;

class LegalDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LegalDocument::create([
            'type' => 'terms_and_conditions',
            'title' => 'Terms and Conditions',
            'content' => $this->getTermsAndConditionsContent(),
            'version' => '1.0',
            'is_active' => true,
            'effective_date' => now(),
        ]);

        LegalDocument::create([
            'type' => 'privacy_policy',
            'title' => 'Privacy Policy',
            'content' => $this->getPrivacyPolicyContent(),
            'version' => '1.0',
            'is_active' => true,
            'effective_date' => now(),
        ]);
    }

    private function getTermsAndConditionsContent(): string
    {
        return <<<'EOT'
# Terms and Conditions

**Last Updated:** [Current Date]

## 1. Acceptance of Terms

By accessing and using WingA Plus ("the Platform"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.

## 2. User Accounts

### 2.1 Account Creation
- Users must provide accurate and complete information during registration
- You are responsible for maintaining the confidentiality of your account credentials
- You must be at least 18 years old to create an account

### 2.2 Account Responsibilities
- You are responsible for all activities that occur under your account
- Notify us immediately of any unauthorized use of your account
- We reserve the right to suspend or terminate accounts that violate these terms

## 3. Seller Responsibilities

### 3.1 Product Listings
- Sellers must provide accurate product descriptions and pricing
- All products must comply with applicable laws and regulations
- Sellers are responsible for delivery and customer service

### 3.2 Business Information
- Sellers must maintain accurate business profiles
- Delivery and payment terms must be clearly communicated
- Sellers must honor their stated delivery and return policies

## 4. Buyer Responsibilities

### 4.1 Purchase Terms
- Buyers must provide accurate shipping and payment information
- All purchases are subject to product availability
- Buyers are responsible for understanding seller policies

### 4.2 Payment
- Payment must be completed through approved methods
- Refunds are processed according to seller policies
- Disputes must be resolved through our support channels

## 5. Prohibited Activities

The following activities are strictly prohibited:
- Fraudulent or illegal activities
- Posting false or misleading information
- Violating intellectual property rights
- Harassment or abusive behavior
- Circumventing platform fees or policies

## 6. Content and Intellectual Property

### 6.1 User Content
- You retain ownership of content you submit
- You grant us license to use, display, and distribute your content
- Content must not violate third-party rights

### 6.2 Platform Content
- Platform content is protected by copyright and trademark laws
- You may not reproduce or distribute platform content without permission

## 7. Privacy and Data Protection

Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the Platform, to understand our practices.

## 8. Disclaimers and Limitation of Liability

### 8.1 Service Disclaimers
- The Platform is provided "as is" without warranties
- We do not guarantee uninterrupted or error-free service
- We are not responsible for third-party content or actions

### 8.2 Liability Limitations
- Our liability is limited to the amount paid for services
- We are not liable for indirect or consequential damages
- Users assume all risks associated with platform use

## 9. Termination

We may terminate or suspend your account and access to the Platform immediately, without prior notice, for conduct that violates these Terms.

## 10. Governing Law

These Terms shall be governed by and construed in accordance with the laws of Kenya, without regard to its conflict of law provisions.

## 11. Changes to Terms

We reserve the right to modify these Terms at any time. We will notify users of material changes via email or platform notifications.

## 12. Contact Information

If you have any questions about these Terms, please contact us at:
- Email: support@wingaplus.com
- Phone: +254 XXX XXX XXX

By using WingA Plus, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions.
EOT;
    }

    private function getPrivacyPolicyContent(): string
    {
        return <<<'EOT'
# Privacy Policy

**Last Updated:** [Current Date]

## 1. Introduction

WingA Plus ("we," "us," or "our") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.

## 2. Information We Collect

### 2.1 Personal Information
We collect information you provide directly to us, including:
- Name, email address, phone number
- Shipping and billing addresses
- Payment information
- Business information (for sellers)

### 2.2 Usage Information
We automatically collect certain information when you use our platform:
- Device information and browser type
- IP address and location data
- Pages visited and time spent
- Purchase history and preferences

### 2.3 Cookies and Tracking Technologies
We use cookies and similar technologies to:
- Remember your preferences
- Analyze platform usage
- Provide personalized content
- Ensure platform security

## 3. How We Use Your Information

### 3.1 Providing Services
- Process transactions and orders
- Provide customer support
- Send order confirmations and updates
- Facilitate communication between buyers and sellers

### 3.2 Platform Improvement
- Analyze usage patterns and trends
- Improve platform functionality
- Develop new features and services
- Conduct research and analytics

### 3.3 Communication
- Send service-related notifications
- Provide marketing communications (with consent)
- Respond to inquiries and support requests
- Send platform updates and announcements

## 4. Information Sharing and Disclosure

### 4.1 With Sellers/Buyers
We share necessary information to facilitate transactions:
- Buyer shipping information with sellers
- Seller business information with buyers
- Order and transaction details

### 4.2 Service Providers
We share information with trusted third parties who assist us:
- Payment processors
- Shipping and logistics partners
- Customer support services
- Analytics and marketing platforms

### 4.3 Legal Requirements
We may disclose information when required by law or to:
- Protect our rights and safety
- Prevent fraud or illegal activities
- Comply with legal obligations
- Respond to government requests

## 5. Data Security

We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.

## 6. Data Retention

We retain your information for as long as necessary to:
- Provide our services
- Comply with legal obligations
- Resolve disputes
- Enforce our agreements

## 7. Your Rights

Depending on your location, you may have the following rights:
- Access to your personal information
- Correction of inaccurate data
- Deletion of your personal information
- Restriction of processing
- Data portability
- Objection to processing

## 8. International Data Transfers

Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place for such transfers.

## 9. Children's Privacy

Our platform is not intended for children under 18. We do not knowingly collect personal information from children under 18. If we become aware that we have collected such information, we will delete it immediately.

## 10. Third-Party Links and Services

Our platform may contain links to third-party websites or services. We are not responsible for the privacy practices of these third parties. We encourage you to review their privacy policies.

## 11. Changes to This Privacy Policy

We may update this Privacy Policy from time to time. We will notify you of any changes by:
- Posting the new Privacy Policy on our platform
- Sending you an email notification
- Providing an in-platform notification

## 12. Contact Us

If you have any questions about this Privacy Policy or our data practices, please contact us at:

- Email: privacy@wingaplus.com
- Phone: +254 XXX XXX XXX
- Address: [Company Address]

By using WingA Plus, you acknowledge that you have read and understood this Privacy Policy.
EOT;
    }
}
