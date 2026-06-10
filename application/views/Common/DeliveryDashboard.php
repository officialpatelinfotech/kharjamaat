<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">

<style>
  .hidden {
    display: none;
  }
  
  /* ═══════════════════════════════════════════════════
     GOLD THEME — scoped to #anjApp
  ═══════════════════════════════════════════════════ */
  #anjApp {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1a1610;
    background: #faf7f0;
    min-height: 100vh;
    padding-bottom: 60px;
  }
  #anjApp {
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
    --green:       #1a6645;
    --green-bg:    #eaf4ee;
    --red:         #b91c1c;
    --red-bg:      #fef2f2;
    --blue:        #1d4ed8;
    --blue-bg:     #eff6ff;
    --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow:      0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
    --shadow-lg:   0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
  }

  /* ── Header ── */
  #anjApp .anj-header {
    margin-bottom: 24px;
  }
  #anjApp .anj-header-inner {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
    border-radius: 20px;
    padding: 20px 24px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    box-shadow: var(--shadow-sm);
  }
  #anjApp .anj-header-inner::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
  }
  #anjApp .anj-title-group {
    position: relative;
    z-index: 1;
  }
  #anjApp .anj-eyebrow {
    font-size: .67rem;
    font-weight: 700;
    letter-spacing: 1.4px;
    text-transform: uppercase;
    color: rgba(255,255,255,.65);
    margin-bottom: 4px;
  }
  #anjApp .anj-title {
    font-family: 'Literata', Georgia, serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: #fff;
    line-height: 1.15;
    margin: 0;
  }

  /* ── Back button ── */
  #anjApp .btn-back {
    border-color: var(--border);
    color: var(--text-2);
    font-weight: 700;
    font-size: 0.8rem;
    padding: 8px 16px;
    border-radius: 10px;
    background: var(--surface);
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  #anjApp .btn-back:hover {
    background: var(--gold-muted);
    border-color: var(--gold);
    color: var(--gold);
    text-decoration: none;
  }

  /* ── Content Card wrapper ── */
  #anjApp .content-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
    overflow: hidden;
    transition: box-shadow 0.2s;
  }
  #anjApp .content-card:hover {
    box-shadow: var(--shadow);
  }

  /* ── Toolbar Panel ── */
  #anjApp .action-toolbar {
    background: var(--surface-2);
    border-bottom: 1.5px solid var(--border);
    padding: 16px;
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 10px;
  }
  #anjApp .btn-toolbar {
    font-family: inherit;
    font-weight: 700;
    font-size: 0.78rem;
    padding: 8px 16px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: 1px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
  }
  #anjApp .btn-toolbar:hover {
    background: var(--gold-muted);
    border-color: var(--gold);
    color: var(--gold);
    text-decoration: none;
  }
  #anjApp .btn-toolbar.active-item {
    background: var(--gold);
    border-color: var(--gold);
    color: #fff;
  }
  #anjApp .btn-toolbar.active-item:hover {
    background: #8f6808;
    border-color: #8f6808;
  }

  /* ── Scrollable table container ── */
  #anjApp .table-h-scroll {
    overflow-x: auto;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    max-height: 68vh;
    position: relative;
  }
  #anjApp .table-h-scroll table {
    min-width: 1000px;
    width: 100%;
  }

  /* ── Premium table styling ── */
  #anjApp .gc-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
  }
  #anjApp .gc-table th {
    background: var(--text-1);
    color: #fff;
    font-weight: 700;
    font-size: 0.76rem;
    padding: 14px 12px;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: sticky;
    top: 0;
    z-index: 5;
  }
  #anjApp .gc-table td {
    padding: 12px;
    font-size: 0.82rem;
    color: var(--text-2);
    vertical-align: middle;
    border-bottom: 1px solid var(--border-light);
    background: var(--surface);
  }
  #anjApp .gc-table tr:hover td {
    background: var(--surface-2);
  }
  #anjApp .gc-table tr:last-child td {
    border-bottom: none;
  }

  /* Floating submit button */
  #anjApp #submit-sub-btn {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    padding: 12px 28px;
    font-weight: 700;
    font-size: 0.85rem;
    border-radius: 30px;
    background: var(--gold);
    border: 1px solid var(--gold);
    color: #fff;
    box-shadow: var(--shadow-lg);
    z-index: 1000;
    transition: all 0.2s;
    animation: floatingPulse 1.8s infinite ease-in-out;
  }
  #anjApp #submit-sub-btn:hover {
    background: #8f6808;
    border-color: #8f6808;
    transform: translateX(-50%) scale(1.04);
    animation-play-state: paused;
    box-shadow: 0 10px 24px rgba(184, 134, 11, 0.3);
  }
  @keyframes floatingPulse {
    0% { transform: translateX(-50%) scale(1); }
    50% { transform: translateX(-50%) scale(0.96); }
    100% { transform: translateX(-50%) scale(1); }
  }

  /* Sticky first column layout */
  #anjApp .table-h-scroll tbody td:first-child,
  #anjApp .table-h-scroll thead th:first-child {
    position: sticky;
    left: 0;
    z-index: 6;
    background: var(--surface);
    border-right: 1px solid var(--border-light);
  }
  #anjApp .table-h-scroll thead th:first-child {
    background: var(--text-1);
  }
  #anjApp tr:hover td:first-child {
    background: var(--surface-2);
  }
  
  tr.clickable-row { cursor: pointer; }
