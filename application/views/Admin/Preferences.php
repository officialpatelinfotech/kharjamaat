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

    <div class="form-group mb-3">
      <label for="jamaat_place" class="font-weight-bold">Jamaat Place / Short Name</label>
      <input
        type="text"
        id="jamaat_place"
        name="jamaat_place"
        class="form-control"
        value="<?php echo htmlspecialchars(isset($jamaat_place) ? $jamaat_place : jamaat_place(), ENT_QUOTES, 'UTF-8'); ?>"
        placeholder="e.g. Khar"
        required
      />
      <small class="form-text text-muted">Used for dynamic place-based labels across the dashboard (e.g. Khar).</small>
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

    <hr />

    <h5 class="mb-3">Payment Receipt Details</h5>

    <div class="form-group mb-3">
      <label for="receipt_jamaat_name" class="font-weight-bold">Receipt Jamaat Name</label>
      <input
        type="text"
        id="receipt_jamaat_name"
        name="receipt_jamaat_name"
        class="form-control"
        value="<?php echo htmlspecialchars(isset($receipt_jamaat_name) ? $receipt_jamaat_name : app_setting('receipt_jamaat_name', 'Anjuman-e-Saifee Dawoodi Bohra Jamaat, ' . strtoupper(jamaat_place())), ENT_QUOTES, 'UTF-8'); ?>"
        placeholder="e.g. Anjuman-e-Saifee Dawoodi Bohra Jamaat"
      />
      <small class="form-text text-muted">Appears at the very top of generated PDF receipts.</small>
    </div>

    <div class="form-group mb-3">
      <label for="trust_regn_no" class="font-weight-bold">Trust Regn No.</label>
      <input
        type="text"
        id="trust_regn_no"
        name="trust_regn_no"
        class="form-control"
        value="<?php echo htmlspecialchars(isset($trust_regn_no) ? $trust_regn_no : app_setting('trust_regn_no', 'E/24158 (Mumbai)'), ENT_QUOTES, 'UTF-8'); ?>"
        placeholder="e.g. E/24158 (Mumbai)"
      />
    </div>

    <div class="form-group mb-3">
      <label for="managed_by" class="font-weight-bold">Managed By</label>
      <input
        type="text"
        id="managed_by"
        name="managed_by"
        class="form-control"
        value="<?php echo htmlspecialchars(isset($managed_by) ? $managed_by : app_setting('managed_by', 'Anjuman-e-Saifee'), ENT_QUOTES, 'UTF-8'); ?>"
        placeholder="e.g. Anjuman-e-Saifee"
      />
    </div>

    <hr />

    <h5 class="mb-3">System Administration</h5>

    <div class="form-group mb-3">
      <label for="admin_emails" class="font-weight-bold">Admin Emails</label>
      <textarea
        id="admin_emails"
        name="admin_emails"
        class="form-control"
        rows="4"
        placeholder="amilsaheb@kharjamaat.in, kharjamaat@gmail.com"
      ><?php echo htmlspecialchars(isset($admin_emails) ? $admin_emails : app_setting('admin_emails', "amilsaheb@kharjamaat.in,\n3042@carmelnmh.in,\nkharjamaat@gmail.com,\nkharamilsaheb@gmail.com,\nkharjamaat786@gmail.com,\nkhozemtopiwalla@gmail.com,\nybookwala@gmail.com"), ENT_QUOTES, 'UTF-8'); ?></textarea>
      <small class="form-text text-muted">Enter admin email addresses separated by commas or newlines. These emails receive copies of Raza requests and user/payment submissions.</small>
    </div>

    <div>
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </form>

  <hr />

  <div class="card p-3 mt-3">
    <h5 class="mb-1">Member Status Management</h5>
    <p class="text-muted small mb-3">
      Recalculate ITS–Sabeel match status, Member Type, and Activity Status for <strong>all</strong> members.<br>
      This runs automatically after every ITS CSV import. Use this button to manually trigger a recalculation
      (e.g. after updating Sabeel records).
    </p>
    <a href="<?php echo base_url('admin/recalculate_all_statuses'); ?>"
       class="btn btn-warning"
       onclick="return confirm('Recalculate statuses for ALL members? This may take a moment.');">
      <i class="fa-solid fa-arrows-rotate me-1"></i> Recalculate All Member Statuses
    </a>
  </div>

</div>
