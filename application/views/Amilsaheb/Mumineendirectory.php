<style>
  body {
    font-family: Arial, sans-serif;
    margin: 20px;
  }

  table {
    border-collapse: collapse;
    width: 100%;
  }

  th,
  td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }

  th {
    background-color: #f2f2f2;
  }

  th.sortable {
    cursor: pointer;
    position: relative;
    padding-right: 20px;
  }

  th.sortable::after {
    content: '⇅';
    position: absolute;
    right: 8px;
    font-size: 0.8em;
    opacity: 0.6;
  }

  th.sortable.asc::after {
    content: '🔼';
  }

  th.sortable.desc::after {
    content: '🔽';
  }

  form {
    margin-bottom: 20px;
  }

  .card {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
  }

  .sector-card {
    background-color: #e2e6ea;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
    display: inline-block;
    margin-right: 10px;
  }

  @media only screen and (max-width: 600px) {
    .sector-card {
      display: block;
      margin-right: 0;
      margin-bottom: 5px;
    }
  }

  .modal-body {
    max-height: 70vh;
    overflow-y: auto;
  }

  @media (max-width: 768px) {
    .modal-dialog {
      margin: 1rem;
      max-width: 95%;
    }

    .modal-body {
      max-height: 60vh;
    }
  }
</style>

