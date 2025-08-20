<style>
  .hidden {
    display: none;
  }
</style>
<div class="margintopcontainer mx-5">
  <h2 class="heading text-center pt-5 mb-4">Sabeel Takhmeen</h2>
  <div class="mb-4 p-0">
    <a href="<?php echo base_url("admin"); ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>
  <div class="row mb-4">
    <div class="col-12 col-md-6">
      <form method="POST" action="<?php echo base_url("admin/filteruserinsabeeltakhmeen"); ?>" class="d-flex m-0">
        <input type="text" name="member_name" id="member-name" class="form-control" placeholder="Member name" value="<?php echo isset($member_name) ? $member_name : ""; ?>">
        <a href="<?php echo base_url("admin/sabeeltakhmeendashboard"); ?>" class="btn btn-secondary ml-2">Clear</a>
        <button type="submit" class="btn btn-primary ml-2">Submit</button>
      </form>
    </div>
    <div class="col-12 col-md-6 text-right">
      <a href="<?php echo base_url("admin/sabeelgrade"); ?>" class="btn btn-info">Sabeel Grade</a>
    </div>
  </div>
  <div>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th colspan="4" class="text-center">Mumineen Info</th>
          <th colspan="3" class="text-center">Establishment Sabeel</th>
          <th colspan="3" class="text-center">Residential Sabeel</th>
          <th rowspan="2" class="text-center">Action</th>
        </tr>
        <tr>
          <th>Sr. No.</th>
          <th>ITS ID</th>
          <th>Name</th>
          <th>Sector</th>
          <th>Grade</th>
          <th>Takhmeen Yearly</th>
          <th>Takhmeen Per Month</th>
          <th>Grade</th>
          <th>Takhmeen Per Month</th>
          <th>Takhmeen Yearly</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (isset($all_user_sabeel_takhmeen)) {
          foreach ($all_user_sabeel_takhmeen as $key => $user) {
        ?>
            <tr>
              <td><?php echo $key + 1; ?></td>
              <td><?php echo $user["ITS_ID"]; ?></td>
              <td><?php echo $user["First_Name"] . " " . $user["Surname"]; ?></td>
              <td><?php echo $user["Sector"] . " - " . $user["Sub_Sector"]; ?></td>
              <td>
                <?php echo $user["establishment_grade"]; ?>
                <br>
                <small class="text-muted"><?php echo isset($user["sabeel_year"]) ? "(" . $user["sabeel_year"] . ")" : ""; ?></small>
              </td>
              <td class="takhmeen-amount"><?php echo isset($user["establishment_yearly"]) ? round($user["establishment_yearly"]) : ""; ?></td>
              <td class="takhmeen-amount"><?php echo isset($user["establishment_monthly"]) ? round($user["establishment_monthly"]) : ""; ?></td>
              <td>
                <?php echo $user["residential_grade"]; ?>
                <br>
                <small class="text-muted"><?php echo isset($user["sabeel_year"]) ? "(" . $user["sabeel_year"] . ")" : ""; ?></small>
              </td>
              <td class="takhmeen-amount"><?php echo isset($user["residential_monthly"]) ? round($user["residential_monthly"]) : ""; ?></td>
              <td class="takhmeen-amount"><?php echo isset($user["residential_yearly"]) ? round($user["residential_yearly"]) : ""; ?></td>
              <td>
                <button id="add-takhmeen" class="add-takhmeen btn btn-success" data-toggle="modal" data-target="#add-takhmeen-container" data-user-id="<?php echo $user["ITS_ID"]; ?>" data-user-name="<?php echo $user["First_Name"] . " " . $user["Surname"]; ?>">Add</button>
                <button id="edit-takhmeen" class="edit-takhmeen btn btn-primary" onclick="openEditTakhmeenModal('<?php echo $user['ITS_ID']; ?>', '<?php echo $user['First_Name'] . ' ' . $user['Surname']; ?>', '<?php echo isset($user['sabeel_year']) ? $user['sabeel_year'] : '' ?>', '<?php echo isset($user['total_sabeel_takhmeen']) ? $user['total_sabeel_takhmeen'] : '' ?>', '<?php echo isset($user['sabeel_id']) ? $user['sabeel_id'] : '' ?>')">Edit</button>
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
        <h5 class="modal-title" id="add-takhmeen-container-label">Add sabeel Takhmeen Amount</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?php echo base_url("admin/addsabeeltakhmeenamount"); ?>">
          <input type="hidden" name="user_id" id="user-id">
          <p><b>Mumineen Name: <span id="user-name">Member Name</span></b></p>
          <label for="takhmeen-year" class="form-label">Takhmeen Year</label>
          <select name="sabeel_takhmeen_year" id="takhmeen-year" class="form-control" required>
            <option value="">-----</option>
            <option value="1446-09|1447-09">1446-09 / 1447-09</option>
            <option value="1447-09|1448-09">1447-09 / 1448-09</option>
            <option value="1448-09|1449-09">1448-09 / 1449-09</option>
            <option value="1449-09|1450-09">1449-09 / 1450-09</option>
            <option value="1450-09|1451-09">1450-09 / 1451-09</option>
            <option value="1451-09|1452-09">1451-09 / 1452-09</option>
            <option value="1452-09|1453-09">1452-09 / 1453-09</option>
            <option value="1453-09|1454-09">1453-09 / 1454-09</option>
            <option value="1454-09|1455-09">1454-09 / 1455-09</option>
            <option value="1455-09|1456-09">1455-09 / 1456-09</option>
            <option value="1456-09|1457-09">1456-09 / 1457-09</option>
            <option value="1457-09|1458-09">1457-09 / 1458-09</option>
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
<div class="modal fade" id="update-takhmeen-modal" tabindex="-1" role="dialog" aria-labelledby="updateTakhmeenLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="updateTakhmeenLabel">Update sabeel Takhmeen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="update-takhmeen-form" method="post" action="<?php echo base_url('Admin/updatesabeeltakhmeen/1') ?>">
        <div class="modal-body">
          <input type="hidden" name="user_id" id="edit-user-id">
          <input type="hidden" name="takhmeen_id" id="edit-takhmeen-id">
          <div class="form-group">
            <p>Mumineen Name:</p>
            <p id="edit-user-name" class="font-weight-bold mb-0"></p>
          </div>
          <div class="form-group">
            <label for="edit-takhmeen-year">Takhmeen Year</label>
            <input type="text" name="year" id="edit-takhmeen-year" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label for="edit-establishment-grade">Establishment Grade</label>
            <select name="establishment_grade" id="edit-establishment-grade" class="form-control">
              <option value="">-----</option>
            </select>
            <p class="hidden takhmeen-amount pt-2 m-0" id="edit-e-amount"></p>
          </div>

          <div class="form-group">
            <label for="edit-residential-grade">Residential Grade</label>
            <select name="residential_grade" id="edit-residential-grade" class="form-control">
              <option value="">-----</option>
            </select>
            <p class="hidden takhmeen-amount pt-2 m-0" id="edit-r-amount"></p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" id="update-takhmeen-btn" class="btn btn-primary">Update Takhmeen</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
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

  $(".add-takhmeen").on("click", function(e) {
    $userId = $(this).data("user-id");
    $userName = $(this).data("user-name");
    $("#user-id").val($userId);
    $("#user-name").html($userName);
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
    $eAmountElem.html(`<span class="text-muted">Total takhmeen for the year ${amount}</span>`);
    $eAmountElem.removeClass("hidden");
  });
  $("#residential-grade").on("change", function(e) {
    const selected = $(this).find(":selected");
    const amount = selected.data("grade-amount");
    $rAmountElem = $("#r-amount");
    $rAmountElem.html(`<span class="text-muted">Total takhmeen per month ${amount}</span>`);
    $rAmountElem.removeClass("hidden");
  });

  function openEditTakhmeenModal(user_id, name, year, amount, sabeel_id) {
    $('#edit-user-id').val(user_id);
    $('#edit-user-name').text(name);
    $('#edit-takhmeen-year').val(year);
    $('#edit-takhmeen-id').val(sabeel_id);
    $.ajax({
      url: "<?php echo base_url('admin/getUserTakhmeenDetails'); ?>",
      type: "POST",
      data: {
        user_id,
        year
      },
      success: function(res) {
        if (res) {
          const data = JSON.parse(res);

          let eOptions = `<option value="">Select Grade</option>`;
          let rOptions = `<option value="">Select Grade</option>`;

          data.grades.forEach(grade => {
            if (grade.type == "Establishment") {
              eOptions += `<option value="${grade.id}" data-grade-amount="${grade.amount}" ${data.selected.establishment_grade == grade.id ? "selected" : ""}>${grade.grade}</option>`;
            }
            if (grade.type == "Residential") {
              rOptions += `<option value="${grade.id}" data-grade-amount="${grade.amount}" ${data.selected.residential_grade == grade.id ? "selected" : ""}>${grade.grade}</option>`;
            }
          });

          $("#edit-establishment-grade").html(eOptions);
          $("#edit-residential-grade").html(rOptions);

          if (data.selected.establishment_amount) {
            $("#edit-e-amount").html(`<span class="text-muted">Yearly: ${data.selected.establishment_amount}</span>`).removeClass("hidden");
          }
          if (data.selected.residential_amount) {
            $("#edit-r-amount").html(`<span class="text-muted">Monthly: ${data.selected.residential_amount}</span>`).removeClass("hidden");
          }

          $(document).on("change", "#edit-establishment-grade", function() {
            const amt = $(this).find(":selected").data("grade-amount");
            $("#edit-e-amount").html(`<span class="text-muted">Total takhmeen for the year ${amt}</span>`).removeClass("hidden");
          });

          $(document).on("change", "#edit-residential-grade", function() {
            const amt = $(this).find(":selected").data("grade-amount");
            $("#edit-r-amount").html(`<span class="text-muted">Total takhmeen per month ${amt}</span>`).removeClass("hidden");
          });

          $('#update-takhmeen-modal').modal('show');
        }
      }
    });
  }

  $("#update-takhmeen-btn").on("click", function(e) {
    e.preventDefault();
    $result = confirm("Are all the details correct?");
    if ($result) {
      $("#update-takhmeen-form").off("submit").submit();
    }
  })
</script>