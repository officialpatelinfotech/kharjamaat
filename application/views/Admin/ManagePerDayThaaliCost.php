<div class="container margintopcontainer pt-5" id="fmbPerDayApp">
  <style>
    :root {
      --gold:        #b8860b;
      --gold-light:  #e6c84a;
      --gold-muted:  #f5e9c0;
      --bg:          #faf7f0;
      --surface:     #ffffff;
      --surface-2:   #f7f4ec;
      --border:      #e8e0cc;
      --text-1:      #1a1610;
      --text-2:      #5a5244;
      --text-3:      #9c8f7a;
      --green:       #1a6645;
      --blue:        #1d4ed8;
      --red:         #b91c1c;
      --sh:          0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
      --sh2:         0 4px 16px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
    }

    #fmbPerDayApp {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
      background-color: #faf7f0 !important;
    }

    /* Elegant Header Banner */
    .anj-header {
      margin-bottom: 24px;
    }
    .anj-header-inner {
      background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
      border-radius: 18px;
      padding: 24px 30px;
      position: relative;
      overflow: hidden;
      box-shadow: var(--sh);
      color: #fff;
    }
    .anj-eyebrow {
      font-size: .67rem;
      font-weight: 700;
      letter-spacing: 1.4px;
      text-transform: uppercase;
      color: rgba(255,255,255,.65);
      margin-bottom: 4px;
    }
    .anj-title {
      font-family: 'Literata', Georgia, serif;
      font-size: 1.7rem;
      font-weight: 600;
      color: #fff;
      margin: 0;
    }

    /* Table Card */
    .miqaat-table-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 12px;
      box-shadow: var(--sh);
      overflow: hidden;
      margin-bottom: 30px;
    }
    .miqaat-table-responsive {
      max-height: 65vh;
      overflow-y: auto;
      overflow-x: auto;
    }
    .miqaat-table {
      width: 100%;
      border-collapse: collapse;
      margin: 0;
    }
    .miqaat-table thead th {
      position: sticky;
      top: 0;
      z-index: 10;
      background-color: var(--text-1) !important;
      color: #fff !important;
      font-weight: 700;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 12px 16px;
      border: none;
      border-bottom: 2px solid var(--border);
    }
    .miqaat-table tbody tr {
      transition: background-color 0.15s;
    }
    .miqaat-table tbody tr:nth-of-type(even) {
      background-color: var(--surface-2);
    }
    .miqaat-table tbody tr:hover {
      background-color: rgba(184, 134, 11, 0.05);
    }
    .miqaat-table tbody td {
      padding: 12px 16px;
      font-size: 13px;
      color: var(--text-2);
      border-bottom: 1px solid var(--border);
      vertical-align: middle;
    }

    /* Premium Buttons */
    .btn-premium {
      font-weight: 700;
      font-size: 0.82rem;
      padding: 8px 16px;
      border-radius: 10px;
      transition: all 0.2s;
      border: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      text-decoration: none;
    }
    .btn-premium-primary {
      background: var(--green);
      color: #fff;
    }
    .btn-premium-primary:hover {
      background: #124d33;
      color: #fff;
      text-decoration: none;
    }
    .btn-premium-secondary {
      background: #fff;
      border: 1.5px solid var(--border);
      color: var(--text-2);
    }
    .btn-premium-secondary:hover {
      background: var(--gold-muted);
      border-color: var(--gold);
      color: var(--gold);
      text-decoration: none;
    }

    .btn-premium-action {
      border-radius: 8px;
      padding: 6px 10px;
      font-size: 0.78rem;
      border: none;
      color: #fff;
      transition: opacity 0.2s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-right: 4px;
    }
    .btn-premium-action:hover {
      opacity: 0.85;
      color: #fff;
      text-decoration: none;
    }
    .btn-premium-action-edit { background-color: var(--gold) !important; }
    .btn-premium-action-delete { background-color: var(--red) !important; }

    .form-group-premium {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    .form-group-premium label {
      font-size: 0.72rem;
      font-weight: 700;
      color: var(--text-2);
      text-transform: uppercase;
      letter-spacing: 0.4px;
      margin-bottom: 0;
    }
    .form-control-premium {
      border: 1.5px solid var(--border);
      border-radius: 10px;
      padding: 8px 12px;
      font-size: 0.85rem;
      color: var(--text-1);
      background: var(--surface-2);
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
      width: 100%;
      height: 38px;
      font-family: inherit;
    }
    .form-control-premium:focus {
      border-color: var(--gold);
      box-shadow: 0 0 0 3px rgba(184,134,11,.1);
      background: var(--surface);
    }

    /* Modals */
    .modal-content-premium {
      border-radius: 16px !important;
      border: 1px solid var(--border) !important;
      box-shadow: var(--sh2) !important;
      background: var(--surface) !important;
      overflow: hidden;
    }
    .modal-header-premium {
      background: linear-gradient(135deg, #78520a, var(--gold)) !important;
      color: #fff !important;
      border-bottom: none !important;
      padding: 16px 20px !important;
    }
    .modal-header-premium .close {
      color: #fff !important;
      opacity: 0.8 !important;
      text-shadow: none !important;
    }
    .modal-header-premium .close:hover {
      opacity: 1 !important;
    }
    .modal-body-premium {
      padding: 24px !important;
    }
    .modal-footer-premium {
      border-top: none !important;
      padding: 16px 24px 24px !important;
      display: flex;
      justify-content: flex-end;
      gap: 8px;
    }
  </style>

  <!-- Actions Header -->
  <div class="d-flex align-items-center justify-content-between mb-4">
    <a href="<?php echo base_url('admin/managefmbtakhmeen'); ?>" class="btn-premium btn-premium-secondary" aria-label="Back to FMB Thaali Takhmeen">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
    <button type="button" class="btn-premium btn-premium-primary" data-toggle="modal" data-target="#createCostModal">
      <i class="fa-solid fa-plus mr-1"></i> Create Cost
    </button>
  </div>

  <?php if (!empty($flash_success)) : ?>
    <div class="alert alert-success" style="border-radius: 10px;"><?php echo htmlspecialchars((string)$flash_success); ?></div>
  <?php endif; ?>
  <?php if (!empty($flash_error)) : ?>
    <div class="alert alert-danger" style="border-radius: 10px;"><?php echo htmlspecialchars((string)$flash_error); ?></div>
  <?php endif; ?>

  <!-- Header Panel -->
  <div class="anj-header text-center mb-4">
    <div class="anj-header-inner flex-column justify-content-center py-4">
      <p class="anj-eyebrow">Fizalat Mawamil al-Burhaniyah</p>
      <h1 class="anj-title">Manage Daily Thaali Cost</h1>
    </div>
  </div>

  <!-- Table Card -->
  <div class="miqaat-table-card">
    <div class="miqaat-table-responsive">
      <table class="miqaat-table">
        <thead>
          <tr>
            <th style="width: 90px;">Sr. No.</th>
            <th>Year</th>
            <th>Amount (₹)</th>
            <th style="width: 160px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($cost_rows)) : ?>
            <?php $sr = 1; ?>
            <?php foreach ($cost_rows as $row) : ?>
              <tr>
                <td><b><?php echo (int)$sr++; ?></b></td>
                <td><?php echo htmlspecialchars((string)($row['year'] ?? '')); ?></td>
                <td><b>₹<?php echo number_format((float)($row['amount'] ?? 0), 2); ?></b></td>
                <td class="text-nowrap">
                  <button type="button"
                          class="btn-premium-action btn-premium-action-edit js-edit-cost"
                          data-toggle="modal"
                          data-target="#createCostModal"
                          data-id="<?php echo htmlspecialchars((string)($row['id'] ?? ''), ENT_QUOTES); ?>"
                          data-year="<?php echo htmlspecialchars((string)($row['year'] ?? ''), ENT_QUOTES); ?>"
                          data-amount="<?php echo htmlspecialchars((string)($row['amount'] ?? ''), ENT_QUOTES); ?>">
                    <i class="fa-solid fa-pencil"></i>
                  </button>

                  <form method="POST" action="<?php echo base_url('admin/deleteperdaythaalicost'); ?>" class="d-inline" onsubmit="return confirm('Delete this cost?');">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)($row['id'] ?? ''), ENT_QUOTES); ?>">
                    <button type="submit" class="btn-premium-action btn-premium-action-delete">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="4" class="text-center text-muted">No cost entries found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="createCostModal" tabindex="-1" aria-labelledby="createCostModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content modal-content-premium">
      <div class="modal-header modal-header-premium">
        <h5 class="modal-title" id="createCostModalLabel"><i class="fa-solid fa-circle-plus mr-2"></i> Create Cost</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="<?php echo base_url('admin/manageperdaythaalicost'); ?>" id="per-day-cost-form">
        <div class="modal-body modal-body-premium">
          <input type="hidden" name="id" id="per-day-cost-id" value="">
          <div class="form-group mb-3">
            <label for="per-day-cost-amount" style="font-size: 0.75rem; font-weight: 700; color: var(--text-2); text-transform: uppercase;">Amount (₹)</label>
            <input type="number" step="0.01" min="0" class="form-control-premium" id="per-day-cost-amount" name="amount" required placeholder="Enter per day cost amount">
          </div>
          <div class="form-group mb-0">
            <label for="per-day-cost-year" style="font-size: 0.75rem; font-weight: 700; color: var(--text-2); text-transform: uppercase;">Year</label>
            <select class="form-control-premium" id="per-day-cost-year" name="year" required>
              <option value="">Select Year</option>
              <?php foreach (($year_ranges ?? []) as $yr) : ?>
                <option value="<?php echo htmlspecialchars((string)$yr, ENT_QUOTES); ?>"><?php echo htmlspecialchars((string)$yr); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer modal-footer-premium">
          <button type="button" class="btn btn-secondary btn-premium btn-premium-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success btn-premium btn-premium-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  (function () {
    function resetCostModal() {
      var idEl = document.getElementById('per-day-cost-id');
      var amountEl = document.getElementById('per-day-cost-amount');
      var yearEl = document.getElementById('per-day-cost-year');
      var titleEl = document.getElementById('createCostModalLabel');
      if (idEl) idEl.value = '';
      if (amountEl) amountEl.value = '';
      if (yearEl) yearEl.value = '';
      if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-circle-plus mr-2"></i> Create Cost';
    }

    document.addEventListener('DOMContentLoaded', function () {
      // Create button: clear modal
      var createBtn = document.querySelector('[data-target="#createCostModal"].btn-premium-primary');
      if (createBtn) {
        createBtn.addEventListener('click', function () {
          resetCostModal();
        });
      }

      // Edit buttons: populate modal
      var editBtns = document.querySelectorAll('.js-edit-cost');
      editBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
          var idEl = document.getElementById('per-day-cost-id');
          var amountEl = document.getElementById('per-day-cost-amount');
          var yearEl = document.getElementById('per-day-cost-year');
          var titleEl = document.getElementById('createCostModalLabel');

          if (idEl) idEl.value = btn.getAttribute('data-id') || '';
          if (amountEl) amountEl.value = btn.getAttribute('data-amount') || '';
          if (yearEl) yearEl.value = btn.getAttribute('data-year') || '';
          if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-pen-to-square mr-2"></i> Edit Cost';
        });
      });

      // When modal closes, reset to create state
      if (window.jQuery) {
        window.jQuery('#createCostModal').on('hidden.bs.modal', function () {
          resetCostModal();
        });
      }
    });
  })();
</script>
