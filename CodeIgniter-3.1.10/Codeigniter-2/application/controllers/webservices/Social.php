<?php

class Social extends CI_Controller 
{

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Logs_model');
        $this->load->library('email');
    }

    public function socialLogin() 
    {

        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('social_id', 'Social_id', 'trim|required');
        $this->form_validation->set_rules('device_id', 'Device_id', 'trim');
        $this->form_validation->set_rules('device_type', 'Device_type', 'trim');
        $this->form_validation->set_rules('fname', 'Device_type', 'trim|required');

        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        } 
        else 
        {
            $type = $this->input->post("type");
            $unq_id = "";
            $uniqu_field = "";
            $device_id = $this->input->post('device_id');
            $device_type = $this->input->post('device_type');
            $fname = $this->input->post('fname');
            if ($type == "facebook") 
            {

                $unq_id = $this->input->post('social_id');
                $uniqu_field = "facebook_id";
            }
            if ($type == "twiter") 
            {
                $unq_id = $this->input->post('social_id');
                $uniqu_field = "twitter_id";
            }
            if ($type == "google") 
            {
                $unq_id = $this->input->post('social_id');
                $uniqu_field = "google_id";
            }

            if ($unq_id != '' && $uniqu_field != '') 
            {

                $soc_arr = array(
                    $uniqu_field => $unq_id
                );

                $is_user = $this->User_model->getAnyData($soc_arr, "id,fname,lname,email_id,phoneno,facebook_id,twitter_id,google_id,status,is_deleted");

                if (!empty($is_user)) 
                {
                    
                    if ($is_user[0]->status == 1 && $is_user[0]->is_deleted == 0) 
                    {
                        $ipaddress = $_SERVER['REMOTE_ADDR'];
                        
                        $data=array(
                                "user_id" => $is_user[0]->id,
                                "loggedin_time" => date("Y-m-d H:i:s"),
                                "ipaddress" => $ipaddress,
                                "device_id" => $device_id,
                                "device_type" => $device_type
                                );
                        $this->Logs_model->Insert_Data($data);
                        $email = $this->input->post('email_id');
                        $emaildata['to'] = $email;
                        $emaildata['subject'] = 'Giftcast - Welcome';
                        $emaildata['message'] = "Hello Welcome to Giftcast
                        ,<br/><br/> <br/><br/> Thanks,<br/>Giftcast";

                        sendmail($emaildata);
                        $response['code'] = 1;
                        $response['status'] = "success";
                        $response['data'] = $is_user;
                    } 
                    else 
                    {
                        $response['code'] = 0;
                        $response['status'] = "error";
                        $response['message'] = "Your account is under verification process";
                    }
                }
                else 
                {

                    $email = $this->input->post('email_id');
                    if (!empty($email) && $email != '') 
                    {

                        $soc_email = array(
                            'email_id' => $email,
                        );
                        
                        $is_email = $this->User_model->getAnyData($soc_email);

                        if (!empty($is_email)) 
                        {
                            
                            $update = $this->User_model->update($soc_arr, $soc_email);
                            if (!empty($update) && $update != '') 
                            {
                                
                                $get_data = $this->User_model->getAnyData($soc_email, "id,fname,lname,email_id,phoneno,facebook_id,twitter_id,google_id,status,is_deleted");
                                
                                if ($get_data[0]->status == 1 && $get_data[0]->is_deleted == 0) 
                                {
                                    $response['code'] = 1;
                                    $response['status'] = "success";
                                    $response['data'] = $get_data;
                                } 
                                else 
                                {
                                    $response['code'] = 0;
                                    $response['status'] = "error";
                                    $response['message'] = "Your account is under verification process";
                                }
                            }
                        } 
                        else 
                        {
                            $data = array(
                                $uniqu_field => $unq_id,
                                "fname" => $this->input->post('fname'),
                                "email_id" => $this->input->post('email_id'),
                                "device_type" => $this->input->post('device_type'),
                                "device_id " => $this->input->post('device_id'),
                                "status" => "1",
                                "created_at" => date("Y-m-d H:i:s"),
                                "is_deleted"=> "0",
                                "role"=> "2",
                            );

                            $insert = $this->User_model->Insert_Data($data);
                            if (!empty($insert) && $insert > 0) 
                            {
                                $last_id = array(
                                    "id" => $insert
                                );
                                $get_data = $this->User_model->getAnyData($last_id, "id,fname,lname,email_id,phoneno,facebook_id,twitter_id,google_id,status,is_deleted");
                                if ($get_data[0]->status == 1 && $get_data[0]->is_deleted == 0) 
                                {
                                    $emaildata['to'] = $email;
                                    $emaildata['subject'] = 'Giftcast - Welcome';
                                    $emaildata['message'] = "Hello Welcome to Giftcast
                                    ,<br/><br/> <br/><br/> Thanks,<br/>Giftcast";

                                    sendmail($emaildata);
                                    $response['code'] = 1;
                                    $response['status'] = "success";
                                    $response['data'] = $get_data;
                                } 
                                else 
                                {
                                    $response['code'] = 0;
                                    $response['status'] = "error";
                                    $response['message'] = "Your account is under verification process";
                                }
                            }
                        }
                    }
                    else 
                    {
                        $response['code'] = 2;
                        $response['status'] = "error";
                        $response['message'] = "Email id field can't be empty.";
                    }
                }
            }
            echo json_encode($response);
        }
    }

}
?>