<?php /* Status Options Settings */ ?>
<div class="container margintopcontainer pt-5">
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= $this->session->flashdata('error'); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $this->session->flashdata('success'); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url('admin/preferences'); ?>" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Back to Preferences
      </a>
    </div>
  </div>

  <h3 class="mb-4">Manage Status Options</h3>

  <!-- Add / Edit Option Card -->
  <div class="card mb-5 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; border: 1px solid #e8e0cc !important;">
    <div class="card-header text-white" style="background: linear-gradient(135deg, #78520a, #b8860b);">
      <h5 class="mb-0" id="formCardTitle"><i class="fa fa-plus-circle mr-2"></i>Add New Status Option</h5>
    </div>
    <div class="card-body bg-white p-4">
      <form method="POST" action="<?php echo base_url('admin/save_status_option'); ?>" id="optionForm">
        <input type="hidden" name="id" id="option_id" value="">
        <div class="row">
          <div class="col-md-4 form-group mb-3">
            <label for="option_type" class="font-weight-bold">Status Type</label>
            <select name="type" id="option_type" class="form-control" required>
              <option value="deeni">Deeni Status</option>
              <option value="health">Health Status</option>
              <option value="residential">Residential Status</option>
            </select>
          </div>
          <div class="col-md-4 form-group mb-3">
            <label for="status_key" class="font-weight-bold">Status Key (DB Value)</label>
            <input type="text" name="status_key" id="status_key" class="form-control" placeholder="e.g. Normal" required>
            <small class="form-text text-muted">The exact text stored in the database.</small>
          </div>
          <div class="col-md-4 form-group mb-3">
            <label for="status_label" class="font-weight-bold">Display Label</label>
            <input type="text" name="status_label" id="status_label" class="form-control" placeholder="e.g. Normal (Active)" required>
            <small class="form-text text-muted">Label shown to users in dropdowns.</small>
          </div>
        </div>
        
        <div class="form-group mb-3">
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="is_inactive_trigger" name="is_inactive_trigger" value="1">
            <label class="custom-control-label font-weight-bold" for="is_inactive_trigger">Inactive Trigger</label>
            <small class="form-text text-muted d-block">If enabled, members assigned to this status will be automatically marked as <b>Inactive</b>.</small>
          </div>
        </div>

        <div class="mt-3">
          <button type="submit" class="btn btn-primary px-4" style="background: #b8860b; border-color: #b8860b;">Save Option</button>
          <button type="button" class="btn btn-link text-muted" id="btnCancelEdit" style="display: none;" onclick="resetForm()">Cancel Edit</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Options Lists -->
  <div class="row">
    <!-- Deeni Statuses -->
    <div class="col-md-4 mb-4">
      <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; border: 1px solid #e8e0cc !important;">
        <div class="card-header bg-light font-weight-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
          <i class="fa fa-star text-warning mr-1"></i> Deeni Status Options
        </div>
        <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
          <?php if (empty($deeni_options)): ?>
            <div class="list-group-item text-muted text-center py-4">No options found.</div>
          <?php else: foreach ($deeni_options as $o): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
              <div>
                <span class="d-block font-weight-bold" style="font-size: 0.9rem;"><?php echo htmlspecialchars($o['status_label']); ?></span>
                <small class="text-muted">Key: <?php echo htmlspecialchars($o['status_key']); ?></small>
                <div class="mt-1">
                  <?php if ($o['is_inactive_trigger']): ?>
                    <span class="badge badge-danger" style="font-size: 0.65rem;">Inactive Trigger</span>
                  <?php else: ?>
                    <span class="badge badge-success" style="font-size: 0.65rem;">Active</span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="d-flex">
                <button class="btn btn-sm btn-outline-primary mr-1" onclick="editOption(<?php echo htmlspecialchars(json_encode($o)); ?>)">
                  <i class="fa fa-pencil"></i>
                </button>
                <a href="<?php echo base_url('admin/delete_status_option/' . $o['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this option?')">
                  <i class="fa fa-trash"></i>
                </a>
              </div>
            </div>
          <?php endforeach; endif; ?>
        </div>
      </div>
    </div>

    <!-- Health Statuses -->
    <div class="col-md-4 mb-4">
      <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; border: 1px solid #e8e0cc !important;">
        <div class="card-header bg-light font-weight-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
          <i class="fa fa-heartbeat text-danger mr-1"></i> Health Status Options
        </div>
        <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
          <?php if (empty($health_options)): ?>
            <div class="list-group-item text-muted text-center py-4">No options found.</div>
          <?php else: foreach ($health_options as $o): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
              <div>
                <span class="d-block font-weight-bold" style="font-size: 0.9rem;"><?php echo htmlspecialchars($o['status_label']); ?></span>
                <small class="text-muted">Key: <?php echo htmlspecialchars($o['status_key']); ?></small>
                <div class="mt-1">
                  <?php if ($o['is_inactive_trigger']): ?>
                    <span class="badge badge-danger" style="font-size: 0.65rem;">Inactive Trigger</span>
                  <?php else: ?>
                    <span class="badge badge-success" style="font-size: 0.65rem;">Active</span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="d-flex">
                <button class="btn btn-sm btn-outline-primary mr-1" onclick="editOption(<?php echo htmlspecialchars(json_encode($o)); ?>)">
                  <i class="fa fa-pencil"></i>
                </button>
                <a href="<?php echo base_url('admin/delete_status_option/' . $o['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this option?')">
                  <i class="fa fa-trash"></i>
                </a>
              </div>
            </div>
          <?php endforeach; endif; ?>
        </div>
      </div>
    </div>

    <!-- Residential Statuses -->
    <div class="col-md-4 mb-4">
      <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; border: 1px solid #e8e0cc !important;">
        <div class="card-header bg-light font-weight-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
          <i class="fa fa-building text-primary mr-1"></i> Residential Status Options
        </div>
        <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
          <?php if (empty($residential_options)): ?>
            <div class="list-group-item text-muted text-center py-4">No options found.</div>
          <?php else: foreach ($residential_options as $o): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
              <div>
                <span class="d-block font-weight-bold" style="font-size: 0.9rem;"><?php echo htmlspecialchars($o['status_label']); ?></span>
                <small class="text-muted">Key: <?php echo htmlspecialchars($o['status_key']); ?></small>
                <div class="mt-1">
                  <?php if ($o['is_inactive_trigger']): ?>
                    <span class="badge badge-danger" style="font-size: 0.65rem;">Inactive Trigger</span>
                  <?php else: ?>
                    <span class="badge badge-success" style="font-size: 0.65rem;">Active</span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="d-flex">
                <button class="btn btn-sm btn-outline-primary mr-1" onclick="editOption(<?php echo htmlspecialchars(json_encode($o)); ?>)">
                  <i class="fa fa-pencil"></i>
                </button>
                <a href="<?php echo base_url('admin/delete_status_option/' . $o['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this option?')">
                  <i class="fa fa-trash"></i>
                </a>
              </div>
            </div>
          <?php endforeach; endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function editOption(opt) {
  document.getElementById('option_id').value = opt.id;
  document.getElementById('option_type').value = opt.type;
  document.getElementById('status_key').value = opt.status_key;
  document.getElementById('status_label').value = opt.status_label;
  document.getElementById('is_inactive_trigger').checked = parseInt(opt.is_inactive_trigger) === 1;
  
  document.getElementById('formCardTitle').innerHTML = '<i class="fa fa-pencil mr-2"></i>Edit Status Option';
  document.getElementById('btnCancelEdit').style.display = 'inline-block';
  
  // Scroll to form card
  document.getElementById('optionForm').scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
  document.getElementById('option_id').value = '';
  document.getElementById('optionForm').reset();
  document.getElementById('formCardTitle').innerHTML = '<i class="fa fa-plus-circle mr-2"></i>Add New Status Option';
  document.getElementById('btnCancelEdit').style.display = 'none';
}
</script>