</style>

<div id="anjApp" class="margintopcontainer pt-5">
  <div class="container-fluid px-md-5 pb-4">
    <!-- Back Button -->
    <div class="mb-4">
      <a href="<?php echo isset($from) ? base_url($from) : base_url("anjuman/fmbthaali"); ?>" class="btn btn-back">
        <i class="fa-solid fa-arrow-left mr-1"></i> Back
      </a>
    </div>

    <!-- Header Panel -->
    <div class="anj-header">
      <div class="anj-header-inner">
        <div class="anj-title-group">
          <p class="anj-eyebrow">Fizalat Mawamil al-Burhaniyah</p>
          <h1 class="anj-title">Delivery Dashboard</h1>
        </div>
      </div>
    </div>

    <!-- Main Content Card -->
    <div class="content-card">
      <!-- Action Toolbar -->
      <div class="action-toolbar">
        <a href="<?php echo base_url("common/currentsubstitutions"); ?>" class="btn-toolbar">
          <i class="fa-solid fa-clock-rotate-left"></i> Current Substitutions
        </a>
        <a href="<?php echo base_url("common/substitutedeliveryperson"); ?>" id="sub-dp-btn" class="btn-toolbar active-item">
          <i class="fa-solid fa-user-pen"></i> Substitute Delivery Person
        </a>
        <a href="<?php echo base_url("common/permanentassignment"); ?>" class="btn-toolbar">
          <i class="fa-solid fa-user-check"></i> Permanent Assignment
        </a>
        <a href="<?php echo base_url("common/managedeliveryperson"); ?>" class="btn-toolbar">
          <i class="fa-solid fa-user-gear"></i> Manage Delivery Persons
        </a>
      </div>

      <!-- Form & Table -->
      <form method="POST" action="<?php echo base_url("common/substitutedeliveryperson"); ?>">
        <div class="table-h-scroll">
          <table class="gc-table table table-hover">
            <thead>
              <tr>
                <th style="width: 40px;"></th>
                <th>Day</th>
                <th>Eng Date</th>
                <th>Hijri Date</th>
                <th>Thaali Signup Count</th>
                <th>Thaali Not Signup Count</th>
                <th>Count of Delivery Person</th>
                <th style="width: 100px;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($pending_days_in_year as $key => $value) {
              ?>
                <tr class="clickable-row" data-href="<?php echo base_url("common/signupforaday/" . $value["greg_date"]); ?>">
                  <td class="substitute-date-td" onclick="document.getElementById('substitute-date-<?php echo $value['id']; ?>').click()">
                    <input type="checkbox" id="substitute-date-<?php echo $value["id"]; ?>" class="substitute-date" name="substitute-date[]" value="<?php echo $value["greg_date"]; ?>">
                  </td>
                  <td style="font-weight: 700; color: var(--text-1);"><?php echo date("l", strtotime($value["greg_date"])); ?></td>
                  <td><?php echo date("d-m-Y", strtotime($value["greg_date"])); ?></td>
                  <td style="font-weight: 600;"><?php echo $value["hijri_date"]; ?></td>
                  <td style="font-weight: 700; color: var(--green);"><?php echo $value["signup_count"]; ?></td>
                  <td style="font-weight: 700; color: var(--red);"><?php echo $value["not_signup_count"]; ?></td>
                  <td style="font-weight: 700;"><?php echo $value["delivery_person_count"]; ?></td>
                  <td>
                    <button class="view-details btn btn-table-info">View</button>
                  </td>
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
          <button id="submit-sub-btn" class="btn hidden">Submit Substitution</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function($) {
    $(".clickable-row").click(function() {
      window.location = $(this).data("href");
    });

    $(".substitute-date-td, .substitute-date").click(function(e) {
      e.stopPropagation();
    });

    function updateCheckedCount() {
      const checkedCount = $('input[type="checkbox"]:checked').length;
      if (checkedCount) {
        $("#submit-sub-btn").removeClass("hidden");
      } else {
        $("#submit-sub-btn").addClass("hidden");
      }
      return checkedCount;
    }

    // Listen for changes on all checkboxes
    $('input[type="checkbox"]').on('change', updateCheckedCount);

    $("#submit-sub-btn").on("click", function(e) {
      if (updateCheckedCount() < 1) {
        alert("Kindly select the date for substitution.")
        e.preventDefault();
      }
    });

    $("#sub-dp-btn").on("click", function(e) {
      e.preventDefault();
      if (updateCheckedCount() < 1) {
        alert("Kindly select the date for substitution.")
      } else {
        $("#submit-sub-btn").click();
      }
    });

    $(".view-details").on("click", function(e) {
      e.preventDefault();
    });
  });
</script>