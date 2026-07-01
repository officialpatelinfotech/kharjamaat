<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:        #b8860b;
    --gold-light:  #e6c84a;
    --gold-muted:  #f5e9c0;
    --gold-deep:   #8a6408;
    --bg:          #faf7f0;
    --surface:     #ffffff;
    --surface-2:   #f7f4ec;
    --border:      #e8e0cc;
    --text-1:      #1a1610;
    --text-2:      #5a5244;
    --text-3:      #9c8f7a;
    --green:       #1a6645;
    --green-bg:    #eaf4ee;
    --green-border:#bbf7d0;
    --red:         #b91c1c;
    --red-bg:      #fef2f2;
    --red-border:  #fecaca;
    --blue:        #1d4ed8;
    --blue-bg:     #eff6ff;
    --blue-border: #bfdbfe;
    --amber:       #b45309;
    --amber-bg:    #fffbeb;
    --teal:        #0e7490;
    --teal-bg:     #ecfeff;
    --teal-border: #a5f3fc;
    --radius-sm:   8px;
    --radius:      14px;
    --radius-lg:   20px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow:      0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-lg:   0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.05);
  }

  body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); }

  /* ── Page header ── */
  .page-header-wrap {
    position: relative; display: flex; align-items: center;
    justify-content: center; min-height: 44px; margin-bottom: 6px;
  }
  .btn-back-nav {
    position: absolute; left: 0; width: 38px; height: 38px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--radius-sm); border: 1.5px solid var(--border);
    background: var(--surface); color: var(--text-2); font-size: 14px;
    text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s;
  }
  .btn-back-nav:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.5rem; font-weight: 600; letter-spacing: -.3px; margin: 0; text-align: center; }
  .page-sub { font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); text-align: center; margin-top: 4px; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 18px 0 24px; }

  /* ── Totals strip ── */
  .totals-strip {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 14px; margin-bottom: 24px;
  }
  .total-tile {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
    padding: 18px 20px; overflow: hidden; position: relative;
    transition: box-shadow .2s;
  }
  .total-tile:hover { box-shadow: var(--shadow); }
  .total-tile::before { content: ''; display: block; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
  .total-tile.tile-fees::before { background: linear-gradient(90deg, var(--blue),  #60a5fa); }
  .total-tile.tile-due::before  { background: linear-gradient(90deg, var(--red),   #f87171); }
  .total-tile .tl { font-size: 0.65rem; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: var(--text-3); margin-bottom: 6px; }
  .total-tile .tv { font-size: 1.6rem; font-weight: 800; letter-spacing: -1px; font-variant-numeric: tabular-nums; }
  .tv-blue { color: var(--blue); }
  .tv-red  { color: var(--red); }
  @media (max-width: 480px) { .total-tile .tv { font-size: 1.3rem; } }

  /* ── Section card ── */
  .section-card {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
    overflow: hidden; margin-bottom: 20px;
  }
  .section-card::before { content: ''; display: block; height: 3px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); }
  .section-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 13px 20px 11px; background: var(--surface-2); border-bottom: 1px solid var(--border);
  }
  .section-card-title {
    font-size: 0.78rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
    color: var(--text-2); display: flex; align-items: center; gap: 8px; margin: 0;
  }
  .section-card-title .fa {
    width: 26px; height: 26px; border-radius: 7px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.75rem; background: var(--gold-muted); color: var(--gold);
  }

  /* ── Classes table ── */
  .t-wrap { overflow-x: auto; overflow-y: auto; max-height: 65vh; -webkit-overflow-scrolling: touch; }
  .themed-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
  .themed-table thead th {
    position: sticky; top: 0; z-index: 2;
    font-size: 0.63rem; font-weight: 700; letter-spacing: .55px; text-transform: uppercase;
    color: var(--text-3); padding: 10px 14px; border-bottom: 1.5px solid var(--border);
    background: var(--surface-2); white-space: nowrap;
  }
  .themed-table tbody td { padding: 11px 14px; border-bottom: 1px solid var(--border); color: var(--text-2); vertical-align: middle; white-space: nowrap; }
  .themed-table tbody tr:last-child td { border-bottom: none; }
  .themed-table tbody tr:nth-of-type(odd)  { background: var(--surface-2); }
  .themed-table tbody tr:nth-of-type(even) { background: var(--surface); }
  .themed-table tbody tr:hover { background: var(--gold-muted) !important; }

  .t-num   { color: var(--text-3); font-size: 0.72rem; font-weight: 700; }
  .t-name  { font-weight: 700; color: var(--text-1); }
  .t-year  { font-size: 0.75rem; color: var(--text-3); font-weight: 600; }
  .t-amt   { font-weight: 700; font-variant-numeric: tabular-nums; text-align: right; }
  .t-blue  { color: var(--blue); }
  .t-green { color: var(--green); }
  .t-red   { color: var(--red); }

  /* status badge */
  .status-pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 40px;
    font-size: 0.63rem; font-weight: 700; letter-spacing: .3px;
  }
  .pill-active   { background: var(--teal-bg);  color: var(--teal);  border: 1px solid var(--teal-border); }
  .pill-inactive { background: var(--surface-2); color: var(--text-3); border: 1px solid var(--border); }

  /* history btn */
  .btn-history {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 13px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); background: var(--surface);
    color: var(--text-2); font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.72rem; font-weight: 700; cursor: pointer; transition: all .15s;
  }
  .btn-history:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold-deep); }

  /* ── Empty state ── */
  .empty-state { padding: 48px 24px; text-align: center; }
  .empty-state .fa { font-size: 2.5rem; color: var(--border); display: block; margin-bottom: 12px; }
  .empty-state p { font-size: 0.88rem; color: var(--text-3); font-weight: 500; margin: 0; }

  /* ── History Modal ── */
  .modal-content {
    border: 1.5px solid var(--border) !important; border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow-lg) !important; overflow: hidden;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  .modal-header {
    background: var(--surface-2); border-bottom: 1px solid var(--border);
    padding: 18px 24px; display: flex; align-items: center; justify-content: space-between;
  }
  .modal-title { font-family: 'Literata', Georgia, serif; font-size: 1.1rem; font-weight: 600; color: var(--gold); margin: 0; }
  .modal-header .close {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); background: var(--surface);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; cursor: pointer; color: var(--text-2);
    transition: all .15s; padding: 0; opacity: 1; text-shadow: none;
  }
  .modal-header .close:hover { background: var(--red-bg); border-color: var(--red-border); color: var(--red); }

  .modal-body { background: var(--bg); padding: 0 !important; }
  .modal-footer { background: var(--surface-2); border-top: 1px solid var(--border); padding: 14px 24px; display: flex; justify-content: flex-end; }

  /* student info band */
  .modal-info-band {
    padding: 14px 20px 12px; background: var(--surface-2);
    border-bottom: 1px solid var(--border);
  }
  .modal-student-name { font-size: 0.9rem; font-weight: 700; color: var(--text-1); margin-bottom: 2px; }
  .modal-class-name   { font-size: 0.7rem; font-weight: 600; letter-spacing: .3px; color: var(--text-3); }

  /* history table */
  .history-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
  .history-table thead th {
    font-size: 0.63rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
    color: var(--text-3); padding: 9px 14px; border-bottom: 1.5px solid var(--border);
    background: var(--surface-2); white-space: nowrap; position: sticky; top: 0; z-index: 1;
  }
  .history-table tbody td { padding: 11px 14px; border-bottom: 1px solid var(--border); color: var(--text-2); vertical-align: middle; }
  .history-table tbody tr:last-child td { border-bottom: none; }
  .history-table tbody tr:hover { background: #fdfbf5; }

  .method-pill {
    display: inline-block; padding: 2px 9px; border-radius: 40px;
    font-size: 0.65rem; font-weight: 700;
    background: var(--surface-2); color: var(--text-2); border: 1px solid var(--border);
  }
  .btn-receipt, .btn-pdf {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: var(--radius-sm); font-size: 0.7rem; font-weight: 700;
    cursor: pointer; transition: all .15s; border: 1.5px solid;
  }
  .btn-receipt { background: var(--blue-bg); border-color: var(--blue-border); color: var(--blue); }
  .btn-receipt:hover { background: var(--blue); color: #fff; }
  .btn-pdf { background: var(--red-bg); border-color: var(--red-border); color: var(--red); margin-left: 5px; }
  .btn-pdf:hover { background: var(--red); color: #fff; }

  /* loading / empty states */
  .modal-state { padding: 32px; text-align: center; font-size: 0.83rem; color: var(--text-3); }
  .modal-state .fa { font-size: 1.5rem; display: block; margin-bottom: 8px; opacity: .4; }

  .btn-modal-close {
    padding: 9px 22px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); background: var(--surface);
    color: var(--text-2); font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all .15s;
  }
  .btn-modal-close:hover { background: var(--surface-2); }

  @media (max-width: 575px) {
    .page-heading { font-size: 1.2rem; }
    .totals-strip { grid-template-columns: 1fr; }
    .modal-header, .modal-footer { padding: 14px 16px !important; }
  }
</style>

<?php
  $fmtMoney = function ($n) {
    $n = ($n === '' || $n === null) ? 0 : (float)$n;
    $formatted = function_exists('format_inr') ? format_inr($n, 2) : number_format($n, 2);
    return preg_replace('/\.00$/', '', (string)$formatted);
  };
  $totals     = !empty($madresa_totals)    && is_array($madresa_totals)    ? $madresa_totals    : [];
  $classes    = !empty($madresa_classes)   && is_array($madresa_classes)   ? $madresa_classes   : [];
  $selectedHy = !empty($selected_hijri_year) ? (int)$selected_hijri_year : 0;
?>

<div class="container margintopcontainer pt-5 pb-5" style="max-width:1200px;">

  <!-- ── Header ── -->
  <div class="page-header-wrap pt-5">
    <a href="<?php echo base_url('accounts/home'); ?>" class="btn-back-nav"><i class="fa fa-arrow-left"></i></a>
    <h1 class="page-heading">Madresa Fees &amp; Dues</h1>
  </div>
  <p class="page-sub">Class fees, payments &amp; outstanding dues</p>
  <hr class="section-divider">

  <!-- ── Totals ── -->
  <div class="totals-strip">
    <div class="total-tile tile-fees">
      <div class="tl">Total Fees</div>
      <div class="tv tv-blue">₹<?php echo htmlspecialchars($fmtMoney($totals['total_fees'] ?? 0)); ?></div>
    </div>
    <div class="total-tile tile-due">
      <div class="tl">Total Dues</div>
      <div class="tv tv-red">₹<?php echo htmlspecialchars($fmtMoney($totals['total_dues'] ?? 0)); ?></div>
    </div>
  </div>

  <!-- ── Classes table ── -->
  <div class="section-card">
    <div class="section-card-header">
      <h5 class="section-card-title"><i class="fa fa-graduation-cap"></i> Classes</h5>
    </div>
    <div class="t-wrap">
      <?php if (empty($classes)): ?>
        <div class="empty-state">
          <i class="fa fa-book"></i>
          <p>No Madresa classes found for Hijri year <?php echo $selectedHy; ?>.</p>
        </div>
      <?php else: ?>
        <table class="themed-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Class</th>
              <th>Hijri Year</th>
              <th class="text-right">Fees</th>
              <th class="text-right">Paid</th>
              <th class="text-right">Due</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($classes as $c): ?>
              <?php $statusVal = (string)($c['status'] ?? 'Active'); ?>
              <tr>
                <td><span class="t-num"><?php echo $i++; ?></span></td>
                <td><span class="t-name"><?php echo htmlspecialchars((string)($c['class_name'] ?? '')); ?></span></td>
                <td><span class="t-year"><?php echo htmlspecialchars((string)($c['hijri_year'] ?? '')); ?></span></td>
                <td><span class="t-amt t-blue">₹<?php echo htmlspecialchars($fmtMoney($c['fees'] ?? 0)); ?></span></td>
                <td><span class="t-amt t-green">₹<?php echo htmlspecialchars($fmtMoney($c['amount_paid'] ?? 0)); ?></span></td>
                <td><span class="t-amt t-red">₹<?php echo htmlspecialchars($fmtMoney($c['amount_due'] ?? 0)); ?></span></td>
                <td>
                  <span class="status-pill <?php echo strtolower($statusVal) === 'active' ? 'pill-active' : 'pill-inactive'; ?>">
                    <i class="fa <?php echo strtolower($statusVal) === 'active' ? 'fa-circle' : 'fa-minus-circle'; ?>" style="font-size:8px;"></i>
                    <?php echo htmlspecialchars($statusVal); ?>
                  </span>
                </td>
                <td>
                  <button class="btn-history"
                    data-class-id="<?php echo (int)($c['class_id'] ?? 0); ?>"
                    data-student-its-id="<?php echo htmlspecialchars((string)($c['students_its_id'] ?? '')); ?>"
                    data-student-name="<?php echo htmlspecialchars((string)($c['student_name'] ?? '')); ?>"
                    data-class-name="<?php echo htmlspecialchars((string)($c['class_name'] ?? '')); ?>">
                    <i class="fa fa-history"></i> History
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

</div><!-- /.container -->

<!-- ── Payment History Modal ── -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyModalLabel">
          <i class="fa fa-history" style="margin-right:8px;color:var(--gold);font-size:.9rem;"></i>Payment History
        </h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="modal-info-band">
          <div class="modal-student-name" id="modal-student-info"></div>
          <div class="modal-class-name"   id="modal-class-info"></div>
        </div>
        <div class="table-responsive" style="max-height:55vh; overflow-y:auto;">
          <table class="history-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Method</th>
                <th>Description</th>
                <th class="text-right">Amount</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody id="history-table-body"></tbody>
          </table>
        </div>
        <div id="history-loading" class="modal-state" style="display:none;">
          <i class="fa fa-spinner fa-spin"></i> Loading history…
        </div>
        <div id="history-empty" class="modal-state" style="display:none;">
          <i class="fa fa-inbox"></i> No payment history found.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-modal-close" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- ══ ALL JS IDENTICAL TO ORIGINAL ══ -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const historyTableBody = document.getElementById('history-table-body');
  const historyLoading   = document.getElementById('history-loading');
  const historyEmpty     = document.getElementById('history-empty');
  const modalStudentInfo = document.getElementById('modal-student-info');
  const modalClassInfo   = document.getElementById('modal-class-info');

  function formatINR(amount) {
    const n = parseFloat(amount) || 0;
    return new Intl.NumberFormat('en-IN', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(n);
  }

  function escapeHtml(str) {
    return String(str == null ? '' : str)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
  }

  document.querySelectorAll('.btn-history').forEach(btn => {
    btn.addEventListener('click', function () {
      const classId      = this.getAttribute('data-class-id');
      const studentItsId = this.getAttribute('data-student-its-id');
      const studentName  = this.getAttribute('data-student-name');
      const className    = this.getAttribute('data-class-name');

      modalStudentInfo.textContent = studentName + ' (' + studentItsId + ')';
      modalClassInfo.textContent   = 'Class: ' + className;

      historyTableBody.innerHTML   = '';
      historyLoading.style.display = 'block';
      historyEmpty.style.display   = 'none';

      $('#historyModal').modal('show');

      fetch('<?php echo base_url('accounts/ajax-madresa-payment-history'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'class_id=' + classId + '&student_its_id=' + studentItsId
      })
      .then(r => r.json())
      .then(res => {
        historyLoading.style.display = 'none';
        if (res.success && res.payments && res.payments.length > 0) {
          let html = '';
          res.payments.forEach(p => {
            let dateStr = p.paid_on || p.created_at || '';
            if (dateStr && dateStr !== '0000-00-00' && dateStr !== '0000-00-00 00:00:00') {
              try {
                const d = new Date(dateStr.replace(/-/g, '/'));
                if (!isNaN(d.getTime())) {
                  dateStr = String(d.getDate()).padStart(2,'0') + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + d.getFullYear();
                }
              } catch(e) {}
            } else { dateStr = '-'; }

            const method     = p.payment_mode ? escapeHtml(p.payment_mode) : '-';
            const desc       = p.notes        ? escapeHtml(p.notes)        : '-';
            const receiptUrl = '<?php echo base_url('madresa/classes/payment-receipt/'); ?>' + p.m_class_id + '?payment_id=' + p.id;
            const pid        = parseInt(p.id, 10) || 0;

            html += `<tr>
              <td style="font-weight:600;color:var(--text-1);">${dateStr}</td>
              <td><span class="method-pill">${method}</span></td>
              <td style="color:var(--text-3);font-size:.78rem;">${desc}</td>
              <td class="text-right" style="font-weight:700;color:var(--green);">₹${formatINR(p.amount)}</td>
              <td class="text-center">
                <a href="${receiptUrl}" class="btn-receipt" target="_blank" title="View Receipt">
                  <i class="fa fa-file-text-o"></i> Receipt
                </a>
                <button type="button" class="btn-pdf" onclick="openMadresaReceiptPdf(${pid})" title="PDF">
                  <i class="fa fa-file-pdf-o"></i> PDF
                </button>
              </td>
            </tr>`;
          });
          historyTableBody.innerHTML = html;
        } else {
          historyEmpty.style.display = 'block';
        }
      })
      .catch(err => {
        console.error('Error fetching history:', err);
        historyLoading.style.display = 'none';
        historyEmpty.textContent     = 'Error loading history.';
        historyEmpty.style.display   = 'block';
      });
    });
  });
});

function openMadresaReceiptPdf(paymentId) {
  if (!paymentId) return;
  const form = new FormData();
  form.append('id', String(paymentId));
  form.append('for', '7');
  fetch('<?php echo base_url('common/generate_pdf'); ?>', { method: 'POST', body: form })
    .then(resp => { if (!resp.ok) throw new Error('HTTP ' + resp.status); return resp.blob(); })
    .then(blob => { window.open(window.URL.createObjectURL(blob), '_blank'); })
    .catch(() => alert('Failed to generate receipt PDF'));
}
</script>