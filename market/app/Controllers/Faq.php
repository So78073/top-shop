<?php

namespace App\Controllers;
use App\Models\FaqModel;
use App\Models\NotificationsModel;
use App\Models\UsersModel;
class Faq extends BaseController
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
			$data["sectionName"] = "FAQ";
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("faq");
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
            if(session()->get("suser_groupe") !== "9"){
                exit();
            }
            else {
                $response = array();
                $form = '
                    <form id="addCont" enctype="multipart/form-data">
                        <div class="form-group row">
                            <div class="col-12">
                                <label>Question</label>
                                <small class="title text-danger"></small>
                                <textarea id="question" name="question" class="form-control"></textarea>
                            </div>
                            <div class="col-12">
                                <label>Answear</label>
                                <small class="post text-danger"></small>
                                <textarea id="answear" name="answear" class="form-control"></textarea>
                            </div>
                        </div>
                    </form>
                ';
                $modalTitle = 'Create New FAQ';
                $response["modal"] = createModal($form, 'fade', $modalTitle, '', '', "1", "1", "1", "1", "1", ['functions' => 'data-api="createlog" ', 'text' => 'Save']);
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
            if(session()->get("logedin") != '1'){
                header('location:'.base_url().'/login');
                exit();
            }
            else {
                $response = array();
                if(session()->get("suser_groupe") == "9"){
                    if($this->request->getMethod() == 'post'){
                        $title = $this->request->getPost("question");
                        $post = $this->request->getPost("answear");
                        $data = [
                            "question" => $title,
                            "answear" => $post,
                        ];
                        $Mmodel = new FaqModel;
                        $Mmodel->save($data);
                        $ModelContent ='<h5>FAQ add succefully</h5>';
                        $ModalTitle = 'Success.'; 
                        $response["modal"] = createModal($ModelContent, 'fade ', $ModalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
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
    
    public function fetchTable(){
        if($this->request->isAJAX()){
            if(session()->get("logedin") != true){
                header('location:'.base_url().'/login');
                exit();
            }
            else {
                $response = array();
                $model = new FaqModel;
                $Results = $model->orderBy('id', 'asc')->findAll();
                $countResults = count($Results);
                $html = '';
                if($countResults > 0) {
                    $html = '';
                    $x = 0;
                    foreach($Results as $value){
                        if(session()->get('suser_groupe') == '9'){
                            $btn = '
                            
                                <div class="card-option tx-24">
                                    <a href="javascript:void(0);" class="tx-gray-600 mg-l-10"  data-api="editinit-'.$value["id"].'"><i class="icon ion-ios-compose-outline lh-0"></i></a>
                                    <a href="javascript:void(0);" class="tx-gray-600 mg-l-10" data-api="rminit-'.$value["id"].'"><i class="icon ion-ios-trash lh-0"></i></a>
                                </div>
                            ';
                        }
                        else {
                            $btn = '';
                        }
                        $html .= '<div class="card">
                                    <div class="card-header card-header-default justify-content-between bg-gray-400">
                                        <h6 class="mg-b-0 tx-14 tx-inverse">'.ucfirst($value["question"]).'</h6>
                                        '.$btn.'
                                    </div>
                                    <div class="card-body ">
                                        <p>'.$value['answear'].'</p>
                                    </div>
                                </div>';
    
                    }
                    //$response["csrft"] = csrf_hash();
                    header('Content-Type: application/json');
                    echo json_encode($html);
                    exit();
                }
                else {
    
                }
            }           
        }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
    }
    
    public function rminit(){
        if($this->request->isAJAX()){
            if(session()->get("suser_groupe") !== "9"){
                exit();
            }
            else {
                if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id'))){
                    $id = $this->request->getPost('id');
                    $Model = new FaqModel;
                    $Results = $Model->where(['id' => $id])->find();
                    $countResults = count($Results);
                    if($countResults == 1){
                        $modalContent = '<p>Do you realy wan to remove this item ?</p>';
                        $response["modal"] = createModal($modalContent, 'fade bounce animated', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "1",['text' => 'Delete', 'functions' =>'data-api="rm-'.$Results[0]["id"].'"']);
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

    public function rm(){
        if($this->request->isAJAX()){
            if(session()->get("suser_groupe") !== "9"){
                exit();
            }
            else {
                if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id'))){
                    $id = $this->request->getPost('id');
                    $Model = new FaqModel;
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

    public function initEdit(){
        if($this->request->isAJAX()){
            if(session()->get("suser_groupe") !== "9"){
                header('location:'.base_url().'/');
                exit();
            }
            else {
                $response = array();
                if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id'))){
                    $id = $this->request->getPost('id');
                    $Model = new FaqModel;
                    $Results = $Model->where(['id' => $id])->find();
                    $countResults = count($Results);
                    if($countResults == 1){
                        $form = '
                            <form id="edittForm" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label>Question <small class="title text-danger"></small></label>
                                        
                                        <textarea name="question" class="form-control" id="question">'.$Results[0]["question"].'</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label>Answear <small class="title text-danger"></small></label>
                                        <textarea class="form-control" name="answear" id="answear">'.$Results[0]["answear"].'</textarea>
                                        <input type="hidden" name="id" value="'.$Results[0]["id"].'">
                                    </div>
                                </div>
                            </form>'; 
                        $modalContent = $form;
                        $response["modal"] = createModal($modalContent, 'fade bounce animated', 'Edit the object', 'text-primary', '', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="edit-'.$Results[0]['id'].'"']);
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

    public function edit(){
        if($this->request->isAJAX()){
            if(session()->get("suser_groupe") !== "9"){
                exit();
            }
            else {
                $response = array();
                if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id'))){
                    $id = $this->request->getPost('id');
                    $Model = new FaqModel;
                    $Results = $Model->where(['id' => $id])->find();
                    $countResults = count($Results);
                    if($countResults == 1){
                        $title = $this->request->getPost("question");
                        $features = $this->request->getPost("answear");
                        $data = [
                            "question" => $title,
                            "answear" => $features,
                        ];    
                        $Request = $Model->update($id, $data);
                        $modalContent = '<p>News Edited succefull</p>';
                        $modalTitle = 'Edit succefull';
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
	        echo "Nice try ;)";
	        exit();
	    }
    }
}