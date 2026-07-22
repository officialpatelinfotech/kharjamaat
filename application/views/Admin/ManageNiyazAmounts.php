<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:        #b8860b;
    --gold-light:  #e6c84a;
    --gold-muted:  #f5e9c0;
    --gold-deep:   #8a6408;
    --bg:          #faf7f0;
    --surface:     #ffffff;
    --surface-2:   #f7f4ec;
    --border:      #e8e0cc;
    --text-1:      #1a1610;
    --text-2:      #5a5244;
    --text-3:      #9c8f7a;
    --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow:      0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
  }

  body {
    background-color: var(--bg) !important;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  /* ── Back button ── */
  .btn-back {
    border-color: var(--border) !important;
    color: var(--text-2) !important;
    font-weight: 700;
    font-size: 0.8rem;
    padding: 8px 16px;
    border-radius: 10px;
    background: var(--surface);
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none !important;
  }
  .btn-back:hover {
    background: var(--gold-muted);
    border-color: var(--gold) !important;
    color: var(--gold) !important;
  }

  /* ── Page Header Panel ── */
  .anj-header {
    margin-bottom: 30px;
  }
  .anj-header-inner {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
    border-radius: 22px;
    padding: 24px 30px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    box-shadow: var(--shadow-sm);
  }
  @media (max-width: 767px) {
    .anj-header-inner {
      flex-direction: column;
      align-items: flex-start;
      gap: 16px;
      padding: 20px 24px;
    }
  }
  .anj-title-group {
    position: relative;
    z-index: 1;
  }
  .anj-eyebrow {
    font-size: .67rem;
    font-weight: 700;
    letter-spacing: 1.4px;
    text-transform: uppercase;
    color: rgba(255,255,255,.65);
    margin-bottom: 4px;
  }
  .anj-title {
    font-family: 'Literata', Georgia, serif;
    font-size: 1.7rem;
    font-weight: 600;
    color: #fff;
    line-height: 1.15;
    margin: 0;
  }
  .anj-year-selector {
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 1;
  }
  @media (max-width: 767px) {
    .anj-year-selector {
      width: 100%;
      justify-content: flex-start;
    }
  }
  .anj-year-label {
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: rgba(255, 255, 255, 0.85);
    margin: 0;
    white-space: nowrap;
  }
  .select-year-premium {
    border: 1.5px solid rgba(255, 255, 255, 0.35);
    border-radius: 10px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    background: rgba(255, 255, 255, 0.12);
    outline: none;
    transition: all 0.2s ease-in-out;
    cursor: pointer;
    min-width: 130px;
    display: inline-block;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23ffffff' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 36px;
  }
  .select-year-premium:focus {
    border-color: #fff;
    background: rgba(255, 255, 255, 0.22);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.25);
  }
  .select-year-premium option {
    background-color: #78520a;
    color: #fff;
  }

  /* ── Table & Form styling ── */
  .admin-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
  }
  
  .niyaz-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }
  .niyaz-table th {
    background: var(--text-1);
    color: #fff;
    padding: 14px 16px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
    font-weight: 700;
  }
  .niyaz-table th:first-child {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
  }
  .niyaz-table th:last-child {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
  }
  .niyaz-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    font-size: 14px;
    color: var(--text-1);
    vertical-align: middle;
  }
  .niyaz-table tr:last-child td {
    border-bottom: none;
  }
  .form-control-premium {
    border: 1.5px solid var(--border);
    border-radius: 8px;
    padding: 6px 10px;
    font-size: 13px;
    color: var(--text-1);
    background: var(--surface-2);
    outline: none;
    transition: all 0.2s;
    width: 100%;
    max-width: 130px;
    font-weight: 600;
  }
  .form-control-premium:focus {
    border-color: var(--gold);
    background: var(--surface);
    box-shadow: 0 0 0 3px rgba(184,134,11,.1);
  }
  .btn-save {
    background: linear-gradient(135deg, var(--gold) 0%, #8f6808 100%);
    color: #fff !important;
    font-weight: 600;
    font-size: 14px;
    padding: 10px 24px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(184, 134, 11, 0.3);
  }

  .btn-delete-premium {
    background: transparent;
    border: 1px solid #fca5a5;
    color: #ef4444;
    padding: 6px 10px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .btn-delete-premium:hover {
    background: #fef2f2;
    border-color: #ef4444;
    color: #b91c1c;
  }

  .badge-fixed {
    font-size: 10px;
    padding: 3px 6px;
    border-radius: 4px;
    font-weight: 700;
    background: #ecfdf5;
    color: #10b981;
    border: 1px solid #bbf7d0;
    margin-left: 6px;
    vertical-align: middle;
  }

  /* Modal premium styling */
  .modal-content {
    border: 1.5px solid var(--border) !important;
    border-radius: 16px !important;
    box-shadow: var(--shadow) !important;
    overflow: hidden;
  }
  .modal-header {
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    padding: 18px 24px;
  }
  .modal-title {
    font-family: 'Literata', Georgia, serif;
    color: var(--gold-deep);
    font-size: 1.2rem;
    font-weight: 600;
  }
  .modal-body {
    padding: 24px;
  }
  .modal-footer {
    background: var(--surface-2);
    border-top: 1px solid var(--border);
    padding: 14px 24px;
  }
</style>

<div class="container margintopcontainer pt-5 pb-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="<?php echo base_url('admin'); ?>" class="btn-back">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
    <button type="button" class="btn btn-save" style="background: #9d7008; border: none;" data-toggle="modal" data-target="#addContributionTypeModal">
      <i class="fa fa-plus"></i> Add New Contribution Type
    </button>
  </div>

  <div class="anj-header">
    <div class="anj-header-inner">
      <div class="anj-title-group">
        <p class="anj-eyebrow">Settings</p>
        <h1 class="anj-title">Miqaat Niyaz Takhmeen</h1>
      </div>
      <div class="anj-year-selector">
        <label for="hijri-year-select" class="anj-year-label">Hijri Year</label>
        <select id="hijri-year-select" class="select-year-premium" onchange="window.location.href='<?php echo base_url('admin/manageniyazamounts'); ?>?year='+this.value">
          <?php if (!empty($hijri_years)): ?>
            <?php foreach ($hijri_years as $y): ?>
              <option value="<?php echo htmlspecialchars($y); ?>" <?php echo (isset($selected_year) && $selected_year == $y) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($y); ?>
              </option>
            <?php endforeach; ?>
          <?php else: ?>
            <option value="<?php echo isset($selected_year) ? htmlspecialchars($selected_year) : date('Y'); ?>">Hijri Year <?php echo isset($selected_year) ? htmlspecialchars($selected_year) : date('Y'); ?></option>
          <?php endif; ?>
        </select>
      </div>
    </div>
  </div>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success rounded-3 mb-4">
      <?php echo $this->session->flashdata('success'); ?>
    </div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger rounded-3 mb-4">
      <?php echo $this->session->flashdata('error'); ?>
    </div>
  <?php endif; ?>

  <div class="admin-card">
    <form action="<?php echo base_url('admin/updateniyazamounts'); ?>" method="post">
      <input type="hidden" name="year" value="<?php echo isset($selected_year) ? htmlspecialchars($selected_year) : ''; ?>">
      
      <div class="table-responsive">
        <table class="niyaz-table">
          <thead>
            <tr>
              <th style="width: 50px;">#</th>
              <th>Miqaat Type</th>
              <th>General (₹)</th>
              <th>Ashara (₹)</th>
              <th>Shehrullah (₹)</th>
              <th>Ladies (₹)</th>
              <th style="width: 100px; text-align: center;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Row 1: Individual Niyaz (Fixed) -->
            <tr>
              <td>1</td>
              <td>
                <strong>Individual Niyaz</strong> <span class="badge-fixed">Fixed</span>
              </td>
              <td>
                <input type="number" step="0.01" min="0" name="individual_amount[General]" class="form-control-premium" value="<?php echo htmlspecialchars($amounts['General']['individual_amount'] ?? '0.00'); ?>" required>
              </td>
              <td>
                <input type="number" step="0.01" min="0" name="individual_amount[Ashara]" class="form-control-premium" value="<?php echo htmlspecialchars($amounts['Ashara']['individual_amount'] ?? '0.00'); ?>" required>
              </td>
              <td>
                <input type="number" step="0.01" min="0" name="individual_amount[Shehrullah]" class="form-control-premium" value="<?php echo htmlspecialchars($amounts['Shehrullah']['individual_amount'] ?? '0.00'); ?>" required>
              </td>
              <td>
                <input type="number" step="0.01" min="0" name="individual_amount[Ladies]" class="form-control-premium" value="<?php echo htmlspecialchars($amounts['Ladies']['individual_amount'] ?? '0.00'); ?>" required>
              </td>
              <td class="text-center text-muted">—</td>
            </tr>

            <!-- Row 2: Hoob Amount (Fixed) -->
            <tr>
              <td>2</td>
              <td>
                <strong>Hoob Amount</strong> <span class="badge-fixed">Fixed</span>
              </td>
              <td>
                <input type="number" step="0.01" min="0" name="fala_amount[General]" class="form-control-premium" value="<?php echo htmlspecialchars($amounts['General']['fala_amount'] ?? '0.00'); ?>" required>
              </td>
              <td>
                <input type="number" step="0.01" min="0" name="fala_amount[Ashara]" class="form-control-premium" value="<?php echo htmlspecialchars($amounts['Ashara']['fala_amount'] ?? '0.00'); ?>" required>
              </td>
              <td>
                <input type="number" step="0.01" min="0" name="fala_amount[Shehrullah]" class="form-control-premium" value="<?php echo htmlspecialchars($amounts['Shehrullah']['fala_amount'] ?? '0.00'); ?>" required>
              </td>
              <td>
                <input type="number" step="0.01" min="0" name="fala_amount[Ladies]" class="form-control-premium" value="<?php echo htmlspecialchars($amounts['Ladies']['fala_amount'] ?? '0.00'); ?>" required>
              </td>
              <td class="text-center text-muted">—</td>
            </tr>

            <!-- Additional Types (User Defined) -->
            <?php if (!empty($additional_types)): ?>
              <?php $idx = 3; foreach ($additional_types as $row): ?>
                <tr>
                  <td><?php echo $idx++; ?></td>
                  <td>
                    <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                  </td>
                  <td>
                    <input type="number" step="0.01" min="0" name="additional_amount[<?php echo htmlspecialchars($row['name']); ?>][General]" class="form-control-premium" value="<?php echo htmlspecialchars($row['General']); ?>" required>
                  </td>
                  <td>
                    <input type="number" step="0.01" min="0" name="additional_amount[<?php echo htmlspecialchars($row['name']); ?>][Ashara]" class="form-control-premium" value="<?php echo htmlspecialchars($row['Ashara']); ?>" required>
                  </td>
                  <td>
                    <input type="number" step="0.01" min="0" name="additional_amount[<?php echo htmlspecialchars($row['name']); ?>][Shehrullah]" class="form-control-premium" value="<?php echo htmlspecialchars($row['Shehrullah']); ?>" required>
                  </td>
                  <td>
                    <input type="number" step="0.01" min="0" name="additional_amount[<?php echo htmlspecialchars($row['name']); ?>][Ladies]" class="form-control-premium" value="<?php echo htmlspecialchars($row['Ladies']); ?>" required>
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn-delete-premium" onclick="confirmDelete('<?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?>')" title="Delete">
                      <i class="fa-solid fa-trash-can"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      
      <p class="text-muted small mb-4">Note: Only amount values can be edited. Miqaat types 'Individual Niyaz' and 'Hoob Amount' are fixed.</p>

      <div class="text-right">
        <button type="submit" class="btn-save">
          <i class="fa-solid fa-floppy-disk mr-2"></i> Save Amounts
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ── Add New Contribution Type Modal ── -->
<div class="modal fade" id="addContributionTypeModal" tabindex="-1" aria-labelledby="addContributionTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addContributionTypeModalLabel"><i class="fa fa-plus mr-2" style="color:var(--gold);"></i>Add New Contribution Type</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form method="post" action="<?php echo base_url('admin/addfmbniyazcontritype'); ?>">
        <input type="hidden" name="year" value="<?php echo htmlspecialchars($selected_year); ?>">
        <div class="modal-body">
          <div class="form-group mb-3">
            <label class="font-weight-bold small text-uppercase text-muted mb-1" style="font-size: 0.74rem;">Contribution Type Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter contribution type name" required />
          </div>
          
          <div class="p-3 rounded border bg-light mb-2">
            <div class="font-weight-bold small text-uppercase text-secondary mb-3" style="font-size: 0.74rem; letter-spacing: 0.5px;">Enter Amounts for All Miqaat Types</div>
            
            <div class="row">
              <div class="col-6 mb-3">
                <label class="small text-muted mb-1 d-block" style="font-size: 0.72rem;">General (₹)</label>
                <input type="number" step="0.01" min="0" name="general_amount" class="form-control" placeholder="0.00" />
              </div>
              <div class="col-6 mb-3">
                <label class="small text-muted mb-1 d-block" style="font-size: 0.72rem;">Ashara (₹)</label>
                <input type="number" step="0.01" min="0" name="ashara_amount" class="form-control" placeholder="0.00" />
              </div>
              <div class="col-6">
                <label class="small text-muted mb-1 d-block" style="font-size: 0.72rem;">Shehrullah (₹)</label>
                <input type="number" step="0.01" min="0" name="shehrullah_amount" class="form-control" placeholder="0.00" />
              </div>
              <div class="col-6">
                <label class="small text-muted mb-1 d-block" style="font-size: 0.72rem;">Ladies (₹)</label>
                <input type="number" step="0.01" min="0" name="ladies_amount" class="form-control" placeholder="0.00" />
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
          <button type="submit" class="btn-save" style="padding: 6px 18px; font-size: 13px; border-radius: 8px;">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Hidden delete form -->
<form id="delete-type-form" action="<?php echo base_url('admin/deletefmbniyazcontritype'); ?>" method="post" style="display: none;">
  <input type="hidden" name="name" id="delete-type-name">
  <input type="hidden" name="year" value="<?php echo htmlspecialchars($selected_year); ?>">
</form>

<script>
function confirmDelete(name) {
  if (confirm('Are you sure you want to delete "' + name + '" contribution type?')) {
    document.getElementById('delete-type-name').value = name;
    document.getElementById('delete-type-form').submit();
  }
}
</script>
