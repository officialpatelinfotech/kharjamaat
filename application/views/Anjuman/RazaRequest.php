<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
  /*td {*/
  /*    min-width: 100px;*/
  /*}*/
  .sno {
    width: 40px;
  }

  .created {
    min-width: 130px;
  }

  .raza {
    min-width: 130px;
  }

  .eventdate {
    min-width: 130px;
  }

  .name {
    min-width: 130px;
  }

  .remark {
    min-width: 130px;
  }

  .approval_status {
    min-width: 130px;
  }

  .action {
    min-width: 100px;
  }

  .action_btn {
    display: flex;
    flex-direction: row;
    gap: 1rem;

    @media screen and (max-width:768px) {
      flex-direction: column;
      gap: 1rem;
      flex-grow: 1;
    }
  }

  .query-form {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    border: 1px solid lightgrey;
    padding: 2rem;
    width: 400px;
    max-height: calc(100vh - 120px);
    overflow-y: auto;
    display: none;
    z-index: 12;
    border-radius: 5px;

    @media screen and (max-width:500px) {

      max-width: 350px;

      @media screen and (max-width: 374px) {

        max-width: 250px;
      }
    }
  }

  #product-overlay {
    display: none;
    top: 0;
    position: fixed;
    height: 100vh;
    width: 100vw;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 10;
  }

  .toast-message {
    position: fixed;
    top: 10;
    right: 0;
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 20px;
    border-radius: 4px;
    z-index: 9999;
    display: none;
    font-size: 15px;
    animation: slideIn 0.5s, slideOut 0.5s 2s;

    @media screen and (max-width:400px) {
      width: 100%;
      text-align: center;
    }
  }

  @keyframes slideIn {
    from {
      right: -100%;
    }

    to {
      right: 0;
    }
  }

  @keyframes slideOut {
    from {
      right: 0;
    }

    to {
      right: -100%;
    }
  }

  .submit {
    margin-top: 2rem;
    display: flex;
    justify-content: space-between;

    @media screen and (max-width:768px) {
      flex-direction: column-reverse;
      gap: 2rem;
    }
  }

  .status {
    min-width: 230px;
  }

  .select {
    background: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: 5px;
    font-size: 18px;
    color: #495057;
    padding-inline: 9px;

    @media screen and (max-width:576px) {
      font-size: 20px;
      width: 100%;
    }
  }

  .options {
    background-color: white;
  }

  .table-responsive {
    transform: rotateX(180deg);
  }

  .table-container {
    transform: rotateX(180deg);
  }

  .chat-button {
    display: flex;
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
    align-items: center;
    justify-content: center;
    margin: auto;
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

  /* Style the sort icons */
  .sort-icons {
    display: inline-block;
    margin-left: 5px;
    cursor: pointer;
    vertical-align: middle;
  }

  .sort-icons i {
    display: block;
    font-size: 10px;
    /* Adjust the size of the icons */
    margin: 0;
    line-height: 1;
    color: #333;
  }

  .sort-icons i:first-child {
    margin-bottom: 2px;
  }

  /* Align the column text with the icons */
  th {
    white-space: nowrap;
  }

  .action-buttons {
    display: inline-block;
    text-align: center;
  }

  .button-group {
    display: flex;
    justify-content: space-between;
    /* Keep buttons spaced apart */
  }

  .button-group button {
    margin-right: 5px;
    /* Add some space between buttons */
  }

  .view-link {
    margin-top: 10px;
    /* Adds space between buttons and the "View" link */
    text-align: center;
  }
</style>
<div id="toast-message" class="toast-message">
  Successfull
</div>
<div class="margintopcontainer mx-3 pb-4">
  <div class="ml-1 mr-1 pt-5">
    <a href="<?php echo base_url("anjuman") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    <p class="h4 text-center mt-5" style="color:goldenrod; text-transform: uppercase;"><?php echo $umoor; ?></p>
    <div class="container">
      <div class="row">
        <form class="form-inline my-2 my-lg-0 w-100">
          <div class="input-group">
            <input class="form-control" type="search" placeholder="Search" aria-label="Search"
              id="razaSearchInput">
            <div class="input-group-append">
              <span class="input-group-text">
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <a class="form-control btn btn-success my-2 my-lg-0 ml-auto" onclick="refresh();">Refresh</a>
        </form>
      </div>
      <div class="row d-flex justify-content-between mt-3">
        <select onchange="updateTable();" name="filter" class="select mb-3" id="filter">
          <option value="" selected disabled>Status</option>

          <!-- <?php foreach ($razatype as $key => $value) { ?>
            <option class="options" value="<?php echo $value['name'] ?>">
              <?php echo $value['name'] ?>
            </option>
          <?php } ?> -->

          <option class="options" value="pending">Pending</option>
          <option class="options" value="approved">Approved</option>
          <option class="options" value="recommended">Recommended</option>
          <option class="options" value="notrecommended">Not Recommended</option>
          <option class="options" value="rejected">Rejected</option>
          <option class="options" value="clear">Clear</option>
        </select>
        <?php if (empty($umoor) || $umoor !== '12 Umoor Raza Applications'): ?>
          <?php if (empty($umoor) || stripos($umoor, 'Kaaraj') === false): ?>
            <select onchange="updateTable();" name="miqaat_filter" class="select mb-3" id="miqaat_filter">
              <option value="" selected>All Miqaat Types</option>
              <option class="options" value="Shehrullah">Shehrullah</option>
              <option class="options" value="Ashara">Ashara</option>
              <option class="options" value="General">General</option>
              <option class="options" value="Ladies">Ladies</option>
            </select>
          <?php endif; ?>
        <?php else: ?>
          <?php
          $umoors = [];
          foreach ($razatype as $rt) {
            if (!empty($rt['umoor'])) $umoors[$rt['umoor']] = $rt['umoor'];
          }
          ?>
          <select onchange="updateTable();" name="umoor_filter" class="select mb-3" id="umoor_filter">
            <option value="" selected>All Umoor Types</option>
            <?php foreach ($umoors as $u): ?>
              <option value="<?php echo htmlspecialchars($u, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($u, ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
          </select>
        <?php endif; ?>

        <?php
        // Build a list of Hijri event years from $raza (miqaat_details or razadata)
        $ci = &get_instance();
        $ci->load->model('HijriCalendar');
        $hijri_years = [];
        foreach ($raza as $r) {
          $d = '';
          if (!empty($r['miqaat_details'])) {
            $md = json_decode($r['miqaat_details'], true);
            if (is_array($md) && !empty($md['date'])) $d = substr($md['date'], 0, 10);
          }
          if (empty($d) && !empty($r['razadata'])) {
            $rd = json_decode($r['razadata'], true);
            if (is_array($rd) && !empty($rd['date'])) $d = substr($rd['date'], 0, 10);
          }
          if (!empty($d)) {
            // expect Y-m-d; use HijriCalendar helper to get hijri parts
            $parts = $ci->HijriCalendar->get_hijri_parts_by_greg_date($d);
            if (!empty($parts) && !empty($parts['hijri_year'])) {
              $hijri_years[$parts['hijri_year']] = $parts['hijri_year'];
            }
          }
        }
        krsort($hijri_years);
        // build map of raza_id => hijri_year for client-side use
        $hijri_map = [];
        foreach ($raza as $r) {
          $d = '';
          if (!empty($r['miqaat_details'])) {
            $md = json_decode($r['miqaat_details'], true);
            if (is_array($md) && !empty($md['date'])) $d = substr($md['date'], 0, 10);
          }
          if (empty($d) && !empty($r['razadata'])) {
            $rd = json_decode($r['razadata'], true);
            if (is_array($rd) && !empty($rd['date'])) $d = substr($rd['date'], 0, 10);
          }
          $hyear = '';
          if (!empty($d)) {
            $parts = $ci->HijriCalendar->get_hijri_parts_by_greg_date($d);
            if (!empty($parts) && !empty($parts['hijri_year'])) $hyear = $parts['hijri_year'];
          }
          $hijri_map[$r['id']] = $hyear;
        }
        ?>

        <select onchange="updateTable();" name="year_filter" class="select mb-3" id="year_filter">
          <option value="" selected>All Hijri Years</option>
          <?php foreach ($hijri_years as $y): ?>
            <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
          <?php endforeach; ?>
        </select>

        <!-- <?php

              // Build Hijri months (1-12) using get_hijri_month()
              $ci = &get_instance();
              $ci->load->model('HijriCalendar');
              $hijri_months = $ci->HijriCalendar->get_hijri_month();
              ?>
        <select onchange="updateTable();" name="hijri_month_filter" class="select mb-3" id="hijri_month_filter">
          <option value="" selected>All Hijri Months</option>
          <?php foreach ($hijri_months as $m): ?>
            <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['hijri_month'], ENT_QUOTES, 'UTF-8'); ?></option>
          <?php endforeach; ?>
        </select>

        <select onchange="updateTable();" name="hijri_day_filter" class="select mb-3" id="hijri_day_filter">
          <option value="" selected>All Hijri Days</option>
          <?php for ($d = 1; $d <= 30; $d++): ?>
            <option value="<?php echo $d; ?>"><?php echo $d; ?></option>
          <?php endfor; ?>
        </select> -->



        <select onchange="updateTable();" name="sort" class="select mb-3" id="sort">
          <option value="" selected disabled>Sort</option>
          <option class="options" value="0">Name(A-Z)</option>
          <option class="options" value="1">Name(Z-A)</option>
          <option class="options" value="2">Event Date (New>Old)</option>
          <option class="options" value="3">Event Date (Old>New)</option>
          <option class="options" value="4">Create Date (New>Old)</option>
          <option class="options" value="5">Create Date (Old>New)</option>
          <option class="options" value="6">Clear</option>
        </select>
      </div>
    </div>
    <div class="table-responsive mb-5">
      <div class="table-container">
        <table class="table table-bordered text-center">
          <thead>
            <tr>
              <th class="sno">S.No.
                <span class="sort-icons" onclick="sortTable(0)">
                  <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                </span>
              </th>
              <!--<th class="sno">FMB Kitchen-->
              <!--    <span class="sort-icons" onclick="sortTable(3)">-->
              <!--        <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>-->
              <!--    </span>-->
              <!--</th>-->
              <th class="name">Name
                <span class="sort-icons" onclick="sortTable(1)">
                  <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                </span>
              </th>
              <th class="raza">Raza For
                <span class="sort-icons" onclick="sortTable(2)">
                  <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                </span>
              </th>
              <th class="eventdate">Event Date
                <span class="sort-icons" onclick="sortTable(3)">
                  <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                </span>
              </th>
              <th class="chat">Chat</th>
              <th class="approval_status">Status
                <span class="sort-icons" onclick="sortTable(5)">
                  <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                </span>
              </th>
              <th class="action">Action</th>
              <th class="created">Created
                <span class="sort-icons" onclick="sortTable(7)">
                  <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                </span>
              </th>
            </tr>
          </thead>
          <tbody id="datatable">
            <?php
            foreach ($raza as $key => $r) {
              // determine hijri year for this row (used for client-side filtering)
              $hijri_year_attr = '';
              $d = '';
              if (!empty($r['miqaat_details'])) {
                $md = json_decode($r['miqaat_details'], true);
                if (is_array($md) && !empty($md['date'])) $d = substr($md['date'], 0, 10);
              }
              if (empty($d) && !empty($r['razadata'])) {
                $rd = json_decode($r['razadata'], true);
                if (is_array($rd) && !empty($rd['date'])) $d = substr($rd['date'], 0, 10);
              }
              if (!empty($d)) {
                $parts = $ci->HijriCalendar->get_hijri_parts_by_greg_date($d);
                if (!empty($parts) && !empty($parts['hijri_year'])) $hijri_year_attr = $parts['hijri_year'];
              }
            ?>
              <tr data-hijri-year="<?php echo $hijri_year_attr; ?>">
                <td>
                  <?php echo $key + 1 ?>
                </td>
                <td>
                  <?php
                  // If this request is a Fala ni Niyaz (FNN), show that label instead of the user's name
                  $is_fnn = false;
                  if (!empty($r['miqaat_id']) && !empty($r['miqaat_details'])) {
                    $miq = json_decode($r['miqaat_details'], true);
                    if (is_array($miq)) {
                      // check assigned_to text
                      $mAssigned = strtolower($miq['assigned_to'] ?? '');
                      if (strpos($mAssigned, 'fnn') !== false || (strpos($mAssigned, 'fala') !== false && (strpos($mAssigned, 'niyaz') !== false || strpos($mAssigned, 'niaz') !== false))) {
                        $is_fnn = true;
                      }
                      // check top-level assign_type (e.g., 'Fala ni Niyaz')
                      if (!$is_fnn && !empty($miq['assign_type'])) {
                        $atop = strtolower($miq['assign_type']);
                        if (strpos($atop, 'fnn') !== false || (strpos($atop, 'fala') !== false && (strpos($atop, 'niyaz') !== false || strpos($atop, 'niaz') !== false))) {
                          $is_fnn = true;
                        }
                      }
                      // check each assignment entry for assign_type indicating FNN
                      if (!$is_fnn && !empty($miq['assignments']) && is_array($miq['assignments'])) {
                        foreach ($miq['assignments'] as $as) {
                          $asz = strtolower((string)($as['assign_type'] ?? $as['type'] ?? ''));
                          if (strpos($asz, 'fnn') !== false || (strpos($asz, 'fala') !== false && (strpos($asz, 'niyaz') !== false || strpos($asz, 'niaz') !== false))) {
                            $is_fnn = true;
                            break;
                          }
                        }
                      }
                    }
                  }
                  if (!$is_fnn && isset($r['razaType'])) {
                    $rt = strtolower($r['razaType']);
                    if (strpos($rt, 'fnn') !== false || (strpos($rt, 'fala') !== false && (strpos($rt, 'niyaz') !== false || strpos($rt, 'niaz') !== false))) {
                      $is_fnn = true;
                    }
                  }

                  if ($is_fnn) {
                    echo 'Fala ni Niyaz';
                  } else {
                    echo htmlspecialchars($r['user_name'], ENT_QUOTES, 'UTF-8');
                  }
                  ?>
                </td>
                <td>
                  <?php
                  if (!empty($r['miqaat_id']) && !empty($r['miqaat_details'])) {
                    $miqaat = json_decode($r['miqaat_details'], true);
                    if (is_array($miqaat)) {
                      $mName = htmlspecialchars($miqaat['name'] ?? '', ENT_QUOTES, 'UTF-8');
                      $mType = htmlspecialchars($miqaat['type'] ?? '', ENT_QUOTES, 'UTF-8');
                      $mAssigned = htmlspecialchars($miqaat['assigned_to'] ?? '', ENT_QUOTES, 'UTF-8');
                      $mDate = !empty($miqaat['date']) ? date('D, d M', strtotime($miqaat['date'])) : '';
                      $metaParts = array_filter([$mType, $mAssigned, $mDate], function ($v) {
                        return $v !== '' && $v !== null;
                      });
                      $meta = implode(' &middot; ', $metaParts);
                      echo "$mName <br><small class=\"text-muted\">$meta</small>";
                    } else {
                      echo htmlspecialchars($r['razaType'], ENT_QUOTES, 'UTF-8');
                    }
                  } else {
                    echo htmlspecialchars($r['razaType'], ENT_QUOTES, 'UTF-8');
                  }
                  ?>
                </td>
                <td>
                  <?php
                  // Gregorian date
                  $greg_date = '';
                  if (!empty($r['miqaat_id']) && !empty($r['miqaat_details'])) {
                    $miqaat_info = json_decode($r['miqaat_details'], true);
                    if (!empty($miqaat_info['date'])) {
                      $greg_date = $miqaat_info['date'];
                      echo date('D, d M', strtotime($greg_date));
                    }
                  } else {
                    $temp = json_decode($r['razadata'], true);
                    if (!empty($temp['date'])) {
                      $greg_date = $temp['date'];
                      echo date('D, d M', strtotime($greg_date));
                    }
                  }
                  // Hijri date below
                  if (!empty($greg_date)) {
                    $hijri = $ci->HijriCalendar->get_hijri_parts_by_greg_date(substr($greg_date, 0, 10));
                    if (!empty($hijri['hijri_day']) && !empty($hijri['hijri_month']) && !empty($hijri['hijri_year'])) {
                      $hijri_month_name = $ci->HijriCalendar->hijri_month_name($hijri['hijri_month']);
                      echo '<br><small class="text-muted">(' . $hijri['hijri_day'] . ' ' . $hijri_month_name . ' ' . $hijri['hijri_year'] . 'H)</small>';
                    }
                  }
                  ?>
                </td>
                <td>
                  <?php $chat_count = !empty($r['chat_count']) ? $r['chat_count'] : ''; ?>
                  <a href="<?php echo base_url('Accounts/chat/') . $r['id'] . '/anjuman'; ?>" class="chat-button">
                    Chat<?php echo $chat_count ? '<div class="chat-count">' . $chat_count . '</div>' : ''; ?>
                  </a>
                </td>
                <td class="status">
                  <div class="text-left">
                    <ul>
                      <?php if ($r['status'] == 0) {
                        echo '<div><strong style="color: orange;">Pending</strong></div>';
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
                  <div class="action-buttons">
                    <div class="button-group">
                      <button type="button" class="btn btn-sm btn-primary remove-form-row" onclick="approve_raza(<?php echo $r['id']; ?>);">
                        <i class="fa fa-circle-check"></i>
                      </button>
                      <button type="button" class="btn btn-sm btn-danger remove-form-row" onclick="reject_raza(<?php echo $r['id']; ?>);">
                        <i class="fa fa-circle-xmark"></i>
                      </button>
                      <button type="button" class="btn btn-sm btn-warning remove-form-row" onclick="deleteRaza(<?php echo $r['id']; ?>);">
                        <i class="fa fa-trash"></i>
                      </button>
                    </div>
                    <div class="view-link btn btn-sm btn-primary remove-form-row">
                      <a onclick="show_raza(<?php echo $r['id']; ?>);">
                        <span style=" cursor:pointer;">View</span>
                      </a>
                    </div>
                  </div>
                </td>
                <td>
                  <?php echo date('D, d M @ g:i a', strtotime($r['time-stamp'])) ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
          <tfoot></tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<div id="product-overlay"></div>
<div id="approve-form" class="query-form">
  <table id="details-table-approve" class="table"></table>
  <form class="approve" id="approve">
    <div class="form-group">
      <label for="remark" class="form-label">Remark (optional)</label>
      <textarea class="form-control" name="remark" id="remark" rows="5"
        style="max-width:100%; height:100%"></textarea>
    </div>
    <div class="submit">
      <button type="button" class="btn btn-danger w100percent-xs mbm-xs" onclick="clearForm();">Cancel</button>
      <button type="submit" class="btn btn-success w100percent-xs mbm-xs">Recommend</button>
    </div>
  </form>
</div>
<div id="reject-form" class="query-form">
  <table id="details-table-reject" class="table"></table>
  <form class="reject" id="reject">
    <div class="form-group">
      <label for="remark" class="form-label">Remark *</label>
      <textarea class="form-control" name="remark" required id="remark" rows="5"
        style="max-width:100%; height:100%"></textarea>
    </div>
    <div class="submit">
      <button type="button" class="btn btn-primary w100percent-xs mbm-xs" onclick="clearForm();">Cancel</button>
      <button type="submit" class="btn btn-danger w100percent-xs mbm-xs">Not Recommend</button>
    </div>
  </form>
</div>
<div id="show-form" class="query-form">
  <table id="details-table-show" class="table"></table>
  <form class="reject" id="show">
    <div class="submit">
      <button type="button" class="close-btn btn btn-primary w100percent-xs mbm-xs" onclick="clearForm();">Close</button>
    </div>
  </form>
</div>
<!-- Financials modal (rendered from JSON) -->
<div id="financials-modal" class="query-form" style="width:520px; max-width:95%; display:none;">
  <div id="financials-modal-content" style="padding:8px 12px;"></div>
</div>
<script>
  // Build as proper JSON to preserve nested structures (razadata, razafields, miqaat_details)

  // Build razas array with hijri_parts for each entry
  let razas = [
    <?php
    foreach ($raza as $r) {
      $d = '';
      if (!empty($r['miqaat_details'])) {
        $md = json_decode($r['miqaat_details'], true);
        if (is_array($md) && !empty($md['date'])) $d = substr($md['date'], 0, 10);
      }
      if (empty($d) && !empty($r['razadata'])) {
        $rd = json_decode($r['razadata'], true);
        if (is_array($rd) && !empty($rd['date'])) $d = substr($rd['date'], 0, 10);
      }
      $hijri_parts = null;
      if (!empty($d)) {
        $parts = $ci->HijriCalendar->get_hijri_parts_by_greg_date($d);
        if (!empty($parts['hijri_year']) && !empty($parts['hijri_month']) && !empty($parts['hijri_day'])) {
          $hijri_parts = [
            'year' => $parts['hijri_year'],
            'month' => $parts['hijri_month'],
            'day' => $parts['hijri_day']
          ];
        }
      }
      $r['hijri_parts'] = $hijri_parts;
      echo json_encode($r, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) . ",\n";
    }
    ?>
  ];
  let hijriMap = <?php echo json_encode($hijri_map ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

  // Expose Hijri month names for JS rendering of hijri date
  <?php
  $ci = isset($ci) ? $ci : get_instance();
  $ci->load->model('HijriCalendar');
  $hijri_months = $ci->HijriCalendar->get_hijri_month();
  $hijri_months_js = [];
  foreach ($hijri_months as $m) {
    $hijri_months_js[$m['id']] = $m['hijri_month'];
  }
  ?>
  window.hijriMonths = <?php echo json_encode($hijri_months_js, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

  function show_raza(id) {
    document.getElementById("show-form").style.display = "block";
    document.getElementById("product-overlay").style.display = "block";
    let raza = razas.find(e => e.id == id);
    otherdetails(raza, 'show');
  }

  function approve_raza(id) {
    // Open approve form; financials modal will appear when Recommend is clicked
    let raza = razas.find(e => e.id == id);
    // clear any previous hidden inputs
    $('#approve').find('input[name="raza_id"]').parent().remove();
    $('#approve').find('input[name="its_id"]').parent().remove();
    document.getElementById("approve-form").style.display = "block";
    document.getElementById("product-overlay").style.display = "block";
    const newInput = document.createElement("div");
    newInput.innerHTML = `<input type="text" hidden name="raza_id" value=${id} Required=true>`;
    document.getElementById("approve").appendChild(newInput);
    try {
      var its = raza.user_id || raza.userId || raza.ITS_ID || raza.its_id || raza.its || null;
      if (its) {
        const itsInput = document.createElement('div');
        itsInput.innerHTML = `<input type="text" hidden name="its_id" value="${its}" Required=true>`;
        document.getElementById('approve').appendChild(itsInput);
      }
    } catch (e) {}
    otherdetails(raza, 'approve');
  }

  function reject_raza(id) {
    document.getElementById("reject-form").style.display = "block";
    document.getElementById("product-overlay").style.display = "block";
    const newInput = document.createElement("div");
    newInput.innerHTML = `<input type="text" hidden name="raza_id" value=${id} Required=true>`;
    document.getElementById("reject").appendChild(newInput);
    let raza = razas.find(e => e.id == id);
    otherdetails(raza, 'reject');
  }

  function clearForm() {
    $('#approve')[0].reset();
    $('#reject')[0].reset();
    document.getElementById("approve-form").style.display = "none";
    document.getElementById("reject-form").style.display = "none";
    document.getElementById("show-form").style.display = "none";
    document.getElementById("product-overlay").style.display = "none";
  }

  function closeFinancialsModal() {
    document.getElementById('financials-modal').style.display = 'none';
    document.getElementById('financials-modal-content').innerHTML = '';
  }

  function formatINR(n) {
    // simple formatting without decimals
    return '₹' + (Math.round(n)).toLocaleString('en-IN');
  }

  function renderFinancialsModal(data, raza_id, raza) {
    var c = document.getElementById('financials-modal-content');
    var html = '';
    html += '<div style="display:flex;justify-content:space-between;align-items:center">';
    html += '<h5 style="margin:0">Pending Financial Dues</h5>';
    html += '<button onclick="closeFinancialsModal()" style="border:0;background:transparent;font-size:18px">&times;</button>';
    html += '</div>';
    html += '<div style="margin-top:8px;padding:8px;border-radius:4px;background:#fff3cd;border:1px solid #ffeeba;color:#856404">The member has pending dues. Please review before proceeding.</div>';
    html += '<table class="table" style="margin-top:12px;font-size:14px;width:100%">';
    html += '<thead><tr><th>Category</th><th style="text-align:right">Due</th></tr></thead>';
    html += '<tbody>';
    html += `<tr><td>FMB Takhmeen</td><td style="text-align:right;color:${data.fmb_due>0?'#dc3545':'#6c757d'}">${formatINR(data.fmb_due)}</td></tr>`;
    html += `<tr><td>Sabeel Takhmeen</td><td style="text-align:right;color:${data.sabeel_due>0?'#dc3545':'#6c757d'}">${formatINR(data.sabeel_due)}</td></tr>`;
    html += `<tr><td>General Contributions</td><td style="text-align:right;color:${data.gc_due>0?'#dc3545':'#6c757d'}">${formatINR(data.gc_due)}</td></tr>`;
    html += `<tr><td>Miqaat Invoices</td><td style="text-align:right;color:${data.miqaat_due>0?'#dc3545':'#6c757d'}">${formatINR(data.miqaat_due)}</td></tr>`;
    html += `<tr><td>Corpus Fund</td><td style="text-align:right;color:${data.corpus_due>0?'#dc3545':'#6c757d'}">${formatINR(data.corpus_due)}</td></tr>`;
    html += `<tr><th style="text-align:left">Total</th><th style="text-align:right;color:#dc3545">${formatINR(data.total_due)}</th></tr>`;
    html += '</tbody></table>';

    // Miqaat / Member invoices table (if any)
    if (Array.isArray(data.miqaat_invoices) && data.miqaat_invoices.length > 0) {
      html += '<h6 style="margin-top:8px">Miqaat / Member Invoices</h6>';
      html += '<div style="max-height:180px;overflow:auto;border-top:1px solid #eee;padding-top:8px">';
      html += '<table class="table table-sm" style="width:100%;font-size:13px">';
      html += '<thead><tr><th>Assigned to</th><th>Invoice</th><th style="text-align:right">Amount</th><th style="text-align:right">Paid</th><th style="text-align:right">Due</th></tr></thead>';
      html += '<tbody>';
      data.miqaat_invoices.forEach(function(row) {
        html += '<tr>';
        html += `<td>${(row.assigned_to||'')}</td>`;
        html += `<td>${(row.invoice||'')}</td>`;
        html += `<td style="text-align:right">${formatINR(row.amount)}</td>`;
        html += `<td style="text-align:right">${formatINR(row.paid)}</td>`;
        html += `<td style="text-align:right;color:#dc3545">${formatINR(row.due)}</td>`;
        html += '</tr>';
      });
      html += '</tbody></table></div>';
    }

    // Footer buttons
    html += '<div style="display:flex;justify-content:flex-end;gap:8px;margin-top:12px">';
    html += `<button class="btn btn-secondary" onclick="closeFinancialsModal()">Cancel</button>`;
    html += `<button class="btn btn-primary" onclick="proceedFromFinancials(${raza_id})">Proceed</button>`;
    html += '</div>';

    c.innerHTML = html;
    document.getElementById('financials-modal').style.display = 'block';
  }

  function proceedFromFinancials(raza_id) {
    // When user clicks Proceed in financials modal, submit the approve form
    try {
      closeFinancialsModal();
    } catch (e) {}
    // ensure raza_id exists in form
    $('#approve').find('input[name="raza_id"]').parent().remove();
    const newInput = document.createElement("div");
    newInput.innerHTML = `<input type="text" hidden name="raza_id" value=${raza_id} Required=true>`;
    document.getElementById("approve").appendChild(newInput);
    // submit via AJAX
    submitApproveForm($('#approve'));
  }

  function otherdetails(raza, action) {
    var table = document.getElementById(`details-table-${action}`);
    table.innerHTML = "";
    var tablehead = document.createElement('thead')
    tablehead.innerHTML = `<thead><tr><th scope=\"col\" colspan=\"2\" class=\"text-center\">Raza Details</th></tr></thead>`;
    table.appendChild(tablehead);
    var tablebody = document.createElement('tbody');
    let tbodydata = "";
    let razadata = JSON.parse(raza.razadata);
    let razafields = JSON.parse(raza.razafields);
    let raza_type_id = parseInt(raza.razaType_id || raza.raza_type_id);
    if (raza_type_id === 2 && raza.miqaat_details) {
      let miqaat_info = JSON.parse(raza.miqaat_details);
      tbodydata += `<tr><th scope=\"row\">Miqaat Name</th><td>${miqaat_info.name}</td></tr>`;
      tbodydata += `<tr><th scope=\"row\">Miqaat Type</th><td>${miqaat_info.type}</td></tr>`;
      // Format miqaat_info.date as dd-mm-yyyy
      let miqaatDateStr = '';
      if (miqaat_info.date) {
        let d = new Date(miqaat_info.date);
        let day = String(d.getDate()).padStart(2, '0');
        let month = String(d.getMonth() + 1).padStart(2, '0');
        let year = d.getFullYear();
        miqaatDateStr = `${day}-${month}-${year}`;
      }
      tbodydata += `<tr><th scope=\"row\">Miqaat Date</th><td>${miqaatDateStr}</td></tr>`;
      tbodydata += `<tr><th scope=\"row\">Assigned To</th><td>${miqaat_info.assigned_to}</td></tr>`;
      tbodydata += `<tr><th scope=\"row\">Status</th><td>${miqaat_info.status == 1 ? 'Active' : 'Inactive'}</td></tr>`;
      // If group assignment, show group leader and all members' names
      if (miqaat_info.assign_type === 'Group' && Array.isArray(miqaat_info.assignments) && miqaat_info.assignments.length > 0) {
        tbodydata += `<tr><th colspan=\"2\" class=\"text-center\">Group Leader</th></tr>`;
        tbodydata += `<tr><th scope=\"row\">Leader Name</th><td>${miqaat_info.group_leader_name ? miqaat_info.group_leader_name : ''} ${miqaat_info.group_leader_surname ? miqaat_info.group_leader_surname : ''}</td></tr>`;
        tbodydata += `<tr><th colspan=\"2\" class=\"text-center\">Group Members</th></tr>`;
        miqaat_info.assignments.forEach(function(member, idx) {
          tbodydata += `<tr><th scope=\"row\">Member ${idx + 1}</th><td>${member.member_first_name ? member.member_first_name : ''} ${member.member_surname ? member.member_surname : ''}</td></tr>`;
        });
      }
    } else {
      let k = 0;
      for (let key in razadata) {
        if (razafields.fields[k].type == 'select') {
          let options = razafields.fields[k].options;
          let value = options[razadata[key]];
          tbodydata += `<tr><th scope=\"row\">${razafields.fields[k].name}</th><td>${value.name}</td></tr>`;
        } else {
          tbodydata += `<tr><th scope=\"row\">${razafields.fields[k].name}</th><td>${razadata[key]}</td></tr>`;
        }
        k++;
      }
    }
    tablebody.innerHTML = tbodydata;
    table.appendChild(tablebody);
  }

  $(document).ready(function() {
    $('#approve').submit(function(event) {
      event.preventDefault();
      var raza_id = $(this).find('input[name="raza_id"]').val();
      var its = $(this).find('input[name="its_id"]').val();
      var hof = $(this).find('input[name="hof_id"]').val();
      var q = '';
      if (its) q = 'its_id=' + encodeURIComponent(its);
      else if (hof) q = 'hof_id=' + encodeURIComponent(hof);
      if (!q) {
        // No identifier; submit directly
        submitApproveForm($(this));
        return;
      }
      var url = `<?php echo base_url('anjuman/member_financials_json'); ?>?` + q;
      fetch(url, {
          credentials: 'same-origin'
        })
        .then(r => r.json())
        .then(function(data) {
          if (!data || !data.success) {
            // no data -> submit directly
            submitApproveForm($('#approve'));
            return;
          }
          renderFinancialsModal(data, raza_id, null);
        })
        .catch(function(e) {
          console.error('financials fetch error', e);
          submitApproveForm($('#approve'));
        });
    });

    window.submitApproveForm = function($form) {
      var formData = $form.serialize();
      $.ajax({
        type: 'POST',
        url: "<?php echo base_url('admin/approveRaza'); ?>",
        data: formData,
        success: function(response) {
          showSuccessMessage();
          clearForm();
          refresh();
        },
        error: function(error) {
          console.error('query submission failed');
        }
      });
    }

    function showSuccessMessage() {
      var toastMessage = $('#toast-message');
      toastMessage.show();
      setTimeout(function() {
        toastMessage.hide();
      }, 2000);
    }
  });
  $(document).ready(function() {
    $('#reject').submit(function(event) {
      event.preventDefault();

      var formData = $(this).serialize();

      $.ajax({
        type: 'POST',
        url: "<?php echo base_url('admin/rejectRaza'); ?>",
        data: formData,
        success: function(response) {
          showSuccessMessage();
          clearForm();
          refresh();
        },
        error: function(error) {
          console.error('query submission failed');
        }
      });
    });

    function showSuccessMessage() {
      var toastMessage = $('#toast-message');
      toastMessage.show();
      setTimeout(function() {
        toastMessage.hide();
      }, 2000);
    }
  });

  function redirectto(location) {
    window.location.href = '<?php echo base_url() ?>' + location;
  }

  function deleteRaza(id) {
    let check = confirm("Do You Want to Delete This Raza?");
    if (check) {
      window.location.href = '<?php echo base_url() ?>' + 'anjuman/DeleteRaza/' + id;
    }
  }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
  function refresh() {
    window.location.reload()
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
<script>
  let tem = document.getElementById("sort");
  // Default to Event Date (New > Old)
  tem.value = 2;
  // Preserve initial server-rendered table HTML as a safe fallback
  const _initialRazaTbodyHTML = (document.getElementById('datatable') || {
    innerHTML: ''
  }).innerHTML;
  updateTable();

  function updateTable() {
    var filterValue = document.getElementById("filter").value;
    var sortValue = document.getElementById("sort").value;
    var miqaatFilterElem = document.getElementById("miqaat_filter");
    var miqaatFilter = miqaatFilterElem ? miqaatFilterElem.value : "";
    var umoorFilterElem = document.getElementById("umoor_filter");
    var umoorFilter = umoorFilterElem ? umoorFilterElem.value : "";
    var yearFilterElem = document.getElementById("year_filter");
    var yearFilter = yearFilterElem ? yearFilterElem.value : "";
    var hijriMonthElem = document.getElementById("hijri_month_filter");
    var hijriMonth = hijriMonthElem ? hijriMonthElem.value : "";
    var hijriDayElem = document.getElementById("hijri_day_filter");
    var hijriDay = hijriDayElem ? hijriDayElem.value : "";
    updateTableContent(filterValue, sortValue, miqaatFilter, yearFilter, umoorFilter, hijriMonth, hijriDay);
  }

  function updateTableContent(filter, sort, miqaatFilter, yearFilter, umoorFilter, hijriMonth, hijriDay) {
    var tbody = document.getElementById('datatable');
    if (!tbody) return;
    // If the server did not provide a JS array, keep server-rendered rows
    if (!Array.isArray(razas) || razas.length === 0) {
      tbody.innerHTML = _initialRazaTbodyHTML;
      return;
    }
    // build into a fragment and only replace on success
    var frag = document.createDocumentFragment();
    var tempContainer = document.createElement('tbody');

    // Filter and sort your razas array based on the selected options
    var filteredAndSortedRazas = razas;

    // helper to safely parse JSON-ish fields
    function parseMaybe(val) {
      if (!val) return null;
      if (typeof val === 'object') return val;
      try {
        return JSON.parse(val);
      } catch (e) {
        return null;
      }
    }

    // Apply miqaat type filter if selected
    if (miqaatFilter && miqaatFilter !== "") {
      filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
        var m = parseMaybe(raza.miqaat_details) || {};
        var miqaatType = (m.type || raza.razaType || '').toString();
        return miqaatType.toLowerCase() === miqaatFilter.toLowerCase();
      });
    }


    // Apply Hijri year filter if selected (uses server-provided hijriMap)
    if (yearFilter && yearFilter !== "") {
      filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
        var hy = (hijriMap && hijriMap[raza.id]) ? hijriMap[raza.id].toString() : '';
        return hy === yearFilter;
      });
    }

    // Hijri month/day filter: parse hijri date from event date (server must provide hijriMap or parse client-side)
    if ((hijriMonth && hijriMonth !== "") || (hijriDay && hijriDay !== "")) {
      filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
        // Try to get gregorian date from miqaat_details or razadata
        function parseMaybe(val) {
          if (!val) return null;
          if (typeof val === 'object') return val;
          try {
            return JSON.parse(val);
          } catch (e) {
            return null;
          }
        }
        var d = '';
        var m = parseMaybe(raza.miqaat_details);
        if (m && m.date) d = m.date;
        if (!d) {
          var rd = parseMaybe(raza.razadata);
          if (rd && rd.date) d = rd.date;
        }
        if (!d) return false;
        // Use a simple Hijri conversion if available, else skip
        // If server provides hijriMap[raza.id] as {year,month,day}, use it
        var hijri = (raza.hijri_parts || null);
        if (!hijri && window.hijriPartsMap && window.hijriPartsMap[raza.id]) hijri = window.hijriPartsMap[raza.id];
        // If not, try to parse from data-hijri-date attribute (not present in this table)
        // If not, skip filtering
        if (!hijri) return true;
        if (hijriMonth && hijriMonth !== "" && parseInt(hijri.month) !== parseInt(hijriMonth)) return false;
        if (hijriDay && hijriDay !== "" && parseInt(hijri.day) !== parseInt(hijriDay)) return false;
        return true;
      });
    }

    // Apply Umoor filter if selected
    if (umoorFilter && umoorFilter !== "") {
      filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
        var u = (raza.umoor || '').toString();
        return u.toLowerCase() === umoorFilter.toLowerCase();
      });
    }

    if (filter !== "") {
      switch (filter) {
        case "approved":
          filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
            return raza.status == 2;
          });
          break;
        case "recommended":
          filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
            return raza.status == 1;
          });
          break;
        case "pending":
          filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
            return raza.status == 0;
          });
          break;
        case "rejected":
          filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
            return raza.status == 3;
          });
          break;
        case "notrecommended":
          filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
            return raza.status == 4;
          });
          break;
        case "clear":
          refresh();
          break;

        default:
          filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
            return raza.razaType === filter;
          });
          break;
      }

    }

    if (sort !== "") {
      filteredAndSortedRazas.sort(function(a, b) {
        // Implement your sorting logic here
        switch (parseInt(sort)) {
          case 0:
            return a.user_name.localeCompare(b.user_name);
          case 1:
            return b.user_name.localeCompare(a.user_name);
          case 4:
            // Implement sorting by date (New > Old)
            return new Date(b['time-stamp']) - new Date(a['time-stamp']);
          case 5:
            // Implement sorting by date (Old > New)
            return new Date(a['time-stamp']) - new Date(b['time-stamp']);
          case 2:
            // Implement sorting by event date (New > Old)
            return new Date(getEventDate(b.razadata, b.miqaat_details)) - new Date(getEventDate(a.razadata, a.miqaat_details));
          case 3:
            // Implement sorting by event date (Old > New)
            return new Date(getEventDate(a.razadata, a.miqaat_details)) - new Date(getEventDate(b.razadata, b.miqaat_details));
          case 6:
            refresh();
          default:
            return 0;
        }
      });
    }

    try {
      // Populate the table with the filtered and sorted data into tempContainer
      for (var i = 0; i < filteredAndSortedRazas.length; i++) {
        var raza = filteredAndSortedRazas[i];
        var chatCount = raza.chat_count && raza.chat_count > 0 ? raza.chat_count : '';
        var chatCountHTML = chatCount ? `<div class="chat-count">${chatCount}</div>` : '';
        var chatURL = `<?php echo base_url('Accounts/chat/') ?>${raza.id}<?php echo "/anjuman"; ?>`;
        var row = document.createElement("tr");
        // Determine display name: if this is a Fala ni Niyaz (FNN) show label instead of user name
        var nameDisplay = raza['user_name'] || '';
        try {
          var _m = parseMaybe(raza.miqaat_details) || {};
          var miqAssignedTo = (_m.assigned_to || '').toString().toLowerCase();
          var miqType = (_m.type || '').toString().toLowerCase();
          var miqName = (_m.name || '').toString().toLowerCase();
          var miqAssignType = (_m.assign_type || _m.assignType || '').toString().toLowerCase();
          var rt = (raza.razaType || '').toString().toLowerCase();
          var isFnn = false;
          var checkFnn = function(s) {
            if (!s) return false;
            if (s.indexOf('fnn') !== -1) return true;
            if (s.indexOf('fala') !== -1 && (s.indexOf('niyaz') !== -1 || s.indexOf('niaz') !== -1)) return true;
            return false;
          };
          if (checkFnn(miqAssignedTo) || checkFnn(miqType) || checkFnn(miqName) || checkFnn(miqAssignType) || checkFnn(rt)) {
            isFnn = true;
          }
          // check assignments array
          if (!isFnn && Array.isArray(_m.assignments)) {
            for (var ai = 0; ai < _m.assignments.length; ai++) {
              var as = _m.assignments[ai] || {};
              var ast = (as.assign_type || as.type || '').toString().toLowerCase();
              if (checkFnn(ast)) {
                isFnn = true;
                break;
              }
            }
          }
          if (isFnn) nameDisplay = 'Fala ni Niyaz';
        } catch (e) {
          // ignore
        }

        row.innerHTML = `
            <td>${i + 1}</td>
            <td>${nameDisplay}</td>
            <td>${formateRazaType(raza)}</td>
            <td>${formatEventDate(raza)}</td>
            <td><a href="${chatURL}" class="chat-button">Chat${chatCountHTML}</a></td>
            <td>${getStatusHTML(raza)}</td>
            <td><span class="action_btn">${getActionHTML(raza)}</span></td>
            <td>${formatDate(raza['time-stamp'])}</td>
        `;
        tempContainer.appendChild(row);
      }
      // Replace existing tbody content on success
      tbody.innerHTML = '';
      // move children from tempContainer to tbody
      while (tempContainer.firstChild) tbody.appendChild(tempContainer.firstChild);
    } catch (e) {
      console.error('updateTableContent error', e);
      // restore server-rendered rows so page doesn't appear empty
      tbody.innerHTML = _initialRazaTbodyHTML;
    }



  }

  function formatSabil(raza) {
    if (raza['sabil'] != '') {
      if (raza['sabil']) {
        return 'yes';
      } else {
        return 'no';
      }
    } else {
      return ""
    }
  }
  // function formatFmbTameer(raza){
  //     if(raza['fmbtameer'] != ''){
  //         if(raza['fmbtameer']){
  //         return 'yes';
  //     }else{
  //         return 'no';
  //     }
  //     }else{
  //         return ""
  //     }
  // }
  function formatFmb(raza) {
    if (raza['fmb'] != '') {
      if (raza['fmb']) {
        return 'yes';
      } else {
        return 'no';
      }
    } else {
      return ""
    }
  }

  function formateRazaType(raza) {
    const parseMaybe = (val) => {
      if (!val) return null;
      if (typeof val === 'object') return val;
      try {
        return JSON.parse(val);
      } catch (e) {
        return null;
      }
    };
    // If miqaat present, show details similar to server rendering
    if (raza.miqaat_id && raza.miqaat_details) {
      const m = parseMaybe(raza.miqaat_details) || {};
      const date = m.date ? new Date(m.date).toLocaleDateString('en-US', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      }) : '';
      const name = m.name || '';
      const type = m.type || '';
      const assigned = m.assigned_to || '';
      const parts = [type, assigned].filter(Boolean);
      return `${name}<br><small style="color: #6c757d;">${parts.join(' · ')}</small>`;
    }
    let data = parseMaybe(raza.razadata) || {};
    let rf = parseMaybe(raza.razafields) || {};
    let razafields = rf.fields || [];

    // Logic for 'raza-purpose' can be included if needed
    // if (data['raza-purpose']) {
    //     let k = razafields.find(e => {
    //         let name = e.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
    //         return name === 'raza-purpose';
    //     });
    //     let value = k.options[data['raza-purpose']];
    //     return `${raza.razaType}<br/> <span style='color:grey'>(${value.name})</span>`;
    // } else {
    //     return `${raza.razaType} (${raza.umoor || ''})`; // Add raza.umoor with razaType
    // }

    // Return razaType and umoor
    return `${raza.umoor ? raza.umoor + '\n' : ''}(${raza.razaType || ''})`;

  }

  function formatDate(dateString) {
    // Implement date formatting logic if needed
    const options = {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
      second: 'numeric',
      hour12: true
    };
    return new Date(dateString).toLocaleDateString('en-US', options);
  }

  function formatEventDate(raza) {
    const parseMaybe = (val) => {
      if (!val) return null;
      if (typeof val === 'object') return val;
      try {
        return JSON.parse(val);
      } catch (e) {
        return null;
      }
    };
    let data = parseMaybe(raza.razadata) || {}
    let rf = parseMaybe(raza.razafields) || {}
    let razafields = rf.fields || []
    let gregDate = '';
    let gregDateStr = '';
    const options = {
      weekday: 'short',
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    };
    if (raza.miqaat_id && raza.miqaat_details) {
      let miqaat_info = parseMaybe(raza.miqaat_details) || {};
      gregDate = miqaat_info.date;
      if (gregDate) {
        gregDateStr = new Date(gregDate).toLocaleDateString('en-US', options);
      }
    } else {
      gregDate = data.date;
      if (gregDate) {
        gregDateStr = new Date(gregDate).toLocaleDateString('en-US', options);
      }
    }
    // Hijri date from hijri_parts (provided by server)
    let hijriStr = '';
    if (raza.hijri_parts && raza.hijri_parts.year && raza.hijri_parts.month && raza.hijri_parts.day) {
      // Use hijri_months array if available, else fallback to number
      let hijriMonthName = '';
      if (window.hijriMonths && window.hijriMonths[raza.hijri_parts.month]) {
        hijriMonthName = window.hijriMonths[raza.hijri_parts.month];
      } else {
        // fallback: just show month number
        hijriMonthName = 'Month ' + raza.hijri_parts.month;
      }
      hijriStr = `<br><small class=\"text-muted\">(${raza.hijri_parts.day} ${hijriMonthName} ${raza.hijri_parts.year}H)</small>`;
    }
    if (gregDateStr) {
      return gregDateStr + hijriStr;
    } else {
      return '';
    }
  }

  function getStatusHTML(raza) {
    let statusHTML = '<div class="text-left">';

    if (raza['status'] == 0) {
      statusHTML += '<div><strong style="color: orange;">Pending</strong></div>';
    } else if (raza['status'] == 1) {
      statusHTML += '<div><strong style="color: blue;">Recommended</strong></div>';
    } else if (raza['status'] == 2) {
      statusHTML += '<div><strong style="color: limegreen;">Approved</strong></div>';
    } else if (raza['status'] == 3) {
      statusHTML += '<div><strong style="color: red;">Rejected</strong></div>';
    } else if (raza['status'] == 4) {
      statusHTML += '<div><strong style="color: blue;">Not Recommended</strong></div>';
    }

    statusHTML += '<ul><li>';
    if (raza['coordinator-status'] == 0) {
      statusHTML += '<div>Jamat <i class="fa-solid fa-clock" style="color: #fff700;"></i></div>';
    } else if (raza['coordinator-status'] == 1) {
      statusHTML += '<div>Jamat <i class="fa-solid fa-circle-check" style="color: limegreen;"></i></div>';
    } else if (raza['coordinator-status'] == 2) {
      statusHTML += '<div>Jamat <i class="fa-solid fa-circle-xmark" style="color: red;"></i></div>';
    }
    statusHTML += '</li>';

    statusHTML += '<li>';
    if (raza['Janab-status'] == 0) {
      statusHTML += '<div>Amil Saheb <i class="fa-solid fa-clock" style="color: #fff700;"></i></div>';
    } else if (raza['Janab-status'] == 1) {
      statusHTML += '<div>Amil Saheb <i class="fa-solid fa-circle-check" style="color: limegreen;"></i></div>';
    } else if (raza['Janab-status'] == 2) {
      statusHTML += '<div>Amil Saheb <i class="fa-solid fa-circle-xmark" style="color: red;"></i></div>';
    }
    statusHTML += '</li></ul></div>';

    return statusHTML;
  }

  function getActionHTML(raza) {
    // Implement your action HTML generation logic
    // You can use the same logic you have in your PHP code

    let actionHTML = '';
    if (raza['Janab-status'] == 0) {
      actionHTML = `
        <div class="action-buttons">
            <div class="button-group">
                <button type="button" class="btn btn-sm btn-primary remove-form-row" onclick="approve_raza(${raza['id']});">
                    <i class="fa fa-circle-check"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger remove-form-row" onclick="reject_raza(${raza['id']});">
                    <i class="fa fa-circle-xmark"></i>
                </button>
                <button type="button" class="btn btn-sm btn-warning remove-form-row" onclick="deleteRaza(${raza['id']});">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
            <div class="view-link btn btn-sm btn-primary remove-form-row">
                <a onclick="show_raza(${raza['id']});">
                    <span style=" cursor:pointer;">View</span>
                </a>
            </div>
        </div>
    `;
    } else {
      actionHTML = `
        <div class="view-link">
          <button type="button" class="btn btn-sm btn-primary remove-form-row" onclick="show_raza(${raza['id']});">View</button>
        </div>
      `;
    }

    return actionHTML;
  }

  function getEventDate(razadata, miqaat_details = {}) {
    // Extract event date from razadata or miqaat_details; both may be object or JSON string
    const parseMaybe = (val) => {
      if (!val) return null;
      if (typeof val === 'object') return val;
      try {
        return JSON.parse(val);
      } catch (e) {
        return null;
      }
    };
    let data = parseMaybe(razadata) || {};
    if (data.date) {
      return new Date(data.date);
    } else if (miqaat_details) {
      let miqaat_info = parseMaybe(miqaat_details) || {};
      return new Date(miqaat_info.date);
    } else {
      return null;
    }
  }
