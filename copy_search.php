<?php
$anjuman = file('/Applications/MAMP/htdocs/kharjamaat/application/views/Anjuman/Home.php');

$html_start = 1185;
$html_end = 1442;
$html_snippet = implode("", array_slice($anjuman, $html_start, $html_end - $html_start + 1));

$js_start = 4140;
$js_end = 4336;
$js_snippet = implode("", array_slice($anjuman, $js_start, $js_end - $js_start + 1));

$admin = file_get_contents('/Applications/MAMP/htdocs/kharjamaat/application/views/Admin/Home.php');

$insert_html = '  <hr>' . "\n" . $html_snippet;
$admin = str_replace('  <hr>', $insert_html, $admin);

$insert_js = "\n" . $js_snippet . "\n</script>";
$admin = str_replace('</script>', $insert_js, $admin);

file_put_contents('/Applications/MAMP/htdocs/kharjamaat/application/views/Admin/Home.php', $admin);
echo "SUCCESS";
