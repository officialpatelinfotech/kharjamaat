<?php
$summary = isset($user_summary) ? $user_summary : null;
$records = isset($records) ? $records : [];
$payments = isset($payments) ? $payments : [];
$selectedYear = $selected_hijri_year ?? '';

$selectedFy = '';
if (!empty($selectedYear) && is_numeric($selectedYear)) {
  $startYear = (int) $selectedYear;
  $selectedFy = sprintf('%d-%02d', $startYear, ($startYear + 1) % 100);
} elseif (!empty($selectedYear) && preg_match('/^(\d{4})-(\d{2})$/', (string) $selectedYear)) {
  $selectedFy = (string) $selectedYear;
}
?>
<div class="container margintopcontainer pt-5">
  <div class="col-3">
    <a href="<?php echo site_url('common/fmb_users') . ($selectedYear ? ('?hijri_year=' . urlencode($selectedYear)) : ''); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="container mt-3">
    <h4 class="heading text-center mb-3">FMB Details<?= $summary && !empty($summary['full_name']) ? ': ' . htmlspecialchars($summary['full_name']) : '' ?></h4>

    <?php if ($summary): ?>
      <div class="row mb-3">
        <div class="col-md-4 mb-2">
          <div class="card p-3 text-center">
            <div>Total Takhmeen</div>
            <div class="h4 text-primary">₹<?= number_format((float)($summary['total_takhmeen'] ?? 0)) ?></div>
          </div>
        </div>
        <div class="col-md-4 mb-2">
          <div class="card p-3 text-center">
            <div>Total Paid</div>
            <div class="h4 text-success">₹<?= number_format((float)($summary['total_paid'] ?? 0)) ?></div>
          </div>
        </div>
        <div class="col-md-4 mb-2">
          <div class="card p-3 text-center">
            <div>Outstanding</div>
            <div class="h4 text-danger">₹<?= number_format(max(0, (float)($summary['outstanding'] ?? 0))) ?></div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-12">
        <h5 class="mt-3">Takhmeen Records<?= $selectedYear ? ' - Hijri ' . htmlspecialchars($selectedYear) : '' ?></h5>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Year</th>
                <th>Thaali Days</th>
                <th>Total Amount</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($records as $r): ?>
                <?php
                  $assignedDays = 0;
                  if ($summary && isset($summary['assigned_thaali_days'])) {
                    $assignedDays = (int) $summary['assigned_thaali_days'];
                  }
                ?>
                <tr>
                  <td><?= htmlspecialchars($r['year'] ?? '') ?></td>
                  <td>
                    <?php if (!empty($selectedFy) && $assignedDays > 0 && $summary): ?>
                      <a href="#" class="view-assigned-thaali-days" data-user-id="<?= htmlspecialchars($summary['user_id'] ?? '', ENT_QUOTES) ?>" data-user-name="<?= htmlspecialchars($summary['full_name'] ?? '', ENT_QUOTES) ?>" data-year="<?= htmlspecialchars($selectedFy, ENT_QUOTES) ?>">
                        <?= (int) $assignedDays ?>
                      </a>
                    <?php else: ?>
                      <?= (int) $assignedDays ?>
                    <?php endif; ?>
                  </td>
                  <td>₹<?= number_format((float)($r['total_amount'] ?? 0)) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-12">
        <h5 class="mt-3">Payments</h5>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Date</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($payments as $p): ?>
                <tr>
                  <td><?= htmlspecialchars($p['payment_date'] ?? '') ?></td>
                  <td>₹<?= number_format((float)($p['amount'] ?? 0)) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Assigned Thaali Days Modal -->
<div class="modal fade" id="assigned-thaali-days-modal" tabindex="-1" aria-labelledby="assigned-thaali-days-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assigned-thaali-days-title">Thaali Dates</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="mb-1"><b>Member Name:</b> <span id="assigned-thaali-days-member"></span></p>
        <p class="mb-3"><b>FY:</b> <span id="assigned-thaali-days-year"></span></p>
        <div id="assigned-thaali-days-loading" class="text-center text-secondary" style="display:none;">Loading...</div>
        <div id="assigned-thaali-days-empty" class="text-center text-secondary" style="display:none;">No assigned dates.</div>
        <ul id="assigned-thaali-days-list" class="mb-0"></ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Cache greg->hijri label lookups to avoid repeated requests
    var hijriLabelCache = {};
    function getHijriLabelForGregIso(gregIso) {
      return new Promise(function(resolve) {
        if (!gregIso) return resolve('');
        if (hijriLabelCache.hasOwnProperty(gregIso)) return resolve(hijriLabelCache[gregIso]);
        if (!window.jQuery) return resolve('');
        $.ajax({
          url: "<?php echo base_url('common/get_hijri_parts'); ?>",
          type: 'POST',
          dataType: 'json',
          data: { greg_date: gregIso },
          success: function(resp) {
            var label = '';
            if (resp && resp.status === 'success' && resp.parts) {
              var p = resp.parts;
              var day = p.hijri_day || '';
              var monthName = p.hijri_month_name || p.hijri_month || '';
              var year = p.hijri_year || '';
              if (day && monthName && year) {
                var dnum = parseInt(day, 10);
                var d2 = (!isNaN(dnum) && dnum < 10) ? ('0' + dnum) : String(day);
                label = d2 + ' ' + monthName + ' ' + year;
              }
            }
            hijriLabelCache[gregIso] = label;
            resolve(label);
          },
          error: function() {
            hijriLabelCache[gregIso] = '';
            resolve('');
          }
        });
      });
    }

    if (window.jQuery) {
      $(document).on('click', '.view-assigned-thaali-days', function(e) {
        e.preventDefault();
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');
        const year = $(this).data('year');

        $('#assigned-thaali-days-member').text(userName || '');
        $('#assigned-thaali-days-year').text(year || '');
        $('#assigned-thaali-days-list').empty();
        $('#assigned-thaali-days-empty').hide();
        $('#assigned-thaali-days-loading').show();
        $('#assigned-thaali-days-modal').modal('show');

        $.ajax({
          url: "<?php echo base_url('common/getfmbassignedthaalidates'); ?>",
          type: 'POST',
          dataType: 'json',
          data: { user_id: userId, year: year },
          success: function(res) {
            $('#assigned-thaali-days-loading').hide();
            const dates = (res && res.success && Array.isArray(res.dates)) ? res.dates : [];
            if (!dates.length) {
              $('#assigned-thaali-days-empty').show();
              return;
            }
            dates.forEach(function(d) {
              var gregIso = (typeof d === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(d)) ? d : '';
              var gregLabel = d;
              if (gregIso) {
                var parts = gregIso.split('-');
                gregLabel = parts[2] + '-' + parts[1] + '-' + parts[0];
              }

              var $li = $('<li></li>');
              var $greg = $('<span class="greg-label"></span>').text(gregLabel);
              var $hijri = $('<span class="text-muted ml-2 hijri-label"></span>').text('');
              $li.append($greg).append($hijri);
              $('#assigned-thaali-days-list').append($li);

              if (gregIso) {
                getHijriLabelForGregIso(gregIso).then(function(hLabel) {
                  if (hLabel) {
                    $hijri.text(' | ' + hLabel);
                  }
                });
              }
            });
          },
          error: function() {
            $('#assigned-thaali-days-loading').hide();
            $('#assigned-thaali-days-empty').text('Failed to load assigned dates.').show();
          }
        });
      });
    }
  });
</script>
