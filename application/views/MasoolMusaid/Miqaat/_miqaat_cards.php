<?php if (!empty($miqaats) && is_array($miqaats)): ?>
  <div class="row" id="miqaat-cards">
    <?php $colors = ["#fce4ec", "#f8f9fa", "#e8f5e9", "#e3f2fd", "#fff3e0", "#ede7f6"];
    $colorIndex = 0; ?>
    <?php foreach ($miqaats as $miqaat): ?>
      <?php $bgColor = $colors[$colorIndex % count($colors)]; ?>
      <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex miqaat-item"
        data-name="<?php echo htmlspecialchars($miqaat['name'], ENT_QUOTES); ?>"
        data-date="<?php echo isset($miqaat['date']) ? date('d M Y', strtotime($miqaat['date'])) : ''; ?>"
        data-hijri="<?php echo htmlspecialchars($miqaat['hijri_date'] ?? '', ENT_QUOTES); ?>">
        <div class="miqaat-card card shadow-sm border rounded flex-fill d-flex flex-column justify-content-between"
          style="min-height:180px; background-color: <?php echo $bgColor; ?>;">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title mb-2"><?php echo htmlspecialchars($miqaat['name']); ?></h5>
            <div class="mb-1"><span class="fw-bold">Date:</span> <?php echo isset($miqaat['date']) ? date('d M Y', strtotime($miqaat['date'])) : '-'; ?></div>
            <?php if (isset($miqaat['hijri_date'])): ?>
              <div class="mb-1"><span class="fw-bold">Hijri Date:</span> <?php echo htmlspecialchars($miqaat['hijri_date']); ?></div>
            <?php endif; ?>
            <?php if (isset($miqaat['member_count'])): ?>
              <div class="mb-1"><span class="fw-bold">Total Members:</span> <span class="badge badge-secondary"><?php echo $miqaat['member_count']; ?></span></div>
            <?php endif; ?>
            <div class="mb-1"><span class="fw-bold">Attendees:</span> <span class="badge badge-success"><?php echo isset($miqaat['attendee_count']) ? $miqaat['attendee_count'] : '-'; ?></span></div>
            <div class="d-flex justify-content-end align-items-end flex-grow-1 mt-2">
              <a href="<?php echo base_url("MasoolMusaid/miqaat_attendance_details/" . $miqaat['id']); ?>" class="btn btn-sm btn-primary ms-auto">Attendance Details</a>
            </div>
          </div>
        </div>
      </div>
      <?php $colorIndex++; ?>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="col-12">
    <div class="alert alert-info text-center">No upcoming Miqaats found.</div>
  </div>
<?php endif; ?>