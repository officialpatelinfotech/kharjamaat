<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">

<!-- Custom Styling Scoped under #anjApp to match the Dashboard -->
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
#anjApp .icon-box.thaali-box {
  background: var(--blue-bg);
  color: var(--blue);
}
#anjApp .icon-box.niyaz-box {
  background: var(--green-bg);
  color: var(--green);
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
#anjApp .btn-thaali {
  background: var(--blue);
  color: #fff;
  border: 1px solid var(--blue);
}
#anjApp .btn-thaali:hover {
  background: #113ca4;
  border-color: #113ca4;
  color: #fff;
  box-shadow: 0 4px 12px rgba(29, 78, 216, 0.2);
}
#anjApp .btn-niyaz {
  background: var(--green);
  color: #fff;
  border: 1px solid var(--green);
}
#anjApp .btn-niyaz:hover {
  background: #124d33;
  border-color: #124d33;
  color: #fff;
  box-shadow: 0 4px 12px rgba(26, 102, 69, 0.2);
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
  color: var(--gold);
  text-decoration: none;
}
</style>

<div id="anjApp" class="margintopcontainer pt-5">
  <div class="container pb-4">
    <!-- Back Button -->
    <div class="mb-4">
      <a href="<?php echo base_url("anjuman") ?>" class="btn btn-back">
        <i class="fa-solid fa-arrow-left mr-1"></i> Back to Dashboard
      </a>
    </div>

    <!-- Header Panel -->
    <div class="anj-header">
      <div class="anj-header-inner">
        <div class="anj-title-group">
          <p class="anj-eyebrow">Fizalat Mawamil al-Burhaniyah</p>
          <h1 class="anj-title">FMB Module</h1>
        </div>
      </div>
    </div>

    <!-- Main Grid -->
    <div class="row mt-4">
      <!-- FMB Thaali Card -->
      <div class="col-md-6 mb-4">
        <div class="module-card">
          <div class="text-center w-100">
            <div class="icon-box thaali-box">
              <i class="fa-solid fa-bowl-food fa-2x"></i>
            </div>
            <h4 class="card-title">FMB Thaali</h4>
            <p class="card-text">Receive Thaali, Extra Thaali Payments & Manage Menu</p>
          </div>
          <a href="<?php echo base_url("anjuman/fmbthaali"); ?>" class="btn-custom btn-thaali">
            Go to FMB Thaali <i class="fa-solid fa-chevron-right"></i>
          </a>
        </div>
      </div>

      <!-- FMB Niyaz Card -->
      <div class="col-md-6 mb-4">
        <div class="module-card">
          <div class="text-center w-100">
            <div class="icon-box niyaz-box">
              <i class="fa-solid fa-bowl-food fa-2x"></i>
            </div>
            <h4 class="card-title">FMB Niyaz</h4>
            <p class="card-text">Generate Niyaz Invoice, Receive Niyaz & Extra Niyaz Payments</p>
          </div>
          <a href="<?php echo base_url("anjuman/fmbniyaz"); ?>" class="btn-custom btn-niyaz">
            Go to FMB Niyaz <i class="fa-solid fa-chevron-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>