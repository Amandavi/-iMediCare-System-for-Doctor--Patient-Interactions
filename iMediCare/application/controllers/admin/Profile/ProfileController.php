<?php 

class ProfileController extends CI_Controller {

    function __construct() {
		
        parent::__construct();
		
        error_reporting(E_ERROR | E_PARSE);
        $this->load->helper('url');
		
        $this->load->library('form_validation');
        $this->load->model('admin/CommonModel');
        $this->load->model('admin/Profile/ProfileModel');
        $this->load->model('admin/Administration/AdministrationModel');
		
    }

    public function index() {
		
		if ($this->session->userdata('user_id') != '' ) {
			
			$data = $this->CommonModel->get_commenData();

			if($data['user_type'] == 'user'){
				self::editUser();
			}

			if($data['user_type'] == 'patient'){
				self::editPatient();
			}

			if($data['user_type'] == 'doctor'){
				self::editDoctor();
			}
			
		} else {
			
            redirect('login', 'refresh');
			
        }
		
    }
	
	public function profileSubmit() {
		
		if ($this->session->userdata('user_id') != '' ) {
			
			$data = $this->CommonModel->get_commenData();

			if($data['user_type'] == 'user'){
				self::saveUser();
			}

			if($data['user_type'] == 'patient'){
				self::savePatient();
			}

			if($data['user_type'] == 'doctor'){
				self::saveDoctor();
			}
			
		} else {
			
            redirect('login', 'refresh');
			
        }

	}

    public function editDoctor() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'profile';
			
			$id = $this->CommonModel->get_doctor_id($data['user_id']);
			
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
				
				$data['image_path'] = $value->doc_image;
				$data['is_new_image'] = 0;
				
				
			}
			
			$data['specialty'] = $this->CommonModel->get_specialty('');
			
			$this->load->view('admin/Profile/Profile',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function saveDoctor() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'profile'; 
			
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
			
			$data['image_path'] = $this->input->post('image_path');	
			$data['is_new_image'] = $this->input->post('is_new_image');
			
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
			
			if ($this->form_validation->run() == FALSE) {

				$this->load->view('admin/Profile/Profile',$data);
				return;

			}
			
			$user_data = array(
				'name' => $data['name'],
				'email' => $data['email'],
				'phone_no' => $data['phone_no'],
				'user_type' => 'doctor',
				'created_by' => 0,
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

			
			$id = $this->CommonModel->get_doctor_id($data['user_id']);
			
			$this->AdministrationModel->updateDoctor($user_data,$save_data,$id);
			
			
			$data_save = array(
				'image_path' => $data['image_path'],
				'is_new_image' => $data['is_new_image'],
			);

			$image_path = $this->ProfileModel->updateProfileImage($data_save,$data['user_id']);
			
			$this->session->set_userdata('user_image', $image_path);			
			$this->session->set_userdata('user_name', $data['name']);
			
			
			redirect('profile', 'refresh');
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function savePatient() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'profile'; 
			
			
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['phone_no'] = $this->input->post('phone_no');
			$data['title'] = $this->input->post('title');
			$data['gender'] = $this->input->post('gender');
			$data['nic'] = $this->input->post('nic');
			$data['address'] = $this->input->post('address');
			$data['birth_day'] = $this->input->post('birth_day');
			$data['image_path'] = $this->input->post('image_path');	
			$data['is_new_image'] = $this->input->post('is_new_image');
			
			$this->form_validation->set_rules('name', 'name', 'trim|required');
			$this->form_validation->set_rules('phone_no', 'phone no', 'trim|required|numeric');
			$this->form_validation->set_rules('address', 'address', 'trim|required');
			$this->form_validation->set_rules('nic', 'nic', 'trim|required');
			$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_emailCheck');
			
			if ($this->form_validation->run() == FALSE) {

				$this->load->view('admin/Profile/Profile',$data);
				return;

			}
			
			$user_data = array(
				'name' => $data['name'],
				'email' => $data['email'],
				'phone_no' => $data['phone_no'],
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
			
			$patient_id = $this->CommonModel->get_patient_id($data['user_id']);

			$this->AdministrationModel->updatePatient($user_data,$save_data,$patient_id);
			
			$data_save = array(
				'image_path' => $data['image_path'],
				'is_new_image' => $data['is_new_image'],
			);

			$image_path = $this->ProfileModel->updateProfileImage($data_save,$data['user_id']);
			
			$this->session->set_userdata('user_image', $image_path);			
			$this->session->set_userdata('user_name', $data['name']);
			
			
			redirect('profile', 'refresh');
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function editPatient() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'profile'; 
			
			$id = $this->CommonModel->get_patient_id($data['user_id']);
			
			$_details = $this->AdministrationModel->get_patient_details($id);
			foreach ($_details->result() as $value) {
				
				$data['name'] = $value->patient_name;
				$data['email'] = $value->patient_email;
				$data['phone_no'] = $value->patient_phone;
				$data['title'] = $value->title;
				$data['gender'] = $value->gender;
				$data['nic'] = $value->nic;
				$data['address'] = $value->address;
				$data['birth_day'] = $value->birth_day;
				
				$data['image_path'] = $value->patient_image;
				$data['is_new_image'] = 0;
				
				
			}
			
			$this->load->view('admin/Profile/Profile',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function saveUser() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'profile'; 
			
			$data['_id'] = $this->input->post('_id');
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['phone_no'] = $this->input->post('phone_no');
			$data['image_path'] = $this->input->post('image_path');	
			$data['is_new_image'] = $this->input->post('is_new_image');
			
			$this->form_validation->set_rules('name', 'name', 'trim|required');
			$this->form_validation->set_rules('phone_no', 'phone no', 'trim|required|numeric');
			$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_emailCheck');
			
			if ($this->form_validation->run() == FALSE) {

				$this->load->view('admin/Profile/Profile',$data);
				return;

			}
			
			$save_data = array(
				'name' => $data['name'],
				'email' => $data['email'],
				'phone_no' => $data['phone_no'],
				'user_type' => 'user',
				'created_by' => 0,
				'image_path' => 'Images/user.jpg',
				'created_on' => date('Y-m-d H:i:s')
			);
			
			$this->AdministrationModel->updateUser($save_data,$data['user_id']);
			
			$data_save = array(
				'image_path' => $data['image_path'],
				'is_new_image' => $data['is_new_image'],
			);

			$image_path = $this->ProfileModel->updateProfileImage($data_save,$data['user_id']);
			
			$this->session->set_userdata('user_image', $image_path);			
			$this->session->set_userdata('user_name', $data['name']);

			
			
			redirect('profile', 'refresh');
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function editUser() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'profile'; 
			
			$_details = $this->AdministrationModel->get_user_details($data['user_id']);
			foreach ($_details->result() as $value) {
				
				$data['name'] = $value->name;
				$data['email'] = $value->email;
				$data['phone_no'] = $value->phone_no;
				
				$data['image_path'] = $value->image_path;
				$data['is_new_image'] = 0;
				
			}
			
			$this->load->view('admin/Profile/Profile',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }
	
	//validations
    public function emailCheck($value) {
		
		$user_id = $this->session->userdata('user_id');
		
		$this->db->where('email', $value);
		$this->db->where_not_in('user_id', $user_id);
        $query = $this->db->get('tbl_users');
		
        if ($query->num_rows() == 0) {
            return TRUE;
        } else {
			$this->form_validation->set_message('emailCheck', 'This email have an other account.');
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
