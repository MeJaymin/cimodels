<?php
class Roles extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Roles_model');
    }


    /**
 * @api {post} api/ws_signin User Login
 * @apiVersion 1.0.0
 * @apiName UserLogin
 * @apiGroup Users
 *
 * @apiDescription Login
 *
 * @apiParam {Number} email_id Email id.
 * @apiParam {Character} password Password.
 * @apiParam {Number} device_token Device token
 * @apiParam {Number} device_type Device Type E.g Android, Ios.
 */
    /*
    @Author: Jaymin Sejpal
    @description: Login WS for a user.*/

    public function add_role() 
    {
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('description', 'tagline', 'trim|required');
        $this->form_validation->set_rules('added_by', 'name', 'trim|required');
        $this->form_validation->set_rules('added_with', 'tagline', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['status'] = 0;
            $response['message'] = 'Please enter all fields';
        } 
        else 
        {
            $role_data['name'] = $this->input->post('name');
            $role_data['description'] = $this->input->post('description');
            $role_data['added_by'] = $this->input->post('added_by');
            $role_data['added_with'] = $this->input->post('added_with');
            $role_data['company_id'] = $this->input->post('company_id');
            $role_data['status'] = 1;
            $role_data['created_at'] = date("Y-m-d H:i:s");
            //as per updated scenario check phone number exists
            $role_check['name']= $this->input->post('name');
            $role_check['company_id']= $this->input->post('company_id');
            $check_signup_result = $this->Roles_model->getAnyData($role_check);
            if(empty($check_signup_result))
            {
                //Check name has been taken or not
                $role_data_success = $this->Roles_model->insert($role_data);
                if(!empty($role_data_success))
                {
                    $response['status'] = 1;
                    $response['message'] = 'Successfly inserted role';
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = 'Issue while inserting role';
                }
            }
            else
            {   
                $response['status'] = 0;
                $response['message'] = 'This role already exists';
            }
        }
        echo json_encode($response);
    }

    
    public function edit_role()
    {
        $this->form_validation->set_rules('id', 'id', 'trim|required');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('description', 'tagline', 'trim|required');
        $this->form_validation->set_rules('added_by', 'name', 'trim|required');
        $this->form_validation->set_rules('added_with', 'tagline', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['status'] = 0;
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        } 
        else 
        {
            $id = $this->input->post('id');
            $name = $this->input->post('name');
            $description = $this->input->post('description');
            $added_by = $this->input->post('added_by');
            $added_with = $this->input->post('added_with');
            $status = $this->input->post('status');
            $company_id = $this->input->post('company_id');
            //as per updated scenario check phone number exists
            $role_check['name']= $this->input->post('name');
            if(isset($name))
            {
                //Check Username has been taken or not
                $role_name['name']= $this->input->post('name');
                $role_data['company_id'] = $this->input->post('company_id');
                $role_name['id!=']= $id;
                $check_signup_result_username = $this->Roles_model->getAnyData($role_name);
                if(empty($check_signup_result_username))
                {
                    $set['name'] = $name;
                    $set['updated_at'] = date("Y-m-d H:i:s");
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = 'role name already exists';
                    echo json_encode($response);
                    die;
                }
            }
            if(isset($description))
            {
                $set['description'] = $description;
            }

            if(isset($added_by))
            {
                $set['added_by'] = $added_by;
                $set['updated_at'] = date("Y-m-d H:i:s");
            }

            if(isset($added_with))
            {
                $set['added_with'] = $added_with;
                $set['updated_at'] = date("Y-m-d H:i:s");
            }
            if(isset($status))
            {
                $set['status'] = $status;
                $set['updated_at'] = date("Y-m-d H:i:s");
            }
            if(isset($company_id))
            {
                $set['company_id'] = $company_id;
                $set['updated_at'] = date("Y-m-d H:i:s");
            }
            $where['id'] = $id;
            $update = $this->Roles_model->update($set, $where);
            if(!empty($update))
            {
                $response['status'] = 1;
                $response['message'] = 'Successfly updated roles details';
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'Issue while updating roles details';
            }
            echo json_encode($response);
        }
    }

    public function delete_role() 
    {
        $this->form_validation->set_rules('id', 'id', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['status'] = 0;
            $response['message'] = 'Please enter all fields';
        } 
        else 
        {
            $id = $this->input->post('id');
            $role_check['id']= $id;
            $check_signup_result_username = $this->Roles_model->getAnyData($role_check);
            //print_r($check_signup_result_username); die;
            if(!empty($check_signup_result_username))
            {
                $where['id'] = $id;
                $role_delete = $this->Roles_model->delete($where);
                if($role_delete)
                {
                    $response['status'] = 1;
                    $response['message'] = 'Successfly role deleted';
                }
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'No Such ID exists.';
            }
        }
        echo json_encode($response);
    }

    public function fetch_role() 
    {
        $id = $this->input->post('id');
        if(isset($id))
        {
            $where['roles.id'] = $id;
            $join_arr[1] = array( 
                                "table_name" => "company", 
                                "cond" => "roles.company_id=company.id", 
                                "type" => "left" 
                                );
            $select = "roles.*,company.name as companyname";
            $fetch_data = $this->Roles_model->getAnyData($where,$select,"","",$join_arr);
            if(!empty($fetch_data))
            {
                $response['status'] = 1;
                $response['data'] = $fetch_data;
                $response['message'] = 'Company Data fetched Successfly';
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'No Data Found';
            }
        }
        else
        {
            $join_arr[1] = array( 
                                "table_name" => "company", 
                                "cond" => "roles.company_id=company.id", 
                                "type" => "left" 
                                );
            $select = "roles.*,company.name as companyname";
            $fetch_data = $this->Roles_model->getAnyData("",$select,"","",$join_arr);
            if(!empty($fetch_data))
            {
                $response['status'] = 1;
                $response['data'] = $fetch_data;
                $response['message'] = 'Company Data fetched Successfly';
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'No Data Found';
            }
        }
        echo json_encode($response);
    }  
}
?>

