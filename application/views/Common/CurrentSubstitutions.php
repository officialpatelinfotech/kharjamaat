<div class="container margintopcontainer pt-5">
  <div class="col-12 mb-3 mb-md-0">
    <a href="<?php echo isset($from) ? base_url("common/deliverydashboard?from=" . $from) : ""; ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="heading text-center mb-3">Current Substitutions</h4>
  <div class="container mt-4">
    <div class="card mb-3">
      <div class="card-body p-3">
        <form method="get" action="<?php echo base_url('common/currentsubstitutions'); ?>" id="backend-filter-form">
          <div class="form-row">
            <div class="form-group col-sm-3 mb-2">
              <label class="mb-1">Name or ITS</label>
              <input type="text" name="member_name" value="<?php echo htmlspecialchars($filter_values['member_name'] ?? ''); ?>" class="form-control form-control-sm" placeholder="Search name or ITS...">
            </div>
            <div class="form-group col-sm-3 mb-2">
              <label class="mb-1">Delivery Person</label>
              <input type="text" name="dp_name" value="<?php echo htmlspecialchars($filter_values['dp_name'] ?? ''); ?>" class="form-control form-control-sm" placeholder="Search delivery person...">
            </div>
            <div class="form-group col-sm-2 mb-2">
              <label class="mb-1">Start Date</label>
              <input type="date" name="start_date" value="<?php echo htmlspecialchars($filter_values['start_date'] ?? ''); ?>" class="form-control form-control-sm">
            </div>
            <div class="form-group col-sm-2 mb-2">
              <label class="mb-1">End Date</label>
              <input type="date" name="end_date" value="<?php echo htmlspecialchars($filter_values['end_date'] ?? ''); ?>" class="form-control form-control-sm">
            </div>
            <div class="form-group col-sm-2 mb-2">
              <label class="mb-1">Active On (Quick)</label>
              <div class="d-flex align-items-center">
                <input type="date" id="active_on_picker" class="form-control form-control-sm" value="<?php echo htmlspecialchars($filter_values['active_on'] ?? ''); ?>" />
              </div>
              <small class="text-muted">Fetch active substitutions for a single date (AJAX)</small>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-sm-2 mb-2 d-flex align-items-center">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="show_all" name="show_all" value="1" <?php echo !empty($filter_values['show_all']) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="show_all">Show All</label>
              </div>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <button type="submit" class="btn btn-sm btn-primary mr-2">Apply</button>
            <a href="<?php echo base_url('common/currentsubstitutions'); ?>" class="btn btn-sm btn-outline-secondary mr-2">Reset</a>
            <small class="text-muted">Showing <?php echo count($current_substitutions); ?> record(s)</small>
          </div>
        </form>
      </div>
    </div>

    <div class="table-responsive" style="max-height:60vh;overflow:auto;">
      <table class="table table-striped table-bordered mb-0" id="substitutions-table">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>ITS ID</th>
            <th>Member</th>
            <th>Delivery Person</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($current_substitutions) && is_array($current_substitutions)): ?>
            <?php foreach ($current_substitutions as $key => $row): ?>
              <tr>
                <td class="ov-id" data-id="<?php echo (int)($row['id'] ?? 0); ?>"><?php echo ($key + 1); ?></td>
                <td class="ov-user-id"><?php echo htmlspecialchars($row['user_id'] ?? ''); ?></td>
                <td class="ov-user-name"><?php echo htmlspecialchars($row['user_name'] ?? ''); ?></td>
                <td class="ov-dp-name" data-dp-id="<?php echo htmlspecialchars($row['dp_id'] ?? ''); ?>"><?php echo htmlspecialchars($row['dp_name'] ?? ''); ?></td>
                <td class="ov-start"><?php echo htmlspecialchars($row['start_date'] ?? ''); ?></td>
                <td class="ov-end"><?php echo htmlspecialchars($row['end_date'] ?? ''); ?></td>
                <td class="ov-actions text-nowrap">
                  <button class="btn btn-sm btn-primary btn-edit"><i class="fa-solid fa-edit"></i></button>
                  <button class="btn btn-sm btn-success btn-save d-none"><i class="fa-solid fa-check"></i></button>
                  <button class="btn btn-sm btn-secondary btn-cancel d-none"><i class="fa-solid fa-times"></i></button>
                  <button class="btn btn-sm btn-danger btn-delete"><i class="fa-solid fa-trash"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-center text-muted">No substitutions found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  (function() {
    const tbl = document.getElementById('substitutions-table');
    if (!tbl) return;
    const tbody = tbl.querySelector('tbody');
    const activeOnPicker = document.getElementById('active_on_picker');
    const dpOptions = <?php echo json_encode(array_map(function ($d) {
                        return ['id' => $d['id'], 'name' => $d['name'], 'phone' => $d['phone']];
                      }, isset($all_dp) ? $all_dp : [])); ?>;

    function buildDpSelect(selectedId) {
      let html = '<select class="form-control form-control-sm ov-dp-select">';
      html += '<option value="">-- select --</option>';
      dpOptions.forEach(o => {
        html += '<option value="' + o.id + '" ' + (o.id == selectedId ? 'selected' : '') + '>' + o.name + '</option>';
      });
      html += '</select>';
      return html;
    }

    function enterEdit(row) {
      if (row.classList.contains('editing')) return;
      row.classList.add('editing');
      const dpCell = row.querySelector('.ov-dp-name');
      const currentDpId = dpCell.getAttribute('data-dp-id');
      dpCell.setAttribute('data-orig-name', dpCell.textContent);
      dpCell.innerHTML = buildDpSelect(currentDpId);
      row.querySelector('.btn-edit').classList.add('d-none');
      row.querySelector('.btn-delete').classList.add('d-none');
      row.querySelector('.btn-save').classList.remove('d-none');
      row.querySelector('.btn-cancel').classList.remove('d-none');
    }

    function exitEdit(row, revert) {
      const dpCell = row.querySelector('.ov-dp-name');
      if (revert) {
        dpCell.textContent = dpCell.getAttribute('data-orig-name') || '';
      }
      row.classList.remove('editing');
      row.querySelector('.btn-edit').classList.remove('d-none');
      row.querySelector('.btn-delete').classList.remove('d-none');
      row.querySelector('.btn-save').classList.add('d-none');
      row.querySelector('.btn-cancel').classList.add('d-none');
    }

    function saveRow(row) {
      const id = row.querySelector('.ov-id').getAttribute('data-id');
      const sel = row.querySelector('.ov-dp-name select');
      const dp_id = sel ? sel.value : '';
      if (!dp_id) {
        alert('Please select a delivery person');
        return;
      }
      row.classList.add('table-warning');
      fetch('<?php echo base_url('common/update_delivery_override'); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'id=' + encodeURIComponent(id) + '&dp_id=' + encodeURIComponent(dp_id)
      }).then(r => r.json()).then(j => {
        if (j.success) {
          const opt = dpOptions.find(o => o.id == dp_id);
          row.querySelector('.ov-dp-name').textContent = opt ? opt.name : '';
          row.querySelector('.ov-dp-name').setAttribute('data-dp-id', dp_id);
          exitEdit(row, false);
        } else {
          alert(j.message || 'Update failed');
        }
      }).catch(() => alert('Network error')).finally(() => row.classList.remove('table-warning'));
    }

    function deleteRow(row) {
      if (!confirm('Delete this substitution?')) return;
      const id = row.querySelector('.ov-id').getAttribute('data-id');
      row.classList.add('table-danger');
      fetch('<?php echo base_url('common/delete_delivery_override'); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'id=' + encodeURIComponent(id)
      }).then(r => r.json()).then(j => {
        if (j.success) {
          row.parentNode.removeChild(row);
        } else {
          alert(j.message || 'Delete failed');
        }
      }).catch(() => alert('Network error')).finally(() => row.classList.remove('table-danger'));
    }

    tbody.addEventListener('click', function(e) {
      const btn = e.target.closest('button');
      if (!btn) return;
      const row = btn.closest('tr');
      if (btn.classList.contains('btn-edit')) enterEdit(row);
      else if (btn.classList.contains('btn-cancel')) exitEdit(row, true);
      else if (btn.classList.contains('btn-save')) saveRow(row);
      else if (btn.classList.contains('btn-delete')) deleteRow(row);
    });

    function rebuildTable(rows) {
      tbody.innerHTML = '';
      if (!rows || !rows.length) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No substitutions found.</td></tr>';
        return;
      }
      rows.forEach((r, idx) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td class="ov-id" data-id="${r.id||''}">${idx+1}</td>
          <td class="ov-user-id">${r.user_id||''}</td>
          <td class="ov-user-name">${r.user_name||''}</td>
          <td class="ov-dp-name" data-dp-id="${r.dp_id||''}">${r.dp_name||''}</td>
          <td class="ov-start">${r.start_date||''}</td>
          <td class="ov-end">${r.end_date||''}</td>
          <td class="ov-actions text-nowrap">
            <button class="btn btn-sm btn-primary btn-edit"><i class="fa-solid fa-edit"></i></button>
            <button class="btn btn-sm btn-success btn-save d-none"><i class="fa-solid fa-check"></i></button>
            <button class="btn btn-sm btn-secondary btn-cancel d-none"><i class="fa-solid fa-times"></i></button>
            <button class="btn btn-sm btn-danger btn-delete"><i class="fa-solid fa-trash"></i></button>
          </td>`;
        tbody.appendChild(tr);
      });
    }

    if (activeOnPicker) {
      let lastFetchedDate = null;
      let inFlight = false;
      activeOnPicker.addEventListener('change', () => {
        const val = activeOnPicker.value;
        if (!val) return; // ignore empty
        if (val === lastFetchedDate) return; // prevent duplicate fetch for same date
        if (inFlight) return; // avoid concurrent requests
        inFlight = true;
        activeOnPicker.classList.add('loading');
        fetch('<?php echo base_url('common/substitutions_by_date'); ?>?date=' + encodeURIComponent(val))
          .then(r => r.json())
          .then(j => {
            if (j.success) {
              lastFetchedDate = val;
              rebuildTable(j.rows);
            } else {
              alert(j.message || 'Failed to fetch');
            }
          })
          .catch(() => alert('Network error'))
          .finally(() => {
            inFlight = false;
            activeOnPicker.classList.remove('loading');
          });
      });
    }
  })();
</script>

<style>
  #substitutions-table thead th {
    position: sticky;
    top: 0;
    z-index: 10;
  }

  #substitutions-table td,
  #substitutions-table th {
    white-space: nowrap;
  }

  #substitutions-table .ov-actions button {
    margin-right: 2px;
  }

  #substitutions-table select.ov-dp-select {
    min-width: 140px;
  }
</style>