<?php

class Notification_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Notification_model');
        if (!isAdmin()) {
            redirect('admin');
        }
    }

    /*
    @author: Jaymin
    @description: Send Push Notification to all active users
    */
    public function index() 
    {
        if ($this->input->post('submit')) 
        {
            $message = $this->input->post('message');
            $where['status'] = 1;
            $users = $this->User_model->getAnyData($where);
            if(!empty($users))
            {
                foreach ($users as $u) 
                {
                    $where_notification['user_id'] = $u->id;
                    $where_notification['admin_giftcast_settings'] = 1;
                    $notification_check = $this->Notification_model->getAnyData($where_notification);
                    $deviceToken = $u->device_token;
                    $title = "Giftcast";
                    $message = $message;
                    $giftdata = "";
                    if(!empty($notification_check))
                    {
                        if(!empty($u->device_token))
                        {
                            if($u->device_type == 'ios')
                            {
                                send_ios_notification($deviceToken,$message,$title,$giftdata);
                            }
                            else
                            {
                                send_android_notification($deviceToken,$message,$title,$giftdata);
                            }
                        }
                    }
                }
                $msg = "Notification sent successfully";
                $this->session->set_flashdata('success_message', $msg);
                redirect('admin/notification');
            }
            $msg = "No Users Found!";
            $this->session->set_flashdata('error_message', $msg);
            redirect('admin/notification');
        }
        else
        {
            $data['body'] = "admin/notification.php";
            $this->load->view('admin/template.php', $data);    
        }
    }
}

?>