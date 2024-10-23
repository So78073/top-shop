<?php

namespace App\Controllers;
use App\Models\SignupModel;
use App\Models\UsersModel;

class Signup extends BaseController
{
    public function index(){
    	$settings = fetchSettings();
        if(session()->get("logedin") == "1" || $settings[0]["openreg"] == "0"){     
            header('location:'.base_url().'/home');
            exit();
        }
        else {

            if(null !== $this->request->getGet("r") && $this->request->getGet("r") != "" && $settings[0]["refsys"] =='1'){
                session()->set("refered_by", $this->request->getGet("r"));
            }
            $data["settings"] = $settings;
            $data["BodyClass"] = "login-page";
            $data["Sitename"] = "HACKSAW";
            $data["pageContainer"] = "container";
            $data["captcha"] = captcha();
            session()->set("captcha", null);
            session()->set("captcha", $data["captcha"]["results"]);
            echo view("assets/header", $data);
			echo view('signup', $data);
			echo view("assets/scripts");
        }
    }

	public function signupme(){
	    if($this->request->isAJAX()){
    		$response = array();
    		$settings =  fetchSettings();
    		if(session()->get("logedin") == "1" || $settings[0]["openreg"] == "0"){
    			$content = '<p>Error, You are alrady Loged in</p>';
    			$response["modal"] = $this->createModal($content, "Error", 'modal-lg modal-lg danger', "1",  "1",  "1",  ['functions' => '', 'text' =>'Test']);
    			$response["csrft"] =  csrf_hash();
    			echo json_encode($response);
    			exit();
    		}
    		else {
    			$ValidationRulls = [
            		'email' => [
    		            'label'  => 'Email',
    		            'rules'  => 'required|valid_email|is_unique[users.email]',
    		            'errors' => [
    		            	'required' => 'Email are required.',
    		                'valid_email' => 'A valid Email are required.',
    		                'is_unique' => 'This email is already registered.',
    
    		            ]
    		        ],
    		        'username' => [
    		            'label'  => 'Username',
    		            'rules'  => 'required|min_length[3]|max_length[30]|alpha_dash|is_unique[users.username]',
    		            'errors' => [
    		            	'required' => 'Username are required.',
    		                'min_length' => 'A valid Username can contain at minimum 3 characters.',
    		                'max_leng' => 'A valid Username can contain at max 30 characters.',
    		                'alpha_dash' => 'A valid Username can contain only alphanumeric, Dashes(-) and Underscors(_) characters.',
    		                'is_unique' => 'Please choose another username.',
    		            ]
    		        ],
    		        'password' => [
    		            'label'  => 'Password',
    		            'rules'  => 'required|min_length[4]|alpha_numeric_punct',
    		            'errors' => [
    		            	'required' => 'Password are required.',
    		                'min_length' => 'A valid Password can contain at minimum 4 characters.',
    		                'max_leng' => 'A valid Password can contain at max 30 characters.',
    		                'alpha_dash' => 'A valid Password can contain only alphanumeric, Dashes(-) and Underscors(_) characters.',
    		            ]
    		        ],
    		        'rpassword' => [
    		            'label'  => 'Password Confirmation',
    		            'rules'  => 'required|matches[password]',
    		            'errors' => [
    		                'matches' => 'Passwords does not matches.',
    		                'required' => 'Password Confirmation are reqiured.'
    		            ]
    		        ],
    		        'captcha' => [
                        'label'  => 'Resolve Captcha',
                        'rules'  => 'required|captchaCheck',
                        'errors' => [
                            'required' => 'Captcha is required.',
                            'captchaCheck' => 'The Captcha is invalidator.',
                        ]
                    ],
    			];
    
    			if($settings[0]["invitecode"] == "1"){
    				$all = array("a","b","c","d","e","f","g","h","i","g","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","G","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0");
    		        $arraycounts = count($all);
    				$long = 8;
    				$i = 1;
    				$code = '';
    				for($i=1;$i<=$long;$i++){
    					$rand = rand(0,$arraycounts-1);
    					$code .=  $all[$rand];
    				}
    				$UserInviteCode = $code;
    				$ValidationRulls['icode'] = array(
    		            'label'  => 'Invitation code',
    		            'rules'  => 'regex_match[/[a-zA-Z0-9]+$/]|exact_length[8]|is_not_unique[breakers.invitecode]',
    		            'errors' => array(
    		                'regex_match' => 'Invitation Code are required.',
    		                'exact_length' => 'Invitation code must have 8 characters.',
    		                'is_not_unique' => 'Invalid invite Code.'
    		            )
    		        );	
    			}
    			else {
    				$UserInviteCode = "";	
    			}
    
    			if($settings[0]["icq"] == "1" && $settings[0]["ricq"] == "1"){
    				$ValidationRulls['icq'] = array(
    		            'label'  => 'ICQ',
    		            'rules'  => 'regex_match[/^\@[a-zA-Z0-9]{3,10}+$/]',
    		            'errors' => array(
    		                'regex_match' => 'Please inser a valid ICQ ID.'
    		            )
    		        );
    			}
    			else if($settings[0]["icq"] == "1" && $settings[0]["ricq"] == "0"){
    				if($this->request->getVar('icq') !== ""){
    					$ValidationRulls['icq'] = array(
    			            'label'  => 'ICQ',
    			            'rules'  => 'regex_match[/^\@[a-zA-Z0-9]{3,10}+$/]',
    			            'errors' => array(
    			                'regex_match' => 'Please inser a valid ICQ ID.'
    			            )
    			        );
    				}
    			}
    			if($settings[0]["telegram"] == "1" && $settings[0]["rtelegram"] == "1"){
    				$ValidationRulls['telegram'] = array(
    		            'label'  => 'Telegram',
    		            'rules'  => 'regex_match[/^\@[a-zA-Z0-9]{3,10}+$/]',
    		            'errors' => array(
    		                'regex_match' => 'Please inser a valid Telegram ID.'
    		            )
    		        );
    			}
    			else if($settings[0]["telegram"] == "1" && $settings[0]["rtelegram"] == "0"){
    				if($this->request->getVar('telegram') !== ""){
    					$ValidationRulls['telegram'] = array(
    			            'label'  => 'Telegram',
    			            'rules'  => 'regex_match[/^\@[a-zA-Z0-9]{3,30}+$/]',
    			            'errors' => array(
    			                'regex_match' => 'Please inser a valid Telegram ID.'
    			            )
    			        );
    				}
    			}
    			if($settings[0]["jaber"] == "1" && $settings[0]["rjaber"] == "1"){
    				$ValidationRulls['jaber'] = array(
    		            'label'  => 'Jaber',
    		            'rules'  => 'valid_email',
    		            'errors' => array(
    		                'valid_email' => 'Please inser a valid Jaber ID.'
    		            )
    		        );
    			}
    			else if($settings[0]["jaber"] == "1" && $settings[0]["rjaber"] == "0"){
    				if($this->request->getVar('jaber') !== ""){
    					$ValidationRulls['jaber'] = array(
    			            'label'  => 'Jaber',
    			            'rules'  => 'regex_match[/^\@[a-zA-Z0-9]{3,10}+$/]',
    			            'errors' => array(
    			                'regex_match' => 'Please inser a valid Jaber ID.'
    			            )
    			        );
    				}
    			}
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
    			}
    			else {
    				$FunctionModel = new SignupModel;
    				//instant
    				$settings[0]["emailconfirm"] = "0";
    				switch($settings[0]["emailconfirm"]){
    					case "0" :
    						$Mfooter = "0";
    						$Mclose = "0";
    						$UserStatus = "1";
    						$UserActivationCode = "";
    						$SignupMsg = '<p>Welcome</p><p>Redirecting...</p>
    						<script>setTimeout(function(){window.location.href="'.base_url().'"},300);</script>';
    					break;
    					case "1":
    						$all = array("a","b","c","d","e","f","g","h","i","g","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","G","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0");
    				        $arraycounts = count($all);
    						$long = 16;
    						$i = 1;
    						$acodes = '';
    						for($i=1;$i<=$long;$i++){
    							$rand = rand(0,$arraycounts-1);
    							$acodes .=  $all[$rand];
    						}
    						$Mfooter = "1";
    						$Mclose = "1";
    						$UserStatus = "0";
    						$UserActivationCode = md5(sha1($acodes));
    
    						$htmlletter = '<h4>Confirm your email.</h4>
    						<p>To be able to use our website, we rquire our users tonfirm there emails.</p> 
    						<p>To confirm your email, Please click on this link <a href="'.base_url().'/activation?c='.$acodes.'">'.base_url().'/activation?c='.$acodes.'</a></p>
    						<br/>
    						<br/>
    						<small>'.base_url().'</small>';	
    						$config["mailType"] = 'html';
    						$email = \Config\Services::email();
    						$email->initialize($config);
    						$email->setFrom($settings[0]["setemailconfirm"], 'No replay');
    						$email->setTo($this->request->getVar('email'));
    						$email->setSubject('Email confirmation.');
    						$email->setMessage($htmlletter);
    						$email->send();
    						$SignupMsg = '<h5>Congratulations, Your account hase been created</h5>
    						<h5>To activate your account, Please verify your email.</h5>
    						Not : If you cannot find the confirmation email in your inbox, please check your spam foolder';
    
    					break;
    				}
    				
    				/**$data = [
    					'username' => $acodes,
    					'password' => $pass,
    					'email' => $acodes.'@carder.cc'
    				];**/
    
    				foreach($this->request->getPost() as $key => $val){
    					$data[$key] = $val;
    				}
    				
    				$data["status"] = $UserStatus;
    				$data["invitecode"] = $UserInviteCode;
    				$data["activationcode"] = $UserActivationCode;
    				$usersmodel = new UsersModel;
    				if($settings[0]["refsys"] =='1'){
    					if(null !== session()->get("refered_by")){
    						$getUserInfosReferal = $usersmodel->where('referal_code', session()->get("refered_by"))->findAll();
    						if(count($getUserInfosReferal) == 1){
    							$getip = $usersmodel->where('last_login_ip', $this->request->getIPAddress())->findAll();
    							if(count($getip) == 0){
    								if($this->request->getIPAddress() != $getUserInfosReferal[0]['last_login_ip']){
    									$data["refered_by_id"] = $getUserInfosReferal[0]['id'];
    									$data["refered_by_username"] = $getUserInfosReferal[0]['username'];
    									
    									$newnbreferals = $getUserInfosReferal[0]['referals_count']+1;
    									$updateReferalnb = [
    										'referals_count' => $newnbreferals
    									];
    									$usersmodel->update($getUserInfosReferal[0]['id'], $updateReferalnb);
    									session()->remove("refered_by");
    								}
    								else {
    									session()->remove("refered_by");
    								}
    							}
    							else {
    								session()->remove("refered_by");
    							}	
    						}
    						else {
    							session()->remove("refered_by");
    						}
    					}
    					$data["referal_code"] = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    				}
    				
    				$data["last_login_ip"] = $this->request->getIPAddress();
    				$Results = $FunctionModel->insert($data);
    				$last = $FunctionModel->getInsertID();
    				$GetUserResults = $usersmodel->where(['id' => $last])->findAll();
                    if($GetUserResults[0]["status"] == "1"){
                        $this->setUserInfoSessionData($GetUserResults[0]);     
    					$modalContent = '<p class="text-success">Signup Successful</p>'.$SignupMsg;
    					$modalTitle = "Success";
    					$response["modal"] = createModal($modalContent, 'fade animated', $modalTitle, 'text-success', 'modal-lg', "0", '1', "1", '1', "0");
    				}
    				else {
    					$modalContent = '<h4 class="text-danger">Error!</h4><h5>Something went wrong !</h5>';
    					$modalTitle = "Error !";
    					$response["modal"] = createModal($modalContent, $modalTitle, 'text-success', 'modal-lg', "0", '1', "0", '1', "0");
    				}		
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
	private function setUserInfoSessionData($userInfos){
        foreach($userInfos as $key => $val){
            $data['suser_'.$key] = esc($val);
        }
        $data['logedin'] = '1';
        $data['cart'] = array();
        session()->set($data);
        return true;
    }
}
