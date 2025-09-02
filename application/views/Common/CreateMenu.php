<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-hijri@3.0.0/moment-hijri.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
  .hidden {
    display: none;
  }
</style>

<div class="container margintopcontainer pt-5">
  <div class="mb-3 mb-md-0">
    <a href="<?php echo base_url(isset($from) ? $from . "?from=$active_controller" : "common/fmbthaalimenu"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <div class="">
    <h3 class="heading text-center">Create Menu</h3>
  </div>
  <div class="mt-3 mt-md-0 text-right">
    <a href="<?php echo base_url("common/add_menu_item?from=createmenu"); ?>" class="btn btn-primary">Edit Items</a>
  </div>

  <form method="post" action="<?php echo base_url("common/add_menu") ?>" class="mt-4">
    <div class="form-group">
      <h4 class="mb-2">Date</h4>
      <div class="d-flex flex-column gap-2">
        <input type="hidden" name="menu_date" value="<?php echo isset($date) ? $date : ""; ?>">
        <?php if ($edit_mode): ?>
          <input type="hidden" name="edit_mode" value="true">
          <input type="hidden" name="menu_id" value="<?php echo $menu["id"]; ?>">
          <input type="hidden" name="hirji_date" value="<?php echo isset($hirji_date) ? $hirji_date : ""; ?>">
        <?php endif ?>
        <input type="text" id="menu_date" name="menu_date" class="form-control mb-3" required value="<?php
                                                                                                      echo isset($menu["date"]) ? date("d-m-Y", strtotime($menu["date"])) : "";
                                                                                                      echo isset($date) ? $date : "";
                                                                                                      ?>" placeholder="Please select a date">
      </div>
    </div>

    <h4>Select Items to Add to Menu</h4>
    <div class="form-group">
      <input type="text" id="search-input" class="form-control" placeholder="Search items...">
    </div>

    <ul id="search-results" class="list-group mb-3"></ul>

    <h5>Selected Items</h5>
    <ul id="selected-items" class="list-group my-2"></ul>

    <input type="hidden" name="menu_name">
    <input type="hidden" name="selected_item_ids" id="selected-item-ids">

    <div class="form-group">
      <input type="submit" id="submit-btn" class="btn btn-success mt-3" value="<?php echo $edit_mode ? "Save Edits" : 'Create Menu'; ?>" />
    </div>
  </form>

</div>
<script>
  $(document).ready(function() {
    let selectedItems = [];

    <?php if ($edit_mode) : ?>
      const itemsFromDB = <?php echo $edit_mode ? json_encode($mapped_menu_items["items"]) : ''; ?>;
      itemsFromDB.forEach(item => {
        const id = Number(item.id);
        const name = item.name;
        selectedItems.push(id);
        $('#selected-items').append(`
        <li class="list-group-item d-flex justify-content-between align-items-center" id="item-${id}">
          ${name}
          <button class="btn btn-sm btn-danger remove-item" data-id="${id}">Remove</button>
        </li>
      `);
      });
    <?php endif ?>

    <?php if (isset($menu_dates)) :
      $menu_dates_arr = [];
      foreach ($menu_dates as $menu_date) {
        $menu_dates_arr[] = date("d-m-Y", strtotime($menu_date["date"]));
      }
    ?>
      $menu_dates = <?php echo json_encode($menu_dates_arr); ?>;
    <?php endif; ?>

    flatpickr("#menu_date", {
      dateFormat: "d-m-Y",
      disable: $menu_dates,
    });


    $('#search-input').on('input', function() {
      const query = $(this).val();

      if (query.length < 2) {
        $('#search-results').empty();
        return;
      }

      $.ajax({
        url: '<?php echo base_url("common/search_items") ?>',
        method: 'POST',
        data: {
          keyword: query
        },
        success: function(response) {
          const items = JSON.parse(response);
          $('#search-results').empty();

          const filteredItems = items.filter(item => !selectedItems.includes(Number(item.id)));
          filteredItems.forEach(item => {
            $('#search-results').append(`
  <li class="list-group-item d-flex justify-content-between align-items-center">
    ${item.name}
    <button class="btn btn-sm btn-success add-item" data-id="${item.id}" data-name="${item.name}">Add</button>
  </li>
  `);
          });
        }
      });
    });

    // Add to selected
    $('#search-results').on('click', '.add-item', function(e) {
      e.preventDefault();
      const id = $(this).data('id');
      const name = $(this).data('name');

      if (!selectedItems.includes(id)) {
        selectedItems.push(id);
        $('#selected-items').append(`
  <li class="list-group-item d-flex justify-content-between align-items-center" id="item-${id}">
    ${name}
    <button class="btn btn-sm btn-danger remove-item" data-id="${id}">Remove</button>
  </li>
  `);
        updateHiddenField();
        $(this).closest('li').remove();
      }
    });

    // Remove from selected
    $('#selected-items').on('click', '.remove-item', function(e) {
      e.preventDefault();
      const id = $(this).data('id');
      selectedItems = selectedItems.filter(itemId => itemId !== id);
      $(`#item-${id}`).remove();
      updateHiddenField();
    });

    function updateHiddenField() {
      $('#selected-item-ids').val(JSON.stringify(selectedItems));
    }

    $("#submit-btn").on('click', function(e) {
      e.preventDefault();
      if (selectedItems.length === 0) {
        alert("Please select at least one item for the menu.");
        return;
      }
      $("form").submit();
    });

    $("#menu_date").on('change', function(e) {
      $.ajax({
        url: '<?php echo base_url("common/verify_menu_date") ?>',
        method: 'POST',
        data: {
          menu_date: $(this).val()
        },
        success: function(response) {
          const data = JSON.parse(response);
          if (data.status === 'exists') {
            alert(`Menu already exists for ${$("#menu_date").val()}. Please choose a different date.`);
            $("#menu_date").val('');
          } else {
            const hijriDate = data.hijri_date;
            $('#hijri-date-display').html(`Hijri Date: ${hijriDate}`);
          }
        }
      });
    });
  });
</script>