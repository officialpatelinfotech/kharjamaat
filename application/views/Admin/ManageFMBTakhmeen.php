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
  <!-- <div class="text-center mb-3">
    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#adminTakhmeenPayModal">
      <i class="fa fa-credit-card me-1"></i> Pay Now
    </button>
  </div> -->
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
          <div class="d-flex align-items-center justify-content-between">
            <button type="submit" id="add-takhmeen-btn" class="btn btn-primary">Add Takhmeen</button>
            <p id="validate-takhmeen" class="text-secondary m-0"></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Admin Takhmeen Pay Modal -->
<div class="modal fade" id="adminTakhmeenPayModal" tabindex="-1" aria-labelledby="adminTakhmeenPayModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="adminTakhmeenPayModalLabel">Admin: Initiate Takhmeen Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form method="post" action="<?= base_url('payment/ccavenue_takhmeen'); ?>">
        <div class="modal-body">
          <div class="form-group mb-2">
            <label for="admin_its_id">ITS ID (optional)</label>
            <input type="text" id="admin_its_id" name="its_id" class="form-control" placeholder="Enter ITS ID to associate payment" />
          </div>
          <div class="form-group mb-2">
            <label for="admin_pay_amount">Amount (₹)</label>
            <input type="number" step="0.01" min="0.01" id="admin_pay_amount" name="amount" class="form-control" required />
          </div>
          <input type="hidden" name="order_id" value="ADMIN-TAKHMEEN-<?= date('YmdHis'); ?>" />
          <input type="hidden" name="takhmeen_year" value="<?= htmlspecialchars($hijri_year); ?>" />
          <div class="form-text text-muted">Provide ITS ID to reconcile this payment with a member (optional).</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Proceed to Pay</button>
        </div>
      </form>
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
  $(".add-takhmeen").on("click", function(e) {
    $userId = $(this).data("user-id");
    $userName = $(this).data("user-name");
    $("#user-id").val($userId);
    $("#user-name").html($userName);
  });

  $("#takhmeen-year").on("change", function(e) {
    $userId = $("#user-id").val();
    $takhmeen_year = $(this).val();

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

  $(".view-takhmeen").on("click", function(e) {
    resetViewTakhmeenModal();
    $userId = $(this).data("user-id");
    $userName = $(this).data("user-name");
    $takhmeens = $(this).data("takhmeens");

    $("#view-user-name").html($userName);

    if ($takhmeens && $takhmeens.length > 0) {
      let historyHtml = '';
      $takhmeens.forEach((takhmeen, index) => {
        historyHtml += `
          <tr>
            <td>${index + 1}</td>
            <td>${takhmeen.year}</td>
            <td>&#8377;${new Intl.NumberFormat("en-IN", { maximumSignificantDigits: 3 }).format(takhmeen.amount)}</td>
            <td>${takhmeen.remark ? $('<div/>').text(takhmeen.remark).html() : '-'}</td>
          </tr>
        `;
      });
      $("#takhmeen-history-body").html(historyHtml);
    } else {
      $("#takhmeen-history-body").html('<tr><td colspan="4" class="text-center">No Takhmeen history found.</td></tr>');
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

    let $btn = $(this);
    let $row = $btn.closest("tr");
    let userId = $btn.data("user-id");
    let year = $btn.data("year");
    let amount = $btn.data("amount");

    // Replace the amount cell with an input field
    let $amountTd = $row.find("td").eq(2);
    $amountTd.html(`
      <input type="number" class="form-control form-control-sm edit-amount-input" 
        value="${amount}" min="1" style="width:120px; display:inline-block;">
    `);

    // Replace the button cell: add remark input + Save/Cancel
    let $actionTd = $row.find("td").eq(3);
    $actionTd.html(`
      <div class=\"form-group mb-2\">
        <input type=\"text\" class=\"form-control form-control-sm edit-remark-input\" placeholder=\"Edit remark (required)\" />
      </div>
      <div>
        <button class=\"btn btn-sm btn-success save-takhmeen\" 
          data-user-id=\"${userId}\" data-year=\"${year}\"><i class=\"fa-solid fa-check\"></i></button>
        <button class=\"btn btn-sm btn-secondary cancel-edit-takhmeen\"
          data-amount=\"${amount}\" data-user-id=\"${userId}\" data-year=\"${year}\">\n          <i class=\"fa-solid fa-times\"></i>\n        </button>
      </div>
    `);
  });

  // ===== CANCEL BUTTON FLOW =====
  $(document).on("click", ".cancel-edit-takhmeen", function(e) {
    let $btn = $(this);
    let $row = $btn.closest("tr");
    let amount = $btn.data("amount");
    let userId = $btn.data("user-id");
    let year = $btn.data("year");

    $row.find("td").eq(2).html(`&#8377;${new Intl.NumberFormat("en-IN").format(amount)}`);
    $row.find("td").eq(3).html(`
    <button class="btn btn-sm btn-primary edit-single-takhmeen" 
      data-user-id="${userId}" 
      data-year="${year}" 
      data-amount="${amount}">
      <i class="fa-solid fa-pencil"></i>
    </button>
    <button class="btn btn-sm btn-danger delete-single-takhmeen" 
      data-user-id="${userId}" 
      data-year="${year}">
      <i class="fa-solid fa-trash"></i>
    </button>
  `);
  });


  // ===== SAVE BUTTON FLOW =====
  $(document).on("click", ".save-takhmeen", function(e) {
    let $btn = $(this);
    let $row = $btn.closest("tr");
    let userId = $btn.data("user-id");
    let year = $btn.data("year");
    let newAmount = $row.find(".edit-amount-input").val();
    let remark = $row.find('.edit-remark-input').val();
    remark = String(remark || '').trim();
    if (!remark) {
      alert('Please enter edit remark before saving.');
      return;
    }

    $.ajax({
      url: "<?php echo base_url('admin/updatefmbtakhmeen/0'); ?>",
      type: "POST",
      data: {
        user_id: userId,
        year: year,
        fmb_takhmeen_amount: newAmount,
        edit_remark: remark
      },
      success: function(res) {
        let parsed = JSON.parse(res);
        if (parsed.success) {
          alert("Takhmeen updated successfully!");
          $row.find("td").eq(2).html(`&#8377;${new Intl.NumberFormat("en-IN").format(newAmount)}`);
          $row.find("td").eq(3).html(`
            <button class="btn btn-sm btn-primary edit-single-takhmeen" 
              data-user-id="${userId}" 
              data-year="${year}" 
              data-amount="${newAmount}">
              <i class="fa-solid fa-pencil"></i>
            </button>
            <button class="btn btn-sm btn-danger delete-single-takhmeen" 
              data-user-id="${userId}" 
              data-year="${year}">
              <i class="fa-solid fa-trash"></i>
            </button>
          `);
          window.location.reload();
        } else {
          alert("Update failed: " + parsed.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("AJAX Error:", textStatus, errorThrown);
      }
    });
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
</script>