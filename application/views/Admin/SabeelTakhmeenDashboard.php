<style>
  .hidden {
    display: none;
  }
</style>
<div class="margintopcontainer mx-5 pt-5">
  <div class="p-0">
    <a href="<?php echo base_url("admin/managesabeeltakhmeen"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="heading text-center mb-4">Manage Sabeel Takhmeen</h4>
  <div class="row mb-4">
    <div class="col-12 col-md-6 d-flex m-0">
      <form method="POST" action="<?php echo base_url("admin/filteruserinsabeeltakhmeen"); ?>" class="d-flex m-0">
        <input type="text" name="member_name" id="member-name" class="form-control" placeholder="Member name" value="<?php echo isset($member_name) ? $member_name : ""; ?>">
        <?php
          // Build Hijri composite year dropdown (1442-43 to 1457-58) and default to current Hijri FY
          $headerYearRanges = [
            '1442-43','1443-44','1444-45','1445-46','1446-47','1447-48','1448-49','1449-50','1450-51','1451-52','1452-53','1453-54','1454-55','1455-56','1456-57','1457-58'
          ];
          $hdrComposite = '';
          $CI =& get_instance();
          $CI->load->model('HijriCalendar');
          $parts = $CI->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
          $hy = isset($parts['hijri_year']) ? (int)$parts['hijri_year'] : 0;
          $hm = isset($parts['hijri_month']) ? (int)$parts['hijri_month'] : 0;
          if ($hy === 0) {
            $hd = $CI->HijriCalendar->get_hijri_date(date('Y-m-d'));
            if ($hd && isset($hd['hijri_date'])) {
              $p = explode('-', $hd['hijri_date']); // d-m-Y
              if (count($p) === 3) { $hm = (int)$p[1]; $hy = (int)$p[2]; }
            }
          }
          if ($hy > 0) {
            if ($hm >= 9) { $sy = $hy; $ey = $hy + 1; }
            else { $sy = $hy - 1; $ey = $hy; }
            $hdrComposite = $sy . '-' . substr((string)$ey, -2);
          }
          $selectedYear = isset($sabeel_year) && $sabeel_year !== '' ? $sabeel_year : $hdrComposite;
        ?>
        <select name="sabeel_year" id="filter-year" class="form-control ml-2">
          <option value="">Year</option>
          <?php foreach ($headerYearRanges as $yr): ?>
            <option value="<?php echo $yr; ?>" <?php echo ($selectedYear === $yr ? 'selected' : ''); ?>><?php echo $yr; ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary ml-2">Submit</button>
      </form>
      <a href="<?php echo base_url("admin/sabeeltakhmeendashboard"); ?>">
        <button class="btn btn-outline-secondary ml-2">
          <i class="fa-solid fa-times"></i>
        </button>
      </a>
    </div>
  </div>
  <div>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th colspan="4" class="text-center">Member Info</th>
          <th colspan="3" class="text-center">Establishment Sabeel</th>
          <th colspan="3" class="text-center">Residential Sabeel</th>
          <th rowspan="2" class="text-center">Current Year Total</th>
          <th rowspan="2" class="text-center">Action</th>
        </tr>
        <tr>
          <th data-no-sort>Sr. No.</th>
          <th>ITS ID</th>
          <th>Name</th>
          <th>Sector</th>
          <th>Grade</th>
          <th>Takhmeen Per Month</th>
          <th>Takhmeen Yearly</th>
          <th>Grade</th>
          <th>Takhmeen Per Month</th>
          <th>Takhmeen Yearly</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        // Determine the current Hijri financial composite year (months 09–12 + next year 01–08)
        $CI =& get_instance();
        $CI->load->model('HijriCalendar');
        $parts = $CI->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
        $hYear = isset($parts['hijri_year']) ? (int)$parts['hijri_year'] : 0;
        $hMonth = isset($parts['hijri_month']) ? (int)$parts['hijri_month'] : 0;
        if ($hYear === 0) {
          $hd = $CI->HijriCalendar->get_hijri_date(date('Y-m-d'));
          if ($hd && isset($hd['hijri_date'])) {
            $p = explode('-', $hd['hijri_date']); // d-m-Y
            if (count($p) === 3) { $hMonth = (int)$p[1]; $hYear = (int)$p[2]; }
          }
        }
        if ($hYear > 0) {
          if ($hMonth >= 9) { $startYear = $hYear; $endYear = $hYear + 1; }
          else { $startYear = $hYear - 1; $endYear = $hYear; }
          $currentCompositeYear = $startYear . '-' . substr((string)$endYear, -2);
        } else {
          $currentCompositeYear = '';
        }
        // If a filter year is provided (from the header dropdown), use it
        if (!empty($sabeel_year)) { $currentCompositeYear = $sabeel_year; }
        ?>
        <?php if (!empty($all_user_sabeel_takhmeen)) : ?>
          <?php foreach ($all_user_sabeel_takhmeen as $key => $user) :
            // Find the takhmeen entry whose year matches the true current composite year
            $current = null;
            if (!empty($user['takhmeens']) && is_array($user['takhmeens'])) {
              foreach ($user['takhmeens'] as $tRow) {
                if (isset($tRow['year']) && $tRow['year'] === $currentCompositeYear) { $current = $tRow; break; }
              }
            }
            // Optional fallback only if provided record matches the current Hijri composite year
            if (!$current && isset($user['current_year_takhmeen']) && isset($user['current_year_takhmeen']['year']) && $user['current_year_takhmeen']['year'] === $currentCompositeYear) {
              $current = $user['current_year_takhmeen'];
            }
            $cy = $current ? $current['year'] : '';
            $cid = $current ? $current['id'] : '';
            $est = $current ? $current['establishment'] : ['grade' => '', 'yearly' => '', 'monthly' => ''];
            $res = $current ? $current['residential'] : ['grade' => '', 'yearly' => '', 'monthly' => ''];
            $est_yearly_val = (isset($est['yearly']) && $est['yearly'] !== '' && is_numeric($est['yearly'])) ? (float)$est['yearly'] : null;
            $res_yearly_val = (isset($res['yearly']) && $res['yearly'] !== '' && is_numeric($res['yearly'])) ? (float)$res['yearly'] : null;
            $total_yearly = ($est_yearly_val !== null || $res_yearly_val !== null) ? (float)($est_yearly_val + $res_yearly_val) : null;
          ?>
            <tr data-its-id="<?php echo htmlspecialchars((string)($user['ITS_ID'] ?? ''), ENT_QUOTES); ?>" data-current-year="<?php echo htmlspecialchars((string)$cy, ENT_QUOTES); ?>">
              <td><?php echo $key + 1; ?></td>
              <td data-sort-value="<?php echo htmlspecialchars((string)($user['ITS_ID'] ?? ''), ENT_QUOTES); ?>"><?php echo htmlspecialchars((string)($user['ITS_ID'] ?? '')); ?></td>
              <td data-sort-value="<?php 
                $__fullName = trim((string)($user['Full_Name'] ?? ''));
                if ($__fullName === '') { $__fullName = trim((string)($user['First_Name'] ?? '') . ' ' . (string)($user['Surname'] ?? '')); }
                echo htmlspecialchars(strtolower($__fullName), ENT_QUOTES);
              ?>">
                <?php echo htmlspecialchars($__fullName); ?>
                <!-- <br><small class="text-muted">ITS: <?php echo htmlspecialchars((string)($user['ITS_ID'] ?? '')); ?></small> -->
              </td>
              <td data-sort-value="<?php echo htmlspecialchars(strtolower(trim((string)($user['Sector'] ?? '') . ' - ' . (string)($user['Sub_Sector'] ?? ''))), ENT_QUOTES); ?>"><?php echo htmlspecialchars(trim((string)($user['Sector'] ?? '') . ' - ' . (string)($user['Sub_Sector'] ?? ''))); ?></td>
              <td data-sort-value="<?php echo htmlspecialchars(strtolower($estGrade ?? ''), ENT_QUOTES); ?>">
                <?php 
                  $estGrade = isset($est['grade']) ? trim((string)$est['grade']) : '';
                  $showEstGrade = ($estGrade !== '' && strcasecmp($estGrade,'no grade') !== 0 && strcasecmp($estGrade,'unknown') !== 0);
                  echo $showEstGrade ? htmlspecialchars($estGrade) : '';
                ?>
                <br><small class="text-muted"><?php echo $cy ? '(' . htmlspecialchars((string)$cy) . ')' : ''; ?></small>
              </td>
              <td class="takhmeen-amount" data-sort-value="<?php echo isset($est['monthly']) && is_numeric($est['monthly']) ? (float)$est['monthly'] : 0; ?>"><?php echo (isset($est['monthly']) && is_numeric($est['monthly']) && $est['monthly'] > 0) ? round($est['monthly']) : ''; ?></td>
              <td class="takhmeen-amount" data-sort-value="<?php echo isset($est['yearly']) && is_numeric($est['yearly']) ? (float)$est['yearly'] : 0; ?>"><?php echo (isset($est['yearly']) && is_numeric($est['yearly']) && $est['yearly'] > 0) ? round($est['yearly']) : ''; ?></td>
              <td>
                <?php 
                  $resGrade = isset($res['grade']) ? trim((string)$res['grade']) : '';
                  $showResGrade = ($resGrade !== '' && strcasecmp($resGrade,'no grade') !== 0 && strcasecmp($resGrade,'unknown') !== 0);
                  echo $showResGrade ? htmlspecialchars($resGrade) : '';
                ?>
                <br><small class="text-muted"><?php echo $cy ? '(' . htmlspecialchars((string)$cy) . ')' : ''; ?></small>
              </td>
              <td class="takhmeen-amount" data-sort-value="<?php echo isset($res['monthly']) && is_numeric($res['monthly']) ? (float)$res['monthly'] : 0; ?>"><?php echo (isset($res['monthly']) && is_numeric($res['monthly']) && $res['monthly'] > 0) ? round($res['monthly']) : ''; ?></td>
              <td class="takhmeen-amount" data-sort-value="<?php echo isset($res['yearly']) && is_numeric($res['yearly']) ? (float)$res['yearly'] : 0; ?>"><?php echo (isset($res['yearly']) && is_numeric($res['yearly']) && $res['yearly'] > 0) ? round($res['yearly']) : ''; ?></td>
              <td class="takhmeen-amount" data-sort-value="<?php echo isset($total_yearly) && is_numeric($total_yearly) ? (float)$total_yearly : 0; ?>"><?php echo (isset($total_yearly) && is_numeric($total_yearly) && $total_yearly > 0) ? round($total_yearly) : ''; ?></td>
              <td class="text-nowrap">
                <button class="add-takhmeen btn btn-success btn-sm mb-1" data-toggle="modal" data-target="#add-takhmeen-container" data-user-id="<?php echo htmlspecialchars((string)($user['ITS_ID'] ?? '')); ?>" data-user-name="<?php echo htmlspecialchars($__fullName); ?>">Add</button>
                <?php /* Edit feature removed */ ?>
                <button type="button" class="btn btn-outline-secondary btn-sm view-takhmeen mb-1" data-user-id="<?php echo htmlspecialchars((string)($user['ITS_ID'] ?? '')); ?>" data-user-name="<?php echo htmlspecialchars($__fullName); ?>" data-takhmeens='<?php echo htmlspecialchars(json_encode($user['takhmeens'] ?? []), ENT_QUOTES, "UTF-8"); ?>' <?php echo empty($user['takhmeens']) ? 'disabled' : ''; ?>>View Takhmeen</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="add-takhmeen-container" tabindex="-1" aria-labelledby="add-takhmeen-container-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add-takhmeen-container-label">Add sabeel Takhmeen Amount</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?php echo base_url("admin/addsabeeltakhmeenamount"); ?>" id="add-sabeel-form">
          <input type="hidden" name="user_id" id="user-id">
          <p><b>Member Name: <span id="user-name">Member Name</span></b></p>
          <label for="takhmeen-year" class="form-label">Takhmeen Year</label>
          <select name="sabeel_takhmeen_year" id="takhmeen-year" class="form-control" required>
            <option value="">-----</option>
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
          <br>
          <label for="establishment-grade" class="form-label">Establishment Sabeel Grade</label>
          <select name="establishment_grade" id="establishment-grade" class="form-control">
            <option value="">-----</option>
          </select>
          <p class="hidden takhmeen-amount pt-2 m-0" id="e-amount"></p>
          <br>
          <label for="residential-grade" class="form-label">Residential Sabeel Grade</label>
          <select name="residential_grade" id="residential-grade" class="form-control">
            <option value="">-----</option>
          </select>
          <p class="hidden takhmeen-amount pt-2 m-0" id="r-amount"></p>
          <br>
          <button type="submit" id="add-takhmeen-btn" class="btn btn-primary text-right">Add Takhmeen</button>
          <p id="validate-takhmeen" class="text-secondary pt-3 m-0"></p>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- View Takhmeen Modal -->
