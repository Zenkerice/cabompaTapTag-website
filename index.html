<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_SESSION['edit_access'])){
    header("Location: profile.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

$profile_query = mysqli_query($conn, "SELECT * FROM profiles WHERE user_id='$user_id'");
$profile = mysqli_fetch_assoc($profile_query);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name      = trim($_POST['name']);
    $bio       = trim($_POST['bio']);
    $instagram = trim($_POST['instagram']);
    $facebook  = trim($_POST['facebook']);
    $phone     = trim($_POST['phone']);
    $website   = trim($_POST['website']);

    $update = mysqli_query($conn, "UPDATE profiles SET name='$name', bio='$bio', instagram='$instagram', facebook='$facebook', phone='$phone', website='$website' WHERE user_id='$user_id'");

    if($update){
        $message = "success";
        $profile_query = mysqli_query($conn, "SELECT * FROM profiles WHERE user_id='$user_id'");
        $profile = mysqli_fetch_assoc($profile_query);
    } else {
        $message = "error";
    }
}

// Generate initials for avatar
$name_val = $profile['name'] ?? 'A';
$words    = explode(' ', trim($name_val));
$initials = '';
foreach(array_slice($words, 0, 2) as $w) $initials .= strtoupper($w[0] ?? '');
if(!$initials) $initials = '?';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — YourSpace</title>
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
            0%,100% { opacity:1; transform:scale(1);    box-shadow: 0 0 12px var(--blue-vivid); }
            50%      { opacity:.6; transform:scale(.85); box-shadow: 0 0 4px  var(--blue-vivid); }
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 14px;
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
        }

        .header-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-core), var(--blue-bright), var(--accent-cyan));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 0.75rem;
            color: var(--white);
            box-shadow: 0 0 0 2px rgba(59,130,246,0.3);
        }

        .view-profile-btn {
            display: flex;
            align-items: center;
            gap: 7px;
            font-family: 'Syne', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            border-radius: 100px;
            padding: 7px 16px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s, color 0.15s, transform 0.15s;
        }
        .view-profile-btn:hover {
            background: rgba(255,255,255,0.09);
            color: var(--white);
            transform: translateY(-1px);
        }

        /* ── Page ── */
        .page-wrap {
            position: relative;
            z-index: 1;
            max-width: 680px;
            margin: 56px auto 80px;
            padding: 0 24px;
            animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both;
        }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(24px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* ── Section header ── */
        .section-header {
            margin-bottom: 36px;
        }

        .section-header h1 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            letter-spacing: -0.03em;
            line-height: 1.1;
            color: var(--white);
        }

        .section-header h1 span {
            background: linear-gradient(135deg, var(--blue-glow), var(--accent-cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-header p {
            margin-top: 10px;
            font-size: 0.95rem;
            color: var(--text-muted);
            font-weight: 300;
        }

        /* ── Toast ── */
        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 28px;
            font-size: 0.88rem;
            font-weight: 500;
            animation: slideIn 0.4s cubic-bezier(0.16,1,0.3,1);
        }

        @keyframes slideIn {
            from { opacity:0; transform:translateY(-10px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .toast.success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.3);
            color: #86efac;
        }

        .toast.error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            color: #fca5a5;
        }

        .toast-icon {
            width: 22px; height: 22px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
        }
        .toast.success .toast-icon { background: rgba(34,197,94,0.2); }
        .toast.error   .toast-icon { background: rgba(239,68,68,0.2); }

        /* ── Card ── */
        .card {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 44px 40px 40px;
            backdrop-filter: blur(20px);
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03),
                0 32px 64px rgba(0,0,0,0.4),
                inset 0 1px 0 rgba(255,255,255,0.07);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue-core), var(--blue-vivid), var(--accent-cyan));
        }

        /* ── Group label ── */
        .group-label {
            font-family: 'Syne', sans-serif;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--blue-ice);
            margin-bottom: 18px;
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

        /* ── Fields ── */
        .field {
            margin-bottom: 16px;
        }

        .field:last-of-type { margin-bottom: 0; }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--glass-border), transparent);
            margin: 28px 0;
        }

        label {
            display: block;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            pointer-events: none;
            opacity: 0.5;
        }

        .input-wrap input { padding-left: 42px; }

        input[type="text"],
        textarea {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 12px;
            padding: 13px 16px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.92rem;
            font-weight: 400;
            color: var(--text-primary);
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }

        input[type="text"]::placeholder,
        textarea::placeholder {
            color: rgba(127,168,216,0.35);
        }

        input[type="text"]:hover,
        textarea:hover {
            border-color: rgba(59,130,246,0.3);
            background: rgba(255,255,255,0.055);
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: var(--blue-vivid);
            background: rgba(59,130,246,0.07);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }

        textarea {
            resize: vertical;
            min-height: 110px;
            line-height: 1.65;
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* ── Submit ── */
        .btn-wrap { margin-top: 36px; }

        button[type="submit"] {
            width: 100%;
            padding: 15px 32px;
            background: linear-gradient(135deg, var(--blue-core) 0%, var(--blue-bright) 60%, var(--accent-cyan) 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 24px rgba(30,80,162,0.5), inset 0 0 0 1px rgba(255,255,255,0.08);
        }

        button[type="submit"]::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.12), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 36px rgba(30,80,162,0.65), inset 0 0 0 1px rgba(255,255,255,0.1);
        }

        button[type="submit"]:hover::before { opacity: 1; }
        button[type="submit"]:active { transform: translateY(0); }

        .btn-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        @media (max-width: 520px) {
            .field-row { grid-template-columns: 1fr; }
            .card { padding: 32px 22px 28px; }
            header { padding: 0 20px; }
        }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <header>
        <a class="logo" href="profile.php">
            <div class="logo-dot"></div>
            Tap
        </a>
        <div class="header-right">
            <div class="header-tag">Dashboard</div>
            <a href="profile.php" class="view-profile-btn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                View Profile
            </a>
            <div class="header-avatar"><?php echo $initials; ?></div>
        </div>
    </header>

    <div class="page-wrap">

        <div class="section-header">
            <h1>Edit Your <span>Profile</span></h1>
            <p>Customize how the world sees you — every detail matters.</p>
        </div>

        <?php if($message === "success"): ?>
        <div class="toast success">
            <div class="toast-icon">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            Profile updated successfully!
        </div>
        <?php elseif($message === "error"): ?>
        <div class="toast error">
            <div class="toast-icon">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </div>
            Something went wrong. Please try again.
        </div>
        <?php endif; ?>

        <div class="card">
            <form method="POST" action="">

                <!-- Identity -->
                <div class="group-label">Identity</div>

                <div class="field">
                    <label for="name">Display Name</label>
                    <div class="input-wrap">
                        <span class="icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#7fa8d8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <input type="text" id="name" name="name"
                            value="<?php echo htmlspecialchars($profile['name'] ?? ''); ?>"
                            placeholder="Your full name">
                    </div>
                </div>

                <div class="field">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" placeholder="Tell people what makes you, you..."><?php echo htmlspecialchars($profile['bio'] ?? ''); ?></textarea>
                </div>

                <div class="divider"></div>

                <!-- Socials -->
                <div class="group-label">Social</div>

                <div class="field-row">
                    <div class="field" style="margin-bottom:0;">
                        <label for="instagram">Instagram</label>
                        <div class="input-wrap">
                            <span class="icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#e1306c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                                </svg>
                            </span>
                            <input type="text" id="instagram" name="instagram"
                                value="<?php echo htmlspecialchars($profile['instagram'] ?? ''); ?>"
                                placeholder="@yourhandle">
                        </div>
                    </div>
                    <div class="field" style="margin-bottom:0;">
                        <label for="facebook">Facebook</label>
                        <div class="input-wrap">
                            <span class="icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                                </svg>
                            </span>
                            <input type="text" id="facebook" name="facebook"
                                value="<?php echo htmlspecialchars($profile['facebook'] ?? ''); ?>"
                                placeholder="username">
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Contact -->
                <div class="group-label">Contact</div>

                <div class="field-row">
                    <div class="field" style="margin-bottom:0;">
                        <label for="phone">Phone</label>
                        <div class="input-wrap">
                            <span class="icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.64 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.64a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </span>
                            <input type="text" id="phone" name="phone"
                                value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>"
                                placeholder="09123456789">
                        </div>
                    </div>
                    <div class="field" style="margin-bottom:0;">
                        <label for="website">Website</label>
                        <div class="input-wrap">
                            <span class="icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="2" y1="12" x2="22" y2="12"/>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                                </svg>
                            </span>
                            <input type="text" id="website" name="website"
                                value="<?php echo htmlspecialchars($profile['website'] ?? ''); ?>"
                                placeholder="https://example.com">
                        </div>
                    </div>
                </div>

                <div class="btn-wrap">
                    <button type="submit">
                        <span class="btn-inner">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Save Changes
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>

</body>
</html>
