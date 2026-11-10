@extends('layouts.marketing')

@section('title', 'PlumbingQuoteCraft — AI Estimate & Proposal Assistant for Plumbing Companies')
@section('meta_description', 'Draft itemized plumbing quotes from intake details, approve proposals, create job tickets, and keep an audit trail. Modular Laravel Filament SaaS for plumbing companies.')

@section('content')
<section class="wrap hero fade-up">
    <div>
        <div class="tag">Rank #16 · Plumbing Companies · Revenue Ops</div>
        <h1>PlumbingQuoteCraft</h1>
        <p class="lead">AI estimate & proposal assistant that turns service intakes into itemized plumbing quotes, routes human approval, and opens field job tickets — with a full audit log.</p>
        <div class="cta-row">
            <a class="btn btn-primary" href="/admin">Open Admin Panel</a>
            <a class="btn btn-ghost" href="{{ route('features') }}">Explore Features</a>
        </div>
        <div class="stat-row">
            <div class="stat"><b>{{ $stats['intakes'] }}</b><span class="muted">Service intakes</span></div>
            <div class="stat"><b>{{ $stats['quotes'] }}</b><span class="muted">Quotes drafted</span></div>
            <div class="stat"><b>{{ $stats['catalog'] }}</b><span class="muted">Catalog items</span></div>
            <div class="stat"><b>{{ $stats['modules_on'] }}</b><span class="muted">Modules ON</span></div>
        </div>
    </div>
    <div class="panel floaty">
        <h3>MVP workflow</h3>
        <p>1 intake trigger → AI draft → human approval → audit log</p>
        <div style="margin-top:1rem">
            <span class="chip">Service Requests</span>
            <span class="chip">AI Quote Drafting</span>
            <span class="chip">Proposal Approval</span>
            <span class="chip">Job Tickets</span>
            <span class="chip">Technicians</span>
            <span class="chip">Compliance</span>
        </div>
        <p style="margin-top:1.5rem;opacity:.9">Integrations ready: Jobber · ServiceTitan · QuickBooks · Google Calendar</p>
    </div>
</section>

<section class="wrap section">
    <h2>Built for plumbing company owners</h2>
    <p class="muted">Stop rewriting the same water-heater and drain-cleaning proposals. Capture intake once, draft with AI, approve, and dispatch.</p>
    <div class="grid-3" style="margin-top:1.5rem">
        <div class="card">
            <h3>Itemized AI quotes</h3>
            <p class="muted">Match intake keywords to your price catalog for labor, materials, diagnostics, and trip fees.</p>
        </div>
        <div class="card">
            <h3>Human approval gate</h3>
            <p class="muted">Owners and ops leads approve before anything reaches the customer — MED compliance aware.</p>
        </div>
        <div class="card">
            <h3>Modular by design</h3>
            <p class="muted">Enable or disable modules for catalog, consent, attendance, integrations, and follow-ups.</p>
        </div>
    </div>
</section>

<section class="wrap section">
    <h2>Feature modules at a glance</h2>
    <div class="grid-2" style="margin-top:1rem">
        @foreach(collect($modules)->take(8) as $module)
            <div class="card">
                <span class="tag {{ $module['enabled'] ? 'on' : 'off' }}">{{ $module['enabled'] ? 'ON' : 'OFF' }}</span>
                <h3 style="margin-top:.6rem">{{ $module['name'] }}</h3>
                <p class="muted">{{ $module['description'] }}</p>
            </div>
        @endforeach
    </div>
    <div class="cta-row"><a class="btn btn-ghost" href="{{ route('modules') }}">View all modules</a></div>
</section>
@endsection
