<?php
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
class Test extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Logs_model');
        $this->load->model('Notification_model');
        $this->load->model('Payment_model');
        $this->load->model('Report_model');
        $this->load->library('email');
    }

    public function createWebHookDwolla()
    {
        require('vendor/autoload.php');
        $apiClient = new DwollaSwagger\ApiClient(DWOLLA_API_URL);
        $webhookApi = new DwollaSwagger\WebhooksubscriptionsApi($apiClient);
        $subscription = $webhookApi->create(array (
          'url' => 'http://giftcast.me/',
          'secret' => 'gSosHq8mKmsELf8zKojOkzCOaRog4VlgwRHJPODAqBqnvEFI6x',
        ));
        $subscription; # => "https://api-sandbox.dwolla.com/webhook-subscriptions/5af4c10a-f6de-4ac8-840d-42cb65454216"
        print_r($subscription);
    }
}
?>