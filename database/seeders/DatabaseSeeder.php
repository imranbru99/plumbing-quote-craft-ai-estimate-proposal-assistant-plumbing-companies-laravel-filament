<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\AuditLog;
use App\Models\CatalogItem;
use App\Models\ConsentRecord;
use App\Models\Customer;
use App\Models\IntegrationSetting;
use App\Models\JobTicket;
use App\Models\ModuleSetting;
use App\Models\ServiceRequest;
use App\Models\Technician;
use App\Models\User;
use App\Services\AiQuoteDrafter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@plumbingquotecraft.test'],
            ['name' => 'Alex Admin', 'password' => Hash::make('password'), 'role' => User::ROLE_ADMIN, 'phone' => '555-0100', 'is_active' => true]
        );
        $owner = User::query()->updateOrCreate(
            ['email' => 'owner@plumbingquotecraft.test'],
            ['name' => 'Olivia Owner', 'password' => Hash::make('password'), 'role' => User::ROLE_OWNER, 'phone' => '555-0101', 'is_active' => true]
        );
        $staff = User::query()->updateOrCreate(
            ['email' => 'staff@plumbingquotecraft.test'],
            ['name' => 'Sam Staff', 'password' => Hash::make('password'), 'role' => User::ROLE_STAFF, 'phone' => '555-0102', 'is_active' => true]
        );
        $techUser = User::query()->updateOrCreate(
            ['email' => 'tech@plumbingquotecraft.test'],
            ['name' => 'Taylor Technician', 'password' => Hash::make('password'), 'role' => User::ROLE_TECHNICIAN, 'phone' => '555-0103', 'is_active' => true]
        );
        User::query()->updateOrCreate(
            ['email' => 'customer@plumbingquotecraft.test'],
            ['name' => 'Casey Customer', 'password' => Hash::make('password'), 'role' => User::ROLE_CUSTOMER, 'phone' => '555-0199', 'is_active' => true]
        );

        foreach (config('modules', []) as $key => $meta) {
            ModuleSetting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'name' => $meta['name'],
                    'description' => $meta['description'] ?? null,
                    'enabled' => (bool) ($meta['enabled'] ?? false),
                    'is_core' => (bool) ($meta['core'] ?? false),
                ]
            );
        }

        $tech = Technician::query()->updateOrCreate(
            ['user_id' => $techUser->id],
            [
                'employee_code' => 'TECH-16',
                'license_number' => 'PL-55210',
                'license_expires_on' => now()->addYear()->toDateString(),
                'specialty' => 'Water heaters, drain cleaning & leak repair',
                'is_available' => true,
                'safety_certified' => true,
                'notes' => 'Licensed master plumber',
            ]
        );

        $catalog = [
            ['sku' => 'LAB-MP', 'name' => 'Master plumber labor', 'category' => 'labor', 'unit' => 'hr', 'unit_price' => 125, 'keywords' => 'labor,install,repair,leak,troubleshoot'],
            ['sku' => 'LAB-JP', 'name' => 'Journeyman plumber labor', 'category' => 'labor', 'unit' => 'hr', 'unit_price' => 95, 'keywords' => 'labor,helper,install'],
            ['sku' => 'WH-50G', 'name' => '50-gallon water heater replacement', 'category' => 'materials', 'unit' => 'ea', 'unit_price' => 1450, 'keywords' => 'water heater,tank,50 gallon,replacement'],
            ['sku' => 'WH-TANKLESS', 'name' => 'Tankless water heater install', 'category' => 'materials', 'unit' => 'ea', 'unit_price' => 2850, 'keywords' => 'tankless,water heater,on-demand'],
            ['sku' => 'DRAIN-MAIN', 'name' => 'Main line drain cleaning', 'category' => 'materials', 'unit' => 'ea', 'unit_price' => 320, 'keywords' => 'drain,clog,sewer,snake,hydro jet'],
            ['sku' => 'LEAK-PIPE', 'name' => 'Copper/PEX pipe leak repair', 'category' => 'materials', 'unit' => 'ea', 'unit_price' => 275, 'keywords' => 'leak,pipe,repair,pex,copper'],
            ['sku' => 'TOILET', 'name' => 'Toilet replacement install', 'category' => 'materials', 'unit' => 'ea', 'unit_price' => 425, 'keywords' => 'toilet,fixture,bath'],
            ['sku' => 'FAUCET', 'name' => 'Kitchen/bath faucet install', 'category' => 'materials', 'unit' => 'ea', 'unit_price' => 185, 'keywords' => 'faucet,fixture,kitchen,bath'],
            ['sku' => 'PERM', 'name' => 'Plumbing permit filing', 'category' => 'fees', 'unit' => 'ea', 'unit_price' => 165, 'keywords' => 'permit,inspection'],
            ['sku' => 'TRIP', 'name' => 'Service call / diagnostic visit', 'category' => 'fees', 'unit' => 'visit', 'unit_price' => 119, 'keywords' => 'service,troubleshoot,diagnostic,leak'],
        ];

        foreach ($catalog as $row) {
            CatalogItem::query()->updateOrCreate(['sku' => $row['sku']], array_merge($row, ['is_active' => true]));
        }

        $customers = [
            [
                'name' => 'Jordan Rivera', 'email' => 'jordan@homesample.test', 'phone' => '555-2001',
                'address' => '418 Maple Ave', 'city' => 'Austin', 'state' => 'TX', 'zip' => '78702',
                'customer_type' => 'residential', 'messaging_consent' => true,
            ],
            [
                'name' => 'BrightSide Bakery', 'email' => 'ops@brightsidebakery.test', 'phone' => '555-2002', 'company' => 'BrightSide Bakery LLC',
                'address' => '90 Commerce Blvd', 'city' => 'Round Rock', 'state' => 'TX', 'zip' => '78664',
                'customer_type' => 'commercial', 'messaging_consent' => true,
            ],
            [
                'name' => 'Morgan Lee', 'email' => 'morgan@homesample.test', 'phone' => '555-2003',
                'address' => '12 Cedar Trail', 'city' => 'Cedar Park', 'state' => 'TX', 'zip' => '78613',
                'customer_type' => 'residential', 'messaging_consent' => false,
            ],
        ];

        $customerModels = [];
        foreach ($customers as $c) {
            $customerModels[] = Customer::query()->updateOrCreate(['email' => $c['email']], $c);
        }

        foreach ($customerModels as $c) {
            if ($c->messaging_consent) {
                ConsentRecord::query()->updateOrCreate(
                    ['customer_id' => $c->id, 'channel' => 'sms'],
                    ['consented' => true, 'source' => 'web_form', 'captured_at' => now()->subDays(3)]
                );
            }
        }

        $sr1 = ServiceRequest::query()->updateOrCreate(
            ['request_number' => 'SR-DEMO-001'],
            [
                'customer_id' => $customerModels[0]->id,
                'assigned_technician_id' => $tech->id,
                'intake_by' => $staff->id,
                'title' => '50-gallon water heater replacement + permit',
                'description' => 'Homeowner needs a 50-gallon water heater replacement. Include permit filing and haul-away of old unit.',
                'job_type' => 'water_heater',
                'priority' => 'high',
                'status' => 'intake',
                'site_address' => '418 Maple Ave, Austin TX',
                'preferred_date' => now()->addDays(5)->toDateString(),
                'estimated_hours' => 6,
                'outlet_count' => 1,
                'panel_amps' => null,
                'source' => 'web',
                'raw_intake' => ['channel' => 'website', 'notes' => 'Prefer same-day if possible'],
            ]
        );

        $sr2 = ServiceRequest::query()->updateOrCreate(
            ['request_number' => 'SR-DEMO-002'],
            [
                'customer_id' => $customerModels[1]->id,
                'assigned_technician_id' => $tech->id,
                'intake_by' => $staff->id,
                'title' => 'Bakery kitchen faucet + drain cleaning',
                'description' => 'Commercial kitchen needs faucet install and main line drain cleaning before weekend rush.',
                'job_type' => 'drain_cleaning',
                'priority' => 'normal',
                'status' => 'intake',
                'site_address' => '90 Commerce Blvd, Round Rock TX',
                'preferred_date' => now()->addDays(8)->toDateString(),
                'estimated_hours' => 5,
                'outlet_count' => 2,
                'source' => 'phone',
            ]
        );

        ServiceRequest::query()->updateOrCreate(
            ['request_number' => 'SR-DEMO-003'],
            [
                'customer_id' => $customerModels[2]->id,
                'intake_by' => $staff->id,
                'title' => 'Under-sink copper pipe leak repair',
                'description' => 'Service call to repair intermittent leak under kitchen sink; possible PEX or copper patch.',
                'job_type' => 'leak_repair',
                'priority' => 'normal',
                'status' => 'intake',
                'site_address' => '12 Cedar Trail, Cedar Park TX',
                'preferred_date' => now()->addDays(2)->toDateString(),
                'estimated_hours' => 2,
                'source' => 'phone',
            ]
        );

        $drafter = app(AiQuoteDrafter::class);
        $quote1 = $drafter->draftFromRequest($sr1->fresh(['customer']), $staff->id);
        $quote1->update(['status' => 'pending_approval']);
        AuditLog::record('quote.submitted_approval', \App\Models\Quote::class, $quote1->id, $staff->id);

        $quote2 = $drafter->draftFromRequest($sr2->fresh(['customer']), $staff->id);
        $quote2->approve($owner);

        JobTicket::query()->updateOrCreate(
            ['ticket_number' => 'JT-DEMO-001'],
            [
                'service_request_id' => $sr2->id,
                'quote_id' => $quote2->id,
                'technician_id' => $tech->id,
                'title' => 'Faucet + drain clean - BrightSide Bakery',
                'status' => 'scheduled',
                'scheduled_for' => now()->addDays(4),
                'notes' => 'Created from approved demo quote',
            ]
        );

        AttendanceRecord::query()->updateOrCreate(
            ['technician_id' => $tech->id, 'clock_in' => now()->startOfDay()->addHours(7)],
            ['clock_out' => now()->startOfDay()->addHours(16), 'source' => 'manual', 'notes' => 'Demo attendance']
        );

        foreach ([
            ['provider' => 'jobber', 'label' => 'Jobber', 'notes' => 'Push approved jobs'],
            ['provider' => 'servicetitan', 'label' => 'ServiceTitan', 'notes' => 'CRM / job sync'],
            ['provider' => 'quickbooks', 'label' => 'QuickBooks', 'notes' => 'Invoice after acceptance'],
            ['provider' => 'google_calendar', 'label' => 'Google Calendar', 'notes' => 'Site visit scheduling'],
        ] as $integration) {
            IntegrationSetting::query()->updateOrCreate(
                ['provider' => $integration['provider']],
                ['label' => $integration['label'], 'is_enabled' => false, 'notes' => $integration['notes']]
            );
        }

        AuditLog::record('demo.seeded', null, null, $admin->id, ['app' => 'PlumbingQuoteCraft']);
    }
}
