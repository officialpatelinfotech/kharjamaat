<?php if (!empty($miqaat_rsvp_counts) && is_array($miqaat_rsvp_counts)): ?>
  <div class="row" id="miqaat-cards">
    <?php $colors = ["#fce4ec", "#f8f9fa", "#e8f5e9", "#e3f2fd", "#fff3e0", "#ede7f6"];
    $colorIndex = 0; ?>
    <?php foreach ($miqaat_rsvp_counts as $miqaat): ?>
      <?php $bgColor = $colors[$colorIndex % count($colors)]; ?>
      <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex miqaat-item"
        data-name="<?php echo htmlspecialchars($miqaat['miqaat_name'], ENT_QUOTES); ?>"
        data-date="<?php echo date('d M Y', strtotime($miqaat['miqaat_date'])); ?>"
        data-hijri="<?php echo htmlspecialchars($miqaat['hijri_date'] ?? '', ENT_QUOTES); ?>">
        <div class="miqaat-card card shadow-sm border rounded flex-fill d-flex flex-column justify-content-between"
          style="min-height:180px; background-color: <?php echo $bgColor; ?>;">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title mb-2"><?php echo htmlspecialchars($miqaat['miqaat_name']); ?></h5>
            <div class="mb-1"><span class="fw-bold">Date:</span> <?php echo date('d M Y', strtotime($miqaat['miqaat_date'])); ?></div>
            <div class="mb-1"><span class="fw-bold">Hijri Date:</span> <?php echo htmlspecialchars($miqaat['hijri_date'] ?? '', ENT_QUOTES); ?></div>
            <div class="mb-1"><span class="fw-bold">Total Members:</span> <span class="badge badge-secondary"><?php echo $miqaat['member_count']; ?></span></div>
            <div class="mb-1"><span class="fw-bold">RSVPs Confirmed:</span> <span class="badge badge-success"><?php echo $miqaat['rsvp_count']; ?></span></div>
            <div class="d-flex justify-content-end align-items-end flex-grow-1 mt-2">
              <a href="<?php echo site_url('MasoolMusaid/general_rsvp/' . $miqaat['miqaat_id']); ?>" class="btn btn-sm btn-primary ms-auto">RSVP</a>
            </div>
          </div>
        </div>
      </div>
      <?php $colorIndex++; ?>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="alert alert-info text-center">No upcoming events to RSVP.</div>
<?php endif; ?>