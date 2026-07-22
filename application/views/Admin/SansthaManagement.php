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

/* ── Sidebar nav ── */
#anjApp .anj-sidebar .sb-brand { font-weight: 800; font-size: .84rem; color: var(--text-2); margin-bottom: 12px; padding: 0 4px; display: flex; align-items: center; gap: 8px; }
#anjApp .anj-sidebar .sb-brand .sb-ico { width: 26px; height: 26px; border-radius: 7px; background: var(--gold-muted); color: var(--gold); display: inline-flex; align-items: center; justify-content: center; font-size: .78rem; }

#anjApp .sb-search { display: flex; align-items: center; gap: 8px; background: var(--surface-2); border: 1.5px solid var(--border); border-radius: 8px; padding: 7px 10px; margin-bottom: 12px; transition: border-color .15s; }
#anjApp .sb-search:focus-within { border-color: var(--gold); }
#anjApp .sb-search i { color: var(--text-3); font-size: .8rem; }
#anjApp .sb-search input { border: none; background: transparent; outline: none; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .81rem; color: var(--text-1); width: 100%; }

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
.hr-tabs { display: flex; gap: 10px; margin-bottom: 22px; }
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

/* ── Cards & Tables ── */
.table-card {
  background: var(--surface); border: 1px solid var(--border);
  border-radius: var(--radius-lg); padding: 18px;
  box-shadow: var(--shadow-sm); margin-bottom: 24px;
}

