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
#anjApp .menu-item:hover .menu-icon, #anjApp .menu-item.active .menu-icon { background: var(--gold-muted); color: var(--gold); }
#anjApp .menu-label { flex: 1; white-space: normal; word-break: break-word; }

#anjApp .anj-header-inner {
  background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
  border-radius: 22px; padding: 22px 26px; color: #fff; margin-bottom: 22px;
  display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
#anjApp .anj-title { font-family: 'Literata', Georgia, serif; font-size: 1.6rem; font-weight: 600; color: #fff; margin: 0; }
#anjApp .anj-eyebrow { font-size: .67rem; font-weight: 700; letter-spacing: 1.4px; text-transform: uppercase; color: rgba(255,255,255,.6); margin-bottom: 4px; }

.year-select-box {
  background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);
  border-radius: 10px; padding: 6px 12px; color: #fff; font-weight: 800; font-size: 0.85rem; outline: none; cursor: pointer;
}
.year-select-box option { color: #000; }

.table-card {
  background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border);
  box-shadow: var(--shadow-sm); padding: 18px 20px; margin-bottom: 20px;
}

.table-custom { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.82rem; }
.table-custom th {
  background: var(--surface-2); color: var(--text-2); font-weight: 700; text-transform: uppercase;
  font-size: 0.68rem; letter-spacing: 0.5px; padding: 10px 14px; border-bottom: 1px solid var(--border);
}
.table-custom td { padding: 12px 14px; border-bottom: 1px solid var(--border-light); color: var(--text-1); vertical-align: middle; }
.table-custom tr:last-child td { border-bottom: none; }

