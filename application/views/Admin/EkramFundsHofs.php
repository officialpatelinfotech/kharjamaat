<style>
  @media (max-width: 576px) {
    .margintopcontainer {
      padding-top: 1rem !important;
    }

    #hofFilters .row>[class^="col-"],
    #hofFilters .row>[class*=" col-"] {
      margin-bottom: .5rem;
    }

    #hofEkramTable th,
    #hofEkramTable td {
      white-space: nowrap;
    }

    #fundModal .modal-dialog {
      width: 95% !important;
      margin: 20px auto !important;
    }

    #fundModal .table {
      font-size: 0.9rem;
    }

    .btn-group.btn-group-sm .btn {
      padding: .25rem .5rem;
    }
  }
</style>
<div class="container margintopcontainer pt-5">
  <div class="d-flex justify-content-between mb-3">
    <a href="<?php echo site_url('admin/ekramfunds'); ?>" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i></a>
  </div>
  <h4 class="heading text-center mb-3">Ekram Funds Assigned</h4>
  <form id="hofFilters" class="mb-3 p-3 border rounded bg-light">
    <div class="row">
      <div class="col-md-3 mb-2">
        <label class="small text-muted mb-1">Name or ITS</label>
        <input type="text" class="form-control form-control-sm" id="filterName" placeholder="Search name or ITS" />
      </div>
      <div class="col-md-2 mb-2">
        <label class="small text-muted mb-1">Sector</label>
        <select class="form-control form-control-sm" id="filterSector">
          <option value="">All</option>
          <?php if (isset($sectors) && !empty($sectors)): foreach ($sectors as $sec): ?>
              <option value="<?php echo strtolower(htmlspecialchars($sec)); ?>"><?php echo htmlspecialchars($sec); ?></option>
          <?php endforeach;
          endif; ?>
        </select>
      </div>
      <div class="col-md-2 mb-2">
        <label class="small text-muted mb-1">Sub-Sector</label>
        <select class="form-control form-control-sm" id="filterSubSector" disabled>
          <option value="">All</option>
        </select>
      </div>
      <!-- Hijri Year filter removed per request -->
      <div class="col-md-3 mb-2 d-flex align-items-end">
        <button type="button" class="btn btn-sm btn-outline-secondary" id="clearFilters">Clear</button>
        <span class="small text-muted" id="filterSummary" aria-live="polite"></span>
      </div>
    </div>
  </form>
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover m-0" id="hofEkramTable">
          <thead>
            <tr>
              <th data-sort="number">#</th>
              <th data-sort="string">ITS ID</th>
              <th data-sort="string">Name</th>
              <th data-sort="string">Sector</th>
              <th data-sort="string">Sub-Sector</th>
              <th data-sort="number">Funds Count</th>
              <th data-sort="number">Ekram Total (₹)</th>
              <th data-sort="none">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($hofs)): $i = 1;
              foreach ($hofs as $h): $hid = (int)$h['HOF_ID'];
                $total = isset($h['ekram_total']) ? (float)$h['ekram_total'] : 0;
                $years = isset($hof_hijri_years[$hid]) ? implode(',', $hof_hijri_years[$hid]) : ''; ?>
                <tr
                  data-hof="<?php echo $hid; ?>"
                  data-name="<?php echo strtolower(htmlspecialchars($h['Full_Name'])); ?>"
                  data-sector="<?php echo strtolower(htmlspecialchars($h['Sector'])); ?>"
                  data-subsector="<?php echo strtolower(htmlspecialchars($h['Sub_Sector'])); ?>"
                  data-hijri-years="<?php echo htmlspecialchars($years); ?>"
                  data-last-updated="<?php echo htmlspecialchars(isset($h['last_updated']) ? $h['last_updated'] : ''); ?>">
                  <td><?php echo $i++; ?></td>
                  <td><?php echo htmlspecialchars($h['ITS_ID']); ?></td>
                  <td><?php echo htmlspecialchars($h['Full_Name']); ?></td>
                  <td><?php echo htmlspecialchars($h['Sector']); ?></td>
                  <td><?php echo htmlspecialchars($h['Sub_Sector']); ?></td>
                  <td><?php echo isset($h['ekram_count']) ? (int)$h['ekram_count'] : (isset($hof_fund_details[$hid]) ? count($hof_fund_details[$hid]) : 0); ?></td>
                  <td>₹<?php echo format_inr_no_decimals($total); ?></td>
                  <td>
                    <?php if ($total > 0 && isset($hof_fund_details[$hid])): ?>
                      <button type="button" class="btn btn-sm btn-info me-1 view-details-btn mb-0 mb-md-2" data-hof="<?php echo $hid; ?>">View Details</button>
                      <button type="button" class="btn btn-sm btn-primary view-funds-btn" data-hof="<?php echo $hid; ?>">View Funds</button>
                    <?php else: ?>
                      <button type="button" class="btn btn-sm btn-secondary" disabled>None</button>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach;
            else: ?>
              <tr>
                <td colspan="8" class="text-center text-muted">No eligible HOFs found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="fundModal" style="display:none; background:rgba(0,0,0,0.5); position:fixed; top:0; left:0; right:0; bottom:0;">
  <div class="modal-dialog" role="document" style="max-width:600px; margin:60px auto; width:95%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ekram Funds</h5>
        <button type="button" class="close" data-close-modal aria-label="Close" style="border:none; background:transparent; font-size:24px; line-height:1;">&times;</button>
      </div>
      <div class="modal-body">
        <div id="fundModalBody"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-close-modal>Close</button>
      </div>
    </div>
  </div>
