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

  <?php
  $miq_rsvp = isset($dashboard_data['miqaat_rsvp']) ? $dashboard_data['miqaat_rsvp'] : null;
  $upcoming = isset($dashboard_data['upcoming_miqaats']) ? $dashboard_data['upcoming_miqaats'] : [];
  $initial_index = 0;
  $initial_id = isset($miq_rsvp['next_miqaat']['id']) ? $miq_rsvp['next_miqaat']['id'] : (isset($upcoming[0]['id']) ? $upcoming[0]['id'] : '');
  foreach ($upcoming as $k => $m) {
    if (isset($m['id']) && $m['id'] == $initial_id) {
      $initial_index = $k;
      break;
    }
  }
  $rsvp_count = isset($miq_rsvp['rsvp_count']) ? (int) $miq_rsvp['rsvp_count'] : 0;
  $not_count = isset($miq_rsvp['not_rsvp_count']) ? (int) $miq_rsvp['not_rsvp_count'] : 0;
  ?>

  <div class="chart-container compact" id="miqaat-rsvp-block" data-initial-index="<?= $initial_index; ?>">
    <div class="d-flex align-items-center justify-content-between" style="gap:12px;">
      <h4 class="section-title text-center m-0" style="flex:1;">RSVP for Next Miqaat</h4>
    </div>
    <div style="min-width:140px;text-align:right;">
      <!-- View details button (computes dates after hijri vars are set below) -->
      <a href="#" class="btn btn-sm btn-primary text-white my-2" id="miqaat-view-details">View details</a>
    </div>


    <div class="d-flex align-items-center justify-content-center mt-3 mb-3">
      <a href="#" class="miqaat-nav-btn prev" aria-label="Previous miqaat"
        style="display:inline-flex;align-items:center;justify-content:center;height:40px;width:40px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,0.04);color:#374151;text-decoration:none;margin-right:12px;">
        <span style="font-size:18px;line-height:1">&#x2039;</span>
      </a>
      <span id="miqaat-current-title" class="mx-3" style="font-weight:600;color:#0ea5a5;font-size:20px;"></span>
      <a href="#" class="miqaat-nav-btn next" aria-label="Next miqaat"
        style="display:inline-flex;align-items:center;justify-content:center;height:40px;width:40px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,0.04);color:#374151;text-decoration:none;margin-left:12px;">
        <span style="font-size:18px;line-height:1">&#x203A;</span>
      </a>
    </div>

    <div id="miqaat-mobile-wrapper">
      <div id="miqaat-mobile-left">
        <div class="row text-center mb-2">
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatWillAttendCard" class="open-miqaat-modal" data-type="rsvp" data-miqaat-id=""
              style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="small text-muted" style="visibility:hidden;">&nbsp;</div>
                <div class="stats-value" id="willAttendCount"><?php
                $combined_total = isset($miq_rsvp['combined_summary']['total']) ? (int) $miq_rsvp['combined_summary']['total'] : (isset($miq_rsvp['rsvp_users_count']) ? (int) $miq_rsvp['rsvp_users_count'] : 0);
                echo $combined_total;
                ?></div>
                <div class="small text-muted" id="willAttendGuest">
                  <?= isset($miq_rsvp['guest_summary']['total']) && (int) $miq_rsvp['guest_summary']['total'] > 0 ? ('+' . (int) $miq_rsvp['guest_summary']['total'] . ' guests') : ''; ?>
                </div>
                <div class="stats-label">Will attend</div>
              </div>
            </a>
          </div>
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatWillNotAttendCard" class="open-miqaat-modal" data-type="no" data-miqaat-id=""
              style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="small text-muted" style="visibility:hidden;">&nbsp;</div>
                <div class="stats-value" id="willNotAttendCount">
                  <?= isset($miq_rsvp['will_not_attend']) ? (int) $miq_rsvp['will_not_attend'] : 0; ?>
                </div>
                <div class="stats-label">Will not attend</div>
              </div>
            </a>
          </div>
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatNotSubmittedCard" class="open-miqaat-modal" data-type="not_submitted"
              data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="small text-muted" style="visibility:hidden;">&nbsp;</div>
                <div class="stats-value" id="rsvpNotSubmittedCount">
                  <?= isset($miq_rsvp['rsvp_not_submitted']) ? (int) $miq_rsvp['rsvp_not_submitted'] : 0; ?>
                </div>
                <div class="stats-label">RSVP not submitted</div>
              </div>
            </a>
          </div>
        </div>
      </div>
      <div id="miqaat-mobile-right">
        <!-- Guest breakdown: Gents / Ladies / Children / Guests -->
        <div class="row text-center mb-3" id="miqaatGuestBreakdown">
          <div class="col-12 col-md-4 mb-2 mb-md-0">
            <a href="#" id="miqaatGuestGentsCard" class="open-miqaat-modal" data-type="gents" data-miqaat-id=""
              style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card bg-white">
                <div class="small text-muted">Gents</div>
                <div class="stats-value" id="guestGentsCount">
                  <?= isset($miq_rsvp['combined_summary']['gents']) ? (int) $miq_rsvp['combined_summary']['gents'] : (isset($miq_rsvp['guest_summary']['gents']) ? (int) $miq_rsvp['guest_summary']['gents'] : 0); ?>
                </div>
                <div class="small text-muted" id="guestGentsBreakdown">
                  Members:
                  <?= isset($miq_rsvp['member_summary']['gents']) ? (int) $miq_rsvp['member_summary']['gents'] : 0; ?> |
                  Guests:
                  <?= isset($miq_rsvp['guest_summary']['gents']) ? (int) $miq_rsvp['guest_summary']['gents'] : 0; ?>
                </div>
              </div>
            </a>
          </div>
          <div class="col-12 col-md-4 mb-2 mb-md-0">
            <a href="#" id="miqaatGuestLadiesCard" class="open-miqaat-modal" data-type="ladies" data-miqaat-id=""
              style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card bg-white">
                <div class="small text-muted">Ladies</div>
                <div class="stats-value" id="guestLadiesCount">
                  <?= isset($miq_rsvp['combined_summary']['ladies']) ? (int) $miq_rsvp['combined_summary']['ladies'] : (isset($miq_rsvp['guest_summary']['ladies']) ? (int) $miq_rsvp['guest_summary']['ladies'] : 0); ?>
                </div>
                <div class="small text-muted" id="guestLadiesBreakdown">
                  Members:
                  <?= isset($miq_rsvp['member_summary']['ladies']) ? (int) $miq_rsvp['member_summary']['ladies'] : 0; ?>
                  | Guests:
                  <?= isset($miq_rsvp['guest_summary']['ladies']) ? (int) $miq_rsvp['guest_summary']['ladies'] : 0; ?>
                </div>
              </div>
            </a>
          </div>
          <div class="col-12 col-md-4 mb-2 mb-md-0">
            <a href="#" id="miqaatGuestChildrenCard" class="open-miqaat-modal" data-type="children" data-miqaat-id=""
              style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card bg-white">
                <div class="small text-muted">Children</div>
                <div class="stats-value" id="guestChildrenCount">
                  <?= isset($miq_rsvp['combined_summary']['children']) ? (int) $miq_rsvp['combined_summary']['children'] : (isset($miq_rsvp['guest_summary']['children']) ? (int) $miq_rsvp['guest_summary']['children'] : 0); ?>
                </div>
                <div class="small text-muted" id="guestChildrenBreakdown">
                  Members:
                  <?= isset($miq_rsvp['member_summary']['children']) ? (int) $miq_rsvp['member_summary']['children'] : 0; ?>
                  | Guests:
                  <?= isset($miq_rsvp['guest_summary']['children']) ? (int) $miq_rsvp['guest_summary']['children'] : 0; ?>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
    <style>
      @media (max-width:767.98px) {

        /* Left/Right split for miqaat cards */
        #miqaat-mobile-wrapper {
          display: flex;
          gap: 8px;
          align-items: flex-start;
        }

        #miqaat-mobile-left,
        #miqaat-mobile-right {
          width: 50%;
        }

        /* Make internal rows stack cleanly and ensure their children stretch */
        #miqaat-mobile-left .row,
        #miqaat-mobile-right .row {
          margin-bottom: 0;
          display: flex;
          flex-direction: column;
          gap: 8px;
        }

        /* Ensure each column cell becomes a flex container so cards can stretch */
        #miqaat-mobile-left .row>[class*="col-"],
        #miqaat-mobile-right .row>[class*="col-"] {
          display: flex;
          align-items: stretch;
        }

        /* Make the anchor fill the column so .mini-card can stretch uniformly */
        #miqaat-mobile-left .row>[class*="col-"]>a,
        #miqaat-mobile-right .row>[class*="col-"]>a {
          display: flex;
          flex: 1;
          align-items: stretch;
          width: 100%;
        }

        /* Make the mini-card fill available height and distribute content */
        #miqaat-mobile-wrapper .mini-card {
          display: flex;
          flex-direction: column;
          justify-content: center;
          /* center content vertically for uniform look */
          align-items: center;
          /* center text horizontally */
          text-align: center;
          flex: 1 1 auto;
          height: 150px;
          padding: 18px 16px;
          box-sizing: border-box;
          overflow: visible;
        }

        /* Slight spacing adjustment for value text; prevent clipping */
        #miqaat-mobile-wrapper .mini-card .stats-value {
          margin-bottom: 8px;
          font-size: 1.6rem;
          font-weight: 700;
          line-height: 1.1;
          display: block;
          white-space: nowrap;
          overflow: visible !important;
          text-overflow: clip;
          max-width: none;
          padding: 0 6px;
        }

        /* Allow labels to wrap to next line instead of being truncated */
        #miqaat-mobile-wrapper .mini-card .stats-label {
          white-space: normal;
          overflow: visible;
          text-overflow: clip;
          overflow-wrap: break-word;
          word-break: break-word;
          font-size: 0.85rem;
          color: #6b7280;
          text-transform: uppercase;
          letter-spacing: 0.8px;
          margin-top: 8px;
        }

        /* Support for demographic small text lines */
        #miqaat-mobile-wrapper .mini-card .small.text-muted {
          display: block;
          color: #6b7280;
          font-size: 0.85rem;
          margin-top: 6px;
        }
      }
    </style>

    <!-- inline message for miqaat actions (hidden by default) -->
    <div id="miqaatMessage" class="miqaat-message"
      style="display:none; margin-top:10px; text-align:center; color:#333; font-size:0.95rem;">&nbsp;</div>

    <!-- loading overlay (hidden by default) -->
    <div id="miqaatLoading" class="miqaat-loading-overlay" style="display:none;">
      <div class="miqaat-spinner" aria-hidden="true"></div>
    </div>

    <script>
      (function () {
        var upcoming = <?= json_encode(array_values($upcoming)); ?> || [];
        var container = document.getElementById('miqaat-rsvp-block');
        var index = parseInt(container ? container.getAttribute('data-initial-index') || '0' : '0', 10) || 0;
        var titleEl = document.getElementById('miqaat-current-title');
        var rsvpCountEl = document.getElementById('miqaatRsvpCount');
        var notCountEl = document.getElementById('miqaatNotRsvpCount');
        var rsvpCard = document.getElementById('miqaatRsvpCard');
        var notCard = document.getElementById('miqaatNotRsvpCard');
        var viewDetails = document.getElementById('miqaat-view-details');

        function renderFor(i) {
          if (!upcoming || !upcoming.length) {
            if (titleEl) titleEl.textContent = 'No upcoming miqaat';
            return;
          }
          if (i < 0) i = 0;
          if (i >= upcoming.length) i = upcoming.length - 1;
          index = i;
          var mi = upcoming[index];
          var miqId = mi.id || mi.miqaat_id || '';
          var miqName = mi.name || ('Miqaat ' + miqId);
          // Prefer hijri_label if provided by controller; otherwise show greg date
          var dateLabel = mi.hijri_label || mi.date || '';
          if (titleEl) {
            // safely escape text to avoid injecting HTML
            function _escapeHtml(s) {
              return String(s === null || s === undefined ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }
            var nameHtml = '<div class="miqaat-name">' + _escapeHtml(miqName) + '</div>';
            var dateHtml = dateLabel ? ('<div class="miqaat-date">' + _escapeHtml(dateLabel) + '</div>') : '';
            titleEl.innerHTML = nameHtml + dateHtml;
          }
          if (rsvpCard) rsvpCard.setAttribute('data-miqaat-id', miqId);
          if (notCard) notCard.setAttribute('data-miqaat-id', miqId);
          if (viewDetails) viewDetails.href = '<?= base_url("common/rsvp_list?from=amilsaheb"); ?>' + '?miqaat_id=' + encodeURIComponent(miqId);

          var url = window.location.pathname;
          try {
            var u = new URL(url, window.location.origin);
            u.searchParams.set('format', 'json');
            u.searchParams.set('miqaat_rsvp', '1');
            u.searchParams.set('miqaat_id', miqId);
            url = u.toString();
          } catch (err) {
            url += '?format=json&miqaat_rsvp=1&miqaat_id=' + encodeURIComponent(miqId);
          }
          fetch(url, {
            credentials: 'same-origin'
          }).then(function (resp) {
            return resp.json();
          }).then(function (data) {
            if (!data || !data.miqaat_rsvp) return;
            var m = data.miqaat_rsvp || {};
            try { if (typeof updateGuestCountsFromPayload === 'function') updateGuestCountsFromPayload(m); } catch (e) { }
            // legacy counts (HOF-level) kept for compatibility
            if (typeof window !== 'undefined') {
              try {
                var waEl = document.getElementById('willAttendCount');
                var wnaEl = document.getElementById('willNotAttendCount');
                var nsEl = document.getElementById('rsvpNotSubmittedCount');
                // Initialize with zeros while user-level counts are fetched
                if (waEl) waEl.textContent = '0';
                if (wnaEl) wnaEl.textContent = '0';
                if (nsEl) nsEl.textContent = '0';
              } catch (e) { }
            }
            // Now fetch per-user classification counts from common endpoint
            try {
              var countsUrl = '<?= base_url('common/miqaat_rsvp_user_counts'); ?>?miqaat_id=' + encodeURIComponent(miqId);
              fetch(countsUrl, { credentials: 'same-origin' }).then(function (r) { return r.json(); }).then(function (cdata) {
                if (cdata && cdata.success) {
                  var wa = cdata.will_attend || 0;
                  var wna = cdata.will_not_attend || 0;
                  var ns = cdata.rsvp_not_submitted || 0;
                  var waEl2 = document.getElementById('willAttendCount');
                  var wnaEl2 = document.getElementById('willNotAttendCount');
                  var nsEl2 = document.getElementById('rsvpNotSubmittedCount');
                  // Prefer payload member/guest breakdown if available
                  var memberTotal = (m && m.member_summary && m.member_summary.total) ? m.member_summary.total : 0;
                  var guestTotal = (m && m.guest_summary && m.guest_summary.total) ? m.guest_summary.total : 0;
                  var combined = (memberTotal + guestTotal) || wa;
                  if (waEl2) waEl2.textContent = combined;
                  // show guest subtext
                  try {
                    var waGuestEl = document.getElementById('willAttendGuest');
                    if (waGuestEl) waGuestEl.textContent = (guestTotal > 0 ? ('+' + guestTotal + ' guests') : '');
                  } catch (e) { }
                  if (wnaEl2) wnaEl2.textContent = wna;
                  if (nsEl2) nsEl2.textContent = ns;
                }
              }).catch(function (err) {
                console.warn('Failed to fetch per-user RSVP counts', err);
              });
            } catch (e) {
              console.warn('Counts fetch failed', e);
            }
          }).catch(function (err) {
            console.error('Failed to fetch miqaat rsvp data', err);
          });
        }

        document.addEventListener('click', function (e) {
          var t = e.target.closest && e.target.closest('.miqaat-nav-btn');
          if (!t) return;
          e.preventDefault();
          if (t.classList.contains('prev')) {
            if (index > 0) {
              renderFor(index - 1);
              return;
            }
            // we're at the first item; try to fetch the previous miqaat from server
            var first = (upcoming && upcoming.length) ? upcoming[0] : null;
            var beforeDate = first ? (first.date || '') : '';
            if (!beforeDate) return; // nothing to do
            var url = window.location.pathname;
            try {
              var u = new URL(url, window.location.origin);
              u.searchParams.set('format', 'json');
              u.searchParams.set('miqaat_prev', '1');
              u.searchParams.set('before_date', beforeDate);
              url = u.toString();
            } catch (err) {
              url += '?format=json&miqaat_prev=1&before_date=' + encodeURIComponent(beforeDate);
            }
            // show loading overlay and disable prev button while loading
            var loadingEl = document.getElementById('miqaatLoading');
            var msgEl = document.getElementById('miqaatMessage');
            if (loadingEl) loadingEl.style.display = 'flex';
            t.style.pointerEvents = 'none';
            fetch(url, {
              credentials: 'same-origin'
            }).then(function (resp) {
              return resp.json();
            }).then(function (data) {
              if (loadingEl) loadingEl.style.display = 'none';
              t.style.pointerEvents = '';
              if (!data || !data.success || !data.miqaat) {
                // no previous miqaat found — show a short message to the user
                if (msgEl) {
                  msgEl.textContent = 'No earlier miqaat found';
                  msgEl.style.display = 'block';
                  clearTimeout(msgEl._t);
                  msgEl._t = setTimeout(function () { msgEl.style.display = 'none'; }, 3000);
                }
                return;
              }
              // prepend to upcoming list and render it
              upcoming.unshift(data.miqaat);
              // stay at the newly-prepended item (index 0)
              renderFor(0);
            }).catch(function (err) {
              if (loadingEl) loadingEl.style.display = 'none';
              t.style.pointerEvents = '';
              console.error('Failed to fetch previous miqaat', err);
              if (msgEl) {
                msgEl.textContent = 'Failed to fetch earlier miqaat';
                msgEl.style.display = 'block';
                clearTimeout(msgEl._t);
                msgEl._t = setTimeout(function () { msgEl.style.display = 'none'; }, 3000);
              }
            });
          } else {
            renderFor(index + 1);
          }
        });

        // initial render
        renderFor(index);

        // Click handler for opening miqaat RSVP modal
        document.addEventListener('click', function (e) {
          var a = e.target.closest && e.target.closest('.open-miqaat-modal');
          if (!a) return;
          e.preventDefault();
          var dtype = a.getAttribute('data-type') || 'rsvp';
          var mid = a.getAttribute('data-miqaat-id') || (upcoming && upcoming[index] && (upcoming[index].id || upcoming[index].miqaat_id)) || '';
          if (!mid) return;

          // local helpers
          function _escapeHtml(s) {
            return String(s === null || s === undefined ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
          }
          function _normalizePhone(raw) {
            var DEFAULT_CC = '91';
            if (!raw) return '';
            var s = String(raw).trim();
            s = s.replace(/[\s\-\.\(\)]/g, '');
            if (s.charAt(0) === '+') {
              var d = s.slice(1).replace(/\D/g, '');
              return '+' + d;
            }
            if (s.indexOf('00') === 0) {
              var d2 = s.slice(2).replace(/\D/g, '');
              return '+' + d2;
            }
            var digits = s.replace(/\D/g, '');
            if (!digits) return '';
            if (digits.length === 10) return '+' + DEFAULT_CC + digits;
            if (digits.length === 11 && digits.charAt(0) === '0') return '+' + DEFAULT_CC + digits.slice(1);
            if (digits.length >= 11 && digits.length <= 15) return '+' + digits;
            return digits;
          }

          function renderHofListLocal(title, rows) {
            var html = '';
            html += '<div class="d-flex justify-content-between align-items-center mb-2">';
            html += '<strong>' + _escapeHtml(title) + '</strong>';
            // Filter out rows that have both Sector and Sub_Sector empty before counting
            try {
              rows = (rows || []).filter(function (r) {
                var sector = (r && (r.Sector || r.sector) ? (r.Sector || r.sector) : '') + '';
                var sub = (r && (r.Sub_Sector || r.sub_sector || r.SubSector) ? (r.Sub_Sector || r.sub_sector || r.SubSector) : '') + '';
                return !((sector || '').trim() === '' && (sub || '').trim() === '');
              });
            } catch (e) { console.warn('HOF filter failed', e); }
            html += '<span class="text-muted">Count: ' + (rows ? rows.length : 0) + '</span>';
            html += '</div>';
            if (!rows || rows.length === 0) {
              html += '<div class="text-muted">No records found.</div>';
              return html;
            }
            try {
              rows.sort(function (a, b) {
                var sa = (a.Sector || a.sector || '') + '';
                var sb = (b.Sector || b.sector || '') + '';
                sa = sa.toLowerCase(); sb = sb.toLowerCase(); if (sa < sb) return -1; if (sa > sb) return 1;
                var ssa = (a.Sub_Sector || a.sub_sector || a.SubSector || '') + '';
                var ssb = (b.Sub_Sector || b.sub_sector || b.SubSector || '') + '';
                ssa = ssa.toLowerCase(); ssb = ssb.toLowerCase(); if (ssa < ssb) return -1; if (ssa > ssb) return 1;
                var na = (a.Full_Name || a.full_name || a.name || '') + '';
                var nb = (b.Full_Name || b.full_name || b.name || '') + '';
                na = na.toLowerCase(); nb = nb.toLowerCase(); if (na < nb) return -1; if (na > nb) return 1;
                return 0;
              });
            } catch (e) { console.warn('sort error', e); }
            // Choose first-column header based on whether this is a member list
            var firstCol = (title && String(title).toLowerCase().indexOf('member') !== -1) ? 'Member ID' : 'HOF ID';
            html += '<table class="table table-sm table-striped"><thead><tr><th>' + _escapeHtml(firstCol) + '</th><th>Name</th><th>Sector</th><th>Sub Sector</th></tr></thead><tbody>';
            rows.forEach(function (r) {
              var id = (r && (r.ITS_ID || r.hof_id || r.ITS)) ? (r.ITS_ID || r.hof_id || r.ITS) : '';
              var name = (r && (r.Full_Name || r.name)) ? (r.Full_Name || r.name) : '';
              var sector = (r && (r.Sector || r.sector)) ? (r.Sector || r.sector) : '';
              var subSector = (r && (r.Sub_Sector || r.sub_sector || r.SubSector)) ? (r.Sub_Sector || r.sub_sector || r.SubSector) : '';
              // Mobile column removed per UX change
              html += '<tr><td>' + _escapeHtml(id) + '</td><td>' + _escapeHtml(name) + '</td><td>' + _escapeHtml(sector) + '</td><td>' + _escapeHtml(subSector) + '</td></tr>';
            });
            html += '</tbody></table>';
            return html;
          }

          // show modal and loading; populate meta block with basic miqaat info
          try {
            var lbl = "Will not attend for Miqaat";
            if (dtype === 'rsvp') lbl = "RSVP'd for Miqaat";
            else if (dtype === 'not_submitted') lbl = "RSVP not submitted for Miqaat";
            try { $('#hofListLabel').text(lbl); } catch (e) { }
          } catch (e) { }
          var loading = document.getElementById('hofListLoading');
          var containerEl = document.getElementById('hofListContainer');
          var metaEl = document.getElementById('miqaatPopupMeta');
          if (loading) loading.style.display = 'block';
          if (containerEl) containerEl.innerHTML = '';
          // determine a local miqaat object if available
          try {
            var miObj = (upcoming || []).find(function (x) { return String(x.id || x.miqaat_id || '') === String(mid); }) || (upcoming[index] || {});
            var miName = miObj && (miObj.name || miObj.miqaat_name) ? (miObj.name || miObj.miqaat_name) : '';
            var miDateLabel = miObj && (miObj.hijri_label || miObj.date) ? (miObj.hijri_label || miObj.date) : '';
            if (metaEl) {
              metaEl.innerHTML = '<div style="font-weight:600;">' + _escapeHtml(miName || 'Miqaat') + '</div>' +
                (miDateLabel ? ('<div class="text-muted">' + _escapeHtml(miDateLabel) + '</div>') : '') +
                '<div style="margin-top:8px;">' +
                '<span class="badge badge-success" id="popupWillAttend" style="margin-right:8px;">Will attend: 0</span>' +
                '<span class="badge badge-danger" id="popupWillNotAttend" style="margin-right:8px;">Will not attend: 0</span>' +
                '<span class="badge badge-secondary" id="popupNotSubmitted">Not submitted: 0</span>' +
                '</div>';
              try { $('#miqaatPopupMeta').show(); } catch (e) { }
            }
          } catch (e) { console.warn('Failed to prepare miqaat meta', e); }
          try { $('#hofListModal').modal('show'); } catch (e) { /* ignore if bootstrap unavailable */ }

          var url = window.location.pathname;
          try {
            var u = new URL(url, window.location.origin);
            u.searchParams.set('format', 'json');
            u.searchParams.set('miqaat_rsvp', '1');
            u.searchParams.set('miqaat_id', mid);
            url = u.toString();
          } catch (err) {
            url += '?format=json&miqaat_rsvp=1&miqaat_id=' + encodeURIComponent(mid);
          }
          fetch(url, { credentials: 'same-origin' }).then(function (resp) { return resp.json(); }).then(function (data) {
            if (loading) loading.style.display = 'none';
            if (!data || !data.miqaat_rsvp) {
              if (containerEl) containerEl.innerHTML = '<div class="text-muted">No data found.</div>';
              return;
            }
            var m = data.miqaat_rsvp;
            try { if (typeof updateGuestCountsFromPayload === 'function') updateGuestCountsFromPayload(m); } catch (e) { }
            // Prefer member-level lists when available (show individual users)
            var rows = [];
            var titleTxt = '';
            if (dtype === 'rsvp') {
              rows = (m.rsvp_member_list && m.rsvp_member_list.length) ? m.rsvp_member_list : (m.rsvp_list || []);
              titleTxt = "RSVP'd Members";
            } else if (dtype === 'no') {
              rows = (m.not_rsvp_member_list && m.not_rsvp_member_list.length) ? m.not_rsvp_member_list : (m.not_rsvp_list || []);
              titleTxt = "Members Will not attend";
            } else if (dtype === 'not_submitted') {
              rows = (m.not_submitted_member_list && m.not_submitted_member_list.length) ? m.not_submitted_member_list : [];
              titleTxt = "Members Not Submitted";
            } else if (dtype === 'gents') {
              rows = (m.rsvp_male_member_list && m.rsvp_male_member_list.length) ? m.rsvp_male_member_list : (m.rsvp_member_list || []);
              titleTxt = "Gents";
            } else if (dtype === 'ladies') {
              rows = (m.rsvp_female_member_list && m.rsvp_female_member_list.length) ? m.rsvp_female_member_list : (m.rsvp_member_list || []);
              titleTxt = "Ladies";
            } else if (dtype === 'children') {
              rows = (m.rsvp_children_member_list && m.rsvp_children_member_list.length) ? m.rsvp_children_member_list : [];
              titleTxt = "Children";
            } else {
              // fallback to HOF-level not-rsvp list
              rows = (m.not_rsvp_member_list && m.not_rsvp_member_list.length) ? m.not_rsvp_member_list : (m.not_rsvp_list || []);
              titleTxt = "Members";
            }
            if (containerEl) containerEl.innerHTML = renderHofListLocal(titleTxt, rows);

            // fetch per-user classification counts and update popup badges
            try {
              var countsUrl = '<?= base_url('common/miqaat_rsvp_user_counts'); ?>?miqaat_id=' + encodeURIComponent(mid);
              fetch(countsUrl, { credentials: 'same-origin' }).then(function (r) { return r.json(); }).then(function (cdata) {
                if (cdata && cdata.success) {
                  var pw = document.getElementById('popupWillAttend');
                  var pwn = document.getElementById('popupWillNotAttend');
                  var pns = document.getElementById('popupNotSubmitted');
                  if (pw) pw.textContent = 'Will attend: ' + (cdata.will_attend || 0);
                  if (pwn) pwn.textContent = 'Will not attend: ' + (cdata.will_not_attend || 0);
                  if (pns) pns.textContent = 'Not submitted: ' + (cdata.rsvp_not_submitted || 0);
                }
              }).catch(function (err) { console.warn('Failed to fetch popup counts', err); });
            } catch (e) { console.warn('Counts fetch failed', e); }

          }).catch(function (err) {
            if (loading) loading.style.display = 'none';
            if (containerEl) containerEl.innerHTML = '<div class="text-danger">Failed to load list.</div>';
            console.error('miqaat rsvp fetch failed', err);
          });
        });

        // Update guest breakdown counts when miqaat JSON is loaded elsewhere (renderFor initial fetch)
        function updateGuestCountsFromPayload(m) {
          try {
            var gs = (m && m.guest_summary) ? m.guest_summary : { gents: 0, ladies: 0, children: 0, total: 0 };
            var ms = (m && m.member_summary) ? m.member_summary : { gents: 0, ladies: 0, children: 0, total: 0 };
            var cs = (m && m.combined_summary) ? m.combined_summary : {
              gents: (ms.gents || 0) + (gs.gents || 0),
              ladies: (ms.ladies || 0) + (gs.ladies || 0),
              children: (ms.children || 0) + (gs.children || 0),
              total: (ms.total || 0) + (gs.total || 0)
            };
            var gentsEl = document.getElementById('guestGentsCount');
            var ladiesEl = document.getElementById('guestLadiesCount');
            var childrenEl = document.getElementById('guestChildrenCount');
            var totalEl = document.getElementById('guestTotalCount');
            var gentsBreak = document.getElementById('guestGentsBreakdown');
            var ladiesBreak = document.getElementById('guestLadiesBreakdown');
            var childrenBreak = document.getElementById('guestChildrenBreakdown');
            var totalBreak = document.getElementById('guestTotalBreakdown');
            if (gentsEl) gentsEl.textContent = (cs.gents || 0);
            if (ladiesEl) ladiesEl.textContent = (cs.ladies || 0);
            if (childrenEl) childrenEl.textContent = (cs.children || 0);
            if (totalEl) totalEl.textContent = (cs.total || 0);
            if (gentsBreak) gentsBreak.textContent = 'Members: ' + (ms.gents || 0) + ' | Guests: ' + (gs.gents || 0);
            if (ladiesBreak) ladiesBreak.textContent = 'Members: ' + (ms.ladies || 0) + ' | Guests: ' + (gs.ladies || 0);
            if (childrenBreak) childrenBreak.textContent = 'Members: ' + (ms.children || 0) + ' | Guests: ' + (gs.children || 0);
            if (totalBreak) totalBreak.textContent = 'Members: ' + (ms.total || 0) + ' | Guests: ' + (gs.total || 0);
            // also update willAttend guest short text
            try {
              var waGuestEl = document.getElementById('willAttendGuest');
              if (waGuestEl) waGuestEl.textContent = (gs.total > 0 ? ('+' + (gs.total || 0) + ' guests') : '');
            } catch (e) { }
          } catch (e) { console.warn('Failed to update guest counts', e); }
        }
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