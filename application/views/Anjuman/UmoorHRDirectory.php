<?php
// Anjuman/UmoorHRDirectory.php — Read-Only 12 Umoor HR Directory for Jamaat matching Dashboard Design
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
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
  --radius:      14px;
  --shadow-sm:   0 1px 3px rgba(0,0,0,.06);
  --shadow:      0 4px 16px rgba(0,0,0,.07);
}

#anjApp, #anjApp *, #anjApp *::before, #anjApp *::after { box-sizing: border-box; }
#anjApp { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); background: var(--bg); min-height: 100vh; }
#anjApp a { color: inherit; }

#anjApp .anj-root { display: flex; min-height: 100vh; padding-top: 57px; }
#anjApp .anj-sidebar {
  width: 270px; flex-shrink: 0; position: sticky; top: 57px; height: calc(100vh - 57px);
  overflow-y: auto; background: var(--surface); border-right: 1px solid var(--border); padding: 16px 12px 32px;
  scrollbar-width: thin; scrollbar-color: var(--border) transparent; z-index: 100;
}
#anjApp .anj-content { flex: 1; min-width: 0; padding: 22px 22px 60px; }

#anjApp .anj-sidebar .sb-brand { font-weight: 800; font-size: .84rem; color: var(--text-2); margin-bottom: 12px; padding: 0 4px; display: flex; align-items: center; gap: 8px; }
#anjApp .anj-sidebar .sb-brand .sb-ico { width: 26px; height: 26px; border-radius: 7px; background: var(--gold-muted); color: var(--gold); display: inline-flex; align-items: center; justify-content: center; font-size: .78rem; }

#anjApp .sb-search { display: flex; align-items: center; gap: 8px; background: var(--bg); border: 1.5px solid var(--border); border-radius: 8px; padding: 7px 10px; margin-bottom: 12px; transition: border-color .15s; }
#anjApp .sb-search:focus-within { border-color: var(--gold); }
#anjApp .sb-search i { color: var(--text-3); font-size: .8rem; }
#anjApp .sb-search input { border: none; background: transparent; outline: none; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .81rem; color: var(--text-1); width: 100%; }
#anjApp .sb-search input::placeholder { color: var(--text-3); }
#anjApp .menu-search-clear { border: none; background: none; cursor: pointer; color: var(--text-3); font-size: .78rem; padding: 0; display: none; }

#anjApp .menu-section {
  font-size: .58rem; font-weight: 800; letter-spacing: 1.1px; text-transform: uppercase;
  color: var(--text-3); padding: 12px 6px 4px; display: flex; align-items: center; justify-content: space-between;
}
#anjApp .menu-list { list-style: none; margin: 0; padding: 0; }
#anjApp .menu-list li + li { margin-top: 2px; }
#anjApp .menu-item {
  display: flex; align-items: center; gap: 9px; padding: 7px 9px; border-radius: 7px;
  color: var(--text-2); font-size: .82rem; font-weight: 500; text-decoration: none; transition: background .14s, color .14s;
}
#anjApp .menu-item:hover, #anjApp .menu-item.active { background: var(--gold-muted); color: var(--gold); text-decoration: none; }
#anjApp .menu-icon {
  width: 27px; height: 27px; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center;
  background: var(--surface-2); color: var(--text-3); font-size: .76rem; flex-shrink: 0; transition: background .14s, color .14s;
}

