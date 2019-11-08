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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
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
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* User Webservice Start*/

$route['api/ws_admin_signin'] = 'webservices/Admin/login';
$route['api/ws_admin_add_company'] = 'webservices/Company/add_company';
$route['api/ws_admin_edit_company'] = 'webservices/Company/edit_company';
$route['api/ws_admin_delete_company'] = 'webservices/Company/delete_company';
$route['api/ws_admin_fetch_company'] = 'webservices/Company/fetch_company';
$route['api/ws_admin_company_login'] = 'webservices/Company/company_login';
$route['api/ws_check_file_upload'] = 'webservices/Company/file_upload';

$route['api/ws_admin_add_role'] = 'webservices/Roles/add_role';
$route['api/ws_admin_edit_role'] = 'webservices/Roles/edit_role';
$route['api/ws_admin_delete_role'] = 'webservices/Roles/delete_role';
$route['api/ws_admin_fetch_role'] = 'webservices/Roles/fetch_role';
/* User Webservice Ends*/

/* Admin Routes Start*/
$route['admin'] = 'admin/admin/index';
$route['admin/dashboard'] = 'admin/admin/admin_dashboard';

/*Company Routes*/  
$route['admin/company-listing'] = 'admin/company/fetch_company';  
$route['admin/add-company'] = 'admin/company/add_company';
$route['admin/edit-company/(:any)'] = 'admin/company/edit_company/$1';
$route['admin/delete-company'] = 'admin/company/delete_company';
$route['admin/delete-company/(:any)'] = 'admin/company/delete_company/$1';

/*Roles Routing*/
$route['admin/roles-listing'] = 'admin/roles/fetch_roles';
$route['admin/add-roles'] = 'admin/roles/add_roles';
$route['admin/edit-roles/(:any)'] = 'admin/roles/edit_roles/$1';
$route['admin/delete-roles'] = 'admin/roles/delete_roles';
$route['admin/delete-roles/(:any)'] = 'admin/roles/delete_roles/$1';