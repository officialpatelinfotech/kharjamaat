<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$expenses = isset($expenses) && is_array($expenses) ? $expenses : [];
$filters = isset($filters) && is_array($filters) ? $filters : [];
$sof_options = isset($sof_options) && is_array($sof_options) ? $sof_options : [];
$hijri_year_options = isset($hijri_year_options) && is_array($hijri_year_options) ? $hijri_year_options : [];
$sources = isset($sources) && is_array($sources) ? $sources : [];
$current_hijri_year_for_expense = isset($current_hijri_year_for_expense) ? (int)$current_hijri_year_for_expense : (isset($filters['hijri_year']) ? (int)$filters['hijri_year'] : null);
$expense_total = isset($expense_total) ? (float)$expense_total : 0.0;
?>

<style>
  .page-title {
    line-height: 1.6;
    margin-bottom: 1.5rem;
  }

  .table-container {
    max-height: 520px;
    overflow-y: auto;
    overflow-x: auto;
  }

  .table thead th {
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 3;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
  }

  /* Expenses table: equal column widths & fixed layout */
  #expensesTable {
    table-layout: fixed;
    width: 100%;
  }

  #expensesTable th,
  #expensesTable td {
    width: 16.666%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* Header with centered title and right-aligned button */
  .expenses-header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
  }

  .expenses-header-title {
    flex: 1;
    text-align: center;
    margin: 0;
  }

  .expenses-header-actions {
    position: absolute;
    right: 0;
  }

  /* Sortable header styling */
  #expensesTable thead th.sortable {
    cursor: pointer;
    user-select: none;
  }

  #expensesTable thead th.sortable::after {
    content: " \25B4\25BE";
    font-size: 0.7rem;
    opacity: 0.5;
  }
</style>