/* Dashboard Hero Header Box */
.anj-hero {
  background: linear-gradient(135deg, #b8860b 0%, #d97706 100%);
  color: #fff;
  border-radius: var(--radius);
  padding: 22px 26px;
  margin-bottom: 22px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 8px 24px -4px rgba(184,134,11,0.3);
  flex-wrap: wrap;
  gap: 16px;
}
.badge-tag {
  background: rgba(255,255,255,0.22);
  color: #fff;
  font-size: .68rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: .8px;
  padding: 3px 10px;
  border-radius: 20px;
  display: inline-block;
  margin-bottom: 6px;
}
.anj-title {
  font-family: 'Literata', serif;
  font-size: 1.55rem;
  font-weight: 600;
  margin: 0;
  color: #fff;
}
.year-select-box {
  background: #fff;
  color: var(--text-1);
  border: none;
  padding: 8px 14px;
  border-radius: 8px;
  font-size: 0.82rem;
  font-weight: 700;
  cursor: pointer;
  box-shadow: var(--shadow-sm);
}

.dir-tabs-bar {
  display: flex;
  gap: 8px;
  margin-bottom: 20px;
  border-bottom: 2px solid var(--border);
  padding-bottom: 2px;
}
.tab-btn {
  background: transparent;
  border: none;
  padding: 10px 20px;
  font-size: 0.88rem;
  font-weight: 700;
  color: var(--text-2);
  cursor: pointer;
  border-bottom: 3px solid transparent;
  transition: all 0.2s;
}
.tab-btn.active {
  color: var(--gold);
  border-bottom-color: var(--gold);
}

.table-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 20px;
  box-shadow: var(--shadow-sm);
}

.table-custom {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}
.table-custom th {
  background: var(--surface-2);
  color: var(--text-2);
  font-size: 0.74rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 12px 14px;
  border-bottom: 1px solid var(--border);
}
.table-custom td {
  padding: 12px 14px;
  font-size: 0.83rem;
  border-bottom: 1px solid var(--border-light);
  vertical-align: middle;
}
.table-custom tr:last-child td { border-bottom: none; }

.umoor-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 16px;
}
.u-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 16px;
  transition: transform 0.2s, box-shadow 0.2s;
}
.u-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
.u-num { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: var(--gold); }
.u-name { font-size: 0.95rem; font-weight: 800; color: var(--text-1); margin: 2px 0 10px 0; }

