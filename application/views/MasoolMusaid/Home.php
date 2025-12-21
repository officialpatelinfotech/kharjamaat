<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
  .icon {
    font-size: 40pt;
    margin: 10px 0;
    color: #ffffff;
  }

  .title {
    color: white;
  }

  .heading {
    color: #ad7e05;
    font-family: 'Amita', cursive;
  }

  .card {
    height: 153px;
  }

  .card:hover {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
  }

  .row a {
    text-decoration: none;
    color: inherit;
  }

  /* Match Jamaat/Amilsaheb layout */
  .chart-container {
    background: #fff;
    border-radius: 15px;
    padding: 18px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  }

  .chart-container.compact {
    padding: 18px;
  }

  .section-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
  }

  .mini-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    text-align: center;
  }

  .mini-card .stats-value {
    font-size: 1.2rem;
    font-weight: 700;
  }

  .mini-card .stats-label {
    font-size: .8rem;
    color: #777;
    letter-spacing: .6px;
  }

  .fmb-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 12px 14px;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .fmb-card .fmb-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .fmb-card .fmb-name {
    font-weight: 700;
    color: #222;
  }

  .fmb-card .fmb-amounts {
    display: flex;
    gap: 12px;
    font-size: .9rem;
    color: #555;
  }

  .fmb-card .fmb-amounts .val {
    font-weight: 700;
    color: #111;
  }
