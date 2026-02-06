<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class QardanHasanaM extends CI_Model
{
    private $tableColumnsCache = [];

    private function filter_to_table_columns($table, array $data)
    {
        if (!isset($this->tableColumnsCache[$table])) {
            $cols = $this->db->list_fields($table);
            $map = [];
            if (is_array($cols)) {
                foreach ($cols as $c) {
                    $map[$c] = true;
                }
            }
            $this->tableColumnsCache[$table] = $map;
        }

        $allowed = $this->tableColumnsCache[$table];
        if (empty($allowed)) return $data;
        return array_intersect_key($data, $allowed);
    }

    private function apply_nullable_where($column, $value)
    {
        if ($value === null) {
            $this->db->where($column . ' IS NULL', null, false);
        } else {
            $this->db->where($column, $value);
        }
    }

    private function normalize_csv_header_key($value)
    {
        $value = (string)$value;
        // Strip UTF-8 BOM if present
        $value = preg_replace('/^\xEF\xBB\xBF/', '', $value);
        $value = strtolower(trim($value));

        // Normalize currency / punctuation
        $value = str_replace(['₹', 'rs.', 'rs'], '', $value);
        $value = preg_replace('/[^a-z0-9]+/', ' ', $value);
        $value = trim(preg_replace('/\s+/', ' ', $value));
        $compact = str_replace(' ', '', $value);

        // Canonicalize
        if (in_array($compact, ['its', 'itsid', 'its_id'], true)) return 'its';
        if (in_array($compact, ['miqaatname', 'miqaat_name', 'miqaat'], true)) return 'miqaat_name';
        if (in_array($compact, ['miqaatid', 'miqaat_id'], true)) return 'miqaat_id';
        if (in_array($compact, ['amount', 'amt', 'collectionamount', 'collection_amount'], true)) return 'amount';
        if (in_array($compact, ['depositdate', 'deposit_date'], true)) return 'deposit_date';
        if (in_array($compact, ['maturitydate', 'maturity_date'], true)) return 'maturity_date';
        if (in_array($compact, ['duration', 'durationinmonthsoryear', 'durationinmonthsoryears'], true)) return 'duration';
        if ($compact === 'unit' || (str_contains($compact, 'unit') && str_contains($compact, '215'))) return 'unit';
        if ($compact === 'units'
            || $compact === 'noofunits'
            || $compact === 'numberofunits'
            || (str_contains($compact, 'noof') && str_contains($compact, 'unit'))
            || (str_contains($compact, 'units') && str_contains($compact, '215'))
        ) return 'units';

        return '';
    }

    private function scheme_table($scheme)
    {
        $scheme = strtolower(trim((string)$scheme));
        $map = [
            'mohammedi' => 'qardan_hasana_mohammedi_scheme',
            'taher' => 'qardan_hasana_taher_scheme',
            'husain' => 'qardan_hasana_husain_scheme'
        ];
        return $map[$scheme] ?? null;
    }

    public function get_scheme_total_amount($scheme)
    {
        $scheme = strtolower(trim((string)$scheme));
        $table = $this->scheme_table($scheme);
        if ($table === null) return 0.0;

        // Avoid fatals if tables aren't present in a given environment.
        if (!$this->db->table_exists($table)) {
            log_message('error', 'QardanHasanaM: missing table ' . $table . ' for scheme=' . $scheme);
            return 0.0;
        }

        $col = 'collection_amount';
        if ($scheme === 'husain') {
            $col = 'amount';
        }

        if (!$this->db->field_exists($col, $table)) {
            log_message('error', 'QardanHasanaM: missing column ' . $col . ' in ' . $table . ' for scheme=' . $scheme);
            return 0.0;
        }

        $oldDebug = $this->db->db_debug;
        $this->db->db_debug = false;

        $query = $this->db
            ->select('COALESCE(SUM(' . $col . '),0) AS total', false)
            ->from($table)
            ->get();

        if ($query === false) {
            $err = $this->db->error();
            log_message('error', 'QardanHasanaM: get_scheme_total_amount query failed scheme=' . $scheme . ' table=' . $table . ' err=' . json_encode($err));
            $this->db->db_debug = $oldDebug;
            return 0.0;
        }

        $row = $query->row_array();

        $this->db->db_debug = $oldDebug;

        return (float)($row['total'] ?? 0);
    }

    public function get_scheme_total_amount_for_its($scheme, $itsList)
    {
        $scheme = strtolower(trim((string)$scheme));
        if (!in_array($scheme, ['taher', 'husain'], true)) {
            return 0.0;
        }

        $table = $this->scheme_table($scheme);
        if ($table === null) return 0.0;

        if (!$this->db->table_exists($table)) {
            log_message('error', 'QardanHasanaM: missing table ' . $table . ' for scheme=' . $scheme);
            return 0.0;
        }

        if (!is_array($itsList)) {
            $itsList = [$itsList];
        }

        $itsList = array_values(array_filter(array_map(function ($v) {
            $v = trim((string)$v);
            return $v === '' ? null : $v;
        }, $itsList)));

        if (empty($itsList)) return 0.0;

        $col = ($scheme === 'husain') ? 'amount' : 'collection_amount';

        if (!$this->db->field_exists($col, $table)) {
            log_message('error', 'QardanHasanaM: missing column ' . $col . ' in ' . $table . ' for scheme=' . $scheme);
            return 0.0;
        }

        $oldDebug = $this->db->db_debug;
        $this->db->db_debug = false;

        $query = $this->db
            ->select('COALESCE(SUM(' . $col . '),0) AS total', false)
            ->from($table)
            ->where_in('ITS', $itsList)
            ->get();

        if ($query === false) {
            $err = $this->db->error();
            log_message('error', 'QardanHasanaM: get_scheme_total_amount_for_its query failed scheme=' . $scheme . ' table=' . $table . ' err=' . json_encode($err));
            $this->db->db_debug = $oldDebug;
            return 0.0;
        }

        $row = $query->row_array();

        $this->db->db_debug = $oldDebug;

        return (float)($row['total'] ?? 0);
    }

    public function get_scheme_records($scheme, $filters = [], $limit = 500)
    {
        $table = $this->scheme_table($scheme);
        if ($table === null) return [];

        $this->db->from($table);

        if (!empty($filters['miqaat_name'])) {
            $this->db->where('miqaat_name', $filters['miqaat_name']);
        }
        if (!empty($filters['hijri_date'])) {
            $this->db->where('hijri_date', $filters['hijri_date']);
        }
        if (!empty($filters['eng_date'])) {
            $this->db->where('eng_date', $filters['eng_date']);
        }

        $this->db->order_by('eng_date', 'DESC');
        $this->db->order_by('uploaded_date', 'DESC');
        $this->db->limit((int)$limit);
        return $this->db->get()->result_array();
    }

    public function get_mohammedi_records($filters = [], $limit = 500)
    {
        return $this->get_scheme_records('mohammedi', $filters, $limit);
    }

    public function get_taher_records($filters = [], $limit = 500)
    {
        $this->db->select('t.*, u.Full_Name AS member_name');
        $this->db->from('qardan_hasana_taher_scheme t');
        $this->db->join('user u', 'u.ITS_ID = t.ITS', 'left');

        if (!empty($filters['miqaat_name'])) {
            $this->db->where('t.miqaat_name', $filters['miqaat_name']);
        }
        if (!empty($filters['ITS'])) {
            if (is_array($filters['ITS'])) {
                $itsList = array_values(array_filter(array_map(function ($v) {
                    $v = trim((string)$v);
                    return $v === '' ? null : $v;
                }, $filters['ITS'])));
                if (!empty($itsList)) {
                    $this->db->where_in('t.ITS', $itsList);
                }
            } else {
                $this->db->where('t.ITS', $filters['ITS']);
            }
        }
        if (!empty($filters['member_name'])) {
            $this->db->like('u.Full_Name', $filters['member_name']);
        }

        $this->db->order_by('id', 'DESC');
        $this->db->limit((int)$limit);
        return $this->db->get()->result_array();
    }

    public function get_husain_records($filters = [], $limit = 500)
    {
        $this->db->select('h.*, u.Full_Name AS member_name');
        $this->db->from('qardan_hasana_husain_scheme h');
        $this->db->join('user u', 'u.ITS_ID = h.ITS', 'left');

        if (!empty($filters['ITS'])) {
            if (is_array($filters['ITS'])) {
                $itsList = array_values(array_filter(array_map(function ($v) {
                    $v = trim((string)$v);
                    return $v === '' ? null : $v;
                }, $filters['ITS'])));
                if (!empty($itsList)) {
                    $this->db->where_in('h.ITS', $itsList);
                }
            } else {
                $this->db->where('h.ITS', $filters['ITS']);
            }
        }
        if (!empty($filters['deposit_date'])) {
            $this->db->where('h.deposit_date', $filters['deposit_date']);
        }
        if (!empty($filters['maturity_date'])) {
            $this->db->where('h.maturity_date', $filters['maturity_date']);
        }
        if (!empty($filters['duration'])) {
            $this->db->like('h.duration', $filters['duration']);
        }
        if (!empty($filters['member_name'])) {
            $this->db->like('u.Full_Name', $filters['member_name']);
        }

        $this->db->order_by('deposit_date', 'DESC');
        $this->db->order_by('uploaded_date', 'DESC');
        $this->db->limit((int)$limit);
        return $this->db->get()->result_array();
    }

    private function normalize_eng_date($value)
    {
        $value = trim((string)$value);
        if ($value === '') return null;

        // Accept YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        // Accept DD-MM-YYYY or DD/MM/YYYY
        if (preg_match('/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/', $value, $m)) {
            $d = (int)$m[1];
            $mo = (int)$m[2];
            $y = (int)$m[3];
            if (checkdate($mo, $d, $y)) {
                return sprintf('%04d-%02d-%02d', $y, $mo, $d);
            }
        }

        // Last resort: strtotime
        $ts = strtotime($value);
        if ($ts !== false) {
            return date('Y-m-d', $ts);
        }
        return null;
    }

    private function normalize_hijri_date($value)
    {
        $value = trim((string)$value);
        if ($value === '') return null;

        // Accept DD-MM-YYYY or DD/MM/YYYY
        if (preg_match('/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/', $value, $m)) {
            $d = (int)$m[1];
            $mo = (int)$m[2];
            $y = (int)$m[3];
            if ($d >= 1 && $d <= 31 && $mo >= 1 && $mo <= 12 && $y > 0) {
                return sprintf('%02d-%02d-%04d', $d, $mo, $y);
            }
        }

        // Accept YYYY-MM-DD or YYYY/MM/DD
        if (preg_match('/^(\d{4})[\/-](\d{1,2})[\/-](\d{1,2})$/', $value, $m)) {
            $y = (int)$m[1];
            $mo = (int)$m[2];
            $d = (int)$m[3];
            if ($d >= 1 && $d <= 31 && $mo >= 1 && $mo <= 12 && $y > 0) {
                return sprintf('%02d-%02d-%04d', $d, $mo, $y);
            }
        }

        return $value;
    }

    public function import_mohammedi_csv($tmpPath)
    {
        return $this->import_scheme_csv('mohammedi', $tmpPath);
    }

    public function import_taher_csv($tmpPath)
    {
        $result = [
            'success' => false,
            'inserted' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => []
        ];

        if (!is_readable($tmpPath)) {
            $result['errors'][] = 'Uploaded file is not readable.';
            return $result;
        }

        $handle = fopen($tmpPath, 'r');
        if (!$handle) {
            $result['errors'][] = 'Could not open uploaded file.';
            return $result;
        }

        $header = null;
        $colIndex = null;
        $batch = [];
        $lineNo = 0;

        $oldDebug = $this->db->db_debug;
        $this->db->db_debug = false;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNo++;

            // Support semicolon-delimited CSV exported by some tools
            if (is_array($row) && count($row) === 1 && strpos((string)$row[0], ';') !== false) {
                $row = str_getcsv((string)$row[0], ';');
            }

            if (empty($row) || (count($row) === 1 && trim((string)$row[0]) === '')) {
                continue;
            }

            if ($header === null) {
                $normalized = array_map(function ($v) { return $this->normalize_csv_header_key($v); }, $row);
                $tmpIndex = [];
                foreach ($normalized as $idx => $key) {
                    if ($key === '') continue;
                    if (!isset($tmpIndex[$key])) {
                        $tmpIndex[$key] = (int)$idx;
                    }
                }

                // Treat as header row if it contains at least ITS + miqaat_name (or any known keys)
                $looksLikeHeader = !empty($tmpIndex);
                if ($looksLikeHeader) {
                    $colIndex = $tmpIndex;
                    $header = $row;
                    continue;
                }

                // No header, fixed order: ITS, unit, units, miqaat_name
                $colIndex = ['its' => 0, 'unit' => 1, 'units' => 2, 'miqaat_name' => 3];
                $header = ['its', 'unit', 'units', 'miqaat_name'];
            }

            $its = '';
            $miqaatName = '';
            $miqaatIdRaw = '';
            $unitsRaw = '0';
            $unitRaw = '215';
            $amountRaw = '';

            if (is_array($colIndex)) {
                if (isset($colIndex['its']) && array_key_exists($colIndex['its'], $row)) {
                    $its = trim((string)$row[$colIndex['its']]);
                }
                if (isset($colIndex['miqaat_name']) && array_key_exists($colIndex['miqaat_name'], $row)) {
                    $miqaatName = trim((string)$row[$colIndex['miqaat_name']]);
                }
                if (isset($colIndex['miqaat_id']) && array_key_exists($colIndex['miqaat_id'], $row)) {
                    $miqaatIdRaw = trim((string)$row[$colIndex['miqaat_id']]);
                }
                if (isset($colIndex['units']) && array_key_exists($colIndex['units'], $row)) {
                    $unitsRaw = (string)$row[$colIndex['units']];
                }
                if (isset($colIndex['unit']) && array_key_exists($colIndex['unit'], $row)) {
                    $unitRaw = (string)$row[$colIndex['unit']];
                }
                if (isset($colIndex['amount']) && array_key_exists($colIndex['amount'], $row)) {
                    $amountRaw = (string)$row[$colIndex['amount']];
                }
            }

            // If miqaat_name missing but miqaat_id provided, resolve name
            if ($miqaatName === '' && $miqaatIdRaw !== '') {
                $miqaatId = (int)preg_replace('/[^0-9]/', '', $miqaatIdRaw);
                if ($miqaatId > 0) {
                    $rowM = $this->db->select('name')->from('miqaat')->where('id', $miqaatId)->get()->row_array();
                    if (!empty($rowM['name'])) {
                        $miqaatName = (string)$rowM['name'];
                    }
                }
            }

            if ($its === '' || $miqaatName === '') {
                $result['skipped']++;
                if (count($result['errors']) < 10) {
                    $missing = [];
                    if ($its === '') $missing[] = 'ITS';
                    if ($miqaatName === '') $missing[] = 'Miqaat Name';
                    $result['errors'][] = 'Line ' . $lineNo . ' skipped: missing ' . implode(' & ', $missing) . '.';
                }
                continue;
            }

            $units = (int)preg_replace('/[^0-9\-]/', '', $unitsRaw);
            if ($units < 0) $units = 0;

            $amount = null;
            if (trim((string)$amountRaw) !== '') {
                $amount = (float)preg_replace('/[^0-9.\-]/', '', (string)$amountRaw);
            }

            $unit = (float)preg_replace('/[^0-9.\-]/', '', $unitRaw);
            if ($unit <= 0) $unit = 215.00;

            // If units were not provided but amount is, compute units from amount/unit.
            if ($units === 0 && $amount !== null && $amount > 0 && $unit > 0) {
                $computedUnits = (int)round($amount / $unit);
                if ($computedUnits > 0) {
                    $units = $computedUnits;
                }
            }

            $collectionAmount = 0.00;
            if ($amount !== null && $amount > 0) {
                $collectionAmount = $amount;
            } else {
                $collectionAmount = (float)$unit * (int)$units;
            }

            $rowData = [
                'ITS' => $its,
                'unit' => $unit,
                'units' => $units,
                'miqaat_name' => $miqaatName,
                'collection_amount' => $collectionAmount
            ];

            $rowData = $this->filter_to_table_columns('qardan_hasana_taher_scheme', $rowData);

            $exists = $this->db
                ->select('id')
                ->from('qardan_hasana_taher_scheme')
                ->where('ITS', $its)
                ->where('miqaat_name', $miqaatName)
                ->limit(1)
                ->get()
                ->row_array();

            if (!empty($exists)) {
                $this->db->where('ITS', $its);
                $this->db->where('miqaat_name', $miqaatName);
                $ok = $this->db->update('qardan_hasana_taher_scheme', $rowData);
                if ($ok === false) {
                    $err = $this->db->error();
                    $result['errors'][] = !empty($err['message']) ? (string)$err['message'] : 'Database update failed.';
                    fclose($handle);
                    $this->db->db_debug = $oldDebug;
                    return $result;
                }
                $result['updated'] += max(1, (int)$this->db->affected_rows());
            } else {
                $ok = $this->db->insert('qardan_hasana_taher_scheme', $rowData);
                if ($ok === false) {
                    $err = $this->db->error();
                    $result['errors'][] = !empty($err['message']) ? (string)$err['message'] : 'Database insert failed.';
                    fclose($handle);
                    $this->db->db_debug = $oldDebug;
                    return $result;
                }
                $result['inserted']++;
            }
        }

        fclose($handle);

        // no-op: batch replaced by per-row upsert logic

        $this->db->db_debug = $oldDebug;

        $result['success'] = true;
        return $result;
    }

    public function import_husain_csv($tmpPath)
    {
        $result = [
            'success' => false,
            'inserted' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => []
        ];

        if (!is_readable($tmpPath)) {
            $result['errors'][] = 'Uploaded file is not readable.';
            return $result;
        }

        $handle = fopen($tmpPath, 'r');
        if (!$handle) {
            $result['errors'][] = 'Could not open uploaded file.';
            return $result;
        }

        $header = null;
        $colIndex = null;
        $batch = [];
        $lineNo = 0;

        $oldDebug = $this->db->db_debug;
        $this->db->db_debug = false;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNo++;

            // Support semicolon-delimited CSV exported by some tools
            if (is_array($row) && count($row) === 1 && strpos((string)$row[0], ';') !== false) {
                $row = str_getcsv((string)$row[0], ';');
            }

            if (empty($row) || (count($row) === 1 && trim((string)$row[0]) === '')) {
                continue;
            }

            if ($header === null) {
                $normalized = array_map(function ($v) { return $this->normalize_csv_header_key($v); }, $row);
                $tmpIndex = [];
                foreach ($normalized as $idx => $key) {
                    if ($key === '') continue;
                    if (!isset($tmpIndex[$key])) {
                        $tmpIndex[$key] = (int)$idx;
                    }
                }

                $looksLikeHeader = !empty($tmpIndex);
                if ($looksLikeHeader) {
                    $colIndex = $tmpIndex;
                    $header = $row;
                    continue;
                }

                // No header; fixed order: ITS, amount, deposit_date, maturity_date, duration
                $colIndex = ['its' => 0, 'amount' => 1, 'deposit_date' => 2, 'maturity_date' => 3, 'duration' => 4];
                $header = ['its', 'amount', 'deposit_date', 'maturity_date', 'duration'];
            }

            $its = '';
            $amountRaw = '0';
            $depositDateRaw = '';
            $maturityDateRaw = '';
            $duration = '';

            if (is_array($colIndex)) {
                if (isset($colIndex['its']) && array_key_exists($colIndex['its'], $row)) {
                    $its = trim((string)$row[$colIndex['its']]);
                }
                if (isset($colIndex['amount']) && array_key_exists($colIndex['amount'], $row)) {
                    $amountRaw = (string)$row[$colIndex['amount']];
                }
                if (isset($colIndex['deposit_date']) && array_key_exists($colIndex['deposit_date'], $row)) {
                    $depositDateRaw = trim((string)$row[$colIndex['deposit_date']]);
                }
                if (isset($colIndex['maturity_date']) && array_key_exists($colIndex['maturity_date'], $row)) {
                    $maturityDateRaw = trim((string)$row[$colIndex['maturity_date']]);
                }
                if (isset($colIndex['duration']) && array_key_exists($colIndex['duration'], $row)) {
                    $duration = trim((string)$row[$colIndex['duration']]);
                }
            }

            if ($its === '') {
                $result['skipped']++;
                if (count($result['errors']) < 10) {
                    $result['errors'][] = 'Line ' . $lineNo . ' skipped: missing ITS.';
                }
                continue;
            }

            $amount = (float)preg_replace('/[^0-9.\-]/', '', $amountRaw);

            $depositDate = null;
            if ($depositDateRaw !== '') {
                $depositDate = $this->normalize_eng_date($depositDateRaw);
                if ($depositDate === null) {
                    $result['skipped']++;
                    if (count($result['errors']) < 10) {
                        $result['errors'][] = 'Invalid Deposit Date on line ' . $lineNo;
                    }
                    continue;
                }
            }

            $maturityDate = null;
            if ($maturityDateRaw !== '') {
                $maturityDate = $this->normalize_eng_date($maturityDateRaw);
                if ($maturityDate === null) {
                    $result['skipped']++;
                    if (count($result['errors']) < 10) {
                        $result['errors'][] = 'Invalid Maturity Date on line ' . $lineNo;
                    }
                    continue;
                }
            }

            $batch[] = [
                'ITS' => $its,
                'amount' => $amount,
                'deposit_date' => $depositDate,
                'maturity_date' => $maturityDate,
                'duration' => $duration,
                'uploaded_date' => date('Y-m-d H:i:s')
            ];

            if (count($batch) >= 200) {
                foreach ($batch as $rowData) {
                    $rowData = $this->filter_to_table_columns('qardan_hasana_husain_scheme', $rowData);

                    $this->db->select('id')->from('qardan_hasana_husain_scheme')->where('ITS', $rowData['ITS'] ?? '');
                    $this->apply_nullable_where('deposit_date', $rowData['deposit_date'] ?? null);
                    $this->apply_nullable_where('maturity_date', $rowData['maturity_date'] ?? null);
                    $exists = $this->db->limit(1)->get()->row_array();

                    if (!empty($exists)) {
                        $this->db->where('ITS', $rowData['ITS'] ?? '');
                        $this->apply_nullable_where('deposit_date', $rowData['deposit_date'] ?? null);
                        $this->apply_nullable_where('maturity_date', $rowData['maturity_date'] ?? null);
                        $ok = $this->db->update('qardan_hasana_husain_scheme', $rowData);
                        if ($ok === false) {
                            $err = $this->db->error();
                            $result['errors'][] = !empty($err['message']) ? (string)$err['message'] : 'Database update failed.';
                            fclose($handle);
                            $this->db->db_debug = $oldDebug;
                            return $result;
                        }
                        $result['updated'] += max(1, (int)$this->db->affected_rows());
                    } else {
                        $ok = $this->db->insert('qardan_hasana_husain_scheme', $rowData);
                        if ($ok === false) {
                            $err = $this->db->error();
                            $result['errors'][] = !empty($err['message']) ? (string)$err['message'] : 'Database insert failed.';
                            fclose($handle);
                            $this->db->db_debug = $oldDebug;
                            return $result;
                        }
                        $result['inserted']++;
                    }
                }
                $batch = [];
            }
        }

        fclose($handle);

        if (!empty($batch)) {
            foreach ($batch as $rowData) {
                $rowData = $this->filter_to_table_columns('qardan_hasana_husain_scheme', $rowData);

                $this->db->select('id')->from('qardan_hasana_husain_scheme')->where('ITS', $rowData['ITS'] ?? '');
                $this->apply_nullable_where('deposit_date', $rowData['deposit_date'] ?? null);
                $this->apply_nullable_where('maturity_date', $rowData['maturity_date'] ?? null);
                $exists = $this->db->limit(1)->get()->row_array();

                if (!empty($exists)) {
                    $this->db->where('ITS', $rowData['ITS'] ?? '');
                    $this->apply_nullable_where('deposit_date', $rowData['deposit_date'] ?? null);
                    $this->apply_nullable_where('maturity_date', $rowData['maturity_date'] ?? null);
                    $ok = $this->db->update('qardan_hasana_husain_scheme', $rowData);
                    if ($ok === false) {
                        $err = $this->db->error();
                        $result['errors'][] = !empty($err['message']) ? (string)$err['message'] : 'Database update failed.';
                        $this->db->db_debug = $oldDebug;
                        return $result;
                    }
                    $result['updated'] += max(1, (int)$this->db->affected_rows());
                } else {
                    $ok = $this->db->insert('qardan_hasana_husain_scheme', $rowData);
                    if ($ok === false) {
                        $err = $this->db->error();
                        $result['errors'][] = !empty($err['message']) ? (string)$err['message'] : 'Database insert failed.';
                        $this->db->db_debug = $oldDebug;
                        return $result;
                    }
                    $result['inserted']++;
                }
            }
        }

        $this->db->db_debug = $oldDebug;

        $result['success'] = true;
        return $result;
    }

    public function import_scheme_csv($scheme, $tmpPath)
    {
        $result = [
            'success' => false,
            'inserted' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => []
        ];

        $table = $this->scheme_table($scheme);
        if ($table === null) {
            $result['errors'][] = 'Invalid scheme.';
            return $result;
        }

        if (!is_readable($tmpPath)) {
            $result['errors'][] = 'Uploaded file is not readable.';
            return $result;
        }

        $handle = fopen($tmpPath, 'r');
        if (!$handle) {
            $result['errors'][] = 'Could not open uploaded file.';
            return $result;
        }

        $header = null;
        $lineNo = 0;

        $oldDebug = $this->db->db_debug;
        $this->db->db_debug = false;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNo++;
            // Skip empty rows
            if (empty($row) || (count($row) === 1 && trim((string)$row[0]) === '')) {
                continue;
            }

            // Detect header row if it contains known column names
            if ($header === null) {
                $lower = array_map(function ($v) { return strtolower(trim((string)$v)); }, $row);
                $looksLikeHeader = in_array('miqaat_name', $lower, true)
                    || in_array('miqaat', $lower, true)
                    || in_array('hijri_date', $lower, true)
                    || in_array('eng_date', $lower, true)
                    || in_array('collection_amount', $lower, true)
                    || in_array('amount', $lower, true);
                if ($looksLikeHeader) {
                    $header = $lower;
                    continue;
                }
                // No header; treat as fixed order
                $header = ['miqaat_name', 'hijri_date', 'eng_date', 'collection_amount'];
            }

            $data = [];
            foreach ($header as $idx => $key) {
                if (!array_key_exists($idx, $row)) continue;
                $data[$key] = $row[$idx];
            }

            $miqaatName = trim((string)($data['miqaat_name'] ?? $data['miqaat'] ?? ''));
            $hijriDate = $this->normalize_hijri_date($data['hijri_date'] ?? '');
            $engDateRaw = (string)($data['eng_date'] ?? $data['english_date'] ?? $data['date'] ?? '');
            $amountRaw = (string)($data['collection_amount'] ?? $data['amount'] ?? '0');

            if ($miqaatName === '' || $hijriDate === null || $hijriDate === '' || trim($engDateRaw) === '') {
                $result['skipped']++;
                continue;
            }

            $engDate = $this->normalize_eng_date($engDateRaw);
            if ($engDate === null) {
                $result['errors'][] = 'Invalid English date on line ' . $lineNo;
                $result['skipped']++;
                continue;
            }

            $amount = (float)preg_replace('/[^0-9.\-]/', '', $amountRaw);

            $rowData = [
                'miqaat_name' => $miqaatName,
                'hijri_date' => $hijriDate,
                'eng_date' => $engDate,
                'collection_amount' => $amount,
                'uploaded_date' => date('Y-m-d H:i:s')
            ];

            $rowData = $this->filter_to_table_columns($table, $rowData);

            $exists = $this->db
                ->select('id')
                ->from($table)
                ->where('miqaat_name', $miqaatName)
                ->where('hijri_date', $hijriDate)
                ->where('eng_date', $engDate)
                ->limit(1)
                ->get()
                ->row_array();

            if (!empty($exists)) {
                $this->db->where('miqaat_name', $miqaatName);
                $this->db->where('hijri_date', $hijriDate);
                $this->db->where('eng_date', $engDate);
                $ok = $this->db->update($table, $rowData);
                if ($ok === false) {
                    $err = $this->db->error();
                    $result['errors'][] = !empty($err['message']) ? (string)$err['message'] : 'Database update failed.';
                    fclose($handle);
                    $this->db->db_debug = $oldDebug;
                    return $result;
                }
                $result['updated'] += max(1, (int)$this->db->affected_rows());
            } else {
                $ok = $this->db->insert($table, $rowData);
                if ($ok === false) {
                    $err = $this->db->error();
                    $result['errors'][] = !empty($err['message']) ? (string)$err['message'] : 'Database insert failed.';
                    fclose($handle);
                    $this->db->db_debug = $oldDebug;
                    return $result;
                }
                $result['inserted']++;
            }
        }

        fclose($handle);

        $this->db->db_debug = $oldDebug;

        $result['success'] = true;
        return $result;
    }

    public function delete_mohammedi_record($id)
    {
        return $this->delete_scheme_record('mohammedi', $id);
    }

    public function delete_taher_record($id)
    {
        return $this->delete_scheme_record('taher', $id);
    }

    public function delete_husain_record($id)
    {
        return $this->delete_scheme_record('husain', $id);
    }

    public function delete_scheme_record($scheme, $id)
    {
        $table = $this->scheme_table($scheme);
        if ($table === null) return false;

        $id = (int)$id;
        if ($id <= 0) return false;
        $this->db->where('id', $id);
        $this->db->delete($table);
        return $this->db->affected_rows() > 0;
    }

    public function update_mohammedi_record($id, $payload)
    {
        return $this->update_scheme_record('mohammedi', $id, $payload);
    }

    public function update_taher_record($id, $payload)
    {
        $id = (int)$id;
        if ($id <= 0) {
            return ['success' => false, 'error' => 'Invalid record.'];
        }

        $its = trim((string)($payload['ITS'] ?? $payload['its'] ?? ''));
        $miqaatName = trim((string)($payload['miqaat_name'] ?? $payload['miqaat'] ?? ''));
        $unitsRaw = (string)($payload['units'] ?? '0');
        $unitRaw = (string)($payload['unit'] ?? '215');

        if ($its === '' || $miqaatName === '') {
            return ['success' => false, 'error' => 'Please fill all required fields.'];
        }

        $units = (int)preg_replace('/[^0-9\-]/', '', $unitsRaw);
        if ($units < 0) $units = 0;

        $unit = (float)preg_replace('/[^0-9.\-]/', '', $unitRaw);
        if ($unit <= 0) $unit = 215.00;

        $data = [
            'ITS' => $its,
            'unit' => $unit,
            'units' => $units,
            'miqaat_name' => $miqaatName,
            'collection_amount' => (float)$unit * (int)$units
        ];

        $oldDebug = $this->db->db_debug;
        $this->db->db_debug = false;
        $this->db->where('id', $id);
        $ok = $this->db->update('qardan_hasana_taher_scheme', $data);
        $this->db->db_debug = $oldDebug;

        if ($ok !== false) {
            return ['success' => true];
        }

        $err = $this->db->error();
        $msg = !empty($err['message']) ? (string)$err['message'] : 'Could not update record.';
        return ['success' => false, 'error' => $msg];
    }

    public function update_husain_record($id, $payload)
    {
        $id = (int)$id;
        if ($id <= 0) {
            return ['success' => false, 'error' => 'Invalid record.'];
        }

        $its = trim((string)($payload['ITS'] ?? $payload['its'] ?? ''));
        $amountRaw = (string)($payload['amount'] ?? $payload['Amount'] ?? '0');
        $depositDateRaw = trim((string)($payload['deposit_date'] ?? $payload['Deposit Date'] ?? ''));
        $maturityDateRaw = trim((string)($payload['maturity_date'] ?? $payload['Maturity Date'] ?? ''));
        $duration = trim((string)($payload['duration'] ?? $payload['Duration'] ?? ''));

        if ($its === '') {
            return ['success' => false, 'error' => 'Please fill all required fields.'];
        }

        $amount = (float)preg_replace('/[^0-9.\-]/', '', $amountRaw);

        $depositDate = null;
        if ($depositDateRaw !== '') {
            $depositDate = $this->normalize_eng_date($depositDateRaw);
            if ($depositDate === null) {
                return ['success' => false, 'error' => 'Invalid Deposit Date.'];
            }
        }

        $maturityDate = null;
        if ($maturityDateRaw !== '') {
            $maturityDate = $this->normalize_eng_date($maturityDateRaw);
            if ($maturityDate === null) {
                return ['success' => false, 'error' => 'Invalid Maturity Date.'];
            }
        }

        $data = [
            'ITS' => $its,
            'amount' => $amount,
            'deposit_date' => $depositDate,
            'maturity_date' => $maturityDate,
            'duration' => $duration
        ];

        $oldDebug = $this->db->db_debug;
        $this->db->db_debug = false;
        $this->db->where('id', $id);
        $ok = $this->db->update('qardan_hasana_husain_scheme', $data);
        $this->db->db_debug = $oldDebug;

        if ($ok !== false) {
            return ['success' => true];
        }

        $err = $this->db->error();
        $msg = !empty($err['message']) ? (string)$err['message'] : 'Could not update record.';
        return ['success' => false, 'error' => $msg];
    }

    public function update_scheme_record($scheme, $id, $payload)
    {
        $table = $this->scheme_table($scheme);
        if ($table === null) {
            return ['success' => false, 'error' => 'Invalid scheme.'];
        }

        $id = (int)$id;
        if ($id <= 0) {
            return ['success' => false, 'error' => 'Invalid record.'];
        }

        $miqaatName = trim((string)($payload['miqaat_name'] ?? ''));
        $hijriDate = $this->normalize_hijri_date($payload['hijri_date'] ?? '');
        $engDateRaw = trim((string)($payload['eng_date'] ?? ''));
        $amountRaw = (string)($payload['collection_amount'] ?? '0');

        if ($miqaatName === '' || $hijriDate === null || $hijriDate === '' || $engDateRaw === '') {
            return ['success' => false, 'error' => 'Please fill all required fields.'];
        }

        $engDate = $this->normalize_eng_date($engDateRaw);
        if ($engDate === null) {
            return ['success' => false, 'error' => 'Invalid English date.'];
        }

        $amount = (float)preg_replace('/[^0-9.\-]/', '', $amountRaw);

        $data = [
            'miqaat_name' => $miqaatName,
            'hijri_date' => $hijriDate,
            'eng_date' => $engDate,
            'collection_amount' => $amount
        ];

        $this->db->where('id', $id);
        $this->db->update($table, $data);
        if ($this->db->affected_rows() >= 0) {
            return ['success' => true];
        }
        return ['success' => false, 'error' => 'Could not update record.'];
    }
}
