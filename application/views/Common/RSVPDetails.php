<div class="container margintopcontainer pt-5">
  <div class="row col-6">
    <a href="<?php echo base_url('common/rsvp_list?from=' . $active_controller); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h3 class="mb-4 mt-3">Miqaat: <?php echo htmlspecialchars($miqaat['name'] ?? $miqaat['miqaat_name'] ?? ''); ?></h3>
  <h5 class="mb-3">Date: <?php echo isset($miqaat['date']) ? date('d M Y', strtotime($miqaat['date'])) : (isset($miqaat['miqaat_date']) ? date('d M Y', strtotime($miqaat['miqaat_date'])) : ''); ?></h5>
  <h6 class="mb-4">Hijri Date: <?php
    if (!empty($hijri_date)) {
      $hijri_parts = explode('-', $hijri_date["hijri_date"]);
      echo $hijri_parts[0] . ' ' . (isset($hijri_parts[1]) ? $hijri_date["hijri_month_name"] : '') . ' ' . (isset($hijri_parts[2]) ? $hijri_parts[2] : '');
    } else {
      echo '-';
    }
  ?></h6>

  <div class="card mb-5">
    <div class="card-header"><strong>All Members & RSVP</strong></div>
    <div class="card-body p-0">
      <?php
      $yes_count = 0;
      if (!empty($rsvp)) {
        foreach ($rsvp as $member) {
          if (isset($member['rsvp_status']) && $member['rsvp_status'] == 1) {
            $yes_count++;
          }
        }
      }
      ?>
      <?php if (!empty($rsvp)): ?>
        <div class="alert alert-info mb-0" style="font-size: 1.1rem; border-radius: 0.25rem;">
          <strong>RSVP Yes Count:</strong> <span class="fw-bold text-success"><?php echo $yes_count; ?></span> / <span class="fw-bold text-primary"><?php echo count($rsvp); ?></span>
        </div>
        <div style="max-height: 600px; overflow-y: auto;">
          <table class="table table-striped mb-0">
            <thead style="position: sticky; top: 0; z-index: 2; background: #fff;">
              <tr>
                <th>Name</th>
                <th>ITS ID</th>
                <th>Mobile</th>
                <th>Sector</th>
                <th>Sub Sector</th>
                <th>RSVP</th>
                <th>Comment</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rsvp as $member): ?>
                <?php $is_yes = isset($member['rsvp_status']) && $member['rsvp_status'] == 1; ?>
                <tr<?php if ($is_yes): ?> style="background-color: #d4edda;" <?php endif; ?>>
                  <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                  <td><?php echo htmlspecialchars($member['ITS_ID']); ?></td>
                  <td>
                    <?php
                    if (!empty($member['mobile'])) {
                      $digits = preg_replace('/\D/', '', $member['mobile']);
                      echo htmlspecialchars(substr($digits, -10));
                    } else {
                      echo '-';
                    }
                    ?>
                  </td>
                  <td><?php echo htmlspecialchars($member['sector'] ?? '-'); ?></td>
                  <td><?php echo htmlspecialchars($member['sub_sector'] ?? '-'); ?></td>
                  <td>
                    <?php if ($is_yes): ?>
                      <span class="badge badge-success">Yes</span>
                    <?php else: ?>
                      <span class="badge badge-danger">No</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo htmlspecialchars($member['rsvp_comment'] ?? ''); ?></td>
                  </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="p-3">No members found.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
