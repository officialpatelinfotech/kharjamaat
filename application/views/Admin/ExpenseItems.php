<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">

<div class="gold-theme-wrapper">
  <div class="container pt-5">
    <style>
      .item-table-container { max-height: 480px; overflow: auto; border-radius: 8px; border: 1px solid #e8e0cc; }
      .item-table thead th { position: sticky; top: 0; background: #f5e9c0; color: #5a5244; z-index: 3; box-shadow: 0 2px 2px -1px rgba(0,0,0,0.1); border-bottom: 2px solid #e8e0cc; }
      .item-table tbody tr { background: #ffffff; }
      .item-table tbody tr:hover { background: #faf7f0; }
    </style>

    <div class="row align-items-center mb-3">
      <div class="col-4 text-left">
        <a href="<?php echo base_url('admin/expense'); ?>" class="btn btn-sm btn-gold-outline" aria-label="Back to Expense"><i class="fa fa-arrow-left"></i> Back</a>
      </div>
      <div class="col-4 text-center">
        <h4 class="m-0 page-title">Expense Sections</h4>
      </div>
      <div class="col-4 text-right">
        <button id="itemAddBtn" type="button" class="btn btn-sm btn-gold" data-toggle="modal" data-target="#itemAddModal">
          <i class="fa fa-plus"></i> Add Expense Section
        </button>
      </div>
    </div>
    <hr style="border-top: 1px solid #e8e0cc;">

    <div class="card mb-3 filter-card">
      <div class="card-body py-2">
        <div class="form-row align-items-end">
          <div class="col-md-4 mb-2">
            <label class="small mb-1 font-weight-bold" style="color: #5a5244;">Sector</label>
            <input type="text" id="itemFilterSector" class="form-control form-control-sm" placeholder="Filter by sector">
          </div>
          <div class="col-md-4 mb-2">
            <label class="small mb-1 font-weight-bold" style="color: #5a5244;">Sub Sector</label>
            <input type="text" id="itemFilterSubSector" class="form-control form-control-sm" placeholder="Filter by sub sector">
          </div>
          <div class="col-md-4 mb-2">
            <label class="small mb-1 font-weight-bold" style="color: #5a5244;">Expense Section</label>
            <input type="text" id="itemFilterName" class="form-control form-control-sm" placeholder="Filter by section name">
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="itemClearFilters">Clear</button>
        <span class="small text-muted ml-2" id="itemFilterSummary" aria-live="polite"></span>
      </div>
    </div>

    <?php $items = (isset($items) && is_array($items)) ? $items : []; ?>

    <div class="card p-3 table-card">
      <div class="item-table-container">
        <table class="table table-bordered mb-0 item-table" id="itemTable">
          <thead>
            <tr>
              <th style="width: 80px;">Sr.No</th>
              <th>Sector</th>
              <th>Sub Sector</th>
              <th>Expense Section</th>
              <th style="width: 120px;">Status</th>
              <th style="width: 160px;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($items)) : ?>
              <?php $sr = 1; foreach ($items as $row) : ?>
                <?php
                  $id = isset($row['id']) ? (string)$row['id'] : '';
                  $sector_name = isset($row['sector_name']) ? (string)$row['sector_name'] : '';
                  $sub_sector_name = isset($row['sub_sector_name']) ? (string)$row['sub_sector_name'] : '';
                  $item_name = isset($row['item_name']) ? (string)$row['item_name'] : '';
                  $status = isset($row['status']) ? (string)$row['status'] : 'Active';
                ?>
                <tr data-id="<?php echo htmlspecialchars($id); ?>" 
                    data-sector="<?php echo htmlspecialchars(strtolower($sector_name)); ?>" 
                    data-sector-name="<?php echo htmlspecialchars($sector_name); ?>" 
                    data-subsector="<?php echo htmlspecialchars(strtolower($sub_sector_name)); ?>" 
                    data-subsector-name="<?php echo htmlspecialchars($sub_sector_name); ?>" 
                    data-name="<?php echo htmlspecialchars(strtolower($item_name)); ?>" 
                    data-item-name="<?php echo htmlspecialchars($item_name); ?>" 
                    data-status="<?php echo htmlspecialchars($status); ?>">
                  <td><?php echo (int)$sr; $sr++; ?></td>
                  <td class="font-weight-semibold"><?php echo htmlspecialchars($sector_name); ?></td>
                  <td><?php echo htmlspecialchars($sub_sector_name); ?></td>
                  <td class="font-weight-semibold" style="color: #78520a;"><?php echo htmlspecialchars($item_name); ?></td>
                  <td>
                    <span class="badge badge-status <?php echo strtolower($status) === 'active' ? 'status-active' : 'status-inactive'; ?>">
                      <?php echo htmlspecialchars($status); ?>
                    </span>
                  </td>
                  <td>
                    <button type="button" class="btn btn-xs btn-gold-outline py-1 px-2 mr-1 item-edit" data-toggle="modal" data-target="#itemAddModal">Edit</button>
                    <button type="button" class="btn btn-xs btn-danger py-1 px-2 item-delete">Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr class="item-empty">
                <td colspan="6" class="text-center text-muted py-3">No records found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add/Edit Expense Item Modal -->
<div class="modal fade" id="itemAddModal" tabindex="-1" role="dialog" aria-labelledby="itemAddModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-custom">
      <div class="modal-header">
        <h5 class="modal-title font-title" id="itemAddModalLabel">Add Expense Section</h5>
        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="itemEditing" value="0">
        
        <!-- Sector Selector & Input -->
        <div class="form-group">
          <label for="itemSectorSelect" class="font-weight-semibold">Sector</label>
          <select class="form-control form-control-sm" id="itemSectorSelect" required>
            <option value="">Select Sector</option>
            <option value="__NEW__">-- Add New Sector --</option>
          </select>
        </div>
        <div class="form-group" id="newSectorNameGroup" style="display: none;">
          <label for="itemSectorName" class="font-weight-semibold" style="color: #78520a;">New Sector Name</label>
          <input type="text" class="form-control form-control-sm" id="itemSectorName" placeholder="e.g. Jamaat Operating Expenses">
        </div>

        <!-- Sub-Sector Selector & Input -->
        <div class="form-group">
          <label for="itemSubSectorSelect" class="font-weight-semibold">Sub-Sector</label>
          <select class="form-control form-control-sm" id="itemSubSectorSelect" required>
            <option value="">Select Sub-Sector</option>
            <option value="__NEW__">-- Add New Sub-Sector --</option>
          </select>
        </div>
        <div class="form-group" id="newSubSectorNameGroup" style="display: none;">
          <label for="itemSubSectorName" class="font-weight-semibold" style="color: #78520a;">New Sub-Sector Name</label>
          <input type="text" class="form-control form-control-sm" id="itemSubSectorName" placeholder="e.g. Aamilsaheb">
        </div>

        <!-- Item Name Input -->
        <div class="form-group">
          <label for="itemName" class="font-weight-semibold">Section Name</label>
          <input type="text" class="form-control form-control-sm" id="itemName" placeholder="e.g. Takarroban Raqam to Dawat" required>
        </div>

        <!-- Status Selector -->
        <div class="form-group">
          <label for="itemStatus" class="font-weight-semibold">Status</label>
          <select class="form-control form-control-sm" id="itemStatus">
            <option value="Active" selected>Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-sm btn-gold" id="itemSaveBtn">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    var subSectorsBySector = {};

    function escapeHtml(s){
      return String(s)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;')
        .replace(/'/g,'&#039;');
    }

    function applyFilters(){
      var filterSector = document.getElementById('itemFilterSector');
      var filterSubSector = document.getElementById('itemFilterSubSector');
      var filterName = document.getElementById('itemFilterName');
      var summaryEl = document.getElementById('itemFilterSummary');
      var tbody = document.querySelector('#itemTable tbody');
      if(!tbody || !summaryEl) return;

      var qSector = (filterSector.value || '').trim().toLowerCase();
      var qSubSector = (filterSubSector.value || '').trim().toLowerCase();
      var qName = (filterName.value || '').trim().toLowerCase();

      var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
      var total = 0;
      var shown = 0;

      rows.forEach(function(tr){
        if(tr.classList.contains('item-empty')) return;
        total++;
        var sector = (tr.getAttribute('data-sector') || '');
        var subsector = (tr.getAttribute('data-subsector') || '');
        var name = (tr.getAttribute('data-name') || '');

        var ok = (!qSector || sector.indexOf(qSector) !== -1) &&
                 (!qSubSector || subsector.indexOf(qSubSector) !== -1) &&
                 (!qName || name.indexOf(qName) !== -1);

        tr.style.display = ok ? '' : 'none';
        if(ok) shown++;
      });

      summaryEl.textContent = total ? ('Showing ' + shown + ' of ' + total) : '';
    }

    function renumberRows(){
      var tbody = document.querySelector('#itemTable tbody');
      if(!tbody) return;
      var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
      var n = 1;
      rows.forEach(function(tr){
        if(tr.classList.contains('item-empty')) return;
        if(tr.children && tr.children[0]) tr.children[0].textContent = String(n++);
      });
    }

    function populateDropdowns() {
      var sectors = new Set();
      subSectorsBySector = {};

      var rows = document.querySelectorAll('#itemTable tbody tr');
      rows.forEach(function(tr) {
        if (tr.classList.contains('item-empty')) return;
        var sName = tr.getAttribute('data-sector-name');
        var subName = tr.getAttribute('data-subsector-name');
        if (sName) {
          sectors.add(sName);
          if (!subSectorsBySector[sName]) {
            subSectorsBySector[sName] = new Set();
          }
          if (subName) {
            subSectorsBySector[sName].add(subName);
          }
        }
      });

      var sectorSelect = document.getElementById('itemSectorSelect');
      var selectedSector = sectorSelect.value;
      sectorSelect.innerHTML = '<option value="">Select Sector</option><option value="__NEW__">-- Add New Sector --</option>';
      sectors.forEach(function(s) {
        var opt = document.createElement('option');
        opt.value = s;
        opt.textContent = s;
        sectorSelect.appendChild(opt);
      });
      if (selectedSector && sectorSelect.querySelector('option[value="' + selectedSector + '"]')) {
        sectorSelect.value = selectedSector;
      }

      updateSubSectorDropdown();
    }

    function updateSubSectorDropdown() {
      var sectorSelect = document.getElementById('itemSectorSelect');
      var subSectorSelect = document.getElementById('itemSubSectorSelect');
      var selectedSubSector = subSectorSelect.value;

      subSectorSelect.innerHTML = '<option value="">Select Sub-Sector</option><option value="__NEW__">-- Add New Sub-Sector --</option>';

      var sectorVal = sectorSelect.value;
      if (sectorVal && sectorVal !== '__NEW__') {
        var subs = subSectorsBySector[sectorVal];
        if (subs) {
          subs.forEach(function(sub) {
            var opt = document.createElement('option');
            opt.value = sub;
            opt.textContent = sub;
            subSectorSelect.appendChild(opt);
          });
        }
      } else {
        var allSubs = new Set();
        var rows = document.querySelectorAll('#itemTable tbody tr');
        rows.forEach(function(tr) {
          if (tr.classList.contains('item-empty')) return;
          var subName = tr.getAttribute('data-subsector-name');
          if (subName) allSubs.add(subName);
        });
        allSubs.forEach(function(sub) {
          var opt = document.createElement('option');
          opt.value = sub;
          opt.textContent = sub;
          subSectorSelect.appendChild(opt);
        });
      }

      if (selectedSubSector && subSectorSelect.querySelector('option[value="' + selectedSubSector + '"]')) {
        subSectorSelect.value = selectedSubSector;
      }
    }

    // Toggle custom sector/sub-sector fields
    document.getElementById('itemSectorSelect').addEventListener('change', function() {
      var isNew = this.value === '__NEW__';
      document.getElementById('newSectorNameGroup').style.display = isNew ? '' : 'none';
      updateSubSectorDropdown();
    });

    document.getElementById('itemSubSectorSelect').addEventListener('change', function() {
      var isNew = this.value === '__NEW__';
      document.getElementById('newSubSectorNameGroup').style.display = isNew ? '' : 'none';
    });

    ['itemFilterSector', 'itemFilterSubSector', 'itemFilterName'].forEach(function(id){
      var el = document.getElementById(id);
      if(el) el.addEventListener('input', applyFilters);
    });

    var clearBtn = document.getElementById('itemClearFilters');
    if(clearBtn){
      clearBtn.addEventListener('click', function(){
        ['itemFilterSector', 'itemFilterSubSector', 'itemFilterName'].forEach(function(id){
          var el = document.getElementById(id);
          if(el) el.value = '';
        });
        applyFilters();
      });
    }

    // Modal reset on Add click
    var addBtn = document.getElementById('itemAddBtn');
    if(addBtn){
      addBtn.addEventListener('click', function(){
        document.getElementById('itemAddModalLabel').textContent = 'Add Expense Section';
        document.getElementById('itemEditing').value = '0';
        document.getElementById('itemSectorSelect').value = '';
        document.getElementById('itemSubSectorSelect').value = '';
        document.getElementById('itemSectorName').value = '';
        document.getElementById('itemSubSectorName').value = '';
        document.getElementById('itemName').value = '';
        document.getElementById('itemStatus').value = 'Active';
        document.getElementById('newSectorNameGroup').style.display = 'none';
        document.getElementById('newSubSectorNameGroup').style.display = 'none';
        populateDropdowns();
      });
    }

    // Modal populate on Edit click
    document.addEventListener('click', function(e){
      var t = e.target;
      if(t && t.classList.contains('item-edit')){
        var tr = t.closest('tr');
        if(!tr) return;

        var id = tr.getAttribute('data-id');
        var sName = tr.getAttribute('data-sector-name');
        var subName = tr.getAttribute('data-subsector-name');
        var name = tr.getAttribute('data-item-name');
        var status = tr.getAttribute('data-status');

        document.getElementById('itemAddModalLabel').textContent = 'Edit Expense Section';
        document.getElementById('itemEditing').value = id;
        document.getElementById('itemName').value = name;
        document.getElementById('itemStatus').value = status;

        populateDropdowns();

        var sectorSelect = document.getElementById('itemSectorSelect');
        var optionExistsSector = Array.prototype.some.call(sectorSelect.options, function(opt) {
          return opt.value === sName;
        });

        if (optionExistsSector) {
          sectorSelect.value = sName;
          document.getElementById('newSectorNameGroup').style.display = 'none';
          document.getElementById('itemSectorName').value = sName;
        } else {
          sectorSelect.value = '__NEW__';
          document.getElementById('newSectorNameGroup').style.display = '';
          document.getElementById('itemSectorName').value = sName;
        }

        updateSubSectorDropdown();

        var subSectorSelect = document.getElementById('itemSubSectorSelect');
        var optionExistsSub = Array.prototype.some.call(subSectorSelect.options, function(opt) {
          return opt.value === subName;
        });

        if (optionExistsSub) {
          subSectorSelect.value = subName;
          document.getElementById('newSubSectorNameGroup').style.display = 'none';
          document.getElementById('itemSubSectorName').value = subName;
        } else {
          subSectorSelect.value = '__NEW__';
          document.getElementById('newSubSectorNameGroup').style.display = '';
          document.getElementById('itemSubSectorName').value = subName;
        }
      }
    });

    // Save button click
    var saveBtn = document.getElementById('itemSaveBtn');
    if(saveBtn){
      saveBtn.addEventListener('click', function(){
        var editingId = parseInt(document.getElementById('itemEditing').value) || 0;
        
        var sectorSelect = document.getElementById('itemSectorSelect');
        var subSectorSelect = document.getElementById('itemSubSectorSelect');

        var sName = sectorSelect.value === '__NEW__' ? document.getElementById('itemSectorName').value.trim() : sectorSelect.value;
        var subName = subSectorSelect.value === '__NEW__' ? document.getElementById('itemSubSectorName').value.trim() : subSectorSelect.value;
        var name = document.getElementById('itemName').value.trim();
        var status = document.getElementById('itemStatus').value;

        if(!sName || !subName || !name){
          alert('Sector, Sub-Sector, and Item Name are required');
          return;
        }

        var url = '<?php echo site_url("admin/expense/item-create"); ?>';
        var fd = new FormData();
        fd.append('sector_name', sName);
        fd.append('sub_sector_name', subName);
        fd.append('item_name', name);
        fd.append('status', status);

        if(editingId > 0){
          url = '<?php echo site_url("admin/expense/item-update"); ?>';
          fd.append('id', editingId);
        }

        saveBtn.disabled = true;
        fetch(url, {
          method: 'POST',
          body: fd
        })
        .then(function(res){ return res.json(); })
        .then(function(data){
          saveBtn.disabled = false;
          if(data && data.status === 'success'){
            $('#itemAddModal').modal('hide');
            location.reload();
          } else {
            alert(data.message || 'Action failed');
          }
        })
        .catch(function(err){
          saveBtn.disabled = false;
          alert('Request failed');
        });
      });
    }

    // Delete item click
    document.addEventListener('click', function(e){
      var t = e.target;
      if(t && t.classList.contains('item-delete')){
        var tr = t.closest('tr');
        if(!tr) return;
        var id = tr.getAttribute('data-id');
        var name = tr.getAttribute('data-item-name');

        if(!confirm('Are you sure you want to delete "' + name + '"? Any related expense records will also be deleted.')){
          return;
        }

        var fd = new FormData();
        fd.append('id', id);

        fetch('<?php echo site_url("admin/expense/item-delete"); ?>', {
          method: 'POST',
          body: fd
        })
        .then(function(res){ return res.json(); })
        .then(function(data){
          if(data && data.status === 'success'){
            tr.parentNode.removeChild(tr);
            renumberRows();
            applyFilters();
          } else {
            alert(data.message || 'Delete failed');
          }
        })
        .catch(function(){
          alert('Request failed');
        });
      }
    });

    applyFilters();
  })();
</script>

<style>
  .gold-theme-wrapper {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #faf7f0;
    min-height: calc(100vh - 57px);
    margin-top: 57px;
    padding-bottom: 50px;
    color: #1a1610;
  }

  .page-title {
    font-family: 'Literata', Georgia, serif;
    font-weight: 600;
    color: #78520a;
  }

  .btn-gold-outline {
    color: #b8860b;
    border-color: #e8e0cc;
    background: #ffffff;
    font-weight: 600;
    transition: all 0.2s;
  }
  .btn-gold-outline:hover {
    background: #f5e9c0;
    color: #78520a;
    border-color: #b8860b;
    text-decoration: none;
  }

  .btn-gold {
    background: #b8860b;
    color: #ffffff;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    transition: all 0.2s;
  }
  .btn-gold:hover {
    background: #78520a;
    color: #ffffff;
  }

  .filter-card {
    background: #ffffff;
    border: 1px solid #e8e0cc;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(184,134,11,0.03);
  }

  .table-card {
    background: #ffffff;
    border: 1px solid #e8e0cc;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(184,134,11,0.04);
  }

  .badge-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.75rem;
  }
  .status-active {
    background: #eaf4ee;
    color: #1a6645;
  }
  .status-inactive {
    background: #fef2f2;
    color: #b91c1c;
  }

  .modal-custom {
    border-radius: 16px;
    border: 1px solid #e8e0cc;
    box-shadow: 0 8px 32px rgba(184,134,11,0.08);
  }
  .modal-custom .modal-header {
    background: #faf7f0;
    border-bottom: 1px solid #e8e0cc;
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
  }
  .modal-custom .modal-footer {
    border-top: 1px solid #e8e0cc;
    background: #faf7f0;
    border-bottom-left-radius: 16px;
    border-bottom-right-radius: 16px;
  }
  
  .font-title {
    font-family: 'Literata', Georgia, serif;
    font-weight: 600;
    color: #78520a;
  }
</style>