.level-badge {
  background: var(--gold-muted);
  color: var(--gold);
  padding: 3px 8px;
  border-radius: 6px;
  font-size: 0.7rem;
  font-weight: 800;
}
.status-pill {
  display: inline-block;
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 0.7rem;
  font-weight: 800;
}
.status-active { background: #dcfce7; color: #15803d; }
.status-inactive { background: #fee2e2; color: #b91c1c; }

.level2-split {
  display: grid;
  grid-template-columns: 250px 1fr;
  gap: 20px;
}
@media (max-width: 992px) {
  .level2-split { grid-template-columns: 1fr; }
}

.u-select-item {
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 0.82rem;
  font-weight: 700;
  color: var(--text-2);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 4px;
  transition: all 0.15s;
}
.u-select-item.active, .u-select-item:hover {
  background: var(--gold-muted);
  color: var(--gold);
}

.roster-modal-overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(3px);
  z-index: 9999; display: none; align-items: center; justify-content: center; padding: 16px;
}
.roster-modal-box {
  background: #fff; border-radius: 18px; width: 100%; max-width: 780px; max-height: 85vh;
  display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15);
}
.roster-modal-head {
  padding: 16px 20px; background: linear-gradient(135deg, #b8860b 0%, #d97706 100%); color: #fff;
  display: flex; align-items: center; justify-content: space-between;
}
.roster-modal-body { padding: 20px; overflow-y: auto; flex: 1; }
</style>

<div id="anjApp">
  <div class="anj-root">

    <!-- ══ FULL JAMAAT SIDEBAR ══ -->
    <aside class="anj-sidebar" id="anjSidebar">
      <div class="sb-brand mb-3">
        <span class="sb-ico"><i class="fa fa-tachometer"></i></span>Quick Menu
      </div>
      
      <div class="sb-search" role="search">
        <i class="fa fa-search"></i>
        <input id="quickMenuSearch" type="text" placeholder="Search menu..." autocomplete="off">
        <button type="button" id="quickMenuClear" class="menu-search-clear">&times;</button>
      </div>

      <div class="menu-section">Raza &amp; Umoor</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('anjuman/EventRazaRequest?event_type=1') ?>"><span class="menu-icon"><i class="fa fa-handshake-o"></i></span><span class="menu-label">Miqaat Raza Request</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/EventRazaRequest?event_type=2') ?>"><span class="menu-icon"><i class="fa fa-handshake-o"></i></span><span class="menu-label">Kaaraj Raza Request</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/UmoorRazaRequest') ?>"><span class="menu-icon"><i class="fa fa-list"></i></span><span class="menu-label">12 Umoor Raza Request</span></a></li>
        <li><a class="menu-item active" href="<?php echo base_url('anjuman/umoor_hr') ?>"><span class="menu-icon"><i class="fa fa-sitemap"></i></span><span class="menu-label">12 Umoor HR</span></a></li>
      </ul>

      <div class="menu-section">Activity</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('anjuman/asharaohbat') ?>"><span class="menu-icon"><i class="fa fa-calendar"></i></span><span class="menu-label">Ashara Ohbat</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/ashara_attendance') ?>"><span class="menu-icon"><i class="fa fa-user-check"></i></span><span class="menu-label">Ashara Attendance</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/fmbcalendar?from=anjuman') ?>"><span class="menu-icon"><i class="fa fa-calendar"></i></span><span class="menu-label">FMB Calendar</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/thaali_signup_dashboard?from=anjuman') ?>"><span class="menu-icon"><i class="fa-solid fa-list-check"></i></span><span class="menu-label">Thaali Sign-up Dashboard</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/fmbthaalimenu?from=anjuman') ?>"><span class="menu-icon"><i class="fa fa-cutlery"></i></span><span class="menu-label">Add FMB Menu</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/managemiqaat?from=anjuman') ?>"><span class="menu-icon"><i class="fa fa-plus-circle"></i></span><span class="menu-label">Create Miqaat</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/rsvp_list?from=anjuman') ?>"><span class="menu-icon"><i class="fa fa-check-square-o"></i></span><span class="menu-label">RSVP Report</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/miqaatattendance?from=anjuman') ?>"><span class="menu-icon"><i class="fa fa-users"></i></span><span class="menu-label">Miqaat Attendance Report</span></a></li>
      </ul>

      <div class="menu-section">Finance</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('anjuman/fmbthaali') ?>"><span class="menu-icon"><i class="fa fa-money"></i></span><span class="menu-label">FMB Thaali Funds</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/fmbniyaz') ?>"><span class="menu-icon"><i class="fa fa-money"></i></span><span class="menu-label">Miqaat Niyaz Funds</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/sabeeltakhmeendashboard') ?>"><span class="menu-icon"><i class="fa fa-credit-card"></i></span><span class="menu-label">Sabeel Funds</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/qardanhasana') ?>"><span class="menu-icon"><i class="fa fa-leaf"></i></span><span class="menu-label">Qardan Hasana</span></a></li>
        <li><a class="menu-item" href="<?= base_url('anjuman/corpusfunds_receive') ?>"><span class="menu-icon"><i class="fa fa-university"></i></span><span class="menu-label">Corpus Funds</span></a></li>
        <li><a class="menu-item" href="<?= base_url('anjuman/ekramfunds_receive') ?>"><span class="menu-icon"><i class="fa fa-gift"></i></span><span class="menu-label">Ekram Fund Module</span></a></li>
        <li><a class="menu-item" href="<?= base_url('anjuman/payments_report') ?>"><span class="menu-icon"><i class="fa fa-file-text-o"></i></span><span class="menu-label">Payments Report</span></a></li>
        <li><a class="menu-item" href="<?= base_url('anjuman/financials') ?>"><span class="menu-icon"><i class="fa fa-file-text-o"></i></span><span class="menu-label">Individual Financial Details</span></a></li>
        <li><a class="menu-item" href="<?= base_url('anjuman/expense') ?>"><span class="menu-icon"><i class="fa fa-calculator"></i></span><span class="menu-label">Expense &amp; Budget Module</span></a></li>
      </ul>
    </aside>

    <!-- ══ MAIN CONTENT AREA ══ -->
    <main class="anj-content">

      <!-- Hero Header matching Jamaat Dashboard design -->
      <div class="anj-hero">
        <div>
          <span class="badge-tag"><i class="fa fa-sitemap mr-1"></i> 12 Umoor HR Directory</span>
          <h1 class="anj-title">12 Umoor HR Directory</h1>
          <div style="font-size:0.8rem;color:rgba(255,255,255,0.85);margin-top:4px">Read-only Jamaat view of 12 Umoor Level 1 Coordinators, Level 2 Teams &amp; Team Members.</div>
        </div>
        <div>
          <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;margin-right:6px"><i class="fa fa-calendar"></i> Hijri Year:</span>
          <select class="year-select-box" onchange="changeYear(this.value)">
            <option value="1446" <?php echo ($active_year === '1446') ? 'selected' : ''; ?>>1446 Hijri</option>
            <option value="1447" <?php echo ($active_year === '1447') ? 'selected' : ''; ?>>1447 Hijri</option>
            <option value="1448" <?php echo ($active_year === '1448') ? 'selected' : ''; ?>>1448 Hijri</option>
            <option value="1449" <?php echo ($active_year === '1449') ? 'selected' : ''; ?>>1449 Hijri</option>
            <option value="1450" <?php echo ($active_year === '1450') ? 'selected' : ''; ?>>1450 Hijri</option>
          </select>
        </div>
      </div>

      <!-- Navigation Tabs -->
      <div class="dir-tabs-bar">
        <button type="button" class="tab-btn active" id="tab-btn-hierarchy" onclick="switchTab('hierarchy')"><i class="fa fa-sitemap mr-1"></i> Team Hierarchy (Level 1 &amp; Level 2)</button>
        <button type="button" class="tab-btn" id="tab-btn-roster" onclick="switchTab('roster')"><i class="fa fa-users mr-1"></i> Assigned Member Roster</button>
      </div>

      <!-- TAB 1: TEAM HIERARCHY -->
      <div id="tab-hierarchy-content">
        <div style="margin-bottom:24px">
          <h3 style="font-size:0.82rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--text-3);margin-bottom:12px">
            Level 1 – 12 Umoor Coordinators (Male &amp; Al Aqeeq)
          </h3>
          <div class="umoor-grid" id="level1-grid">
            <!-- JS populated -->
          </div>
        </div>

        <div>
          <h3 style="font-size:0.82rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--text-3);margin-bottom:12px">
            Level 2 – Sub-Committees &amp; Teams under Selected Umoor
          </h3>
          <div class="level2-split">
            <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:12px">
              <div style="font-size:0.75rem;font-weight:800;color:var(--text-3);text-transform:uppercase;margin-bottom:8px">Select Umoor</div>
              <div id="l2-umoor-select-list">
                <!-- JS populated -->
              </div>
            </div>

            <div class="table-card">
              <h4 style="font-size:0.9rem;font-weight:800;margin:0 0 14px 0" id="l2-selected-umoor-heading">Teams under Selected Umoor</h4>
              <div class="table-responsive">
                <table class="table-custom">
                  <thead>
                    <tr>
                      <th style="width:40px">#</th>
                      <th>Team Name</th>
                      <th>Team Lead</th>
                      <th>Members Count</th>
                      <th style="text-align:right">Action</th>
                    </tr>
                  </thead>
                  <tbody id="l2-teams-tbl-body">
                    <!-- JS populated -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB 2: ASSIGNED MEMBER ROSTER -->
      <div id="tab-roster-content" style="display:none">
        <div class="table-card">
          <div style="display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap">
            <div style="flex:1;min-width:200px;display:flex;align-items:center;background:var(--surface-2);border:1px solid var(--border);border-radius:8px;padding:0 12px">
              <i class="fa fa-search" style="color:var(--text-3);font-size:0.85rem;margin-right:8px"></i>
              <input type="text" id="roster-search-q" placeholder="Search by Name or ITS ID..." style="border:none;background:transparent;outline:none;font-size:0.82rem;width:100%" onkeyup="onRosterSearchInput()">
            </div>
            <select id="roster-filter-umoor" class="form-control" style="width:auto;font-size:0.82rem" onchange="onRosterFilterChange()">
              <option value="">All 12 Umoor</option>
              <?php foreach ($umoor_list as $uid => $uname): ?>
                <option value="<?php echo $uid; ?>"><?php echo $uid . '. ' . htmlspecialchars($uname); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="table-responsive">
            <table class="table-custom">
              <thead>
                <tr>
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
              <tbody id="tbl-roster-body">
                <!-- JS populated -->
              </tbody>
            </table>
          </div>

          <div style="display:flex;align-items:center;justify-content:space-between;margin-top:14px">
            <span id="roster-page-count-info" style="font-size:0.75rem;color:var(--text-2)">Showing members</span>
            <div id="roster-pagination-controls" style="display:flex;gap:4px"></div>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<!-- Team Roster Popup Modal -->
