<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* ═══════════════════════════════════════════════════
   DESIGN TOKENS — Gold / Warm theme
═══════════════════════════════════════════════════ */
:root {
  --font-display: 'Plus Jakarta Sans', sans-serif;
  --font-body:    'Plus Jakarta Sans', sans-serif;

  --gold:        #b8860b;
  --gold-light:  #e6c84a;
  --gold-muted:  #f5e9c0;
  --bg:          #faf7f0;
  --surface:     #ffffff;
  --surface-2:   #f7f4ec;
  --border:      #e8e0cc;
  --border-light:#f0ece0;

  --text-1:  #1a1610;
  --text-2:  #5a5244;
  --text-3:  #9c8f7a;

  --green:       #1a6645;
  --green-bg:    #eaf4ee;
  --red:         #b91c1c;
  --red-bg:      #fef2f2;
  --blue:        #1d4ed8;
  --blue-bg:     #eff6ff;
  --amber:       #b45309;
  --amber-bg:    #fffbeb;

  --radius-sm: 8px;
  --radius:    14px;
  --radius-lg: 20px;
  --radius-xl: 26px;

  --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
  --shadow:    0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
  --shadow-lg: 0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);

  --sidebar-w: 272px;
}

/* ── Scope everything ── */
#dashApp, #dashApp *, #dashApp *::before, #dashApp *::after { box-sizing: border-box; }
#dashApp { font-family: var(--font-body); color: var(--text-1); background: var(--bg); min-height: 100vh; }
#dashApp a { text-decoration: none; color: inherit; }

/* ═══════════════════════════════════════════════════
   LAYOUT
═══════════════════════════════════════════════════ */
#dashApp .dash-root {
  display: flex;
  min-height: 100vh;
  padding-top: 57px; /* navbar offset */
}

#dashApp .dash-sidebar {
  width: var(--sidebar-w);
  flex-shrink: 0;
  position: sticky;
  top: 57px;
  height: calc(100vh - 57px);
  overflow-y: auto;
  background: var(--surface);
  border-right: 1px solid var(--border);
  padding: 18px 12px 32px;
  scrollbar-width: thin;
  scrollbar-color: var(--border) transparent;
  z-index: 100;
}

#dashApp .dash-content {
  flex: 1;
  min-width: 0;
  padding: 24px 24px 60px;
}

/* ═══════════════════════════════════════════════════
   DASHBOARD HEADER
═══════════════════════════════════════════════════ */
#dashApp .dash-header { margin-bottom: 24px; }

#dashApp .dash-header-inner {
  background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
  border-radius: var(--radius-xl);
  padding: 24px 28px;
  position: relative; overflow: hidden;
  display: flex; align-items: center; justify-content: space-between; gap: 16px;
}
#dashApp .dash-header-inner::before {
  content: ''; position: absolute; inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}
#dashApp .dash-header-inner::after {
  content: ''; position: absolute; right: -50px; top: -50px;
  width: 240px; height: 240px;
  background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
  pointer-events: none;
}

#dashApp .dash-title-group { position: relative; z-index: 1; }
#dashApp .dash-eyebrow {
  font-size: .68rem; font-weight: 700; letter-spacing: 1.5px;
  text-transform: uppercase; color: rgba(255,255,255,.6); margin-bottom: 5px;
}
#dashApp .dash-title {
  font-family: 'Literata', Georgia, serif;
  font-size: 1.7rem; font-weight: 600; color: #fff; line-height: 1.15; margin: 0;
}
#dashApp .dash-title span { color: rgba(255,255,255,.75); font-size: 1rem; font-weight: 500; }

#dashApp .dash-header-badge {
  position: relative; z-index: 1;
  background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
  border-radius: var(--radius); padding: 10px 16px;
  backdrop-filter: blur(6px); text-align: center; flex-shrink: 0;
}
#dashApp .dash-header-badge .badge-val {
  font-size: 1.5rem; font-weight: 800; color: #fff; line-height: 1; display: block;
}
#dashApp .dash-header-badge .badge-lbl {
  font-size: .65rem; font-weight: 700; color: rgba(255,255,255,.65);
  letter-spacing: .5px; text-transform: uppercase; margin-top: 3px; display: block;
}

/* ═══════════════════════════════════════════════════
   SIDEBAR
═══════════════════════════════════════════════════ */
#dashApp .sidebar-brand {
  font-weight: 800; font-size: .85rem; color: var(--text-2);
  letter-spacing: .3px; margin-bottom: 12px; padding: 0 4px;
  display: flex; align-items: center; gap: 8px;
}
#dashApp .sidebar-brand .sb-icon {
  width: 28px; height: 28px; border-radius: 7px;
  background: var(--gold-muted); color: var(--gold);
  display: inline-flex; align-items: center; justify-content: center; font-size: .8rem;
}

#dashApp .sidebar-search {
  display: flex; align-items: center; gap: 8px;
  background: var(--bg); border: 1.5px solid var(--border);
  border-radius: var(--radius-sm); padding: 7px 11px; margin-bottom: 14px;
  transition: border-color .15s;
}
#dashApp .sidebar-search:focus-within { border-color: var(--gold); }
#dashApp .sidebar-search i { color: var(--text-3); font-size: .82rem; }
#dashApp .sidebar-search input {
  border: none; background: transparent; outline: none;
  font-family: var(--font-body); font-size: .82rem; color: var(--text-1); width: 100%;
}
#dashApp .sidebar-search input::placeholder { color: var(--text-3); }
#dashApp .s-clear { border: none; background: none; cursor: pointer; color: var(--text-3); font-size: .8rem; padding: 0; display: none; }
#dashApp .s-clear.vis { display: block; }

#dashApp .nav-section {
  font-size: .6rem; font-weight: 800; letter-spacing: 1.2px;
  text-transform: uppercase; color: var(--text-3);
  padding: 12px 6px 4px;
  display: flex; align-items: center; justify-content: space-between;
  cursor: pointer;
}
#dashApp .nav-section::after { content: '▾'; font-size: .65rem; transition: transform .2s; }
#dashApp .nav-section.collapsed::after { transform: rotate(-90deg); }

#dashApp .nav-list { list-style: none; margin: 0; padding: 0; }
#dashApp .nav-list li + li { margin-top: 2px; }

#dashApp .nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 8px 10px; border-radius: var(--radius-sm);
  color: var(--text-2); font-size: .83rem; font-weight: 500;
  transition: background .15s, color .15s;
}
#dashApp .nav-item:hover { background: var(--gold-muted); color: var(--gold); }
#dashApp .nav-icon {
  width: 28px; height: 28px; border-radius: 7px;
  display: inline-flex; align-items: center; justify-content: center;
  background: var(--surface-2); color: var(--text-3);
  font-size: .78rem; flex-shrink: 0; transition: background .15s, color .15s;
}
#dashApp .nav-item:hover .nav-icon { background: var(--gold-muted); color: var(--gold); }

/* ═══════════════════════════════════════════════════
   SECTION HEADER
═══════════════════════════════════════════════════ */
#dashApp .section-hd {
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 0 10px; margin-bottom: 14px;
  border-bottom: 1.5px solid var(--border-light);
}
#dashApp .section-hd-title {
  font-size: .88rem; font-weight: 800; color: var(--text-2);
  display: flex; align-items: center; gap: 8px; margin: 0;
}
#dashApp .section-hd-title .hd-icon {
  width: 26px; height: 26px; border-radius: 7px;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: .72rem; flex-shrink: 0;
  background: var(--gold-muted); color: var(--gold);
}
#dashApp .toggle-btn {
  width: 26px; height: 26px; border-radius: 50%;
  border: 1.5px solid var(--border); background: var(--surface-2);
  color: var(--text-3); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  font-size: .68rem; transition: all .2s; flex-shrink: 0;
}
#dashApp .btn-outline-secondary { border-color: var(--border); color: var(--text-2); font-size: .78rem; }
#dashApp .btn-outline-secondary:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); }
#dashApp .btn-outline-primary { border-color: var(--blue); color: var(--blue); font-size: .78rem; }
#dashApp .btn-sm { font-size: .76rem; }

