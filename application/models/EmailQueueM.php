<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class EmailQueueM extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function enqueue($recipient, $subject, $message, $bcc = null, $mailtype = 'html', $send_after = null)
  {
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
    return $this->db->where('id', $id)->update('email_queue');
  }
}
