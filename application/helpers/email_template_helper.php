<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('render_generic_email_html')) {
  /**
   * Render a generic email using the shared green header/card/footer layout.
   * Template file: assets/email_generic.php
   */
  function render_generic_email_html($params)
  {
    $title = isset($params['title']) ? (string)$params['title'] : '';
    if ($title === '') $title = 'Notification';

    $todayDate = isset($params['todayDate']) ? (string)$params['todayDate'] : '';
    if ($todayDate === '') $todayDate = date('l, j M Y, h:i:s A');

    // Greeting should be plain text (no embedded HTML).
    $greeting = isset($params['greeting']) ? (string)$params['greeting'] : 'Baad Afzalus Salaam,';
    $name = isset($params['name']) ? trim((string)$params['name']) : '';
    $its = isset($params['its']) ? trim((string)$params['its']) : '';

    // Intro block (supports optional name/ITS)
    $introHtml = '';
    if (trim($greeting) !== '') {
      $introHtml = '<strong>' . htmlspecialchars($greeting) . '</strong>';
    }
    if ($name !== '' || $its !== '') {
      if ($introHtml !== '') {
        $introHtml .= '<br />';
      }
      if ($name !== '') {
        $introHtml .= '<span style="font-weight:600">' . htmlspecialchars($name) . '</span>';
      }
      if ($its !== '') {
        $introHtml .= ($name !== '' ? ' — ' : '');
        $introHtml .= '<span style="color:#5b6b6b">' . htmlspecialchars($its) . '</span>';
      }
    }

    $cardTitle = isset($params['cardTitle']) ? trim((string)$params['cardTitle']) : '';
    $cardTitleHtml = '';
    if ($cardTitle !== '') {
      $cardTitleHtml = '<p style="margin:0 0 8px 0; font-weight:600;">' . htmlspecialchars($cardTitle) . '</p>';
    }

    $body = isset($params['body']) ? $params['body'] : '';
    $body = is_string($body) ? trim($body) : '';

    $autoTable = !empty($params['auto_table']);
    $detailsRows = [];
    if (isset($params['details'])) {
      $details = $params['details'];
      if (is_array($details)) {
        // Support associative arrays: ['Label' => 'Value']
        // Or list arrays: [['label' => '...', 'value' => '...'], ...]
        $isAssoc = array_keys($details) !== range(0, count($details) - 1);
        if ($isAssoc) {
          foreach ($details as $k => $v) {
            $k = trim((string)$k);
            $v = is_scalar($v) ? trim((string)$v) : (is_null($v) ? '' : trim(json_encode($v)));
            if ($k === '' && $v === '') continue;
            $detailsRows[] = ['label' => $k, 'value' => $v];
          }
        } else {
          foreach ($details as $row) {
            if (!is_array($row)) continue;
            $label = isset($row['label']) ? trim((string)$row['label']) : '';
            $value = isset($row['value']) ? $row['value'] : '';
            $value = is_scalar($value) ? trim((string)$value) : (is_null($value) ? '' : trim(json_encode($value)));
            if ($label === '' && $value === '') continue;
            $detailsRows[] = ['label' => $label, 'value' => $value];
          }
        }
      }
    }

    $hasTableAlready = (stripos($body, '<table') !== false);
    $bodyLooksHtml = ($body !== '' && preg_match('/<\s*(br|p|div|table|ul|ol|li|a|strong|em|span|h\d)\b/i', $body));

    // Default body rendering (HTML preserved; otherwise escaped)
    $cardBodyHtml = $bodyLooksHtml ? $body : nl2br(htmlspecialchars($body));

    // If explicit details provided, prefer rendering as a table.
    if (!empty($detailsRows) && !$hasTableAlready) {
      $tableHtml = email_kv_details_table_html($detailsRows);
      $cardBodyHtml = $tableHtml . ($cardBodyHtml !== '' ? ('<div style="margin-top:12px;">' . $cardBodyHtml . '</div>') : '');
    }

    // Auto-convert "Label: Value" lines into a details table when requested.
    if ($autoTable && !$hasTableAlready && empty($detailsRows) && trim($body) !== '') {
      $parsedRows = email_extract_kv_rows_from_body($body);
      if (count($parsedRows) >= 2) {
        $cardBodyHtml = email_kv_details_table_html($parsedRows);
      }
    }

    $ctaUrl = isset($params['ctaUrl']) ? trim((string)$params['ctaUrl']) : '';
    $ctaText = isset($params['ctaText']) ? trim((string)$params['ctaText']) : '';
    $ctaHtml = '';
    if ($ctaUrl !== '' && $ctaText !== '') {
      $ctaHtml = '<a class="cta" style="color: white;" href="' . htmlspecialchars($ctaUrl) . '">' . htmlspecialchars($ctaText) . '</a>';
    }

    $templatePath = (defined('FCPATH') ? FCPATH : '') . 'assets/email_generic.php';
    if ($templatePath === 'assets/email_generic.php') {
      // Fallback if FCPATH is not defined
      $templatePath = 'assets/email_generic.php';
    }

    $tpl = @file_get_contents($templatePath);
    if ($tpl === false || $tpl === '') {
      // Fallback: simple HTML wrapper
      $out = $introHtml . '<br /><br />' . $cardTitleHtml . $cardBodyHtml . '<br /><br />Regards,<br />Kharjamaat Administration';
      return $out;
    }

    $repl = [
      'title' => htmlspecialchars($title),
      'todayDate' => htmlspecialchars($todayDate),
      'introHtml' => $introHtml,
      'cardTitleHtml' => $cardTitleHtml,
      'cardBodyHtml' => $cardBodyHtml,
      'ctaHtml' => $ctaHtml,
      'jamaat_name' => htmlspecialchars(jamaat_name()),
    ];

    foreach ($repl as $key => $value) {
      $tpl = str_replace('{%' . $key . '%}', $value, $tpl);
    }

    return $tpl;
  }
}

