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
