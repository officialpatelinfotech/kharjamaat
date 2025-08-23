<style>
  .hidden {
    display: hidden;
  }
</style>

<div class="container margintopcontainer">
  <h2 class="heading text-center pt-5 mb-4">FMB <?php echo $type == 1 ? "Thaali" : "Niyaz"; ?> General Contribution</h2>
  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("anjuman/fmbmodule") ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
    <div class="col-12 col-md-6 mb-3 text-right">
      <button class="btn btn-success" data-toggle="modal" data-target="#generateInvoiceModal">
        <i class="fa-solid fa-file-invoice-dollar me-2"></i> Generate Invoice
      </button>
    </div>
  </div>
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <i class="fa-solid fa-list-ul me-2"></i> Generated Invoices
    </div>
    <div class="card-body p-0 table-responsive">
      <table class="table table-striped align-middle">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>ITS ID</th>
            <th>Member Name</th>
            <th>Contribution Type</th>
            <th>Amount (₹)</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($all_user_fmbgc)): ?>
            <?php foreach ($all_user_fmbgc as $key => $row): ?>
              <tr>
                <td><?php echo $key + 1; ?></td>
                <td><?php echo date("d-m-Y", strtotime($row["created_at"])); ?></td>
                <td><?php echo $row["ITS_ID"]; ?></td>
                <td><?php echo $row["First_Name"] . " " . $row["Surname"]; ?></td>
                <td><?php echo $row["contri_type"]; ?></td>
                <td><?php echo $row["amount"]; ?></td>
                <?php
                if ($row["payment_status"] == 1) {
                ?>
                  <td><span class="badge bg-success text-white">Paid</span></td>
                <?php
                } else {
                ?>
                  <td><span class="badge bg-danger text-white">Pending</span></td>
                <?php
                }
                ?>
                <td>
                  <!-- <button class="update-payment-btn btn btn-sm btn-success" data-fmbgc-id="<?php echo $row["id"]; ?>" data-user-id="<?php echo $row["user_id"]; ?>" data-member-name="<?php echo $row["First_Name"] . " " . $row["Surname"]; ?>" data-amount="<?php echo $row["amount"]; ?>" data-toggle="modal" data-target="#update-payment-modal">Update Payment</button> -->
                  <?php if ($row["payment_status"] == 0): ?>
                    <a href="<?php echo base_url("anjuman/updatefmbgcpayment1/" . $row["id"] . "/$type") ?>" class="update-payment-btn btn btn-sm btn-success">Mark as Paid</a>
                  <?php else: ?>
                    <a href="<?php echo base_url("anjuman/updatefmbgcpayment0/" . $row["id"]) . "/$type" ?>" class="update-payment-btn btn btn-sm btn-outline-danger">Mark as Unpaid</a>
                  <?php endif; ?>
                  <button class="view-description btn btn-sm btn-outline-primary" data-description="<?php echo $row["description"]; ?>" data-toggle="modal" data-target="#description-modal">
                    <i class="fa-solid fa-eye"></i> View Description
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="modal fade" id="generateInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="fa-solid fa-plus-circle me-2"></i> Generate New Invoice</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="save-fmbgc-form" method="POST" action="<?php echo base_url("anjuman/addfmbgc"); ?>">
            <div class="mb-3">
              <label for="contri-year">Contribution Year</label>
              <select name="contri_year" id="contri-year" class="form-control" required>
                <option value="">Select Contribution Year</option>
                <option value="1446-1447">1446-1447</option>
                <option value="1447-1448">1447-1448</option>
                <option value="1448-1449">1448-1449</option>
                <option value="1449-1450">1449-1450</option>
                <option value="1450-1451">1450-1451</option>
                <option value="1451-1452">1451-1452</option>
                <option value="1452-1453">1452-1453</option>
                <option value="1453-1454">1453-1454</option>
                <option value="1454-1455">1454-1455</option>
                <option value="1455-1456">1455-1456</option>
                <option value="1456-1457">1456-1457</option>
                <option value="1457-1458">1457-1458</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="user-id">Member ITS ID</label>
              <input type="number" name="user_id" id="user-id" class="form-control" min="1" placeholder="Enter Member ITS ID" required>
              <p id="member-name" class="mt-2 text-muted hidden"></p>
            </div>
            <div class="mb-3">
              <label for="contri-type">Contribution Type</label>
              <input type="hidden" name="fmb_type" value="<?php echo $type == 1 ? "Thaali" : "Niyaz"; ?>" required>
              <select name="contri_type" id="contri-type" class="form-control" required>
                <option value="">Select Type</option>
                <?php if (isset($contri_type_gc)): ?>
                  <?php foreach ($contri_type_gc as $key => $value): ?>
                    <option value="<?php echo $value["name"]; ?>"><?php echo $value["name"]; ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="amount" class="form-label">Amount (₹)</label>
              <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter amount" min="1" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea name="description" id="description" class="form-control" rows="2" placeholder="Optional"></textarea>
            </div>
            <button type="submit" id="save-fmbgc-btn" class="btn btn-success w-100">
              <i class="fa-solid fa-circle-check me-2"></i> Generate Invoice
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="update-payment-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="fa-solid fa-plus-circle me-2"></i> Update Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="update-fmbgc-payment-form" method="POST" action="<?php echo base_url("anjuman/updatefmbgcpayment"); ?>">
            <div class="mb-3">
              <p>Member Name: <span id="up-member-name"></span></p>
              <p class="mb-3">Due Amount: <span id="due-amount"></span></p>
              <input type="hidden" name="user_id" id="up-user-id" value="">
              <input type="hidden" name="fmbgc_id" id="up-fmbgc-id" value="">
            </div>
            <div class="mb-3">
              <label for="payment-amount" class="form-label">Payment Amount (₹)</label>
              <input type="number" name="amount" id="payment-amount" class="form-control" placeholder="Enter amount" min="1" required>
            </div>
            <button type="submit" id="update-fmbgc-payment-btn" class="btn btn-success w-100">
              <i class="fa-solid fa-circle-check me-2"></i> Submit
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="description-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="fa-solid fa-plus-circle me-2"></i> Description</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="modal-view-description" class="text-dark"></p>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $("#user-id").on("input", function() {
      $userId = $(this).val();

      if (!$userId) {
        $("#member-name").text("").hide();
        return;
      }

      $.ajax({
        url: "<?php echo base_url("anjuman/getmemberdetails"); ?>",
        type: "POST",
        data: {
          "user_id": $userId,
        },
        dataType: "json",
        success: function(res) {
          if (res.success && res.member_details) {
            const memberDetails = res.member_details;
            if (memberDetails.First_Name || memberDetails.Surname) {
              $("#member-name")
                .text("Member Name: " + (memberDetails.First_Name ?? "") + " " + (memberDetails.Surname ?? ""))
                .show();
            } else {
              $("#member-name").text("").hide();
            }
          } else {
            $("#member-name").text("").hide();
          }
        },
        error: function() {
          $("#member-name").text("").hide();
        }
      });
    });

    $("#save-fmbgc-btn").on("click", function(e) {
      e.preventDefault();
      $contriYear = $("#contri-year").val();
      $userId = $("#user-id").val();
      $contriType = $("#contri-type").val();
      $amount = $("#amount").val();

      if ($("#member-name").text().trim().length === 0) {
        alert("Please enter correct ITS ID.");
        return;
      }

      if (!$amount || parseFloat($amount) === 0) {
        alert("Amount can't be zero.");
        return;
      }

      $.ajax({
        url: "<?php echo base_url("anjuman/validatefmbgc"); ?>",
        type: "POST",
        data: {
          "contri_year": $contriYear,
          "user_id": $userId,
          "fmb_type": "<?php echo $type == 1 ? "Thaali" : "Niyaz"; ?>",
          "contri_type": $contriType,
        },
        dataType: "json",
        success: function(res) {
          if (res.success) {
            $result = confirm(
              "FMB <?php echo $type == 1 ? "Thaali" : "Niyaz"; ?> General Contribution found for type " + $contriType + " for member. Are you sure you want to move forward?"
            );
            if ($result) {
              $("#save-fmbgc-form").off("submit").submit();
            }
          } else {
            $("#save-fmbgc-form").off("submit").submit();
          }
        },
        error: function(xhr, status, error) {
          console.log(error);
        }
      });
    });

    // $(".update-payment-btn").on("click", function(e) {
    //   e.preventDefault();
    //   $userId = $(this).data("user-id");
    //   $fmbgcId = $(this).data("fmbgc-id");
    //   $memberName = $(this).data("member-name");
    //   $amount = $(this).data("amount");
    //   $("#up-user-id").val($userId);
    //   $("#up-fmbgc-id").val($fmbgcId);
    //   $("#up-member-name").text($memberName);
    //   $("#due-amount").text($amount);
    //   $("#payment-amount").attr("max", $amount);
    // });

    $(".view-description").on("click", function(e) {
      e.preventDefault();
      if ($(this).data("description")) {
        $("#modal-view-description").text($(this).data("description"));
      } else {
        $("#modal-view-description").text("No description found!");
      }
    });
  });
</script>