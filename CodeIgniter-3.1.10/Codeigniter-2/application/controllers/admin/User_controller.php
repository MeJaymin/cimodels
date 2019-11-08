<?php

class User_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Notification_model');
        $this->load->library('email');
        if (!isAdmin()) {
            redirect('admin');
        }
    }

    /*
    @description
        Loads the page with all entries 
    */
    public function index() {
        $data['users'] = $this->User_model->getAnyData();
        $data['body'] = "admin/user.php";
        $this->load->view('admin/template.php', $data);
    }

    /* Add User
    @descrption
        Adds new entry for User table
    @var:
        $postVar: Contains all the post variables
    @update (add) :
        name: garde name (varchar)
        is_active: Status is active(1) or inactive(0) (boolean)
        created: date time of created day
    @message
        Success: redirect to grade page with success msg
        Error: redirect to grade page with error msg
    */

    public function AddNewUser() 
    {
        if (!$this->session->userdata('user_id')) 
        {
            redirect('admin');
        }
        if ($this->input->post('submit')) 
        {

            $baseurl = $this->config->base_url();
            
            $token=RandomPassword();
            /*$data = array(
                'fname' => $this->input->post('first_name'),
                'lname' => $this->input->post('last_name'),
                'email_id' => $this->input->post('email_id'),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'password' => md5($token),
                'status' => $this->input->post('status'),
                'phone_number' => $this->input->post('phone_number'),
                'created_at' => date("Y-m-d H:i:s")
            );*/
            $data['fname'] = $this->input->post('first_name');
            $data['lname'] = $this->input->post('last_name');
            $data['email_id'] = $this->input->post('email_id');
            $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $data['password'] = md5($token);
            $data['status'] = $this->input->post('status');
            $data['phone_number'] = $this->input->post('phone_number');
            $data['created_at'] = date("Y-m-d H:i:s");

            /* Start: Image Uploading */
            $profile_photo = "";
            $profile_path = '';
            $config['upload_path'] = './assets/profile_pictures';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '0';
            $config['max_width'] = '0';
            $config['max_height'] = '0';

            
            if (!empty($_FILES['image']['name'])) 
            {
                $this->file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $this->file_name = time() . "." . $this->file_ext;
                $config['file_name'] = $this->file_name;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('image', FALSE)) 
                {
                    echo $this->upload->display_errors();
                    $this->form_validation->set_message('checkdoc', $data['error'] = $this->upload->display_errors());
                    if ($_FILES['image']['error'] != 4) 
                    {
                        return false;
                    }
                } 
                else 
                {
                    $profile_photo = $this->file_name;
                    $profile_path = $baseurl . "assets/profile_pictures";
                }

                $data['profile_picture'] = $profile_photo;
                /*$data = array(
                'fname' => $this->input->post('first_name'),
                'lname' => $this->input->post('last_name'),
                'email_id' => $this->input->post('email_id'),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'password' => md5($token),
                'status' => $this->input->post('status'),
                'phone_number' => $this->input->post('phone_number'),
                'profile_picture' => $profile_photo,
                'created_at' => date("Y-m-d H:i:s")
                );*/
            }

            /* End: Image Uploading */
            
            //check if email exists
            /*$where['email_id'] = $data['email_id'];
            $where['phone_number'] = $data['phone_number'];*/
            $where = "`email_id` = '".$data['email_id']."' OR `phone_number` = '".$data['phone_number']."'";
            $check_user = $this->User_model->getAnyData($where);
            if(empty($check_user))
            {
                $insert = $this->User_model->Insert_adminData($data);
                if (!empty($insert)) 
                {
                    $notification_data['user_id'] = $insert;
                    $notification_data['recieve_gift'] = 1; //by default 1
                    $notification_data['send_gift'] = 1; //by default 1
                    $notification_data['contacts_joined'] = 1; //by default 1
                    $notification_data['admin_giftcast_settings'] = 1; //by default 1
                    $notification_data['gift_opened'] = 1; //by default 1
                    $notification_data['created_at'] = date("Y-m-d H:i:s");
                    $notification_insert = $this->Notification_model->Insert_Data($notification_data);
                    if(!empty($notification_insert))
                    {
                        $baseurl = $this->config->base_url();
                        $lname = '';
                        if ($this->input->post('last_name') != '') 
                        {
                            $lname = " " . $this->input->post('last_name');
                        }
                        $email = $this->input->post('email_id');
                        $name = $this->input->post('first_name') . $lname;
                        $emaildata['to'] = $email;
                        $emaildata['subject'] = 'Giftcast - Welcome';
                        $emaildata['message'] = "Hello".' '.$name.",<br><br>Welcome to Giftcast
                        .<br/><br/>Your Login Credentials are as below <br> Email : ".$email."<br>Password : ".$token."<br>
                        <br> Thanks,<br/><b>Giftcast team</b>.";
                        sendmail($emaildata);

                        $msg = "User Added Successfully";
                        $this->session->set_flashdata('success_message', $msg);
                        redirect('admin/user');
                    }
                } 
                else 
                {
                    $msg = "Email id or phone number already Registered";
                    $this->session->set_flashdata('error_message', $msg);
                    redirect('admin/add-user');
                }
            }
            else
            {
                $msg = "Email id or phone number already Registered";
                $this->session->set_flashdata('error_message', $msg);
                redirect('admin/add-user');
            }
        }
        $data['body'] = "admin/add_user.php";
        $this->load->view('admin/template.php', $data);
    }

    public function edit_user($eid) 
    {
        if ($eid != "") 
        {
            $data = array(
                'id' => $eid
            );


            if ($this->input->post('submit')) 
            {
                $baseurl = $this->config->base_url();
                $phone_number = $this->input->post('phone_number');
                //$data['phone_number'] = $phone_number;
                $data = array('id !=' => $eid,'phone_number' => $phone_number);
                $check_user = $this->User_model->getAnyData($data);
                if(empty($check_user))
                {
                    $set = array(
                    'fname' => $this->input->post('first_name'),
                    'lname' => $this->input->post('last_name'),
                    'phone_number' => $this->input->post('phone_number'),
                    'status' => $this->input->post('status'),
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'updated_at' => date("Y-m-d H:i:s")
                    );
                
                    /* Start: Image Uploading */
                    $profile_photo = "";
                    $profile_path = '';
                    $config['upload_path'] = './assets/profile_pictures';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = '0';
                    $config['max_width'] = '0';
                    $config['max_height'] = '0';
                
                    if (!empty($_FILES['image']['name'])) 
                    {
                        
                        $this->file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $this->file_name = time() . "." . $this->file_ext;
                        $config['file_name'] = $this->file_name;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if (!$this->upload->do_upload('image', FALSE)) 
                        {
                            echo $this->upload->display_errors();
                            $this->form_validation->set_message('checkdoc', $data['error'] = $this->upload->display_errors());
                            if ($_FILES['image']['error'] != 4) 
                            {
                                return false;
                            }
                        } 
                        else 
                        {
                            $profile_photo = $this->file_name;
                            $profile_path = $baseurl . "assets/profile_pictures";
                        }

                        $user_data = $this->User_model->getAnyData($data);
                        
                        $old_profilepicture = $user_data[0]->profile_picture;
                        $fullpath = './assets/profile_pictures/'.$old_profilepicture;
                        
                        unlink('./assets/profile_pictures/'.$old_profilepicture);

                        $set = array(
                        'fname' => $this->input->post('first_name'),
                        'lname' => $this->input->post('last_name'),
                        'profile_picture' => $profile_photo,
                        'phone_number' => $this->input->post('phone_number'),
                        'status' => $this->input->post('status'),
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'updated_at' => date("Y-m-d H:i:s")
                        );
                    }

                    /* End: Image Uploading */

                    $this->User_model->update($set, $data);
                    $msg = "User Updated Successfully";
                    $this->session->set_flashdata('success_message', $msg);
                    redirect('admin/user');
                }
                else
                {
                    $msg = "Email id or phone number already Registered";
                    $this->session->set_flashdata('error_message', $msg);
                    redirect('admin/edit-user/'.$eid);
                }
            }


            $data['edtUser'] = $this->User_model->getAnyData($data);
            $data['body'] = "admin/add_user.php";
            $this->load->view('admin/template.php', $data);
        }
        else 
        {
            redirect('admin/user');
        }
    }

    public function delete_user($did) 
    {

        if ($did != "") 
        {
            $did = explode(",", $did);
            $user_data = $this->User_model->getAnyData('','','','','',$did);
            foreach($user_data as $u)
            {
                $profile_picture = $u->profile_picture;
                if($profile_picture!="" && !empty($profile_picture))
                {
                    unlink('./assets/profile_pictures/'.$profile_picture);
                }
            }
            
            $this->User_model->delete($did);
            $msg = "User Deleted Successfully";
            $this->session->set_flashdata('success_message', $msg);   
        }
            redirect("admin/user");
    }

    public function delete_profile_picture()
    {
        $image_name = $this->input->post('image_name');
        $id = $this->input->post('id');

        unlink('./assets/profile_pictures/'.$image_name);

        $set = array(
            'profile_picture' => "",
            'updated_at' => date("Y-m-d H:i:s")
        );
        $data['id'] = $id;
        $update = $this->User_model->update($set, $data);
        if($update)
        {
            echo 'success';
        }
    }
    public function ChangeUserStatus() 
    {

        if (!empty($_POST) && isset($_POST)) {
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            
            if ($id != "" && $status != "") {
                $where['id'] = $id;
                $data = array(
                    'status' => $status,
                    'updated_at' => date("Y-m-d H:i:s")
                );
                $update = $this->User_model->update($data, $id, "1");
                $msg = "Status changed Successfully";
                $this->session->set_flashdata('success_message', $msg);
            }
            else{
                $this->session->set_flashdata('error_message', "Something went wrong please try again.");
            }
        }
    }

        public function ChangePwd() 
        {
            if ($_REQUEST['unq'] != '' && $_REQUEST['em'] != '' && $_REQUEST['tm'] != '') 
            {
                $unique_id = base64_decode($_REQUEST['unq']);
                $u_email = base64_decode($_REQUEST['em']);
                $email_para = array(
                    'id' => $unique_id
                );

                $check_avaibility = $this->User_model->getAnyData($email_para);
                // print_r($check_avaibility); die;
                $url = current_full_url();

                if (!empty($check_avaibility)) 
                {
                    $u_time = $_REQUEST['tm'];
                    $cur_time = time();
                    if ($cur_time - $u_time < 10800) 
                    {
                        if ($this->input->post('Submit')) 
                        {
                            $n_pwd = md5($this->input->post('n_pwd'));
                            $c_pwd = md5($this->input->post('c_pwd'));

                            if ($n_pwd != '' && $n_pwd == $c_pwd) 
                            {
                                $set = array(
                                    'password' => $c_pwd
                                    );

                                $where = array(
                                    'id' => $unique_id
                                );


                                $update = $this->User_model->update($set, $where);
                                if (!empty($update)) 
                                {

                                    redirect("view-success");
                                }
                            } 
                            else 
                            {
                                $msg = 'Password not match'; 
                                $this->session->set_flashdata('error_message', $msg);
                                redirect($url);

                                $data['body'] = "web/change_pwd";
                                $this->load->view('admin/template.php', $data);
                            }
                        } 
                        else 
                        {
                            $data['body'] = "web/change_pwd";
                            $this->load->view('admin/template.php', $data);
                        }
                }
                else 
                {

                    /*$msg = 'This link is expired, Please try again'; 
                    $this->session->set_flashdata('error_message', $msg);
                    redirect($url);*/
                    redirect("view-error");
                }
            }
            else 
            {
               /* $data = array(
                    'error_message' => 'This link is expired, Please try again'
                );
                $data['body'] = "web/change_pwd";
                $this->load->view('admin/template.php', $data);*/
                redirect("view-error");
            }
        } 
        else 
        {
            $data = array(
                'heading' => 'Page Not Found',
                'message' => $url . " URL Is not proper please check"
            );
            $data['body'] = "web/change_pwd";
            $this->load->view('web/template.php', $data);
        }
    }

}

?>