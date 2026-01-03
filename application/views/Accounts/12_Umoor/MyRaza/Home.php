<style>
  td {
    min-width: 130px;
  }

  .status {
    min-width: 170px;
  }

  .chat-button {
    display: inline-block;
    padding: 10px 20px;
    font-size: 18px;
    font-weight: bold;
    text-decoration: none;
    color: #fff;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  /* Hover effect */
  .chat-button:hover {
    background-color: #0056b3;
    color: white;
    text-decoration: none;
  }

  .chat-count {
    display: inline-block;
    width: 25px;
    height: 25px;
    background-color: grey;
    color: white;
    border-radius: 50%;
    /* Make it circular */
    text-align: center;
    line-height: 25px;
    font-size: 14px;
    font-weight: bold;
    margin-left: 5px;
    /* Adjust as needed */
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
    /* Add shadow for depth */
  }
</style>
<div class="margintopcontainer pt-5">
  <div class="container">
    <div class="">
      <a href="<?php echo base_url('accounts/home'); ?>" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i>
      </a>
    </div>
    <p class="h4 text-center" style="text-transform: uppercase; color: goldenrod;">My Raza Request For <?php echo $value ?></p>
    <div class="container">
      <div class="row mt-5">
        <form class="form-inline my-2 my-lg-0 w-100">
          <div class="input-group">
            <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="razaSearchInput">
            <div class="input-group-append">
              <span class="input-group-text">
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <a class="form-control btn btn-success my-2 my-lg-0 ml-auto" href="<?php echo base_url('Umoor12/NewRaza?value=' . $value); ?>">New Request</a>

        </form>
      </div>
    </div>
    <div class="table-responsive mt-5 mb-5">
      <div class="table-container">
        <table class="table table-bordered text-center">
          <thead>
            <tr>
              <th class="created">Created</th>
              <th class="raza">Raza For</th>
              <th class="date">Name</th>
              <th class="approval_status">Status</th>
              <th class="approval_status">Remark</th>
              <th class="action">Action</th>
            </tr>
            <?php
            foreach ($raza as $r) { ?>
              <tr>
                <td>
                  <?php echo date('D, d M @ g:i a', strtotime($r['time-stamp'])) ?>
                </td>
                <td>
                  <?php echo $r['razaType'] ?>
                </td>
                <td>
                  <?php echo $r['user_name'] ?>
                </td>
                <td class="status">
                  <div class="text-left">
                    <ul>
                      <?php if ($r['status'] == 0) {
                        echo '<div><strong style="color: darkblue;">Pending</strong></div>';
                      } elseif ($r['status'] == 1) {
                        echo '<div><strong style="color: blue;">Recommended</strong></div>';
                      } elseif ($r['status'] == 2) {
                        echo '<div><strong style="color: limegreen;">Approved</strong></div>';
                      } elseif ($r['status'] == 3) {
                        echo '<div><strong style="color: red;">Rejected</strong></div>';
                      } elseif ($r['status'] == 4) {
                        echo '<div><strong style="color: blue;">Not Recommended</strong></div>';
                      } ?>
                      <li>
                        <?php if ($r['coordinator-status'] == 0) {
                          echo '<div>Jamat <i class="fa-solid fa-clock" style="color: #fff700;"></i></div>';
                        } elseif ($r['coordinator-status'] == 1) {
                          echo '<div>Jamat <i class="fa-solid fa-circle-check" style="color: limegreen;"></i></div>';
                        } elseif ($r['coordinator-status'] == 2) {
                          echo '<div>Jamat <i class="fa-solid fa-circle-xmark" style="color: red;"></i></div>';
                        } ?>
                      </li>
                      <li>
                        <?php if ($r['Janab-status'] == 0) {
                          echo '<div>Amil Saheb <i class="fa-solid fa-clock" style="color: #fff700;"></i></div>';
                        } elseif ($r['Janab-status'] == 1) {
                          echo '<div>Amil Saheb <i class="fa-solid fa-circle-check" style="color: limegreen;"></i></div>';
                        } elseif ($r['Janab-status'] == 2) {
                          echo '<div>Amil Saheb <i class="fa-solid fa-circle-xmark" style="color: red;"></i></div>';
                        } ?>
                      </li>
                    </ul>
                  </div>
                </td>
                <td>
                  <a href="<?= base_url('Accounts/chat/') . $r['id'] ?>" class="chat-button">
                    Chat<?= isset($r['chat_count']) && $r['chat_count'] > 0 ? '<div class="chat-count">' . $r['chat_count'] . '</div>' : '' ?>
                  </a>
                </td>
                <td>
                  <?php if ($r['coordinator-status'] == 0) { ?>
                    <button type="button" class="btn btn-sm btn-primary remove-form-row" onclick="redirectto('umoor12/edit_raza/<?php echo $r['id'] ?>?value=<?php echo $value ?>');"><i class="fa <?php echo $r['razaType_id'] == 2 ? "fa-eye" : "fa-pen-to-square"; ?>"></i></button>
                    <button type="button" class="btn btn-sm btn-danger remove-form-row" onclick="redirecttodelete('umoor12/DeleteRaza/<?php echo $r['id'] ?>?value=<?php echo $value ?>');">
                      <i class="fa fa-trash-alt"></i>
                    </button>
                  <?php } ?>
                </td>
              </tr>
            <?php } ?>
          </thead>
          <tbody></tbody>
          <tfoot></tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  function redirecttodelete(location) {
    let check = confirm('Do you want to Delete this Raza')
    if (check) {
      window.location.href = '<?php echo base_url() ?>' + location;
    }
  }

  function redirectto(location) {
    window.location.href = '<?php echo base_url() ?>' + location;

  }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function() {
    // Function to handle the search functionality
    function performSearch() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("razaSearchInput");
      filter = input.value.toLowerCase();
      table = document.getElementsByTagName("table")[0]; // Assuming it's the first table on the page
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows and hide those that don't match the search query
      for (i = 1; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        var found = false;
        for (var j = 0; j < td.length; j++) {
          if (td[j]) {
            txtValue = td[j].textContent || td[j].innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
              found = true;
              break;
            }
          }
        }
        if (found) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }
    }

    // Attach the performSearch function to the input field's keyup event
    document.getElementById("razaSearchInput").addEventListener("keyup", performSearch);
  });
</script>