</style>
<div class="container margintopcontainer">
  <h1 class="text-center heading pt-5 mb-4">Welcome to Anjuman-e-Saifee Khar Jamaat</h1>
  <hr>
  <div class="chart-container compact weekly-summary">

    <!-- HEADER -->
    <div class="d-flex align-items-center justify-content-between">
      <h4 class="section-title text-center m-0 flex-grow-1">
        Thaali Signup for Current Month
      </h4>
    </div>

    <!-- VIEW DETAILS -->
    <div class="text-right my-2">
      <a id="thaali-details-btn" href="#" class="btn btn-sm btn-primary text-white">
        View details
      </a>
    </div>

    <?php
    /* ==========================
       HIJRI CONTEXT
       ========================== */
    $this->load->model('HijriCalendar');

    $hijri_today = $selected_hijri_parts
      ?? $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));

    $current_hijri_year = (int) ($hijri_today['hijri_year'] ?? 0);
    $current_hijri_month = (int) ($hijri_today['hijri_month'] ?? 0);

    // Month name from hijri_month table
    $monthRow = $this->db->where('id', $current_hijri_month)->get('hijri_month')->row_array();
    $current_hijri_month_name = $monthRow['hijri_month'] ?? '';

    // Prev / Next month logic (NO SKIP)
    $prev_month = $current_hijri_month - 1;
    $prev_year = $current_hijri_year;
    $next_month = $current_hijri_month + 1;
    $next_year = $current_hijri_year;

    if ($prev_month < 1) {
      $prev_month = 12;
      $prev_year--;
    }
    if ($next_month > 12) {
      $next_month = 1;
      $next_year++;
    }

    // Gregorian range
    $hijri_days = $this->HijriCalendar
      ->get_hijri_days_for_month_year($current_hijri_month, $current_hijri_year);

    $month_start = $hijri_days[0]['greg_date'] ?? date('Y-m-01');
    $month_end = $hijri_days[count($hijri_days) - 1]['greg_date'] ?? date('Y-m-t');
    ?>

    <script>
      (function () {
        const btn = document.getElementById('thaali-details-btn');
        if (!btn) return;

        if (!window.USER_NAME) {
          console.error('[THAALI LINK] USER_NAME not found');
          return;
        }

        const baseUrl = "<?= base_url('common/thaali_signups_breakdown'); ?>";

        const params = new URLSearchParams({
          from: 'masoolmusaid',
          start_date: "<?= $month_start ?>",
          end_date: "<?= $month_end ?>",
          sector: window.USER_NAME   // ✅ Saifee / SaifeeA / BurhaniB etc
        });

        const finalUrl = baseUrl + '?' + params.toString();
        btn.href = finalUrl;

        console.log('[THAALI LINK SET]', finalUrl);
      })();
    </script>


    <!-- HIJRI SWITCHER -->
    <div class="d-flex justify-content-center align-items-center hijri-switcher my-2">

      <a href="#" class="hijri-nav-btn" data-hijri-year="<?= $prev_year ?>" data-hijri-month="<?= $prev_month ?>">
        <div class="chev-box"><i class="fa fa-chevron-left"></i></div>
      </a>

      <div id="hijri-current-title" style="margin:0 18px;color:#0ea5a4;font-weight:600;">
        <?= htmlspecialchars($current_hijri_month_name . ' ' . $current_hijri_year) ?>
      </div>

      <a href="#" class="hijri-nav-btn" data-hijri-year="<?= $next_year ?>" data-hijri-month="<?= $next_month ?>">
        <div class="chev-box"><i class="fa fa-chevron-right"></i></div>
      </a>

    </div>

    <!-- LOADER -->
    <div id="monthLoader" class="text-center my-2" style="display:none;">
      <i class="fa fa-spinner fa-spin"></i> Loading month…
    </div>

    <!-- MONTH STATS -->
    <div id="thaali-month-block" class="row text-center">

      <div class="col-md-6 mb-2">
        <a href="#" class="open-hof-modal" data-modal-type="signed" data-hijri-year="<?= $current_hijri_year ?>"
          data-hijri-month="<?= $current_hijri_month ?>">
          <div class="mini-card">
            <div class="stats-value"><?= (int) ($month_stats['families_signed_up'] ?? 0) ?></div>
            <div class="stats-label">Sign up this month</div>
          </div>
        </a>
      </div>

      <div class="col-md-6 mb-2">
        <a href="#" class="open-hof-modal" data-modal-type="no" data-hijri-year="<?= $current_hijri_year ?>"
          data-hijri-month="<?= $current_hijri_month ?>">
          <div class="mini-card">
            <div class="stats-value"><?= (int) ($month_stats['no_thaali_count'] ?? 0) ?></div>
            <div class="stats-label">No sign up this month</div>
          </div>
        </a>
      </div>

    </div>

    <!-- =======================
       MODAL (REQUIRED)
       ======================= -->
    <div class="modal fade" id="hofListModal" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header py-2">
            <h6 class="modal-title" id="hofListLabel"></h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body">
            <div id="hofListLoading" class="text-center py-3" style="display:none;">
              <i class="fa fa-spinner fa-spin"></i> Loading...
            </div>
            <div id="hofListContainer" style="max-height:60vh;overflow:auto;"></div>
          </div>

        </div>
      </div>
    </div>



    <!-- =======================
       JS (MONTH + MODAL)
       ======================= -->
    <script>
      (function () {

        console.log('=== HIJRI DASHBOARD INITIALIZED ===');

        /* ======================================================
           USER SCOPE (USED ONLY FOR MODAL FILTERING)
        ====================================================== */
        const ALLOWED_SECTORS = ['BURHANI', 'MOHAMMEDI', 'NAJMI', 'SAIFEE', 'TAHERI'];
        const ALLOWED_SUBS = ['A', 'B', 'C'];

        function parseUserScope(userName) {
          if (!userName) return null;

          const name = userName.trim().toUpperCase();
          const last = name.slice(-1);
          const base = name.slice(0, -1);

          if (ALLOWED_SUBS.includes(last) && ALLOWED_SECTORS.includes(base)) {
            return { sector: base, sub: last };
          }
          if (ALLOWED_SECTORS.includes(name)) {
            return { sector: name, sub: null };
          }
          return null;
        }

        const USER_SCOPE = parseUserScope(window.USER_NAME || '');
        console.log('[USER_SCOPE]', USER_SCOPE);

        /* ======================================================
           MOBILE FORMATTER
           - 91XXXXXXXXXX → tel:
           - others → click to copy
        ====================================================== */
        function renderMobile(raw) {
          if (!raw) return '';

          const digits = String(raw).replace(/\D/g, '');

          // 🇮🇳 India number
          if (digits.startsWith('91') && digits.length === 12) {
            return `
        <a href="tel:+${digits}"
           style="color:blue;text-decoration:none;">
          +${digits}
        </a>`;
          }

          // 🌍 Other numbers → copy on click
          return `
      <span class="copy-mobile"
            data-mobile="${raw}"
            style="cursor:pointer;color:#0ea5a4;">
        ${raw}
      </span>`;
        }

        /* ======================================================
           COPY HANDLER
        ====================================================== */
        document.addEventListener('click', function (e) {
          const el = e.target.closest('.copy-mobile');
          if (!el) return;

          const num = el.dataset.mobile;
          navigator.clipboard.writeText(num).then(() => {
            const old = el.innerText;
            el.innerText = 'Copied ✔';
            setTimeout(() => el.innerText = old, 1200);
          });
        });

        /* ======================================================
           MODAL (JSON) – WORKING WITH YOUR CONTROLLER
        ====================================================== */
        $(document).on('click', '.open-hof-modal', function (e) {
          e.preventDefault();

          const type = $(this).data('modal-type'); // signed | no
          const y = $(this).data('hijri-year');
          const m = $(this).data('hijri-month');

          if (!y || !m) return;

          const url =
            window.location.pathname +
            `?hijri_year=${y}&hijri_month=${m}&format=json`;

          $('#hofListContainer').html('');
          $('#hofListLoading').show();
          $('#hofListModal').modal('show');

          fetch(url, { credentials: 'same-origin' })
            .then(r => r.json())
            .then(data => {

              let rows =
                type === 'signed'
                  ? data?.monthly_stats?.signed_hof_list || []
                  : data?.monthly_stats?.no_thaali_list || [];

              // 🔒 Sector filtering (matches backend logic)
              if (USER_SCOPE) {
                rows = rows.filter(r => {
                  const s = String(r.Sector || '').toUpperCase();
                  const sub = String(r.Sub_Sector || '').toUpperCase();
                  if (s !== USER_SCOPE.sector) return false;
                  if (USER_SCOPE.sub === null) return ALLOWED_SUBS.includes(sub);
                  return sub === USER_SCOPE.sub;
                });
              }

              $('#hofListLoading').hide();

              if (!rows.length) {
                $('#hofListContainer').html(
                  '<div class="text-muted text-center">No records found</div>'
                );
                return;
              }

              let html = `
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th>ITS</th>
                <th>Name</th>
                <th>Sector</th>
                <th>Sub Sector</th>
                <th>Mobile</th>
              </tr>
            </thead>
            <tbody>
        `;

              rows.forEach(r => {
                html += `
            <tr>
              <td>${r.ITS_ID || ''}</td>
              <td>${r.Full_Name || ''}</td>
              <td>${r.Sector || ''}</td>
              <td>${r.Sub_Sector || ''}</td>
              <td>${renderMobile(r.Mobile || r.RFM_Mobile)}</td>
            </tr>
          `;
              });

              html += '</tbody></table>';
              $('#hofListContainer').html(html);
            })
            .catch(() => {
              $('#hofListLoading').hide();
              $('#hofListContainer').html(
                '<div class="text-danger">Failed to load data</div>'
              );
            });
        });

        /* ======================================================
           HIJRI MONTH SWITCH (HTML RELOAD – CORRECT WAY)
        ====================================================== */
        function buildUrl(base, y, m) {
          try {
            const u = new URL(base, window.location.origin);
            u.searchParams.set('hijri_year', y);
            u.searchParams.set('hijri_month', m);
            u.searchParams.set('ajax', '1');
            return u.toString();
          } catch {
            return base + '?hijri_year=' + y + '&hijri_month=' + m + '&ajax=1';
          }
        }

        function loadMonth(year, month, pushState) {
          const url = buildUrl(window.location.pathname, year, month);

          fetch(url, { credentials: 'same-origin' })
            .then(r => r.text())
            .then(html => {
              const doc = new DOMParser().parseFromString(html, 'text/html');

              const newBlock = doc.querySelector('#thaali-month-block');
              const curBlock = document.querySelector('#thaali-month-block');
              if (newBlock && curBlock) curBlock.replaceWith(newBlock);

              const newTitle = doc.querySelector('#hijri-current-title');
              const curTitle = document.getElementById('hijri-current-title');
              if (newTitle && curTitle) curTitle.innerHTML = newTitle.innerHTML;

              const newBtns = doc.querySelectorAll('.hijri-nav-btn');
              const curBtns = document.querySelectorAll('.hijri-nav-btn');
              newBtns.forEach((b, i) => {
                if (!curBtns[i]) return;
                curBtns[i].dataset.hijriYear = b.dataset.hijriYear || '';
                curBtns[i].dataset.hijriMonth = b.dataset.hijriMonth || '';
                curBtns[i].classList.toggle(
                  'disabled',
                  b.classList.contains('disabled')
                );
              });

              if (pushState) {
                history.pushState(
                  { year, month },
                  '',
                  `?hijri_year=${year}&hijri_month=${month}`
                );
              }
            });
        }

        document.addEventListener('click', function (e) {
          const btn = e.target.closest('.hijri-nav-btn');
          if (!btn || btn.classList.contains('disabled')) return;
          e.preventDefault();
          loadMonth(btn.dataset.hijriYear, btn.dataset.hijriMonth, true);
        });

      })();
    </script>




  </div>
  <div class="continer d-flex justify-content-center">
    <div class="row container">
      <a href="<?php echo base_url('MasoolMusaid/mumineendirectory') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card dashboard-card text-center">
          <div class="card-body">
            <div class="title">Mumineen Directory</div>
            <i class="fa-solid icon fa-clipboard-user"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('MasoolMusaid/asharaohbat') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card dashboard-card text-center">
          <div class="card-body">
            <div class="title">Ashara Ohbat 1446</div>
            <i class="fa-solid icon fa-calendar-days"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('MasoolMusaid/ashara_attendance') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card dashboard-card text-center">
          <div class="card-body">
            <div class="title">Ashara Attendance</div>
            <i class="fa-solid icon fa-user-check"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('MasoolMusaid/rsvp_list') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card dashboard-card text-center">
          <div class="card-body">
            <div class="title">Miqaat RSVP</div>
            <i class="fa-solid icon fa-users"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('MasoolMusaid/miqaat_attendance') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card dashboard-card text-center">
          <div class="card-body">
            <div class="title">Miqaat Attendance</div>
            <i class="fa-solid icon fa-calendar-check"></i>
          </div>
        </div>
      </a>
    </div>
  </div>