<div class="roster-modal-overlay" id="team-roster-modal">
  <div class="roster-modal-box">
    <div class="roster-modal-head">
      <div>
        <h5 style="font-size:1rem;font-weight:800;margin:0" id="team-roster-modal-title">Team Roster</h5>
        <span style="font-size:0.75rem;opacity:0.9" id="team-roster-modal-sub">12 Umoor HR &bull; <?php echo htmlspecialchars($active_year); ?> Hijri</span>
      </div>
      <button type="button" onclick="closeTeamRosterModal()" style="border:none;background:rgba(255,255,255,0.2);color:#fff;border-radius:6px;width:28px;height:28px;cursor:pointer;font-size:1.1rem">&times;</button>
    </div>
    <div class="roster-modal-body">
      <div class="table-responsive">
        <table class="table-custom">
          <thead>
            <tr>
              <th>ITS ID</th>
              <th>Member Name</th>
              <th>Assigned Role</th>
              <th>Mobile</th>
              <th>Gender</th>
            </tr>
          </thead>
          <tbody id="tbl-team-roster-modal-body">
            <!-- JS populated -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';
var activeYear = '<?php echo $active_year; ?>';
var umoorList = <?php echo json_encode($umoor_list); ?>;
var hierarchyData = <?php echo json_encode($hierarchy); ?>;
var selectedUmoorId = 1;
var rosterPage = 1;

