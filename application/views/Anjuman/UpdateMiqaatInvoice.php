<div class="margintopcontainer pt-3">
  <div class="row px-3">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
        <div class="mb-2">
          <a href="<?php echo base_url("anjuman/fmbniyaz") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <div class="mb-2 ml-auto">
          <?php
          $btnType = isset($miqaat_type) ? (string)$miqaat_type : '';
          $btnYears = isset($hijri_years) && is_array($hijri_years) ? $hijri_years : [];
          $btnYear = isset($selected_year) && $selected_year !== ''
            ? (string)$selected_year
            : (isset($current_hijri_year) && $current_hijri_year !== '' ? (string)$current_hijri_year : (!empty($btnYears) ? (string)$btnYears[0] : ''));
          ?>
          <button id="fala-ni-niyaz-invoices" class="btn btn-primary" data-toggle="modal" data-target="#falaNiyazInvoicesModal">Update Fala Amount for <?php echo htmlspecialchars($btnType); ?> <?php echo htmlspecialchars($btnYear); ?></button>
        </div>
      </div>
    </div>
  </div>
  <div id="miqaat-filters" class="p-3 bg-light border m-3">
    <div class="form-row">
      <div class="col-md-2 mb-2">
        <label for="mf-name" class="mb-1 text-muted">Name or ITS</label>
        <input type="text" id="mf-name" class="form-control form-control-sm" placeholder="Search name or ITS...">
      </div>
      <div class="col-md-2 mb-2">
        <label for="mf-sector" class="mb-1 text-muted">Sector</label>
        <select id="mf-sector" class="form-control form-control-sm">
          <option value="">All Sectors</option>
          <?php if (isset($sectors) && is_array($sectors)): foreach ($sectors as $s): $secVal = isset($s['Sector']) ? $s['Sector'] : (is_string($s) ? $s : '');
              if ($secVal === '') continue; ?>
              <option value="<?php echo htmlspecialchars(strtolower($secVal), ENT_QUOTES); ?>"><?php echo htmlspecialchars($secVal); ?></option>
          <?php endforeach;
          endif; ?>
        </select>
      </div>
      <div class="col-md-2 mb-2">
        <label for="mf-subsector" class="mb-1 text-muted">Sub Sector</label>
        <select id="mf-subsector" class="form-control form-control-sm">
          <option value="">All Sub Sectors</option>
          <?php if (isset($sub_sectors) && is_array($sub_sectors)): foreach ($sub_sectors as $ss): $subVal = isset($ss['Sub_Sector']) ? $ss['Sub_Sector'] : (is_string($ss) ? $ss : '');
              if ($subVal === '') continue; ?>
              <option value="<?php echo htmlspecialchars(strtolower($subVal), ENT_QUOTES); ?>"><?php echo htmlspecialchars($subVal); ?></option>
          <?php endforeach;
          endif; ?>
        </select>
      </div>
      <div class="col-md-2 mb-2">
        <label for="mf-year" class="mb-1 text-muted">Hijri Year</label>
        <?php
        $years = isset($hijri_years) && is_array($hijri_years) ? $hijri_years : [];
        $defaultYear = isset($selected_year) && $selected_year !== ''
          ? $selected_year
          : (isset($current_hijri_year) && $current_hijri_year !== '' ? $current_hijri_year : (!empty($years) ? end($years) : ''));
        if (!empty($years)) reset($years);
        ?>
        <select id="mf-year" class="form-control form-control-sm" data-default-year="<?php echo htmlspecialchars($defaultYear, ENT_QUOTES); ?>">
          <option value="">All Years</option>
          <?php foreach ($years as $y): ?>
            <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>" <?php echo ($defaultYear === $y ? 'selected' : ''); ?>><?php echo htmlspecialchars($y); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2 mb-2 d-flex align-items-end">
        <button type="button" id="mf-clear" class="btn btn-outline-secondary btn-sm w-100">Clear Filters</button>
      </div>
    </div>
  </div>

  <div class="col-12 mb-3">
    <?php
    $displayYear = isset($current_hijri_year) && $current_hijri_year !== ''
      ? $current_hijri_year
      : (isset($hijri_years) && is_array($hijri_years) && !empty($hijri_years) ? $hijri_years[0] : '');
    ?>
    <h4 class="heading text-center mb-0">
      <span class="text-primary"><?php echo isset($miqaat_type) ? $miqaat_type : ''; ?></span>
      Miqaat Invoice
      <?php if ($displayYear) : ?>
        <span id="title-hijri-year" class="text-muted" style="font-size:0.9em;">(Hijri <?php echo htmlspecialchars($displayYear); ?>)</span>
      <?php else: ?>
        <span id="title-hijri-year" class="text-muted" style="font-size:0.9em;"></span>
      <?php endif; ?>
    </h4>
  </div>

  <?php
  $members = [];

  // Debug output removed

  if (isset($member_miqaat_invoices)) {
    if (is_array($member_miqaat_invoices) && isset($member_miqaat_invoices['members']) && is_array($member_miqaat_invoices['members'])) {
      $members = $member_miqaat_invoices['members'];
    } elseif (is_array($member_miqaat_invoices)) {
      $members = $member_miqaat_invoices;
    }
  }

  // Build a flat invoice-wise list (each row = one invoice)
  $invoice_rows = [];
  $grand_total = 0.0;
  $seen_invoice_ids = [];

  if (!function_exists('format_inr_no_decimals')) {
    function format_inr_no_decimals($num)
    {
      if ($num === null || $num === '' || !is_numeric($num)) {
        $num = 0;
      }
      $num = (float)$num;
      $num = round($num);
      $neg = $num < 0;
      if ($neg) $num = abs($num);
      $int = (string)$num;
      if (strlen($int) <= 3) {
        $res = $int;
      } else {
        $last3 = substr($int, -3);
        $rest = substr($int, 0, -3);
        $rest = preg_replace('/\B(?=(?:\d{2})+(?!\d))/', ',', $rest);
        $res = $rest . ',' . $last3;
      }
      return ($neg ? '-' : '') . $res;
    }
  }

  if (!empty($members)) {
    foreach ($members as $m) {
      $its = isset($m['ITS_ID']) ? (string)$m['ITS_ID'] : '';
      $name = isset($m['Full_Name']) ? (string)$m['Full_Name'] : '';
      $sector = isset($m['Sector']) ? (string)$m['Sector'] : (isset($m['sector']) ? (string)$m['sector'] : '');
      $subSector = isset($m['Sub_Sector']) ? (string)$m['Sub_Sector'] : (isset($m['sub_sector']) ? (string)$m['sub_sector'] : '');

      $invoices = isset($m['miqaat_invoices']) && is_array($m['miqaat_invoices']) ? $m['miqaat_invoices'] : [];
      foreach ($invoices as $inv) {
        $invoiceId = isset($inv['invoice_id']) ? (string)$inv['invoice_id'] : '';
        if ($invoiceId !== '' && isset($seen_invoice_ids[$invoiceId])) {
          continue;
        }
        if ($invoiceId !== '') {
          $seen_invoice_ids[$invoiceId] = true;
        }

        $amount = 0.0;
        if (isset($inv['amount']) && $inv['amount'] !== '' && $inv['amount'] !== null) {
          $amount = (float)$inv['amount'];
        } elseif (isset($inv['invoice_amount']) && $inv['invoice_amount'] !== '' && $inv['invoice_amount'] !== null) {
          $amount = (float)$inv['invoice_amount'];
        }

        $displayDateIso = '';
        if (!empty($inv['miqaat_date'])) {
          $displayDateIso = (string)$inv['miqaat_date'];
        } elseif (!empty($inv['invoice_date'])) {
          $displayDateIso = (string)$inv['invoice_date'];
        }

        $invoiceDateIso = '';
        if (!empty($inv['invoice_date'])) {
          $invoiceDateIso = (string)$inv['invoice_date'];
        }

        $miqaatId = isset($inv['miqaat_id']) ? (string)$inv['miqaat_id'] : '';
        $razaId = isset($inv['raza_id']) ? (string)$inv['raza_id'] : '';
        $miqaatName = isset($inv['miqaat_name']) ? (string)$inv['miqaat_name'] : '';
        $assignedTo = isset($inv['assigned_to']) ? (string)$inv['assigned_to'] : '';
        if ($assignedTo === '' && $miqaatId === '') {
          $assignedTo = 'Fala ni Niyaz';
        }

        $individualCount = 0;
        if (isset($inv['individual_count']) && $inv['individual_count'] !== '' && $inv['individual_count'] !== null) {
          $individualCount = (int)$inv['individual_count'];
        }

        $hijriDate = isset($inv['hijri_date']) ? (string)$inv['hijri_date'] : '';
        $invYear = isset($inv['invoice_year']) ? (string)$inv['invoice_year'] : '';
        $desc = isset($inv['description']) ? (string)$inv['description'] : '';

        $invoice_rows[] = [
          'invoice_id' => $invoiceId,
          'its_id' => $its,
          'full_name' => $name,
          'sector' => $sector,
          'sub_sector' => $subSector,
          'display_date_iso' => $displayDateIso,
          'invoice_date_iso' => $invoiceDateIso,
          'hijri_date' => $hijriDate,
          'invoice_year' => $invYear,
          'miqaat_id' => $miqaatId,
          'raza_id' => $razaId,
          'miqaat_name' => $miqaatName,
          'assigned_to' => $assignedTo,
          'individual_count' => $individualCount,
          'details' => trim($name . ($its !== '' ? (' (' . $its . ')') : '')),
          'amount' => $amount,
          'description' => $desc,
        ];
        $grand_total += (float)$amount;
      }
    }
  }

  // Extract miqaats list for Fala ni Niyaz modal (to display miqaats, not members)
  $miqaats_list = [];
  if (
    isset($member_miqaat_invoices)
    && is_array($member_miqaat_invoices)
    && isset($member_miqaat_invoices['miqaats'])
    && is_array($member_miqaat_invoices['miqaats'])
  ) {
    $miqaats_list = $member_miqaat_invoices['miqaats'];

    // Show ONLY Fala ni Niyaz invoice groups in the popup.
    // In our data shape, miqaat invoice groups have group_key like "M#...".
    // Fala ni Niyaz invoice groups use a non-M# group_key and have miqaat_id null.
    $miqaats_list = array_values(array_filter($miqaats_list, function ($m) {
      $gk = isset($m['group_key']) ? (string)$m['group_key'] : '';
      $miqaatId = isset($m['miqaat_id']) ? $m['miqaat_id'] : null;
      $name = isset($m['miqaat_name']) ? (string)$m['miqaat_name'] : '';

      // Primary rule: exclude real miqaats (group_key starts with M#)
      if ($gk !== '' && strpos($gk, 'M#') === 0) {
        return false;
      }

      // Secondary: allow groups that clearly look like Fala (miqaat_id empty or name matches)
      if ($miqaatId === null || $miqaatId === '') {
        return true;
      }
      if ($name !== '' && stripos($name, 'Fala ni Niyaz') !== false) {
        return true;
      }

      return false;
    }));
  }
  ?>

  <?php
  // Format INR without decimals for the summary (fallback if helper not loaded)
  $gt = isset($grand_total) ? (float)$grand_total : 0.0;
  $gtRounded = round($gt);
  $gtStr = (string)abs((int)$gtRounded);
  if (strlen($gtStr) > 3) {
    $last3 = substr($gtStr, -3);
    $rest = substr($gtStr, 0, -3);
    $rest = preg_replace('/\B(?=(?:\d{2})+(?!\d))/', ',', $rest);
    $gtStr = $rest . ',' . $last3;
  }
  $gtStr = ($gtRounded < 0 ? '-' : '') . $gtStr;
  ?>

  <?php if (empty($invoice_rows)) : ?>
    <div class="col-3 m-auto alert alert-info">No invoices found.</div>
  <?php else : ?>
    <style>
      #miqaat-filters label {
        font-weight: 600;
        font-size: 12px;
      }

      #miqaat-filters input,
      #miqaat-filters select {
        font-size: 13px;
      }

      /* Prevent long text from forcing table overflow */
      .km-table-fixed {
        table-layout: fixed;
        width: 100%;
      }

      .km-table-fixed th,
      .km-table-fixed td {
        overflow-wrap: anywhere;
      }

      .km-cell-nowrap {
        white-space: nowrap;
      }

      .km-cell-wrap {
        white-space: normal;
      }

      .km-actions {
        white-space: nowrap;
      }

      th.km-sortable {
        cursor: pointer;
        user-select: none;
        white-space: nowrap;
      }

      th.km-sortable .sort-indicator {
        font-size: 11px;
        margin-left: 4px;
        opacity: 0.8;
      }
    </style>
    <div class="mb-3">
      <div class="d-flex justify-content-between align-items-center p-2 col-4 mx-auto" style="background:#f8f9fa; border:1px solid #e5e7eb; border-radius:10px;">
        <div>
          <b>Total Invoice Amount:</b>
          <span id="miqaat-total-amount" class="text-success">₹<?php echo $gtStr; ?></span>
        </div>
        <div class="text-dark" style="font-size:16px;">
          Invoices shown: <span id="miqaat-invoices-count"><?php echo isset($invoice_rows) && is_array($invoice_rows) ? count($invoice_rows) : 0; ?></span>
        </div>
      </div>
    </div>

    <div class="col-12 table-responsive">
      <table class="table table-bordered table-striped km-table-fixed" id="miqaat-invoice-table">
        <colgroup>
          <col style="width: 44px;">
          <col style="width: 128px;">
          <col style="width: 140px;">
          <col style="width: 92px;">
          <col style="width: 92px;">
          <col style="width: 96px;">
          <col style="width: 220px;">
          <col style="width: 140px;">
          <col style="width: 190px;">
          <col style="width: 128px;">
          <col style="width: 110px;">
          <col style="width: 96px;">
        </colgroup>
        <thead class="thead-dark table-dark">
          <tr>
            <th class="km-sortable" data-sort-key="index" data-sort-type="number"># <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="date" data-sort-type="date">Date <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="hijri" data-sort-type="string">Hijri Date <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="miqaatId" data-sort-type="number">Miqaat ID <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="razaId" data-sort-type="number">Raza ID <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="invoiceId" data-sort-type="number">Invoice ID <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="miqaatName" data-sort-type="string">Miqaat Name <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="assignedTo" data-sort-type="string">Assigned To <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="details" data-sort-type="string">Details <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="invoiceDate" data-sort-type="date">Invoice Date <span class="sort-indicator"></span></th>
            <th class="km-sortable text-right" data-sort-key="amount" data-sort-type="number">Amount <span class="sort-indicator"></span></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr id="miqaat-invoice-no-matches" style="display:none;">
            <td colspan="12" class="text-center text-muted">No matching rows</td>
          </tr>
          <?php foreach ($invoice_rows as $i => $r) : ?>
            <?php
            $greg = isset($r['display_date_iso']) ? (string)$r['display_date_iso'] : '';
            $gregFmt = '-';
            if ($greg !== '') {
              $t = strtotime($greg);
              if ($t) $gregFmt = date('d F Y', $t);
            }

            $invIso = isset($r['invoice_date_iso']) ? (string)$r['invoice_date_iso'] : '';
            $invFmt = '-';
            if ($invIso !== '') {
              $ti = strtotime($invIso);
              if ($ti) $invFmt = date('d F Y', $ti);
            }
            $miqaatId = isset($r['miqaat_id']) ? (string)$r['miqaat_id'] : '';
            $razaId = isset($r['raza_id']) ? (string)$r['raza_id'] : '';

            $assignedLabel = isset($r['assigned_to']) ? (string)$r['assigned_to'] : '';
            $assignedKey = strtolower(trim($assignedLabel));
            $individualCountForSort = 0;
            if ($assignedKey === 'individual') {
              $cnt = isset($r['individual_count']) ? (int)$r['individual_count'] : 0;
              $assignedLabel = $assignedLabel . ' (' . $cnt . ')';
              $individualCountForSort = $cnt;
            }
            ?>
            <tr class="miqaat-invoice-row"
              data-name="<?php echo htmlspecialchars(strtolower($r['full_name']), ENT_QUOTES); ?>"
              data-its="<?php echo htmlspecialchars(strtolower($r['its_id']), ENT_QUOTES); ?>"
              data-sector="<?php echo htmlspecialchars(strtolower($r['sector']), ENT_QUOTES); ?>"
              data-subsector="<?php echo htmlspecialchars(strtolower($r['sub_sector']), ENT_QUOTES); ?>"
              data-year="<?php echo htmlspecialchars($r['invoice_year'], ENT_QUOTES); ?>"
              data-greg-date="<?php echo htmlspecialchars($greg, ENT_QUOTES); ?>"
              data-invoice-date="<?php echo htmlspecialchars($invIso, ENT_QUOTES); ?>"
              data-hijri-date="<?php echo htmlspecialchars(strtolower($r['hijri_date']), ENT_QUOTES); ?>"
              data-miqaat-id="<?php echo htmlspecialchars($miqaatId, ENT_QUOTES); ?>"
              data-raza-id="<?php echo htmlspecialchars($razaId, ENT_QUOTES); ?>"
              data-miqaat-name="<?php echo htmlspecialchars(strtolower($r['miqaat_name']), ENT_QUOTES); ?>"
              data-assigned-to="<?php echo htmlspecialchars(strtolower($assignedLabel), ENT_QUOTES); ?>"
              data-individual-count="<?php echo htmlspecialchars((string)$individualCountForSort, ENT_QUOTES); ?>"
              data-amount="<?php echo htmlspecialchars((string)$r['amount'], ENT_QUOTES); ?>"
              data-description="<?php echo htmlspecialchars((string)($r['description'] ?? ''), ENT_QUOTES); ?>"
              data-invoice-id="<?php echo htmlspecialchars($r['invoice_id'], ENT_QUOTES); ?>">
              <td class="km-cell-nowrap"><b><?php echo $i + 1; ?></b></td>
              <td class="km-cell-wrap"><?php echo htmlspecialchars($gregFmt); ?></td>
              <td class="km-cell-wrap"><?php echo htmlspecialchars($r['hijri_date']); ?></td>
              <td class="km-cell-wrap"><?php echo $miqaatId !== '' ? ('M#' . htmlspecialchars($miqaatId)) : '-'; ?></td>
              <td class="km-cell-wrap"><?php echo $razaId !== '' ? ('R#' . htmlspecialchars($razaId)) : '-'; ?></td>
              <td class="km-cell-wrap"><?php echo $r['invoice_id'] !== '' ? ('I#' . htmlspecialchars((string)$r['invoice_id'])) : '-'; ?></td>
              <td class="km-cell-wrap"><b><?php echo htmlspecialchars($r['miqaat_name']); ?></b></td>
              <td class="km-cell-wrap"><b><?php echo htmlspecialchars($assignedLabel); ?></b></td>
              <td class="km-cell-wrap"><?php echo htmlspecialchars($r['details']); ?></td>
              <td class="invoice-date-cell km-cell-wrap"><?php echo htmlspecialchars($invFmt); ?></td>
              <td class="text-right invoice-amount-cell">₹<?php echo htmlspecialchars(format_inr_no_decimals($r['amount'])); ?></td>
              <td class="text-center km-actions">
                <div class="btn-group-vertical btn-group-sm" role="group" aria-label="Actions">
                  <button type="button" class="btn btn-outline-primary invoice-edit-btn" data-invoice-id="<?php echo htmlspecialchars($r['invoice_id'], ENT_QUOTES); ?>"><i class="fa-solid fa-pencil"></i></button>
                  <button type="button" class="btn btn-outline-danger invoice-delete-btn" data-invoice-id="<?php echo htmlspecialchars($r['invoice_id'], ENT_QUOTES); ?>"><i class="fa-solid fa-trash"></i></button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Edit Invoice Modal -->
    <div class="modal fade" id="editInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editInvoiceModalLabel">Edit Invoice</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="editInvoiceForm">
            <div class="modal-body">
              <input type="hidden" id="edit-invoice-id" value="">

              <div class="mb-2">
                <label class="form-label mb-0"><b>Miqaat Details:</b></label>
                <div id="edit-invoice-details" class="form-control-plaintext"></div>
              </div>
              <hr>

              <div class="mb-2">
                <label class="mb-1 text-muted" for="edit-amount">Amount</label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text">₹</span></div>
                  <input type="number" class="form-control" id="edit-amount" min="0" step="1" required>
                </div>
              </div>

              <div class="mb-2">
                <label class="mb-1 text-muted" for="edit-description">Description</label>
                <textarea class="form-control" id="edit-description" rows="2"></textarea>
              </div>

              <div class="mb-2">
                <label class="mb-1 text-muted" for="edit-invoice-date">Invoice Date</label>
                <input type="date" class="form-control" id="edit-invoice-date" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary" id="edit-invoice-save">Save</button>
            </div>
          </form>
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

    <!-- Fala ni Niyaz Invoices Modal -->
    <div class="modal fade" id="falaNiyazInvoicesModal" tabindex="-1" role="dialog" aria-labelledby="falaNiyazInvoicesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="falaNiyazInvoicesModalLabel">Invoices Generated for Miqaats</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="fala-modal-table-wrapper" class="table-responsive"></div>
            <!-- Loading overlay for bulk operations -->
            <div id="fala-loading-overlay" class="fala-loading-overlay d-none">
              <div class="fala-loading-inner text-center">
                <div class="mb-2"><i class="fa-solid fa-circle-notch fa-spin fa-2x"></i></div>
                <div class="message">Please wait...</div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Filter logic with default year pre-selection
      (function() {
        function toLower(s) {
          return (s || '').toString().trim().toLowerCase();
        }

        function escapeHtml(str) {
          const s = (str === null || str === undefined) ? '' : String(str);
          return s
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
        }

        function applyFilters() {
          const nameVal = toLower(document.getElementById('mf-name')?.value);
          const sectorVal = toLower(document.getElementById('mf-sector')?.value);
          const subVal = toLower(document.getElementById('mf-subsector')?.value);
          const yearSelect = document.getElementById('mf-year');
          const yearRaw = (yearSelect && yearSelect.value ? yearSelect.value : '').trim();
          // Update title year display when filter changes (display-only; data already server-filtered)
          const titleYearEl = document.getElementById('title-hijri-year');
          if (titleYearEl) {
            if (yearRaw) {
              titleYearEl.textContent = `(Hijri ${yearRaw})`;
            } else {
              const defYear = (yearSelect && yearSelect.getAttribute('data-default-year')) ? yearSelect.getAttribute('data-default-year') : '';
              titleYearEl.textContent = defYear ? `(Hijri ${defYear})` : '';
            }
          }
          const rows = Array.from(document.querySelectorAll('#miqaat-invoice-table tbody tr.miqaat-invoice-row'));
          const noMatchRow = document.getElementById('miqaat-invoice-no-matches');
          let index = 1;
          let visibleInvoices = 0;
          let visibleTotal = 0;

          // Local helper for INR grouping (no decimals)
          function formatRupees(n) {
            if (n === undefined || n === null) n = 0;
            let num = parseFloat(n);
            if (isNaN(num)) num = 0;
            num = Math.round(num);
            const neg = num < 0;
            if (neg) num = Math.abs(num);
            let s = String(num);
            if (s.length > 3) {
              const last3 = s.slice(-3);
              let rest = s.slice(0, -3);
              rest = rest.replace(/\B(?=(?:\d{2})+(?!\d))/g, ',');
              s = rest + ',' + last3;
            }
            return (neg ? '-' : '') + '₹' + s;
          }

          rows.forEach(function(r) {
            const rName = r.getAttribute('data-name') || '';
            const rIts = r.getAttribute('data-its') || '';
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
            r.style.display = '';
            const firstCell = r.querySelector('td');
            if (firstCell) firstCell.textContent = index++;
            visibleInvoices++;
            visibleTotal += parseFloat(r.getAttribute('data-amount') || '0') || 0;
          });

          if (noMatchRow) {
            noMatchRow.style.display = visibleInvoices === 0 ? '' : 'none';
          }

          const totalEl = document.getElementById('miqaat-total-amount');
          if (totalEl) totalEl.textContent = formatRupees(visibleTotal);
          const cntEl = document.getElementById('miqaat-invoices-count');
          if (cntEl) cntEl.textContent = String(visibleInvoices);
        }
        const nameInput = document.getElementById('mf-name');
        const sectorSel = document.getElementById('mf-sector');
        const subSel = document.getElementById('mf-subsector');
        const yearSel = document.getElementById('mf-year');
        if (nameInput) nameInput.addEventListener('input', applyFilters);
        if (sectorSel) sectorSel.addEventListener('change', applyFilters);
        if (subSel) subSel.addEventListener('change', applyFilters);
        if (yearSel) yearSel.addEventListener('change', function() {
          const y = (yearSel.value || '').trim();
          const params = new URLSearchParams(window.location.search);
          if (y) {
            params.set('year', y);
          } else {
            params.delete('year');
          }
          // Preserve existing miqaat_type param
          const url = window.location.pathname + '?' + params.toString();
          window.location.replace(url);
        });
        const clearBtn = document.getElementById('mf-clear');
        if (clearBtn) {
          clearBtn.addEventListener('click', function() {
            // Reset inputs
            nameInput.value = '';
            sectorSel.value = '';
            subSel.value = '';
            const hadYear = (yearSel && yearSel.value);
            if (yearSel) yearSel.value = '';

            // If the page is server-scoped by a `year` query param (or user had selected a year),
            // remove the `year` param and reload so server returns the default dataset.
            const params = new URLSearchParams(window.location.search);
            if (params.has('year') || hadYear) {
              params.delete('year');
              const q = params.toString();
              const url = window.location.pathname + (q ? ('?' + q) : '');
              window.location.replace(url);
              return;
            }
            applyFilters();
          });
        }

        // Initial paint
        applyFilters();
      })();

      // Sorting + Edit/Delete for invoice-wise table
      (function() {
        const UPDATE_URL = '<?php echo base_url("anjuman/updateMiqaatInvoiceAmount"); ?>';
        const DELETE_URL = '<?php echo base_url("anjuman/deleteMiqaatInvoice"); ?>';

        function escapeHtml(str) {
          const s = (str === null || str === undefined) ? '' : String(str);
          return s
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
        }

        function parseNumber(text) {
          if (text == null) return NaN;
          const m = String(text).match(/\d+(?:\.\d+)?/);
          return m ? parseFloat(m[0]) : NaN;
        }

        function getSortValue(tr, key, type) {
          if (!tr) return '';
          if (key === 'index') {
            const firstCellText = tr.querySelector('td') ? tr.querySelector('td').textContent : '';
            return parseNumber(firstCellText);
          }
          if (key === 'date') {
            const iso = tr.getAttribute('data-greg-date') || '';
            const t = Date.parse(iso.replace(/-/g, '/'));
            return isNaN(t) ? 0 : t;
          }
          if (key === 'hijri') return (tr.getAttribute('data-hijri-date') || '').toString().toLowerCase();
          if (key === 'miqaatId') return parseNumber(tr.getAttribute('data-miqaat-id'));
          if (key === 'razaId') return parseNumber(tr.getAttribute('data-raza-id'));
          if (key === 'invoiceId') return parseNumber(tr.getAttribute('data-invoice-id'));
          if (key === 'miqaatName') return (tr.getAttribute('data-miqaat-name') || '').toString().toLowerCase();
          if (key === 'assignedTo') {
            const raw = (tr.getAttribute('data-assigned-to') || '').toString().trim().toLowerCase();
            let label = raw;
            if (!label) {
              const cells = tr.querySelectorAll('td');
              // Assigned To column index in invoice table: 7
              const cell = cells && cells.length >= 8 ? cells[7] : null;
              label = (cell ? cell.textContent : '').toString().trim().toLowerCase();
            }

            const type = (label.split('(')[0] || '').trim();
            const typeKey = type || label;

            // Special-case: for Individual (N), sort by N within the type
            if (typeKey === 'individual') {
              let cnt = parseInt((tr.getAttribute('data-individual-count') || '').toString(), 10);
              if (isNaN(cnt)) {
                const m = label.match(/\((\d+)\)/);
                cnt = m ? parseInt(m[1], 10) : 0;
              }
              const padded = ('000000' + String(isNaN(cnt) ? 0 : cnt)).slice(-6);
              return 'individual|' + padded;
            }

            return typeKey + '|';
          }
          if (key === 'invoiceDate') {
            const iso = tr.getAttribute('data-invoice-date') || '';
            const t = Date.parse(iso.replace(/-/g, '/'));
            return isNaN(t) ? 0 : t;
          }
          if (key === 'amount') {
            return parseNumber(tr.getAttribute('data-amount'));
          }
          if (key === 'details') {
            const cells = tr.querySelectorAll('td');
            const detailsCell = cells && cells.length >= 9 ? cells[8] : null;
            return (detailsCell ? detailsCell.textContent : '').toString().trim().toLowerCase();
          }
          return '';
        }

        function updateIndicators(table, activeTh, dir) {
          table.querySelectorAll('thead th.km-sortable').forEach(function(th) {
            const ind = th.querySelector('.sort-indicator');
            if (th === activeTh) {
              th.setAttribute('data-sort-dir', dir);
              if (ind) ind.textContent = (dir === 'asc') ? '▲' : '▼';
            } else {
              th.removeAttribute('data-sort-dir');
              if (ind) ind.textContent = '↕';
            }
          });
        }

        function sortTable(key, type, dir) {
          const table = document.getElementById('miqaat-invoice-table');
          if (!table) return;
          const tbody = table.querySelector('tbody');
          if (!tbody) return;
          const noMatchRow = document.getElementById('miqaat-invoice-no-matches');
          const rows = Array.from(tbody.querySelectorAll('tr.miqaat-invoice-row'));

          rows.sort(function(a, b) {
            const va = getSortValue(a, key, type);
            const vb = getSortValue(b, key, type);
            let cmp = 0;
            if (type === 'number' || type === 'date') {
              const na = (typeof va === 'number' && !isNaN(va)) ? va : -Infinity;
              const nb = (typeof vb === 'number' && !isNaN(vb)) ? vb : -Infinity;
              cmp = na === nb ? 0 : (na < nb ? -1 : 1);
            } else {
              cmp = String(va).localeCompare(String(vb));
            }
            return (dir === 'asc') ? cmp : -cmp;
          });

          if (noMatchRow && noMatchRow.parentElement === tbody) {
            tbody.appendChild(noMatchRow);
          }
          rows.forEach(function(r) {
            tbody.appendChild(r);
          });
        }

        document.addEventListener('DOMContentLoaded', function() {
          const table = document.getElementById('miqaat-invoice-table');
          if (table) {
            table.querySelectorAll('thead th.km-sortable .sort-indicator').forEach(function(ind) {
              if (!ind.textContent || ind.textContent.trim() === '') ind.textContent = '↕';
            });
            table.querySelectorAll('thead th.km-sortable').forEach(function(th) {
              th.addEventListener('click', function() {
                const key = th.getAttribute('data-sort-key') || '';
                const type = th.getAttribute('data-sort-type') || 'string';
                const current = th.getAttribute('data-sort-dir');
                const dir = (current === 'asc') ? 'desc' : 'asc';
                updateIndicators(table, th, dir);
                sortTable(key, type, dir);
              });
            });
          }

          // Edit
          $(document).on('click', '.invoice-edit-btn', function() {
            const invoiceId = $(this).attr('data-invoice-id') || '';
            if (!invoiceId) return;
            const tr = document.querySelector('tr.miqaat-invoice-row[data-invoice-id="' + invoiceId + '"]');
            const amount = tr ? (tr.getAttribute('data-amount') || '0') : '0';
            const description = tr ? (tr.getAttribute('data-description') || '') : '';
            const invoiceDateIso = tr ? (tr.getAttribute('data-invoice-date') || '') : '';

            // Prefer display strings from table cells (preserves formatting)
            let miqaatIdText = '-';
            let razaIdText = '-';
            let miqaatNameText = '-';
            let dateText = '-';
            let hijriText = '-';
            let assignedText = '-';
            let assignmentDetailsText = '-';
            if (tr) {
              const tds = tr.querySelectorAll('td');
              if (tds && tds.length >= 11) {
                dateText = (tds[1].textContent || '-').trim();
                hijriText = (tds[2].textContent || '-').trim();
                miqaatIdText = (tds[3].textContent || '-').trim();
                razaIdText = (tds[4].textContent || '-').trim();
                // tds[5] is Invoice ID
                miqaatNameText = (tds[6].textContent || '-').trim();
                assignedText = (tds[7].textContent || '-').trim();
                assignmentDetailsText = (tds[8].textContent || '-').trim();
              }
            }

            const detailsHtml = `
              <div><b>Miqaat ID:</b> ${escapeHtml(miqaatIdText)}</div>
              <div><b>Raza ID:</b> ${escapeHtml(razaIdText)}</div>
              <div><b>Miqaat Name:</b> ${escapeHtml(miqaatNameText)}</div>
              <div><b>Date:</b> ${escapeHtml(dateText)}</div>
              <div><b>Hijri:</b> ${escapeHtml(hijriText)}</div>
              <div><b>Assigned:</b> ${escapeHtml(assignedText)}</div>
              <div><b>Assignment Details:</b> ${escapeHtml(assignmentDetailsText)}</div>
            `;

            $('#edit-invoice-id').val(invoiceId);
            $('#edit-amount').val(Math.round(parseFloat(amount) || 0));
            $('#edit-description').val(description);
            $('#edit-invoice-date').val(invoiceDateIso);
            $('#edit-invoice-details').html(detailsHtml);
            $('#editInvoiceModal').modal('show');
          });

          $('#editInvoiceForm').on('submit', function(e) {
            e.preventDefault();
            const invoiceId = ($('#edit-invoice-id').val() || '').trim();
            const amount = ($('#edit-amount').val() || '').trim();
            const description = ($('#edit-description').val() || '').trim();
            const invoiceDate = ($('#edit-invoice-date').val() || '').trim();
            if (!invoiceId) return;

            $('#edit-invoice-save').prop('disabled', true);
            fetch(UPDATE_URL, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `invoice_id=${encodeURIComponent(invoiceId)}&amount=${encodeURIComponent(amount)}&description=${encodeURIComponent(description)}&invoice_date=${encodeURIComponent(invoiceDate)}`
              })
              .then(res => res.json().catch(() => ({})))
              .then(data => {
                if (!data || data.status !== true) throw new Error('Update failed');
                const tr = document.querySelector('tr.miqaat-invoice-row[data-invoice-id="' + invoiceId + '"]');
                if (tr) {
                  const newAmt = String(parseFloat(amount) || 0);
                  tr.setAttribute('data-amount', newAmt);
                  tr.setAttribute('data-description', description);
                  if (invoiceDate) {
                    tr.setAttribute('data-invoice-date', invoiceDate);
                    const invCell = tr.querySelector('td.invoice-date-cell');
                    if (invCell) {
                      const d = new Date(invoiceDate + 'T00:00:00');
                      if (!isNaN(d.getTime())) {
                        const day = String(d.getDate()).padStart(2, '0');
                        const month = d.toLocaleString('en-US', {
                          month: 'long'
                        });
                        const year = String(d.getFullYear());
                        invCell.textContent = `${day} ${month} ${year}`;
                      } else {
                        invCell.textContent = invoiceDate;
                      }
                    }
                  }
                  const amtCell = tr.querySelector('td.invoice-amount-cell');
                  if (amtCell) {
                    const rounded = Math.round(parseFloat(newAmt) || 0);
                    const neg = rounded < 0;
                    let n = Math.abs(rounded);
                    let s = String(n);
                    if (s.length > 3) {
                      const last3 = s.slice(-3);
                      let rest = s.slice(0, -3);
                      rest = rest.replace(/\B(?=(?:\d{2})+(?!\d))/g, ',');
                      s = rest + ',' + last3;
                    }
                    amtCell.textContent = '₹' + (neg ? '-' : '') + s;
                  }
                }
                $('#editInvoiceModal').modal('hide');
                // Recompute totals via current filters
                const nameEl = document.getElementById('mf-name');
                if (nameEl) nameEl.dispatchEvent(new Event('input'));
              })
              .catch(() => {
                alert('Update failed. Please try again.');
              })
              .finally(() => {
                $('#edit-invoice-save').prop('disabled', false);
              });
          });

          // Delete
          $(document).on('click', '.invoice-delete-btn', function() {
            const invoiceId = $(this).attr('data-invoice-id') || '';
            if (!invoiceId) return;
            if (!confirm('Are you sure you want to delete this invoice?')) return;

            const btn = this;
            btn.disabled = true;
            fetch(DELETE_URL, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `invoice_id=${encodeURIComponent(invoiceId)}`
              })
              .then(res => res.json().catch(() => ({})))
              .then(data => {
                if (!data || data.status !== true) throw new Error('Delete failed');
                const tr = document.querySelector('tr.miqaat-invoice-row[data-invoice-id="' + invoiceId + '"]');
                if (tr && tr.parentNode) tr.parentNode.removeChild(tr);
                const nameEl = document.getElementById('mf-name');
                if (nameEl) nameEl.dispatchEvent(new Event('input'));
              })
              .catch(() => {
                alert('Delete failed. Please try again.');
              })
              .finally(() => {
                btn.disabled = false;
              });
          });
        });
      })();

      // Styles for loading overlay (scoped to Fala modal)
      (function addFalaOverlayStyles() {
        try {
          const tag = document.createElement('style');
          tag.textContent = `
            #falaNiyazInvoicesModal .modal-body { position: relative; }
            .fala-loading-overlay { position: absolute; left:0; top:0; right:0; bottom:0; background: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center; z-index: 1050; }
          `;
          document.head.appendChild(tag);
        } catch (e) {
          /* no-op */
        }
      })();

      // Provide miqaats array for Fala Ni Niyaz modal (we will render miqaats, not member invoices)
      let FALA_MIQAATS = <?php echo json_encode($miqaats_list); ?>;

      (function() {
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
          let num = parseFloat(n);
          if (isNaN(num)) num = 0;
          // Round to nearest rupee
          num = Math.round(num);
          const neg = num < 0;
          if (neg) num = Math.abs(num);
          let intPart = String(num);
          if (intPart.length > 3) {
            const last3 = intPart.slice(-3);
            let rest = intPart.slice(0, -3);
            rest = rest.replace(/\B(?=(?:\d{2})+(?!\d))/g, ',');
            intPart = rest + ',' + last3;
          }
          return (neg ? '-' : '') + '₹' + intPart;
        }

        function buildTable(invoices, memberITS, memberSector, memberSubSector) {
          if (!invoices || !invoices.length) {
            return '<div class="alert alert-info">No invoices for this member.</div>';
          }
          let total = 0;
          let rows = invoices.map(inv => {
            const rawAmt = (inv.amount !== undefined && inv.amount !== null && inv.amount !== '') ? inv.amount : ((inv.invoice_amount !== undefined && inv.invoice_amount !== null && inv.invoice_amount !== '') ? inv.invoice_amount : 0);
            const amount = parseFloat(rawAmt || 0);
            total += amount;
            return `
              <tr data-invoice-id="${inv.invoice_id ? inv.invoice_id : ''}">
                <td>${inv.invoice_id ? inv.invoice_id : ''}</td>
                <td>${memberITS ? memberITS : ''}</td>
                <td>${inv.miqaat_name ? inv.miqaat_name : ''}</td>
                <td>${inv.miqaat_id ? 'M#' + inv.miqaat_id : ''}</td>
                <td>${memberSector ? memberSector : ''}</td>
                <td>${memberSubSector ? memberSubSector : ''}</td>
                <td>${formatDate(inv.invoice_date)}</td>
                <td class="text-right align-middle" data-invoice-id="${inv.invoice_id}" data-amount="${amount}">
                    <div class="amount-view d-flex align-items-center justify-content-end">
                      <span class="amount-text mr-2">${currency(amount)}</span>
                      <button type="button" class="btn btn-link btn-sm edit-amount"><i class="fa-solid fa-pencil"></i></button>
                    </div>
                    <div class="amount-edit d-none">
                      <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                          <span class="input-group-text">₹</span>
                        </div>
                        <input type="number" class="form-control amount-input" value="${Math.round(amount)}" step="1" min="0">
                      </div>
                      <div class="mt-2 text-right">
                        <button type="button" class="btn btn-sm btn-primary save-amount">Save</button>
                        <button type="button" class="btn btn-sm btn-secondary cancel-amount">Cancel</button>
                      </div>
                    </div>
                  </td>
                  <td>${inv.description ? inv.description : ''}</td>
                  <td class="text-center align-middle">
                    <button type="button" class="btn btn-sm btn-outline-danger delete-invoice"><i class="fa-solid fa-trash"></i></button>
                  </td>
              </tr>
            `;
          }).join('');
          return `
            <table class="table table-striped table-bordered">
              <thead class="thead-light">
                <tr>
                  <th>Invoice ID</th>
                  <th>ITS ID</th>
                  <th>Miqaat</th>
                  <th>Miqaat ID</th>
                  <th>Sector</th>
                  <th>Sub Sector</th>
                  <th>Invoice Date</th>
                  <th class="text-right">Amount</th>
                  <th>Description</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                ${rows}
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="5" class="text-right">Total</th>
                  <th class="text-right" id="modal-total-amount">${currency(total)}</th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          `;
        }

        const UPDATE_URL = '<?php echo base_url("anjuman/updateMiqaatInvoiceAmount"); ?>';
        const DELETE_URL = '<?php echo base_url("anjuman/deleteMiqaatInvoice"); ?>';
        let currentTriggerBtn = null;

        function recalcModalTotal() {
          const wrapper = document.getElementById('miqaat-modal-table-wrapper');
          const amountTds = wrapper.querySelectorAll('td[data-amount]');
          let total = 0;
          amountTds.forEach(td => {
            total += parseFloat(td.getAttribute('data-amount')) || 0;
          });
          const totalEl = document.getElementById('modal-total-amount');
          if (totalEl) totalEl.textContent = currency(total);
        }

        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.view-invoices-btn');
          if (!btn) return;
          currentTriggerBtn = btn;
          const its = btn.getAttribute('data-its') || '';
          const name = btn.getAttribute('data-name') || '';
          const memberSector = btn.getAttribute('data-sector') || '';
          const memberSubSector = btn.getAttribute('data-subsector') || '';
          let invoices = [];
          try {
            invoices = JSON.parse(btn.getAttribute('data-invoices') || '[]');
          } catch (err) {
            invoices = [];
          }
          // Deduplicate by invoice_id to avoid duplicate rows
          (function dedupe() {
            const seen = new Set();
            invoices = invoices.filter(inv => {
              const id = String(inv.invoice_id || '');
              if (!id) return true; // keep if no id
              if (seen.has(id)) return false;
              seen.add(id);
              return true;
            });
          })();
          // Do not apply the main year filter inside the modal; always show all
          // invoices for the selected member to avoid empty modal scenarios.
          document.getElementById('miqaat-modal-its').textContent = its;
          document.getElementById('miqaat-modal-name').textContent = name;
          document.getElementById('miqaat-modal-table-wrapper').innerHTML = buildTable(invoices, its, memberSector, memberSubSector);
        });

        // Delegated handlers inside modal for edit/save/cancel
        document.getElementById('miqaat-modal-table-wrapper').addEventListener('click', function(e) {
          const td = e.target.closest('td[data-invoice-id]');
          // For delete action, we may not be on amount td; find row first
          const row = e.target.closest('tr[data-invoice-id]');
          const isDelete = e.target.closest('.delete-invoice');
          if (!td && !isDelete) return;

          // Handle Delete
          if (isDelete && row) {
            const invoiceId = row.getAttribute('data-invoice-id');
            if (!invoiceId) return;
            if (!confirm('Are you sure you want to delete this invoice?')) return;

            // Capture amount before deleting for totals/summary updates
            const amountTd = row.querySelector('td[data-amount]');
            const amtVal = amountTd ? (parseFloat(amountTd.getAttribute('data-amount')) || 0) : 0;

            // Disable button to prevent double submit
            const delBtn = row.querySelector('.delete-invoice');
            if (delBtn) delBtn.disabled = true;

            fetch(DELETE_URL, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `invoice_id=${encodeURIComponent(invoiceId)}`
              })
              .then(res => {
                if (!res.ok) throw new Error('Failed');
                return res.json().catch(() => ({}));
              })
              .then(() => {
                row.parentNode.removeChild(row);
                recalcModalTotal();

                if (currentTriggerBtn) {
                  try {
                    const arr = JSON.parse(currentTriggerBtn.getAttribute('data-invoices') || '[]');
                    const filtered = arr.filter(inv => String(inv.invoice_id) !== String(invoiceId));
                    currentTriggerBtn.setAttribute('data-invoices', JSON.stringify(filtered));

                    const memberRow = currentTriggerBtn.closest('tr');
                    if (memberRow) {
                      // Column order: 0 #, 1 ITS, 2 Name, 3 Sector, 4 Sub Sector, 5 Total Invoices, 6 Total Amount, 7 Action
                      const cells = memberRow.querySelectorAll('td');
                      const countCell = cells[5];
                      const totalCell = cells[6];
                      if (countCell) countCell.textContent = filtered.length;
                      if (totalCell) {
                        const sum = filtered.reduce((acc, it) => {
                          const a = (it.amount !== undefined && it.amount !== null) ? parseFloat(it.amount) : undefined;
                          const ia = (it.invoice_amount !== undefined && it.invoice_amount !== null) ? parseFloat(it.invoice_amount) : undefined;
                          const v = (!isNaN(a) ? a : (!isNaN(ia) ? ia : 0));
                          return acc + (isNaN(v) ? 0 : v);
                        }, 0);
                        totalCell.textContent = currency(sum);
                      }
                    }
                  } catch (err) {
                    // ignore
                  }
                }

                // If no rows left, show empty state
                const wrapper = document.getElementById('miqaat-modal-table-wrapper');
                const tbody = wrapper.querySelector('tbody');
                if (tbody && tbody.children.length === 0) {
                  wrapper.innerHTML = '<div class="alert alert-info">No invoices for this member.</div>';
                }
              })
              .catch(() => {
                alert('Delete failed. Please try again.');
              })
              .finally(() => {
                if (delBtn) delBtn.disabled = false;
              });
            return;
          }

          if (e.target.closest('.edit-amount')) {
            td.querySelector('.amount-view').classList.add('d-none');
            td.querySelector('.amount-edit').classList.remove('d-none');
            const input = td.querySelector('.amount-input');
            if (input) input.focus();
            return;
          }

          if (e.target.closest('.cancel-amount')) {
            td.querySelector('.amount-edit').classList.add('d-none');
            td.querySelector('.amount-view').classList.remove('d-none');
            return;
          }

          if (e.target.closest('.save-amount')) {
            const invoiceId = td.getAttribute('data-invoice-id');
            const input = td.querySelector('.amount-input');
            const newVal = parseFloat(input.value);
            if (isNaN(newVal) || newVal < 0) {
              alert('Please enter a valid amount.');
              return;
            }
            // Disable buttons during save
            const saveBtn = td.querySelector('.save-amount');
            const cancelBtn = td.querySelector('.cancel-amount');
            saveBtn.disabled = true;
            cancelBtn.disabled = true;

            fetch(UPDATE_URL, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `invoice_id=${encodeURIComponent(invoiceId)}&amount=${encodeURIComponent(newVal.toFixed(2))}`
              })
              .then(res => {
                if (!res.ok) throw new Error('Failed to update');
                return res.json().catch(() => ({}));
              })
              .then(data => {
                // Update UI in modal
                td.setAttribute('data-amount', String(newVal));
                const amtText = td.querySelector('.amount-text');
                if (amtText) amtText.textContent = currency(newVal);
                td.querySelector('.amount-edit').classList.add('d-none');
                td.querySelector('.amount-view').classList.remove('d-none');
                recalcModalTotal();

                // Also update the member list row totals and the trigger button's data-invoices
                if (currentTriggerBtn) {
                  try {
                    const arr = JSON.parse(currentTriggerBtn.getAttribute('data-invoices') || '[]');
                    const idx = arr.findIndex(inv => String(inv.invoice_id) === String(invoiceId));
                    if (idx > -1) {
                      // Prefer storing in both keys for consistency
                      arr[idx].amount = newVal;
                      arr[idx].invoice_amount = newVal;
                    }
                    currentTriggerBtn.setAttribute('data-invoices', JSON.stringify(arr));

                    const memberRow = currentTriggerBtn.closest('tr');
                    if (memberRow) {
                      // Column order: 0 #, 1 ITS, 2 Name, 3 Sector, 4 Sub Sector, 5 Total Invoices, 6 Total Amount, 7 Action
                      const cells = memberRow.querySelectorAll('td');
                      const totalCell = cells[6];
                      if (totalCell) {
                        const sum = arr.reduce((acc, it) => {
                          const a = (it.amount !== undefined && it.amount !== null) ? parseFloat(it.amount) : undefined;
                          const ia = (it.invoice_amount !== undefined && it.invoice_amount !== null) ? parseFloat(it.invoice_amount) : undefined;
                          const v = (!isNaN(a) ? a : (!isNaN(ia) ? ia : 0));
                          return acc + (isNaN(v) ? 0 : v);
                        }, 0);
                        totalCell.textContent = currency(sum);
                      }
                    }
                  } catch (err) {
                    // ignore
                  }
                }
              })
              .catch(err => {
                alert('Update failed. Please try again.');
              })
              .finally(() => {
                saveBtn.disabled = false;
                cancelBtn.disabled = false;
              });
          }
        });

        // ========== Fala ni Niyaz Modal Logic: render miqaats ==========
        function buildFalaMiqaatsTable(miqaList) {
          if (!miqaList || !miqaList.length) {
            return '<div class="alert alert-info">No miqaats found.</div>';
          }
          // Deduplicate groups by group_key to avoid repeated rows
          const unique = [];
          const seenGK = new Set();
          (Array.isArray(miqaList) ? miqaList : []).forEach(m => {
            const gk = m.group_key || '';
            const key = String(gk);
            if (key && seenGK.has(key)) return;
            if (key) seenGK.add(key);
            unique.push(m);
          });
          const rows = unique.map((m, idx) => {
            const gk = m.group_key || '';
            const name = m.miqaat_name || '';
            const type = m.miqaat_type || '';
            const date = m.miqaat_date ? formatDate(m.miqaat_date) : '';
            const year = (m.year !== undefined && m.year !== null) ? String(m.year) : '';
            const amt = (m.amount !== undefined && m.amount !== null) ? parseFloat(m.amount) : null;
            const amtText = (amt !== null && !Number.isNaN(amt)) ? currency(amt) : '-';
            const invoiceIds = Array.isArray(m.invoice_ids) ? m.invoice_ids : [];
            return `
              <tr data-group-key="${gk}" data-invoice-ids='${JSON.stringify(invoiceIds)}'>
                <td>${idx + 1}</td>
                <td>${gk}</td>
                <td>${name}</td>
                <td>${date}</td>
                <td>${year || '-'}</td>
                <td class="text-right align-middle">
                  <div class="fala-amount-view d-flex align-items-center justify-content-end">
                    <span class="fala-amount-text mr-2">${amtText}</span>
                    <button type="button" class="btn btn-link btn-sm fala-edit-amount" title="Edit amount"><i class="fa-solid fa-pencil"></i></button>
                  </div>
                  <div class="fala-amount-edit d-none">
                    <div class="input-group input-group-sm">
                      <div class="input-group-prepend">
                        <span class="input-group-text">₹</span>
                      </div>
                      <input type="number" class="form-control fala-amount-input" value="${(amt !== null && !Number.isNaN(amt)) ? Math.round(amt) : '0'}" step="1" min="0">
                    </div>
                    <div class="mt-2 text-right">
                      <button type="button" class="btn btn-sm btn-primary fala-save-amount">Save</button>
                      <button type="button" class="btn btn-sm btn-secondary fala-cancel-amount">Cancel</button>
                    </div>
                  </div>
                </td>
                <td class="text-center align-middle">
                  <button type="button" class="btn btn-sm btn-outline-danger fala-delete-group" title="Delete all invoices in this group"><i class="fa-solid fa-trash"></i></button>
                </td>
            `;
          }).join('');
          return `
            <table class="table table-striped table-bordered">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Miqaat ID</th>
                  <th>Miqaat Name</th>
                  <th>Date</th>
                  <th>Year</th>
                  <th class="text-right">Amount</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                ${rows}
              </tbody>
            </table>
          `;
        }

        const falaBtn = document.getElementById('fala-ni-niyaz-invoices');
        if (falaBtn) {
          falaBtn.addEventListener('click', function() {
            const list = Array.isArray(FALA_MIQAATS) ? FALA_MIQAATS : [];
            document.getElementById('fala-modal-table-wrapper').innerHTML = buildFalaMiqaatsTable(list);
          });
        }

        // Group-level edit/delete handlers for Fala modal
        const falaWrapper = document.getElementById('fala-modal-table-wrapper');
        const falaOverlay = document.getElementById('fala-loading-overlay');
        const falaLoading = {
          show(msg) {
            if (!falaOverlay) return;
            falaOverlay.classList.remove('d-none');
            const m = falaOverlay.querySelector('.message');
            if (m) m.textContent = msg || 'Please wait...';
          },
          hide() {
            if (!falaOverlay) return;
            falaOverlay.classList.add('d-none');
          }
        };
        falaWrapper.addEventListener('click', function(e) {
          const row = e.target.closest('tr[data-group-key]');
          if (!row) return;
          const amountCell = row.querySelector('.text-right');
          const groupKey = row.getAttribute('data-group-key') || '';
          const invoiceIds = (() => {
            try {
              return JSON.parse(row.getAttribute('data-invoice-ids') || '[]');
            } catch {
              return [];
            }
          })();

          // Edit amount
          if (e.target.closest('.fala-edit-amount')) {
            const view = amountCell.querySelector('.fala-amount-view');
            const edit = amountCell.querySelector('.fala-amount-edit');
            view.classList.add('d-none');
            edit.classList.remove('d-none');
            const input = edit.querySelector('.fala-amount-input');
            if (input) input.focus();
            return;
          }

          // Cancel amount edit
          if (e.target.closest('.fala-cancel-amount')) {
            const view = amountCell.querySelector('.fala-amount-view');
            const edit = amountCell.querySelector('.fala-amount-edit');
            edit.classList.add('d-none');
            view.classList.remove('d-none');
            return;
          }

          // Save amount edit: apply to all invoices in the group
          if (e.target.closest('.fala-save-amount')) {
            const edit = amountCell.querySelector('.fala-amount-edit');
            const input = edit.querySelector('.fala-amount-input');
            const newVal = parseFloat(input.value);
            if (isNaN(newVal) || newVal < 0) {
              alert('Please enter a valid amount.');
              return;
            }
            const saveBtn = edit.querySelector('.fala-save-amount');
            const cancelBtn = edit.querySelector('.fala-cancel-amount');
            saveBtn.disabled = true;
            cancelBtn.disabled = true;

            // Update all invoices in the group sequentially
            const updateOne = (invoiceId) => fetch('<?php echo base_url("anjuman/updateMiqaatInvoiceAmount"); ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `invoice_id=${encodeURIComponent(invoiceId)}&amount=${encodeURIComponent(newVal.toFixed(2))}`
            }).then(res => {
              if (!res.ok) throw new Error('fail');
              return res;
            });

            (async () => {
              try {
                falaLoading.show('Updating invoices...');
                for (const id of invoiceIds) {
                  await updateOne(id);
                }
                // Update UI cell
                const view = amountCell.querySelector('.fala-amount-view');
                const amtText = view.querySelector('.fala-amount-text');
                amtText.textContent = currency(newVal);
                const edit = amountCell.querySelector('.fala-amount-edit');
                edit.classList.add('d-none');
                view.classList.remove('d-none');
                // Update FALA_MIQAATS cached list
                const idx = (Array.isArray(FALA_MIQAATS) ? FALA_MIQAATS : []).findIndex(m => (m.group_key || '') === groupKey);
                if (idx > -1) {
                  FALA_MIQAATS[idx].amount = newVal;
                }
              } catch (err) {
                alert('Update failed for one or more invoices.');
              } finally {
                saveBtn.disabled = false;
                cancelBtn.disabled = false;
                falaLoading.hide();
              }
            })();
            return;
          }

          // Delete all invoices in the group
          if (e.target.closest('.fala-delete-group')) {
            if (!invoiceIds.length) {
              alert('No invoices to delete in this group.');
              return;
            }
            if (!confirm('Delete ALL invoices in this group? This cannot be undone.')) return;

            const deleteOne = (invoiceId) => fetch('<?php echo base_url("anjuman/deleteMiqaatInvoice"); ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `invoice_id=${encodeURIComponent(invoiceId)}`
            }).then(res => {
              if (!res.ok) throw new Error('fail');
              return res;
            });

            (async () => {
              try {
                falaLoading.show('Deleting invoices...');
                for (const id of invoiceIds) {
                  await deleteOne(id);
                }
                // Remove row from table
                row.parentNode.removeChild(row);
                // Remove from cached list
                FALA_MIQAATS = (Array.isArray(FALA_MIQAATS) ? FALA_MIQAATS : []).filter(m => (m.group_key || '') !== groupKey);
              } catch (err) {
                alert('Delete failed for one or more invoices.');
              } finally {
                falaLoading.hide();
              }
            })();
          }
        });

        // Refresh the page when the Fala modal is closed
        (function setupFalaModalCloseRefresh() {
          const modalEl = document.getElementById('falaNiyazInvoicesModal');
          if (!modalEl) return;
          const refresh = function() {
            window.location.reload();
          };
          if (window.jQuery && typeof jQuery.fn !== 'undefined' && typeof jQuery.fn.modal === 'function') {
            jQuery(modalEl).on('hidden.bs.modal', refresh);
          } else {
            modalEl.addEventListener('hidden.bs.modal', refresh);
          }
        })();
      })();
      // --- Sorting for member table ---
      (function enableMemberTableSorting() {
        const table = document.getElementById('miqaat-member-table');
        if (!table) return;
        const headers = table.querySelectorAll('thead th.km-sortable');

        function parseCurrency(text) {
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
            // reset indicators
            headers.forEach(h => {
              if (h !== th) {
                h.removeAttribute('data-sort-dir');
                const si = h.querySelector('.sort-indicator');
                if (si) si.textContent = '';
              }
            });
            th.setAttribute('data-sort-dir', newDir);
            const ind = th.querySelector('.sort-indicator');
            if (ind) ind.textContent = newDir === 'asc' ? '▲' : '▼';
            const tbody = table.querySelector('tbody');
            const allRows = Array.from(tbody.querySelectorAll('tr'));
            const visible = allRows.filter(r => r.style.display !== 'none');
            const hidden = allRows.filter(r => r.style.display === 'none');
            visible.sort((a, b) => {
              let aVal = a.children[colIndex] ? a.children[colIndex].textContent.trim() : '';
              let bVal = b.children[colIndex] ? b.children[colIndex].textContent.trim() : '';
              if (sortType === 'number') {
                const toNum = v => {
                  const n = parseFloat(v.replace(/[^0-9.-]/g, ''));
                  return isNaN(n) ? 0 : n;
                };
                aVal = toNum(aVal);
                bVal = toNum(bVal);
                return newDir === 'asc' ? aVal - bVal : bVal - aVal;
              } else if (sortType === 'currency') {
                aVal = parseCurrency(aVal);
                bVal = parseCurrency(bVal);
                return newDir === 'asc' ? aVal - bVal : bVal - aVal;
              } else { // string
                aVal = aVal.toLowerCase();
                bVal = bVal.toLowerCase();
                if (aVal === bVal) return 0;
                return newDir === 'asc' ? (aVal < bVal ? -1 : 1) : (aVal > bVal ? -1 : 1);
              }
            });
            visible.forEach((r, i) => {
              tbody.appendChild(r);
              const first = r.children[0];
              if (first) first.textContent = i + 1;
            });
            hidden.forEach(r => tbody.appendChild(r));
          });
        });
      })();
    </script>
  <?php endif; ?>
</div>