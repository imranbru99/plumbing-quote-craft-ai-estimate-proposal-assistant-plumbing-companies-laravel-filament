@extends('layouts.marketing')
@section('title', 'Toggleable Modules — PlumbingQuoteCraft')
@section('content')
<section class="wrap section fade-up">
    <h1>Run only the modules you need</h1>
    <p class="lead">Every contractor business is different. Flip modules ON/OFF from Admin → Feature Modules without rewriting the app.</p>
    <div class="card" style="margin-top:1.5rem; overflow:auto">
        <table class="table">
            <thead><tr><th>Module</th><th>Key</th><th>Core</th><th>Status</th><th>Description</th></tr></thead>
            <tbody>
            @foreach($modules as $module)
                <tr>
                    <td><strong>{{ $module['name'] }}</strong></td>
                    <td><code>{{ $module['key'] }}</code></td>
                    <td>{{ !empty($module['core']) ? 'Yes' : 'No' }}</td>
                    <td><span class="tag {{ $module['enabled'] ? 'on' : 'off' }}">{{ $module['enabled'] ? 'ON' : 'OFF' }}</span></td>
                    <td class="muted">{{ $module['description'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection