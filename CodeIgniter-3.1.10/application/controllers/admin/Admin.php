  <?php

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // $this->load->model('User_model'); 
        // $this->load->model('Logs_model');
    }

    public function index() 
    {
        if(!empty($this->input->post()))
        {
            $post_data['email_id'] = $this->input->post('email_id');
            $post_data['password'] = $this->input->post('password');
            $apidata['api_url'] = 'ws_admin_signin';
            $apidata['postdata'] = $post_data;
            $response = callapi($apidata);
            // print_r($response); die;
            if($response['status'] == 0)
            {
                $this->session->set_flashdata('error_message', $response['message']);
                redirect('admin/dashboard');
            }
            else
            {
                //echo "Successfully logged in";
                redirect('/admin/dashboard');
            }
        }
        else
        {
            $this->load->view('admin_login');
        }     
    }

    public function admin_dashboard()
    {
        $data['body'] = "admin_dashboard";
        $this->load->view('template', $data);
    }

}

?>
