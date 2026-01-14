<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifications extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    // Keep constructor lightweight to avoid early DB connections when CI env or env vars
    // are not yet available to the runtime (cron/web differences). Models and
    // libraries are loaded lazily inside methods after the `is_cli()` guard.
  }

  public function index()
  {
    echo "Notifications controller. Use the 'process' method from CLI.\n";
  }

  /**
   * Process pending notifications (CLI-only)
   * Usage: php index.php notifications process [limit]
   */
  public function process($limit = 100)
  {
    if (!is_cli()) {
      echo "This endpoint may only be run from CLI." . PHP_EOL;
      return;
    }

    echo "ENVIRONMENT=" . (defined('ENVIRONMENT') ? ENVIRONMENT : 'unknown') . PHP_EOL;

    // Lazy-load dependencies now that environment is confirmed (CLI).
    $this->load->model('NotificationM');
    $this->load->library('Notification_lib');
    $this->load->library('email');
    $this->load->model('CommonM');
    $this->load->helper('email_template');
    $this->config->load('notifications', TRUE);

    // Initialize email transport like EmailWorker so SMTP settings, newline/crlf, and mailtype are respected
    $this->config->load('email');
    $emailKeys = [
      'protocol',
      'smtp_host',
      'smtp_port',
      'smtp_user',
      'smtp_pass',
      'smtp_timeout',
      'smtp_keepalive',
      'smtp_crypto',
      'mailtype',
      'charset',
      'newline',
      'crlf',
      'wordwrap',
      'validate',
      'mailpath'
    ];
    $emailConfig = [];
    foreach ($emailKeys as $key) {
      $value = $this->config->item($key);
      if ($value !== null) $emailConfig[$key] = $value;
    }
    if (empty($emailConfig['newline'])) $emailConfig['newline'] = "\r\n";
    if (empty($emailConfig['crlf'])) $emailConfig['crlf'] = "\r\n";
    // avoid persistent SMTP connections inside cron runs
    $emailConfig['smtp_keepalive'] = false;
    $this->email->initialize($emailConfig);

    $limit = (int)$limit;
    echo "Processing up to {$limit} pending notifications...\n";
    $pending = $this->NotificationM->get_pending($limit);
    $count = count($pending);
    echo "Found {$count} pending items\n";

    foreach ($pending as $n) {
      $id = isset($n['id']) ? (int)$n['id'] : 0;
      $channel = isset($n['channel']) ? $n['channel'] : 'email';
      $recipient = isset($n['recipient']) ? $n['recipient'] : null;
      $subject = isset($n['subject']) ? $n['subject'] : null;
      $body = isset($n['body']) ? $n['body'] : null;

      echo "[{$id}] channel={$channel} recipient={$recipient}\n";

      try {
        if ($channel === 'email') {
          // Standardize all notification emails to the branded template (same look as Raza submission)
          // unless a full HTML document was already stored.
          if (is_string($body) && trim($body) !== '' && function_exists('email_body_is_full_document') && !email_body_is_full_document($body)) {
            $plainStart = strtolower(trim(preg_replace('/\s+/', ' ', strip_tags(substr($body, 0, 250)))));
            $hasGreeting = (strpos($plainStart, 'baad afzalus salaam') === 0) || (strpos($plainStart, 'baad afzalus salam') === 0);

            $body = render_generic_email_html([
              'title' => (string)($subject ?: 'Notification'),
              'todayDate' => date('l, j M Y, h:i:s A'),
              'greeting' => $hasGreeting ? '' : 'Baad Afzalus Salaam,',
              // Title already shows in the header; avoid duplicating it inside the card.
              'cardTitle' => '',
              'body' => $body,
              'auto_table' => true,
              'ctaUrl' => base_url('accounts'),
              'ctaText' => 'Login to your account',
            ]);
          }

          $this->email->clear(true);
          $from = $this->config->item('smtp_user') ?: 'no-reply@localhost';
          $this->email->from($from);
          $this->email->to($recipient);
          if (!empty($subject)) $this->email->subject($subject);
          $this->email->message($body);
          $ok = $this->email->send();
          // Always capture debugger output so we can inspect SMTP server responses (cron logs)
          $debug = $this->email->print_debugger(array('headers', 'subject'));
          if ($ok) {
            $this->NotificationM->mark_sent($id);
            echo "[{$id}] email sent\n";
            echo "DEBUG: " . PHP_EOL . $debug . PHP_EOL;
          } else {
            // One retry with alternate SMTP mode (some hosts require 587/TLS instead of 465/SSL or vice versa)
            $retryOk = false;
            $retryDebug = '';
            $currentPort = (string)($emailConfig['smtp_port'] ?? '');
            $currentCrypto = (string)($emailConfig['smtp_crypto'] ?? '');
            $currentHost = (string)($emailConfig['smtp_host'] ?? '');
            $looksLikeSsl465 = ($currentPort === '465') || (stripos($currentHost, 'ssl://') === 0) || (strtolower($currentCrypto) === 'ssl');

            $retryConfig = $emailConfig;
            if ($looksLikeSsl465) {
              // try STARTTLS on 587
              $retryConfig['smtp_host'] = preg_replace('#^ssl://#i', '', $currentHost);
              $retryConfig['smtp_port'] = '587';
              $retryConfig['smtp_crypto'] = 'tls';
            } else {
              // try implicit SSL on 465
              $retryConfig['smtp_host'] = preg_replace('#^ssl://#i', '', $currentHost);
              $retryConfig['smtp_port'] = '465';
              $retryConfig['smtp_crypto'] = 'ssl';
            }
            $retryConfig['smtp_keepalive'] = false;

            $this->email->clear(true);
            $this->email->initialize($retryConfig);
            $this->email->from($from);
            $this->email->to($recipient);
            if (!empty($subject)) $this->email->subject($subject);
            $this->email->message($body);
            $retryOk = $this->email->send();
            $retryDebug = $this->email->print_debugger(array('headers', 'subject'));

            if ($retryOk) {
              $this->NotificationM->mark_sent($id);
              echo "[{$id}] email sent (retry)\n";
              echo "DEBUG (first): " . PHP_EOL . $debug . PHP_EOL;
              echo "DEBUG (retry): " . PHP_EOL . $retryDebug . PHP_EOL;
            } else {
              // Fallback: try PHP mail() (server local MTA) like EmailWorker
              $fallbackOk = $this->php_mail_fallback($recipient, $subject, $body);
              if ($fallbackOk) {
                $this->NotificationM->mark_sent($id);
                echo "[{$id}] email sent (fallback mail())\n";
              } else {
                echo "[{$id}] email failed\n";
                echo "DEBUG (first): " . PHP_EOL . $debug . PHP_EOL;
                echo "DEBUG (retry): " . PHP_EOL . $retryDebug . PHP_EOL;
                $this->NotificationM->increment_attempts_and_fail($id);
              }
            }
          }
        } elseif ($channel === 'whatsapp') {
          // Placeholder: enqueue/record or integrate with WhatsApp API here
          // For now mark as sent (or you may implement proper integration)
          $this->NotificationM->mark_sent($id);
          echo "[{$id}] whatsapp marked sent (placeholder)\n";
        } elseif ($channel === 'sms') {
          // Placeholder for SMS provider integration
          $this->NotificationM->mark_sent($id);
          echo "[{$id}] sms marked sent (placeholder)\n";
        } else {
          echo "[{$id}] unknown channel: {$channel}\n";
          $this->NotificationM->increment_attempts_and_fail($id);
        }
      } catch (Exception $e) {
        $this->NotificationM->increment_attempts_and_fail($id);
        echo "[{$id}] exception: " . $e->getMessage() . "\n";
      }
    }

    echo "Done.\n";
  }

  /**
   * Send fallback using PHP mail() when SMTP fails.
   * Accepts $to (string or array)
   */
  private function php_mail_fallback($to, $subject, $message)
  {
    $tos = is_array($to) ? $to : [$to];
    $from = $this->config->item('smtp_user') ?: 'admin@' . ($_SERVER['HTTP_HOST'] ?? 'localhost');

    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';
    $headers[] = 'From: ' . $from;

    $hdr = implode("\r\n", $headers);
    $okAny = false;

    foreach ($tos as $t) {
      $t = is_string($t) ? trim($t) : '';
      if (empty($t)) continue;
      $ok = @mail($t, (string)$subject, (string)$message, $hdr);
      $okAny = $okAny || $ok;
      $line = "[" . date('Y-m-d H:i:s') . "] notifications mail() to={$t} status=" . ($ok ? 'sent' : 'failed') . "\n";
      @file_put_contents(APPPATH . 'logs/emailworker.log', $line, FILE_APPEND | LOCK_EX);
    }

    return $okAny;
  }

  /**
   * Enqueue scheduled reminders. Run from cron.
   * Usage: php index.php notifications schedule [job]
   * job: all|thaali_signup|thaali_feedback|sabeel_monthly|fmb_monthly|corpus_weekly
   */
  public function schedule($job = 'all')
  {
    if (!is_cli()) {
      echo "This endpoint may only be run from CLI." . PHP_EOL;
      return;
    }

    echo "ENVIRONMENT=" . (defined('ENVIRONMENT') ? ENVIRONMENT : 'unknown') . PHP_EOL;

    // Lazy-load models/libs after confirming CLI context
    $this->load->model('NotificationM');
    $this->load->library('Notification_lib');
    $this->load->model('CommonM');
    $this->config->load('notifications', TRUE);

    $job = strtolower(trim((string)$job));
    echo "Scheduling job: {$job}\n";

    if ($job === 'all' || $job === 'thaali_signup') {
      $this->schedule_daily_thaali_signup();
    }
    if ($job === 'all' || $job === 'thaali_feedback') {
      $this->schedule_daily_thaali_feedback();
    }
    if ($job === 'all' || $job === 'finances_monthly' || $job === 'sabeel_monthly' || $job === 'fmb_monthly') {
      $this->schedule_monthly_finances();
    }
    if ($job === 'all' || $job === 'corpus_weekly') {
      $this->schedule_weekly_corpus();
    }
    if ($job === 'all' || $job === 'appointments_digest') {
      $this->schedule_nightly_appointments_digest();
    }

    echo "Scheduling finished." . PHP_EOL;
  }

  protected function schedule_daily_thaali_signup()
  {
    $tpl = $this->config->item('tpl_thaali_signup', 'notifications');
    // Select active members who have NOT signed up for thaali in the upcoming week (Mon-Sat)
    // Compute upcoming week's Monday (next Monday) and Saturday
    $start = date('Y-m-d', strtotime('next monday'));
    $end = date('Y-m-d', strtotime($start . ' +5 days')); // Monday +5 = Saturday
    // Helpful log for cron output
    echo "Checking thaali signup for upcoming week: {$start} to {$end}\n";

    $sql = "SELECT u.ITS_ID, u.Full_Name,
              COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile,
              COALESCE(NULLIF(u.Email,''), NULLIF(u.email,''), '') AS email
            FROM user u
            WHERE u.Inactive_Status IS NULL
              AND COALESCE(u.Sector,'') <> ''
              AND COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') <> ''
              AND NOT EXISTS (
                SELECT 1 FROM fmb_weekly_signup fs
                WHERE fs.user_id = u.ITS_ID
                  AND fs.signup_date BETWEEN " . $this->db->escape($start) . " AND " . $this->db->escape($end) . "
                  AND fs.want_thali = 1
              )";
    $rows = $this->db->query($sql)->result_array();
    // Group by email to avoid duplicate sends
    $grouped = [];
    foreach ($rows as $r) {
      $email = strtolower(trim((string)($r['email'] ?? '')));
      if (!empty($email)) {
        if (!isset($grouped[$email])) $grouped[$email] = [];
        $grouped[$email][] = $r;
      }
    }
    $count = 0;
    foreach ($grouped as $email => $members) {
      // Send one email per group
      $this->notification_lib->send_email([
        'recipient' => $email,
        'recipient_type' => 'member',
        'subject' => 'Thaali signup reminder',
        'body' => $tpl,
        'card_title' => 'Thaali signup reminder'
      ]);
      $count++;
      // Send WhatsApp to each member in the group
      foreach ($members as $r) {
        $phone = preg_replace('/[^0-9+]/', '', $r['mobile']);
        if (!empty($phone)) {
          $this->notification_lib->send_whatsapp([
            'recipient' => $phone,
            'recipient_type' => 'member',
            'body' => $tpl
          ]);
        }
      }
    }
    echo "Thaali signup reminders queued for {$count} unique emails\n";
  }

  protected function schedule_daily_thaali_feedback()
  {
    $tpl = $this->config->item('tpl_thaali_feedback', 'notifications');
    // Send feedback reminder ONLY to members who have signed up for thaali today.
    $sql = "SELECT DISTINCT u.ITS_ID, u.Full_Name,
              COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile,
              COALESCE(NULLIF(u.Email,''), NULLIF(u.email,''), '') AS email
            FROM fmb_weekly_signup fs
            JOIN user u ON u.ITS_ID = fs.user_id
            WHERE fs.signup_date = CURDATE()
              AND fs.want_thali = 1
              AND u.Inactive_Status IS NULL
              AND COALESCE(u.Sector,'') <> ''
              AND COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') <> ''";
    $rows = $this->db->query($sql)->result_array();

    // Group by email to avoid duplicate emails; members without email will get WhatsApp only
    $grouped = [];
    $count = 0;
    foreach ($rows as $r) {
      $email = strtolower(trim((string)($r['email'] ?? '')));
      if ($email !== '') {
        if (!isset($grouped[$email])) $grouped[$email] = [];
        $grouped[$email][] = $r;
      } else {
        // No email: send whatsapp directly per member
        $phone = preg_replace('/[^0-9+]/', '', $r['mobile']);
        if (empty($phone)) continue;
        $this->notification_lib->send_whatsapp([
          'recipient' => $phone,
          'recipient_type' => 'member',
          'body' => $tpl
        ]);
        $count++;
      }
    }

    // Send one email per unique address and WhatsApp to members in that group
    foreach ($grouped as $email => $members) {
      $this->notification_lib->send_email([
        'recipient' => $email,
        'recipient_type' => 'member',
        'subject' => 'Thaali feedback reminder',
        'body' => $tpl,
        'card_title' => 'Thaali feedback reminder'
      ]);
      $count++;

      foreach ($members as $r) {
        $phone = preg_replace('/[^0-9+]/', '', $r['mobile']);
        if (empty($phone)) continue;
        $this->notification_lib->send_whatsapp([
          'recipient' => $phone,
          'recipient_type' => 'member',
          'body' => $tpl
        ]);
      }
    }

    echo "Thaali feedback reminders queued for {$count} recipients\n";
  }

  protected function schedule_monthly_sabeel()
  {
    // Backwards compatible entrypoint — consolidated into schedule_monthly_finances()
    $this->schedule_monthly_finances();
  }

  protected function schedule_monthly_fmb()
  {
    // Backwards compatible entrypoint — consolidated into schedule_monthly_finances()
    $this->schedule_monthly_finances();
  }

  /**
   * Consolidated monthly finance reminder.
   * Sends a single email per member that includes:
   * - FMB Takhmeen due
   * - Sabeel Takhmeen due
   * - Miqaat invoice dues
   * - Extra contribution (FMB General Contributions) due
   */
  protected function schedule_monthly_finances()
  {
    $this->load->model('AccountM');

    $subject = (string)$this->config->item('tpl_finance_dues_subject', 'notifications');
    if ($subject === '') $subject = 'Finance Dues Summary';
    $intro = (string)$this->config->item('tpl_finance_dues_intro', 'notifications');
    $footer = (string)$this->config->item('tpl_finance_dues_footer', 'notifications');

    // Avoid sending duplicates if multiple cron jobs invoke this on the same day.
    $existing = $this->db->select('recipient')
      ->from('notifications')
      ->where('channel', 'email')
      ->where('subject', $subject)
      ->where('DATE(created_at) = CURDATE()', null, false)
      ->get()
      ->result_array();
    $already = [];
    foreach ($existing as $e) {
      $rcpt = isset($e['recipient']) ? trim((string)$e['recipient']) : '';
      if ($rcpt !== '') $already[strtolower($rcpt)] = true;
    }

    // Build family-wise recipient list, preferring HOF email (or any valid family email).
    $sql = "SELECT ITS_ID, HOF_ID, HOF_FM_TYPE, Full_Name,
              COALESCE(NULLIF(Email,''), NULLIF(email,''), '') AS email
            FROM user
            WHERE Inactive_Status IS NULL AND COALESCE(Sector,'') <> ''";
    $rows = $this->db->query($sql)->result_array();

    $families = []; // familyId => ['its'=>familyId,'name'=>...,'email'=>...]
    foreach ($rows as $r) {
      $its = (int)($r['ITS_ID'] ?? 0);
      if ($its <= 0) continue;
      $hof = (int)($r['HOF_ID'] ?? 0);
      $familyId = ($hof > 0) ? $hof : $its;

      $email = trim((string)($r['email'] ?? ''));
      $emailOk = (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL));
      $name = trim((string)($r['Full_Name'] ?? ''));
      $isHofRow = ((string)($r['HOF_FM_TYPE'] ?? '') === 'HOF') || ($its === $familyId);

      if (!isset($families[$familyId])) {
        $families[$familyId] = ['its' => $familyId, 'name' => $name, 'email' => ($emailOk ? $email : '')];
      }

      // Prefer HOF email/name when available; otherwise fill missing email.
      if ($isHofRow) {
        if ($name !== '') $families[$familyId]['name'] = $name;
        if ($emailOk) $families[$familyId]['email'] = $email;
      } else {
        if ($families[$familyId]['email'] === '' && $emailOk) $families[$familyId]['email'] = $email;
      }
    }

    $count = 0;
    $sentEmails = [];

    foreach ($families as $familyId => $fam) {
      $familyId = (int)$familyId;
      if ($familyId <= 0) continue;

      $email = trim((string)($fam['email'] ?? ''));
      if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

      $emailKey = strtolower($email);
      if (isset($already[$emailKey])) continue;
      if (isset($sentEmails[$emailKey])) continue;

      // Compute dues (family-wise for FMB & Sabeel; other dues remain HOF/family anchor based)
      $fmb = $this->AccountM->get_family_total_fmb_due_upto_current_year($familyId);
      $fmbDue = is_array($fmb) ? (float)($fmb['total_due'] ?? 0) : 0.0;

      $sabeel = $this->AccountM->get_family_total_sabeel_due_upto_current_year($familyId);
      $sabeelDue = is_array($sabeel) ? (float)($sabeel['total_due'] ?? 0) : 0.0;

      $miqInvoices = $this->AccountM->get_user_miqaat_invoices($familyId);
      $miqDue = 0.0;
      if (is_array($miqInvoices)) {
        foreach ($miqInvoices as $inv) {
          $miqDue += (float)($inv['due_amount'] ?? 0);
        }
      }

      $gcDue = (float)$this->AccountM->get_member_total_general_contrib_due($familyId);

      $total = $fmbDue + $sabeelDue + $miqDue + $gcDue;

      $fmt = function ($n) {
        return number_format((float)$n, 2);
      };

      $name = trim((string)($fam['name'] ?? ''));
      $lines = [];
      $lines[] = ($intro !== '' ? $intro : 'This is a consolidated reminder of your current outstanding dues:');
      $lines[] = '';
      $lines[] = 'FMB Takhmeen Due: ₹' . $fmt($fmbDue);
      $lines[] = 'Sabeel Takhmeen Due: ₹' . $fmt($sabeelDue);
      $lines[] = 'Miqaat Invoice Due: ₹' . $fmt($miqDue);
      $lines[] = 'Extra Contribution Due: ₹' . $fmt($gcDue);
      $lines[] = '----------------------------------------';
      $lines[] = 'Total Due: ₹' . $fmt($total);
      $lines[] = '';
      $lines[] = ($footer !== '' ? $footer : 'You may review details and make payments using the links below.');
      $lines[] = 'FMB & Extra Contributions: accounts/viewfmbtakhmeen';
      $lines[] = 'Sabeel: accounts/ViewSabeelTakhmeen';
      $lines[] = 'Miqaat Invoices: accounts/assigned_miqaats';

      $body = implode("\n", $lines);

      $this->notification_lib->send_email([
        'recipient' => $email,
        'recipient_type' => 'member',
        'subject' => $subject,
        'body' => $body,
        'recipient_name' => $name,
        'recipient_its' => (string)$familyId,
        'card_title' => 'Finance Dues Summary'
      ]);
      $count++;
      $sentEmails[$emailKey] = true;
    }

    echo "Finances monthly reminders queued for {$count} families\n";
  }

  protected function schedule_weekly_corpus()
  {
    $tpl = $this->config->item('tpl_corpus_fund', 'notifications');
    $sql = "SELECT DISTINCT HOF_ID, COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') AS mobile, COALESCE(NULLIF(Email,''), NULLIF(email,''), '') AS email FROM user WHERE HOF_FM_TYPE = 'HOF' AND Inactive_Status IS NULL AND COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') <> ''";
    $rows = $this->db->query($sql)->result_array();
    $count = 0;
    $sentEmails = [];
    foreach ($rows as $r) {
      $hofId = isset($r['HOF_ID']) ? (int)$r['HOF_ID'] : 0;
      if ($hofId <= 0) continue;

      // Compute outstanding corpus details for this family (HOF)
      $assignments = $this->db->select('a.fund_id, f.title, COALESCE(SUM(a.amount_assigned),0) AS assigned_total', false)
        ->from('corpus_fund_assignment a')
        ->join('corpus_fund f', 'f.id = a.fund_id', 'inner')
        ->where('a.hof_id', $hofId)
        ->group_by(['a.fund_id', 'f.title'])
        ->order_by('a.fund_id', 'ASC')
        ->get()->result_array();

      $payments = $this->db->select('fund_id, COALESCE(SUM(amount_paid),0) AS paid_total', false)
        ->from('corpus_fund_payment')
        ->where('hof_id', $hofId)
        ->group_by('fund_id')
        ->get()->result_array();

      $paidByFund = [];
      foreach ($payments as $p) {
        $fundId = isset($p['fund_id']) ? (int)$p['fund_id'] : 0;
        if ($fundId <= 0) continue;
        $paidByFund[$fundId] = (float)($p['paid_total'] ?? 0);
      }

      $fmt = function ($n) {
        return number_format((float)$n, 2);
      };

      $totalAssigned = 0.0;
      $totalPaid = 0.0;
      $totalDue = 0.0;
      $fundLines = [];

      foreach ($assignments as $a) {
        $fundId = isset($a['fund_id']) ? (int)$a['fund_id'] : 0;
        if ($fundId <= 0) continue;

        $title = trim((string)($a['title'] ?? ''));
        $assigned = (float)($a['assigned_total'] ?? 0);
        $paid = (float)($paidByFund[$fundId] ?? 0);
        $due = max(0.0, $assigned - $paid);

        $totalAssigned += $assigned;
        $totalPaid += $paid;
        $totalDue += $due;

        if ($due > 0) {
          $label = ($title !== '' ? $title : ('Fund #' . $fundId));
          $fundLines[] = $label . ': Due ₹' . $fmt($due) . ' (Assigned ₹' . $fmt($assigned) . ', Paid ₹' . $fmt($paid) . ')';
        }
      }

      $lines = [];
      $lines[] = trim((string)$tpl);
      $lines[] = '';
      if ($totalDue > 0) {
        $lines[] = 'Total Corpus Fund Due: ₹' . $fmt($totalDue);
        if (!empty($fundLines)) {
          $lines[] = '';
          $lines[] = 'Fund-wise details:';
          foreach ($fundLines as $l) {
            $lines[] = '- ' . $l;
          }
        }
      } else {
        $lines[] = 'No pending Corpus Fund dues.';
      }

      $lines[] = '';
      $lines[] = 'View details: accounts/corpusfunds';

      $body = implode("\n", array_filter($lines, function ($v) {
        return $v !== null;
      }));

      $phone = preg_replace('/[^0-9+]/', '', $r['mobile']);
      if (empty($phone)) continue;
      $this->notification_lib->send_whatsapp([
        'recipient' => $phone,
        'recipient_type' => 'hof',
        'body' => $body
      ]);
      $email = trim((string)($r['email'] ?? ''));
      if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailKey = strtolower($email);
        if (isset($sentEmails[$emailKey])) {
          $count++;
          continue;
        }
        $this->notification_lib->send_email([
          'recipient' => $email,
          'recipient_type' => 'hof',
          'subject' => 'Corpus fund reminder',
          'body' => $body,
          'card_title' => 'Corpus fund reminder'
        ]);
        $sentEmails[$emailKey] = true;
      }
      $count++;
    }
    echo "Corpus weekly reminders queued for {$count} HOFs\n";
  }

  /**
   * Nightly scheduled appointments digest for Amil Saheb.
   * Intended to be invoked by cron at 22:00 (10pm).
   * Usage: php index.php notifications schedule appointments_digest
   */
  protected function schedule_nightly_appointments_digest()
  {
    $this->load->model('AmilsahebM');

    $recipients = $this->config->item('amilsaheb_appointments_digest_recipients', 'notifications');
    if (empty($recipients) || !is_array($recipients)) {
      $recipients = ['kharamilsaheb@gmail.com'];
    }
    $subject = (string)$this->config->item('tpl_appointments_digest_subject', 'notifications');
    if ($subject === '') $subject = 'Scheduled Appointments Summary';

    // Digest covers next day's appointments (tomorrow) that are not attended.
    // Table: appointments (has purpose/other_details), slots (date/time), user (member details)
    $rows = $this->db->select('a.id, a.its, a.status, a.purpose, a.other_details, s.date, s.time, u.Full_Name, u.Mobile')
      ->from('appointments a')
      ->join('slots s', 'a.slot_id = s.slot_id')
      ->join('user u', 'a.its = u.ITS_ID', 'left')
      ->where('s.active', 1)
      ->where('s.date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)', null, false)
      ->order_by('s.date', 'ASC')
      ->order_by('s.time', 'ASC')
      ->get()
      ->result_array();

    $countAppointments = count($rows);
    $tomorrow = date('d M Y', strtotime('+1 day'));

    $body = '<p style="font-family:Arial,Helvetica,sans-serif;">
Below is the list of scheduled appointments for tomorrow (' . $tomorrow . '):
</p>';

    if ($countAppointments === 0) {

      $body .= '<p><strong>No upcoming pending appointments found.</strong></p>';
    } else {

      $body .= '
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;font-family:Arial,Helvetica,sans-serif;font-size:14px;">
    <thead>
      <tr style="background:#f2f2f2;">
        <th align="left" style="padding:8px;border:1px solid #ddd;">Date</th>
        <th align="left" style="padding:8px;border:1px solid #ddd;">Time</th>
        <th align="left" style="padding:8px;border:1px solid #ddd;">Member</th>
        <th align="left" style="padding:8px;border:1px solid #ddd;">ITS</th>
        <th align="left" style="padding:8px;border:1px solid #ddd;">Mobile</th>
        <th align="left" style="padding:8px;border:1px solid #ddd;">Purpose</th>
        <th align="left" style="padding:8px;border:1px solid #ddd;">Details</th>
      </tr>
    </thead>
    <tbody>
  ';

      foreach ($rows as $r) {

        $date    = !empty($r['date']) ? date('D, d M Y', strtotime($r['date'])) : '';
        $time    = $r['time'] ?? '';
        $its     = $r['its'] ?? '';
        $name    = trim((string)($r['Full_Name'] ?? 'Member'));
        $mobile  = trim((string)($r['Mobile'] ?? ''));
        $purpose = trim((string)($r['purpose'] ?? ''));
        $details = trim((string)($r['other_details'] ?? ''));

        $body .= '
    <tr>
      <td style="padding:8px;border:1px solid #ddd;" data-label="Date"><strong>' . htmlspecialchars($date) . '</strong></td>
      <td style="padding:8px;border:1px solid #ddd;" data-label="Time">' . htmlspecialchars($time) . '</td>
      <td style="padding:8px;border:1px solid #ddd;" data-label="Member">' . htmlspecialchars($name) . '</td>
      <td style="padding:8px;border:1px solid #ddd;" data-label="ITS">' . htmlspecialchars($its) . '</td>
      <td style="padding:8px;border:1px solid #ddd;" data-label="Mobile">' . htmlspecialchars($mobile) . '</td>
      <td style="padding:8px;border:1px solid #ddd;" data-label="Purpose">' . htmlspecialchars($purpose) . '</td>
      <td style="padding:8px;border:1px solid #ddd;" data-label="Details">' . htmlspecialchars($details) . '</td>
    </tr>
    ';
      }

      $body .= '
          </tbody>
        </table>
        ';
    }

    $body .= '
      <br>
      <p>
        <a href="' . base_url('amilsaheb/manage_appointment') . '" 
          style="display:inline-block;padding:10px 16px;background:#0d6efd;color:#ffffff;text-decoration:none;border-radius:4px;">
          Manage appointments
        </a>
      </p>
    ';

    // Duplicate protection: one digest per recipient per day
    foreach ($recipients as $to) {
      $to = trim((string)$to);
      if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) continue;

      $exists = $this->db->select('id')
        ->from('notifications')
        ->where('channel', 'email')
        ->where('recipient', $to)
        ->where('subject', $subject)
        ->where('DATE(created_at) = CURDATE()', null, false)
        ->limit(1)
        ->get()->row_array();
      if (!empty($exists)) continue;

      $this->notification_lib->send_email([
        'recipient' => $to,
        'recipient_type' => 'amil',
        'subject' => $subject,
        'body' => $body,
        'card_title' => 'Scheduled Appointments Summary',
        'disable_template' => false
      ]);
    }

    echo "Appointments digest queued ({$countAppointments} appointments)\n";
  }
}
