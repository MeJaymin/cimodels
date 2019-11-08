<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* print function short form */

function pr($data) {
    echo "<pre/>";
    print_r($data);
}


function isAdmin() {
    $CI = & get_instance();
    if (isset($CI->session->userdata['Admin'])) {
        return true;
    } else {
        return false;
    }
}


function current_full_url()
{
    $CI =& get_instance();

    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
}

/*check login as admin*/

function RandomPassword()
{
    $chars = array(
                'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
                'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '?', '!', '@', '#',
                '$', '%', '^', '&', '*', '(', ')', '[', ']', '{', '}', '|', ';', '/', '=', '+'
            );

            shuffle($chars);
            $token = '';

            for ($i = 0; $i < 10; $i++) { // <-- $num_chars instead of $len
                $token .= $chars[mt_rand(0, 10)];
            }
    return $token;
}

function sendmail($data) 
{ 
    $CI =& get_instance();
    $config = Array(
          'protocol' => 'smtp',
          'smtp_host' => 'smtp.googlemail.com',
          'smtp_port' => 587,
          'smtp_user' => 'jayminzap@gmail.com',//php.zaptech@gmail.com',
          'smtp_pass' => 'jaymin321#',
          'mailtype' => 'html',
          'charset' => 'iso-8859-1',
          'wordwrap' => TRUE
        );
        /*$config = Array(
          'protocol' => 'smtp',
          'smtp_host' => 'mail.artmarketanalyses.com',
          'smtp_port' =>  587,
          'smtp_user' => 'no-reply@artmarketanalyses.com',//php.zaptech@gmail.com',
          'smtp_pass' => 'am+Y19hk1TRh',
          'mailtype' => 'html',
          'charset' => 'iso-8859-1',
          'wordwrap' => TRUE
        );*/
    // $config['protocol'] = 'smtp';
    // $config['charset'] = 'iso-8859-1';
    // $config['wordwrap'] = TRUE;
    // $config['mailtype'] = 'html';
    // $config['smtp_crypto'] = 'tls';
    // $config['smtp_host'] = 'smtp.elasticemail.com';
    // $config['smtp_user'] = 'jayminzap@gmail.com';
    // $config['smtp_pass'] = '849e52cf-dee9-4d25-b854-ad5dcc50789b';
    // $config['smtp_port'] = '2525';
    $CI->load->library('email');
    $CI->email->initialize($config);
    
    $email_from = $CI->config->item('email_from');
    /*pr($data);
    die();*/
    $CI->email->from($email_from, 'GiftCast Team');
    $CI->email->to($data['to']);
    //$CI->email->addBCC('jayminzap@gmail.com');
    $CI->email->subject($data['subject']);
    $CI->email->message($data['message']);
    $mail=$CI->email->send();
    echo $CI->email->print_debugger();die;
}
function send_android_notification($registration_ids, $message,$title,$giftdata) 
{
    #prep the bundle
    $msg = array(
        'body'  => $message,
        'title' => $title,
    );
    
    $data =  array('giftdata' => $giftdata, 'body'  => $message,'title' => $title,'image'=> 'www/img/play-button.png','style' => 'inbox','summaryText' => 'There are %n% notifications','priority'=> 1);

    //$fields = array('to'=> $registration_ids,'notification' => $msg,'data' => $data);   
    $fields = array('to'=> $registration_ids,'data' => $data);   

    $headers = array('Authorization: key=' . API_ACCESS_KEY,'Content-Type: application/json');

    #Send Reponse To FireBase Server
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );
    curl_close( $ch );

    #Echo Result Of FireBase Server

    return $result;
}

