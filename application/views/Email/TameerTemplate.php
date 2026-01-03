<!DOCTYPE html>
<html>

<body style="font-family: Arial, Helvetica, sans-serif; line-height: 1.6; color:#333;">

  <p>Salaam e Jameel,</p>

  <p><strong><?php echo htmlspecialchars($name); ?></strong>,</p>

  <p>We hope this message finds you in good health and high spirits.</p>

  <p>
    This is a gentle reminder regarding your pending Jamaat contributions.
    Below are the details as per our records:
  </p>

  <table width="100%" cellpadding="6" cellspacing="0"
    style="border-collapse: collapse; margin-top:10px;">
    <thead>
      <tr style="background:#f5f5f5;">
        <th align="left" style="border:1px solid #ddd;">Category</th>
        <th align="right" style="border:1px solid #ddd;">Pending (₹)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="border:1px solid #ddd;">Sabeel Takhmeen</td>
        <td align="right" style="border:1px solid #ddd;"><?php echo $sabeel_pending; ?></td>
      </tr>
      <tr>
        <td style="border:1px solid #ddd;">FMB Takhmeen</td>
        <td align="right" style="border:1px solid #ddd;"><?php echo $fmb_pending; ?></td>
      </tr>
      <tr>
        <td style="border:1px solid #ddd;">Corpus Fund</td>
        <td align="right" style="border:1px solid #ddd;"><?php echo $corpus_pending; ?></td>
      </tr>
      <tr>
        <td style="border:1px solid #ddd;">Miqaat Niyaz Invoices</td>
        <td align="right" style="border:1px solid #ddd;"><?php echo $miqaat_pending; ?></td>
      </tr>
      <tr>
        <td style="border:1px solid #ddd;">Extra Contributions</td>
        <td align="right" style="border:1px solid #ddd;"><?php echo $extra_pending; ?></td>
      </tr>
      <tr style="background:#fafafa; font-weight:bold;">
        <td style="border:1px solid #ddd;">Total</td>
        <td align="right" style="border:1px solid #ddd;"><?php echo $total_pending; ?></td>
      </tr>
    </tbody>
  </table>

  <p style="margin-top:15px;">
    We humbly request you to kindly clear the pending amount at your earliest convenience.
    Your timely contribution plays an important role in supporting the Jamaat’s initiatives.
  </p>

  <p>
    You may make the payment through <em>bank transfer / cheque / online link / office visit</em>.
  </p>

  <p>
    If there is any discrepancy, please feel free to contact the Jamaat office.
  </p>

  <p>
    Shukran,<br>
    <strong>Anjuman e Saifee Jamaat Khar</strong>
  </p>

</body>

</html>