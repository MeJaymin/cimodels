  <?php

class Company extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // $this->load->model('User_model'); 
        // $this->load->model('Logs_model');
    }

    public function fetch_company()
    {
        $data['company_listing'] = "";
        $apidata['api_url'] = 'ws_admin_fetch_company';
        $response = callapi($apidata);
        if($response['status'] == 1)
        {
            $data['company_listing'] = $response['data'];    
        }
        $data['body'] = "company_listing";
        $this->load->view('template', $data);
    }

    public function add_company()
    {
        if(!empty($this->input->post()))
        {
            // print_r($_POST);
            //print_r($_FILES); die;
            $post_data['username'] = $this->input->post('username');
            $post_data['password'] = $this->input->post('password');
            $post_data['name'] = $this->input->post('company_name');
            $post_data['tagline'] = $this->input->post('tagline');
            $post_data['description'] = $this->input->post('description');
            $post_data['color_code'] = $this->input->post('color_code');
            $post_data['logo'] =  base64_encode(file_get_contents($_FILES['logo']['tmp_name']));
            $post_data['status'] = 1;
            $apidata['api_url'] = 'ws_admin_add_company';
            $apidata['postdata'] = $post_data;
            //print_r($apidata['postdata']); die;
            $response = callapi($apidata);
            print_r($response); die;
            if(!empty($response))
            {
                if($response['status'] == 1)
                {
                    redirect('admin/company-listing');
                }
                else
                {
                    $this->session->set_flashdata('error_message', $response['message']);
                }
                redirect('add-company');
            }
        }
        $data['body'] = "add_company";
        $this->load->view('template', $data);   
    }

    public function edit_company($id)
    {
        if(!empty($this->input->post()))
        {
            $post_data['id'] = $id;
            $post_data['username'] = $this->input->post('username');
            $post_data['password'] = $this->input->post('password');
            $post_data['name'] = $this->input->post('company_name');
            $post_data['tagline'] = $this->input->post('tagline');
            $post_data['description'] = $this->input->post('description');
            $post_data['color_code'] = $this->input->post('color_code');
            if(!empty($_FILES['logo']['tmp_name']))
            {
                $post_data['logo'] =  base64_encode(file_get_contents($_FILES['logo']['tmp_name']));
            }
            $post_data['status'] = 1;
            $apidata['api_url'] = 'ws_admin_edit_company';
            $apidata['postdata'] = $post_data;
            //print_r($apidata['postdata']); die;
            $response = callapi($apidata);
            if(!empty($response))
            {
                if($response['status'] == 1)
                {
                    redirect('admin/company-listing');
                }
                else
                {
                    $this->session->set_flashdata('error_message', $response['message']);
                }
                redirect('admin/edit-company/$id');
            }
        }
        $post_data['id'] = $id;
        $apidata['postdata'] = $post_data;
        $apidata['api_url'] = 'ws_admin_fetch_company';
        $response = callapi($apidata);
        if($response['status'] == 1)
        {
            $data['edit_company'] = $response['data'];    
        }
        $data['body'] = "add_company";
        $this->load->view('template', $data);
    }


    public function delete_company($did)
    {
        if ($did != "") 
        {
            $did = explode(",", $did);
            foreach ($did as $value) 
            {
                $post_data['id'] = $value;
                $apidata['postdata'] = $post_data;
                $apidata['api_url'] = 'ws_admin_delete_company';
                $response = callapi($apidata);
            }
            redirect('admin/company-listing');
        }
    }

}

?>