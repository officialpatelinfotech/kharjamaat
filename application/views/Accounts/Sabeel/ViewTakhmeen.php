<div class="container margintopcontainer">
  <h2 class="text-center pt-5">Sabeel Takhmeen Details</h2>
  <div class="mb-4 p-0">
    <a href="<?php echo base_url("accounts") ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>

  <div class="container pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h6 class="mb-1 text-muted">Total Sabeel Due</h6>
        <h4 class="text-danger">
          <?php echo "&#8377;" . number_format($sabeel_takhmeen_details['overall']['total_due'], 2); ?>
        </h4>
      </div>
      <div>
        <h6 class="mb-1 text-muted">Total Paid</h6>
        <h4 class="text-success">
          <?php echo "&#8377;" . number_format($sabeel_takhmeen_details['overall']['total_paid'], 2); ?>
        </h4>
      </div>
      <div>
        <h6 class="mb-1 text-muted">Total Amount</h6>
        <h4 class="text-primary">
          <?php echo "&#8377;" . number_format($sabeel_takhmeen_details['overall']['total_amount'], 2); ?>
        </h4>
      </div>
    </div>

    <?php if (!empty($sabeel_takhmeen_details['latest'])): ?>
      <div class="alert alert-info shadow-sm">
        <h6 class="mb-1">Latest Takhmeen (<?php echo $sabeel_takhmeen_details['latest']['year']; ?>)</h6>
        <p class="mb-0">
          <b>Establishment:</b> <?php echo "&#8377;" . number_format($sabeel_takhmeen_details['latest']['establishment_amount'], 2); ?> |
          <b>Residential:</b> <?php echo "&#8377;" . number_format($sabeel_takhmeen_details['latest']['residential_amount'] / 12, 2); ?>(per month) |
          <b>Total:</b> <?php echo "&#8377;" . number_format($sabeel_takhmeen_details['latest']['total_amount'], 2); ?> |
          <b>Paid:</b> <?php echo "&#8377;" . number_format($sabeel_takhmeen_details['latest']['amount_paid'], 2); ?> |
          <b>Due:</b> <?php echo "&#8377;" . number_format(($sabeel_takhmeen_details['latest']['total_amount'] - $sabeel_takhmeen_details['latest']['amount_paid']), 2); ?>
        </p>
      </div>
    <?php endif; ?>

    <div class="card shadow-sm rounded-3 mb-4">
      <div class="card-header bg-light">
        <h5 class="mb-0">Establishment Sabeel Takhmeen List</h5>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
          <tr class="thead-dark">
            <th>Year</th>
            <th>Grade</th>
            <th>Total</th>
            <th>Paid</th>
            <th>Due</th>
          </tr>
          <?php foreach ($sabeel_takhmeen_details["e_takhmeen"] as $row): ?>
            <tr>
              <td><?php echo $row['year']; ?></td>
              <td><?php echo $row['grade']; ?></td>
              <td><?php echo "&#8377;" . number_format($row['total']); ?></td>
              <td><?php echo "&#8377;" . number_format($row['paid']); ?></td>
              <td><?php echo "&#8377;" . number_format($row['due']); ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
    <div class="card shadow-sm rounded-3 mb-4">
      <div class="card-header bg-light">
        <h5 class="mb-0">Residential Sabeel Takhmeen List</h5>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
          <tr class="thead-dark">
            <th>Year</th>
            <th>Grade</th>
            <th>Total</th>
            <th>Paid</th>
            <th>Due</th>
          </tr>
          <?php foreach ($sabeel_takhmeen_details["r_takhmeen"] as $row): ?>
            <tr>
              <td><?php echo $row['year']; ?></td>
              <td><?php echo $row['grade']; ?></td>
              <td><?php echo "&#8377;" . number_format($row['total']); ?></td>
              <td><?php echo "&#8377;" . number_format($row['paid']); ?></td>
              <td><?php echo "&#8377;" . number_format($row['due']); ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>

    <div class="card shadow-sm rounded-3 my-4">
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
              <th>Type</th>
              <th>Remarks</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($sabeel_takhmeen_details['all_payments'])): ?>
              <?php foreach ($sabeel_takhmeen_details['all_payments'] as $pay): ?>
                <tr>
                  <td><?php echo date('d-M-Y', strtotime($pay['payment_date'])) ?></td>
                  <td class="text-success"><?php echo number_format($pay['amount'], 2) ?></td>
                  <td><?php echo $pay['payment_method'] ?></td>
                  <td><?php echo ucfirst($pay['type']); ?></td>
                  <td><?php echo $pay['remarks'] ?></td>
                  <td>
                    <button class="view-invoice btn btn-sm btn-primary" data-payment-id="<?php echo $pay['id'] ?>">View Invoice</button>
                  </td>
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