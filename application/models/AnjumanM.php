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

  public function get_miqaats_by_type($type, $year)
  {
    // Get all gregorian dates for the hijri year
    $this->db->select('greg_date');
    $this->db->from('hijri_calendar');
    $this->db->like('hijri_date', '-' . $year);
    $greg_dates = $this->db->get()->result_array();
    $greg_date_list = array_column($greg_dates, 'greg_date');
    if (empty($greg_date_list)) return [];
    $this->db->select('id, name, date');
    $this->db->from('miqaat');
    $this->db->where('type', $type);
    $this->db->where_in('date', $greg_date_list);
    $query = $this->db->get();
    return $query->result_array();
  }

  // public function get_miqaat_members($miqaat_id, $assigned_to)
  // {
  //   $results = [];

  //   if (strtolower($assigned_to) === 'group') {
  //     // First, fetch the group leader ID
  //     $leader_row = $this->db
  //       ->select('group_leader_id')
  //       ->from('miqaat_assignments')
  //       ->where('miqaat_id', $miqaat_id)
  //       ->where('assign_type', 'Group')
  //       ->get()
  //       ->row_array();

  //     if (!$leader_row || empty($leader_row['group_leader_id'])) {
  //       return []; // no group leader → no members
  //     }

  //     $group_leader_id = $leader_row['group_leader_id'];

  //     // Fetch group leader details
  //     $leader = $this->db
  //       ->select('u.ITS_ID, u.Full_Name')
  //       ->from('user u')
  //       ->where('u.ITS_ID', $group_leader_id)
  //       ->get()
  //       ->row_array();

  //     if ($leader) {
  //       $leader['is_leader'] = 1;
  //       $results[] = $leader;
  //     }

  //     // Fetch group members (exclude nulls)
  //     $members = $this->db
  //       ->select('u.ITS_ID, u.Full_Name')
  //       ->from('miqaat_assignments ma')
  //       ->join('user u', 'u.ITS_ID = ma.member_id', 'inner')
  //       ->where('ma.miqaat_id', $miqaat_id)
  //       ->where('ma.group_leader_id', $group_leader_id)
  //       ->where('ma.assign_type', 'Group')
  //       ->where('ma.member_id IS NOT NULL')
  //       ->where('ma.member_id !=', $group_leader_id)
  //       ->order_by('u.Full_Name', 'ASC')
  //       ->get()
  //       ->result_array();

  //     // If members exist, merge them, else keep only leader
  //     if (!empty($members)) {
  //       foreach ($members as &$m) {
  //         $m['is_leader'] = 0;
  //       }
  //       $results = array_merge($results, $members);
  //     }
  //   } else {
  //     // For Individual / Fala ni Niyaz
  //     $results = $this->db
  //       ->select('u.ITS_ID, u.Full_Name')
  //       ->from('miqaat_assignments ma')
  //       ->join('user u', 'u.ITS_ID = ma.member_id', 'inner')
  //       ->where('ma.miqaat_id', $miqaat_id)
  //       ->where('ma.assign_type', $assigned_to)
  //       ->where('ma.member_id IS NOT NULL')
  //       ->order_by('u.Full_Name', 'ASC')
  //       ->get()
  //       ->result_array();
  //   }
  //   return $results;
  // }

  public function get_miqaat_members($miqaat_id, $assigned_to)
  {
    $results = [];

    if (strtolower($assigned_to) === 'group') {
      // First, fetch the group leader ID + group name
      $leader_row = $this->db
        ->select('group_leader_id, group_name')
        ->from('miqaat_assignments')
        ->where('miqaat_id', $miqaat_id)
        ->where('assign_type', 'Group')
        ->get()
        ->row_array();

      if (!$leader_row || empty($leader_row['group_leader_id'])) {
        return []; // no group leader → no members
      }

      $group_leader_id = $leader_row['group_leader_id'];
      $group_name = $leader_row['group_name'];

      // Fetch group leader details
      $leader = $this->db
        ->select('u.ITS_ID, u.Full_Name')
        ->from('user u')
        ->where('u.ITS_ID', $group_leader_id)
        ->get()
        ->row_array();

      if ($leader) {
        $leader['is_leader'] = 1;
        $leader['group_name'] = $group_name;
        $results[] = $leader;
      }

      // Fetch group members (exclude nulls and leader itself)
      $members = $this->db
        ->select('u.ITS_ID, u.Full_Name')
        ->from('miqaat_assignments ma')
        ->join('user u', 'u.ITS_ID = ma.member_id', 'inner')
        ->where('ma.miqaat_id', $miqaat_id)
        ->where('ma.group_leader_id', $group_leader_id)
        ->where('ma.assign_type', 'Group')
        ->where('ma.member_id IS NOT NULL')
        ->where('ma.member_id !=', $group_leader_id)
        ->order_by('u.Full_Name', 'ASC')
        ->get()
        ->result_array();

      // Attach group name + leader flag
      foreach ($members as &$m) {
        $m['is_leader'] = 0;
        $m['group_name'] = $group_name;
      }

      // Merge into results
      if (!empty($members)) {
        $results = array_merge($results, $members);
      }
    } else {
      // For Individual / Fala ni Niyaz assignments
      $results = $this->db
        ->select('u.ITS_ID, u.Full_Name')
        ->from('miqaat_assignments ma')
        ->join('user u', 'u.ITS_ID = ma.member_id', 'inner')
        ->where('ma.miqaat_id', $miqaat_id)
        ->where('ma.assign_type', $assigned_to)
        ->where('ma.member_id IS NOT NULL')
        ->order_by('u.Full_Name', 'ASC')
        ->get()
        ->result_array();
    }

    return $results;
  }

  public function get_miqaat_invoices()
  {
    return $this->db
      ->select('i.id as invoice_id, i.date, i.amount, i.description, i.miqaat_id, 
              m.name as miqaat_name, m.type as miqaat_type, m.date as miqaat_date,
                  u.ITS_ID, u.Full_Name, u.HOF_ID')
      ->from('miqaat_invoice i')
      ->join('miqaat m', 'm.id = i.miqaat_id', 'left')
      ->join('user u', 'u.ITS_ID = i.member_id', 'left')
      ->order_by('i.date', 'DESC')
      ->get()
      ->result_array();
  }

  // Pagination: get paginated invoices
  public function get_miqaat_invoices_paginated($limit, $offset)
  {
    return $this->db
      ->select('i.id as invoice_id, i.date, i.amount, i.description, i.miqaat_id, 
              m.name as miqaat_name, m.type as miqaat_type, m.date as miqaat_date,
                  u.ITS_ID, u.Full_Name, u.HOF_ID')
      ->from('miqaat_invoice i')
      ->join('miqaat m', 'm.id = i.miqaat_id', 'left')
      ->join('user u', 'u.ITS_ID = i.user_id', 'left')
      ->order_by('i.date', 'DESC')
      ->limit($limit, $offset)
      ->get()
      ->result_array();
  }

  // Pagination: get total invoice count
  public function get_miqaat_invoices_count()
  {
    return $this->db->count_all('miqaat_invoice');
  }


  public function get_miqaat_assigned_to($miqaat_id)
  {
    $this->db->distinct();
    $this->db->select('assigned_to');
    $this->db->from('miqaat');
    $this->db->where('id', $miqaat_id);
    $query = $this->db->get();
    $results = array_map(function ($row) {
      return $row['assigned_to'];
    }, $query->result_array());
    return $results;
  }

  public function create_miqaat_invoice($data)
  {
    $result = $this->db->insert("miqaat_invoice", $data);
    if ($result) {
      return $this->db->insert_id();
    } else {
      return false;
    }
  }

  // Update invoice amount by invoice_id
  public function update_miqaat_invoice_amount($invoice_id, $amount)
  {
    $this->db->where('id', $invoice_id);
    return $this->db->update('miqaat_invoice', ['amount' => $amount]);
  }

  // Delete invoice by invoice_id
  public function delete_miqaat_invoice($invoice_id)
  {
    $this->db->where('id', $invoice_id);
    return $this->db->delete('miqaat_invoice');
  }

  public function get_miqaat_payments_paginated()
  {
    return $this->db
      ->select('p.id as payment_id, p.payment_date, p.amount, p.payment_method, p.remarks, u.ITS_ID, u.Full_Name, u.HOF_ID')
      ->from('miqaat_payment p')
      ->join('user u', 'u.ITS_ID = p.user_id', 'left')
      ->order_by('p.payment_date', 'DESC')
      ->get()
      ->result_array();
  }

  public function get_miqaat_payments_count()
  {
    return $this->db->count_all('miqaat_payment');
  }

  public function get_all_members($query)
  {
    $this->db->select('ITS_ID, Full_Name');
    $this->db->from('user');
    if (!empty($query)) {
      $this->db->like('Full_Name', $query);
    }
    return $this->db->get()->result_array();
  }

  public function addmiqaatpayment($data)
  {
    $result = $this->db->insert("miqaat_payment", $data);
    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  public function update_miqaat_payment_amount($payment_id, $amount) {
    $this->db->where('id', $payment_id);
    return $this->db->update('miqaat_payment', ['amount' => $amount]);
  }

  public function delete_miqaat_payment($payment_id) {
    $this->db->where('id', $payment_id);
    return $this->db->delete('miqaat_payment');
  }
}
