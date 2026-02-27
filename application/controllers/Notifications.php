<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifications extends CI_Controller
{

  private function env_bool($key, $default = false)
  {
    $val = getenv($key);
    if (($val === false || $val === null || $val === '') && isset($_ENV[$key])) $val = $_ENV[$key];
    if (($val === false || $val === null || $val === '') && isset($_SERVER[$key])) $val = $_SERVER[$key];
    if ($val === false || $val === null || $val === '') return (bool)$default;
    $val = strtolower(trim((string)$val));
    return in_array($val, ['1', 'true', 'yes', 'y', 'on'], true);
  }

  private function allow_php_mail_fallback()
  {
    // Default OFF to avoid marking items sent when mail() returns true but delivery fails later.
    // Enable explicitly via env: EMAIL_ALLOW_PHP_MAIL_FALLBACK=1
    return $this->env_bool('EMAIL_ALLOW_PHP_MAIL_FALLBACK', false);
  }

  private function prefer_php_mail_primary()
  {
    // When enabled, attempt PHP mail() first, then try SMTP only if mail() fails.
    // Enable explicitly via env: EMAIL_PREFER_PHP_MAIL=1
    return $this->env_bool('EMAIL_PREFER_PHP_MAIL', false);
  }

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
   * Send a one-off test email (CLI-only) to verify SMTP and delivery.
   * Usage: php index.php notifications test_mail someone@example.com
   */
  public function test_mail($to = '')
  {
    if (!is_cli()) {
      echo "This endpoint may only be run from CLI." . PHP_EOL;
      return;
    }

    $to = trim((string)$to);
    if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
      echo "Usage: php index.php notifications test_mail someone@example.com" . PHP_EOL;
      return;
    }

    $this->load->library('email');
    $this->load->helper('email_template');
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
    $emailConfig['smtp_keepalive'] = false;
    $this->email->initialize($emailConfig);

    $proto = (string)($emailConfig['protocol'] ?? '');
    $host = (string)($emailConfig['smtp_host'] ?? '');
    $port = (string)($emailConfig['smtp_port'] ?? '');
    $crypto = (string)($emailConfig['smtp_crypto'] ?? '');
    $user = (string)($emailConfig['smtp_user'] ?? '');
    $fallbackEnabled = $this->allow_php_mail_fallback();

    echo "EMAIL CONFIG: protocol={$proto} host={$host} port={$port} crypto={$crypto} user={$user}" . PHP_EOL;
    echo "PHP_MAIL_FALLBACK: " . ($fallbackEnabled ? 'enabled' : 'disabled') . PHP_EOL;
    if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
      $pass = (string)($this->config->item('smtp_pass') ?? '');
      if ($pass === '' || $pass === 'admin@2024' || $pass === 'KharJamaat@2026') {
        echo "WARN: SMTP_PASS is missing or looks like a placeholder; check production .env" . PHP_EOL;
      }
    }

    $subject = 'Test Email - Khar Jamaat Notifications (' . date('Y-m-d H:i') . ')';
    $body = render_generic_email_html([
      'title' => $subject,
      'todayDate' => date('l, j M Y, h:i:s A'),
      'greeting' => 'Baad Afzalus Salaam,',
      'cardTitle' => 'Test delivery',
      'body' => '<p>This is a test email to verify SMTP delivery from the Notifications system.</p>'
        . '<p>If you received this, reminders will be able to deliver as well.</p>',
      'auto_table' => false,
      'ctaUrl' => base_url('accounts'),
      'ctaText' => 'Open site',
    ]);

    $from = $this->config->item('smtp_user') ?: 'no-reply@localhost';

    $this->email->clear(true);
    $this->email->from($from);
    $this->email->to($to);
    $messageId = '<test-' . date('YmdHis') . '-' . substr(md5($to), 0, 8) . '@kharjamaat.in>';
    $this->email->set_header('Message-ID', $messageId);
    $this->email->subject($subject);
    $this->email->message($body);

    $ok = $this->email->send();
    $debug = $this->email->print_debugger(['headers', 'subject']);

    if (!$ok) {
      // Retry with alternate SMTP mode (same logic as Notifications::process)
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
        // CI adds the 'ssl://' prefix internally when smtp_crypto === 'ssl'
        $retryConfig['smtp_host'] = preg_replace('#^ssl://#i', '', $currentHost);
        $retryConfig['smtp_port'] = '465';
        $retryConfig['smtp_crypto'] = 'ssl';
      }
      $retryConfig['smtp_keepalive'] = false;

      $this->email->clear(true);
      $this->email->initialize($retryConfig);
      $this->email->from($from);
      $this->email->to($to);
      $this->email->set_header('Message-ID', $messageId);
      $this->email->subject($subject . ' [retry]');
      $this->email->message($body);
      $retryOk = $this->email->send();
      $retryDebug = $this->email->print_debugger(['headers', 'subject']);

      if ($retryOk) {
        echo "OK: sent (retry)\n";
        echo "MESSAGE-ID: {$messageId}\n";
        echo "DEBUG (first):\n" . $debug . "\n";
        echo "DEBUG (retry):\n" . $retryDebug . "\n";
        return;
      }

      // Fallback: php mail()
      if ($fallbackEnabled) {
        $fallbackOk = $this->php_mail_fallback($to, $subject, $body);
        if ($fallbackOk) {
          echo "OK: sent (fallback mail(); NOT guaranteed delivery)\n";
          echo "DEBUG (first):\n" . $debug . "\n";
          echo "DEBUG (retry):\n" . $retryDebug . "\n";
          return;
        }
      } else {
        echo "NOTE: mail() fallback disabled; not marking sent\n";
      }

      echo "FAILED: not sent\n";
      echo "MESSAGE-ID: {$messageId}\n";
      echo "DEBUG (first):\n" . $debug . "\n";
      echo "DEBUG (retry):\n" . $retryDebug . "\n";
      return;
    }

    echo "OK: sent\n";
    echo "MESSAGE-ID: {$messageId}\n";
    echo "DEBUG:\n" . $debug . "\n";
  }

  /**
   * Send a one-off test email using the same Raza submission email template (assets/email.php).
   * CLI-only.
   * Usage: php index.php notifications test_raza_mail_parts someone gmail.com
   */
  public function test_raza_mail_parts($user = '', $domain = '')
  {
    if (!is_cli()) {
      echo "This endpoint may only be run from CLI." . PHP_EOL;
      return;
    }

    $user = trim((string)$user);
    $domain = trim((string)$domain);
    if ($user === '' || $domain === '') {
      echo "Usage: php index.php notifications test_raza_mail_parts someone gmail.com" . PHP_EOL;
      return;
    }

    $to = $user . '@' . $domain;
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
      echo "Invalid email." . PHP_EOL;
      return;
    }

    $this->load->library('email');
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
    $emailConfig['smtp_keepalive'] = false;
    $this->email->initialize($emailConfig);
    $this->email->set_mailtype('html');

    $proto = (string)($emailConfig['protocol'] ?? '');
    $host = (string)($emailConfig['smtp_host'] ?? '');
    $port = (string)($emailConfig['smtp_port'] ?? '');
    $crypto = (string)($emailConfig['smtp_crypto'] ?? '');
    $userCfg = (string)($emailConfig['smtp_user'] ?? '');

    echo "EMAIL CONFIG: protocol={$proto} host={$host} port={$port} crypto={$crypto} user={$userCfg}" . PHP_EOL;
    if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
      $pass = (string)($this->config->item('smtp_pass') ?? '');
      if ($pass === '' || $pass === 'admin@2024' || $pass === 'KharJamaat@2026') {
        echo "WARN: SMTP_PASS is missing or looks like a placeholder; check production .env" . PHP_EOL;
      }
    }

    $subject = 'Raza Submission Successful (Test) - ' . date('Y-m-d H:i');

    $tplPath = FCPATH . 'assets/email.php';
    $emailTemplate = @file_get_contents($tplPath);
    if ($emailTemplate === false || trim($emailTemplate) === '') {
      echo "Template missing or unreadable: {$tplPath}" . PHP_EOL;
      return;
    }

    $weekDateTime = date('l, j M Y, h:i:s A');
    $table = '<tr>'
      . '<td align="center" style="border: 1px solid black;width: 50%;"><p style="color: #000000; margin: 0px; padding: 10px; font-size: 15px; font-weight: bold; font-family: Roboto, arial, sans-serif;">Test Field</p></td>'
      . '<td align="center" style="border: 1px solid black;width: 50%;"><p style="color: #000000; margin: 0px; padding: 10px; font-size: 15px; font-weight: normal; font-family: Roboto, arial, sans-serif;">Test Value</p></td>'
      . '</tr>';

    $dynamic = [
      'todayDate' => $weekDateTime,
      'name' => 'Test Member',
      'its' => '00000000',
      'table' => $table,
      'razaname' => 'Test Raza',
      'jamaat_name' => jamaat_name()
    ];
    foreach ($dynamic as $key => $value) {
      $emailTemplate = str_replace('{%' . $key . '%}', $value, $emailTemplate);
    }

    $from = $this->config->item('smtp_user') ?: 'no-reply@localhost';
    $this->email->clear(true);
    $this->email->from($from);
    $this->email->to($to);
    $messageId = '<raza-test-' . date('YmdHis') . '-' . substr(md5($to), 0, 8) . '@kharjamaat.in>';
    $this->email->set_header('Message-ID', $messageId);
    $this->email->subject($subject);
    $this->email->message($emailTemplate);

    $ok = $this->email->send();
    $debug = $this->email->print_debugger(['headers', 'subject']);

    if ($ok) {
      echo "OK: sent\n";
      echo "MESSAGE-ID: {$messageId}\n";
      echo "DEBUG:\n" . $debug . "\n";
      return;
    }

    // Retry alternate SMTP mode (465/ssl <-> 587/tls)
    $currentPort = (string)($emailConfig['smtp_port'] ?? '');
    $currentCrypto = (string)($emailConfig['smtp_crypto'] ?? '');
    $currentHost = (string)($emailConfig['smtp_host'] ?? '');
    $looksLikeSsl465 = ($currentPort === '465') || (stripos($currentHost, 'ssl://') === 0) || (strtolower($currentCrypto) === 'ssl');

    $retryConfig = $emailConfig;
    if ($looksLikeSsl465) {
      $retryConfig['smtp_host'] = preg_replace('#^ssl://#i', '', $currentHost);
      $retryConfig['smtp_port'] = '587';
      $retryConfig['smtp_crypto'] = 'tls';
    } else {
      $retryConfig['smtp_host'] = preg_replace('#^ssl://#i', '', $currentHost);
      $retryConfig['smtp_port'] = '465';
      $retryConfig['smtp_crypto'] = 'ssl';
    }
    $retryConfig['smtp_keepalive'] = false;

    $this->email->clear(true);
    $this->email->initialize($retryConfig);
    $this->email->set_mailtype('html');
    $this->email->from($from);
    $this->email->to($to);
    $this->email->set_header('Message-ID', $messageId);
    $this->email->subject($subject . ' [retry]');
    $this->email->message($emailTemplate);
    $retryOk = $this->email->send();
    $retryDebug = $this->email->print_debugger(['headers', 'subject']);

    if ($retryOk) {
      echo "OK: sent (retry)\n";
      echo "MESSAGE-ID: {$messageId}\n";
      echo "DEBUG (first):\n" . $debug . "\n";
      echo "DEBUG (retry):\n" . $retryDebug . "\n";
      return;
    }

    echo "FAILED: not sent\n";
    echo "MESSAGE-ID: {$messageId}\n";
    echo "DEBUG (first):\n" . $debug . "\n";
    echo "DEBUG (retry):\n" . $retryDebug . "\n";
  }

  /**
   * Send a one-off test email via PHP mail() (fallback path).
   * CLI-only.
   * Usage: php index.php notifications test_php_mail_parts someone gmail.com
   */
  public function test_php_mail_parts($user = '', $domain = '')
  {
    if (!is_cli()) {
      echo "This endpoint may only be run from CLI." . PHP_EOL;
      return;
    }

    $user = trim((string)$user);
    $domain = trim((string)$domain);
    if ($user === '' || $domain === '') {
      echo "Usage: php index.php notifications test_php_mail_parts someone gmail.com" . PHP_EOL;
      return;
    }

    $to = $user . '@' . $domain;
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
      echo "Invalid email." . PHP_EOL;
      return;
    }

    $this->load->helper('email_template');
    $this->config->load('email');

    $subject = 'Test Email (PHP mail fallback) - ' . date('Y-m-d H:i');
    $body = render_generic_email_html([
      'title' => $subject,
      'todayDate' => date('l, j M Y, h:i:s A'),
      'greeting' => 'Baad Afzalus Salaam,',
      'cardTitle' => 'Fallback mail() test',
      'body' => '<p>This email was sent using PHP <code>mail()</code> (fallback path).</p>'
        . '<p>If you received this but not the SMTP test, the issue is likely SMTP deliverability.</p>',
      'auto_table' => false,
      'ctaUrl' => base_url('accounts'),
      'ctaText' => 'Open site',
    ]);

    $ok = $this->php_mail_fallback($to, $subject, $body);
    echo $ok ? "OK: mail() returned true (NOT guaranteed delivery)\n" : "FAILED: mail() returned false\n";
  }

  /**
   * CLI-safe test email sender that avoids '@' in URI segments.
   * Usage: php index.php notifications test_mail_parts shubhampatel7654 gmail.com
   */
  public function test_mail_parts($user = '', $domain = '')
  {
    if (!is_cli()) {
      echo "This endpoint may only be run from CLI." . PHP_EOL;
      return;
    }
    $user = trim((string)$user);
    $domain = trim((string)$domain);
    if ($user === '' || $domain === '') {
      echo "Usage: php index.php notifications test_mail_parts user domain.com" . PHP_EOL;
      return;
    }
    // Build email and delegate to test_mail implementation
    $to = $user . '@' . $domain;
    $this->test_mail($to);
  }

  /**
   * CLI-safe test email sender (base64url encoded).
   * Example encoding (PHP): rtrim(strtr(base64_encode('a@b.com'), '+/', '-_'), '=')
   * Usage: php index.php notifications test_mail_b64url <encoded>
   */
  public function test_mail_b64url($encoded = '')
  {
    if (!is_cli()) {
      echo "This endpoint may only be run from CLI." . PHP_EOL;
      return;
    }
    $encoded = trim((string)$encoded);
    if ($encoded === '') {
      echo "Usage: php index.php notifications test_mail_b64url <base64url>" . PHP_EOL;
      return;
    }
    $b64 = strtr($encoded, '-_', '+/');
    $pad = strlen($b64) % 4;
    if ($pad) $b64 .= str_repeat('=', 4 - $pad);
    $decoded = base64_decode($b64, true);
    if ($decoded === false) {
      echo "Invalid base64url input" . PHP_EOL;
      return;
    }
    $this->test_mail($decoded);
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

    $proto = (string)($emailConfig['protocol'] ?? '');
    $host = (string)($emailConfig['smtp_host'] ?? '');
    $port = (string)($emailConfig['smtp_port'] ?? '');
    $crypto = (string)($emailConfig['smtp_crypto'] ?? '');
    $user = (string)($emailConfig['smtp_user'] ?? '');
    $fallbackEnabled = $this->allow_php_mail_fallback();
    $preferPhpMailPrimary = $this->prefer_php_mail_primary();
    echo "EMAIL CONFIG: protocol={$proto} host={$host} port={$port} crypto={$crypto} user={$user}" . PHP_EOL;
    echo "PHP_MAIL_FALLBACK: " . ($fallbackEnabled ? 'enabled' : 'disabled') . PHP_EOL;
    echo "PHP_MAIL_PRIMARY: " . ($preferPhpMailPrimary ? 'enabled' : 'disabled') . PHP_EOL;
    if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
      $pass = (string)($this->config->item('smtp_pass') ?? '');
      if ($pass === '' || $pass === 'admin@2024' || $pass === 'KharJamaat@2026') {
        echo "WARN: SMTP_PASS is missing or looks like a placeholder; check production .env" . PHP_EOL;
      }
    }

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
          $messageId = '<n-' . $id . '-' . date('YmdHis') . '-' . substr(md5((string)$recipient), 0, 8) . '@kharjamaat.in>';
          $this->email->set_header('Message-ID', $messageId);
          $this->email->set_header('X-Notification-ID', (string)$id);
          if (!empty($subject)) $this->email->subject($subject);
          $this->email->message($body);

          // If configured, try PHP mail() first.
          if ($preferPhpMailPrimary) {
            $primaryOk = $this->php_mail_fallback($recipient, $subject, $body);
            if ($primaryOk) {
              $this->NotificationM->mark_sent($id);
              echo "[{$id}] email sent (primary php mail(); NOT guaranteed delivery)\n";
              echo "MESSAGE-ID: {$messageId}\n";
              $line = "[" . date('Y-m-d H:i:s') . "] notifications email id={$id} recipient=" . (string)$recipient . " message_id={$messageId} transport=php_mail status=sent primary=1\n";
              @file_put_contents(APPPATH . 'logs/notifications_email.log', $line, FILE_APPEND | LOCK_EX);
              continue;
            }
            echo "[{$id}] primary php mail() failed; trying SMTP...\n";
          }

          $ok = $this->email->send();
          // Always capture debugger output so we can inspect SMTP server responses (cron logs)
          $debug = $this->email->print_debugger(array('headers', 'subject'));
          if ($ok) {
            $this->NotificationM->mark_sent($id);
            echo "[{$id}] email sent (SMTP accepted)\n";
            echo "MESSAGE-ID: {$messageId}\n";
            $proto = (string)($emailConfig['protocol'] ?? '');
            $host = (string)($emailConfig['smtp_host'] ?? '');
            $port = (string)($emailConfig['smtp_port'] ?? '');
            $crypto = (string)($emailConfig['smtp_crypto'] ?? '');
            echo "TRANSPORT: protocol={$proto} host={$host} port={$port} crypto={$crypto}\n";
            echo "DEBUG: " . PHP_EOL . $debug . PHP_EOL;

            $line = "[" . date('Y-m-d H:i:s') . "] notifications email id={$id} recipient=" . (string)$recipient . " message_id={$messageId} transport={$proto} host={$host} port={$port} crypto={$crypto} status=sent\n";
            @file_put_contents(APPPATH . 'logs/notifications_email.log', $line, FILE_APPEND | LOCK_EX);
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
            $this->email->set_header('Message-ID', $messageId);
            $this->email->set_header('X-Notification-ID', (string)$id);
            if (!empty($subject)) $this->email->subject($subject);
            $this->email->message($body);
            $retryOk = $this->email->send();
            $retryDebug = $this->email->print_debugger(array('headers', 'subject'));

            if ($retryOk) {
              $this->NotificationM->mark_sent($id);
              echo "[{$id}] email sent (retry; SMTP accepted)\n";
              echo "MESSAGE-ID: {$messageId}\n";
              $proto = (string)($retryConfig['protocol'] ?? ($emailConfig['protocol'] ?? ''));
              $host = (string)($retryConfig['smtp_host'] ?? '');
              $port = (string)($retryConfig['smtp_port'] ?? '');
              $crypto = (string)($retryConfig['smtp_crypto'] ?? '');
              echo "TRANSPORT: protocol={$proto} host={$host} port={$port} crypto={$crypto}\n";
              echo "DEBUG (first): " . PHP_EOL . $debug . PHP_EOL;
              echo "DEBUG (retry): " . PHP_EOL . $retryDebug . PHP_EOL;

              $line = "[" . date('Y-m-d H:i:s') . "] notifications email id={$id} recipient=" . (string)$recipient . " message_id={$messageId} transport={$proto} host={$host} port={$port} crypto={$crypto} status=sent retry=1\n";
              @file_put_contents(APPPATH . 'logs/notifications_email.log', $line, FILE_APPEND | LOCK_EX);
            } else {
              if ($fallbackEnabled) {
                // Fallback: try PHP mail() (server local MTA)
                $fallbackOk = $this->php_mail_fallback($recipient, $subject, $body);
                if ($fallbackOk) {
                  $this->NotificationM->mark_sent($id);
                  echo "[{$id}] email sent (fallback mail(); NOT guaranteed delivery)\n";
                  echo "MESSAGE-ID: {$messageId}\n";

                  $line = "[" . date('Y-m-d H:i:s') . "] notifications email id={$id} recipient=" . (string)$recipient . " message_id={$messageId} transport=php_mail status=sent\n";
                  @file_put_contents(APPPATH . 'logs/notifications_email.log', $line, FILE_APPEND | LOCK_EX);
                } else {
                  echo "[{$id}] email failed\n";
                  echo "DEBUG (first): " . PHP_EOL . $debug . PHP_EOL;
                  echo "DEBUG (retry): " . PHP_EOL . $retryDebug . PHP_EOL;
                  $this->NotificationM->increment_attempts_and_fail($id);
                }
              } else {
                echo "[{$id}] email failed (SMTP failed; mail() fallback disabled)\n";
                echo "DEBUG (first): " . PHP_EOL . $debug . PHP_EOL;
                echo "DEBUG (retry): " . PHP_EOL . $retryDebug . PHP_EOL;
                $this->NotificationM->increment_attempts_and_fail($id);
              }
            }
          }
        } elseif ($channel === 'whatsapp') {
          $result = $this->notification_lib->deliver_whatsapp($n);
          if (!empty($result['ok'])) {
            $this->NotificationM->mark_sent($id);
            $http = isset($result['http_code']) ? (int)$result['http_code'] : 0;
            echo "[{$id}] whatsapp sent http={$http}\n";
          } else {
            $http = isset($result['http_code']) ? (int)$result['http_code'] : 0;
            $err = isset($result['error']) ? (string)$result['error'] : 'unknown error';
            echo "[{$id}] whatsapp failed http={$http} err={$err}\n";
            // Persist attempt count like email
            $this->NotificationM->increment_attempts_and_fail($id);
            // Lightweight file log for debugging provider issues
            $line = "[" . date('Y-m-d H:i:s') . "] notifications whatsapp id={$id} recipient=" . (string)($n['recipient'] ?? '') . " http={$http} err={$err}\n";
            @file_put_contents(APPPATH . 'logs/whatsapp.log', $line, FILE_APPEND | LOCK_EX);
          }
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
   * Process only pending WhatsApp notifications for a specific template (CLI-only)
   * Usage: php index.php notifications process_whatsapp_template <template_name> [limit]
   */
  public function process_whatsapp_template($templateName = '', $limit = 200)
  {
    if (!is_cli()) {
      echo "This endpoint may only be run from CLI." . PHP_EOL;
      return;
    }

    $templateName = trim((string)$templateName);
    if ($templateName === '') {
      echo "Usage: php index.php notifications process_whatsapp_template <template_name> [limit]" . PHP_EOL;
      return;
    }

    echo "ENVIRONMENT=" . (defined('ENVIRONMENT') ? ENVIRONMENT : 'unknown') . PHP_EOL;

    $this->load->model('NotificationM');
    $this->load->library('Notification_lib');

    $limit = (int)$limit;
    if ($limit <= 0) $limit = 200;

    $now = date('Y-m-d H:i:s');
    $needle = '"template_name":"' . $templateName . '"';

    echo "Processing up to {$limit} pending WhatsApp template messages for '{$templateName}'...\n";

    $pending = $this->db->from('notifications')
      ->where("(scheduled_at IS NULL OR scheduled_at <= '{$now}')", null, false)
      ->where('status', 'pending')
      ->where('channel', 'whatsapp')
      ->like('body', $needle, 'both', false)
      ->order_by('scheduled_at', 'ASC')
      ->limit($limit)
      ->get()
      ->result_array();

    $count = count($pending);
    echo "Found {$count} pending WhatsApp items\n";

    foreach ($pending as $n) {
      $id = isset($n['id']) ? (int)$n['id'] : 0;
      $recipient = isset($n['recipient']) ? $n['recipient'] : null;
      echo "[{$id}] whatsapp recipient={$recipient}\n";

      try {
        $result = $this->notification_lib->deliver_whatsapp($n);
        if (!empty($result['ok'])) {
          $this->NotificationM->mark_sent($id);
          $http = isset($result['http_code']) ? (int)$result['http_code'] : 0;
          echo "[{$id}] whatsapp sent http={$http}\n";
        } else {
          $http = isset($result['http_code']) ? (int)$result['http_code'] : 0;
          $err = isset($result['error']) ? (string)$result['error'] : 'unknown error';
          echo "[{$id}] whatsapp failed http={$http} err={$err}\n";
          $this->NotificationM->increment_attempts_and_fail($id);
          $line = "[" . date('Y-m-d H:i:s') . "] notifications whatsapp id={$id} recipient=" . (string)($n['recipient'] ?? '') . " http={$http} err={$err}\n";
          @file_put_contents(APPPATH . 'logs/whatsapp.log', $line, FILE_APPEND | LOCK_EX);
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
   * job: all|thaali_signup|thaali_feedback|sabeel_monthly|fmb_monthly|corpus_weekly|appointments_digest|event_reminders|event_reminders_3d|event_reminders_1d|event_reminders_dayof
   */
  public function schedule($job = 'all', $force = '0')
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
    $forceFlag = false;
    if (is_string($force) || is_numeric($force)) {
      $forceStr = strtolower(trim((string)$force));
      $forceFlag = in_array($forceStr, ['1', 'true', 'yes', 'y', 'force'], true);
    }
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
    if ($job === 'all' || $job === 'event_reminders') {
      // Intended to be invoked by cron at the configured times.
      // It will only enqueue the reminders matching the current HH:MM.
      $nowHm = date('H:i');
      $t3 = (string)$this->config->item('event_reminder_time_d3', 'notifications');
      $t1 = (string)$this->config->item('event_reminder_time_d1', 'notifications');
      $t0 = (string)$this->config->item('event_reminder_time_d0', 'notifications');

      if ($forceFlag) {
        echo "Event reminders: FORCE enabled; running all offsets now.\n";
        $this->schedule_event_reminders(3, 'd3_1300');
        $this->schedule_event_reminders(1, 'd1_2100');
        $this->schedule_event_reminders(0, 'd0_0900');
      } else {
        if ($t3 !== '' && $nowHm === $t3) $this->schedule_event_reminders(3, 'd3_1300');
        if ($t1 !== '' && $nowHm === $t1) $this->schedule_event_reminders(1, 'd1_2100');
        if ($t0 !== '' && $nowHm === $t0) $this->schedule_event_reminders(0, 'd0_0900');
      }

      if ($job === 'event_reminders') {
        echo "Event reminders (event_reminders): now={$nowHm} configured={d3:$t3,d1:$t1,d0:$t0}\n";
      }
    }
    if ($job === 'event_reminders_3d') {
      $this->schedule_event_reminders(3, 'd3_1300');
    }
    if ($job === 'event_reminders_1d') {
      $this->schedule_event_reminders(1, 'd1_2100');
    }
    if ($job === 'event_reminders_dayof') {
      $this->schedule_event_reminders(0, 'd0_0900');
    }

    echo "Scheduling finished." . PHP_EOL;
  }

  /**
   * CLI helper: list upcoming dates that have approved Public-Event razas (miqaat-backed).
   * Usage: php index.php notifications event_reminders_find_dates [limit]
   */
  public function event_reminders_find_dates($limit = 20)
  {
    if (!is_cli()) {
      echo "This endpoint may only be run from CLI." . PHP_EOL;
      return;
    }

    $this->load->database();
    $limit = (int)$limit;
    if ($limit <= 0) $limit = 20;
    if ($limit > 200) $limit = 200;

    $sql = "SELECT DATE(m.date) AS event_date, COUNT(DISTINCT r.id) AS raza_count\n"
      . "FROM raza r\n"
      . "JOIN raza_type rt ON rt.id = r.razaType AND rt.umoor = 'Public-Event'\n"
      . "JOIN miqaat m ON m.id = r.miqaat_id\n"
      . "WHERE r.active = 1 AND r.status = 2 AND DATE(m.date) >= CURDATE()\n"
      . "GROUP BY DATE(m.date)\n"
      . "ORDER BY DATE(m.date) ASC\n"
      . "LIMIT " . (int)$limit;

    $rows = $this->db->query($sql)->result_array();
    echo "Upcoming Public-Event dates with approved razas (limit={$limit}):\n";
    if (empty($rows)) {
      echo "(none found)\n";
      return;
    }
    foreach ($rows as $r) {
      echo " - " . (string)$r['event_date'] . " : " . (string)$r['raza_count'] . "\n";
    }
  }

  /**
   * Schedule reminder emails for upcoming Miqaat Public Events and Kaaraj Private Events.
   *
   * Source of truth:
   * - `raza` rows with `raza_type.umoor` IN ('Public-Event','Private-Event') and status=2 (approved)
   * - event date is taken from miqaat.date when miqaat_id is present; otherwise from razadata JSON field `date` (best-effort)
   *
   * @param int $offsetDays 3|1|0
   * @param string $triggerKey d3_1300|d1_2100|d0_0900
   */
  protected function schedule_event_reminders($offsetDays, $triggerKey)
  {
    $offsetDays = (int)$offsetDays;
    $triggerKey = trim((string)$triggerKey);
    if (!in_array($offsetDays, [0, 1, 3], true)) {
      echo "Event reminders: invalid offsetDays={$offsetDays}\n";
      return;
    }
    if ($triggerKey === '') {
      echo "Event reminders: missing triggerKey\n";
      return;
    }

    $recipients = $this->config->item('amilsaheb_event_reminder_recipients', 'notifications');
    if (empty($recipients) || !is_array($recipients)) {
      $recipients = $this->config->item('amilsaheb_appointments_digest_recipients', 'notifications');
    }
    if (empty($recipients) || !is_array($recipients)) {
      $recipients = ['kharamilsaheb@gmail.com'];
    }

    // Test/ops overrides
    $overrideTo = getenv('EVENT_REMINDER_TEST_TO');
    if (is_string($overrideTo)) {
      $overrideTo = trim($overrideTo);
      if ($overrideTo !== '' && filter_var($overrideTo, FILTER_VALIDATE_EMAIL)) {
        $recipients = [$overrideTo];
        echo "Event reminders: using EVENT_REMINDER_TEST_TO={$overrideTo}\n";
      }
    }

    $dryRun = getenv('EVENT_REMINDER_DRY_RUN');
    $dryRun = is_string($dryRun) && trim($dryRun) === '1';

    // WhatsApp recipient (Amil). Pulled from DB (roles/app_settings) with config fallback.
    $amilWhatsapp = amilsaheb_whatsapp_number();

    $targetDate = date('Y-m-d', strtotime('+' . $offsetDays . ' day'));
    $overrideDate = getenv('EVENT_REMINDER_TARGET_DATE');
    if (is_string($overrideDate)) {
      $overrideDate = trim($overrideDate);
      if ($overrideDate !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $overrideDate)) {
        $targetDate = $overrideDate;
        echo "Event reminders: using EVENT_REMINDER_TARGET_DATE={$overrideDate}\n";
      }
    }
    $label = $offsetDays === 0 ? 'Today' : ($offsetDays === 1 ? 'Tomorrow' : ('In ' . $offsetDays . ' days'));
    echo "Event reminders ({$triggerKey}): targetDate={$targetDate} ({$label})\n";

    if (!$this->db->table_exists('event_reminder_log')) {
      echo "Event reminders: missing table event_reminder_log. Run migrations first (php index.php migrate).\n";
      return;
    }

    $rows = [];

    // 1) Miqaat Public Events: match by miqaat date directly (reliable even for older razas)
    $sqlPub = "SELECT r.id AS raza_id, r.user_id, r.miqaat_id, r.razadata, r.`time-stamp` AS created_at,
                rt.umoor, rt.name AS razatype_name,
                u.Full_Name AS member_name,
                m.name AS miqaat_name, m.date AS miqaat_date
              FROM raza r
              JOIN raza_type rt ON rt.id = r.razaType
              LEFT JOIN user u ON u.ITS_ID = r.user_id
              JOIN miqaat m ON m.id = r.miqaat_id
              WHERE r.active = 1
                AND r.status = 2
                AND rt.umoor = 'Public-Event'
                AND DATE(m.date) = " . $this->db->escape($targetDate) . "
              ORDER BY r.`time-stamp` DESC";
    $rowsPub = $this->db->query($sqlPub)->result_array();
    if (!empty($rowsPub)) $rows = array_merge($rows, $rowsPub);

    // 2) Private Events (and any event without miqaat date): scan approved events from recent history and parse JSON date.
    // Bound scan window to keep cron fast.
    $sqlOther = "SELECT r.id AS raza_id, r.user_id, r.miqaat_id, r.razadata, r.`time-stamp` AS created_at,
                  rt.umoor, rt.name AS razatype_name,
                  u.Full_Name AS member_name,
                  m.name AS miqaat_name, m.date AS miqaat_date
                FROM raza r
                JOIN raza_type rt ON rt.id = r.razaType
                LEFT JOIN user u ON u.ITS_ID = r.user_id
                LEFT JOIN miqaat m ON m.id = r.miqaat_id
                WHERE r.active = 1
                  AND r.status = 2
                  AND rt.umoor IN ('Public-Event','Private-Event')
                  AND r.`time-stamp` >= DATE_SUB(NOW(), INTERVAL 3 YEAR)
                ORDER BY r.`time-stamp` DESC
                LIMIT 3000";
    $rowsOther = $this->db->query($sqlOther)->result_array();
    if (!empty($rowsOther)) $rows = array_merge($rows, $rowsOther);

    $matches = [];
    $seen = [];
    foreach ($rows as $r) {
      $rid = (int)($r['raza_id'] ?? 0);
      if ($rid > 0) {
        if (isset($seen[$rid])) continue;
        $seen[$rid] = true;
      }
      $eventDate = $this->extract_event_date_ymd($r);
      if ($eventDate === '' || $eventDate !== $targetDate) continue;
      $r['event_date'] = $eventDate;
      $matches[] = $r;
    }

    if ($dryRun) {
      echo "Event reminders dry-run: matches=" . count($matches) . " (no emails queued)\n";
      $i = 0;
      foreach ($matches as $m) {
        if ($i++ >= 10) break;
        $umoor = (string)($m['umoor'] ?? '');
        $miqaatName = trim((string)($m['miqaat_name'] ?? ''));
        $memberName = trim((string)($m['member_name'] ?? ''));
        $rid = (int)($m['raza_id'] ?? 0);
        echo " - raza_id={$rid} umoor={$umoor} date=" . (string)($m['event_date'] ?? '') . " miqaat=" . $miqaatName . " member=" . $memberName . "\n";
      }
      return;
    }

    $countQueued = 0;
    foreach ($matches as $ev) {
      $razaId = (int)($ev['raza_id'] ?? 0);
      if ($razaId <= 0) continue;

      $umoor = (string)($ev['umoor'] ?? '');
      $eventTypeLabel = ($umoor === 'Public-Event') ? 'Miqaat Public Event' : (($umoor === 'Private-Event') ? 'Kaaraj Private Event' : 'Event');
      $eventDateNice = date('D, d M Y', strtotime($ev['event_date']));

      $dTag = ($offsetDays === 0) ? 'D0' : ('D-' . $offsetDays);
      $subject = "Event Reminder ({$dTag}): {$eventTypeLabel} on {$eventDateNice}";

      $miqaatId = (int)($ev['miqaat_id'] ?? 0);
      $miqaatName = trim((string)($ev['miqaat_name'] ?? ''));
      $memberName = trim((string)($ev['member_name'] ?? ''));
      $requestedBy = $memberName !== '' ? $memberName : ('ITS ' . (string)($ev['user_id'] ?? ''));

      // Prefer public IDs for emails (raza.raza_id / miqaat.miqaat_id) but fall back to numeric IDs.
      $emailRazaIdRaw = (string)$razaId;
      $rp = $this->db->select('raza_id')->from('raza')->where('id', $razaId)->limit(1)->get()->row_array();
      if (!empty($rp) && !empty($rp['raza_id'])) {
        $emailRazaIdRaw = (string)$rp['raza_id'];
      }
      $emailRazaId = trim($emailRazaIdRaw);
      if ($emailRazaId !== '' && stripos($emailRazaId, 'R#') !== 0 && preg_match('/^\d/', $emailRazaId)) {
        $emailRazaId = 'R#' . $emailRazaId;
      }

      $emailMiqaatId = '';
      if ($miqaatId > 0) {
        $emailMiqaatIdRaw = (string)$miqaatId;
        $mp = $this->db->select('miqaat_id')->from('miqaat')->where('id', $miqaatId)->limit(1)->get()->row_array();
        if (!empty($mp) && !empty($mp['miqaat_id'])) {
          $emailMiqaatIdRaw = (string)$mp['miqaat_id'];
        }
        $emailMiqaatId = trim($emailMiqaatIdRaw);
        if ($emailMiqaatId !== '' && stripos($emailMiqaatId, 'M#') !== 0 && preg_match('/^\d/', $emailMiqaatId)) {
          $emailMiqaatId = 'M#' . $emailMiqaatId;
        }
      }

      // WhatsApp vars (single-line; provider can silently fail on newlines in variables)
      $waEventType = preg_replace('/\s+/', ' ', (string)$eventTypeLabel);
      $waEventDate = preg_replace('/\s+/', ' ', (string)$eventDateNice);
      // Use public IDs with prefixes (consistent with email table + other notifications)
      $waRazaId = $emailRazaId !== '' ? (string)$emailRazaId : ('R#' . (string)$razaId);
      if ($miqaatId > 0) {
        $waMiqaatId = $emailMiqaatId !== '' ? (string)$emailMiqaatId : ('M#' . (string)$miqaatId);
      } else {
        $waMiqaatId = '-';
      }
      $waMiqaatName = $miqaatName !== '' ? $miqaatName : '-';
      $waRequestedBy = preg_replace('/\s+/', ' ', (string)$requestedBy);
      if ($waEventType === null) $waEventType = (string)$eventTypeLabel;
      if ($waEventDate === null) $waEventDate = (string)$eventDateNice;
      if ($waRequestedBy === null) $waRequestedBy = (string)$requestedBy;
      $waEventType = trim((string)$waEventType);
      $waEventDate = trim((string)$waEventDate);
      $waRequestedBy = trim((string)$waRequestedBy);

      $detailsRows = [];
      $detailsRows[] = ['Event Type', $eventTypeLabel];
      $detailsRows[] = ['Event Date', $eventDateNice];
      $detailsRows[] = ['Raza ID', (string)$emailRazaId];
      if ($miqaatId > 0) $detailsRows[] = ['Miqaat ID', (string)$emailMiqaatId];
      if ($miqaatName !== '') $detailsRows[] = ['Miqaat Name', $miqaatName];
      $detailsRows[] = ['Requested By', $requestedBy];

      $body = '<p style="font-family:Arial,Helvetica,sans-serif;">'
        . 'Reminder for an upcoming ' . htmlspecialchars($eventTypeLabel) . '.</p>';
      $body .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;font-family:Arial,Helvetica,sans-serif;font-size:14px;">'
        . '<tbody>';
      foreach ($detailsRows as $dr) {
        $body .= '<tr>'
          . '<td style="padding:8px;border:1px solid #ddd;background:#f7f7f7;width:180px;"><strong>' . htmlspecialchars((string)$dr[0]) . '</strong></td>'
          . '<td style="padding:8px;border:1px solid #ddd;">' . htmlspecialchars((string)$dr[1]) . '</td>'
          . '</tr>';
      }
      $body .= '</tbody></table>';

      $ctaUrl = ($umoor === 'Public-Event')
        ? base_url('amilsaheb/EventRazaRequest?event_type=1')
        : base_url('amilsaheb/EventRazaRequest?event_type=2');
      $ctaText = 'View event requests';

      foreach ($recipients as $to) {
        $to = trim((string)$to);
        if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) continue;

        // Duplicate protection: only one reminder per event+trigger per recipient.
        $exists = $this->db->select('id')
          ->from('event_reminder_log')
          ->where('event_umoor', $umoor)
          ->where('raza_id', $razaId)
          ->where('trigger_key', $triggerKey)
          ->where('recipient', $to)
          ->where('DATE(created_at) = CURDATE()', null, false)
          ->limit(1)
          ->get()->row_array();
        if (!empty($exists)) continue;

        $notificationId = $this->notification_lib->send_email([
          'recipient' => $to,
          'recipient_type' => 'amil',
          'subject' => $subject,
          'body' => $body,
          'card_title' => 'Event Reminder',
          'cta_url' => $ctaUrl,
          'cta_text' => $ctaText,
        ]);

        if (!empty($notificationId)) {
          // Record enqueue so repeated cron invocations don't duplicate.
          $this->db->insert('event_reminder_log', [
            'event_umoor' => $umoor,
            'raza_id' => $razaId,
            'trigger_key' => $triggerKey,
            'recipient' => $to,
            'notification_id' => (int)$notificationId,
          ]);
          $countQueued++;
        }
      }

      // WhatsApp reminder to Amil (same template used for D-3/D-1/D0)
      if (!empty($amilWhatsapp)) {
        $existsWa = $this->db->select('id')
          ->from('event_reminder_log')
          ->where('event_umoor', $umoor)
          ->where('raza_id', $razaId)
          ->where('trigger_key', $triggerKey)
          ->where('recipient', $amilWhatsapp)
          ->limit(1)
          ->get()->row_array();

        if (empty($existsWa)) {
          $notificationId = $this->notification_lib->send_whatsapp([
            'recipient' => $amilWhatsapp,
            'recipient_type' => 'amil',
            'template_name' => 'event_reminder_d3_member',
            'template_language' => 'en',
            'body_vars' => [
              (string)$waEventType,
              (string)$waEventDate,
              (string)$waRazaId,
              (string)$waMiqaatId,
              (string)$waMiqaatName,
              (string)$waRequestedBy,
            ]
          ]);

          if (!empty($notificationId)) {
            $this->db->insert('event_reminder_log', [
              'event_umoor' => $umoor,
              'raza_id' => $razaId,
              'trigger_key' => $triggerKey,
              'recipient' => $amilWhatsapp,
              'notification_id' => (int)$notificationId,
            ]);
          }
        }
      }
    }

    echo "Event reminders queued: {$countQueued} emails (matches=" . count($matches) . ")\n";
  }

  /**
   * Extract an event date (YYYY-MM-DD) from a joined event row.
   * Prefers miqaat.date when available; falls back to razadata JSON `date`.
   */
  protected function extract_event_date_ymd($row)
  {
    $miqaatDate = trim((string)($row['miqaat_date'] ?? ''));
    if ($miqaatDate !== '') {
      $d = substr($miqaatDate, 0, 10);
      if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) return $d;
      $ts = strtotime($miqaatDate);
      if ($ts !== false) return date('Y-m-d', $ts);
    }

    $razadata = $row['razadata'] ?? null;
    if (!is_string($razadata) || trim($razadata) === '') return '';

    $decoded = json_decode($razadata, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) return '';

    $candidates = [];
    if (isset($decoded['date'])) $candidates[] = $decoded['date'];
    if (isset($decoded['event_date'])) $candidates[] = $decoded['event_date'];
    if (isset($decoded['Event Date'])) $candidates[] = $decoded['Event Date'];
    if (isset($decoded['Date'])) $candidates[] = $decoded['Date'];

    foreach ($candidates as $val) {
      if (!is_string($val)) continue;
      $val = trim($val);
      if ($val === '') continue;
      $raw = $val;
      // Normalize dd/mm/yyyy -> yyyy-mm-dd when unambiguous
      if (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $val, $m)) {
        $d = (int)$m[1];
        $mo = (int)$m[2];
        $y = (int)$m[3];
        if (checkdate($mo, $d, $y)) {
          return sprintf('%04d-%02d-%02d', $y, $mo, $d);
        }
      }
      if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) return $val;
      $ts = strtotime($raw);
      if ($ts !== false) return date('Y-m-d', $ts);
    }

    return '';
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
          $waName = trim((string)($r['Full_Name'] ?? ''));
          if ($waName === '') $waName = 'Member';
          $this->notification_lib->send_whatsapp([
            'recipient' => $phone,
            'recipient_type' => 'member',
            'template_name' => 'thaali_signup_reminder_member',
            'template_language' => 'en',
            'body_vars' => [
              (string)$waName,
            ]
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

        $waName = trim((string)($r['Full_Name'] ?? ''));
        if ($waName === '') $waName = 'Member';
        $this->notification_lib->send_whatsapp([
          'recipient' => $phone,
          'recipient_type' => 'member',
          'template_name' => 'thaali_feedback_reminder_member',
          'template_language' => 'en',
          'body_vars' => [
            (string)$waName,
          ]
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

        $waName = trim((string)($r['Full_Name'] ?? ''));
        if ($waName === '') $waName = 'Member';
        $this->notification_lib->send_whatsapp([
          'recipient' => $phone,
          'recipient_type' => 'member',
          'template_name' => 'thaali_feedback_reminder_member',
          'template_language' => 'en',
          'body_vars' => [
            (string)$waName,
          ]
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
    $this->load->library('Whatsapp_bot_lib');

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

    // Avoid sending duplicate WhatsApp messages on the same day.
    // Template payload is stored as JSON in notifications.body.
    $existingWa = $this->db->select('recipient')
      ->from('notifications')
      ->where('channel', 'whatsapp')
      ->like('body', '"template_name":"finance_dues_summary_member"', 'both', false)
      ->where('DATE(created_at) = CURDATE()', null, false)
      ->get()
      ->result_array();
    $waAlready = [];
    foreach ($existingWa as $e) {
      $rcpt = isset($e['recipient']) ? trim((string)$e['recipient']) : '';
      if ($rcpt !== '') $waAlready[strtolower($rcpt)] = true;
    }

    // Build family-wise recipient list, preferring HOF email (or any valid family email).
    $sql = "SELECT ITS_ID, HOF_ID, HOF_FM_TYPE, Full_Name,
              COALESCE(NULLIF(Email,''), NULLIF(email,''), '') AS email,
              COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') AS mobile
            FROM user
            WHERE Inactive_Status IS NULL AND COALESCE(Sector,'') <> ''";
    $rows = $this->db->query($sql)->result_array();

    $families = []; // familyId => ['its'=>familyId,'name'=>...,'email'=>...,'mobile'=>...]
    foreach ($rows as $r) {
      $its = (int)($r['ITS_ID'] ?? 0);
      if ($its <= 0) continue;
      $hof = (int)($r['HOF_ID'] ?? 0);
      $familyId = ($hof > 0) ? $hof : $its;

      $email = trim((string)($r['email'] ?? ''));
      $emailOk = (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL));

      $mobile = trim((string)($r['mobile'] ?? ''));
      $mobileOk = ($mobile !== '');

      $name = trim((string)($r['Full_Name'] ?? ''));
      $isHofRow = ((string)($r['HOF_FM_TYPE'] ?? '') === 'HOF') || ($its === $familyId);

      if (!isset($families[$familyId])) {
        $families[$familyId] = [
          'its' => $familyId,
          'name' => $name,
          'email' => ($emailOk ? $email : ''),
          'mobile' => ($mobileOk ? $mobile : ''),
        ];
      }

      // Prefer HOF email/name when available; otherwise fill missing email.
      if ($isHofRow) {
        if ($name !== '') $families[$familyId]['name'] = $name;
        if ($emailOk) $families[$familyId]['email'] = $email;
        if ($mobileOk) $families[$familyId]['mobile'] = $mobile;
      } else {
        if ($families[$familyId]['email'] === '' && $emailOk) $families[$familyId]['email'] = $email;
        if ($families[$familyId]['mobile'] === '' && $mobileOk) $families[$familyId]['mobile'] = $mobile;
      }
    }

    $count = 0;
    $sentEmails = [];
    $sentWhatsapps = [];

    foreach ($families as $familyId => $fam) {
      $familyId = (int)$familyId;
      if ($familyId <= 0) continue;

      $email = trim((string)($fam['email'] ?? ''));
      $emailOk = (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL));

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

      // WhatsApp (template): only send if mobile exists AND there is some due.
      $mobile = trim((string)($fam['mobile'] ?? ''));
      $waName = ($name !== '' ? $name : 'Member');
      if ($mobile !== '' && $total > 0) {
        $waRecipient = strtolower($this->whatsapp_bot_lib->normalize_phone($mobile));
        if (!isset($waAlready[$waRecipient]) && !isset($sentWhatsapps[$waRecipient])) {
          $details = 'FMB: ₹' . $fmt($fmbDue)
            . ' | Sabeel: ₹' . $fmt($sabeelDue)
            . ' | Miqaat: ₹' . $fmt($miqDue)
            . ' | Extra: ₹' . $fmt($gcDue);
          // Provider can silently fail on newlines; keep vars strictly single-line.
          $details = preg_replace('/\s+/', ' ', (string)$details);
          if ($details === null) $details = 'FMB: ₹' . $fmt($fmbDue);

          $this->notification_lib->send_whatsapp([
            'recipient' => $mobile,
            'recipient_type' => 'member',
            'template_name' => 'finance_dues_summary_member',
            'template_language' => 'en',
            'body_vars' => [
              (string)$waName,
              (string)$details,
              '₹' . $fmt($total),
            ]
          ]);
          $sentWhatsapps[$waRecipient] = true;
        }
      }

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

      // Email (existing behavior): send only when a valid family email exists.
      if ($emailOk) {
        $emailKey = strtolower($email);
        if (!isset($already[$emailKey]) && !isset($sentEmails[$emailKey])) {
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
      }
    }

    echo "Finances monthly reminders queued for {$count} families\n";
  }

  protected function schedule_weekly_corpus()
  {
    $tpl = $this->config->item('tpl_corpus_fund', 'notifications');
    $sql = "SELECT DISTINCT HOF_ID, COALESCE(Full_Name,'') AS full_name, COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') AS mobile, COALESCE(NULLIF(Email,''), NULLIF(email,''), '') AS email FROM user WHERE HOF_FM_TYPE = 'HOF' AND Inactive_Status IS NULL AND COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') <> ''";
    $rows = $this->db->query($sql)->result_array();
    $count = 0;
    $sentEmails = [];
    foreach ($rows as $r) {
      $hofId = isset($r['HOF_ID']) ? (int)$r['HOF_ID'] : 0;
      if ($hofId <= 0) continue;

      $hofName = trim((string)($r['full_name'] ?? ''));

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

      // WhatsApp template reminder (only when there is an outstanding due)
      if ($totalDue > 0) {
        $waName = ($hofName !== '' ? $hofName : 'Member');
        $this->notification_lib->send_whatsapp([
          'recipient' => $phone,
          'recipient_type' => 'hof',
          'template_name' => 'corpus_fund_reminder_member',
          'template_language' => 'en',
          'body_vars' => [
            (string)$waName,
            '₹' . $fmt($totalDue),
          ]
        ]);
      }
      $email = trim((string)($r['email'] ?? ''));
      if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailKey = strtolower($email);
        if (isset($sentEmails[$emailKey])) {
          $count++;
          continue;
        }

        $subject = 'Corpus fund reminder';
        if ($hofName !== '') {
          $subject = $hofName . ' - ' . $subject;
        }

        $this->notification_lib->send_email([
          'recipient' => $email,
          'recipient_type' => 'hof',
          'subject' => $subject,
          'body' => $body,
          'recipient_name' => $hofName,
          'recipient_its' => (string)$hofId,
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

    // WhatsApp summary (single-line; provider can silently fail on newlines in variables)
    $waSummary = '';
    if ($countAppointments === 0) {
      $waSummary = 'No upcoming pending appointments found.';
    } else {
      $parts = [];
      foreach ($rows as $r) {
        $time    = trim((string)($r['time'] ?? ''));
        $its     = trim((string)($r['its'] ?? ''));
        $name    = trim((string)($r['Full_Name'] ?? 'Member'));
        $purpose = trim((string)($r['purpose'] ?? ''));
        if ($purpose === '') $purpose = '-';

        $item = '';
        if ($time !== '') $item .= $time . ' - ';
        $item .= $name;
        if ($its !== '') $item .= ' (' . $its . ')';
        $item .= ' - ' . $purpose;

        $item = preg_replace('/\s+/', ' ', $item);
        if ($item === null) $item = '';
        $item = trim($item);
        if ($item !== '') $parts[] = $item;
      }

      $waSummary = implode(' | ', $parts);
      $waSummary = preg_replace('/\s+/', ' ', (string)$waSummary);
      if ($waSummary === null) $waSummary = '';
      $waSummary = trim($waSummary);
      if ($waSummary === '') $waSummary = 'Appointments scheduled for ' . $tomorrow . '.';
    }

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

    // WhatsApp digest to Amil (once per day)
    $amilWhatsapp = amilsaheb_whatsapp_number();
    if (!empty($amilWhatsapp)) {
      $existsWa = $this->db->select('id')
        ->from('notifications')
        ->where('channel', 'whatsapp')
        ->where('recipient', $amilWhatsapp)
        ->like('body', '"template_name":"daily_appointments_summary_amil"')
        ->where('DATE(created_at) = CURDATE()', null, false)
        ->limit(1)
        ->get()->row_array();

      if (empty($existsWa)) {
        $this->notification_lib->send_whatsapp([
          'recipient' => $amilWhatsapp,
          'recipient_type' => 'amil',
          'template_name' => 'daily_appointments_summary_amil',
          'template_language' => 'en',
          'body_vars' => [
            (string)$waSummary,
          ]
        ]);
      }
    }

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
