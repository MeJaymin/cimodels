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
  | example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so ttshirt a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  | https://codeigniter.com/user_guide/general/routing.html
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
  | $route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  | $route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names ttshirt contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples: my-controller/index -> my_controller/index
  |   my-controller/my-method -> my_controller/my_method
 
/* Start: Admin Panel */
  
/*START: USER ROUTES*/
$route['admin'] = 'admin/admin/index';  
$route['admin/user'] = 'admin/User_controller/index';
$route['admin/edit-user/(:any)'] = 'admin/User_controller/edit_user/$1';
$route['admin/delete'] = 'admin/User_controller/delete_user';
$route['admin/delete/(:any)'] = 'admin/User_controller/delete_user/$1';
$route['admin/logout'] = 'admin/admin/logout';
$route['admin/detail/(:any)'] = 'admin/User_controller/detail_user/$1';
$route['admin/change-status'] = 'admin/User_controller/ChangeUserStatus';
$route['admin/delete-profile-picture'] = 'admin/User_controller/delete_profile_picture';

$route['admin/add-user'] = 'admin/User_controller/AddNewUser';
$route['changepwd'] = 'admin/Web_controller/ChangePwd';
$route['view-success']='admin/Web_controller/Load_successmsg';
$route['view-error']='admin/Web_controller/Load_error';
$route['application_store'] = 'admin/Web_controller/open_application';
/*END: USER ROUTES*/

/*START: GIFTS ROUTES*/
$route['admin/gifts']='admin/Gift_controller/index';
$route['admin/detail-gift/(:any)'] = 'admin/Gift_controller/detail_gift_view/$1';
$route['admin/gifts-truncate']='admin/Gift_controller/gifts_truncate';
/*END: GIFTS ROUTES*/

/*START: PUSH NOTIFICATION ROUTES*/
$route['admin/notification']='admin/Notification_controller/index';
/*END: PUSH NOTIFICATION ROUTES*/

/*START: PUSH NOTIFICATION ROUTES*/
$route['admin/report-log']='admin/Report_controller/index';
/*END: PUSH NOTIFICATION ROUTES*/

/*START: PUSH NOTIFICATION ROUTES*/
$route['admin/set-tax']='admin/Payment_controller/index';
/*END: PUSH NOTIFICATION ROUTES*/
/*START: FALLBACK URL FOR SYNAPSEFY*/
$route['fallback-url']='Error_controller/fallback_url';
/*END: FALLBACK URL FOR SYNAPSEFY*/

/* End: Admin Panel */


/* Start: Webservices */

/* User Webservice Start*/

$route['api/ws_signup'] = 'webservices/User/signup';
$route['api/ws_signin'] = 'webservices/User/login';
$route['api/ws_social'] = 'webservices/Social/socialLogin';
$route['api/ws_forget_password'] = 'webservices/User/forgot_password';
$route['api/ws_change_user_password'] = 'webservices/User/change_user_password';
$route['api/ws_update_user_details'] = 'webservices/User/update_user_details'; 
$route['api/ws_fetch_user_details'] = 'webservices/User/user_details';
/* User Webservice end*/


/* Gift Webservice Start*/

$route['api/ws_send_gift'] = 'webservices/Gift/send_gift';
$route['api/ws_send_gift_later'] = 'webservices/Gift/gift_send_later';
$route['api/ws_crone_send_gift_later'] = 'webservices/Gift/crone_gift_send';
$route['api/ws_gift_history'] = 'webservices/Gift/gift_history';
$route['api/ws_gift_history_delete'] = 'webservices/Gift/gift_delete';
$route['api/ws_gift_history_recieved'] = 'webservices/Gift/gift_recieved';
$route['api/ws_gift_opened'] = 'webservices/Gift/gift_opened';

/* Gift Webservice end*/

$route['api/ws_notification_settings'] = 'webservices/Notification/notification_settings';
/* Inivation Webservice Start*/

$route['api/ws_invite'] = 'webservices/User/friend_invitaion';

/* Inivation Webservice End*/


