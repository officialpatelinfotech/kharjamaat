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
}
#anjApp .btn-back:hover {
  background: var(--gold-muted);
  border-color: var(--gold);
  color: var(--gold);
  text-decoration: none;
}

/* ── Action Cards Grid ── */
#anjApp .niyaz-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 18px;
  padding: 26px 20px;
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
#anjApp .niyaz-card::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: var(--card-accent);
  transform: scaleX(0);
  transition: transform 0.25s ease;
  transform-origin: left;
}
#anjApp .niyaz-card:hover::after {
  transform: scaleX(1);
}
#anjApp .niyaz-card:hover {
  border-color: var(--card-accent);
  box-shadow: var(--shadow);
  transform: translateY(-4px);
}

/* ── Themes for Cards ── */
#anjApp .card-shehrullah {
  --card-accent: #b8860b;
  --card-accent-bg: #f5e9c0;
  --card-accent-hover: #8f6808;
}
#anjApp .card-ashara {
  --card-accent: #b8860b;
  --card-accent-bg: #f5e9c0;
  --card-accent-hover: #8f6808;
}
#anjApp .card-general {
  --card-accent: #b8860b;
  --card-accent-bg: #f5e9c0;
  --card-accent-hover: #8f6808;
}
#anjApp .card-ladies {
  --card-accent: #b8860b;
  --card-accent-bg: #f5e9c0;
  --card-accent-hover: #8f6808;
}
#anjApp .card-extra {
  --card-accent: #b8860b;
  --card-accent-bg: #f5e9c0;
  --card-accent-hover: #8f6808;
}

/* ── Icon Box ── */
#anjApp .icon-box {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 66px;
  height: 66px;
  border-radius: 16px;
  margin-bottom: 16px;
  background: var(--card-accent-bg);
  color: var(--card-accent);
  transition: transform 0.2s ease;
}
#anjApp .niyaz-card:hover .icon-box {
  transform: scale(1.06);
}

#anjApp .card-title {
  font-weight: 700;
  color: var(--text-1);
  font-size: 1.15rem;
  margin-bottom: 8px;
}
#anjApp .card-text {
  color: var(--text-2);
  font-size: 0.82rem;
  line-height: 1.4;
  margin-bottom: 20px;
}

/* ── Button Actions Grid ── */
#anjApp .action-buttons-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
  width: 100%;
}
#anjApp .btn-action {
  font-family: inherit;
  font-weight: 700;
  font-size: 0.78rem;
  padding: 8px 14px;
  border-radius: 10px;
  text-decoration: none;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}
#anjApp .btn-action-primary {
  background: var(--card-accent);
  color: #fff;
  border: 1px solid var(--card-accent);
}
#anjApp .btn-action-primary:hover {
  background: var(--card-accent-hover);
  border-color: var(--card-accent-hover);
  color: #fff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}
#anjApp .btn-action-secondary {
  background: transparent;
  color: var(--card-accent);
  border: 1px solid var(--card-accent);
}
#anjApp .btn-action-secondary:hover {
  background: var(--card-accent-bg);
  color: var(--card-accent);
}
</style>

