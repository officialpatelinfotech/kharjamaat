<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NotificationM extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function insert_notification($row)
    {
        $defaults = [
            'channel' => 'email',
            'recipient' => null,
            'recipient_type' => null,
            'subject' => null,
            'body' => null,
            'status' => 'pending',
            'attempts' => 0,
            'scheduled_at' => null
        ];
        $data = array_merge($defaults, $row);
        $this->db->insert('notifications', $data);
        return $this->db->insert_id();
    }

    public function get_pending($limit = 100)
    {
        $now = date('Y-m-d H:i:s');
        $this->db->where("(scheduled_at IS NULL OR scheduled_at <= '".$now."')");
        $this->db->where('status', 'pending');
        $this->db->order_by('scheduled_at', 'ASC');
        $this->db->limit((int)$limit);
        $q = $this->db->get('notifications');
        return $q->result_array();
    }

    public function mark_sent($id)
    {
        // Treat attempts as "number of times we tried to deliver".
        // Successful deliveries should therefore have attempts >= 1.
        $this->db->set('attempts', 'attempts+1', FALSE);
        $this->db->set('status', 'sent');
        $this->db->set('sent_at', date('Y-m-d H:i:s'));
        $this->db->where('id', $id);
        $this->db->update('notifications');
        return $this->db->affected_rows() > 0;
    }

    public function increment_attempts_and_fail($id)
    {
        $this->db->set('attempts', 'attempts+1', FALSE);
        $this->db->where('id', $id);
        $this->db->update('notifications');
        // If attempts >= 3, mark failed
        $row = $this->db->where('id', $id)->get('notifications')->row_array();
        if ($row && isset($row['attempts']) && $row['attempts'] >= 3) {
            $this->db->where('id', $id)->update('notifications', ['status' => 'failed']);
        }
    }

}
