<style>
  .hide {
    display: none;
  }
</style>
<div class="margintopcontainer mb-5">
  <div class="container pt-5">
    <div class="row">
      <div class="col-12">
        <h1 class="text-center">Miqaat RSVP</h1>
        <div class="card mt-4">
          <div class="card-header bg-light">
            <div class="clearfix">
              <div class="pull-left">Miqaat Details</div>
            </div>
          </div>
          <div class="card-body">
            <h4 class="mt0">
              <?php echo $rsvp['name'] ?>
            </h4>
            <div>Date:
              <?php echo date('D, d M', strtotime($rsvp['date'])) ?> @
              <?php echo $rsvp['time'] ?>
            </div>
            <div>Program:
              <?php echo $rsvp['description'] ?>
            </div>
            <div>Last day to RSVP:
              <?php echo date('D, d M', strtotime($rsvp['expired'])) ?>
            </div>
          </div>
        </div>
        <div class="alert alert-warning mt-3"><b>Important!&nbsp;</b>
          <ul>
            <li>To mark as an Absent submit without selecting family member</li>
          </ul>
        </div>
        <form method="post" class="form-inline"
          action="<?php echo base_url('accounts/submit_rsvp/') . $rsvp['id'] ?>">
          <table class="table table-bordered mb-5">
            <thead>
              <tr>
                <th class="text-center">S.No.</th>
                <th>Name</th>
                <th class="text-center">Mark Attendance</th>
              </tr>
            </thead>
            <tbody>
              <?php $index = 1;
              foreach ($family as $f) { ?>
                <tr>
                  <td class="text-center">
                    <?php echo $index ?>
                  </td>
                  <td>
                    <?php echo $f['Full_Name'] ?>
                  </td>
                  <td class="text-center">
                    <input type="checkbox" name="<?php echo $f['id'] ?>" <?php if (!empty($f['attend'])) {
                                                                            echo 'checked';
                                                                          } ?>>
                  </td>

                </tr>
              <?php $index++;
              } ?>
              <tr>
                <td class="text-center">
                  <?php echo $index++ ?>
                </td>
                <td>Mehman Mardo</td>
                <td class="text-center"><input
                    style="max-width:50px; font-size:15px; padding:2px; text-align:center;"
                    type="number" name="guest_male"
                    value="<?php if (!empty($guest[0]['male'])) {
                              echo $guest[0]['male'];
                            } ?>"></td>
              </tr>
              <tr>
                <td class="text-center">
                  <?php echo $index ?>
                </td>
                <td>Mehman Bairao</td>
                <td class="text-center"><input
                    style="max-width:50px; font-size:15px; padding:2px; text-align:center;"
                    type="number" name="guest_female"
                    value="<?php if (!empty($guest[0]['female'])) {
                              echo $guest[0]['female'];
                            } ?>"></td>
              </tr>
            </tbody>
          </table>
          <div class="text-center w-100"><button type="submit"
              class="btn btn-success w100percent-xs">Submit</button>&nbsp;<a
              href="<?php echo base_url('accounts/miqaat') ?>"
              class="btn btn-danger w100percent-xs">Cancel</a></div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  var isAttendingSelect = document.getElementById("id_is_attending");
  var adultCountRow = document.querySelector(".hide:nth-child(2)");
  var kidsCountRow = document.querySelector(".hide:nth-child(3)");

  isAttendingSelect.addEventListener("change", function() {
    if (isAttendingSelect.value === "True") {
      adultCountRow.style.display = "table-row";
      kidsCountRow.style.display = "table-row";
    } else {
      adultCountRow.style.display = "none";
      kidsCountRow.style.display = "none";
    }
  });
</script>