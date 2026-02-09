<div class="container margintopcontainer pt-5">
  <a href="<?php echo base_url('admin/managefmbsettings'); ?>" class="btn btn-outline-secondary mb-3" aria-label="Back to Manage FMB Settings"><i class="fa-solid fa-arrow-left"></i></a>

  <?php if (!empty($flash_success)) : ?>
    <div class="alert alert-success"><?php echo htmlspecialchars((string)$flash_success); ?></div>
  <?php endif; ?>
  <?php if (!empty($flash_error)) : ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars((string)$flash_error); ?></div>
  <?php endif; ?>

  <div class="row align-items-center mb-4">
    <div class="col-12 col-md-4"></div>
    <div class="col-12 col-md-4 text-center">
      <h4 class="heading mb-0">Manage Per Day Thaali Cost</h4>
    </div>
    <div class="col-12 col-md-4 text-center text-md-right mt-3 mt-md-0">
      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createCostModal">Create Cost</button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped mb-0">
      <thead>
        <tr>
          <th style="width: 90px;">Sr. No.</th>
          <th>Year</th>
          <th>Amount</th>
          <th style="width: 160px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($cost_rows)) : ?>
          <?php $sr = 1; ?>
          <?php foreach ($cost_rows as $row) : ?>
            <tr>
              <td><?php echo (int)$sr++; ?></td>
              <td><?php echo htmlspecialchars((string)($row['year'] ?? '')); ?></td>
              <td><?php echo htmlspecialchars((string)($row['amount'] ?? '')); ?></td>
              <td class="text-nowrap">
                <button type="button"
                        class="btn btn-sm btn-info js-edit-cost"
                        data-toggle="modal"
                        data-target="#createCostModal"
                        data-id="<?php echo htmlspecialchars((string)($row['id'] ?? ''), ENT_QUOTES); ?>"
                        data-year="<?php echo htmlspecialchars((string)($row['year'] ?? ''), ENT_QUOTES); ?>"
                        data-amount="<?php echo htmlspecialchars((string)($row['amount'] ?? ''), ENT_QUOTES); ?>">Edit</button>

                <form method="POST" action="<?php echo base_url('admin/deleteperdaythaalicost'); ?>" class="d-inline" onsubmit="return confirm('Delete this cost?');">
                  <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)($row['id'] ?? ''), ENT_QUOTES); ?>">
                  <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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

<div class="modal fade" id="createCostModal" tabindex="-1" aria-labelledby="createCostModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createCostModalLabel">Create Cost</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="<?php echo base_url('admin/manageperdaythaalicost'); ?>" id="per-day-cost-form">
        <div class="modal-body">
          <input type="hidden" name="id" id="per-day-cost-id" value="">
          <div class="form-group">
            <label for="per-day-cost-amount">Amount</label>
            <input type="number" step="0.01" min="0" class="form-control" id="per-day-cost-amount" name="amount" required>
          </div>
          <div class="form-group mb-0">
            <label for="per-day-cost-year">Year</label>
            <select class="form-control" id="per-day-cost-year" name="year" required>
              <option value="">-----</option>
              <?php foreach (($year_ranges ?? []) as $yr) : ?>
                <option value="<?php echo htmlspecialchars((string)$yr, ENT_QUOTES); ?>"><?php echo htmlspecialchars((string)$yr); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Submit</button>
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
      if (titleEl) titleEl.textContent = 'Create Cost';
    }

    document.addEventListener('DOMContentLoaded', function () {
      // Create button: clear modal
      var createBtn = document.querySelector('[data-target="#createCostModal"].btn-success');
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
          if (titleEl) titleEl.textContent = 'Edit Cost';
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
