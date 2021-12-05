<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LoginModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function login($email) {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('email', $email);

        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	public function saveUser($data) {
        $this->db->insert('tbl_users', $data);
        $UserId = $this->db->insert_id();
        
        return $UserId;
    }
	
	public function savePatient($data) {
		
        $this->db->insert('tbl_patients', $data);
        $UserId = $this->db->insert_id();
        
        return $UserId;
		
    }
	
}
