<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:       #b8860b; --gold-light: #e6c84a; --gold-muted: #f5e9c0; --gold-deep: #8a6408;
    --bg:         #faf7f0; --surface: #ffffff; --surface-2: #f7f4ec; --border: #e8e0cc;
    --text-1:     #1a1610; --text-2: #5a5244; --text-3: #9c8f7a;
    --green:      #1a6645; --green-bg: #eaf4ee; --green-border: #bbf7d0;
    --red:        #b91c1c; --red-bg: #fef2f2; --red-border: #fecaca;
    --blue:       #1d4ed8; --blue-bg: #eff6ff; --blue-border: #bfdbfe;
    --amber:      #b45309; --amber-bg: #fffbeb; --amber-border: #fde68a;
    --radius-sm:  8px; --radius: 14px; --radius-lg: 20px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow:     0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-lg:  0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.05);
  }
  body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); }

  /* header */
  .page-header-wrap { position: relative; display: flex; align-items: center; justify-content: center; min-height: 44px; margin-bottom: 6px; }
  .btn-back-nav { position: absolute; left: 0; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: 1.5px solid var(--border); background: var(--surface); color: var(--text-2); font-size: 14px; text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s; }
  .btn-back-nav:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.45rem; font-weight: 600; letter-spacing: -.3px; margin: 0; text-align: center; }
  .miqaat-sub { font-size: 0.8rem; font-weight: 600; color: var(--text-3); text-align: center; margin-top: 5px; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 16px 0 22px; }

  /* flash */
  .flash-alert { display: flex; align-items: center; gap: 10px; padding: 11px 16px; border-radius: var(--radius-sm); font-size: 0.83rem; font-weight: 600; margin-bottom: 14px; border: 1.5px solid; }
  .flash-success { background: var(--green-bg); color: var(--green); border-color: var(--green-border); }
  .flash-danger  { background: var(--red-bg);   color: var(--red);   border-color: var(--red-border); }
  .flash-warning { background: var(--amber-bg); color: var(--amber); border-color: var(--amber-border); }

  /* form card */
  .rsvp-card {
    max-width: 560px; margin: 0 auto;
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius-lg); box-shadow: var(--shadow);
    overflow: hidden;
  }
  .rsvp-card::before { content: ''; display: block; height: 3px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); }

  .rsvp-card-header { padding: 16px 22px 14px; background: var(--surface-2); border-bottom: 1px solid var(--border); }
  .rsvp-card-title { font-size: 0.78rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-2); display: flex; align-items: center; gap: 8px; margin: 0; }
  .rsvp-card-title .fa { width: 26px; height: 26px; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; background: var(--gold-muted); color: var(--gold); }

  .rsvp-card-body { padding: 20px 22px; }

  /* member list */
  .member-list { list-style: none; margin: 0 0 18px; padding: 0; display: flex; flex-direction: column; gap: 8px; }
  .member-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); background: var(--surface);
    transition: border-color .15s, background .15s; cursor: pointer;
  }
  .member-item:has(input:checked) { border-color: var(--green); background: var(--green-bg); }
  .member-item label { display: flex; flex-direction: column; gap: 2px; cursor: pointer; flex: 1; margin: 0; }
  .member-name { font-size: 0.88rem; font-weight: 600; color: var(--text-1); }
  .member-id   { font-size: 0.68rem; color: var(--text-3); font-weight: 500; }
  .member-item input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--green); flex-shrink: 0; cursor: pointer; }

  .no-members { font-size: 0.85rem; color: var(--text-3); padding: 16px 0; text-align: center; }

  /* section divider inside form */
  .form-sep { border: none; border-top: 1px dashed var(--border); margin: 18px 0; }

  /* guest toggle */
  .guest-toggle-wrap {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 16px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); background: var(--surface);
    margin-bottom: 14px; cursor: pointer; transition: border-color .15s, background .15s;
  }
  .guest-toggle-wrap:has(input:checked) { border-color: var(--amber); background: var(--amber-bg); }
  .guest-toggle-wrap input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--amber); flex-shrink: 0; cursor: pointer; }
  .guest-toggle-label { font-size: 0.85rem; font-weight: 600; color: var(--text-2); cursor: pointer; margin: 0; }

  /* guest fields */
  #guestCounts { background: var(--surface-2); border: 1.5px solid var(--border); border-radius: var(--radius-sm); padding: 14px 16px; margin-bottom: 16px; }
  .guest-label { font-size: 0.65rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); margin-bottom: 6px; display: block; }
  .guest-input {
    width: 100%; height: 52px; padding: 0 16px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    background: var(--surface); font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 15px; font-weight: 500; color: var(--text-1);
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    transition: border-color .2s, box-shadow .2s;
    appearance: none; -webkit-appearance: none; box-sizing: border-box;
  }
  .guest-input:focus { border-color: var(--gold); outline: none; box-shadow: 0 0 0 3px rgba(184,134,11,.12); }

  /* submit button */
  .btn-submit-rsvp {
    width: 100%; padding: 12px 20px; border-radius: var(--radius-sm); border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.9rem; font-weight: 700; cursor: pointer;
    box-shadow: 0 2px 8px rgba(184,134,11,.28); transition: all .18s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
  }
  .btn-submit-rsvp:hover { background: linear-gradient(135deg, var(--gold-deep), #6b4d06); box-shadow: 0 4px 14px rgba(184,134,11,.38); transform: translateY(-1px); }

  @media (max-width: 575px) {
    .page-heading { font-size: 1.2rem; }
    .rsvp-card-body { padding: 16px; }
  }
</style>

<div class="container margintopcontainer pt-5 pb-5">

  <!-- Flash messages -->
  <?php if ($this->session->flashdata('error')): ?>
    <div class="flash-alert flash-danger" style="max-width:560px;margin:0 auto 14px;"><i class="fa fa-times-circle"></i><?= $this->session->flashdata('error') ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('success')): ?>
    <div class="flash-alert flash-success" style="max-width:560px;margin:0 auto 14px;"><i class="fa fa-check-circle"></i><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('warning')): ?>
    <div class="flash-alert flash-warning" style="max-width:560px;margin:0 auto 14px;"><i class="fa fa-exclamation-triangle"></i><?= $this->session->flashdata('warning') ?></div>
  <?php endif; ?>

  <!-- Header -->
  <div class="page-header-wrap pt-5">
    <a href="<?php echo base_url('accounts/rsvp_list') ?>" class="btn-back-nav"><i class="fa fa-arrow-left"></i></a>
    <h1 class="page-heading"><?php echo htmlspecialchars($miqaat['name']); ?></h1>
  </div>
  <p class="miqaat-sub"><i class="fa fa-calendar" style="margin-right:5px;color:var(--gold);"></i><?php echo date("d F Y", strtotime($miqaat['date'])); ?></p>
  <hr class="section-divider">

  <!-- RSVP Form Card -->
  <div class="rsvp-card">
    <div class="rsvp-card-header">
      <h5 class="rsvp-card-title"><i class="fa fa-users"></i> Select Family Members</h5>
    </div>
    <div class="rsvp-card-body">
      <form method="post" action="<?php echo base_url('accounts/submit_general_rsvp'); ?>" id="rsvp-form">
        <input type="hidden" name="miqaat_id" value="<?php echo htmlspecialchars($miqaat['id']); ?>" />

        <!-- Family members -->
        <?php if (!empty($family)): ?>
          <ul class="member-list">
            <?php foreach ($family as $member): ?>
              <li class="member-item">
                <label for="rsvp_<?= htmlspecialchars($member['ITS_ID']) ?>">
                  <span class="member-name"><?= htmlspecialchars($member['First_Name']) . ' ' . htmlspecialchars($member['Surname']) ?></span>
                  <span class="member-id">ID: <?= htmlspecialchars($member['ITS_ID']) ?></span>
                </label>
                <input type="checkbox"
                  name="rsvp_members[]"
                  id="rsvp_<?= htmlspecialchars($member['ITS_ID']) ?>"
                  value="<?= htmlspecialchars($member['ITS_ID']) ?>"
                  <?= in_array($member['ITS_ID'], $rsvp_miqaat_ids) ? 'checked' : '' ?> />
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="no-members"><i class="fa fa-user-times" style="margin-right:6px;"></i>No family members found.</p>
        <?php endif; ?>

        <hr class="form-sep">

        <!-- Guests toggle -->
        <div class="guest-toggle-wrap">
          <input type="checkbox" id="guest_rsvp_chk" name="guest_rsvp" value="1"
            <?= !empty($guest_rsvp) ? 'checked' : '' ?> />
          <label class="guest-toggle-label" for="guest_rsvp_chk">
            <i class="fa fa-user-plus" style="margin-right:6px;color:var(--amber);"></i>Include Guests
          </label>
        </div>

        <!-- Guest counts -->
        <div id="guestCounts" style="<?= !empty($guest_rsvp) ? '' : 'display:none;' ?>">
          <div class="row g-2">
            <div class="col-12 col-md-4">
              <label for="guest_gents" class="guest-label">Gents</label>
              <input type="number" min="0" step="1" class="guest-input" id="guest_gents" name="guest_gents"
                value="<?= isset($guest_rsvp['gents'])    ? (int)$guest_rsvp['gents']    : 0 ?>" />
            </div>
            <div class="col-12 col-md-4">
              <label for="guest_ladies" class="guest-label">Ladies</label>
              <input type="number" min="0" step="1" class="guest-input" id="guest_ladies" name="guest_ladies"
                value="<?= isset($guest_rsvp['ladies'])   ? (int)$guest_rsvp['ladies']   : 0 ?>" />
            </div>
            <div class="col-12 col-md-4">
              <label for="guest_children" class="guest-label">Children</label>
              <input type="number" min="0" step="1" class="guest-input" id="guest_children" name="guest_children"
                value="<?= isset($guest_rsvp['children']) ? (int)$guest_rsvp['children'] : 0 ?>" />
            </div>
          </div>
        </div>

        <button type="submit" class="btn-submit-rsvp">
          <i class="fa fa-check"></i> Submit RSVP
        </button>
      </form>
    </div>
  </div>