if (!function_exists('email_kv_details_table_html')) {
  function email_kv_details_table_html($rows)
  {
    if (!is_array($rows) || empty($rows)) return '';
    $html = '<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="width:100%; border-collapse:collapse;">';
    foreach ($rows as $r) {
      if (!is_array($r)) continue;
      $label = isset($r['label']) ? trim((string)$r['label']) : '';
      $value = isset($r['value']) ? (string)$r['value'] : '';
      if ($label === '' && trim($value) === '') continue;
      $html .= '<tr>';
      $html .= '<td style="padding:8px 10px; border-bottom:1px solid #e9ecef; width:170px; color:#334; font-weight:600; vertical-align:top;">' . htmlspecialchars($label) . '</td>';
      $html .= '<td style="padding:8px 10px; border-bottom:1px solid #e9ecef; color:#111; vertical-align:top;">' . nl2br(htmlspecialchars($value)) . '</td>';
      $html .= '</tr>';
    }
    $html .= '</table>';
    return $html;
  }
}

if (!function_exists('email_extract_kv_rows_from_body')) {
  function email_extract_kv_rows_from_body($body)
  {
    $body = is_string($body) ? $body : '';
    $body = trim($body);
    if ($body === '') return [];

    // Normalize HTML-ish content into lines
    $normalized = preg_replace('/<\s*br\s*\/?>/i', "\n", $body);
    $normalized = preg_replace('/<\s*\/\s*p\s*>/i', "\n", $normalized);
    $normalized = preg_replace('/<\s*\/\s*div\s*>/i', "\n", $normalized);
    $normalized = strip_tags($normalized);
    $normalized = html_entity_decode($normalized, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    $lines = preg_split('/\r\n|\r|\n/', $normalized);
    $rows = [];
    foreach ($lines as $line) {
      $line = trim((string)$line);
      if ($line === '') continue;
      $lc = strtolower($line);
      if (strpos($lc, 'baad afzalus salaam') === 0 || strpos($lc, 'baad afzalus salam') === 0) continue;

      if (preg_match('/^([A-Za-z0-9][A-Za-z0-9 \-\/#_]{1,60})\s*:\s*(.+)$/', $line, $m)) {
        $label = trim($m[1]);
        $value = trim($m[2]);
        if ($label !== '' && $value !== '') {
          $rows[] = ['label' => $label, 'value' => $value];
        }
      }
    }
    return $rows;
  }
}

if (!function_exists('email_body_is_full_document')) {
  function email_body_is_full_document($body)
  {
    if (!is_string($body)) return false;
    return (stripos($body, '<!doctype') !== false) || (stripos($body, '<html') !== false);
  }
}
