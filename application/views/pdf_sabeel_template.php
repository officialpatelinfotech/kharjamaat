<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$isSabeel = (isset($for) && (int)$for === 4);
$isFmb    = (isset($for) && (int)$for === 3);
$isMiqaat = (isset($for) && (int)$for === 2);

$receipt_jamaat = htmlspecialchars(app_setting('receipt_jamaat_name', 'Anjuman-e-Saifee Dawoodi Bohra Jamaat, ' . ucfirst(jamaat_place())));
$receipt_trust  = htmlspecialchars(app_setting('trust_regn_no',       'E/24158 (Mumbai)'));
$receipt_mby    = htmlspecialchars(app_setting('managed_by',          'Anjuman-e-Saifee'));

$disp_date   = isset($date)         ? date('d-m-Y', strtotime($date)) : '';
$disp_name   = isset($name)         ? htmlspecialchars((string)$name)         : '';
$disp_its    = isset($its_id)       ? htmlspecialchars((string)$its_id)       : '';
$disp_addr   = isset($address)      ? htmlspecialchars((string)$address)      : '';
$disp_rno    = isset($receipt_no)   ? htmlspecialchars((string)$receipt_no)   : '';
$disp_amt    = isset($amount)       ? htmlspecialchars((string)$amount)       : '';
$disp_words  = isset($amount_words) ? htmlspecialchars((string)$amount_words) : '';
$disp_cheque = isset($cheque_no)    ? htmlspecialchars((string)$cheque_no)    : '';
$disp_bank   = isset($bank_name)    ? htmlspecialchars((string)$bank_name)    : '';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  @page { size: A4 landscape; margin: 15mm 12mm; }
  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 11px;
    color: #2c1a00;
    background: #f5ead6;
  }

  /* ── Outer border table ── */
  .page-wrap {
    width: 100%;
    border-collapse: collapse;
    border: 2.5px solid #7a5c1e;
    background: #fffdf6;
  }

  /* ═══ HEADER ═══ */
  .hdr-cell {
    background-color: #7a5010;
    text-align: center;
    padding: 10px 20px 9px;
    border-bottom: 2.5px solid #5a3a00;
  }
  .hdr-title {
    font-size: 17px;
    font-weight: bold;
    color: #fff8dc;
    letter-spacing: 2px;
    text-transform: uppercase;
  }
  .hdr-rule {
    border: none;
    border-top: 1px solid #c9a048;
    margin: 6px 60px 5px;
  }
  .hdr-org {
    font-size: 12px;
    font-style: italic;
    font-weight: bold;
    color: #f5d878;
  }
  .hdr-trust {
    font-size: 10px;
    color: #d4b060;
    margin-top: 2px;
  }

  /* ═══ META BAR (Receipt No / Date) ═══ */
  .meta-cell {
    background-color: #ede0b0;
    border-bottom: 1.5px solid #b89030;
    padding: 12px 18px;
  }
  .meta-table { width: 100%; border-collapse: collapse; }
  .meta-table td { vertical-align: middle; padding: 0; }
  .meta-lbl {
    font-size: 9px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #5a3a00;
    padding-right: 5px;
  }
  .meta-val {
    font-size: 13px;
    font-weight: bold;
    color: #1a0e00;
    border-bottom: 1.5px solid #7a5010;
    display: inline-block;
    min-width: 100px;
    padding-bottom: 1px;
  }

  /* ═══ BODY ═══ */
  .body-cell {
    background-color: #fffdf6;
    padding: 28px 26px 22px;
    vertical-align: top;
  }

  /* Info rows */
  .info-table { width: 100%; border-collapse: collapse; margin-bottom: 26px; }
  .info-table td { vertical-align: bottom; padding: 11px 4px; }
  .lbl {
    font-size: 9px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: #7a5010;
    white-space: nowrap;
    padding-right: 5px;
  }
  .fld {
    font-size: 12px;
    font-weight: bold;
    color: #1a0e00;
    border-bottom: 1px solid #7a5010;
    display: inline-block;
    min-width: 90px;
    padding-bottom: 1px;
    vertical-align: bottom;
  }

  /* Salutation */
  .salaam {
    font-size: 11px;
    font-style: italic;
    color: #4a3010;
    margin-bottom: 26px;
    padding-left: 2px;
  }

  /* Acknowledgement box */
  .ack-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 26px;
  }
  .ack-cell {
    background-color: #faf4de;
    border: 1px solid #c9a048;
    border-left: 4px solid #7a5010;
    padding: 20px 18px;
    font-size: 12px;
    line-height: 2.6;
    color: #1a0e00;
  }
  .hl {
    font-weight: bold;
    color: #3a2000;
    border-bottom: 1.5px solid #7a5010;
    display: inline-block;
    min-width: 80px;
    padding-bottom: 1px;
    vertical-align: bottom;
  }
  .hl-wide { min-width: 180px; }
  .hl-sm   { min-width: 65px; }

  /* Payment row */
  .pay { font-size: 12px; color: #1a0e00; line-height: 2.8; margin-bottom: 22px; }
  .pay .hl { font-size: 12px; }

  /* ═══ FOOTER ═══ */
  .ftr-cell {
    background-color: #5a3a00;
    border-top: 2px solid #9a7030;
    padding: 22px 22px;
  }
  .ftr-table { width: 100%; border-collapse: collapse; }
  .ftr-table td { vertical-align: bottom; padding: 0 4px; }
  .ftr-lbl {
    font-size: 9px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #d4b060;
    display: block;
    margin-bottom: 3px;
  }
  .ftr-val {
    font-size: 11px;
    font-weight: bold;
    color: #fff8dc;
  }
  .ftr-sig {
    display: inline-block;
    min-width: 150px;
    border-bottom: 1.5px solid #d4b060;
    height: 16px;
    margin-left: 6px;
    vertical-align: bottom;
  }
</style>
</head>
<body>

<?php if ($isSabeel): /* ==================== SABEEL ==================== */ ?>

<table class="page-wrap">

  <!-- HEADER -->
  <tr>
    <td class="hdr-cell">
      <div class="hdr-title">&diams;&nbsp; Sabilul Khair Wal Barakaat Receipt &nbsp;&diams;</div>
      <hr class="hdr-rule">
      <div class="hdr-org"><?php echo $receipt_jamaat; ?></div>
      <?php if ($receipt_trust): ?>
      <div class="hdr-trust">Regd. No: <?php echo $receipt_trust; ?></div>
      <?php endif; ?>
    </td>
  </tr>

  <!-- META BAR -->
  <tr>
    <td class="meta-cell">
      <table class="meta-table">
        <tr>
          <td style="width:50%;">
            <span class="meta-lbl">Receipt No.</span>
            <span class="meta-val"><?php echo $disp_rno; ?></span>
          </td>
          <td style="width:50%; text-align:right;">
            <span class="meta-lbl">Date</span>
            <span class="meta-val"><?php echo $disp_date; ?></span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- BODY -->
  <tr>
    <td class="body-cell">

      <!-- Name / ITS -->
      <table class="info-table">
        <tr>
          <td style="width:58%;">
            <span class="lbl">Name</span>
            <span class="fld" style="min-width:240px;"><?php echo $disp_name; ?></span>
          </td>
          <td style="width:42%;">
            <span class="lbl">ITS ID</span>
            <span class="fld" style="min-width:110px;"><?php echo $disp_its; ?></span>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="padding-top:2px;">
            <span class="lbl">Address</span>
            <span class="fld" style="min-width:360px;"><?php echo $disp_addr; ?></span>
          </td>
        </tr>
      </table>

      <div class="salaam">Baad al Salaam al-jameel,</div>

      <!-- Acknowledgement -->
      <table class="ack-table">
        <tr>
          <td class="ack-cell">
            It is hereby acknowledged that an amount of
            <strong>Rs.&nbsp;<span class="hl"><?php echo $disp_amt; ?></span></strong>
            &nbsp;(in words:&nbsp;<span class="hl hl-wide"><?php echo $disp_words; ?></span>&nbsp;)
            has been received from the above-mentioned person.
          </td>
        </tr>
      </table>

      <!-- Payment Details -->
      <div class="pay">
        By Cash&nbsp;/&nbsp;Draft&nbsp;/&nbsp;Cheque No.:&nbsp;<span class="hl"><?php echo $disp_cheque; ?></span>
        &nbsp;&nbsp;&nbsp;Dated:&nbsp;<span class="hl"><?php echo $disp_date; ?></span>
        &nbsp;&nbsp;&nbsp;Drawn on Bank:&nbsp;<span class="hl" style="min-width:130px;"><?php echo $disp_bank; ?></span>
      </div>
      <div class="pay">
        For Sabilul Khair wal Barakaat
      </div>

    </td>
  </tr>

  <!-- FOOTER -->
  <tr>
    <td class="ftr-cell">
      <table class="ftr-table">
        <tr>
          <td style="width:50%;">
            <span class="ftr-lbl">Receipt Issued By</span>
            <span class="ftr-val">&nbsp;</span><span class="ftr-sig"></span>
          </td>
          <td style="width:50%; text-align:right;">
            <span class="ftr-lbl" style="text-align:right;">Signature of Receiver</span>
            <span class="ftr-sig"></span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

</table>

<?php elseif ($isFmb): /* ==================== FMB ==================== */ ?>

<table class="page-wrap">

  <!-- HEADER -->
  <tr>
    <td class="hdr-cell">
      <div class="hdr-title">&diams;&nbsp; Voluntary Contribution &mdash; Jamaat Receipt &nbsp;&diams;</div>
      <hr class="hdr-rule">
      <div class="hdr-org"><?php echo $receipt_jamaat; ?></div>
      <?php if ($receipt_trust): ?>
      <div class="hdr-trust">Regd. No: <?php echo $receipt_trust; ?></div>
      <?php endif; ?>
    </td>
  </tr>

  <!-- META BAR -->
  <tr>
    <td class="meta-cell">
      <table class="meta-table">
        <tr>
          <td style="width:50%;">
            <span class="meta-lbl">Receipt No.</span>
            <span class="meta-val"><?php echo $disp_rno; ?></span>
          </td>
          <td style="width:50%; text-align:right;">
            <span class="meta-lbl">Date</span>
            <span class="meta-val"><?php echo $disp_date; ?></span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- BODY -->
  <tr>
    <td class="body-cell">

      <!-- Name / ITS -->
      <table class="info-table">
        <tr>
          <td style="width:58%;">
            <span class="lbl">Name</span>
            <span class="fld" style="min-width:240px;"><?php echo $disp_name; ?></span>
          </td>
          <td style="width:42%;">
            <span class="lbl">ITS ID</span>
            <span class="fld" style="min-width:110px;"><?php echo $disp_its; ?></span>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="padding-top:2px;">
            <span class="lbl">Address</span>
            <span class="fld" style="min-width:360px;"><?php echo $disp_addr; ?></span>
          </td>
        </tr>
      </table>

      <div class="salaam">Baad al Salaam el Jameel,</div>

      <!-- Acknowledgement -->
      <table class="ack-table">
        <tr>
          <td class="ack-cell">
            Received from the above-mentioned person a voluntary contribution of:&nbsp;
            Amount (in numbers):&nbsp;<span class="hl"><?php echo $disp_amt; ?></span>
            &nbsp;&nbsp;Amount (in words):&nbsp;<span class="hl hl-wide"><?php echo $disp_words; ?></span>
            &nbsp;&mdash; received as a Voluntary Contribution.
            <?php if (isset($payment_for) && $payment_for): ?>
            <br><br>
            <strong>Payment For:</strong> <?php echo htmlspecialchars((string)$payment_for); ?>
            <?php endif; ?>
          </td>
        </tr>
      </table>

      <!-- Payment Details -->
      <div class="pay">
        By Cash&nbsp;/&nbsp;Draft&nbsp;/&nbsp;Cheque No.:&nbsp;<span class="hl"><?php echo $disp_cheque; ?></span>
        &nbsp;&nbsp;&nbsp;Dated:&nbsp;<span class="hl"><?php echo $disp_date; ?></span>
        &nbsp;&nbsp;&nbsp;Drawn on Bank:&nbsp;<span class="hl" style="min-width:130px;"><?php echo $disp_bank; ?></span>
      </div>

    </td>
  </tr>

  <!-- FOOTER -->
  <tr>
    <td class="ftr-cell">
      <table class="ftr-table">
        <tr>
          <td style="text-align:center;">
            <span class="ftr-lbl" style="text-align:center;">Receiver's Signature</span>
            <span class="ftr-sig"></span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

</table>

<?php elseif ($isMiqaat): /* ==================== MIQAAT ==================== */ ?>

<table class="page-wrap">

  <!-- HEADER -->
  <tr>
    <td class="hdr-cell">
      <div class="hdr-title">&diams;&nbsp; MIQAAT NIYAZ &mdash; JAMAAT RECEIPT &nbsp;&diams;</div>
      <hr class="hdr-rule">
      <div class="hdr-org"><?php echo $receipt_jamaat; ?></div>
      <?php if ($receipt_trust): ?>
      <div class="hdr-trust">Regd. No: <?php echo $receipt_trust; ?></div>
      <?php endif; ?>
    </td>
  </tr>

  <!-- META BAR -->
  <tr>
    <td class="meta-cell">
      <table class="meta-table">
        <tr>
          <td style="width:50%;">
            <span class="meta-lbl">Receipt No.</span>
            <span class="meta-val"><?php echo $disp_rno; ?></span>
          </td>
          <td style="width:50%; text-align:right;">
            <span class="meta-lbl">Date</span>
            <span class="meta-val"><?php echo $disp_date; ?></span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- BODY -->
  <tr>
    <td class="body-cell">

      <!-- Name / ITS -->
      <table class="info-table">
        <tr>
          <td style="width:58%;">
            <span class="lbl">Name</span>
            <span class="fld" style="min-width:240px;"><?php echo $disp_name; ?></span>
          </td>
          <td style="width:42%;">
            <span class="lbl">ITS ID</span>
            <span class="fld" style="min-width:110px;"><?php echo $disp_its; ?></span>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="padding-top:2px;">
            <span class="lbl">Address</span>
            <span class="fld" style="min-width:360px;"><?php echo $disp_addr; ?></span>
          </td>
        </tr>
      </table>

      <div class="salaam">Baad al Salaam el Jameel,</div>

      <!-- Acknowledgement -->
      <table class="ack-table">
        <tr>
          <td class="ack-cell">
            Received from the above-mentioned person a Miqaat Niyaz contribution of:&nbsp;
            Amount (in numbers):&nbsp;<span class="hl"><?php echo $disp_amt; ?></span>
            &nbsp;&nbsp;Amount (in words):&nbsp;<span class="hl hl-wide"><?php echo $disp_words; ?></span>
            &nbsp;&mdash; received as a Miqaat Niyaz.
            <?php if (isset($payment_for) && $payment_for): ?>
            <br><br>
            <strong>Payment For:</strong> <?php echo htmlspecialchars((string)$payment_for); ?>
            <?php endif; ?>
          </td>
        </tr>
      </table>

      <!-- Payment Details -->
      <div class="pay">
        By Cash&nbsp;/&nbsp;Draft&nbsp;/&nbsp;Cheque No.:&nbsp;<span class="hl"><?php echo $disp_cheque; ?></span>
        &nbsp;&nbsp;&nbsp;Dated:&nbsp;<span class="hl"><?php echo $disp_date; ?></span>
        &nbsp;&nbsp;&nbsp;Drawn on Bank:&nbsp;<span class="hl" style="min-width:130px;"><?php echo $disp_bank; ?></span>
      </div>

    </td>
  </tr>

  <!-- FOOTER -->
  <tr>
    <td class="ftr-cell">
      <table class="ftr-table">
        <tr>
          <td style="text-align:center;">
            <span class="ftr-lbl" style="text-align:center;">Receiver's Signature</span>
            <span class="ftr-sig"></span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

</table>

<?php else: ?>
  <p style="font-size:13px; color:#2c1a00;">Receipt data unavailable.</p>
<?php endif; ?>

</body>
</html>