</div>
<script>
  window.hofFunds = <?php echo json_encode(isset($hof_fund_details) ? $hof_fund_details : []); ?>;
  (function() {
    const modal = document.getElementById('fundModal');
    const body = document.getElementById('fundModalBody');

    function close() {
      modal.style.display = 'none';
      body.innerHTML = '';
    }

    function open() {
      modal.style.display = 'block';
    }
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        close();
      }
    });
    [].forEach.call(document.querySelectorAll('[data-close-modal]'), function(btn) {
      btn.addEventListener('click', close);
    });
    [].forEach.call(document.querySelectorAll('.view-funds-btn'), function(btn) {
      btn.addEventListener('click', function() {
        const hid = this.getAttribute('data-hof');
        const tr = this.closest('tr');
        const nameCell = tr ? tr.children[2] : null; // 3rd column is Name
        const memberName = nameCell ? nameCell.textContent.trim() : '';
        // Keep a static modal title; show member name in body
        var titleEl = document.querySelector('#fundModal .modal-title');
        if (titleEl) {
          titleEl.textContent = 'Ekram Funds';
        }
        const funds = window.hofFunds[hid] || [];
        // Deduplicate funds by fund_id so each title appears once
        const uniq = {};
        for (let i = 0; i < funds.length; i++) {
          const f = funds[i];
          if (!uniq[f.fund_id]) uniq[f.fund_id] = f;
        }
        const fundList = Object.keys(uniq).map(function(k) {
          return uniq[k];
        });
        // Sort funds by hijri_year descending (newest first). If hijri_year missing, try to extract numeric year from title.
        fundList.sort(function(a, b) {
          var ay = a && a.hijri_year ? parseInt(a.hijri_year) : (a && a.title && (a.title.match(/(1[4-6]\d{2})/) || [])[1] ? parseInt((a.title.match(/(1[4-6]\d{2})/) || [])[1]) : 0);
          var by = b && b.hijri_year ? parseInt(b.hijri_year) : (b && b.title && (b.title.match(/(1[4-6]\d{2})/) || [])[1] ? parseInt((b.title.match(/(1[4-6]\d{2})/) || [])[1]) : 0);
          return by - ay;
        });
        if (!funds.length) {
          body.innerHTML = (memberName ? '<div class="mb-2"><span>Name:</span> <strong>' + escapeHtml(memberName) + '</strong></div>' : '') +
            '<p class="text-muted mb-2">No funds assigned.</p>';
          open();
          return;
        }
        let html = '';
        if (memberName) {
          html += '<div class="mb-2"><span>Name:</span> <strong>' + escapeHtml(memberName) + '</strong></div>';
        }
        html += '<div id="fundUpdateStatus" class="alert alert-success py-1 px-2 small" style="display:none;"></div>';
        html += '<div class="table-responsive"><form id="fundEditForm"><table class="table table-sm table-bordered">';
        html += '<thead><tr><th>Fund Year</th><th>Amount (₹)</th><th>Actions</th></tr></thead><tbody>';
        fundList.forEach(function(f) {
          var yearLabel = f.hijri_year ? String(f.hijri_year) : (f.title || '').replace(/[^0-9]/g, '');
          html += '<tr data-fund-row="' + f.fund_id + '">' +
            '<td>' + escapeHtml(yearLabel) + '</td>' +
            '<td class="amount-cell" data-fund="' + f.fund_id + '">₹' + Number(f.amount || 0).toLocaleString('en-IN', {
              minimumFractionDigits: 0,
              maximumFractionDigits: 0
            }) + '</td>' +
            '<td><button type="button" class="btn btn-sm btn-outline-primary edit-fund-btn" data-fund="' + f.fund_id + '">Edit</button></td>' +
            '</tr>';
        });
        html += '</tbody></table></form></div>';
        body.innerHTML = html;
        const form = document.getElementById('fundEditForm');
        // Enable inline edit: replace amount text with input on click
        [].forEach.call(form.querySelectorAll('.edit-fund-btn'), function(eb) {
          eb.addEventListener('click', function() {
            const fid = this.getAttribute('data-fund');
            const row = form.querySelector('tr[data-fund-row="' + fid + '"]');
            const cell = form.querySelector('.amount-cell[data-fund="' + fid + '"]');
            if (!row || !cell) return;
            if (cell.querySelector('input')) return; // already editing
            const currentText = cell.textContent.trim();
            const cleaned = currentText.replace(/[₹,\s]/g, '').replace(/[^0-9.\-]/g, '');
            const currentVal = cleaned ? Number(cleaned) : 0;
            // Replace amount cell with input
            cell.innerHTML = '<input type="number" step="0.01" min="0" class="form-control form-control-sm" name="fund_' + fid + '" value="' + currentVal + '" />';
            // Replace actions cell with Save/Cancel inline controls
            const actionsCell = row.children[2];
            const originalActions = actionsCell.innerHTML;
            actionsCell.innerHTML = '<div class="btn-group btn-group-sm" role="group">\
              <button type="button" class="btn btn-success row-save-btn">Save</button>\
              <button type="button" class="btn btn-outline-secondary row-cancel-btn">Cancel</button>\
            </div>';

            // Cancel restores original content
            actionsCell.querySelector('.row-cancel-btn').addEventListener('click', function() {
              // Restore amount text using previous numeric value
              const restored = Number(currentVal || 0).toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
              });
              cell.textContent = '₹' + restored;
              actionsCell.innerHTML = originalActions;
              // Rebind Edit on restored button so it works again
              const reb = actionsCell.querySelector('.edit-fund-btn');
              if (reb) {
                reb.addEventListener('click', function() {
                  eb.click();
                });
              }
            });

            // Save sends only this row's change (use jQuery AJAX to detect HTML redirects)
            actionsCell.querySelector('.row-save-btn').addEventListener('click', function() {
              const inp = cell.querySelector('input[name="fund_' + fid + '"]');
              const newVal = parseFloat(inp && inp.value ? inp.value : '0');
              const payload = new FormData();
              payload.append('hof_id', hid);
              payload.append('assignments', JSON.stringify({
                [fid]: newVal
              }));
              $.ajax({
                url: '<?php echo site_url('admin/ekramfunds_update_assignments'); ?>',
                method: 'POST',
                data: payload,
                processData: false,
                contentType: false,
                dataType: 'text',
                headers: {
                  'Accept': 'application/json, text/plain, */*'
                }
              }).done(function(text) {
                var resp = null;
                try {
                  resp = JSON.parse(text);
                } catch (e) {
                  alert('Session expired or server error. Please refresh and login again.');
                  return;
                }
                if (!resp || !resp.success) {
                  alert('Update failed: ' + (resp && resp.errors ? resp.errors.join(', ') : 'Unknown error'));
                  return;
                }
                // Update cache and UI
                window.hofFunds[hid].forEach(function(f) {
                  if (String(f.fund_id) === String(fid)) {
                    f.amount = newVal;
                  }
                });
                cell.textContent = '₹' + Number(newVal || 0).toLocaleString('en-IN', {
                  minimumFractionDigits: 0,
                  maximumFractionDigits: 0
                });
                // Update total cell in main table row
                const rowBtn = document.querySelector('button.view-funds-btn[data-hof="' + hid + '"]');
                if (rowBtn) {
                  const trMain = rowBtn.closest('tr');
                  const totalCell = trMain.querySelector('td:nth-child(7)');
                  const serverTotal = (typeof resp.new_total === 'number') ? resp.new_total : window.hofFunds[hid].reduce((sum, x) => sum + parseFloat(x.amount || 0), 0);
                  if (totalCell) totalCell.textContent = '₹' + Number(serverTotal || 0).toLocaleString('en-IN', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                  });
                }
                // Show success notification
                var statusEl = document.getElementById('fundUpdateStatus');
                if (statusEl) {
                  statusEl.textContent = 'Updated successfully.';
                  statusEl.className = 'alert alert-success py-1 px-2 small';
                  statusEl.style.display = '';
                  setTimeout(function() {
                    statusEl.style.display = 'none';
                  }, 2000);
                }
                // Restore actions and rebind Edit
                actionsCell.innerHTML = originalActions;
                const reb = actionsCell.querySelector('.edit-fund-btn');
                if (reb) {
                  reb.addEventListener('click', function() {
                    eb.click();
                  });
                }
              }).fail(function(jqXHR, textStatus) {
                var rt = jqXHR && jqXHR.responseText ? jqXHR.responseText : '';
                var msg = 'Network error: ' + textStatus;
                if (rt && (rt.indexOf('<meta') !== -1 || rt.indexOf('<!DOCTYPE') !== -1)) {
                  msg += '\nSession expired or server error.';
                } else if (rt) {
                  try {
                    var parsed = JSON.parse(rt);
                    if (parsed && parsed.error) msg += '\n' + parsed.error;
                  } catch (e) {
                    msg += '\n' + rt.substring(0, 200);
                  }
                }
                alert(msg);
              });
            });
          });
        });
        // Save handler: only send edited inputs (batch save) using jQuery AJAX
        form.addEventListener('submit', function(ev) {
          ev.preventDefault();
          const inputs = form.querySelectorAll('input[name^="fund_"]');
          if (inputs.length === 0) {
            close();
            return;
          }
          const assignments = {};
          [].forEach.call(inputs, function(inp) {
            const name = inp.getAttribute('name');
            const fid = name.replace('fund_', '');
            assignments[fid] = parseFloat(inp.value || '0');
          });
          const payload = new FormData();
          payload.append('hof_id', hid);
          payload.append('assignments', JSON.stringify(assignments));
          $.ajax({
            url: '<?php echo site_url('admin/ekramfunds_update_assignments'); ?>',
            method: 'POST',
            data: payload,
            processData: false,
            contentType: false,
            dataType: 'text',
            headers: {
              'Accept': 'application/json, text/plain, */*'
            }
          }).done(function(text) {
            var resp = null;
            try {
              resp = JSON.parse(text);
            } catch (e) {
              alert('Session expired or server error. Please refresh and login again.');
              return;
            }
            if (!resp || !resp.success) {
              alert('Update failed: ' + (resp && resp.errors ? resp.errors.join(', ') : 'Unknown error'));
              return;
            }
            // Update cache amounts
            window.hofFunds[hid].forEach(function(f) {
              if (assignments[f.fund_id] !== undefined) {
                f.amount = assignments[f.fund_id];
              }
            });
            // Update main row total using server value if provided
            const rowBtn = document.querySelector('button.view-funds-btn[data-hof="' + hid + '"]');
            if (rowBtn) {
              const tr = rowBtn.closest('tr');
              const totalCell = tr.querySelector('td:nth-child(7)');
              const serverTotal = (typeof resp.new_total === 'number') ? resp.new_total : window.hofFunds[hid].reduce((sum, x) => sum + parseFloat(x.amount || 0), 0);
              if (totalCell) {
                totalCell.textContent = '₹' + Number(serverTotal || 0).toLocaleString('en-IN', {
                  minimumFractionDigits: 0,
                  maximumFractionDigits: 0
                });
              }
            }
            // Re-render modal in read-only view
            let ro = (memberName ? '<div class="mb-2"><span class="small text-muted">Member:</span> <strong>' + escapeHtml(memberName) + '</strong></div>' : '');
            ro += '<table class="table table-sm table-bordered">';
            ro += '<thead><tr><th>Fund Title</th><th>Amount (₹)</th></tr></thead><tbody>';
            // Render funds sorted by hijri_year descending for read-only view as well
            var sortedFundsRO = (window.hofFunds[hid] || []).slice().sort(function(a, b) {
              var ay = a && a.hijri_year ? parseInt(a.hijri_year) : 0;
              var by = b && b.hijri_year ? parseInt(b.hijri_year) : 0;
              return by - ay;
            });
            sortedFundsRO.forEach(function(f) {
              ro += '<tr><td>' + escapeHtml(f.title) + '</td><td>₹' + Number(f.amount || 0).toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
              }) + '</td></tr>';
            });
            ro += '</tbody></table>';
            body.innerHTML = ro;
            var statusEl = document.getElementById('fundUpdateStatus');
            if (statusEl) {
              statusEl.textContent = 'Changes saved.';
              statusEl.className = 'alert alert-success py-1 px-2 small';
              statusEl.style.display = '';
              setTimeout(function() {
                statusEl.style.display = 'none';
              }, 2500);
            }
          }).fail(function(jqXHR, textStatus) {
            var rt = jqXHR && jqXHR.responseText ? jqXHR.responseText : '';
            var msg = 'Network error: ' + textStatus;
            if (rt && (rt.indexOf('<meta') !== -1 || rt.indexOf('<!DOCTYPE') !== -1)) {
              msg += '\nSession expired or server error.';
            } else if (rt) {
              try {
                var parsed = JSON.parse(rt);
                if (parsed && parsed.error) msg += '\n' + parsed.error;
              } catch (e) {
                msg += '\n' + rt.substring(0, 200);
              }
            }
            alert(msg);
          });
        });
        open();
      });
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
  })();
  // HOF Details modal logic
  (function() {
    // create details modal markup
    var detailsModal = document.createElement('div');
    detailsModal.className = 'modal';
    detailsModal.id = 'hofDetailsModal';
    detailsModal.setAttribute('tabindex', '-1');
    detailsModal.style.cssText = 'display:none; background:rgba(0,0,0,0.5); position:fixed; top:0; left:0; right:0; bottom:0;';
    detailsModal.innerHTML = '\n      <div class="modal-dialog" role="document" style="max-width:420px; margin:80px auto; width:95%;">\n        <div class="modal-content">\n          <div class="modal-header">\n            <h5 class="modal-title">Ekram Fund Details</h5>\n            <button type="button" class="close" data-close-details aria-label="Close" style="border:none; background:transparent; font-size:24px; line-height:1;">&times;</button>\n          </div>\n          <div class="modal-body">\n            <div class="small text-muted">Name</div><div id="hofDetailsName" class="mb-2"></div>\n            <div class="small text-muted">ITS ID</div><div id="hofDetailsITS" class="mb-2"></div>\n            <div class="small text-muted">Amount</div><div id="hofDetailsAmount" class="mb-2"></div>\n                       <div class="small text-muted">Last Updated</div><div id="hofDetailsLast" class="mb-2"></div>\n          </div>\n          <div class="modal-footer">\n            <button type="button" class="btn btn-secondary" data-close-details>Close</button>\n          </div>\n        </div>\n      </div>';
    document.body.appendChild(detailsModal);

    function open() {
      detailsModal.style.display = 'block';
    }

    function close() {
      detailsModal.style.display = 'none';
    }
    detailsModal.addEventListener('click', function(e) {
      if (e.target === detailsModal) close();
    });
    [].forEach.call(detailsModal.querySelectorAll('[data-close-details]'), function(b) {
      b.addEventListener('click', close);
    });

    function formatVal(v) {
      return v ? v : '—';
    }

    [].forEach.call(document.querySelectorAll('.view-details-btn'), function(btn) {
      btn.addEventListener('click', function() {
        var hid = this.getAttribute('data-hof');
        var tr = this.closest('tr');
        var name = tr ? tr.children[2].textContent.trim() : '';
        var its = tr ? tr.children[1].textContent.trim() : '';
        var amount = tr ? tr.children[6].textContent.trim() : '';
        var last = tr ? tr.getAttribute('data-last-updated') : '';
        document.getElementById('hofDetailsName').textContent = formatVal(name);
        document.getElementById('hofDetailsITS').textContent = formatVal(its);
        document.getElementById('hofDetailsAmount').textContent = formatVal(amount);
        // Format last-updated into dd-mm-yyyy if possible
        function formatDateDMY(s) {
          if (!s) return '—';
          // Expecting formats like 'YYYY-MM-DD' or 'YYYY-MM-DD HH:MM:SS'
          var m = String(s).match(/^(\d{4})-(\d{2})-(\d{2})(?:\s+(\d{2}):(\d{2}):(\d{2}))?/);
          if (m) {
            return m[3] + '-' + m[2] + '-' + m[1];
          }
          // Fallback: try JS Date parse
          var d = new Date(s);
          if (!isNaN(d.getTime())) {
            var dd = ('0' + d.getDate()).slice(-2);
            var mm = ('0' + (d.getMonth() + 1)).slice(-2);
            var yy = d.getFullYear();
            return dd + '-' + mm + '-' + yy;
          }
          return s;
        }
        document.getElementById('hofDetailsLast').textContent = formatVal(last ? formatDateDMY(last) : '—');
        open();
      });
    });
  })();
  // Filtering logic
  (function() {
    const nameInput = document.getElementById('filterName');
    const sectorSelect = document.getElementById('filterSector');
    const subSectorSelect = document.getElementById('filterSubSector');
    const clearBtn = document.getElementById('clearFilters');
    const summaryEl = document.getElementById('filterSummary');
    const tbody = document.querySelector('#hofEkramTable tbody');

    // Hijri year filter removed: no population required

    function applyFilters() {
      const nameVal = nameInput.value.trim().toLowerCase();
      const sectorVal = sectorSelect.value.trim().toLowerCase();
      const subSectorVal = subSectorSelect.value.trim().toLowerCase();
      const yearVal = ''; // Hijri year filter removed
      let shown = 0;
      let total = 0;
      [].forEach.call(tbody.querySelectorAll('tr'), function(r) {
        if (r.querySelector('td') === null) return; // skip placeholder row
        total++;
        const rName = r.getAttribute('data-name') || '';
        const rIts = ((r.children && r.children[1] ? r.children[1].textContent : '') || '').trim().toLowerCase();
        const rSector = r.getAttribute('data-sector') || '';
        const rSubSector = r.getAttribute('data-subsector') || '';
        const rYears = (r.getAttribute('data-hijri-years') || '').split(',').filter(x => x);
        let ok = true;
        if (nameVal && rName.indexOf(nameVal) === -1 && rIts.indexOf(nameVal) === -1) ok = false;
        if (sectorVal && rSector.indexOf(sectorVal) === -1) ok = false;
        if (subSectorVal && rSubSector.indexOf(subSectorVal) === -1) ok = false;
        if (yearVal && yearVal !== '') {
          // Prefer explicit row attribute match
          if (rYears.indexOf(yearVal) === -1) {
            // Fallback: check cached modal fund data for this HOF
            var hid = r.getAttribute('data-hof');
            var found = false;
            try {
              if (window.hofFunds && hid && window.hofFunds[hid]) {
                found = window.hofFunds[hid].some(function(f) {
                  if (!f) return false;
                  if (f.hijri_year && String(f.hijri_year) === String(yearVal)) return true;
                  if (f.fund_year && String(f.fund_year) === String(yearVal)) return true;
                  if (f.year && String(f.year) === String(yearVal)) return true;
                  if (f.title && String(f.title).indexOf(String(yearVal)) !== -1) return true;
                  return false;
                });
              }
            } catch (e) {
              found = false;
            }
            if (!found) ok = false;
          }
        }
        r.style.display = ok ? '' : 'none';
        if (ok) shown++;
      });
      summaryEl.textContent = shown + ' / ' + total + ' shown';
    }
    ['input', 'change'].forEach(function(evt) {
      nameInput.addEventListener(evt, applyFilters);
    });
    sectorSelect.addEventListener('change', function() {
      // Populate sub-sector options based on selected sector
      const map = window.sectorSubMap || {}; // lower-case keys
      const sel = sectorSelect.value;
      subSectorSelect.innerHTML = '<option value="">All</option>';
      if (sel && map[sel]) {
        map[sel].forEach(function(sub) {
          const opt = document.createElement('option');
          opt.value = sub.toLowerCase();
          opt.textContent = sub;
          subSectorSelect.appendChild(opt);
        });
        subSectorSelect.disabled = false;
      } else {
        subSectorSelect.disabled = true;
      }
      applyFilters();
    });
    subSectorSelect.addEventListener('change', applyFilters);
    clearBtn.addEventListener('click', function() {
      nameInput.value = '';
      sectorSelect.value = '';
      subSectorSelect.value = '';
      yearSelect.value = '';
      subSectorSelect.disabled = true;
      subSectorSelect.innerHTML = '<option value="">All</option>';
      applyFilters();
    });
    applyFilters();
  })();
  // Simple client-side sorting for main table
  (function() {
    const table = document.getElementById('hofEkramTable');
    if (!table) return;
    const thead = table.querySelector('thead');
    const tbody = table.querySelector('tbody');
    const headers = [].slice.call(thead.querySelectorAll('th'));
    const dirState = {}; // column index -> asc/desc
    headers.forEach(function(th) {
      th.dataset.label = th.textContent.trim();
    });
    headers.forEach(function(th, idx) {
      const type = th.getAttribute('data-sort');
      if (!type || type === 'none') return;
      th.style.cursor = 'pointer';
      th.addEventListener('click', function() {
        const current = dirState[idx] === 'asc' ? 'desc' : 'asc';
        dirState[idx] = current;
        // Reset all headers to base label and remove indicators
        headers.forEach(function(h) {
          h.innerHTML = h.dataset.label;
        });
        // Add single indicator span
        const indicator = document.createElement('span');
        indicator.className = 'sort-indicator';
        indicator.textContent = current === 'asc' ? ' ▲' : ' ▼';
        th.appendChild(indicator);
        const rows = [].slice.call(tbody.querySelectorAll('tr'));
        rows.sort(function(a, b) {
          const aCell = a.children[idx];
          const bCell = b.children[idx];
          const aVal = aCell ? aCell.textContent.trim() : '';
          const bVal = bCell ? bCell.textContent.trim() : '';
          let cmp = 0;
          if (type === 'number') {
            const aNum = parseFloat(aVal.replace(/[₹,\s]/g, '')) || 0;
            const bNum = parseFloat(bVal.replace(/[₹,\s]/g, '')) || 0;
            cmp = aNum === bNum ? 0 : (aNum < bNum ? -1 : 1);
          } else {
            cmp = aVal.localeCompare(bVal, undefined, {
              sensitivity: 'base'
            });
          }
          return current === 'asc' ? cmp : -cmp;
        });
        rows.forEach(r => tbody.appendChild(r));
        if (headers[0] && headers[0].getAttribute('data-sort') === 'number') {
          let serial = 1;
          [].forEach.call(tbody.querySelectorAll('tr'), function(r) {
            const cell = r.children[0];
            if (cell) cell.textContent = serial++;
          });
        }
      });
    });
  })();
</script>
<style>
  .sort-indicator {
    font-size: 0.85em;
  }

  /* Ensure spacing between Clear button and summary, independent of Bootstrap utilities */
  #clearFilters {
    margin-right: 8px;
  }

  #filterSummary {
    margin-left: 4px;
    display: inline-block;
  }
</style>
<script>
  // Expose sector->subSector mapping (lowercase keys) for dynamic dropdown
  window.sectorSubMap = (function(raw) {
    var out = {};
    try {
      var obj = raw ? JSON.parse(raw) : {};
    } catch (e) {
      obj = {};
    }
    Object.keys(obj).forEach(function(sec) {
      out[sec.toLowerCase()] = obj[sec];
    });
    return out;
  })('<?php echo json_encode(isset($sector_sub_map) ? $sector_sub_map : []); ?>');
</script>