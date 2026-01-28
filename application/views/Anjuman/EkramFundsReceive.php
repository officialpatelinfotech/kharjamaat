<div class="container-fluid margintopcontainer pt-5">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <a href="<?= base_url('anjuman'); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="m-0 flex-grow-1 text-center mb-3">Ekram Funds - Receive Payments</h4>
  <?php if (!empty($message)): ?>
    <div class="alert alert-success py-2 px-3"><?= htmlspecialchars($message); ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 px-3"><?= htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <!-- Filters and table (assignments will be rendered client-side if not provided server-side) -->
  <div class="card mb-2">
    <div class="card-body py-2">
      <div class="row g-2 align-items-center mb-3">
        <div class="col-md-3 mb-2 mb-md-0">
          <label class="small mb-1">Filter by Name</label>
          <input type="text" class="form-control form-control-sm" id="filterName" placeholder="Search HOF name..." value="<?= isset($filter_name) ? htmlspecialchars($filter_name) : ''; ?>" />
        </div>
        <div class="col-md-2 mb-2 mb-md-0">
          <label class="small mb-1">ITS ID</label>
          <input type="text" class="form-control form-control-sm" id="filterITS" name="its_id" placeholder="ITS or HOF ID" value="<?= isset($filter_its) ? htmlspecialchars($filter_its) : ''; ?>" />
        </div>
        <div class="col-md-2 mb-2 mb-md-0">
          <label class="small mb-1">Sector</label>
          <select class="form-control form-control-sm" id="filterSector">
            <option value="">All</option>
            <?php foreach ($sectorList as $sec => $label): ?>
              <option value="<?= htmlspecialchars($sec); ?>" <?= isset($filter_sector) && $filter_sector === $sec ? 'selected' : ''; ?>><?= htmlspecialchars($label); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2 mb-2 mb-md-0">
          <label class="small mb-1">Sub-Sector</label>
          <select class="form-control form-control-sm" id="filterSubSector">
            <option value="">All</option>
            <?php foreach ($subSectorList as $ssec => $label): ?>
              <option value="<?= htmlspecialchars($ssec); ?>" <?= isset($filter_sub_sector) && $filter_sub_sector === $ssec ? 'selected' : ''; ?>><?= htmlspecialchars($label); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <!-- Filter by Year removed -->
        <div class="col-md-2">
          <button type="button" id="applyFiltersBtn" class="btn btn-primary btn-sm mb-2 mb-md-0">Apply</button>
          <button type="button" id="clearFilters" class="btn btn-secondary btn-sm">Clear</button>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover m-0" id="hofTable">
          <thead>
            <tr>
              <th class="td-idx">Sr. No.</th>
              <th>ITS ID</th>
              <th>Name</th>
              <th>Sector</th>
              <th>Sub-Sector</th>
              <th class="text-right">Assigned</th>
              <th class="text-right">Paid</th>
              <th class="text-right">Due</th>
              <th class="text-right">Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- rows will be injected here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script>
    (function() {
      // Modal stacking helpers (same behavior as Corpus receive view)
      $(document).on('show.bs.modal', '.modal', function() {
        var $modal = $(this);
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $modal.css('z-index', zIndex);
        setTimeout(function() {
          $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
      });
      $(document).on('hidden.bs.modal', '.modal', function() {
        if ($('.modal:visible').length) {
          $('body').addClass('modal-open');
        }
      });

      function fmtINR(num) {
        var n = Number(num) || 0;
        n = Math.round(n);
        return n.toLocaleString('en-IN', {
          minimumFractionDigits: 0,
          maximumFractionDigits: 0
        });
      }

      function reindexRows() {
        var idx = 1;
        $('#hofTable tbody tr').each(function() {
          var $tr = $(this);
          if ($tr.is('#noFilterResults')) return;
          if ($tr.is(':visible')) {
            $tr.find('.td-idx').text(idx++);
          }
        });
      }

      function sortTableBy(colIndex, type, asc) {
        var rows = $('#hofTable tbody tr').filter(function() {
          return !$(this).is('#noFilterResults');
        }).get();
        rows.sort(function(a, b) {
          var A = $(a).children().eq(colIndex),
            B = $(b).children().eq(colIndex),
            aVal, bVal;
          if (type === 'number') {
            aVal = parseFloat(A.data('val'));
            if (isNaN(aVal)) aVal = parseFloat((A.text() || '').replace(/[^0-9.-]/g, '')) || 0;
            bVal = parseFloat(B.data('val'));
            if (isNaN(bVal)) bVal = parseFloat((B.text() || '').replace(/[^0-9.-]/g, '')) || 0;
          } else {
            aVal = (A.text() || '').toLowerCase();
            bVal = (B.text() || '').toLowerCase();
          }
          if (aVal < bVal) return asc ? -1 : 1;
          if (aVal > bVal) return asc ? 1 : -1;
          return 0;
        });
        var tbody = $('#hofTable tbody');
        rows.forEach(function(r) {
          tbody.append(r);
        });
        reindexRows();
      }

      $('#hofTable thead').on('click', '.th-sort', function() {
        var $th = $(this);
        var type = $th.data('type') || 'string';
        var idx = $th.index();
        var asc = !$th.data('asc');
        $th.closest('tr').find('.th-sort').not($th).removeData('asc');
        $th.data('asc', asc);
        sortTableBy(idx, type, asc);
      });

      function applyFilters() {
        var nameQ = ($('#filterName').val() || '').trim().toLowerCase();
        var itsQ = ($('#filterITS').val() || '').trim().toLowerCase();
        // year filter removed
        var year = '';
        var sectorQ = ($('#filterSector').val() || '').trim().toLowerCase();
        var subSectorQ = ($('#filterSubSector').val() || '').trim().toLowerCase();
        var anyVisible = false;
        $('#hofTable tbody tr').each(function() {
          var $tr = $(this);
          if ($tr.attr('id') === 'noFilterResults') return;
          var textName = ($tr.find('.td-hofname').text() || '').trim().toLowerCase();
          var textSector = ($tr.find('.td-sector').text() || '').trim().toLowerCase();
          var textSubSector = ($tr.find('.td-subsector').text() || '').trim().toLowerCase();
          var rowFundYears = ($tr.data('fund-years') || '').toString();
          var rowIts = (($tr.data('its') || '') + '').toString().trim().toLowerCase();
          var matchName = !nameQ || textName.indexOf(nameQ) !== -1;
          var matchIts = !itsQ || rowIts.indexOf(itsQ) !== -1 || (String($tr.find('.td-its').text() || '').toLowerCase().indexOf(itsQ) !== -1);
          var matchFund = true; // no year filter
          var matchSector = !sectorQ || textSector === sectorQ;
          var matchSubSector = !subSectorQ || textSubSector === subSectorQ;
          var show = matchName && matchIts && matchFund && matchSector && matchSubSector;
          $tr.toggle(show);
          if (show) anyVisible = true;
        });
        $('#noFilterResults').toggle(!anyVisible);
        reindexRows();
      }

      $('#filterName').on('keyup change', applyFilters);
      $('#filterSector').on('change', applyFilters);
      $('#filterSubSector').on('change', applyFilters);

      $('#applyFiltersBtn').on('click', function() {
        var nameQ = ($('#filterName').val() || '').trim();
        var itsQ = ($('#filterITS').val() || '').trim();
        var sectorQ = ($('#filterSector').val() || '').trim();
        var subSectorQ = ($('#filterSubSector').val() || '').trim();
        var onlyName = nameQ && !itsQ && !sectorQ && !subSectorQ;
        if (onlyName) {
          applyFilters();
          return;
        }
        var url = '<?= base_url('anjuman/ekramfunds_receive'); ?>';
        var params = [];
        if (itsQ) params.push('its_id=' + encodeURIComponent(itsQ));
        if (sectorQ) params.push('sector=' + encodeURIComponent(sectorQ));
        if (subSectorQ) params.push('sub_sector=' + encodeURIComponent(subSectorQ));
        if (params.length) url += '?' + params.join('&');
        window.location = url;
      });

      $('#clearFilters').on('click', function() {
        $('#filterName').val('');
        $('#filterITS').val('');
        $('#filterSector').val('');
        $('#filterSubSector').val('');
        applyFilters();
      });

      // Helper: render funds list HTML into the modal for a HOF
      function renderHofFunds(funds, hofId, hofName) {
        if (!Array.isArray(funds) || funds.length === 0) {
          $('#vfFundsList').html('<div class="text-muted">No funds assigned.</div>');
          return;
        }
        var html = '<div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Fund</th><th class="text-right">Assigned</th><th class="text-right">Paid</th><th class="text-right">Due</th><th></th></tr></thead><tbody>';
        funds.forEach(function(f) {
          var title = f.hijri_year ? f.hijri_year : (f.title || '-');
          var ass = fmtINR(f.amount_assigned || 0);
          var paid = fmtINR(f.amount_paid || 0);
          var due = fmtINR(f.amount_due || 0);
          html += '<tr>' + '<td>' + $('<div/>').text(title).html() + '</td>' + '<td class="text-right">₹' + ass + '</td>' + '<td class="text-right text-success">₹' + paid + '</td>' + '<td class="text-right ' + (parseFloat(f.amount_due || 0) > 0 ? 'text-danger' : 'text-muted') + '">₹' + due + '</td>' + '<td class="text-right">' + '<div>' + '<button type="button" class="mr-2 btn btn-sm btn-primary mb-2 mb-md-0 js-receive-payment"' + ' data-hof-id="' + hofId + '" data-hof-name="' + $('<div/>').text(hofName).html() + '"' + ' data-fund-id="' + f.fund_id + '" data-fund-title="' + $('<div/>').text(title).html() + '" data-due-raw="' + (Math.round(parseFloat(f.amount_due || 0))) + '">Receive Payment</button>' + '<button type="button" class="btn btn-sm btn-info text-white js-payment-history" data-hof-id="' + hofId + '" data-fund-id="' + f.fund_id + '">Payment History</button>' + '</div>' + '</td>' + '</tr>';
        });
        html += '</tbody></table></div>';
        $('#vfFundsList').html(html);
      }

      // View funds for a HOF: fetch list via AJAX and render a compact table (parity with Corpus UI)
      $(document).on('click', '.js-view-funds', function() {
        var b = $(this);
        var hofId = b.data('hof-id');
        var hofName = b.data('hof-name') || '';
        $('#vfHofTitle').text("Name: " + hofName);
        $('#vfFundsList').html('<div class="text-muted">Loading...</div>');
        $('#viewFundsModal').modal('show');
        $.ajax({
          url: '<?= base_url('anjuman/ekramfunds_hof_funds'); ?>',
          method: 'GET',
          dataType: 'json',
          data: {
            hof_id: hofId
          },
          success: function(resp) {
            try {
              if (resp && resp.success && Array.isArray(resp.funds)) {
                renderHofFunds(resp.funds, hofId, hofName);
              } else {
                $('#vfFundsList').html('<div class="text-danger">Failed to load funds.</div>');
              }
            } catch (e) {
              $('#vfFundsList').html('<div class="text-danger">Invalid response.</div>');
            }
          },
          error: function(xhr, status, err) {
            var msg = 'Error fetching funds';
            try {
              if (xhr && xhr.responseText) {
                msg += ': ' + xhr.status + ' ' + xhr.statusText + ' - ' + $('<div/>').text(xhr.responseText.substring(0, 200)).html();
              }
            } catch (e) {}
            $('#vfFundsList').html('<div class="text-danger">' + msg + '</div>');
            console.error('ekramfunds_hof_funds error', status, err, xhr);
          }
        });
      });

      // Client-side loader: use server-provided assignments if present, otherwise fetch JSON
      function renderAssignments(rows) {
        var tbody = $('#hofTable tbody');
        tbody.empty();
        if (!rows || !rows.length) {
          tbody.append('<tr id="noFilterResults"><td colspan="9" class="text-center text-muted">No assignments found.</td></tr>');
          return;
        }
        var idx = 1;
        // collect sector/subsector and fund lists for filters
        var sectorMap = {};
        var fundMap = {};
        // Aggregate rows by hof_id so we display one row per HOF with totals
        var hofMap = {};
        rows.forEach(function(r) {
          var hid = String(r.hof_id || r.hofid || '');
          if (!hofMap[hid]) {
            hofMap[hid] = {
              hof_id: hid,
              its_id: r.its_id || r.ITS_ID || '',
              hof_name: r.hof_name || r.Full_Name || '',
              sector: r.sector || r.Sector || '',
              sub_sector: r.sub_sector || r.Sub_Sector || '',
              assigned: 0,
              paid: 0,
              fund_years: new Set()
            };
          }
          var assignedVal = parseFloat(r.amount_assigned || 0) || 0;
          var paidVal = parseFloat(r.amount_paid || 0) || 0;
          hofMap[hid].assigned += assignedVal;
          hofMap[hid].paid += paidVal;
          if (r.hijri_year) hofMap[hid].fund_years.add(String(r.hijri_year));
          else if (r.title) hofMap[hid].fund_years.add(String(r.title));
          // collect sector/subsector
          var sec = (r.sector || r.Sector || '').trim();
          var sub = (r.sub_sector || r.Sub_Sector || '').trim();
          if (sec) {
            sectorMap[sec] = sectorMap[sec] || new Set();
            if (sub) sectorMap[sec].add(sub);
          }
          // collect fund id/title
          var fid = r.fund_id || r.fundId || r.fundid || '';
          var flabel = r.hijri_year ? String(r.hijri_year) : (r.title || 'Fund ' + fid);
          if (fid) fundMap[fid] = flabel;
        });
        Object.keys(hofMap).forEach(function(hid) {
          var h = hofMap[hid];
          var its = $('<div/>').text(h.its_id).html();
          var name = $('<div/>').text(h.hof_name).html();
          var sector = $('<div/>').text(h.sector).html();
          var sub = $('<div/>').text(h.sub_sector).html();
          var assignedFmt = '₹' + Math.round(h.assigned).toLocaleString('en-IN');
          var paidFmt = '₹' + Math.round(h.paid).toLocaleString('en-IN');
          var dueVal = Math.max(0, Math.round((h.assigned || 0) - (h.paid || 0)));
          var dueFmt = '₹' + dueVal.toLocaleString('en-IN');
          var actionBtn = '<button type="button" class="btn btn-sm btn-outline-secondary js-view-funds" data-hof-id="' + hid + '" data-hof-name="' + name + '">View Funds</button>';
          var yearsArr = Array.from(h.fund_years).filter(Boolean);
          var tr = '<tr data-fund-years="' + yearsArr.join(',') + '" data-its="' + (h.its_id || '') + '">';
          tr += '<td class="td-idx">' + (idx++) + '</td>';
          tr += '<td class="td-its">' + its + '</td>';
          tr += '<td class="td-hofname">' + name + '</td>';
          tr += '<td class="td-sector">' + sector + '</td>';
          tr += '<td class="td-subsector">' + sub + '</td>';
          tr += '<td class="text-right" data-val="' + (Math.round(h.assigned) || 0) + '">' + assignedFmt + '</td>';
          tr += '<td class="text-right text-success" data-val="' + (Math.round(h.paid) || 0) + '">₹' + (Math.round(h.paid).toLocaleString('en-IN')) + '</td>';
          tr += '<td class="text-right ' + (dueVal > 0 ? 'text-danger' : 'text-muted') + '" data-val="' + dueVal + '">' + dueFmt + '</td>';
          tr += '<td class="text-right">' + actionBtn + '</td>';
          tr += '</tr>';
          tbody.append(tr);
        });
        reindexRows();
        // populate sector select and sub-sector mapping
        var sectorSelect = $('#filterSector');
        var subSectorSelect = $('#filterSubSector');
        sectorSelect.find('option:not(:first)').remove();
        // build map of sector(lower) -> sorted array of sub-sectors
        var sectorSubMap = {};
        Object.keys(sectorMap).sort().forEach(function(s) {
          var opt = $('<option>').attr('value', s.toLowerCase()).text(s);
          sectorSelect.append(opt);
          var subs = Array.from(sectorMap[s] || []).filter(Boolean).sort();
          sectorSubMap[s.toLowerCase()] = subs;
        });
        // reset sub-sector dropdown
        subSectorSelect.find('option:not(:first)').remove();
        subSectorSelect.prop('disabled', true);
        // on sector change, populate sub-sector options
        sectorSelect.off('change.fillSub').on('change.fillSub', function() {
          var v = $(this).val() || '';
          subSectorSelect.find('option:not(:first)').remove();
          if (!v) {
            subSectorSelect.prop('disabled', true);
            return;
          }
          var list = sectorSubMap[v] || [];
          if (!list.length) {
            subSectorSelect.prop('disabled', true);
            return;
          }
          list.forEach(function(ss) {
            subSectorSelect.append($('<option>').attr('value', ss.toLowerCase()).text(ss));
          });
          subSectorSelect.prop('disabled', false);
        });
        // populate fund select
        var fundSelect = $('#filterFund');
        fundSelect.find('option:not(:first)').remove();
        Object.keys(fundMap).sort(function(a, b) {
          return String(fundMap[b]).localeCompare(String(fundMap[a]));
        }).forEach(function(fid) {
          var opt = $('<option>').attr('value', fid).text(fundMap[fid]);
          fundSelect.append(opt);
        });
        // year select removed — no population required
      }

      // Attempt to use server-injected assignments if available
      var serverData = <?php echo isset($assignments) ? json_encode($assignments) : 'null'; ?>;
      if (serverData && Array.isArray(serverData) && serverData.length) {
        renderAssignments(serverData);
      } else {
        // Fetch assignments via JSON endpoint
        $.getJSON('<?= base_url('anjuman/ekramfunds_receive_data'); ?>', function(resp) {
          if (resp && resp.success && Array.isArray(resp.assignments)) {
            renderAssignments(resp.assignments);
          } else {
            renderAssignments([]);
          }
        }).fail(function() {
          renderAssignments([]);
        });
      }

      // Receive payment handler (opens modal with defaults)
      $(document).on('click', '.js-receive-payment', function() {
        var b = $(this);
        $('#rpFundId').val(b.data('fund-id'));
        $('#rpHofId').val(b.data('hof-id'));
        $('#rpHofName').text("Name: " + (b.data('hof-name') || ''));
        $('#rpFundTitle').text("Fund: " + b.data('fund-title'));
        $('#rpDue').text(b.data('due-raw'));
        $('#rpAmount').val('');
        $('#rpNotes').val('');
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var dd = String(today.getDate()).padStart(2, '0');
        $('#rpPaymentDate').val(yyyy + '-' + mm + '-' + dd);
        $('#rpPaymentMethod').val('Cash');
        $('#receivePaymentModal').modal('show');
      });

      // Client-side validation + submit: amount should not exceed due; sends POST then refreshes assignments
      $(document).on('click', '#rpSubmit', function(e) {
        e.preventDefault();
        var dueStr = $('#rpDue').text() || '0';
        var due = parseFloat(String(dueStr).replace(/[,₹\s]/g, '')) || 0;
        var amt = parseFloat($('#rpAmount').val()) || 0;
        if (amt <= 0) {
          alert('Please enter an amount');
          return false;
        }
        if (amt > due + 0.0001) {
          alert('Amount exceeds due. Due: ₹' + fmtINR(due));
          return false;
        }
        var fundId = $('#rpFundId').val();
        var hofId = $('#rpHofId').val();
        var paidAt = $('#rpPaymentDate').val();
        var method = $('#rpPaymentMethod').val();
        var notes = $('#rpNotes').val();
        var postUrl = '<?= base_url('anjuman/ekramfunds_receive_payment'); ?>';
        var payload = {
          fund_id: fundId,
          hof_id: hofId,
          amount: amt,
          notes: notes,
          payment_date: paidAt,
          payment_method: method
        };
        $('#rpSubmit').prop('disabled', true).text('Submitting...');
        $.ajax({
          url: postUrl,
          method: 'POST',
          data: payload,
          success: function(resp) {
            // Close modal then reload entire page so all UI states refresh
            $('#rpSubmit').prop('disabled', false).text('Receive Payment');
            $('#receivePaymentModal').modal('hide');
            location.reload();
          },
          error: function(xhr, status, err) {},
          error: function(xhr, status, err) {
            $('#rpSubmit').prop('disabled', false).text('Receive Payment');
            console.error('receive_payment error', status, err, xhr);
            var msg = 'Error submitting payment';
            try {
              if (xhr && xhr.responseText) msg += ': ' + xhr.status + ' ' + xhr.statusText + ' - ' + $('<div/>').text(xhr.responseText.substring(0, 200)).html();
            } catch (e) {}
            alert(msg);
          }
        });
        return false;
      });

      // Payment history (renders table and Receipt action)
      $(document).on('click', '.js-payment-history', function() {
        var b = $(this);
        var fundId = b.data('fund-id');
        var hofId = b.data('hof-id');
        $('#phList').html('Loading...');
        $('#paymentHistoryModal').modal('show');
        $.ajax({
          url: '<?= base_url('anjuman/ekramfunds_payment_history'); ?>',
          method: 'GET',
          data: {
            fund_id: fundId,
            hof_id: hofId
          },
          success: function(resp) {
            try {
              if (resp && resp.success && Array.isArray(resp.payments)) {
                if (resp.payments.length === 0) {
                  $('#phList').html('<div class="text-muted">No payments yet.</div>');
                } else {
                  var html = '<div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Date</th><th>Method</th><th class="text-right">Amount</th><th>Notes</th><th class="text-right">Actions</th></tr></thead><tbody>';

                  function fmtDateDMY(d) {
                    if (!d) return '';
                    var s = String(d).trim();
                    var dtObj = new Date(s);
                    if (!isNaN(dtObj.getTime())) {
                      var dd = ('0' + dtObj.getDate()).slice(-2);
                      var mm = ('0' + (dtObj.getMonth() + 1)).slice(-2);
                      var yyyy = dtObj.getFullYear();
                      return dd + '-' + mm + '-' + yyyy;
                    }
                    var m = s.match(/(\d{4})-(\d{2})-(\d{2})/);
                    if (m) return m[3] + '-' + m[2] + '-' + m[1];
                    return '';
                  }
                  resp.payments.forEach(function(p) {
                    var amt = Math.round(parseFloat(p.amount_paid || 0));
                    var dt = fmtDateDMY(p.paid_at || '');
                    var paymentId = p.id || '';
                    var method = p.payment_method ? $('<div/>').text(p.payment_method).html() : '';
                    var notes = p.notes ? $('<div/>').text(p.notes).html() : '';
                    html += '<tr>' + '<td>' + dt + '</td>' + '<td>' + method + '</td>' + '<td class="text-right">₹' + fmtINR(amt) + '</td>' + '<td>' + notes + '</td>' + '<td class="text-right"><a class="btn btn-outline-secondary btn-sm view-invoice" target="_blank" data-payment-id="' + paymentId + '">View</a></td>' + '</tr>';
                  });
                  html += '</tbody></table></div>';
                  $('#phList').html(html);
                }
              } else {
                $('#phList').html('<div class="text-danger">Failed to load history.</div>');
              }
            } catch (e) {
              $('#phList').html('<div class="text-danger">Invalid response.</div>');
            }
          },
          error: function() {
            $('#phList').html('<div class="text-danger">Error fetching history.</div>');
          }
        });
      });

    })();
  </script>

  <!-- View Funds Modal -->
  <div class="modal fade" id="viewFundsModal" tabindex="-1" role="dialog" aria-labelledby="viewFundsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="vfHofTitle">Funds</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="vfFundsList">
          <!-- loaded via AJAX -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Receive Payment Modal -->
  <div class="modal fade" id="receivePaymentModal" tabindex="-1" role="dialog" aria-labelledby="receivePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Receive Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="rpFundId" />
          <input type="hidden" id="rpHofId" />
          <p id="rpHofName" class="font-weight-bold"></p>
          <p id="rpFundTitle" class="font-weight-bold"></p>
          <p>Due: <span id="rpDue">0</span></p>
          <div class="form-group">
            <label>Amount</label>
            <input id="rpAmount" class="form-control" type="number" step="0.01" min="0" />
          </div>
          <div class="form-group">
            <label>Date</label>
            <input id="rpPaymentDate" class="form-control" type="date" />
          </div>
          <div class="form-group">
            <label>Method</label>
            <select id="rpPaymentMethod" class="form-control">
              <option>Cash</option>
              <option>Cheque</option>
            </select>
          </div>
          <div class="form-group">
            <label>Notes</label>
            <textarea id="rpNotes" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" id="rpSubmit" class="btn btn-primary">Receive Payment</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Payment History Modal -->
  <div class="modal fade" id="paymentHistoryModal" tabindex="-1" role="dialog" aria-labelledby="paymentHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Payment History</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="phList">
          <!-- payment history will be loaded here -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <style>
    /* Slightly darken backdrop and ensure stacking works smoothly */
    .modal-backdrop.modal-stack {
      opacity: 0.4;
    }

    @media (min-width: 992px) {
      #viewFundsModal .modal-dialog {
        max-width: 900px;
      }
    }
  </style>

  <script>
    $(document).on("click", ".view-invoice", function(e) {
      e.preventDefault();
      const paymentId = $(this).data("payment-id");

      $.ajax({
        url: "<?php echo base_url('common/generate_pdf'); ?>",
        type: "POST",
        data: {
          id: paymentId,
          for: 6,
        },
        xhrFields: {
          responseType: 'blob'
        },
        success: function(response) {
          var blob = new Blob([response], {
            type: "application/pdf"
          });
          var url = window.URL.createObjectURL(blob);
          window.open(url, "_blank");
        },
        error: function() {
          alert("Failed to generate invoice PDF");
        }
      });
    });
  </script>