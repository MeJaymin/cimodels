<?php

class Report_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Report_model');
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
        $select ="report.*,users.id,users.fname,users.lname";
        $join_arr[] = array( 
                                "table_name" => "users", 
                                "cond" => "report.user_id=users.id", 
                                "type" => "inner" 
                                );
        $data['report'] = $this->Report_model->getAnyData("",$select,"","",$join_arr);
        $data['body'] = "admin/report.php";
        $this->load->view('admin/template.php', $data);
    }
    
}

?>