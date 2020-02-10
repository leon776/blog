<?php
class WaterFall{
	private $response = array('errno' => 0, 'body' => array());
	
	private function get_img() {
		$baseUrl = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']);
		$pics = array();
		$picNameList = $this->NoRand();
		foreach ($picNameList as $key => $value) {
			$pics[$key]['id'] = $value;
			$pics[$key]['pic'] =  $baseUrl . '/img/' . $value . '.jpg';
			$pics[$key]['title'] = $value . '.jpg';
			$pics[$key]['height'] = getimagesize('img/' . $value . '.jpg')[1];
		}
		return $pics;
		//var_dump($pics);
	}
	private function NoRand($begin = 0, $end = 4, $limit = 2){ 
		$rand_array = range($begin,$end); 
		shuffle($rand_array);//调用现成的数组随机排列函数 
		return array_slice($rand_array, 0, $limit);//截取前$limit个 
	}
	public function response_json() {
		$this->response['body'] = $this->get_img();
		$json = json_encode($this->response);
		echo $json;
	}
}
$waterFall = new WaterFall();
$waterFall->response_json();
