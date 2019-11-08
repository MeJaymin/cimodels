<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "./library/jwt/vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


class Admin extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Admin_model');
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

    public function login() 
    {
        /* Field required Validation */
        $this->form_validation->set_rules('email_id', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() === FALSE) 
        {
            $response['status'] = 0;
            $response['message'] = 'Please enter all fields';
        } 
        else 
        {
            $check_admin['email_id'] = $this->input->post('email_id');
            $check_admin['password'] = base64_encode($this->input->post('password'));
            $result = $this->Admin_model->getAnyData($check_admin);
            if (!empty($result)) 
            {
                // $secret_key = "YOUR_SECRET_KEY";
                // $issuer_claim = "THE_ISSUER"; // this can be the servername
                // $audience_claim = "THE_AUDIENCE";
                // //display the converted time
                // $issuedat_claim = time(); // issued at
                // $notbefore_claim = $issuedat_claim + 10; //not before in seconds
                // //$expire_claim = $issuedat_claim + date('Y-m-d H:i',strtotime('+1 hour',strtotime(date('Y-m-d H:i:s')))); // expire time in seconds
                // $expire_claim = $issuedat_claim + 60; // expire time in seconds
                // $token = array(
                //     "iss" => $issuer_claim,
                //     "aud" => $audience_claim,
                //     "iat" => $issuedat_claim,
                //     "nbf" => $notbefore_claim,
                //     "exp" => $expire_claim,
                //     "data" => array(
                //         "id" => $id,
                //         "name" => $name
                // ));
                // print_r($token); die;
                // echo $jwt = JWT::encode($token, $secret_key); die;
                $response['status'] = 1;
                $response['message'] = "Successfully Loggedin";
            } 
            else 
            {
                $response['status'] = 0;
                $response['message'] = 'Invalid Email or Password';
            }
        }
        echo json_encode($response);
    }

    public function validateToken()
    {
        $secret_key = "YOUR_SECRET_KEY";
        $issuer_claim = "THE_ISSUER"; // this can be the servername
        $audience_claim = "THE_AUDIENCE";
        //display the converted time
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 10; //not before in seconds
        //$expire_claim = $issuedat_claim + date('Y-m-d H:i',strtotime('+1 hour',strtotime(date('Y-m-d H:i:s')))); // expire time in seconds
        $expire_claim = $issuedat_claim + 60; // expire time in seconds
        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "id" => $id,
                "name" => $name
        ));
        $jwt = JWT::encode($token, $secret_key);
        return $jwt;
        // echo json_encode(
        //     array(
        //         "message" => "Successful login.",
        //         "jwt" => $jwt,
        //         "email" => $email,
        //         "expireAt" => $expire_claim
        //     ));
    }
}
?>

