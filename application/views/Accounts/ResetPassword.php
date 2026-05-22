<?php
/*
 | ---------------------------------------------------------------
 |  Anjuman-e-Saifee — Change Password Page
 |  CodeIgniter view — drop into application/views/
 | ---------------------------------------------------------------
*/
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
    body, html {
    overflow: hidden !important;
    height: 100% !important;
    margin: 0;
    padding: 0;
  }

  .lp-root {
    font-family: 'Plus Jakarta Sans', sans-serif;
    margin-top: var(--lp-nav-h, 57px);
    height: calc(100dvh - var(--lp-nav-h, 57px));
    width: 100%;
    background-color: #c9a460;
    background-image: url('https://www.its52.com/imgs/1443/bg_Login_Jamea.jpg?v1');
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    position: relative;
    box-sizing: border-box;
  }

    .lp-root::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(60, 35, 5, 0.38);
        pointer-events: none;
        z-index: 0;
    }

    .lp-bg-shape {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        z-index: 1;
    }

    .lp-bg-shape-1 {
        width: 500px;
        height: 500px;
        top: -200px;
        left: -180px;
        background: radial-gradient(circle, rgba(184, 134, 11, 0.20) 0%, transparent 70%);
    }

    .lp-bg-shape-2 {
        width: 420px;
        height: 420px;
        bottom: -150px;
        right: -120px;
        background: radial-gradient(circle, rgba(201, 162, 39, 0.18) 0%, transparent 70%);
    }

    .lp-bg-shape-3 {
        width: 220px;
        height: 220px;
        top: 38%;
        right: 8%;
        background: radial-gradient(circle, rgba(184, 134, 11, 0.12) 0%, transparent 70%);
    }

    .lp-bg-svg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    .lp-card {
        background: #ffffff;
        border-radius: 22px;
        width: 100%;
        max-width: 420px;
        position: relative;
        z-index: 2;
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.28), 0 2px 8px rgba(0, 0, 0, 0.12);
        border: 1px solid rgba(184, 134, 11, 0.22);
        max-height: calc(100dvh - var(--lp-nav-h, 57px) - 2rem);
        overflow-y: auto;
        overflow-x: hidden;
    }

    @media (max-width: 480px) {
        .lp-card {
            max-width: 100%;
            border-radius: 16px;
        }

        .lp-root {
            padding: 0.75rem;
        }
    }

    @media (max-width: 360px) {
        .lp-card {
            border-radius: 12px;
        }

        .lp-root {
            padding: 0.5rem;
        }
    }

    @media (max-height: 600px) {
        .lp-card-top {
            padding: 16px 24px 14px !important;
        }

        .lp-org-name {
            font-size: 1.15rem !important;
        }

        .lp-card-body {
            padding: 14px 24px 16px !important;
        }

        .lp-field {
            margin-bottom: 10px !important;
        }

        .lp-icon-circle {
            width: 40px !important;
            height: 40px !important;
            margin-bottom: 8px !important;
        }

        .lp-icon-circle i {
            font-size: 20px !important;
        }

        .lp-strength {
            display: none;
        }
    }

    /* ─── Card header ─── */
    .lp-card-top {
        background: linear-gradient(135deg, #78520a 0%, #b8860b 55%, #c9a227 100%);
        padding: 28px 28px 22px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .lp-card-top::before {
        content: '';
        position: absolute;
        top: -45px;
        right: -45px;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 24px solid rgba(255, 255, 255, 0.08);
    }

    .lp-card-top::after {
        content: '';
        position: absolute;
        bottom: -30px;
        left: 10px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 16px solid rgba(255, 255, 255, 0.06);
    }

    .lp-top-diamond {
        position: absolute;
        top: 14px;
        left: 22px;
        width: 13px;
        height: 13px;
        background: rgba(255, 255, 255, 0.18);
        transform: rotate(45deg);
        border-radius: 2px;
    }

    .lp-top-diamond-2 {
        position: absolute;
        bottom: 14px;
        right: 30px;
        width: 9px;
        height: 9px;
        background: rgba(255, 255, 255, 0.14);
        transform: rotate(45deg);
        border-radius: 2px;
    }

    .lp-icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        position: relative;
        z-index: 1;
    }

    .lp-icon-circle i {
        font-size: 24px;
        color: #fff;
    }

    .lp-org-name {
        font-family: 'Literata', Georgia, serif;
        font-size: 1.45rem;
        font-weight: 600;
        color: #fff;
        margin: 0 0 6px;
        line-height: 1.2;
        position: relative;
        z-index: 1;
    }

    .lp-org-sub {
        font-size: 0.74rem;
        color: rgba(255, 255, 255, 0.68);
        font-weight: 500;
        margin: 0;
        position: relative;
        z-index: 1;
        letter-spacing: 0.2px;
    }

    /* ─── Card body ─── */
    .lp-card-body {
        padding: 22px 28px 26px;
        background: #fff;
    }

    @media (max-width: 360px) {
        .lp-card-body {
            padding: 18px 18px 22px;
        }

        .lp-card-top {
            padding: 22px 18px 18px;
        }

        .lp-card-footer {
            padding: 10px 18px;
        }
    }

    .lp-section-label {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .lp-section-line {
        flex: 1;
        height: 1px;
        background: #ede5d0;
    }

    .lp-section-text {
        font-size: 0.67rem;
        font-weight: 700;
        letter-spacing: 1.1px;
        color: #b8860b;
        text-transform: uppercase;
        white-space: nowrap;
    }

    /* ─── Form fields ─── */
    .lp-field {
        margin-bottom: 14px;
    }

    .lp-label {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.76rem;
        font-weight: 700;
        color: #5a4a2a;
        margin-bottom: 6px;
    }

    .lp-label i {
        font-size: 14px;
        color: #b8860b;
    }

    .lp-input-wrap {
        position: relative;
    }

    .lp-input {
        width: 100%;
        padding: 10px 44px 10px 40px;
        border: 1.5px solid #e0d4b8;
        border-radius: 9px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: max(16px, 0.88rem);
        color: #2a2010;
        background: #fdf9f0;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        box-sizing: border-box;
    }

    .lp-input:focus {
        border-color: #b8860b;
        box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.12);
        background: #fff;
    }

    .lp-input::placeholder {
        color: #c4b08a;
    }

    .lp-input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 15px;
        color: #c4a855;
        pointer-events: none;
    }

    .lp-input-suffix {
        position: absolute;
        right: 11px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #c4a855;
        font-size: 15px;
        background: none;
        border: none;
        padding: 0;
        display: flex;
        align-items: center;
        min-width: 36px;
        min-height: 36px;
        justify-content: center;
        transition: color 0.15s;
    }

    .lp-input-suffix:hover {
        color: #b8860b;
    }

    /* ─── Password strength ─── */
    .lp-strength {
        margin-top: 6px;
    }

    .lp-strength-bar {
        height: 4px;
        border-radius: 2px;
        background: #ede5d0;
        overflow: hidden;
        margin-bottom: 4px;
    }

    .lp-strength-fill {
        height: 100%;
        border-radius: 2px;
        width: 0%;
        transition: width 0.3s, background 0.3s;
    }

    .lp-strength-text {
        font-size: 0.72rem;
        color: #9a8060;
        font-weight: 500;
    }

    /* ─── Match indicator ─── */
    .lp-match {
        font-size: 0.72rem;
        font-weight: 500;
        margin-top: 5px;
        display: none;
        align-items: center;
        gap: 4px;
    }

    .lp-match i {
        font-size: 13px;
    }

    /* ─── Submit button ─── */
    .lp-btn {
        width: 100%;
        padding: 11px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #78520a 0%, #b8860b 55%, #c9a227 100%);
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.92rem;
        font-weight: 700;
        cursor: pointer;
        letter-spacing: 0.3px;
        transition: opacity 0.2s, transform 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        position: relative;
        overflow: hidden;
        min-height: 44px;
        touch-action: manipulation;
        margin-top: 4px;
    }

    .lp-btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .lp-btn:active {
        transform: translateY(0);
        opacity: 1;
    }

    .lp-btn i {
        font-size: 17px;
    }

    .lp-btn-shine {
        position: absolute;
        top: 0;
        left: -80%;
        width: 55%;
        height: 100%;
        background: linear-gradient(120deg, transparent 0%, rgba(255, 255, 255, 0.18) 50%, transparent 100%);
        transform: skewX(-20deg);
        animation: lp-shine 3.5s ease-in-out infinite;
    }

    @keyframes lp-shine {
        0% {
            left: -80%;
        }

        55% {
            left: 130%;
        }

        100% {
            left: 130%;
        }
    }

    /* ─── Card footer ─── */
    .lp-card-footer {
        background: #fdf7e8;
        border-top: 1px solid #ede5d0;
        padding: 10px 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .lp-footer-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #d4a84a;
        opacity: 0.6;
        flex-shrink: 0;
    }

    .lp-footer-text {
        font-size: 0.67rem;
        color: #b8860b;
        font-weight: 700;
        letter-spacing: 0.7px;
        text-transform: uppercase;
    }
