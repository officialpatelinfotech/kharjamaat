<div class="container margintopcontainer pt-5">
  <div class="mb-4 mb-md-0">
    <a href="<?php echo $active_controller ? $active_controller : base_url(); ?>" class="btn btn-outline-secondary inline-block text-blue-600 hover:underline">
      <i class="fas fa-arrow-left"></i>
    </a>
  </div>
  <h3 class="heading text-center mb-4">RSVP <span class="text-primary">Report</span></h3>
  <div class="row">
    <?php if (!empty($miqaats)) : ?>
      <?php foreach ($miqaats as $miqaat) : ?>
        <div class="col-md-4 col-sm-6 mb-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($miqaat['miqaat_name']); ?></h5>
              <p class="card-text mb-2">
                <strong>Date:</strong> <?php echo date('d M Y', strtotime($miqaat['miqaat_date'])); ?>
              </p>
              <p class="card-text mb-2">
                <strong>Hijri Date:</strong>
                <?php
                if (!empty($miqaat['hijri_date']) && !empty($miqaat['hijri_month_name'])) {
                  $hijri_parts = explode('-', $miqaat['hijri_date']);
                  echo $hijri_parts[0] . ' ' . $miqaat['hijri_month_name'] . ' ' . (isset($hijri_parts[2]) ? $hijri_parts[2] : '');
                } else {
                  echo '-';
                }
                ?>
              </p>
              <p class="card-text mb-0">
                <strong>RSVPs:</strong> <?php echo (isset($rsvp_by_miqaat[$miqaat['miqaat_id']]) && $rsvp_by_miqaat[$miqaat['miqaat_id']] > 0) ? "<span class='badge badge-success'>" . $rsvp_by_miqaat[$miqaat['miqaat_id']] . "</span>" : "<span class='badge badge-danger'>0</span>"; ?>
              </p>
              <div class="mt-3">
                <a href="<?php echo base_url('common/rsvp_details/' . $miqaat['miqaat_id']); ?>" class="btn btn-primary btn-sm">View Details</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <div class="col-12">
        <div class="alert alert-info">No miqaats found.</div>
      </div>
    <?php endif; ?>
  </div>
</div>
