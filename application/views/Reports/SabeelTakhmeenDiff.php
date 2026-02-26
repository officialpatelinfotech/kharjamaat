<div class="container margintopcontainer pt-5">
  <div class="row mb-3">
    <div class="col-12">
      <h3 class="mb-1">Sabeel Takhmeen: Member YoY (Previous vs Current Year)</h3>
      <div class="text-muted small">Shows each member’s Total yearly takhmeen change (Establishment + Residential) from previous year to current year with %.</div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-4">
      <label class="small text-muted">Current Year</label>
      <select id="yearFilter" class="form-control">
        <option value="">Loading…</option>
      </select>
    </div>
    <div class="col-md-8 d-flex align-items-end">
      <button id="refreshBtn" class="btn btn-primary">Refresh</button>
      <div id="status" class="ml-3 text-muted small"></div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered w-100" style="width:100%;">
          <thead>
            <tr>
              <th style="min-width: 70px;">Sr. No.</th>
              <th style="min-width: 120px;">ITS</th>
              <th style="min-width: 240px;">Member</th>
              <th style="min-width: 90px;">Sector</th>
              <th style="min-width: 110px;">Sub Sector</th>
              <th class="text-right" style="min-width: 150px;">Total Prev</th>
              <th class="text-right" style="min-width: 150px;">Total Curr</th>
              <th class="text-right" style="min-width: 150px;">Total Diff</th>
              <th class="text-right" style="min-width: 120px;">Total %</th>
            </tr>
          </thead>
          <tbody id="rows"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  (function () {
    const yearFilter = document.getElementById('yearFilter');
    const refreshBtn = document.getElementById('refreshBtn');
    const statusEl = document.getElementById('status');
    const rowsEl = document.getElementById('rows');

    function fmtINR(amount) {
      const n = Number(amount || 0);
      try {
        return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 }).format(n);
      } catch (e) {
        return '₹ ' + Math.round(n).toString();
      }
    }

    function fmtPct(p) {
      if (p === null || typeof p === 'undefined' || Number.isNaN(Number(p))) return '-';
      const n = Number(p);
      const sign = n > 0 ? '+' : '';
      return sign + n.toFixed(2) + '%';
    }

    function setStatus(msg) {
      statusEl.textContent = msg || '';
    }

    async function fetchData() {
      const y = yearFilter.value;
      const url = y ? `<?php echo base_url('reports/sabeel-diff/data'); ?>?year=${encodeURIComponent(y)}` : `<?php echo base_url('reports/sabeel-diff/data'); ?>`;

      setStatus('Loading…');
      rowsEl.innerHTML = '';

      const res = await fetch(url, { credentials: 'same-origin' });
      const json = await res.json();
      if (!json || !json.success) {
        throw new Error((json && json.error) ? json.error : 'Failed to load data');
      }
      return json;
    }

    function populateYearOptions(currentYear, previousYear) {
      // If dropdown already populated with >1 entries, do nothing.
      const opts = yearFilter.querySelectorAll('option');
      if (opts.length > 1) return;

      // Build year list from server-provided current/previous and also fallback to known years via additional fetch if needed.
      // Minimal approach: allow typing by selecting current year only; years list will grow as user navigates.
      yearFilter.innerHTML = '';
      const opt0 = document.createElement('option');
      opt0.value = '';
      opt0.textContent = currentYear ? `${currentYear} (default)` : 'Select year';
      yearFilter.appendChild(opt0);
      if (currentYear) {
        const opt = document.createElement('option');
        opt.value = currentYear;
        opt.textContent = `${currentYear} (prev ${previousYear || '-'})`;
        yearFilter.appendChild(opt);
        yearFilter.value = currentYear;
      }
    }

    function render(payload) {
      const rows = (payload && payload.rows) ? payload.rows : [];
      const currY = payload && payload.current_year ? payload.current_year : '';
      const prevY = payload && payload.previous_year ? payload.previous_year : '';
      // Populate year dropdown from payload.years
      if (payload && Array.isArray(payload.years)) {
        yearFilter.innerHTML = '';
        for (const y of payload.years) {
          const opt = document.createElement('option');
          opt.value = y;
          opt.textContent = y;
          yearFilter.appendChild(opt);
        }
        if (currY) yearFilter.value = currY;
      } else {
        populateYearOptions(currY, prevY);
      }

      if (!rows.length) {
        rowsEl.innerHTML = '<tr><td colspan="9" class="text-center text-muted">No data</td></tr>';
        return;
      }

      // Sort by sector/subsector/name already from server; keep stable.

      rowsEl.innerHTML = rows.map((r, idx) => {
        const tPrev = Number(r.total_previous || 0);
        const tCurr = Number(r.total_current || 0);
        const tDiff = Number(r.total_diff || 0);
        const tPct = r.total_diff_percent;

        const tClass = tDiff > 0 ? 'text-success' : (tDiff < 0 ? 'text-danger' : '');
        return `
          <tr>
            <td>${idx + 1}</td>
            <td>${(r.its_id || '-')}</td>
            <td>${(r.full_name || '-')}</td>
            <td>${(r.sector || '-')}</td>
            <td>${(r.sub_sector || '-')}</td>

            <td class="text-right">${fmtINR(tPrev)}</td>
            <td class="text-right">${fmtINR(tCurr)}</td>
            <td class="text-right ${tClass}">${fmtINR(tDiff)}</td>
            <td class="text-right ${tClass}">${fmtPct(tPct)}</td>
          </tr>
        `;
      }).join('');
    }

    async function refresh() {
      try {
        const payload = await fetchData();
        render(payload);
        const currY = payload && payload.current_year ? payload.current_year : '';
        const prevY = payload && payload.previous_year ? payload.previous_year : '';
        const count = payload && payload.rows ? payload.rows.length : 0;
        setStatus(`Loaded ${count} member(s). ${prevY ? ('Prev: ' + prevY + ' | ') : ''}${currY ? ('Curr: ' + currY) : ''}`);
      } catch (e) {
        setStatus(e && e.message ? e.message : 'Error');
        rowsEl.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Error loading data</td></tr>';
      }
    }

    refreshBtn.addEventListener('click', function () {
      refresh();
    });

    yearFilter.addEventListener('change', function () {
      refresh();
    });

    refresh();
  })();
</script>
