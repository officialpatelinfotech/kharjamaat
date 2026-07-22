<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
  --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
  --shadow:      0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
  --shadow-lg:   0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
}

#anjApp, #anjApp *, #anjApp *::before, #anjApp *::after { box-sizing: border-box; }
#anjApp { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); background: var(--bg); min-height: 100vh; }
#anjApp a { color: inherit; text-decoration: none !important; }

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

#anjApp .sb-search { display: flex; align-items: center; gap: 8px; background: var(--surface-2); border: 1.5px solid var(--border); border-radius: 8px; padding: 7px 10px; margin-bottom: 12px; transition: border-color .15s; }
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
  transition: background .14s, color .14s;
}
#anjApp .menu-item:hover { background: var(--gold-muted); color: var(--gold); }

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
  cursor: pointer; outline: none;
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

/* ── Premium Admin Dashboard Cards Grid ── */
.admin-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 24px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  height: 100%;
  position: relative;
  overflow: hidden;
  transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}
.admin-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow);
  border-color: var(--gold);
}
.admin-card::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 100%);
  transform: scaleX(0);
  transition: transform 0.2s ease;
  transform-origin: left;
}
.admin-card:hover::after {
  transform: scaleX(1);
}
.admin-card-icon {
  width: 58px;
  height: 58px;
  border-radius: 14px;
  background: var(--surface-2);
  color: var(--gold);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 1.6rem;
  margin-bottom: 16px;
  transition: background 0.2s, color 0.2s;
}
.admin-card:hover .admin-card-icon {
  background: var(--gold-muted);
  color: var(--gold);
}
.admin-card-title {
  font-size: 0.95rem;
  font-weight: 700;
  color: var(--text-1);
  margin-bottom: 6px;
  line-height: 1.3;
}
.admin-card-desc {
  font-size: 0.76rem;
  color: var(--text-3);
  line-height: 1.4;
  margin: 0;
}

/* ── Member search ── */
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

/* ── Sidebar overlay (mobile) ── */
#anjApp .sidebar-overlay { position: fixed; inset: 0; background: rgba(26,22,16,.4); z-index: 1040; display: none; }
#anjApp .sidebar-overlay.show { display: block; }
#anjApp .sidebar-close-btn {
  position: absolute; top: 10px; right: 10px; width: 28px; height: 28px;
  border: none; border-radius: 7px; background: var(--gold-muted); color: var(--gold);
  font-size: .95rem; line-height: 1; display: none; align-items: center; justify-content: center; cursor: pointer;
}

