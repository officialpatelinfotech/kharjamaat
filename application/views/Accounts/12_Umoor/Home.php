<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:       #b8860b;
    --gold-light: #e6c84a;
    --gold-muted: #f5e9c0;
    --gold-deep:  #8a6408;
    --bg:         #faf7f0;
    --surface:    #ffffff;
    --surface-2:  #f7f4ec;
    --border:     #e8e0cc;
    --text-1:     #1a1610;
    --text-2:     #5a5244;
    --text-3:     #9c8f7a;
    --radius-sm:  8px;
    --radius:     14px;
    --radius-lg:  20px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow:     0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-lg:  0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
  }

  body {
    background: var(--bg);
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-1);
  }

  /* ── Page Header ── */
  .page-header-wrap {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 44px;
  }
  .btn-back-nav {
    position: absolute; left: 0;
    width: 40px; height: 40px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    font-size: 14px;
    text-decoration: none;
    box-shadow: var(--shadow-sm);
    transition: all .15s;
  }
  .btn-back-nav:hover {
    background: var(--gold-muted);
    border-color: var(--gold);
    color: var(--gold);
    text-decoration: none;
  }
  .page-heading {
    font-family: 'Literata', Georgia, serif;
    color: var(--gold);
    font-size: 1.65rem;
    font-weight: 600;
    letter-spacing: -.3px;
    margin: 0;
    text-align: center;
  }
  .page-sub {
    font-size: 0.8rem;
    color: var(--text-3);
    font-weight: 500;
    letter-spacing: .4px;
    text-align: center;
    margin-top: 5px;
  }
  .section-divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 20px 0 24px;
  }

  /* ── Search ── */
  .search-wrap {
    position: relative;
    max-width: 820px;
    margin: 0 auto 30px;
  }
  .search-input-field {
    width: 100%;
    padding: 14px 90px 14px 48px;
    border-radius: 50px;
    border: 1.5px solid var(--border);
    background: var(--surface);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.92rem;
    font-weight: 500;
    color: var(--text-1);
    box-shadow: var(--shadow-sm);
    transition: border-color .2s, box-shadow .2s;
    outline: none;
    display: block;
  }
  .search-input-field::placeholder { color: var(--text-3); font-weight: 400; }
  .search-input-field:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184,134,11,0.13), var(--shadow);
  }
  .search-icon-left {
    position: absolute;
    left: 19px; top: 50%; transform: translateY(-50%);
    color: var(--gold); font-size: 15px; pointer-events: none;
  }
  .kbd-hint {
    position: absolute;
    right: 14px; top: 50%; transform: translateY(-50%);
    display: flex; align-items: center; gap: 3px;
    pointer-events: none;
  }
  .kbd-hint kbd {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.6rem; font-weight: 700;
    color: var(--text-3);
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 5px;
    padding: 2px 6px;
    letter-spacing: .2px;
    box-shadow: 0 1px 0 var(--border);
  }

  /* ── Dropdown ── */
  .suggestions-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    left: 0; right: 0;
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-lg);
    z-index: 1050;
    display: none;
    overflow: hidden;
    max-height: 360px;
    overflow-y: auto;
  }
  .suggestions-dropdown::-webkit-scrollbar { width: 4px; }
  .suggestions-dropdown::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

  .sugg-header {
    padding: 9px 16px 8px;
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: .7px;
    text-transform: uppercase;
    color: var(--text-3);
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    position: sticky; top: 0; z-index: 1;
  }
  .suggestion-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 16px;
    cursor: pointer;
    border-bottom: 1px solid var(--border);
    transition: background .14s;
  }
  .suggestion-item:last-child { border-bottom: none; }
  .suggestion-item:hover { background: var(--gold-muted); }
  .suggestion-item:hover .sugg-arrow { opacity: 1; transform: translateX(0); color: var(--gold); }
  .suggestion-item:hover .sugg-icon-wrap { background: rgba(184,134,11,0.15); border-color: rgba(184,134,11,0.3); }

  .sugg-icon-wrap {
    width: 32px; height: 32px;
    border-radius: 8px;
    background: var(--surface-2);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 13px; color: var(--gold);
    transition: all .14s;
  }
  .sugg-text { flex: 1; min-width: 0; }
  .suggestion-name {
    font-size: 0.85rem; font-weight: 600; color: var(--text-1);
    display: block; line-height: 1.3;
  }
  .suggestion-umoor {
    font-size: 0.65rem; font-weight: 600; color: var(--text-3);
    letter-spacing: .3px; margin-top: 1px; display: block;
  }
  .sugg-arrow {
    font-size: 11px; color: var(--text-3);
    opacity: 0; transform: translateX(-5px);
    transition: all .15s; flex-shrink: 0;
  }
  .sugg-no-result {
    padding: 28px 16px;
    text-align: center;
    font-size: 0.83rem;
    color: var(--text-3);
    font-weight: 500;
  }
  .sugg-no-result .fa { display: block; font-size: 1.5rem; margin-bottom: 8px; opacity: .25; }

  /* ── Section label ── */
  .section-label {
    font-size: 0.67rem;
    font-weight: 700;
    letter-spacing: .7px;
    text-transform: uppercase;
    color: var(--text-3);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .section-label::after {
    content: ''; flex: 1;
    border-top: 1px solid var(--border);
  }

  /* ── Cards Grid ── */
  .umoor-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }
  @media (max-width: 1100px) { .umoor-grid { grid-template-columns: repeat(3, 1fr); } }
  @media (max-width: 700px)  { .umoor-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; } }
  @media (max-width: 400px)  { .umoor-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } }

  .umoor-card {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 24px 16px 22px;
    gap: 0;
    position: relative;
    overflow: hidden;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    cursor: pointer;
  }
  /* gold top bar on hover */
  .umoor-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
    opacity: 0;
    transition: opacity .2s;
  }
  /* warm tint overlay */
  .umoor-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(160deg, rgba(245,233,192,0) 0%, rgba(245,233,192,0.35) 100%);
    opacity: 0;
    transition: opacity .2s;
    pointer-events: none;
  }
  .umoor-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(184,134,11,0.5);
    text-decoration: none;
    color: inherit;
  }
  .umoor-card:hover::before { opacity: 1; }
  .umoor-card:hover::after  { opacity: 1; }

  /* image wrapper */
  .card-img-wrap {
    width: 136px; height: 136px;
    border-radius: 22px;
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    flex-shrink: 0;

    transition: background .2s, border-color .2s, box-shadow .2s;
    position: relative; z-index: 1;
  }
  .umoor-card:hover .card-img-wrap {
    background: var(--gold-muted);
    border-color: rgba(184,134,11,0.4);
    box-shadow: 0 4px 16px rgba(184,134,11,0.2);
  }
  .card-img-wrap img {
    width: 108px; height: 108px;
    object-fit: contain; display: block;
    transition: transform .2s ease;
  }
  .umoor-card:hover .card-img-wrap img { transform: scale(1.07); }

  .card-img-wrap .fa-icon-fallback {
    font-size: 44px; color: var(--gold);
  }

  .card-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--text-2);
    text-align: center;
    line-height: 1.4;
    transition: color .15s;
    position: relative; z-index: 1;
  }
  .umoor-card:hover .card-label { color: var(--gold-deep); }

  /* Others special */
  .umoor-card.card-others .card-img-wrap {
    background: var(--gold-muted);
    border-color: rgba(184,134,11,0.3);
  }

  /* ── Responsive text ── */
  @media (max-width: 575px) {
    .page-heading { font-size: 1.25rem; }
    .card-img-wrap { width: 96px; height: 96px; border-radius: 16px; margin-bottom: 12px; }
    .card-img-wrap img { width: 72px; height: 72px; }
    .umoor-card { padding: 20px 12px 16px; }
    .card-label { font-size: 0.75rem; min-height: 34px; margin-bottom: 10px; }
    .kbd-hint { display: none; }
  }
