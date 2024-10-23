<?php

namespace App\Controllers;
use App\Models\CardsModel;
use App\Models\UsersModel;
use App\Models\SectionsModel;
use App\Models\NotificationsModel;
use App\Models\PayementsModel;
use App\Models\SellerrequestsModel;
use App\Models\TiketsModel;
use App\Models\WithdrawrequestsModel;

class Admindashboard extends BaseController
{
	public function index(){
		if(session()->get("suser_groupe") == '9'){

			//Total depos
			$db      = \Config\Database::connect();
            $builder = $db->table('nowpayements');
			$builder->select('price_amount')->where(['intstatus' =>'1', 'payment_status' => 'finished']);
			$Results =  $builder->get();
			//$Results->getResult('array');
			
			$countresults = count($Results->getResult('array'));
			$mnarray = $Results->getResult('array');
			
			$arraysome = [];
			if($countresults > 0){
			    foreach($mnarray as $k => $v){
			        foreach($v as $ks => $vs){
    			        $arraysome[] = $vs;
    			    }
			    }
			}
			else{
			    $arraysome = [0];
			}
			//seller requests
			$modelSellersRequests = new SellerrequestsModel;
			$ResultsSellersRequests = $modelSellersRequests->where('status', '0')->orderby('date', 'asc')->findAll();
			$countresultsSellersRequests = count($ResultsSellersRequests);
			//Total Opend Tikets
			$modelTikets = new TiketsModel;
			$ResultsTikets = $modelTikets->where(['status'=> '0'])->findAll();
			$countresultsTikets = count($ResultsTikets);
			//Total sellers
		 	$modelUsers = new UsersModel;
	        $ResultsTotalSellers = $modelUsers->where(['groupe'=> 1, 'seller_balance !=' => '0'])->orderBy('seller_balance', 'asc')->limit(10)->findAll();    
	        //Withdraws Request
	        $modelWithdraws = new WithdrawrequestsModel;
			$ResultsWithdraws = $modelWithdraws->where(['status'=> '1'])->findAll();
			$countresultsWithdraws = count($ResultsWithdraws);
			//Secions
			$modelsections = new SectionsModel;
            $Resultssections = $modelsections->findAll();

            $totalRevearray = [];
            foreach($Resultssections as $k => $v){
            	$totalRevearray[] = $v['sectionrevenue'];
            }
			$data = [];
			$settings = fetchSettings();
			$mycart = getCart();
			$data["nbitemscart"] = $mycart[0];
			$data["cartInnerHtml"] = $mycart[1];
			$data["settings"] = $settings;
			$data["sectionName"] = "Admin Dashboard";
			$data["totdep"] = array_sum($arraysome);
			$data["totalrev"] = array_sum($totalRevearray);
			$data["sellerrequests"] = $countresultsSellersRequests;
			$data["tikets"] = $countresultsTikets;
			$data["withdraws"] = $countresultsWithdraws;
			$data['sections'] = $Resultssections;
			$data['topsellers'] = $ResultsTotalSellers;
			echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view('admindashboard');
            echo view("assets/footer");
            echo view("assets/scripts");	
		}
		else {
			header('location:'.base_url().'/login');
			exit();
		}
	}

	public function approveBase(){
		if(session()->get("suser_groupe") == '9'){
			$basename = $this->request->getPost('base');
			$cardsmodel = new CardsModel;
			$getAllcards = $cardsmodel->where(['base' => $basename, 'baseapproved' => '0'])->findAll();
			if(count($getAllcards) > 0){
				foreach ($getAllcards as $key => $value) {
					$data = [
						'baseapproved' => '1'
					];
					$cardsmodel->update($value['id'], $data);
				}
			}
			$dataNotif = [
				'subject' => 'Base '.$basename.' Accepted',
				'text' => 'Your base has been accepted & published.',
				'url' => base_url().'/cards',
				'userid' => $getAllcards[0]['sellerid']
			];
            $modelNotif = new NotificationsModel;
            $modelNotif->save($dataNotif);
            $updateUsersNotifiNB = [
                'notifications_nb' => '1'
            ];
            $usersModel = new UsersModel;
            $usersModel->update($getAllcards[0]["sellerid"], $updateUsersNotifiNB);
			$response['ok'] = '1';
			echo json_encode($response);
			exit();
		}
		else {
			header('location:'.base_url().'/login');
			exit();
		}
	}

