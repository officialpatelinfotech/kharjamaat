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
    if (!is_cli()) {
      echo "This worker must be run from CLI\n";
      return;
    }

    // Load email config and library. Disable keepalive here to avoid
    // SMTP connection reuse issues that some MTAs don't handle well.
    $email_config = $this->config->item('email');
    $email_config['smtp_keepalive'] = false;
    $this->load->library('email', $email_config);

    // Process in small batches and reuse SMTP connection
    $batch = 50;
    $jobs = $this->EmailQueueM->get_pending($batch);
    if (empty($jobs)) {
      echo "No pending emails\n";
      return;
    }

    foreach ($jobs as $job) {
      $to = $job['recipient'];
      $bcc = $job['bcc'];
      $subject = $job['subject'];
      $message = $job['message'];
      $mailtype = $job['mailtype'] ?: 'html';

      // recipients may be stored as JSON for arrays
      if ($this->is_json($to)) $to = json_decode($to, true);
      if ($this->is_json($bcc)) $bcc = json_decode($bcc, true);

      try {
                $this->email->clear(true);
                $this->email->set_mailtype($mailtype);
                // annotate message with queue id for tracking on the MTA side
                $this->email->set_header('X-Queue-ID', $job['id']);
                $this->email->set_header('X-Queue-Created', $job['created_at'] ?? '');
                $this->email->from('admin@kharjamaat.in', 'Admin');
        if (is_array($to)) {
          $this->email->to($to);
        } else {
          $this->email->to($to);
        }
        if (!empty($bcc)) $this->email->bcc($bcc);
        $this->email->subject($subject);
        $this->email->message($message);

        $ok = $this->email->send();
        $debug = $this->email->print_debugger(['headers']);
        // write debug info to application logs for post-mortem
        $logLine = "[" . date('Y-m-d H:i:s') . "] Job {$job['id']} To: " . (is_array($to) ? implode(',', $to) : $to) . " Subject: " . str_replace("\n", ' ', $subject) . " Status: " . ($ok ? 'sent' : 'failed') . "\n" . $debug . "\n---\n";
        @file_put_contents(APPPATH . 'logs/emailworker.log', $logLine, FILE_APPEND | LOCK_EX);
        if ($ok) {
          $this->EmailQueueM->mark_sent($job['id']);
          echo "Sent job {$job['id']}\n";
        } else {
          $this->EmailQueueM->mark_failed($job['id'], $debug);
          echo "Failed job {$job['id']}\n";
        }
        // Keep a tiny pause to avoid overwhelming SMTP server
        usleep(100000);
      } catch (Exception $e) {
        $this->EmailQueueM->mark_failed($job['id'], $e->getMessage());
        echo "Exception for job {$job['id']}: " . $e->getMessage() . "\n";
      }
    }
  }

  private function is_json($string)
  {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
  }
}
