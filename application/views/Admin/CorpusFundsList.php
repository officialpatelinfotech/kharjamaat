<div class="container margintopcontainer pt-5">
  <a href="<?php echo site_url('admin/corpusfunds'); ?>" class="btn btn-outline-secondary mb-3" aria-label="Back to Corpus Funds Menu"><i class="fa fa-arrow-left"></i></a>
  <h4 class="heading text-center mb-4">Created Corpus Funds</h4>
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <table class="table table-hover m-0" id="corpusFundsTable">
        <thead class="thead-light">
          <tr>
            <th data-sort-key="sr" class="sortable">Sr. No</th>
            <th data-sort-key="title" class="sortable">Title</th>
            <th data-sort-key="amount" class="sortable">Amount (&#8377;)</th>
            <th data-sort-key="created" class="sortable">Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($funds) && !empty($funds)): $sr = 1; foreach ($funds as $f): ?>
              <tr data-fund-row="<?php echo (int)$f['id']; ?>">
                <td><?php echo $sr++; ?></td>
                <td class="fund-title" data-fund-title><?php echo htmlspecialchars($f['title']); ?></td>
                <td class="fund-amount" data-fund-amount>₹<?php echo format_inr_no_decimals($f['amount']); ?></td>
                <td><?php echo date('d-m-Y', strtotime($f['created_at'])); ?></td>
                <td>
                  <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary edit-fund-btn" data-fund-id="<?php echo (int)$f['id']; ?>">Edit</button>
                    <button type="button" class="btn btn-outline-danger delete-fund-btn" data-fund-id="<?php echo (int)$f['id']; ?>">Delete</button>
                  </div>
                </td>
              </tr>
            <?php endforeach;
          else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted">No corpus funds created yet.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <div id="fundsStatus" class="px-3 py-2 small text-muted"></div>
    </div>
  </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="editFundModal" style="display:none; background:rgba(0,0,0,0.5); position:fixed; top:0; left:0; right:0; bottom:0;">
  <div class="modal-dialog" role="document" style="max-width:500px; margin:60px auto;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Corpus Fund</h5>
        <button type="button" class="close" data-dismiss-edit-fund aria-label="Close" style="border:none; background:transparent; font-size:24px; line-height:1;">&times;</button>
      </div>
      <div class="modal-body" id="editFundModalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss-edit-fund>Close</button>
        <button type="button" class="btn btn-primary" id="saveFundChangesBtn">Save</button>
      </div>
    </div>
  </div>
