<div class="container margintopcontainer pt-5">
  <div class="mb-4 mb-md-0">
    <a href="<?php echo $active_controller ? $active_controller : base_url(); ?>" class="btn btn-outline-secondary inline-block text-blue-600 hover:underline">
      <i class="fas fa-arrow-left"></i>
    </a>
  </div>
  <?php
  // Prefer explicit counts passed from controller. Fallback to available arrays if present.
  $totalMembersText = '';
  if (isset($total_members)) {
    $totalMembersText = (int)$total_members;
  } elseif (isset($members_count)) {
    $totalMembersText = (int)$members_count;
  } elseif (isset($member_count)) {
    $totalMembersText = (int)$member_count;
  } elseif (isset($members) && is_array($members)) {
    $totalMembersText = count($members);
  } elseif (isset($all_members) && is_array($all_members)) {
    $totalMembersText = count($all_members);
  }
  ?>
  <h3 class="heading text-center mb-2">RSVP <span class="text-primary">Report</span></h3>

  <style>
    /* Modern compact filter row */
    .miqaat-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      align-items: center;
      justify-content: center;
      margin-bottom: 12px;
    }

    .miqaat-filters .filter-item {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .miqaat-filters .filter-input {
      border: 1px solid #e6e9ef;
      padding: 8px 10px;
      border-radius: 8px;
      box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
      background: #fff;
    }

    .miqaat-filters .filter-input[type="date"] {
      padding: 6px 8px;
    }

    .miqaat-filters .filter-btn {
      border-radius: 8px;
      padding: 7px 10px;
    }

    .miqaat-filters .preset-select {
      border-radius: 8px;
      padding: 6px 10px;
      border: 1px solid #e6e9ef;
      background: #fff;
    }

    @media (max-width:575.98px) {
      .miqaat-filters {
        justify-content: stretch;
      }

      .miqaat-filters .filter-item {
        flex: 1 1 100%;
      }

      /* Stack label above input and make inputs full width */
      .miqaat-filters .filter-item {
        flex-direction: column;
        align-items: flex-start;
      }

      .miqaat-filters .filter-item label {
        margin-bottom: 6px;
      }

      .miqaat-filters .filter-input,
      .miqaat-filters .preset-select {
        width: 100%;
        box-sizing: border-box;
      }

      /* Make action buttons full width (or side-by-side if space) */
      .miqaat-filters .filter-item:last-child {
        flex-direction: row;
        gap: 8px;
        justify-content: flex-end;
      }

      .miqaat-filters .filter-btn {
        width: auto;
      }

      /* Reduce vertical spacing slightly */
      .miqaat-filters {
        gap: 6px;
        margin-bottom: 10px;
      }
    }
  </style>

  <div class="miqaat-filters p-4 rounded-3 shadow-sm" role="group" aria-label="Miqaat filters">
    <div class="filter-item">
      <label for="filter-range" class="small text-muted d-block d-md-inline mb-1">Range</label>
      <select id="filter-range" class="preset-select" title="Quick ranges">
        <option value="">Range</option>
        <option value="7">Last 7 days</option>
        <option value="30">Last 30 days</option>
        <option value="90">Last 90 days</option>
        <option value="365">Last 1 year</option>
      </select>
    </div>
    <div class="filter-item">
      <label for="filter-from" class="small text-muted d-block d-md-inline mb-1">From</label>
      <input type="date" id="filter-from" class="filter-input" placeholder="From" />
    </div>
    <div class="filter-item">
      <label for="filter-to" class="small text-muted d-block d-md-inline mb-1">To</label>
      <input type="date" id="filter-to" class="filter-input" placeholder="To" />
    </div>
    <div class="filter-item" style="min-width:220px;">
      <label for="filter-name" class="small text-muted d-block d-md-inline mb-1">Search</label>
      <div style="position:relative;">
        <input type="text" id="filter-name" class="filter-input" placeholder="Search miqaat name" aria-label="Search by miqaat name" />
        <button id="filter-clear-name" type="button" title="Clear" style="position:absolute; right:6px; top:6px; border:none; background:transparent; color:#6b7280;">&times;</button>
      </div>
    </div>
    <div class="filter-item">
      <div style="display:flex; gap:6px; align-items:center;">
        <button id="filter-apply" class="btn btn-primary filter-btn">Apply</button>
        <button id="filter-reset" class="btn btn-outline-secondary filter-btn">Reset</button>
      </div>
    </div>
  </div>
  <div class="row" id="miqaat-cards">

    <style>
      #miqaat-cards {
        margin-left: 0 !important;
        margin-right: 0 !important;
      }

      /* Ensure miqaat cards are visually equal height and buttons align */
      #miqaat-cards .miqaat-card {
        display: flex;
        align-items: stretch;
        margin-bottom: 1rem;
        box-sizing: border-box;
        /* ensure each column aligns to the top of the row */
      }

      /* Let the inner .card stretch to fill the column and use column layout */
      #miqaat-cards .miqaat-card .card {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        height: 100%;
        min-height: 180px;
        box-sizing: border-box;
      }

      /* Make card body distribute space so the action sits at the bottom */
      #miqaat-cards .miqaat-card .card .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1 1 auto;
      }

      @media (max-width:575.98px) {
        #miqaat-cards .miqaat-card .card {
          min-height: 160px;
        }
      }
    </style>

    <div class="row" id="miqaat-cards">
      <?php if (!empty($miqaats)) : ?>
        <?php
        // Ensure miqaats are ordered ascending by date (earliest first)
        usort($miqaats, function ($a, $b) {
          $ta = (int)strtotime($a['miqaat_date'] ?? '0');
          $tb = (int)strtotime($b['miqaat_date'] ?? '0');
          return $ta <=> $tb;
        });
        ?>
        <?php foreach ($miqaats as $miqaat) : ?>
          <?php $cardDate = isset($miqaat['miqaat_date']) ? $miqaat['miqaat_date'] : '';
          $cardName = isset($miqaat['miqaat_name']) ? strtolower($miqaat['miqaat_name']) : ''; ?>
          <div class="col-md-4 col-sm-6 mb-4 miqaat-card" data-date="<?php echo htmlspecialchars($cardDate); ?>" data-name="<?php echo htmlspecialchars($cardName); ?>">
            <div class="card shadow-sm">
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($miqaat['miqaat_name']); ?></h5>
                <p class="card-text mb-2">
                  <strong>Date:</strong> <?php echo date('d M Y', strtotime($miqaat['miqaat_date'])); ?>
                </p>
                <p class="card-text mb-2">
                  <strong>Hijri Date:</strong>
                  <?php
                  if (!empty($miqaat['hijri_date']) && !empty($miqaat['hijri_month_name'])) {
                    $hijri_parts = explode('-', $miqaat['hijri_date']);
                    echo $hijri_parts[0] . ' ' . $miqaat['hijri_month_name'] . ' ' . (isset($hijri_parts[2]) ? $hijri_parts[2] : '');
                  } else {
                    echo '-';
                  }
                  ?>
                </p>
                <p class="card-text mb-2">
                  <strong>Total members:</strong>
                  <?php if ($totalMembersText !== ''): ?>
                    <span class="badge badge-secondary"><?php echo $totalMembersText; ?></span>
                  <?php else: ?>
                    <span class="badge badge-light">-</span>
                  <?php endif; ?>
                </p>
                <p class="card-text mb-0">
                  <strong>RSVPs:</strong> <?php echo (isset($rsvp_by_miqaat[$miqaat['miqaat_id']]) && $rsvp_by_miqaat[$miqaat['miqaat_id']] > 0) ? "<span class='badge badge-success'>" . $rsvp_by_miqaat[$miqaat['miqaat_id']] . "</span>" : "<span class='badge badge-danger'>0</span>"; ?>
                </p>
                <div class="mt-3">
                  <a href="<?php echo base_url('common/rsvp_details/' . $miqaat['miqaat_id']); ?>" class="btn btn-primary btn-sm">View Details</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <div class="col-12">
          <div class="alert alert-info">No miqaats found.</div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    (function() {
      function parseDate(s) {
        if (!s) return null;
        var t = new Date(s);
        return isNaN(t.getTime()) ? null : t;
      }

      function applyFilters() {
        var from = parseDate(document.getElementById('filter-from').value);
        var to = parseDate(document.getElementById('filter-to').value);
        var name = (document.getElementById('filter-name').value || '').toLowerCase().trim();
        document.querySelectorAll('.miqaat-card').forEach(function(card) {
          var cdate = parseDate(card.getAttribute('data-date'));
          var cname = (card.getAttribute('data-name') || '').toLowerCase();
          var show = true;
          if (from && cdate && cdate < from) show = false;
          if (to && cdate && cdate > to) show = false;
          if (name && cname.indexOf(name) === -1) show = false;
          card.style.display = show ? '' : 'none';
        });
      }
      document.getElementById('filter-apply').addEventListener('click', function(e) {
        e.preventDefault();
        applyFilters();
      });
      document.getElementById('filter-reset').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('filter-from').value = '';
        document.getElementById('filter-to').value = '';
        document.getElementById('filter-name').value = '';
        document.getElementById('filter-range').value = '';
        applyFilters();
      });
      // quick preset range handler
      var rangeEl = document.getElementById('filter-range');
      if (rangeEl) rangeEl.addEventListener('change', function() {
        var v = parseInt(this.value || 0, 10);
        if (!v) return;
        var to = new Date();
        var from = new Date();
        from.setDate(to.getDate() - v + 1);

        function toISO(d) {
          var y = d.getFullYear(),
            m = ('0' + (d.getMonth() + 1)).slice(-2),
            day = ('0' + d.getDate()).slice(-2);
          return y + '-' + m + '-' + day;
        }
        document.getElementById('filter-from').value = toISO(from);
        document.getElementById('filter-to').value = toISO(to);
        applyFilters();
      });
      var clearName = document.getElementById('filter-clear-name');
      if (clearName) clearName.addEventListener('click', function() {
        document.getElementById('filter-name').value = '';
        document.getElementById('filter-name').focus();
      });
      // Ensure DOM cards are ordered by date descending for visual consistency
      function sortMiqaatCards() {
        var container = document.getElementById('miqaat-cards');
        if (!container) return;
        var cards = Array.prototype.slice.call(container.querySelectorAll('.miqaat-card'));
        cards.sort(function(a, b) {
          var da = a.getAttribute('data-date') || '';
          var db = b.getAttribute('data-date') || '';
          var ta = da ? new Date(da).getTime() : 0;
          var tb = db ? new Date(db).getTime() : 0;
          return ta - tb; // ascending
        });
        cards.forEach(function(c) {
          container.appendChild(c);
        });
      }
      // Run once on load to guarantee ordering
      sortMiqaatCards();
    })();
  </script>