document.addEventListener('DOMContentLoaded', function() {
  renderHierarchyTab();
  loadRoster(1);

  $('#quickMenuSearch').on('keyup input', function() {
    var q = $(this).val().toLowerCase().trim();
    $('.menu-list li').each(function() {
      var txt = $(this).text().toLowerCase();
      $(this).toggle(txt.indexOf(q) !== -1);
    });
  });
});

function changeYear(yr) {
  window.location.href = BASE_URL + 'anjuman/umoor_hr?year=' + yr;
}

function switchTab(tab) {
  document.getElementById('tab-btn-hierarchy').classList.toggle('active', tab === 'hierarchy');
  document.getElementById('tab-btn-roster').classList.toggle('active', tab === 'roster');
  document.getElementById('tab-hierarchy-content').style.display = (tab === 'hierarchy') ? 'block' : 'none';
  document.getElementById('tab-roster-content').style.display = (tab === 'roster') ? 'block' : 'none';
}

function renderHierarchyTab() {
  renderLevel1Grid();
  renderLevel2Split();
}

function renderLevel1Grid() {
  var container = document.getElementById('level1-grid');
  container.innerHTML = '';

  hierarchyData.forEach(function(item) {
    var div = document.createElement('div');
    div.className = 'u-card';

    var mCoordHtml = item.male_coordinator ? 
      '<div style="font-size:0.72rem;color:#b8860b;font-weight:700;margin-bottom:4px"><i class="fa fa-user-circle"></i> <strong>Male:</strong> ' + escapeHtml(item.male_coordinator.name) + '</div>' :
      '<div style="font-size:0.7rem;color:var(--text-3);margin-bottom:4px"><i class="fa fa-user-circle-o"></i> Male: <em>Unassigned</em></div>';
      
    var fCoordHtml = item.female_coordinator ? 
      '<div style="font-size:0.72rem;color:#059669;font-weight:700"><i class="fa fa-user-circle"></i> <strong>Al Aqeeq:</strong> ' + escapeHtml(item.female_coordinator.name) + '</div>' :
      '<div style="font-size:0.7rem;color:var(--text-3)"><i class="fa fa-user-circle-o"></i> Al Aqeeq: <em>Unassigned</em></div>';

    div.innerHTML = '<div class="u-num">' + item.umoor_id + '</div>' +
      '<div class="u-name">' + escapeHtml(item.umoor_name) + '</div>' +
      '<div style="border-top:1px dashed var(--border);padding-top:6px;margin-top:6px">' +
        mCoordHtml + fCoordHtml +
      '</div>';

    container.appendChild(div);
  });
}

