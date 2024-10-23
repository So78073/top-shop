<?php

namespace App\Controllers;
use App\Models\MyitemsModel;
use App\Models\UsersModel;
use App\Models\CardsModel;
use App\Models\SectionsModel;
use CodeIgniter\HTTP\Response;


class Myorders extends BaseController
{
	private function sectionsMenuCreator(){
		$sectionsModel = new SectionsModel;
		$activesections = $sectionsModel->findAll();
		$ActiveSections = [];
		foreach ($activesections as $key => $value) {
			if($value['sectionstatus'] == '1'){
				$ActiveSections[] = [$value["identifier"], $value['sectionlable'], $value['sectionicon']];
			}
		}
		return $ActiveSections;
	}

	private function CrreateTableHeaders($id){
		$activesections = $this->sectionsMenuCreator();
		if($id != null){
			if($id == '1'){
				$tableheaderHtml = '<table id="TabelStock" class="table table-hover" style="width:100%">';
				$tableheaderHtml .= '<thead>';
				$tableheaderHtml .= '<tr>';
				$tableheaderHtml .= '<th><input type="checkbox" id="chekall"></th>';
				$tableheaderHtml .= '<th>Card Informations</th>';
				$tableheaderHtml .= '<th>Report</th>';
				$tableheaderHtml .= '<th>Check Status</th>';
				$tableheaderHtml .= '<th>Copy</th>';
				$tableheaderHtml .= '<th>Download</th>';
				$tableheaderHtml .= '<th>Delete</th>';
				$tableheaderHtml .= '</tr>';
				$tableheaderHtml .= '</thead>';
				$tableheaderHtml .= '</table>';
			}
			else {
				$tableheaderHtml = '<table id="TabelStock" class="table table-hover" style="width:100%">';
				$tableheaderHtml .= '<thead>';
				$tableheaderHtml .= '<tr>';
				$tableheaderHtml .= '<th>Details</th>';
				$tableheaderHtml .= '<th>Copy</th>';
				$tableheaderHtml .= '<th>Dispute</th>';
				$tableheaderHtml .= '<th>Delete</th>';
				$tableheaderHtml .= '</tr>';
				$tableheaderHtml .= '</thead>';
				$tableheaderHtml .= '</table>';
			}
		}
		else {
			if($activesections[0][0] == '1'){
				$tableheaderHtml = '<table id="TabelStock" class="table table-hover" style="width:100%">';
				$tableheaderHtml .= '<thead>';
				$tableheaderHtml .= '<tr>';
				$tableheaderHtml .= '<th><input type="checkbox" id="chekall"></th>';
				$tableheaderHtml .= '<th>Card Informations</th>';
				$tableheaderHtml .= '<th>Check Status</th>';
				$tableheaderHtml .= '<th>Copy</th>';
				$tableheaderHtml .= '<th>Download</th>';
				$tableheaderHtml .= '<th>Delete</th>';
				$tableheaderHtml .= '</tr>';
				$tableheaderHtml .= '</thead>';
				$tableheaderHtml .= '</table>';
			}
			else {
				$tableheaderHtml = '<table id="TabelStock" class="table table-hover" style="width:100%">';
				$tableheaderHtml .= '<thead>';
				$tableheaderHtml .= '<tr>';
				$tableheaderHtml .= '<th>Details</th>';
				$tableheaderHtml .= '<th>Copy</th>';
				$tableheaderHtml .= '<th>Dispute</th>';
				$tableheaderHtml .= '<th>Delete</th>';
				$tableheaderHtml .= '</tr>';
				$tableheaderHtml .= '</thead>';
				$tableheaderHtml .= '</table>';
			}
		}

		return $tableheaderHtml;
	}

	private function createNavigations($id){
		$activesections = $this->sectionsMenuCreator();
		$menu = '';
		if($id != null){
			foreach ($activesections as $key => $value) {
				if($id == $value[0]){
					$active = 'active';
				}
				else {
					$active = '';
				}
				$menu .= '<li class="nav-item">
						<a class="nav-link '.$active.'"  href="'.base_url().'/myorders/'.base64_encode($value[0]).'" >
							<span class="bx '.$value[2].'"></span> 
							'.$value[1].'
						</a>
		        	</li>';
			}
		}
		else {		
			foreach ($activesections as $key => $value) {
				if($key == 0){
					$active = 'active';
				}
				else {
					$active = '';
				}
				$menu .= '<li class="nav-item">
						<a class="nav-link '.$active.'"  href="'.base_url().'/myorders/'.base64_encode($value[0]).'" >
							<span class="bx '.$value[2].'"></span> 
							'.$value[1].'
						</a>
		        	</li>';
			}		
		}
		return $menu;
	}

