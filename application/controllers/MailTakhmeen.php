<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MailTakhmeen extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('email');
  }

  /**
   * Send reminder emails one-by-one for members with pending dues.
   * Accessible via web or CLI: /index.php/mailtakhmeen/send
   */
  public function send()
  {
    // Allow long run
    set_time_limit(0);

    // Query: fetch user contact + dues. Sabeel excludes the latest year only.
    $sql = "SELECT
      u.ITS_ID AS its_id,
      COALESCE(u.Full_Name, CONCAT(COALESCE(u.First_Name,''),' ',COALESCE(u.Surname,''))) AS customer_name,
      COALESCE(NULLIF(u.Email,''), NULLIF(u.Email,''), NULLIF(u.email,''), '') AS email,
      COALESCE(sabeel.sabeel_due,0.00) AS sabeel_due,
      COALESCE(fmb.fmb_due,0.00) AS fmb_due,
      COALESCE(corpus.corpus_due,0.00) AS corpus_due,
      COALESCE(miqaat.miqaat_due,0.00) AS miqaat_due,
      COALESCE(extra.extra_due,0.00) AS extra_contri_due
    FROM `user` u
    LEFT JOIN (
      SELECT st.user_id,
             COALESCE(SUM(COALESCE(est.amount,0) + COALESCE(res.yearly_amount,0)),0) - COALESCE(paid.total_paid,0) AS sabeel_due
      FROM sabeel_takhmeen st
      LEFT JOIN sabeel_takhmeen_grade est ON est.id = st.establishment_grade
      LEFT JOIN sabeel_takhmeen_grade res ON res.id = st.residential_grade
      LEFT JOIN (SELECT user_id, SUM(amount) AS total_paid FROM sabeel_takhmeen_payments GROUP BY user_id) paid ON paid.user_id = st.user_id
      WHERE st.year IS NULL OR CAST(SUBSTRING_INDEX(st.year,'-',1) AS UNSIGNED) <> (SELECT MAX(CAST(SUBSTRING_INDEX(year,'-',1) AS UNSIGNED)) FROM sabeel_takhmeen)
      GROUP BY st.user_id
    ) sabeel ON sabeel.user_id = u.ITS_ID
    LEFT JOIN (
      SELECT ft.user_id,
             COALESCE(SUM(ft.total_amount),0) - COALESCE(paid.total_paid,0) AS fmb_due
      FROM fmb_takhmeen ft
      LEFT JOIN (SELECT user_id, SUM(amount) AS total_paid FROM fmb_takhmeen_payments GROUP BY user_id) paid ON paid.user_id = ft.user_id
      GROUP BY ft.user_id
    ) fmb ON fmb.user_id = u.ITS_ID
    LEFT JOIN (
      SELECT a.hof_id,
             COALESCE(SUM(a.amount_assigned),0) - COALESCE(SUM(p.amount_paid),0) AS corpus_due,
             u.ITS_ID AS its_id
      FROM corpus_fund_assignment a
      LEFT JOIN corpus_fund_payment p ON p.fund_id = a.fund_id AND p.hof_id = a.hof_id
      LEFT JOIN `user` u ON u.HOF_ID = a.hof_id AND u.HOF_FM_TYPE = 'HOF'
      GROUP BY a.hof_id, u.ITS_ID
    ) corpus ON corpus.its_id = u.ITS_ID
    LEFT JOIN (
      SELECT i.user_id,
             COALESCE(SUM(GREATEST(i.amount - COALESCE(p.paid,0),0)),0) AS miqaat_due
      FROM miqaat_invoice i
      LEFT JOIN (SELECT miqaat_invoice_id, SUM(amount) AS paid FROM miqaat_payment GROUP BY miqaat_invoice_id) p ON p.miqaat_invoice_id = i.id
      GROUP BY i.user_id
    ) miqaat ON miqaat.user_id = u.ITS_ID
    LEFT JOIN (
      SELECT gc.user_id,
             COALESCE(SUM(gc.amount - COALESCE(p.total_received,0)),0) AS extra_due
      FROM fmb_general_contribution gc
      LEFT JOIN (SELECT fmbgc_id, SUM(amount) AS total_received FROM fmb_general_contribution_payments GROUP BY fmbgc_id) p ON p.fmbgc_id = gc.id
      GROUP BY gc.user_id
    ) extra ON extra.user_id = u.ITS_ID
    WHERE u.HOF_FM_TYPE = 'HOF'
      AND (u.Inactive_Status IS NULL OR u.Inactive_Status = 0)
    ORDER BY u.Full_Name ASC, u.ITS_ID ASC";

    $rows = $this->db->query($sql)->result_array();

    // Ensure mail log table exists (simple schema)
    $this->db->query("CREATE TABLE IF NOT EXISTS mail_takhmeen_log (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      its_id VARCHAR(50) NULL,
      email VARCHAR(200) NULL,
      status VARCHAR(20) NULL,
      error_text TEXT NULL,
      sent_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id),
      KEY idx_its_id (its_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    $sent = 0;
    $skipped = 0;
    $errors = 0;

    foreach ($rows as $r) {
      $email = trim((string)($r['email'] ?? ''));
      $name = trim($r['customer_name']);
      $sabeel = (float) ($r['sabeel_due'] ?? 0);
      $fmb    = (float) ($r['fmb_due'] ?? 0);
      $corpus = (float) ($r['corpus_due'] ?? 0);
      $miqaat = (float) ($r['miqaat_due'] ?? 0);
      $extra  = (float) ($r['extra_contri_due'] ?? 0);

      $total = $sabeel + $fmb + $corpus + $miqaat + $extra;

      // Basic safety checks
      if (empty($email) || $total <= 0) {
        // record skipped
        $this->db->insert('mail_takhmeen_log', [
          'its_id' => $r['its_id'] ?? null,
          'email' => $email ?: null,
          'status' => 'SKIPPED',
          'error_text' => $total <= 0 ? 'Zero total' : 'Missing email'
        ]);
        $skipped++;
        continue;
      }

      // Prepare template data
      // Format amounts using simple grouping and 2 decimals
      $fmt = function ($v) { return number_format((float)$v, 2, '.', ','); };
      $data = [
        'name' => $name,
        'sabeel_pending' => $fmt($sabeel),
        'fmb_pending' => $fmt($fmb),
        'corpus_pending' => $fmt($corpus),
        'miqaat_pending' => $fmt($miqaat),
        'extra_pending' => $fmt($extra),
        'total_pending' => $fmt($total),
      ];

      // Render HTML body using view
      $htmlBody = $this->load->view('Email/TameerTemplate', $data, true);

      // Send email to actual member address
      $this->email->clear(true);
      $this->email->from('anjuman@kharjamaat.in', 'Anjuman e Saifee Jamaat Khar');
      $this->email->to($email);
      $this->email->subject('Gentle Reminder: Pending Jamaat Contributions');
      $this->email->message($htmlBody);
      $this->email->set_mailtype('html');

      $sent_status = 'FAILED';
      $error_text = null;
      if ($this->email->send()) {
        $sent++;
        $sent_status = 'SENT';
      } else {
        $errors++;
        $error_text = $this->email->print_debugger(['headers']);
        log_message('error', 'MailTakhkeen send failed to ' . $email . ' Error: ' . $error_text);
      }

      // Persist mail attempt
      $this->db->insert('mail_takhmeen_log', [
        'its_id' => $r['its_id'] ?? null,
        'email' => $email,
        'status' => $sent_status,
        'error_text' => $error_text
      ]);

      // Short pause to reduce chance of rate limiting
      if (php_sapi_name() === 'cli') {
        sleep(1);
      } else {
        usleep(300000); // 0.3s
      }
    }

    // Output summary
    $out = "Mails processed: total=" . count($rows) . ", sent={$sent}, skipped={$skipped}, errors={$errors}";
    if (php_sapi_name() === 'cli') {
      echo $out . PHP_EOL;
    } else {
      echo '<pre>' . htmlspecialchars($out) . '</pre>';
    }
  }
}
