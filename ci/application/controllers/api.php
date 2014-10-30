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
            $this->response($result, 200); // 200 being the HTTP response code
		}
        else{
            $this->response(NULL, 404);
		}
	}
     
}
?>