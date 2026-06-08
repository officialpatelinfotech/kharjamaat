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

  <!-- Back Button -->
  <div class="d-flex align-items-center pt-5 mb-4">
    <a href="<?php echo isset($back_url) ? $back_url : 'javascript:void(0)'; ?>"
       class="btn btn-outline-secondary"
       title="Back"
       <?php if (!isset($back_url)) : ?>onclick="window.history.back()"<?php endif; ?>>
      <i class="fa-solid fa-arrow-left"></i>
    </a>
  </div>

  <!-- Header Section -->
  <div class="header-section text-center mb-4">
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

  <!-- Sector Cards -->
  <div class="mb-2 fw-bold" id="totalSectorCard"></div>
  <div class="row">
    <div class="col-12">
      <div class="chart-container sector-block">
        <div class="section-header-standard">
          <h4 class="section-title"><i class="fa fa-map-marker"></i> Sector-wise Members</h4>
          <button class="collapse-toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSectorsAshara" aria-expanded="true">
            <i class="fa fa-chevron-down"></i>
          </button>
        </div>
        <div class="collapse show" id="collapseSectorsAshara">
          <div id="sectorCardsContainer" class="row"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Reset Button (shown when a sector/status filter is active) -->
  <div class="d-flex justify-content-end mb-2">
    <button type="button" class="btn btn-outline-danger btn-sm" id="resetStatsBtn" onclick="resetFiltersAndStats()" style="display: none; border-radius: 20px; font-weight: 600;">
      <i class="fa-solid fa-arrows-rotate me-1"></i> Reset Filters
    </button>
  </div>

  <!-- Ashara Ohbat Status Section -->
  <h4 class="section-title">Ashara Ohbat Status</h4>
  <div class="stats-grid" id="ohbatStatusGrid">
    <?php foreach ($stats['LeaveStatus'] as $status => $count):
      $statusLabel = $status ?: 'No Status';
      if (in_array(strtolower(trim($statusLabel)), ['bed ridden', 'not in town', 'married outcaste', 'wafaat'])) {
        continue;
      }
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

  const originalData      = <?= json_encode($users) ?>;
  const sectorIncharges   = <?= json_encode($stats['Sectors'] ?? []) ?>;
  const loggedInSector    = '<?= isset($current_sector)     ? $current_sector     : "" ?>';
  const loggedInSubSector = '<?= isset($current_sub_sector) ? $current_sub_sector : "" ?>';

  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"]/g, function(s) {
      return ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;' }[s]);
    });
  }

  let sortDirection        = {};
  let currentSectorFilter    = null;
  let currentSubSectorFilter = null;
  let currentStatusFilter    = null;
  let currentSortKey         = null;
  let currentSortDir         = 1;

  // ── Bootstrap ──────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', function() {
    initSectorCards();
    updateOhbatStatusGrid(originalData);
    updateUserTable(originalData);
  });

  // ── Search / filter pipeline ───────────────────────────────
  function performSearch() {
    const keyword = document.getElementById('searchInput').value.toLowerCase().trim();
    let filtered = originalData;

    if (currentSectorFilter) {
      filtered = filtered.filter(u => u.Sector === currentSectorFilter);
    }
    if (currentSubSectorFilter) {
      filtered = filtered.filter(u => u.Sub_Sector === currentSubSectorFilter);
    }
    if (currentStatusFilter) {
      if (currentStatusFilter === 'no-status') {
        filtered = filtered.filter(u => !u.LeaveStatus || u.LeaveStatus.trim() === "Musaaid didn't Contacted Yet");
      } else {
        filtered = filtered.filter(u => u.LeaveStatus === currentStatusFilter);
      }
    }
    if (keyword) {
      filtered = filtered.filter(u => {
        const fields = ['ITS','HOF_FM_TYPE','HOF_ID','Full_Name','Name','Mobile','Sector','Sub_Sector','LeaveStatus','Comment'];
        return fields.some(f => (u[f] ? u[f].toString().toLowerCase() : '').includes(keyword));
      });
    }

    updateOhbatStatusGrid(filtered);
    updateUserTable(filtered);

    const resetBtn = document.getElementById('resetStatsBtn');
    if (resetBtn) {
      resetBtn.style.display = (currentSectorFilter || currentStatusFilter || keyword) ? 'inline-block' : 'none';
    }
  }

  function filterByStatus() {
    const status = document.getElementById('statusFilter').value;
    currentStatusFilter = status || null;

    document.querySelectorAll('.status-card-btn').forEach(card => {
      const cardStatus = card.dataset.status;
      const mappedVal  = (cardStatus === 'No Status') ? 'no-status' : cardStatus;
      card.classList.toggle('active', mappedVal === status);
    });

    performSearch();
  }

  function clickStatusCard(statusValue) {
    const filterSelect  = document.getElementById('statusFilter');
    const targetValue   = (statusValue === 'No Status') ? 'no-status' : statusValue;
    filterSelect.value  = (filterSelect.value === targetValue) ? '' : targetValue;
    filterByStatus();
    filterSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  // ── Ashara Ohbat Status grid (dynamic, reflects filtered data) ──
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

    filtered.forEach(u => {
      let st = u.LeaveStatus || "Musaaid didn't Contacted Yet";
      if (st === 'Unknown') st = "Musaaid didn't Contacted Yet";
      if (['bed ridden','not in town','married outcaste','wafaat'].includes(st.toLowerCase().trim())) return;
      if (statusCounts[st] !== undefined) statusCounts[st]++;
      else statusCounts[st] = 1;
    });

    const grid = document.getElementById('ohbatStatusGrid');
    grid.innerHTML = '';
    Object.keys(statusCounts).forEach(statusLabel => {
      const isActive = (currentStatusFilter === statusLabel);
      const card = document.createElement('div');
      card.className = `stats-card status-card-btn${isActive ? ' active' : ''}`;
      card.dataset.status = statusLabel;
      card.style.cursor = 'pointer';
      card.onclick = () => clickStatusCard(statusLabel);
      card.innerHTML = `<h5>${escapeHtml(statusLabel)}</h5><div class="stats-value">${statusCounts[statusLabel]}</div>`;
      grid.appendChild(card);
    });
  }

  // ── Reset all filters ──────────────────────────────────────
  function resetFiltersAndStats() {
    currentSectorFilter    = null;
    currentSubSectorFilter = null;
    currentStatusFilter    = null;
    document.getElementById('searchInput').value   = '';
    document.getElementById('statusFilter').value  = '';

    document.querySelectorAll('#sectorCardsContainer .overview-card').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.status-card-btn').forEach(c => c.classList.remove('active'));

    performSearch();
  }

  // ── Sector cards ───────────────────────────────────────────
  function initSectorCards() {
    const container = document.getElementById('sectorCardsContainer');
    if (!container) return;
    container.className = 'row';
    container.innerHTML = '';

    const inchargeMap    = {};
    const subInchargeMap = {};

    sectorIncharges.forEach(item => {
      const secName = item.Sector || 'Unknown';
      inchargeMap[secName.toLowerCase()] = item;
      (item.sub_sectors || []).forEach(sub => {
        const subKey = `${secName.toLowerCase()}_${(sub.Sub_Sector || '').toLowerCase()}`;
        subInchargeMap[subKey] = sub;
      });
    });

    let cardsToRender = [];

    if (loggedInSector) {
      // Sector / sub-sector login: show sub-sectors
      const subSectorsFound = new Set();
      originalData.forEach(u => {
        if ((u.Sector || '').toLowerCase() === loggedInSector.toLowerCase()) {
          const sub = (u.Sub_Sector || '').trim();
          if (sub) subSectorsFound.add(sub);
        }
      });
      Object.keys(subInchargeMap).forEach(key => {
        if (key.startsWith(loggedInSector.toLowerCase() + '_')) {
          const subName = subInchargeMap[key].Sub_Sector;
          if (subName) subSectorsFound.add(subName);
        }
      });

      let subSectorsList = Array.from(subSectorsFound);
      if (loggedInSubSector) {
        subSectorsList = subSectorsList.filter(s => s.toLowerCase() === loggedInSubSector.toLowerCase());
      }
      subSectorsList.sort();

      subSectorsList.forEach(subName => {
        const members = originalData.filter(u =>
          (u.Sector || '').toLowerCase() === loggedInSector.toLowerCase() &&
          (u.Sub_Sector || '').toLowerCase() === subName.toLowerCase()
        );
        const hof = members.filter(u => u.HOF_FM_TYPE === 'HOF').length;
        const fm  = members.filter(u => u.HOF_FM_TYPE === 'FM').length;
        const subKey = `${loggedInSector.toLowerCase()}_${subName.toLowerCase()}`;
        const subInfo = subInchargeMap[subKey] || {};
        cardsToRender.push({
          type: 'subsector',
          displayName: loggedInSector + ' ' + subName,
          Sector: loggedInSector,
          Sub_Sector: subName,
          hof, fm,
          inchargeName: subInfo.Sub_Sector_Incharge_Name || '',
          inchargeFemaleName: subInfo.Sub_Sector_Incharge_Female_Name || ''
        });
      });

    } else {
      // Admin / Amilsaheb: show all sectors
      const sortedSectors = sectorIncharges
        .filter(item => {
          const sec = (item.Sector || '').trim();
          return sec !== '' && sec.toLowerCase() !== 'unassigned';
        })
        .sort((a, b) => parseInt(b.total || 0) - parseInt(a.total || 0));

      sortedSectors.forEach(itemData => {
        cardsToRender.push({
          type: 'sector',
          displayName: itemData.Sector || 'Unknown',
          Sector: itemData.Sector || 'Unknown',
          Sub_Sector: null,
          hof: parseInt(itemData.hof_count || 0),
          fm:  parseInt(itemData.fm_count  || 0),
          inchargeName: itemData.Sector_Incharge_Name || '',
          inchargeFemaleName: itemData.Sector_Incharge_Female_Name || '',
          sub_sectors: itemData.sub_sectors || []
        });
      });
    }

    cardsToRender.forEach(itemData => {
      const colDiv  = document.createElement('div');
      colDiv.className = 'col-12 col-md-3 mb-3';

      const isCardActive = (itemData.type === 'subsector')
        ? (currentSectorFilter === itemData.Sector && currentSubSectorFilter === itemData.Sub_Sector)
        : (currentSectorFilter === itemData.Sector && !currentSubSectorFilter);

      const cardDiv = document.createElement('div');
      cardDiv.className = `overview-card${isCardActive ? ' active' : ''}`;
      cardDiv.dataset.sector = itemData.Sector;
      if (itemData.Sub_Sector) cardDiv.dataset.subsector = itemData.Sub_Sector;
      cardDiv.style.cssText = 'display:flex; flex-direction:column; gap:8px; cursor:pointer;';

      const total = itemData.hof + itemData.fm;

      let inchargeHtml = '';
      if (itemData.inchargeName) {
        inchargeHtml += `<div style="font-size:.7rem;color:var(--text-3);margin-top:4px;"><i class="fa fa-male" style="color:var(--gold);margin-right:3px;"></i>${escapeHtml(itemData.inchargeName)}</div>`;
      }
      if (itemData.inchargeFemaleName) {
        inchargeHtml += `<div style="font-size:.7rem;color:var(--text-3);"><i class="fa fa-female" style="color:var(--gold);margin-right:3px;"></i>${escapeHtml(itemData.inchargeFemaleName)}</div>`;
      }

      cardDiv.innerHTML = `
        <div style="display:flex;align-items:center;gap:10px;">
          <div class="overview-icon"><i class="fa fa-map-marker"></i></div>
          <div class="overview-body" style="flex:1;min-width:0;">
            <span class="overview-title">${escapeHtml(itemData.displayName)}</span>
            <span class="overview-value">${total}</span>
          </div>
        </div>
        <div style="display:flex;gap:8px;font-size:.72rem;color:var(--text-2);">
          <span><b>HOF:</b> ${itemData.hof}</span>
          <span><b>FM:</b> ${itemData.fm}</span>
        </div>
        ${inchargeHtml}
      `;

      cardDiv.addEventListener('click', function() {
        const clickedSector    = this.dataset.sector;
        const clickedSubSector = this.dataset.subsector || null;
        const isSameFilter = (currentSectorFilter === clickedSector && currentSubSectorFilter === clickedSubSector);

        if (isSameFilter) {
          currentSectorFilter    = null;
          currentSubSectorFilter = null;
          document.querySelectorAll('#sectorCardsContainer .overview-card').forEach(c => c.classList.remove('active'));
        } else {
          currentSectorFilter    = clickedSector;
          currentSubSectorFilter = clickedSubSector;
          document.querySelectorAll('#sectorCardsContainer .overview-card').forEach(c => c.classList.remove('active'));
          this.classList.add('active');
        }
        performSearch();
      });

      colDiv.appendChild(cardDiv);
      container.appendChild(colDiv);
    });
  }

  // ── Table render ───────────────────────────────────────────
  function updateUserTable(data) {
    const tbody = document.getElementById('userTableBody');
    tbody.innerHTML = '';

    if (!data || data.length === 0) {
      tbody.innerHTML = '<tr><td colspan="12" class="text-center text-muted py-4">No members found.</td></tr>';
      return;
    }

    data.forEach((user, index) => {
      const row = document.createElement('tr');

      const type = user.HOF_FM_TYPE || '';
      if (type === 'HOF') row.classList.add('hof-row');
      else if (type === 'FM') row.classList.add('fm-row');

      const leaveStatus = user.LeaveStatus || '';
      if (!leaveStatus || leaveStatus.trim() === '' || leaveStatus === "Musaaid didn't Contacted Yet") {
        row.classList.add('no-leave');
      }

      const statusBadgeColor = getStatusBadgeColor(leaveStatus);

      row.innerHTML = `
        <td>${index + 1}</td>
        <td>${escapeHtml(user.ITS)}</td>
        <td><span class="badge ${type === 'HOF' ? 'bg-primary' : 'bg-secondary'}">${escapeHtml(type)}</span></td>
        <td>${escapeHtml(user.HOF_ID)}</td>
        <td>${escapeHtml(user.Full_Name)}</td>
        <td>${escapeHtml(user.Age)}</td>
        <td>${escapeHtml(user.Mobile)}</td>
        <td>${escapeHtml(user.Sector)}</td>
        <td>${escapeHtml(user.Sub_Sector)}</td>
        <td><span class="badge badge-status" style="background-color:${statusBadgeColor};color:#fff;">${escapeHtml(leaveStatus || 'No Status')}</span></td>
        <td>${escapeHtml(user.Comment)}</td>
        <td>
          <a href="<?= base_url($view_member_base) ?>${escapeHtml(user.ITS)}" class="btn btn-sm btn-outline-primary action-btn" onclick="event.stopPropagation()">
            <i class="fa-solid fa-eye"></i> View
          </a>
        </td>
      `;

      row.addEventListener('click', function() {
        openEditModal(user);
      });

      tbody.appendChild(row);
    });
  }

  function getStatusBadgeColor(status) {
    const map = {
      'Will attend all 9 Days':            '#2e7d32',
      'Will attend few Days only':         '#f57c00',
      'Will not attend any Day':           '#c62828',
      'Not answering calls or messages':   '#6a1b9a',
      "Musaaid didn't Contacted Yet":      '#78909c',
      'Ashara with Maula tus':             '#0277bd',
    };
    return map[status] || '#78909c';
  }

  // ── Sort ───────────────────────────────────────────────────
  function sortTable(key) {
    if (currentSortKey === key) {
      currentSortDir = -currentSortDir;
    } else {
      currentSortKey = key;
      currentSortDir = 1;
    }

    let filtered = originalData;
    if (currentSectorFilter)    filtered = filtered.filter(u => u.Sector     === currentSectorFilter);
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
      filtered = filtered.filter(u => {
        const fields = ['ITS','HOF_FM_TYPE','HOF_ID','Full_Name','Name','Mobile','Sector','Sub_Sector','LeaveStatus','Comment'];
        return fields.some(f => (u[f] ? u[f].toString().toLowerCase() : '').includes(keyword));
      });
    }

    filtered.sort((a, b) => {
      const av = a[key] || '';
      const bv = b[key] || '';
      if (!isNaN(av) && !isNaN(bv)) return currentSortDir * (Number(av) - Number(bv));
      return currentSortDir * String(av).localeCompare(String(bv));
    });

    updateUserTable(filtered);
  }

  // ── Edit modal ─────────────────────────────────────────────
  function openEditModal(user) {
    const fieldsContainer = document.getElementById('userDetailsFields');
    fieldsContainer.innerHTML = '';
    document.getElementById('modal_ITS').value = user.ITS;

    const editableFields = [
      { key: 'LeaveStatus', label: 'Ohbat Status', type: 'select', options: [
        '', 'Will attend all 9 Days', 'Will attend few Days only', 'Will not attend any Day',
        'Not answering calls or messages', "Musaaid didn't Contacted Yet", 'Ashara with Maula tus',
        'Bed Ridden', 'Not in Town', 'Married Outcaste', 'Wafaat'
      ]},
      { key: 'Comment', label: 'Comment', type: 'textarea' },
    ];

    const readonlyFields = [
      { key: 'ITS',         label: 'ITS'         },
      { key: 'Full_Name',   label: 'Full Name'   },
      { key: 'HOF_FM_TYPE', label: 'Type'        },
      { key: 'HOF_ID',      label: 'HOF ID'      },
      { key: 'Age',         label: 'Age'          },
      { key: 'Gender',      label: 'Gender'       },
      { key: 'Mobile',      label: 'Mobile'       },
      { key: 'Sector',      label: 'Sector'       },
      { key: 'Sub_Sector',  label: 'Sub-Sector'  },
    ];

    readonlyFields.forEach(f => {
      const col = document.createElement('div');
      col.className = 'col-md-6';
      col.innerHTML = `
        <label class="form-label fw-semibold" style="font-size:.8rem;color:var(--text-3);">${f.label}</label>
        <input type="text" class="form-control form-control-sm" value="${escapeHtml(user[f.key] || '')}" readonly style="background:var(--surface-2);">
      `;
      fieldsContainer.appendChild(col);
    });

    editableFields.forEach(f => {
      const col = document.createElement('div');
      col.className = 'col-md-6';
      let inputHtml = '';
      if (f.type === 'select') {
        const opts = f.options.map(o =>
          `<option value="${escapeHtml(o)}" ${user[f.key] === o ? 'selected' : ''}>${escapeHtml(o) || '-- Select --'}</option>`
        ).join('');
        inputHtml = `<select name="${f.key}" class="form-control form-select form-select-sm">${opts}</select>`;
      } else if (f.type === 'textarea') {
        inputHtml = `<textarea name="${f.key}" class="form-control form-control-sm" rows="3">${escapeHtml(user[f.key] || '')}</textarea>`;
      }
      col.innerHTML = `<label class="form-label fw-semibold" style="font-size:.8rem;color:var(--text-2);">${f.label}</label>${inputHtml}`;
      fieldsContainer.appendChild(col);
    });

    const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
    modal.show();
  }

  // ── Form submit ────────────────────────────────────────────
  document.getElementById('userDetailsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const its = document.getElementById('modal_ITS').value;
    formData.append('ITS', its);

    fetch('<?= base_url($role_segment . "/update_ashara_status") ?>', {
      method: 'POST',
      body: formData
    })
    .then(r => r.json())
    .then(resp => {
      if (resp.success) {
        // Update in-memory data
        const idx = originalData.findIndex(u => String(u.ITS) === String(its));
        if (idx !== -1) {
          formData.forEach((val, key) => { if (key !== 'ITS') originalData[idx][key] = val; });
        }
        bootstrap.Modal.getInstance(document.getElementById('userDetailsModal')).hide();
        performSearch();
        alert('Updated successfully!');
      } else {
        alert('Update failed: ' + (resp.message || 'Unknown error'));
      }
    })
    .catch(() => alert('Network error. Please try again.'));
  });
</script>