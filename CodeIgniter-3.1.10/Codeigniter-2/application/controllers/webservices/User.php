<?php
require('./nexmo/vendor/autoload.php');
require('./synapsefi-php/init.php');
use SynapsePayRest\Client;
class User extends CI_Controller {

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

     /**
 * @api {post} api/ws_signup User Signup
 * @apiVersion 1.0.0
 * @apiName UserSignup
 * @apiGroup Users
 *
 * @apiDescription Signup
 *
 * @apiParam {Number} email_id Email id.
 * @apiParam {Character} fname First Name.
 * @apiParam {Character} [lname] Last Name.
 * @apiParam {Character} password Password.
 * @apiParam {Number} phone_number Phone Number.
 * @apiParam {Number} device_token Device token
 * @apiParam {Number} device_type Device Type E.g Android, Ios.
 */
    /*
    @Author: Jaymin Sejpal
    @description: Signup Webservice for User
    */

    public function signup() 
    {
        $baseurl = $this->config->base_url();
        $this->form_validation->set_rules('email_id', 'Email', 'trim|required');
        $this->form_validation->set_rules('fname', 'First_name', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Phone_number', 'trim|required');
        $this->form_validation->set_rules('device_token', 'device_token', 'trim|required');
        $this->form_validation->set_rules('device_type', 'device_type', 'trim|required');
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
            $Insert = "";
            $base_url = $this->config->base_url();
            $signup_data['email_id'] = $this->input->post('email_id');
            $signup_data['fname'] = ucwords($this->input->post('fname'));
            $signup_data['lname'] = ucwords($this->input->post('lname'));
            $signup_data['password'] = md5($this->input->post('password'));
            $phone_number = $this->input->post('phone_number');
            $signup_data['phone_number'] = $phone_number; //adding 1 because it is for us numbers only
            $signup_data['profile_picture'] = "";
            $signup_data['device_token'] = $this->input->post('device_token');
            $signup_data['device_type'] = $this->input->post('device_type');
            $signup_data['status'] = "1";
            $signup_data['synapsefy_user_id'] = "";
            $signup_data['created_at'] = date("Y-m-d H:i:s");
            $signup_data['kyc'] = 0;
            $ip_address = $this->input->post('ip_address');
            $signup_data['ip_address'] = $ip_address;
            //as per updated scenario check phone number exists
            $check_signup['phone_number']= $this->input->post('phone_number');
            $check_signup_result = $this->User_model->getAnyData($check_signup);
            if(!empty($check_signup_result))
            {
                //Phone no exists
                $is_deleted = $check_signup_result[0]->status;
                if($is_deleted != 1)
                {
                    //deleted entry then update the field with latest record
                    $signup_data['updated_at'] = date("Y-m-d H:i:s");
                    $where['id'] = $check_signup_result[0]->id;
                    $update = $this->User_model->update($signup_data, $where);
                    if (!empty($update)) 
                    {
                        $notification_data['user_id'] = $check_signup_result[0]->id;
                        $notification_data['recieve_gift'] = 1; //by default 1
                        $notification_data['send_gift'] = 1; //by default 1
                        $notification_data['contacts_joined'] = 1; //by default 1
                        $notification_data['admin_giftcast_settings'] = 1; //by default 1
                        $notification_data['gift_opened'] = 1; //by default 1
                        $notification_data['created_at'] = date("Y-m-d H:i:s");
                        $where_notification['user_id'] = $check_signup_result[0]->id;
                        $notification_insert = $this->Notification_model->update($notification_data,$where_notification);
                        if(!empty($notification_insert))
                        {
                            $signup_data['id'] = $check_signup_result[0]->id;
                            $signup_data['user_id'] = $check_signup_result[0]->id;
                            $signup_data['fullname'] = ucwords($this->input->post('fname')).' '.ucwords($this->input->post('lname'));
                            $success_message = 'Registration Successful.';
                            $response['code'] = 1;
                            $response['status'] = "success";
                            unset($signup_data['password']);
                            $signup_data['is_active'] = $signup_data['status'];
                            //print_r($signup_data); die;
                            $response['data'] = $signup_data;
                            $response['message'] = $success_message;
                            //Sending Email
                            $baseurl = $this->config->base_url();
                            $fname = $this->input->post('fname');
                            $lname = $this->input->post('lname');
                            $email = $this->input->post('email_id');
                            /*$emaildata['to'] = $email;
                            $emaildata['subject'] = 'Giftcast - Welcome';
                            $emaildata['message'] = "Hello".' '.$fname.''.$lname.",<br><br>Welcome to Giftcast
                            .<br/><br/> Thanks,<br/><b>Giftcast Team</b>.";*/
                            $fullname = $fname.' '.$lname;
                            $emaildata['to'] = $email;
                            $emaildata['subject'] = 'Giftcast - Welcome';            
                            $emaildata['message'] = file_get_contents(base_url().'email_templates/signup.php');
                            $emaildata['message'] = str_replace('FULLNAME', $fullname, $emaildata['message']);
                            sendmail($emaildata);
                            $synapsefy_create_user = $this->create_user($check_signup_result[0]->id,$email,$phone_number,$fullname,$ip_address);
                            if($synapsefy_create_user == 1)
                            {
                                echo json_encode($response);
                            }
                            else
                            {
                                $response['code'] = 0;
                                $response['status'] = "error";
                                $response['message'] = $synapsefy_create_user;
                                echo json_encode($response);
                            }
                        }
                    }
                }
                else if(empty($is_email))
                {
                    //deleted entry then update the field with latest record
                    $signup_data['updated_at'] = date("Y-m-d H:i:s");
                    $where['id'] = $check_signup_result[0]->id;
                    $update = $this->User_model->update($signup_data, $where);
                    if (!empty($update)) 
                    {
                        $notification_data['user_id'] = $check_signup_result[0]->id;
                        $notification_data['recieve_gift'] = 1; //by default 1
                        $notification_data['send_gift'] = 1; //by default 1
                        $notification_data['contacts_joined'] = 1; //by default 1
                        $notification_data['admin_giftcast_settings'] = 1; //by default 1
                        $notification_data['gift_opened'] = 1; //by default 1
                        $notification_data['created_at'] = date("Y-m-d H:i:s");
                        $where_notification['user_id'] = $check_signup_result[0]->id;
                        $notification_insert = $this->Notification_model->update($notification_data,$where_notification);
                        if(!empty($notification_insert))
                        {
                            $signup_data['id'] = $check_signup_result[0]->id;
                            $signup_data['user_id'] = $check_signup_result[0]->id;
                            $signup_data['fullname'] = ucwords($this->input->post('fname')).' '.ucwords($this->input->post('lname'));
                            $success_message = 'Registration Successful.';
                            $response['code'] = 1;
                            $response['status'] = "success";
                            unset($signup_data['password']);
                            $signup_data['is_active'] = $signup_data['status'];
                            //print_r($signup_data); die;
                            $response['data'] = $signup_data;
                            $response['message'] = $success_message;
                            //Sending Email
                            $baseurl = $this->config->base_url();
                            $fname = $this->input->post('fname');
                            $lname = $this->input->post('lname');
                            $email = $this->input->post('email_id');
                            /*$emaildata['to'] = $email;
                            $emaildata['subject'] = 'Giftcast - Welcome';
                            $emaildata['message'] = "Hello".' '.$fname.''.$lname.",<br><br>Welcome to Giftcast
                            .<br/><br/> Thanks,<br/><b>Giftcast Team</b>.";*/
                            $fullname = $fname.' '.$lname;
                            $emaildata['to'] = $email;
                            $emaildata['subject'] = 'Giftcast - Welcome';            
                            $emaildata['message'] = file_get_contents(base_url().'email_templates/signup.php');
                            $emaildata['message'] = str_replace('FULLNAME', $fullname, $emaildata['message']);
                            sendmail($emaildata);
                            $synapsefy_create_user = $this->create_user($check_signup_result[0]->id,$email,$phone_number,$fullname,$ip_address);        
                            if($synapsefy_create_user == 1)
                            {
                                echo json_encode($response);
                            }
                            else
                            {
                                $response['code'] = 0;
                                $response['status'] = "error";
                                $response['message'] = $synapsefy_create_user;
                                echo json_encode($response);
                            }
                        }
                    }
                }
                else
                {
                    $response['code'] = 0;
                    $response['status'] = "error";
                    $response['message'] = 'This Phone number and/or Email address already exists.';
                    echo json_encode($response);
                }
                //print_r($check_signup_result);
            }
            else
            {
                //insert
                //$Insert = $this->User_model->Insert_Data($signup_data);
                $Insert = $this->User_model->insert($signup_data);
                if (!empty($Insert)) 
                {
                    $notification_data['user_id'] = $Insert;
                    $notification_data['recieve_gift'] = 1; //by default 1
                    $notification_data['send_gift'] = 1; //by default 1
                    $notification_data['contacts_joined'] = 1; //by default 1
                    $notification_data['admin_giftcast_settings'] = 1; //by default 1
                    $notification_data['gift_opened'] = 1; //by default 1
                    $notification_data['created_at'] = date("Y-m-d H:i:s");
                    $notification_insert = $this->Notification_model->Insert_Data($notification_data);
                    if(!empty($notification_insert))
                    {
                        $signup_data['id'] = $Insert;
                        $signup_data['user_id'] = $Insert;
                        $signup_data['fullname'] = ucwords($this->input->post('fname')).' '.ucwords($this->input->post('lname'));
                        $success_message = 'Registration Successful.';
                        $response['code'] = 1;
                        $response['status'] = "success";
                        unset($signup_data['password']);
                        $signup_data['is_active'] = $signup_data['status'];
                        //print_r($signup_data); die;
                        $response['data'] = $signup_data;
                        $response['message'] = $success_message;
                        //Sending Email
                        $baseurl = $this->config->base_url();
                        $fname = ucwords($this->input->post('fname'));
                        $lname = ucwords($this->input->post('lname'));
                        $email = $this->input->post('email_id');
                        /*$emaildata['to'] = $email;
                        $emaildata['subject'] = 'Giftcast - Welcome';
                        $emaildata['message'] = "Hello".' '.$fname.''.$lname.",<br><br>Welcome to Giftcast
                        .<br/><br/> Thanks,<br/><b>Giftcast Team</b>.";*/
                        $fullname = $fname.' '.$lname;
                        $emaildata['to'] = $email;
                        $emaildata['subject'] = 'Giftcast - Welcome';            
                        $emaildata['message'] = file_get_contents(base_url().'email_templates/signup.php');
                        $emaildata['message'] = str_replace('FULLNAME', $fullname, $emaildata['message']);
                        sendmail($emaildata);
                        $synapsefy_create_user = $this->create_user($Insert,$email,$phone_number,$fullname,$ip_address);
                        if($synapsefy_create_user == 1)
                        {
                            echo json_encode($response);
                        }
                        else
                        {
                            $response['code'] = 0;
                            $response['status'] = "error";
                            $response['message'] = $synapsefy_create_user;
                            echo json_encode($response);
                        } 
                    }
                }
                else 
                {
                    $response['code'] = 0;
                    $response['status'] = "error";
                    $response['message'] = 'Email Or Mobile Number already exist';
                    echo json_encode($response);
                }
            }
        }
    }

