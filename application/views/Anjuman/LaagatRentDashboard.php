<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$is_laagat = isset($module_type) && $module_type === 'laagat';
$is_rent = isset($module_type) && $module_type === 'rent';
$title = $is_laagat ? 'Laagat Module' : ($is_rent ? 'Rent Module' : 'Laagat & Rent Module');
$back_url = base_url("anjuman");
$invoices_url = base_url("anjuman/laagat_rent_invoices" . ($is_laagat ? "?charge_type=laagat" : ($is_rent ? "?charge_type=rent" : "")));
$payments_url = base_url("anjuman/laagat_rent_payments" . ($is_laagat ? "?charge_type=laagat" : ($is_rent ? "?charge_type=rent" : "")));
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">

<style>
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
  --blue:        #1d4ed8;
  --blue-bg:     #eff6ff;
  --amber:       #d97706;
  --amber-bg:    #fffbeb;
  --purple:      #7c3aed;
  --purple-bg:   #faf5ff;
  --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
  --shadow:      0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
}

/* ── Dashboard header ── */
#anjApp .anj-header {
  margin-bottom: 30px;
}
#anjApp .anj-header-inner {
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
  font-size: 1.7rem;
  font-weight: 600;
  color: #fff;
  line-height: 1.15;
  margin: 0;
}

/* ── Card container ── */
#anjApp .module-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 18px;
  padding: 30px 24px;
  text-align: center;
  box-shadow: var(--shadow-sm);
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  position: relative;
  overflow: hidden;
}
#anjApp .module-card::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: var(--gold);
  transform: scaleX(0);
  transition: transform 0.25s ease;
  transform-origin: left;
}
#anjApp .module-card:hover::after {
  transform: scaleX(1);
}
#anjApp .module-card:hover {
  border-color: var(--gold);
  box-shadow: var(--shadow);
  transform: translateY(-4px);
}

/* ── Icon Box ── */
#anjApp .icon-box {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 76px;
  height: 76px;
  border-radius: 18px;
  margin-bottom: 20px;
  transition: transform 0.25s ease;
}
#anjApp .module-card:hover .icon-box {
  transform: scale(1.08);
}
#anjApp .icon-box.blue-box {
  background: var(--blue-bg);
  color: var(--blue);
}
#anjApp .icon-box.green-box {
  background: var(--green-bg);
  color: var(--green);
}
#anjApp .icon-box.amber-box {
  background: var(--amber-bg);
  color: var(--amber);
}
#anjApp .icon-box.purple-box {
  background: var(--purple-bg);
  color: var(--purple);
}

/* ── Typography & buttons ── */
#anjApp .card-title {
  font-weight: 700;
  color: var(--text-1);
  font-size: 1.25rem;
  margin-bottom: 10px;
}
#anjApp .card-text {
  color: var(--text-2);
  font-size: 0.9rem;
  line-height: 1.5;
  margin-bottom: 24px;
}
#anjApp .btn-custom {
  font-family: inherit;
  font-weight: 700;
  font-size: 0.85rem;
  padding: 10px 20px;
  border-radius: 12px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
  text-decoration: none;
}
#anjApp .btn-blue {
  background: var(--blue);
  color: #fff;
  border: 1px solid var(--blue);
}
#anjApp .btn-blue:hover {
  background: #113ca4;
  border-color: #113ca4;
  color: #fff;
  box-shadow: 0 4px 12px rgba(29, 78, 216, 0.2);
}
#anjApp .btn-green {
  background: var(--green);
  color: #fff;
  border: 1px solid var(--green);
}
#anjApp .btn-green:hover {
  background: #124d33;
  border-color: #124d33;
  color: #fff;
  box-shadow: 0 4px 12px rgba(26, 102, 69, 0.2);
}
#anjApp .btn-amber {
  background: var(--amber);
  color: #fff;
  border: 1px solid var(--amber);
}
#anjApp .btn-amber:hover {
  background: #b45309;
  border-color: #b45309;
  color: #fff;
  box-shadow: 0 4px 12px rgba(217, 119, 6, 0.2);
}
#anjApp .btn-purple {
  background: var(--purple);
  color: #fff;
  border: 1px solid var(--purple);
}
#anjApp .btn-purple:hover {
  background: #6d28d9;
  border-color: #6d28d9;
  color: #fff;
  box-shadow: 0 4px 12px rgba(124, 58, 237, 0.2);
}

#anjApp .btn-back {
  border-color: var(--border);
  color: var(--text-2);
  font-weight: 700;
  font-size: 0.8rem;
  padding: 8px 16px;
  border-radius: 10px;
  background: var(--surface);
  transition: all 0.15s;
}
#anjApp .btn-back:hover {
  background: var(--gold-muted);
  border-color: var(--gold);
  color: var(--gold-deep);
  text-decoration: none;
}
</style>

