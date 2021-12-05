<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AdministrationModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	//doctor
    public function get_doctors() {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_doctors.* ";
        $str_quary .= " ,tbl_users.name as doc_name ";
        $str_quary .= " ,tbl_users.email as doc_email ";
        $str_quary .= " ,tbl_users.phone_no as doc_phone ";
        $str_quary .= " ,tbl_users.image_path as doc_image ";
        $str_quary .= " from tbl_doctors ";
        $str_quary .= " inner join tbl_users on tbl_users.user_id = tbl_doctors.user_id ";

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	public function saveDoctor($user_data,$save_data) {
		
        $this->db->insert('tbl_users', $user_data);
        $user_id = $this->db->insert_id();
		
        $this->db->insert('tbl_doctors', $save_data);
        $_id = $this->db->insert_id();
		
		$this->db->set('user_id', $user_id);
		$this->db->where('id', $_id);
		$this->db->update('tbl_doctors');
        
        return $user_id;
    }
	
	public function updateDoctor($user_data,$save_data,$_id) {
		
		$this->db->set('specialty', $save_data['specialty']);
		$this->db->set('channelling_fee', $save_data['channelling_fee']);
		$this->db->set('title', $save_data['title']);
		$this->db->set('gender', $save_data['gender']);
		$this->db->set('nic', $save_data['nic']);
		$this->db->set('address', $save_data['address']);
		
		$this->db->set('chkMonday' , $save_data['chkMonday']);
		$this->db->set('avlMonday' , $save_data['avlMonday']);

		$this->db->set('chkTuesday' , $save_data['chkTuesday']);
		$this->db->set('avlTuesday' , $save_data['avlTuesday']);

		$this->db->set('chkWednesday' , $save_data['chkWednesday']);
		$this->db->set('avlWednesday' , $save_data['avlWednesday']);

		$this->db->set('chkThursday' , $save_data['chkThursday']);
		$this->db->set('avlThursday' , $save_data['avlThursday']);

		$this->db->set('chkFriday' , $save_data['chkFriday']);
		$this->db->set('avlFriday' , $save_data['avlFriday']);

		$this->db->set('chkSaturday' , $save_data['chkSaturday']);
		$this->db->set('avlSaturday' , $save_data['avlSaturday']);

		$this->db->set('chkSunday' , $save_data['chkSunday']);
		$this->db->set('avlSunday' , $save_data['avlSunday']);
		
		
		$this->db->where('id', $_id);
		$this->db->update('tbl_doctors');
		
		$user_id = $this->CommonModel->get_doctor_user_id($_id);
		
		$this->db->set('name', $user_data['name']);
		$this->db->set('email', $user_data['email']);
		$this->db->set('phone_no', $user_data['phone_no']);
		$this->db->where('user_id', $user_id);
		$this->db->update('tbl_users');
        
    }
	
    public function get_doctor_details($_id) {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_doctors.* ";
        $str_quary .= " ,tbl_users.name as doc_name ";
        $str_quary .= " ,tbl_users.email as doc_email ";
        $str_quary .= " ,tbl_users.phone_no as doc_phone ";
        $str_quary .= " ,tbl_users.image_path as doc_image ";
        $str_quary .= " from tbl_doctors ";
        $str_quary .= " inner join tbl_users on tbl_users.user_id = tbl_doctors.user_id ";
        $str_quary .= " where tbl_doctors.id = ".$_id;

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	//patient
    public function get_patient() {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_patients.* ";
        $str_quary .= " ,tbl_users.name as patient_name ";
        $str_quary .= " ,tbl_users.email as patient_email ";
        $str_quary .= " ,tbl_users.phone_no as patient_phone ";
        $str_quary .= " ,tbl_users.image_path as patient_image ";
        $str_quary .= " from tbl_patients ";
        $str_quary .= " inner join tbl_users on tbl_users.user_id = tbl_patients.user_id ";

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	public function savePatient($user_data,$save_data) {
		
        $this->db->insert('tbl_users', $user_data);
        $user_id = $this->db->insert_id();
		
        $this->db->insert('tbl_patients', $save_data);
        $_id = $this->db->insert_id();
		
		$this->db->set('user_id', $user_id);
		$this->db->where('id', $_id);
		$this->db->update('tbl_patients');
        
        return $user_id;
    }
	
	public function updatePatient($user_data,$save_data,$_id) {
		
		$this->db->set('birth_day', $save_data['birth_day']);
		$this->db->set('title', $save_data['title']);
		$this->db->set('gender', $save_data['gender']);
		$this->db->set('nic', $save_data['nic']);
		$this->db->set('address', $save_data['address']);
		$this->db->where('id', $_id);
		$this->db->update('tbl_patients');
		
		$user_id = $this->CommonModel->get_patient_user_id($_id);
		
		$this->db->set('name', $user_data['name']);
		$this->db->set('email', $user_data['email']);
		$this->db->set('phone_no', $user_data['phone_no']);
		$this->db->set('image_path', $user_data['image_path']);
		$this->db->where('user_id', $user_id);
		$this->db->update('tbl_users');
        
    }
	
    public function get_patient_details($_id) {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_patients.* ";
        $str_quary .= " ,tbl_users.name as patient_name ";
        $str_quary .= " ,tbl_users.email as patient_email ";
        $str_quary .= " ,tbl_users.phone_no as patient_phone ";
        $str_quary .= " ,tbl_users.image_path as patient_image ";
        $str_quary .= " from tbl_patients ";
        $str_quary .= " inner join tbl_users on tbl_users.user_id = tbl_patients.user_id ";
        $str_quary .= " where tbl_patients.id = ".$_id;

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	//specialty
    public function get_specialty() {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_specialty.* ";
        $str_quary .= " from tbl_specialty ";

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	public function saveSpecialty($save_data) {
		
        $this->db->insert('tbl_specialty', $save_data);
        $_id = $this->db->insert_id();
		
        return $_id;
    }
	
	public function updateSpecialty($save_data,$_id) {
		
		$this->db->set('specialty', $save_data['specialty']);
		$this->db->where('id', $_id);
		$this->db->update('tbl_specialty');
		
        
    }
	
    public function get_specialty_details($_id) {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_specialty.* ";
        $str_quary .= " from tbl_specialty";
        $str_quary .= " where tbl_specialty.id = ".$_id;

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	//user
    public function get_users() {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_users.* ";
        $str_quary .= " from tbl_users ";
        $str_quary .= " where tbl_users.user_type = 'user' ";

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	public function saveUser($save_data) {
		
        $this->db->insert('tbl_users', $save_data);
        $user_id = $this->db->insert_id();
		
        return $user_id;
    }
	
	public function updateUser($save_data,$_id) {
		
		$this->db->set('name', $save_data['name']);
		$this->db->set('email', $save_data['email']);
		$this->db->set('phone_no', $save_data['phone_no']);
		$this->db->set('image_path', $save_data['image_path']);
		$this->db->where('user_id', $_id);
		$this->db->update('tbl_users');
        
    }
	
    public function get_user_details($_id) {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_users.* ";
        $str_quary .= " from tbl_users ";
        $str_quary .= " where tbl_users.user_id = ".$_id;

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	//medical reports
	public function saveMedicalReport($save_data) {
		
        $this->db->insert('tbl_medical_reports', $save_data);
        $user_id = $this->db->insert_id();
		
        return $user_id;
    }
	
	public function updateMedicalReport($save_data,$_id) {
		
		$this->db->set('patient_id', $save_data['patient_id']);
		$this->db->set('note', $save_data['note']);
		$this->db->set('report', $save_data['report']);
		$this->db->where('id', $_id);
		$this->db->update('tbl_medical_reports');
        
    }
	
    public function get_report_details($_id) {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_medical_reports.* ";
        $str_quary .= " ,tbl_users.name as patient ";
        $str_quary .= " from tbl_medical_reports ";
        $str_quary .= " inner join tbl_patients on tbl_patients.id = tbl_medical_reports.patient_id ";
        $str_quary .= " inner join tbl_users on tbl_users.user_id = tbl_patients.user_id ";
        $str_quary .= " where tbl_medical_reports.id = ".$_id;
		
        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	public function update_report_doc_path($_id,$doc_path) {
		
		$this->db->set('doc_path', $doc_path);
		$this->db->where('id', $_id);
		$this->db->update('tbl_medical_reports');
        
    }
	
	

}
