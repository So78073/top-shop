<?php

namespace App\Controllers;
use App\Models\PayementsModel;
use App\Models\UsersModel;
class Profile extends BaseController
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
			$data["sectionName"] = "My Profile";
			$data["results"] = $Results;
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("profile");
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
    			$Results = $model->where('user_id', session()->get("suser_id"))->findAll();
    			$countresults = count($Results);
    			if($countresults > 0){
    				foreach ($Results as $value) {
    	                $id = '#'.$value["id"];
    					$amUSD = '$'.$value["ammountusd"];
    					$status = $value["status"];
                        switch($status){
                            case 'Error/TimeOut' : 
                                $status = '<span class="btn btn-danger">'.$value["status"].'</span>';
                            break;
                            case 'In the way' : 
                                $status = '<span class="btn btn-primary">'.$value["status"].'</span>';
                            break;
                            case 'Waiting Funds' : 
                                $status = '<span class="btn btn-warning">'.$value["status"].'</span>';
                            break;
                            case 'Success' : 
                                $status = '<span class="btn btn-success">'.$value["status"].'</span>';
                            break;
                            default :
                                $status = '<span class="btn btn-secondary">'.$value["status"].'</span>';
                            break;
                        }
    					$ndate = new \DateTime($value["create_at"]);
    					$createdat = $ndate->format('d/m/Y H:i:s');
    					$output['data'][] = array(
    		        		$id,
    						$amUSD,				
    						$status,
    						$createdat,					
    					);
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

	public function updateperso(){
	    if($this->request->isAJAX()){
    		$settings = fetchSettings();
    		if(session()->get("logedin") == '1'){
    			$ValidationRulls = [
            		'email' => [
    		            'label'  => 'Email',
    		            'rules'  => 'required|valid_email',
    		            'errors' => [
    		            	'required' => 'Email is required',
    		                'valid_email' => 'A valid Email is required.',
    		                'is_unique' => 'This email is already registered.'
    		            ]
    		        ],
    		        'telegram' => [
    		            'label'  => 'Telegram',
    		            'rules'  => 'permit_empty|regex_match[/^\@[a-zA-Z0-9]{3,10}+$/]',
    		            'errors' => [
    		                'regex_match' => 'Please inser a valid Telegram ID.',
    		                'required' => 'Telegram ID is required.'
    		            ]
    		        ],
    		        'btcaddress' => [
    		            'label'  => 'BTC Address',
    		            'rules'  => 'permit_empty|regex_match[/^(bc1|[13])[a-zA-HJ-NP-Z0-9]{25,42}$/]',
    		            'errors' => [
    		                'regex_match' => 'Please inser a valid BTC Address.',
    		                'required' => 'Telegram ID is required.'
    		            ]
    		        ],
    			];
    			if($settings[0]["telegram"] == "1" && $settings[0]["rtelegram"] == "1"){
    				$ValidationRulls['telegram'] = array(
    		            'label'  => 'telegram',
    		            'rules'  => 'required|regex_match[/^\@[a-zA-Z0-9]{3,10}+$/]',
    		            'errors' => array(
    		            	'regex_match' => 'Please inser a valid Telegram ID.',
    		                'valid_email' => 'Please inser a valid Jaber ID.'
    		            )
    		        );
    			}
    			else if($settings[0]["telegram"] == "1" && $settings[0]["rtelegram"] == "0"){
    				$ValidationRulls['telegram'] = array(
    		            'label'  => 'telegram',
    		            'rules'  => 'permit_empty|regex_match[/^\@[a-zA-Z0-9]{3,10}+$/]',
    		            'errors' => array(
    		                'valid_email' => 'Please inser a valid Jaber ID.'
    		            )
    		        );
    			}
    			if($settings[0]["jaber"] == "1" && $settings[0]["rjaber"] == "1"){
    				$ValidationRulls['jaber'] = array(
    		            'label'  => 'Jaber',
    		            'rules'  => 'required|valid_email',
    		            'errors' => array(
    		                'valid_email' => 'Please inser a valid Jaber ID.'
    		            )
    		        );
    			}
    			else if($settings[0]["jaber"] == "1" && $settings[0]["rjaber"] == "0"){
    				$ValidationRulls['jaber'] = array(
    		            'label'  => 'Jaber',
    		            'rules'  => 'permit_empty|valid_email',
    		            'errors' => array(
    		                'valid_email' => 'Please inser a valid Jaber ID.'
    		            )
    		        );
    			}
    			if($settings[0]["icq"] == "1" && $settings[0]["ricq"] == "1" ){
    				$ValidationRulls['icq'] = array(
    		            'label'  => 'ICQ',
    		            'rules'  => 'required|regex_match[/^\@[a-zA-Z0-9]{3,10}+$/]',
    		            'errors' => array(
    		                'regex_match' => 'Please inser a valid ICQ ID.'
    		            )
    		        );
    			}
    			else if($settings[0]["icq"] == "1" && $settings[0]["ricq"] == "0"){
    				$ValidationRulls['icq'] = array(
    		            'label'  => 'ICQ',
    		            'rules'  => 'permit_empty|regex_match[/^\@[a-zA-Z0-9]{3,10}+$/]',
    		            'errors' => array(
    		                'regex_match' => 'Please inser a valid ICQ ID.'
    		            )
    		        );
    			}
    			//$regex = '/^(bc1|[13])[a-zA-HJ-NP-Z0-9]{25,42}$/';
    
    			
    
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
    				foreach($this->request->getPost() as $key => $val){
    					$data[$key] = $val;
    				}
    				$model = new UsersModel;
    				$Request = $model->update(session()->get("suser_id"), $data);
    				$modalContent = '<p>Updated successfully</p>';
    				$response["modal"] = createModal($modalContent, 'fade animated', 'Success', 'text-success', 'modal-lg', "1", "1", "1", "1", "0");
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

	public function updatepass(){
	    if($this->request->isAJAX()){
    		if(session()->get("logedin") == '1'){
    
    			$ValidationRulls = [
    		        'password' => [
    		            'label'  => 'Password',
    		            'rules'  => 'required|min_length[8]|alpha_numeric_punct',
    		            'errors' => [
    		            	'required' => 'Password is required',
    		                'min_length' => 'A valid Password can contain at minimum 8 characters.',
    		                'max_leng' => 'A valid Password can contain at max 30 characters.',
    		                'alpha_dash' => 'A valid Password can contain only alphanumeric, Dashes(-) and Underscors(_) characters.',
    		            ]
    		        ],
    		        'npassword' => [
    		            'label'  => 'Password',
    		            'rules'  => 'required|min_length[8]|alpha_numeric_punct',
    		            'errors' => [
    		            	'required' => 'Password is required',
    		                'min_length' => 'A valid Password can contain at minimum 8 characters.',
    		                'max_leng' => 'A valid Password can contain at max 30 characters.',
    		                'alpha_dash' => 'A valid Password can contain only alphanumeric, Dashes(-) and Underscors(_) characters.',
    		            ]
    		        ],
    		        'rpassword' => [
    		            'label'  => 'Password Confirmation',
    		            'rules'  => 'required|matches[npassword]',
    		            'errors' => [
    		                'matches' => 'Passwords dose not matches.',
    		                'required' => 'Password Confirmation is required'
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
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$model = new UsersModel;
    				$oldpas = $this->request->getVar('password');
    				$getInfosFromPass = $model->where(['id' => session()->get('suser_id')])->findAll();
    				$userpasswordOlds = $getInfosFromPass[0]["password"];
    
    				if(password_verify($oldpas, $userpasswordOlds)){
    					$data = [
    						'password' => password_hash($this->request->getVar('npassword'), PASSWORD_DEFAULT), 
    					];	
    					$Request = $model->update(session()->get("suser_id"), $data);
    					$modalContent = '<p>Profile updated succefull</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Success', 'text-success', 'modal-lg', "1", "1", "1", "1", "0");	
    				}
    				else {
    					$modalContent = '<p>Incorrect old password</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				
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
	}
}