<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Properindo Enviro Tech — Sistem Internal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #000000;
            --bg-grid: #141b16;
            --surface: #2b2b2b;
            --line: #2a332c;
            --text: #eceeea;
            --text-muted: #93a196;
            --accent: #d6a83c;
            --accent-soft: rgba(214, 168, 60, .14);
            --signal: #6e9c7d;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            background: var(--bg);
            color: var(--text);
            font-family: 'IBM Plex Sans', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        body {
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            background-image:
                linear-gradient(var(--bg-grid) 1px, transparent 1px),
                linear-gradient(90deg, var(--bg-grid) 1px, transparent 1px);
            background-size: 56px 56px;
            background-position: center;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse 60% 50% at 50% 30%, rgba(0, 0, 0, 0.06), transparent 70%);
            pointer-events: none;
        }

        .frame {
            position: fixed;
            inset: 24px;
            pointer-events: none;
            z-index: 5;
        }

        .tick {
            position: absolute;
            width: 18px;
            height: 18px;
            border: 1px solid var(--line);
        }
        .tick.tl { top: 0; left: 0; border-right: 0; border-bottom: 0; }
        .tick.tr { top: 0; right: 0; border-left: 0; border-bottom: 0; }
        .tick.bl { bottom: 0; left: 0; border-right: 0; border-top: 0; }
        .tick.br { bottom: 0; right: 0; border-left: 0; border-top: 0; }

        main {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
            text-align: center;
        }

        .eyebrow {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 7px 14px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: var(--surface);
            margin-bottom: 40px;
        }

        .eyebrow .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--signal);
            box-shadow: 0 0 0 0 rgba(110,156,125,.5);
            animation: pulse 2.4s ease-out infinite;
        }

        @keyframes pulse {
            0%   { box-shadow: 0 0 0 0 rgba(110,156,125,.45); }
            70%  { box-shadow: 0 0 0 8px rgba(110,156,125,0); }
            100% { box-shadow: 0 0 0 0 rgba(110,156,125,0); }
        }

        h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: clamp(2.4rem, 5.5vw, 4.4rem);
            line-height: 1.05;
            letter-spacing: -.01em;
            max-width: 16ch;
            margin: 0 0 26px;
        }

        h1 em {
            font-style: normal;
            color: var(--accent);
        }

        p.lede {
            font-size: clamp(1rem, 1.6vw, 1.15rem);
            line-height: 1.6;
            color: var(--text-muted);
            max-width: 46ch;
            margin: 0 0 40px;
        }

        .cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: .02em;
            color: #14180f;
            background: var(--accent);
            padding: 15px 28px;
            border-radius: 6px;
            text-decoration: none;
            transition: transform .18s ease, box-shadow .18s ease;
            box-shadow: 0 0 0 1px rgba(214,168,60,.35);
        }

        .cta:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(214,168,60,.25); }
        .cta:focus-visible { outline: 2px solid var(--accent); outline-offset: 3px; }
        .cta svg { transition: transform .18s ease; }
        .cta:hover svg { transform: translateX(3px); }

        .readout {
            margin-top: 72px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 28px;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            color: var(--text-muted);
            letter-spacing: .03em;
        }

        .readout span b {
            color: var(--text);
            font-weight: 500;
        }

        .readout .sep { color: var(--line); }

        footer {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 20px 24px 32px;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: .03em;
        }

        @media (prefers-reduced-motion: reduce) {
            .eyebrow .dot { animation: none; }
        }

        @media (max-width: 520px) {
            .frame { inset: 12px; }
            .readout { gap: 16px 22px; }
        }
    </style>
</head>
<body>

    <div class="frame">
        <span class="tick tl"></span>
        <span class="tick tr"></span>
        <span class="tick bl"></span>
        <span class="tick br"></span>
    </div>

    <main>
        <span class="eyebrow"><span></span> PT Properindo Enviro Tech</span>

        <h1>Sistem Informasi Data Karyawan & <em>Sistem Monitoring Pekerjaan Internal</em></h1>

        <a href="/admin" class="cta">
            Masuk ke Sistem
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.5 7H11.5M11.5 7L7.5 3M11.5 7L7.5 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </main>


</body>
</html>
