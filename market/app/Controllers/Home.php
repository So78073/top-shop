<?php
namespace App\Controllers;
use App\Models\UsersModel;
use App\Models\NotificationsModel;
use App\Models\NewsModel;

class Home extends BaseController
{
    public function index()
    {
        if(session()->get("logedin") == "1"){
            $data = [];
            $usersModel = new UsersModel;
            $userLogein = $usersModel->where('`id`' , session()->get('suser_id'))->findAll();
            //$stlogin = session()->get('logedin'); 

            $settings = fetchSettings();
            $mycart = getCart();
            /**if(null !== $stlogin && $stlogin == '0'){
                $html = '<div class="modal fade" id="bsModal" tabindex="-1" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Welcome</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">Welcome to '.base_url().' '.ucfirst(session()->get('suser_username')).'.</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="assets/js/bootstrap.bundle.min.js"></script>
                <script>
                    var myModal = new bootstrap.Modal(document.getElementById(\'bsModal\'), {
                        keyboard: false,
                        backdrop: "static",
                    });
                    myModal.show(); 
                </script>';

                $updateuser = [
                    'stconnect' => '1'
                ];

                $usersModel->update(session()->get('suser_id'),$updateuser);
            }
            else {
                $html = '';
            }**/

            $data["sectionName"] = 'Home';
            //$data["stlogin"] = $html;
            $data["nbitemscart"] = $mycart[0];
            $data["cartInnerHtml"] = $mycart[1];
            $data["settings"] = $settings;
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("home");
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
                            <div class="col-6">
                                <label>Title</label>
                                <small class="title text-danger"></small>
                                <input type="text" name="title" class="form-control" id="title">
                            </div>
                            <div class="col-12">
                                <label>Create new post</label>
                                <small class="post text-danger"></small>
                                <textarea class="form-control" name="post" id="post"></textarea>
                            </div>
                        </div>
                    </form>
                    <script>
                        tinymce.init({
                          selector: \'#post\',
                            setup: function (editor) {
                                editor.on(\'change\', function () {
                                    editor.save();
                                });
                            },
                            height: 500,
                            menubar: false,
                            plugins: [
                              \'advlist autolink lists link image charmap print preview anchor\',
                              \'searchreplace visualblocks code fullscreen\',
                              \'insertdatetime media table paste code help wordcount\'
                            ],
                            toolbar: \'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image | link\'
                        });
    
                        document.addEventListener(\'focusin\', (e) => {
                          if (e.target.closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
                            e.stopImmediatePropagation();
                          }
                        });
                    </script>
                ';
                $modalTitle = 'Create New Post';
                $response["modal"] = createModal($form, 'fade', $modalTitle, '', 'modal-fullscreen', "1", "1", "1", "1", "1", ['functions' => 'data-api="createlog" ', 'text' => 'Save']);
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
                        $title = $this->request->getPost("title");
                        $post = $this->request->getPost("post");
                        $data = [
                            "title" => $title,
                            "news" => $post,
                        ];
                        $Mmodel = new NewsModel;
                        $Mmodel->save($data);
                        $ModelContent ='<h5>Added</h5>';
                        $ModalTitle = 'Success'; 
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
            if(session()->get("logedin") != '1'){
                header('location:'.base_url().'/login');
                exit();
            }
            else {
                $response = array();
                $model = new NewsModel;
                $Results = $model->orderBy('id', 'desc')->findAll();
                $countResults = count($Results);
                $html = '';
                if($countResults > 0) {
                    foreach($Results as $value){
    
                        if(session()->get('suser_groupe') == '9'){
                            $isadmin = '
                                <div class="card-header card-header-default justify-content-between bg-gray-400">
                                  <h6 class="mg-b-0 tx-14 tx-inverse">'.ucfirst($value["title"]).'</h6>
                                    <div class="card-option tx-24">
                                        <a href="javascript:void(0);" class="tx-gray-600 mg-l-10" data-api="editinit-'.$value["id"].'"><i class="icon ion-ios-compose-outline lh-0"></i></a>
                                        <a href="javascript:void(0);" class="tx-gray-600 mg-l-10" data-api="rminit-'.$value["id"].'"><i class="icon ion-ios-trash lh-0"></i></a>
                                    </div>
                                </div>';
                        }
                        else {
                           $isadmin = '
                                <div class="card-header card-header-default justify-content-between bg-gray-400">
                                  <h6 class="mg-b-0 tx-14 tx-inverse">'.ucfirst($value["title"]).'</h6>
                                </div>';
                        }
                        
                        $html .= '<div class="card">
                                        '.$isadmin.'
                                        <div class="card-body ">
                                            '.$value['news'].'
                                        </div>
                                    </div>
                        ';  
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
                    $Model = new NewsModel;
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
                    $Model = new NewsModel;
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
                    $Model = new NewsModel;
                    $Results = $Model->where(['id' => $id])->find();
                    $countResults = count($Results);
                    if($countResults == 1){
                        $form = '
                            <form id="edittForm" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label>Title</label>
                                        <small class="title text-danger"></small>
                                        <input type="text" name="title" class="form-control" id="title" value="'.$Results[0]["title"].'">
                                    </div>
                                    <div class="col-12">
                                        <label>Creat new post</label>
                                        <small class="post text-danger"></small>
                                        <input type="hidden" name="id" value="'.$Results[0]["id"].'">
                                        <textarea class="form-control" name="post" id="post">'.$Results[0]["news"].'</textarea>
                                        <input type="hidden" name="id" value="'.$Results[0]["id"].'">
                                    </div>
                                </div>
                            </form>
                            <script>
                                tinymce.init({
                                  selector: \'#post\',
                                    setup: function (editor) {
                                        editor.on(\'change\', function () {
                                            editor.save();
                                        });
                                    },
                                    height: 500,
                                    menubar: false,
                                    plugins: [
                                      \'advlist autolink lists link image charmap print preview anchor\',
                                      \'searchreplace visualblocks code fullscreen\',
                                      \'insertdatetime media table paste code help wordcount\'
                                    ],
                                    toolbar: \'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image | link\'
                                });
            
                                document.addEventListener(\'focusin\', (e) => {
                                  if (e.target.closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
                                    e.stopImmediatePropagation();
                                  }
                                });
                            </script>'; 
                        $modalContent = $form;
                        $response["modal"] = createModal($modalContent, 'fade bounce animated', 'Edit the object', 'text-primary', 'modal-fullscreen', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="edit-'.$Results[0]['id'].'"']);
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
                    $Model = new NewsModel;
                    $Results = $Model->where(['id' => $id])->find();
                    $countResults = count($Results);
                    if($countResults == 1){
                        $title = $this->request->getPost("title");
                        $features = $this->request->getPost("post");
                        $data = [
                            "title" => $title,
                            "news" => $features,
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
