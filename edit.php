<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$success = '';
$error   = '';

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name      = trim($_POST['name']      ?? '');
    $bio       = trim($_POST['bio']       ?? '');
    $instagram = trim($_POST['instagram'] ?? '');
    $facebook  = trim($_POST['facebook']  ?? '');
    $phone     = trim($_POST['phone']     ?? '');
    $website   = trim($_POST['website']   ?? '');

    $name_s      = mysqli_real_escape_string($conn, $name);
    $bio_s       = mysqli_real_escape_string($conn, $bio);
    $instagram_s = mysqli_real_escape_string($conn, $instagram);
    $facebook_s  = mysqli_real_escape_string($conn, $facebook);
    $phone_s     = mysqli_real_escape_string($conn, $phone);
    $website_s   = mysqli_real_escape_string($conn, $website);

    // Upsert
    $check = mysqli_query($conn, "SELECT id FROM profiles WHERE user_id='$user_id'");
    if(mysqli_num_rows($check) > 0){
        $sql = "UPDATE profiles SET name='$name_s', bio='$bio_s', instagram='$instagram_s',
                facebook='$facebook_s', phone='$phone_s', website='$website_s'
                WHERE user_id='$user_id'";
    } else {
        $sql = "INSERT INTO profiles (user_id,name,bio,instagram,facebook,phone,website)
                VALUES ('$user_id','$name_s','$bio_s','$instagram_s','$facebook_s','$phone_s','$website_s')";
    }

    if(mysqli_query($conn, $sql)){
        $success = 'Profile updated successfully!';
    } else {
        $error = 'Something went wrong. Please try again.';
    }
}

// Fetch current profile
$profile_query = mysqli_query($conn, "SELECT * FROM profiles WHERE user_id='$user_id'");
$profile = mysqli_fetch_assoc($profile_query);

$name      = htmlspecialchars($profile['name']      ?? '');
$bio       = htmlspecialchars($profile['bio']       ?? '');
$instagram = htmlspecialchars($profile['instagram'] ?? '');
$facebook  = htmlspecialchars($profile['facebook']  ?? '');
$phone     = htmlspecialchars($profile['phone']     ?? '');
$website   = htmlspecialchars($profile['website']   ?? '');

