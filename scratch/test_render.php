<?php
$selected_year = '1447';
$current_hijri_year = '1448';
$hijri_years = ['1448', '1447', '1446', '1445'];

$years = isset($hijri_years) && is_array($hijri_years) ? $hijri_years : [];
$defaultYear = isset($selected_year) && $selected_year !== ''
  ? $selected_year
  : (isset($current_hijri_year) && $current_hijri_year !== '' ? $current_hijri_year : (!empty($years) ? end($years) : ''));

echo "defaultYear: " . var_export($defaultYear, true) . "\n";

echo "Options:\n";
foreach ($years as $y) {
    $selected = ($defaultYear === $y ? 'selected' : '');
    echo "  y: " . var_export($y, true) . " -> " . ($selected ? 'SELECTED' : 'not selected') . "\n";
}