</style>

<div class="lp-root">

    <!-- Decorative blobs -->
    <div class="lp-bg-shape lp-bg-shape-1"></div>
    <div class="lp-bg-shape lp-bg-shape-2"></div>
    <div class="lp-bg-shape lp-bg-shape-3"></div>

    <!-- Geometric SVG shapes -->
    <svg class="lp-bg-svg" viewBox="0 0 900 600" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true">
        <polygon points="860,0 900,0 900,70" fill="rgba(255,220,100,0.12)" />
        <polygon points="0,530 0,600 100,600" fill="rgba(255,220,100,0.10)" />
        <polygon points="380,0 415,0 397,24" fill="rgba(255,220,100,0.10)" />
        <polygon points="880,470 900,470 900,510" fill="rgba(255,220,100,0.09)" />
        <rect x="52" y="66" width="14" height="14" rx="2" fill="rgba(255,220,100,0.20)" transform="rotate(45,59,73)" />
        <rect x="828" y="308" width="11" height="11" rx="1" fill="rgba(255,220,100,0.17)"
            transform="rotate(45,833,313)" />
        <rect x="190" y="525" width="9" height="9" rx="1" fill="rgba(255,220,100,0.14)"
            transform="rotate(45,194,529)" />
        <circle cx="775" cy="62" r="4" fill="rgba(255,220,100,0.28)" />
        <circle cx="95" cy="435" r="3" fill="rgba(255,220,100,0.22)" />
        <circle cx="510" cy="568" r="4" fill="rgba(255,220,100,0.16)" />
        <line x1="710" y1="0" x2="900" y2="190" stroke="rgba(255,220,100,0.10)" stroke-width="1" />
        <line x1="0" y1="430" x2="160" y2="600" stroke="rgba(255,220,100,0.09)" stroke-width="1" />
    </svg>

    <!-- Change Password card -->
    <div class="lp-card">

        <!-- Header -->
        <div class="lp-card-top">
            <div class="lp-top-diamond" aria-hidden="true"></div>
            <div class="lp-top-diamond-2" aria-hidden="true"></div>
            <div class="lp-icon-circle" aria-hidden="true">
                <i class="ti ti-shield-lock"></i>
            </div>
            <h1 class="lp-org-name">Change Password</h1>
            <p class="lp-org-sub">Set a new secure password for your account</p>
        </div>

        <!-- Body -->
        <div class="lp-card-body">

            <div class="lp-section-label" aria-hidden="true">
                <div class="lp-section-line"></div>
                <span class="lp-section-text">New Credentials</span>
                <div class="lp-section-line"></div>
            </div>

            <form method="post" action="<?php echo base_url('/accounts/submitchangepassword'); ?>" id="change-pwd-form">

                <!-- New Password -->
                <div class="lp-field">
                    <label class="lp-label" for="id_password">
                        <i class="ti ti-lock" aria-hidden="true"></i> New Password
                    </label>
                    <div class="lp-input-wrap">
                        <i class="ti ti-lock lp-input-icon" aria-hidden="true"></i>
                        <input class="lp-input" type="password" id="id_password" name="password"
                            placeholder="Min. 8 characters" required minlength="8" autocomplete="new-password"
                            oninput="lpStrength(this.value)">
                        <button type="button" class="lp-input-suffix" aria-label="Toggle new password visibility"
                            onclick="lpToggle('id_password', this)">
                            <i class="ti ti-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <!-- Strength meter -->
                    <div class="lp-strength" id="lp-strength">
                        <div class="lp-strength-bar">
                            <div class="lp-strength-fill" id="lp-strength-fill"></div>
                        </div>
                        <span class="lp-strength-text" id="lp-strength-text">Enter a password</span>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="lp-field">
                    <label class="lp-label" for="id_confirm_password">
                        <i class="ti ti-lock-check" aria-hidden="true"></i> Confirm Password
                    </label>
                    <div class="lp-input-wrap">
                        <i class="ti ti-lock-check lp-input-icon" aria-hidden="true"></i>
                        <input class="lp-input" type="password" id="id_confirm_password" name="confirm_password"
                            placeholder="Re-enter new password" required minlength="8" autocomplete="new-password"
                            oninput="lpMatch()">
                        <button type="button" class="lp-input-suffix" aria-label="Toggle confirm password visibility"
                            onclick="lpToggle('id_confirm_password', this)">
                            <i class="ti ti-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <!-- Match indicator -->
                    <div class="lp-match" id="lp-match" role="status" aria-live="polite">
                        <i class="ti ti-circle-check" aria-hidden="true"></i>
                        <span id="lp-match-text"></span>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="lp-btn">
                    <div class="lp-btn-shine" aria-hidden="true"></div>
                    <i class="ti ti-shield-check" aria-hidden="true"></i>
                    Update Password
                </button>

            </form>
        </div><!-- /lp-card-body -->

        <!-- Footer -->
        <div class="lp-card-footer">
            <div class="lp-footer-dot" aria-hidden="true"></div>
            <span class="lp-footer-text">Protected &amp; Secure</span>
            <div class="lp-footer-dot" aria-hidden="true"></div>
        </div>

    </div><!-- /lp-card -->
