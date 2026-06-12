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
      <a href="<?php echo base_url('admin'); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>
  </div>

  <h3 class="mb-4">Notification Settings</h3>

  <form method="POST" action="<?php echo base_url('admin/notification_settings'); ?>" class="card p-3">
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
          <!-- 13. 3 Days before the event 01:00 pm to Amil Saheb (Public Event) -->
          <tr>
            <td>
              <div class="font-weight-bold">13. Amil Saheb Reminder - Public Event (3 Days Before at 01:00 PM)</div>
              <small class="text-muted">First pre-event alert to Amil Saheb for Public Events.</small>
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
          <!-- 14. Again 1 Day before the event at 09:00 pm to Amil Saheb (Public Event) -->
          <tr>
            <td>
              <div class="font-weight-bold">14. Amil Saheb Reminder - Public Event (1 Day Before at 09:00 PM)</div>
              <small class="text-muted">Second pre-event alert to Amil Saheb for Public Events.</small>
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
          <!-- 15. On the Event day at 9:00 am to Amil Saheb (Public Event) -->
          <tr>
            <td>
              <div class="font-weight-bold">15. Amil Saheb Reminder - Public Event (On Event Day at 09:00 AM)</div>
              <small class="text-muted">Final event day alert to Amil Saheb for Public Events.</small>
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
          <!-- 16. 3 Days before the event 01:00 pm to Amil Saheb (Private Event) -->
          <tr>
            <td>
              <div class="font-weight-bold">16. Amil Saheb Reminder - Kaaraj/Private Event (3 Days Before)</div>
              <small class="text-muted">First pre-event alert to Amil Saheb for Kaaraj/Private Events.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_amil_3days_before_private" name="notify_member_whatsapp_amil_3days_before_private" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_3days_before_private', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_amil_3days_before_private"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_amil_3days_before_private" name="notify_member_email_amil_3days_before_private" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_3days_before_private', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_amil_3days_before_private"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_amil_3days_before_private" name="notify_admin_whatsapp_amil_3days_before_private" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_3days_before_private', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_amil_3days_before_private"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_amil_3days_before_private" name="notify_admin_email_amil_3days_before_private" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_3days_before_private', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_amil_3days_before_private"></label>
              </div>
            </td>
          </tr>
          <!-- 17. Again 1 Day before the event at 09:00 pm to Amil Saheb (Private Event) -->
          <tr>
            <td>
              <div class="font-weight-bold">17. Amil Saheb Reminder - Kaaraj/Private Event (1 Day Before)</div>
              <small class="text-muted">Second pre-event alert to Amil Saheb for Kaaraj/Private Events.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_amil_1day_before_private" name="notify_member_whatsapp_amil_1day_before_private" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_1day_before_private', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_amil_1day_before_private"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_amil_1day_before_private" name="notify_member_email_amil_1day_before_private" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_1day_before_private', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_amil_1day_before_private"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_amil_1day_before_private" name="notify_admin_whatsapp_amil_1day_before_private" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_1day_before_private', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_amil_1day_before_private"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_amil_1day_before_private" name="notify_admin_email_amil_1day_before_private" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_1day_before_private', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_amil_1day_before_private"></label>
              </div>
            </td>
          </tr>
          <!-- 18. On the Event day at 9:00 am to Amil Saheb (Private Event) -->
          <tr>
            <td>
              <div class="font-weight-bold">18. Amil Saheb Reminder - Kaaraj/Private Event (On Event Day)</div>
              <small class="text-muted">Final event day alert to Amil Saheb for Kaaraj/Private Events.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_amil_event_day_private" name="notify_member_whatsapp_amil_event_day_private" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_event_day_private', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_amil_event_day_private"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_amil_event_day_private" name="notify_member_email_amil_event_day_private" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_event_day_private', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_amil_event_day_private"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_amil_event_day_private" name="notify_admin_whatsapp_amil_event_day_private" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_event_day_private', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_amil_event_day_private"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_amil_event_day_private" name="notify_admin_email_amil_event_day_private" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_event_day_private', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_amil_event_day_private"></label>
              </div>
            </td>
          </tr>
          <!-- 19. 3 Days before the event 01:00 pm to Amil Saheb (Non Event) -->
          <tr>
            <td>
              <div class="font-weight-bold">19. Amil Saheb Reminder - Non Event (3 Days Before)</div>
              <small class="text-muted">First pre-event alert to Amil Saheb for Non Events.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_amil_3days_before_non" name="notify_member_whatsapp_amil_3days_before_non" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_3days_before_non', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_amil_3days_before_non"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_amil_3days_before_non" name="notify_member_email_amil_3days_before_non" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_3days_before_non', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_amil_3days_before_non"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_amil_3days_before_non" name="notify_admin_whatsapp_amil_3days_before_non" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_3days_before_non', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_amil_3days_before_non"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_amil_3days_before_non" name="notify_admin_email_amil_3days_before_non" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_3days_before_non', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_amil_3days_before_non"></label>
              </div>
            </td>
          </tr>
          <!-- 20. Again 1 Day before the event at 09:00 pm to Amil Saheb (Non Event) -->
          <tr>
            <td>
              <div class="font-weight-bold">20. Amil Saheb Reminder - Non Event (1 Day Before)</div>
              <small class="text-muted">Second pre-event alert to Amil Saheb for Non Events.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_amil_1day_before_non" name="notify_member_whatsapp_amil_1day_before_non" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_1day_before_non', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_amil_1day_before_non"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_amil_1day_before_non" name="notify_member_email_amil_1day_before_non" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_1day_before_non', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_amil_1day_before_non"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_amil_1day_before_non" name="notify_admin_whatsapp_amil_1day_before_non" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_1day_before_non', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_amil_1day_before_non"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_amil_1day_before_non" name="notify_admin_email_amil_1day_before_non" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_1day_before_non', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_amil_1day_before_non"></label>
              </div>
            </td>
          </tr>
          <!-- 21. On the Event day at 9:00 am to Amil Saheb (Non Event) -->
          <tr>
            <td>
              <div class="font-weight-bold">21. Amil Saheb Reminder - Non Event (On Event Day)</div>
              <small class="text-muted">Final event day alert to Amil Saheb for Non Events.</small>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_whatsapp_amil_event_day_non" name="notify_member_whatsapp_amil_event_day_non" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_event_day_non', 'member_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_whatsapp_amil_event_day_non"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_member_email_amil_event_day_non" name="notify_member_email_amil_event_day_non" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_event_day_non', 'member_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_member_email_amil_event_day_non"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_whatsapp_amil_event_day_non" name="notify_admin_whatsapp_amil_event_day_non" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_event_day_non', 'admin_whatsapp') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_whatsapp_amil_event_day_non"></label>
              </div>
            </td>
            <td class="text-center align-middle">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="notify_admin_email_amil_event_day_non" name="notify_admin_email_amil_event_day_non" value="1" <?php echo is_notif_enabled($notif_settings, 'amil_event_day_non', 'admin_email') ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="notify_admin_email_amil_event_day_non"></label>
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
</div>
