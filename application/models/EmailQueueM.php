<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class EmailQueueM extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function enqueue($recipient, $subject, $message, $bcc = null, $mailtype = 'html', $send_after = null)
  {
    // Ensure queued HTML emails use the shared branded template (same look as Raza submission)
    // unless the caller already provided a full HTML document.
    if ($mailtype === 'html' && is_string($message) && trim($message) !== '') {
      $CI = &get_instance();
      if ($CI) {
        $CI->load->helper('email_template');
        $CI->load->helper('url');
        if (function_exists('email_body_is_full_document') && !email_body_is_full_document($message)) {
          // Avoid repeating greeting if the message already starts with it.
          $plainStart = strtolower(trim(preg_replace('/\s+/', ' ', strip_tags(substr($message, 0, 250)))));
          $hasGreeting = (strpos($plainStart, 'baad afzalus salaam') === 0) || (strpos($plainStart, 'baad afzalus salam') === 0);

          $message = render_generic_email_html([
            'title' => (string)$subject,
            'todayDate' => date('l, j M Y, h:i:s A'),
            'greeting' => $hasGreeting ? '' : 'Baad Afzalus Salaam,',
            // Title already shows in the header; avoid duplicating it inside the card.
            'cardTitle' => '',
            'body' => $message,
            'auto_table' => true,
            'ctaUrl' => function_exists('base_url') ? base_url('accounts') : '',
            'ctaText' => 'Login to your account',
          ]);
        }
      }
    }

    $data = [
      'recipient' => is_array($recipient) ? json_encode($recipient) : $recipient,
      'bcc' => is_array($bcc) ? json_encode($bcc) : $bcc,
      'subject' => $subject,
      'message' => $message,
      'mailtype' => $mailtype,
      'send_after' => $send_after
    ];
    $this->db->insert('email_queue', $data);
    return $this->db->insert_id();
  }

  public function get_pending($limit = 50)
  {
    $now = date('Y-m-d H:i:s');
    $this->db->from('email_queue');
    $this->db->where("status = 'pending'");
    $this->db->group_start();
    $this->db->where('send_after IS NULL', null, false);
    $this->db->or_where('send_after <=', $now);
    $this->db->group_end();
    $this->db->order_by('created_at', 'ASC');
    $this->db->limit((int)$limit);
    $q = $this->db->get();
    return $q->result_array();
  }

  public function mark_sent($id)
  {
    // Mark as sent and record that we attempted delivery.
    // (Attempts being 0 is misleading in production debugging.)
    $this->db->set('attempts', 'attempts+1', false);
    $this->db->set('last_error', null);
    return $this->db->where('id', $id)->update('email_queue', ['status' => 'sent']);
  }

  public function mark_failed($id, $error)
  {
    // increment attempts and set last_error
    $this->db->set('attempts', 'attempts+1', false);
    $this->db->set('last_error', $error);
    return $this->db->where('id', $id)->update('email_queue', ['status' => 'failed']);
  }
}
