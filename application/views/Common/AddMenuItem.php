<style>
  .hidden {
    display: none !important;
  }
</style>

<div class="container margintopcontainer pt-5">
  <div class="mb-3 mb-md-0">
    <a href="<?php echo base_url("common/$from"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <div class="mb-4">
    <h3 class="heading text-center">Menu Item List</h3>
  </div>
  <div class="item-list-container">
    <div class="filter-create-item-btn d-flex">
      <form method="post" action="<?php echo base_url("common/filter_menu_item?from=$from"); ?>" class="d-flex">
        <div class="filter-options mr-3">
          <select id="filter-type" name="filter-type" class="form-control">
            <option value="">All Types</option>
            <?php
            foreach ($item_types as $key => $value) {
            ?>
              <option value="<?php echo isset($value) ? $value["type_name"] : ""; ?>" <?php echo isset($filter_type) ? ($filter_type == $value["type_name"] ? 'selected' : '') : "" ?>><?php echo isset($value) ? $value["type_name"] : ""; ?></option>
            <?php
            }
            ?>
          </select>
        </div>
        <div class="search-bar mr-3">
          <input type="text" id="search-item" name="search-item" value="<?php echo isset($search_item) ? $search_item : '' ?>" class="form-control" placeholder="Search Item Name">
        </div>
        <div class="sort-options mr-3">
          <select id="sort-type" name="sort-type" class="form-control">
            <option value="asc" <?php echo isset($sort_type) && $sort_type == 'asc' ? 'selected' : '' ?>>Sort by Name (A-Z)</option>
            <option value="desc" <?php echo isset($sort_type) && $sort_type == 'desc' ? 'selected' : '' ?>>Sort by Name (Z-A)</option>
          </select>
        </div>
        <div class="search-btn">
          <button href="javascript:void(0)" id="search-btn" class="btn btn-primary" type="submit">Search</button>
        </div>
        <div class="clear-filter-btn">
          <a href="<?php echo base_url("common/add_menu_item?from=$from"); ?>" id="clear-filter" class="btn btn-secondary mx-3">Clear Filter</a>
        </div>
      </form>
      <div class="ml-auto">
        <a href="javascript:void(0)" class="add-type btn btn-warning"><i class="fa-solid fa-plus"></i> Add Type</a>
        <a href="javascript:void(0)" class="add-item btn btn-secondary"><i class="fa-solid fa-plus"></i> Add Item</a>
      </div>
    </div>

    <form method="post" action="<?php echo base_url("common/insert_item_type") ?>" id="add-item-type" class="d-flex justify-content-evenly align-items-center mt-4">
      <div class="form-group flex-grow-1 mr-2">
        <label for="type-name">Type Name</label>
        <input type="text" id="type-name" name="type_name" placeholder="Enter Type Name" class="form-control mb-3" required>
      </div>
      <div class="form-group d-flex flex-grow-1 mr-2">
        <input type="submit" class="btn btn-success flex-grow-1 px-3" value="Add" />
        <button type="button" class="btn btn-secondary flex-grow-1 ml-2 cancel-add-item">Cancel</button>
      </div>
    </form>

    <form method="post" action="<?php echo base_url("common/insert_menu_item?from=$from"); ?>" id="add-item-form" class="d-flex justify-content-evenly align-items-center mt-4">
      <div class="form-group flex-grow-1 mr-2">
        <label for="item_name">Item Name</label>
        <input type="text" id="item_name" name="item_name" placeholder="Enter Item Name" class="form-control mb-3" required>
      </div>
      <div class="form-group flex-grow-1 mr-2">
        <label for="item_type">Item Type</label>
        <select name="item_type" id="item_type" class="form-control mb-3" required>
          <option value="select" disabled>Select</option>
          <?php
          foreach ($item_types as $key => $value) {
          ?>
            <option value="<?php echo isset($value) ? $value["type_name"] : ""; ?>"><?php echo isset($value) ? $value["type_name"] : ""; ?></option>
          <?php
          }
          ?>
        </select>
      </div>
      <div class="form-group d-flex flex-grow-1 mr-2">
        <input type="submit" class="btn btn-success flex-grow-1 px-3" value="Add" />
        <button type="button" class="btn btn-secondary flex-grow-1 ml-2 cancel-add-item">Cancel</button>
      </div>
    </form>

    <div class="item-list">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Item Name</th>
            <th>Item Type</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($menu_items)) : ?>
            <?php foreach ($menu_items as $item) : ?>
              <tr>
                <td><span id="itemName_<?php echo $item["id"]; ?>"><?php echo htmlspecialchars($item['name']); ?></span><input type="text" value="<?php echo htmlspecialchars($item['name']); ?>" id="editItemName_<?php echo $item["id"]; ?>" class="edit-item-name form-control" required></td>
                <td>
                  <span id="itemType_<?php echo $item["id"]; ?>"><?php echo htmlspecialchars($item['type']); ?></span>
                  <select id="editItemType_<?php echo $item['id']; ?>" class="edit-item-type form-control" required>
                    <?php
                    foreach ($item_types as $key => $value) {
                    ?>
                      <option value="<?php echo isset($value) ? $value["type_name"] : ""; ?>" <?php echo isset($item['type']) ? ($item['type'] == $value["type_name"] ? 'selected' : '') : "" ?>><?php echo isset($value) ? $value["type_name"] : ""; ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </td>
                <td class="text-center">
                  <a href="javascript:void(0);" id="edit-btn-<?php echo $item['id']; ?>" class="edit-btn btn btn-sm btn-secondary" onclick="editMenuItem(<?php echo $item['id']; ?>)"><i class="fa fa-edit"></i></a>
                  <a href="<?php echo base_url("common/edit_menu_item/"); ?>" id="save-btn-<?php echo $item['id']; ?>" class="save-btn btn btn-success" onclick="saveItem(<?php echo $item['id']; ?>)">Save</a>
                  <a href="<?php echo base_url("common/delete_menu_item/"); ?>" id="delete-btn-<?php echo $item['id']; ?>" class="delete-btn btn btn-sm btn-danger" onclick="deleteItem(<?php echo $item['id']; ?>)"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="3" class="text-center">No items found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div id="deleteToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">
            Item deleted successfully!
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      $('#add-item-form').addClass('hidden');
      $('#add-item-type').addClass('hidden');
      $('.add-item').on('click', function() {
        $('#add-item-form').removeClass('hidden');
      });
      $('.add-type').on('click', function() {
        $('#add-item-type').removeClass('hidden');
      });

      $('.cancel-add-item').on('click', function() {
        $('#add-item-form').addClass('hidden');
        $('#add-item-type').addClass('hidden');
      });
      $('.save-btn').hide();
      $('.edit-item-name').hide();
      $('.edit-item-type').hide();

      $(".save-btn").on("click", function(e) {
        e.preventDefault();
      });
    });

    function editMenuItem($id) {
      $("#edit-btn-" + $id).hide();
      $('#save-btn-' + $id).show();
      $('#itemName_' + $id).hide();
      $('#editItemName_' + $id).show();
      $('#itemType_' + $id).hide();
      $('#editItemType_' + $id).show();
    }

    function saveItem($id) {
      const itemName = $('#editItemName_' + $id).val();
      const itemType = $('#editItemType_' + $id).val();

      const url = $("#save-btn-" + $id).attr('href');

      $.ajax({
        url: url,
        type: 'POST',
        data: {
          id: $id,
          name: itemName,
          type: itemType
        },
        success: function(response) {
          console.log(response);
          $("#itemName_" + $id).text(itemName);
          $("#itemType_" + $id).text(itemType);

          $("#edit-btn-" + $id).show();
          $('#save-btn-' + $id).hide();
          $('#itemName_' + $id).show();
          $('#editItemName_' + $id).hide();
          $('#itemType_' + $id).show();
          $('#editItemType_' + $id).hide();
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
        }
      });
    };

    function deleteItem($id) {
      if (!confirm('Are you sure you want to delete this item?')) {
        event.preventDefault();
        return false;
      }
      const url = $("#delete-btn-" + $id).attr('href');

      $.ajax({
        url: url,
        type: 'POST',
        data: {
          id: $id,
        },
        success: function(response) {
          $("#itemName_" + $id).closest('tr').remove();
          alert('Item deleted successfully.');
          location.reload();
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
        }
      });
    };
  </script>