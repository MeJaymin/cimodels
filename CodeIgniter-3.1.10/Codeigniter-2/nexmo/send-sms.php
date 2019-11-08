<?php
//require_once __DIR__ . '/../config.php';
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once  'vendor/autoload.php';
$NEXMO_API_KEY = "c467dc21";
$NEXMO_API_SECRET = "6nHln1xD94e25to5";
$client = new Nexmo\Client(new Nexmo\Client\Credentials\Basic($NEXMO_API_KEY, $NEXMO_API_SECRET));     
//echo '<pre>'; print_r($client); die;
$TO_NUMBER = 2063979956;
$message = $client->message()->send([
    // 'to' => 19174468872,
    'to' => 12063979956,
    'from' => 12029750880,
    'text' => 'A text message sent using the Nexmo SMS API'
]);


// $message = $client->message()->send([
//     'to' => 2063979956,
//     'from' => 2029750880,
//     'text' => 'Test message from the Nexmo PHP Client'
// ]);
echo '<pre>';print_r($message); 
var_dump($message->getResponseData());

?>