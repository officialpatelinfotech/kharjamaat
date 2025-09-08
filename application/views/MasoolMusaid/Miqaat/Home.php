<?php
$colors = [
  "#fce4ec", // light pink
  "#f8f9fa", // light gray
  "#e8f5e9", // light green
  "#e3f2fd", // light blue
  "#fff3e0", // light orange
  "#ede7f6",  // light purple
];
$colorIndex = 0;
?>

<style>
  #miqaat-dashboard .card:nth-child(6n+1) {
    background-color: #f8f9fa;
  }

  #miqaat-dashboard .card:nth-child(6n+2) {
    background-color: #e3f2fd;
  }

  #miqaat-dashboard .card:nth-child(6n+3) {
    background-color: #fce4ec;
  }

  #miqaat-dashboard .card:nth-child(6n+4) {
    background-color: #e8f5e9;
  }

  #miqaat-dashboard .card:nth-child(6n+5) {
    background-color: #fff3e0;
  }

  #miqaat-dashboard .card:nth-child(6n+6) {
    background-color: #ede7f6;
  }

  .miqaat-card:hover {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    transform: translateY(-5px);
    transition: all 0.3s ease;
  }
</style>

<div class="container margintopcontainer pt-5">
  <div class="mb-4 mb-md-0">
    <a href="<?php echo base_url('/MasoolMusaid') ?>" class="btn btn-outline-secondary inline-block text-blue-600 hover:underline">
      <i class="fas fa-arrow-left"></i>
    </a>
  </div>

  <h3 class="heading pb-5 text-center">Miqaat <span class="text-primary">Attendance</span></h3>
  <div id="miqaat-dashboard">
    <div class="row">
      <?php if (!empty($miqaats)): ?>
        <?php foreach ($miqaats as $miqaat): ?>
          <?php $bgColor = $colors[$colorIndex % count($colors)]; ?>
          <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex">
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
                  <?php 
                  if (isset($miqaat["raza_status"]) && $miqaat["raza_status"] == 1): ?>
                    <a href="<?php echo base_url("MasoolMusaid/miqaat_attendance_details/" . $miqaat['id']); ?>" class="btn btn-sm btn-primary ms-auto">Attendance Details</a>
                  <?php else: ?>
                    <button class="btn btn-sm btn-secondary ms-auto" disabled>No Action</button>
                  <?php endif; ?>

                </div>
              </div>
            </div>
          </div>
          <?php $colorIndex++; ?>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12">
          <div class="alert alert-info text-center">No upcoming Miqaats found.</div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>