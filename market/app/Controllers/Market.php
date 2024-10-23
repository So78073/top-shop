<?php

namespace App\Controllers;
use App\Models\UsersModel;
use App\Models\SettingsModel;
use App\Models\SectionsModel;
use App\Models\NotificationsModel;

class Market extends BaseController
{
	function _remap($param) {
	    if(preg_match("/^([a-zA-Z0-9])+$/", $param)){
	        switch($param){
    			case 'fetchTable' :
    				$this->fetchTable();
    			break;
    			case 'fetchCards' :
    				$this->fetchCards();
    			break;
    			/**case 'initCreate' :
    				$this->initCreate();
    			break;
    			case 'doCreate' :
    				$this->doCreate();
    			break;
    			case 'initDelete' :
    				$this->initDelete();
    			break;
    			case 'doDelete' :
    				$this->doDelete();
    			break;
    			case 'initEdit' :
    				$this->initEdit();
    			break;
    			case 'doEdit' :
    				$this->doEdit();
    			break;
    			case 'massinitEdit' :
    				$this->massinitEdit();
    			break;
    			case 'massedit' :
    				$this->massedit();
    			break;
    			case 'massrmuserinit' :
    				$this->massrmuserinit();
    			break;
    			case 'massrmuser' :
    				$this->massrmuser();
    			break;**/
    			default:
    				$this->index($param);
    			break;
    		}    
	    }
	    else {
	        header('location:'.base_url());
	        exit();
	    }
    }
    
    public function index($param = null){
    	if(verifysection($param) != null){
    		$verify = verifysection($param);
	    	if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9'){
	    		header('location:'.base_url());
	    		exit();
	    	}
    	}
    	else {
    		header('location:'.base_url());
    		exit();
    	}
	    
