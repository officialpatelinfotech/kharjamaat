<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
| $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
$route['admin/corpusfunds'] = 'Admin/corpusfunds';
$route['admin/ekramfunds'] = 'Admin/ekramfunds';
$route['admin/corpusfunds/new'] = 'Admin/corpusfunds_new';
$route['admin/corpusfunds/list'] = 'Admin/corpusfunds_list';
$route['admin/corpusfunds/create'] = 'Admin/corpusfunds_create';
$route['admin/corpusfunds/hofs'] = 'Admin/corpusfunds_hofs';
$route['admin/corpusfunds/update_assignments'] = 'Admin/corpusfunds_update_assignments';
$route['admin/corpusfunds/delete_assignment'] = 'Admin/corpusfunds_delete_assignment';
$route['admin/corpusfunds/update_fund'] = 'Admin/corpusfunds_update_fund';
$route['admin/corpusfunds/delete_fund'] = 'Admin/corpusfunds_delete_fund';
*/
$route['default_controller'] = 'Home';
// Ekram fund routes
$route['admin/ekramfunds/new'] = 'Admin/ekramfunds_new';
$route['admin/ekramfunds/create'] = 'Admin/ekramfunds_create';
$route['payment/ccavenue/checkout'] = 'PaymentCCAvenue/checkout';
$route['payment/ccavenue/initiate'] = 'PaymentCCAvenue/initiate';
$route['payment/ccavenue/callback'] = 'PaymentCCAvenue/callback';
$route['accounts/corpusfunds'] = 'accounts/corpusfunds_details';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Expense module routes
$route['admin/expense'] = 'Admin/expense';
$route['admin/expense/source-of-funds'] = 'Admin/expense_source_of_funds';
$route['admin/expense/source-create'] = 'Admin/expense_source_create';
$route['admin/expense/source-update'] = 'Admin/expense_source_update';
$route['admin/expense/source-delete'] = 'Admin/expense_source_delete';

// Laagat / Rent module routes
$route['admin/laagat/create'] = 'Admin/laagat_create';
$route['admin/laagat/manage'] = 'Admin/laagat_manage';
$route['admin/laagat/check-duplicate'] = 'Admin/laagat_check_duplicate';




// CLI/Cron routes (important on case-sensitive servers)
$route['emailworker'] = 'EmailWorker';

// Legal & Policy Pages
$route['terms']   = 'legal/terms';
$route['privacy'] = 'legal/privacy';
$route['refund']  = 'legal/refund';
$route['contact'] = 'legal/contact';

// Public menu page (no login required)
$route['menu'] = 'publicmenu';

// Cookie consent
$route['cookies/accept'] = 'cookies/accept';

// Member Madresa pages
$route['accounts/madresa/payment-history/(:num)'] = 'accounts/madresa_payment_history/$1';

// Madresa module
$route['madresa'] = 'madresa/index';
$route['madresa/classes'] = 'madresa/classes';
$route['madresa/classes/new'] = 'madresa/classes_new';
$route['madresa/classes/create'] = 'madresa/classes_create';
$route['madresa/classes/view/(:num)'] = 'madresa/classes_view/$1';
$route['madresa/classes/receive-payment/(:num)/(:num)'] = 'madresa/classes_receive_payment/$1/$2';
$route['madresa/classes/payment-history/(:num)'] = 'madresa/classes_payment_history/$1';
$route['madresa/classes/payment-receipt/(:num)'] = 'madresa/classes_payment_receipt/$1';
$route['madresa/classes/edit/(:num)'] = 'madresa/classes_edit/$1';
$route['madresa/classes/update/(:num)'] = 'madresa/classes_update/$1';
$route['madresa/classes/delete/(:num)'] = 'madresa/classes_delete/$1';
$route['madresa/student-details'] = 'madresa/student_details';

// Admin alias for Madresa module
$route['admin/madresa'] = 'madresa/index';

// Admin aliases for Madresa sub-routes (keep admin URL space)
$route['admin/madresa/classes'] = 'madresa/classes';
$route['admin/madresa/classes/new'] = 'madresa/classes_new';
$route['admin/madresa/classes/create'] = 'madresa/classes_create';
$route['admin/madresa/classes/view/(:num)'] = 'madresa/classes_view/$1';
$route['admin/madresa/classes/payment-history/(:num)'] = 'madresa/classes_payment_history/$1';
$route['admin/madresa/classes/payment-receipt/(:num)'] = 'madresa/classes_payment_receipt/$1';
$route['admin/madresa/classes/edit/(:num)'] = 'madresa/classes_edit/$1';
$route['admin/madresa/classes/update/(:num)'] = 'madresa/classes_update/$1';
$route['admin/madresa/classes/delete/(:num)'] = 'madresa/classes_delete/$1';
$route['admin/madresa/student-details'] = 'madresa/student_details';
