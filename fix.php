<?php
$admin = file_get_contents('/Applications/MAMP/htdocs/kharjamaat/application/views/Admin/Home.php');
$admin = preg_replace('#</script>$#', '', trim($admin));
file_put_contents('/Applications/MAMP/htdocs/kharjamaat/application/views/Admin/Home.php', $admin . "\n");
echo "DONE";
