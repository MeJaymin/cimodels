<?php

class Payment_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Payment_model');
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
            $tax = $this->input->post('tax');
            $id = $this->input->post('id');
            $where['status'] = 1;
            $tax_data = $this->Payment_model->getAnyData();
            if(!empty($tax_data))
            {
                //update
                $set['updated_at'] = date("Y-m-d H:i:s");
                $set['tax'] = $tax;
                $data['id'] = $id;
                $this->Payment_model->update($set, $data);
                $msg = "Tax has been updated successfully";
                $this->session->set_flashdata('success_message', $msg);
                redirect('admin/set-tax');
            }
            else
            {
                //insert
                $tax_data['tax'] = $tax;
                $tax_data['created_at'] = date("Y-m-d H:i:s");
                $tax = $this->Payment_model->Insert_Data($tax_data);
                if($tax)
                {
                    $msg = "Tax has been updated";
                    $this->session->set_flashdata('success_message', $msg);
                    redirect('admin/set-tax');    
                }
                
            }
            
        }
        else
        {
            $data['tax_data'] = $this->Payment_model->getAnyData();
            $data['body'] = "admin/set_tax.php";
            $this->load->view('admin/template.php', $data);    
        }
    }
}

?>