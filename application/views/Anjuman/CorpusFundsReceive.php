<div class="container-fluid margintopcontainer pt-5">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <a href="<?= base_url('anjuman'); ?>" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="m-0 flex-grow-1 text-center mb-3">Corpus Funds - Receive Payments</h4>
  <?php if (!empty($message)): ?>
    <div class="alert alert-success py-2 px-3"><?= htmlspecialchars($message); ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 px-3"><?= htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <div class="card mb-3">
    <div class="card-body p-2">
      <small class="text-muted">List of all corpus fund assignments with paid and due amounts.</small>
    </div>
  </div>

  <?php
  // Build unique lists for filters
  $fundList = [];
  $sectorList = [];
  $subSectorList = [];
  // Local INR formatter without decimals using Indian grouping
  if (!function_exists('inr_no_decimals')) {
    function inr_no_decimals($num) {
      if ($num === null || $num === '' || !is_numeric($num)) return '0';
      $neg = $num < 0; $num = abs((int)round($num));
      $str = (string)$num; $len = strlen($str);
      if ($len <= 3) return ($neg ? '-' : '') . $str;
      $last3 = substr($str, -3);
      $rest = substr($str, 0, $len - 3);
      $rest_rev = strrev($rest);
      $rest_groups = str_split($rest_rev, 2);
      $rest_formatted = strrev(implode(',', $rest_groups));
      return ($neg ? '-' : '') . $rest_formatted . ',' . $last3;
    }
  }
  if (!empty($assignments)) {
    foreach ($assignments as $r) {
      $fid = (int)($r['fund_id'] ?? 0);
      $ftitle = isset($r['title']) ? $r['title'] : '';
      if ($fid > 0 && !isset($fundList[$fid])) {
        $fundList[$fid] = $ftitle;
      }

      $sec = isset($r['sector']) ? trim($r['sector']) : '';
      if ($sec !== '' && !isset($sectorList[$sec])) {
        $sectorList[$sec] = $sec;
      }

      $ssec = isset($r['sub_sector']) ? trim($r['sub_sector']) : '';
      if ($ssec !== '' && !isset($subSectorList[$ssec])) {
        $subSectorList[$ssec] = $ssec;
      }
    }
    asort($fundList, SORT_NATURAL | SORT_FLAG_CASE);
    asort($sectorList, SORT_NATURAL | SORT_FLAG_CASE);
    asort($subSectorList, SORT_NATURAL | SORT_FLAG_CASE);
  }
  ?>
  <div class="card mb-2">
    <div class="card-body py-2">
      <div class="row g-2 align-items-center">
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
        <div class="col-md-2 mb-2 mb-md-0">
          <label class="small mb-1">Filter by Fund</label>
          <select class="form-control form-control-sm" id="filterFund">
            <option value="">All funds</option>
            <?php foreach ($fundList as $fid => $ftitle): ?>
              <option value="<?= (int)$fid; ?>" <?= isset($filter_fund) && (int)$filter_fund === (int)$fid ? 'selected' : ''; ?>><?= htmlspecialchars($ftitle); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-1 mb-2 mb-md-0">
          <label class="d-none d-md-block mb-1">&nbsp;</label>
          <button type="button" id="applyFiltersBtn" class="btn btn-primary btn-sm w-100 mb-1">Apply</button>
          <button type="button" id="clearFilters" class="btn btn-light btn-sm w-100">Clear</button>
        </div>
      </div>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-sm table-striped align-middle" id="hofTable">
      <thead class="table-light">
        <tr>
          <th>Sr. No.</th>
          <th class="th-sort" data-type="number">ITS ID</th>
          <th class="th-sort" data-type="string">Name</th>
          <th class="th-sort" data-type="string">Sector</th>
          <th class="th-sort" data-type="string">Sub-Sector</th>
          <th class="text-end th-sort" data-type="number">Assigned</th>
          <th class="text-end th-sort" data-type="number">Paid</th>
          <th class="text-end th-sort" data-type="number">Due</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Group assignments by HOF for unique member view
        $hofMap = [];
        if (!empty($assignments)) {
            foreach ($assignments as $row) {
            $hid = (int)($row['hof_id'] ?? 0);
            if ($hid <= 0) continue;
            if (!isset($hofMap[$hid])) {
              $hofMap[$hid] = [
                'hof_id' => $hid,
                'its_id' => isset($row['its_id']) ? $row['its_id'] : null,
                'hof_name' => $row['hof_name'] ?? '',
                'sector' => $row['sector'] ?? '',
                'sub_sector' => $row['sub_sector'] ?? '',
                'assigned_total' => 0.0,
                'paid_total' => 0.0,
                'due_total' => 0.0,
                'fund_ids' => [],
              ];
            }
            $ass = (float)($row['amount_assigned'] ?? 0);
            $paid = (float)($row['amount_paid'] ?? 0);
            $due = (float)($row['amount_due'] ?? max(0, $ass - $paid));
            $hofMap[$hid]['assigned_total'] += $ass;
            $hofMap[$hid]['paid_total'] += $paid;
            $hofMap[$hid]['due_total'] += $due;
            $fid = (int)($row['fund_id'] ?? 0);
            if ($fid) {
              $hofMap[$hid]['fund_ids'][$fid] = true;
            }
          }
        }
        if (!empty($hofMap)):
          $i = 1;
          foreach ($hofMap as $hid => $h):
            $assignedT = (int)round($h['assigned_total']);
            $paidT = (int)round($h['paid_total']);
            $dueT = (int)round($h['due_total']);
            $fundIdsCsv = implode(',', array_keys($h['fund_ids']));
        ?>
            <?php $rowIts = isset($h['its_id']) && $h['its_id'] !== null ? $h['its_id'] : ''; ?>
            <tr data-hof-id="<?= (int)$h['hof_id']; ?>" data-fund-ids="<?= htmlspecialchars($fundIdsCsv); ?>" data-its="<?= htmlspecialchars($rowIts); ?>">
              <td class="td-idx"><?= $i++; ?></td>
              <td class="td-its" data-val="<?= htmlspecialchars($rowIts !== '' ? $rowIts : (int)$h['hof_id']); ?>"><?= $rowIts !== '' ? htmlspecialchars($rowIts) : (int)$h['hof_id']; ?></td>
              <td class="td-hofname"><?= htmlspecialchars($h['hof_name']); ?></td>
              <td class="td-sector"><?= htmlspecialchars($h['sector'] ?? ''); ?></td>
              <td class="td-subsector"><?= htmlspecialchars($h['sub_sector'] ?? ''); ?></td>
              <td class="text-end td-assigned" data-val="<?= (int)$assignedT; ?>">₹<?= inr_no_decimals($assignedT); ?></td>
              <td class="text-end text-success td-paid" data-val="<?= (int)$paidT; ?>">₹<?= inr_no_decimals($paidT); ?></td>
              <td class="text-end td-due <?= $dueT > 0 ? 'text-danger' : 'text-muted'; ?>" data-val="<?= (int)$dueT; ?>">₹<?= inr_no_decimals($dueT); ?></td>
              <td>
                <button type="button" class="btn btn-outline-secondary btn-sm js-view-funds"
                  data-hof-id="<?= (int)$h['hof_id']; ?>"
                  data-hof-name="<?= htmlspecialchars($h['hof_name']); ?>">View Funds</button>
              </td>
            </tr>
          <?php endforeach;
        else: ?>
          <tr>
            <td colspan="9" class="text-center text-muted">No assignments found.</td>
          </tr>
        <?php endif; ?>
        <tr id="noFilterResults" style="display:none;">
          <td colspan="9" class="text-center text-muted">No matching results.</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- View Funds Modal -->
