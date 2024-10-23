<?php

namespace App\Controllers;
use App\Models\MytiketModel;
use App\Models\TiketsModel;
use App\Models\NotificationsModel;
use App\Models\UsersModel;
class Mytiket extends BaseController
{
	public function index($id = null)
	{

		if(session()->get("logedin") == '1'){
            
		    if(null !== $id){
		        $id = base64_decode($id);
		        $data = [];
                $settings = fetchSettings();
                $mycart = getCart();
                $data["nbitemscart"] = $mycart[0];
                $data["cartInnerHtml"] = $mycart[1];
                $data["settings"] = $settings;
                $data["sectionName"] = "My Tickets";
    			$model = new TiketsModel;
    			if(session()->get('suser_groupe') == '9'  || session()->get('suser_groupe') == '2') {
                    $Results = $model->where(['id'=> $id])->findAll();
    			}
    			else {
    			    $Results = $model->where(['id'=> $id, 'userid' => session()->get("suser_id")])->findAll();    
    			}
    			if(count($Results) == 1){
    			    $mytikets = new MytiketModel;
    			    $ResultsMytiket = $mytikets->where(['tiketid'=> $id])->findAll();
    			    
    			    $data["results"] = $Results;
			        $data["resultsMytiket"] = $ResultsMytiket;
                    echo view("assets/header", $data);
                    echo view("assets/aside");
                    echo view("assets/topbarre");
                    echo view("mytiket");
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
	
	public function addresponse(){
	    if($this->request->isAJAX()){
    	    if(session()->get("logedin") == '1'){
        	    if($this->request->getPost('id') != '' && $this->request->getPost('msg') != ''){
        	         $mydata = [
                        'id' => [
                            'id'  => 'ID',
                            'rules'  => 'required|numeric',
                            'errors' => [
                                'required' => 'ID is required.',
                                'numeric' => 'Error',
        
                            ]
                        ],
                        'msg' => [
                            'label'  => 'Message',
                            'rules'  => 'required|min_length[1]|max_length[500]|textArea',
                            'errors' => [
                                'required' => 'Message is required.',
                                'min_length' => 'A valid Message is between 1 and 500 charts.',
                                'max_length' => 'A valid Message is between 1 and 500 charts.',
                                'PassCheck' => 'Only those characters are allowed (a-z A-Z 0-9 _ - * $ % . ! ? : , ; / \\)',
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
                        $response["csrft"] = csrf_hash();
                        header('Content-Type: application/json');
                        echo json_encode($response);
                        exit();
                    }
                    else {
            	        $id = $this->request->getPost('id');
            	        $msg = $this->request->getPost('msg');
            	        $tiketsmodel = new TiketsModel;
            	        if(session()->get('suser_groupe') == '9') {
                            $Results = $tiketsmodel->where(['id'=> $id])->findAll();
            			}
            			else {
            			    $Results = $tiketsmodel->where(['id'=> $id, 'userid' => session()->get("suser_id")])->findAll();    
            			}
            	        if(count($Results) == 1){
            	            $mytiketsmodel = new MytiketModel;
            	            $data = [
            	                'responses' => $msg,
            	                'responseuserid' => session()->get('suser_id'),
            	                'responseusername' => session()->get('suser_username'),
            	                'responseusergroupe' => session()->get('suser_groupe'),
            	                'tiketid' => $id,
                            ]; 
                            
                            $mytiketsmodel->insert($data);
                            $last = $mytiketsmodel->getInsertID();
                            
                            $Resultslast = $mytiketsmodel->where(['id'=> $last])->findAll();
                            
                            if(session()->get('suser_id') == $Resultslast[0]['responseuserid']){
                                $class="chat-content-leftside";
                                $textclass= "chat-left-msg";
                                $textend = '';
                            }
                            else {
                                $class="chat-content-rightside"; 
                                $textclass= "chat-right-msg";
                                $textend = 'text-end';
                            }
                            $html = '<div class="'.$class.'">
    							    <div class="d-flex">
        								<div class="flex-grow-1 ms-2">
        									<p class="mb-0 chat-time '.$textend.'">';
        					if($Resultslast[0]['responseusergroupe'] == '9' || session()->get('suser_groupe') == '2'){ 
        					    $html .= '<span class="text-danger"><b>Support</b></span>'; 
    					    } 
    					    else {  
    					        $html .= '<span class="text-default"><b>'.ucfirst(esc($Resultslast[0]['responseusername'])).'</b></span>'; 
    					    }
    					    $repdate = new \DateTime($Resultslast[0]['responsedate']);
    					    $html .= ', '.$repdate->format('H:i:s d/m').'</p>
        									<p class="'.$textclass.'">'.nl2br(ucfirst(esc($Resultslast[0]['responses']))) .'</p>
        								</div>
        							</div>
        						</div>';
        						
                            $response['responses'] = $html;
                            $response["csrft"] = csrf_hash();
                            header('Content-Type: application/json');
                            echo json_encode($response);
                            exit();
            	        }
            	        else {
            	            $html = '';
            	            $response['responses'] =$html;
                            $response["csrft"] = csrf_hash();
                            header('Content-Type: application/json');
            	            echo json_encode($response);
            	            exit();
            	        }
                    }
        	    }
        	    else {
        	        $response['html'] = '1';
                    $response["csrft"] = csrf_hash();
                    header('Content-Type: application/json');
        	        echo json_encode($response);
        	        exit();
        	    }
    	    }
    	    else {
    	        $response['html'] = '2';
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
	
	public function updatestatus(){
	    if($this->request->isAJAX()){
    	    if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '2'){
    	        $id = $this->request->getPost('id');
    	        $status = $this->request->getPost('status');
    	        $data = [
    	            'status' => $status
    	        ];
    	        $model = new TiketsModel;
    	        $model->update($id, $data);
    	        $modalTitle = 'Ticket Updated';
                $response["modal"] = createModal('Update Success', 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
                $response["csrft"] = csrf_hash();
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
    
    	    }
    	    else {
    	        $response["message"] = "Error.";
                $response["typemsg"] = "error";
                $response["position"] = "bottom right";
                $response["size"] = "mini";
                $response["icone"] = "fa fa-check-circle";
                $response["sounds"] = "sound4";
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