</div>
<script>
  const colors = ["rgb(142, 68, 173)",
    "rgb(243, 156, 18)",
    "rgb(135, 0, 0)",
    "rgb(211, 84, 0)",
    "rgb(0, 106, 63)",
    "rgb(192, 57, 43)",
    "rgb(39, 174, 96)",
    "rgb(41, 128, 185)",
    "rgb(142, 68, 173)",
    "rgb(243, 156, 18)",
    "rgb(135, 0, 0)",
    "rgb(211, 84, 0)",
    "rgb(0, 106, 63)",
    "rgb(192, 57, 43)",
    "rgb(39, 174, 96)",
    "rgb(41, 128, 185)",
    "rgb(142, 68, 173)",
    "rgb(243, 156, 18)",
    "rgb(135, 0, 0)",
    "rgb(211, 84, 0)",
    "rgb(0, 106, 63)",
    "rgb(192, 57, 43)",
    "rgb(39, 174, 96)",
    "rgb(41, 128, 185)",
  ]
  $(document).ready(function () {
    $(".dashboard-card").each(function (i) {
      this.style.backgroundColor = colors[i % colors.length];
    });
  })
</script>
<script>
  // Disable browser back button
  history.pushState(null, null, location.href);
  window.onpopstate = function () {
    history.pushState(null, null, location.href);
  };
</script>