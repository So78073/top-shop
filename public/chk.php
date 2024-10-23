<?php
	ini_set('output_buffering','on');
	ini_set('zlib.output_compression', 0);
	ob_implicit_flush();
	$ids = json_decode($_POST['ids'], true);

	//ini_set('implicit_flush',1);
	
	//$headers = ['Content-Type' => 'application/json'];
	foreach($ids as $val){
		if(preg_match("/^([0-9])+$/i", $val) == true){
			//$myimtemsModal = new MyitemsModel;
			//$getProductInfos = $myimtemsModal->where([
			//	"prodid" => $val, 
			//	"typeid" => '1', 
			//	"userid" => session()->get('suser_id'), 
			//	"checked" => '0',
			//	"refunded" => '0',
			//	"refundible" => '1'
			//])->findAll();
			//if(count($getProductInfos) == '1'){
			//	$details = explode("|", $getProductInfos[0]["details"]); 
			//	//$a = chkchecker($details);
			//}
			
			//echo json_encode(['data' => $val]);
			//var_dump($this->response->setJSON($val));
			echo $val;
			ob_flush();
			flush();
			sleep(1);
		}
	}
?>