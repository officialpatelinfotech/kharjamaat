<style>
  #generate-new-invoice-form-container {
    max-width: 500px;
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
      <a href="<?php echo base_url("anjuman/miqaatinvoice") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <div id="generate-new-invoice-form-container" class="col-12 mt-4 mb-4">
    <div class="card-header border rounded">
      <h5 class="mb-0 text-center">Generate New Invoice</h5>
    </div>
    <div class="card-body p-4 border rounded">
      <form method="post" action="<?php echo base_url("anjuman/create_miqaat_invoice"); ?>">
        <div class="form-group mb-3">
          <label for="year">Select Year</label>
          <select class="form-control" id="year" name="year" required>
            <option value="">-- Select Year --</option>
            <option value="1446">1446</option>
            <option value="1447">1447</option>
            <option value="1448">1448</option>
            <option value="1449">1449</option>
            <option value="1450">1450</option>
            <option value="1451">1451</option>
            <option value="1452">1452</option>
            <option value="1453">1453</option>
            <option value="1454">1454</option>
            <option value="1455">1455</option>
            <option value="1456">1456</option>
          </select>
        </div>
        <div id="miqaat-type-section" class="form-group mb-3" style="display:none;">
          <label for="miqaat-type">Select Miqaat Type</label>
          <select class="form-control" id="miqaat-type" name="miqaat_type" required>
            <option value="">-- Select Miqaat Type --</option>
            <option value="General">General</option>
            <option value="Ashara">Ashara</option>
            <option value="Shehrullah">Shehrullah</option>
            <option value="Ladies">Ladies</option>
          </select>
        </div>
        <div id="miqaat-select-section" class="form-group mb-3" style="display:none;">
          <label for="miqaat_id">Select Miqaat</label>
          <select class="form-control" id="miqaat_id" name="miqaat_id" required>
            <option value="">-- Select Miqaat --</option>
          </select>
        </div>
        <div id="rest-of-form" style="display:none;">
          <div class="form-group mb-3">
            <label for="assigned-to">Assigned To</label>
            <select class="form-control" id="assigned-to" name="assigned_to" required>
              <option value="">-- Select Assigned To --</option>
              <option value="Individual">Individual</option>
              <option value="Group">Group</option>
              <option value="Fala ni Niyaz">Fala ni Niyaz</option>
            </select>
          </div>
          <div class="form-group mb-3" id="member-select-section" style="display:none;">
            <label for="member-id">Select Member</label>
            <select class="form-control" id="member-id" name="member_id" required>
              <option value="">-- Select Member --</option>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="date">Invoice Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
          </div>
          <div class="form-group mb-3">
            <label for="amount">Amount</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" placeholder="Enter amount" required>
          </div>
          <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Create Invoice</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var yearSelect = document.getElementById('year');
    var miqaatTypeSection = document.getElementById('miqaat-type-section');
    var miqaatTypeSelect = document.getElementById('miqaat-type');
    var miqaatSelectSection = document.getElementById('miqaat-select-section');
    var miqaatSelect = document.getElementById('miqaat_id');
    var restOfForm = document.getElementById('rest-of-form');

    yearSelect.addEventListener('change', function() {
      if (this.value) {
        miqaatTypeSection.style.display = '';
      } else {
        miqaatTypeSection.style.display = 'none';
        miqaatSelectSection.style.display = 'none';
        restOfForm.style.display = 'none';
        miqaatTypeSelect.value = '';
        miqaatSelect.innerHTML = '<option value="">-- Select Miqaat --</option>';
      }
    });

    miqaatTypeSelect.addEventListener('change', function() {
      if (this.value) {
        // Fetch miqaats for selected type via AJAX
        var year = yearSelect.value;
        miqaatSelect.innerHTML = '<option value="">Loading...</option>';
        miqaatSelectSection.style.display = '';
        restOfForm.style.display = 'none';
        fetch('<?php echo base_url('anjuman/get_miqaats_by_type'); ?>', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'type=' + encodeURIComponent(this.value) + '&year=' + encodeURIComponent(year)
          })
          .then(response => response.json())
          .then(data => {
            miqaatSelect.innerHTML = '<option value="">-- Select Miqaat --</option>';
            if (Array.isArray(data) && data.length > 0) {
              data.forEach(function(miqaat) {
                var option = document.createElement('option');
                option.value = miqaat.id;
                option.textContent = miqaat.name + (miqaat.date ? ' (' + miqaat.date + ')' : '');
                miqaatSelect.appendChild(option);
              });
            } else {
              miqaatSelect.innerHTML = '<option value="">No miqaats found</option>';
            }
          })
          .catch(() => {
            miqaatSelect.innerHTML = '<option value="">Error loading miqaats</option>';
          });
      } else {
        miqaatSelectSection.style.display = 'none';
        restOfForm.style.display = 'none';
        miqaatSelect.innerHTML = '<option value="">-- Select Miqaat --</option>';
      }
    });


    var assignedToSelect = document.getElementById('assigned-to');
    var memberSelectSection = document.getElementById('member-select-section');
    var memberSelect = document.getElementById('member-id');

    function fetchMembersIfReady() {
      var miqaatId = miqaatSelect.value;
      var assignedTo = assignedToSelect.value;
      if (miqaatId && assignedTo) {
        memberSelectSection.style.display = '';
        memberSelect.innerHTML = '<option value="">Loading...</option>';
        fetch('<?php echo base_url('anjuman/get_miqaat_members'); ?>', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'miqaat_id=' + encodeURIComponent(miqaatId) + '&assigned_to=' + encodeURIComponent(assignedTo)
          })
          .then(response => response.json())
          .then(data => {
            memberSelect.innerHTML = '<option value="">-- Select Member --</option>';
            if (Array.isArray(data) && data.length > 0) {
              data.forEach(function(member) {
                var option = document.createElement('option');
                option.value = member.ITS_ID;
                option.textContent = member.Full_Name + ' (' + member.ITS_ID + ')' + (member.group_name ? (" - " + member.group_name) : '');
                memberSelect.appendChild(option);
              });
            } else {
              memberSelect.innerHTML = '<option value="">No members found</option>';
            }
          })
          .catch(() => {
            memberSelect.innerHTML = '<option value="">Error loading members</option>';
          });
      } else {
        memberSelectSection.style.display = 'none';
        memberSelect.innerHTML = '<option value="">-- Select Member --</option>';
      }
    }

    miqaatSelect.addEventListener('change', function() {
      if (this.value) {
        // Fetch assigned_to from miqaat table
        fetch('<?php echo base_url('anjuman/get_miqaat_assigned_to'); ?>', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'miqaat_id=' + encodeURIComponent(this.value)
          })
          .then(response => response.json())
          .then(data => {
            if (data && data.length > 0) {
              assignedToSelect.value = data[0];
              // Trigger change event to update member select
              var event = new Event('change');
              assignedToSelect.dispatchEvent(event);
            }
          })
          .catch(() => {
            // Optionally handle error
          });
        restOfForm.style.display = '';
        fetchMembersIfReady();
      } else {
        restOfForm.style.display = 'none';
        memberSelectSection.style.display = 'none';
      }
    });

    assignedToSelect.addEventListener('change', function() {
      if (this.value === 'Fala ni Niyaz') {
        memberSelectSection.style.display = 'none';
        memberSelect.required = false;
      } else {
        memberSelect.required = true;
        fetchMembersIfReady();
      }
    });
  });

  $(".alert").delay(3000).fadeOut(500);
</script>