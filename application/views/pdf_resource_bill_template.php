<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Resource Utilization Bill</title>
  <style>
    @page {
      margin: 20px;
    }
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 11px;
      color: #333;
      line-height: 1.4;
      margin: 0;
      padding: 0;
    }
    .header-title {
      font-size: 15px;
      font-weight: bold;
      text-align: center;
      text-transform: uppercase;
      margin-bottom: 12px;
      border-bottom: 2px solid #000;
      padding-bottom: 6px;
    }
    .info-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    .info-table td {
      border: 1px solid #333;
      padding: 5px 8px;
      vertical-align: middle;
    }
    .info-label {
      font-weight: bold;
      background-color: #f2f2f2;
      width: 18%;
    }
    .info-value {
      width: 32%;
    }
    .bill-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .bill-table th, .bill-table td {
      border: 1px solid #333;
      padding: 5px 8px;
    }
    .bill-table th {
      background-color: #f2f2f2;
      font-weight: bold;
      text-align: left;
    }
    .section-header {
      background-color: #e6e6e6;
      font-weight: bold;
      text-align: left;
    }
    .text-right {
      text-align: right;
    }
    .text-center {
      text-align: center;
    }
    .total-row {
      font-weight: bold;
      background-color: #f9f9f9;
    }
    .grand-total-row {
      font-weight: bold;
      background-color: #e6f2ff;
      font-size: 12px;
    }
    .refund-row {
      font-weight: bold;
      background-color: #ffe6e6;
      font-size: 12px;
    }
    .signatures-section {
      width: 100%;
      margin-top: 40px;
      border-collapse: collapse;
    }
    .signatures-section td {
      width: 25%;
      border: 1px solid #333;
      padding: 15px 8px 5px 8px;
      text-align: center;
      vertical-align: bottom;
      height: 60px;
    }
    .signature-title {
      font-weight: bold;
      margin-top: 5px;
      border-top: 1px solid #999;
      padding-top: 4px;
      font-size: 10px;
    }
  </style>
