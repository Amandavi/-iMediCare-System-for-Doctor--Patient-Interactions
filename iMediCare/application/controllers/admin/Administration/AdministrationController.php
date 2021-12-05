<?php 

class AdministrationController extends CI_Controller {

    function __construct() {
		
        parent::__construct();
		
        error_reporting(E_ERROR | E_PARSE);
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('admin/CommonModel');
        $this->load->model('admin/Administration/AdministrationModel');
		
    }

    public function index() { }

	//doctors
    public function doctorList() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'doctors';
			
			$data['list'] = $this->AdministrationModel->get_doctors($data);
			
			$this->load->view('admin/Administration/Doctors/List',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function newDoctor() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'doctors';
			
			$data['_id'] = 0;
			$data['name'] = '';
			$data['email'] = '';
			$data['phone_no'] = '';
			$data['specialty_id'] = '0';
			$data['title'] = 'Mr.';
			$data['gender'] = 'Male';
			$data['nic'] = '';
			$data['address'] = '';
			$data['channelling_fee'] = '';
			$data['password'] = '';
			$data['confirmPassword'] = '';
			
			$data['chkMonday'] = 0;
			$data['avlMonday'] = '';
			$data['chkTuesday'] = 0;
			$data['avlTuesday'] = '';
			$data['chkWednesday'] = 0;
			$data['avlWednesday'] = '';
			$data['chkThursday'] = 0;
			$data['avlThursday'] = '';
			$data['chkFriday'] = 0;
			$data['avlFriday'] = '';
			$data['chkSaturday'] = 0;
			$data['avlSaturday'] = '';
			$data['chkSunday'] = 0;
			$data['avlSunday'] = '';
			
			
			$data['specialty'] = $this->CommonModel->get_specialty('');
			
			$this->load->view('admin/Administration/Doctors/Form',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function saveDoctor() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'doctors';
			
			$data['_id'] = $this->input->post('_id');
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['phone_no'] = $this->input->post('phone_no');
			$data['specialty_id'] = $this->input->post('specialty_id');
			$data['title'] = $this->input->post('title');
			$data['gender'] = $this->input->post('gender');
			$data['nic'] = $this->input->post('nic');
			$data['address'] = $this->input->post('address');
			$data['channelling_fee'] = $this->input->post('fee');
			$data['password'] = $this->input->post('password');
			$data['confirmPassword'] = $this->input->post('confirmPassword');
			
			$data['chkMonday'] = $this->input->post('chkMonday');
			$data['avlMonday'] = $this->input->post('avlMonday');
			$data['chkTuesday'] = $this->input->post('chkTuesday');
			$data['avlTuesday'] = $this->input->post('avlTuesday');
			$data['chkWednesday'] = $this->input->post('chkWednesday');
			$data['avlWednesday'] = $this->input->post('avlWednesday');
			$data['chkThursday'] = $this->input->post('chkThursday');
			$data['avlThursday'] = $this->input->post('avlThursday');
			$data['chkFriday'] = $this->input->post('chkFriday');
			$data['avlFriday'] = $this->input->post('avlFriday');
			$data['chkSaturday'] = $this->input->post('chkSaturday');
			$data['avlSaturday'] = $this->input->post('avlSaturday');
			$data['chkSunday'] = $this->input->post('chkSunday');
			$data['avlSunday'] = $this->input->post('avlSunday');
			
			$data['specialty'] = $this->CommonModel->get_specialty('');
			
			$this->form_validation->set_rules('name', 'name', 'trim|required');
			$this->form_validation->set_rules('phone_no', 'phone no', 'trim|required|numeric');
			$this->form_validation->set_rules('address', 'address', 'trim|required');
			$this->form_validation->set_rules('nic', 'nic', 'trim|required');
			$this->form_validation->set_rules('fee', 'channelling fee', 'trim|required|numeric');
			$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_emailCheck');
			$this->form_validation->set_rules('specialty_id', 'specialty', 'trim|callback_specialtyCheck');
			
			if($data['chkMonday'] == 1){
				$this->form_validation->set_rules('avlMonday', 'time', 'trim|required');
			}
			if($data['chkTuesday'] == 1){
				$this->form_validation->set_rules('avlTuesday', 'time', 'trim|required');
			}
			if($data['chkWednesday'] == 1){
				$this->form_validation->set_rules('avlWednesday', 'time', 'trim|required');
			}
			if($data['chkThursday'] == 1){
				$this->form_validation->set_rules('avlThursday', 'time', 'trim|required');
			}
			if($data['chkFriday'] == 1){
				$this->form_validation->set_rules('avlFriday', 'time', 'trim|required');
			}
			if($data['chkSaturday'] == 1){
				$this->form_validation->set_rules('avlSaturday', 'time', 'trim|required');
			}
			if($data['chkSunday'] == 1){
				$this->form_validation->set_rules('avlSunday', 'time', 'trim|required');
			}
			
			
			
			
			if($data['_id'] == 0){
				$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[3]|matches[confirmPassword]');
				$this->form_validation->set_rules('confirmPassword', 'confirm Password', 'trim|required');
			}
		
			if ($this->form_validation->run() == FALSE) {

				$this->load->view('admin/Administration/Doctors/Form',$data);
				return;

			}
			
			$cost = 10;
			$salt = strtr(base64_encode(random_bytes(16)), '+', '.');
			$salt = sprintf("$2a$%02d$", $cost) . $salt;
			$hash = crypt($data['password'], $salt);
			
			$user_data = array(
				'name' => $data['name'],
				'email' => $data['email'],
				'phone_no' => $data['phone_no'],
				'password' => $hash,
				'user_type' => 'doctor',
				'created_by' => 0,
				'image_path' => 'Images/user.jpg',
				'created_on' => date('Y-m-d H:i:s')
			);
			
			$save_data = array(
				'specialty' => $data['specialty_id'],
				'channelling_fee' => $data['channelling_fee'],
				'title' => $data['title'],
				'gender' => $data['gender'],
				'nic' => $data['nic'],
				'address' => $data['address'],
				
				'chkMonday' => $data['chkMonday'] == 1 ? 1 : 0,
				'avlMonday' => $data['chkMonday'] == 1 ? $data['avlMonday'] : '',
				
				'chkTuesday' => $data['chkTuesday'] == 1 ? 1 : 0,
				'avlTuesday' => $data['chkTuesday'] == 1 ? $data['avlTuesday'] : '',
				
				'chkWednesday' => $data['chkWednesday'] == 1 ? 1 : 0,
				'avlWednesday' => $data['chkWednesday'] == 1 ? $data['avlWednesday'] : '',
				
				'chkThursday' => $data['chkThursday'] == 1 ? 1 : 0,
				'avlThursday' => $data['chkThursday'] == 1 ? $data['avlThursday'] : '',
				
				'chkFriday' => $data['chkFriday'] == 1 ? 1 : 0,
				'avlFriday' => $data['chkFriday'] == 1 ? $data['avlFriday'] : '',
				
				'chkSaturday' => $data['chkSaturday'] == 1 ? 1 : 0,
				'avlSaturday' => $data['chkSaturday'] == 1 ? $data['avlSaturday'] : '',
				
				'chkSunday' => $data['chkSunday'] == 1 ? 1 : 0,
				'avlSunday' => $data['chkSunday'] == 1 ? $data['avlSunday'] : ''
				
			);   

			if($data['_id'] > 0){
				$this->AdministrationModel->updateDoctor($user_data,$save_data,$data['_id']);
			} else {
				
				$this->AdministrationModel->saveDoctor($user_data,$save_data);
				
				//send email
				$emailBody_details = array(
					'name'=> $data['name'],
					'email'=> $data['email'],
					'msg'=> 'This is your password. '.$data['password']
				);
				$_emailBody = $this->CommonModel->userRegister_emailBody($emailBody_details);

				$details = array(
					'email'=> $data['email'],
					'receiver_name'=> $data['name'],
					'subject'=> 'Register',
					'_emailBody'=> $_emailBody
				);

				$this->CommonModel->send_email($details);
				
			}
			
			
			redirect('doctors', 'refresh');
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function editDoctor() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'doctors';
			
			$id = base64_decode(urldecode($this->input->post('selected_id')));
			
			$_details = $this->AdministrationModel->get_doctor_details($id);
			foreach ($_details->result() as $value) {
				
				$data['_id'] = $id;
				$data['name'] = $value->doc_name;
				$data['email'] = $value->doc_email;
				$data['phone_no'] = $value->doc_phone;
				$data['specialty_id'] = $value->specialty;
				$data['title'] = $value->title;
				$data['gender'] = $value->gender;
				$data['nic'] = $value->nic;
				$data['address'] = $value->address;
				$data['channelling_fee'] = $value->channelling_fee;
				$data['password'] = '';
				$data['confirmPassword'] = '';
				
				$data['chkMonday'] = $value->chkMonday;
				$data['avlMonday'] = $value->avlMonday;
				
				$data['chkTuesday'] = $value->chkTuesday;
				$data['avlTuesday'] = $value->avlTuesday;
				
				$data['chkWednesday'] = $value->chkWednesday;
				$data['avlWednesday'] = $value->avlWednesday;
				
				$data['chkThursday'] = $value->chkThursday;
				$data['avlThursday'] = $value->avlThursday;
				
				$data['chkFriday'] = $value->chkFriday;
				$data['avlFriday'] = $value->avlFriday;
				
				$data['chkSaturday'] = $value->chkSaturday;
				$data['avlSaturday'] = $value->avlSaturday;
				
				$data['chkSunday'] = $value->chkSunday;
				$data['avlSunday'] = $value->avlSunday;
				
				
			}
			
			$data['specialty'] = $this->CommonModel->get_specialty('');
			
			$this->load->view('admin/Administration/Doctors/Form',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

	//patient
    public function patientList() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'patient';
			
			$data['list'] = $this->AdministrationModel->get_patient($data);
			
			$this->load->view('admin/Administration/Patient/List',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function newPatient() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'patient';
			
			$data['_id'] = 0;
			$data['name'] = '';
			$data['email'] = '';
			$data['phone_no'] = '';
			$data['title'] = 'Mr.';
			$data['gender'] = 'Male';
			$data['nic'] = '';
			$data['address'] = '';
			$data['birth_day'] = '1900-01-01';
			$data['password'] = '';
			$data['confirmPassword'] = '';
			
			$this->load->view('admin/Administration/Patient/Form',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function savePatient() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'patient';
			
			$data['_id'] = $this->input->post('_id');
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['phone_no'] = $this->input->post('phone_no');
			$data['title'] = $this->input->post('title');
			$data['gender'] = $this->input->post('gender');
			$data['nic'] = $this->input->post('nic');
			$data['address'] = $this->input->post('address');
			$data['birth_day'] = $this->input->post('birth_day');
			$data['password'] = $this->input->post('password');
			$data['confirmPassword'] = $this->input->post('confirmPassword');
			
			$this->form_validation->set_rules('name', 'name', 'trim|required');
			$this->form_validation->set_rules('phone_no', 'phone no', 'trim|required|numeric');
			$this->form_validation->set_rules('address', 'address', 'trim|required');
			$this->form_validation->set_rules('nic', 'nic', 'trim|required');
			$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_patientEmailCheck');
			
			if($data['_id'] == 0){
				$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[3]|matches[confirmPassword]');
				$this->form_validation->set_rules('confirmPassword', 'confirm Password', 'trim|required');
			}
		
			if ($this->form_validation->run() == FALSE) {

				$this->load->view('admin/Administration/Patient/Form',$data);
				return;

			}
			
			$cost = 10;
			$salt = strtr(base64_encode(random_bytes(16)), '+', '.');
			$salt = sprintf("$2a$%02d$", $cost) . $salt;
			$hash = crypt($data['password'], $salt);
			
			$user_data = array(
				'name' => $data['name'],
				'email' => $data['email'],
				'phone_no' => $data['phone_no'],
				'password' => $hash,
				'user_type' => 'patient',
				'created_by' => 0,
				'image_path' => 'Images/user.jpg',
				'created_on' => date('Y-m-d H:i:s')
			);
			
			$save_data = array(
				'birth_day' => $data['birth_day'],
				'title' => $data['title'],
				'gender' => $data['gender'],
				'nic' => $data['nic'],
				'address' => $data['address'],
				
			);   

			if($data['_id'] > 0){
				$this->AdministrationModel->updatePatient($user_data,$save_data,$data['_id']);
			} else {
				
				$this->AdministrationModel->savePatient($user_data,$save_data);
				
				//send email
				$emailBody_details = array(
					'name'=> $data['name'],
					'email'=> $data['email'],
					'msg'=> 'This is your password. '.$data['password']
				);
				$_emailBody = $this->CommonModel->userRegister_emailBody($emailBody_details);

				$details = array(
					'email'=> $data['email'],
					'receiver_name'=> $data['name'],
					'subject'=> 'Register',
					'_emailBody'=> $_emailBody
				);

				$this->CommonModel->send_email($details);
			}
			
			
			redirect('patients', 'refresh');
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function editPatient() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'patient';
			
			$id = base64_decode(urldecode($this->input->post('selected_id')));
			
			$_details = $this->AdministrationModel->get_patient_details($id);
			foreach ($_details->result() as $value) {
				
				$data['_id'] = $id;
				$data['name'] = $value->patient_name;
				$data['email'] = $value->patient_email;
				$data['phone_no'] = $value->patient_phone;
				$data['title'] = $value->title;
				$data['gender'] = $value->gender;
				$data['nic'] = $value->nic;
				$data['address'] = $value->address;
				$data['birth_day'] = $value->birth_day;
				$data['password'] = '';
				$data['confirmPassword'] = '';
				
			}
			
			$this->load->view('admin/Administration/Patient/Form',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

	//specialty
    public function specialtyList() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'specialty';
			
			$data['list'] = $this->AdministrationModel->get_specialty($data);
			
			$this->load->view('admin/Administration/Specialty/List',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function newSpecialty() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'specialty';
			
			$data['_id'] = 0;
			$data['specialty'] = '';
			
			
			$this->load->view('admin/Administration/Specialty/Form',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function saveSpecialty() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'specialty';
			
			$data['_id'] = $this->input->post('_id');
			$data['specialty'] = $this->input->post('specialty');
			
			$this->form_validation->set_rules('specialty', 'specialty', 'trim|required');
			
			
			if ($this->form_validation->run() == FALSE) {

				$this->load->view('admin/Administration/Specialty/Form',$data);
				return;

			}
			
			$save_data = array(
				'specialty' => $data['specialty'],
				
			);   

			if($data['_id'] > 0){
				$this->AdministrationModel->updateSpecialty($save_data,$data['_id']);
			} else {
				$this->AdministrationModel->saveSpecialty($save_data);
			}
			
			
			redirect('specialty', 'refresh');
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function editSpecialty() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'specialty';
			
			$id = base64_decode(urldecode($this->input->post('selected_id')));
			
			$_details = $this->AdministrationModel->get_specialty_details($id);
			foreach ($_details->result() as $value) {
				
				$data['_id'] = $id;
				$data['specialty'] = $value->specialty;
				
				
			}
			
			$this->load->view('admin/Administration/Specialty/Form',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

	//Users
    public function userList() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'users';
			
			$data['list'] = $this->AdministrationModel->get_users($data);
			
			$this->load->view('admin/Administration/Users/List',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function newUser() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'users';
			
			$data['_id'] = 0;
			$data['name'] = '';
			$data['email'] = '';
			$data['phone_no'] = '';
			$data['password'] = '';
			$data['confirmPassword'] = '';
			
			$data['specialty'] = $this->CommonModel->get_specialty('');
			
			$this->load->view('admin/Administration/Users/Form',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function saveUser() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'users';
			
			$data['_id'] = $this->input->post('_id');
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['phone_no'] = $this->input->post('phone_no');
			$data['password'] = $this->input->post('password');
			$data['confirmPassword'] = $this->input->post('confirmPassword');
			
			$this->form_validation->set_rules('name', 'name', 'trim|required');
			$this->form_validation->set_rules('phone_no', 'phone no', 'trim|required|numeric');
			$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_userEmailCheck');
			
			if($data['_id'] == 0){
				$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[3]|matches[confirmPassword]');
				$this->form_validation->set_rules('confirmPassword', 'confirm Password', 'trim|required');
			}
			
			if ($this->form_validation->run() == FALSE) {

				$this->load->view('admin/Administration/Users/Form',$data);
				return;

			}
			
			$cost = 10;
			$salt = strtr(base64_encode(random_bytes(16)), '+', '.');
			$salt = sprintf("$2a$%02d$", $cost) . $salt;
			$hash = crypt($data['password'], $salt);
			
			$save_data = array(
				'name' => $data['name'],
				'email' => $data['email'],
				'phone_no' => $data['phone_no'],
				'password' => $hash,
				'user_type' => 'user',
				'created_by' => 0,
				'image_path' => 'Images/user.jpg',
				'created_on' => date('Y-m-d H:i:s')
			);
			
			if($data['_id'] > 0){
				$this->AdministrationModel->updateUser($save_data,$data['_id']);
			} else {
				$this->AdministrationModel->saveUser($save_data);
			}
			
			
			redirect('user', 'refresh');
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function editUser() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'users';
			
			$id = base64_decode(urldecode($this->input->post('selected_id')));
			
			$_details = $this->AdministrationModel->get_user_details($id);
			foreach ($_details->result() as $value) {
				
				$data['_id'] = $id;
				$data['name'] = $value->name;
				$data['email'] = $value->email;
				$data['phone_no'] = $value->phone_no;
				
				$data['password'] = '';
				$data['confirmPassword'] = '';
				
			}
			
			$this->load->view('admin/Administration/Users/Form',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }
	
	//Medical Report
    public function newMedicalReport() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'medical_reports';
			
			$data['_id'] = 0;
			$data['patient'] = '';
			$data['patient_id'] = 0;
			$data['report_type'] = '';
			$data['note'] = '';
			$data['doc_path'] = '';
			$data['is_new_doc'] = 0;
			
			$this->load->view('admin/Dashboard/MedicalReportAdd',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function saveMedicalReport() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'medical_reports';
			
			$data['_id'] = $this->input->post('_id');
			$data['patient'] = $this->input->post('patient');
			$data['patient_id'] = $this->input->post('patient_id');
			$data['report_type'] = $this->input->post('report_type');
			$data['note'] = $this->input->post('note');
			$data['doc_path'] = $this->input->post('doc_path');
			$data['is_new_doc'] = $this->input->post('is_new_doc');
			
			$this->form_validation->set_rules('patient', 'patient', 'trim|required');
			$this->form_validation->set_rules('report_type', 'report type', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {

				$this->load->view('admin/Dashboard/MedicalReportAdd',$data);
				return;

			}
			
			$save_data = array(
				'patient_id' => $data['patient_id'],
				'report' => $data['report_type'],
				'note' => $data['note'],
				'created_by' => $data['user_id'],
				'created_on' => date('Y-m-d H:i:s')
			);
			
			if($data['_id'] > 0){
				$this->AdministrationModel->updateMedicalReport($save_data,$data['_id']);
			} else {
				$data['_id'] = $this->AdministrationModel->saveMedicalReport($save_data);
			}
			
			if($data['is_new_doc'] == 1){
				
				self::documentUpload(0, '_modalDocuments', 'patient', $data['_id'],$data['patient_id'],$data['report_type']);
				
			} else {
				redirect('medical-reports', 'refresh');
			}
			
			
			
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function editMedicalReport() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'medical_reports';
			
			$id = base64_decode(urldecode($this->input->post('selected_id')));
			
			$_details = $this->AdministrationModel->get_report_details($id);
			foreach ($_details->result() as $value) {
				
				$data['_id'] = $id;
				$data['patient_id'] = $value->patient_id;
				$data['patient'] = $value->patient;
				$data['report_type'] = $value->report;
				$data['note'] = $value->note;
				$data['doc_path'] = $value->doc_path;
				$data['is_new_doc'] = 0;
				
				
			}
			
			$this->load->view('admin/Dashboard/MedicalReportAdd',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }
	
    public function documentUpload($_id, $uploaderName, $docType, $_docId,$patient_id,$report_type) {

        $is_selectedDocument = 0;

        $extention = pathinfo($_FILES[$uploaderName]['name'], PATHINFO_EXTENSION);
        $is_selectedDocument = strlen($_FILES[$uploaderName]['name']);

        if ($is_selectedDocument > 0) {

            $targetfolder = $this->CommonModel->targetDocumentPath($docType,$patient_id,'medical_reports');

            $targetfolder = $targetfolder . basename('medical_reports_'. $_id . '_' . $_docId . '.' . $extention);

            $file_type = $_FILES[$uploaderName]['type'];

            if (
                    $file_type == "application/pdf" || $file_type == "image/jpeg" || $file_type == "image/png" || $file_type == "application/msword" || $file_type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $file_type == "application/vnd.ms-powerpoint" || $file_type == "application/vnd.openxmlformats-officedocument.presentationml.presentation" || $file_type == "application/vnd.ms-excel" || $file_type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            ) {

                if (move_uploaded_file($_FILES[$uploaderName]['tmp_name'], $targetfolder)) {

                    $this->AdministrationModel->update_report_doc_path($_docId, $targetfolder);
					
					
					$email = $this->CommonModel->get_patient_email($patient_id);
					$name = $this->CommonModel->get_patient_name($patient_id);

					//send email
					$emailBody_details = array(
						'name'=> $name,
						'report'=> $report_type
					);
					$_emailBody = $this->CommonModel->medicalReport_emailBody($emailBody_details);

					$details = array(
						'email'=> $email,
						'receiver_name'=> $name,
						'subject'=> 'Medical report',
						'_emailBody'=> $_emailBody
					);

					$this->CommonModel->send_email($details);

                    redirect('medical-reports', 'refresh');
                } else {

                    redirect('medical-reports', 'refresh');
                }
            } else {

                redirect('medical-reports', 'refresh');
            }
        } else {
			redirect('medical-reports', 'refresh');
		}
		
    }
	
	//validations
    public function emailCheck($value) {
		
		$_id = $this->input->post('_id');
		$user_id = $this->CommonModel->get_doctor_user_id($_id);
		
		$this->db->where('email', $value);
		$this->db->where_not_in('user_id', $user_id);
        $query = $this->db->get('tbl_users');
		
        if ($query->num_rows() == 0) {
            return TRUE;
        } else {
			$this->form_validation->set_message('emailCheck', 'This email have an account.');
            return FALSE;
        }
		
    }
	
    public function userEmailCheck($value) {
		
		$_id = $this->input->post('_id');
		
		$this->db->where('email', $value);
		$this->db->where_not_in('user_id', $_id);
        $query = $this->db->get('tbl_users');
		
        if ($query->num_rows() == 0) {
            return TRUE;
        } else {
			$this->form_validation->set_message('emailCheck', 'This email have an account.');
            return FALSE;
        }
		
    }
	
    public function patientEmailCheck($value) {
		
		$_id = $this->input->post('_id');
		$user_id = $this->CommonModel->get_patient_user_id($_id);
		
		$this->db->where('email', $value);
		$this->db->where_not_in('user_id', $user_id);
        $query = $this->db->get('tbl_users');
		
        if ($query->num_rows() == 0) {
            return TRUE;
        } else {
			$this->form_validation->set_message('patientEmailCheck', 'This email have an account.');
            return FALSE;
        }
		
    }
	
    public function specialtyCheck($value) {
		
        if (trim($value) == '0') {
            $this->form_validation->set_message('specialtyCheck', 'Doctor specialty required.');
            return FALSE;
        } else {
            return TRUE;
        }
		
    }

}
