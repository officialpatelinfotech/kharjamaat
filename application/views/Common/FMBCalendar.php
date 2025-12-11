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

  /* Ensure Hijri date stays on one line with enough width */
  .table-container .table th.hijri-col,
  .table-container .table td.hijri-col {
    white-space: nowrap !important;
    min-width: 240px;
  }

  /* Mobile-first responsive refinements */
  @media (max-width: 576px) {

    /* Reduce outer spacing on small screens */
    .page-wrap {
      margin: 1rem !important;
    }

    .filter-row {
      padding-top: 4rem !important;
      margin-bottom: 0.75rem !important;
    }

    /* Stack filter controls and allow wrapping */
    #filter-form {
      flex-wrap: wrap;
      justify-content: flex-start !important;
    }

    #filter-form .form-group {
      width: 100%;
      margin: 0.25rem 0 !important;
    }

    #filter-form .search-btn,
    #filter-form .clear-filter-btn {
      width: auto;
      margin-top: 0.25rem;
    }

    #filter-form .btn {
      padding: 0.4rem 0.6rem;
      font-size: 0.9rem;
    }

    /* Compact badges and allow wrapping */
    .badge {
      margin: 0.25rem !important;
      padding: 0.35rem 0.5rem !important;
      font-size: 0.85rem;
    }

    /* Table readability on mobile */
    .table-container .table {
      font-size: 0.9rem;
    }

    .table-container .table th,
    .table-container .table td {
      white-space: nowrap;
    }

    .table-container .table td:nth-child(2),
    .table-container .table td:nth-child(3) {
      white-space: normal;
    }

    /* Month header visibility */
    .month-header td {
      font-size: 0.95rem;
    }
  }

  /* Stats cards grid */
  .stats-cards-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    width: 100%;
    max-width: 1140px;
    margin: 0 16px;
  }

  .stat-card {
    border-radius: 10px;
    padding: 12px 14px;
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(16, 24, 40, .06);
    text-align: center;
    height: 96px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-direction: column;
    background: transparent; /* avoid white */
    border: 1px solid #94a3b8; /* slate-400 */
  }

  .stat-card .label {
    display: block;
    font-weight: 600;
    font-size: 14px;
    color: #374151; /* gray-700: not black */
  }

  .stat-card .value {
    display: block;
    font-weight: 800;
    font-size: 1.35rem;
    line-height: 1.1;
    margin-top: 6px;
    color: #1f2937; /* gray-800: not black */
  }

  /* Per-card accents */
  .stat-card.general {
    background: #bfdbfe; /* blue-200 */
    border-color: #3b82f6; /* blue-500 */
  }

  .stat-card.holidays {
    background: #fed7aa; /* orange-200 */
    border-color: #fb923c; /* orange-500 */
  }

  .stat-card.sundays {
    background: #fbcfe8; /* pink-200 */
    border-color: #f472b6; /* pink-500 */
  }

  .stat-card.individual {
    background: #ddd6fe; /* indigo-200 */
    border-color: #8b5cf6; /* indigo-500 */
  }

  .stat-card.group {
    background: #d1fae5; /* emerald-100 */
    border-color: #10b981; /* emerald-500 */
  }

  .stat-card.fnn {
    background: #fde68a; /* amber-200 */
    border-color: #f59e0b; /* amber-500 */
  }

  .stat-card.thaali {
    background: #bae6fd; /* sky-200 */
    border-color: #06b6d4; /* cyan-500 */
  }

  @media (min-width: 768px) {
    .stats-cards-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }

  @media (min-width: 992px) {
    .stats-cards-grid {
      grid-template-columns: repeat(6, 1fr);
      margin: 0 auto;
    }
  }
</style>

