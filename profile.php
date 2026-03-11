<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$profile_query = mysqli_query($conn, "SELECT * FROM profiles WHERE user_id='$user_id'");
$profile = mysqli_fetch_assoc($profile_query);

$name      = htmlspecialchars($profile['name']      ?? 'Anonymous');
$bio       = htmlspecialchars($profile['bio']       ?? '');
$instagram = htmlspecialchars($profile['instagram'] ?? '');
$facebook  = htmlspecialchars($profile['facebook']  ?? '');
$phone     = htmlspecialchars($profile['phone']     ?? '');
$website   = htmlspecialchars($profile['website']   ?? '');

// Generate initials for avatar
$words    = explode(' ', trim($name));
$initials = '';
foreach(array_slice($words, 0, 2) as $w) $initials .= strtoupper($w[0] ?? '');
if(!$initials) $initials = '?';

// PIN — change this to your desired PIN
define('EDIT_PIN', '1234');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name; ?> — Profile</title>
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

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--blue-deep);
            min-height: 100vh;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(30,80,162,0.55) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 80%, rgba(6,182,212,0.18) 0%, transparent 55%),
                radial-gradient(ellipse 50% 50% at 60% 30%, rgba(99,102,241,0.14) 0%, transparent 50%);
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

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
            animation: drift 14s ease-in-out infinite alternate;
        }
        .orb-1 {
            width: 480px; height: 480px;
            background: radial-gradient(circle, rgba(30,80,162,0.4), transparent 70%);
            top: -100px; left: -120px;
        }
        .orb-2 {
            width: 360px; height: 360px;
            background: radial-gradient(circle, rgba(6,182,212,0.2), transparent 70%);
            bottom: 0; right: -80px;
            animation-delay: -7s;
        }
        @keyframes drift {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(30px,20px) scale(1.08); }
        }

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
            0%,100% { opacity:1; transform:scale(1);    box-shadow: 0 0 12px var(--blue-vivid); }
            50%      { opacity:.6; transform:scale(.85); box-shadow: 0 0 4px  var(--blue-vivid); }
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-tag {
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--blue-ice);
            background: rgba(59,130,246,0.12);
            border: 1px solid rgba(59,130,246,0.25);
            padding: 5px 14px;
            border-radius: 100px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background 0.15s, border-color 0.15s, transform 0.15s;
            font-family: 'Syne', sans-serif;
        }
        .header-tag:hover {
            background: rgba(59,130,246,0.2);
            border-color: rgba(59,130,246,0.45);
            transform: translateY(-1px);
        }
        .header-tag.open {
            background: rgba(59,130,246,0.22);
            border-color: rgba(59,130,246,0.5);
        }

        /* ── Menu Modal ── */
        .menu-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99;
        }
        .menu-backdrop.active { display: block; }

        .menu-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 230px;
            background: rgba(10, 24, 48, 0.97);
            border: 1px solid rgba(255,255,255,0.11);
            border-radius: 18px;
            padding: 10px;
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.04),
                0 24px 56px rgba(0,0,0,0.55),
                inset 0 1px 0 rgba(255,255,255,0.07);
            backdrop-filter: blur(20px);
            z-index: 100;
            overflow: hidden;
        }
        .menu-dropdown::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--blue-core), var(--blue-vivid), var(--accent-cyan));
        }
        .menu-dropdown.open {
            display: block;
            animation: dropIn 0.22s cubic-bezier(0.16,1,0.3,1) both;
        }
        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-8px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0)   scale(1); }
        }

        /* User info row at top of menu */
        .menu-user {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 10px 12px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            margin-bottom: 6px;
        }
        .menu-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-core), var(--blue-bright), var(--accent-cyan));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 0.72rem;
            color: var(--white);
            flex-shrink: 0;
            box-shadow: 0 0 0 2px rgba(59,130,246,0.25);
        }
        .menu-user-info { min-width: 0; }
        .menu-user-name {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.82rem;
            color: var(--white);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .menu-user-role {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 1px;
        }

        /* Menu items */
        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 11px;
            border: none;
            background: transparent;
            color: var(--text-primary);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            width: 100%;
            text-align: left;
            transition: background 0.15s, color 0.15s;
        }
        .menu-item:hover {
            background: rgba(255,255,255,0.06);
            color: var(--white);
        }
        .menu-item .item-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .menu-item.settings .item-icon { background: rgba(99,102,241,0.15); }
        .menu-item.help     .item-icon { background: rgba(6,182,212,0.15); }
        .menu-item.logout   .item-icon { background: rgba(248,113,113,0.12); }
        .menu-item.logout               { color: #f87171; }
        .menu-item.logout:hover         { background: rgba(248,113,113,0.08); color: #fca5a5; }

        .menu-divider {
            height: 1px;
            background: rgba(255,255,255,0.07);
            margin: 6px 0;
        }

        /* wrapper needs position:relative for dropdown */
        .header-menu-wrap { position: relative; }

        .edit-btn {
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
            padding: 7px 18px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.15s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 16px rgba(30,80,162,0.4);
        }
        .edit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(30,80,162,0.6);
        }

        .page-wrap {
            position: relative;
            z-index: 1;
            max-width: 720px;
            margin: 60px auto 80px;
            padding: 0 24px;
            animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both;
        }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(24px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .hero-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 48px 44px 40px;
            backdrop-filter: blur(20px);
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03),
                0 32px 64px rgba(0,0,0,0.4),
                inset 0 1px 0 rgba(255,255,255,0.07);
            position: relative;
            overflow: hidden;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue-core), var(--blue-vivid), var(--accent-cyan));
        }

        .profile-top {
            display: flex;
            align-items: center;
            gap: 28px;
            margin-bottom: 32px;
        }

        .avatar {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-core) 0%, var(--blue-bright) 60%, var(--accent-cyan) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--white);
            flex-shrink: 0;
            box-shadow:
                0 0 0 3px rgba(59,130,246,0.2),
                0 0 32px rgba(59,130,246,0.25),
                0 8px 24px rgba(0,0,0,0.3);
            letter-spacing: -0.02em;
            position: relative;
        }

        .avatar::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid transparent;
            background: linear-gradient(135deg, rgba(59,130,246,0.5), rgba(6,182,212,0.3)) border-box;
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: destination-out;
            mask-composite: exclude;
        }

        .profile-info h1 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: clamp(1.5rem, 3.5vw, 2.1rem);
            letter-spacing: -0.03em;
            color: var(--white);
            line-height: 1.1;
        }

        .profile-info .handle {
            margin-top: 6px;
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 300;
            font-style: italic;
        }

        .bio-block {
            font-size: 0.95rem;
            font-weight: 300;
            color: var(--text-muted);
            line-height: 1.75;
            padding: 20px 22px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px;
            margin-bottom: 32px;
            font-style: italic;
            position: relative;
        }

        .bio-block::before {
            content: '"';
            position: absolute;
            top: -6px; left: 16px;
            font-size: 2.5rem;
            font-family: 'Syne', sans-serif;
            color: var(--blue-vivid);
            opacity: 0.4;
            line-height: 1;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--glass-border), transparent);
            margin: 28px 0;
        }

        .group-label {
            font-family: 'Syne', sans-serif;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--blue-ice);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .group-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(147,197,253,0.2), transparent);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media (max-width: 520px) {
            .contact-grid { grid-template-columns: 1fr; }
            .hero-card { padding: 32px 22px 28px; }
            .profile-top { flex-direction: column; align-items: flex-start; gap: 18px; }
            header { padding: 0 20px; }
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px;
            text-decoration: none;
            transition: background 0.2s ease, border-color 0.2s ease, transform 0.15s ease;
            cursor: pointer;
        }

        .contact-item:hover {
            background: rgba(59,130,246,0.08);
            border-color: rgba(59,130,246,0.25);
            transform: translateY(-2px);
        }

        .contact-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .contact-icon.instagram { background: rgba(225,48,108,0.15); }
        .contact-icon.facebook  { background: rgba(59,130,246,0.15); }
        .contact-icon.phone     { background: rgba(34,197,94,0.15); }
        .contact-icon.website   { background: rgba(6,182,212,0.15); }

        .contact-text { min-width: 0; }

        .contact-label {
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .contact-value {
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .contact-item.empty {
            opacity: 0.35;
            pointer-events: none;
        }

        .empty-bio {
            text-align: center;
            padding: 24px 0;
            color: var(--text-muted);
            font-size: 0.88rem;
            font-style: italic;
            opacity: 0.5;
        }

        /* ── PIN Modal ── */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 100;
            background: rgba(5, 14, 28, 0.75);
            backdrop-filter: blur(10px);
            align-items: center;
            justify-content: center;
            animation: backdropIn 0.25s ease both;
        }
        .modal-backdrop.active {
            display: flex;
        }
        @keyframes backdropIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        .modal {
            background: rgba(13, 31, 60, 0.95);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 24px;
            padding: 40px 36px 36px;
            width: 100%;
            max-width: 380px;
            position: relative;
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.04),
                0 40px 80px rgba(0,0,0,0.6),
                inset 0 1px 0 rgba(255,255,255,0.07);
            animation: modalIn 0.3s cubic-bezier(0.16,1,0.3,1) both;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: translateY(20px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0)  scale(1); }
        }

        /* Top accent stripe */
        .modal::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 24px 24px 0 0;
            background: linear-gradient(90deg, var(--blue-core), var(--blue-vivid), var(--accent-cyan));
        }

        .modal-close {
            position: absolute;
            top: 16px; right: 16px;
            width: 30px; height: 30px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.04);
            color: var(--text-muted);
            font-size: 1rem;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.15s, color 0.15s;
        }
        .modal-close:hover {
            background: rgba(255,255,255,0.09);
            color: var(--white);
        }

        .modal-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(30,80,162,0.5), rgba(45,108,223,0.3));
            border: 1px solid rgba(59,130,246,0.25);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 0 20px rgba(59,130,246,0.15);
        }

        .modal h2 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.02em;
            color: var(--white);
            margin-bottom: 6px;
        }

        .modal p {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 300;
            margin-bottom: 28px;
            line-height: 1.5;
        }

        /* PIN dots row */
        .pin-dots {
            display: flex;
            justify-content: center;
            gap: 14px;
            margin-bottom: 24px;
        }
        .pin-dot {
            width: 14px; height: 14px;
            border-radius: 50%;
            border: 2px solid rgba(59,130,246,0.35);
            background: transparent;
            transition: background 0.15s, border-color 0.15s, box-shadow 0.15s;
        }
        .pin-dot.filled {
            background: var(--blue-vivid);
            border-color: var(--blue-vivid);
            box-shadow: 0 0 10px rgba(59,130,246,0.5);
        }

        /* Numpad */
        .numpad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .num-key {
            height: 52px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.04);
            color: var(--text-primary);
            font-family: 'Syne', sans-serif;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s, transform 0.1s;
            display: flex; align-items: center; justify-content: center;
            user-select: none;
        }
        .num-key:hover {
            background: rgba(59,130,246,0.12);
            border-color: rgba(59,130,246,0.3);
        }
        .num-key:active {
            transform: scale(0.94);
            background: rgba(59,130,246,0.2);
        }
        .num-key.delete {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        .num-key.empty {
            pointer-events: none;
            opacity: 0;
        }

        .pin-submit {
            width: 100%;
            height: 48px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, var(--blue-core), var(--blue-bright));
            color: var(--white);
            font-family: 'Syne', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(30,80,162,0.4);
        }
        .pin-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(30,80,162,0.6);
        }
        .pin-submit:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
        }

        .pin-error {
            text-align: center;
            color: #f87171;
            font-size: 0.8rem;
            margin-top: 12px;
            height: 18px;
            transition: opacity 0.2s;
        }

        /* shake animation on wrong PIN */
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%      { transform: translateX(-8px); }
            40%      { transform: translateX(8px); }
            60%      { transform: translateX(-6px); }
            80%      { transform: translateX(6px); }
        }
        .modal.shake { animation: shake 0.4s ease; }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <!-- PIN Modal -->
    <div class="modal-backdrop" id="pinModal">
        <div class="modal" id="modalBox">
            <button class="modal-close" onclick="closeModal()" aria-label="Close">✕</button>

            <div class="modal-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>

            <h2>Enter Your PIN</h2>
            <p>Enter your 4-digit PIN to access the profile editor.</p>

            <div class="pin-dots" id="pinDots">
                <div class="pin-dot" id="d0"></div>
                <div class="pin-dot" id="d1"></div>
                <div class="pin-dot" id="d2"></div>
                <div class="pin-dot" id="d3"></div>
            </div>

            <div class="numpad">
                <?php for($n=1;$n<=9;$n++): ?>
                <button class="num-key" onclick="pressKey('<?php echo $n; ?>')"><?php echo $n; ?></button>
                <?php endfor; ?>
                <button class="num-key empty"></button>
                <button class="num-key" onclick="pressKey('0')">0</button>
                <button class="num-key delete" onclick="deleteKey()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"/>
                        <line x1="18" y1="9" x2="12" y2="15"/>
                        <line x1="12" y1="9" x2="18" y2="15"/>
                    </svg>
                </button>
            </div>

            <button class="pin-submit" id="submitBtn" disabled onclick="submitPin()">Unlock Editor</button>
            <div class="pin-error" id="pinError"></div>
        </div>
    </div>

    <header>
        <a class="logo" href="#">
            <div class="logo-dot"></div>
            Tap
        </a>
        <div class="header-actions">
            <div class="header-menu-wrap">
                <button class="header-tag" id="menuBtn" onclick="toggleMenu()" aria-haspopup="true" aria-expanded="false">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Profile
                    <svg id="menuChevron" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="transition:transform 0.2s ease;">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                <div class="menu-dropdown" id="menuDropdown">
                    <!-- User info -->
                    <div class="menu-user">
                        <div class="menu-avatar"><?php echo $initials; ?></div>
                        <div class="menu-user-info">
                            <div class="menu-user-name"><?php echo $name; ?></div>
                            <div class="menu-user-role">Tap Member</div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <button class="menu-item settings" onclick="closeMenu(); alert('Settings coming soon!')">
                        <span class="item-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                            </svg>
                        </span>
                        Settings
                    </button>

                    <!-- Help -->
                    <button class="menu-item help" onclick="closeMenu(); alert('Help coming soon!')">
                        <span class="item-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                        </span>
                        Help &amp; Support
                    </button>

                    <div class="menu-divider"></div>

                    <!-- Logout -->
                    <a class="menu-item logout" href="logout.php">
                        <span class="item-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                        </span>
                        Log Out
                    </a>
                </div>
            </div>

            <!-- Invisible backdrop to close on outside click -->
            <div class="menu-backdrop" id="menuBackdrop" onclick="closeMenu()"></div>

            <button onclick="openModal()" class="edit-btn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit Profile
            </button>
        </div>
    </header>

    <div class="page-wrap">
        <div class="hero-card">

            <div class="profile-top">
                <div class="avatar"><?php echo $initials; ?></div>
                <div class="profile-info">
                    <h1><?php echo $name; ?></h1>
                    <?php if($instagram): ?>
                        <p class="handle">@<?php echo ltrim($instagram, '@'); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($bio): ?>
            <div class="bio-block"><?php echo nl2br($bio); ?></div>
            <?php else: ?>
            <p class="empty-bio">No bio added yet.</p>
            <?php endif; ?>

            <div class="divider"></div>

            <div class="group-label">Social</div>
            <div class="contact-grid" style="margin-bottom: 14px;">

                <a <?php if($instagram): ?>href="https://instagram.com/<?php echo ltrim($instagram,'@'); ?>" target="_blank"<?php endif; ?>
                   class="contact-item <?php echo !$instagram ? 'empty' : ''; ?>">
                    <div class="contact-icon instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e1306c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <div class="contact-label">Instagram</div>
                        <div class="contact-value"><?php echo $instagram ?: 'Not set'; ?></div>
                    </div>
                </a>

                <a <?php if($facebook): ?>href="https://facebook.com/<?php echo $facebook; ?>" target="_blank"<?php endif; ?>
                   class="contact-item <?php echo !$facebook ? 'empty' : ''; ?>">
                    <div class="contact-icon facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <div class="contact-label">Facebook</div>
                        <div class="contact-value"><?php echo $facebook ?: 'Not set'; ?></div>
                    </div>
                </a>

            </div>

            <div class="divider"></div>

            <div class="group-label">Contact</div>
            <div class="contact-grid">

                <a <?php if($phone): ?>href="tel:<?php echo $phone; ?>"<?php endif; ?>
                   class="contact-item <?php echo !$phone ? 'empty' : ''; ?>">
                    <div class="contact-icon phone">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.64 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.64a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <div class="contact-label">Phone</div>
                        <div class="contact-value"><?php echo $phone ?: 'Not set'; ?></div>
                    </div>
                </a>

                <a <?php if($website): ?>href="<?php echo $website; ?>" target="_blank"<?php endif; ?>
                   class="contact-item <?php echo !$website ? 'empty' : ''; ?>">
                    <div class="contact-icon website">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="2" y1="12" x2="22" y2="12"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <div class="contact-label">Website</div>
                        <div class="contact-value"><?php echo $website ?: 'Not set'; ?></div>
                    </div>
                </a>

            </div>

        </div>
    </div>

    <script>
        const CORRECT_PIN = '<?php echo EDIT_PIN; ?>';
        let enteredPin = '';

        function openModal() {
            enteredPin = '';
            updateDots();
            document.getElementById('pinError').textContent = '';
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('pinModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('pinModal').classList.remove('active');
            enteredPin = '';
            updateDots();
        }

        function pressKey(digit) {
            if (enteredPin.length >= 4) return;
            enteredPin += digit;
            updateDots();
            if (enteredPin.length === 4) {
                document.getElementById('submitBtn').disabled = false;
            }
        }

        function deleteKey() {
            enteredPin = enteredPin.slice(0, -1);
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('pinError').textContent = '';
            updateDots();
        }

        function updateDots() {
            for (let i = 0; i < 4; i++) {
                const dot = document.getElementById('d' + i);
                dot.classList.toggle('filled', i < enteredPin.length);
            }
        }

        function submitPin() {
            if (enteredPin === CORRECT_PIN) {
                window.location.href = 'edit.php';
            } else {
                const box = document.getElementById('modalBox');
                box.classList.remove('shake');
                void box.offsetWidth; // reflow to re-trigger
                box.classList.add('shake');
                document.getElementById('pinError').textContent = 'Incorrect PIN. Please try again.';
                enteredPin = '';
                updateDots();
                document.getElementById('submitBtn').disabled = true;
            }
        }

        // Close on backdrop click
        document.getElementById('pinModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Keyboard support
        document.addEventListener('keydown', function(e) {
            if (!document.getElementById('pinModal').classList.contains('active')) return;
            if (e.key >= '0' && e.key <= '9') pressKey(e.key);
            if (e.key === 'Backspace') deleteKey();
            if (e.key === 'Enter' && enteredPin.length === 4) submitPin();
            if (e.key === 'Escape') closeModal();
        });

        // ── Menu toggle ──
        function toggleMenu() {
            const dropdown = document.getElementById('menuDropdown');
            const btn      = document.getElementById('menuBtn');
            const backdrop = document.getElementById('menuBackdrop');
            const chevron  = document.getElementById('menuChevron');
            const isOpen   = dropdown.classList.contains('open');
            if (isOpen) {
                closeMenu();
            } else {
                dropdown.classList.add('open');
                btn.classList.add('open');
                backdrop.classList.add('active');
                chevron.style.transform = 'rotate(180deg)';
                btn.setAttribute('aria-expanded', 'true');
            }
        }

        function closeMenu() {
            document.getElementById('menuDropdown').classList.remove('open');
            document.getElementById('menuBtn').classList.remove('open');
            document.getElementById('menuBackdrop').classList.remove('active');
            document.getElementById('menuChevron').style.transform = 'rotate(0deg)';
            document.getElementById('menuBtn').setAttribute('aria-expanded', 'false');
        }

        // Close menu on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMenu();
        });
    </script>

</body>
</html>