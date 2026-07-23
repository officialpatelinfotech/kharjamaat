<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$is_laagat = isset($module_type) && $module_type === 'laagat';
$module_title = $is_laagat ? 'Laagat Module' : 'Rent Module';
$create_url = $is_laagat ? site_url('admin/laagat/create') : site_url('admin/rent/create');
$manage_url = $is_laagat ? site_url('admin/laagat/manage') : site_url('admin/rent/manage');
$col_class = 'col-md-5';
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="container margintopcontainer pt-5">
  <a href="<?php echo base_url('admin'); ?>" class="btn btn-outline-secondary mb-3" aria-label="Back to Admin Dashboard"><i class="fa-solid fa-arrow-left"></i></a>
  <h4 class="heading text-center mb-5"><?php echo $module_title; ?></h4>

  <div class="row g-3 align-items-stretch lr-cards-row justify-content-center">
    <div class="col-12 <?php echo $col_class; ?> d-flex mb-4">
      <a href="<?php echo $create_url; ?>" class="text-decoration-none w-100">
        <div class="admin-card">
          <div class="admin-card-icon">
            <i class="fa fa-plus-circle"></i>
          </div>
          <h5 class="admin-card-title">Create <?php echo $is_laagat ? 'Laagat' : 'Rent'; ?> Form</h5>
          <p class="admin-card-desc">Create a new <?php echo $is_laagat ? 'Laagat' : 'Rent'; ?> form for the selected Hijri year and applicable Raza category.</p>
        </div>
      </a>
    </div>

    <div class="col-12 <?php echo $col_class; ?> d-flex mb-4">
      <a href="<?php echo $manage_url; ?>" class="text-decoration-none w-100">
        <div class="admin-card">
          <div class="admin-card-icon">
            <i class="fa fa-database"></i>
          </div>
          <h5 class="admin-card-title">Manage <?php echo $is_laagat ? 'Laagat' : 'Rent'; ?></h5>
          <p class="admin-card-desc">Browse created forms, edit details, and activate/deactivate a year.</p>
        </div>
      </a>
    </div>


  </div>
</div>

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
    --shadow-lg:   0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
  }

  body {
    background-color: var(--bg) !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
  }

  .heading {
    font-family: 'Literata', Georgia, serif !important;
    font-weight: 700 !important;
    color: var(--text-1) !important;
    letter-spacing: -0.5px;
  }

  .btn-outline-secondary {
    border-color: var(--border) !important;
    color: var(--text-2) !important;
    font-size: .88rem !important;
    font-weight: 700 !important;
    border-radius: 8px !important;
    padding: 8px 16px !important;
    transition: all 0.2s ease !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
  }
  .btn-outline-secondary:hover {
    background: var(--gold-muted) !important;
    border-color: var(--gold) !important;
    color: var(--gold) !important;
  }

  .admin-card {
    background: var(--surface) !important;
    border: 1px solid var(--border) !important;
    border-radius: 16px !important;
    padding: 32px 24px !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    text-align: center !important;
    height: 100% !important;
    position: relative !important;
    overflow: hidden !important;
    box-shadow: var(--shadow-sm) !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease !important;
  }
  .admin-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: var(--shadow) !important;
    border-color: var(--gold) !important;
  }
  .admin-card::after {
    content: '' !important;
    position: absolute !important;
    bottom: 0 !important; left: 0 !important; right: 0 !important;
    height: 4px !important;
    background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 100%) !important;
    transform: scaleX(0) !important;
    transition: transform 0.2s ease !important;
    transform-origin: left !important;
  }
  .admin-card:hover::after {
    transform: scaleX(1) !important;
  }
  .admin-card-icon {
    width: 64px !important;
    height: 64px !important;
    border-radius: 16px !important;
    background: var(--surface-2) !important;
    color: var(--gold) !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 1.8rem !important;
    margin-bottom: 20px !important;
    transition: background 0.2s, color 0.2s !important;
  }
  .admin-card:hover .admin-card-icon {
    background: var(--gold-muted) !important;
    color: var(--gold) !important;
  }
  .admin-card-title {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: 1.05rem !important;
    font-weight: 700 !important;
    color: var(--text-1) !important;
    margin-bottom: 8px !important;
    line-height: 1.3 !important;
  }
  .admin-card-desc {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: 0.81rem !important;
    color: var(--text-3) !important;
    line-height: 1.5 !important;
    margin: 0 !important;
  }
  @media (max-width: 767.98px){ .admin-card { padding: 24px 16px !important; } }
</style>