<div class="pt-5">
  <div class="pt-5 mb-2 mx-md-5">
    <div class="col-12 mb-2">
      <a href="<?php echo $active_controller; ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <div class="m-0 mb-3 mt-3">
    <h4 class="text-center">FMB Calendar For Year - <?php echo $hijri_year; ?></h4>
    <div class="container">
      <form method="post" action="<?php echo base_url('common/fmbcalendar?from=' . $from); ?>" id="filter-form" class="my-3 p-3 rounded shadow-sm d-flex justify-content-center align-items-center">
        <div class="form-group">
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
        <div class="form-group mx-3">
          <input type="text" class="form-control" name="member_name_filter" id="member-name-filter" placeholder="Filter by member/group" value="<?php echo isset($member_name_filter) ? htmlspecialchars($member_name_filter) : ''; ?>">
        </div>
        <div class="form-group mx-3">
          <select id="assignment-filter" name="assignment_filter" class="form-control">
            <option value="">All (Assigned + Unassigned)</option>
            <option value="assigned" <?php echo (isset($assignment_filter) && $assignment_filter === 'assigned') ? 'selected' : ''; ?>>Assigned Only</option>
            <option value="unassigned" <?php echo (isset($assignment_filter) && $assignment_filter === 'unassigned') ? 'selected' : ''; ?>>Unassigned Only</option>
          </select>
        </div>
        <div class="form-group">
          <select id="miqaat-type" name="miqaat_type" class="form-control">
            <option value="">All Miqaat Types</option>
            <?php if (!empty($miqaat_types)) {
              foreach ($miqaat_types as $type) { ?>
                <option value="<?php echo htmlspecialchars($type); ?>" <?php echo (isset($miqaat_type) && $miqaat_type === $type) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type); ?></option>
            <?php }
            } ?>
            <option value="Thaali" <?php echo (isset($miqaat_type) && $miqaat_type === 'Thaali') ? 'selected' : ''; ?>>Thaali</option>
          </select>
        </div>
        <div class="form-group search-btn">
          <button id="search-btn" class="btn btn-primary mx-2" type="submit"><i class="fa-solid fa-filter mr-1"></i> Filter</button>
        </div>
        <div class="form-group clear-filter-btn">
          <a href="<?php echo base_url("common/fmbcalendar?from=$from"); ?>" id="clear-filter" class="btn btn-secondary mx-3"><i class="fa-solid fa-times"></i></a>
        </div>
      </form>
    </div>
    <!-- <?php if (!empty($year_daytype_stats)) { ?>
      <div class="d-flex justify-content-center flex-wrap my-3 stats-badges mx-auto text-center">
        <div class="badge badge-primary mx-2 p-2">Miqaat days: <strong><?php echo (int)$year_daytype_stats['miqaat_days']; ?></strong></div>
        <div class="badge badge-success mx-2 p-2">Thaali days: <strong><?php echo (int)$year_daytype_stats['thaali_days']; ?></strong></div>
        <div class="badge badge-secondary mx-2 p-2">Holidays: <strong><?php echo (int)$year_daytype_stats['holiday_days']; ?></strong></div>
      </div>
    <?php } ?> -->
    <?php
    // Aggregated counts for the currently displayed rows
    $summary_individual = 0;
    $summary_group = 0;
    $summary_fnn = 0;
    $summary_miqaat_rows = 0;
    $summary_general_miqaats = 0; // legacy: general miqaats excluding Shehrullah, Ashara, Ladies
    $summary_miqaat_days_excl_ladies = 0; // Total Miqaat Days excluding Ladies
    $summary_holidays_excl_sundays = 0;
    $summary_sundays_without_miqaat_thaali = 0;
    $summary_thaali_days = 0;
    if (!empty($calendar)) {
      foreach ($calendar as $row) {
        $type = isset($row['miqaat_type']) ? $row['miqaat_type'] : '';
        $miqaat_name = isset($row['miqaat_name']) ? $row['miqaat_name'] : '';
        $menu_items_present = !empty($row['menu_items']) && (!is_array($row['menu_items']) || count($row['menu_items']) > 0);
        $isHoliday = empty($type) && empty($miqaat_name) && !$menu_items_present;
        $isSunday = (strtolower(date('l', strtotime($row['greg_date']))) === 'sunday');

        // Holidays excluding Sundays
        if ($isHoliday && !$isSunday) {
          $summary_holidays_excl_sundays++;
        }
        // Sundays without any Miqaat on that day (regardless of Thaali/menu)
        if ($isSunday) {
          $hasMiqaat = !empty($miqaat_name) || (isset($type) && in_array(strtolower($type), ['miqaat', 'both']));
          if (!$hasMiqaat) {
            $summary_sundays_without_miqaat_thaali++;
          }
        }

        // Thaali day count
        if (strtolower($type) === 'thaali') {
          $summary_thaali_days++;
        }

        if (!$isHoliday && $type !== 'Thaali') {
          $summary_miqaat_rows++;
          $assignments = isset($row['assignments']) ? $row['assignments'] : [];
          // Robust Fala ni Niyaz detection: consider miqaat_type, miqaat_name, assignment types, and 'fnn' acronym,
          // normalize by removing non-letters and collapsing repeated vowels. Also include assigned_to field.
          $hay_parts = [(string)$type, (string)$miqaat_name, (string)($row['assigned_to'] ?? '')];
          if (!empty($assignments)) {
            foreach ($assignments as $as_chk) {
              $hay_parts[] = (string)($as_chk['assign_type'] ?? '');
            }
          }
          $hay = strtolower(trim(implode(' ', $hay_parts)));
          $basic = preg_replace('/\s+/', ' ', $hay);
          $letters = preg_replace('/[^a-z]/', '', $basic);
          $letters = preg_replace(['/a{2,}/', '/i{2,}/', '/y{2,}/'], ['a', 'i', 'y'], $letters);
          $is_fnn = (strpos($basic, 'fnn') !== false)
            || ((strpos($basic, 'fala') !== false) && (strpos($basic, 'niyaz') !== false || strpos($basic, 'niaz') !== false))
            || ((strpos($letters, 'fala') !== false) && (strpos($letters, 'niyaz') !== false || strpos($letters, 'niaz') !== false));

          // Fala ni Niyaaz count
          if ($is_fnn) {
            $summary_fnn++;
          }
          // Total Miqaat Days excluding Ladies
          $t_lower = strtolower($type);
          if ($t_lower !== 'ladies') {
            $summary_miqaat_days_excl_ladies++;
          }
          // Per-row flags: count each miqaat row once for Individual/Group presence
          $row_has_individual = false;
          $row_has_group = false;
          foreach ($assignments as $as) {
            $t = isset($as['assign_type']) ? strtolower($as['assign_type']) : '';
            if ($t === 'individual') {
              $row_has_individual = true;
            } elseif ($t === 'group') {
              $row_has_group = true;
            } else {
              // Count FNN across typical variations in assignment labels
              $t_basic = preg_replace('/\s+/', ' ', $t);
              $t_letters = preg_replace('/[^a-z]/', '', $t_basic);
              $t_letters = preg_replace(['/a{2,}/', '/i{2,}/', '/y{2,}/'], ['a', 'i', 'y'], $t_letters);
              if (
                strpos($t_basic, 'fnn') !== false
                || (strpos($t_basic, 'fala') !== false && (strpos($t_basic, 'niyaz') !== false || strpos($t_basic, 'niaz') !== false))
                || (strpos($t_letters, 'fala') !== false && (strpos($t_letters, 'niyaz') !== false || strpos($t_letters, 'niaz') !== false))
              ) {
                // If assignment label itself indicates FNN, count once per row
                $summary_fnn++;
              }
            }
          }
          if ($row_has_individual) $summary_individual++;
          if ($row_has_group) $summary_group++;
        }
      }
    }
    ?>
    <div class="d-flex justify-content-center">
      <div class="stats-cards-grid">
        <div class="stat-card general"><span class="label">Total Miqaat Days</span><span class="value"><?php echo (int)$summary_miqaat_days_excl_ladies; ?></span></div>
        <div class="stat-card thaali"><span class="label">Total Thaali Days</span><span class="value"><?php echo (int)$summary_thaali_days; ?></span></div>
        <?php $summary_sundays_or_utlal = (int)$summary_holidays_excl_sundays + (int)$summary_sundays_without_miqaat_thaali; ?>
        <div class="stat-card holidays"><span class="label">Sundays + Utlat</span><span class="value"><?php echo $summary_sundays_or_utlal; ?></span></div>
        <div class="stat-card individual"><span class="label">Individual Niyaaz</span><span class="value"><?php echo (int)$summary_individual; ?></span></div>
        <div class="stat-card group"><span class="label">Group Niyaaz</span><span class="value"><?php echo (int)$summary_group; ?></span></div>
        <div class="stat-card fnn"><span class="label">Fala ni Niyaaz</span><span class="value"><?php echo (int)$summary_fnn; ?></span></div>
      </div>
    </div>
  </div>
  <div class="row mx-2 mx-md-5">
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
              <th class="sno" data-no-sort>#</th>
              <th>Eng Date</th>
              <th class="hijri-col">Hijri Date</th>
              <th class="day-col" style="white-space:nowrap;">Day</th>
              <th>Type</th>
              <th>Miqaat Name</th>
              <th>Assigned to</th>
              <th>Thaali Menu</th>

            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($calendar)) {
              $sno = 1;
              $last_month = '';
              if (empty($calendar)) {
                $msg = 'No rows match the selected filters.';
                if (!empty($miqaat_type) || !empty($member_name_filter) || !empty($assignment_filter)) {
                  $parts = [];
                  if (!empty($miqaat_type)) {
                    $parts[] = 'type: ' . htmlspecialchars($miqaat_type);
                  }
                  if (!empty($member_name_filter)) {
                    $parts[] = 'member: ' . htmlspecialchars($member_name_filter);
                  }
                  if (!empty($assignment_filter)) {
                    $parts[] = 'assignment: ' . htmlspecialchars($assignment_filter);
                  }
                  $msg .= ' (' . implode(', ', $parts) . ')';
                }
                echo '<tr><td colspan="9" class="text-center text-muted">' . $msg . '</td></tr>';
              }
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
                  echo '<tr class="month-header" data-hijri-month="' . htmlspecialchars($hijri_month, ENT_QUOTES) . '" data-hijri-month-name="' . htmlspecialchars($month_name, ENT_QUOTES) . '" data-hijri-year="' . htmlspecialchars($hijri_year, ENT_QUOTES) . '" style="background:linear-gradient(90deg,#e0eafc,#cfdef3);font-weight:bold;"><td colspan="9">Hijri Month: ' . $month_name . ' (' . $hijri_month . ') / Year: ' . $hijri_year . '</td></tr>';
                }
            ?>
                <?php
                // Prepare sortable keys
                $eng_sort_value = $date; // YYYY-MM-DD
                // Hijri sort value from dd-mm-YYYY -> YYYY-MM-DD
                $hijri_sort_value = '';
                if (!empty($hijri_date)) {
                  $hp = explode('-', $hijri_date);
                  if (count($hp) === 3) {
                    $hijri_sort_value = sprintf('%04d-%02d-%02d', (int)$hp[2], (int)$hp[1], (int)$hp[0]);
                  }
                }
                ?>
                <tr <?php echo $rowClass; ?> data-hijri-month="<?php echo htmlspecialchars($hijri_month, ENT_QUOTES); ?>" data-hijri-month-name="<?php echo htmlspecialchars($month_name, ENT_QUOTES); ?>" data-hijri-year="<?php echo htmlspecialchars($hijri_year, ENT_QUOTES); ?>">
                  <td class="sno"><?php echo $sno++; ?></td>
                  <td data-sort-value="<?php echo htmlspecialchars($eng_sort_value, ENT_QUOTES); ?>"><?php echo date("d M Y", strtotime($eng_date)); ?></td>
                  <td class="hijri-col" data-sort-value="<?php echo htmlspecialchars($hijri_sort_value, ENT_QUOTES); ?>"><?php echo isset($row['hijri_date_with_month']) ? $row['hijri_date_with_month'] : $hijri_date; ?></td>
                  <td class="day-col" style="white-space:nowrap;"><?php echo $day; ?></td>
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

  $("#hijri-month, #assignment-filter").on("change", function() {
    $("#filter-form").submit();
  });
  $("#miqaat-type").on("change", function() {
    $("#filter-form").submit();
  });
  // Make the Assigned/Unassigned badges clickable to apply the corresponding filter instantly
  $(document).on('click', '.assignment-badge', function(e) {
    e.preventDefault();
    var val = $(this).data('assignment');
    $("#assignment-filter").val(val);
    $("#filter-form").submit();
  });
