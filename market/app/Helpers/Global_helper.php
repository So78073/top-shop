<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

	function fetchSettings(){
		$target =  'App\Models\SettingsModel';
		$model = new $target;
		return $model->findAll();
	}

	function fetchSections(){
		$targetTwo =  'App\Models\SectionsModel';
		$modelTwo = new $targetTwo;
		if(count($modelTwo->findAll()) > 0){
			return $modelTwo->findAll();
		}
		else {
			return [null];
		}	
	}

	function fetchThisSections($id){
	    if(preg_match("/^([a-zA-Z0-9])+$/", $id) ){
    	    $targetFive =  'App\Models\SectionsModel';
    		$modelFive = new $targetFive;
    		$resuts = $modelFive->where('identifier',$id)->findAll();
    		if(count($resuts) > 0){
    			return $resuts;
    		}
    		else {
    			return [null];
    		}    
	    }
		else {
			return [null];
		}	
	}
	
	function telegram($telebot, $chatid, $teletext){
    	$teledata = [
            'chat_id' => "$chatid",
            'text' => "$teletext"
        ];
        file_get_contents("https://api.telegram.org/bot$telebot/sendMessage?".http_build_query($teledata));
	}

	function verifysection($id){
		$sesionStats = fetchThisSections($id);
		$verif = [];
		if($sesionStats[0] != NULL){
			if($sesionStats[0]['sectionstatus'] == "1"){
				$verif['sectionstatus'] = 1;
			}
			else {
				$verif['sectionstatus'] = 0;
			}
			if($sesionStats[0]['maintenancemode'] == "1"){
				$verif['maintenancemode'] = 1;
			}
			else {
				$verif['maintenancemode'] = 0;
			}
			if($sesionStats[0]['sellersactivate'] == "1"){
				$verif['sellersactivate'] = 1;
			}
			else {
				$verif['sellersactivate'] = 0;
			}
			$verif['sectionrevenue'] = $sesionStats[0]['sectionrevenue'];
			$verif['itemsnumbers'] = $sesionStats[0]['itemsnumbers'];
			$verif['identifier'] = $sesionStats[0]['identifier'];
			$verif['sectionName'] = $sesionStats[0]['sectioname'];
		}
		else {
			$verif = null;
		}
		return $verif;
	}

	function fetchNotifications(){
		$targetThree =  'App\Models\NotificationsModel';
		$modelThree = new $targetThree;
		$resNot = $modelThree->where(['userid' => session()->get('suser_id')])->orwhere('userId', 'all')->orderby('id', 'desc')->findAll(5);
		if(count($resNot) > 0){
			return $resNot;
		}
		else {
			return [null];
		}
	}

	function captcha(){
		$captcha["part1"] = rand(1,9);
		$captcha["part2"] = rand(1,9);
		$captcha["results"] = $captcha["part1"]+$captcha["part2"];
		return $captcha;
	}

	function getCart(){
		if(!session()->get('cart')){
			session()->set('cart', []);
		}
		$nbitems = count(session()->get('cart'));
		$html = '';
		$prices = array();
		if($nbitems > 0){
			$html .= '<div style="overflow-y:scroll; max-height: 250px;">';
			foreach(session()->get('cart') as $k => $v){
				$html .='<a href="javascript:void(0)" class="media-list-link read mycartLink-'.esc($v['id']).'">
			                <div class="media pd-x-20 pd-y-15">
			                  <i class="icon ion-ios-cart tx-24"></i>
			                  <div class="media-body">
			                    <p class="tx-13 mg-b-0"><strong class="tx-medium">'.esc($v["type"]).' </strong> 
				                <span data-api="removeCartProd-'.esc($v['id']).'|'.esc($v["typebuying"]).'" class="msg-time float-end '.esc($v["id"]).'-'.esc($v["typebuying"]).'">Remove</span>
			                    </p>
			                    <span class="tx-12">Price: '.esc($v["price"]).'</span>
			                  </div>
			                </div><!-- media -->
			              </a>
					';
					//var_dump(str_replace('$', '', $v["price"]));
 
					//$priceint = preg_match_all('!\d+!', str_replace('$', '', floatval($v["price"])), $matches);
					//var_dump($priceint);
					$prices[] = number_format((float) filter_var( $v["price"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ), 2, '.', '');
			}
			$html .= '</div>';
			$html .= '<div class="media-list-footer">
    				<a href="'.base_url() .'/cart" class="tx-12">
    					<i class="fa fa-angle-down mg-r-5"></i> To checkout
    				</a>	

              </div>';
              
              $signal='1';
              $tottal = '$'.number_format(array_sum($prices), 2, '.', '');
		}
		else {
			$nbitems = '0';
			$html = '';
		    $signal= '0';
		    $tottal = '$0.00';
		}
		//var_dump($prices);
		$ItemsCartStuff = [$nbitems, $html, $signal, $tottal];
		return $ItemsCartStuff;
	}

	function refreshUserInfos(){
		if(session()->get("logedin") == "1"){
			$userId = session()->get("suser_id");
			$target =  'App\Models\UsersModel';
			$Model = new $target;
			$userGetInfosArray = $Model->where(['id' => esc($userId)])->findAll(1);
			if(count($userGetInfosArray) == 1){
				foreach($userGetInfosArray[0] as $key => $val){
					$data['suser_'.$key] = esc($val);
				}
				session()->set($data);
				if($userGetInfosArray[0]['active'] == '0' ){
					$router = service('router'); 
					$controller  = $router->controllerName();
					$settingheader = fetchSettings();
					if($settingheader[0]['depoactivate'] == '1'){
						if($controller !== '\App\Controllers\Bridge' && $controller !== '\App\Controllers\Finances' && $controller !== '\App\Controllers\History' &&session()->get('suser_groupe') != '9' && $controller !== '\App\Controllers\Logout'){
							header('location:'.base_url().'/bridge');
							exit();
						}
					}
					unset($settingheader);
				}
			}
			else {
				session()->stop();
				session()->destroy();			
				header('location:'.base_url().'/login');
				exit();
			}
					
		}
		//else {
		//	exit();
		//}
	}

	function createModal(
			$Content = "",  
			$Modalstyle ="", 
			$ModalTitle = "", 
			$TitleColor ="", 
			$CoreClass = "",  
			$modalHeader = "", 
			$modalFooter = "", 
			$WigetClose = "", 
			$BtnClose = "", 
			$BtnSave = "", 
			$BtnParam = array()){

		if($WigetClose == "1"){
			$WigetClose = '<button type="button" class="close" data-api="closeModal" aria-label="Close"><span aria-hidden="true">×</span></button>';		
		}
		else {
			$WigetClose = '';
		}
		if($BtnClose == "1"){
			$BtnClose = '<button type="button" class="btn btn-danger btn-sm" data-api="closeModal">Close</button>';
		}
		else {
			$BtnClose = '';
		}
		if($BtnSave == "1"){
			$BtnSave = '<button id="msavebtn" type="button" class="btn btn-success btn-sm" '.$BtnParam["functions"].'>'.$BtnParam["text"].'</button>';
		}
		else {
			$BtnSave = '';
		}

		if($modalHeader == "1"){
			$modalHeader = '
				<div class="modal-header pd-x-20">
					<h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold '.$TitleColor.'">'.$ModalTitle.'</h6>
					'.$WigetClose.'
				</div>';
		}
		else {
			$modalHeader = '';
		}
		if($modalFooter == "1"){
			$modalFooter = '
				<div class="modal-footer justify-content-center">
					'.$BtnClose.'
					'.$BtnSave.'
				</div>';
		}
		else {
			$modalFooter = '';
		}
		$modal = '
		<div id="bsModal" class="modal '.$Modalstyle.'">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content tx-size-sm">
              '.$modalHeader.'
              <div class="modal-body pd-20">
                '.$Content.'
              </div><!-- modal-body -->
              '.$modalFooter.'
            </div>
          </div><!-- modal-dialog -->
        </div><!-- modal -->





		';
		/**$modal = '<div class="modal d-block pos-static" id="bsModal">
		<div class="modal-dialog '.$CoreClass.'" >
				<div class="modal-content">
					'.$modalHeader.'
					<div class="modal-body">'.$Content.'</div>
					'.$modalFooter.'
				</div>
			</div>
		</div>';**/
		return $modal;
	}

        



	function getIpInfos($ip){
		$JsonIpinfos = file_get_contents('http://ip-api.com/php/'.$ip);
		return(unserialize($JsonIpinfos));
	}

	function encryptage($string){
		$encrypter = \Config\Services::encrypter();
		$enc = base64_encode($encrypter->encrypt($string));
		return $enc;
	}

	function decryptage($string){
		$encrypter = \Config\Services::encrypter();
		$enc = $encrypter->decrypt(base64_decode($string));
		return $enc;
	}

	function cccheck($cc, $bins){
        $bin = substr($cc, 0, 6);
        $searchfor = $bin; 
        $contents = $bins;
        $pattern = preg_quote($searchfor, '/');
        $pattern = "/^.*$pattern.*\$/m";
        $founds = null;
        if (preg_match_all($pattern, $contents, $matches)) {
            $founds = implode("\n", $matches[0]);
        }
        if(null !== $founds || $founds != '' || !empty($founds)){
        	$myArray = explode(",", $founds);
	        $count = count($myArray);
			$dataCC['scheme'] = $myArray[1]; 
			$dataCC['type'] = $myArray[2];
			$dataCC['brand'] = $myArray[3];
			$dataCC['bank'] = $myArray[4];
			$dataCC['countryAlpha2'] = $myArray[5];
			$dataCC['country'] = $myArray[7];
			return $dataCC;
        }
        else {
        	return null;
        }
        
	}
    //chk checker function
	function chkchecker($par, $key){
		$exp = explode('|', $par[1]);
	    $apis = '98440555e09df97b2e75aecbad247cf88458f46c';
		$client = \Config\Services::curlrequest();
		$url = "https://api.chk.cards/v1/cards?";
		$url .= "key=" . $key;
		$url .= "&card=".$par[0]."|".$exp[0].'/'.$exp[1]."|".$par[2];
		$url .= "&format=plain";
		$url .= "&cc_format=1";
		$url .= "&safe_checking=0";
		$url .= "&bypass_prepaid=0";
		try {
			$response = $client->get("$url", ['timeout' => 30]);
			return $response->getBody();	
		} 
		catch (Exception $e) {
			return null;
		}
		
	}

	// Luxocheck

	function luxchek($par, $key, $user){
		$exp = explode('|', $par[1]);
		$client = \Config\Services::curlrequest();
		$url = "https://mirror2.luxchecker.vc/apiv2/ck.php?";
		$url .= "cardnum=".$par[0];
		$url .= "&expm=".$exp[0];
		$url .= "&expy=".$exp[1];
		$url .= "&cvv=".$par[2];
		$url .= "&key=".$key;
		$url .= "&username=".$user;
		try {
			$response = $client->get("$url", ['timeout' => 30]);
			return $response->getBody();	
		} 
		catch (Exception $e) {
			return null;
		}
	}
	
    function tags($tag, $params = array()){
        $svname = '/\#\#vname+\#\#/';
        $sdigits = '/\#\#digits+\#\#/';
        $sservice = '/\#\#service+\#\#/';

        $taggedlline = "";
        $taggedlline .= preg_replace_callback_array([
            $svname=>function($match) use ($params){
                return $params[0];
            },
            $sdigits=>function($match) use ($params){
                return $params[1];
            }, 
            $sservice=>function($match) use ($params){
                return $params[2];
            },
        ], $tag);

        return $taggedlline;
    }
    
    function createletter($letter, $par = array()){
        $lines = explode("\n", $letter);
        $pathTags = '/\#\#[a-zA-Z0-9 ]+\#\#|\#\#[a-zA-Z]+\#\#/';
        $NewLines = "";

        foreach ($lines as $key => $value) {
            if(preg_match($pathTags, $value)){
                
                $NewLines .= tags($value, $p = [$par[0], $par[1], $par[2]]);
            }
            else {
                $NewLines .= $value;
            }
        }
          
        return $NewLines;  
    }

    function getCoins($api){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.nowpayments.io/v1/currencies',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'x-api-key: '.$api.''
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
    }

    function getPaymentstatus($api, $pid){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.nowpayments.io/v1/payment/'.$pid,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'x-api-key: '.$api.''
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		return json_decode($response, true);
    }

    function createForm($results, $id, $sectype){
    	$form = '<form id="createForm" enctype="multipart/form-data">';
    	$form .= '<div class="row">';
    	foreach ($results as $key => $value) {

    		if(strpos($value['validationrull'], 'required') !== false){
    			$required = '<span class="text-danger"> *</span>';
    		}
    		else {
    			$required = '';
    		}
    		switch ($value['inputsTypes']) {
	    		case 'text':		    		
	    			$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
										<input type="text" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control">
										<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'""></i></a>
									</div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';	
				break;
	    		case 'password':
	    			$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
									<input type="password" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control">
									<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'""></i></a></div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
				break;
				case 'email':
	    			$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
									<input type="email" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control">
									<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'""></i></a></div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
				break;
				case 'number':
					if($value['inputName'] == "price"){
						$form .= '<div class="form-group col-12 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
									<input type="number" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control" >
									<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'""></i></a></div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
					}
					else {
						$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
									<input type="number" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control">
									<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'""></i></a></div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
					}
	    			
				break;
				case 'textaria':
					$form .= '<div class="form-group col-12 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<textarea id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control"></textarea>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
	    			
				break;
				case 'imageupload':
	    			$form .= '<div class="form-group col-12 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
									<input type="file" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control">
									<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'""></i></a></div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
				break;
				case 'prodimagezeh': 
					if($sectype == '2' && $value['inputName'] == 'prodimagezeh'){
						$form .= '<div class="form-group col-12 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
									<input type="file" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control">
									<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'""></i></a></div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
					}
					else {
						$form .= '';
					}
				break;
				case 'titleprodzeh':
					if($sectype == '2' && $value['inputName'] == 'titleprodzeh'){		    		
		    			$form .= '<div class="form-group col-12 pb-2">
										<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
										<div class="input-group">
										<input type="text" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control">
										<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'""></i></a></div>
										<small class="'.$value['inputName'].' text-danger"></small>
									</div>';
					}
					else {
						$form .= '';
					}	
				break;
				case 'checkbox':
	    			$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
									<input type="checkbox" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="checkbox form-control">
									<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'""></i></a></div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
				break;
				case 'countrylist':
	    			
				break;
				case 'yesno':
	    			$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
										<select class="form-select select-single" id="'.$value['inputName'].'" name="'.$value['inputName'].'" >

											<option value="yes">YES</option>
											<option value="no">NO</option>
										</select>
										<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'""></i></a>
									</div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
				break;
				case (preg_match("/^([a-z0-9]+),([a-zA-Z0-9 ]+)$/m", $value['inputsTypes']) ? true : false) :
					$form .= '<div class="form-group col-6 pb-2">
								<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
								<div class="input-group">
								<select class="form-control" id="'.$value['inputName'].'" name="'.$value['inputName'].'" >';

					$valueType = explode("\r\n", $value['inputsTypes']);
					foreach ($valueType as $keyTypes => $valueTypes) {
						$valuesVals = explode(',', $valueTypes);
						$form .= '<option value="'.$valuesVals[0].'">'.$valuesVals[1].'</option>';
					}
					$form .= '</select>
								<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'""></i></a>
							</div>
							<small class="'.$value['inputName'].' text-danger"></small>
						</div>';
				break;
	    	}
    	}
    	$form .= '<input type="hidden" name="id" value="'.base64_encode(esc($id)).'">';
    	$form .= '</div>';
    	$form .= '</form>';
    	return $form;
    }

    function createFormEdit($results, $id, $sectype, $data){
    	$form = '';
    	foreach ($results as $key => $value) {
    		if(strpos($value['validationrull'], 'required') !== false){
    			$required = '<span class="text-danger"> *</span>';
    		}
    		else {
    			$required = '';
    		}
    		switch ($value['inputsTypes']) {
	    		case 'text':		    		
	    			$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
									    <input type="text" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control" value="'.$data[$value['inputName']].'">
									    <span href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'"></i></span>
									</div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';	
				break;
	    		case 'password':
	    			$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
    									<input type="password" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control" value="'.$data[$value['inputName']].'">
    									<span href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'"></i></span>
									</div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
				break;
				case 'email':
	    			$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
    									<input type="email" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control" value="'.$data[$value['inputName']].'">
    									<span href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'"></i></span>
									</div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
				break;
				case 'number':
					if($value['inputName'] == "price"){
						$form .= '<div class="form-group col-12 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
									<input type="number" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control" value="'.$data[$value['inputName']].'">
									<span href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'"></i></span></div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
					}
					else {
						$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
    									<input type="number" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control" value="'.$data[$value['inputName']].'">
    									<span href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'"></i></span>
									</div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
					}
				break;
				case 'textaria':
					$form .= '<div class="form-group col-12 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
									    <textarea id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control">'.$data[$value['inputName']].'</textarea>
									</div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
	    			
				break;
				case 'imageupload':
	    			$form .= '<div class="form-group col-12 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
    									<input type="file" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control">
    									<span href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'"></i></span>
									</div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
				break;
				case 'prodimagezeh': 
					if($sectype == '2' && $value['inputName'] == 'prodimagezeh'){
						$form .= '<div class="form-group col-12 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).'<span class="text-danger"> *</span></label>
									<div class="input-group">
    									<input type="file" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control">
    									<span href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'"></i></span>
									</div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
					}
					else {
						$form .= '';
					}
				break;
				case 'titleprodzeh':
					if($sectype == '2' && $value['inputName'] == 'titleprodzeh'){		    		
		    			$form .= '<div class="form-group col-12 pb-2">
										<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
										<div class="input-group">
    										<input type="text" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="form-control" value="'.$data[$value['inputName']].'">
    										<span href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'"></i></span>
										</div>
										<small class="'.$value['inputName'].' text-danger"></small>
									</div>';
					}
					else {
						$form .= '';
					}	
				break;
				case 'checkbox':
	    			$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
    									<input type="checkbox" id="'.$value['inputName'].'" name="'.$value['inputName'].'" class="checkbox form-control">
    									<span href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'"></i></span>
									</div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
				break;
				case 'countrylist':
	    			
				break;
				case 'yesno':
				    switch($data[$value['inputName']]){
				        case 'yes':
				        case '1' :
				            $showInput = 'YES';
			            break;
			            case 'no':
				        case '0' :
				            $showInput = 'NO';
			            break;
				    }
	    			$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
									<select class="form-select select-single" id="'.$value['inputName'].'" name="'.$value['inputName'].'" >
										<option  value="'.$data[$value['inputName']].'" select>'.$showInput.'</option>
										<option value="yes">YES</option>
										<option value="no">NO</option>
									</select>
									<span href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'"></i></span></div>
									<small class="'.$value['inputName'].' text-danger"></small>
								</div>';
				break;
				case (preg_match("/^([a-z0-9]+),([a-zA-Z0-9 ]+)$/m", $value['inputsTypes']) ? true : false) :
				    $valueType = explode("\r\n", $value['inputsTypes']);
					$form .= '<div class="form-group col-6 pb-2">
									<label class="form-label">'.ucfirst($value['inputLable']).$required.'</label>
									<div class="input-group">
									<select class="form-control select2" id="'.$value['inputName'].'" name="'.$value['inputName'].'" >';
									$form .= '<option value="'.$data[$value['inputName']].'" select>'.ucfirst($data[$value['inputName']]).'</option>';
					
					foreach ($valueType as $keyTypes => $valueTypes) {
						$valuesVals = explode(',', $valueTypes);
						$form .= '<option value="'.$valuesVals[0].'">'.$valuesVals[1].'</option>';
					}
					$form .= '</select>
					<span href="javascript:;" class="input-group-text bg-transparent"><i class="bx '.$value['inputsIcone'].'"></i></span>
						</div>
							<small class="'.$value['inputName'].' text-danger"></small>
						</div>';
				break;
	    	}
    	}
    	return $form;
    }

    /**function createvalidations($results){

    	foreach ($results as $key => $value) {
    		if($value['validationrull'] != 'No validation' && $value['validationrull'] != 'No Validation'){
    			$ValidationRulls[$value['inputName']] = array(
		            'label'  => $value['inputLable'],
		            'rules'  => $value['validationrull']
	            );
    		}
    	}
    	return $ValidationRulls;
    }**/

    function createvalidations($results, $style){

    	foreach ($results as $key => $value) {
    		if($value['validationrull'] != 'No validation' && $value['validationrull'] != 'No Validation'){
    			if($style == '1'){
    				if($value['inputName'] != 'titleprodzeh' && $value['inputName'] != 'prodimagezeh'){
    					$ValidationRulls[$value['inputName']] = array(
				            'label'  => $value['inputLable'],
				            'rules'  => $value['validationrull']
			            );	
    				}
    			}
    			else {
    				$ValidationRulls[$value['inputName']] = array(
			            'label'  => $value['inputLable'],
			            'rules'  => $value['validationrull']
		            );	
    			}
    			
    		}
    	}
    	return $ValidationRulls;
    }

    function createTableStyles($key, $value){
    	switch (strtolower($key)) {
    	    case 'sumdollar':
    			return '$'.esc(ucfirst($value));
			break;
			case 'sumpound':
    			return esc(ucfirst($value)).'£';
			break;
			case 'sumeur':
    			return esc(ucfirst($value)).'€';
			break;
    		case 'btnsuccess':
    			return '<button class="btn btn-success btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btnwarning':
    			return '<button class="btn btn-warning btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btndanger':
    			return '<button class="btn btn-danger btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btnlight':
    			return '<button class="btn btn-light btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btninfo':
    			return '<button class="btn btn-info btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btndefault':
    			return '<button class="btn btn-default btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btncheck':
    			return '<button class="btn btn-light"><i class="bx bx-check"></i></button>';
			break;
			case 'btnclose':
    			return '<button class="btn btn-light"><i class="bx bx-x"></i></button>';
			break;
			case 'link':
    			return '<button class="btn btn-light btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btnimage':
    			return '<a data-lightbox="'.esc($value).'" class="btn btn-success btn-sm" href="'.esc($value).'">Show Image</a>';
			break;
			case 'image':
    			return '<a data-lightbox="'.esc($value).'" href="'.esc($value).'">Show Image</a>';
			break;
			case 'imageupload':
    			return '<a data-lightbox="'.esc($value).'" class="btn btn-light btn-sm" href="'.base_url().'/assets/images/proofs/'.esc($value).'">Show Images</a>';
			break;
			case 'existnot':
				if($value != ''){
					return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Yes</div>';
				}
				else {
					return '<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>No</div>';
				}
			break;
			case 'yesno':
				if($value == 'yes' || $value == '1'){
					return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Yes</div>';
				}
				else if($value == 'no' || $value == '0'){
					return '<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>No</div>';
				}
				else {
					return '<span class="badge rounded-pill bg-danger p-2 px-3"><i class="bx bx-message-x"></i></span>';
				}
			break;
    		default:
    			return esc(ucfirst($value));
			break;
    	}
    }

    function createCardsStyles($key, $value){
    	switch (strtolower($key)) {
    	    case 'sumdollar':
    			return '$'.esc(ucfirst($value));
			break;
			case 'sumpound':
    			return esc(ucfirst($value)).'£';
			break;
			case 'sumeur':
    			return esc(ucfirst($value)).'€';
			break;
    		case 'btnsuccess':
    			return '<button class="btn btn-success btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btnwarning':
    			return '<button class="btn btn-warning btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btndanger':
    			return '<button class="btn btn-danger btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btnlight':
    			return '<button class="btn btn-light btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btninfo':
    			return '<button class="btn btn-info btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btndefault':
    			return '<button class="btn btn-default btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btncheck':
    			return '<button class="btn btn-light"><i class="bx bx-check"></i></button>';
			break;
			case 'btnclose':
    			return '<button class="btn btn-light"><i class="bx bx-x"></i></button>';
			break;
			case 'link':
    			return '<button class="btn btn-light btn-sm">'.esc(ucfirst($value)).'</button>';
			break;
			case 'btnimage':
    			return '<a data-lightbox="'.esc($value).'" class="btn btn-light btn-sm" href="'.esc($value).'">Show Image</a>';
			break;
			case 'image':
    			return '<a data-lightbox="'.esc($value).'" class="btn btn-light btn-sm" href="'.esc($value).'"><img src="'.esc($value).'" style="width:50px;"></a>';
			break;
			case 'imageupload':
    			return '<a data-lightbox="'.esc($value).'" class="btn btn-light btn-sm" href="'.base_url().'/assets/images/proofs/'.esc($value).'"><img src="'.base_url().'/assets/images/proofs/'.esc($value).'" style="width:50px;"></a>';
			break;
			case 'existnot':
				if($value != ''){
					return '<span class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Yes</span>';
				}
				else {
					return '<span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>No</span>';
				}
			break;
			case 'yesno':
				if($value == 'yes' || $value == '1'){
					return '<span class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Yes</span>';
				}
				else if($value == 'no' || $value == '0'){
					return '<span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>No</span>';
				}
				else {
					return '<span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3"><i class="bx bx-message-x"></i></span>';
				}
			break;
    		default:
    			return esc(ucfirst($value));
			break;
    	}
    }


	function CpanelChecker(string $url,string $username,string $pass){
		$client = \Config\Services::curlrequest();
		$url = $url;

		try {
			$response = $client->request('POST', $url.'/login', [
			    'form_params' => [
			        'user' => $username,
			        'pass' => $pass,
			    ],
			    'verify' => false, 'timeout' => 10
			]);
			$body = $response->getBody();
			if (strpos($body, 'CONTENT="2;URL=/cpsess') !== false) {
				return true;
			}
			else {
				return null;
			}	
		} 
		catch (Exception $e) {
			return null;
		}
		
	}

	function RDPChecker($host, $user, $pass){
        $timeout = 6;
        $port = 3389;
        $rdp_sock = @fsockopen($host, $port, $user, $pass, $timeout);
        if (!$rdp_sock) {
            return false;
        } 
        else {
            fclose($rdp_sock);
            return true;
        }
    }

	function smtpchecker($host, $port, $user, $pass){
		
		$mail = new PHPMailer(true);  
		try {
		    $mail->isSMTP();  
		    $mail->Host         = $host;
		    $mail->SMTPAuth     = true;     
		    $mail->Username     = $user;  
		    $mail->Password     = $pass;
		    $mail->Port         = $port; 
		    if($port == '587'){
		    	$mail->SMTPSecure   = 'tls'; 
		    }
		    else if($port == '465'){
		    	$mail->SMTPSecure   = 'ssl'; 
		    }
		    else {
		    }
			$mail->Timeout 		= 15;
			$mail->Subject      = 'Check SMTP ';
			$mail->Body         = 'Check SMTP Buy From : '.base_url();
			$mail->setFrom($user, 'SMTP CHekcer');
			$mail->addAddress('grantcory8080@gmail.com');  
			$mail->isHTML(true);      
			if(!$mail->send()) {
				return false;
			}
		    else {
			    return true;
		    }
		} catch (Exception $e) {
			return false;
		}
	}

	/**function smtpchecker($host, $port, $user, $pass){
		$email = \Config\Services::email();
        $config['protocol'] = 'smtp';
        $config['SMTPHost'] = $host;
        $config['SMTPUser'] = $user;
        $config['SMTPPass'] = $pass;
        $config['SMTPPort'] = $port;
        $config['SMTPCrypto'] = 'tls';
        $config['SMTPTimeout'] = '5';
		$config['charset']  = 'iso-8859-1';
		$config['wordWrap'] = true;
		$email->initialize($config);
		$email->setFrom($user, 'SmtpChecker');
		$email->setTo('grantcory8080@gmail.com');
		$email->setSubject('Check SMTP');
		$email->setMessage('Check SMTP '.base_url());
		$email->send();
		var_dump($email->printDebugger());
        //if (!$email->send()) {
        //    return false;
        //} 
        //else {
        //    fclose($rdp_sock);
        //    return true;
        //}
    }**/
