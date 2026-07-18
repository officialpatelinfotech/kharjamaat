<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
/* ── Theme variables matching dashboard ── */
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

#umoorSubCommApp, #umoorSubCommApp *, #umoorSubCommApp *::before, #umoorSubCommApp *::after {
  box-sizing: border-box;
}

#umoorSubCommApp {
  font-family: 'Plus Jakarta Sans', sans-serif;
  color: var(--text-1);
  background: var(--bg);
  min-height: 100vh;
}

#umoorSubCommApp .app-root {
  display: flex;
  min-height: 100vh;
  padding-top: 57px;
}

#umoorSubCommApp .app-sidebar {
  width: 270px;
  flex-shrink: 0;
  position: sticky;
  top: 57px;
  height: calc(100vh - 57px);
  overflow-y: auto;
  background: var(--surface);
  border-right: 1px solid var(--border);
  padding: 16px 12px 32px;
}

#umoorSubCommApp .app-content {
  flex: 1;
  min-width: 0;
  padding: 22px 22px 60px;
}

/* ── Header banner ── */
#umoorSubCommApp .app-header {
  background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
  border-radius: 22px;
  padding: 22px 28px;
  margin-bottom: 24px;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}

#umoorSubCommApp .app-header::before {
  content: '';
  position: absolute;
  inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

#umoorSubCommApp .app-header::after {
  content: '';
  position: absolute;
  right: -50px;
  top: -50px;
  width: 200px;
  height: 200px;
  background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
  pointer-events: none;
}

#umoorSubCommApp .app-eyebrow {
  font-size: .67rem;
  font-weight: 700;
  letter-spacing: 1.4px;
  text-transform: uppercase;
  color: rgba(255,255,255,.6);
  margin-bottom: 4px;
  position: relative;
  z-index: 1;
}

#umoorSubCommApp .app-title {
  font-family: 'Literata', Georgia, serif;
  font-size: 1.5rem;
  font-weight: 600;
  color: #fff;
  line-height: 1.15;
  margin: 0;
  position: relative;
  z-index: 1;
}

#umoorSubCommApp .app-title span {
  color: rgba(255,255,255,.72);
  font-size: .95rem;
  font-weight: 500;
  display: block;
  margin-top: 2px;
}

#umoorSubCommApp .app-badge {
  position: relative;
  z-index: 1;
  flex-shrink: 0;
  background: rgba(255,255,255,.15);
  border: 1px solid rgba(255,255,255,.25);
  border-radius: 14px;
  padding: 10px 16px;
  backdrop-filter: blur(6px);
  text-align: center;
}

#umoorSubCommApp .app-badge-val {
  font-size: 1.5rem;
  font-weight: 800;
  color: #fff;
  line-height: 1;
  display: block;
}

#umoorSubCommApp .app-badge-lbl {
  font-size: .65rem;
  font-weight: 700;
  color: rgba(255,255,255,.65);
  letter-spacing: .5px;
  text-transform: uppercase;
  margin-top: 3px;
  display: block;
}

/* ── Sidebar Nav ── */
#umoorSubCommApp .menu-section {
  font-size: .58rem;
  font-weight: 800;
  letter-spacing: 1.1px;
  text-transform: uppercase;
  color: var(--text-3);
  padding: 12px 6px 4px;
}

#umoorSubCommApp .menu-list {
  list-style: none;
  margin: 0;
  padding: 0;
}

#umoorSubCommApp .menu-item {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 7px 9px;
  border-radius: 7px;
  color: var(--text-2);
  font-size: .82rem;
  font-weight: 500;
  text-decoration: none;
  transition: background .14s, color .14s;
}

#umoorSubCommApp .menu-item:hover, #umoorSubCommApp .menu-item.active {
  background: var(--gold-muted);
  color: var(--gold);
  text-decoration: none;
}

#umoorSubCommApp .menu-icon {
  width: 27px;
  height: 27px;
  border-radius: 7px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: var(--surface-2);
  color: var(--text-3);
  font-size: .76rem;
  flex-shrink: 0;
}

#umoorSubCommApp .menu-item:hover .menu-icon, #umoorSubCommApp .menu-item.active .menu-icon {
  background: var(--gold-muted);
  color: var(--gold);
}

#umoorSubCommApp .menu-label {
  flex: 1;
}

/* ── Umoor Cards ── */
#umoorSubCommApp .umoor-card {
  background: var(--surface);
  border-radius: 16px;
  border: 1.5px solid var(--border);
  margin-bottom: 16px;
  overflow: hidden;
  box-shadow: var(--shadow-sm);
  transition: transform .2s, box-shadow .2s;
}

