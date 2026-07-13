<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$expenses = isset($expenses) && is_array($expenses) ? $expenses : [];
$filters = isset($filters) && is_array($filters) ? $filters : [];
$hijri_year_options = isset($hijri_year_options) && is_array($hijri_year_options) ? $hijri_year_options : [];
$current_hijri_year_for_expense = isset($current_hijri_year_for_expense) ? (int)$current_hijri_year_for_expense : (isset($filters['hijri_year']) ? (int)$filters['hijri_year'] : null);
$expense_total = isset($expense_total) ? (float)$expense_total : 0.0;
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">

<div class="gold-theme-wrapper">
  <div class="container margintopcontainer pt-3">
    <style>
      .table-container { max-height: 520px; overflow: auto; border-radius: 8px; border: 1px solid #e8e0cc; }
      #expensesTable thead th { position: sticky; top: 0; background: #f5e9c0; color: #5a5244; z-index: 3; box-shadow: 0 2px 2px -1px rgba(0,0,0,0.1); border-bottom: 2px solid #e8e0cc; }
      #expensesTable tbody tr { background: #ffffff; }
      #expensesTable tbody tr:hover { background: #faf7f0; }
    </style>

    <div class="row align-items-center mb-3">
      <div class="col-12">
        <a href="<?= base_url('anjuman'); ?>" class="btn btn-sm btn-gold-outline" aria-label="Back"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
      </div>
    </div>

    <div class="anj-header mb-4">
      <div class="anj-header-inner d-flex align-items-center justify-content-between">
        <div class="anj-title-group">
          <span class="anj-eyebrow">Overview</span>
          <h2 class="anj-title">Expenses<?= $current_hijri_year_for_expense ? ' for ' . (int)$current_hijri_year_for_expense : ''; ?></h2>
        </div>
        <div class="anj-badge">
          <span class="anj-badge-val">₹<?= number_format($expense_total, 0); ?></span>
          <span class="anj-badge-lbl">Total Spend</span>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="card filter-card mb-4">
      <div class="card-body">
        <h6 class="font-weight-bold mb-3 font-title">Search & Filter</h6>
        <form id="filterForm" class="mb-0" method="get" action="<?= current_url(); ?>">
          <div class="form-row">
            <div class="form-group col-12 col-md-3">
              <label for="filterItem" class="mb-1 font-weight-bold small">Expense Section</label>
              <input type="text" id="filterItem" name="item" class="form-control form-control-sm" placeholder="Search Sector, Sub Sector, Section" value="<?= htmlspecialchars(isset($filters['item']) ? (string)$filters['item'] : '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-group col-12 col-md-3">
              <label for="filterPaymentMode" class="mb-1 font-weight-bold small">Payment Mode</label>
              <select id="filterPaymentMode" name="payment_mode" class="form-control form-control-sm">
                <option value="">All</option>
                <option value="Cash" <?= (isset($filters['payment_mode']) && $filters['payment_mode'] === 'Cash') ? 'selected' : ''; ?>>Cash</option>
                <option value="Cheque" <?= (isset($filters['payment_mode']) && $filters['payment_mode'] === 'Cheque') ? 'selected' : ''; ?>>Cheque</option>
                <option value="Bank Transfer" <?= (isset($filters['payment_mode']) && $filters['payment_mode'] === 'Bank Transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                <option value="Online" <?= (isset($filters['payment_mode']) && $filters['payment_mode'] === 'Online') ? 'selected' : ''; ?>>Online</option>
                <option value="Other" <?= (isset($filters['payment_mode']) && $filters['payment_mode'] === 'Other') ? 'selected' : ''; ?>>Other</option>
              </select>
            </div>

            <div class="form-group col-12 col-md-2">
              <label for="filterHijriYear" class="mb-1 font-weight-bold small">Financial Hijri Year</label>
              <select id="filterHijriYear" name="hijri_year" class="form-control form-control-sm">
                <option value="">All</option>
                <?php for ($yrInt = 1442; $yrInt <= 1457; $yrInt++): ?>
                  <?php $selected = (isset($filters['hijri_year']) && (int)$filters['hijri_year'] === $yrInt) ? 'selected' : ''; ?>
                  <option value="<?= $yrInt; ?>" <?= $selected; ?>><?= $yrInt . '-' . substr((string)($yrInt + 1), -2); ?></option>
                <?php endfor; ?>
              </select>
            </div>

            <div class="form-group col-12 col-md-4">
              <label class="mb-1 font-weight-bold small">Date Range</label>
              <div class="d-flex" style="gap: 4px;">
                <input type="date" name="date_from" class="form-control form-control-sm" value="<?= htmlspecialchars(isset($filters['date_from']) ? (string)$filters['date_from'] : '', ENT_QUOTES, 'UTF-8'); ?>" title="From date">
                <input type="date" name="date_to" class="form-control form-control-sm" value="<?= htmlspecialchars(isset($filters['date_to']) ? (string)$filters['date_to'] : '', ENT_QUOTES, 'UTF-8'); ?>" title="To date">
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end" style="gap: 8px;">
            <a href="<?= current_url(); ?>" class="btn btn-sm btn-gold-outline">Reset</a>
            <button type="submit" class="btn btn-sm btn-gold">Apply Filters</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Expenses table -->
    <div class="card table-card mb-3">
      <div class="card-body">
        <div class="expenses-header d-flex justify-content-between align-items-center mb-3">
          <h5 class="m-0 font-title">Recorded Expenses</h5>
          <a href="<?= base_url('anjuman/expense_add'); ?>" class="btn btn-sm btn-gold"><i class="fa fa-plus"></i> Add Expense</a>
        </div>

        <div class="table-container">
          <table id="expensesTable" class="table table-bordered align-middle mb-0">
            <thead>
              <tr>
                <th class="sortable" data-type="date" style="width: 125px;">Date</th>
                <th class="sortable" data-type="string">Sector</th>
                <th class="sortable" data-type="string">Sub Sector</th>
                <th class="sortable" data-type="string">Expense Section</th>
                <th class="sortable text-right" data-type="number" style="width: 120px;">Amount</th>
                <th class="sortable" data-type="string" style="width: 130px;">Payment Mode</th>
                <th class="sortable text-center" data-type="number" style="width: 100px;">Financial Hijri Year</th>
                <th style="width: 140px;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($expenses)): ?>
                <?php foreach ($expenses as $row): ?>
                  <?php
                    $date_raw = isset($row['expense_date']) ? $row['expense_date'] : '';
                    $date = $date_raw ? date('d-m-Y', strtotime($date_raw)) : '';
                    
                    $sector_name = isset($row['sector_name']) ? (string)$row['sector_name'] : '';
                    $sector_code = isset($row['sector_code']) ? (string)$row['sector_code'] : '';
                    $sub_sector_name = isset($row['sub_sector_name']) ? (string)$row['sub_sector_name'] : '';
                    $sub_sector_code = isset($row['sub_sector_code']) ? (string)$row['sub_sector_code'] : '';
                    $item_name = isset($row['item_name']) ? (string)$row['item_name'] : '';
                    $item_code = isset($row['item_code']) ? (string)$row['item_code'] : '';

                    $sector_display = $sector_code !== '' ? "{$sector_code} - {$sector_name}" : $sector_name;
                    $sub_sector_display = $sub_sector_code !== '' ? "{$sub_sector_code} - {$sub_sector_name}" : $sub_sector_name;
                    $item_display = $item_code !== '' ? "{$item_code} - {$item_name}" : $item_name;

                    $amount = isset($row['amount']) ? (float)$row['amount'] : 0.0;
                    $hijriYear = isset($row['hijri_year']) ? (int)$row['hijri_year'] : null;
                    $id = isset($row['id']) ? (int)$row['id'] : 0;
                    $hijriYearDisplay = $hijriYear ? $hijriYear . '-' . substr((string)($hijriYear + 1), -2) : '';
                  ?>
                  <tr>
                    <td data-sort-value="<?= htmlspecialchars($date_raw, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td title="<?= htmlspecialchars($sector_display, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($sector_display, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td title="<?= htmlspecialchars($sub_sector_display, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($sub_sector_display, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td title="<?= htmlspecialchars($item_display, ENT_QUOTES, 'UTF-8'); ?>" class="font-weight-semibold" style="color: #78520a;"><?= htmlspecialchars($item_display, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="text-right font-weight-bold" data-sort-value="<?= htmlspecialchars((string)$amount, ENT_QUOTES, 'UTF-8'); ?>">₹<?= number_format($amount, 0); ?></td>
                    <td><?= htmlspecialchars($row['payment_mode'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="text-center" data-sort-value="<?= $hijriYear ? (int)$hijriYear : ''; ?>"><?= htmlspecialchars($hijriYearDisplay, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="text-center">
                      <?php if ($id > 0): ?>
                        <div class="d-flex justify-content-center align-items-center" style="gap: 6px;">
                          <a href="<?= base_url('anjuman/expense_edit/' . $id); ?>" class="btn btn-xs btn-gold-outline">Edit</a>
                          <a href="<?= base_url('anjuman/expense_delete/' . $id); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to delete this expense?');">Delete</a>
                        </div>
                      <?php else: ?>
                        <span class="text-muted">N/A</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="text-center text-muted py-3">No expenses found for the selected criteria.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
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

        rows.forEach(function (row) {
          tbody.appendChild(row);
        });

        asc = !asc;
      });
    });

    // Auto-submit filter form on selection/change
    var filterForm = document.getElementById('filterForm');
    if (filterForm) {
      var inputs = filterForm.querySelectorAll('select, input[type="date"], input[type="text"]');
      Array.prototype.forEach.call(inputs, function(input) {
        input.addEventListener('change', function() {
          filterForm.submit();
        });
      });
    }
  });
</script>

<style>
  .gold-theme-wrapper {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #faf7f0;
    min-height: calc(100vh - 57px);
    margin-top: 57px;
    padding-bottom: 50px;
    color: #1a1610;
  }

  .anj-header-inner {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
    border-radius: 16px;
    padding: 24px 30px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,.05);
  }
  .anj-header-inner::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
  }
  .anj-eyebrow {
    font-size: .67rem;
    font-weight: 700;
    letter-spacing: 1.4px;
    text-transform: uppercase;
    color: rgba(255,255,255,.6);
    margin-bottom: 4px;
    display: block;
  }
  .anj-title {
    font-family: 'Literata', Georgia, serif;
    font-size: 1.6rem;
    font-weight: 600;
    color: #fff;
    line-height: 1.15;
    margin: 0;
  }

  .anj-badge {
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
    border-radius: 14px; padding: 10px 16px; backdrop-filter: blur(6px);
    text-align: center; flex-shrink: 0;
  }
  .anj-badge-val { font-size: 1.5rem; font-weight: 800; color: #fff; line-height: 1; display: block; }
  .anj-badge-lbl { font-size: .64rem; font-weight: 700; color: rgba(255,255,255,.65); letter-spacing: .5px; text-transform: uppercase; margin-top: 3px; display: block; }

  .btn-gold-outline {
    color: #b8860b;
    border: 1.5px solid #e8e0cc;
    background: #ffffff;
    font-weight: 600;
    transition: all 0.2s;
  }
  .btn-gold-outline:hover {
    background: #f5e9c0;
    color: #78520a;
    border-color: #b8860b;
    text-decoration: none;
  }

  .btn-gold {
    background: #b8860b;
    color: #ffffff;
    font-weight: 600;
    border: 1.5px solid transparent;
    border-radius: 8px;
    transition: all 0.2s;
  }
  .btn-gold:hover {
    background: #78520a;
    color: #ffffff;
  }

  .btn-xs {
    padding: 3px 10px;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .filter-card {
    background: #ffffff;
    border: 1px solid #e8e0cc;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(184,134,11,0.03);
  }

  .table-card {
    background: #ffffff;
    border: 1px solid #e8e0cc;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(184,134,11,0.04);
  }

  .font-title {
    font-family: 'Literata', Georgia, serif;
    font-weight: 600;
    color: #78520a;
  }
</style>
