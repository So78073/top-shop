<?php

namespace App\Controllers;
use App\Models\CardsModel;
use App\Models\UsersModel;
use App\Models\SectionsModel;
use App\Models\NotificationsModel;
use monken\TablesIgniter;
class Bases extends BaseController
{
	function sectionControl(){
		if(verifysection('1') != null){
    		$verify = verifysection('1');
	    	if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9'){
	    		header('location:'.base_url());
	    		exit();
	    	}
	    	else if($verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9') {
	    		$view = 'maintenance';
	    		return ['view' => $view, 'sellersactivate' => 0];
	    		
	    	}
	    	else {
	    		$view = 'cards';
	    		return ['view' => $view, 'sellersactivate' => $verify['sellersactivate']];
	    	}
    	}
    	else {
    		header('location:'.base_url());
    		exit();
    	}
	}
	public function index(){
		if(session()->get("suser_groupe") == "1" || session()->get("suser_groupe") == "9"){
			$sectionVerif = $this->sectionControl();
			$data = [];
			$settings = fetchSettings();
			$mycart = getCart();
			$modelCards = new CardsModel;
			$data["nbitemscart"] = $mycart[0];
			$data["cartInnerHtml"] = $mycart[1];
			$data["settings"] = $settings;
			$data["sectionName"] = "My Bases";
			$data["sellersactivate"] = $sectionVerif['sellersactivate'];
			echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("bases");
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
			if(session()->get('suser_groupe') != '1' && session()->get('suser_groupe') != '9'){
				header('location:'.base_url());
				exit();
			}
			else {
				$cardsModel = new CardsModel;
				if(session()->get('suser_groupe') == '1'){
					$Results = $cardsModel->where(['sellerid'=> session()->get('suser_id')])->findAll();
				}
				else if(session()->get('suser_groupe') == '9'){
					$Results = $cardsModel->findAll();
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
				if(count($Results) > 0){
					$Bases = [];
					$Data = [];
					foreach($Results as $key => $value) {
						$Bases[] = $value['base'];
					}
					$ucBases = array_unique($Bases);
					foreach($ucBases as $k => $v){
						$getContains = $cardsModel->where(['base' => $v, 'sellerid' => session()->get('suser_id')])->findAll();
						$getSelled = $cardsModel->where(['base' => $v, 'selled' => '1', 'sellerid' => session()->get('suser_id')])->findAll();
						$Data[] = [
							'basename' => $v , 
							'approved' => $getContains[0]['baseapproved'],
							'contains' => count($getContains), 
							'selled' => count($getSelled), 
						];
					}
					foreach($Data as $c => $d){
						$prgrspers = intval($d['selled']/$d['contains'] * 100); 
						$progress = '<div class="progress" style="height:20px">
						    <div class="progress-bar progress-bar-striped" style="width:'.$prgrspers.'%;height:20px" aria-valuemin="0" aria-valuemax="100">'.$d['selled'].'/'.$d['contains'].'</div>
						  </div>';

						if((intval($prgrspers) - 100) == 0){
							$check = '';
							$tools = '<div class="btn-groupe text-center">
								<button type="button" disabled class="btn btn-indigo btn-sm disabled text-white"> Set Discount %<span class="bx bx-percent"></span></button>
								<button type="button" disabled class="btn btn-info btn-sm disabled">Edit Base<span class="bx bx-edit"></span></button>
								<button type="button" disabled class="btn btn-danger btn-sm disabled">Delete Base<span class="bx bx-trash"></span></button>
								</div>';
						}
						else {
							$check = '';
							$tools = '<div class="btn-groupe text-center">
							<button type="button" data-api="discount-'.base64_encode($d['basename']).'" class="btn btn-indigo btn-sm"> Set Discount %<span class="bx bx-percent"></span></button>
							<button type="button" data-api="editinit-'.base64_encode($d['basename']).'" class="btn btn-info btn-sm">Edit Base<span class="bx bx-edit"></span></button>
							<button type="button" data-api="initbasedelete-'.base64_encode($d['basename']).'" class="btn btn-danger btn-sm">Delete Base<span class="bx bx-trash"></span></button>
							</div>';
						}
						if($d['approved'] == '1'){
							$approvd = '<span class="btn btn-success btn-sm">Approved</span>';
						}
						elseif($d['approved'] == '2'){
							$approvd = '<span class="btn btn-danger btn-sm">Declined</span>';
						}
						else {
							$approvd = '<span class="btn btn-warning btn-sm">Waitting appoval</span>';
						}
						$output['data'][] = array(
							$check,
    		        		esc($d['basename']),
    		        		$approvd,
    		        		$progress,
    		        		esc($d['contains'].' Cards'),	
    						$tools,
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
    				);
    				echo json_encode($output);
    				exit();
    			}
			}
		}
		else {
			echo "Nice try x)";
			exit();
		}
	}
	public function initEdit(){
		if($this->request->isAJAX()){
			$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1' ){
    			if(null !== $this->request->getPost('id')){
    				$base = base64_decode($this->request->getPost('id'));
    				if(preg_match("/^[a-zA-Z0-9\_]+$/", $base)){
    					$model = new CardsModel;
    					$Results = $model->where(['base' => $base, 'sellerid' => session()->get('suser_id')])->findAll();
    					if(count($Results) > 0){
    						if($Results['0']['refun'] == '1'){
    							$options = '<option selected value="1">Yes</option>
											<option value="0">No</option>';
    						}
    						else {
								$options = '<option value="1">Yes</option>
											<option selected value="0">No</option>';
    						}
    						$modalContent = '<form id="editform">
											<div class="row">
												<div class="form-group col-12">
		    										<label>Base</label>
		    										<input type="text" disabled class="form-control" value="'.$Results['0']['base'].'">
		    										<input type="hidden" name="base" id="base" value="'.$Results['0']['base'].'">
		    										<small class="text-danger refun"></small>
		    									</div>
		    									<div class="form-group col-12">
		    										<label>Refundable</label>
		    										<select class="form-control select2" id="refun" name="refun">
		    										    '.$options.'
		    										</select>
		    										<small class="text-danger refun"></small>
		    									</div>
		    									<div class="form-group col-12">
		    										<label>Update price for all cards in this base </label>
		    										<input type="number" name="price" id="price" class="form-control">
		    										<small class="text-danger price"></small>
		    									</div>
		    								</div>
		    								<script>
					    						$(\'select\').select2({
					    							width: "100%",
					    							dropdownParent: $("#bsModal")
					    					    });
					    					</script>
		    							</form>
		    				';
		    
		    				$modalheader = 'Edit the Base '.esc(ucfirst($Results[0]['base']));
		    				$response["modal"] = createModal($modalContent, 'fade', $modalheader, '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Save', 'functions' => 'data-api="updatebase"']);
    					}	
    					else {
    						$modalContent = 'An Error has been detected, please try again, Refer : E001';
							$modalheader = 'Error';
							$response["modal"] = createModal($modalContent, 'fade', $modalheader, '', 'modal-lg ', '1', '1', '1', '1', '0');
    					}
    				}
    				else {
    					$modalContent = 'An Error has been detected, please try again, Refer : E002';
						$modalheader = 'Error';
						$response["modal"] = createModal($modalContent, 'fade', $modalheader, '', 'modal-lg ', '1', '1', '1', '1', '0');
    				}
    			}
    			else {
    				$modalContent = 'An Error has been detected, please try again, Refer : E003';
					$modalheader = 'Error';
					$response["modal"] = createModal($modalContent, 'fade', $modalheader, '', 'modal-lg ', '1', '1', '1', '1', '0');
    			}

    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($response);
    			exit();
    		}
    		else {
    			header('location:'.base_url());
    			exit();
    		}
    	}
    	else {
    		echo "Nice try x)";
    		exit();

    	}
	}

	public function edit(){
        if($this->request->isAJAX()){
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			$ValidationRulls = [
    				'base' => [
	    				'label' => 'Base Name',
	    				'rules' => 'required|regex_match[/^[a-zA-Z0-9\_]{10,50}$/]',
	    				'errors' => [
	    					'required' => 'Invalid Base Name',
	    					'regex_match' => 'Invalid Base Name',
	    				]
	    			],
	    			'refun' => [
	    				'label' => 'Refundable',
	    				'rules' => 'required|regex_match[/^[0-1]$/]',
	    				'errors' => [
	    					'required' => 'Invalid Refund option',
	    					'regex_match' => 'Invalid Refund option',
	    				]
	    			],
	    			'price' => [
	    				'label' => 'Price',
	    				'rules' => 'permit_empty|regex_match[/^[0-9]+$/]',
	    				'errors' => [
	    					'required' => 'Invalid Base Price',
	    					'regex_match' => 'Invalid Base Price',
	    				]
	    			],
    			];

    			if(!$this->validate($ValidationRulls)){
    				$ErrorFields = $this->validator->getErrors();
    				$response["fieldslist"] = $ErrorFields;
    				$response["csrft"] = csrf_hash();
    				echo json_encode($response);
    				exit();
    			}
    			else {
	    			$base = $this->request->getPost('base');
	    			$price = $this->request->getPost('price');
	    			$refun = $this->request->getPost('refun');
    				$cardsModel = new CardsModel;
    				$GetProdBase = $cardsModel->where(['base' => $base, 'selled' => '0', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();
	    			if(count($GetProdBase) > 0){
	    				$ids = [];
	    				foreach($GetProdBase as $Prod){
	    					$ids[] = $Prod['id'];
	    				}
	    				$data = [];
	    				foreach($this->request->getPost() as $k => $v){
	    					if($v != ''){
	    						if($k != 'base'){
	    							$data[$k] = $v;	
	    						}
	    					}
	    				}
	    				$cardsModel->update($ids, $data);
	    				$response["modal"] = createModal('Base Updated', 'fade', 'Success', '', 'modal-lg ', '1', '1', '1', '1', '0');
	    				$response["csrft"] = csrf_hash();
	    				header('Content-Type: application/json');
	    				echo json_encode($response);
	    				exit();
	    			}
	    			else {
	    				$response["modal"] = createModal('Somethin went wrong', 'fade', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
	    				$response["csrft"] = csrf_hash();
	    				header('Content-Type: application/json');
	    				echo json_encode($response);
	    				exit();
	    			}
	    		}
    		}
    		else {
    			header('location:'.base_url());
    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			exit();
    		}
        }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function initdiscount(){
		if($this->request->isAJAX()){
			$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1' ){
    			if(null !== $this->request->getPost('id')){
    				$base = base64_decode($this->request->getPost('id'));
    				if(preg_match("/^[a-zA-Z0-9\_]+$/", $base)){
    					$model = new CardsModel;
    					$Results = $model->where(['base' => $base, 'sellerid' => session()->get('suser_id')])->findAll();
    					if(count($Results) > 0){
    						$modalContent = '<form id="editform">
											<div class="row">
												<div class="form-group col-12">
		    										<label>Base</label>
		    										<input type="text" disabled class="form-control" value="'.$Results['0']['base'].'">
		    										<input type="hidden" name="base" id="base" value="'.$Results['0']['base'].'">
		    										<small class="text-danger refun"></small>
		    									</div>
		    									<div class="form-group col-12">
		    										<label>Set discount percentage (%) for all cards in this base.</label>
		    										<input type="number" name="discount" id="discount" class="form-control">
		    										<small class="text-danger discount"></small>
		    									</div>
		    								</div>
		    							</form>
		    				';
		    
		    				$modalheader = 'Discount the Base '.esc(ucfirst($Results[0]['base']));
		    				$response["modal"] = createModal($modalContent, 'fade', $modalheader, '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Save', 'functions' => 'data-api="updatediscount"']);
    					}	
    					else {
    						$modalContent = 'An Error has been detected, please try again, Refer : E001';
							$modalheader = 'Error';
							$response["modal"] = createModal($modalContent, 'fade', $modalheader, '', 'modal-lg ', '1', '1', '1', '1', '0');
    					}
    				}
    				else {
    					$modalContent = 'An Error has been detected, please try again, Refer : E002';
						$modalheader = 'Error';
						$response["modal"] = createModal($modalContent, 'fade', $modalheader, '', 'modal-lg ', '1', '1', '1', '1', '0');
    				}
    			}
    			else {
    				$modalContent = 'An Error has been detected, please try again, Refer : E003';
					$modalheader = 'Error';
					$response["modal"] = createModal($modalContent, 'fade', $modalheader, '', 'modal-lg ', '1', '1', '1', '1', '0');
    			}

    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($response);
    			exit();
    		}
    		else {
    			header('location:'.base_url());
    			exit();
    		}
    	}
    	else {
    		echo "Nice try x)";
    		exit();

    	}
	}

	public function discount(){
        if($this->request->isAJAX()){
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			$ValidationRulls = [
    				'base' => [
	    				'label' => 'Base Name',
	    				'rules' => 'required|regex_match[/^[a-zA-Z0-9\_]{10,50}$/]',
	    				'errors' => [
	    					'required' => 'Invalid Base Name',
	    					'regex_match' => 'Invalid Base Name',
	    				]
	    			],
	    			'discount' => [
	    				'label' => 'Discount',
	    				'rules' => 'required|regex_match[/^(100|[1-9]?[0-9])$/]',
	    				'errors' => [
	    					'required' => 'Invalid Discount percentage',
	    					'regex_match' => 'Invalid Discount percentage',
	    				]
	    			],
    			];

    			if(!$this->validate($ValidationRulls)){
    				$ErrorFields = $this->validator->getErrors();
    				$response["fieldslist"] = $ErrorFields;
    				$response["csrft"] = csrf_hash();
    				echo json_encode($response);
    				exit();
    			}
    			else {
	    			$base = $this->request->getPost('base');
	    			$discount = $this->request->getPost('discount');
    				$cardsModel = new CardsModel;
    				$GetProdBase = $cardsModel->where(['base' => $base, 'selled' => '0', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();
	    			if(count($GetProdBase) > 0){
	    				$ids = [];
	    				$data = [];
	    				foreach($GetProdBase as $Prod){
	    					$calcdiscount = intval($Prod['price']) - (intval($Prod['price']) * intval($discount) /100);
	    					$cardsModel->update($Prod['id'], ['price' => $calcdiscount]);
	    				}
	    				
	    				$response["modal"] = createModal('Base Updated', 'fade', 'Success', '', 'modal-lg ', '1', '1', '1', '1', '0');
	    				$response["csrft"] = csrf_hash();
	    				header('Content-Type: application/json');
	    				echo json_encode($response);
	    				exit();
	    			}
	    			else {
	    				$response["modal"] = createModal('Somethin went wrong', 'fade', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
	    				$response["csrft"] = csrf_hash();
	    				header('Content-Type: application/json');
	    				echo json_encode($response);
	    				exit();
	    			}
	    		}
    		}
    		else {
    			header('location:'.base_url());
    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			exit();
    		}
        }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function initDelete(){
		if($this->request->isAJAX()){
			$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1' ){
    			if(null !== $this->request->getPost('id')){
    				$base = base64_decode($this->request->getPost('id'));
    				if(preg_match("/^[a-zA-Z0-9\_]+$/", $base)){
    					$model = new CardsModel;
    					$Results = $model->where(['base' => $base, 'sellerid' => session()->get('suser_id')])->findAll();
    					if(count($Results) > 0){
    						$modalContent = '<form id="editform">
											<div class="row">
												<div class="form-group col-12">
		    										<p class="text-warning">Warning</p>
		    										<p>Delete the base '.$Results['0']['base'].'?</p>
		    										<p>This will delete all Cards in this base.</p>
		    										<input type="hidden" name="base" id="base" value="'.$Results['0']['base'].'">
		    									</div>
		    								</div>
		    								<script>
					    						$(\'select\').select2({
					    							width: "100%",
					    							dropdownParent: $("#bsModal")
					    					    });
					    					</script>
		    							</form>
		    				';
		    
		    				$modalheader = 'Delete the Base '.esc(ucfirst($Results[0]['base']));
		    				$response["modal"] = createModal($modalContent, 'fade', $modalheader, '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Save', 'functions' => 'data-api="deletebase-'.base64_encode($Results['0']['base']).'"']);
    					}	
    					else {
    						$modalContent = 'An Error has been detected, please try again, Refer : E001';
							$modalheader = 'Error';
							$response["modal"] = createModal($modalContent, 'fade', $modalheader, '', 'modal-lg ', '1', '1', '1', '1', '0');
    					}
    				}
    				else {
    					$modalContent = 'An Error has been detected, please try again, Refer : E002';
						$modalheader = 'Error';
						$response["modal"] = createModal($modalContent, 'fade', $modalheader, '', 'modal-lg ', '1', '1', '1', '1', '0');
    				}
    			}
    			else {
    				$modalContent = 'An Error has been detected, please try again, Refer : E003';
					$modalheader = 'Error';
					$response["modal"] = createModal($modalContent, 'fade', $modalheader, '', 'modal-lg ', '1', '1', '1', '1', '0');
    			}

    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($response);
    			exit();
    		}
    		else {
    			header('location:'.base_url());
    			exit();
    		}
    	}
    	else {
    		echo "Nice try x)";
    		exit();

    	}
	}

	public function deletebase(){
        if($this->request->isAJAX()){
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			$ValidationRulls = [
    				'base' => [
	    				'label' => 'Base Name',
	    				'rules' => 'required|regex_match[/^[a-zA-Z0-9\_]{10,50}$/]',
	    				'errors' => [
	    					'required' => 'Invalid Base Name',
	    					'regex_match' => 'Invalid Base Name',
	    				]
	    			],
    			];

    			if(!$this->validate($ValidationRulls)){
    				$ErrorFields = $this->validator->getErrors();
    				$response["fieldslist"] = $ErrorFields;
    				$response["csrft"] = csrf_hash();
    				echo json_encode($response);
    				exit();
    			}
    			else {
	    			$base = $this->request->getPost('base');
    				$cardsModel = new CardsModel;
    				$GetProdBase = $cardsModel->where(['base' => $base, 'selled' => '0', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();
	    			if(count($GetProdBase) > 0){
	    				foreach($GetProdBase as $Prod){
	    					$cardsModel->delete($Prod['id']);
	    				}
	    				$response["modal"] = createModal('Base Deleted', 'fade', 'Success', '', 'modal-lg ', '1', '1', '1', '1', '0');
	    				$response["csrft"] = csrf_hash();
	    				header('Content-Type: application/json');
	    				echo json_encode($response);
	    				exit();
	    			}
	    			else {
	    				$response["modal"] = createModal('Somethin went wrong', 'fade', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
	    				$response["csrft"] = csrf_hash();
	    				header('Content-Type: application/json');
	    				echo json_encode($response);
	    				exit();
	    			}
	    		}
    		}
    		else {
    			header('location:'.base_url());
    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			exit();
    		}
        }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}
}

    			