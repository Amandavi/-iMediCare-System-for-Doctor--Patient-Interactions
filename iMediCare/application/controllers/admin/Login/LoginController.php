<?php

class LoginController extends CI_Controller {

    function __construct() {
        parent::__construct();
		
        error_reporting(E_ERROR | E_PARSE);
        $this->load->helper('url');
		
        $this->load->library('form_validation');
		
        $this->load->model('admin/Login/LoginModel');
        $this->load->model('admin/CommonModel');
		
    }

    public function index() {
		
		$data = $this->CommonModel->get_commenData();
		$data['page'] = 'login'; 
		
		$data['email'] = '';
		$data['password'] = '';
		
        $this->load->view('admin/Login/Login',$data);
		
    }
	
	public function loginSubmit() {
		
		$data = $this->CommonModel->get_commenData();
		$data['page'] = 'login'; 
		
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		
		$data['email'] = $email;
		$data['password'] = $password;
		
		$user_id = -1;
		
		$this->form_validation->set_rules('email', 'email', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');
		
		if(strlen(trim($email)) > 0 && strlen(trim($password)) > 0 ) {
			
			$login_details = $this->LoginModel->login($email);
			
			if (!empty($login_details)) {
				foreach ($login_details as $value) {

					if (hash_equals($value->password, crypt($password, $value->password))) {
						
						$user_id = $value->user_id;
						$user_name = $value->name;
						$user_type = $value->user_type;
						$user_image = $value->image_path;
						
					}
				}
			}
			
		}
		
		if($user_id == -1){
			$this->form_validation->set_rules('password', 'password', 'callback_nomatch');
		}
			
		
		if ($this->form_validation->run() == FALSE) {

			$data['password'] = '';
			$data['page_status'] = 'error';
			$this->load->view('admin/Login/Login', $data);
			return;
		}
		
		$data['page_status'] = 'login';
		
		$this->session->set_userdata('user_id', $user_id);
		$this->session->set_userdata('user_type', $user_type);
		$this->session->set_userdata('user_name', $user_name);
		$this->session->set_userdata('user_image', $user_image);
		
		if($user_type == 'user'){
			redirect('administration', 'refresh');
		} if($user_type == 'doctor'){
			redirect('administration', 'refresh');
		}else {
			redirect('appointment', 'refresh');
		}
		
		
	}
	
    public function register() {
		
		$data = $this->CommonModel->get_commenData();
		$data['page'] = 'register'; 
		
		$data['name'] = '';
		$data['phone_no'] = '';
		$data['email'] = '';
		$data['password'] = '';
		$data['confirmPassword'] = '';
		
        $this->load->view('admin/Login/Register',$data);
		
    }
	
	public function registerSubmit() {
		
		$data = $this->CommonModel->get_commenData();
		$data['page'] = 'register';
				
		$name = $this->input->post('name');		
		$phone_no = $this->input->post('phone_no');		
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$confirmPassword = $this->input->post('confirmPassword');
		
		$this->form_validation->set_rules('name', 'name', 'trim|required');
		$this->form_validation->set_rules('phone_no', 'phone no', 'trim|required|numeric');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_regEmailCheck');
		$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[3]|matches[confirmPassword]');
		$this->form_validation->set_rules('confirmPassword', 'confirm Password', 'trim|required');
		
		$data['name'] = $name;
		$data['phone_no'] = $phone_no;
		$data['email'] = $email;
		$data['password'] = $password;
		$data['confirmPassword'] = '';
		
		if ($this->form_validation->run() == FALSE) {

			$data['page_status'] = 'error';
			$this->load->view('admin/Login/Register', $data);
			return;
			
		}
		
		$cost = 10;
		$salt = strtr(base64_encode(random_bytes(16)), '+', '.');
		$salt = sprintf("$2a$%02d$", $cost) . $salt;
		$hash = crypt($password, $salt);

		$data_save = array(
			'name' => $name,
			'email' => $email,
			'phone_no' => $phone_no,
			'password' => $hash,
			'user_type' => 'patient',
			'created_by' => 0,
			'image_path' => 'Images/user.jpg',
			'created_on' => date('Y-m-d H:i:s')
		);
		
		$user_id = $this->LoginModel->saveUser($data_save);
		
		$data_ = array(
			'user_id' => $user_id
		);
		
		$patient_id = $this->LoginModel->savePatient($data_);
		
		$this->session->set_userdata('user_id', $user_id);
		$this->session->set_userdata('user_type', 'patient');
		$this->session->set_userdata('user_name', $name);
		$this->session->set_userdata('user_image', 'Images/user.jpg');
		
		
		
		//send email
		$emailBody_details = array(
			'name'=> $name,
			'email'=> $email
		);
		$_emailBody = $this->CommonModel->signUp_emailBody($emailBody_details);

		$details = array(
			'email'=> $email,
			'receiver_name'=> $name,
			'subject'=> 'Sign-up',
			'_emailBody'=> $_emailBody
		);

		$this->CommonModel->send_email($details);
		
		
		
		redirect('register-thank-you', 'refresh');
		
	}
	
	public function thankYou() {
		
		$data = $this->CommonModel->get_commenData();
		$data['page'] = 'register';
		
		$data['image'] = 'Images/thanks.png';
		$data['header'] = 'WELCOME';
		$data['msg1'] = 'Hi <strong>'.$data['user_name'].'</strong><br>Thank you for joining us.<br><br>';
		$data['msg2'] = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.';
		
		$this->load->view('admin/message',$data);
	}
	
    public function logOut() {
		
        $newdata = array(
            'user_id' => '',
            'password' => '',
            'user_type' => '',
            'user_name' => '',
            'user_image' => '',
            'logged_in' => FALSE,
        );

        $this->session->unset_userdata($newdata);
        $this->session->sess_destroy();

        redirect('login', 'refresh');
		
    }

	
	//validations
    public function nomatch() {
		
        $this->form_validation->set_message('nomatch', 'Username or password invalid.');
		return FALSE;
		
    }
	
    public function emailCheck($value) {
		
		$this->db->where('email', $value);
        $query = $this->db->get('tbl_users');
		
        if ($query->num_rows() == 0) {
            $this->form_validation->set_message('emailCheck', 'This email have not an account.');
            return FALSE;
        } else {
            return TRUE;
        }
		
    }
	
    public function regEmailCheck($value) {
		
		$this->db->where('email', $value);
        $query = $this->db->get('tbl_users');
		
        if ($query->num_rows() == 0) {
            return TRUE;
        } else {
			$this->form_validation->set_message('regEmailCheck', 'This email have an account.');
            return FALSE;
        }
		
    }
	
    public function conPassCheck($value) {
		
		$confirmPassword = $this->input->post('confirmPassword');
		
        if (trim($confirmPassword) != trim($value)) {
            $this->form_validation->set_message('conPassCheck', 'The password field does not match the confirm Password field.');
            return FALSE;
        } else {
            return TRUE;
        }
		
    }
	
    public function verifiCode_check($value) {
		
		$this->db->where('email', $value);
        $query = $this->db->get('tbl_users');
		
        if ($query->num_rows() == 0) {
            $this->form_validation->set_message('verifiCode_check', 'This email have not an account.');
            return FALSE;
        } else {
            return TRUE;
        }
		
    }

}
