<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Company extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Company_model');
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

    public function add_company() 
    {
        $this->form_validation->set_rules('username', 'name', 'trim|required');
        $this->form_validation->set_rules('password', 'tagline', 'trim|required');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('tagline', 'tagline', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim|required');
        $this->form_validation->set_rules('color_code', 'color_code', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['status'] = 0;
            $response['message'] = 'Please enter all fields';
        } 
        else 
        {
            $company_data['username'] = $this->input->post('username');
            $company_data['password'] = base64_encode($this->input->post('password'));
            $company_data['name'] = ucwords($this->input->post('name'));
            $company_data['tagline'] = $this->input->post('tagline');
            $company_data['description'] = $this->input->post('description');
            $company_data['color_code'] = $this->input->post('color_code');
            $company_data['status'] = 1;
            $company_data['created_at'] = date("Y-m-d H:i:s");
            //as per updated scenario check phone number exists
            $company_check['name']= $this->input->post('name');
            $check_signup_result = $this->Company_model->getAnyData($company_check);
            if(empty($check_signup_result))
            {
                //Check Username has been taken or not
                $company_check_username['username']= $this->input->post('username');
                $check_signup_result_username = $this->Company_model->getAnyData($company_check_username);
                if(empty($check_signup_result_username))
                {    
                    if(!empty($this->input->post('logo')))
                    {
                        $base64_encoded_file = $this->input->post('logo');
                        $filename = time().'.png';
                        file_put_contents('assets/company/'.$filename, base64_decode ($base64_encoded_file)) ;
                    }
                    else
                    {
                        $response['status'] = 0;
                        $response['message'] = 'Logo Required';
                        echo json_encode($response);
                        die;
                    }

                    $company_data['logo'] = $filename;
                    $this->send_email(); die;
                    $company_data_success = $this->Company_model->insert($company_data);
                    if(!empty($company_data_success))
                    {
                        //Send Credentials to company
                        $response['status'] = 1;
                        $response['message'] = 'Successfly registered company';
                    }
                    else
                    {
                        $response['status'] = 0;
                        $response['message'] = 'Issue while registering company';
                    }
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = 'Username already exists';
                }
            }
            else
            {   
                $response['status'] = 0;
                $response['message'] = 'Company name already exists';
            }
        }
        echo json_encode($response);
    }

    public function send_email()
    {
        $email_id = "";
        $password="";
        $email_data['to'] = 'jayminsejpal@gmail.com';
        $email_data['subject'] = 'jayminsejpal@gmail.com'; 
        $message = "Hello $email_id Company Logo <br>Congratulations! Your Company account was successfully created.<br>By creating a Company account you have also agreed to the Tiles Project Terms of Service and Privacy Policy (link). <br> Here you can find the credentials:<br> Email: $email_id <br> Password: $password <br> Thanks for Joining us. hope you enjoy our services.";
        $email_data['message'] = $message;
        sendmail($email_data);
        print_r($email_data); die;
    }
    public function edit_company()
    {
        $this->form_validation->set_rules('id', 'id', 'trim|required');
        $this->form_validation->set_rules('username', 'name', 'trim|required');
        $this->form_validation->set_rules('password', 'tagline', 'trim|required');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('tagline', 'tagline', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim|required');
        $this->form_validation->set_rules('color_code', 'color_code', 'trim|required');
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
            $username = $this->input->post('username');
            $password = base64_encode($this->input->post('password'));
            $name = ucwords($this->input->post('name'));
            $tagline = $this->input->post('tagline');
            $description = $this->input->post('description');
            $color_code = $this->input->post('color_code');
            $status = 1;
            if(isset($name))
            {
                //Check Username has been taken or not
                $company_check_username['name']= $this->input->post('name');
                $company_check_username['id!=']= $id;
                $check_signup_result_username = $this->Company_model->getAnyData($company_check_username);
                if(empty($check_signup_result_username))
                {
                    $set['name'] = $name;
                    $set['updated_at'] = date("Y-m-d H:i:s");
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = 'Company name already exists';
                    echo json_encode($response);
                    die;
                }
            }
            if(!empty($this->input->post('logo')))
            {
                if(!empty($this->input->post('logo')))
                {
                    $base64_encoded_file = $this->input->post('logo');
                    $filename = time().'.png';
                    file_put_contents('assets/company/'.$filename, base64_decode ($base64_encoded_file)) ;
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = 'Logo Required';
                    echo json_encode($response);
                    die;
                }
                $set['logo'] = $filename;
            }

            if(isset($username))
            {
                //Check Username has been taken or not
                $company_check_username['username']= $this->input->post('username');
                $company_check_username['id!=']= $id;
                $check_signup_result_username = $this->Company_model->getAnyData($company_check_username);
                if(empty($check_signup_result_username))
                {
                    $set['username'] = $username;
                    $set['updated_at'] = date("Y-m-d H:i:s");
                }
                else
                {
                    $response['status'] = 0;
                    $response['message'] = 'Username already exists';
                    echo json_encode($response);
                    die;
                }
            }

            if(isset($password))
            {
                $set['password'] = $password;
                $set['updated_at'] = date("Y-m-d H:i:s");
            }
            if(isset($tagline))
            {
                $set['tagline'] = $tagline;
                $set['updated_at'] = date("Y-m-d H:i:s");
            }
            if(isset($description))
            {
                $set['description'] = $description;
                $set['updated_at'] = date("Y-m-d H:i:s");
            }
            if(isset($color_code))
            {
                $set['color_code'] = $color_code;
                $set['updated_at'] = date("Y-m-d H:i:s");
            }
            $set['status'] = $status;
            $where['id'] = $id;
            $update = $this->Company_model->update($set, $where);
            if(!empty($update))
            {
                $response['status'] = 1;
                $response['message'] = 'Successfly updated company details';
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'Issue while updating company details';
            }
            echo json_encode($response);
        }
    }

    public function delete_company() 
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
            $company_check_username['id']= $id;
            $check_signup_result_username = $this->Company_model->getAnyData($company_check_username);
            //print_r($check_signup_result_username); die;
            if(!empty($check_signup_result_username))
            {
                if($logo!="" && !empty($logo))
                {
                    unlink('./assets/company/'.$logo);
                }
                $where['id'] = $id;
                $company_delete = $this->Company_model->delete($where);
                if($company_delete)
                {
                    $response['status'] = 1;
                    $response['message'] = 'Successfly conpany deleted';
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

    public function fetch_company() 
    {
        $id = $this->input->post('id');
        if(isset($id))
        {
            $where['id'] = $id;
            $company_data = $this->Company_model->getAnyData($where);
            if(!empty($company_data))
            {
                $response['status'] = 1;
                $response['data'] = $company_data;
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
            $company_data = $this->Company_model->getAnyData();
            if(!empty($company_data))
            {
                $response['status'] = 1;
                $response['data'] = $company_data;
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

    public function company_login()
    {
        $this->form_validation->set_rules('username', 'color_code', 'trim|required');
        $this->form_validation->set_rules('password', 'status', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['status'] = 0;
            $response['message'] = 'Please enter all fields';
        } 
        else 
        {
            $company_data['username'] = $this->input->post('username');
            $company_data['password'] = base64_encode($this->input->post('password'));
            $check_signup_result = $this->Company_model->getAnyData($company_data);
            if(!empty($check_signup_result))
            {
                $response['status'] = 1;
                $response['message'] = 'Successfly Loggedin';
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'Invalid Username and password';
            }
        }
        echo json_encode($response);
    }    
}
?>

