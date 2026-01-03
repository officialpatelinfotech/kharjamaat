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


  .better-dashboard {
    display: flex;
    justify-content: center;
    gap: 2.5rem;
    margin: 30px auto 20px auto;
    flex-wrap: wrap;
  }

  .better-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.08), 0 1.5px 6px 0 rgba(0, 0, 0, 0.04);
    padding: 1.2rem 1.7rem 1.2rem 1.2rem;
    min-width: 220px;
    max-width: 320px;
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1.1rem;
    transition: box-shadow 0.2s, transform 0.2s;
    position: relative;
    cursor: pointer;
    border: none;
  }

  .better-card:hover {
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.16), 0 3px 12px 0 rgba(0, 0, 0, 0.08);
    transform: translateY(-4px) scale(1.03);
  }

  .better-card .better-icon {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.1rem;
    color: #fff;
    box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.08);
    margin-bottom: 0;
    flex-shrink: 0;
  }

  .better-card.pending .better-icon {
    background: linear-gradient(135deg, #ffe066 60%, #ffd700 100%);
    color: #bfa100;
  }

  .better-card.completed .better-icon {
    background: linear-gradient(135deg, #66cdaa 60%, #43b97f 100%);
    color: #1e6b4c;
  }

  .better-card.processing .better-icon {
    background: linear-gradient(135deg, #afeeee 60%, #4fd1c5 100%);
    color: #1b6e6e;
  }

  .better-card .better-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    gap: 0.2rem;
  }

  .better-label {
    font-size: 1.08rem;
    color: #444;
    font-weight: 500;
    letter-spacing: 0.01em;
    margin-bottom: 0;
    text-align: left;
  }

  .better-value {
    font-size: 2.3rem;
    font-weight: 700;
    color: #222;
    margin-top: 0.1rem;
    text-align: left;
    letter-spacing: 0.01em;
  }

  @media (max-width: 900px) {
    .better-dashboard {
      gap: 1.2rem;
    }

    .better-card {
      min-width: 120px;
      max-width: 160px;
      padding: 1.3rem 1rem 1rem 1rem;
    }

    .better-card .better-icon {
      font-size: 1.5rem;
      width: 38px;
      height: 38px;
    }

    .better-value {
      font-size: 1.4rem;
    }
  }

  @media (max-width: 600px) {
    .better-dashboard {
      flex-direction: column;
      align-items: center;
      gap: 1.2rem;
    }

    .better-card {
      width: 100%;
      min-width: 0;
      max-width: 100%;
    }
  }

  .card-text {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 15px;
  }

  .btn {
    /*background-color: #007bff;*/
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .btn:hover {
    background-color: #0056b3;
  }

  .event-list {
    max-height: 50px;
    /* Height to display only two events initially */
    overflow: hidden;
    transition: max-height 0.3s ease;
  }

  .event-list-content {
    padding: 0;
    margin: 0;
  }

  .event-list:hover {
    max-height: 200px;
    /* Height to display more events on hover */
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
<div class="margintopcontainer pt-4 mx-3 pb-4">
  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("amilsaheb"); ?>" class="btn btn-outline-secondary bg-dark"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <div class="ml-1 mr-1">
    <p class="h4 text-center mt-5" style="color: goldenrod; text-transform: uppercase;"><?php echo $umoor ?></p>
    <div class="container">
      <div class="dashboard-container better-dashboard">
        <div class="better-card pending">
          <div class="better-icon"><i class="fas fa-clock"></i></div>
          <div class="better-content">
            <div class="better-label">Pending Tasks</div>
            <div class="better-value"><?php echo $janab_status_0_count ?></div>
          </div>
        </div>
        <div class="better-card completed">
          <div class="better-icon"><i class="fas fa-check-circle"></i></div>
          <div class="better-content">
            <div class="better-label">Completed Tasks</div>
            <div class="better-value"><?php echo $janab_status_1_count ?></div>
          </div>
        </div>
        <div class="better-card processing">
          <div class="better-icon"><i class="fas fa-sync"></i></div>
          <div class="better-content">
            <div class="better-label">Processing Tasks</div>
            <div class="better-value"><?php echo $coordinator_status_0_count ?></div>
          </div>
        </div>
      </div>

      <!-- <script>
                    // Generate random dates for the dummy data
                    function generateRandomDate() {
                        const startDate = new Date();
                        const endDate = new Date();
                        endDate.setDate(endDate.getDate() + 30); // Events within the next 30 days
                        return new Date(startDate.getTime() + Math.random() * (endDate.getTime() - startDate.getTime()));
                    }

                    // Format date as "Month Day, Year"
                    function formatDate(date) {
                        const options = {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        };
                        return date.toLocaleDateString('en-US', options);
                    }

                    // Update event dates with dummy data
                    document.getElementById('event1-date').textContent = formatDate(generateRandomDate());
                    document.getElementById('event2-date').textContent = formatDate(generateRandomDate());
                    document.getElementById('event3-date').textContent = formatDate(generateRandomDate());
                    document.getElementById('event4-date').textContent = formatDate(generateRandomDate());
                </script> -->



    </div>
    <div class="row m-2 mt-md-5">
      <form class="form-inline my-2 my-lg-0 w-100">
        <div class="input-group">
          <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="razaSearchInput">
          <div class="input-group-append">
            <span class="input-group-text">
              <i class="fa fa-search"></i>
            </span>
          </div>
        </div>
        <a class="form-control btn btn-success my-2 my-lg-0 ml-auto" onclick="refresh();">Refresh</a>
      </form>
    </div>
    <div class="row d-flex justify-content-between border rounded p-2 p-md-3 m-2 mt-md-3">
      <select onchange="updateTable();" name="filter" class="select mb-3" id="filter">
        <option value="" selected disabled>Filter</option>
        <!-- <?php
              foreach ($razatype as $key => $value) { ?>
            <option class="options" value="<?php echo $value['name'] ?>">
              <?php echo $value['name'] ?>
            </option>
          <?php
              } ?> -->
        <option class="options" value="pending">Pending</option>
        <option class="options" value="approved">Approved</option>
        <option class="options" value="recommended">Recommended</option>
        <option class="options" value="notrecommended">Not Recommended</option>
        <option class="options" value="rejected">Rejected</option>
        <option class="options" value="clear">Clear</option>
      </select>
      <?php if (empty($umoor) || $umoor !== '12 Umoor Raza Applications'): ?>
        <?php if (empty($umoor) || (stripos($umoor, 'Kaaraj') === false && $umoor !== '12 Umoor Raza Applications')): ?>
          <select onchange="updateTable();" name="miqaat_filter" class="select mb-3" id="miqaat_filter">
            <option value="" selected>All Miqaat Types</option>
            <option class="options" value="Shehrullah">Shehrullah</option>
            <option class="options" value="Ashara">Ashara</option>
            <option class="options" value="General">General</option>
            <option class="options" value="Ladies">Ladies</option>
          </select>
        <?php elseif ($umoor === '12 Umoor Raza Applications'): ?>
          <?php
          // Build distinct umoor categories from $razatype
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
        <?php else: ?>
          <!-- Kaaraj view: miqaat filter intentionally hidden -->
        <?php endif; ?>
      <?php else: ?>
        <?php
        // Build distinct umoor categories from $razatype
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
          $parts = $ci->HijriCalendar->get_hijri_parts_by_greg_date($d);
          if (!empty($parts) && !empty($parts['hijri_year'])) {
            $hijri_years[$parts['hijri_year']] = $parts['hijri_year'];
          }
        }
      }
      krsort($hijri_years);
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
  <div class="table-responsive mt-5 mb-5">
    <div class="table-container">
      <!-- <?php echo "<pre>";
            print_r($raza);
            echo "</pre>"; ?> -->
      <table class="table table-bordered text-center">
        <thead>
          <tr>
            <th class="sno">S.No.
              <span class="sort-icons" onclick="sortTable(0)">
                <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
              </span>
            </th>
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
              <span class="sort-icons" onclick="sortTable(8)">
                <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
              </span>
            </th>
          </tr>
        </thead>
        <tbody id="datatable">
          <?php
          foreach ($raza as $key => $r) {
            // compute hijri year for this row
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
                if (!empty($r['miqaat_details'])) {
                  $miq = json_decode($r['miqaat_details'], true);
                  if (is_array($miq)) {
                    $miq_type = strtolower($miq['type'] ?? '');
                    $miq_name = strtolower($miq['name'] ?? '');
                    if (strpos($miq_type, 'fnn') !== false || (strpos($miq_type, 'fala') !== false && (strpos($miq_type, 'niyaz') !== false || strpos($miq_type, 'niaz') !== false))) {
                      $is_fnn = true;
                    }
                    if (!$is_fnn && (strpos($miq_name, 'fnn') !== false || (strpos($miq_name, 'fala') !== false && (strpos($miq_name, 'niyaz') !== false || strpos($miq_name, 'niaz') !== false)))) {
                      $is_fnn = true;
                    }
                    // check top-level assign_type
                    if (!$is_fnn && !empty($miq['assign_type'])) {
                      $atop = strtolower($miq['assign_type']);
                      if (strpos($atop, 'fnn') !== false || (strpos($atop, 'fala') !== false && (strpos($atop, 'niyaz') !== false || strpos($atop, 'niaz') !== false))) {
                        $is_fnn = true;
                      }
                    }
                    // check assignments entries
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
                <?php echo $r['razaType'] ?>
              </td>
              <td>
                <?php $temp = json_decode($r['razadata'], true);
                if (!empty($temp['date'])) {
                  echo date('D, d M ', strtotime($temp['date']));
                } ?>
              </td>
              <td>
                <?php $chat_count = !empty($r['chat_count']) ? $r['chat_count'] : ''; ?>
                <a href="<?php echo base_url('Accounts/chat/') . $r['id'] . '/amilsaheb'; ?>" class="chat-button">
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
                <button type="button" class="btn btn-sm btn-primary remove-form-row" onclick="approve_raza(<?php echo $r['id'] ?>);"><i class="fa fa-circle-check"></i></button>
                <button type="button" class="btn btn-sm btn-danger remove-form-row" onclick="reject_raza(<?php echo $r['id'] ?>);"><i class="fa fa-circle-xmark"></i></button>
                <button type="button" class="btn btn-sm btn-danger remove-form-row" onclick="redirectto(<?php echo 'amilsaheb/DeleteRaza/' . $r['id'] ?>);"><i class="fa fa-circle-xmark"></i></button>
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
      <textarea class="form-control" name="remark" id="remark" rows="5" style="max-width:100%; height:100%"></textarea>
    </div>
    <div class="submit">
      <button class="btn btn-danger w100percent-xs mbm-xs" onclick="clearForm();">Cancel</button>
      <button type="submit" class="btn btn-success w100percent-xs mbm-xs">Approve</button>
    </div>
  </form>
</div>
<div id="reject-form" class="query-form">
  <table id="details-table-reject" class="table"></table>
  <form class="reject" id="reject">
    <div class="form-group">
      <label for="remark" class="form-label">Remark *</label>
      <textarea class="form-control" name="remark" required id="remark" rows="5" style="max-width:100%; height:100%"></textarea>
    </div>
    <div class="submit">
      <button class="btn btn-primary w100percent-xs mbm-xs" onclick="clearForm();">Cancel</button>
      <button type="submit" class="btn btn-danger w100percent-xs mbm-xs">Reject</button>
    </div>
  </form>
</div>
<div id="show-form" class="query-form">
  <table id="details-table-show" class="table"></table>
  <form class="reject" id="show">
    <div class="submit">
      <button class="btn btn-primary w100percent-xs mbm-xs" onclick="clearForm();">Close</button>
    </div>
  </form>
</div>
<script>
  // Build razas as a proper JSON array to prevent parsing issues
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
  let hijriMap = <?php
                  // build map of raza id => hijri year for JS
                  $hmap = [];
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
                    $hmap[$r['id']] = $hyear;
                  }
                  echo json_encode($hmap, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
                  ?>;

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
    let raza = razas.find(e => e.id == id)
    otherdetails(raza, 'show')
  }

  function approve_raza(id) {
    document.getElementById("approve-form").style.display = "block";
    document.getElementById("product-overlay").style.display = "block";
    const newInput = document.createElement("div");
    newInput.innerHTML = `<input type="text" hidden name="raza_id" value=${id} Required=true>`;
    document.getElementById("approve").appendChild(newInput);
    let raza = razas.find(e => e.id == id)
    otherdetails(raza, 'approve')
  }

  function reject_raza(id) {
    document.getElementById("reject-form").style.display = "block";
    document.getElementById("product-overlay").style.display = "block";
    const newInput = document.createElement("div");
    newInput.innerHTML = `<input type="text" hidden name="raza_id" value=${id} Required=true>`;
    document.getElementById("reject").appendChild(newInput);
    let raza = razas.find(e => e.id == id)
    otherdetails(raza, 'reject')
  }

  function clearForm() {
    $('#approve')[0].reset();
    $('#reject')[0].reset();
    document.getElementById("approve-form").style.display = "none";
    document.getElementById("reject-form").style.display = "none";
    document.getElementById("show-form").style.display = "none";
    document.getElementById("product-overlay").style.display = "none";
    event.preventDefault();
  }

  function otherdetails(raza, action) {
    var table = document.getElementById(`details-table-${action}`);
    table.innerHTML = "";
    var tablehead = document.createElement('thead')
    tablehead.innerHTML = `<thead><tr><th scope="col" colspan="2" class="text-center">Raza Details</th></tr></thead>`
    table.appendChild(tablehead)
    var tablebody = document.createElement('tbody');
    let tbodydata = "";
    const parseMaybe = (val) => {
      if (!val) return null;
      if (typeof val === 'object') return val;
      try {
        return JSON.parse(val);
      } catch (e) {
        return null;
      }
    };
    let razadata = parseMaybe(raza.razadata);
    let razafields = parseMaybe(raza.razafields);
    let raza_type_id = parseInt(raza.razaType_id || raza.raza_type_id);

    if (raza_type_id === 2 && raza.miqaat_details) {
      let miqaat_info = parseMaybe(raza.miqaat_details) || {};
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
      for (let key in (razadata || {})) {
        if (razafields && razafields.fields && razafields.fields[k] && razafields.fields[k].type == 'select') {
          let options = razafields.fields[k].options
          let value = options[razadata[key]]
          tbodydata += `<tr><th scope="row">${razafields.fields[k].name}</th><td>${value.name}</td></tr>`
        } else {
          const fname = (razafields && razafields.fields && razafields.fields[k]) ? razafields.fields[k].name : key;
          tbodydata += `<tr><th scope="row">${fname}</th><td>${razadata ? razadata[key] : ''}</td></tr>`
        }
        k++;
      }
    }
    tablebody.innerHTML = tbodydata
    table.appendChild(tablebody)
  }
  $(document).ready(function() {
    $('#approve').submit(function(event) {
      event.preventDefault();

      var formData = $(this).serialize();

      $.ajax({
        type: 'POST',
        url: "<?php echo base_url('amilsaheb/approveRaza'); ?>",
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
  $(document).ready(function() {
    $('#reject').submit(function(event) {
      event.preventDefault();

      var formData = $(this).serialize();

      $.ajax({
        type: 'POST',
        url: "<?php echo base_url('amilsaheb/rejectRaza'); ?>",
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
      window.location.href = '<?php echo base_url() ?>' + 'amilsaheb/DeleteRaza/' + id;
    }
  }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
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
  tem.value = 2;
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
    tbody.innerHTML = "";

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

    // Hijri month/day filter: parse hijri_parts from raza
    if ((hijriMonth && hijriMonth !== "") || (hijriDay && hijriDay !== "")) {
      filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
        var hijri = (raza.hijri_parts || null);
        if (!hijri) return true;
        if (hijriMonth && hijriMonth !== "" && parseInt(hijri.month) !== parseInt(hijriMonth)) return false;
        if (hijriDay && hijriDay !== "" && parseInt(hijri.day) !== parseInt(hijriDay)) return false;
        return true;
      });
    }

    // Apply Umoor filter (for 12 Umoor view) if selected
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
          case 7:
            // Sort by miqaat date (New > Old)
            return new Date(getMiqaatDate(b)) - new Date(getMiqaatDate(a));
          case 8:
            // Sort by miqaat date (Old > New)
            return new Date(getMiqaatDate(a)) - new Date(getMiqaatDate(b));
          case 6:
            refresh();
          default:
            return 0;
        }
      });
    }

    // Populate the table with the filtered and sorted data
    for (var i = 0; i < filteredAndSortedRazas.length; i++) {
      var raza = filteredAndSortedRazas[i];
      var chatCount = raza.chat_count && raza.chat_count > 0 ? raza.chat_count : '';
      var chatCountHTML = chatCount ? `<div class="chat-count">${chatCount}</div>` : '';
      var chatURL = `<?php echo base_url('Accounts/chat/') ?>${raza.id}<?php echo "/amilsaheb"; ?>`;
      var row = document.createElement("tr");
      // Determine display name: if this is a Fala ni Niyaz (FNN) show label instead of user name
      var nameDisplay = raza['user_name'] || '';
      try {
        var _m = (function(v) {
          if (!v) return null;
          if (typeof v === 'object') return v;
          try {
            return JSON.parse(v);
          } catch (e) {
            return null;
          }
        })(raza.miqaat_details) || {};
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

      tbody.appendChild(row);
    }
  }

  // Sabil and FMB related columns removed; helper functions were removed accordingly.

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
    // If linked to a miqaat, show Name with small Type · Date
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
      const assignedTo = m.assigned_to || '';
      const parts = [];
      if (type) parts.push(type);
      if (assignedTo) parts.push(assignedTo);
      const meta = parts.join(' · ');
      return `${name}<br><small style="color:#6c757d;">${meta}</small>`;
    }

    let data = parseMaybe(raza.razadata) || {};
    let rf = parseMaybe(raza.razafields) || {};
    let razafields = rf.fields || [];

    // Default: show umoor and razaType
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
    let data = parseMaybe(raza.razadata) || {};
    let rf = parseMaybe(raza.razafields) || {};
    let razafields = rf.fields || [];
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
            <div class="view-link remove-form-row">
              <a class="btn btn-sm btn-primary" onclick="show_raza(${raza['id']});">
                <span style=" cursor:pointer;">View</span>
              </a>
            </div>
        </div>
    `;
    } else {
      actionHTML = `
        <div class="view-link remove-form-row">
          <a class="btn btn-sm btn-primary" onclick="show_raza(${raza['id']});">
            <span style=" cursor:pointer;">View</span>
          </a>
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
</script>