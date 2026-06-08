<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
  $qh_prefix = isset($qh_prefix) && trim((string)$qh_prefix) !== '' ? trim((string)$qh_prefix) : 'admin';
  $qh_home_url = base_url($qh_prefix);
  $qh_scheme_base = $qh_prefix . '/qardanhasana';

  $qh_schemes = isset($qh_schemes) && is_array($qh_schemes) && !empty($qh_schemes)
    ? array_values(array_map(function($s){ return strtolower(trim((string)$s)); }, $qh_schemes))
    : ['mohammedi', 'taher', 'husain'];

  $qh_scheme_meta = [
    'mohammedi' => [
      'cls'   => 'qh-mohammedi',
      'icon'  => 'fa fa-hand-o-up',
      'title' => 'Mohammedi Scheme',
      'desc'  => 'Miqaat-wise collection records',
      'accent'=> '#1a7850',
      'accent2'=> '#0d5c38',
    ],
    'taher' => [
      'cls'   => 'qh-taher',
      'icon'  => 'fa fa-money',
      'title' => 'Taher Scheme',
      'desc'  => 'Per-member unit contribution',
      'accent'=> '#1d4ed8',
      'accent2'=> '#1e3a8a',
    ],
    'husain' => [
      'cls'   => 'qh-husain',
      'icon'  => 'fa fa-handshake-o',
      'title' => 'Husain Scheme',
      'desc'  => 'Deposit & maturity tracking',
      'accent'=> '#b45309',
      'accent2'=> '#7c2d12',
    ],
  ];

  $qh_scheme_totals = (isset($qh_scheme_totals) && is_array($qh_scheme_totals)) ? $qh_scheme_totals : [];
  $qh_total_all = isset($qh_total_all) ? (float)$qh_total_all : null;
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
/* ═══════════════════════════════════════
   QARDAN HASANA — OVERVIEW PAGE
   Matches Umoor dashboard gold theme
   ═══════════════════════════════════════ */
:root {
  --gold:       #b8860b;
  --gold-light: #e6c84a;
  --gold-muted: #f5e9c0;
  --bg:         #faf7f0;
  --surface:    #ffffff;
  --surface-2:  #f7f4ec;
  --border:     #e8e0cc;
  --text-1:     #1a1610;
  --text-2:     #5a5244;
  --text-3:     #9c8f7a;
  --green:      #1a6645;
  --green-bg:   #eaf4ee;
  --blue:       #1d4ed8;
  --blue-bg:    #eff6ff;
  --amber:      #b45309;
  --amber-bg:   #fffbeb;
  --shadow:     0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
  --shadow-lg:  0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
}

#qhApp, #qhApp *, #qhApp *::before, #qhApp *::after { box-sizing: border-box; }
#qhApp {
  font-family: 'Plus Jakarta Sans', sans-serif;
  color: var(--text-1);
  background: var(--bg);
  min-height: 100vh;
  padding: 22px 22px 60px;
}

/* ── Back button ── */
.qh-back-btn {
  display: inline-flex; align-items: center; gap: 7px;
  background: var(--surface); border: 1.5px solid var(--border);
  border-radius: 9px; padding: 7px 14px;
  font-size: .80rem; font-weight: 600; color: var(--text-2);
  text-decoration: none; transition: all .15s;
  margin-bottom: 20px;
}
.qh-back-btn:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }

/* ── Hero banner ── */
.qh-hero {
  background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
  border-radius: 22px; padding: 26px 28px;
  position: relative; overflow: hidden;
  display: flex; align-items: center; justify-content: space-between; gap: 16px;
  margin-bottom: 28px;
  box-shadow: var(--shadow-lg);
}
.qh-hero::before {
  content: ''; position: absolute; inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}
.qh-hero::after {
  content: ''; position: absolute; right: -50px; top: -50px;
  width: 220px; height: 220px;
  background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
  pointer-events: none;
}
.qh-hero-left { position: relative; z-index: 1; }
.qh-hero-eyebrow {
  font-size: .66rem; font-weight: 700; letter-spacing: 1.4px;
  text-transform: uppercase; color: rgba(255,255,255,.6); margin-bottom: 5px;
}
.qh-hero-title {
  font-family: 'Literata', Georgia, serif;
  font-size: 1.75rem; font-weight: 600; color: #fff; line-height: 1.15; margin: 0 0 4px;
}
.qh-hero-sub { font-size: .82rem; color: rgba(255,255,255,.7); font-weight: 500; margin: 0; }
.qh-hero-badge {
  position: relative; z-index: 1; flex-shrink: 0;
  background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
  border-radius: 16px; padding: 14px 20px;
  backdrop-filter: blur(6px); text-align: center;
}
.qh-hero-badge-val { font-size: 1.6rem; font-weight: 800; color: #fff; display: block; line-height: 1; }
.qh-hero-badge-lbl { font-size: .62rem; font-weight: 700; color: rgba(255,255,255,.65); letter-spacing: .5px; text-transform: uppercase; margin-top: 4px; display: block; }

/* ── Section title ── */
.qh-section-label {
  font-size: .6rem; font-weight: 800; letter-spacing: 1.1px;
  text-transform: uppercase; color: var(--text-3);
  margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
}
.qh-section-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }

