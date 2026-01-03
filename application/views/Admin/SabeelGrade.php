<style>
  .hidden {
    display: none;
  }

  .add-sabeel-grade {
    background-color: #f1f1f1;
  }
</style>
<div class="container margintopcontainer">
  <h4 class="heading text-center pt-5 mb-4">Sabeel Grade</h4>
  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("admin/managesabeeltakhmeen"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="col-12 col-md-6">
      <form method="POST" action="<?php echo base_url("admin/filtersabeelgrade"); ?>" id="filter-sabeel-grade-form" class="d-flex m-0">

        <select name="sabeel_year" id="sabeel-year" class="form-filter form-control" required>
          <option value="">Select Year</option>
          <?php
          $yearRanges = [
            '1442-43',
            '1443-44',
            '1444-45',
            '1445-46',
            '1446-47',
            '1447-48',
            '1448-49',
            '1449-50',
            '1450-51',
            '1451-52',
            '1452-53',
            '1453-54',
            '1454-55',
            '1455-56',
            '1456-57',
            '1457-58'
          ];
          if (!function_exists('renderYearOption')) {
            function renderYearOption($yr, $current)
            {
              $sel = ($current === $yr) ? 'selected' : '';
              echo "<option value=\"{$yr}\" {$sel}>{$yr}</option>";
            }
          }
          $currentSelected = isset($sabeel_year) ? $sabeel_year : null;
          foreach ($yearRanges as $yr) {
            renderYearOption($yr, $currentSelected);
          }
          ?>
        </select>
        <a href="<?php echo base_url("admin/sabeelgrade"); ?>" class="btn btn-secondary ml-2">Clear</a>
      </form>
    </div>
  </div>
  <hr>
  <div class="row col-12">
    <?php
      $displayYear = isset($sabeel_year) ? $sabeel_year : null;
      if (!$displayYear && isset($sabeel_grades) && is_array($sabeel_grades)) {
        foreach ($sabeel_grades as $g) {
          if (!empty($g['year'])) { $displayYear = $g['year']; break; }
        }
      }
    ?>
    <div class="col-12 col-md-6">
      <div class="p-2 border rounded text-right">
        <h4 class="text-center mt-3">Establishment Sabeel Grade<?php echo $displayYear ? " – $displayYear" : ""; ?></h4>
        <button id="add-e-sabeel-grade-btn" class="add-sabeel-grade-btn btn btn-sm btn-primary" data-index="0">Add Grade</button>
        <div id="add-e-sabeel-grade" class="add-sabeel-grade hidden border mt-3 rounded">
          <form method="post" action="<?php echo base_url('admin/addsabeelgrade/1') ?>" id="add-e-sabeel-form" class="add-sabeel-grade-form">
            <div class="row modal-body">
              <div class="col-12 col-md-12">
                <select name="e_sabeel_year" id="e-sabeel-year" class="sabeel-year form-control" required>
                  <option value="">Select Year</option>
                  <?php
                  $yearRanges = [
                    '1442-43',
                    '1443-44',
                    '1444-45',
                    '1445-46',
                    '1446-47',
                    '1447-48',
                    '1448-49',
                    '1449-50',
                    '1450-51',
                    '1451-52',
                    '1452-53',
                    '1453-54',
                    '1454-55',
                    '1455-56',
                    '1456-57',
                    '1457-58'
                  ];
                  if (!function_exists('renderYearOption')) {
                    function renderYearOption($yr, $current)
                    {
                      $sel = ($current === $yr) ? 'selected' : '';
                      echo "<option value=\"{$yr}\" {$sel}>{$yr}</option>";
                    }
                  }
                  $currentSelected = isset($sabeel_year) ? $sabeel_year : null;
                  foreach ($yearRanges as $yr) {
                    renderYearOption($yr, $currentSelected);
                  }
                  ?>
                </select>
              </div>
              <div id="add-e-sabeel-grade-table" class="row col-12 col-md-12 mt-3">
                <div class="col-12 col-md-4">
                  <select name="e_sabeel_grade" id="e-sabeel-grade" class="sabeel-grade form-control" required></select>
                </div>
                <div class="col-12 col-md-4 mt-2 mt-md-0">
                  <input type="number" name="e_sabeel_amount_monthly" id="e_sabeel_amount_monthly" class="sabeel-amount-monthly form-control" placeholder="Monthly amount" min="0" step="1">
                </div>
                <div class="col-12 col-md-4 mt-2 mt-md-0">
                  <input type="number" name="e_sabeel_amount_yearly" id="e_sabeel_amount_yearly" class="sabeel-amount-yearly form-control" placeholder="Yearly amount" min="0" step="1">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="hide-add-container btn btn-secondary" data-index="0">Cancel</button>
              <button type="submit" class="submit-sabeel-grade-btn btn btn-primary" data-index="0">Submit</button>
            </div>
          </form>
        </div>
        <table class="table table-hover table-striped mt-3">
          <thead>
            <tr>
              <th>Grade</th>
              <th>Monthly</th>
              <th>Yearly</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($sabeel_grades)): ?>
              <?php foreach ($sabeel_grades as $key => $value): ?>
                <?php if ($value["type"] == "Establishment"): ?>
                  <tr>
                    <td><?php echo $value["grade"]; ?></td>
                    <td>
                      <?php $est_monthly = ($value['amount'] && is_numeric($value['amount'])) ? floor($value['amount']/12) : 0; ?>
                      <span id="amount-monthly-<?php echo $value["id"]; ?>"><?php echo (int)$est_monthly; ?></span>
                      <input type="number" id="edit-amount-monthly-<?php echo $value["id"]; ?>" class="form-control hidden" value="<?php echo (int)$est_monthly; ?>" min="0" step="1">
                    </td>
                    <td>
                      <span id="amount-yearly-<?php echo $value["id"]; ?>"><?php echo number_format((float)$value["amount"], 0, '.', ''); ?></span>
                      <input type="number" id="edit-amount-yearly-<?php echo $value["id"]; ?>" class="form-control hidden" value="<?php echo (int)$value["amount"]; ?>" min="0" step="1">
                    </td>
                    <td>
                      <button id="edit-btn-<?php echo $value["id"]; ?>" data-sabeel-grade-id="<?php echo $value["id"]; ?>" data-sabeel-type="Establishment" class="edit-btn btn btn-sm btn-secondary">Edit</button>
                      <button id="save-btn-<?php echo $value["id"]; ?>" data-sabeel-grade-id="<?php echo $value["id"]; ?>" data-sabeel-type="Establishment" class="save-btn btn btn-sm btn-success hidden">Save</button>
                      <button id="cancel-btn-<?php echo $value["id"]; ?>" data-sabeel-grade-id="<?php echo $value["id"]; ?>" class="cancel-btn btn btn-sm btn-light hidden">Cancel</button>
                      <?php if (!empty($value["in_use"])): ?>
                        <button class="btn btn-sm btn-danger" title="This item can't be deleted as it is assign to a member." disabled>Delete</button>
                      <?php else: ?>
                        <a href="<?php echo base_url("admin/deletesabeelgrade/" . $value["id"]); ?>"
                          class="delete-sabeel-grade btn btn-sm btn-danger">Delete</a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4">No records found!</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-12 col-md-6">
      <div class="p-2 border rounded text-right">
        <h4 class="text-center mt-3">Residential Sabeel Grade<?php echo $displayYear ? " – $displayYear" : ""; ?></h4>
        <button id="add-r-sabeel-grade-btn" class="add-sabeel-grade-btn btn btn-sm btn-primary" data-index="1">Add Grade</button>
        <div id="add-r-sabeel-grade" class="add-sabeel-grade hidden border mt-3 rounded">
          <form method="post" action="<?php echo base_url('admin/addsabeelgrade/2') ?>" id="add-r-sabeel-form" class="add-sabeel-grade-form">
            <div class="row modal-body">
              <div class="col-12 col-md-12">
                <select name="r_sabeel_year" id="r-sabeel-year" class="sabeel-year form-control" required>
                  <option value="">Select Year</option>
                  <?php
                  $yearRanges = [
                    '1442-43',
                    '1443-44',
                    '1444-45',
                    '1445-46',
                    '1446-47',
                    '1447-48',
                    '1448-49',
                    '1449-50',
                    '1450-51',
                    '1451-52',
                    '1452-53',
                    '1453-54',
                    '1454-55',
                    '1455-56',
                    '1456-57',
                    '1457-58'
                  ];
                  if (!function_exists('renderYearOption')) {
                    function renderYearOption($yr, $current)
                    {
                      $sel = ($current === $yr) ? 'selected' : '';
                      echo "<option value=\"{$yr}\" {$sel}>{$yr}</option>";
                    }
                  }
                  $currentSelected = isset($sabeel_year) ? $sabeel_year : null;
                  foreach ($yearRanges as $yr) {
                    renderYearOption($yr, $currentSelected);
                  }
                  ?>
                </select>
              </div>
              <div id="add-e-sabeel-grade-table" class="row col-12 col-md-12 mt-3">
                <div class="col-12 col-md-4">
                  <select name="r_sabeel_grade" id="r-sabeel-grade" class="sabeel-grade form-control" required></select>
                </div>
                <div class="col-12 col-md-4 mt-2 mt-md-0">
                  <input type="number" name="r_sabeel_amount_monthly" id="r_sabeel_amount_monthly" class="sabeel-amount-monthly form-control" placeholder="Monthly amount" min="0" step="1">
                </div>
                <div class="col-12 col-md-4 mt-2 mt-md-0">
                  <input type="number" name="r_sabeel_amount_yearly" id="r_sabeel_amount_yearly" class="sabeel-amount-yearly form-control" placeholder="Yearly amount" min="0" step="1">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="hide-add-container btn btn-secondary" data-index="1">Cancel</button>
              <button type="submit" class="submit-sabeel-grade-btn btn btn-primary" data-index="1">Submit</button>
            </div>
          </form>
        </div>
        <table class="table table-hover table-striped mt-3">
          <thead>
            <tr>
              <th>Grade</th>
              <th>Monthly</th>
              <th>Yearly</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($sabeel_grades)): ?>
              <?php foreach ($sabeel_grades as $key => $value): ?>
                <?php if ($value["type"] == "Residential"): ?>
                  <tr>
                    <td><?php echo $value["grade"]; ?></td>
                    <td>
                      <?php $res_monthly = ($value['yearly_amount'] && is_numeric($value['yearly_amount'])) ? floor($value['yearly_amount']/12) : ((isset($value['amount']) && is_numeric($value['amount'])) ? (int)$value['amount'] : 0); ?>
                      <span id="amount-monthly-<?php echo $value["id"]; ?>"><?php echo (int)$res_monthly; ?></span>
                      <input type="number" id="edit-amount-monthly-<?php echo $value["id"]; ?>" class="form-control hidden" value="<?php echo (int)$res_monthly; ?>" min="0" step="1">
                    </td>
                    <td>
                      <span id="amount-yearly-<?php echo $value["id"]; ?>"><?php echo number_format((float)$value["yearly_amount"], 0, '.', ''); ?></span>
                      <input type="number" id="edit-amount-yearly-<?php echo $value["id"]; ?>" class="form-control hidden" value="<?php echo (int)$value["yearly_amount"]; ?>" min="0" step="1">
                    </td>
                    <td>
                      <button id="edit-btn-<?php echo $value["id"]; ?>" data-sabeel-grade-id="<?php echo $value["id"]; ?>" data-sabeel-type="<?php echo $value["type"]; ?>" class="edit-btn btn btn-sm btn-secondary">Edit</button>
                      <button id="save-btn-<?php echo $value["id"]; ?>" data-sabeel-grade-id="<?php echo $value["id"]; ?>" data-sabeel-type="<?php echo $value["type"]; ?>" class="save-btn btn btn-sm btn-success hidden">Save</button>
                      <button id="cancel-btn-<?php echo $value["id"]; ?>" data-sabeel-grade-id="<?php echo $value["id"]; ?>" class="cancel-btn btn btn-sm btn-light hidden">Cancel</button>
                      <?php if (!empty($value["in_use"])): ?>
                        <button class="btn btn-sm btn-danger" title="This item can't be deleted as it is assign to a member." disabled>Delete</button>
                      <?php else: ?>
                        <a href="<?php echo base_url("admin/deletesabeelgrade/" . $value["id"]); ?>"
                          class="delete-sabeel-grade btn btn-sm btn-danger">Delete</a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4">No records found!</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  $(".form-filter").on("change", function() {
    $("#filter-sabeel-grade-form").submit();
  });

  $(".add-sabeel-grade-btn").on("click", function(e) {
    $grades = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
    $gradeOptions = "";
    $grades.forEach(grade => {
      $gradeOptions += `<option value="${grade}">${grade}</option>`;
    });
    $index = $(this).data("index");
    $(".sabeel-grade").eq($index).html($gradeOptions);
    $(".add-sabeel-grade").eq($index).removeClass("hidden");
  });

  $(".hide-add-container").on("click", function(e) {
    $index = $(this).data("index");
    $(".add-sabeel-grade").eq($index).addClass("hidden");
  });

  $(".edit-btn").on("click", function(e) {
    $(this).addClass("hidden");
    $id = $(this).data("sabeel-grade-id");
    $("#amount-monthly-" + $id).addClass("hidden");
    $("#amount-yearly-" + $id).addClass("hidden");
    $("#edit-amount-monthly-" + $id).removeClass("hidden");
    $("#edit-amount-yearly-" + $id).removeClass("hidden");
    $("#save-btn-" + $id).removeClass("hidden");
    $("#cancel-btn-" + $id).removeClass("hidden");
  });

  $(".save-btn").on("click", function(e) {
    $(this).addClass("hidden");
    $id = $(this).data("sabeel-grade-id");
    $type = $(this).data("sabeel-type");

    // Read edited values
    const $editMonthly = $("#edit-amount-monthly-" + $id);
    const $editYearly = $("#edit-amount-yearly-" + $id);
    let monthlyVal = $editMonthly.val();
    let yearlyVal = $editYearly.val();

    // Normalize to numbers or null
    monthlyVal = monthlyVal !== '' ? parseInt(monthlyVal, 10) : null;
    yearlyVal = yearlyVal !== '' ? parseInt(yearlyVal, 10) : null;

    // Derive missing counterpart
    if ($type === 'Establishment') {
      if (yearlyVal === null && monthlyVal !== null) {
        yearlyVal = monthlyVal * 12;
      } else if (monthlyVal === null && yearlyVal !== null) {
        monthlyVal = Math.floor(yearlyVal / 12);
      }
    } else {
      // Residential: base is monthly
      if (monthlyVal === null && yearlyVal !== null) {
        monthlyVal = Math.floor(yearlyVal / 12);
      } else if (yearlyVal === null && monthlyVal !== null) {
        yearlyVal = monthlyVal * 12;
      }
    }

    if ((monthlyVal === null || isNaN(monthlyVal)) && (yearlyVal === null || isNaN(yearlyVal))) {
      alert('Please enter monthly or yearly amount.');
      $(this).removeClass('hidden');
      return;
    }

    $.ajax({
      url: "<?php echo base_url("admin/updatesabeelgrade"); ?>",
      type: "POST",
      data: {
        id: $id,
        type: $type,
        amount_monthly: monthlyVal,
        amount_yearly: yearlyVal
      },
      success: function(res) {
        try { $res = JSON.parse(res); } catch (e) { $res = { success: false }; }
        if ($res && $res.success) {
          alert("Sabeel Grade Updated Successfully!");
          location.reload();
        }
      }
    })
  });

  $(".cancel-btn").on("click", function(){
    const id = $(this).data("sabeel-grade-id");
    // Reset inputs to current displayed values
    const mText = ($("#amount-monthly-" + id).text() || '').trim();
    const yText = ($("#amount-yearly-" + id).text() || '').trim();
    const mVal = mText !== '' ? parseInt(mText, 10) : '';
    const yVal = yText !== '' ? parseInt(yText, 10) : '';
    $("#edit-amount-monthly-" + id).val(isNaN(mVal) ? '' : mVal);
    $("#edit-amount-yearly-" + id).val(isNaN(yVal) ? '' : yVal);

    // Toggle back to view mode
    $("#edit-amount-monthly-" + id).addClass("hidden");
    $("#edit-amount-yearly-" + id).addClass("hidden");
    $("#amount-monthly-" + id).removeClass("hidden");
    $("#amount-yearly-" + id).removeClass("hidden");
    $("#save-btn-" + id).addClass("hidden");
    $("#cancel-btn-" + id).addClass("hidden");
    $("#edit-btn-" + id).removeClass("hidden");
  });

  $(".delete-sabeel-grade").on("click", function(e) {
    $result = confirm("Are you sure you want to delete this sabeel grade?");
    if (!$result) {
      e.preventDefault();
    }
  });

  $(".submit-sabeel-grade-btn").on("click", function(event) {
    event.preventDefault();
    $index = $(this).data("index");
    $sabeelYear = $(".sabeel-year").eq($index).val();
    $sabeelGrade = $(".sabeel-grade").eq($index).val();
    const $monthlyField = $(".sabeel-amount-monthly").eq($index);
    const $yearlyField = $(".sabeel-amount-yearly").eq($index);
    let monthlyVal = $monthlyField.val();
    let yearlyVal = $yearlyField.val();

    monthlyVal = monthlyVal !== '' ? parseInt(monthlyVal, 10) : null;
    yearlyVal = yearlyVal !== '' ? parseInt(yearlyVal, 10) : null;

    if ($sabeelYear == "" || $sabeelGrade == "") {
      alert("Fields can't be empty");
      return;
    }

    // Derive missing counterpart based on section type (index 0 = Establishment, 1 = Residential)
    if ($index === 0) {
      // Establishment: base is yearly
      if (yearlyVal === null && monthlyVal !== null) yearlyVal = monthlyVal * 12;
      if (monthlyVal === null && yearlyVal !== null) monthlyVal = Math.floor(yearlyVal / 12);
    } else {
      // Residential: base is monthly
      if (monthlyVal === null && yearlyVal !== null) monthlyVal = Math.floor(yearlyVal / 12);
      if (yearlyVal === null && monthlyVal !== null) yearlyVal = monthlyVal * 12;
    }

    if ((monthlyVal === null || isNaN(monthlyVal)) && (yearlyVal === null || isNaN(yearlyVal))) {
      alert('Please enter monthly or yearly amount.');
      return;
    }

    // Write back computed values so they submit with the form
    $monthlyField.val(monthlyVal !== null ? monthlyVal : '');
    $yearlyField.val(yearlyVal !== null ? yearlyVal : '');

    $.ajax({
      url: "<?php echo base_url("admin/validatesabeelgrade"); ?>",
      type: "POST",
      data: {
        "type": $index + 1,
        "year": $sabeelYear,
        "grade": $sabeelGrade,
      },
      success: function(res) {
        if (res) {
          $res = JSON.parse(res);
          if ($res.success) {
            alert("Sabeel grade already exists!");
            return;
          }
        } else {
          $(".add-sabeel-grade-form").eq($index).off("submit").submit();
        }
      }
    })
  });

  // Live sync: typing monthly updates yearly (x12) and vice-versa
  function toInt(val) {
    if (val === undefined || val === null || val === '') return null;
    const n = parseInt(val, 10);
    return isNaN(n) ? null : Math.max(0, n);
  }
  function syncFromMonthly($monthly, $yearly){
    const m = toInt($monthly.val());
    if (m !== null) { $yearly.val(m * 12); }
  }
  function syncFromYearly($yearly, $monthly){
    const y = toInt($yearly.val());
    if (y !== null) { $monthly.val(Math.floor(y / 12)); }
  }

  // Add forms (use class selectors)
  $(document).on('input', '.sabeel-amount-monthly', function(){
    const $container = $(this).closest('#add-e-sabeel-grade, #add-r-sabeel-grade');
    const $yearly = $container.find('.sabeel-amount-yearly').eq(0);
    syncFromMonthly($(this), $yearly);
  });
  $(document).on('input', '.sabeel-amount-yearly', function(){
    const $container = $(this).closest('#add-e-sabeel-grade, #add-r-sabeel-grade');
    const $monthly = $container.find('.sabeel-amount-monthly').eq(0);
    syncFromYearly($(this), $monthly);
  });

  // Table edit inputs (use id-prefix selectors)
  $(document).on('input', "input[id^='edit-amount-monthly-']", function(){
    const id = $(this).attr('id').replace('edit-amount-monthly-','');
    const $yearly = $("#edit-amount-yearly-" + id);
    syncFromMonthly($(this), $yearly);
  });
  $(document).on('input', "input[id^='edit-amount-yearly-']", function(){
    const id = $(this).attr('id').replace('edit-amount-yearly-','');
    const $monthly = $("#edit-amount-monthly-" + id);
    syncFromYearly($(this), $monthly);
  });
</script>