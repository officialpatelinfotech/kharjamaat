<!DOCTYPE html>
<html>

<head>
  <style>
    @page {
      margin: 10px;
    }

    body {
      margin: 10px;
    }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 14px;
      background-color: #fff7e6;
      margin: 0;
      padding: 20px;
    }

    .header {
      text-align: center;
    }

    .section {
      margin-top: 10px;
      font-size: 19px;
    }

    .label {
      font-weight: bold;
    }

    .rtl {
      direction: rtl;
      font-family: "Noto Naskh Arabic", "DejaVu Sans";
    }

    .text-center {
      text-align: center;
    }

    .text-dotted {
      text-decoration: dotted !important;
    }
    .text-underline {
      text-decoration: underline !important;
    }
    .label {
      text-decoration: none !important;
    }

    .m-0 {
      margin: 0;
    }

    .meta-table {
      width: 100%;
      border-collapse: collapse;
    }

    .meta-left {
      text-align: left;
      vertical-align: top;
    }

    .meta-right {
      text-align: right;
      vertical-align: top;
      white-space: nowrap;
    }
  </style>
</head>

<body>

  <div class="header">
    <h1>Anjuman-e-Saifee, Dawoodi Bohra Jamaat (Khar)</h1>
    <h3>Trust Regn No: E / 24158 (Mumbai)<br>Managed by: Anjuman-e-Saifee</h3>
  </div>

  <div class="section">
    <table class="meta-table">
      <tr>
        <td class="meta-left">
          <p class="text-underline m-0"><span class="label">تاريخ:</span> <?php echo isset($date) ? date("d-m-Y", strtotime($date)) : ""; ?></p>
          <p class="text-underline m-0"><span class="label">Payment For:</span> <?php echo isset($payment_for) ? htmlspecialchars((string) $payment_for) : ""; ?></p>
          <?php if (!empty($remarks)) { ?>
            <p class="text-underline m-0"><span class="label">Remarks:</span> <?php echo htmlspecialchars((string) $remarks); ?></p>
          <?php } ?>
        </td>
        <td class="meta-right rtl">
          <p class="text-underline m-0"><span class="label">رسيد نمبر:</span> <?php echo isset($receipt_no) ? htmlspecialchars((string) $receipt_no) : ""; ?></p>
        </td>
      </tr>
    </table>
    <br>
  </div>

  <div class="section">
    <p class="text-dotted m-0"><span class="label">(نام): </span><?php echo isset($name) ? $name : ""; ?></p><br>
    <!-- <span class="label">Sabil No:</span> <?php echo isset($sabil_no) ? $sabil_no : ""; ?><br>
    <span class="label">Jamaat No:</span> <?php echo isset($jamaat_no) ? $jamaat_no : ""; ?><br> -->
    <p class="text-underline"><span class="label">Address: </span> <?php echo isset($address) ? $address : ""; ?></p><br>
  </div>

  <div class="section rtl text-center">
    بعد السلام الجمیل
  </div>

  <div class="section">
    أب طرف سي روبية. . <?php echo isset($amount) ? $amount : ""; ?> (<?php echo isset($amount_words) ? $amount_words : ""; ?>)
  </div>

  <div class="section text-center" style="margin-top:30px;">
    <strong>Voluntary Contribution</strong><br>
    (وصول كرنار ني صحيح) عبد سيد ناطع
  </div>

</body>

</html>