<?php
ini_set('memory_limit','128M');
ini_set('max_execution_time','0');
/**
 * Created by JetBrains PhpStorm.
 * User: Bikooch
 * Date: 11/26/13
 * Time: 3:18 PM
 * To change this template use File | Settings | File Templates.
 */

class rest extends CI_Controller{
	
	private function handle_request($url, $data_string, $api_key){
		$header = array('Content-Type: application/json', 'X-API-KEY: '.$api_key);	
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_POST, 1);                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                                   
		$result = curl_exec($ch);
		print_r($result);
		$result = json_decode($result);
		return $result;
	}
	
	public function randomizer($count){
		$repository = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$output = '';
		for ($i=0; $i < $count; $i++){
			$output .= $repository[mt_rand(0, strlen($repository) - 1)];
		}
		echo $output;
	}
	
	public function test(){
		$url = 'http://localhost/kadokadeh/index.php/api/user.json';
		$cell_number = '09153117217';
		$api_key = 'eywfHzJctuyz6Rv6TcS2aOspWbD5Vg47OBTAXgwg';
		$data = array('data'=>array('cell_number'=>$cell_number));
		$data_string = json_encode($data);  
		$result = $this->handle_request($url, $data_string, $api_key);
	}
		
}