<?php

namespace App\Controllers;
use App\Models\NotificationsModel;
use App\Models\UsersModel;
use App\Models\ReportsModel;
use App\Models\ReportsDetailsModel;
use App\Models\MyitemsModel;
use App\Models\CardsModel;

class Reports extends BaseController {
    protected $db;
    public function __construct(){
        $this->db = \Config\Database::connect();
    }
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
			$data["sectionName"] = "Reports";
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("reports");
            echo view("assets/footer");
            echo view("assets/scripts");
            echo view("assets/reports");
		}
		else {
			header('location:'.base_url().'/login');
			exit();
		}
	}

    public function fetchTableOpen(){
        if($this->request->isAJAX()){
            $output = $output = array('data' => array());
            if(session()->get("logedin") == '1'){
                $model = new ReportsModel;
                if(session()->get('suser_groupe') == '9') {
                    $Results =  $this->db->table('reports')->where("`status`='0'")->get();
                }
                else {
                    $Results =  $this->db->table('reports')->where("`status`='0' AND `buyerid`='".session()->get("suser_id")."'")->orWhere("`status`='0' AND `sellerid`='".session()->get("suser_id")."'")->get();
                }
                $countresults = count($Results->getResultArray());
                if($countresults > 0){
                    foreach ($Results->getResultArray() as $value) {
                        $id = '#'.$value["id"];
                        $subject = $value["message"];
                        $status = '<span class="badge badge-sm badge-primary">Open</span>';
                        $ndate = new \DateTime($value["reportdate"]);
                        $createdat = $ndate->format('d/m/Y H:i:s');
                        $actions = '<a class="btn btn-primary btn-xxs" href="'.base_url().'/reports/details/'.$value["id"].'">View <i class="mdi mdi-eye-plus-outline"></i></a>';
                        if(session()->get('suser_groupe') == '9'){
                            $output['data'][] = array(
                                $id,
                                $subject,
                                $status,
                                $createdat,
                                $actions
                            );
                        }
                        else {
                            $output['data'][] = array(
                                $id,
                                $subject,
                                $status,
                                $createdat,
                                $actions
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

    public function fetchTableReplace(){
        if($this->request->isAJAX()){
            $output = $output = array('data' => array());
            if(session()->get("logedin") == '1'){
                $model = new ReportsModel;
                if(session()->get('suser_groupe') == '9') {
                    $Results =  $this->db->table('reports')->where("`status`='2'")->get();
                }
                else {
                    $Results =  $this->db->table('reports')->where("`status`='2' AND `buyerid`='".session()->get("suser_id")."'")->orWhere("`status`='2' AND `sellerid`='".session()->get("suser_id")."'")->get();
                }
                $countresults = count($Results->getResultArray());
                if($countresults > 0){
                    foreach ($Results->getResultArray() as $value) {
                        $id = '#'.$value["id"];
                        $subject = $value["message"];
                        $status = '<span class="badge badge-sm badge-success">Replaced</span>';
                        $ndate = new \DateTime($value["reportdate"]);
                        $createdat = $ndate->format('d/m/Y H:i:s');
                        $actions = '<a class="btn btn-primary btn-xxs" href="'.base_url().'disputes/details/'.$value["id"].'">View <i class="mdi mdi-eye-plus-outline"></i></a>';
                        if(session()->get('suser_groupe') == '9'){
                            $output['data'][] = array(
                                $id,
                                $subject,
                                $status,
                                $createdat,
                                $actions
                            );
                        }
                        else {
                            $output['data'][] = array(
                                $id,
                                $subject,
                                $status,
                                $createdat,
                                $actions
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

    public function fetchTableRefunds(){
        if($this->request->isAJAX()){
            $output = $output = array('data' => array());
            if(session()->get("logedin") == '1'){
                $model = new ReportsModel;
                if(session()->get('suser_groupe') == '9') {
                    $Results =  $this->db->table('reports')->where("`status`='1'")->get();
                }
                else {
                    $Results =  $this->db->table('reports')->where("`status`='1' AND `buyerid`='".session()->get("suser_id")."'")->orWhere("`status`='1' AND `sellerid`='".session()->get("suser_id")."'")->get();
                }
                $countresults = count($Results->getResultArray());
                if($countresults > 0){
                    foreach ($Results->getResultArray() as $value) {
                        $id = '#'.$value["id"];
                        $subject = $value["message"];
                        $status = '<span class="badge badge-sm badge-success">Refunded</span>';
                        $ndate = new \DateTime($value["reportdate"]);
                        $createdat = $ndate->format('d/m/Y H:i:s');
                        $actions = '<a class="btn btn-primary btn-xxs" href="'.base_url().'disputes/details/'.$value["id"].'">View <i class="mdi mdi-eye-plus-outline"></i></a>';
                        if(session()->get('suser_groupe') == '9'){
                            $output['data'][] = array(
                                $id,
                                $subject,
                                $status,
                                $createdat,
                                $actions
                            );
                        }
                        else {
                            $output['data'][] = array(
                                $id,
                                $subject,
                                $status,
                                $createdat,
                                $actions
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

    public function fetchTableCloses(){
        if($this->request->isAJAX()){
            $output = $output = array('data' => array());
            if(session()->get("logedin") == '1'){
                $model = new ReportsModel;
                if(session()->get('suser_groupe') == '9') {
                    $Results =  $this->db->table('reports')->where("`status`='3'")->get();
                }
                else {
                    $Results =  $this->db->table('reports')->where("`status`='3' AND `buyerid`='".session()->get("suser_id")."'")->orWhere("`status`='3' AND `sellerid`='".session()->get("suser_id")."'")->get();
                }
                $countresults = count($Results->getResultArray());
                if($countresults > 0){
                    foreach ($Results->getResultArray() as $value) {
                        $id = '#'.$value["id"];
                        $subject = $value["message"];
                        $status = '<span class="badge badge-sm badge-secondary">Closed</span>';
                        $ndate = new \DateTime($value["reportdate"]);
                        $createdat = $ndate->format('d/m/Y H:i:s');
                        $actions = '<a class="btn btn-primary btn-xxs" href="'.base_url().'disputes/details/'.$value["id"].'">View <i class="mdi mdi-eye-plus-outline"></i></a>';
                        if(session()->get('suser_groupe') == '9'){
                            $output['data'][] = array(
                                $id,
                                $subject,
                                $status,
                                $createdat,
                                $actions
                            );
                        }
                        else {
                            $output['data'][] = array(
                                $id,
                                $subject,
                                $status,
                                $createdat,
                                $actions
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

    public function initReport(){
        if(session()->get('logedin') == '1'){
            if($this->request->isAJAX()){
                $userid = session()->get('suser_id');
                $usersModel = new UsersModel;
                $getUserinfo = $usersModel->where('id', $userid)->findAll();
                $id = $this->request->getPost('id');
                if(count($getUserinfo) > 0){
                    if(null !== $id && preg_match("/^[0-9]{1,30}/", $id)){
                        $itemsModel = new MyitemsModel;
                        $getItemInfos = $itemsModel->where(['id' => $id, 'userid' => session()->get('suser_id'), 'reported' => '0'])->findAll();
                        if(count($getItemInfos) === 1){
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
							$response["modal"] = createModal($modalContent, 'fade  ', 'Report', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="confirmdispute-'.$getItemInfos[0]['id'].'"']);
                        }
                        else {
                            $modalContent = '<p>Object not found. E001</p>';
							$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
                        }
                    }
                    else {
						$modalContent = '<p>Object not found. E002</p>';
						$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");

                    }
                }
                else {
					$modalContent = '<p>Object not found. E003</p>';
					$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");

                }
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

    public function Report(){
    	
        if(session()->get('logedin') == '1'){
            if($this->request->isAJAX()){
                $userid = session()->get('suser_id');
                $usersModel = new UsersModel;
                $getUserinfo = $usersModel->where('id', $userid)->findAll();
                if(count($getUserinfo) > 0){
                    $ValidationRulls = [
                        'id' => [
                            'label' => 'Item ID',
                            'rules'  => 'required|regex_match[/^[a-zA-Z0-9\s\p{P}]+$/u]',
                            'errors' => [
                                'required' => 'Invalid Item ID.',
                                'regex_match' => 'Invalid Item ID.',
                            ]
                        ],
                        'additionals' => [
                            'label' => 'Additional Informations',
                            'rules'  => 'required|regex_match[/^[a-zA-Z0-9\s\p{P}]+$/u]',
                            'errors' => [
                                'required' => 'Invalid Additional Informations.',
                                'regex_match' => 'Invalid Additional Informations.',
                            ]
                        ],
                    ];
                    if(!$this->validate($ValidationRulls)){
                        $ErrorFields = $this->validator->getErrors();
                        $ErrorsText = [];
                        $keys = [];
                        foreach($ErrorFields as $key => $value){
                            $ErrorsText[] = $value;
                            $keys[] = $key;
                        }
                        $response["content"] =  array_combine($keys, $ErrorsText);
                        $response["csrft"] = csrf_hash();
                        header('Content-Type: application/json');
                        echo json_encode($response);
                        exit(); 
                    }
                    else {
                        $itemsModel = new MyitemsModel;
                        $settings = fetchSettings();
                        $id = $this->request->getPost('id');
                        $getItemInfos = $itemsModel->where(['id' => $id, 'userid' => session()->get('suser_id'), 'reported' => '0'])->findAll();
                        if(count($getItemInfos) === 1){
                            $now = time() - strtotime($getItemInfos[0]["date"]);
                            if($settings[0]['ccopenreport'] == '1'){
                                $MessageDetails = nl2br(esc($this->request->getPost('additionals')));
                                
                                $MessageDetails .= '<p class="mt-2 mb-1">Item Details:</p>
                                					<p>'.$getItemInfos[0]["details"].'</p>';
                                $dataReport = [
                                    'itemid' => $getItemInfos[0]["prodid"],
                                    'myitemid' => $id,
                                    'itemprice' => $getItemInfos[0]["price"],
                                    'sellerid' => $getItemInfos[0]["sellerid"],
                                    'buyerusername' => session()->get("suser_username"),
                                    'buyerid' => session()->get("suser_id"),
                                    'fastdetails' => $getItemInfos[0]["details"],
                                    'buydate' => $getItemInfos[0]["date"],
                                    'request' => 3,
                                    'message' => strtoupper($getItemInfos[0]["type"]).' Item Reporte, Item ID #'.$getItemInfos[0]["prodid"],
                                    'rtype' => $getItemInfos[0]["type"]
                                ];
                                $reportsModel = new ReportsModel;
                                $reportsModel->insert($dataReport);
                                $lastInsertReport = $reportsModel->getInsertID();
                                $dataReportDetails = [
                                    'reportid' => $lastInsertReport,
                                    'message' => $MessageDetails,
                                    'userid' => session()->get("suser_id"),
                                    'username' => session()->get("suser_username"),
                                    'user_groupe' => session()->get("suser_groupe"),
                                ];
                                $reportDetailsModel = new ReportsDetailsModel;
                                $reportDetailsModel->insert($dataReportDetails);
                                $dataNotif = [
                                    'subject' => 'New Report ',
                                    'text' => strtoupper($getItemInfos[0]["type"]).' Item Reporte, Item ID #'.$getItemInfos[0]["prodid"],
                                    'url' => base_url().'/reports/details/'.$lastInsertReport,
                                    'icon' => 'mdi-alert',
                                    'type' => 0,
                                    'userid' => $getItemInfos[0]["sellerid"]
                                ];
                                $modelNotif = new NotificationsModel;
                                $modelNotif->save($dataNotif);
                                $updateUsersNotifiNB = [
                                    'notifications_nb' => '1'
                                ];
                                $usersModel->update([$getItemInfos[0]["sellerid"], session()->get('suser_id')], $updateUsersNotifiNB);

                                $updateMyitem = [
                                    'reported' => '1'
                                ];
                                $itemsModel->update($getItemInfos[0]['id'], $updateMyitem);
                                $form = '
	                                <p class="card-text"><strong>Item reported successfully.</strong></p>
                                    <p class="card-text">Find all your dispute in <a href="'.base_url().'/reports" class="text-danger">My Disputes Section</a></p>
                                    <script>setTimeout(function(){window.location.href = "'.base_url().'/reports/details/'.$lastInsertReport.'"},1000)</script>
	                            ';
	                            $modalContent = $form;
								$response["modal"] = createModal($modalContent, 'fade  ', 'Report', 'text-primary', 'modal-lg', "1", "1", "1", "1", "0");
                            }
                            else {
                                $modalContent = '<p>Object not found. E001</p>';
								$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
                            }
                        }
                        else {
                            $modalContent = '<p>Object not found. E002</p>';
							$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
                        }
                    }
                }
                else {
                    $modalContent = '<p>Object not found. E003</p>';
					$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
                }
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

    public function ManuelleRefund(){
        $response = array();
        if(session()->get('logedin') == '1' && session()->get('suser_groupe') == '9' || session()->get('logedin') == '1' && session()->get('suser_groupe') == '4' || session()->get('logedin') == '1' && session()->get('suser_groupe') == '1' ){
            if($this->request->isAJAX()){
                $id = $this->request->getPost('id');
                if(null !== $id && preg_match("/^[0-9]{1,30}/", $id)){
                    $reportsModel = new ReportsModel;
                    $reportDetailsModel = new ReportsDetailsModel;
                    $getReport = $reportsModel->where('id', $id)->findAll(1);
                    if(count($getReport) > 0 && $getReport[0]['status'] == '0'){
                        $reportType = $getReport[0]['rtype'];
                        $itemId = $getReport[0]['itemid'];
                        $itemprice = $getReport[0]['itemprice'];
                        $sellerid = $getReport[0]['sellerid'];
                        $buyerid = $getReport[0]['buyerid'];
                        $buyerusername = $getReport[0]['buyerusername'];
                        $myitemid = $getReport[0]['myitemid'];
                        $usersModel = new UsersModel;
                        if(session()->get('suser_groupe') == '9' || session()->get('suser_id') == $sellerid){
                            switch ($reportType) {
                                case 'Credit Card':
                                    $model = new CardsModel;
                                    $getProductInfos = $model->where('id', $itemId)->findAll(1);
                                    if(count($getProductInfos) > 0){
                                        $getUserBalance = $usersModel->where('id' , $buyerid)->findAll(1);
                                        if(count($getUserBalance)> 0){
                                            $getSellerBalance = $usersModel->where('id' , $sellerid)->findAll(1);
                                            if(count($getSellerBalance) > 0){
                                                $newBuyerBalance = ['balance' => $getUserBalance[0]['balance']+$getProductInfos[0]['price']];
                                                $usersModel->update($getUserBalance[0]['id'] , $newBuyerBalance);
                                                $newSellerBalance = ['seller_balance' => $getSellerBalance[0]['seller_balance']-$getProductInfos[0]['price']];
                                                $usersModel->update($getSellerBalance[0]['id'] , $newSellerBalance);

                                                //$allsectionsModel = new SectionsModel;
                                                //$getSections = $allsectionsModel->where('sectioname','Smtp')->findAll();
                                                //$newNbsectionRevenue = $getSections[0]["sectionrevenue"] - $getProductInfos[0]['price'];
                                                //$dataUpdateSection = [
                                                //    'sectionrevenue' => $newNbsectionRevenue
                                                //];
                                                //$allsectionsModel->update($getSections[0]["id"], $dataUpdateSection);


                                                $error = 'na';
                                            }
                                            else {
                                                $error = 'Error: E001'; 
                                            }  
                                        }
                                        else {
                                            $error = 'Error: E002';
                                        } 
                                    }
                                    else {
                                        $error = 'Error: E003';
                                    }
                                break;
                                case 'Cpanel':
                                    $model = new CpanelModel;
                                    $getProductInfos = $model->where('id', $itemId)->findAll(1);
                                    if(count($getProductInfos) > 0){
                                        $getUserBalance = $usersModel->where('id' , $buyerid)->findAll(1);
                                        if(count($getUserBalance)> 0){
                                            $getSellerBalance = $usersModel->where('id' , $sellerid)->findAll(1);
                                            if(count($getSellerBalance) > 0){
                                                $newBuyerBalance = ['balance' => $getUserBalance[0]['balance']+$getProductInfos[0]['price']];
                                                $usersModel->update($getUserBalance[0]['id'] , $newBuyerBalance);
                                                $newSellerBalance = ['balance_inhold' => $getSellerBalance[0]['balance_inhold']-$getProductInfos[0]['price']];
                                                $usersModel->update($getSellerBalance[0]['id'] , $newSellerBalance);

                                                $allsectionsModel = new SectionsModel;
                                                $getSections = $allsectionsModel->where('sectioname','Cpanel')->findAll();
                                                $newNbsectionRevenue = $getSections[0]["sectionrevenue"] - $getProductInfos[0]['price'];
                                                $dataUpdateSection = [
                                                    'sectionrevenue' => $newNbsectionRevenue
                                                ];
                                                $allsectionsModel->update($getSections[0]["id"], $dataUpdateSection);

                                                $error = 'na';
                                            }
                                            else {
                                                $error = 'Error: E001'; 
                                            }  
                                        }
                                        else {
                                            $error = 'Error: E002';
                                        } 
                                    }
                                    else {
                                        $error = 'Error: E003';
                                    }
                                break;
                                case 'Rdp':
                                    $model = new RdpModel;
                                    $getProductInfos = $model->where('id', $itemId)->findAll(1);
                                    if(count($getProductInfos) > 0){
                                        $getUserBalance = $usersModel->where('id' , $buyerid)->findAll(1);
                                        if(count($getUserBalance)> 0){
                                            $getSellerBalance = $usersModel->where('id' , $sellerid)->findAll(1);
                                            if(count($getSellerBalance) > 0){
                                                $newBuyerBalance = ['balance' => $getUserBalance[0]['balance']+$getProductInfos[0]['price']];
                                                $usersModel->update($getUserBalance[0]['id'] , $newBuyerBalance);
                                                $newSellerBalance = ['balance_inhold' => $getSellerBalance[0]['balance_inhold']-$getProductInfos[0]['price']];
                                                $usersModel->update($getSellerBalance[0]['id'] , $newSellerBalance);

                                                $allsectionsModel = new SectionsModel;
                                                $getSections = $allsectionsModel->where('sectioname','Rdp')->findAll();
                                                $newNbsectionRevenue = $getSections[0]["sectionrevenue"] - $getProductInfos[0]['price'];
                                                $dataUpdateSection = [
                                                    'sectionrevenue' => $newNbsectionRevenue
                                                ];
                                                $allsectionsModel->update($getSections[0]["id"], $dataUpdateSection);

                                                $error = 'na';
                                            }
                                            else {
                                                $error = 'Error: E001'; 
                                            }  
                                        }
                                        else {
                                            $error = 'Error: E002';
                                        } 
                                    }
                                    else {
                                        $error = 'Error: E003';
                                    }
                                break;
                                case 'Ssh':
                                    $model = new SshModel;
                                    $getProductInfos = $model->where('id', $itemId)->findAll(1);
                                    if(count($getProductInfos) > 0){
                                        $getUserBalance = $usersModel->where('id' , $buyerid)->findAll(1);
                                        if(count($getUserBalance)> 0){
                                            $getSellerBalance = $usersModel->where('id' , $sellerid)->findAll(1);
                                            if(count($getSellerBalance) > 0){
                                                $newBuyerBalance = ['balance' => $getUserBalance[0]['balance']+$getProductInfos[0]['price']];
                                                $usersModel->update($getUserBalance[0]['id'] , $newBuyerBalance);
                                                $newSellerBalance = ['balance_inhold' => $getSellerBalance[0]['balance_inhold']-$getProductInfos[0]['price']];
                                                $usersModel->update($getSellerBalance[0]['id'] , $newSellerBalance);

                                                $allsectionsModel = new SectionsModel;
                                                $getSections = $allsectionsModel->where('sectioname','Ssh')->findAll();
                                                $newNbsectionRevenue = $getSections[0]["sectionrevenue"] - $getProductInfos[0]['price'];
                                                $dataUpdateSection = [
                                                    'sectionrevenue' => $newNbsectionRevenue
                                                ];
                                                $allsectionsModel->update($getSections[0]["id"], $dataUpdateSection);

                                                $error = 'na';
                                            }
                                            else {
                                                $error = 'Error: E001'; 
                                            }  
                                        }
                                        else {
                                            $error = 'Error: E002';
                                        } 
                                    }
                                    else {
                                        $error = 'Error: E003';
                                    }
                                break;
                                case 'Shell':
                                    $model = new ShellModel;
                                    $getProductInfos = $model->where('id', $itemId)->findAll(1);
                                    if(count($getProductInfos) > 0){
                                        $getUserBalance = $usersModel->where('id' , $buyerid)->findAll(1);
                                        if(count($getUserBalance)> 0){
                                            $getSellerBalance = $usersModel->where('id' , $sellerid)->findAll(1);
                                            if(count($getSellerBalance) > 0){
                                                $newBuyerBalance = ['balance' => $getUserBalance[0]['balance']+$getProductInfos[0]['price']];
                                                $usersModel->update($getUserBalance[0]['id'] , $newBuyerBalance);
                                                $newSellerBalance = ['balance_inhold' => $getSellerBalance[0]['balance_inhold']-$getProductInfos[0]['price']];
                                                $usersModel->update($getSellerBalance[0]['id'] , $newSellerBalance);

                                                $allsectionsModel = new SectionsModel;
                                                $getSections = $allsectionsModel->where('sectioname','Shell')->findAll();
                                                $newNbsectionRevenue = $getSections[0]["sectionrevenue"] - $getProductInfos[0]['price'];
                                                $dataUpdateSection = [
                                                    'sectionrevenue' => $newNbsectionRevenue
                                                ];
                                                $allsectionsModel->update($getSections[0]["id"], $dataUpdateSection);

                                                $error = 'na';
                                            }
                                            else {
                                                $error = 'Error: E001'; 
                                            }  
                                        }
                                        else {
                                            $error = 'Error: E002';
                                        } 
                                    }
                                    else {
                                        $error = 'Error: E003';
                                    }
                                break;
                                case 'Mailer':
                                    $model = new MailerModel;
                                    $getProductInfos = $model->where('id', $itemId)->findAll(1);
                                    if(count($getProductInfos) > 0){
                                        $getUserBalance = $usersModel->where('id' , $buyerid)->findAll(1);
                                        if(count($getUserBalance)> 0){
                                            $getSellerBalance = $usersModel->where('id' , $sellerid)->findAll(1);
                                            if(count($getSellerBalance) > 0){
                                                $newBuyerBalance = ['balance' => $getUserBalance[0]['balance']+$getProductInfos[0]['price']];
                                                $usersModel->update($getUserBalance[0]['id'] , $newBuyerBalance);
                                                $newSellerBalance = ['balance_inhold' => $getSellerBalance[0]['balance_inhold']-$getProductInfos[0]['price']];
                                                $usersModel->update($getSellerBalance[0]['id'] , $newSellerBalance);

                                                $allsectionsModel = new SectionsModel;
                                                $getSections = $allsectionsModel->where('sectioname','Mailer')->findAll();
                                                $newNbsectionRevenue = $getSections[0]["sectionrevenue"] - $getProductInfos[0]['price'];
                                                $dataUpdateSection = [
                                                    'sectionrevenue' => $newNbsectionRevenue
                                                ];
                                                $allsectionsModel->update($getSections[0]["id"], $dataUpdateSection);

                                                $error = 'na';
                                            }
                                            else {
                                                $error = 'Error: E001'; 
                                            }  
                                        }
                                        else {
                                            $error = 'Error: E002';
                                        } 
                                    }
                                    else {
                                        $error = 'Error: E003';
                                    }
                                break;
                                case 'Webmail':
                                    $model = new WebmailModel;
                                    $getProductInfos = $model->where('id', $itemId)->findAll(1);
                                    if(count($getProductInfos) > 0){
                                        $getUserBalance = $usersModel->where('id' , $buyerid)->findAll(1);
                                        if(count($getUserBalance)> 0){
                                            $getSellerBalance = $usersModel->where('id' , $sellerid)->findAll(1);
                                            if(count($getSellerBalance) > 0){
                                                $newBuyerBalance = ['balance' => $getUserBalance[0]['balance']+$getProductInfos[0]['price']];
                                                $usersModel->update($getUserBalance[0]['id'] , $newBuyerBalance);
                                                $newSellerBalance = ['balance_inhold' => $getSellerBalance[0]['balance_inhold']-$getProductInfos[0]['price']];
                                                $usersModel->update($getSellerBalance[0]['id'] , $newSellerBalance);

                                                $allsectionsModel = new SectionsModel;
                                                $getSections = $allsectionsModel->where('sectioname','Mailer')->findAll();
                                                $newNbsectionRevenue = $getSections[0]["sectionrevenue"] - $getProductInfos[0]['price'];
                                                $dataUpdateSection = [
                                                    'sectionrevenue' => $newNbsectionRevenue
                                                ];
                                                $allsectionsModel->update($getSections[0]["id"], $dataUpdateSection);

                                                $error = 'na';
                                            }
                                            else {
                                                $error = 'Error: E001'; 
                                            }  
                                        }
                                        else {
                                            $error = 'Error: E002';
                                        } 
                                    }
                                    else {
                                        $error = 'Error: E003';
                                    }
                                break;
                            }
                            if($error === 'na'){
                                $myItemsModel = new MyitemsModel;
                                //$myItemsModel->delete($myitemid);
                                $type = ucfirst(esc($reportType));
                                $dataNotif = [
                                    'subject' => 'Item Refunded',
                                    'text' => strtoupper($type).' ID #'.$itemId.' Refunded<span class="mdi mdi-check"></span>',
                                    'url' => base_url().'myorders',
                                    'icon' => 'mdi-backburger',
                                    'type' => 0,
                                    'userid' => $buyerid,
                                ];
                                $modelNotif = new NotificationsModel;
                                $modelNotif->save($dataNotif);
                                $updateReplaceNotifNB = [
                                    'notifications_nb' => '1'
                                ];
                                $usersModel->update($buyerid,$updateReplaceNotifNB);

                                $reportStatusData = [
                                    'status' => '1'
                                ];
                                $reportsModel->update($id, $reportStatusData);
                                $dataReportDetails = [
                                    'reportid' => $id,
                                    'message' => '<b class="text-danger">Item Refunded</b><br/>Refunded Amount $'.$getProductInfos[0]['price'],
                                    'userid' => $buyerid,
                                    'username' => 'System',
                                    'user_groupe' => '9',
                                ];
                                $reportDetailsModel->insert($dataReportDetails);
                                $response["message"] = '<p class="text-danger">Item Refunded</p><p>Refunded Amount $'.$getProductInfos[0]['price'].'</p>';
                                $response["date"] = date('Y/m/d H:i:s');
                                $response["error"] = '0';
                                $response["username"] = 'System';
                                $response["statustext"] = '<span class="badge badge-success px-2 py-2">ITEM REFUNDED <i class="mdi mdi-check"></i></span>';
                                
                                $response['sellerbalance'] = '$'.number_format($getSellerBalance[0]['seller_balance']-$getProductInfos[0]['price'], 2, '.', '');
                                
                            }
                            else {
                                $modalPar = [
                                    'close' => '1',
                                    'mtitle' => 'Error',
                                    'content' => '<div class="alert alert-danger left-icon-big fade show">
                                                <div class="media">
                                                    <div class="alert-left-icon-big">
                                                        <span><i class="mdi mdi-alert"></i></span>
                                                    </div>
                                                    <div class="media-body">
                                                        <p class="card-text"><strong>Error : <span class="text-danger">'.$error.'</span></strong></p>
                                                    </div>
                                                </div>
                                            </div>',
                                ];
                                $response["modal"] = createModal($modalPar); 
                            }
                        }
                        else {
                            $modalPar = [
                                'close' => '1',
                                'mtitle' => 'Error',
                                'content' => '<div class="alert alert-danger left-icon-big fade show">
                                            <div class="media">
                                                <div class="alert-left-icon-big">
                                                    <span><i class="mdi mdi-alert"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <p class="card-text"><strong>Error : <span class="text-danger">E:003</span></strong></p>
                                                </div>
                                            </div>
                                        </div>',
                            ];
                            $response["modal"] = createModal($modalPar); 
                        }
                    }
                    else {
                        $modalPar = [
                            'close' => '1',
                            'mtitle' => 'Error',
                            'content' => '<div class="alert alert-danger left-icon-big fade show">
                                        <div class="media">
                                            <div class="alert-left-icon-big">
                                                <span><i class="mdi mdi-alert"></i></span>
                                            </div>
                                            <div class="media-body">
                                                <p class="card-text"><strong>Error : <span class="text-danger">E:005</span></strong></p>
                                            </div>
                                        </div>
                                    </div>',
                        ];
                        $response["modal"] = createModal($modalPar);
                    }
                }
                else {
                    $modalPar = [
                        'close' => '1',
                        'mtitle' => 'Error',
                        'content' => '<div class="alert alert-danger left-icon-big fade show">
                                    <div class="media">
                                        <div class="alert-left-icon-big">
                                            <span><i class="mdi mdi-alert"></i></span>
                                        </div>
                                        <div class="media-body">
                                            <p class="card-text"><strong>Error : <span class="text-danger">E:003</span></strong></p>
                                        </div>
                                    </div>
                                </div>',
                    ];
                    $response["modal"] = createModal($modalPar);
                }
                
            }
            else {
                echo 'Nice try ;)';
            }  
        }
        else {
            $modalPar = [
                'close' => '1',
                'mtitle' => 'Error',
                'content' => '<div class="alert alert-danger left-icon-big fade show">
                            <div class="media">
                                <div class="alert-left-icon-big">
                                    <span><i class="mdi mdi-alert"></i></span>
                                </div>
                                <div class="media-body">
                                    <h5 class="mt-1 mb-2"><strong>Error : <span class="text-danger">E:004</spa></h5>
                                    <p class="card-text">Session Timeou, <a href="'.base_url().'">Please Login</a>.</p>
                                </div>
                            </div>
                        </div>',
            ];
            $response["modal"] = createModal($modalPar);
        }
        header('Content-Type: application/json');
        echo json_encode($response);  
        exit();
    }

    public function ManuelleClose(){
        $response = array();
        if(session()->get('logedin') == '1' && session()->get('suser_groupe') == '9' || session()->get('logedin') == '1' && session()->get('suser_groupe') == '4' || session()->get('logedin') == '1' && session()->get('suser_groupe') == '0' ){
            if($this->request->isAJAX()){
                $id = $this->request->getPost('id');
                if(null !== $id && preg_match("/^[0-9]{1,30}/", $id)){
                    $reportsModel = new ReportsModel;
                    $reportDetailsModel = new ReportsDetailsModel;
                    $getReport = $reportsModel->where('id', $id)->findAll(1);
                    if(count($getReport) > 0 && $getReport[0]['status'] == '0'){
                        $reportType = $getReport[0]['rtype'];
                        $itemId = $getReport[0]['itemid'];
                        $itemprice = $getReport[0]['itemprice'];
                        $sellerid = $getReport[0]['sellerid'];
                        $buyerid = $getReport[0]['buyerid'];
                        $buyerusername = $getReport[0]['buyerusername'];
                        $myitemid = $getReport[0]['myitemid'];
                        $usersModel = new UsersModel;
                        if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '4' || session()->get('suser_id') == $buyerid){
                            $type = ucfirst(esc($reportType));
                            $dataNotif = [
                                'subject' => 'Report Closed',
                                'text' => 'Report ID #'.$id.' closed',
                                'url' => base_url().'disputes',
                                'icon' => 'mdi-close',
                                'type' => 0,
                                'userid' => $buyerid,
                            ];
                            $modelNotif = new NotificationsModel;
                            $modelNotif->save($dataNotif);
                            $updateReplaceNotifNB = [
                                'notifications_nb' => '1'
                            ];
                            $usersModel->update($buyerid,$updateReplaceNotifNB);
                            $reportStatusData = [
                                'status' => '3'
                            ];
                            $reportsModel->update($id, $reportStatusData);
                            $myItemsModel = new MyitemsModel;
                            $myItemupdateStatus = [
                                'reported' => '2'
                            ];
                            $myItemsModel->update($myitemid, $myItemupdateStatus);


                            $dataReportDetails = [
                                'reportid' => $id,
                                'message' => '<p class="text-danger">Dispute Closed</p><p> This disput has been closed.</p>',
                                'userid' => $buyerid,
                                'username' => 'System',
                                'user_groupe' => '9',
                            ];
                            $reportDetailsModel->insert($dataReportDetails);
                            $response["message"] = '<p class="text-danger">Dispute Closed</p><p> This disput has been closed.</p>';
                            $response["date"] = date('Y/m/d H:i:s');
                            $response["error"] = '0';
                            $response["username"] = 'System';
                            $response["statustext"] = '<span class="badge badge-success px-2 py-2">DISPUTE CLOSED <i class="mdi mdi-check"></i></span>';
                        }
                        else {
                            $modalPar = [
                                'close' => '1',
                                'mtitle' => 'Error',
                                'content' => '<div class="alert alert-danger left-icon-big fade show">
                                            <div class="media">
                                                <div class="alert-left-icon-big">
                                                    <span><i class="mdi mdi-alert"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <p class="card-text"><strong>Error : <span class="text-danger">E:003</span></strong></p>
                                                </div>
                                            </div>
                                        </div>',
                            ];
                            $response["modal"] = createModal($modalPar); 
                        }
                    }
                    else {
                        $modalPar = [
                            'close' => '1',
                            'mtitle' => 'Error',
                            'content' => '<div class="alert alert-danger left-icon-big fade show">
                                        <div class="media">
                                            <div class="alert-left-icon-big">
                                                <span><i class="mdi mdi-alert"></i></span>
                                            </div>
                                            <div class="media-body">
                                                <p class="card-text"><strong>Error : <span class="text-danger">E:005</span></strong></p>
                                            </div>
                                        </div>
                                    </div>',
                        ];
                        $response["modal"] = createModal($modalPar);
                    }
                }
                else {
                    $modalPar = [
                        'close' => '1',
                        'mtitle' => 'Error',
                        'content' => '<div class="alert alert-danger left-icon-big fade show">
                                    <div class="media">
                                        <div class="alert-left-icon-big">
                                            <span><i class="mdi mdi-alert"></i></span>
                                        </div>
                                        <div class="media-body">
                                            <p class="card-text"><strong>Error : <span class="text-danger">E:003</span></strong></p>
                                        </div>
                                    </div>
                                </div>',
                    ];
                    $response["modal"] = createModal($modalPar);
                }
                
            }
            else {
                echo 'Nice try ;)';
            }  
        }
        else {
            $modalPar = [
                'close' => '1',
                'mtitle' => 'Error',
                'content' => '<div class="alert alert-danger left-icon-big fade show">
                            <div class="media">
                                <div class="alert-left-icon-big">
                                    <span><i class="mdi mdi-alert"></i></span>
                                </div>
                                <div class="media-body">
                                    <h5 class="mt-1 mb-2"><strong>Error : <span class="text-danger">E:004</spa></h5>
                                    <p class="card-text">Session Timeou, <a href="'.base_url().'">Please Login</a>.</p>
                                </div>
                            </div>
                        </div>',
            ];
            $response["modal"] = createModal($modalPar);
        }
        header('Content-Type: application/json');
        echo json_encode($response);  
        exit();
    }

    public function ProofProvider(){
        $response = array();
        if(session()->get('logedin') == '1' && session()->get('suser_groupe') == '9' || session()->get('logedin') == '1' && session()->get('suser_groupe') == '4' || session()->get('logedin') == '1' && session()->get('suser_groupe') == '1' ){
            if($this->request->isAJAX()){
                $ValidationRulls = [
                    'id' => [
                        'label' => 'id',
                        'rules'  => 'required|regex_match[/^([0-9]{2,11}+)$/]',
                        'errors' => [
                            'required' => 'Invalid id.',
                            'regex_match' => 'Invalid id regex.',
                        ]
                    ],
                    'proof.*' => [
                        'label' => 'Proof',
                        'rules'  => 'required|valid_url',
                        'errors' => [
                            'required' => 'Invalid Proof.',
                            'valid_url' => 'Invalid Proof.',
                        ]
                    ],
                ];
                if(!$this->validate($ValidationRulls)){
                    $ErrorFields = $this->validator->getErrors();
                    $ErrorsText = [];
                    $keys = [];
                    foreach($ErrorFields as $key => $value){
                        $ErrorsText[] = $value;
                        $keys[] = $key;
                    }
                    $response["content"] =  array_combine($keys, $ErrorsText);
                    $response["csrft"] = csrf_hash();
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit(); 
                }
                else {
                    $id = $this->request->getPost('id');
                    $proofs = $this->request->getPost('proof');
                    $reportsModel = new ReportsModel;
                    $reportDetailsModel = new ReportsDetailsModel;
                    $getReport = $reportsModel->where('id', $id)->findAll(1);
                    if(count($getReport) > 0 && $getReport[0]['status'] == '0'){
                        $reportType = $getReport[0]['rtype'];
                        $itemId = $getReport[0]['itemid'];
                        $itemprice = $getReport[0]['itemprice'];
                        $sellerid = $getReport[0]['sellerid'];
                        $buyerid = $getReport[0]['buyerid'];
                        $buyerusername = $getReport[0]['buyerusername'];
                        $myitemid = $getReport[0]['myitemid'];
                        $usersModel = new UsersModel;
                        if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '4' || session()->get('suser_id') == $sellerid){
                            $type = ucfirst(esc($reportType));
                            $dataNotif = [
                                'subject' => 'Proof Provided',
                                'text' => 'Proof of working provided for Report ID #'.$id,
                                'url' => base_url().'disputes',
                                'icon' => 'mdi-close',
                                'type' => 0,
                                'userid' => $buyerid,
                            ];
                            $modelNotif = new NotificationsModel;
                            $modelNotif->save($dataNotif);
                            $updateReplaceNotifNB = [
                                'notifications_nb' => '1'
                            ];
                            $usersModel->update($buyerid,$updateReplaceNotifNB);
                            $reportChatOpenData = [
                                'chatopen' => '1'
                            ];
                            $reportsModel->update($id, $reportChatOpenData);
                            $chatdetails = '<div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-fex">';
                            foreach ($proofs as $key => $value) {
                                $chatdetails .=     '<img src="'.esc($value).'" class="img-fluid" with="150px">';
                            }
                            $chatdetails .= '   </div>
                                            </div>';
                            $dataReportDetails = [
                                'reportid' => $id,
                                'message' => $chatdetails,
                                'userid' => $sellerid,
                                'username' => 'Seller'.$sellerid,
                                'user_groupe' => '1',
                            ];
                            $reportDetailsModel->insert($dataReportDetails);

                            $response["message"] = $chatdetails;
                            $response["date"] = date('Y/m/d H:i:s');
                            $response["error"] = '0';
                            $response["username"] = 'Seller'.$sellerid;
                            $response["statustext"] = '<span class="badge badge-secondary badge-xl">Proof Provided <i class="mdi mdi-check"></i></span>';
                        }
                        else {
                            $modalPar = [
                                'close' => '1',
                                'mtitle' => 'Error',
                                'content' => '<div class="alert alert-danger left-icon-big fade show">
                                            <div class="media">
                                                <div class="alert-left-icon-big">
                                                    <span><i class="mdi mdi-alert"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <p class="card-text"><strong>Error : <span class="text-danger">E:003</span></strong></p>
                                                </div>
                                            </div>
                                        </div>',
                            ];
                            $response["modal"] = createModal($modalPar); 
                        }
                    }
                    else {
                        $modalPar = [
                            'close' => '1',
                            'mtitle' => 'Error',
                            'content' => '<div class="alert alert-danger left-icon-big fade show">
                                        <div class="media">
                                            <div class="alert-left-icon-big">
                                                <span><i class="mdi mdi-alert"></i></span>
                                            </div>
                                            <div class="media-body">
                                                <p class="card-text"><strong>Error : <span class="text-danger">E:005</span></strong></p>
                                            </div>
                                        </div>
                                    </div>',
                        ];
                        $response["modal"] = createModal($modalPar);
                    }
                }
                
            }
            else {
                echo 'Nice try ;)';
            }  
        }
        else {
            $modalPar = [
                'close' => '1',
                'mtitle' => 'Error',
                'content' => '<div class="alert alert-danger left-icon-big fade show">
                            <div class="media">
                                <div class="alert-left-icon-big">
                                    <span><i class="mdi mdi-alert"></i></span>
                                </div>
                                <div class="media-body">
                                    <h5 class="mt-1 mb-2"><strong>Error : <span class="text-danger">E:004</spa></h5>
                                    <p class="card-text">Session Timeou, <a href="'.base_url().'">Please Login</a>.</p>
                                </div>
                            </div>
                        </div>',
            ];
            $response["modal"] = createModal($modalPar);
        }
        header('Content-Type: application/json');
        echo json_encode($response);  
        exit();
    }

    public function InChat(){
        $response = array();
        if(session()->get('logedin') == '1'){
            if($this->request->isAJAX()){
                $ValidationRulls = [
                    'id' => [
                        'label' => 'id',
                        'rules'  => 'required|regex_match[/^([0-9]{2,11}+)$/]',
                        'errors' => [
                            'required' => 'Invalid id.',
                            'regex_match' => 'Invalid id regex.',
                        ]
                    ],
                    'chattext' => [
                        'label' => 'chattext',
                        'rules'  => 'required|regex_match[/^[a-zA-Z0-9\s\p{P}]+$/u]',
                        'errors' => [
                            'required' => 'Invalid Message.',
                            'regex_match' => 'Invalid Message.',
                        ]
                    ],
                ];
                if(!$this->validate($ValidationRulls)){
                    $ErrorFields = $this->validator->getErrors();
                    $ErrorsText = [];
                    $keys = [];
                    foreach($ErrorFields as $key => $value){
                        $ErrorsText[] = $value;
                        $keys[] = $key;
                    }
                    $response["content"] =  array_combine($keys, $ErrorsText);
                    $response["csrft"] = csrf_hash();
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit(); 
                }
                else {
                    $id = $this->request->getPost('id');
                    $chattext = $this->request->getPost('chattext');
                    $reportsModel = new ReportsModel;
                    $reportDetailsModel = new ReportsDetailsModel;
                    $getReport = $reportsModel->where('id', $id)->findAll(1);
                    if(count($getReport) > 0 && $getReport[0]['status'] == '0'){
                        $reportType = $getReport[0]['rtype'];
                        $itemId = $getReport[0]['itemid'];
                        $itemprice = $getReport[0]['itemprice'];
                        $sellerid = $getReport[0]['sellerid'];
                        $buyerid = $getReport[0]['buyerid'];
                        $buyerusername = $getReport[0]['buyerusername'];
                        $myitemid = $getReport[0]['myitemid'];
                        $usersModel = new UsersModel;
                        if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '4' || session()->get('suser_id') == $sellerid || session()->get('suser_id') == $buyerid){
                            $type = ucfirst(esc($reportType));
                            if(session()->get('suser_id') == $buyerid){
                                $dataNotif = [
                                    'subject' => 'New Message',
                                    'text' => 'New message on Report ID #'.$id,
                                    'url' => base_url().'disputes/details/'.$id,
                                    'icon' => 'mdi-mdi-message-alert',
                                    'type' => 0,
                                    'userid' => $sellerid,
                                ];
                                $modelNotif = new NotificationsModel;
                                $modelNotif->save($dataNotif);
                                $updateReplaceNotifNB = [
                                    'notifications_nb' => '1'
                                ];
                                $usersModel->update($sellerid,$updateReplaceNotifNB);
                            }
                            else if(session()->get('suser_id') == $sellerid){
                                $dataNotif = [
                                    'subject' => 'New Message',
                                    'text' => 'New message on Report ID #'.$id,
                                    'url' => base_url().'disputes/details/'.$id,
                                    'icon' => 'mdi-mdi-message-alert',
                                    'type' => 0,
                                    'userid' => $buyerid,
                                ];
                                $modelNotif = new NotificationsModel;
                                $modelNotif->save($dataNotif);
                                $updateReplaceNotifNB = [
                                    'notifications_nb' => '1'
                                ];
                                $usersModel->update($buyerid,$updateReplaceNotifNB);
                            }
                            else {
                                $dataNotif = [
                                    'subject' => 'New Message',
                                    'text' => 'New message on Report ID #'.$id,
                                    'url' => base_url().'disputes/details/'.$id,
                                    'icon' => 'mdi-mdi-message-alert',
                                    'type' => 0,
                                    'userid' => $buyerid,
                                ];
                                $modelNotif = new NotificationsModel;
                                $modelNotif->save($dataNotif);
                                $updateReplaceNotifNB = [
                                    'notifications_nb' => '1'
                                ];
                                $usersModel->update($buyerid,$updateReplaceNotifNB);
                            }
                            
                            $reportChatOpenData = [
                                'chatopen' => '1'
                            ];
                            $reportsModel->update($id, $reportChatOpenData);
                            $chatdetails = nl2br(esc($chattext));
                            if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '4'){
                            	$groupe = '9';
                            	$usename = 'Support';
                            }
                            elseif(session()->get('suser_groupe') == '4'){
                            	$groupe = '1';
                            	$usename = 'Seller';
                            }
                            else {
                            	$groupe = '0';
                            	$usename = 'Buyer';
                            }
                            $dataReportDetails = [
                                'reportid' => $id,
                                'message' => $chatdetails,
                                'userid' => $sellerid,
                                'username' => $usename,
                                'user_groupe' => $groupe,
                            ];
                            $reportDetailsModel->insert($dataReportDetails);
                            if(session()->get('suser_id') == $buyerid){
                                $response["message"] = $chatdetails;
                                $response["date"] = date('Y/m/d H:i:s');
                                $response["error"] = '0';
                                $response["username"] = 'Buyer';
                                $response["class"] = '';
                            }
                            else if(session()->get('suser_id') == $sellerid){
                                $response["message"] = $chatdetails;
                                $response["date"] = date('Y/m/d H:i:s');
                                $response["error"] = '0';
                                $response["username"] = 'Seller';
                                $response["class"] = 'text-info';
                            }
                            else if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '4'){
                                $response["message"] = $chatdetails;
                                $response["date"] = date('Y/m/d H:i:s');
                                $response["error"] = '0';
                                $response["username"] = 'Support';
                                $response["class"] = 'text-danger';
                            }
                        }
                        else {
                            $modalPar = [
                                'close' => '1',
                                'mtitle' => 'Error',
                                'content' => '<div class="alert alert-danger left-icon-big fade show">
                                            <div class="media">
                                                <div class="alert-left-icon-big">
                                                    <span><i class="mdi mdi-alert"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <p class="card-text"><strong>Error : <span class="text-danger">E:003</span></strong></p>
                                                </div>
                                            </div>
                                        </div>',
                            ];
                            $response["modal"] = createModal($modalPar); 
                        }
                    }
                    else {
                        $modalPar = [
                            'close' => '1',
                            'mtitle' => 'Error',
                            'content' => '<div class="alert alert-danger left-icon-big fade show">
                                        <div class="media">
                                            <div class="alert-left-icon-big">
                                                <span><i class="mdi mdi-alert"></i></span>
                                            </div>
                                            <div class="media-body">
                                                <p class="card-text"><strong>Error : <span class="text-danger">E:005</span></strong></p>
                                            </div>
                                        </div>
                                    </div>',
                        ];
                        $response["modal"] = createModal($modalPar);
                    }
                }
                
            }
            else {
                echo 'Nice try ;)';
            }  
        }
        else {
            $modalPar = [
                'close' => '1',
                'mtitle' => 'Error',
                'content' => '<div class="alert alert-danger left-icon-big fade show">
                            <div class="media">
                                <div class="alert-left-icon-big">
                                    <span><i class="mdi mdi-alert"></i></span>
                                </div>
                                <div class="media-body">
                                    <h5 class="mt-1 mb-2"><strong>Error : <span class="text-danger">E:004</spa></h5>
                                    <p class="card-text">Session Timeou, <a href="'.base_url().'">Please Login</a>.</p>
                                </div>
                            </div>
                        </div>',
            ];
            $response["modal"] = createModal($modalPar);
        }
        header('Content-Type: application/json');
        echo json_encode($response);  
        exit();
    }

    public function getMessages(){
        $response = array();
        if(session()->get('logedin') == '1'){
            if($this->request->isAJAX()){
                $ValidationRulls = [
                    'id' => [
                        'label' => 'id',
                        'rules'  => 'required|regex_match[/^([0-9]{2,11}+)$/]',
                        'errors' => [
                            'required' => 'Invalid id.',
                            'regex_match' => 'Invalid id regex.',
                        ]
                    ],
                ];
                if(!$this->validate($ValidationRulls)){
                    $ErrorFields = $this->validator->getErrors();
                    $ErrorsText = [];
                    $keys = [];
                    foreach($ErrorFields as $key => $value){
                        $ErrorsText[] = $value;
                        $keys[] = $key;
                    }
                    $response["content"] =  array_combine($keys, $ErrorsText);
                    $response["csrft"] = csrf_hash();
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit(); 
                }
                else {
                    $id = $this->request->getPost('id');
                    $reportsModel = new ReportsModel;
                    $reportDetailsModel = new ReportsDetailsModel;
                    $getReport = $reportsModel->where('id', $id)->findAll(1);
                    if(count($getReport) > 0){
                        $reportType = $getReport[0]['rtype'];
                        $itemId = $getReport[0]['itemid'];
                        $itemprice = $getReport[0]['itemprice'];
                        $sellerid = $getReport[0]['sellerid'];
                        $buyerid = $getReport[0]['buyerid'];
                        $buyerusername = $getReport[0]['buyerusername'];
                        $myitemid = $getReport[0]['myitemid'];
                        if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '4' || session()->get('suser_id') == $sellerid || session()->get('suser_id') == $buyerid){
                            if($getReport[0]['status'] == '0' && $getReport[0]['chatopen'] == '1'){
                                $GetChatsDetails = $reportDetailsModel->where('reportid', $id)->findAll();
                                $chatHistory = '';
                                foreach ($GetChatsDetails as $key => $value) {
                                    $ndate = new \DateTime($value['messagedate']);
                                    $chatHistory .= '<li>
                                                        <div class="timeline-badge primary"></div>
                                                        <div class="timeline-panel">
                                                            <span>'.$value['username'].'-'.$ndate->format('d/m/Y H:i:s').'</span>
                                                            '.$value['message'].'
                                                        </div>
                                                    </li>';
                                }
                                $response["message"] = $chatHistory;
                                $response["error"] = '0';
                                $response["chatopen"] = '1';
                                $response["status"] = $getReport[0]['status'];
                                $response["statustext"] = '<span class="badge badge-secondary badge-xl">PROOF PROVIDED <i class="mdi mdi-check"></i></span>';
                            }
                            else {
                               switch ($getReport[0]['status']) {
                                   case '1':
                                       $statustext = '<span class="badge badge-success badge-xl">ITEM REFUNDED <i class="mdi mdi-check"></i></span>';
                                       $response["statustext"] = $statustext;
                                   break;
                                   case '2':
                                       $statustext = '<span class="badge badge-success badge-xl">ITEM REPLACED <i class="mdi mdi-check"></i></span>';
                                       $response["statustext"] = $statustext;
                                   break;
                                   case '3':
                                       $statustext = '<span class="badge badge-secondary badge-xl">DISPUTE CLOSED <i class="mdi mdi-close"></i></span>';
                                       $response["statustext"] = $statustext;
                                   break;
                                }

                                $GetChatsDetails = $reportDetailsModel->where('reportid', $id)->findAll();
                                $chatHistory = '';
                                foreach ($GetChatsDetails as $key => $value) {
                                    $ndate = new \DateTime($value['messagedate']);
                                    $chatHistory .= '<li>
                                                        <div class="timeline-badge primary"></div>
                                                        <div class="timeline-panel">
                                                            <span>'.$value['username'].'-'.$ndate->format('d/m/Y H:i:s').'</span>
                                                            '.$value['message'].'
                                                        </div>
                                                    </li>';
                                }
                                $response["message"] = $chatHistory;
                                $response["error"] = '0';
                                $response["status"] = $getReport[0]['status'];
                                switch ($getReport[0]['status']) {
                                    case '1':
                                        $response["statustext"] = '<span class="badge badge-success badge-xl">REFUNDED <i class="mdi mdi-check"></i></span>';
                                    break;
                                    case '2':
                                        $response["statustext"] = '<span class="badge badge-success badge-xl">REPLACED <i class="mdi mdi-check"></i></span>';
                                    break;
                                }
                            }
                        }
                        else {
                            $modalPar = [
                                'close' => '1',
                                'mtitle' => 'Error',
                                'content' => '<div class="alert alert-danger left-icon-big fade show">
                                            <div class="media">
                                                <div class="alert-left-icon-big">
                                                    <span><i class="mdi mdi-alert"></i></span>
                                                </div>
                                                <div class="media-body">
                                                    <p class="card-text"><strong>Error : <span class="text-danger">E:003</span></strong></p>
                                                </div>
                                            </div>
                                        </div>',
                            ];
                            $response["modal"] = createModal($modalPar); 
                        }
                    }
                    else {
                        $modalPar = [
                            'close' => '1',
                            'mtitle' => 'Error',
                            'content' => '<div class="alert alert-danger left-icon-big fade show">
                                        <div class="media">
                                            <div class="alert-left-icon-big">
                                                <span><i class="mdi mdi-alert"></i></span>
                                            </div>
                                            <div class="media-body">
                                                <p class="card-text"><strong>Error : <span class="text-danger">E:005</span></strong></p>
                                            </div>
                                        </div>
                                    </div>',
                        ];
                        $response["modal"] = createModal($modalPar);
                    }
                }
                
            }
            else {
                echo 'Nice try ;)';
            }  
        }
        else {
            $modalPar = [
                'close' => '1',
                'mtitle' => 'Error',
                'content' => '<div class="alert alert-danger left-icon-big fade show">
                            <div class="media">
                                <div class="alert-left-icon-big">
                                    <span><i class="mdi mdi-alert"></i></span>
                                </div>
                                <div class="media-body">
                                    <h5 class="mt-1 mb-2"><strong>Error : <span class="text-danger">E:004</spa></h5>
                                    <p class="card-text">Session Timeou, <a href="'.base_url().'">Please Login</a>.</p>
                                </div>
                            </div>
                        </div>',
            ];
            $response["modal"] = createModal($modalPar);
        }
        header('Content-Type: application/json');
        echo json_encode($response);  
        exit();
    }
}