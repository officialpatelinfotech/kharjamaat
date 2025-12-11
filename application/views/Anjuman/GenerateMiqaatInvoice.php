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
</style>

<div class="margintopcontainer mx-5 pt-5">
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
          <?php echo isset($miqaat_type) ? $miqaat_type : ""; ?> Niyaz Takhmeen <span class="fala-ni-niyaz-count text-primary"><?php echo isset($miqaats["Fala_ni_Niyaz"]) ? count($miqaats["Fala_ni_Niyaz"]) : 0; ?></span>
        </button>
      </div>
    </div>
  </div>
  <h4 class="text-center"><?php echo isset($miqaat_type) ? $miqaat_type : ""; ?> Miqaat <span class="text-primary">Pending Invoices</span></h4>
  <div class="col-12 mt-4">
    <div class="card-header">
      <!-- <h5 class="mb-0 text-center">Invoice List</h5> -->
    </div>
    <div class="card-body p-0 table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Hijri Date</th>
            <th>Miqaat ID</th>
            <th>Raza ID</th>
            <th>Miqaat Name</th>
            <th>Assigned To</th>
            <th>Details</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!isset($miqaats["miqaats"]) || empty($miqaats["miqaats"])): ?>
            <tr>
              <td colspan="9" class="text-center">No Pending Invoices Found</td>
            </tr>
          <?php else: ?>
            <?php foreach ($miqaats["miqaats"] as $i => $m): ?>
              <tr>
                <td><b><?php echo $i + 1 ?></b></td>
                <td><?php echo date("d F Y", strtotime($m['miqaat_date'])) ?></td>
                <td><?php echo htmlspecialchars($m['hijri_date']) ?></td>
                <td><?php echo "M#" . htmlspecialchars($m['miqaat_id']) ?></td>
                <td><?php echo "R#" . htmlspecialchars($m['raza_id']) ?></td>
                <td><b><?php echo htmlspecialchars($m['miqaat_name']) ?></b></td>
                <td><b><?php echo htmlspecialchars($m['assigned_to']) ?></b></td>
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
                    data-raza_index="<?php echo htmlspecialchars($m['raza_index']) ?>"
                    data-raza_id="<?php echo htmlspecialchars($m['raza_id']) ?>"
                    data-miqaat_name="<?php echo htmlspecialchars($m['miqaat_name']) ?>"
                    data-miqaat_date="<?php echo date("d", strtotime($m['miqaat_date'])) . " " . date("F", strtotime($m['miqaat_date'])) . " " . date("Y", strtotime($m['miqaat_date'])) ?>"
                    data-miqaat_type="<?php echo htmlspecialchars($m['miqaat_type']) ?>"
                    data-hijri_date="<?php echo htmlspecialchars($m['hijri_date']) ?>"
                    data-hijri_year="<?php $hijriParts = explode(' ', htmlspecialchars($m['hijri_date']));
                                      echo end($hijriParts); ?>"
                    data-assigned_to="<?php echo htmlspecialchars($m['assigned_to']) ?>"
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

        const detailsLine =
          '<b>Miqaat ID:</b> M#' + btn.getAttribute('data-miqaat_id') + '<br>' +
          '<b>Raza ID:</b> R#' + btn.getAttribute('data-raza_id') + '<br>' +
          '<b>Miqaat Name:</b> ' + btn.getAttribute('data-miqaat_name') + '<br>' +
          '<b>Date:</b> ' + btn.getAttribute('data-miqaat_date') + '<br>' +
          '<b>Hijri:</b> ' + btn.getAttribute('data-hijri_date') + '<br>' +
          '<b>Assigned:</b> ' + btn.getAttribute('data-assigned_to') + '<br>' +
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

            const detailsLine =
              '<b>Miqaat ID:</b> M#' + btn.getAttribute('data-miqaat_id') + '<br>' +
              '<b>Raza ID:</b> R#' + btn.getAttribute('data-raza_id') + '<br>' +
              '<b>Miqaat Name:</b> ' + btn.getAttribute('data-miqaat_name') + '<br>' +
              '<b>Date:</b> ' + btn.getAttribute('data-miqaat_date') + '<br>' +
              '<b>Hijri:</b> ' + btn.getAttribute('data-hijri_date') + '<br>' +
              '<b>Assigned:</b> ' + btn.getAttribute('data-assigned_to') + '<br>' +
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
  $(document).on('submit', '#generateInvoiceForm', function (e) {
    var mtype = ($('#input_miqaat_type').val() || '').toLowerCase();
    var year = $('#input_hijri_year').val() || '';
    var assignedTo = ($('#input_assigned_to').val() || '').toLowerCase();
    var miqaatIndex = $('#modal_miqaat_index').val() || '';
    var razaIndex = $('#modal_raza_index').val() || '';

    // Year-level flow has no specific miqaat/raza index and targets all families
    var isYearLevel = assignedTo === 'fala ni niyaz'
      && (mtype === 'shehrullah' || mtype === 'ashara')
      && miqaatIndex === ''
      && razaIndex === '';

    if (isYearLevel) {
      var confirmMsg = 'This will create invoices for ALL families for ' + (mtype.charAt(0).toUpperCase() + mtype.slice(1)) + ' (' + year + ').\n\nDo you want to continue?';
      if (!window.confirm(confirmMsg)) {
        e.preventDefault();
        return false;
      }
    }
  });
</script>