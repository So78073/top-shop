<?php
namespace App\Controllers;
//use App\Models\Activationmodel;
use App\Models\UsersModel;
use App\Models\SignupModel;
use App\Models\LoginModel;
class Login extends BaseController
{
	
	public function index()
    {
        $settings = fetchSettings();
        if(session()->get("logedin") == "1"){     
            header('location:'.base_url().'/home');
            exit();
        }
        else {
            $data["settings"] = $settings;
            $data["BodyClass"] = "login-page";
            $data["pageContainer"] = "container";
            $data["captcha"] = captcha();
            session()->set("captcha", null);
            session()->set("captcha", $data["captcha"]["results"]);
            echo view("assets/header", $data);
            echo view("login");
            echo view("assets/scripts");
        }
    }

	/**public function activation(){
		if(session()->get("logedin") == "1" && $this->req->getVar('c') == ""){
			header('location:'.base_url().'/');
			exit();
		}
		else {
			$code = $this->request->getVar('c');
			$data = [];
			$FunctionModel = new Activationmodel;
			$QueryResults = $FunctionModel->where('activationcode',$code)->findAll();
			if(count($QueryResults) == 1){
				$dataUpdate = [
					'activationcode' => null,
					'status' => '1'
				];
				$FunctionModel->update($QueryResults[0]['id'], $dataUpdate);
				$data["Message"] = "Your account has been activated.";
				$data["MessageClassType"] = "lobibox-notify-success";
				$data["NotifTitle"] = "Success !";
			}
			else {
				$data["Message"] = "Invalide activation code.";
				$data["MessageClassType"] = "lobibox-notify-warning";
				$data["NotifTitle"] = "Error !";
			}
			return view('login', $data);
		}
	}**/

    public function logmein(){
        if($this->request->isAJAX()){
        	$response = array();
            if(session()->get("logedin") == "1"){
                header('location:'.base_url().'/');
                exit();
            }
            else {
            	if($this->request->getPost()){
            		$mydata = [
    	                'username' => [
                            'label'  => 'Choose a Username',
                            'rules'  => 'required|min_length[3]|max_length[30]|UserName',
                            'errors' => [
                                'required' => 'Username is required.',
                                'min_length' => 'A valid Username is between 3 and 30 charts.',
                                'max_length' => 'A valid Username is between 3 and 30 charts.',
                                'is_unique' => 'This Username is already registered.',
                                'UserName' => '(Only those characters are allowed  a-z A-Z 0-9 _ -)',
    
                            ]
                        ],
    	                'password' => [
                            'label'  => 'Password',
                            'rules'  => 'required|min_length[4]|max_length[30]|alpha_numeric_punct|loginChec[user_email, user_password]',
                            'errors' => [
                                'required' => 'Password is required.',
                                'min_length' => 'A valid Password is between 4 and 30 charts.',
                                'max_length' => 'A valid Password is between 4 and 30 charts.',
                                'PassCheck' => 'Only those characters are allowed (a-z A-Z 0-9 _ - * $ % . ! ? : , ; / \\)',
                                'loginChec' => 'Username or password are incorrect.',
                            ]
                        ],
    	                /**'captcha' => [
                            'label'  => 'Resolve Captcha',
                            'rules'  => 'required|captchaCheck',
                            'errors' => [
                                'required' => 'Captcha is required.',
                                'captchaCheck' => 'The Captcha is invalidator.',
                            ]
                        ],**/
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
    	            	if($this->request->getPost("captcha") /**== session()->get("captcha")**/){
    	            		$modelLogin = new LoginModel;
    	            		$modelUsers = new UsersModel;
    		                $GetUserResults = $modelLogin->where(['username' => trim($this->request->getPost('username'))])->findall();
    		                if($GetUserResults[0]["status"] == "1"){
                                $modalContent = '<p>Username: <span style="color:green">Matched</span></p><p>Password: <span style="color:green">Matched</span></p><p>Login successfully, redirecting you now...</p>';
    							$response["modal"] = createModal($modalContent, '', 'Notification', 'text-primary', 'modal-lg', "1", "1", "1", "0", "0");  
     		                    $ndat = new \DateTime();
    		                    $data = [
    		                    	'last_login_date' =>  $ndat->format("Y-m-d H:i:s"),
    		                    	'last_login_ip' => $this->request->getIPAddress(),
    		                    ];
    		                    $modelUsers->update($GetUserResults[0]["id"], $data);
    
    		                    $this->setUserInfoSessionData($GetUserResults);
    		                }
    		                else {
    		                	$data["captcha"] = captcha();
    	                        session()->set("captcha", null);
    	                        session()->set("captcha", $data["captcha"]["results"]);
    	                        $ErrorsText = ["Your account has been Banned.", $data["captcha"]["part1"].' + '.$data["captcha"]["part2"]];
    	                        $keys = ["username", "newcaptcha"];
    	                        $Errors =  array_combine($keys, $ErrorsText);
    	                        $response["fieldslist"] =  $Errors; 
    		                }
    	            	}
    	            	else {
    						$data["captcha"] = captcha();
                            session()->set("captcha", null);
                            session()->set("captcha", $data["captcha"]["results"]);
                            $ErrorsText = ["Captcha is Incorrect, Please try again", $data["captcha"]["part1"].' + '.$data["captcha"]["part2"]];
                            $keys = ["captcha", "newcaptcha"];
                            $Errors =  array_combine($keys, $ErrorsText);
                            $response["fieldslist"] =  $Errors; 
    	            	}   
    	            }
            	}      
            }
            $response["csrft"] = csrf_hash();
    		header('Content-Type: application/json');
            return $this->response->setJSON($response);
            exit();
        }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
    }

    private function setUserInfoSessionData($userInfos){
        foreach($userInfos[0] as $key => $val){
            $data['suser_'.$key] = esc($val);
        }
        $data['logedin'] = '1';
        $data['cart'] = array();
        session()->set($data);
        return true;
    }
}