    	if($param !== null && $param != '' && preg_match("/^([a-zA-Z0-9])+$/", $param)){
    		if(session()->get("logedin") == '1'){
    			$data = array();
    			$model = new SectionsModel;
	    		$Results = $model->where('identifier' , $param)->findAll();
	    		if(count($Results) == 1){
	    			$data['sectionName'] = $Results[0]['sectionlable'];
	    			$data['sectionid'] = $Results[0]['identifier'];
	    			$data['sectiontype'] = $Results[0]['sectiontype'];
	    			$data['sectionmessage'] = $Results[0]['sectionmessage'];
	    			$db = db_connect();
	    			$query = $db->query("SELECT * FROM `table_".$db->escapeString(strtolower($Results[0]['sectioname']))."`");
	    			$theTable = array();
	    			foreach($query->getResultArray() as $key => $val){
	    				if($val["cellsTypes"] !== 'hide' && $key !== 'id'){
	    					$theTable[] = $val["cellsHeads"];
	    				}
	    			}
	    			$settings = fetchSettings();
					$mycart = getCart();
					$data["nbitemscart"] = $mycart[0];
					$data["cartInnerHtml"] = $mycart[1];
					$data["settings"] = $settings;

					$data["Sellers"] =  $verify['sellersactivate'];
	    			$data['DataTable'] = $theTable;
		            echo view("assets/header", $data);
		            echo view("assets/aside");
		            echo view("assets/topbarre");
		            if($verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9'){
		            	echo view("maintenance");	
			    	}
			    	else {
		    			echo view("market");	
			    	}
		            echo view("assets/footer");
		            echo view("assets/scripts");		
	    		}
	    		else {
	    			header('location:'.base_url().'/login');
	            	exit();
	    		}
		            
	        }
	        else {
	            header('location:'.base_url().'/login');
	            exit();
	        }
    	}
    	else {
            header('location:'.base_url().'/login');
            exit();
        }  
    }

    public function fetchTable(){
        if($this->request->isAJAX()){
        	$verify = verifysection($this->request->getGet('id'));
        	if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9' || $verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9' ){
        		$output['data'][] = array(
            		NULL,					
    			);
    			header('Content-Type: application/json');
    			echo json_encode($output);
    			exit();
        	}
    		$output = array('data' => array());
    		if(session()->get("logedin") == '1'){
    			if($this->request->getGet('id') !== "" && preg_match("/^([a-zA-Z0-9])+$/", $this->request->getGet('id'))){
    				$sessionID = $this->request->getGet('id');
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', $sessionID)->findAll();
    				$countresults = count($Results);
    				$countCarts = count(session()->get('cart'));
    				if($countCarts > 0){
    					$cartsid = [];
    					$types = [];
    					foreach(session()->get('cart') as $kk => $vv){
    						$cartsid[] = $vv['id'];
    						$types[] = $vv['typebuying']; 	
    					}
    					$myCombinedArray = array_combine($cartsid, $types);
    				}
    				else {
    					$myCombinedArray = [];
    				}
    				if($countresults == 1){
    					$data['sectionName'] = $Results[0]['sectioname'];
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `table_".$db->escapeString(strtolower($data['sectionName']))."`");
    	    			$theTable = array();
    	    			$thetypes = array();
    	    			foreach($GetSectionConfigs->getResultArray() as $key => $val){;
    	    				if($val["cellsTypes"] !== 'hide'){
    	    					$theTable[] = $val["cellsName"];
    	    					$thetypes[] = $val["cellsTypes"];
    	    				}
    	    			}
    	    			$theTableValues = array_values($theTable);
    	    			$theTableTypes = array_values($thetypes);
    	    			$getSectionContent = $db->query("SELECT * FROM `section_".$db->escapeString(strtolower($data['sectionName']))."` WHERE `selled`='0'");
    	    			$x = 0;
    	    			foreach($getSectionContent->getResultArray() as $key => $values){
    	    				$dataArray = array();
    	    				for($i = 0 ; $i < count($theTableValues); $i++){
    							if($values['sellerid'] == session()->get('suser_id')){
    								$id = '';
    								$Buy = '<div class="btn-groupe">
    									<span class="btn btn-info btn-sm">My Product</button>
    									
    									</div>';		
    							}
    							else if(session()->get('suser_groupe') == '9'){
    								$id = '';
    								$Buy = '<div class="btn-groupe">
    									<span class="btn btn-info btn-sm">Seller : '.ucfirst($values["sellerusername"]).'</button>
    									
    									</div>';
    							}
    							else {
    								if(array_key_exists($values['id'], $myCombinedArray)){
    									$id = '';
    									$Buy = '
    										<div class="btn-groupe d-grid">
    											<button id="buybtn-'.$values['id'].'" type="button" data-api="removeCartProd-'.$values['id'].'|'.strtolower($data['sectionName']).'" class="btn btn-danger btn-sm">Remove form cart <span class="bx bx-message-x"></span></button>
    										</div>
    									';
    								}
    								else {
    									$id = '';
    									$Buy = '
    										<div class="btn-groupe d-grid">
    											<button id="buybtn-'.$values['id'].'" type="button" data-api="addtocart-'.$values['id'].'|'.strtolower($data['sectionName']).'" class="btn btn-success btn-sm">Add to cart <span class="bx bx-cart"></span></button>
    										</div>
    									';
    								}
    							}
    							if(strpos($theTableValues[$i], ' ') !== false){
    								$dataArray[$i] = $values[str_replace(' ', '',strtolower($theTableValues[$i]))];
    							}
    							else {
    				    			if($theTableValues[$i] == 'price'){
    									$mival = '$'.$values[$theTableValues[$i]];
    								}
    								else {
    									$mival = createTableStyles($theTableTypes[$i], $values[$theTableValues[$i]]);
    								}
    								$dataArray[$i] = $mival;	
    							}			
    	    				}
    	    				//array_unshift($dataArray , $id);
    	    				array_push($dataArray, $Buy);
    	    				$output['data'][] = $dataArray;
    	    			}
    	    			header('Content-Type: application/json');
            			echo json_encode($output);
    					exit();
    				}
    				else {
    					$output['data'][] = array(
    		        		NULL,					
    					);
    					header('Content-Type: application/json');
    					echo json_encode($output);
    					exit();
    				}
    			}
    			else {
    				$output['data'][] = array(
    	        		NULL,					
    				);
    				header('Content-Type: application/json');
    				echo json_encode($output);
    				exit();
    			}
    		}
    		else {
    			$output['data'][] = array(
            		NULL,					
    			);
    			header('Content-Type: application/json');
    			echo json_encode($output);
    			exit();
    		}
        }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

    public function fetchCards(){
        if($this->request->isAJAX()){
        	$verify = verifysection($this->request->getGet('id'));
        	if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9' || 
        		$verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9' ){
        		$output['data'][] = array(
            		NULL,					
    			);
    			header('Content-Type: application/json');
    			echo json_encode($output);
    			exit();
        	}
    		$output = array('data' => array());
    		if(session()->get("logedin") == '1'){
    			if($this->request->getGet('id') != "" && preg_match("/^([a-zA-Z0-9])+$/", $this->request->getGet('id'))){
    				$sessionID = $this->request->getGet('id');
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', $sessionID)->findAll();
    				$countresults = count($Results);
    				$countCarts = count(session()->get('cart'));
    				if($countCarts > 0){
    					$cartsid = [];
    					$types = [];
    					foreach(session()->get('cart') as $kk => $vv){
    						$cartsid[] = $vv['id'];
    						$types[] = $vv['typebuying']; 	
    					}
    					$myCombinedArray = array_combine($cartsid, $types);
    				}
    				else {
    					$myCombinedArray = [];
    				}
    				if($countresults == 1){
    					$data['sectionName'] = $Results[0]['sectioname'];
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `table_".$db->escapeString(strtolower($data['sectionName']))."`");
    	    			$theTable = array();
    	    			$thetypes = array();
    	    			$theTableHeads = array();
    	    			foreach($GetSectionConfigs->getResultArray() as $key => $val){;
    	    				if($val["cellsTypes"] !== 'hide'){
    	    					$theTable[] = $val["cellsName"];
    	    					$thetypes[] = $val["cellsTypes"];
    	    					$theTableHeads[] = $val["cellsHeads"];
    	    				}
    	    			}
    	    			
    	    			$theTableValues = array_values($theTable);
    	    			$theTableTypes = array_values($thetypes);
    	    			$getSectionContent = $db->query("SELECT * FROM `section_".$db->escapeString(strtolower($data['sectionName']))."` WHERE `selled`='0'");
    	    			$x = 0;
    	    			foreach($getSectionContent->getResultArray() as $key => $values){
    	    				$dataArray = array();
    	    				for($i = 0 ; $i < count($theTableValues); $i++){
    							if($values['sellerid'] == session()->get('suser_id')){
    								$Buy = '
    								<div class="button-groupe d-grid">
    									<span class="btn btn-info rounded-3">My Product</span></span>
									</div>';		
    							}
    							else if(session()->get('suser_groupe') == '9'){
    								$id = '';
    								$Buy = '<div class="btn-groupe d-grid">
    									<span class="btn btn-info rounded-3">Seller : '.ucfirst($values["sellerusername"]).'</button>
    									
    									</div>';
    							}
    							else {
    								if(array_key_exists($values['id'], $myCombinedArray)){
    									$Buy = '<button id="buybtn-'.$values['id'].'" type="button" data-api="removeCartProd-'.$values['id'].'|'.strtolower($data['sectionName']).'" class="btn btn-danger btn-block rounded-3">REMOVE FROM CART <span class="bx bx-message-x"></span></button>';
    								}
    								else {
    									$Buy = '<button id="buybtn-'.$values['id'].'" type="button" data-api="addtocart-'.$values['id'].'|'.strtolower($data['sectionName']).'" class="btn btn-success  btn-block rounded-3">ADD TO CART <span class="bx bx-cart"></span></button>';
    								}	
    							}
    
    							$image = isset($values['prodimagezeh']) ? $values['prodimagezeh'] : 'default.png';
    							$title = isset($values['titleprodzeh']) ? $values['titleprodzeh'] : 'Product';
    							$description = isset($values['description']) ? $values['description'] : '';
    							$price = esc($values['price']);
    
    							if(strpos($theTableValues[$i], ' ') !== false){
    								$dataArray[$i] = $values[str_replace(' ', '',strtolower($theTableValues[$i]))];
    							}
    							else {
    				    			if($theTableValues[$i] == 'price'){
    									$mival = '$'.$values[$theTableValues[$i]];
    								}
    								else {
    									$mival = createCardsStyles($theTableTypes[$i], $values[$theTableValues[$i]]);
    								}
    								$dataArray[$theTableHeads[$i]] = $mival;	
    							}			
    	    				}
    	    				array_push($dataArray, $description);
    	    				array_push($dataArray, $image);
    	    				array_push($dataArray, $title);
    	    				array_push($dataArray, $price);
    	    				array_push($dataArray, $Buy);
    	    				$output['data'][] = $dataArray;
    	    			}
    	    			
    	    			$Products = '';
    	    			foreach($output as $k => $v){
    
        					foreach ($v as $MykeyPost => $MyvaluePost) {
        						$descrip = '';
        						foreach($MyvaluePost as $kep => $valp){
        							if($kep!='Price' && !is_numeric($kep)){
        								$descrip .= ucfirst($kep).' : '.ucfirst($valp).'</br>';
        							}
        							$newMYval = array_values($MyvaluePost);
        							$mpdescriptions = $newMYval[count($newMYval)-5];
        							$mpImage = $newMYval[count($newMYval)-4];
        							$mpTitle = $newMYval[count($newMYval)-3];
        							$mpPrice = $newMYval[count($newMYval)-2];
        							$mpBuy = $newMYval[count($newMYval)-1];
        						}
        						$Products .= '

    								<div class="card col-md-2 px-0  rounded-5">
    									<a data-lightbox="'.esc($mpImage).'" href="'.base_url().'/assets/images/products/'.esc($mpImage).'">
    										<img class="card-img-top  rounded-top" style="height:200px" src="'.base_url().'/assets/images/products/'.esc($mpImage).'" alt="Card image cap">
    									</a>
    									<div class="card-body">
    										<h5 class="card-title">'.ucfirst(esc($mpTitle)).'<span class="text-success float-end">$'.number_format($mpPrice, 2, '.', '').'</span></h5>
    										<p class="card-text">'.$descrip.'</p>
    										<p class="card-text">'.nl2br(esc(ucfirst($mpdescriptions))).'</p>
    									</div>
    									<div class="card-footer d-grid">
    										'.$mpBuy.'
    									</div>
    								</div>

    							';
        					}
        				}
        				$response["html"] = $Products;
    	    			header('Content-Type: application/json');
            			echo json_encode($response);
    					exit();
    				}
    				else {
    					$output['data'][] = array(
    		        		NULL,					
    					);
    					header('Content-Type: application/json');
    					echo json_encode($output);
    					exit();
    				}
    			}
    			else {
    				$output['data'][] = array(
    	        		NULL,					
    				);
    				header('Content-Type: application/json');
    				echo json_encode($output);
    				exit();
    			}
    		}
    		else {
    			$output['data'][] = array(
            		NULL,					
    			);
    			header('Content-Type: application/json');
    			echo json_encode($output);
    			exit();
    		}
        }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	/**public function initCreate(){
	    if($this->request->isAJAX()){
    		$verify = verifysection($this->request->getPost('id'));
    		if(null !== $verify){
    		    if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9' || $verify['sellersactivate'] == '0' && session()->get('suser_groupe') != '9' || $verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9'){
            		header('location:'.base_url());
            		exit();
            	}
    		}
    		else {
    		    header('location:'.base_url());
        		exit();
    		}
            	
    		$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			if($this->request->getPost('id') != "" && preg_match("/^([a-zA-Z0-9])+$/", $this->request->getPost('id'))){
    				$secsionID = $this->request->getPost('id');
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', $secsionID)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    
    					$sectionName = $Results[0]['sectioname'];
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `inputs_".$db->escapeString(strtolower($sectionName))."`");
    
    	    			$arrayinputs = $GetSectionConfigs->getResultArray();
    
    					$modalcontent = createForm($arrayinputs, $Results[0]['identifier'], $Results[0]['sectiontype']);
    
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Save', 'functions' => 'data-api="dosave"']);
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    				else {
    					$modalcontent = '<p>An error has been detected, E-001 </p>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    			}
    			else {
    				$modalcontent = '<p>An error has been detected, E-002 </p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
    			$modalcontent = '<p>An error has been detected, E-003 </p>';
    			$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
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

	public function doCreate(){
	    if($this->request->isAJAX()){
    		$verify = verifysection($this->request->getPost('id'));
    		if(null !== $verify){
    		    if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9' || $verify['sellersactivate'] == '0' && session()->get('suser_groupe') != '9' || $verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9'){
            		header('location:'.base_url());
            		exit();
            	}    
    		}
    		else {
    		    echo 'Error !';
    		    exit();
    		}
            	
    		$response = array();
    		$settings = fetchSettings();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			if($this->request->getPost('id') != "" && preg_match("/^([a-zA-Z0-9])+$/", $this->request->getPost('id'))){
    				$secsionID = $this->request->getPost('id');
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', esc($secsionID))->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = $Results[0]['sectioname'];
    					$sectionLable = $Results[0]['sectionlable'];
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `inputs_".strtolower($sectionName)."`");
    	    			$arrayvalidations = $GetSectionConfigs->getResultArray();
    
    	    			$validations = createvalidations($arrayvalidations, $Results[0]['sectiontype']);
    	    			/--if(null !== $this->validate($validations)){
    	    			    var_dump($this->validate($validations));
        			    }
        			    else {
        			        echo "Error";
        			    }
    	    			exit();--/
    	    			if(!$this->validate($validations)){
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
    						foreach ($_FILES as $key => $value) {
    							if($_FILES[$key]['name'] != ''){
    								$image = $this->request->getFile($key);
    								$type = $image->getClientMimeType();
    								
    								$extention = pathinfo($image->getName(), PATHINFO_EXTENSION);
    								
    								$allowedExtentions = ['jpg', 'png', 'jpeg'];
    								if(in_array($extention, $allowedExtentions)){
        								if($type == 'image/png' || $type == 'image/jpg' || $type == 'image/jpeg'){
        									if($image->isValid() && !$image->hasMoved()){
        									    $pattern = '/([\$\.\_\-\ ])/i';
        									    $imagenamestamped = preg_replace($pattern,'',substr(password_hash(time(), PASSWORD_DEFAULT),5,11)).'_prod.'.$extention;
        										$data[$key] = $db->escapeString($imagenamestamped);
        										if($key == 'prodimagezeh'){	
        											$image->move('assets/images/products/', $imagenamestamped);
        										}
        										else {
        											$image->move('assets/images/proofs/', $imagenamestamped);
        										}
        									}	
        								}
    							    }
    							    else {
    							        $data[$key] = 'default.png';
    							    }
    							}
    						}
    						foreach($this->request->getPost() as $key => $val){
    							if(!empty($val) && $val != "" && $key != 'id' && $key != 'hashed'){
    								$data[$key] = $val;
    							}
    						}
    						$data['sellerid'] = session()->get('suser_id');
    						$data['sellerusername'] = session()->get('suser_username');
    						$db->table("`section_".$db->escapeString(strtolower($sectionName))."`")->insert($data);
    						//do notification
    						$dataNotif = [
    							'subject' => ucfirst($sectionLable).' Section',
    							'text' => 'New Stuff has been Added',
    							'url' => base_url().'/market/'.$Results[0]['identifier']
    						];
    						$modelNotif = new NotificationsModel;
    						$modelNotif->save($dataNotif);
    						//update users notification number
    						$usersModel = new UsersModel;
    						$ResUsers = $usersModel->findAll();
    						foreach($ResUsers as $value){
    							$newNotifNumbers = $value['notifications_nb']+1;
    							$dataMNotif = [
    								'notifications_nb' => $newNotifNumbers,
    							];
    							$usersModel->update($value['id'], $dataMNotif);
    						}
    						$getSeller = $usersModel->where(['id' => session()->get('suser_id')])->findAll();
    						if(count($getSeller) == 1){
    							$nbobinsell = $getSeller[0]['seller_nbobjects'] + 1;
    							$dataUpdateSeller = [
    								'seller_nbobjects' => $nbobinsell
    							];
    							$usersModel->update($getSeller[0]['id'], $dataUpdateSeller);
    						}
    						if($settings[0]['telenotif'] == '1'){
    						    $teletext = 'New Stuff was added in '.ucfirst($sectionLable).PHP_EOL.'Click here to buy '.ucfirst($sectionLable).PHP_EOL.base_url();
                                telegram($settings[0]['telebot'], $settings[0]['chatid'], $teletext);
    						}
                            
    						//update section items count
    						$newsectionItems = $Results[0]['itemsnumbers'] + 1;
    						$MysdataItemsSection = [
    							'itemsnumbers' => $newsectionItems
    						];
    						$model->update($Results[0]['id'], $MysdataItemsSection);				
    						//send success response 
    						$modalContent = '<p>'.$sectionName.' Added successfully</p>';
    						$modalTitle = 'Added successfully';
    						$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
    						$response["csrft"] = csrf_hash();
    						header('Content-Type: application/json');
    						echo json_encode($response);
    						exit();
    					}
    				}
    				else {
    					$modalcontent = '<p>An error has been detected, E-001 </p>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Save', 'functions' => 'data-api="edituser-'.$Results[0]['id'].'"']);
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    			}
    			else {
    				$modalcontent = '<p>An error has been detected, E-002 </p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Error !', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
    			$modalcontent = '<p>An error has been detected, E-003 </p>';
    			$response["modal"] = createModal($modalcontent, 'fade animated', 'Error !', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
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

	public function initEdit(){
	    if($this->request->isAJAX()){
    		$verify = verifysection($this->request->getPost('buytype'));
    		if(null !== $verify){
            	if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9' || $verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9'){
            		header('location:'.base_url());
            		exit();
            	}
    		}
    		else {
    		    header('location:'.base_url());
        		exit();
    		}
    		$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			if($this->request->getPost('buytype') != "" 
    			    && preg_match("/^([0-9])+$/", $this->request->getPost('id') ) 
    			    && preg_match("/^([0-9])+$/", $this->request->getPost('buytype') ) 
    			    || preg_match("/^([a-zA-Z0-9])+$/", $this->request->getPost('buytype') )  
    			    && preg_match("/^([0-9])+$/", $this->request->getPost('id') )
    			    ) {
    				$secsionID = $this->request->getPost('buytype');
    				$id = $this->request->getPost('id');
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', $secsionID)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = strtolower($Results[0]['sectioname']);
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `inputs_".$db->escapeString($sectionName)."`");
    	    			$GetSectionData = $db->query("SELECT * FROM `section_".$db->escapeString($sectionName)."` WHERE `id`='".$db->escapeString($id)."'");
    	    			$arrayinputs = $GetSectionConfigs->getResultArray();
    	    			$arraydatas = $GetSectionData->getResultArray();
    	    			$modalcontent = '<form id="createForm" enctype="multipart/form-data">';
        				$modalcontent .= '<div class="row">';
    	    			if(count($arraydatas) == '1'){
    	    				foreach ($arraydatas as $dataKey => $datavalue) {
    	    					$modalcontent .= createFormEdit($arrayinputs, $Results[0]['identifier'], $Results[0]['sectiontype'], esc($datavalue));
    	    				}
    	    			}
    	    			$modalcontent .= '<input type="hidden" name="id" value="'.esc($id).'">';
    	    			$modalcontent .= '<input type="hidden" name="buytype" value="'.$Results[0]['identifier'].'">';
    			    	$modalcontent .= '</div>';
    			    	$modalcontent .= '</form>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Edit Object', '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Update', 'functions' => 'data-api="doedit"']);
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    				else {
    					$modalcontent = '<p>An error has been detected, E-001 </p>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    			}
    			else {
    				$modalcontent = '<p>An error has been detected, E-002 </p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
    			$modalcontent = '<p>An error has been detected, E-003 </p>';
    			$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
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

	public function doEdit(){
	    if($this->request->isAJAX()){
    		$verify = verifysection($this->request->getPost('buytype'));
    		if(null !== $verify){
    		    if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9' || $verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9'){
            		header('location:'.base_url());
            		exit();
            	}    
    		}
    		else {
    		    header('location:'.base_url());
        		exit();
    		}
    		$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			if($this->request->getPost('id') != "" && preg_match("/^([a-zA-Z0-9])+$/i", $this->request->getPost('id'))){
    				$secsionID = $this->request->getPost('buytype');
    				$id = $this->request->getPost('id');
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', $secsionID)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = strtolower($Results[0]['sectioname']);
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `inputs_".$db->escapeString($sectionName)."`");
    	    			$arrayvalidations = $GetSectionConfigs->getResultArray();
    
    	    			$validations = createvalidations($arrayvalidations, $Results[0]['sectiontype']);
    	    			if(!$this->validate($validations)){
    						$ErrorFields = $this->validator->getErrors();
    						$modalTitle = "Validation Error";
    						$modalContent = '';
    						foreach($ErrorFields as $key => $value){
    							$modalContent .= '<p class=""><b>'.$value.'</b></p>';
    						}	
    						$response["fieldslist"] = $ErrorFields;
    						$response["csrft"] = csrf_hash();
    						header('Content-Type: application/json');
    						echo json_encode($response);
    						exit();
    					}
    					else {
    						$usergroupe = session()->get('suser_groupe');
    						$userid = session()->get('suser_id');
    						switch ($usergroupe) {
    							case '1':
    								$GetMSectionData = $db->query("SELECT * FROM `section_".$db->escapeString($sectionName)."` WHERE `id`='".$db->escapeString($id)."' AND `sellerid`='".$db->escapeString($userid)."' AND `selled`='0'");
    							break;
    							case '9':
    								$GetMSectionData = $db->query("SELECT * FROM `section_".$db->escapeString($sectionName)."` WHERE `id`='".$db->escapeString($id)."' AND `selled`='0'");
    							break;
    						}
        					$arraydatas = $GetMSectionData->getResultArray();
        					if(count($arraydatas) == '1'){
        						$sql = [];
    							foreach($this->request->getPost() as $key => $val){
    								if($key != 'id' && $key != 'buytype' && $key != 'hashed'){
    									$data[$key] = $db->escapeString($val);
    									$sql[] = $db->escapeString("`".$key."`")."='".$db->escapeString($val)."'";
    								}
    							}
    							
    							
    							foreach ($_FILES as $key => $value) {
        							if($_FILES[$key]['name'] != ''){
        								$image = $this->request->getFile($key);
        								$type = $image->getClientMimeType();
        								
        								$extention = pathinfo($image->getName(), PATHINFO_EXTENSION);
        								
        								$allowedExtentions = ['jpg', 'png', 'jpeg'];
        								if(in_array($extention, $allowedExtentions)){
            								if($type == 'image/png' || $type == 'image/jpg' || $type == 'image/jpeg'){
            									if($image->isValid() && !$image->hasMoved()){
            									    $pattern = '/([\$\.\_\-\ ])/i';
            									    $imagenamestamped = preg_replace($pattern,'',substr(password_hash(time(), PASSWORD_DEFAULT),5,11)).'_prod.'.$extention;
            										array_push($sql, "`".$db->escapeString($key)."`='".$db->escapeString($imagenamestamped)."'");
            										if($key == 'prodimagezeh'){	
            											$image->move('assets/images/products/', $imagenamestamped);
            										}
            										else {
            											$image->move('assets/images/proofs/', $imagenamestamped);
            										}
            									}	
            								}
        							    }
        							    else {
        							        array_push($sql, "`".$db->escapeString($key)."`='default.png'");
        							    }
        							}
        						}
 
    							$dataKeysme = implode(',', $sql);
    							switch ($usergroupe) {
    								case '1':
    									$updates = $db->query("UPDATE `section_".$db->escapeString($sectionName)."` SET ".$dataKeysme." WHERE `id`='".$db->escapeString($id)."' AND `selled`='0' AND `sellerid`='".$db->escapeString($userid)."'");
    								break;
    								case '9':
    									$updates = $db->query("UPDATE `section_".$db->escapeString($sectionName)."` SET ".$dataKeysme." WHERE `id`='".$db->escapeString($id)."' AND `selled`='0'");
    								break;
    							}
    							
    							$modalContent = '<p>Updated successfully</p>';
    							$modalTitle = 'Updated successfully';
    							$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
    							$response["csrft"] = csrf_hash();
    							
        					}
        					else {
        						$modalcontent = '<p>An error has been detected, E-001 </p>';
    							$response["modal"] = createModal($modalcontent, 'fade animated', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    							$response["csrft"] = csrf_hash();
    							
        					}
        					header('Content-Type: application/json');
    						echo json_encode($response);
    						exit();	
    					}
    				}
    				else {
    					$modalcontent = '<p>An error has been detected, E-001 </p>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    			}
    			else {
    				$modalcontent = '<p>An error has been detected, E-002 </p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
    			$modalcontent = '<p>An error has been detected, E-003 </p>';
    			$response["modal"] = createModal($modalcontent, 'fade animated', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
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

	public function initDelete(){
	    if($this->request->isAJAX()){
    		$verify = verifysection($this->request->getPost('buytype'));
        	if($verify['sectionstatus'] == '0'){
        		header('location:'.base_url());
        		exit();
        	}
    		$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			if($this->request->getPost('buytype') != "" && preg_match("/^([a-zA-Z0-9])+$/i", $this->request->getPost('buytype')) && preg_match("/^([0-9])+$/i", $this->request->getPost('id'))){
    				$secsionID = $this->request->getPost('buytype');
    				$prodid = $this->request->getPost('id');
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', $secsionID)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = $Results[0]['sectioname'];
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `section_".$db->escapeString(strtolower($sectionName))."` WHERE `id`='".$prodid."'");
    
    	    			$arrayinputs = $GetSectionConfigs->getResultArray();
    
    	    			if(count($arrayinputs) === 1){
    	    				if(session()->get('suser_groupe') == '9' || 
    	    					session()->get('suser_groupe') == '1' && $arrayinputs[0]['sellerid'] == session()->get('suser_id')){
    		    				$modalcontent = '<p><span class="text-warning">Warning :</span> Are you sure you want to delete this iteml ?</p>';
    		    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Delete', 'functions' => 'data-api="dodelete-'.$arrayinputs[0]['id'].'|'.$Results[0]['identifier'].'"']);
    	    				}
    	    				else {
    	    					$modalcontent = '<p><span class="text-danger">Error :</span> An error has ben detected.</p>';
    	    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '1', '1', '0');
    	    				}
    	    			}
    	    			else {
    	    				$modalcontent = '<p><span class="text-danger">Error :</span> An error has ben detected.</p>';
    	    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '1', '1', '0');
    	    			}
    					
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    				else {
    					$modalcontent = '<p>An error has ben detected, E-001 </p>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    			}
    			else {
    				$modalcontent = '<p>An error has been detected, E-002 </p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
    			$modalcontent = '<p>An error has been detected, E-003 </p>';
    			$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
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

	public function doDelete(){
	    if($this->request->isAJAX()){
    		$verify = verifysection($this->request->getPost('buytype'));
        	if($verify['sectionstatus'] == '0'){
        		header('location:'.base_url());
        		exit();
        	}
    		$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			if($this->request->getPost('buytype') != ""){
    				$secsionID = $this->request->getPost('buytype');
    				$prodid = $this->request->getPost('id');
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', $secsionID)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = $Results[0]['sectioname'];
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `section_".$db->escapeString(strtolower($sectionName))."` WHERE `id`='".$prodid."'");
    	    			$arrayinputs = $GetSectionConfigs->getResultArray();
    	    			if(count($arrayinputs) === 1){
    	    				if(session()->get('suser_groupe') == '9' || 
    	    					session()->get('suser_groupe') == '1' && $arrayinputs[0]['sellerid'] == session()->get('suser_id')){
    	    					$sellerID = $arrayinputs[0]['sellerid'];
    
    	    					$usersModel = new UsersModel;
    	    					$isuser = $usersModel->where(['id'=> $sellerID])->findAll();
    	    					if(count($isuser) == '1'){
    	    						$newnsellernbitems = $isuser[0]['seller_nbobjects'] - 1;
    	    						$dataSellernbobjct = [
    	    							'seller_nbobjects' => $newnsellernbitems
    	    						];
    	    						$usersModel->update($isuser[0]['id'], $dataSellernbobjct);
    	    					}
    	    					$GetSectionConfigs = $db->query("DELETE FROM `section_".$db->escapeString(strtolower($sectionName))."` WHERE `id`='".$prodid."'");
    	    					$newsectionItems = $Results[0]['itemsnumbers'] - 1;
    							$MysdataItemsSection = [
    								'itemsnumbers' => $newsectionItems
    							];
    							$model->update($Results[0]['id'], $MysdataItemsSection);
    
    		    				$modalcontent = '<p><span class="text-success">Success :</span> Object Deleted</p>';
    		    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '1', '1', '0');
    	    				}
    	    				else {
    	    					$modalcontent = '<p><span class="text-danger">Error :</span> An error has ben detected.</p>';
    	    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '1', '1', '0');
    	    				}
    	    			}
    	    			else {
    	    				$modalcontent = '<p><span class="text-danger">Error :</span> An error has ben detected.</p>';
    	    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '1', '1', '0');
    	    			}
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    				else {
    					$modalcontent = '<p>An error has ben detected, E-001 </p>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    			}
    			else {
    				$modalcontent = '<p>An error has been detected, E-002 </p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
    			$modalcontent = '<p>An error has been detected, E-003 </p>';
    			$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
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

	public function massinitEdit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			header('location:'.base_url().'/');
    			exit();
    		}
    		else {
    			if($this->request->getPost('buytype') != ""){
    				$buytype = $this->request->getPost('buytype');
    				$modelSections = new SectionsModel;
    				$Results = $modelSections->where('identifier', $buytype)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = strtolower($Results[0]['identifier']);
    				}
    			}
    			else {
    				header('location:'.base_url().'/');
    				exit();	
    			}
    			$response = array();
    			$form = '
    				<form id="MasseditUserForm">
    					<div class="form-group row">
    						<div class="col-12">
    							<div class="form-group col-12">
    								<label>Refundable</label>
    								<select class="form-control select2" id="refun" name="refun">
    								    <option>Select</option>
    									<option value="1">Yes</option>
    									<option value="0">No</option>
    								</select>
    								<small class="refun text-danger"></small>
    							</div>
    						</div>
    					</div>
    					<div class="form-group row">
    						<div class="col-12">
    							<label>Price</label>
    							<input type="number" id="price" name="price" class="form-control">
    							<small class="price text-danger"></small>
    							<input type="hidden" name="'.csrf_token().'" value="'.csrf_hash().'">
    							<input type="hidden" name="buytype" value="'.$sectionName.'">
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
    			$modalContent = $form;
    			$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Edit the object', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="massedit"']);
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

	public function massedit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") !== "9" && session()->get("suser_groupe") !== "1"){
    			exit();
    		}
    		else {
    			$response = array();
    			if(null !== $this->request->getPost("refun") && $this->request->getPost("refun") !== ""){
    				$ValidationRulls["refun"] = array(
    		            'rules'  => 'permit_empty|numeric',
    		            'errors' => array(
    		            	'numeric' => 'Invalid Refund option.',
    	            	)
    	         	);
    			}
    			if(null !== $this->request->getPost("price") && $this->request->getPost("price") !== ""){
    				$ValidationRulls["price"] = array(
    		            'rules'  => 'required|numeric',
    		            'errors' => array(
    		            	'numeric' => 'Invalid referals rate.',
    		            	'required' => 'This Price is required.',
    	            	)
    	         	);
    			}
    			if(!$this->validate($ValidationRulls)){
    				$ErrorFields = $this->validator->getErrors();
    				$modalTitle = "Validation Error";
    				$modalContent = '';
    				foreach($ErrorFields as $key => $value){
    					$modalContent .= '<p class=""><b>'.$value.'</b></p>';
    				}	
    				$response["fieldslist"] = $ErrorFields;
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$response = array();
    				if($this->request->getPost('id') != ""){
    					$buytype = $this->request->getPost('buytype');
    					$id = explode(',',$this->request->getPost('id'));
    					$modelSections = new SectionsModel;
    					$Results = $modelSections->where('identifier', $buytype)->findAll();
    					$countresults = count($Results);
    					if($countresults == 1){
    						$sectionName = strtolower($Results[0]['sectioname']);
    		    			$db = db_connect();
    		    			$usergroupe = session()->get('suser_groupe');
    		    			$userid = session()->get('suser_id');
    		    			foreach($id as $t => $mids){
    			    			switch ($usergroupe) {
    			    				case '9':
    			    					$GetSectionConfigs = $db->query("SELECT * FROM `section_".$db->escapeString($sectionName)."` WHERE `id`='".$db->escapeString($mids)."' AND `selled`='0'");		
    		    					break;
    			    				case '1':
    			    					$GetSectionConfigs = $db->query("SELECT * FROM `section_".$db->escapeString($sectionName) ."` WHERE `id`='".$db->escapeString($mids)."' AND `sellerid`='".$db->escapeString($userid)."' AND `selled`='0'");	
    		    					break;
    			    			}
    			    			$ResultsItem =  $GetSectionConfigs->getResultArray();
    			    			if(count($ResultsItem) == 1){
    			    				$data = [];
    								foreach($this->request->getPost() as $key => $val){
    									if($val != ""){
    										if($key != "csrf_test_name" && $key != 'buytype' && $key != 'id'){
    											$data[] = "`".$db->escapeString($key) ."`='".$db->escapeString($val)."'";
    										}											
    									}	
    								}
    								$dataSql = implode(',', $data);
    								
    								$ids = explode(',', $this->request->getPost('id'));
    								foreach($ids as $y => $es){
    									switch ($usergroupe) {
    										case '1':
    											$updates = $db->query("UPDATE `section_".$db->escapeString($sectionName)."` SET ".$dataSql." WHERE `id`='".$db->escapeString($es)."' AND `selled`='0' AND `sellerid`='".$db->escapeString($userid)."'");
    										break;
    										case '9':
    											$updates = $db->query("UPDATE `section_".$db->escapeString($sectionName)."` SET ".$dataSql." WHERE `id`='".$db->escapeString($es)."' AND `selled`='0'");
    										break;
    									}
    								}
    								$modalContent = '<p>Updated successfully</p>';
    								$modalTitle = 'Updated successfully';
    								$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
    								$response["csrft"] = csrf_hash();
    								
    	    					}
    	    					else {
    	    						$modalcontent = '<p>An error has been detected, E-001 </p>';
    								$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Save', 'functions' => 'data-api="edituser-'.$Results[0]['id'].'"']);
    								$response["csrft"] = csrf_hash();
    								
    	    					}
    	    					header('Content-Type: application/json');
    							echo json_encode($response);
    							exit();	
    		    			}
    		    		}
    		    		else {
    		    			$modalcontent = '<p>An error has been detected, E-001 </p>';
    						$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Save', 'functions' => 'data-api="edituser-'.$Results[0]['id'].'"']);
    
    						$response["csrft"] = csrf_hash();
    						header('Content-Type: application/json');
    						echo json_encode($response);
    						exit();
    		    		}
    				}
    				else {
    					$modalContent = '<p>Object not selected. E003</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					$response["csrft"] = csrf_hash();
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

	public function massrmuserinit(){
        if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			header('location:'.base_url().'/');
    			exit();
    		}
    		else {
    			if($this->request->getPost('id') != ""){
    				$buytype = $this->request->getPost('id');
    				$modelSections = new SectionsModel;
    				$Results = $modelSections->where('identifier', $buytype)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = $Results[0]['identifier'];
    				}
    			}
    			else {
    				header('location:'.base_url().'/');
    				exit();	
    			}
    			
    			$id = explode(',',$this->request->getPost('id'));
    			$countResults = count($id);
    			if($countResults >= 2){
    				$modalContent = '<p>Do you realy wan to remove those items ?</p>';
    				$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "1",['text' => 'Delete', 'functions' =>'data-api="massrmuser"']);
    			}
    			else {
    				$modalContent = '<p>Object not found. E002</p>';
    				$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    			}
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

	public function massrmuser(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			header('location:'.base_url().'/');
    			exit();
    		}
    		else {
    			if($this->request->getPost('id') != ""){
    				$buytype = $this->request->getPost('buytype');
    				$modelSections = new SectionsModel;
    				$Results = $modelSections->where('identifier', $buytype)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = $Results[0]['sectioname'];
    				}
    			}
    			else {
    				header('location:'.base_url().'/');
    				exit();	
    			}
    			$id = explode(',', $this->request->getPost('id'));
    			$db = db_connect();
    			$countResults = count($id);
    			if($countResults >= 2){
    				$susergroupe = session()->get("suser_groupe");
    				$suserid = session()->get("suser_id");
    				foreach($id as $m => $ids){
    					switch($susergroupe){
    						case '9':
    	    					$GetSectionConfigs = $db->query("DELETE FROM `section_".$db->escapeString($sectionName)."` WHERE `id`='".$db->escapeString($ids)."' AND `selled`='0'");		
        					break;
    	    				case '1':
    	    					$GetSectionConfigs = $db->query("DELETE FROM `section_".$db->escapeString($sectionName) ."` WHERE `id`='".$db->escapeString($ids)."' AND `sellerid`='".$db->escapeString($userid)."' AND `selled`='0'");	
        					break;
    					}
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
    					$sectionItems = $ModelSection->where(['identifier' => '1'])->findAll();
    					if(count($sectionItems) == 1 ){
    						$newsectionItems = $sectionItems[0]['itemsnumbers']-1;
    						$MysdataItemsSection = [
    							'itemsnumbers' => $newsectionItems
    						];
    
    						$secid = $sectionItems[0]['id'];
    						$ModelSection->update($secid, $MysdataItemsSection);
    					}
    				}	
    				$modalContent = '<p>Item Deleted.</p>';
    				$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");				
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$modalContent = '<p>Object not found. E002</p>';
    				$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
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
	} **/
}
