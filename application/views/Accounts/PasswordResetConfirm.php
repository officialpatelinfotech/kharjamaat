<?php
/*
 | ---------------------------------------------------------------
 |  Anjuman-e-Saifee — Password Reset Confirmation Page
 |  CodeIgniter view — drop into application/views/
 |  Expects: $user_email (string)
 | ---------------------------------------------------------------
*/
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
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
    width: 500px; height: 500px;
    top: -200px; left: -180px;
    background: radial-gradient(circle, rgba(184,134,11,0.20) 0%, transparent 70%);
  }
  .lp-bg-shape-2 {
    width: 420px; height: 420px;
    bottom: -150px; right: -120px;
    background: radial-gradient(circle, rgba(201,162,39,0.18) 0%, transparent 70%);
  }
  .lp-bg-shape-3 {
    width: 220px; height: 220px;
    top: 38%; right: 8%;
    background: radial-gradient(circle, rgba(184,134,11,0.12) 0%, transparent 70%);
  }

  .lp-bg-svg {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
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
    box-shadow: 0 12px 48px rgba(0,0,0,0.28), 0 2px 8px rgba(0,0,0,0.12);
    border: 1px solid rgba(184,134,11,0.22);
    max-height: calc(100dvh - var(--lp-nav-h, 57px) - 2rem);
    overflow-y: auto;
    overflow-x: hidden;
  }

  @media (max-width: 480px) {
    .lp-card { max-width: 100%; border-radius: 16px; }
    .lp-root { padding: 0.75rem; }
  }
  @media (max-width: 360px) {
    .lp-card { border-radius: 12px; }
    .lp-root { padding: 0.5rem; }
  }
  @media (max-height: 560px) {
    .lp-card-top   { padding: 16px 24px 14px !important; }
    .lp-org-name   { font-size: 1.15rem !important; }
    .lp-card-body  { padding: 16px 24px 18px !important; }
    .lp-check-circle { width: 44px !important; height: 44px !important; margin-bottom: 8px !important; }
    .lp-check-circle i { font-size: 22px !important; }
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
    top: -45px; right: -45px;
    width: 150px; height: 150px;
    border-radius: 50%;
    border: 24px solid rgba(255,255,255,0.08);
  }
  .lp-card-top::after {
    content: '';
    position: absolute;
    bottom: -30px; left: 10px;
    width: 100px; height: 100px;
    border-radius: 50%;
    border: 16px solid rgba(255,255,255,0.06);
  }
  .lp-top-diamond {
    position: absolute;
    top: 14px; left: 22px;
    width: 13px; height: 13px;
    background: rgba(255,255,255,0.18);
    transform: rotate(45deg);
    border-radius: 2px;
  }
  .lp-top-diamond-2 {
    position: absolute;
    bottom: 14px; right: 30px;
    width: 9px; height: 9px;
    background: rgba(255,255,255,0.14);
    transform: rotate(45deg);
    border-radius: 2px;
  }

  .lp-check-circle {
    width: 60px; height: 60px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    border: 2px solid rgba(255,255,255,0.30);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    position: relative;
    z-index: 1;
  }
  .lp-check-circle i { font-size: 28px; color: #fff; }

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
    color: rgba(255,255,255,0.68);
    font-weight: 500;
    margin: 0;
    position: relative;
    z-index: 1;
    letter-spacing: 0.2px;
  }

  /* ─── Card body ─── */
  .lp-card-body { padding: 22px 28px 26px; background: #fff; }

  @media (max-width: 360px) {
    .lp-card-body   { padding: 18px 18px 22px; }
    .lp-card-top    { padding: 22px 18px 18px; }
    .lp-card-footer { padding: 10px 18px; }
  }

  .lp-section-label {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
  }
  .lp-section-line { flex: 1; height: 1px; background: #ede5d0; }
  .lp-section-text {
    font-size: 0.67rem;
    font-weight: 700;
    letter-spacing: 1.1px;
    color: #b8860b;
    text-transform: uppercase;
    white-space: nowrap;
  }

  /* ─── Message box ─── */
  .lp-msg-box {
    background: #fdf7e8;
    border: 1px solid rgba(184,134,11,0.25);
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 16px;
  }
  .lp-msg-box p {
    font-size: 0.85rem;
    color: #5a4a2a;
    margin: 0 0 8px;
    line-height: 1.55;
  }
  .lp-msg-box p:last-child { margin: 0; }
  .lp-email-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #b8860b;
    font-weight: 700;
    font-size: 0.88rem;
    text-decoration: none;
    word-break: break-all;
  }
  .lp-email-link:hover { color: #78520a; text-decoration: underline; }
  .lp-email-link i { font-size: 15px; flex-shrink: 0; }

  /* ─── OR divider ─── */
  .lp-divider {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 14px 0;
  }
  .lp-divider-line { flex: 1; height: 1px; background: #ede5d0; }
  .lp-divider-text { font-size: 0.72rem; color: #c4a855; font-weight: 600; }

  /* ─── Contact box ─── */
  .lp-contact-box {
    background: #fff;
    border: 1.5px solid #e0d4b8;
    border-radius: 10px;
    padding: 12px 14px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
  }
  .lp-contact-box > i { font-size: 20px; color: #b8860b; flex-shrink: 0; margin-top: 1px; }
  .lp-contact-text {
    font-size: 0.82rem;
    color: #5a4a2a;
    font-weight: 500;
    line-height: 1.5;
  }
  .lp-contact-text a {
    color: #b8860b;
    font-weight: 700;
    text-decoration: none;
  }
  .lp-contact-text a:hover { color: #78520a; text-decoration: underline; }

  /* ─── Redirect timer ─── */
  .lp-timer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 16px;
    font-size: 0.75rem;
    color: #9a8060;
    font-weight: 500;
  }
  .lp-timer i { font-size: 14px; color: #c4a855; }

  /* ─── Progress bar ─── */
  .lp-progress {
    height: 3px;
    background: #ede5d0;
    border-radius: 2px;
    margin-top: 8px;
    overflow: hidden;
  }
  .lp-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #78520a, #c9a227);
    border-radius: 2px;
    width: 100%;
    animation: lp-countdown 8s linear forwards;
  }
  @keyframes lp-countdown { from { width: 100%; } to { width: 0%; } }

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
    width: 4px; height: 4px;
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
  <svg class="lp-bg-svg"
       viewBox="0 0 900 600" preserveAspectRatio="xMidYMid slice"
       xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <polygon points="860,0 900,0 900,70"      fill="rgba(255,220,100,0.12)"/>
    <polygon points="0,530 0,600 100,600"     fill="rgba(255,220,100,0.10)"/>
    <polygon points="380,0 415,0 397,24"      fill="rgba(255,220,100,0.10)"/>
    <polygon points="880,470 900,470 900,510" fill="rgba(255,220,100,0.09)"/>
    <rect x="52"  y="66"  width="14" height="14" rx="2" fill="rgba(255,220,100,0.20)" transform="rotate(45,59,73)"/>
    <rect x="828" y="308" width="11" height="11" rx="1" fill="rgba(255,220,100,0.17)" transform="rotate(45,833,313)"/>
    <rect x="190" y="525" width="9"  height="9"  rx="1" fill="rgba(255,220,100,0.14)" transform="rotate(45,194,529)"/>
    <circle cx="775" cy="62"  r="4" fill="rgba(255,220,100,0.28)"/>
    <circle cx="95"  cy="435" r="3" fill="rgba(255,220,100,0.22)"/>
    <circle cx="510" cy="568" r="4" fill="rgba(255,220,100,0.16)"/>
    <line x1="710" y1="0"   x2="900" y2="190" stroke="rgba(255,220,100,0.10)" stroke-width="1"/>
    <line x1="0"   y1="430" x2="160" y2="600" stroke="rgba(255,220,100,0.09)" stroke-width="1"/>
  </svg>

  <!-- Confirmation card -->
  <div class="lp-card">

    <!-- Header -->
    <div class="lp-card-top">
      <div class="lp-top-diamond"   aria-hidden="true"></div>
      <div class="lp-top-diamond-2" aria-hidden="true"></div>
      <div class="lp-check-circle" aria-hidden="true">
        <i class="ti ti-mail-check"></i>
      </div>
      <h1 class="lp-org-name">Password Reset Sent!</h1>
      <p class="lp-org-sub">Check your inbox for further instructions</p>
    </div>

    <!-- Body -->
    <div class="lp-card-body">

      <div class="lp-section-label" aria-hidden="true">
        <div class="lp-section-line"></div>
        <span class="lp-section-text">Email Dispatched</span>
        <div class="lp-section-line"></div>
      </div>

      <div class="lp-msg-box">
        <p>Your password has been reset and sent to your registered email address:</p>
        <p>
          <a class="lp-email-link" href="mailto:<?php echo htmlspecialchars($user_email); ?>">
            <i class="ti ti-mail" aria-hidden="true"></i>
            <?php echo htmlspecialchars($user_email); ?>
          </a>
        </p>
      </div>

      <div class="lp-divider">
        <div class="lp-divider-line"></div>
        <span class="lp-divider-text">OR</span>
        <div class="lp-divider-line"></div>
      </div>

      <div class="lp-contact-box">
        <i class="ti ti-building-community" aria-hidden="true"></i>
        <div class="lp-contact-text">
          If you haven't received an email, contact the Jamaat office or write to
          <a href="mailto:info@kharjamaat.in">info@kharjamaat.in</a>
        </div>
      </div>

      <div class="lp-timer">
        <i class="ti ti-clock" aria-hidden="true"></i>
        <span id="lp-timer-text">Redirecting to login in 8 seconds…</span>
      </div>
      <div class="lp-progress">
        <div class="lp-progress-bar" id="lp-progress-bar"></div>
      </div>

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

  /* ─── Countdown timer with live text update ─── */
  var seconds = 8;
  var timerEl = document.getElementById('lp-timer-text');
  var interval = setInterval(function () {
    seconds--;
    if (seconds <= 0) {
      clearInterval(interval);
      window.location.href = '<?php echo base_url('accounts'); ?>';
    } else {
      timerEl.textContent = 'Redirecting to login in ' + seconds + ' second' + (seconds === 1 ? '' : 's') + '…';
    }
  }, 1000);
</script>