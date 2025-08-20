<style>
  .menu-list-container {
    width: 100%;
  }
</style>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-hijri@3.0.0/moment-hijri.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<div class="container margintopcontainer">
  <h1 class="text-center pt-5 mb-5">Menu List</h1>
  <div class="container mb-4 p-0">
    <a href="<?php echo base_url("Umoor") ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>
  <div class="menu-list-container">
    <div class="create-menu-btn d-flex">
      <form method="post" action="<?php echo base_url("Umoor/filter_menu"); ?>" class="d-flex">
        <!-- <div class="form-group mr-3">
          <input type="text" id="daterange" name="daterange" class="form-control" />
          <input type="hidden" id="start_date" name="start_date" value="<?php echo isset($start_date) ? $start_date : ''; ?>">
          <input type="hidden" id="end_date" name="end_date" value="<?php echo isset($end_date) ? $end_date : ''; ?>">
        </div> -->

        <div class="form-group mr-3">
          <select name="hijri_month" id="hijri-month" class="form-control">
            <option value="">Select Month / Year</option>
            <option value="-3" <?php echo (isset($hijri_month_id) ? $hijri_month_id : 0) == -3 ? "selected" : ""; ?>>Last Year</option>
            <option value="-1" <?php echo (isset($hijri_month_id) ? $hijri_month_id : 0) == -1 ? "selected" : ""; ?>>This Year</option>
            <?php
            if (isset($hijri_months)) {
              foreach ($hijri_months as $key => $value) {
            ?>
                <option value="<?php echo $value["id"]; ?>" <?php echo isset($value["id"]) ? ($value["id"] == (isset($hijri_month_id) ? $hijri_month_id : 0) ? "selected" : "") : ""; ?>><?php echo $value["hijri_month"]; ?></option>
            <?php
              }
            }
            ?>
            <option value="-2" <?php echo (isset($hijri_month_id) ? $hijri_month_id : 0) == -2 ? "selected" : ""; ?>>Next Year</option>
          </select>
        </div>

        <!-- <div class="sort-options mr-3">
          <select id="sort-type" name="sort_type" class="form-control">
            <option value="asc" <?php echo isset($sort_type) ? ($sort_type == 'asc' ? 'selected' : '') : "" ?>>Sort by Date &darr;</i></option>
            <option value="desc" <?php echo isset($sort_type) ? ($sort_type == 'desc' ? 'selected' : '') : "" ?>>Sort by Date &uarr;</option>
          </select>
        </div> -->
        <div class="clear-filter-btn">
          <a href="<?php echo base_url("Umoor/menulist"); ?>" id="clear-filter" class="btn btn-secondary mx-3">Clear Filter</a>
        </div>
        <div class="search-btn">
          <button href="javascript:void(0)" id="search-btn" class="btn btn-primary" type="submit">Search</button>
        </div>
      </form>

      <div class="ml-auto">
        <!-- <a href="<?= base_url('admin/duplicate_last_month_menu'); ?>" class="btn btn-outline-primary" id="duplicate-menu-btn">
          <i class="fa fa-copy"></i> Duplicate Last Month's Menu
        </a> -->

        <a href="<?php echo base_url("Umoor/add_menu_item"); ?>" class="btn btn-secondary">Edit Items</a>
        <a href="<?php echo base_url("Umoor/create_menu"); ?>" class="btn btn-primary">Create Menu</a>
      </div>
    </div>
    <table class="table table-bordered mt-4">
      <thead>
        <tr>
          <th>ID</th>
          <th>Hijri Date</th>
          <th>Menu Date</th>
          <th>Day</th>
          <th>Menu</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($menu)) : ?>
          <?php foreach ($menu as $key => $item) :
            if (isset($menu[$key - 1])) {
              if (substr($menu[$key - 1]["hijri_date"], 3) != substr($menu[$key]["hijri_date"], 3)):
          ?>
                <tr>
                  <td colspan="6" class="bg-dark text-white"><?php echo substr($menu[$key]["hijri_date"], 3); ?></td>
                </tr>
            <?php
              endif;
            }
            ?>
            <tr>
              <td><?php echo $key + 1; ?></td>
              <td><?php echo isset($item['hijri_date']) ? $item['hijri_date'] : ""; ?></td>
              <td><?php echo isset($item['date']) ? date("d/m/Y", strtotime($item['date'])) : ""; ?></td>
              <td><?php echo isset($item['date']) ? date("l", strtotime($item['date'])) : ""; ?></td>
              <td>
                <?php echo implode(", ",  $item["items"]); ?>
              <td>
                <a href="<?php echo base_url("Umoor/edit_menu/" . $item['id']); ?>" class="btn btn-primary my-2">Edit</a>
              </td>
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
      alert("Select a month to filter");
    }
  });
</script>