function renderLevel2Split() {
  var uList = document.getElementById('l2-umoor-select-list');
  uList.innerHTML = '';

  hierarchyData.forEach(function(item) {
    var div = document.createElement('div');
    div.className = 'u-select-item ' + (item.umoor_id === selectedUmoorId ? 'active' : '');
    div.innerHTML = '<span>' + item.umoor_id + '. ' + escapeHtml(item.umoor_name) + '</span> <span class="status-pill status-active">' + (item.teams ? item.teams.length : 0) + '</span>';
    div.onclick = function() {
      selectedUmoorId = item.umoor_id;
      renderLevel2Split();
    };
    uList.appendChild(div);
  });

  var curItem = hierarchyData.find(function(h) { return h.umoor_id === selectedUmoorId; });
  document.getElementById('l2-selected-umoor-heading').textContent = 'Teams under ' + (curItem ? curItem.umoor_name : 'Selected Umoor');

  var tbody = document.getElementById('l2-teams-tbl-body');
  tbody.innerHTML = '';

  if (!curItem || !curItem.teams || curItem.teams.length === 0) {
    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:20px;color:var(--text-3)">No teams created under this Umoor yet.</td></tr>';
    return;
  }

  curItem.teams.forEach(function(team, idx) {
    var tr = document.createElement('tr');
    tr.innerHTML = '<td>' + (idx + 1) + '</td>' +
      '<td><strong>' + escapeHtml(team.name) + '</strong></td>' +
      '<td>' + (team.team_lead_name ? ('<strong>' + escapeHtml(team.team_lead_name) + '</strong><br><small class="text-muted">' + team.team_lead_its + '</small>') : '<span class="text-muted">Unassigned</span>') + '</td>' +
      '<td><span class="status-pill status-active">' + (team.members_count || 0) + ' Members</span></td>' +
      '<td style="text-align:right"><button type="button" class="btn btn-xs" style="background:var(--gold-muted);color:var(--gold);font-weight:700;border:none;border-radius:6px;padding:4px 10px;" onclick="viewTeamRoster(' + team.id + ', \'' + escapeHtml(team.name) + '\')"><i class="fa fa-eye"></i> View Roster</button></td>';
    tbody.appendChild(tr);
  });
}

