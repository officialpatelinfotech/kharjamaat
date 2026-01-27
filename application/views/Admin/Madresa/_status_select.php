<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
// $selected_status expected: 'Active' or 'Inactive'
$selected_status = !empty($selected_status) ? $selected_status : 'Active';
?>
<div class="form-group">
	<label>Status</label>
	<select name="status" class="form-control" required>
		<option value="Active" <?php echo ($selected_status === 'Active') ? 'selected' : ''; ?>>Active</option>
		<option value="Inactive" <?php echo ($selected_status === 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
	</select>
</div>
