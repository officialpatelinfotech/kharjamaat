<div class="container margintopcontainer pt-5">
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
      <?= $this->session->flashdata('error'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
      <?= $this->session->flashdata('success'); ?>
    </div>
  <?php endif; ?>

  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url('admin'); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>

  <h3 class="mb-4">Preferences</h3>

  <form method="POST" action="<?php echo base_url('admin/preferences'); ?>" class="card p-3">
    <div class="form-group mb-3">
      <label for="jamaat_name" class="font-weight-bold">Jamaat Name</label>
      <input
        type="text"
        id="jamaat_name"
        name="jamaat_name"
        class="form-control"
        value="<?php echo htmlspecialchars(isset($jamaat_name) ? $jamaat_name : jamaat_name(), ENT_QUOTES, 'UTF-8'); ?>"
        placeholder="e.g. Khar Jamaat"
        required
      />
      <small class="form-text text-muted">This updates the site title and shared footer branding.</small>
    </div>

    <hr />

    <div class="form-group mb-3">
      <label for="address_line" class="font-weight-bold">Address Line</label>
      <input
        type="text"
        id="address_line"
        name="address_line"
        class="form-control"
        value="<?php echo htmlspecialchars(isset($address_line) ? $address_line : app_setting('address_line', ''), ENT_QUOTES, 'UTF-8'); ?>"
        placeholder="Street / locality"
      />
    </div>

    <div class="form-group mb-3">
      <label for="city_state" class="font-weight-bold">City / State</label>
      <input
        type="text"
        id="city_state"
        name="city_state"
        class="form-control"
        value="<?php echo htmlspecialchars(isset($city_state) ? $city_state : app_setting('city_state', ''), ENT_QUOTES, 'UTF-8'); ?>"
        placeholder="City, State"
      />
    </div>

    <div class="form-group mb-3">
      <label for="pincode" class="font-weight-bold">Pincode</label>
      <input
        type="text"
        id="pincode"
        name="pincode"
        class="form-control"
        value="<?php echo htmlspecialchars(isset($pincode) ? $pincode : app_setting('pincode', ''), ENT_QUOTES, 'UTF-8'); ?>"
        placeholder="Postal code"
      />
    </div>

    <div class="form-group mb-3">
      <label for="support_email" class="font-weight-bold">Support Email</label>
      <input
        type="email"
        id="support_email"
        name="support_email"
        class="form-control"
        value="<?php echo htmlspecialchars(isset($support_email) ? $support_email : app_setting('support_email', ''), ENT_QUOTES, 'UTF-8'); ?>"
        placeholder="support@example.com"
      />
      <small class="form-text text-muted">Used in the footer “Contact” section.</small>
    </div>

    <div>
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </form>
</div>
