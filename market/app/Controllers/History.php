<?php

namespace App\Controllers;
use App\Models\PayementsModel;
use App\Models\NotificationsModel;
use App\Models\UsersModel;
class History extends BaseController
{
	public function index()
	{
		if(session()->get("logedin") == '1'){
			$data = [];
            $settings = fetchSettings();
            $mycart = getCart();
            $data["nbitemscart"] = $mycart[0];
            $data["cartInnerHtml"] = $mycart[1];
            $data["settings"] = $settings;
			$model = new UsersModel;
			$Results = $model->where('id' , session()->get("suser_id"))->findAll();
			$data["results"] = $Results;			
			$db      = \Config\Database::connect();
            $builder = $db->table('`nowpayements`');
			$builder->select('`price_amount`')->where(['`intstatus`' =>'1', '`payment_status`' => 'finished'])->orwhere(['`payment_status`' => 'Partially paid contact support']);
			$Results =  $builder->get();
			$Results->getResult('array');
			$countresults = count($Results->getResult('array'));
			$mnarray = $Results->getResult('array');
			$arraysome = [];
			if($countresults > 0){
			    foreach($mnarray as $k => $v){
			        foreach($v as $ks => $vs){
    			        $arraysome[] = $vs;
    			    }
			    }
			}
			else{
			    $arraysome = [0];
			}
			$data["totdep"] = array_sum($arraysome);
			$data["sectionName"] = "Deposits history";
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("history");
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
    			$model = new PayementsModel;
    			if(session()->get('suser_groupe') == '9') {
    			    $Results = $model->where('`payment_status` !=', 'expired' )->orderby('`created_at`','desc')->findAll();
    			}
    			else {
    			    $Results = $model->where('`userid`', session()->get("suser_id"))->orderby('`created_at`','desc')->findAll();
    			}
    			$countresults = count($Results);
    			if($countresults > 0){
    				foreach ($Results as $value) {
    					$amUSD = '$'.$value["price_amount"];
    					$status = $value["payment_status"];
                        switch($status){
                            case 'expired' : 
                                $status = '<span class="text-danger">'.strtoupper($status).'</span>';
                            break;
                            case 'waiting' : 
                                $status = '<button class="btn btn-primary btn-sm text-white" data-api="refreshstatus-'.$value["id"].'">'.strtoupper($status).' <i class="bx bx-refresh text-white bx-spin"></i></button>';
                            break;
                            case 'finished' : 
                                $status = '<button class="btn btn-success btn-sm text-white">'.strtoupper($status).' <i class="bx bx-check-double text-white"></i></button>';
                            break;
                            case 'confirming' : 
                                $status = '<button class="btn btn-info btn-sm text-white" data-api="refreshstatus-'.$value["id"].'">'.strtoupper($status).' <i class="bx bx-refresh text-white bx-spin"></i></button>';
                            break;
                            case 'sending' : 
                                $status = '<button class="btn btn-info btn-sm text-white" data-api="refreshstatus-'.$value["id"].'">'.strtoupper($status).'<i class="bx bx-refresh text-white bx-spin"></i></button>';
                            break;
                            case 'Partially paid contact support' : 
                                $status = '<span class="text-danger">'.strtoupper($status).' <i class="bx bx-comment-error"></i></span>';
                            break; 
                        }
    					$ndate = new \DateTime($value["created_at"]);
    					$createdat = $ndate->format('d/m/Y H:s:i');
    					$address = $value["pay_address"];
    					$payusernam = $value["username"];
    					$payuserid = $value["userid"];
    					$typeCurrency = strtoupper($value["pay_currency"]);
    					if(session()->get('suser_groupe') == '9'){
    					    $output['data'][] = array(
        		        		esc($createdat),
        		        		esc($payusernam),
        		        		esc($payuserid),
        						esc($amUSD),
        						esc($typeCurrency),
        						esc($address),		
        						$status,
        					);
    					}
    					else {
    					    $output['data'][] = array(
        		        		esc($createdat),
        						esc($amUSD),
        						esc($typeCurrency),
        						esc($address),		
        						$status,
        					);
    					}
        					
    				}
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
    					NULL,
    				);
    				echo json_encode($output);
    				exit();
    			}
    
    		}
    		else {
    			$output['data'][] = array(
            		NULL,
    				NULL,
    				NULL,				
    				NULL,				
    				NULL,				
    			);
    			echo json_encode($output);
    			exit();
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

    function getstatus(){
        if($this->request->isAJAX()){
    		$response = array();
    		if(session()->get("logedin") == '1'){
    			$settings = fetchSettings();
    			$api = 'FC9QD85-40Q47DH-KQ1Y2FZ-91TX6A0';
    			//$api = $settings[0]['nowpayementapikey'];
    			$userid = session()->get('suser_id');
    			$id = $this->request->getPost('id');
    			$model = new PayementsModel;
    			$usersmodel = new UsersModel;
    			$modelNotif = new NotificationsModel;
    			if(session()->get('suser_groupe') == 9 || session()->get('suser_groupe') == 2){
    				$Results = $model->where(['id'=> $id])->findAll();
    			}
    			else {
    				$Results = $model->where(['id'=> $id, 'userid' => $userid])->findAll();	
    			}
    			$ResultsUser = $usersmodel->where(['id'=> $userid])->findAll();
    			if($Results[0]['intstatus'] == '0'){
    			    if(count($Results) > 0){
        				$payementid = $Results[0]['payment_id'];
        				$status = getPaymentstatus($api, $payementid);
        				$nowstatus = $status["payment_status"];
        				if($status["payment_status"] == "sending"){
        	                if($Results[0]['price_amount'] != $status["price_amount"]){
        	                   	$response["message"] = "Error, Something Went Wrong E-001.";
        		                $response["typemsg"] = "error";
        		                $response["position"] = "bottom right";
        		                $response["size"] = "mini";
        		                $response["icone"] = "bx bx-close";
        		                $response["sounds"] = ""; 
        	                }
        	                else if($Results[0]['created_at'] != $status["created_at"]){
           	                   	$response["message"] = "Error, Something Went Wrong E-002.";
        		                $response["typemsg"] = "error";
        		                $response["position"] = "bottom right";
        		                $response["size"] = "mini";
        		                $response["icone"] = "bx bx-close";
        		                $response["sounds"] = ""; 
        
        	                }
        	                else if($Results[0]['order_id'] != $status["order_id"]) {
           	                   	$response["message"] = "Error, Something Went Wrong E-003.";
        		                $response["typemsg"] = "error";
        		                $response["position"] = "bottom right";
        		                $response["size"] = "mini";
        		                $response["icone"] = "bx bx-close";
        		                $response["sounds"] = ""; 
        	                }
        	                else { 
        	                    if(count($ResultsUser) == 1){
    		                        $data = [
    		                            'payment_status' => $status["payment_status"],
    		                            'intstatus' => '1',
    		                        ];
    		                        $nbNotif = $ResultsUser[0]['notifications_nb']+1;
    		                        $newbalance = $ResultsUser[0]['balance'] +  $status["price_amount"];
    		                        if($ResultsUser[0]['refered_by_id'] != '' && $settings[0]['refsys'] == '1'){
    		                        	$userReferal = $usersmodel->where('id', $ResultsUser[0]['refered_by_id'])->findAll();
    		                        	if(count($userReferal) == '1'){
    		                        		$percent = $userReferal[0]['referals_rate'];
    		                        		$deposamountuser = $status["price_amount"]* $percent;
    		                        		$getPercen = $deposamountuser/100;
    		                        		$newbalanceReferal =$getPercen+$userReferal[0]['balance'];
    		                        		$referalnewbalance = [
    		                        			'balance' => $newbalanceReferal
    		                        		];
    	                        		 	$usersmodel->update($userReferal[0]['id'], $referalnewbalance);
    		                        	}
    		                        }
    		                        $dataUser = [
    		                            'balance' => $newbalance,
    		                            'notifications_nb' => $nbNotif,
    		                            'active' => '1'
    		                        ];
    		                        $dataNotif = [
    									'subject' => 'Deposits',
    									'text' => 'Your deposit was successfully processed!',
    									'url' => base_url().'/history',
    									'userid' => $Results[0]['userid']
    								];
    								$model->update($Results[0]['id'], $data);
    		                        $usersmodel->update($ResultsUser[0]['id'], $dataUser);
    		                        $modelNotif->save($dataNotif);
    								$response["message"] = "Success, Balance added";
    				                $response["typemsg"] = "success";
    				                $response["position"] = "bottom right";
    				                $response["size"] = "mini";
    				                $response["icone"] = "bx bx-check";
    				                $response["sounds"] = "";
        	                    }
        	                }
        	            }
        	            else if($status["payment_status"] == "waiting" || $status["payment_status"] == "expired" || $status["payment_status"] == "confirming" || $status["payment_status"] == "comming"){
    	                    if($Results[0]['price_amount'] != $status["price_amount"]){
        	                    $response["message"] = "Error, Something Went Wrong E-004.";
        		                $response["typemsg"] = "error";
        		                $response["position"] = "bottom right";
        		                $response["size"] = "mini";
        		                $response["icone"] = "bx bx-close";
        		                $response["sounds"] = ""; 
        	                }
        	                else if($Results[0]['created_at'] != $status["created_at"]){
        	                   	$response["message"] = "Error, Something Went Wrong E-005.";
        		                $response["typemsg"] = "error";
        		                $response["position"] = "bottom right";
        		                $response["size"] = "mini";
        		                $response["icone"] = "bx bx-close";
        		                $response["sounds"] = ""; 
        	                }
        	                else if($Results[0]['order_id'] != $status["order_id"]) {
        	                   	$response["message"] = "Error, Something Went Wrong E-006.";
        		                $response["typemsg"] = "error";
        		                $response["position"] = "bottom right";
        		                $response["size"] = "mini";
        		                $response["icone"] = "bx bx-close";
        		                $response["sounds"] = "";  
        	                } 
        	                if(count($ResultsUser) == 1){
        	                    switch ($status["payment_status"]) {
        	                        case 'finished':
        	                            $init = '1';
        	                        break;
        	                        case 'waiting':
        	                            $init = '0';
        	                        break;
        	                        case 'expired':
        	                            $init = '2';
        	                        break;
        	                    }
        	                    $data = [
        	                        'payment_status' => $status["payment_status"],
        	                        'intstatus' => $init
        	                    ];
        	                    $model->update($Results[0]['id'], $data);
        	                    $response["message"] = "Refreshed";
        		                $response["typemsg"] = "success";
        		                $response["position"] = "bottom right";
        		                $response["size"] = "mini";
        		                $response["icone"] = "bx bx-check";
        		                $response["sounds"] = "";
        	                }
        	                else  {
        		               	$response["message"] = "Error, Something Went Wrong E-007.";
        		                $response["typemsg"] = "error";
        		                $response["position"] = "bottom right";
        		                $response["size"] = "mini";
        		                $response["icone"] = "bx bx-close";
        		                $response["sounds"] = ""; 
        		            }
        	            }
        	            else if($status["payment_status"] == "partially_paid"){
        	                if(count($ResultsUser) == 1){
        	                    $data = [
        	                        'payment_status' => 'Partially paid contact support',
        	                        'intstatus' => '2'
        	                    ];
        	                    $model->update($Results[0]['id'], $data);
        	                    $response["message"] = "Partially paid contact support.";
        		                $response["typemsg"] = "error";
        		                $response["position"] = "bottom right";
        		                $response["size"] = "mini";
        		                $response["icone"] = "bx bx-close";
        		                $response["sounds"] = ""; 
        	                }  
        	            }
        	            else  {
        	               	$response["message"] = "API CHANGED";
        	                $response["typemsg"] = "error";
        	                $response["position"] = "bottom right";
        	                $response["size"] = "mini";
        	                $response["icone"] = "bx bx-close";
        	                $response["sounds"] = ""; 
        	            }
        			}
        			else {
        				$response["message"] = "Error, Something Went Wrong E-009.";
                        $response["typemsg"] = "error";
                        $response["position"] = "bottom right";
                        $response["size"] = "mini";
                        $response["icone"] = "bx bx-close";
                        $response["sounds"] = ""; 
        			}
    			}
    			else {
    				$response["message"] = "Error, Arady Checked E-011.";
                    $response["typemsg"] = "error";
                    $response["position"] = "bottom right";
                    $response["size"] = "mini";
                    $response["icone"] = "bx bx-close";
                    $response["sounds"] = ""; 
    			}
    		}
    		else {
    			$response["message"] = "Error, Something Went Wrong E-010.";
    	        $response["typemsg"] = "error";
    	        $response["position"] = "bottom right";
    	        $response["size"] = "mini";
    	        $response["icone"] = "bx bx-close";
    	        $response["sounds"] = "";
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
}