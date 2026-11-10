@extends('layouts.marketing')
@section('title', 'Features — PlumbingQuoteCraft AI Quoting for Plumbing Companies')
@section('content')
<section class="wrap section fade-up">
    <h1>Everything in PlumbingQuoteCraft</h1>
    <p class="lead">Complete estimate & proposal assistant covering intake, AI drafting, approval, field tickets, compliance, and optional integrations.</p>
    <div class="grid-3" style="margin-top:2rem">
        @foreach($modules as $module)
            <div class="card">
                <span class="tag {{ $module['enabled'] ? 'on' : 'off' }}">{{ $module['enabled'] ? 'Enabled' : 'Optional' }}</span>
                @if(!empty($module['core']))<span class="tag">Core</span>@endif
                <h3 style="margin-top:.7rem">{{ $module['name'] }}</h3>
                <p class="muted">{{ $module['description'] }}</p>
            </div>
        @endforeach
    </div>
    <div class="kw">
        <span>plumbing estimate software</span><span>proposal automation</span><span>water heater quoting</span>
        <span>drain cleaning estimate</span><span>field service AI</span><span>Laravel Filament SaaS</span>
        <span>Jobber quoting</span><span>ServiceTitan alternative module</span><span>QuickBooks ready</span>
    </div>
</section>
@endsection