</script>
<script>
  function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.querySelector("table");
    switching = true;
    dir = "asc"; // Set the sorting direction to ascending

    // Make a loop that will continue until no switching has been done
    while (switching) {
      switching = false;
      rows = table.rows;

      // Loop through all table rows (except the first, which contains table headers)
      for (i = 1; i < (rows.length - 1); i++) {
        shouldSwitch = false;

        // Get the two elements you want to compare, one from current row and one from the next
        x = rows[i].getElementsByTagName("TD")[n];
        y = rows[i + 1].getElementsByTagName("TD")[n];

        // Check if the two rows should switch place, based on the direction, asc or desc
        if (dir === "asc") {
          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
            // If so, mark as a switch and break the loop
            shouldSwitch = true;
            break;
          }
        } else if (dir === "desc") {
          if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
            // If so, mark as a switch and break the loop
            shouldSwitch = true;
            break;
          }
        }
      }
      if (shouldSwitch) {
        // If a switch has been marked, make the switch and mark that a switch has been done
        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        switching = true;
        // Each time a switch is done, increase this count by 1
        switchcount++;
      } else {
        // If no switching has been done AND the direction is "asc", set the direction to "desc" and run the loop again
        if (switchcount === 0 && dir === "asc") {
          dir = "desc";
          switching = true;
        }
      }
    }
  }
</script>
<script>
  $(document).ready(function() {
    if ($.fn && $.fn.DataTable) {
      $('.table').DataTable();
    }
  });

  $(document).ready(function() {
    $('.close-btn').click(function(e) {
      e.preventDefault();
    });
  });
</script>