#anjApp .btn-outline-secondary { border-color: var(--border); color: var(--text-2); font-size: .78rem; font-weight: 700; }
#anjApp .btn-outline-secondary:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); }
#anjApp .btn-sm { font-size: .76rem; }

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
  #anjApp .sidebar-close-btn { display: inline-flex; }
}
@media (min-width: 992px) { #anjApp .mob-bar { display: none !important; } }
</style>

<div id="anjApp">
<div class="anj-root">

  <!-- ══ SIDEBAR ══ -->
  <aside class="anj-sidebar" id="anjSidebar">
    <button class="sidebar-close-btn" id="sidebarCloseBtn">&times;</button>
    <div class="sidebar-menu">
      <div class="menu-title mb-3">
        <span class="sb-ico" style="display:inline-flex;width:26px;height:26px;border-radius:7px;background:var(--gold-muted);color:var(--gold);align-items:center;justify-content:center;font-size:.78rem;margin-right:8px;">
          <i class="fa fa-tachometer"></i>
        </span>Admin Menu
      </div>
      <div class="sb-search" role="search">
        <i class="fa fa-search"></i>
        <input id="quickMenuSearch" type="text" placeholder="Search menu..." autocomplete="off">
        <button type="button" id="quickMenuClear" class="msw-clear-btn" style="position:static; transform:none; display:none; margin-left:auto;">&times;</button>
      </div>

      <div class="menu-section">Member Management</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('admin/managemembers'); ?>"><span class="menu-icon"><i class="fa fa-users"></i></span><span class="menu-label">Manage Members</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/importmembers'); ?>"><span class="menu-icon"><i class="fa fa-upload"></i></span><span class="menu-label">Import Members</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/umoor_sub_committees'); ?>"><span class="menu-icon"><i class="fa fa-sitemap"></i></span><span class="menu-label">Umoor Sub-Committees</span></a></li>
      </ul>

      <div class="menu-section">Miqaat & FMB</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('admin/razalist'); ?>"><span class="menu-icon"><i class="fa fa-list"></i></span><span class="menu-label">Manage Raza Form</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/managefmbtakhmeen'); ?>"><span class="menu-icon"><i class="fa fa-cutlery"></i></span><span class="menu-label">FMB Thaali Takhmeen</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/manageniyazamounts'); ?>"><span class="menu-icon"><i class="fa fa-coins"></i></span><span class="menu-label">Miqaat Niyaz Takhmeen</span></a></li>
      </ul>

      <div class="menu-section">Finance & Sabeel</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('admin/managesabeeltakhmeen'); ?>"><span class="menu-icon"><i class="fa fa-credit-card"></i></span><span class="menu-label">Manage Sabeel Takhmeen</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/corpusfunds'); ?>"><span class="menu-icon"><i class="fa fa-university"></i></span><span class="menu-label">Corpus Funds</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/laagat'); ?>"><span class="menu-icon"><i class="fa-solid fa-coins"></i></span><span class="menu-label">Laagat Module</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/rent'); ?>"><span class="menu-icon"><i class="fa fa-building"></i></span><span class="menu-label">Rent Module</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/ekramfunds'); ?>"><span class="menu-icon"><i class="fa fa-gift"></i></span><span class="menu-label">Ekram Funds</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/qardanhasana'); ?>"><span class="menu-icon"><i class="fa fa-leaf"></i></span><span class="menu-label">Qardan Hasana</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/wajebaat'); ?>"><span class="menu-icon"><i class="fa fa-book"></i></span><span class="menu-label">Wajebaat</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/expense'); ?>"><span class="menu-icon"><i class="fa fa-calculator"></i></span><span class="menu-label">Expense &amp; Budget Module</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/madresa'); ?>"><span class="menu-icon"><i class="fa fa-graduation-cap"></i></span><span class="menu-label">Madresa Module</span></a></li>
      </ul>

      <div class="menu-section">System Settings</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('admin/preferences'); ?>"><span class="menu-icon"><i class="fa fa-cog"></i></span><span class="menu-label">Preferences</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/notification_settings'); ?>"><span class="menu-icon"><i class="fa fa-bell"></i></span><span class="menu-label">Notifications Settings</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/status_options'); ?>"><span class="menu-icon"><i class="fa fa-sliders"></i></span><span class="menu-label">Manage Status Options</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/login_report'); ?>"><span class="menu-icon"><i class="fa fa-list-alt"></i></span><span class="menu-label">System Login Report</span></a></li>
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
      <span style="font-size:.82rem;font-weight:700;color:var(--text-2);">Admin Dashboard</span>
    </div>

    <!-- Dashboard header -->
    <div class="anj-header">
      <div class="anj-header-inner">
        <div class="anj-title-group">
          <p class="anj-eyebrow">Anjuman-e-Saifee <?php echo htmlspecialchars(jamaat_name(), ENT_QUOTES, 'UTF-8'); ?></p>
          <h1 class="anj-title">
            Admin Dashboard
            <br><span>System Administration Panel &bull; <?php echo date('Y'); ?></span>
          </h1>
        </div>
        <div class="anj-badge">
          <span class="anj-badge-val">ADMIN</span>
          <span class="anj-badge-lbl">Control Center</span>
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
          <a href="<?php echo base_url('admin/managemembers'); ?>" class="btn btn-outline-secondary btn-sm" style="white-space:nowrap;border-radius:9px;font-weight:700;">
            <i class="fa fa-users"></i> Manage Members
          </a>
        </div>
      </div>
    </div>

    <!-- ══ ADMIN MODULES GRID ══ -->
    <div class="row">
      <!-- 4. Manage Sabeel Takhmeen -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/managesabeeltakhmeen'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-credit-card"></i></div>
            <h5 class="admin-card-title">Sabeel Takhmeen</h5>
            <p class="admin-card-desc">Oversee annual sabeel takhmeen rates and configurations.</p>
          </div>
        </a>
      </div>

      <!-- 3b. Miqaat Niyaz Takhmeen -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/manageniyazamounts'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-coins"></i></div>
            <h5 class="admin-card-title">Miqaat Niyaz Takhmeen</h5>
            <p class="admin-card-desc">Set up auto-generation amounts for Miqaat Niyaz.</p>
          </div>
        </a>
      </div>

      <!-- 3. FMB Thaali Takhmeen -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/managefmbtakhmeen'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-cutlery"></i></div>
            <h5 class="admin-card-title">FMB Thaali Takhmeen</h5>
            <p class="admin-card-desc">Oversee annual takhmeen estimates and settings.</p>
          </div>
        </a>
      </div>
        
      <!-- Umoor Sub-Committees -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/umoor_sub_committees'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-sitemap"></i></div>
            <h5 class="admin-card-title">Umoor Sub-Committees</h5>
            <p class="admin-card-desc">Organize sub-committees and assign team leads under Umoor.</p>
          </div>
        </a>
      </div>
      
      <!-- 2. Manage Raza Form -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/razalist'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-list"></i></div>
            <h5 class="admin-card-title">Manage Raza Form</h5>
            <p class="admin-card-desc">Review and approve member Raza application requests.</p>
          </div>
        </a>
      </div>

      <!-- 6. Corpus Funds -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/corpusfunds'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-university"></i></div>
            <h5 class="admin-card-title">Corpus Funds</h5>
            <p class="admin-card-desc">Monitor deposits, yields, and allocations for the corpus fund.</p>
          </div>
        </a>
      </div>

      <!-- 7. Laagat Module -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/laagat'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa-solid fa-coins"></i></div>
            <h5 class="admin-card-title">Laagat Module</h5>
            <p class="admin-card-desc">Configure and manage Laagat forms for non-event Raza types.</p>
          </div>
        </a>
      </div>

      <!-- 7b. Rent Module -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/rent'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-building"></i></div>
            <h5 class="admin-card-title">Rent Module</h5>
            <p class="admin-card-desc">Track event venue rents, deposits, and manage property configurations.</p>
          </div>
        </a>
      </div>

      <!-- 8. Ekram Funds -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/ekramfunds'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-gift"></i></div>
            <h5 class="admin-card-title">Ekram Funds</h5>
            <p class="admin-card-desc">Administer relief contributions, collections, and grants.</p>
          </div>
        </a>
      </div>

      <!-- 9. Qardan Hasana -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/qardanhasana'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-leaf"></i></div>
            <h5 class="admin-card-title">Qardan Hasana</h5>
            <p class="admin-card-desc">Track interest-free loan applications, payouts, and collections.</p>
          </div>
        </a>
      </div>

      <!-- 10. Wajebaat -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/wajebaat'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-book"></i></div>
            <h5 class="admin-card-title">Wajebaat</h5>
            <p class="admin-card-desc">Record, upload, and verify wajebaat details and statements.</p>
          </div>
        </a>
      </div>

      <!-- 11. Expense & Budget Module -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/expense'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-calculator"></i></div>
            <h5 class="admin-card-title">Expense &amp; Budget Module</h5>
            <p class="admin-card-desc">Log and categorize daily operational expenditures and receipts.</p>
          </div>
        </a>
      </div>

      <!-- 12. Madresa Module -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/madresa'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-graduation-cap"></i></div>
            <h5 class="admin-card-title">Madresa Module</h5>
            <p class="admin-card-desc">Oversee student enrollment, schedules, and grading sheets.</p>
          </div>
        </a>
      </div>

      <!-- 13. Preferences -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/preferences'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-cog"></i></div>
            <h5 class="admin-card-title">Preferences</h5>
            <p class="admin-card-desc">Edit global application options, SMS templates, and defaults.</p>
          </div>
        </a>
      </div>

      <!-- 14. Notifications Settings -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/notification_settings'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-bell"></i></div>
            <h5 class="admin-card-title">Notifications Settings</h5>
            <p class="admin-card-desc">Manage system triggers, email reports, and WhatsApp settings.</p>
          </div>
        </a>
      </div>

      <!-- 15. Manage Status Options -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/status_options'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-sliders"></i></div>
            <h5 class="admin-card-title">Manage Status Options</h5>
            <p class="admin-card-desc">Configure fields for health, residential, and deeni statuses.</p>
          </div>
        </a>
      </div>

      <!-- 16. System Login Report -->
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <a href="<?php echo base_url('admin/login_report'); ?>">
          <div class="admin-card">
            <div class="admin-card-icon"><i class="fa fa-list-alt"></i></div>
            <h5 class="admin-card-title">System Login Report</h5>
            <p class="admin-card-desc">Track member and admin login locations, times, and IPs.</p>
          </div>
        </a>
      </div>
    </div>

  </main>