	public function index($id = null){
		if(session()->get("logedin") == "1"){
			if($id != null){
				$id = base64_decode($id);
			}
			$data = [];
			$settings = fetchSettings();
			$mycart = getCart();
			$sectionsModel = new SectionsModel;
			$tableheaderHtml = $this->CrreateTableHeaders($id);
			$data["tableheaderHtml"] = $tableheaderHtml;
			$data["activesections"] = $this->createNavigations($id);
			$data["nbitemscart"] = $mycart[0];
			$data["cartInnerHtml"] = $mycart[1];
			$data["settings"] = $settings;
			$data["sectionName"] = "My Orders";
			echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("myorders");
            echo view("assets/footer");
            echo view("assets/scripts");	
		}
		else {
			header('location:'.base_url().'/login');
			exit();
		}
	}

	public function fetchTable(){
		if(session()->get("logedin") == '1'){
			//var_dump($this->request->getPost("id"));
			//exit();
			if($this->request->isAJAX()){
				$sectionModel = new SectionsModel;
				$settings = fetchSettings();
				if(null !== $this->request->getGet("id")){
					$id = base64_decode($this->request->getGet("id"));
				}
				else {
					$activesections = $sectionModel->where(['sectionstatus' => '1'])->findAll();
					foreach($activesections[0] as $key => $val){
						if($key == 'identifier'){
							$id = $val;	
						}
					}	
				}
				if(preg_match("/^([a-z0-9])+$/i", $id)){
					//$id = $this->request->getPost("id");
					//$sectionModel = new SectionsModel;
					$myordersModel = new MyitemsModel;
					$userID = session()->get("suser_id");
					$GetSectionInfo = $sectionModel->where(['identifier' => $id])->findAll();
					if(count($GetSectionInfo) == 1){
						if($id == "1"){
							//credit cards
							$GetMyOrders = $myordersModel->where(['userid' => $userID, 'typeid' => '1'])->orderby('id', 'desc')->findAll();
							$output = array('data' => array());
							if(count($GetMyOrders) > 0){
								foreach ($GetMyOrders as $key => $value) {
									if($settings[0]['ccopenreport'] == '1'){
										$ccopenreport = '<span class="btn btn-sm btn-info dispute" data-api="dispute-'.$value["id"].'" id="dispute-'.$value["id"].'" data-api="dispute-'.$value["id"].'">Open Report</span>';	
									}
									else {
										$ccopenreport = '';
									}
									if($value["refundible"] == "1"){
										$now = time() - strtotime($value["date"]);
										if($now <= $settings[0]['ccchecktimeout'] && $value["checked"] == '0'){
											$id = '<input type="checkbox" class="chk" data-id="'.$value['prodid'].'">';	
										}
										else {
											$id = '';
										}
										switch ($value["gb"]) {
											case '0':
												if($now <= $settings[0]['ccchecktimeout'] && $value["checked"] == '0'){
													$status = '<span class="btn btn-sm btn-info ccstatus" id="'.$value["id"].'">New</span>';
												}
												else {
													$status = '<span class="btn btn-sm btn-danger">Timeout</span>';
												}
											break;
											case '1':
												$status = '<span class="btn btn-sm btn-success">Approved</span>';
											break;
											case '2':
												$status = '<span class="btn btn-sm btn-danger">Refunded</span>';
											break;
										}
									}
									else {
										$id = '';
										$status = '<span class="btn btn-sm btn-dark">Non-refundable</span>';
									}

									$ccinfo = $value['details'];
									$copy = '<button class="btn btn-sm btn-warning" data-api="copy-this"><span class="bx bx-copy"></span></button>';
									$downloadbtn = '<a href="'.base_url().'/myorders/downloadcc/'.$value['id'].'" class="btn btn-sm btn-indigo"><span class="bx bx-chevrons-down"></span></a>';
									$deletebtn = '<button type="button" data-api="initDelete-'.$value['id'].'" class="btn btn-sm btn-danger"><span class="bx bx-trash"></span></button>';
									$output['data'][] = array(
				                		$id,
				                		esc($ccinfo),
				                		$ccopenreport,
				                		$status,
				                		$copy,
				                		$downloadbtn,
				                		$deletebtn,					
									);
								}
								header('Content-Type: application/json');
								echo json_encode($output);
								exit();
							}
							else {
								$output['data'][] = array(
									NULL,
				            		NULL,
									NULL,
									NULL,
									NULL,
									NULL,
								);
								header('Content-Type: application/json');
								echo json_encode($output);
								exit(); 
							}
						}
						else {
							$GetMyOrders = $myordersModel->where(['userid' => $userID, 'typeid' => $id])->orderby('id', 'desc')->findAll();
							$output = array('data' => array());
							if(count($GetMyOrders) > 0){
								foreach ($GetMyOrders as $key => $value) {
									
									$now = time() - strtotime($value["date"]);
									switch ($value["reported"]) {
										case '0':
											if($now <= $settings[0]['ccchecktimeout'] && $value["refunded"] == '0'){
												$status = '<button data-api="initcreatelog" type="button" class="btn btn-primary btn-sm">Open dispute</button>';
											}
											else {
												$status = '<span class="btn btn-sm btn-danger">Timeout</span>';
											}
										break;
										case '1':
											$status = '<span class="btn btn-sm btn-danger">Refunded</span>';
										break;
									}
									

									$details = $value['details'];
									$copy = '<button class="btn btn-sm btn-warning" data-api="copy-this"><span class="bx bx-copy"></span></button>';
									$downloadbtn = '<a href="'.base_url().'/myorders/downloadcc/'.$value['id'].'" class="btn btn-sm btn-indigo"><span class="bx bx-chevrons-down"></span></a>';
									$deletebtn = '<button type="button" data-api="initDelete-'.$value['id'].'" class="btn btn-sm btn-danger"><span class="bx bx-trash"></span></button>';
									$output['data'][] = array(
				                		$details,
				                		$copy,
				                		$status,
				                		$deletebtn,					
									);
								}
								header('Content-Type: application/json');
								echo json_encode($output);
								exit();
							}
							else {
								$output['data'][] = array(
									NULL,
				            		NULL,
									NULL,
									NULL,
								);
								header('Content-Type: application/json');
								echo json_encode($output);
								exit(); 
							}
						}
					}
					else {
						echo "Nice try x) 1";
						exit();		
					}
				}
				else {
					echo "Nice try x) 2";
					exit();	
				}
			}
			else {
				echo "Nice try x) 3";
				exit();
			}
		}
		else {
			header('location:'.base_url().'/login');
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
			$myimtemsModal = new MyitemsModel;
			$userModel = new UsersModel;
			$getUserInfo = $userModel->where('id', session()->get('suser_id'))->findAll();
			$Total = count($ids);
			$x = 0;
			$good = 0;
			$bad = 0;
			if(count($getUserInfo) == 1){
				$refundsum = 0;
				$checkcost = 0;
		    	foreach($ids as $val){
		    		$x++;
		    		if(preg_match("/^([0-9])+$/i", $val) == true){
		    			$getProductInfos = $myimtemsModal->where([
		    				"prodid" => $val, 
		    				"typeid" => '1', 
		    				"userid" => session()->get('suser_id'), 
		    				"checked" => '0',
		    				"refunded" => '0',
		    				"refundible" => '1'
		    			])->findAll();
		    			if(count($getProductInfos) == '1'){
		    				$details = explode("|", $getProductInfos[0]["details"]);
		    				$Checkresults = [];		    					
		    				if(strpos($details[1] , '/')){
								$parts = explode('/', $details[1]);
								$details[1] = $parts[0];
								$details[1] .= '|'.$parts[1];
							}
							if($settings[0]["checkerused"] == "1"){
								$doCheckRequ = luxchek($details, $settings[0]["luxorchecjerapi"], $settings[0]["luxorcheckeruser"]);
								if(null !== $doCheckRequ){
									$check = json_decode($doCheckRequ, true);
									$check["auth_message"] = 'Declined';
									if($check["auth_message"] == 'Approved'){
										$good++;
										$Checkresults["id"] = $getProductInfos[0]["id"];
										$Checkresults["result"] = "1";
										$data = [
											'checked' => '1',
											'gb' => '1',
										];
										$myimtemsModal->update($getProductInfos[0]['id'], $data);
										$checkcost =  $checkcost+$settings['0']['cccheckercost'];
										$getUserMBalance = $userModel->where('id', session()->get('suser_id'))->findAll();
										$newuserbalances = $getUserMBalance[0]['balance'] - $checkcost;
										$Checkresults["balance"] = '$'.number_format($newuserbalances, 2, '.', '');
										
									}
									else if($check["auth_message"] == 'Declined') {
										$bad++;
										$price = $getProductInfos[0]['price'];
										$refundsum = $refundsum + $getProductInfos[0]['price']-$settings['0']['cccheckercost'];
										$GetSellerInfo = $userModel->where('id', $getProductInfos[0]['sellerid'])->findAll();
										if(count($GetSellerInfo) == 1){
											$newSellerBalance = $GetSellerInfo[0]["seller_balance"] - $getProductInfos[0]['price'];
											$UpdatedSellerBalance = [
												'seller_balance' => $newSellerBalance,
											];
											$userModel->update($GetSellerInfo[0]['id'], $UpdatedSellerBalance);
										}
										$getUserMBalance = $userModel->where('id', session()->get('suser_id'))->findAll();
										$dataUpdateProduct = [
											'checked' => '1',
											'gb' => '2',
											'refunded' => '1',
										];
										$myimtemsModal->update($getProductInfos[0]['id'], $dataUpdateProduct);
										$cardsModel = new CardsModel;
										$dataCard = [
											'refunded' => '1',
										];
										$cardsModel->update($getProductInfos[0]['prodid'],$dataCard);
										$Checkresults["id"] = $getProductInfos[0]["id"];
										$Checkresults["result"] = "0";
										$Checkresults['balances'] = $refundsum;
										$newuserbalance = $settings['0']['cccheckercost']+$refundsum;
										if($newuserbalance > $getUserMBalance[0]['balance']){
											$newuserbalances = $settings['0']['cccheckercost']+$refundsum - $getUserMBalance[0]['balance'];
										}
										else {
											$newuserbalances =  $getUserMBalance[0]['balance'] - $settings['0']['cccheckercost']+$refundsum;
										}

										$Checkresults["balance"] = '$'.number_format($newuserbalances, 2, '.', '');
									}
									else {
										$Checkresults["id"] = $getProductInfos[0]["id"];
										$Checkresults["result"] = "3";	
									}

								}
								else {
									$Checkresults["id"] = $getProductInfos[0]["id"];
									$Checkresults["result"] = "3";
								}	
							}
							//chk Chekcer 
							else {
								$doCheckRequ = chkchecker($details, $settings[0]["luxorchecjerapi"]);
								if(null !== $doCheckRequ){
									$check = $doCheckRequ;
									$check = 'Declined';
									if($check == ' Approved'){
										$good++;
										$Checkresults["id"] = $getProductInfos[0]["id"];
										$Checkresults["result"] = "1";
										$data = [
											'checked' => '1',
											'gb' => '1',
										];
										$myimtemsModal->update($getProductInfos[0]['id'], $data);
										$checkcost =  $checkcost+$settings['0']['cccheckercost'];
										$getUserMBalance = $userModel->where('id', session()->get('suser_id'))->findAll();
										$newuserbalances = $getUserMBalance[0]['balance'] - $checkcost;
										$Checkresults["balance"] = '$'.number_format($newuserbalances, 2, '.', '');
										
									}
									else if($check == 'Declined') {
										$bad++;
										$price = $getProductInfos[0]['price'];
										$refundsum = $refundsum + $getProductInfos[0]['price']-$settings['0']['cccheckercost'];
										$GetSellerInfo = $userModel->where('id', $getProductInfos[0]['sellerid'])->findAll();
										if(count($GetSellerInfo) == 1){
											$newSellerBalance = $GetSellerInfo[0]["seller_balance"] - $getProductInfos[0]['price'];
											$UpdatedSellerBalance = [
												'seller_balance' => $newSellerBalance,
											];
											$userModel->update($GetSellerInfo[0]['id'], $UpdatedSellerBalance);
										}
										$getUserMBalance = $userModel->where('id', session()->get('suser_id'))->findAll();
										$dataUpdateProduct = [
											'checked' => '1',
											'gb' => '2',
											'refunded' => '1',
										];
										$myimtemsModal->update($getProductInfos[0]['id'], $dataUpdateProduct);
										$cardsModel = new CardsModel;
										$dataCard = [
											'refunded' => '1',
										];
										$cardsModel->update($getProductInfos[0]['prodid'],$dataCard);
										$Checkresults["id"] = $getProductInfos[0]["id"];
										$Checkresults["result"] = "0";
										$Checkresults['balances'] = $refundsum;
										$newuserbalance = $settings['0']['cccheckercost']+$refundsum;
										if($newuserbalance > $getUserMBalance[0]['balance']){
											$newuserbalances = $settings['0']['cccheckercost']+$refundsum - $getUserMBalance[0]['balance'];
										}
										else {
											$newuserbalances =  $getUserMBalance[0]['balance'] - $settings['0']['cccheckercost']+$refundsum;
										}

										$Checkresults["balance"] = '$'.number_format($newuserbalances, 2, '.', '');
									}
									else {
										$Checkresults["id"] = $getProductInfos[0]["id"];
										$Checkresults["result"] = "3";	
									}

								}
								else {
									$Checkresults["id"] = $getProductInfos[0]["id"];
									$Checkresults["result"] = "3";
								}
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
		    	if($checkcost > 0){
		    		$newUserBalance = $getUserInfo[0]['balance'] - $checkcost;
					$upDatedUserBalance = [
						'balance' => $newUserBalance,
					];
					$userModel->update($getUserInfo[0]['id'], $upDatedUserBalance);
		    	}
		    	if($refundsum > 0){
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
		    	}
		    	return $this->response->setJSON('');
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

	public function initDelete(){
		if(session()->get('logedin') == '1'){
			$response = array();
			if($this->request->isAJAX()){
				if(null !== $this->request->getPost('id') && preg_match("/^([0-9])+$/i", $this->request->getPost('id'))){
					$id = $this->request->getPost('id');
					$myimtemsModal = new MyitemsModel;
					$isProduct = $myimtemsModal->where(['id'=> $id, 'userid' => session()->get('suser_id')])->findAll();
					if(count($isProduct) == 1){
						$modalcontent = '<p><span class="text-warning">Warning :</span> Are you sure you want to delete this iteml ?</p>';
	    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '0', '1', '1', 
	    				['text' => 'Delete', 'functions' => 'data-api="dodelete-'.$isProduct[0]['id'].'|1"']);
	    				echo json_encode($response);
	    				exit();
					}
					else {
						$modalcontent = '<p><span class="text-warning">Warning :</span> Error 001</p>';
	    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Error !', '', 'modal-lg ', '1', '1', '0', '0', '0');
	    				echo json_encode($response);
	    				exit();
					}
				}
				else {
					$modalcontent = '<p><span class="text-warning">Warning :</span> Error 002</p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Error !', '', 'modal-lg', '1', '1', '0', '0', '0');
    				echo json_encode($response);
    				exit();
				}
			}
			else {
				echo "Nice try x)";
				exit();
			}
		}
		else {
			header("location:".base_url());
			exit();
		}
	}

	public function doDelete(){
		if(session()->get("logedin") == '1'){
		    if($this->request->isAJAX()){
				if(null !== $this->request->getPost('id') && preg_match("/^([0-9])+$/i", $this->request->getPost('id'))){
					$id = $this->request->getPost('id');
					$Model = new MyitemsModel;
					
					$Results = $Model->where(['id' => $id, 'userid' => session()->get('suser_id')])->find();
								
					$countResults = count($Results);
					if($countResults == 1){
						$Model->delete($Results[0]["id"]);
						$modalContent = '<p>Item Deleted.</p>';
						$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
					}
					else {
						$modalContent = '<p>Error. E001</p>';
						$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
					}
					$response["csrft"] = csrf_hash();
					header('Content-Type: application/json');
					echo json_encode($response);
				}
				else {
					$modalContent = '<p>Object not selected. E003</p>';
					$response["modal"] = createModal($modalContent, 'fade animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
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
		else {
			header('location:'.base_url());
			exit();
		}
	}

	public function InitemptyItems(){
		if(session()->get("logedin") == '1'){
		    if($this->request->isAJAX()){
		    	$response = array();
		    	$modalcontent = '<p><span class="text-warning">Warning :</span> Are you sure you want to Empty your items folder ?</p>';
		    	$modalcontent .= '<p><span class="text-danger">This action will delete forever all your bought items</p>';
				$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg', '1', '1', '0', '1', '1', 
				['text' => 'Delete', 'functions' => 'data-api="emtyitems"']);
				echo json_encode($response);
				exit();
		    }
	    	else {
	        	echo "Nice try ;)";
	        	exit();
	        }
	    }
	    else {
			header('location:'.base_url());
			exit();
		}
	}

	public function emptyItems(){
		if(session()->get("logedin") == '1'){
		    if($this->request->isAJAX()){
		    	$response = array();
		    	$Model = new MyitemsModel;
		    	$Results = $Model->where('userid',session()->get('suser_id'))->find();			
				if(count($Results) > 0){
					foreach ($Results as $key => $value) {
						$Model->delete($value['id']);
					}
					
					$modalContent = '<p>Items Deleted.</p>';
					$response["modal"] = createModal($modalContent, 'fade animated', 'Success', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
				}
				else {
					$modalContent = '<p>Error. E001</p>';
					$response["modal"] = createModal($modalContent, 'fade animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
				}
				echo json_encode($response);
				exit();
		    }
	    	else {
	        	echo "Nice try ;)";
	        	exit();
	        }
	    }
	    else {
			header('location:'.base_url());
			exit();
		}
	}

	public function downloadcc($id){
		if(session()->get("logedin") == true){
			if(null !== $id){
				if(preg_match("/^([0-9])+$/i", $id)){
					$cc = '';
					$model = new MyitemsModel;
					$Results = $model->where(['id' => $id, 'userid' => session()->get("suser_id")])->findAll();
					$countres = count($Results);
					if($countres > 0){
						foreach ($Results as $key => $value) {
							$cc .= $value['details'];
						}
						header('Content-Description: File Transfer');
					    header('Content-Type: application/octet-stream');
					    header('Content-disposition: attachment; filename=cc.txt');
					    header('Content-Length: '.strlen($cc));
					    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					    header('Expires: 0');
					    header('Pragma: public');
					    echo $cc;
					    exit;
					}
					else {
						header('location:'.base_url().'/myorders');
						exit();
					}
				}
				else {
					echo "Nice try x) 1";
					exit();
				}
			}
			else {
				echo "Nice try x)";
				exit();
			}


		}
		else {
			header('location:'.base_url().'/myorders');
			exit();
		}
	}

	public function downloadccToday(){
		if(session()->get("logedin") == true){
			$cc = '';
			$model = new MyitemsModel;
			$ndate = new \DateTime();
			$date = $ndate->format('Y-m-d');
			$Results = $model->like(['userid' => session()->get("suser_id"), 'date' => $date])->findAll();
			$countres = count($Results);
			if($countres > 0){
				foreach ($Results as $key => $value) {
					$cc .= $value['details'].PHP_EOL;
				}
				header('Content-Description: File Transfer');
			    header('Content-Type: application/octet-stream');
			    header('Content-disposition: attachment; filename=cc.txt');
			    header('Content-Length: '.strlen($cc));
			    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			    header('Expires: 0');
			    header('Pragma: public');
			    echo $cc;
			    exit;
			}
			else {
				header('location:'.base_url().'/myorders');
				exit();
			}

		}
		else {
			header('location:'.base_url().'/myorders');
			exit();
		}
	}

	public function downloadAll(){
		if(session()->get("logedin") == true){
			$cc = '';
			$model = new MyitemsModel;
			$id= $this->request->getGet('id');
			$ndate = new \DateTime();
			$date = $ndate->format('Y-m-d');
			$Results = $model->like(['userid' => session()->get("suser_id")])->findAll();
			$countres = count($Results);
			if($countres > 0){
				foreach ($Results as $key => $value) {
					$cc .= $value['details'].PHP_EOL;
				}
				header('Content-Description: File Transfer');
			    header('Content-Type: application/octet-stream');
			    header('Content-disposition: attachment; filename=cc.txt');
			    header('Content-Length: '.strlen($cc));
			    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			    header('Expires: 0');
			    header('Pragma: public');
			    echo $cc;
			    exit;
			}
			else {
				header('location:'.base_url().'/myorders');
				exit();
			}

		}
		else {
			header('location:'.base_url().'/myorders');
			exit();
		}
	}

}


	