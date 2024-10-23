<?php

namespace App\Controllers;
use App\Models\ShellExtendedModel;
use App\Models\ShellModel;
use App\Models\UsersModel;
use App\Models\SectionsModel;
use App\Models\NotificationsModel;
use monken\TablesIgniter;
class Shell extends BaseController
{
	private function sectionControl(){
		if(verifysection('2') != null){
    		$verify = verifysection('2');
	    	if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9'){
	    		header('location:'.base_url());
	    		exit();
	    	}
	    	else if($verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9') {
	    		$view = 'maintenance';
	    		return ['view' => $view, 'sellersactivate' => 0];
	    	}
	    	else {
	    		$view = 'cpanel';
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
			$modelCards = new ShellModel;
			$Res = $modelCards->where(['selled' => '0', 'refunded' => '0'])->orderBy('id', 'RANDOM')->findAll();
			$data['allcards'] = $Res;
			$data["nbitemscart"] = $mycart[0];
			$data["cartInnerHtml"] = $mycart[1];
			$data["settings"] = $settings;
			$data["sectionName"] = "Shell";
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
	
	/**public function getStates(){
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
        		$Resultscity = $model->where(["country"=> $country, "state" => $state, "selled" => '0', "refunded" => '0'])->findAll();
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
	}**/

	/**public function dosearche(){
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
    		
	}**/
	
	public function fetchTable(){
	    if($this->request->isAJAX()){
    		if(session()->get("logedin") == '1'){
    			$model = new ShellExtendedModel();
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

	public function checker(){
		if(session()->get("logedin") == '1'){
			ini_set('output_buffering','on');
			ini_set('zlib.output_compression', 0);
			ob_implicit_flush();
			$response = service('response');
			$response->send();
			ob_end_flush();
			flush();	
			$ids = json_decode($this->request->getPost('ids'), true);
			$settings = fetchSettings();
			$ShellModel = new ShellModel;
			$Total = count($ids);
			$x = 0;
			$good = 0;
			$bad = 0;
	    	foreach($ids as $vals){
	    		$valsarray = explode('-', $vals);
	    		$val = $valsarray[0];
	    		$x++;
	    		if(preg_match("/^([0-9])+$/i", $val) == true){
	    			$getProductInfos = $ShellModel->where(["id" => $val, ])->findAll();
	    			if(count($getProductInfos) > 0){
	    				$Checkresults = [];
						$doCheckRequ = CpanelChecker($getProductInfos[0]['host'], $getProductInfos[0]['username'], $getProductInfos[0]['password']);
						if($doCheckRequ == true){
							$good++;
							$Checkresults["id"] = $getProductInfos[0]["id"];
							$Checkresults["result"] = "1";
						}
						else {
							$bad++;
							$userModel = new UsersModel;
							$GetSellerInfo = $userModel->where('id', $getProductInfos[0]['sellerid'])->findAll();
							if(count($GetSellerInfo) == 1){
								$newSellerNbOrbjects = $GetSellerInfo[0]["seller_nbobjects"] - 1;
								$UpdatedSellerBalance = [
									'seller_nbobjects' => $newSellerNbOrbjects,
								];
								$userModel->update($GetSellerInfo[0]['id'], $UpdatedSellerBalance);
							}
							//$ShellModel->delete($getProductInfos[0]['id']);
							$Checkresults["id"] = $getProductInfos[0]["id"];
							$Checkresults["result"] = "0";
						}
						$Checkresults["progress"] = intval($x/$Total * 100); 
	    				$Checkresults["total"] = $Total; 
	    				$Checkresults["x"] = $x;
						$Checkresults["good"] = $good;
	    				$Checkresults["bad"] = $bad;

						echo json_encode($Checkresults);
						ob_flush();
						flush();
						sleep(1);
	    			}	
	    		}
	    	}
	    	return $this->response->setJSON('');
		}
		else {
			header('location:'.base_url().'/login');
			exit();
		}			
	}

	//cc tools
	public function initEdit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			header('location:'.base_url().'/');
    			exit();
    		}
    		else {
    			$response = array();
    			if(preg_match("/^([0-9])+$/i", base64_decode($this->request->getPost('id')))){
    				$id = base64_decode($this->request->getPost('id'));
    				$Model = new ShellModel;
    				$Results = $Model->where(['id' => $id, 'selled' => 0, 'refunded' => '0'])->find();
    				$countResults = count($Results);
    				$usermodel = new UsersModel;
    				if($countResults == 1){
    					$getseller = $usermodel->where(['id' => session()->get('suser_id')])->findAll();
    					if(count($getseller) > 0){
    						if($Results[0]['sellerid'] == $getseller[0]['id'] || session()->get('suser_groupe') == '9'){
    							$form = '
    								<form id="edittForm">
    									<div class="form-group row">
    										<div class="col-12">
    											<label>Host<i class="text-danger"> *</i></label>
    											<input type="text" id="host" name="host" class="form-control" value="'.$Results[0]['host'].'">
    											<small class="host text-danger"></small>
    										</div>
    									</div>
    									<div class="form-group row">
    										<div class="col-6">
    											<label>User name<i class="text-danger"> *</i></label>
    											<input type="text" id="username" name="username" class="form-control" value="'.$Results[0]['username'].'">
    											<small class="username text-danger"></small>
    										</div>
    										<div class="col-6">
    											<label>Password<i class="text-danger"> *</i></label>
    											<input type="text" id="password" name="password" class="form-control" value="'.$Results[0]['password'].'">
    											<small class="password text-danger"></small>
    										</div>
    									</div>
    									<div class="form-group row">
    										<div class="col-12">
    											<label>Price<i class="text-danger"> *</i></label>
    											<input type="number" id="price" name="price" class="form-control" value="'.$Results[0]['price'].'">
    											<small class="price text-danger"></small>
    											<input type="hidden" name="'.csrf_token().'" value="'.csrf_hash().'">
    											<input type="hidden" name="id" value="'.base64_encode($Results[0]['id']).'">
    										</div>
    									</div>
    								</form>
    							'; 
    							$modalContent = $form;
    							$response["modal"] = createModal($modalContent, 'fade  ', 'Edit', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="edititem-editcpanel|'.$Results[0]['id'].'"']);
    						}
    						else {
    							$modalContent = '<p>Object not found. E0012</p>';
    							$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    						}
    					}
    					else {
    						$modalContent = '<p>Object not found. E0012</p>';
    						$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					}
    				}
    				else {
    					$modalContent = '<p>Object not found. E002</p>';
    					$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$modalContent = '<p>Object not selected. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();	
    			}
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function edit(){
	   if($this->request->isAJAX()){ 
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			exit();
    		}
    		else {
    			$response = array();
    			$ValidationRulls = [
    				'host' => [
    					'label' => 'Host',
    		            'rules'  => 'required|regex_match[/^http|https?:\/\/[\w\.]+:208[0-9]{1}\/[\w\.]+$/]',
    		            'errors' => [
    		            	'required' => 'Insert Host.',
    		            	'regex_match' => 'A valid Host is required.',
    	            	]
    	            ],
    	            'username' => [
    	            	'label' => 'Username',
    		            'rules'  => 'required|regex_match[/^[a-zA-Z0-9]([a-zA-Z0-9-]{0,30}[a-zA-Z0-9])?$/]',
    		            'errors' => [
    		            	'required' => 'Insert Username.',
    		            	'regex_match' => 'Invalid Username input.'
    	            	]
    	            ],
    	            'password' => [
    	            	'label' => 'Password',
    		            'rules'  => 'required|regex_match[/^[A-Za-z\d\@\$\!\%\*\?\&\{\}\(\)\[\]\<\>\~\#\_\-\,\+\;\.\£\^]{4,30}+$/]',
    		            'errors' => [
    		            	'required' => 'Insert Password.',
    		            	'numeric' => 'nvalid Password input.',
    	            	]
    	            ],
    	            'price' => [
    	            	'label' => 'IP Address',
    		            'rules'  => 'required|regex_match[/^[0-9]{1,50}$/]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid Price.',
    		            	'required' => 'Please insert a valid Price.',
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
    				$response["csrft"] = csrf_hash();
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				if(preg_match("/^([0-9])+$/i", base64_decode($this->request->getPost('id')))){
    					$id = base64_decode($this->request->getPost('id'));
    					$Model = new ShellModel;
    					$Results = $Model->where(['id' => $id, 'selled' => 0])->find();
    					$countResults = count($Results);
    					if($countResults == 1){
    						
							$cpanelData = explode('://', $this->request->getPost('host'));

							if (substr($this->request->getPost('host'), 0, 8) === "https://") {
						        $data["port"] = 'HTTPS';
						    } 
							elseif (substr($this->request->getPost('host'), 0, 7) === "http://") {
						        $data["port"] = 'HTTP';
						    } 
						    else {
						        $data["port"] = 'HTTP';
						    }

							$cpanelhostname = $cpanelData[1];
							if(strpos('/',$cpanelData[1])){
								$cpanelDataCleanedarray = explode('/', $cpanelData[1]);
								$cpanelDataCleaneds = explode(':',$cpanelDataCleanedarray[0]);
								$cpanelDataCleaned = $cpanelDataCleaneds[0];

							}
							else {
								$cpanelDataCleaneds = explode(':',$cpanelData[1]);
								$cpanelDataCleaned = $cpanelDataCleaneds[0];
							}

							$parts = explode(".", $cpanelDataCleaned);
							$data["tld"] = end($parts);

							$GetCpanelip =  gethostbyname($cpanelDataCleaned);
							//var_dump($GetCpanelip);
							//exit();
							try {
								if(filter_var($GetCpanelip, FILTER_VALIDATE_IP) && $GetCpanelip != '127.0.0.1' && $GetCpanelip != '' && $GetCpanelip != null){
							        $json = file_get_contents('https://api.ipregistry.co/'.$GetCpanelip.'?key=pf0f4w9q20rzwwoz' ,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		        					$Infosmyip = json_decode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		        					$data['host'] = $this->request->getPost('host');
		        					$data['username'] = $this->request->getPost('username');
		        					$data['password'] = $this->request->getPost('password');
		        					if(null !== $Infosmyip['connection']['domain']){
	        							$data['hoster'] = $Infosmyip['connection']['domain'];
		        					}
		        					else {
		        						$data['hoster'] = 'N/A';
		        					}
		        					if(null !== $Infosmyip['location']['country']['flag']['twemoji']){
		        						$data['country'] = $Infosmyip['location']['country']['flag']['twemoji'];
		        					}
		        					else {
		        						$data['country'] = 'N/A';
		        					}
									$data["price"] = $this->request->getPost('price');
									$data["sellerid"] = session()->get('suser_id');
									$data["sellerusername"] = session()->get('suser_username');
									//the Insert
									$Model->update($id, $data);
									$modalContent = '<p>Log Edited succefull</p>';
    								$modalTitle = 'Edit succefull';
    								$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");	
								}
								else {
									$modalContent = '<p>Object not found. E001</p>';
    								$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
								}
							}
							catch (Exception $e) {
								$modalContent = '<p>Object not found. E002</p>';
								$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
							}
							//$response["csrft"] = csrf_hash();
	    					//header('Content-Type: application/json');
	    					//echo json_encode($response);					
    					}
    					else {
    						$modalContent = '<p>Object not found. E003</p>';
    						$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					}
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    						
    				}
    				else {
    					$modalContent = '<p>Object not selected. E004</p>';
    					$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();	
    				}
    			}
    		}
	   }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function rminit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			exit();
    		}
    		else {
    			if(preg_match("/^[0-9]{1,11}$/",base64_decode($this->request->getPost('id')))){
    				$id = base64_decode($this->request->getPost('id'));
    				$Model = new ShellModel;
    				switch (session()->get("suser_groupe")) {
    					case '9':
    						$Results = $Model->where(['id' => $id])->find();
    					break;
    					
    					case '1':
    						$Results = $Model->where(['id' => $id, 'sellerid' => session()->get("suser_id"), 'selled' => '0'])->find();
    					break;
    				}
    				
    				$countResults = count($Results);
    				if($countResults == 1){
    					$modalContent = '<p>Do you realy wan to remove this item ?</p>';
    					$response["modal"] = createModal($modalContent, 'fade', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "1",['text' => 'Delete', 'functions' =>'data-api="rm-cpanel|'.base64_encode($Results[0]["id"]).'"']);
    				}
    				else {
    					$modalContent = '<p>Object not found. E002</p>';
    					$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    
    			}
    			else {
    				$modalContent = '<p>Object not selected. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();	
    			}
    
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function rm(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			exit();
    		}
    		else {
    			if(preg_match("/^[0-9]{1,11}$/",base64_decode($this->request->getPost('id')))){
    				$id = base64_decode($this->request->getPost('id'));
    				$Model = new ShellModel;
    				switch (session()->get("suser_groupe")) {
    					case '9':
    						$Results = $Model->where(['id' => $id])->find();
    					break;
    					
    					case '1':
    						$Results = $Model->where(['id' => $id, 'sellerid' => session()->get("suser_id"), 'selled' => '0'])->find();
    					break;
    				}				
    				$countResults = count($Results);
    				if($countResults == 1){
    					$Model->delete($Results[0]["id"]);
    					$sectionmodel = new SectionsModel;
    					$getInfo = $sectionmodel->where('identifier', '2')->findAll();
    					$newit = intval($getInfo[0]['itemsnumbers']) - 1;
    					$datanewit = [
    						'itemsnumbers' => $newit
    					];
    					$sectionmodel->update($getInfo[0]['id'], $datanewit);
    					$modalContent = '<p>Item Deleted.</p>';
    					$response["modal"] = createModal($modalContent, 'fade', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				else {
    					$modalContent = '<p>Object not found. E002</p>';
    					$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$modalContent = '<p>Object not selected. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();	
    			}
    
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function massinitEdit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			header('location:'.base_url().'/');
    			exit();
    		}
    		else {
    			$response = array();
    			$form = '
    				<form id="MasseditUserForm">
    					<div class="form-group row">
    						<div class="col-12">
    							<label>Price</label>
    							<input type="number" id="price" name="price" class="form-control">
    							<small class="price text-danger"></small>
    							<input type="hidden" name="'.csrf_token().'" value="'.csrf_hash().'">
    							<input type="hidden" name="id" value="">
    						</div>
    					</div>
    				</form>
    			'; 
    			$modalContent = $form;
    			$response["modal"] = createModal($modalContent, 'fade', 'Edit', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="massedits-cpanel"']);
    			$response["csrft"] = csrf_hash();
    			echo json_encode($response);
    			exit();
    			
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function massedit(){
		if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") !== "9" && session()->get("suser_groupe") !== "1"){
    			exit();
    		}
    		else {
    			$response = array();
				$ValidationRulls["price"] = array(
		            'rules'  => 'required|numeric',
		            'errors' => array(
		            	'numeric' => 'Invalid Price.',
		            	'required' => 'This Price is required.',
	            	)
	         	);

    			if(!$this->validate($ValidationRulls)){
    				$ErrorFields = $this->validator->getErrors();
    				$modalTitle = "Validation Error";
    				$modalContent = '';
    				foreach($ErrorFields as $key => $value){
    					$modalContent .= '<p class=""><b>'.$value.'</b></p>';
    				}	
    				$response["fieldslist"] = $ErrorFields;
    				$response["csrft"] = csrf_hash();
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$response = array();
    				//$idbs64 = base64_decode();
					$id = explode(',',$this->request->getPost('id'));
					//var_dump($id);
					//exit();
					$Model = new ShellModel;
					$susergroupe = session()->get('suser_groupe');
					$suserid = session()->get('suser_id');
					foreach($id as $t => $midss){
						$mids = base64_decode($midss);
						if(preg_match("/^[0-9]{1,50}$/",$mids)){
    						switch($susergroupe){
    							case '1' :
    								$Results = $Model->where(['id' => $mids, 'sellerid' => $suserid, 'selled' => '0'])->find();
    							break;
    							case '9' :
    								$Results = $Model->where(['id' => $mids, 'selled' => '0'])->find();
    							break;	
    						}
    						$countResults = count($Results);
    						if($countResults == 1){
    							$data = [];
    							foreach($this->request->getPost() as $key => $val){
    								if($val != ""){
    								    $data[$key] = $val;
    								}	
    							}
    							$Request = $Model->update($mids, $data);
    							$modalContent = '<p>Cpanel Updateded successfully</p>';
    							$modalTitle = 'Edit succefull';
    							$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
    						}
    						else {
    							$modalContent = '<p>Object not found. E002</p>';
    							$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    						}
						}
						else {
							$modalContent = '<p>Object not selected. E008</p>';
	    					$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
	    					$response["csrft"] = csrf_hash();
						}
					}
					$response["csrft"] = csrf_hash();
					echo json_encode($response);
    			}
    		}
        }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function massrmuserinit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			exit();
    		}
    		else {
				$id = explode(',',$this->request->getPost('id'));
				foreach($id as $m => $idss){
					$ids = base64_decode($idss);
				    if(!preg_match("/^[0-9]{1,50}$/i", $ids)){
				        $modalContent = '<p>Object not selected. E003</p>';
        				$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
        				$response["csrft"] = csrf_hash();
        				echo json_encode($response);
        				exit();	
				    }
				}
				$countResults = count($id);
				if($countResults >= 2){
					$modalContent = '<form id="MassDeleteForm"></form><p>Do you realy wan to remove those items ?</p>';
					$response["modal"] = createModal($modalContent, 'fade', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "1",['text' => 'Delete', 'functions' =>'data-api="massrms-cpanel"']);
				}
				else {
					$modalContent = '<p>Object not found. E002</p>';
					$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
				}
				$response["csrft"] = csrf_hash();
				header('Content-Type: application/json');
				echo json_encode($response);
    
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function massrm(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			exit();
    		}
    		else {
    			
    				$id = explode(',',$this->request->getPost('id'));
    				$Model = new ShellModel;
    				$susergroupe = session()->get("suser_groupe");
    				$suserid = session()->get("suser_id");
    				foreach($id as $m => $idss){
    					$ids = base64_decode($idss);
    				    if(preg_match("/^[0-9]{1,50}$/i", $ids)){
        					switch($susergroupe){
        						case '1' :
        							$Results = $Model->where(['id' => $ids, 'sellerid' => $suserid])->find();
        						break;
        						case '9' :
        							$Results = $Model->where(['id' => $ids])->find();
        						break;	
        					}
    				    }
    				    else {
            				$modalContent = '<p>Object not selected. E003</p>';
            				$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
            				$response["csrft"] = csrf_hash();
            				echo json_encode($response);
            				exit();	
            			}
    					$countResults = count($Results);
    					if($countResults == 1){
    						$Model->delete($Results[0]["id"]);
    						$usermodel = new UsersModel;
    						$getSeller = $usermodel->where(['id' => session()->get('suser_id')])->findAll();
    						if(count($getSeller) == 1){
    							$newNb = $getSeller[0]['seller_nbobjects']-1;
    							$dataseller = [
    								'seller_nbobjects' => $newNb
    							];
    							$usermodel->update($getSeller[0]['id'], $dataseller);
    						}
    
    						$ModelSection = new SectionsModel;
    						$sectionItems = $ModelSection->where(['identifier' => '2'])->findAll();
    						if(count($sectionItems) == 1 ){
    							$newsectionItems = $sectionItems[0]['itemsnumbers']-1;
    							$MysdataItemsSection = [
    								'itemsnumbers' => $newsectionItems
    							];
    
    							$secid = $sectionItems[0]['id'];
    							$ModelSection->update($secid, $MysdataItemsSection);
    						}
    
    						$modalContent = '<p>Item Deleted.</p>';
    						$response["modal"] = createModal($modalContent, 'fade', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					}
    					else {
    						$modalContent = '<p>Object not found. E002</p>';
    						$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					}
    				}					
    				$response["csrft"] = csrf_hash();
    				echo json_encode($response);
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}
}