$words    = explode(' ', trim($name ?: 'A'));
$initials = '';
foreach(array_slice($words, 0, 2) as $w) $initials .= strtoupper($w[0] ?? '');
if(!$initials) $initials = '?';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile — YourSpace</title>
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
        }

        .back-btn {
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
            padding: 7px 18px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s, color 0.15s, transform 0.15s;
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.09);
            color: var(--white);
            transform: translateY(-1px);
        }

        /* ── Page ── */
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

        /* ── Card ── */
        .edit-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 48px 44px 44px;
            backdrop-filter: blur(20px);
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03),
                0 32px 64px rgba(0,0,0,0.4),
                inset 0 1px 0 rgba(255,255,255,0.07);
            position: relative;
            overflow: hidden;
        }

        .edit-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue-core), var(--blue-vivid), var(--accent-cyan));
        }

        /* ── Avatar preview ── */
        .avatar-preview-row {
            display: flex;
            align-items: center;
            gap: 22px;
            margin-bottom: 36px;
            padding-bottom: 32px;
            border-bottom: 1px solid var(--glass-border);
        }

        .avatar-preview {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-core) 0%, var(--blue-bright) 60%, var(--accent-cyan) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--white);
            flex-shrink: 0;
            box-shadow:
                0 0 0 3px rgba(59,130,246,0.2),
                0 0 24px rgba(59,130,246,0.2);
            letter-spacing: -0.02em;
            position: relative;
            transition: all 0.25s ease;
        }

        .avatar-preview::after {
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

        .avatar-info h2 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.02em;
            color: var(--white);
        }

        .avatar-info p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 300;
        }

        /* ── Group label ── */
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

        /* ── Field ── */
        .field-group {
            margin-bottom: 14px;
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 14px;
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

        input[type="text"],
        input[type="url"],
        input[type="tel"],
        textarea {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 12px;
            padding: 13px 16px 13px 42px;
            color: var(--text-primary);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 400;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }

        textarea {
            padding: 13px 16px;
            resize: vertical;
            min-height: 100px;
            line-height: 1.65;
        }

        input[type="text"]::placeholder,
        input[type="url"]::placeholder,
        input[type="tel"]::placeholder,
        textarea::placeholder {
            color: rgba(127,168,216,0.4);
        }

        input[type="text"]:focus,
        input[type="url"]:focus,
        input[type="tel"]:focus,
        textarea:focus {
            border-color: rgba(59,130,246,0.5);
            background: rgba(59,130,246,0.06);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        /* ── Divider ── */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--glass-border), transparent);
            margin: 28px 0;
        }

        /* ── Actions ── */
        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 32px;
        }

        .save-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Syne', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: var(--white);
            background: linear-gradient(135deg, var(--blue-core), var(--blue-bright));
            border: none;
            border-radius: 12px;
            padding: 13px 28px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(30,80,162,0.4);
        }
        .save-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(30,80,162,0.6);
        }

        .cancel-btn {
            display: flex;
            align-items: center;
            gap: 7px;
            font-family: 'Syne', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 12px;
            padding: 13px 24px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
        }
        .cancel-btn:hover {
            background: rgba(255,255,255,0.08);
            color: var(--white);
        }

        /* ── Alerts ── */
        .alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 24px;
            animation: fadeUp 0.3s ease both;
        }
        .alert-success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.25);
            color: #4ade80;
        }
        .alert-error {
            background: rgba(248,113,113,0.1);
            border: 1px solid rgba(248,113,113,0.25);
            color: #f87171;
        }

        /* ── Responsive ── */
        @media (max-width: 520px) {
            .edit-card { padding: 32px 22px 28px; }
            header { padding: 0 20px; }
            .field-row { grid-template-columns: 1fr; }
            .avatar-preview-row { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <header>
        <a class="logo" href="#">
            <div class="logo-dot"></div>
            Tap
        </a>
        <div class="header-actions">
            <div class="header-tag">Edit Profile</div>
            <a href="profile.php" class="back-btn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back
            </a>
        </div>
    </header>

    <div class="page-wrap">
        <div class="edit-card">

            <?php if($success): ?>
            <div class="alert alert-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>

            <?php if($error): ?>
            <div class="alert alert-error">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <!-- Avatar preview -->
            <div class="avatar-preview-row">
                <div class="avatar-preview" id="avatarPreview"><?php echo $initials; ?></div>
                <div class="avatar-info">
                    <h2 id="namePreview"><?php echo $name ?: 'Your Name'; ?></h2>
                    <p>Your initials are auto-generated from your name.</p>
                </div>
            </div>

            <form method="POST" action="edit.php">

                <!-- Basic Info -->
                <div class="group-label">Basic Info</div>

                <div class="field-group">
                    <label for="name">Full Name</label>
                    <div class="input-wrap">
                        <span class="icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#7fa8d8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <input type="text" id="name" name="name" placeholder="e.g. Juan dela Cruz" value="<?php echo $name; ?>" oninput="updatePreview(this.value)">
                    </div>
                </div>

                <div class="field-group" style="margin-bottom:28px;">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" placeholder="Tell the world a little about yourself…"><?php echo $bio; ?></textarea>
                </div>

                <div class="divider"></div>

                <!-- Social -->
                <div class="group-label">Social</div>
                <div class="field-row">
                    <div class="field-group" style="margin-bottom:0;">
                        <label for="instagram">Instagram</label>
                        <div class="input-wrap">
                            <span class="icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#e1306c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                                </svg>
                            </span>
                            <input type="text" id="instagram" name="instagram" placeholder="@username" value="<?php echo $instagram; ?>">
                        </div>
                    </div>
                    <div class="field-group" style="margin-bottom:0;">
                        <label for="facebook">Facebook</label>
                        <div class="input-wrap">
                            <span class="icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                                </svg>
                            </span>
                            <input type="text" id="facebook" name="facebook" placeholder="username or URL" value="<?php echo $facebook; ?>">
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Contact -->
                <div class="group-label">Contact</div>
                <div class="field-row">
                    <div class="field-group" style="margin-bottom:0;">
                        <label for="phone">Phone</label>
                        <div class="input-wrap">
                            <span class="icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.64 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.64a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </span>
                            <input type="tel" id="phone" name="phone" placeholder="+63 912 345 6789" value="<?php echo $phone; ?>">
                        </div>
                    </div>
                    <div class="field-group" style="margin-bottom:0;">
                        <label for="website">Website</label>
                        <div class="input-wrap">
                            <span class="icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="2" y1="12" x2="22" y2="12"/>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                                </svg>
                            </span>
                            <input type="url" id="website" name="website" placeholder="https://yoursite.com" value="<?php echo $website; ?>">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="save-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Save Changes
                    </button>
                    <a href="profile.php" class="cancel-btn">Cancel</a>
                </div>

            </form>
        </div>
    </div>

    <script>
        function updatePreview(val) {
            const words = val.trim().split(/\s+/).filter(Boolean);
            let initials = '';
            for (let i = 0; i < Math.min(2, words.length); i++) {
                initials += words[i][0].toUpperCase();
            }
            document.getElementById('avatarPreview').textContent = initials || '?';
            document.getElementById('namePreview').textContent   = val.trim() || 'Your Name';
        }
    </script>

</body>
</html>