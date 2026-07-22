<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">

<div class="gold-theme-wrapper">
  <div class="container pt-5">
    <div class="row mb-3">
      <div class="col-12">
        <a href="<?php echo base_url('admin'); ?>" class="btn btn-sm btn-gold-outline" aria-label="Back to Admin Dashboard"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
      </div>
    </div>
    
    <div class="anj-header mb-4">
      <div class="anj-header-inner text-center">
        <div class="anj-title-group">
          <span class="anj-eyebrow">Settings</span>
          <h2 class="anj-title">Expense &amp; Budget Module</h2>
        </div>
      </div>
    </div>

    <div class="row g-4 align-items-stretch justify-content-center">
      <div class="col-12 col-md-6 col-lg-5 d-flex">
        <a href="<?php echo site_url('admin/expense/items'); ?>" class="text-decoration-none w-100">
          <div class="card h-100 expense-card shadow-sm">
            <div class="card-body d-flex flex-column text-center p-4">
              <div class="overview-icon mb-3 mx-auto icon-green">
                <i class="fa fa-list-check fa-2x"></i>
              </div>
              <h5 class="fw-semibold text-dark mb-2">Expense Sections</h5>
              <p class="flex-grow-1 small text-muted mb-4">Manage expense categories, sub sectors and sections.</p>
              <div>
                <span class="btn btn-sm btn-gold px-4">Open &raquo;</span>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>

<style>
  .gold-theme-wrapper {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #faf7f0;
    min-height: calc(100vh - 57px);
    margin-top: 57px;
    padding-bottom: 50px;
    color: #1a1610;
  }

  .anj-header-inner {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
    border-radius: 16px;
    padding: 24px 30px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,.05);
  }
  .anj-header-inner::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
  }
  .anj-eyebrow {
    font-size: .67rem;
    font-weight: 700;
    letter-spacing: 1.4px;
    text-transform: uppercase;
    color: rgba(255,255,255,.6);
    margin-bottom: 4px;
    display: block;
  }
  .anj-title {
    font-family: 'Literata', Georgia, serif;
    font-size: 1.6rem;
    font-weight: 600;
    color: #fff;
    line-height: 1.15;
    margin: 0;
  }

  .btn-gold-outline {
    color: #b8860b;
    border-color: #e8e0cc;
    background: #ffffff;
    font-weight: 600;
    transition: all 0.2s;
  }
  .btn-gold-outline:hover {
    background: #f5e9c0;
    color: #78520a;
    border-color: #b8860b;
  }

  .btn-gold {
    background: #b8860b;
    color: #ffffff;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    transition: all 0.2s;
  }
  .btn-gold:hover {
    background: #78520a;
    color: #ffffff;
  }

  .expense-card {
    background: #ffffff;
    border: 1px solid #e8e0cc;
    border-radius: 14px;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
  }
  .expense-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(184,134,11,0.08) !important;
    border-color: #b8860b;
  }

  .overview-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #eff6ff;
    color: #1d4ed8;
  }
  .overview-icon.icon-green {
    background: #eaf4ee;
    color: #1a6645;
  }
</style>
