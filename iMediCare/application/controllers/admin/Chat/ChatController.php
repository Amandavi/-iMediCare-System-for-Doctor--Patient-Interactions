<?php 

class ChatController extends CI_Controller {

    function __construct() {
		
        parent::__construct();
		
        error_reporting(E_ERROR | E_PARSE);
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('admin/CommonModel');
        $this->load->model('admin/Chat/ChatModel');
        $this->load->model('admin/Appointment/AppointmentModel');
		
    }

    public function index() {
		
		if ($this->session->userdata('user_id') != '' ) {
			
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'chat'; 

			$this->load->view('admin/Chat/Chat',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

   
	
	
	
	
}
