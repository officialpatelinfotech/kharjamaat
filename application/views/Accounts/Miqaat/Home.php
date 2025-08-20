<style>
  .rsvp-col {
    position: relative;
  }

  .hide {
    display: none;
  }

  .rsvp-btn {
    position: absolute;
    bottom: 0;
    right: 10px;
  }

  .btn-default {
    background-color: #f3b007;
    color: white;
  }

  .fontcertgreen {
    color: #799840;
  }

  .text-info {
    color: #17a2b8;
  }

  .fontbold {
    font-weight: 700;
  }

  .fontblack {
    color: black
  }

  @media only screen and (max-width: 767px) {
    .dblock-xs {
      display: block !important;
    }

    .dinblock-xs {
      display: inline-block !important;
    }

    .hide-override-xs {
      display: none;
    }

    .btn {
      width: auto;
    }
  }

  .seach_btn {
    display: flex;
    justify-content: center;
    justify-content: space-between;

    @media screen and (max-width:768px) {
      flex-direction: column;
      row-gap: 1rem;
    }
  }

  .w100percent-xs {
    @media screen and (max-width:548px) {
      width: 100%;
    }
  }

  td {
    min-width: 130px;
  }

  .utensils {
    min-width: 250px;
  }
</style>
<div class="container pt-5 margintopcontainer">
  <h1 class="text-center">RSVP</h1>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 mbm">
        <h4 class="clearfix mt-5 mb-4">
          <div class="pull-left">Upcoming Miqaats&nbsp;</div>
        </h4>
        <div class="list-items">
          <ul class="list-group">
            <li class="list-group-item hide-override-xs">
              <div class="row">
                <h5 class="mt0 mb0 col-sm-9">Miqaat</h5>
                <h5 class="mt0 mb0 col-sm-3">RSVP</h5>
              </div>
            </li>
            <?php foreach ($rsvp_list as $rv) { ?>
              <li class="list-group-item">
                <div class="row">
                  <div class="col-sm-12 col-md-9">
                    <h5 class="mt0"><a href="<?php echo base_url('accounts/Rsvp/') . $rv['id'] ?>"
                        class="fontblack">
                        <?php echo $rv['name'] ?>
                      </a></h5>
                    <div class="fontcertgreen fontbold">
                      <?php echo date('D, d M', strtotime($rv['date'])) ?> @
                      <?php echo $rv['time'] ?>
                    </div>
                    <div class="font-italic font-sml-1 fontgray">
                      <?php echo $rv['hijri_date'] ?>
                    </div>
                    <div class="text-info fontbold mtop-5">Kindly RSVP by
                      <?php echo date('D, d M', strtotime($rv['expired'])) ?>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-3 rsvp-col">
                    <hr class="mbm hide dblock-xs">
                    <div class="font-lvl-3 dinblock-xs">
                      <div class="mbm">
                        <?php if ($rv['attend'] == null) {
                          echo 'No RSVP Received';
                        } else {
                          echo '<strong style="color:#799840;">Done</strong>';
                        } ?>
                      </div>

                      <a href="<?php echo base_url('accounts/Rsvp/') . $rv['id'] ?>"
                        class="btn btn-default btn-sm rsvp-btn">
                        RSVP</a>
                    </div>
                  </div>
                </div>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
    <!--<div class="row mt-4">-->
    <!--    <div class="col-12">-->
    <!--        <hr>-->
    <!--        <h4 class="mt0 mb0">Vasan Requests&nbsp;</h4>-->
    <!--        <div class="clearfix mbm mt-4">-->
    <!--            <div class="seach_btn">-->
    <!--                <div>-->
    <!--                    <div class="input-group">-->
    <!--                        <input class="form-control" type="search" placeholder="Search" aria-label="Search"-->
    <!--                            id="razaSearchInput">-->
    <!--                        <div class="input-group-append">-->
    <!--                            <span class="input-group-text">-->
    <!--                                <i class="fa fa-search"></i>-->
    <!--                            </span>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div><a href="<?php echo base_url('accounts/newVasanReq') ?>"-->
    <!--                        class="btn btn-default btn-sm w100percent-xs">New-->
    <!--                        Vasan Request</a></div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="table-responsive mt-4 mb-5">-->
    <!--            <div class="table-container">-->
    <!--                <table class="table table-striped table-bordered">-->
    <!--                    <thead>-->
    <!--                        <tr>-->
    <!--                            <th class="created">Created</th>-->
    <!--                            <th class="reason">Reason</th>-->
    <!--                            <th class="from_date">Date Or From Date</th>-->
    <!--                            <th class="to_date">To Date</th>-->
    <!--                            <th class="utensils">Utensils</th>-->
    <!--                            <th class="status">Status</th>-->
    <!--                            <th class="action">Action</th>-->
    <!--                        </tr>-->
    <!--                        <?php foreach ($vasanreq_list as $vr) { ?>-->
    <!--                            <tr>-->
    <!--                                <td>-->
    <!--                                    <?php echo date('D, d M @ g:i a', strtotime($vr['time-stamp'])) ?>-->
    <!--                                </td>-->
    <!--                                <td>-->
    <!--                                    <?php echo $vr['reason'] ?>-->
    <!--                                </td>-->
    <!--                                <td>-->
    <!--                                    <?php echo date('D, d M', strtotime($vr['from_date'])) ?>-->
    <!--                                </td>-->
    <!--                                <td>-->
    <!--                                    <?php echo date('D, d M', strtotime($vr['to_date'])) ?>-->
    <!--                                </td>-->
    <!--                                <td>-->
    <!--                                    <?php echo $vr['utensils'] ?>-->
    <!--                                </td>-->

    <!--                                <td>-->
    <!--                                    <div class="text-left">-->
    <!--                                        if ($vr['status'] == 0) {-->
    <!--                                            echo '<div><strong style="color: darkblue;">Pending</strong></div>';-->
    <!--                                        } elseif ($vr['status'] == 1) {-->
    <!--                                            echo '<div><strong style="background: limegreen;">Approved</strong></div>';-->
    <!--                                        } -->
    <!--                                    </div>-->
    <!--                                </td>-->
    <!--                                <td>-->
    <!--                                    <button type="button" class="btn btn-sm btn-primary remove-form-row"-->
    <!--                                        onclick="redirectto('accounts/updateVasanReq/<?php echo $vr['id'] ?>');"><i-->
    <!--                                            class="fa fa-pen-to-square"></i></button>-->
    <!--                                    <button type="button" class="btn btn-sm btn-danger remove-form-row"-->
    <!--                                        onclick="redirectto('accounts/deleteVasanReq/<?php echo $vr['id'] ?>');"><i-->
    <!--                                            class="fa fa-trash-alt"></i></button>-->
    <!--                                </td>-->

    <!--                            </tr>-->
    <!--                        <?php } ?>-->
    <!--                    </thead>-->
    <!--                    <tbody></tbody>-->
    <!--                </table>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function redirectto(location) {
    window.location.href = '<?php echo base_url() ?>' + location;
  }
</script>
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