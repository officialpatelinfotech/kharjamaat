<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
/* ── CSS Variables ── */
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
  --shadow-lg:   0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
}

#umoorApp, #umoorApp *, #umoorApp *::before, #umoorApp *::after {
  box-sizing: border-box;
}
#umoorApp {
  font-family: 'Plus Jakarta Sans', sans-serif;
  color: var(--text-1);
  background: var(--bg);
  min-height: 100vh;
  padding-top: 57px;
}

/* ── Header banner ── */
#umoorApp .mm-header {
  background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
  border-radius: 22px;
  padding: 22px 28px;
  margin-bottom: 24px;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}
#umoorApp .mm-header::before {
  content: '';
  position: absolute;
  inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}
#umoorApp .mm-header::after {
  content: '';
  position: absolute;
  right: -50px;
  top: -50px;
  width: 200px;
  height: 200px;
  background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
  pointer-events: none;
}
#umoorApp .mm-eyebrow {
  font-size: .67rem;
  font-weight: 700;
  letter-spacing: 1.4px;
  text-transform: uppercase;
  color: rgba(255,255,255,.6);
  margin-bottom: 4px;
  position: relative;
  z-index: 1;
}
#umoorApp .mm-title {
  font-family: 'Literata', Georgia, serif;
  font-size: 1.5rem;
  font-weight: 600;
  color: #fff;
  line-height: 1.15;
  margin: 0;
  position: relative;
  z-index: 1;
}
#umoorApp .mm-title span {
  color: rgba(255,255,255,.72);
  font-size: .95rem;
  font-weight: 500;
  display: block;
  margin-top: 2px;
}
#umoorApp .mm-badge {
  position: relative;
  z-index: 1;
  flex-shrink: 0;
  background: rgba(255,255,255,.15);
  border: 1px solid rgba(255,255,255,.25);
  border-radius: 14px;
  padding: 10px 16px;
  backdrop-filter: blur(6px);
  text-align: center;
}
#umoorApp .mm-badge-val {
  font-size: 1.5rem;
  font-weight: 800;
  color: #fff;
  line-height: 1;
  display: block;
}
#umoorApp .mm-badge-lbl {
  font-size: .65rem;
  font-weight: 700;
  color: rgba(255,255,255,.65);
  letter-spacing: .5px;
  text-transform: uppercase;
  margin-top: 3px;
  display: block;
}

/* ── Surface card ── */
#umoorApp .surf {
  background: var(--surface);
  border-radius: 20px;
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
  padding: 20px 22px;
  margin-bottom: 18px;
  position: relative;
  overflow: hidden;
  transition: box-shadow .2s;
}
#umoorApp .surf:hover { box-shadow: var(--shadow); }

/* ── Section header ── */
#umoorApp .sec-hd {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-bottom: 10px;
  margin-bottom: 16px;
  border-bottom: 1.5px solid var(--border-light);
}
#umoorApp .sec-hd-title {
  font-size: .9rem;
  font-weight: 800;
  color: var(--text-2);
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 0;
}
#umoorApp .sec-hd-title .hd-icon {
  width: 26px;
  height: 26px;
  border-radius: 7px;
  background: var(--gold-muted);
  color: var(--gold);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: .72rem;
  flex-shrink: 0;
}

/* ── Action grid ── */
#umoorApp .action-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}
@media (min-width: 576px) { #umoorApp .action-grid { grid-template-columns: repeat(3, 1fr); } }
@media (min-width: 992px) { #umoorApp .action-grid { grid-template-columns: repeat(4, 1fr); } }

#umoorApp .action-btn {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 22px 12px;
  border-radius: 14px;
  text-decoration: none;
  overflow: hidden;
  min-height: 115px;
  transition: transform .15s, box-shadow .15s;
  box-shadow: var(--shadow);
}
#umoorApp .action-btn::after {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(255,255,255,0);
  transition: background .2s;
  pointer-events: none;
}
#umoorApp .action-btn:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-lg);
  text-decoration: none;
}
#umoorApp .action-btn:hover::after { background: rgba(255,255,255,.1); }

#umoorApp .ab-icon {
  width: 46px;
  height: 46px;
  border-radius: 12px;
  background: rgba(255,255,255,.22);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: inset 0 0 0 1px rgba(255,255,255,.3);
  font-size: 1.3rem;
  color: #fff;
  flex-shrink: 0;
}
#umoorApp .ab-label {
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .4px;
  text-transform: uppercase;
  color: #fff;
  text-align: center;
  line-height: 1.35;
}

/* ── Welcome note ── */
#umoorApp .welcome-note {
  background: var(--gold-muted);
  border: 1px solid #e6c84a55;
  border-radius: 14px;
  padding: 14px 20px;
  margin-bottom: 20px;
  box-shadow: 0 4px 14px rgba(184,134,11,.1);
  text-align: center;
}
#umoorApp .welcome-note p {
  margin: 0;
  color: var(--gold);
  font-weight: 700;
  font-size: .85rem;
  letter-spacing: .3px;
}
</style>

<div id="umoorApp">
  <div class="container pt-4 pb-5">

    <!-- ── Header Banner ── -->
    <div class="mm-header">
      <div>
        <p class="mm-eyebrow">Umoor Portal</p>
        <h1 class="mm-title">
          Anjuman-e-Saifee
          <span><?php echo htmlspecialchars(jamaat_name(), ENT_QUOTES, 'UTF-8'); ?></span>
        </h1>
      </div>
      <div class="mm-badge">
        <span class="mm-badge-val"><i class="fa fa-star" style="font-size:1.3rem;"></i></span>
        <span class="mm-badge-lbl">Umoor</span>
      </div>
    </div>

    <!-- ── Welcome note ── -->
    <div class="welcome-note">
      <p><i class="fa fa-user" style="margin-right:6px;"></i>Welcome, <?php echo htmlspecialchars($_SESSION['user']['username'] ?? ''); ?></p>
    </div>

    <!-- ── Quick Actions ── -->
    <div class="surf">
      <div class="sec-hd">
        <h4 class="sec-hd-title">
          <span class="hd-icon"><i class="fa fa-th-large"></i></span>
          Quick Actions
        </h4>
      </div>

      <?php
      $actions = [
        ['url' => base_url('Umoor/RazaRequest'),          'icon' => 'fa fa-hand-o-up',        'label' => 'Raza Request',         'bg' => '#8e44ad'],
        ['url' => base_url('umoor/asharaohbat'),           'icon' => 'fa fa-calendar',          'label' => 'Ashara Ohbat',         'bg' => '#d97706'],
        ['url' => base_url('umoor/ashara_attendance'),     'icon' => 'fa fa-calendar-check-o',  'label' => 'Ashara Attendance',    'bg' => '#870000'],
        
      ];
      ?>

      <div class="action-grid">
        <?php foreach ($actions as $act): ?>
        <a href="<?= $act['url'] ?>" class="action-btn" style="background:<?= $act['bg'] ?>;">
          <div class="ab-icon"><i class="<?= $act['icon'] ?>"></i></div>
          <div class="ab-label"><?= $act['label'] ?></div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</div>