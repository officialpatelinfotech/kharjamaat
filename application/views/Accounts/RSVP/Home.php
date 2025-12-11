<?php
// Show only upcoming miqaats approved by Janab (status == 1)
if (isset($miqaats) && is_array($miqaats)) {
  $today = strtotime('today');
  $miqaats = array_values(array_filter($miqaats, function ($m) use ($today) {
    $status = null;
    $dateStr = null;
    if (is_array($m)) {
      $status = $m['Janab-status'] ?? $m['janab_status'] ?? $m['status'] ?? null;
      $dateStr = $m['date'] ?? $m['miqaat_date'] ?? $m['event_date'] ?? $m['start_date'] ?? null;
    } elseif (is_object($m)) {
      $status = isset($m->{'Janab-status'}) ? $m->{'Janab-status'} : ($m->janab_status ?? $m->status ?? null);
      $dateStr = $m->date ?? $m->miqaat_date ?? $m->event_date ?? $m->start_date ?? null;
    }
    $isApproved = false;
    if (is_string($status)) $isApproved = trim($status) === '1';
    elseif (is_numeric($status)) $isApproved = ((int)$status === 1);
    elseif (is_bool($status)) $isApproved = ($status === true);

    $isUpcoming = false;
    if (!empty($dateStr)) {
      $d = strtotime($dateStr);
      if ($d !== false) {
        $isUpcoming = ($d >= $today);
      }
    }
    return $isApproved && $isUpcoming;
  }));
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

  <?php if ($this->session->flashdata('warning')): ?>
    <div class="alert alert-warning">
      <?= $this->session->flashdata('warning'); ?>
    </div>
  <?php endif; ?>
  <div class="mb-4 mb-md-0 pt-5">
    <a href="<?php echo base_url('accounts/home') ?>" class="btn btn-outline-secondary inline-block text-blue-600 hover:underline">
      <i class="fas fa-arrow-left"></i>
    </a>
  </div>

  <h3 class="heading text-center mb-4">RSVP <span class="text-primary">Dashboard</span></h3>
  <?php if (!empty($miqaats) && is_array($miqaats)): ?>
    <div class="row justify-content-center">
      <?php foreach ($miqaats as $miqaat): ?>
        <div class="col-12 col-md-6 col-lg-4 mb-5 d-flex">
          <div class="card shadow-sm border flex-fill d-flex flex-column justify-content-between" style="min-height:220px; position:relative;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title mb-2"><?php echo htmlspecialchars($miqaat['name']); ?></h5>
              <div class="mb-1"><span class="fw-bold">Type:</span> <?php echo htmlspecialchars($miqaat['type']); ?></div>
              <div class="mb-1"><span class="fw-bold">Date:</span> <?php echo date('d M Y', strtotime($miqaat['date'])); ?></div>
              <div class="mb-1"><span class="fw-bold">Hijri Date:</span> <?php echo $miqaat['hijri_date']; ?></div>
              <div class="mb-1"><span class="fw-bold">Miqaat Status:</span> <?php echo $miqaat['status'] == '1' ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>'; ?></div>
              <div class="mb-5">
                <span class="fw-bold">RSVP Status:</span> <?php echo in_array($miqaat["id"], (isset($rsvp_overview[$miqaat["id"]]) && $rsvp_overview[$miqaat["id"]]) ? array_column($rsvp_overview[$miqaat["id"]], 'miqaat_id') : []) ? '<span class="badge badge-success">Confirmed</span>' : '<span class="badge badge-danger">Pending</span>'; ?>
              </div>
              <?php if (isset($miqaat["raza_status"])): ?>
                <?php if ($miqaat["raza_status"] == 1): ?>
                  <div class="d-flex justify-content-end align-items-end flex-grow-1" style="position:absolute; bottom:16px; right:16px; left:16px;">
                    <a href="<?php echo base_url('accounts/general_rsvp/' . $miqaat['id']); ?>" class="btn btn-sm btn-primary ms-auto"><?php echo (in_array($miqaat["id"], ((isset($rsvp_overview[$miqaat["id"]]) && $rsvp_overview[$miqaat["id"]]) ? array_column($rsvp_overview[$miqaat["id"]], 'miqaat_id') : [])) ? 'Update RSVP' : 'RSVP'); ?></a>
                  </div>
                <?php else: ?>
                  <div class="d-flex justify-content-end align-items-end flex-grow-1" style="position:absolute; bottom:16px; right:16px; left:16px;">
                    <button class="btn btn-sm btn-secondary ms-auto" disabled>RSVP</button>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">No upcoming events to RSVP.</div>
  <?php endif; ?>
</div>
<script>
  $(".alert").delay(3000).fadeOut(500);
</script>