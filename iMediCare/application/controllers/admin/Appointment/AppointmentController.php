<?php 

class AppointmentController extends CI_Controller {

    function __construct() {
		
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('admin/CommonModel');
        $this->load->model('admin/Appointment/AppointmentModel');
		
    }

    public function index() {
		
		$data = $this->CommonModel->get_commenData();
		$data['page'] = 'appointment'; 
		
		$name = '';
		$phone_no = '';
		$email = '';
		
		$_details = $this->AppointmentModel->get_patient_details($data['user_id']);
		foreach ($_details->result() as $value) {

			$name = $value->name;
			$phone_no = $value->phone_no;
			$email = $value->email;
		}
		
		$data['name'] = $name;
		$data['phone_no'] = $phone_no;
		$data['email'] = $email;
		$data['app_date'] = date('Y-m-d');
		$data['specialty_id'] = 0;
		$data['doctor_id'] = 0;
		$data['specialty'] = '';
		$data['doctor'] = '';
		$data['message'] = '';
		$data['app_time'] = '';
		$data['fee'] = '';
		
		if($data['user_type'] == 'user'){
			$data['patient_id'] = 0;
			$data['patient'] = '';
		}
		
		$this->load->view('admin/Appointment/Appointment',$data);
		
    }
	
	public function appointmentSubmit() {
		
		$data = $this->CommonModel->get_commenData();
		$data['page'] = 'appointment';
				
		$name = $this->input->post('name');		
		$phone_no = $this->input->post('phone_no');		
		$email = $this->input->post('email');	
		$app_date = $this->input->post('app_date');	
		$specialty_id = $this->input->post('specialty_id');	
		$doctor_id = $this->input->post('doctor_id');	
		$specialty = $this->input->post('specialty');	
		$doctor = $this->input->post('doctor');	
		$message = $this->input->post('message');
		$app_time = $this->input->post('app_time');
		$fee = $this->input->post('fee');
		
		$this->form_validation->set_rules('name', 'name', 'trim|required');
		$this->form_validation->set_rules('phone_no', 'phone no', 'trim|required|numeric');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
		$this->form_validation->set_rules('doctor', 'doctor', 'trim|required');
		
		if($data['user_type'] == 'user') {
			
			$this->form_validation->set_rules('patient', 'patient', 'trim|required');
			
			$data['patient_id'] = $this->input->post('patient_id');
			$data['patient'] = $this->input->post('patient');
			
			$user_id = $this->CommonModel->get_patient_user_id($data['patient_id']);
			
		} else {
			$user_id = $data['user_id'];
		}
		
		
		$data['name'] = $name;
		$data['phone_no'] = $phone_no;
		$data['email'] = $email;
		$data['app_date'] = $app_date;
		$data['specialty_id'] = $specialty_id;
		$data['doctor_id'] = $doctor_id;
		$data['specialty'] = $specialty;
		$data['doctor'] = $doctor;
		$data['message'] = $message;
		$data['app_time'] = $app_time;
		$data['fee'] = $fee;
		
		if ($this->form_validation->run() == FALSE) {

			$this->load->view('admin/Appointment/Appointment',$data);
			return;
			
		}
		
		$data_save = array(
			'name' => $name,
			'email' => $email,
			'phone_no' => $phone_no,
			'channel_date' => $app_date,
			'specialty' => $specialty_id,
			'doctor_id' => $doctor_id,
			'message' => $message,
			'channel_time' => $app_time,
			'fee' => $fee,
			'status' => 'New',
			'user_id' => $user_id,
			'created_by' => $data['user_id'],
			'created_on' => date('Y-m-d H:i:s')
		);
		
		$user_id = $this->AppointmentModel->saveAppointment($data_save);
		
		if($data['user_type'] == 'user') {
			redirect('appointment-list', 'refresh');
		} else {
			redirect('thank-you', 'refresh');
		}

	}
	
	public function thankYou() {
		
		$data = $this->CommonModel->get_commenData();
		$data['page'] = 'appointment';
		
		$data['image'] = 'Images/thanks.png';
		$data['header'] = 'THANK YOU!';
		$data['msg1'] = 'Your request is received and we will contact you soon.<br>Please check your email.';
		$data['msg2'] = '+94 770 127 136 / +94 112 252 252 <br>info@imedicare.com';
		
		$this->load->view('admin/message',$data);
	}
	
	
	
	
	public function specialty(){
		
		$data = $this->CommonModel->get_specialty($_REQUEST["q"]);
        echo json_encode($data->result_array());
		
	}
	
	public function doctors(){
		
		$data = $this->CommonModel->get_doctors($_REQUEST["q"],$_REQUEST["specialty"],$_REQUEST["channelling_date"]);
        echo json_encode($data->result_array());
		
	}
	
	public function patient(){
		
		$data = $this->CommonModel->get_patient($_REQUEST["q"]);
        echo json_encode($data->result_array());
		
	}
	

}
