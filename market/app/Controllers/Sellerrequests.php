<?php
namespace App\Controllers;
use App\Models\SellerrequestsModel;
use App\Models\UsersModel;
use App\Models\NotificationsModel;
class Sellerrequests extends BaseController
{
	public function index(){
		if(session()->get("suser_groupe") == "9"){
			$data = [];
			$settings = fetchSettings();
			$mycart = getCart();
			$data["nbitemscart"] = $mycart[0];
			$data["cartInnerHtml"] = $mycart[1];
			$data["settings"] = $settings;
			$data["sectionName"] = "Seller Requests";
			echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("sellerrequests");
            echo view("assets/footer");
            echo view("assets/scripts");	
		}
		else {
			header('location:'.base_url().'/login');
			exit();
		}
	}

	public function createrequestInit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") !== "0"){
    			exit();
    		}
    		else {
    			$response = array();
    			$form = '
    				<form id="addCont" enctype="multipart/form-data">
    					<div class="form-group row">
    						<div class="col-12">
    							<label>Please insert your Telegram or Jabber and we will contact you <i class="text-danger"> *</i></label>
    							<input type="text" id="info" name="info" class="form-control" placeholder="@telegram_username">
    							<input type="hidden" name="username" value="'.session()->get('suser_username').'">
    							<input type="hidden" name="userid" value="'.session()->get('suser_id').'">
    							<small class="info text-danger"></small>
    						</div>
    					</div>
    				</form>
    			';
    			$modalTitle = 'Seller Request';
    			$response["modal"] = createModal($form, 'fade animated', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "1", ['functions' => 'data-api="creatrequest" ', 'text' => 'Save']);
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

	public function createrequest(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") !== "0"){
    			echo 'no';
    			exit();
    		}
    		else {
    			$response = array();
    			$ValidationRulls = [
    				'info' => [
    		            'rules'  => 'required|regex_match[/^(@[a-z0-9\_\-\.]+)$/]',
    		            'errors' => [
    		            	'required' => 'Please insert your Telegram or Jaber',
    	            	]
    	            ],
    	            'username' => [
    		            'rules'  => 'required|regex_match[/'.session()->get("suser_username").'/]',
    		            'errors' => [
    		            	'required' => 'Username is required.',
    		            	'regex_match' => 'Invalid Username.',
    	            	]
    	            ],
    	            'userid' => [
    		            'rules'  => 'required|numeric|regex_match[/'.session()->get("suser_id").'/]',
    		            'errors' => [
    		            	'required' => 'ID is required.',
    		            	'valid_ip' => 'Invalid ID.',
    	            	]
    	            ],
    			];
    
    			if(!$this->validate($ValidationRulls)){
    
    				$ErrorFields = $this->validator->getErrors();
                    $ErrorsText = [];
                    $keys = [];
                    foreach($ErrorFields as $key => $value){
                        $ErrorsText[] = $value;
                        $keys[] = $key;
                    }
                    $Errors = array_combine($keys, $ErrorsText);
                    $response["fieldslist"] =  $Errors;
                    $response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
                    echo json_encode($response);
                    exit();
    			}
    			else {
    
    				$model = new SellerrequestsModel;
    				$musersModel = new UsersModel;
    				if(session()->get('suser_requeststatus') == '0'){
    
    					foreach ($this->request->getPost() as $key => $value) {
    						if($value !== ''){
    							$data[$key] = $value;
    						}
    					}
    
    					$model->save($data);
    					
    					$userdata = [
    						'requeststatus' => '1'
    					];
    					$musersModel->update(session()->get('suser_id'), $userdata);
    
    					$ResAdmins = $musersModel->where('groupe', '9')->findAll();
    					foreach ($ResAdmins as $res => $admin) {
    						$dataNotif = [
    							'subject' => 'Request',
    							'text' => 'New Seller request!',
    							'url' => base_url().'/sellerrequests',
    							'userid' => $admin['id']
    						];
    						$modelNotif = new NotificationsModel;
    						$modelNotif->save($dataNotif);
    					}
    					session()->set('suser_requeststatus', '1');
    					$modalTitle = 'Success';
    					$response["modal"] = createModal('<p>Your request has been succeful posted, an admin will review it and contact you.</p>', 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
    					$response["csrft"] = csrf_hash();
    				}
    				else if(session()->get('suser_requeststatus') == '1') {
    					$modalTitle = 'Warning';
    					$response["modal"] = createModal('<p>Your request is in waitting.</p>', 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
    					$response["csrft"] = csrf_hash();
    				}
    				else {
    					$modalTitle = 'Warning';
    					$response["modal"] = createModal('<p>Your request has been declined, you can not repost for new request.</p>', 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
    					$response["csrft"] = csrf_hash();
    				}
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

    public function fetchTable(){
        if($this->request->isAJAX()){
    		$output = array('data' => array());
    		if(session()->get("suser_groupe") == "9"){
    			$model = new SellerrequestsModel;
    			$Results = $model->where('status', '0')->orderby('date', 'asc')->findAll();
    			$countresults = count($Results);
    			if($countresults > 0){
    				foreach ($Results as $value) {
    	                $id = '#'.$value["id"];
    	                $username = esc(ucfirst($value["username"]));
    					$Info = esc($value["info"]);
    					$ndate = new \DateTime($value["date"]);
    					$createdat = $ndate->format('d/m/y');
    					$buttons = '
    						<div class="btn-group " role="group">
    	                  		<button type="button" class="btn btn-primary dropdown-toggle btn-sm " data-bs-toggle="dropdown" aria-expanded="false">
    	                        Manage
    	                      	</button>
    	                      	<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
    	                        	<li>
    	                        		<a data-api="accptdecline-'.$value['id'].'|1"  class="dropdown-item" href="javascript:void(0);">
    	                        			<span class="fa fa-edit"></span> Accept 
    	                    			</a>
    	                			</li>
    	                        	<li>
    	                        		<a data-api="accptdecline-'.$value['id'].'|2"  class="dropdown-item" href="javascript:void(0);">
    	                        			<span class="fa fa-trash"></span> Decline 
    	                        		</a>
    	                    		</li>
    	                      	</ul>
    	                    </div>
    					';
    					$output['data'][] = array(
    		        		$id,
    		        		$username,
    						$Info,
    						$createdat,					
    						$buttons,					
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
    				);
    				header('Content-Type: application/json');
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

	public function accptdecline(){
	    if($this->request->isAJAX()){
    		$output = array('data' => array());
    		if(session()->get("suser_groupe") == "9"){
    			$id = $this->request->getPost('id');
    			$type = $this->request->getPost('type');
    			$reqModel = new SellerrequestsModel;
    			$data = [
    				'status' => $type
    			];
    			$reqModel->update($id, $data);
    			$getResults = $reqModel->where('id', $id)->findAll();
    			$newNotifNumbers = session()->get('suser_notifications_nb')+1;
    			if($type == '1'){
    				$datauser = [
    					'requeststatus' => 1,
    					'notifications_nb' => $newNotifNumbers,
    					'groupe' => '1'
    				];
    
    				$dataNotif = [
    					'subject' => 'Accepted',
    					'text' => 'Your seller request was Accepted.',
    					'url' => base_url().'/cards',
    					'userid' => $getResults[0]['userid']
    				];
    				$response["message"] = "Request Accepted succeful.";
    			}
    			else {
    				$datauser = [
    					'requeststatus' => 2,
    					'notifications_nb' => $newNotifNumbers,
    				];
    
    				$dataNotif = [
    					'subject' => 'Declined',
    					'text' => 'Your seller request was declined.',
    					'url' => base_url().'/cards',
    					'userid' => $getResults[0]['userid']
    				];
    				$response["message"] = "Request Declined succeful.";
    			}
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
    		else {
    
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}
}