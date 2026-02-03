<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('format_inr')) {
  function format_inr($num)
  {
    $num = (int)round((float)$num);
    $n = strval($num);
    $len = strlen($n);
    if ($len <= 3) return $n;
    $last3 = substr($n, -3);
    $rest = substr($n, 0, $len - 3);
    $parts = [];
    while (strlen($rest) > 2) {
      $parts[] = substr($rest, -2);
      $rest = substr($rest, 0, strlen($rest) - 2);
    }
    if ($rest !== '') $parts[] = $rest;
    $parts = array_reverse($parts);
    return implode(',', $parts) . ',' . $last3;
  }
}
// Build sector/sub-sector lists from assignments (similar to Anjuman)
$sectorList = [];
$subSectorList = [];
if (!empty($corpus_details)) {
  foreach ($corpus_details as $group) {
    foreach (($group['rows'] ?? []) as $r) {
      $s = trim($r['sector'] ?? '');
      if ($s === '') $s = 'Unassigned';
      $ss = trim($r['subsector'] ?? '');
      if ($ss === '') $ss = 'Unassigned';
      $sectorList[$s] = true;
      $subSectorList[$ss] = true;
    }
  }
}
?>
<div class="container-fluid margintopcontainer pt-5">
  <div class="d-flex align-items-center">
    <a href="<?= base_url('amilsaheb'); ?>" class="btn btn-sm btn-outline-secondary mr-2"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="text-center mb-3">Corpus Funds Details</h4>

  <?php
    // Compute overview totals across all funds
    $sumAssigned = 0; $sumPaid = 0; $sumOutstanding = 0;
    if (!empty($corpus_details)) {
      foreach ($corpus_details as $group) {
        $sumAssigned += (float)($group['assigned_total'] ?? 0);
        $sumPaid += (float)($group['paid_total'] ?? 0);
        $sumOutstanding += (float)($group['outstanding_total'] ?? 0);
      }
    }
  ?>
  <div class="card mb-3">
    <div class="card-body py-2">
      <div class="row text-center">
        <div class="col-6 col-md-4 mb-2">
          <div class="mini-card" style="margin-bottom:4px;">
            <div class="stats-value">₹<?= format_inr($sumAssigned); ?></div>
            <div class="stats-label">Total Amount</div>
          </div>
        </div>
        <div class="col-6 col-md-4 mb-2">
          <div class="mini-card" style="margin-bottom:4px;">
            <div class="stats-value text-success">₹<?= format_inr($sumPaid); ?></div>
            <div class="stats-label">Total Paid</div>
          </div>
        </div>
        <div class="col-6 col-md-4 mb-2">
          <div class="mini-card" style="margin-bottom:4px;">
            <div class="stats-value text-danger">₹<?= format_inr($sumOutstanding); ?></div>
            <div class="stats-label">Outstanding</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filters: Name, Fund, Sector, Sub-Sector -->
  <div class="card mb-3">
    <div class="card-body py-2">
      <div class="form-row align-items-end">
        <div class="col-md-3">
          <label class="small mb-1">Name</label>
          <input type="text" id="filterName" class="form-control form-control-sm" placeholder="Search name or ITS">
        </div>
        <div class="col-md-3">
          <label class="small mb-1">Fund</label>
          <select id="filterFund" class="form-control form-control-sm">
            <option value="">All Funds</option>
            <?php if (!empty($corpus_details)) {
              foreach ($corpus_details as $group) {
                $fund = $group['fund']; ?>
                <option value="<?= (int)($fund['id'] ?? 0); ?>"><?= htmlspecialchars($fund['name'] ?? 'Fund'); ?></option>
            <?php }
            } ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="small mb-1">Sector</label>
          <select id="filterSector" class="form-control form-control-sm">
            <option value="">All Sectors</option>
            <?php foreach (array_keys($sectorList) as $sec) { ?>
              <option value="<?= htmlspecialchars($sec); ?>"><?= htmlspecialchars($sec); ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="small mb-1">Sub-Sector</label>
          <select id="filterSubSector" class="form-control form-control-sm">
            <option value="">All Sub-Sectors</option>
            <?php foreach (array_keys($subSectorList) as $ssec) { ?>
              <option value="<?= htmlspecialchars($ssec); ?>"><?= htmlspecialchars($ssec); ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="mt-2 d-flex">
        <button id="btnClearFilters" class="btn btn-sm btn-outline-secondary">Clear</button>
      </div>
    </div>
  </div>

  <!-- HOF grouped table (unique HOF rows) -->
  <div class="card">
    <div class="table-responsive">
      <table class="table table-sm mb-0" id="hofTable">
        <thead class="thead-light">
          <tr>
            <th style="width:60px;">Sr. No</th>
            <th class="sortable" data-key="name">Member Name</th>
            <th class="sortable" data-key="sector">Sector</th>
            <th class="sortable" data-key="subsector">Sub-Sector</th>
            <th class="text-right sortable" data-key="assigned">Assigned</th>
            <th class="text-right sortable" data-key="paid">Paid</th>
            <th class="text-right sortable" data-key="due">Due</th>
            
          </tr>
        </thead>
        <tbody id="hofTableBody">
          <!-- populated by JS from PHP data -->
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modals: Assigned Funds, Payment History (view-only) -->
  <div class="modal fade" id="assignedFundsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Assigned Funds - <span id="afMemberName"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Fund</th>
                  <th class="text-right">Assigned</th>
                  <th class="text-right">Paid</th>
                  <th class="text-right">Due</th>
                  <th class="text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="assignedFundsBody"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="paymentHistoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Payment History - <span id="phMemberName"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Method</th>
                  <th class="text-right">Amount</th>
                  <th>Notes</th>
                  <th class="text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="paymentHistoryBody"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
  (function() {
    // Build in-memory unique HOF rows from PHP data
    var allRows = [];
    <?php if (!empty($corpus_details)) {
      foreach ($corpus_details as $group) {
        $fund = $group['fund'];
        $fid = (int)($fund['id'] ?? 0); ?>
        <?php foreach (($group['rows'] ?? []) as $r) { ?>
          allRows.push({
            hof_id: <?= json_encode((int)($r['hof_id'] ?? 0)); ?>,
            name: <?= json_encode($r['name'] ?? ''); ?>,
            sector: <?= json_encode($r['sector'] ?? ''); ?> || 'Unassigned',
            subsector: <?= json_encode($r['subsector'] ?? ''); ?> || 'Unassigned',
            fund_id: <?= json_encode($fid); ?>,
            fund_name: <?= json_encode($fund['name'] ?? ''); ?>,
            per_family: <?= json_encode((float)($fund['amount'] ?? 0)); ?>,
            assigned: <?= json_encode((float)($r['assigned'] ?? 0)); ?>,
            paid: <?= json_encode((float)($r['paid'] ?? 0)); ?>,
            due: <?= json_encode((float)($r['due'] ?? 0)); ?>
          });
        <?php } ?>
    <?php }
    } ?>

    // Group by HOF for unique rows with totals across funds
    function aggregateByHof(rows) {
      var map = {};
      rows.forEach(function(r) {
        var k = r.hof_id + '|' + (r.name || '');
        if (!map[k]) {
          map[k] = {
            hof_id: r.hof_id,
            name: r.name,
            sector: r.sector,
            subsector: r.subsector,
            assigned: 0,
            paid: 0,
            due: 0,
            funds: []
          };
        }
        map[k].assigned += (r.assigned || 0);
        map[k].paid += (r.paid || 0);
        map[k].due += (r.due || 0);
        map[k].funds.push(r);
      });
      return Object.values(map);
    }

    // Rendering
    var $tbody = $('#hofTableBody');

    function renderTable(rows) {
      $tbody.empty();
      rows.forEach(function(r, idx) {
        var tr = $('<tr></tr>');
        tr.append('<td>'+ (idx + 1) +'</td>');
        var nameCell = $('<td></td>').text(r.name || '');
        var sectorCell = $('<td></td>').text(r.sector || '');
        var subSectorCell = $('<td></td>').text(r.subsector || '');
        tr.append(nameCell);
        tr.append(sectorCell);
        tr.append(subSectorCell);
        tr.append('<td class="text-right" data-val="' + r.assigned + '">₹' + formatINR(r.assigned) + '</td>');
        tr.append('<td class="text-right text-success" data-val="' + r.paid + '">₹' + formatINR(r.paid) + '</td>');
        tr.append('<td class="text-right text-danger" data-val="' + r.due + '">₹' + formatINR(r.due) + '</td>');
        
        $tbody.append(tr);

        var needsFetch = (!r.name) || (!r.sector) || (!r.subsector) || r.sector === 'Unassigned' || r.subsector === 'Unassigned';
        if (needsFetch) {
          $.get(<?= json_encode(base_url('anjuman/corpusfunds_hof_funds')); ?>, { hof_id: r.hof_id }).done(function(resp){
            var funds = resp && resp.funds ? resp.funds : [];
            if (funds.length > 0) {
              var f0 = funds[0];
              if (!r.name && f0.member_name) { r.name = f0.member_name; nameCell.text(r.name); }
              var sec = f0.sector || f0.Sector || '';
              var ssec = f0.sub_sector || f0.Sub_Sector || '';
              if ((!r.sector || r.sector === 'Unassigned') && sec) { r.sector = sec; sectorCell.text(r.sector); }
              if ((!r.subsector || r.subsector === 'Unassigned') && ssec) { r.subsector = ssec; subSectorCell.text(r.subsector); }
            }
          });
        }
      });
    }

    // Helpers
    function formatINR(x) {
      try {
        var n = Math.round(Number(x || 0));
        return Number(n).toLocaleString('en-IN');
      } catch (e) {
        return x;
      }
    }

    function escapeHtml(s) {
      return (s || '').replace(/[&<>"']/g, function(c) {
        return {
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          '\'': '&#39;'
        } [c];
      });
    }

    function escapeAttr(s) {
      return (s || '').replace(/"/g, '&quot;');
    }

    // Filters
    var nameInput = $('#filterName');
    var fundSelect = $('#filterFund');
    var sectorSelect = $('#filterSector');
    var subSectorSelect = $('#filterSubSector');

    function applyFilters() {
      var name = (nameInput.val() || '').toLowerCase();
      var fundId = fundSelect.val();
      var sector = sectorSelect.val();
      var subsector = subSectorSelect.val();
      var filtered = allRows.filter(function(r) {
        var ok = true;
        if (name) ok = ok && (((r.name || '').toLowerCase().indexOf(name) >= 0) || (String(r.hof_id || '').toLowerCase().indexOf(name) >= 0));
        if (fundId) ok = ok && String(r.fund_id) === String(fundId);
        if (sector) ok = ok && String(r.sector) === String(sector);
        if (subsector) ok = ok && String(r.subsector) === String(subsector);
        return ok;
      });
      var agg = aggregateByHof(filtered);
      // Default sort: Sector, Sub-Sector, Name (ascending)
      agg.sort(function(a,b){
        var as = (a.sector||'').toLowerCase();
        var bs = (b.sector||'').toLowerCase();
        if (as < bs) return -1; if (as > bs) return 1;
        var ass = (a.subsector||'').toLowerCase();
        var bss = (b.subsector||'').toLowerCase();
        if (ass < bss) return -1; if (ass > bss) return 1;
        var an = (a.name||'').toLowerCase();
        var bn = (b.name||'').toLowerCase();
        if (an < bn) return -1; if (an > bn) return 1;
        return 0;
      });
      renderTable(agg);
    }
    nameInput.on('input', applyFilters);
    fundSelect.on('change', applyFilters);
    sectorSelect.on('change', applyFilters);
    subSectorSelect.on('change', applyFilters);
    $('#btnClearFilters').on('click', function() {
      nameInput.val('');
      fundSelect.val('');
      sectorSelect.val('');
      subSectorSelect.val('');
      applyFilters();
    });

    // Sorting
    var sortDir = {};
    $('#hofTable thead th.sortable').on('click', function(){
      var key = $(this).data('key');
      sortDir[key] = sortDir[key] === 'asc' ? 'desc' : 'asc';
      var rows = [];
      $('#hofTableBody tr').each(function() {
        var $tr = $(this);
        rows.push({
          el: $tr,
          // Adjust indices due to Sr. No column at index 0
          name: $tr.children().eq(1).text(),
          sector: $tr.children().eq(2).text(),
          subsector: $tr.children().eq(3).text(),
          assigned: parseFloat($tr.children().eq(4).data('val')) || 0,
          paid: parseFloat($tr.children().eq(5).data('val')) || 0,
          due: parseFloat($tr.children().eq(6).data('val')) || 0
        });
      });
      rows.sort(function(a, b) {
        var av = a[key],
          bv = b[key];
        if (typeof av === 'string') {
          av = av.toLowerCase();
          bv = bv.toLowerCase();
        }
        if (av < bv) return sortDir[key] === 'asc' ? -1 : 1;
        if (av > bv) return sortDir[key] === 'asc' ? 1 : -1;
        return 0;
      });
      $('#hofTableBody').empty();
      rows.forEach(function(r) {
        $('#hofTableBody').append(r.el);
      });
    });

    // View Funds modal (per HOF)
    $(document).on('click', '.btn-view-funds', function() {
      var hofId = $(this).data('hof');
      var name = $(this).data('name');
      $('#afMemberName').text(name);
      $('#assignedFundsBody').empty();
      // Fetch per-HOF funds from Anjuman endpoint
      $.get(<?= json_encode(base_url('anjuman/corpusfunds_hof_funds')); ?>, {
        hof_id: hofId
      }).done(function(resp) {
        var rows = resp && resp.funds ? resp.funds : [];
        rows.forEach(function(x) {
          var tr = $('<tr></tr>');
          tr.append('<td>' + escapeHtml(x.fund_name || '') + '</td>');
          tr.append('<td class="text-right">₹' + formatINR(x.assigned || 0) + '</td>');
          tr.append('<td class="text-right text-success">₹' + formatINR(x.paid || 0) + '</td>');
          tr.append('<td class="text-right text-danger">₹' + formatINR(Math.max(0, (x.assigned || 0) - (x.paid || 0))) + '</td>');
          var actions = $('<td class="text-right"></td>');
          actions.append('<button class="btn btn-xs btn-outline-secondary btn-history" data-fund="' + (x.fund_id || 0) + '" data-hof="' + hofId + '" data-name="' + escapeAttr(name) + '">History</button>');
          tr.append(actions);
          $('#assignedFundsBody').append(tr);
        });
        $('#assignedFundsModal').modal('show');
      });
    });

    // Receive payments disabled on this view-only page

    // Payment History modal
    $(document).on('click', '.btn-history', function() {
      var fundId = $(this).data('fund');
      var hofId = $(this).data('hof');
      var name = $(this).data('name');
      $('#phMemberName').text(name);
      $('#paymentHistoryBody').empty();
      $.get(<?= json_encode(base_url('anjuman/corpusfunds_payment_history')); ?>, {
        fund_id: fundId,
        hof_id: hofId
      }).done(function(resp) {
        var items = resp && resp.payments ? resp.payments : [];
        items.forEach(function(p) {
          var tr = $('<tr></tr>');
          tr.append('<td>' + escapeHtml(p.paid_at || '') + '</td>');
          tr.append('<td>' + escapeHtml(p.payment_method || '') + '</td>');
          tr.append('<td class="text-right">₹' + formatINR(p.amount_paid || 0) + '</td>');
          tr.append('<td>' + escapeHtml(p.notes || '') + '</td>');
          var actions = $('<td class="text-right"></td>');
          actions.append('<a class="btn btn-xs btn-outline-secondary" target="_blank" href="' + <?= json_encode(base_url('anjuman/corpusfunds_payment_receipt')); ?> + '?payment_id=' + encodeURIComponent(p.id) + '">View</a>');
          tr.append(actions);
          $('#paymentHistoryBody').append(tr);
        });
        $('#paymentHistoryModal').modal('show');
      });
    });
    // Delete disabled on view-only page

    // Modal overlay stacking (multiple modals)
    $(document).on('show.bs.modal', '.modal', function() {
      var zIndex = 1040 + (10 * $('.modal:visible').length);
      $(this).css('z-index', zIndex);
      setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
      }, 0);
    });
    $(document).on('hidden.bs.modal', '.modal', function() {
      if ($('.modal:visible').length) $('body').addClass('modal-open');
    });

    // Initial render
    applyFilters();
  })();
</script>