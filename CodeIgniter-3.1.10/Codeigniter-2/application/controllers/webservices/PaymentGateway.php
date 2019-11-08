<?php
error_reporting(E_ALL);
require('./synapsefi-php/init.php');
use SynapsePayRest\Client;
class PaymentGateway extends CI_Controller 
{

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('PaymentData_model');
        $this->load->model('Transactions_model');
        $this->load->model('Gift_model');
    }

    public function create_user()
    {
        $this->form_validation->set_rules('id', 'Email id', 'trim|required');
        $this->form_validation->set_rules('email_id', 'Email id', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Phone', 'trim|required');
        //$this->form_validation->set_rules('ssn', 'ssn', 'trim|required');
        //$this->form_validation->set_rules('govt_id', 'Goverment id', 'trim|required');
        //$this->form_validation->set_rules('selfie', 'Selfie', 'trim|required');
        $this->form_validation->set_rules('fullname', 'Fullname', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
        } 
        else 
        {
            $u_id = $this->input->post('id');
            $email_id = $this->input->post('email_id');
            $phone_number = $this->input->post('phone_number');
            $address_street = $this->input->post('address_street');
            $address_city = $this->input->post('address_city');
            $address_subdivision = $this->input->post('address_subdivision');
            $address_postal_code = $this->input->post('address_postal_code');
            $address_country_code = $this->input->post('address_country_code');
            $fullname = $this->input->post('fullname');
            //finger print will be user_pk+email_id .
            $fingerprint = md5($u_id.$email_id);
            //Live
            $options = synapsefyClientDetails();
            
            //$user_id = USER_ID
            $client = new Client($options);
            $where['id'] = $u_id;
            $users = $this->User_model->getAnyData($where);
            $fullname= $fullname;
            if(empty($users[0]->synapsefy_user_id))
            {
                // Create a User

                $create_payload = array(
                    "logins" => array(
                        array(
                            "email" => $email_id,
                            "read_only" => false
                        )
                    ),
                    "phone_numbers" => array(
                        $phone_number //"901.111.1114"
                    ),
                    "legal_names" => array(
                        $fullname
                    ),
                    "extra" => array(
                        "note" => "Interesting user",
                        "supp_id" => "122eddfgbeafrfvbbb",
                        "cip_tag" => 1,
                        "is_business" => false
                    ),
                    "documents" => array(
                        array(
                            "email" => $email_id,
                            "phone_number" => '+'.$phone_number,
                            "ip" => '202.131.115.106',
                            "name" => $fullname,
                            "entity_type" => "M",
                            "entity_scope" => "Arts & Entertainment",
                            // "day" => 2,
                            // "month" => 5,
                            // "year" => 1996,
                            // "address_street" => $address_street,
                            // "address_city" => $address_city,
                            // "address_subdivision" => $address_subdivision,
                            // "address_postal_code" => $address_postal_code,
                            // "address_country_code" => $address_country_code,
                        )
                    )
                );

                $create_response = $client->user->create($create_payload);
                echo '<pre>'; print_r($create_response); die;
                if(!empty($create_response['_id']) && isset($create_response['_id']))
                {
                    $synapse_user_id = $create_response['_id'];
                    $set['synapsefy_user_id'] = $synapse_user_id;
                    $where_user['id'] = $u_id; 
                    $update = $this->User_model->update($set, $where_user);
                    if(!empty($update))
                    {
                        $response['code'] = 1;
                        $response['status'] = "success";
                        $response['message'] = 'Account verified';                  
                    }
                }
                else
                {
                    $response['code'] = 0;
                    $response['status'] = "error";
                    $response['message'] = $create_response['error']['en'];
                }
            }
            else
            {
                $response['code'] = 0;
                $response['status'] = "error";
                $response['message'] = 'User already created';
            }
        }
        echo json_encode($response);
    }

    public function bank_account_login()
    {
        //Get bank listing here
        //https://uat-api.synapsefi.com/v3/institutions/show
        $bank_id = $this->input->post('bank_id');
        $bank_password = $this->input->post('bank_password');
        $bank_name = $this->input->post('bank_name');
        $id = $this->input->post('id');

        $where['id']= $id;
        $user_result = $this->User_model->getAnyData($where);
        $user_id = $user_result[0]->synapsefy_user_id;
        $email_id= $user_result[0]->email_id;
        $fingerprint = md5($id.$email_id);

        $options = synapsefyClientDetails();
        //print_r($options); die;
        //$user_id = USER_ID
        $client = new Client($options);
        //$user_id = "5c063b993c4e2800b091bbd0";    
        $user = $client->user->get($user_id);
        //echo '<pre>'; print_r($user); die;
        $refresh_payload = array('refresh_token' => $user['refresh_token']);
        $refresh_response = $client->user->refresh($refresh_payload);
        //print_r($refresh_response); die;
        $options['oauth_key'] = $refresh_response['oauth_key'];
        $login_node_payload = array(
            "type" => "ACH-US",
            "info" => array(
                "bank_id" => $bank_id,
                "bank_pw" => $bank_password,
                "bank_name" => $bank_name
            )
        );

        $node_login_response = $client->node->add($login_node_payload);
        //print_r($node_login_response); die;
        if(!empty($node_login_response['success']))
        {
            if($node_login_response['http_code'] == '202')
            {
                $response['code'] = 1;
                $response['status'] = "success";
                $response['questions'] = $node_login_response['mfa'];
            }
            else if($node_login_verify_response['http_code'] == 402)
            {
                $response['code'] = 0;
                $response['message'] = 'No bank Accounts exists';

            }
            else if($node_login_verify_response['http_code'] == 503)
            {
                $response['code'] = 0;
                $response['message'] = 'There is maintainence issue while linking your bank';

            }
        }
        else
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = "Invalid Username or password.";
        }
        echo json_encode($response);
    }

    public function verify_bank_answer()
    {
        $bank_id = $this->input->post('bank_id');
        $bank_password = $this->input->post('bank_password');
        $bank_name = $this->input->post('bank_name');
        $access_token = $this->input->post('access_token');
        $answer = $this->input->post('answer');
        $id = $this->input->post('id');

        $where['id']= $id;
        $user_result = $this->User_model->getAnyData($where);
        $user_id = $user_result[0]->synapsefy_user_id;
        $email_id= $user_result[0]->email_id;
        $fingerprint = md5($id.$email_id);

        $options = synapsefyClientDetails();
        //print_r($options); die;
        //$user_id = USER_ID
        $client = new Client($options);

        //$user_id = "5c063b993c4e2800b091bbd0";    
        $user = $client->user->get($user_id);
        $refresh_payload = array('refresh_token' => $user['refresh_token']);
        $refresh_response = $client->user->refresh($refresh_payload);
        $options['oauth_key'] = $refresh_response['oauth_key'];
        $login_node_payload = array(
            "type" => "ACH-US",
            "info" => array(
                "bank_id" => $bank_id,
                "bank_pw" => $bank_password,
                "bank_name" => $bank_name
            )
        );

        $node_login_response = $client->node->add($login_node_payload);
        // Verify ACH-US via MFA

        $login_verify_payload = array(
            "access_token" => $access_token,
            "mfa_answer" => $answer
        );

        $node_login_verify_response = $client->node->verify(null, $login_verify_payload);
        if(!empty($node_login_verify_response))
        {
            if($node_login_verify_response['http_code'] == 200)
            {
                $response['code'] = 1;
                $response['message'] = 'Successfully Linked Banks';
                $response['bank_data'] = $node_login_verify_response['nodes'];

            }
            else
            {
                $response['code'] = 1;
                $response['message'] = 'Incorrect answer added';
                $response['access_token'] = $node_login_verify_response['mfa']['access_token'];
                $response['status'] = "error";
            }
        }
        echo json_encode($response);
    }

    public function link_card()
    {
        $options = synapsefyClientDetails();
        //print_r($options); die;
        //$user_id = USER_ID
        $client = new Client($options);
        $id = $this->input->post('user_id');
        $where['id']= $id;
        $user_result = $this->User_model->getAnyData($where);
        $user_id = $user_result[0]->synapsefy_user_id;

        //$user_id = "5c063b993c4e2800b091bbd0"; //"5c651dfb7f9c202544edb566";  
        $user = $client->user->get($user_id);
        // echo "<pre>";
        // print_r($user); die;
        //25efe818e6e82c8d7654cc5b5dc88f921761ee71018b0eea7c406bf0c496bfe5
        //745b7c6016a8a18f60017a5e921bdc8ca46891fa387d05adaf2fe4f3df640fbe
        //1f3468b8e6a4168c8bd74776909bd8b81f76a537e1a1a8dc3a90fa23ba28faf8
        //915b632663cd3a7cdf3691ea585681c017ec6340052a4562ffe98f7d749c162f
        //475a185dfbe37d3f4066baf01e3ed4f15e8bac269d22bc426dc5d2c9e70ce84b

        $card_number = $this->input->post('card_number');
        $exp_date = $this->input->post('exp_date');
        $nickname = $this->input->post('nickname');
        $info= (object) [
            "nickname" => $nickname,//"My Debit Card1",
            "card_number" => $card_number ,//"Zoo8g2vBUjt7TwmEpRW8f6eQT3AOEEYePw2LkoxD+mO9lOT5OemHlGwgamgLGUbrmWu3DPwnEr2IqDy5YMFVgvQWP3w9nLOFzFFSW43auDgsVAqZScoRf8nI+6/B9KvOEV4XI8JeyXT+O+y3p3RtbiXGmYQNJ56Hy3hs2E5O+yn+3fpLfJQpVvNc38V+aE21VEsJuXFFNtS/8r4jJ6Dx/etTEaE/rtcEUEbwLLHFHjPiOWaHWZPuhXFLtyYrR9zG8FWSJVFwNTG/mEpv2O7We1iCB+9WoEKqdHyGwjjBcVgkUlU5huJIXv9xj53RGNvmHkDFTqgrlHpKkb0E/Ot0Zg==",
            "exp_date" => $exp_date, //"ctA4Zj1CP0WCiMefPYsyewVbIHNilfwA09X9NSCyWxft4WGwFZmZkhsBJh51QL751/iFkUHbd09ZpDYjS86PqyNPZ5LkBueGHDIghLwWyzH1l99RiIs8urOW9c4g3L1USD+kzzRAqG1DBkW47FAX6AhPSi3YgQd94ery1H+asaqDrP79ayzoJ+nRXeEqe83FIgNUk/J5+EcAz3JYnoBmp1sfz7a4zHkvk0eKCxQWLETdqvONyCZyXdC/4CkaCxJ/87VsN3i4+ToULtSluRv8xr1NpRhzipKiEKTYW1nvNDAaJQezTVP/+GxmTmQfnfpVNDpJbXjNrOTej1HgMFpg4w==",
            "document_id" => $user['documents'][0]['id'],//"0370cc4d22b64c6df3904d1030f7a2c8a65f6a5818495765118a2d3ab1fdd677",
            // "network" => "Visa",
            // "type" => "PERSONAL",
            // "is_international" => "false"

        ];
       // print_r($info);die;
        $body = (object) [
           "logins" => array(
                array(
                    "email" => "phpTest@synapsepay.com",
                    "password" => "test1234",
                    "read_only" => false
                )
            ),
           "phone_numbers" => array(
                "901.111.1114"
            ),
           "legal_names" => array(
                'Demo Credit Card'
            ),
           'type' => 'INTERCHANGE-US',
           'info' => $info
        ];
        $refresh_payload = array('refresh_token' => $user['refresh_token']);
        $refresh_response = $client->user->refresh($refresh_payload);
        //print_r($refresh_response); die;
        $options['oauth_key']= $refresh_response['oauth_key'];
        $na = $client->node->add($body);
        // echo "<pre>";
        // print_r($na); die;
        if(!empty($na['success']))
        {
            $response['code'] = 1;
            $response['status'] = "success";
            $response['message'] = 'Your card has been linked';   
        }
        else
        {
            $response['code'] = 0;
            $response['status'] = "Error";
            $response['err'] = $na['error']['en'];
            $response['message'] = 'Error while linking your card';   
        }
        echo json_encode($response);
    }

    public function check_user_synapsefy()
    {
        $this->form_validation->set_rules('id', 'Id', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
        } 
        else 
        {
            $id = $this->input->post('id');
            $where['id']= $id;
            $result = $this->User_model->getAnyData($where);
            if(!empty($result))
            {
                if(!empty($result[0]->kyc==0))
                {
                    $response['status'] = 0;
                    $response['synapsefy_user_id'] = $result[0]->synapsefy_user_id;
                    $response['message'] = 'Successfully Fetched Data';
                }
                else
                {
                    $response['status'] = 1;
                    $response['message'] = 'No Synapsefy user exists';   
                }
            }
            else
            {
                $response['status'] = 2;
                $response['message'] = 'Invalid user id passed';
            }
        }
        echo json_encode($response);
    }
    public function delete_node()
    {
        $this->form_validation->set_rules('user_id', 'Id', 'trim|required');
        $this->form_validation->set_rules('node_id', 'Id', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
        } 
        else 
        {
            $id = $this->input->post('user_id');
            $where['id']= $id;
            $result = $this->User_model->getAnyData($where);
            if(!empty($result))
            {
                if(!empty($result[0]->synapsefy_user_id))
                {
                    //$user_id = "5c7e4e427d093e03a21acd44";
                    $user_id = $result[0]->synapsefy_user_id;
                    $node_id = $this->input->post('node_id');
                    $options = synapsefyClientDetails();
                    $client = new Client($options);

                    $from_user_id = $user_id;
                    $user = $client->user->get($from_user_id);
                    //setting userid to get value of particular account of particular node
                    $client->client->user_id= $user_id;
                    //adding refresh token
                    $refresh_payload = array('refresh_token' => $user['refresh_token']);
                    $refresh_response = $client->user->refresh($refresh_payload);

                    $node_delete_response = $client->node->delete($node_id);
                    $response['status'] = 1;
                    $response['message'] = 'Successfully Unlinked Your Bank';
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = 'No Synapsefy user exists';   
                }
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'Invalid user id passed';
            }
        }
        echo json_encode($response);
    }
    public function create_node()
    {
        $this->form_validation->set_rules('u_id', 'Id', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
        } 
        else 
        {
            $u_id = $this->input->post('u_id');
            $nickname = $this->input->post('nickname');
            $name_on_account = $this->input->post('name_on_account');
            $account_num = $this->input->post('account_num');
            $routing_num = $this->input->post('routing_num');
            $type = $this->input->post('type');
            $class = $this->input->post('class');
            $where['u_id'] = $u_id;
            $users = $this->PaymentData_model->getAnyData($where);
            //print_r($users); die;
            if(!empty($users))
            {
                $options = synapsefyClientDetails();
                //print_r($options); die;
                //$user_id = USER_ID
                $client = new Client($options);
                 
                $user_id = "5c063b993c4e2800b091bbd0";    
                $user = $client->user->get($user_id);
                //echo '<pre>'; print_r($user['refresh_token']); die;
                $refresh_payload = array('refresh_token' => $user['refresh_token']);

                $refresh_response = $client->user->refresh($refresh_payload);
                $options['oauth_key']= $user['refresh_token'];
                //echo '<pre>';  print_r($options); die;
                $ac_node_payload = array(
                "type" => "ACH-US",
                "info" => array(
                    "nickname" => $nickname,//"PHP Library Savings Account",
                    "name_on_account" => $name_on_account, //PHP Library
                    "account_num" => $account_num,//"72347235423",
                    "routing_num" => $routing_num, //"051000017",
                    "type" => $type, //"PERSONAL",
                    "class" => $class //"CHECKING"
                    ),
                );

                $na = $client->node->add($ac_node_payload);
                print_r($na);
                die;
                $na['nodes'][0]['_id'] = "5c10d5286a294e00641c8966";
                $ac_verify_payload = array(
                    "micro" => array(0.1,0.1)
                );

                $nav = $client->node->verify($na['nodes'][0]['_id'],$ac_verify_payload);
                print_r($nav); die;
            }
            else
            {
                $response['code'] = 0;
                $response['status'] = "error";
                $response['message'] = 'No such user exists';
            }
        }
        echo json_encode($response);
    }

    public function create_custody_node()
    {
        $options = synapsefyClientDetails();
        //print_r($options); die;
        //$user_id = USER_ID
        $client = new Client($options);
         
        $user_id = "5c7df3ed7d093e00671e228b";   //Tim Campbell GiftCast Account which we supposed him as client
        $user = $client->user->get($user_id);
        //echo '<pre>'; print_r($user['refresh_token']); die;
        $refresh_payload = array('refresh_token' => $user['refresh_token']);

        $refresh_response = $client->user->refresh($refresh_payload);
        $options['oauth_key']= $user['refresh_token'];

        $synapse_node_payload = array(
            "type" => "CUSTODY-US",
            "info" => array(
                "nickname" => "Custody Account"
            ),
            "extra" => array(
                "supp_id" => "123sa"
            )
        );

        $ns = $client->node->add($synapse_node_payload);
        print_r($ns);
        die;
        $na['nodes'][0]['_id'] = "5c10d5286a294e00641c8966";
        $ac_verify_payload = array(
            "micro" => array(0.1,0.1)
        );

        $nav = $client->node->verify($na['nodes'][0]['_id'],$ac_verify_payload);
        print_r($nav); die;
    }
    public function fetch_node()
    {
        $id = $this->input->post('id');
        $where['email_id']= $id;
        $user_result = $this->User_model->getAnyData($where);
        if(!empty($user_result))
        {
            $user_id = $user_result[0]->synapsefy_user_id;

            $options = synapsefyClientDetails();

            $client = new Client($options);

            //echo '<preprint_r($client);
            //$node_id = "5c10d5286a294e00641c8966"; //Node id of account Jaymin Sejpal
            //$user_id = "5c063b993c4e2800b091bbd0"; //unique if of account Jaymin Sejpal
            $user = $client->user->get($user_id);
            //echo '<pre>';print_r($user); die;
            //setting userid to get value of particular account of particular node
            $client->client->user_id= $user_id;
            //adding refresh token
            $refresh_payload = array('refresh_token' => $user['refresh_token']);
            $refresh_response = $client->user->refresh($refresh_payload);
            //$node = $client->node->get($node_id);
            $nodes = $client->node->get();
            //echo '<pre>';print_r($nodes); die;
            $nodes_count = count($nodes['nodes']);
            $user_nodes = array();
            if($nodes_count > 0)
            {
                for($i =0 ; $i <= $nodes_count; $i++)
                {
                    if(!empty($nodes['nodes'][$i]['info']))
                    {
                        $nodes['nodes'][$i]['info']['node_id'] = $nodes['nodes'][$i]['_id'];
                        $bankdata = json_encode($nodes['nodes'][$i]['info']);
                        $bankdata = json_decode($bankdata);
                        array_push($user_nodes, $bankdata);
                    }    
                }
                $response['code'] = 1;
                $response['totalnodes'] = $nodes_count;
                $response['status'] = "success";
                $response['data'] = $user_nodes;
                $response['message'] = 'Succcessfully fetched data';
            }
            else
            {
                $response['code'] = 0;
                $response['totalnodes'] = $nodes_count;
                $response['status'] = "error";
                $response['message'] = 'No data found';   
            }
        } 
        else 
        {
            $response['code'] = 0;
            $response['totalnodes'] = 0;
            $response['status'] = "error";
            $response['message'] = 'User not found';   
        }
        echo json_encode($response);
    }

    public function transfer_payment()
    {
        $id = $this->input->post('user_id');
        $gift_id = $this->input->post('gift_id');
        $from_node_id= $this->input->post('node_id');
        $amount= $this->input->post('amount');
        $type= $this->input->post('type'); //1- Bank Account 2 - Credit Card 3 - Debit Card
        if($type == 1)
        {
            $fee = 1.50;
        }
        elseif ($type == 2) 
        {
           $fee = ($amount * 0.03)+1.50;
        }
        elseif($type == 3) 
        {
            $fee = ($amount * 0.02)+1.50;
        }
        elseif($type == 4) 
        {
            $fee = ($amount * 0.02)+1.50;
        }
        $where['id']= $id;
        $result = $this->User_model->getAnyData($where);
        //print_r($result); die;
        if(!empty($result))
        {
            $options = synapsefyClientDetails();
            //print_r($options);
            //$user_id = USER_ID
            $client = new Client($options);

            /*Fetch Clients Custody Account ID*/
            $paymentresult = $this->PaymentData_model->getAnyData();
            $client_custody_account = $paymentresult[0]->client_custody_account;
            //$from_node_id = "5c89013cd23e5f45c2c33ef8"; //Node id of account from whom transfer will be initiated
            //$from_user_id = "5c8900e55ac648006672551c"; //unique if of account from whom transfer will be initiated
            $from_user_id = $result[0]->synapsefy_user_id;
            $user = $client->user->get($from_user_id);
            //setting userid to get value of particular account of particular node
            $client->client->user_id= $from_user_id;
            //adding refresh token
            $refresh_payload = array('refresh_token' => $user['refresh_token']);
            $refresh_response = $client->user->refresh($refresh_payload);

            //
            $nodes = $client->node->get($from_node_id);
            //Now check if node has valid permissions to check such as DEbit and Credit-debit if yes then only allow them to push
            //
            if($nodes['allowed'] == 'CREDIT-AND-DEBIT' || $nodes['allowed'] == 'DEBIT')
            {
                //echo '<pre>';print_r($client); die;
                //$node = $client->node->get($node_id);
                // Verify ACH-US via Micro-Deposits

                //$to_node_id = "5c42d28c7b08ab0067c48d7f";
                $final_amount = $fee+ $amount;
                $trans_payload = array(
                    "to" => array(
                        "type" => "CUSTODY-US",
                        "id" => $client_custody_account
                    ),
                    "amount" => array(
                        "amount" => $final_amount,
                        "currency" => "USD"
                    ),
                    "extra" => array(
                        "supp_id" => "1283764wqwsdd34wd13212",
                        "note" => "Deposit to bank account",
                        // "webhook" => "http => //requestb.in/q94kxtq9    ",
                        "process_on" => 1,
                        "ip" => $_SERVER['HTTP_HOST']
                    ),
                    /*"fees" => array(
                        array(
                            "fee" => $fee,
                            "note" => "Facilitator Fee",
                            "to" => array(
                                "id" => $client_custody_account
                            )
                        )
                    )*/
                );
                $create_response = $client->trans->create($from_node_id,$trans_payload);
                //print_r($create_response); die;
                if(!empty($create_response['timeline']))
                {
                    $transaction_id = $create_response['_id'];
                    $transaction_data['gift_id'] = $gift_id;
                    $transaction_data['payment_type'] = $type;
                    $transaction_data['transaction_id'] = $transaction_id;
                    $transaction_data['transaction_details'] = serialize($create_response);
                    $transaction_data['payment_status'] = $create_response['recent_status']['status'];
                    $transaction_data['created_at'] = date("Y-m-d H:i:s");
                    $transaction_insert = $this->Transactions_model->Insert_Data($transaction_data);
                    if($transaction_insert)
                    {
                        $response['status'] = 1;
                        $response['message'] = 'Transfer initiated  Successfully';
                    }
                    else
                    {
                        $response['status'] = 0;
                        $response['message'] = 'Issue while saving to local database.';   
                    }
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = 'No Synapsefy user exists';
                }
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'Your Card/Bank dont have permissions to transfer the amount';
            }
        }
        else
        {
            $response['status'] = 0;
            $response['message'] = 'Invalid user id passed';
        }
        echo json_encode($response);
    }
    
    public function withdrawal_payment()
    {
        //Check if gift is withdrawn or not
        $gift_id = $this->input->post('gift_id');
        $where['id'] = $gift_id;
        $withdrawcheck = $this->Gift_model->getAnyData($where);
        if($withdrawcheck[0]->withdraw_status == 0)
        {
            //Check if Sent transaction is settled or not
            $amount = $withdrawcheck[0]->amount; 
            $recipient_id = $withdrawcheck[0]->recipient_id; 
            $from_where['id']= $withdrawcheck[0]->from_id;
            $from_data = $this->User_model->getAnyData($from_where);
            $from_synapsefy_user_id = $from_data[0]->synapsefy_user_id;
            $from_node_id = $withdrawcheck[0]->from_node_id;

            //$from_synapsefy_user_id = "5c8900e55ac648006672551c";
            //$from_node_id = "5c89013cd23e5f45c2c33ef8";

            /*Fetch transaction_id*/
            $where_trans['gift_id'] = $gift_id;
            $transaction_data = $this->Transactions_model->getAnyData($where_trans);
            $transaction_id = $transaction_data[0]->transaction_id;

            //$transaction_id = "5c8a34dfaf7f75259f9298ef";

            $options = synapsefyClientDetails();
            //print_r($options);
            //$user_id = USER_ID
            $client = new Client($options); 
            $client->client->user_id = $from_synapsefy_user_id;
            $user = $client->user->get($from_synapsefy_user_id);
            $refresh_payload = array('refresh_token' => $user['refresh_token']);

            $refresh_response = $client->user->refresh($refresh_payload);
            //print_r($client); die;
            $tg = $client->trans->get($from_node_id, $transaction_id);
            $count_transaction = count($tg['trans']);
            $transaction_status = "";
            for($i=0;$i<$count_transaction; $i++)
            {
                if($tg['trans'][$i]['_id'] == $transaction_id)
                {
                    $transaction_status = $tg['trans'][$i]['recent_status']['status'];
                }
            }
            if($transaction_status == "SETTLED")
            {
                //Withdraw Funds from Custody account to B's account
                $this->withdraw_fund($recipient_id,$gift_id,$amount);
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'Your gift funds are on the way. It may take 3-5 business days for funds to appear in your bank account.'; 
                echo json_encode($response);
            }
        }
        else
        {
            $response['status'] = 0;
            $response['message'] = 'Gift already withdrawn'; 
            echo json_encode($response);  
        }
        
    }
    
    public function fetch_bank_synapsefy()
    {
       $this->form_validation->set_rules('type', 'Type', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
        } 
        else 
        {
            $type = $this->input->post('type');
            $options = synapsefyClientDetails();
            //print_r($options); die;
            //$user_id = USER_ID
            $client = new Client($options);

            $pk = $client->publickey->get();
            //print_r($pk['public_key_obj']); die;
            if(!empty($pk['public_key_obj']))
            {
                $pk = $pk['public_key_obj']['public_key'];
            }
            else
            {
                $pk = $pk['public_key'];   
            }
            $user_id = "5c063b993c4e2800b091bbd0"; //unique if of account Jaymin Sejpal
            $user = $client->user->get($user_id, null, null, null, $full_dehydrate='yes');
            $refresh_payload = array('refresh_token' => $user['refresh_token']);

            $refresh_response = $client->user->refresh($refresh_payload);
            //print_r($refresh_response); die;
            if(!empty($refresh_response['oauth_key']))
            {
                $auth_key = $refresh_response['oauth_key'];
                if($type == 1)
                {
                    //Bank Account
                    $url = "https://uat-uiaas.synapsefi.com/link?oauth_key=".$auth_key."&public_key=".$pk;
                }
                elseif($type == 2)
                {
                    //Credit Card
                    $url = "https://uat-uiaas.synapsefi.com/interchange?oauth_key=".$auth_key."&public_key=".$pk;
                }
                elseif($type == 3)
                {
                    //Debit Card
                    $url = "https://uat-uiaas.synapsefi.com/interchange?oauth_key=".$auth_key."&public_key=".$pk;
                }
                $response['status'] = 1;
                $response['message'] = "URL Fetched Successfully";
                $response['url'] = $url;
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = "Issue while fetching URL";
            }
            echo json_encode($response);
        }
    }

    public function withdraw_fund($recipient_id,$gift_id,$amount)
    {
        //Fetch recipient's banks
        $recipient_where['id']= $recipient_id;
        $recipient_data = $this->User_model->getAnyData($recipient_where);
        $recipient_synapsefy_user_id = $recipient_data[0]->synapsefy_user_id;
        $options = synapsefyClientDetails();

        $client = new Client($options);

        $user = $client->user->get($recipient_synapsefy_user_id);
        $client->client->user_id= $recipient_synapsefy_user_id;
        //adding refresh token
        $refresh_payload = array('refresh_token' => $user['refresh_token']);
        $refresh_response = $client->user->refresh($refresh_payload);
        //$node = $client->node->get($node_id);
        $nodes = $client->node->get();
        /*Fetch Nodes(Banks) of recipients*/

        $nodes_count = count($nodes['nodes']);
        if($nodes_count > 0)
        {
            $recipient_node_id = $nodes['nodes'][0]['_id'];
            $node_type = $nodes['nodes'][0]['type'];
            $set['recipient_node_id'] = $recipient_node_id;
            $set['updated_at']  = date("Y-m-d H:i:s");
            $where_gift_status['id'] = $gift_id;
            $update = $this->Gift_model->update($set, $where_gift_status);

            //Transfer from Custody US to recipient

            /*Fetch Clients Custody Account ID*/
            $paymentresult = $this->PaymentData_model->getAnyData();
            $client_synapsefy_userid = $paymentresult[0]->client_synapsefy_userid; //
            $from_node_id = $paymentresult[0]->client_custody_account; //

            $from_user_id = $client_synapsefy_userid;
            $user = $client->user->get($from_user_id);
            $client->client->user_id= $from_user_id;
            //adding refresh token
            $refresh_payload = array('refresh_token' => $user['refresh_token']);
            $refresh_response = $client->user->refresh($refresh_payload);

            $trans_payload = array(
                "to" => array(
                    "type" => $node_type,
                    "id" => $recipient_node_id
                ),
                "amount" => array(
                    "amount" => $amount,
                    "currency" => "USD"
                ),
                "extra" => array(
                    "supp_id" => "1283764wqwsdd34wd13212",
                    "note" => "Deposit to bank account",
                    "webhook" => "http => //requestb.in/q94kxtq9    ",
                    "process_on" => 1,
                    "ip" => "202.131.115.106"
                ),
                /*"fees" => array(
                    array(
                        "fee" => $fee,
                        "note" => "Facilitator Fee",
                        "to" => array(
                            "id" => $client_custody_account
                        )
                    )
                )*/
            );
            $create_response = $client->trans->create($from_node_id,$trans_payload);
            if(!empty($create_response['timeline']))
            {
                if($node_type == 'ACH-US')
                {
                    $type=1;
                }
                else
                {
                    $type=2;   
                }
                $transaction_id = $create_response['_id'];
                $transaction_data['gift_id'] = $gift_id;
                $transaction_data['payment_type'] = $type;
                $transaction_data['transaction_id'] = $transaction_id;
                $transaction_data['gift_sent_type'] = 2;
                $transaction_data['transaction_details'] = serialize($create_response);
                $transaction_data['payment_status'] = $create_response['recent_status']['status'];
                $transaction_data['created_at'] = date("Y-m-d H:i:s");
                $transaction_insert = $this->Transactions_model->Insert_Data($transaction_data);
                if($transaction_insert)
                {
                    //Now update gift withdrawal status
                    $set['withdraw_status']  = 1;
                    $set['updated_at']  = date("Y-m-d H:i:s");
                    $where_gift_status['id'] = $gift_id;
                    $update = $this->Gift_model->update($set, $where_gift_status);
                    $response['status'] = 1;
                    $response['message'] = 'Transfer initiated  Successfully';
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = 'Issue while saving to local database.';   
                }
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'Issue while processing credit';
            }
        }
        else
        {
            $response['status'] = 0;
            $response['message'] = 'You must link your banks first.';
        }        
        //echo '<pre>';print_r($nodes);
        //echo '<pre>'; print_r($create_response);
        echo json_encode($response);
    }

    public function user_statement()
    {
        $options = synapsefyClientDetails();

        $client = new Client($options);
        $user = $client->user->get($user_id);
        $client->client->user_id= $user_id;
        //adding refresh token
        $refresh_payload = array('refresh_token' => $user['refresh_token']);
        $refresh_response = $client->user->refresh($refresh_payload);
        
        $tg = $client->trans->get($ns['nodes'][0]['_id'], $create_response['_id']);
    }

    /**
 * @api {post} api/ws_cron_check_transaction Transaction Update
 * @apiVersion 1.0.0
 * @apiName Transaction Update
 * @apiGroup Payment
 *
 * @apiDescription It will run as cron to check if transaction is changed or not, if yes will update it
 *
 */

    public function check_transaction_status()
    {
        $options = synapsefyClientDetails();

        $client = new Client($options);
        //Fetch all users without having SETTLED and try to update the table
        $where = "payment_status != 'SETTLED'";
        $transaction_check = $this->Transactions_model->getAnyData($where);
        //print_r($transaction_check);
        if(!empty($transaction_check))
        {
            foreach ($transaction_check as $key => $value) 
            {
                $transaction_id = $value->transaction_id;
                $gift_id = $value->gift_id;
                $select = "gifts.from_id,gifts.from_node_id,users.synapsefy_user_id";
                $join_arr[0] = array( 
                                "table_name" => "users", 
                                "cond" => "gifts.from_id=users.id", 
                                "type" => "inner" 
                                );
                $where= array('gifts.id' => $gift_id);
                $usersresult = $this->Gift_model->getAnyData($where,$select,"","",$join_arr);
                if(!empty($usersresult))
                {
                    foreach($usersresult as $value)
                    {
                        $user_id = $value->synapsefy_user_id;
                        $node_id = $value->from_node_id;
                        $user = $client->user->get($user_id);
                        $client->client->user_id= $user_id;
                        //adding refresh token
                        $refresh_payload = array('refresh_token' => $user['refresh_token']);
                        $refresh_response = $client->user->refresh($refresh_payload);
                        $tg = $client->trans->get($node_id, $transaction_id);
                        $total_transaction_page = $tg['page'];
                        $tg = $client->trans->get($node_id, $transaction_id);
                        foreach ($tg['trans'] as $key => $value) 
                        {
                            if($transaction_id == $value['_id'])
                            {
                                $latest_status = $value['recent_status']['status'];
                                $where_trans['gift_id'] = $gift_id;
                                $set['payment_status']= $latest_status;
                                $update = $this->Transactions_model->update($set, $where_trans);
                            }
                        }
                    }
                }
            }
        }
    }

    public function request_2fa()
    {
        $this->form_validation->set_rules('user_id', 'Request Code', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $check_signup['id'] = $this->input->post('user_id');
            $check_signup_result = $this->User_model->getAnyData($check_signup);
            if(!empty($check_signup_result))
            {
                $type = $this->input->post('type');
                $options = synapsefyClientDetails();
                $user_id = $check_signup_result[0]->synapsefy_user_id;
                $phone_number = '+1'.$check_signup_result[0]->phone_number;
                //$email_id = $check_signup_result[0]->email_id;
                $email_id = 't.prakash@zaptechsolutions.com';
                $client = new Client($options);
                $client->client->user_id= $user_id;
                $user = $client->user->get($user_id);
                $social_docs_id = $user['documents'][0]['id'];
                $refresh_payload = array('refresh_token' => $user['refresh_token']);
                $refresh_response = $client->user->refresh($refresh_payload);
                if($type == 1)
                {
                    //Phone 2fa
                    $update_existing_docs_payload = array(
                        'documents' => array(
                            array(
                                'id' => $social_docs_id,
                                'social_docs' => array(
                                    array(
                                        'document_value' => $phone_number,
                                        'document_type' => 'PHONE_NUMBER_2FA'
                                    ),
                                )
                            )
                        )
                    );
                }
                else
                {
                    //Email 2fa
                    $update_existing_docs_payload = array(
                        'documents' => array(
                            array(
                                'id' => $social_docs_id,
                                'social_docs' => array(
                                    array(
                                        'document_value' => $email_id,
                                        'document_type' => 'EMAIL_2FA'
                                    )
                                )
                            )
                        )
                    );
                }
                $update_existing_docs_response = $client->user->update($update_existing_docs_payload);
                if(!empty($update_existing_docs_response))
                {
                    $response['code'] = 1;
                    $response['status'] = "success";
                    $response['message'] = 'PIN has been sent Successfully';
                }
                else
                {
                    $response['code'] = 0;
                    $response['status'] = "error";
                    $response['message'] = 'Issue while sending PIN.';
                }
            }
            else
            {
                $response['code'] = 0;
                $response['status'] = "error";
                $response['message'] = 'Invalid User id';
            }
            echo json_encode($response);
        }
    }

    public function verify_2fa()
    {
        $this->form_validation->set_rules('user_id', 'Request Code', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('pin', 'PIN', 'trim|required');
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $check_signup['id'] = $this->input->post('user_id');
            $pin = $this->input->post('pin');
            $check_signup_result = $this->User_model->getAnyData($check_signup);
            if(!empty($check_signup_result))
            {
                $type = $this->input->post('type');
                $options = synapsefyClientDetails();
                $user_id = $check_signup_result[0]->synapsefy_user_id;
                $phone_number = '+1'.$check_signup_result[0]->phone_number;
                $email_id = $check_signup_result[0]->email_id;
                $client = new Client($options);
                $client->client->user_id= $user_id;
                $user = $client->user->get($user_id);
                $social_docs_id = $user['documents'][0]['id'];
                $refresh_payload = array('refresh_token' => $user['refresh_token']);
                $refresh_response = $client->user->refresh($refresh_payload);
                if($type == 1)
                {
                    //Phone 2fa
                    $update_existing_docs_payload = array(
                        'documents' => array(
                            array(
                                'id' => $social_docs_id,
                                'social_docs' => array(
                                    array(
                                        'document_value' => $phone_number,
                                        'document_type' => 'PHONE_NUMBER_2FA',
                                        'mfa_answer' => $pin
                                    ),
                                )
                            )
                        )
                    );
                }
                else
                {
                    $update_existing_docs_payload = array(
                        'documents' => array(
                            array(
                                'id' => $social_docs_id,
                                'social_docs' => array(
                                    array(
                                        'document_value' => $email_id,
                                        'document_type' => 'EMAIL_2FA',
                                        'mfa_answer' => $pin
                                    )
                                )
                            )
                        )
                    );
                }
                $update_existing_docs_response = $client->user->update($update_existing_docs_payload);
                if(!empty($update_existing_docs_response))
                {
                    sleep(10);
                    $client->client->user_id= $user_id;
                    $user = $client->user->get($user_id);
                    if($user['documents'][0]['social_docs'][3]['status'] == 'SUBMITTED|VALID')
                    {
                        $response['code'] = 1;
                        $response['status'] = "success";
                        $response['email_2fa'] = 0;
                        $response['message'] = 'User verified Successfully';
                    }
                    else
                    {
                        $response['code'] = 0;
                        $response['status'] = "error";
                        $response['email_2fa'] = 1;
                        $response['message'] = 'Email 2FA needed';
                    }
                }
                else
                {
                    $response['code'] = 0;
                    $response['status'] = "error";
                    $response['message'] = 'Error while submitting the PIN, Please try again.';   
                }
                echo json_encode($response);
            }
        }
    }

    public function check_user_status()
    {
        $this->form_validation->set_rules('user_id', 'Request Code', 'trim|required');
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $check_signup['id'] = $this->input->post('user_id');
            $check_signup_result = $this->User_model->getAnyData($check_signup);
            if(!empty($check_signup_result))
            {
                $options = synapsefyClientDetails();
                $user_id = $check_signup_result[0]->synapsefy_user_id;
                $client = new Client($options);
                $client->client->user_id= $user_id;
                $user = $client->user->get($user_id);
                print_r($user); die;
            }
        }
    }

    public function update_kyc()
    {
        $this->form_validation->set_rules('id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('address_street', 'Street', 'trim|required');
        $this->form_validation->set_rules('address_city', 'City', 'trim|required');
        $this->form_validation->set_rules('address_subdivision', 'Subdivision', 'trim|required');
        $this->form_validation->set_rules('address_postal_code', 'Postal Code', 'trim|required');
        $this->form_validation->set_rules('address_country_code', 'Country Code', 'trim|required');
        $this->form_validation->set_rules('ip_address', 'Country Code', 'trim|required');
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $options = synapsefyClientDetails();
            $check_signup['id'] = $this->input->post('id');
            $check_signup_result = $this->User_model->getAnyData($check_signup);
            if(!empty($check_signup_result) && !empty($check_signup_result[0]->synapsefy_user_id))
            {
                $ip_address = $this->input->post('ip_address');
                $client = new Client($options);
                $email_id = $check_signup_result[0]->email_id;
                $phone_number = $check_signup_result[0]->phone_number;
                $fullname = $check_signup_result[0]->fname.' '.$check_signup_result[0]->lname;
                $synapsefy_user_id = $check_signup_result[0]->synapsefy_user_id;

                /*$email_id = 'hiten@zaptechsolutions.com';
                $phone_number = '2018628841';
                $fullname = 'Hiten Shah';
                $synapsefy_user_id = '5cf0cdc0ec747f009d14f032';*/

                $client->client->user_id= $synapsefy_user_id;
                $user = $client->user->get($synapsefy_user_id);
                //print_r($user); die;
                $social_docs_id = $user['documents'][0]['id'];
                $address_street = $this->input->post('address_street');
                $address_city = $this->input->post('address_city');
                $address_subdivision = $this->input->post('address_subdivision');
                $address_postal_code = $this->input->post('address_postal_code');
                $address_country_code = $this->input->post('address_country_code');
                $update_existing_docs_payload = array(
                    "documents" => array(
                            array(
                                'id' => $social_docs_id,
                                "email" => $email_id,
                                "phone_number" => $phone_number,
                                "ip" => $ip_address,
                                "name" => $fullname,
                                "entity_type" => "M",
                                "entity_scope" => "Arts & Entertainment",
                                "day" => 2,
                                "month" => 5,
                                "year" => 1996,
                                "address_street" => $address_street,
                                "address_city" => $address_city,
                                "address_subdivision" => $address_subdivision,
                                "address_postal_code" => $address_postal_code,
                                "address_country_code" => $address_country_code,
                            )
                    )
                );
                $client = new Client($options);
                $client->client->user_id= $synapsefy_user_id;
                $user = $client->user->get($synapsefy_user_id);
                $social_docs_id = $user['documents'][0]['id'];
                $refresh_payload = array('refresh_token' => $user['refresh_token']);
                $refresh_response = $client->user->refresh($refresh_payload);
                $update_existing_docs_response = $client->user->update($update_existing_docs_payload);
                if(!empty($update_existing_docs_response))
                {
                    $response['code'] = 1;
                    $response['status'] = "success";
                    $response['ipaddress'] = $ip_address;
                    $response['message'] = 'User KYC Information updated';
                }
                else
                {
                    $response['code'] = 0;
                    $response['status'] = "error";
                    $response['message'] = 'Issue while updating user information. Please try again later.';
                }
            }
            else
            {
                $response['code'] = 0;
                $response['status'] = "error";
                $response['message'] = 'Invalid User id';
            }
            echo json_encode($response);
        }
    }

    public function update_user_permission()
    {
        $options = synapsefyClientDetails();
        $update_existing_docs_payload = array(
            "permission" => "CLOSED"
        );
        $client = new Client($options);
        $synapsefy_user_id = "5cdd8dcc82c85e00652a77a2";
        $client->client->user_id= $synapsefy_user_id;
        $user = $client->user->get($synapsefy_user_id);
        $social_docs_id = $user['documents'][0]['id'];
        $refresh_payload = array('refresh_token' => $user['refresh_token']);
        $refresh_response = $client->user->refresh($refresh_payload);
        $update_existing_docs_response = $client->user->update($update_existing_docs_payload);
        print_r($update_existing_docs_response);die;
        if(!empty($update_existing_docs_response))
        {
            echo "updated";
        }
        else
        {
            echo "issue while updating";
        }
    }
}

?>