.table-responsive-custom { overflow-x: auto; margin-bottom: 14px; }
table.sanstha-tbl { width: 100%; border-collapse: collapse; font-size: 0.78rem; }
table.sanstha-tbl th {
  background: var(--surface-2); padding: 10px; text-align: left;
  font-size: 0.66rem; text-transform: uppercase; letter-spacing: .6px;
  color: var(--text-2); border-bottom: 2px solid var(--border);
  font-weight: 800;
}
table.sanstha-tbl td {
  padding: 10px; border-bottom: 1px solid var(--border-light);
  vertical-align: middle;
}
table.sanstha-tbl tr:hover { background: #fdfaf3; }

.status-pill {
  font-size: 0.66rem; font-weight: 800; padding: 2px 8px;
  border-radius: 10px; display: inline-block;
}
.status-active { background: #dcfce7; color: #15803d; }
.status-inactive { background: #fee2e2; color: #b91c1c; }

.btn-action-primary {
  padding: 6px 14px; border: none;
  background: linear-gradient(135deg, #b8860b 0%, #966c07 100%);
  color: #ffffff; font-family: inherit; font-size: 0.78rem;
  font-weight: 700; border-radius: var(--radius-sm); cursor: pointer;
  box-shadow: 0 2px 6px rgba(184,134,11,0.2); transition: all .15s;
  display: inline-flex; align-items: center; gap: 6px;
}
.btn-action-primary:hover {
  background: linear-gradient(135deg, #966c07 0%, #78520a 100%);
  transform: translateY(-1px);
}

.btn-action-sec {
  padding: 5px 10px; border: 1px solid var(--border);
  background: var(--surface-2); color: var(--text-1); font-family: inherit;
  font-size: 0.74rem; font-weight: 700; border-radius: var(--radius-sm);
  cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: 4px;
}
.btn-action-sec:hover { background: var(--gold-muted); color: var(--gold); border-color: var(--gold); }

.btn-action-danger {
  padding: 5px 10px; border: none; background: #fee2e2; color: #b91c1c;
  font-family: inherit; font-size: 0.74rem; font-weight: 700;
  border-radius: var(--radius-sm); cursor: pointer; transition: all .15s;
  display: inline-flex; align-items: center; gap: 4px;
}
.btn-action-danger:hover { background: #fca5a5; color: #991b1b; }

/* Modal & Autocomplete */
.modal-overlay {
  position: fixed; inset: 0; background: rgba(15,23,42,0.4);
  backdrop-filter: blur(2px); z-index: 9999; display: none;
  align-items: center; justify-content: center;
}
.modal-box {
  background: #fff; border-radius: var(--radius); width: 100%;
  max-width: 520px; padding: 22px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
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

/* ── Workspace 2-Column Split ── */
.assign-workspace {
  display: grid; grid-template-columns: 1fr 380px;
  gap: 20px; margin-bottom: 24px;
}
@media (max-width: 1024px) { .assign-workspace { grid-template-columns: 1fr; } }

.search-bar-wrap {
  display: flex; gap: 10px; margin-bottom: 14px; flex-wrap: wrap;
}
.search-input-box {
  flex: 1; min-width: 180px; display: flex; align-items: center;
  background: var(--surface-2); border: 1px solid var(--border);
  border-radius: var(--radius-sm); padding: 0 12px;
}
.search-input-box i { color: var(--text-3); font-size: 0.85rem; margin-right: 8px; }
.search-input-box input {
  border: none; background: transparent; outline: none;
  font-family: inherit; font-size: 0.8rem; color: var(--text-1); width: 100%;
}

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

.assigned-user-card {
  display: flex; align-items: center; gap: 10px;
  background: #fdfaf3; border: 1px solid var(--border);
  border-radius: var(--radius-sm); padding: 8px 10px; margin-bottom: 6px;
}
.user-avatar {
  width: 30px; height: 30px; border-radius: 50%;
  background: var(--gold-muted); color: var(--gold);
  display: flex; align-items: center; justify-content: center;
  font-weight: 800; font-size: 0.74rem; flex-shrink: 0;
}
</style>

<div id="anjApp">
  <div class="anj-root">
    
    <!-- ══ DASHBOARD LEFT SIDEBAR ══ -->
    <aside class="anj-sidebar d-none d-lg-block">
      <div class="sb-brand mb-3">
        <span class="sb-ico"><i class="fa fa-tachometer"></i></span>Admin Menu
      </div>
      
      <div class="sb-search" role="search">
        <i class="fa fa-search"></i>
        <input id="quickMenuSearch" type="text" placeholder="Search menu..." autocomplete="off">
      </div>

      <div class="menu-section">Member Management</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('admin/managemembers'); ?>"><span class="menu-icon"><i class="fa fa-users"></i></span><span class="menu-label">Manage Members</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/importmembers'); ?>"><span class="menu-icon"><i class="fa fa-upload"></i></span><span class="menu-label">Import Members</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/umoor_sub_committees'); ?>"><span class="menu-icon"><i class="fa fa-sitemap"></i></span><span class="menu-label">12 Umoor HR</span></a></li>
        <li><a class="menu-item active" href="<?php echo base_url('admin/sanstha'); ?>"><span class="menu-icon"><i class="fa fa-building-o"></i></span><span class="menu-label">Create Sanstha</span></a></li>
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
      
      <!-- Top Header Banner -->
      <div class="anj-header">
        <div class="anj-header-inner">
          <div class="anj-title-group">
            <div class="anj-eyebrow">Sanstha Management Module</div>
            <h1 class="anj-title">Create &amp; Manage Sanstha</h1>
            <p style="color:rgba(255,255,255,0.8);font-size:0.8rem;margin:4px 0 0 0">
              Create dynamic Sansthas and manage dedicated member rosters per Sanstha.
            </p>
          </div>

          <div class="anj-badge">
            <span style="font-size:0.7rem;font-weight:700;color:rgba(255,255,255,0.7);text-transform:uppercase;margin-right:6px"><i class="fa fa-calendar"></i> Hijri Year:</span>
            <select id="sel-hijri-year" onchange="onYearChange()" style="background:rgba(255,255,255,0.2);color:#fff;border:1px solid rgba(255,255,255,0.3);border-radius:8px;padding:4px 8px;font-weight:800;font-size:0.82rem;outline:none;cursor:pointer">
              <option value="1446" style="color:#000" <?php echo ($year === '1446') ? 'selected' : ''; ?>>1446 Hijri</option>
              <option value="1447" style="color:#000" <?php echo ($year === '1447') ? 'selected' : ''; ?>>1447 Hijri</option>
              <option value="1448" style="color:#000" <?php echo ($year === '1448') ? 'selected' : ''; ?>>1448 Hijri</option>
              <option value="1449" style="color:#000" <?php echo ($year === '1449') ? 'selected' : ''; ?>>1449 Hijri</option>
              <option value="1450" style="color:#000" <?php echo ($year === '1450') ? 'selected' : ''; ?>>1450 Hijri</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Navigation Tabs -->
      <div class="hr-tabs">
        <button type="button" class="hr-tab-btn active" onclick="switchTab('directory')" id="tab-btn-directory">
          <i class="fa fa-list-alt"></i> Sanstha Directory
        </button>
        <button type="button" class="hr-tab-btn" onclick="switchTab('members')" id="tab-btn-members">
          <i class="fa fa-users"></i> Member Management
        </button>
      </div>

      <!-- ═══════════════════════════════════════════════════
           TAB 1: SANSTHA DIRECTORY & SETUP
      ═══════════════════════════════════════════════════ -->
      <div id="tab-directory-content">
        <div class="table-card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px">
            <div>
              <h3 style="font-size:0.95rem;font-weight:800;color:var(--text-1);margin:0">Registered Sansthas</h3>
              <p style="font-size:0.76rem;color:var(--text-2);margin:2px 0 0 0">View, create, edit, activate/deactivate, and delete Sansthas.</p>
            </div>
            <button type="button" class="btn-action-primary" onclick="openCreateSansthaModal()">
              <i class="fa fa-plus"></i> Create New Sanstha
            </button>
          </div>

          <!-- Filters -->
          <div class="search-bar-wrap mb-3">
            <div class="search-input-box">
              <i class="fa fa-search"></i>
              <input type="text" id="sanstha-search-q" placeholder="Search Sanstha by name or description..." onkeyup="filterSansthaDirectory()">
            </div>
            <select class="step-select" style="width:auto" id="sanstha-filter-status" onchange="filterSansthaDirectory()">
              <option value="All">Status: All</option>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>

          <div class="table-responsive-custom">
            <table class="sanstha-tbl">
              <thead>
                <tr>
                  <th style="width:50px">#</th>
                  <th>Sanstha Name</th>
                  <th>Description</th>
                  <th>Active Members</th>
                  <th>Status</th>
                  <th style="text-align:right">Actions</th>
                </tr>
              </thead>
              <tbody id="tbl-sanstha-list-body">
                <!-- Populated via JS -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════════════════
           TAB 2: DEDICATED MEMBER MANAGEMENT
      ═══════════════════════════════════════════════════ -->
      <div id="tab-members-content" style="display:none">
        
        <!-- Sanstha Selector Row -->
        <div class="table-card mb-3" style="padding:14px 18px">
          <div class="row align-items-center">
            <div class="col-md-6">
              <label class="step-label" style="margin-bottom:4px">Select Sanstha to Manage Members</label>
              <select class="step-select" id="sel-sanstha-members" onchange="onSansthaMemberSelectChange()">
                <!-- JS populated -->
              </select>
            </div>
            <div class="col-md-6 text-md-right mt-2 mt-md-0 d-flex align-items-center justify-content-md-end flex-wrap" style="gap:10px">
              <span style="font-size:0.8rem;color:var(--text-2);font-weight:700">
                Assigned Members: <span id="sanstha-active-count-badge" class="status-pill status-active" style="font-size:0.8rem">0</span>
              </span>
              <button type="button" class="btn-action-sec" onclick="copyPreviousYearMembers()" title="Copy members roster from previous year to active year">
                <i class="fa fa-copy"></i> Copy From Prev Year
              </button>
            </div>
          </div>
        </div>

        <!-- 2 Column Workspace Split -->
        <div class="assign-workspace">
          
          <!-- LEFT COLUMN: Available Jamaat Members Table -->
          <div class="table-card" style="margin-bottom:0">
            <h4 style="font-size:0.86rem;font-weight:800;margin:0 0 12px 0"><i class="fa fa-user-plus text-warning"></i> Add Members from Jamaat</h4>
            
            <div class="search-bar-wrap">
              <div class="search-input-box">
                <i class="fa fa-search"></i>
                <input type="text" id="avail-search-q" placeholder="Search by Name, ITS ID..." onkeyup="onAvailSearchInput()">
              </div>
              <select class="step-select" style="width:auto" id="avail-gender" onchange="loadAvailableMembers(1)">
                <option value="All">Gender: All</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
              <select class="step-select" style="width:auto" id="avail-status" onchange="loadAvailableMembers(1)">
                <option value="All">Status: All</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
              <label style="font-size:0.76rem;font-weight:700;cursor:pointer">
                <input type="checkbox" id="chk-avail-select-all" onchange="toggleAvailSelectAllPage(this.checked)"> Select All (Page)
              </label>
              <span style="font-size:0.74rem;color:var(--text-3)"><span id="avail-sel-count" style="font-weight:800;color:var(--gold)">0</span> selected</span>
            </div>

            <div class="table-responsive-custom">
              <table class="sanstha-tbl">
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
                <tbody id="tbl-avail-members-body">
                  <!-- JS populated -->
                </tbody>
              </table>
            </div>

            <div class="pagination-wrap">
              <span id="avail-page-info">Showing 0 of 0</span>
              <div id="avail-pagination-ctrls" style="display:flex;gap:4px"></div>
            </div>
            
            <div style="margin-top:14px;text-align:right">
              <button type="button" class="btn-action-primary" onclick="submitAddSansthaMembers()">
                <i class="fa fa-check-circle"></i> Add Selected Member(s) to Sanstha
              </button>
            </div>
          </div>

          <!-- RIGHT COLUMN: Assigned Members List -->
          <div class="table-card" style="margin-bottom:0">
            <h4 style="font-size:0.86rem;font-weight:800;margin:0 0 12px 0"><i class="fa fa-id-badge text-warning"></i> Assigned Sanstha Members Roster</h4>
            
            <div class="search-input-box mb-3">
              <i class="fa fa-search"></i>
              <input type="text" id="assigned-search-q" placeholder="Filter assigned members..." onkeyup="onAssignedSearchInput()">
            </div>

            <div id="assigned-members-roster-box" style="max-height:480px;overflow-y:auto">
              <!-- JS populated -->
            </div>

            <div class="pagination-wrap mt-3">
              <span id="assigned-page-info">Showing 0 of 0</span>
              <div id="assigned-pagination-ctrls" style="display:flex;gap:4px"></div>
            </div>
          </div>

        </div>

      </div>

    </main>
  </div>
</div>

<!-- Modal: Create / Edit Sanstha -->
<div class="modal-overlay" id="sanstha-modal">
  <div class="modal-box">
    <h3 style="font-size:1rem;font-weight:800;margin:0 0 16px 0;color:var(--text-1)" id="modal-sanstha-title">Create New Sanstha</h3>
    
    <form onsubmit="saveSansthaSubmit(event)">
      <input type="hidden" id="modal-sanstha-id" value="0">
      
      <div style="margin-bottom:12px">
        <label class="step-label">Sanstha Name <span style="color:#ef4444">*</span></label>
        <input type="text" class="step-select" id="modal-sanstha-name" placeholder="e.g. Al Jamaat Youth Sanstha, Saifee Burhani Sanstha..." required>
      </div>

      <div style="margin-bottom:12px">
        <label class="step-label">Description (Optional)</label>
        <textarea class="step-select" id="modal-sanstha-desc" rows="3" style="height:auto" placeholder="Enter brief details or purpose of this Sanstha..."></textarea>
      </div>

      <div style="margin-bottom:18px">
        <label class="step-label">Status</label>
        <select class="step-select" id="modal-sanstha-status">
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:8px">
        <button type="button" class="btn-action-sec" style="padding:8px 16px" onclick="closeSansthaModal()">Cancel</button>
        <button type="submit" class="btn-action-primary" style="padding:8px 18px">Save Sanstha</button>
      </div>
    </form>
  </div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';
var sansthaData = <?php echo json_encode($sanstha_list); ?>;

var state = {
  year: '<?php echo !empty($year) ? $year : "1448"; ?>',
  selectedSansthaId: (sansthaData && sansthaData.length > 0) ? sansthaData[0].id : null,
  availSelectedMembers: [],
  availMembersMap: {},
  availPage: 1,
  assignedPage: 1
};

document.addEventListener('DOMContentLoaded', function() {
  renderSansthaDirectoryTable(sansthaData);
  populateSansthaDropdown();

  $('#quickMenuSearch').on('keyup input', function() {
    var q = $(this).val().toLowerCase().trim();
    $('.menu-list li').each(function() {
      var txt = $(this).text().toLowerCase();
      $(this).toggle(txt.indexOf(q) !== -1);
    });
  });
});

function onYearChange() {
  state.year = document.getElementById('sel-hijri-year').value;
  reloadSansthaData();
}

function reloadSansthaData() {
  $.ajax({
    url: BASE_URL + 'admin/get_sansthas_ajax',
    type: 'GET',
    data: { year: state.year },
    dataType: 'json',
    success: function(res) {
      if (res && res.success && res.sanstha_list) {
        sansthaData = res.sanstha_list;
        filterSansthaDirectory();
        populateSansthaDropdown();
        if (state.selectedSansthaId) {
          loadAvailableMembers(1);
          loadAssignedSansthaMembers(1);
        }
      }
    }
  });
}

function switchTab(tab) {
  document.getElementById('tab-btn-directory').classList.toggle('active', tab === 'directory');
  document.getElementById('tab-btn-members').classList.toggle('active', tab === 'members');
  document.getElementById('tab-directory-content').style.display = (tab === 'directory') ? 'block' : 'none';
  document.getElementById('tab-members-content').style.display = (tab === 'members') ? 'block' : 'none';
}

/* ═══════════════════════════════════════════════════
   TAB 1: SANSTHA DIRECTORY & SETUP LOGIC
═══════════════════════════════════════════════════ */
function filterSansthaDirectory() {
  var q = document.getElementById('sanstha-search-q').value.toLowerCase().trim();
  var status = document.getElementById('sanstha-filter-status').value;

  var filtered = sansthaData.filter(function(s) {
    var matchQ = !q || (s.name && s.name.toLowerCase().indexOf(q) !== -1) || (s.description && s.description.toLowerCase().indexOf(q) !== -1);
    var matchStatus = (status === 'All') || (s.status === status);
    return matchQ && matchStatus;
  });

  renderSansthaDirectoryTable(filtered);
}

function renderSansthaDirectoryTable(list) {
  var tbody = document.getElementById('tbl-sanstha-list-body');
  tbody.innerHTML = '';

  if (!list || list.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-3)">No Sansthas found. Click "Create New Sanstha" to add one.</td></tr>';
    return;
  }

  list.forEach(function(s, idx) {
    var tr = document.createElement('tr');
    var stClass = (s.status === 'Active') ? 'status-active' : 'status-inactive';

    tr.innerHTML = '<td><strong>' + (idx + 1) + '</strong></td>' +
      '<td><strong style="color:var(--text-1);font-size:0.86rem">' + escapeHtml(s.name) + '</strong></td>' +
      '<td><span class="text-muted" style="font-size:0.76rem">' + (escapeHtml(s.description) || '—') + '</span></td>' +
      '<td><span class="status-pill status-active">' + (s.members_count || 0) + ' Members (' + state.year + ' H)</span></td>' +
      '<td><span class="status-pill ' + stClass + '">' + s.status + '</span></td>' +
      '<td style="text-align:right">' +
        '<button type="button" class="btn-action-primary mr-1" style="padding:4px 8px;font-size:0.72rem" onclick="manageSansthaMembers(' + s.id + ')"><i class="fa fa-users"></i> Members</button>' +
        '<button type="button" class="btn-action-sec mr-1" onclick="openEditSansthaModal(' + s.id + ', \'' + escapeHtml(s.name) + '\', \'' + escapeHtml(s.description || '') + '\', \'' + s.status + '\')"><i class="fa fa-pencil"></i> Edit</button>' +
        '<button type="button" class="btn-action-sec mr-1" onclick="toggleSansthaStatus(' + s.id + ')"><i class="fa fa-power-off"></i> Toggle</button>' +
        '<button type="button" class="btn-action-danger" onclick="deleteSanstha(' + s.id + ')"><i class="fa fa-trash"></i> Delete</button>' +
      '</td>';

    tbody.appendChild(tr);
  });
}

function openCreateSansthaModal() {
  document.getElementById('modal-sanstha-id').value = '0';
  document.getElementById('modal-sanstha-name').value = '';
  document.getElementById('modal-sanstha-desc').value = '';
  document.getElementById('modal-sanstha-status').value = 'Active';
  document.getElementById('modal-sanstha-title').textContent = 'Create New Sanstha';
  document.getElementById('sanstha-modal').style.display = 'flex';
}

function openEditSansthaModal(id, name, desc, status) {
  document.getElementById('modal-sanstha-id').value = id;
  document.getElementById('modal-sanstha-name').value = name;
  document.getElementById('modal-sanstha-desc').value = desc;
  document.getElementById('modal-sanstha-status').value = status;
  document.getElementById('modal-sanstha-title').textContent = 'Edit Sanstha';
  document.getElementById('sanstha-modal').style.display = 'flex';
}

function closeSansthaModal() {
  document.getElementById('sanstha-modal').style.display = 'none';
}

function saveSansthaSubmit(e) {
  e.preventDefault();
  var id = document.getElementById('modal-sanstha-id').value;
  var name = document.getElementById('modal-sanstha-name').value;
  var desc = document.getElementById('modal-sanstha-desc').value;
  var status = document.getElementById('modal-sanstha-status').value;

  $.ajax({
    url: BASE_URL + 'admin/save_sanstha_ajax',
    type: 'POST',
    data: { id: id, name: name, description: desc, status: status },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        closeSansthaModal();
        alert(res.message);
        reloadSansthaData();
      } else {
        alert((res && res.message) ? res.message : 'Error saving Sanstha.');
      }
    }
  });
}

function toggleSansthaStatus(id) {
  $.ajax({
    url: BASE_URL + 'admin/toggle_sanstha_status_ajax',
    type: 'POST',
    data: { id: id },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        alert(res.message);
        reloadSansthaData();
      } else {
        alert('Failed to toggle status.');
      }
    }
  });
}

function deleteSanstha(id) {
  if (!confirm('Are you sure you want to delete this Sanstha?')) return;
  $.ajax({
    url: BASE_URL + 'admin/delete_sanstha_ajax',
    type: 'POST',
    data: { id: id },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        alert(res.message);
        reloadSansthaData();
      } else {
        alert((res && res.message) ? res.message : 'Cannot delete Sanstha.');
      }
    }
  });
}

