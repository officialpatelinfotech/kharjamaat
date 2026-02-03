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
    $this->CI->load->library('Whatsapp_bot_lib');
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
   * Queue a WhatsApp notification (template-based preferred).
   *
   * Supported formats:
   * - Template: ['recipient'=>..., 'template_name'=>..., 'template_language'=>'en', 'body_vars'=>[...]]
   * - Legacy text: ['recipient'=>..., 'body'=>'some text']
   */
  public function send_whatsapp($opts)
  {
    $recipient = isset($opts['recipient']) ? (string)$opts['recipient'] : '';
    $recipient = $this->CI->whatsapp_bot_lib->normalize_phone($recipient);

    $templateName = isset($opts['template_name']) ? trim((string)$opts['template_name']) : '';
    $templateLanguage = isset($opts['template_language']) ? trim((string)$opts['template_language']) : '';
    $bodyVars = isset($opts['body_vars']) && is_array($opts['body_vars']) ? array_values($opts['body_vars']) : [];

    $body = isset($opts['body']) ? $opts['body'] : null;
    if (!empty($body) && is_string($body)) {
      $body = $this->absolutize_account_links($body);
    }

    // Store whatsapp payload as JSON when template-based. Keeps the notifications table schema unchanged.
    if ($templateName !== '') {
      $storedBody = json_encode([
        'type' => 'template',
        'template_name' => $templateName,
        'template_language' => $templateLanguage,
        'body' => $bodyVars
      ]);
      if ($storedBody === false) {
        $storedBody = null;
      }
    } else {
      // Legacy: plain text body
      $storedBody = $body;
    }

    $id = $this->CI->NotificationM->insert_notification([
      'channel' => 'whatsapp',
      'recipient' => $recipient !== '' ? $recipient : null,
      'recipient_type' => isset($opts['recipient_type']) ? $opts['recipient_type'] : null,
      'subject' => null,
      'body' => $storedBody,
      'scheduled_at' => isset($opts['scheduled_at']) ? $opts['scheduled_at'] : null
    ]);
    return $id;
  }

  /**
   * Deliver a queued WhatsApp notification immediately.
   * Accepts either JSON template payload (preferred) or plain text body.
   *
   * @return array {ok, http_code, response_raw, response_json, error}
   */
  public function deliver_whatsapp($notificationRow)
  {
    $recipient = isset($notificationRow['recipient']) ? (string)$notificationRow['recipient'] : '';
    $recipient = $this->CI->whatsapp_bot_lib->normalize_phone($recipient);
    $body = isset($notificationRow['body']) ? $notificationRow['body'] : null;

    // If stored as JSON, interpret as template payload.
    if (is_string($body)) {
      $decoded = json_decode($body, true);
      if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && ($decoded['type'] ?? '') === 'template') {
        $templateName = (string)($decoded['template_name'] ?? '');
        $language = (string)($decoded['template_language'] ?? '');
        $vars = isset($decoded['body']) && is_array($decoded['body']) ? $decoded['body'] : [];
        return $this->CI->whatsapp_bot_lib->send_template($recipient, $templateName, $vars, $language !== '' ? $language : null);
      }
    }

    // Legacy plain text: attempt to send using configured text template (if provided)
    $message = is_string($body) ? $body : '';
    $this->CI->config->load('whatsapp', true);
    $textTpl = (string)$this->CI->config->item('text_template_name', 'whatsapp');
    $textLang = (string)$this->CI->config->item('text_template_language', 'whatsapp');

    if ($textTpl !== '') {
      return $this->CI->whatsapp_bot_lib->send_template($recipient, $textTpl, [$message], $textLang !== '' ? $textLang : null);
    }

    return [
      'ok' => false,
      'http_code' => 0,
      'response_raw' => '',
      'response_json' => null,
      'error' => 'No whatsapp template payload and no EXPREZBOT_WHATSAPP_TEXT_TEMPLATE_NAME configured'
    ];
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
