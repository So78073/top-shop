<?php
namespace App\Controllers;
use App\Models\CardsModel;
use App\Models\CpanelModel;
use App\Models\RdpModel;
use App\Models\SmtpModel;
use App\Models\UsersModel;
use App\Models\MyitemsModel;
use App\Models\PayementsModel;
use App\Models\WithdrawrequestsModel;
use App\Models\NotificationsModel;
use App\Models\BanksModel;
use App\Models\SectionsModel;
use App\Models\CodesModel;
class Finances extends BaseController {
	public function withdrawinit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") == '1' ){
    			$settings = fetchSettings();
    			$usermodel = new UsersModel;
    			$Results = $usermodel->where('id', session()->get("suser_id") )->findAll();
    			if(count($Results) == 1){
    			    if($Results[0]['btcaddress'] !==""){
    			        if($Results[0]['seller_balance'] > 0 ){
        					if($Results[0]['withdrawstatus'] == 0){
        						$sellerBalance = $Results[0]['seller_balance'];
        						$sellerRate = $Results[0]['seller_fees'];
        						$globalrate = $settings[0]['sellerate'] ;
        						if($globalrate == $sellerRate){
        						    $feespercent = $globalrate;
        						    $BalanceFees = $sellerBalance * $globalrate / 100 ;
        						    $BalanceToWithdraw = $sellerBalance - $BalanceFees ;    
        						}
        						else {
        						    $feespercent = $sellerRate;
        						    $BalanceFees = $sellerBalance * $sellerRate / 100 ;
        						    $BalanceToWithdraw = $sellerBalance - $BalanceFees ;
        						}
        						$modalContent = '
        							<form id="">
        								<div class="row">
        									<div class="col-md-12">
        										<p>Total Balance : $'.number_format(esc($sellerBalance), 2, '.', '').'</p>
        										<p>Market Fees : $'.number_format($BalanceFees, 2, '.', '').' ('.$feespercent.'%)</p>
        										<p>Final withdrawal amount : $'.number_format($BalanceToWithdraw, 2, '.', '').'</p>
        										<p>BTC Address: '.esc($Results[0]['btcaddress']).'</p>
        									</div>
        								</div>
        							</form>
        						';
        						$response["modal"] = createModal($modalContent, ' fade', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Withdraw', 'functions' => 'data-api="confirmWithdraw"']);
        						$response["csrft"] = csrf_hash();
        						header('Content-Type: application/json');
        						echo json_encode($response);
        						exit();
        					}
        					else{
        						$modalContent = '
        							<form id="">
        								<div class="row">
        									<div class="col-md-12">
        										<p>You alrady have a withdraw request in review.</p>
        									</div>
        								</div>
        							</form>
        						';
        						$response["modal"] = createModal($modalContent, 'fade', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
        						$response["csrft"] = csrf_hash();
        						header('Content-Type: application/json');
        						echo json_encode($response);
        						exit();
        					}	
        				}
        				else {
        					$modalContent = '
        						<form id="">
        							<div class="row">
        								<div class="col-md-12">
        									<p>Your Total Balance : $0, so you cannot withdraw.</p>
        								</div>
        							</div>
        						</form>
        					';
        					$response["modal"] = createModal($modalContent, 'fade', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
        					$response["csrft"] = csrf_hash();
        					header('Content-Type: application/json');
        					echo json_encode($response);
        					exit();
        				}	
    			    }
    			    else {
    					$modalContent = '
    						<form id="">
    							<div class="row">
    								<div class="col-md-12">
    									<p>Please setup your BTC address <a href="/profile">HERE</a></p>
    								</div>
    							</div>
    						</form>
    					';
    					$response["modal"] = createModal($modalContent, 'fade', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}		
        					
    			}
    		}
    		else {
    			header('location:'.base_url().'/');
    			exit();
    		}
	    }
		else {
	        echo "Nice try ;)";
	        exit();
	    }	
	}

	public function withdrawConfirm(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") == '1' ){
    			$settings = fetchSettings();
    			$usermodel = new UsersModel;
    			$Results = $usermodel->where('id', session()->get("suser_id") )->findAll();
    			if(count($Results) == 1){
    				if($Results[0]['seller_balance'] > 0 ){
    					if($Results[0]['withdrawstatus'] == 0){
    						$sellerBalance = $Results[0]['seller_balance'];
    						$sellerRate = $Results[0]['seller_fees'];
    						$BalanceFees = $sellerBalance * $sellerRate / 100 ;
    						$BalanceToWithdraw = $sellerBalance - $BalanceFees ;
    						
    						$userdata = [
    							'withdrawstatus' => 1,
    							'withdrawinhold' => $BalanceToWithdraw
    						];
    
    						$usermodel->update($Results[0]['id'], $userdata);
    
    						$withdrawinfodata = [
    							"sum" => $BalanceToWithdraw,
    							"userid" => $Results[0]['id'],
    							"username" => $Results[0]['username'],
    							"userwallet" => $Results[0]['btcaddress'],
    							"originalsum" => $Results[0]['seller_balance'],
    							"status" => '1'
    						];
    						$withmod  = new WithdrawrequestsModel;
    						$withmod->save($withdrawinfodata);
    						$ResAdmins = $usermodel->where('groupe', '9')->findAll();
    						foreach ($ResAdmins as $res => $admin) {
    							$dataNotif = [
    								'subject' => 'Request',
    								'text' => 'New Withdraw request was posted.',
    								'url' => base_url().'/sellerrequests',
    								'userid' => $admin['id']
    							];
    							$modelNotif = new NotificationsModel;
    							$modelNotif->save($dataNotif);
    
    							$adminNewNotif = $admin['notifications_nb']+1;
    							$dataadmin = [
    								'notifications_nb' => $adminNewNotif
    							];
    							$usermodel->update($admin['id'], $dataadmin);
    						}
    						$modalContent = '
    							<form id="">
    								<div class="row">
    									<div class="col-md-12">
    										<p>You Withdraw request was successful posted.</p>
    									</div>
    								</div>
    							</form>
    						';
    						$response["modal"] = createModal($modalContent, 'fade', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
    						$response["csrft"] = csrf_hash();
    						header('Content-Type: application/json');
    						echo json_encode($response);
    						exit();	
    					}
    					else{
    						$modalContent = '
    							<form id="">
    								<div class="row">
    									<div class="col-md-12">
    										<p>You alrady have a withdraw request in review.</p>
    									</div>
    								</div>
    							</form>
    						';
    						$response["modal"] = createModal($modalContent, 'fade', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
    						$response["csrft"] = csrf_hash();
    						header('Content-Type: application/json');
    						echo json_encode($response);
    						exit();
    					}	
    				}
    				else {
    					$modalContent = '
    						<form id="">
    							<div class="row">
    								<div class="col-md-12">
    									<p>Your Total Balance : $0, so you cannot withdraw.</p>
    								</div>
    							</div>
    						</form>
    					';
    					$response["modal"] = createModal($modalContent, 'fade', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}		
    			}
    		}
    		else {
    			header('location:'.base_url().'/');
    			exit();
    		}
	    }
		else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function depoinit(){
	    if($this->request->isAJAX()){
    		if(session()->get("logedin") == '1'){
    			$settings = fetchSettings();
    			$initcryptos = $settings[0]['nowpaymentaccept'];
    			$mindepos = $settings[0]['mindepo'];
    			$cryptos = explode(',', $initcryptos);
    			$select = '<select class="form-control select2" name="crypto">';
    			foreach ($cryptos as $key => $value) {
    				$select .= '<option value="'.$value.'">'.strtoupper($value).'</option>';
    			}
    			$select .= '</select>';
    			$modalContent = '
    				<form id="depoForm">
    					<div class="row">
    						<div class="form-group col-12">
    							<label>Select Currency</label>
    							'.$select.'
    						</div>
    						<div class="form-group col-12">
    
    							<label>Minimum $'.number_format($mindepos,2,'.','').'</label>
    							<input type="number" class="form-control" name="depoammount" id="depoammount" placeholder="'.$mindepos.'">
    							<input type="hidden" name="'.csrf_token().'" value="'.csrf_hash().'">
    						</div>
    					</div>
    				</form>
    				<script>
    				$(\'select\').select2({
    					width: "100%",
    					dropdownParent: $("#bsModal")
    			    });
    				</script>
    			';
    			$response["modal"] = createModal($modalContent, 'fade', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Deposit', 'functions' => 'data-api="depoconfirm"']);
    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($response);
    			exit();
    		}
    		else {
    			header('location:'.base_url().'/');
    			exit();
    		}
	    }
		else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function depoconfirm(){
	    if($this->request->isAJAX()){
            $settings = fetchSettings();
            $mindepos = $settings[0]['mindepo'];
    		$mresponse = array();
    		$ValidationRulls = [
        		'depoammount' => [
		            'label'  => 'Deposit amount',
		            'rules'  => 'required|numeric|greater_than_equal_to['.$mindepos.']',
		            'errors' => [
		            	'required' => 'Invalid Amount.',
		                'numeric' => 'Invalid Amount.',
		                'greater_than_equal_to' =>'Invalid Amount.'
		            ]
		        ],
		        'crypto' => [
		            'label'  => 'Crypto',
		            'rules'  => 'required|regex_match[/^[a-zA-Z0-9]{3,10}/]',
		            'errors' => [
		            	'required' => 'Invalid Currency.',
		                'numeric' =>  'Invalid Currency.',
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
	    		$crypto = $this->request->getPost("crypto");
	    		$deposammount = $this->request->getPost('depoammount');
	    		$api = 'FC9QD85-40Q47DH-KQ1Y2FZ-91TX6A0';
	    		//$api = $settings[0]['nowpayementapikey'];
    			$curl = curl_init();
    			curl_setopt_array($curl, array(
    			  CURLOPT_URL => 'https://api.nowpayments.io/v1/payment',
    			  CURLOPT_RETURNTRANSFER => true,
    			  CURLOPT_ENCODING => '',
    			  CURLOPT_MAXREDIRS => 10,
    			  CURLOPT_TIMEOUT => 0,
    			  CURLOPT_FOLLOWLOCATION => true,
    			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    			  CURLOPT_CUSTOMREQUEST => 'POST',
    			  CURLOPT_POSTFIELDS =>'{
    			  "price_amount": '.$deposammount.',
    			  "price_currency": "usd",
    			  "pay_currency": "'.strtolower($crypto).'",
    			  "ipn_callback_url": "'.base_url().'/hook",
    			  "order_id": "RGDBP-'.rand(100,10000).'",
    			  "order_description": "Depo"
    			}',
    			  CURLOPT_HTTPHEADER => array(
    			    'x-api-key: '.$api,
    			    'Content-Type: application/json'
    			  ),
    			));
    			$curlexe = curl_exec($curl);
    			curl_close($curl);
    
    			$data = json_decode($curlexe , true);
    			$data['userid'] = session()->get('suser_id');
    			$data['username'] = session()->get('suser_username');
    			$payementsmodel = new PayementsModel;
    			if(isset($data['pay_currency']) && !empty($data['pay_currency'])){
    				switch ($data['pay_currency']) {
    					case 'btc':
    						$mcur = 'bitcoin';
    					break;
    					case 'ltc':
    						$mcur = 'litecoin';
    					break;
    					
    					default:
    						$mcur = $data['pay_currency'];
    					break;
    				}
    				$modalContent = '
    						<div class="card">
    							<div class="row g-0">
    								<div class="col-md-4">
    									<img src="https://api.qrserver.com/v1/create-qr-code/?size=290x290&data='.$mcur.':'.$data['pay_address'].'" class="img-fluid rounded-start" alt="QR">
    								</div>
    								<div class="col-md-8">
    									<div class="card-body">
    										<p style="font-size:16px; font-weight:500; text-align:center ;" class="card-text">Please send <span class="text-success">'.$data['pay_amount'].' '.strtoupper($data['pay_currency']).'</span></p>
    										<p style="font-size:16px; font-weight:500; text-align:center ;" class="card-text">Address: <b><span style="color:red">'.$data['pay_address'].'</span></b></p>
    										<p style="font-size:16px; font-weight:500; text-align:center ;" class="card-text">To verify your deposit go to <a class="btn btn-sm btn-success" href="'.base_url().'/history">History</a></p>
    									</div>
    								</div>
    							</div>
    						</div>';
    				$mresponse["modal"] = createModal($modalContent, 'fade animated', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
    				$payementsmodel->save($data);
    				$mresponse["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($mresponse);
    				exit();
    			}
    			else {
    				$modalContent = '<p>Error while trying to get an address, please try again with another amount.<p>';
    	    		$response["modal"] = createModal($modalContent, 'fade', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
    				$response["csrft"] = csrf_hash();
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

	public function addtoCart(){
	    if($this->request->isAJAX()){
    		$response = array();
    		if(session()->get("logedin") == '1'){
    			if(preg_match("/^([0-9])+$/i",$this->request->getPost("id"))){
    				$id = $this->request->getPost("id");
    				if(count(session()->get('cart')) > 0){
        	            foreach (session()->get('cart') as $cartKey => $cartValue){
            	            if(array_key_exists('id', $cartValue) && array_key_exists('typebuying', $cartValue)){
            	                if($cartValue['id'] == $id && $cartValue['typebuying'] == $this->request->getPost("buytype")){
            	                    $html = getCart();
				        			$response["html"] = $html[1];
				        			$response["signal"] = $html[2];
				    				$response["csrft"] = csrf_hash();
						    		header('Content-Type: application/json');
						    		echo json_encode($response);
						    		exit();
            	                }
            	            }
            	        }
            	    }
    				switch ($this->request->getPost("buytype")) {
    					case '1':
    						$model = new CardsModel;
    						$Results = $model->where(['id' => $id, 'selled' => '0'])->find();
    						$typebuying = "1";
    						$countResults = count($Results);
    						if($countResults > 0){
    							if(session()->get('suser_id') == $Results[0]['sellerid']){
    								//$error = "You cannot buy your items.";
    								$html = getCart();
				        			$response["html"] = $html[1];
				        			$response["signal"] = $html[2];
    							}
    							else {
    								$prod = [
    									'id' => $id,
    									'type' => $Results[0]["scheme"].' '.substr($Results[0]["number"], 0,6) ,
    									'price' => '$'.number_format($Results[0]["price"],2,'.',''),
    									'icon' => 'credit-card',
    									'typebuying' => '1',
    								];
    								$cart = array($prod);
    								session()->push('cart', $cart);
    								$html = getCart();
                        			$response["html"] = $html[1];
                        			$response["signal"] = $html[2];
    							}
    
    						}
    						else {
    							$html = getCart();
			        			$response["html"] = $html[1];
			        			$response["signal"] = $html[2];
    						}
    					break;
    					case '2':
    						$model = new CpanelModel;
    						$Results = $model->where(['id' => $id, 'selled' => '0'])->find();
    						$typebuying = "2";
    						$countResults = count($Results);
    						if($countResults > 0){
    							if(session()->get('suser_id') == $Results[0]['sellerid']){
    								$html = getCart();
				        			$response["html"] = $html[1];
				        			$response["signal"] = $html[2];
    							}
    							else {
    								$prod = [
    									'id' => $id,
    									'type' => 'Cpanel '.ucfirst($Results[0]["hoster"]),
    									'price' => '$'.number_format($Results[0]["price"],2,'.',''),
    									'icon' => 'cart',
    									'typebuying' => '2',
    								];
    								$cart = array($prod);
    								session()->push('cart', $cart);
    								$html = getCart();
                        			$response["html"] = $html[1];
                        			$response["signal"] = $html[2];
    							}
    						}
    						else {
    							$html = getCart();
			        			$response["html"] = $html[1];
			        			$response["signal"] = $html[2];
    						}
    					break;
    					case '3':
    						$model = new RdpModel;
    						$Results = $model->where(['id' => $id, 'selled' => '0'])->find();
    						$typebuying = "3";
    						$countResults = count($Results);
    						if($countResults > 0){
    							if(session()->get('suser_id') == $Results[0]['sellerid']){
    								$html = getCart();
				        			$response["html"] = $html[1];
				        			$response["signal"] = $html[2];
    							}
    							else {
    								$prod = [
    									'id' => $id,
    									'type' => 'Rdp '.ucfirst($Results[0]["hoster"]),
    									'price' => '$'.number_format($Results[0]["price"],2,'.',''),
    									'icon' => 'cart',
    									'typebuying' => '3',
    								];
    								$cart = array($prod);
    								session()->push('cart', $cart);
    								$html = getCart();
                        			$response["html"] = $html[1];
                        			$response["signal"] = $html[2];
    							}
    						}
    						else {
    							$html = getCart();
			        			$response["html"] = $html[1];
			        			$response["signal"] = $html[2];
    						}
    					break;
    					case '4':
    						$model = new SmtpModel;
    						$Results = $model->where(['id' => $id, 'selled' => '0'])->find();
    						$typebuying = "4";
    						$countResults = count($Results);
    						if($countResults > 0){
    							if(session()->get('suser_id') == $Results[0]['sellerid']){
    								$html = getCart();
				        			$response["html"] = $html[1];
				        			$response["signal"] = $html[2];
    							}
    							else {
    								$prod = [
    									'id' => $id,
    									'type' => 'Smtp '.ucfirst($Results[0]["hoster"]),
    									'price' => '$'.number_format($Results[0]["price"],2,'.',''),
    									'icon' => 'cart',
    									'typebuying' => '4',
    								];
    								$cart = array($prod);
    								session()->push('cart', $cart);
    								$html = getCart();
                        			$response["html"] = $html[1];
                        			$response["signal"] = $html[2];
    							}
    						}
    						else {
    							$html = getCart();
			        			$response["html"] = $html[1];
			        			$response["signal"] = $html[2];
    						}
    					break;
    					default :
    						$model = new SectionsModel;
    						$typebuying = strtolower($this->request->getPost("buytype"));
    						$GetIfSection = $model->where('`sectioname`' , $typebuying )->findAll();
    						if(count($GetIfSection) == '1'){
    							$db = db_connect();
    			    			$GetSectionConfigs = $db->query("SELECT * FROM `section_".strtolower($GetIfSection[0]['sectioname'])."` WHERE `id`='".$id."' AND `selled`='0'");
    			    			$resultsSection = $GetSectionConfigs->getResultArray();
    							$countResults = count($resultsSection);
    							if($countResults > 0){
    								$prod = [
    									'id' => $id,
    									'type' => $GetIfSection['0']['sectionlable'],
    									'price' => '$'.number_format($resultsSection[0]["price"],2,'.',''),
    									'icon' => $GetIfSection['0']['sectionicon'],
    									'typebuying' => $typebuying,
    								];
    								$cart = array($prod);
    								session()->push('cart', $cart);
    								$html = getCart();
                        			$response["html"] = $html[1];
                        			$response["signal"] = $html[2];
    								$response["message"] = "Item added to cart.";
    								$response["typemsg"] = "success";
    							}
    							else {
    								$response["message"] = "Error E101.";
    								$response["typemsg"] = "danger";
    								$response["html"] = '';
    							}
    						}
    						else {
    							$response["message"] = "Error E101.";
    							$response["typemsg"] = "danger";
    							$response["html"] = '';
    						}
    							
    					break;
    				}
    			}
    			else {
    				$response["message"] = "Error E102.";
    				$response["typemsg"] = "danger";
    				$response["html"] = '';
    			}
    		}
    		else {
    			$response["message"] = "Error E103.";
    			$response["typemsg"] = "danger";
    			$response["html"] = '';
    		}
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
	
	public function addtoBunchCart(){
		//var_dump($this->request->getPost('ids'), true);
	    if($this->request->isAJAX()){
    		$response = array();
    		if(session()->get("logedin") == '1'){
    			if(count(json_decode($this->request->getPost('ids'), true)) <= 0){
    				$html = getCart();
        			$response["html"] = $html[1];
        			$response["signal"] = $html[2];
    				$response["csrft"] = csrf_hash();
		    		header('Content-Type: application/json');
		    		echo json_encode($response);
		    		exit();
    			}
    		    $idsarray = json_decode($this->request->getPost('ids'), true);
    		    foreach($idsarray as $k => $postVal){
    		    	if(strpos($postVal, '-')){
    		    		$PostParts = explode('-', $postVal);
    		    	}
    		    	else {
						$html = getCart();
            			$response["html"] = $html[1];
            			$response["signal"] = $html[2];
    		    	}
    		        if(preg_match("/^([0-9])+$/i",str_replace(' ' ,'',$PostParts['0'])) 
		        	&& preg_match("/^([a-zA-Z0-9])+$/i",str_replace(' ' ,'',$PostParts['1']))){
        				$id = $PostParts['0'];
        				if(count(session()->get('cart')) > 0){
            	            foreach (session()->get('cart') as $cartKey => $cartValue){
                	            if(array_key_exists('id', $cartValue) && array_key_exists('typebuying', $cartValue)){
                	                if($cartValue['id'] == $id && $cartValue['typebuying'] == $PostParts['1']){
                	                    $html = getCart();
                            			$response["html"] = $html[1];
                            			$response["signal"] = $html[2];
                	                }
                	            }
                	        }
                	    }
        				switch ($PostParts['1']) {
        					case '1':
        						$model = new CardsModel;
        						$Results = $model->where(['id' => $id, 'selled' => '0'])->find();
        						$typebuying = "1";
        						$countResults = count($Results);
        						if($countResults > 0){
        							if(session()->get('suser_id') == $Results[0]['sellerid']){
        								$html = getCart();
                            			$response["html"] = $html[1];
                            			$response["signal"] = $html[2];
        							}
        							else {
        								$prod = [
        									'id' => $id,
        									'type' => $Results[0]["scheme"].' '.substr($Results[0]["number"], 0,6) ,
        									'price' => '$'.number_format($Results[0]["price"],2,'.',''),
        									'icon' => 'credit-card',
        									'typebuying' => '1',
        								];
        								$cart = array($prod);
        								session()->push('cart', $cart);
        								$html = getCart();
                            			$response["html"] = $html[1];
                            			$response["signal"] = $html[2];
        							}
        						}
        						else {
    								$html = getCart();
                        			$response["html"] = $html[1];
                        			$response["signal"] = $html[2];
        						}
        					break;
        					case '2':
        						$model = new CpanelModel;
        						$Results = $model->where(['id' => $id, 'selled' => '0'])->find();
        						$typebuying = "2";
        						$countResults = count($Results);
        						if($countResults > 0){
        							if(session()->get('suser_id') == $Results[0]['sellerid']){
        								$html = getCart();
                            			$response["html"] = $html[1];
                            			$response["signal"] = $html[2];
        							}
        							else {
        								$prod = [
        									'id' => $id,
        									'type' => 'Cpanel '.ucfirst($Results[0]["hoster"]),
        									'price' => '$'.number_format($Results[0]["price"],2,'.',''),
        									'icon' => 'cart',
        									'typebuying' => '2',
        								];
        								$cart = array($prod);
        								session()->push('cart', $cart);
        								$html = getCart();
                            			$response["html"] = $html[1];
                            			$response["signal"] = $html[2];
        							}
        						}
        						else {
    								$html = getCart();
                        			$response["html"] = $html[1];
                        			$response["signal"] = $html[2];
        						}
        					break;
        					case '3':
        						$model = new RdpModel;
        						$Results = $model->where(['id' => $id, 'selled' => '0'])->find();
        						$typebuying = "3";
        						$countResults = count($Results);
        						if($countResults > 0){
        							if(session()->get('suser_id') == $Results[0]['sellerid']){
        								$html = getCart();
                            			$response["html"] = $html[1];
                            			$response["signal"] = $html[2];
        							}
        							else {
        								$prod = [
        									'id' => $id,
        									'type' => 'Rdp '.ucfirst($Results[0]["hoster"]),
        									'price' => '$'.number_format($Results[0]["price"],2,'.',''),
        									'icon' => 'cart',
        									'typebuying' => '3',
        								];
        								$cart = array($prod);
        								session()->push('cart', $cart);
        								$html = getCart();
                            			$response["html"] = $html[1];
                            			$response["signal"] = $html[2];
        							}
        						}
        						else {
    								$html = getCart();
                        			$response["html"] = $html[1];
                        			$response["signal"] = $html[2];
        						}
        					break; 
        					case '4':
        						$model = new SmtpModel;
        						$Results = $model->where(['id' => $id, 'selled' => '0'])->find();
        						$typebuying = "4";
        						$countResults = count($Results);
        						if($countResults > 0){
        							if(session()->get('suser_id') == $Results[0]['sellerid']){
        								$html = getCart();
                            			$response["html"] = $html[1];
                            			$response["signal"] = $html[2];
        							}
        							else {
        								$prod = [
        									'id' => $id,
        									'type' => 'Smtp '.ucfirst($Results[0]["hoster"]),
        									'price' => '$'.number_format($Results[0]["price"],2,'.',''),
        									'icon' => 'cart',
        									'typebuying' => '4',
        								];
        								$cart = array($prod);
        								session()->push('cart', $cart);
        								$html = getCart();
                            			$response["html"] = $html[1];
                            			$response["signal"] = $html[2];
        							}
        						}
        						else {
    								$html = getCart();
                        			$response["html"] = $html[1];
                        			$response["signal"] = $html[2];
        						}
        					break;    					
        					/**default :
        						$model = new SectionsModel;
        						$typebuying = strtolower($PostParts[1]);
        						$GetIfSection = $model->where('`sectioname`' , $typebuying )->findAll();
        						if(count($GetIfSection) == '1'){
        							$db = db_connect();
        			    			$GetSectionConfigs = $db->query("SELECT * FROM `section_".$db->escapeString(strtolower($GetIfSection[0]['sectioname']))."` WHERE `id`='".$id."' AND `selled`='0'");
        			    			$resultsSection = $GetSectionConfigs->getResultArray();
        							$countResults = count($resultsSection);
        							if($countResults > 0){
        								$prod = [
        									'id' => $id,
        									'type' => $typebuying ,
        									'price' => '$'.number_format($resultsSection[0]["price"],2,'.',''),
        									'icon' => 'cart',
        									'typebuying' => $typebuying,
        								];
        								$cart = array($prod);
        								session()->push('cart', $cart);
        								$html = getCart();
                            			$response["html"] = $html[1];
                            			$response["signal"] = $html[2];
        								$response["message"] = "Item added to cart.";
        								$response["typemsg"] = "success";
        								/**$response["html"] = '<a class="dropdown-item " href="javascript:;">
        										<div class="d-flex align-items-center">
        											<div class="notify bg-light-primary text-primary"><i class="bx bx-credit-card"></i>
        											</div>
        											<div class="flex-grow-1">
        												<h6 class="msg-name">'.esc(ucfirst($prod["type"])).' <span data-api="removeCartProd-'.$id.'|'.esc($typebuying).'" class="msg-time float-end '.$id.'-'.esc($typebuying).'">Remove</span>
        												</h6>
        												<p class="msg-info">'.esc($prod["price"]).'</p>
        											</div>
        										</div>
        									</a>';/
        							}
        							else {
        								$response["message"] = "Error E101.";
        								$response["typemsg"] = "danger";
        								$response["html"] = '';
        							}
        						}
        						else {
        							$response["message"] = "Error E101.";
        							$response["typemsg"] = "danger";
        							$response["html"] = '';
        						}
        							
        					break;**/
        				}
                    }
        			else {
        				$html = getCart();
            			$response["html"] = $html[1];
            			$response["signal"] = $html[2];
                    }    
    		    }
    		    $response["csrft"] = csrf_hash();
	    		header('Content-Type: application/json');
	    		echo json_encode($response);
	    		exit();
    		}
    		else {
    			$html = getCart();
    			$response["html"] = $html[1];
    			$response["signal"] = $html[2];
				$response["csrft"] = csrf_hash();
	    		header('Content-Type: application/json');
	    		echo json_encode($response);
	    		exit();
    		}
	    }
		else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function clearCart(){
	    if($this->request->isAJAX()){
    		$response = array();
    		if(session()->get("logedin") == '1'){
    			if(count(session()->get('cart')) > 0){
    				session()->set('cart', []);
    				$cart = getCart();
    				if($cart[0] == '0'){
    				    $response["signal"] = '0';
    				}
    				else {
    					$response["signal"] = '1';
    				}
        			$response["html"] = $cart[1];
    			}
    			else {
    				$cart = getCart();
    				if($cart[0] == ''){
    				    $response["signal"] = '0';
    				}
    				else {
    					$response["signal"] = '1';
    				}
        			$response["html"] = $cart[1];
    			}
    		}
    		else {
    			$cart = getCart();
				if($cart[0] == ''){
				    $response["signal"] = '0';
				}
				else {
					$response["signal"] = '1';
				}
    			$response["html"] = $cart[1];
    		}
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

	public function removeCartProd(){
	    if($this->request->isAJAX()){
    		$response = array();
    		if(session()->get("logedin") == '1'){
    			if(count(session()->get('cart')) > 0){
    				if(preg_match("/^([0-9])+$/i",$this->request->getPost("id")) && preg_match("/^([a-zA-Z0-9])+$/i",$this->request->getPost("buytype"))){
    					$items = session()->get('cart');
    					$id = $this->request->getPost('id');
    					foreach ($items as $key => $value) {
    						if($this->request->getPost('buytype') == $value["typebuying"]){
    							if($id == $value['id'] ){
    								unset($items[$key]);
    							}	
    						}
    					}
    					session()->set('cart', $items);
    					$cart = getCart();
    					if($cart[0] == '0'){
	    				    $response["signal"] = '0';
	    				    $response["total"] = '$0.00';
	    				}
	    				else {
	    					$response["signal"] = '1';
	    					$response["total"] = $cart[3];
	    				}
	        			$response["html"] = $cart[1];
	        			
    				}
    				else {
    					$html = getCart();    					
    					if($cart[0] == '0'){
	    				    $response["signal"] = '0';
	    				    $response["total"] = '$0.00';
	    				}
	    				else {
	    					$response["signal"] = '1';
	    					$response["total"] = $cart[3];
	    				}
	        			$response["html"] = $cart[1];
    				}	
    			}
    			else {
    				$html = getCart();
					if($cart[0] == '0'){
    				    $response["signal"] = '0';
    				    $response["total"] = '$0.00';
    				}
    				else {
    					$response["signal"] = '1';
    					$response["total"] = $cart[3];
    				}
        			$response["html"] = $cart[1];
    			}
    		}
    		else {
    			$html = getCart();
				if($cart[0] == '0'){
				    $response["signal"] = '0';
				    $response["total"] = '$0.00';
				}
				else {
					$response["signal"] = '1';
					$response["total"] = $cart[3];
				}
    			$response["html"] = $cart[1];
    		}
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

	public function checkoutinit(){
	    if($this->request->isAJAX()){
    		if(session()->get("logedin") == '1'){
    			$error = '';
    			$ModelTwo = new UsersModel;
    			$response = array();
    			$mycart = getCart();
    			$countmycart = count(session()->get('cart'));
    
    			if($countmycart > 0){
    				$total = 0;
    				foreach(session()->get('cart') as $p => $o){ 
    					$total = $total + str_replace('$', '', $o["price"]); 
    				}
    				$in = null;
    			}
    			else {
    				$total = '$0.00';
    				$in = 'empty';
    			}
    
    			$UserResults = $ModelTwo->where(['id' => session()->get("suser_id")])->find();
    			$countUserResults = count($UserResults);
    			if($countUserResults == "1"){
    				$UserActualBalance = $UserResults[0]["balance"];
    				if($UserActualBalance >= $total ){
    					foreach(session()->get('cart') as $ke => $ve){
    						switch ($ve["typebuying"]) {
    							case '1':
    								$modelCas = new CardsModel;
    								$getRes = $modelCas->where(['id' => $ve['id']])->findAll();
    								$countGetRes = count($getRes);
    								if($countGetRes > 0){
    									if(session()->get('suser_id') == $getRes[0]['sellerid']){
    										$error = "You cannot buy your items.";
    									}
    									else {
    										if($getRes[0]['selled'] == '0'){
    											$ProductPrice = $getRes[0]['price'];										
    											if(/**$UserActualBalance > 0 &&**/ $UserActualBalance >= $ProductPrice){
    												$ModelInsertItems = new MyitemsModel;
    												$ndate = new \DateTime();
    												$date = $ndate->format('Y-m-d H:i:s');
    												$thedetails = "";
    												foreach($getRes[0] as $gkey => $gval){
    												    if($gval != "" 
    												        && $gkey != 'reported'
    												        && $gkey != 'selled'
    												        && $gkey != 'selledto'
    												        && $gkey != 'refunded'
    												        && $gkey != 'selledon'
    												        && $gkey != 'sellerusername'
    												        && $gkey != 'sellerid'
    												        && $gkey != 'price'
    												        && $gkey != 'refun'
    												        && $gkey != 'id'
    												        && $gkey != 'base'
    												    ){
    												        //$thedetails .= ucfirst(esc($gkey)).': '.$gval."</br>"; 
    												        if($gval != 'N/A'){
    												        	$thedetails .= esc($gval)."|";
    												        }    
    												        //$thedetails .= $gval."|";     
    												    }       
    												}
    												$insertData = [
    													'type' => 'Credit Card',
    													'typeid' => '1',
    													'prodid' => $getRes[0]['id'],
    													'details' => $thedetails,
    													'date' => $date,
    													'refundible' => $getRes[0]['refun'],
    													'userid' => session()->get('suser_id'),
    													'reported' => 0,
    													'price' => $getRes[0]['price'],
    													'sellerid' => $getRes[0]['sellerid']
    												];
    												$ModelInsertItems->save($insertData);
    												$dataupdateProd = [
    													'selled' => 1,
    													'selledto' => session()->get('suser_id'),
    													'selledon' => $date,
    												];
    												$modelCas->update($getRes[0]["id"], $dataupdateProd);
    												$getSeller =  $ModelTwo->where('id', $getRes[0]['sellerid'])->findAll();
    												if(count($getSeller) == 1){
    													$NewSellerBalance =  $getSeller[0]['seller_balance']+$getRes[0]['price'];
    													$NewSellerNbObjects =  $getSeller[0]['seller_nbobjects'] - 1;
    													$sellerbalanceData = [
    														'seller_balance' => $NewSellerBalance,
    														'seller_nbobjects' => $NewSellerNbObjects
    													];
    													$ModelTwo->update($getSeller[0]['id'], $sellerbalanceData);	
    												}
    												
    												$items = session()->get('cart');
    												
    												foreach ($items as $key => $value) {
    													if($value["typebuying"] == "1"){
    														if($getRes[0]["id"] == $value['id'] ){
    															unset($items[$key]);
    														}	
    													}
    												}
    												$ModelSection = new SectionsModel;
    												$sectionRevenue = $ModelSection->where(['identifier' => '1'])->findAll();
    												if(count($sectionRevenue) == 1 ){
    													$newSectionRevenue = $sectionRevenue[0]['sectionrevenue'] + $getRes[0]['price'];
    													$dataSection = [
    														'sectionrevenue' => $newSectionRevenue,
    													];
    													$ModelSection->update($sectionRevenue[0]['id'], $dataSection);
    												}
    
    												session()->set('cart', $items);
    											}
    											else {
    												$error = "Card Prodect Error".PHP_EOL;
    											}
    										}
    										else {
    											$items = session()->get('cart');
    											foreach ($items as $key => $value) {
    												if($value["typebuying"] == "1"){
    													if($getRes[0]["id"] == $value['id'] ){
    														unset($items[$key]);
    													}	
    												}
    											}
    											session()->set('cart', $items);
    											$error = "Some card are already purchased by someone else, Please choose another ones.".PHP_EOL;
    											$error .= '<script>setTimeout(function(){ window.location.href="'.base_url().'/myorders" }, 3000);</script>';
    										}
    									}	
    								}
    							break;
    							case '2':
    								$modelCas = new CpanelModel;
    								$getRes = $modelCas->where(['id' => $ve['id']])->findAll();
    								$countGetRes = count($getRes);
    								if($countGetRes > 0){
    									if(session()->get('suser_id') == $getRes[0]['sellerid']){
    										$error = "You cannot buy your items.";
    									}
    									else {
    										if($getRes[0]['selled'] == '0'){
    											$ProductPrice = $getRes[0]['price'];										
    											if(/**$UserActualBalance > 0 &&**/ $UserActualBalance >= $ProductPrice){
    												$ModelInsertItems = new MyitemsModel;
    												$ndate = new \DateTime();
    												$date = $ndate->format('Y-m-d H:i:s');
    												$thedetails = "";
    												foreach($getRes[0] as $gkey => $gval){
    												    if($gval != "" 
    												        && $gkey != 'reported'
    												        && $gkey != 'selled'
    												        && $gkey != 'selledto'
    												        && $gkey != 'refunded'
    												        && $gkey != 'selledon'
    												        && $gkey != 'sellerusername'
    												        && $gkey != 'sellerid'
    												        && $gkey != 'price'
    												        && $gkey != 'id'
    												    ){
    												        //$thedetails .= ucfirst(esc($gkey)).': '.$gval."</br>"; 
    												        if($gval != 'N/A' && $gkey != 'country'){
    												        	$thedetails .= '<b>'.esc(ucfirst($gkey)).':</b> '.esc($gval).'<br/>';
    												        }    
    												        //$thedetails .= $gval."|";     
    												    }       
    												}
    												$insertData = [
    													'type' => 'Cpanel',
    													'typeid' => '2',
    													'prodid' => $getRes[0]['id'],
    													'details' => $thedetails,
    													'date' => $date,
    													'refundible' => 1,
    													'userid' => session()->get('suser_id'),
    													'reported' => 0,
    													'price' => $getRes[0]['price'],
    													'sellerid' => $getRes[0]['sellerid']
    												];
    												$ModelInsertItems->save($insertData);
    												$dataupdateProd = [
    													'selled' => 1,
    													'selledto' => session()->get('suser_id'),
    													'selledon' => $date,
    												];
    												$modelCas->update($getRes[0]["id"], $dataupdateProd);
    												$getSeller =  $ModelTwo->where('id', $getRes[0]['sellerid'])->findAll();
    												if(count($getSeller) == 1){
    													$NewSellerBalance =  $getSeller[0]['seller_balance']+$getRes[0]['price'];
    													$NewSellerNbObjects =  $getSeller[0]['seller_nbobjects'] - 1;
    													$sellerbalanceData = [
    														'seller_balance' => $NewSellerBalance,
    														'seller_nbobjects' => $NewSellerNbObjects
    													];
    													$ModelTwo->update($getSeller[0]['id'], $sellerbalanceData);	
    												}
    												
    												$items = session()->get('cart');
    												
    												foreach ($items as $key => $value) {
    													if($value["typebuying"] == "2"){
    														if($getRes[0]["id"] == $value['id'] ){
    															unset($items[$key]);
    														}	
    													}
    												}
    												$ModelSection = new SectionsModel;
    												$sectionRevenue = $ModelSection->where(['identifier' => '2'])->findAll();
    												if(count($sectionRevenue) == 1 ){
    													$newSectionRevenue = $sectionRevenue[0]['sectionrevenue'] + $getRes[0]['price'];
    													$dataSection = [
    														'sectionrevenue' => $newSectionRevenue,
    													];
    													$ModelSection->update($sectionRevenue[0]['id'], $dataSection);
    												}
    
    												session()->set('cart', $items);
    											}
    											else {
    												$error = "Card Prodect Error".PHP_EOL;
    											}
    										}
    										else {
    											$items = session()->get('cart');
    											foreach ($items as $key => $value) {
    												if($value["typebuying"] == "1"){
    													if($getRes[0]["id"] == $value['id'] ){
    														unset($items[$key]);
    													}	
    												}
    											}
    											session()->set('cart', $items);
    											$error = "Some Cpanels are already purchased by someone else, Please choose another ones.".PHP_EOL;
    											$error .= '<script>setTimeout(function(){ window.location.href="'.base_url().'/myorders/" }, 3000);</script>';
    										}
    									}	
    								}
    							break;
    							case '3':
    								$modelCas = new RdpModel;
    								$getRes = $modelCas->where(['id' => $ve['id']])->findAll();
    								$countGetRes = count($getRes);
    								if($countGetRes > 0){
    									if(session()->get('suser_id') == $getRes[0]['sellerid']){
    										$error = "You cannot buy your items.";
    									}
    									else {
    										if($getRes[0]['selled'] == '0'){
    											$ProductPrice = $getRes[0]['price'];										
    											if(/**$UserActualBalance > 0 &&**/ $UserActualBalance >= $ProductPrice){
    												$ModelInsertItems = new MyitemsModel;
    												$ndate = new \DateTime();
    												$date = $ndate->format('Y-m-d H:i:s');
    												$thedetails = "";
    												foreach($getRes[0] as $gkey => $gval){
    												    if($gval != "" 
    												        && $gkey != 'reported'
    												        && $gkey != 'selled'
    												        && $gkey != 'selledto'
    												        && $gkey != 'refunded'
    												        && $gkey != 'selledon'
    												        && $gkey != 'sellerusername'
    												        && $gkey != 'sellerid'
    												        && $gkey != 'price'
    												        && $gkey != 'id'
    												    ){
    												        //$thedetails .= ucfirst(esc($gkey)).': '.$gval."</br>"; 
    												        if($gval != 'N/A' && $gkey != 'country'){
    												        	$thedetails .= '<b>'.esc(ucfirst($gkey)).':</b> '.esc($gval).'<br/>';
    												        }    
    												        //$thedetails .= $gval."|";     
    												    }       
    												}
    												$insertData = [
    													'type' => 'Rdp',
    													'typeid' => '3',
    													'prodid' => $getRes[0]['id'],
    													'details' => $thedetails,
    													'date' => $date,
    													'refundible' => 1,
    													'userid' => session()->get('suser_id'),
    													'reported' => 0,
    													'price' => $getRes[0]['price'],
    													'sellerid' => $getRes[0]['sellerid']
    												];
    												$ModelInsertItems->save($insertData);
    												$dataupdateProd = [
    													'selled' => 1,
    													'selledto' => session()->get('suser_id'),
    													'selledon' => $date,
    												];
    												$modelCas->update($getRes[0]["id"], $dataupdateProd);
    												$getSeller =  $ModelTwo->where('id', $getRes[0]['sellerid'])->findAll();
    												if(count($getSeller) == 1){
    													$NewSellerBalance =  $getSeller[0]['seller_balance']+$getRes[0]['price'];
    													$NewSellerNbObjects =  $getSeller[0]['seller_nbobjects'] - 1;
    													$sellerbalanceData = [
    														'seller_balance' => $NewSellerBalance,
    														'seller_nbobjects' => $NewSellerNbObjects
    													];
    													$ModelTwo->update($getSeller[0]['id'], $sellerbalanceData);	
    												}
    												
    												$items = session()->get('cart');
    												
    												foreach ($items as $key => $value) {
    													if($value["typebuying"] == "3"){
    														if($getRes[0]["id"] == $value['id'] ){
    															unset($items[$key]);
    														}	
    													}
    												}
    												$ModelSection = new SectionsModel;
    												$sectionRevenue = $ModelSection->where(['identifier' => '3'])->findAll();
    												if(count($sectionRevenue) == 1 ){
    													$newSectionRevenue = $sectionRevenue[0]['sectionrevenue'] + $getRes[0]['price'];
    													$dataSection = [
    														'sectionrevenue' => $newSectionRevenue,
    													];
    													$ModelSection->update($sectionRevenue[0]['id'], $dataSection);
    												}
    
    												session()->set('cart', $items);
    											}
    											else {
    												$error = "Card Prodect Error".PHP_EOL;
    											}
    										}
    										else {
    											$items = session()->get('cart');
    											foreach ($items as $key => $value) {
    												if($value["typebuying"] == "1"){
    													if($getRes[0]["id"] == $value['id'] ){
    														unset($items[$key]);
    													}	
    												}
    											}
    											session()->set('cart', $items);
    											$error = "Some Cpanels are already purchased by someone else, Please choose another ones.".PHP_EOL;
    											$error .= '<script>setTimeout(function(){ window.location.href="'.base_url().'/myorders/" }, 3000);</script>';
    										}
    									}	
    								}
    							break;
    							case '4':
    								$modelCas = new SmtpModel;
    								$getRes = $modelCas->where(['id' => $ve['id']])->findAll();
    								$countGetRes = count($getRes);
    								if($countGetRes > 0){
    									if(session()->get('suser_id') == $getRes[0]['sellerid']){
    										$error = "You cannot buy your items.";
    									}
    									else {
    										if($getRes[0]['selled'] == '0'){
    											$ProductPrice = $getRes[0]['price'];										
    											if(/**$UserActualBalance > 0 &&**/ $UserActualBalance >= $ProductPrice){
    												$ModelInsertItems = new MyitemsModel;
    												$ndate = new \DateTime();
    												$date = $ndate->format('Y-m-d H:i:s');
    												$thedetails = "";
    												foreach($getRes[0] as $gkey => $gval){
    												    if($gval != "" 
    												        && $gkey != 'reported'
    												        && $gkey != 'selled'
    												        && $gkey != 'selledto'
    												        && $gkey != 'refunded'
    												        && $gkey != 'selledon'
    												        && $gkey != 'sellerusername'
    												        && $gkey != 'sellerid'
    												        && $gkey != 'price'
    												        && $gkey != 'id'
    												    ){
    												        //$thedetails .= ucfirst(esc($gkey)).': '.$gval."</br>"; 
    												        if($gval != 'N/A' && $gkey != 'country'){
    												        	$thedetails .= '<b>'.esc(ucfirst($gkey)).':</b> '.esc($gval).'<br/>';
    												        }    
    												        //$thedetails .= $gval."|";     
    												    }       
    												}
    												$insertData = [
    													'type' => 'Smtp',
    													'typeid' => '4',
    													'prodid' => $getRes[0]['id'],
    													'details' => $thedetails,
    													'date' => $date,
    													'refundible' => 1,
    													'userid' => session()->get('suser_id'),
    													'reported' => 0,
    													'price' => $getRes[0]['price'],
    													'sellerid' => $getRes[0]['sellerid']
    												];
    												$ModelInsertItems->save($insertData);
    												$dataupdateProd = [
    													'selled' => 1,
    													'selledto' => session()->get('suser_id'),
    													'selledon' => $date,
    												];
    												$modelCas->update($getRes[0]["id"], $dataupdateProd);
    												$getSeller =  $ModelTwo->where('id', $getRes[0]['sellerid'])->findAll();
    												if(count($getSeller) == 1){
    													$NewSellerBalance =  $getSeller[0]['seller_balance']+$getRes[0]['price'];
    													$NewSellerNbObjects =  $getSeller[0]['seller_nbobjects'] - 1;
    													$sellerbalanceData = [
    														'seller_balance' => $NewSellerBalance,
    														'seller_nbobjects' => $NewSellerNbObjects
    													];
    													$ModelTwo->update($getSeller[0]['id'], $sellerbalanceData);	
    												}
    												
    												$items = session()->get('cart');
    												
    												foreach ($items as $key => $value) {
    													if($value["typebuying"] == "4"){
    														if($getRes[0]["id"] == $value['id'] ){
    															unset($items[$key]);
    														}	
    													}
    												}
    												$ModelSection = new SectionsModel;
    												$sectionRevenue = $ModelSection->where(['identifier' => '4'])->findAll();
    												if(count($sectionRevenue) == 1 ){
    													$newSectionRevenue = $sectionRevenue[0]['sectionrevenue'] + $getRes[0]['price'];
    													$dataSection = [
    														'sectionrevenue' => $newSectionRevenue,
    													];
    													$ModelSection->update($sectionRevenue[0]['id'], $dataSection);
    												}
    
    												session()->set('cart', $items);
    											}
    											else {
    												$error = "Card Prodect Error".PHP_EOL;
    											}
    										}
    										else {
    											$items = session()->get('cart');
    											foreach ($items as $key => $value) {
    												if($value["typebuying"] == "1"){
    													if($getRes[0]["id"] == $value['id'] ){
    														unset($items[$key]);
    													}	
    												}
    											}
    											session()->set('cart', $items);
    											$error = "Some Cpanels are already purchased by someone else, Please choose another ones.".PHP_EOL;
    											$error .= '<script>setTimeout(function(){ window.location.href="'.base_url().'/myorders/" }, 3000);</script>';
    										}
    									}	
    								}
    							break;
    							default:
    								$model = new SectionsModel;
    								$typebuying = strtolower($ve["typebuying"]);
    								$GetIfSection = $model->where('sectioname' , $typebuying )->findAll();
    								if(count($GetIfSection) == '1'){
    									$db = db_connect();
    				    				$GetSectionConfigs = $db->query("
    				    					SELECT * FROM section_".strtolower($GetIfSection[0]['sectioname'])."
    				    					WHERE id='".$db->escapeString($ve['id'])."'");
    					    			$getRes = $GetSectionConfigs->getResultArray();
    									$countResults = count($getRes);
    									if($countResults > 0){
    										if(session()->get('suser_id') == $getRes[0]['sellerid']){
    											$error = "You cannot buy your items.";
    										}
    										else {
    											if($getRes[0]['selled'] == '0'){
    												$ProductPrice = $getRes[0]['price'];
    												if($UserActualBalance > 0 && $UserActualBalance >= $ProductPrice){
    													$ModelInsertItems = new MyitemsModel;
    													$ndate = new \DateTime();
    													$date = $ndate->format('Y-m-d H:i:s');
    													$Notneeded = [
    														'reported' => 'no',
    														'refunded' => 'no',
    														'selledon' => 'no',
    														'sellerusername' => 'no',
    														'price' => 'no',
    														'id' => 'no',
    														'addon'=> 'no',
    														'selled'=> 'no',
    														'sellerid'=> 'no',
    														'selledto'=> 'no',
    														'selledtoid'=> 'no',
    														'titleprodzeh'=> 'no',
    														'prodimagezeh'=> 'no',
    														'selledti'=> 'no',
    														'selledtimes'=> 'no',
    														'refun' => 'no'

    													];
    													$thedetails = '';
    													foreach($getRes[0] as $needed => $mythevalue){
    														if(array_key_exists($needed, $Notneeded) === false){
    															$thedetails .= '<b>'.esc(ucfirst($needed)).':</b> '.esc($mythevalue).'<br/>';
    														}
    													}
    													$insertData = [
    														'type' => esc(ucfirst($GetIfSection[0]['sectionlable'])),
    														'typeid' => $GetIfSection[0]['identifier'],
    														'prodid' => $getRes[0]['id'],
    														'details' => $thedetails,
    														'date' => $date,
    														'refundible' => 1,
    														'userid' => session()->get('suser_id'),
    														'reported' => 0
    													];
    													$ModelInsertItems->save($insertData);
    													if($GetIfSection[0]['resell'] == '1'){
    														$InsertQuery = $db->query("
    															UPDATE  `section_".strtolower($GetIfSection[0]['sectioname'])."` 
    															SET `selled`='0',
    															`selledtimes` = `selledtimes` +1,
    															`selledto`='".session()->get('suser_username')."', 
    															`selledtoid`='".session()->get('suser_id')."',  
    															`selledon`='".$date."' 
    															WHERE `id`= '".$getRes[0]["id"]."'"
    														);
    													}
    													else {
    														$InsertQuery = $db->query("
    															UPDATE  `section_".strtolower($GetIfSection[0]['sectioname'])."`
    															SET `selled`='1', 
    															`selledto`='".session()->get('suser_username')."', 
    															`selledtoid`='".session()->get('suser_id')."',  
    															`selledon`='".$date."' 
    															WHERE `id`='".$getRes[0]["id"]."'"
    														);
    													}
    													$getSeller =  $ModelTwo->where(['id'=> $getRes[0]['sellerid']])->findAll();
    													$NewSellerBalance =  $getSeller[0]['seller_balance']+$getRes[0]['price'];
    													$NewSellerNbObjects =  $getSeller[0]['seller_nbobjects'] - 1;
    													$sellerbalanceData = [
    														'seller_balance' => $NewSellerBalance,
    														'seller_nbobjects' => $NewSellerNbObjects
    													];
    													$ModelTwo->update($getSeller[0]['id'], $sellerbalanceData);
    													$items = session()->get('cart');
    													
    													foreach ($items as $key => $value) {
    														if($value["typebuying"] == strtolower($GetIfSection[0]['sectioname'])){
    															if($getRes[0]["id"] == $value['id'] ){
    																unset($items[$key]);
    															}	
    														}
    													}
    													$sectionRevenue = $model->where(['identifier' => $GetIfSection[0]['identifier']])->findAll();
    													if(count($sectionRevenue) == 1 ){
    														$newSectionRevenue = $sectionRevenue[0]['sectionrevenue'] + $getRes[0]['price'];
    														$newSectionNubers = $sectionRevenue[0]['itemsnumbers'] - 1;
    														$dataSection = [
    															'sectionrevenue' => $newSectionRevenue,
    															'itemsnumbers' => $newSectionNubers
    														];
    														$model->update($sectionRevenue[0]['id'], $dataSection);
    													}
    
    													session()->set('cart', $items);
    												}
    												else {
    													$error = "Cart Prodducte Error".PHP_EOL;
    												}		
    											}
    											else {
    												$items = session()->get('cart');
    												foreach ($items as $key => $value) {
    													if($value["typebuying"] == "1"){
    														if($getRes[0]["id"] == $value['id'] ){
    															unset($items[$key]);
    														}	
    													}
    												}
    												session()->set('cart', $items);
    												$error = "Some card are already purchased by someone else, Please choose another ones.".PHP_EOL;
    												$error .= '<script>setTimeout(function(){ window.location.href="'.base_url().'/myorders" }, 3000);</script>';
    											}
    										}		
    									}
    								}
    							break;
    						}
    					}
    					if($error == ''){
    						if($total >= 0 && $in != 'empty'){
    							$FinalUserBalance = intval($UserActualBalance) - intval($total);
    							$objectCount = $countmycart + $UserResults[0]['object_nb'];
    							$userData = [
    								'balance' => $FinalUserBalance,
    								'object_nb' => $countmycart,
    							];
    							$ModelTwo->update(session()->get("suser_id"),$userData);
    							$modalContent = '<p>Checkout Success</p>';
    							$modalContent .= '<p>Redirecting to your orders...</p>';
    							$modalContent .= '<script>setTimeout(function(){ window.location.href="'.base_url().'/myorders" }, 500);</script>';
    							$response["modal"] = createModal($modalContent, 'fade', 'Success', 'text-danger', 'modal-lg', "0", "0", "0", "0", "0");	
    						}
    						else {
    							$modalContent = '<p>Error</p>';
    							$modalContent .= '<p>Your cart is empty.</p>';
    							$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "0", "1", "0", "1", "0");
    						}
    					}
    					else {
    						$modalContent = $error;
    						$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");		
    					}
    					
    				}
    				else {
    					$modalContent = '<p>Insufficient balance, please add enough funds</p>';
    					$response["modal"] = createModal($modalContent, 'fade', 'Oops', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");		
    				}
    						
    			}
    			else {
    				$modalContent = '<p>Error BE-002.</p>';
    				$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");		
    			}
    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($response);
    			exit();
    		}
    		else {
    			
    			header('location:'.base_url().'/');
    			exit();
    		}
	    }
		else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function vocherinit(){
	    if($this->request->isAJAX()){
    		if(session()->get("logedin") == '1'){
    			$settings = fetchSettings();
    			if($settings[0]["voucher"] == '1'){
    				$modalContent = '
    					<form id="depoForm">
    						<div class="row">
    							<div class="form-group col-12">
    								<label>Insert your Voucher Code</label>
    								<input type="text" class="form-control" name="code" id="code">
    								<input type="hidden" name="'.csrf_token().'" value="'.csrf_hash().'">
    							</div>
    						</div>
    					</form>
    				';
    				$response["modal"] = createModal($modalContent, 'fade', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Confirm', 'functions' => 'data-api="vocherconf"']);
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();	
    			}
    			else {
    				header('location:'.base_url().'/');
    				exit();
    			}
    		}
    		else {
    			header('location:'.base_url().'/');
    			exit();
    		}
	    }
		else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function vocherconf(){
	    if($this->request->isAJAX()){
    		if(session()->get("logedin") == '1'){
    			if(preg_match("/^([a-zA-Z0-9])+$/i",$this->request->getPost("code"))){
    				$code = $this->request->getPost('code');
    				$userModel = new UsersModel;
    				$ResultsUser = $userModel->where(['id' => session()->get('suser_id')])->findAll();
    				if(count($ResultsUser) == 1){
    					$modelCodes = new CodesModel;
    					$ResultsCodes = $modelCodes->where(['code' => $code , 'status' => '0'])->findAll();
    					if(count($ResultsCodes) == 1){
    						$valueCode = $ResultsCodes[0]['value'];
    						$date = new \DateTime();
    						$mdate = $date->format('Y-m-d H:s:i');
    						$dataCode = [
    							'status' => '1',
    							'useddate' => $mdate,
    							'usedbyid' => session()->get("suser_id"),
    							'usedbyname' => session()->get("suser_username"),
    						];
    						$modelCodes->update($ResultsCodes[0]['id'], $dataCode);
    						$NewUserBalance = $ResultsUser[0]["balance"] + $valueCode;
    						$dataUser = [
    							'balance' => $NewUserBalance
    						];
    						$userModel->update($ResultsUser[0]['id'], $dataUser);
    						session()->set('suser_balance', $NewUserBalance);
    						$response["modal"] = createModal('<p>Code successfully applied</p><p>$'.$valueCode.' Add to your balance</p>', '', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
    						$response["csrft"] = csrf_hash();
    						header('Content-Type: application/json');
    						echo json_encode($response);
    						exit();	
    					}
    					else {
    						$response["modal"] = createModal('<p><b><span style="color:red">OOPS!<br> INVALID/USED CODE</span></b></p>', '', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
    						$response["csrft"] = csrf_hash();
    						header('Content-Type: application/json');
    						echo json_encode($response);
    						exit();	
    					}
    				}
    				else {
    					$response["modal"] = createModal('<p>Error. E-001</p>', 'fade', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();	
    				}
    			}
    			else {
    				$response["modal"] = createModal('<p>Please insert a valid code</p>', 'fade', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();	
    			}
    		}
    		else {
    			header('location:'.base_url().'/');
    			exit();
    		}
	    }
		else {
	        echo "Nice try ;)";
	        exit();
	    }
	}
}