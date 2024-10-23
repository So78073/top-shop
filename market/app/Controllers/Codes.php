<?php

namespace App\Controllers;
use App\Models\CodesModel;
use App\Models\NotificationsModel;
use App\Models\UsersModel;
use App\Models\CodesExtendedModel;
use monken\TablesIgniter;

class Codes extends BaseController
{
	public function index()
	{
		if(session()->get("suser_groupe") == "9"){
			$data = [];
            $settings = fetchSettings();
            $mycart = getCart();
            $data["sectionName"] = "Codes";
            $data["nbitemscart"] = $mycart[0];
            $data["cartInnerHtml"] = $mycart[1];
            $data["settings"] = $settings;
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("codes");
            echo view("assets/footer");
            echo view("assets/scripts");
		}
		else {
			header('location:'.base_url().'/login');
			exit();
		}
	}

	public function createLogInit(){
	    if($this->request->isAJAX()){
        	if(session()->get("suser_groupe") != '9'){
    			exit();
    		}
    		else {
    			$form = '
    				<form id="addCont" enctype="multipart/form-data">
    					<div class="row">
    						<div class="col-12 form-group">
    							<label>Value $<i class="text-danger"> *</i></label>
    							<input type="number" id="value" name="value" class="form-control">
    							<small class="value text-danger"></small>
    						</div>
    					</div>
    				</form>
    			';
    			$modalTitle = 'Create Voucher Codes';
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
    		if(session()->get("suser_groupe") != '9'){
    			exit();
    		}
    		else {
    			$value = $this->request->getPost('value');
    			$all = array("a","b","c","d","e","f","g","h","i","g","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","G","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0");
    	        $arraycounts = count($all);
    			$long = 12;
    			$i = 1;
    			$acodes = '';
    			for($i=1;$i<=$long;$i++){
    				$rand = rand(0,$arraycounts-1);
    				$acodes .=  $all[$rand];
    			}
    			$model = new CodesModel;
    			$data = [
    				'value' => $value,
    				'code' => $acodes
    			];
    
    			$model->save($data);
    			$modalTitle = 'Voucher Code Created';
    			$content = '<p>'.$acodes .'-> $'.$value.'</p>';
    			$response["modal"] = createModal($content, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
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


	public function fetchTable(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") == '9'){
    			$model = new CodesExtendedModel();
    			$table = new TablesIgniter($model->initTable());
    			return $table->getDatatable();
    		}
    		else {
    			header('location:'.base_url());
    			exit();
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }

	}


	public function rminit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9"){
    			exit();
    		}
    		else {
    			if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id'))){
    				$id = $this->request->getPost('id');
    				$Model = new CodesModel;
    				$Results = $Model->where(['id' => $id])->find();
    				
    				$countResults = count($Results);
    				if($countResults == 1){
    					$modalContent = '<p>Do you really want to remove this item ?</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "1",['text' => 'Delete', 'functions' =>'data-api="rm-'.$Results[0]["id"].'"']);
    				}
    				else {
    					$modalContent = '<p>Object not found. E002</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    
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

	public function rm(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9"){
    			exit();
    		}
    		else {
    			if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id'))){
    				$id = $this->request->getPost('id');
    				$Model = new CodesModel;
    				
    				$Results = $Model->where(['id' => $id])->find();
    							
    				$countResults = count($Results);
    				if($countResults == 1){
    					$Model->delete($Results[0]["id"]);
    					$modalContent = '<p>Item Deleted.</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				else {
    					$modalContent = '<p>Object not found. E002</p>';
    					$response["modal"] = createModal($modalContent, 'fade bounce animated', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
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