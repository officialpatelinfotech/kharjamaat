<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('raza_make_field_key')) {
  /**
   * Replicates the key-normalization used by raza submission forms.
   * Converts a field display name to its POST/JSON key.
   */
  function raza_make_field_key($fieldName)
  {
    $fieldName = (string)$fieldName;
    return preg_replace(
      ['/\s+/', '/[()]/', '/[\/?]/'],
      ['-', '_', '-'],
      strtolower($fieldName)
    );
  }
}

if (!function_exists('render_raza_details_table_html')) {
  /**
   * Render a two-column HTML table for a Raza request details.
   *
   * @param string $razaName
   * @param array|null $razatypeFieldsDecoded The decoded `raza_type.fields` JSON.
   * @param array|null $razadataDecoded The decoded `raza.razadata` JSON.
   */
  function render_raza_details_table_html($razaName, $razatypeFieldsDecoded, $razadataDecoded)
  {
    $razaName = (string)$razaName;
    $fields = [];
    if (is_array($razatypeFieldsDecoded) && isset($razatypeFieldsDecoded['fields']) && is_array($razatypeFieldsDecoded['fields'])) {
      $fields = $razatypeFieldsDecoded['fields'];
    }

    $razadata = is_array($razadataDecoded) ? $razadataDecoded : [];

    $rowsHtml = '';

    // Always show "Raza For" first
    $rowsHtml .= '<tr>'
      . '<td style="border:1px solid #e9e9e9; padding:10px 12px; width:40%; font-weight:600;">Raza For</td>'
      . '<td style="border:1px solid #e9e9e9; padding:10px 12px;">' . htmlspecialchars($razaName) . '</td>'
      . '</tr>';

    foreach ($fields as $field) {
      if (!is_array($field)) continue;
      $label = isset($field['name']) ? (string)$field['name'] : '';
      if ($label === '') continue;

      $key = raza_make_field_key($label);
      $valueOut = '';

      // Lookup helper: try several key variants to be tolerant of input-name formats
      $lookup_value = function ($dataArr, $k) {
        if (!is_array($dataArr)) return null;
        // direct
        if (array_key_exists($k, $dataArr)) return $dataArr[$k];
        // hyphen -> underscore
        $k1 = str_replace('-', '_', $k);
        if ($k1 !== $k && array_key_exists($k1, $dataArr)) return $dataArr[$k1];
        // underscore -> hyphen
        $k2 = str_replace('_', '-', $k);
        if ($k2 !== $k && array_key_exists($k2, $dataArr)) return $dataArr[$k2];
        // stripped non-alphanumeric (fallback)
        $k3 = preg_replace('/[^a-z0-9]/', '', $k);
        foreach ($dataArr as $dk => $dv) {
          $dk_norm = preg_replace('/[^a-z0-9]/', '', strtolower((string)$dk));
          if ($dk_norm === $k3) return $dv;
        }
        return null;
      };

      $type = isset($field['type']) ? (string)$field['type'] : '';
      if ($type === 'select') {
        $idx = $lookup_value($razadata, $key);
        if ($idx !== null && isset($field['options']) && is_array($field['options'])) {
          $idxInt = (int)$idx;
          if (isset($field['options'][$idxInt]['name'])) {
            $valueOut = (string)$field['options'][$idxInt]['name'];
          }
        }
      } else {
        $val = $lookup_value($razadata, $key);
        if ($val !== null) {
          $valueOut = (string)$val;
        }
      }

      // Normalize ID formatting for notifications (avoid double-prefixing).
      $lcLabel = strtolower(trim($label));
      $valueOutTrim = trim((string)$valueOut);
      if ($valueOutTrim !== '') {
        if (strpos($lcLabel, 'raza id') !== false) {
          if (stripos($valueOutTrim, 'R#') !== 0 && preg_match('/^\d/', $valueOutTrim)) {
            $valueOut = 'R#' . $valueOutTrim;
          }
        }
        if (strpos($lcLabel, 'miqaat id') !== false) {
          if (stripos($valueOutTrim, 'M#') !== 0 && preg_match('/^\d/', $valueOutTrim)) {
            $valueOut = 'M#' . $valueOutTrim;
          }
        }
      }

      $rowsHtml .= '<tr>'
        . '<td style="border:1px solid #e9e9e9; padding:10px 12px; width:40%; font-weight:600;">' . htmlspecialchars($label) . '</td>'
        . '<td style="border:1px solid #e9e9e9; padding:10px 12px;">' . nl2br(htmlspecialchars($valueOut)) . '</td>'
        . '</tr>';
    }

    $tableHtml = '<table role="presentation" style="width:100%; border-collapse:collapse; margin-top:12px;">'
      . '<thead>'
      . '<tr>'
      . '<th colspan="2" style="text-align:left; padding:10px 12px; background:#f2f6f4; color:#0b815a; border:1px solid #e9e9e9;">Raza Details</th>'
      . '</tr>'
      . '</thead>'
      . '<tbody>'
      . $rowsHtml
      . '</tbody>'
      . '</table>';

    return $tableHtml;
  }
}

