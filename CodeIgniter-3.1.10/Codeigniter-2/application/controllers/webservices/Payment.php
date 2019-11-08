<?php
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
class Payment extends CI_Controller 
{

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Transactions_model');
        $this->load->model('Card_details_model');
        $this->load->model('Payment_model');
    }

    /*
    @Author: Jaymin Sejpal
    @description: Payment through Credit Card
    */

    public function send_amount_credit_card()
    {
        $this->load->library('authorize_net');
        $this->form_validation->set_rules('x_card_num', 'x_card_num', 'trim|required');
        $this->form_validation->set_rules('x_exp_date', 'x_exp_date', 'trim|required');
        $this->form_validation->set_rules('x_card_code', 'x_card_code', 'trim|required');
        $this->form_validation->set_rules('amount', 'amount', 'trim|required');
        $this->form_validation->set_rules('gift_id', 'gift_id', 'trim|required');
        $this->form_validation->set_rules('payment_type', 'payment_type', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        } 
        else 
        {
            $x_card_num = $this->input->post('x_card_num');
            $x_exp_date = $this->input->post('x_exp_date');
            $x_card_code = $this->input->post('x_card_code');
            $amount = $this->input->post('amount');
            $gift_id = $this->input->post('gift_id');
            $payment_type = $this->input->post('payment_type');

           $auth_net = array(
            'x_card_num'            => $x_card_num, //4111111111111111 Visa
            'x_exp_date'            => $x_exp_date, // 12/18',
            'x_card_code'           => $x_card_code, // 123,
            'x_description'         => 'A test transaction',
            'x_amount'              => $amount, //40,
            'x_first_name'          => 'John',
            'x_last_name'           => 'Doe',
            'x_address'             => '123 Green St.',
            'x_city'                => 'Lexington',
            'x_state'               => 'KY',
            'x_zip'                 => '40502',
            'x_country'             => 'US',
            'x_phone'               => '555-123-4567',
            'x_email'               => 'jaymin.s@zaptechsolutions.com',
            );
            $this->authorize_net->setData($auth_net);
            // Try to AUTH_CAPTURE
            if( $this->authorize_net->authorizeAndCapture() )
            {
                $transaction_id = $this->authorize_net->getTransactionId();
                $approval_code = $this->authorize_net->getApprovalCode();
                $transaction_details = serialize($this->authorize_net);
                $transaction_data['gift_id'] = $gift_id;
                $transaction_data['payment_type'] = $payment_type;
                $transaction_data['transaction_id'] = $transaction_id;
                $transaction_data['transaction_details'] = $transaction_details;
                $transaction_data['created_at'] = date("Y-m-d H:i:s");
                $transaction_insert = $this->Transactions_model->Insert_Data($transaction_data);
                if($transaction_insert)
                {
                    $response['code'] = 1;
                    $response['status'] = "success";
                    $response['message'] = 'Payment done successfully';
                    echo json_encode($response); 
                }
            }
            else
            {
                $response['code'] = 0;
                $response['status'] = "error";
                $response['message'] = 'Payment failed';
                echo json_encode($response); 
                //Get error
                echo '<p>' . $this->authorize_net->getError() . '</p>';
                //Show debug data
                $this->authorize_net->debug();
            }
        }
    }

    /*
    */
    public function store_card_details()
    {
        $this->form_validation->set_rules('u_id', 'u_id', 'trim|required');
        $this->form_validation->set_rules('card_num', 'card_num', 'trim|required');
        $this->form_validation->set_rules('card_type', 'card_type', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        } 
        else 
        {
            $card_data['u_id'] = $this->input->post('u_id');
            $card_data['card_num'] = $this->input->post('card_num');
            $card_data['card_type'] = $this->input->post('card_type');
            $card_data['created_at'] = date("Y-m-d H:i:s");

            $Insert = $this->Card_details_model->Insert_Data($card_data);

            if (!empty($Insert))
            {
                $response['code'] = 1;
                $response['status'] = "success";
                $response['message'] = 'Card Details have been added successfully';
                echo json_encode($response);
            }
        }
    }

    public function fetch_bank_details()
    {
        $this->form_validation->set_rules('email_id', 'email_id', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        } 
        else 
        {
            $payment_data = $this->Payment_model->getAnyData();
            $tax = $payment_data[0]->tax;

            $data = array();
            $email_id = $this->input->post('email_id');
            $customersApi = dwollaConfig();
            $customers = $customersApi->_list('','',$email_id); // pass email address to get customer data
            //echo '<pre>';print_r($customers->_embedded->customers); die;
            if(!empty($customers->_embedded->customers))
            {
                $getCustomerId = $customers->_embedded->customers[0]->id; //get customer id
                $customerUrl = DWOLLA_CUSTOMER_URL.$getCustomerId; 
                $apiClient = new DwollaSwagger\ApiClient(DWOLLA_API_URL);
                $fsApi = new DwollaSwagger\FundingsourcesApi($apiClient);
                $fundingSources = $fsApi->getCustomerFundingSources($customerUrl,'');
                //pr($fundingSources->_embedded->{'funding-sources'});
                $data = array();
                $bank_data = array();
                foreach ($fundingSources->_embedded->{'funding-sources'} as $key => $value) 
                {
                    if($value->type == 'bank' && $value->status == 'verified')
                    { 
                        if($value->removed != 1) //check if funding source is removed or inactive
                        {
                            $bank_data['unique_bank_id'] = $value->id;
                            $bank_data['bankName'] = $value->name;
                            $bank_data['bankAccountType'] = $value->bankAccountType;
                            array_push($data,$bank_data);
                        }
                    }
                }
                if(!empty($data))
                {
                    $response['code'] = 1;
                    $response['status'] = "success";
                    $response['firstName'] = $customers->_embedded->customers[0]->firstName;
                    $response['lastName'] = $customers->_embedded->customers[0]->lastName;
                    $response['email'] = $customers->_embedded->customers[0]->email;
                    $response['type'] = $customers->_embedded->customers[0]->type;
                    $response['address1'] = $customers->_embedded->customers[0]->address1;
                    $response['city'] = $customers->_embedded->customers[0]->city;
                    $response['state'] = $customers->_embedded->customers[0]->state;
                    $response['postalCode'] = $customers->_embedded->customers[0]->postalCode;
                    $response['tax'] = $tax;
                    $response['data'] = $data;
                    $response['message'] = 'Bank Details Fetched successfully';
                }
                else
                {
                    $response['code'] = 0;
                    $response['status'] = "error";
                    $response['message'] = 'No Details found';
                }
            }
            else
            {
                $response['code'] = 0;
                $response['status'] = "error";
                $response['data'] = $data;
                $response['message'] = 'No Details found';
            }
            echo json_encode($response);
        }
    }

    public function fetch_creditcard_details()
    {
        $this->form_validation->set_rules('email_id', 'email_id', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        } 
        else 
        {
            $email_id = $this->input->post('email_id');
            require BASE_PATH.'application/third_party/sdk-php/autoload.php';
            error_reporting(E_ALL);
            
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName("2xmBMWu5J6q");
            $merchantAuthentication->setTransactionKey("5D5d5XS983GynKVs");

            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);

            $refId = 'ref' . time();
            $customerProfileId = "1503139290";
            $customerPaymentProfileId = "1502496494";
            //request requires customerProfileId and customerPaymentProfileId
            $request = new AnetAPI\GetCustomerPaymentProfileRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setCustomerProfileId($customerProfileId);
            $request->setCustomerPaymentProfileId($customerPaymentProfileId);

            $controller = new AnetController\GetCustomerPaymentProfileController($request);
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
            if(($response != null))
            {
                if ($response->getMessages()->getResultCode() == "Ok")
                {
                    $response['customer_profile_id'] = $response->getPaymentProfile()->getCustomerPaymentProfileId();
                    $response['customer_billing_address'] = $response->getPaymentProfile()->getbillTo()->getAddress();
                    $response['customer_credit_card_num'] = $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber();
                    
                    $response['code'] = 1;
                    $response['status'] = "success";
                    $response['message'] = 'Credit Card Details fetched successfully';
                    /*echo "Customer Payment Profile Id: " . $response->getPaymentProfile()->getCustomerPaymentProfileId() . "\n";
                    echo "Customer Payment Profile Billing Address: " . $response->getPaymentProfile()->getbillTo()->getAddress(). "\n";
                    echo "Customer Payment Profile Card Last 4 " . $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber(). "\n";
                    echo '<pre>';
                    print_r($response->getPaymentProfile());*/
                }
            }
            echo json_encode($response);
        }
    }
}