/* ── Scheme cards grid ── */
.qh-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 18px;
}

/* ── Individual scheme card ── */
.qh-scheme-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 18px;
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: transform .2s, box-shadow .2s;
  display: flex; flex-direction: column;
  text-decoration: none; color: inherit;
}
.qh-scheme-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); text-decoration: none; }

.qh-scheme-card-top {
  padding: 24px 22px 20px;
  position: relative; overflow: hidden;
  display: flex; flex-direction: column; gap: 12px;
}
.qh-scheme-card-top::before {
  content: ''; position: absolute; right: -30px; top: -30px;
  width: 130px; height: 130px;
  background: radial-gradient(circle, rgba(255,255,255,.14) 0%, transparent 70%);
  pointer-events: none;
}

.qh-scheme-card.qh-mohammedi .qh-scheme-card-top { background: linear-gradient(135deg, #1a7850 0%, #0d5c38 100%); }
.qh-scheme-card.qh-taher     .qh-scheme-card-top { background: linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%); }
.qh-scheme-card.qh-husain    .qh-scheme-card-top { background: linear-gradient(135deg, #c97c1a 0%, #7c2d12 100%); }

.qh-card-icon-wrap {
  width: 50px; height: 50px; border-radius: 14px;
  background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.22);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.35rem; color: #fff;
  flex-shrink: 0;
}
.qh-card-title { font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0; }
.qh-card-desc  { font-size: .76rem; color: rgba(255,255,255,.7); margin: 0; }

.qh-card-amount-block { margin-top: 4px; }
.qh-card-amount-label { font-size: .64rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: rgba(255,255,255,.6); margin-bottom: 2px; }
.qh-card-amount-val { font-size: 1.5rem; font-weight: 800; color: #fff; line-height: 1; }

/* ── Card bottom ── */
.qh-scheme-card-bottom {
  padding: 14px 22px;
  display: flex; align-items: center; justify-content: space-between;
  border-top: 1px solid var(--border);
}
.qh-goto-label { font-size: .78rem; font-weight: 600; color: var(--text-2); }
.qh-goto-arrow {
  width: 30px; height: 30px; border-radius: 8px;
  background: var(--surface-2); border: 1px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  font-size: .75rem; color: var(--text-2);
  transition: background .15s, color .15s;
}
.qh-scheme-card:hover .qh-goto-arrow { background: var(--gold-muted); color: var(--gold); border-color: var(--gold); }

@media (max-width: 600px) {
  #qhApp { padding: 14px 12px 40px; }
  .qh-hero { flex-direction: column; align-items: flex-start; gap: 14px; }
  .qh-hero-badge { align-self: stretch; }
}
</style>

<div id="qhApp">
  <!-- Back button -->
  <a href="<?php echo $qh_home_url; ?>" class="qh-back-btn">
    <i class="fa fa-arrow-left"></i> Back to Dashboard
  </a>

  <!-- Hero banner -->
  <div class="qh-hero">
    <div class="qh-hero-left">
      <div class="qh-hero-eyebrow">Finance Module</div>
      <h1 class="qh-hero-title">Qardan Hasana</h1>
      <p class="qh-hero-sub">Loan &amp; collection scheme overview</p>
    </div>
    <?php if ($qh_total_all !== null): ?>
    <div class="qh-hero-badge">
      <span class="qh-hero-badge-val">₹<?php echo function_exists('format_inr') ? format_inr($qh_total_all, 0) : number_format($qh_total_all, 0); ?></span>
      <span class="qh-hero-badge-lbl">Grand Total</span>
    </div>
    <?php endif; ?>
  </div>

  <!-- Schemes -->
  <div class="qh-section-label"><i class="fa fa-th-large"></i> Select a Scheme</div>

  <div class="qh-grid">
    <?php foreach ($qh_schemes as $sc): ?>
      <?php if (!isset($qh_scheme_meta[$sc])) continue; ?>
      <?php $m = $qh_scheme_meta[$sc]; ?>
      <a href="<?php echo base_url($qh_scheme_base . '/' . $sc); ?>" class="qh-scheme-card <?php echo $m['cls']; ?>">
        <div class="qh-scheme-card-top">
          <div class="qh-card-icon-wrap"><i class="<?php echo $m['icon']; ?>"></i></div>
          <div>
            <div class="qh-card-title"><?php echo htmlspecialchars((string)$m['title']); ?></div>
            <div class="qh-card-desc"><?php echo htmlspecialchars((string)$m['desc']); ?></div>
          </div>
          <?php if (array_key_exists($sc, $qh_scheme_totals)): ?>
          <div class="qh-card-amount-block">
            <div class="qh-card-amount-label">Total Collected</div>
            <div class="qh-card-amount-val">₹<?php echo function_exists('format_inr') ? format_inr((float)$qh_scheme_totals[$sc], 0) : number_format((float)$qh_scheme_totals[$sc], 0); ?></div>
          </div>
          <?php endif; ?>
        </div>
        <div class="qh-scheme-card-bottom">
          <span class="qh-goto-label">View Scheme Records</span>
          <span class="qh-goto-arrow"><i class="fa fa-arrow-right"></i></span>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</div>
