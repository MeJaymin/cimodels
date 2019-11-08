  <?php

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model'); 
        $this->load->model('Logs_model');
    }

    public function index() 
    {

        if (isAdmin()) 
        {
            redirect('admin/user');
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() === FALSE) 
        {

            $data['body'] = "admin/login";
            $this->load->view('admin/template', $data);
        }
        else 
        {
            $password = md5($this->input->post('password'));
            $data_log = array(
                'email_id' => $this->input->post('email'),
                'password' => $password
            );

            $result = $this->User_model->getAnyData($data_log);

            if (!empty($result)) {

                $ipaddress = $_SERVER['REMOTE_ADDR'];
                $email = $this->input->post('email');

                $session_data = array(
                    'admin_email' => $result[0]->email_id,
                    'Admin' => TRUE,
                    'user_id' => $result[0]->id
                );

                // Add user data in session
                $this->session->set_userdata($session_data);
                $data=array(
                            "user_id" => $result[0]->id,
                            "loggedin_time" => date("Y-m-d H:i:s"),
                            "ipaddress" => $ipaddress
                        );
                 $this->Logs_model->Insert_Data($data);
                $this->session->set_flashdata('success_message', 'Successfully LoggedIn');
                redirect('admin/user');
            } else {
                $this->session->set_flashdata('error_message', 'Invalid Username or Password');
                redirect('admin');
            }
        }
    }

    public function edit_user() {
        if ($this->uri->segment(4) === FALSE) {
            $product_id = 0;
        } else {
            $product_id = $this->uri->segment(4);
        }

        echo $product_id;
    }

    public function logout() {
        $sess_array = array(
            'admin_email' => ''
        );
        $this->session->unset_userdata('Admin', $sess_array);
        redirect('admin');
    }

    

}

?>
