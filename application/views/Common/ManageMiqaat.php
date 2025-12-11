<style>
  .miqaat-list-container {
    width: 100%;
    overflow-x: auto;
    /* enable horizontal scroll on small screens */
  }

  .d-grid>* {
    width: 100%;
    min-width: 0;
  }

  .d-grid .btn {
    width: 100%;
  }

  /* Mobile-friendly tweaks */
  @media (max-width: 576px) {
    .create-miqaat-btn {
      flex-direction: column;
      gap: 10px;
    }

    #filter-form {
      flex-direction: column !important;
      gap: 8px !important;
    }

    #filter-form .form-group {
      width: 100%;
    }

    .ml-auto {
      width: 100%;
      display: flex;
      justify-content: flex-start;
    }

    .stats-cards-grid {
      flex-wrap: wrap !important;
    }

    .stat-card {
      min-width: 140px;
      padding: 10px 12px;
      flex: 0 0 calc(50% - 12px);
      width: calc(50% - 12px);
    }

    .stat-card .value {
      font-size: 20px;
    }

    .stat-card .label {
      font-size: 12px;
    }

    table.table {
      font-size: 12px;
    }

    table.table td,
    table.table th {
      padding: .4rem;
    }

    .d-grid.gap-2 {
      grid-template-columns: 1fr 1fr !important;
      grid-template-rows: auto auto !important;
    }
  }
