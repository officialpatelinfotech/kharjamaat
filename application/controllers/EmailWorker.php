<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmailWorker extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('EmailQueueM');
  }

  public function index()
  {
    // CLI entrypoint: run the job processor
    if (!is_cli()) {
      echo "This worker must be run from CLI\n";
      return;
    }

    $this->process_jobs(false);
  }

  private function is_json($string)
  {
    if (!is_string($string) || $string === '') return false;
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
  }

  /**
   * Token-protected web runner for environments without SSH access.
   * Usage: /index.php/emailworker/web_run/<token>
   */
  public function web_run($token = null)
  {
    $expected = getenv('EMAILWORKER_DEBUG_TOKEN') ?: ($_ENV['EMAILWORKER_DEBUG_TOKEN'] ?? $_SERVER['EMAILWORKER_DEBUG_TOKEN'] ?? '');
    if (empty($expected) || $token !== $expected) {
      http_response_code(403);
      echo "Forbidden\n";
      return;
    }
    $this->process_jobs(true);
  }

  /**
   * Core processing logic used by CLI and web runner.
   */
  protected function process_jobs($echoOutput = false)
  {
    $this->load->library('email');

    $allowPhpMailFallback = getenv('EMAIL_ALLOW_PHP_MAIL_FALLBACK');
    if ($allowPhpMailFallback === false && isset($_ENV['EMAIL_ALLOW_PHP_MAIL_FALLBACK'])) $allowPhpMailFallback = $_ENV['EMAIL_ALLOW_PHP_MAIL_FALLBACK'];
    $allowPhpMailFallback = is_string($allowPhpMailFallback) ? trim($allowPhpMailFallback) : '';
    $allowPhpMailFallback = ($allowPhpMailFallback === '1' || strtolower($allowPhpMailFallback) === 'true');

    $preferPhpMailPrimary = getenv('EMAIL_PREFER_PHP_MAIL');
    if ($preferPhpMailPrimary === false && isset($_ENV['EMAIL_PREFER_PHP_MAIL'])) $preferPhpMailPrimary = $_ENV['EMAIL_PREFER_PHP_MAIL'];
    $preferPhpMailPrimary = is_string($preferPhpMailPrimary) ? trim($preferPhpMailPrimary) : '';
    $preferPhpMailPrimary = ($preferPhpMailPrimary === '1' || strtolower($preferPhpMailPrimary) === 'true');

    // Load central email config so production changes apply everywhere.
    // Note: CI config loader doesn't return a whole config array via item('email');
    // it loads keys into the global config namespace (unless using sections).
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
      'mailpath',
    ];

    $config = [];
    foreach ($emailKeys as $key) {
      $value = $this->config->item($key);
      if ($value !== null) {
        $config[$key] = $value;
      }
    }

    // Ensure required newline/crlf are correct for SMTP.
    if (empty($config['newline'])) $config['newline'] = "\r\n";
    if (empty($config['crlf'])) $config['crlf'] = "\r\n";

    // Worker should not keep SMTP connections open across CLI/web runs.
    $config['smtp_keepalive'] = false;

    $this->email->initialize($config);

    if ($echoOutput) {
      $safeHost = $config['smtp_host'] ?? '';
      $safePort = $config['smtp_port'] ?? '';
      $safeCrypto = $config['smtp_crypto'] ?? '';
      $safeProto = $config['protocol'] ?? '';
      echo "EmailWorker config: protocol={$safeProto} host={$safeHost} port={$safePort} crypto={$safeCrypto}\n";
      echo "PHP_MAIL_PRIMARY: " . ($preferPhpMailPrimary ? 'enabled' : 'disabled') . "\n";
      echo "PHP_MAIL_FALLBACK: " . ($allowPhpMailFallback ? 'enabled' : 'disabled') . "\n";
    }


    $batch = 50;
    $jobs = $this->EmailQueueM->get_pending($batch);
    if (empty($jobs)) {
      if ($echoOutput) echo "No pending emails\n";
      else echo "No pending emails\n";
      return;
    }

    foreach ($jobs as $job) {
      $to = $job['recipient'];
      $bcc = $job['bcc'];
      $subject = $job['subject'];
      $message = $job['message'];
      $mailtype = $job['mailtype'] ?: 'html';

      if ($this->is_json($to)) $to = json_decode($to, true);
      if ($this->is_json($bcc)) $bcc = json_decode($bcc, true);

      try {
        // If configured, try PHP mail() first.
        if ($preferPhpMailPrimary) {
          $primaryOk = $this->php_mail_fallback($to, $subject, $message, $bcc);
          if ($primaryOk) {
            $this->EmailQueueM->mark_sent($job['id']);
            $fbLog = "[" . date('Y-m-d H:i:s') . "] Job {$job['id']} Primary: php mail() returned true\n";
            @file_put_contents(APPPATH . 'logs/emailworker.log', $fbLog, FILE_APPEND | LOCK_EX);
            if ($echoOutput) echo "Primary sent job {$job['id']}\n";
            else echo "Primary sent job {$job['id']}\n";
            usleep(100000);
            continue;
          }
          $fbLog = "[" . date('Y-m-d H:i:s') . "] Job {$job['id']} Primary: php mail() returned false; trying SMTP\n";
          @file_put_contents(APPPATH . 'logs/emailworker.log', $fbLog, FILE_APPEND | LOCK_EX);
        }

        $this->email->clear(true);
        $this->email->set_mailtype($mailtype);
        $this->email->set_header('X-Queue-ID', $job['id']);
        $this->email->set_header('X-Queue-Created', $job['created_at'] ?? '');
        $messageId = '<q-' . $job['id'] . '-' . date('YmdHis') . '@kharjamaat.in>';
        $this->email->set_header('Message-ID', $messageId);
        $fromEmail = $config['smtp_user'] ?? 'admin@kharjamaat.in';
        $this->email->from($fromEmail, 'Admin');
        $this->email->to(is_array($to) ? $to : $to);
        if (!empty($bcc)) $this->email->bcc($bcc);
        $this->email->subject($subject);
        $this->email->message($message);

        $ok = $this->email->send();

        // Keep logs useful but avoid writing full HTML bodies into logs.
        $debug = $this->email->print_debugger(['headers', 'subject']);

        // If SMTP config was changed (465/ssl vs 587/tls), retry once with alternate mode.
        $retryOk = false;
        $retryDebug = '';
        $usedRetry = false;
        if (!$ok) {
          $currentPort = (string)($config['smtp_port'] ?? '');
          $currentCrypto = (string)($config['smtp_crypto'] ?? '');
          $currentHost = (string)($config['smtp_host'] ?? '');
          $looksLikeSsl465 = ($currentPort === '465') || (stripos($currentHost, 'ssl://') === 0) || (strtolower($currentCrypto) === 'ssl');

          $retryConfig = $config;
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
          if (empty($retryConfig['newline'])) $retryConfig['newline'] = "\r\n";
          if (empty($retryConfig['crlf'])) $retryConfig['crlf'] = "\r\n";

          $this->email->clear(true);
          $this->email->initialize($retryConfig);
          $usedRetry = true;
          $this->email->set_mailtype($mailtype);
          $this->email->set_header('X-Queue-ID', $job['id']);
          $this->email->set_header('X-Queue-Created', $job['created_at'] ?? '');
          $this->email->set_header('Message-ID', $messageId);
          $fromEmailRetry = $retryConfig['smtp_user'] ?? ($config['smtp_user'] ?? 'admin@kharjamaat.in');
          $this->email->from($fromEmailRetry, 'Admin');
          $this->email->to(is_array($to) ? $to : $to);
          if (!empty($bcc)) $this->email->bcc($bcc);
          $this->email->subject($subject);
          $this->email->message($message);
          $retryOk = $this->email->send();
          $retryDebug = $this->email->print_debugger(['headers', 'subject']);
        }

        $finalOk = $ok || $retryOk;
        $transport = ($config['protocol'] ?? 'mail');
        $transportMeta = $transport;
        if ($transport === 'smtp') {
          $transportMeta .= ' host=' . ($config['smtp_host'] ?? '') . ' port=' . ($config['smtp_port'] ?? '') . ' crypto=' . ($config['smtp_crypto'] ?? '');
        }
        $logLine = "[" . date('Y-m-d H:i:s') . "] Job {$job['id']} To: " . (is_array($to) ? implode(',', $to) : $to) . " Subject: " . str_replace("\n", ' ', $subject) . " Status: " . ($finalOk ? 'sent' : 'failed') . "\n" . $debug;
        if (!$ok && $retryDebug !== '') {
          $logLine .= "\n[retry]\n" . $retryDebug;
        }
        $logLine .= "\n[meta] message_id=" . $messageId . " transport=" . $transportMeta . " retry=" . ($usedRetry ? '1' : '0') . " php_mail_fallback=" . ($allowPhpMailFallback ? '1' : '0');
        $logLine .= "\n---\n";
        @file_put_contents(APPPATH . 'logs/emailworker.log', $logLine, FILE_APPEND | LOCK_EX);

        if ($finalOk) {
          $this->EmailQueueM->mark_sent($job['id']);
          if ($echoOutput) echo "Sent job {$job['id']}\n";
          else echo "Sent job {$job['id']}\n";
        } else {
          if ($allowPhpMailFallback) {
            $fallbackOk = $this->php_mail_fallback($to, $subject, $message, $bcc);
            if ($fallbackOk) {
              // Note: PHP mail() returning TRUE does not guarantee delivery.
              // Keep it opt-in via EMAIL_ALLOW_PHP_MAIL_FALLBACK.
              $this->EmailQueueM->mark_sent($job['id']);
              $fbLog = "[" . date('Y-m-d H:i:s') . "] Job {$job['id']} Fallback: php mail() returned true\n";
              @file_put_contents(APPPATH . 'logs/emailworker.log', $fbLog, FILE_APPEND | LOCK_EX);
              if ($echoOutput) echo "Fallback sent job {$job['id']}\n";
              else echo "Fallback sent job {$job['id']}\n";
            } else {
              $err = $debug;
              if ($retryDebug !== '') $err .= "\n[retry]\n" . $retryDebug;
              $this->EmailQueueM->mark_failed($job['id'], $err);
              if ($echoOutput) echo "Failed job {$job['id']}\n";
              else echo "Failed job {$job['id']}\n";
            }
          } else {
            $err = $debug;
            if ($retryDebug !== '') $err .= "\n[retry]\n" . $retryDebug;
            $this->EmailQueueM->mark_failed($job['id'], $err);
            if ($echoOutput) echo "Failed job {$job['id']}\n";
            else echo "Failed job {$job['id']}\n";
          }
        }
        usleep(100000);
      } catch (Exception $e) {
        $this->EmailQueueM->mark_failed($job['id'], $e->getMessage());
        if ($echoOutput) echo "Exception for job {$job['id']}: " . $e->getMessage() . "\n";
        else echo "Exception for job {$job['id']}: " . $e->getMessage() . "\n";
      }
    }
  }

  /**
   * Send fallback using PHP mail() when SMTP fails.
   * Accepts $to (string or array), $bcc (string or array)
   */
  private function php_mail_fallback($to, $subject, $message, $bcc = null)
  {
    // Normalize recipients
    $tos = is_array($to) ? $to : [$to];
    $bccs = [];
    if (!empty($bcc)) {
      $bccs = is_array($bcc) ? $bcc : [$bcc];
    }

    $from = $this->config->item('smtp_user') ?: 'admin@' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';
    $headers[] = 'From: ' . $from;
    if (!empty($bccs)) {
      $headers[] = 'Bcc: ' . implode(',', $bccs);
    }

    $hdr = implode("\r\n", $headers);
    $okAny = false;
    foreach ($tos as $t) {
      if (empty($t)) continue;
      $ok = @mail($t, $subject, $message, $hdr);
      $okAny = $okAny || $ok;
      $line = "[" . date('Y-m-d H:i:s') . "] php mail() to={$t} status=" . ($ok ? 'sent' : 'failed') . "\n";
      @file_put_contents(APPPATH . 'logs/emailworker.log', $line, FILE_APPEND | LOCK_EX);
    }
    return $okAny;
  }
}
