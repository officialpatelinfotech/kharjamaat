<div class="container margintopcontainer pt-5">
  <div class="mb-4 p-0">
    <a href="<?php echo base_url("accounts") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="heading text-center">FMB Details</h4>
  <?php // Debug dump (commented out after verification)
  // echo "<pre>"; print_r($fmb_takhmeen_details); echo "</pre>"; 
  ?>
  <div class="row mb-4">
    <div class="container pt-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h6 class="mb-1 text-muted">Total FMB Due</h6>
          <h4 class="text-danger"><?php echo "&#8377;" . number_format($fmb_takhmeen_details['overall']['total_due'], 2); ?></h4>
        </div>
        <div>
          <h6 class="mb-1 text-muted">Total Paid</h6>
          <h4 class="text-success mb-0"><?php echo "&#8377;" . number_format($fmb_takhmeen_details['overall']['total_paid'], 2); ?></h4>
          <?php if(isset($fmb_takhmeen_details['overall']['excess_paid']) && $fmb_takhmeen_details['overall']['excess_paid'] > 0): ?>
            <small class="text-warning d-block">Excess: &#8377;<?php echo number_format($fmb_takhmeen_details['overall']['excess_paid'],2); ?></small>
          <?php endif; ?>
        </div>
        <div>
          <h6 class="mb-1 text-muted">Total Amount</h6>
          <h4 class="text-primary"><?php echo "&#8377;" . number_format($fmb_takhmeen_details['overall']['total_amount'], 2); ?></h4>
        </div>
      </div>

      <div class="alert alert-info shadow-sm">
        <?php
        // Prefer current_year data block provided by controller/model if available
        $currentYearBlock = $fmb_takhmeen_details['current_year'] ?? null;
        // Fallback to latest if current_year not present
        if (!$currentYearBlock && !empty($fmb_takhmeen_details['latest'])) {
          $currentYearBlock = [
            'year' => $fmb_takhmeen_details['latest']['year'] ?? 'N/A',
            'total_amount' => $fmb_takhmeen_details['latest']['total_amount'] ?? 0,
            'total_paid' => 0,
            'total_due' => $fmb_takhmeen_details['latest']['total_amount'] ?? 0
          ];
        }

        // If current_year doesn't already carry total_paid/total_due, derive via allocation
        $allocatedPaidMap = [];
        $highlightId = null;
        if (!empty($fmb_takhmeen_details['all_takhmeen'])) {
          $allYearsAlloc = $fmb_takhmeen_details['all_takhmeen'];
          // Determine oldest-first ordering for allocation (assuming array is newest-first)
          if (count($allYearsAlloc) > 1) {
            // Heuristic: if index 0 equals the latest id (provided), reverse for FIFO oldest-first
            if (isset($fmb_takhmeen_details['latest']['id']) && $allYearsAlloc[0]['id'] == $fmb_takhmeen_details['latest']['id']) {
              $allYearsAlloc = array_reverse($allYearsAlloc);
            }
          }
          $remainingAlloc = isset($fmb_takhmeen_details['overall']['total_paid']) ? (float)$fmb_takhmeen_details['overall']['total_paid'] : 0;
          foreach ($allYearsAlloc as $yr) {
            $cap = (float)$yr['total_amount'];
            $alloc = min($cap, $remainingAlloc);
            $allocatedPaidMap[$yr['id']] = $alloc;
            $remainingAlloc -= $alloc;
            if ($remainingAlloc <= 0) { $remainingAlloc = 0; }
          }
          // Try to find a takhmeen row whose year string starts with current_year['year'] (if numeric segmentation differs)
          if ($currentYearBlock) {
            foreach ($fmb_takhmeen_details['all_takhmeen'] as $rowCY) {
              if (strpos($rowCY['year'], (string)$currentYearBlock['year']) !== false) {
                $highlightId = $rowCY['id'];
                // If current_year block missing paid/due, derive
                if (!isset($currentYearBlock['total_paid'])) {
                  $paidVal = $allocatedPaidMap[$rowCY['id']] ?? 0;
                  $currentYearBlock['total_paid'] = $paidVal;
                  $currentYearBlock['total_due'] = ((float)$rowCY['total_amount']) - $paidVal;
                }
                break;
              }
            }
          }
        }
        ?>
        <h6 class="mb-1">Current Year Takhmeen (<?php echo htmlspecialchars($currentYearBlock['year'] ?? 'Not Found'); ?><?php 
          if(isset($currentYearBlock['derived_hijri_year']) && $currentYearBlock['derived_hijri_year']){
            echo ' / Hijri '.htmlspecialchars($currentYearBlock['derived_hijri_year']);
          } elseif(isset($fmb_takhmeen_details['current_hijri_year']) && $fmb_takhmeen_details['current_hijri_year']) {
            echo ' / Hijri '.htmlspecialchars($fmb_takhmeen_details['current_hijri_year']);
          }
        ?>)</h6>
        <p class="mb-0">
          <b>Total:</b> &#8377;<?php echo number_format($currentYearBlock['total_amount'] ?? 0, 2); ?> |
          <b>Paid<?php echo isset($currentYearBlock['allocated']) ? ' (allocated)' : ''; ?>:</b> &#8377;<?php echo number_format($currentYearBlock['total_paid'] ?? 0, 2); ?> |
          <b>Due:</b> &#8377;<?php echo number_format($currentYearBlock['total_due'] ?? (($currentYearBlock['total_amount'] ?? 0) - ($currentYearBlock['total_paid'] ?? 0)), 2); ?>
          <?php if(isset($currentYearBlock['excess_paid']) && $currentYearBlock['excess_paid'] > 0): ?>
            <br><small class="text-warning">Excess Paid (will carry forward): &#8377;<?php echo number_format($currentYearBlock['excess_paid'],2); ?></small>
          <?php endif; ?>
        </p>
      </div>

      <div class="card shadow-sm rounded-3 mb-4">
        <div class="card-header text-center bg-light">
          <h5 class="mb-0">FMB Takhmeen List</h5>
        </div>
        <div class="card-body p-0">
          <table class="table table-striped table-hover mb-0">
            <thead class="thead-dark">
              <tr>
                <th>Year</th>
                <th>Total Amount</th>
                <th>Paid (Allocated)</th>
                <th>Due</th>
                <!-- <th>Invoice</th> -->
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($fmb_takhmeen_details['all_takhmeen'])): ?>
                <?php
                // Allocate payments FIFO across all years for table rows
                $allYears = $fmb_takhmeen_details['all_takhmeen'];
                $latestId = $fmb_takhmeen_details['latest']['id'] ?? null;
                if (count($allYears) > 1 && $latestId && $allYears[0]['id'] == $latestId) {
                  $allYears = array_reverse($allYears); // oldest first for allocation
                }
                $remainingPaid = isset($fmb_takhmeen_details['overall']['total_paid']) ? (float)$fmb_takhmeen_details['overall']['total_paid'] : 0;
                $allocMap = [];
                foreach ($allYears as $yr) {
                  $cap = (float)$yr['total_amount'];
                  $alloc = min($cap, $remainingPaid);
                  $allocMap[$yr['id']] = $alloc;
                  $remainingPaid -= $alloc;
                  if ($remainingPaid <= 0) { $remainingPaid = 0; }
                }
                // Display in original order (newest first) for UI familiarity
                $currentYearYear = $currentYearBlock['year'] ?? null;
                foreach ($fmb_takhmeen_details['all_takhmeen'] as $row):
                  $paidVal = $allocMap[$row['id']] ?? 0;
                  $dueVal = (float)$row['total_amount'] - $paidVal;
                  $rowHighlight = '';
                  if ($currentYearYear && strpos($row['year'], (string)$currentYearYear) !== false) {
                    $rowHighlight = 'class="table-warning fw-bold"';
                  }
                ?>
                  <tr <?php echo $rowHighlight; ?>>
                    <td><?php echo $row['year']; ?></td>
                    <td><?php echo "&#8377;" . number_format($row['total_amount'], 2); ?></td>
                    <td class="text-success">&#8377;<?php echo number_format($paidVal, 2); ?></td>
                    <td class="text-danger">&#8377;<?php echo number_format($dueVal, 2); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4">Takhmeen Not Found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-header text-center">
          <h5 class="mb-0">General Contributions</h5>
        </div>
        <div class="card-body p-0 table-responsive">
          <table class="table table-striped align-middle mb-0">
            <thead class="thead-dark">
              <tr>
                <th>#</th>
                <th>Date</th>
                <th>Year</th>
                <th>FMB Type</th>
                <th>Contribution Type</th>
                <th class="text-end">Amount (₹)</th>
                <th class="text-end">Paid (₹)</th>
                <th class="text-end">Due (₹)</th>
                <th>Status</th>
                <th>Description</th>
                <th>Payments</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($fmb_takhmeen_details['general_contributions'])): ?>
                <?php foreach ($fmb_takhmeen_details['general_contributions'] as $idx => $gc): ?>
                  <?php
                    $amount      = (float)$gc['amount'];
                    $paid        = isset($gc['amount_paid']) ? (float)$gc['amount_paid'] : 0.0;
                    $due         = isset($gc['total_due']) ? (float)$gc['total_due'] : max($amount - $paid, 0);
                    $statusFlag  = (int)$gc['payment_status'];
                    $badgeClass  = 'bg-danger text-white';
                    $badgeText   = 'Unpaid';
                    if ($paid > 0 && $due > 0) { $badgeClass = 'bg-warning text-dark'; $badgeText = 'Partial'; }
                    if ($statusFlag === 1 || $due <= 0.00001) { $badgeClass = 'bg-success text-white'; $badgeText = 'Paid'; $due = 0; }
                  ?>
                  <tr>
                    <td><?php echo $idx + 1; ?></td>
                    <td><?php echo $gc['created_at'] ? date('d-m-Y', strtotime($gc['created_at'])) : '-'; ?></td>
                    <td><?php echo htmlspecialchars($gc['contri_year']); ?></td>
                    <td><?php echo htmlspecialchars($gc['fmb_type']); ?></td>
                    <td><?php echo htmlspecialchars($gc['contri_type']); ?></td>
                    <td class="text-end">&#8377;<?php echo number_format($amount, 2); ?></td>
                    <td class="text-end text-success">&#8377;<?php echo number_format($paid, 2); ?></td>
                    <td class="text-end text-danger">&#8377;<?php echo number_format($due, 2); ?></td>
                    <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span></td>
                    <td>
                      <button class="view-description btn btn-sm btn-outline-primary" data-description="<?php echo htmlspecialchars($gc['description']); ?>" data-toggle="modal" data-target="#description-modal" title="View Description">
                        <i class="fa-solid fa-eye"></i>
                      </button>
                    </td>
                    <td>
                      <button class="btn btn-sm btn-outline-secondary view-gc-payments" data-fmbgc-id="<?php echo (int)$gc['id']; ?>" data-amount="<?php echo number_format($amount,2,'.',''); ?>" data-paid="<?php echo number_format($paid,2,'.',''); ?>" data-due="<?php echo number_format($due,2,'.',''); ?>" title="View Payments">
                        <i class="fa-regular fa-money-bill-1"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="10" class="text-center text-muted">No general contributions found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card shadow-sm rounded-3 mt-4">
        <div class="card-header bg-light text-center">
          <h5 class="mb-0">Payment History</h5>
        </div>
        <div class="card-body p-0">
          <table class="table table-striped table-hover mb-0">
            <thead class="thead-dark">
              <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Remarks</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($fmb_takhmeen_details['all_payments'])): ?>
                <?php foreach ($fmb_takhmeen_details['all_payments'] as $pay): ?>
                  <tr>
                    <td><?php echo date('d-M-Y', strtotime($pay['payment_date'])) ?></td>
                    <td class="text-success"><?php echo number_format($pay['amount'], 2) ?></td>
                    <td><?php echo $pay['payment_method'] ?></td>
                    <td><?php echo $pay['remarks'] ?></td>
                    <td><button class="view-invoice btn btn-sm btn-primary" data-payment-id="<?php echo $pay["id"]; ?>">Payment Receipt</button></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center text-muted">No payments found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="description-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="fa-solid fa-plus-circle me-2"></i> Description</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="modal-view-description" class="text-dark"></p>
        </div>
      </div>
    </div>
  </div>
  <!-- General Contribution Payments History Modal -->
  <div class="modal fade" id="gc-payments-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fa-solid fa-receipt me-2"></i> General Contribution Payments</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body p-0">
          <div class="p-3 border-bottom small bg-light">
            <div class="d-flex flex-wrap gap-3">
              <div><strong>Invoice ID:</strong> <span id="gcph-invoice-id">-</span></div>
              <div><strong>Contri Year:</strong> <span id="gcph-year">-</span></div>
              <div><strong>Type:</strong> <span id="gcph-type">-</span></div>
              <div><strong>Amount:</strong> ₹<span id="gcph-amount">0.00</span></div>
              <div><strong>Received:</strong> ₹<span id="gcph-received" class="text-success">0.00</span></div>
              <div><strong>Balance:</strong> ₹<span id="gcph-balance" class="text-danger">0.00</span></div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-sm table-striped mb-0" id="gc-payments-table">
              <thead class="thead-dark">
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th class="text-end">Amount (₹)</th>
                  <th>Method</th>
                  <th>Remarks</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="5" class="text-center text-muted">Select a contribution to view payments.</td></tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).on("click", ".view-invoice", function(e) {
    e.preventDefault();
    const paymentId = $(this).data("payment-id");

    $.ajax({
      url: "<?php echo base_url('anjuman/generate_pdf'); ?>",
      type: "POST",
      data: {
        id: paymentId,
        for: 1,
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

  $(".view-description").on("click", function(e) {
    e.preventDefault();
    if ($(this).data("description")) {
      $("#modal-view-description").text($(this).data("description"));
    } else {
      $("#modal-view-description").text("No description found!");
    }
  });

  // Load General Contribution payment history
  $(document).on('click', '.view-gc-payments', function(e){
    e.preventDefault();
    const invoiceId = $(this).data('fmbgc-id');
    if(!invoiceId){ return; }
    // Reset
    $('#gc-payments-table tbody').html('<tr><td colspan="5" class="text-center text-muted">Loading...</td></tr>');
    $('#gcph-invoice-id').text(invoiceId);
    // Fire AJAX
    $.ajax({
      url: '<?php echo base_url('anjuman/fmbgc_payment_history'); ?>',
      type: 'POST',
      dataType: 'json',
      data: { fmbgc_id: invoiceId },
      success: function(res){
        if(!res || !res.success){
          $('#gc-payments-table tbody').html('<tr><td colspan="5" class="text-center text-danger">'+(res && res.message ? res.message : 'Failed to load payments')+'</td></tr>');
          $('#gc-payments-modal').modal('show');
          return;
        }
        const inv = res.invoice || {};
        $('#gcph-year').text(inv.contri_year || '-');
        $('#gcph-type').text(inv.contri_type || '-');
        $('#gcph-amount').text(parseFloat(inv.amount || 0).toFixed(2));
        $('#gcph-received').text(parseFloat(res.total_received || 0).toFixed(2));
        $('#gcph-balance').text(parseFloat(res.balance_due || 0).toFixed(2));
        const pays = res.payments || [];
        if(pays.length === 0){
          $('#gc-payments-table tbody').html('<tr><td colspan="5" class="text-center text-muted">No payments recorded.</td></tr>');
        } else {
          let rows = '';
            pays.forEach(function(p,i){
              rows += '<tr>'+
                '<td>'+(i+1)+'</td>'+
                '<td>'+( (p.payment_date) ? moment(p.payment_date).format('DD-MMM-YYYY') : '-' )+'</td>'+
                '<td class="text-end text-success">'+parseFloat(p.amount||0).toFixed(2)+'</td>'+
                '<td>'+(p.payment_method || '-')+'</td>'+
                '<td>'+(p.remarks ? $('<div/>').text(p.remarks).html() : '-')+'</td>'+
              '</tr>';
            });
          $('#gc-payments-table tbody').html(rows);
        }
        $('#gc-payments-modal').modal('show');
      },
      error: function(){
        $('#gc-payments-table tbody').html('<tr><td colspan="5" class="text-center text-danger">Error loading data.</td></tr>');
        $('#gc-payments-modal').modal('show');
      }
    });
  });
</script>