</style>
<div class="margintopcontainer pt-5 px px-3">
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
  <div class="m-0 mb-3">
  </div>
  <div class="mb-3 p-0 d-flex align-items-center" style="gap:8px;">
    <a href="<?php echo isset($from) ? base_url($from) : base_url("anjuman/fmbthaali"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="text-center">Manage Miqaat For Year - <?php echo isset($hijri_year) ? $hijri_year : ""; ?></h4>
  <div class="create-miqaat-btn d-flex">
    <form method="post" action="<?php echo base_url('common/managemiqaat'); ?>" id="filter-form" class="shadow-sm p-3 d-flex my-2 align-items-start" style="flex-wrap:wrap; gap:10px;">
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
      <div class="form-group">
        <input type="text" name="member_name_filter" id="member-name-filter" class="form-control" placeholder="Member / Leader name" value="<?php echo isset($member_name_filter) ? htmlspecialchars($member_name_filter, ENT_QUOTES) : ''; ?>" />
      </div>
      <div class="form-group">
        <select id="assignment-filter" name="assignment_filter" class="form-control">
          <option value="" <?php echo empty($assignment_filter) ? 'selected' : ''; ?>>All Miqaats</option>
          <option value="unassigned" <?php echo (isset($assignment_filter) && $assignment_filter === 'unassigned') ? 'selected' : ''; ?>>Unassigned Only</option>
          <option value="assigned" <?php echo (isset($assignment_filter) && $assignment_filter === 'assigned') ? 'selected' : ''; ?>>Assigned Only</option>
        </select>
      </div>
      <div class="form-group">
        <select id="miqaat-type" name="miqaat_type" class="form-control">
          <option value="">All Types</option>
          <?php if (!empty($miqaat_types)) {
            foreach ($miqaat_types as $type) { ?>
              <option value="<?php echo htmlspecialchars($type, ENT_QUOTES); ?>" <?php echo (isset($miqaat_type) && $miqaat_type === $type) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($type); ?>
              </option>
          <?php }
          } ?>
        </select>
      </div>
      <div class="form-group d-flex" style="gap:6px;">
        <button type="submit" class="btn btn-primary" id="apply-filters-btn">Filter</button>
        <a href="<?php echo base_url('common/managemiqaat'); ?>" class="btn btn-outline-secondary" title="Clear all filters">X</a>
      </div>
      <!-- <div class="clear-filter-btn">
        <a href="<?php echo base_url('common/managemiqaat'); ?>" id="clear-filter" class="btn btn-secondary mx-3"><i class="fa fa-times"></i></a>
      </div> -->
    </form>
    <div class="ml-auto">
      <a href="<?php echo base_url('common/createmiqaat?date=' . date('Y-m-d')); ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Miqaat</a>
    </div>
  </div>

  <!-- Summary cards below filters (safe defaults if controller not set) -->
  <style>
    .stats-cards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
      align-items: stretch;
      justify-content: center;
      margin: 0 auto;
      max-width: 1100px;
      padding: 6px;
      box-sizing: border-box;
    }

    /* Smaller screens: two columns is fine */
    @media (max-width: 768px) {
      .stats-cards-grid { grid-template-columns: repeat(2, minmax(140px, 1fr)); gap: 12px; }
      .stat-card { min-width: auto; width: 100%; }
    }

    .stat-card {
      border-radius: 16px;
      padding: 12px 14px; /* slightly smaller */
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-width: 160px; /* slightly smaller */
      gap: 6px;
      box-sizing: border-box;
    }

    .stat-card .label {
      font-weight: 600;
      color: #0f172a;
      margin-bottom: 6px;
    }

    .stat-card .value {
      font-size: 22px; /* slightly smaller */
      font-weight: 700;
      color: #0f172a;
    }

    /* Force table columns to a single line; enable horizontal scroll if needed */
    .miqaat-table-container {
      overflow-x: auto;
    }
    .miqaat-table {
      table-layout: auto; /* natural column widths */
      width: max-content; /* expand to fit content; scroll container handles overflow */
    }
    .miqaat-table th,
    .miqaat-table td {
      white-space: nowrap;
      overflow: visible;        /* let columns size naturally */
      text-overflow: clip;      /* no ellipsis to avoid cramped look */
      word-break: normal;       /* do not break words */
      overflow-wrap: normal;    /* prevent wrapping within words */
      hyphens: none;            /* avoid automatic hyphenation */
    }

    .stat-card.general {
      background: #dbeafe;
    }

    .stat-card.thaali {
      background: #e0f2fe;
    }

    .stat-card.holidays {
      background: #ffedd5;
    }

    .stat-card.individual {
      background: #e9d5ff;
    }

    .stat-card.group {
      background: #d1fae5;
    }

    .stat-card.fnn {
      background: #fef3c7;
    }
  </style>
  <div>
    <?php
    // Derive counts from visible dataset when summary vars are not provided
    $sum_total_miqaats = isset($summary_miqaat_days) ? (int)$summary_miqaat_days : 0;
    $sum_total_thaali_days = isset($summary_total_thaali_days) ? (int)$summary_total_thaali_days : 0; // not used on Miqaat page; keep for consistency
    $sum_sundays = isset($summary_sundays) ? (int)$summary_sundays : 0;
    $sum_individual = isset($summary_individual) ? (int)$summary_individual : 0;
    $sum_group = isset($summary_group) ? (int)$summary_group : 0;
    $sum_fnn = isset($summary_fnn) ? (int)$summary_fnn : 0;

    // Recompute lightweight fallbacks only for miqaat/niyaaz counts; do not override Sundays from controller
    if ($sum_total_miqaats === 0 || $sum_individual === 0 || $sum_group === 0 || $sum_fnn === 0) {
      // Compute lightweight fallbacks from $miqaats structure
      $visibleDays = [];
      $sum_total_miqaats = 0;
      $sum_individual = 0;
      $sum_group = 0;
      $sum_fnn = 0;
      // Do not reset $sum_sundays; keep controller value intact
      if (!empty($miqaats) && is_array($miqaats)) {
        foreach ($miqaats as $d) {
          $dayName = isset($d['date']) ? date('l', strtotime($d['date'])) : '';
          $hasMiqaat = !empty($d['miqaats']);
          // Skip recomputing Sundays here; controller provides authoritative count
          if ($hasMiqaat) {
            foreach ($d['miqaats'] as $m) {
              $sum_total_miqaats++;
              $hasInd = false;
              $hasGrp = false;
              if (!empty($m['assignments'])) {
                foreach ($m['assignments'] as $as) {
                  $at = strtolower($as['assign_type'] ?? '');
                  if ($at === 'individual') $hasInd = true;
                  if ($at === 'group') $hasGrp = true;
                }
              }
              if ($hasInd) $sum_individual++;
              if ($hasGrp) $sum_group++;
              $hay = strtolower(trim(((string)($m['type'] ?? '') . ' ' . (string)($m['name'] ?? '') . ' ' . (string)($m['assigned_to'] ?? ''))));
              $letters = preg_replace('/[^a-z]/', '', preg_replace('/\s+/', ' ', $hay));
              $is_fnn = (strpos($hay, 'fnn') !== false)
                || ((strpos($hay, 'fala') !== false) && (strpos($hay, 'niyaz') !== false or strpos($hay, 'niaz') !== false))
                || ((strpos($letters, 'fala') !== false) && (strpos($letters, 'niyaz') !== false or strpos($letters, 'niaz') !== false));
              if ($is_fnn) $sum_fnn++;
            }
          }
        }
      }
    }
    ?>
    <div class="stats-cards-grid my-3" aria-label="Miqaat summary">
      <div class="stat-card general"><span class="label">Total Miqaat Days</span><span class="value"><?php echo isset($calendar_summary['total_miqaat_days']) ? (int)$calendar_summary['total_miqaat_days'] : (isset($summary_miqaat_days) ? (int)$summary_miqaat_days : (int)$sum_total_miqaats); ?></span></div>
      <div class="stat-card thaali"><span class="label">Total Thaali Days</span><span class="value"><?php echo isset($calendar_summary['total_thaali_days']) ? (int)$calendar_summary['total_thaali_days'] : (isset($summary_total_thaali_days) ? (int)$summary_total_thaali_days : (int)$sum_total_thaali_days); ?></span></div>
      <div class="stat-card holidays"><span class="label">Sundays + Utlat</span><span class="value"><?php echo isset($calendar_summary['sundays_utlat']) ? (int)$calendar_summary['sundays_utlat'] : (isset($summary_sundays) ? (int)$summary_sundays : (int)$sum_sundays); ?></span></div>
      <div class="stat-card individual"><span class="label">Individual Niyaaz</span><span class="value"><?php echo isset($calendar_summary['individual']) ? (int)$calendar_summary['individual'] : (int)$sum_individual; ?></span></div>
      <div class="stat-card group"><span class="label">Group Niyaaz</span><span class="value"><?php echo isset($calendar_summary['group']) ? (int)$calendar_summary['group'] : (int)$sum_group; ?></span></div>
      <div class="stat-card fnn"><span class="label">Fala ni Niyaaz</span><span class="value"><?php echo isset($calendar_summary['fnn']) ? (int)$calendar_summary['fnn'] : (int)$sum_fnn; ?></span></div>
    </div>
  </div>
  <div class="miqaat-list-container">
    <div class="border">
      <table class="table table-striped table-bordered mb-0">
        <thead class="thead-dark">
          <tr>
            <th class="sno" data-no-sort>#</th>
            <th>Eng Date</th>
            <th>Hijri Date</th>
            <th>Day</th>
            <th>Name</th>
            <th>Type</th>
            <th>Assigned to</th>
            <th>Status</th>
            <th data-no-sort>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Group miqaats by hijri month
          $monthWiseMiqaats = [];
          if (!empty($miqaats)) {
            // Apply assignment filter if provided
            $filter = isset($assignment_filter) ? $assignment_filter : '';
            $filteredMiqaats = [];
            if ($filter === 'unassigned' || $filter === 'assigned') {
              foreach ($miqaats as $d) {
                if (empty($d['miqaats'])) {
                  continue;
                } // skip days with no miqaat entries entirely
                $dayCopy = $d;
                $dayCopy['miqaats'] = [];
                foreach ($d['miqaats'] as $m) {
                  $hasAssignments = !empty($m['assignments']);
                  if ($filter === 'unassigned' && !$hasAssignments) {
                    $dayCopy['miqaats'][] = $m;
                  } elseif ($filter === 'assigned' && $hasAssignments) {
                    $dayCopy['miqaats'][] = $m;
                  }
                }
                if (!empty($dayCopy['miqaats'])) {
                  $filteredMiqaats[] = $dayCopy;
                }
              }
            } else {
              // Show only days that actually have one or more Miqaats
              $filteredMiqaats = array_values(array_filter($miqaats, function ($d) {
                return !empty($d['miqaats']);
              }));
            }

            // Aggregate counts for the (already filtered) visible rows
            $total_miqaats = 0;
            $ashara_count = 0;
            $shehrullah_count = 0;
            $general_count = 0;
            $ladies_count = 0;
            $individual_niyaaz_count = 0;
            $group_niyaaz_count = 0;
            $fnn_count = 0;

            foreach ($filteredMiqaats as $d) {
              if (empty($d['miqaats'])) continue;
              foreach ($d['miqaats'] as $m) {
                $total_miqaats++;
                $type_l = strtolower(isset($m['type']) ? $m['type'] : '');
                if ($type_l === 'ashara') $ashara_count++;
                if ($type_l === 'shehrullah') $shehrullah_count++;
                if ($type_l === 'general') $general_count++;
                if ($type_l === 'ladies') $ladies_count++;

                // Assignment-based Individual/Group Niyaaz counts (count once per miqaat if present)
                $hasInd = false;
                $hasGrp = false;
                if (!empty($m['assignments'])) {
                  foreach ($m['assignments'] as $as) {
                    $at = strtolower(isset($as['assign_type']) ? $as['assign_type'] : '');
                    if ($at === 'individual') $hasInd = true;
                    if ($at === 'group') $hasGrp = true;
                  }
                }
                if ($hasInd) $individual_niyaaz_count++;
                if ($hasGrp) $group_niyaaz_count++;

                // Robust Fala ni Niyaaz detection against name/type/assignments/assigned_to
                $hay_parts = [
                  (string)($m['type'] ?? ''),
                  (string)($m['name'] ?? ''),
                  (string)($m['assigned_to'] ?? '')
                ];
                if (!empty($m['assignments'])) {
                  foreach ($m['assignments'] as $as_chk) {
                    $hay_parts[] = (string)($as_chk['assign_type'] ?? '');
                  }
                }
                $hay = strtolower(trim(implode(' ', $hay_parts)));
                $basic = preg_replace('/\s+/', ' ', $hay);
                $letters = preg_replace('/[^a-z]/', '', $basic);
                $letters = preg_replace(['/.{0}a{2,}/', '/.{0}i{2,}/', '/.{0}y{2,}/'], ['a', 'i', 'y'], $letters);
                $is_fnn = (strpos($basic, 'fnn') !== false)
                  || ((strpos($basic, 'fala') !== false) && (strpos($basic, 'niyaz') !== false || strpos($basic, 'niaz') !== false))
                  || ((strpos($letters, 'fala') !== false) && (strpos($letters, 'niyaz') !== false || strpos($letters, 'niaz') !== false));
                if ($is_fnn) $fnn_count++;
              }
            }

            // Summary badges removed; cards section above now provides summary

            foreach ($filteredMiqaats as $day) {
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
              <tr class="table-info month-header" data-hijri-month-name="<?php echo htmlspecialchars($monthName, ENT_QUOTES); ?>">
                <td colspan="9" class="text-center" style="font-weight:bold;">Hijri Month: <?php echo $monthName; ?></td>
              </tr>
              <?php foreach ($days as $day): ?>
                <?php
                $dayName = isset($day['date']) ? date('l', strtotime($day['date'])) : '';
                $rowClass = ($dayName === 'Sunday') ? 'style="background:#ffe5e5"' : '';
                ?>
                <?php if (!empty($day['miqaats'])): ?>
                  <?php foreach ($day['miqaats'] as $miqaat): ?>
                    <tr <?php echo $rowClass; ?> data-hijri-month-name="<?php echo htmlspecialchars($monthName, ENT_QUOTES); ?>" data-eng-date="<?php echo htmlspecialchars($day['date'], ENT_QUOTES); ?>">
                      <td class="sno"><?php echo $sno++; ?></td>
                      <td data-sort-value="<?php echo htmlspecialchars($day['date'], ENT_QUOTES); ?>"><?php echo date('d M Y', strtotime($day['date'])); ?></td>
                      <td><?php echo $day['hijri_date_with_month']; ?></td>
                      <td data-sort-value="<?php echo strtolower($dayName); ?>"><?php echo $dayName; ?></td>
                      <td data-sort-value="<?php echo htmlspecialchars(strtolower($miqaat['name']), ENT_QUOTES); ?>"><?php echo $miqaat['name']; ?></td>
                      <td data-sort-value="<?php echo htmlspecialchars(strtolower($miqaat['type']), ENT_QUOTES); ?>"><?php echo $miqaat['type']; ?></td>
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
                          <?php
                          $assignedTo = isset($miqaat['assigned_to']) ? trim($miqaat['assigned_to']) : '';
                          if ($assignedTo === '' || strtolower($assignedTo) === 'n/a') {
                            echo '<span class="badge badge-secondary">Assignment Pending</span>';
                          } else {
                            echo '<strong>' . htmlspecialchars($assignedTo, ENT_QUOTES) . '</strong>';
                          }
                          ?>
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
                              <button type="submit" class="btn btn-sm btn-success" <?php
                                                                                    $noAssignments = empty($miqaat['assignments']);
                                                                                    echo (isset($miqaat['status']) && $miqaat['status'] == 1) || $noAssignments ? 'disabled title="Assign first to activate"' : '';
                                                                                    ?>>
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
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <?php
            $noMsgParts = [];
            if (!empty($member_name_filter)) {
              $noMsgParts[] = 'matching member "' . htmlspecialchars($member_name_filter, ENT_QUOTES) . '"';
            }
            if (!empty($assignment_filter)) {
              if ($assignment_filter === 'unassigned') {
                $noMsgParts[] = 'that are unassigned';
              }
              if ($assignment_filter === 'assigned') {
                $noMsgParts[] = 'that are assigned';
              }
            }
            if (isset($miqaat_type) && $miqaat_type !== '') {
              $noMsgParts[] = 'of type ' . htmlspecialchars($miqaat_type, ENT_QUOTES);
            }
            if (!empty($hijri_month_id) && $hijri_month_id > 0 && isset($hijri_months)) {
              foreach ($hijri_months as $hm) {
                if ($hm['id'] == $hijri_month_id) {
                  $noMsgParts[] = 'in ' . $hm['hijri_month'];
                  break;
                }
              }
            } elseif ($hijri_month_id == -3) {
              $noMsgParts[] = 'in last year';
            } elseif ($hijri_month_id == -1) {
              $noMsgParts[] = 'in current year';
            }
            $noMsg = 'No Miqaats found';
            if (!empty($noMsgParts)) {
              $noMsg .= ' ' . implode(' ', $noMsgParts);
            }
            $noMsg .= '.';
            ?>
            <tr class="no-results-row">
              <td colspan="9" class="text-center text-muted">
                <?php echo $noMsg; ?>
                <div class="mt-2">
                  <a href="<?php echo base_url('common/createmiqaat?date=' . date('Y-m-d')); ?>" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Create Miqaat</a>
                  <a href="<?php echo base_url('common/managemiqaat'); ?>" class="btn btn-sm btn-outline-secondary ml-2">Clear Filters</a>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
  // Optional: Only auto-submit for month & sort; others require button
  $('#hijri-month, #assignment-filter, #miqaat-type').on('change', function() {
    $('#filter-form').submit();
  });

  // Debounced submit for member name filter
  // Remove debounce auto-submit; user presses Filter
  // (Keep code placeholder for potential future re-enable)

  $(".alert").delay(3000).fadeOut(500);

  // Client-side sortable headers (similar to calendar) with month header regrouping
  (function() {
    const table = document.querySelector('.miqaat-list-container table');
    if (!table) return;
    const thead = table.querySelector('thead');
    const tbody = table.querySelector('tbody');
    if (!thead || !tbody) return;

    thead.querySelectorAll('th').forEach((th, idx) => {
      if (th.hasAttribute('data-no-sort')) return;
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
      if (!isNaN(parseFloat(val)) && isFinite(val)) return 'number';
      return 'text';
    }

    function norm(val) {
      const t = inferType(val);
      if (t === 'date') return new Date(val).getTime();
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

      const allRows = Array.from(tbody.querySelectorAll('tr'));
      const monthHeaders = allRows.filter(r => r.classList.contains('month-header'));
      const dataRows = allRows.filter(r => !r.classList.contains('month-header') && !r.classList.contains('no-results-row'));
      const noResultsRow = allRows.find(r => r.classList.contains('no-results-row'));

      dataRows.sort((a, b) => {
        const va = norm(getCellValue(a, idx));
        const vb = norm(getCellValue(b, idx));
        if (va < vb) return newDir === 'asc' ? -1 : 1;
        if (va > vb) return newDir === 'asc' ? 1 : -1;
        return 0;
      });

      // Rebuild with dynamic month headers ahead of first row in each month group
      tbody.innerHTML = '';
      const inserted = new Set();
      dataRows.forEach(r => {
        const mName = r.getAttribute('data-hijri-month-name') || '';
        if (mName && !inserted.has(mName)) {
          const hdr = document.createElement('tr');
          hdr.className = 'table-info month-header';
          const td = document.createElement('td');
          td.colSpan = 9;
          td.className = 'text-center';
          td.style.fontWeight = 'bold';
          td.textContent = 'Hijri Month: ' + mName;
          hdr.appendChild(td);
          tbody.appendChild(hdr);
          inserted.add(mName);
        }
        tbody.appendChild(r);
      });
      if (noResultsRow) {
        tbody.appendChild(noResultsRow);
      }
      renumber();
    }

    function renumber() {
      let i = 1;
      tbody.querySelectorAll('td.sno').forEach(td => td.textContent = i++);
    }
  })();
  // End sortable headers
</script>