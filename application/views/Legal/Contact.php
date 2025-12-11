<div class="container mt-4 mb-5" style="margin-top:40px !important;">
  <a href="<?php echo base_url("/"); ?>" class="btn btn-sm btn-secondary mb-3"><i class="fa fa-arrow-left"></i> Back</a>
  <h2 class="mb-3">Contact Us</h2>
  <p class="text-muted mb-4">Last Updated: <?php echo $last_updated; ?></p>
  <div class="row">
    <div class="col-md-6 mb-3">
      <h5>Office</h5>
      <p class="mb-1"><?php echo $org_name; ?></p>
      <p class="mb-1"><?php echo $address_line; ?></p>
      <p class="mb-1"><?php echo $city_state; ?> - <?php echo $pincode; ?></p>
    </div>
    <div class="col-md-6 mb-3">
      <h5>Support</h5>
      <p class="mb-1"><strong>Email:</strong> <a href="mailto:<?php echo $support_email; ?>"><?php echo $support_email; ?></a></p>
      <p class="mb-2"><strong>Escalation:</strong> For unresolved issues escalate via email with subject line including ITS ID and brief description.</p>
    </div>
  </div>
  <h5>Feedback</h5>
  <p>Platform feedback (usability, accuracy, improvements) is welcome. Please avoid sharing sensitive data over unsecured channels.</p>
</div>