</div><!-- /anj-root -->
<div class="sidebar-overlay" id="anjSidebarOverlay"></div>
</div><!-- /#anjApp -->

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

  /* ── Member Search Widget JavaScript ── */
  var baseUrl = '<?php echo base_url(); ?>';
  var searchUrl = baseUrl + 'admin/searchmembers';
  var viewMemberUrl = baseUrl + 'admin/viewmember/';

  var $input   = $('#mswInput');
  var $dropdown= $('#mswDropdown');
  var $spinner = $('#mswSpinner');
  var $clear   = $('#mswClear');

  var debounceTimer = null;

  function getInitials(name){
    if(!name) return '?';
    var parts = name.trim().split(/\s+/);
    if(parts.length === 1) return parts[0].charAt(0).toUpperCase();
    return (parts[0].charAt(0) + parts[parts.length-1].charAt(0)).toUpperCase();
  }

  function closeDropdown(){
    $dropdown.removeClass('open').html('');
  }

  function showDropdown(results){
    $dropdown.html('');
    if(!results || results.length === 0){
      $dropdown.html('<div class="msw-no-results"><i class="fa fa-search mr-1"></i>No members found</div>');
      $dropdown.addClass('open');
      return;
    }
    results.forEach(function(r){
      var isF = (String(r.gender||'').toLowerCase() === 'female' || String(r.gender||'').toLowerCase() === 'f');
      var avatarCls = isF ? 'msw-avatar female' : 'msw-avatar';
      var initials  = getInitials(r.name);
      var sector = r.sector ? ('<span>'+escHtml(r.sector)+'</span>') : '';
      var hof    = r.hof_type ? (' &bull; '+escHtml(r.hof_type)) : '';
      var item = $('<div class="msw-result-item" role="option" tabindex="0"></div>');
      item.html(
        '<div class="'+avatarCls+'">'+initials+'</div>'+
        '<div style="flex:1;min-width:0;">'+
          '<div class="msw-res-name">'+escHtml(r.name)+'</div>'+
          '<div class="msw-res-meta">'+sector+hof+'</div>'+
        '</div>'+
        '<div class="msw-its-badge">'+escHtml(String(r.its_id))+'</div>'
      );
      item.on('click keydown', function(e){
        if(e.type === 'keydown' && e.key !== 'Enter') return;
        window.location.href = viewMemberUrl + r.its_id;
      });
      $dropdown.append(item);
    });
    $dropdown.addClass('open');
  }

  function escHtml(s){
    return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function doSearch(q){
    $spinner.addClass('active');
    $.getJSON(searchUrl, {q: q}, function(data){
      $spinner.removeClass('active');
      if(data && data.status === 'ok'){
        showDropdown(data.results);
      }
    }).fail(function(){
      $spinner.removeClass('active');
      $dropdown.html('<div class="msw-no-results text-danger">Search failed. Please try again.</div>').addClass('open');
    });
  }

  // Input handler
  $input.on('input', function(){
    var q = $(this).val().trim();
    if(q.length > 0) $clear.addClass('visible'); else $clear.removeClass('visible');
    clearTimeout(debounceTimer);
    if(q.length < 2){
      closeDropdown();
      return;
    }
    debounceTimer = setTimeout(function(){ doSearch(q); }, 350);
  });

  // Clear button
  $clear.on('click', function(){
    $input.val('').focus();
    $clear.removeClass('visible');
    closeDropdown();
  });

  // Close dropdown on outside click
  $(document).on('click', function(e){
    if(!$(e.target).closest('#member-search-block').length){
      closeDropdown();
    }
  });

  // Keyboard: Escape closes
  $input.on('keydown', function(e){
    if(e.key === 'Escape'){ closeDropdown(); }
  });

  // Keyboard navigation in dropdown
  $input.on('keydown', function(e){
    var $items = $dropdown.find('.msw-result-item');
    if(!$items.length) return;
    var $focused = $dropdown.find('.msw-result-item:focus');
    if(e.key === 'ArrowDown'){
      e.preventDefault();
      if(!$focused.length){ $items.first().focus(); }
      else { var next = $items.index($focused)+1; if(next < $items.length) $items.eq(next).focus(); }
    }
    if(e.key === 'ArrowUp'){
      e.preventDefault();
      if($focused.length){ var prev = $items.index($focused)-1; if(prev >= 0) $items.eq(prev).focus(); else $input.focus(); }
    }
  });

})();
</script>