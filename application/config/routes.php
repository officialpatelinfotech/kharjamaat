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
// Corpus Funds feature routes
$route['admin/corpusfunds'] = 'Admin/corpusfunds';
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


// CLI/Cron routes (important on case-sensitive servers)
$route['emailworker'] = 'EmailWorker';

// Legal & Policy Pages
$route['terms']   = 'legal/terms';
$route['privacy'] = 'legal/privacy';
$route['refund']  = 'legal/refund';
$route['contact'] = 'legal/contact';

// Public menu page (no login required)
$route['menu'] = 'publicmenu';
