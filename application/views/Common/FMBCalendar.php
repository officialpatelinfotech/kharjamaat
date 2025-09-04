<style>
  .filter-container {
    height: 100vh;
    position: sticky;
    overflow-y: auto;
    border-radius: 5px;
  }

  .table-container div {
    border-radius: 5px;
    border: 1px solid #999;
  }

  /* Make all table columns the same width */
  .table th,
  .table td {
    /* width: 11.11%; */
    text-align: center;
    vertical-align: middle;
    /* min-width: 120px;
    max-width: 120px; */
    word-break: break-word;
  }

  .table th.sno,
  .table td.sno {
    width: 5%;
    min-width: 50px;
    max-width: 50px;
  }
</style>
<div class="m-5">
  <div class="row pt-5 mb-4">
    <div class="col-6">
      <a href="<?php echo $active_controller; ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <form method="post" action="<?php echo base_url('common/fmbcalendar?from=' . $from); ?>" id="filter-form" class="col-6 d-flex m-0 justify-content-center">
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
      <!-- <div class="search-btn">
        <button id="search-btn" class="btn btn-primary" type="submit">Filter</button>
      </div> -->
      <div class="clear-filter-btn">
        <a href="<?php echo base_url("common/fmbcalendar?from=$from"); ?>" id="clear-filter" class="btn btn-secondary mx-3">Clear Filter</a>
      </div>
    </form>
  </div>
  <div class="m-0 mb-3">
    <h3 class="text-center">FMB Calendar For Year - <?php echo $hijri_year; ?></h3>
  </div>
  <div class="row">
    <!-- <div class="filter-container col-12 col-md-3 bg-light">
      <div class="col-12">
        <form action=""></form>
      </div>
    </div> -->
    <div class="table-container col-12 col-md-12">
      <div class="col-12 p-0 table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="thead-dark">
            <tr>
              <th class="sno">#</th>
              <th>Day</th>
              <th>Eng Date</th>
              <th>Hijri Date</th>
              <th>Type</th>
              <th>Miqaat Name</th>
              <th>Assigned to</th>
              <th>Thaali Menu</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($calendar)) {
              $sno = 1;
              $last_month = '';
              foreach ($calendar as $row) {
                $date = $row['greg_date'];
                $menu_id = isset($row['menu_id']) ? $row['menu_id'] : '';
                $eng_date = date('d-m-Y', strtotime($date));
                $day = date('l', strtotime($date));
                $rowClass = ($day === 'Sunday') ? 'style="background:#ffe5e5"' : '';
                $type = $row['miqaat_type'];
                $miqaat_name = $row['miqaat_name'];
                $assigned_to = $row['assigned_to'];
                $assignments = isset($row['assignments']) ? $row['assignments'] : [];
                $menu_items = !empty($row['menu_items']) ? implode(', ', $row['menu_items']) : '';
                $contact = $row['contact'];
                $isHoliday = empty($type) && empty($miqaat_name) && empty($menu_items);
                $hijri_date = isset($row['hijri_date']) ? $row['hijri_date'] : '';
                $hijri_parts = explode('-', $hijri_date);
                $hijri_month = isset($hijri_parts[1]) ? $hijri_parts[1] : '';
                $hijri_year = isset($hijri_parts[2]) ? $hijri_parts[2] : '';
                // Show month header if month changes
                if ($hijri_month !== $last_month) {
                  $last_month = $hijri_month;
                  $month_name = isset($row['hijri_month_name']) ? $row['hijri_month_name'] : '';
                  echo '<tr style="background:linear-gradient(90deg,#e0eafc,#cfdef3);font-weight:bold;"><td colspan="10">Hijri Month: ' . $month_name . ' (' . $hijri_month . ') / Year: ' . $hijri_year . '</td></tr>';
                }
            ?>
                <tr <?php echo $rowClass; ?>>
                  <td class="sno"><?php echo $sno++; ?></td>
                  <td><?php echo $day; ?></td>
                  <td><?php echo $eng_date; ?></td>
                  <td><?php echo isset($row['hijri_date_with_month']) ? $row['hijri_date_with_month'] : $hijri_date; ?></td>
                  <?php if ($isHoliday): ?>
                    <td colspan="4">Holiday</td>
                  <?php else: ?>
                    <td><?php echo $isHoliday ? 'Holiday' : $type; ?></td>
                    <td><?php echo $isHoliday ? 'Holiday' : $miqaat_name; ?></td>
                    <td>
                      <?php if ($isHoliday): ?>
                        Holiday
                      <?php elseif (!empty($assignments)): ?>
                        <a href="#" class="show-assignment-details" data-assignments='<?php echo json_encode($assignments); ?>'>
                          <?php echo $assigned_to; ?>
                        </a>
                      <?php else: ?>
                        <?php echo $assigned_to; ?>
                      <?php endif; ?>
                    </td>
                    <td><?php echo $isHoliday ? 'Holiday' : $menu_items; ?></td>
                  <?php endif; ?>
                  <td>
                    <?php if ($isHoliday): ?>
                      <span class="badge badge-success">Holiday</span>
                    <?php elseif ($type == 'Thaali'): ?>
                      <a href="<?php echo base_url('common/edit_menu?id=' . $menu_id . "&from=common/fmbcalendar"); ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                    <?php elseif (!empty($miqaat_name)): ?>
                      <a href="<?php echo base_url('common/edit_miqaat?id=' . $row['miqaat_id'] . "&from=common/fmbcalendar"); ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                    <?php endif; ?>
                  </td>
                </tr>
            <?php
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="modal fade" id="manageDayModal" tabindex="-1" role="dialog" aria-labelledby="manageDayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <form id="manageDayForm" method="post" action="<?php echo base_url('common/update_day'); ?>">
          <div class="modal-header">
            <h5 class="modal-title" id="manageDayModalLabel">Manage Day</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="greg_date" id="greg_date">

            <div class="form-group">
              <label for="day_type">Day Type</label>
              <select name="day_type" id="day_type" class="form-control">
                <option value="">-- Select --</option>
                <option value="Holiday">Holiday</option>
                <option value="Thaali">Thaali Only</option>
                <option value="Miqaat">Miqaat Only</option>
                <option value="Both">Thaali + Miqaat</option>
              </select>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Assignment Details Modal -->
<div class="modal fade" id="assignmentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="assignmentDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assignmentDetailsModalLabel">Assignment Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="assignmentDetailsBody">
        <!-- Details will be injected here -->
      </div>
    </div>
  </div>
</div>
<script>
  $(document).on("click", ".show-assignment-details", function(e) {
    e.preventDefault();
    var assignments = $(this).data("assignments");
    var html = "";
    if (assignments && assignments.length > 0) {
      assignments.forEach(function(assignment) {
        if (assignment.assign_type === "Individual") {
          html += "<div><strong>Individual:</strong> " + assignment.member_name + "<span class='text-muted'> (Mobile: " + (assignment.member_mobile || "N/A") + ")</span></div>";
        } else if (assignment.assign_type === "Group") {
          html += "<div><strong>Sanstha / Group:</strong> " + assignment.group_name + " <br><br><strong>Leader:</strong> " + assignment.group_leader_name + "<span class='text-muted'> (Mobile: " + (assignment.group_leader_mobile || "N/A") + ")</span></div><br>";
          if (assignment.members && assignment.members.length > 0) {
            html += "<div><strong>Co-leader:</strong> " + assignment.members[0].name + " <span class='text-muted'>(Mobile: " + (assignment.members[0].mobile || "N/A") + ")</span></div>";
          }
        }
      });
    } else {
      html = "<div>No assignment details available.</div>";
    }
    $("#assignmentDetailsBody").html(html);
    $("#assignmentDetailsModal").modal("show");
  });
  $(document).on("click", ".manage-day-btn", function() {
    let gregDate = $(this).data("date");
    let hijriDate = $(this).data("hijri");

    $("#greg_date").val(gregDate);

    $("#day_type").val("");
  });

  $('#manageDayModal').on('hidden.bs.modal', function() {
    $('#greg_date').val('');
    $('#day_type').val('');
  });

  $("#hijri-month, #sort-type").on("change", function() {
    $("#filter-form").submit();
  });
</script>