</div>

<!-- ══ ALL JS IDENTICAL TO ORIGINAL ══ -->
<script>
(function () {
  if (window.jQuery) { $(".flash-alert").delay(3000).fadeOut(500); }
  else { setTimeout(function () { document.querySelectorAll('.flash-alert').forEach(function(a){ a.style.transition='opacity .5s'; a.style.opacity='0'; setTimeout(function(){ if(a.parentNode) a.parentNode.removeChild(a); },500); }); }, 3000); }

  function byId(id) { return document.getElementById(id); }
  var chk      = byId('guest_rsvp_chk');
  var counts   = byId('guestCounts');
  var gents    = byId('guest_gents');
  var ladies   = byId('guest_ladies');
  var children = byId('guest_children');

  function setRequired(el, req) {
    if (!el) return;
    if (req) { el.setAttribute('required','required'); el.removeAttribute('disabled'); }
    else     { el.removeAttribute('required'); el.setAttribute('disabled','disabled'); }
  }
  function updateVisibility() {
    if (!chk || !counts) return;
    var checked = false;
    try { checked = chk.checked; } catch(e) {}
    if (checked) { counts.style.display=''; setRequired(gents,true); setRequired(ladies,true); setRequired(children,true); }
    else         { counts.style.display='none'; setRequired(gents,false); setRequired(ladies,false); setRequired(children,false); }
  }
  if (chk) chk.addEventListener('change', updateVisibility);
  updateVisibility();

  var form = document.getElementById('rsvp-form');
  if (form) {
    form.addEventListener('submit', function(e) {
      if (chk && chk.checked) {
        function toInt(v) { var n=parseInt(String(v||'0').trim(),10); return isNaN(n)?NaN:n; }
        var a=toInt(gents&&gents.value), b=toInt(ladies&&ladies.value), c=toInt(children&&children.value);
        if (isNaN(a)||isNaN(b)||isNaN(c)||a<0||b<0||c<0) { e.preventDefault(); alert('Please enter valid non-negative integers for guest counts.'); return false; }
      }
    });
  }
})();
</script>