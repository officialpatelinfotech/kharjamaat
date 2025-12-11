<div class="margintopcontainer pt-5">
  <div class="row">
    <div class="col-12">
      <div class="col-12 col-md-6 m-0 mb-2">
        <a href="<?php echo base_url("anjuman/fmbniyaz") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
      </div>
      <div class="col-12 col-md-6 m-0 text-right ml-auto mb-3">
        <button id="fala-ni-niyaz-invoices" class="btn btn-primary" data-toggle="modal" data-target="#falaNiyazInvoicesModal">Invoices Generated for Miqaats</button>
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

  $rows = [];
  $grand_total = 0.0;
  if (!empty($members)) {
    foreach ($members as $m) {
      $its = isset($m['ITS_ID']) ? $m['ITS_ID'] : '';
      $name = isset($m['Full_Name']) ? $m['Full_Name'] : '';
      if (isset($m['miqaat_invoices']) && is_array($m['miqaat_invoices'])) {
        foreach ($m['miqaat_invoices'] as $inv) {
          $row = [
            'invoice_id'   => isset($inv['invoice_id']) ? $inv['invoice_id'] : '',
            'its_id'       => $its,
            'full_name'    => $name,
            'miqaat_id'    => isset($inv['miqaat_id']) ? $inv['miqaat_id'] : '',
            'miqaat_name'  => isset($inv['miqaat_name']) ? $inv['miqaat_name'] : '',
            'invoice_date' => isset($inv['invoice_date']) ? $inv['invoice_date'] : '',
            'amount'       => isset($inv['amount']) ? (float)$inv['amount'] : 0.0,
            'description'  => isset($inv['description']) ? $inv['description'] : '',
          ];
          $rows[] = $row;
          $grand_total += (float)$row['amount'];
        }
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
  }
  ?>

  <?php if (empty($members)) : ?>
    <div class="col-12 alert alert-info">No invoices found for members.</div>
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
    </style>
    <div class="col-12 table-responsive">
      <div id="miqaat-filters" class="p-3 bg-light border mb-2">
        <div class="form-row">
          <div class="col-md-3 mb-2">
            <label for="mf-name" class="mb-1 text-muted">Member Name</label>
            <input type="text" id="mf-name" class="form-control form-control-sm" placeholder="Search name...">
          </div>
          <div class="col-md-3 mb-2">
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
          <div class="col-md-3 mb-2">
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
          <div class="col-md-3 mb-2">
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
          <div class="col-md-3 mb-2 d-flex align-items-end">
            <button type="button" id="mf-clear" class="btn btn-outline-secondary btn-sm w-100">Clear Filters</button>
          </div>
        </div>
      </div>
      <table class="table table-striped table-bordered" id="miqaat-member-table">
        <thead class="thead-light">
          <tr>
            <th class="km-sortable" data-sort-type="number"># <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-type="string">ITS ID <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-type="string">Member Name <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-type="string">Sector <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-type="string">Sub Sector <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-type="number">Total Invoices <span class="sort-indicator"></span></th>
            <th class="km-sortable text-right" data-sort-type="currency">Total Amount <span class="sort-indicator"></span></th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Default ordering: Sector ASC, Sub Sector ASC (case-insensitive)
          if (!empty($members) && is_array($members)) {
            usort($members, function($a, $b) {
              $sa = strtolower(isset($a['Sector']) ? $a['Sector'] : (isset($a['sector']) ? $a['sector'] : ''));
              $sb = strtolower(isset($b['Sector']) ? $b['Sector'] : (isset($b['sector']) ? $b['sector'] : ''));
              if ($sa === $sb) {
                $ssa = strtolower(isset($a['Sub_Sector']) ? $a['Sub_Sector'] : (isset($a['sub_sector']) ? $a['sub_sector'] : ''));
                $ssb = strtolower(isset($b['Sub_Sector']) ? $b['Sub_Sector'] : (isset($b['sub_sector']) ? $b['sub_sector'] : ''));
                return $ssa <=> $ssb;
              }
              return $sa <=> $sb;
            });
          }
          ?>
          <?php foreach ($members as $key => $m) : ?>
            <?php
            $its = isset($m['ITS_ID']) ? $m['ITS_ID'] : '';
            $name = isset($m['Full_Name']) ? $m['Full_Name'] : '';
            $sector = isset($m['Sector']) ? $m['Sector'] : (isset($m['sector']) ? $m['sector'] : '');
            $subSector = isset($m['Sub_Sector']) ? $m['Sub_Sector'] : (isset($m['sub_sector']) ? $m['sub_sector'] : '');
            $invoices = isset($m['miqaat_invoices']) && is_array($m['miqaat_invoices']) ? $m['miqaat_invoices'] : [];
            // Normalize invoice amount fields so frontend has both keys
            $invoices_normalized = [];
            $seen_ids = [];
            foreach ($invoices as $inv) {
              $norm = $inv;
              $hasAmount = isset($inv['amount']) && $inv['amount'] !== '' && $inv['amount'] !== null;
              $hasInvoiceAmount = isset($inv['invoice_amount']) && $inv['invoice_amount'] !== '' && $inv['invoice_amount'] !== null;
              if ($hasAmount && !$hasInvoiceAmount) {
                $norm['invoice_amount'] = $inv['amount'];
              } elseif (!$hasAmount && $hasInvoiceAmount) {
                $norm['amount'] = $inv['invoice_amount'];
              } elseif (!$hasAmount && !$hasInvoiceAmount) {
                $norm['amount'] = 0;
                $norm['invoice_amount'] = 0;
              }
              // Deduplicate by invoice_id (string compare)
              $iid = isset($norm['invoice_id']) ? (string)$norm['invoice_id'] : '';
              if ($iid !== '' && isset($seen_ids[$iid])) {
                continue;
              }
              if ($iid !== '') { $seen_ids[$iid] = true; }
              $invoices_normalized[] = $norm;
            }
            // Use deduplicated invoices for accurate count
            $count = count($invoices_normalized);
            $totalAmt = 0.0;
            $yearsForMember = [];
            foreach ($invoices_normalized as $inv) {
              $amt = 0.0;
              if (isset($inv['amount']) && $inv['amount'] !== '') {
                $amt = (float)$inv['amount'];
              } elseif (isset($inv['invoice_amount']) && $inv['invoice_amount'] !== '') {
                $amt = (float)$inv['invoice_amount'];
              }
              $totalAmt += $amt;
              if (isset($inv['invoice_year']) && $inv['invoice_year'] !== '' && !in_array($inv['invoice_year'], $yearsForMember)) {
                $yearsForMember[] = $inv['invoice_year'];
              }
            }
            $yearsAttr = strtolower(implode(',', $yearsForMember));
            ?>
            <tr data-sector="<?php echo htmlspecialchars(strtolower($sector), ENT_QUOTES); ?>" data-subsector="<?php echo htmlspecialchars(strtolower($subSector), ENT_QUOTES); ?>" data-years="<?php echo htmlspecialchars($yearsAttr, ENT_QUOTES); ?>">
              <td><?php echo $key + 1; ?></td>
              <td><?php echo htmlspecialchars($its); ?></td>
              <td class="member-name-cell"><?php echo htmlspecialchars($name); ?></td>
              <td><?php echo htmlspecialchars($sector); ?></td>
              <td><?php echo htmlspecialchars($subSector); ?></td>
              <td><?php echo $count; ?></td>
              <td class="text-right">₹<?php
                if (!function_exists('format_inr_no_decimals')) {
                  function format_inr_no_decimals($num) {
                    if ($num === null || $num === '' || !is_numeric($num)) {
                      $num = 0;
                    }
                    $num = (float)$num;
                    // Round to nearest rupee (can change to floor if needed)
                    $num = round($num);
                    $neg = $num < 0; if ($neg) $num = abs($num);
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
                echo format_inr_no_decimals($totalAmt); ?></td>
              <td>
                <button
                  class="btn btn-sm btn-outline-primary view-invoices-btn"
                  data-toggle="modal"
                  data-target="#memberInvoicesModal"
                  data-its="<?php echo htmlspecialchars($its); ?>"
                  data-name="<?php echo htmlspecialchars($name); ?>"
                  data-sector="<?php echo htmlspecialchars($sector); ?>"
                  data-subsector="<?php echo htmlspecialchars($subSector); ?>"
                  data-invoices='<?php echo json_encode($invoices_normalized); ?>'>View Invoices</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
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
        function applyFilters() {
          const nameVal = (document.getElementById('mf-name').value || '').trim().toLowerCase();
          const sectorVal = (document.getElementById('mf-sector').value || '').trim();
          const subVal = (document.getElementById('mf-subsector').value || '').trim();
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
          const rows = document.querySelectorAll('#miqaat-member-table tbody tr');
          let index = 1;

          // Local helper for INR grouping (no decimals)
          function formatRupees(n) {
            if (n === undefined || n === null) n = 0;
            let num = parseFloat(n);
            if (isNaN(num)) num = 0;
            num = Math.round(num);
            const neg = num < 0; if (neg) num = Math.abs(num);
            let s = String(num);
            if (s.length > 3) {
              const last3 = s.slice(-3);
              let rest = s.slice(0, -3);
              rest = rest.replace(/\B(?=(?:\d{2})+(?!\d))/g, ',');
              s = rest + ',' + last3;
            }
            return (neg ? '-' : '') + '₹' + s;
          }

          rows.forEach(r => {
            const rName = (r.querySelector('.member-name-cell')?.textContent || '').trim().toLowerCase();
            const rSector = r.getAttribute('data-sector') || '';
            const rSub = r.getAttribute('data-subsector') || '';
            let show = true;
            if (nameVal && rName.indexOf(nameVal) === -1) show = false;
            if (sectorVal && rSector !== sectorVal) show = false;
            if (subVal && rSub !== subVal) show = false;

            // Always show member regardless of year; adjust counts/amount per selected year
            if (!show) {
              r.style.display = 'none';
              return;
            }

            // Recalculate invoice count & total for selected year (or all if none selected)
            const btn = r.querySelector('.view-invoices-btn');
            let allInvoices = [];
            if (btn) {
              try { allInvoices = JSON.parse(btn.getAttribute('data-invoices') || '[]'); } catch(e) { allInvoices = []; }
            }
            // Do not client-filter by year; server provides year-scoped invoices.
            let working = allInvoices;
            const cells = r.querySelectorAll('td');
            // Column order: 0 #, 1 ITS, 2 Name, 3 Sector, 4 Sub Sector, 5 Total Invoices, 6 Total Amount, 7 Action
            if (cells[5]) cells[5].textContent = working.length;
            if (cells[6]) {
              const sum = working.reduce((acc, it) => {
                const a = (it.amount !== undefined && it.amount !== null) ? parseFloat(it.amount) : undefined;
                const ia = (it.invoice_amount !== undefined && it.invoice_amount !== null) ? parseFloat(it.invoice_amount) : undefined;
                const v = (!isNaN(a) ? a : (!isNaN(ia) ? ia : 0));
                return acc + (isNaN(v) ? 0 : v);
              }, 0);
              cells[6].textContent = formatRupees(sum);
            }
            r.style.display = '';
            r.querySelector('td').textContent = index++;
          });
        }
        const nameInput = document.getElementById('mf-name');
        const sectorSel = document.getElementById('mf-sector');
        const subSel = document.getElementById('mf-subsector');
        const yearSel = document.getElementById('mf-year');
        if (nameInput) nameInput.addEventListener('input', applyFilters);
        if (sectorSel) sectorSel.addEventListener('change', applyFilters);
        if (subSel) subSel.addEventListener('change', applyFilters);
        if (yearSel) yearSel.addEventListener('change', function(){
          const y = (yearSel.value||'').trim();
          const params = new URLSearchParams(window.location.search);
          if (y) { params.set('year', y); } else { params.delete('year'); }
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

            // Otherwise just reset visible rows/counts to their full values (client-side)
            const rows = document.querySelectorAll('#miqaat-member-table tbody tr');
            let i = 1;
            rows.forEach(r => {
              // Reset counts/amounts to full invoice totals
              const btn = r.querySelector('.view-invoices-btn');
              let allInvoices = [];
              if (btn) { try { allInvoices = JSON.parse(btn.getAttribute('data-invoices') || '[]'); } catch(e) { allInvoices = []; } }
              const cells = r.querySelectorAll('td');
              if (cells[5]) cells[5].textContent = allInvoices.length;
              if (cells[6]) {
                const sum = allInvoices.reduce((acc, it) => {
                  const a = (it.amount !== undefined && it.amount !== null) ? parseFloat(it.amount) : undefined;
                  const ia = (it.invoice_amount !== undefined && it.invoice_amount !== null) ? parseFloat(it.invoice_amount) : undefined;
                  const v = (!isNaN(a) ? a : (!isNaN(ia) ? ia : 0));
                  return acc + (isNaN(v) ? 0 : v);
                }, 0);
                // Basic INR no-decimal format (reuse formatting logic)
                let num = Math.round(sum);
                let s = String(num);
                if (s.length > 3) {
                  const last3 = s.slice(-3);
                  let rest = s.slice(0, -3);
                  rest = rest.replace(/\B(?=(?:\d{2})+(?!\d))/g, ',');
                  s = rest + ',' + last3;
                }
                cells[6].textContent = '₹' + s;
              }
              r.style.display = '';
              r.querySelector('td').textContent = i++;
            });
            // Re-apply filters to update title and any remaining visual state
            applyFilters();
          });
        }
        // Set title year to selected/default on initial load
        if (yearSel) {
          const defYear = (yearSel.getAttribute('data-default-year') || '').trim();
          const titleYearEl = document.getElementById('title-hijri-year');
          if (titleYearEl && defYear) titleYearEl.textContent = `(Hijri ${defYear})`;
          // Because data is already filtered server-side, we only update counts visually
          // to ensure consistency on initial paint
          applyFilters();
        }
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
          /* no-op */ }
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
          const neg = num < 0; if (neg) num = Math.abs(num);
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
          (function dedupe(){
            const seen = new Set();
            invoices = invoices.filter(inv => {
              const id = String(inv.invoice_id||'');
              if(!id) return true; // keep if no id
              if(seen.has(id)) return false;
              seen.add(id); return true;
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
          (Array.isArray(miqaList)?miqaList:[]).forEach(m => {
            const gk = m.group_key || '';
            const key = String(gk);
            if(key && seenGK.has(key)) return;
            if(key) seenGK.add(key);
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
      (function enableMemberTableSorting(){
        const table = document.getElementById('miqaat-member-table');
        if(!table) return;
        const headers = table.querySelectorAll('thead th.km-sortable');
        function parseCurrency(text){
          if(text==null) return 0;
          const cleaned = String(text).replace(/[^0-9.-]/g,'');
          const v = parseFloat(cleaned); return isNaN(v)?0:v;
        }
        headers.forEach((th, colIndex)=>{
          th.addEventListener('click', ()=>{
            const sortType = th.getAttribute('data-sort-type') || 'string';
            const currentDir = th.getAttribute('data-sort-dir');
            const newDir = currentDir === 'asc' ? 'desc' : 'asc';
            // reset indicators
            headers.forEach(h=>{ if(h!==th){ h.removeAttribute('data-sort-dir'); const si=h.querySelector('.sort-indicator'); if(si) si.textContent=''; }});
            th.setAttribute('data-sort-dir', newDir);
            const ind = th.querySelector('.sort-indicator'); if(ind) ind.textContent = newDir==='asc'?'▲':'▼';
            const tbody = table.querySelector('tbody');
            const allRows = Array.from(tbody.querySelectorAll('tr'));
            const visible = allRows.filter(r=>r.style.display !== 'none');
            const hidden = allRows.filter(r=>r.style.display === 'none');
            visible.sort((a,b)=>{
              let aVal = a.children[colIndex]?a.children[colIndex].textContent.trim():'';
              let bVal = b.children[colIndex]?b.children[colIndex].textContent.trim():'';
              if(sortType==='number'){
                const toNum = v=>{ const n = parseFloat(v.replace(/[^0-9.-]/g,'')); return isNaN(n)?0:n; };
                aVal = toNum(aVal); bVal = toNum(bVal);
                return newDir==='asc'? aVal-bVal : bVal-aVal;
              } else if(sortType==='currency') {
                aVal = parseCurrency(aVal); bVal = parseCurrency(bVal);
                return newDir==='asc'? aVal-bVal : bVal-aVal;
              } else { // string
                aVal = aVal.toLowerCase(); bVal = bVal.toLowerCase();
                if(aVal===bVal) return 0;
                return newDir==='asc' ? (aVal<bVal?-1:1) : (aVal>bVal?-1:1);
              }
            });
            visible.forEach((r,i)=>{ tbody.appendChild(r); const first=r.children[0]; if(first) first.textContent = i+1; });
            hidden.forEach(r=>tbody.appendChild(r));
          });
        });
      })();
    </script>
  <?php endif; ?>
</div>