<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ProfileModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
    public function get_profile_details($_id) {

        $str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_users.* ";
        $str_quary .= " , ifnull(tbl_patients.note,'') as patient_note ";
        $str_quary .= " from tbl_users ";
        $str_quary .= " left join tbl_doctors on tbl_doctors.user_id = tbl_users.user_id ";
        $str_quary .= " left join tbl_patients on tbl_patients.user_id = tbl_users.user_id ";
        $str_quary .= " where tbl_users.user_id  = '" . trim($_id) . "'";

        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
	public function updateProfile($data,$user_id,$user_type) {
		
		$image_path = $data["image_path"];
		
		if($data["is_new_image"] == 1) {
			
			$falder_path = "documents/userimages/".$user_id;
			$std_image_url = $falder_path.'.png';
			
			if (file_exists($std_image_url))  
			{ 
				unlink(trim($std_image_url));
			} 
			
			$std_image = $data["image_path"];

			$image_parts = explode(";base64,", $std_image);
			$image_type_aux = explode("image/", $image_parts[0]);
			$image_type = $image_type_aux[1];
			$image_base64 = base64_decode($image_parts[1]);
			$file = $std_image_url;

			file_put_contents($file, $image_base64);
			$image_path = $std_image_url;

		}
		
        $this->db->set('image_path', $image_path);
        $this->db->set('name', $data["name"]);
		$this->db->set('email', $data["email"]);
		$this->db->set('phone_no', $data["phone_no"]);

		$this->db->where('user_id', $user_id);
		$this->db->update('tbl_users');
		
		return $image_path;
        
    }
	
	
	
	public function updateProfileImage($data,$user_id) {
		
		$image_path = $data["image_path"];
		
		if($data["is_new_image"] == 1) {
			
			$falder_path = "documents/userimages/".$user_id;
			$std_image_url = $falder_path.'.png';
			
			if (file_exists($std_image_url))  
			{ 
				unlink(trim($std_image_url));
			} 
			
			$std_image = $data["image_path"];

			$image_parts = explode(";base64,", $std_image);
			$image_type_aux = explode("image/", $image_parts[0]);
			$image_type = $image_type_aux[1];
			$image_base64 = base64_decode($image_parts[1]);
			$file = $std_image_url;

			file_put_contents($file, $image_base64);
			$image_path = $std_image_url;

		}
		
        $this->db->set('image_path', $image_path);
		
		$this->db->where('user_id', $user_id);
		$this->db->update('tbl_users');
		
		return $image_path;
        
    }
	
    public function get_patient_id($_id) {
		
		$patient_id = 0;
		
		$str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_patients.* ";
        $str_quary .= " from tbl_patients ";
        $str_quary .= " where tbl_patients.user_id  = '" . trim($_id) . "'";

        $query = $this->db->query($str_quary);
		
		foreach ($query->result() as $value) {

			$patient_id = $value->id;
			
		}
		
		return $patient_id;
		
	}

}
