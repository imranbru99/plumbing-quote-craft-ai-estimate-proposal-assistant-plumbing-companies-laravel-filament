<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PlumbingQuoteCraft — AI Estimate & Proposal Assistant for Plumbing Companies')</title>
    <meta name="description" content="@yield('meta_description', 'PlumbingQuoteCraft drafts itemized plumbing quotes and proposals from intake details with human approval, job tickets, and audit logs. Built for plumbing companies.')">
    <meta name="keywords" content="plumbing company software, AI estimate, proposal assistant, plumbing quotes, Jobber alternative, ServiceTitan quoting, Laravel Filament SaaS, water heater estimate, drain cleaning quote">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #0b1c2c;
            --forest: #0a3a5c;
            --leaf: #1a7fb5;
            --mint: #d6eef8;
            --sand: #eef4f8;
            --amber: #e8a317;
            --white: #ffffff;
            --muted: #5a6f7d;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "DM Sans", system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1200px 600px at 10% -10%, #b9e0f4 0%, transparent 55%),
                radial-gradient(900px 500px at 100% 0%, #ffe7b0 0%, transparent 45%),
                linear-gradient(180deg, #f4f9fc 0%, var(--sand) 100%);
            min-height: 100vh;
        }
        a { color: inherit; text-decoration: none; }
        .wrap { width: min(1120px, 92vw); margin: 0 auto; }
        header.site {
            position: sticky; top: 0; z-index: 20;
            backdrop-filter: blur(10px);
            background: rgba(244,249,252,.85);
            border-bottom: 1px solid rgba(10,58,92,.08);
        }
        .nav { display: flex; align-items: center; justify-content: space-between; padding: 1rem 0; gap: 1rem; }
        .brand { font-family: Syne, sans-serif; font-weight: 800; font-size: 1.2rem; color: var(--forest); letter-spacing: -.02em; }
        .brand span { color: var(--leaf); }
        .nav links, .nav-links { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; font-size: .95rem; }
        .nav-links a { color: var(--muted); font-weight: 500; }
        .nav-links a:hover { color: var(--forest); }
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: .75rem 1.2rem; border-radius: 999px; font-weight: 700;
            border: 1px solid transparent; transition: .2s ease;
        }
        .btn-primary { background: var(--forest); color: var(--white); }
        .btn-primary:hover { background: var(--leaf); transform: translateY(-1px); }
        .btn-ghost { border-color: rgba(10,58,92,.2); background: rgba(255,255,255,.6); }
        .hero {
            padding: 4.5rem 0 3rem;
            display: grid; gap: 2rem;
            grid-template-columns: 1.2fr .8fr;
            align-items: center;
        }
        @media (max-width: 860px) {
            .hero { grid-template-columns: 1fr; padding-top: 2.5rem; }
            .nav-links { display: none; }
        }
        h1, h2, h3 { font-family: Syne, sans-serif; letter-spacing: -.03em; line-height: 1.1; margin: 0 0 .75rem; }
        h1 { font-size: clamp(2.4rem, 5vw, 3.8rem); color: var(--forest); }
        h2 { font-size: clamp(1.7rem, 3vw, 2.3rem); color: var(--forest); }
        p.lead { font-size: 1.15rem; color: var(--muted); max-width: 40rem; }
        .cta-row { display: flex; gap: .75rem; flex-wrap: wrap; margin-top: 1.5rem; }
        .panel {
            background: linear-gradient(160deg, #0a3a5c, #146994 55%, #1a7fb5);
            color: white; border-radius: 24px; padding: 1.5rem;
            box-shadow: 0 30px 60px rgba(10,58,92,.25);
            min-height: 320px;
            position: relative; overflow: hidden;
        }
        .panel::after {
            content: ""; position: absolute; inset: auto -20% -30% 20%;
            height: 220px; background: radial-gradient(circle, rgba(232,163,23,.45), transparent 60%);
        }
        .panel h3 { color: white; }
        .chip {
            display: inline-flex; padding: .35rem .7rem; border-radius: 999px;
            background: rgba(255,255,255,.12); font-size: .8rem; margin: .2rem;
        }
        .section { padding: 3.5rem 0; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        @media (max-width: 860px) { .grid-3, .grid-2 { grid-template-columns: 1fr; } }
        .card {
            background: rgba(255,255,255,.78);
            border: 1px solid rgba(10,58,92,.08);
            border-radius: 18px; padding: 1.25rem;
        }
        .card h3 { font-size: 1.15rem; }
        .muted { color: var(--muted); }
        .tag { display:inline-block; background: var(--mint); color: var(--forest); padding:.2rem .55rem; border-radius:6px; font-size:.75rem; font-weight:700; }
        .on { background: #d6eef8; color: #0a3a5c; }
        .off { background: #eee; color: #777; }
        footer.site { border-top: 1px solid rgba(10,58,92,.08); padding: 2rem 0 3rem; margin-top: 2rem; color: var(--muted); }
        .stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: .75rem; margin-top: 2rem; }
        @media (max-width: 860px) { .stat-row { grid-template-columns: repeat(2, 1fr); } }
        .stat { background: white; border-radius: 14px; padding: 1rem; border: 1px solid rgba(10,58,92,.08); }
        .stat b { display:block; font-size: 1.5rem; color: var(--forest); font-family: Syne, sans-serif; }
        .steps { counter-reset: step; display:grid; gap:1rem; }
        .step { display:flex; gap:1rem; align-items:flex-start; }
        .step::before {
            counter-increment: step; content: counter(step);
            width: 2rem; height: 2rem; border-radius: 999px; background: var(--forest); color:white;
            display:flex; align-items:center; justify-content:center; font-weight:700; flex:none;
        }
        .kw { display:flex; flex-wrap:wrap; gap:.4rem; margin-top:1rem; }
        .kw span { background: white; border:1px solid rgba(10,58,92,.1); border-radius:999px; padding:.25rem .6rem; font-size:.78rem; color:var(--muted); }
        .table { width:100%; border-collapse: collapse; }
        .table th, .table td { text-align:left; padding:.75rem; border-bottom:1px solid rgba(10,58,92,.08); }
        .demo-box { background: white; border-radius: 16px; padding: 1rem 1.25rem; border: 1px solid rgba(10,58,92,.1); }
        .fade-up { animation: fadeUp .7s ease both; }
        @keyframes fadeUp { from { opacity:0; transform: translateY(12px);} to { opacity:1; transform:none;} }
        .floaty { animation: floaty 5s ease-in-out infinite; }
        @keyframes floaty { 0%,100%{ transform: translateY(0);} 50%{ transform: translateY(-8px);} }
    </style>
</head>
<body>
<header class="site">
    <div class="wrap nav">
        <a class="brand" href="{{ route('home') }}">Plumbing<span>Quote</span>Craft</a>
        <nav class="nav-links">
            <a href="{{ route('features') }}">Features</a>
            <a href="{{ route('modules') }}">Modules</a>
            <a href="{{ route('workflow') }}">Workflow</a>
            <a href="{{ route('pricing') }}">Pricing</a>
            <a href="{{ route('demo') }}">Demo</a>
            <a class="btn btn-primary" href="/admin">Admin Login</a>
        </nav>
    </div>
</header>
<main>@yield('content')</main>
<footer class="site">
    <div class="wrap">
        <p><strong>PlumbingQuoteCraft</strong> — AI Estimate & Proposal Assistant for Plumbing Companies.</p>
        <p>Built with Laravel + Filament. Modular SaaS for Home & Field Services revenue operations.</p>
        <p>Contact: <a href="https://imrandev.bd/contact">https://imrandev.bd/contact</a> · Imran Ahmed · me@imrandev.bd</p>
    </div>
</footer>
</body>
</html>