#umoorSubCommApp .umoor-card:hover {
  box-shadow: var(--shadow);
}

#umoorSubCommApp .umoor-card-header {
  padding: 16px 20px;
  background: var(--surface-2);
  display: flex;
  align-items: center;
  justify-content: space-between;
  cursor: pointer;
  user-select: none;
  border-bottom: 1px solid var(--border-light);
}

#umoorSubCommApp .umoor-card-title {
  font-family: 'Literata', Georgia, serif;
  font-size: 1.05rem;
  font-weight: 600;
  color: var(--text-1);
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0;
}

#umoorSubCommApp .umoor-card-title .uc-badge {
  width: 32px;
  height: 32px;
  border-radius: 9px;
  background: var(--gold-muted);
  color: var(--gold);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 0.85rem;
}

#umoorSubCommApp .umoor-card-body {
  padding: 20px;
  display: none; /* Collapsed by default */
}

#umoorSubCommApp .umoor-card.active .umoor-card-body {
  display: block;
}

#umoorSubCommApp .umoor-card-chevron {
  font-size: 0.95rem;
  color: var(--text-3);
  transition: transform 0.2s;
}

#umoorSubCommApp .umoor-card.active .umoor-card-chevron {
  transform: rotate(180deg);
}

/* ── Sub-Committee Items ── */
#umoorSubCommApp .sub-comm-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 18px;
}

#umoorSubCommApp .sub-comm-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  background: var(--bg);
  border: 1px solid var(--border);
  border-radius: 10px;
  flex-wrap: wrap;
  gap: 10px;
}

#umoorSubCommApp .sub-comm-name {
  font-weight: 700;
  color: var(--text-1);
  font-size: 0.9rem;
}

#umoorSubCommApp .sub-comm-lead {
  font-size: 0.78rem;
  color: var(--text-3);
}

#umoorSubCommApp .sub-comm-lead .lead-badge {
  background: var(--gold-muted);
  color: var(--gold);
  padding: 2px 8px;
  border-radius: 12px;
  font-weight: 700;
  margin-left: 6px;
  font-size: 0.72rem;
}

#umoorSubCommApp .sub-comm-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}

/* ── Premium Buttons ── */
#umoorSubCommApp .btn-premium {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  font-weight: 700;
  font-size: 0.8rem;
  padding: 7px 14px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  transition: transform 0.15s, background 0.15s;
}

#umoorSubCommApp .btn-premium-gold {
  background: linear-gradient(135deg, #78520a 0%, #b8860b 100%);
  color: #fff;
}

#umoorSubCommApp .btn-premium-gold:hover {
  background: linear-gradient(135deg, #8c600b 0%, #cca525 100%);
  transform: translateY(-1px);
}

#umoorSubCommApp .btn-premium-outline {
  background: transparent;
  border: 1.5px solid var(--border);
  color: var(--text-2);
}

#umoorSubCommApp .btn-premium-outline:hover {
  background: var(--gold-muted);
  border-color: var(--gold);
  color: var(--gold);
}

#umoorSubCommApp .btn-premium-danger-outline {
  background: transparent;
  border: 1.5px solid #fca5a5;
  color: #ef4444;
}

#umoorSubCommApp .btn-premium-danger-outline:hover {
  background: #fee2e2;
  border-color: #ef4444;
}

/* ── Autocomplete CSS ── */
#umoorSubCommApp .autocomplete-container {
  position: relative;
  width: 100%;
}

#umoorSubCommApp .autocomplete-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: #fff;
  border: 1.5px solid var(--border);
  border-top: none;
  border-radius: 0 0 10px 10px;
  box-shadow: var(--shadow-lg);
  max-height: 220px;
  overflow-y: auto;
  z-index: 2000;
  display: none;
}

#umoorSubCommApp .autocomplete-item {
  padding: 10px 14px;
  cursor: pointer;
  border-bottom: 1px solid var(--border-light);
  font-size: 0.82rem;
  transition: background 0.15s;
}

#umoorSubCommApp .autocomplete-item:hover {
  background: var(--gold-muted);
}

#umoorSubCommApp .selected-lead-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: var(--surface-2);
  border: 1.5px solid var(--gold-light);
  border-radius: 10px;
  padding: 10px 14px;
  margin-top: 8px;
}

#umoorSubCommApp .form-control-premium {
  width: 100%;
  padding: 10px 14px;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  outline: none;
  font-family: inherit;
  font-size: 0.85rem;
  transition: border-color 0.2s, box-shadow 0.2s;
  background: var(--surface-2);
}

