<?php 

class DashboardController extends CI_Controller {

    function __construct() {
		
        parent::__construct();
		
        error_reporting(E_ERROR | E_PARSE);
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('admin/CommonModel');
        $this->load->model('admin/Dashboard/DashboardModel');
        $this->load->model('admin/Appointment/AppointmentModel');
		
    }

    public function index() {
		
		if ($this->session->userdata('user_id') != '' ) {
			
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'dashboard';

			$this->load->view('admin/Dashboard/Dashboard',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function dashboard() {
		
		if ($this->session->userdata('user_id') != '' ) {
			
			$data = $this->CommonModel->get_commenData();
			
			$current_date = $this->input->post('scr_date');
			$current_date = $current_date != null && $current_date != '' ? $current_date : date('Y-m-d');
			
			if($data['user_type'] == 'user') {
				self::userDashboard($current_date);
			}
			
			else if($data['user_type'] == 'doctor') {
				self::doctorDashboard($current_date);
			}
			
			else if($data['user_type'] == 'patient') {
				self::patientDashboard($current_date);
			}	else {
			
            	redirect('login', 'refresh');
			
        	}

			
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function userDashboard($current_date) {
		
		$data = $this->CommonModel->get_commenData();
		$data['page'] = 'administration'; 
		$data['act_menu'] = 'dashboard';
		
		$data['scr_date'] = $current_date;
		
		$data['doctor_count'] = $this->DashboardModel->get_user_count('doctor');
		$data['patient_count'] = $this->DashboardModel->get_user_count('patient');
		
		$data['dashboardDetails'] = $this->DashboardModel->get_doctors_appointments($current_date);
		
		$this->load->view('admin/Dashboard/Dashboard',$data);
	}

    public function doctorDashboard($current_date) {
		
		$data = $this->CommonModel->get_commenData();
		$data['page'] = 'administration'; 
		$data['act_menu'] = 'dashboard';
		
		$data['scr_date'] = $current_date;
		
		$data['dashboardDetails'] = $this->DashboardModel->get_doctors_appointments_summary($current_date);
		
		$this->load->view('admin/Dashboard/Dashboard',$data);
	}

    public function patientDashboard($current_date) {
		
		$data = $this->CommonModel->get_commenData();
		$data['page'] = 'administration'; 
		$data['act_menu'] = 'dashboard';
		
		$data['scr_date'] = $current_date;
		
		$data['dashboardDetails'] = $this->DashboardModel->get_user_appointments($current_date);
		
		$this->load->view('admin/Dashboard/Dashboard',$data);
	}

	
    public function appointmentsList() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'appointments_list';
			
			if($data['user_type'] == 'user' || $data['user_type'] == 'doctor'){
				
				$data['src_app_date'] = date('Y-m-d');
				$data['src_doctor_id'] = 0;
				$data['src_doctor'] = '';
				$data['user_data'] = '';
				$data['src_status'] = 'All';
				
			}

			$data['appointments'] = $this->AppointmentModel->get_appointments($data);
			
			$this->load->view('admin/Dashboard/AppointmentList',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function appointmentSearch() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'appointments_list';
			
			if($data['user_type'] == 'user' || $data['user_type'] == 'doctor'){
				
				$data['src_app_date'] = $this->input->post('src_app_date');
				$data['src_doctor_id'] = $this->input->post('src_doctor_id');
				$data['src_doctor'] = $this->input->post('src_doctor');
				$data['user_data'] = $this->input->post('user_data');
				$data['src_status'] = $this->input->post('src_status');
				
			}
			
			//print($data['doctor']);die;

			$data['appointments'] = $this->AppointmentModel->get_appointments($data);
			
			$this->load->view('admin/Dashboard/AppointmentList',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function appointmentNew() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'appointments_list';

			$name = '';
			$phone_no = '';
			$email = '';
			$status = 'New';
			$currentStatus = 'New';

			$_details = $this->AppointmentModel->get_patient_details($data['user_id']);
			foreach ($_details->result() as $value) {

				$name = $value->name;
				$phone_no = $value->phone_no;
				$email = $value->email;
			}

			$data['name'] = $name;
			$data['phone_no'] = $phone_no;
			$data['email'] = $email;
			$data['status'] = $status;
			$data['currentStatus'] = $currentStatus;
			$data['app_date'] = date('Y-m-d');
			$data['specialty_id'] = 0;
			$data['doctor_id'] = 0;
			$data['specialty'] = '';
			$data['doctor'] = '';
			$data['message'] = '';
			$data['app_time'] = '';
			$data['fee'] = '';
			$data['appointment_id'] = 0;
			
			if($data['user_type'] == 'user') {
				
				$data['patient_id'] = 0;
				$data['patient'] = '';
				
			}


			$this->load->view('admin/Dashboard/Appointment',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }
	
	public function appointmentSubmit() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'appointments_list';

			$name = $this->input->post('name');		
			$phone_no = $this->input->post('phone_no');		
			$email = $this->input->post('email');			
			$status = $this->input->post('status');			
			$currentStatus = $this->input->post('currentStatus');	
			$app_date = $this->input->post('app_date');	
			$specialty_id = $this->input->post('specialty_id');	
			$doctor_id = $this->input->post('doctor_id');	
			$specialty = $this->input->post('specialty');	
			$doctor = $this->input->post('doctor');	
			$message = $this->input->post('message');
			$app_time = $this->input->post('app_time');
			$fee = $this->input->post('fee');
			$appointment_id = $this->input->post('appointment_id');

			$this->form_validation->set_rules('name', 'name', 'trim|required');
			$this->form_validation->set_rules('phone_no', 'phone no', 'trim|required|numeric');
			$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
			$this->form_validation->set_rules('doctor', 'doctor', 'trim|required');
			
			
			if($data['user_type'] == 'user') {
				
				$data['patient_id'] = $this->input->post('patient_id');
				$data['patient'] = $this->input->post('patient');
				
				$user_id = $this->CommonModel->get_patient_user_id($data['patient_id']);
				
			} else {
				$user_id = $data['user_id'];
			}

			
			$data['name'] = $name;
			$data['phone_no'] = $phone_no;
			$data['email'] = $email;
			$data['status'] = $status;
			$data['currentStatus'] = $currentStatus;
			$data['app_date'] = $app_date;
			$data['specialty_id'] = $specialty_id;
			$data['doctor_id'] = $doctor_id;
			$data['specialty'] = $specialty;
			$data['doctor'] = $doctor;
			$data['message'] = $message;
			$data['app_time'] = $app_time;
			$data['fee'] = $fee;
			$data['appointment_id'] = $appointment_id;

			
			if ($this->form_validation->run() == FALSE) {

				$this->load->view('admin/Dashboard/Appointment',$data);
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
				'status' => $status == 'Confirmed' && $currentStatus == 'New' ? 'Confirmed' : $currentStatus,
				'user_id' => $user_id,
				'created_by' => $data['user_id'],
				'created_on' => date('Y-m-d H:i:s')
			);

			if($appointment_id > 0){
				$this->AppointmentModel->updateAppointment($data_save,$appointment_id);
			}else {
				$appointment_id = $this->AppointmentModel->saveAppointment($data_save);
			}
			
			
			if($status != 'Confirmed' && $currentStatus == 'Confirmed'){
				$token = $this->AppointmentModel->tokenIssue($data_save,$appointment_id);
				
				//send email
				$emailBody_details = array(
					'name'=> $name,
					'doctor'=> $doctor,
					'in_time'=> $app_time,
					'token'=> $token
				);
				$_emailBody = $this->CommonModel->token_emailBody($emailBody_details);

				$details = array(
					'email'=> $email,
					'receiver_name'=> $name,
					'subject'=> 'Token',
					'_emailBody'=> $_emailBody
				);

				$this->CommonModel->send_email($details);
				
			}
			
			redirect('appointment-list', 'refresh');
			
        } else {
			
            redirect('login', 'refresh');
			
        }

	}

    public function appointmentLoad() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'appointments_list';
			
			$id = base64_decode(urldecode($this->input->post('selected_id')));

			$_details = $this->AppointmentModel->get_appointment_details($id);
			foreach ($_details->result() as $value) {

				$name = $value->name;
				$phone_no = $value->phone_no;
				$email = $value->email;
				$status = $value->status;
				$app_date = $value->channel_date;
				$specialty_id = $value->specialty;
				$doctor_id = $value->doctor_id;
				$specialty = $value->specialty;
				$doctor = $value->doctor_name;
				$message = $value->message;
				$app_time = $value->channel_time;
				$fee = $value->fee;
				
				$patient_id = $value->patient_id;
				$patient_name = $value->patient_name;
				
			}
			
			
			if($data['user_type'] == 'user') {
				
				$data['patient_id'] = $patient_id;
				$data['patient'] = $patient_name;
				
				if($patient_id == 0){
					$data['patient'] = 'derect_channel';
				}
				
			}
			

			$data['name'] = $name;
			$data['phone_no'] = $phone_no;
			$data['email'] = $email;
			$data['status'] = $status;
			$data['currentStatus'] = $status;
			$data['app_date'] = $app_date;
			$data['specialty_id'] = $specialty_id;
			$data['doctor_id'] = $doctor_id;
			$data['specialty'] = $specialty_id;
			$data['doctor'] = 'Dr.'.$doctor;
			$data['message'] = $message;
			$data['app_time'] = $app_time;
			$data['fee'] = $fee;
			$data['appointment_id'] = $id;


			$this->load->view('admin/Dashboard/Appointment',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function channellingDetails() {
		
		$id = base64_decode(urldecode($this->input->post('selected_id')));

		self::channellingLoad($id);
		
    }

    public function channellingLoad($id) {
		
		if ($this->session->userdata('user_id') != '' ) {
			
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'appointments_list';
		
			$channel_details = $this->AppointmentModel->get_appointment_details($id);

			foreach ($channel_details->result() as $value) {

				$data['doctor_name'] = 'Dr. '.$value->doctor_name;
				$data['doc_image'] = $value->doc_image;
				$data['doc_phoneNo'] = $value->doc_phoneNo;
				$data['doc_email'] = $value->doc_email;
				$data['specialty'] = $value->specialty;
				$data['note'] = $value->doctor_notes;
				$data['patient_name'] = $value->patient_name;
				$patient_id = $value->patient_id;
				$data['patient_id'] = $value->patient_id;

				$data['channeling_id'] = $id;

				$data['patient_name'] = $value->patient_name;
				$data['patient_image'] = $value->patient_image;
				$data['patient_phone_no'] = $value->patient_phone_no;
				$data['patient_email'] = $value->patient_email;

			}
			
			if($data['user_type'] == 'doctor') {
				$data['patient_channellingHistory'] = $this->DashboardModel->get_patient_channellingHistory($patient_id,$id);
			}

			$data['channelling_docs'] = $this->AppointmentModel->get_channelling_docs($id,$patient_id);

			$this->load->view('admin/Dashboard/ChannelDetails',$data);

		
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }
	
	public function channellingDocUpload() {
		
		$patient_id = $this->input->post('patient_id');
		$channeling_id = $this->input->post('channeling_id');
		$note = $this->input->post('note');
		$document_type = $this->input->post('document_type');
		
		$data_save = array(
			'doc' => $document_type,
			'note' => $note,
			'channel_id' => $channeling_id,
			'patient_id' => $patient_id
		);
		
		$_docId = $this->DashboardModel->save_channelling_doc($data_save);
		
		self::documentUpload($channeling_id, '_modalDocuments', 'patient', $_docId,$patient_id);
		
		
	}
	
    public function documentUpload($_id, $uploaderName, $docType, $_docId,$patient_id) {

        $is_selectedDocument = 0;

        $extention = pathinfo($_FILES[$uploaderName]['name'], PATHINFO_EXTENSION);
        $is_selectedDocument = strlen($_FILES[$uploaderName]['name']);

        if ($is_selectedDocument > 0) {

            $targetfolder = $this->CommonModel->targetDocumentPath($docType,$patient_id,'channelling_doc');

            $targetfolder = $targetfolder . basename('channelling_doc_'. $_id . '_' . $_docId . '.' . $extention);

            $file_type = $_FILES[$uploaderName]['type'];

            if (
                    $file_type == "application/pdf" || $file_type == "image/jpeg" || $file_type == "image/png" || $file_type == "application/msword" || $file_type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $file_type == "application/vnd.ms-powerpoint" || $file_type == "application/vnd.openxmlformats-officedocument.presentationml.presentation" || $file_type == "application/vnd.ms-excel" || $file_type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            ) {

                if (move_uploaded_file($_FILES[$uploaderName]['tmp_name'], $targetfolder)) {

                    $this->DashboardModel->update_channelling_doc_path($_docId, $targetfolder);

                    redirect('channelling-load/'.$_id, 'refresh');
                } else {

                    redirect('channelling-load/'.$_id, 'refresh');
                }
            } else {

                redirect('channelling-load/'.$_id, 'refresh');
            }
        } else {
			redirect('channelling-load/'.$_id, 'refresh');
		}
		
    }
	
	public function channellingDocDelete() {
		
		$_id = $this->input->post('selected_id');
		$channeling_id = $this->input->post('channeling_id');
		
		$query = ("Delete from tbl_channelling_doc where id = " . $_id);
        $this->db->query($query);
		
		redirect('channelling-load/'.$channeling_id, 'refresh');
	}
	
	public function doctorRecommends() {
		
		$channeling_id = $this->input->post('channeling_id');
		$doctor_recommend = $this->input->post('doctor_recommend');
		
		
		$query = ("UPDATE tbl_channelling SET status = 'Doctor checked' , doctor_notes ='".$doctor_recommend."'where id = ".$channeling_id);
        $this->db->query($query);
		
		redirect('appointment-list', 'refresh');
	}

	
	
    public function medicalReports() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'medical_reports';
			
			$data['patient_id'] = $this->CommonModel->get_patient_id($data['user_id']);
			
			if($data['user_type'] == 'user'){
				$data['rpt_type'] = '';
				$data['rpt_date'] = '';
				$data['user_data'] = '';
			}
			
			$data['medical_reports'] = $this->DashboardModel->get_medical_reports($data);

			$this->load->view('admin/Dashboard/MedicalReports',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }
	
	public function searchMedicalReport() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'medical_reports';
			
			$data['patient_id'] = $this->CommonModel->get_patient_id($data['user_id']);
			
			if($data['user_type'] == 'user'){
				$data['rpt_type'] = $this->input->post('rpt_type');
				$data['rpt_date'] = $this->input->post('rpt_date');
				$data['user_data'] = $this->input->post('user_data');
			}
			
			$data['medical_reports'] = $this->DashboardModel->get_medical_reports($data);

			$this->load->view('admin/Dashboard/MedicalReports',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }
	
	
    public function changePassword() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'changePassword';
			
			$data['password'] = '';
			$data['confirmPassword'] = '';

			$this->load->view('admin/Dashboard/ChangePassword',$data);
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }

    public function passwordSubmit() {
		
		if ($this->session->userdata('user_id') != '' ) {
		
			$data = $this->CommonModel->get_commenData();
			$data['page'] = 'administration'; 
			$data['act_menu'] = 'changePassword';
			
			$data['password'] = $this->input->post('password');
			$data['confirmPassword'] = $this->input->post('confirmPassword');
			
			$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[3]|matches[confirmPassword]');
			$this->form_validation->set_rules('confirmPassword', 'confirm Password', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {

				$this->load->view('admin/Dashboard/ChangePassword',$data);
				return;

			}
			
			$cost = 10;
			$salt = strtr(base64_encode(random_bytes(16)), '+', '.');
			$salt = sprintf("$2a$%02d$", $cost) . $salt;
			$hash = crypt($data['password'], $salt);
			
			
			$this->DashboardModel->updateUserPassword($hash,$data['user_id']);
			
			
			redirect('login', 'refresh');
			
        } else {
			
            redirect('login', 'refresh');
			
        }
		
    }
	
	
	
	
}
