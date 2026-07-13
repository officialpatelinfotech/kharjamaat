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
    --border-light:#f0ece0;
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

  .hidden {
    display: none !important;
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
    gap: 16px;
    box-shadow: var(--shadow-sm);
  }
  .anj-header-inner::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
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

  /* ── Form controls ── */
  .form-control-premium {
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 8px 14px;
    font-size: 0.85rem;
    color: var(--text-1);
    background: var(--surface-2);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    height: 38px;
    width: 100%;
  }
  .form-control-premium:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184,134,11,.1);
    background: var(--surface);
  }

  /* ── Premium Buttons ── */
  .btn-action {
    font-family: inherit;
    font-weight: 600;
    font-size: 0.82rem;
    padding: 8px 16px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    letter-spacing: 0.2px;
    cursor: pointer;
    border: none;
    height: 38px;
  }
  .btn-action-primary {
    background: linear-gradient(135deg, var(--gold) 0%, #8f6808 100%);
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(184, 134, 11, 0.15);
  }
  .btn-action-primary:hover {
    color: #fff !important;
    box-shadow: 0 6px 20px rgba(184, 134, 11, 0.3);
    transform: translateY(-2px);
    text-decoration: none;
  }
  .btn-action-secondary {
    background: var(--surface);
    color: var(--gold) !important;
    border: 1.5px solid var(--gold) !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
  }
  .btn-action-secondary:hover {
    background: var(--gold-muted);
    color: var(--gold) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(184, 134, 11, 0.12);
    text-decoration: none;
  }

  /* ── Table Styling ── */
  .miqaat-table-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    margin-bottom: 30px;
    width: 100%;
  }
  .miqaat-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
  }
  .miqaat-table thead th {
    background-color: var(--text-1) !important;
    color: #fff !important;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border: none;
    border-bottom: 2px solid var(--border);
  }
  .miqaat-table tbody tr {
    transition: background-color 0.15s;
  }
  .miqaat-table tbody tr:nth-of-type(even) {
    background-color: var(--surface-2);
  }
  .miqaat-table tbody tr:hover {
    background-color: rgba(184, 134, 11, 0.05);
  }
  .miqaat-table tbody td {
    padding: 12px 16px;
    font-size: 13px;
    color: var(--text-2);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
  }

  .btn-outline-secondary {
    border-color: var(--border) !important;
    color: var(--text-2) !important;
  }
  .btn-outline-secondary:hover {
    background: var(--gold-muted) !important;
    border-color: var(--gold) !important;
    color: var(--gold) !important;
  }
  .save-fmbgc-btn {
    background-color: var(--gold) !important;
    border-color: var(--gold) !important;
    color: #fff !important;
  }
  .save-fmbgc-btn:hover {
    background-color: #8f6808 !important;
    border-color: #8f6808 !important;
  }

  @media (max-width: 768px) {
    .miqaat-table th, .miqaat-table td {
      white-space: nowrap;
    }
  }

  @media (max-width: 576px) {
    .filters-wrapper {
      max-width: 100% !important;
      width: 100% !important;
    }
    #filter-form {
      display: grid !important;
      grid-template-columns: 1fr 1fr;
      gap: 8px !important;
      width: 100% !important;
    }
    #filter-form select {
      width: 100% !important;
      margin-right: 0 !important;
    }
    #filter-form a.btn-action-secondary {
      grid-column: span 2;
      width: 100% !important;
      height: 38px !important;
    }
    .d-flex.align-items-center.justify-content-between.flex-wrap.gap-3.mb-4 {
      flex-direction: column !important;
      align-items: stretch !important;
    }
    #add-contri-type {
      width: 100% !important;
    }
    .anj-header-inner {
      padding: 16px 20px !important;
    }
    .anj-title {
      font-size: 1.3rem !important;
    }
  }
</style>

