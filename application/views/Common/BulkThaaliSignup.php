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
    --green:       #2e7d32;
    --red:         #c62828;
    --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow:      0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
  }

  body {
    background-color: var(--bg) !important;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

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

  .bulk-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    padding: 24px;
    margin-bottom: 30px;
  }

  .bulk-card-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text-1);
    margin-bottom: 18px;
    border-bottom: 1.5px solid var(--border-light);
    padding-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .form-control-premium {
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.85rem;
    color: var(--text-1);
    background: var(--surface-2);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    width: 100%;
  }
  .form-control-premium:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184,134,11,.1);
    background: var(--surface);
  }

  .date-nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 8px 12px;
    margin-bottom: 14px;
  }
  .date-nav-btn {
    border: 1px solid var(--border) !important;
    color: var(--text-2) !important;
    background: var(--surface) !important;
    padding: 5px 12px !important;
    font-size: 0.76rem !important;
    font-weight: 700 !important;
    border-radius: 8px !important;
    text-decoration: none !important;
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .date-nav-btn:hover {
    border-color: var(--gold) !important;
    background: var(--gold-muted) !important;
    color: var(--gold) !important;
  }

  .date-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 12px;
    max-height: 380px;
    overflow-y: auto;
    padding: 8px;
    border: 1.5px solid var(--border-light);
    border-radius: 10px;
    background: var(--surface-2);
  }

  .date-item {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: 8px;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.15s;
  }
  .date-item:hover {
    border-color: var(--gold);
    background: rgba(184, 134, 11, 0.03);
  }
  .date-item input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: var(--gold);
    cursor: pointer;
  }

  .hof-grid-container {
    max-height: 250px;
    overflow-y: auto;
    border: 1.5px solid var(--border-light);
    border-radius: 10px;
    padding: 10px;
    background: var(--surface-2);
  }
  .hof-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px;
    border-radius: 8px;
    background: var(--surface);
    border: 1px solid var(--border);
    margin-bottom: 6px;
    cursor: pointer;
    transition: all 0.1s;
  }
  .hof-item:hover {
    border-color: var(--gold);
    background: var(--surface-2);
  }
  .hof-item input[type="checkbox"] {
    width: 15px;
    height: 15px;
    accent-color: var(--gold);
    cursor: pointer;
  }

  .btn-action {
    font-family: inherit;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 10px 24px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    border: none;
  }
  .btn-action-primary {
    background: linear-gradient(135deg, var(--gold) 0%, #8f6808 100%);
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(184, 134, 11, 0.15);
  }
  .btn-action-primary:hover {
    box-shadow: 0 6px 20px rgba(184, 134, 11, 0.3);
    transform: translateY(-2px);
  }

  .alert-premium {
    border-radius: 10px;
    padding: 14px 20px;
    margin-bottom: 24px;
    font-size: 0.88rem;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .alert-premium-success {
    background-color: #e8f5e9;
    border: 1.5px solid #a5d6a7;
    color: var(--green);
  }
  .alert-premium-danger {
    background-color: #ffebee;
    border: 1.5px solid #ef9a9a;
    color: var(--red);
  }

  @media (max-width: 768px) {
    .date-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="container margintopcontainer pt-5">
  <!-- Back Button -->
  <div class="mb-4">
    <a href="<?php echo htmlspecialchars($active_controller); ?>" class="btn-back">
      <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
    </a>
  </div>

  <!-- Header Panel -->
  <div class="anj-header">
    <div class="anj-header-inner">
      <div class="anj-title-group">
        <p class="anj-eyebrow">Fizalat Mawamil al-Burhaniyah</p>
        <h1 class="anj-title">Bulk Thaali Sign-up</h1>
      </div>
    </div>
  </div>

  <!-- Alerts -->
  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert-premium alert-premium-success">
      <i class="fa-solid fa-circle-check fa-lg"></i>
      <div><?php echo htmlspecialchars($this->session->flashdata('success')); ?></div>
    </div>
  <?php endif; ?>
  
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert-premium alert-premium-danger">
      <i class="fa-solid fa-circle-exclamation fa-lg"></i>
      <div><?php echo htmlspecialchars($this->session->flashdata('error')); ?></div>
    </div>
  <?php endif; ?>

  <form method="POST" action="<?php echo base_url('common/bulk_thaali_signup?hijri=' . $selected_hijri . ($from ? '&from=' . urlencode($from) : '')); ?>">
    <div class="row">
      <!-- Left side: Date selection -->
      <div class="col-md-6">
        <div class="bulk-card">
          <div class="bulk-card-title">
            <i class="fa-solid fa-calendar-days text-primary"></i> 1. Select Thaali Dates
          </div>
          
          <!-- Month Navigation Bar -->
          <div class="date-nav-container">
            <a href="<?php echo base_url('common/bulk_thaali_signup?hijri=' . $prev_hijri . ($from ? '&from=' . urlencode($from) : '')); ?>" class="date-nav-btn">
              <i class="fa fa-chevron-left"></i> Prev Month
            </a>
            <span style="font-weight: 700; font-size: 0.82rem; color: var(--gold); text-transform: uppercase; letter-spacing: 0.5px;">
              <?php echo htmlspecialchars($hijri_month_name) . ' ' . htmlspecialchars($hijri_year); ?>
            </span>
            <a href="<?php echo base_url('common/bulk_thaali_signup?hijri=' . $next_hijri . ($from ? '&from=' . urlencode($from) : '')); ?>" class="date-nav-btn">
              Next Month <i class="fa fa-chevron-right"></i>
            </a>
          </div>

          <p class="text-muted" style="font-size:0.8rem; margin-bottom:12px;">Select the dates in the chosen Hijri month for which you want to perform the bulk action.</p>
          
          <div class="d-flex justify-content-between mb-2" style="font-size:0.75rem; font-weight:bold; width:100%;">
            <button type="button" class="btn btn-sm btn-link p-0 text-primary" id="select-all-dates" style="text-decoration:none; font-weight:700;"><i class="fa-solid fa-check-double"></i> Select All</button>
            <button type="button" class="btn btn-sm btn-link p-0 text-danger" id="deselect-all-dates" style="text-decoration:none; font-weight:700;"><i class="fa-solid fa-times-circle"></i> Deselect All</button>
          </div>

          <div class="date-grid">
            <?php if (!empty($menus)): ?>
              <?php foreach ($menus as $m): ?>
                <label class="date-item m-0">
                  <input type="checkbox" name="dates[]" value="<?php echo htmlspecialchars($m['date'], ENT_QUOTES); ?>">
                  <div style="font-size:0.83rem; font-weight:600; color:var(--text-1);">
                    <?php echo date('d-M-Y', strtotime($m['date'])); ?> 
                    <span style="font-size:0.75rem; color:var(--text-3); font-weight:normal; margin-left:6px;">
                      (<?php echo htmlspecialchars($m['hijri_label']); ?>)
                    </span>
                  </div>
                </label>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="p-4 text-center text-muted" style="font-size:0.85rem; width:100%;">
                <i class="fa fa-calendar-xmark fa-2x mb-2 d-block" style="color:var(--text-3);"></i>
                No FMB Menu dates found for this month.
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Right side: HOF Select list & Settings -->
      <div class="col-md-6">
        <div class="bulk-card">
          <div class="bulk-card-title">
            <i class="fa-solid fa-users text-primary"></i> 2. Select Families & Settings
          </div>
          
          <!-- HOF Search and selection list -->
          <div class="form-group mb-3">
            <label style="font-weight: 600; font-size: 0.83rem; color: var(--text-2); display: block; margin-bottom: 6px;">Select Families (HOFs)</label>
            <input type="text" id="search-hof" class="form-control-premium mb-2" placeholder="Search HOF name or ITS..." autocomplete="off">
            
            <div class="hof-grid-container">
              <div class="d-flex justify-content-between mb-2" style="font-size:0.75rem; font-weight:bold; width:100%;">
                <button type="button" class="btn btn-sm btn-link p-0 text-primary" id="select-all-hofs" style="text-decoration:none; font-weight:700;"><i class="fa-solid fa-check-double"></i> Select All</button>
                <button type="button" class="btn btn-sm btn-link p-0 text-danger" id="deselect-all-hofs" style="text-decoration:none; font-weight:700;"><i class="fa-solid fa-times-circle"></i> Deselect All</button>
              </div>
              <div id="hof-list">
                <?php if (!empty($hofs)): ?>
                  <?php foreach ($hofs as $hof): ?>
                    <label class="hof-item m-0" data-search="<?php echo strtolower(htmlspecialchars($hof['Full_Name'] . ' ' . $hof['ITS_ID'])); ?>">
                      <input type="checkbox" name="its_list_check[]" value="<?php echo htmlspecialchars($hof['ITS_ID'], ENT_QUOTES); ?>">
                      <span style="font-size:0.83rem; font-weight:600; color:var(--text-1);"><?php echo htmlspecialchars($hof['Full_Name']); ?></span>
                      <span style="font-size:0.75rem; color:var(--text-3); font-weight:500;">(<?php echo htmlspecialchars($hof['ITS_ID']); ?>)</span>
                    </label>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="p-3 text-center text-muted" style="font-size:0.85rem;">
                    No active HOFs found.
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Optional manual input for custom ITS lists -->
          <div class="form-group mb-3">
            <a href="javascript:void(0);" onclick="jQuery('#manual-its-group').toggle();" style="font-size:0.75rem; font-weight:700; color:var(--gold); text-decoration:underline;">
              + Or enter ITS IDs manually
            </a>
            <div id="manual-its-group" style="display:none; margin-top:8px;">
              <textarea class="form-control-premium" name="its_list" rows="3" placeholder="Enter custom ITS IDs separated by commas, spaces, or newlines."></textarea>
              <small class="text-muted" style="font-size:0.72rem; display:block; margin-top:4px;">
                Inputting any family member's ITS ID here will automatically resolve and register the signup under their family's Head of Family (HOF).
              </small>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group mb-3">
                <label for="bulk-want-thali" style="font-weight: 600; font-size: 0.83rem; color: var(--text-2); display: block; margin-bottom: 6px;">Action</label>
                <select name="want_thali" id="bulk-want-thali" class="form-control-premium" required>
                  <option value="1">Sign-up (Want Thali: Yes)</option>
                  <option value="0">Sign-out (Want Thali: No)</option>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group mb-3" id="thali-size-group">
                <label for="bulk-thali-size" style="font-weight: 600; font-size: 0.83rem; color: var(--text-2); display: block; margin-bottom: 6px;">Thali Size</label>
                <select name="thali_size" id="bulk-thali-size" class="form-control-premium">
                  <option value="">Select Size</option>
                  <?php foreach (['Big','Double Big','Triple Big','Medium','Double Medium','China','Double China'] as $sz): ?>
                    <option value="<?php echo htmlspecialchars($sz, ENT_QUOTES); ?>" <?php echo $sz === 'Medium' ? 'selected' : ''; ?>><?php echo htmlspecialchars($sz); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="text-right mt-4" style="display:flex; justify-content:flex-end;">
            <button type="submit" class="btn-action btn-action-primary">
              <i class="fa-solid fa-circle-check"></i> Perform Bulk Action
            </button>
          </div>

        </div>
      </div>
    </div>
  </form>
</div>

<script>
  jQuery(document).ready(function() {
    // Show/hide size based on signup action
    jQuery('#bulk-want-thali').on('change', function() {
      if (jQuery(this).val() == '1') {
        jQuery('#thali-size-group').show();
        jQuery('#bulk-thali-size').prop('required', true);
      } else {
        jQuery('#thali-size-group').hide();
        jQuery('#bulk-thali-size').prop('required', false).val('');
      }
    });
    jQuery('#bulk-want-thali').trigger('change');

    // Search/filter HOFs list
    jQuery('#search-hof').on('input', function() {
      const q = jQuery(this).val().toLowerCase().trim();
      jQuery('#hof-list .hof-item').each(function() {
        const text = jQuery(this).attr('data-search') || '';
        if (text.indexOf(q) > -1) {
          jQuery(this).show();
        } else {
          jQuery(this).hide();
        }
      });
    });

    // Select/Deselect visible HOF items
    jQuery('#select-all-hofs').on('click', function(e) {
      e.preventDefault();
      jQuery('#hof-list .hof-item:visible input[type="checkbox"]').prop('checked', true);
    });

    jQuery('#deselect-all-hofs').on('click', function(e) {
      e.preventDefault();
      jQuery('#hof-list .hof-item:visible input[type="checkbox"]').prop('checked', false);
    });

    // Select/Deselect dates
    jQuery('#select-all-dates').on('click', function(e) {
      e.preventDefault();
      jQuery('.date-grid input[type="checkbox"]').prop('checked', true);
    });

    jQuery('#deselect-all-dates').on('click', function(e) {
      e.preventDefault();
      jQuery('.date-grid input[type="checkbox"]').prop('checked', false);
    });
  });
</script>
