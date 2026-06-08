<?php
$notif_settings = isset($notification_settings) ? $notification_settings : [];
if (!function_exists('is_notif_enabled')) {
    function is_notif_enabled($settings, $key, $type) {
        return isset($settings[$key][$type]) && $settings[$key][$type] == 1;
    }
}
?>
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

    <div class="form-group mb-3">
      <label for="registration_email" class="font-weight-bold">Registration Page Email</label>
      <input
        type="email"
        id="registration_email"
        name="registration_email"
        class="form-control"
        value="<?php echo htmlspecialchars(isset($registration_email) ? $registration_email : app_setting('registration_email', 'anjuman@kharjamaat.in'), ENT_QUOTES, 'UTF-8'); ?>"
        placeholder="anjuman@example.com"
      />
      <small class="form-text text-muted">This email is shown to users on the Register page for registration inquiries.</small>
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

    <hr />

    <h5 class="mb-3"><i class="fa-solid fa-bell text-primary mr-2"></i> Notification Configurations</h5>
    <p class="text-muted small mb-3">Enable or disable notifications for members and admins across the system.</p>

    <div class="table-responsive mb-4">
      <table class="table table-bordered table-hover bg-white align-middle">
        <thead class="thead-light">
          <tr>
            <th rowspan="2" style="width: 40%; vertical-align: middle;">Notification Type</th>
            <th colspan="2" class="text-center" style="width: 30%;">Notify Members</th>
            <th colspan="2" class="text-center" style="width: 30%;">Notify Admins</th>
          </tr>
          <tr>
            <th class="text-center" style="width: 15%; font-size: 0.85rem; font-weight: normal; background-color: #f8f9fa;">WhatsApp</th>
            <th class="text-center" style="width: 15%; font-size: 0.85rem; font-weight: normal; background-color: #f8f9fa;">Email</th>
            <th class="text-center" style="width: 15%; font-size: 0.85rem; font-weight: normal; background-color: #f8f9fa;">WhatsApp</th>
            <th class="text-center" style="width: 15%; font-size: 0.85rem; font-weight: normal; background-color: #f8f9fa;">Email</th>
          </tr>
        </thead>
        <tbody>
          <!-- 1. Miqaat Assignment -->
          <tr>
            <td>
              <div class="font-weight-bold">1. Miqaat Assignment</div>
              <small class="text-muted">Triggered when a member is assigned to a Miqaat duty or RSVP.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_miqaat_assignment" name="notify_member_whatsapp_miqaat_assignment" value="1" <?php echo is_notif_enabled($notif_settings, 'miqaat_assignment', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_miqaat_assignment"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_miqaat_assignment" name="notify_member_email_miqaat_assignment" value="1" <?php echo is_notif_enabled($notif_settings, 'miqaat_assignment', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_miqaat_assignment"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_miqaat_assignment" name="notify_admin_whatsapp_miqaat_assignment" value="1" <?php echo is_notif_enabled($notif_settings, 'miqaat_assignment', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_miqaat_assignment"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_miqaat_assignment" name="notify_admin_email_miqaat_assignment" value="1" <?php echo is_notif_enabled($notif_settings, 'miqaat_assignment', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_miqaat_assignment"></label>
              </div>
            </td>
          </tr>
          <!-- 2. Miqaat Activation -->
          <tr>
            <td>
              <div class="font-weight-bold">2. Miqaat Activation</div>
              <small class="text-muted">Triggered when a Miqaat is activated by administrators.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_miqaat_activation" name="notify_member_whatsapp_miqaat_activation" value="1" <?php echo is_notif_enabled($notif_settings, 'miqaat_activation', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_miqaat_activation"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_miqaat_activation" name="notify_member_email_miqaat_activation" value="1" <?php echo is_notif_enabled($notif_settings, 'miqaat_activation', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_miqaat_activation"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_miqaat_activation" name="notify_admin_whatsapp_miqaat_activation" value="1" <?php echo is_notif_enabled($notif_settings, 'miqaat_activation', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_miqaat_activation"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_miqaat_activation" name="notify_admin_email_miqaat_activation" value="1" <?php echo is_notif_enabled($notif_settings, 'miqaat_activation', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_miqaat_activation"></label>
              </div>
            </td>
          </tr>
          <!-- 3. Raza Submission -->
          <tr>
            <td>
              <div class="font-weight-bold">3. Raza Submission</div>
              <small class="text-muted">Triggered when a new Raza request is submitted by a member.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_raza_submission" name="notify_member_whatsapp_raza_submission" value="1" <?php echo is_notif_enabled($notif_settings, 'raza_submission', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_raza_submission"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_raza_submission" name="notify_member_email_raza_submission" value="1" <?php echo is_notif_enabled($notif_settings, 'raza_submission', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_raza_submission"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_raza_submission" name="notify_admin_whatsapp_raza_submission" value="1" <?php echo is_notif_enabled($notif_settings, 'raza_submission', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_raza_submission"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_raza_submission" name="notify_admin_email_raza_submission" value="1" <?php echo is_notif_enabled($notif_settings, 'raza_submission', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_raza_submission"></label>
              </div>
            </td>
          </tr>
          <!-- 4. Raza Recommendation -->
          <tr>
            <td>
              <div class="font-weight-bold">4. Raza Recommendation</div>
              <small class="text-muted">Triggered when a Raza request has been recommended by the sector masool.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_raza_recommendation" name="notify_member_whatsapp_raza_recommendation" value="1" <?php echo is_notif_enabled($notif_settings, 'raza_recommendation', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_raza_recommendation"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_raza_recommendation" name="notify_member_email_raza_recommendation" value="1" <?php echo is_notif_enabled($notif_settings, 'raza_recommendation', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_raza_recommendation"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_raza_recommendation" name="notify_admin_whatsapp_raza_recommendation" value="1" <?php echo is_notif_enabled($notif_settings, 'raza_recommendation', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_raza_recommendation"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_raza_recommendation" name="notify_admin_email_raza_recommendation" value="1" <?php echo is_notif_enabled($notif_settings, 'raza_recommendation', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_raza_recommendation"></label>
              </div>
            </td>
          </tr>
          <!-- 5. Raza Approval -->
          <tr>
            <td>
              <div class="font-weight-bold">5. Raza Approval</div>
              <small class="text-muted">Triggered when a Raza request is approved by Amil Saheb.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_raza_approval" name="notify_member_whatsapp_raza_approval" value="1" <?php echo is_notif_enabled($notif_settings, 'raza_approval', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_raza_approval"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_raza_approval" name="notify_member_email_raza_approval" value="1" <?php echo is_notif_enabled($notif_settings, 'raza_approval', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_raza_approval"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_raza_approval" name="notify_admin_whatsapp_raza_approval" value="1" <?php echo is_notif_enabled($notif_settings, 'raza_approval', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_raza_approval"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_raza_approval" name="notify_admin_email_raza_approval" value="1" <?php echo is_notif_enabled($notif_settings, 'raza_approval', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_raza_approval"></label>
              </div>
            </td>
          </tr>
          <!-- 6. RSVP Notifications on Raza Approval -->
          <tr>
            <td>
              <div class="font-weight-bold">6. RSVP Notifications on Raza Approval</div>
              <small class="text-muted">Triggered to send RSVP updates when a related Raza is approved.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_rsvp_on_raza_approval" name="notify_member_whatsapp_rsvp_on_raza_approval" value="1" <?php echo is_notif_enabled($notif_settings, 'rsvp_on_raza_approval', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_rsvp_on_raza_approval"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_rsvp_on_raza_approval" name="notify_member_email_rsvp_on_raza_approval" value="1" <?php echo is_notif_enabled($notif_settings, 'rsvp_on_raza_approval', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_rsvp_on_raza_approval"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_rsvp_on_raza_approval" name="notify_admin_whatsapp_rsvp_on_raza_approval" value="1" <?php echo is_notif_enabled($notif_settings, 'rsvp_on_raza_approval', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_rsvp_on_raza_approval"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_rsvp_on_raza_approval" name="notify_admin_email_rsvp_on_raza_approval" value="1" <?php echo is_notif_enabled($notif_settings, 'rsvp_on_raza_approval', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_rsvp_on_raza_approval"></label>
              </div>
            </td>
          </tr>
          <!-- 7. FMB Thaali Signup everyday at 10am -->
          <tr>
            <td>
              <div class="font-weight-bold">7. FMB Thaali Signup (Everyday at 10:00 AM)</div>
              <small class="text-muted">Daily reminder for FMB Thaali signup.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_fmb_signup_10am" name="notify_member_whatsapp_fmb_signup_10am" value="1" <?php echo is_notif_enabled($notif_settings, 'fmb_signup_10am', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_fmb_signup_10am"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_fmb_signup_10am" name="notify_member_email_fmb_signup_10am" value="1" <?php echo is_notif_enabled($notif_settings, 'fmb_signup_10am', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_fmb_signup_10am"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_fmb_signup_10am" name="notify_admin_whatsapp_fmb_signup_10am" value="1" <?php echo is_notif_enabled($notif_settings, 'fmb_signup_10am', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_fmb_signup_10am"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_fmb_signup_10am" name="notify_admin_email_fmb_signup_10am" value="1" <?php echo is_notif_enabled($notif_settings, 'fmb_signup_10am', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_fmb_signup_10am"></label>
              </div>
            </td>
          </tr>
          <!-- 8. FMB Thaali Feedback everyday at 10pm -->
          <tr>
            <td>
              <div class="font-weight-bold">8. FMB Thaali Feedback (Everyday at 10:00 PM)</div>
              <small class="text-muted">Daily prompt requesting feedback on FMB Thaali distribution.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_fmb_feedback_10pm" name="notify_member_whatsapp_fmb_feedback_10pm" value="1" <?php echo is_notif_enabled($notif_settings, 'fmb_feedback_10pm', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_fmb_feedback_10pm"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_fmb_feedback_10pm" name="notify_member_email_fmb_feedback_10pm" value="1" <?php echo is_notif_enabled($notif_settings, 'fmb_feedback_10pm', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_fmb_feedback_10pm"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_fmb_feedback_10pm" name="notify_admin_whatsapp_fmb_feedback_10pm" value="1" <?php echo is_notif_enabled($notif_settings, 'fmb_feedback_10pm', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_fmb_feedback_10pm"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_fmb_feedback_10pm" name="notify_admin_email_fmb_feedback_10pm" value="1" <?php echo is_notif_enabled($notif_settings, 'fmb_feedback_10pm', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_fmb_feedback_10pm"></label>
              </div>
            </td>
          </tr>
          <!-- 9. FMB Takhmeen, Sabeel Takhmeen, Miqaat Invoices and Extra Contributions due reminder every month -->
          <tr>
            <td>
              <div class="font-weight-bold">9. Monthly Dues Reminder</div>
              <small class="text-muted">Sent monthly for FMB Takhmeen, Sabeel Takhmeen, Miqaat Invoices, and Extra Contributions.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_monthly_due_reminder" name="notify_member_whatsapp_monthly_due_reminder" value="1" <?php echo is_notif_enabled($notif_settings, 'monthly_due_reminder', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_monthly_due_reminder"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_monthly_due_reminder" name="notify_member_email_monthly_due_reminder" value="1" <?php echo is_notif_enabled($notif_settings, 'monthly_due_reminder', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_monthly_due_reminder"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_monthly_due_reminder" name="notify_admin_whatsapp_monthly_due_reminder" value="1" <?php echo is_notif_enabled($notif_settings, 'monthly_due_reminder', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_monthly_due_reminder"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_monthly_due_reminder" name="notify_admin_email_monthly_due_reminder" value="1" <?php echo is_notif_enabled($notif_settings, 'monthly_due_reminder', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_monthly_due_reminder"></label>
              </div>
            </td>
          </tr>
          <!-- 10. Corpus Funds due reminder every week -->
          <tr>
            <td>
              <div class="font-weight-bold">10. Corpus Funds Due Reminder (Every week)</div>
              <small class="text-muted">Weekly notification for outstanding Corpus Funds dues.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_corpus_funds_weekly_reminder" name="notify_member_whatsapp_corpus_funds_weekly_reminder" value="1" <?php echo is_notif_enabled($notif_settings, 'corpus_funds_weekly_reminder', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_corpus_funds_weekly_reminder"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_corpus_funds_weekly_reminder" name="notify_member_email_corpus_funds_weekly_reminder" value="1" <?php echo is_notif_enabled($notif_settings, 'corpus_funds_weekly_reminder', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_corpus_funds_weekly_reminder"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_corpus_funds_weekly_reminder" name="notify_admin_whatsapp_corpus_funds_weekly_reminder" value="1" <?php echo is_notif_enabled($notif_settings, 'corpus_funds_weekly_reminder', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_corpus_funds_weekly_reminder"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_corpus_funds_weekly_reminder" name="notify_admin_email_corpus_funds_weekly_reminder" value="1" <?php echo is_notif_enabled($notif_settings, 'corpus_funds_weekly_reminder', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_corpus_funds_weekly_reminder"></label>
              </div>
            </td>
          </tr>
          <!-- 11. Appointment Schedule -->
          <tr>
            <td>
              <div class="font-weight-bold">11. Appointment Schedule</div>
              <small class="text-muted">Triggered when a new appointment is booked or scheduled.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_appointment_schedule" name="notify_member_whatsapp_appointment_schedule" value="1" <?php echo is_notif_enabled($notif_settings, 'appointment_schedule', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_appointment_schedule"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_appointment_schedule" name="notify_member_email_appointment_schedule" value="1" <?php echo is_notif_enabled($notif_settings, 'appointment_schedule', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_appointment_schedule"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_appointment_schedule" name="notify_admin_whatsapp_appointment_schedule" value="1" <?php echo is_notif_enabled($notif_settings, 'appointment_schedule', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_appointment_schedule"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_appointment_schedule" name="notify_admin_email_appointment_schedule" value="1" <?php echo is_notif_enabled($notif_settings, 'appointment_schedule', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_appointment_schedule"></label>
              </div>
            </td>
          </tr>
          <!-- 12. Appointment Report every night for the next day's appointments -->
          <tr>
            <td>
              <div class="font-weight-bold">12. Appointment Nightly Report</div>
              <small class="text-muted">Nightly summary report for next day's scheduled appointments.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_appointment_report_nightly" name="notify_member_whatsapp_appointment_report_nightly" value="1" <?php echo is_notif_enabled($notif_settings, 'appointment_report_nightly', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_appointment_report_nightly"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_appointment_report_nightly" name="notify_member_email_appointment_report_nightly" value="1" <?php echo is_notif_enabled($notif_settings, 'appointment_report_nightly', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_appointment_report_nightly"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_appointment_report_nightly" name="notify_admin_whatsapp_appointment_report_nightly" value="1" <?php echo is_notif_enabled($notif_settings, 'appointment_report_nightly', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_appointment_report_nightly"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_appointment_report_nightly" name="notify_admin_email_appointment_report_nightly" value="1" <?php echo is_notif_enabled($notif_settings, 'appointment_report_nightly', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_appointment_report_nightly"></label>
              </div>
            </td>
          </tr>
          <!-- 13. 3 Days before the event 01:00 pm to Amil Saheb -->
          <tr>
            <td>
              <div class="font-weight-bold">13. Amil Saheb Reminder (3 Days Before Event at 01:00 PM)</div>
              <small class="text-muted">First pre-event alert to Amil Saheb.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_amil_3days_before" name="notify_member_whatsapp_amil_3days_before" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_3days_before', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_amil_3days_before"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_amil_3days_before" name="notify_member_email_amil_3days_before" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_3days_before', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_amil_3days_before"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_amil_3days_before" name="notify_admin_whatsapp_amil_3days_before" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_3days_before', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_amil_3days_before"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_amil_3days_before" name="notify_admin_email_amil_3days_before" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_3days_before', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_amil_3days_before"></label>
              </div>
            </td>
          </tr>
          <!-- 14. Again 1 Day before the event at 09:00 pm to Amil Saheb -->
          <tr>
            <td>
              <div class="font-weight-bold">14. Amil Saheb Reminder (1 Day Before Event at 09:00 PM)</div>
              <small class="text-muted">Second pre-event alert to Amil Saheb.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_amil_1day_before" name="notify_member_whatsapp_amil_1day_before" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_1day_before', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_amil_1day_before"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_amil_1day_before" name="notify_member_email_amil_1day_before" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_1day_before', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_amil_1day_before"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_amil_1day_before" name="notify_admin_whatsapp_amil_1day_before" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_1day_before', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_amil_1day_before"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_amil_1day_before" name="notify_admin_email_amil_1day_before" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_1day_before', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_amil_1day_before"></label>
              </div>
            </td>
          </tr>
          <!-- 15. On the Event day at 9:00 am to Amil Saheb -->
          <tr>
            <td>
              <div class="font-weight-bold">15. Amil Saheb Reminder (On Event Day at 09:00 AM)</div>
              <small class="text-muted">Final event day alert to Amil Saheb.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_amil_event_day" name="notify_member_whatsapp_amil_event_day" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_event_day', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_amil_event_day"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_amil_event_day" name="notify_member_email_amil_event_day" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_event_day', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_amil_event_day"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_amil_event_day" name="notify_admin_whatsapp_amil_event_day" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_event_day', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_amil_event_day"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_amil_event_day" name="notify_admin_email_amil_event_day" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_event_day', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_amil_event_day"></label>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div>
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </form>

  <hr />

  <div class="card p-3 mt-3">
    <h5 class="mb-1">Member Status Management</h5>
    <p class="text-muted small mb-3">
      Recalculate ITS–Sabeel match status and Activity Status for <strong>all</strong> members.<br>
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
