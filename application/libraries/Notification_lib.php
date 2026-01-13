<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification_lib
{

  protected $CI;

  public function __construct()
  {
    $this->CI = &get_instance();
    $this->CI->load->model('NotificationM');
    $this->CI->load->library('email');
  }

  public function send_email($opts)
  {
    $recipient = isset($opts['recipient']) ? $opts['recipient'] : null;
    $subject = isset($opts['subject']) ? $opts['subject'] : '';
    $body = isset($opts['body']) ? $opts['body'] : '';
    if (!empty($body) && is_string($body)) {
      $body = $this->absolutize_account_links($body);
    }

    // Apply unified email template (same look as Raza emails) unless disabled.
    $disableTemplate = !empty($opts['disable_template']);
    if (!$disableTemplate) {
      $this->CI->load->helper('email_template');
      if (!email_body_is_full_document($body)) {
        $title = $subject !== '' ? $subject : 'Notification';
        $ctaUrl = isset($opts['cta_url']) ? (string)$opts['cta_url'] : '';
        if ($ctaUrl === '') {
          $ctaUrl = base_url('accounts');
        }
        $ctaText = isset($opts['cta_text']) ? (string)$opts['cta_text'] : '';
        if ($ctaText === '') {
          $ctaText = 'Login to your account';
        }

        $body = render_generic_email_html([
          'title' => $title,
          'todayDate' => date('l, j M Y, h:i:s A'),
          'greeting' => isset($opts['greeting']) ? (string)$opts['greeting'] : 'Baad Afzalus Salaam,',
          'name' => isset($opts['recipient_name']) ? (string)$opts['recipient_name'] : '',
          'its' => isset($opts['recipient_its']) ? (string)$opts['recipient_its'] : '',
          'cardTitle' => isset($opts['card_title']) ? (string)$opts['card_title'] : '',
          'body' => $body,
          'auto_table' => true,
          'ctaUrl' => $ctaUrl,
          'ctaText' => $ctaText,
        ]);
      }
    }
    $scheduled = isset($opts['scheduled_at']) ? $opts['scheduled_at'] : null;

    $id = $this->CI->NotificationM->insert_notification([
      'channel' => 'email',
      'recipient' => $recipient,
      'recipient_type' => isset($opts['recipient_type']) ? $opts['recipient_type'] : null,
      'subject' => $subject,
      'body' => $body,
      'scheduled_at' => $scheduled
    ]);

    // If scheduled in future, skip immediate send
    if (!empty($scheduled) && strtotime($scheduled) > time()) {
      return $id;
    }

    // Attempt send now
    try {
      $this->CI->email->clear(true);
      $this->CI->email->from($this->CI->config->item('smtp_user') ?: 'admin@kharjamaat.in');
      $this->CI->email->to($recipient);
      $this->CI->email->subject($subject);
      $this->CI->email->message($body);
      $ok = $this->CI->email->send();
      if ($ok) {
        $this->CI->NotificationM->mark_sent($id);
      } else {
        $this->CI->NotificationM->increment_attempts_and_fail($id);
      }
    } catch (Exception $e) {
      $this->CI->NotificationM->increment_attempts_and_fail($id);
    }
    return $id;
  }

  /**
   * Queue a WhatsApp message — placeholder: records notification for background worker
   */
  public function send_whatsapp($opts)
  {
    $body = isset($opts['body']) ? $opts['body'] : null;
    // Convert relative account paths like "accounts/foo" or "/accounts/foo" to absolute links
    if (!empty($body) && is_string($body)) {
      $body = $this->absolutize_account_links($body);
    }

    $id = $this->CI->NotificationM->insert_notification([
      'channel' => 'whatsapp',
      'recipient' => isset($opts['recipient']) ? $opts['recipient'] : null,
      'recipient_type' => isset($opts['recipient_type']) ? $opts['recipient_type'] : null,
      'subject' => null,
      'body' => $body,
      'scheduled_at' => isset($opts['scheduled_at']) ? $opts['scheduled_at'] : null
    ]);
    return $id;
  }

  /**
   * Replace occurrences of account-relative paths with full URLs so WhatsApp makes them clickable.
   * Examples: "accounts/fmbweeklysignup" or "/accounts/fmbweeklysignup" -> "https://example.com/accounts/fmbweeklysignup"
   */
  protected function absolutize_account_links($text)
  {
    $base = rtrim(base_url('/'), '/');
    $replaced = preg_replace_callback('#(?<=\s|^)(/?accounts/[A-Za-z0-9_\/-]+)#i', function ($m) use ($base) {
      $path = ltrim($m[1], '/');
      return $base . '/' . $path;
    }, $text);
    return $replaced === null ? $text : $replaced;
  }

  /**
   * Queue an SMS message — placeholder
   */
  public function send_sms($opts)
  {
    $id = $this->CI->NotificationM->insert_notification([
      'channel' => 'sms',
      'recipient' => isset($opts['recipient']) ? $opts['recipient'] : null,
      'recipient_type' => isset($opts['recipient_type']) ? $opts['recipient_type'] : null,
      'subject' => null,
      'body' => isset($opts['body']) ? $opts['body'] : null,
      'scheduled_at' => isset($opts['scheduled_at']) ? $opts['scheduled_at'] : null
    ]);
    return $id;
  }
}
