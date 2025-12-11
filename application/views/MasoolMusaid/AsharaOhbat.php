<?php $users = $users ?? []; ?>
<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f8f9fa;
  }

  .header-section {
    background-color: #2c3e50;
    color: white;
    padding: 1.5rem;
    border-radius: 8px;
  }

  .stats-card {
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
    height: 100%;
  }

  .stats-card:hover {
    transform: translateY(-5px);
  }

  .stats-card h5 {
    font-size: 1rem;
    margin-bottom: 10px;
    color: #495057;
  }

  .stats-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: #212529;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
  }

  .sector-card {
    background-color: #e7f1ff;
    padding: 10px 15px;
    border-radius: 8px;
    margin: 5px 5px 10px 0;
    display: inline-block;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
  }

  .sector-card:hover {
    background-color: #d0e2ff;
    transform: scale(1.02);
  }

  .sector-card.active {
    background-color: #2c3e50;
    color: white;
  }

  .table th {
    background-color: #2c3e50;
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
    border-bottom: 2px solid #2c3e50;
    padding-bottom: 8px;
    margin: 20px 0 15px;
    color: #2c3e50;
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

  @media (max-width: 768px) {
    .stats-grid {
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
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

  <!-- Stats Section -->
  <h4 class="section-title">Overview Statistics</h4>
  <div class="stats-grid">
    <div class="stats-card bg-light">
      <h5>Total Members</h5>
      <div class="stats-value"><?= count($users) ?></div>
    </div>
    <div class="stats-card bg-light">
      <h5>HOF (Head of Family)</h5>
      <div class="stats-value"><?= $stats['HOF'] ?></div>
    </div>
    <div class="stats-card bg-light">
      <h5>FM (Family Members)</h5>
      <div class="stats-value"><?= $stats['FM'] ?></div>
    </div>
    <div class="stats-card bg-light">
      <h5>Males</h5>
      <div class="stats-value"><?= $stats['Mardo'] ?></div>
    </div>
    <div class="stats-card bg-light">
      <h5>Females</h5>
      <div class="stats-value"><?= $stats['Bairo'] ?></div>
    </div>
    <div class="stats-card bg-light">
      <h5>Age 0-4</h5>
      <div class="stats-value"><?= $stats['Age_0_4'] ?></div>
    </div>
    <div class="stats-card bg-light">
      <h5>Age 5-15</h5>
      <div class="stats-value"><?= $stats['Age_5_15'] ?></div>
    </div>
    <div class="stats-card bg-light">
      <h5>Seniors (65+)</h5>
      <div class="stats-value"><?= $stats['Buzurgo'] ?></div>
    </div>
  </div>

  <!-- Leave Status Section -->
  <h4 class="section-title">Ashara Ohbat Status</h4>
  <div class="stats-grid">
    <?php foreach ($stats['LeaveStatus'] as $status => $count):
      $statusLabel = $status ?: 'No Status';
      $statusClass = str_replace(' ', '-', strtolower($statusLabel));
    ?>
      <div class="stats-card bg-light">
        <h5><?= $statusLabel ?></h5>
        <div class="stats-value"><?= $count ?></div>
      </div>
    <?php endforeach; ?>

    <?php if (empty($stats['LeaveStatus'])): ?>
      <div class="stats-card bg-light">
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
          <option value="Bed Ridden">Bed Ridden</option>
          <option value="Not in Town">Not in Town</option>
          <option value="Ashara with Maula tus">Ashara with Maula tus</option>
          <option value="Married Outcaste">Married Outcaste</option>
          <option value="Wafaat">Wafaat</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Sector Cards -->
  <div class="card p-3 shadow-sm mb-4">
    <h5 class="section-title">Sector Overview</h5>
    <div class="mb-2 fw-bold" id="totalSectorCard"></div>
    <div id="sectorCardsContainer"></div>
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
          <th onclick="sortTable('LeaveStatus')">Status</th>
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
  let sortDirection = {};
  let currentSectorFilter = null;
  let currentStatusFilter = null;

  // Initialize the page
  document.addEventListener('DOMContentLoaded', function() {
    updateSectorCount(originalData);
    updateUserTable(originalData);
  });

  function performSearch() {
    const keyword = document.getElementById('searchInput').value.toLowerCase().trim();

    let filtered = originalData;

    // Apply sector filter if active
    if (currentSectorFilter) {
      filtered = filtered.filter(user => user.Sector === currentSectorFilter);
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

    updateSectorCount(filtered);
    updateUserTable(filtered);
  }

  function filterByStatus() {
    const status = document.getElementById('statusFilter').value;
    currentStatusFilter = status || null;
    performSearch();
  }

  function updateSectorCount(users) {
    const sectorStats = {};
    users.forEach(u => {
      const sector = u.Sector || 'Unknown';
      if (!sectorStats[sector]) {
        sectorStats[sector] = {
          HOF: 0,
          FM: 0,
          noLeave: 0
        };
      }
      if (u.HOF_FM_TYPE === 'HOF') sectorStats[sector].HOF++;
      else if (u.HOF_FM_TYPE === 'FM') sectorStats[sector].FM++;

      if (!u.LeaveStatus || u.LeaveStatus.trim() === "Musaaid didn't Contacted Yet") {
        sectorStats[sector].noLeave++;
      }
    });

    const totalNoLeave = users.filter(u => !u.LeaveStatus || u.LeaveStatus.trim() === "Musaaid didn't Contacted Yet").length;

    document.getElementById('totalSectorCard').innerHTML = `
        <div class="alert alert-info p-2 mb-3 fs-5">
            <strong>Total Members:</strong> ${users.length} 
            <span class="badge bg-warning text-dark ms-2 fs-6">Not Contacted: ${totalNoLeave}</span>
        </div>
    `;

    const container = document.getElementById('sectorCardsContainer');
    container.className = 'd-flex flex-wrap gap-3';
    container.innerHTML = '';

    Object.entries(sectorStats).forEach(([sector, counts]) => {
      const div = document.createElement('div');
      div.className = `sector-card p-3 rounded shadow-sm border text-center ${currentSectorFilter === sector ? 'bg-primary text-white' : 'bg-white'}`;
      div.style.cssText = `
            width: 200px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: transform 0.2s;
        `;
      div.innerHTML = `
            <h5 class="mb-2">${sector}</h5>
            <div class="d-flex flex-column gap-2">
                <span class="badge bg-success text-white fs-6 mb-2">HOF: ${counts.HOF}</span>
                <span class="badge bg-info text-dark fs-6 mb-2">FM: ${counts.FM}</span>
                <span class="badge bg-warning text-dark fs-6">Not Contacted: ${counts.noLeave}</span>
            </div>
        `;
      div.onclick = () => {
        currentSectorFilter = currentSectorFilter === sector ? null : sector;
        performSearch();

        document.querySelectorAll('.sector-card').forEach(card => {
          card.classList.remove('bg-primary', 'text-white');
          card.classList.add('bg-white');
        });

        if (currentSectorFilter === sector) {
          div.classList.remove('bg-white');
          div.classList.add('bg-primary', 'text-white');
        }
      };
      container.appendChild(div);
    });
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
      'Bed Ridden',
      'Not in Town',
      'Ashara with Maula tus',
      'Married Outcaste',
      'Wafaat',
    ];

    const leaveCol = document.createElement('div');
    leaveCol.className = 'col-12 mb-3';
    leaveCol.innerHTML = `
                <label class="form-label fw-bold">Leave Status</label>
                <select name="LeaveStatus" id="LeaveStatus" class="form-control form-select">
                    <option value="">-- Select Leave Status --</option>
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