    /**
 * @api {post} api/ws_signin User Login
 * @apiVersion 1.0.0
 * @apiName UserLogin
 * @apiGroup Users
 *
 * @apiDescription Login
 *
 * @apiParam {Number} email_id Email id.
 * @apiParam {Character} password Password.
 * @apiParam {Number} device_token Device token
 * @apiParam {Number} device_type Device Type E.g Android, Ios.
 */
    /*
    @Author: Jaymin Sejpal
    @description: Login WS for a user.*/

    public function login() 
    {

        /* Field required Validation */
        $this->form_validation->set_rules('email_id', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('device_token', 'device_token', 'trim|required');
        $this->form_validation->set_rules('device_type', 'device_type', 'trim|required');
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        } 
        else 
        {
            $data_log['email_id'] = $this->input->post('email_id');
            $data_log['password'] = md5($this->input->post('password'));
            
            $ipaddress = $_SERVER['REMOTE_ADDR'];
            $device_token = $this->input->post('device_token');
            $device_type = $this->input->post('device_type');
            $result = $this->User_model->getAnyData($data_log, '', '', '', '');
            
            if (!empty($result)) 
            {
                if ($result[0]->status == 1) 
                {
                    $set['device_token'] = $device_token;
                    $set['device_type'] = $device_type;
                    $set['updated_at'] = date("Y-m-d H:i:s");
                    $where['id'] = $result[0]->id;
                    $update = $this->User_model->update($set, $where);
                    
                    $data=array(
                            "user_id" => $result[0]->id,
                            "loggedin_time" => date("Y-m-d H:i:s"),
                            "ipaddress" => $ipaddress,
                            "device_token" => $device_token,
                            "device_type" => $device_type
                        );
                    $this->Logs_model->Insert_Data($data);
                    $email = $this->input->post('email');

                    $where = array('user_id' => $result[0]->id);
                    $notification_data = $this->Notification_model->getAnyData($where);

                    $data['id'] = $result[0]->id;
                    $data['email_id'] = $result[0]->email_id;
                    $data['phone_number'] = $result[0]->phone_number;
                    
                    $data['fname'] = $result[0]->fname;
                    $data['lname'] = $result[0]->lname;
                    $data['fullname'] = $result[0]->fname.' '.$result[0]->lname;
                    $data['profile_picture'] = "";
                    if(!empty($result[0]->profile_picture) && $result[0]->profile_picture!="")
                    {
                        $data['profile_picture'] = ASSETS_URL.'profile_pictures/'.$result[0]->profile_picture;
                    }
                    
                    $data['is_active'] = $result[0]->status;

                    $response['code'] = 1;
                    $response['status'] = "success";
                    $response['data'] = $data;
                    $response['notification_data'] = $notification_data;
                    $response['tax'] = 0.00;
                    $response['message'] = 'Login successfully';
                    echo json_encode($response);
                } 
                else 
                {
                    $response['code'] = 0;
                    $response['status'] = "error";
                    $response['message'] = 'Your account is under verification process';
                    echo json_encode($response);
                }
            } 
            else 
            {
                $response['code'] = 0;
                $response['status'] = "Error";
                $response['message'] = 'Invalid Email or Password';
                echo json_encode($response);
            }
        }
    }