/* ═══════════════════════════════════════════════════
   TAB 2: MEMBER MANAGEMENT LOGIC
═══════════════════════════════════════════════════ */
function manageSansthaMembers(sansthaId) {
  state.selectedSansthaId = sansthaId;
  document.getElementById('sel-sanstha-members').value = sansthaId;
  switchTab('members');
  onSansthaMemberSelectChange();
}

function populateSansthaDropdown() {
  var select = document.getElementById('sel-sanstha-members');
  select.innerHTML = '';

  if (!sansthaData || sansthaData.length === 0) {
    var opt = document.createElement('option');
    opt.value = '';
    opt.textContent = 'No Sansthas Created';
    select.appendChild(opt);
    select.disabled = true;
    return;
  }

  sansthaData.forEach(function(s) {
    var opt = document.createElement('option');
    opt.value = s.id;
    opt.textContent = s.name + ' (' + (s.members_count || 0) + ' Members - ' + state.year + ' H)';
    select.appendChild(opt);
  });

  if (!state.selectedSansthaId) {
    state.selectedSansthaId = sansthaData[0].id;
  }
  select.value = state.selectedSansthaId;
}

function onSansthaMemberSelectChange() {
  state.selectedSansthaId = parseInt(document.getElementById('sel-sanstha-members').value) || null;
  state.availSelectedMembers = [];
  document.getElementById('chk-avail-select-all').checked = false;
  document.getElementById('avail-sel-count').textContent = '0';
  
  if (state.selectedSansthaId) {
    loadAvailableMembers(1);
    loadAssignedSansthaMembers(1);
  }
}

