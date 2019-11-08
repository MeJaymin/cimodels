<?php

class Gift_controller extends CI_Controller 
{

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Gift_model');
        $this->load->model('Transactions_model');
        
        if (!isAdmin()) 
        {
            redirect('admin');
        }
    }

    /*
    @description
        Loads the page with all entries 
    */
    public function index() 
    {
        $select ="gifts.*,users.email_id";
        $join_arr[] = array( 
                                "table_name" => "users", 
                                "cond" => "gifts.recipient_id=users.id", 
                                "type" => "inner" 
                                );
        $data['gifts'] = $this->Gift_model->getAnyData("",$select,"","",$join_arr);
        
        $data['body'] = "admin/gifts.php";
        $this->load->view('admin/template.php', $data);
    }

    public function detail_gift_view($id)
    {
        $where['id'] = $id;
        $data['gifts'] = $this->Gift_model->getAnyData($where);

        $data['body'] = "admin/detail_gift.php";
        $this->load->view('admin/template.php', $data);
    }

    public function gifts_truncate()
    {
        $data = $this->Gift_model->truncate();
        $data1 = $this->Transactions_model->truncate();
        $msg = "Gifts data deleted successfully";
        $this->session->set_flashdata('success_message', $msg);
        echo 1;
    }
}