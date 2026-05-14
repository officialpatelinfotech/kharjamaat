<?php
$admin = file_get_contents('/Applications/MAMP/htdocs/kharjamaat/application/views/Admin/Home.php');
$css = '
  .chart-container {
    background: #fff;
    border-radius: 15px;
    padding: 18px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  }
</style>';
$admin = str_replace('</style>', $css, $admin);
file_put_contents('/Applications/MAMP/htdocs/kharjamaat/application/views/Admin/Home.php', $admin);
echo "DONE CSS";
