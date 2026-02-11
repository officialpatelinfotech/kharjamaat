<div class="container margintopcontainer pt-5">
  <style>
    .fmb-filter-bar { display:flex; flex-wrap:wrap; gap:.5rem .75rem; align-items:flex-end; }
    .fmb-filter-bar .form-group { margin-bottom:0; }
    .fmb-filter-bar label { font-size:.75rem; color:#6c757d; margin-bottom:.25rem; }
    .fmb-filter-bar .form-control { min-width:220px; }
    @media (max-width: 576px){ .fmb-filter-bar .form-control { min-width:unset; width:100%; } }
  </style>
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

  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("admin/managefmbsettings"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <div class="mb-4 p-0">
    <?php
      // Use global filter meta provided by controller to avoid collapsing options after filtering
      $sectors_list = isset($filter_meta['sectors']) && is_array($filter_meta['sectors']) ? $filter_meta['sectors'] : [];
      $sub_sectors_list = isset($filter_meta['sub_sectors']) && is_array($filter_meta['sub_sectors']) ? $filter_meta['sub_sectors'] : [];
      if (!function_exists('selopt')) { function selopt($cur, $val){ return ((string)($cur ?? '') === (string)$val) ? 'selected' : ''; } }
    ?>
    <form method="POST" action="<?php echo base_url("admin/filterfmbtakhmeen"); ?>" id="filter-form" class="row m-0">
      <input type="text" name="member_name" id="member-name" class="apply-filter form-control col-3 mr-3" placeholder="Filter by Member name" value="<?php echo isset($member_name) ? $member_name : ""; ?>">
      <select name="sector" id="sector" class="apply-filter form-control col-3 mr-3">
        <option value="">All Sectors</option>
        <?php foreach($sectors_list as $s): ?>
          <option value="<?php echo htmlspecialchars($s); ?>" <?php echo selopt($sector ?? '', $s); ?>><?php echo htmlspecialchars($s); ?></option>
        <?php endforeach; ?>
      </select>
      <select name="sub_sector" id="sub-sector" class="apply-filter form-control col-3 mr-3">
        <option value="">All Sub Sectors</option>
        <?php foreach($sub_sectors_list as $ss): ?>
          <option value="<?php echo htmlspecialchars($ss); ?>" <?php echo selopt($sub_sector ?? '', $ss); ?>><?php echo htmlspecialchars($ss); ?></option>
        <?php endforeach; ?>
      </select>

      <select name="filter_year" id="filter-year" class="apply-filter form-control col-3 mt-3">
        <option value="">Select Year</option>
        <option value="1442-43" <?php echo (isset($year) && $year == "1442-43") ? "selected" : ""; ?>>1442-43</option>
        <option value="1443-44" <?php echo (isset($year) && $year == "1443-44") ? "selected" : ""; ?>>1443-44</option>
        <option value="1444-45" <?php echo (isset($year) && $year == "1444-45") ? "selected" : ""; ?>>1444-45</option>
        <option value="1445-46" <?php echo (isset($year) && $year == "1445-46") ? "selected" : ""; ?>>1445-46</option>
        <option value="1446-47" <?php echo (isset($year) && $year == "1446-47") ? "selected" : ""; ?>>1446-47</option>
        <option value="1447-48" <?php echo (isset($year) && $year == "1447-48") ? "selected" : ""; ?>>1447-48</option>
        <option value="1448-49" <?php echo (isset($year) && $year == "1448-49") ? "selected" : ""; ?>>1448-49</option>
      </select>

      <button type="submit" class="btn btn-primary ml-2 mt-3">Filter</button>

      <button type="button" id="clear-filters" class="btn btn-outline-secondary ml-2 mt-3" title="Clear">
        <i class="fa-solid fa-times"></i>
      </button>
    </form>
  </div>
  <?php
  if (isset($all_user_fmb_takhmeen) && !empty($all_user_fmb_takhmeen)):
    $hijri_year = isset($all_user_fmb_takhmeen[0]["hijri_year"]) ? $all_user_fmb_takhmeen[0]["hijri_year"] : '';
  else:
    // Fallback to selected filter year if takhmeen list is empty
    $hijri_year = isset($year) ? $year : '';
  endif;
  ?>
  <h4 class="heading text-center mb-4">FMB Thaali Takhmeen for <span class="text-primary"><?php echo $hijri_year; ?></span></h4>
  <div class="table-responsive" style="overflow-x:auto;">
    <table class="table table-bordered table-striped" style="min-width: 700px;">
      <thead>
        <tr>
          <th data-no-sort>#</th>
          <th>ITS ID</th>
          <th>Name</th>
          <th>Sector</th>
          <th>Sub-Sector</th>
          <th>Takhmeen</th>
          <th>Thaali Day</th>
          <th>Assigned Thaali Days</th>
          <th data-no-sort>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (isset($all_user_fmb_takhmeen)) {
          foreach ($all_user_fmb_takhmeen as $key => $user) {
        ?>
            <tr>
              <td><?php echo $key + 1; ?></td>
              <td data-sort-value="<?php echo htmlspecialchars(strtolower($user["ITS_ID"]), ENT_QUOTES); ?>"><?php echo htmlspecialchars($user["ITS_ID"]); ?></td>
              <td data-sort-value="<?php echo htmlspecialchars(strtolower($user["Full_Name"]), ENT_QUOTES); ?>"><?php echo $user["Full_Name"]; ?></td>
              <td data-sort-value="<?php echo htmlspecialchars(strtolower($user["Sector"]), ENT_QUOTES); ?>"><?php echo $user["Sector"]; ?></td>
              <td data-sort-value="<?php echo htmlspecialchars(strtolower($user["Sub_Sector"]), ENT_QUOTES); ?>"><?php echo $user["Sub_Sector"]; ?></td>
              <td data-sort-value="<?php echo isset($user["current_year_takhmeen"]) ? (float)$user["current_year_takhmeen"]["amount"] : 0; ?>">
                <?php if (isset($user["current_year_takhmeen"])):
                ?>
                  <p class="takhmeen-amount m-0 p-0"><?php echo $user["current_year_takhmeen"]["amount"]; ?></p>
                  <p class="financial-year pt-2 m-0">
                    <small class="text-secondary">(FY - <?php echo $user["current_year_takhmeen"]["year"]; ?>)</small>
                  </p>
                <?php
                else: ?>
                  Takhmeen Not Found
                <?php
                endif; ?>
              </td>
              <td>
                <?php
                  $per_day = isset($per_day_thaali_cost_amount) && is_numeric($per_day_thaali_cost_amount) ? (float)$per_day_thaali_cost_amount : 0;
                  if (isset($user['current_year_takhmeen']) && $per_day > 0) {
                    $rawAmt = (string)($user['current_year_takhmeen']['amount'] ?? '');
                    $amt = (float)preg_replace('/[^0-9.]/', '', $rawAmt);
                    $days = (int)floor($amt / $per_day);
                    echo htmlspecialchars((string)$days);
                  } else {
                    echo '-';
                  }
                ?>
              </td>
              <td data-sort-value="<?php echo isset($user['assigned_thaali_days']) ? (int)$user['assigned_thaali_days'] : 0; ?>">
                <?php $assignedCnt = isset($user['assigned_thaali_days']) ? (int)$user['assigned_thaali_days'] : 0; ?>
                <a href="#" class="view-assigned-thaali-days" data-user-id="<?php echo htmlspecialchars($user['ITS_ID'], ENT_QUOTES); ?>" data-user-name="<?php echo htmlspecialchars($user['Full_Name'], ENT_QUOTES); ?>" data-year="<?php echo htmlspecialchars($hijri_year, ENT_QUOTES); ?>">
                  <?php echo $assignedCnt; ?>
                </a>
              </td>
              <td>
                <button id="add-takhmeen" class="add-takhmeen mb-2 btn btn-sm btn-success" data-toggle="modal" data-target="#add-takhmeen-container" data-user-id="<?php echo $user["ITS_ID"]; ?>" data-user-name="<?php echo $user["Full_Name"]; ?>"><i class="fa-solid fa-plus"></i></button>

                <button id="view-takhmeen" class="view-takhmeen mb-2 btn btn-sm btn-primary" data-toggle="modal" data-target="#view-takhmeen-container" data-user-id="<?php echo $user["ITS_ID"]; ?>" data-user-name="<?php echo $user["Full_Name"]; ?>" data-takhmeens="<?php echo htmlspecialchars(json_encode($user["takhmeens"]), ENT_QUOTES, 'UTF-8'); ?>"><i class="fa-solid fa-eye"></i></button>

                <button id="edit-takhmeen" class="edit-takhmeen mb-2 btn btn-sm btn-info" data-toggle="modal" data-target="#edit-takhmeen-container" data-user-id="<?php echo $user["ITS_ID"]; ?>" data-user-name="<?php echo $user["Full_Name"]; ?>" data-takhmeens="<?php echo htmlspecialchars(json_encode($user["takhmeens"]), ENT_QUOTES, 'UTF-8'); ?>"><i class="fa-solid fa-pencil"></i></button>
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

<div class="modal fade" id="assigned-thaali-days-container" tabindex="-1" aria-labelledby="assigned-thaali-days-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assigned-thaali-days-label">Assigned Thaali Dates</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="mb-2"><strong>Member Name:</strong> <span id="assigned-user-name">-</span></p>
        <p class="mb-3"><strong>FY:</strong> <span id="assigned-fy">-</span></p>
        <div id="assigned-dates-loading" class="text-secondary">Loading...</div>
        <div id="assigned-dates-empty" class="text-secondary d-none">No dates assigned.</div>
        <ul id="assigned-dates-list" class="pl-3 mb-0"></ul>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="add-takhmeen-container" tabindex="-1" aria-labelledby="add-takhmeen-container-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add-takhmeen-container-label">Add FMB Takhmeen Amount</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?php echo base_url("admin/addfmbtakhmeenamount"); ?>">
          <input type="hidden" name="user_id" id="user-id" />
          <p class="mb-2"><strong>Member Name:</strong> <span id="user-name">-</span></p>
          <div class="form-group">
            <label for="takhmeen-year">Year</label>
            <select name="fmb_takhmeen_year" id="takhmeen-year" class="form-control" required>
              <option value="1442-43">1442-43</option>
              <option value="1443-44">1443-44</option>
              <option value="1444-45">1444-45</option>
              <option value="1445-46">1445-46</option>
              <option value="1446-47">1446-47</option>
              <option value="1447-48">1447-48</option>
              <option value="1448-49">1448-49</option>
              <option value="1449-50">1449-50</option>
              <option value="1450-51">1450-51</option>
              <option value="1451-52">1451-52</option>
              <option value="1452-53">1452-53</option>
              <option value="1453-54">1453-54</option>
              <option value="1454-55">1454-55</option>
              <option value="1455-56">1455-56</option>
              <option value="1456-57">1456-57</option>
              <option value="1457-58">1457-58</option>
            </select>
          </div>
          <div class="form-group">
            <label for="takhmeen-amount" class="form-label">Takhmeen Amount</label>
            <input type="number" id="takhmeen-amount" name="fmb_takhmeen_amount" class="form-control" placeholder="Enter Takhmeen Amount" min="1" required>
          </div>
          <div class="form-group">
            <label for="thaali-date" class="form-label">Thaali days</label>
            <div class="input-group">
              <input type="date" id="thaali-date" class="form-control" min="<?php echo !empty($hijri_calendar_min_greg) ? htmlspecialchars($hijri_calendar_min_greg, ENT_QUOTES) : '2000-01-01'; ?>" max="<?php echo !empty($hijri_calendar_max_greg) ? htmlspecialchars($hijri_calendar_max_greg, ENT_QUOTES) : '2100-12-31'; ?>" />
              <div class="input-group-append">
                <button type="button" id="add-thaali-date-btn" class="btn btn-secondary">Add</button>
              </div>
            </div>
            <small id="thaali-error" class="text-danger d-none">Please select date</small>
            <div id="thaali-dates-list" class="mt-2"></div>
            <input type="hidden" name="thaali_dates" id="thaali-dates-hidden" />
          </div>
          <div class="d-flex align-items-center justify-content-between">
            <button type="submit" id="add-takhmeen-btn" class="btn btn-primary">Add Takhmeen</button>
            <p id="validate-takhmeen" class="text-secondary m-0"></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="view-takhmeen-container" tabindex="-1" aria-labelledby="view-takhmeen-container-label" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="view-takhmeen-container-label">View FMB Takhmeen History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><b>Member Name: </b><span id="view-user-name">Member Name</span></p>
        <div class="table-responsive" style="overflow-x:auto;">
          <table class="table table-bordered table-striped" style="min-width: 600px;">
            <thead>
              <tr>
                <th>#</th>
                <th>Takhmeen Year</th>
                <th>Amount</th>
                <th>Thaali Days</th>
                <th>Assigned Thaali Days</th>
                <th>Update Remark</th>
              </tr>
            </thead>
            <tbody id="takhmeen-history-body">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-takhmeen-container" tabindex="-1" aria-labelledby="edit-takhmeen-label" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edit-takhmeen-label">Edit FMB Takhmeen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><b>Member Name: </b><span id="edit-user-name">Member Name</span></p>
        <div class="table-responsive rounded" style="overflow-x:auto;">
          <div class="card-header">
            <h5 class="card-title m-0 text-center">Takhmeen History</h5>
          </div>
          <div class="card-body p-0">
            <table class="table table-bordered table-striped" style="min-width: 400px;">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Takhmeen Year</th>
                  <th>Amount</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="edit-takhmeen-body">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  const hijriGregMin = <?php echo json_encode(isset($hijri_calendar_min_greg) ? $hijri_calendar_min_greg : null); ?>;
  const hijriGregMax = <?php echo json_encode(isset($hijri_calendar_max_greg) ? $hijri_calendar_max_greg : null); ?>;

  function isIsoInRange(isoDate){
    const d = String(isoDate || '');
    if (!d.match(/^\d{4}-\d{2}-\d{2}$/)) return false;
    if (hijriGregMin && d < String(hijriGregMin)) return false;
    if (hijriGregMax && d > String(hijriGregMax)) return false;
    return true;
  }

  function hijriRangeMsg(){
    if (hijriGregMin && hijriGregMax) return 'Please select a date between ' + formatThaaliDateDisplay(hijriGregMin) + ' and ' + formatThaaliDateDisplay(hijriGregMax) + '.';
    return 'Please select a valid date.';
  }

  function resetAddTakhmeenModal() {
    // Reset to Add mode defaults
    $("#add-takhmeen-container-label").text("Add FMB Takhmeen Amount");
    $("#add-takhmeen-btn").show();
    $("#takhmeen-amount").prop("disabled", false).val("");
    $("#validate-takhmeen").html("");
    $("#thaali-dates-list").empty();
    $("#thaali-dates-hidden").val("");
    $("#thaali-date").val("");
    $("#thaali-date").prop('disabled', false);
    $("#add-thaali-date-btn").prop('disabled', false);
    $("#thaali-error").addClass("d-none").text("Please select date");
    $("#takhmeen-year").prop("disabled", false);
  }

  function openAddTakhmeenAsEdit(userId, userName, year) {
    resetAddTakhmeenModal();
    $("#add-takhmeen-container-label").text("Edit FMB Takhmeen Amount");
    $("#user-id").val(userId);
    $("#user-name").html(userName);

    if (year) {
      $("#takhmeen-year").val(year);
    }
    // Trigger validation/update UI for selected year
    $("#takhmeen-year").trigger("change");
    // Show already selected/assigned thaali dates for this FY
    if (userId && year) {
      loadAssignedThaaliDatesIntoForm(userId, year);
    }
    // When editing a specific year via history, prevent changing year accidentally
    if (year) {
      $("#takhmeen-year").prop("disabled", true);
    }

    $("#add-takhmeen-container").modal("show");
  }

  $(".add-takhmeen").on("click", function(e) {
    resetAddTakhmeenModal();
    $userId = $(this).data("user-id");
    $userName = $(this).data("user-name");
    $("#user-id").val($userId);
    $("#user-name").html($userName);
  });

  // Ensure modal resets when closed so next open is clean.
  $(document).on('hidden.bs.modal', '#add-takhmeen-container', function(){
    resetAddTakhmeenModal();
  });

  $("#takhmeen-year").on("change", function(e) {
    $userId = $("#user-id").val();
    $takhmeen_year = $(this).val();

    // If there are already assigned thaali dates for this FY, show them in the list
    if ($userId && $takhmeen_year) {
      loadAssignedThaaliDatesIntoForm($userId, $takhmeen_year);
    }

    $.ajax({
      url: "<?php echo base_url("admin/validatefmbtakhmeen"); ?>",
      type: "POST",
      data: {
        "user_id": $userId,
        "year": $takhmeen_year
      },
      success: function(res) {
        if (res) {
          $res = JSON.parse(res);
          if ($res.success) {
            $("#add-takhmeen-btn").hide();
            $("#takhmeen-amount").prop("disabled", true);
            $("#validate-takhmeen").html(`
              <div class="alert alert-info">
                <p>Takhmeen already exists for this year.</p>
                <label for="edit-takhmeen-amount" class="form-label">Update Amount</label>
                <input type="number" id="edit-takhmeen-amount" 
                  class="form-control mb-2" 
                  value="${$res.user_takhmeen.total_amount}" min="1">
                <label for=\"edit-takhmeen-remark\" class=\"form-label\">Edit Remark</label>
                <textarea id=\"edit-takhmeen-remark\" class=\"form-control mb-2\" rows=\"2\" placeholder=\"Enter reason for update\" required></textarea>
                <button type="button" id="save-takhmeen-btn" class="btn btn-primary">Save</button>
              </div>
            `);
            $(document).off("click", "#save-takhmeen-btn").on("click", "#save-takhmeen-btn", function() {
              $newAmount = $("#edit-takhmeen-amount").val();
              let remark = $("#edit-takhmeen-remark").val();
              remark = String(remark || '').trim();
              if (!remark) {
                alert('Please enter edit remark before saving.');
                return;
              }
              $.ajax({
                url: "<?php echo base_url("admin/updatefmbtakhmeen/0"); ?>",
                type: "POST",
                data: {
                  "user_id": $userId,
                  "year": $takhmeen_year,
                  "fmb_takhmeen_amount": $newAmount,
                  "edit_remark": remark
                },
                success: function(updateRes) {
                  if (updateRes) {
                    let uRes = JSON.parse(updateRes);
                    if (uRes.success) {
                      alert("Takhmeen updated successfully!");
                      location.reload();
                    } else {
                      alert("Update failed: " + uRes.message);
                    }
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  console.error('Update AJAX Error:', textStatus, errorThrown, jqXHR);
                }
              });
            });
          }
        } else {
          $("#add-takhmeen-btn").show();
          $("#validate-takhmeen").html("");
          $("#takhmeen-amount").prop("disabled", false);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error('AJAX Error:', textStatus, errorThrown, jqXHR);
      }
    });
  });

  function renderThaaliDatesListReadOnly(dates) {
    const $list = $('#thaali-dates-list');
    $list.empty();
    const safeDates = Array.isArray(dates) ? dates : [];
    if (!safeDates.length) {
      // Show explicit empty state so user knows we tried loading
      $list.append($('<div>').addClass('text-secondary').text('No dates selected.'));
      $('#thaali-dates-hidden').val('[]');
      return;
    }
    $('#thaali-dates-hidden').val(JSON.stringify(safeDates));
    safeDates.forEach(function(d){
      const row = $('<div>')
        .addClass('thaali-date-row d-flex align-items-center mb-1')
        .css('gap','0.5rem');
      const dateText = $('<span>')
        .addClass('badge badge-info badge-pill')
        .text(formatThaaliDateDisplay(d));
      const remove = $('<a>')
        .attr('href', '#')
        .addClass('remove-assigned-thaali-date text-danger')
        .attr('data-date', d)
        .html('&times;')
        .css({ 'font-weight': 'bold', 'font-size': '1.2em', 'line-height': '1' });
      row.append(dateText);
      row.append(remove);
      $list.append(row);
    });
  }

  $(document).on('click', '.remove-assigned-thaali-date', function(e){
    e.preventDefault();
    const dateVal = String($(this).data('date') || '').trim();
    const userId = String($('#user-id').val() || '').trim();
    const year = String($('#takhmeen-year').val() || '').trim();
    if (!dateVal || !userId || !year) return;

    if (!confirm('Remove thaali date ' + formatThaaliDateDisplay(dateVal) + ' ?')) {
      return;
    }

    const $link = $(this);
    $link.css('pointer-events','none');
    $.ajax({
      url: "<?php echo base_url('admin/removefmbassignedthaalidate'); ?>",
      type: 'POST',
      data: { user_id: userId, year: year, date: dateVal },
      success: function(res){
        let payload = res;
        try { if (typeof res === 'string') payload = JSON.parse(res); } catch (e) { payload = { success: false, message: 'Invalid response' }; }
        if (!payload || payload.success === false) {
          alert((payload && payload.message) ? payload.message : 'Failed to remove date');
          $link.css('pointer-events','');
          return;
        }

        // Update hidden list
        let existing = [];
        try { existing = JSON.parse($('#thaali-dates-hidden').val() || '[]'); } catch(err) { existing = []; }
        existing = Array.isArray(existing) ? existing : [];
        const idx = existing.indexOf(dateVal);
        if (idx !== -1) existing.splice(idx, 1);
        $('#thaali-dates-hidden').val(JSON.stringify(existing));

        // Remove row
        $link.closest('.thaali-date-row').remove();
        if (!existing.length) {
          $('#thaali-dates-list').empty().append($('<div>').addClass('text-secondary').text('No dates selected.'));
        }
      },
      error: function(){
        alert('Failed to remove date');
        $link.css('pointer-events','');
      }
    });
  });

  function loadAssignedThaaliDatesIntoForm(userId, year) {
    // This is for displaying existing assigned dates (read-only) in the Add/Edit modal.
    const $list = $('#thaali-dates-list');
    $list.empty().append($('<div>').addClass('text-secondary').text('Loading...'));
    $.ajax({
      url: "<?php echo base_url('admin/getfmbassignedthaalidates'); ?>",
      type: 'POST',
      data: { user_id: userId, year: year },
      success: function(res){
        let payload = res;
        try {
          if (typeof res === 'string') payload = JSON.parse(res);
        } catch (e) {
          payload = { success: false, dates: [], message: 'Invalid response' };
        }

        if (!payload || payload.success === false) {
          const msg = payload && payload.message ? String(payload.message) : 'No dates selected.';
          $list.empty().append($('<div>').addClass('text-secondary').text(msg));
          $('#thaali-dates-hidden').val('[]');
          return;
        }

        const dates = payload && payload.dates ? payload.dates : [];
        renderThaaliDatesListReadOnly(dates);
      },
      error: function(){
        // Show empty state on failure
        $list.empty().append($('<div>').addClass('text-secondary').text('No dates selected.'));
        $('#thaali-dates-hidden').val('[]');
      }
    });
  }

  $(".view-takhmeen").on("click", function(e) {
    resetViewTakhmeenModal();
    $userId = $(this).data("user-id");
    $userName = $(this).data("user-name");
    $takhmeens = $(this).data("takhmeens");
    const perDayCost = <?php echo json_encode(isset($per_day_thaali_cost_amount) ? (float)$per_day_thaali_cost_amount : 0); ?>;

    $("#view-user-name").html($userName);

    if ($takhmeens && $takhmeens.length > 0) {
      let historyHtml = '';
      $takhmeens.forEach((takhmeen, index) => {
        const assignedCnt = (takhmeen && typeof takhmeen.assigned_thaali_days !== 'undefined') ? Number(takhmeen.assigned_thaali_days) : 0;
        let thaaliDaysDisplay = '-';
        if (perDayCost && Number(perDayCost) > 0 && takhmeen && typeof takhmeen.amount !== 'undefined') {
          const amtNum = parseFloat(String(takhmeen.amount).replace(/[^0-9.]/g, ''));
          if (!isNaN(amtNum)) {
            thaaliDaysDisplay = Math.floor(amtNum / Number(perDayCost));
          }
        }
        historyHtml += `
          <tr>
            <td>${index + 1}</td>
            <td>${takhmeen.year}</td>
            <td>&#8377;${new Intl.NumberFormat("en-IN", { maximumSignificantDigits: 3 }).format(takhmeen.amount)}</td>
            <td>${thaaliDaysDisplay}</td>
            <td>
              <a href="#" class="view-assigned-thaali-days" data-user-id="${$userId}" data-user-name="${$userName}" data-year="${takhmeen.year}">${assignedCnt}</a>
            </td>
            <td>${takhmeen.remark ? $('<div/>').text(takhmeen.remark).html() : '-'}</td>
          </tr>
        `;
      });
      $("#takhmeen-history-body").html(historyHtml);
    } else {
      $("#takhmeen-history-body").html('<tr><td colspan="6" class="text-center">No Takhmeen history found.</td></tr>');
    }
  });

  $(".edit-takhmeen").on("click", function(e) {
    resetViewTakhmeenModal();
    $userId = $(this).data("user-id");
    $userName = $(this).data("user-name");
    $takhmeens = $(this).data("takhmeens");

    $("#edit-user-name").html($userName);

    if ($takhmeens && $takhmeens.length > 0) {
      let historyHtml = '';
      $takhmeens.forEach((takhmeen, index) => {
        historyHtml += `
        <tr>
          <td>${index + 1}</td>
          <td>${takhmeen.year}</td>
          <td>&#8377;${new Intl.NumberFormat("en-IN", { maximumSignificantDigits: 3 }).format(takhmeen.amount)}</td>
          <td>
            <button class="btn btn-sm btn-primary edit-single-takhmeen" 
              data-user-id="${$userId}" 
              data-year="${takhmeen.year}" 
              data-amount="${takhmeen.amount}">
              <i class="fa-solid fa-pencil"></i>
            </button>
            <button class="btn btn-sm btn-danger delete-single-takhmeen" 
              data-user-id="${$userId}" 
              data-year="${takhmeen.year}">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>
      `;
      });

      $("#edit-takhmeen-body").html(historyHtml);
    } else {
      $("#edit-takhmeen-body").html('<tr><td colspan="4" class="text-center">No Takhmeen history found.</td></tr>');
    }
  });

  // ===== EDIT BUTTON FLOW =====
  $(document).on("click", ".edit-single-takhmeen", function(e) {
    e.preventDefault();

    // Open popup like the Add Takhmeen modal, prefilled for this year.
    const $btn = $(this);
    const userId = $btn.data('user-id');
    const year = $btn.data('year');
    const userName = String($('#edit-user-name').text() || '').trim();

    // Close history modal first to avoid stacked modal/backdrop issues.
    // Bind handler before hiding to avoid missing the event.
    $('#edit-takhmeen-container').off('hidden.bs.modal.__openEdit').on('hidden.bs.modal.__openEdit', function(){
      $(this).off('hidden.bs.modal.__openEdit');
      openAddTakhmeenAsEdit(userId, userName, year);
    });
    $('#edit-takhmeen-container').modal('hide');
  });

  $(document).on("click", ".delete-single-takhmeen", function(e) {
    e.preventDefault();
    let $btn = $(this);
    let $row = $btn.closest("tr");
    let userId = $btn.data("user-id");
    let year = $btn.data("year");

    if (!confirm(`Are you sure you want to delete takhmeen for year ${year}?`)) {
      return;
    }

    $.ajax({
      url: "<?php echo base_url('admin/deletefmbtakhmeen'); ?>",
      type: "POST",
      data: {
        user_id: userId,
        year: year
      },
      success: function(res) {
        let parsed = JSON.parse(res);
        if (parsed.success) {
          alert("Takhmeen deleted successfully!");
          $row.remove();
          window.location.reload();
        } else {
          alert("Delete failed: " + parsed.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("AJAX Error:", textStatus, errorThrown);
      }
    });
  });

  function resetViewTakhmeenModal() {
    $("#edit-user-name").html("Member Name");
    $("#edit-takhmeen-body").html("");

    $("#view-user-name").html("Member Name");
    $("#takhmeen-history-body").html("");
  }

  $(document).ready(
    function() {
      $takhmeenAmount = $(".takhmeen-amount");
      for (const index in $takhmeenAmount) {
        $indexTakhmeenAmount = $($takhmeenAmount[Number(index)]);
        $takhmeenAmountText = $indexTakhmeenAmount.html();
        $indianFormatTA = new Intl.NumberFormat("en-IN", {
          maximumSignificantDigits: 3
        }).format(
          $takhmeenAmountText,
        );

        $indianFormatTA = "&#8377;" + $indianFormatTA;
        $indexTakhmeenAmount.html($indianFormatTA);
      };
    }()
  );

  $(".apply-filter").on("change", function() {
    $("#filter-form").submit();
  });

  // Clear filters: reset inputs/selects and submit
  $(document).on('click', '#clear-filters', function(){
    const form = document.getElementById('filter-form');
    if(!form) return;
    // Reset known fields
    const nameInput = form.querySelector('#member-name');
    const sectorSel = form.querySelector('#sector');
    const subSectorSel = form.querySelector('#sub-sector');
    const yearSel = form.querySelector('#filter-year');
    if(nameInput) nameInput.value = '';
    if(sectorSel) sectorSel.value = '';
    if(subSectorSel) subSectorSel.value = '';
    if(yearSel) yearSel.value = '';
    form.submit();
  });

  $(".alert").delay(3000).fadeOut(500);

  // Client-side sortable headers for the takhmeen table (table-only change)
  (function(){
    const table = document.querySelector('.table.table-bordered.table-striped');
    if(!table) return;
    const thead = table.querySelector('thead');
    const tbody = table.querySelector('tbody');
    if(!thead || !tbody) return;

    thead.querySelectorAll('th').forEach((th, idx) => {
      if(th.hasAttribute('data-no-sort')) return;
      th.classList.add('sortable');
      const original = th.innerHTML.trim();
      th.innerHTML = '<span class="sort-label">'+original+'</span><span class="sort-indicator" aria-hidden="true"></span>';
      th.setAttribute('role','button'); th.setAttribute('tabindex','0');
      th.addEventListener('click', () => toggleSort(idx, th));
      th.addEventListener('keydown', e => { if(['Enter',' '].includes(e.key)){ e.preventDefault(); toggleSort(idx, th); }});
    });

    function getCellValue(tr, index){
      const cells = tr.querySelectorAll('td');
      if(!cells[index]) return '';
      return cells[index].getAttribute('data-sort-value') || cells[index].textContent.trim();
    }
    function inferType(val){
      if(/^\d+(?:\.\d+)?$/.test(val)) return 'number';
      if(/^\d{4}-\d{2}-\d{2}$/.test(val)) return 'date';
      return 'text';
    }
    function norm(val){
      const t = inferType(val);
      if(t==='number') return parseFloat(val);
      if(t==='date') return new Date(val).getTime();
      return String(val).toLowerCase();
    }
    function toggleSort(idx, th){
      const newDir = th.dataset.sortDir === 'asc' ? 'desc' : 'asc';
      thead.querySelectorAll('th.sortable').forEach(h => { h.dataset.sortDir=''; const ind=h.querySelector('.sort-indicator'); if(ind) ind.textContent=''; });
      th.dataset.sortDir = newDir; const ind=th.querySelector('.sort-indicator'); if(ind) ind.textContent = newDir==='asc' ? '▲' : '▼';

      const rows = Array.from(tbody.querySelectorAll('tr'));
      rows.sort((a,b) => {
        const va = norm(getCellValue(a, idx));
        const vb = norm(getCellValue(b, idx));
        if(va < vb) return newDir==='asc' ? -1 : 1;
        if(va > vb) return newDir==='asc' ? 1 : -1;
        return 0;
      });

      rows.forEach(r => tbody.appendChild(r));
      // Renumber first column
      let i=1; tbody.querySelectorAll('tr').forEach(r => { const td = r.querySelector('td'); if(td) td.textContent = i++; });
    }
  })();
  // === Thaali Date add/remove handlers ===
    // Require at least one Thaali date on submit
    $(document).on('submit', '#add-takhmeen-container form', function(e) {
      var thaaliDates = $('#thaali-dates-hidden').val();
      try { thaaliDates = JSON.parse(thaaliDates || '[]'); } catch(err) { thaaliDates = []; }
      if (!thaaliDates.length) {
        alert('Please select date');
        $('#thaali-error').removeClass('d-none').text('Please select date');
        $('#thaali-date').focus();
        e.preventDefault();
        return false;
      }
      $('#thaali-error').addClass('d-none');
    });
  function formatThaaliDateDisplay(isoDate) {
    // Expects YYYY-MM-DD from <input type="date">. Return DD-MM-YYYY.
    const m = String(isoDate || '').match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if (!m) return isoDate;
    return `${m[3]}-${m[2]}-${m[1]}`;
  }

  function addThaaliDate(dateVal) {
    if (!dateVal) {
      $('#thaali-error').removeClass('d-none').text('Please select date');
      return;
    }
    if (!isIsoInRange(dateVal)) {
      alert(hijriRangeMsg());
      return;
    }
    $('#thaali-error').addClass('d-none');

    let existing = [];
    try { existing = JSON.parse($('#thaali-dates-hidden').val() || '[]'); } catch(err) { existing = []; }
    if (existing.indexOf(dateVal) !== -1) {
      alert('Date already added');
      $('#thaali-date').val('');
      return;
    }
    existing.push(dateVal);
    $('#thaali-dates-hidden').val(JSON.stringify(existing));

    // Each selected date renders on its own line (ek ke niche ek)
    const row = $('<div>').addClass('thaali-date-row d-flex align-items-center mb-1').css('gap','0.5rem');
    const dateText = $('<span>').addClass('badge badge-info badge-pill').text(formatThaaliDateDisplay(dateVal));
    const remove = $('<a>')
      .attr('href', '#')
      .addClass('remove-thaali-date text-white ml-2')
      .attr('data-date', dateVal)
      .html('&times;')
      .css({ 'font-weight': 'bold', 'font-size': '1.2em' });
    row.append(dateText).append(remove);
    $('#thaali-dates-list').append(row);
    $('#thaali-date').val('');
  }

  function isExistingEditMode(){
    return $('#validate-takhmeen #save-takhmeen-btn').length > 0;
  }

  function addAssignedThaaliDateAjax(dateVal){
    if (!dateVal) {
      $('#thaali-error').removeClass('d-none').text('Please select date');
      return;
    }
    if (!isIsoInRange(dateVal)) {
      alert(hijriRangeMsg());
      return;
    }
    $('#thaali-error').addClass('d-none');

    const userId = String($('#user-id').val() || '').trim();
    const year = String($('#takhmeen-year').val() || '').trim();
    if (!userId || !year) {
      alert('Missing user/year');
      return;
    }

    $.ajax({
      url: "<?php echo base_url('admin/addfmbassignedthaalidate'); ?>",
      type: 'POST',
      data: { user_id: userId, year: year, date: dateVal },
      success: function(res){
        let payload = res;
        try { if (typeof res === 'string') payload = JSON.parse(res); } catch (e) { payload = { success: false, message: 'Invalid response' }; }
        if (!payload || payload.success === false) {
          alert((payload && payload.message) ? payload.message : 'Failed to add date');
          return;
        }
        // Refresh the fetched list
        loadAssignedThaaliDatesIntoForm(userId, year);
        $('#thaali-date').val('');
      },
      error: function(){
        alert('Failed to add date');
      }
    });
  }

  $(document).on('click', '#add-thaali-date-btn', function(e) {
    e.preventDefault();
    const v = $('#thaali-date').val();
    if (isExistingEditMode()) {
      addAssignedThaaliDateAjax(v);
    } else {
      addThaaliDate(v);
    }
  });

  // Auto-add when a date is selected from the picker
  $(document).on('change', '#thaali-date', function() {
    const v = $(this).val();
    if (isExistingEditMode()) {
      addAssignedThaaliDateAjax(v);
    } else {
      addThaaliDate(v);
    }
  });

  $(document).on('click', '.remove-thaali-date', function(e) {
    e.preventDefault();
    const d = $(this).data('date');
    let existing = [];
    try { existing = JSON.parse($('#thaali-dates-hidden').val() || '[]'); } catch(err) { existing = []; }
    const idx = existing.indexOf(d);
    if (idx !== -1) existing.splice(idx, 1);
    $('#thaali-dates-hidden').val(JSON.stringify(existing));
    $(this).closest('.thaali-date-row').remove();
  });

  // === Assigned Thaali days popup ===
  function formatIsoToDmy(isoDate){
    const m = String(isoDate || '').match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if(!m) return isoDate;
    return `${m[3]}-${m[2]}-${m[1]}`;
  }

  function openAssignedThaaliDaysModal(userId, userName, year){
    $('#assigned-user-name').text(userName || '-');
    $('#assigned-fy').text(year || '-');
    $('#assigned-dates-list').empty();
    $('#assigned-dates-empty').addClass('d-none').text('No dates assigned.');
    $('#assigned-dates-loading').removeClass('d-none');

    // Show assigned modal (supports being opened over another modal)
    var $assigned = $('#assigned-thaali-days-container');

    // Ensure modal is under body to avoid z-index/overflow quirks
    if ($assigned.parent()[0] !== document.body) {
      $assigned.appendTo(document.body);
    }

    // Compute next z-index above any currently-visible modal
    var maxZ = 1040;
    $('.modal.show').each(function(){
      var z = parseInt($(this).css('z-index'), 10);
      if (!isNaN(z)) maxZ = Math.max(maxZ, z);
    });
    $assigned.css('z-index', maxZ + 10);

    $assigned.off('shown.bs.modal.fmbstack').on('shown.bs.modal.fmbstack', function(){
      // Raise the newest backdrop just under the assigned modal
      var $backdrop = $('.modal-backdrop').not('.modal-stack').last();
      $backdrop.css('z-index', maxZ + 5).addClass('modal-stack');
      // Keep body locked when multiple modals are open
      $('body').addClass('modal-open');
    });
    $assigned.off('hidden.bs.modal.fmbstack').on('hidden.bs.modal.fmbstack', function(){
      $(this).css('z-index', '');
      // If another modal is still open, keep modal-open
      if ($('.modal.show').length) {
        $('body').addClass('modal-open');
      }
    });

    $assigned.modal('show');

    $.ajax({
      url: "<?php echo base_url('admin/getfmbassignedthaalidates'); ?>",
      type: 'POST',
      data: { user_id: userId, year: year },
      success: function(res){
        let payload = res;
        try {
          if (typeof res === 'string') payload = JSON.parse(res);
        } catch (err) {
          payload = { success: false, dates: [], message: 'Invalid response' };
        }

        $('#assigned-dates-loading').addClass('d-none');
        const dates = payload && payload.dates ? payload.dates : [];
        if (!dates.length) {
          $('#assigned-dates-empty').removeClass('d-none');
          return;
        }
        dates.forEach(function(d){
          $('#assigned-dates-list').append($('<li>').text(formatIsoToDmy(d)));
        });
      },
      error: function(){
        $('#assigned-dates-loading').addClass('d-none');
        $('#assigned-dates-empty').removeClass('d-none').text('Failed to load dates.');
      }
    });
  }

  $(document).on('click', '.view-assigned-thaali-days', function(e){
    e.preventDefault();
    const userId = $(this).data('user-id');
    const userName = $(this).data('user-name');
    const year = $(this).data('year');

    openAssignedThaaliDaysModal(userId, userName, year);
  });
</script>