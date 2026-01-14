<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$sof_options = isset($sof_options) && is_array($sof_options) ? $sof_options : [];
$aos_options = isset($aos_options) && is_array($aos_options) ? $aos_options : [];
$hijri_year_options = isset($hijri_year_options) && is_array($hijri_year_options) ? $hijri_year_options : [];
$current_hijri_year_for_expense = isset($current_hijri_year_for_expense) ? (int)$current_hijri_year_for_expense : null;
$expense = isset($expense) && is_array($expense) ? $expense : null;

$today = date('Y-m-d');

$is_edit = $expense !== null;

$val_date = $is_edit && !empty($expense['expense_date']) ? $expense['expense_date'] : $today;
$val_area_id = $is_edit ? ($expense['area_id'] ?? '') : '';
$val_amount = $is_edit ? ($expense['amount'] ?? '') : '';
$val_source_id = $is_edit ? ($expense['source_id'] ?? '') : '';
$val_hijri_year = $is_edit ? ($expense['hijri_year'] ?? $current_hijri_year_for_expense) : $current_hijri_year_for_expense;
$val_notes = $is_edit ? ($expense['notes'] ?? '') : '';
?>

<div class="container-fluid margintopcontainer pt-5">
  <div class="d-flex align-items-center mb-2">
    <a href="<?= base_url('anjuman/expense'); ?>" class="btn btn-sm btn-outline-secondary mr-2" aria-label="Back"><i class="fa-solid fa-arrow-left"></i></a>
    <h5 class="m-0 flex-grow-1 text-center"><?= $is_edit ? 'Edit Expense' : 'Add Expense'; ?></h5>
  </div>

  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('error'), ENT_QUOTES, 'UTF-8'); ?></div>
  <?php endif; ?>

  <div class="card shadow-sm" style="margin-left:500px;margin-right:500px;margin-top:20px;">
    <div class="card-body">
      <form method="post" action="<?= current_url(); ?>">
        <div class="form-group">
          <label for="expenseDate">Date</label>
          <input type="date" id="expenseDate" name="expense_date" class="form-control form-control-sm" value="<?= htmlspecialchars($val_date, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
          <label for="expenseAos">AOS (Area of Spend)</label>
          <?php
          $val_aos_name = '';
          if ($val_area_id !== '' && !empty($aos_options)) {
            foreach ($aos_options as $opt) {
              if ((int)($opt['id'] ?? 0) === (int)$val_area_id) {
                $val_aos_name = isset($opt['name']) ? (string)$opt['name'] : '';
                break;
              }
            }
          }
          ?>
          <input type="text" id="expenseAos" name="aos_name" class="form-control form-control-sm" value="<?= htmlspecialchars($val_aos_name, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Enter Area of Spend">
        </div>

        <div class="form-group">
          <label for="expenseAmount">Amount</label>
          <input type="number" step="0.01" min="0" id="expenseAmount" name="amount" class="form-control form-control-sm" value="<?= htmlspecialchars($val_amount, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
          <label for="expenseSof">SOF (Source of Funds)</label>
          <select id="expenseSof" name="source_id" class="form-control form-control-sm" required>
            <option value="">Select</option>
            <?php foreach ($sof_options as $opt): ?>
              <?php
                $id = isset($opt['id']) ? (int)$opt['id'] : 0;
                $name = isset($opt['name']) ? (string)$opt['name'] : '';
                $selected = ($val_source_id !== '' && (int)$val_source_id === $id) ? 'selected' : '';
              ?>
              <option value="<?= $id; ?>" <?= $selected; ?>><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="expenseHijriYear">Hijri Year</label>
          <select id="expenseHijriYear" name="hijri_year" class="form-control form-control-sm" required>
            <option value="">Select</option>
            <?php for ($yrInt = 1442; $yrInt <= 1457; $yrInt++): ?>
              <option value="<?= $yrInt; ?>" <?= ($val_hijri_year && $yrInt === (int)$val_hijri_year) ? 'selected' : ''; ?>><?= $yrInt; ?></option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="expenseNotes">Note</label>
          <input type="text" id="expenseNotes" name="notes" class="form-control form-control-sm" maxlength="255" value="<?= htmlspecialchars($val_notes, ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div class="text-right">
          <button type="submit" class="btn btn-sm btn-primary"><?= $is_edit ? 'Update Expense' : 'Save Expense'; ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