</div><!-- /lp-root -->

<script>
    /* ─── Navbar height ─── */
    (function () {
        var selectors = ['.navbar', 'nav.navbar', 'header nav', '#mainNav', '.top-navbar', '.main-header'];
        var navH = 0;
        for (var i = 0; i < selectors.length; i++) {
            var el = document.querySelector(selectors[i]);
            if (el && el.offsetHeight > 0) { navH = el.offsetHeight; break; }
        }
        if (navH > 0) {
            document.documentElement.style.setProperty('--lp-nav-h', navH + 'px');
        }
    })();

    /* ─── Toggle password visibility ─── */
    function lpToggle(fieldId, btn) {
        var field = document.getElementById(fieldId);
        var icon = btn.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.className = 'ti ti-eye-off';
        } else {
            field.type = 'password';
            icon.className = 'ti ti-eye';
        }
    }

    /* ─── Password strength meter ─── */
    function lpStrength(val) {
        var fill = document.getElementById('lp-strength-fill');
        var text = document.getElementById('lp-strength-text');
        var score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        var levels = [
            { w: '0%', bg: '#e0d4b8', t: 'Enter a password', c: '#9a8060' },
            { w: '25%', bg: '#e24b4a', t: 'Weak — add more variety', c: '#b91c1c' },
            { w: '50%', bg: '#ef9f27', t: 'Fair — getting better', c: '#b8860b' },
            { w: '75%', bg: '#c9a227', t: 'Good — almost there', c: '#78520a' },
            { w: '100%', bg: '#3b6d11', t: 'Strong password', c: '#3b6d11' }
        ];
        var lvl = levels[score] || levels[0];
        fill.style.width = lvl.w;
        fill.style.background = lvl.bg;
        text.textContent = lvl.t;
        text.style.color = lvl.c;

        lpMatch(); /* re-check match whenever password changes */
    }

    /* ─── Passwords match indicator ─── */
    function lpMatch() {
        var pwd = document.getElementById('id_password').value;
        var conf = document.getElementById('id_confirm_password').value;
        var el = document.getElementById('lp-match');
        var icon = el.querySelector('i');
        var span = document.getElementById('lp-match-text');

        if (!conf) { el.style.display = 'none'; return; }
        el.style.display = 'flex';

        if (pwd === conf) {
            el.style.color = '#3b6d11';
            icon.className = 'ti ti-circle-check';
            span.textContent = 'Passwords match';
        } else {
            el.style.color = '#b91c1c';
            icon.className = 'ti ti-circle-x';
            span.textContent = 'Passwords do not match';
        }
    }

    /* ─── Form validation before submit ─── */
    document.getElementById('change-pwd-form').addEventListener('submit', function (e) {
        var pwd = document.getElementById('id_password').value;
        var conf = document.getElementById('id_confirm_password').value;

        if (pwd.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters.');
            return;
        }
        if (pwd !== conf) {
            e.preventDefault();
            alert('Passwords do not match. Please check and try again.');
        }
    });
</script>