<div id="anjApp" class="margintopcontainer pt-5">
  <div class="container pb-4">
    <!-- Back Button -->
    <div class="mb-4">
      <a href="<?php echo $back_url; ?>" class="btn btn-back">
        <i class="fa-solid fa-arrow-left mr-1"></i> Back to Dashboard
      </a>
    </div>

    <!-- Header Panel -->
    <div class="anj-header">
      <div class="anj-header-inner">
        <div class="anj-title-group">
          <p class="anj-eyebrow">Umoor 12 — Rent & Laagat</p>
          <h1 class="anj-title"><?php echo $title; ?></h1>
        </div>
      </div>
    </div>

    <!-- Main Grid -->
    <div class="row mt-4">
      <?php if ($is_rent): ?>
        <!-- Card 1: Rent Invoices -->
        <div class="col-md-6 mb-4">
          <div class="module-card">
            <div class="text-center w-100">
              <div class="icon-box blue-box">
                <i class="fa-solid fa-file-invoice fa-2x"></i>
              </div>
              <h4 class="card-title">Update Rent Invoices</h4>
              <p class="card-text">Manage all generated Rent invoices, update individual amounts, and oversee general configurations.</p>
            </div>
            <a href="<?php echo base_url('anjuman/laagat_rent_invoices?charge_type=rent'); ?>" class="btn-custom btn-blue">
              Update Rent Invoices <i class="fa-solid fa-chevron-right"></i>
            </a>
          </div>
        </div>

        <!-- Card 2: Rent Payments -->
        <div class="col-md-6 mb-4">
          <div class="module-card">
            <div class="text-center w-100">
              <div class="icon-box green-box">
                <i class="fa-solid fa-money-bill-transfer fa-2x"></i>
              </div>
              <h4 class="card-title">Receive Rent Payment</h4>
              <p class="card-text">Record Rent payments received against invoices, inspect receipts, and browse history.</p>
            </div>
            <a href="<?php echo base_url('anjuman/laagat_rent_payments?charge_type=rent'); ?>" class="btn-custom btn-green">
              Receive Rent Payment <i class="fa-solid fa-chevron-right"></i>
            </a>
          </div>
        </div>

        <!-- Card 3: Deposit Invoices -->
        <div class="col-md-6 mb-4">
          <div class="module-card">
            <div class="text-center w-100">
              <div class="icon-box amber-box">
                <i class="fa-solid fa-vault fa-2x"></i>
              </div>
              <h4 class="card-title">Update Deposit Invoices</h4>
              <p class="card-text">Manage all generated Deposit invoices, update individual amounts, and oversee configurations.</p>
            </div>
            <a href="<?php echo base_url('anjuman/laagat_rent_invoices?charge_type=deposit'); ?>" class="btn-custom btn-amber">
              Update Deposit Invoices <i class="fa-solid fa-chevron-right"></i>
            </a>
          </div>
        </div>

        <!-- Card 4: Deposit Payments -->
        <div class="col-md-6 mb-4">
          <div class="module-card">
            <div class="text-center w-100">
              <div class="icon-box purple-box">
                <i class="fa-solid fa-piggy-bank fa-2x"></i>
              </div>
              <h4 class="card-title">Receive Deposit Payment</h4>
              <p class="card-text">Record Deposit payments received against invoices, inspect receipts, and browse history.</p>
            </div>
            <a href="<?php echo base_url('anjuman/laagat_rent_payments?charge_type=deposit'); ?>" class="btn-custom btn-purple">
              Receive Deposit Payment <i class="fa-solid fa-chevron-right"></i>
            </a>
          </div>
        </div>

      <?php else: ?>
        <!-- Card 1: Invoices -->
        <div class="col-md-6 mb-4">
          <div class="module-card">
            <div class="text-center w-100">
              <div class="icon-box blue-box">
                <i class="fa-solid fa-file-invoice fa-2x"></i>
              </div>
              <h4 class="card-title">Update <?php echo $is_laagat ? 'Laagat' : 'Laagat & Rent'; ?> Invoices</h4>
              <p class="card-text">Manage all generated invoices, update individual amounts, and oversee general configurations.</p>
            </div>
            <a href="<?php echo $invoices_url; ?>" class="btn-custom btn-blue">
              Update Invoices <i class="fa-solid fa-chevron-right"></i>
            </a>
          </div>
        </div>

        <!-- Card 2: Payments -->
        <div class="col-md-6 mb-4">
          <div class="module-card">
            <div class="text-center w-100">
              <div class="icon-box green-box">
                <i class="fa-solid fa-money-bill-transfer fa-2x"></i>
              </div>
              <h4 class="card-title">Receive <?php echo $is_laagat ? 'Laagat' : 'Laagat & Rent'; ?> Payment</h4>
              <p class="card-text">Record new payments received against invoices, inspect receipts, and browse history.</p>
            </div>
            <a href="<?php echo $payments_url; ?>" class="btn-custom btn-green">
              Receive Payment <i class="fa-solid fa-chevron-right"></i>
            </a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
