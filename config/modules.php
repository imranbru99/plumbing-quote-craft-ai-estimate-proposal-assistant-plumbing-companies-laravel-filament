<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature modules — toggle ON/OFF per plumbing company needs
    |--------------------------------------------------------------------------
    */
    'quote_intake' => [
        'enabled' => env('MODULE_QUOTE_INTAKE', true),
        'name' => 'Quote Intake',
        'description' => 'Capture plumbing service requests and job intake details that feed AI quote drafting.',
        'core' => true,
    ],
    'ai_quote_drafting' => [
        'enabled' => env('MODULE_AI_QUOTE_DRAFTING', true),
        'name' => 'AI Quote Drafting',
        'description' => 'Draft itemized plumbing estimates and proposals from intake details using AI.',
        'core' => true,
    ],
    'proposal_approval' => [
        'enabled' => env('MODULE_PROPOSAL_APPROVAL', true),
        'name' => 'Proposal Approval',
        'description' => 'Human-in-the-loop approval workflow before quotes go to customers.',
        'core' => true,
    ],
    'audit_log' => [
        'enabled' => env('MODULE_AUDIT_LOG', true),
        'name' => 'Audit Log',
        'description' => 'Immutable activity trail for intake, drafts, approvals and sends.',
        'core' => true,
    ],
    'service_requests' => [
        'enabled' => env('MODULE_SERVICE_REQUESTS', true),
        'name' => 'Service Requests',
        'description' => 'Track residential and commercial plumbing service requests.',
        'core' => true,
    ],
    'job_tickets' => [
        'enabled' => env('MODULE_JOB_TICKETS', true),
        'name' => 'Job Tickets',
        'description' => 'Convert approved proposals into field job tickets.',
        'core' => true,
    ],
    'field_technicians' => [
        'enabled' => env('MODULE_FIELD_TECHNICIANS', true),
        'name' => 'Field Technicians',
        'description' => 'Manage plumber profiles, licenses and assignments.',
        'core' => true,
    ],
    'price_catalog' => [
        'enabled' => env('MODULE_PRICE_CATALOG', true),
        'name' => 'Price Catalog',
        'description' => 'Plumbing materials and labor price book for itemized quotes.',
        'core' => false,
    ],
    'customer_portal' => [
        'enabled' => env('MODULE_CUSTOMER_PORTAL', false),
        'name' => 'Customer Portal',
        'description' => 'Let customers view proposals and accept quotes online.',
        'core' => false,
    ],
    'integrations_hub' => [
        'enabled' => env('MODULE_INTEGRATIONS_HUB', false),
        'name' => 'Integrations Hub',
        'description' => 'Connect Jobber, ServiceTitan, QuickBooks and Google Calendar.',
        'core' => false,
    ],
    'pdf_proposals' => [
        'enabled' => env('MODULE_PDF_PROPOSALS', true),
        'name' => 'PDF Proposals',
        'description' => 'Generate branded Unicode-ready PDF proposals for any language.',
        'core' => false,
    ],
    'messaging_consent' => [
        'enabled' => env('MODULE_MESSAGING_CONSENT', true),
        'name' => 'Messaging Consent',
        'description' => 'Track SMS/email consent for compliance before outreach.',
        'core' => false,
    ],
    'licensing_safety' => [
        'enabled' => env('MODULE_LICENSING_SAFETY', true),
        'name' => 'Licensing & Safety',
        'description' => 'Track plumber licenses, permits and safety checklists.',
        'core' => false,
    ],
    'calendar_scheduling' => [
        'enabled' => env('MODULE_CALENDAR_SCHEDULING', false),
        'name' => 'Calendar Scheduling',
        'description' => 'Schedule site visits and proposal walkthroughs.',
        'core' => false,
    ],
    'attendance_hub' => [
        'enabled' => env('MODULE_ATTENDANCE_HUB', false),
        'name' => 'Attendance Hub',
        'description' => 'Optional field attendance / time clock integration for crews.',
        'core' => false,
    ],
    'follow_up_autopilot' => [
        'enabled' => env('MODULE_FOLLOW_UP_AUTOPILOT', false),
        'name' => 'Follow-Up Autopilot',
        'description' => 'Automated reminder sequences for pending proposals.',
        'core' => false,
    ],
    'ai_catalog_scrape' => [
        'enabled' => env('MODULE_AI_CATALOG_SCRAPE', false),
        'name' => 'AI Catalog Enrichment',
        'description' => 'Enrich price catalog from online plumbing supply data.',
        'core' => false,
    ],
    'slug_engine' => [
        'enabled' => env('MODULE_SLUG_ENGINE', true),
        'name' => 'Universal Slug Engine',
        'description' => 'SEO-friendly multi-language slugs for proposals and pages.',
        'core' => false,
    ],
];
