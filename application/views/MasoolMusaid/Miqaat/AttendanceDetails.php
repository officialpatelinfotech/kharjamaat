<style>
  .attend-checkbox {
    width: 1.5em;
    height: 1.5em;
    min-width: 1.5em;
    min-height: 1.5em;
    accent-color: #0d6efd;
  }
</style>
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

  <div class="mb-4 mb-md-0">
    <a href="<?php echo base_url('/MasoolMusaid/miqaat_attendance') ?>" class="btn btn-outline-secondary inline-block text-blue-600 hover:underline">
      <i class="fas fa-arrow-left"></i>
    </a>
  </div>
  <h3 class="heading text-center mb-4">Attendance <span class="text-primary">Confirmation</span></h3>
  <h6 class="text-center text-muted mb-4">Miqaat: <?php echo htmlspecialchars($miqaat["name"]); ?> | Date: <?php echo date('d M Y', strtotime($miqaat["date"])); ?></h6>

  <?php
  $total_members = isset($members) ? count($members) : 0;
  $total_attended = isset($existing_attendance) ? count($existing_attendance) : 0;
  ?>
  <div class="row mb-4">
    <div class="col-12 col-md-6 mb-2 mb-md-0">
      <div class="p-2 text-center shadow-sm border rounded">
        <div class="row">
          <div class="col-6 text-left">
            <span><strong>Total Members:</strong></span>
          </div>
          <div class="col-6 text-right">
            <span class="p-2 badge badge-secondary"><?php echo $total_members; ?></span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6">
      <div class="p-2 text-center shadow-sm border rounded">
        <div class="row">
          <div class="col-6 text-left">
            <span><strong>Total Attended:</strong></span>
          </div>
          <div class="col-6 text-right">
            <span class="p-2 badge badge-success"><?php echo $total_attended; ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <form method="post" action="<?php echo base_url('masoolmusaid/submit_miqaat_attendance'); ?>">
    <input type="hidden" name="miqaat_id" value="<?php echo htmlspecialchars($miqaat_id); ?>">
    <div class="row">
      <div class="col-12">
        <?php if (!empty($members)):
          if (!empty($existing_attendance)) {
            $attended_ids = array_column($existing_attendance, 'user_id');
          } else {
            $attended_ids = [];
          }
        ?>
          <div class="table-responsive">
            <table class="table table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th scope="col" style="width:40px;"></th>
                  <th scope="col">ITS ID</th>
                  <th scope="col">Full Name</th>
                  <th scope="col">Mobile</th>
                  <th scope="col">Sector</th>
                  <th scope="col">Sub Sector</th>
                  <th scope="col">Comment</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($members as $member): ?>
                  <tr>
                    <td class="text-center">
                      <input class="m-0 attend-checkbox" type="checkbox" name="attendance_members[]" value="<?php echo htmlspecialchars($member['ITS_ID']); ?>" id="attend_<?php echo htmlspecialchars($member['ITS_ID']); ?>" <?php echo in_array($member['ITS_ID'], $attended_ids) ? 'checked' : ''; ?>>
                    </td>
                    <td><?php echo htmlspecialchars($member['ITS_ID']); ?></td>
                    <td><?php echo htmlspecialchars($member['Full_Name']); ?></td>
                    <td><?php
                        $mobile = $member['Mobile'] ?? '';
                        $last10 = $mobile ? substr(preg_replace('/\D/', '', $mobile), -10) : '-';
                        echo htmlspecialchars($last10 ?: '-');
                        ?></td>
                    <td><?php echo htmlspecialchars($member['Sector']); ?></td>
                    <td><?php echo htmlspecialchars($member['Sub_Sector']); ?></td>
                    <td>
                      <input type="text" name="attendance_comments[<?php echo htmlspecialchars($member['ITS_ID']); ?>]" class="form-control form-control-sm" value="<?php echo isset($member['comment']) ? htmlspecialchars($member['comment']) : ''; ?>" placeholder="Comment...">
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="alert alert-info text-center">No members found.</div>
        <?php endif; ?>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-12 text-end">
      </div>
    </div>
    <div style="height:60px;"></div>
    <div class="submit-footer d-flex justify-content-center align-items-center" style="position:fixed;left:0;right:0;bottom:0;z-index:1050;background:rgba(255,255,255,0.95);box-shadow:0 -2px 8px rgba(0,0,0,0.07);padding:16px 0;">
      <button type="submit" class="btn btn-primary px-5 py-2 fs-5">Submit Attendance</button>
    </div>
  </form>
</div>
<script>
  $(".alert").delay(3000).fadeOut(500);
</script>