<div class="container-fluid margintopcontainer pt-5">
  <div class="d-flex align-items-center mb-2">
    <a href="<?= base_url('anjuman'); ?>" class="btn btn-sm btn-outline-secondary mr-2" aria-label="Back"><i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <h4 class="text-center page-title">
    Expenses<?= $current_hijri_year_for_expense ? ' for ' . (int)$current_hijri_year_for_expense : ''; ?>
  </h4>

  <div class="card shadow-sm mb-3">
    <div class="card-body text-center py-2">
      <span class="font-weight-bold">Total Expense<?= $current_hijri_year_for_expense ? ' for ' . (int)$current_hijri_year_for_expense : ''; ?>:</span>
      <span>
        ₹<?= number_format($expense_total, 0); ?>
      </span>
    </div>
  </div>

  <!-- Filters -->
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <form class="mb-0" method="get" action="<?= current_url(); ?>">
        <div class="form-row">
          <div class="form-group col-sm-6 col-md-3">
            <label for="filterAos" class="mb-1">AOS (Area of Spend)</label>
            <input type="text" id="filterAos" name="aos" class="form-control form-control-sm" placeholder="Search AOS" value="<?= htmlspecialchars(isset($filters['aos']) ? (string)$filters['aos'] : '', ENT_QUOTES, 'UTF-8'); ?>">
          </div>

          <div class="form-group col-sm-6 col-md-3">
            <label for="filterSof" class="mb-1">SOF (Source of Funds)</label>
            <select id="filterSof" name="sof" class="form-control form-control-sm">
              <option value="">All</option>
              <?php foreach ($sof_options as $opt): ?>
                <?php
                  $id = isset($opt['id']) ? (int)$opt['id'] : 0;
                  $name = isset($opt['name']) ? (string)$opt['name'] : '';
                  $selected = (isset($filters['sof']) && (int)$filters['sof'] === $id) ? 'selected' : '';
                ?>
                <option value="<?= $id; ?>" <?= $selected; ?>><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group col-sm-6 col-md-3">
            <label for="filterHijriYear" class="mb-1">Hijri Year</label>
            <select id="filterHijriYear" name="hijri_year" class="form-control form-control-sm">
              <option value="">All</option>
              <?php for ($yrInt = 1442; $yrInt <= 1457; $yrInt++): ?>
                <?php $selected = (isset($filters['hijri_year']) && (int)$filters['hijri_year'] === $yrInt) ? 'selected' : ''; ?>
                <option value="<?= $yrInt; ?>" <?= $selected; ?>><?= $yrInt; ?></option>
              <?php endfor; ?>
            </select>
          </div>

          <div class="form-group col-sm-6 col-md-3">
            <label class="mb-1">Date</label>
            <div class="d-flex" style="gap: 4px;">
              <input type="date" name="date_from" class="form-control form-control-sm" value="<?= htmlspecialchars(isset($filters['date_from']) ? (string)$filters['date_from'] : '', ENT_QUOTES, 'UTF-8'); ?>" title="From date">
              <input type="date" name="date_to" class="form-control form-control-sm" value="<?= htmlspecialchars(isset($filters['date_to']) ? (string)$filters['date_to'] : '', ENT_QUOTES, 'UTF-8'); ?>" title="To date">
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end" style="gap: 8px;">
          <a href="<?= current_url(); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
          <button type="submit" class="btn btn-sm btn-primary">Apply Filters</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Expenses table -->
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <div class="expenses-header">
        <h5 class="expenses-header-title">Expenses</h5>
        <div class="expenses-header-actions">
          <a href="<?= base_url('anjuman/expense_add'); ?>" class="btn btn-sm btn-success">Add Expense</a>
        </div>
      </div>

      <div class="table-container">
        <table id="expensesTable" class="table table-striped table-bordered mb-0">
          <thead>
            <tr>
              <th class="sortable" data-type="date">Date</th>
              <th class="sortable" data-type="string">AOS (Area of Spend)</th>
              <th class="sortable text-right" data-type="number">Amount</th>
              <th class="sortable" data-type="string">SOF (Source of Funds)</th>
              <th class="sortable text-center" data-type="number">Hijri Year</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($expenses)): ?>
              <?php foreach ($expenses as $row): ?>
                <?php
                  $date_raw = isset($row['expense_date']) ? $row['expense_date'] : '';
                  $date = $date_raw ? date('d-m-Y', strtotime($date_raw)) : '';
                  $aos = isset($row['area_name']) ? $row['area_name'] : '';
                  $amount = isset($row['amount']) ? (float)$row['amount'] : 0.0;
                  $sofName = isset($row['source_name']) ? $row['source_name'] : '';
                  $hijriYear = isset($row['hijri_year']) ? (int)$row['hijri_year'] : null;
                  $id = isset($row['id']) ? (int)$row['id'] : 0;
                ?>
                <tr>
                  <td data-sort-value="<?= htmlspecialchars($date_raw, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?= htmlspecialchars($aos, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td class="text-right" data-sort-value="<?= htmlspecialchars((string)$amount, ENT_QUOTES, 'UTF-8'); ?>">₹<?= number_format($amount, 0); ?></td>
                  <td><?= htmlspecialchars($sofName, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td class="text-center" data-sort-value="<?= $hijriYear ? (int)$hijriYear : ''; ?>"><?= $hijriYear ? (int)$hijriYear : ''; ?></td>
                  <td class="text-center">
                    <?php if ($id > 0): ?>
                      <a href="<?= base_url('anjuman/expense_edit/' . $id); ?>" class="btn btn-sm btn-outline-primary mr-1">Edit</a>
                      <a href="<?= base_url('anjuman/expense_delete/' . $id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this expense?');">Delete</a>
                    <?php else: ?>
                      <span class="text-muted">N/A</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-muted">No expenses found for the selected criteria.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  
</div>

<script>
  // Simple client-side sorting for the Expenses table
  document.addEventListener('DOMContentLoaded', function () {
    var table = document.getElementById('expensesTable');
    if (!table) return;

    var getCellValue = function (row, index) {
      var cell = row.children[index];
      if (!cell) return '';
      var sortVal = cell.getAttribute('data-sort-value');
      return sortVal !== null ? sortVal : cell.textContent.trim();
    };

    var comparer = function (index, type, asc) {
      return function (rowA, rowB) {
        var a = getCellValue(rowA, index);
        var b = getCellValue(rowB, index);

        if (type === 'number') {
          var numA = parseFloat(String(a).replace(/[^0-9.-]/g, '')) || 0;
          var numB = parseFloat(String(b).replace(/[^0-9.-]/g, '')) || 0;
          return (numA - numB) * (asc ? 1 : -1);
        }

        if (type === 'date') {
          var timeA = a ? Date.parse(a) : 0;
          var timeB = b ? Date.parse(b) : 0;
          return (timeA - timeB) * (asc ? 1 : -1);
        }

        // string compare
        a = String(a).toLowerCase();
        b = String(b).toLowerCase();
        if (a < b) return asc ? -1 : 1;
        if (a > b) return asc ? 1 : -1;
        return 0;
      };
    };

    var headers = table.querySelectorAll('thead th.sortable');
    var tbody = table.tBodies[0];
    if (!tbody) return;

    Array.prototype.forEach.call(headers, function (th, index) {
      var asc = true;
      th.addEventListener('click', function () {
        var type = th.getAttribute('data-type') || 'string';
        var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));

        rows.sort(comparer(index, type, asc));

        // re-append sorted rows
        rows.forEach(function (row) {
          tbody.appendChild(row);
        });

        asc = !asc;
      });
    });
  });
</script>
