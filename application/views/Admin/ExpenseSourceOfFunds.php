<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">

<div class="gold-theme-wrapper">
  <div class="container pt-5">
    <style>
      .sof-table-container { max-height: 420px; overflow: auto; border-radius: 8px; border: 1px solid #e8e0cc; }
      .sof-table thead th { position: sticky; top: 0; background: #f5e9c0; color: #5a5244; z-index: 3; box-shadow: 0 2px 2px -1px rgba(0,0,0,0.1); border-bottom: 2px solid #e8e0cc; }
      .sof-table tbody tr { background: #ffffff; }
      .sof-table tbody tr:hover { background: #faf7f0; }
    </style>

    <div class="row align-items-center mb-3">
      <div class="col-4 text-left">
        <a href="<?php echo base_url('admin/expense'); ?>" class="btn btn-sm btn-gold-outline" aria-label="Back to Expense"><i class="fa fa-arrow-left"></i> Back</a>
      </div>
      <div class="col-4 text-center">
        <h4 class="m-0 page-title">Source of Funds</h4>
      </div>
      <div class="col-4 text-right">
        <button id="sofAddBtn" type="button" class="btn btn-sm btn-gold" data-toggle="modal" data-target="#sofAddModal">
          <i class="fa fa-plus"></i> Add SOF
        </button>
      </div>
    </div>
    <hr style="border-top: 1px solid #e8e0cc;">

    <div class="card mb-3 filter-card">
      <div class="card-body py-2">
        <div class="form-row align-items-end">
          <div class="col-md-6 mb-2">
            <label class="small mb-1 font-weight-bold" style="color: #5a5244;">Filter by Name</label>
            <input type="text" id="sofFilterName" class="form-control form-control-sm" placeholder="Search Source of Funds...">
          </div>
        </div>
        <button type="button" class="btn btn-xs btn-outline-secondary btn-sm" id="sofClearFilters">Clear</button>
        <span class="small text-muted ml-2" id="sofFilterSummary" aria-live="polite"></span>
      </div>
    </div>

    <?php $sources = (isset($sources) && is_array($sources)) ? $sources : []; ?>

    <div class="card p-3 table-card">
      <div class="sof-table-container">
        <table class="table table-bordered mb-0 sof-table" id="sofTable">
          <thead>
            <tr>
              <th style="width: 90px;">Sr.No</th>
              <th>Name</th>
              <th style="width: 140px;">Status</th>
              <th style="width: 170px;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($sources)) : ?>
              <?php $sr = 1; foreach ($sources as $row) : ?>
                <?php
                  $id = isset($row['id']) ? (string)$row['id'] : '';
                  $name = isset($row['name']) ? (string)$row['name'] : '';
                  $status = isset($row['status']) ? (string)$row['status'] : 'Active';
                ?>
                <tr data-id="<?php echo htmlspecialchars($id); ?>" data-name="<?php echo htmlspecialchars(strtolower($name)); ?>" data-status="<?php echo htmlspecialchars($status); ?>">
                  <td><?php echo (int)$sr; $sr++; ?></td>
                  <td class="font-weight-semibold"><?php echo htmlspecialchars($name); ?></td>
                  <td>
                    <span class="badge badge-status <?php echo strtolower($status) === 'active' ? 'status-active' : 'status-inactive'; ?>">
                      <?php echo htmlspecialchars($status); ?>
                    </span>
                  </td>
                  <td>
                    <button type="button" class="btn btn-xs btn-gold-outline py-1 px-2 mr-1 sof-edit" data-toggle="modal" data-target="#sofAddModal">Edit</button>
                    <button type="button" class="btn btn-xs btn-danger py-1 px-2 sof-delete">Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr class="sof-empty">
                <td colspan="4" class="text-center text-muted py-3">No records found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add SOF Modal -->
<div class="modal fade" id="sofAddModal" tabindex="-1" role="dialog" aria-labelledby="sofAddModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-custom">
      <div class="modal-header">
        <h5 class="modal-title font-title" id="sofAddModalLabel">Add Source of Funds</h5>
        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="sofEditing" value="0">
        <div class="form-group">
          <label for="sofName" class="font-weight-semibold">Name</label>
          <input type="text" class="form-control" id="sofName" placeholder="e.g. Donations" required>
        </div>
        <div class="form-group">
          <label for="sofStatus" class="font-weight-semibold">Status</label>
          <select class="form-control" id="sofStatus">
            <option value="Active" selected>Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-gold" id="sofSaveBtn">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    function escapeHtml(s){
      return String(s)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;')
        .replace(/'/g,'&#039;');
    }

    function applyFilters(){
      var input = document.getElementById('sofFilterName');
      var summaryEl = document.getElementById('sofFilterSummary');
      var tbody = document.querySelector('#sofTable tbody');
      if(!input || !summaryEl || !tbody) return;

      var q = (input.value || '').trim().toLowerCase();
      var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
      var total = 0;
      var shown = 0;

      rows.forEach(function(tr){
        if(tr.classList.contains('sof-empty')) return;
        total++;
        var name = (tr.getAttribute('data-name') || '');
        var ok = !q || name.indexOf(q) !== -1;
        tr.style.display = ok ? '' : 'none';
        if(ok) shown++;
      });

      summaryEl.textContent = total ? ('Showing ' + shown + ' of ' + total) : '';
    }

    function renumberRows(){
      var tbody = document.querySelector('#sofTable tbody');
      if(!tbody) return;
      var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
      var n = 1;
      rows.forEach(function(tr){
        if(tr.classList.contains('sof-empty')) return;
        if(tr.children && tr.children[0]) tr.children[0].textContent = String(n++);
      });
    }

    var filterInput = document.getElementById('sofFilterName');
    if(filterInput) filterInput.addEventListener('input', applyFilters);

    var clearBtn = document.getElementById('sofClearFilters');
    if(clearBtn){
      clearBtn.addEventListener('click', function(){
        if(filterInput) filterInput.value = '';
        applyFilters();
      });
    }

    // Modal reset on Add click
    var addBtn = document.getElementById('sofAddBtn');
    if(addBtn){
      addBtn.addEventListener('click', function(){
        document.getElementById('sofAddModalLabel').textContent = 'Add Source of Funds';
        document.getElementById('sofEditing').value = '0';
        document.getElementById('sofName').value = '';
        document.getElementById('sofStatus').value = 'Active';
      });
    }

    // Modal populate on Edit click
    document.addEventListener('click', function(e){
      var t = e.target;
      if(t && t.classList.contains('sof-edit')){
        var tr = t.closest('tr');
        if(!tr) return;
        var id = tr.getAttribute('data-id');
        var name = tr.getAttribute('data-name');
        var status = tr.getAttribute('data-status');

        document.getElementById('sofAddModalLabel').textContent = 'Edit Source of Funds';
        document.getElementById('sofEditing').value = id;
        document.getElementById('sofName').value = tr.children[1].textContent.trim();
        document.getElementById('sofStatus').value = status;
      }
    });

    // Save button click
    var saveBtn = document.getElementById('sofSaveBtn');
    if(saveBtn){
      saveBtn.addEventListener('click', function(){
        var name = document.getElementById('sofName').value.trim();
        var status = document.getElementById('sofStatus').value;
        var editingId = parseInt(document.getElementById('sofEditing').value) || 0;

        if(!name){
          alert('Name is required');
          return;
        }

        var url = '<?php echo site_url("admin/expense/source-create"); ?>';
        var fd = new FormData();
        fd.append('name', name);
        fd.append('status', status);

        if(editingId > 0){
          url = '<?php echo site_url("admin/expense/source-update"); ?>';
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
            $('#sofAddModal').modal('hide');
            location.reload();
          } else {
            alert(data.message || 'Action failed');
          }
        })
        .catch(function(){
          saveBtn.disabled = false;
          alert('Request failed');
        });
      });
    }

    // Delete source click
    document.addEventListener('click', function(e){
      var t = e.target;
      if(t && t.classList.contains('sof-delete')){
        var tr = t.closest('tr');
        if(!tr) return;
        var id = tr.getAttribute('data-id');
        var name = tr.getAttribute('data-name');

        if(!confirm('Are you sure you want to delete "' + name + '"? Any related expense records will also be deleted.')){
          return;
        }

        var fd = new FormData();
        fd.append('id', id);

        fetch('<?php echo site_url("admin/expense/source-delete"); ?>', {
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
