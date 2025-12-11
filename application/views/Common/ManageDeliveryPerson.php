<style>
  #delivery-person-form-container {
    border-radius: 5px;
    border: 1px solid #999;
  }

  .hidden {
    display: none;
  }
</style>
<div class="container margintopcontainer pt-5">
  <div class="row mb-4 mx-4">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("common/deliverydashboard"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="col-12 col-md-6 mt-md-0 text-right">
      <button id="add-delivery-person" class="btn btn-secondary"><i class="fa-solid fa-plus"></i> Add Delivery Person</button>
    </div>
  </div>
  <h4 class="heading text-center mb-4">All Delivery Person</h4>
  <div class="pt-3 hidden" id="delivery-person-form-container">
    <form method="POST" action="<?php echo base_url("common/adddeliveryperson"); ?>" class="row">
      <div class="container col-md-3">
        <label for="delivery-person-name"><b>Delivery Person Name:</b></label>
        <input type="text" name="delivery_person_name" id="delivery-person-name" class="form-control" placeholder="Enter delivery person name" required>
      </div>
      <div class="container col-md-3 mt-2 mt-md-0">
        <label for="delivery-person-phone"><b>Delivery Person Phone:</b></label>
        <input type="number" name="delivery_person_phone" id="delivery-person-phone" class="form-control" placeholder="Enter delivery person phone">
      </div>
      <div class="col-md-3 d-flex align-items-center">
        <input type="submit" id="submit-delivery-person" class="mt-2 mt-md-0 btn btn-success ml-auto" value="Submit">
      </div>
      <div class="col-md-3 d-flex align-items-center">
        <button id="hidden-add-dp" class="mt-2 mt-md-0 btn btn-secondary">Cancel</button>
      </div>
    </form>
  </div>
  <div class="container">
    <form>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Sr. No.</th>
            <th>Created At</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($all_dp)) {
            foreach ($all_dp as $key => $dp) {
          ?>
              <tr>
                <td>
                  <?php echo $key + 1; ?>
                </td>
                <td><?php echo date("d-m-Y", strtotime($dp["created_at"])); ?></td>
                <td>
                  <span id="name-text-<?php echo $dp["id"]; ?>"><?php echo $dp["name"]; ?></span>
                  <input type="text" id="name-<?php echo $dp["id"]; ?>" class="hidden form-control" name="name[]" value="<?php echo $dp["name"]; ?>" required>
                </td>
                <td>
                  <span id="phone-text-<?php echo $dp["id"]; ?>"><?php echo !empty($dp["phone"]) ? $dp["phone"] : "NA"; ?></span>
                  <input type="text" id="phone-<?php echo $dp["id"]; ?>" class="hidden form-control" name="phone[]" value="<?php echo $dp["phone"]; ?>">
                </td>
                <td>
                  <span id="delivery-person-status-text-<?php echo $dp["id"]; ?>"><?php echo isset($dp["status"]) ? ((int)$dp["status"] ? "Active" : "Inactive") : "NA"; ?></span>
                  <select name="delivery_person_status" id="delivery-person-status-<?php echo $dp["id"]; ?>" class="form-control delivery-person-status hidden" data-dp-id="<?php echo $dp["id"]; ?>">
                    <option value="1" <?php echo (int)$dp["status"] == 1 ? "selected" : ""; ?>>Active</option>
                    <option value="0" <?php echo (int)$dp["status"] == 0 ? "selected" : ""; ?>>Inactive</option>
                  </select>
                </td>
                <td>
                  <button id="edit-button-<?php echo $dp["id"]; ?>" class="edit-button btn btn-primary" data-dp-id="<?php echo $dp["id"]; ?>">Edit</button>
                  <button id="save-button-<?php echo $dp["id"]; ?>" class="save-button btn btn-success hidden" data-dp-id="<?php echo $dp["id"]; ?>">Save</button>
                </td>
              </tr>
          <?php
            }
          }
          ?>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script>
  $("#add-delivery-person").on("click", function() {
    $("#delivery-person-form-container").removeClass("hidden");
  });
  $("#submit-delivery-person").on("click", function(e) {
    if ($("#delivery-person-phone").val().length != 10) {
      e.preventDefault();
      alert("Phone number can't be less than 10 digits.");
    }
  })
  $("#hidden-add-dp").on("click", function(e) {
    e.preventDefault();
    $("#delivery-person-form-container").addClass("hidden");
    $("#delivery-person-name").val("");
  });

  $(".edit-button").on("click", function(e) {
    e.preventDefault();
    $dpId = $(this).data("dp-id");
    $(this).addClass("hidden");
    $("#name-text-" + $dpId).addClass("hidden");
    $("#phone-text-" + $dpId).addClass("hidden");
    $("#delivery-person-status-text-" + $dpId).addClass("hidden");
    $("#name-" + $dpId).removeClass("hidden");
    $("#phone-" + $dpId).removeClass("hidden");
    $("#delivery-person-status-" + $dpId).removeClass("hidden");
    $("#save-button-" + $dpId).removeClass("hidden");
  });

  $(".save-button").on("click", function(e) {
    e.preventDefault();
    $nameField = $("#name-" + $dpId);
    $phoneField = $("#phone-" + $dpId);
    $statusField = $("#delivery-person-status-" + $dpId);
    $dpId = $(this).data("dp-id");
    $(this).addClass("hidden");
    $("#name-text-" + $dpId).removeClass("hidden");
    $("#phone-text-" + $dpId).removeClass("hidden");
    $("#delivery-person-status-text-" + $dpId).removeClass("hidden");
    $nameField.addClass("hidden");
    $phoneField.addClass("hidden");
    $statusField.addClass("hidden");
    $("#edit-button-" + $dpId).removeClass("hidden");

    $nameFieldValue = $nameField.val();
    $phoneFieldValue = $phoneField.val();
    $statusFieldValue = $statusField.val();

    $.ajax({
      url: "<?php echo base_url("common/updatedeliveryperson"); ?>",
      method: "POST",
      data: {
        "dp_id": $dpId,
        "name": $nameFieldValue,
        "phone": $phoneFieldValue,
        "status": $statusFieldValue,
      },
      success: function(res) {
        const JSONres = JSON.parse(res);
        if (JSONres.success) {
          alert("Delivery person's details updated successfully!");
          location.reload();
        } else {
          alert("There was some problem while saving delivery person's details.")
        }
      }
    });
  });
</script>