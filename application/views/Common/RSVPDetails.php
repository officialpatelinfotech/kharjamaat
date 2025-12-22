<div class="container margintopcontainer pt-5">
  <div class="row container">
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
      // Prefer member-level lists when controller provides them (rsvp_member_list, not_rsvp_member_list, not_submitted_member_list)
      $rows = [];
      $list_label = 'HOF-level';
      if (!empty($rsvp_member_list) && is_array($rsvp_member_list)) {
        $rows = $rsvp_member_list;
        $list_label = 'Member-level';
      } elseif (!empty($rsvp) && is_array($rsvp)) {
        $rows = $rsvp;
        $list_label = 'HOF-level';
      }

      // Apply business filters: show only rows where Sector and Sub_Sector are present
      // and Inactive_Status is empty/null/blank.
      $filteredRows = [];
      if (!empty($rows) && is_array($rows)) {
        $filteredRows = array_values(array_filter($rows, function($m) {
          $sector = '';
          $sub = '';
          $inactiveRaw = null;
          if (is_array($m)) {
            $sector = trim((string)($m['sector'] ?? $m['Sector'] ?? ''));
            $sub = trim((string)($m['sub_sector'] ?? $m['Sub_Sector'] ?? $m['SubSector'] ?? ''));
            // Determine inactive value: prefer explicit keys; if missing, treat as null
            if (array_key_exists('Inactive_Status', $m)) {
              $inactiveRaw = $m['Inactive_Status'];
            } elseif (array_key_exists('inactive_status', $m)) {
              $inactiveRaw = $m['inactive_status'];
            } elseif (array_key_exists('InactiveStatus', $m)) {
              $inactiveRaw = $m['InactiveStatus'];
            } else {
              $inactiveRaw = null;
            }
          }
          // Sector and Sub_Sector must be non-empty, and Inactive must be NULL (or key missing)
          return $sector !== '' && $sub !== '' && ($inactiveRaw === null);
        }));
      }

      // Replace rows with filteredRows for display and counts
      $rows = $filteredRows;

      $yes_count = 0;
      if (!empty($rows)) {
        foreach ($rows as $member) {
          if (isset($member['rsvp_status']) && $member['rsvp_status'] == 1) {
            $yes_count++;
          }
        }
      }
      ?>
      <?php if (!empty($rows)): ?>
        <div class="alert alert-info mb-0" style="font-size: 1.1rem; border-radius: 0.25rem;">
          <strong><?php echo $list_label; ?> RSVP Yes Count:</strong> <span class="fw-bold text-success"><?php echo $yes_count; ?></span> / <span class="fw-bold text-primary"><?php echo count($rows); ?></span>
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
              <?php foreach ($rows as $member): ?>
                <?php $is_yes = isset($member['rsvp_status']) && $member['rsvp_status'] == 1; ?>
                <tr<?php if ($is_yes): ?> style="background-color: #d4edda;" <?php endif; ?>>
                  <td><?php echo htmlspecialchars($member['full_name'] ?? $member['Full_Name'] ?? $member['name'] ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($member['ITS_ID'] ?? $member['hof_id'] ?? $member['ITS'] ?? ''); ?></td>
                  <td>
                    <?php
                    $mobileVal = $member['mobile'] ?? $member['Mobile'] ?? $member['RFM_Mobile'] ?? $member['rfm_mobile'] ?? '';
                    if (!empty($mobileVal)) {
                      $digits = preg_replace('/\D/', '', $mobileVal);
                      echo htmlspecialchars(substr($digits, -10));
                    } else {
                      echo '-';
                    }
                    ?>
                  </td>
                  <td><?php echo htmlspecialchars($member['sector'] ?? $member['Sector'] ?? '-'); ?></td>
                  <td><?php echo htmlspecialchars($member['sub_sector'] ?? $member['Sub_Sector'] ?? $member['SubSector'] ?? '-'); ?></td>
                  <td>
                    <?php if ($is_yes): ?>
                      <span class="badge badge-success">Yes</span>
                    <?php else: ?>
                      <span class="badge badge-danger">No</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo htmlspecialchars($member['rsvp_comment'] ?? $member['comment'] ?? ''); ?></td>
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