function send_ios_notification($tToken, $vMessage, $title, $giftdata="") 
{

    
    // Provide the Host Information.

    $tHost = 'gateway.sandbox.push.apple.com';

    $tPort = 2195;


    
    $tCert = APPPATH.'GiftCastDev_new.pem';
    
    
    $tPassphrase = '123456';

    // Provide the Device Identifier (Ensure that the Identifier does not have spaces in it).
    // Replace this token with the token of the iOS device that is to receive the notification.
    //$tToken = 'bb4a221836b622b5f8067e742defcfb64a06d0d3c43a4eb71cf4e36a7bf21a06'
    // The message that is to appear on the dialog.

    $tAlert = $vMessage;

    // The Badge Number for the Application Icon (integer >=0).

    $tBadge = 1;

    // Audible Notification Option.

    $tSound = 'default';

    // The content that is returned by the LiveCode "pushNotificationReceived" message.

    $tPayload = 'APNS';

    // Create the message content that is to be sent to the device.

    $tBody['aps'] = array(
        'alert' => $tAlert,
        'badge' => $tBadge,
        'sound' => $tSound,
        'data' => 'Hello',
        'content-available' => 1
    );

    $tBody ['payload'] = $tPayload;

    // Encode the body to JSON.

    $tBody = json_encode($tBody);

    // Create the Socket Stream.

    $tContext = stream_context_create();

    stream_context_set_option($tContext, 'ssl', 'local_cert', $tCert);

    // Remove this line if you would like to enter the Private Key Passphrase manually.

    stream_context_set_option($tContext, 'ssl', 'passphrase', $tPassphrase);

    // Open the Connection to the APNS Server.

    $tSocket = stream_socket_client('ssl://' . $tHost . ':' . $tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $tContext);
    // Check if we were able to open a socket.

    if (!$tSocket)
        exit("APNS Connection Failed: $error $errstr" . PHP_EOL);

    // Build the Binary Notification.

    $tMsg = chr(0) . chr(0) . chr(32) . pack('H*', $tToken) . pack('n', strlen($tBody)) . $tBody;

    // Send the Notification to the Server.

    $tResult = fwrite($tSocket, $tMsg, strlen($tMsg));
    fclose($tSocket);
    if ($tResult)
        return true;
    else
        return false;
}


function callapi($data)
{
    $CI =& get_instance();
    $url =  API_BASE_URL.$data['api_url'];        
    $handle = curl_init($url);

    if(empty($data['postdata']))
    {  
        $data['postdata'] = "";
    }
    // print_r($data['postdata']); die;
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data['postdata']);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);                                                                      
    $result = curl_exec($handle);
    curl_close($handle);
    $datas = json_decode($result, TRUE);
    return $datas;
}

function dwollaConfig()
{
    require('vendor/autoload.php');
    $access_token = getAccessToken();
    DwollaSwagger\Configuration::$access_token = $access_token;
    // DwollaSwagger\Configuration::$debug = 1;
    $apiClient = new DwollaSwagger\ApiClient(DWOLLA_API_URL);
    return $customersApi = new DwollaSwagger\CustomersApi($apiClient);
}

function synapsefyClientDetails()
{
    // $options = array(
    //     /*'oauth_key'=> USER_OAUTH_KEY,*/ # Optional,
    //     'fingerprint'=> '',
    //     'client_id'=> 'client_id_BgIcot9iFnbGydKer517NW3wpQzA8quRUkEDZ0J6',
    //     'client_secret'=> 'client_secret_YgiN7loX8rIQTzkv2dHOy64AZ0xRwaBfUM0nuLh5',
    //     'development_mode'=> true, # true will ping sandbox.synapsepay.com
    //     'ip_address'=> $_SERVER['HTTP_HOST'],
    //     'oauth_key' => ''
    // );
    //Live
    $options = array(
        /*'oauth_key'=> USER_OAUTH_KEY,*/ # Optional,
        'fingerprint'=> '',
        'client_id'=> 'client_id_eWS0Tf5htsDKBk2Gbay91VEZU0igR4dvCr3jP76M',
        'client_secret'=> 'client_secret_6lhqYTzyO1f5FtoQipmDrweMnLcWSCPRBIJ0Ggjs',
        'development_mode'=> false, # true will ping sandbox.synapsepay.com
        'ip_address'=> '202.131.115.106',
        'oauth_key' => ''
    );
    return $options;
}

