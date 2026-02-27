<style>
  /* Improved pill counter for Fala ni Niyaz button */
  .fala-ni-niyaz-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    margin: 0;
    margin-left: 2px;
    font-size: 12px;
    font-weight: 600;
    line-height: 1;
    border-radius: 999px;
    background-color: #ffffff;
    border: 1px solid rgba(13, 110, 253, 0.18);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    vertical-align: middle;
    user-select: none;
  }

  /* Ensure good contrast when on primary button */
  .btn-primary .fala-ni-niyaz-count {
    background-color: #ffffff;
  }

  /* Slight hover emphasis with parent button */
  #fala-ni-niyaz-invoices:hover .fala-ni-niyaz-count {
    border-color: rgba(13, 110, 253, 0.35);
    box-shadow: 0 2px 6px rgba(13, 110, 253, 0.12);
  }

  /* Sortable table headers */
  th.km-sortable {
    cursor: pointer;
    position: relative;
    user-select: none;
    white-space: nowrap;
  }

  th.km-sortable .sort-indicator {
    font-size: 11px;
    margin-left: 4px;
    opacity: 0.8;
  }
</style>

<div class="margintopcontainer mx-5 pt-3">
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
      <?php echo $this->session->flashdata('error'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
      <?php echo $this->session->flashdata('success'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('warning')): ?>
    <div class="alert alert-warning">
      <?php echo $this->session->flashdata('warning'); ?>
    </div>
  <?php endif; ?>
  <div class="col-12 mb-4">
    <div class="row p-0">
      <div class="col-12 col-md-6">
        <a href="<?php echo base_url("anjuman/fmbniyaz") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
      </div>
      <div class="col-12 col-md-6 text-right ml-auto">
        <button id="fala-ni-niyaz-invoices" class="btn btn-primary">
          <?php echo isset($miqaat_type) ? $miqaat_type : ""; ?> Niyaz Fala Takhmeen <span class="fala-ni-niyaz-count text-primary"><?php echo isset($miqaats["Fala_ni_Niyaz"]) ? count($miqaats["Fala_ni_Niyaz"]) : 0; ?></span>
        </button>
      </div>
    </div>
  </div>

  <?php
  $pending = (isset($miqaats["miqaats"]) && is_array($miqaats["miqaats"])) ? $miqaats["miqaats"] : [];
  $gen_sectors = [];
  $gen_sub_sectors = [];
  $gen_years = [];
  if (!empty($pending)) {
    foreach ($pending as $row) {
      $assignType = isset($row['assign_type']) ? strtolower(trim((string)$row['assign_type'])) : '';
      $sector = '';
      $subSector = '';
      if ($assignType === 'group') {
        $sector = isset($row['leader_Sector']) ? (string)$row['leader_Sector'] : '';
        $subSector = isset($row['leader_Sub_Sector']) ? (string)$row['leader_Sub_Sector'] : '';
      } else {
        $sector = isset($row['Sector']) ? (string)$row['Sector'] : '';
        $subSector = isset($row['Sub_Sector']) ? (string)$row['Sub_Sector'] : '';
      }
      if ($sector !== '' && !in_array($sector, $gen_sectors, true)) $gen_sectors[] = $sector;
      if ($subSector !== '' && !in_array($subSector, $gen_sub_sectors, true)) $gen_sub_sectors[] = $subSector;

      $hy = '';
      if (!empty($row['hijri_date'])) {
        $parts = preg_split('/\s+/', trim((string)$row['hijri_date']));
        $hy = $parts ? (string)end($parts) : '';
      }
      if ($hy !== '' && !in_array($hy, $gen_years, true)) $gen_years[] = $hy;
    }
    sort($gen_sectors);
    sort($gen_sub_sectors);
    rsort($gen_years);
  }

  // Prefer full Hijri years list from hijri_calendar (provided by controller)
  if (isset($hijri_years) && is_array($hijri_years) && !empty($hijri_years)) {
    $gen_years = array_values(array_unique(array_filter(array_map(function ($y) {
      $y = trim((string)$y);
      return $y !== '' ? $y : null;
    }, $hijri_years))));
    rsort($gen_years);
  }

  $default_gen_year = '';
  if (isset($current_hijri_year) && $current_hijri_year !== '') {
    $default_gen_year = (string)$current_hijri_year;
  } elseif (!empty($gen_years)) {
    $default_gen_year = $gen_years[0];
  }
  ?>

  <?php if (!empty($pending)) : ?>
    <style>
      #miqaat-generate-filters label {
        font-weight: 600;
        font-size: 12px;
      }

      #miqaat-generate-filters input,
      #miqaat-generate-filters select {
        font-size: 13px;
      }
    </style>
    <div id="miqaat-generate-filters" class="p-3 bg-light border m-md-3">
      <div class="form-row">
        <div class="col-md-2 mb-2">
          <label for="pf-name" class="mb-1 text-muted">Name or ITS</label>
          <input type="text" id="pf-name" class="form-control form-control-sm" placeholder="Search name or ITS...">
        </div>
        <div class="col-md-2 mb-2">
          <label for="pf-sector" class="mb-1 text-muted">Sector</label>
          <select id="pf-sector" class="form-control form-control-sm">
            <option value="">All Sectors</option>
            <?php if (!empty($gen_sectors)) : foreach ($gen_sectors as $s) : if ($s === '') continue; ?>
                <option value="<?php echo htmlspecialchars(strtolower($s), ENT_QUOTES); ?>"><?php echo htmlspecialchars($s); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-2 mb-2">
          <label for="pf-subsector" class="mb-1 text-muted">Sub Sector</label>
          <select id="pf-subsector" class="form-control form-control-sm">
            <option value="">All Sub Sectors</option>
            <?php if (!empty($gen_sub_sectors)) : foreach ($gen_sub_sectors as $ss) : if ($ss === '') continue; ?>
                <option value="<?php echo htmlspecialchars(strtolower($ss), ENT_QUOTES); ?>"><?php echo htmlspecialchars($ss); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-2 mb-2">
          <label for="pf-year" class="mb-1 text-muted">Hijri Year</label>
          <select id="pf-year" class="form-control form-control-sm" data-default-year="<?php echo htmlspecialchars($default_gen_year, ENT_QUOTES); ?>">
            <option value="">All Years</option>
            <?php if (!empty($gen_years)) : foreach ($gen_years as $y) : ?>
                <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>" <?php echo ($default_gen_year === $y ? 'selected' : ''); ?>><?php echo htmlspecialchars($y); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-2 mb-2 d-flex align-items-end">
          <button type="button" id="pf-clear" class="btn btn-outline-secondary btn-sm w-100">Clear Filters</button>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <h4 class="text-center">
    <?php echo isset($miqaat_type) ? $miqaat_type : ""; ?> Miqaat <span class="text-primary">Pending Invoices</span>
    <span id="generate-title-hijri-year" class="text-muted" style="font-size:0.9em;"></span>
  </h4>

  <!-- <div class="col-12 mt-3 text-center">
    <?php
    $totalAmt = isset($generated_invoice_total) ? (float)$generated_invoice_total : 0.0;
    $totalFmt = function_exists('format_inr_no_decimals') ? format_inr_no_decimals($totalAmt) : number_format($totalAmt, 0);
    ?>
    <div class="d-inline-block px-3 py-2" style="background:#f8f9fa; border:1px solid #e5e7eb; border-radius:10px;">
      <b>Total Invoice Amount:</b> ₹<?php echo $totalFmt; ?>
      <span class="text-muted" style="font-size:12px;">(generated)</span>
    </div>
  </div>

  <div class="col-12 mt-3">
    <form method="get" action="<?php echo base_url('anjuman/generatemiqaatinvoice'); ?>" class="form-inline justify-content-center" style="gap:8px; flex-wrap:wrap;">
      <input type="hidden" name="miqaat_type" value="<?php echo htmlspecialchars($this->input->get('miqaat_type') ?? '', ENT_QUOTES); ?>">
      <div class="form-group">
        <label for="raza-id-filter" class="mr-2"><b>Raza ID:</b></label>
        <input
          type="text"
          class="form-control"
          id="raza-id-filter"
          name="raza_id"
          placeholder="Raza ID (optional)"
          value="<?php echo isset($raza_id_filter) ? htmlspecialchars($raza_id_filter, ENT_QUOTES) : ''; ?>"
          style="min-width:220px;"
        />
      </div>
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="<?php echo base_url('anjuman/generatemiqaatinvoice?miqaat_type=' . htmlspecialchars($this->input->get('miqaat_type') ?? '', ENT_QUOTES)); ?>" class="btn btn-outline-secondary">Clear</a>
    </form>
  </div> -->

  <div class="col-12 mt-4">
    <div class="card-header">
      <!-- <h5 class="mb-0 text-center">Invoice List</h5> -->
    </div>
    <div class="card-body p-0">
      <div class="table-responsive" style="max-height:80vh; overflow:auto;">
        <table class="table table-bordered table-striped" id="miqaat-generate-table">
          <thead class="table-dark">
            <tr>
              <th class="km-sortable" data-sort-key="index" data-sort-type="number"># <span class="sort-indicator"></span></th>
              <th class="km-sortable" data-sort-key="date" data-sort-type="date">Date <span class="sort-indicator"></span></th>
              <th class="km-sortable" data-sort-key="hijri" data-sort-type="string">Hijri Date <span class="sort-indicator"></span></th>
              <th class="km-sortable" data-sort-key="miqaatId" data-sort-type="number">Miqaat ID <span class="sort-indicator"></span></th>
              <th class="km-sortable" data-sort-key="razaId" data-sort-type="number">Raza ID <span class="sort-indicator"></span></th>
              <th class="km-sortable" data-sort-key="miqaatName" data-sort-type="string">Miqaat Name <span class="sort-indicator"></span></th>
              <th class="km-sortable" data-sort-key="assignedTo" data-sort-type="string">Assigned To <span class="sort-indicator"></span></th>
              <th class="km-sortable" data-sort-key="details" data-sort-type="string">Details <span class="sort-indicator"></span></th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!isset($miqaats["miqaats"]) || empty($miqaats["miqaats"])): ?>
              <tr>
                <td colspan="9" class="text-center">No Pending Invoices Found</td>
              </tr>
            <?php else: ?>
              <tr id="miqaat-generate-no-matches" style="display:none;">
                <td colspan="9" class="text-center text-muted">No pending invoices found for this year.</td>
              </tr>
              <?php foreach ($miqaats["miqaats"] as $i => $m): ?>
                <?php
                $assignType = isset($m['assign_type']) ? strtolower(trim((string)$m['assign_type'])) : '';
                $rowIts = '';
                $rowName = '';
                $rowSector = '';
                $rowSubSector = '';

                if ($assignType === 'group') {
                  $rowIts = isset($m['leader_ITS_ID']) ? (string)$m['leader_ITS_ID'] : '';
                  $rowName = isset($m['leader_Full_Name']) ? (string)$m['leader_Full_Name'] : '';
                  $rowSector = isset($m['leader_Sector']) ? (string)$m['leader_Sector'] : '';
                  $rowSubSector = isset($m['leader_Sub_Sector']) ? (string)$m['leader_Sub_Sector'] : '';
                } else {
                  $rowIts = isset($m['ITS_ID']) ? (string)$m['ITS_ID'] : '';
                  $rowName = isset($m['Full_Name']) ? (string)$m['Full_Name'] : '';
                  $rowSector = isset($m['Sector']) ? (string)$m['Sector'] : '';
                  $rowSubSector = isset($m['Sub_Sector']) ? (string)$m['Sub_Sector'] : '';
                }

                $rowYear = '';
                if (!empty($m['hijri_date'])) {
                  $p = preg_split('/\s+/', trim((string)$m['hijri_date']));
                  $rowYear = $p ? (string)end($p) : '';
                }

                $assignedToLabel = isset($m['assigned_to']) ? (string)$m['assigned_to'] : '';
                $assignedToKey = strtolower(trim($assignedToLabel));
                $assignedToDisplay = $assignedToLabel;
                $individualCountForSort = 0;
                if ($assignedToKey === 'individual') {
                  $cnt = isset($m['individual_count']) ? (int)$m['individual_count'] : 0;
                  $assignedToDisplay = $assignedToLabel . ' (' . $cnt . ')';
                  $individualCountForSort = $cnt;
                }
                ?>
                <tr class="miqaat-generate-row"
                  data-name="<?php echo htmlspecialchars(strtolower($rowName), ENT_QUOTES); ?>"
                  data-its="<?php echo htmlspecialchars(strtolower($rowIts), ENT_QUOTES); ?>"
                  data-sector="<?php echo htmlspecialchars(strtolower($rowSector), ENT_QUOTES); ?>"
                  data-subsector="<?php echo htmlspecialchars(strtolower($rowSubSector), ENT_QUOTES); ?>"
                  data-year="<?php echo htmlspecialchars($rowYear, ENT_QUOTES); ?>"
                  data-greg-date="<?php echo htmlspecialchars((string)($m['miqaat_date'] ?? ''), ENT_QUOTES); ?>"
                  data-hijri-date="<?php echo htmlspecialchars((string)($m['hijri_date'] ?? ''), ENT_QUOTES); ?>"
                  data-miqaat-id="<?php echo htmlspecialchars((string)($m['miqaat_id'] ?? ''), ENT_QUOTES); ?>"
                  data-raza-id="<?php echo htmlspecialchars((string)($m['raza_id'] ?? ''), ENT_QUOTES); ?>"
                  data-miqaat-name="<?php echo htmlspecialchars((string)($m['miqaat_name'] ?? ''), ENT_QUOTES); ?>"
                  data-assigned-to="<?php echo htmlspecialchars(strtolower($assignedToDisplay), ENT_QUOTES); ?>"
                  data-individual-count="<?php echo htmlspecialchars((string)$individualCountForSort, ENT_QUOTES); ?>">
                  <td><b><?php echo $i + 1 ?></b></td>
                  <td><?php echo date("d F Y", strtotime($m['miqaat_date'])) ?></td>
                  <td><?php echo htmlspecialchars($m['hijri_date']) ?></td>
                  <td><?php echo "M#" . htmlspecialchars($m['miqaat_id']) ?></td>
                  <td>
                    <?php if (!empty($m['raza_id'])): ?>
                      <?php echo "R#" . htmlspecialchars($m['raza_id']) ?>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                  <td><b><?php echo htmlspecialchars($m['miqaat_name']) ?></b></td>
                  <td>
                    <b>
                      <?php
                      echo htmlspecialchars($assignedToDisplay);
                      ?>
                    </b>
                  </td>
                  <td>
                    <?php if (strtolower($m['assign_type']) === 'individual'): ?>
                      <?php echo htmlspecialchars($m['Full_Name']) ?> (<?php echo htmlspecialchars($m['ITS_ID']) ?>)
                    <?php elseif (strtolower($m['assign_type']) === 'group'): ?>
                      <b>Group:</b> <?php echo htmlspecialchars($m['group_name']) ?><br>
                      <?php if (!empty($m['leader_ITS_ID'])): ?>
                        <br><b>Leader:</b> <?php echo htmlspecialchars($m['leader_Full_Name']) ?> (<?php echo htmlspecialchars($m['leader_ITS_ID']) ?>)
                      <?php endif; ?>
                      <?php if (!empty($m['ITS_ID'])): ?>
                        <!-- <br><b>Co-leader:</b> <?php echo htmlspecialchars($m['Full_Name']) ?> (<?php echo htmlspecialchars($m['ITS_ID']) ?>) -->
                      <?php endif; ?>
                    <?php elseif (strtolower($m['assign_type']) === 'fala ni niyaz'): ?>
                      <b>Assigned to Fala ni Niyaz</b>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                  <td>
                    <button type="button" class="btn btn-sm btn-primary generate-invoice-btn"
                      data-miqaat_index="<?php echo htmlspecialchars($m['miqaat_index']) ?>"
                      data-miqaat_id="<?php echo htmlspecialchars($m['miqaat_id']) ?>"
                      data-raza_index="<?php echo htmlspecialchars((string)($m['raza_index'] ?? ''), ENT_QUOTES) ?>"
                      data-raza_id="<?php echo htmlspecialchars((string)($m['raza_id'] ?? ''), ENT_QUOTES) ?>"
                      data-miqaat_name="<?php echo htmlspecialchars($m['miqaat_name']) ?>"
                      data-miqaat_date="<?php echo date("d", strtotime($m['miqaat_date'])) . " " . date("F", strtotime($m['miqaat_date'])) . " " . date("Y", strtotime($m['miqaat_date'])) ?>"
                      data-miqaat_type="<?php echo htmlspecialchars($m['miqaat_type']) ?>"
                      data-hijri_date="<?php echo htmlspecialchars($m['hijri_date']) ?>"
                      data-hijri_year="<?php $hijriParts = explode(' ', htmlspecialchars($m['hijri_date']));
                                        echo end($hijriParts); ?>"
                      data-sector="<?php echo htmlspecialchars($rowSector, ENT_QUOTES); ?>"
                      data-subsector="<?php echo htmlspecialchars($rowSubSector, ENT_QUOTES); ?>"
                      data-assigned_to="<?php echo htmlspecialchars($m['assigned_to']) ?>"
                      data-individual_count="<?php echo htmlspecialchars((string)($m['individual_count'] ?? ''), ENT_QUOTES); ?>"
                      data-member_id="<?php
                                      if (strtolower($m['assigned_to']) === 'group') {
                                        echo isset($m['leader_ITS_ID']) ? htmlspecialchars($m['leader_ITS_ID']) : '';
                                      } else {
                                        echo isset($m['ITS_ID']) ? htmlspecialchars($m['ITS_ID']) : '';
                                      }
                                      ?>"
                      data-details="<?php
                                    if (strtolower($m['assign_type']) === 'individual') {
                                      echo htmlspecialchars($m['Full_Name']) . ' (' . htmlspecialchars($m['ITS_ID']) . ')';
                                    } elseif (strtolower($m['assign_type']) === 'group') {
                                      echo 'Group: ' . htmlspecialchars($m['group_name']);
                                      if (!empty($m['leader_ITS_ID'])) {
                                        echo ' | Leader: ' . htmlspecialchars($m['leader_Full_Name']) . ' (' . htmlspecialchars($m['leader_ITS_ID']) . ')';
                                      }
                                    } elseif (strtolower($m['assign_type']) === 'fala ni niyaz') {
                                      echo 'Assigned to Fala ni Niyaz';
                                    } else {
                                      echo '-';
                                    }
                                    ?>">Generate Invoice</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- Single reusable Invoice Modal (placed outside loops) -->
  <div class="modal fade" id="generateInvoiceModal" tabindex="-1" aria-labelledby="generateInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="generateInvoiceModalLabel">Generate Miqaat Invoice</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="generateInvoiceForm" method="post" action="<?php echo base_url('anjuman/create_miqaat_invoice') ?>">
          <div class="modal-body">
            <input type="hidden" name="miqaat_id" id="modal_miqaat_index">
            <input type="hidden" name="raza_id" id="modal_raza_index">
            <input type="hidden" name="miqaat_type" id="input_miqaat_type">
            <input type="hidden" name="year" id="input_hijri_year">
            <input type="hidden" name="assigned_to" id="input_assigned_to">
            <input type="hidden" name="member_id" id="input_member_id">
            <input type="hidden" name="details" id="input_details">
            <div class="mb-2">
              <label class="form-label mb-0"><b>Miqaat Details:</b></label>
              <div id="modal_miqaat_details" class="form-control-plaintext"></div>
            </div>
            <hr>
            <div class="mb-2">
              <label class="form-label"><b>Amount:</b></label>
              <input type="number" class="form-control" name="amount" required min="1">
            </div>
            <div class="mb-2">
              <label class="form-label"><b>Description:</b></label>
              <textarea class="form-control" name="description"></textarea>
            </div>
            <div class="mb-2">
              <label class="form-label"><b>Invoice Date:</b></label>
              <input type="date" class="form-control" name="invoice_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" id="create-miqaat-invoice-btn" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Fala ni Niyaz Modal -->
  <div class="modal fade" id="falaNiyazModal" tabindex="-1" aria-labelledby="falaNiyazModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="falaNiyazModalLabel"><?php echo isset($miqaat_type) ? $miqaat_type : ""; ?> Niyaz Takhmeen</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="falaNiyazTableWrapper" class="table-responsive"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const genModalEl = document.getElementById('generateInvoiceModal');
    const genFormEl = document.getElementById('generateInvoiceForm');
    let modal = null;
    if (genModalEl) {
      modal = new bootstrap.Modal(genModalEl);
    }
    document.querySelectorAll('.generate-invoice-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        if (genFormEl) genFormEl.reset();
        const setVal = (id, val) => {
          const el = document.getElementById(id);
          if (el) el.value = val;
        };
        const setHtml = (id, html) => {
          const el = document.getElementById(id);
          if (el) el.innerHTML = html;
        };
        setHtml('modal_miqaat_details', '');
        setVal('modal_miqaat_index', '');
        setVal('modal_raza_index', '');
        setVal('input_miqaat_type', '');
        setVal('input_hijri_year', '');
        setVal('input_assigned_to', '');
        setVal('input_member_id', '');
        setVal('input_details', '');

        setVal('modal_miqaat_index', btn.getAttribute('data-miqaat_index'));
        setVal('modal_raza_index', btn.getAttribute('data-raza_index'));
        setVal('input_miqaat_type', btn.getAttribute('data-miqaat_type'));
        setVal('input_hijri_year', btn.getAttribute('data-hijri_year'));
        setVal('input_assigned_to', btn.getAttribute('data-assigned_to'));
        setVal('input_member_id', btn.getAttribute('data-member_id'));
        setVal('input_details', btn.getAttribute('data-details'));

        const razaDisplay = (btn.getAttribute('data-raza_id') || '').trim();
        const assignedRaw = (btn.getAttribute('data-assigned_to') || '').trim();
        const cnt = parseInt((btn.getAttribute('data-individual_count') || '0').toString(), 10) || 0;
        const assignedDisplay = (assignedRaw.toLowerCase() === 'individual' && cnt > 0)
          ? (assignedRaw + ' (' + cnt + ')')
          : assignedRaw;
        const detailsLine =
          '<b>Miqaat ID:</b> M#' + btn.getAttribute('data-miqaat_id') + '<br>' +
          '<b>Raza ID:</b> ' + (razaDisplay ? ('R#' + razaDisplay) : '-') + '<br>' +
          '<b>Miqaat Name:</b> ' + btn.getAttribute('data-miqaat_name') + '<br>' +
          '<b>Date:</b> ' + btn.getAttribute('data-miqaat_date') + '<br>' +
          '<b>Hijri:</b> ' + btn.getAttribute('data-hijri_date') + '<br>' +
          '<b>Assigned:</b> ' + assignedDisplay + '<br>' +
          '<b>Assignment Details:</b> ' + btn.getAttribute('data-details');
        setHtml('modal_miqaat_details', detailsLine);
        if (modal) modal.show();
      });
    });

    if (genModalEl) {
      $('#generateInvoiceModal').on('hidden.bs.modal', function() {
        if (genFormEl) genFormEl.reset();
        $('#modal_miqaat_details').html('');
        $('#modal_miqaat_index').val('');
        $('#modal_raza_index').val('');

        const idsToClear = [
          'input_miqaat_type',
          'input_hijri_year',
          'input_assigned_to',
          'input_member_id',
          'input_details'
        ];

        idsToClear.forEach(function(id) {
          $('#' + id).val('');
        });
      });
    }
  });

  $(".alert").delay(5000).slideUp(300);