</style>

<div class="container margintopcontainer pt-5">

  <!-- ── Header ── -->
  <div class="page-header-wrap mb-2">
    <a href="<?= base_url('accounts') ?>" class="btn-back-nav"><i class="fa fa-arrow-left"></i></a>
    <h1 class="page-heading">Raza Request</h1>
  </div>
  <p class="page-sub">Anjuman-e-Saifee <?php echo htmlspecialchars(jamaat_name(), ENT_QUOTES, 'UTF-8'); ?></p>

  <hr class="section-divider">

  <!-- ── Search ── -->
  <div class="search-wrap">
    <i class="fa fa-search search-icon-left"></i>
    <input
      type="text"
      id="razaSearch"
      class="search-input-field"
      placeholder="Search Raza forms…"
      autocomplete="off"
    >
    <div class="kbd-hint">
      <kbd>Ctrl</kbd><span style="font-size:.6rem;color:var(--text-3);font-weight:600;">+</span><kbd>K</kbd>
    </div>
    <div id="searchSuggestions" class="suggestions-dropdown"></div>
  </div>

  <!-- ── Grid ── -->
  <div class="section-label">All Categories</div>

  <div class="umoor-grid" id="razaCardsContainer">

    <a class="umoor-card" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorDeeniyah') ?>">
      <div class="card-img-wrap">
        <img src="<?php echo base_url('assets/Umoor_Deeniyah.png'); ?>" alt="Umoor Deeniyah"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <i class="fa fa-star fa-icon-fallback" style="display:none"></i>
      </div>
      <div class="card-label">Umoor Deeniyah</div>

    </a>

    <a class="umoor-card" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorTalimiyah') ?>">
      <div class="card-img-wrap">
        <img src="<?php echo base_url('assets/Umoor_Talimiyah.png'); ?>" alt="Umoor Talimiyah"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <i class="fa fa-graduation-cap fa-icon-fallback" style="display:none"></i>
      </div>
      <div class="card-label">Umoor Talimiyah</div>

    </a>

    <a class="umoor-card" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorMarafiqBurhaniyah') ?>">
      <div class="card-img-wrap">
        <img src="<?php echo base_url('assets/Umoor_Marafiq_Burhaniyah.png'); ?>" alt="Umoor Marafiq Burhaniyah"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <i class="fa fa-building fa-icon-fallback" style="display:none"></i>
      </div>
      <div class="card-label">Umoor Marafiq Burhaniyah</div>

    </a>

    <a class="umoor-card" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorMaaliyah') ?>">
      <div class="card-img-wrap">
        <img src="<?php echo base_url('assets/Umoor_Maaliyah.png'); ?>" alt="Umoor Maaliyah"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <i class="fa fa-money fa-icon-fallback" style="display:none"></i>
      </div>
      <div class="card-label">Umoor Maaliyah</div>

    </a>

    <a class="umoor-card" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorMawaridBashariyah') ?>">
      <div class="card-img-wrap">
        <img src="<?php echo base_url('assets/Umoor_Mawarid_Bashariyah.png'); ?>" alt="Umoor Mawarid Bashariyah"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <i class="fa fa-users fa-icon-fallback" style="display:none"></i>
      </div>
      <div class="card-label">Umoor Mawarid Bashariyah</div>

    </a>

    <a class="umoor-card" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorDakheliyah') ?>">
      <div class="card-img-wrap">
        <img src="<?php echo base_url('assets/Umoor_Dakheliyah.png'); ?>" alt="Umoor Dakheliya"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <i class="fa fa-home fa-icon-fallback" style="display:none"></i>
      </div>
      <div class="card-label">Umoor Dakheliya</div>

    </a>

    <a class="umoor-card" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorKharejiyah') ?>">
      <div class="card-img-wrap">
        <img src="<?php echo base_url('assets/Umoor_Kharijiyah.png'); ?>" alt="Umoor Kharejiyah"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <i class="fa fa-globe fa-icon-fallback" style="display:none"></i>
      </div>
      <div class="card-label">Umoor Kharejiyah</div>

    </a>

    <a class="umoor-card" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorIqtesadiyah') ?>">
      <div class="card-img-wrap">
        <img src="<?php echo base_url('assets/Umoor_Iqtesadiyah.png'); ?>" alt="Umoor Iqtesadiyah"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <i class="fa fa-bar-chart fa-icon-fallback" style="display:none"></i>
      </div>
      <div class="card-label">Umoor Iqtesadiyah</div>

    </a>

    <a class="umoor-card" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorFMB') ?>">
      <div class="card-img-wrap">
        <img src="<?php echo base_url('assets/Umoor_FMB.png'); ?>" alt="Umoor FMB"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <i class="fa fa-cutlery fa-icon-fallback" style="display:none"></i>
      </div>
      <div class="card-label">Umoor FMB</div>

    </a>

    <a class="umoor-card" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorAl-Qaza') ?>">
      <div class="card-img-wrap">
        <img src="<?php echo base_url('assets/Umoor_al-Qaza.png'); ?>" alt="Umoor Al-Qaza"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <i class="fa fa-gavel fa-icon-fallback" style="display:none"></i>
      </div>
      <div class="card-label">Umoor Al-Qaza</div>

    </a>

    <a class="umoor-card" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorAl-Amlaak') ?>">
      <div class="card-img-wrap">
        <img src="<?php echo base_url('assets/Umoor_al-Amlaak.png'); ?>" alt="Umoor Al-Amlaak"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <i class="fa fa-key fa-icon-fallback" style="display:none"></i>
      </div>
      <div class="card-label">Umoor Al-Amlaak</div>

    </a>

    <a class="umoor-card" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorAl-Sehhat') ?>">
      <div class="card-img-wrap">
        <img src="<?php echo base_url('assets/Umoor_al-Sehhat.png'); ?>" alt="Umoor Al-Sehhat"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <i class="fa fa-heartbeat fa-icon-fallback" style="display:none"></i>
      </div>
      <div class="card-label">Umoor Al-Sehhat</div>

    </a>

    <a class="umoor-card card-others" href="<?= base_url('accounts/NewRaza') ?>">
      <div class="card-img-wrap">
        <i class="fa fa-ellipsis-h fa-icon-fallback"></i>
      </div>
      <div class="card-label">Others</div>

    </a>

  </div><!-- /.umoor-grid -->

