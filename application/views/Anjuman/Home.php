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

/* ── FMB sector cards ── */
#anjApp .fmb-card {
  background: var(--surface-2); border: 1px solid var(--border);
  border-radius: 12px; padding: 12px 14px;
  display: flex; flex-direction: column; gap: 8px; transition: box-shadow .14s;
}
#anjApp .fmb-card:hover { box-shadow: var(--shadow-sm); }
#anjApp .fmb-card .fmb-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
#anjApp .fmb-card .fmb-name { font-weight: 700; color: var(--text-1); font-size: .86rem; }
#anjApp .fmb-card .fmb-amounts { display: flex; gap: 12px; flex-wrap: wrap; font-size: .76rem; color: var(--text-3); }
#anjApp .fmb-card .fmb-amounts .val { font-weight: 700; color: var(--text-1); }
#anjApp .progress-slim { height: 5px; border-radius: 5px; background: var(--border); overflow: hidden; }
#anjApp .progress-slim .bar { height: 100%; background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 100%); }

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

/* ── FIX 2: Takhmeen — keep summary totals visible when details are open ── */
#anjApp .takhmeen-summary-totals {
  /* Always visible row showing Total/Paid/Due */
}
#anjApp .takhmeen-details-toggle {
  cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 6px;
  padding: 6px 12px; border-radius: 20px;
  background: var(--gold-muted); color: var(--gold);
  border: 1px solid rgba(184,134,11,.25);
  font-size: .78rem; font-weight: 700;
  margin: 6px auto 0; width: fit-content;
  transition: background .15s;
}
#anjApp .takhmeen-details-toggle:hover { background: #edd98a; }
#anjApp .takhmeen-details-toggle i { transition: transform .2s; }
#anjApp .takhmeen-details-toggle.open i { transform: rotate(180deg); }

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
#anjApp .anj-modal-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 10px; margin-top: 10px; }
#anjApp .anj-pill { background: var(--surface-2); border: 1px solid var(--border); border-radius: 9px; padding: 10px 12px; text-align: center; }
#anjApp .anj-pill .label { display: block; font-size: .75rem; color: var(--text-3); margin-bottom: 3px; }
#anjApp .anj-pill .value { display: block; font-weight: 800; color: var(--text-1); font-size: .95rem; }

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
  #anjApp .fmb-card .fmb-amounts { flex-direction: column; gap: 3px; }
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
        <li><a class="menu-item" href="<?php echo base_url('anjuman/EventRazaRequest?event_type=1') ?>"><span class="menu-icon"><i class="fa fa-handshake-o"></i></span><span class="menu-label">Miqaat Raza Request</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/EventRazaRequest?event_type=2') ?>"><span class="menu-icon"><i class="fa fa-handshake-o"></i></span><span class="menu-label">Kaaraj Raza Request</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/UmoorRazaRequest') ?>"><span class="menu-icon"><i class="fa fa-list"></i></span><span class="menu-label">12 Umoor Raza Request</span></a></li>
      </ul>

      <div class="menu-section">Activity</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('anjuman/mumineendirectory') ?>"><span class="menu-icon"><i class="fa fa-users"></i></span><span class="menu-label">Mumineen Directory</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/asharaohbat') ?>"><span class="menu-icon"><i class="fa fa-calendar"></i></span><span class="menu-label">Ashara Ohbat</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/ashara_attendance') ?>"><span class="menu-icon"><i class="fa fa-user-check"></i></span><span class="menu-label">Ashara Attendance</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/fmbcalendar?from=anjuman') ?>"><span class="menu-icon"><i class="fa fa-calendar"></i></span><span class="menu-label">FMB Calendar</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/thaali_signups_breakdown?from=anjuman') ?>"><span class="menu-icon"><i class="fa fa-bar-chart"></i></span><span class="menu-label">FMB Thaali Signups</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/fmbthaalimenu?from=anjuman') ?>"><span class="menu-icon"><i class="fa fa-cutlery"></i></span><span class="menu-label">Add FMB Menu</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/managemiqaat?from=common/managemiqaat') ?>"><span class="menu-icon"><i class="fa fa-plus-circle"></i></span><span class="menu-label">Create Miqaat</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/rsvp_list?from=anjuman') ?>"><span class="menu-icon"><i class="fa fa-check-square-o"></i></span><span class="menu-label">RSVP Report</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/miqaatattendance?from=anjuman') ?>"><span class="menu-icon"><i class="fa fa-users"></i></span><span class="menu-label">Miqaat Attendance Report</span></a></li>
      </ul>

      <div class="menu-section">Finance</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('anjuman/fmbmodule') ?>"><span class="menu-icon"><i class="fa fa-money"></i></span><span class="menu-label">FMB Module</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/sabeeltakhmeendashboard') ?>"><span class="menu-icon"><i class="fa fa-credit-card"></i></span><span class="menu-label">Sabeel Module</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/qardanhasana') ?>"><span class="menu-icon"><i class="fa fa-leaf"></i></span><span class="menu-label">Qardan Hasana</span></a></li>
        <li><a class="menu-item" href="<?= base_url('anjuman/corpusfunds_receive') ?>"><span class="menu-icon"><i class="fa fa-university"></i></span><span class="menu-label">Corpus Funds</span></a></li>
        <li><a class="menu-item" href="<?= base_url('anjuman/ekramfunds_receive') ?>"><span class="menu-icon"><i class="fa fa-gift"></i></span><span class="menu-label">Ekram Fund Module</span></a></li>
        <li><a class="menu-item" href="<?= base_url('anjuman/payments_report') ?>"><span class="menu-icon"><i class="fa fa-file-text-o"></i></span><span class="menu-label">Payments Report</span></a></li>
        <li><a class="menu-item" href="<?= base_url('anjuman/financials') ?>"><span class="menu-icon"><i class="fa fa-file-text-o"></i></span><span class="menu-label">Individual Financial Details</span></a></li>
        <li><a class="menu-item" href="<?= base_url('anjuman/expense') ?>"><span class="menu-icon"><i class="fa fa-calculator"></i></span><span class="menu-label">Expense Module</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/laagat_rent') ?>"><span class="menu-icon"><i class="fa fa-home"></i></span><span class="menu-label">Laagat / Rent</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/wajebaat') ?>"><span class="menu-icon"><i class="fa fa-coins"></i></span><span class="menu-label">Wajebaat</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('madresa') ?>"><span class="menu-icon"><i class="fa fa-graduation-cap"></i></span><span class="menu-label">Madresa Module</span></a></li>
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
      <span style="font-size:.82rem;font-weight:700;color:var(--text-2);">Jamaat Dashboard</span>
    </div>

    <!-- Dashboard header -->
    <div class="anj-header">
      <div class="anj-header-inner">
        <div class="anj-title-group">
          <p class="anj-eyebrow">Anjuman-e-Saifee</p>
          <h1 class="anj-title">
            Jamaat Dashboard
            <br><span><?php $hijri_year = isset($year_daytype_stats['hijri_year']) ? $year_daytype_stats['hijri_year'] : '1446'; echo $hijri_year . 'H — ' . date('Y'); ?></span>
          </h1>
        </div>
        <div class="anj-badge">
          <span class="anj-badge-val"><?= (int)(($stats['active_inactive']['active'] ?? 0) + ($stats['active_inactive']['inactive'] ?? 0)) ?></span>
          <span class="anj-badge-lbl">Total Members</span>
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
          <a href="<?php echo base_url('anjuman/mumineendirectory') ?>" class="btn btn-outline-secondary btn-sm" style="white-space:nowrap;border-radius:9px;font-weight:700;">
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
            <a href="<?= base_url('anjuman/mumineendirectory?status=active') ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card" style="border-color:#86efac;">
                <div class="overview-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-check-circle"></i></div>
                <div class="overview-body"><span class="overview-title">Active Members</span><span class="overview-value"><?= (int)($stats['active_inactive']['active'] ?? 0) ?></span></div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-6 mb-3">
            <a href="<?= base_url('anjuman/mumineendirectory?status=inactive') ?>" style="text-decoration:none;color:inherit;display:block;">
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
          <div class="col-6 col-md-3 mb-3"><a href="<?= base_url('anjuman/mumineendirectory?its_sabeel_match=its_sabeel_both_khar') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#f5e9c0;color:#b8860b;"><i class="fa fa-home"></i></div><div class="overview-body"><span class="overview-title">ITS &amp; Sabeel both in <?= htmlspecialchars(jamaat_place()) ?></span><span class="overview-value"><?= (int)($stats['active_inactive']['its_sabeel_both_khar'] ?? 0) ?></span></div></div></a></div>
          <div class="col-6 col-md-3 mb-3"><a href="<?= base_url('anjuman/mumineendirectory?its_sabeel_match=sabeel_khar_its_out') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#ecfeff;color:#0891b2;"><i class="fa fa-external-link"></i></div><div class="overview-body"><span class="overview-title">Sabeel in Khar, ITS not in <?= htmlspecialchars(jamaat_place()) ?></span><span class="overview-value"><?= (int)($stats['active_inactive']['sabeel_khar_its_out'] ?? 0) ?></span></div></div></a></div>
          <div class="col-6 col-md-3 mb-3"><a href="<?= base_url('anjuman/mumineendirectory?its_sabeel_match=its_khar_sabeel_out') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#fff7ed;color:#b45309;"><i class="fa fa-sign-out"></i></div><div class="overview-body"><span class="overview-title">ITS in Khar, Sabeel not in <?= htmlspecialchars(jamaat_place()) ?></span><span class="overview-value"><?= (int)($stats['active_inactive']['its_khar_sabeel_out'] ?? 0) ?></span></div></div></a></div>
          <div class="col-6 col-md-3 mb-3"><a href="<?= base_url('anjuman/mumineendirectory?its_sabeel_match=both_not_khar') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#fef2f2;color:#b91c1c;"><i class="fa fa-ban"></i></div><div class="overview-body"><span class="overview-title">Sabeel &amp; ITS both not in <?= htmlspecialchars(jamaat_place()) ?></span><span class="overview-value"><?= (int)($stats['active_inactive']['both_not_khar'] ?? 0) ?></span></div></div></a></div>
        </div>
      </div>

      <!-- General Overall Stats -->
      <div class="section-header-standard ml-3 mr-3">
        <h5 class="section-title"><i class="fa fa-info-circle"></i> General Overall Stats</h5>
        <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseGeneralOverallStats" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseGeneralOverallStats">
        <div class="row px-3">
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('anjuman/mumineendirectory?status=active') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon"><i class="fa fa-users"></i></div><div class="overview-body"><span class="overview-title">Total Members</span><span class="overview-value"><?= (int)($stats['active_inactive']['active'] ?? 0) ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('anjuman/mumineendirectory?status=active&filter=hof_fm_type&value=HOF') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon"><i class="fa fa-user"></i></div><div class="overview-body"><span class="overview-title">HOF</span><span class="overview-value"><?= $stats['HOF'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('anjuman/mumineendirectory?status=active&filter=hof_fm_type&value=FM') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon"><i class="fa fa-user-plus"></i></div><div class="overview-body"><span class="overview-title">FM</span><span class="overview-value"><?= $stats['FM'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('anjuman/mumineendirectory?status=active&filter=gender&value=male') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="fa fa-male"></i></div><div class="overview-body"><span class="overview-title">Males</span><span class="overview-value"><?= $stats['Mardo'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('anjuman/mumineendirectory?status=active&filter=gender&value=female') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#fdf2f8;color:#9d174d;"><i class="fa fa-female"></i></div><div class="overview-body"><span class="overview-title">Females</span><span class="overview-value"><?= $stats['Bairo'] ?></span></div></div></a></div>

          <?php $ageGs=[['0','4','Age 0-4',$stats['Age_0_4']],['5','15','Age 5-15',$stats['Age_5_15']],['16','25','Age 16-25',$stats['Age_16_25']],['26','65','Age 26-65',$stats['Age_26_65']],['66','','Above 65',$stats['Buzurgo']]];
          foreach($ageGs as[$mn,$mx,$lbl,$val]):$url=$mx?base_url("anjuman/mumineendirectory?status=active&filter=age_range&min=$mn&max=$mx"):base_url("anjuman/mumineendirectory?status=active&filter=age_range&min=$mn");?>
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
      foreach ($groups1 as $filterKey => $g):
        if (empty($status_counts[$g['count_key']])) continue; ?>
      <div class="section-header-standard ml-3 mr-3">
        <h5 class="section-title"><i class="fa <?= $g['icon'] ?>"></i> <?= $g['label'] ?></h5>
        <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#<?= $g['id'] ?>" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="<?= $g['id'] ?>">
        <div class="row px-3">
          <?php foreach ($status_counts[$g['count_key']] as $lbl => $cnt): if ($lbl==='None'||$lbl==='') continue; ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('anjuman/mumineendirectory?filter='.$filterKey.'&value='.rawurlencode($lbl)) ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card"><div class="overview-icon" style="background:<?= $g['bg'] ?>;color:<?= $g['color'] ?>;"><i class="fa <?= $g['icon'] ?>"></i></div><div class="overview-body"><span class="overview-title"><?= htmlspecialchars($lbl) ?></span><span class="overview-value"><?= $cnt ?></span></div></div>
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
              <div class="col-12 col-md-4 mb-3"><a href="<?= base_url('anjuman/mumineendirectory?status=Active&min=5&max=15') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="fa fa-child"></i></div><div class="overview-body"><span class="overview-title">Total Eligible (Age 5-15)</span><span class="overview-value"><?= (int)($stats['deeni_eligible'] ?? 0) ?> <small style="font-size:.7rem;color:var(--text-3);">Farzando</small></span></div></div></a></div>
              <div class="col-12 col-md-4 mb-3"><a href="<?= base_url('anjuman/mumineendirectory?status=Active&min=5&max=15&madresa_deprived=0') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-book"></i></div><div class="overview-body"><span class="overview-title">Deeni Taalim Taking</span><span class="overview-value"><?= (int)($stats['deeni_taking'] ?? 0) ?> <small style="font-size:.7rem;color:var(--text-3);">Farzando</small></span></div></div></a></div>
              <div class="col-12 col-md-4 mb-3"><a href="<?= base_url('anjuman/mumineendirectory?status=Active&min=5&max=15&madresa_deprived=1') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="overview-card"><div class="overview-icon" style="background:#fff7ed;color:#b45309;"><i class="fa fa-book"></i></div><div class="overview-body"><span class="overview-title">Not Taking Deeni Taalim</span><span class="overview-value"><?= (int)($stats['madresa_deprived'] ?? 0) ?> <small style="font-size:.7rem;color:var(--text-3);">Farzando</small></span></div></div></a></div>
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
            <a href="<?= base_url('anjuman/mumineendirectory?filter='.$filterKey.'&value='.rawurlencode($lbl)) ?>" style="text-decoration:none;color:inherit;display:block;">
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
              <a href="<?= base_url('anjuman/mumineendirectory?filter=sector&value='.rawurlencode($row['Sector']??'')) ?>" style="display:flex;align-items:center;gap:10px;flex:1;text-decoration:none;color:inherit;">
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
            <a href="<?= base_url('anjuman/mumineendirectory?status=Active&marital_status=Single&min=21&max=40') ?>" style="text-decoration:none;color:inherit;display:block;">
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
            <a href="<?= base_url('anjuman/mumineendirectory?status=Active&marital_status='.rawurlencode($label)) ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card"><div class="overview-icon" style="background:<?= $iconBg ?>;color:<?= $iconColor ?>;"><i class="fa <?= $icon ?>"></i></div><div class="overview-body"><span class="overview-title"><?= htmlspecialchars($label) ?></span><span class="overview-value"><?= (int)$count ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div></div></div>

    <!-- ══ THAALI SIGNUP ══ -->
    <?php
    $sw = isset($dashboard_data['this_week_sector_signup_avg']) ? $dashboard_data['this_week_sector_signup_avg'] : null;
    $sw_start = isset($sw['start']) ? $sw['start'] : date('Y-m-d', strtotime('monday this week'));
    $sw_end   = isset($sw['end'])   ? $sw['end']   : date('Y-m-d', strtotime('sunday this week'));
    $sw_sectors = (isset($sw['sectors'])&&is_array($sw['sectors'])) ? $sw['sectors'] : [];
    $allSectorNames=[];
    if (isset($stats['Sectors'])&&is_array($stats['Sectors'])) {
      foreach($stats['Sectors'] as $sr){$nm=trim($sr['Sector']??'');if($nm!==''&&strtolower($nm)!=='unassigned')$allSectorNames[]=$nm;}
      $allSectorNames=array_values(array_unique($allSectorNames));
    }
    $byWeekly=[];foreach($sw_sectors as $r){$nm=trim($r['sector']??'');if($nm!=='')$byWeekly[strtolower($nm)]=$r;}
    $displayWeekly=[];
    if(!empty($allSectorNames)){foreach($allSectorNames as $secName){$key=strtolower($secName);$displayWeekly[]=isset($byWeekly[$key])?['sector'=>$secName,'avg'=>(float)($byWeekly[$key]['avg']??0),'total'=>(int)($byWeekly[$key]['total']??0)]:['sector'=>$secName,'avg'=>0,'total'=>0];}}
    else{foreach($sw_sectors as $row)$displayWeekly[]=['sector'=>$row['sector']??'—','avg'=>(float)($row['avg']??0),'total'=>(int)($row['total']??0)];}
    ?>
    <div class="chart-container compact weekly-summary">
      <div class="section-header-standard">
        <h4 class="section-title"><i class="fa fa-cutlery"></i> Thaali Signup for Current Month</h4>
        <div class="d-flex align-items-center">
          <a id="thaali-details-btn" href="#" class="btn btn-sm btn-primary text-white mr-2" style="white-space:nowrap;">View details</a>
          <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseThaaliAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
      </div>
      <div class="collapse show" id="collapseThaaliAnjuman">
        <?php
        $this->load->model('HijriCalendar');
        if (isset($selected_hijri_parts)&&is_array($selected_hijri_parts)&&!empty($selected_hijri_parts)){$hijri_today=$selected_hijri_parts;}
        else{$hijri_today=$this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));}
        $current_hijri_year=$hijri_today['hijri_year']??null;
        $current_hijri_month_id=(int)($hijri_today['hijri_month']??0);
        $current_hijri_month_name=$hijri_today['hijri_month_name']??'';
        $years=$this->HijriCalendar->get_distinct_hijri_years();
        $monthList=[];foreach($years as $y){$ms2=$this->HijriCalendar->get_hijri_months_for_year($y);if(!is_array($ms2))continue;foreach($ms2 as $m)$monthList[]=['year'=>$y,'id'=>(int)$m['id'],'name'=>$m['name']??''];}
        $currentIndex=null;foreach($monthList as $i=>$mn){if($mn['year']==$current_hijri_year&&(int)$mn['id']===(int)$current_hijri_month_id){$currentIndex=$i;break;}}
        $prev=null;$next=null;
        if($currentIndex!==null){if($currentIndex>0)$prev=$monthList[$currentIndex-1];if($currentIndex<count($monthList)-1)$next=$monthList[$currentIndex+1];}
        $basePath=current_url();
        $hijri_days_for_button=$this->HijriCalendar->get_hijri_days_for_month_year($current_hijri_month_id,$current_hijri_year);
        if(!empty($hijri_days_for_button)&&is_array($hijri_days_for_button)){$button_start=$hijri_days_for_button[0]['greg_date']??date('Y-m-01');$button_end=$hijri_days_for_button[count($hijri_days_for_button)-1]['greg_date']??date('Y-m-t');}
        else{$button_start=date('Y-m-01');$button_end=date('Y-m-t');}
        ?>
        <script>
        (function(){
          var btn=document.getElementById('thaali-details-btn');
          if(btn) btn.setAttribute('href','<?= base_url('common/thaali_signups_breakdown?from=anjuman&start_date='.rawurlencode($button_start).'&end_date='.rawurlencode($button_end)) ?>');
        })();
        </script>

        <div class="d-flex justify-content-center align-items-center hijri-switcher" style="margin-top:-6px;margin-bottom:14px;">
          <a href="#" data-hijri-year="<?= $prev?htmlspecialchars($prev['year']):'' ?>" data-hijri-month="<?= $prev?htmlspecialchars($prev['id']):'' ?>" class="hijri-nav-btn <?= $prev?'':'disabled' ?>">
            <div class="chev-box"><i class="fa fa-chevron-left"></i></div>
          </a>
          <div class="hijri-title text-center" id="hijri-current-title" style="margin:0 18px;">
            <?= htmlspecialchars($current_hijri_month_name?$current_hijri_month_name.'  '.(isset($current_hijri_year)?$current_hijri_year:''):'—') ?>
          </div>
          <a href="#" data-hijri-year="<?= $next?htmlspecialchars($next['year']):'' ?>" data-hijri-month="<?= $next?htmlspecialchars($next['id']):'' ?>" class="hijri-nav-btn <?= $next?'':'disabled' ?>">
            <div class="chev-box"><i class="fa fa-chevron-right"></i></div>
          </a>
        </div>

        <div id="thaali-month-block" class="row">
          <div class="col-12">
            <?php
            $noThaali=isset($dashboard_data['no_thaali_families'])?$dashboard_data['no_thaali_families']:[];
            $hofTotal=isset($stats['HOF'])?(int)$stats['HOF']:0;
            if(!isset($month_start)||!isset($month_end)){
              $hijri_days=$this->HijriCalendar->get_hijri_days_for_month_year($current_hijri_month_id,$current_hijri_year);
              if(!empty($hijri_days)&&is_array($hijri_days)){$month_start=$hijri_days[0]['greg_date']??date('Y-m-01');$month_end=$hijri_days[count($hijri_days)-1]['greg_date']??date('Y-m-t');}
              else{$month_start=date('Y-m-01');$month_end=date('Y-m-t');}
            }
            if(isset($dashboard_data['this_month_families_signed_up'])||isset($dashboard_data['no_thaali_families_month'])){
              $familiesSignedUpMonth=(int)($dashboard_data['this_month_families_signed_up']??$dashboard_data['this_month_signed_up']??($hofTotal-count($noThaali)));
              $noThaaliMonthList=$dashboard_data['no_thaali_families_month']??$dashboard_data['no_thaali_families']??[];
            } else {
              $this->load->model('CommonM');
              $mstats=$this->CommonM->get_monthly_thaali_stats($current_hijri_month_id,$current_hijri_year);
              $familiesSignedUpMonth=(int)($mstats['families_signed_up']??($hofTotal-count($noThaali)));
              $noThaaliMonthList=$mstats['no_thaali_list']??$dashboard_data['no_thaali_families']??[];
            }
            $noThaaliMonthCount=is_array($noThaaliMonthList)?count($noThaaliMonthList):(int)$noThaaliMonthList;
            ?>
            <div class="row text-center mb-2">
              <div class="col-12 col-md-6 mb-2">
                <a href="#" class="open-hof-modal" data-modal-type="signed" style="text-decoration:none;color:inherit;display:block;"
                   data-hijri-year="<?= htmlspecialchars($current_hijri_year) ?>"
                   data-hijri-month="<?= htmlspecialchars($current_hijri_month_id) ?>"
                   data-start-date="<?= htmlspecialchars($month_start) ?>"
                   data-end-date="<?= htmlspecialchars($month_end) ?>">
                  <div class="mini-card"><div class="stats-value"><?= (int)$familiesSignedUpMonth ?></div><div class="stats-label">Sign up this month</div></div>
                </a>
              </div>
              <div class="col-12 col-md-6 mb-2">
                <a href="#" class="open-hof-modal" data-modal-type="no" style="text-decoration:none;color:inherit;display:block;"
                   data-hijri-year="<?= htmlspecialchars($current_hijri_year) ?>"
                   data-hijri-month="<?= htmlspecialchars($current_hijri_month_id) ?>"
                   data-start-date="<?= htmlspecialchars($month_start) ?>"
                   data-end-date="<?= htmlspecialchars($month_end) ?>">
                  <div class="mini-card"><div class="stats-value"><?= (int)$noThaaliMonthCount ?></div><div class="stats-label">No sign up this month</div></div>
                </a>
              </div>
            </div>
          </div>
        </div>

        <script>
        (function(){
          /* ── Thaali HOF modal helper ── */
          function _normalizePhone(raw){var DEFAULT_CC='91';if(!raw)return '';var s=String(raw).trim().replace(/[\s\-\.\(\)]/g,'');if(s.charAt(0)==='+'){return '+'+s.slice(1).replace(/\D/g,'');}if(s.indexOf('00')===0){return '+'+s.slice(2).replace(/\D/g,'');}var digits=s.replace(/\D/g,'');if(!digits)return '';if(digits.length===10)return '+'+DEFAULT_CC+digits;if(digits.length===11&&digits.charAt(0)==='0')return '+'+DEFAULT_CC+digits.slice(1);if(digits.length>=11&&digits.length<=15)return '+'+digits;return digits;}
          function renderHofList(title,rows){
            var html='<div class="d-flex justify-content-between align-items-center mb-2"><strong>'+title+'</strong>';
            try{rows=(rows||[]).filter(function(r){var s=((r&&(r.Sector||r.sector))||'')+'';var ss=((r&&(r.Sub_Sector||r.sub_sector||r.SubSector))||'')+'';return !((s.trim()===''&&ss.trim()===''));});}catch(e){}
            html+='<span class="text-muted">Count: '+(rows?rows.length:0)+'</span></div>';
            if(!rows||!rows.length)return html+'<div class="text-muted">No records found.</div>';
            try{rows.sort(function(a,b){var sa=((a.Sector||a.sector||'')+'').toLowerCase();var sb=((b.Sector||b.sector||'')+'').toLowerCase();if(sa<sb)return -1;if(sa>sb)return 1;var na=((a.Full_Name||a.name||'')+'').toLowerCase();var nb=((b.Full_Name||b.name||'')+'').toLowerCase();return na<nb?-1:na>nb?1:0;});}catch(e){}
            html+='<table class="table table-sm table-striped"><thead><tr><th>HOF ID</th><th>Name</th><th>Sector</th><th>Sub Sector</th><th>Mobile</th></tr></thead><tbody>';
            rows.forEach(function(r){var id=(r&&(r.ITS_ID||r.hof_id||r.ITS))||'';var name=(r&&(r.Full_Name||r.name))||'';var sector=(r&&(r.Sector||r.sector))||'';var sub=(r&&(r.Sub_Sector||r.sub_sector||r.SubSector))||'';var mobile=(r&&(r.RFM_Mobile||r.rfm_mobile||r.Mobile||r.mobile))||'';var tel=mobile?_normalizePhone(mobile):'';html+='<tr><td>'+id+'</td><td>'+name+'</td><td>'+sector+'</td><td>'+sub+'</td><td>'+(mobile?(tel?('<a href="tel:'+tel+'" style="color:blue;">'+mobile+'</a>'):mobile):'')+'</td></tr>';});
            return html+'</tbody></table>';
          }

          /* Open HOF list modal for thaali signed/not-signed */
          $(document).on('click','.open-hof-modal',function(e){
            e.preventDefault();
            var $a=$(this);var type=$a.data('modal-type');var hijriYear=$a.data('hijri-year');var hijriMonth=$a.data('hijri-month');
            $('#anjHofListLabel').text(type==='signed'?'HOFs Signed Up This Month':'HOFs With No Signup This Month');
            $('#anjHofListContainer').html('');$('#anjHofListLoading').show();
            $('#anjHofListModal').addClass('show').attr('aria-hidden','false');
            var url=window.location.pathname;
            try{var u=new URL(url,window.location.origin);u.searchParams.set('format','json');u.searchParams.set('hijri_year',hijriYear);u.searchParams.set('hijri_month',hijriMonth);u.searchParams.set('ajax','1');url=u.toString();}catch(e){url+='?format=json&hijri_year='+encodeURIComponent(hijriYear)+'&hijri_month='+encodeURIComponent(hijriMonth)+'&ajax=1';}
            fetch(url,{credentials:'same-origin'}).then(function(resp){return resp.json();}).then(function(data){
              var rows=[];
              if(data&&data.monthly_stats)rows=(type==='signed')?(data.monthly_stats.signed_hof_list||[]):(data.monthly_stats.no_thaali_list||[]);
              $('#anjHofListLoading').hide();$('#anjHofListContainer').html(renderHofList('',rows));
            }).catch(function(){$('#anjHofListLoading').hide();$('#anjHofListContainer').html('<div class="text-danger">Failed to load data.</div>');});
          });

          /* Hijri month navigation */
          function buildUrlWithParams(base,y,m){try{var u=new URL(base,window.location.origin);u.searchParams.set('hijri_year',y);u.searchParams.set('hijri_month',m);u.searchParams.set('ajax','1');return u.toString();}catch(e){return base+'?hijri_year='+encodeURIComponent(y)+'&hijri_month='+encodeURIComponent(m)+'&ajax=1';}}
          function ajaxLoadMonth(year,month,pushState){
            if(!year||!month)return;
            var url=buildUrlWithParams(window.location.pathname,year,month);
            document.querySelectorAll('.hijri-switcher .hijri-nav-btn .chev-box').forEach(function(b){b.dataset.orig=b.innerHTML;b.innerHTML='<i class="fa fa-spinner fa-spin"></i>';});
            fetch(url,{credentials:'same-origin'}).then(function(resp){return resp.text();}).then(function(htmlText){
              var parser=new DOMParser();var doc=parser.parseFromString(htmlText,'text/html');
              var newBlock=doc.querySelector('#thaali-month-block');var newTitle=doc.querySelector('#hijri-current-title');
              if(newBlock){var cur=document.querySelector('#thaali-month-block');cur.parentNode.replaceChild(newBlock,cur);if(newTitle){var curTitle=document.getElementById('hijri-current-title');if(curTitle)curTitle.innerHTML=newTitle.innerHTML;}var newBtn=doc.getElementById('thaali-details-btn');var curBtn=document.getElementById('thaali-details-btn');if(newBtn&&curBtn)curBtn.setAttribute('href',newBtn.getAttribute('href'));}
              var navBtns=doc.querySelectorAll('.hijri-switcher .hijri-nav-btn');var curNavBtns=document.querySelectorAll('.hijri-switcher .hijri-nav-btn');
              if(navBtns[0]&&curNavBtns[0]){curNavBtns[0].dataset.hijriYear=navBtns[0].dataset.hijriYear||'';curNavBtns[0].dataset.hijriMonth=navBtns[0].dataset.hijriMonth||'';curNavBtns[0].classList.toggle('disabled',navBtns[0].classList.contains('disabled'));}
              if(navBtns[1]&&curNavBtns[1]){curNavBtns[1].dataset.hijriYear=navBtns[1].dataset.hijriYear||'';curNavBtns[1].dataset.hijriMonth=navBtns[1].dataset.hijriMonth||'';curNavBtns[1].classList.toggle('disabled',navBtns[1].classList.contains('disabled'));}
              if(pushState)history.pushState({hijri_year:year,hijri_month:month},'',window.location.pathname+'?hijri_year='+encodeURIComponent(year)+'&hijri_month='+encodeURIComponent(month));
            }).catch(function(err){console.error('Failed to load month data',err);}).finally(function(){
              document.querySelectorAll('.hijri-switcher .hijri-nav-btn .chev-box').forEach(function(b){if(b.dataset.orig){b.innerHTML=b.dataset.orig;delete b.dataset.orig;}});
            });
          }
          document.querySelectorAll('.hijri-switcher .hijri-nav-btn').forEach(function(btn){
            btn.addEventListener('click',function(e){e.preventDefault();if(btn.classList.contains('disabled'))return;ajaxLoadMonth(btn.getAttribute('data-hijri-year'),btn.getAttribute('data-hijri-month'),true);});
          });
          window.addEventListener('popstate',function(ev){if(ev.state&&ev.state.hijri_year&&ev.state.hijri_month)ajaxLoadMonth(ev.state.hijri_year,ev.state.hijri_month,false);});
        })();
        </script>
      </div>
    </div>

    <!-- ══ FIX 3: RSVP for Next Miqaat ══
         Fixes: title not rendering on first paint, missing miqaat-id on action cards,
         broken loading spinner reference, and nav direction logic -->
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
        <!-- Loading spinner (FIX: was broken inline style) -->
        <div id="miqaatLoading"><div class="miqaat-spinner"></div></div>
      </div>
    </div>

    <script>
    /* ══════════════════════════════════════════════════════════
       FIX 3 — RSVP miqaat block
       Changes:
         1. renderFor() called inside DOMContentLoaded so DOM is ready
         2. Initial data seeded from PHP for first paint (no blank state)
         3. Loading spinner uses .active class toggle instead of style.display
         4. miqaat-id set on ALL clickable cards including gents/ladies/children
         5. Robust null-guard for upcoming array
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
        if(btn) btn.href = '<?= base_url("common/rsvp_list?from=anjuman") ?>' + (mid ? '&miqaat_id='+encodeURIComponent(mid) : '');
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
        if(prevBtn) prevBtn.style.opacity = (index <= 0) ? '.35' : '1';
        if(nextBtn) nextBtn.style.opacity = (index >= upcoming.length-1) ? '.35' : '1';

        if(!miqId) return;
        showLoading(true);

        /* Fetch fresh RSVP data */
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

            /* Fetch per-user counts separately */
            var cUrl = '<?= base_url('common/miqaat_rsvp_user_counts') ?>?miqaat_id='+encodeURIComponent(miqId);
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

      /* Nav button clicks */
      document.addEventListener('click', function(e){
        var t = e.target.closest && e.target.closest('.miqaat-nav-btn');
        if(!t) return;
        e.preventDefault();
        if(t.classList.contains('prev')){
          if(index > 0){ renderFor(index-1); }
          else {
            /* Try fetching earlier miqaat from server */
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

      /* Open RSVP detail modal */
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
          html+='<table class="table table-sm table-striped"><thead><tr><th>ID</th><th>Name</th><th>Sector</th><th>Sub Sector</th></tr></thead><tbody>';
          rows.forEach(function(r){var id=(r&&(r.ITS_ID||r.hof_id||r.ITS))||'';var name=(r&&(r.Full_Name||r.name))||'';var s=(r&&(r.Sector||r.sector))||'';var ss=(r&&(r.Sub_Sector||r.sub_sector||r.SubSector))||'';html+='<tr><td>'+_escH(id)+'</td><td>'+_escH(name)+'</td><td>'+_escH(s)+'</td><td>'+_escH(ss)+'</td></tr>';});
          return html+'</tbody></table>';
        }

        var lblMap={rsvp:"RSVP'd for Miqaat",no:"Will not attend",not_submitted:"RSVP not submitted",gents:"Gents",ladies:"Ladies",children:"Children"};
        $('#anjHofListLabel').text(lblMap[dtype]||"Members");
        $('#anjHofListContainer').html('');$('#anjHofListLoading').show();

        var miObj=(upcoming||[]).find(function(x){return String(x.id||x.miqaat_id||'')===String(mid);})||(upcoming[index]||{});
        $('#anjMiqaatPopupMeta').html('<div style="font-weight:700;">'+_escH(miObj.name||'Miqaat')+'</div>'+(miObj.hijri_label||miObj.date?'<div class="text-muted">'+_escH(miObj.hijri_label||miObj.date)+'</div>':'')+'<div style="margin-top:8px;"><span class="badge badge-success mr-2" id="popupWillAttend">Will attend: 0</span><span class="badge badge-danger mr-2" id="popupWillNotAttend">Will not attend: 0</span><span class="badge badge-secondary" id="popupNotSubmitted">Not submitted: 0</span></div>').show();

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
            $('#anjHofListContainer').html(renderList(titleTxt,rows));

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

    <!-- FMB Calendar Overview -->
    <?php if (!empty($year_daytype_stats)): ?>
    <div class="chart-container calendar-overview">
      <div class="section-header-standard">
        <h4 class="section-title"><i class="fa fa-calendar"></i> FMB Calendar Overview (Hijri <?= htmlspecialchars($year_daytype_stats['hijri_year']) ?>)</h4>
        <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseCalendarAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseCalendarAnjuman">
        <div class="row">
          <a href="<?= base_url('common/fmbcalendar?from=anjuman') ?>" class="col-12 col-md-4"><div class="stats-card"><div class="text-center"><h5>Miqaat Days</h5><div class="stats-value"><?= (int)$year_daytype_stats['miqaat_days'] ?></div></div></div></a>
          <a href="<?= base_url('common/fmbcalendar?from=anjuman') ?>" class="col-12 col-md-4"><div class="stats-card"><div class="text-center"><h5>Thaali Days</h5><div class="stats-value"><?= (int)$year_daytype_stats['thaali_days'] ?></div></div></div></a>
          <a href="<?= base_url('common/fmbcalendar?from=anjuman') ?>" class="col-12 col-md-4"><div class="stats-card"><div class="text-center"><h5>Holidays</h5><div class="stats-value"><?= (int)$year_daytype_stats['holiday_days'] ?></div></div></div></a>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- ══ FIX 2: FMB Takhmeen — summary totals stay visible when details are expanded ══ -->
    <?php
    $fmbSectorRows=isset($dashboard_data['fmb_takhmeen_sector'])?$dashboard_data['fmb_takhmeen_sector']:[];
    $fmbYear=isset($dashboard_data['fmb_takhmeen_year'])?$dashboard_data['fmb_takhmeen_year']:($year_daytype_stats['hijri_year']??null);
    $allSectors=array_values(array_unique(array_map(fn($s)=>$s['Sector'], array_filter($stats['Sectors']??[], fn($s)=>trim($s['Sector']??'')!==''))));
    $bySector=[];foreach($fmbSectorRows as $r){$nm=trim($r['sector']??'');if($nm!==''&&strtolower($nm)!=='unassigned')$bySector[$nm]=$r;}
    $displayFmb=[];foreach($allSectors as $sn){if(isset($bySector[$sn])){$r=$bySector[$sn];$r['no_takhmeen']=false;$displayFmb[]=$r;}else $displayFmb[]=['sector'=>$sn,'total_takhmeen'=>0,'total_paid'=>0,'outstanding'=>0,'members'=>0,'no_takhmeen'=>true];}
    $fmbTotal=0;$fmbPaid=0;$fmbDue=0;
    foreach($displayFmb as $rSum){$t=(float)($rSum['total_takhmeen']??0);$p=(float)($rSum['total_paid']??0);$fmbTotal+=$t;$fmbPaid+=$p;$fmbDue+=max(0,$t-$p);}
    $fmbTotal=(int)round($fmbTotal);$fmbPaid=(int)round($fmbPaid);$fmbDue=(int)round($fmbDue);
    ?>
    <div class="row mb-2">
      <div class="col-md-12">
        <div class="chart-container">
          <div class="section-header-standard">
            <h5 class="section-title"><i class="fa fa-money"></i> FMB Takhmeen</h5>
            <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseFmbTakhmeen" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
          </div>
          <?php if ($fmbYear) echo '<div class="container-year">Hijri '.htmlspecialchars($fmbYear).'</div>'; ?>
          <div class="collapse show" id="collapseFmbTakhmeen">

            <!-- FIX 2: Summary totals are ALWAYS shown (not hidden when details open) -->
            <div class="row mb-2">
              <div class="col-12 col-md-4 mb-2">
                <div class="overview-card">
                  <div class="overview-body"><span class="overview-title">Total</span><span class="overview-value">₹<?= format_inr($fmbTotal) ?></span></div>
                </div>
              </div>
              <div class="col-12 col-md-4 mb-2">
                <div class="overview-card">
                  <div class="overview-body"><span class="overview-title">Paid</span><span class="overview-value" style="color:var(--green);">₹<?= format_inr($fmbPaid) ?></span></div>
                </div>
              </div>
              <div class="col-12 col-md-4 mb-2">
                <div class="overview-card">
                  <div class="overview-body"><span class="overview-title">Due</span><span class="overview-value" style="color:var(--red);">₹<?= format_inr($fmbDue) ?></span></div>
                </div>
              </div>
            </div>

            <!-- Expandable sector details toggle -->
            <p class="tk-hint" id="fmbDetailsToggle" style="cursor:pointer;"
              onclick="(function(){
                var d=document.getElementById('anj-fmb-details'),
                    i=document.querySelector('#fmbDetailsToggle i'),
                    open=d.style.display!=='none';

                d.style.display=open?'none':'block';
                i.style.transform=open?'rotate(0deg)':'rotate(180deg)';
                document.querySelector('#fmbDetailsToggle span').textContent=
                  open?'Click to view sector-wise':'Hide sector details';
              })()">

                <i class="fa fa-chevron-down" style="margin-right:3px;font-size:.68rem;transition:.2s;"></i>
                <span>Click to view sector-wise</span>

            </p>

            <div id="anj-fmb-details" style="display:none;margin-top:12px;">
              <?php foreach($displayFmb as $row):
                $sector=trim($row['sector']??'Unassigned');$total=(int)round((float)($row['total_takhmeen']??0));$paid=(int)round((float)($row['total_paid']??0));$due=(int)round((float)($row['outstanding']??max(0,$total-$paid)));
                $pct=$total>0?min(100,round(($paid/$total)*100)):0;
              ?>
              <div class="fmb-card mb-2 sector-click" data-type="FMB" data-sector="<?= htmlspecialchars($sector) ?>" data-members="<?= intval($row['members']??0) ?>" data-total="₹<?= format_inr($total) ?>" data-paid="₹<?= format_inr($paid) ?>" data-due="₹<?= format_inr($due) ?>">
                <div class="fmb-head">
                  <div class="fmb-name"><i class="fa fa-map-marker" style="color:var(--gold);margin-right:5px;"></i><?= htmlspecialchars($sector) ?> <small class="text-muted">(<?= intval($row['members']??0) ?>)</small><?php if(!empty($row['no_takhmeen'])): ?> <small class="text-muted">— No takhmeen done</small><?php endif; ?></div>
                  <div class="fmb-amounts"><span>Total <span class="val">₹<?= format_inr($total) ?></span></span><span>Paid <span class="val" style="color:var(--green);">₹<?= format_inr($paid) ?></span></span><span>Due <span class="val" style="color:var(--red);">₹<?= format_inr($due) ?></span></span></div>
                </div>
                <div class="progress-slim"><div class="bar" style="width:<?= $pct ?>%"></div></div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Sabeel Takhmeen -->
      <?php
      $sabSectorRows=isset($dashboard_data['sabeel_takhmeen_sector'])?$dashboard_data['sabeel_takhmeen_sector']:[];
      $sabYear=isset($dashboard_data['sabeel_takhmeen_year'])?$dashboard_data['sabeel_takhmeen_year']:($year_daytype_stats['hijri_year']??null);
      $bySector2=[];foreach($sabSectorRows as $r){$nm=trim($r['sector']??'');if($nm!==''&&strtolower($nm)!=='unassigned')$bySector2[$nm]=$r;}
      $displaySab=[];foreach($allSectors as $sn){if(isset($bySector2[$sn])){$r=$bySector2[$sn];$r['no_takhmeen']=false;$displaySab[]=$r;}else $displaySab[]=['sector'=>$sn,'total_takhmeen'=>0,'total_paid'=>0,'outstanding'=>0,'members'=>0,'no_takhmeen'=>true];}
      $sabTotal=0;$sabPaid=0;$sabDue=0;
      foreach($displaySab as $rSum){$t=(float)($rSum['total_takhmeen']??0);$p=(float)($rSum['total_paid']??0);$sabTotal+=$t;$sabPaid+=$p;$sabDue+=max(0,$t-$p);}
      $sabTotal=(int)round($sabTotal);$sabPaid=(int)round($sabPaid);$sabDue=(int)round($sabDue);
      ?>
      <div class="col-md-12">
        <div class="chart-container">
          <div class="section-header-standard">
            <h5 class="section-title"><i class="fa fa-credit-card"></i> Sabeel Takhmeen</h5>
            <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseSabTakhmeen" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
          </div>
          <?php if ($sabYear) echo '<div class="container-year">Hijri '.htmlspecialchars($sabYear).'</div>'; ?>
          <div class="collapse show" id="collapseSabTakhmeen">

            <!-- FIX 2: Summary totals always visible -->
            <div class="row mb-2">
              <div class="col-12 col-md-4 mb-2">
                <div class="overview-card">
                  <div class="overview-body"><span class="overview-title">Total</span><span class="overview-value">₹<?= format_inr($sabTotal) ?></span></div>
                </div>
              </div>
              <div class="col-12 col-md-4 mb-2">
                <div class="overview-card">
                  <div class="overview-body"><span class="overview-title">Paid</span><span class="overview-value" style="color:var(--green);">₹<?= format_inr($sabPaid) ?></span></div>
                </div>
              </div>
              <div class="col-12 col-md-4 mb-2">
                <div class="overview-card">
                  <div class="overview-body"><span class="overview-title">Due</span><span class="overview-value" style="color:var(--red);">₹<?= format_inr($sabDue) ?></span></div>
                </div>
              </div>
            </div>

            <p class="tk-hint" id="sabDetailsToggle" style="cursor:pointer;"
              onclick="(function(){
                var d=document.getElementById('anj-sab-details'),
                    i=document.querySelector('#sabDetailsToggle i'),
                    open=d.style.display!=='none';

                d.style.display=open?'none':'block';
                i.style.transform=open?'rotate(0deg)':'rotate(180deg)';
                document.querySelector('#sabDetailsToggle span').textContent=
                  open?'Click to view sector-wise':'Hide sector details';
              })()">

                <i class="fa fa-chevron-down" style="margin-right:3px;font-size:.68rem;transition:.2s;"></i>
                <span>Click to view sector-wise</span>

            </p>

            <div id="anj-sab-details" style="display:none;margin-top:12px;">
              <?php foreach($displaySab as $row):
                $sector=trim($row['sector']??'Unassigned');$total=(int)round((float)($row['total_takhmeen']??0));$paid=(int)round((float)($row['total_paid']??0));$due=(int)round((float)($row['outstanding']??max(0,$total-$paid)));
                $pct=$total>0?min(100,round(($paid/$total)*100)):0;
              ?>
              <div class="fmb-card mb-2 sector-click" data-type="Sabeel" data-sector="<?= htmlspecialchars($sector) ?>" data-members="<?= intval($row['members']??0) ?>" data-total="₹<?= format_inr($total) ?>" data-paid="₹<?= format_inr($paid) ?>" data-due="₹<?= format_inr($due) ?>">
                <div class="fmb-head">
                  <div class="fmb-name"><i class="fa fa-map-marker" style="color:var(--green);margin-right:5px;"></i><?= htmlspecialchars($sector) ?> <small class="text-muted">(<?= intval($row['members']??0) ?>)</small><?php if(!empty($row['no_takhmeen'])): ?> <small class="text-muted">— No takhmeen done</small><?php endif; ?></div>
                  <div class="fmb-amounts"><span>Total <span class="val">₹<?= format_inr($total) ?></span></span><span>Paid <span class="val" style="color:var(--green);">₹<?= format_inr($paid) ?></span></span><span>Due <span class="val" style="color:var(--red);">₹<?= format_inr($due) ?></span></span></div>
                </div>
                <div class="progress-slim"><div class="bar" style="width:<?= $pct ?>%;background:linear-gradient(90deg,var(--green) 0%,#4ade80 100%);"></div></div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /row takhmeen -->

    <!-- Finance Modules -->
    <?php
    $fundsCount=0;$sumAmount=0;$sumReceived=0;
    if(isset($corpus_funds)&&is_array($corpus_funds)){$fundsCount=count($corpus_funds);foreach($corpus_funds as $f){$sumAmount+=(float)($f['assigned_total']??0);$sumReceived+=(float)($f['paid_total']??0);}}
    $sumAmount=(int)round($sumAmount);$sumReceived=(int)round($sumReceived);$sumPending=max(0,(int)round($sumAmount-$sumReceived));
    $efundsCount=0;$esumAssigned=0;$esumPaid=0;
    if(isset($ekram_funds)&&is_array($ekram_funds)){$efundsCount=count($ekram_funds);foreach($ekram_funds as $ef){$esumAssigned+=(float)($ef['assigned_total']??0);$esumPaid+=(float)($ef['paid_total']??0);}}
    $esumPending=max(0,(int)round($esumAssigned-$esumPaid));
    $wa=isset($dashboard_data['wajebaat_summary'])&&is_array($dashboard_data['wajebaat_summary'])?$dashboard_data['wajebaat_summary']:['count'=>0,'total'=>0,'received'=>0,'due'=>0];
    $qh_tot=isset($qh_all_schemes_totals)&&is_array($qh_all_schemes_totals)?$qh_all_schemes_totals:['mohammedi'=>0,'taher'=>0,'husain'=>0,'total'=>0];
    $rz=isset($dashboard_data['raza_summary'])?$dashboard_data['raza_summary']:['pending'=>0,'approved'=>0,'rejected'=>0];
    $dashboard_expenses=isset($dashboard_expenses)&&is_array($dashboard_expenses)?$dashboard_expenses:[];
    $dashboard_expense_total=isset($dashboard_expense_total)?(float)$dashboard_expense_total:0.0;
    $dashboard_expense_hijri_year=isset($dashboard_expense_hijri_year)?(int)$dashboard_expense_hijri_year:null;
    $dashboard_madresa_hijri_year=isset($year_daytype_stats['hijri_year'])?(int)$year_daytype_stats['hijri_year']:null;
    ?>

    <div class="row mt-2 mb-4">
      <!-- Corpus Funds -->
      <div class="col-12 mb-3">
        <div class="chart-container compact">
          <div class="section-header-standard"><h5 class="section-title"><i class="fa fa-university"></i> Corpus Funds</h5><button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseCorpusAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button></div>
          <div class="collapse show" id="collapseCorpusAnjuman">
            <div class="row text-center g-2">
              <div class="col-12 col-md-4"><a href="<?= base_url('anjuman/corpusfunds_receive') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value">₹<?= format_inr($sumAmount) ?></div><div class="stats-label">Total Takhmeen</div></div></a></div>
              <div class="col-12 col-md-4"><a href="<?= base_url('anjuman/corpusfunds_receive') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value" style="color:var(--green);">₹<?= format_inr($sumReceived) ?></div><div class="stats-label">Total Received</div></div></a></div>
              <div class="col-12 col-md-4"><a href="<?= base_url('anjuman/corpusfunds_receive') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value" style="color:var(--red);">₹<?= format_inr($sumPending) ?></div><div class="stats-label">Total Pending</div></div></a></div>
              <div class="col-12"><small style="color:var(--text-3);">Funds: <?= (int)$fundsCount ?></small></div>
              <div class="col-12 mt-1"><a href="<?= base_url('anjuman/corpusfunds_receive') ?>" class="btn btn-outline-secondary btn-sm">View All</a></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Ekram Funds -->
      <div class="col-md-12 mb-3">
        <div class="chart-container compact">
          <div class="section-header-standard"><h5 class="section-title"><i class="fa fa-gift"></i> Ekram Funds</h5><button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseEkramAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button></div>
          <div class="collapse show" id="collapseEkramAnjuman">
            <div class="row text-center g-2">
              <div class="col-12 col-md-4"><a href="<?= base_url('anjuman/ekramfunds_receive') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value">₹<?= format_inr($esumAssigned) ?></div><div class="stats-label">Total Assigned</div></div></a></div>
              <div class="col-12 col-md-4"><a href="<?= base_url('anjuman/ekramfunds_receive') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value" style="color:var(--green);">₹<?= format_inr($esumPaid) ?></div><div class="stats-label">Total Received</div></div></a></div>
              <div class="col-12 col-md-4"><a href="<?= base_url('anjuman/ekramfunds_receive') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value" style="color:var(--red);">₹<?= format_inr($esumPending) ?></div><div class="stats-label">Total Pending</div></div></a></div>
              <div class="col-12"><small style="color:var(--text-3);">Funds: <?= (int)$efundsCount ?></small></div>
              <div class="col-12 mt-1"><a href="<?= base_url('anjuman/ekramfunds_receive') ?>" class="btn btn-outline-secondary btn-sm">View All</a></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Wajebaat -->
      <div class="col-md-12 mb-3">
        <div class="chart-container compact">
          <div class="section-header-standard"><h5 class="section-title"><i class="fa fa-university"></i> Wajebaat</h5><div class="d-flex align-items-center"><a href="<?= base_url('anjuman/wajebaat') ?>" class="btn btn-outline-secondary btn-sm mr-2">View All</a><button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseWajebaatAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button></div></div>
          <div class="collapse show" id="collapseWajebaatAnjuman">
            <div class="row text-center g-2">
              <div class="col-12 col-md-4"><a href="<?= base_url('anjuman/wajebaat') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value" style="color:var(--blue);">₹<?= format_inr((int)($wa['total']??0)) ?></div><div class="stats-label">Total</div></div></a></div>
              <div class="col-12 col-md-4"><a href="<?= base_url('anjuman/wajebaat') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value" style="color:var(--green);">₹<?= format_inr((int)($wa['received']??0)) ?></div><div class="stats-label">Received</div></div></a></div>
              <div class="col-12 col-md-4"><a href="<?= base_url('anjuman/wajebaat') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value" style="color:var(--red);">₹<?= format_inr((int)($wa['due']??0)) ?></div><div class="stats-label">Pending</div></div></a></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Qardan Hasana -->
      <div class="col-md-12 mb-3">
        <div class="chart-container compact">
          <div class="section-header-standard"><h5 class="section-title"><i class="fa fa-leaf"></i> Qardan Hasana Schemes</h5><div class="d-flex align-items-center"><a href="<?= base_url('anjuman/qardanhasana') ?>" class="btn btn-outline-secondary btn-sm mr-2">View</a><button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseQHAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button></div></div>
          <div class="collapse show" id="collapseQHAnjuman">
            <div class="row text-center g-2">
              <div class="col-12 col-md-3"><a href="<?= base_url('anjuman/qardanhasana/mohammedi') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value" style="color:var(--blue);">₹<?= format_inr((float)($qh_tot['mohammedi']??0)) ?></div><div class="stats-label">Mohammedi</div></div></a></div>
              <div class="col-12 col-md-3"><a href="<?= base_url('anjuman/qardanhasana/taher') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value" style="color:var(--blue);">₹<?= format_inr((float)($qh_tot['taher']??0)) ?></div><div class="stats-label">Taher</div></div></a></div>
              <div class="col-12 col-md-3"><a href="<?= base_url('anjuman/qardanhasana/husain') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value" style="color:var(--blue);">₹<?= format_inr((float)($qh_tot['husain']??0)) ?></div><div class="stats-label">Husain</div></div></a></div>
              <div class="col-12 col-md-3"><a href="<?= base_url('anjuman/qardanhasana') ?>" style="text-decoration:none;color:inherit;display:block;"><div class="mini-card" style="margin-bottom:8px;"><div class="stats-value" style="color:var(--green);">₹<?= format_inr((float)($qh_tot['total']??0)) ?></div><div class="stats-label">Total</div></div></a></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Madresa -->
      <div class="col-md-12 mb-3">
        <div class="chart-container compact">
          <div class="section-header-standard"><h5 class="section-title"><i class="fa fa-book"></i> Madresa</h5><div class="d-flex align-items-center"><a href="<?= base_url('madresa') ?>" class="btn btn-outline-secondary btn-sm mr-2">View</a><button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseMadresaAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button></div></div>
          <div class="collapse show" id="collapseMadresaAnjuman">
            <div class="row justify-content-center text-center"><div class="col-12 col-md-4"><div class="mini-card"><div class="stats-value"><?= $dashboard_madresa_hijri_year?((int)$dashboard_madresa_hijri_year.'H'):'Classes' ?></div><div class="stats-label">Madresa<?= $dashboard_madresa_hijri_year?' (Current Year)':'' ?></div></div></div></div>
          </div>
        </div>
      </div>

      <!-- Expenses -->
      <div class="col-md-12 mb-3">
        <div class="chart-container compact">
          <div class="section-header-standard"><h5 class="section-title"><i class="fa fa-calculator"></i> Expenses</h5><div class="d-flex align-items-center"><a href="<?= base_url('anjuman/expense') ?>" class="btn btn-outline-secondary btn-sm mr-2">View</a><button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseExpensesAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button></div></div>
          <div class="collapse show" id="collapseExpensesAnjuman">
            <div class="row justify-content-center text-center"><div class="col-12 col-md-4"><div class="mini-card"><div class="stats-value">₹<?= number_format($dashboard_expense_total,0) ?></div><div class="stats-label">Expense<?= $dashboard_expense_hijri_year?' for '.$dashboard_expense_hijri_year:'' ?></div></div></div></div>
          </div>
        </div>
      </div>

      <!-- Laagat & Rent -->
      <div class="col-md-12 mb-3">
        <div class="chart-container compact">
          <div class="section-header-standard"><h5 class="section-title"><i class="fa fa-home"></i> Laagat &amp; Rent</h5><div class="d-flex align-items-center"><a href="<?= base_url('anjuman/laagat_rent') ?>" class="btn btn-outline-secondary btn-sm mr-2">View</a><button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseRentAnjuman" aria-expanded="true"><i class="fa fa-chevron-down"></i></button></div></div>
          <div class="collapse show" id="collapseRentAnjuman">
            <div class="row justify-content-center text-center"><div class="col-12 col-md-4"><div class="mini-card"><div class="stats-value">₹<?= number_format($dashboard_laagat_rent_total??0,0) ?></div><div class="stats-label">Invoiced<?= isset($dashboard_laagat_rent_hijri_year)?' for '.$dashboard_laagat_rent_hijri_year.'H':'' ?></div></div></div></div>
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
              <a class="btn btn-sm btn-outline-secondary" href="<?= base_url('anjuman/EventRazaRequest?event_type=1') ?>">Miqaat Raza</a>
              <a class="btn btn-sm btn-outline-secondary" href="<?= base_url('anjuman/EventRazaRequest?event_type=2') ?>">Kaaraj Raza</a>
              <a class="btn btn-sm btn-outline-secondary" href="<?= base_url('anjuman/UmoorRazaRequest') ?>">Umoor Raza</a>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /finance row -->

    <!-- ══ FIX 1: ALL MODALS INSIDE #anjApp so CSS vars & scoping work ══ -->

    <!-- HOF List modal (used by both thaali & RSVP) -->
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

    <!-- Sector modal (FMB/Sabeel click detail) -->
    <div id="anjSectorModal" class="anj-modal-overlay" aria-hidden="true" role="dialog">
      <div class="anj-modal-card" style="max-width:400px;">
        <div class="anj-modal-head">
          <h5 id="anjSectorModalTitle" class="anj-modal-title">Sector</h5>
          <button id="anjSectorModalClose" class="anj-modal-close">&times;</button>
        </div>
        <div id="anjSectorModalMeta" style="font-size:.78rem;color:var(--text-3);margin-bottom:8px;">&nbsp;</div>
        <div class="anj-modal-grid">
          <div class="anj-pill"><span class="label">Total</span><span id="anjSectorModalTotal" class="value">₹0</span></div>
          <div class="anj-pill"><span class="label">Paid</span><span id="anjSectorModalPaid" class="value" style="color:var(--green);">₹0</span></div>
          <div class="anj-pill"><span class="label">Due</span><span id="anjSectorModalDue" class="value" style="color:var(--red);">₹0</span></div>
        </div>
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

  /* ── FIX 1: Modal open/close (scoped, using .anj-modal-overlay) ── */
  /* HOF list modal close */
  var anjHofClose = document.getElementById('anjHofListClose');
  if(anjHofClose) anjHofClose.addEventListener('click', function(){
    var m=document.getElementById('anjHofListModal');
    if(m){ m.classList.remove('show'); m.setAttribute('aria-hidden','true'); }
  });
  document.getElementById('anjHofListModal') && document.getElementById('anjHofListModal').addEventListener('click',function(e){
    if(e.target===this){ this.classList.remove('show'); this.setAttribute('aria-hidden','true'); }
  });
  /* Sector modal close */
  var anjSecClose = document.getElementById('anjSectorModalClose');
  if(anjSecClose) anjSecClose.addEventListener('click', function(){
    var m=document.getElementById('anjSectorModal');
    if(m){ m.classList.remove('show'); m.setAttribute('aria-hidden','true'); }
  });
  document.getElementById('anjSectorModal') && document.getElementById('anjSectorModal').addEventListener('click',function(e){
    if(e.target===this){ this.classList.remove('show'); this.setAttribute('aria-hidden','true'); }
  });
  /* Close any anj-modal-overlay on Escape */
  document.addEventListener('keydown',function(e){
    if(e.key==='Escape') document.querySelectorAll('.anj-modal-overlay.show').forEach(function(m){ m.classList.remove('show'); m.setAttribute('aria-hidden','true'); });
  });

  /* ── FIX 2: Sector click → open scoped sector modal ── */
  document.addEventListener('click', function(e){
    var card = e.target.closest && e.target.closest('.sector-click');
    if(!card) return;
    var modal  = document.getElementById('anjSectorModal');
    var title  = document.getElementById('anjSectorModalTitle');
    var meta   = document.getElementById('anjSectorModalMeta');
    var tot    = document.getElementById('anjSectorModalTotal');
    var paid   = document.getElementById('anjSectorModalPaid');
    var due    = document.getElementById('anjSectorModalDue');
    if(title) title.textContent = (card.dataset.type||'') + ' — ' + (card.dataset.sector||'Sector');
    if(meta)  meta.textContent  = 'Members: ' + (card.dataset.members||'0');
    if(tot)   tot.textContent   = card.dataset.total||'₹0';
    if(paid)  paid.textContent  = card.dataset.paid||'₹0';
    if(due)   due.textContent   = card.dataset.due||'₹0';
    if(modal){ modal.classList.add('show'); modal.setAttribute('aria-hidden','false'); }
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
    $('.overview-value,.stats-value,.fmb-amounts .val').each(function(){
      var txt=$(this).text();
      var m=txt&&txt.match(/^(₹?)([0-9,]+)(?:\.[0-9]+)?$/);
      if(m)$(this).text((m[1]||'')+m[2]);
    });
    $('[data-toggle="tooltip"]').tooltip({placement:'top',html:false});
  });

})();
</script>