#umoorSubCommApp .form-control-premium:focus {
  border-color: var(--gold);
  box-shadow: 0 0 0 3px rgba(184,134,11,.1);
  background: #fff;
}
</style>

<?php
$umoor_list = [
  1 => 'Umoor Deeniyah',
  2 => 'Umoor Talimiyah',
  3 => 'Umoor Kharijiyah',
  4 => 'Umoor Dakheliyah',
  5 => 'Umoor Maliyah',
  6 => 'Umoor Iqtesadiyah',
  7 => 'Umoor Sehat',
  8 => 'Umoor Faisala',
  9 => 'Umoor Ikram',
  10 => 'Umoor Dawat',
  11 => 'Umoor Mawaid',
  12 => 'Umoor Amlak'
];

// Group sub-committees by Umoor ID
$sub_committees_by_umoor = [];
foreach ($umoor_list as $uid => $uname) {
  $sub_committees_by_umoor[$uid] = [];
}
if (!empty($sub_committees)) {
  foreach ($sub_committees as $sc) {
    $sub_committees_by_umoor[(int)$sc['umoor_id']][] = $sc;
  }
}
?>

<div id="umoorSubCommApp">
  <div class="app-root">
    
    <!-- ══ SIDEBAR ══ -->
    <aside class="app-sidebar">
      <div class="sb-brand mb-3">
        <span class="sb-ico" style="display:inline-flex;width:26px;height:26px;border-radius:7px;background:var(--gold-muted);color:var(--gold);align-items:center;justify-content:center;font-size:.78rem;margin-right:8px;">
          <i class="fa fa-tachometer"></i>
        </span>Admin Menu
      </div>
      <div class="menu-section">Member Management</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('admin/managemembers'); ?>"><span class="menu-icon"><i class="fa fa-users"></i></span><span class="menu-label">Manage Members</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/importmembers'); ?>"><span class="menu-icon"><i class="fa fa-upload"></i></span><span class="menu-label">Import Members</span></a></li>
        <li><a class="menu-item active" href="<?php echo base_url('admin/umoor_sub_committees'); ?>"><span class="menu-icon"><i class="fa fa-sitemap"></i></span><span class="menu-label">Umoor Sub-Committees</span></a></li>
      </ul>
      <div class="menu-section">Finance & Sabeel</div>
      <ul class="menu-list">
        <li><a class="menu-item" href="<?php echo base_url('admin/managesabeeltakhmeen'); ?>"><span class="menu-icon"><i class="fa fa-credit-card"></i></span><span class="menu-label">Sabeel Takhmeen</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/corpusfunds'); ?>"><span class="menu-icon"><i class="fa fa-university"></i></span><span class="menu-label">Corpus Funds</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('admin/rent'); ?>"><span class="menu-icon"><i class="fa fa-building"></i></span><span class="menu-label">Rent Module</span></a></li>
      </ul>
    </aside>

    <!-- ══ CONTENT ══ -->
    <main class="app-content">
      
      <!-- Header banner -->
      <div class="app-header">
        <div>
          <p class="app-eyebrow">Anjuman-e-Saifee <?php echo htmlspecialchars(jamaat_name(), ENT_QUOTES, 'UTF-8'); ?></p>
          <h1 class="app-title">
            Umoor Sub-Committees
            <span>Organize sub-committees and assign team leads under the 12 Umoor</span>
          </h1>
        </div>
        <div class="app-badge">
          <span class="app-badge-val">12</span>
          <span class="app-badge-lbl">Umoor Categories</span>
        </div>
      </div>

      <!-- ══ Umoor Collapsible Grid ══ -->
      <div class="umoor-accordion">
        <?php foreach ($umoor_list as $uid => $uname): ?>
          <div class="umoor-card" id="umoor-card-<?php echo $uid; ?>">
            <div class="umoor-card-header" onclick="toggleUmoorCard(<?php echo $uid; ?>)">
              <h4 class="umoor-card-title">
                <span class="uc-badge"><?php echo $uid; ?></span>
                <?php echo htmlspecialchars($uname); ?>
                <span class="small text-muted" style="font-family:'Plus Jakarta Sans', sans-serif; font-size:0.72rem; font-weight:normal;">
                  (<?php echo count($sub_committees_by_umoor[$uid]); ?> Sub-Committees)
                </span>
              </h4>
              <i class="fa fa-chevron-down umoor-card-chevron"></i>
            </div>
            <div class="umoor-card-body">
              
              <!-- Sub-Committees list -->
              <div class="sub-comm-list">
                <?php if (empty($sub_committees_by_umoor[$uid])): ?>
                  <p class="text-muted small text-center my-3"><i class="fa fa-info-circle"></i> No sub-committees registered under this Umoor.</p>
                <?php else: ?>
                  <?php foreach ($sub_committees_by_umoor[$uid] as $sc): ?>
                    <div class="sub-comm-item">
                      <div>
                        <div class="sub-comm-name"><?php echo htmlspecialchars($sc['name']); ?></div>
                        <div class="sub-comm-lead">
                          <?php if ($sc['team_lead_its']): ?>
                            Team Lead: <strong><?php echo htmlspecialchars($sc['team_lead_name']); ?></strong> <span class="lead-badge"><?php echo htmlspecialchars($sc['team_lead_its']); ?></span>
                          <?php else: ?>
                            <span class="text-danger"><i class="fa fa-exclamation-triangle"></i> No Team Lead Assigned</span>
                          <?php endif; ?>
                        </div>
                      </div>
                      <div class="sub-comm-actions">
                        <button type="button" class="btn-premium btn-premium-outline btn-sm" onclick="openSubCommitteeModal(<?php echo $uid; ?>, <?php echo $sc['id']; ?>, '<?php echo addslashes($sc['name']); ?>', '<?php echo addslashes($sc['team_lead_its'] ?? ''); ?>', '<?php echo addslashes($sc['team_lead_name'] ?? ''); ?>')">
                          <i class="fa fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn-premium btn-premium-danger-outline btn-sm" onclick="deleteSubCommittee(<?php echo $sc['id']; ?>)">
                          <i class="fa fa-trash"></i> Delete
                        </button>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>

              <!-- Add button -->
              <div class="text-right">
                <button type="button" class="btn-premium btn-premium-gold" onclick="openSubCommitteeModal(<?php echo $uid; ?>)">
                  <i class="fa fa-plus"></i> Add Sub-Committee
                </button>
              </div>

            </div>
          </div>
        <?php endforeach; ?>
      </div>

    </main>
  </div>
