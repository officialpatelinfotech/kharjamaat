<style>
  .view-due:hover {
    color: blue;
    cursor: pointer;
    text-decoration: underline;
  }
</style>
<div class="container margintopcontainer">
  <h2 class="heading text-center pt-5 mb-4">FMB Takhmeen</h2>
  <div class="mb-4 p-0">
    <a href="<?php echo base_url("anjuman") ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
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
          <th>Total Paid</th>
          <th>Total Due</th>
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
                <?php if ($user["latest_total_takhmeen"] != 0):
                  $fy_start = explode("|", $user["latest_takhmeen_year"])[0];
                  $fy_end = explode("|", $user["latest_takhmeen_year"])[1];
                ?>
                  <p class="takhmeen-amount m-0 p-0"><?php echo $user["latest_total_takhmeen"]; ?></p>
                  <p class="financial-year pt-2 m-0">
                    <small class="text-secondary">(FY - <?php echo $fy_start . " / " . $fy_end; ?>)</small>
                  </p>
                <?php
                else: ?>
                  Takhmeen Not Found
                <?php
                endif; ?>
              </td>
              <td class="takhmeen-amount"><?php echo $user["overall_total_paid"]; ?></td>
              <td class="view-due takhmeen-amount" data-toggle="modal" data-target="#due-overview-modal" data-user-name="<?php echo $user["First_Name"] . " " . $user["Surname"]; ?>" data-all-takhmeen='<?php echo json_encode($user["all_takhmeen"]); ?>'><?php echo $user["overall_due"]; ?></td>
              <td>
                <?php if ($user["overall_due"] > 0): ?>
                  <button id="pay-takhmeen-btn" class="pay-takhmeen-btn btn btn-sm btn-success" data-toggle="modal" data-target="#pay-takhmeen-container" data-user-id="<?php echo $user["ITS_ID"]; ?>" data-user-name="<?php echo $user["First_Name"] . " " . $user["Surname"]; ?>" data-overall-due="<?php echo $user["overall_due"]; ?>">Update Payment</button>
                <?php endif; ?>
                <?php if (count($user["all_takhmeen"])): ?>
                  <button id="payment-history" class="payment-history mt-2 btn btn-sm btn-primary" onclick="openPaymentHistoryModal('<?php echo $user['ITS_ID']; ?>', '<?php echo $user['First_Name'] . ' ' . $user['Surname']; ?>')">Payment History</button>
                <?php endif; ?>
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
<!-- Modal -->
<div class="modal fade" id="due-overview-modal" role="dialog" tabindex="-1" aria-labelledby="due-overview" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="due-overview">Pending Takhmeen Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 id="member-name"></h6>
        <table class="table table-sm table-striped">
          <thead>
            <tr>
              <th>Year</th>
              <th>Total Takhmeen</th>
              <th>Paid</th>
              <th>Due</th>
            </tr>
          </thead>
          <tbody id="due-details"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="pay-takhmeen-container" tabindex="-1" aria-labelledby="pay-takhmeen-container-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pay-takhmeen-container-label">Update FMB Takhmeen Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updatePaymentForm" method="post" action="<?= base_url('anjuman/update_fmb_payment'); ?>">
          <input type="hidden" name="user_id" id="modal_user_id">
          <div class="form-group">
            <label><strong>Mumineen Name:</strong> <span id="modal_user_name" class="mb-1"></span></label>
          </div>
          <div class="form-group">
            <label for="payment-method">Payment Method</label>
            <select name="payment_method" id="payment-method" class="form-control" required>
              <option value="">-----</option>
              <option value="Cash">Cash</option>
              <option value="Cheque">Cheque</option>
            </select>
          </div>
          <div class="form-group">
            <label for="modal_amount">Payment Amount</label>
            <input type="number" class="form-control" name="amount" id="modal_amount" placeholder="Enter payment amount" required min="1">
          </div>
          <div class="form-group">
            <label for="modal-payment-date">Payment Date</label>
            <input type="date" class="form-control" name="payment_date" id="modal-payment-date" required>
          </div>
          <div class="form-group">
            <label for="modal_payment_remark">Payment Remark</label>
            <input type="text" class="form-control" name="remarks" id="modal_payment_remark">
          </div>
          <button type="submit" class="btn btn-primary">Save changes</button>
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

  $(document).ready(function() {
    $(".view-due").on("click", function() {
      const userName = $(this).data("user-name");
      const allTakhmeen = $(this).data("all-takhmeen");

      $("#member-name").text("Mumineen: " + userName);
      $("#due-details").empty();
      allTakhmeen.forEach(function(takhmeen) {
        $("#due-details").append(`
        <tr>
          <td>${takhmeen.year}</td>
          <td>₹${takhmeen.total_amount.toLocaleString()}</td>
          <td>₹${takhmeen.amount_paid.toLocaleString()}</td>
          <td class="text-danger">₹${takhmeen.due.toLocaleString()}</td>
        </tr>
      `);
      });

      $("#due-overview-modal").modal("show");
    });
  });

  $(document).on("click", ".pay-takhmeen-btn", function() {
    let userId = $(this).data("user-id");
    let userName = $(this).data("user-name");
    let today = new Date().toISOString().split("T")[0];

    $("#modal_user_id").val(userId);
    $("#modal_user_name").text(userName);
    $("#modal-payment-date").val(today);
    $("#modal_amount").attr("max", $(this).data("overall-due"));

    $("#pay-takhmeen-container").modal("show");
  });

  function openPaymentHistoryModal(user_id, name) {
    $('#history-user-name').text(name);
    $('#payment-history-rows').html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');

    $.ajax({
      url: "<?php echo base_url('anjuman/getPaymentHistory/1'); ?>",
      type: "POST",
      data: {
        user_id: user_id
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
        for: 1,
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