<style>
  .site-footer {
    background: #222;
    color: #eee;
    font-size: 14px;
    padding: 32px 0 16px;
  }

  .site-footer a {
    color: #ffc107;
    text-decoration: none;
  }

  .site-footer a:hover {
    text-decoration: underline;
    color: #ffda55;
  }

  .site-footer h5 {
    font-size: 16px;
    font-weight: 600;
    color: #fff;
  }

  .site-footer .footer-bottom {
    border-top: 1px solid #444;
    margin-top: 24px;
    padding-top: 12px;
    font-size: 12px;
    color: #bbb;
  }

  @media (max-width:767px) {
    .site-footer {
      padding: 24px 0;
    }

    .site-footer h5 {
      margin-top: 24px;
    }
  }
</style>
<footer class="site-footer">
  <div class="container">
    <?php
      // Defaults from app settings (can be overridden by view params)
      if (!isset($address_line)) $address_line = app_setting('address_line', isset($address_line) ? $address_line : '');
      if (!isset($city_state)) $city_state = app_setting('city_state', isset($city_state) ? $city_state : '');
      if (!isset($pincode)) $pincode = app_setting('pincode', isset($pincode) ? $pincode : '');
      if (!isset($support_email)) $support_email = app_setting('support_email', isset($support_email) ? $support_email : '');
    ?>
    <div class="row">
      <div class="col-md-4 col-sm-6">
        <h5>About</h5>
        <p><?php echo isset($org_name) ? $org_name : htmlspecialchars(jamaat_name(), ENT_QUOTES, 'UTF-8'); ?> is a community platform facilitating structured participation, contributions, and communication for members.</p>
      </div>
      <div class="col-md-2 col-sm-6">
        <h5>Legal</h5>
        <ul class="list-unstyled mb-0">
          <li><a href="<?php echo base_url('terms'); ?>">Terms & Conditions</a></li>
          <li><a href="<?php echo base_url('privacy'); ?>">Privacy Policy</a></li>
          <li><a href="<?php echo base_url('refund'); ?>">Refund & Cancellation</a></li>
          <li><a href="<?php echo base_url('contact'); ?>">Contact Us</a></li>
        </ul>
      </div>
      <div class="col-md-3 col-sm-6">
        <h5>Contact</h5>
        <p class="mb-1"><strong>Address:</strong><br><?php echo isset($address_line) ? $address_line : ''; ?><br><?php echo isset($city_state) ? $city_state : ''; ?> - <?php echo isset($pincode) ? $pincode : ''; ?></p>
        <p class="mb-1"><strong>Email:</strong> <a href="mailto:<?php echo isset($support_email) ? $support_email : ''; ?>"><?php echo isset($support_email) ? $support_email : ''; ?></a></p>
      </div>
      <div class="col-md-3 col-sm-6">
        <h5>Status</h5>
        <p class="mb-2">Last Updated: <span><?php echo isset($last_updated) ? $last_updated : date('d M Y'); ?></span></p>
        <p class="small mb-2">For payment or account questions, please reach out via the listed support email before initiating any dispute.</p>
      </div>
    </div>
    <div class="footer-bottom text-center">
      <span>&copy; <?php echo date('Y'); ?> <?php echo isset($org_name) ? $org_name : ('Anjuman-e-Saifee ' . htmlspecialchars(jamaat_place(), ENT_QUOTES, 'UTF-8')); ?>. All rights reserved.</span>
    </div>
  </div>
</footer>