</div>

<!-- ══ Create/Edit Sub-Committee Modal ══ -->
<div class="modal fade" id="subCommitteeModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:14px; border:none; box-shadow:var(--shadow-lg);">
      <div class="modal-header" style="border-bottom:1.5px solid var(--border-light); background:var(--surface-2); border-radius:14px 14px 0 0;">
        <h5 class="modal-title" id="modalTitle" style="font-family:'Literata', Georgia, serif; font-weight:600; color:var(--text-1);">Add Sub-Committee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline:none;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="subCommitteeForm" onsubmit="saveSubCommittee(event)">
        <input type="hidden" name="id" id="sc_id" value="0">
        <input type="hidden" name="umoor_id" id="sc_umoor_id" value="0">

        <div class="modal-body p-4">
          
          <div class="form-group">
            <label style="font-weight:700; color:var(--text-2); font-size:0.8rem; text-transform:uppercase; display:block; margin-bottom:6px;">Umoor Category</label>
            <input type="text" id="sc_umoor_name_display" class="form-control-premium" disabled style="background:#e9e7e0;">
          </div>

          <div class="form-group mt-3">
            <label for="sc_name" style="font-weight:700; color:var(--text-2); font-size:0.8rem; text-transform:uppercase; display:block; margin-bottom:6px;">Sub-Committee Name *</label>
            <input type="text" name="name" id="sc_name" class="form-control-premium" required placeholder="Enter sub-committee name..." autocomplete="off">
          </div>

          <div class="form-group mt-3">
            <label style="font-weight:700; color:var(--text-2); font-size:0.8rem; text-transform:uppercase; display:block; margin-bottom:6px;">Team Lead</label>
            
            <!-- Autocomplete input -->
            <div class="autocomplete-container" id="leadSearchContainer">
              <input type="text" id="leadSearchInput" class="form-control-premium" placeholder="Type Team Lead's name or ITS ID..." autocomplete="off">
              <div class="autocomplete-dropdown" id="leadSearchDropdown"></div>
            </div>

            <!-- Selected Lead Card -->
            <div id="selectedLeadCard" class="selected-lead-card" style="display:none;">
              <div>
                <strong id="cardLeadName">Name</strong>
                <div class="small text-muted" id="cardLeadIts">ITS ID</div>
              </div>
              <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearSelectedLead()" style="border-radius:6px; font-weight:700; font-size:0.75rem;">Change</button>
            </div>
            
            <input type="hidden" name="team_lead_its" id="sc_team_lead_its" value="">
          </div>

        </div>
        <div class="modal-footer" style="border-top:1.5px solid var(--border-light); background:var(--surface-2); border-radius:0 0 14px 14px;">
          <button type="button" class="btn-premium btn-premium-outline" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-premium btn-premium-gold">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
