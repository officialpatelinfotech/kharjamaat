<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Ekram Payment Receipt</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="p-3">
  <div class="container">
    <div class="card">
      <div class="card-header">Ekram Fund Payment Receipt</div>
      <div class="card-body">
        <p><strong>Member:</strong> <?= htmlspecialchars($hof_name ?? ''); ?></p>
        <p><strong>Fund:</strong> <?= htmlspecialchars($fund_title ?? ($fund_year ?? '')); ?></p>
        <p><strong>Amount:</strong> ₹<?= number_format((float)($amount_paid ?? 0), 2); ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($paid_at ?? ''); ?></p>
        <p><strong>Method:</strong> <?= htmlspecialchars($payment_method ?? ''); ?></p>
        <?php if (!empty($notes)): ?>
          <p><strong>Notes:</strong> <?= htmlspecialchars($notes); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
