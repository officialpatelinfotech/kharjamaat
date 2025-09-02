<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Umoor12M extends CI_Model
{
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }
  function check_user($username, $password)
  {
    $sql = "SELECT * FROM `login` WHERE `username` = ? AND `password` = ? AND `active` = 1";

    $query = $this->db->query($sql, array($username, $password));

    return $query->result_array();
  }
  function check_user_exist($username)
  {
    $sql = "SELECT * FROM `user` WHERE `ITS_ID` = ? ";

    $query = $this->db->query($sql, array($username));

    return $query->result_array();
  }

  public function get_miqaat_by_its_id($user_id)
  {
    $sql = "
      SELECT 
        m.id AS miqaat_id,
        m.name AS miqaat_name,
        m.type AS miqaat_type,
        m.date AS miqaat_date,
        m.status AS miqaat_status,
        ma.assign_type, 
        ma.group_name, 
        ma.group_leader_id, 
        ANY_VALUE(gl.first_name) AS group_leader_name,
        GROUP_CONCAT(u.ITS_ID) AS member_ids,
        GROUP_CONCAT(u.first_name SEPARATOR ', ') AS member_names
      FROM `miqaat` m
      INNER JOIN `miqaat_assignments` ma 
        ON ma.miqaat_id = m.id
      LEFT JOIN `user` u 
        ON u.ITS_ID = ma.member_id
      LEFT JOIN `user` gl 
        ON gl.ITS_ID = ma.group_leader_id
      LEFT JOIN `raza` r
        ON r.miqaat_id = m.id AND r.user_id = u.ITS_ID
      WHERE r.miqaat_id IS NULL
        AND (
          (ma.assign_type = 'group' AND ma.group_leader_id = ?)
          OR
          (ma.assign_type = 'individual' AND ma.member_id = ?)
        )
      GROUP BY m.id, ma.assign_type, ma.group_name, ma.group_leader_id
      ORDER BY m.date DESC
    ";

    $query = $this->db->query($sql, [$user_id, $user_id]);
    return $query->result_array();
  }

  public function insert_raza($userId, $razaType, $data, $miqaat_id, $sabil, $fmb, $fmbtameer)
  {
    $data = array(
      'user_id' => $userId,
      'razaType' => $razaType,
      'razadata' => $data,
      'miqaat_id' => $miqaat_id,
      'sabil' => $sabil,
      'fmb' => $fmb,
      'fmbtameer' => $fmbtameer,
    );

    if (!empty($data)) {
      $this->db->insert('raza', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }
  public function update_raza($id, $razadata)
  {
    $data = array(
      'razadata' => $razadata
    );

    $this->db->where('id', $id);
    $this->db->update('raza', $data);

    return $this->db->affected_rows() > 0;
  }
  // Join miqaat table for raza details
  public function get_raza_miqaat_details($miqaat_id, $raza_id)
  {
    $sql = "SELECT m.*, r.razadata FROM miqaat m INNER JOIN raza r ON m.id = r.miqaat_id WHERE m.id = ? AND r.id = ?";
    $query = $this->db->query($sql, array($miqaat_id, $raza_id));
    return $query->row_array();
  }

  public function delete_raza($id)
  {
    $this->db->where('id', $id);
    $this->db->delete('raza');
    return $this->db->affected_rows() > 0;
  }

  public function get_razatype($umoor)
  {
    $sql = "SELECT * FROM `raza_type` WHERE `active` = 1 AND `umoor` = ?";
    $query = $this->db->query($sql, array($umoor));
    return $query->result_array();
  }


  public function get_raza_byid($id)
  {
    $sql = 'SELECT * from `raza` where  id = ? ';
    $query = $this->db->query($sql, array($id));
    return $query->result_array();
  }

  public function get_raza($user_id, $umoor)
  {
    $sql = "SELECT * FROM raza WHERE user_id = ? AND active = 1 AND razaType IN (SELECT id FROM raza_type WHERE umoor = ?) ORDER BY `time-stamp` DESC";
    $query = $this->db->query($sql, array($user_id, $umoor));
    return $query->result_array();
  }

  public function get_razatype_byid($id, $umoor)
  {
    $sql = "SELECT * FROM raza_type WHERE id = ? and active = 1 and umoor = ?";
    $query = $this->db->query($sql, array($id, $umoor));
    return $query->result_array();
  }

  public function get_user($id)
  {
    $sql = "SELECT * from `user` where  `ITS_ID`= '$id'";
    $query = $this->db->query($sql);
    return $query->result_array();
  }
}
