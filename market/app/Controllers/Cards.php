<?php

namespace App\Controllers;
use App\Models\CardsExtendModel;
use App\Models\CardsSearchModel;
use App\Models\CardsModel;
use App\Models\UsersModel;
use App\Models\SectionsModel;
use App\Models\NotificationsModel;
use monken\TablesIgniter;
class Cards extends BaseController
{
	private function sectionControl(){
		if(verifysection('1') != null){
    		$verify = verifysection('1');
	    	if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9'){
	    		header('location:'.base_url());
	    		exit();
	    	}
	    	else if($verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9') {
	    		$view = 'maintenance';
	    		return ['view' => $view, 'sellersactivate' => 0];
	    		
	    	}
	    	else {
	    		$view = 'cards';
	    		return ['view' => $view, 'sellersactivate' => $verify['sellersactivate']];
	    	}
    	}
    	else {
    		header('location:'.base_url());
    		exit();
    	}
	}
	public function index(){
		if(session()->get("logedin") == "1"){
			$sectionVerif = $this->sectionControl();
			$data = [];
			if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
				$modelusers = new UsersModel;
				$res = $modelusers->where('id' , session()->get('suser_id'))->findAll();
				$data['sellerbalance'] = $res[0]['seller_balance'];
			}
			$settings = fetchSettings();
			$mycart = getCart();
			$modelCards = new CardsModel;
			$Res = $modelCards->where(['selled' => '0', 'refunded' => '0'])->orderBy('id', 'RANDOM')->findAll();
			$data['allcards'] = $Res;
			$data["nbitemscart"] = $mycart[0];
			$data["cartInnerHtml"] = $mycart[1];
			$data["settings"] = $settings;
			$data["sectionName"] = "Cards";
			$data["sellersactivate"] = $sectionVerif['sellersactivate'];
			echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view($sectionVerif['view']);
            echo view("assets/footer");
            echo view("assets/scripts");	
		}
		else {
			header('location:'.base_url().'/login');
			exit();
		}
	}
	
	public function getStates(){
	    if(session()->get("logedin") == "1"){
    	    if($this->request->isAJAX()){
        		$response = array();
        		$html = '';
        		$citys =  '';
        		if(preg_match("/^([a-zA-Z0-9 ])+$/i", trim($this->request->getPost('country')))){
        		    $country = $this->request->getPost('country');    
        		}
        		else {
        		    $country = ""; 
        		}
        		
        		$model = new CardsModel;
        		$Results = $model->where(["country" => $country, "selled" => '0', "refunded" => '0'])->findAll();
        		if(count($Results) > 0){
        			$mstates = [];
        			$html .= '<option selected value="all">All</option>';
        			foreach($Results as $value){
        				$mstates[] = $value['state'];
        			}
        			
        			$unicmstates = array_unique($mstates);
        			
        			foreach ($unicmstates as $z => $s) {
        				if($s != '' && $s != 'N/A'){
        					$html .= '<option value="'.$s.'">'.$s.'</option>';
        				}
        				else {
        					$html .= '<option value="">No Results</option>';
        				}
        			}
        		}
        		$mcitys = [];
        		$citys .= '<option selected value="all">All</option>';
        		foreach($Results as $valuecity){
        			$mcitys[] = $valuecity['city'];
        		}
        		
        		$usnicmcitys = array_unique($mcitys);
        		foreach ($usnicmcitys as $p => $c) {
        			if($c != '' && $c != 'N/A'){
        				$citys .='<option value="'.$c.'">'.$c.'</option>';
        			}
        			else {
        				$citys .= '<option value="">No Results</option>';
        			}
        		}
        		$response["html"] = $html;
        		$response["citys"] = $citys;
        		$response["csrft"] = csrf_hash();
        		header('Content-Type: application/json');
        		echo json_encode($response);
        		exit();
    	    }
    	    else {
    	        echo "Nice try ;)";
    	        exit();
    	    }
	    }
        else {
			header('location:'.base_url().'/login');
			exit();
		}
	}

	public function getCitys(){
	    if(session()->get("logedin") == "1"){
    	    if($this->request->isAJAX()){
        		$response = array();
        		$citys =  '';
        		if(preg_match("/^([a-zA-Z0-9 ])+$/i", trim($this->request->getPost('state'))) && preg_match("/^([a-zA-Z0-9 ])+$/i", trim($this->request->getPost('country')))){
        		    $state = $this->request->getPost('state');
        		    $country = $this->request->getPost('country');
        		}
        		else {
        		    $state = ""; 
        		}
        		$model = new CardsModel;
        		$Resultscity = $model->where(["country"=> $country, "state" => $state, "selled" => '0', "refunded" => '0', "baseapproved" => '1'])->findAll();
        		if(count($Resultscity) > 0){
        		    $citys .= '<option selected value="all">All</option>';
        			foreach($Resultscity as $valuecity){
        				$mcitys[] = $valuecity['city'];
        			}
        			$usnicmcitys = array_unique($mcitys);
        			foreach ($usnicmcitys as $p => $c) {
        				if($c != '' && $c != 'N/A'){
        					$citys .='<option value="'.$c.'">'.$c.'</option>';
        				}
        				else {
        					$citys .= '<option value="">No Results</option>';
        				}
        			}	
        		}	
        		$response["citys"] = $citys;
        		$response["csrft"] = csrf_hash();
        		header('Content-Type: application/json');
        		echo json_encode($response);
        		exit();
    	    }
    	    else {
    	        echo "Nice try ;)";
    	        exit();
    	    }
	    }
	    else {
			header('location:'.base_url().'/login');
			exit();
		}
	}

	public function dosearche(){
	    if($this->request->isAJAX()){
    		if(session()->get('logedin') == '1'){
    			if($this->request->getPost()) {
    				$posts = [];
    				foreach ($this->request->getPost('data') as $key => $value) {	
    					$posts[$value['name']] =  $value['value'];				
    				}
    				$params = array();
    				foreach($posts as $key => $val){
    					if($val != "" && $val != 'All' && $val != 'all' && $val != 'N/A' && $key != 'pricerange' && $key != 'hashed'){
    					    if(strpos($val, "\r\n")){
    					        $val = str_replace("\r\n", ' ', $val);
    					    }
    					    if(preg_match("/^([a-zA-Z0-9 \_\-])+$/i", $val)){
    					        $params[$key] = $val;    
    					    }
    					}
    					if(preg_match("/^([0-9;])+$/i", $posts['pricerange'])){
    					    $prices = explode(';', $posts['pricerange']);    
    					}
    					else {
    					    $prices = ["1","10000"];    
    					}
    				}
    				$model = new CardsSearchModel();
    				
    				$table = new TablesIgniter($model->initTable($params, $prices));
    				return $table->getDatatable();		
    			}
    		}
	    }
    	else {
		    echo "Nice Try ;)";
		    exit();
		}
    		
	}
	
	public function fetchTable(){
	    if($this->request->isAJAX()){
    		if(session()->get("logedin") == '1'){
    			$model = new CardsExtendModel();
    			$table = new TablesIgniter($model->initTable());
    			return $table->getDatatable();
    		}
    		else {
    			header('location:'.base_url());
    			exit();
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }

	}



	/**public function singleAdd(){
		if(session()->get("suser_groupe") != "9" ){
			header('location:'.base_url().'/');
			exit();
		}
		else {
			$response = array();
				$form = '
					<form id="addCont">
						<div class="form-group row">
							<div class="col-12">
								<label>Card number<i class="text-danger"> *</i></label>
								<input type="text" id="number" name="number" class="form-control">
								<small class="number text-danger"></small>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-6">
								<label>Expiration date (MM/YY)<i class="text-danger"> *</i></label>
								<input type="text" id="expiration" name="expiration" class="form-control" data-mask="99/99">
								<small class="expiration text-danger"></small>
							</div>
							<div class="col-6">
								<label>CVV<i class="text-danger"> *</i></label>
								<input type="text" id="cvv" name="cvv" class="form-control">
								<small class="cvv text-danger"></small>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-12">
								<label>Full Name</label>
								<input type="text" id="fullname" name="fullname" class="form-control">
								<small class="fullname text-danger"></small>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-6">
								<label>Phone Number</label>
								<input type="text" id="phone" name="phone" class="form-control" data-mask="">
								<small class="phone text-danger"></small>
							</div>
							<div class="col-6">
								<label>DOB</label>
								<input type="text" id="dob" name="dob" class="form-control">
								<small class="dob text-danger"></small>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-12">
								<label>Address</label>
								<input type="text" id="address" name="address" class="form-control">
								<small class="address text-danger"></small>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-4">
								<label>City</label>
								<input type="text" id="city" name="city" class="form-control">
								<small class="city text-danger"></small>
							</div>
							<div class="col-4">
								<label>State</label>
								<input type="text" id="state" name="state" class="form-control">
								<small class="state text-danger"></small>
							</div>
							<div class="col-4">
								<label>Zip</label>
								<input type="text" id="zip" name="zip" class="form-control">
								<small class="zip text-danger"></small>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-12">
								<label>Price<i class="text-danger"> *</i></label>
								<input type="number" id="price" name="price" class="form-control">
								<small class="price text-danger"></small>
								<input type="hidden" name="'.csrf_token().'" value="'.csrf_hash().'">
							</div>
						</div>
					</form>
				'; 
				$modalContent = $form;
				$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Add new CC', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="addSingle"']);
				$response["csrft"] = csrf_hash();
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
		}
	}**/

	/**public function addSingle(){
		if(session()->get("suser_groupe") != "1" && session()->get("suser_groupe") != "5"){
			exit();
		}
		else {
			$response = array();
			$ValidationRulls = [
				'number' => [
		            'rules'  => 'required',
		            'errors' => [
		            	'required' => 'Insert card number.',
	            	]
	            ],
	            'expiration' => [
		            'rules'  => 'required',
		            'errors' => [
		            	'required' => 'Insert Expiration date.',
	            	]
	            ],
	            'cvv' => [
		            'rules'  => 'required',
		            'errors' => [
		            	'required' => 'Insert CVV.',
	            	]
	            ],
			];

			if(!$this->validate($ValidationRulls)){
				$ErrorFields = $this->validator->getErrors();
				$modalTitle = "Validation Error";
				$modalContent = '';
				foreach($ErrorFields as $key => $value){
					$modalContent .= '<p class=""><b>'.$value.'</b></p>';
				}	
				$response["fieldslist"] = $ErrorFields;
				//$response["modal"] = createModal($modalContent, 'faid bounce animated', $modalTitle, 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
				$response["csrft"] = csrf_hash();
				echo json_encode($response);
				exit();
			}
			else {
				$response = array();
				if(session()->get('suser_groupe') == "1" && session()->get("suser_groupe") != "5"){
					$id = $this->request->getPost('id');
					$Model = new CardsModel;

					
					$arrContextOptions=array(
					    "ssl"=>array(
					        "verify_peer"=>false,
					        "verify_peer_name"=>false,
					    ),
					);
					$bins = file_get_contents(base_url().'/assets/files/binlist-data.csv', false, stream_context_create($arrContextOptions));
					$cc = $this->request->getPost("number");
					$CurlResults = cccheck($cc, $bins);
					//scheme
					if($CurlResults['scheme'] != ""){
						$data['scheme'] = $CurlResults['scheme'];
					}
					else {
						$data['scheme'] = "N/A";
					}
					//type
					if($CurlResults['type'] != ""){
						$data['type'] = $CurlResults['type'];
					}
					else {
						$data['type'] = "N/A";
					}
					//brand
					if($CurlResults['brand'] != ""){
						$data['brand'] = $CurlResults['brand'];
					}
					else {
						$data['brand'] = "N/A";
					}
					//country alpha 2
					if($CurlResults['countryAlpha2']){
						$data['countryalpha2'] = $CurlResults['countryAlpha2'];
					}
					else {
						$data['countryalpha2'] = "Undif";
					}
					//country
					if($CurlResults['country'] != ""){
						$data['country'] = $CurlResults['country'];
					}
					else {
						$data['country'] = "N/A";
					}
					//bank
					if($CurlResults['bank'] != ""){
						$data['bank'] = $CurlResults['bank'];
					}
					else {
						$data['bank']= "N/A";
					}

					foreach($this->request->getPost() as $key => $val){
						if($val != ""){
							$data[$key] = $val;
						}
						else {
							$data[$key] = 'N/A';
						}
					}

					$data['sellerusername'] = session()->get('suser_username');
					$data['sellerid'] = session()->get('suser_id');

					$dataNotif = [
						'subject' => 'CC Store',
						'text' => 'New Cards has been Added',
						'url' => base_url().'/cards'
					];
					$modelNotif = new NotificationsModel;
					$modelNotif->save($dataNotif);
					$usersModel = new UsersModel;
					$ResUsers = $usersModel->findAll();
					foreach($ResUsers as $value){
						$newNotifNumbers = $value['nbnotifications']+1;
						$dataMNotif = [
							'nbnotifications' => $newNotifNumbers,
						];
						$usersModel->update($value['id'], $dataMNotif);
					}

					$Request = $Model->save($data);
					$modalContent = '<p>Log Add succefull</p>';
					$modalTitle = 'Add succefull';
					$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
					$response["csrft"] = csrf_hash();
					header('Content-Type: application/json');
					echo json_encode($response);
				}
				else {
					$modalContent = '<p>Object not selected. E003</p>';
					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
					$response["csrft"] = csrf_hash();
					header('Content-Type: application/json');
					echo json_encode($response);
					exit();	
				}
			}
		}
	}**/




}

