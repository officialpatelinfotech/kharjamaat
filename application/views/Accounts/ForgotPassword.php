<?php
/*
 | ---------------------------------------------------------------
 |  Anjuman-e-Saifee — Forgot Password Page
 |  CodeIgniter view — drop into application/views/
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
    .lp-card-top { padding: 16px 24px 14px !important; }
    .lp-org-name  { font-size: 1.15rem !important; }
    .lp-card-body { padding: 16px 24px 18px !important; }
    .lp-field     { margin-bottom: 10px !important; }
    .lp-icon-circle { width: 40px !important; height: 40px !important; margin-bottom: 8px !important; }
    .lp-icon-circle i { font-size: 20px !important; }
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
  .lp-icon-circle {
    width: 52px; height: 52px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    border: 2px solid rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    position: relative;
    z-index: 1;
  }
  .lp-icon-circle i { font-size: 24px; color: #fff; }

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

  /* ─── Info banner ─── */
  .lp-info {
    background: #fdf7e8;
    border: 1px solid rgba(184,134,11,0.25);
    border-radius: 8px;
    padding: 9px 12px;
    font-size: 0.79rem;
    color: #78520a;
    font-weight: 500;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 7px;
  }
  .lp-info i { font-size: 15px; flex-shrink: 0; color: #b8860b; }

  /* ─── Form fields ─── */
  .lp-field { margin-bottom: 14px; }
  .lp-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.76rem;
    font-weight: 700;
    color: #5a4a2a;
    margin-bottom: 6px;
  }
  .lp-label i { font-size: 14px; color: #b8860b; }

  .lp-input-wrap { position: relative; }
  .lp-input {
    width: 100%;
    padding: 10px 14px 10px 40px;
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
    box-shadow: 0 0 0 3px rgba(184,134,11,0.12);
    background: #fff;
  }
  .lp-input::placeholder { color: #c4b08a; }

  .lp-input-icon {
    position: absolute;
    left: 12px; top: 50%;
    transform: translateY(-50%);
    font-size: 15px;
    color: #c4a855;
    pointer-events: none;
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
  }
  .lp-btn:hover  { opacity: 0.9; transform: translateY(-1px); }
  .lp-btn:active { transform: translateY(0); opacity: 1; }
  .lp-btn i { font-size: 17px; }

  .lp-btn-shine {
    position: absolute;
    top: 0; left: -80%;
    width: 55%; height: 100%;
    background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,0.18) 50%, transparent 100%);
    transform: skewX(-20deg);
    animation: lp-shine 3.5s ease-in-out infinite;
  }
  @keyframes lp-shine {
    0%   { left: -80%; }
    55%  { left: 130%; }
    100% { left: 130%; }
  }

  /* ─── Back link ─── */
  .lp-back {
    display: block;
    text-align: center;
    margin-top: 12px;
    font-size: 0.78rem;
    color: #b8860b;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.15s;
    padding: 4px 0;
  }
  .lp-back:hover { color: #78520a; text-decoration: underline; }
  .lp-back i { font-size: 12px; vertical-align: -1px; }

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

  <!-- Forgot Password card -->
  <div class="lp-card">

    <!-- Header -->
    <div class="lp-card-top">
      <div class="lp-top-diamond"   aria-hidden="true"></div>
      <div class="lp-top-diamond-2" aria-hidden="true"></div>
      <div class="lp-icon-circle" aria-hidden="true">
        <i class="ti ti-lock-question"></i>
      </div>
      <h1 class="lp-org-name">Forgot Password?</h1>
      <p class="lp-org-sub">Enter your ITS ID to receive reset instructions</p>
    </div>

    <!-- Body -->
    <div class="lp-card-body">

      <div class="lp-section-label" aria-hidden="true">
        <div class="lp-section-line"></div>
        <span class="lp-section-text">Account Recovery</span>
        <div class="lp-section-line"></div>
      </div>

      <div class="lp-info">
        <i class="ti ti-info-circle" aria-hidden="true"></i>
        We'll send a reset link to your registered email address.
      </div>

      <form method="post" action="<?php echo base_url('/accounts/submitForgotPassword'); ?>">

        <div class="lp-field">
          <label class="lp-label" for="id_username">
            <i class="ti ti-id-badge-2" aria-hidden="true"></i> ITS ID / Username
          </label>
          <div class="lp-input-wrap">
            <i class="ti ti-user lp-input-icon" aria-hidden="true"></i>
            <input class="lp-input"
                   type="text"
                   id="id_username"
                   name="username"
                   placeholder="Enter your ITS ID"
                   autofocus
                   autocomplete="username"
                   required
                   maxlength="254">
          </div>
        </div>

        <button type="submit" class="lp-btn">
          <div class="lp-btn-shine" aria-hidden="true"></div>
          <i class="ti ti-send" aria-hidden="true"></i>
          Send Reset Link
        </button>

        <a href="<?php echo base_url('/accounts/login'); ?>" class="lp-back">
          <i class="ti ti-arrow-left" aria-hidden="true"></i>
          Back to Sign In
        </a>

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
</script>