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

#anjApp .sb-search { display: flex; align-items: center; gap: 8px; background: var(--bg); border: 1.5px solid var(--border); border-radius: 8px; padding: 7px 10px; margin-bottom: 12px; transition: border-color .15s; }
#anjApp .sb-search:focus-within { border-color: var(--gold); }
#anjApp .sb-search i { color: var(--text-3); font-size: .8rem; }
#anjApp .sb-search input { border: none; background: transparent; outline: none; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .81rem; color: var(--text-1); width: 100%; }
#anjApp .sb-search input::placeholder { color: var(--text-3); }

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
#anjApp .menu-label { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

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
#anjApp .chart-container canvas { max-height: 230px; }

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
#anjApp .stats-label { font-size: .65rem; font-weight: 700; letter-spacing: .4px; color: var(--text-3); text-transform: uppercase; display: block; }

/* ── Section title ── */
#anjApp .section-title { font-size: 1rem; font-weight: 700; color: var(--text-1); margin-bottom: 16px; }

/* ── Stats cards ── */
#anjApp .stats-card {
  background: var(--surface); border: 1px solid var(--border); border-radius: 10px;
  padding: 14px 16px; margin-bottom: 12px; transition: background .2s;
}
#anjApp .stats-card:hover { background: var(--surface-2); }
#anjApp .stats-icon { font-size: 1.6rem; margin-bottom: 8px; color: var(--gold); }

/* ── Sidebar menu ── */
#anjApp .sidebar-menu {
  background: transparent; border: none; border-radius: 0;
  padding: 0; position: static; box-shadow: none;
  max-height: none; overflow: visible;
}
#anjApp .sidebar-menu .menu-title { font-weight: 800; font-size: .84rem; color: var(--text-2); margin-bottom: 10px; padding: 0 4px; }
#anjApp .sidebar-menu .menu-search { display: flex; align-items: center; gap: 8px; background: var(--bg); border: 1.5px solid var(--border); border-radius: 8px; padding: 7px 10px; margin-bottom: 12px; }
#anjApp .sidebar-menu .menu-search i { color: var(--text-3); font-size: .8rem; }
#anjApp .sidebar-menu .menu-search input { border: none; background: transparent; outline: none; font-family: inherit; font-size: .81rem; color: var(--text-1); width: 100%; }
#anjApp .sidebar-menu .menu-search input::placeholder { color: var(--text-3); }
#anjApp .sidebar-menu .menu-search-clear { border: none; background: none; cursor: pointer; color: var(--text-3); font-size: .78rem; padding: 0; width: auto; height: auto; border-radius: 0; box-shadow: none; }

/* ── Container-year ── */
#anjApp .container-year { position: absolute; right: 18px; top: 18px; font-size: .72rem; font-weight: 700; color: var(--text-3); }

/* ── Button styles ── */
#anjApp .btn-outline-secondary { border-color: var(--border); color: var(--text-2); font-size: .78rem; }
#anjApp .btn-outline-secondary:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); }
#anjApp .btn-outline-primary { border-color: var(--blue); color: var(--blue); font-size: .78rem; }
#anjApp .btn-sm { font-size: .76rem; }

/* ── Hijri switcher ── */
#anjApp .hijri-switcher .chev-box {
  width: 40px; height: 40px; border: 1px solid var(--border);
  border-radius: 6px; display: flex; align-items: center; justify-content: center;
  background: var(--surface); color: var(--text-2); font-size: 1.1rem;
  transition: background .15s, color .15s;
}
#anjApp .hijri-switcher .chev-box:hover { background: var(--gold-muted); color: var(--gold); }
#anjApp .hijri-switcher .hijri-nav-btn.disabled { pointer-events: none; opacity: .4; }
#anjApp .hijri-title { font-weight: 700; color: var(--gold) !important; font-size: 1.1rem; }

/* ── FIX 1: Bootstrap modals inside #anjApp ── */
#anjApp .modal-backdrop { display: none !important; }

/* ── Custom modal overlays (scoped inside #anjApp now) ── */
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

