<style>
  .menu-list-container {
    width: 100%;
  }
</style>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-hijri@3.0.0/moment-hijri.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<div class="container margintopcontainer pt-5">
  <div class="container mb-3 p-0">
    <a href="<?php echo base_url("admin/managefmbsettings"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <div class="create-menu-btn d-flex">
    <form method="post" action="<?php echo base_url("common/filter_menu"); ?>" id="filter-form" class="d-flex m-0">
      <!-- <div class="form-group mr-3">
          <input type="text" id="daterange" name="daterange" class="form-control" />
          <input type="hidden" id="start_date" name="start_date" value="<?php echo isset($start_date) ? $start_date : ''; ?>">
          <input type="hidden" id="end_date" name="end_date" value="<?php echo isset($end_date) ? $end_date : ''; ?>">
        </div> -->

      <div class="form-group">
        <select name="hijri_month" id="hijri-month" class="form-control">
          <option value="">Select Month / Year</option>
          <option value="-3" <?php echo (isset($hijri_month_id) ? $hijri_month_id : 0) == -3 ? "selected" : ""; ?>>Last Year</option>
          <option value="-1" <?php echo (isset($hijri_month_id) ? $hijri_month_id : 0) == -1 ? "selected" : ""; ?>>Current Year</option>
          <?php
          if (isset($hijri_months)) {
            foreach ($hijri_months as $key => $value) {
          ?>
              <option value="<?php echo $value["id"]; ?>" <?php echo isset($value["id"]) ? ($value["id"] == (isset($hijri_month_id) ? $hijri_month_id : 0) ? "selected" : "") : ""; ?>><?php echo $value["hijri_month"]; ?></option>
          <?php
            }
          }
          ?>
        </select>
      </div>

      <!-- <div class="sort-options mr-3">
          <select id="sort-type" name="sort_type" class="form-control">
            <option value="asc" <?php echo isset($sort_type) ? ($sort_type == 'asc' ? 'selected' : '') : "" ?>>Sort by Date &darr;</i></option>
            <option value="desc" <?php echo isset($sort_type) ? ($sort_type == 'desc' ? 'selected' : '') : "" ?>>Sort by Date &uarr;</option>
          </select>
        </div> -->
      <div class="clear-filter-btn">
        <a href="<?php echo base_url("common/fmbthaalimenu"); ?>" id="clear-filter" class="btn btn-secondary mx-3">Clear Filter</a>
      </div>
    </form>

    <div class="ml-auto">
      <!-- <a href="<?= base_url('admin/duplicate_last_month_menu'); ?>" class="btn btn-outline-primary" id="duplicate-menu-btn">
          <i class="fa fa-copy"></i> Duplicate Last Month's Menu
        </a> -->

      <a href="<?php echo base_url("common/add_menu_item?from=fmbthaalimenu"); ?>" class="btn btn-outline-secondary">Edit Items</a>
      <a href="<?php echo base_url("common/createmenu"); ?>" class="btn btn-primary">Add Menu</a>
    </div>
  </div>

  <h3 class="text-center mb-3">FMB Thaali Menu</h3>

  <div class="menu-list-container">
    <table class="table table-bordered mt-2">
      <thead>
        <tr>
          <th>#</th>
          <th>Eng Date</th>
          <th>Hijri Date</th>
          <th>Day</th>
          <th>Menu</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($menu)) : ?>
          <?php foreach ($menu as $key => $item) :
            if ($key === 0 || (isset($menu[$key - 1]) && explode(" ", $menu[$key - 1]["hijri_date"], 2)[1] != explode(" ", $menu[$key]["hijri_date"], 2)[1])):
          ?>
              <tr>
                <td colspan="6" class="bg-dark text-white text-center">Hijri Month: <?php echo explode(" ", $menu[$key]["hijri_date"], 2)[1]; ?></td>
              </tr>
            <?php endif;
            $dayName = isset($item['date']) ? date("l", strtotime($item['date'])) : "";
            $rowClass = '';
            if ($dayName === 'Sunday') {
              $rowClass = 'style="background:#ffe5e5"';
            } elseif (count($item["items"]) == 0) {
              $rowClass = 'class="table-secondary"';
            }
            ?>
            <tr <?php echo $rowClass; ?>>
              <td><?php echo $key + 1; ?></td>
              <td>
                <?php echo isset($item['date']) ? date("d M Y", strtotime($item['date'])) : ""; ?>
              </td>
              <td>
                <?php echo isset($item['hijri_date']) ? $item['hijri_date'] : ""; ?>
              </td>
              <td>
                <?php echo $dayName; ?>
              </td>
              <td>
                <?php echo implode(", ",  $item["items"]); ?>
              </td>
              <?php
              if (count($item["items"]) > 0) :
              ?>
                <td>
                  <a href="<?php echo base_url("common/edit_menu/" . $item['id']); ?>" class="btn btn-sm btn-primary mb-2 mb-md-0"><i class="fa fa-edit"></i></a>
                  <form method="POST" action="<?php echo base_url('common/delete_menu'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this menu?');">
                    <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                  </form>
                </td>
              <?php else : ?>
                <td>
                  <a href="<?php echo base_url("common/createmenu?date=" . $item['date']); ?>" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i>
                  </a>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td colspan="3" class="text-center">No menu items found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
  </div>
</div>
<script>
  $(function() {
    start = '<?= isset($start_date) ? $start_date : '' ?>';
    end = '<?= isset($end_date) ? $end_date : '' ?>';

    let startDate = start ? moment(start) : moment().startOf('month');
    let endDate = end ? moment(end) : moment().endOf('month');

    $('#daterange').daterangepicker({
      startDate: startDate,
      endDate: endDate,
      locale: {
        format: 'YYYY-MM-DD'
      },
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'This Week': [moment().startOf('week'), moment().endOf('week')],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      }
    }, function(start, end) {
      $('#start_date').val(start.format('YYYY-MM-DD'));
      $('#end_date').val(end.format('YYYY-MM-DD'));
      $('#daterange').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
    });

    // On page load: Set the visible input field manually
    $('#daterange').val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
  });

  $("#search-btn").on("click", function(e) {
    if ($("#hijri-month").val() == "") {
      e.preventDefault();
      alert("Select a Month / Year to filter");
    }
  });

  $('#hijri-month, #sort-type').on('change', function() {
    $('#filter-form').submit();
  });
</script>