var umoorList = <?php echo json_encode($umoor_list); ?>;

function toggleUmoorCard(uid) {
  var card = jQuery('#umoor-card-' + uid);
  card.toggleClass('active');
}

function openSubCommitteeModal(umoorId, id = 0, name = '', leadIts = '', leadName = '') {
  jQuery('#sc_umoor_id').val(umoorId);
  jQuery('#sc_umoor_name_display').val(umoorList[umoorId]);
  jQuery('#sc_id').val(id);
  jQuery('#sc_name').val(name);
  jQuery('#sc_team_lead_its').val(leadIts);

  if (id > 0) {
    jQuery('#modalTitle').text('Edit Sub-Committee');
  } else {
    jQuery('#modalTitle').text('Add Sub-Committee');
  }

  if (leadIts) {
    jQuery('#leadSearchContainer').hide();
    jQuery('#cardLeadName').text(leadName);
    jQuery('#cardLeadIts').text('ITS: ' + leadIts);
    jQuery('#selectedLeadCard').show();
  } else {
    clearSelectedLead();
  }

  jQuery('#subCommitteeModal').modal('show');
}

function clearSelectedLead() {
  jQuery('#sc_team_lead_its').val('');
  jQuery('#leadSearchInput').val('');
  jQuery('#selectedLeadCard').hide();
  jQuery('#leadSearchContainer').show();
}

function saveSubCommittee(e) {
  e.preventDefault();
  
  var formData = jQuery('#subCommitteeForm').serialize();

  jQuery.ajax({
    url: '<?php echo base_url("admin/save_sub_committee"); ?>',
    type: 'POST',
    dataType: 'json',
    data: formData,
    success: function(res) {
      if (res && res.success) {
        alert(res.message);
        jQuery('#subCommitteeModal').modal('hide');
        location.reload();
      } else {
        alert(res.message || 'Operation failed.');
      }
    },
    error: function() {
      alert('Server error. Please try again.');
    }
  });
}

function deleteSubCommittee(id) {
  if (!confirm('Are you sure you want to delete this Sub-Committee?')) return;

  jQuery.ajax({
    url: '<?php echo base_url("admin/delete_sub_committee"); ?>',
    type: 'POST',
    dataType: 'json',
    data: { id: id },
    success: function(res) {
      if (res && res.success) {
        alert(res.message);
        location.reload();
      } else {
        alert(res.message || 'Deletion failed.');
      }
    },
    error: function() {
      alert('Server error. Please try again.');
    }
  });
}

// ── Auto-complete Logic for Team Lead ──
var typingTimer;
var doneTypingInterval = 300;

jQuery(document).ready(function() {
  var searchInput = jQuery('#leadSearchInput');
  var dropdown = jQuery('#leadSearchDropdown');

  searchInput.on('keyup', function() {
    clearTimeout(typingTimer);
    var query = searchInput.val().trim();
    
    if (query.length < 2) {
      dropdown.hide().empty();
      return;
    }

    typingTimer = setTimeout(function() {
      jQuery.ajax({
        url: '<?php echo base_url("admin/searchmembers"); ?>',
        type: 'GET',
        dataType: 'json',
        data: { q: query },
        success: function(res) {
          dropdown.empty();
          if (res && res.results && res.results.length > 0) {
            res.results.forEach(function(item) {
              var div = jQuery('<div class="autocomplete-item"></div>');
              div.text(item.name + ' (' + item.its_id + ') - ' + (item.sector || 'N/A'));
              div.on('click', function() {
                // Select lead
                jQuery('#sc_team_lead_its').val(item.its_id);
                jQuery('#cardLeadName').text(item.name);
                jQuery('#cardLeadIts').text('ITS: ' + item.its_id + ' | Sector: ' + (item.sector || 'N/A'));
                jQuery('#leadSearchContainer').hide();
                jQuery('#selectedLeadCard').show();
                dropdown.hide().empty();
              });
              dropdown.append(div);
            });
            dropdown.show();
          } else {
            dropdown.append('<div class="p-3 text-center text-muted" style="font-size:0.8rem;">No members found.</div>').show();
          }
        }
      });
    }, doneTypingInterval);
  });

  // Hide dropdown when clicking outside
  jQuery(document).on('click', function(e) {
    if (!jQuery(e.target).closest('#leadSearchContainer').length) {
      dropdown.hide();
    }
  });
});
</script>
