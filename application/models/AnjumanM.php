<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class AnjumanM extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  public function getmemberdetails($user_id)
  {
    $this->db->from("user");
    $this->db->where("ITS_ID", $user_id);
    $result = $this->db->get()->result_array();
    if ($result) {
      return $result;
    }
  }

  public function get_miqaat_by_id($miqaat_id)
  {
    if ($miqaat_id) {
  $this->db->select('m.*, ma.*, u.First_Name as member_first_name, u.Surname as member_surname, leader.First_Name as group_leader_name, leader.Surname as group_leader_surname');
  $this->db->from('miqaat m');
  $this->db->join('miqaat_assignments ma', 'ma.miqaat_id = m.id', 'left');
  $this->db->join('user u', 'u.ITS_ID = ma.member_id', 'left');
  $this->db->join('user leader', 'leader.ITS_ID = ma.group_leader_id', 'left');
  $this->db->where('m.id', $miqaat_id);
  $query = $this->db->get();

      if ($query->num_rows() > 0) {
        $rows = $query->result_array();
        $grouped = [];
        foreach ($rows as $row) {
          $group_name = isset($row['group_name']) ? $row['group_name'] : null;
          if ($group_name && $group_name !== '') {
            if (!isset($grouped[$group_name])) {
              // Copy miqaat fields and initialize assignments array
              $grouped[$group_name] = $row;
              $grouped[$group_name]['assignments'] = [];
            }
            $grouped[$group_name]['assignments'][] = $row;
          } else {
            // Not a group, add as individual
            $grouped[] = $row;
          }
        }
        // Return a single object: first group or first assignment
        $values = array_values($grouped);
        return count($values) > 0 ? $values[0] : null;
      }
      return null;
    }
  }

  public function get_user_takhmeen_details()
  {
    $this->db->select("
      u.ITS_ID,
      u.First_Name,
      u.Surname,
      u.Sector,
      u.Sub_Sector,

      fmb_t.year AS latest_takhmeen_year,
      IFNULL(fmb_t.total_amount, 0) AS latest_total_takhmeen,
      IFNULL(fmb_t.amount_paid, 0) AS latest_total_paid,
      (IFNULL(fmb_t.total_amount, 0) - IFNULL(fmb_t.amount_paid, 0)) AS latest_due,

      IFNULL(overall.total_takhmeen, 0) AS overall_total_takhmeen,
      IFNULL(overall.total_paid, 0) AS overall_total_paid,
      (IFNULL(overall.total_takhmeen, 0) - IFNULL(overall.total_paid, 0)) AS overall_due
    ");
    $this->db->from("user u");

    $this->db->join(
      "fmb_takhmeen fmb_t",
      "fmb_t.user_id = u.ITS_ID 
      AND fmb_t.year = (
        SELECT MAX(fmb_t1.year) 
        FROM fmb_takhmeen fmb_t1 
        WHERE fmb_t1.user_id = u.ITS_ID
      )",
      "left",
      false
    );

    $this->db->join(
      "(SELECT user_id, 
          SUM(total_amount) AS total_takhmeen, 
          SUM(amount_paid) AS total_paid
        FROM fmb_takhmeen
        GROUP BY user_id
      ) overall",
      "overall.user_id = u.ITS_ID",
      "left"
    );

    $this->db->where("u.Inactive_Status IS NULL 
      AND u.HOF_FM_TYPE = 'HOF' 
      AND u.Sector IS NOT NULL");
    $this->db->order_by("u.Sector, u.Sub_Sector, u.First_Name, u.Surname");

    $query = $this->db->get();
    $users = $query->result_array();

    foreach ($users as &$user) {
      $this->db->select("year, total_amount, amount_paid, (total_amount - amount_paid) AS due");
      $this->db->from("fmb_takhmeen");
      $this->db->where("user_id", $user['ITS_ID']);
      $this->db->order_by("year", "DESC");
      $user['all_takhmeen'] = $this->db->get()->result_array();
    }

    return $users;
  }

  public function update_fmb_payment($formData)
  {
    $this->db->insert("fmb_takhmeen_payments", [
      "user_id"     => $formData["user_id"],
      "payment_method"     => $formData["payment_method"],
      "amount"      => $formData["amount"],
      "payment_date" => $formData["payment_date"],
      "remarks"     => $formData["remarks"]
    ]);

    $remaining = $formData["amount"];

    $takhmeens = $this->db->select("id, total_amount, amount_paid")
      ->from("fmb_takhmeen")
      ->where("user_id", $formData["user_id"])
      ->order_by("year ASC, id ASC")
      ->get()->result_array();

    foreach ($takhmeens as $tk) {
      if ($remaining <= 0) break;

      $due = $tk['total_amount'] - $tk['amount_paid'];

      if ($due > 0) {
        $apply = min($due, $remaining);

        $this->db->set("amount_paid", "amount_paid + {$apply}", false)
          ->where("id", $tk['id'])
          ->update("fmb_takhmeen");

        $remaining -= $apply;
      }
    }

    return true;
  }

  // FMB General Contribution section
  public function get_fmbgc_by_type($type)
  {
    if ($type == 1) {
      $fmb_type = "Thaali";
    } else {
      $fmb_type = "Niyaz";
    }

    $this->db->from("fmb_general_contribution_master");
    $this->db->where("fmb_type", $fmb_type);
    return $this->db->get()->result_array();
  }

  public function validatefmbgc(
    $contri_year,
    $user_id,
    $contri_type
  ) {
    $this->db->from("fmb_general_contribution");
    $this->db->where("user_id = $user_id AND contri_year = '$contri_year' AND fmb_type = '$contri_type'");
    $result = $this->db->get()->result_array();
    if (count($result) > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function addfmbgc($data)
  {
    $result = $this->db->insert("fmb_general_contribution", $data);
    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  public function get_user_fmbgc($fmb_type)
  {
    if ($fmb_type == 1) {
      $fmb_type = "Thaali";
    } else {
      $fmb_type = "Niyaz";
    }

    $this->db->select("fmb_general_contribution.*, user.ITS_ID, user.First_Name, user.Surname");
    $this->db->from("fmb_general_contribution");
    $this->db->join("user", "user.ITS_ID = fmb_general_contribution.user_id", "left");
    $this->db->where("fmb_general_contribution.fmb_type", $fmb_type);
    return $this->db->get()->result_array();
  }
  public function updatefmbgcpayment($id, $action)
  {
    $this->db->where("id", $id);
    if ($action == 1) {
      $result = $this->db->update("fmb_general_contribution", ["payment_status" => $action, "payment_date" => date("Y-m-d")]);
    } else {
      $result = $this->db->update("fmb_general_contribution", ["payment_status" => $action, "payment_date" => NULL]);
    }
    if ($result) {
      return true;
    } else {
      return true;
    }
  }
  // FMB General Contribution section

  public function get_user_sabeel_takhmeen_details($filter_data = null)
  {
    $this->db->select("
        u.ITS_ID,
        u.First_Name,
        u.Surname,
        u.Sector,
        u.Sub_Sector,

        est_grade.grade AS establishment_grade,
        est_grade.amount AS establishment_yearly,
        ROUND(est_grade.amount / 12, 2) AS establishment_monthly,

        res_grade.grade AS residential_grade,
        res_grade.amount AS residential_monthly,
        ROUND(res_grade.amount * 12, 2) AS residential_yearly,

        st.id AS sabeel_id,
        st.year AS sabeel_year,

        IFNULL(SUM(CASE WHEN pay.type = 'establishment' THEN pay.amount ELSE 0 END), 0) AS establishment_paid,
        IFNULL(SUM(CASE WHEN pay.type = 'residential' THEN pay.amount ELSE 0 END), 0) AS residential_paid,

        (
            (SELECT IFNULL(SUM(g.amount),0) 
            FROM sabeel_takhmeen st_all
            LEFT JOIN sabeel_takhmeen_grade g 
              ON g.id = st_all.establishment_grade 
            AND g.type = 'establishment'
            WHERE st_all.user_id = u.ITS_ID)
            -
            IFNULL(SUM(CASE WHEN pay.type = 'establishment' THEN pay.amount ELSE 0 END), 0)
        ) AS establishment_due,

        (
            (SELECT IFNULL(SUM(g.amount),0) 
            FROM sabeel_takhmeen st_all
            LEFT JOIN sabeel_takhmeen_grade g 
              ON g.id = st_all.residential_grade 
            AND g.type = 'residential'
            WHERE st_all.user_id = u.ITS_ID)
            * 12
            -
            IFNULL(SUM(CASE WHEN pay.type = 'residential' THEN pay.amount ELSE 0 END), 0)
        ) AS residential_due
    ", false);

    $this->db->from("user u");

    $this->db->join(
      "sabeel_takhmeen st",
      "st.user_id = u.ITS_ID 
        AND st.year = (
          SELECT MAX(st1.year) 
          FROM sabeel_takhmeen st1 
          WHERE st1.user_id = u.ITS_ID
        )",
      "left",
      false
    );

    $this->db->join(
      "sabeel_takhmeen_grade est_grade",
      "est_grade.id = st.establishment_grade 
        AND est_grade.type = 'establishment' 
        AND est_grade.year = st.year",
      "left"
    );

    $this->db->join(
      "sabeel_takhmeen_grade res_grade",
      "res_grade.id = st.residential_grade 
        AND res_grade.type = 'residential' 
        AND res_grade.year = st.year",
      "left"
    );

    $this->db->join(
      "sabeel_takhmeen_payments pay",
      "pay.user_id = u.ITS_ID 
        AND pay.type IN ('establishment','residential')",
      "left"
    );

    if (!empty($filter_data) && !empty($filter_data["member_name"])) {
      $this->db->like("u.Full_Name", $filter_data["member_name"]);
    }

    $this->db->where("u.Inactive_Status IS NULL 
                      AND u.HOF_FM_TYPE = 'HOF' 
                      AND u.Sector IS NOT NULL");

    $this->db->group_by("
        u.ITS_ID,
        u.First_Name,
        u.Surname,
        u.Sector,
        u.Sub_Sector,
        st.id,
        st.year,
        est_grade.id,
        est_grade.grade,
        est_grade.amount,
        res_grade.id,
        res_grade.grade,
        res_grade.amount
    ");

    $this->db->order_by("u.Sector, u.Sub_Sector, u.First_Name, u.Surname");

    $query = $this->db->get();
    return $query->result_array();
  }


  public function update_sabeel_payment($formData)
  {
    $this->db->insert("sabeel_takhmeen_payments", [
      "user_id"     => $formData["user_id"],
      "payment_method"     => $formData["payment_method"],
      "type"     => $formData["type"],
      "amount"      => $formData["amount"],
      "payment_date" => $formData["payment_date"],
      "remarks"     => $formData["remarks"]
    ]);

    return true;
  }

  public function getPaymentHistory($user_id, $for)
  {
    $this->db->where('user_id', $user_id);
    $this->db->order_by('payment_date', 'DESC');
    if ($for == 1) {
      $query = $this->db->get('fmb_takhmeen_payments');
    } else {
      $query = $this->db->get('sabeel_takhmeen_payments');
    }
    return $query->result_array();
  }

  public function get_payment_details($payment_id, $for)
  {
    $this->db->select('payments.*, user.First_Name, user.Surname, user.Address');
    if ($for == 1) {
      $this->db->from('fmb_takhmeen_payments as payments');
    } else {
      $this->db->from('sabeel_takhmeen_payments as payments');
    }
    $this->db->join('user', 'user.ITS_ID = payments.user_id', 'left');
    $this->db->where('payments.id', $payment_id);
    $query = $this->db->get();

    return $query->row_array();
  }
}
