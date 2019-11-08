<?php
class Notification extends CI_Controller 
{

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Notification_model');
    }

    
	/**
	* @api {post} api/ws_notification_settings Notification settings
	* @apiVersion 1.0.0
	* @apiName Notification Settings
	* @apiGroup Notifications
	*
	* @apiDescription Notification settings can be enabled/disabled.
	*
	* @apiParam {Number} user_id The user id.
	* @apiParam {Number} [recieve_gift] Recieve gift
	* @apiParam {Number} [send_gift] Send gift
	* @apiParam {Number} [contacts_joined] Contacts Joined
	* @apiParam {Number} [admin_giftcast_settings] Admin Notification
	*/

    public function notification_settings()
    {
    	$this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
        /* Check Validation For Field Require */
        if ($this->form_validation->run() === FALSE) 
        {
            $response['code'] = 0;
            $response['status'] = "error";
            $response['message'] = 'Please enter all fields';
            echo json_encode($response);
        } 
        else 
        {
        	$recieve_gift = $this->input->post('recieve_gift');
        	$send_gift = $this->input->post('send_gift');
        	$contacts_joined = $this->input->post('contacts_joined');
        	$admin_giftcast_settings = $this->input->post('admin_giftcast_settings');
        	$gift_opened = $this->input->post('gift_opened');
			$user_id = $this->input->post('user_id');

			if(isset($recieve_gift))
			{
				$set['recieve_gift'] = $recieve_gift;
			}
			if(isset($send_gift))
			{
				$set['send_gift'] = $send_gift;
			}
			if(isset($contacts_joined))
			{
				$set['contacts_joined'] = $contacts_joined;
			}
			if(isset($admin_giftcast_settings))
			{
				$set['admin_giftcast_settings'] = $admin_giftcast_settings;
			}
			if(isset($gift_opened))
			{
				$set['gift_opened'] = $gift_opened;
			}
			$set['updated_at'] = date("Y-m-d H:i:s");
			
            $where = array('user_id' => $user_id);
            $notification_data = $this->Notification_model->getAnyData($where);
            if(!empty($notification_data))
            {
            	$notification = $this->Notification_model->update($set, $where);
	            if(!empty($notification))
	            {
	            	$where = array('user_id' => $user_id);
            		$notification_updated_data = $this->Notification_model->getAnyData($where);
            		if(!empty($notification_updated_data))
            		{
		            	$response['code'] = 1;
			            $response['notification_data'] = $notification_updated_data;
			            $response['status'] = "success";
			            $response['message'] = 'Notifications settings changed successfully';
            		}
	            }
	            else
	            {
	            	$response['code'] = 0;
		            $response['status'] = "error";
		            $response['message'] = 'Issue while updating the notifications';
	            }
            }
            else
            {
            	$response['code'] = 0;
	            $response['status'] = "error";
	            $response['message'] = 'No such user id exists';
            }
            echo json_encode($response);
        }
    }
}
?>