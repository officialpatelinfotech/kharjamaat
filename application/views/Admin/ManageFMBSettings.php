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

  /* ── Premium Admin Dashboard Cards Grid ── */
  .admin-card-link {
    text-decoration: none !important;
    color: inherit;
    display: block;
    height: 100%;
  }
  .admin-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 28px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    height: 100%;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
  }
  .admin-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow);
    border-color: var(--gold);
  }
  .admin-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 100%);
    transform: scaleX(0);
    transition: transform 0.2s ease;
    transform-origin: left;
  }
  .admin-card:hover::after {
    transform: scaleX(1);
  }
  .admin-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    background: var(--surface-2);
    color: var(--gold);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    margin-bottom: 18px;
    transition: background 0.2s, color 0.2s;
  }
  .admin-card:hover .admin-card-icon {
    background: var(--gold-muted);
    color: var(--gold);
  }
  .admin-card-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text-1);
    margin-bottom: 6px;
    line-height: 1.3;
  }
  .admin-card-desc {
    font-size: 0.76rem;
    color: var(--text-3);
    line-height: 1.4;
    margin: 0;
  }
</style>

<div class="container margintopcontainer pt-5">
  <!-- Back Button -->
  <div class="mb-4">
    <a href="<?php echo base_url('admin'); ?>" class="btn-back">
      <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
    </a>
  </div>

  <!-- Header Panel -->
  <div class="anj-header">
    <div class="anj-header-inner">
      <div class="anj-title-group">
        <p class="anj-eyebrow">Fizalat Mawamil al-Burhaniyah</p>
        <h1 class="anj-title">Manage FMB Thaali & Niyaz</h1>
      </div>
    </div>
  </div>

  <!-- Module Cards Grid -->
  <div class="row mt-4">
    <!-- 1. Create & Manage Miqaat -->
    <div class="col-12 col-sm-6 col-md-4 mb-4">
      <a href="<?php echo base_url('common/managemiqaat?from=admin/managefmbsettings'); ?>" class="admin-card-link">
        <div class="admin-card">
          <div class="admin-card-icon"><i class="fa-solid fa-calendar-days"></i></div>
          <h5 class="admin-card-title">Create & Manage Miqaat</h5>
          <p class="admin-card-desc">Configure, schedule, and view all miqaat events.</p>
        </div>
      </a>
    </div>

    <!-- 2. Manage Thaali Menu -->
    <div class="col-12 col-sm-6 col-md-4 mb-4">
      <a href="<?php echo base_url('common/fmbthaalimenu?from=admin/managefmbsettings'); ?>" class="admin-card-link">
        <div class="admin-card">
          <div class="admin-card-icon"><i class="fa-solid fa-utensils"></i></div>
          <h5 class="admin-card-title">Manage Thaali Menu</h5>
          <p class="admin-card-desc">Set and update weekly recipes and distribution items.</p>
        </div>
      </a>
    </div>

    <!-- 3. Manage Per Day Thaali Cost -->
    <div class="col-12 col-sm-6 col-md-4 mb-4">
      <a href="<?php echo base_url('admin/manageperdaythaalicost'); ?>" class="admin-card-link">
        <div class="admin-card">
          <div class="admin-card-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
          <h5 class="admin-card-title">Manage Per Day Thaali Cost</h5>
          <p class="admin-card-desc">Define day-wise pricing rules and base costs.</p>
        </div>
      </a>
    </div>

    <!-- 4. Manage Thaali Takhmeen -->
    <div class="col-12 col-sm-6 col-md-4 mb-4">
      <a href="<?php echo base_url('admin/managefmbtakhmeen'); ?>" class="admin-card-link">
        <div class="admin-card">
          <div class="admin-card-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
          <h5 class="admin-card-title">Manage Thaali Takhmeen</h5>
          <p class="admin-card-desc">Oversee annual takhmeen estimates and settings.</p>
        </div>
      </a>
    </div>

    <!-- 5. Manage Contribution Master -->
    <div class="col-12 col-sm-6 col-md-4 mb-4">
      <a href="<?php echo base_url('admin/fmbgeneralcontributionmaster'); ?>" class="admin-card-link">
        <div class="admin-card">
          <div class="admin-card-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
          <h5 class="admin-card-title">Manage Contribution Master</h5>
          <p class="admin-card-desc">Manage custom categories for contributions.</p>
        </div>
      </a>
    </div>

    <!-- 6. Manage Niyaz Invoice Amounts -->
    <div class="col-12 col-sm-6 col-md-4 mb-4">
      <a href="<?php echo base_url('admin/manageniyazamounts'); ?>" class="admin-card-link">
        <div class="admin-card">
          <div class="admin-card-icon"><i class="fa-solid fa-coins"></i></div>
          <h5 class="admin-card-title">Manage Niyaz Invoice Amounts</h5>
          <p class="admin-card-desc">Set up auto-generation amounts for Miqaat Niyaz.</p>
        </div>
      </a>
    </div>
  </div>
</div>