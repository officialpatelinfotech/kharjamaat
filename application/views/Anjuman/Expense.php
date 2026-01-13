<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$sources = isset($sources) && is_array($sources) ? $sources : [];
?>

<style>
  .page-title {
    line-height: 1.6;
    margin-bottom: 1.5rem;
  }

  .table-container {
    max-height: 620px;
    overflow: auto;
  }

  .table thead th {
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 3;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
  }
</style>

<div class="container-fluid margintopcontainer pt-5">
  <div class="d-flex align-items-center mb-2">
    <a href="<?= base_url('anjuman'); ?>" class="btn btn-sm btn-outline-secondary mr-2" aria-label="Back"><i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <h4 class="text-center page-title">Expense Module</h4>

  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3" style="gap: 10px;">
        <h5 class="m-0">Source of Funds</h5>
        <div class="d-flex align-items-center" style="gap: 8px;">
          <label for="sofFilter" class="m-0 text-muted">Filter</label>
          <input type="text" id="sofFilter" class="form-control form-control-sm" placeholder="Search by name" style="min-width: 220px;" />
        </div>
      </div>

      <div class="table-container">
        <table class="table table-striped table-bordered mb-0">
          <thead>
            <tr>
              <th style="width: 80px;">Sr.No</th>
              <th>Name</th>
              <th style="width: 140px;">Status</th>
            </tr>
          </thead>
          <tbody id="sofTbody">
            <?php if (!empty($sources)) : ?>
              <?php $sr = 1; ?>
              <?php foreach ($sources as $row) : ?>
                <?php
                $name = isset($row['name']) ? (string)$row['name'] : '';
                $statusRaw = $row['status'] ?? 'Active';
                $isActive = false;
                if (is_numeric($statusRaw)) {
                  $isActive = ((int)$statusRaw) === 1;
                } else {
                  $isActive = strtolower(trim((string)$statusRaw)) === 'active';
                }
                $statusLabel = $isActive ? 'Active' : 'Inactive';
                ?>
                <tr>
                  <td class="text-center"><?= $sr++; ?></td>
                  <td class="sof-name"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td class="text-center"><?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="3" class="text-center text-muted">No sources of funds found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  (function () {
    var filter = document.getElementById('sofFilter');
    var tbody = document.getElementById('sofTbody');
    if (!filter || !tbody) return;

    function applyFilter() {
      var q = (filter.value || '').toLowerCase().trim();
      var rows = tbody.querySelectorAll('tr');
      rows.forEach(function (tr) {
        var nameCell = tr.querySelector('.sof-name');
        if (!nameCell) return;
        var name = (nameCell.textContent || '').toLowerCase();
        tr.style.display = q === '' || name.indexOf(q) !== -1 ? '' : 'none';
      });
    }

    filter.addEventListener('input', applyFilter);
  })();
</script>
