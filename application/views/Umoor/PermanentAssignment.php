<div class="container margintopcontainer">
  <h2 class="heading text-center pt-5 mb-4">Permanent Delivery Person Assignment</h2>
  <div class="row col-12 col-md-12 mb-3">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("Umoor/deliverydashboard") ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
    <div class="col-12 col-md-6 text-right">
      <a href="<?php echo base_url("Umoor/managedeliveryperson"); ?>" class="btn btn-secondary">Manage Delivery Person</a>
    </div>
  </div>
  <div id="assign-delivery-person" class="container">
    <form action="<?php echo base_url("Umoor/assigndeliveryperson"); ?>" id="assign-delivery-person">
      <legend class="text-center pt-2 text-primary">Mumineen List</legend>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Sr. No.</th>
            <th>Mumineen Name</th>
            <th>Sector</th>
            <th>Sub-sector</th>
            <th>Delivery Person</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($all_users)) {
            foreach ($all_users as $key => $user) {
          ?>
              <tr>
                <td>
                  <?php echo $key + 1; ?>
                </td>
                <td><?php echo $user["First_Name"] . " " . $user["Surname"]; ?></td>
                <td><?php echo $user["Sector"]; ?></td>
                <td><?php echo $user["Sub_Sector"]; ?></td>
                <td>
                  <select class="form-control deliver-person-select" data-user-id="<?php echo $user["ITS_ID"]; ?>">
                    <option value="">-----</option>
                    <?php
                    foreach ($all_dp as $dp) {
                    ?>
                      <option value="<?php echo $dp["id"]; ?>" <?php echo $user["dp_id"] == $dp["id"] ? "selected" : ""; ?>><?php echo $dp["name"]; ?></option>
                    <?php
                    }
                    ?>
                  </select>
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
  $(".deliver-person-select").on("change", function(e) {
    $userId = $(this).data("user-id");
    $dpId = $(this).val();
    $.ajax({
      url: "<?php echo base_url("Umoor/updatedpmapping"); ?>",
      method: "POST",
      data: {
        "user_id": $userId,
        "dp_id": $dpId,
      },
      success: function(res) {
        if (JSON.parse(res).success) {
          alert("Delivery person updated successfully!");
        }
      }
    })
  })
</script>