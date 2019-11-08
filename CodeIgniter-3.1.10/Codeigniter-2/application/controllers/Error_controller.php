 <?php

class Error_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //$this->load->model('User_model');
    }
	function error_404() {
        $url = current_full_url();
        /*$data = array(
            'heading' => 'URL is Not Correct',
            'message' => $url . " Is not proper please check"
        );*/
        $data['body'] = "errors/error_404";
        $this->load->view('web/error_template.php', $data);
    }
    
    public function fallback_url()
    {
        $this->load->view('fallback_url.php');
    }
}
?>