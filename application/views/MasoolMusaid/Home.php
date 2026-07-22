<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* ═══════════════════════════════════════════════════
   GOLD THEME — scoped to #anjApp
   ═══════════════════════════════════════════════════ */
:root {
  --gold:        #b8860b;
  --gold-light:  #e6c84a;
  --gold-muted:  #f5e9c0;
  --bg:          #faf7f0;
  --surface:     #ffffff;
  --surface-2:   #f7f4ec;
  --border:      #e8e0cc;
  --border-light:#f0ece0;
  --text-1:      #1a1610;
  --text-2:      #5a5244;
  --text-3:      #9c8f7a;
  --green:       #1a6645;
  --green-bg:    #eaf4ee;
  --red:         #b91c1c;
  --red-bg:      #fef2f2;
  --blue:        #1d4ed8;
  --blue-bg:     #eff6ff;
  --amber:       #b45309;
  --amber-bg:    #fffbeb;
  --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
  --shadow:      0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
  --shadow-lg:   0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
}

#anjApp, #anjApp *, #anjApp *::before, #anjApp *::after { box-sizing: border-box; }
#anjApp { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); background: var(--bg); min-height: 100vh; }
#anjApp a { color: inherit; }

/* ── Layout ── */
#anjApp .anj-root { display: flex; min-height: 100vh; padding-top: 57px; }

#anjApp .anj-sidebar {
  width: 270px; flex-shrink: 0;
  position: sticky; top: 57px; height: calc(100vh - 57px);
  overflow-y: auto; background: var(--surface);
  border-right: 1px solid var(--border);
  padding: 16px 12px 32px;
  scrollbar-width: thin; scrollbar-color: var(--border) transparent;
  z-index: 100;
}

#anjApp .anj-content { flex: 1; min-width: 0; padding: 22px 22px 60px; }

/* ── Dashboard header ── */
#anjApp .anj-header { margin-bottom: 22px; }
#anjApp .anj-header-inner {
  background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
  border-radius: 22px; padding: 22px 26px;
  position: relative; overflow: hidden;
  display: flex; align-items: center; justify-content: space-between; gap: 16px;
}
#anjApp .anj-header-inner::before {
  content: ''; position: absolute; inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}
#anjApp .anj-header-inner::after {
  content: ''; position: absolute; right: -50px; top: -50px;
  width: 220px; height: 220px;
  background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
  pointer-events: none;
}
#anjApp .anj-title-group { position: relative; z-index: 1; }
#anjApp .anj-eyebrow { font-size: .67rem; font-weight: 700; letter-spacing: 1.4px; text-transform: uppercase; color: rgba(255,255,255,.6); margin-bottom: 4px; }
#anjApp .anj-title { font-family: 'Literata', Georgia, serif; font-size: 1.6rem; font-weight: 600; color: #fff; line-height: 1.15; margin: 0; }
#anjApp .anj-title span { color: rgba(255,255,255,.72); font-size: 1rem; font-weight: 500; }
#anjApp .anj-badge {
  position: relative; z-index: 1;
  background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
  border-radius: 14px; padding: 10px 16px; backdrop-filter: blur(6px);
  text-align: center; flex-shrink: 0;
}
#anjApp .anj-badge-val { font-size: 1.5rem; font-weight: 800; color: #fff; line-height: 1; display: block; }
#anjApp .anj-badge-lbl { font-size: .64rem; font-weight: 700; color: rgba(255,255,255,.65); letter-spacing: .5px; text-transform: uppercase; margin-top: 3px; display: block; }

/* ── Sidebar nav ── */
#anjApp .anj-sidebar .sb-brand { font-weight: 800; font-size: .84rem; color: var(--text-2); margin-bottom: 12px; padding: 0 4px; display: flex; align-items: center; gap: 8px; }
#anjApp .anj-sidebar .sb-brand .sb-ico { width: 26px; height: 26px; border-radius: 7px; background: var(--gold-muted); color: var(--gold); display: inline-flex; align-items: center; justify-content: center; font-size: .78rem; }

/* ── Sidebar menu ── */
#anjApp .sidebar-menu {
  background: transparent; border: none; border-radius: 0;
  padding: 0; position: static; box-shadow: none;
  max-height: none; overflow: visible;
}
#anjApp .sidebar-menu .menu-title { font-weight: 800; font-size: .84rem; color: var(--text-2); margin-bottom: 10px; padding: 0 4px; }
#anjApp .sidebar-menu .menu-search { display: flex; align-items: center; gap: 8px; background: var(--bg); border: 1.5px solid var(--border); border-radius: 8px; padding: 7px 10px; margin-bottom: 12px; transition: border-color .15s; }
#anjApp .sidebar-menu .menu-search:focus-within { border-color: var(--gold); }
#anjApp .sidebar-menu .menu-search i { color: var(--text-3); font-size: .8rem; }
#anjApp .sidebar-menu .menu-search input { border: none; background: transparent; outline: none; font-family: inherit; font-size: .81rem; color: var(--text-1); width: 100%; }
#anjApp .sidebar-menu .menu-search input::placeholder { color: var(--text-3); }
#anjApp .sidebar-menu .menu-search-clear { border: none; background: none; cursor: pointer; color: var(--text-3); font-size: .78rem; padding: 0; width: auto; height: auto; border-radius: 0; box-shadow: none; }

#anjApp .menu-section {
  font-size: .58rem; font-weight: 800; letter-spacing: 1.1px; text-transform: uppercase;
  color: var(--text-3); padding: 12px 6px 4px;
  display: flex; align-items: center; justify-content: space-between; cursor: pointer;
}
#anjApp .menu-section::after { content: '▾'; font-size: .62rem; transition: transform .2s; }
#anjApp .menu-section.is-collapsed::after { transform: rotate(-90deg); }

#anjApp .menu-list { list-style: none; margin: 0; padding: 0; }
#anjApp .menu-list li + li { margin-top: 2px; }

#anjApp .menu-item {
  display: flex; align-items: center; gap: 9px;
  padding: 7px 9px; border-radius: 7px;
  color: var(--text-2); font-size: .82rem; font-weight: 500;
  text-decoration: none; transition: background .14s, color .14s;
}
#anjApp .menu-item:hover { background: var(--gold-muted); color: var(--gold); text-decoration: none; }

#anjApp .menu-icon {
  width: 27px; height: 27px; border-radius: 7px;
  display: inline-flex; align-items: center; justify-content: center;
  background: var(--surface-2); color: var(--text-3); font-size: .76rem; flex-shrink: 0;
  transition: background .14s, color .14s;
}
#anjApp .menu-item:hover .menu-icon { background: var(--gold-muted); color: var(--gold); }
#anjApp .menu-label { flex: 1; white-space: normal; word-break: break-word; }

/* ── Mobile toolbar ── */
#anjApp .mob-bar {
  display: flex; align-items: center; justify-content: space-between;
  background: var(--surface); border: 1px solid var(--border);
  border-radius: 12px; padding: 9px 13px; margin-bottom: 14px; box-shadow: var(--shadow-sm);
}
#anjApp .mob-btn {
  display: inline-flex; align-items: center; gap: 7px; padding: 6px 13px;
  border-radius: 40px; background: var(--gold-muted); color: var(--gold);
  font-weight: 700; font-size: .82rem; border: 1px solid rgba(184,134,11,.25);
}
#anjApp .mob-btn .mic { width: 24px; height: 24px; border-radius: 6px; background: var(--surface); color: var(--gold); display: flex; align-items: center; justify-content: center; font-size: .78rem; }

/* ── Surface card ── */
#anjApp .chart-container {
  background: var(--surface);
  border-radius: 16px;
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
  padding: 18px 20px;
  margin-bottom: 16px;
  position: relative; overflow: hidden;
  transition: box-shadow .2s;
}
#anjApp .chart-container:hover { box-shadow: var(--shadow); }
#anjApp .chart-container.compact { padding: 14px 16px; }

/* ── Section header ── */
#anjApp .section-header-standard {
  display: flex; align-items: center; justify-content: space-between;
  padding-bottom: 10px; margin-bottom: 14px;
  border-bottom: 1.5px solid var(--border-light);
}
#anjApp .section-header-standard .section-title {
  font-size: .87rem; font-weight: 800; color: var(--text-2);
  margin: 0 !important; display: flex; align-items: center; gap: 8px;
}
#anjApp .section-header-standard .section-title i { color: var(--gold); font-size: .82rem; }

#anjApp .collapse-toggle-btn {
  border-radius: 50%; width: 28px; height: 28px; padding: 0;
  display: flex; align-items: center; justify-content: center;
  background: var(--surface-2); border: 1.5px solid var(--border); color: var(--text-3);
  transition: all .2s; flex-shrink: 0; cursor: pointer;
}
#anjApp .collapse-toggle-btn:hover { background: var(--gold-muted); color: var(--gold); border-color: var(--gold); }
#anjApp .collapse-toggle-btn i { transition: transform .22s; }
#anjApp .collapse-toggle-btn[aria-expanded="false"] i { transform: rotate(-90deg); }

/* ── Overview cards ── */
#anjApp .overview-card {
  background: var(--surface); border: 1.5px solid var(--border); border-radius: 12px;
  padding: 12px 14px; display: flex; align-items: center; gap: 11px; height: 100%;
  transition: border-color .2s, box-shadow .2s, transform .2s; position: relative; overflow: hidden;
}
#anjApp .overview-card:hover { border-color: var(--gold); box-shadow: var(--shadow); transform: translateY(-2px); }
#anjApp .overview-card::after {
  content: ''; position: absolute; bottom: 0; left: 0; right: 0;
  height: 2px; background: var(--gold); transform: scaleX(0); transition: transform .2s; transform-origin: left;
}
#anjApp .overview-card:hover::after { transform: scaleX(1); }
#anjApp .overview-card.green  { border-color: #86efac; } #anjApp .overview-card.green::after  { background: var(--green); }
#anjApp .overview-card.red    { border-color: #fca5a5; } #anjApp .overview-card.red::after    { background: var(--red); }
#anjApp .overview-icon {
  width: 36px; height: 36px; border-radius: 9px;
  display: inline-flex; align-items: center; justify-content: center;
  background: var(--gold-muted); color: var(--gold); font-size: .95rem; flex-shrink: 0;
}
#anjApp .overview-body { display: flex; flex-direction: column; min-width: 0; }
#anjApp .overview-title { font-size: .72rem; color: var(--text-3); font-weight: 600; margin: 0; line-height: 1.3; }
#anjApp .overview-value {
  font-size: 1.2rem; font-weight: 800; color: var(--text-1); line-height: 1.1; margin: 3px 0 0;
  word-break: break-all; overflow-wrap: anywhere;
}

