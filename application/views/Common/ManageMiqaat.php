<style>
  .miqaat-list-container {
    width: 100%;
  }

  .d-grid>* {
    width: 100%;
    min-width: 0;
  }

  .d-grid .btn {
    width: 100%;
  }
</style>
<div class="margintopcontainer p-5">
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
  <div class="mb-3 p-0">
    <a href="<?php echo base_url('admin/managefmbsettings'); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <div class="create-miqaat-btn d-flex">
    <form method="post" action="<?php echo base_url('common/managemiqaat'); ?>" id="filter-form" class="d-flex m-0 my-2">
      <div class="form-group mr-3">
        <select name="hijri_month" id="hijri-month" class="form-control">
          <option value="">Select Month / Year</option>
          <option value="-3" <?php echo (isset($hijri_month_id) ? $hijri_month_id : 0) == -3 ? "selected" : ""; ?>>Last Year</option>
          <option value="-1" <?php echo (isset($hijri_month_id) ? $hijri_month_id : 0) == -1 ? "selected" : ""; ?>>Current Year</option>
          <?php
          if (isset($hijri_months)) {
            foreach ($hijri_months as $key => $value) {
          ?>
              <option value="<?php echo $value['id']; ?>" <?php echo isset($hijri_month_id) && $value['id'] == $hijri_month_id ? 'selected' : ''; ?>><?php echo $value['hijri_month']; ?></option>
          <?php
            }
          }
          ?>
        </select>
      </div>
      <div class="sort-options">
        <select id="sort-type" name="sort_type" class="form-control">
          <option value="asc" <?php echo isset($sort_type) ? ($sort_type == 'asc' ? 'selected' : '') : "" ?>>Sort by Date &darr;</option>
          <option value="desc" <?php echo isset($sort_type) ? ($sort_type == 'desc' ? 'selected' : '') : "" ?>>Sort by Date &uarr;</option>
        </select>
      </div>
      <div class="clear-filter-btn">
        <a href="<?php echo base_url('common/managemiqaat'); ?>" id="clear-filter" class="btn btn-secondary mx-3"><i class="fa fa-times"></i></a>
      </div>
    </form>
    <div class="ml-auto">
      <a href="<?php echo base_url('common/createmiqaat?date=' . date('Y-m-d')); ?>" class="btn btn-primary">Add Miqaat</a>
    </div>
  </div>
  <div class="m-0 mb-3">
    <h3 class="text-center">Manage Miqaat For Year - <?php echo isset($hijri_year) ? $hijri_year : ""; ?></h3>
  </div>
  <div class="miqaat-list-container">
    <div class="border">
      <table class="table table-striped table-bordered mb-0">
        <thead class="thead-dark">
          <tr>
            <th class="sno">#</th>
            <th>Eng Date</th>
            <th>Hijri Date</th>
            <th>Day</th>
            <th>Name</th>
            <th>Type</th>
            <th>Assigned to</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Group miqaats by hijri month
          $monthWiseMiqaats = [];
          if (!empty($miqaats)) {
            foreach ($miqaats as $day) {
              $hijriMonth = '';
              if (!empty($day['hijri_date_with_month'])) {
                $parts = explode(' ', $day['hijri_date_with_month'], 2);
                $hijriMonth = isset($parts[1]) ? $parts[1] : '';
              }
              if ($hijriMonth) {
                if (!isset($monthWiseMiqaats[$hijriMonth])) {
                  $monthWiseMiqaats[$hijriMonth] = [];
                }
                $monthWiseMiqaats[$hijriMonth][] = $day;
              }
            }
          }
          ?>
          <?php if (!empty($monthWiseMiqaats)): ?>
            <?php $sno = 1; ?>
            <?php foreach ($monthWiseMiqaats as $monthName => $days): ?>
              <tr class="table-info">
                <td colspan="9" class="text-center" style="font-weight:bold;">Hijri Month: <?php echo $monthName; ?></td>
              </tr>
              <?php foreach ($days as $day): ?>
                <?php
                $dayName = isset($day['date']) ? date('l', strtotime($day['date'])) : '';
                $rowClass = ($dayName === 'Sunday') ? 'style="background:#ffe5e5"' : '';
                ?>
                <?php if (!empty($day['miqaats'])): ?>
                  <?php foreach ($day['miqaats'] as $miqaat): ?>
                    <tr <?php echo $rowClass; ?>>
                      <td class="sno"><?php echo $sno++; ?></td>
                      <td><?php echo date('d M Y', strtotime($day['date'])); ?></td>
                      <td><?php echo $day['hijri_date_with_month']; ?></td>
                      <td><?php echo $dayName; ?></td>
                      <td><?php echo $miqaat['name']; ?></td>
                      <td><?php echo $miqaat['type']; ?></td>
                      <td>
                        <?php if (!empty($miqaat['assignments'])): ?>
                          <?php foreach ($miqaat['assignments'] as $assignment): ?>
                            <?php if (isset($assignment['assign_type']) && $assignment['assign_type'] === "Group"): ?>
                              <strong>Group: <?php echo $assignment['group_name'] ?></strong><br><br><strong>Leader:</strong> <?php echo $assignment['group_leader_name']; ?> (<?php echo $assignment['group_leader_id']; ?>)
                              <?php if (!empty($assignment['members'])): ?>
                                <br><br>
                                <strong>Co-leader:</strong>
                                <?php foreach ($assignment['members'] as $member): ?>
                                  <?php echo $member['name'] ?> (<?php echo $member['id'] ?>)
                                <?php endforeach; ?>
                              <?php endif; ?>
                            <?php elseif (isset($assignment['assign_type']) && $assignment['assign_type'] === "Individual"): ?>
                              <?php echo $assignment['member_name'] ?> (<?php echo $assignment['member_id'] ?>)<br>
                            <?php endif; ?>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <strong><?php echo $miqaat['assigned_to']; ?></strong>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php
                        if (isset($miqaat['invoice_status']) && $miqaat['invoice_status'] == 'Generated') {
                          echo '<span class="badge badge-success">Completed</span><br>';
                        } elseif (isset($miqaat['invoice_status']) && $miqaat['invoice_status'] == 'Partially Generated') {
                          echo '<span class="badge badge-warning text-left">Completed:<br><br> Invoice Partially Generated</span><br>';
                        } else {
                          if ((isset($miqaat['status']))) {
                            if ($miqaat['status'] == 1) {
                              echo '<span class="badge badge-success">Active</span>';
                            } elseif ($miqaat['status'] == 2) {
                              echo '<span class="badge badge-warning">Cancelled</span>';
                            } else {
                              echo '<span class="badge badge-secondary">Inactive</span>';
                            }
                          } else {
                            echo '<span class="badge badge-secondary">N/A</span>';
                          }
                        }
                        ?>
                      </td>
                      <td>
                        <div class="d-grid gap-2" style="display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; gap: 5px;">
                          <!-- Edit Button -->
                          <div>
                            <a href="<?php echo base_url('common/edit_miqaat?id=' . $miqaat['id']); ?>" class="btn btn-sm btn-primary">
                              <i class="fa fa-edit"></i>
                            </a>
                          </div>
                          <!-- Cancel Button -->
                          <div>
                            <form method="POST" action="<?php echo base_url('common/cancel_miqaat'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to cancel this Miqaat?');">
                              <input type="hidden" name="miqaat_id" value="<?php echo $miqaat['id']; ?>">
                              <button type="submit" class="btn btn-sm btn-warning"
                                <?php echo (isset($miqaat['status']) && $miqaat['status'] == 2) ? 'disabled' : ''; ?>>
                                <i class="fa fa-ban"></i>
                              </button>
                            </form>
                          </div>
                          <!-- Make Active Button -->
                          <div>
                            <form method="POST" action="<?php echo base_url('common/activate_miqaat'); ?>" style="display:inline;">
                              <input type="hidden" name="miqaat_id" value="<?php echo $miqaat['id']; ?>">
                              <button type="submit" class="btn btn-sm btn-success"
                                <?php echo (isset($miqaat['status']) && $miqaat['status'] == 1) ? 'disabled' : ''; ?>>
                                <i class="fa fa-check"></i>
                              </button>
                            </form>
                          </div>
                          <!-- Delete Button -->
                          <div>
                            <form method="POST" action="<?php echo base_url('common/delete_miqaat'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this Miqaat?');">
                              <input type="hidden" name="miqaat_id" value="<?php echo $miqaat['id']; ?>">
                              <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i>
                              </button>
                            </form>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr <?php echo $rowClass; ?>>
                    <td class="sno"><?php echo $sno++; ?></td>
                    <td><?php echo date('d M Y', strtotime($day['date'])); ?></td>
                    <td><?php echo $day['hijri_date_with_month']; ?></td>
                    <td><?php echo $dayName; ?></td>
                    <td colspan="5" class="text-center text-muted">
                      No Miqaat found
                      <a href="<?php echo base_url('common/createmiqaat?date=' . $day['date']); ?>" class="btn btn-sm btn-success ml-2">
                        <i class="fa fa-plus"></i>
                      </a>
                    </td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center">No Miqaats found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
  $('#hijri-month, #sort-type').on('change', function() {
    $('#filter-form').submit();
  });

  $(".alert").delay(3000).fadeOut(500);
</script>