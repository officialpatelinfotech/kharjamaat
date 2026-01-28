<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class MadresaM extends CI_Model
{
  private $tableClass = 'madresa_class';
  private $tableAdmission = 'madresa_class_admission';
  private $maxStudentAge = 15;
  private $tablePayments = 'madresa_fee_payment';

  public function __construct()
  {
    parent::__construct();
  }

  public function list_students()
  {
    // Reuse same active-member logic as AdminM::get_all_members
    return $this->db
      ->select('ITS_ID, Full_Name, Age')
      ->from('user')
      ->where('Inactive_Status IS NULL')
      ->where('Sector IS NOT NULL')
      ->where('Age IS NOT NULL')
      ->where('Age <=', (int)$this->maxStudentAge)
      ->order_by('Full_Name ASC')
      ->get()->result_array();
  }

  private function get_valid_student_ids_for_assignment($itsIds)
  {
    $itsIds = is_array($itsIds) ? $itsIds : [];

    $cleanIds = [];
    foreach ($itsIds as $sid) {
      $sid = (int)$sid;
      if ($sid > 0) $cleanIds[] = $sid;
    }
    $cleanIds = array_values(array_unique($cleanIds));

    if (empty($cleanIds)) return [];

    $rows = $this->db
      ->select('ITS_ID')
      ->from('user')
      ->where('Inactive_Status IS NULL')
      ->where('Sector IS NOT NULL')
      ->where('Age IS NOT NULL')
      ->where('Age <=', (int)$this->maxStudentAge)
      ->where_in('ITS_ID', $cleanIds)
      ->get()->result_array();

    $validIds = [];
    if (!empty($rows)) {
      foreach ($rows as $r) {
        if (isset($r['ITS_ID'])) $validIds[] = (int)$r['ITS_ID'];
      }
    }
    return array_values(array_unique($validIds));
  }

  public function get_students_details($itsIds)
  {
    $itsIds = is_array($itsIds) ? $itsIds : [];

    $cleanIds = [];
    foreach ($itsIds as $sid) {
      $sid = (int)$sid;
      if ($sid > 0) $cleanIds[] = $sid;
    }
    $cleanIds = array_values(array_unique($cleanIds));

    if (empty($cleanIds)) return [];

    return $this->db
      ->select('ITS_ID, Full_Name, Age, Sector, Sub_Sector')
      ->from('user')
      ->where('Inactive_Status IS NULL')
      ->where('Sector IS NOT NULL')
      ->where('Age IS NOT NULL')
      ->where('Age <=', (int)$this->maxStudentAge)
      ->where_in('ITS_ID', $cleanIds)
      ->order_by('Full_Name ASC')
      ->get()->result_array();
  }

  public function get_admitted_student_ids($itsIds)
  {
    $itsIds = is_array($itsIds) ? $itsIds : [];

    $cleanIds = [];
    foreach ($itsIds as $sid) {
      $sid = (int)$sid;
      if ($sid > 0) $cleanIds[] = $sid;
    }
    $cleanIds = array_values(array_unique($cleanIds));

    if (empty($cleanIds)) return [];

    $rows = $this->db
      ->select('DISTINCT students_its_id AS ITS_ID', false)
      ->from($this->tableAdmission)
      ->where_in('students_its_id', $cleanIds)
      ->get()->result_array();

    $out = [];
    if (!empty($rows)) {
      foreach ($rows as $r) {
        if (!empty($r['ITS_ID'])) $out[] = (string)$r['ITS_ID'];
      }
    }
    return array_values(array_unique($out));
  }

  public function create_class($className, $hijriYear, $fees = null, $status = 'Active')
  {
    try {
      $status = trim((string)$status);
      if ($status !== 'Active' && $status !== 'Inactive') {
        $status = 'Active';
      }

      $data = [
        'name' => $className,
        'year' => (int)$hijriYear,
        'fees' => ($fees === '' || $fees === null) ? null : $fees,
        'status' => $status,
      ];

      $ok = $this->db->insert($this->tableClass, $data);
      if ($ok) {
        return ['success' => true, 'id' => $this->db->insert_id()];
      }
      return ['success' => false, 'error' => $this->db->error()];
    } catch (Exception $e) {
      return ['success' => false, 'error' => ['message' => $e->getMessage()]];
    }
  }

  public function assign_students_to_class($classId, $studentItsIds)
  {
    $classId = (int)$classId;
    if ($classId <= 0) {
      return ['success' => false, 'error' => ['message' => 'Invalid class id']];
    }

    $class = $this->get_class($classId);
    $classYear = !empty($class) && isset($class['hijri_year']) ? (int)$class['hijri_year'] : 0;
    if ($classYear <= 0) {
      return ['success' => false, 'error' => ['message' => 'Invalid class hijri year']];
    }

    $studentItsIds = is_array($studentItsIds) ? $studentItsIds : [];
    // Normalize to ints and unique
    $cleanIds = [];
    foreach ($studentItsIds as $sid) {
      $sid = (int)$sid;
      if ($sid > 0) $cleanIds[] = $sid;
    }
    $cleanIds = array_values(array_unique($cleanIds));

    if (empty($cleanIds)) {
      // For now: fresh assign (clear existing)
      $this->db->where('m_class_id', $classId)->delete($this->tableAdmission);
      return ['success' => true, 'count' => 0];
    }

    $validIds = $this->get_valid_student_ids_for_assignment($cleanIds);
    $invalidIds = array_values(array_diff($cleanIds, $validIds));
    if (!empty($invalidIds)) {
      return [
        'success' => false,
        'error' => ['message' => 'Only students aged ' . (int)$this->maxStudentAge . ' years or below can be assigned to Madresa classes.'],
      ];
    }

    // Enforce: one student can be in only one class per hijri year.
    $conflicts = $this->db
      ->select('a.students_its_id, c.name AS class_name', false)
      ->from($this->tableAdmission . ' a')
      ->join($this->tableClass . ' c', 'c.id = a.m_class_id', 'inner')
      ->where('c.year', $classYear)
      ->where('a.m_class_id <>', $classId)
      ->where_in('a.students_its_id', $validIds)
      ->get()->result_array();

    if (!empty($conflicts)) {
      $parts = [];
      foreach ($conflicts as $r) {
        $sid = isset($r['students_its_id']) ? (string)$r['students_its_id'] : '';
        $cn = isset($r['class_name']) ? (string)$r['class_name'] : '';
        if ($sid === '') continue;
        $parts[] = $cn !== '' ? ($sid . ' (' . $cn . ')') : $sid;
      }
      $parts = array_values(array_unique($parts));
      return [
        'success' => false,
        'error' => ['message' => 'A student can be assigned to only one Madresa class per Hijri year (' . $classYear . '). Conflicts: ' . implode(', ', $parts)],
      ];
    }

    // For now: fresh assign (clear existing)
    $this->db->where('m_class_id', $classId)->delete($this->tableAdmission);

    $rows = [];
    foreach ($validIds as $sid) {
      $rows[] = [
        'm_class_id' => $classId,
        'students_its_id' => $sid,
      ];
    }

    $ok = $this->db->insert_batch($this->tableAdmission, $rows);
    if ($ok === false) {
      return ['success' => false, 'error' => $this->db->error()];
    }
    return ['success' => true, 'count' => count($rows)];
  }

  public function get_class($classId)
  {
    $classId = (int)$classId;
    if ($classId <= 0) return null;
    $hasStatus = $this->db->field_exists('status', $this->tableClass);
    $statusSelect = $hasStatus ? 'c.status AS status,' : 'NULL AS status,';
    $sql = "SELECT
      c.id,
      c.name AS class_name,
      c.year AS hijri_year,
      c.fees AS fees,
      {$statusSelect}
      c.created_at,
      NULL AS updated_at
    FROM {$this->tableClass} c
    WHERE c.id = ?
    LIMIT 1";
    $q = $this->db->query($sql, [$classId]);
    return $q ? $q->row_array() : null;
  }

  public function get_class_students($classId)
  {
    $classId = (int)$classId;
    if ($classId <= 0) return [];

    // Join to user table for names
    $this->db->select('a.students_its_id AS ITS_ID, u.Full_Name');
    $this->db->from($this->tableAdmission . ' a');
    $this->db->join('user u', 'u.ITS_ID = a.students_its_id', 'left');
    $this->db->where('a.m_class_id', $classId);
    $this->db->order_by('u.Full_Name ASC');
    return $this->db->get()->result_array();
  }

  public function get_class_students_financials($classId)
  {
    $classId = (int)$classId;
    if ($classId <= 0) return [];

    $hasPaymentTable = $this->db->table_exists($this->tablePayments);

    if ($hasPaymentTable) {
      $sql = "SELECT
        a.students_its_id AS ITS_ID,
        u.Full_Name,
        COALESCE(c.fees, 0) AS amount_to_collect,
        COALESCE(pp.amount_paid, 0) AS amount_paid,
        GREATEST(COALESCE(c.fees, 0) - COALESCE(pp.amount_paid, 0), 0) AS amount_due
      FROM {$this->tableAdmission} a
      JOIN {$this->tableClass} c ON c.id = a.m_class_id
      LEFT JOIN user u ON u.ITS_ID = a.students_its_id
      LEFT JOIN (
        SELECT p.students_its_id, SUM(p.amount) AS amount_paid
        FROM {$this->tablePayments} p
        WHERE p.m_class_id = ?
        GROUP BY p.students_its_id
      ) pp ON pp.students_its_id = a.students_its_id
      WHERE a.m_class_id = ?
      ORDER BY u.Full_Name ASC";
      $q = $this->db->query($sql, [$classId, $classId]);
      return $q ? $q->result_array() : [];
    }

    $sql = "SELECT
      a.students_its_id AS ITS_ID,
      u.Full_Name,
      COALESCE(c.fees, 0) AS amount_to_collect,
      0 AS amount_paid,
      COALESCE(c.fees, 0) AS amount_due
    FROM {$this->tableAdmission} a
    JOIN {$this->tableClass} c ON c.id = a.m_class_id
    LEFT JOIN user u ON u.ITS_ID = a.students_its_id
    WHERE a.m_class_id = ?
    ORDER BY u.Full_Name ASC";
    $q = $this->db->query($sql, [$classId]);
    return $q ? $q->result_array() : [];
  }

  public function get_class_student_financials($classId, $studentItsId)
  {
    $classId = (int)$classId;
    $studentItsId = (int)$studentItsId;
    if ($classId <= 0 || $studentItsId <= 0) return null;

    $hasPaymentTable = $this->db->table_exists($this->tablePayments);

    if ($hasPaymentTable) {
      $sql = "SELECT
        a.students_its_id AS ITS_ID,
        u.Full_Name,
        COALESCE(c.fees, 0) AS amount_to_collect,
        COALESCE(pp.amount_paid, 0) AS amount_collected,
        GREATEST(COALESCE(c.fees, 0) - COALESCE(pp.amount_paid, 0), 0) AS amount_due
      FROM {$this->tableAdmission} a
      JOIN {$this->tableClass} c ON c.id = a.m_class_id
      LEFT JOIN user u ON u.ITS_ID = a.students_its_id
      LEFT JOIN (
        SELECT p.students_its_id, SUM(p.amount) AS amount_paid
        FROM {$this->tablePayments} p
        WHERE p.m_class_id = ? AND p.students_its_id = ?
        GROUP BY p.students_its_id
      ) pp ON pp.students_its_id = a.students_its_id
      WHERE a.m_class_id = ? AND a.students_its_id = ?
      LIMIT 1";
      $q = $this->db->query($sql, [$classId, $studentItsId, $classId, $studentItsId]);
      return $q ? $q->row_array() : null;
    }

    $sql = "SELECT
      a.students_its_id AS ITS_ID,
      u.Full_Name,
      COALESCE(c.fees, 0) AS amount_to_collect,
      0 AS amount_collected,
      COALESCE(c.fees, 0) AS amount_due
    FROM {$this->tableAdmission} a
    JOIN {$this->tableClass} c ON c.id = a.m_class_id
    LEFT JOIN user u ON u.ITS_ID = a.students_its_id
    WHERE a.m_class_id = ? AND a.students_its_id = ?
    LIMIT 1";
    $q = $this->db->query($sql, [$classId, $studentItsId]);
    return $q ? $q->row_array() : null;
  }

  public function create_class_payment($classId, $studentItsId, $amount, $paidOn = null, $paymentMode = null, $reference = null, $notes = null, $createdBy = null)
  {
    try {
      $classId = (int)$classId;
      $studentItsId = (int)$studentItsId;
      $amount = ($amount === '' || $amount === null) ? 0 : (float)$amount;

      if ($classId <= 0) return ['success' => false, 'error' => ['message' => 'Invalid class id']];
      if ($studentItsId <= 0) return ['success' => false, 'error' => ['message' => 'Invalid student ITS id']];
      if ($amount <= 0) return ['success' => false, 'error' => ['message' => 'Amount must be greater than 0']];

      if (!$this->db->table_exists($this->tablePayments)) {
        return ['success' => false, 'error' => ['message' => 'Payments table not found']];
      }

      // Ensure student belongs to class
      $exists = $this->db
        ->select('1', false)
        ->from($this->tableAdmission)
        ->where('m_class_id', $classId)
        ->where('students_its_id', $studentItsId)
        ->limit(1)
        ->get()->row_array();
      if (empty($exists)) {
        return ['success' => false, 'error' => ['message' => 'Student is not assigned to this class']];
      }

      $data = [
        'm_class_id' => $classId,
        'students_its_id' => $studentItsId,
        'amount' => $amount,
        'paid_on' => ($paidOn === '' ? null : $paidOn),
        'payment_mode' => ($paymentMode === '' ? null : $paymentMode),
        'reference' => ($reference === '' ? null : $reference),
        'notes' => ($notes === '' ? null : $notes),
        'created_by' => ($createdBy === '' ? null : $createdBy),
      ];

      $ok = $this->db->insert($this->tablePayments, $data);
      if ($ok) {
        return ['success' => true, 'id' => (int)$this->db->insert_id()];
      }
      return ['success' => false, 'error' => $this->db->error()];
    } catch (Exception $e) {
      return ['success' => false, 'error' => ['message' => $e->getMessage()]];
    }
  }

  public function update_class($classId, $className, $hijriYear, $fees = null, $status = 'Active')
  {
    $classId = (int)$classId;
    if ($classId <= 0) {
      return ['success' => false, 'error' => ['message' => 'Invalid class id']];
    }

    $status = trim((string)$status);
    if ($status !== 'Active' && $status !== 'Inactive') {
      $status = 'Active';
    }

    $data = [
      'name' => $className,
      'year' => (int)$hijriYear,
      'fees' => ($fees === '' || $fees === null) ? null : $fees,
      'status' => $status,
    ];

    $this->db->where('id', $classId);
    $ok = $this->db->update($this->tableClass, $data);
    if ($ok) {
      return ['success' => true];
    }
    return ['success' => false, 'error' => $this->db->error()];
  }

  public function list_classes_by_year($hijriYear, $includeNullYear = false)
  {
    $hijriYear = (int)$hijriYear;
    $includeNullYear = (bool)$includeNullYear;

    $hasStatus = $this->db->field_exists('status', $this->tableClass);
    $statusSelect = $hasStatus ? 'c.status AS status,' : 'NULL AS status,';

    $paymentTable = 'madresa_fee_payment';
    $hasPaymentTable = $this->db->table_exists($paymentTable);

    $studentCountSql = "(SELECT COUNT(*) FROM {$this->tableAdmission} a WHERE a.m_class_id = c.id)";
    $amountToCollectSql = "(COALESCE(c.fees, 0) * {$studentCountSql})";
    $amountCollectedSql = $hasPaymentTable
      ? "(COALESCE((SELECT SUM(p.amount) FROM {$paymentTable} p WHERE p.m_class_id = c.id), 0))"
      : "(0)";
    $amountDueSql = "(GREATEST({$amountToCollectSql} - {$amountCollectedSql}, 0))";

    $sql = "SELECT
      c.id,
      c.name AS class_name,
      c.year AS hijri_year,
      c.fees AS fees,
      {$statusSelect}
      c.created_at,
      NULL AS updated_at,
      {$studentCountSql} AS student_count,
      {$amountToCollectSql} AS amount_to_collect,
      {$amountCollectedSql} AS amount_collected,
      {$amountDueSql} AS amount_due
    FROM {$this->tableClass} c
    WHERE (c.year = ?" . ($includeNullYear ? " OR c.year IS NULL" : "") . ")
    ORDER BY c.id DESC";
    $query = $this->db->query($sql, [$hijriYear]);
    return $query ? $query->result_array() : [];
  }

  public function list_classes()
  {
    $paymentTable = $this->tablePayments;
    $hasPaymentTable = $this->db->table_exists($paymentTable);

    $hasStatus = $this->db->field_exists('status', $this->tableClass);
    $statusSelect = $hasStatus ? 'c.status AS status,' : 'NULL AS status,';

    $studentCountSql = "(SELECT COUNT(*) FROM {$this->tableAdmission} a WHERE a.m_class_id = c.id)";
    $amountToCollectSql = "(COALESCE(c.fees, 0) * {$studentCountSql})";
    $amountCollectedSql = $hasPaymentTable
      ? "(COALESCE((SELECT SUM(p.amount) FROM {$paymentTable} p WHERE p.m_class_id = c.id), 0))"
      : "(0)";
    $amountDueSql = "(GREATEST({$amountToCollectSql} - {$amountCollectedSql}, 0))";

    $sql = "SELECT
      c.id,
      c.name AS class_name,
      c.year AS hijri_year,
      c.fees AS fees,
      {$statusSelect}
      c.created_at,
      NULL AS updated_at,
      {$studentCountSql} AS student_count,
      {$amountToCollectSql} AS amount_to_collect,
      {$amountCollectedSql} AS amount_collected,
      {$amountDueSql} AS amount_due
    FROM {$this->tableClass} c
    ORDER BY c.id DESC";

    $query = $this->db->query($sql);
    return $query ? $query->result_array() : [];
  }

  public function delete_class($classId)
  {
    try {
      $classId = (int)$classId;
      if ($classId <= 0) {
        return ['success' => false, 'error' => ['message' => 'Invalid class id']];
      }

      $this->db->where('m_class_id', $classId)->delete($this->tableAdmission);
      $this->db->where('id', $classId);
      $ok = $this->db->delete($this->tableClass);

      if ($ok) {
        return ['success' => true];
      }
      return ['success' => false, 'error' => $this->db->error()];
    } catch (Exception $e) {
      return ['success' => false, 'error' => ['message' => $e->getMessage()]];
    }
  }

  public function get_class_financials($classId)
  {
    $classId = (int)$classId;
    if ($classId <= 0) return null;

    $hasPaymentTable = $this->db->table_exists($this->tablePayments);
    $studentCountSql = "(SELECT COUNT(*) FROM {$this->tableAdmission} a WHERE a.m_class_id = c.id)";
    $amountToCollectSql = "(COALESCE(c.fees, 0) * {$studentCountSql})";
    $amountCollectedSql = $hasPaymentTable
      ? "(COALESCE((SELECT SUM(p.amount) FROM {$this->tablePayments} p WHERE p.m_class_id = c.id), 0))"
      : "(0)";
    $amountDueSql = "(GREATEST({$amountToCollectSql} - {$amountCollectedSql}, 0))";

    $sql = "SELECT
      c.id,
      {$studentCountSql} AS student_count,
      {$amountToCollectSql} AS amount_to_collect,
      {$amountCollectedSql} AS amount_collected,
      {$amountDueSql} AS amount_due
    FROM {$this->tableClass} c
    WHERE c.id = ?
    LIMIT 1";
    $q = $this->db->query($sql, [$classId]);
    return $q ? $q->row_array() : null;
  }

  public function list_class_payments($classId, $studentItsId = null)
  {
    $classId = (int)$classId;
    if ($classId <= 0) return [];
    if (!$this->db->table_exists($this->tablePayments)) return [];

    $studentItsId = ($studentItsId === null || $studentItsId === '') ? null : (int)$studentItsId;
    if ($studentItsId !== null && $studentItsId <= 0) $studentItsId = null;

    $this->db->select('p.id, p.created_at, p.m_class_id, p.students_its_id, p.amount, p.paid_on, p.payment_mode, p.reference, p.notes, p.created_by');
    $this->db->select('u.Full_Name AS student_name');
    $this->db->from($this->tablePayments . ' p');
    $this->db->join('user u', 'u.ITS_ID = p.students_its_id', 'left');
    $this->db->where('p.m_class_id', $classId);

    if ($studentItsId !== null) {
      $this->db->where('p.students_its_id', $studentItsId);
    }

    $this->db->order_by('p.id DESC');
    return $this->db->get()->result_array();
  }

  public function get_class_payment($paymentId)
  {
    $paymentId = (int)$paymentId;
    if ($paymentId <= 0) return null;
    if (!$this->db->table_exists($this->tablePayments)) return null;

    $this->db->select('p.id, p.created_at, p.m_class_id, p.students_its_id, p.amount, p.paid_on, p.payment_mode, p.reference, p.notes, p.created_by');
    $this->db->select('u.Full_Name AS student_name');
    $this->db->from($this->tablePayments . ' p');
    $this->db->join('user u', 'u.ITS_ID = p.students_its_id', 'left');
    $this->db->where('p.id', $paymentId);
    $q = $this->db->get();
    return $q ? $q->row_array() : null;
  }

  public function get_latest_class_payment($classId)
  {
    $classId = (int)$classId;
    if ($classId <= 0) return null;
    if (!$this->db->table_exists($this->tablePayments)) return null;

    $this->db->select('p.id, p.created_at, p.m_class_id, p.students_its_id, p.amount, p.paid_on, p.payment_mode, p.reference, p.notes, p.created_by');
    $this->db->select('u.Full_Name AS student_name');
    $this->db->from($this->tablePayments . ' p');
    $this->db->join('user u', 'u.ITS_ID = p.students_its_id', 'left');
    $this->db->where('p.m_class_id', $classId);
    $this->db->order_by('p.id DESC');
    $this->db->limit(1);
    $q = $this->db->get();
    return $q ? $q->row_array() : null;
  }

  public function get_students_classwise_financials($studentItsIds)
  {
    $studentItsIds = is_array($studentItsIds) ? $studentItsIds : [];

    $cleanIds = [];
    foreach ($studentItsIds as $sid) {
      $sid = (int)$sid;
      if ($sid > 0) $cleanIds[] = $sid;
    }
    $cleanIds = array_values(array_unique($cleanIds));

    if (empty($cleanIds)) return [];

    // Safe because values are normalized to ints.
    $inClause = implode(',', $cleanIds);

    $hasPaymentTable = $this->db->table_exists($this->tablePayments);

    if ($hasPaymentTable) {
      $sql = "SELECT
        a.students_its_id AS students_its_id,
        COALESCE(u.Full_Name, '') AS student_name,
        COALESCE(c.name, '') AS class_name,
        c.year AS hijri_year,
        COALESCE(c.fees, 0) AS fees,
        COALESCE(pp.amount_paid, 0) AS amount_paid,
        GREATEST(COALESCE(c.fees, 0) - COALESCE(pp.amount_paid, 0), 0) AS amount_due
      FROM {$this->tableAdmission} a
      JOIN {$this->tableClass} c ON c.id = a.m_class_id
      LEFT JOIN user u ON u.ITS_ID = a.students_its_id
      LEFT JOIN (
        SELECT p.students_its_id, p.m_class_id, SUM(p.amount) AS amount_paid
        FROM {$this->tablePayments} p
        WHERE p.students_its_id IN ({$inClause})
        GROUP BY p.students_its_id, p.m_class_id
      ) pp ON pp.students_its_id = a.students_its_id AND pp.m_class_id = a.m_class_id
      WHERE a.students_its_id IN ({$inClause})
      ORDER BY u.Full_Name ASC, c.year DESC, c.name ASC";
      $q = $this->db->query($sql);
      return $q ? $q->result_array() : [];
    }

    $sql = "SELECT
      a.students_its_id AS students_its_id,
      COALESCE(u.Full_Name, '') AS student_name,
      COALESCE(c.name, '') AS class_name,
      c.year AS hijri_year,
      COALESCE(c.fees, 0) AS fees,
      0 AS amount_paid,
      COALESCE(c.fees, 0) AS amount_due
    FROM {$this->tableAdmission} a
    JOIN {$this->tableClass} c ON c.id = a.m_class_id
    LEFT JOIN user u ON u.ITS_ID = a.students_its_id
    WHERE a.students_its_id IN ({$inClause})
    ORDER BY u.Full_Name ASC, c.year DESC, c.name ASC";
    $q = $this->db->query($sql);
    return $q ? $q->result_array() : [];
  }

  public function get_students_financials_summary($studentItsIds)
  {
    $rows = $this->get_students_classwise_financials($studentItsIds);

    $totalFees = 0.0;
    $totalPaid = 0.0;
    $totalDue = 0.0;
    $byStudent = [];

    foreach ($rows as $r) {
      $sid = (string)($r['students_its_id'] ?? '');
      if ($sid === '') continue;

      $fees = (float)($r['fees'] ?? 0);
      $paid = (float)($r['amount_paid'] ?? 0);
      $due  = (float)($r['amount_due'] ?? 0);

      $totalFees += $fees;
      $totalPaid += $paid;
      $totalDue  += $due;

      if (!isset($byStudent[$sid])) {
        $byStudent[$sid] = [
          'students_its_id' => $sid,
          'student_name' => (string)($r['student_name'] ?? ''),
          'total_fees' => 0.0,
          'total_paid' => 0.0,
          'total_due' => 0.0,
        ];
      }

      $byStudent[$sid]['total_fees'] += $fees;
      $byStudent[$sid]['total_paid'] += $paid;
      $byStudent[$sid]['total_due'] += $due;
    }

    $byStudentList = array_values($byStudent);
    usort($byStudentList, function ($a, $b) {
      return strcmp((string)($a['student_name'] ?? ''), (string)($b['student_name'] ?? ''));
    });

    return [
      'total_fees' => $totalFees,
      'total_paid' => $totalPaid,
      'total_due' => $totalDue,
      'by_student' => $byStudentList,
      'rows_count' => is_array($rows) ? count($rows) : 0,
    ];
  }
}