<div class="modal fade" id="viewFundsModal" tabindex="-1" role="dialog" aria-labelledby="viewFundsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title" id="viewFundsLabel">Assigned Funds</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="mb-2"><strong id="vfHofTitle">-</strong></div>
        <div id="vfFundsList">
          <div class="text-muted">Loading...</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Receive Payment Modal -->
<div class="modal fade" id="receivePaymentModal" tabindex="-1" role="dialog" aria-labelledby="receivePaymentLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title" id="receivePaymentLabel">Receive Payment</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="<?= base_url('anjuman/corpusfunds_receive_payment'); ?>">
        <div class="modal-body">
          <input type="hidden" name="fund_id" id="rpFundId" />
          <input type="hidden" name="hof_id" id="rpHofId" />
          <div class="mb-1"><strong id="rpFundTitle">-</strong></div>
          <!-- <div class="mb-1" id="rpHofName" style="font-size:1rem; font-weight:600;">-</div> -->
          <div class="form-group mt-2 mb-1">
            <label class="small">Amount</label>
            <input type="number" step="0.01" min="0" class="form-control form-control-sm" name="amount" id="rpAmount" required />
            <small class="form-text text-muted">Due: <span id="rpDue">0</span></small>
          </div>
          <div class="form-row">
            <div class="form-group col-6 mb-1">
              <label class="small">Payment Date</label>
              <input type="date" class="form-control form-control-sm" name="payment_date" id="rpPaymentDate" />
            </div>
            <div class="form-group col-6 mb-1">
              <label class="small">Payment Method</label>
              <select class="form-control form-control-sm" name="payment_method" id="rpPaymentMethod">
                <option value="">Select</option>
                <option value="Cash">Cash</option>
                <option value="Cheque">Cheque</option>
              </select>
            </div>
          </div>
          <div class="form-group mb-0">
            <label class="small">Notes</label>
            <input type="text" class="form-control form-control-sm" name="notes" id="rpNotes" placeholder="Optional" />
          </div>
        </div>
        <div class="modal-footer py-2">
          <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm" id="rpSubmit">Receive</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Payment History Modal -->