var availSearchTimer = null;
function onAvailSearchInput() {
  clearTimeout(availSearchTimer);
  availSearchTimer = setTimeout(function() {
    loadAvailableMembers(1);
  }, 300);
}

function loadAvailableMembers(page) {
  if (!state.selectedSansthaId) return;
  state.availPage = page || 1;

  var q = document.getElementById('avail-search-q').value;
  var gender = document.getElementById('avail-gender').value;
  var status = document.getElementById('avail-status').value;
  var tbody = document.getElementById('tbl-avail-members-body');

  tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:16px"><i class="fa fa-spinner fa-spin"></i> Loading members...</td></tr>';

  $.ajax({
    url: BASE_URL + 'admin/get_available_members_for_sanstha_ajax',
    type: 'GET',
    data: {
      sanstha_id: state.selectedSansthaId,
      q: q,
      gender: gender,
      status: status,
      year: state.year,
      page: state.availPage,
      limit: 10
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        renderAvailableMembersTable(res.members, res.page, res.total, res.pages);
      } else {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:16px;color:var(--text-3)">Failed to load members.</td></tr>';
      }
    }
  });
}

function renderAvailableMembersTable(members, page, total, pages) {
  var tbody = document.getElementById('tbl-avail-members-body');
  tbody.innerHTML = '';
  state.availMembersMap = {};

  if (!members || members.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:16px;color:var(--text-3)">No members found matching filters.</td></tr>';
    return;
  }

  members.forEach(function(m) {
    state.availMembersMap[m.ITS_ID] = m;
    var isSel = state.availSelectedMembers.includes(m.ITS_ID);
    var tr = document.createElement('tr');

    var chkBox = m.is_assigned ?
      '<span class="status-pill status-active" style="font-size:0.6rem">Assigned (' + state.year + ')</span>' :
      '<input type="checkbox" class="avail-chk-item" data-its="' + m.ITS_ID + '" ' + (isSel ? 'checked' : '') + ' onchange="onAvailMemberSelectToggle(\'' + m.ITS_ID + '\', this.checked)">';

    tr.innerHTML = '<td>' + chkBox + '</td>' +
      '<td><strong>' + m.ITS_ID + '</strong></td>' +
      '<td>' + escapeHtml(m.Full_Name || '—') + '</td>' +
      '<td>' + (m.Mobile_No || '—') + '</td>' +
      '<td>' + (m.Gender || '—') + '</td>' +
      '<td><span class="status-pill ' + (m.status_label === 'Active' ? 'status-active' : 'status-inactive') + '">' + m.status_label + '</span></td>';

    tbody.appendChild(tr);
  });

  document.getElementById('avail-page-info').textContent = 'Showing ' + ((page-1)*10 + 1) + ' to ' + Math.min(total, page*10) + ' of ' + total;

  var pCtrl = document.getElementById('avail-pagination-ctrls');
  pCtrl.innerHTML = '';
  for (var i = 1; i <= Math.min(pages, 10); i++) {
    var btn = document.createElement('button');
    btn.className = 'page-btn ' + (i === page ? 'active' : '');
    btn.textContent = i;
    btn.onclick = (function(p) { return function() { loadAvailableMembers(p); }; })(i);
    pCtrl.appendChild(btn);
  }
}

