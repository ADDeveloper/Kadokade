<?php
require(APPPATH.'/libraries/REST_Controller.php');
 
class Api extends REST_Controller{
    
	function user_get(){
        if($this->get('cell')){
			$this->load->model('user_model');
			$params = new stdclass;
			$params->cell_number = $this->get('cell');
			$result = $this->user_model->getUserInfo($params);
            $this->response($result, 200); // 200 being the HTTP response code
		}
        else{
            $this->response(NULL, 404);
		}
	}
     
    function user_post(){
        if($this->post('data')){
			$data = $this->post('data');
			$this->load->model('user_model');
			$params = new stdclass;
			$params->cell_number = $data['cell_number'];
			$result = $this->user_model->getUserInfo($params);
            $this->response($result, 200); // 200 being the HTTP response code
		}
        else{
            $this->response(NULL, 404);
		}
	}
	
	function referer_get(){
        if($this->get('referer_cell') && $this->get('own_cell') && $this->get('IMEI')){
			$this->load->model('user_model');
			$params = new stdclass;
			$params->referer_cell = $this->get('referer_cell');
			$params->own_cell = $this->get('own_cell');
			$params->IMEI = $this->get('IMEI');
			$result = $this->user_model->setReferer($params);
			if ($result->status == 1){
				$params = new stdclass;
				$params->user_id = $result->data['user_id'];
				$params->cell_number = $result->data['cell_number'];
				$this->user_model->setVerificationCode($params);
			}
            $this->response($result, 200); // 200 being the HTTP response code
		}
        else{
            $this->response(NULL, 404);
		}
	}
     
	function verification_get(){
        if($this->get('cell_number') && $this->get('verification_code')){
			$this->load->model('user_model');
			$params = new stdclass;
			$params->cell_number = $this->get('cell_number');
			$params->verification_code = $this->get('verification_code');
			$result = $this->user_model->checkVerification($params);
            $this->response($result, 200); // 200 being the HTTP response code
		}
        else{
            $this->response(NULL, 404);
		}
	}
     
	function login_get(){
        if($this->get('cell_number') && $this->get('password')){
			$this->load->model('user_model');
			$params = new stdclass;
			$params->cell_number = $this->get('cell_number');
			$params->password = $this->get('password');
			$result = $this->user_model->login($params);
            $this->response($result, 200); // 200 being the HTTP response code
		}
        else{
            $this->response(NULL, 404);
		}
	}
     
	function setVerificationCode_get(){
        if($this->get('user_id') && $this->get('cell_number')){
			$this->load->model('user_model');
			$params = new stdclass;
			$params->user_id = $this->get('user_id');
			$params->cell_number = $this->get('cell_number');
			$result = $this->user_model->setVerificationCode($params);
            $this->response($result, 200); // 200 being the HTTP response code
		}
        else{
            $this->response(NULL, 404);
		}
	}
     
	function setPassword_get(){
        if($this->get('cell_number') && $this->get('password')){
			$this->load->model('user_model');
			$params = new stdclass;
			$params->cell_number = $this->get('cell_number');
			$params->password = $this->get('password');
			$result = $this->user_model->setPassword($params);
            $this->response($result, 200); // 200 being the HTTP response code
		}
        else{
            $this->response(NULL, 404);
		}
	}
     
}
?>