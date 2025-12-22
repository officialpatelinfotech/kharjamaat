<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class AnjumanM extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $this->load->model("HijriCalendar");
  }

  public function getmemberdetails($user_id)
  {
    $this->db->from("user");
    $this->db->where("ITS_ID", $user_id);
    $result = $this->db->get()->result_array();

    // Fallback: explicitly fetch FM-assigned invoices under each HOF and merge
    $this->db->select("\n        u.ITS_ID,\n        u.Full_Name,\n        u.HOF_ID,\n        u.Sector,\n        u.Sub_Sector,\n        m2.id as miqaat_id,\n        m2.miqaat_id as miqaat_code,\n        m2.name as miqaat_name,\n        m2.type as miqaat_type,\n        m2.date as miqaat_date,\n        i2.id as invoice_id,\n        i2.amount,\n        i2.amount as invoice_amount,\n        i2.date as invoice_date,\n        i2.description,\n        i2.year as invoice_year,\n        i2.miqaat_type as invoice_miqaat_type,\n        fm.ITS_ID as member_its_id,\n        fm.Full_Name as member_full_name,\n        fm.Sector as member_sector,\n        fm.Sub_Sector as member_sub_sector\n      ");
    $this->db->from('user u');
    $this->db->join('user fm', 'fm.HOF_ID = u.ITS_ID', 'left');
    $this->db->join('miqaat_invoice i2', 'i2.user_id = fm.ITS_ID', 'left');
    $this->db->join('miqaat m2', 'm2.id = i2.miqaat_id', 'left');
    $this->db->where('u.HOF_FM_TYPE', 'HOF');
    $this->db->where('u.Inactive_Status IS NULL', null, false);
    $this->db->order_by('u.Full_Name ASC, i2.year DESC, i2.date DESC');
    $fmRows = $this->db->get()->result_array();

    if (!empty($fmRows)) {
      $result = array_merge($result, $fmRows);
    }
    if ($result) {
      return $result;
    }
  }

  public function get_all_approved_past_miqaats($miqaat_type)
  {
    $this->db->select("
      m.id as miqaat_index,
      m.miqaat_id,
      m.name as miqaat_name,
      m.type as miqaat_type,
      m.date as miqaat_date,
      m.assigned_to,
      m.status as miqaat_status,

      ma.id as assignment_id,
      ma.assign_type,
      ma.group_name,

      u.ITS_ID, u.First_Name, u.Surname, u.Full_Name, u.Sector, u.Sub_Sector,

      gl.ITS_ID as leader_ITS_ID, gl.Full_Name as leader_Full_Name,

      r.id as raza_index, r.raza_id, r.status as raza_status, r.`Janab-status` as janab_status
    ");
    $this->db->from("miqaat m");
    $this->db->join("miqaat_assignments ma", "ma.miqaat_id = m.id", "inner");
    $this->db->join("user u", "u.ITS_ID = ma.member_id", "left");
    $this->db->join("user gl", "gl.ITS_ID = ma.group_leader_id", "left");
    $this->db->join("raza r", "r.miqaat_id = m.id 
        AND (r.user_id = ma.member_id OR r.user_id = ma.group_leader_id)", "inner");
    $this->db->join("miqaat_invoice inv", "inv.miqaat_id = m.id AND inv.raza_id = r.id", "left");

    $this->db->where("inv.id IS NULL");
    $this->db->where("m.date < CURDATE()");
    $this->db->where("m.type", $miqaat_type);
    $this->db->where("m.assigned_to !=", "Fala ni Niyaz");
    $this->db->where("r.`Janab-status`", 1);

    $this->db->order_by("m.date ASC, u.Full_Name ASC");
    $normal_miqaats = $this->db->get()->result_array();

    foreach ($normal_miqaats as &$row) {
      $hijri = $this->HijriCalendar->get_hijri_date($row['miqaat_date']);
      if ($hijri && isset($hijri['hijri_date'])) {
        $parts = explode("-", $hijri['hijri_date']);
        $day = $parts[0] ?? '';
        $month_id = $parts[1] ?? '';
        $year = $parts[2] ?? '';
        $month_name = $this->HijriCalendar->hijri_month_name($month_id);
        $row['hijri_date'] = trim($day . ' ' . ($month_name['hijri_month'] ?? '') . ' ' . $year);
      } else {
        $row['hijri_date'] = null;
      }
    }
    unset($row);

    $this->db->select("
      m.id as miqaat_index,
      m.miqaat_id,
      m.name as miqaat_name,
      m.type as miqaat_type,
      m.date as miqaat_date,
      m.assigned_to,
      m.status as miqaat_status,

      ma.id as assignment_id,
      ma.assign_type,
      ma.group_name,

      u.ITS_ID, u.First_Name, u.Surname, u.Full_Name, u.Sector, u.Sub_Sector,

      gl.ITS_ID as leader_ITS_ID, gl.Full_Name as leader_Full_Name,

      r.id as raza_index, r.raza_id, r.status as raza_status, r.`Janab-status` as janab_status
    ");
    $this->db->from("miqaat m");
    $this->db->join("miqaat_assignments ma", "ma.miqaat_id = m.id", "inner");
    $this->db->join("user u", "u.ITS_ID = ma.member_id", "left");
    $this->db->join("user gl", "gl.ITS_ID = ma.group_leader_id", "left");
    $this->db->join("raza r", "r.miqaat_id = m.id 
      AND (r.user_id = ma.member_id OR r.user_id = ma.group_leader_id)", "inner");
    $this->db->join("miqaat_invoice inv", "inv.miqaat_id = m.id AND inv.raza_id = r.id", "left");

    $this->db->where("inv.id IS NULL");
    $this->db->where("m.date < CURDATE()");
    $this->db->where("m.type", $miqaat_type);
    $this->db->where("m.assigned_to", "Fala ni Niyaz");
    $this->db->where("r.`Janab-status`", 1);
    $this->db->order_by("m.date ASC, u.Full_Name ASC");

    $fala_ni_niyaz = $this->db->get()->result_array();

    $fala_grouped_by_year = [];

    foreach ($fala_ni_niyaz as &$row) {
      $hijri = $this->HijriCalendar->get_hijri_date($row['miqaat_date']);
      if ($hijri && !empty($hijri['hijri_date'])) {
        $parts = explode("-", $hijri['hijri_date']);
        $day = $parts[0] ?? '';
        $month_id = $parts[1] ?? '';
        $year = $parts[2] ?? '';
        $month_name = $this->HijriCalendar->hijri_month_name($month_id);
        $row['hijri_date'] = trim($day . ' ' . ($month_name['hijri_month'] ?? '') . ' ' . $year);
        $row['hijri_year'] = $year;
      } else {
        $row['hijri_date'] = null;
        $row['hijri_year'] = null;
      }

      $miqaat_type = $row['miqaat_type'];
      $hijri_year = $row['hijri_year'];

      $result = $this->db->from('miqaat_invoice')
        ->where('miqaat_type', $miqaat_type)
        ->where('year', $hijri_year)
        ->get();

      $invoices = $result->result_array();

      if (count($invoices) > 0) {
        continue;
      }

      if ($row['hijri_year']) {
        if (!isset($fala_grouped_by_year[$row['hijri_year']])) {
          $fala_grouped_by_year[$row['hijri_year']] = [
            'year'        => $row['hijri_year'],
            'assigned_to' => 'Fala ni Niyaz',
            'miqaats'     => [],
            'count'       => 0,
            'earliest_date' => $row['miqaat_date'],
            'latest_date'   => $row['miqaat_date'],
          ];
        }

        $fala_grouped_by_year[$row['hijri_year']]['miqaats'][] = $row;
        $fala_grouped_by_year[$row['hijri_year']]['count']++;

        if ($row['miqaat_date'] < $fala_grouped_by_year[$row['hijri_year']]['earliest_date']) {
          $fala_grouped_by_year[$row['hijri_year']]['earliest_date'] = $row['miqaat_date'];
        }
        if ($row['miqaat_date'] > $fala_grouped_by_year[$row['hijri_year']]['latest_date']) {
          $fala_grouped_by_year[$row['hijri_year']]['latest_date'] = $row['miqaat_date'];
        }
      }
    }
    unset($row);

    if ($miqaat_type === "Shehrullah" || $miqaat_type === "Ashara") {
      return [
        'miqaats'        => $normal_miqaats,
        'Fala_ni_Niyaz'  => array_values($fala_grouped_by_year)
      ];
    }

    return [
      'miqaats' => $normal_miqaats,
      'Fala_ni_Niyaz' => $fala_ni_niyaz
    ];
  }

  // public function get_all_member_miqaat_invoices($miqaat_type)
  // {
  //   $this->db->select("
  //       u.ITS_ID,
  //       u.Full_Name,
  //       u.HOF_ID,
  //       m.id as miqaat_id,
  //       m.miqaat_id as miqaat_code,
  //       m.name as miqaat_name,
  //       m.type as miqaat_type,
  //       m.date as miqaat_date,
  //       i.id as invoice_id,
  //       i.amount,
  //       i.date as invoice_date,
  //       i.description,
  //       i.year as invoice_year,
  //       i.miqaat_type as invoice_miqaat_type
  //   ");
  //   $this->db->from('user u');

  //   // only HOF & active
  //   $this->db->where('u.HOF_FM_TYPE', 'HOF');
  //   $this->db->where('u.Inactive_Status IS NULL', null, false);

  //   // join invoices
  //   $this->db->join('miqaat_invoice i', 'u.ITS_ID = i.user_id', 'left');

  //   // join miqaat if available
  //   $this->db->join('miqaat m', 'm.id = i.miqaat_id', 'left');

  //   // filter by type (either linked miqaat OR fala ni niyaz invoices)
  //   $this->db->where("(m.type = '$miqaat_type' OR (i.miqaat_id IS NULL AND i.miqaat_type = '$miqaat_type'))");

  //   $this->db->order_by('u.Full_Name, i.date DESC');

  //   $result = $this->db->get()->result_array();

  // $groupedMembers = [];
  // $miqaats = [];
  // // Track occurrences of invoice amounts per miqaat group
  // // Structure: [group_key][amountKey] => count
  // $miqaat_amount_counts = [];

  //   foreach ($result as $row) {
  //     $ITS_ID = $row['ITS_ID'];

  //     if (!isset($groupedMembers[$ITS_ID])) {
  //       $groupedMembers[$ITS_ID] = [
  //         'ITS_ID' => $row['ITS_ID'],
  //         'Full_Name' => $row['Full_Name'],
  //         'HOF_ID' => $row['HOF_ID'],
  //         'miqaat_invoices' => []
  //       ];
  //     }

  //     if (!empty($row['invoice_id'])) {

  //       if (!empty($row['miqaat_id'])) {
  //         $group_key   = "M#" . $row['miqaat_code'];
  //         $miqaat_id   = $row['miqaat_code'];
  //         $miqaat_name = $row['miqaat_name'];
  //         $miqaat_type_final = $row['miqaat_type'];
  //         $miqaat_date = $row['miqaat_date'];
  //         $year        = $row['invoice_year'];
  //       } else {
  //         $group_key   = $row['invoice_miqaat_type'] . " " . $row['invoice_year'];
  //         $miqaat_id   = null;
  //         $miqaat_name = "Fala ni Niyaz " . $row['invoice_year'];
  //         $miqaat_type_final = $row['invoice_miqaat_type'];
  //         $miqaat_date = null;
  //         $year        = $row['invoice_year'];
  //       }

  //       // Track amount occurrence for this group
  //       $amt = (float)$row['amount'];
  //       $amtKey = number_format($amt, 2, '.', '');
  //       if (!isset($miqaat_amount_counts[$group_key])) {
  //         $miqaat_amount_counts[$group_key] = [];
  //       }
  //       if (!isset($miqaat_amount_counts[$group_key][$amtKey])) {
  //         $miqaat_amount_counts[$group_key][$amtKey] = 0;
  //       }
  //       $miqaat_amount_counts[$group_key][$amtKey]++;

  //       $groupedMembers[$ITS_ID]['miqaat_invoices'][] = [
  //         'miqaat_group' => $group_key,
  //         'miqaat_id'    => $miqaat_id,
  //         'miqaat_name'  => $miqaat_name,
  //         'invoice_id'   => $row['invoice_id'],
  //         'amount'       => $row['amount'],
  //         'invoice_date' => $row['invoice_date'],
  //         'description'  => $row['description']
  //       ];

  //       if (!isset($miqaats[$group_key])) {
  //         $miqaats[$group_key] = [
  //           'group_key'    => $group_key,
  //           'miqaat_id'    => $miqaat_id,
  //           'miqaat_name'  => $miqaat_name,
  //           'miqaat_type'  => $miqaat_type_final,
  //           'miqaat_date'  => $miqaat_date,
  //           'year'         => $year,
  //           'amount'       => 0,
  //           'invoice_ids'  => [],
  //         ];
  //       }

  //       // Track invoice ids for group-level operations (edit/delete)
  //       $miqaats[$group_key]['invoice_ids'][] = (int)$row['invoice_id'];
  //     }
  //   }

  //   // After collecting, set each miqaat's amount to the most frequent (mode) value
  //   foreach ($miqaats as $gk => &$m) {
  //     if (isset($miqaat_amount_counts[$gk]) && !empty($miqaat_amount_counts[$gk])) {
  //       $modeAmountKey = null;
  //       $modeCount = -1;
  //       foreach ($miqaat_amount_counts[$gk] as $amountKey => $count) {
  //         if ($count > $modeCount) {
  //           $modeCount = $count;
  //           $modeAmountKey = $amountKey;
  //         } elseif ($count === $modeCount && $modeAmountKey !== null) {
  //           // Tie-breaker: pick higher amount when frequencies are equal
  //           if ((float)$amountKey > (float)$modeAmountKey) {
  //             $modeAmountKey = $amountKey;
  //           }
  //         }
  //       }
  //       $m['amount'] = (float)$modeAmountKey;
  //       // Also attach the histogram for optional UI/diagnostics
  //       $m['amount_counts'] = $miqaat_amount_counts[$gk];
  //     }
  //     // Ensure invoice_ids are unique integers
  //     if (isset($m['invoice_ids']) && is_array($m['invoice_ids'])) {
  //       $m['invoice_ids'] = array_values(array_unique(array_map('intval', $m['invoice_ids'])));
  //     }
  //   }
  //   unset($m);

  //   return [
  //     'members' => array_values($groupedMembers),
  //     'miqaats' => array_values($miqaats),
  //   ];
  // }

  public function get_all_member_miqaat_invoices($miqaat_type, $year = null)
  {
    $this->db->select("
    u.ITS_ID,
    u.Full_Name,
    u.HOF_ID,
    u.Sector,
    u.Sub_Sector,
    m.id as miqaat_id,
    m.miqaat_id as miqaat_code,
    m.name as miqaat_name,
    m.type as miqaat_type,
    m.date as miqaat_date,
    i.id as invoice_id,
    i.amount,
    i.amount as invoice_amount,
    i.date as invoice_date,
    i.description,
    i.year as invoice_year,
    i.miqaat_type as invoice_miqaat_type,
    COALESCE(r.user_id, iu.ITS_ID) as member_its_id,
    COALESCE(fm.Full_Name, iu.Full_Name) as member_full_name,
    COALESCE(fm.Sector, iu.Sector) as member_sector,
    COALESCE(fm.Sub_Sector, iu.Sub_Sector) as member_sub_sector
    ");
    $this->db->from('user u');
    // join invoices for HOF or any FM under that HOF
    $this->db->join('miqaat_invoice i', '(i.user_id = u.ITS_ID OR i.user_id IN (SELECT uf.ITS_ID FROM user uf WHERE uf.HOF_ID = u.ITS_ID))', 'left', false);

    // join miqaat if available
    $this->db->join('miqaat m', 'm.id = i.miqaat_id', 'left');
    // join the actual invoice user (HOF or direct FM) to fetch their details
    $this->db->join('user iu', 'iu.ITS_ID = i.user_id', 'left');
    // also join raza to map historical invoices (assigned to HOF) to the intended FM
    $this->db->join('raza r', 'r.id = i.raza_id', 'left');
    $this->db->join('user fm', 'fm.ITS_ID = r.user_id', 'left');

    // only HOF & active
    $this->db->where('u.HOF_FM_TYPE', 'HOF');
    $this->db->where('u.Inactive_Status IS NULL', null, false);
    // ❌ DO NOT filter invoices in SQL — otherwise HOFs without invoices vanish
    // $this->db->where("(m.type = '$miqaat_type' OR (i.miqaat_id IS NULL AND i.miqaat_type = '$miqaat_type'))");

    // Order invoices year-wise (Hijri year desc), then latest date
    $this->db->order_by('u.Full_Name ASC, i.year DESC, i.date DESC');

    $result = $this->db->get()->result_array();

    $groupedMembers = [];
    $miqaats = [];
    $miqaat_amount_counts = [];
    $addedFmInvoiceIds = [];

    foreach ($result as $row) {
      // Compute effective hijri year for filtering/display
      $effectiveYear = null;
      if (!empty($row['miqaat_id']) && !empty($row['miqaat_date'])) {
        $h = $this->HijriCalendar->get_hijri_date($row['miqaat_date']);
        if ($h && !empty($h['hijri_date'])) {
          $parts = explode('-', $h['hijri_date']);
          $effectiveYear = isset($parts[2]) ? $parts[2] : null;
        }
      } else {
        $effectiveYear = isset($row['invoice_year']) ? $row['invoice_year'] : null;
      }

      // If a year is selected, skip invoices not in that hijri year
      if (!empty($year) && (string)$effectiveYear !== (string)$year) {
        $ITS_ID = $row['ITS_ID'];
        if (!isset($groupedMembers[$ITS_ID])) {
          $groupedMembers[$ITS_ID] = [
            'ITS_ID'        => $row['ITS_ID'],
            'Full_Name'     => $row['Full_Name'],
            'HOF_ID'        => $row['HOF_ID'],
            'Sector'        => isset($row['Sector']) ? $row['Sector'] : '',
            'Sub_Sector'    => isset($row['Sub_Sector']) ? $row['Sub_Sector'] : '',
            'miqaat_invoices' => []
          ];
        }
        continue;
      }
      if (!empty($year)) {
        // Skip invoices not matching selected Hijri year
        if (isset($row['invoice_year']) && (string)$row['invoice_year'] !== (string)$year) {
          // still ensure HOF entries exist even if no invoice for year
          $ITS_ID = $row['ITS_ID'];
          if (!isset($groupedMembers[$ITS_ID])) {
            $groupedMembers[$ITS_ID] = [
              'ITS_ID'        => $row['ITS_ID'],
              'Full_Name'     => $row['Full_Name'],
              'HOF_ID'        => $row['HOF_ID'],
              'Sector'        => isset($row['Sector']) ? $row['Sector'] : '',
              'Sub_Sector'    => isset($row['Sub_Sector']) ? $row['Sub_Sector'] : '',
              'miqaat_invoices' => []
            ];
          }
          continue;
        }
      }
      $ITS_ID = $row['ITS_ID'];

      if (!isset($groupedMembers[$ITS_ID])) {
        $groupedMembers[$ITS_ID] = [
          'ITS_ID'        => $row['ITS_ID'],
          'Full_Name'     => $row['Full_Name'],
          'HOF_ID'        => $row['HOF_ID'],
          'Sector'        => isset($row['Sector']) ? $row['Sector'] : '',
          'Sub_Sector'    => isset($row['Sub_Sector']) ? $row['Sub_Sector'] : '',
          'miqaat_invoices' => []
        ];
      } else {
        // Update sector/sub-sector if not yet populated (handle case of earlier null values)
        if (empty($groupedMembers[$ITS_ID]['Sector']) && !empty($row['Sector'])) {
          $groupedMembers[$ITS_ID]['Sector'] = $row['Sector'];
        }
        if (empty($groupedMembers[$ITS_ID]['Sub_Sector']) && !empty($row['Sub_Sector'])) {
          $groupedMembers[$ITS_ID]['Sub_Sector'] = $row['Sub_Sector'];
        }
      }

      // Only consider invoices that match type
      if (
        !empty($row['invoice_id']) &&
        ($row['miqaat_type'] === $miqaat_type || $row['invoice_miqaat_type'] === $miqaat_type)
      ) {

        if (!empty($row['miqaat_id'])) {
          $group_key   = "M#" . $row['miqaat_code'];
          $miqaat_id   = $row['miqaat_code'];
          $miqaat_name = $row['miqaat_name'];
          $miqaat_type_final = $row['miqaat_type'];
          $miqaat_date = $row['miqaat_date'];
          $year        = $effectiveYear;
        } else {
          $group_key   = $row['invoice_miqaat_type'] . " " . $effectiveYear;
          $miqaat_id   = null;
          $miqaat_name = "Fala ni Niyaz " . $row['invoice_year'];
          $miqaat_type_final = $row['invoice_miqaat_type'];
          $miqaat_date = null;
          $year        = $effectiveYear;
        }

        // Decide base amount with fallbacks: invoice.amount -> invoice_amount -> miqaat_default_amount
        $amt = null;
        if (isset($row['amount']) && $row['amount'] !== '' && $row['amount'] !== null) {
          $amt = (float)$row['amount'];
        } elseif (isset($row['invoice_amount']) && $row['invoice_amount'] !== '' && $row['invoice_amount'] !== null) {
          $amt = (float)$row['invoice_amount'];
        } elseif (!empty($row['miqaat_default_amount'])) {
          $amt = (float)$row['miqaat_default_amount'];
        } else {
          $amt = 0.0;
        }
        // Track amount counts
        $amtKey = number_format($amt, 2, '.', '');
        if (!isset($miqaat_amount_counts[$group_key])) {
          $miqaat_amount_counts[$group_key] = [];
        }
        if (!isset($miqaat_amount_counts[$group_key][$amtKey])) {
          $miqaat_amount_counts[$group_key][$amtKey] = 0;
        }
        $miqaat_amount_counts[$group_key][$amtKey]++;

        // Add to HOF only if the invoice actually belongs to the HOF (i.user_id == HOF ITS_ID)
        $belongsToHOF = isset($row['member_its_id']) && (string)$row['member_its_id'] === (string)$ITS_ID;
        if ($belongsToHOF) {
          // Ensure we expose an effective Hijri year for the invoice (fallback: derive from invoice_date)
          $finalYear = $effectiveYear;
          if (empty($finalYear) && !empty($row['invoice_date'])) {
            $hFallback = $this->HijriCalendar->get_hijri_date($row['invoice_date']);
            if ($hFallback && !empty($hFallback['hijri_date'])) {
              $p = explode('-', $hFallback['hijri_date']);
              $finalYear = isset($p[2]) ? $p[2] : null;
            }
          }
          $groupedMembers[$ITS_ID]['miqaat_invoices'][] = [
            'miqaat_group' => $group_key,
            'miqaat_id'    => $miqaat_id,
            'miqaat_name'  => $miqaat_name,
            'invoice_id'   => $row['invoice_id'],
            // Populate both keys with the resolved base amount to simplify views
            'amount'       => (float)$amt,
            'invoice_amount' => (float)$amt,
            'invoice_date' => $row['invoice_date'],
            'description'  => $row['description'],
            // expose hijri invoice year for front-end filtering
            'invoice_year' => $finalYear,
            // family member details (if available via raza link)
            'member_its_id' => isset($row['member_its_id']) ? $row['member_its_id'] : null,
            'member_full_name' => isset($row['member_full_name']) ? $row['member_full_name'] : null
          ];
        }

        // Additionally: create/update an entry for the FM itself (only if invoice exists and is not the HOF)
        if (!empty($row['member_its_id']) && $row['member_its_id'] != $row['ITS_ID']) {
          $fmId = $row['member_its_id'];
          if (!isset($groupedMembers[$fmId])) {
            $groupedMembers[$fmId] = [
              'ITS_ID'        => $fmId,
              'Full_Name'     => isset($row['member_full_name']) ? $row['member_full_name'] : '',
              'HOF_ID'        => $row['HOF_ID'],
              'Sector'        => isset($row['member_sector']) ? $row['member_sector'] : (isset($row['Sector']) ? $row['Sector'] : ''),
              'Sub_Sector'    => isset($row['member_sub_sector']) ? $row['member_sub_sector'] : (isset($row['Sub_Sector']) ? $row['Sub_Sector'] : ''),
              'miqaat_invoices' => []
            ];
          }
          $finalYearFm = $effectiveYear;
          if (empty($finalYearFm) && !empty($row['invoice_date'])) {
            $hfb = $this->HijriCalendar->get_hijri_date($row['invoice_date']);
            if ($hfb && !empty($hfb['hijri_date'])) {
              $pt = explode('-', $hfb['hijri_date']);
              $finalYearFm = isset($pt[2]) ? $pt[2] : null;
            }
          }
          $groupedMembers[$fmId]['miqaat_invoices'][] = [
            'miqaat_group' => $group_key,
            'miqaat_id'    => $miqaat_id,
            'miqaat_name'  => $miqaat_name,
            'invoice_id'   => $row['invoice_id'],
            'amount'       => (float)$amt,
            'invoice_amount' => (float)$amt,
            'invoice_date' => $row['invoice_date'],
            'description'  => $row['description'],
            'invoice_year' => $finalYearFm
          ];
        }

        if (!isset($miqaats[$group_key])) {
          $miqaats[$group_key] = [
            'group_key'    => $group_key,
            'miqaat_id'    => $miqaat_id,
            'miqaat_name'  => $miqaat_name,
            'miqaat_type'  => $miqaat_type_final,
            'miqaat_date'  => $miqaat_date,
            'year'         => $year,
            'amount'       => 0,
            'invoice_ids'  => [],
          ];
        }

        $miqaats[$group_key]['invoice_ids'][] = (int)$row['invoice_id'];
      }
    }

    // Supplementary pass: ensure FM invoices are included even if the main join missed them
    $this->db->select("\n      u.ITS_ID AS HOF_ITS_ID,\n      u.Full_Name AS HOF_Full_Name,\n      u.Sector,\n      u.Sub_Sector,\n      fm.ITS_ID AS member_its_id,\n      fm.Full_Name AS member_full_name,\n      fm.Sector AS member_sector,\n      fm.Sub_Sector AS member_sub_sector,\n      m.id AS miqaat_id,\n      m.miqaat_id AS miqaat_code,\n      m.name AS miqaat_name,\n      m.type AS miqaat_type,\n      m.date AS miqaat_date,\n      i.id AS invoice_id,\n      i.amount AS invoice_amount,\n      i.date AS invoice_date,\n      i.description,\n      i.year AS invoice_year,\n      i.miqaat_type AS invoice_miqaat_type\n    ");
    $this->db->from('user u');
    $this->db->join('user fm', 'fm.HOF_ID = u.ITS_ID', 'left');
    $this->db->join('miqaat_invoice i', 'i.user_id = fm.ITS_ID', 'left');
    $this->db->join('miqaat m', 'm.id = i.miqaat_id', 'left');
    $this->db->where('u.HOF_FM_TYPE', 'HOF');
    $this->db->where('u.Inactive_Status IS NULL', null, false);
    $this->db->order_by('u.Full_Name ASC, i.year DESC, i.date DESC');
    $fmRows = $this->db->get()->result_array();
    foreach ($fmRows as $row) {
      // Effective hijri year for FM pass
      $effectiveYear = null;
      if (!empty($row['miqaat_id']) && !empty($row['miqaat_date'])) {
        $h2 = $this->HijriCalendar->get_hijri_date($row['miqaat_date']);
        if ($h2 && !empty($h2['hijri_date'])) {
          $parts2 = explode('-', $h2['hijri_date']);
          $effectiveYear = isset($parts2[2]) ? $parts2[2] : null;
        }
      } else {
        $effectiveYear = isset($row['invoice_year']) ? $row['invoice_year'] : null;
      }
      if (!empty($year) && (string)$effectiveYear !== (string)$year) {
        continue;
      }
      if (empty($row['invoice_id'])) continue;
      if (!($row['miqaat_type'] === $miqaat_type || $row['invoice_miqaat_type'] === $miqaat_type)) continue;
      $hofId = $row['HOF_ITS_ID'];
      if (!isset($groupedMembers[$hofId])) {
        $groupedMembers[$hofId] = [
          'ITS_ID'        => $hofId,
          'Full_Name'     => $row['HOF_Full_Name'],
          'HOF_ID'        => $hofId,
          'Sector'        => isset($row['Sector']) ? $row['Sector'] : '',
          'Sub_Sector'    => isset($row['Sub_Sector']) ? $row['Sub_Sector'] : '',
          'miqaat_invoices' => []
        ];
      }
      // Create FM member row
      $fmId = $row['member_its_id'];
      if (!$fmId) continue;
      if (!isset($groupedMembers[$fmId])) {
        $groupedMembers[$fmId] = [
          'ITS_ID'        => $fmId,
          'Full_Name'     => isset($row['member_full_name']) ? $row['member_full_name'] : '',
          'HOF_ID'        => $hofId,
          'Sector'        => isset($row['member_sector']) ? $row['member_sector'] : (isset($row['Sector']) ? $row['Sector'] : ''),
          'Sub_Sector'    => isset($row['member_sub_sector']) ? $row['member_sub_sector'] : (isset($row['Sub_Sector']) ? $row['Sub_Sector'] : ''),
          'miqaat_invoices' => []
        ];
      }
      // Avoid duplicate FM invoice entries
      $iid = (int)$row['invoice_id'];
      if (isset($addedFmInvoiceIds[$fmId]) && in_array($iid, $addedFmInvoiceIds[$fmId], true)) {
        continue;
      }
      $group_key = !empty($row['miqaat_id']) ? ('M#' . $row['miqaat_code']) : ($row['invoice_miqaat_type'] . ' ' . $effectiveYear);
      $miqaat_id = !empty($row['miqaat_id']) ? $row['miqaat_code'] : null;
      $miqaat_name = !empty($row['miqaat_id']) ? $row['miqaat_name'] : ('Fala ni Niyaz ' . $row['invoice_year']);
      $amount = isset($row['invoice_amount']) && $row['invoice_amount'] !== '' && $row['invoice_amount'] !== null ? (float)$row['invoice_amount'] : 0.0;
      // Ensure we include a hijri invoice year (fallback from invoice_date)
      $finalYear2 = $effectiveYear;
      if (empty($finalYear2) && !empty($row['invoice_date'])) {
        $hf = $this->HijriCalendar->get_hijri_date($row['invoice_date']);
        if ($hf && !empty($hf['hijri_date'])) {
          $p2 = explode('-', $hf['hijri_date']);
          $finalYear2 = isset($p2[2]) ? $p2[2] : null;
        }
      }
      $groupedMembers[$fmId]['miqaat_invoices'][] = [
        'miqaat_group' => $group_key,
        'miqaat_id'    => $miqaat_id,
        'miqaat_name'  => $miqaat_name,
        'invoice_id'   => $iid,
        'amount'       => $amount,
        'invoice_amount' => $amount,
        'invoice_date' => $row['invoice_date'],
        'description'  => $row['description'],
        'invoice_year' => $finalYear2
      ];
      if (!isset($addedFmInvoiceIds[$fmId])) $addedFmInvoiceIds[$fmId] = [];
      $addedFmInvoiceIds[$fmId][] = $iid;
    }

    // Set amount = most frequent
    foreach ($miqaats as $gk => &$m) {
      if (isset($miqaat_amount_counts[$gk]) && !empty($miqaat_amount_counts[$gk])) {
        $modeAmountKey = null;
        $modeCount = -1;
        foreach ($miqaat_amount_counts[$gk] as $amountKey => $count) {
          if ($count > $modeCount || ($count === $modeCount && (float)$amountKey > (float)$modeAmountKey)) {
            $modeCount = $count;
            $modeAmountKey = $amountKey;
          }
        }
        $m['amount'] = (float)$modeAmountKey;
      }
      $m['invoice_ids'] = array_values(array_unique(array_map('intval', $m['invoice_ids'] ?? [])));
    }
    unset($m);

    // Return HOFs (with or without invoices) and only those family members
    // that were explicitly added when invoices existed (created above).
    // No extra filtering here so FM entries appear only if invoices exist.
    return [
      'members' => array_values($groupedMembers),
      'miqaats' => array_values($miqaats),
    ];
  }

  public function get_all_member_miqaat_payments($miqaat_type, $include_fm = false)
  {
    // 1️⃣ Main query for invoices + totals
    $this->db->select("
      u.ITS_ID,
      u.Full_Name,
      u.HOF_ID,
      u.Sector,
      u.Sub_Sector,
        m.id as miqaat_id,
        m.miqaat_id as miqaat_code,
        m.name as miqaat_name,
        m.type as miqaat_type,
        m.date as miqaat_date,
        i.id as invoice_id,
        i.amount as invoice_amount,
        i.date as invoice_date,
        i.description,
        i.year as invoice_year,
        i.miqaat_type as invoice_miqaat_type,
        COALESCE(SUM(p.amount), 0) as paid_amount
    ");
    $this->db->from('user u');
    $this->db->where('u.HOF_FM_TYPE', 'HOF');
    $this->db->where('u.Inactive_Status IS NULL', null, false);

    $this->db->join('miqaat_invoice i', 'u.ITS_ID = i.user_id', 'left');
    $this->db->join('miqaat m', 'm.id = i.miqaat_id', 'left');
    $this->db->join('miqaat_payment p', 'p.miqaat_invoice_id = i.id', 'left');

    $this->db->group_by([
      'u.ITS_ID',
      'u.Full_Name',
      'u.HOF_ID',
      'u.Sector',
      'u.Sub_Sector',
      'm.id',
      'm.miqaat_id',
      'm.name',
      'm.type',
      'm.date',
      'i.id',
      'i.amount',
      'i.date',
      'i.description',
      'i.year',
      'i.miqaat_type'
    ]);

    $this->db->order_by('u.Full_Name, i.date DESC');
    $invoiceResult = $this->db->get()->result_array();

    // 2️⃣ Separate query: all payments per member with full details + linked invoice/miqaat
    $this->db->select("
      u.ITS_ID,
      p.id as payment_id,
      p.amount as payment_amount,
      p.payment_date,
      p.payment_method,
      p.remarks,
      i2.id as invoice_id,
      i2.amount as invoice_amount,
      i2.date as invoice_date,
      i2.year as invoice_year,
      i2.description as invoice_description,
      i2.miqaat_type as invoice_miqaat_type,
      m2.id as miqaat_id,
      m2.miqaat_id as miqaat_code,
      m2.name as miqaat_name,
      i2.description as miqaat_description,
      m2.type as miqaat_type,
      m2.date as miqaat_date
    ");
    $this->db->from('user u');
    $this->db->join('miqaat_payment p', 'p.user_id = u.ITS_ID', 'left');
    $this->db->join('miqaat_invoice i2', 'i2.id = p.miqaat_invoice_id', 'left');
    $this->db->join('miqaat m2', 'm2.id = i2.miqaat_id', 'left');
    $this->db->where('u.HOF_FM_TYPE', 'HOF');
    $this->db->where('u.Inactive_Status IS NULL', null, false);
    // Restrict to requested type (linked miqaat OR Fala ni Niyaz invoice type)
    $this->db->where('(m2.type = ' . $this->db->escape($miqaat_type) . ' OR (i2.miqaat_id IS NULL AND i2.miqaat_type = ' . $this->db->escape($miqaat_type) . '))', null, false);
    $this->db->order_by('p.id DESC, u.Full_Name ASC, p.payment_date ASC');
    $paymentRows = $this->db->get()->result_array();

    $paymentsMap = [];
    foreach ($paymentRows as $prow) {
      $uid = $prow['ITS_ID'];
      if (!isset($paymentsMap[$uid])) $paymentsMap[$uid] = [];
      if (empty($prow['payment_id'])) continue; // skip rows with no payment
      // Determine label per rule: if no miqaat_id, use invoice description; else use miqaat name
      $miqaat_label = empty($prow['miqaat_id'])
        ? ($prow['invoice_description'] ?? '')
        : ($prow['miqaat_name'] ?? '');

      $paymentsMap[$uid][] = [
        'payment_id'          => (int)$prow['payment_id'],
        'amount'              => isset($prow['payment_amount']) ? (float)$prow['payment_amount'] : 0.0,
        'date'                => $prow['payment_date'],
        'payment_method'      => $prow['payment_method'],
        'remarks'             => $prow['remarks'],
        'invoice_id'          => $prow['invoice_id'],
        'invoice_amount'      => isset($prow['invoice_amount']) ? (float)$prow['invoice_amount'] : null,
        'invoice_date'        => $prow['invoice_date'],
        'invoice_year'        => $prow['invoice_year'],
        'invoice_miqaat_type' => $prow['invoice_miqaat_type'],
        'miqaat_id'           => $prow['miqaat_id'],
        'miqaat_code'         => $prow['miqaat_code'],
        'miqaat_name'         => $miqaat_label,
        'miqaat_type'         => $prow['miqaat_type'],
        'miqaat_date'         => $prow['miqaat_date'],
      ];
    }

    // 3️⃣ Build member array: include ALL HOFs (even without invoices);
    // add invoice rows only when present and type matches.
    $groupedMembers = [];
    foreach ($invoiceResult as $row) {
      $ITS_ID = $row['ITS_ID'];

      if (!isset($groupedMembers[$ITS_ID])) {
        $groupedMembers[$ITS_ID] = [
          'ITS_ID'         => $row['ITS_ID'],
          'Full_Name'      => $row['Full_Name'],
          'HOF_ID'         => $row['HOF_ID'],
          'Sector'         => isset($row['Sector']) ? $row['Sector'] : '',
          'Sub_Sector'     => isset($row['Sub_Sector']) ? $row['Sub_Sector'] : '',
          'miqaat_invoices' => [],
          'total_invoiced' => 0,
          'total_paid'     => 0,
          'total_due'      => 0,
          'payments'       => $paymentsMap[$ITS_ID] ?? []
        ];
      }

      if (
        !empty($row['invoice_id']) &&
        ($row['miqaat_type'] === $miqaat_type || $row['invoice_miqaat_type'] === $miqaat_type)
      ) {
        if (!empty($row['miqaat_id'])) {
          $group_key   = "M#" . $row['miqaat_code'];
          $miqaat_id   = $row['miqaat_code'];
          $miqaat_name = $row['miqaat_name'];
        } else {
          $group_key   = $row['invoice_miqaat_type'] . " " . $row['invoice_year'];
          $miqaat_id   = null;
          $miqaat_name = "Fala ni Niyaz " . $row['invoice_year'];
        }

        $invoice_amount = (float)$row['invoice_amount'];
        $paid_amount    = (float)$row['paid_amount'];
        $due_amount     = $invoice_amount - $paid_amount;

        // Attach under HOF only if invoice belongs to HOF (i.user_id == HOF ITS_ID)
        // Since this payments view aggregates by HOF, guard duplicates: if invoice
        // actually belongs to an FM, skip attaching it to HOF here.
        $belongsToHOF = true; // default true when invoice user is the same as HOF
        // If miqaat is linked, we can infer the intended user via join context only loosely;
        // rely on the fact that this method joins invoices by u.ITS_ID = i.user_id.
        // So when this row exists, it should belong to HOF. Keep guard for consistency.
        if ($belongsToHOF) {
          $groupedMembers[$ITS_ID]['miqaat_invoices'][] = [
            'miqaat_group'   => $group_key,
            'miqaat_id'      => $miqaat_id,
            'miqaat_name'    => $miqaat_name,
            'invoice_id'     => $row['invoice_id'],
            'invoice_year'   => $row['invoice_year'],
            'invoice_amount' => $invoice_amount,
            'paid_amount'    => $paid_amount,
            'due_amount'     => $due_amount,
            'invoice_date'   => $row['invoice_date'],
            'description'    => $row['description']
          ];
        }

        $groupedMembers[$ITS_ID]['total_invoiced'] += $invoice_amount;
        $groupedMembers[$ITS_ID]['total_paid']    += $paid_amount;
        $groupedMembers[$ITS_ID]['total_due']     += $due_amount;
      }
    }

    // Optionally include Family Members (FM) that have invoices/payments for this type
    if ($include_fm) {
      // FM invoice rows (only where invoice exists and matches type)
      $this->db->reset_query();
      $this->db->select("\n        u.ITS_ID,\n        u.Full_Name,\n        u.HOF_ID,\n        u.Sector,\n        u.Sub_Sector,\n        m.id as miqaat_id,\n        m.miqaat_id as miqaat_code,\n        m.name as miqaat_name,\n        m.type as miqaat_type,\n        m.date as miqaat_date,\n        i.id as invoice_id,\n        i.amount as invoice_amount,\n        i.date as invoice_date,\n        i.description,\n        i.year as invoice_year,\n        i.miqaat_type as invoice_miqaat_type,\n        COALESCE(SUM(p.amount), 0) as paid_amount\n      ");
      $this->db->from('user u');
      $this->db->join('miqaat_invoice i', 'u.ITS_ID = i.user_id', 'inner'); // inner to ensure FM has invoice
      $this->db->join('miqaat m', 'm.id = i.miqaat_id', 'left');
      $this->db->join('miqaat_payment p', 'p.miqaat_invoice_id = i.id', 'left');
      $this->db->where('u.HOF_FM_TYPE', 'FM');
      $this->db->where('u.Inactive_Status IS NULL', null, false);
      // Restrict to requested type
      $this->db->where('(m.type = ' . $this->db->escape($miqaat_type) . ' OR (i.miqaat_id IS NULL AND i.miqaat_type = ' . $this->db->escape($miqaat_type) . '))', null, false);
      $this->db->group_by([
        'u.ITS_ID', 'u.Full_Name', 'u.HOF_ID', 'u.Sector', 'u.Sub_Sector',
        'm.id', 'm.miqaat_id', 'm.name', 'm.type', 'm.date',
        'i.id', 'i.amount', 'i.date', 'i.description', 'i.year', 'i.miqaat_type'
      ]);
      $this->db->order_by('u.Full_Name, i.date DESC');
      $fmInvoiceRows = $this->db->get()->result_array();

      // FM payments map
      $this->db->reset_query();
      $this->db->select("\n        u.ITS_ID,\n        p.id as payment_id,\n        p.amount as payment_amount,\n        p.payment_date,\n        p.payment_method,\n        p.remarks,\n        i2.id as invoice_id,\n        i2.amount as invoice_amount,\n        i2.date as invoice_date,\n        i2.year as invoice_year,\n        i2.description as invoice_description,\n        i2.miqaat_type as invoice_miqaat_type,\n        m2.id as miqaat_id,\n        m2.miqaat_id as miqaat_code,\n        m2.name as miqaat_name,\n        i2.description as miqaat_description,\n        m2.type as miqaat_type,\n        m2.date as miqaat_date\n      ");
      $this->db->from('user u');
      $this->db->join('miqaat_payment p', 'p.user_id = u.ITS_ID', 'left');
      $this->db->join('miqaat_invoice i2', 'i2.id = p.miqaat_invoice_id', 'left');
      $this->db->join('miqaat m2', 'm2.id = i2.miqaat_id', 'left');
      $this->db->where('u.HOF_FM_TYPE', 'FM');
      $this->db->where('u.Inactive_Status IS NULL', null, false);
      $this->db->where('(m2.type = ' . $this->db->escape($miqaat_type) . ' OR (i2.miqaat_id IS NULL AND i2.miqaat_type = ' . $this->db->escape($miqaat_type) . '))', null, false);
      $this->db->order_by('p.id DESC, u.Full_Name ASC, p.payment_date ASC');
      $fmPaymentRows = $this->db->get()->result_array();

      $fmPaymentsMap = [];
      foreach ($fmPaymentRows as $prow) {
        $uid = $prow['ITS_ID'];
        if (!isset($fmPaymentsMap[$uid])) $fmPaymentsMap[$uid] = [];
        if (empty($prow['payment_id'])) continue;
        $miqaat_label = empty($prow['miqaat_id']) ? ($prow['invoice_description'] ?? '') : ($prow['miqaat_name'] ?? '');
        // Year filter for FM payments
        $effY = null;
        if (!empty($prow['miqaat_id']) && !empty($prow['miqaat_date'])) {
          $hy = $this->HijriCalendar->get_hijri_date($prow['miqaat_date']);
          if ($hy && !empty($hy['hijri_date'])) {
            $pt = explode('-', $hy['hijri_date']);
            $effY = isset($pt[2]) ? $pt[2] : null;
          }
        } else {
          $effY = isset($prow['invoice_year']) ? $prow['invoice_year'] : null;
          if (empty($effY) && !empty($prow['invoice_date'])) {
            $hfb = $this->HijriCalendar->get_hijri_date($prow['invoice_date']);
            if ($hfb && !empty($hfb['hijri_date'])) {
              $pt2 = explode('-', $hfb['hijri_date']);
              $effY = isset($pt2[2]) ? $pt2[2] : null;
            }
          }
        }
        if (!empty($year) && (string)$effY !== (string)$year) {
          continue;
        }
        $fmPaymentsMap[$uid][] = [
          'payment_id'          => (int)$prow['payment_id'],
          'amount'              => isset($prow['payment_amount']) ? (float)$prow['payment_amount'] : 0.0,
          'date'                => $prow['payment_date'],
          'payment_method'      => $prow['payment_method'],
          'remarks'             => $prow['remarks'],
          'invoice_id'          => $prow['invoice_id'],
          'invoice_amount'      => isset($prow['invoice_amount']) ? (float)$prow['invoice_amount'] : null,
          'invoice_date'        => $prow['invoice_date'],
          'invoice_year'        => $effY,
          'invoice_miqaat_type' => $prow['invoice_miqaat_type'],
          'miqaat_id'           => $prow['miqaat_id'],
          'miqaat_code'         => $prow['miqaat_code'],
          'miqaat_name'         => $miqaat_label,
          'miqaat_type'         => $prow['miqaat_type'],
          'miqaat_date'         => $prow['miqaat_date'],
        ];
      }

      foreach ($fmInvoiceRows as $row) {
        if (empty($row['invoice_id'])) continue; // safety
        if (!($row['miqaat_type'] === $miqaat_type || $row['invoice_miqaat_type'] === $miqaat_type)) continue;
        // Year filter for FM invoices
        $effYear = null;
        if (!empty($row['miqaat_id']) && !empty($row['miqaat_date'])) {
          $h = $this->HijriCalendar->get_hijri_date($row['miqaat_date']);
          if ($h && !empty($h['hijri_date'])) {
            $pt = explode('-', $h['hijri_date']);
            $effYear = isset($pt[2]) ? $pt[2] : null;
          }
        } else {
          $effYear = isset($row['invoice_year']) ? $row['invoice_year'] : null;
        }
        if (!empty($year) && (string)$effYear !== (string)$year) {
          continue;
        }
        $ITS_ID = $row['ITS_ID'];
        if (!isset($groupedMembers[$ITS_ID])) {
          $groupedMembers[$ITS_ID] = [
            'ITS_ID'         => $row['ITS_ID'],
            'Full_Name'      => $row['Full_Name'],
            'HOF_ID'         => $row['HOF_ID'],
            'Sector'         => isset($row['Sector']) ? $row['Sector'] : '',
            'Sub_Sector'     => isset($row['Sub_Sector']) ? $row['Sub_Sector'] : '',
            'miqaat_invoices' => [],
            'total_invoiced' => 0,
            'total_paid'     => 0,
            'total_due'      => 0,
            'payments'       => $fmPaymentsMap[$ITS_ID] ?? []
          ];
        }

        if (!empty($row['miqaat_id'])) {
          $group_key   = "M#" . $row['miqaat_code'];
          $miqaat_id   = $row['miqaat_code'];
          $miqaat_name = $row['miqaat_name'];
        } else {
          $group_key   = $row['invoice_miqaat_type'] . " " . $row['invoice_year'];
          $miqaat_id   = null;
          $miqaat_name = "Fala ni Niyaz " . $row['invoice_year'];
        }

        $invoice_amount = (float)$row['invoice_amount'];
        $paid_amount    = (float)$row['paid_amount'];
        $due_amount     = $invoice_amount - $paid_amount;

        $groupedMembers[$ITS_ID]['miqaat_invoices'][] = [
          'miqaat_group'   => $group_key,
          'miqaat_id'      => $miqaat_id,
          'miqaat_name'    => $miqaat_name,
          'invoice_id'     => $row['invoice_id'],
          'invoice_year'   => $effYear,
          'invoice_amount' => $invoice_amount,
          'paid_amount'    => $paid_amount,
          'due_amount'     => $due_amount,
          'invoice_date'   => $row['invoice_date'],
          'description'    => $row['description']
        ];

        $groupedMembers[$ITS_ID]['total_invoiced'] += $invoice_amount;
        $groupedMembers[$ITS_ID]['total_paid']    += $paid_amount;
        $groupedMembers[$ITS_ID]['total_due']     += $due_amount;
      }
    }

    return ['members' => array_values($groupedMembers)];
  }


  // === Sabeel Payments (added for Anjuman parity with AdminM) ===
  public function update_sabeel_payment($formData)
  {
    // Basic validation
    if (empty($formData['user_id']) || empty($formData['type']) || empty($formData['amount'])) {
      return false;
    }
    $insert = [
      'user_id'      => $formData['user_id'],
      'payment_method' => $formData['payment_method'] ?? null,
      'type'         => $formData['type'],
      'amount'       => $formData['amount'],
      'payment_date' => $formData['payment_date'] ?? date('Y-m-d'),
      'remarks'      => $formData['remarks'] ?? null,
    ];
    $this->db->insert('sabeel_takhmeen_payments', $insert);
    return $this->db->affected_rows() > 0;
  }

  public function getPaymentHistory($user_id, $for)
  {
    if (empty($user_id)) return [];
    $this->db->where('user_id', $user_id);
    $this->db->order_by('payment_date', 'DESC');
    if ($for == 1) {
      $query = $this->db->get('fmb_takhmeen_payments');
    } else {
      $query = $this->db->get('sabeel_takhmeen_payments');
    }
    return $query->result_array();
  }
  // === End Sabeel Payments additions ===

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
    $current_hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d"));
    if ($current_hijri_date) {
      $current_hijri_date = $current_hijri_date["hijri_date"];
      $current_hijri_month = explode("-", $current_hijri_date)[1];
      $current_hijri_year = explode("-", $current_hijri_date)[2];
    } else {
      $current_hijri_date = null;
    }

    if ($current_hijri_month >= "01" && $current_hijri_month <= "08") {
      $y1 = $current_hijri_year - 1;
      $y2 = substr($current_hijri_year, -2);
      $takhmeen_year = "$y1-$y2";
    } else {
      $y1 = $current_hijri_year;
      $y2 = substr($current_hijri_year + 1, -2);
      $takhmeen_year = "$y1-$y2";
    }

    $this->db->select("
      u.ITS_ID,
      u.First_Name,
      u.Surname,
      u.Sector,
      u.Sub_Sector,
      u.Full_Name,

      fmb_t.year AS latest_takhmeen_year,
      IFNULL(fmb_t.total_amount, 0) AS latest_total_takhmeen,

      IFNULL(latest_payments.total_paid, 0) AS latest_total_paid,
      (IFNULL(fmb_t.total_amount, 0) - IFNULL(latest_payments.total_paid, 0)) AS latest_due,

      IFNULL(overall.total_takhmeen, 0) AS overall_total_takhmeen,
      IFNULL(overall.total_paid, 0) AS overall_total_paid,
      (IFNULL(overall.total_takhmeen, 0) - IFNULL(overall.total_paid, 0)) AS overall_due
    ");
    $this->db->from("user u");

    $this->db->join(
      "fmb_takhmeen fmb_t",
      "fmb_t.user_id = u.ITS_ID 
        AND fmb_t.year = " . $this->db->escape($takhmeen_year),
      "left",
      false
    );
    $this->db->join(
      "(SELECT user_id, payment_year, SUM(amount) AS total_paid
          FROM (
            SELECT p.user_id, f.year AS payment_year, p.amount
            FROM fmb_takhmeen f
            JOIN fmb_takhmeen_payments p 
              ON p.user_id = f.user_id
              AND f.year = f.year
          ) t
          GROUP BY user_id, payment_year
        ) latest_payments",
      "latest_payments.user_id = u.ITS_ID AND latest_payments.payment_year = fmb_t.year",
      "left"
    );
    $this->db->join(
      "(SELECT f.user_id, 
          SUM(f.total_amount) AS total_takhmeen,
          IFNULL(p.total_paid, 0) AS total_paid
        FROM fmb_takhmeen f
        LEFT JOIN (
          SELECT user_id, SUM(amount) AS total_paid
          FROM fmb_takhmeen_payments
          GROUP BY user_id
        ) p ON p.user_id = f.user_id
        GROUP BY f.user_id
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

    // Get all takhmeens and payments, then allocate payments from oldest to newest year
    foreach ($users as &$user) {
  // Fetch all takhmeen years for the user, oldest first (ASC)
  $this->db->select("year, total_amount, remark");
      $this->db->from("fmb_takhmeen");
      $this->db->where("user_id", $user['ITS_ID']);
      $this->db->order_by("year", "ASC");
      $takhmeens = $this->db->get()->result_array();

      // Fetch all payments for the user, oldest first
      $this->db->select("amount");
      $this->db->from("fmb_takhmeen_payments");
      $this->db->where("user_id", $user['ITS_ID']);
      $this->db->order_by("payment_date", "ASC");
      $payments = $this->db->get()->result_array();

      $total_payment = 0;
      foreach ($payments as $p) {
        $total_payment += $p['amount'];
      }

      $user['all_takhmeen'] = [];
      foreach ($takhmeens as $t) {
        $paid = min($t['total_amount'], $total_payment);
        $due = $t['total_amount'] - $paid;
        $user['all_takhmeen'][] = [
          'year' => $t['year'],
          'total_amount' => $t['total_amount'],
          'total_paid' => $paid,
          'due' => $due,
          'remark' => isset($t['remark']) ? $t['remark'] : null,
        ];
        $total_payment -= $paid;
        if ($total_payment < 0) $total_payment = 0;
      }
      // Reverse for display: latest to oldest
      $user['all_takhmeen'] = array_reverse($user['all_takhmeen']);
    }

    return $users;
  }

  public function update_fmb_payment($formData)
  {
    // Calculate total due for this user
    $user_id = $formData["user_id"];
    $amount = $formData["amount"];

    // Get all takhmeen
    $this->db->select("year, total_amount");
    $this->db->from("fmb_takhmeen");
    $this->db->where("user_id", $user_id);
    $takhmeens = $this->db->get()->result_array();

    // Get all payments
    $this->db->select("amount");
    $this->db->from("fmb_takhmeen_payments");
    $this->db->where("user_id", $user_id);
    $payments = $this->db->get()->result_array();

    $total_takhmeen = 0;
    foreach ($takhmeens as $t) {
      $total_takhmeen += $t['total_amount'];
    }
    $total_paid = 0;
    foreach ($payments as $p) {
      $total_paid += $p['amount'];
    }
    $total_due = $total_takhmeen - $total_paid;

    if ($amount <= $total_due) {
      // Insert new payment
      $this->db->insert("fmb_takhmeen_payments", [
        "user_id"       => $formData["user_id"],
        "payment_method" => $formData["payment_method"],
        "amount"        => $formData["amount"],
        "payment_date"  => $formData["payment_date"],
        "remarks"       => $formData["remarks"]
      ]);
      return true;
    } else {
      // Payment exceeds due, do not insert
      return false;
    }
  }

  public function delete_takhmeen_payment($payment_id)
  {
    $this->db->where("id", $payment_id);
    return $this->db->delete("fmb_takhmeen_payments");
  }

  // FMB General Contribution section
  public function search_members($query)
  {
    $this->db->select('ITS_ID, Full_Name');
    $this->db->from('user');
    if (!empty($query)) {
      $this->db->like('Full_Name', $query);
    }
    $this->db->where('HOF_FM_TYPE', 'HOF');
    $this->db->where("Inactive_Status IS NULL AND Sector IS NOT NULL");
    $this->db->order_by('Full_Name', 'ASC');
    $this->db->limit(10);
    return $this->db->get()->result_array();
  }

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
    // Use subquery aggregation to remain compatible with ONLY_FULL_GROUP_BY
    $this->db->select('gc.*, u.ITS_ID, u.Full_Name, IFNULL(paid.total_received,0) AS total_received, (gc.amount - IFNULL(paid.total_received,0)) AS balance_due', false);
    $this->db->from('fmb_general_contribution gc');
    $this->db->join('user u', 'u.ITS_ID = gc.user_id', 'left');
    $this->db->join('(SELECT fmbgc_id, SUM(amount) AS total_received FROM fmb_general_contribution_payments GROUP BY fmbgc_id) paid', 'paid.fmbgc_id = gc.id', 'left');
    $this->db->where('gc.fmb_type', $fmb_type);
    $this->db->order_by('gc.created_at', 'DESC');
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

  /**
   * Record (or overwrite) payment details for a general contribution invoice.
   * Expects keys: id, payment_method, payment_received_amount, payment_date, payment_remarks, payment_status
   */
  public function record_fmbgc_payment($data)
  {
    // Deprecated single-row update approach. Map to new insert logic expecting multi-payment architecture.
    if (!isset($data['fmbgc_id'])) {
      return false;
    }
    $result = $this->insert_fmbgc_payment(
      $data['fmbgc_id'],
      $data['user_id'] ?? null,
      $data['amount'] ?? ($data['payment_received_amount'] ?? 0),
      $data['payment_method'] ?? null,
      $data['payment_date'] ?? date('Y-m-d'),
      $data['remarks'] ?? ($data['payment_remarks'] ?? null)
    );
    return $result['success'] ?? false;
  }
  // FMB General Contribution section

  public function get_user_sabeel_takhmeen_details($filter_data = null)
  {
    // Parity with AdminM::get_user_sabeel_takhmeen_details
    $current_hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d"));
    if ($current_hijri_date) {
      $current_hijri_date = $current_hijri_date["hijri_date"];
      $current_hijri_month = explode("-", $current_hijri_date)[1];
      $current_hijri_year = explode("-", $current_hijri_date)[2];
    } else {
      $current_hijri_date = null;
      $current_hijri_month = null;
      $current_hijri_year = null;
    }

  // Coalesce grade amounts to 0 to avoid NULL values causing arithmetic issues
  $this->db->select("
    u.ITS_ID,
    u.First_Name,
    u.Surname,
    u.Full_Name,
    u.Sector,
    u.Sub_Sector,

    st.id AS takhmeen_id,
    st.year AS takhmeen_year,

    est_grade.grade AS establishment_grade,
    COALESCE(est_grade.amount, 0) AS establishment_amount,

    res_grade.grade AS residential_grade,
    COALESCE(res_grade.amount, 0) AS residential_amount,
    COALESCE(res_grade.yearly_amount, 0) AS residential_yearly_amount,
  ");
    $this->db->from("user u");
    $this->db->join("sabeel_takhmeen st", "st.user_id = u.ITS_ID", "left");
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
    // Removed direct join to payments because it duplicated each payment across all years
    // for the user, leading to over-counting. We'll aggregate & allocate payments separately
    // after constructing the takhmeen year rows.

    if (!empty($filter_data["member_name"])) {
      $this->db->like("u.Full_Name", $filter_data["member_name"]);
    }
    if (!empty($filter_data["its_id"])) {
      // exact match on ITS
      $this->db->where("u.ITS_ID", $filter_data["its_id"]);
    }

    $this->db->where("u.Inactive_Status IS NULL 
                      AND u.HOF_FM_TYPE = 'HOF' 
                      AND u.Sector IS NOT NULL");

    $this->db->order_by("u.Sector, u.Sub_Sector, u.First_Name, u.Surname, st.year DESC");

    $rows = $this->db->get()->result_array();

    // Flags: by default, require a takhmeen for the current financial (Hijri) year
    $requireCurrentYear = isset($filter_data['require_current_year']) ? (bool)$filter_data['require_current_year'] : true;
    // Optional: allow falling back to latest year when current-year missing (default false)
    $allowLatestWhenMissing = isset($filter_data['allow_latest_when_missing']) ? (bool)$filter_data['allow_latest_when_missing'] : false;

    $users = [];
    $userIds = [];
    foreach ($rows as $row) {
      $itsId = $row['ITS_ID'];
      if (!isset($users[$itsId])) {
        $users[$itsId] = [
          'ITS_ID' => $row['ITS_ID'],
          'Full_Name' => $row['Full_Name'],
          'Sector' => $row['Sector'],
          'Sub_Sector' => $row['Sub_Sector'],
          'First_Name' => $row['First_Name'],
          'Surname' => $row['Surname'],
          // We'll build takhmeens via an index first to avoid duplicates caused by payment join
          '_takhmeens_index' => [],
          'takhmeens' => [],
          'current_year_takhmeen' => null,
          'hijri_year' => null,
        ];
        $userIds[] = $itsId;
      }

      if (empty($row['takhmeen_id'])) {
        continue; // No takhmeen record for this row
      }

      // Determine the logical current fiscal (Hijri) year string once per loop
      if (isset($filter_data['year']) && !empty($filter_data['year'])) {
        $takhmeen_year_current = $filter_data['year'];
      } else {
        if ($current_hijri_month >= "01" && $current_hijri_month <= "08") {
          $y1 = $current_hijri_year - 1;
          $y2 = substr($current_hijri_year, -2);
          $takhmeen_year_current = "$y1-$y2";
        } else if ($current_hijri_month >= "09" && $current_hijri_month <= "12") {
          $y1 = $current_hijri_year;
          $y2 = substr($current_hijri_year + 1, -2);
          $takhmeen_year_current = "$y1-$y2";
        } else {
          $takhmeen_year_current = $row['takhmeen_year'];
        }
      }
      $users[$itsId]['hijri_year'] = $takhmeen_year_current;

      $takhmeenId = $row['takhmeen_id'];
      // If this takhmeen (year) not yet indexed, create the base entry
      if (!isset($users[$itsId]['_takhmeens_index'][$takhmeenId])) {
        // Normalize amounts to float for safe calculations
        $est_amount = isset($row['establishment_amount']) ? (float)$row['establishment_amount'] : 0.0;
        $res_amount = isset($row['residential_amount']) ? (float)$row['residential_amount'] : 0.0;
        $res_yearly_amount = isset($row['residential_yearly_amount']) ? (float)$row['residential_yearly_amount'] : 0.0;

        $users[$itsId]['_takhmeens_index'][$takhmeenId] = [
          'id' => $takhmeenId,
          'year' => $row['takhmeen_year'],
          'establishment' => [
            'grade' => $row['establishment_grade'],
            'yearly' => $est_amount,
            'monthly' => $est_amount ? round($est_amount / 12, 2) : 0,
            'paid' => 0,
            'due' => 0,
          ],
          'residential' => [
            'grade' => $row['residential_grade'],
            'monthly' => $res_amount,
            'yearly' => $res_yearly_amount,
            'paid' => 0,
            'due' => 0,
          ]
        ];
      }

      $tRef =& $users[$itsId]['_takhmeens_index'][$takhmeenId];

      // (Payments removed from this phase)

      // Mark current year takhmeen (only once; payments will be allocated later)
      if ($tRef['year'] == $takhmeen_year_current) {
        $users[$itsId]['current_year_takhmeen'] = $tRef;
      }
    }

    // Determine allocation order preference (default oldest-first). Allow override via $filter_data['allocation_order'] = 'current-first'
    $allocationOrder = isset($filter_data['allocation_order']) && $filter_data['allocation_order'] === 'current-first'
      ? 'current-first'
      : 'oldest-first';

    // === Aggregate & allocate payments per user and type (FIFO oldest-first or current-first) ===
    if (!empty($userIds)) {
      $this->db->select('user_id, type, SUM(amount) AS total_amount');
      $this->db->from('sabeel_takhmeen_payments');
      $this->db->where_in('user_id', $userIds);
      $this->db->where_in('type', ['establishment','residential']);
      $this->db->group_by(['user_id','type']);
      $paymentSums = $this->db->get()->result_array();

      // Map: payments[user_id][type] = total amount
      $payments = [];
      foreach ($paymentSums as $ps) {
        $uid = $ps['user_id'];
        if (!isset($payments[$uid])) $payments[$uid] = ['establishment' => 0.0, 'residential' => 0.0];
        $payments[$uid][$ps['type']] = (float)$ps['total_amount'];
      }

      // Allocate payments oldest -> newest year per type
      foreach ($users as $uid => &$uData) {
        $estRemain = isset($payments[$uid]) ? $payments[$uid]['establishment'] : 0.0;
        $resRemain = isset($payments[$uid]) ? $payments[$uid]['residential'] : 0.0;
        if (empty($uData['_takhmeens_index'])) continue;

        // Build year list for allocation (ascending for oldest-first, descending for current-first)
        $yearRows = array_values($uData['_takhmeens_index']);
        usort($yearRows, function($a,$b){ return strcmp($a['year'],$b['year']); }); // ascending initially
        if ($allocationOrder === 'current-first') {
          $yearRows = array_reverse($yearRows); // newest first
        }

        foreach ($yearRows as &$yr) {
          // Establishment allocation
          $allocEst = min($yr['establishment']['yearly'], $estRemain);
          $yr['establishment']['paid'] = $allocEst;
          $yr['establishment']['due']  = max(0, $yr['establishment']['yearly'] - $allocEst);
          $estRemain -= $allocEst;
          if ($estRemain < 0) $estRemain = 0;

          // Residential allocation
          $allocRes = min($yr['residential']['yearly'], $resRemain);
          $yr['residential']['paid'] = $allocRes;
          $yr['residential']['due']  = max(0, $yr['residential']['yearly'] - $allocRes);
          $resRemain -= $allocRes;
          if ($resRemain < 0) $resRemain = 0;

          // Write back into main index (by id)
          $tid = $yr['id'];
          $uData['_takhmeens_index'][$tid] = $yr;
        }
        unset($yr);
      }
      unset($uData);
    }

    // Finalize takhmeens array (maintain DESC year order similar to original query ordering)
    foreach ($users as &$u) {
      if (!empty($u['_takhmeens_index'])) {
        // Sort by year DESC (years stored as string like 1445-46, so string comparison works if consistent)
        $temp = $u['_takhmeens_index'];
        usort($temp, function ($a, $b) {
          if ($a['year'] == $b['year']) return 0;
          return ($a['year'] < $b['year']) ? 1 : -1; // DESC
        });
        $u['takhmeens'] = array_values($temp);
      }
      if (empty($u['current_year_takhmeen']) && !empty($u['takhmeens']) && $allowLatestWhenMissing) {
        // Only fall back to the latest year if explicitly allowed
        $u['current_year_takhmeen'] = $u['takhmeens'][0];
      }
      // Re-bind current_year_takhmeen to the instance inside final takhmeens (ensures updated paid/due after allocation)
      if (!empty($u['current_year_takhmeen'])) {
        foreach ($u['takhmeens'] as $refTk) {
          if ($refTk['year'] === $u['current_year_takhmeen']['year']) {
            $u['current_year_takhmeen'] = $refTk; // overwrite with updated reference
            break;
          }
        }
      }
      unset($u['_takhmeens_index']); // Clean internal index

      // --- Aggregate totals across ALL years for convenience ---
      $totalEstYearly = 0; $totalResYearly = 0;
      $totalEstPaid = 0; $totalResPaid = 0;
      // Exclude upcoming/future takhmeen years from "All Yrs" aggregates.
      // A takhmeen is considered upcoming if its starting year (before '-') is greater than the
      // current financial takhmeen year computed earlier in this method ($takhmeen_year).
      foreach ($u['takhmeens'] as $tk) {
        $include = true;
        // Use the user's selected/current hijri year (if available) to determine upcoming years
        if (!empty($u['hijri_year']) && !empty($tk['year'])) {
          $curParts = explode('-', $u['hijri_year']);
          $tkParts = explode('-', $tk['year']);
          $curStart = intval($curParts[0]);
          $tkStart = intval($tkParts[0]);
          if ($tkStart > $curStart) {
            $include = false; // upcoming year, skip in All Yrs totals
          }
        }
        if ($include) {
          $totalEstYearly += (float)($tk['establishment']['yearly'] ?? 0);
          $totalResYearly += (float)($tk['residential']['yearly'] ?? 0);
          $totalEstPaid += (float)($tk['establishment']['paid'] ?? 0);
          $totalResPaid += (float)($tk['residential']['paid'] ?? 0);
        }
      }
      $totalEstDue = max(0, $totalEstYearly - $totalEstPaid);
      $totalResDue = max(0, $totalResYearly - $totalResPaid);
      $totalAllDue = $totalEstDue + $totalResDue;

  $u['total_establishment_yearly'] = $totalEstYearly;
  $u['total_residential_yearly'] = $totalResYearly;
  $u['total_paid_establishment'] = $totalEstPaid;
  $u['total_paid_residential'] = $totalResPaid;
  $u['total_establishment_due'] = $totalEstDue;
  $u['total_residential_due'] = $totalResDue;
  $u['total_due_all_years'] = $totalAllDue;

      // Also embed an aggregate block inside current_year_takhmeen for quick view access
      if (!empty($u['current_year_takhmeen'])) {
        $u['current_year_takhmeen']['aggregate'] = [
          'establishment_yearly_total' => $totalEstYearly,
            'residential_yearly_total' => $totalResYearly,
            'establishment_paid_total' => $totalEstPaid,
            'residential_paid_total' => $totalResPaid,
            'establishment_due_total' => $totalEstDue,
            'residential_due_total' => $totalResDue,
            'overall_due_total' => $totalAllDue,
        ];
      }
    }
    unset($u);

    // If required, keep only users who have an actual takhmeen for the computed current financial year
    if ($requireCurrentYear) {
      $users = array_filter($users, function($u){
        return !empty($u['current_year_takhmeen']) && isset($u['hijri_year']) && ($u['current_year_takhmeen']['year'] === $u['hijri_year']);
      });
    }

    return array_values($users);
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
    $this->db->where('HOF_FM_TYPE', 'HOF');
    $this->db->where("Inactive_Status IS NULL AND Sector IS NOT NULL");
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

  // Add a payment linked to a specific invoice (invoice-wise payment)
  public function add_miqaat_invoice_payment($invoice_id, $amount, $payment_date, $payment_method, $remarks)
  {
    // Fetch invoice to derive user_id
    $invoice = $this->db
      ->select('id, user_id')
      ->from('miqaat_invoice')
      ->where('id', $invoice_id)
      ->get()
      ->row_array();

    if (!$invoice || empty($invoice['user_id'])) {
      return false;
    }

    // Build insert data
    $data = [
      'user_id'           => $invoice['user_id'],
      'miqaat_invoice_id' => $invoice_id,
      'payment_date'      => $payment_date,
      'payment_method'    => $payment_method,
      'amount'            => $amount,
      'remarks'           => $remarks
    ];

    // If miqaat_payment has invoice_id column, set it. Otherwise, prefix remarks
    if ($this->db->field_exists('miqaat_invoice_id', 'miqaat_payment')) {
      $data['miqaat_invoice_id'] = $invoice_id;
    } else {
      // Ensure remarks carries the invoice reference for traceability
      $prefix = "[INV:" . $invoice_id . "] ";
      $data['remarks'] = $prefix . (isset($data['remarks']) ? $data['remarks'] : '');
    }

    return $this->db->insert('miqaat_payment', $data);
  }

  public function update_miqaat_payment_amount($payment_id, $amount)
  {
    $this->db->where('id', $payment_id);
    return $this->db->update('miqaat_payment', ['amount' => $amount]);
  }

  public function delete_miqaat_payment($payment_id)
  {
    $this->db->where('id', $payment_id);
    return $this->db->delete('miqaat_payment');
  }

  // --- Validation helpers for payment caps ---
  // Get invoice total amount and already paid sum
  public function get_invoice_paid_and_amount($invoice_id)
  {
    $row = $this->db
      ->select('i.amount AS invoice_amount, COALESCE(SUM(p.amount), 0) AS paid_sum', false)
      ->from('miqaat_invoice i')
      ->join('miqaat_payment p', 'p.miqaat_invoice_id = i.id', 'left')
      ->where('i.id', $invoice_id)
      ->group_by('i.id, i.amount')
      ->get()
      ->row_array();
    if (!$row) return ['invoice_amount' => 0.0, 'paid_sum' => 0.0];
    return [
      'invoice_amount' => (float) $row['invoice_amount'],
      'paid_sum'       => (float) $row['paid_sum'],
    ];
  }

  // Check whether updating a payment to a new amount stays within the invoice cap
  public function can_update_payment_amount($payment_id, $new_amount)
  {
    // Find the invoice id for this payment
    $payment = $this->db->select('id, miqaat_invoice_id')->from('miqaat_payment')->where('id', $payment_id)->get()->row_array();
    if (!$payment || empty($payment['miqaat_invoice_id'])) return false;
    $invoice_id = (int) $payment['miqaat_invoice_id'];

    // Sum of other payments for the same invoice (exclude this payment)
    $sumRow = $this->db
      ->select('COALESCE(SUM(amount), 0) AS sum_other', false)
      ->from('miqaat_payment')
      ->where('miqaat_invoice_id', $invoice_id)
      ->where('id !=', $payment_id)
      ->get()
      ->row_array();
    $sum_other = $sumRow ? (float) $sumRow['sum_other'] : 0.0;

    // Invoice total amount
    $invRow = $this->db->select('amount')->from('miqaat_invoice')->where('id', $invoice_id)->get()->row_array();
    $invoice_amount = $invRow ? (float) $invRow['amount'] : 0.0;

    // Valid if other payments + new amount <= invoice total
    return ($sum_other + (float) $new_amount) <= $invoice_amount + 1e-6; // small epsilon
  }

  /**
   * Insert a payment record for an FMB General Contribution invoice, validating against remaining balance.
   * Returns array: [success => bool, message => string]
   */
  public function insert_fmbgc_payment($fmbgc_id, $user_id, $amount, $payment_method, $payment_date, $remarks)
  {
    $invoice = $this->db->select('gc.id, gc.amount, IFNULL(SUM(p.amount),0) AS total_received')
      ->from('fmb_general_contribution gc')
      ->join('fmb_general_contribution_payments p', 'p.fmbgc_id = gc.id', 'left')
      ->where('gc.id', $fmbgc_id)
      ->group_by('gc.id')
      ->get()->row_array();

    if (!$invoice) return ['success' => false, 'message' => 'Invoice not found'];
    $remaining = $invoice['amount'] - $invoice['total_received'];
    if ($remaining <= 0) return ['success' => false, 'message' => 'Invoice already fully paid'];
    if ($amount <= 0) return ['success' => false, 'message' => 'Amount must be greater than zero'];
    if ($amount > $remaining) return ['success' => false, 'message' => 'Payment exceeds remaining balance'];

    $insert = [
      'user_id' => $user_id,
      'fmbgc_id' => $fmbgc_id,
      'amount' => $amount,
      'payment_method' => $payment_method,
      'payment_date' => $payment_date,
      'remarks' => $remarks,
      'created_at' => date('Y-m-d H:i:s')
    ];
    if (!$this->db->insert('fmb_general_contribution_payments', $insert)) {
      return ['success' => false, 'message' => 'Failed to insert payment'];
    }

    $newTotal = $invoice['total_received'] + $amount;
    $status = ($newTotal >= $invoice['amount']) ? 1 : 0;
    $this->db->where('id', $fmbgc_id)->update('fmb_general_contribution', [
      'payment_status' => $status,
      'updated_at' => date('Y-m-d H:i:s')
    ]);

    return ['success' => true, 'message' => 'Payment recorded'];
  }

  /**
   * Get payment history for an FMB General Contribution invoice.
   * Returns array with keys: payments (array), meta (invoice info)
   */
  public function get_fmbgc_payment_history($fmbgc_id)
  {
    $invoice = $this->db->select('gc.id, gc.amount, gc.fmb_type, gc.contri_type, u.Full_Name, u.ITS_ID, gc.created_at')
      ->from('fmb_general_contribution gc')
      ->join('user u', 'u.ITS_ID = gc.user_id', 'left')
      ->where('gc.id', $fmbgc_id)
      ->get()->row_array();
    if (!$invoice) return ['payments' => [], 'meta' => null];

    $payments = $this->db->select('id AS payment_id, amount, payment_method, payment_date, remarks, created_at')
      ->from('fmb_general_contribution_payments')
      ->where('fmbgc_id', $fmbgc_id)
      ->order_by('payment_date', 'DESC')
      ->order_by('id', 'DESC')
      ->get()->result_array();
    return ['payments' => $payments, 'meta' => $invoice];
  }

  /**
   * Delete a payment and recompute invoice status.
   */
  public function delete_fmbgc_payment($payment_id)
  {
    $row = $this->db->select('fmbgc_id')->from('fmb_general_contribution_payments')->where('id', $payment_id)->get()->row_array();
    if (!$row) return ['success' => false, 'message' => 'Payment not found'];
    $invoice_id = (int)$row['fmbgc_id'];
    $this->db->where('id', $payment_id)->delete('fmb_general_contribution_payments');
    $this->recompute_fmbgc_status($invoice_id);
    return ['success' => true, 'message' => 'Payment deleted', 'invoice_id' => $invoice_id];
  }

  /**
   * Recalculate payment_status for invoice (1 if fully paid else 0)
   */
  private function recompute_fmbgc_status($invoice_id)
  {
    $totals = $this->db->select('gc.amount, COALESCE(SUM(p.amount),0) AS received')
      ->from('fmb_general_contribution gc')
      ->join('fmb_general_contribution_payments p', 'p.fmbgc_id = gc.id', 'left')
      ->where('gc.id', $invoice_id)
      ->group_by('gc.id')
      ->get()->row_array();
    if (!$totals) return;
    $status = ((float)$totals['received'] >= (float)$totals['amount']) ? 1 : 0;
    $this->db->where('id', $invoice_id)->update('fmb_general_contribution', ['payment_status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
  }

  /**
   * Get summary (amount, total_received, balance_due, payment_status) for a single invoice.
   */
  public function get_fmbgc_invoice_summary($invoice_id)
  {
    return $this->db->select('gc.id, gc.amount, gc.payment_status, COALESCE(SUM(p.amount),0) AS total_received, (gc.amount - COALESCE(SUM(p.amount),0)) AS balance_due', false)
      ->from('fmb_general_contribution gc')
      ->join('fmb_general_contribution_payments p', 'p.fmbgc_id = gc.id', 'left')
      ->where('gc.id', $invoice_id)
      ->group_by('gc.id')
      ->get()->row_array();
  }

  /**
   * Delete entire General Contribution invoice and all its payments.
   */
  public function delete_fmbgc_invoice($invoice_id)
  {
    $invoice_id = (int)$invoice_id;
    if (!$invoice_id) return ['success' => false, 'message' => 'Invalid invoice id'];
    $exists = $this->db->select('id')->from('fmb_general_contribution')->where('id', $invoice_id)->get()->row_array();
    if (!$exists) return ['success' => false, 'message' => 'Invoice not found'];
    // Delete payments first
    $this->db->where('fmbgc_id', $invoice_id)->delete('fmb_general_contribution_payments');
    // Delete invoice
    $this->db->where('id', $invoice_id)->delete('fmb_general_contribution');
    return ['success' => true, 'message' => 'Invoice deleted'];
  }

  public function delete_sabeel_payment($payment_id)
  {
    if (empty($payment_id)) return false;
    $this->db->where('id', $payment_id);
    return $this->db->delete('sabeel_takhmeen_payments');
  }
}
