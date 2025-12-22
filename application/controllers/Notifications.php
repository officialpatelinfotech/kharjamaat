<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifications extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    // This controller is intended for CLI/cron usage
    $this->load->model('NotificationM');
    $this->load->library('Notification_lib');
    $this->load->library('email');
    $this->load->model('CommonM');
    $this->config->load('notifications', TRUE);
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
          $this->email->clear(true);
          $from = $this->config->item('smtp_user') ?: 'no-reply@localhost';
          $this->email->from($from);
          $this->email->to($recipient);
          if (!empty($subject)) $this->email->subject($subject);
          $this->email->message($body);
          $ok = $this->email->send();
          if ($ok) {
            $this->NotificationM->mark_sent($id);
            echo "[{$id}] email sent\n";
          } else {
            $this->NotificationM->increment_attempts_and_fail($id);
            echo "[{$id}] email failed\n";
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

    $job = strtolower(trim((string)$job));
    echo "Scheduling job: {$job}\n";

    if ($job === 'all' || $job === 'thaali_signup') {
      $this->schedule_daily_thaali_signup();
    }
    if ($job === 'all' || $job === 'thaali_feedback') {
      $this->schedule_daily_thaali_feedback();
    }
    if ($job === 'all' || $job === 'sabeel_monthly') {
      $this->schedule_monthly_sabeel();
    }
    if ($job === 'all' || $job === 'fmb_monthly') {
      $this->schedule_monthly_fmb();
    }
    if ($job === 'all' || $job === 'corpus_weekly') {
      $this->schedule_weekly_corpus();
    }

    echo "Scheduling finished." . PHP_EOL;
  }

  protected function schedule_daily_thaali_signup()
  {
    $tpl = $this->config->item('tpl_thaali_signup', 'notifications');
    // select active members with valid mobile
    $sql = "SELECT ITS_ID, Full_Name, COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') AS mobile FROM user WHERE Inactive_Status IS NULL AND COALESCE(Sector,'') <> '' AND COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') <> ''";
    $rows = $this->db->query($sql)->result_array();
    $count = 0;
    foreach ($rows as $r) {
      $phone = preg_replace('/[^0-9+]/', '', $r['mobile']);
      if (empty($phone)) continue;
      $this->notification_lib->send_whatsapp([
        'recipient' => $phone,
        'recipient_type' => 'member',
        'body' => $tpl
      ]);
      $count++;
    }
    echo "Thaali signup reminders queued for {$count} members\n";
  }

  protected function schedule_daily_thaali_feedback()
  {
    $tpl = $this->config->item('tpl_thaali_feedback', 'notifications');
    $sql = "SELECT ITS_ID, Full_Name, COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') AS mobile FROM user WHERE Inactive_Status IS NULL AND COALESCE(Sector,'') <> '' AND COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') <> ''";
    $rows = $this->db->query($sql)->result_array();
    $count = 0;
    foreach ($rows as $r) {
      $phone = preg_replace('/[^0-9+]/', '', $r['mobile']);
      if (empty($phone)) continue;
      $this->notification_lib->send_whatsapp([
        'recipient' => $phone,
        'recipient_type' => 'member',
        'body' => $tpl
      ]);
      $count++;
    }
    echo "Thaali feedback reminders queued for {$count} members\n";
  }

  protected function schedule_monthly_sabeel()
  {
    $tpl = $this->config->item('tpl_pending_sabeel', 'notifications');
    // For now send to all active members; later refine to only pending contributors
    $sql = "SELECT ITS_ID, Full_Name, COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') AS mobile FROM user WHERE Inactive_Status IS NULL AND COALESCE(Sector,'') <> '' AND COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') <> ''";
    $rows = $this->db->query($sql)->result_array();
    $count = 0;
    foreach ($rows as $r) {
      $phone = preg_replace('/[^0-9+]/', '', $r['mobile']);
      if (empty($phone)) continue;
      $this->notification_lib->send_whatsapp([
        'recipient' => $phone,
        'recipient_type' => 'member',
        'body' => $tpl
      ]);
      $count++;
    }
    echo "Sabeel monthly reminders queued for {$count} members\n";
  }

  protected function schedule_monthly_fmb()
  {
    $tpl = $this->config->item('tpl_fmb_dues', 'notifications');
    $sql = "SELECT ITS_ID, Full_Name, COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') AS mobile FROM user WHERE Inactive_Status IS NULL AND COALESCE(Sector,'') <> '' AND COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') <> ''";
    $rows = $this->db->query($sql)->result_array();
    $count = 0;
    foreach ($rows as $r) {
      $phone = preg_replace('/[^0-9+]/', '', $r['mobile']);
      if (empty($phone)) continue;
      $this->notification_lib->send_whatsapp([
        'recipient' => $phone,
        'recipient_type' => 'member',
        'body' => $tpl
      ]);
      $count++;
    }
    echo "FMB monthly reminders queued for {$count} members\n";
  }

  protected function schedule_weekly_corpus()
  {
    $tpl = $this->config->item('tpl_corpus_fund', 'notifications');
    $sql = "SELECT DISTINCT HOF_ID, COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') AS mobile FROM user WHERE HOF_FM_TYPE = 'HOF' AND Inactive_Status IS NULL AND COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') <> ''";
    $rows = $this->db->query($sql)->result_array();
    $count = 0;
    foreach ($rows as $r) {
      $phone = preg_replace('/[^0-9+]/', '', $r['mobile']);
      if (empty($phone)) continue;
      $this->notification_lib->send_whatsapp([
        'recipient' => $phone,
        'recipient_type' => 'hof',
        'body' => $tpl
      ]);
      $count++;
    }
    echo "Corpus weekly reminders queued for {$count} HOFs\n";
  }
}
