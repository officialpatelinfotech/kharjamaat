<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
/* ═══════════════════════════════════════════════════
   GOLD THEME — Scoped to #anjApp (Exact Dashboard Theme)
   ═══════════════════════════════════════════════════ */
:root {
  --gold:         #b8860b;
  --gold-light:   #e6c84a;
  --gold-muted:   #f5e9c0;
  --primary:      #b8860b;
  --primary-hover:#8a6408;
  --primary-light:#f5e9c0;
  --bg:           #faf7f0;
  --surface:      #ffffff;
  --surface-2:    #f7f4ec;
  --border:       #e8e0cc;
  --border-light: #f0ece0;
  --text-1:       #1a1610;
  --text-2:       #5a5244;
  --text-3:       #9c8f7a;
  --shadow-sm:    0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
  --shadow:       0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
  --shadow-lg:    0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
  --radius-sm:    6px;
  --radius:       12px;
  --radius-lg:    16px;
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

/* ── Sidebar nav (Matches Home.php) ── */
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

#anjApp .menu-list { list-style: none; margin: 0; padding: 0; }
#anjApp .menu-list li + li { margin-top: 2px; }

#anjApp .menu-item {
  display: flex; align-items: center; gap: 9px;
  padding: 7px 9px; border-radius: 7px;
  color: var(--text-2); font-size: .82rem; font-weight: 500;
  transition: background .14s, color .14s;
}
#anjApp .menu-item:hover { background: var(--gold-muted); color: var(--gold); }
#anjApp .menu-item.active { background: var(--gold-muted); color: var(--gold); font-weight: 700; }

#anjApp .menu-icon {
  width: 27px; height: 27px; border-radius: 7px;
  display: inline-flex; align-items: center; justify-content: center;
  background: var(--surface-2); color: var(--text-3); font-size: .76rem; flex-shrink: 0;
  transition: background .14s, color .14s;
}
#anjApp .menu-item:hover .menu-icon, #anjApp .menu-item.active .menu-icon { background: var(--gold); color: #fff; }
#anjApp .menu-label { flex: 1; white-space: normal; word-break: break-word; }

/* ── Dashboard Header Banner ── */
#anjApp .anj-header { margin-bottom: 22px; }
#anjApp .anj-header-inner {
  background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
  border-radius: 22px; padding: 22px 26px;
  position: relative; overflow: hidden;
  display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
#anjApp .anj-header-inner::before {
  content: ''; position: absolute; inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}
#anjApp .anj-title-group { position: relative; z-index: 1; }
#anjApp .anj-eyebrow { font-size: .67rem; font-weight: 700; letter-spacing: 1.4px; text-transform: uppercase; color: rgba(255,255,255,.7); margin-bottom: 4px; }
#anjApp .anj-title { font-family: 'Literata', Georgia, serif; font-size: 1.5rem; font-weight: 600; color: #fff; line-height: 1.15; margin: 0; }

#anjApp .anj-badge {
  position: relative; z-index: 1;
  background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
  border-radius: 14px; padding: 10px 16px; backdrop-filter: blur(6px);
  text-align: center; flex-shrink: 0; display: flex; align-items: center; gap: 10px;
}
#anjApp .anj-badge-lbl { font-size: .68rem; font-weight: 800; color: rgba(255,255,255,.9); letter-spacing: .5px; text-transform: uppercase; }

/* ── Navigation Tabs ── */
.hr-tabs {
  display: flex; gap: 10px; margin-bottom: 22px;
}
.hr-tab-btn {
  padding: 10px 20px; border: 1px solid var(--border);
  background: var(--surface); border-radius: 12px;
  font-family: inherit; font-size: 0.84rem; font-weight: 700;
  color: var(--text-2); cursor: pointer; transition: all .18s;
  display: inline-flex; align-items: center; gap: 8px;
  box-shadow: var(--shadow-sm);
}
.hr-tab-btn:hover { color: var(--gold); border-color: var(--gold); background: var(--gold-muted); }
.hr-tab-btn.active {
  color: #ffffff; background: linear-gradient(135deg, #b8860b 0%, #966c07 100%);
  border-color: #b8860b; box-shadow: 0 4px 14px rgba(184,134,11,0.3);
}

/* ── Step Cards & Selection Header ── */
.assign-steps-row {
  display: grid; grid-template-columns: repeat(3, 1fr) 1.2fr;
  gap: 14px; margin-bottom: 22px;
}
@media (max-width: 992px) { .assign-steps-row { grid-template-columns: 1fr; } }