</script>

<script>
  // Pending invoices table filters (client-side)
  (function() {
    function toLower(s) {
      return (s || '').toString().trim().toLowerCase();
    }

    function applyGenerateFilters() {
      const nameQ = toLower(document.getElementById('pf-name')?.value);
      const sectorV = toLower(document.getElementById('pf-sector')?.value);
      const subSectorV = toLower(document.getElementById('pf-subsector')?.value);
      const yearV = (document.getElementById('pf-year')?.value || '').toString().trim();

      const titleYearEl = document.getElementById('generate-title-hijri-year');
      if (titleYearEl) {
        titleYearEl.textContent = yearV ? (' (Hijri ' + yearV + ')') : '';
      }

      const rows = Array.from(document.querySelectorAll('tr.miqaat-generate-row'));
      let visible = 0;
      rows.forEach(function(tr) {
        const rName = toLower(tr.getAttribute('data-name'));
        const rIts = toLower(tr.getAttribute('data-its'));
        const rSector = toLower(tr.getAttribute('data-sector'));
        const rSub = toLower(tr.getAttribute('data-subsector'));
        const rYear = (tr.getAttribute('data-year') || '').toString().trim();

        let ok = true;
        if (nameQ) {
          ok = (rName.indexOf(nameQ) !== -1) || (rIts.indexOf(nameQ) !== -1);
        }
        if (ok && sectorV) ok = rSector === sectorV;
        if (ok && subSectorV) ok = rSub === subSectorV;
        if (ok && yearV) ok = rYear === yearV;

        tr.style.display = ok ? '' : 'none';
        if (ok) visible++;
      });

      const noMatchRow = document.getElementById('miqaat-generate-no-matches');
      if (noMatchRow) {
        noMatchRow.style.display = (rows.length && visible === 0) ? '' : 'none';
      }

      renumberVisibleRows();
    }

    function renumberVisibleRows() {
      const rows = Array.from(document.querySelectorAll('tr.miqaat-generate-row'));
      let n = 0;
      rows.forEach(function(tr) {
        if (tr.style.display === 'none') return;
        n++;
        const cell = tr.querySelector('td');
        if (cell) cell.innerHTML = '<b>' + n + '</b>';
      });
    }

    function parseNumber(val) {
      const s = (val || '').toString();
      const m = s.match(/\d+(?:\.\d+)?/);
      return m ? parseFloat(m[0]) : NaN;
    }

    function getSortValue(tr, key, type) {
      if (!tr) return '';
      if (key === 'index') {
        // Use current DOM order index; will be stable after sort
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
      if (key === 'miqaatName') return (tr.getAttribute('data-miqaat-name') || '').toString().toLowerCase();
      if (key === 'assignedTo') {
        const raw = (tr.getAttribute('data-assigned-to') || '').toString().trim().toLowerCase();
        let label = raw;
        if (!label) {
          const cells = tr.querySelectorAll('td');
          // Assigned To column index in generate table: 6
          const cell = cells && cells.length >= 7 ? cells[6] : null;
          label = (cell ? cell.textContent : '').toString().trim().toLowerCase();
        }

        const type = (label.split('(')[0] || '').trim();
        const typeKey = type || label;

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
      if (key === 'details') {
        // Details is column 7 (0-based: 0..8 with actions). Keep robust by selecting 8th cell.
        const cells = tr.querySelectorAll('td');
        const detailsCell = cells && cells.length >= 8 ? cells[7] : null;
        return (detailsCell ? detailsCell.textContent : '').toString().trim().toLowerCase();
      }
      return '';
    }

    function updateSortIndicators(activeTh, dir) {
      document.querySelectorAll('#miqaat-generate-table thead th.km-sortable').forEach(function(th) {
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

    function sortGenerateTable(key, type, dir) {
      const table = document.getElementById('miqaat-generate-table');
      if (!table) return;
      const tbody = table.querySelector('tbody');
      if (!tbody) return;

      const noMatchRow = document.getElementById('miqaat-generate-no-matches');
      const rows = Array.from(tbody.querySelectorAll('tr.miqaat-generate-row'));

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

      // Re-append rows (keep no-match row at top)
      if (noMatchRow && noMatchRow.parentElement === tbody) {
        tbody.appendChild(noMatchRow);
      }
      rows.forEach(function(r) {
        tbody.appendChild(r);
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      const filtersEl = document.getElementById('miqaat-generate-filters');
      if (!filtersEl) return;

      // Sorting
      const table = document.getElementById('miqaat-generate-table');
      if (table) {
        // Show neutral arrows by default
        table.querySelectorAll('thead th.km-sortable .sort-indicator').forEach(function(ind) {
          if (!ind.textContent || ind.textContent.trim() === '') {
            ind.textContent = '↕';
          }
        });

        const headers = table.querySelectorAll('thead th.km-sortable');
        headers.forEach(function(th) {
          th.addEventListener('click', function() {
            const key = th.getAttribute('data-sort-key') || '';
            const type = th.getAttribute('data-sort-type') || 'string';
            const current = th.getAttribute('data-sort-dir');
            const dir = (current === 'asc') ? 'desc' : 'asc';

            updateSortIndicators(th, dir);
            sortGenerateTable(key, type, dir);
            applyGenerateFilters();
          });
        });
      }

      ['pf-name', 'pf-sector', 'pf-subsector', 'pf-year'].forEach(function(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', applyGenerateFilters);
        el.addEventListener('change', applyGenerateFilters);
      });

      const clearBtn = document.getElementById('pf-clear');
      if (clearBtn) {
        clearBtn.addEventListener('click', function() {
          const nameEl = document.getElementById('pf-name');
          const sectorEl = document.getElementById('pf-sector');
          const subEl = document.getElementById('pf-subsector');
          const yearEl = document.getElementById('pf-year');
          if (nameEl) nameEl.value = '';
          if (sectorEl) sectorEl.value = '';
          if (subEl) subEl.value = '';
          if (yearEl) yearEl.value = '';
          applyGenerateFilters();
        });
      }

      applyGenerateFilters();
    });
  })();
</script>
<script>
  // Allow stacked modals: ensure each new modal/backdrop is layered above the previous
  (function() {
    $(document)
      .off('show.bs.modal.stacked')
      .on('show.bs.modal.stacked', '.modal', function() {
        const $open = $('.modal.show');
        const $this = $(this);
        const idx = $open.length;
        // Set z-index of this modal above existing ones
        $this.css('z-index', 1050 + idx * 20);
        // Adjust the backdrop just below this modal
        setTimeout(function() {
          $('.modal-backdrop').not('.modal-stack').first().css('z-index', 1040 + idx * 20).addClass('modal-stack');
        }, 0);
      })
      .off('hidden.bs.modal.stacked')
      .on('hidden.bs.modal.stacked', '.modal', function() {
        // Maintain scroll lock if there are other modals still open
        if ($('.modal.show').length) {
          $('body').addClass('modal-open');
        }
      });
  })();
</script>
<?php
$fala_items = [];
$fala_summary = [];

if (isset($miqaats['Fala_ni_Niyaz']) && is_array($miqaats['Fala_ni_Niyaz'])) {
  if (isset($miqaats["Fala_ni_Niyaz"][0]["year"])) {
    foreach ($miqaats['Fala_ni_Niyaz'] as $group) {
      $year = isset($group['year']) ? $group['year'] : '';
      $count = isset($group['count']) ? (int)$group['count'] : 0;
      $earliest = isset($group['earliest_date']) ? $group['earliest_date'] : '';
      $latest = isset($group['latest_date']) ? $group['latest_date'] : '';
      $fala_summary[] = [
        'year' => $year,
        'count' => $count,
        'earliest_date' => $earliest,
        'latest_date' => $latest,
      ];
      if (!empty($group['miqaats']) && is_array($group['miqaats'])) {
        foreach ($group['miqaats'] as $m) {
          $fala_items[] = [
            'miqaat_index' => isset($m['miqaat_index']) ? $m['miqaat_index'] : '',
            'raza_index'   => isset($m['raza_index']) ? $m['raza_index'] : '',
            'miqaat_id'    => isset($m['miqaat_id']) ? $m['miqaat_id'] : '',
            'raza_id'      => isset($m['raza_id']) ? $m['raza_id'] : '',
            'miqaat_name'  => isset($m['miqaat_name']) ? $m['miqaat_name'] : '',
            'miqaat_date'  => isset($m['miqaat_date']) ? $m['miqaat_date'] : '',
            'miqaat_type'  => isset($m['miqaat_type']) ? $m['miqaat_type'] : '',
            'hijri_date'   => isset($m['hijri_date']) ? $m['hijri_date'] : '',
            'assigned_to'  => isset($m['assigned_to']) ? $m['assigned_to'] : 'Fala ni Niyaz',
            'details'      => 'Assigned to Fala ni Niyaz',
            'member_id'    => ''
          ];
        }
      }
    }
  } else {
    foreach ($miqaats["Fala_ni_Niyaz"] as $m) {
      $assignType = isset($m['assign_type']) ? strtolower($m['assign_type']) : '';
      if ($assignType === 'fala ni niyaz') {
        $fala_items[] = [
          'miqaat_index' => isset($m['miqaat_index']) ? $m['miqaat_index'] : '',
          'raza_index'   => isset($m['raza_index']) ? $m['raza_index'] : '',
          'miqaat_id'    => isset($m['miqaat_id']) ? $m['miqaat_id'] : '',
          'raza_id'      => isset($m['raza_id']) ? $m['raza_id'] : '',
          'miqaat_name'  => isset($m['miqaat_name']) ? $m['miqaat_name'] : '',
          'miqaat_date'  => isset($m['miqaat_date']) ? $m['miqaat_date'] : '',
          'miqaat_type'  => isset($m['miqaat_type']) ? $m['miqaat_type'] : '',
          'hijri_date'   => isset($m['hijri_date']) ? $m['hijri_date'] : '',
          'assigned_to'  => isset($m['assigned_to']) ? $m['assigned_to'] : 'Fala ni Niyaz',
          'details'      => 'Assigned to Fala ni Niyaz',
          'member_id'    => ''
        ];
      }
    }
  }
}
?>
<script>
  const FALA_NI_NIYAZ_ITEMS = <?php echo json_encode($fala_items); ?>;
  const FALA_NI_NIYAZ_SUMMARY = <?php echo json_encode($fala_summary); ?>;
  const IS_SHEHRULLAH = <?php echo json_encode(isset($miqaat_type) && (strtolower($miqaat_type) === 'shehrullah' || strtolower($miqaat_type) === 'ashara')); ?>;
  const CURRENT_MIQAAT_TYPE = <?php echo json_encode(isset($miqaat_type) ? $miqaat_type : ''); ?>;

  function formatLongDate(isoDate) {
    if (!isoDate) return '';
    const dt = new Date(isoDate.replace(/-/g, '/'));
    if (isNaN(dt)) return isoDate;
    const options = {
      day: '2-digit',
      month: 'long',
      year: 'numeric'
    };
    return dt.toLocaleDateString(undefined, options);
  }

  function escapeHtml(str) {
    if (str === undefined || str === null) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function buildFalaTable(items) {
    let summaryHtml = '';

    if (IS_SHEHRULLAH) {
      if (Array.isArray(FALA_NI_NIYAZ_SUMMARY) && FALA_NI_NIYAZ_SUMMARY.length) {
        const yearRows = FALA_NI_NIYAZ_SUMMARY.map(g => {
          const start = formatLongDate(g.earliest_date || '');
          const end = formatLongDate(g.latest_date || '');
          return `
            <tr>
              <td>${escapeHtml(g.year)}</td>
              <td>
                <button type="button" class="btn btn-sm btn-primary generate-year-invoice-btn"
                  data-hijri_year="${escapeHtml(g.year)}"
                  data-count="${escapeHtml(String(g.count))}"
                  data-range_start="${escapeHtml(start)}"
                  data-range_end="${escapeHtml(end)}"
                  data-miqaat_type="${escapeHtml(CURRENT_MIQAAT_TYPE)}"
                >Do Takhmeen for this Year</button>
              </td>
            </tr>
          `;
        }).join('');
        return `
          ${summaryHtml}
          <table class="table table-bordered table-striped">
            <thead class="table-dark">
              <tr>
                <th>Hijri Year</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              ${yearRows}
            </tbody>
          </table>
        `;
      }
      return '<div class="alert alert-info">No Fala ni Niyaz miqaats found.</div>';
    }

    if (!items || !items.length) {
      return '<div class="alert alert-info">No Fala ni Niyaz miqaats found.</div>';
    }
    const rows = items.map((m, i) => {
      const miqaatDateStr = formatLongDate(m.miqaat_date);
      const hijriYear = (m.hijri_date || '').trim().split(' ').pop();
      const details = 'Assigned to Fala ni Niyaz';
      return `
        <tr>
          <td><b>${i + 1}</b></td>
          <td>${escapeHtml(miqaatDateStr)}</td>
          <td>${escapeHtml(m.hijri_date || '')}</td>
          <td>M#${escapeHtml(m.miqaat_id)}</td>
          <td>R#${escapeHtml(m.raza_id)}</td>
          <td><b>${escapeHtml(m.miqaat_name)}</b></td>
          <td>
            <button type="button" class="btn btn-sm btn-primary generate-invoice-btn"
              data-miqaat_index="${escapeHtml(m.miqaat_index)}"
              data-miqaat_id="${escapeHtml(m.miqaat_id)}"
              data-raza_index="${escapeHtml(m.raza_index)}"
              data-raza_id="${escapeHtml(m.raza_id)}"
              data-miqaat_name="${escapeHtml(m.miqaat_name)}"
              data-miqaat_date="${escapeHtml(miqaatDateStr)}"
              data-miqaat_type="${escapeHtml(m.miqaat_type)}"
              data-hijri_date="${escapeHtml(m.hijri_date)}"
              data-hijri_year="${escapeHtml(hijriYear)}"
              data-assigned_to="${escapeHtml(m.assigned_to || 'Fala ni Niyaz')}"
              data-individual_count="${escapeHtml(String(m.individual_count || ''))}"
              data-member_id=""
              data-details="${escapeHtml(details)}"
            >Generate Invoice</button>
          </td>
        </tr>
      `;
    }).join('');

    return `
      ${summaryHtml}
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Hijri Date</th>
            <th>Miqaat ID</th>
            <th>Raza ID</th>
            <th>Miqaat Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          ${rows}
        </tbody>
      </table>
    `;
  }

  document.addEventListener('DOMContentLoaded', function() {
    const falaBtn = document.getElementById('fala-ni-niyaz-invoices');
    if (!falaBtn) return;

    falaBtn.addEventListener('click', function() {
      const genModalElLocal = document.getElementById('generateInvoiceModal');
      const genFormElLocal = document.getElementById('generateInvoiceForm');
      const wrapper = document.getElementById('falaNiyazTableWrapper');
      if (wrapper) {
        wrapper.innerHTML = buildFalaTable(FALA_NI_NIYAZ_ITEMS);

        wrapper.querySelectorAll('.generate-invoice-btn').forEach(function(btn) {
          btn.addEventListener('click', function() {
            if (genFormElLocal) genFormElLocal.reset();
            const setVal = (id, val) => {
              const el = document.getElementById(id);
              if (el) el.value = val;
            };
            const setHtml = (id, html) => {
              const el = document.getElementById(id);
              if (el) el.innerHTML = html;
            };
            setHtml('modal_miqaat_details', '');
            setVal('modal_miqaat_index', '');
            setVal('modal_raza_index', '');
            setVal('input_miqaat_type', '');
            setVal('input_hijri_year', '');
            setVal('input_assigned_to', '');
            setVal('input_member_id', '');
            setVal('input_details', '');

            document.getElementById('modal_miqaat_index').value = btn.getAttribute('data-miqaat_index');
            document.getElementById('modal_raza_index').value = btn.getAttribute('data-raza_index');
            document.getElementById('input_miqaat_type').value = btn.getAttribute('data-miqaat_type');
            document.getElementById('input_hijri_year').value = btn.getAttribute('data-hijri_year');
            document.getElementById('input_assigned_to').value = btn.getAttribute('data-assigned_to');
            document.getElementById('input_member_id').value = btn.getAttribute('data-member_id') || '';
            document.getElementById('input_details').value = btn.getAttribute('data-details');

            const assignedRaw = (btn.getAttribute('data-assigned_to') || '').trim();
            const cnt = parseInt((btn.getAttribute('data-individual_count') || '0').toString(), 10) || 0;
            const assignedDisplay = (assignedRaw.toLowerCase() === 'individual' && cnt > 0)
              ? (assignedRaw + ' (' + cnt + ')')
              : assignedRaw;
            const detailsLine =
              '<b>Miqaat ID:</b> M#' + btn.getAttribute('data-miqaat_id') + '<br>' +
              '<b>Raza ID:</b> R#' + btn.getAttribute('data-raza_id') + '<br>' +
              '<b>Miqaat Name:</b> ' + btn.getAttribute('data-miqaat_name') + '<br>' +
              '<b>Date:</b> ' + btn.getAttribute('data-miqaat_date') + '<br>' +
              '<b>Hijri:</b> ' + btn.getAttribute('data-hijri_date') + '<br>' +
              '<b>Assigned:</b> ' + assignedDisplay + '<br>' +
              '<b>Assignment Details:</b> ' + btn.getAttribute('data-details');
            setHtml('modal_miqaat_details', detailsLine);

            // Show Generate modal on top of Fala modal (stacked)
            if (genModalElLocal) {
              const genModal = new bootstrap.Modal(genModalElLocal);
              genModal.show();
            }
          });
        });

        wrapper.querySelectorAll('.generate-year-invoice-btn').forEach(function(btn) {
          btn.addEventListener('click', function() {
            // Gather details for confirmation
            const year = btn.getAttribute('data-hijri_year') || '';
            const count = btn.getAttribute('data-count') || '';
            const rangeStart = btn.getAttribute('data-range_start') || '';
            const rangeEnd = btn.getAttribute('data-range_end') || '';
            const mtype = btn.getAttribute('data-miqaat_type') || '';

            // Confirm with the user before proceeding
            const rangeText = (rangeStart && rangeEnd) ? `, Range: ${rangeStart} - ${rangeEnd}` : '';

            if (genFormElLocal) genFormElLocal.reset();
            const setVal = (id, val) => {
              const el = document.getElementById(id);
              if (el) el.value = val;
            };
            const setHtml = (id, html) => {
              const el = document.getElementById(id);
              if (el) el.innerHTML = html;
            };

            setVal('modal_miqaat_index', '');
            setVal('modal_raza_index', '');
            setVal('input_member_id', '');

            setVal('input_miqaat_type', mtype);
            setVal('input_hijri_year', year);
            setVal('input_assigned_to', 'Fala ni Niyaz');

            const details = `Yearly ${escapeHtml(mtype)} Fala ni Niyaz — Year: ${escapeHtml(year)} | Miqaat Count: ${escapeHtml(count)} | ${escapeHtml(rangeStart)} - ${escapeHtml(rangeEnd)}`;
            setVal('input_details', details);

            const html = `
              <b>Type:</b> ${escapeHtml(mtype)}<br>
              <b>Assigned:</b> Fala ni Niyaz<br>
              <b>Year:</b> ${escapeHtml(year)}<br>
            `;
            setHtml('modal_miqaat_details', html);

            if (genModalElLocal) {
              const genModal = new bootstrap.Modal(genModalElLocal);
              genModal.show();
            }
          });
        });
      }

      const falaEl = document.getElementById('falaNiyazModal');
      if (falaEl) {
        const falaModal = new bootstrap.Modal(falaEl);
        falaModal.show();
      }
    });

    $("#create-miqaat-invoice-btn").on("click", function() {
      const confirmMsg = `This will create invoices for ALL families for ${mtype} (${year}).\n\nDo you want to continue?`;
      if (!window.confirm(confirmMsg)) {
        return;
      }
    });
  });
</script>
<script>
  // Ensure user confirms before proceeding with YEAR-LEVEL (ALL families) invoices
  $(document).on('submit', '#generateInvoiceForm', function(e) {
    var mtype = ($('#input_miqaat_type').val() || '').toLowerCase();
    var year = $('#input_hijri_year').val() || '';
    var assignedTo = ($('#input_assigned_to').val() || '').toLowerCase();
    var miqaatIndex = $('#modal_miqaat_index').val() || '';
    var razaIndex = $('#modal_raza_index').val() || '';

    // Year-level flow has no specific miqaat/raza index and targets all families
    var isYearLevel = assignedTo === 'fala ni niyaz' &&
      (mtype === 'shehrullah' || mtype === 'ashara') &&
      miqaatIndex === '' &&
      razaIndex === '';

    if (isYearLevel) {
      var confirmMsg = 'This will create invoices for ALL families for ' + (mtype.charAt(0).toUpperCase() + mtype.slice(1)) + ' (' + year + ').\n\nDo you want to continue?';
      if (!window.confirm(confirmMsg)) {
        e.preventDefault();
        return false;
      }
    }
  });
</script>