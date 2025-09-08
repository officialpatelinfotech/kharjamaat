<div class="container margintopcontainer pt-5">
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
  <div class="p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("anjuman/miqaatinvoicepayment") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <h2 class="text-center">Miqaat <span class="text-primary">Payment</span></h2>
  <div class="col-12 text-right">
    <a href="<?php echo base_url("anjuman/newmiqaatpayment"); ?>" class="btn btn-primary">Receive Payment</a>
  </div>
  <div class="col-12 mt-4">
    <div class="card-header">
      <h5 class="mb-0 text-center">Payment List</h5>
    </div>
    <div class="card-body p-0">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>ITS ID</th>
            <th>Member Name</th>
            <th>Payment Date</th>
            <th>Payment Method</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($miqaat_payments)): ?>
            <?php foreach ($miqaat_payments as $payment): ?>
              <tr data-payment-id="<?php echo $payment['payment_id']; ?>">
                <td><?php echo $payment['payment_id'] + 1; ?></td>
                <td><?php echo $payment['ITS_ID']; ?></td>
                <td><?php echo $payment['Full_Name']; ?></td>
                <td><?php echo date("d-m-Y", strtotime($payment['payment_date'])); ?></td>
                <td><?php echo $payment['payment_method']; ?></td>
                <td class="amount-cell">
                  <span class="amount-text"><?php echo $payment['amount']; ?></span>
                  <input type="number" class="form-control amount-input d-none" value="<?php echo $payment['amount']; ?>" min="0" step="0.01" style="max-width:100px;display:inline-block;" />
                </td>
                <td><?php echo $payment['remarks']; ?></td>
                <td>
                  <button type="button" class="btn btn-sm btn-primary edit-amount-btn"><i class="fa fa-pencil-alt"></i></button>
                  <button type="button" class="btn btn-sm btn-success save-amount-btn d-none"><i class="fa fa-check"></i></button>
                  <button type="button" class="btn btn-sm btn-secondary cancel-amount-btn d-none"><i class="fa fa-times"></i></button>
                  <button type="button" class="btn btn-sm btn-danger delete-payment-btn"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center">No payments found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <?php if (!empty($pagination) && $pagination['pages'] > 1): ?>
        <nav aria-label="Page navigation" class="mt-3 pt-3 pb-1 rounded border-0">
          <ul class="pagination justify-content-center">
            <?php $current = $pagination['page']; ?>
            <li class="page-item<?php if ($current <= 1) echo ' disabled'; ?>">
              <a class="page-link" href="?page=<?php echo $current - 1; ?>" tabindex="-1">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
              <li class="page-item<?php if ($i == $current) echo ' active'; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item<?php if ($current >= $pagination['pages']) echo ' disabled'; ?>">
              <a class="page-link" href="?page=<?php echo $current + 1; ?>">Next</a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit-amount-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var tr = btn.closest('tr');
        tr.querySelector('.amount-text').classList.add('d-none');
        tr.querySelector('.amount-input').classList.remove('d-none');
        tr.querySelector('.save-amount-btn').classList.remove('d-none');
        tr.querySelector('.cancel-amount-btn').classList.remove('d-none');
        btn.classList.add('d-none');
      });
    });
    document.querySelectorAll('.cancel-amount-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var tr = btn.closest('tr');
        var input = tr.querySelector('.amount-input');
        var span = tr.querySelector('.amount-text');
        input.value = span.textContent;
        input.classList.add('d-none');
        span.classList.remove('d-none');
        tr.querySelector('.save-amount-btn').classList.add('d-none');
        tr.querySelector('.edit-amount-btn').classList.remove('d-none');
        btn.classList.add('d-none');
      });
    });
    document.querySelectorAll('.save-amount-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var tr = btn.closest('tr');
        var paymentId = tr.getAttribute('data-payment-id');
        var input = tr.querySelector('.amount-input');
        var newAmount = input.value;
        // AJAX call to update amount
        fetch('<?php echo base_url('anjuman/update_miqaat_payment_amount'); ?>', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'payment_id=' + encodeURIComponent(paymentId) + '&amount=' + encodeURIComponent(newAmount)
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              tr.querySelector('.amount-text').textContent = newAmount;
              // Highlight row
              tr.classList.add('table-success');
              setTimeout(function() {
                tr.classList.remove('table-success');
              }, 1200);
            } else {
              alert('Failed to update amount.');
            }
            input.classList.add('d-none');
            tr.querySelector('.amount-text').classList.remove('d-none');
            btn.classList.add('d-none');
            tr.querySelector('.cancel-amount-btn').classList.add('d-none');
            tr.querySelector('.edit-amount-btn').classList.remove('d-none');
          })
          .catch(() => {
            alert('Error updating amount.');
            input.classList.add('d-none');
            tr.querySelector('.amount-text').classList.remove('d-none');
            btn.classList.add('d-none');
            tr.querySelector('.cancel-amount-btn').classList.add('d-none');
            tr.querySelector('.edit-amount-btn').classList.remove('d-none');
          });
      });
    });
  });

  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-payment-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var tr = btn.closest('tr');
        var paymentId = tr.getAttribute('data-payment-id');
        if (confirm('Are you sure you want to delete this payment?')) {
          fetch('<?php echo base_url('anjuman/delete_miqaat_payment'); ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: 'payment_id=' + encodeURIComponent(paymentId)
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                tr.classList.add('table-danger');
                setTimeout(function() {
                  tr.remove();
                }, 600);
              } else {
                alert('Failed to delete payment.');
              }
            })
            .catch(() => {
              alert('Error deleting payment.');
            });
        }
      });
    });
  });

  $(".alert").delay(3000).fadeOut(500);
</script>