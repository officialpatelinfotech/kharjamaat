<?php // Sabeel user detail page
$user_summary = isset($user_summary) ? $user_summary : null;
$records = isset($records) ? $records : [];
$payments = isset($payments) ? $payments : [];
?>
<?php
// Small helper to format numbers in Indian (INR) grouping: 1,23,45,678
if (!function_exists('format_inr')) {
  function format_inr($number, $decimals = 2) {
    $number = round((float)$number, $decimals);
    $sign = '';
    if ($number < 0) { $sign = '-'; $number = abs($number); }
    $parts = explode('.', number_format($number, $decimals, '.', ''));
    $int = $parts[0];
    $last3 = substr($int, -3);
    $rest = substr($int, 0, -3);
    if ($rest !== false && $rest !== '' ) {
      $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest) . ',';
    } else {
      $rest = '';
    }
    $formatted = $sign . $rest . $last3;
    if ($decimals > 0) {
      $formatted .= '.' . str_pad($parts[1] ?? '0', $decimals, '0');
    }
    return $formatted;
  }
}
?>
<div class="container margintopcontainer pt-5">
  <div class="col-3">
    <a href="<?php echo base_url("common/sabeel_users"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <div class="container mt-4">
    <?php if ($user_summary): ?>
      <h3><?php echo htmlspecialchars($user_summary['full_name'] ?? ''); ?> (<?php echo htmlspecialchars($user_summary['user_id'] ?? ''); ?>)</h3>
      <p>Total Takhmeen: ₹ <?php echo format_inr((float)($user_summary['total_takhmeen'] ?? 0)); ?> | Paid: ₹ <?php echo format_inr((float)($user_summary['total_paid'] ?? 0)); ?> | Outstanding: ₹ <?php echo format_inr(max(0, (float)($user_summary['outstanding'] ?? 0))); ?></p>
    <?php else: ?>
      <h3>User Details</h3>
    <?php endif; ?>

    <h5>Records</h5>
    <?php
      // Sort records by starting Hijri year (desc)
      $records_sorted = $records;
      usort($records_sorted, function($a, $b) {
        $ay = 0; $by = 0;
        if (!empty($a['year'])) { $ap = explode('-', $a['year']); $ay = (int)($ap[0] ?? 0); }
        if (!empty($b['year'])) { $bp = explode('-', $b['year']); $by = (int)($bp[0] ?? 0); }
        // Descending: latest year first
        if ($by === $ay) return 0;
        return ($by < $ay) ? -1 : 1;
      });
    ?>
    <table class="table table-sm table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Year</th>
          <th>Est Grade</th>
          <th>Est Amount</th>
          <th>Res Grade</th>
          <th>Res Yearly Amount</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; foreach ($records_sorted as $r):
          $estAmt = isset($r['est_amount']) ? (float)$r['est_amount'] : 0;
          $resAmt = isset($r['res_amount']) ? (float)$r['res_amount'] : 0;
        ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($r['year'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($r['est_grade'] ?? ''); ?></td>
            <td>₹ <?php echo format_inr($estAmt); ?></td>
            <td><?php echo htmlspecialchars($r['res_grade'] ?? ''); ?></td>
            <td>₹ <?php echo format_inr($resAmt); ?></td>
            <td>₹ <?php echo format_inr(($estAmt + $resAmt)); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h5>Payments</h5>
    <table class="table table-sm table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Amount</th>
          <th>Reference</th>
        </tr>
      </thead>
      <tbody>
        <?php $j = 1;
        foreach ($payments as $p): ?>
          <tr>
            <td><?php echo $j++; ?></td>
            <td><?php echo htmlspecialchars($p['payment_date'] ?? ''); ?></td>
            <td>₹ <?php echo format_inr((float)($p['amount'] ?? 0)); ?></td>
            <td><?php echo htmlspecialchars($p['reference'] ?? ''); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>