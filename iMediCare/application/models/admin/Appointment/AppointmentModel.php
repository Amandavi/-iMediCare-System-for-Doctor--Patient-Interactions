<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AppointmentModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
    public function get_patient_details($_id) {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_users.* ";
        $str_quary .= " from tbl_users ";
        $str_quary .= " where user_id  = '" . trim($_id) . "'";
        $str_quary .= " and user_type  = 'patient'";

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	public function saveAppointment($data) {
        $this->db->insert('tbl_channelling', $data);
        $id = $this->db->insert_id();
        
        return $id;
    }
	
	public function updateAppointment($data,$appointment_id) {
		
		if($this->session->userdata('user_type') == 'user') {
			$this->db->set('user_id', $data["user_id"]);
		}
		
        $this->db->set('name', $data["name"]);
		$this->db->set('email', $data["email"]);
		$this->db->set('phone_no', $data["phone_no"]);
		$this->db->set('channel_date', $data["channel_date"]);
		$this->db->set('specialty', $data["specialty"]);
		$this->db->set('doctor_id', $data["doctor_id"]);
		$this->db->set('message', $data["message"]);
		$this->db->set('channel_time', $data["channel_time"]);
		$this->db->set('fee', $data["fee"]);
		$this->db->set('status', $data["status"]);

		$this->db->where('id', $appointment_id);
		$this->db->update('tbl_channelling');
		
    }
	
    public function get_appointments($data) {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_channelling.* ";
        $str_quary .= " ,dcu.name as doctor_name ";
        $str_quary .= " ,ifnull(pu.name,tbl_channelling.name) as patient_name ";
        $str_quary .= " from tbl_channelling ";
        $str_quary .= " inner join tbl_doctors on tbl_doctors.id =  tbl_channelling.doctor_id ";
		$str_quary .= " inner join tbl_users dcu on dcu.user_id =  tbl_doctors.user_id ";
		$str_quary .= " left join tbl_users pu on pu.user_id =  tbl_channelling.user_id ";
		
		if($data['user_type'] == 'user' || $data['user_type'] == 'doctor') {
			
			$str_quary .= " where 0 = 0";
			if($data['src_app_date'] != null && $data['src_app_date'] != '') {
				$str_quary .= " and tbl_channelling.channel_date = '".$data['src_app_date']."'";
			}
			if($data['src_doctor_id'] > 0) {
				$str_quary .= " and tbl_channelling.doctor_id = '".$data['src_doctor_id']."'";
			}
			if($data['user_data'] != null && $data['user_data'] != '') {
				$str_quary .= " and (tbl_channelling.name like '%".$data['user_data']."%'";
				$str_quary .= " or tbl_channelling.email like '%".$data['user_data']."%'";
				$str_quary .= " or tbl_channelling.phone_no like '%".$data['user_data']."%')";
			}
			if($data['src_status'] != 'All') {
				$str_quary .= " and tbl_channelling.status = '".$data['src_status']."'";
			}
			
		}
		
		if($data['user_type'] == 'patient') {
			
			$str_quary .= " where tbl_channelling.user_id  = '" . trim($data['user_id']) . "'";
			
		}elseif($data['user_type'] == 'doctor'){
			
			$doctor_id = $this->CommonModel->get_doctor_id($data['user_id']);
			$str_quary .= " and tbl_channelling.doctor_id  = '" . $doctor_id . "'";
			$str_quary .= " and (tbl_channelling.status  = 'Confirmed'";
			$str_quary .= " or tbl_channelling.status  = 'Doctor checked')";
		}
        
        $str_quary .= " order by tbl_channelling.channel_date,tbl_channelling.token";

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
    public function get_appointment_details($_id) {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_channelling.* ";
		//doctor details
        $str_quary .= " ,dcu.name as doctor_name ";
        $str_quary .= " ,dcu.image_path as doc_image ";
        $str_quary .= " ,dcu.phone_no as doc_phoneNo ";
        $str_quary .= " ,dcu.email as doc_email ";
        $str_quary .= " ,tbl_doctors.specialty as specialty ";
		//patient details
        $str_quary .= " ,ifnull(tbl_patients.id,0) as patient_id ";
        $str_quary .= " ,ifnull(pu.name,tbl_channelling.name) as patient_name ";
        $str_quary .= " ,ifnull(pu.image_path,'Images/user.jpg') as patient_image ";
        $str_quary .= " ,ifnull(pu.phone_no,tbl_channelling.phone_no) as patient_phone_no ";
        $str_quary .= " ,ifnull(pu.email,tbl_channelling.email) as patient_email ";
		
        $str_quary .= " from tbl_channelling ";
        $str_quary .= " inner join tbl_doctors on tbl_doctors.id =  tbl_channelling.doctor_id ";
        $str_quary .= " inner join tbl_users dcu on dcu.user_id =  tbl_doctors.user_id ";
        $str_quary .= " left join tbl_patients on tbl_patients.user_id =  tbl_channelling.user_id ";
		$str_quary .= " left join tbl_users pu on pu.user_id =  tbl_channelling.user_id ";
		
		$str_quary .= " where tbl_channelling.id  = '" . trim($_id) . "'";

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
    public function get_channelling_docs($id,$patient_id) {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_channelling_doc.* ";
        $str_quary .= " from tbl_channelling_doc ";
		$str_quary .= " where tbl_channelling_doc.channel_id  = '" . trim($id) . "'";
		$str_quary .= " and tbl_channelling_doc.patient_id  = '" . trim($patient_id) . "'";

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	public function tokenIssue($data,$appointment_id) {
		
		$token = 1;
		
		$str_quary = "";
		
        $str_quary .= " select * ";
		$str_quary .= " from tbl_channelling ";
		$str_quary .= " where tbl_channelling.doctor_id = ".$data['doctor_id'];
		$str_quary .= " and tbl_channelling.channel_date = '".$data['channel_date']."' ";
		$str_quary .= " and tbl_channelling.status in ('Confirmed') ";
		$str_quary .= " and tbl_channelling.token not in ('N/A') ";
		$str_quary .= " order by tbl_channelling.token desc ";
		$str_quary .= " limit 1 ";
		
		
		$_details = $this->db->query($str_quary);
		foreach ($_details->result() as $value) {
			
			$token = $value->token + 1;
			
		}
		
		$this->db->set('token', $token);

		$this->db->where('id', $appointment_id);
		$this->db->update('tbl_channelling');
		
        return $token;
		
    }
	
    

}