/* ── FIX 3: RSVP miqaat block loading state ── */
#anjApp #miqaatLoading {
  display: none; position: absolute; left: 0; right: 0; top: 0; bottom: 0;
  align-items: center; justify-content: center;
  background: rgba(255,255,255,.6); border-radius: 14px; z-index: 5;
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

      <div class="menu-section">Raza</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('Umoor/RazaRequest') ?>"><span class="menu-icon"><i class="fa fa-handshake-o"></i></span><span class="menu-label">Raza Requests</span></a></li>
      </ul>

      <div class="menu-section">Activity</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('umoor/asharaohbat') ?>"><span class="menu-icon"><i class="fa fa-calendar"></i></span><span class="menu-label">Ashara Ohbat</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('umoor/ashara_attendance') ?>"><span class="menu-icon"><i class="fa fa-user-check"></i></span><span class="menu-label">Ashara Attendance</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/rsvp_list?from=umoor') ?>"><span class="menu-icon"><i class="fa fa-check-square-o"></i></span><span class="menu-label">RSVP Report</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/miqaatattendance?from=umoor') ?>"><span class="menu-icon"><i class="fa fa-users"></i></span><span class="menu-label">Miqaat Attendance Report</span></a></li>
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
      <span style="font-size:.82rem;font-weight:700;color:var(--text-2);">Deeniyah Dashboard</span>
    </div>

    <!-- Dashboard header -->
    <div class="anj-header">
      <div class="anj-header-inner">
        <div class="anj-title-group">
          <p class="anj-eyebrow">Anjuman-e-Saifee</p>
          <h1 class="anj-title">
            Deeniyah Dashboard
            <br><span><?php $hijri_year = isset($year_daytype_stats['hijri_year']) ? $year_daytype_stats['hijri_year'] : '1446'; echo $hijri_year . 'H — ' . date('Y'); ?></span>
          </h1>
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
          <a href="<?php echo base_url('Umoor/Mumineendirectory') ?>" class="btn btn-outline-secondary btn-sm" style="white-space:nowrap;border-radius:9px;font-weight:700;">
            <i class="fa fa-users"></i> All Members
          </a>
        </div>
      </div>
    </div>

    <!-- ══ JAMAAT OVERVIEW ══ -->
    <div class="chart-container grouped-block">
      <h4 class="section-title text-center mt-2 mb-4">Jamaat Overview</h4>

      <!-- Member Status -->
      <div class="section-header-standard ml-3 mr-3">
        <h5 class="section-title"><i class="fa fa-toggle-on"></i> Member Status</h5>
        <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseMemberActivity" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseMemberActivity">
        <div class="row px-3">
          <div class="col-6 col-md-6 mb-3">
            <a href="<?= base_url('Umoor/Mumineendirectory?status=active') ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card" style="border-color:#86efac;">
                <div class="overview-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-check-circle"></i></div>
                <div class="overview-body"><span class="overview-title">Active Members</span><span class="overview-value"><?= (int)($stats['active_inactive']['active'] ?? 0) ?></span></div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-6 mb-3">
            <a href="<?= base_url('Umoor/Mumineendirectory?status=inactive') ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card" style="border-color:#fca5a5;">
                <div class="overview-icon" style="background:#fef2f2;color:#b91c1c;"><i class="fa fa-times-circle"></i></div>
                <div class="overview-body"><span class="overview-title">Inactive Members</span><span class="overview-value"><?= (int)($stats['active_inactive']['inactive'] ?? 0) ?></span></div>
              </div>
            </a>
          </div>
        </div>
      </div>

      <!-- ITS Sabeel Match -->
      <div class="section-header-standard ml-3 mr-3">
        <h5 class="section-title"><i class="fa fa-exchange"></i> ITS Sabeel Match</h5>
        <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseItsSabeelMatch" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseItsSabeelMatch">
        <div class="row px-3">
          <div class="col-6 col-md-3 mb-3"><a href="<?= base_url('Umoor/Mumineendirectory?its_sabeel_match=its_sabeel_both_khar') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#f5e9c0;color:#b8860b;"><i class="fa fa-home"></i></div><div class="overview-body"><span class="overview-title">ITS &amp; Sabeel both in <?= htmlspecialchars(jamaat_place()) ?></span><span class="overview-value"><?= (int)($stats['active_inactive']['its_sabeel_both_khar'] ?? 0) ?></span></div></div></a></div>
          <div class="col-6 col-md-3 mb-3"><a href="<?= base_url('Umoor/Mumineendirectory?its_sabeel_match=sabeel_khar_its_out') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#ecfeff;color:#0891b2;"><i class="fa fa-external-link"></i></div><div class="overview-body"><span class="overview-title">Sabeel in Khar, ITS not in <?= htmlspecialchars(jamaat_place()) ?></span><span class="overview-value"><?= (int)($stats['active_inactive']['sabeel_khar_its_out'] ?? 0) ?></span></div></div></a></div>
          <div class="col-6 col-md-3 mb-3"><a href="<?= base_url('Umoor/Mumineendirectory?its_sabeel_match=its_khar_sabeel_out') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#fff7ed;color:#b45309;"><i class="fa fa-sign-out"></i></div><div class="overview-body"><span class="overview-title">ITS in Khar, Sabeel not in <?= htmlspecialchars(jamaat_place()) ?></span><span class="overview-value"><?= (int)($stats['active_inactive']['its_khar_sabeel_out'] ?? 0) ?></span></div></div></a></div>
          <div class="col-6 col-md-3 mb-3"><a href="<?= base_url('Umoor/Mumineendirectory?its_sabeel_match=both_not_khar') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#fef2f2;color:#b91c1c;"><i class="fa fa-ban"></i></div><div class="overview-body"><span class="overview-title">Sabeel &amp; ITS both not in <?= htmlspecialchars(jamaat_place()) ?></span><span class="overview-value"><?= (int)($stats['active_inactive']['both_not_khar'] ?? 0) ?></span></div></div></a></div>
        </div>
      </div>

      <!-- General Overall Stats -->
      <div class="section-header-standard ml-3 mr-3">
        <h5 class="section-title"><i class="fa fa-info-circle"></i> General Overall Stats</h5>
        <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseGeneralOverallStats" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseGeneralOverallStats">
        <div class="row px-3">
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('Umoor/Mumineendirectory?status=active') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon"><i class="fa fa-users"></i></div><div class="overview-body"><span class="overview-title">Total Members</span><span class="overview-value"><?= (int)($stats['active_inactive']['active'] ?? 0) ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('Umoor/Mumineendirectory?status=active&filter=hof_fm_type&value=HOF') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon"><i class="fa fa-user"></i></div><div class="overview-body"><span class="overview-title">HOF</span><span class="overview-value"><?= $stats['HOF'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('Umoor/Mumineendirectory?status=active&filter=hof_fm_type&value=FM') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon"><i class="fa fa-user-plus"></i></div><div class="overview-body"><span class="overview-title">FM</span><span class="overview-value"><?= $stats['FM'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('Umoor/Mumineendirectory?status=active&filter=gender&value=male') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="fa fa-male"></i></div><div class="overview-body"><span class="overview-title">Males</span><span class="overview-value"><?= $stats['Mardo'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('Umoor/Mumineendirectory?status=active&filter=gender&value=female') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#fdf2f8;color:#9d174d;"><i class="fa fa-female"></i></div><div class="overview-body"><span class="overview-title">Females</span><span class="overview-value"><?= $stats['Bairo'] ?></span></div></div></a></div>

          <?php $ageGs=[['0','4','Age 0-4',$stats['Age_0_4']],['5','15','Age 5-15',$stats['Age_5_15']],['16','25','Age 16-25',$stats['Age_16_25']],['26','65','Age 26-65',$stats['Age_26_65']],['66','','Above 65',$stats['Buzurgo']]];
          foreach($ageGs as[$mn,$mx,$lbl,$val]):$url=$mx?base_url("Umoor/Mumineendirectory?status=active&filter=age_range&min=$mn&max=$mx"):base_url("Umoor/Mumineendirectory?status=active&filter=age_range&min=$mn");?>
          <div class="col-6 col-md-5th mb-3"><a href="<?= $url ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-child"></i></div><div class="overview-body"><span class="overview-title"><?= $lbl ?></span><span class="overview-value"><?= $val ?></span></div></div></a></div>
          <?php endforeach; ?>
        </div>
      </div>

      <?php
      $status_counts = isset($stats['status_counts']) ? $stats['status_counts'] : [];
      $groups1 = [
        'health_status'      => ['label'=>'Health Status',      'icon'=>'fa-heartbeat',     'bg'=>'#fef2f2','color'=>'#b91c1c','count_key'=>'health',      'id'=>'collapseHealth'],
        'deeni_status'       => ['label'=>'Deeni Status',       'icon'=>'fa-star',          'bg'=>'#f5f3ff','color'=>'#7c3aed','count_key'=>'deeni',       'id'=>'collapseDeeni'],
        'residential_status' => ['label'=>'Residential Status', 'icon'=>'fa-building',      'bg'=>'#eff6ff','color'=>'#1d4ed8','count_key'=>'residential', 'id'=>'collapseResidential'],
        'Qualification'      => ['label'=>'Dunyavi Education',  'icon'=>'fa-graduation-cap','bg'=>'#eaf4ee','color'=>'#1a6645','count_key'=>'education',   'id'=>'collapseEducation'],
      ];
      $CI =& get_instance();
      $CI->load->model('MemberStatusM');
      $deeni_map = MemberStatusM::deeni_status_options();
      $health_map = MemberStatusM::health_status_options();
      $res_map = MemberStatusM::residential_status_options();

      foreach ($groups1 as $filterKey => $g):
        if (empty($status_counts[$g['count_key']])) continue; ?>
      <div class="section-header-standard ml-3 mr-3">
        <h5 class="section-title"><i class="fa <?= $g['icon'] ?>"></i> <?= $g['label'] ?></h5>
        <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#<?= $g['id'] ?>" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="<?= $g['id'] ?>">
        <div class="row px-3">
          <?php foreach ($status_counts[$g['count_key']] as $lbl => $cnt): 
            if ($lbl==='None'||$lbl==='') continue; 
            $display_lbl = $lbl;
            if ($g['count_key'] === 'deeni' && isset($deeni_map[$lbl])) {
              $display_lbl = $deeni_map[$lbl];
            } elseif ($g['count_key'] === 'health' && isset($health_map[$lbl])) {
              $display_lbl = $health_map[$lbl];
            } elseif ($g['count_key'] === 'residential' && isset($res_map[$lbl])) {
              $display_lbl = $res_map[$lbl];
            }
            $display_lbl = preg_replace('/\s*\((Active|Inactive)\)$/i', '', $display_lbl);
          ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('Umoor/Mumineendirectory?filter='.$filterKey.'&value='.rawurlencode($lbl)) ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card"><div class="overview-icon" style="background:<?= $g['bg'] ?>;color:<?= $g['color'] ?>;"><i class="fa <?= $g['icon'] ?>"></i></div><div class="overview-body"><span class="overview-title"><?= htmlspecialchars($display_lbl) ?></span><span class="overview-value"><?= $cnt ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>

    </div><!-- /grouped-block -->

    <!-- Deeni Taalim -->
    <div class="row">
      <div class="col-12">
        <div class="chart-container member-types-block compact" style="padding:10px;">
          <div class="section-header-standard">
            <h4 class="section-title"><i class="fa fa-graduation-cap"></i> Deeni Taalim Stats</h4>
            <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseEduAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
          </div>
          <div class="collapse show" id="collapseEduAnjuman">
            <div class="row px-3">
              <div class="col-12 col-md-4 mb-3"><a href="<?= base_url('Umoor/Mumineendirectory?status=Active&min=5&max=15') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="fa fa-child"></i></div><div class="overview-body"><span class="overview-title">Total Eligible (Age 5-15)</span><span class="overview-value"><?= (int)($stats['deeni_eligible'] ?? 0) ?> <small style="font-size:.7rem;color:var(--text-3);">Farzando</small></span></div></div></a></div>
              <div class="col-12 col-md-4 mb-3"><a href="<?= base_url('Umoor/Mumineendirectory?status=Active&min=5&max=15&madresa_deprived=0') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-book"></i></div><div class="overview-body"><span class="overview-title">Deeni Taalim Taking</span><span class="overview-value"><?= (int)($stats['deeni_taking'] ?? 0) ?> <small style="font-size:.7rem;color:var(--text-3);">Farzando</small></span></div></div></a></div>
              <div class="col-12 col-md-4 mb-3"><a href="<?= base_url('Umoor/Mumineendirectory?status=Active&min=5&max=15&madresa_deprived=1') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#fff7ed;color:#b45309;"><i class="fa fa-book"></i></div><div class="overview-body"><span class="overview-title">Not Taking Deeni Taalim</span><span class="overview-value"><?= (int)($stats['madresa_deprived'] ?? 0) ?> <small style="font-size:.7rem;color:var(--text-3);">Farzando</small></span></div></div></a></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Occupation -->
    <?php
    $groups2 = ['Occupation'=>['label'=>'Occupation','icon'=>'fa-briefcase','bg'=>'#f7f7f5','color'=>'#57534e','count_key'=>'occupation','id'=>'collapseOccupation']];
    foreach ($groups2 as $filterKey => $g):
      if (empty($status_counts[$g['count_key']])) continue; ?>
    <div class="chart-container compact" style="padding:10px;">
      <div class="section-header-standard">
        <h5 class="section-title"><i class="fa <?= $g['icon'] ?>"></i> <?= $g['label'] ?></h5>
        <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#<?= $g['id'] ?>" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="<?= $g['id'] ?>">
        <div class="row px-1">
          <?php foreach ($status_counts[$g['count_key']] as $lbl => $cnt): if ($lbl==='None'||$lbl==='') continue; ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('Umoor/Mumineendirectory?filter='.$filterKey.'&value='.rawurlencode($lbl)) ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card"><div class="overview-icon" style="background:var(--surface-2);color:var(--text-2);"><i class="fa <?= $g['icon'] ?>"></i></div><div class="overview-body"><span class="overview-title"><?= htmlspecialchars($lbl) ?></span><span class="overview-value"><?= $cnt ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- Sector-wise -->
    <div class="row"><div class="col-12"><div class="chart-container sector-block">
      <div class="section-header-standard">
        <h4 class="section-title"><i class="fa fa-map-marker"></i> Sector-wise Members</h4>
        <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseSectorsAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseSectorsAnjuman">
        <?php
        $sectorRows = isset($stats['Sectors']) ? $stats['Sectors'] : [];
        if (!empty($sectorRows)) {
          $sectorRows = array_values(array_filter($sectorRows, fn($r) => trim($r['Sector']??'')!=='' && strtolower($r['Sector']??'')!=='unassigned'));
          usort($sectorRows, fn($a,$b) => intval($b['total']??0) <=> intval($a['total']??0));
        }
        if (!empty($sectorRows)): ?>
        <div class="row">
          <?php foreach ($sectorRows as $idx => $row):
            $cid='collapseSectorIncharge'.$idx;
            $hof=intval($row['hof_count']??$row['HOF']??0);
            $fm=intval($row['fm_count']??$row['FM']??0);
          ?>
          <div class="col-12 col-md-3 mb-3">
            <div class="overview-card" style="display:flex;flex-direction:column;align-items:stretch;height:100%;">
              <a href="<?= base_url('Umoor/Mumineendirectory?filter=sector&value='.rawurlencode($row['Sector']??'')) ?>" style="display:flex;align-items:center;gap:10px;flex:1;text-decoration:none;color:inherit;">
                <div class="overview-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-map-marker"></i></div>
                <div class="overview-body" style="width:100%;">
                  <span class="overview-title"><?= htmlspecialchars($row['Sector']??'Unassigned') ?></span>
                  <?php if (!empty($row['Sector_Incharge_Name'])): ?>
                  <div style="font-size:.74rem;color:var(--text-3);margin:4px 0;"><i class="fa fa-male" style="color:#1d4ed8;margin-right:3px;"></i><?= htmlspecialchars($row['Sector_Incharge_Name']) ?></div>
                  <?php endif; ?>
                  <span class="overview-value" style="font-size:.95rem;">HOF <?= $hof ?> &nbsp;·&nbsp; FM <?= $fm ?></span>
                </div>
              </a>
              <?php if (!empty($row['sub_sectors'])||!empty($row['Sector_Incharge_Name'])): ?>
              <div style="margin-top:8px;border-top:1px solid var(--border-light);padding-top:5px;">
                <button class="btn btn-sm btn-link text-decoration-none w-100 text-start p-0 d-flex justify-content-between align-items-center" style="color:var(--text-3);font-size:.74rem;" type="button" data-toggle="collapse" data-target="#<?= $cid ?>">
                  <span><i class="fa fa-info-circle"></i> View Incharges</span><i class="fa fa-chevron-down" style="font-size:.62rem;"></i>
                </button>
                <div class="collapse" id="<?= $cid ?>">
                  <?php if (!empty($row['sub_sectors'])): ?>
                  <ul style="list-style:none;padding:5px 0 0;margin:0;font-size:.75rem;color:var(--text-2);">
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
          <?php endforeach; ?>
        </div>
        <?php else: ?><div class="text-center" style="color:var(--text-3);">No assigned sectors found.</div><?php endif; ?>
      </div>
    </div></div></div>

    <!-- Marital Stats -->
    <div class="row"><div class="col-12"><div class="chart-container member-types-block compact" style="padding:10px;">
      <div class="section-header-standard">
        <h4 class="section-title"><i class="fa fa-heart"></i> Marital Stats</h4>
        <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseMaritalAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseMaritalAnjuman">
        <?php $ms = isset($marital_status_counts) ? $marital_status_counts : []; ?>
        <div class="row">
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('Umoor/Mumineendirectory?status=Active&marital_status=Single&min=21&max=40') ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card"><div class="overview-icon" style="background:var(--gold-muted);color:var(--gold);"><i class="fa fa-heart"></i></div><div class="overview-body"><span class="overview-title">Single (21-40)</span><span class="overview-value"><?= isset($stats['singles_21_40'])?(int)$stats['singles_21_40']:0 ?></span></div></div>
            </a>
          </div>
          <?php foreach($ms as $label=>$count):
            $ll=strtolower(trim($label));
            if($ll==='unknown'||$ll===''||$ll==='single') continue;
            $iconBg='#f5f5f7';$iconColor='#6b7280';$icon='fa-info-circle';
            if(str_contains($ll,'married')){$icon='fa-user';$iconBg='#fff0f6';$iconColor='#9d174d';}
            elseif(str_contains($ll,'engag')){$icon='fa-star';$iconBg='#fffbeb';$iconColor='#b45309';}
            elseif(str_contains($ll,'divorc')){$icon='fa-user';$iconBg='#fef2f2';$iconColor='#b91c1c';}
            elseif(str_contains($ll,'widow')){$icon='fa-user-secret';$iconBg='#ecfeff';$iconColor='#0891b2';}
          ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('Umoor/Mumineendirectory?status=Active&marital_status='.rawurlencode($label)) ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card"><div class="overview-icon" style="background:<?= $iconBg ?>;color:<?= $iconColor ?>;"><i class="fa <?= $icon ?>"></i></div><div class="overview-body"><span class="overview-title"><?= htmlspecialchars($label) ?></span><span class="overview-value"><?= (int)$count ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div></div></div>

    <!-- ══ RSVP FOR NEXT MIQAAT ══ -->
    <?php
    $miq_rsvp=isset($dashboard_data['miqaat_rsvp'])?$dashboard_data['miqaat_rsvp']:null;
    $upcoming=isset($dashboard_data['upcoming_miqaats'])?$dashboard_data['upcoming_miqaats']:[];
    $initial_index=0;
    $initial_id=isset($miq_rsvp['next_miqaat']['id'])?$miq_rsvp['next_miqaat']['id']:(isset($upcoming[0]['id'])?$upcoming[0]['id']:'');
    foreach($upcoming as $k=>$m){if(isset($m['id'])&&$m['id']==$initial_id){$initial_index=$k;break;}}
    ?>
    <div class="chart-container compact" id="miqaat-rsvp-block" data-initial-index="<?= $initial_index ?>" style="position:relative;">
      <div class="section-header-standard">
        <h4 class="section-title"><i class="fa fa-calendar-check-o"></i> RSVP for Next Miqaat</h4>
        <div class="d-flex align-items-center">
        <a href="#" id="miqaat-view-details" class="btn btn-sm btn-primary text-white mr-2" style="white-space:nowrap;">View details</a>
        <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseRsvpAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
      </div>
      <div class="collapse show" id="collapseRsvpAnjuman">
        <div class="d-flex align-items-center justify-content-between mt-2 mb-1" style="gap:10px;">
          <div class="d-flex align-items-center justify-content-center" style="gap:10px;width:100%;">
  
            <button type="button" class="miqaat-nav-btn prev"
              style="width:34px;height:34px;border:1px solid var(--border);border-radius:8px;background:var(--surface);color:var(--text-2);font-size:1.2rem;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s;"
              onmouseover="this.style.background='var(--gold-muted)'"
              onmouseout="this.style.background='var(--surface)'">
              &lsaquo;
            </button>

            <div id="miqaat-current-title"
              style="font-weight:700;color:var(--gold);font-size:.95rem;text-align:center;min-width:160px;">
            </div>

            <button type="button" class="miqaat-nav-btn next"
              style="width:34px;height:34px;border:1px solid var(--border);border-radius:8px;background:var(--surface);color:var(--text-2);font-size:1.2rem;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s;"
              onmouseover="this.style.background='var(--gold-muted)'"
              onmouseout="this.style.background='var(--surface)'">
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
        <!-- Loading spinner -->
        <div id="miqaatLoading"><div class="miqaat-spinner"></div></div>
      </div>
    </div>

    <!-- Other Modules -->
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
    $rz=isset($dashboard_data['raza_summary'])?$dashboard_data['raza_summary']:['pending'=>0,'approved'=>0,'rejected'=>0];
    $dashboard_madresa_hijri_year=isset($year_daytype_stats['hijri_year'])?(int)$year_daytype_stats['hijri_year']:null;
    ?>

    <div class="row mt-2 mb-4">
      <!-- Madresa -->
      <div class="col-md-12 mb-3">
        <div class="chart-container compact">
          <div class="section-header-standard"><h5 class="section-title"><i class="fa fa-book"></i> Madresa</h5><button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseMadresaAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button></div>
          <div class="collapse show" id="collapseMadresaAnjuman">
            <div class="row justify-content-center text-center"><div class="col-12 col-md-4"><div class="mini-card"><div class="stats-value"><?= $dashboard_madresa_hijri_year?((int)$dashboard_madresa_hijri_year.'H'):'Classes' ?></div><div class="stats-label">Madresa<?= $dashboard_madresa_hijri_year?' (Current Year)':'' ?></div></div></div></div>
          </div>
        </div>
      </div>

      <!-- Raza Summary -->
      <div class="col-md-12">
        <div class="chart-container">
          <div class="section-header-standard"><h5 class="section-title"><i class="fa fa-file-text-o"></i> Raza Summary</h5><button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseRazaAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button></div>
          <div class="collapse show" id="collapseRazaAnjuman">
            <div class="row text-center mb-2">
              <div class="col-12 col-md-4 mb-2"><div class="mini-card"><div class="stats-value"><?= (int)$rz['pending'] ?></div><div class="stats-label">Pending</div></div></div>
              <div class="col-12 col-md-4 mb-2"><div class="mini-card"><div class="stats-value" style="color:var(--green);"><?= (int)$rz['approved'] ?></div><div class="stats-label">Approved</div></div></div>
              <div class="col-12 col-md-4 mb-2"><div class="mini-card"><div class="stats-value" style="color:var(--red);"><?= (int)$rz['rejected'] ?></div><div class="stats-label">Rejected</div></div></div>
            </div>
            <div class="text-center" style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
              <a class="btn btn-sm btn-outline-secondary" href="<?= base_url('Umoor/RazaRequest') ?>">View Raza Requests</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- HOF List modal -->
    <div id="anjHofListModal" class="anj-modal-overlay" aria-hidden="true" role="dialog">
      <div class="anj-modal-card">
        <div class="anj-modal-head">
          <h5 id="anjHofListLabel" class="anj-modal-title">Members</h5>
          <button id="anjHofListClose" class="anj-modal-close" aria-label="Close">&times;</button>
        </div>
        <div id="anjMiqaatPopupMeta" style="font-size:.82rem;color:var(--text-2);margin-bottom:10px;display:none;"></div>
        <div id="anjHofListLoading" class="text-center py-3" style="display:none;">
          <div class="miqaat-spinner" style="margin:0 auto;"></div>
        </div>
        <div class="anj-modal-body" id="anjHofListContainer"></div>
      </div>
    </div>

    <!-- Sidebar overlay (mobile) -->
    <div id="anjSidebarOverlay" class="sidebar-overlay"></div>

  </main>
</div><!-- /anj-root -->
</div><!-- /#anjApp -->

<!-- ══ GLOBAL SCRIPTS ══ -->
<script>
(function(){

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

  /* ── Quick menu search ── */
  var qInput  = document.getElementById('quickMenuSearch');
  var qClear  = document.getElementById('quickMenuClear');
  var sections= Array.prototype.slice.call(document.querySelectorAll('#anjSidebar .menu-section'));
  var lists   = Array.prototype.slice.call(document.querySelectorAll('#anjSidebar .menu-list'));
  if(qInput){
    qInput.addEventListener('input', applySearch);
    if(qClear) qClear.addEventListener('click',function(){ qInput.value=''; applySearch(); });
  }
  function applySearch(){
    var q=(qInput.value||'').toLowerCase().trim();
    if(qClear) qClear.style.display = q ? 'block' : 'none';
    if(!q){ lists.forEach(function(l){ l.querySelectorAll('li').forEach(function(li){ li.style.display=''; }); l.style.display=''; }); sections.forEach(function(s){ s.style.display=''; }); return; }
    lists.forEach(function(list){
      var vc=0; list.querySelectorAll('li').forEach(function(li){ var txt=(li.querySelector('.menu-label')||li).textContent.toLowerCase(); var match=txt.indexOf(q)>-1; li.style.display=match?'':'none'; if(match)vc++; });
      list.style.display=vc>0?'':'none';
    });
  }
  sections.forEach(function(sec){
    var list=sec.nextElementSibling; if(!list) return;
    sec.addEventListener('click',function(){ if((qInput&&(qInput.value||'').trim())) return; list.style.display=list.style.display==='none'?'':'none'; sec.classList.toggle('is-collapsed',list.style.display==='none'); });
  });

  /* HOF list modal close */
  var anjHofClose = document.getElementById('anjHofListClose');
  if(anjHofClose) anjHofClose.addEventListener('click', function(){
    var m=document.getElementById('anjHofListModal');
    if(m){ m.classList.remove('show'); m.setAttribute('aria-hidden','true'); }
  });
  document.getElementById('anjHofListModal') && document.getElementById('anjHofListModal').addEventListener('click',function(e){
    if(e.target===this){ this.classList.remove('show'); this.setAttribute('aria-hidden','true'); }
  });

  /* Close any anj-modal-overlay on Escape */
  document.addEventListener('keydown',function(e){
    if(e.key==='Escape') document.querySelectorAll('.anj-modal-overlay.show').forEach(function(m){ m.classList.remove('show'); m.setAttribute('aria-hidden','true'); });
  });

  /* ── Section header click-to-collapse ── */
  $(document).on('click','.section-header-standard',function(e){
    if($(e.target).closest('button, a').length) return;
    var target=$(this).find('.collapse-toggle-btn').data('target');
    if(target) $(target).collapse('toggle');
  });

  /* ── Collapse toggle button aria-expanded sync ── */
  $(document).on('show.bs.collapse hide.bs.collapse', function(e){
    var btn = $('[data-target="#'+e.target.id+'"]');
    btn.attr('aria-expanded', e.type==='show' ? 'true' : 'false');
  });

  /* ── Member Search ── */
  var baseUrl = '<?= base_url() ?>';
  var searchUrl     = baseUrl + 'admin/searchmembers';
  var viewMemberUrl = baseUrl + 'admin/viewmember/';
  var $mswInput = $('#mswInput');
  var $mswDropdown = $('#mswDropdown');
  var $mswSpinner = $('#mswSpinner');
  var $mswClear = $('#mswClear');
  var mswTimer = null;
  function escH(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
  function initials(name){ if(!name) return '?'; var p=name.trim().split(/\s+/); return p.length===1?p[0][0].toUpperCase():(p[0][0]+p[p.length-1][0]).toUpperCase(); }
  function mswClose(){ $mswDropdown.removeClass('open').html(''); }
  function mswShow(results){
    $mswDropdown.html('');
    if(!results||!results.length){ $mswDropdown.html('<div class="msw-no-results">No members found</div>').addClass('open'); return; }
    results.forEach(function(r){
      var isF=String(r.gender||'').toLowerCase()==='female';
      var item=$('<div class="msw-result-item" role="option" tabindex="0"></div>');
      item.html('<div class="msw-avatar'+(isF?' female':'')+'">'+initials(r.name)+'</div><div style="flex:1;min-width:0;"><div class="msw-res-name">'+escH(r.name)+'</div><div class="msw-res-meta">'+(r.sector?escH(r.sector):'')+(r.hof_type?' &bull; '+escH(r.hof_type):'')+'</div></div><div class="msw-its-badge">'+escH(String(r.its_id))+'</div>');
      item.on('click keydown',function(e){ if(e.type==='keydown'&&e.key!=='Enter') return; window.location.href=viewMemberUrl+r.its_id; });
      $mswDropdown.append(item);
    });
    $mswDropdown.addClass('open');
  }
  $mswInput.on('input',function(){
    var q=$(this).val().trim();
    if(q.length>0) $mswClear.addClass('visible'); else $mswClear.removeClass('visible');
    clearTimeout(mswTimer);
    if(q.length<2){ mswClose(); return; }
    mswTimer=setTimeout(function(){
      $mswSpinner.addClass('active');
      $.getJSON(searchUrl,{q:q},function(data){ $mswSpinner.removeClass('active'); if(data&&data.status==='ok') mswShow(data.results); })
       .fail(function(){ $mswSpinner.removeClass('active'); $mswDropdown.html('<div class="msw-no-results" style="color:var(--red);">Search failed.</div>').addClass('open'); });
    },350);
  });
  $mswClear.on('click',function(){ $mswInput.val('').focus(); $mswClear.removeClass('visible'); mswClose(); });
  $(document).on('click',function(e){ if(!$(e.target).closest('#member-search-block').length) mswClose(); });
  $mswInput.on('keydown',function(e){ if(e.key==='Escape') mswClose(); });

  /* ── Strip trailing decimals from ₹ values ── */
  $(function(){
    $('.overview-value,.stats-value').each(function(){
      var txt=$(this).text();
      var m=txt&&txt.match(/^(₹?)([0-9,]+)(?:\.[0-9]+)?$/);
      if(m)$(this).text((m[1]||'')+m[2]);
    });
    $('[data-toggle="tooltip"]').tooltip({placement:'top',html:false});
  });

})();
</script>

<script>
/* ══════════════════════════════════════════════════════════
   RSVP miqaat switcher block
   ══════════════════════════════════════════════════════════ */
(function(){
  var upcoming = <?= json_encode(array_values($upcoming)) ?> || [];
  var initialRsvp = <?= json_encode($miq_rsvp ?: new stdClass()) ?>;
  var index = parseInt('<?= $initial_index ?>', 10) || 0;

  function _esc(s){ return String(s===null||s===undefined?'':s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

  /* Seed UI with initial PHP data so page never shows blank counts */
  function seedFromPhp(){
    var m = initialRsvp;
    if(!m || !m.combined_summary) return;
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
    if(btn) btn.href = '<?= base_url("common/rsvp_list?from=umoor") ?>' + (mid ? '&miqaat_id='+encodeURIComponent(mid) : '');
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

    /* Disable/enable nav buttons */
    var prevBtn = document.querySelector('.miqaat-nav-btn.prev');
    var nextBtn = document.querySelector('.miqaat-nav-btn.next');
    if(prevBtn) prevBtn.style.opacity = (index <= 0) ? '0.3' : '1';
    if(nextBtn) nextBtn.style.opacity = (index >= upcoming.length-1) ? '0.3' : '1';

    /* Fetch new counts via AJAX */
    showLoading(true);
    var countsUrl = '<?= base_url('common/miqaat_rsvp_user_counts') ?>?miqaat_id=' + encodeURIComponent(miqId);
    fetch(countsUrl, {credentials: 'same-origin'})
      .then(function(resp){ return resp.json(); })
      .then(function(cdata){
        showLoading(false);
        if(!cdata || !cdata.success) return;
        var wa  = document.getElementById('willAttendCount');
        var wna = document.getElementById('willNotAttendCount');
        var ns  = document.getElementById('rsvpNotSubmittedCount');
        if(wa)  wa.textContent  = (cdata.will_attend||0);
        if(wna) wna.textContent = (cdata.will_not_attend||0);
        if(ns)  ns.textContent  = (cdata.rsvp_not_submitted||0);
      })
      .catch(function(err){ showLoading(false); console.warn('fetch counts failed', err); });

    /* Fetch guest breakdown */
    var listUrl = window.location.pathname;
    try {
      var u = new URL(listUrl, window.location.origin);
      u.searchParams.set('format', 'json');
      u.searchParams.set('miqaat_rsvp', '1');
      u.searchParams.set('miqaat_id', miqId);
      listUrl = u.toString();
    } catch(e) {
      listUrl += '?format=json&miqaat_rsvp=1&miqaat_id=' + encodeURIComponent(miqId);
    }
    fetch(listUrl, {credentials: 'same-origin'})
      .then(function(resp){ return resp.json(); })
      .then(function(data){
        if(data && data.miqaat_rsvp){ updateGuestCountsFromPayload(data.miqaat_rsvp); }
      })
      .catch(function(err){ console.warn('fetch guest breakdown failed', err); });
  }

  function updateGuestCountsFromPayload(m){
    var gentsVal    = (m.rsvp_male_count||0) + (m.guest_gents_count||0);
    var ladiesVal   = (m.rsvp_female_count||0) + (m.guest_ladies_count||0);
    var childrenVal = (m.rsvp_children_count||0) + (m.guest_children_count||0);

    var gents = document.getElementById('guestGentsCount');
    var ladies = document.getElementById('guestLadiesCount');
    var children = document.getElementById('guestChildrenCount');

    if(gents) gents.textContent = gentsVal;
    if(ladies) ladies.textContent = ladiesVal;
    if(children) children.textContent = childrenVal;

    var gB = document.getElementById('guestGentsBreakdown');
    var lB = document.getElementById('guestLadiesBreakdown');
    var cB = document.getElementById('guestChildrenBreakdown');

    if(gB) gB.textContent = 'Members: ' + (m.rsvp_male_count||0) + ' | Guests: ' + (m.guest_gents_count||0);
    if(lB) lB.textContent = 'Members: ' + (m.rsvp_female_count||0) + ' | Guests: ' + (m.guest_ladies_count||0);
    if(cB) cB.textContent = 'Members: ' + (m.rsvp_children_count||0) + ' | Guests: ' + (m.guest_children_count||0);

    var waGuest = document.getElementById('willAttendGuest');
    if(waGuest){
      var totGuest = (m.guest_gents_count||0) + (m.guest_ladies_count||0) + (m.guest_children_count||0);
      waGuest.textContent = totGuest ? ('+ ' + totGuest + ' guests') : '';
    }
  }

  /* Listeners */
  var prevBtn = document.querySelector('.miqaat-nav-btn.prev');
  var nextBtn = document.querySelector('.miqaat-nav-btn.next');
  if(prevBtn) prevBtn.addEventListener('click', function(){ if(index > 0) renderFor(index - 1); });
  if(nextBtn) nextBtn.addEventListener('click', function(){ if(index < upcoming.length-1) renderFor(index + 1); });

  /* HOF list rendering for RSVP click cards */
  function renderPopupHofList(title,rows){
    var html='<div class="d-flex justify-content-between align-items-center mb-2"><strong>'+_esc(title)+'</strong>';
    try{rows=(rows||[]).filter(function(r){var s=((r&&(r.Sector||r.sector))||'')+'';var ss=((r&&(r.Sub_Sector||r.sub_sector||r.SubSector))||'')+'';return!((s.trim()===''&&ss.trim()===''));});}catch(e){}
    html+='<span class="text-muted">Count: '+(rows?rows.length:0)+'</span></div>';
    if(!rows||!rows.length)return html+'<div class="text-muted">No records found.</div>';
    try{rows.sort(function(a,b){var sa=((a.Sector||a.sector||'')+'').toLowerCase();var sb=((b.Sector||b.sector||'')+'').toLowerCase();return sa<sb?-1:sa>sb?1:0;});}catch(e){}
    html+='<table class="table table-sm table-striped"><thead><tr><th>ID</th><th>Name</th><th>Sector</th><th>Sub Sector</th></tr></thead><tbody>';
    rows.forEach(function(r){var id=(r&&(r.ITS_ID||r.hof_id||r.ITS))||'';var name=(r&&(r.Full_Name||r.name))||'';var s=(r&&(r.Sector||r.sector))||'';var ss=(r&&(r.Sub_Sector||r.sub_sector||r.SubSector))||'';html+='<tr><td>'+_esc(id)+'</td><td>'+_esc(name)+'</td><td>'+_esc(s)+'</td><td>'+_esc(ss)+'</td></tr>';});
    return html+'</tbody></table>';
  }

  $(document).on('click','.open-miqaat-modal',function(e){
    e.preventDefault();
    var $c=$(this);
    var mid=$c.attr('data-miqaat-id');
    var dtype=$c.attr('data-type');
    if(!mid) return;

    var lblMap={rsvp:"RSVP'd for Miqaat",no:"Will not attend",not_submitted:"RSVP not submitted",gents:"Gents",ladies:"Ladies",children:"Children"};
    $('#anjHofListLabel').text(lblMap[dtype]||"Members");
    $('#anjHofListContainer').html('');$('#anjHofListLoading').show();

    var miObj=(upcoming||[]).find(function(x){return String(x.id||x.miqaat_id||'')===String(mid);})||(upcoming[index]||{});
    $('#anjMiqaatPopupMeta').html('<div style="font-weight:700;">'+_esc(miObj.name||'Miqaat')+'</div>'+(miObj.hijri_label||miObj.date?'<div class="text-muted">'+_esc(miObj.hijri_label||miObj.date)+'</div>':'')+'<div style="margin-top:8px;"><span class="badge badge-success mr-2" id="popupWillAttend">Will attend: 0</span><span class="badge badge-danger mr-2" id="popupWillNotAttend">Will not attend: 0</span><span class="badge badge-secondary" id="popupNotSubmitted">Not submitted: 0</span></div>').show();

    $('#anjHofListModal').addClass('show').attr('aria-hidden','false');

    var url=window.location.pathname;
    try{ var u=new URL(url,window.location.origin); u.searchParams.set('format','json'); u.searchParams.set('miqaat_rsvp','1'); u.searchParams.set('miqaat_id',mid); url=u.toString(); }
    catch(err){ url+='?format=json&miqaat_rsvp=1&miqaat_id='+encodeURIComponent(mid); }

    fetch(url,{credentials:'same-origin'}).then(function(r){ return r.json(); })
      .then(function(data){
        $('#anjHofListLoading').hide();
        if(!data||!data.miqaat_rsvp){$('#anjHofListContainer').html('<div class="text-muted">No data found.</div>');return;}
        var m=data.miqaat_rsvp;
        var rows=[];var titleTxt='';
        if(dtype==='rsvp'){rows=(m.rsvp_member_list&&m.rsvp_member_list.length)?m.rsvp_member_list:(m.rsvp_list||[]);titleTxt="RSVP'd Members";}
        else if(dtype==='no'){rows=(m.not_rsvp_member_list&&m.not_rsvp_member_list.length)?m.not_rsvp_member_list:(m.not_rsvp_list||[]);titleTxt="Will not attend";}
        else if(dtype==='not_submitted'){rows=(m.not_submitted_member_list&&m.not_submitted_member_list.length)?m.not_submitted_member_list:[];titleTxt="Not Submitted";}
        else if(dtype==='gents'){rows=(m.rsvp_male_member_list&&m.rsvp_male_member_list.length)?m.rsvp_male_member_list:[];titleTxt="Gents";}
        else if(dtype==='ladies'){rows=(m.rsvp_female_member_list&&m.rsvp_female_member_list.length)?m.rsvp_female_member_list:[];titleTxt="Ladies";}
        else if(dtype==='children'){rows=(m.rsvp_children_member_list&&m.rsvp_children_member_list.length)?m.rsvp_children_member_list:[];titleTxt="Children";}
        $('#anjHofListContainer').html(renderPopupHofList(titleTxt,rows));

        /* Badge counts */
        var cUrl='<?= base_url('common/miqaat_rsvp_user_counts') ?>?miqaat_id='+encodeURIComponent(mid);
        fetch(cUrl,{credentials:'same-origin'}).then(function(r){return r.json();}).then(function(cdata){
          if(cdata&&cdata.success){
            var pw=document.getElementById('popupWillAttend');var pwn=document.getElementById('popupWillNotAttend');var pns=document.getElementById('popupNotSubmitted');
            if(pw)pw.textContent='Will attend: '+(cdata.will_attend||0);
            if(pwn)pwn.textContent='Will not attend: '+(cdata.will_not_attend||0);
            if(pns)pns.textContent='Not submitted: '+(cdata.rsvp_not_submitted||0);
          }
        }).catch(function(e){console.warn('popup counts failed',e);});
      })
      .catch(function(){ $('#anjHofListLoading').hide(); $('#anjHofListContainer').html('<div class="text-danger">Failed to load list.</div>'); });
  });

  /* Kick off initial render after DOM is ready */
  if(document.readyState==='loading'){
    document.addEventListener('DOMContentLoaded',function(){ seedFromPhp(); renderFor(index); });
  } else {
    seedFromPhp(); renderFor(index);
  }
})();
</script>