.step-card {
  background: var(--surface); border: 1px solid var(--border);
  border-radius: var(--radius); padding: 14px 16px;
  box-shadow: var(--shadow-sm);
}
.step-label {
  font-size: 0.68rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .8px; color: var(--text-3); display: block; margin-bottom: 6px;
}
.step-select {
  width: 100%; padding: 8px 12px; border: 1px solid var(--border);
  border-radius: var(--radius-sm); font-family: inherit; font-size: 0.82rem;
  font-weight: 600; color: var(--text-1); background: var(--surface-2);
  outline: none; transition: border-color .15s;
}
.step-select:focus { border-color: var(--gold); background: #fff; }

.role-info-banner {
  background: #fdf8e6; border: 1px solid #f5e9c0;
  border-radius: var(--radius); padding: 14px 16px;
  display: flex; align-items: flex-start; gap: 12px;
}
.role-info-banner i { color: var(--gold); font-size: 1.1rem; margin-top: 2px; }
.role-info-title { font-size: 0.82rem; font-weight: 800; color: #78520a; display: block; }
.role-info-sub { font-size: 0.72rem; color: #8a6408; display: block; }

/* ── Workspace 2-Column Split ── */
.assign-workspace {
  display: grid; grid-template-columns: 1fr 320px;
  gap: 20px; margin-bottom: 24px;
}
@media (max-width: 1024px) { .assign-workspace { grid-template-columns: 1fr; } }

.table-card {
  background: var(--surface); border: 1px solid var(--border);
  border-radius: var(--radius-lg); padding: 18px;
  box-shadow: var(--shadow-sm);
}

.search-bar-wrap {
  display: flex; gap: 10px; margin-bottom: 14px; flex-wrap: wrap;
}
.search-input-box {
  flex: 1; min-width: 200px; display: flex; align-items: center;
  background: var(--surface-2); border: 1px solid var(--border);
  border-radius: var(--radius-sm); padding: 0 12px;
}
.search-input-box i { color: var(--text-3); font-size: 0.85rem; margin-right: 8px; }
.search-input-box input {
  border: none; background: transparent; outline: none;
  font-family: inherit; font-size: 0.8rem; color: var(--text-1); width: 100%;
}

.table-responsive-custom {
  overflow-x: auto; margin-bottom: 14px;
}
table.members-tbl {
  width: 100%; border-collapse: collapse; font-size: 0.78rem;
}
table.members-tbl th {
  background: var(--surface-2); padding: 10px; text-align: left;
  font-size: 0.66rem; text-transform: uppercase; letter-spacing: .6px;
  color: var(--text-2); border-bottom: 2px solid var(--border);
  font-weight: 800;
}
table.members-tbl td {
  padding: 10px; border-bottom: 1px solid var(--border-light);
  vertical-align: middle;
}
table.members-tbl tr:hover { background: #fdfaf3; }
table.members-tbl input[type="checkbox"] { accent-color: var(--gold); cursor: pointer; }

.status-pill {
  font-size: 0.66rem; font-weight: 800; padding: 2px 8px;
  border-radius: 10px; display: inline-block;
}
.status-active { background: #dcfce7; color: #15803d; }
.status-inactive { background: #fee2e2; color: #b91c1c; }

/* Pagination controls */
.pagination-wrap {
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 10px; font-size: 0.76rem; color: var(--text-2);
}
.page-btn {
  padding: 4px 10px; border: 1px solid var(--border);
  background: var(--surface); border-radius: 6px; font-weight: 700;
  cursor: pointer; color: var(--text-1); transition: all .15s;
}
.page-btn:hover, .page-btn.active {
  background: var(--gold); color: #fff; border-color: var(--gold);
}

/* ── Right Summary Sidebar ── */
.summary-panel {
  display: flex; flex-direction: column; gap: 16px;
}
.summary-card {
  background: var(--surface); border: 1px solid var(--border);
  border-radius: var(--radius-lg); padding: 18px;
  box-shadow: var(--shadow-sm);
}
.summary-card h4 {
  font-size: 0.86rem; font-weight: 800; color: var(--text-1);
  margin: 0 0 12px 0; display: flex; align-items: center; justify-content: space-between;
}
.summary-meta-item {
  margin-bottom: 10px;
}
.summary-meta-label {
  font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
  color: var(--text-3); display: block;
}
.summary-meta-val {
  font-size: 0.82rem; font-weight: 700; color: var(--text-1);
}

.assigned-box {
  background: var(--surface-2); border: 1px dashed var(--border);
  border-radius: var(--radius-sm); padding: 12px; text-align: center;
  font-size: 0.76rem; color: var(--text-3);
}
.assigned-user-card {
  display: flex; align-items: center; gap: 10px;
  background: #fdfaf3; border: 1px solid var(--border);
  border-radius: var(--radius-sm); padding: 10px 12px; margin-bottom: 8px;
}
.user-avatar {
  width: 32px; height: 32px; border-radius: 50%;
  background: var(--gold-muted); color: var(--gold);
  display: flex; align-items: center; justify-content: center;
  font-weight: 800; font-size: 0.78rem; flex-shrink: 0;
}

.btn-assign-primary {
  width: 100%; padding: 12px; border: none;
  background: linear-gradient(135deg, #b8860b 0%, #966c07 100%);
  color: #ffffff; font-family: inherit; font-size: 0.84rem;
  font-weight: 800; border-radius: var(--radius-sm); cursor: pointer;
  box-shadow: 0 4px 14px rgba(184,134,11,0.35); transition: all .15s;
}
.btn-assign-primary:hover {
  background: linear-gradient(135deg, #966c07 0%, #78520a 100%);
  transform: translateY(-1px);
}

.selected-user-pill {
  background: #fefce8; border: 1px solid #fef08a;
  border-radius: var(--radius-sm); padding: 6px 10px;
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 6px; font-size: 0.76rem;
}
.btn-remove-sel {
  border: none; background: transparent; color: #ef4444;
  cursor: pointer; font-size: 0.85rem; padding: 0 4px;
}

/* ── Audit History Table ── */
.history-card {
  background: var(--surface); border: 1px solid var(--border);
  border-radius: var(--radius-lg); padding: 18px;
  box-shadow: var(--shadow-sm); margin-bottom: 24px;
}

/* ═══════════════════════════════════════════════════
   TAB 2: TEAM HIERARCHY MODULE
═══════════════════════════════════════════════════ */
.level-badge {
  font-size: 0.66rem; font-weight: 800; padding: 3px 10px;
  border-radius: 20px; background: var(--gold-muted); color: var(--gold);
  text-transform: uppercase; letter-spacing: .5px; display: inline-block;
}
.umoor-grid {
  display: grid; grid-template-columns: repeat(6, 1fr);
  gap: 12px; margin-bottom: 24px;
}
@media (max-width: 1200px) { .umoor-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px) { .umoor-grid { grid-template-columns: repeat(2, 1fr); } }

.u-card {
  border: 1px solid var(--border); border-radius: var(--radius-sm);
  padding: 12px; text-align: center; background: var(--surface-2);
  transition: all .15s;
}
.u-card:hover { border-color: var(--gold); background: #fff; box-shadow: var(--shadow-sm); }
.u-num { font-size: 0.65rem; font-weight: 800; color: var(--text-3); }
.u-name { font-size: 0.78rem; font-weight: 800; color: var(--text-1); margin: 4px 0; }
.u-coord { font-size: 0.7rem; color: var(--gold); font-weight: 700; }

.level2-split {
  display: grid; grid-template-columns: 240px 1fr 240px;
  gap: 16px;
}
@media (max-width: 992px) { .level2-split { grid-template-columns: 1fr; } }

.u-select-list {
  border: 1px solid var(--border); border-radius: var(--radius-sm);
  overflow: hidden; background: var(--surface);
}
.u-select-item {
  padding: 10px 12px; font-size: 0.76rem; font-weight: 700;
  color: var(--text-2); border-bottom: 1px solid var(--border-light);
  cursor: pointer; display: flex; align-items: center; justify-content: space-between;
  transition: background .15s;
}
.u-select-item:hover, .u-select-item.active {
  background: var(--gold-muted); color: var(--gold);
}

.controls-sidebar {
  border: 1px solid var(--border); border-radius: var(--radius-sm);
  padding: 14px; font-size: 0.74rem; background: var(--surface-2);
}
.controls-sidebar h5 {
  font-size: 0.76rem; font-weight: 800; color: var(--text-1); margin-bottom: 8px;
}
.controls-sidebar ul { padding-left: 14px; margin: 0 0 12px 0; }

/* Modal & Autocomplete */
.modal-overlay {
  position: fixed; inset: 0; background: rgba(15,23,42,0.4);
  backdrop-filter: blur(2px); z-index: 9999; display: none;
  align-items: center; justify-content: center;
}
.modal-box {
  background: #fff; border-radius: var(--radius); width: 100%;
  max-width: 480px; padding: 20px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
}

.autocomplete-dropdown {
  position: absolute; top: 100%; left: 0; right: 0;
  background: #ffffff; border: 1px solid var(--border);
  border-radius: var(--radius-sm); box-shadow: 0 10px 25px rgba(0,0,0,0.12);
  max-height: 200px; overflow-y: auto; z-index: 10000; margin-top: 4px;
}
.ac-item {
  padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f1f5f9;
  display: flex; align-items: center; gap: 10px; font-size: 0.78rem;
  transition: background .15s;
}
.ac-item:hover { background: var(--gold-muted); color: var(--gold); }
.ac-name { font-weight: 700; color: var(--text-1); }
.ac-its { font-size: 0.7rem; color: var(--text-3); }
</style>

<div id="anjApp">
  <div class="anj-root">
    
    <!-- ══ EXACT DASHBOARD LEFT SIDEBAR ══ -->
    <aside class="anj-sidebar d-none d-lg-block">
      <div class="sb-brand mb-3">
        <span class="sb-ico" style="display:inline-flex;width:26px;height:26px;border-radius:7px;background:var(--gold-muted);color:var(--gold);align-items:center;justify-content:center;font-size:.78rem;margin-right:8px;">
          <i class="fa fa-tachometer"></i>
        </span>Admin Menu
      </div>
      
      <div class="sb-search" role="search">
        <i class="fa fa-search"></i>
        <input id="quickMenuSearch" type="text" placeholder="Search menu..." autocomplete="off">
      </div>

      <div class="menu-section">Member Management</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('admin/managemembers'); ?>"><span class="menu-icon"><i class="fa fa-users"></i></span><span class="menu-label">Manage Members</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/importmembers'); ?>"><span class="menu-icon"><i class="fa fa-upload"></i></span><span class="menu-label">Import Members</span></a></li>
        <li><a class="menu-item active" href="<?php echo base_url('admin/umoor_sub_committees'); ?>"><span class="menu-icon"><i class="fa fa-sitemap"></i></span><span class="menu-label">12 Umoor HR</span></a></li>
      </ul>

      <div class="menu-section">Miqaat &amp; FMB</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('admin/razalist'); ?>"><span class="menu-icon"><i class="fa fa-list"></i></span><span class="menu-label">Manage Raza Form</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/managefmbtakhmeen'); ?>"><span class="menu-icon"><i class="fa fa-cutlery"></i></span><span class="menu-label">FMB Thaali Takhmeen</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/manageniyazamounts'); ?>"><span class="menu-icon"><i class="fa fa-coins"></i></span><span class="menu-label">Miqaat Niyaz Takhmeen</span></a></li>
      </ul>

      <div class="menu-section">Finance &amp; Sabeel</div>
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
    </aside>

    <!-- ══ MAIN CONTENT ══ -->
    <main class="anj-content">
      
      <!-- Top Dashboard Banner -->
      <div class="anj-header">
        <div class="anj-header-inner">
          <div class="anj-title-group">
            <div class="anj-eyebrow">12 Umoor HR Module</div>
            <h1 class="anj-title">12 Umoor HR Hierarchy & Role Management</h1>
            <p style="color:rgba(255,255,255,0.8);font-size:0.8rem;margin:4px 0 0 0">
              Manage Level 1 Coordinators, Level 2 Team Leads, and Level 3 Team Members year by year.
            </p>
          </div>

          <div class="anj-badge">
            <span class="anj-badge-lbl"><i class="fa fa-calendar"></i> Hijri Year</span>
            <select id="hr-year-select" onchange="changeYear(this.value)" style="background:rgba(255,255,255,0.25);color:#fff;border:1px solid rgba(255,255,255,0.4);border-radius:10px;padding:6px 12px;font-weight:800;font-size:0.85rem;outline:none;cursor:pointer">
              <?php foreach ($years_list as $y): ?>
                <option value="<?php echo $y; ?>" <?php echo ($active_year == $y) ? 'selected' : ''; ?> style="color:#1a1610">
                  <?php echo $y; ?> Hijri
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- Navigation Tabs -->
      <div class="hr-tabs">
        <button type="button" class="hr-tab-btn active" onclick="switchTab('assignment')" id="tab-btn-assignment">
          <i class="fa fa-user-plus"></i> Role Assignment
        </button>
        <button type="button" class="hr-tab-btn" onclick="switchTab('hierarchy')" id="tab-btn-hierarchy">
          <i class="fa fa-sitemap"></i> Team Hierarchy (Level 1 & Level 2)
        </button>
      </div>

      <!-- ═══════════════════════════════════════════════════
           TAB 1: ROLE ASSIGNMENT WORKSPACE
      ═══════════════════════════════════════════════════ -->
      <div id="tab-assignment-content">
        
        <!-- 3 Step Filters Header -->
        <div class="assign-steps-row">
          <div class="step-card">
            <span class="step-label">1. Select Umoor</span>
            <select class="step-select" id="sel-umoor" onchange="onUmoorChange()">
              <?php foreach ($umoor_list as $uid => $uname): ?>
                <option value="<?php echo $uid; ?>"><?php echo $uid . '. ' . htmlspecialchars($uname); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="step-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
              <span class="step-label" style="margin-bottom:0">2. Select Sub Committee</span>
              <button type="button" onclick="openAddTeamModal()" style="border:none;background:none;color:var(--gold);font-weight:700;font-size:0.7rem;cursor:pointer;padding:0" title="Create a new Sub-Committee / Team"><i class="fa fa-plus-circle"></i> Add Team</button>
            </div>
            <select class="step-select" id="sel-subcomm" onchange="onSubCommChange()">
              <!-- Populated via JS -->
            </select>
          </div>

          <div class="step-card">
            <span class="step-label">3. Select Role</span>
            <select class="step-select" id="sel-role" onchange="onRoleChange()">
              <option value="Team Lead">Team Lead</option>
              <option value="Team Member">Team Member</option>
            </select>
          </div>

          <div class="role-info-banner" id="role-type-banner">
            <i class="fa fa-info-circle"></i>
            <div>
              <span class="role-info-title" id="banner-title">Role Type: Single (Team Lead)</span>
              <span class="role-info-sub" id="banner-sub">Only 1 Team Lead per Team per Year</span>
            </div>
          </div>
        </div>

        <!-- 2 Column Workspace Split -->
        <div class="assign-workspace">
          
          <!-- LEFT COLUMN: Members Search & Filter Table -->
          <div class="table-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px">
              <div>
                <h3 style="font-size:0.92rem;font-weight:800;color:var(--text-1);margin:0">Assigned Members Roster</h3>
                <p style="font-size:0.74rem;color:var(--text-2);margin:2px 0 0 0">Showing active assigned members for this Umoor / Sub-Committee.</p>
              </div>
              <button type="button" class="btn-assign-primary" style="width:auto;padding:6px 14px;font-size:0.78rem" onclick="openAssignMemberModal()">
                <i class="fa fa-user-plus"></i> Assign Member
              </button>
            </div>

            <div class="search-bar-wrap">
              <div class="search-input-box">
                <i class="fa fa-search"></i>
                <input type="text" id="search-q" placeholder="Filter assigned members..." onkeyup="onSearchInput()">
              </div>
              <select class="step-select" style="width:auto" id="filter-gender" onchange="loadMembers(1)">
                <option value="All">Gender: All</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
              <select class="step-select" style="width:auto" id="filter-status" onchange="loadMembers(1)">
                <option value="All">Status: All</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
              <button type="button" class="btn-assign-primary" style="width:auto;padding:0 14px;background:var(--surface-2);color:var(--text-1);border:1px solid var(--border);box-shadow:none" onclick="resetFilters()">
                <i class="fa fa-undo"></i> Reset
              </button>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
              <label style="font-size:0.76rem;font-weight:700;cursor:pointer">
                <input type="checkbox" id="chk-select-all" onchange="toggleSelectAllPage(this.checked)"> Select All (Page)
              </label>
              <span style="font-size:0.74rem;color:var(--text-3)"><span id="sel-count-pill" style="font-weight:800;color:var(--gold)">0</span> member selected</span>
            </div>

            <div class="table-responsive-custom">
              <table class="members-tbl">
                <thead>
                  <tr>
                    <th style="width:36px"></th>
                    <th>ITS Number</th>
                    <th>Member Name</th>
                    <th>Assigned Role</th>
                    <th>Mobile Number</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Family (HOF)</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="tbl-members-body">
                  <!-- JS populated -->
                </tbody>
              </table>
            </div>

            <div class="pagination-wrap">
              <span id="page-count-info">Showing 1 to 10 of members</span>
              <div id="pagination-controls" style="display:flex;gap:4px"></div>
            </div>
          </div>

          <!-- RIGHT COLUMN: Assignment Summary Panel -->
          <div class="summary-panel">
            <div class="summary-card">
              <h4>Assignment Summary <span class="level-badge" id="summary-role-type-badge">Single Role</span></h4>
              
              <div class="summary-meta-item">
                <span class="summary-meta-label">Hijri Year</span>
                <span class="summary-meta-val" id="sum-year"><?php echo $active_year; ?></span>
              </div>
              <div class="summary-meta-item">
                <span class="summary-meta-label">Umoor</span>
                <span class="summary-meta-val" id="sum-umoor">—</span>
              </div>
              <div class="summary-meta-item">
                <span class="summary-meta-label">Sub Committee</span>
                <span class="summary-meta-val" id="sum-subcomm">—</span>
              </div>
              <div class="summary-meta-item">
                <span class="summary-meta-label">Role</span>
                <span class="summary-meta-val" id="sum-role">—</span>
              </div>
            </div>

            <div class="summary-card">
              <h4>Currently Assigned</h4>
              <div id="currently-assigned-box">
                <div class="assigned-box"><i class="fa fa-user"></i> No member assigned yet</div>
              </div>
            </div>

            <div class="summary-card">
              <h4>Selected Member(s) <span class="level-badge" id="selected-count-badge">0</span></h4>
              <div id="selected-members-list">
                <p class="text-muted small text-center my-2">Please select a member from the list to assign.</p>
              </div>
              <button type="button" class="btn-assign-primary" onclick="openAssignMemberModal()">
                <i class="fa fa-user-plus"></i> Assign Member
              </button>
            </div>

            <div class="summary-card" style="background:#fefce8;border-color:#fef08a">
              <h5 style="font-size:0.76rem;font-weight:800;color:#8a6408;margin:0 0 6px 0"><i class="fa fa-info-circle"></i> Role Rules:</h5>
              <ul style="padding-left:14px;margin:0;font-size:0.72rem;color:#78520a">
                <li><strong>Umoor Coordinator</strong> – Only 1 member</li>
                <li><strong>Team Lead</strong> – Only 1 member</li>
                <li><strong>Members</strong> – Multiple members allowed</li>
              </ul>
            </div>
          </div>

        </div>

        <!-- Audit Log History -->
        <div class="history-card">
          <h4 style="font-size:0.86rem;font-weight:800;color:var(--text-1);margin:0 0 14px 0;display:flex;align-items:center;justify-content:space-between">
            <span><i class="fa fa-history"></i> Assignment History (Audit Log)</span>
          </h4>
          <div class="table-responsive-custom">
            <table class="members-tbl">
              <thead>
                <tr>
                  <th>Member Name</th>
                  <th>ITS Number</th>
                  <th>Role</th>
                  <th>Sub-Committee</th>
                  <th>Assigned By</th>
                  <th>Assigned On</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="tbl-history-body">
                <!-- JS Populated -->
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- ═══════════════════════════════════════════════════
           TAB 2: TEAM HIERARCHY MODULE
      ═══════════════════════════════════════════════════ -->
      <div id="tab-hierarchy-content" style="display:none">
        
        <div style="margin-bottom:20px">
          <h2 style="font-size:1.1rem;font-weight:800;color:var(--text-1);margin:0">Team Hierarchy Module – Level 1 & Level 2</h2>
          <p style="font-size:0.78rem;color:var(--text-2);margin:2px 0 0 0">Umoor (Level 1) are hard-coded. Admin can create Team Leads (Level 2) under each Umoor.</p>
        </div>

        <!-- Level 1 Coordinators Grid -->
        <div style="margin-bottom:24px">
          <h3 style="font-size:0.82rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--text-3);margin-bottom:12px">
            Level 1 – Umoor Coordinators (12 Hard-Coded Umoor)
          </h3>
          <div class="umoor-grid" id="level1-grid">
            <!-- JS populated 12 cards -->
          </div>
        </div>

        <!-- Level 2 Team Leads Split View -->
        <div style="margin-bottom:24px">
          <h3 style="font-size:0.82rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--text-3);margin-bottom:12px">
            Level 2 – Team Leads (Sub-Committee Heads)
          </h3>

          <div class="level2-split">
            <!-- Select Umoor Left List -->
            <div>
              <span class="step-label">Select Umoor</span>
              <div class="u-select-list" id="l2-umoor-select-list">
                <!-- JS populated -->
              </div>
            </div>

            <!-- Middle Teams Table -->
            <div class="table-card">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                <h4 style="font-size:0.86rem;font-weight:800;margin:0" id="l2-selected-umoor-heading">Teams under Selected Umoor</h4>
                <button type="button" class="btn-assign-primary" style="width:auto;padding:6px 14px;font-size:0.76rem" onclick="openAddTeamModal()">
                  <i class="fa fa-plus"></i> Add Team Lead / Team
                </button>
              </div>

              <div class="table-responsive-custom">
                <table class="members-tbl">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Team Name</th>
                      <th>Team Lead (Member)</th>
                      <th>Members Count</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody id="l2-teams-tbl-body">
                    <!-- JS populated -->
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Right Controls Panel -->
            <div class="controls-sidebar">
              <h5>Level 1 Controls</h5>
              <ul>
                <li>View All Umoor</li>
                <li>View Coordinator</li>
                <li>No Create / Edit / Delete</li>
                <li>Umoor are Hard-coded</li>
              </ul>
              <h5>Level 2 Controls (Admin)</h5>
              <ul>
                <li>Add Team Lead / Team</li>
                <li>Edit Team</li>
                <li>Delete Team</li>
                <li>Assign / Transfer Team Lead</li>
                <li>View Members in Team</li>
              </ul>
            </div>

          </div>
        </div>

      </div>

    </main>
  </div>
</div>

<!-- Modal: Add / Edit Team -->
<div class="modal-overlay" id="team-modal">
  <div class="modal-box">
    <h3 style="font-size:1rem;font-weight:800;margin:0 0 16px 0;color:var(--text-1)" id="modal-team-title">Add Team Lead / Team</h3>
    
    <form onsubmit="saveTeamSubmit(event)">
      <input type="hidden" id="modal-team-id" value="0">
      <div style="margin-bottom:12px">
        <label class="step-label">Umoor Category</label>
        <select class="step-select" id="modal-team-umoor" required>
          <?php foreach ($umoor_list as $uid => $uname): ?>
            <option value="<?php echo $uid; ?>"><?php echo $uid . '. ' . htmlspecialchars($uname); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div style="margin-bottom:12px">
        <label class="step-label">Team / Sub-Committee Name</label>
        <input type="text" class="step-select" id="modal-team-name" placeholder="e.g. Madresa Team, Library Team..." required>
      </div>

      <div style="margin-bottom:16px;position:relative">
        <label class="step-label">Team Lead <span style="color:#ef4444">* (Compulsory)</span></label>
        <input type="hidden" id="modal-team-lead-its" required>
        <input type="text" class="step-select" id="modal-team-lead-input" placeholder="Type Name or ITS ID to search..." autocomplete="off" oninput="onTeamLeadInput(this.value)" required>
        <div id="modal-team-lead-results" class="autocomplete-dropdown" style="display:none"></div>
        <div id="modal-team-lead-selected" style="font-size:0.75rem;color:#15803d;font-weight:700;margin-top:4px;display:none"></div>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:8px">
        <button type="button" class="btn-assign-primary" style="width:auto;background:var(--surface-2);color:var(--text-1);border:1px solid var(--border);box-shadow:none" onclick="closeAddTeamModal()">Cancel</button>
        <button type="submit" class="btn-assign-primary" style="width:auto;padding:0 18px">Save Team</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Assign Member Popup -->
<div class="modal-overlay" id="assign-member-modal">
  <div class="modal-box" style="max-width:760px; max-height:88vh; display:flex; flex-direction:column; overflow:hidden;">
    <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:12px; border-bottom:1px solid var(--border);">
      <div>
        <h3 style="font-size:1rem; font-weight:800; margin:0; color:var(--text-1)" id="modal-assign-title">Select Member to Assign</h3>
        <span style="font-size:0.75rem; color:var(--text-2)" id="modal-assign-subtitle">12 Umoor HR &bull; 1448 Hijri</span>
      </div>
      <button type="button" onclick="closeAssignMemberModal()" style="border:none; background:var(--surface-2); border-radius:6px; width:28px; height:28px; cursor:pointer; font-size:1.1rem;">&times;</button>
    </div>

    <div style="padding:16px 0; overflow-y:auto; flex:1;">
      <!-- Search & Filters inside Modal -->
      <div style="display:flex; gap:10px; margin-bottom:14px; flex-wrap:wrap;">
        <div style="flex:1; min-width:200px; display:flex; align-items:center; background:var(--surface-2); border:1px solid var(--border); border-radius:8px; padding:0 12px;">
          <i class="fa fa-search" style="color:var(--text-3); font-size:0.85rem; margin-right:8px;"></i>
          <input type="text" id="modal-search-q" placeholder="Type Name or ITS ID to search Jamaat members..." style="border:none; background:transparent; outline:none; font-size:0.8rem; color:var(--text-1); width:100%;" onkeyup="onModalSearchInput()">
        </div>
        <select id="modal-filter-gender" class="step-select" style="width:auto" onchange="loadModalMembers(1)">
          <option value="All">Gender: All</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
        <select id="modal-filter-status" class="step-select" style="width:auto" onchange="loadModalMembers(1)">
          <option value="Active">Status: Active</option>
          <option value="All">Status: All</option>
        </select>
      </div>

      <!-- Candidate Members Table inside Modal -->
      <div class="table-responsive-custom" style="max-height:340px; overflow-y:auto;">
        <table class="members-tbl">
          <thead>
            <tr>
              <th style="width:36px"></th>
              <th>ITS ID</th>
              <th>Member Name</th>
              <th>Mobile</th>
              <th>Gender</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="tbl-modal-members-body">
            <!-- JS populated -->
          </tbody>
        </table>
      </div>

      <div style="display:flex; align-items:center; justify-content:space-between; margin-top:12px;">
        <span id="modal-page-count-info" style="font-size:0.75rem; color:var(--text-2)">Showing members</span>
        <div id="modal-pagination-controls" style="display:flex; gap:4px"></div>
      </div>
    </div>

    <!-- Selected Member Preview Footer -->
    <div style="border-top:1px solid var(--border); padding-top:14px; display:flex; align-items:center; justify-content:space-between;">
      <div id="modal-selected-summary" style="font-size:0.8rem; font-weight:700; color:var(--text-1);">
        No member selected
      </div>
      <div style="display:flex; gap:8px;">
        <button type="button" class="btn-assign-primary" style="width:auto; background:var(--surface-2); color:var(--text-1); border:1px solid var(--border); box-shadow:none" onclick="closeAssignMemberModal()">Cancel</button>
        <button type="button" class="btn-assign-primary" style="width:auto; padding:0 20px" onclick="confirmModalAssignment()">
          <i class="fa fa-check-circle"></i> Confirm &amp; Assign Member
        </button>
      </div>
    </div>
  </div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';
var activeYear = '<?php echo $active_year; ?>';
var umoorList = <?php echo json_encode($umoor_list); ?>;
var subCommittees = <?php echo json_encode($sub_committees); ?>;
var hierarchyData = <?php echo json_encode($hierarchy); ?>;

var state = {
  year: activeYear,
  umoorId: 1,
  subCommId: null,
  role: 'Team Lead',
  selectedMembers: [],
  membersMap: {},
  page: 1
};

document.addEventListener('DOMContentLoaded', function() {
  onUmoorChange();
  renderHierarchyTab();

  $('#quickMenuSearch').on('keyup input', function() {
    var q = $(this).val().toLowerCase().trim();
    $('.menu-list li').each(function() {
      var txt = $(this).text().toLowerCase();
      $(this).toggle(txt.indexOf(q) !== -1);
    });
  });
});

function changeYear(yr) {
  window.location.href = BASE_URL + 'admin/umoor_sub_committees?year=' + yr;
}

function switchTab(tab) {
  document.getElementById('tab-btn-assignment').classList.toggle('active', tab === 'assignment');
  document.getElementById('tab-btn-hierarchy').classList.toggle('active', tab === 'hierarchy');
  document.getElementById('tab-assignment-content').style.display = (tab === 'assignment') ? 'block' : 'none';
  document.getElementById('tab-hierarchy-content').style.display = (tab === 'hierarchy') ? 'block' : 'none';
}

function isCoordinatorRole(role) {
  role = (role || '').trim();
  return (
    role === 'Male Coordinator' ||
    role === 'Female Coordinator (Al Aqeeq)' ||
    role === 'Female Coordinator (Al Aqeeq Member)' ||
    role === 'Female Coordinator' ||
    role === 'Al Aqeeq Member' ||
    role === 'Umoor Coordinator' ||
    role === 'Coordinator'
  );
}

function onUmoorChange() {
  state.umoorId = parseInt(document.getElementById('sel-umoor').value) || 1;
  populateSubCommitteesDropdown();
}

function populateSubCommitteesDropdown() {
  var select = document.getElementById('sel-subcomm');
  select.innerHTML = '';

  var isCoord = isCoordinatorRole(state.role);

  if (isCoord) {
    var opt = document.createElement('option');
    opt.value = '';
    opt.textContent = 'N/A (Assigned directly to Umoor)';
    select.appendChild(opt);
    state.subCommId = null;
    select.disabled = true;
  } else {
    var curItem = hierarchyData.find(function(h) { return h.umoor_id === state.umoorId; });
    var teams = (curItem && curItem.teams) ? curItem.teams : [];

    if (teams.length > 0) {
      teams.forEach(function(t) {
        var opt = document.createElement('option');
        opt.value = t.id;
        opt.textContent = t.name + ' (' + (t.members_count || 0) + ' Members)';
        select.appendChild(opt);
      });
      state.subCommId = teams[0].id;
      select.disabled = false;
    } else {
      var opt = document.createElement('option');
      opt.value = '';
      opt.textContent = 'No Sub-Committees Created';
      select.appendChild(opt);
      state.subCommId = null;
      select.disabled = true;
    }
  }

  onSubCommChange();
}

function onSubCommChange() {
  var select = document.getElementById('sel-subcomm');
  var isCoord = isCoordinatorRole(state.role);
  state.subCommId = isCoord ? null : (select.value ? parseInt(select.value) : null);
  updateSummaryMeta();
  loadMembers(1);
  loadCurrentlyAssigned();
  loadHistory();
}

function onRoleChange() {
  state.role = document.getElementById('sel-role').value;
  state.selectedMembers = [];
  renderSelectedMembersList();

  var isSingleRole = (state.role === 'Team Lead' || isCoordinatorRole(state.role));
  var selectAllChk = document.getElementById('chk-select-all');
  if (selectAllChk) {
    selectAllChk.checked = false;
    selectAllChk.disabled = isSingleRole;
    if (selectAllChk.parentElement) {
      selectAllChk.parentElement.style.opacity = isSingleRole ? '0.5' : '1';
      selectAllChk.parentElement.style.pointerEvents = isSingleRole ? 'none' : 'auto';
    }
  }
  
  var banner = document.getElementById('role-type-banner');
  var bTitle = document.getElementById('banner-title');
  var bSub = document.getElementById('banner-sub');
  var badge = document.getElementById('summary-role-type-badge');

  if (state.role === 'Male Coordinator') {
    banner.style.background = '#fefce8';
    banner.style.borderColor = '#fef08a';
    bTitle.textContent = 'Role Type: Single (Male Coordinator)';
    bSub.textContent = 'Assigned directly to Umoor (Male members only)';
    badge.textContent = 'Male Coordinator';
  } else if (state.role === 'Female Coordinator (Al Aqeeq)' || state.role === 'Female Coordinator') {
    banner.style.background = '#ecfdf5';
    banner.style.borderColor = '#a7f3d0';
    bTitle.textContent = 'Role Type: Single (Al Aqeeq Member)';
    bSub.textContent = 'Assigned directly to Umoor (Female members only - Al Aqeeq)';
    badge.textContent = 'Al Aqeeq Member';
  } else if (state.role === 'Team Lead') {
    banner.style.background = '#fdf8e6';
    banner.style.borderColor = '#f5e9c0';
    bTitle.textContent = 'Role Type: Single (Team Lead)';
    bSub.textContent = 'Only 1 Team Lead per Team per Year';
    badge.textContent = 'Single Role';
  } else {
    banner.style.background = '#f0fdf4';
    banner.style.borderColor = '#bbf7d0';
    bTitle.textContent = 'Role Type: Multiple (Team Members)';
    bSub.textContent = 'Multiple members allowed per Team';
    badge.textContent = 'Multiple Members';
  }

  populateSubCommitteesDropdown();
}

function updateSummaryMeta() {
  document.getElementById('sum-year').textContent = activeYear;
  document.getElementById('sum-umoor').textContent = umoorList[state.umoorId] || '—';
  
  var selSubText = '—';
  if (isCoordinatorRole(state.role)) {
    selSubText = 'N/A (Assigned directly to Umoor)';
  } else {
    var scElem = document.getElementById('sel-subcomm');
    if (scElem && scElem.options && scElem.options.selectedIndex >= 0) {
      selSubText = scElem.options[scElem.options.selectedIndex].text;
    }
  }
  document.getElementById('sum-subcomm').textContent = selSubText;
  document.getElementById('sum-role').textContent = state.role;
}

var searchTimer = null;
function onSearchInput() {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(function() {
    loadMembers(1);
  }, 300);
}

function resetFilters() {
  document.getElementById('search-q').value = '';
  document.getElementById('filter-gender').value = 'All';
  document.getElementById('filter-status').value = 'All';
  loadMembers(1);
}

function loadMembers(page) {
  state.page = page || 1;
  var q = document.getElementById('search-q').value;
  var gender = document.getElementById('filter-gender').value;
  var status = document.getElementById('filter-status').value;

  var tbody = document.getElementById('tbl-members-body');
  tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:20px"><i class="fa fa-spinner fa-spin"></i> Loading members...</td></tr>';

  $.ajax({
    url: BASE_URL + 'admin/get_members_ajax',
    type: 'GET',
    data: {
      q: q,
      gender: gender,
      status: status,
      year: activeYear,
      umoor_id: state.umoorId,
      sub_committee_id: state.subCommId,
      page: state.page,
      limit: 10
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        renderMembersTable(res.members, res.page, res.total, res.pages);
      } else {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:20px;color:var(--text-3)">Failed to load members.</td></tr>';
      }
    }
  });
}

function renderMembersTable(members, page, total, pages) {
  var tbody = document.getElementById('tbl-members-body');
  tbody.innerHTML = '';
  state.membersMap = {};

  var isSingleRole = (state.role === 'Team Lead' || state.role === 'Umoor Coordinator' || state.role === 'Coordinator');
  var selectAllChk = document.getElementById('chk-select-all');
  if (selectAllChk) {
    selectAllChk.disabled = isSingleRole;
    if (selectAllChk.parentElement) {
      selectAllChk.parentElement.style.opacity = isSingleRole ? '0.5' : '1';
      selectAllChk.parentElement.style.pointerEvents = isSingleRole ? 'none' : 'auto';
    }
  }

  if (!members || members.length === 0) {
    var qVal = document.getElementById('search-q').value.trim();
    var msg = qVal ? 'No members found matching "' + escapeHtml(qVal) + '".' : 'No members assigned to this Umoor / Sub-Committee for ' + activeYear + ' Hijri.<br><small class="text-muted">Click the <strong>Assign Member</strong> button above to search and assign members.</small>';
    tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:24px;color:var(--text-3)">' + msg + '</td></tr>';
    document.getElementById('page-count-info').textContent = 'Showing 0 to 0 of 0 members';
    document.getElementById('pagination-controls').innerHTML = '';
    return;
  }

  members.forEach(function(m) {
    state.membersMap[m.ITS_ID] = m;
    var isSel = state.selectedMembers.includes(m.ITS_ID);
    var tr = document.createElement('tr');

    var inputType = isSingleRole ? 'radio' : 'checkbox';
    var inputName = isSingleRole ? 'single_member_radio' : '';

    var roleLabel = m.assigned_role || state.role || 'Member';
    var roleBadge = '<span class="level-badge" style="font-weight:700;">' + escapeHtml(roleLabel) + '</span>';

    tr.innerHTML = '<td><input type="' + inputType + '" name="' + inputName + '" class="member-chk-item" data-its="' + m.ITS_ID + '" ' + (isSel ? 'checked' : '') + ' onchange="onMemberSelectToggle(\'' + m.ITS_ID + '\', this.checked)"></td>' +
      '<td><strong>' + m.ITS_ID + '</strong></td>' +
      '<td>' + (m.Full_Name || '—') + '</td>' +
      '<td>' + roleBadge + '</td>' +
      '<td>' + (m.Mobile_No || '—') + '</td>' +
      '<td>' + (m.Email || '—') + '</td>' +
      '<td>' + (m.Gender || '—') + '</td>' +
      '<td>' + (m.HOF_Name || '—') + '</td>' +
      '<td><span class="status-pill ' + (m.status_label === 'Active' ? 'status-active' : 'status-inactive') + '">' + m.status_label + '</span></td>';

    tbody.appendChild(tr);
  });

  // Update pagination info
  document.getElementById('page-count-info').textContent = 'Showing ' + ((page-1)*10 + 1) + ' to ' + Math.min(total, page*10) + ' of ' + total + ' members';
  
  var pCtrl = document.getElementById('pagination-controls');
  pCtrl.innerHTML = '';
  for (var i = 1; i <= Math.min(pages, 10); i++) {
    var btn = document.createElement('button');
    btn.className = 'page-btn ' + (i === page ? 'active' : '');
    btn.textContent = i;
    btn.onclick = (function(p) { return function() { loadMembers(p); }; })(i);
    pCtrl.appendChild(btn);
  }
}

function onMemberSelectToggle(its, isChecked) {
  var isSingleRole = (state.role === 'Team Lead' || state.role === 'Umoor Coordinator' || state.role === 'Coordinator');
  if (isSingleRole) {
    if (isChecked) {
      state.selectedMembers = [its];
      $('.member-chk-item').prop('checked', false);
      $('.member-chk-item[data-its="' + its + '"]').prop('checked', true);
    } else {
      state.selectedMembers = [];
    }
  } else {
    if (isChecked) {
      if (!state.selectedMembers.includes(its)) {
        state.selectedMembers.push(its);
      }
    } else {
      state.selectedMembers = state.selectedMembers.filter(function(id) { return id !== its; });
    }
  }
  renderSelectedMembersList();
}

function toggleSelectAllPage(isChecked) {
  Object.keys(state.membersMap).forEach(function(its) {
    if (isChecked && !state.selectedMembers.includes(its)) {
      state.selectedMembers.push(its);
    } else if (!isChecked) {
      state.selectedMembers = state.selectedMembers.filter(function(id) { return id !== its; });
    }
  });
  loadMembers(state.page);
  renderSelectedMembersList();
}

function renderSelectedMembersList() {
  document.getElementById('selected-count-badge').textContent = state.selectedMembers.length;
  document.getElementById('sel-count-pill').textContent = state.selectedMembers.length;
  
  var container = document.getElementById('selected-members-list');
  container.innerHTML = '';

  if (state.selectedMembers.length === 0) {
    container.innerHTML = '<p class="text-muted small text-center my-2">Please select a member from the list to assign.</p>';
    return;
  }

  state.selectedMembers.forEach(function(its) {
    var m = state.membersMap[its] || { ITS_ID: its, Full_Name: 'Member ' + its };
    var div = document.createElement('div');
    div.className = 'selected-user-pill';
    div.innerHTML = '<div><strong>' + (m.Full_Name || m.ITS_ID) + '</strong><br><small class="text-muted">' + m.ITS_ID + '</small></div>' +
      '<button type="button" class="btn-remove-sel" onclick="onMemberSelectToggle(\'' + its + '\', false)"><i class="fa fa-times"></i></button>';
    container.appendChild(div);
  });
}

function loadCurrentlyAssigned() {
  var container = document.getElementById('currently-assigned-box');
  container.innerHTML = '<div style="text-align:center;padding:10px"><i class="fa fa-spinner fa-spin"></i> Loading...</div>';

  var isCoord = (state.role === 'Male Coordinator' || state.role === 'Female Coordinator (Al Aqeeq)' || state.role === 'Female Coordinator' || state.role === 'Umoor Coordinator' || state.role === 'Coordinator');
  var reqRole = isCoord ? state.role : (state.role === 'Team Lead' ? 'Team Lead' : '');
  var subId = isCoord ? '' : state.subCommId;

  $.ajax({
    url: BASE_URL + 'admin/get_assigned_members_ajax',
    type: 'GET',
    data: {
      year: activeYear,
      umoor_id: state.umoorId,
      sub_committee_id: subId,
      role: reqRole
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success && res.assigned && res.assigned.length > 0) {
        container.innerHTML = '';
        res.assigned.forEach(function(a) {
          var div = document.createElement('div');
          div.className = 'assigned-user-card';
          div.innerHTML = '<div class="user-avatar"><i class="fa fa-user"></i></div>' +
            '<div style="flex:1"><strong>' + (a.member_name || a.user_its) + '</strong><span style="font-size:0.65rem;background:var(--gold-muted);color:var(--gold);padding:1px 6px;border-radius:10px;margin-left:6px;font-weight:800">' + a.role + '</span><br><small class="text-muted">' + a.user_its + '</small></div>' +
            '<button type="button" class="btn-remove-sel" title="Remove Assignment" onclick="removeAssignment(' + a.id + ')"><i class="fa fa-trash"></i></button>';
          container.appendChild(div);
        });
      } else {
        container.innerHTML = '<div class="assigned-box"><i class="fa fa-user"></i> No members assigned yet</div>';
      }
    }
  });
}

function submitAssignment() {
  openAssignMemberModal();
}

var modalState = {
  selectedMembers: [],
  membersMap: {},
  page: 1
};

function openAssignMemberModal() {
  var isCoord = (state.role === 'Male Coordinator' || state.role === 'Female Coordinator (Al Aqeeq)' || state.role === 'Female Coordinator' || state.role === 'Umoor Coordinator' || state.role === 'Coordinator');
  if (!isCoord && !state.subCommId) {
    if (confirm('No Sub-Committee / Team selected for this Umoor. Would you like to create a Sub-Committee / Team first?')) {
      openAddTeamModal();
    }
    return;
  }

  modalState.selectedMembers = [];
  modalState.membersMap = {};

  var uName = umoorList[state.umoorId] || ('Umoor #' + state.umoorId);
  var scName = 'N/A (Umoor Level)';
  var scElem = document.getElementById('sel-subcomm');
  if (scElem && scElem.options && scElem.options.selectedIndex >= 0 && scElem.options[scElem.options.selectedIndex].value) {
    scName = scElem.options[scElem.options.selectedIndex].text;
  }

  document.getElementById('modal-assign-title').textContent = 'Select & Assign ' + state.role;
  document.getElementById('modal-assign-subtitle').textContent = uName + ' \u2022 ' + scName + ' \u2022 ' + activeYear + ' Hijri';

  document.getElementById('modal-search-q').value = '';

  var genderSel = document.getElementById('modal-filter-gender');
  if (state.role === 'Male Coordinator') {
    genderSel.value = 'Male';
    genderSel.disabled = true;
  } else if (state.role === 'Female Coordinator (Al Aqeeq)' || state.role === 'Female Coordinator') {
    genderSel.value = 'Female';
    genderSel.disabled = true;
  } else {
    genderSel.value = 'All';
    genderSel.disabled = false;
  }

  document.getElementById('assign-member-modal').style.display = 'flex';
  renderModalSelectedSummary();
  loadModalMembers(1);
}

function closeAssignMemberModal() {
  document.getElementById('assign-member-modal').style.display = 'none';
}

var modalSearchTimer = null;
function onModalSearchInput() {
  clearTimeout(modalSearchTimer);
  modalSearchTimer = setTimeout(function() {
    loadModalMembers(1);
  }, 300);
}

function loadModalMembers(page) {
  modalState.page = page || 1;
  var q = document.getElementById('modal-search-q').value;
  var gender = document.getElementById('modal-filter-gender').value;
  var status = document.getElementById('modal-filter-status').value;

  var tbody = document.getElementById('tbl-modal-members-body');
  tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:16px"><i class="fa fa-spinner fa-spin"></i> Searching Jamaat members...</td></tr>';

  $.ajax({
    url: BASE_URL + 'admin/get_members_ajax',
    type: 'GET',
    data: {
      q: q,
      gender: gender,
      status: status,
      year: activeYear,
      assigned_only: 0,
      page: modalState.page,
      limit: 10
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        renderModalMembersTable(res.members, res.page, res.total, res.pages);
      } else {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:16px;color:var(--text-3)">Failed to load members.</td></tr>';
      }
    }
  });
}

function renderModalMembersTable(members, page, total, pages) {
  var tbody = document.getElementById('tbl-modal-members-body');
  tbody.innerHTML = '';
  modalState.membersMap = {};

  var isSingleRole = (state.role === 'Team Lead' || state.role === 'Male Coordinator' || state.role === 'Female Coordinator (Al Aqeeq)' || state.role === 'Female Coordinator' || state.role === 'Umoor Coordinator' || state.role === 'Coordinator');

  if (!members || members.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;color:var(--text-3)">No members found matching search.</td></tr>';
    document.getElementById('modal-page-count-info').textContent = 'Showing 0 to 0 of 0 members';
    document.getElementById('modal-pagination-controls').innerHTML = '';
    return;
  }

  members.forEach(function(m) {
    modalState.membersMap[m.ITS_ID] = m;
    var isSel = modalState.selectedMembers.includes(m.ITS_ID);
    var tr = document.createElement('tr');

    var inputType = isSingleRole ? 'radio' : 'checkbox';
    var inputName = isSingleRole ? 'modal_member_radio' : '';

    tr.innerHTML = '<td><input type="' + inputType + '" name="' + inputName + '" class="modal-chk-item" data-its="' + m.ITS_ID + '" ' + (isSel ? 'checked' : '') + ' onchange="onModalMemberSelectToggle(\'' + m.ITS_ID + '\', this.checked)"></td>' +
      '<td><strong>' + m.ITS_ID + '</strong></td>' +
      '<td>' + escapeHtml(m.Full_Name || '—') + '</td>' +
      '<td>' + (m.Mobile_No || '—') + '</td>' +
      '<td>' + (m.Gender || '—') + '</td>' +
      '<td><span class="status-pill ' + (m.status_label === 'Active' ? 'status-active' : 'status-inactive') + '">' + m.status_label + '</span></td>';

    tbody.appendChild(tr);
  });

  document.getElementById('modal-page-count-info').textContent = 'Showing ' + ((page-1)*10 + 1) + ' to ' + Math.min(total, page*10) + ' of ' + total + ' members';

  var pCtrl = document.getElementById('modal-pagination-controls');
  pCtrl.innerHTML = '';
  for (var i = 1; i <= Math.min(pages, 10); i++) {
    var btn = document.createElement('button');
    btn.className = 'page-btn ' + (i === page ? 'active' : '');
    btn.textContent = i;
    btn.onclick = (function(p) { return function() { loadModalMembers(p); }; })(i);
    pCtrl.appendChild(btn);
  }
}

function onModalMemberSelectToggle(its, isChecked) {
  var isSingleRole = (state.role === 'Team Lead' || state.role === 'Male Coordinator' || state.role === 'Female Coordinator (Al Aqeeq)' || state.role === 'Female Coordinator' || state.role === 'Umoor Coordinator' || state.role === 'Coordinator');
  if (isSingleRole) {
    if (isChecked) {
      modalState.selectedMembers = [its];
      $('.modal-chk-item').prop('checked', false);
      $('.modal-chk-item[data-its="' + its + '"]').prop('checked', true);
    } else {
      modalState.selectedMembers = [];
    }
  } else {
    if (isChecked) {
      if (!modalState.selectedMembers.includes(its)) {
        modalState.selectedMembers.push(its);
      }
    } else {
      modalState.selectedMembers = modalState.selectedMembers.filter(function(id) { return id !== its; });
    }
  }
  renderModalSelectedSummary();
}

function renderModalSelectedSummary() {
  var container = document.getElementById('modal-selected-summary');
  if (modalState.selectedMembers.length === 0) {
    container.innerHTML = '<span class="text-muted">No member selected</span>';
    return;
  }
  var names = modalState.selectedMembers.map(function(its) {
    var m = modalState.membersMap[its];
    return (m && m.Full_Name) ? m.Full_Name + ' (' + its + ')' : its;
  });
  container.innerHTML = '<span style="color:var(--gold);"><i class="fa fa-user-check mr-1"></i> Selected: ' + escapeHtml(names.join(', ')) + '</span>';
}

function confirmModalAssignment() {
  if (modalState.selectedMembers.length === 0) {
    alert('Please select at least one member to assign.');
    return;
  }

  var isCoord = (state.role === 'Male Coordinator' || state.role === 'Female Coordinator (Al Aqeeq)' || state.role === 'Female Coordinator' || state.role === 'Umoor Coordinator' || state.role === 'Coordinator');

  $.ajax({
    url: BASE_URL + 'admin/assign_role_ajax',
    type: 'POST',
    data: {
      year: activeYear,
      umoor_id: state.umoorId,
      sub_committee_id: isCoord ? '' : state.subCommId,
      role: state.role,
      user_its: modalState.selectedMembers
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        alert(res.message || 'Assignment successful!');
        closeAssignMemberModal();
        modalState.selectedMembers = [];

        var selRoleElem = document.getElementById('sel-role');
        if (selRoleElem && selRoleElem.value) {
          state.role = selRoleElem.value;
        }

        state.selectedMembers = [];
        renderSelectedMembersList();
        loadMembers(1);
        loadCurrentlyAssigned();
        loadHistory();
        renderHierarchyTab();
      } else {
        alert((res && res.message) ? res.message : 'Failed to save assignment.');
      }
    }
  });
}

function removeAssignment(id) {
  if (!confirm('Are you sure you want to remove this assignment?')) return;
  $.ajax({
    url: BASE_URL + 'admin/remove_assignment_ajax',
    type: 'POST',
    data: { id: id },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        alert(res.message);
        loadCurrentlyAssigned();
        loadHistory();
        renderHierarchyTab();
      } else {
        alert('Failed to remove assignment.');
      }
    }
  });
}

function loadHistory() {
  var tbody = document.getElementById('tbl-history-body');
  tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:14px"><i class="fa fa-spinner fa-spin"></i> Loading audit history...</td></tr>';

  var isCoord = (state.role === 'Male Coordinator' || state.role === 'Female Coordinator (Al Aqeeq)' || state.role === 'Female Coordinator' || state.role === 'Umoor Coordinator' || state.role === 'Coordinator');
  var reqRole = isCoord ? state.role : '';
  var subId = isCoord ? '' : state.subCommId;

  $.ajax({
    url: BASE_URL + 'admin/get_assignment_history_ajax',
    type: 'GET',
    data: {
      year: activeYear,
      umoor_id: state.umoorId,
      sub_committee_id: subId,
      role: reqRole
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success && res.history && res.history.length > 0) {
        tbody.innerHTML = '';
        res.history.forEach(function(h) {
          var tr = document.createElement('tr');
          var stClass = (h.status === 'Active') ? 'status-active' : ((h.status === 'Transferred') ? 'status-inactive' : 'status-inactive');
          tr.innerHTML = '<td><strong>' + (h.member_name || h.user_its) + '</strong></td>' +
            '<td>' + h.user_its + '</td>' +
            '<td><span class="level-badge">' + h.role + '</span></td>' +
            '<td>' + (h.sub_committee_name || 'Umoor Level') + '</td>' +
            '<td>' + (h.assigned_by || 'Admin') + '</td>' +
            '<td>' + (h.assigned_at || h.created_at) + '</td>' +
            '<td><span class="status-pill ' + stClass + '">' + h.status + '</span></td>' +
            '<td>' + (h.status === 'Active' ? '<button type="button" class="btn-remove-sel" onclick="removeAssignment(' + h.id + ')"><i class="fa fa-trash"></i></button>' : '—') + '</td>';
          tbody.appendChild(tr);
        });
      } else {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:14px;color:var(--text-3)">No history logs found.</td></tr>';
      }
    }
  });
}

/* ═══════════════════════════════════════════════════
   TAB 2: HIERARCHY TAB RENDER
═══════════════════════════════════════════════════ */
function renderHierarchyTab() {
  $.ajax({
    url: BASE_URL + 'admin/get_umoor_hierarchy_ajax',
    type: 'GET',
    data: { year: activeYear },
    dataType: 'json',
    success: function(res) {
      if (res && res.success && res.hierarchy) {
        hierarchyData = res.hierarchy;
        renderLevel1Grid();
        renderLevel2Split();
      }
    }
  });
}

function renderLevel1Grid() {
  var container = document.getElementById('level1-grid');
  container.innerHTML = '';

  hierarchyData.forEach(function(item) {
    var div = document.createElement('div');
    div.className = 'u-card';
    div.style.display = 'flex';
    div.style.flexDirection = 'column';
    div.style.justifyContent = 'space-between';

    var mCoordHtml = item.male_coordinator ? 
      '<div style="font-size:0.72rem;color:#b8860b;font-weight:700;margin-bottom:6px;display:flex;align-items:center;justify-content:space-between;" title="Male Coordinator">' +
        '<span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:140px"><i class="fa fa-user-circle"></i> <strong>Male:</strong> ' + escapeHtml(item.male_coordinator.name) + '</span>' +
        '<button type="button" class="btn-assign-primary" style="width:auto;padding:1px 6px;font-size:0.65rem;background:var(--surface-2);color:var(--text-1);border:1px solid var(--border);box-shadow:none;" onclick="openCoordinatorAssignModal(' + item.umoor_id + ', \'Male Coordinator\')"><i class="fa fa-pencil"></i></button>' +
      '</div>' :
      '<div style="font-size:0.7rem;color:var(--text-3);margin-bottom:6px;display:flex;align-items:center;justify-content:space-between;">' +
        '<span><i class="fa fa-user-circle-o"></i> Male: <em>Unassigned</em></span>' +
        '<button type="button" class="btn-assign-primary" style="width:auto;padding:2px 8px;font-size:0.65rem;" onclick="openCoordinatorAssignModal(' + item.umoor_id + ', \'Male Coordinator\')">+ Assign</button>' +
      '</div>';
      
    var fCoordHtml = item.female_coordinator ? 
      '<div style="font-size:0.72rem;color:#059669;font-weight:700;display:flex;align-items:center;justify-content:space-between;" title="Female Coordinator / Al Aqeeq Member">' +
        '<span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:140px"><i class="fa fa-user-circle"></i> <strong>Al Aqeeq:</strong> ' + escapeHtml(item.female_coordinator.name) + '</span>' +
        '<button type="button" class="btn-assign-primary" style="width:auto;padding:1px 6px;font-size:0.65rem;background:var(--surface-2);color:var(--text-1);border:1px solid var(--border);box-shadow:none;" onclick="openCoordinatorAssignModal(' + item.umoor_id + ', \'Female Coordinator (Al Aqeeq)\')"><i class="fa fa-pencil"></i></button>' +
      '</div>' :
      '<div style="font-size:0.7rem;color:var(--text-3);display:flex;align-items:center;justify-content:space-between;">' +
        '<span><i class="fa fa-user-circle-o"></i> Al Aqeeq: <em>Unassigned</em></span>' +
        '<button type="button" class="btn-assign-primary" style="width:auto;padding:2px 8px;font-size:0.65rem;background:#059669;" onclick="openCoordinatorAssignModal(' + item.umoor_id + ', \'Female Coordinator (Al Aqeeq)\')">+ Assign</button>' +
      '</div>';

    div.innerHTML = '<div>' +
        '<div class="u-num">' + item.umoor_id + '</div>' +
        '<div class="u-name">' + escapeHtml(item.umoor_name) + '</div>' +
      '</div>' +
      '<div style="border-top:1px dashed var(--border);padding-top:6px;margin-top:6px;">' +
        mCoordHtml + fCoordHtml +
      '</div>';

    container.appendChild(div);
  });
}

function openCoordinatorAssignModal(umoorId, role) {
  state.umoorId = parseInt(umoorId) || 1;
  state.role = role;
  
  var uSelect = document.getElementById('sel-umoor');
  if (uSelect) uSelect.value = state.umoorId;
  
  openAssignMemberModal();
}

function renderLevel2Split() {
  var uList = document.getElementById('l2-umoor-select-list');
  uList.innerHTML = '';

  hierarchyData.forEach(function(item) {
    var div = document.createElement('div');
    div.className = 'u-select-item ' + (item.umoor_id === state.umoorId ? 'active' : '');
    div.innerHTML = '<span>' + item.umoor_id + '. ' + item.umoor_name + '</span> <span class="status-pill status-active">' + (item.teams ? item.teams.length : 0) + '</span>';
    div.onclick = function() {
      state.umoorId = item.umoor_id;
      document.getElementById('sel-umoor').value = item.umoor_id;
      onUmoorChange();
      renderLevel2Split();
    };
    uList.appendChild(div);
  });

  var curItem = hierarchyData.find(function(h) { return h.umoor_id === state.umoorId; });
  document.getElementById('l2-selected-umoor-heading').textContent = 'Teams under ' + (curItem ? curItem.umoor_name : 'Selected Umoor');

  var tbody = document.getElementById('l2-teams-tbl-body');
  tbody.innerHTML = '';

  if (!curItem || !curItem.teams || curItem.teams.length === 0) {
    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:20px;color:var(--text-3)">No teams/sub-committees created under this Umoor yet.</td></tr>';
    return;
  }

  curItem.teams.forEach(function(team, idx) {
    var tr = document.createElement('tr');
    tr.innerHTML = '<td>' + (idx + 1) + '</td>' +
      '<td><strong>' + team.name + '</strong></td>' +
      '<td>' + (team.team_lead_name ? ('<strong>' + team.team_lead_name + '</strong><br><small class="text-muted">' + team.team_lead_its + '</small>') : '<span class="text-danger">Not Assigned</span>') + '</td>' +
      '<td><span class="status-pill status-active">' + (team.members_count || 0) + ' Members</span></td>' +
      '<td>' +
        '<button type="button" class="btn-assign-primary" style="width:auto;padding:2px 8px;font-size:0.7rem;margin-right:4px;background:var(--surface-2);color:var(--text-1);border:1px solid var(--border);box-shadow:none" onclick="editTeamModal(' + team.id + ', \'' + escapeHtml(team.name) + '\', \'' + (team.team_lead_its || '') + '\', \'' + escapeHtml(team.team_lead_name || '') + '\')"><i class="fa fa-pencil"></i> Edit</button>' +
        '<button type="button" class="btn-assign-primary" style="width:auto;padding:2px 8px;font-size:0.7rem;background:#fee2e2;color:#b91c1c;border:none;box-shadow:none" onclick="deleteTeam(' + team.id + ')"><i class="fa fa-trash"></i> Delete</button>' +
      '</td>';
    tbody.appendChild(tr);
  });
}

function escapeHtml(str) {
  return String(str || '').replace(/'/g, "\\'");
}

/* Add / Edit Team Modal & Autocomplete */
var acTimer = null;
function onTeamLeadInput(q) {
  clearTimeout(acTimer);
  var dropdown = document.getElementById('modal-team-lead-results');
  var hiddenIts = document.getElementById('modal-team-lead-its');
  var selectedDiv = document.getElementById('modal-team-lead-selected');

  hiddenIts.value = '';
  selectedDiv.style.display = 'none';

  if (!q || q.trim().length < 1) {
    dropdown.style.display = 'none';
    return;
  }

  acTimer = setTimeout(function() {
    dropdown.style.display = 'block';
    dropdown.innerHTML = '<div style="padding:10px;text-align:center;font-size:0.74rem;color:#64748b"><i class="fa fa-spinner fa-spin"></i> Searching members...</div>';

    $.ajax({
      url: BASE_URL + 'admin/get_members_ajax',
      type: 'GET',
      data: { q: q, limit: 8 },
      dataType: 'json',
      success: function(res) {
        if (res && res.success && res.members && res.members.length > 0) {
          dropdown.innerHTML = '';
          res.members.forEach(function(m) {
            var item = document.createElement('div');
            item.className = 'ac-item';
            item.innerHTML = '<div class="user-avatar" style="width:26px;height:26px;font-size:0.7rem"><i class="fa fa-user"></i></div>' +
              '<div><div class="ac-name">' + (m.Full_Name || m.ITS_ID) + '</div><div class="ac-its">ITS: ' + m.ITS_ID + (m.Mobile_No ? (' · ' + m.Mobile_No) : '') + '</div></div>';
            item.onclick = function() {
              selectTeamLeadMember(m.ITS_ID, m.Full_Name);
            };
            dropdown.appendChild(item);
          });
        } else {
          dropdown.innerHTML = '<div style="padding:10px;text-align:center;font-size:0.74rem;color:#94a3b8">No matching member found.</div>';
        }
      }
    });
  }, 250);
}

function selectTeamLeadMember(its, name) {
  document.getElementById('modal-team-lead-its').value = its;
  document.getElementById('modal-team-lead-input').value = (name || its) + ' (ITS: ' + its + ')';
  document.getElementById('modal-team-lead-results').style.display = 'none';

  var selectedDiv = document.getElementById('modal-team-lead-selected');
  selectedDiv.style.display = 'block';
  selectedDiv.innerHTML = '<i class="fa fa-check-circle"></i> Selected Team Lead: <strong>' + (name || its) + '</strong> (' + its + ')';
}

document.addEventListener('click', function(e) {
  if (!e.target.closest('#modal-team-lead-input') && !e.target.closest('#modal-team-lead-results')) {
    var dropdown = document.getElementById('modal-team-lead-results');
    if (dropdown) dropdown.style.display = 'none';
  }
});

function openAddTeamModal() {
  document.getElementById('modal-team-id').value = '0';
  document.getElementById('modal-team-name').value = '';
  document.getElementById('modal-team-lead-its').value = '';
  document.getElementById('modal-team-lead-input').value = '';
  document.getElementById('modal-team-lead-selected').style.display = 'none';
  document.getElementById('modal-team-lead-results').style.display = 'none';
  document.getElementById('modal-team-umoor').value = state.umoorId;
  document.getElementById('modal-team-title').textContent = 'Add Team Lead / Team';
  document.getElementById('team-modal').style.display = 'flex';
}

function editTeamModal(id, name, leadIts, leadName) {
  document.getElementById('modal-team-id').value = id;
  document.getElementById('modal-team-name').value = name;
  document.getElementById('modal-team-umoor').value = state.umoorId;
  document.getElementById('modal-team-title').textContent = 'Edit Team Lead / Team';

  if (leadIts) {
    selectTeamLeadMember(leadIts, leadName || leadIts);
  } else {
    document.getElementById('modal-team-lead-its').value = '';
    document.getElementById('modal-team-lead-input').value = '';
    document.getElementById('modal-team-lead-selected').style.display = 'none';
  }

  document.getElementById('team-modal').style.display = 'flex';
}

function closeAddTeamModal() {
  document.getElementById('team-modal').style.display = 'none';
}

function saveTeamSubmit(e) {
  e.preventDefault();
  var id = document.getElementById('modal-team-id').value;
  var umoorId = document.getElementById('modal-team-umoor').value;
  var name = document.getElementById('modal-team-name').value;
  var leadIts = document.getElementById('modal-team-lead-its').value;

  if (!leadIts) {
    alert('Please select a Team Lead from the member list.');
    return;
  }

  $.ajax({
    url: BASE_URL + 'admin/save_sub_committee',
    type: 'POST',
    data: {
      id: id,
      umoor_id: umoorId,
      name: name,
      team_lead_its: leadIts,
      year: activeYear
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        closeAddTeamModal();
        alert(res.message);
        location.reload();
      } else {
        alert((res && res.message) ? res.message : 'Error saving team.');
      }
    }
  });
}

function deleteTeam(id) {
  if (!confirm('Are you sure you want to delete this Team / Sub-committee?')) return;
  $.ajax({
    url: BASE_URL + 'admin/delete_sub_committee',
    type: 'POST',
    data: { id: id },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        alert(res.message);
        location.reload();
      } else {
        alert('Failed to delete team.');
      }
    }
  });
}
</script>
