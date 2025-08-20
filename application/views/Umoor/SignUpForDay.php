<div class="container margintopcontainer">
  <h2 class="heading text-center pt-5 mb-2">Delivery Details</h2>
  <h5 class="text-center text-primary">For <?php echo date("d-m-Y", strtotime($date)); ?></h5>
  <div class="row mb-4 mx-4">
    <div class="container col-12 col-md-12">
      <a href="<?php echo base_url("Umoor/DeliveryDashboard") ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
  </div>
  <div class="row mb-4 mx-4">
    <form method="POST" action="<?php echo base_url("Umoor/signupforaday/" . $date); ?>" class="row col-md-12">
      <div class="col-12 col-md-3">
        <label for="thali-taken">Thali Taken?</label>
        <select name="thali_taken" id="thali-taken" class="form-control">
          <option value="-1">All</option>
          <option value="1" <?php echo isset($filter_data["thali_taken"]) ? ($filter_data["thali_taken"] == 1 ? "selected" : "") : ""; ?>>Yes</option>
          <option value="0" <?php echo isset($filter_data["thali_taken"]) ? ($filter_data["thali_taken"] == 0 ? "selected" : "") : ""; ?>>No</option>
        </select>
      </div>
      <div class="col-12 col-md-3">
        <label for="delivery-person">Reg. Delivery Person</label>
        <select name="reg_dp_id" id="delivery-person" class="form-control">
          <option value="">------</option>
          <?php
          foreach ($all_dp as $dp) {
          ?>
            <option value="<?php echo $dp["id"]; ?>" <?php echo $filter_data["reg_dp_id"] == $dp["id"] ? "selected" : ""; ?>><?php echo $dp["name"]; ?></option>
          <?php
          }
          ?>
        </select>
      </div>
      <div class="col-12 col-md-3">
        <label for="delivery-person">Sub. Delivery Person</label>
        <select name="sub_dp_id" id="delivery-person" class="form-control">
          <option value="">------</option>
          <?php
          foreach ($all_dp as $dp) {
          ?>
            <option value="<?php echo $dp["id"]; ?>" <?php echo $filter_data["sub_dp_id"] == $dp["id"] ? "selected" : ""; ?>><?php echo $dp["name"]; ?></option>
          <?php
          }
          ?>
        </select>
      </div>
      <div class="col-12 col-md-3 text-right">
        <a href="<?php echo base_url("Umoor/signupforaday/" . $date); ?>" class="btn btn-secondary">Clear Filter</a>
        <button class="btn btn-primary ml-3">Filter</button>
      </div>
    </form>
  </div>
  <div class="container">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>ITS ID</th>
          <th>Full Name</th>
          <th>Thali Taken?</th>
          <th>Thali Size</th>
          <th>Reg. Delivery Person</th>
          <th>Sub. Delivery Person</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (isset($signupdata)) {
          foreach ($signupdata as $key => $value) {
        ?>
            <tr>
              <td><?php echo $value["ITS_ID"]; ?></td>
              <td><?php echo $value["Full_Name"]; ?></td>
              <td><?php echo !empty($value["want_thali"]) ? ($value["want_thali"] ? "Yes" : "No") : "NA"; ?></td>
              <td><?php echo !empty($value["thali_size"]) ? $value["thali_size"] : ""; ?></td>
              <td><?php echo $value["delivery_person_name"]; ?></td>
              <td><?php echo $value["sub_delivery_person_name"]; ?></td>
            </tr>
          <?php
          }
        } else {
          ?>
          <tr><td colspan="6">No data found!</td></tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</div>