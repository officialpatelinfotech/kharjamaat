<style>
  .hidden {
    display: none;
  }
</style>
<div class="margintopcontainer mx-5">
  <h2 class="heading text-center pt-5 mb-4">Sabeel Takhmeen</h2>
  <div class="mb-4 p-0">
    <a href="<?php echo base_url("anjuman"); ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>
  <div class="row mb-4">
    <div class="col-12 col-md-6">
      <form method="POST" action="<?php echo base_url("anjuman/filteruserinsabeeltakhmeen"); ?>" class="d-flex m-0">
        <input type="text" name="member_name" id="member-name" class="form-control" placeholder="Member name" value="<?php echo isset($member_name) ? $member_name : ""; ?>">
        <a href="<?php echo base_url("anjuman/sabeeltakhmeendashboard"); ?>" class="btn btn-secondary ml-2">Clear</a>
        <button type="submit" class="btn btn-primary ml-2">Submit</button>
      </form>
    </div>
  </div>
  <div>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th colspan="4" class="text-center">Mumineen Info</th>
          <th colspan="4" class="text-center">Establishment Sabeel</th>
          <th colspan="4" class="text-center">Residential Sabeel</th>
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
          <th>Total Due</th>
          <th>Grade</th>
          <th>Takhmeen Per Month</th>
          <th>Takhmeen Yearly</th>
          <th>Total Due</th>
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
              <td class="takhmeen-amount <?php echo isset($user["establishment_due"]) ? ($user["establishment_due"] > 0 ? "text-danger" : "") : ""; ?>"><?php echo isset($user["establishment_due"]) ? round($user["establishment_due"]) : ""; ?></td>
              <td>
                <?php echo $user["residential_grade"]; ?>
                <br>
                <small class="text-muted"><?php echo isset($user["sabeel_year"]) ? "(" . $user["sabeel_year"] . ")" : ""; ?></small>
              </td>
              <td class="takhmeen-amount"><?php echo isset($user["residential_monthly"]) ? round($user["residential_monthly"]) : ""; ?></td>
              <td class="takhmeen-amount"><?php echo isset($user["residential_yearly"]) ? round($user["residential_yearly"]) : ""; ?></td>
              <td class="takhmeen-amount <?php echo isset($user["residential_due"]) ? ($user["residential_due"] > 0 ? "text-danger" : "") : ""; ?>"><?php echo isset($user["residential_due"]) ? round($user["residential_due"]) : ""; ?></td>
              <td>
                <div>
                  <button id="update-payment-btn" class="update-payment-btn btn btn-sm btn-primary" onclick="openUpdatePaymentModal('<?php echo $user['ITS_ID']; ?>', '<?php echo $user['First_Name'] . ' ' . $user['Surname']; ?>', '<?php echo $user['establishment_due']; ?>', '<?php echo $user['residential_due']; ?>')">Update Payment</button>
                </div>
                <div>
                  <button
                    class="payment-history btn btn-sm btn-success mt-3"
                    onclick="openPaymentHistoryModal('<?php echo $user['ITS_ID']; ?>', '<?php echo $user['First_Name'] . ' ' . $user['Surname']; ?>')">
                    Payment History
                  </button>
                </div>
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
<div class="modal fade" id="update-payment-container" tabindex="-1" aria-labelledby="update-payment-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="update-payment-label">Update Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?php echo base_url("anjuman/updatesabeeltakhmeenpayment"); ?>">
          <input type="hidden" name="user_id" id="payment-user-id">

          <p><b>Mumineen Name: <span id="payment-user-name">Member Name</span></b></p>

          <div class="form-group">
            <label for="payment-method">Payment Method</label>
            <select name="payment_method" id="payment-method" class="form-control" required>
              <option value="">-----</option>
              <option value="Cash">Cash</option>
              <option value="Cheque">Cheque</option>
            </select>
          </div>

          <!-- Payment Type -->
          <label for="sabeel-type" class="form-label">Sabeel Type</label>
          <select name="type" id="sabeel-type" class="form-control" required data-e-due="" data-r-due="">
            <option value="">-----</option>
            <option value="establishment">Establishment</option>
            <option value="residential">Residential</option>
          </select>
          <br>

          <!-- Amount -->
          <label for="payment-amount" class="form-label">Amount</label>
          <input type="number" name="amount" id="payment-amount" class="form-control" placeholder="Enter payment amount" min="1" required>
          <br>

          <!-- Payment Date -->
          <label for="payment-date" class="form-label">Payment Date</label>
          <input type="date" name="payment_date" id="payment-date" class="form-control" required>
          <br>

          <!-- Remarks -->
          <label for="payment-remarks" class="form-label">Remarks</label>
          <textarea name="remarks" id="payment-remarks" class="form-control" rows="2" placeholder="Enter remarks"></textarea>
          <br>

          <button type="submit" id="update-payment-btn" class="btn btn-primary">Update Payment</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="payment-history-container" tabindex="-1" aria-labelledby="payment-history-label" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="payment-history-label">Payment History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6><b>Mumineen Name: <span id="history-user-name"></span></b></h6>
        <div id="payment-history-table-container">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Payment Date</th>
                <th>Type</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="payment-history-rows">

            </tbody>
          </table>
        </div>
      </div>
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

  function openUpdatePaymentModal(user_id, name, establishment_due, residential_due) {
    $('#payment-user-id').val(user_id);
    $('#payment-user-name').text(name);
    const today = new Date().toISOString().split("T")[0];
    $("#payment-date").val(today);

    $("#sabeel-type").data("e-due", establishment_due);
    $("#sabeel-type").data("r-due", residential_due);

    $('#update-payment-container').modal('show');
  }

  $("#sabeel-type").on("change", function(e) {
    $eDue = $(this).data("e-due");
    $rDue = $(this).data("r-due");
    $type = $(this).val();
    if ($type == "establishment") {
      $("#payment-amount").prop("max", $eDue);
    } else {
      $("#payment-amount").prop("max", $rDue);
    }
  });

  function openPaymentHistoryModal(user_id, name) {
    $('#history-user-name').text(name);
    $('#payment-history-rows').html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');

    $.ajax({
      url: "<?php echo base_url('anjuman/getPaymentHistory/2'); ?>",
      type: "POST",
      data: {
        user_id: user_id,
      },
      dataType: "json",
      success: function(response) {
        let rows = "";
        if (response.length > 0) {
          response.forEach((payment, index) => {
            rows += `
            <tr>
              <td>${index + 1}</td>
              <td>${payment.payment_date}</td>
              <td>${payment.type}</td>
              <td>${payment.payment_method}</td>
              <td>&#8377;${new Intl.NumberFormat("en-IN").format(payment.amount)}</td>
              <td>${payment.remarks ?? ''}</td>
              <td>
                <button class="view-invoice btn btn-sm btn-primary" data-payment-id="${payment.id}">View Invoice</button>
              </td>
            </tr>
          `;
          });
        } else {
          rows = `<tr><td colspan="6" class="text-center">No payment history found</td></tr>`;
        }
        $('#payment-history-rows').html(rows);
      },
      error: function() {
        $('#payment-history-rows').html('<tr><td colspan="6" class="text-center text-danger">Error loading history</td></tr>');
      }
    });

    $('#payment-history-container').modal('show');
  }

  $(document).on("click", ".view-invoice", function(e) {
    e.preventDefault();
    const paymentId = $(this).data("payment-id");

    $.ajax({
      url: "<?php echo base_url('anjuman/generate_pdf'); ?>",
      type: "POST",
      data: {
        id: paymentId,
        for: 2,
      },
      xhrFields: {
        responseType: 'blob'
      },
      success: function(response) {
        var blob = new Blob([response], {
          type: "application/pdf"
        });
        var url = window.URL.createObjectURL(blob);
        window.open(url, "_blank");
      },
      error: function() {
        alert("Failed to generate invoice PDF");
      }
    });
  });
</script>