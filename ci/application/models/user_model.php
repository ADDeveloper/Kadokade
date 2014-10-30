<?php

/**
 * @author Rahman Haghparast <haghparast@gmail.com>
 */
class user_model extends ci_model {

    function getUserInfo($parameters) {
        $SQL = "SELECT * FROM `users` WHERE `cell_number`=?";
        $q = $this->db->query($SQL, array($parameters->cell_number));
    	return $q->row();
    }
	
    function setReferer($parameters) {
		$output = new stdclass;
        $SQL = "SELECT * FROM `users` u1 JOIN `users` u2 ON u1.id=u2.referer_id WHERE u2.`cell_number`=? AND u1.`cell_number`=?";
        $q = $this->db->query($SQL, array($parameters->own_cell, $parameters->referer_cell));

		$SQL = "SELECT id FROM `users` WHERE `cell_number`=?";
		$q = $this->db->query($SQL, array($parameters->referer_cell));
		if ($q->num_rows() == 0){
			$output->status = -1;
			$output->message = 'referer not in database';
			return $output;
		}
		else{
			$tmp = $q->row();
			$referer_id = $tmp->id;
			$SQL = "SELECT id FROM `users` WHERE `cell_number`=? OR `IMEI`=?";
			$q = $this->db->query($SQL, array($parameters->own_cell,$parameters->IMEI));
			if ($q->num_rows() == 0){
				$data = array();
				$data['cell_number'] = $parameters->own_cell;
				$data['referer_id'] = $referer_id;
				$data['status'] = 0;
				$data['IMEI'] = $parameters->IMEI;
				$this->db->insert('users', $data);
				$user_id = $this->db->insert_id();
				$data = array();
				$data['related_user_id'] = $user_id;
				$data['relation_type'] = 1;//self
				$this->db->insert('people', $data);
				$output->status = 1;
				$output->message = 'user added';
				$output->data = array('user_id'=>$user_id);
				return $output;
			}
			else{
				$output->status = -2;
				$output->message = 'existing user or IMEI';
				return $output;
			}
		}


    	if ($q->num_rows() == 0){
			$SQL = "SELECT id FROM `users` WHERE `cell_number`=?";
			$q = $this->db->query($SQL, array($parameters->own_cell));
			if ($q->num_rows() == 0){
				$data = array();
				$data['cell_number'] = $parameters->own_cell;
				$data['status'] = 0;
				$data['IMEI'] = $parameters->IMEI;
				$this->db->insert('users', $data);
				$user_id = $this->db->insert_id();
				echo $user_id;
			}
		}
    }
	
    function checkVerification($parameters) {
		$output = new stdclass;
        $SQL = "SELECT * FROM `users` WHERE `cell_number`=? AND `verification_code`=? AND `status`=1";
        $q = $this->db->query($SQL, array($parameters->cell_number, $parameters->verification_code));
		if ($q->num_rows() > 0){
			$output->status = -2;
			$output->message = 'cell number already verified';
			return $output;
		}

        $SQL = "SELECT * FROM `users` WHERE `cell_number`=? AND `verification_code`=? AND `status`=0";
        $q = $this->db->query($SQL, array($parameters->cell_number, $parameters->verification_code));

		if ($q->num_rows() == 0){
			$output->status = -1;
			$output->message = 'cell number or verification code not valid';
			return $output;
		}
		else{
			$data = array();
			$data['status'] = 1;
			$this->db->where('cell_number', $parameters->cell_number);
			$this->db->update('users', $data); 
			$output->status = 1;
			$output->message = 'validated';
			return $output;
		}
    }
    
	function login($parameters) {
		$output = new stdclass;
        $SQL = "SELECT IMEI FROM `users` WHERE `cell_number`=? AND `status`=1";
		$q = $this->db->query($SQL, array($parameters->cell_number));
		if ($q->num_rows() == 0){
			$output->status = -1;
			$output->message = 'cell number not registered';
			return $output;
		}
		$tmp = $q->row(); 
		$IMEI = $tmp->IMEI;
		$password = sha1($IMEI.$parameters->password);
        $SQL = "SELECT * FROM `users` WHERE `cell_number`=? AND `password`=? AND `status`=1";
        $q = $this->db->query($SQL, array($parameters->cell_number, $password));
		if ($q->num_rows() == 0){
			$output->status = -2;
			$output->message = 'password error';
			return $output;
		}
		else{
			$output->status = 1;
			$output->message = 'login ok';
			return $output;
		}
    }
    
	function setPassword($parameters) {
		$output = new stdclass;
        $SQL = "SELECT `IMEI` FROM `users` WHERE `cell_number`=? AND `status`=1";
		$q = $this->db->query($SQL, array($parameters->cell_number));
		if ($q->num_rows() == 0){
			$output->status = -1;
			$output->message = 'cell number not registered';
			return $output;
		}
		$tmp = $q->row(); 
		$IMEI = $tmp->IMEI;
		$password = sha1($IMEI.$parameters->password);
		$data = array();
		$data['password'] = $password;
		$this->db->where('cell_number', $parameters->cell_number);
		$this->db->update('users', $data); 
		$output->status = 1;
		$output->message = 'password set';
		return $output;
    }
	
}

