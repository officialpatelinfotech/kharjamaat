<style>
  #payment-form-container {
    max-width: 600px;
    margin: 0 auto;
  }
</style>
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
      <a href="<?php echo base_url("anjuman/miqaatpayment") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <h3 class="mb-4 text-center mt-3">Payment Details</h3>
  <div class="col-12 border shadow-sm rounded p-3 mt-1 mt-md-5" id="payment-form-container">
    <form method="post" action="<?php echo base_url('anjuman/addmiqaatpayment'); ?>" id="newMiqaatPaymentForm">
      <div class="form-group mb-3">
        <label for="payment-date">Payment Date</label>
        <input type="date" class="form-control" id="payment-date" name="payment_date" value="<?php echo date('Y-m-d'); ?>" required>
      </div>
      <div class="form-group">
        <label for="payment-method">Payment Method</label>
        <select class="form-control" id="payment-method" name="payment_method" required>
          <option value="">Select Payment Method</option>
          <option value="Cash">Cash</option>
          <option value="Cheque">Cheque</option>
          <option value="NEFT">NEFT</option>
        </select>
      </div>
      <div class="form-group mb-3">
        <label for="member-name">Name or ITS</label>
        <input type="text" class="form-control" id="member-name" name="member_name" placeholder="Enter name or ITS" required>
        <div id="member-autocomplete-list" class="list-group position-absolute" style="z-index: 1000; width: 100%;"></div>
        <input type="hidden" name="user_id" id="user-id">
      </div>
      <div class="form-group mb-3">
        <label for="amount">Amount</label>
        <input type="number" class="form-control" id="amount" placeholder="Enter amount" name="amount" required>
      </div>
      <div class="form-group mb-3">
        <label for="remark">Remark</label>
        <textarea class="form-control" id="remark" placeholder="Enter Remark" name="remark"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const memberNameInput = document.getElementById('member-name');
    const userIdInput = document.getElementById('user-id');
    const autocompleteList = document.getElementById('member-autocomplete-list');

    let debounceTimeout = null;

    memberNameInput.addEventListener('input', function() {
      const query = this.value.trim();
      userIdInput.value = '';
      autocompleteList.innerHTML = '';
      if (debounceTimeout) clearTimeout(debounceTimeout);
      if (query.length < 2) return;

      debounceTimeout = setTimeout(() => {
        fetch('<?php echo base_url('anjuman/search_members'); ?>', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'query=' + encodeURIComponent(query)
          })
          .then(response => response.json())
          .then(data => {
            autocompleteList.innerHTML = '';
            if (Array.isArray(data) && data.length > 0) {
              data.forEach(member => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = member.Full_Name + ' (' + member.ITS_ID + ')';
                item.onclick = function() {
                  memberNameInput.value = member.Full_Name;
                  userIdInput.value = member.ITS_ID;
                  autocompleteList.innerHTML = '';
                };
                autocompleteList.appendChild(item);
              });
            }
          });
      }, 250);
    });

    // Hide autocomplete on blur (with slight delay for click)
    memberNameInput.addEventListener('blur', function() {
      setTimeout(() => {
        autocompleteList.innerHTML = '';
      }, 200);
    });
  });

  $(".alert").delay(3000).fadeOut(500);
</script>