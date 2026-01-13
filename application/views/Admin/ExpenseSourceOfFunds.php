<div class="container margintopcontainer pt-3">
  <style>
    .sof-table-container { max-height: 420px; overflow: auto; }
    .sof-table thead th { position: sticky; top: 0; background: #fff; z-index: 3; box-shadow: 0 2px 2px -1px rgba(0,0,0,0.1); }
  </style>

  <div class="row align-items-center mb-2">
    <div class="col-4 text-left">
      <a href="<?php echo base_url('admin/expense'); ?>" class="btn btn-sm btn-outline-secondary" aria-label="Back to Expense"><i class="fa fa-arrow-left"></i></a>
    </div>
    <div class="col-4 text-center">
      <h4 class="m-0">Source of Funds</h4>
    </div>
    <div class="col-4 text-right">
      <button id="sofAddBtn" type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#sofAddModal">
        Add SOF
      </button>
    </div>
  </div>
  <hr>

  <div class="card mb-3">
    <div class="card-body py-2">
      <div class="form-row align-items-end">
        <div class="col-md-6 mb-2">
          <label class="small mb-1">Name</label>
          <input type="text" id="sofFilterName" class="form-control form-control-sm" placeholder="Filter by name">
        </div>
      </div>
      <button type="button" class="btn btn-sm btn-outline-secondary" id="sofClearFilters">Clear</button>
      <span class="small text-muted ml-2" id="sofFilterSummary" aria-live="polite"></span>
    </div>
  </div>

  <?php $sources = (isset($sources) && is_array($sources)) ? $sources : []; ?>

  <div class="card p-3">
    <div class="sof-table-container">
      <table class="table table-striped table-bordered sof-table" id="sofTable">
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
                <td><?php echo htmlspecialchars($name); ?></td>
                <td><?php echo htmlspecialchars($status); ?></td>
                <td>
                  <button type="button" class="btn btn-sm btn-outline-primary sof-edit" data-toggle="modal" data-target="#sofAddModal">Edit</button>
                  <button type="button" class="btn btn-sm btn-outline-danger sof-delete">Delete</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr class="sof-empty">
              <td colspan="4" class="text-center">No records found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add SOF Modal -->
<div class="modal fade" id="sofAddModal" tabindex="-1" role="dialog" aria-labelledby="sofAddModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sofAddModalLabel">Add Source of Funds</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="sofEditing" value="0">
        <div class="form-group">
          <label for="sofName">Name</label>
          <input type="text" class="form-control" id="sofName" placeholder="e.g. Donations" required>
        </div>
        <div class="form-group">
          <label for="sofStatus">Status</label>
          <select class="form-control" id="sofStatus">
            <option value="Active" selected>Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="sofSaveBtn">Save</button>
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

    document.addEventListener('DOMContentLoaded', function(){
      var filterInput = document.getElementById('sofFilterName');
      var clearBtn = document.getElementById('sofClearFilters');
      var saveBtn = document.getElementById('sofSaveBtn');
      var nameInput = document.getElementById('sofName');
      var statusInput = document.getElementById('sofStatus');
      var editingInput = document.getElementById('sofEditing');
      var modalTitle = document.getElementById('sofAddModalLabel');
      var tbody = document.querySelector('#sofTable tbody');
      var editingRow = null;

      function resetModal(){
        if(editingInput) editingInput.value = '0';
        editingRow = null;
        if(modalTitle) modalTitle.textContent = 'Add Source of Funds';
        if(saveBtn) saveBtn.textContent = 'Save';
        if(nameInput) nameInput.value = '';
        if(statusInput) statusInput.value = 'Active';
      }

      if(filterInput) filterInput.addEventListener('input', applyFilters);
      if(clearBtn) clearBtn.addEventListener('click', function(){ if(filterInput) filterInput.value = ''; applyFilters(); });

      if(window.jQuery){
        // Ensure focus is never left inside an aria-hidden modal
        window.jQuery('#sofAddModal').on('shown.bs.modal', function(){
          try{ window.jQuery('#sofName').trigger('focus'); }catch(e){}
        });
        window.jQuery('#sofAddModal').on('hide.bs.modal', function(){
          try{ var active = document.activeElement; if(active && typeof active.blur === 'function') active.blur(); }catch(e){}
        });
        window.jQuery('#sofAddModal').on('hidden.bs.modal', function(){
          resetModal();
          try{ window.jQuery('#sofAddBtn').trigger('focus'); }catch(e){}
        });
      }

      // Delegate edit/delete actions and set editingRow before modal opens
      if(tbody){
        tbody.addEventListener('click', function(e){
          var t = e.target;
          if(!t) return;
          var tr = t.closest('tr');
          if(!tr || tr.classList.contains('sof-empty')) return;

          if(t.classList.contains('sof-delete')){
            var name = tr.children[1] ? (tr.children[1].textContent || '').trim() : '';
            if(!window.confirm('Delete SOF: ' + name + ' ?')) return;
            var id = tr.getAttribute('data-id');
            if(!id){ alert('Missing id'); return; }
            fetch('<?php echo base_url("admin/expense/source-delete") ?>', {
              method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'id=' + encodeURIComponent(id)
            }).then(function(r){ return r.text(); }).then(function(text){
              var resp = null; try { resp = JSON.parse(text); } catch(e) { resp = null; }
              if(resp && resp.status === 'success'){
                tr.parentNode.removeChild(tr);
                if(tbody.querySelectorAll('tr').length === 0){ var empty = document.createElement('tr'); empty.className = 'sof-empty'; empty.innerHTML = '<td colspan="4" class="text-center">No records found.</td>'; tbody.appendChild(empty); }
                renumberRows();
                applyFilters();
              } else {
                if (<?php echo (defined('ENVIRONMENT') && ENVIRONMENT === 'development') ? 'true' : 'false'; ?>) {
                  alert(text || JSON.stringify(resp) || 'Delete failed');
                } else {
                  alert((resp && resp.message) ? resp.message : 'Delete failed');
                }
              }
            }).catch(function(){ alert('Network error'); });
            return;
          }

          if(t.classList.contains('sof-edit')){
            editingRow = tr;
            if(editingInput) editingInput.value = '1';
            if(modalTitle) modalTitle.textContent = 'Edit Source of Funds';
            if(saveBtn) saveBtn.textContent = 'Update';
            var currentName = tr.children[1] ? (tr.children[1].textContent || '').trim() : '';
            var currentStatus = tr.getAttribute('data-status') || (tr.children[2] ? (tr.children[2].textContent || '').trim() : 'Active');
            if(nameInput) nameInput.value = currentName;
            if(statusInput) statusInput.value = currentStatus || 'Active';
            return; // allow modal to open via data-toggle
          }
        });
      }

      if(saveBtn && nameInput && statusInput && tbody){
        saveBtn.addEventListener('click', function(){
          var name = (nameInput.value || '').trim(); var status = (statusInput.value || 'Active');
          if(!name){ nameInput.focus(); return; }

          if(editingInput && editingInput.value === '1' && editingRow){
            var id = editingRow.getAttribute('data-id');
            if(!id){ alert('Missing id'); return; }
            fetch('<?php echo base_url("admin/expense/source-update") ?>', {
              method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: 'id='+encodeURIComponent(id)+'&name='+encodeURIComponent(name)+'&status='+encodeURIComponent(status)
            }).then(function(r){ return r.text(); }).then(function(text){
              var resp = null; try { resp = JSON.parse(text); } catch(e) { resp = null; }
              if(resp && resp.status === 'success'){
                editingRow.setAttribute('data-name', name.toLowerCase()); editingRow.setAttribute('data-status', status);
                if(editingRow.children[1]) editingRow.children[1].textContent = name;
                if(editingRow.children[2]) editingRow.children[2].textContent = status;
                if(window.jQuery){ window.jQuery('#sofAddModal').modal('hide'); }
                applyFilters();
              } else {
                if (<?php echo (defined('ENVIRONMENT') && ENVIRONMENT === 'development') ? 'true' : 'false'; ?>) {
                  alert(text || JSON.stringify(resp) || 'Update failed');
                } else {
                  alert((resp && resp.message) ? resp.message : 'Update failed');
                }
              }
            }).catch(function(){ alert('Network error'); });
          } else {
            // create
            fetch('<?php echo base_url("admin/expense/source-create") ?>', {
              method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: 'name='+encodeURIComponent(name)+'&status='+encodeURIComponent(status)
            }).then(function(r){ return r.text(); }).then(function(text){
              var resp = null; try { resp = JSON.parse(text); } catch(e) { resp = null; }
              if(resp && resp.status === 'success'){
                var empty = tbody.querySelector('tr.sof-empty'); if(empty) empty.parentNode.removeChild(empty);
                var tr = document.createElement('tr'); tr.setAttribute('data-id', resp.id); tr.setAttribute('data-name', name.toLowerCase()); tr.setAttribute('data-status', status);
                tr.innerHTML = '<td></td>' + '<td>'+escapeHtml(name)+'</td>' + '<td>'+escapeHtml(status)+'</td>' + '<td>' + '<button type="button" class="btn btn-sm btn-outline-primary sof-edit" data-toggle="modal" data-target="#sofAddModal">Edit</button> ' + '<button type="button" class="btn btn-sm btn-outline-danger sof-delete">Delete</button>' + '</td>';
                tbody.appendChild(tr);
                if(window.jQuery){ window.jQuery('#sofAddModal').modal('hide'); }
                renumberRows();
                applyFilters();
              } else {
                if (<?php echo (defined('ENVIRONMENT') && ENVIRONMENT === 'development') ? 'true' : 'false'; ?>) {
                  alert(text || JSON.stringify(resp) || 'Create failed');
                } else {
                  alert((resp && resp.message) ? resp.message : 'Create failed');
                }
              }
            }).catch(function(){ alert('Network error'); });
          }
        });
      }

      renumberRows();
      applyFilters();
    });
  })();
</script>