/* ═══════════════════════════════════════════════════
   SURFACE CARD
═══════════════════════════════════════════════════ */
#dashApp .surf {
  background: var(--surface);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
  padding: 20px 22px;
  margin-bottom: 18px;
  position: relative; overflow: hidden;
  transition: box-shadow .2s;
}
#dashApp .surf:hover { box-shadow: var(--shadow); }
#dashApp .surf.compact { padding: 16px 18px; }

/* ═══════════════════════════════════════════════════
   OVERVIEW CARDS (stat cards)
═══════════════════════════════════════════════════ */
#dashApp .ov-card {
  background: var(--surface);
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  padding: 12px 14px;
  display: flex; align-items: center; gap: 12px;
  height: 100%;
  transition: border-color .2s, box-shadow .2s, transform .2s;
  position: relative; overflow: hidden;
}
#dashApp .ov-card:hover { border-color: var(--gold); box-shadow: var(--shadow); transform: translateY(-2px); }
#dashApp .ov-card::after {
  content: ''; position: absolute; bottom: 0; left: 0; right: 0;
  height: 2px; background: var(--gold);
  transform: scaleX(0); transition: transform .2s; transform-origin: left;
}
#dashApp .ov-card:hover::after { transform: scaleX(1); }
#dashApp .ov-card.green  { border-color: #86efac; } #dashApp .ov-card.green::after  { background: var(--green); }
#dashApp .ov-card.red    { border-color: #fca5a5; } #dashApp .ov-card.red::after    { background: var(--red); }
#dashApp .ov-icon {
  width: 36px; height: 36px; border-radius: 9px;
  display: inline-flex; align-items: center; justify-content: center;
  flex-shrink: 0; font-size: .95rem;
}
#dashApp .ov-body { display: flex; flex-direction: column; min-width: 0; }
#dashApp .ov-label { font-size: .7rem; color: var(--text-3); font-weight: 600; letter-spacing: .2px; line-height: 1.3; margin: 0; }
#dashApp .ov-value {
  font-size: 1.2rem; font-weight: 800;
  color: var(--text-1); line-height: 1.1; margin: 2px 0 0;
  /* FIX: prevent mobile overflow */
  word-break: break-all; overflow-wrap: anywhere;
}

/* ═══════════════════════════════════════════════════
   MINI CARDS
═══════════════════════════════════════════════════ */
#dashApp .mini-card {
  background: var(--surface-2); border: 1px solid var(--border);
  border-radius: var(--radius-sm); padding: 12px 10px; text-align: center;
  transition: box-shadow .15s, transform .15s;
}
#dashApp .mini-card:hover { box-shadow: var(--shadow-sm); transform: translateY(-1px); }
#dashApp .mini-val {
  font-size: 1.15rem; font-weight: 800; color: var(--text-1);
  display: block; line-height: 1;
  /* FIX: mobile overflow */
  word-break: break-all; overflow-wrap: anywhere;
  font-size: clamp(.8rem, 2.5vw, 1.15rem);
}
#dashApp .mini-val.green { color: var(--green); }
#dashApp .mini-val.red   { color: var(--red); }
#dashApp .mini-lbl {
  font-size: .65rem; font-weight: 700; letter-spacing: .4px;
  color: var(--text-3); text-transform: uppercase;
  margin-top: 5px; display: block;
}

/* ═══════════════════════════════════════════════════
   FMB SECTOR CARDS
═══════════════════════════════════════════════════ */
#dashApp .fmb-card {
  background: var(--surface-2); border: 1px solid var(--border);
  border-radius: var(--radius); padding: 12px 14px;
  display: flex; flex-direction: column; gap: 8px;
  transition: box-shadow .15s;
}
#dashApp .fmb-card:hover { box-shadow: var(--shadow-sm); }
#dashApp .fmb-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
#dashApp .fmb-name { font-weight: 700; color: var(--text-1); font-size: .85rem; }
#dashApp .fmb-name small { color: var(--text-3); font-weight: 500; }
#dashApp .fmb-amounts { display: flex; gap: 12px; flex-wrap: wrap; }
#dashApp .fmb-amounts span { font-size: .75rem; color: var(--text-3); }
#dashApp .fmb-amounts .val { font-weight: 700; color: var(--text-1); }
#dashApp .fmb-amounts .val.green { color: var(--green); }
#dashApp .fmb-amounts .val.red   { color: var(--red); }

#dashApp .prog-bar { height: 5px; border-radius: 5px; background: var(--border); overflow: hidden; }
#dashApp .prog-fill { height: 100%; border-radius: 5px; background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 100%); transition: width .4s; }
#dashApp .prog-fill.green-fill { background: linear-gradient(90deg, var(--green) 0%, #4ade80 100%); }

/* ═══════════════════════════════════════════════════
   MEMBER SEARCH WIDGET
═══════════════════════════════════════════════════ */
#member-search-block { overflow: visible !important; }

#dashApp .msw-wrap { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
#dashApp .msw-heading {
  font-size: .78rem; font-weight: 800; color: var(--text-2);
  text-transform: uppercase; letter-spacing: .5px;
}
#dashApp .msw-sub { font-size: .7rem; color: var(--text-3); }
#dashApp .msw-right { flex: 1 1 auto; display: flex; gap: 10px; align-items: center; justify-content: flex-end; }
#dashApp .msw-ig { position: relative; flex: 1 1 240px; max-width: 460px; }
#dashApp .msw-ig .ico { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-3); font-size: .82rem; pointer-events: none; }
#dashApp #mswInput {
  width: 100%; height: 38px; padding: 0 36px 0 34px;
  border: 1.5px solid var(--border); border-radius: var(--radius-sm);
  font-family: var(--font-body); font-size: .86rem;
  color: var(--text-1); background: var(--surface-2); outline: none;
  transition: border-color .15s, box-shadow .15s;
}
#dashApp #mswInput:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(184,134,11,.12); background: var(--surface); }
#dashApp .msw-spinner { position: absolute; right: 34px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; border-radius: 50%; border: 2px solid rgba(0,0,0,.08); border-top-color: var(--gold); animation: mspin .7s linear infinite; display: none; }
#dashApp .msw-spinner.active { display: block; }
@keyframes mspin { to { transform: translateY(-50%) rotate(360deg); } }
#dashApp .msw-clear { position: absolute; right: 9px; top: 50%; transform: translateY(-50%); background: var(--border); border: none; border-radius: 4px; width: 20px; height: 20px; display: none; align-items: center; justify-content: center; cursor: pointer; color: var(--text-3); font-size: .68rem; line-height: 1; }
#dashApp .msw-clear.visible { display: flex; }
#dashApp #mswDropdown { position: absolute; top: calc(100% + 5px); left: 0; right: 0; background: var(--surface); border: 1.5px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-lg); z-index: 1050; display: none; max-height: 280px; overflow-y: auto; }
#dashApp #mswDropdown.open { display: block; }
#dashApp .msw-item { display: flex; align-items: center; gap: 11px; padding: 10px 13px; cursor: pointer; transition: background .12s; border-bottom: 1px solid var(--border-light); }
#dashApp .msw-item:last-child { border-bottom: none; }
#dashApp .msw-item:hover { background: var(--gold-muted); }
#dashApp .msw-av { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--gold), #c9a227); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .85rem; flex-shrink: 0; }
#dashApp .msw-av.f { background: linear-gradient(135deg, #b45309, #f59e0b); }
#dashApp .msw-name { font-weight: 700; color: var(--text-1); font-size: .85rem; }
#dashApp .msw-meta { font-size: .7rem; color: var(--text-3); margin-top: 2px; }
#dashApp .msw-its { margin-left: auto; background: var(--gold-muted); color: var(--gold); font-size: .66rem; font-weight: 800; padding: 2px 7px; border-radius: 40px; white-space: nowrap; flex-shrink: 0; }
#dashApp .msw-empty { padding: 16px; text-align: center; color: var(--text-3); font-size: .85rem; }

