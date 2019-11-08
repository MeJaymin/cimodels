<?php
//require_once __DIR__ . '/../config.php';
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once  'vendor/autoload.php';
$NEXMO_API_KEY = "c467dc21";
$NEXMO_API_SECRET = "6nHln1xD94e25to5";
$client = new Nexmo\Client(new Nexmo\Client\Credentials\Basic($NEXMO_API_KEY, $NEXMO_API_SECRET));     
//echo '<pre>'; print_r($client); die;
$TO_NUMBER = 12063979956;

$verification = new \Nexmo\Verify\Verification($TO_NUMBER, 'NexmoVerifyTest');
$client->verify()->start($verification);
echo "Started verification, `request_id` is " . $verification->getRequestId();

// echo '<pre>';print_r($message); 
// var_dump($message->getResponseData());

?>