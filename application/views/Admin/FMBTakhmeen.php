<style></style>
<div class="container margintopcontainer">
  <h2 class="heading text-center pt-5 mb-4">FMB Module</h2>
  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("admin"); ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
    <div class="col-12 col-md-6 text-right">
      <a href="<?php echo base_url("admin/fmbgeneralcontributionmaster"); ?>" class="btn btn-primary">FMB General Contribution Master</a>
    </div>
  </div>
  <div class="row mb-4 p-0">

    <div class="col-12 col-md-6">
      <form method="POST" action="<?php echo base_url("admin/filteruserinfmbtakhmeen"); ?>" class="d-flex m-0">
        <input type="text" name="member_name" id="member-name" class="form-control" placeholder="Member name" value="<?php echo isset($member_name) ? $member_name : ""; ?>">
        <a href="<?php echo base_url("admin/fmbtakhmeendashboard"); ?>" class="btn btn-secondary ml-2">Clear</a>
        <button type="submit" class="btn btn-primary ml-2">Submit</button>
      </form>
    </div>
  </div>
  <div>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Sr. No.</th>
          <th>Name</th>
          <th>Sector</th>
          <th>Sub-Sector</th>
          <th>Takhmeen</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (isset($all_user_fmb_takhmeen)) {
          foreach ($all_user_fmb_takhmeen as $key => $user) {
        ?>
            <tr>
              <td><?php echo $key + 1; ?></td>
              <td><?php echo $user["First_Name"] . " " . $user["Surname"]; ?></td>
              <td><?php echo $user["Sector"]; ?></td>
              <td><?php echo $user["Sub_Sector"]; ?></td>
              <td>
                <?php if (isset($user["total_fmb_takhmeen"]) && $user["total_fmb_takhmeen"] != 0):
                  $fy_start = explode("|", $user["takhmeen_year"])[0];
                  $fy_end = explode("|", $user["takhmeen_year"])[1];
                ?>
                  <p class="takhmeen-amount m-0 p-0"><?php echo $user["total_fmb_takhmeen"]; ?></p>
                  <p class="financial-year pt-2 m-0">
                    <small class="text-secondary">(FY - <?php echo $fy_start . " / " . $fy_end; ?>)</small>
                  </p>
                <?php
                else: ?>
                  Takhmeen Not Found
                <?php
                endif; ?>
              </td>
              <td>
                <button id="add-takhmeen" class="add-takhmeen btn btn-success" data-toggle="modal" data-target="#add-takhmeen-container" data-user-id="<?php echo $user["ITS_ID"]; ?>" data-user-name="<?php echo $user["First_Name"] . " " . $user["Surname"]; ?>">Add</button>
                <button id="edit-takhmeen" class="edit-takhmeen btn btn-primary" onclick="openEditTakhmeenModal('<?php echo $user['takhmeen_id']; ?>', '<?php echo $user['ITS_ID']; ?>', '<?php echo $user['First_Name'] . ' ' . $user['Surname']; ?>', '<?php echo $user['takhmeen_year'] ?>', '<?php echo isset($user['total_fmb_takhmeen']) ? $user['total_fmb_takhmeen'] : ''; ?>')">Edit</button>
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
          <input type="hidden" name="user_id" id="user-id">
          <p><b>Mumineen Name: <span id="user-name">Member Name</span></b></p>
          <label for="takhmeen-year" class="form-label">Takhmeen Year</label>
          <select name="fmb_takhmeen_year" id="takhmeen-year" class="form-control" required>
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
          <label for="takhmeen-amount" class="form-label">Takhmeen Amount</label>
          <input type="number" id="takhmeen-amount" name="fmb_takhmeen_amount" class="form-control" value="" placeholder="Enter Takhmeen Amount" min="1" required>
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
        <h5 class="modal-title" id="updateTakhmeenLabel">Update FMB Takhmeen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="update-takhmeen-form" method="post" action="<?php echo base_url('Admin/updatefmbtakhmeen/1') ?>">
        <div class="modal-body">
          <input type="hidden" name="takhmeen_id" id="edit-takhmeen-id">
          <input type="hidden" name="user_id" id="edit-user-id">
          <div class="form-group">
            <label>Member</label>
            <p id="edit-user-name" class="font-weight-bold mb-0"></p>
          </div>
          <div class="form-group">
            <label>Financial Year</label>
            <input type="text" name="year" id="edit-takhmeen-year" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label for="edit-takhmeen-amount">Takhmeen Amount</label>
            <input type="number" name="fmb_takhmeen_amount" id="edit-takhmeen-amount" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Takhmeen</button>
        </div>
      </form>
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
                <button type="button" id="save-takhmeen-btn" class="btn btn-primary">Save</button>
              </div>
            `);
            $(document).off("click", "#save-takhmeen-btn").on("click", "#save-takhmeen-btn", function() {
              $newAmount = $("#edit-takhmeen-amount").val();
              $.ajax({
                url: "<?php echo base_url("admin/updatefmbtakhmeen/0"); ?>",
                type: "POST",
                data: {
                  "user_id": $userId,
                  "year": $takhmeen_year,
                  "fmb_takhmeen_amount": $newAmount
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

  // when admin clicks Edit button
  function openEditTakhmeenModal(takhmeen_id, user_id, name, year, amount) {
    $('#edit-takhmeen-id').val(takhmeen_id);
    $('#edit-user-id').val(user_id);
    $('#edit-user-name').text(name);
    $('#edit-takhmeen-year').val(year);
    $('#edit-takhmeen-amount').val(amount);

    $('#update-takhmeen-modal').modal('show');
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
</script>