</script>
<script>
  // Improved client-side column sorting keeping month headers grouped with their rows.
  (function() {
    const table = document.querySelector('.table.table-striped.table-bordered');
    if (!table) return;
    const thead = table.querySelector('thead');
    const tbody = table.querySelector('tbody');
    if (!thead || !tbody) return;

    // Mark sortable headers
    thead.querySelectorAll('th').forEach((th, idx) => {
      if (th.hasAttribute('data-no-sort')) return; // skip unsortable columns
      th.classList.add('sortable');
      const original = th.innerHTML.trim();
      th.innerHTML = '<span class="sort-label">' + original + '</span><span class="sort-indicator" aria-hidden="true"></span>';
      th.setAttribute('role', 'button');
      th.setAttribute('tabindex', '0');
      th.addEventListener('click', () => toggleSort(idx, th));
      th.addEventListener('keydown', e => {
        if (['Enter', ' '].includes(e.key)) {
          e.preventDefault();
          toggleSort(idx, th);
        }
      });
    });

    function getCellValue(tr, index) {
      const cells = tr.querySelectorAll('td');
      if (!cells[index]) return '';
      return cells[index].getAttribute('data-sort-value') || cells[index].textContent.trim();
    }

    function inferType(val) {
      if (/^\d{4}-\d{2}-\d{2}$/.test(val)) return 'date';
      if (/^\d{2}-\d{2}-\d{4}$/.test(val)) return 'date_dmY';
      if (!isNaN(parseFloat(val)) && isFinite(val)) return 'number';
      return 'text';
    }

    function norm(val) {
      const t = inferType(val);
      if (t === 'date') return new Date(val).getTime();
      if (t === 'date_dmY') {
        const p = val.split('-');
        return new Date(p[2] + '-' + p[1] + '-' + p[0]).getTime();
      }
      if (t === 'number') return parseFloat(val);
      return val.toLowerCase();
    }

    function toggleSort(idx, th) {
      const newDir = th.dataset.sortDir === 'asc' ? 'desc' : 'asc';
      thead.querySelectorAll('th.sortable').forEach(h => {
        h.dataset.sortDir = '';
        const ind = h.querySelector('.sort-indicator');
        if (ind) ind.textContent = '';
      });
      th.dataset.sortDir = newDir;
      const ind = th.querySelector('.sort-indicator');
      if (ind) ind.textContent = newDir === 'asc' ? '▲' : '▼';

      // Collect only data rows (exclude month headers by class)
      const allRows = Array.from(tbody.querySelectorAll('tr'));
      const monthHeaderRows = allRows.filter(r => r.classList.contains('month-header'));
      const dataRows = allRows.filter(r => !r.classList.contains('month-header'));

      dataRows.sort((a, b) => {
        const va = norm(getCellValue(a, idx));
        const vb = norm(getCellValue(b, idx));
        if (va < vb) return newDir === 'asc' ? -1 : 1;
        if (va > vb) return newDir === 'asc' ? 1 : -1;
        return 0;
      });

      // Remove existing month headers
      monthHeaderRows.forEach(r => r.remove());

      // Rebuild tbody with dynamic month headers preceding first row of each month group in current order
      tbody.innerHTML = '';
      const insertedMonthKeys = new Set();
      dataRows.forEach(r => {
        const m = r.getAttribute('data-hijri-month');
        const y = r.getAttribute('data-hijri-year');
        const mn = r.getAttribute('data-hijri-month-name');
        const key = m + '-' + y;
        if (!insertedMonthKeys.has(key)) {
          const header = document.createElement('tr');
          header.className = 'month-header';
          header.setAttribute('data-hijri-month', m);
          header.setAttribute('data-hijri-month-name', mn);
          header.setAttribute('data-hijri-year', y);
          header.style.background = 'linear-gradient(90deg,#e0eafc,#cfdef3)';
          header.style.fontWeight = 'bold';
          const td = document.createElement('td');
          td.colSpan = 9;
          td.textContent = 'Hijri Month: ' + mn + ' (' + m + ') / Year: ' + y;
          header.appendChild(td);
          tbody.appendChild(header);
          insertedMonthKeys.add(key);
        }
        tbody.appendChild(r);
      });
      renumber();
    }

    function renumber() {
      let i = 1;
      tbody.querySelectorAll('td.sno').forEach(td => td.textContent = i++);
    }
  })();
</script>
<style>
  th.sortable {
    cursor: pointer;
    user-select: none;
    position: relative;
  }

  th.sortable:focus {
    outline: 2px solid #0d6efd;
    outline-offset: 2px;
  }

  th.sortable .sort-indicator {
    margin-left: 4px;
    font-size: 0.65rem;
  }
</style>