/* ═══════════════════════════════════════════════════
   MOBILE TOOLBAR
═══════════════════════════════════════════════════ */
#dashApp .mob-toolbar {
  display: flex; align-items: center; justify-content: space-between;
  background: var(--surface); border: 1px solid var(--border);
  border-radius: var(--radius); padding: 9px 13px;
  margin-bottom: 14px; box-shadow: var(--shadow-sm);
}
#dashApp .mob-menu-btn {
  display: inline-flex; align-items: center; gap: 7px; padding: 6px 13px;
  border-radius: 40px; background: var(--gold-muted); color: var(--gold);
  font-weight: 700; font-size: .82rem; border: 1px solid #e6c84a44;
}
#dashApp .mob-menu-btn .mic { width: 24px; height: 24px; border-radius: 6px; background: var(--surface); color: var(--gold); display: flex; align-items: center; justify-content: center; font-size: .78rem; }

/* ═══════════════════════════════════════════════════
   SECTOR INCHARGE TOGGLE
═══════════════════════════════════════════════════ */
#dashApp .sc-incharge-toggle {
  font-size: .73rem; color: var(--text-3); cursor: pointer;
  display: flex; align-items: center; justify-content: space-between;
  padding: 6px 0 0; border-top: 1px solid var(--border-light); margin-top: 8px;
  background: none; border-left: none; border-right: none; border-bottom: none;
  width: 100%; text-align: left; font-family: var(--font-body);
}
#dashApp .sc-incharge-toggle:hover { color: var(--gold); }

/* ═══════════════════════════════════════════════════
   TAKHMEEN HINT
═══════════════════════════════════════════════════ */
#dashApp .tk-hint { font-size: .73rem; color: var(--text-3); text-align: center; margin: 6px 0 0; cursor: pointer; }

/* ═══════════════════════════════════════════════════
   CONTAINER YEAR LABEL
═══════════════════════════════════════════════════ */
#dashApp .container-year { position: absolute; right: 20px; top: 20px; font-size: .73rem; font-weight: 700; color: var(--text-3); }

/* ═══════════════════════════════════════════════════
   5-COL GRID
═══════════════════════════════════════════════════ */
@media (min-width: 768px) { #dashApp .col-md-5th { flex: 0 0 20%; max-width: 20%; } }

/* ═══════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════ */
@media (max-width: 991px) {
  #dashApp .dash-sidebar {
    position: fixed; top: 0; left: 0; height: 100vh;
    transform: translateX(-100%); transition: transform .25s;
    z-index: 1050; width: 290px; padding-top: 16px;
  }
  #dashApp .dash-sidebar.open { transform: translateX(0); box-shadow: var(--shadow-lg); }
  #dashApp .dash-content { padding: 14px 12px 48px; }
  #dashApp .dash-title { font-size: 1.3rem; }
  #dashApp .dash-header-inner { padding: 18px 20px; }

  /* Mobile amount overflow fixes */
  #dashApp .ov-value { font-size: clamp(.85rem, 3.5vw, 1.2rem); }
  #dashApp .mini-val { font-size: clamp(.72rem, 2.8vw, 1.1rem); }

  #dashApp .sidebar-overlay { position: fixed; inset: 0; background: rgba(26,22,16,.4); z-index: 1040; display: none; }
  #dashApp .sidebar-overlay.show { display: block; }
  #dashApp .sidebar-close-btn { display: flex; position: absolute; top: 11px; right: 11px; width: 28px; height: 28px; border-radius: 7px; background: var(--gold-muted); color: var(--gold); border: none; align-items: center; justify-content: center; font-size: .95rem; cursor: pointer; }
}

