<?php
namespace App\Controllers;
use App\Models\SmtpExtendedModel;
use App\Models\SmtpModel;
use App\Models\UsersModel;
use App\Models\SectionsModel;
use App\Models\NotificationsModel;
use monken\TablesIgniter;
class Smtp extends BaseController
{
	private function sectionControl(){
		if(verifysection('4') != null){
    		$verify = verifysection('4');
	    	if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9'){
	    		header('location:'.base_url());
	    		exit();
	    	}
	    	else if($verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9') {
	    		$view = 'maintenance';
	    		return ['view' => $view, 'sellersactivate' => 0];
	    		
	    	}
	    	else {
	    		$view = 'smtp';
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
			$modelCards = new SmtpModel;
			$Res = $modelCards->where(['selled' => '0', 'refunded' => '0'])->orderBy('id', 'RANDOM')->findAll();
			$data['allcards'] = $Res;
			$data["nbitemscart"] = $mycart[0];
			$data["cartInnerHtml"] = $mycart[1];
			$data["settings"] = $settings;
			$data["sectionName"] = "Smtp's";
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
	
	public function fetchTable(){
	    if($this->request->isAJAX()){
    		if(session()->get("logedin") == '1'){
    			$model = new SmtpExtendedModel();
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
			$Smtpmodel = new SmtpModel;
			$Total = count($ids);
			$x = 0;
			$good = 0;
			$bad = 0;
	    	foreach($ids as $vals){
	    		$valsarray = explode('-', $vals);
	    		$val = $valsarray[0];
	    		$x++;
	    		if(preg_match("/^([0-9])+$/i", $val) == true){
	    			$getProductInfos = $Smtpmodel->where(["id" => $val, ])->findAll();
	    			if(count($getProductInfos) > 0){
	    				$Checkresults = [];
						$doCheckRequ = smtpchecker($getProductInfos[0]['host'], $getProductInfos[0]['port'],$getProductInfos[0]['user'], $getProductInfos[0]['pass']);
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
							//$SmtpModel->delete($getProductInfos[0]['id']);
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
	    	/**if($refundsum > 0){
	    		$newUserBalance = $getUserInfo[0]['balance'] + $refundsum;
				$upDatedUserBalance = [
					'balance' => $newUserBalance,
				];
				$userModel->update($getUserInfo[0]['id'], $upDatedUserBalance);
				$sectionModel = new SectionsModel;
				$getSectionInfo = $sectionModel->where('identifier', '1')->findAll();
				$newrev = intval($getSectionInfo[0]["sectionrevenue"]) -  intval($refundsum);
				$datanewrev = [
					'sectionrevenue' => $newrev
				];
				$sectionModel->update($getSectionInfo[0]['id'], $datanewrev);
	    	}**/
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
    				$Model = new SmtpModel;
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
    										<div class="col-6">
    											<label>Host<i class="text-danger"> *</i></label>
    											<input type="text" id="host" name="host" class="form-control" value="'.$Results[0]['host'].'">
    											<small class="host text-danger"></small>
    										</div>
    										<div class="col-6">
    											<label>Port<i class="text-danger"> *</i></label>
    											<input type="text" id="port" name="port" class="form-control" value="'.$Results[0]['port'].'">
    											<small class="port text-danger"></small>
    										</div>
    									</div>
    									<div class="form-group row">
    										<div class="col-6">
    											<label>User name<i class="text-danger"> *</i></label>
    											<input type="text" id="user" name="user" class="form-control" value="'.$Results[0]['user'].'">
    											<small class="user text-danger"></small>
    										</div>
    										<div class="col-6">
    											<label>Password<i class="text-danger"> *</i></label>
    											<input type="text" id="pass" name="pass" class="form-control" value="'.$Results[0]['pass'].'">
    											<small class="pass text-danger"></small>
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
    							$response["modal"] = createModal($modalContent, 'fade  ', 'Edit', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="edititem-editsmtp|'.$Results[0]['id'].'"']);
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
    		            'rules'  => 'required|regex_match[/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$|^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$|^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z0-9]+(-[a-z0-9]+)*\.[a-z]{2,}$/]',
    		            'errors' => [
    		            	'required' => 'Insert Host.',
    		            	'regex_match' => 'A valid Host is required.',
    	            	]
    	            ],
    	            'port' => [
    	            	'label' => 'Port',
    		            'rules'  => 'required|regex_match[/^[0-9]{2,11}$/]',
    		            'errors' => [
    		            	'required' => 'Insert Username.',
    		            	'regex_match' => 'Invalid Username input.'
    	            	]
    	            ],
    	            'user' => [
    	            	'label' => 'Username',
    		            'rules'  => 'required|regex_match[/^[a-zA-Z0-9]([a-zA-Z0-9-]{0,30}[a-zA-Z0-9])?$/]',
    		            'errors' => [
    		            	'required' => 'Insert Username.',
    		            	'regex_match' => 'Invalid Username input.'
    	            	]
    	            ],
    	            'pass' => [
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
    					$Model = new SmtpModel;
    					$Results = $Model->where(['id' => $id, 'selled' => 0])->find();
    					$countResults = count($Results);
    					if($countResults == 1){

    						if(filter_var($this->request->getPost('host'), FILTER_VALIDATE_IP) === false){
								$GetCpanelip =  gethostbyname($this->request->getPost('host'));
							}
							else {
								$GetCpanelip =  $this->request->getPost('host');
							}
							if(filter_var($GetCpanelip, FILTER_VALIDATE_IP) && $GetCpanelip != '127.0.0.1'){
						        $json = file_get_contents('https://api.ipregistry.co/'.$GetCpanelip.'?key=pf0f4w9q20rzwwoz' ,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	        					$Infosmyip = json_decode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	        					$data['host'] = $this->request->getPost('host');
	        					$data['user'] = $this->request->getPost('user');
	        					$data['pass'] = $this->request->getPost('pass');
	        					$data['port'] = $this->request->getPost('port');
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
    				$Model = new SmtpModel;
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
    					$response["modal"] = createModal($modalContent, 'fade', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "1",['text' => 'Delete', 'functions' =>'data-api="rm-smtp|'.base64_encode($Results[0]["id"]).'"']);
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
    				$Model = new SmtpModel;
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
    					$getInfo = $sectionmodel->where('identifier', '4')->findAll();
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
    			$response["modal"] = createModal($modalContent, 'fade', 'Edit', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="massedits-smtp"']);
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
					$Model = new smtpModel;
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
    							$modalContent = '<p>Rdp Updateded successfully</p>';
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
					$response["modal"] = createModal($modalContent, 'fade', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "1",['text' => 'Delete', 'functions' =>'data-api="massrms-smtp"']);
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
    				$Model = new SmtpModel;
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
    						$sectionItems = $ModelSection->where(['identifier' => '4'])->findAll();
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