/* ── Mini cards ── */
#anjApp .mini-card {
  background: var(--surface-2); border: 1px solid var(--border);
  border-radius: 8px; padding: 12px 10px; text-align: center;
  transition: box-shadow .14s, transform .14s;
}
#anjApp .mini-card:hover { box-shadow: var(--shadow-sm); transform: translateY(-1px); }
#anjApp .stats-value {
  font-size: clamp(.8rem, 2.5vw, 1.2rem); font-weight: 800; color: var(--text-1);
  display: block; line-height: 1; margin-bottom: 2px;
  word-break: break-all; overflow-wrap: anywhere;
}
#anjApp .stats-value.green { color: var(--green); }
#anjApp .stats-value.red   { color: var(--red); }
#anjApp .stats-label { font-size: .65rem; font-weight: 700; letter-spacing: .4px; color: var(--text-3); text-transform: uppercase; display: block; }

/* ── Section title ── */
#anjApp .section-title { font-size: 1rem; font-weight: 700; color: var(--text-1); margin-bottom: 16px; }

/* ── Hijri switcher ── */
#anjApp .hijri-switcher { display: flex; justify-content: center; align-items: center; margin: 4px 0 14px; }
#anjApp .hijri-switcher .chev-box {
  width: 40px; height: 40px; border: 1px solid var(--border);
  border-radius: 6px; display: flex; align-items: center; justify-content: center;
  background: var(--surface); color: var(--text-2); font-size: 1.1rem;
  transition: background .15s, color .15s;
}
#anjApp .hijri-switcher .chev-box:hover { background: var(--gold-muted); color: var(--gold); }
#anjApp .hijri-switcher .hijri-nav-btn.disabled { pointer-events: none; opacity: .4; }
#anjApp .hijri-title { font-weight: 700; color: var(--gold) !important; font-size: 1.1rem; }
#anjApp #hijri-current-title { margin: 0 18px; color: var(--gold) !important; font-weight: 700; font-size: 1.1rem; }

#anjApp #monthLoader { display:none; color:var(--text-3); font-size:.85rem; }

/* ── Button styles ── */
#anjApp .btn-outline-secondary { border-color: var(--border); color: var(--text-2); font-size: .78rem; }
#anjApp .btn-outline-secondary:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); }
#anjApp .btn-outline-primary { border-color: var(--blue); color: var(--blue); font-size: .78rem; }
#anjApp .btn-sm { font-size: .76rem; }

/* ── Custom modal overlays ── */
#anjApp .anj-modal-overlay {
  position: fixed; inset: 0; background: rgba(26,22,16,.45);
  display: none; align-items: center; justify-content: center;
  z-index: 2000; padding: 16px;
}
#anjApp .anj-modal-overlay.show { display: flex; }
#anjApp .anj-modal-card {
  background: var(--surface); border: 1px solid var(--border);
  border-radius: 16px; width: 100%; max-width: 560px;
  padding: 20px; box-shadow: var(--shadow-lg);
  max-height: 90vh; display: flex; flex-direction: column;
}
#anjApp .anj-modal-head {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 14px; flex-shrink: 0;
}
#anjApp .anj-modal-title { font-weight: 800; font-size: 1rem; margin: 0; color: var(--text-1); }
#anjApp .anj-modal-close {
  border: none; background: var(--surface-2); width: 32px; height: 32px;
  border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;
  cursor: pointer; color: var(--text-2); font-size: 1rem; transition: background .15s;
}
#anjApp .anj-modal-close:hover { background: var(--gold-muted); color: var(--gold); }
#anjApp .anj-modal-body { overflow-y: auto; flex: 1; }

/* ── Sidebar overlay (mobile) ── */
#anjApp .sidebar-overlay { position: fixed; inset: 0; background: rgba(26,22,16,.4); z-index: 1040; display: none; }
#anjApp .sidebar-overlay.show { display: block; }
#anjApp .sidebar-close-btn {
  position: absolute; top: 10px; right: 10px; width: 28px; height: 28px;
  border: none; border-radius: 7px; background: var(--gold-muted); color: var(--gold);
  font-size: .95rem; line-height: 1; display: none; align-items: center; justify-content: center; cursor: pointer;
}

/* ── Action buttons ── */
#anjApp .action-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}
@media (min-width: 576px) { #anjApp .action-grid { grid-template-columns: repeat(3, 1fr); } }
@media (min-width: 992px) { #anjApp .action-grid { grid-template-columns: repeat(5, 1fr); } }

#anjApp .action-btn {
  position: relative; display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 10px;
  padding: 20px 12px; border-radius: 14px; text-decoration: none;
  overflow: hidden; min-height: 110px;
  transition: transform .15s, box-shadow .15s;
  box-shadow: var(--shadow);
}
#anjApp .action-btn::after {
  content: ''; position: absolute; inset: 0;
  background: rgba(255,255,255,0); transition: background .2s;
}
#anjApp .action-btn:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); text-decoration: none; }
#anjApp .action-btn:hover::after { background: rgba(255,255,255,.1); }
#anjApp .action-btn .ab-icon {
  width: 44px; height: 44px; border-radius: 12px;
  background: rgba(255,255,255,.22); backdrop-filter: blur(4px);
  display: flex; align-items: center; justify-content: center;
  box-shadow: inset 0 0 0 1px rgba(255,255,255,.3);
  font-size: 1.3rem; color: #fff; flex-shrink: 0;
}
#anjApp .action-btn .ab-label {
  font-size: .72rem; font-weight: 700; letter-spacing: .4px;
  text-transform: uppercase; color: #fff; text-align: center; line-height: 1.3;
}

/* ── RSVP miqaat block loading state ── */
#anjApp #miqaatLoading {
  display: none; position: absolute; left: 0; right: 0; top: 0; bottom: 0;
  align-items: center; justify-content: center;
  background: rgba(255,255,255,.6); border-radius: 20px; z-index: 5;
}
#anjApp #miqaatLoading.active { display: flex; }
@keyframes miqaat-spin { to { transform: rotate(360deg); } }
#anjApp .miqaat-spinner {
  width: 32px; height: 32px; border-radius: 50%;
  border: 3px solid rgba(0,0,0,.08); border-top-color: var(--gold);
  animation: miqaat-spin 1s linear infinite;
}

/* ── Mobile ── */
@media (max-width: 991px) {
  #anjApp .anj-sidebar {
    position: fixed; top: 0; left: 0; height: 100vh;
    transform: translateX(-100%); transition: transform .25s;
    z-index: 1050; width: 290px; padding-top: 16px;
  }
  #anjApp .anj-sidebar.open { transform: translateX(0); box-shadow: var(--shadow-lg); }
  #anjApp .anj-content { padding: 12px 11px 48px; }
  #anjApp .anj-title { font-size: 1.25rem; }
  #anjApp .anj-header-inner { padding: 16px 18px; }
  #anjApp .overview-value { font-size: clamp(.78rem, 3.2vw, 1.2rem); }
  #anjApp .stats-value { font-size: clamp(.68rem, 2.5vw, 1.1rem); }
  #anjApp .sidebar-close-btn { display: inline-flex; }
}
@media (min-width: 992px) { #anjApp .mob-bar { display: none !important; } }

@media (max-width: 400px) {
  #anjApp .overview-value, #anjApp .stats-value { font-size: .72rem; letter-spacing: -.2px; }
}

/* 5-col grid */
@media (min-width: 768px) { #anjApp .col-md-5th { flex: 0 0 20%; max-width: 20%; } }

/* Member search */
#anjApp #member-search-block { overflow: visible !important; }
#anjApp .msw-label { font-size:.85rem; font-weight:700; color:var(--text-3); text-transform:uppercase; letter-spacing:.6px; margin-bottom:10px; }
#anjApp .msw-input-wrap { position:relative; display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
#anjApp .msw-input-group { position:relative; flex:1 1 250px; max-width:500px; }
#anjApp .msw-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-3); font-size:15px; pointer-events:none; }
#anjApp #mswInput { width:100%; padding:11px 38px 11px 38px; border:1.5px solid var(--border); border-radius:10px; font-size:.95rem; color:var(--text-1); background:var(--surface-2); outline:none; transition:border-color .2s,box-shadow .2s; font-family:inherit; }
#anjApp #mswInput:focus { border-color:var(--gold); box-shadow:0 0 0 3px rgba(184,134,11,.1); background:var(--surface); }
#anjApp .msw-clear-btn { position:absolute; right:10px; top:50%; transform:translateY(-50%); background:var(--border); border:none; border-radius:6px; width:22px; height:22px; display:none; align-items:center; justify-content:center; cursor:pointer; color:var(--text-3); font-size:11px; line-height:1; }
#anjApp .msw-clear-btn.visible { display:flex; }
#anjApp .msw-spinner { position:absolute; right:36px; top:50%; transform:translateY(-50%); width:15px; height:15px; border-radius:50%; border:2px solid rgba(0,0,0,.08); border-top-color:var(--gold); animation:msw-spin .7s linear infinite; display:none; }
#anjApp .msw-spinner.active { display:block; }
@keyframes msw-spin { to { transform:translateY(-50%) rotate(360deg); } }
#anjApp #mswDropdown { position:absolute; top:calc(100% + 6px); left:0; right:0; background:var(--surface); border:1.5px solid var(--border); border-radius:12px; box-shadow:var(--shadow-lg); z-index:1050; display:none; max-height:300px; overflow-y:auto; }
#anjApp #mswDropdown.open { display:block; }
#anjApp .msw-result-item { display:flex; align-items:center; gap:12px; padding:11px 14px; cursor:pointer; transition:background .14s; border-bottom:1px solid var(--border-light); }
#anjApp .msw-result-item:last-child { border-bottom:none; }
#anjApp .msw-result-item:hover { background:var(--gold-muted); }
#anjApp .msw-avatar { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,var(--gold),#c9a227); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.88rem; flex-shrink:0; }
#anjApp .msw-avatar.female { background:linear-gradient(135deg,#b45309,#f59e0b); }
#anjApp .msw-res-name { font-weight:700; color:var(--text-1); font-size:.9rem; }
#anjApp .msw-res-meta { font-size:.76rem; color:var(--text-3); margin-top:1px; }
#anjApp .msw-its-badge { margin-left:auto; background:var(--gold-muted); color:var(--gold); font-size:.68rem; font-weight:800; padding:2px 7px; border-radius:20px; white-space:nowrap; flex-shrink:0; }
#anjApp .msw-no-results { padding:16px 14px; color:var(--text-3); text-align:center; font-size:.9rem; }
#anjApp .tk-hint { font-size: .73rem; color: var(--text-3); text-align: center; margin: 6px 0 0; cursor: pointer; }
</style>

<?php
$username_raw = $_SESSION['user']['username'] ?? '';
$is_sub_sector = false;
if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z])$/i', $username_raw)) {
    $is_sub_sector = true;
}
$directory_label = $is_sub_sector ? 'Mumineen in your Sub Mohalla' : 'Mumineen in your Mohalla';
?>