@media (min-width: 992px) { #dashApp .mob-toolbar { display: none !important; } #dashApp .sidebar-close-btn { display: none; } }

/* Prevent any amount value from overflowing on very small screens */
@media (max-width: 400px) {
  #dashApp .ov-value, #dashApp .mini-val { font-size: .75rem; letter-spacing: -.3px; }
  #dashApp .fmb-amounts { flex-direction: column; gap: 4px; }
}

#dashApp .surf canvas { max-height: 230px; }
</style>

<?php
  if (!function_exists('format_inr')) {
    function format_inr($num) {
      $num = (int)round((float)$num);
      $n = strval($num); $len = strlen($n);
      if ($len <= 3) return $n;
      $last3 = substr($n, -3); $rest = substr($n, 0, $len - 3);
      $parts = [];
      while (strlen($rest) > 2) { $parts[] = substr($rest, -2); $rest = substr($rest, 0, strlen($rest) - 2); }
      if ($rest !== '') $parts[] = $rest;
      return implode(',', array_reverse($parts)) . ',' . $last3;
    }
  }
?>

<!-- Sidebar overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div id="dashApp">
<div class="dash-root">

  <!-- ══ SIDEBAR ══ -->
  <aside class="dash-sidebar" id="dashSidebar">
    <button class="sidebar-close-btn" id="sidebarCloseBtn">&times;</button>
    <div class="sidebar-brand"><span class="sb-icon"><i class="fa fa-tachometer"></i></span> Quick Menu</div>

    <div class="sidebar-search">
      <i class="fa fa-search"></i>
      <input id="quickMenuSearch" type="text" placeholder="Search menu…" autocomplete="off">
      <button class="s-clear" id="quickMenuClear">&times;</button>
    </div>

    <div class="nav-section" data-target="navRaza">Raza</div>
    <ul class="nav-list" id="navRaza">
      <li><a class="nav-item" href="<?php echo base_url('amilsaheb/EventRazaRequest?event_type=1') ?>"><span class="nav-icon"><i class="fa fa-handshake-o"></i></span><span>Miqaat Raza Request</span></a></li>
      <li><a class="nav-item" href="<?php echo base_url('amilsaheb/EventRazaRequest?event_type=2') ?>"><span class="nav-icon"><i class="fa fa-handshake-o"></i></span><span>Kaaraj Raza Request</span></a></li>
      <li><a class="nav-item" href="<?php echo base_url('amilsaheb/UmoorRazaRequest') ?>"><span class="nav-icon"><i class="fa fa-list"></i></span><span>12 Umoor Raza Request</span></a></li>
    </ul>

    <div class="nav-section" data-target="navFinance">Finance</div>
    <ul class="nav-list" id="navFinance">
      <li><a class="nav-item" href="<?php echo base_url('common/sabeeltakhmeen?from=amilsaheb') ?>"><span class="nav-icon"><i class="fa fa-money"></i></span><span>Sabeel Takhmeen</span></a></li>
      <li><a class="nav-item" href="<?php echo base_url('common/fmbtakhmeen?from=amilsaheb') ?>"><span class="nav-icon"><i class="fa fa-cutlery"></i></span><span>FMB Thaali Takhmeen</span></a></li>
      <li><a class="nav-item" href="<?php echo base_url('common/fmb_general_contributions?from=amilsaheb') ?>"><span class="nav-icon"><i class="fa fa-inr"></i></span><span>FMB General Contributions</span></a></li>
      <li><a class="nav-item" href="<?php echo base_url('amilsaheb/qardanhasana') ?>"><span class="nav-icon"><i class="fa fa-leaf"></i></span><span>Qardan Hasana</span></a></li>
      <li><a class="nav-item" href="<?= base_url('amilsaheb/corpusfunds_details') ?>"><span class="nav-icon"><i class="fa fa-university"></i></span><span>Corpus Funds</span></a></li>
      <li><a class="nav-item" href="<?= base_url('anjuman/laagat_rent_payments?charge_type=laagat') ?>"><span class="nav-icon"><i class="fa fa-calculator"></i></span><span>Laagat</span><?php if (isset($laagat_summary) && $laagat_summary['due'] > 0): ?><span class="badge badge-danger ml-auto" style="font-size: 11px; padding: 3px 6px; font-weight: 600;">₹<?= format_inr($laagat_summary['due']) ?></span><?php endif; ?></a></li>
      <li><a class="nav-item" href="<?= base_url('anjuman/laagat_rent_payments?charge_type=rent') ?>"><span class="nav-icon"><i class="fa fa-building-o"></i></span><span>Rent</span><?php if (isset($rent_summary) && $rent_summary['due'] > 0): ?><span class="badge badge-danger ml-auto" style="font-size: 11px; padding: 3px 6px; font-weight: 600;">₹<?= format_inr($rent_summary['due']) ?></span><?php endif; ?></a></li>
      <li><a class="nav-item" href="<?= base_url('amilsaheb/ekramfunds_details') ?>"><span class="nav-icon"><i class="fa fa-gift"></i></span><span>Ekram Funds</span></a></li>
      <li><a class="nav-item" href="<?php echo base_url('amilsaheb/wajebaat_details') ?>"><span class="nav-icon"><i class="fa fa-coins"></i></span><span>Wajebaat</span></a></li>
      <li><a class="nav-item" href="<?= base_url('amilsaheb/expense') ?>"><span class="nav-icon"><i class="fa fa-file-text-o"></i></span><span>Expense &amp; Budget Module</span></a></li>
      <li><a class="nav-item" href="<?php echo base_url('madresa') ?>"><span class="nav-icon"><i class="fa fa-graduation-cap"></i></span><span>Madresa</span></a></li>
    </ul>

    <div class="nav-section" data-target="navReports">Reports</div>
    <ul class="nav-list" id="navReports">
      <li><a class="nav-item" href="<?php echo base_url('common/miqaatattendance?from=amilsaheb') ?>"><span class="nav-icon"><i class="fa fa-users"></i></span><span>Miqaat Attendance</span></a></li>
      <li><a class="nav-item" href="<?php echo base_url('common/thaali_signup_dashboard?from=amilsaheb') ?>"><span class="nav-icon"><i class="fa fa-bar-chart"></i></span><span>Thaali Sign-up Dashboard</span></a></li>
      <li><a class="nav-item" href="<?php echo base_url('common/rsvp_list?from=amilsaheb') ?>"><span class="nav-icon"><i class="fa fa-check-square-o"></i></span><span>RSVP Report</span></a></li>
    </ul>

    <div class="nav-section" data-target="navAppt">Appointments</div>
    <ul class="nav-list" id="navAppt">
      <li><a class="nav-item" href="<?php echo base_url('amilsaheb/manage_appointment') ?>"><span class="nav-icon"><i class="fa fa-calendar-check-o"></i></span><span>Appointments</span></a></li>
      <li><a class="nav-item" href="<?php echo base_url('amilsaheb/slots_calendar') ?>"><span class="nav-icon"><i class="fa fa-clock-o"></i></span><span>Manage Time Slots</span></a></li>
    </ul>

    <div class="nav-section" data-target="navActivity">Activity</div>
    <ul class="nav-list" id="navActivity">
      <li><a class="nav-item" href="<?php echo base_url('Amilsaheb/asharaohbat') ?>"><span class="nav-icon"><i class="fa fa-calendar"></i></span><span>Ashara Ohbat</span></a></li>
      <li><a class="nav-item" href="<?php echo base_url('Amilsaheb/ashara_attendance') ?>"><span class="nav-icon"><i class="fa fa-user"></i></span><span>Ashara Attendance</span></a></li>
      <li><a class="nav-item" href="<?php echo base_url('common/fmbcalendar?from=amilsaheb') ?>"><span class="nav-icon"><i class="fa fa-calendar"></i></span><span>FMB Calendar</span></a></li>
    </ul>
  </aside>

  <!-- ══ CONTENT ══ -->
  <main class="dash-content">

    <!-- Mobile toolbar -->
    <div class="mob-toolbar">
      <button id="sidebarToggle" class="mob-menu-btn">
        <span class="mic"><i class="fa fa-bars"></i></span> Menu
      </button>
      <span style="font-size:.82rem;font-weight:700;color:var(--text-2);">Amilsaheb Dashboard</span>
    </div>

    <!-- Dashboard header -->
    <div class="dash-header">
      <div class="dash-header-inner">
        <div class="dash-title-group">
          <p class="dash-eyebrow">Anjuman-e-Saifee</p>
          <h1 class="dash-title">
            Amilsaheb Dashboard
            <br><span><?php $hijri_year = isset($year_daytype_stats['hijri_year']) ? $year_daytype_stats['hijri_year'] : '1446'; echo $hijri_year . 'H — ' . date('Y'); ?></span>
          </h1>
        </div>
        <div class="dash-header-badge">
          <span class="badge-val"><?= (int)($stats['active_inactive']['active'] ?? 0) ?></span>
          <span class="badge-lbl">Active Members</span>
        </div>
      </div>
    </div>

    <!-- Member Search -->
    <div class="surf mb-3" id="member-search-block" style="overflow:visible;">
      <div class="msw-wrap">
        <div class="d-none d-md-flex" style="flex-direction:column;gap:2px;">
          <span class="msw-heading"><i class="fa fa-search" style="margin-right:4px;"></i>Member Search</span>
          <span class="msw-sub">Search by name or ITS ID</span>
        </div>
        <div class="msw-right">
          <div class="msw-ig">
            <i class="fa fa-search ico"></i>
            <input type="text" id="mswInput" placeholder="Type name or ITS ID…" autocomplete="off">
            <div class="msw-spinner" id="mswSpinner"></div>
            <button class="msw-clear" id="mswClear">&times;</button>
            <div id="mswDropdown" role="listbox"></div>
          </div>
          <a href="<?php echo base_url('amilsaheb/mumineendirectory') ?>" class="btn btn-outline-secondary btn-sm" style="white-space:nowrap;border-radius:8px;font-size:.78rem;font-weight:700;border-color:var(--border);color:var(--text-2);">
            <i class="fa fa-users"></i> All Members
          </a>
        </div>
      </div>
    </div>

    <!-- ── Jamaat Overview ── -->
    <div class="surf">
      <h2 style="font-size:1rem;font-weight:800;color:var(--text-1);margin:0 0 18px;text-align:center;letter-spacing:-.2px;">Jamaat Overview</h2>

      <!-- Member Status -->
      <div class="section-hd">
        <h3 class="section-hd-title"><span class="hd-icon"><i class="fa fa-toggle-on"></i></span> Member Status</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseAmilMemberActivity" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseAmilMemberActivity">
        <div class="row mb-3">
          <div class="col-6">
            <a href="<?= base_url('amilsaheb/mumineendirectory?status=active') ?>">
              <div class="ov-card green">
                <div class="ov-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-check-circle"></i></div>
                <div class="ov-body"><span class="ov-label">Active Members</span><span class="ov-value"><?= (int)($stats['active_inactive']['active'] ?? 0) ?></span></div>
              </div>
            </a>
          </div>
          <div class="col-6">
            <a href="<?= base_url('amilsaheb/mumineendirectory?status=inactive') ?>">
              <div class="ov-card red">
                <div class="ov-icon" style="background:#fef2f2;color:#b91c1c;"><i class="fa fa-times-circle"></i></div>
                <div class="ov-body"><span class="ov-label">Inactive Members</span><span class="ov-value"><?= (int)($stats['active_inactive']['inactive'] ?? 0) ?></span></div>
              </div>
            </a>
          </div>
        </div>
      </div>

      <!-- ITS Sabeel Match -->
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon"><i class="fa fa-exchange"></i></span> ITS Sabeel Match</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseItsSabeelMatch" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseItsSabeelMatch">
        <div class="row mb-3">
          <?php
          $statCards = [
            ['its_sabeel_both_khar',  'ITS & Sabeel in '.jamaat_place(), 'fa-home',         '#f5e9c0','#b8860b'],
            ['sabeel_khar_its_out',   'Sabeel in Khar, ITS out',         'fa-external-link','#ecfeff','#0891b2'],
            ['its_khar_sabeel_out',   'ITS in Khar, Sabeel out',         'fa-sign-out',     '#fff7ed','#b45309'],
            ['both_not_khar',         'Both not in Khar',                'fa-ban',           '#fef2f2','#b91c1c'],
          ];
          foreach ($statCards as [$key, $label, $icon, $bg, $color]): ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?its_sabeel_match='.$key) ?>">
              <div class="ov-card">
                <div class="ov-icon" style="background:<?= $bg ?>;color:<?= $color ?>;"><i class="fa <?= $icon ?>"></i></div>
                <div class="ov-body"><span class="ov-label"><?= $label ?></span><span class="ov-value"><?= (int)($stats['active_inactive'][$key] ?? 0) ?></span></div>
              </div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- General Overall Stats -->
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon"><i class="fa fa-info-circle"></i></span> General Overall Stats</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseGeneralOverallStats" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseGeneralOverallStats">
        <div class="row mb-3">
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('amilsaheb/mumineendirectory?status=active') ?>"><div class="ov-card"><div class="ov-icon" style="background:var(--gold-muted);color:var(--gold);"><i class="fa fa-users"></i></div><div class="ov-body"><span class="ov-label">Total Members</span><span class="ov-value"><?= (int)($stats['active_inactive']['active'] ?? 0) ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('amilsaheb/mumineendirectory?status=active&filter=hof_fm_type&value=HOF') ?>"><div class="ov-card"><div class="ov-icon" style="background:var(--gold-muted);color:var(--gold);"><i class="fa fa-user"></i></div><div class="ov-body"><span class="ov-label">HOF</span><span class="ov-value"><?= $stats['HOF'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('amilsaheb/mumineendirectory?status=active&filter=hof_fm_type&value=FM') ?>"><div class="ov-card"><div class="ov-icon" style="background:var(--gold-muted);color:var(--gold);"><i class="fa fa-user-plus"></i></div><div class="ov-body"><span class="ov-label">FM</span><span class="ov-value"><?= $stats['FM'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('amilsaheb/mumineendirectory?status=active&filter=gender&value=male') ?>"><div class="ov-card"><div class="ov-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="fa fa-male"></i></div><div class="ov-body"><span class="ov-label">Males</span><span class="ov-value"><?= $stats['Mardo'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('amilsaheb/mumineendirectory?status=active&filter=gender&value=female') ?>"><div class="ov-card"><div class="ov-icon" style="background:#fdf2f8;color:#9d174d;"><i class="fa fa-female"></i></div><div class="ov-body"><span class="ov-label">Females</span><span class="ov-value"><?= $stats['Bairo'] ?></span></div></div></a></div>
          <?php
          $ageGroups=[['0','4','Age 0-4',$stats['Age_0_4']],['5','15','Age 5-15',$stats['Age_5_15']],['16','25','Age 16-25',$stats['Age_16_25']],['26','65','Age 26-65',$stats['Age_26_65']],['66','','Above 65',$stats['Buzurgo']]];
          foreach($ageGroups as[$mn,$mx,$lbl,$val]):$url=$mx?base_url("amilsaheb/mumineendirectory?status=active&filter=age_range&min=$mn&max=$mx"):base_url("amilsaheb/mumineendirectory?status=active&filter=age_range&min=$mn");?>
          <div class="col-6 col-md-5th mb-3"><a href="<?= $url ?>"><div class="ov-card"><div class="ov-icon" style="background:var(--green-bg);color:var(--green);"><i class="fa fa-child"></i></div><div class="ov-body"><span class="ov-label"><?= $lbl ?></span><span class="ov-value"><?= $val ?></span></div></div></a></div>
          <?php endforeach; ?>
        </div>
      </div>

      <?php
      $status_counts = isset($stats['status_counts']) ? $stats['status_counts'] : [];
      $statusGroups = [
        'deeni_status'       => ['label'=>'Deeni Status',       'icon'=>'fa-star',           'bg'=>'#f5f3ff','color'=>'#7c3aed','key'=>'deeni',       'id'=>'collapseAmilDeeni'],
        'health_status'      => ['label'=>'Health Status',      'icon'=>'fa-heartbeat',      'bg'=>'#fef2f2','color'=>'#b91c1c','key'=>'health',      'id'=>'collapseAmilHealth'],
        'residential_status' => ['label'=>'Residential Status', 'icon'=>'fa-building',       'bg'=>'#eff6ff','color'=>'#1d4ed8','key'=>'residential', 'id'=>'collapseAmilResidential'],
        'Qualification'      => ['label'=>'Dunyavi Education',  'icon'=>'fa-graduation-cap', 'bg'=>'#eaf4ee','color'=>'#1a6645','key'=>'education',   'id'=>'collapseAmilEducation'],
      ];
      $CI =& get_instance();
      $CI->load->model('MemberStatusM');
      $deeni_map = MemberStatusM::deeni_status_options();
      $health_map = MemberStatusM::health_status_options();
      $res_map = MemberStatusM::residential_status_options();

      foreach ($statusGroups as $filterKey => $g):
        if (empty($status_counts[$g['key']])) continue; ?>
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon" style="background:<?= $g['bg'] ?>;color:<?= $g['color'] ?>;"><i class="fa <?= $g['icon'] ?>"></i></span><?= $g['label'] ?></h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#<?= $g['id'] ?>" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
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
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter='.$filterKey.'&value='.rawurlencode($lbl)) ?>">
              <div class="ov-card"><div class="ov-icon" style="background:<?= $g['bg'] ?>;color:<?= $g['color'] ?>;"><i class="fa <?= $g['icon'] ?>"></i></div><div class="ov-body"><span class="ov-label"><?= htmlspecialchars($display_lbl) ?></span><span class="ov-value"><?= $cnt ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Deeni Taalim -->
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="fa fa-graduation-cap"></i></span> Deeni Taalim Stats</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseAmilEduTracking" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseAmilEduTracking">
        <div class="row mb-3">
          <?php
          $deeniCards=[
            [base_url('amilsaheb/mumineendirectory?status=Active&min=5&max=15'),              '#eff6ff','#1d4ed8','fa-child',     'Total Eligible (5-15)',   $stats['deeni_eligible']??0],
            [base_url('amilsaheb/mumineendirectory?status=Active&min=5&max=15&madresa_deprived=0'),'#eaf4ee','#1a6645','fa-book','Deeni Taalim Taking',    $stats['deeni_taking']??0],
            [base_url('amilsaheb/mumineendirectory?status=Active&min=5&max=15&madresa_deprived=1'),'#fff7ed','#b45309','fa-book','Not Taking Deeni Taalim',$stats['madresa_deprived']??0],
          ];
          foreach($deeniCards as[$url,$bg,$color,$icon,$lbl,$val]):?>
          <div class="col-12 col-md-4 mb-3">
            <a href="<?= $url ?>">
              <div class="ov-card"><div class="ov-icon" style="background:<?= $bg ?>;color:<?= $color ?>;"><i class="fa <?= $icon ?>"></i></div><div class="ov-body"><span class="ov-label"><?= $lbl ?></span><span class="ov-value"><?= (int)$val ?> <small style="font-size:.7rem;color:var(--text-3);font-weight:600;">Farzando</small></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Occupation -->
      <?php if (!empty($status_counts['occupation'])): ?>
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon"><i class="fa fa-briefcase"></i></span> All Occupation</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseAmilOccupation" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseAmilOccupation">
        <div class="row mb-3">
          <?php foreach ($status_counts['occupation'] as $lbl => $cnt): if ($lbl==='None'||$lbl==='') continue; ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=Occupation&value='.rawurlencode($lbl)) ?>">
              <div class="ov-card"><div class="ov-icon" style="background:var(--surface-2);color:var(--text-2);"><i class="fa fa-briefcase"></i></div><div class="ov-body"><span class="ov-label"><?= htmlspecialchars($lbl) ?></span><span class="ov-value"><?= $cnt ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Sector-wise -->
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-map-marker"></i></span> Sector-wise Members</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseGroupSectors" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseGroupSectors">
        <div class="row mb-3">
          <?php
          $sectorRows = isset($stats['Sectors']) ? $stats['Sectors'] : [];
          if (!empty($sectorRows)) {
            $sectorRows = array_values(array_filter($sectorRows, fn($r) => trim($r['Sector']??'')!=='' && strtolower($r['Sector']??'')!=='unassigned'));
            usort($sectorRows, fn($a,$b) => intval($b['total']??0) <=> intval($a['total']??0));
            foreach ($sectorRows as $idx => $row):
              $hof = intval($row['hof_count']??$row['HOF']??0);
              $fm  = intval($row['fm_count'] ??$row['FM'] ??0);
              $cid = 'secIncharge'.$idx;
          ?>
          <div class="col-12 col-md-3 mb-3">
            <div class="ov-card" style="display:flex;flex-direction:column;align-items:stretch;height:100%;">
              <a href="<?= base_url('amilsaheb/mumineendirectory?filter=sector&value='.rawurlencode($row['Sector']??'')) ?>" style="display:flex;align-items:center;gap:10px;flex:1;">
                <div class="ov-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-map-marker"></i></div>
                <div class="ov-body" style="width:100%;">
                  <span class="ov-label"><?= htmlspecialchars($row['Sector']??'Unassigned') ?></span>
                  <span class="ov-value" style="font-size:.95rem;">HOF <?= $hof ?> &nbsp;·&nbsp; FM <?= $fm ?></span>
                </div>
              </a>
              <?php if (!empty($row['sub_sectors'])||!empty($row['Sector_Incharge_Name'])): ?>
              <div>
                <button class="sc-incharge-toggle" onclick="$('#<?= $cid ?>').collapse('toggle');$(this).find('i').toggleClass('fa-chevron-down fa-chevron-up')">
                  <span><i class="fa fa-info-circle"></i> View Incharges</span>
                  <i class="fa fa-chevron-down" style="font-size:.62rem;"></i>
                </button>
                <div class="collapse" id="<?= $cid ?>">
                  <?php if (!empty($row['sub_sectors'])): ?>
                  <ul style="list-style:none;padding:5px 0 0;margin:0;font-size:.76rem;color:var(--text-2);">
                    <?php foreach ($row['sub_sectors'] as $sub): ?>
                    <li style="padding:5px 6px;background:var(--bg);border-radius:6px;margin-bottom:3px;">
                      <strong><?= htmlspecialchars($sub['Sub_Sector']) ?></strong><br>
                      <?php if (!empty($sub['Sub_Sector_Incharge_Name'])): ?><i class="fa fa-male" style="color:#1d4ed8;margin-right:3px;"></i><?= htmlspecialchars($sub['Sub_Sector_Incharge_Name']) ?><br><?php endif; ?>
                      <?php if (!empty($sub['Sub_Sector_Incharge_Female_Name'])): ?><i class="fa fa-female" style="color:#9d174d;margin-right:3px;"></i><?= htmlspecialchars($sub['Sub_Sector_Incharge_Female_Name']) ?><?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                  </ul>
                  <?php endif; ?>
                </div>
              </div>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; } ?>
        </div>
      </div>

      <!-- Marital Stats -->
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon" style="background:#fff0f6;color:#9d174d;"><i class="fa fa-heart"></i></span> Marital Stats</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseGroupMarital" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseGroupMarital">
        <div class="row mb-3">
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?status=Active&marital_status=Single&min=21&max=40') ?>">
              <div class="ov-card"><div class="ov-icon" style="background:var(--gold-muted);color:var(--gold);"><i class="fa fa-heart"></i></div><div class="ov-body"><span class="ov-label">Single (21-40)</span><span class="ov-value"><?= isset($stats['singles_21_40'])?(int)$stats['singles_21_40']:0 ?></span></div></div>
            </a>
          </div>
          <?php
          $ms = isset($marital_status_counts)?$marital_status_counts:[];
          foreach($ms as $label=>$count):
            $ll=strtolower(trim($label));
            if($ll==='unknown'||$ll===''||$ll==='single') continue;
            $iconBg='#f5f5f7';$iconColor='#6b7280';$icon='fa-info-circle';
            if(str_contains($ll,'married'))  {$icon='fa-user';$iconBg='#fff0f6';$iconColor='#9d174d';}
            elseif(str_contains($ll,'engag'))  {$icon='fa-star';$iconBg='#fffbeb';$iconColor='#b45309';}
            elseif(str_contains($ll,'divorc')) {$icon='fa-user';$iconBg='#fef2f2';$iconColor='#b91c1c';}
            elseif(str_contains($ll,'widow'))  {$icon='fa-user-secret';$iconBg='#ecfeff';$iconColor='#0891b2';}
          ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?status=Active&marital_status='.rawurlencode($label)) ?>">
              <div class="ov-card"><div class="ov-icon" style="background:<?= $iconBg ?>;color:<?= $iconColor ?>;"><i class="fa <?= $icon ?>"></i></div><div class="ov-body"><span class="ov-label"><?= htmlspecialchars($label) ?></span><span class="ov-value"><?= (int)$count ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Weekly Signup Performance -->
      <?php
      $sw = isset($dashboard_data['this_week_sector_signup_avg'])?$dashboard_data['this_week_sector_signup_avg']:null;
      $sw_sectors = ($sw && is_array($sw['sectors']??null))?$sw['sectors']:[];
      if (!empty($sw_sectors)): ?>
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon"><i class="fa fa-line-chart"></i></span> Weekly Signup Performance</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseGroupWeekly" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseGroupWeekly">
        <div class="row mb-2">
          <?php foreach ($sw_sectors as $sr): ?>
          <div class="col-6 col-md-3 mb-3">
            <div class="mini-card"><span class="mini-val"><?= round((float)($sr['avg_signup']??0),1) ?>%</span><span class="mini-lbl"><?= htmlspecialchars($sr['sector']??'Unassigned') ?></span></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div><!-- /surf Jamaat Overview -->

    <!-- ── FMB Takhmeen ── -->
    <div class="surf">
      <div class="section-hd">
        <h3 class="section-hd-title"><span class="hd-icon" style="background:#fffbeb;color:#b45309;"><i class="fa fa-money"></i></span> FMB Takhmeen</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseFmbTakhmeen" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseFmbTakhmeen">
        <?php
        $fmbData = isset($dashboard_data['fmb_takhmeen_sector'])?$dashboard_data['fmb_takhmeen_sector']:[];
        $fYear   = isset($dashboard_data['fmb_takhmeen_year'])?$dashboard_data['fmb_takhmeen_year']:($hijri_year??null);
        if ($fYear) echo '<div class="container-year">Hijri '.htmlspecialchars($fYear).'</div>';
        $fTotal=0;$fPaid=0;
        foreach ($fmbData as $r){$t=(float)($r['total_takhmeen']??0);$p=(float)($r['total_paid']??0);$fTotal+=$t;$fPaid+=$p;}
        $fDue=max(0,$fTotal-$fPaid);
        ?>
        <div class="row mb-2" role="button" data-toggle="collapse" data-target="#fmb-sector-details" style="cursor:pointer;">
          <div class="col-4"><div class="mini-card"><span class="mini-val">₹<?= format_inr($fTotal) ?></span><span class="mini-lbl">Total</span></div></div>
          <div class="col-4"><div class="mini-card"><span class="mini-val green">₹<?= format_inr($fPaid) ?></span><span class="mini-lbl">Paid</span></div></div>
          <div class="col-4"><div class="mini-card"><span class="mini-val red">₹<?= format_inr($fDue) ?></span><span class="mini-lbl">Due</span></div></div>
        </div>
        <p class="tk-hint" data-toggle="collapse" data-target="#fmb-sector-details"><i class="fa fa-chevron-down" style="margin-right:3px;font-size:.68rem;"></i>Click to view sector-wise</p>
        <div id="fmb-sector-details" class="collapse">
          <?php foreach ($fmbData as $row):
            $t=(float)($row['total_takhmeen']??0);$p=(float)($row['total_paid']??0);$d=(float)($row['outstanding']??max(0,$t-$p));
            $pct=$t>0?min(100,round(($p/$t)*100)):0;
          ?>
          <div class="fmb-card mb-2">
            <div class="fmb-head">
              <div class="fmb-name"><i class="fa fa-map-marker" style="color:var(--gold);margin-right:5px;"></i><?= htmlspecialchars($row['sector']??'Unassigned') ?> <small>(<?= (int)($row['members']??0) ?>)</small></div>
              <div class="fmb-amounts"><span>Total <span class="val">₹<?= format_inr($t) ?></span></span><span>Paid <span class="val green">₹<?= format_inr($p) ?></span></span><span>Due <span class="val red">₹<?= format_inr($d) ?></span></span></div>
            </div>
            <div class="prog-bar"><div class="prog-fill" style="width:<?= $pct ?>%"></div></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ── Sabeel Takhmeen ── -->
    <div class="surf">
      <div class="section-hd">
        <h3 class="section-hd-title"><span class="hd-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-credit-card"></i></span> Sabeel Takhmeen</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseSabTakhmeen" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseSabTakhmeen">
        <?php
        $sabData=isset($dashboard_data['sabeel_takhmeen_sector'])?$dashboard_data['sabeel_takhmeen_sector']:[];
        $sYear=isset($dashboard_data['sabeel_takhmeen_year'])?$dashboard_data['sabeel_takhmeen_year']:($hijri_year??null);
        if ($sYear) echo '<div class="container-year">Hijri '.htmlspecialchars($sYear).'</div>';
        $sTotal=0;$sPaid=0;
        foreach ($sabData as $r){$t=(float)($r['total_takhmeen']??0);$p=(float)($r['total_paid']??0);$sTotal+=$t;$sPaid+=$p;}
        $sDue=max(0,$sTotal-$sPaid);
        ?>
        <div class="row mb-2" role="button" data-toggle="collapse" data-target="#sab-sector-details" style="cursor:pointer;">
          <div class="col-4"><div class="mini-card"><span class="mini-val">₹<?= format_inr($sTotal) ?></span><span class="mini-lbl">Total</span></div></div>
          <div class="col-4"><div class="mini-card"><span class="mini-val green">₹<?= format_inr($sPaid) ?></span><span class="mini-lbl">Paid</span></div></div>
          <div class="col-4"><div class="mini-card"><span class="mini-val red">₹<?= format_inr($sDue) ?></span><span class="mini-lbl">Due</span></div></div>
        </div>
        <p class="tk-hint" data-toggle="collapse" data-target="#sab-sector-details"><i class="fa fa-chevron-down" style="margin-right:3px;font-size:.68rem;"></i>Click to view sector-wise</p>
        <div id="sab-sector-details" class="collapse">
          <?php foreach ($sabData as $row):
            $t=(float)($row['total_takhmeen']??0);$p=(float)($row['total_paid']??0);$d=(float)($row['outstanding']??max(0,$t-$p));
            $pct=$t>0?min(100,round(($p/$t)*100)):0;
          ?>
          <div class="fmb-card mb-2">
            <div class="fmb-head">
              <div class="fmb-name"><i class="fa fa-map-marker" style="color:var(--green);margin-right:5px;"></i><?= htmlspecialchars($row['sector']??'Unassigned') ?> <small>(<?= (int)($row['members']??0) ?>)</small></div>
              <div class="fmb-amounts"><span>Total <span class="val">₹<?= format_inr($t) ?></span></span><span>Paid <span class="val green">₹<?= format_inr($p) ?></span></span><span>Due <span class="val red">₹<?= format_inr($d) ?></span></span></div>
            </div>
            <div class="prog-bar"><div class="prog-fill green-fill" style="width:<?= $pct ?>%"></div></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ── Finance Modules ── -->
    <div class="row">
      <?php
      $cTot=0;$cRec=0;$cCount=0;
      if (isset($corpus_funds)&&is_array($corpus_funds)){$cCount=count($corpus_funds);foreach($corpus_funds as $f){$cTot+=(float)($f['assigned_total']??0);$cRec+=(float)($f['paid_total']??0);}}
      $cPen=max(0,$cTot-$cRec);
      $wa=isset($dashboard_data['wajebaat_summary'])?$dashboard_data['wajebaat_summary']:['total'=>0,'received'=>0,'due'=>0];
      $laagat=isset($laagat_summary)?$laagat_summary:['total'=>0,'received'=>0,'due'=>0];
      $rent=isset($rent_summary)?$rent_summary:['total'=>0,'received'=>0,'due'=>0];
      $qh=isset($qh_all_schemes_totals)?$qh_all_schemes_totals:['mohammedi'=>0,'taher'=>0,'husain'=>0,'total'=>0];
      $rz=isset($dashboard_data['raza_summary'])?$dashboard_data['raza_summary']:['pending'=>0,'approved'=>0,'rejected'=>0];

      $finCards = [
        ['id'=>'collapseCorpus',   'icon'=>'fa-university',  'bg'=>'var(--gold-muted)', 'color'=>'var(--gold)',  'title'=>'Corpus Funds',   'url'=>base_url('amilsaheb/corpusfunds_receive'),
          'rows'=>[['Total','₹'.format_inr($cTot),''],['Received','₹'.format_inr($cRec),'green'],['Pending','₹'.format_inr($cPen),'red']], 'footer'=>'Funds: '.$cCount],
        ['id'=>'collapseWajebaat', 'icon'=>'fa-coins',       'bg'=>'#fffbeb',           'color'=>'#b45309',     'title'=>'Wajebaat',       'url'=>base_url('amilsaheb/wajebaat'),
          'rows'=>[['Total','₹'.format_inr($wa['total']),''],['Received','₹'.format_inr($wa['received']),'green'],['Pending','₹'.format_inr($wa['due']),'red']], 'footer'=>''],
        ['id'=>'collapseLaagat',   'icon'=>'fa-calculator',  'bg'=>'#ecfdf5',           'color'=>'#059669',     'title'=>'Laagat',         'url'=>base_url('anjuman/laagat_rent_payments?charge_type=laagat'),
          'rows'=>[['Total','₹'.format_inr($laagat['total']),''],['Received','₹'.format_inr($laagat['received']),'green'],['Pending','₹'.format_inr($laagat['due']),'red']], 'footer'=>''],
        ['id'=>'collapseRent',     'icon'=>'fa-building-o',  'bg'=>'#f0fdfa',           'color'=>'#0d9488',     'title'=>'Rent',           'url'=>base_url('anjuman/laagat_rent_payments?charge_type=rent'),
          'rows'=>[['Total','₹'.format_inr($rent['total']),''],['Received','₹'.format_inr($rent['received']),'green'],['Pending','₹'.format_inr($rent['due']),'red']], 'footer'=>''],
        ['id'=>'collapseRaza',     'icon'=>'fa-file-text-o', 'bg'=>'#eff6ff',           'color'=>'#1d4ed8',     'title'=>'Raza Summary',   'url'=>'',
          'rows'=>[['Pending',(int)$rz['pending'],''],['Approved',(int)$rz['approved'],'green'],['Rejected',(int)$rz['rejected'],'red']], 'footer'=>''],
      ];
      foreach ($finCards as $fc): ?>
      <div class="col-md-6 mb-3">
        <div class="surf compact h-100" style="margin-bottom:0;">
          <div class="section-hd">
            <h3 class="section-hd-title"><span class="hd-icon" style="background:<?= $fc['bg'] ?>;color:<?= $fc['color'] ?>;"><i class="fa <?= $fc['icon'] ?>"></i></span> <?= $fc['title'] ?></h3>
            <button class="toggle-btn" data-toggle="collapse" data-target="#<?= $fc['id'] ?>" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
          </div>
          <div class="collapse show" id="<?= $fc['id'] ?>">
            <div class="row text-center mb-2">
              <?php foreach ($fc['rows'] as [$lbl,$val,$cls]): ?>
              <div class="col-4"><div class="mini-card"><span class="mini-val <?= $cls ?>"><?= $val ?></span><span class="mini-lbl"><?= $lbl ?></span></div></div>
              <?php endforeach; ?>
            </div>
            <?php if ($fc['footer']): ?><p class="tk-hint"><?= $fc['footer'] ?></p><?php endif; ?>
            <?php if ($fc['url']): ?><div style="text-align:center;margin-top:6px;"><a href="<?= $fc['url'] ?>" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem;border-color:var(--border);color:var(--text-2);">View All</a></div><?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Qardan Hasana -->
      <div class="col-md-6 mb-3">
        <div class="surf compact h-100" style="margin-bottom:0;">
          <div class="section-hd">
            <h3 class="section-hd-title"><span class="hd-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-leaf"></i></span> Qardan Hasana</h3>
            <button class="toggle-btn" data-toggle="collapse" data-target="#collapseQH" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
          </div>
          <div class="collapse show" id="collapseQH">
            <div class="row text-center mb-2">
              <div class="col-3"><div class="mini-card"><span class="mini-val">₹<?= format_inr($qh['mohammedi']) ?></span><span class="mini-lbl">Mohammedi</span></div></div>
              <div class="col-3"><div class="mini-card"><span class="mini-val">₹<?= format_inr($qh['taher']) ?></span><span class="mini-lbl">Taher</span></div></div>
              <div class="col-3"><div class="mini-card"><span class="mini-val">₹<?= format_inr($qh['husain']) ?></span><span class="mini-lbl">Husain</span></div></div>
              <div class="col-3"><div class="mini-card"><span class="mini-val green">₹<?= format_inr($qh['total']) ?></span><span class="mini-lbl">Total</span></div></div>
            </div>
            <div style="text-align:center;"><a href="<?= base_url('amilsaheb/qardanhasana') ?>" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem;border-color:var(--border);color:var(--text-2);">View All</a></div>
          </div>
        </div>
      </div>
    </div><!-- /Finance row -->

  </main>
</div><!-- /dash-root -->
</div><!-- /#dashApp -->

<script>
$(document).ready(function () {
  /* Sidebar toggle */
  $('#sidebarToggle').on('click', function () {
    $('#dashSidebar').addClass('open');
    if (!$('#sidebarOverlay').length) $('body').append('<div class="sidebar-overlay" id="sidebarOverlay"></div>');
    $('#sidebarOverlay').fadeIn(200).addClass('show');
  });
  $(document).on('click', '#sidebarCloseBtn, #sidebarOverlay', function () {
    $('#dashSidebar').removeClass('open');
    $('#sidebarOverlay').fadeOut(200, function () { $(this).remove(); });
  });

  /* Quick menu search */
  $('#quickMenuSearch').on('input', function () {
    const q = this.value.toLowerCase();
    $('#dashApp .s-clear').toggleClass('vis', q !== '');

    // If empty, show everything and exit
    if (!q) {
      $('#dashApp .nav-item').parent().show();
      $('#dashApp .nav-section').show();
      $('#dashApp .nav-list').show();
      return;
    }

    $('#dashApp .nav-item').each(function () {
      $(this).parent().toggle($(this).text().toLowerCase().includes(q));
    });
    $('#dashApp .nav-section').each(function () {
      const list = $(this).next('.nav-list');
      const vis = list.find('li:visible').length > 0;
      $(this).toggle(vis); list.toggle(vis);
    });
  });
  $('#quickMenuClear').on('click', () => { $('#quickMenuSearch').val('').trigger('input'); });

  /* Nav section collapse */
  $(document).on('click', '#dashApp .nav-section', function () {
    $(this).next('.nav-list').slideToggle(180);
    $(this).toggleClass('collapsed');
  });

  /* Section header click-to-collapse */
  $(document).on('click', '#dashApp .section-hd', function (e) {
    if ($(e.target).closest('button, a').length) return;
    const target = $(this).find('.toggle-btn').data('target');
    if (target) $(target).collapse('toggle');
  });

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
        url: '<?= base_url("amilsaheb/search_members_json") ?>',
        data: { query: q }, dataType: 'json',
        success(data) {
          $('#mswSpinner').removeClass('active');
          const $drop = $('#mswDropdown').empty().addClass('open');
          if (data && data.length) {
            data.forEach(item => {
              const init = (item.full_name||'M')[0].toUpperCase();
              const fem  = (item.gender||'').toLowerCase()==='female';
              $drop.append(`<div class="msw-item" onclick="location.href='<?= base_url("amilsaheb/viewmember/") ?>${item.its_id}'"><div class="msw-av${fem?' f':''}">${init}</div><div><div class="msw-name">${item.full_name}</div><div class="msw-meta">${item.sector||'No Sector'} | HOF: ${item.hof_id||'—'}</div></div><div class="msw-its">${item.its_id}</div></div>`);
            });
          } else {
            $drop.append('<div class="msw-empty">No members found</div>');
          }
        },
        error() { $('#mswSpinner').removeClass('active'); }
      });
    }, 280);
  });
  $('#mswClear').on('click', () => { $('#mswInput').val('').trigger('input'); });
  $(document).on('click', e => { if (!$(e.target).closest('#member-search-block').length) $('#mswDropdown').removeClass('open'); });
});
</script>