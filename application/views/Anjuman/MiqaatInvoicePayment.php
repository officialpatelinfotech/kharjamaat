<div class="margintopcontainer mx-5 pt-5">
  <style>
    /* Slightly widen the Member Invoices modal on larger screens */
    @media (min-width: 576px) {
      #memberInvoicesModal .modal-dialog {
        max-width: 95% !important;
        width: 95%;
      }

      /* Make Payment History modal wider as well */
      #memberPaymentsModal .modal-dialog {
        max-width: 95% !important;
        width: 95%;
      }
    }

    /* Allow natural column widths (auto layout) */
    .km-flex-columns th,
    .km-flex-columns td {
      word-break: break-word;
    }

    /* Flash highlight for updated rows */
    .flash-highlight {
      animation: km-flash-bg 1.2s ease-in-out 1;
    }

    @keyframes km-flash-bg {
      0% {
        background-color: #fff3cd;
      }

      /* light warning */
      100% {
        background-color: transparent;
      }
    }

    /* Table accent theme */
    .km-table-accent thead.thead-light th {
      background-color: #e9f3ff;
      /* soft primary tint */
      color: #0b5ed7;
      border-color: #cfe2ff;
    }

    .km-table-accent tbody tr:nth-of-type(odd) {
      background-color: #f8fbff;
    }

    .km-table-accent tbody tr:hover {
      background-color: #eef7ff;
    }

    .km-table-accent tfoot th {
      background-color: #f1f6ff;
      border-top: 2px solid #cfe2ff;
    }

    /* Sortable headers */
    th.km-sortable {
      cursor: pointer;
      position: relative;
      user-select: none;
    }

    th.km-sortable .sort-indicator {
      font-size: 11px;
      margin-left: 4px;
      color: #666;
    }

    th.km-sortable[data-sort-dir="asc"] .sort-indicator {
      color: #0b5ed7;
    }

    th.km-sortable[data-sort-dir="desc"] .sort-indicator {
      color: #0b5ed7;
    }
  </style>
  <div class="row">
    <div class="col-12">
      <div class="col-12 col-md-6 m-0 mb-2">
        <a href="<?php echo base_url("anjuman/fmbniyaz") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
      </div>
    </div>
  </div>
  <div class="col-12 mb-3">
    <?php
    // Prefer current Hijri year passed from controller; fallback will be updated later by JS when default year inferred.
    $title_display_year = '';
    if (isset($current_hijri_year) && $current_hijri_year !== '') {
      $title_display_year = $current_hijri_year;
    }

    // Simple helper for Indian number grouping without decimals (e.g., 1234567 -> 12,34,567)
    if (!function_exists('inr_format')) {
      function inr_format($num)
      {
        $num = (int)round($num);
        $n = (string)$num;
        if (strlen($n) <= 3) return $n; // no grouping needed
        $last3 = substr($n, -3);
        $rest = substr($n, 0, -3);
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
        return $rest . ',' . $last3;
      }
    }
    ?>
    <h4 class="heading text-center mb-0">
      <span class="text-primary"><?php echo isset($miqaat_type) ? $miqaat_type : ''; ?></span>
      Miqaat Invoice Payments
      <span id="payment-title-hijri-year" class="text-muted" style="font-size:0.9em;">
        <?php echo $title_display_year ? '(Hijri ' . htmlspecialchars($title_display_year) . ')' : ''; ?>
      </span>
    </h4>
  </div>

  <?php
  $members = [];

  if (isset($member_miqaat_payments)) {
    if (is_array($member_miqaat_payments) && isset($member_miqaat_payments['members']) && is_array($member_miqaat_payments['members'])) {
      $members = $member_miqaat_payments['members'];
    } elseif (is_array($member_miqaat_payments)) {
      $members = $member_miqaat_payments;
    }
  }

  // Prefer full Hijri years list passed from controller (hijri_calendar); fallback to deriving from invoice data.
  $all_hijri_years = [];
  if (isset($hijri_years) && is_array($hijri_years) && !empty($hijri_years)) {
    $all_hijri_years = array_values(array_unique(array_filter($hijri_years, function ($y) {
      return $y !== null && (string)$y !== '';
    })));
    rsort($all_hijri_years);
  }

  // Default sorting: Sector -> Sub_Sector -> Full_Name (case-insensitive)
  if (!empty($members) && is_array($members)) {
    usort($members, function($a, $b) {
      $sa = isset($a['Sector']) ? strtolower(trim($a['Sector'])) : (isset($a['sector']) ? strtolower(trim($a['sector'])) : '');
      $sb = isset($b['Sector']) ? strtolower(trim($b['Sector'])) : (isset($b['sector']) ? strtolower(trim($b['sector'])) : '');
      if ($sa !== $sb) return $sa < $sb ? -1 : 1;
      $ssa = isset($a['Sub_Sector']) ? strtolower(trim($a['Sub_Sector'])) : (isset($a['sub_sector']) ? strtolower(trim($a['sub_sector'])) : '');
      $ssb = isset($b['Sub_Sector']) ? strtolower(trim($b['Sub_Sector'])) : (isset($b['sub_sector']) ? strtolower(trim($b['sub_sector'])) : '');
      if ($ssa !== $ssb) return $ssa < $ssb ? -1 : 1;
      $na = isset($a['Full_Name']) ? strtolower(trim($a['Full_Name'])) : (isset($a['full_name']) ? strtolower(trim($a['full_name'])) : '');
      $nb = isset($b['Full_Name']) ? strtolower(trim($b['Full_Name'])) : (isset($b['full_name']) ? strtolower(trim($b['full_name'])) : '');
      if ($na === $nb) return 0;
      return $na < $nb ? -1 : 1;
    });
  }

  // Show both HOF and FM rows: remove prior HOF-only filter so family members with invoices/payments appear too.

  $rows = [];
  $grand_total = 0.0;
  if (!empty($members)) {
    // Build distinct Sectors, Sub Sectors, and Hijri Years from data
    $sectors = [];
    $sub_sectors = [];
    $years_from_invoices = [];
    foreach ($members as $m) {
      $its = isset($m['ITS_ID']) ? $m['ITS_ID'] : '';
      $name = isset($m['Full_Name']) ? $m['Full_Name'] : '';
      $sector = isset($m['Sector']) ? $m['Sector'] : (isset($m['sector']) ? $m['sector'] : '');
      $subSector = isset($m['Sub_Sector']) ? $m['Sub_Sector'] : (isset($m['sub_sector']) ? $m['sub_sector'] : '');
      if ($sector !== '' && !in_array($sector, $sectors, true)) $sectors[] = $sector;
      if ($subSector !== '' && !in_array($subSector, $sub_sectors, true)) $sub_sectors[] = $subSector;
      if (isset($m['miqaat_invoices']) && is_array($m['miqaat_invoices'])) {
        foreach ($m['miqaat_invoices'] as $inv) {
          $row = [
            'invoice_id'   => isset($inv['invoice_id']) ? $inv['invoice_id'] : '',
            'its_id'       => $its,
            'full_name'    => $name,
            'miqaat_id'    => isset($inv['miqaat_id']) ? $inv['miqaat_id'] : '',
            'miqaat_name'  => isset($inv['miqaat_name']) ? $inv['miqaat_name'] : '',
            'invoice_date' => isset($inv['invoice_date']) ? $inv['invoice_date'] : '',
            'amount'       => isset($inv['invoice_amount']) ? (float)$inv['invoice_amount'] : 0.0,
            'description'  => isset($inv['description']) ? $inv['description'] : '',
          ];
          $rows[] = $row;
          $grand_total += (float)$row['amount'];
          if (isset($inv['invoice_year']) && $inv['invoice_year'] !== '' && !in_array($inv['invoice_year'], $years_from_invoices, true)) {
            $years_from_invoices[] = $inv['invoice_year'];
          }
        }
      }
    }
    sort($sectors);
    sort($sub_sectors);
    rsort($years_from_invoices); // show latest first if present
  }

  // Year dropdown should list all years available; prefer controller list.
  $hijri_years = !empty($all_hijri_years) ? $all_hijri_years : ($years_from_invoices ?? []);

  // Note: Payment view is read-only; Fala ni Niyaz edit/delete logic removed.
  ?>

  <?php if (empty($members)) : ?>
    <div class="col-12 alert alert-info">No invoices found for members.</div>
  <?php else : ?>
    <style>
      #miqaat-payment-filters label {
        font-weight: 600;
        font-size: 12px;
      }

      #miqaat-payment-filters input,
      #miqaat-payment-filters select {
        font-size: 13px;
      }
    </style>
    <?php
    // Determine default year: prefer controller-provided $current_hijri_year if set, else latest from $hijri_years list.
    $default_hijri_year = '';
    if (isset($current_hijri_year) && $current_hijri_year !== '') {
      $default_hijri_year = $current_hijri_year;
    } elseif (!empty($hijri_years)) {
      // $hijri_years already rsort()ed: first element is latest
      $default_hijri_year = $hijri_years[0];
    }
    ?>

    <div class="d-flex flex-wrap justify-content-between align-items-center p-2 bg-white border mb-2 col-4 mx-auto" style="border-radius:10px;">
      <div>
        <strong>Total Invoice Amount:</strong>
        <span id="miqaat-payment-total-amount" class="text-success">₹<?php echo inr_format($grand_total); ?></span>
      </div>
      <div class="text-muted small">
        Members shown:
        <span id="miqaat-payment-members-count"><?php echo count($members); ?></span>
      </div>
    </div>

    <div id="miqaat-payment-filters" class="p-3 bg-light border mb-2">
      <div class="form-row">
        <div class="col-md-3 mb-2">
          <label for="pf-name" class="mb-1 text-muted">Name or ITS</label>
          <input type="text" id="pf-name" class="form-control form-control-sm" placeholder="Search name or ITS...">
        </div>
        <div class="col-md-3 mb-2">
          <label for="pf-sector" class="mb-1 text-muted">Sector</label>
          <select id="pf-sector" class="form-control form-control-sm">
            <option value="">All Sectors</option>
            <?php if (!empty($sectors)) : foreach ($sectors as $s) : if ($s === '') continue; ?>
                <option value="<?php echo htmlspecialchars(strtolower($s), ENT_QUOTES); ?>"><?php echo htmlspecialchars($s); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label for="pf-subsector" class="mb-1 text-muted">Sub Sector</label>
          <select id="pf-subsector" class="form-control form-control-sm">
            <option value="">All Sub Sectors</option>
            <?php if (!empty($sub_sectors)) : foreach ($sub_sectors as $ss) : if ($ss === '') continue; ?>
                <option value="<?php echo htmlspecialchars(strtolower($ss), ENT_QUOTES); ?>"><?php echo htmlspecialchars($ss); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label for="pf-year" class="mb-1 text-muted">Hijri Year</label>
          <select id="pf-year" class="form-control form-control-sm" data-default-year="<?php echo htmlspecialchars($default_hijri_year, ENT_QUOTES); ?>">
            <option value="">All Years</option>
            <?php if (!empty($hijri_years)) : foreach ($hijri_years as $y) : ?>
                <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>" <?php echo ($default_hijri_year === $y ? 'selected' : ''); ?>><?php echo htmlspecialchars($y); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-3 mb-2 d-flex align-items-end">
          <button type="button" id="pf-clear" class="btn btn-outline-secondary btn-sm w-100">Clear Filters</button>
        </div>
      </div>
    </div>
    <div class="col-12 table-responsive">
      <table class="table table-striped table-bordered km-flex-columns km-table-accent" id="miqaat-payments-table">
        <thead class="thead-light">
          <tr>
            <th class="km-sortable" data-sort-type="number"># <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-type="string">ITS ID <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-type="string">Member Name <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-type="string">Sector <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-type="string">Sub Sector <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-type="number">Total Invoices <span class="sort-indicator"></span></th>
            <th class="km-sortable text-right" data-sort-type="currency">Total Amount <span class="sort-indicator"></span></th>
            <th class="km-sortable text-right" data-sort-type="currency">Total Paid <span class="sort-indicator"></span></th>
            <th class="km-sortable text-right" data-sort-type="currency">Total Due <span class="sort-indicator"></span></th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="tbody-light">
          <?php foreach ($members as $key => $m) : ?>
            <?php
            $its = isset($m['ITS_ID']) ? $m['ITS_ID'] : '';
            $name = isset($m['Full_Name']) ? $m['Full_Name'] : '';
            $sector = isset($m['Sector']) ? $m['Sector'] : (isset($m['sector']) ? $m['sector'] : '');
            $subSector = isset($m['Sub_Sector']) ? $m['Sub_Sector'] : (isset($m['sub_sector']) ? $m['sub_sector'] : '');
            $invoices = isset($m['miqaat_invoices']) && is_array($m['miqaat_invoices']) ? $m['miqaat_invoices'] : [];
            $count = count($invoices);
            $totalAmt = 0.0;
            $totalPaid = isset($m['total_paid']) ? (float)$m['total_paid'] : 0.0;
            $totalDue  = isset($m['total_due'])  ? (float)$m['total_due']  : null;
            foreach ($invoices as $inv) {
              $totalAmt += isset($inv['invoice_amount']) ? (float)$inv['invoice_amount'] : 0.0;
              if (!isset($m['total_paid']) && isset($inv['paid_amount'])) {
                $totalPaid += (float)$inv['paid_amount'];
              }
            }
            // If total due wasn't provided at member level, compute from invoice due_amount or fallback
            if ($totalDue === null) {
              $sumDue = 0.0;
              foreach ($invoices as $inv) {
                if (isset($inv['due_amount'])) {
                  $sumDue += (float)$inv['due_amount'];
                }
              }
              // If invoices carried due_amount, use that; else fallback to totalAmt - totalPaid
              $totalDue = $sumDue > 0 ? $sumDue : max(0.0, $totalAmt - $totalPaid);
            }
            ?>
            <tr data-sector="<?php echo htmlspecialchars(strtolower($sector), ENT_QUOTES); ?>" data-subsector="<?php echo htmlspecialchars(strtolower($subSector), ENT_QUOTES); ?>">
              <td><?php echo $key + 1; ?></td>
              <td><?php echo htmlspecialchars($its); ?></td>
              <td class="member-name-cell"><?php echo htmlspecialchars($name); ?></td>
              <td><?php echo htmlspecialchars($sector); ?></td>
              <td><?php echo htmlspecialchars($subSector); ?></td>
              <td><?php echo $count; ?></td>
              <td class="text-right">₹<?php echo inr_format($totalAmt); ?></td>
              <td class="text-right">₹<?php echo inr_format($totalPaid); ?></td>
              <?php $dueClass = ($totalDue > 0.0) ? 'text-danger font-weight-bold' : 'text-success font-weight-bold'; ?>
              <td class="text-right"><span class="<?php echo $dueClass; ?>">₹<?php echo inr_format($totalDue); ?></span></td>
              <td>
                <button
                  class="btn btn-sm btn-primary view-invoices-btn"
                  data-toggle="modal"
                  data-target="#memberInvoicesModal"
                  data-its="<?php echo htmlspecialchars($its); ?>"
                  data-name="<?php echo htmlspecialchars($name); ?>"
                  data-invoices='<?php echo htmlspecialchars(json_encode($invoices), ENT_QUOTES, "UTF-8"); ?>'>Receive Payment</button>
                <button
                  class="btn btn-sm btn-outline-info view-payments-btn mt-2"
                  data-toggle="modal"
                  data-target="#memberPaymentsModal"
                  data-its="<?php echo htmlspecialchars($its); ?>"
                  data-name="<?php echo htmlspecialchars($name); ?>"
                  data-payments='<?php echo htmlspecialchars(json_encode(isset($m['payments']) ? $m['payments'] : []), ENT_QUOTES, "UTF-8"); ?>'>Payment History</button>
              </td>
            </tr>
            <tr id="payments-inline-<?php echo $key; ?>" class="payment-history-row d-none">
              <td colspan="10">
                <div class="payment-history-container small text-muted">No payments to show.</div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <!-- Member Payments Modal -->
    <div class="modal fade" id="memberPaymentsModal" tabindex="-1" role="dialog" aria-labelledby="memberPaymentsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="memberPaymentsModalLabel">Payment History</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <strong>ITS:</strong> <span id="payments-modal-its"></span>
              <strong>Name:</strong> <span id="payments-modal-name"></span>
            </div>
            <div id="payments-modal-table-wrapper" class="table-responsive"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Payment Modal (amount only for now) -->
    <div class="modal fade" id="editPaymentModal" tabindex="-1" role="dialog" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="edit-payment-form">
              <input type="hidden" id="ep-payment-id" name="payment_id" />
              <input type="hidden" id="ep-invoice-id" name="invoice_id" />
              <div class="form-group">
                <label>Payment ID</label>
                <input type="text" id="ep-payment-id-display" class="form-control" readonly />
              </div>
              <div class="form-group">
                <label>Amount</label>
                <input type="number" id="ep-amount" name="amount" class="form-control" step="0.01" min="0.01" required />
                <small class="form-text text-muted" id="ep-max-hint"></small>
              </div>
            </form>
            <div id="ep-alert" class="alert d-none" role="alert"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" id="ep-submit" class="btn btn-primary">Save Changes</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="memberInvoicesModal" tabindex="-1" role="dialog" aria-labelledby="memberInvoicesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="memberInvoicesModalLabel">Member Invoices</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <strong>ITS:</strong> <span id="miqaat-modal-its"></span>
              <strong>Name:</strong> <span id="miqaat-modal-name"></span>
            </div>
            <div id="miqaat-modal-table-wrapper" class="table-responsive"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Receive Payment Modal (invoice-wise) -->
    <div class="modal fade" id="receiveInvoicePaymentModal" tabindex="-1" role="dialog" aria-labelledby="receiveInvoicePaymentModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="receiveInvoicePaymentModalLabel">Receive Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="invoice-payment-form">
              <input type="hidden" id="rip-invoice-id" name="invoice_id" />
              <div class="form-group">
                <label>Invoice ID</label>
                <input type="text" id="rip-invoice-id-display" class="form-control" readonly />
              </div>
              <div class="form-group">
                <label>Amount</label>
                <input type="number" id="rip-amount" name="amount" class="form-control" step="0.01" min="0.01" required />
                <small class="form-text text-muted" id="rip-max-hint"></small>
              </div>
              <div class="form-group">
                <label>Payment Date</label>
                <input type="date" id="rip-date" name="payment_date" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Payment Method</label>
                <select id="rip-method" name="payment_method" class="form-control">
                  <option value="Cash">Cash</option>
                  <option value="Cheque">Cheque</option>
                </select>
              </div>
              <div class="form-group">
                <label>Remarks</label>
                <textarea id="rip-remarks" name="remarks" class="form-control" rows="2" placeholder="Optional"></textarea>
              </div>
            </form>
            <div id="rip-alert" class="alert d-none" role="alert"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="rip-submit" class="btn btn-primary">Save Payment</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      (function() {
        // Filters for member list (name/sector/sub-sector/year) with per-year totals
        function applyPaymentFilters() {
          const nameVal = (document.getElementById('pf-name').value || '').trim().toLowerCase();
          const sectorVal = (document.getElementById('pf-sector').value || '').trim();
          const subVal = (document.getElementById('pf-subsector').value || '').trim();
          const yearRaw = (document.getElementById('pf-year').value || '').trim();
          const yearVal = yearRaw.toLowerCase();
          // Only operate on main member rows, not the hidden payment-history rows
          const rows = document.querySelectorAll('#miqaat-payments-table tbody tr:not(.payment-history-row)');
          let index = 1;
          let visibleMembers = 0;
          let visibleAmountTotal = 0;

          function currency(n) {
            if (n === undefined || n === null) return '₹0';
            const num = Math.round(parseFloat(n) || 0);
            // Use Indian locale for digit grouping
            return '₹' + num.toLocaleString('en-IN');
          }

          function parseNum(v) {
            const n = parseFloat(v);
            return isNaN(n) ? 0 : n;
          }

          rows.forEach(r => {
            const nameCell = r.querySelector('.member-name-cell');
            const rName = (nameCell ? nameCell.textContent : '').trim().toLowerCase();
            const rIts = ((r.children && r.children[1] ? r.children[1].textContent : '') || '').trim().toLowerCase();
            const rSector = r.getAttribute('data-sector') || '';
            const rSub = r.getAttribute('data-subsector') || '';
            let show = true;
            if (nameVal && rName.indexOf(nameVal) === -1 && rIts.indexOf(nameVal) === -1) show = false;
            if (sectorVal && rSector !== sectorVal) show = false;
            if (subVal && rSub !== subVal) show = false;

            if (!show) {
              r.style.display = 'none';
              return;
            }

            // Recalculate totals for selected year (or all if none)
            const invBtn = r.querySelector('.view-invoices-btn');
            let invoices = [];
            if (invBtn) {
              try {
                invoices = JSON.parse(invBtn.getAttribute('data-invoices') || '[]');
              } catch (e) {
                invoices = [];
              }
            }
            let working = invoices;
            if (yearVal) {
              working = invoices.filter(inv => String(inv.invoice_year || '').toLowerCase() === yearVal);
            }
            // Compute totals
            let count = working.length;
            let amountSum = 0,
              paidSum = 0,
              dueSum = 0;
            working.forEach(inv => {
              amountSum += parseNum(inv.invoice_amount || 0);
              const paid = parseNum(inv.paid_amount || 0);
              let due = (inv.due_amount !== undefined) ? parseNum(inv.due_amount) : (parseNum(inv.invoice_amount || 0) - paid);
              if (due < 0) due = 0;
              paidSum += paid;
              dueSum += due;
            });

            const cells = r.children; // 0 #,1 ITS,2 Name,3 Sector,4 Sub Sector,5 Count,6 Amount,7 Paid,8 Due,9 Action
            if (cells[5]) cells[5].textContent = count;
            if (cells[6]) cells[6].textContent = currency(amountSum);
            if (cells[7]) cells[7].textContent = currency(paidSum);
            if (cells[8]) cells[8].innerHTML = '<span class="' + (dueSum > 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold') + '">' + currency(dueSum) + '</span>';

            r.style.display = '';
            // Set serial number for visible main rows only
            const firstCell = r.querySelector('td');
            if (firstCell) firstCell.textContent = index++;

            visibleMembers += 1;
            visibleAmountTotal += amountSum;
          });

          const totalEl = document.getElementById('miqaat-payment-total-amount');
          if (totalEl) totalEl.textContent = currency(visibleAmountTotal);
          const membersEl = document.getElementById('miqaat-payment-members-count');
          if (membersEl) membersEl.textContent = String(visibleMembers);
        }

        const pfName = document.getElementById('pf-name');
        const pfSector = document.getElementById('pf-sector');
        const pfSub = document.getElementById('pf-subsector');
        const pfYear = document.getElementById('pf-year');
        if (pfName) pfName.addEventListener('input', applyPaymentFilters);
        if (pfSector) pfSector.addEventListener('change', applyPaymentFilters);
        if (pfSub) pfSub.addEventListener('change', applyPaymentFilters);
        if (pfYear) pfYear.addEventListener('change', function() {
          applyPaymentFilters();
          const titleYearEl = document.getElementById('payment-title-hijri-year');
          if (titleYearEl) {
            const y = pfYear.value.trim();
            if (y) {
              titleYearEl.textContent = '(Hijri ' + y + ')';
            } else {
              // fallback to default-year data attribute
              const defYear = (pfYear.getAttribute('data-default-year') || '').trim();
              titleYearEl.textContent = defYear ? '(Hijri ' + defYear + ')' : '';
            }
          }
        });
        const pfClear = document.getElementById('pf-clear');
        if (pfClear) {
          pfClear.addEventListener('click', function() {
            if (pfName) pfName.value = '';
            if (pfSector) pfSector.value = '';
            if (pfSub) pfSub.value = '';
            if (pfYear) pfYear.value = '';
            applyPaymentFilters();
          });
        }

        // Apply default year filter on initial load
        if (pfYear) {
          const defYear = (pfYear.getAttribute('data-default-year') || '').trim();
          if (defYear) {
            pfYear.value = defYear;
            applyPaymentFilters();
            const titleYearEl = document.getElementById('payment-title-hijri-year');
            if (titleYearEl) {
              titleYearEl.textContent = '(Hijri ' + defYear + ')';
            }
          }
        }

        // Ensure summary bar is initialized even when default year is empty
        applyPaymentFilters();

        // Ensure proper overlay handling for stacked modals (member invoices -> receive payment)
        if (window.jQuery) {
          // When a modal is shown, bump its z-index above existing modals and adjust the backdrop
          jQuery(document).on('show.bs.modal', '.modal', function() {
            var $visibleModals = jQuery('.modal:visible');
            var zIndex = 1040 + (10 * $visibleModals.length);
            jQuery(this).css('z-index', zIndex);
            // Next tick so backdrop is inserted first
            setTimeout(function() {
              jQuery('.modal-backdrop').not('.modal-stack')
                .css('z-index', zIndex - 1)
                .addClass('modal-stack');
            }, 0);
          });

          // Keep body from losing scroll lock when one of multiple modals closes
          jQuery(document).on('hidden.bs.modal', '.modal', function() {
            if (jQuery('.modal:visible').length) {
              jQuery('body').addClass('modal-open');
            }
          });

          // Refresh page when Receive Payment modal is closed
          jQuery(document).on('hidden.bs.modal', '#receiveInvoicePaymentModal', function() {
            window.location.reload();
          });
        }

        function formatDate(d) {
          if (!d) return '';
          const dt = new Date(d.replace(/-/g, '/'));
          if (isNaN(dt)) return d;
          const dd = String(dt.getDate()).padStart(2, '0');
          const mm = String(dt.getMonth() + 1).padStart(2, '0');
          const yyyy = dt.getFullYear();
          return `${dd}-${mm}-${yyyy}`;
        }

        function currency(n) {
          if (n === undefined || n === null) return '₹0';
          const num = Math.round(parseFloat(n) || 0);
          return '₹' + num.toLocaleString('en-IN');
        }

        function parseCurrency(text) {
          if (text == null) return 0;
          const n = String(text).replace(/[^0-9.-]/g, '');
          const v = parseFloat(n);
          return isNaN(v) ? 0 : v;
        }

        function buildTable(invoices) {
          if (!invoices || !invoices.length) {
            return '<div class="alert alert-info">No invoices for this member.</div>';
          }
          // Sort invoices year-wise (desc) then date desc
          invoices.sort((a, b) => {
            const ya = parseInt(a.invoice_year || 0, 10);
            const yb = parseInt(b.invoice_year || 0, 10);
            if (yb !== ya) return yb - ya; // year desc
            // fallback: date desc
            const da = a.invoice_date || '';
            const db = b.invoice_date || '';
            return (db > da) ? 1 : (db < da ? -1 : 0);
          });

          let totalAmount = 0,
            totalPaid = 0,
            totalDue = 0;
          let currentYear = null;
          let rowsHtml = '';
          invoices.forEach(inv => {
            const year = inv.invoice_year || '';
            if (year !== currentYear) {
              currentYear = year;
              rowsHtml += `<tr class="table-secondary"><td colspan="10" class="font-weight-bold">Hijri Year: ${year || 'Unknown'}</td></tr>`;
            }
            const amount = parseFloat(inv.invoice_amount || 0);
            const due = parseFloat(inv.due_amount || 0);
            const paid = parseFloat(inv.paid_amount || 0);
            totalAmount += amount;
            totalPaid += paid;
            totalDue += due;
            const disabledAttr = (isNaN(due) || due <= 0) ? 'disabled' : '';
            rowsHtml += `
              <tr>
                <td>${inv.invoice_id ? inv.invoice_id : ''}</td>
                <td>${inv.miqaat_name ? inv.miqaat_name : ''}</td>
                <td>${inv.miqaat_id ? 'M#' + inv.miqaat_id : ''}</td>
                <td>${formatDate(inv.invoice_date)}</td>
                <td>${year || ''}</td>
                <td class="text-right">${currency(amount).replace('₹','₹')}</td>
                <td class="text-right">${currency(paid).replace('₹','₹')}</td>
                <td class="text-right">${currency(due).replace('₹','₹')}</td>
                <td>${inv.description ? inv.description : ''}</td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-success receive-payment-btn"
                    data-invoice-id="${inv.invoice_id || ''}"
                    data-amount="${isNaN(due) ? 0 : due}" ${disabledAttr}>Receive Payment</button>
                </td>
              </tr>`;
          });
          const dueClass = (totalDue > 0) ? 'text-danger font-weight-bold' : 'text-success font-weight-bold';
          return `
            <table class="table table-striped table-bordered km-flex-columns km-table-accent">
              <thead class="thead-light">
                <tr>
                  <th>Invoice ID</th>
                  <th>Miqaat</th>
                  <th>Miqaat ID</th>
                  <th>Invoice Date</th>
                  <th>Year</th>
                  <th class="text-right">Amount</th>
                  <th class="text-right">Paid</th>
                  <th class="text-right">Due</th>
                  <th>Description</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                ${rowsHtml}
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="5" class="text-right">Totals</th>
                  <th class="text-right">${currency(totalAmount)}</th>
                  <th class="text-right">${currency(totalPaid)}</th>
                  <th class="text-right ${dueClass}">${currency(totalDue)}</th>
                  <th colspan="2"></th>
                </tr>
              </tfoot>
            </table>
          `;
        }

        function buildPaymentsTable(payments, showActions = true) {
          if (!payments || !payments.length) {
            return '<div class="alert alert-info mb-0">No payments found.</div>';
          }
          let total = 0;
          const rows = payments.map(p => {
            const amt = parseFloat(p.amount || 0);
            total += amt;
            const dt = p.date || p.payment_date || '';
            const method = p.payment_method || '';
            const invId = p.invoice_id || '';
            const miqaatName = p.miqaat_name || '';
            const viewBtn = `<button type="button" class="btn btn-sm btn-outline-secondary view-receipt-btn" data-payment-id="${p.payment_id || ''}"><i class="fa-solid fa-file-pdf"></i></button>`;
            const actionBtns = showActions ? `
                <td class="text-center">
                  ${viewBtn}
                  <button type="button" class="btn btn-sm btn-outline-primary ml-2 edit-payment-btn" 
                          data-payment-id="${p.payment_id || ''}" 
                          data-amount="${isNaN(amt) ? 0 : amt}" 
                          data-invoice-id="${invId}"><i class="fa-solid fa-pen"></i></button>
                  <button type="button" class="btn btn-sm btn-outline-danger ml-2 delete-payment-btn" 
                          data-payment-id="${p.payment_id || ''}" 
                          data-amount="${isNaN(amt) ? 0 : amt}" 
                          data-invoice-id="${invId}"><i class="fa-solid fa-trash"></i></button>
                </td>` : '';
            return `
              <tr data-payment-id="${p.payment_id || ''}" data-invoice-id="${invId}">
                <td>${p.payment_id ? p.payment_id : ''}</td>
                <td class="text-right">${currency(amt)}</td>
                <td>${formatDate(dt)}</td>
                <td>${method}</td>
                <td>${invId}</td>
                <td>${miqaatName}</td>
                ${actionBtns}
              </tr>
            `;
          }).join('');
          const actionsHeader = showActions ? '<th>Actions</th>' : '';
          const colCount = showActions ? 7 : 6; // total columns rendered
          const colWidth = (100 / colCount).toFixed(2) + '%';
          const cols = Array.from({
            length: colCount
          }).map(() => `<col style="width:${colWidth}">`).join('');
          const tfootColsSpan = showActions ? 5 : 4; // remaining columns after 2 in total row
          return `
            <table class="table table-sm table-striped table-bordered mb-0 km-flex-columns km-table-accent">
              <thead class="thead-light">
                <tr>
                  <th>Payment ID</th>
                  <th class="text-right">Amount</th>
                  <th>Date</th>
                  <th>Method</th>
                  <th>Invoice</th>
                  <th>Miqaat</th>
                  ${actionsHeader}
                </tr>
              </thead>
              <tbody>${rows}</tbody>
              <tfoot>
                <tr>
                  <th class="text-right">Total</th>
                  <th class="text-right">${currency(total)}</th>
                  <th colspan="${tfootColsSpan}"></th>
                </tr>
              </tfoot>
            </table>
          `;
        }
        // View Receipt button: fetch PDF via AJAX (POST) with payment_id & for=2, then open blob
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.view-receipt-btn');
          if (!btn) return;
          const pid = btn.getAttribute('data-payment-id');
          if (!pid) return;
          const endpoint = '<?php echo base_url('common/generate_pdf'); ?>';

          // Provide light UI feedback
          btn.disabled = true;
          btn.classList.add('disabled');

          fetch(endpoint, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: 'id=' + encodeURIComponent(pid) + '&for=2'
            })
            .then(async (res) => {
              const ct = (res.headers.get('Content-Type') || '').toLowerCase();
              if (!res.ok) {
                // Try to surface any error text
                let msg = 'HTTP ' + res.status;
                try {
                  const t = await res.text();
                  if (t) msg += ': ' + t.substring(0, 200);
                } catch (_) {}
                throw new Error(msg);
              }
              if (ct.includes('application/pdf')) {
                return res.blob();
              }
              // Unexpected content-type; attempt to parse for message
              try {
                const t = await res.text();
                throw new Error(t.substring(0, 300) || 'Unexpected response (not PDF).');
              } catch (err) {
                throw err;
              }
            })
            .then((blob) => {
              const fileURL = URL.createObjectURL(blob);
              window.open(fileURL, '_blank');
              // Revoke after a minute to free memory
              setTimeout(() => URL.revokeObjectURL(fileURL), 60000);
            })
            .catch((err) => {
              alert('Unable to fetch receipt: ' + err.message);
            })
            .finally(() => {
              btn.disabled = false;
              btn.classList.remove('disabled');
            });
        });

        let currentTriggerBtn = null;
        let lastReceiveBtn = null;

        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.view-invoices-btn');
          if (!btn) return;
          currentTriggerBtn = btn;
          const its = btn.getAttribute('data-its') || '';
          const name = btn.getAttribute('data-name') || '';
          let invoices = [];
          try {
            invoices = JSON.parse(btn.getAttribute('data-invoices') || '[]');
          } catch (err) {
            invoices = [];
          }
          // Apply current Hijri year filter (pf-year) if selected
          const yearSel = document.getElementById('pf-year');
          if (yearSel && yearSel.value.trim() !== '') {
            const y = yearSel.value.trim();
            invoices = invoices.filter(inv => String(inv.invoice_year || '') === y);
          }
          document.getElementById('miqaat-modal-its').textContent = its;
          document.getElementById('miqaat-modal-name').textContent = name;
          document.getElementById('miqaat-modal-table-wrapper').innerHTML = buildTable(invoices);
        });

        // Re-render invoices modal table if year filter changes while modal is open
        (function attachYearChangeReRender() {
          const yearSel = document.getElementById('pf-year');
          if (!yearSel) return;
          yearSel.addEventListener('change', function() {
            const modal = document.getElementById('memberInvoicesModal');
            const isVisible = modal && (modal.classList.contains('show') || (window.jQuery && jQuery(modal).hasClass('show')));
            if (!isVisible || !currentTriggerBtn) return;
            let invoices = [];
            try {
              invoices = JSON.parse(currentTriggerBtn.getAttribute('data-invoices') || '[]');
            } catch (e) {
              invoices = [];
            }
            const yVal = yearSel.value.trim();
            if (yVal !== '') {
              invoices = invoices.filter(inv => String(inv.invoice_year || '') === yVal);
            }
            document.getElementById('miqaat-modal-table-wrapper').innerHTML = buildTable(invoices);
          });
        })();

        // Payment History: Modal
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.view-payments-btn');
          if (!btn) return;
          window.currentPaymentsTriggerBtn = btn; // track which member triggered this modal
          // also track related invoices button in same row for data sync
          const mainRow = btn.closest('tr');
          window.currentInvoicesBtn = mainRow ? mainRow.querySelector('.view-invoices-btn') : null;
          const its = btn.getAttribute('data-its') || '';
          const name = btn.getAttribute('data-name') || '';
          let payments = [];
          try {
            payments = JSON.parse(btn.getAttribute('data-payments') || '[]');
          } catch (_) {
            payments = [];
          }
          document.getElementById('payments-modal-its').textContent = its;
          document.getElementById('payments-modal-name').textContent = name;
          document.getElementById('payments-modal-table-wrapper').innerHTML = buildPaymentsTable(payments, true);
        });

        // Payment History: Inline toggle
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.toggle-payments-inline');
          if (!btn) return;
          const targetSel = btn.getAttribute('data-target');
          const row = document.querySelector(targetSel);
          let payments = [];
          try {
            payments = JSON.parse(btn.getAttribute('data-payments') || '[]');
          } catch (_) {
            payments = [];
          }
          if (row) {
            const container = row.querySelector('.payment-history-container');
            container.innerHTML = buildPaymentsTable(payments, false);
            row.classList.toggle('d-none');
          }
        });

        // Edit Payment button click (open modal)
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.edit-payment-btn');
          if (!btn) return;
          const pid = btn.getAttribute('data-payment-id');
          const amt = parseFloat(btn.getAttribute('data-amount') || '0');
          const invId = btn.getAttribute('data-invoice-id') || '';
          document.getElementById('ep-payment-id').value = pid || '';
          document.getElementById('ep-invoice-id').value = invId || '';
          document.getElementById('ep-payment-id-display').value = pid || '';
          document.getElementById('ep-amount').value = isNaN(amt) ? '' : amt.toFixed(2);
          // Set max for editing: cannot exceed (invoice total - other payments)
          // We don't have full context for invoice total here, so we'll compute max from current modal/payment table if available
          (function setEditMax() {
            const epMaxHint = document.getElementById('ep-max-hint');
            const input = document.getElementById('ep-amount');
            const invIdStr = String(invId || '');
            let maxAllowed = null;
            try {
              if (window.currentInvoicesBtn && invIdStr) {
                const invs = JSON.parse(window.currentInvoicesBtn.getAttribute('data-invoices') || '[]');
                const found = invs.find(x => String(x.invoice_id) === invIdStr);
                if (found) {
                  // Max = current amount + remaining due
                  const due = parseFloat(found.due_amount || 0);
                  maxAllowed = (isNaN(amt) ? 0 : amt) + (isNaN(due) ? 0 : due);
                }
              }
            } catch (e) {}
            if (maxAllowed !== null) {
              input.max = maxAllowed.toFixed(2);
              epMaxHint.textContent = 'Max allowed: ' + currency(maxAllowed);
            } else {
              input.removeAttribute('max');
              epMaxHint.textContent = '';
            }
          })();
          const alertBox = document.getElementById('ep-alert');
          alertBox.classList.add('d-none');
          alertBox.textContent = '';
          if (window.jQuery && typeof jQuery.fn !== 'undefined' && typeof jQuery.fn.modal === 'function') {
            jQuery('#editPaymentModal').modal('show');
          }
        });

        // Save edited payment amount
        document.getElementById('ep-submit').addEventListener('click', function() {
          const pid = document.getElementById('ep-payment-id').value;
          const invId = document.getElementById('ep-invoice-id').value;
          const newAmt = parseFloat(document.getElementById('ep-amount').value || '0');
          const alertBox = document.getElementById('ep-alert');
          const submitBtn = document.getElementById('ep-submit');
          alertBox.classList.add('d-none');
          alertBox.textContent = '';
          if (!pid || isNaN(newAmt) || newAmt <= 0) {
            alertBox.className = 'alert alert-danger';
            alertBox.textContent = 'Please enter a valid amount (> 0).';
            alertBox.classList.remove('d-none');
            return;
          }
          // Client-side guard: respect max if set
          const epMaxAttr = document.getElementById('ep-amount').getAttribute('max');
          if (epMaxAttr) {
            const maxVal = parseFloat(epMaxAttr);
            if (!isNaN(maxVal) && newAmt - maxVal > 1e-6) {
              alertBox.className = 'alert alert-danger';
              alertBox.textContent = 'Amount exceeds allowed maximum (' + currency(maxVal) + ').';
              alertBox.classList.remove('d-none');
              return;
            }
          }
          submitBtn.disabled = true;
          fetch('<?php echo base_url('anjuman/update_miqaat_payment_amount'); ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `payment_id=${encodeURIComponent(pid)}&amount=${encodeURIComponent(newAmt.toFixed(2))}`
            }).then(r => r.json().catch(() => ({
              success: false
            })))
            .then(data => {
              if (data && data.success) {
                // Update UI in payments modal table
                const table = document.querySelector('#memberPaymentsModal table');
                if (table) {
                  const tr = table.querySelector(`tbody tr[data-payment-id="${pid}"]`);
                  if (tr) {
                    const amtCell = tr.children[1];
                    const oldAmt = parseCurrency(amtCell.textContent);
                    const delta = newAmt - oldAmt;
                    amtCell.textContent = currency(newAmt);
                    // Update footer total
                    const tfootAmtCell = table.querySelector('tfoot th.text-right:nth-child(2)');
                    if (tfootAmtCell) {
                      const prevTotal = parseCurrency(tfootAmtCell.textContent);
                      tfootAmtCell.textContent = currency(prevTotal + delta);
                    }
                    // Update main row totals
                    if (window.currentPaymentsTriggerBtn) {
                      const mainRow = window.currentPaymentsTriggerBtn.closest('tr');
                      if (mainRow) {
                        const tds = mainRow.querySelectorAll('td');
                        const mainPaidPrev = parseCurrency(tds[7].textContent);
                        const mainDuePrev = parseCurrency(tds[8].textContent);
                        const mainPaidNew = mainPaidPrev + delta;
                        const mainDueNew = Math.max(0, mainDuePrev - delta);
                        tds[7].textContent = currency(mainPaidNew);
                        tds[8].innerHTML = '<span class="' + (mainDueNew > 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold') + '">' + currency(mainDueNew) + '</span>';
                        mainRow.classList.remove('flash-highlight');
                        void mainRow.offsetWidth;
                        mainRow.classList.add('flash-highlight');
                      }
                    }
                    // Update invoices data for the member (so invoice modal reflects changes when reopened)
                    if (window.currentInvoicesBtn && invId) {
                      try {
                        const invsJson = window.currentInvoicesBtn.getAttribute('data-invoices') || '[]';
                        const invs = JSON.parse(invsJson);
                        for (let i = 0; i < invs.length; i++) {
                          if (String(invs[i].invoice_id) === String(invId)) {
                            const prevPaid = parseFloat(invs[i].paid_amount || 0);
                            const prevDue = parseFloat(invs[i].due_amount || 0);
                            invs[i].paid_amount = (prevPaid + delta).toFixed(2);
                            invs[i].due_amount = Math.max(0, prevDue - delta).toFixed(2);
                            break;
                          }
                        }
                        window.currentInvoicesBtn.setAttribute('data-invoices', JSON.stringify(invs));
                      } catch (e) {
                        /* ignore */
                      }
                    }
                    // Update payments data payload on trigger button
                    if (window.currentPaymentsTriggerBtn) {
                      try {
                        const pjson = window.currentPaymentsTriggerBtn.getAttribute('data-payments') || '[]';
                        const parr = JSON.parse(pjson);
                        for (let i = 0; i < parr.length; i++) {
                          if (String(parr[i].payment_id) === String(pid)) {
                            parr[i].amount = newAmt.toFixed(2);
                            break;
                          }
                        }
                        window.currentPaymentsTriggerBtn.setAttribute('data-payments', JSON.stringify(parr));
                      } catch (e) {
                        /* ignore */
                      }
                    }
                  }
                }
                // Success alert and close
                alertBox.className = 'alert alert-success';
                alertBox.textContent = 'Payment updated.';
                alertBox.classList.remove('d-none');
                setTimeout(() => {
                  if (window.jQuery) jQuery('#editPaymentModal').modal('hide');
                }, 600);
              } else {
                alertBox.className = 'alert alert-danger';
                alertBox.textContent = (data && data.error) ? data.error : 'Update failed.';
                alertBox.classList.remove('d-none');
              }
            })
            .catch(() => {
              alertBox.className = 'alert alert-danger';
              alertBox.textContent = 'Network error. Please try again.';
              alertBox.classList.remove('d-none');
            })
            .finally(() => {
              submitBtn.disabled = false;
            });
        });

        // Delete Payment button click
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.delete-payment-btn');
          if (!btn) return;
          const pid = btn.getAttribute('data-payment-id');
          const amt = parseFloat(btn.getAttribute('data-amount') || '0');
          const invId = btn.getAttribute('data-invoice-id') || '';
          if (!pid) return;
          if (!confirm('Are you sure you want to delete this payment?')) return;
          fetch('<?php echo base_url('anjuman/delete_miqaat_payment'); ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `payment_id=${encodeURIComponent(pid)}`
            }).then(r => r.json().catch(() => ({
              success: false
            })))
            .then(data => {
              if (data && data.success) {
                // Remove row from table and update totals
                const table = document.querySelector('#memberPaymentsModal table');
                if (table) {
                  const tr = table.querySelector(`tbody tr[data-payment-id="${pid}"]`);
                  if (tr) {
                    // Update footer total (subtract amt)
                    const tfootAmtCell = table.querySelector('tfoot th.text-right:nth-child(2)');
                    if (tfootAmtCell) {
                      const prevTotal = parseCurrency(tfootAmtCell.textContent);
                      tfootAmtCell.textContent = currency(Math.max(0, prevTotal - amt));
                    }
                    tr.parentNode.removeChild(tr);
                  }
                  // If no more rows, show empty state
                  if (!table.querySelector('tbody tr')) {
                    document.getElementById('payments-modal-table-wrapper').innerHTML = '<div class="alert alert-info mb-0">No payments found.</div>';
                  }
                }
                // Update main row totals (reduce paid, increase due)
                if (window.currentPaymentsTriggerBtn) {
                  const mainRow = window.currentPaymentsTriggerBtn.closest('tr');
                  if (mainRow) {
                    const tds = mainRow.querySelectorAll('td');
                    const mainPaidPrev = parseCurrency(tds[7].textContent);
                    const mainDuePrev = parseCurrency(tds[8].textContent);
                    const mainPaidNew = Math.max(0, mainPaidPrev - amt);
                    const mainDueNew = mainDuePrev + amt;
                    tds[7].textContent = currency(mainPaidNew);
                    tds[8].innerHTML = '<span class="' + (mainDueNew > 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold') + '">' + currency(mainDueNew) + '</span>';
                    mainRow.classList.remove('flash-highlight');
                    void mainRow.offsetWidth;
                    mainRow.classList.add('flash-highlight');
                  }
                }
                // Update invoices data for the member
                if (window.currentInvoicesBtn && invId) {
                  try {
                    const invsJson = window.currentInvoicesBtn.getAttribute('data-invoices') || '[]';
                    const invs = JSON.parse(invsJson);
                    for (let i = 0; i < invs.length; i++) {
                      if (String(invs[i].invoice_id) === String(invId)) {
                        const prevPaid = parseFloat(invs[i].paid_amount || 0);
                        const prevDue = parseFloat(invs[i].due_amount || 0);
                        invs[i].paid_amount = Math.max(0, prevPaid - amt).toFixed(2);
                        invs[i].due_amount = (prevDue + amt).toFixed(2);
                        break;
                      }
                    }
                    window.currentInvoicesBtn.setAttribute('data-invoices', JSON.stringify(invs));
                  } catch (e) {
                    /* ignore */
                  }
                }
                // Update payments data payload on trigger button
                if (window.currentPaymentsTriggerBtn) {
                  try {
                    const pjson = window.currentPaymentsTriggerBtn.getAttribute('data-payments') || '[]';
                    let parr = JSON.parse(pjson);
                    parr = parr.filter(x => String(x.payment_id) !== String(pid));
                    window.currentPaymentsTriggerBtn.setAttribute('data-payments', JSON.stringify(parr));
                  } catch (e) {
                    /* ignore */
                  }
                }
              } else {
                alert('Delete failed.');
              }
            })
            .catch(() => alert('Network error while deleting.'));
        });

        // Receive Payment (invoice-wise)
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.receive-payment-btn');
          if (!btn) return;
          lastReceiveBtn = btn;
          const invId = btn.getAttribute('data-invoice-id');
          const amt = parseFloat(btn.getAttribute('data-amount') || '0');
          // Prefill modal fields
          document.getElementById('rip-invoice-id').value = invId || '';
          document.getElementById('rip-invoice-id-display').value = invId || '';
          document.getElementById('rip-amount').value = isNaN(amt) ? '' : amt.toFixed(2);
          // Set max to current due
          const ripAmountInput = document.getElementById('rip-amount');
          if (!isNaN(amt) && amt > 0) {
            ripAmountInput.max = amt.toFixed(2);
            const hint = document.getElementById('rip-max-hint');
            if (hint) hint.textContent = 'Max allowed: ' + currency(amt);
          } else {
            ripAmountInput.removeAttribute('max');
            const hint = document.getElementById('rip-max-hint');
            if (hint) hint.textContent = '';
          }
          const today = new Date();
          const yyyy = today.getFullYear();
          const mm = String(today.getMonth() + 1).padStart(2, '0');
          const dd = String(today.getDate()).padStart(2, '0');
          document.getElementById('rip-date').value = `${yyyy}-${mm}-${dd}`;
          document.getElementById('rip-method').value = 'Cash';
          document.getElementById('rip-remarks').value = '';
          const alertBox = document.getElementById('rip-alert');
          alertBox.classList.add('d-none');
          alertBox.textContent = '';
          // Show modal (stacked over the member invoices modal). If Bootstrap JS is available, it will handle backdrops.
          if (window.jQuery && typeof jQuery.fn !== 'undefined' && typeof jQuery.fn.modal === 'function') {
            jQuery('#receiveInvoicePaymentModal').modal('show');
          } else {
            document.getElementById('receiveInvoicePaymentModal').classList.add('show');
            // Minimal fallback: add a backdrop if Bootstrap JS isn't present
            var backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'fallback-modal-backdrop';
            document.body.appendChild(backdrop);
            document.body.classList.add('modal-open');
          }
        });

        document.getElementById('rip-submit').addEventListener('click', function() {
          const invId = document.getElementById('rip-invoice-id').value;
          const amount = parseFloat(document.getElementById('rip-amount').value || '');
          const date = document.getElementById('rip-date').value;
          const method = document.getElementById('rip-method').value;
          const remarks = document.getElementById('rip-remarks').value;

          const alertBox = document.getElementById('rip-alert');
          const submitBtn = document.getElementById('rip-submit');
          alertBox.classList.add('d-none');
          alertBox.textContent = '';

          if (!invId || isNaN(amount) || amount <= 0 || !date) {
            alertBox.className = 'alert alert-danger';
            alertBox.textContent = 'Please provide valid amount and date.';
            alertBox.classList.remove('d-none');
            return;
          }
          // Client-side guard: ensure amount <= max
          const ripMaxAttr = document.getElementById('rip-amount').getAttribute('max');
          if (ripMaxAttr) {
            const maxVal = parseFloat(ripMaxAttr);
            if (!isNaN(maxVal) && amount - maxVal > 1e-6) {
              alertBox.className = 'alert alert-danger';
              alertBox.textContent = 'Amount exceeds due. Max: ' + currency(maxVal);
              alertBox.classList.remove('d-none');
              return;
            }
          }

          submitBtn.disabled = true;
          fetch('<?php echo base_url("anjuman/add_miqaat_invoice_payment"); ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `invoice_id=${encodeURIComponent(invId)}&amount=${encodeURIComponent(amount.toFixed(2))}&payment_date=${encodeURIComponent(date)}&payment_method=${encodeURIComponent(method)}&remarks=${encodeURIComponent(remarks || '')}`
            })
            .then(res => res.json().catch(() => ({
              success: false
            })))
            .then(data => {
              if (data && data.success) {
                alertBox.className = 'alert alert-success';
                alertBox.textContent = 'Payment recorded successfully.';
                alertBox.classList.remove('d-none');

                // Sync UI: update the in-modal row Paid/Due and footer totals
                try {
                  const paidAmount = amount; // amount just posted

                  // 1) Update the clicked invoice row inside the modal
                  if (lastReceiveBtn) {
                    const r = lastReceiveBtn.closest('tr');
                    if (r) {
                      const cells = r.children;
                      // indices with Year column: 5 Amount, 6 Paid, 7 Due
                      const paidCell = cells[6];
                      const dueCell = cells[7];
                      const prevPaid = parseCurrency(paidCell.textContent);
                      const prevDue = parseCurrency(dueCell.textContent);
                      const newPaid = prevPaid + paidAmount;
                      const newDue = Math.max(0, prevDue - paidAmount);
                      paidCell.textContent = currency(newPaid);
                      dueCell.textContent = currency(newDue);
                      dueCell.className = 'text-right ' + (newDue > 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold');
                      // Update the button's data-amount (remaining due)
                      lastReceiveBtn.setAttribute('data-amount', String(newDue));

                      // Flash highlight the updated invoice row
                      r.classList.remove('flash-highlight');
                      // force reflow to restart animation
                      void r.offsetWidth;
                      r.classList.add('flash-highlight');
                    }
                  }

                  // 2) Update modal footer totals (Amount | Paid | Due)
                  const tfoot = document.querySelector('#memberInvoicesModal tfoot tr');
                  if (tfoot) {
                    const cells = tfoot.children;
                    // cells[1] total amount, cells[2] total paid, cells[3] total due
                    const totalPaidPrev = parseCurrency(cells[2].textContent);
                    const totalDuePrev = parseCurrency(cells[3].textContent);
                    const totalPaidNew = totalPaidPrev + paidAmount;
                    const totalDueNew = Math.max(0, totalDuePrev - paidAmount);
                    cells[2].textContent = currency(totalPaidNew);
                    cells[3].textContent = currency(totalDueNew);
                    cells[3].className = 'text-right ' + (totalDueNew > 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold');
                  }

                  // 3) Update the main table row totals (Total Paid | Total Due)
                  if (currentTriggerBtn) {
                    const mainRow = currentTriggerBtn.closest('tr');
                    if (mainRow) {
                      const tds = mainRow.querySelectorAll('td');
                      // indices unchanged in main table (already adjusted earlier): 6 Total Amount, 7 Total Paid, 8 Total Due
                      const mainPaidPrev = parseCurrency(tds[7].textContent);
                      const mainDuePrev = parseCurrency(tds[8].textContent);
                      const mainPaidNew = mainPaidPrev + paidAmount;
                      const mainDueNew = Math.max(0, mainDuePrev - paidAmount);
                      tds[7].textContent = currency(mainPaidNew);
                      tds[8].innerHTML = '<span class="' + (mainDueNew > 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold') + '">' + currency(mainDueNew) + '</span>';

                      // Flash highlight the updated main table row
                      mainRow.classList.remove('flash-highlight');
                      void mainRow.offsetWidth; // reflow
                      mainRow.classList.add('flash-highlight');

                      // 4) Update the invoices data on the button so reopening modal shows updated numbers
                      try {
                        const invsJson = currentTriggerBtn.getAttribute('data-invoices') || '[]';
                        const invs = JSON.parse(invsJson);
                        const invIdStr = String(invId);
                        for (let i = 0; i < invs.length; i++) {
                          if (String(invs[i].invoice_id) === invIdStr) {
                            const prevPaid = parseFloat(invs[i].paid_amount || 0);
                            const prevDue = parseFloat(invs[i].due_amount || 0);
                            invs[i].paid_amount = (prevPaid + paidAmount).toFixed(2);
                            invs[i].due_amount = Math.max(0, prevDue - paidAmount).toFixed(2);
                            break;
                          }
                        }
                        currentTriggerBtn.setAttribute('data-invoices', JSON.stringify(invs));
                      } catch (e) {
                        /* ignore */
                      }
                    }
                  }
                } catch (e) {
                  // Swallow UI sync errors; server has already saved
                }

                // Optionally close after short delay
                setTimeout(() => {
                  if (window.jQuery && typeof jQuery.fn !== 'undefined' && typeof jQuery.fn.modal === 'function') {
                    jQuery('#receiveInvoicePaymentModal').modal('hide');
                  }
                  // Fallback close if Bootstrap JS isn't present
                  else {
                    var modal = document.getElementById('receiveInvoicePaymentModal');
                    modal.classList.remove('show');
                    var fb = document.getElementById('fallback-modal-backdrop');
                    if (fb) {
                      fb.parentNode.removeChild(fb);
                    }
                    document.body.classList.remove('modal-open');
                    // After closing, refresh the page
                    window.location.reload();
                  }
                }, 800);
              } else {
                alertBox.className = 'alert alert-danger';
                alertBox.textContent = (data && data.error) ? data.error : 'Failed to save payment.';
                alertBox.classList.remove('d-none');
              }
            })
            .catch(() => {
              alertBox.className = 'alert alert-danger';
              alertBox.textContent = 'Network error. Please try again.';
              alertBox.classList.remove('d-none');
            })
            .finally(() => {
              submitBtn.disabled = false;
            });
        });

        // Read-only: removed Fala ni Niyaz group edit/delete logic and page refresh hooks
        // ---- Sortable table headers for member payments ----
        (function enableTableSorting() {
          const table = document.getElementById('miqaat-payments-table');
          if (!table) return;
          const headers = table.querySelectorAll('thead th.km-sortable');

          function parseCurrencyValue(text) {
            if (text == null) return 0;
            const cleaned = String(text).replace(/[^0-9.-]/g, '');
            const v = parseFloat(cleaned);
            return isNaN(v) ? 0 : v;
          }
          headers.forEach((th, colIndex) => {
            th.addEventListener('click', () => {
              const sortType = th.getAttribute('data-sort-type') || 'string';
              const currentDir = th.getAttribute('data-sort-dir');
              const newDir = currentDir === 'asc' ? 'desc' : 'asc';
              // reset others
              headers.forEach(h => {
                if (h !== th) {
                  h.removeAttribute('data-sort-dir');
                  const si = h.querySelector('.sort-indicator');
                  if (si) si.textContent = '';
                }
              });
              th.setAttribute('data-sort-dir', newDir);
              const indicator = th.querySelector('.sort-indicator');
              if (indicator) indicator.textContent = newDir === 'asc' ? '▲' : '▼';
              const tbody = table.querySelector('tbody');
              let rows = Array.from(tbody.querySelectorAll('tr'))
                .filter(r => !r.classList.contains('payment-history-row'));
              // Only sort visible rows; keep hidden ones at end (their order preserved)
              const visible = rows.filter(r => r.style.display !== 'none');
              const hidden = rows.filter(r => r.style.display === 'none');
              visible.sort((a, b) => {
                const aCell = a.children[colIndex];
                const bCell = b.children[colIndex];
                let aVal = aCell ? aCell.textContent.trim() : '';
                let bVal = bCell ? bCell.textContent.trim() : '';
                if (sortType === 'number') {
                  const toNum = v => {
                    const n = parseFloat(v.replace(/[^0-9.-]/g, ''));
                    return isNaN(n) ? 0 : n;
                  };
                  aVal = toNum(aVal);
                  bVal = toNum(bVal);
                  return newDir === 'asc' ? aVal - bVal : bVal - aVal;
                } else if (sortType === 'currency') {
                  aVal = parseCurrencyValue(aVal);
                  bVal = parseCurrencyValue(bVal);
                  return newDir === 'asc' ? aVal - bVal : bVal - aVal;
                } else { // string
                  aVal = aVal.toLowerCase();
                  bVal = bVal.toLowerCase();
                  if (aVal === bVal) return 0;
                  return newDir === 'asc' ? (aVal < bVal ? -1 : 1) : (aVal > bVal ? -1 : 1);
                }
              });
              // Re-append visible (sorted) then hidden (unsorted)
              visible.forEach((r, i) => {
                tbody.appendChild(r);
                const first = r.children[0];
                if (first) first.textContent = i + 1;
              });
              hidden.forEach(r => tbody.appendChild(r));
            });
          });
        })();
      })();
    </script>
  <?php endif; ?>
</div>