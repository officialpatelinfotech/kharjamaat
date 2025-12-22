<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simple Notification library
 * - uses CI Email for sending emails
 * - records notifications to `notifications` table via NotificationM
 * - WhatsApp/SMS are placeholders that record the notification for a worker to send
 */
class Notification_lib {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('NotificationM');
        $this->CI->load->library('email');
    }

    /**
     * Send email immediately and record notification
     * $opts: recipient (email), recipient_type, subject, body, scheduled_at (optional)
     */
    public function send_email($opts)
    {
        $recipient = isset($opts['recipient']) ? $opts['recipient'] : null;
        $subject = isset($opts['subject']) ? $opts['subject'] : '';
        $body = isset($opts['body']) ? $opts['body'] : '';
        $scheduled = isset($opts['scheduled_at']) ? $opts['scheduled_at'] : null;

        // Record first (so we have audit) with pending status
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
            $this->CI->email->from($this->CI->config->item('smtp_user') ?: 'no-reply@localhost');
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
        $id = $this->CI->NotificationM->insert_notification([
            'channel' => 'whatsapp',
            'recipient' => isset($opts['recipient']) ? $opts['recipient'] : null,
            'recipient_type' => isset($opts['recipient_type']) ? $opts['recipient_type'] : null,
            'subject' => null,
            'body' => isset($opts['body']) ? $opts['body'] : null,
            'scheduled_at' => isset($opts['scheduled_at']) ? $opts['scheduled_at'] : null
        ]);
        return $id;
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