</head>
<body>

  <div class="header-title">
    Khar Mawaid - Utilisation of Resources
  </div>

  <table class="info-table">
    <tr>
      <td class="info-label">Hirer Name</td>
      <td class="info-value"><strong><?= htmlspecialchars($hirer_name) ?></strong> (ITS: <?= htmlspecialchars($its_id) ?>)</td>
      <td class="info-label">thaals</td>
      <td class="info-value"><?= htmlspecialchars($thaals ?: 'N/A') ?></td>
    </tr>
    <tr>
      <td class="info-label">Jaman Date</td>
      <td class="info-value"><?= htmlspecialchars($jaman_date ? date('d/m/Y', strtotime($jaman_date)) : 'N/A') ?></td>
      <td class="info-label">Type</td>
      <td class="info-value"><?= htmlspecialchars($type) ?></td>
    </tr>
    <tr>
      <td class="info-label">Timings</td>
      <td class="info-value"><?= htmlspecialchars($timing ?: 'N/A') ?></td>
      <td class="info-label">Morning/ Evening</td>
      <td class="info-value"><?= htmlspecialchars(strpos(strtolower($timing), 'maghrib') !== false || strpos(strtolower($timing), 'evening') !== false ? 'evening' : 'morning') ?></td>
    </tr>
    <tr>
      <td class="info-label">Caterer</td>
      <td class="info-value"><?= htmlspecialchars($caterer ?: 'N/A') ?></td>
      <td class="info-label">Decorator</td>
      <td class="info-value"><?= htmlspecialchars($decorator ?: 'N/A') ?></td>
    </tr>
  </table>

  <table class="bill-table">
    <thead>
      <tr>
        <th style="width: 45%;">Resources</th>
        <th style="width: 10%;" class="text-center">#</th>
        <th style="width: 15%;" class="text-right">Rate</th>
        <th style="width: 15%;" class="text-center">UOM</th>
        <th style="width: 15%;" class="text-right">Amount</th>
      </tr>
    </thead>
    <tbody>
      
      <!-- SECTION A -->
      <tr class="section-header">
        <td colspan="5">Provided by Jamaat</td>
      </tr>
      <?php 
        $uom_helper = function($item_name) {
          $name = strtolower($item_name);
          if (strpos($name, 'set') !== false || strpos($name, 'crocery') !== false || strpos($name, 'grocery') !== false) {
            return 'Set';
          }
          if (strpos($name, 'table') !== false && strpos($name, '5pc') !== false) {
            return 'Per 5 Nos';
          }
          if (strpos($name, 'table') !== false && strpos($name, '4pc') !== false) {
            return 'Per 4 nos';
          }
          if (strpos($name, 'extension') !== false || strpos($name, 'hour') !== false) {
            return 'Hour';
          }
          return 'Pc';
        };

        if (empty($items_jamaat)): 
      ?>
        <tr>
          <td colspan="5" class="text-muted text-center" style="font-style: italic;">No resources selected under this category.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($items_jamaat as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['item_name']) ?></td>
            <td class="text-center"><?= $item['qty'] ?></td>
            <td class="text-right">₹<?= number_format($item['rate'], 2) ?></td>
            <td class="text-center"><?= $uom_helper($item['item_name']) ?></td>
            <td class="text-right">₹<?= number_format($item['total'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      <tr class="total-row">
        <td colspan="4" class="text-right">Total - A</td>
        <td class="text-right">₹<?= number_format($total_a, 2) ?></td>
      </tr>

      <!-- SECTION B -->
      <tr class="section-header">
        <td colspan="5">Provided by Ladies Committee</td>
      </tr>
      <?php if (empty($items_ladies)): ?>
        <tr>
          <td colspan="5" class="text-muted text-center" style="font-style: italic;">No resources selected under this category.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($items_ladies as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['item_name']) ?></td>
            <td class="text-center"><?= $item['qty'] ?></td>
            <td class="text-right">₹<?= number_format($item['rate'], 2) ?></td>
            <td class="text-center"><?= $uom_helper($item['item_name']) ?></td>
            <td class="text-right">₹<?= number_format($item['total'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      <tr class="total-row">
        <td colspan="4" class="text-right">Total - B</td>
        <td class="text-right">₹<?= number_format($total_b, 2) ?></td>
      </tr>

      <!-- SECTION C -->
      <tr class="section-header">
        <td colspan="5">Extras</td>
      </tr>
      <?php if (empty($items_extras)): ?>
        <tr>
          <td colspan="5" class="text-muted text-center" style="font-style: italic;">No extras configured.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($items_extras as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['item_name']) ?></td>
            <td class="text-center"><?= $item['qty'] ?></td>
            <td class="text-right">₹<?= number_format($item['rate'], 2) ?></td>
            <td class="text-center"><?= $uom_helper($item['item_name']) ?></td>
            <td class="text-right">₹<?= number_format($item['total'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      <tr class="total-row">
        <td colspan="4" class="text-right">Total - C</td>
        <td class="text-right">₹<?= number_format($total_c, 2) ?></td>
      </tr>

      <!-- GRAND TOTAL -->
      <tr class="grand-total-row">
        <td colspan="4" class="text-right">Total A + B + C</td>
        <td class="text-right">₹<?= number_format($total_abc, 2) ?></td>
      </tr>

      <!-- PAYMENTS SUMMARY -->
      <tr>
        <td colspan="4" class="text-right">Deposit received <?= $deposit_cheque ? '(Cheque/Ref: ' . htmlspecialchars($deposit_cheque) . ')' : '' ?></td>
        <td class="text-right">₹<?= number_format($deposit_received, 2) ?></td>
      </tr>
      <tr>
        <td colspan="4" class="text-right">Rent Rcd <?= $rent_cheque ? '(Cheque/Ref: ' . htmlspecialchars($rent_cheque) . ')' : '' ?></td>
        <td class="text-right">₹<?= number_format($rent_received, 2) ?></td>
      </tr>

      <!-- REFUND SUMMARY -->
      <?php if ($deposit_to_be_refunded >= 0): ?>
        <tr class="refund-row">
          <td colspan="4" class="text-right">Deposit to be refunded</td>
          <td class="text-right">₹<?= number_format($deposit_to_be_refunded, 2) ?></td>
        </tr>
      <?php else: ?>
        <tr class="refund-row" style="background-color: #ffcccc;">
          <td colspan="4" class="text-right">Net Outstanding Due (Payable by Hirer)</td>
          <td class="text-right">₹<?= number_format(abs($deposit_to_be_refunded), 2) ?></td>
        </tr>
      <?php endif; ?>

    </tbody>
  </table>

  <table class="signatures-section">
    <tr>
      <td>
        <div class="signature-title">Mawaid In Charge</div>
      </td>
      <td>
        <div class="signature-title">Office in Charge</div>
      </td>
      <td>
        <div class="signature-title">Signature of the Hirer</div>
      </td>
      <td>
        <div class="signature-title">Date</div>
      </td>
    </tr>
  </table>

</body>
</html>
