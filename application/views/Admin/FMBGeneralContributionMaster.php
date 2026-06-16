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
</style>

<div class="container margintopcontainer pt-5">
  <!-- Back Button -->
  <div class="mb-4">
    <a href="<?php echo base_url("admin/managefmbsettings"); ?>" class="btn-back">
      <i class="fa-solid fa-arrow-left"></i> Back to FMB Settings
    </a>
  </div>

  <!-- Header Panel -->
  <div class="anj-header">
    <div class="anj-header-inner">
      <div class="anj-title-group">
        <p class="anj-eyebrow">Fizalat Mawamil al-Burhaniyah</p>
        <h1 class="anj-title">FMB Extra Contribution Master</h1>
      </div>
    </div>
  </div>

  <!-- Filters and Controls Row -->
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div style="flex: 1; min-width: 280px; max-width: 400px;">
      <form method="POST" action="<?php echo base_url("admin/fmbgeneralcontributionmaster"); ?>" id="filter-form" class="d-flex align-items-center gap-2">
        <select name="filter_status" id="filter-status" class="form-control-premium" style="flex: 1; min-width: 150px;">
          <option value="">Select Status</option>
          <option value="1" <?php echo isset($filter_status) ? ($filter_status === "1" ? "selected" : "") : ""; ?>>Active</option>
          <option value="0" <?php echo isset($filter_status) ? ($filter_status === "0" ? "selected" : "") : ""; ?>>Inactive</option>
        </select>
        <a href="<?php echo base_url("admin/fmbgeneralcontributionmaster"); ?>" class="btn-action btn-action-secondary" style="height: 38px; width: 38px; padding: 0;">
          <i class="fa-solid fa-times"></i>
        </a>
      </form>
    </div>
    <div>
      <button id="add-contri-type" class="btn-action btn-action-primary"><i class="fa-solid fa-plus"></i> Add Contribution Type</button>
    </div>
  </div>

  <!-- Add Form Panel -->
  <div id="add-contri-type-form" class="col-12 p-4 mt-3 mb-4 hidden" style="border: 1px solid var(--border); border-radius: 12px; background: var(--surface-2); box-shadow: var(--shadow-sm);">
    <h6 style="font-weight: 700; color: var(--text-1); margin-bottom: 14px;"><i class="fa-solid fa-circle-plus mr-1"></i> Add New Contribution Type</h6>
    <form method="POST" action="<?php echo base_url("admin/addfmbcontritype"); ?>" class="form-inline gap-2" style="display: flex; flex-wrap: wrap; align-items: center;">
      <select class="form-control-premium mr-2" name="fmb_type" id="fmb-type" required style="min-width: 180px; max-width: 200px;">
        <option value="">Select FMB Type</option>
        <option value="Thaali">Thaali</option>
        <option value="Niyaz">Niyaz</option>
      </select>

      <input type="text" class="form-control-premium mr-2" name="contri_for" placeholder="Enter contribution type name" required style="min-width: 250px; flex: 1;">
      
      <button type="submit" class="btn-action btn-action-primary mr-2">
        Submit
      </button>
      <button id="hide-add-contri-type-form" class="btn-action btn-action-secondary">
        Cancel
      </button>
    </form>
  </div>

  <!-- Master Table Card -->
  <div class="miqaat-table-card">
    <table class="miqaat-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Contribution Type</th>
          <th>FMB Type</th>
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
                <input name="edit_contri_for" value="<?php echo $value["name"]; ?>" id="edit-contri-for-<?php echo $value["id"]; ?>" class="hidden form-control form-control-premium">
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
                <p id="status-<?php echo $value["id"]; ?>" class="m-0"><?php echo $value["status"] ? "Active" : "Inactive"; ?></p>
                <select name="edit_status" id="edit-status-<?php echo $value["id"]; ?>" class="hidden form-control form-control-premium">
                  <option value="">Select Status</option>
                  <option value="1" <?php echo $value["status"] === "1" ? "selected" : ""; ?>>Active</option>
                  <option value="0" <?php echo $value["status"] === "0" ? "selected" : ""; ?>>Inactive</option>
                </select>
              </td>
              <td>
                <button class="edit-fmbgc-btn btn btn-sm btn-outline-secondary" id="edit-fmbgc-btn-<?php echo $value["id"]; ?>" data-fmbgc-id="<?php echo $value["id"]; ?>"><i class="fa-solid fa-pencil"></i></button>
                <button class="save-fmbgc-btn btn btn-sm btn-success hidden" id="save-fmbgc-btn-<?php echo $value["id"]; ?>" data-fmbgc-id="<?php echo $value["id"]; ?>">Save</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  $(document).ready(function() {
    $("#filter-status").on("change", function() {
      $("#filter-form").submit();
    });
    
    $("#add-contri-type").on("click", function() {
      $("#add-contri-type-form").removeClass("hidden").show();
    });
    $("#hide-add-contri-type-form").on("click", function(e) {
      e.preventDefault();
      $("#add-contri-type-form").addClass("hidden").hide();
    });

    $(".edit-fmbgc-btn").on("click", function(e) {
      e.preventDefault();
      $id = $(this).data("fmbgc-id");
      $("#contri-for-" + $id).addClass("hidden");
      $("#fmb-type-" + $id).addClass("hidden");
      $("#status-" + $id).addClass("hidden");
      $("#edit-contri-for-" + $id).removeClass("hidden");
      $("#edit-fmb-type-" + $id).removeClass("hidden");
      $("#edit-status-" + $id).removeClass("hidden");
      $(this).addClass("hidden");
      $("#save-fmbgc-btn-" + $id).removeClass("hidden");
    });

    $(".save-fmbgc-btn").on("click", function(e) {
      e.preventDefault();
      $id = $(this).data("fmbgc-id");
      $contriFor = $("#edit-contri-for-" + $id).val();
      $fmbType = $("#edit-fmb-type-" + $id).val();
      $status = $("#edit-status-" + $id).val();

      $.ajax({
        url: "<?php echo base_url("admin/updatefmbgc"); ?>",
        type: "POST",
        data: {
          "id": $id,
          "name": $contriFor,
          "fmb_type": $fmbType,
          "status": $status,
        },
        success: function(res) {
          $res = JSON.parse(res);
          if ($res.success) {
            alert("General Contribution Updated Successfully!");
            location.reload();
          }
        }
      })
    });
  });
</script>