<div class="container margintopcontainer pt-5">
  <!-- Back Button -->
  <div class="mb-4">
    <a href="<?php echo base_url("admin"); ?>" class="btn-back">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
  </div>

  <!-- Header Panel -->
  <div class="anj-header">
    <div class="anj-header-inner">
      <div class="anj-title-group">
        <p class="anj-eyebrow">Fizalat Mawamil al-Burhaniyah</p>
        <h1 class="anj-title">Miqaat Niyaz Contribution Master</h1>
      </div>
    </div>
  </div>

  <!-- Filters and Controls Row -->
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="filters-wrapper" style="flex: 1; min-width: 280px; max-width: 800px;">
      <form method="POST" action="<?php echo base_url("admin/fmbgeneralcontributionmaster"); ?>" id="filter-form" class="d-flex align-items-center flex-wrap gap-2" style="flex: 1;">
        <!-- Status Filter -->
        <select name="filter_status" id="filter-status" class="form-control-premium mr-2" style="width: 140px;">
          <option value="">All Statuses</option>
          <option value="1" <?php echo isset($filter_status) && $filter_status === "1" ? "selected" : ""; ?>>Active</option>
          <option value="0" <?php echo isset($filter_status) && $filter_status === "0" ? "selected" : ""; ?>>Inactive</option>
        </select>
        
        <!-- FMB Type Filter -->
        <select name="filter_fmb_type" id="filter-fmb-type" class="form-control-premium mr-2" style="width: 140px;">
          <option value="">All FMB Types</option>
          <option value="Thaali" <?php echo isset($filter_fmb_type) && $filter_fmb_type === "Thaali" ? "selected" : ""; ?>>Thaali</option>
          <option value="Niyaz" <?php echo isset($filter_fmb_type) && $filter_fmb_type === "Niyaz" ? "selected" : ""; ?>>Niyaz</option>
        </select>

        <!-- Miqaat Type Filter -->
        <select name="filter_miqaat_type" id="filter-miqaat-type" class="form-control-premium mr-2" style="width: 150px;">
          <option value="">All Miqaat Types</option>
          <option value="Ashara" <?php echo isset($filter_miqaat_type) && $filter_miqaat_type === "Ashara" ? "selected" : ""; ?>>Ashara</option>
          <option value="Shehrullah" <?php echo isset($filter_miqaat_type) && $filter_miqaat_type === "Shehrullah" ? "selected" : ""; ?>>Shehrullah</option>
          <option value="General" <?php echo isset($filter_miqaat_type) && $filter_miqaat_type === "General" ? "selected" : ""; ?>>General</option>
          <option value="Ladies" <?php echo isset($filter_miqaat_type) && $filter_miqaat_type === "Ladies" ? "selected" : ""; ?>>Ladies</option>
        </select>

        <!-- Hijri Year Filter -->
        <select name="filter_year" id="filter-year" class="form-control-premium mr-2" style="width: 140px;">
          <option value="">All Hijri Years</option>
          <?php if (!empty($hijri_years)): ?>
            <?php foreach ($hijri_years as $y): ?>
              <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>" <?php echo isset($filter_year) && $filter_year === $y ? "selected" : ""; ?>><?php echo htmlspecialchars($y); ?></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>

        <a href="<?php echo base_url("admin/fmbgeneralcontributionmaster"); ?>" class="btn-action btn-action-secondary" style="height: 38px; width: 38px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Reset Filters">
          <i class="fa-solid fa-times"></i>
        </a>
      </form>
    </div>
    <div>
      <button id="add-contri-type" class="btn-action btn-action-primary"><i class="fa-solid fa-plus"></i> Add Contribution Type</button>
    </div>
  </div>

  <!-- Add Contribution Type Modal -->
  <div class="modal fade" id="addContributionTypeModal" tabindex="-1" role="dialog" aria-labelledby="addContributionTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content modal-content-premium" style="border: 1px solid var(--border); border-radius: 12px; background: var(--surface); box-shadow: var(--shadow);">
        <div class="modal-header modal-header-premium" style="border-bottom: 1px solid var(--border-light); padding: 16px 24px; display: flex; align-items: center; justify-content: space-between;">
          <h5 class="modal-title" id="addContributionTypeModalLabel" style="font-weight: 700; color: var(--text-1); margin: 0;"><i class="fa-solid fa-circle-plus mr-1" style="color: var(--gold);"></i> Add New Contribution Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="border: none; background: none; font-size: 1.5rem; color: var(--text-3); cursor: pointer;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="<?php echo base_url("admin/addfmbcontritype"); ?>">
          <div class="modal-body modal-body-premium" style="padding: 24px;">
            <div class="form-group form-group-premium mb-3">
              <label for="modal-fmb-type" style="font-weight: 600; font-size: 0.85rem; color: var(--text-2); display: block; margin-bottom: 6px;">Select FMB Type</label>
              <select class="form-control-premium" name="fmb_type" id="modal-fmb-type" required>
                <option value="">Select FMB Type</option>
                <option value="Thaali">Thaali</option>
                <option value="Niyaz">Niyaz</option>
              </select>
            </div>

            <div class="form-group form-group-premium mb-3 d-none" id="add-miqaat-type-group">
              <label for="modal-miqaat-type" style="font-weight: 600; font-size: 0.85rem; color: var(--text-2); display: block; margin-bottom: 6px;">Miqaat Type</label>
              <select class="form-control-premium" name="miqaat_type" id="modal-miqaat-type">
                <option value="">Select Miqaat Type</option>
                <option value="Ashara">Ashara</option>
                <option value="Shehrullah">Shehrullah</option>
                <option value="General">General</option>
                <option value="Ladies">Ladies</option>
              </select>
            </div>

            <div class="form-group form-group-premium mb-3">
              <label for="modal-contri-for" style="font-weight: 600; font-size: 0.85rem; color: var(--text-2); display: block; margin-bottom: 6px;">Contribution Type Name</label>
              <input type="text" class="form-control-premium" name="contri_for" id="modal-contri-for" placeholder="Enter contribution type name" required>
            </div>

            <div class="form-group form-group-premium mb-3">
              <label for="modal-amount" style="font-weight: 600; font-size: 0.85rem; color: var(--text-2); display: block; margin-bottom: 6px;">Amount (₹) (Optional)</label>
              <input type="number" class="form-control-premium" name="amount" id="modal-amount" placeholder="Enter amount" min="0" step="0.01">
            </div>

            <div class="form-group form-group-premium mb-3">
              <label for="modal-hijri-year" style="font-weight: 600; font-size: 0.85rem; color: var(--text-2); display: block; margin-bottom: 6px;">Hijri Financial Year (Optional)</label>
              <select class="form-control-premium" name="hijri_year" id="modal-hijri-year">
                <option value="">Select Year</option>
                <?php if (!empty($hijri_years)): ?>
                  <?php foreach ($hijri_years as $y): ?>
                    <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>"><?php echo htmlspecialchars($y); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </div>
          <div class="modal-footer" style="border-top: 1px solid var(--border-light); padding: 16px 24px; display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn-action btn-action-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-action btn-action-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Master Table Card -->
  <div class="miqaat-table-card">
    <div style="overflow-x: auto;">
      <table class="miqaat-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Contribution Type</th>
          <th>FMB Type</th>
          <th>Miqaat Type</th>
          <th>Amount</th>
          <th>Hijri Year</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (isset($all_fmbgc)): ?>
          <?php foreach ($all_fmbgc as $key => $value): ?>
            <tr>
              <td><?php echo $key + 1; ?></td>
              <td>
                <p id="contri-for-<?php echo $value["id"]; ?>" class="m-0" style="font-weight: 600;"><?php echo $value["name"]; ?></p>
                <input name="edit_contri_for" value="<?php echo htmlspecialchars($value["name"], ENT_QUOTES); ?>" id="edit-contri-for-<?php echo $value["id"]; ?>" class="hidden form-control form-control-premium">
              </td>
              <td>
                <p id="fmb-type-<?php echo $value["id"]; ?>" class="m-0"><?php echo $value["fmb_type"]; ?></p>
                <select name="edit_fmb_type" id="edit-fmb-type-<?php echo $value["id"]; ?>" class="hidden form-control form-control-premium">
                  <option value="">Select FMB Type</option>
                  <option value="Thaali" <?php echo $value["fmb_type"] === "Thaali" ? "selected" : ""; ?>>Thaali</option>
                  <option value="Niyaz" <?php echo $value["fmb_type"] === "Niyaz" ? "selected" : ""; ?>>Niyaz</option>
                </select>
              </td>
              <td>
                <p id="miqaat-type-<?php echo $value["id"]; ?>" class="m-0"><?php echo $value["miqaat_type"] ?: '-'; ?></p>
                <select name="edit_miqaat_type" id="edit-miqaat-type-<?php echo $value["id"]; ?>" class="hidden form-control form-control-premium" <?php echo $value["fmb_type"] === "Niyaz" ? "" : "disabled"; ?>>
                  <option value="">Select Miqaat Type</option>
                  <option value="Ashara" <?php echo $value["miqaat_type"] === "Ashara" ? "selected" : ""; ?>>Ashara</option>
                  <option value="Shehrullah" <?php echo $value["miqaat_type"] === "Shehrullah" ? "selected" : ""; ?>>Shehrullah</option>
                  <option value="General" <?php echo $value["miqaat_type"] === "General" ? "selected" : ""; ?>>General</option>
                  <option value="Ladies" <?php echo $value["miqaat_type"] === "Ladies" ? "selected" : ""; ?>>Ladies</option>
                </select>
              </td>
              <td>
                <p id="amount-<?php echo $value["id"]; ?>" class="m-0"><?php echo isset($value["amount"]) && $value["amount"] !== NULL ? '₹' . number_format($value["amount"], 2) : '-'; ?></p>
                <input type="number" name="edit_amount" value="<?php echo $value["amount"]; ?>" id="edit-amount-<?php echo $value["id"]; ?>" class="hidden form-control form-control-premium" min="0" step="0.01">
              </td>
              <td>
                <p id="hijri-year-<?php echo $value["id"]; ?>" class="m-0"><?php echo $value["hijri_year"] ?: '-'; ?></p>
                <select name="edit_hijri_year" id="edit-hijri-year-<?php echo $value["id"]; ?>" class="hidden form-control form-control-premium">
                  <option value="">Select Year</option>
                  <?php if (!empty($hijri_years)): ?>
                    <?php foreach ($hijri_years as $y): ?>
                      <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>" <?php echo $value["hijri_year"] === $y ? "selected" : ""; ?>><?php echo htmlspecialchars($y); ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </td>
              <td>
                <p id="status-<?php echo $value["id"]; ?>" class="m-0"><?php echo $value["status"] ? "Active" : "Inactive"; ?></p>
                <select name="edit_status" id="edit-status-<?php echo $value["id"]; ?>" class="hidden form-control form-control-premium">
                  <option value="">Select Status</option>
                  <option value="1" <?php echo $value["status"] === "1" ? "selected" : ""; ?>>Active</option>
                  <option value="0" <?php echo $value["status"] === "0" ? "selected" : ""; ?>>Inactive</option>
                </select>
              </td>
              <td>
                <div class="d-flex gap-1 align-items-center">
                  <button class="edit-fmbgc-btn btn btn-sm mr-2 btn-outline-secondary" id="edit-fmbgc-btn-<?php echo $value["id"]; ?>" data-fmbgc-id="<?php echo $value["id"]; ?>"><i class="fa-solid fa-pencil"></i></button>
                  <button class="save-fmbgc-btn btn btn-sm mr-2 btn-success hidden" id="save-fmbgc-btn-<?php echo $value["id"]; ?>" data-fmbgc-id="<?php echo $value["id"]; ?>">Save</button>
                  <button class="delete-fmbgc-btn btn btn-sm btn-outline-danger" id="delete-fmbgc-btn-<?php echo $value["id"]; ?>" data-fmbgc-id="<?php echo $value["id"]; ?>" data-name="<?php echo htmlspecialchars($value["name"], ENT_QUOTES); ?>"><i class="fa-solid fa-trash"></i></button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $("#filter-status, #filter-fmb-type, #filter-miqaat-type, #filter-year").on("change", function() {
      $("#filter-form").submit();
    });
    
    $("#modal-fmb-type").on("change", function() {
      if ($(this).val() === "Niyaz") {
        $("#add-miqaat-type-group").removeClass("d-none");
        $("#modal-miqaat-type").prop("required", true);
      } else {
        $("#add-miqaat-type-group").addClass("d-none");
        $("#modal-miqaat-type").prop("required", false).val("");
      }
    });

    $(document).on("change", "select[id^=edit-fmb-type-]", function() {
      const id = $(this).attr("id").split("-").pop();
      if ($(this).val() === "Niyaz") {
        $("#edit-miqaat-type-" + id).prop("disabled", false);
      } else {
        $("#edit-miqaat-type-" + id).prop("disabled", true).val("");
      }
    });

    $("#add-contri-type").on("click", function() {
      $("#addContributionTypeModal").modal("show");
    });

    $(".edit-fmbgc-btn").on("click", function(e) {
      e.preventDefault();
      $id = $(this).data("fmbgc-id");
      $("#contri-for-" + $id).addClass("hidden");
      $("#fmb-type-" + $id).addClass("hidden");
      $("#miqaat-type-" + $id).addClass("hidden");
      $("#amount-" + $id).addClass("hidden");
      $("#hijri-year-" + $id).addClass("hidden");
      $("#status-" + $id).addClass("hidden");
      $("#edit-contri-for-" + $id).removeClass("hidden");
      $("#edit-fmb-type-" + $id).removeClass("hidden");
      $("#edit-miqaat-type-" + $id).removeClass("hidden");
      $("#edit-amount-" + $id).removeClass("hidden");
      $("#edit-hijri-year-" + $id).removeClass("hidden");
      $("#edit-status-" + $id).removeClass("hidden");
      
      const currentFmbType = $("#edit-fmb-type-" + $id).val();
      if (currentFmbType === "Niyaz") {
        $("#edit-miqaat-type-" + $id).prop("disabled", false);
      } else {
        $("#edit-miqaat-type-" + $id).prop("disabled", true).val("");
      }

      $(this).addClass("hidden");
      $("#save-fmbgc-btn-" + $id).removeClass("hidden");
    });

    $(".save-fmbgc-btn").on("click", function(e) {
      e.preventDefault();
      $id = $(this).data("fmbgc-id");
      $contriFor = $("#edit-contri-for-" + $id).val();
      $fmbType = $("#edit-fmb-type-" + $id).val();
      $amount = $("#edit-amount-" + $id).val();
      $hijriYear = $("#edit-hijri-year-" + $id).val();
      $status = $("#edit-status-" + $id).val();
      $miqaatType = $("#edit-miqaat-type-" + $id).val();

      $.ajax({
        url: "<?php echo base_url("admin/updatefmbgc"); ?>",
        type: "POST",
        data: {
          "id": $id,
          "name": $contriFor,
          "fmb_type": $fmbType,
          "amount": $amount,
          "hijri_year": $hijriYear,
          "status": $status,
          "miqaat_type": $miqaatType
        },
        success: function(res) {
          $res = JSON.parse(res);
          if ($res.success) {
            alert("Contribution Type Updated Successfully!");
            location.reload();
          } else {
            alert("Failed to update Contribution Type.");
          }
        }
      })
    });

    $(".delete-fmbgc-btn").on("click", function(e) {
      e.preventDefault();
      const id = $(this).data("fmbgc-id");
      const name = $(this).data("name");
      if (confirm("Are you sure you want to delete this contribution type: '" + name + "'?")) {
        $.ajax({
          url: "<?php echo base_url("admin/deletefmbgc"); ?>",
          type: "POST",
          data: {
            "id": id
          },
          success: function(res) {
            const $res = JSON.parse(res);
            if ($res.success) {
              alert("Contribution Type Deleted Successfully!");
              location.reload();
            } else {
              alert("Failed to delete Contribution Type.");
            }
          }
        });
      }
    });
  });
</script>