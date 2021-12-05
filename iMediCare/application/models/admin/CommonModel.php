<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CommonModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_commenData() {
		
		$user_id = $this->session->userdata('user_id');
		$user_type = $this->session->userdata('user_type');
		$user_name = $this->session->userdata('user_name');
		$user_image = $this->session->userdata('user_image');
		
		$data['user_id'] = $user_id != null && $user_id != '' ? $user_id : 0;
		$data['user_type'] = $user_type != null && $user_type != '' ? $user_type : '';
		$data['user_name'] = $user_name != null && $user_name != '' ? $user_name : '';
		$data['user_image'] = $user_image != null && $user_image != '' ? $user_image : '';
		
		$data['is_login'] = $user_id != null && $user_id != '' ? true : false;
		
		$data['unreadMsg'] = self::get_unreaded_msgs();

        return $data;
		
    }

	public function get_specialty($text) {

        $str_quary = "";

        $str_quary .= " select ";
		$str_quary .= "  tbl_specialty.* ";
		$str_quary .= " from tbl_specialty ";
		
		if (strlen(trim($text)) > 0) {
			$str_quary .= " where tbl_specialty.specialty like '%" . $text . "%'";
		}

        $query = $this->db->query($str_quary);
        return $query;
    }

	public function get_doctors($text,$specialty,$date) {
		
		$date = date("Y-m-d", strtotime(date($date)));

        $str_quary = "";

        $str_quary .= " select ";
		$str_quary .= "  tbl_doctors.* ";
		$str_quary .= "  ,tbl_users.name as doctor_name ";
		
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
		
		$str_quary .= " from tbl_doctors ";
		$str_quary .= " inner join tbl_users on tbl_users.user_id = tbl_doctors.user_id ";
		$str_quary .= " where tbl_doctors.is_active = 1";
		
		if ($specialty != '0') {
			$str_quary .= " and tbl_doctors.specialty like '%".trim($specialty)."%'";
		}
		
		if (strlen(trim($text)) > 0) {
			$str_quary .= " and tbl_users.name like '%" . trim($text) . "%'";
		}
		
		if (strlen(trim($date)) > 0) {
			$str_quary .= " and case "; 
			$str_quary .= " 	when dayname('".$date."') = 'Monday' then tbl_doctors.chkTuesday = 1 ";
			$str_quary .= " 	when dayname('".$date."') = 'Tuesday' then tbl_doctors.chkTuesday = 1 ";
			$str_quary .= " 	when dayname('".$date."') = 'Wednesday' then tbl_doctors.chkWednesday = 1 ";
			$str_quary .= " 	when dayname('".$date."') = 'Thursday' then tbl_doctors.chkThursday = 1 ";
			$str_quary .= " 	when dayname('".$date."') = 'Friday' then tbl_doctors.chkFriday = 1 ";
			$str_quary .= " 	when dayname('".$date."') = 'Saturday' then tbl_doctors.chkSaturday = 1 ";
			$str_quary .= " 	when dayname('".$date."') = 'Sunday' then tbl_doctors.chkSunday = 1 ";
			$str_quary .= " 	else 0 = 0 ";
			$str_quary .= " end ";
		}
		
        $query = $this->db->query($str_quary);
        return $query;
		
    }

	public function get_patient($text) {

        $str_quary = "";

        $str_quary .= " select ";
		$str_quary .= "  tbl_patients.* ";
		$str_quary .= "  ,tbl_users.name as patient_name ";
		$str_quary .= "  ,tbl_users.phone_no as phone_no ";
		$str_quary .= "  ,tbl_users.email as email ";
		$str_quary .= " from tbl_patients ";
		$str_quary .= " inner join tbl_users on tbl_users.user_id = tbl_patients.user_id ";
		
		if (strlen(trim($text)) > 0) {
			$str_quary .= " and ((tbl_users.name like '%".$text."%'";
			$str_quary .= " or tbl_users.email like '%".$text."%')";
			$str_quary .= " or tbl_users.phone_no like '%".$text."%')";
		}
		
        $query = $this->db->query($str_quary);
        return $query;
		
    }
	
    public function get_doctor_id($_id) {
		
		$doctor_id = 0;
		
		$str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_doctors.* ";
        $str_quary .= " from tbl_doctors ";
        $str_quary .= " where tbl_doctors.user_id  = '" . trim($_id) . "'";

        $query = $this->db->query($str_quary);
		
		foreach ($query->result() as $value) {

			$doctor_id = $value->id;
			
		}
		
		return $doctor_id;
		
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
	
    public function get_doctor_user_id($_id) {
		
		$doctor_id = 0;
		
		$str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_doctors.* ";
        $str_quary .= " from tbl_doctors ";
        $str_quary .= " where tbl_doctors.id  = '" . trim($_id) . "'";

        $query = $this->db->query($str_quary);
		
		foreach ($query->result() as $value) {

			$doctor_id = $value->user_id;
			
		}
		
		return $doctor_id;
		
	}
	
    public function get_patient_user_id($_id) {
		
		$patient_id = 0;
		
		$str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_patients.* ";
        $str_quary .= " from tbl_patients ";
        $str_quary .= " where tbl_patients.id  = '" . trim($_id) . "'";

        $query = $this->db->query($str_quary);
		
		foreach ($query->result() as $value) {

			$patient_id = $value->user_id;
			
		}
		
		return $patient_id;
		
	}

	
    public function targetDocumentPath($targetFolder,$parent_id,$_type) {

        $folderPath = FCPATH . $this->config->item('documents_path');

        if (is_dir($folderPath . $targetFolder)) {
            
        } else {
            mkdir($folderPath . $targetFolder, 0777);
        }

        $directory = FCPATH . $this->config->item('documents_path') . $targetFolder . "/";

        if (is_dir($directory . $parent_id)) {
            
        } else {
            mkdir($directory . $parent_id, 0777);
        }

        $image_directory = FCPATH . $this->config->item('documents_path') . $targetFolder . "/" . $parent_id . "/";

        if (is_dir($image_directory . $_type)) {
            
        } else {
            mkdir($image_directory . $_type, 0777);
        }

        $falder_path = $this->config->item('documents_path') . $targetFolder . "/" . $parent_id . "/" . $_type . "/";

        return $falder_path;
    }
	
    public function get_patient_email($_id) {
		
		$email = 0;
		
		$str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_users.* ";
        $str_quary .= " from tbl_patients ";
        $str_quary .= " inner join tbl_users on tbl_users.user_id =  tbl_patients.user_id";
        $str_quary .= " where tbl_patients.id  = '" . trim($_id) . "'";

        $query = $this->db->query($str_quary);
		
		foreach ($query->result() as $value) {

			$email = $value->email;
			
		}
		
		return $email;
		
	}
	
    public function get_patient_name($_id) {
		
		$name = 0;
		
		$str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " tbl_users.* ";
        $str_quary .= " from tbl_patients ";
        $str_quary .= " inner join tbl_users on tbl_users.user_id =  tbl_patients.user_id";
        $str_quary .= " where tbl_patients.id  = '" . trim($_id) . "'";

        $query = $this->db->query($str_quary);
		
		foreach ($query->result() as $value) {

			$name = $value->name;
			
		}
		
		return $name;
		
	}
	
    public function get_unreaded_msgs() {
		
		$user_id = $this->session->userdata('user_id');
		$user_type = $this->session->userdata('user_type');
		
		$count = 0;
		
		$str_quary = "";

        $str_quary .= " select ";
        $str_quary .= " count(tbl_chat.id) as msgCount ";
        $str_quary .= " from tbl_chat ";
        $str_quary .= " where tbl_chat.is_read = 0 ";
		
		if($user_type != 'user') {
			$str_quary .= " and tbl_chat.receiver_id  = '" . trim($user_id) . "'";
		} else {
			$str_quary .= " and tbl_chat.is_medical_center  = 1";
			$str_quary .= " and tbl_chat.receiver_id  = 0";
		}
        

        $query = $this->db->query($str_quary);
		
		foreach ($query->result() as $value) {

			$count = $value->msgCount;
			
		}
		
		return $count;
		
	}
	
	
	
	/*email templete*/
	public function send_email($details) {
		
   		$email = $details['email'];
		
		$receiver_name = $details['receiver_name'];
		$subject = $details['subject'];

		$From_name = $this->config->item('_fromName');
		$Host = $this->config->item('host');
		$Username = $this->config->item('user_name');
		$Password = $this->config->item('password');
		$From = $this->config->item('from');
		$Port = $this->config->item('port');

		$_emailTemp = self::email_templete($details['_emailBody']);
		
		try { 
			
			if($email != '' && $email != null) {

				try {
					
					require_once APPPATH . "third_party/PHPMailer/src/PHPMailer.php";
					require_once APPPATH . "third_party/PHPMailer/src/SMTP.php";
					require_once APPPATH . "third_party/PHPMailer/src/Exception.php";
					
				} catch (customException $e) {

					throw $e;
					
				}

				$mail = new \PHPMailer\PHPMailer\PHPMailer();

				$strRmail = $email;
				$mail->SMTPDebug = 0;
				$mail->isSMTP();                       
				$mail->Host = $Host;
				$mail->SMTPAuth = true;   
				$mail->Username = $Username;
				$mail->Password = $Password;
				$mail->SMTPSecure = "tls";
				$mail->Port = $Port;
				$mail->From = $From;
				$mail->FromName = $From_name;
				$mail->addAddress($strRmail, $receiver_name);
				$mail->isHTML(true);
				// $mail->addAttachment($filename, $TransactionRef);

				$mail->Subject = $subject;
				$mail->Body = $_emailTemp;

				if (!$mail->send()) {
					//echo('sss');
					echo json_encode($mail->ErrorInfo);
					return;

				} else {
					//echo "Mailer : Success " ;
					//echo json_encode('Success');
					return;
				}
			}
		

		} catch( Exception $e ) {

 			echo json_encode($e->getMessage());
			
		}
		
    }
	
	public function email_templete($_emailBody){
		
		$base_url = $this->config->item('base_url');
		
		$logo = 'https://pcs.anura-group.com/Images/logo.png';
		
		$_html = '<html>';
		$_html .= '<body>';
		
		
		$_html .= '<div style="overflow: auto;padding: 50px 10px 0px 10px;">';
		$_html .= '	<div style="margin: auto;text-align: center;">';
		//$_html .= '		<a><img src="'.$logo.'" style="margin: auto;width: 250px;"></a>';
		$_html .= '		<h1 style="color: #e91e63;margin-bottom: 5px;">iMediCare</h1>';
		$_html .= '		<a style="color: #e91e63;">+94 770 127 136 / +94 770 127 136</a>';
		$_html .= '	</div>';
		$_html .= '</div>';
		
		$_html .= $_emailBody;

		$_html .= '<div style="background-color: #e91e63;overflow: auto;">';
		$_html .= '	<div style="margin: auto;text-align: center;">';
		$_html .= '		<h1 style="color: #FFFFFF;font-size: 20px;font-weight: 100;">iMediCare</h1>';
		$_html .= '	</div>';
		$_html .= '</div>';
		

		$_html .= "</body></html>";
		
		return $_html;
		
	}
	
	public function signUp_emailBody($_details) {
		
		$_emailBody = '';
		
		$_emailBody .= '<div style="padding: 50px 20px;text-align: center;"> ';
		$_emailBody .= '	<h1 style="margin: 0px;text-align: center;font-size: 20px;">Thank you for registered.</h1> ';
		$_emailBody .= '	<h1 style="margin: 0px;margin-top: 20px; text-align: center;font-size: 15px;font-weight: 100;"><strong>'.$_details['name'].'</strong>, We are excited to have you join us. </h1> ';
		$_emailBody .= '	<h1 style="margin: 0px;margin-top: 5px; text-align: center;font-size: 15px;font-weight: 100;"><strong>'.$_details['email'].'</strong></h1> ';
		$_emailBody .= '</div> ';
		
		return $_emailBody;
		
	}
	
	public function token_emailBody($_details) {
		
		$_emailBody = '';
		
		$_emailBody .= '<div style="padding: 50px 20px;text-align: center;"> ';
		$_emailBody .= '	<h1 style="margin: 0px;text-align: center;font-size: 20px;">Appointment token</h1> ';
		$_emailBody .= '	<h1 style="margin: 0px;margin-top: 20px; text-align: center;font-size: 15px;font-weight: 100;"><strong>Hi '.$_details['name'].'</strong>, This is your token. <strong> '.$_details['token'].'</strong> </h1> ';
		$_emailBody .= '	<h1 style="margin: 0px;margin-top: 5px; text-align: center;font-size: 15px;font-weight: 100;"><strong>'.$_details['doctor'].'</strong><br>'.$_details['in_time'].'</h1> ';
		$_emailBody .= '</div> ';
		
		return $_emailBody;
		
	}
	
	public function medicalReport_emailBody($_details) {
		
		$_emailBody = '';
		
		$_emailBody .= '<div style="padding: 50px 20px;text-align: center;"> ';
		$_emailBody .= '	<h1 style="margin: 0px;text-align: center;font-size: 20px;">MEDICAL REPORTS</h1> ';
		$_emailBody .= '	<h1 style="margin: 0px;margin-top: 20px; text-align: center;font-size: 15px;font-weight: 100;"><strong>Hi '.$_details['name'].'</strong>, Your <strong> '.$_details['report'].'</strong> is ready.Please visit our site. </h1> ';
		$_emailBody .= '</div> ';
		
		return $_emailBody;
		
	}
	
	public function userRegister_emailBody($_details) {
		
		$_emailBody = '';
		
		$_emailBody .= '<div style="padding: 50px 20px;text-align: center;"> ';
		$_emailBody .= '	<h1 style="margin: 0px;text-align: center;font-size: 20px;">Registered.</h1> ';
		$_emailBody .= '	<h1 style="margin: 0px;margin-top: 20px; text-align: center;font-size: 15px;font-weight: 100;"><strong>'.$_details['name'].'</strong>, We are excited to have you join us. </h1> ';
		$_emailBody .= '	<h1 style="margin: 0px;margin-top: 5px; text-align: center;font-size: 15px;font-weight: 100;"><strong>'.$_details['email'].'</strong><br>'.$_details['msg'].'</h1> ';
		$_emailBody .= '</div> ';
		
		return $_emailBody;
		
	}

}
