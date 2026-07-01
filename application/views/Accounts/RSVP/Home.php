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

  .page-header-wrap { position: relative; display: flex; align-items: center; justify-content: center; min-height: 44px; margin-bottom: 6px; }
  .btn-back-nav { position: absolute; left: 0; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: 1.5px solid var(--border); background: var(--surface); color: var(--text-2); font-size: 14px; text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s; }
  .btn-back-nav:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.5rem; font-weight: 600; letter-spacing: -.3px; margin: 0; text-align: center; }
  .page-sub { font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); text-align: center; margin-top: 4px; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 18px 0 24px; }

  /* Flash alerts */
  .flash-alert { display: flex; align-items: center; gap: 10px; padding: 11px 16px; border-radius: var(--radius-sm); font-size: 0.83rem; font-weight: 600; margin-bottom: 16px; border: 1.5px solid; }
  .flash-success { background: var(--green-bg); color: var(--green); border-color: var(--green-border); }
  .flash-danger  { background: var(--red-bg);   color: var(--red);   border-color: var(--red-border); }
  .flash-warning { background: var(--amber-bg); color: var(--amber); border-color: var(--amber-border); }

  /* Search */
  .search-wrap { position: relative; max-width: 580px; margin: 0 auto 28px; }
  .search-icon-left { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gold); font-size: 13px; pointer-events: none; }
  .search-input { width: 100%; padding: 11px 14px 11px 38px; border: 1.5px solid var(--border); border-radius: 50px; background: var(--surface); font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.88rem; font-weight: 500; color: var(--text-1); box-shadow: var(--shadow-sm); outline: none; transition: border-color .2s, box-shadow .2s; }
  .search-input::placeholder { color: var(--text-3); font-weight: 400; }
  .search-input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(184,134,11,0.12); }
</style>

<div class="container margintopcontainer pt-5 pb-5">

  <!-- Flash messages -->
  <?php if ($this->session->flashdata('error')): ?>
    <div class="flash-alert flash-danger"><i class="fa fa-times-circle"></i><?= $this->session->flashdata('error') ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('success')): ?>
    <div class="flash-alert flash-success"><i class="fa fa-check-circle"></i><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('warning')): ?>
    <div class="flash-alert flash-warning"><i class="fa fa-exclamation-triangle"></i><?= $this->session->flashdata('warning') ?></div>
  <?php endif; ?>

  <!-- Header -->
  <div class="page-header-wrap pt-5">
    <a href="<?php echo base_url('accounts/home') ?>" class="btn-back-nav"><i class="fa fa-arrow-left"></i></a>
    <h1 class="page-heading">RSVP Dashboard</h1>
  </div>
  <p class="page-sub">Upcoming miqaats &amp; your RSVP status</p>
  <hr class="section-divider">

  <!-- Search -->
  <div class="search-wrap">
    <i class="fa fa-search search-icon-left"></i>
    <input id="miqaat-filter" type="search" class="search-input" placeholder="Search by name, type or date…" autocomplete="off">
  </div>

  <!-- Cards (rendered by partial — same as AJAX response target) -->
  <?php $this->load->view('Accounts/RSVP/_miqaat_cards', ['miqaats' => $miqaats, 'rsvp_overview' => $rsvp_overview]); ?>

</div>

<script>
  $(function () {
    var searchTimer = null;
    $("#miqaat-filter").on('input', function () {
      clearTimeout(searchTimer);
      var q = $(this).val().trim();
      searchTimer = setTimeout(function () {
        $.getJSON("<?php echo base_url('accounts/rsvp_search'); ?>", { q: q })
          .done(function (res) {
            if (res.success) {
              if ($('#miqaat-cards').length) {
                $('#miqaat-cards').replaceWith(res.html);
              } else {
                $("#miqaat-filter").closest('.search-wrap').after(res.html);
              }
            }
          });
      }, 300);
    });

    $(".flash-alert").delay(3000).fadeOut(500);
  });
</script>