function viewTeamRoster(teamId, teamName) {
  document.getElementById('team-roster-modal-title').textContent = teamName + ' Roster (' + activeYear + ' H)';
  document.getElementById('team-roster-modal-sub').textContent = 'Showing members assigned for ' + activeYear + ' Hijri';
  var tbody = document.getElementById('tbl-team-roster-modal-body');
  tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:20px"><i class="fa fa-spinner fa-spin"></i> Loading roster...</td></tr>';
  document.getElementById('team-roster-modal').style.display = 'flex';

  $.ajax({
    url: BASE_URL + 'admin/get_assigned_members_ajax',
    type: 'GET',
    data: {
      year: activeYear,
      umoor_id: selectedUmoorId,
      sub_committee_id: teamId
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success && res.assigned && res.assigned.length > 0) {
        tbody.innerHTML = '';
        res.assigned.forEach(function(a) {
          var tr = document.createElement('tr');
          tr.innerHTML = '<td><strong>' + a.user_its + '</strong></td>' +
            '<td>' + escapeHtml(a.member_name || '—') + '</td>' +
            '<td><span class="level-badge">' + escapeHtml(a.role) + '</span></td>' +
            '<td>' + (a.Mobile_No || '—') + '</td>' +
            '<td>' + (a.Gender || '—') + '</td>';
          tbody.appendChild(tr);
        });
      } else {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:20px;color:var(--text-3)">No members currently assigned to this team.</td></tr>';
      }
    }
  });
}

function closeTeamRosterModal() {
  document.getElementById('team-roster-modal').style.display = 'none';
}

var searchTimer = null;
function onRosterSearchInput() {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(function() { loadRoster(1); }, 300);
}

function onRosterFilterChange() {
  loadRoster(1);
}

function loadRoster(page) {
  rosterPage = page || 1;
  var q = document.getElementById('roster-search-q').value;
  var uid = document.getElementById('roster-filter-umoor').value;

  var tbody = document.getElementById('tbl-roster-body');
  tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:20px"><i class="fa fa-spinner fa-spin"></i> Loading member roster...</td></tr>';

  $.ajax({
    url: BASE_URL + 'admin/get_members_ajax',
    type: 'GET',
    data: {
      q: q,
      year: activeYear,
      umoor_id: uid,
      assigned_only: 1,
      page: rosterPage,
      limit: 10
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success && res.members && res.members.length > 0) {
        tbody.innerHTML = '';
        res.members.forEach(function(m) {
          var tr = document.createElement('tr');
          tr.innerHTML = '<td><strong>' + m.ITS_ID + '</strong></td>' +
            '<td>' + escapeHtml(m.Full_Name || '—') + '</td>' +
            '<td><span class="level-badge">' + escapeHtml(m.assigned_role || 'Member') + '</span></td>' +
            '<td>' + (m.Mobile_No || '—') + '</td>' +
            '<td>' + (m.Email || '—') + '</td>' +
            '<td>' + (m.Gender || '—') + '</td>' +
            '<td>' + (m.HOF_Name || '—') + '</td>' +
            '<td><span class="status-pill ' + (m.status_label === 'Active' ? 'status-active' : 'status-inactive') + '">' + m.status_label + '</span></td>';
          tbody.appendChild(tr);
        });

        document.getElementById('roster-page-count-info').textContent = 'Showing ' + ((res.page-1)*10 + 1) + ' to ' + Math.min(res.total, res.page*10) + ' of ' + res.total + ' members';

        var pCtrl = document.getElementById('roster-pagination-controls');
        pCtrl.innerHTML = '';
        for (var i = 1; i <= Math.min(res.pages, 10); i++) {
          var btn = document.createElement('button');
          btn.className = 'btn btn-xs ' + (i === res.page ? 'btn-primary' : 'btn-default');
          btn.textContent = i;
          btn.onclick = (function(p) { return function() { loadRoster(p); }; })(i);
          pCtrl.appendChild(btn);
        }
      } else {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:20px;color:var(--text-3)">No assigned members found.</td></tr>';
        document.getElementById('roster-page-count-info').textContent = 'Showing 0 to 0 of 0 members';
        document.getElementById('roster-pagination-controls').innerHTML = '';
      }
    }
  });
}

function escapeHtml(str) {
  return String(str || '').replace(/'/g, "\\'");
}
</script>