function onAvailMemberSelectToggle(its, isChecked) {
  if (isChecked) {
    if (!state.availSelectedMembers.includes(its)) {
      state.availSelectedMembers.push(its);
    }
  } else {
    state.availSelectedMembers = state.availSelectedMembers.filter(function(id) { return id !== its; });
  }
  document.getElementById('avail-sel-count').textContent = state.availSelectedMembers.length;
}

function toggleAvailSelectAllPage(isChecked) {
  Object.keys(state.availMembersMap).forEach(function(its) {
    var m = state.availMembersMap[its];
    if (!m.is_assigned) {
      if (isChecked && !state.availSelectedMembers.includes(its)) {
        state.availSelectedMembers.push(its);
      } else if (!isChecked) {
        state.availSelectedMembers = state.availSelectedMembers.filter(function(id) { return id !== its; });
      }
    }
  });
  loadAvailableMembers(state.availPage);
  document.getElementById('avail-sel-count').textContent = state.availSelectedMembers.length;
}

function submitAddSansthaMembers() {
  if (!state.selectedSansthaId) {
    alert('Please select a Sanstha.');
    return;
  }
  if (state.availSelectedMembers.length === 0) {
    alert('Please select at least one member to add.');
    return;
  }

  $.ajax({
    url: BASE_URL + 'admin/add_sanstha_members_ajax',
    type: 'POST',
    data: {
      sanstha_id: state.selectedSansthaId,
      user_its: state.availSelectedMembers,
      year: state.year
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        alert(res.message);
        state.availSelectedMembers = [];
        document.getElementById('chk-avail-select-all').checked = false;
        document.getElementById('avail-sel-count').textContent = '0';
        reloadSansthaData();
      } else {
        alert((res && res.message) ? res.message : 'Error adding members.');
      }
    }
  });
}

