<style>
  .miqaat-status-badge {
    font-size: 0.875rem;
    padding: 5px;
    border-radius: 0.375rem;
    margin: 0;
    margin-left: 0.5rem;
    position: relative;
    top: -4px;
  }
</style>
<div class="container margintopcontainer py-5">
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

  <div class="mb-4 mb-md-0">
    <a href="<?php echo base_url('accounts/home') ?>" class="btn btn-outline-secondary inline-block text-blue-600 hover:underline">
      <i class="fas fa-arrow-left"></i>
    </a>
  </div>
  <div>
    <h3 class="text-center text-2xl font-semibold mb-4">Assigned Miqaats</h3>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php if (!empty($miqaats)) {
      $sr = 1;
      foreach ($miqaats as $miqaat) { ?>
        <div class="p-4 mt-3 rounded rounded-xl shadow-sm border border-gray-200 bg-white hover:shadow-md transition">
          <h4 class="text-lg font-semibold text-gray-800 mb-3">
            Miqaat Name: <?php echo htmlspecialchars($miqaat['name']); ?>
            <?php
            if (isset($miqaat['invoice_status']) && $miqaat['invoice_status']) {
              echo '<span class="miqaat-status-badge badge badge-success">Completed</span>';
            } else {
              if ($miqaat['status'] == 0) {
                echo '<span class="miqaat-status-badge badge badge-secondary">Inactive</span>';
              } elseif ($miqaat['status'] == 1) {
                echo '<span class="miqaat-status-badge badge badge-success">Active</span>';
              } elseif ($miqaat['status'] == 2) {
                echo '<span class="miqaat-status-badge badge badge-warning">Cancelled</span>';
              }
            }
            ?>
          </h4>
          <p class="text-sm text-gray-500"><strong>Eng Date:</strong> <?php echo date('d M Y', strtotime($miqaat['date'])); ?></p>
          <p class="text-sm text-gray-500"><strong>Hijri Date:</strong> <?php echo htmlspecialchars($miqaat['hijri_date']); ?></p>
          <p class="text-sm text-gray-500"><strong>Miqaat Type:</strong> <?php echo htmlspecialchars($miqaat['type']); ?></p>
          <p class="text-sm text-gray-500"><strong>Assigned To:</strong> <?php echo htmlspecialchars($miqaat['assigned_to']); ?></p>

          <?php if (!empty($miqaat['assignments'])): ?>
            <div class="mt-3">
              <div class="font-semibold text-gray-700 mb-1"><strong>Assignments:</strong></div>
              <ul class="list-disc list-inside text-sm text-gray-600">
                <?php foreach ($miqaat['assignments'] as $assignment): ?>
                  <?php if ($assignment['assign_type'] === 'Individual'): ?>
                    <li>
                      <span class="font-medium"></span>
                      <?php echo htmlspecialchars($assignment['member_name']); ?> (ID: <?php echo htmlspecialchars($assignment['member_id']); ?>)
                    </li>
                  <?php elseif ($assignment['assign_type'] === 'Group'): ?>
                    <li>
                      <span class="font-medium"><strong>Group:</strong></span> <?php echo htmlspecialchars($assignment['group_name']); ?>
                      <br>
                      <strong>Leader:</strong> <?php echo htmlspecialchars($assignment['group_leader_name']); ?> (ID: <?php echo htmlspecialchars($assignment['group_leader_id']); ?>)
                      <?php if (!empty($assignment['co_leader_name'])): ?>
                        <br>
                        <strong>Co-Leader:</strong> <?php echo htmlspecialchars($assignment['co_leader_name']); ?> (ID: <?php echo htmlspecialchars($assignment['co_leader_id']); ?>)
                      <?php endif; ?>
                    </li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <p class="text-sm text-gray-500"><strong>Raza Status:</strong>
            <?php if (isset($miqaat['raza'])) {
              if ($miqaat["raza"]["Janab-status"] == 1) {
                echo '<span class="badge badge-success">Approved</span>';
              } else if ($miqaat["raza"]["coordinator-status"] == 1) {
                echo '<span class="badge badge-warning">Recommended</span>';
              } else {
                echo '<span class="badge badge-primary">Submitted</span>';
              }
            } else {
              echo '<span class="badge badge-secondary">Not Submitted</span>';
            } ?>
          </p>

          <?php if ($miqaat['status'] == 1 && !isset($miqaat['raza'])): ?>
            <a href="<?php echo base_url('accounts/submit_miqaat_raza/' . $miqaat['id']) ?>" class="raza-submit-btn btn btn-sm btn-success">Submit Raza</a>
          <?php else: ?>
            <a class="btn btn-sm btn-secondary disabled" style="pointer-events: none; opacity: 0.6;">Submit Raza</a>
          <?php endif; ?>
        </div>
      <?php }
    } else { ?>
      <div class="col-span-2 text-center text-gray-500 py-8">No assigned miqaats found.</div>
    <?php } ?>
  </div>
</div>
<script>
  $(".alert").delay(3000).fadeOut(500);

  $(".raza-submit-btn").click(function(e) {
    e.preventDefault();
    if (confirm("Are you sure you want to submit Raza?")) {
      window.location.href = $(this).attr("href");
    }
  });
</script>