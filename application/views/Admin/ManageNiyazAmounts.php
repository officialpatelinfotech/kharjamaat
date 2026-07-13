<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:        #b8860b;
    --gold-light:  #e6c84a;
    --gold-muted:  #f5e9c0;
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
    padding: 12px 16px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
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
    padding: 16px;
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
    padding: 8px 12px;
    font-size: 14px;
    color: var(--text-1);
    background: var(--surface-2);
    outline: none;
    transition: all 0.2s;
    width: 100%;
    max-width: 200px;
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
  }
  .btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(184, 134, 11, 0.3);
  }
</style>

<div class="container margintopcontainer pt-5">
  <div class="mb-4">
    <a href="<?php echo base_url('admin'); ?>" class="btn-back">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
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
      <table class="niyaz-table">
        <thead>
          <tr>
            <th>Miqaat Type</th>
            <th>Individual Niyaz Amount (₹)</th>
            <th>Hoob Amount (₹)</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($amounts)): ?>
            <?php foreach ($amounts as $row): ?>
              <tr>
                <td><strong><?php echo htmlspecialchars($row['miqaat_type']); ?></strong>
                  <input type="hidden" name="miqaat_type[]" value="<?php echo htmlspecialchars($row['miqaat_type']); ?>">
                </td>
                <td>
                  <input type="number" step="0.01" min="0" name="individual_amount[]" class="form-control-premium" value="<?php echo htmlspecialchars($row['individual_amount']); ?>" required>
                </td>
                <td>
                  <input type="number" step="0.01" min="0" name="fala_amount[]" class="form-control-premium" value="<?php echo htmlspecialchars($row['fala_amount']); ?>" required>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" class="text-center">No miqaat types found. Default types will be populated automatically if not present.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      
      <div class="text-right">
        <button type="submit" class="btn-save">
          <i class="fa-solid fa-floppy-disk mr-2"></i> Save Amounts
        </button>
      </div>
    </form>
  </div>
</div>
