# PlumbingQuoteCraft — AI Estimate & Proposal Assistant for Plumbing Companies

**PlumbingQuoteCraft** is a modular Laravel + Filament SaaS that helps plumbing company owners and operations leads **draft itemized quotes and proposals from intake details**, route **human approval**, open **field job tickets**, and keep a complete **audit log**.

> Rank #16 opportunity · Business category: Plumbing Companies · Sector: Home & Field Services · Workflow: Estimate & Proposal Assistant · Function: Revenue operations

## SEO Keywords

plumbing company software, AI estimate software, plumbing proposal assistant, itemized plumbing quotes, water heater estimate, drain cleaning quote, leak repair proposal, tankless install estimate, field service quoting, service request intake, job ticket software, technician dispatch, proposal approval workflow, audit log compliance, messaging consent plumbing, plumber license tracking, Jobber integration, ServiceTitan quoting, QuickBooks plumbing invoice handoff, Google Calendar site visits, Laravel Filament SaaS, modular feature toggles, AI business automation, home & field services revenue operations, plumbing company CRM ops, residential commercial plumbing estimating, price catalog labor materials, Unicode PDF proposals, multi-role contractor admin, sewer line quote, fixture install estimate, PEX copper pipe repair

## Problem solved

For plumbing companies: draft itemized quotes or proposals from intake details for approval; operate on service requests, field technicians and job tickets.

**MVP flow:** 1 intake trigger → AI draft/action → human approval → audit log

## Features & modules

All modules are toggleable (Admin → **Feature Modules** or `config/modules.php` / env flags). Core workflow modules ship ON.

| Module | Default | Purpose |
|--------|---------|---------|
| Quote Intake | ON | Capture service requests that feed quoting |
| AI Quote Drafting | ON | Draft itemized estimates from intake + catalog |
| Proposal Approval | ON | Human-in-the-loop approval gate |
| Audit Log | ON | Immutable activity trail |
| Service Requests | ON | Residential/commercial request tracking |
| Job Tickets | ON | Convert approved quotes to field work |
| Field Technicians | ON | Profiles, licenses, availability |
| Price Catalog | ON | Materials/labor price book for AI matching |
| PDF Proposals | ON | Branded HTML/Unicode proposal output |
| Messaging Consent | ON | SMS/email consent records |
| Licensing & Safety | ON | License expiry + safety flags |
| Customer Portal | OFF | Optional customer-facing proposal view |
| Integrations Hub | OFF | Jobber / ServiceTitan / QuickBooks / Google Calendar |
| Calendar Scheduling | OFF | Site-visit scheduling hooks |
| Attendance Hub | OFF | Crew time clock (attendance machine ready) |
| Follow-Up Autopilot | OFF | Pending proposal reminder sequences |
| AI Catalog Enrichment | OFF | Online catalog enrichment hooks |
| Universal Slug Engine | ON | Multi-language proposal slugs |

### Package notes

Packagist packages `imrandevbd/drutogoti`, `laravel-admin-panel`, `laravel-ai-catalog`, `laravel-ai-hub`, `laravel-attendance-hub`, `laravel-unicode-pdf`, and `laravel-universal-slug` were not installable as stable public releases at build time. Equivalent capabilities are implemented in-app (`AiQuoteDrafter`, `ProposalPdfGenerator`, `SlugService`, attendance records, Filament admin, module toggles).

## Multi-type users

| Role | Demo email | Password | Access |
|------|------------|----------|--------|
| Admin | `admin@plumbingquotecraft.test` | `password` | Full admin + modules |
| Owner | `owner@plumbingquotecraft.test` | `password` | Approvals & revenue ops |
| Staff | `staff@plumbingquotecraft.test` | `password` | Intake & AI drafting |
| Technician | `tech@plumbingquotecraft.test` | `password` | Field tickets / profile |
| Customer | `customer@plumbingquotecraft.test` | `password` | No Filament admin (portal demo) |

## Local run with Docker

```bash
cd pipeline/apps/PlumbingQuoteCraft
docker compose up -d --build
```

- App: http://localhost:8116
- Admin: http://localhost:8116/admin
- MySQL host port: `33076`
- Redis host port: `63806`

First boot runs migrations + demo seed automatically. If needed inside the container:

```bash
docker exec plumbingquotecraft_app php artisan migrate --force
docker exec plumbingquotecraft_app php artisan db:seed --force
```

## Public pages

- `/` — Marketing home with live stats
- `/features` — Every feature & module
- `/modules` — ON/OFF module matrix
- `/workflow` — Intake → draft → approve → ticket
- `/pricing` — Pilot $299–$1,499/mo
- `/demo` — Demo credentials
- `/proposals/{slug}` — HTML proposal preview
- `/admin` — Filament operations console

## Tech stack

- PHP 8.4 · Laravel 13 · Filament 4 · Livewire
- MySQL 8.4 · Redis 7
- Docker Compose (`ext-intl` installed for Filament)
- Modular feature flags · AI drafting service · Audit logging

## Screenshots

See `docs/screenshots/` for home, features, admin dashboard, and quote workflow captures.

## Integrations (hooks)

Jobber · ServiceTitan · QuickBooks · Google Calendar — toggle via Integrations Hub module.

## Risk / compliance

MED — messaging consent, licensing and safety. Consent records and technician license fields are included.

## Contact / Hire

- Developer: Imran Ahmed (IMRAN Dev BD)
- Website: https://imrandev.bd/contact
- Email: me@imrandev.bd
- GitHub: https://github.com/imranbru99