/*Delete Account Webservice Start */

$route['api/ws_deleteuser'] = 'webservices/User/delete_account';

/*Delete Account Webservice End */

/*Report Problem Webservice Start */

$route['api/ws_reportproblem'] = 'webservices/User/report_problem';

/*Report Problem Webservice End */

/*Facebook Login Webservice Start */

$route['api/ws_fblogin'] = 'webservices/User/facebook_login';

/*Facebook Login Webservice End */

/*Send Payment API Starts*/

$route['api/ws_send_payment_credit_card'] = 'webservices/Payment/send_amount_credit_card';
$route['api/ws_store_card_details'] = 'webservices/Payment/store_card_details';
$route['api/ws_fetch_bank_details'] = 'webservices/Payment/fetch_bank_details';
$route['api/fetch_creditcard_details'] = 'webservices/Payment/fetch_creditcard_details';

/*Send Payment API Ends*/

/* End: Webservices */






//$route['404_override'] = 'admin/error_404.php';
$route['404_override'] = 'Error_controller/error_404';
$route['translate_uri_dashes'] = FALSE;


$route['api/ws_dwolla_verified_sender']='webservices/Gift/verifiedSender';
$route['api/ws_dwolla_verified_reciever']='webservices/Gift/verifiedReciever';
$route['api/ws_withDraw']='webservices/Gift/withDrawFund';
$route['api/ws_checkfund']='webservices/Gift/checkFund';

$route['api/ws_create_webhook']='webservices/Test/createWebHookDwolla';

/*@Author: Jaymin,
@Date: 1/1/19,
@Description: Add New Webservice for Synapsefy Payment Gateway*/
$route['api/ws_create_user_synapsefy']='webservices/PaymentGateway/create_user';
$route['api/ws_create_node_synapsefy']='webservices/PaymentGateway/create_node';
$route['api/ws_create_custody_node_synapsefy']='webservices/PaymentGateway/create_custody_node';
$route['api/ws_fetch_node_synapsefy']='webservices/PaymentGateway/fetch_node';
$route['api/ws_transfer_synapsefy']='webservices/PaymentGateway/transfer_payment';
$route['api/ws_fetch_bank_ui_synapsefy']='webservices/PaymentGateway/fetch_bank_synapsefy';
$route['api/ws_check_user_synapsefy']='webservices/PaymentGateway/check_user_synapsefy';
$route['api/ws_delete_node_synapsefy']='webservices/PaymentGateway/delete_node';
$route['api/ws_bank_account_login_synapsefy']='webservices/PaymentGateway/bank_account_login';
$route['api/ws_bank_answer_verify_synapsefy']='webservices/PaymentGateway/verify_bank_answer';
$route['api/ws_link_card_synapsefy']='webservices/PaymentGateway/link_card';
$route['api/ws_fetch_auth_key_synapsefy']='webservices/PaymentGateway/fetch_authkey';
$route['api/ws_check_notification']='webservices/Gift/checkNotification';
$route['api/ws_withdraw_funds']='webservices/PaymentGateway/withdrawal_payment';
$route['api/ws_request_otp']='webservices/User/request_otp';
$route['api/ws_verify_otp']='webservices/User/verify_otp';
$route['api/ws_resend_otp']='webservices/User/resend_otp';
$route['api/ws_payment_user_statement']='webservices/PaymentGateway/user_statement';
$route['api/ws_validate_number']='webservices/Gift/validate_number';
$route['api/ws_logout']='webservices/User/logout';
$route['api/ws_cron_check_transaction']='webservices/PaymentGateway/check_transaction_status';
$route['api/ws_request_2fa']='webservices/PaymentGateway/request_2fa';
$route['api/ws_verify_2fa']='webservices/PaymentGateway/verify_2fa';
$route['api/ws_check_user_status']='webservices/PaymentGateway/check_user_status';
$route['api/ws_update_kyc']='webservices/PaymentGateway/update_kyc';
$route['api/ws_update_user_permission']='webservices/PaymentGateway/update_user_permission';