<div class="modal fade" id="view-takhmeen-modal" tabindex="-1" role="dialog" aria-labelledby="viewTakhmeenLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewTakhmeenLabel">Sabeel Takhmeen History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="vt-summary" class="mb-3 small"></div>
        <div class="table-responsive">
          <table class="table table-sm table-bordered" id="vt-history-table">
            <thead class="thead-light">
              <tr>
                <th>Year</th>
                <th>Est. Grade</th>
                <th>Est. Monthly</th>
                <th>Est. Yearly</th>
                <th>Res. Grade</th>
                <th>Res. Monthly</th>
                <th>Res. Yearly</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="alert alert-warning small" id="vt-edit-hint">Click Edit to modify grades for a specific year. Deleting removes that year's takhmeen permanently.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
  function formatCurrencyCells(context) {
    const $scope = context ? $(context) : $(document);
    $scope.find('.takhmeen-amount').each(function() {
      const raw = $(this).text().trim();
      if (raw === '' || isNaN(raw)) return;
      const formatted = '&#8377;' + new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0, minimumFractionDigits: 0 }).format(Number(raw));
      $(this).html(formatted);
    });
  }
  $(function(){
    formatCurrencyCells();
    // Auto-submit on year change to refresh table for selected FY
    $('#filter-year').on('change', function(){
      $(this).closest('form').trigger('submit');
    });
  });

  // Open View Takhmeen modal
  $(document).on('click','.view-takhmeen', function(){
    const userId = $(this).data('user-id');
    const userName = $(this).data('user-name');
    let takhmeens = [];
    try { takhmeens = JSON.parse($(this).attr('data-takhmeens')); } catch(e){ takhmeens = []; }
    const $tbody = $('#vt-history-table tbody');
    $tbody.empty();
    if(!takhmeens.length){
      $tbody.append('<tr><td colspan="8" class="text-center text-muted">No records.</td></tr>');
    } else {
      takhmeens.forEach(t => {
        const rowId = `trow-${t.id}`;
        $tbody.append(`<tr id="${rowId}">
          <td>${t.year}</td>
          <td>${t.establishment.grade || ''}</td>
          <td class='takhmeen-amount'>${t.establishment.monthly !== '' ? t.establishment.monthly : ''}</td>
          <td class='takhmeen-amount'>${t.establishment.yearly !== '' ? t.establishment.yearly : ''}</td>
          <td>${t.residential.grade || ''}</td>
          <td class='takhmeen-amount'>${t.residential.monthly !== '' ? t.residential.monthly : ''}</td>
            <td class='takhmeen-amount'>${t.residential.yearly !== '' ? t.residential.yearly : ''}</td>
          <td class='text-nowrap'>
             <button class="btn btn-sm btn-outline-primary vt-edit" data-tid="${t.id}" data-year="${t.year}" data-user-id="${userId}">Edit</button>
             <button class="btn btn-sm btn-outline-danger vt-delete" data-tid="${t.id}" data-year="${t.year}" data-user-id="${userId}">Delete</button>
          </td>
        </tr>`);
        // Hidden editable row
        $tbody.append(`<tr id="edit-${t.id}" class="vt-edit-row d-none">
          <td colspan="8">
            <form class="form-inline vt-edit-form" data-tid="${t.id}" data-user-id="${userId}" data-year="${t.year}">
              <div class="form-group mb-2 mr-2">
                <label class="mr-1 small">Est. Grade</label>
                <select class="form-control form-control-sm est-grade-select"></select>
              </div>
              <div class="form-group mb-2 mr-2">
                <label class="mr-1 small">Res. Grade</label>
                <select class="form-control form-control-sm res-grade-select"></select>
              </div>
              <button type="submit" class="btn btn-sm btn-success mb-2 mr-2">Save</button>
              <button type="button" class="btn btn-sm btn-secondary mb-2 vt-cancel-edit">Cancel</button>
              <span class="ml-3 small text-muted vt-status"></span>
            </form>
          </td>
        </tr>`);
      });
    }
    $('#vt-summary').html(`<strong>${userName}</strong> (ITS: ${userId}) - Years: ${takhmeens.length}`);
    $('#view-takhmeen-modal').modal('show');
    formatCurrencyCells('#view-takhmeen-modal');
    $('#view-takhmeen-modal').data('currentUserId', userId);
  });

  $(".add-takhmeen").on("click", function(e) {
    $userId = $(this).data("user-id");
    $userName = $(this).data("user-name");
    $("#user-id").val($userId);
    $("#user-name").html($userName);

    // Reset the form so takhmeen year is blank by default each time Add is clicked
    $("#takhmeen-year").val("");
    // Ensure first placeholder option is selected (in case browsers keep old selection)
    $("#takhmeen-year option:first").prop("selected", true);
    // Clear grade dropdowns and restore placeholder option
    $("#establishment-grade").html('<option value="">-----</option>');
    $("#residential-grade").html('<option value="">-----</option>');
    // Hide and clear any previously shown amount hints
    $("#e-amount").addClass("hidden").html("");
    $("#r-amount").addClass("hidden").html("");
    // Reset validation / warning message state
    $("#validate-takhmeen").text("").removeClass("text-danger").addClass("text-secondary");
    // Re-enable submit button (it may have been disabled if a duplicate year was detected earlier)
    $("#add-takhmeen-btn").prop("disabled", false);
  });

  $("#takhmeen-year").on("change", function(e) {
    $("#establishment-grade").html("");
    $("#residential-grade").html("");
    $takhmeen_year = $(this).val();
    $user_id = $("#user-id").val();
    $.ajax({
      url: "<?php echo base_url("admin/getsabeelgrades") ?>",
      type: "POST",
      data: {
        "sabeel_year": $takhmeen_year,
      },
      success: function(res) {
        if (res) {
          $sabeel_grades = JSON.parse(res);
          $eGradeOptions = `<option value="">Select Grade</option>`;
          $rGradeOptions = `<option value="">Select Grade</option>`;
          $e_grades = $sabeel_grades.filter((grade) => grade.type == "Establishment");
          $e_grades.forEach(grade => {
            $eGradeOptions += `<option value="${grade.id}" data-grade-amount="${grade.amount}">${grade.grade}</option>`;
          });
          $r_grades = $sabeel_grades.filter((grade) => grade.type == "Residential");
          $r_grades.forEach(grade => {
            $rGradeOptions += `<option value="${grade.id}" data-grade-amount="${grade.amount}">${grade.grade}</option>`;
          });
          $("#establishment-grade").html($eGradeOptions);
          $("#residential-grade").html($rGradeOptions);
        } else {
          alert("No sabeel grades found for this year.")
        }
      }
    });

    if ($user_id && $takhmeen_year) {
      $.ajax({
        url: "<?php echo base_url('admin/checkSabeelTakhmeenExists'); ?>",
        type: "POST",
        data: {
          user_id: $user_id,
          year: $takhmeen_year
        },
        success: function(res) {
          const data = JSON.parse(res);

          if (data.exists) {
            $("#add-takhmeen-btn").prop("disabled", true);
            $("#validate-takhmeen")
              .text("⚠️ Takhmeen already exists for this year!")
              .removeClass("text-secondary")
              .addClass("text-danger");
          } else {
            $("#add-takhmeen-btn").prop("disabled", false);
            $("#validate-takhmeen")
              .text("")
              .removeClass("text-danger")
              .addClass("text-secondary");
          }
        },
      });
    }
  });

  $("#establishment-grade").on("change", function(e) {
    const selected = $(this).find(":selected");
    const amount = selected.data("grade-amount");
    $eAmountElem = $("#e-amount");
    const disp = (amount !== undefined && amount !== null && amount !== '') ? ('&#8377;' + new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0, minimumFractionDigits: 0 }).format(Number(amount))) : '';
    $eAmountElem.html(`<span class="text-muted">Total takhmeen for the year ${disp}</span>`);
    $eAmountElem.removeClass("hidden");
  });
  $("#residential-grade").on("change", function(e) {
    const selected = $(this).find(":selected");
    const amount = selected.data("grade-amount");
    $rAmountElem = $("#r-amount");
    const disp = (amount !== undefined && amount !== null && amount !== '') ? ('&#8377;' + new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0, minimumFractionDigits: 0 }).format(Number(amount))) : '';
    $rAmountElem.html(`<span class="text-muted">Total takhmeen per month ${disp}</span>`);
    $rAmountElem.removeClass("hidden");
  });

  // Ensure at least one grade is selected when adding takhmeen
  $(document).on('submit', '#add-sabeel-form', function(e){
    const est = String($("#establishment-grade").val() || '').trim();
    const res = String($("#residential-grade").val() || '').trim();
    if(!est && !res){
      e.preventDefault();
      $("#validate-takhmeen").text("Please select at least one grade (Establishment or Residential).").removeClass("text-secondary").addClass("text-danger");
      return false;
    }
    return true;
  });

  // Fetch grades helper
  function fetchSabeelGrades(year, cb){
    $.ajax({
      url: '<?php echo base_url("admin/getsabeelgrades"); ?>',
      type: 'POST',
      data: { sabeel_year: year },
      success: function(res){
        try { cb(null, JSON.parse(res)); } catch(e){ cb(e); }
      },
      error: function(){ cb('Network error'); }
    });
  }

  // Open edit row
  $(document).on('click', '.vt-edit', function(){
    const tid = $(this).data('tid');
    const year = $(this).data('year');
    const userId = $(this).data('user-id');
    const $editRow = $('#edit-'+tid);
    if(!$editRow.length) return;
    // Populate grade selects
    fetchSabeelGrades(year, function(err, grades){
      if(err || !grades){ alert('Unable to fetch grades'); return; }
      const est = grades.filter(g => g.type === 'Establishment');
      const res = grades.filter(g => g.type === 'Residential');
      const $estSel = $editRow.find('.est-grade-select');
      const $resSel = $editRow.find('.res-grade-select');
      $estSel.empty().append('<option value="">--Establishment--</option>');
      est.forEach(g => $estSel.append(`<option value="${g.id}">${g.grade}</option>`));
      $resSel.empty().append('<option value="">--Residential--</option>');
      res.forEach(g => $resSel.append(`<option value="${g.id}">${g.grade}</option>`));
      $('.vt-edit-row').addClass('d-none'); // hide others
      $editRow.removeClass('d-none');
    });
  });

  // Cancel edit
  $(document).on('click', '.vt-cancel-edit', function(){
    $(this).closest('.vt-edit-row').addClass('d-none');
  });

  // Submit edit form
  $(document).on('submit', '.vt-edit-form', function(e){
    e.preventDefault();
    const $form = $(this);
    const tid = $form.data('tid');
    const year = $form.data('year');
    const userId = $form.data('user-id');
    const establishment_grade = String($form.find('.est-grade-select').val() || '').trim();
    const residential_grade = String($form.find('.res-grade-select').val() || '').trim();
    const $status = $form.find('.vt-status').removeClass('text-danger text-success').text('Saving...');
    if(!establishment_grade && !residential_grade){
      $status.addClass('text-danger').text('Select at least one grade');
      return;
    }
    $.ajax({
      url: '<?php echo base_url("admin/updatesabeeltakhmeen"); ?>',
      type: 'POST',
      data: { user_id: userId, takhmeen_id: tid, establishment_grade, residential_grade },
      success: function(res){
        try { const data = JSON.parse(res); if(data.success){
          $status.addClass('text-success').text('Updated');
          // Refresh page to show latest amounts (simpler than partial update)
          setTimeout(() => { window.location.reload(); }, 600);
        } else { $status.addClass('text-danger').text('Update failed'); } }
        catch(e){ $status.addClass('text-danger').text('Invalid response'); }
      },
      error: function(){ $status.addClass('text-danger').text('Network error'); }
    });
  });

  // Delete takhmeen
  $(document).on('click', '.vt-delete', function(){
    if(!confirm('Delete this takhmeen year? This cannot be undone.')) return;
    const tid = $(this).data('tid');
    const year = $(this).data('year');
    const userId = $(this).data('user-id');
    $.ajax({
      url: '<?php echo base_url("admin/deletesabeeltakhmeen"); ?>',
      type: 'POST',
      data: { takhmeen_id: tid },
      success: function(res){
        try { const data = JSON.parse(res); if(data.success){
          $('#trow-'+tid).remove();
          $('#edit-'+tid).remove();
          // Update main table row for this user
          const $mainRow = $('tr[data-its-id="'+ userId +'"]');
          if($mainRow.length){
            const currentYear = String($mainRow.data('current-year') || '');
            // If the deleted year is what's shown in the main table, clear displayed values
            if(String(year) === currentYear){
              const $cells = $mainRow.find('td');
              // Indices: 4 Est. Grade, 5 Est. Yearly, 6 Est. Monthly, 7 Res. Grade, 8 Res. Monthly, 9 Res. Yearly, 10 Total
              $cells.eq(4).html('');
              $cells.eq(5).attr('data-sort-value','0').html('');
              $cells.eq(6).attr('data-sort-value','0').html('');
              $cells.eq(7).html('');
              $cells.eq(8).attr('data-sort-value','0').html('');
              $cells.eq(9).attr('data-sort-value','0').html('');
              $cells.eq(10).attr('data-sort-value','0').html('');
              // Clear the current-year marker so future deletes won't match
              $mainRow.data('current-year','');
            }
            // Also update the cached takhmeens on the View button
            const $viewBtn = $mainRow.find('.view-takhmeen');
            if($viewBtn.length){
              let arr = [];
              try { arr = JSON.parse($viewBtn.attr('data-takhmeens') || '[]'); } catch(e){ arr = []; }
              arr = arr.filter(t => String(t.id) !== String(tid));
              $viewBtn.attr('data-takhmeens', JSON.stringify(arr));
              if(arr.length === 0){ $viewBtn.prop('disabled', true); }
            }
          }
        } else { alert('Delete failed'); } }
        catch(e){ alert('Invalid response'); }
      },
      error: function(){ alert('Network error'); }
    });
  });

  // Client-side table sorting (table-only): clickable headers, skip columns with data-no-sort
  (function(){
    const table = document.querySelector('.margintopcontainer table.table.table-bordered.table-striped');
    if(!table) return;
    const thead = table.querySelector('thead');
    const tbody = table.querySelector('tbody');
    if(!thead || !tbody) return;

    // Build a header->column index mapping that respects colspan/rowspan.
    // We construct a grid to place TH cells into their correct column slots, accounting for rowspans.
    const rowsArr = Array.from(thead.querySelectorAll('tr'));
    const rowCount = rowsArr.length;
    // grid[r][c] = cell occupying that row/col
    const grid = [];
    let maxCols = 0;
    for (let r = 0; r < rowCount; r++) grid[r] = [];
    for (let r = 0; r < rowCount; r++) {
      const cells = Array.from(rowsArr[r].children).filter(c => c.tagName === 'TH');
      let col = 0;
      for (const cell of cells) {
        // find next free column in this row
        while (grid[r][col]) col++;
        const colspan = parseInt(cell.getAttribute('colspan')) || 1;
        const rowspan = parseInt(cell.getAttribute('rowspan')) || 1;
        for (let c = 0; c < colspan; c++) {
          for (let rr = 0; rr < rowspan; rr++) {
            const rrIndex = r + rr;
            if (!grid[rrIndex]) grid[rrIndex] = [];
            grid[rrIndex][col + c] = cell;
          }
        }
        col += colspan;
      }
      if (grid[r].length > maxCols) maxCols = grid[r].length;
    }

    const totalCols = maxCols || 0;
    // For each final column, prefer the bottom-most header cell that occupies it (so visual headers map correctly)
    const headerCells = new Array(totalCols).fill(null);
    for (let c = 0; c < totalCols; c++) {
      for (let r = rowCount - 1; r >= 0; r--) {
        if (grid[r] && grid[r][c]) { headerCells[c] = grid[r][c]; break; }
      }
    }

    // Create a unique map of cell -> firstIndex so each TH gets a single handler (use leftmost index)
    const cellIndexMap = new Map();
    for (let i = 0; i < headerCells.length; i++) {
      const c = headerCells[i];
      if (!c) continue;
      if (!cellIndexMap.has(c)) cellIndexMap.set(c, i);
    }
    const headerCellList = Array.from(cellIndexMap.keys());
    headerCellList.forEach(cell => {
      const idx = cellIndexMap.get(cell);
      if (cell.hasAttribute('data-no-sort')) return;
      cell.classList.add('sortable');
      const original = cell.innerHTML.trim();
      cell.innerHTML = '<span class="sort-label">' + original + '</span><span class="sort-indicator" aria-hidden="true"></span>';
      cell.setAttribute('role', 'button'); cell.setAttribute('tabindex', '0');
      cell.addEventListener('click', () => toggleSort(idx, cell));
      cell.addEventListener('keydown', e => { if (['Enter', ' '].includes(e.key)) { e.preventDefault(); toggleSort(idx, cell); } });
    });

    function getCellValue(tr, index){
      const cells = tr.querySelectorAll('td');
      if(!cells[index]) return '';
      return cells[index].getAttribute('data-sort-value') || cells[index].textContent.trim();
    }
    function inferType(val){
      if(val === null || val === undefined) return 'text';
      // Trim
      const s = String(val).trim();
      if(s === '') return 'text';
      // Currency or numbers with commas (e.g. ₹3,23,400 or 1,234.56)
      if(/[\d][\d,]*\.?\d*/.test(s) && /[\d,]/.test(s)) return 'number';
      if(/^\d{4}-\d{2}-\d{2}$/.test(s)) return 'date';
      return 'text';
    }
    function norm(val){
      if(val === null || val === undefined) return '';
      const s = String(val).trim();
      const t = inferType(s);
      if(t==='number'){
        // Strip currency symbols, commas, non-numeric except dot and minus
        const num = s.replace(/[^0-9.\-]/g,'');
        const parsed = parseFloat(num);
        return isNaN(parsed) ? 0 : parsed;
      }
      if(t==='date') return new Date(s).getTime();
      return s.toLowerCase();
    }
    function toggleSort(idx, th){
      const newDir = th.dataset.sortDir === 'asc' ? 'desc' : 'asc';
      // Clear indicators only on bottom header sortable cells
      headerCells.forEach(h => { if(h.classList.contains('sortable')){ h.dataset.sortDir=''; const ind=h.querySelector('.sort-indicator'); if(ind) ind.textContent=''; }});
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
      // Renumber first column for visible rows only
      let i=1; tbody.querySelectorAll('tr').forEach(r => {
        if(r.style.display === 'none') return; const td = r.querySelector('td'); if(td) td.textContent = i++;
      });
    }
  })();
</script>