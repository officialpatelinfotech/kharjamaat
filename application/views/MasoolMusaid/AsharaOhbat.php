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
  .overview-card.active {
    border-color: var(--gold) !important;
  }
  .overview-card.active::after {
    transform: scaleX(1) !important;
  }
  .overview-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: inline-flex; align-items: center; justify-content: center;
    background: var(--gold-muted); color: var(--gold); font-size: .95rem; flex-shrink: 0;
  }
  .overview-body { display: flex; flex-direction: column; min-width: 0; }
  .overview-title { font-size: .72rem; color: var(--text-3); font-weight: 600; margin: 0; line-height: 1.3; }
  .overview-value {
    font-size: 1.2rem; font-weight: 800; color: var(--text-1); line-height: 1.1; margin: 3px 0 0;
  }

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

  .hof-row {
    background-color: #e3f2fd !important;
    border-left: 4px solid #1976d2;
  }

  .fm-row {
    background-color: #f3e5f5 !important;
    border-left: 4px solid #8e24aa;
  }

  .no-leave {
    background-color: #ffebee !important;
    border-left: 4px solid #d32f2f;
  }

  .no-leave-count {
    color: #d32f2f;
    font-weight: bold;
  }

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

  .badge-status {
    font-size: 0.8em;
    padding: 5px 8px;
    border-radius: 12px;
  }

  .action-btn {
    min-width: 80px;
  }

  #userData tbody tr {
    cursor: pointer;
  }

  @media (max-width: 768px) {
    .stats-grid, .sector-grid {
      grid-template-columns: repeat(2, 1fr);
    }

    .table-responsive {
      max-height: none;
    }
  }

  .sector-row {
    background-color: #f8f9fa;
  }

  .subsector-row {
    background-color: white;
  }

  .subsector-row:hover {
    background-color: #f1f1f1;
  }

  .table-primary {
    background-color: #e7f1ff !important;
  }

  .pl-4 {
    padding-left: 1.5rem !important;
  }

  .card {
    background-color: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
  }

  .btn-primary {
    background-color: var(--gold);
    border-color: var(--gold);
  }
  .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
    background-color: #78520a !important;
    border-color: #78520a !important;
  }

  .modal-header.bg-primary {
    background-color: var(--gold) !important;
  }

  /* ── Surface card ── */
  .chart-container {
    background: var(--surface);
    border-radius: 16px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    padding: 18px 20px;
    margin-bottom: 16px;
    position: relative; overflow: hidden;
    transition: box-shadow .2s;
  }
  .chart-container:hover { box-shadow: var(--shadow); }
  .chart-container.compact { padding: 14px 16px; }

  /* ── Section header ── */
  .section-header-standard {
    display: flex; align-items: center; justify-content: space-between;
    padding-bottom: 10px; margin-bottom: 14px;
    border-bottom: 1.5px solid var(--border-light);
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
       class="btn btn-outline-secondary"
       title="Back"
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

  <!-- Sector Cards (Moved Above Stats) -->
  <div class="mb-2 fw-bold" id="totalSectorCard"></div>
  <?php if (!$is_sub_sector): ?>
  <div class="row">
    <div class="col-12">
      <div class="chart-container sector-block">
        <div class="section-header-standard">
          <h4 class="section-title"><i class="fa fa-map-marker"></i> Sector-wise Members</h4>
          <button class="collapse-toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSectorsAshara" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
        <div class="collapse show" id="collapseSectorsAshara">
          <div id="sectorCardsContainer" class="row"></div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Stats Section -->
  <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3 mt-4">
    <h4 class="m-0" style="color: var(--text-2); font-size: 1.1rem; font-weight: 800;">Overview Statistics</h4>
    <button type="button" class="btn btn-outline-danger btn-sm" id="resetStatsBtn" onclick="resetFiltersAndStats()" style="display: none; border-radius: 20px; font-weight: 600;">
      <i class="fa-solid fa-arrows-rotate me-1"></i> Reset Sector & Stats
    </button>
  </div>
  
  <div class="stats-grid">
    <a id="link_total_members" href="<?= $directory_url . '?year=' . $selected_year . '&filter=all' ?>" style="text-decoration: none; color: inherit; display: block;">
      <div class="stats-card bg-light">
        <h5>Total Members</h5>
        <div class="stats-value" id="stat_total_members"><?= count($users) ?></div>
      </div>
    </a>
    <a id="link_hof" href="<?= $directory_url . '?year=' . $selected_year . '&filter=hof_fm_type&value=HOF' ?>" style="text-decoration: none; color: inherit; display: block;">
      <div class="stats-card bg-light">
        <h5>HOF (Head of Family)</h5>
        <div class="stats-value" id="stat_hof"><?= $stats['HOF'] ?></div>
      </div>
    </a>
    <a id="link_fm" href="<?= $directory_url . '?year=' . $selected_year . '&filter=hof_fm_type&value=FM' ?>" style="text-decoration: none; color: inherit; display: block;">
      <div class="stats-card bg-light">
        <h5>FM (Family Members)</h5>
        <div class="stats-value" id="stat_fm"><?= $stats['FM'] ?></div>
      </div>
    </a>
    <a id="link_mardo" href="<?= $directory_url . '?year=' . $selected_year . '&filter=gender&value=male' ?>" style="text-decoration: none; color: inherit; display: block;">
      <div class="stats-card bg-light">
        <h5>Males</h5>
        <div class="stats-value" id="stat_mardo"><?= $stats['Mardo'] ?></div>
      </div>
    </a>
    <a id="link_bairo" href="<?= $directory_url . '?year=' . $selected_year . '&filter=gender&value=female' ?>" style="text-decoration: none; color: inherit; display: block;">
      <div class="stats-card bg-light">
        <h5>Females</h5>
        <div class="stats-value" id="stat_bairo"><?= $stats['Bairo'] ?></div>
      </div>
    </a>
    <a id="link_age_0_4" href="<?= $directory_url . '?year=' . $selected_year . '&filter=age_range&min=0&max=4' ?>" style="text-decoration: none; color: inherit; display: block;">
      <div class="stats-card bg-light">
        <h5>Age 0-4</h5>
        <div class="stats-value" id="stat_age_0_4"><?= $stats['Age_0_4'] ?></div>
      </div>
    </a>
    <a id="link_age_5_15" href="<?= $directory_url . '?year=' . $selected_year . '&filter=age_range&min=5&max=15' ?>" style="text-decoration: none; color: inherit; display: block;">
      <div class="stats-card bg-light">
        <h5>Age 5-15</h5>
        <div class="stats-value" id="stat_age_5_15"><?= $stats['Age_5_15'] ?></div>
      </div>
    </a>
    <a id="link_age_16_25" href="<?= $directory_url . '?year=' . $selected_year . '&filter=age_range&min=16&max=25' ?>" style="text-decoration: none; color: inherit; display: block;">
      <div class="stats-card bg-light">
        <h5>Age 16-25</h5>
        <div class="stats-value" id="stat_age_16_25"><?= $stats['Age_16_25'] ?></div>
      </div>
    </a>
    <a id="link_age_26_65" href="<?= $directory_url . '?year=' . $selected_year . '&filter=age_range&min=26&max=65' ?>" style="text-decoration: none; color: inherit; display: block;">
      <div class="stats-card bg-light">
        <h5>Age 26-65</h5>
        <div class="stats-value" id="stat_age_26_65"><?= $stats['Age_26_65'] ?></div>
      </div>
    </a>
    <a id="link_age_above_65" href="<?= $directory_url . '?year=' . $selected_year . '&filter=age_range&min=66' ?>" style="text-decoration: none; color: inherit; display: block;">
      <div class="stats-card bg-light">
        <h5>Above 65</h5>
        <div class="stats-value" id="stat_age_above_65"><?= $stats['Buzurgo'] ?></div>
      </div>
    </a>
  </div>

  <!-- Leave Status Section -->
  <h4 class="section-title">Ashara Ohbat Status</h4>
  <div class="stats-grid" id="ohbatStatusGrid">
    <?php foreach ($stats['LeaveStatus'] as $status => $count):
      $statusLabel = $status ?: 'No Status';
      if (in_array(strtolower(trim($statusLabel)), ['bed ridden', 'not in town', 'married outcaste', 'wafaat'])) {
        continue;
      }
      $statusClass = str_replace(' ', '-', strtolower($statusLabel));
    ?>
      <div class="stats-card status-card-btn" data-status="<?= htmlspecialchars($statusLabel) ?>" onclick="clickStatusCard('<?= addslashes($statusLabel) ?>')" style="cursor: pointer;">
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
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel"
  aria-hidden="true">
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
  // Year switcher: reload page with ?year=
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

  const originalData = <?= json_encode($users) ?>;
  const sectorIncharges = <?= json_encode($stats['Sectors'] ?? []) ?>;
  const loggedInSector = '<?= isset($current_sector) ? $current_sector : "" ?>';
  const loggedInSubSector = '<?= isset($current_sub_sector) ? $current_sub_sector : "" ?>';

  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"]/g, function(s) {
      return ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;'
      } [s]);
    });
  }
  let sortDirection = {};
  let currentSectorFilter = null;
  let currentSubSectorFilter = null;
  let currentStatusFilter = null;

  // Initialize the page
  document.addEventListener('DOMContentLoaded', function() {
    initSectorCards();
    updateMemberSummary(originalData);
    updateStats(originalData);
    updateUserTable(originalData);
  });

  function performSearch() {
    const keyword = document.getElementById('searchInput').value.toLowerCase().trim();

    let filtered = originalData;

    // Apply sector filter if active
    if (currentSectorFilter) {
      filtered = filtered.filter(user => user.Sector === currentSectorFilter);
    }

    // Apply sub-sector filter if active
    if (currentSubSectorFilter) {
      filtered = filtered.filter(user => user.Sub_Sector === currentSubSectorFilter);
    }

    // Apply status filter if active
    if (currentStatusFilter) {
      if (currentStatusFilter === 'no-status') {
        filtered = filtered.filter(user => !user.LeaveStatus || user.LeaveStatus.trim() === "Musaaid didn't Contacted Yet");
      } else {
        filtered = filtered.filter(user => user.LeaveStatus === currentStatusFilter);
      }
    }

    // Apply search keyword
    if (keyword) {
      filtered = filtered.filter(user => {
        const searchFields = [
          'ITS', 'HOF_FM_TYPE', 'HOF_ID', 'Full_Name',
          'Name', 'Mobile', 'Sector', 'Sub_Sector',
          'LeaveStatus', 'Comment'
        ];

        return searchFields.some(field => {
          const value = user[field] ? user[field].toString().toLowerCase() : '';
          return value.includes(keyword);
        });
      });
    }

    updateMemberSummary(filtered);
    updateStats(filtered);
    updateUserTable(filtered);

    // Toggle Reset Button visibility
    const resetBtn = document.getElementById('resetStatsBtn');
    if (resetBtn) {
      if (currentSectorFilter || currentStatusFilter || keyword) {
        resetBtn.style.display = 'inline-block';
      } else {
        resetBtn.style.display = 'none';
      }
    }
  }

  function filterByStatus() {
    const status = document.getElementById('statusFilter').value;
    currentStatusFilter = status || null;

    // Synchronize active style on cards
    document.querySelectorAll('.status-card-btn').forEach(card => {
      const cardStatus = card.dataset.status;
      const mappedVal = (cardStatus === "No Status") ? "no-status" : cardStatus;
      if (mappedVal === status) {
        card.classList.add('active');
      } else {
        card.classList.remove('active');
      }
    });

    performSearch();
  }

  function clickStatusCard(statusValue) {
    const filterSelect = document.getElementById('statusFilter');
    const targetValue = (statusValue === "No Status") ? "no-status" : statusValue;

    if (filterSelect.value === targetValue) {
      filterSelect.value = "";
    } else {
      filterSelect.value = targetValue;
    }
    filterByStatus();

    const target = document.getElementById('statusFilter');
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }

  function updateStats(filtered) {
    let hof = 0, fm = 0, mardo = 0, bairo = 0;
    let age_0_4 = 0, age_5_15 = 0, age_16_25 = 0, age_26_65 = 0, buzurgo = 0;
    
    filtered.forEach(user => {
      if (user.HOF_FM_TYPE === 'HOF') hof++;
      if (user.HOF_FM_TYPE === 'FM') fm++;
      
      const gender = (user.Gender || '').toLowerCase();
      if (gender === 'male') mardo++;
      if (gender === 'female') bairo++;
      
      const age = parseInt(user.Age || 0);
      if (age >= 0 && age <= 4) age_0_4++;
      else if (age >= 5 && age <= 15) age_5_15++;
      else if (age >= 16 && age <= 25) age_16_25++;
      else if (age >= 26 && age <= 65) age_26_65++;
      else if (age > 65) buzurgo++;
    });

    document.getElementById('stat_total_members').textContent = filtered.length;
    document.getElementById('stat_hof').textContent = hof;
    document.getElementById('stat_fm').textContent = fm;
    document.getElementById('stat_mardo').textContent = mardo;
    document.getElementById('stat_bairo').textContent = bairo;
    document.getElementById('stat_age_0_4').textContent = age_0_4;
    document.getElementById('stat_age_5_15').textContent = age_5_15;
    document.getElementById('stat_age_16_25').textContent = age_16_25;
    document.getElementById('stat_age_26_65').textContent = age_26_65;
    document.getElementById('stat_age_above_65').textContent = buzurgo;

    // Update link hrefs based on currentSectorFilter
    const baseDirUrl = '<?= $directory_url ?>';
    const selYear = '<?= $selected_year ?>';
    
    function updateLinkHref(id, extraParams) {
      const link = document.getElementById(id);
      if (!link) return;
      
      let params = new URLSearchParams();
      params.set('year', selYear);
      
      Object.keys(extraParams).forEach(k => params.set(k, extraParams[k]));
      
      if (currentSectorFilter) {
        params.set('sector', currentSectorFilter);
      }
      if (currentSubSectorFilter) {
        params.set('subsector', currentSubSectorFilter);
      }
      
      link.href = baseDirUrl + '?' + params.toString();
    }

    updateLinkHref('link_total_members', { filter: 'all' });
    updateLinkHref('link_hof', { filter: 'hof_fm_type', value: 'HOF' });
    updateLinkHref('link_fm', { filter: 'hof_fm_type', value: 'FM' });
    updateLinkHref('link_mardo', { filter: 'gender', value: 'male' });
    updateLinkHref('link_bairo', { filter: 'gender', value: 'female' });
    updateLinkHref('link_age_0_4', { filter: 'age_range', min: '0', max: '4' });
    updateLinkHref('link_age_5_15', { filter: 'age_range', min: '5', max: '15' });
    updateLinkHref('link_age_16_25', { filter: 'age_range', min: '16', max: '25' });
    updateLinkHref('link_age_26_65', { filter: 'age_range', min: '26', max: '65' });
    updateLinkHref('link_age_above_65', { filter: 'age_range', min: '66' });

    // 2. Ashara Ohbat Status
    const statusCounts = {};
    const possibleStatuses = [
      'Will attend all 9 Days',
      'Not answering calls or messages',
      "Musaaid didn't Contacted Yet",
      'Will attend few Days only',
      'Will not attend any Day',
      'Ashara with Maula tus'
    ];
    possibleStatuses.forEach(st => statusCounts[st] = 0);
    
    filtered.forEach(user => {
      let st = user.LeaveStatus || "Musaaid didn't Contacted Yet";
      if (st === 'Unknown') st = "Musaaid didn't Contacted Yet";
      
      if (['bed ridden', 'not in town', 'married outcaste', 'wafaat'].includes(st.toLowerCase().trim())) {
        return;
      }
      if (statusCounts[st] !== undefined) {
        statusCounts[st]++;
      } else {
        statusCounts[st] = 1;
      }
    });

    const statusGrid = document.getElementById('ohbatStatusGrid');
    statusGrid.innerHTML = '';
    
    Object.keys(statusCounts).forEach(statusLabel => {
      const count = statusCounts[statusLabel];
      const isCardActive = (currentStatusFilter === statusLabel);
      
      const cardDiv = document.createElement('div');
      cardDiv.className = `stats-card status-card-btn ${isCardActive ? 'active' : ''}`;
      cardDiv.dataset.status = statusLabel;
      cardDiv.style.cursor = 'pointer';
      cardDiv.onclick = () => clickStatusCard(statusLabel);
      
      cardDiv.innerHTML = `
        <h5>${escapeHtml(statusLabel)}</h5>
        <div class="stats-value">${count}</div>
      `;
      statusGrid.appendChild(cardDiv);
    });
  }

  function resetFiltersAndStats() {
    currentSectorFilter = null;
    currentSubSectorFilter = null;
    currentStatusFilter = null;
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    
    document.querySelectorAll('#sectorCardsContainer .overview-card').forEach(card => {
      card.classList.remove('active');
    });

    document.querySelectorAll('.status-card-btn').forEach(card => {
      card.classList.remove('active');
    });

    performSearch();
  }

  function initSectorCards() {
    const container = document.getElementById('sectorCardsContainer');
    if (!container) return;
    container.className = 'row';
    container.innerHTML = '';

    const inchargeMap = {};
    const subInchargeMap = {};
    
    sectorIncharges.forEach(item => {
      const secName = item.Sector || 'Unknown';
      inchargeMap[secName.toLowerCase()] = item;
      
      const subs = item.sub_sectors || [];
      subs.forEach(sub => {
        const subKey = `${secName.toLowerCase()}_${(sub.Sub_Sector || '').toLowerCase()}`;
        subInchargeMap[subKey] = sub;
      });
    });

    let cardsToRender = [];

    if (loggedInSector) {
      // It's a sector or sub-sector login: show sub-sectors under loggedInSector
      const subSectorsFound = new Set();
      
      // 1. Check originalData (members actually in this scope)
      originalData.forEach(u => {
        if ((u.Sector || '').toLowerCase() === loggedInSector.toLowerCase()) {
          const sub = (u.Sub_Sector || '').trim();
          if (sub) {
            subSectorsFound.add(sub);
          }
        }
      });
      
      // 2. Check subInchargeMap for any other defined sub-sectors under loggedInSector
      Object.keys(subInchargeMap).forEach(key => {
        if (key.startsWith(loggedInSector.toLowerCase() + '_')) {
          const subName = subInchargeMap[key].Sub_Sector;
          if (subName) {
            subSectorsFound.add(subName);
          }
        }
      });

      // Filter subSectorsFound if a specific loggedInSubSector is set
      let subSectorsList = Array.from(subSectorsFound);
      if (loggedInSubSector) {
        subSectorsList = subSectorsList.filter(s => s.toLowerCase() === loggedInSubSector.toLowerCase());
      }
      
      // Sort sub-sectors alphabetically
      subSectorsList.sort();

      subSectorsList.forEach(subName => {
        // Calculate counts dynamically from originalData
        const members = originalData.filter(u => 
          (u.Sector || '').toLowerCase() === loggedInSector.toLowerCase() && 
          (u.Sub_Sector || '').toLowerCase() === subName.toLowerCase()
        );
        const hof = members.filter(u => u.HOF_FM_TYPE === 'HOF').length;
        const fm = members.filter(u => u.HOF_FM_TYPE === 'FM').length;
        
        const subKey = `${loggedInSector.toLowerCase()}_${subName.toLowerCase()}`;
        const subInchargeInfo = subInchargeMap[subKey] || {};
        
        cardsToRender.push({
          type: 'subsector',
          displayName: loggedInSector + ' ' + subName,
          Sector: loggedInSector,
          Sub_Sector: subName,
          hof: hof,
          fm: fm,
          inchargeName: subInchargeInfo.Sub_Sector_Incharge_Name || '',
          inchargeFemaleName: subInchargeInfo.Sub_Sector_Incharge_Female_Name || ''
        });
      });
    } else {
      // Admin/Amilsaheb/Umoor: show all sectors
      const sortedSectors = sectorIncharges.filter(item => {
        const sec = (item.Sector || '').trim();
        return sec !== '' && sec.toLowerCase() !== 'unassigned';
      });
      sortedSectors.sort((a, b) => {
        const totalA = parseInt(a.total || 0);
        const totalB = parseInt(b.total || 0);
        return totalB - totalA;
      });

      sortedSectors.forEach(itemData => {
        const sector = itemData.Sector || 'Unknown';
        const inchargeName = itemData.Sector_Incharge_Name || '';
        const inchargeFemaleName = itemData.Sector_Incharge_Female_Name || '';
        const subSectors = itemData.sub_sectors || [];
        const hof = parseInt(itemData.hof_count || 0);
        const fm = parseInt(itemData.fm_count || 0);

        cardsToRender.push({
          type: 'sector',
          displayName: sector,
          Sector: sector,
          Sub_Sector: null,
          hof: hof,
          fm: fm,
          inchargeName: inchargeName,
          inchargeFemaleName: inchargeFemaleName,
          sub_sectors: subSectors
        });
      });
    }

    cardsToRender.forEach(itemData => {
      const cid = 'collapseSectorIncharge_' + itemData.displayName.replace(/[^a-zA-Z0-9]/g, '_');

      const colDiv = document.createElement('div');
      colDiv.className = 'col-12 col-md-3 mb-3';

      const cardDiv = document.createElement('div');
      
      const isCardActive = (itemData.type === 'subsector') 
        ? (currentSectorFilter === itemData.Sector && currentSubSectorFilter === itemData.Sub_Sector)
        : (currentSectorFilter === itemData.Sector && !currentSubSectorFilter);

      cardDiv.className = `overview-card ${isCardActive ? 'active' : ''}`;
      cardDiv.dataset.sector = itemData.Sector;
      if (itemData.Sub_Sector) {
        cardDiv.dataset.subsector = itemData.Sub_Sector;
      }
      cardDiv.style.cssText = `
        display: flex;
        flex-direction: column;
        align-items: stretch;
        height: 100%;
        cursor: pointer;
      `;

      const mainLink = document.createElement('div');
      mainLink.style.cssText = 'display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit;';
      
      const iconDiv = document.createElement('div');
      iconDiv.className = 'overview-icon';
      iconDiv.style.cssText = 'background:#eaf4ee;color:#1a6645;';
      iconDiv.innerHTML = '<i class="fa fa-map-marker"></i>';

      const bodyDiv = document.createElement('div');
      bodyDiv.className = 'overview-body';
      bodyDiv.style.cssText = 'width:100%;';

      const titleSpan = document.createElement('span');
      titleSpan.className = 'overview-title';
      titleSpan.textContent = itemData.displayName;

      let inchargeHtml = '';
      if (itemData.inchargeName || itemData.inchargeFemaleName) {
        inchargeHtml = '<div style="font-size:.74rem;color:var(--text-3);margin:4px 0;text-align:left;">';
        if (itemData.inchargeName) {
          inchargeHtml += `<div style="margin-bottom:2px;"><i class="fa fa-male" style="color:#1d4ed8;margin-right:4px;"></i>${escapeHtml(itemData.inchargeName)}</div>`;
        }
        if (itemData.inchargeFemaleName) {
          inchargeHtml += `<div><i class="fa fa-female" style="color:#9d174d;margin-right:4px;"></i>${escapeHtml(itemData.inchargeFemaleName)}</div>`;
        }
        inchargeHtml += '</div>';
      }

      const valueSpan = document.createElement('span');
      valueSpan.className = 'overview-value';
      valueSpan.style.cssText = 'font-size:.95rem; font-weight: 800; color: var(--text-1); margin-top: 4px;';
      valueSpan.innerHTML = `HOF ${itemData.hof} &nbsp;·&nbsp; FM ${itemData.fm}`;

      bodyDiv.appendChild(titleSpan);
      if (itemData.inchargeName || itemData.inchargeFemaleName) {
        const temp = document.createElement('div');
        temp.innerHTML = inchargeHtml;
        bodyDiv.appendChild(temp.firstElementChild);
      }
      bodyDiv.appendChild(valueSpan);

      mainLink.appendChild(iconDiv);
      mainLink.appendChild(bodyDiv);
      cardDiv.appendChild(mainLink);

      cardDiv.onclick = (e) => {
        e.stopPropagation();
        if (itemData.type === 'subsector') {
          if (currentSectorFilter === itemData.Sector && currentSubSectorFilter === itemData.Sub_Sector) {
            currentSectorFilter = null;
            currentSubSectorFilter = null;
          } else {
            currentSectorFilter = itemData.Sector;
            currentSubSectorFilter = itemData.Sub_Sector;
          }
        } else {
          if (currentSectorFilter === itemData.Sector) {
            currentSectorFilter = null;
            currentSubSectorFilter = null;
          } else {
            currentSectorFilter = itemData.Sector;
            currentSubSectorFilter = null;
          }
        }
        
        // Toggle active style on cards
        document.querySelectorAll('#sectorCardsContainer .overview-card').forEach(card => {
          const cardSec = card.dataset.sector;
          const cardSub = card.dataset.subsector || '';
          
          const isFilterActive = (itemData.type === 'subsector') 
            ? (cardSec === currentSectorFilter && cardSub === currentSubSectorFilter)
            : (cardSec === currentSectorFilter && !currentSubSectorFilter);
            
          if (isFilterActive) {
            card.classList.add('active');
          } else {
            card.classList.remove('active');
          }
        });

        performSearch();
      };

      // Calculate status counts for this card's scope
      const cardMembers = originalData.filter(user => {
        const isSectorMatch = (user.Sector || '').toLowerCase() === itemData.Sector.toLowerCase();
        if (!isSectorMatch) return false;
        if (itemData.type === 'subsector') {
          return (user.Sub_Sector || '').toLowerCase() === (itemData.Sub_Sector || '').toLowerCase();
        }
        return true;
      });

      const statusCounts = {};
      const possibleStatuses = [
        'Will attend all 9 Days',
        'Not answering calls or messages',
        "Musaaid didn't Contacted Yet",
        'Will attend few Days only',
        'Will not attend any Day',
        'Ashara with Maula tus'
      ];
      possibleStatuses.forEach(st => statusCounts[st] = 0);

      cardMembers.forEach(user => {
        let st = user.LeaveStatus || "Musaaid didn't Contacted Yet";
        if (st === 'Unknown') st = "Musaaid didn't Contacted Yet";
        if (['bed ridden', 'not in town', 'married outcaste', 'wafaat'].includes(st.toLowerCase().trim())) {
          return;
        }
        if (statusCounts[st] !== undefined) {
          statusCounts[st]++;
        } else {
          statusCounts[st] = 1;
        }
      });

      let statusHtml = '<div style="margin-top:8px; padding-top:8px; border-top:1.5px dashed var(--border-light); font-size:.74rem; text-align:left;">';
      const statusLabelsMap = {
        'Will attend all 9 Days': { short: 'All 9 Days', color: '#1a6645', bg: '#eaf4ee' },
        'Ashara with Maula tus': { short: 'With Maula', color: '#b8860b', bg: '#f5e9c0' },
        'Will attend few Days only': { short: 'Few Days', color: '#b45309', bg: '#fff7ed' },
        "Musaaid didn't Contacted Yet": { short: 'Uncontacted', color: '#4b5563', bg: '#f3f4f6' },
        'Not answering calls or messages': { short: 'No Answer', color: '#b91c1c', bg: '#fef2f2' },
        'Will not attend any Day': { short: 'Not Attending', color: '#b91c1c', bg: '#fef2f2' }
      };

      let statusShown = false;
      Object.keys(statusLabelsMap).forEach(st => {
        const cnt = statusCounts[st] || 0;
        if (cnt > 0) {
          statusShown = true;
          const cfg = statusLabelsMap[st];
          statusHtml += `
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
              <span style="color:var(--text-2); font-weight:600;">${cfg.short}</span>
              <span style="background:${cfg.bg}; color:${cfg.color}; font-weight:800; padding:1px 6px; border-radius:10px; font-size:.65rem; min-width:20px; text-align:center;">${cnt}</span>
            </div>
          `;
        }
      });
      statusHtml += '</div>';

      if (statusShown) {
        const temp = document.createElement('div');
        temp.innerHTML = statusHtml;
        cardDiv.appendChild(temp.firstElementChild);
      }

      if (itemData.type === 'sector' && (itemData.sub_sectors.length > 0 || itemData.inchargeName)) {
        const collapseWrapper = document.createElement('div');
        collapseWrapper.style.cssText = 'margin-top:8px;border-top:1px solid var(--border-light);padding-top:5px;width:100%;';
        
        const collapseBtn = document.createElement('button');
        collapseBtn.className = 'btn btn-sm btn-link text-decoration-none w-100 text-start p-0 d-flex justify-content-between align-items-center';
        collapseBtn.style.cssText = 'color:var(--text-3);font-size:.74rem;';
        collapseBtn.type = 'button';
        collapseBtn.setAttribute('data-bs-toggle', 'collapse');
        collapseBtn.setAttribute('data-bs-target', '#' + cid);
        collapseBtn.innerHTML = '<span><i class="fa fa-info-circle"></i> View Incharges</span><i class="fa fa-chevron-down" style="font-size:.62rem;"></i>';
        
        const collapseContent = document.createElement('div');
        collapseContent.className = 'collapse';
        collapseContent.id = cid;

        let listHtml = '<ul style="list-style:none;padding:5px 0 0;margin:0;font-size:.75rem;color:var(--text-2);">';
        itemData.sub_sectors.forEach(sub => {
          listHtml += `<li style="padding:5px 6px;background:var(--bg);border-radius:6px;margin-bottom:3px;text-align:left;">
            <strong>${escapeHtml(sub.Sub_Sector)}</strong><br>`;
          if (sub.Sub_Sector_Incharge_Name) {
            listHtml += `<i class="fa fa-male" style="color:#1d4ed8;margin-right:3px;"></i>${escapeHtml(sub.Sub_Sector_Incharge_Name)}<br>`;
          }
          if (sub.Sub_Sector_Incharge_Female_Name) {
            listHtml += `<i class="fa fa-female" style="color:#9d174d;margin-right:3px;"></i>${escapeHtml(sub.Sub_Sector_Incharge_Female_Name)}`;
          }
          listHtml += '</li>';
        });
        itemData.sub_sectors.forEach(sub => {
        });
        listHtml += '</ul>';
        collapseContent.innerHTML = listHtml;

        collapseWrapper.appendChild(collapseBtn);
        collapseWrapper.appendChild(collapseContent);
        cardDiv.appendChild(collapseWrapper);

        collapseWrapper.addEventListener('click', (e) => {
          e.stopPropagation();
        });

        collapseContent.addEventListener('show.bs.collapse', (e) => {
          e.stopPropagation();
          collapseBtn.querySelector('.fa-chevron-down').style.transform = 'rotate(180deg)';
        });
        collapseContent.addEventListener('hide.bs.collapse', (e) => {
          e.stopPropagation();
          collapseBtn.querySelector('.fa-chevron-down').style.transform = '';
        });
      }

      colDiv.appendChild(cardDiv);
      container.appendChild(colDiv);
    });
  }

  function updateMemberSummary(users) {
    const totalNoLeave = users.filter(u => !u.LeaveStatus || u.LeaveStatus.trim() === "Musaaid didn't Contacted Yet").length;

    document.getElementById('totalSectorCard').innerHTML = `
        <div class="alert alert-info p-2 mb-3 fs-5">
            <strong>Total Members:</strong> ${users.length} 
            <span class="badge bg-warning text-dark ms-2 fs-6">Not Contacted: ${totalNoLeave}</span>
        </div>
    `;
  }

  function updateUserTable(users) {
    const body = document.getElementById('userTableBody');
    body.innerHTML = '';

    if (users.length === 0) {
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

      if (!user.LeaveStatus || user.LeaveStatus.trim() === "Musaaid didn't Contacted Yet") {
        rowClass += ' no-leave';
      }
      if (user.HOF_FM_TYPE === 'HOF') {
        rowClass += ' hof-row';
      } else if (user.HOF_FM_TYPE === 'FM') {
        rowClass += ' fm-row';
      }

      row.className = rowClass;

      // S.No.
      row.insertCell().textContent = index + 1;

      // ITS
      row.insertCell().textContent = user.ITS || '';

      // Type with badge
      const typeCell = row.insertCell();
      if (user.HOF_FM_TYPE === 'HOF') {
        typeCell.innerHTML = '<span class="badge bg-primary">HOF</span>';
      } else if (user.HOF_FM_TYPE === 'FM') {
        typeCell.innerHTML = '<span class="badge bg-info text-dark">FM</span>';
      }

      // HOF ID
      row.insertCell().textContent = user.HOF_ID || '';

      // Name
      row.insertCell().textContent = user.Full_Name || user.Name || '';

      // Age
      row.insertCell().textContent = user.Age || '';

      // Mobile
      row.insertCell().textContent = user.Mobile || '';

      // Sector
      row.insertCell().textContent = user.Sector || '';

      // Sub
      row.insertCell().textContent = user.Sub_Sector || '';

      // Status with colored badge
      const statusCell = row.insertCell();
      if (user.LeaveStatus) {
        let badgeClass = 'bg-secondary text-white';

        switch (user.LeaveStatus.trim()) {
          case 'Will attend all 9 Days':
            badgeClass = 'bg-success';
            break;
          case 'Will attend few Days only':
            badgeClass = 'bg-warning text-dark';
            break;
          case 'Will not attend any Day':
            badgeClass = 'bg-danger';
            break;
          case 'Not answering calls or messages':
            badgeClass = 'bg-dark text-white';
            break;
          case "Musaaid didn't Contacted Yet":
            badgeClass = 'bg-secondary text-white';
            break;
          case 'Bed Ridden':
            badgeClass = 'bg-danger-subtle text-dark';
            break;
          case 'Not in Town':
            badgeClass = 'bg-info text-dark';
            break;
          case 'Ashara with Maula tus':
            badgeClass = 'bg-primary';
            break;
          case 'Married Outcaste':
            badgeClass = 'bg-light text-muted border';
            break;
          case 'Wafaat':
            badgeClass = 'bg-black text-white';
            break;
          default:
            badgeClass = 'bg-secondary text-white';
        }

        statusCell.innerHTML = `<span class="badge ${badgeClass} badge-status">${user.LeaveStatus}</span>`;
      }


      // Comment (truncated if long)
      const commentCell = row.insertCell();
      if (user.Comment && user.Comment.length > 30) {
        commentCell.textContent = user.Comment.substring(0, 27) + '...';
        commentCell.title = user.Comment;
      } else {
        commentCell.textContent = user.Comment || '';
      }

      // Action button
      const actionCell = row.insertCell();
      const btn = document.createElement('button');
      btn.className = 'btn btn-sm btn-primary action-btn';
      btn.textContent = 'Edit';
      btn.onclick = () => openModal(user);
      actionCell.appendChild(btn);

      // Make the whole row clickable except action buttons
      row.addEventListener('click', function(e) {
        if (!e.target.closest('a, button, input, select, textarea, label')) {
          window.location.href = '<?php echo base_url($view_member_base); ?>' + user.ITS;
        }
      });
    });
  }

  function sortTable(col) {
    const dir = sortDirection[col] === 'asc' ? 'desc' : 'asc';
    sortDirection[col] = dir;

    let filtered = [...originalData];

    // Apply current filters before sorting
    if (currentSectorFilter) {
      filtered = filtered.filter(user => user.Sector === currentSectorFilter);
    }
    if (currentStatusFilter) {
      if (currentStatusFilter === 'no-status') {
        filtered = filtered.filter(user => !user.LeaveStatus || user.LeaveStatus.trim() === "Musaaid didn't Contacted Yet");
      } else {
        filtered = filtered.filter(user => user.LeaveStatus === currentStatusFilter);
      }
    }

    filtered.sort((a, b) => {
      const valA = (a[col] || '').toString().toLowerCase();
      const valB = (b[col] || '').toString().toLowerCase();
      return dir === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
    });

    updateUserTable(filtered);
  }

  function openModal(user) {
    const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
    const container = document.getElementById('userDetailsFields');
    container.innerHTML = '';

    // LeaveStatus dropdown
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
                    ${leaveOptions.map(opt => `
                        <option value="${opt}" ${user.LeaveStatus === opt ? 'selected' : ''}>${opt}</option>
                    `).join('')}
                </select>
            `;
    container.appendChild(leaveCol);

    // Comment textarea
    const commentCol = document.createElement('div');
    commentCol.className = 'col-12 mb-3';
    commentCol.innerHTML = `
                <label class="form-label fw-bold">Comments</label>
                <textarea name="Comment" id="Comment" class="form-control" rows="4" 
                          placeholder="Add any additional comments here...">${user.Comment || ''}</textarea>
            `;
    container.appendChild(commentCol);

    // Read-only user info
    const infoCol = document.createElement('div');
    infoCol.className = 'col-12';
    infoCol.innerHTML = `
                <div class="card bg-light p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> ${user.Full_Name || user.Name || ''}</p>
                            <p><strong>ITS:</strong> ${user.ITS || ''}</p>
                            <p><strong>HOF ID:</strong> ${user.HOF_ID || ''}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Age:</strong> ${user.Age || ''}</p>
                            <p><strong>Mobile:</strong> ${user.Mobile || ''}</p>
                            <p><strong>Sector:</strong> ${user.Sector || ''} ${user.Sub_Sector ? '(' + user.Sub_Sector + ')' : ''}</p>
                        </div>
                    </div>
                </div>
            `;
    container.appendChild(infoCol);

    document.getElementById('modal_ITS').value = user.ITS;
    modal.show();
  }

  document.getElementById('userDetailsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    // Append selected year (if present) so backend can scope by year
    try {
      var ySel = document.getElementById('yearSelect');
      if(ySel && !formData.has('year')){
        formData.append('year', ySel.value);
      }
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