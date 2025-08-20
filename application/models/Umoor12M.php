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


  public function insert_raza($userId, $razaType, $data, $sabil, $fmb, $fmbtameer)
  {
    $data = array(
      'user_id' => $userId,
      'razaType' => $razaType,
      'razadata' => $data,
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
