<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DashboardModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
    public function get_medical_reports($data) {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_medical_reports.* ";
        $str_quary .= " ,tbl_users.name as patient ";
        $str_quary .= " from tbl_medical_reports ";
        $str_quary .= " inner join tbl_patients on tbl_patients.id = tbl_medical_reports.patient_id ";
        $str_quary .= " inner join tbl_users on tbl_users.user_id = tbl_patients.user_id ";
		
		if($this->session->userdata('user_type') == 'patient'){
			$str_quary .= " where tbl_medical_reports.patient_id  = '" . trim($data['patient_id']) . "'";
		}
		
		if($this->session->userdata('user_type') == 'user'){
			$str_quary .= " where 0 = 0";
			if(strlen(trim($data['rpt_type'])) > 0){
				$str_quary .= " and tbl_medical_reports.report like '%".$data['rpt_type']."%'";
			}
			if(strlen(trim($data['user_data'])) > 0) {
				$str_quary .= " and (tbl_users.name like '%".$data['user_data']."%'";
				$str_quary .= " or tbl_users.email like '%".$data['user_data']."%'";
				$str_quary .= " or tbl_users.phone_no like '%".$data['user_data']."%')";
			}
			if(strlen(trim($data['rpt_date'])) > 0){
				$str_quary .= " and date(tbl_medical_reports.created_on) = '".$data['rpt_date']."'";
			}
		}
		
        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	public function save_channelling_doc($data) {
        $this->db->insert('tbl_channelling_doc', $data);
        $_id = $this->db->insert_id();
        
        return $_id;
    }
	
	public function update_channelling_doc_path($_docId, $doc_path) {
		
        $this->db->set('doc_path', $doc_path);

		$this->db->where('id', $_docId);
		$this->db->update('tbl_channelling_doc');
		
    }
	
	public function updateUserPassword($password, $user_id) {
		
        $this->db->set('password', $password);

		$this->db->where('user_id', $user_id);
		$this->db->update('tbl_users');
		
    }
	
    public function get_doctors_appointments($date) {

        $str_quary = " ";

        $str_quary .= " select "; 
		$str_quary .= " tbl_users.name ";
		$str_quary .= " ,tbl_users.image_path ";
		$str_quary .= " ,tbl_doctors.specialty ";
		$str_quary .= " ,count(tbl_channelling.id) as totalCount ";
		$str_quary .= " ,(select "; 
		$str_quary .= " count(tbl_channelling.id) ";
		$str_quary .= " from tbl_channelling ";
		$str_quary .= " where (tbl_channelling.status = 'Confirmed' or tbl_channelling.status = 'Doctor checked') ";
		$str_quary .= " and tbl_channelling.channel_date = '".$date."'";
		$str_quary .= " and tbl_channelling.doctor_id = tbl_doctors.id ) as confirmedCount ";
		$str_quary .= " ,(select "; 
		$str_quary .= " count(tbl_channelling.id) ";
		$str_quary .= " from tbl_channelling ";
		$str_quary .= " where tbl_channelling.status = 'New' ";
		$str_quary .= " and tbl_channelling.channel_date = '".$date."'";
		$str_quary .= " and tbl_channelling.doctor_id = tbl_doctors.id ) as newCount ";
		$str_quary .= " ,(select "; 
		$str_quary .= " count(tbl_channelling.id) ";
		$str_quary .= " from tbl_channelling ";
		$str_quary .= " where tbl_channelling.status = 'Cancelled' ";
		$str_quary .= " and tbl_channelling.channel_date = '".$date."'";
		$str_quary .= " and tbl_channelling.doctor_id = tbl_doctors.id ) as cancelCount ";
		
		$str_quary .= " ,( case "; 
		$str_quary .= " 	when dayname('".$date."') = 'Monday' then tbl_doctors.avlTuesday ";
		$str_quary .= " 	when dayname('".$date."') = 'Tuesday' then tbl_doctors.avlTuesday ";
		$str_quary .= " 	when dayname('".$date."') = 'Wednesday' then tbl_doctors.avlWednesday ";
		$str_quary .= " 	when dayname('".$date."') = 'Thursday' then tbl_doctors.avlThursday ";
		$str_quary .= " 	when dayname('".$date."') = 'Friday' then tbl_doctors.avlFriday ";
		$str_quary .= " 	when dayname('".$date."') = 'Saturday' then tbl_doctors.avlSaturday ";
		$str_quary .= " 	when dayname('".$date."') = 'Sunday' then tbl_doctors.avlSunday ";
		$str_quary .= " 	else '' ";
		$str_quary .= " end ) as channelling_time  ";
		
		$str_quary .= " from tbl_channelling ";
		$str_quary .= " inner join tbl_doctors on tbl_doctors.id = tbl_channelling.doctor_id ";
		$str_quary .= " inner join tbl_users on tbl_users.user_id = tbl_doctors.user_id ";
		$str_quary .= " where tbl_channelling.channel_date = '".$date."'";
		$str_quary .= " group by tbl_channelling.doctor_id ";
		
        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
    public function get_doctors_appointments_summary($date) {

        $str_quary = " ";

        $str_quary .= " select "; 
		$str_quary .= " tbl_users.name ";
		$str_quary .= " ,tbl_users.image_path ";
		$str_quary .= " ,ch.channel_date ";
		$str_quary .= " ,count(ch.id) as totalCount ";
		$str_quary .= " ,(select "; 
		$str_quary .= " count(tbl_channelling.id) ";
		$str_quary .= " from tbl_channelling ";
		$str_quary .= " where (tbl_channelling.status = 'Confirmed' or tbl_channelling.status = 'Doctor checked') ";
		$str_quary .= " and tbl_channelling.channel_date = ch.channel_date";
		$str_quary .= " and tbl_channelling.doctor_id = tbl_doctors.id ) as confirmedCount ";
		$str_quary .= " ,(select "; 
		$str_quary .= " count(tbl_channelling.id) ";
		$str_quary .= " from tbl_channelling ";
		$str_quary .= " where tbl_channelling.status = 'New' ";
		$str_quary .= " and tbl_channelling.channel_date = ch.channel_date";
		$str_quary .= " and tbl_channelling.doctor_id = tbl_doctors.id ) as newCount ";
		$str_quary .= " ,(select "; 
		$str_quary .= " count(tbl_channelling.id) ";
		$str_quary .= " from tbl_channelling ";
		$str_quary .= " where tbl_channelling.status = 'Cancelled' ";
		$str_quary .= " and tbl_channelling.channel_date = ch.channel_date";
		$str_quary .= " and tbl_channelling.doctor_id = tbl_doctors.id ) as cancelCount ";
		
		$str_quary .= " from tbl_channelling ch";
		$str_quary .= " inner join tbl_doctors on tbl_doctors.id = ch.doctor_id ";
		$str_quary .= " inner join tbl_users on tbl_users.user_id = tbl_doctors.user_id ";
		$str_quary .= " where ch.channel_date >= '".$date."'";
		
		$user_id = $this->session->userdata('user_id');
		$doctor_id = $this->CommonModel->get_doctor_id($user_id);
		$str_quary .= " and ch.doctor_id = '".$doctor_id."'";
		$str_quary .= " group by ch.channel_date ";
		
        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
    public function get_user_appointments($date) {
		
		$user_id = $this->session->userdata('user_id');

        $str_quary = " ";

        $str_quary .= " select "; 
		$str_quary .= " tbl_users.name ";
		$str_quary .= " ,tbl_channelling.token ";
		$str_quary .= " ,tbl_users.image_path ";
		$str_quary .= " ,tbl_doctors.specialty ";
		$str_quary .= " , tbl_channelling.channel_time as channelling_time  ";
		$str_quary .= " , tbl_channelling.status ";
		
		$str_quary .= " from tbl_channelling ";
		$str_quary .= " inner join tbl_doctors on tbl_doctors.id = tbl_channelling.doctor_id ";
		$str_quary .= " inner join tbl_users on tbl_users.user_id = tbl_doctors.user_id ";
		$str_quary .= " where tbl_channelling.channel_date = '".$date."'";
		$str_quary .= " and tbl_channelling.user_id = '".$user_id."'";
		
		
        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
    public function get_user_count($user_type) {
		
		$user_count = 0;

        $str_quary = " ";

        $str_quary .= " select "; 
		$str_quary .= " count(tbl_users.user_id)  as users";
		
		$str_quary .= " from tbl_users ";
		$str_quary .= " where tbl_users.user_type = '".$user_type."'";
		
		$query = $this->db->query($str_quary);
		foreach ($query->result() as $value) {
			$user_count = $value->users;
		}
		
        return $user_count;
		
    }
	
	public function get_patient_channellingHistory($patient_id) {
		
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
		
		$str_quary .= " where tbl_patients.id  = '" . trim($patient_id) . "'";
		$str_quary .= " and tbl_channelling.status = 'Doctor checked' ";
		$str_quary .= " and tbl_channelling.id not in ('".$id."')";
		$str_quary .= " order by tbl_channelling.channel_date desc ";
		$str_quary .= " limit 5";

        $query = $this->db->query($str_quary);
        return $query;
		
	}

}
