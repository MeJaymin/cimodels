<?php 
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once  'vendor/autoload.php';
$NEXMO_API_KEY = "c467dc21";
$NEXMO_API_SECRET = "6nHln1xD94e25to5";
$client = new Nexmo\Client(new Nexmo\Client\Credentials\Basic($NEXMO_API_KEY, $NEXMO_API_SECRET)); 
$REQUEST_ID = "8a3b08e345d74e8389eb8fc464cd2cbf";
$CODE = 1027;
$verification = new \Nexmo\Verify\Verification($REQUEST_ID);
$result = $client->verify()->check($verification, $CODE);
var_dump($result->getResponseData());
?>