     /**
 * @api {post} api/ws_forget_password Forget Password
 * @apiVersion 1.0.0
 * @apiName ForgetPassword
 * @apiGroup Users
 *
 * @apiDescription Forgte Password
 *
 * @apiParam {Number} email_id Email id.
 */
    /*@Author: Jaymin Sejpal
      @description: Forget Password Webservice for User
     */
   
    public function forgot_password() 
    {
        $this->form_validation->set_rules('email_id', 'Email', 'trim|required');

        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter your email address.';
            echo json_encode($response);
        }
        else 
        {
            $email = $this->input->post('email_id');
            $data = array(
                'email_id' => $email
            );
            $check = $this->User_model->getAnyData($data);
            if (!empty($check)) 
            {
                $baseurl = $this->config->base_url();
                $emaildata['to'] = $email;
                $emaildata['subject'] = 'Forgot Your Password?';
                $lname = '';
                if ($check[0]->lname != '') 
                {
                    $lname = " " . $check[0]->lname;
                }
                $name = $check[0]->fname . $lname;


                $email_enc = base64_encode($check[0]->email_id);
                $id_enc = base64_encode($check[0]->id);
                $time = time();
                $url = $baseurl . "changepwd?unq=" . $id_enc . "&em=" . $email_enc . "&tm=" . $time;
                $emaildata['message'] = file_get_contents(base_url().'email_templates/forgot-pass.php');
                $emaildata['message'] = str_replace('FULLNAME', $name, $emaildata['message']);
                $emaildata['message'] = str_replace('RESETLINK', $url, $emaildata['message']);
                // $emaildata['message'] = "Hello $name,<br/><br/> Please <a href='$url'>Click</a> here to reset your password.<br/>";
                sendmail($emaildata);
                $response['code'] = 1;
                $response['status'] = "success";
                $response['message'] = 'Please check your email to change your password.';
                
                echo json_encode($response);
            } 
            else 
            {
                $response['code'] = 0;
                $response['status'] = "error";
                $response['message'] = 'User with this email address does not exist.';
                echo json_encode($response);
            }
        }
    }
    
    /*
    @Author: Jaymin Sejpal
    @description: Update User WS for a user.*/

    public function update_user_details()
    {
        $this->form_validation->set_rules('id', 'Id', 'trim|required');
        $this->form_validation->set_rules('fullname', 'Fname', 'trim');
        $this->form_validation->set_rules('email_id', 'Email', 'trim');
        $this->form_validation->set_rules('phone_number', 'Phoneno', 'trim');
        $this->form_validation->set_rules('current_password', 'Current', 'trim');
        $this->form_validation->set_rules('new_password', 'New', 'trim');
        //$this->form_validation->set_rules('flag', 'flag', 'trim|required');
        $baseurl = $this->config->base_url();
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $id = $this->input->post('id');
            $fullname = ucwords($this->input->post('fullname'));
            $email_id = $this->input->post('email_id');
            $phone_number = $this->input->post('phone_number');
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');
            $profile_picture = $this->input->post('profile_picture');
            $where = array('id' => $id);
            $result = $this->User_model->getAnyData($where);
            
            if(!empty($result))
            {
                /* Start: Image Uploading using base64 */
                if(isset($profile_picture))
                {
                    $upload_item_dir = './assets/profile_pictures/';
                    $data = base64_decode($profile_picture);
                    $imagename = time() . '.png';
                    $save_file = $upload_item_dir . $imagename;
                    $success = file_put_contents($save_file, $data);
                    $target_path = $baseurl."assets/profile_pictures_thumbnails/";
                    $source_path = $baseurl."assets/profile_pictures/".$imagename;
                    /*Image thumbnail code starts*/
                     /*$config_manip = array(
                    'image_library' => 'gd2',
                    'source_image' => $source_path,
                    'new_image' => $target_path,
                    'maintain_ratio' => TRUE,
                    'create_thumb' => TRUE,
                    'thumb_marker' => '_thumb',
                    'width' => 150,
                    'height' => 150
                    );
                    
                    $this->load->library('image_lib');
                    $this->image_lib->initialize($config_manip);
                    if (!$this->image_lib->resize()) 
                    {
                        echo $this->image_lib->display_errors();
                    }*/
                    /*Image thumbnail code ends*/
                    if($success)
                    {
                        if(!empty($result[0]->profile_picture) && $result[0]->profile_picture!="")
                        {
                            unlink('./assets/profile_pictures/'.$result[0]->profile_picture);
                        }
                        $set['profile_picture']=$imagename;
                        $set['updated_at'] = date("Y-m-d H:i:s");
                    }
                }
                /* End: Image Uploading using base64 */
                if(isset($fullname))
                {
                    $full_name_split = explode(" ", $fullname);
                    $fname = $full_name_split[0];
                    $set['fname']=$fname;
                    $set['updated_at'] = date("Y-m-d H:i:s");
                }
                if(isset($fullname))
                {
                    $full_name_split = explode(" ", $fullname);
                    if(!empty($full_name_split[1]) && isset($full_name_split[1]))
                    {
                        $lname = $full_name_split[1];
                        $set['lname']=$lname;
                        $set['updated_at'] = date("Y-m-d H:i:s");
                    }
                }
                if(isset($email_id))
                {
                    
                    $data = array(
                        'email_id' => $email_id,
                        'id!=' => $id
                    );
                    $check = $this->User_model->getAnyData($data);
                    if (empty($check)) 
                    {
                        $set['email_id']=$email_id;
                        $set['updated_at'] = date("Y-m-d H:i:s");
                    }
                    else
                    {
                        $response['code'] = 0;
                        $response['status'] = "error";
                        $response['message'] = 'Email Address has been already registered.';
                    }
                }
                if(isset($phone_number))
                {
                    
                    $data_phone_number = array(
                        'phone_number' => $phone_number
                    );
                    $check_phone_number = $this->User_model->getAnyData($data_phone_number);
                    //pr($check_phone_number);
                    if (empty($check_phone_number)) 
                    {
                        $set['phone_number']=$phone_number;
                        $set['updated_at'] = date("Y-m-d H:i:s");
                    }
                    else
                    {
                        $response['code'] = 0;
                        $response['status'] = "error";
                        $response['message'] = 'Entered Phone number already exists.';
                    }
                }
                if(isset($new_password) && isset($current_password))
                {
                    $data = array(
                        'id' => $id,
                        'password' => md5($current_password)
                    );
                    $check = $this->User_model->getAnyData($data);

                    if (!empty($check)) 
                    {
                        $set['password']=md5($new_password);
                        $set['updated_at'] = date("Y-m-d H:i:s");
                    }
                    else
                    {
                        $response['code'] = 0;
                        $response['status'] = "error";
                        $response['message'] = 'Current Password is not asssociated with this id.';
                    }
                }
                $set['updated_at'] = date("Y-m-d H:i:s");
                $update = $this->User_model->update($set, $where);
                if(empty($response))
                {
                    $result = $this->User_model->getAnyData($where);
                    $responsedata['id'] = $result[0]->id;
                    $responsedata['fullname'] = $result[0]->fname.' '.$result[0]->lname;
                    $responsedata['fname'] = $result[0]->fname;
                    $responsedata['lname'] = $result[0]->lname;
                    $responsedata['email_id'] = $result[0]->email_id;
                    $responsedata['profile_picture'] = "";
                    if(!empty($result[0]->profile_picture) && $result[0]->profile_picture!="")
                    {
                        $responsedata['profile_picture'] = ASSETS_URL.'profile_pictures/'.$result[0]->profile_picture;
                    }
                    
                    $responsedata['phone_number'] = $result[0]->phone_number;
                    $responsedata['is_active'] = $result[0]->status;
                    $response['code'] = 1;
                    $response['status'] = "Success";
                    $response['data'] = $responsedata;
                    $response['message'] = 'User Details has been changed successfully.';
                    $response['code'] = 1;
                    $response['status'] = "success";
                    // $response['message'] = 'Success';
                    echo json_encode($response);
                }
                else
                {
                    echo json_encode($response);
                }
            }
            else
            {
                $response['code'] = 0;
                $response['status'] = "error";
                $response['message'] = 'Id does not exists';
                echo json_encode($response);
            }
        }
    }

    /**
 * @api {post} api/ws_fetch_user_details User Details
 * @apiVersion 1.0.0
 * @apiName UserDetails
 * @apiGroup Users
 *
 * @apiDescription Login
 *
 * @apiParam {Number} id User id.
 */

    public function user_details()
    {
        $this->form_validation->set_rules('id', 'Id', 'trim|required');
        
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $id = $this->input->post('id');
            $where = array(
                'users.id' => $id
            );
            $baseurl = $this->config->base_url();
            $select = "users.id as user_id,users.fname,users.lname,users.phone_number,users.email_id,users.status,users.profile_picture,user_logs.ipaddress,user_logs.device_token,user_logs.device_type,user_logs.loggedin_time";
            $join_arr[0] = array( 
                                "table_name" => "user_logs", 
                                "cond" => "users.id=user_logs.id", 
                                "type" => "inner" 
                                );
            $result = $this->User_model->getAnyData($where,$select,"","",$join_arr,"");
            if (!empty($result)) 
            {
                $responsedata['user_id'] = $result[0]->user_id;
                $responsedata['fname'] = $result[0]->fname;
                $responsedata['lname'] = $result[0]->lname;
                $responsedata['phone_number'] = $result[0]->phone_number;
                $responsedata['email_id'] = $result[0]->email_id;
                if(!empty($result[0]->profile_picture))
                {
                    $responsedata['profilepicture'] =  $baseurl.'assets/profile_pictures/'. $result[0]->profile_picture;
                }
                else
                {
                    $responsedata['profilepicture'] = "";
                }
                $responsedata['ipaddress'] = $result[0]->ipaddress;
                $responsedata['device_token'] = $result[0]->device_token;
                $responsedata['device_type'] = $result[0]->device_type;
                $responsedata['is_active'] = $result[0]->status;
                $responsedata['loggedin_time'] = $result[0]->loggedin_time;

                $where = array('user_id' => $result[0]->user_id);
                $notification_data = $this->Notification_model->getAnyData($where);

                $tax_data = $this->Payment_model->getAnyData();
                $tax = $tax_data[0]->tax;

                $response['code'] = 1;
                $response['status'] = "success";
                $response['data'] = $responsedata;
                $response['notification_data'] = $notification_data;
                $response['tax'] = $tax;
                $response['message'] = 'User Details has been fetched successfully.'; 
            }
            else
            {
                $response['code'] = 0;
                $response['status'] = "error";
                $response['message'] = 'User Does not exists. Try new one';
            }
            echo json_encode($response);
        }
    }

    public function change_user_password() 
    {
        $this->form_validation->set_rules('id', 'Id', 'trim|required');
        $this->form_validation->set_rules('current_password', 'Current', 'trim|required');
        $this->form_validation->set_rules('new_password', 'New', 'trim|required');

        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $id = $this->input->post('id');
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');
            if($current_password != $new_password)
            {
                $data = array(
                'id' => $id,
                'password' => md5($current_password)
                );
                $check = $this->User_model->getAnyData($data);
                if(!empty($check))
                {
                    $new_password = $this->input->post('new_password');
                    $set = array(
                            'password' => md5($new_password),
                            'updated_at' => date("Y-m-d H:i:s")
                            );
                    $where = array(
                        'id' => $id
                    );

                    $update = $this->User_model->update($set, $where);
                    if($update)
                    {
                        /*@Date:26/3/18*/
                        $fullname = $check[0]->fname.' '.$check[0]->lname;
                        $email = $check[0]->email_id;
                        $message="You have successfully changed your account password <br> and it is ready to use immediately.";
                        $emaildata['to'] = $email;
                        $emaildata['subject'] = 'Your GiftCast password has been changed'; 
                        $emaildata['message'] = file_get_contents(base_url().'email_templates/change-pass.php');
                        $emaildata['message'] = str_replace('FULLNAME', $fullname, $emaildata['message']);
                        $emaildata['message'] = str_replace('MESSAGE', $message, $emaildata['message']);
                        sendmail($emaildata);
                        $response['code'] = 1;
                        $response['status'] = "Success";
                        $response['message'] = 'Your password has been changed.';
                        
                    }
                    else
                    {
                        $response['code'] = 0;
                        $response['status'] = "error";
                        $response['message'] = 'Error while updating the password.';
                    }
                    echo json_encode($response);
                }
                else
                {
                    $response['code'] = 0;
                    $response['status'] = "error";
                    $response['message'] = 'Current Password is not asssociated with this id.';
                    echo json_encode($response);
                }
            }
            else
            {
                $response['code'] = 0;
                $response['status'] = "error";
                $response['message'] = 'You cannot set old password as new password';
                echo json_encode($response);
            }
        }
    }
    
    /**
 * @api {post} api/ws_invite Invite Friend
 * @apiVersion 1.0.0
 * @apiName InviteAFriend
 * @apiGroup Users
 *
 * @apiDescription Invite a friend
 *
 * @apiParam {Number} id User id.
 * @apiParam {Number} recipient User id.
 * @apiParam {Character} title Title.
 * @apiParam {Character} message Message.
 */

    public function friend_invitaion()
    {
        $this->form_validation->set_rules('id', 'Id', 'trim|required');
        $this->form_validation->set_rules('recipient', 'Recipient', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');

        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $postVar = $this->input->post();
            $id = $postVar['id'];
            $data = array(
                'id' => $id
            );
            $result = $this->User_model->getAnyData($data);
            $fullname = $result[0]->fname. ' '.$result[0]->lname;
            $baseurl = $this->config->base_url();
            $emaildata['to'] = $postVar['recipient'];
            $emaildata['subject'] = 'Giftcast - Invitation Mail';
            $message = "Hello,<br/>";
            $message .= "Your friend ".$fullname." has invited you to join.<br/>";
            $emaildata['message'] = $message;
            sendmail($emaildata);
            $response['code'] = 1;
            $response['status'] = "success";
            $response['message'] = 'Invite Sent to your friend.';
            echo json_encode($response);
        }
    }

    /**
 * @api {post} api/ws_deleteuser Delete Account
 * @apiVersion 1.0.0
 * @apiName DeleteAccount
 * @apiGroup Users
 *
 * @apiDescription Delete Account of user
 *
 * @apiParam {Number} id User id.
 * @apiParam {Character} reason Reason for deleting.
 * @apiParam {Character} password Password.
 */

    ///Delete Account Webservice///
    public function delete_account()
    {
        $this->form_validation->set_rules('id', 'Id', 'trim|required');
        $this->form_validation->set_rules('reason', 'Reason', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $postVar = $this->input->post();
            $id = $postVar['id'];
            $password = $postVar['password'];
            $data = array(
                'id' => $id,
                'password' => md5($password)
            );
            $result = $this->User_model->getAnyData($data);
            $checkUser = count($result);
            if($checkUser > 0){
                $set = array('status' => 0);
                $where = array('id' => $id);
                $removeUser = $this->User_model->update($set, $where);
                if($removeUser)
                {
                    /*Date: 26/3/19, @Description: On Delete user all the nodes which are linked from synapsefy will be
                    removed. */
                    $synapsefy_user_id = $result[0]->synapsefy_user_id;

                    if(!empty($synapsefy_user_id))
                    {
                        //now fetch all nodes first
                        $options = array(
                            /*'oauth_key'=> USER_OAUTH_KEY,*/ # Optional,
                            'fingerprint'=> '',
                            'client_id'=> 'client_id_BgIcot9iFnbGydKer517NW3wpQzA8quRUkEDZ0J6',
                            'client_secret'=> 'client_secret_YgiN7loX8rIQTzkv2dHOy64AZ0xRwaBfUM0nuLh5',
                            'development_mode'=> true, # true will ping sandbox.synapsepay.com
                            'ip_address'=> '202.131.115.106',
                            'oauth_key' => ''
                        );

                        $client = new Client($options);

                        $user = $client->user->get($synapsefy_user_id);
                        //echo '<pre>';print_r($user); die;
                        //setting userid to get value of particular account of particular node
                        $client->client->user_id= $synapsefy_user_id;
                        //adding refresh token
                        $refresh_payload = array('refresh_token' => $user['refresh_token']);
                        $refresh_response = $client->user->refresh($refresh_payload);
                        //$node = $client->node->get($node_id);
                        $nodes = $client->node->get();
                        //echo '<pre>';print_r($nodes); die;
                        $nodes_count = count($nodes['nodes']);
                        $user_nodes = array();
                        if($nodes['http_code'] == 200) //ok
                        {
                            if($nodes_count > 0)
                            {
                                for($i =0 ; $i <= $nodes_count; $i++)
                                {
                                    if(!empty($nodes['nodes'][$i]['info']))
                                    {
                                        $node_id = $nodes['nodes'][$i]['_id'];
                                        $node_delete_response = $client->node->delete($node_id);
                                        //print_r($node_delete_response);
                                    }
                                }
                            }
                        }
                    }
                    $response['code'] = 1;
                    $response['status'] = "success";
                    $response['message'] = 'Account has been deleted.';
                    echo json_encode($response);
                }
                else{ 
                    $response['code'] = 0;
                    $response['status'] = "error";
                    $response['message'] = 'The User does not exist.';
                    echo json_encode($response);
                }
            }
            else{
                  $response['code'] = 0;
                  $response['status'] = "error";
                  $response['message'] = 'The User does not exist.';
                  echo json_encode($response);
            }
            
        }
    }

    /**
 * @api {post} api/ws_reportproblem Report Account
 * @apiVersion 1.0.0
 * @apiName ReportProblem
 * @apiGroup Report User
 *
 * @apiDescription Delete Account of user
 *
 * @apiParam {Number} id User id.
 * @apiParam {Character} topic Topic For Reporting.
 * @apiParam {Character} title Title For Reporting.
 * @apiParam {Character} complaint Complaint.
 */

    ///// Report a Problem Webservice
    public function report_problem()
    {
        $this->form_validation->set_rules('id', 'Id', 'trim|required');
        $this->form_validation->set_rules('topic', 'Topic', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('complaint', 'Complaint', 'trim|required');

        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $postVar = $this->input->post();
            $id = $postVar['id'];
            $data = array(
                'id' => $id
            );
            $result = $this->User_model->getAnyData($data);
            
            $checkUser = count($result);
            if($checkUser > 0){
                $data = array(
                    'user_id' => $id,
                    'topic' => $postVar['topic'],
                    'title' => $postVar['title'],
                    'complaint' => $postVar['complaint'],
                    'created_at' => date("Y-m-d H:i:s"),
                    );
                $insert = $this->Report_model->Insert_Data($data);
                if($insert)
                {
                    //send email
                    $emaildata['to'] =  "customercare@giftcast.me";//"customercare@giftcast.me";
                    $emaildata['subject'] = 'Giftcast - Report a Problem';
                    $emaildata['message'] = "Hello Admin,<br/><br/> You got a complaint report.<br/>Below are the details:<br>Name : ".$result[0]->fname.' '.$result[0]->lname."<br>Topic : ".$postVar['topic']."<br>Title : ".$postVar['title']."<br>Complaint : ".$postVar['complaint'];
                    sendmail($emaildata);
                    
                }
                 $response['code'] = 1;
                 $response['status'] = "success";
                 $response['message'] = 'Your problem is reported.';
                 echo json_encode($response); 
            }
            else{
                $response['code'] = 0;
                $response['status'] = "error";
                $response['message'] = 'The User does not exist.';
                echo json_encode($response);    
            }
        }
    }

    /**
 * @api {post} api/ws_fblogin Facebook Login
 * @apiVersion 1.0.0
 * @apiName FacebookLogin
 * @apiGroup Users
 *
 * @apiDescription Facebook Login for a user
 *
 * @apiParam {Number} facebook_id Facebook Unique id.
 * @apiParam {Character} email_id Email id.
 */


    public function facebook_login()
    {
        $this->form_validation->set_rules('email_id', 'Email', 'trim|required');
        $this->form_validation->set_rules('facebook_id', 'Facebook_id', 'trim|required');

        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {       
            $postVar = $this->input->post();
            $email_id = $postVar['email_id'];
            $facebook_id = $postVar['facebook_id'];
            $device_token = $postVar['device_token'];
            $device_type = $postVar['device_type'];

            $data['email_id'] = $email_id;
            $data['facebook_id'] = $facebook_id;
            $select = "email_id = '$email_id' OR facebook_id = '$facebook_id'";
            $result = $this->User_model->getAnyData($select);
            if(!empty($result))
            {
                $set['facebook_id'] = $facebook_id;
                $set['device_type'] = $device_type;
                $set['device_token'] = $device_token;
                $set['updated_at'] = date("Y-m-d H:i:s");

                $where['id'] = $result[0]->id;
                
                $updateUser = $this->User_model->update($set, $where);
                if($updateUser)
                {
                    $where = array('user_id' => $result[0]->id);
                    $notification_data = $this->Notification_model->getAnyData($where);

                    $fblogin_data['id'] = $result[0]->id;
                    $fblogin_data['fname'] = $result[0]->fname;
                    $fblogin_data['lname'] = $result[0]->lname;
                    $fblogin_data['fullname'] = $result[0]->fname.' '.$result[0]->lname;
                    $fblogin_data['email_id'] = $result[0]->email_id;
                    $fblogin_data['facebook_id'] = $result[0]->facebook_id;
                    $fblogin_data['phone_number'] = $result[0]->phone_number;
                    $fblogin_data['status'] = $result[0]->status;
                    $fblogin_data['profile_picture'] = $result[0]->profile_picture;
                    $fblogin_data['device_token'] = $device_token;
                    $fblogin_data['device_type'] = $device_type;
                    $fblogin_data['ip_address'] = $result[0]->ip_address;
                    $fblogin_data['created_at'] = $result[0]->created_at;

                    $response['code'] = 1;
                    $response['status'] = "success";
                    $response['data'] = $fblogin_data;
                    $response['notification_data'] = $notification_data;
                    $response['message'] = 'Login successfully';
                }
            }
            else
            {
                $response['code'] = 0;
                $response['status'] = "error";
                $response['message'] = "Not Registered!";

                // $fname=$lname=$profile_picture="";

                // if(isset($postVar['fname']) && $postVar['fname'] != '')
                // {
                //     $fname = ucwords($postVar['fname']);
                //     $signup_data['fname'] = $fname;
                // }
                // if(isset($postVar['lname']) && $postVar['lname'] != '')
                // {
                //     $lname = ucwords($postVar['lname']);
                //     $signup_data['lname'] = $lname;
                // }
                // if(isset($postVar['profile_picture']) && $postVar['profile_picture'] != '')
                // {
                //     $profile_picture = $postVar['profile_picture'];
                //     $signup_data['profile_picture'] = $profile_picture;
                // }

                // $signup_data['email_id'] = $email_id;
                // $signup_data['facebook_id'] = $facebook_id;
                // $signup_data['password'] = "";
                // $signup_data['phone_number'] = "";
                // $signup_data['status'] = 1;
                // $signup_data['device_token'] = $device_token;
                // $signup_data['device_type'] = $device_type;
                // $signup_data['ip_address'] = '';
                // $signup_data['created_at'] = date("Y-m-d H:i:s");
                // //echo '<pre>';print_r($signup_data); die;
                // $insert = $this->User_model->insertFbData($signup_data);
                // //print_r($insert); die;
                // if(!empty($insert))
                // {
                //     $notification_data['user_id'] = $insert;
                //     $notification_data['recieve_gift'] = 1; //by default 1
                //     $notification_data['send_gift'] = 1; //by default 1
                //     $notification_data['contacts_joined'] = 1; //by default 1
                //     $notification_data['admin_giftcast_settings'] = 1; //by default 1
                //     $notification_data['gift_opened'] = 1; //by default 1
                //     $notification_data['created_at'] = date("Y-m-d H:i:s");
                //     $where_notification['user_id'] = $insert;
                //     $signup_data['fullname'] = $fname.' '.$lname;
                //     $notification_insert = $this->Notification_model->update($notification_data,$where_notification);
                //     if(!empty($notification_insert))
                //     {
                //         $signup_data['id'] = $insert;
                //         $fullname = $fname.' '.$lname;
                //         $emaildata['to'] = $email_id;
                //         $emaildata['subject'] = 'Giftcast - Welcome';            
                //         $emaildata['message'] = file_get_contents(base_url().'email_templates/signup.php');
                //         $emaildata['message'] = str_replace('FULLNAME', $fullname, $emaildata['message']);
                //         sendmail($emaildata);

                //         unset($signup_data['password']);

                //         $response['code'] = 1;
                //         $response['status'] = "success";
                //         $response['data'] = $signup_data;
                //         $response['message'] = "Registration successfully done";
                //     }
                //     else
                //     {
                //         $signup_data['id'] = $insert;
                //         $fullname = $fname.' '.$lname;
                //         $emaildata['to'] = $email_id;
                //         $emaildata['subject'] = 'Giftcast - Welcome';            
                //         $emaildata['message'] = file_get_contents(base_url().'email_templates/signup.php');
                //         $emaildata['message'] = str_replace('FULLNAME', $fullname, $emaildata['message']);
                //         sendmail($emaildata);

                //         unset($signup_data['password']);

                //         $response['code'] = 1;
                //         $response['status'] = "success";
                //         $response['data'] = $signup_data;
                //         $response['message'] = "Registration successfully done";
                //     }
                // }
            }
            echo json_encode($response);
        }
    }

    public function request_otp()
    {
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
        $this->form_validation->set_rules('email_id', 'Email Id', 'trim|required');

        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $phone_number = '1'.$this->input->post('phone_number');
            $email_id = $this->input->post('email_id');
            $original_phone_no = $this->input->post('phone_number');

            //as per updated scenario check phone number exists
            // $check_signup['phone_number']= $this->input->post('phone_number');
            // $check_signup['email_id']= $email_id;
            $check_signup = 'phone_number = '.$original_phone_no.' OR email_id = "'.$email_id.'"';
            $check_signup_result = $this->User_model->getAnyData($check_signup);
            //print_r($check_signup_result); die;
            if(!empty($check_signup_result))
            {
                //Phone no exists
                $is_deleted = $check_signup_result[0]->status;
                $is_email = $check_signup_result[0]->email_id;
                if($is_deleted != 1)
                {
                    //Try request OTP Code
                    $client = new Nexmo\Client(new Nexmo\Client\Credentials\Basic(NEXMO_API_KEY, NEXMO_API_SECRET));     
                    //echo '<pre>'; print_r($client); die;
                    $TO_NUMBER = 12063979956; //just for checking value
                    //$phone_number = 919898891097;
                    try
                    {
                        $verification = new \Nexmo\Verify\Verification($phone_number, 'Your GiftCast code is');
                        //$verification = new \Nexmo\Verify\Verification($phone_number, 'GiftCast Verify');
                        $client->verify()->start($verification);
                        if(!empty($verification->getRequestId()))
                        {
                            $response['status'] = 1;
                            $response['request_id'] = $verification->getRequestId();
                            $response['message'] = 'OTP sent Successfully';
                        }
                    }
                    catch(Exception $ex)
                    {
                        $response['status'] = 0;
                        $response['message'] = 'Issue while sending OTP. Please try again later';
                        $response['error_message'] = $ex->getMessage();
                    }
                }
                else if(empty($is_email))
                {
                    //Try request OTP Code
                    $client = new Nexmo\Client(new Nexmo\Client\Credentials\Basic(NEXMO_API_KEY, NEXMO_API_SECRET));     
                    //echo '<pre>'; print_r($client); die;
                    $TO_NUMBER = 12063979956; //just for checking value
                    //$phone_number = 919898891097;
                    try
                    {
                        $verification = new \Nexmo\Verify\Verification($phone_number, 'GiftCast Verify');
                        //$verification = new \Nexmo\Verify\Verification($phone_number, 'GiftCast Verify');
                        $client->verify()->start($verification);
                        if(!empty($verification->getRequestId()))
                        {
                            $response['status'] = 1;
                            $response['request_id'] = $verification->getRequestId();
                            $response['message'] = 'OTP sent Successfully';
                        }
                    }
                    catch(Exception $ex)
                    {
                        $response['status'] = 0;
                        $response['message'] = 'Issue while sending OTP. Please try again later';
                        $response['error_message'] = $ex->getMessage();
                    }
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = 'This Phone number and/or Email address already exists.';
                }
            }
            else
            {
                //Try request OTP Code
                    $client = new Nexmo\Client(new Nexmo\Client\Credentials\Basic(NEXMO_API_KEY, NEXMO_API_SECRET));     
                    //echo '<pre>'; print_r($client); die;
                    $TO_NUMBER = 12063979956; //just for checking value
                    //$phone_number = 919898891097;
                    try
                    {
                        $verification = new \Nexmo\Verify\Verification($phone_number, 'GiftCast Verify');
                        $client->verify()->start($verification);
                        if(!empty($verification->getRequestId()))
                        {
                            $response['status'] = 1;
                            $response['request_id'] = $verification->getRequestId();
                            $response['message'] = 'OTP sent Successfully';
                        }
                    }
                    catch(Exception $ex)
                    {
                        $response['status'] = 0;
                        $response['message'] = $ex->getMessage();
                    }
            }
            echo json_encode($response);
            /*$check_signup_data['email_id'] = $email_id;
            $check_email = $this->User_model->getAnyData($check_signup_data);
            if(empty($check_email))
            {
                //Check Phone Number
                $check_signup_data_1['phone_number'] = $original_phone_no;
                $check_phone_number = $this->User_model->getAnyData($check_signup_data_1);
                if(empty($check_phone_number))
                {
                    //Try request OTP Code
                    $client = new Nexmo\Client(new Nexmo\Client\Credentials\Basic(NEXMO_API_KEY, NEXMO_API_SECRET));     
                    //echo '<pre>'; print_r($client); die;
                    $TO_NUMBER = 12063979956; //just for checking value
                    $phone_number = 919898891097;
                    try
                    {
                        $verification = new \Nexmo\Verify\Verification($phone_number, 'GiftCast Verify');
                        $client->verify()->start($verification);
                        if(!empty($verification->getRequestId()))
                        {
                            $response['status'] = 1;
                            $response['request_id'] = $verification->getRequestId();
                            $response['message'] = 'OTP sent Successfully';
                        }
                    }
                    catch(Exception $ex)
                    {
                        $response['status'] = 0;
                        $response['message'] = $ex->getMessage();
                    }
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = 'This Phone number already exists with other email address';       
                }
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'This Email address already exists with other phone number';
            }
            echo json_encode($response);*/
        }
    }

    public function verify_otp()
    {
        $this->form_validation->set_rules('request_code', 'Request Code', 'trim|required');
        $this->form_validation->set_rules('otp', 'OTP', 'trim|required');
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $request_code = $this->input->post('request_code');
            $otp = $this->input->post('otp');
            $client = new Nexmo\Client(new Nexmo\Client\Credentials\Basic(NEXMO_API_KEY, NEXMO_API_SECRET));
            try
            {
                $verification = new \Nexmo\Verify\Verification($request_code);
                $result = $client->verify()->check($verification, $otp);
                //var_dump($result->getResponseData());
                $response['status'] = 1;
                $response['message'] = 'Verified';
            }
            catch (Exception $ex)
            {
                //print_r($ex->getMessage());
                $response['status'] = 0;
                $response['message'] = $ex->getMessage();
            }
        }
        echo json_encode($response);
    }

    public function resend_otp()
    {
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
        $this->form_validation->set_rules('request_code', 'Request Code', 'trim|required');

        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $phone_number = '1'.$this->input->post('phone_number');
            $request_code = $this->input->post('request_code');
            //echo $request_code; die;
            $client = new Nexmo\Client(new Nexmo\Client\Credentials\Basic(NEXMO_API_KEY, NEXMO_API_SECRET));
            try 
            {
                $result = $client->verify()->cancel($request_code);
                if(!empty($result->getResponseData()))
                {
                    //Cancelled last OTP Request ID
                    //Now resend OTP
                    //Try request OTP Code

                    //echo '<pre>'; print_r($client); die;
                    $TO_NUMBER = 12063979956; //just for checking value
                    $phone_number = 919898891097;
                    try
                    {
                        $verification = new \Nexmo\Verify\Verification($phone_number, 'GiftCast Verify');
                        $client->verify()->start($verification);
                        if(!empty($verification->getRequestId()))
                        {
                            $response['status'] = 1;
                            $response['request_id'] = $verification->getRequestId();
                            $response['message'] = 'OTP sent Successfully';
                        }
                    }
                    catch(Exception $ex)
                    {
                        $response['status'] = 0;
                        $response['message'] = $ex->getMessage();
                    }
                }
                else
                {
                    $response['status'] = 1;
                    $response['message'] = 'Issue while Resending OTP'; 
                }
            }
            catch(Exception $e) 
            {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }
            echo json_encode($response);
        }
    }

    public function logout()
    {
        $this->form_validation->set_rules('id', 'id', 'trim|required');
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        }
        else 
        {
            $id = $this->input->post('id');
            $set['device_type'] = '';
            $set['device_token'] = '';
            $set['updated_at'] = date("Y-m-d H:i:s");

            $where['id'] = $id;
            
            $updateUser = $this->User_model->update($set, $where);
            if($updateUser)
            {
                $response['status'] = 1;
                $response['message'] = 'Successfully Logged out';
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'Issue while logging out';
            }
        }
        echo json_encode($response);
    }

    function create_user($u_id,$email_id,$phone_number,$fullname,$ip_address)
    {
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
                        "ip" => $ip_address,
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
            if(!empty($create_response['_id']) && isset($create_response['_id']))
            {
                $synapse_user_id = $create_response['_id'];
                $set['synapsefy_user_id'] = $synapse_user_id;
                $where_user['id'] = $u_id; 
                $update = $this->User_model->update($set, $where_user);
                if(!empty($update))
                {
                    return 1;                 
                }
            }
            else
            {
                return $create_response['error']['en'];
            }
        }
        else
        {
            return 0;
        }
    }
}
?>