<div id="anjApp">
<div class="anj-root">

  <!-- ══ SIDEBAR ══ -->
  <aside class="anj-sidebar" id="anjSidebar">
    <button class="sidebar-close-btn" id="sidebarCloseBtn">&times;</button>
    <div class="sidebar-menu">
      <div class="menu-title">
        <span class="sb-ico" style="display:inline-flex;width:26px;height:26px;border-radius:7px;background:var(--gold-muted);color:var(--gold);align-items:center;justify-content:center;font-size:.78rem;margin-right:8px;">
          <i class="fa fa-tachometer"></i>
        </span>Quick Menu
      </div>
      <div class="menu-search" role="search">
        <i class="fa fa-search"></i>
        <input id="quickMenuSearch" type="text" placeholder="Search menu..." autocomplete="off">
        <button type="button" id="quickMenuClear" class="menu-search-clear">&times;</button>
      </div>

      <div class="menu-section">Activity</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('MasoolMusaid/mumineendirectory') ?>"><span class="menu-icon"><i class="fa fa-users"></i></span><span class="menu-label"><?= $directory_label ?></span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('MasoolMusaid/asharaohbat') ?>"><span class="menu-icon"><i class="fa fa-calendar"></i></span><span class="menu-label">Ashara Ohbat</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('MasoolMusaid/ashara_attendance') ?>"><span class="menu-icon"><i class="fa fa-user-check"></i></span><span class="menu-label">Ashara Attendance</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('MasoolMusaid/rsvp_list') ?>"><span class="menu-icon"><i class="fa fa-calendar-check-o"></i></span><span class="menu-label">Miqaat RSVP</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('MasoolMusaid/miqaat_attendance') ?>"><span class="menu-icon"><i class="fa fa-check-square-o"></i></span><span class="menu-label">Miqaat Attendance</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('MasoolMusaid/qardanhasana') ?>"><span class="menu-icon"><i class="fa fa-hand-holding-heart"></i></span><span class="menu-label">Qardan Hasana</span></a></li>
      </ul>
    </div>
  </aside>

  <!-- ══ CONTENT ══ -->
  <main class="anj-content">

    <!-- Mobile toolbar -->
    <div class="mob-bar">
      <button id="sidebarToggle" class="mob-btn">
        <span class="mic"><i class="fa fa-bars"></i></span> Menu
      </button>
      <span style="font-size:.82rem;font-weight:700;color:var(--text-2);">Dashboard</span>
    </div>

    <!-- Dashboard header -->
    <div class="anj-header">
      <div class="anj-header-inner">
        <div class="anj-title-group">
          <p class="anj-eyebrow">Anjuman-e-Saifee</p>
          <h1 class="anj-title">
            <?php echo htmlspecialchars($current_sector . ($current_sub_sector !== '' ? ' ' . $current_sub_sector : ''), ENT_QUOTES, 'UTF-8'); ?>
            <br><span><?php $hijri_year = isset($year_daytype_stats['hijri_year']) ? $year_daytype_stats['hijri_year'] : '1446'; echo $hijri_year . 'H — ' . date('Y'); ?></span>
          </h1>
          <?php if ($incharge_male !== '' || $incharge_female !== ''): ?>
            <div style="font-size: 0.85rem; font-weight: 600; display: block; margin-top: 8px; color: rgba(255,255,255,.95);">
              <?php if ($incharge_male !== ''): ?>
                <span class="mr-3"><i class="fa fa-male" style="color:#eff6ff;margin-right:4px;"></i><?= htmlspecialchars($incharge_male) ?></span>
              <?php endif; ?>
              <?php if ($incharge_female !== ''): ?>
                <span><i class="fa fa-female" style="color:#fdf2f8;margin-right:4px;"></i><?= htmlspecialchars($incharge_female) ?></span>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="anj-badge">
          <span class="anj-badge-val"><?= (int)($stats['active_inactive']['active'] ?? 0) ?></span>
          <span class="anj-badge-lbl">Active Members</span>
        </div>
      </div>
    </div>

    <!-- ===== Member Search Widget ===== -->
    <div class="chart-container mb-4 member-search-widget" id="member-search-block" style="padding:18px 20px;">
      <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:15px;">
        <div class="d-flex align-items-center" style="gap:10px;">
          <div class="msw-label m-0"><i class="fa fa-search mr-1"></i> Member Search</div>
          <span class="d-none d-md-inline" style="font-size:.76rem;color:var(--text-3);margin-top:2px;">Search by name or ITS ID</span>
        </div>
        <div class="msw-input-wrap m-0" style="flex:1 1 auto;justify-content:flex-end;">
          <div class="msw-input-group">
            <i class="fa fa-search msw-icon"></i>
            <input type="text" id="mswInput" placeholder="Type name or ITS ID..." autocomplete="off">
            <div class="msw-spinner" id="mswSpinner"></div>
            <button class="msw-clear-btn" id="mswClear">&#x2715;</button>
            <div id="mswDropdown" role="listbox"></div>
          </div>
          <a href="<?php echo base_url('MasoolMusaid/mumineendirectory') ?>" class="btn btn-outline-secondary btn-sm" style="white-space:nowrap;border-radius:9px;font-weight:700;">
            <i class="fa fa-users"></i> All Members
          </a>
        </div>
      </div>
    </div>

    
                <!-- ── Husaini Scheme Stats ── -->
<?php
$hs = $husaini_stats ?? ['active_given' => 0, 'active_not_given' => 0, 'active_total' => 0];
$hs_pct_given     = $hs['active_total'] > 0 ? round($hs['active_given']     / $hs['active_total'] * 100) : 0;
$hs_pct_not_given = $hs['active_total'] > 0 ? round($hs['active_not_given'] / $hs['active_total'] * 100) : 0;
?>
<div class="chart-container compact mb-3">
  <div class="section-header-standard">
    <h4 class="section-title">
      <i class="fa fa-hand-holding-heart"></i> Husaini Scheme
      <small style="font-size:.7rem;font-weight:500;color:var(--text-3);margin-left:6px;">(Active members only)</small>
    </h4>
    <a href="<?= base_url('MasoolMusaid/qardanhasana') ?>"
       class="btn btn-sm btn-outline-secondary"
       style="white-space:nowrap;border-radius:9px;font-weight:700;font-size:.74rem;">
      <i class="fa fa-arrow-right"></i> View Details
    </a>
  </div>
 
  <div class="row text-center">
 
    <!-- Given -->
    <div class="col-4">
      <a href="<?= base_url('MasoolMusaid/qardanhasana') ?>"
         style="text-decoration:none;color:inherit;display:block;">
        <div class="mini-card" style="border-left:3px solid #16a34a;">
          <span class="stats-value green"><?= (int)$hs['active_given'] ?></span>
          <span class="stats-label" style="color:#15803d;">✔ Given</span>
          <div style="font-size:.68rem;color:var(--text-3);margin-top:3px;"><?= $hs_pct_given ?>%</div>
        </div>
      </a>
    </div>
 
    <!-- Not Given -->
    <div class="col-4">
      <a href="<?= base_url('MasoolMusaid/qardanhasana') ?>"
         style="text-decoration:none;color:inherit;display:block;">
        <div class="mini-card" style="border-left:3px solid #dc2626;">
          <span class="stats-value red"><?= (int)$hs['active_not_given'] ?></span>
          <span class="stats-label" style="color:#b91c1c;">✘ Not Given</span>
          <div style="font-size:.68rem;color:var(--text-3);margin-top:3px;"><?= $hs_pct_not_given ?>%</div>
        </div>
      </a>
    </div>
 
    <!-- Total Active -->
    <div class="col-4">
      <a href="<?= base_url('MasoolMusaid/qardanhasana') ?>"
         style="text-decoration:none;color:inherit;display:block;">
        <div class="mini-card">
          <span class="stats-value"><?= (int)$hs['active_total'] ?></span>
          <span class="stats-label">Active Total</span>
          <div style="font-size:.68rem;color:var(--text-3);margin-top:3px;">100%</div>
        </div>
      </a>
    </div>
 
  </div>
 
  <!-- Progress bar -->
  <?php if ($hs['active_total'] > 0): ?>
  <div style="margin-top:12px;">
    <div style="display:flex;justify-content:space-between;font-size:.72rem;color:var(--text-3);margin-bottom:4px;">
      <span>Given <?= $hs_pct_given ?>%</span>
      <span>Not Given <?= $hs_pct_not_given ?>%</span>
    </div>
    <div style="height:8px;border-radius:20px;background:#fee2e2;overflow:hidden;">
      <div style="height:100%;width:<?= $hs_pct_given ?>%;background:linear-gradient(90deg,#16a34a,#22c55e);border-radius:20px;transition:width .4s;"></div>
    </div>
  </div>
  <?php endif; ?>
 
