<div class="container margintopcontainer pt-5">
  <div class="row col-12 col-md-12 mb-3">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("common/deliverydashboard") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="col-12 col-md-6 text-right">
      <a href="<?php echo base_url("common/managedeliveryperson"); ?>" class="btn btn-secondary"><i class="fa-solid fa-hard-hat"></i> Manage Delivery Person</a>
    </div>
  </div>
  <h4 class="heading text-center mb-4">Permanent Delivery Person List</h4>
  <div id="assign-delivery-person" class="container">
    <form action="<?php echo base_url("common/assigndeliveryperson"); ?>" id="assign-delivery-person">
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
                <td><?php echo $user["Full_Name"]; ?></td>
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
      url: "<?php echo base_url("common/updatedpmapping"); ?>",
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