<div class="modal fade" id="paymentHistoryModal" tabindex="-1" role="dialog" aria-labelledby="paymentHistoryLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title" id="paymentHistoryLabel">Payment History</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="phList" class="small">Loading...</div>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    // Handle multiple modals overlay stacking (Bootstrap 4)
    // Ensures newer modals appear above older ones and backdrops stack correctly.
    $(document).on('show.bs.modal', '.modal', function() {
      var $modal = $(this);
      var zIndex = 1040 + (10 * $('.modal:visible').length);
      $modal.css('z-index', zIndex);
      setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
      }, 0);
    });
    // When closing, restore body scroll state correctly
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
    // Client-side sorting for table headers with .th-sort
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
        var A = $(a).children().eq(colIndex);
        var B = $(b).children().eq(colIndex);
        var aVal, bVal;
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
      // reset others
      $th.closest('tr').find('.th-sort').not($th).removeData('asc');
      $th.data('asc', asc);
      sortTableBy(idx, type, asc);
    });

    function applyFilters() {
      var nameQ = ($('#filterName').val() || '').trim().toLowerCase();
      var itsQ = ($('#filterITS').val() || '').trim().toLowerCase();
      var fundId = ($('#filterFund').val() || '').trim();
      var sectorQ = ($('#filterSector').val() || '').trim().toLowerCase();
      var subSectorQ = ($('#filterSubSector').val() || '').trim().toLowerCase();
      var anyVisible = false;
      $('#hofTable tbody tr').each(function() {
        var $tr = $(this);
        if ($tr.attr('id') === 'noFilterResults') return; // skip message row
        var textName = ($tr.find('.td-hofname').text() || '').trim().toLowerCase();
        var textSector = ($tr.find('.td-sector').text() || '').trim().toLowerCase();
        var textSubSector = ($tr.find('.td-subsector').text() || '').trim().toLowerCase();
        var rowFundIds = ($tr.data('fund-ids') || '').toString();
        var rowIts = (($tr.data('its') || '') + '').toString().trim().toLowerCase();
        var matchName = !nameQ || textName.indexOf(nameQ) !== -1;
        var matchIts = !itsQ || rowIts.indexOf(itsQ) !== -1 || (String($tr.find('.td-its').text() || '').toLowerCase().indexOf(itsQ) !== -1);
        var matchFund = !fundId || (',' + rowFundIds + ',').indexOf(',' + fundId + ',') !== -1;
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
    $('#filterFund').on('change', applyFilters);
    $('#filterSector').on('change', applyFilters);
    $('#filterSubSector').on('change', applyFilters);

    // Apply: if only name is set, use client-side filter for snappy UX; otherwise redirect with GET params
    $('#applyFiltersBtn').on('click', function() {
      var nameQ = ($('#filterName').val() || '').trim();
      var itsQ = ($('#filterITS').val() || '').trim();
      var fundId = ($('#filterFund').val() || '').trim();
      var sectorQ = ($('#filterSector').val() || '').trim();
      var subSectorQ = ($('#filterSubSector').val() || '').trim();
      var onlyName = nameQ && !itsQ && !fundId && !sectorQ && !subSectorQ;
      if (onlyName) { applyFilters(); return; }
      var url = '<?= base_url('anjuman/corpusfunds_receive'); ?>';
      var params = [];
      if (itsQ) params.push('its_id=' + encodeURIComponent(itsQ));
      if (fundId) params.push('fund_id=' + encodeURIComponent(fundId));
      if (sectorQ) params.push('sector=' + encodeURIComponent(sectorQ));
      if (subSectorQ) params.push('sub_sector=' + encodeURIComponent(subSectorQ));
      if (params.length) url += '?' + params.join('&');
      window.location = url;
    });

    $('#clearFilters').on('click', function() {
      $('#filterName').val('');
      $('#filterITS').val('');
      $('#filterFund').val('');
      $('#filterSector').val('');
      $('#filterSubSector').val('');
      applyFilters();
      refreshPage();
    });

    function refreshPage() {
      window.location = '<?= base_url('anjuman/corpusfunds_receive'); ?>';
    }

    // View funds for a HOF: fetch list via AJAX and render
    $(document).on('click', '.js-view-funds', function() {
      var b = $(this);
      var hofId = b.data('hof-id');
      var hofName = b.data('hof-name') || '';
      $('#vfHofTitle').text("Name: " + hofName);
      $('#vfFundsList').html('<div class="text-muted">Loading...</div>');
      $('#viewFundsModal').modal('show');
      $.ajax({
        url: '<?= base_url('anjuman/corpusfunds_hof_funds'); ?>',
        method: 'GET',
        data: {
          hof_id: hofId
        },
        success: function(resp) {
          try {
            if (resp && resp.success && Array.isArray(resp.funds)) {
              if (resp.funds.length === 0) {
                $('#vfFundsList').html('<div class="text-muted">No funds assigned.</div>');
              } else {
                var html = '<div class="table-responsive"><table class="table table-sm mb-0">' +
                  '<thead><tr><th>Fund</th><th class="text-right">Assigned</th><th class="text-right">Paid</th><th class="text-right">Due</th><th></th></tr></thead><tbody>';
                resp.funds.forEach(function(f) {
                  var title = f.title || '-';
                  var ass = fmtINR(f.amount_assigned || 0);
                  var paid = fmtINR(f.amount_paid || 0);
                  var due = fmtINR(f.amount_due || 0);
                  html += '<tr>' +
                    '<td>' + $('<div/>').text(title).html() + '</td>' +
                    '<td class="text-right">₹' + ass + '</td>' +
                    '<td class="text-right text-success">₹' + paid + '</td>' +
                    '<td class="text-right ' + (parseFloat(f.amount_due || 0) > 0 ? 'text-danger' : 'text-muted') + '">₹' + due + '</td>' +
                    '<td class="text-right">' +
                    '<div>' +
                    '<button type="button" class="mr-2 btn btn-sm btn-primary js-receive-payment"' +
                    ' data-hof-id="' + hofId + '"' +
                    ' data-hof-name="' + $('<div/>').text(hofName).html() + '"' +
                    ' data-fund-id="' + f.fund_id + '"' +
                    ' data-fund-title="' + $('<div/>').text(title).html() + '"' +
                    ' data-due-raw="' + (Math.round(parseFloat(f.amount_due || 0))) + '">Receive Payment</button>' +
                    '<button type="button" class="btn btn-sm btn-info text-white js-payment-history"' +
                    ' data-hof-id="' + hofId + '" data-fund-id="' + f.fund_id + '">Payment History</button>' +
                    '</div>' +
                    '</td>' +
                    '</tr>';
                });
                html += '</tbody></table></div>';
                $('#vfFundsList').html(html);
              }
            } else {
              $('#vfFundsList').html('<div class="text-danger">Failed to load funds.</div>');
            }
          } catch (e) {
            $('#vfFundsList').html('<div class="text-danger">Invalid response.</div>');
          }
        },
        error: function() {
          $('#vfFundsList').html('<div class="text-danger">Error fetching funds.</div>');
        }
      });
    });

    // Receive payment
    $(document).on('click', '.js-receive-payment', function() {
      var b = $(this);
      $('#rpFundId').val(b.data('fund-id'));
      $('#rpHofId').val(b.data('hof-id'));
      $('#rpFundTitle').text("Fund: " + b.data('fund-title'));
      $('#rpDue').text(b.data('due-raw'));
      $('#rpAmount').val('');
      $('#rpNotes').val('');
      // Defaults for date & method
      var today = new Date();
      var yyyy = today.getFullYear();
      var mm = String(today.getMonth() + 1).padStart(2, '0');
      var dd = String(today.getDate()).padStart(2, '0');
      $('#rpPaymentDate').val(yyyy + '-' + mm + '-' + dd);
      $('#rpPaymentMethod').val('Cash');
      $('#receivePaymentModal').modal('show');
    });

    // Client-side validation: amount should not exceed due
    $(document).on('click', '#rpSubmit', function(e) {
      var dueStr = $('#rpDue').text() || '0';
      var due = parseFloat(String(dueStr).replace(/[,₹\s]/g, '')) || 0;
      var amt = parseFloat($('#rpAmount').val()) || 0;
      if (amt > due + 0.0001) {
        e.preventDefault();
        alert('Amount exceeds due. Due: ₹' + fmtINR(due));
        return false;
      }
    });

    // Payment history
    $(document).on('click', '.js-payment-history', function() {
      var b = $(this);
      var fundId = b.data('fund-id');
      var hofId = b.data('hof-id');
      $('#phList').html('Loading...');
      $('#paymentHistoryModal').modal('show');
      $.ajax({
        url: '<?= base_url('anjuman/corpusfunds_payment_history'); ?>',
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
                var html = '<div class="table-responsive">' +
                  '<table class="table table-sm mb-0">' +
                  '<thead><tr>' +
                  '<th>Date</th><th>Method</th><th class="text-right">Amount</th><th>Notes</th><th class="text-right">Actions</th>' +
                  '</tr></thead><tbody>';

                function fmtDateDMY(d) {
                  if (!d) return '';
                  var s = String(d).trim();
                  // Prefer robust Date parsing
                  var dtObj = new Date(s);
                  if (!isNaN(dtObj.getTime())) {
                    var dd = ('0' + dtObj.getDate()).slice(-2);
                    var mm = ('0' + (dtObj.getMonth() + 1)).slice(-2);
                    var yyyy = dtObj.getFullYear();
                    return dd + '-' + mm + '-' + yyyy;
                  }
                  // Fallback: regex extract YYYY-MM-DD
                  var m = s.match(/(\d{4})-(\d{2})-(\d{2})/);
                  if (m) {
                    var yyyy = m[1],
                      mm = m[2],
                      dd = m[3];
                    return dd + '-' + mm + '-' + yyyy;
                  }
                  return '';
                }
                resp.payments.forEach(function(p) {
                  var amt = Math.round(parseFloat(p.amount_paid || 0));
                  var dt = fmtDateDMY(p.paid_at || '');
                  var method = p.payment_method ? $('<div/>').text(p.payment_method).html() : '';
                  var notes = p.notes ? $('<div/>').text(p.notes).html() : '';
                  html += '<tr>' +
                    '<td>' + dt + '</td>' +
                    '<td>' + method + '</td>' +
                    '<td class="text-right">₹' + fmtINR(amt) + '</td>' +
                    '<td>' + notes + '</td>' +
                    '<td class="text-right">' +
                    '<button type="button" class="btn btn-outline-secondary btn-sm mr-2 js-view-receipt" data-payment-id="' + p.id + '">View</button>' +
                    '<button type="button" class="btn btn-outline-danger btn-sm js-delete-payment" data-payment-id="' + p.id + '">Delete</button>' +
                    '</td>' +
                    '</tr>';
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
    // Delete payment
    $(document).on('click', '.js-delete-payment', function() {
      var pid = $(this).data('payment-id');
      if (!pid) return;
      if (!confirm('Delete this payment?')) return;
      $.ajax({
        url: '<?= base_url('anjuman/corpusfunds_delete_payment'); ?>',
        method: 'POST',
        data: {
          id: pid
        },
        success: function(resp) {
          try {
            if (resp && resp.success) {
              // Refresh the whole page to update aggregates and history
              window.location.reload();
            } else {
              alert('Failed to delete payment');
            }
          } catch (e) {
            alert('Invalid response');
          }
        },
        error: function() {
          alert('Error deleting payment');
        }
      });
    });
    // View receipt: submit POST to PDF generator in a new tab
    $(document).on('click', '.js-view-receipt', function(){
      var pid = $(this).data('payment-id');
      if(!pid) return;
      var form = document.createElement('form');
      form.method = 'POST';
      form.action = '<?= base_url('common/generate_pdf'); ?>';
      form.target = '_blank';
      var inpId = document.createElement('input'); inpId.type='hidden'; inpId.name='id'; inpId.value=pid;
      var inpFor = document.createElement('input'); inpFor.type='hidden'; inpFor.name='for'; inpFor.value='5';
      form.appendChild(inpId); form.appendChild(inpFor);
      document.body.appendChild(form);
      form.submit();
      setTimeout(function(){ document.body.removeChild(form); }, 1000);
    });
  })();
</script>

<style>
  /* Slightly darken backdrop and ensure stacking works smoothly */
  .modal-backdrop.modal-stack {
    opacity: 0.4;
  }

  /* Keep the assigned funds modal wider on larger screens */
  @media (min-width: 992px) {
    #viewFundsModal .modal-dialog {
      max-width: 900px;
    }
  }
</style>