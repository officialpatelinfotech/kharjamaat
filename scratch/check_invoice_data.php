<?php
define('BASEPATH', '1');
define('ENVIRONMENT', 'development');
$db = [];

// Load CodeIgniter framework bootstrap
ob_start();
require_once(__DIR__ . '/../index.php');
ob_end_clean();

$CI =& get_instance();
$CI->load->model('AnjumanM');
$res = $CI->AnjumanM->get_all_member_miqaat_payments('Ashara', true, null);

$user_data = null;
foreach ($res['members'] as $m) {
    if ($m['ITS_ID'] == 20321805) {
        $user_data = $m;
        break;
    }
}

echo "User 20321805 Invoices:\n";
echo json_encode($user_data ? $user_data['miqaat_invoices'] : null, JSON_PRETTY_PRINT) . "\n";