</div>


    <!-- ── Overview ── -->
    <div class="chart-container">
      <h2 style="font-size:1.1rem;font-weight:800;color:var(--text-1);margin:0 0 18px;text-align:center;letter-spacing:-.2px;">Mohalla Overview</h2>

      <!-- Member Status -->
      <div class="section-header-standard">
        <h3 class="section-title"><i class="fa fa-toggle-on"></i> Member Status</h3>
        <button class="collapse-toggle-btn" data-toggle="collapse" data-target="#collapseMMPMemberActivity" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseMMPMemberActivity">
        <div class="row mb-3">
          <div class="col-6">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?status=active') ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card green">
                <div class="overview-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-check-circle"></i></div>
                <div class="overview-body"><span class="overview-title">Active Members</span><span class="overview-value"><?= (int)($stats['active_inactive']['active'] ?? 0) ?></span></div>
              </div>
            </a>
          </div>
          <div class="col-6">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?status=inactive') ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card red">
                <div class="overview-icon" style="background:#fef2f2;color:#b91c1c;"><i class="fa fa-times-circle"></i></div>
                <div class="overview-body"><span class="overview-title">Inactive Members</span><span class="overview-value"><?= (int)($stats['active_inactive']['inactive'] ?? 0) ?></span></div>
              </div>
            </a>
          </div>
        </div>
      </div>

      <!-- ITS Sabeel Match -->
      <div class="section-header-standard mt-1">
        <h3 class="section-title"><i class="fa fa-exchange"></i> ITS Sabeel Match</h3>
        <button class="collapse-toggle-btn" data-toggle="collapse" data-target="#collapseMMPItsSabeelMatch" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseMMPItsSabeelMatch">
        <div class="row mb-3">
          <?php
          $CI =& get_instance();
          $CI->load->model('MemberStatusM');
          $statCards = [
            ['its_sabeel_both_khar',  MemberStatusM::match_status_label('its_sabeel_both_khar'), 'fa-home',         '#f5e9c0','#b8860b'],
            ['sabeel_khar_its_out',   MemberStatusM::match_status_label('sabeel_khar_its_out'),  'fa-external-link','#ecfeff','#0891b2'],
            ['its_khar_sabeel_out',   MemberStatusM::match_status_label('its_khar_sabeel_out'),  'fa-sign-out',     '#fff7ed','#b45309'],
            ['both_not_khar',         MemberStatusM::match_status_label('both_not_khar'),        'fa-ban',           '#fef2f2','#b91c1c'],
          ];
          foreach ($statCards as [$key, $label, $icon, $bg, $color]): ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?its_sabeel_match='.$key) ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon" style="background:<?= $bg ?>;color:<?= $color ?>;"><i class="fa <?= $icon ?>"></i></div>
                <div class="overview-body"><span class="overview-title"><?= $label ?></span><span class="overview-value"><?= (int)($stats['active_inactive'][$key] ?? 0) ?></span></div>
              </div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- General Overall Stats -->
      <div class="section-header-standard mt-1">
        <h3 class="section-title"><i class="fa fa-info-circle"></i> General Overall Stats</h3>
        <button class="collapse-toggle-btn" data-toggle="collapse" data-target="#collapseMMPGeneralOverallStats" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseMMPGeneralOverallStats">
        <div class="row mb-3">
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('MasoolMusaid/mumineendirectory?status=active') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon"><i class="fa fa-users"></i></div><div class="overview-body"><span class="overview-title">Total Members</span><span class="overview-value"><?= (int)($stats['active_inactive']['active'] ?? 0) ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('MasoolMusaid/mumineendirectory?status=active&filter=hof_fm_type&value=HOF') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon"><i class="fa fa-user"></i></div><div class="overview-body"><span class="overview-title">HOF</span><span class="overview-value"><?= $stats['HOF'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('MasoolMusaid/mumineendirectory?status=active&filter=hof_fm_type&value=FM') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon"><i class="fa fa-user-plus"></i></div><div class="overview-body"><span class="overview-title">FM</span><span class="overview-value"><?= $stats['FM'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('MasoolMusaid/mumineendirectory?status=active&filter=gender&value=male') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="fa fa-male"></i></div><div class="overview-body"><span class="overview-title">Males</span><span class="overview-value"><?= $stats['Mardo'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('MasoolMusaid/mumineendirectory?status=active&filter=gender&value=female') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#fdf2f8;color:#9d174d;"><i class="fa fa-female"></i></div><div class="overview-body"><span class="overview-title">Females</span><span class="overview-value"><?= $stats['Bairo'] ?></span></div></div></a></div>
          <?php
          $ageGroups=[['0','4','Age 0-4',$stats['Age_0_4']],['5','15','Age 5-15',$stats['Age_5_15']],['16','25','Age 16-25',$stats['Age_16_25']],['26','65','Age 26-65',$stats['Age_26_65']],['66','','Above 65',$stats['Buzurgo']]];
          foreach($ageGroups as[$mn,$mx,$lbl,$val]):$url=$mx?base_url("MasoolMusaid/mumineendirectory?status=active&filter=age_range&min=$mn&max=$mx"):base_url("MasoolMusaid/mumineendirectory?status=active&filter=age_range&min=$mn");?>
          <div class="col-6 col-md-5th mb-3"><a href="<?= $url ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:var(--green-bg);color:var(--green);"><i class="fa fa-child"></i></div><div class="overview-body"><span class="overview-title"><?= $lbl ?></span><span class="overview-value"><?= $val ?></span></div></div></a></div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Health, Deeni, Residential Statuses -->
      <?php
      $status_counts = isset($stats['status_counts']) ? $stats['status_counts'] : [];
      $statusGroups = [
        'deeni_status'       => ['label'=>'Deeni Status',       'icon'=>'fa-star',           'bg'=>'#f5f3ff','color'=>'#7c3aed','key'=>'deeni',       'id'=>'collapseMMPDeeni'],
        'health_status'      => ['label'=>'Health Status',      'icon'=>'fa-heartbeat',      'bg'=>'#fef2f2','color'=>'#b91c1c','key'=>'health',      'id'=>'collapseMMPHealth'],
        'residential_status' => ['label'=>'Residential Status', 'icon'=>'fa-building',       'bg'=>'#eff6ff','color'=>'#1d4ed8','key'=>'residential', 'id'=>'collapseMMPResidential'],
        'Qualification'      => ['label'=>'Dunyavi Education',  'icon'=>'fa-graduation-cap', 'bg'=>'#eaf4ee','color'=>'#1a6645','key'=>'education',   'id'=>'collapseMMPEducation'],
      ];
      $CI =& get_instance();
      $CI->load->model('MemberStatusM');
      $deeni_map = MemberStatusM::deeni_status_options();
      $health_map = MemberStatusM::health_status_options();
      $res_map = MemberStatusM::residential_status_options();

      foreach ($statusGroups as $filterKey => $g):
        if (empty($status_counts[$g['key']])) continue; ?>
      <div class="section-header-standard mt-1">
        <h3 class="section-title"><i class="fa <?= $g['icon'] ?>"></i> <?= $g['label'] ?></h3>
        <button class="collapse-toggle-btn" data-toggle="collapse" data-target="#<?= $g['id'] ?>" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="<?= $g['id'] ?>">
        <div class="row mb-3">
          <?php foreach ($status_counts[$g['key']] as $lbl => $cnt): 
            if ($lbl==='None'||$lbl==='') continue; 
            $display_lbl = $lbl;
            if ($g['key'] === 'deeni' && isset($deeni_map[$lbl])) {
              $display_lbl = $deeni_map[$lbl];
            } elseif ($g['key'] === 'health' && isset($health_map[$lbl])) {
              $display_lbl = $health_map[$lbl];
            } elseif ($g['key'] === 'residential' && isset($res_map[$lbl])) {
              $display_lbl = $res_map[$lbl];
            }
            $display_lbl = preg_replace('/\s*\((Active|Inactive)\)$/i', '', $display_lbl);
          ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?filter='.$filterKey.'&value='.rawurlencode($lbl)) ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card"><div class="overview-icon" style="background:<?= $g['bg'] ?>;color:<?= $g['color'] ?>;"><i class="fa <?= $g['icon'] ?>"></i></div><div class="overview-body"><span class="overview-title"><?= htmlspecialchars($display_lbl) ?></span><span class="overview-value"><?= $cnt ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Deeni Taalim -->
      <div class="section-header-standard mt-1">
        <h3 class="section-title"><i class="fa fa-graduation-cap"></i> Deeni Taalim Stats</h3>
        <button class="collapse-toggle-btn" data-toggle="collapse" data-target="#collapseMMPEduTracking" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseMMPEduTracking">
        <div class="row mb-3">
          <?php
          $deeniCards=[
            [base_url('MasoolMusaid/mumineendirectory?status=Active&min=5&max=15'),              '#eff6ff','#1d4ed8','fa-child',     'Total Eligible (5-15)',   $stats['deeni_eligible']??0],
            [base_url('MasoolMusaid/mumineendirectory?status=Active&min=5&max=15&madresa_deprived=0'),'#eaf4ee','#1a6645','fa-book','Deeni Taalim Taking',    $stats['deeni_taking']??0],
            [base_url('MasoolMusaid/mumineendirectory?status=Active&min=5&max=15&madresa_deprived=1'),'#fff7ed','#b45309','fa-book','Not Taking Deeni Taalim',$stats['madresa_deprived']??0],
          ];
          foreach($deeniCards as[$url,$bg,$color,$icon,$lbl,$val]):?>
          <div class="col-12 col-md-4 mb-3">
            <a href="<?= $url ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card"><div class="overview-icon" style="background:<?= $bg ?>;color:<?= $color ?>;"><i class="fa <?= $icon ?>"></i></div><div class="overview-body"><span class="overview-title"><?= $lbl ?></span><span class="overview-value"><?= (int)$val ?> <small style="font-size:.7rem;color:var(--text-3);font-weight:600;">Farzando</small></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Occupation -->
      <?php if (!empty($status_counts['occupation'])): ?>
      <div class="section-header-standard mt-1">
        <h3 class="section-title"><i class="fa fa-briefcase"></i> All Occupation</h3>
        <button class="collapse-toggle-btn" data-toggle="collapse" data-target="#collapseMMPOccupation" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseMMPOccupation">
        <div class="row mb-3">
          <?php foreach ($status_counts['occupation'] as $lbl => $cnt): if ($lbl==='None'||$lbl==='') continue; ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?filter=Occupation&value='.rawurlencode($lbl)) ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card"><div class="overview-icon" style="background:var(--surface-2);color:var(--text-2);"><i class="fa fa-briefcase"></i></div><div class="overview-body"><span class="overview-title"><?= htmlspecialchars($lbl) ?></span><span class="overview-value"><?= $cnt ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Sub-sectors -->
      <div class="section-header-standard mt-1">
        <h3 class="section-title"><i class="fa fa-map-marker"></i> Sub-sectors</h3>
        <button class="collapse-toggle-btn" data-toggle="collapse" data-target="#collapseGroupSubSectors" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseGroupSubSectors">
        <div class="row mb-3">
          <?php
          $subSectorRows = isset($stats['SubSectors']) ? $stats['SubSectors'] : [];
          if (!empty($subSectorRows)) {
            $subSectorRows = array_values(array_filter($subSectorRows, fn($r) => trim($r['SubSector']??'')!=='' && strtolower($r['SubSector']??'')!=='unassigned'));
            usort($subSectorRows, fn($a,$b) => intval($b['total']??0) <=> intval($a['total']??0));
            foreach ($subSectorRows as $idx => $row):
              $hof = intval($row['hof_count']??$row['HOF']??0);
              $fm  = intval($row['fm_count'] ??$row['FM'] ??0);
          ?>
          <div class="col-12 col-md-3 mb-3">
            <div class="overview-card" style="display:flex;flex-direction:column;align-items:stretch;height:100%;">
              <a href="<?= base_url('MasoolMusaid/mumineendirectory?filter=sub_sector&value='.rawurlencode($row['SubSector']??'')) ?>" style="display:flex;align-items:center;gap:10px;flex:1;text-decoration:none;color:inherit;">
                <div class="overview-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-map-marker"></i></div>
                <div class="overview-body" style="width:100%;">
                  <span class="overview-title"><?= htmlspecialchars(($row['Sector']??'') . ' ' . ($row['SubSector']??'')) ?></span>
                  <?php if (!empty($row['Sub_Sector_Incharge_Name']) || !empty($row['Sub_Sector_Incharge_Female_Name'])): ?>
                  <div style="font-size:.74rem;color:var(--text-3);margin:4px 0;">
                    <?php if (!empty($row['Sub_Sector_Incharge_Name'])): ?>
                    <div style="margin-bottom:2px;"><i class="fa fa-male" style="color:#1d4ed8;margin-right:3px;"></i><?= htmlspecialchars($row['Sub_Sector_Incharge_Name']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($row['Sub_Sector_Incharge_Female_Name'])): ?>
                    <div><i class="fa fa-female" style="color:#9d174d;margin-right:3px;"></i><?= htmlspecialchars($row['Sub_Sector_Incharge_Female_Name']) ?></div>
                    <?php endif; ?>
                  </div>
                  <?php endif; ?>
                  <span class="overview-value" style="font-size:.95rem;">HOF <?= $hof ?> &nbsp;·&nbsp; FM <?= $fm ?></span>
                </div>
              </a>
            </div>
          </div>
          <?php endforeach; } ?>
        </div>
      </div>

      <!-- Marital Stats -->
      <div class="section-header-standard mt-1">
        <h3 class="section-title"><i class="fa fa-heart"></i> Marital Stats</h3>
        <button class="collapse-toggle-btn" data-toggle="collapse" data-target="#collapseGroupMarital" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseGroupMarital">
        <div class="row mb-3">
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?status=Active&marital_status=Single&min=21&max=40') ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card"><div class="overview-icon" style="background:var(--gold-muted);color:var(--gold);"><i class="fa fa-heart"></i></div><div class="overview-body"><span class="overview-title">Single (21-40)</span><span class="overview-value"><?= isset($stats['singles_21_40'])?(int)$stats['singles_21_40']:0 ?></span></div></div>
            </a>
          </div>
          <?php
          $maritalDistribution = isset($marital_status_counts) ? $marital_status_counts : [];
          foreach($maritalDistribution as $label => $count):
            $ll = strtolower(trim($label));
            if($ll==='unknown'||$ll===''||$ll==='single') continue;
            $iconBg='#f5f5f7'; $iconColor='#6b7280'; $icon='fa-info-circle';
            if(str_contains($ll,'married')){$icon='fa-user';$iconBg='#fff0f6';$iconColor='#9d174d';}
            elseif(str_contains($ll,'engag')){$icon='fa-star';$iconBg='#fffbeb';$iconColor='#b45309';}
            elseif(str_contains($ll,'divorc')){$icon='fa-user';$iconBg='#fef2f2';$iconColor='#b91c1c';}
            elseif(str_contains($ll,'widow')){$icon='fa-user-secret';$iconBg='#ecfeff';$iconColor='#0891b2';}
          ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?status=Active&marital_status='.rawurlencode($label)) ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card"><div class="overview-icon" style="background:<?= $iconBg ?>;color:<?= $iconColor ?>;"><i class="fa <?= $icon ?>"></i></div><div class="overview-body"><span class="overview-title"><?= htmlspecialchars($label) ?></span><span class="overview-value"><?= (int)$count ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Weekly Signup Performance -->
      <?php
      $sw_items = isset($weekly_signup_avg['items']) ? $weekly_signup_avg['items'] : [];
      if (!empty($sw_items)): ?>
      <div class="section-header-standard mt-1">
        <h3 class="section-title"><i class="fa fa-line-chart"></i> Weekly Signup Performance</h3>
        <button class="collapse-toggle-btn" data-toggle="collapse" data-target="#collapseGroupWeekly" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseGroupWeekly">
        <div class="row mb-2">
          <?php foreach ($sw_items as $sr): ?>
          <div class="col-6 col-md-3 mb-3">
            <div class="mini-card">
              <span class="stats-value"><?= round((float)($sr['avg']??0), 1) ?> <small style="font-size:.65rem;color:var(--text-3); font-weight:600;">/ day</small></span>
              <span class="stats-label"><?= htmlspecialchars(($sr['sector']??'') . ' ' . ($sr['sub_sector']??'')) ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div><!-- /surf Overview -->

    <!-- ── Thaali Signup card ── -->
    <div class="chart-container compact">
      <div class="section-header-standard">
        <h4 class="section-title">
          <i class="fa fa-cutlery"></i>
          Thaali Signup for Current Month
        </h4>
        <a id="thaali-details-btn" href="#" class="btn btn-sm btn-primary text-white" style="font-size:.76rem;font-weight:700;border-radius:8px;">
          View details
        </a>
      </div>

      <?php
      $this->load->model('HijriCalendar');
      $hijri_today = $selected_hijri_parts ?? $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
      $current_hijri_year  = (int)($hijri_today['hijri_year']  ?? 0);
      $current_hijri_month = (int)($hijri_today['hijri_month'] ?? 0);
      $monthRow = $this->db->where('id', $current_hijri_month)->get('hijri_month')->row_array();
      $current_hijri_month_name = $monthRow['hijri_month'] ?? '';

      $prev_month = $current_hijri_month - 1; $prev_year = $current_hijri_year;
      $next_month = $current_hijri_month + 1; $next_year = $current_hijri_year;
      if ($prev_month < 1)  { $prev_month = 12; $prev_year--; }
      if ($next_month > 12) { $next_month = 1;  $next_year++; }

      $hijri_days  = $this->HijriCalendar->get_hijri_days_for_month_year($current_hijri_month, $current_hijri_year);
      $month_start = $hijri_days[0]['greg_date']                       ?? date('Y-m-01');
      $month_end   = $hijri_days[count($hijri_days) - 1]['greg_date']  ?? date('Y-m-t');
      ?>

      <script>
      (function(){
        var btn=document.getElementById('thaali-details-btn');
        if(!btn||!window.USER_NAME)return;
        var params=new URLSearchParams({from:'masoolmusaid',hijri:'<?= $current_hijri_year ?>-<?= sprintf('%02d', $current_hijri_month) ?>',sector:window.USER_NAME});
        btn.href='<?= base_url('common/thaali_signup_dashboard') ?>?'+params.toString();
      })();
      </script>

      <!-- Hijri switcher -->
      <div class="hijri-switcher">
        <a href="#" class="hijri-nav-btn" data-hijri-year="<?= $prev_year ?>" data-hijri-month="<?= $prev_month ?>">
          <div class="chev-box"><i class="fa fa-chevron-left"></i></div>
        </a>
        <div id="hijri-current-title"><?= htmlspecialchars($current_hijri_month_name . ' ' . $current_hijri_year) ?></div>
        <a href="#" class="hijri-nav-btn" data-hijri-year="<?= $next_year ?>" data-hijri-month="<?= $next_month ?>">
          <div class="chev-box"><i class="fa fa-chevron-right"></i></div>
        </a>
      </div>

      <!-- Loader -->
      <div id="monthLoader" class="text-center my-2">
        <i class="fa fa-spinner fa-spin"></i> Loading month…
      </div>

      <!-- Month stats -->
      <div id="thaali-month-block" class="row text-center">
        <div class="col-6 mb-2">
          <a href="#" class="open-hof-modal" data-modal-type="signed" data-hijri-year="<?= $current_hijri_year ?>" data-hijri-month="<?= $current_hijri_month ?>" style="text-decoration:none;color:inherit;display:block;">
            <div class="mini-card">
              <div class="stats-value"><?= (int)($month_stats['families_signed_up'] ?? 0) ?></div>
              <div class="stats-label">Signed up this month</div>
            </div>
          </a>
        </div>
        <div class="col-6 mb-2">
          <a href="#" class="open-hof-modal" data-modal-type="no" data-hijri-year="<?= $current_hijri_year ?>" data-hijri-month="<?= $current_hijri_month ?>" style="text-decoration:none;color:inherit;display:block;">
            <div class="mini-card">
              <div class="stats-value"><?= (int)($month_stats['no_thaali_count'] ?? 0) ?></div>
              <div class="stats-label">No signup this month</div>
            </div>
          </a>
        </div>
      </div>

      <!-- HOF List Modal -->
      <div class="modal fade" id="hofListModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header py-2">
              <h6 class="modal-title" id="hofListLabel"></h6>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div id="miqaatPopupMeta" style="display:none;margin-bottom:14px;background:var(--surface-2);border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.82rem;"></div>
              <div id="hofListLoading" class="text-center py-3" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</div>
              <div id="hofListContainer" style="max-height:60vh;overflow:auto;"></div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /surf -->

    <!-- ── RSVP for Next Miqaat ── -->
    <div class="chart-container" id="miqaat-rsvp-block" data-initial-index="<?= isset($initial_index) ? $initial_index : 0 ?>" style="position:relative;">
      <div class="section-header-standard">
        <h4 class="section-title">
          <i class="fa fa-calendar-check-o"></i>
          RSVP for Next Miqaat
        </h4>
        <div class="d-flex align-items-center">
          <a href="<?= base_url('MasoolMusaid/rsvp_list') ?>" id="miqaat-view-details" class="btn btn-sm btn-primary text-white mr-2" style="white-space:nowrap;border-radius:8px;font-size:.76rem;font-weight:700;">View details</a>
          <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseRsvpMM" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
      </div>
      <div class="collapse show" id="collapseRsvpMM">
        <div class="d-flex align-items-center justify-content-between mt-2 mb-1" style="gap:10px;">
          <div class="d-flex align-items-center justify-content-center" style="gap:10px;width:100%;">
            <button type="button" class="miqaat-nav-btn prev"
              style="width:34px;height:34px;border:1px solid var(--border);border-radius:8px;background:var(--surface);color:var(--text-2);font-size:1.2rem;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s;">
              &lsaquo;
            </button>
            <div id="miqaat-current-title" style="font-weight:700;color:var(--gold);font-size:.95rem;text-align:center;min-width:160px;"></div>
            <button type="button" class="miqaat-nav-btn next"
              style="width:34px;height:34px;border:1px solid var(--border);border-radius:8px;background:var(--surface);color:var(--text-2);font-size:1.2rem;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s;">
              &rsaquo;
            </button>
          </div>
        </div>

        <div class="row text-center mb-2 mt-2">
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatWillAttendCard" class="open-miqaat-modal" data-type="rsvp" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="stats-value" id="willAttendCount">0</div>
                <div class="small text-muted" id="willAttendGuest" style="min-height:16px;font-size:.72rem;"></div>
                <div class="stats-label">Will attend</div>
              </div>
            </a>
          </div>
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatWillNotAttendCard" class="open-miqaat-modal" data-type="no" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="stats-value" id="willNotAttendCount">0</div>
                <div class="stats-label">Will not attend</div>
              </div>
            </a>
          </div>
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatNotSubmittedCard" class="open-miqaat-modal" data-type="not_submitted" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="stats-value" id="rsvpNotSubmittedCount">0</div>
                <div class="stats-label">RSVP not submitted</div>
              </div>
            </a>
          </div>
        </div>

        <div class="row text-center mb-3" id="miqaatGuestBreakdown">
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatGuestGentsCard" class="open-miqaat-modal" data-type="gents" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="small text-muted" style="font-size:.72rem;">Gents</div>
                <div class="stats-value" id="guestGentsCount">0</div>
                <div class="small text-muted" id="guestGentsBreakdown" style="font-size:.7rem;">Members: 0 | Guests: 0</div>
              </div>
            </a>
          </div>
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatGuestLadiesCard" class="open-miqaat-modal" data-type="ladies" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="small text-muted" style="font-size:.72rem;">Ladies</div>
                <div class="stats-value" id="guestLadiesCount">0</div>
                <div class="small text-muted" id="guestLadiesBreakdown" style="font-size:.7rem;">Members: 0 | Guests: 0</div>
              </div>
            </a>
          </div>
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatGuestChildrenCard" class="open-miqaat-modal" data-type="children" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="small text-muted" style="font-size:.72rem;">Children</div>
                <div class="stats-value" id="guestChildrenCount">0</div>
                <div class="small text-muted" id="guestChildrenBreakdown" style="font-size:.7rem;">Members: 0 | Guests: 0</div>
              </div>
            </a>
          </div>
        </div>

        <div id="miqaatMessage" style="display:none;margin-top:6px;text-align:center;color:var(--text-2);font-size:.85rem;"></div>
        <div id="miqaatLoading"><div class="miqaat-spinner"></div></div>
      </div>
    </div>

    <!-- ── FMB Calendar Overview ── -->
    <?php if (!empty($year_daytype_stats)): ?>
    <div class="chart-container">
      <div class="section-header-standard">
        <h4 class="section-title">
          <i class="fa fa-calendar"></i>
          FMB Calendar Overview (Hijri <?= htmlspecialchars($year_daytype_stats['hijri_year'] ?? '') ?>)
        </h4>
        <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseCalendarMM" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseCalendarMM">
        <div class="row">
          <a href="<?= base_url('common/fmbcalendar?from=masoolmusaid') ?>" class="col-12 col-md-4 mb-2" style="text-decoration:none;">
            <div class="mini-card">
              <div class="stats-value"><?= (int)($year_daytype_stats['miqaat_days'] ?? 0) ?></div>
              <div class="stats-label">Miqaat Days</div>
            </div>
          </a>
          <a href="<?= base_url('common/fmbcalendar?from=masoolmusaid') ?>" class="col-12 col-md-4 mb-2" style="text-decoration:none;">
            <div class="mini-card">
              <div class="stats-value"><?= (int)($year_daytype_stats['thaali_days'] ?? 0) ?></div>
              <div class="stats-label">Thaali Days</div>
            </div>
          </a>
          <a href="<?= base_url('common/fmbcalendar?from=masoolmusaid') ?>" class="col-12 col-md-4 mb-2" style="text-decoration:none;">
            <div class="mini-card">
              <div class="stats-value"><?= (int)($year_daytype_stats['holiday_days'] ?? 0) ?></div>
              <div class="stats-label">Holidays</div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </main>

  <!-- Sidebar overlay (mobile) -->
  <div id="anjSidebarOverlay" class="sidebar-overlay"></div>

</div>
</div>

<!-- ── All JS preserved exactly ── -->
<script>
(function(){
  // User scope
  const ALLOWED_SECTORS=['BURHANI','MOHAMMEDI','NAJMI','SAIFEE','TAHERI'];
  const ALLOWED_SUBS=['A','B','C'];
  function parseUserScope(userName){if(!userName)return null;var name=userName.trim().toUpperCase();var last=name.slice(-1);var base=name.slice(0,-1);if(ALLOWED_SUBS.includes(last)&&ALLOWED_SECTORS.includes(base))return{sector:base,sub:last};if(ALLOWED_SECTORS.includes(name))return{sector:name,sub:null};return null;}
  const USER_SCOPE=parseUserScope(window.USER_NAME||'');

  // Mobile formatter
  function renderMobile(raw){if(!raw)return '';var digits=String(raw).replace(/\D/g,'');if(digits.startsWith('91')&&digits.length===12)return '<a href="tel:+'+digits+'" style="color:var(--gold);text-decoration:none;">+'+digits+'</a>';return '<span class="copy-mobile" data-mobile="'+raw+'" style="cursor:pointer;color:var(--gold);">'+raw+'</span>';}

  // Copy handler
  document.addEventListener('click',function(e){var el=e.target.closest('.copy-mobile');if(!el)return;var num=el.dataset.mobile;navigator.clipboard.writeText(num).then(function(){var old=el.innerText;el.innerText='Copied ✔';setTimeout(function(){el.innerText=old;},1200);});});

  // Modal
  $(document).on('click','.open-hof-modal',function(e){
    e.preventDefault();
    $('#miqaatPopupMeta').hide();
    var type=$(this).data('modal-type');var y=$(this).data('hijri-year');var m=$(this).data('hijri-month');
    if(!y||!m)return;
    var url=window.location.pathname+'?hijri_year='+y+'&hijri_month='+m+'&format=json';
    $('#hofListContainer').html('');$('#hofListLoading').show();
    $('#hofListLabel').text(type==='signed'?'Families Signed Up This Month':'Families With No Signup This Month');
    $('#hofListModal').modal('show');
    fetch(url,{credentials:'same-origin'}).then(function(r){return r.json();}).then(function(data){
      var rows=type==='signed'?(data?.monthly_stats?.signed_hof_list||[]):(data?.monthly_stats?.no_thaali_list||[]);
      if(USER_SCOPE){rows=rows.filter(function(r){var s=String(r.Sector||'').toUpperCase();var sub=String(r.Sub_Sector||'').toUpperCase();if(s!==USER_SCOPE.sector)return false;if(USER_SCOPE.sub===null)return ALLOWED_SUBS.includes(sub);return sub===USER_SCOPE.sub;});}
      $('#hofListLoading').hide();
      if(!rows.length){$('#hofListContainer').html('<div class="text-muted text-center py-3">No records found</div>');return;}
      var html='<table class="table table-sm table-striped"><thead><tr><th>ITS</th><th>Name</th><th>Sector</th><th>Sub Sector</th><th>Mobile</th></tr></thead><tbody>';
      rows.forEach(function(r){html+='<tr><td>'+( r.ITS_ID||'')+'</td><td>'+(r.Full_Name||'')+'</td><td>'+(r.Sector||'')+'</td><td>'+(r.Sub_Sector||'')+'</td><td>'+renderMobile(r.Mobile||r.RFM_Mobile)+'</td></tr>';});
      html+='</tbody></table>';$('#hofListContainer').html(html);
    }).catch(function(){$('#hofListLoading').hide();$('#hofListContainer').html('<div class="text-danger">Failed to load data</div>');});
  });
 
   // Hijri switcher
   function buildUrl(base,y,m){try{var u=new URL(base,window.location.origin);u.searchParams.set('hijri_year',y);u.searchParams.set('hijri_month',m);u.searchParams.set('ajax','1');return u.toString();}catch(e){return base+'?hijri_year='+y+'&hijri_month='+m+'&ajax=1';}}
   function loadMonth(year,month,pushState){
     var url=buildUrl(window.location.pathname,year,month);
     document.getElementById('monthLoader').style.display='block';
     fetch(url,{credentials:'same-origin'}).then(function(r){return r.text();}).then(function(html){
       document.getElementById('monthLoader').style.display='none';
       var doc=new DOMParser().parseFromString(html,'text/html');
       var newBlock=doc.querySelector('#thaali-month-block');var curBlock=document.querySelector('#thaali-month-block');if(newBlock&&curBlock)curBlock.replaceWith(newBlock);
       var newTitle=doc.querySelector('#hijri-current-title');var curTitle=document.getElementById('hijri-current-title');if(newTitle&&curTitle)curTitle.innerHTML=newTitle.innerHTML;
       var newBtns=doc.querySelectorAll('.hijri-nav-btn');var curBtns=document.querySelectorAll('.hijri-nav-btn');
       newBtns.forEach(function(b,i){if(!curBtns[i])return;curBtns[i].dataset.hijriYear=b.dataset.hijriYear||'';curBtns[i].dataset.hijriMonth=b.dataset.hijriMonth||'';curBtns[i].classList.toggle('disabled',b.classList.contains('disabled'));});
       if(pushState)history.pushState({year:year,month:month},'','?hijri_year='+year+'&hijri_month='+month);
     }).catch(function(){document.getElementById('monthLoader').style.display='none';});
   }
   document.addEventListener('click',function(e){var btn=e.target.closest('.hijri-nav-btn');if(!btn||btn.classList.contains('disabled'))return;e.preventDefault();loadMonth(btn.dataset.hijriYear,btn.dataset.hijriMonth,true);});
   window.addEventListener('popstate',function(ev){if(ev.state&&ev.state.year&&ev.state.month)loadMonth(ev.state.year,ev.state.month,false);});
 })();
 
 // Disable back button
 history.pushState(null, null, location.href);
 window.onpopstate = function(){ history.pushState(null, null, location.href); };
 
 $(function(){
  /* ── Sidebar toggle ── */
  var sidebarToggle = document.getElementById('sidebarToggle');
  var anjSidebar    = document.getElementById('anjSidebar');
  var sidebarOverlay= document.getElementById('anjSidebarOverlay');
  var sidebarClose  = document.getElementById('sidebarCloseBtn');
  function openSidebar(){ anjSidebar.classList.add('open'); sidebarOverlay.classList.add('show'); }
  function closeSidebar(){ anjSidebar.classList.remove('open'); sidebarOverlay.classList.remove('show'); }
  if(sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
  if(sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);
  if(sidebarClose)  sidebarClose.addEventListener('click', closeSidebar);

  // Quick menu search filter
  var quickSearch = document.getElementById('quickMenuSearch');
  var quickClear = document.getElementById('quickMenuClear');
  if(quickSearch){
    quickSearch.addEventListener('input', function(){
      var q = this.value.trim().toLowerCase();
      if (quickClear) quickClear.classList.toggle('visible', q.length > 0);
      var items = document.querySelectorAll('#anjSidebar .menu-item');
      items.forEach(function(item){
        var lbl = (item.querySelector('.menu-label')?.textContent||'').toLowerCase();
        item.style.display = lbl.includes(q) ? '' : 'none';
      });
    });
  }
  if(quickClear){
    quickClear.addEventListener('click', function(){
      if(quickSearch){ quickSearch.value = ''; quickSearch.dispatchEvent(new Event('input')); }
    });
  }

  /* Member search */
  let msTimer;
  $('#mswInput').on('input', function () {
    const q = this.value.trim();
    $('#mswClear').toggleClass('visible', q.length > 0);
    if (!q) { $('#mswDropdown').removeClass('open').empty(); return; }
    if (q.length < 2) return;
    clearTimeout(msTimer);
    msTimer = setTimeout(() => {
      $('#mswSpinner').addClass('active');
      $.ajax({
        url: '<?= base_url("MasoolMusaid/search_members_json") ?>',
        data: { query: q }, dataType: 'json',
        success(data) {
          $('#mswSpinner').removeClass('active');
          const $drop = $('#mswDropdown').empty().addClass('open');
          if (data && data.length) {
            data.forEach(item => {
              const init = (item.full_name||'M')[0].toUpperCase();
              const fem  = (item.gender||'').toLowerCase()==='female';
              $drop.append(`<div class="msw-result-item" onclick="location.href='<?= base_url("MasoolMusaid/viewmember/") ?>${item.its_id}'"><div class="msw-avatar${fem?' female':''}">${init}</div><div><div class="msw-res-name">${item.full_name}</div><div class="msw-res-meta">${item.sector||'No Sector'} | HOF: ${item.its_id||'—'}</div></div><div class="msw-its-badge">${item.its_id}</div></div>`);
            });
          } else {
            $drop.append('<div class="msw-no-results">No members found</div>');
          }
        },
        error() { $('#mswSpinner').removeClass('active'); }
      });
    }, 280);
  });
  $('#mswClear').on('click', () => { $('#mswInput').val('').trigger('input'); });
  $(document).on('click', e => { if (!$(e.target).closest('#member-search-block').length) $('#mswDropdown').removeClass('open'); });

  /* ══════════════════════════════════════════════════════════
     RSVP miqaat block
  ══════════════════════════════════════════════════════════ */
  (function(){
    var upcoming = <?= json_encode(array_values($upcoming_miqaats ?? [])) ?> || [];
    var initialRsvp = <?= json_encode($miqaat_rsvp ?? new stdClass()) ?>;
    var index = parseInt('<?= isset($initial_index) ? $initial_index : 0 ?>', 10) || 0;

    function _esc(s){ return String(s===null||s===undefined?'':s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

    function seedFromPhp(){
      var m = initialRsvp;
      if(!m) return;
      updateGuestCountsFromPayload(m);
      var wna = document.getElementById('willNotAttendCount');
      var ns  = document.getElementById('rsvpNotSubmittedCount');
      if(wna) wna.textContent = (m.will_not_attend||0);
      if(ns)  ns.textContent  = (m.rsvp_not_submitted||0);
    }

    function setMiqaatIdOnCards(mid){
      ['miqaatWillAttendCard','miqaatWillNotAttendCard','miqaatNotSubmittedCard',
       'miqaatGuestGentsCard','miqaatGuestLadiesCard','miqaatGuestChildrenCard'].forEach(function(id){
        var el = document.getElementById(id);
        if(el) el.setAttribute('data-miqaat-id', mid||'');
      });
    }

    function renderTitle(mi){
      var el = document.getElementById('miqaat-current-title');
      if(!el) return;
      if(!mi){ el.textContent='No miqaat found'; return; }
      var name = mi.name || ('Miqaat '+(mi.id||mi.miqaat_id||''));
      var dateLabel = mi.hijri_label || mi.date || '';
      el.innerHTML = '<div style="font-weight:700;color:var(--gold);font-size:.95rem;">'+_esc(name)+'</div>'
                   + (dateLabel?'<div style="font-size:.78rem;color:var(--text-3);margin-top:2px;">'+_esc(dateLabel)+'</div>':'');
    }

    function setViewDetails(mid){
      var btn = document.getElementById('miqaat-view-details');
      if(btn) btn.href = '<?= base_url("MasoolMusaid/rsvp_list") ?>' + (mid ? '?miqaat_id='+encodeURIComponent(mid) : '');
    }

    function showLoading(on){
      var el = document.getElementById('miqaatLoading');
      if(el) el.classList.toggle('active', on);
    }

    function renderFor(i){
      if(!upcoming||!upcoming.length){ renderTitle(null); return; }
      i = Math.max(0, Math.min(i, upcoming.length-1));
      index = i;
      var mi = upcoming[index];
      var miqId = String(mi.id||mi.miqaat_id||'');
      renderTitle(mi);
      setViewDetails(miqId);
      setMiqaatIdOnCards(miqId);

      var prevBtn = document.querySelector('.miqaat-nav-btn.prev');
      var nextBtn = document.querySelector('.miqaat-nav-btn.next');
      if(prevBtn) prevBtn.style.opacity = (index <= 0) ? '.35' : '1';
      if(nextBtn) nextBtn.style.opacity = (index >= upcoming.length-1) ? '.35' : '1';

      if(!miqId) return;
      showLoading(true);

      var url = window.location.pathname;
      try{ var u=new URL(url,window.location.origin); u.searchParams.set('format','json'); u.searchParams.set('miqaat_rsvp','1'); u.searchParams.set('miqaat_id',miqId); url=u.toString(); }
      catch(err){ url+='?format=json&miqaat_rsvp=1&miqaat_id='+encodeURIComponent(miqId); }

      fetch(url,{credentials:'same-origin'})
        .then(function(r){ return r.json(); })
        .then(function(data){
          showLoading(false);
          if(!data||!data.miqaat_rsvp) return;
          var m = data.miqaat_rsvp;
          updateGuestCountsFromPayload(m);
          var wna = document.getElementById('willNotAttendCount');
          var ns  = document.getElementById('rsvpNotSubmittedCount');
          if(wna) wna.textContent = (m.will_not_attend||0);
          if(ns)  ns.textContent  = (m.rsvp_not_submitted||0);

          var cUrl = '<?= base_url('MasoolMusaid/miqaat_rsvp_user_counts') ?>?miqaat_id='+encodeURIComponent(miqId);
          fetch(cUrl,{credentials:'same-origin'}).then(function(r){ return r.json(); }).then(function(cdata){
            if(cdata&&cdata.success){
              var ms=(m&&m.member_summary&&m.member_summary.total)?m.member_summary.total:0;
              var gs=(m&&m.guest_summary&&m.guest_summary.total)?m.guest_summary.total:0;
              var combined=(ms+gs)||(cdata.will_attend||0);
              var waEl = document.getElementById('willAttendCount');
              var wgEl = document.getElementById('willAttendGuest');
              if(waEl) waEl.textContent = combined;
              if(wgEl) wgEl.textContent = (gs>0?('+'+gs+' guests'):'');
              var wnaEl = document.getElementById('willNotAttendCount');
              var nsEl  = document.getElementById('rsvpNotSubmittedCount');
              if(wnaEl) wnaEl.textContent = (cdata.will_not_attend||0);
              if(nsEl)  nsEl.textContent  = (cdata.rsvp_not_submitted||0);
            }
          }).catch(function(e){ console.warn('RSVP counts fetch failed',e); });
        })
        .catch(function(e){ showLoading(false); console.error('RSVP fetch failed',e); });
    }

    function updateGuestCountsFromPayload(m){
      try{
        var gs = (m&&m.guest_summary)  ? m.guest_summary  : {gents:0,ladies:0,children:0,total:0};
        var ms = (m&&m.member_summary) ? m.member_summary : {gents:0,ladies:0,children:0,total:0};
        var cs = (m&&m.combined_summary)? m.combined_summary : {
          gents:   (ms.gents||0)+(gs.gents||0),
          ladies:  (ms.ladies||0)+(gs.ladies||0),
          children:(ms.children||0)+(gs.children||0),
          total:   (ms.total||0)+(gs.total||0)
        };
        var els = {guestGentsCount:cs.gents, guestLadiesCount:cs.ladies, guestChildrenCount:cs.children};
        Object.keys(els).forEach(function(id){ var el=document.getElementById(id); if(el) el.textContent=els[id]||0; });
        var breaks = {
          guestGentsBreakdown:   'Members: '+(ms.gents||0)+' | Guests: '+(gs.gents||0),
          guestLadiesBreakdown:  'Members: '+(ms.ladies||0)+' | Guests: '+(gs.ladies||0),
          guestChildrenBreakdown:'Members: '+(ms.children||0)+' | Guests: '+(gs.children||0)
        };
        Object.keys(breaks).forEach(function(id){ var el=document.getElementById(id); if(el) el.textContent=breaks[id]; });
        var waG = document.getElementById('willAttendGuest');
        if(waG) waG.textContent = (gs.total>0?('+'+gs.total+' guests'):'');
      }catch(e){ console.warn('updateGuestCountsFromPayload failed',e); }
    }

    document.addEventListener('click', function(e){
      var t = e.target.closest && e.target.closest('.miqaat-nav-btn');
      if(!t) return;
      e.preventDefault();
      if(t.classList.contains('prev')){
        if(index > 0){ renderFor(index-1); }
        else {
          var first = (upcoming&&upcoming.length) ? upcoming[0] : null;
          var beforeDate = first?(first.date||''):'';
          if(!beforeDate) return;
          var url = window.location.pathname;
          try{ var u=new URL(url,window.location.origin); u.searchParams.set('format','json'); u.searchParams.set('miqaat_prev','1'); u.searchParams.set('before_date',beforeDate); url=u.toString(); }
          catch(err){ url+='?format=json&miqaat_prev=1&before_date='+encodeURIComponent(beforeDate); }
          showLoading(true); t.style.pointerEvents='none';
          fetch(url,{credentials:'same-origin'}).then(function(r){ return r.json(); })
            .then(function(data){
              showLoading(false); t.style.pointerEvents='';
              if(!data||!data.success||!data.miqaat){
                var msgEl=document.getElementById('miqaatMessage');
                if(msgEl){msgEl.textContent='No earlier miqaat found';msgEl.style.display='block';clearTimeout(msgEl._t);msgEl._t=setTimeout(function(){msgEl.style.display='none';},3000);}
                return;
              }
              upcoming.unshift(data.miqaat); renderFor(0);
            }).catch(function(){ showLoading(false); t.style.pointerEvents=''; });
        }
      } else {
        if(index < upcoming.length-1){ renderFor(index+1); }
      }
    });

    document.addEventListener('click', function(e){
      var a = e.target.closest && e.target.closest('.open-miqaat-modal');
      if(!a) return;
      e.preventDefault();
      var dtype = a.getAttribute('data-type')||'rsvp';
      var mid   = a.getAttribute('data-miqaat-id') || (upcoming&&upcoming[index]&&(upcoming[index].id||upcoming[index].miqaat_id||''))||'';
      if(!mid) return;

      function _escH(s){ return String(s===null||s===undefined?'':s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
      function renderList(title,rows){
        var html='<div class="d-flex justify-content-between align-items-center mb-2"><strong>'+_escH(title)+'</strong>';
        try{rows=(rows||[]).filter(function(r){var s=((r&&(r.Sector||r.sector))||'')+'';var ss=((r&&(r.Sub_Sector||r.sub_sector||r.SubSector))||'')+'';return!((s.trim()===''&&ss.trim()===''));});}catch(e){}
        html+='<span class="text-muted">Count: '+(rows?rows.length:0)+'</span></div>';
        if(!rows||!rows.length)return html+'<div class="text-muted">No records found.</div>';
        try{rows.sort(function(a,b){var sa=((a.Sector||a.sector||'')+'').toLowerCase();var sb=((b.Sector||b.sector||'')+'').toLowerCase();return sa<sb?-1:sa>sb?1:0;});}catch(e){}
        html+='<table class="table table-sm table-striped"><thead><tr><th>ID</th><th>Name</th><th>Sector</th><th>Sub Sector</th><th>Mobile</th></tr></thead><tbody>';
        rows.forEach(function(r){
          var id=(r&&(r.ITS_ID||r.hof_id||r.ITS))||'';
          var name=(r&&(r.Full_Name||r.name))||'';
          var s=(r&&(r.Sector||r.sector))||'';
          var ss=(r&&(r.Sub_Sector||r.sub_sector||r.SubSector))||'';
          var mobile=(r&&(r.mobile||r.Mobile||r.RFM_Mobile))||'';
          html+='<tr><td>'+_escH(id)+'</td><td>'+_escH(name)+'</td><td>'+_escH(s)+'</td><td>'+_escH(ss)+'</td><td>'+renderMobile(mobile)+'</td></tr>';
        });
        return html+'</tbody></table>';
      }

      var lblMap={rsvp:"RSVP'd for Miqaat",no:"Will not attend",not_submitted:"RSVP not submitted",gents:"Gents",ladies:"Ladies",children:"Children"};
      $('#hofListLabel').text(lblMap[dtype]||"Members");
      $('#hofListContainer').html('');$('#hofListLoading').show();

      var miObj=(upcoming||[]).find(function(x){return String(x.id||x.miqaat_id||'')===String(mid);})||(upcoming[index]||{});
      $('#miqaatPopupMeta').html('<div style="font-weight:700;">'+_escH(miObj.name||'Miqaat')+'</div>'+(miObj.hijri_label||miObj.date?'<div class="text-muted">'+_escH(miObj.hijri_label||miObj.date)+'</div>':'')+'<div style="margin-top:8px;"><span class="badge badge-success mr-2" id="popupWillAttend">Will attend: 0</span><span class="badge badge-danger mr-2" id="popupWillNotAttend">Will not attend: 0</span><span class="badge badge-secondary" id="popupNotSubmitted">Not submitted: 0</span></div>').show();

      $('#hofListModal').modal('show');

      var url=window.location.pathname;
      try{ var u=new URL(url,window.location.origin); u.searchParams.set('format','json'); u.searchParams.set('miqaat_rsvp','1'); u.searchParams.set('miqaat_id',mid); url=u.toString(); }
      catch(err){ url+='?format=json&miqaat_rsvp=1&miqaat_id='+encodeURIComponent(mid); }

      fetch(url,{credentials:'same-origin'}).then(function(r){ return r.json(); })
        .then(function(data){
          $('#hofListLoading').hide();
          if(!data||!data.miqaat_rsvp){$('#hofListContainer').html('<div class="text-muted">No data found.</div>');return;}
          var m=data.miqaat_rsvp;
          var rows=[];var titleTxt='';
          if(dtype==='rsvp'){rows=(m.rsvp_member_list&&m.rsvp_member_list.length)?m.rsvp_member_list:(m.rsvp_list||[]);titleTxt="RSVP'd Members";}
          else if(dtype==='no'){rows=(m.not_rsvp_member_list&&m.not_rsvp_member_list.length)?m.not_rsvp_member_list:(m.not_rsvp_list||[]);titleTxt="Will not attend";}
          else if(dtype==='not_submitted'){rows=(m.not_submitted_member_list&&m.not_submitted_member_list.length)?m.not_submitted_member_list:[];titleTxt="Not Submitted";}
          else if(dtype==='gents'){rows=(m.rsvp_male_member_list&&m.rsvp_male_member_list.length)?m.rsvp_male_member_list:[];titleTxt="Gents";}
          else if(dtype==='ladies'){rows=(m.rsvp_female_member_list&&m.rsvp_female_member_list.length)?m.rsvp_female_member_list:[];titleTxt="Ladies";}
          else if(dtype==='children'){rows=(m.rsvp_children_member_list&&m.rsvp_children_member_list.length)?m.rsvp_children_member_list:[];titleTxt="Children";}
          $('#hofListContainer').html(renderList(titleTxt,rows));

          var cUrl='<?= base_url('MasoolMusaid/miqaat_rsvp_user_counts') ?>?miqaat_id='+encodeURIComponent(mid);
          fetch(cUrl,{credentials:'same-origin'}).then(function(r){return r.json();}).then(function(cdata){
            if(cdata&&cdata.success){
              var pw=document.getElementById('popupWillAttend');var pwn=document.getElementById('popupWillNotAttend');var pns=document.getElementById('popupNotSubmitted');
              if(pw)pw.textContent='Will attend: '+(cdata.will_attend||0);
              if(pwn)pwn.textContent='Will not attend: '+(cdata.will_not_attend||0);
              if(pns)pns.textContent='Not submitted: '+(cdata.rsvp_not_submitted||0);
            }
          }).catch(function(e){console.warn('popup counts failed',e);});
        })
        .catch(function(){ $('#hofListLoading').hide(); $('#hofListContainer').html('<div class="text-danger">Failed to load list.</div>'); });
    });

    if(document.readyState==='loading'){
      document.addEventListener('DOMContentLoaded',function(){ seedFromPhp(); renderFor(index); });
    } else {
      seedFromPhp(); renderFor(index);
    }
  })();
 });
</script>