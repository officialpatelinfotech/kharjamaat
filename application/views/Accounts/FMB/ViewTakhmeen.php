<div class="container margintopcontainer">
  <h2 class="text-center pt-5">FMB Details</h2>
  <div class="mb-4 p-0">
    <a href="<?php echo base_url("accounts") ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>

  <div class="container pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h6 class="mb-1 text-muted">Total FMB Due</h6>
        <h4 class="text-danger"><?php echo "&#8377;" . number_format($fmb_takhmeen_details['overall']['total_due'], 2); ?></h4>
      </div>
      <div>
        <h6 class="mb-1 text-muted">Total Paid</h6>
        <h4 class="text-success"><?php echo "&#8377;" . number_format($fmb_takhmeen_details['overall']['total_paid'], 2); ?></h4>
      </div>
      <div>
        <h6 class="mb-1 text-muted">Total Amount</h6>
        <h4 class="text-primary"><?php echo "&#8377;" . number_format($fmb_takhmeen_details['overall']['total_amount'], 2); ?></h4>
      </div>
    </div>

    <div class="alert alert-info shadow-sm">
      <h6 class="mb-1">Latest Takhmeen (<?php echo isset($fmb_takhmeen_details['latest']['year']) ? $fmb_takhmeen_details['latest']['year'] : "Not Found"; ?>)</h6>
      <p class="mb-0">
        <b>Total:</b> <?php echo "&#8377;" . number_format(isset($fmb_takhmeen_details['latest']['total_amount']) ? $fmb_takhmeen_details['latest']['total_amount'] : 0, 2); ?> |
        <b>Paid:</b> <?php echo "&#8377;" . number_format(isset($fmb_takhmeen_details['latest']['amount_paid']) ? $fmb_takhmeen_details['latest']['amount_paid'] : 0, 2); ?> |
        <b>Due:</b> <?php echo "&#8377;" . number_format(((isset($fmb_takhmeen_details['latest']['total_amount']) ? $fmb_takhmeen_details['latest']['total_amount'] : 0) - (isset($fmb_takhmeen_details['latest']['amount_paid']) ? $fmb_takhmeen_details['latest']['amount_paid'] : 0)), 2); ?>
      </p>
    </div>

    <div class="card shadow-sm rounded-3 mb-4">
      <div class="card-header bg-light">
        <h5 class="mb-0">FMB Takhmeen List</h5>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th>Year</th>
              <th>Total Amount</th>
              <th>Paid</th>
              <th>Due</th>
              <!-- <th>Invoice</th> -->
            </tr>
          </thead>
          <tbody>
            <?php if (isset($fmb_takhmeen_details['all_takhmeen']) && count($fmb_takhmeen_details['all_takhmeen']) > 0): ?>
              <?php foreach ($fmb_takhmeen_details['all_takhmeen'] as $row): ?>
                <tr <?php echo ($row['id'] == $fmb_takhmeen_details['latest']['id']) ? 'class="table-warning fw-bold"' : '' ?>>
                  <td><?php echo $row['year'] ?></td>
                  <td><?php echo "&#8377;" . number_format($row['total_amount'], 2) ?></td>
                  <td class="text-success"><?php echo "&#8377;" . number_format($row['amount_paid'], 2) ?></td>
                  <td class="text-danger"><?php echo "&#8377;" . number_format($row['total_amount'] - $row['amount_paid'], 2) ?></td>
                  <!-- <td>
                  <a href="<?php echo base_url('invoice/' . $row['id']) ?>" class="btn btn-sm btn-outline-primary">
                    View Invoice
                  </a>
                </td> -->
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4">Takhmeen Not Found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <i class="fa-solid fa-list-ul me-2"></i> General Contributions
      </div>
      <div class="card-body p-0 table-responsive">
        <table class="table table-striped align-middle">
          <thead class="thead-dark">
            <tr>
              <th>#</th>
              <th>Date</th>
              <th>Contribution Type</th>
              <th>Amount (₹)</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($fmb_takhmeen_details["general_contributions"])): ?>
              <?php foreach ($fmb_takhmeen_details["general_contributions"] as $key => $row): ?>
                <tr>
                  <td><?php echo $key + 1; ?></td>
                  <td><?php echo date("d-m-Y", strtotime($row["created_at"])); ?></td>
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

    <div class="card shadow-sm rounded-3 mt-4">
      <div class="card-header bg-light">
        <h5 class="mb-0">Payment History</h5>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th>Date</th>
              <th>Amount</th>
              <th>Method</th>
              <th>Remarks</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($fmb_takhmeen_details['all_payments'])): ?>
              <?php foreach ($fmb_takhmeen_details['all_payments'] as $pay): ?>
                <tr>
                  <td><?php echo date('d-M-Y', strtotime($pay['payment_date'])) ?></td>
                  <td class="text-success"><?php echo number_format($pay['amount'], 2) ?></td>
                  <td><?php echo $pay['payment_method'] ?></td>
                  <td><?php echo $pay['remarks'] ?></td>
                  <td><button class="view-invoice btn btn-sm btn-primary" data-payment-id="<?php echo $pay["id"]; ?>">Payment Receipt</button></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center text-muted">No payments found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
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
  
  $(".view-description").on("click", function(e) {
    e.preventDefault();
    if ($(this).data("description")) {
      $("#modal-view-description").text($(this).data("description"));
    } else {
      $("#modal-view-description").text("No description found!");
    }
  });
</script>