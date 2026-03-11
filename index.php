<?php
session_start();

// If already logged in, send straight to profile
if(isset($_SESSION['user_id'])){
    header("Location: profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YourSpace — Tap to Connect</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue-deep:    #0a1628;
            --blue-dark:    #0d1f3c;
            --blue-mid:     #1a3a6e;
            --blue-core:    #1e50a2;
            --blue-bright:  #2d6cdf;
            --blue-vivid:   #3b82f6;
            --blue-glow:    #60a5fa;
            --blue-ice:     #93c5fd;
            --blue-frost:   #dbeafe;
            --accent-cyan:  #06b6d4;
            --accent-indigo:#6366f1;
            --white:        #ffffff;
            --glass-border: rgba(255,255,255,0.10);
            --text-primary: #e8f0fe;
            --text-muted:   #7fa8d8;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--blue-deep);
            min-height: 100vh;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* ── Background layers ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 10%,  rgba(30,80,162,0.55)  0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 80%,  rgba(6,182,212,0.18)  0%, transparent 55%),
                radial-gradient(ellipse 50% 50% at 60% 30%,  rgba(99,102,241,0.14) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, rgba(96,165,250,0.06) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Orbs ── */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
            animation: drift 14s ease-in-out infinite alternate;
        }
        .orb-1 {
            width: 560px; height: 560px;
            background: radial-gradient(circle, rgba(30,80,162,0.45), transparent 70%);
            top: -160px; left: -160px;
        }
        .orb-2 {
            width: 420px; height: 420px;
            background: radial-gradient(circle, rgba(6,182,212,0.22), transparent 70%);
            bottom: -80px; right: -100px;
            animation-delay: -7s;
        }
        .orb-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(99,102,241,0.18), transparent 70%);
            top: 50%; left: 55%;
            animation-delay: -3.5s;
        }
        @keyframes drift {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(30px,20px) scale(1.08); }
        }

        /* ── Header ── */
        header {
            position: relative;
            z-index: 10;
            padding: 0 48px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--glass-border);
            backdrop-filter: blur(12px);
            background: rgba(10,22,40,0.7);
        }

        .logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: -0.02em;
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--blue-vivid);
            box-shadow: 0 0 12px var(--blue-vivid);
            animation: pulse 2.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%,100% { opacity:1; transform:scale(1);    box-shadow:0 0 12px var(--blue-vivid); }
            50%      { opacity:.6; transform:scale(.85); box-shadow:0 0 4px  var(--blue-vivid); }
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-ghost {
            font-family: 'Syne', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            border-radius: 100px;
            padding: 7px 18px;
            text-decoration: none;
            transition: background 0.15s, color 0.15s, transform 0.15s;
        }
        .nav-ghost:hover {
            background: rgba(255,255,255,0.09);
            color: var(--white);
            transform: translateY(-1px);
        }

        .nav-primary {
            display: flex;
            align-items: center;
            gap: 7px;
            font-family: 'Syne', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: var(--white);
            background: linear-gradient(135deg, var(--blue-core), var(--blue-bright));
            border: none;
            border-radius: 100px;
            padding: 7px 20px;
            text-decoration: none;
            transition: transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(30,80,162,0.4);
        }
        .nav-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(30,80,162,0.6);
        }

        /* ── Hero ── */
        .hero {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
            padding: 100px 24px 80px;
            text-align: center;
            animation: fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) both;
        }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(28px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* Badge */
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--blue-ice);
            background: rgba(59,130,246,0.1);
            border: 1px solid rgba(59,130,246,0.25);
            border-radius: 100px;
            padding: 6px 16px;
            margin-bottom: 32px;
        }

        .badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--accent-cyan);
            box-shadow: 0 0 8px var(--accent-cyan);
            animation: pulse 2s ease-in-out infinite;
        }

        /* Headline */
        .hero h1 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: clamp(2.6rem, 7vw, 5rem);
            letter-spacing: -0.04em;
            line-height: 1.05;
            color: var(--white);
            margin-bottom: 28px;
        }

        .hero h1 .grad {
            background: linear-gradient(135deg, var(--blue-glow) 0%, var(--accent-cyan) 50%, var(--blue-ice) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Sub */
        .hero p {
            font-size: clamp(1rem, 2vw, 1.2rem);
            font-weight: 300;
            color: var(--text-muted);
            max-width: 560px;
            margin: 0 auto 48px;
            line-height: 1.7;
        }

        /* CTA row */
        .cta-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            font-family: 'Syne', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            color: var(--white);
            background: linear-gradient(135deg, var(--blue-core) 0%, var(--blue-bright) 60%, var(--accent-cyan) 100%);
            border: none;
            border-radius: 14px;
            padding: 15px 32px;
            text-decoration: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 6px 28px rgba(30,80,162,0.5), inset 0 0 0 1px rgba(255,255,255,0.08);
        }
        .cta-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.12), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }
        .cta-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(30,80,162,0.65), inset 0 0 0 1px rgba(255,255,255,0.1);
        }
        .cta-primary:hover::before { opacity: 1; }

        .cta-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Syne', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            color: var(--text-muted);
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            padding: 15px 28px;
            text-decoration: none;
            transition: background 0.15s, color 0.15s, transform 0.15s;
        }
        .cta-secondary:hover {
            background: rgba(255,255,255,0.08);
            color: var(--white);
            transform: translateY(-2px);
        }

        /* ── Mock card preview ── */
        .preview-wrap {
            position: relative;
            z-index: 1;
            max-width: 420px;
            margin: 64px auto 80px;
            padding: 0 24px;
            animation: fadeUp 0.8s 0.15s cubic-bezier(0.16,1,0.3,1) both;
        }

        .mock-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 36px 32px 30px;
            backdrop-filter: blur(20px);
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03),
                0 32px 64px rgba(0,0,0,0.45),
                inset 0 1px 0 rgba(255,255,255,0.07);
            position: relative;
            overflow: hidden;
        }

        .mock-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue-core), var(--blue-vivid), var(--accent-cyan));
        }

        .mock-profile-top {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 20px;
        }

        .mock-avatar {
            width: 60px; height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-core), var(--blue-bright), var(--accent-cyan));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.3rem;
            color: var(--white);
            flex-shrink: 0;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2), 0 0 24px rgba(59,130,246,0.2);
        }

        .mock-name {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.02em;
            color: var(--white);
        }

        .mock-handle {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 3px;
            font-style: italic;
        }

        .mock-bio {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-style: italic;
            padding: 14px 16px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px;
            margin-bottom: 18px;
            line-height: 1.6;
        }

        .mock-links {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .mock-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 13px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 11px;
        }

        .mock-link-icon {
            width: 28px; height: 28px;
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .mock-link-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
        }

        .mock-link-val {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        /* Floating "tap" badge on card */
        .tap-badge {
            position: absolute;
            bottom: -14px;
            right: 28px;
            background: linear-gradient(135deg, var(--blue-core), var(--accent-cyan));
            border-radius: 100px;
            padding: 8px 18px;
            font-family: 'Syne', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: var(--white);
            box-shadow: 0 4px 20px rgba(6,182,212,0.35);
            display: flex;
            align-items: center;
            gap: 7px;
            animation: bobble 3s ease-in-out infinite;
        }

        @keyframes bobble {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-6px); }
        }

        /* ── Features section ── */
        .features {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 20px auto 100px;
            padding: 0 24px;
            animation: fadeUp 0.8s 0.25s cubic-bezier(0.16,1,0.3,1) both;
        }

        .features-label {
            text-align: center;
            font-family: 'Syne', sans-serif;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--blue-ice);
            margin-bottom: 40px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .feature-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 28px 24px;
            backdrop-filter: blur(16px);
            transition: background 0.2s, border-color 0.2s, transform 0.2s;
            position: relative;
            overflow: hidden;
        }

        .feature-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            opacity: 0;
            transition: opacity 0.2s;
            background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(59,130,246,0.08), transparent);
        }

        .feature-card:hover {
            background: rgba(255,255,255,0.05);
            border-color: rgba(59,130,246,0.25);
            transform: translateY(-4px);
        }

        .feature-card:hover::after { opacity: 1; }

        .feature-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px;
        }

        .fi-nfc    { background: rgba(59,130,246,0.15); }
        .fi-secure { background: rgba(6,182,212,0.15); }
        .fi-share  { background: rgba(99,102,241,0.15); }

        .feature-card h3 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: -0.01em;
            color: var(--white);
            margin-bottom: 8px;
        }

        .feature-card p {
            font-size: 0.84rem;
            color: var(--text-muted);
            line-height: 1.65;
            font-weight: 300;
        }

        /* ── Footer ── */
        footer {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 32px 24px 48px;
            border-top: 1px solid var(--glass-border);
            font-size: 0.8rem;
            color: var(--text-muted);
            opacity: 0.6;
        }

        footer a {
            color: var(--blue-glow);
            text-decoration: none;
        }

        footer a:hover { color: var(--blue-ice); }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            header { padding: 0 20px; }
            .hero  { padding: 72px 24px 56px; }
            .features-grid { grid-template-columns: 1fr; }
            .mock-links { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .nav-ghost { display: none; }
        }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Header -->
    <header>
        <a class="logo" href="index.php">
            <div class="logo-dot"></div>
            YourSpace
        </a>
        <nav class="nav-links">
            <a href="login.php" class="nav-ghost">Log In</a>
            <a href="signup.php" class="nav-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/>
                    <line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                Get Started
            </a>
        </nav>
    </header>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-badge">
            <div class="badge-dot"></div>
            NFC-Powered Profile Cards
        </div>

        <h1>
            One Tap.<br>
            <span class="grad">Your Whole Space.</span>
        </h1>

        <p>
            Claim your NFC tag, build your profile, and share everything — socials, contact, website — instantly with a single tap.
        </p>

        <div class="cta-row">
            <a href="signup.php" class="cta-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/>
                    <line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                Create Your Space
            </a>
            <a href="login.php" class="cta-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Sign In
            </a>
        </div>
    </section>

    <!-- Mock profile card preview -->
    <div class="preview-wrap">
        <div class="mock-card">
            <div class="mock-profile-top">
                <div class="mock-avatar">JD</div>
                <div>
                    <div class="mock-name">Juan dela Cruz</div>
                    <div class="mock-handle">@juandelacruz</div>
                </div>
            </div>
            <div class="mock-bio">
                Designer, developer, and coffee enthusiast. Building things that matter.
            </div>
            <div class="mock-links">
                <div class="mock-link">
                    <div class="mock-link-icon" style="background:rgba(225,48,108,0.15);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#e1306c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                        </svg>
                    </div>
                    <div>
                        <div class="mock-link-label">Instagram</div>
                        <div class="mock-link-val">@juandc</div>
                    </div>
                </div>
                <div class="mock-link">
                    <div class="mock-link-icon" style="background:rgba(59,130,246,0.15);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="mock-link-label">Facebook</div>
                        <div class="mock-link-val">juandelacruz</div>
                    </div>
                </div>
                <div class="mock-link">
                    <div class="mock-link-icon" style="background:rgba(34,197,94,0.15);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.64 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.64a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="mock-link-label">Phone</div>
                        <div class="mock-link-val">+63 912 345 6789</div>
                    </div>
                </div>
                <div class="mock-link">
                    <div class="mock-link-icon" style="background:rgba(6,182,212,0.15);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="mock-link-label">Website</div>
                        <div class="mock-link-val">juandc.dev</div>
                    </div>
                </div>
            </div>

            <div class="tap-badge">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                    <path d="M8 12l3 3 5-5"/>
                </svg>
                Tap to Share
            </div>
        </div>
    </div>

    <!-- Features -->
    <section class="features">
        <div class="features-label">Why YourSpace</div>
        <div class="features-grid">

            <div class="feature-card">
                <div class="feature-icon fi-nfc">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <h3>NFC-Powered</h3>
                <p>Tap your card to any phone — no app needed. Your profile opens instantly in any browser.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon fi-secure">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <h3>PIN Protected</h3>
                <p>Your profile edits are locked behind a 4-digit PIN. Only you can update your information.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon fi-share">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                    </svg>
                </div>
                <h3>Share Everything</h3>
                <p>Instagram, Facebook, phone, and website — all in one elegant card, always up to date.</p>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer>
        &copy; <?php echo date('Y'); ?> YourSpace &nbsp;·&nbsp;
        <a href="login.php">Log In</a> &nbsp;·&nbsp;
        <a href="signup.php">Sign Up</a>
    </footer>

</body>
</html>