	public function declineBaseInit(){
        if(session()->get('logedin') == '1'){
            if($this->request->isAJAX()){
                $basename = $this->request->getPost('base');
                $form = '
                    <form id="form">
                        <div class="form-row">
                            <div class="form-group mb-2 col-md-12">
                                <label>Please provide additional informations.<span class="text-danger"> *</span></label>
                                <div class="input-group mb-3 input-group-sm input-warning">
                                    <textarea class="form-control" name="additionals" id="addtionals" style="min-height:150px"></textarea>
                                </div>
                            </div>
                        </div>
                    </form> 
                ';
                $modalContent = $form;
				$response["modal"] = createModal($modalContent, 'fade  ', 'Decline Base', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="declinebase-'.$basename.'"']);
                    
            }
            else {
                echo 'Nice try ;)';
            }
        }
        else {
            $modalContent = '<p>Object not found. E004</p>';
			$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
        }
        header('Content-Type: application/json');
        echo json_encode($response);  
        exit();
    }

	public function declineBase(){
		if(session()->get("suser_groupe") == '9'){
			$basename = $this->request->getPost('base');
			$additionals = $this->request->getPost('additionals');
			$cardsmodel = new CardsModel;
			$getAllcards = $cardsmodel->where(['base' => $basename, 'baseapproved' => '0'])->findAll();
			if(count($getAllcards) > 0){
				foreach ($getAllcards as $key => $value) {
					$data = [
						'baseapproved' => '2'
					];
					$cardsmodel->update($value['id'], $data);
				}
			}
			$dataNotif = [
				'subject' => 'Base '.$basename.' Declined',
				'text' => $additionals,
				'url' => base_url().'/cards',
				'userid' => $getAllcards[0]['sellerid']
			];
            $modelNotif = new NotificationsModel;
            $modelNotif->save($dataNotif);
            $userModel = new UsersModel;
            $updateUsersNotifiNB = [
                'notifications_nb' => '1'
            ];
            $usersModel = new UsersModel;
            $usersModel->update($getAllcards[0]["sellerid"], $updateUsersNotifiNB);
			$response['ok'] = '1';
			echo json_encode($response);
			exit();
		}
		else {
			header('location:'.base_url().'/login');
			exit();
		}
	}

	public function fetchTable(){
		if($this->request->isAJAX()){
    		$output = $output = array('data' => array());
			if(session()->get('suser_groupe') != '9'){
				header('location:'.base_url());
				exit();
			}
			else {
				$cardsModel = new CardsModel;
				$Results = $cardsModel->where(['baseapproved' => '0', 'selled' => '0'])->findAll();
				if(count($Results) > 0){
					$Bases = [];
					$Data = [];
					foreach($Results as $key => $value) {
						$Bases[] = $value['base'];
					}
					$ucBases = array_unique($Bases);
					foreach($ucBases as $k => $v){
						$getContains = $cardsModel->where(['base' => $v, 'baseapproved' => '0', 'selled' => '0'])->findAll();
						if(count($getContains) > 0){
							$Data[] = [
								'basename' => $v , 
								'contains' => count($getContains), 
							];
						}
						
					}
					foreach($Data as $c => $d){
							$tools = '<div class="btn-groupe text-center">
							<a href="'.base_url().'/baselists/'.$d['basename'].'" class="btn btn-success btn-sm">List <span class="fa fa-eye"></span></a>
							<button type="button" data-api="approvebase-'.$d['basename'].'" class="btn btn-info btn-sm">Approve<span class="bx bx-check"></span></button>
							<button type="button" data-api="declinebaseinit-'.$d['basename'].'" class="btn btn-danger btn-sm">Decline<span class="bx bx-trash"></span></button>
							</div>';

						$output['data'][] = array(
    		        		esc($d['basename']),
    		        		esc($d['contains'].' Cards'),
    		        		$Results[0]['sellerusername'],	
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
}