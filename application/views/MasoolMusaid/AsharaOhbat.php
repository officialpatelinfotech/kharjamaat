<?php 
  $users = $users ?? []; 
  $role_segment = $this->uri->segment(1);
  $directory_url = base_url($role_segment . '/mumineendirectory');

  $view_member_base = 'admin/viewmember/';
  if ($this->session->userdata('role') === 'amilsaheb' || (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 2)) {
    $view_member_base = 'amilsaheb/viewmember/';
  } else if ($this->session->userdata('role') === 'MasoolMusaid' || (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 16)) {
    $view_member_base = 'MasoolMusaid/viewmember/';
  }

  $username_raw = $_SESSION['user']['username'] ?? '';
  $is_sub_sector = false;
  if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z])$/i', $username_raw)) {
      $is_sub_sector = true;
  }
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
    --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow:      0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
  }

  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background-color: var(--bg);
    color: var(--text-1);
  }

  .header-section {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
  }

  .stats-card {
    background-color: var(--surface);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: var(--shadow-sm);
    transition: transform 0.2s, box-shadow 0.2s;
    height: 100%;
  }

  .stats-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow);
    cursor: pointer;
  }

  .stats-card.active {
    border-color: var(--gold) !important;
    background-color: var(--gold-muted) !important;
  }

  .stats-card h5 {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .4px;
    color: var(--text-3);
    text-transform: uppercase;
    margin-bottom: 10px;
  }

  .stats-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text-1);
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
  }

  /* ── Overview cards ── */
  .overview-card {
    background: var(--surface); border: 1.5px solid var(--border); border-radius: 12px;
    padding: 12px 14px; display: flex; align-items: center; gap: 11px; height: 100%;
    transition: border-color .2s, box-shadow .2s, transform .2s; position: relative; overflow: hidden;
  }
  .overview-card:hover { border-color: var(--gold); box-shadow: var(--shadow); transform: translateY(-2px); }
  .overview-card::after {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0;
    height: 2px; background: var(--gold); transform: scaleX(0); transition: transform .2s; transform-origin: left;
  }
  .overview-card:hover::after { transform: scaleX(1); }
  .overview-card.active { border-color: var(--gold) !important; }
  .overview-card.active::after { transform: scaleX(1) !important; }
  .overview-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: inline-flex; align-items: center; justify-content: center;
    background: var(--gold-muted); color: var(--gold); font-size: .95rem; flex-shrink: 0;
  }
  .overview-body { display: flex; flex-direction: column; min-width: 0; }
  .overview-title { font-size: .72rem; color: var(--text-3); font-weight: 600; margin: 0; line-height: 1.3; }
  .overview-value { font-size: 1.2rem; font-weight: 800; color: var(--text-1); line-height: 1.1; margin: 3px 0 0; }

  .table th {
    background-color: var(--gold);
    color: white;
    position: sticky;
    top: 0;
  }

  .table-responsive {
    overflow-x: auto;
    max-height: 70vh;
  }

  .hof-row  { background-color: #e3f2fd !important; border-left: 4px solid #1976d2; }
  .fm-row   { background-color: #f3e5f5 !important; border-left: 4px solid #8e24aa; }
  .no-leave { background-color: #ffebee !important; border-left: 4px solid #d32f2f; }
  .no-leave-count { color: #d32f2f; font-weight: bold; }

  .section-title {
    border-bottom: 1.5px solid var(--border-light);
    padding-bottom: 10px;
    margin: 24px 0 16px;
    color: var(--text-2);
    font-size: 1.1rem;
    font-weight: 800;
  }

  .filter-section {
    background-color: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
  }

  .badge-status { font-size: 0.8em; padding: 5px 8px; border-radius: 12px; }
  .action-btn { min-width: 80px; }
  #userData tbody tr { cursor: pointer; }

  @media (max-width: 768px) {
    .stats-grid, .sector-grid { grid-template-columns: repeat(2, 1fr); }
    .table-responsive { max-height: none; }
  }

  .card {
    background-color: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
  }

  .btn-primary { background-color: var(--gold); border-color: var(--gold); }
  .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
    background-color: #78520a !important; border-color: #78520a !important;
  }
  .modal-header.bg-primary { background-color: var(--gold) !important; }

  .chart-container {
    background: var(--surface); border-radius: 16px; border: 1px solid var(--border);
    box-shadow: var(--shadow-sm); padding: 18px 20px; margin-bottom: 16px;
    position: relative; overflow: hidden; transition: box-shadow .2s;
  }
  .chart-container:hover { box-shadow: var(--shadow); }
  .chart-container.compact { padding: 14px 16px; }

  .section-header-standard {
    display: flex; align-items: center; justify-content: space-between;
    padding-bottom: 10px; margin-bottom: 14px; border-bottom: 1.5px solid var(--border-light);
  }
  .section-header-standard .section-title {
    font-size: .87rem; font-weight: 800; color: var(--text-2);
    margin: 0 !important; display: flex; align-items: center; gap: 8px;
  }
  .section-header-standard .section-title i { color: var(--gold); font-size: .82rem; }

  .collapse-toggle-btn {
    border-radius: 50%; width: 28px; height: 28px; padding: 0;
    display: flex; align-items: center; justify-content: center;
    background: var(--surface-2); border: 1.5px solid var(--border); color: var(--text-3);
    transition: all .2s; flex-shrink: 0; cursor: pointer;
  }
  .collapse-toggle-btn:hover { background: var(--gold-muted); color: var(--gold); border-color: var(--gold); }
  .collapse-toggle-btn i { transition: transform .22s; }
  .collapse-toggle-btn[aria-expanded="false"] i { transform: rotate(-90deg); }
</style>

<div class="container-fluid pt-5 p-3 p-md-4">

  <!-- Header Section -->
  <div class="d-flex align-items-center pt-5 mb-4">
    <a href="<?php echo isset($back_url) ? $back_url : 'javascript:void(0)'; ?>"
       class="btn btn-outline-secondary" title="Back"
       <?php if (!isset($back_url)) : ?>onclick="window.history.back()"<?php endif; ?>>
      <i class="fa-solid fa-arrow-left"></i>
    </a>
  </div>

  <div class="header-section text-center">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
      <div class="text-start flex-grow-1">
        <h2 class="mb-0 fw-bold">Ashara Ohbat<?php if(isset($selected_year)) echo ' ' . htmlspecialchars($selected_year); ?></h2>
        <h4 class="mb-0"><?php echo ucwords($user_name); ?></h4>
      </div>
      <?php if(isset($year_options) && is_array($year_options) && !empty($year_options)): ?>
      <div class="text-end">
        <label for="yearSelect" class="me-1">Hijri Year</label>
        <select id="yearSelect" class="form-control form-select form-select-sm d-inline-block" style="width:auto; display:inline-block;">
          <?php foreach($year_options as $y): ?>
            <option value="<?php echo (int)$y; ?>" <?php echo (isset($selected_year) && (int)$selected_year === (int)$y) ? 'selected' : ''; ?>><?php echo (int)$y; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="mb-2 fw-bold" id="totalSectorCard"></div>

  <!-- Sector/Sub-sector Filters Panel -->
  <div class="chart-container compact mb-3">
    <div class="row align-items-center g-3">
      <div class="col-12 col-md-6">
        <label for="sectorFilterSel" class="form-label fw-bold small mb-1" style="color: var(--text-2);">
          <i class="fa fa-map-marker" style="color: var(--gold); margin-right: 4px;"></i> Sector
        </label>
        <select id="sectorFilterSel" class="form-control form-select shadow-sm" onchange="onSectorChange()">
          <option value="">All Sectors</option>
        </select>
      </div>
      <div class="col-12 col-md-6">
        <label for="subSectorFilterSel" class="form-label fw-bold small mb-1" style="color: var(--text-2);">
          <i class="fa fa-map" style="color: var(--gold); margin-right: 4px;"></i> Sub Sector
        </label>
        <select id="subSectorFilterSel" class="form-control form-select shadow-sm" onchange="onSubSectorChange()">
          <option value="">All Sub Sectors</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Ashara Ohbat Status -->
  <h4 class="section-title">Ashara Ohbat Status</h4>
  <div class="stats-grid" id="ohbatStatusGrid">
    <?php foreach ($stats['LeaveStatus'] as $status => $count):
      $statusLabel = $status ?: 'No Status';
      if (in_array(strtolower(trim($statusLabel)), ['bed ridden', 'not in town', 'married outcaste', 'wafaat'])) {
        continue;
      }
    ?>
      <div class="stats-card status-card-btn" data-status="<?= htmlspecialchars($statusLabel) ?>"
           onclick="clickStatusCard('<?= addslashes($statusLabel) ?>')" style="cursor: pointer;">
        <h5><?= $statusLabel ?></h5>
        <div class="stats-value"><?= $count ?></div>
      </div>
    <?php endforeach; ?>

    <?php if (empty($stats['LeaveStatus'])): ?>
      <div class="stats-card">
        <h5 class="text-muted">No leave status</h5>
        <div class="stats-value">0</div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Filter Section -->
  <div class="filter-section mt-2">
    <div class="row">
      <div class="col-md-8">
        <input type="text" id="searchInput" placeholder="Search by name, ITS, mobile, or status..."
          class="form-control shadow-sm" oninput="performSearch()">
      </div>
      <div class="col-md-4 mt-2 mt-md-0">
        <select id="statusFilter" class="form-control form-select shadow-sm" onchange="filterByStatus()">
          <option value="">All Statuses</option>
          <option value="no-status">No Status</option>
          <option value="Will attend all 9 Days">Will attend all 9 Days</option>
          <option value="Not answering calls or messages">Not answering calls or messages</option>
          <option value="Musaaid didn't Contacted Yet">Musaaid didn't Contacted Yet</option>
          <option value="Will attend few Days only">Will attend few Days only</option>
          <option value="Will not attend any Day">Will not attend any Day</option>
          <option value="Ashara with Maula tus">Ashara with Maula tus</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Main Data Table -->
  <h4 class="section-title">Member Details</h4>
  <div class="table-responsive">
    <table class="table table-bordered table-hover" id="userData">
      <thead>
        <tr>
          <th>S.No.</th>
          <th onclick="sortTable('ITS')">ITS</th>
          <th onclick="sortTable('HOF_FM_TYPE')">Type</th>
          <th onclick="sortTable('HOF_ID')">HOF</th>
          <th onclick="sortTable('Full_Name')">Name</th>
          <th onclick="sortTable('Age')">Age</th>
          <th onclick="sortTable('Mobile')">Mobile</th>
          <th onclick="sortTable('Sector')">Sector</th>
          <th onclick="sortTable('Sub_Sector')">Sub</th>
          <th onclick="sortTable('LeaveStatus')">Ohbat Status</th>
          <th onclick="sortTable('Comment')">Comment</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="userTableBody"></tbody>
    </table>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="userDetailsForm">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Edit Member Details</h5>
          <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa-solid fa-times text-white"></i></button>
        </div>
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
          <div class="container-fluid">
            <div id="userDetailsFields" class="row g-3"></div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="ITS" id="modal_ITS">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Details</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Year switcher
  (function(){
    var sel = document.getElementById('yearSelect');
    if(sel){
      sel.addEventListener('change', function(){
        var url = new URL(window.location.href);
        url.searchParams.set('year', this.value);
        window.location.href = url.toString();
      });
    }
  })();

  const originalData      = <?= json_encode($users) ?>;
  const sectorIncharges   = <?= json_encode($stats['Sectors'] ?? []) ?>;
  const loggedInSector    = '<?= isset($current_sector)     ? $current_sector     : "" ?>';
  const loggedInSubSector = '<?= isset($current_sub_sector) ? $current_sub_sector : "" ?>';

  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"]/g, function(s) {
      return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[s]);
    });
  }

  let sortDirection        = {};
  let currentSectorFilter    = null;
  let currentSubSectorFilter = null;
  let currentStatusFilter    = null;

  // ── Init ──────────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', function() {
    initSectorDropdowns();
    updateMemberSummary(originalData);
    updateOhbatStatusGrid(originalData);
    updateUserTable(originalData);

    const urlParams = new URLSearchParams(window.location.search);
    const statusParam = urlParams.get('status');
    if (statusParam) {
      const filterSelect = document.getElementById('statusFilter');
      if (filterSelect) { filterSelect.value = statusParam; filterByStatus(); }
    }
  });

  // ── Search / filter pipeline ───────────────────────────────
  function performSearch() {
    const keyword = document.getElementById('searchInput').value.toLowerCase().trim();

    // Scoped = filtered by sector/subsector only (used for status grid)
    let scoped = originalData;
    if (currentSectorFilter)    scoped = scoped.filter(u => u.Sector    === currentSectorFilter);
    if (currentSubSectorFilter) scoped = scoped.filter(u => u.Sub_Sector === currentSubSectorFilter);

    // Table data = scoped + status + keyword
    let tableData = scoped;
    if (currentStatusFilter) {
      if (currentStatusFilter === 'no-status') {
        tableData = tableData.filter(u => !u.LeaveStatus || u.LeaveStatus.trim() === "Musaaid didn't Contacted Yet");
      } else {
        tableData = tableData.filter(u => u.LeaveStatus === currentStatusFilter);
      }
    }
    if (keyword) {
      const fields = ['ITS','HOF_FM_TYPE','HOF_ID','Full_Name','Name','Mobile','Sector','Sub_Sector','LeaveStatus','Comment'];
      tableData = tableData.filter(u => fields.some(f => (u[f] ? u[f].toString().toLowerCase() : '').includes(keyword)));
    }

    updateMemberSummary(scoped);
    updateOhbatStatusGrid(scoped);
    updateUserTable(tableData);
  }

  // ── Status filter ──────────────────────────────────────────
  function filterByStatus() {
    const status = document.getElementById('statusFilter').value;
    currentStatusFilter = status || null;
    document.querySelectorAll('.status-card-btn').forEach(card => {
      const mv = (card.dataset.status === 'No Status') ? 'no-status' : card.dataset.status;
      card.classList.toggle('active', mv === status);
    });
    performSearch();
  }

  function clickStatusCard(statusValue) {
    const filterSelect = document.getElementById('statusFilter');
    const targetValue  = (statusValue === 'No Status') ? 'no-status' : statusValue;
    filterSelect.value = (filterSelect.value === targetValue) ? '' : targetValue;
    filterByStatus();
    filterSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  // ── Ohbat status grid (dynamic) ────────────────────────────
  function updateOhbatStatusGrid(filtered) {
    const possibleStatuses = [
      'Will attend all 9 Days',
      'Not answering calls or messages',
      "Musaaid didn't Contacted Yet",
      'Will attend few Days only',
      'Will not attend any Day',
      'Ashara with Maula tus'
    ];
    const statusCounts = {};
    possibleStatuses.forEach(st => statusCounts[st] = 0);

    filtered.forEach(user => {
      let st = user.LeaveStatus || "Musaaid didn't Contacted Yet";
      if (st === 'Unknown') st = "Musaaid didn't Contacted Yet";
      if (['bed ridden','not in town','married outcaste','wafaat'].includes(st.toLowerCase().trim())) return;
      if (statusCounts[st] !== undefined) statusCounts[st]++;
      else statusCounts[st] = 1;
    });

    const grid = document.getElementById('ohbatStatusGrid');
    grid.innerHTML = '';
    Object.keys(statusCounts).forEach(statusLabel => {
      const isActive = (currentStatusFilter === statusLabel);
      const cardDiv = document.createElement('div');
      cardDiv.className = `stats-card status-card-btn${isActive ? ' active' : ''}`;
      cardDiv.dataset.status = statusLabel;
      cardDiv.style.cursor = 'pointer';
      cardDiv.onclick = () => clickStatusCard(statusLabel);
      cardDiv.innerHTML = `<h5>${escapeHtml(statusLabel)}</h5><div class="stats-value">${statusCounts[statusLabel]}</div>`;
      grid.appendChild(cardDiv);
    });
  }

  // ── Member summary bar ─────────────────────────────────────
  function updateMemberSummary(users) {
    const totalNoLeave = users.filter(u => !u.LeaveStatus || u.LeaveStatus.trim() === "Musaaid didn't Contacted Yet").length;
    document.getElementById('totalSectorCard').innerHTML = `
      <div class="alert alert-info p-2 mb-3 fs-5">
        <strong>Total Members:</strong> ${users.length}
        <span class="badge bg-warning text-dark ms-2 fs-6">Not Contacted: ${totalNoLeave}</span>
      </div>`;
  }

  // ── Sector dropdowns ───────────────────────────────────────
  function initSectorDropdowns() {
    const sectorSel    = document.getElementById('sectorFilterSel');
    const subSectorSel = document.getElementById('subSectorFilterSel');
    if (!sectorSel || !subSectorSel) return;

    const sectors = [...new Set(originalData.map(u => u.Sector).filter(Boolean))].sort();
    sectorSel.innerHTML = '<option value="">All Sectors</option>';
    sectors.forEach(sec => {
      const opt = document.createElement('option');
      opt.value = sec; opt.textContent = sec;
      sectorSel.appendChild(opt);
    });

    if (loggedInSector) {
      sectorSel.value    = loggedInSector;
      sectorSel.disabled = true;
      currentSectorFilter = loggedInSector;
    }

    updateSubSectorDropdown();

    if (loggedInSubSector) {
      subSectorSel.value    = loggedInSubSector;
      subSectorSel.disabled = true;
      currentSubSectorFilter = loggedInSubSector;
    }

    performSearch();
  }

  function updateSubSectorDropdown() {
    const sectorSel    = document.getElementById('sectorFilterSel');
    const subSectorSel = document.getElementById('subSectorFilterSel');
    if (!sectorSel || !subSectorSel) return;

    const selectedSector = sectorSel.value;
    subSectorSel.innerHTML = '<option value="">All Sub Sectors</option>';

    if (selectedSector) {
      const subs = [...new Set(originalData
        .filter(u => u.Sector === selectedSector)
        .map(u => u.Sub_Sector).filter(Boolean)
      )].sort();
      subs.forEach(sub => {
        const opt = document.createElement('option');
        opt.value = sub; opt.textContent = sub;
        subSectorSel.appendChild(opt);
      });
    }
  }

  function onSectorChange() {
    currentSectorFilter    = document.getElementById('sectorFilterSel').value || null;
    currentSubSectorFilter = null;
    updateSubSectorDropdown();
    performSearch();
  }

  function onSubSectorChange() {
    currentSubSectorFilter = document.getElementById('subSectorFilterSel').value || null;
    performSearch();
  }

  // ── Table render ───────────────────────────────────────────
  function updateUserTable(users) {
    const body = document.getElementById('userTableBody');
    body.innerHTML = '';

    if (!users.length) {
      const row = body.insertRow();
      const cell = row.insertCell();
      cell.colSpan = 12;
      cell.className = 'text-center py-4 text-muted';
      cell.textContent = 'No members found matching your criteria';
      return;
    }

    users.forEach((user, index) => {
      const row = body.insertRow();
      let rowClass = '';
      if (!user.LeaveStatus || user.LeaveStatus.trim() === "Musaaid didn't Contacted Yet") rowClass += ' no-leave';
      if (user.HOF_FM_TYPE === 'HOF') rowClass += ' hof-row';
      else if (user.HOF_FM_TYPE === 'FM') rowClass += ' fm-row';
      row.className = rowClass;

      row.insertCell().textContent = index + 1;
      row.insertCell().textContent = user.ITS || '';

      const typeCell = row.insertCell();
      if (user.HOF_FM_TYPE === 'HOF')      typeCell.innerHTML = '<span class="badge bg-primary">HOF</span>';
      else if (user.HOF_FM_TYPE === 'FM')  typeCell.innerHTML = '<span class="badge bg-info text-dark">FM</span>';

      row.insertCell().textContent = user.HOF_ID  || '';
      row.insertCell().textContent = user.Full_Name || user.Name || '';
      row.insertCell().textContent = user.Age      || '';
      row.insertCell().textContent = user.Mobile   || '';
      row.insertCell().textContent = user.Sector   || '';
      row.insertCell().textContent = user.Sub_Sector || '';

      const statusCell = row.insertCell();
      if (user.LeaveStatus) {
        const badgeMap = {
          'Will attend all 9 Days':          'bg-success',
          'Will attend few Days only':       'bg-warning text-dark',
          'Will not attend any Day':         'bg-danger',
          'Not answering calls or messages': 'bg-dark text-white',
          "Musaaid didn't Contacted Yet":    'bg-secondary text-white',
          'Ashara with Maula tus':           'bg-primary',
          'Bed Ridden':                      'bg-danger',
          'Not in Town':                     'bg-info text-dark',
          'Married Outcaste':                'bg-light text-muted border',
          'Wafaat':                          'bg-dark text-white',
        };
        const cls = badgeMap[user.LeaveStatus.trim()] || 'bg-secondary text-white';
        statusCell.innerHTML = `<span class="badge ${cls} badge-status">${escapeHtml(user.LeaveStatus)}</span>`;
      }

      const commentCell = row.insertCell();
      if (user.Comment && user.Comment.length > 30) {
        commentCell.textContent = user.Comment.substring(0, 27) + '...';
        commentCell.title = user.Comment;
      } else {
        commentCell.textContent = user.Comment || '';
      }

      const actionCell = row.insertCell();
      const btn = document.createElement('button');
      btn.className = 'btn btn-sm btn-primary action-btn';
      btn.textContent = 'Edit';
      btn.onclick = () => openModal(user);
      actionCell.appendChild(btn);

      row.addEventListener('click', function(e) {
        if (!e.target.closest('a, button, input, select, textarea, label')) {
          window.location.href = '<?php echo base_url($view_member_base); ?>' + user.ITS;
        }
      });
    });
  }

  // ── Sort ───────────────────────────────────────────────────
  function sortTable(col) {
    const dir = sortDirection[col] === 'asc' ? 'desc' : 'asc';
    sortDirection[col] = dir;

    let filtered = [...originalData];
    if (currentSectorFilter)    filtered = filtered.filter(u => u.Sector    === currentSectorFilter);
    if (currentSubSectorFilter) filtered = filtered.filter(u => u.Sub_Sector === currentSubSectorFilter);
    if (currentStatusFilter) {
      if (currentStatusFilter === 'no-status') {
        filtered = filtered.filter(u => !u.LeaveStatus || u.LeaveStatus.trim() === "Musaaid didn't Contacted Yet");
      } else {
        filtered = filtered.filter(u => u.LeaveStatus === currentStatusFilter);
      }
    }
    const keyword = document.getElementById('searchInput').value.toLowerCase().trim();
    if (keyword) {
      const fields = ['ITS','HOF_FM_TYPE','HOF_ID','Full_Name','Name','Mobile','Sector','Sub_Sector','LeaveStatus','Comment'];
      filtered = filtered.filter(u => fields.some(f => (u[f] ? u[f].toString().toLowerCase() : '').includes(keyword)));
    }

    filtered.sort((a, b) => {
      const va = (a[col] || '').toString().toLowerCase();
      const vb = (b[col] || '').toString().toLowerCase();
      return dir === 'asc' ? va.localeCompare(vb) : vb.localeCompare(va);
    });

    updateUserTable(filtered);
  }

  // ── Open modal ─────────────────────────────────────────────
  function openModal(user) {
    const modal     = new bootstrap.Modal(document.getElementById('userDetailsModal'));
    const container = document.getElementById('userDetailsFields');
    container.innerHTML = '';

    const leaveOptions = [
      'Will attend all 9 Days',
      'Not answering calls or messages',
      "Musaaid didn't Contacted Yet",
      'Will attend few Days only',
      'Will not attend any Day',
      'Ashara with Maula tus',
    ];

    const leaveCol = document.createElement('div');
    leaveCol.className = 'col-12 mb-3';
    leaveCol.innerHTML = `
      <label class="form-label fw-bold">Ohbat Status</label>
      <select name="LeaveStatus" id="LeaveStatus" class="form-control form-select">
        <option value="">-- Select Ohbat --</option>
        ${leaveOptions.map(opt => `<option value="${opt}" ${user.LeaveStatus === opt ? 'selected' : ''}>${opt}</option>`).join('')}
      </select>`;
    container.appendChild(leaveCol);

    const commentCol = document.createElement('div');
    commentCol.className = 'col-12 mb-3';
    commentCol.innerHTML = `
      <label class="form-label fw-bold">Comments</label>
      <textarea name="Comment" id="Comment" class="form-control" rows="4"
                placeholder="Add any additional comments here...">${escapeHtml(user.Comment || '')}</textarea>`;
    container.appendChild(commentCol);

    const infoCol = document.createElement('div');
    infoCol.className = 'col-12';
    infoCol.innerHTML = `
      <div class="card bg-light p-3">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Name:</strong> ${escapeHtml(user.Full_Name || user.Name || '')}</p>
            <p><strong>ITS:</strong> ${escapeHtml(user.ITS || '')}</p>
            <p><strong>HOF ID:</strong> ${escapeHtml(user.HOF_ID || '')}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Age:</strong> ${escapeHtml(user.Age || '')}</p>
            <p><strong>Mobile:</strong> ${escapeHtml(user.Mobile || '')}</p>
            <p><strong>Sector:</strong> ${escapeHtml(user.Sector || '')} ${user.Sub_Sector ? '(' + escapeHtml(user.Sub_Sector) + ')' : ''}</p>
          </div>
        </div>
      </div>`;
    container.appendChild(infoCol);

    document.getElementById('modal_ITS').value = user.ITS;
    modal.show();
  }

  // ── Form submit ────────────────────────────────────────────
  document.getElementById('userDetailsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    try {
      var ySel = document.getElementById('yearSelect');
      if (ySel && !formData.has('year')) formData.append('year', ySel.value);
    } catch(err) {}

    fetch('<?php echo base_url('MasoolMusaid/update_ashara_ohbat_details') ?>', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('User details updated successfully');
        location.reload();
      } else {
        alert('Update failed: ' + (data.message || 'Please try again'));
      }
    })
    .catch(error => {
      alert('Error occurred: ' + error.message);
    });
  });
</script>