if (!function_exists('render_raza_details_compact_text')) {
  /**
   * Render a compact, single-line text summary of all Raza details.
   * Intended for WhatsApp template variables (no newlines).
   *
   * @param string $razaName
   * @param array|null $razatypeFieldsDecoded The decoded `raza_type.fields` JSON.
   * @param array|null $razadataDecoded The decoded `raza.razadata` JSON.
   * @param array $opts Optional settings: separator (default ' | '), max_len (0 = no limit)
   */
  function render_raza_details_compact_text($razaName, $razatypeFieldsDecoded, $razadataDecoded, $opts = [])
  {
    $razaName = (string)$razaName;
    $separator = isset($opts['separator']) ? (string)$opts['separator'] : ' | ';
    $maxLen = isset($opts['max_len']) ? (int)$opts['max_len'] : 0;

    $fields = [];
    if (is_array($razatypeFieldsDecoded) && isset($razatypeFieldsDecoded['fields']) && is_array($razatypeFieldsDecoded['fields'])) {
      $fields = $razatypeFieldsDecoded['fields'];
    }

    $razadata = is_array($razadataDecoded) ? $razadataDecoded : [];

    $clean = function ($val) {
      if (is_array($val)) {
        $tmp = [];
        foreach ($val as $v) {
          $s = is_scalar($v) ? (string)$v : '';
          $s = preg_replace('/\s+/', ' ', trim($s));
          if ($s !== '') $tmp[] = $s;
        }
        return implode(', ', $tmp);
      }
      $s = is_scalar($val) ? (string)$val : '';
      $s = preg_replace('/\s+/', ' ', trim($s));
      return $s;
    };

    // Lookup helper: try several key variants to be tolerant of input-name formats
    $lookup_value = function ($dataArr, $k) {
      if (!is_array($dataArr)) return null;
      if (array_key_exists($k, $dataArr)) return $dataArr[$k];
      $k1 = str_replace('-', '_', $k);
      if ($k1 !== $k && array_key_exists($k1, $dataArr)) return $dataArr[$k1];
      $k2 = str_replace('_', '-', $k);
      if ($k2 !== $k && array_key_exists($k2, $dataArr)) return $dataArr[$k2];
      $k3 = preg_replace('/[^a-z0-9]/', '', $k);
      foreach ($dataArr as $dk => $dv) {
        $dk_norm = preg_replace('/[^a-z0-9]/', '', strtolower((string)$dk));
        if ($dk_norm === $k3) return $dv;
      }
      return null;
    };

    $segments = [];
    if ($clean($razaName) !== '') {
      $segments[] = 'Raza For: ' . $clean($razaName);
    }

    foreach ($fields as $field) {
      if (!is_array($field)) continue;
      $label = isset($field['name']) ? (string)$field['name'] : '';
      if ($label === '') continue;

      $key = raza_make_field_key($label);
      $type = isset($field['type']) ? (string)$field['type'] : '';
      $valueOut = '';

      if ($type === 'select') {
        $raw = $lookup_value($razadata, $key);
        if ($raw !== null && isset($field['options']) && is_array($field['options'])) {
          if (is_numeric($raw)) {
            $idxInt = (int)$raw;
            if (isset($field['options'][$idxInt]['name'])) {
              $valueOut = (string)$field['options'][$idxInt]['name'];
            }
          } else {
            $rawStr = trim((string)$raw);
            if ($rawStr !== '') {
              $rawLower = strtolower($rawStr);
              foreach ($field['options'] as $opt) {
                if (!is_array($opt)) continue;
                $optName = isset($opt['name']) ? trim((string)$opt['name']) : '';
                if ($optName === '') continue;
                if (strtolower($optName) === $rawLower) {
                  $valueOut = $optName;
                  break;
                }
              }
              if ($valueOut === '') $valueOut = $rawStr;
            }
          }
        } elseif ($raw !== null) {
          $valueOut = (string)$raw;
        }
      } else {
        $raw = $lookup_value($razadata, $key);
        if ($raw !== null) $valueOut = is_scalar($raw) ? (string)$raw : '';
      }

      $valueOut = $clean($valueOut);
      if ($valueOut !== '') {
        // Normalize Miqaat ID formatting for WhatsApp templates.
        // Many templates expect the ID to be displayed as M#<id>.
        $labelClean = strtolower($clean($label));
        if ($labelClean === 'miqaat id' || strpos($labelClean, 'miqaat id') !== false) {
          $v = trim((string)$valueOut);
          if ($v !== '' && stripos($v, 'M#') !== 0) {
            $valueOut = 'M#' . $v;
          }
        }
        $segments[] = $clean($label) . ': ' . $valueOut;
      }
    }

    $out = implode($separator, $segments);
    if ($maxLen > 0 && strlen($out) > $maxLen) {
      $out = substr($out, 0, max(0, $maxLen - 3)) . '...';
    }
    return $out;
  }
}

