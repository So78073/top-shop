<?php

namespace App\Controllers;
use App\Models\PayementsModel;
use App\Models\UsersModel;
use App\Models\NotificationsModel;
use App\Models\WithdrawrequestsModel;
class Historywithdraws extends BaseController
{
	public function index(){
		if(session()->get("logedin") == '1'){
			$data = [];
            $settings = fetchSettings();
            $mycart = getCart();
            $data["nbitemscart"] = $mycart[0];
            $data["cartInnerHtml"] = $mycart[1];
            $data["settings"] = $settings;
			$model = new UsersModel;
			$Results = $model->where('`id`' , session()->get("suser_id"))->findAll();
			$data["results"] = $Results;
			$data["sectionName"] = "Withdraw History";
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("historywithdraws");
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
    		$output = $output = array('data' => array());
    		if(session()->get("logedin") == '1'){
    			$model = new WithdrawrequestsModel;
    			switch (session()->get('suser_groupe')) {
    				case '1':
    					$Results = $model->where(['userid'=> session()->get("suser_id")])->findAll();
    				break;
    				
    				default:
    					$Results = $model->orderby("status  desc, date asc")->findAll();
    				break;
    			}
    			$countresults = count($Results);
    			if($countresults > 0){
    				foreach ($Results as $value) {
    	                $id = '#'.$value["id"];
    	                $ndate = new \DateTime($value["date"]);
    					$sum = '$'.$value["sum"];
    					$wallet = $value["userwallet"];
    
    					switch($value["status"]){
    						case '1':
    							$status = '<span class="btn btn-info btn-sm">In Review</span>';
    						break;
    						case '2':
    							$status = '<span class="btn btn-success btn-sm">Accepted</span>';
    						break;
    						case '3':
    							$status = '<span class="btn btn-danger btn-sm">Declined</span>';
    						break;
    					}
    					$createdat = $ndate->format('d/m/Y H:s:i');
    
    					$buttons = '
    						<div class="btn-group" role="group">
    	                  		<button type="button" class="btn btn-primary dropdown-toggle btn-sm " data-bs-toggle="dropdown" aria-expanded="false">
    	                        Manage
    	                      	</button>
    	                      	<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
    	                        	<li>
    	                        		<a data-api="accptdecline-'.$value['id'].'|2"  class="dropdown-item" href="javascript:void(0);">
    	                        			<span class="fa fa-edit"></span> Accept 
    	                    			</a>
    	                			</li>
    	                        	<li>
    	                        		<a data-api="accptdecline-'.$value['id'].'|3"  class="dropdown-item" href="javascript:void(0);">
    	                        			<span class="fa fa-trash"></span> Decline 
    	                        		</a>
    	                    		</li>
    	                      	</ul>
    	                    </div>
    					';
    
    					switch(session()->get('suser_groupe')){
    						case '1':
    							$output['data'][] = array(
    				        		esc($id),
    								esc($createdat),		
    								esc($sum),
    								esc($wallet),					
    								$status
    							);
    						break;
    						case '9':
    							$output['data'][] = array(
    				        		esc($id),
    								esc($createdat),		
    								esc($sum),
    								esc($wallet),					
    								$status,
    								$buttons
    							);
    						break;
    					}	
    				}
    				echo json_encode($output);
    				exit();
    			}
    			else {
    				switch(session()->get('suser_groupe')){
    					case '1':
    						$output['data'][] = array(
    			        		null,
    							null,		
    							null,
    							null,					
    							null
    						);
    					break;
    					case '9':
    						$output['data'][] = array(
    			        		null,
    							null,		
    							null,
    							null,					
    							null,
    							null
    						);
    					break;
    				}
    				echo json_encode($output);
    				exit();
    			}
    
    		}
    		else {
    			switch(session()->get('suser_groupe')){
    				case '1':
    					$output['data'][] = array(
    		        		null,
    						null,		
    						null,
    						null,					
    						null
    					);
    				break;
    				case '9':
    					$output['data'][] = array(
    		        		null,
    						null,		
    						null,
    						null,					
    						null,
    						null
    					);
    				break;
    			}
    			echo json_encode($output);
    			exit();
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function accptdecline(){
	    if($this->request->isAJAX()){
    		$output = array('data' => array());
    		$settings = fetchSettings();
    		if(session()->get("suser_groupe") == "9"){
    			$id = $this->request->getPost('id');
    			$type = $this->request->getPost('type');
    			$reqModel = new WithdrawrequestsModel;
    			$userModel = new UsersModel;
    			
    			$data = [
    				'status' => $type
    			];
    			
    			$getResults = $reqModel->where('id', $id)->findAll();
    			$getUserInfo = $userModel->where('id', $getResults[0]['userid'])->findAll();
    			
    			$sellerRate = $getUserInfo[0]['seller_fees'];
    			$sellerBalance = $getUserInfo[0]['seller_balance'];
				$globalrate = $settings[0]['sellerate'] ;
				
				if($globalrate == $sellerRate){
				    $feespercent = $globalrate;
				    $BalanceFees = $getResults[0]["originalsum"] * $globalrate / 100 ;
				}
				else {
				    $feespercent = $sellerRate;
				    $BalanceFees = $getResults[0]["originalsum"] * $sellerRate / 100 ;
				}
				
    			$totalreduce = $getResults[0]["sum"]+$BalanceFees;
    			$newSellerBalance = $getUserInfo[0]['seller_balance']-$totalreduce;
    			$newNotifNumbers = session()->get('suser_notifications_nb')+1;
    			$reqModel->update($id, $data);
    			if($type == '2'){
    				$datauser = [
    					'withdrawstatus' => 0,
    					'notifications_nb' => $newNotifNumbers,
    					'seller_balance' => $newSellerBalance,
    					'withdrawinhold' => 0
    				];
    				$dataNotif = [
    					'subject' => 'Accepted',
    					'text' => 'Your withdraw has been processed.',
    					'url' => base_url().'/historywithdraws',
    					'userid' => $getResults[0]['userid']
    				];
    				$response["message"] = "Request Accepted succeful.";
    				$modelNotif = new NotificationsModel;
    				$modelNotif->save($dataNotif);
    				
    				$userModel->update($getResults[0]['userid'], $datauser);
    	            $response["typemsg"] = "success";
    	            $response["position"] = "bottom right";
    	            $response["size"] = "mini";
    	            $response["icone"] = "fa fa-check-circle";
    	            $response["sounds"] = "sound4";
    	            $response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$datauser = [
    					'withdrawstatus' => 0,
    					'notifications_nb' => $newNotifNumbers,
    				];
    
    				$dataNotif = [
    					'subject' => 'Declined',
    					'text' => 'Your withdraw has been declined.',
    					'url' => base_url().'/historywithdraws',
    					'userid' => $getResults[0]['userid']
    				];
    				$response["message"] = "Request Declined succeful.";
    				$modelNotif = new NotificationsModel;
    				$modelNotif->save($dataNotif);
    				$userModel = new UsersModel;
    				$userModel->update($getResults[0]['userid'], $datauser);
    	            $response["typemsg"] = "success";
    	            $response["position"] = "bottom right";
    	            $response["size"] = "mini";
    	            $response["icone"] = "fa fa-check-circle";
    	            $response["sounds"] = "sound4";
    	            $response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
                $response["typemsg"] = "error";
                $response["position"] = "bottom right";
                $response["size"] = "mini";
                $response["icone"] = "fa fa-check-circle";
                $response["sounds"] = "sound4";
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
}