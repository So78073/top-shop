<?php

namespace App\Controllers;
use App\Models\UsersModel;

class Users extends BaseController
{
    public function index(){
        if(session()->get("suser_groupe") == "9"){ 
        	$router = service('router'); 
	        $controller  = $router->controllerName();  
	        $router = service('router');
	        $method = $router->methodName();
	        $model = new UsersModel;
	        $users = $model->findAll();    
	        $sellers = $model->where('groupe', '1')->findAll();    
	        $support = $model->where('groupe', '2')->findAll();    
            $data = array();
            $mycart = getCart();
            $countmycart = count(session()->get('cart'));
            $data["nbusers"] = count($users);
            $data["nsellers"] = count($sellers);
            $data["nsupport"] = count($support);
            $data["sectionName"] = "Users Manager";
            $settings = fetchSettings();
            if($countmycart > 0){
                $total = 0;
                foreach(session()->get('cart') as $p => $o){ 
                    $total = $total + str_replace('$', '', $o["price"]); 
                }
                $data["total"] = '$'.number_format($total, 2,'.', '');
            }
            else {
                $data["total"] = '$0.00';
            }
            $data["nbitemscart"] = $mycart[0];
            $data["cartInnerHtml"] = $mycart[1];
            $data["settings"] = $settings; 
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("users");
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
    		$output = array('data' => array());
    		if(session()->get("suser_groupe") == "9"){
    			$model = new UsersModel;
    			$Results = $model->findAll();
    			$countresults = count($Results);
    			if($countresults > 0){
    				foreach ($Results as $value) {
    	                $id = '<input type="checkbox" class="selected" id="'.$value["id"].'"> #'.$value["id"];
    	                $username = esc(ucfirst($value["username"]));
    					$email = esc(substr($value["email"],0, 8 )).'...';
    					switch ($value["groupe"]) {
    						case '0':
    							$groupe = 'Client';
    						break;
    						case '1':
    							$groupe = 'Seller';
    						break;
    						case '2':
    							$groupe = 'Support';
    						break;
    						case '9':
    							$groupe = 'Admin';
    						break;
    					}
    					switch ($value["status"]) {
    						case '0':
    							$status = 'Deactivated';
    						break;
    						case '1':
    							$status = 'Activated';
    						break;
    					}
    					$balance = '$'.number_format($value["balance"], 2, '.', '');
    					if($value["groupe"] == "1"){
    						$sellerbalance = '$'.number_format($value["seller_balance"], 2, '.', '');	
    					}
    					else {
    						$sellerbalance = 'N/S';
    					}
    					if($value["groupe"] == "1"){
    						$sellerfees = $value["seller_fees"].'%';
    					}
    					else {
    						$sellerfees = 'N/S';
    					}
    					
    					
    					$referals = $value["referals_count"];
    					$referalsrate = $value["referals_rate"].'%';
    					$ndate = new \DateTime($value["add_datae"]);
    					$createdat = $ndate->format('d/m/y');
    					$ldate = new \DateTime($value["last_login_date"]);
    					$lastelogindate = $ldate->format('d/m/y');
    					$lasteloginip = $value["last_login_ip"];
    					$buttons = '
    						<div class="btn-group " role="group">
    	                  		<button type="button" class="btn btn-primary dropdown-toggle btn-sm " data-bs-toggle="dropdown" aria-expanded="false">
    	                        Manage
    	                      	</button>
    	                      	<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
    	                        	<li>
    	                        		<a data-api="edituserinit-'.$value['id'].'"  class="dropdown-item" href="javascript:void(0);">
    	                        			<span class="fa fa-edit"></span> Edit 
    	                    			</a>
    	                			</li>
    	                        	<li>
    	                        		<a data-api="rmuserinit-'.$value['id'].'"  class="dropdown-item" href="javascript:void(0);">
    	                        			<span class="fa fa-trash"></span> Delete 
    	                        		</a>
    	                    		</li>
    	                      	</ul>
    	                    </div>
    					';
    					$output['data'][] = array(
    		        		$id,
    		        		$username,
    						$email,
    						$groupe,
    						$status,					
    						$balance,					
    						$sellerbalance,					
    						$sellerfees,					
    						$referals,					
    						$referalsrate,					
    						$createdat,					
    						$lastelogindate,					
    						//$lasteloginip,					
    						$buttons,					
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
    					NULL,					
    					NULL,					
    					NULL,					
    					NULL,					
    					NULL,					
    					NULL,					
    					NULL,					
    					NULL,					
    					//NULL,					
    					NULL,					
    				);
    				echo json_encode($output);
    				exit();
    			}
    
    		}
    		else {
    			$output['data'][] = array(
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

	public function initEdit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" ){
    			hearder('location:'.base_url().'/');
    			exit();
    		}
    		else {
    			$response = array();
    			if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id')) && session()->get('suser_groupe') == '9'){
    				$id = $this->request->getPost('id');
    				$Model = new UsersModel;
    				$Results = $Model->where(['id' => $id])->find();
    				$countResults = count($Results);
    				if($countResults == 1){
    					switch ($Results[0]['status']) {
    						case '1':
    							$status = '<div class="form-groupe col-6">
    									<label >Status</label>
    									<select class="form-control select2" name="status" id="status">
    										<option value="1" selected>Activated</option>
    										<option value="0">Deactivated</option>
    									</select>
    									<small class="status text-danger"></small>
    								</div>';
    						break;
    						
    						case '0':
    							$status = '<div class="form-groupe col-6">
    									<label >Status</label>
    									<select class="form-control select2" name="status" id="status">
    										<option value="0" selected>Deactivated</option>
    										<option value="1">Activated</option>
    										
    									</select>
    									<small class="status text-danger"></small>
    								</div>';
    						break;
    					}
    					switch ($Results[0]['groupe']) {
    						case '0':
    							$groupe = '<div class="form-groupe col-6">
    								<label >Groupe</label>
    								<select class="form-control select2" name="groupe" id="groupe">
    									<option value="0" >Client</option>
    									<option value="1">Seller</option>
    									<option value="2">Support</option>
    									<option value="9">Admin</option>
    								</select>
    								<small class="groupe text-danger"></small>
    							</div>';
    							$classto = 'col-md-12';
    						break;
    						
    						case '1':
    							$groupe = '<div class="form-groupe col-6">
    								<label >Groupe</label>
    								<select class="form-control select2" name="groupe" id="groupe">
    									<option value="0">Client</option>
    									<option value="1" selected>Seller</option>
    									<option value="2">Support</option>
    									<option value="9">Admin</option>
    								</select>
    								<small class="groupe text-danger"></small>
    							</div>';
    							$classto = 'col-md-4';
    						break;
    
    						case '2':
    							$groupe = '<div class="form-groupe col-6">
    								<label >Groupe</label>
    								<select class="form-control select2" name="groupe" id="groupe">
    									<option value="0">Client</option>
    									<option value="1">Seller</option>
    									<option value="2" selected>Support</option>
    									<option value="9">Admin</option>
    								</select>
    								<small class="groupe text-danger"></small>
    							</div>';
    							$classto = 'col-md-12';
    						break;
    
    						case '9':
    							$groupe = '<div class="form-groupe col-6">
    								<label >Groupe</label>
    								<select class="form-control select2" name="groupe" id="groupe">
    									<option value="0">Client</option>
    									<option value="1">Seller</option>
    									<option value="2">Support</option>
    									<option value="9" selected>Admin</option>
    								</select>
    								<small class="groupe text-danger"></small>
    							</div>';
    							$classto = 'col-md-12';
    						break;
    					}
    					$ndate = new \DateTime($Results[0]['add_datae']);
    					$ldate = new \DateTime($Results[0]['last_login_date']);
    					$form = '
    						<div class="row">
    							<div class="col-md-6">
    								<p>Join Date: '.$ndate->format('d/m/y h:s').'</p>
    								<p>Refered By: '.$Results[0]['refered_by_username'].'</p>
    							</div>
    							<div class="col-md-6">
    								<p>Last Login: '.$ldate->format('d/m/y h:s').'</p>
    								<p>Last Login IP: '.$Results[0]['last_login_ip'].'</p>
    							</div>
    						</div>
    						<hr>
    						<form id="editUserForm">
    							<div class="row pt-3">
    								<div class="form-group col-6">
    									<label >Email</label>
    									<input type="email" id="email" name="email" class="form-control" placeholder="Email" value="'.esc($Results[0]['email']).'">
    									<small class="email text-danger"></small>
    								</div>
    								<div class="form-group col-6">
    									<label >Change password</label>
    									<input type="text" id="password" name="password" class="form-control" placeholder="Change password">
    									<small class="password text-danger"></small>
    								</div>
    							</div>
    							<div class="row pt-3">
    								<div class="form-group '.$classto.'">
    									<label >Balance</label>
    									<input type="number" id="balance" name="balance" class="form-control" placeholder="300" value="'.esc($Results[0]['balance']).'">
    									<small class="balance text-danger"></small>
    								</div>';
    
    					if($Results[0]['groupe'] == '1'){
    						$form .= '
    								<div class="form-group col-4">
    									<label >Seller Balance</label>
    									<input type="number" id="seller_balance" name="seller_balance" class="form-control" placeholder="10.75" value="'.esc($Results[0]['seller_balance']).'">
    									<small class="seller_balance text-danger"></small>
    								</div>
    								<div class="form-group col-4">
    									<label >Seller fees</label>
    									<input type="number" id="seller_fees" name="seller_fees" class="form-control" placeholder="20" value="'.esc($Results[0]['seller_fees']).'">
    									<small class="seller_fees text-danger"></small>
    								</div>';
    					}
    					
    					$form .= '			
    							</div>
    							<div class="row pt-3">
    								'.$status.'
    								'.$groupe.'
    							</div>
    							<div class="row pt-3">
    								<div class="form-group col-6">
    									<label >Referals Count</label>
    									<input type="number" id="referals_count" name="referals_count" class="form-control" placeholder="0" value="'.esc($Results[0]['referals_count']).'">
    									<small class="referals_count text-danger"></small>
    								</div>
    								<div class="form-group col-6">
    									<label >Referals Rate</label>
    									<input type="number" id="referals_rate" name="referals_rate" class="form-control" placeholder="10" value="'.esc($Results[0]['referals_rate']).'">
    									<small class="referals_rate text-danger"></small>
    								</div>
    							</div>
    							<input type="hidden" name="'.csrf_token().'" value="'.csrf_hash().'">
    							<input type="hidden" name="id" value="'.$Results[0]['id'].'">
    						</form>
    						<script>
    						$(\'select\').select2({
    							width: "100%",
    							dropdownParent: $("#bsModal")
    					    });
    						</script>
    					'; 
    					$modalContent = $form;
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Edit the user '.esc(ucfirst($Results[0]['username'])), '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Save', 'functions' => 'data-api="edituser-'.$Results[0]['id'].'"']);
    				}
    				else {
    					$modalContent = '<p>Object not found. E002</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', '1', '1', '1', '1', '0');
    				}
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$modalContent = '<p>Object not selected. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', '1', '1', '1', '1', '0');
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

	public function edit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9"){
    			exit();
    		}
    		else {
    		    if(session()->get('suser_groupe') == '9'){
        		    $settings = fetchSettings();
        			$response = array();
        			$ValidationRulls = [
        				'email' => [
        		            'rules'  => 'required|valid_email',
        		            'errors' => [
        		            	'required' => 'Email is required.',
        		            	'valid_email' => 'A valid email is required.',
        
        	            	]
        	            ],
        	            'groupe' => [
        		            'rules'  => 'required|numeric',
        		            'errors' => [
        		            	'numeric' => 'Invalid Groupe.',
        		            	'required' => 'This field is required.',
        	            	]
        	            ],
        	            'balance' => [
        		            'rules'  => 'required|numeric',
        		            'errors' => [
        		            	'numeric' => 'Invalid balance, must be a number.',
        		            	'required' => 'This field is required.',
        	            	]
        	            ],
        	            'status' => [
        		            'rules'  => 'required|numeric',
        		            'errors' => [
        		            	'numeric' => 'Invalid Status.',
        		            	'required' => 'This field is required.',
        	            	]
        	            ],
        	            'referals_count' => [
        		            'rules'  => 'required|numeric',
        		            'errors' => [
        		            	'numeric' => 'Invalid referals count.',
        		            	'required' => 'This field is required.',
        	            	]
        	            ],
        	            'referals_rate' => [
        		            'rules'  => 'required|numeric',
        		            'errors' => [
        		            	'numeric' => 'Invalid referals rate.',
        		            	'required' => 'This field is required.',
        	            	]
        	            ],
        			];
        			if(null !== $this->request->getPost("seller_balance") && $this->request->getPost("seller_balance") !== ""){
        				$ValidationRulls["seller_balance"] = array(
        		            'rules'  => 'required|numeric',
        		            'errors' => array(
        		            	'numeric' => 'Invalid referals rate.',
        		            	'required' => 'This field is required.',
        	            	)
        	         	);
        			}
        			if(null !== $this->request->getPost("seller_fees") && $this->request->getPost("seller_fees") !== ""){
        				$ValidationRulls["seller_fees"] = array(
        		            'rules'  => 'required|numeric',
        		            'errors' => array(
        		            	'numeric' => 'Invalid referals rate.',
        		            	'required' => 'This field is required.',
        	            	)
        	         	);
        			}
        			if($this->request->getPost("password") !== ""){
        				$ValidationRulls["password"] = array(
        		            'rules'  => 'required|min_length[8]|alpha_numeric_punct',
        		            'errors' => array(
        		            	'required' => 'Password are required.',
        		                'min_length' => 'A valid Password can contain at minimum 8 characters.',
        		                'max_leng' => 'A valid Password can contain at max 30 characters.',
        		                'alpha_dash' => 'A valid Password can contain only alphanumeric, Dashes(-) and Underscors(_) characters.',
        		            )
        	         	);
        			}
        
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
        				$response = array();
        				if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id'))){
        					$id = $this->request->getPost('id');
        					$Model = new UsersModel;
        					$Results = $Model->where(['id' => $id])->find();
        					$countResults = count($Results);
        					if($countResults == 1){
        						$data = [];
        						foreach($this->request->getPost() as $key => $val){
        							if($val != ""){
        								if($key == "password"){
        									$data[$key] = password_hash($val, PASSWORD_DEFAULT);
        								}
        								else {
        								    if($this->request->getPost('groupe') == '1'){
        								        $data['seller_fees'] = $settings[0]['sellerate'];
        								    }
        									$data[$key] = $val;	
        								}
        							}	
        						}
        						$Request = $Model->update($id, $data);
        						$modalContent = '<p>User Edited successfully</p>';
        						$modalTitle = 'Edit successfully';
        						$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
        					}
        					else {
        						$modalContent = '<p>Object not found. E002</p>';
        						$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
        					}
        					$response["csrft"] = csrf_hash();
        					header('Content-Type: application/json');
        					echo json_encode($response);
        					exit();
        				}
        				else {
        					$modalContent = '<p>Object not selected. E003</p>';
        					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
        					$response["csrft"] = csrf_hash();
        					header('Content-Type: application/json');
        					echo json_encode($response);
        					exit();	
        				}
        			}
    			}
    			else {
    			    $response = array();
    			    $modalContent = '<p>Object not selected. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', '1', '1', '1', '1', '0');
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