<div class="margintopcontainer pt-5">
  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo isset($back_url) ? $back_url : base_url('amilsaheb'); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <form>
    <input type="text" id="searchInput" placeholder="Search..." oninput="performSearch()" class="form-control">
  </form>

  <div id="activeFilter" class="card" style="display:none;">
    <span id="activeFilterText"></span>
    <a id="clearFilterLink" href="<?php echo base_url('amilsaheb/mumineendirectory'); ?>" class="btn btn-sm btn-outline-secondary ms-2">Clear</a>
  </div>

  <div class="card">
    <div class="sector-card" id="totalSectorCard"></div>
    <div id="sectorCardsContainer"></div>
  </div>

  <div style="overflow-x: auto;">
    <table id="userData">
      <thead>
        <tr>
          <th>S.no.</th>
          <th class="sortable" onclick="sortTable('ITS_ID')">ITS_ID</th>
          <th class="sortable" onclick="sortTable('Full_Name')">Full_Name</th>
          <th class="sortable" onclick="sortTable('Age')">Age</th>
          <th class="sortable" onclick="sortTable('Mobile')">Mobile</th>
          <th class="sortable" onclick="sortTable('Email')">Email</th>
          <th class="sortable" onclick="sortTable('Address')">Address</th>
          <th class="sortable" onclick="sortTable('Sector')">Sector</th>
          <th class="sortable" onclick="sortTable('Sub_Sector')">Sub_Sector</th>
          <th class="sortable" onclick="sortTable('HOF_ID')">HOF_ID</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="userTableBody"></tbody>
    </table>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <form id="userDetailsForm">
        <div class="modal-header">
          <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="userDetailsFields" class="row row-cols-1 row-cols-md-2 g-3"></div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="ITS_ID" id="modal_ITS_ID">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const originalData = <?= json_encode($users) ?>;
  let currentData = [...originalData];
  let sortDirection = {};

  // Apply initial filter based on URL params (sector, gender, member_type, HOF/FM, age ranges)
  applyInitialFilterFromQuery();
  updateSectorCount(currentData);
  updateUserTable(currentData);

  function performSearch() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const filteredData = currentData.filter(user => {
      return Object.values(user).some(val => val && val.toString().toLowerCase().includes(keyword));
    });
    updateSectorCount(filteredData);
    updateUserTable(filteredData);
  }

  function updateSectorCount(users) {
    const sectors = {};
    users.forEach(user => {
      if (user.Sector) {
        sectors[user.Sector] = (sectors[user.Sector] || 0) + 1;
      }
    });
    document.getElementById('totalSectorCard').textContent = 'Total Count: ' + users.length;
    const container = document.getElementById('sectorCardsContainer');
    container.innerHTML = '';
    Object.keys(sectors).forEach(sector => {
      const card = document.createElement('div');
      card.className = 'sector-card';
      card.textContent = `Sector: ${sector}, Count: ${sectors[sector]}`;
      container.appendChild(card);
    });
  }

  function updateUserTable(users) {
    const body = document.getElementById('userTableBody');
    body.innerHTML = '';
    users.forEach((user, key) => {
      const row = body.insertRow();
      row.insertCell().textContent = key + 1;
      row.insertCell().textContent = user.ITS_ID || '';
      row.insertCell().textContent = user.Full_Name || '';
      row.insertCell().textContent = user.Age || '';
      row.insertCell().textContent = user.Mobile || '';
      row.insertCell().textContent = user.Email || '';
      row.insertCell().textContent = user.Address || '';
      row.insertCell().textContent = user.Sector || '';
      row.insertCell().textContent = user.Sub_Sector || '';
      row.insertCell().textContent = user.HOF_ID || '';
      const actionCell = row.insertCell();
      const btn = document.createElement('button');
      btn.className = 'btn btn-sm btn-primary view-details-btn';
      btn.textContent = 'View Details';
      btn.onclick = () => openModal(user);
      actionCell.appendChild(btn);
    });
  }

  function sortTable(column) {
    const direction = sortDirection[column] === 'asc' ? 'desc' : 'asc';
    sortDirection[column] = direction;

    document.querySelectorAll('th.sortable').forEach(th => {
      th.classList.remove('asc', 'desc');
      if (th.textContent.replace(/\s+/g, '_') === column) {
        th.classList.add(direction);
      }
    });

    currentData.sort((a, b) => {
      const valA = (a[column] || '').toString().toLowerCase();
      const valB = (b[column] || '').toString().toLowerCase();

      if (!isNaN(valA) && !isNaN(valB)) {
        return direction === 'asc' ? valA - valB : valB - valA;
      } else {
        return direction === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
      }
    });

    updateUserTable(currentData);
  }

  function openModal(user) {
    const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
    const container = document.getElementById('userDetailsFields');
    container.innerHTML = '';
    Object.keys(user).forEach(key => {
      const col = document.createElement('div');
      col.className = 'col';

      const label = document.createElement('label');
      label.className = 'form-label';
      label.setAttribute('for', key);
      label.textContent = key.replace(/_/g, ' ');

      const input = document.createElement('input');
      input.type = 'text';
      input.className = 'form-control';
      input.id = key;
      input.name = key;
      input.value = user[key] || '';

      col.appendChild(label);
      col.appendChild(input);
      container.appendChild(col);
    });
    document.getElementById('modal_ITS_ID').value = user.ITS_ID;
    modal.show();
  }

  document.getElementById('userDetailsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    fetch('<?php echo isset($update_user_url) ? $update_user_url : base_url('amilsaheb/update_user_details'); ?>', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('User details updated successfully');
          location.reload();
        } else {
          alert('Update failed');
        }
      });
  });

  // Utilities: read URL params and filter data accordingly
  function applyInitialFilterFromQuery() {
    const params = new URLSearchParams(window.location.search);
    const filter = (params.get('filter') || '').toLowerCase();
    if (!filter) { currentData = [...originalData]; return; }

    const value = params.get('value');
    const min = params.get('min') !== null ? parseInt(params.get('min'), 10) : null;
    const max = params.get('max') !== null ? parseInt(params.get('max'), 10) : null;

    const getMemberType = (u) => {
      return (u.member_type || u.Member_Type || '').toString();
    };

    const getGender = (u) => {
      return (u.Gender || '').toString().toLowerCase();
    };

    const getSector = (u) => {
      return (u.Sector || '').toString().trim();
    };

    const getHofFm = (u) => {
      return (u.HOF_FM_TYPE || u.hof_fm_type || '').toString().toUpperCase();
    };

    const getAge = (u) => {
      const a = parseInt(u.Age, 10);
      return isNaN(a) ? null : a;
    };

    let label = '';

    switch (filter) {
      case 'all':
        currentData = [...originalData];
        label = 'All Members';
        break;
      case 'sector':
        currentData = originalData.filter(u => getSector(u) === (value || ''));
        label = 'Sector: ' + (value || '');
        break;
      case 'member_type':
        currentData = originalData.filter(u => getMemberType(u).toLowerCase().includes((value || '').toLowerCase()));
        label = 'Member Type: ' + (value || '');
        break;
      case 'gender':
        currentData = originalData.filter(u => getGender(u) === (value || '').toLowerCase());
        label = 'Gender: ' + (value || '');
        break;
      case 'hof_fm_type':
        currentData = originalData.filter(u => getHofFm(u) === (value || '').toUpperCase());
        label = 'Type: ' + (value || '');
        break;
      case 'age_range':
        currentData = originalData.filter(u => {
          const age = getAge(u);
          if (age === null) return false;
          if (min !== null && age < min) return false;
          if (max !== null && age > max) return false;
          return true;
        });
        if (min !== null && max !== null) label = `Age: ${min}-${max}`;
        else if (min !== null) label = `Age: ${min}+`;
        else if (max !== null) label = `Age: ≤ ${max}`;
        break;
      default:
        currentData = [...originalData];
    }

    // Show active filter chip
    const chip = document.getElementById('activeFilter');
    const chipText = document.getElementById('activeFilterText');
    if (label) {
      chipText.textContent = 'Showing ' + label;
      chip.style.display = 'block';
    } else {
      chip.style.display = 'none';
    }
  }
</script>