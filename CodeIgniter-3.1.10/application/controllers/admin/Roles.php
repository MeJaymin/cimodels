  <?php

class Roles extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function fetch_roles()
    {
        $data['roles_listing'] = "";
        $apidata['api_url'] = 'ws_admin_fetch_role';
        $response = callapi($apidata);
        if($response['status'] == 1)
        {
            $data['roles_listing'] = $response['data'];    
        }
        $data['body'] = "roles_listing";
        $this->load->view('template', $data);
    }

    public function add_roles()
    {
        if(!empty($this->input->post()))
        {
            // print_r($_POST);
            //print_r($_FILES); die;
            $post_data['name'] = $this->input->post('name');
            $post_data['description'] = $this->input->post('description');
            $post_data['status'] = $this->input->post('status');
            $post_data['company_id'] = $this->input->post('c_id');
            $post_data['added_by'] = 1;
            $post_data['added_with'] = 1;
            $apidata['api_url'] = 'ws_admin_add_role';
            $apidata['postdata'] = $post_data;
            //print_r($apidata['postdata']); die;
            $response = callapi($apidata);
            // print_r($response); die;
            if(!empty($response))
            {
                if($response['status'] == 1)
                {
                    redirect('admin/roles-listing');
                }
                else
                {
                    $this->session->set_flashdata('error_message', $response['message']);
                }
                redirect('admin/add-roles');
            }
            else
            {
                $this->session->set_flashdata('error_message', $response['message']);
                redirect('admin/add-roles');   
            }
        }

        //fetch Company
        $data['company_listing'] = "";
        $apidata['api_url'] = 'ws_admin_fetch_company';
        $response = callapi($apidata);
        $data['company_listing'] = $response['data'];
        $data['body'] = "add_roles";
        $this->load->view('template', $data);   
    }

    public function edit_roles($id)
    {
        if(!empty($this->input->post()))
        {
            $post_data['id'] = $id;
            $post_data['name'] = $this->input->post('name');
            $post_data['description'] = $this->input->post('description');
            $post_data['status'] = $this->input->post('status');
            $post_data['company_id'] = $this->input->post('c_id');
            $post_data['added_by'] = 1;
            $post_data['added_with'] = 1;
            $apidata['api_url'] = 'ws_admin_edit_role';
            $apidata['postdata'] = $post_data;
            //print_r($apidata['postdata']); die;
            $response = callapi($apidata);
            if(!empty($response))
            {
                if($response['status'] = 1)
                {
                    redirect('admin/roles-listing');
                }
                else
                {
                    $this->session->set_flashdata('error_message', $response['message']);
                }
                redirect('admin/edit-role/$id');
            }
        }
        $post_data['id'] = $id;
        $apidata['postdata'] = $post_data;
        $apidata['api_url'] = 'ws_admin_fetch_role';
        $response = callapi($apidata);
        if($response['status'] == 1)
        {
            $data['edit_role'] = $response['data'];
            //fetch Company
            $apidata1['api_url'] = 'ws_admin_fetch_company';
            $response1 = callapi($apidata1);
            $data['company_listing'] = $response1['data'];    
        }
        //print_r($data); die;
        $data['body'] = "add_roles";
        $this->load->view('template', $data);
    }


    public function delete_roles($did)
    {
        if ($did != "") 
        {
            $did = explode(",", $did);
            foreach ($did as $value) 
            {
                $post_data['id'] = $value;
                $apidata['postdata'] = $post_data;
                $apidata['api_url'] = 'ws_admin_delete_role';
                $response = callapi($apidata);
            }
            redirect('admin/roles-listing');
        }
    }

}

?>