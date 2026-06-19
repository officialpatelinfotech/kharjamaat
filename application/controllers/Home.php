<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Home extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('AccountM');
  }
  public function index()
  {
    if (!empty($_SESSION['user'])) {
      $from = $this->input->get('from');
      $data['user_name'] = $_SESSION['user']['username'];
      $data["from"] = $from ? strtoupper($from) : strtoupper($data['user_name']);
      if (isset($from)) {
        // $this->load->view(strtoupper($from) . '/Header', $data);
        $this->load->view('Accounts/Header', $data);
      } else {
        $this->load->view('Accounts/Header', $data);
      }
    } else {
      $this->load->view('Home/Header');
    }
    $this->load->view('Home/Home');
  }

  public function run_db_migration()
  {
    $token = $this->input->get('token');
    if ($token !== 'kf83kds92k') {
      show_error('Unauthorized', 401);
      return;
    }

    $this->load->database();
    
    // 1. ALTER TABLE to change year column to VARCHAR(15)
    $this->db->query("ALTER TABLE miqaat_invoice MODIFY COLUMN year VARCHAR(15) NULL DEFAULT NULL");
    
    // 2. Correct all invalid or mismatched year fields using the exact same correction query
    $sql = "UPDATE miqaat_invoice i
            JOIN miqaat m ON i.miqaat_id = m.id
            LEFT JOIN hijri_calendar hc ON hc.greg_date = m.date
            SET i.year = IF(hc.hijri_date IS NOT NULL,
                            IF(hc.hijri_month_id >= 7,
                               CONCAT(SUBSTRING_INDEX(hc.hijri_date, '-', -1), '-', LPAD((CAST(SUBSTRING_INDEX(hc.hijri_date, '-', -1) AS UNSIGNED) + 1) % 100, 2, '0')),
                               CONCAT(CAST(SUBSTRING_INDEX(hc.hijri_date, '-', -1) AS UNSIGNED) - 1, '-', LPAD(CAST(SUBSTRING_INDEX(hc.hijri_date, '-', -1) AS UNSIGNED) % 100, 2, '0'))
                            ),
                            IF(MONTH(m.date) >= 7 OR MONTH(m.date) = 12 OR (MONTH(m.date) = 6 AND CAST(SUBSTRING_INDEX(m.miqaat_id, '-', 1) AS UNSIGNED) != YEAR(m.date) - 578),
                               CONCAT(SUBSTRING_INDEX(m.miqaat_id, '-', 1), '-', LPAD((CAST(SUBSTRING_INDEX(m.miqaat_id, '-', 1) AS UNSIGNED) + 1) % 100, 2, '0')),
                               CONCAT(CAST(SUBSTRING_INDEX(m.miqaat_id, '-', 1) AS UNSIGNED) - 1, '-', LPAD(CAST(SUBSTRING_INDEX(m.miqaat_id, '-', 1) AS UNSIGNED) % 100, 2, '0'))
                            )
                         )
            WHERE i.year IS NULL OR i.year != 
                  IF(hc.hijri_date IS NOT NULL,
                     IF(hc.hijri_month_id >= 7,
                        CONCAT(SUBSTRING_INDEX(hc.hijri_date, '-', -1), '-', LPAD((CAST(SUBSTRING_INDEX(hc.hijri_date, '-', -1) AS UNSIGNED) + 1) % 100, 2, '0')),
                        CONCAT(CAST(SUBSTRING_INDEX(hc.hijri_date, '-', -1) AS UNSIGNED) - 1, '-', LPAD(CAST(SUBSTRING_INDEX(hc.hijri_date, '-', -1) AS UNSIGNED) % 100, 2, '0'))
                     ),
                     IF(MONTH(m.date) >= 7 OR MONTH(m.date) = 12 OR (MONTH(m.date) = 6 AND CAST(SUBSTRING_INDEX(m.miqaat_id, '-', 1) AS UNSIGNED) != YEAR(m.date) - 578),
                        CONCAT(SUBSTRING_INDEX(m.miqaat_id, '-', 1), '-', LPAD((CAST(SUBSTRING_INDEX(m.miqaat_id, '-', 1) AS UNSIGNED) + 1) % 100, 2, '0')),
                        CONCAT(CAST(SUBSTRING_INDEX(m.miqaat_id, '-', 1) AS UNSIGNED) - 1, '-', LPAD(CAST(SUBSTRING_INDEX(m.miqaat_id, '-', 1) AS UNSIGNED) % 100, 2, '0'))
                     )
                  )";
    
    $this->db->query($sql);
    $affected_rows = $this->db->affected_rows();
    
    echo "Migration completed successfully! Affected rows: " . $affected_rows;
  }
}
