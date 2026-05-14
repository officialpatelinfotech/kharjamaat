<?php
$anjuman = file('/Applications/MAMP/htdocs/kharjamaat/application/views/Anjuman/Home.php');

$html_start = 1185;
$html_end = 1442;
$html_snippet = implode("", array_slice($anjuman, $html_start, $html_end - $html_start + 1));

$js_start = 4140;
$js_end = 4336;
$js_snippet = implode("", array_slice($anjuman, $js_start, $js_end - $js_start + 1));

$admin = file_get_contents('/Applications/MAMP/htdocs/kharjamaat/application/views/Admin/Home.php');

// 1. Add CSS before </style>
$css = "
  .chart-container {
    background: #fff;
    border-radius: 15px;
    padding: 18px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  }
</style>";
$admin = str_replace('</style>', $css, $admin);

// 2. Add HTML after the FIRST <hr> tag ONLY
$hr_pos = strpos($admin, '<hr>');
if ($hr_pos !== false) {
    // We want to insert AFTER the <hr> tag. <hr> is 4 chars.
    $insert_pos = $hr_pos + 4;
    $admin = substr_replace($admin, "\n" . $html_snippet, $insert_pos, 0);
}

// 3. Add JS right before the final script block
// The final script block starts with `<script>` and sets colors. Let's find the `$(document).ready` script.
// It's easier to insert it right before the final `</script>` or right before the `<script>` tag that starts the color logic.
$script_pos = strrpos($admin, '<script>');
if ($script_pos !== false) {
    $admin = substr_replace($admin, $js_snippet . "\n", $script_pos, 0);
}

file_put_contents('/Applications/MAMP/htdocs/kharjamaat/application/views/Admin/Home.php', $admin);
echo "SUCCESS";