</div>
<script>
  (function() {
        // Sorting helpers
        function parseAmount(text) {
          var cleaned = String(text || '').trim().replace(/[₹,\s]/g, '').replace(/[^0-9.\-]/g, '');
          var n = cleaned ? Number(cleaned) : 0;
          return isFinite(n) ? n : 0;
        }
        function parseDateDMY(text) {
          var t = String(text || '').trim();
          var parts = t.split('-');
          if (parts.length !== 3) return 0;
          var d = parseInt(parts[0], 10);
          var m = parseInt(parts[1], 10) - 1;
          var y = parseInt(parts[2], 10);
          var dt = new Date(y, m, d);
          return dt.getTime();
        }
        function getCellValue(row, key) {
          if (key === 'sr') {
            var srCell = row.cells[0];
            return parseInt(srCell.textContent.trim(), 10) || 0;
          }
          if (key === 'title') {
            var titleCell = row.querySelector('[data-fund-title]') || row.cells[1];
            return (titleCell ? titleCell.textContent : '').trim().toLowerCase();
          }
          if (key === 'amount') {
            var amtCell = row.querySelector('[data-fund-amount]') || row.cells[2];
            return parseAmount(amtCell ? amtCell.textContent : '0');
          }
          if (key === 'created') {
            var dateCell = row.cells[3];
            return parseDateDMY(dateCell ? dateCell.textContent : '');
          }
          return '';
        }
        function sortTableBy(key, direction) {
          var table = document.getElementById('corpusFundsTable');
          if (!table) return;
          var tbody = table.querySelector('tbody');
          if (!tbody) return;
          var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
          rows.sort(function(a, b) {
            var va = getCellValue(a, key);
            var vb = getCellValue(b, key);
            if (typeof va === 'number' && typeof vb === 'number') {
              return direction === 'asc' ? va - vb : vb - va;
            }
            // string compare
            if (va < vb) return direction === 'asc' ? -1 : 1;
            if (va > vb) return direction === 'asc' ? 1 : -1;
            return 0;
          });
          // Re-append in sorted order
          rows.forEach(function(r, idx){
            tbody.appendChild(r);
            // Optionally re-number Sr. No after sort
            var srCell = r.cells[0];
            if (srCell) srCell.textContent = String(idx + 1);
          });
        }
        // Attach click handlers to sortable headers
        var currentSort = { key: null, dir: 'asc' };
        [].forEach.call(document.querySelectorAll('#corpusFundsTable thead th.sortable'), function(th) {
          th.style.cursor = 'pointer';
          th.addEventListener('click', function() {
            var key = this.getAttribute('data-sort-key');
            if (!key) return;
            // Toggle direction if clicking same column
            if (currentSort.key === key) {
              currentSort.dir = currentSort.dir === 'asc' ? 'desc' : 'asc';
            } else {
              currentSort.key = key;
              currentSort.dir = 'asc';
            }
            sortTableBy(currentSort.key, currentSort.dir);
          });
        });
    var modal = document.getElementById('editFundModal');
    var body = document.getElementById('editFundModalBody');

    function closeModal() {
      modal.style.display = 'none';
      body.innerHTML = '';
    }

    function openModal() {
      modal.style.display = 'block';
    }
    [].forEach.call(document.querySelectorAll('[data-dismiss-edit-fund]'), function(btn) {
      btn.addEventListener('click', closeModal);
    });
    modal.addEventListener('click', function(e) {
      if (e.target === modal) closeModal();
    });

    function escapeHtml(str) {
      return String(str).replace(/[&<>"]/g, function(s) {
        return ({
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;'
        } [s]);
      });
    }

    var currentFundId = null;
    [].forEach.call(document.querySelectorAll('.edit-fund-btn'), function(btn) {
      btn.addEventListener('click', function() {
        var fid = this.getAttribute('data-fund-id');
        currentFundId = fid;
        var row = document.querySelector('tr[data-fund-row="' + fid + '"]');
        if (!row) {
          return;
        }
        var title = row.querySelector('[data-fund-title]').textContent.trim();
        var amountText = row.querySelector('[data-fund-amount]').textContent.trim();
        // Normalize formatted INR like "₹ 1,23,456" to plain numeric string
        var cleanedAmount = amountText.replace(/[₹,\s]/g, '').replace(/[^0-9.\-]/g, '');
        var amount = cleanedAmount ? Number(cleanedAmount) : 0;
        var formHtml = '<form id="editFundForm">' +
          '<div class="mb-3"><label class="form-label small">Title</label>' +
          '<input type="text" class="form-control form-control-sm" name="title" value="' + escapeHtml(title) + '" /></div>' +
          '<div class="mb-3"><label class="form-label small">Amount (INR)</label>' +
          '<input type="number" min="0" step="0.01" class="form-control form-control-sm" name="amount" value="' + amount + '" required /></div>' +
          '<div class="form-check mb-3">' +
          '<input class="form-check-input" type="checkbox" id="propagateCb" name="propagate" checked />' +
          '<label class="form-check-label small" for="propagateCb">Propagate new amount to all HOF assignments</label>' +
          '</div>' +
          '<div class="mb-2"><label class="form-label small">Description (optional)</label>' +
          '<textarea class="form-control form-control-sm" rows="2" name="description"></textarea></div>' +
          '</form>' +
          '<div class="alert alert-info py-1 px-2 small">Editing fund ID ' + fid + '. Changes may affect totals for all HOFs if propagated.</div>';
        body.innerHTML = formHtml;
        openModal();
      });
    });

    var saveBtn = document.getElementById('saveFundChangesBtn');
    saveBtn.addEventListener('click', function() {
      var form = document.getElementById('editFundForm');
      if (!form || currentFundId === null) {
        return;
      }
      var amountInput = form.querySelector('input[name="amount"]');
      if (!amountInput || amountInput.value.trim() === '') {
        alert('Amount required');
        return;
      }
      var payload = new FormData();
      payload.append('fund_id', currentFundId);
      payload.append('amount', amountInput.value.trim());
      var titleVal = form.querySelector('input[name="title"]').value.trim();
      if (titleVal !== '') {
        payload.append('title', titleVal);
      }
      var descVal = form.querySelector('textarea[name="description"]').value.trim();
      if (descVal !== '') {
        payload.append('description', descVal);
      }
      var propagate = form.querySelector('#propagateCb').checked ? '1' : '0';
      payload.append('propagate', propagate);
      // Use jQuery AJAX for better error handling (server may return HTML on redirects)
      $.ajax({
        url: '<?php echo site_url('admin/corpusfunds_update_fund'); ?>',
        method: 'POST',
        data: payload,
        processData: false,
        contentType: false,
        dataType: 'json'
      }).done(function(resp) {
        if (!resp || !resp.success) {
          alert('Update failed: ' + (resp && resp.error ? resp.error : 'Unknown'));
          return;
        }
        var row = document.querySelector('tr[data-fund-row="' + currentFundId + '"]');
        if (row) {
          var amtCell = row.querySelector('[data-fund-amount]');
          if (amtCell) {
            var inr = Number(resp.amount).toLocaleString('en-IN', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
            amtCell.textContent = '₹' + inr;
          }
          // Update title cell if user supplied a new title or server returned one
          var newTitle = null;
          try { if (typeof titleVal !== 'undefined' && titleVal !== '') newTitle = titleVal; } catch(e){}
          if (!newTitle && resp.title) newTitle = resp.title;
          if (newTitle) {
            var titleCell = row.querySelector('[data-fund-title]');
            if (titleCell) { titleCell.textContent = newTitle; }
          }
          if (resp.fund_updated && resp.assignments_updated && propagate === '1') {
            row.classList.add('table-success');
            setTimeout(function() { row.classList.remove('table-success'); }, 1800);
          }
        }
        closeModal();
      }).fail(function(jqXHR, textStatus, errorThrown) {
        var msg = 'Network error: ' + textStatus;
        var rt = jqXHR && jqXHR.responseText ? jqXHR.responseText : '';
        if (rt && rt.indexOf('<meta') !== -1) {
          msg += '\nServer returned HTML (possible redirect to login).';
        } else if (rt) {
          try { var parsed = JSON.parse(rt); if (parsed && parsed.error) msg += '\n' + parsed.error; } catch (e) { msg += '\n' + rt.substring(0,200); }
        }
        alert(msg);
      });
    });

    // Delete handlers
    [].forEach.call(document.querySelectorAll('.delete-fund-btn'), function(btn) {
      btn.addEventListener('click', function() {
        var fid = this.getAttribute('data-fund-id');
        if (!fid) return;
        if (!confirm('Delete fund ID ' + fid + '? This will remove all its assignments.')) return;
        var payload = new FormData();
        payload.append('fund_id', fid);
        // Use jQuery AJAX for delete
        $.ajax({
          url: '<?php echo site_url('admin/corpusfunds_delete_fund'); ?>',
          method: 'POST',
          data: payload,
          processData: false,
          contentType: false,
          dataType: 'json'
        }).done(function(resp) {
          if (!resp || !resp.success) {
            alert('Delete failed: ' + (resp && resp.error ? resp.error : 'Unknown'));
            return;
          }
          var row = document.querySelector('tr[data-fund-row="' + fid + '"]');
          if (row) { row.parentNode.removeChild(row); }
          var statusBox = document.getElementById('fundsStatus');
          if (statusBox) {
            statusBox.textContent = 'Fund ' + fid + ' deleted. Assignments removed: ' + (resp.assignments_deleted || 0);
            statusBox.className = 'px-3 py-2 small text-success';
          }
        }).fail(function(jqXHR, textStatus) {
          var msg = 'Network error: ' + textStatus;
          var rt = jqXHR && jqXHR.responseText ? jqXHR.responseText : '';
          if (rt && rt.indexOf('<meta') !== -1) {
            msg += '\nServer returned HTML (possible redirect to login).';
          } else if (rt) {
            try { var parsed = JSON.parse(rt); if (parsed && parsed.error) msg += '\n' + parsed.error; } catch (e) { msg += '\n' + rt.substring(0,200); }
          }
          alert(msg);
          var statusBox = document.getElementById('fundsStatus');
          if (statusBox) {
            statusBox.textContent = 'Delete error: ' + msg;
            statusBox.className = 'px-3 py-2 small text-danger';
          }
        });
      });
    });
  })();
</script>