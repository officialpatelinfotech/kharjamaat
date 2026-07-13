<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$item_options = isset($item_options) && is_array($item_options) ? $item_options : [];
$hijri_year_options = isset($hijri_year_options) && is_array($hijri_year_options) ? $hijri_year_options : [];
$current_hijri_year_for_expense = isset($current_hijri_year_for_expense) ? (int)$current_hijri_year_for_expense : null;
$expense = isset($expense) && is_array($expense) ? $expense : null;

$today = date('Y-m-d');

$is_edit = $expense !== null;

$val_date = $is_edit && !empty($expense['expense_date']) ? $expense['expense_date'] : $today;
$val_item_id = $is_edit ? ($expense['item_id'] ?? '') : '';
$val_amount = $is_edit ? ($expense['amount'] ?? '') : '';
$val_payment_mode = $is_edit ? ($expense['payment_mode'] ?? '') : '';
$val_hijri_year = $is_edit ? ($expense['hijri_year'] ?? $current_hijri_year_for_expense) : $current_hijri_year_for_expense;
$val_notes = $is_edit ? ($expense['notes'] ?? '') : '';
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">

<div class="gold-theme-wrapper">
  <div class="container pt-5">
    <div class="row mb-3">
      <div class="col-12">
        <a href="<?= base_url('anjuman/expense'); ?>" class="btn btn-sm btn-gold-outline" aria-label="Back"><i class="fa-solid fa-arrow-left"></i> Back to Expenses</a>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-7 col-xl-6">
        <div class="card shadow-sm form-card">
          <div class="card-header form-card-header text-center">
            <h5 class="m-0 font-title"><?= $is_edit ? 'Edit Expense Record' : 'Add Expense Record'; ?></h5>
          </div>
          <div class="card-body p-4">
            <?php if ($this->session->flashdata('error')): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('error'), ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="post" action="<?= current_url(); ?>">
              <div class="form-group">
                <label for="expenseDate" class="font-weight-semibold">Date</label>
                <input type="date" id="expenseDate" name="expense_date" class="form-control form-control-sm" value="<?= htmlspecialchars($val_date, ENT_QUOTES, 'UTF-8'); ?>" required>
              </div>

              <div class="form-group position-relative">
                <label for="item-autocomplete" class="font-weight-semibold">Expense Section</label>
                <?php
                $initial_text = '';
                if ($val_item_id !== '' && !empty($item_options)) {
                  foreach ($item_options as $opt) {
                    if ((int)$opt['id'] === (int)$val_item_id) {
                      $sName = isset($opt['sector_name']) ? (string)$opt['sector_name'] : '';
                      $subName = isset($opt['sub_sector_name']) ? (string)$opt['sub_sector_name'] : '';
                      $name = isset($opt['item_name']) ? (string)$opt['item_name'] : '';
                      $initial_text = "{$sName} > {$subName} > {$name}";
                      break;
                    }
                  }
                }
                ?>
                <input type="text" id="item-autocomplete" class="form-control form-control-sm" placeholder="Type to search Expense Section..." value="<?= htmlspecialchars($initial_text, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" required>
                <input type="hidden" id="expenseItemVal" name="item_id" value="<?= htmlspecialchars($val_item_id, ENT_QUOTES, 'UTF-8'); ?>">
                <div id="item-autocomplete-list" class="list-group position-absolute shadow-sm" style="z-index: 1000; width: 100%; max-height: 250px; overflow-y: auto; display: none;"></div>
              </div>

              <div class="form-group">
                <label for="expenseAmount" class="font-weight-semibold">Amount</label>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0">₹</span>
                  </div>
                  <input type="number" step="0.01" min="0" id="expenseAmount" name="amount" class="form-control form-control-sm border-left-0" value="<?= htmlspecialchars($val_amount, ENT_QUOTES, 'UTF-8'); ?>" placeholder="0.00" required>
                </div>
              </div>

              <div class="form-group">
                <label for="expensePaymentMode" class="font-weight-semibold">Payment Mode</label>
                <select id="expensePaymentMode" name="payment_mode" class="form-control form-control-sm" required>
                  <option value="">Select Payment Mode</option>
                  <option value="Cash" <?= ($val_payment_mode === 'Cash') ? 'selected' : ''; ?>>Cash</option>
                  <option value="Cheque" <?= ($val_payment_mode === 'Cheque') ? 'selected' : ''; ?>>Cheque</option>
                  <option value="Bank Transfer" <?= ($val_payment_mode === 'Bank Transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                  <option value="Online" <?= ($val_payment_mode === 'Online') ? 'selected' : ''; ?>>Online</option>
                  <option value="Other" <?= ($val_payment_mode === 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
              </div>

              <div class="form-group">
                <label for="expenseHijriYear" class="font-weight-semibold">Financial Hijri Year</label>
                <select id="expenseHijriYear" name="hijri_year" class="form-control form-control-sm" required>
                  <option value="">Select Financial Hijri Year</option>
                  <?php for ($yrInt = 1442; $yrInt <= 1457; $yrInt++): ?>
                    <option value="<?= $yrInt; ?>" <?= ($val_hijri_year && $yrInt === (int)$val_hijri_year) ? 'selected' : ''; ?>><?= $yrInt . '-' . substr((string)($yrInt + 1), -2); ?></option>
                  <?php endfor; ?>
                </select>
              </div>

              <div class="form-group">
                <label for="expenseNotes" class="font-weight-semibold">Note</label>
                <input type="text" id="expenseNotes" name="notes" class="form-control form-control-sm" maxlength="255" value="<?= htmlspecialchars($val_notes, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Enter remarks or details (optional)">
              </div>

              <div class="text-right mt-4">
                <button type="submit" class="btn btn-sm btn-gold px-4 py-2"><?= $is_edit ? 'Update Expense' : 'Save Expense'; ?></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var items = <?php echo json_encode($item_options); ?> || [];
    var input = document.getElementById('item-autocomplete');
    var hiddenInput = document.getElementById('expenseItemVal');
    var listContainer = document.getElementById('item-autocomplete-list');
 
    if (!input || !listContainer) return;
 
    function renderList(filtered) {
      listContainer.innerHTML = '';
      if (filtered.length === 0) {
        var emptyDiv = document.createElement('div');
        emptyDiv.className = 'list-group-item list-group-item-action text-muted p-2';
        emptyDiv.textContent = 'No matching expense sections found';
        listContainer.appendChild(emptyDiv);
        return;
      }
 
      filtered.forEach(function(item) {
        var sName = item.sector_name || '';
        var subName = item.sub_sector_name || '';
        var name = item.item_name || '';
        var label = sName + ' > ' + subName + ' > ' + name;
 
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'list-group-item list-group-item-action text-left p-2';
        btn.style.fontSize = '0.85rem';
        btn.innerHTML = escapeHtml(label);
        btn.addEventListener('click', function() {
          input.value = label;
          hiddenInput.value = item.id;
          listContainer.style.display = 'none';
        });
        listContainer.appendChild(btn);
      });
    }
 
    function escapeHtml(s){
      return String(s)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;')
        .replace(/'/g,'&#039;');
    }
 
    input.addEventListener('input', function() {
      var val = this.value.trim().toLowerCase();
      hiddenInput.value = '';
      
      if (!val) {
        listContainer.style.display = 'none';
        return;
      }
 
      var filtered = items.filter(function(item) {
        var sName = (item.sector_name || '').toLowerCase();
        var subName = (item.sub_sector_name || '').toLowerCase();
        var name = (item.item_name || '').toLowerCase();
        return sName.indexOf(val) !== -1 || subName.indexOf(val) !== -1 || name.indexOf(val) !== -1;
      });
 
      renderList(filtered);
      listContainer.style.display = 'block';
    });
 
    input.addEventListener('focus', function() {
      var val = this.value.trim().toLowerCase();
      var filtered = items;
      if (val) {
        filtered = items.filter(function(item) {
          var sName = (item.sector_name || '').toLowerCase();
          var subName = (item.sub_sector_name || '').toLowerCase();
          var name = (item.item_name || '').toLowerCase();
          return sName.indexOf(val) !== -1 || subName.indexOf(val) !== -1 || name.indexOf(val) !== -1;
        });
      }
      renderList(filtered);
      listContainer.style.display = 'block';
    });
 
    // Hide dropdown on clicking outside
    document.addEventListener('click', function(e) {
      if (e.target !== input && !listContainer.contains(e.target)) {
        listContainer.style.display = 'none';
      }
    });
 
    // Prevent submitting without a valid selected ID
    input.closest('form').addEventListener('submit', function(e) {
      if (!hiddenInput.value) {
        e.preventDefault();
        alert('Please select an Expense Section from the list.');
        input.focus();
      }
    });
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
 
  .form-card {
    background: #ffffff;
    border: 1px solid #e8e0cc;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(184,134,11,0.05);
    overflow: hidden;
  }
 
  .form-card-header {
    background: #faf7f0;
    border-bottom: 1px solid #e8e0cc;
    padding: 16px;
  }
 
  .font-title {
    font-family: 'Literata', Georgia, serif;
    font-weight: 600;
    color: #78520a;
  }
 
  /* Autocomplete custom dropdown style */
  #item-autocomplete-list {
    border: 1px solid #e8e0cc;
    border-radius: 8px;
    background: #ffffff;
  }
  #item-autocomplete-list button {
    border: none;
    border-bottom: 1.5px solid #faf7f0;
    color: #5a5244;
    transition: background 0.15s, color 0.15s;
  }
  #item-autocomplete-list button:hover {
    background: #f5e9c0;
    color: #78520a;
  }
  #item-autocomplete-list button:last-child {
    border-bottom: none;
  }
</style>