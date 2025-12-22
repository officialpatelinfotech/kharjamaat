<div class="container-fluid margintopcontainer pt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="<?= base_url('anjuman'); ?>" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i></a>
    <div></div>
  </div>
  <h4 class="m-2 text-center">Individual Details</h4>

  <div class="card mb-3">
    <div class="card-body">
      <h6>Find Member</h6>
      <form method="get" action="<?= base_url('anjuman/member_financials'); ?>" class="form-inline mb-2">
        <div class="form-row w-100">
          <div class="col-auto mb-2">
            <input type="text" name="its_id" value="<?= htmlspecialchars($user_its ?? ''); ?>" class="form-control form-control-sm" placeholder="Enter ITS ID" />
          </div>
          <div class="col-auto mb-2">
            <input type="text" name="hof_id" value="<?= htmlspecialchars($user_hof ?? ''); ?>" class="form-control form-control-sm" placeholder="Or HOF ID" />
          </div>
          <div class="col-auto mb-2">
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
          </div>
          <div class="col-auto mb-2">
            <a href="<?= base_url('anjuman/member_financials'); ?>" class="btn btn-outline-secondary btn-sm">Clear</a>
          </div>
        </div>
      </form>
      <?php if (empty($user_its) && empty($user_hof)): ?>
        <div class="mt-2 text-muted">Enter ITS ID or HOF ID and click Search.</div>
      <?php else: ?>
        <div class="mb-0">
          <strong>Showing details for:</strong>
          ITS: <?= htmlspecialchars($user_its ?? ''); ?> &nbsp;
          HOF ID: <?= htmlspecialchars($user_hof ?? ''); ?>
          <?php if (!empty($hof_name)): ?>
            &nbsp; | &nbsp; HOF Name: <?= htmlspecialchars($hof_name); ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <?php
        // Compute overall totals across sections
        $overall_total = 0.0;
        $overall_paid = 0.0;
        $overall_due = 0.0;

        // Sabeel totals (if computed above)
        if (!empty($sabeel) && !empty($sabeel['takhmeens'])) {
          foreach ($sabeel['takhmeens'] as $t) {
            $est_y = (float)($t['establishment']['yearly'] ?? 0);
            $est_paid = (float)($t['establishment']['paid'] ?? 0);
            $res_y = (float)($t['residential']['yearly'] ?? 0);
            $res_paid = (float)($t['residential']['paid'] ?? 0);
            $overall_total += ($est_y + $res_y);
            $overall_paid += ($est_paid + $res_paid);
          }
        }

        // FMB takhmeen
        if (!empty($fmb_takhmeen)) {
          foreach ($fmb_takhmeen as $f) {
            $amt = (float)($f['total_amount'] ?? 0);
            $paid = (float)($f['amount_paid'] ?? 0);
            $overall_total += $amt;
            $overall_paid += $paid;
          }
        }

        // Corpus assignments
        if (!empty($corpus)) {
          foreach ($corpus as $c) {
            $assigned = (float)($c['amount_assigned'] ?? 0);
            $paid = (float)($c['amount_paid'] ?? 0);
            $overall_total += $assigned;
            $overall_paid += $paid;
          }
        }

        // General contributions
        if (!empty($general_contribs)) {
          foreach ($general_contribs as $g) {
            $amt = (float)($g['amount'] ?? 0);
            $paid = (float)($g['amount_paid'] ?? 0);
            $overall_total += $amt;
            $overall_paid += $paid;
          }
        }

        // Miqaat invoices
        if (!empty($miqaat_invoices)) {
          foreach ($miqaat_invoices as $m) {
            $amt = (float)($m['amount'] ?? 0);
            $paid = (float)($m['paid_amount'] ?? 0);
            $overall_total += $amt;
            $overall_paid += $paid;
          }
        }

        $overall_due = max(0, $overall_total - $overall_paid);
      ?>
      <div class="mb-2">
        <strong>Overall Summary:</strong>
        <span class="ml-2">Total: ₹<?= format_inr($overall_total, 0); ?></span>
        <span class="ml-3 text-success">Paid: ₹<?= format_inr($overall_paid, 0); ?></span>
        <span class="ml-3 text-danger">Due: ₹<?= format_inr($overall_due, 0); ?></span>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8">
      <div class="card mb-3">
        <div class="card-header">Sabeel Takhmeen</div>
        <div class="card-body small">
          <?php if (!empty($sabeel) && !empty($sabeel['takhmeens'])): ?>
            <?php
              // compute totals (establishment + residential)
              $s_total_amount = 0.0;
              $s_total_paid = 0.0;
              $s_total_due = 0.0;
              foreach ($sabeel['takhmeens'] as $t) {
                $est_y = (float)($t['establishment']['yearly'] ?? 0);
                $est_paid = (float)($t['establishment']['paid'] ?? 0);
                $est_due = (float)($t['establishment']['due'] ?? 0);
                $res_y = (float)($t['residential']['yearly'] ?? 0);
                $res_paid = (float)($t['residential']['paid'] ?? 0);
                $res_due = (float)($t['residential']['due'] ?? 0);
                $s_total_amount += ($est_y + $res_y);
                $s_total_paid += ($est_paid + $res_paid);
                $s_total_due += ($est_due + $res_due);
              }
            ?>
            <div class="mb-2">
              <strong>Totals:</strong>
              <span class="ml-2">Total Amount: ₹<?= format_inr($s_total_amount, 0); ?></span>
              <span class="ml-3 text-success">Paid: ₹<?= format_inr($s_total_paid, 0); ?></span>
              <span class="ml-3 text-danger">Due: ₹<?= format_inr($s_total_due, 0); ?></span>
            </div>
            <div class="table-responsive">
              <table class="table table-sm table-bordered mb-0">
                <thead class="thead-light">
                  <tr>
                    <th>Year</th>
                    <th class="text-right">Establishment (Yearly)</th>
                    <th class="text-right">Est Paid</th>
                    <th class="text-right">Est Due</th>
                    <th class="text-right">Residential (Yearly)</th>
                    <th class="text-right">Res Paid</th>
                    <th class="text-right">Res Due</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($sabeel['takhmeens'] as $t): ?>
                    <tr>
                      <td><?= htmlspecialchars($t['year']); ?></td>
                      <td class="text-right">₹<?= format_inr((float)($t['establishment']['yearly'] ?? 0), 0); ?></td>
                        <td class="text-right <?php echo ((float)($t['establishment']['paid'] ?? 0) > 0) ? 'text-success' : 'text-muted'; ?>">₹<?= format_inr((float)($t['establishment']['paid'] ?? 0), 0); ?></td>
                        <td class="text-right <?php echo ((float)($t['establishment']['due'] ?? 0) > 0) ? 'text-danger' : 'text-muted'; ?>">₹<?= format_inr((float)($t['establishment']['due'] ?? 0), 0); ?></td>
                      <td class="text-right">₹<?= format_inr((float)($t['residential']['yearly'] ?? 0), 0); ?></td>
                      <td class="text-right <?php echo ((float)($t['residential']['paid'] ?? 0) > 0) ? 'text-success' : 'text-muted'; ?>">₹<?= format_inr((float)($t['residential']['paid'] ?? 0), 0); ?></td>
                      <td class="text-right <?php echo ((float)($t['residential']['due'] ?? 0) > 0) ? 'text-danger' : 'text-muted'; ?>">₹<?= format_inr((float)($t['residential']['due'] ?? 0), 0); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="text-muted">No Sabeel takhmeen records for this member.</div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">FMB Takhmeen</div>
        <div class="card-body small">
          <?php if (!empty($fmb_takhmeen)): ?>
            <table class="table table-sm">
              <thead><tr><th>Year</th><th class="text-right">Amount</th><th class="text-right">Paid</th><th class="text-right">Due</th></tr></thead>
              <tbody>
                <?php foreach ($fmb_takhmeen as $f): ?>
                  <tr>
                    <td><?= htmlspecialchars($f['year']); ?></td>
                    <td class="text-right">₹<?= format_inr($f['total_amount'], 0); ?></td>
                    <td class="text-right <?php echo ((float)($f['amount_paid'] ?? 0) > 0) ? 'text-success' : 'text-muted'; ?>">₹<?= format_inr($f['amount_paid'] ?? 0, 0); ?></td>
                    <td class="text-right <?php echo ((float)($f['due'] ?? 0) > 0) ? 'text-danger' : 'text-muted'; ?>">₹<?= format_inr($f['due'] ?? 0, 0); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div class="text-muted">No FMB takhmeen records.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card mb-3">
        <div class="card-header">Corpus Fund Assignments</div>
        <div class="card-body small">
          <?php if (!empty($corpus)): ?>
            <table class="table table-sm">
              <thead><tr><th>Fund</th><th class="text-right">Assigned</th><th class="text-right">Paid</th><th class="text-right">Due</th></tr></thead>
              <tbody>
                <?php foreach ($corpus as $c): ?>
                  <tr>
                    <td><?= htmlspecialchars($c['title'] ?? ''); ?></td>
                    <td class="text-right">₹<?= format_inr($c['amount_assigned'] ?? 0, 0); ?></td>
                    <td class="text-right <?php echo ((float)($c['amount_paid'] ?? 0) > 0) ? 'text-success' : 'text-muted'; ?>">₹<?= format_inr($c['amount_paid'] ?? 0, 0); ?></td>
                      <?php $c_due = max(0, ($c['amount_assigned'] ?? 0) - ($c['amount_paid'] ?? 0)); ?>
                      <td class="text-right <?php echo ($c_due > 0) ? 'text-danger' : 'text-muted'; ?>">₹<?= format_inr($c_due, 0); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div class="text-muted">No corpus assignments.</div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">General Contributions (FMB GC)</div>
        <div class="card-body small">
          <?php if (!empty($general_contribs)): ?>
            <table class="table table-sm">
              <thead><tr><th>Type</th><th class="text-right">Amount</th><th class="text-right">Paid</th><th class="text-right">Due</th></tr></thead>
              <tbody>
                <?php foreach ($general_contribs as $g): ?>
                  <tr>
                    <td><?= htmlspecialchars($g['contrib_type'] ?? $g['contr_type'] ?? 'General'); ?></td>
                    <td class="text-right">₹<?= format_inr($g['amount'] ?? 0, 0); ?></td>
                    <td class="text-right <?php echo ((float)($g['amount_paid'] ?? 0) > 0) ? 'text-success' : 'text-muted'; ?>">₹<?= format_inr($g['amount_paid'] ?? 0, 0); ?></td>
                    <td class="text-right <?php echo ((float)($g['amount_due'] ?? 0) > 0) ? 'text-danger' : 'text-muted'; ?>">₹<?= format_inr($g['amount_due'] ?? 0, 0); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div class="text-muted">No general contributions.</div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">Miqaat Invoices</div>
        <div class="card-body small">
          <?php if (!empty($miqaat_invoices)): ?>
            <table class="table table-sm">
              <thead><tr><th>Invoice</th><th class="text-right">Amount</th><th class="text-right">Paid</th><th class="text-right">Due</th></tr></thead>
              <tbody>
                <?php foreach ($miqaat_invoices as $m): ?>
                  <tr>
                    <td><?= htmlspecialchars($m['miqaat_name'] ?? ('#'.$m['id'])); ?></td>
                    <td class="text-right">₹<?= format_inr($m['amount'] ?? 0, 0); ?></td>
                    <td class="text-right <?php echo ((float)($m['paid_amount'] ?? 0) > 0) ? 'text-success' : 'text-muted'; ?>">₹<?= format_inr($m['paid_amount'] ?? 0, 0); ?></td>
                    <td class="text-right <?php echo ((float)($m['due_amount'] ?? 0) > 0) ? 'text-danger' : 'text-muted'; ?>">₹<?= format_inr($m['due_amount'] ?? 0, 0); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div class="text-muted">No miqaat invoices.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
