<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * SimpleXLSX - Lightweight XLSX reader
 * Based on SimpleXLSX by Sergey Shuchkin (MIT License)
 */
class SimpleXLSX
{
    public static $parseError = '';
    private $workbook;
    private $sheets = [];
    private $sharedstrings = [];
    private $rels = [];
    private $package;

    public function __construct($filename = null, $is_data = false)
    {
        if ($filename !== null) {
            $this->parse($filename, $is_data);
        }
    }

    public static function parse($filename, $is_data = false)
    {
        $xlsx = new self();
        if ($xlsx->parseFile($filename, $is_data)) {
            return $xlsx;
        }
        return false;
    }

    public static function parseError()
    {
        return self::$parseError;
    }

    public function parseFile($filename, $is_data = false)
    {
        if (!$is_data) {
            if (!file_exists($filename)) {
                self::$parseError = 'File not found: ' . $filename;
                return false;
            }
            $this->package = new ZipArchive();
            if ($this->package->open($filename) !== true) {
                self::$parseError = 'Package could not be opened: ' . $filename;
                return false;
            }
        } else {
            // If data stream
            $temp = tempnam(sys_get_temp_dir(), 'xlsx');
            file_put_contents($temp, $filename);
            $this->package = new ZipArchive();
            if ($this->package->open($temp) !== true) {
                self::$parseError = 'Package data could not be opened';
                unlink($temp);
                return false;
            }
            unlink($temp);
        }

        // 1. Read shared strings
        if (($sharedStringsXml = $this->getEntryData('xl/sharedStrings.xml')) !== false) {
            $xml = simplexml_load_string($sharedStringsXml, 'SimpleXMLElement', LIBXML_NOENT | LIBXML_COMPACT);
            if ($xml && isset($xml->si)) {
                foreach ($xml->si as $val) {
                    if (isset($val->t)) {
                        $this->sharedstrings[] = (string)$val->t;
                    } elseif (isset($val->r)) {
                        $s = '';
                        foreach ($val->r as $r) {
                            $s .= (string)$r->t;
                        }
                        $this->sharedstrings[] = $s;
                    } else {
                        $this->sharedstrings[] = '';
                    }
                }
            }
        }

        // 2. Read workbook relationships
        if (($workbookRelsXml = $this->getEntryData('xl/_rels/workbook.xml.rels')) !== false) {
            $xml = simplexml_load_string($workbookRelsXml, 'SimpleXMLElement', LIBXML_NOENT | LIBXML_COMPACT);
            if ($xml && isset($xml->Relationship)) {
                foreach ($xml->Relationship as $rel) {
                    $target = ltrim((string)$rel['Target'], '/');
                    if (strpos($target, 'xl/') === 0) {
                        $target = substr($target, 3);
                    }
                    $this->rels[(string)$rel['Id']] = $target;
                }
            }
        }

        // 3. Read workbook
        if (($workbookXml = $this->getEntryData('xl/workbook.xml')) !== false) {
            $xml = simplexml_load_string($workbookXml, 'SimpleXMLElement', LIBXML_NOENT | LIBXML_COMPACT);
            if ($xml && isset($xml->sheets->sheet)) {
                foreach ($xml->sheets->sheet as $sheet) {
                    $rId = (string)$sheet['id'];
                    if (!$rId && isset($sheet->attributes('r', true)->id)) {
                        $rId = (string)$sheet->attributes('r', true)->id;
                    }
                    $name = (string)$sheet['name'];
                    $this->sheets[$name] = isset($this->rels[$rId]) ? 'xl/' . $this->rels[$rId] : '';
                }
            }
        }

        if (empty($this->sheets)) {
            self::$parseError = 'No sheets found in workbook';
            return false;
        }

        return true;
    }

    public function sheetNames()
    {
        return array_keys($this->sheets);
    }

    public function rows($sheetIndex = 0)
    {
        $names = array_keys($this->sheets);
        if (!isset($names[$sheetIndex])) {
            self::$parseError = 'Sheet index not found: ' . $sheetIndex;
            return [];
        }
        $sheetName = $names[$sheetIndex];
        $entry = $this->sheets[$sheetName];
        if (!$entry || ($xmlData = $this->getEntryData($entry)) === false) {
            self::$parseError = 'Worksheet not found: ' . $entry;
            return [];
        }

        $xml = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOENT | LIBXML_COMPACT);
        if (!$xml || !isset($xml->sheetData->row)) {
            return [];
        }

        $rows = [];
        $curR = 0;

        foreach ($xml->sheetData->row as $row) {
            $rowNum = (int)$row['r'];
            if ($rowNum > 0) {
                $curR = $rowNum - 1;
            }

            $cells = [];
            $curC = 0;

            foreach ($row->c as $c) {
                $r = (string)$c['r'];
                $t = (string)$c['t'];
                $v = (string)$c->v;

                if ($r !== '') {
                    // Calculate column index from A, B, C... AA, AB...
                    preg_match('/[A-Z]+/', $r, $matches);
                    $colStr = $matches[0];
                    $colIdx = 0;
                    $len = strlen($colStr);
                    for ($i = 0; $i < $len; $i++) {
                        $colIdx = $colIdx * 26 + (ord($colStr[$i]) - 64);
                    }
                    $curC = $colIdx - 1;
                }

                $val = $v;
                if ($t === 's') { // shared string
                    $val = isset($this->sharedstrings[(int)$v]) ? $this->sharedstrings[(int)$v] : '';
                } elseif ($t === 'inlineStr') { // inline string
                    $val = isset($c->is->t) ? (string)$c->is->t : '';
                }

                $cells[$curC] = $val;
                $curC++;
            }

            // Fill missing cells with empty strings
            if (!empty($cells)) {
                $maxC = max(array_keys($cells));
                $rowArr = [];
                for ($i = 0; $i <= $maxC; $i++) {
                    $rowArr[] = isset($cells[$i]) ? $cells[$i] : '';
                }
                $rows[$curR] = $rowArr;
            } else {
                $rows[$curR] = [];
            }
            $curR++;
        }

        // Sort rows by key in case they came out of order, and fill empty rows
        if (!empty($rows)) {
            ksort($rows);
            $maxR = max(array_keys($rows));
            $finalRows = [];
            for ($i = 0; $i <= $maxR; $i++) {
                $finalRows[] = isset($rows[$i]) ? $rows[$i] : [];
            }
            return $finalRows;
        }

        return [];
    }

    private function getEntryData($name)
    {
        if (!$this->package) return false;
        $name = ltrim($name, '/');
        $stat = $this->package->statName($name);
        if (!$stat) {
            return false;
        }
        return $this->package->getFromName($name);
    }
}