<div id="anjApp" class="margintopcontainer pt-5">
  <div class="container pb-4">
    <!-- Back Button -->
    <div class="mb-4">
      <a href="<?php echo base_url("anjuman/fmbmodule") ?>" class="btn btn-back">
        <i class="fa-solid fa-arrow-left mr-1"></i> Back to FMB Module
      </a>
    </div>

    <!-- Header Panel -->
    <div class="anj-header">
      <div class="anj-header-inner">
        <div class="anj-title-group">
          <p class="anj-eyebrow">Fizalat Mawamil al-Burhaniyah</p>
          <h1 class="anj-title">FMB Niyaz</h1>
        </div>
      </div>
    </div>

    <!-- Niyaz Cards Grid -->
    <div class="row mt-4">
      <!-- Shehrullah -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="niyaz-card card-shehrullah">
          <div class="text-center w-100 mb-3">
            <div class="icon-box">
              <i class="fa-solid fa-money-bill-wheat fa-2x"></i>
            </div>
            <h4 class="card-title">Shehrullah Miqaat</h4>
            <p class="card-text">Generate Shehrullah Miqaat invoices & receive payment</p>
          </div>
          <div class="action-buttons-group">
            <a href="<?php echo base_url("anjuman/generatemiqaatinvoice?miqaat_type=1"); ?>" class="btn-action btn-action-primary">
              <i class="fa-solid fa-file-invoice-dollar"></i> Create Invoice
            </a>
            <a href="<?php echo base_url("anjuman/updatemiqaatinvoice?miqaat_type=1"); ?>" class="btn-action btn-action-secondary">
              <i class="fa-solid fa-file-pen"></i> Update Invoice
            </a>
            <a href="<?php echo base_url("anjuman/miqaatinvoicepayment?miqaat_type=1"); ?>" class="btn-action btn-action-primary">
              <i class="fa-solid fa-wallet"></i> Receive Payment
            </a>
          </div>
        </div>
      </div>

      <!-- Ashara -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="niyaz-card card-ashara">
          <div class="text-center w-100 mb-3">
            <div class="icon-box">
              <i class="fa-solid fa-money-bill-wheat fa-2x"></i>
            </div>
            <h4 class="card-title">Ashara Miqaat</h4>
            <p class="card-text">Generate Ashara Miqaat invoices & receive payment</p>
          </div>
          <div class="action-buttons-group">
            <a href="<?php echo base_url("anjuman/generatemiqaatinvoice?miqaat_type=2"); ?>" class="btn-action btn-action-primary">
              <i class="fa-solid fa-file-invoice-dollar"></i> Create Invoice
            </a>
            <a href="<?php echo base_url("anjuman/updatemiqaatinvoice?miqaat_type=2"); ?>" class="btn-action btn-action-secondary">
              <i class="fa-solid fa-file-pen"></i> Update Invoice
            </a>
            <a href="<?php echo base_url("anjuman/miqaatinvoicepayment?miqaat_type=2"); ?>" class="btn-action btn-action-primary">
              <i class="fa-solid fa-wallet"></i> Receive Payment
            </a>
          </div>
        </div>
      </div>

      <!-- General -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="niyaz-card card-general">
          <div class="text-center w-100 mb-3">
            <div class="icon-box">
              <i class="fa-solid fa-money-bill-wheat fa-2x"></i>
            </div>
            <h4 class="card-title">General Miqaat</h4>
            <p class="card-text">Generate General Miqaat invoices & receive payment</p>
          </div>
          <div class="action-buttons-group">
            <a href="<?php echo base_url("anjuman/generatemiqaatinvoice?miqaat_type=3"); ?>" class="btn-action btn-action-primary">
              <i class="fa-solid fa-file-invoice-dollar"></i> Create Invoice
            </a>
            <a href="<?php echo base_url("anjuman/updatemiqaatinvoice?miqaat_type=3"); ?>" class="btn-action btn-action-secondary">
              <i class="fa-solid fa-file-pen"></i> Update Invoice
            </a>
            <a href="<?php echo base_url("anjuman/miqaatinvoicepayment?miqaat_type=3"); ?>" class="btn-action btn-action-primary">
              <i class="fa-solid fa-wallet"></i> Receive Payment
            </a>
          </div>
        </div>
      </div>

      <!-- Ladies -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="niyaz-card card-ladies">
          <div class="text-center w-100 mb-3">
            <div class="icon-box">
              <i class="fa-solid fa-money-bill-wheat fa-2x"></i>
            </div>
            <h4 class="card-title">Ladies Miqaat</h4>
            <p class="card-text">Generate Ladies Miqaat invoices & receive payment</p>
          </div>
          <div class="action-buttons-group">
            <a href="<?php echo base_url("anjuman/generatemiqaatinvoice?miqaat_type=4"); ?>" class="btn-action btn-action-primary">
              <i class="fa-solid fa-file-invoice-dollar"></i> Create Invoice
            </a>
            <a href="<?php echo base_url("anjuman/updatemiqaatinvoice?miqaat_type=4"); ?>" class="btn-action btn-action-secondary">
              <i class="fa-solid fa-file-pen"></i> Update Invoice
            </a>
            <a href="<?php echo base_url("anjuman/miqaatinvoicepayment?miqaat_type=4"); ?>" class="btn-action btn-action-primary">
              <i class="fa-solid fa-wallet"></i> Receive Payment
            </a>
          </div>
        </div>
      </div>

      <!-- Extra Contribution -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="niyaz-card card-extra">
          <div class="text-center w-100 mb-3">
            <div class="icon-box">
              <i class="fa-solid fa-money-bill-wheat fa-2x"></i>
            </div>
            <h4 class="card-title">FMB Extra Niyaz</h4>
            <p class="card-text">Generate Extra Niyaz invoices & receive payment</p>
          </div>
          <div class="action-buttons-group">
            <a href="<?php echo base_url("anjuman/fmbgeneralcontribution/2"); ?>" class="btn-action btn-action-primary">
              <i class="fa-solid fa-wallet"></i> Receive Extra Niyaz Payment
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>