<?php

namespace App\Controllers;
use App\Models\NotificationsModel;
use App\Models\UsersModel;
class Notifications extends BaseController
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
			$Results = $model->where('id' , session()->get("suser_id"))->findAll();
			$data["results"] = $Results;
			$data["sectionName"] = "Notifications";
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view('notifications');
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
    		$userid = session()->get('suser_id');			
    		$output = array('data' => array());
    		$model = new NotificationsModel();
    
    		$categorysListArray = $model->where(['userid' => $userid])->orWhere(['userid' => 'all'])->find();
    		$Counts =  count($categorysListArray);
    		if($Counts > 0){
    			foreach ($categorysListArray as $value) {
    				$ndate = new \DateTime($value["date"]);
    				$notifdate = $ndate->format("d/m/Y h:i:s");
    				$notif = '<a href="'.base_url().'/'.$value['url'].'">'.$value['subject'].'<br>'.$value['text'].'</a>';	
    				$tools = '<div class="button-group">
                        <div class="btn-group">
                        	<button type="button" class="archive'.$value["id"].' btn btn-light btn-sm" onclick="archivenotifs(\''.$value["id"].'\')">
                        	<span class="bx bx-trash"></span>
                        	</button>                     
                        </div>
                    </div>';
    
    	        	$output['data'][] = array(
    	        		$notifdate,
    					$notif,
    					$tools,					
    				);	
    		
    			}
    			$output['curr'] = $this->request->getVar('valp');
    			echo json_encode($output);
    			exit();
    		}
    		else {
    			$output['data'][] = array(
    				NULL,
    				NULL,
    				NULL,
    			);
    			$output['curr'] = $this->request->getVar('valp');
    			echo json_encode($output);
    			exit();
    		}	
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}


	public function getnotifs(){
	    if($this->request->isAJAX()){
    		$respons = array();
    		if(session()->get('Logedin') == '1'){
    			$userID = session()->get('suser_id');
    			$notifsmodel = new NotificationsModel;
    			$resutls = $notifsmodel->where(['userid' => $userID] )->orWhere(['userid' => 'all'])->orderBy('id', 'desc')->findAll(5);
    			$countresutls = count($resutls);
    			$notfisarray = array();
    	        if($countresutls > 0){	
    	        	$content = '';
    	        	foreach ($resutls as $value) {
    	        		$ndate = new \DateTime($value["date"]);
    					$content .= '
    						<a class="dropdown-item " href="'.$value["url"].'">
    							<div class="d-flex align-items-center">
    								<div class="notify bg-light-sucess text-success"><i class="bx bx-bell"></i>
    								</div>
    								<div class="flex-grow-1">
    									<h6 class="msg-name">'.$value["subject"].'<span class="msg-time float-end">'.$ndate->format('d/m/y h:i:s').'</span></h6>
    									<p class="msg-info">'.$value["text"].'</p>
    								</div>
    							</div>
    						</a>';
    				}
    			}
    			else {
    				$content = '';
    			}
    			$datauserNotfi = [
    				'nbnotifications' => '0',
    			];
    			$userModel = new BreakersModel;
    			$userModel->update(session()->get('suser_id'), $datauserNotfi);
    			$respons['results'] = $content;
    			$respons["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($respons);
    			exit();
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function archivenotifs(){
	    if($this->request->isAJAX()){
    		$respons = array();
    		if(session()->get('Logedin') == '1'){
    			$id = $this->request->getVar("id");
    			$userID = session()->get('suser_id');
    			$notfismodel = new NotificationsModel;
    			switch (session()->get('suser_groupe')) {
    				case '1':
    				case '0':
    					$resutls = $notfismodel->where(['userid' => $userID,  'id' => $id])->findAll();
    				break;
    				case '9':
    					$resutls = $notfismodel->where(['id' => $id])->findAll();
    				break;
    			}
    			
    			$countresutls = count($resutls);
    			if($countresutls > 0){
    				$notfismodel->delete($id);
    				$response["message"] = "Deleted Successfully";
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
    				$response['notifs'] = "0";
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}						
    		}
    		else {
    			$response['notifs'] = "0";
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

	public function offnotifs(){
	    if($this->request->isAJAX()){
    		$respons = array();
    		if(session()->get('logedin') == '1'){
    			$Modelusers = new UsersModel;
    			$data = [
    				'notifications_nb' => "0",
    			];
    			$userID = session()->get("suser_id");
    			$Modelusers->update($userID, $data);
    			$notifmodel = new NotificationsModel;
    			session()->set('suser_notifications_nb', '0');
    			$respons['notifs'] = "1";
    			$respons["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($respons);	
    			exit();		
    		}
    		else {
    			$respons['notifs'] = "0";
    			$respons['notifs'] = "1";
    			$respons["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($respons);
    			exit();
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}
}