	public function massinitEdit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") !== "9"){
    			hearder('location:'.base_url().'/');
    			exit();
    		}
    		else {
    			$response = array();
    				$form = '
    				<form id="MasseditUserForm">
    					<div class="form-group row pt-3">
    						<div class="col-md-4">
    							<label >Balance</label>
    							<input type="number" id="balance" name="balance" class="form-control" placeholder="300">
    							<small class="balance text-danger"></small>
    						</div>
    						<div class="col-4">
    							<label >Seller Balance</label>
    							<input type="number" id="seller_balance" name="seller_balance" class="form-control" placeholder="10.75">
    							<small class="seller_balance text-danger"></small>
    						</div>
    						<div class="col-4">
    							<label >Seller fees</label>
    							<input type="number" id="seller_fees" name="seller_fees" class="form-control" placeholder="20">
    							<small class="seller_fees text-danger"></small>
    						</div>			
    					</div>
    					<div class="form-group row pt-3">
    						<div class="col-6">
    							<label >Status</label>
    							<select class="form-control select2" name="status" id="status">
    								<option selected>Select One</option>
    								<option value="0">Deactivated</option>
    								<option value="1">Activated</option>
    								
    							</select>
    							<small class="status text-danger"></small>
    						</div>
    						<div class="col-6">
    							<label >Groupe</label>
    							<select class="form-control select2" name="groupe" id="groupe">
    								<option selected>Select One</option>
    								<option value="0" >Client</option>
    								<option value="1">Seller</option>
    								<option value="2">Support</option>
    								<option value="9">Admin</option>
    							</select>
    							<small class="groupe text-danger"></small>
    						</div>
    					</div>
    					<div class="form-group row pt-3">
    						<div class="col-6">
    							<label >Referals Count</label>
    							<input type="number" id="referals_count" name="referals_count" class="form-control" placeholder="0">
    							<small class="referals_count text-danger"></small>
    						</div>
    						<div class="col-6">
    							<label >Referals Rate</label>
    							<input type="number" id="referals_rate" name="referals_rate" class="form-control" placeholder="10">
    							<small class="referals_rate text-danger"></small>
    						</div>
    					</div>
    					<input type="hidden" name="'.csrf_token().'" value="'.csrf_hash().'">
    				</form>
    				<script>
    				$(\'select\').select2({
    					width: "100%",
    					dropdownParent: $("#bsModal")
    			    });
    				</script>
    			'; 
    			$modalContent = $form;
    			$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Edit the users', '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Save', 'functions' => 'data-api="massedit"']);
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

	public function massedit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") !== "9"){
    			exit();
    		}
    		else {
    			$response = array();
    			if(null !== $this->request->getPost("groupe") && $this->request->getPost("groupe") !== ""){
    				$ValidationRulls["groupe"] = array(
    		            'rules'  => 'required|numeric',
    		            'errors' => array(
    		            	'numeric' => 'Invalid referals rate.',
    		            	'required' => 'This field is required.',
    	            	)
    	         	);
    			}
    			if(null !== $this->request->getPost("balance") && $this->request->getPost("balance") !== ""){
    				$ValidationRulls["balance"] = array(
    		            'rules'  => 'required|numeric',
    		            'errors' => array(
    		            	'numeric' => 'Invalid referals rate.',
    		            	'required' => 'This field is required.',
    	            	)
    	         	);
    			}
    			if(null !== $this->request->getPost("status") && $this->request->getPost("status") !== ""){
    				$ValidationRulls["status"] = array(
    		            'rules'  => 'required|numeric',
    		            'errors' => array(
    		            	'numeric' => 'Invalid referals rate.',
    		            	'required' => 'This field is required.',
    	            	)
    	         	);
    			}
    			if(null !== $this->request->getPost("referals_count") && $this->request->getPost("referals_count") !== ""){
    				$ValidationRulls["referals_count"] = array(
    		            'rules'  => 'required|numeric',
    		            'errors' => array(
    		            	'numeric' => 'Invalid referals rate.',
    		            	'required' => 'This field is required.',
    	            	)
    	         	);
    			}
    			if(null !== $this->request->getPost("referals_rate") && $this->request->getPost("referals_rate") !== ""){
    				$ValidationRulls["referals_rate"] = array(
    		            'rules'  => 'required|numeric',
    		            'errors' => array(
    		            	'numeric' => 'Invalid referals rate.',
    		            	'required' => 'This field is required.',
    	            	)
    	         	);
    			}
    			if(null !== $this->request->getPost("seller_balance") && $this->request->getPost("seller_balance") !== ""){
    				$ValidationRulls["seller_balance"] = array(
    		            'rules'  => 'required|numeric',
    		            'errors' => array(
    		            	'numeric' => 'Invalid referals rate.',
    		            	'required' => 'This field is required.',
    	            	)
    	         	);
    			}
    			if(null !== $this->request->getPost("seller_fees") && $this->request->getPost("seller_fees") !== ""){
    				$ValidationRulls["seller_fees"] = array(
    		            'rules'  => 'required|numeric',
    		            'errors' => array(
    		            	'numeric' => 'Invalid referals rate.',
    		            	'required' => 'This field is required.',
    	            	)
    	         	);
    			}
    			
    
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
    				$response = array();
    				if($this->request->getPost('id') != ""){
    					$id = explode(',',$this->request->getPost('id'));
    					$Model = new UsersModel;
    					foreach($id as $t => $mids){
    						$Results = $Model->where(['id' => $mids])->find();
    						$countResults = count($Results);
    						if($countResults == 1){
    							$data = [];
    							foreach($this->request->getPost() as $key => $val){
    								if($val != ""){
    									$data[$key] = $val;	
    								}	
    							}
    							$Request = $Model->update($mids, $data);
    							$modalContent = '<p>Users Edited successfully</p>';
    							$modalTitle = 'Edit successfully';
    							$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
    						}
    						else {
    							$modalContent = '<p>Object not found. E002</p>';
    							$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    						}
    					}
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    				else {
    					$modalContent = '<p>Object not selected. E003</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();	
    				}
    			}
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function rmuserinit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") !== "9"){
    			exit();
    		}
    		else {
    			if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id'))){
    				$id = $this->request->getPost('id');
    				$Model = new UsersModel;
    				$Results = $Model->where(['id' => $id])->find();
    				$countResults = count($Results);
    				if($countResults == 1){
    					$modalContent = '<p>Do you realy wan to remove this user ?</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "1",['text' => 'Delete', 'functions' =>'data-api="rmuser-'.$Results[0]["id"].'"']);
    				}
    				else {
    					$modalContent = '<p>Object not found. E002</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    
    			}
    			else {
    				$modalContent = '<p>Object not selected. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
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

	public function rmuser(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") !== "9"){
    			exit();
    		}
    		else {
    			if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id'))){
    				$id = $this->request->getPost('id');
    				$Model = new UsersModel;
    				$Results = $Model->where(['id' => $id])->find();
    				$countResults = count($Results);
    				if($countResults == 1){
    					$Model->delete($Results[0]["id"]);
    					$modalContent = '<p>User Deleted.</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				else {
    					$modalContent = '<p>Object not found. E002</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    
    			}
    			else {
    				$modalContent = '<p>Object not selected. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
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

	public function massrmuserinit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") !== "9"){
    			exit();
    		}
    		else {
    			if($this->request->getPost('id') != ""){
    				$id = explode(',',$this->request->getPost('id'));
    				
    				$countResults = count($id);
    				if($countResults > 0){
    					$modalContent = '<p>Do you realy wan to remove '.$countResults.' users ?</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "1",['text' => 'Delete', 'functions' =>'data-api="massrmuser"']);
    				}
    				else {
    					$modalContent = '<p>Object not found. E002</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    
    			}
    			else {
    				$modalContent = '<p>Object not selected. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
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

	public function massrmuser(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") !== "9"){
    			exit();
    		}
    		else {
    			if($this->request->getPost('id')){
    				$id = explode(',',$this->request->getPost('id'));
    				foreach($id as $m => $ids){
    					$Model = new UsersModel;
    					$Results = $Model->where(['id' => $ids])->find();
    					$countResults = count($Results);
    					if($countResults == 1){
    						$Model->delete($Results[0]["id"]);
    						$modalContent = '<p>User Deleted.</p>';
    						$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					}
    					else {
    						$modalContent = '<p>Object not found. E002</p>';
    						$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					}
    				}					
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$modalContent = '<p>Object not selected. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
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