.status-pill { display: inline-block; padding: 3px 10px; border-radius: 20px; font-weight: 800; font-size: 0.68rem; }
.status-active { background: #eaf4ee; color: #1a6645; border: 1px solid #bbf7d0; }

.btn-action-primary {
  background: linear-gradient(135deg, #b8860b 0%, #78520a 100%); color: #fff; border: none;
  padding: 6px 14px; border-radius: 8px; font-weight: 700; font-size: 0.78rem; cursor: pointer; text-decoration: none;
  display: inline-flex; align-items: center; gap: 6px;
}
.btn-action-primary:hover { opacity: 0.9; color: #fff; text-decoration: none; }

.roster-modal-overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(3px);
  z-index: 9999; display: none; align-items: center; justify-content: center; padding: 16px;
}
.roster-modal-box {
  background: #fff; border-radius: 18px; width: 100%; max-width: 780px; max-height: 85vh;
  display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15);
}
.roster-modal-head {
  padding: 16px 20px; background: var(--surface-2); border-bottom: 1px solid var(--border);
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

      <div class="menu-section">Raza</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('anjuman/EventRazaRequest?event_type=1') ?>"><span class="menu-icon"><i class="fa fa-handshake-o"></i></span><span class="menu-label">Miqaat Raza Request</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/EventRazaRequest?event_type=2') ?>"><span class="menu-icon"><i class="fa fa-handshake-o"></i></span><span class="menu-label">Kaaraj Raza Request</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/UmoorRazaRequest') ?>"><span class="menu-icon"><i class="fa fa-list"></i></span><span class="menu-label">12 Umoor Raza Request</span></a></li>
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
        <li><a class="menu-item" href="<?php echo base_url('anjuman/laagat') ?>"><span class="menu-icon"><i class="fa fa-calculator"></i></span><span class="menu-label">Laagat Module</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/rent') ?>"><span class="menu-icon"><i class="fa fa-home"></i></span><span class="menu-label">Rent Module</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('anjuman/wajebaat') ?>"><span class="menu-icon"><i class="fa fa-coins"></i></span><span class="menu-label">Wajebaat</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('madresa') ?>"><span class="menu-icon"><i class="fa fa-graduation-cap"></i></span><span class="menu-label">Madresa Module</span></a></li>
      </ul>

      <div class="menu-section">Sanstha Module</div>
      <ul class="menu-list">
        <li><a class="menu-item active" href="<?php echo base_url('anjuman/sanstha') ?>"><span class="menu-icon"><i class="fa fa-building-o"></i></span><span class="menu-label">Sanstha Directory</span></a></li>
      </ul>
    </aside>

    <!-- ══ MAIN CONTENT ══ -->
    <main class="anj-content">

      <!-- Header Banner -->
      <div class="anj-header-inner">
        <div>
          <div class="anj-eyebrow">Sanstha Module &bull; View Only</div>
          <h1 class="anj-title">Sanstha Directory</h1>
          <div style="font-size:0.8rem;color:rgba(255,255,255,0.8);margin-top:4px">Browse active Sansthas in Jamaat and view member rosters per Hijri year.</div>
        </div>
        <div>
          <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;margin-right:6px"><i class="fa fa-calendar"></i> Hijri Year:</span>
          <select class="year-select-box" onchange="location.href='<?php echo base_url('anjuman/sanstha?year='); ?>' + this.value">
            <option value="1446" <?php echo ($year === '1446') ? 'selected' : ''; ?>>1446 Hijri</option>
            <option value="1447" <?php echo ($year === '1447') ? 'selected' : ''; ?>>1447 Hijri</option>
            <option value="1448" <?php echo ($year === '1448') ? 'selected' : ''; ?>>1448 Hijri</option>
            <option value="1449" <?php echo ($year === '1449') ? 'selected' : ''; ?>>1449 Hijri</option>
            <option value="1450" <?php echo ($year === '1450') ? 'selected' : ''; ?>>1450 Hijri</option>
          </select>
        </div>
      </div>

      <!-- Sanstha List Table -->
      <div class="table-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px">
          <div>
            <h3 style="font-size:0.95rem;font-weight:800;color:var(--text-1);margin:0">Registered Sansthas Directory</h3>
            <p style="font-size:0.76rem;color:var(--text-2);margin:2px 0 0 0">Click "View Member Roster" to inspect members assigned for <?php echo htmlspecialchars($year); ?> Hijri.</p>
          </div>
          <div style="position:relative;width:260px">
            <input type="text" id="sanstha-search-q" placeholder="Search Sanstha..." style="width:100%;padding:6px 12px 6px 30px;border:1px solid var(--border);border-radius:8px;font-size:0.8rem;outline:none;" onkeyup="filterDirectoryTable()">
            <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-3);font-size:0.75rem"></i>
          </div>
        </div>

        <div style="overflow-x:auto">
          <table class="table-custom">
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
            <tbody id="tbl-sanstha-body">
              <?php if(!empty($sanstha_list)): ?>
                <?php foreach($sanstha_list as $idx => $s): ?>
                  <tr class="sanstha-row-item" data-name="<?php echo strtolower(htmlspecialchars($s['name'])); ?>" data-desc="<?php echo strtolower(htmlspecialchars($s['description'] ?? '')); ?>">
                    <td><strong><?php echo ($idx + 1); ?></strong></td>
                    <td><strong style="color:var(--text-1);font-size:0.86rem"><?php echo htmlspecialchars($s['name']); ?></strong></td>
                    <td><span class="text-muted" style="font-size:0.76rem"><?php echo htmlspecialchars($s['description'] ?? '—'); ?></span></td>
                    <td><span class="status-pill status-active"><?php echo (int)($s['members_count'] ?? 0); ?> Members (<?php echo htmlspecialchars($year); ?>H)</span></td>
                    <td><span class="status-pill status-active"><?php echo htmlspecialchars($s['status']); ?></span></td>
                    <td style="text-align:right">
                      <button type="button" class="btn-action-primary" onclick="openRosterModal(<?php echo $s['id']; ?>, '<?php echo htmlspecialchars(addslashes($s['name'])); ?>')">
                        <i class="fa fa-users"></i> View Member Roster
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" style="text-align:center;padding:24px;color:var(--text-3)">No active Sansthas found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>

<!-- ROSTER VIEW MODAL -->
<div class="roster-modal-overlay" id="roster-modal">
  <div class="roster-modal-box">
    <div class="roster-modal-head">
      <div>
        <h4 style="font-size:1rem;font-weight:800;margin:0;color:var(--text-1)" id="modal-sanstha-name">Sanstha Roster</h4>
        <span style="font-size:0.75rem;color:var(--text-2)">Assigned Members Roster for <?php echo htmlspecialchars($year); ?> Hijri</span>
      </div>
      <button type="button" onclick="closeRosterModal()" style="border:none;background:var(--surface-2);border-radius:6px;width:28px;height:28px;cursor:pointer">&times;</button>
    </div>
    <div class="roster-modal-body">
      <div style="display:flex;gap:10px;margin-bottom:14px">
        <input type="text" id="roster-search-q" placeholder="Search assigned members by Name, ITS..." style="flex:1;padding:6px 12px;border:1px solid var(--border);border-radius:8px;font-size:0.8rem;outline:none;" onkeyup="onRosterSearchInput()">
      </div>
      <div id="roster-list-container">
        <div style="text-align:center;padding:20px;color:var(--text-3)"><i class="fa fa-spinner fa-spin"></i> Loading roster...</div>
      </div>
    </div>
  </div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';
var activeYear = '<?php echo $year; ?>';
var currentSansthaId = null;

$(document).ready(function() {
  $('#quickMenuSearch').on('keyup input', function() {
    var q = $(this).val().toLowerCase().trim();
    $('.menu-list li').each(function() {
      var txt = $(this).text().toLowerCase();
      $(this).toggle(txt.indexOf(q) !== -1);
    });
  });
});

function filterDirectoryTable() {
  var q = document.getElementById('sanstha-search-q').value.toLowerCase().trim();
  var rows = document.querySelectorAll('.sanstha-row-item');
  rows.forEach(function(row) {
    var name = row.getAttribute('data-name') || '';
    var desc = row.getAttribute('data-desc') || '';
    row.style.display = (!q || name.indexOf(q) !== -1 || desc.indexOf(q) !== -1) ? '' : 'none';
  });
}

function openRosterModal(sansthaId, sansthaName) {
  currentSansthaId = sansthaId;
  document.getElementById('modal-sanstha-name').textContent = sansthaName + ' — Assigned Members';
  document.getElementById('roster-modal').style.display = 'flex';
  loadRosterMembers(1);
}

function closeRosterModal() {
  document.getElementById('roster-modal').style.display = 'none';
}

var searchTimer = null;
function onRosterSearchInput() {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(function() {
    loadRosterMembers(1);
  }, 300);
}

function loadRosterMembers(page) {
  if (!currentSansthaId) return;
  var q = document.getElementById('roster-search-q').value;
  var container = document.getElementById('roster-list-container');
  container.innerHTML = '<div style="text-align:center;padding:20px;color:var(--text-3)"><i class="fa fa-spinner fa-spin"></i> Loading roster...</div>';

  $.ajax({
    url: BASE_URL + 'admin/get_sanstha_members_ajax',
    type: 'GET',
    data: {
      sanstha_id: currentSansthaId,
      q: q,
      year: activeYear,
      page: page,
      limit: 20
    },
    dataType: 'json',
    success: function(res) {
      if (res && res.success && res.members) {
        if (res.members.length === 0) {
          container.innerHTML = '<div style="text-align:center;padding:24px;color:var(--text-3)">No members assigned to this Sanstha for ' + activeYear + ' Hijri.</div>';
          return;
        }

        var html = '<table class="table-custom"><thead><tr><th>ITS ID</th><th>Full Name</th><th>Mobile</th><th>Gender</th><th>Status</th></tr></thead><tbody>';
        res.members.forEach(function(m) {
          html += '<tr>' +
            '<td><strong>' + m.ITS_ID + '</strong></td>' +
            '<td>' + escapeHtml(m.Full_Name || '—') + '</td>' +
            '<td>' + (m.Mobile_No || '—') + '</td>' +
            '<td>' + (m.Gender || '—') + '</td>' +
            '<td><span class="status-pill ' + (m.status_label === 'Active' ? 'status-active' : 'status-inactive') + '">' + m.status_label + '</span></td>' +
            '</tr>';
        });
        html += '</tbody></table>';
        container.innerHTML = html;
      } else {
        container.innerHTML = '<div style="text-align:center;padding:20px;color:var(--text-3)">Failed to load roster.</div>';
      }
    }
  });
}

function escapeHtml(str) {
  return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}
</script>