</div><!-- /.container -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

  /* ── Ctrl+K focuses search ── */
  $(document).on('keydown', function (e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
      e.preventDefault();
      $('#razaSearch').focus().select();
    }
    if (e.key === 'Escape') {
      $('#searchSuggestions').hide();
      $('#razaSearch').blur();
    }
  });

  /* ── Exact original search logic ── */
  const razaData = <?= json_encode($raza_types) ?>;

  razaData.unshift({
    name: "Others",
    umoor: "Others",
    id: "others"
  });

  const iconMap = {
    UmoorDeeniyah: 'fa-star',
    UmoorTalimiyah: 'fa-graduation-cap',
    UmoorMarafiqBurhaniyah: 'fa-building',
    UmoorMaaliyah: 'fa-money',
    UmoorMawaridBashariyah: 'fa-users',
    UmoorDakheliyah: 'fa-home',
    UmoorKharejiyah: 'fa-globe',
    UmoorIqtesadiyah: 'fa-bar-chart',
    UmoorFMB: 'fa-cutlery',
    'UmoorAl-Qaza': 'fa-gavel',
    'UmoorAl-Amlaak': 'fa-key',
    'UmoorAl-Sehhat': 'fa-heartbeat',
    Others: 'fa-ellipsis-h'
  };

  function showSuggestions(filteredResults) {
    const suggestions = $('#searchSuggestions');
    suggestions.empty();

    if (filteredResults.length > 0) {
      suggestions.append('<div class="sugg-header">Suggestions</div>');
      filteredResults.forEach(item => {
        const icon = iconMap[item.umoor] || 'fa-file-text-o';
        const tag  = item.umoor
          ? item.umoor.replace(/^Umoor/, '').replace(/([A-Z])/g, ' $1').trim()
          : '';
        suggestions.append(`
          <div class="suggestion-item" data-umoor="${item.umoor}" data-id="${item.id || ''}">
            <div class="sugg-icon-wrap"><i class="fa ${icon}"></i></div>
            <div class="sugg-text">
              <span class="suggestion-name">${item.name}</span>
              <span class="suggestion-umoor">${tag}</span>
            </div>
            <i class="fa fa-chevron-right sugg-arrow"></i>
          </div>
        `);
      });
      suggestions.show();
    } else {
      suggestions.append(`
        <div class="sugg-no-result">
          <i class="fa fa-search"></i>
          No results found
        </div>
      `);
      suggestions.show();
    }
  }

  /* Show all on focus */
  $('#razaSearch').on('focus', function () {
    showSuggestions(razaData);
  });

  /* Filter on input */
  $('#razaSearch').on('input', function () {
    const searchTerm = $(this).val().toLowerCase().trim();
    if (searchTerm.length === 0) {
      showSuggestions(razaData);
      return;
    }
    const filteredResults = razaData.filter(item =>
      item.name.toLowerCase().includes(searchTerm) ||
      (item.umoor && item.umoor.toLowerCase().includes(searchTerm))
    );
    showSuggestions(filteredResults);
  });

  /* Suggestion click — exact original logic */
  $(document).on('click', '.suggestion-item', function () {
    const umoorValue = $(this).data('umoor');
    const razaId     = $(this).data('id');

    if (umoorValue === 'Others') {
      window.location.href = `<?= base_url('accounts/NewRaza') ?>`;
      return;
    }

    const $form = $('<form>', {
      method: 'POST',
      action: `<?= base_url('Umoor12/NewRazaBySearch?value=') ?>${umoorValue}`
    }).append(
      $('<input>', { type: 'hidden', name: 'razaId', value: razaId })
    );

    $('body').append($form);
    $form.submit();
  });

  /* Hide on outside click */
  $(document).on('click', function (e) {
    if (!$(e.target).closest('#razaSearch, #searchSuggestions').length) {
      $('#searchSuggestions').hide();
    }
  });

});
</script>