if (!function_exists('render_raza_details_compact_text_from_html')) {
  /**
   * Best-effort conversion of the HTML details table into a single-line text.
   * Used as a fallback when structured razadata/fields are not available.
   *
   * @param string $detailsHtml
   * @param array $opts Optional settings: separator (default ' | '), max_len (0 = no limit)
   */
  function render_raza_details_compact_text_from_html($detailsHtml, $opts = [])
  {
    $separator = isset($opts['separator']) ? (string)$opts['separator'] : ' | ';
    $maxLen = isset($opts['max_len']) ? (int)$opts['max_len'] : 0;

    $html = (string)$detailsHtml;
    if (trim($html) === '') return '';

    $clean = function ($s) {
      $s = html_entity_decode((string)$s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
      $s = trim(strip_tags($s));
      $s = preg_replace('/\s+/', ' ', $s);
      return trim($s);
    };

    $segments = [];
    // Parse per-row to avoid header rows (colspan) corrupting the first data row.
    if (preg_match_all('/<tr\b[^>]*>(.*?)<\/tr>/is', $html, $rowMatches, PREG_SET_ORDER)) {
      foreach ($rowMatches as $rm) {
        $rowHtml = $rm[1] ?? '';
        if ($rowHtml === '') continue;

        if (!preg_match_all('/<t[dh]\b[^>]*>(.*?)<\/t[dh]>/is', $rowHtml, $cellMatches)) {
          continue;
        }
        if (empty($cellMatches[1]) || count($cellMatches[1]) < 2) continue;

        $label = $clean($cellMatches[1][0] ?? '');
        $value = $clean($cellMatches[1][1] ?? '');
        if ($label === '' && $value === '') continue;

        // Normalize Miqaat ID formatting.
        $lcLabel = strtolower($label);
        if (($lcLabel === 'miqaat id' || strpos($lcLabel, 'miqaat id') !== false) && $value !== '' && stripos($value, 'M#') !== 0) {
          $value = 'M#' . $value;
        }

        if ($label === '') {
          $segments[] = $value;
        } elseif ($value === '') {
          $segments[] = $label;
        } else {
          $segments[] = $label . ': ' . $value;
        }
      }
    }

    $out = '';
    if (!empty($segments)) {
      $out = implode($separator, $segments);
    } else {
      // Last resort: put spaces where table tags were so text doesn't glue together.
      $out = $clean(preg_replace('/<\/?(tr|td|th|thead|tbody|table)\b[^>]*>/i', ' ', $html));
    }

    if ($maxLen > 0 && strlen($out) > $maxLen) {
      $out = substr($out, 0, max(0, $maxLen - 3)) . '...';
    }
    return $out;
  }
}
