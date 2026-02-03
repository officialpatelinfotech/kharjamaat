<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <style>
    /* A4 layout for Dompdf using pt units (more stable than mm to avoid rounding overflow/blank pages) */
    @page {
      size: 595.28pt 841.89pt;
      margin: 0;
    }

    html,
    body {
      margin: 0;
      padding: 0;
    }

    body {
      font-family: DejaVu Sans, Arial, sans-serif;
      color: #111;
    }

    .receipt {
      position: relative;
      /* Don't force exact A4 height; Dompdf may round and overflow to a border-only extra page */
      width: 595.28pt;
      overflow: hidden;
    }

    .frame {
      position: relative;
      box-sizing: border-box;
      /* Leave slack so the border never spills to the next page */
      width: 535pt;
      height: 760pt;
      margin: 30pt;
      border: 1.6pt solid #222;
      border-radius: 50pt;
    }

    .header1 {
      text-align: center;
      font-size: 22pt;
      font-weight: 700;
      font-style: italic;
      padding-top: 26pt;
    }

    .header2 {
      text-align: center;
      font-size: 20pt;
      font-weight: 700;
      font-style: italic;
      margin-top: 26pt;
    }

    .label {
      font-weight: 700;
    }

    .field {
      position: absolute;
      left: 50pt;
      right: 50pt;
      font-size: 14pt;
      font-weight: 700;
    }

    .field.name {
      top: 150pt;
    }

    .field.its {
      top: 180pt;
    }

    .field .val {
      font-weight: 400;
      margin-left: 18pt;
    }

    /* Three separate boxes (like screenshot) */
    .sbox {
      position: absolute;
      left: 50pt;
      /* centered */
      width: 340pt;
      height: 35pt;
      border: 1.6pt solid #222;
      box-sizing: border-box;
      padding: 12pt 16pt;
      font-size: 14pt;
      font-weight: 700;
      overflow: hidden;
    }

    .sbox.takhmeen {
      top: 250pt;
    }

    .sbox.paid {
      top: 330pt;
    }

    .sbox.balance {
      top: 410pt;
    }

    .sbox .amt { float: right; font-weight: 700; }

    .bigbox {
      position: absolute;
      left: 42pt;
      width: 459pt;
      top: 520pt;
      /* moved slightly up so it sits clearly below the balance box */
      height: 120pt;
      border: 1.6pt solid #222;
      border-radius: 28pt;
      box-sizing: border-box;
    }

    .sigline {
      position: absolute;
      left: 130pt;
      bottom: 36pt;
      width: 90pt;
      border-top: 1.6pt solid #222;
    }

    .arabic {
      position: absolute;
      right: 70pt;
      bottom: 28pt;
      font-size: 16pt;
      font-weight: 700;
      direction: rtl;
      unicode-bidi: bidi-override;
    }
  </style>
</head>

<body>
  <?php
  function fmt_int_inr($n)
  {
    $n = (int)round((float)$n);
    $neg = $n < 0;
    $n = abs($n);
    $str = (string)$n;
    $len = strlen($str);
    if ($len <= 3) return ($neg ? '-' : '') . $str;
    $last3 = substr($str, -3);
    $rest = substr($str, 0, $len - 3);
    $restRev = strrev($rest);
    $groups = str_split($restRev, 2);
    $restFormatted = strrev(implode(',', $groups));
    return ($neg ? '-' : '') . $restFormatted . ',' . $last3;
  }
  ?>

  <?php if (!empty($receipts)): ?>
    <?php $total = count($receipts);
    $idx = 0; ?>
    <?php foreach ($receipts as $r): $idx++; ?>
      <div class="receipt" style="page-break-after: <?php echo ($idx < $total) ? 'always' : 'auto'; ?>;">
        <div class="frame">
          <div class="header1"><?php echo htmlspecialchars($org_title ?? 'Anjuman e Saifee Khar'); ?></div>
          <div class="header2"><?php echo htmlspecialchars($fund_title ?? 'Masjid Tameer Funds Shehrullah 1447'); ?></div>

          <div class="field name">
            <span class="label">Name:</span>
            <span class="val"><?php echo htmlspecialchars($r['name'] ?? ''); ?></span>
          </div>

          <div class="field its">
            <span class="label">ITS No:</span>
            <span class="val"><?php echo htmlspecialchars($r['its_id'] ?? ''); ?></span>
          </div>

          <div class="sbox takhmeen">Takhmeen <span class="amt">₹<?php echo fmt_int_inr($r['assigned'] ?? 0); ?></span></div>
          <div class="sbox paid">Paid <span class="amt">₹<?php echo fmt_int_inr($r['paid'] ?? 0); ?></span></div>
          <div class="sbox balance">Balance <span class="amt">₹<?php echo fmt_int_inr($r['due'] ?? 0); ?></span></div>

          <div class="bigbox"></div>

          <div class="sigline"></div>
          <div class="arabic">عبد سيدان املفضال ط ع</div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="receipt">
      <div class="frame">
        <div class="header1">No receipts to generate.</div>
      </div>
    </div>
  <?php endif; ?>
</body>

</html>