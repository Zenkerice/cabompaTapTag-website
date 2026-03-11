<?php
session_start();
include 'db.php';

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($query) == 1){
        $user = mysqli_fetch_assoc($query);
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            header("Location: profile.php");
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — YourSpace</title>
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
            display: flex;
            flex-direction: column;
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
            width: 520px; height: 520px;
            background: radial-gradient(circle, rgba(30,80,162,0.45), transparent 70%);
            top: -140px; left: -140px;
        }
        .orb-2 {
            width: 380px; height: 380px;
            background: radial-gradient(circle, rgba(6,182,212,0.22), transparent 70%);
            bottom: -60px; right: -80px;
            animation-delay: -7s;
        }
        .orb-3 {
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(99,102,241,0.18), transparent 70%);
            top: 40%; left: 60%;
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

        /* ── Center layout ── */
        .center-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            padding: 40px 24px;
        }

        /* ── Login card ── */
        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 48px 40px 44px;
            backdrop-filter: blur(20px);
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03),
                0 40px 80px rgba(0,0,0,0.5),
                inset 0 1px 0 rgba(255,255,255,0.07);
            position: relative;
            overflow: hidden;
            animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both;
        }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(28px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue-core), var(--blue-vivid), var(--accent-cyan));
        }

        /* ── Card header ── */
        .card-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(30,80,162,0.5), rgba(45,108,223,0.3));
            border: 1px solid rgba(59,130,246,0.25);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 22px;
            box-shadow: 0 0 20px rgba(59,130,246,0.15);
        }

        .login-card h1 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.65rem;
            letter-spacing: -0.03em;
            color: var(--white);
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .login-card h1 span {
            background: linear-gradient(135deg, var(--blue-glow), var(--accent-cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-card .subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            font-weight: 300;
            margin-bottom: 34px;
        }

        /* ── Error alert ── */
        .alert-error {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: rgba(248,113,113,0.1);
            border: 1px solid rgba(248,113,113,0.25);
            border-radius: 10px;
            color: #f87171;
            font-size: 0.84rem;
            font-weight: 500;
            margin-bottom: 24px;
            animation: fadeUp 0.3s ease both;
        }

        /* ── Fields ── */
        .field {
            margin-bottom: 16px;
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

        /* Eye toggle for password */
        .eye-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            padding: 0;
            opacity: 0.5;
            transition: opacity 0.15s;
        }
        .eye-toggle:hover { opacity: 1; }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
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

        input::placeholder { color: rgba(127,168,216,0.35); }

        input:hover {
            border-color: rgba(59,130,246,0.3);
            background: rgba(255,255,255,0.055);
        }

        input:focus {
            border-color: var(--blue-vivid);
            background: rgba(59,130,246,0.07);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }

        /* ── Submit ── */
        .submit-btn {
            width: 100%;
            margin-top: 28px;
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.12), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 36px rgba(30,80,162,0.65), inset 0 0 0 1px rgba(255,255,255,0.1);
        }

        .submit-btn:hover::before { opacity: 1; }
        .submit-btn:active { transform: translateY(0); }

        /* ── Footer link ── */
        .card-footer {
            margin-top: 26px;
            text-align: center;
            font-size: 0.84rem;
            color: var(--text-muted);
        }

        .card-footer a {
            color: var(--blue-glow);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s;
        }

        .card-footer a:hover { color: var(--blue-ice); }

        @media (max-width: 480px) {
            .login-card { padding: 36px 24px 32px; }
            header { padding: 0 20px; }
        }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <header>
        <a class="logo" href="#">
            <div class="logo-dot"></div>
            Tap
        </a>
    </header>

    <div class="center-wrap">
        <div class="login-card">

            <div class="card-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
            </div>

            <h1>Welcome <span>back.</span></h1>
            <p class="subtitle">Sign in to manage your YourSpace profile.</p>

            <?php if($error !== ""): ?>
            <div class="alert-error">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="field">
                    <label for="email">Email Address</label>
                    <div class="input-wrap">
                        <span class="icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#7fa8d8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <span class="icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#7fa8d8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <button type="button" class="eye-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                            <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Sign In
                </button>

            </form>

            <div class="card-footer">
                Don't have an account? <a href="signup.php">Sign up here</a>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>`;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>`;
            }
        }
    </script>

</body>
</html>