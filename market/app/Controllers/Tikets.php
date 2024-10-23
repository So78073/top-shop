<?php

namespace App\Controllers;
use App\Models\TiketsModel;
use App\Models\MytiketModel;
use App\Models\NotificationsModel;
use App\Models\UsersModel;
class Tikets extends BaseController
{
	public function index(){
		if(session()->get("logedin") == true){
			$data = [];
            $settings = fetchSettings();
            $mycart = getCart();
            $data["nbitemscart"] = $mycart[0];
            $data["cartInnerHtml"] = $mycart[1];
            $data["settings"] = $settings;
			$model = new UsersModel;
			$Results = $model->where('id' , session()->get("suser_id"))->findAll();
			$data["results"] = $Results;
			$data["sectionName"] = "Ticket";
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("tikets");
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
    		if(session()->get("logedin") == true){
    			$model = new TiketsModel;
    			if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '2') {
    			    $Results = $model->orderby('date','desc')->findAll();
    			}
    			else {
    			    $Results = $model->where(['userid'=> session()->get("suser_id")])->orderby('date','desc')->findAll();
    			}
    			$countresults = count($Results);
    			if($countresults > 0){
    				foreach ($Results as $value) {
    	                $id = '#'.$value["id"];
    					$subject = ucfirst(esc($value["subject"]));
    					$description = esc(substr($value["description"], 0, 50)).' ...';
    					$status = '';
                        switch($value["status"]){
                            case '0' : 
                                $status = "<span class='btn btn-primary btn-sm'>Open</span>";
                            break;
                            case '1' : 
                                $status = "<span class='btn btn-success btn-sm'>Solved</span>";
                            break;
                            case '2' : 
                                $status = "<span class='btn btn-danger btn-sm'>Closed</span>";
                            break;
                            case '3' : 
                                $status = "<span class='btn btn-success btn-sm'>Refunded</span>";
                            break;
                        }
    					$ndate = new \DateTime($value["date"]);
    					$createdat = $ndate->format('d/m/Y H:s:i');
    					$username = esc($value["username"]);
    					$userid = '#'.$value["userid"];
    					$button = '<a href="'.base_url().'/mytiket/'.base64_encode($value["id"]).'" class="btn btn-primary btn-sm">VIEW</a>';
    					if(session()->get('suser_groupe') == '9'){
    					    $output['data'][] = array(
        		        		esc($id),
        		        		$subject,
        		        		$description,
        		        		$status,
        						$createdat,
        						$username,
        						$userid,		
        						$button
        					);
    					}
    					else {
    					    $output['data'][] = array(
        		        		esc($id),
        		        		$subject,
        		        		$description,
        		        		$status,
        						$createdat,
        						$button,
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
	
	public function createLogInit(){
	    if($this->request->isAJAX()){
        	if(session()->get("logedin") != true){
    			exit();
    		}
    		else {
    			$form = '
    				<form id="addCont">
                        <div class="form-group">
							<label class="form-label">Subject<i class="text-danger"> *</i></label>
							<input type="text" id="subject" name="subject" class="form-control">
							<small class="subject text-danger"></small>
                        </div>
                        <div class="form-group">
							<label class="form-label">Description <i class="text-danger"> *</i></label>
							<small class="description text-danger"></small>
							<textarea class="form-control" name="description" id="description" style="height:250px"></textarea>
                        </div>
    				</form>
    			';
    			$modalTitle = 'Create Ticket';
    			$response["modal"] = createModal($form, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "1", ['functions' => 'data-api="createlog" ', 'text' => 'Send']);
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
	
	public function createLog(){
	    if($this->request->isAJAX()){
    	    $response = array();
    	    if($this->request->getPost('subject') != "" && $this->request->getPost('description') != ""){
    	        $mydata = [
                    'subject' => [
                        'label'  => 'Subejct',
                        'rules'  => 'required|min_length[4]|max_length[100]|regex_match[/^([a-zA-Z0-9 £\€\-\_\:\?\!\/\\\%\$\.\,\*\)\(]+)$/mi]',
                        'errors' => [
                            'required' => 'Subject is required.',
                            'min_length' => 'A valid Subejct is between 5 and 30 charts.',
                            'max_length' => 'A valid Subejct is between 5 and 30 charts.',
                            'PassCheck' => 'Only those characters are allowed (a-z A-Z 0-9 - _ : ? ! / \ % $ £ € . , * ( ) and spaces )',
    
                        ]
                    ],
                    'description' => [
                        'label'  => 'Description',
                        'rules'  => 'required|min_length[8]|max_length[500]|regex_match[/^([a-zA-Z0-9 £\€\-\_\:\?\!\/\\\%\$\.\,\*\)\(]+)$/mi]',
                        'errors' => [
                            'required' => 'Description is required.',
                            'min_length' => 'A valid Description is between 8 and 500 charts.',
                            'max_length' => 'A valid Description is between 8 and 500 charts.',
                            'regex_match' => 'Only those characters are allowed (a-z A-Z 0-9 - _ : ? ! / \ % $ £ € . , * ( )  )',
                        ]
                    ],
                ];
                if(!$this->validate($mydata)){
                    $ErrorFields = $this->validator->getErrors();
                    $ErrorsText = [];
                    $keys = [];
                    foreach($ErrorFields as $key => $value){
                        $ErrorsText[] = $value;
                        $keys[] = $key;
                    }
                    $Errors = array_combine($keys, $ErrorsText);
                    $response["fieldslist"] =  $Errors;
                }
                else {
            		$modeltikets = new TiketsModel;
            		$modelUsers = new UsersModel;
                    $GetUserResults = $modelUsers->where(['id' => session()->get('suser_id')])->findall();
                    if($GetUserResults[0]["status"] == "1"){
                        $subject = esc($this->request->getPost('subject'));
                        $description = esc($this->request->getPost('description'));
                        $data = [
                            'subject' => $subject,
                            'description' => $description,
                            'username' => session()->get('suser_username'),
                            'userid' => session()->get('suser_id')
                        ];
                        $modeltikets->insert($data);
                        $modalContent = '<p>Your Ticket has been sent</p>';
    					$modalTitle = 'Success';
    					$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', ' modal-lg', "1", "1", "1", "1", "0");
                    }
                    else {
                        $modalContent = '<p>Error, something went wrong, E-001.</p>';
    					$modalTitle = 'Error';
    					$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', ' modal-lg', "1", "1", "1", "1", "0");
                    }
                } 
    	    }
    	    else {
                $modalContent = '<p>Error, something went wrong, E-002.</p>';
    			$modalTitle = 'Error';
    			$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', ' modal-lg', "1", "1", "1", "1", "0");
     
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