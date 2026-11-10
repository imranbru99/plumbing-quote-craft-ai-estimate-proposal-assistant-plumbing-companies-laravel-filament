@extends('layouts.marketing')
@section('title', 'Workflow — Intake to Approved Plumbing Proposal')
@section('content')
<section class="wrap section fade-up">
    <h1>Estimate & proposal workflow</h1>
    <p class="lead">Designed around the revenue-ops problem: draft itemized quotes from intake details for approval; operate on service requests, technicians, and job tickets.</p>
    <div class="steps" style="margin-top:2rem">
        <div class="step card"><div><h3>Intake trigger</h3><p class="muted">Phone, web, or partner lead becomes a Service Request with job type, fixture count, site address, and preferred date.</p></div></div>
        <div class="step card"><div><h3>AI draft action</h3><p class="muted">PlumbingQuoteCraft matches catalog keywords and builds line items, tax, totals, and a proposal narrative with confidence score.</p></div></div>
        <div class="step card"><div><h3>Human approval</h3><p class="muted">Owner/ops lead reviews, edits line items, and approves. Messaging consent and license checks stay visible.</p></div></div>
        <div class="step card"><div><h3>Job ticket + audit</h3><p class="muted">Approved quotes spawn field job tickets. Every draft/approve/send is recorded in the audit log.</p></div></div>
    </div>
</section>
@endsection