var assignedSearchTimer = null;
function onAssignedSearchInput() {
  clearTimeout(assignedSearchTimer);
  assignedSearchTimer = setTimeout(function() {
    loadAssignedSansthaMembers(1);
  }, 300);
}

function loadAssignedSansthaMembers(page) {
  if (!state.selectedSansthaId) return;
  state.assignedPage = page || 1;

  var q = document.getElementById('assigned-search-q').value;
  var container = document.getElementById('assigned-members-roster-box');

  container.innerHTML = '<div style="text-align:center;padding:16px"><i class="fa fa-spinner fa-spin"></i> Loading assigned roster...</div>';

  $.ajax({
    url: BASE_URL + 'admin/get_sanstha_members_ajax',
    type: 'GET',
    data: {
      sanstha_id: state.selectedSansthaId,
      q: q,
      year: state.year,
      page: state.assignedPage,
      limit: 10
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        document.getElementById('sanstha-active-count-badge').textContent = res.total || 0;
        renderAssignedMembersList(res.members, res.page, res.total, res.pages);
      } else {
        container.innerHTML = '<div style="text-align:center;padding:16px;color:var(--text-3)">Failed to load assigned members.</div>';
      }
    }
  });
}

function renderAssignedMembersList(members, page, total, pages) {
  var container = document.getElementById('assigned-members-roster-box');
  container.innerHTML = '';

  if (!members || members.length === 0) {
    container.innerHTML = '<div style="text-align:center;padding:24px;color:var(--text-3)">No members assigned to this Sanstha for ' + state.year + ' Hijri yet.</div>';
    document.getElementById('assigned-page-info').textContent = 'Showing 0 of 0';
    document.getElementById('assigned-pagination-ctrls').innerHTML = '';
    return;
  }

  members.forEach(function(m) {
    var div = document.createElement('div');
    div.className = 'assigned-user-card';
    div.innerHTML = '<div class="user-avatar"><i class="fa fa-user"></i></div>' +
      '<div style="flex:1"><strong>' + escapeHtml(m.Full_Name || m.ITS_ID) + '</strong><br><small class="text-muted">ITS: ' + m.ITS_ID + (m.Gender ? (' · ' + m.Gender) : '') + ' · <span class="badge badge-warning" style="font-size:0.6rem">' + (m.year || state.year) + ' H</span></small></div>' +
      '<button type="button" class="btn-action-danger" style="padding:3px 8px;font-size:0.7rem" title="Remove Member" onclick="removeSansthaMember(\'' + m.ITS_ID + '\')"><i class="fa fa-times"></i> Remove</button>';
    container.appendChild(div);
  });

  document.getElementById('assigned-page-info').textContent = 'Showing ' + ((page-1)*10 + 1) + ' to ' + Math.min(total, page*10) + ' of ' + total;

  var pCtrl = document.getElementById('assigned-pagination-ctrls');
  pCtrl.innerHTML = '';
  for (var i = 1; i <= Math.min(pages, 10); i++) {
    var btn = document.createElement('button');
    btn.className = 'page-btn ' + (i === page ? 'active' : '');
    btn.textContent = i;
    btn.onclick = (function(p) { return function() { loadAssignedSansthaMembers(p); }; })(i);
    pCtrl.appendChild(btn);
  }
}

function removeSansthaMember(its) {
  if (!confirm('Are you sure you want to remove this member from the Sanstha for ' + state.year + ' Hijri?')) return;
  $.ajax({
    url: BASE_URL + 'admin/remove_sanstha_member_ajax',
    type: 'POST',
    data: {
      sanstha_id: state.selectedSansthaId,
      user_its: its,
      year: state.year
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        reloadSansthaData();
      } else {
        alert('Failed to remove member.');
      }
    }
  });
}

function copyPreviousYearMembers() {
  if (!state.selectedSansthaId) {
    alert('Please select a Sanstha.');
    return;
  }
  var fromYear = String(parseInt(state.year) - 1);
  if (!confirm('Copy member roster from ' + fromYear + ' Hijri to ' + state.year + ' Hijri for this Sanstha?')) return;

  $.ajax({
    url: BASE_URL + 'admin/copy_sanstha_members_ajax',
    type: 'POST',
    data: {
      sanstha_id: state.selectedSansthaId,
      from_year: fromYear,
      to_year: state.year
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success) {
        alert(res.message);
        reloadSansthaData();
      } else {
        alert((res && res.message) ? res.message : 'Error copying members.');
      }
    }
  });
}

function escapeHtml(str) {
  return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}
</script>
