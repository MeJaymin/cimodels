<?php
	class Web_controller extends CI_Controller
	{
		
    	public function __construct() {
        	parent::__construct();
        	$this->load->model('User_model');
		}

	/*
      @ START:  Change PASSWORD
      ---> Linck Only works Once for change password After that it will Expire
      ---> link will Expire after 3 hours from sending time.
      ---> After Successfully change password User can login with new/updated password only
    */

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
                                    'password' => $c_pwd,
                                    'updated_at' => date("Y-m-d H:i:s")
                                    );

                                $where = array(
                                    'id' => $unique_id
                                );


                                $update = $this->User_model->update($set, $where);
                                if (!empty($update)) 
                                {
                                    /*@Date:26/3/18*/
                                    $fullname = $check_avaibility[0]->fname.' '.$check_avaibility[0]->lname;
                                    $email = $check_avaibility[0]->email_id;
                                    $message="You have successfully changed your account password <br> and it is ready to use immediately.";
                                    $emaildata['to'] = $email;
                                    $emaildata['subject'] = 'Your GiftCast password has been changed'; 
                                    $emaildata['message'] = file_get_contents(base_url().'email_templates/change-pass.php');
                                    $emaildata['message'] = str_replace('FULLNAME', $fullname, $emaildata['message']);
                                    $emaildata['message'] = str_replace('MESSAGE', $message, $emaildata['message']);
                                    sendmail($emaildata);
                                    
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
/*                    $msg = 'This link is expired, Please try again'; 
                    $this->session->set_flashdata('error_message', $msg);
                    redirect($url);*/
                    redirect("view-error");
                }
            }
            else 
            {
/*                $data = array(
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


    /*
      @ End:  Change PASSWORD
     */

    public function Load_successmsg()
    {   
        $this->load->view('web/changepwd_success');
    }
    public function Load_error()
    {   
        $this->load->view('web/changepwd_error');
    }

    public function open_application()
    {
        $this->load->view('web/application_store');
    }
}
?>