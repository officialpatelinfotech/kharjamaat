<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ConfidentialCommentM extends CI_Model
{
    public function get_comments(int $its_id): array
    {
        return $this->db
            ->where('its_id', $its_id)
            ->order_by('created_at', 'ASC')
            ->get('confidential_comments')
            ->result_array();
    }

    public function add_comment(int $its_id, string $comment, string $created_by, string $created_by_name): bool
    {
        $data = [
            'its_id' => $its_id,
            'comment' => $comment,
            'created_by' => $created_by,
            'created_by_name' => $created_by_name,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->insert('confidential_comments', $data);
    }
}
