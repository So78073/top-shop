<?php

namespace App\Controllers;
use App\Models\UsersModel;
use App\Models\SettingsModel;
use App\Models\SectionsModel;

class Settings extends BaseController
{
    public function index(){
        if(session()->get("logedin") == "1" && session()->get("suser_groupe") == "9"){ 
        	$router = service('router'); 
	        $controller  = $router->controllerName();  
	        $router = service('router');
	        $method = $router->methodName(); 
            $data = array();
            $mycart = getCart();
            $countmycart = count(session()->get('cart'));
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
            if($settings[0]['payementgetway'] == '1'){
                $coins = json_decode(getCoins($settings[0]['nowpayementapikey']), true);
                $data["coins"] = $coins["currencies"];
            }
            $data["nbitemscart"] = $mycart[0];
            $data["cartInnerHtml"] = $mycart[1];
            $data["settings"] = $settings;  
            
            $data['method'] = explode('\\', $controller);
            $data['sectionName'] = 'Settings';
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("settings");
            echo view("assets/footer");
            echo view("assets/scripts");
        }
        else {
            header('location:'.base_url().'/login');
            exit();
        }  
    }

    public function saveBasic(){
        $response = array();
        if(session()->get("suser_groupe") == "9"){ 
            $ValidationRulls = [
                'sitename' => [
                    'rules'  => 'required|min_length[3]',
                    'errors' => [
                        'required' => 'Please set The site Name.',
                        'min_length' => 'Please set The site Name with minimum 3 charters.',
                    ]
                ],
            ];
            if($this->request->getFile("logo")->getName() !== ""){
                $ValidationRulls["logo"] = array(
                    'rules'  => 'uploaded[logo]|max_size[logo, 10240]|is_image[logo]',
                    'errors' => array(
                        'uploaded' => 'Please set a valid image for Logo.',
                        'max_size' => 'Max size exessed 10240Bb.',
                        'is_image' => 'Please set a valid image for Logo.',
                        )

                );
            } 
            if($this->request->getFile("siteuserslogo")->getName() !== ""){
                $ValidationRulls["siteuserslogo"] = array(
                'rules'  => 'uploaded[siteuserslogo]|max_size[siteuserslogo, 10240]|is_image[siteuserslogo]',
                'errors' => array(
                    'uploaded' => 'Please set a valid image for Site users Logo.',
                    'max_size' => 'Max size exessed 10240Bb.',
                    'is_image' => 'Please set a valid image for Site users Logo.',
                    )
                );
            }
            if($this->request->getPost("sitemeta") !== ""){
                $ValidationRulls["sitemeta"] = array(
                'rules'  => 'required|min_length[3]',
                'errors' => array(
                    'min_length' => 'Please set The site SEO with minimum 3 charters.',
                    )
                );
            }
            if($this->request->getPost("sitejava") !== ""){
                $ValidationRulls["sitejava"] = array(
                'rules'  => 'required|min_length[3]',
                'errors' => array(
                    'min_length' => 'Please set The site Javascript/Plugins with minimum 3 charters.',
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
                header("Content-Type: application/json;");
                echo json_encode($response);
                exit();
            }
            else {
                $logo = $this->request->getFile('logo');
                if(null !== $logo && $logo->isValid() && !$logo->hasMoved()){
                    $logo->move('assets/images/logo/');
                    $data["logo"] = $logo->getName();
                }
                $siteuserslogo = $this->request->getFile('siteuserslogo');
                if(null !== $siteuserslogo && $siteuserslogo->isValid() && !$siteuserslogo->hasMoved()){
                    $siteuserslogo->move('assets/images/avatars/');
                    $data["siteuserslogo"] = $siteuserslogo->getName().'.'.$siteuserslogo->guessExtension();
                }
                foreach($this->request->getPost() as $key => $val){
                    $data[$key] = $val;    
                }
                $model = new SettingsModel;
                $Results = $model->findAll();
                $model->update($Results[0]["id"], $data);
                $response["message"] = "Settings Saved succefull.";
                $response["typemsg"] = "success";
                $response["position"] = "bottom right";
                $response["size"] = "mini";
                $response["icone"] = "fa fa-check-circle";
                $response["sounds"] = "sound4";
            }
        }
        else {
            $modalContent = '<p class="text-danger">Error</p>';
            $modalTitle = '<p class="text-danger">You do not have permissions to see this content.</p>';
            $response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
        }
        $response["csrft"] = csrf_hash();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function saveRegistration(){
        $response = array();
        if(session()->get("suser_groupe") == "9"){ 
            $ValidationRulls = [
                'openreg' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
                    ]
                ],
                'invitecode' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
                    ]
                ],
                'telegram' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
                    ]
                ],
                'rtelegram' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
                    ]
                ],
                'icq' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
                    ]
                ],
                'ricq' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
                    ]
                ],
                'jaber' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
                    ]
                ],
                'rjaber' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
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
                header("Content-Type: application/json;");
                echo json_encode($response);
                exit();
            }
            else {
                foreach($this->request->getPost() as $key => $val){
                    if($val != ""){
                        $data[$key] = $val;   
                    }   
                }
                $model = new SettingsModel;
                $Results = $model->findAll();
                $model->update($Results[0]["id"], $data);
                $response["message"] = "Settings Saved succefull.";
                $response["typemsg"] = "success";
                $response["position"] = "bottom right";
                $response["size"] = "mini";
                $response["icone"] = "bx bx-check";
                $response["sounds"] = "sound4";
            }
        }
        else {
            $modalContent = '<p class="text-danger">Error</p>';
            $modalTitle = '<p class="text-danger">You do not have permissions to see this content.</p>';
            $response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
        }
        $response["csrft"] = csrf_hash();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function saveReferals(){
        $response = array();
        if(session()->get("suser_groupe") == "9"){ 
            $ValidationRulls = [
                'refsys' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
                    ]
                ],
                'refrate' => [
                    'rules'  => 'required|NumCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'NumCheck' => 'Please check your entry.',
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
                header("Content-Type: application/json;");
                echo json_encode($response);
                exit();
            }
            else {
                foreach($this->request->getPost() as $key => $val){
                    if($val != ""){
                        $data[$key] = $val;   
                    }   
                }
                $model = new SettingsModel;
                $Results = $model->findAll();
                $model->update($Results[0]["id"], $data);
                $response["message"] = "Settings Saved succefull.";
                $response["typemsg"] = "success";
                $response["position"] = "bottom right";
                $response["size"] = "mini";
                $response["icone"] = "bx bx-check";
                $response["sounds"] = "sound4";
            }
        }
        else {
            $modalContent = '<p class="text-danger">Error</p>';
            $modalTitle = '<p class="text-danger">You do not have permissions to see this content.</p>';
            $response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
        }
        $response["csrft"] = csrf_hash();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
    
    public function saveTelegram(){
        $response = array();
        if(session()->get("suser_groupe") == "9"){ 
            $ValidationRulls = [
                'telenotif' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
                    ]
                ],
                'telebot' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => 'Input Error.',
                    ]
                ],
                'chatid' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => 'Input Error.',
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
                header("Content-Type: application/json;");
                echo json_encode($response);
                exit();
            }
            else {
                foreach($this->request->getPost() as $key => $val){
                    if($val != ""){
                        $data[$key] = $val;   
                    }   
                }
                $model = new SettingsModel;
                $Results = $model->findAll();
                $model->update($Results[0]["id"], $data);
                $response["message"] = "Settings Saved succefull.";
                $response["typemsg"] = "success";
                $response["position"] = "bottom right";
                $response["size"] = "mini";
                $response["icone"] = "bx bx-check";
                $response["sounds"] = "sound4";
            }
        }
        else {
            $modalContent = '<p class="text-danger">Error</p>';
            $modalTitle = '<p class="text-danger">You do not have permissions to see this content.</p>';
            $response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
        }
        $response["csrft"] = csrf_hash();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function saveSellers(){
        $response = array();
        if(session()->get("suser_groupe") == "9"){ 
            $ValidationRulls = [
                'sellersystem' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
                    ]
                ],
                'sellerate' => [
                    'rules'  => 'required|NumCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'NumCheck' => 'Please check your entry.',
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
                foreach($this->request->getPost() as $key => $val){
                    if($val != ""){
                        $data[$key] = $val;   
                    }   
                }
                $model = new SettingsModel;
                $Results = $model->findAll();
                $model->update($Results[0]["id"], $data);
                $response["message"] = "Settings Saved succefull.";
                $response["typemsg"] = "success";
                $response["position"] = "bottom right";
                $response["size"] = "mini";
                $response["icone"] = "bx bx-check";
                $response["sounds"] = "sound4";
            }
        }
        else {
            $modalContent = '<p class="text-danger">Error</p>';
            $modalTitle = '<p class="text-danger">You do not have permissions to see this content.</p>';
            $response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
        }
        $response["csrft"] = csrf_hash();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function saveCheckers(){
        $response = array();
        if(session()->get("suser_groupe") == "9"){ 
            $ValidationRulls = [
                'ccchecker' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
                    ]
                ],
                'checkerused' => [
                    'rules'  => 'required|NumCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'NumCheck' => 'Please check your entry.',
                    ]
                ],
                'ccchecktimeout' => [
                    'rules'  => 'required|NumCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'NumCheck' => 'Please check your entry.',
                    ]
                ],
                'cccheckercost' => [
                    'rules'  => 'required|DoubleCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'NumCheck' => 'Please check your entry.',
                    ]
                ],
                'baseapproved' => [
                    'rules'  => 'required|BinaryCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'BinaryCheck' => 'Please check your entry.',
                    ]
                ],
                
            ];
            if($this->request->getPost('ccchecker') == "1"){
                switch($this->request->getPost('checkerused')){
                    case '1' :
                        $ValidationRulls["luxorcheckeruser"] = array(
                            'rules'  => 'required',
                            'errors' => array(
                                'required' => 'Please set your Luxchecker.pw User name.',
                            )
                        );
                        $ValidationRulls["luxorchecjerapi"] = array(
                            'rules'  => 'required',
                            'errors' => array(
                                'required' => 'Please set your Luxchecker.pw API Key.',
                            )
                        );
                    break;
                    case '2' :
                        $ValidationRulls["ccdotcheckapi"] = array(
                            'rules'  => 'required',
                            'errors' => array(
                                'required' => 'Please set your Chk.cards API Key.',
                            )
                        );
                    break;
                    case '3' :
                        $ValidationRulls["luxorcheckeruser"] = array(
                            'rules'  => 'required',
                            'errors' => array(
                                'required' => 'Please set your Luxchecker.pw User name.',
                            )
                        );
                        $ValidationRulls["luxorchecjerapi"] = array(
                            'rules'  => 'required',
                            'errors' => array(
                                'required' => 'Please set your Luxchecker.pw API Key.',
                            )
                        );
                        $ValidationRulls["ccdotcheckapi"] = array(
                            'rules'  => 'required',
                            'errors' => array(
                                'required' => 'Please set your Chk.cards API Key.',
                            )
                        );
                    break;
                }    
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
                $response["csrft"] = csrf_hash();
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
            else {
                foreach($this->request->getPost() as $key => $val){
                    $data[$key] = $val;   
                }
                $model = new SettingsModel;
                $Results = $model->findAll();
                $model->update($Results[0]["id"], $data);
                $response["message"] = "Settings Saved succefull.";
                $response["typemsg"] = "success";
                $response["position"] = "bottom right";
                $response["size"] = "mini";
                $response["icone"] = "bx bx-check";
                $response["sounds"] = "sound4";
            }
        }
        else {
            $modalContent = '<p class="text-danger">Error</p>';
            $modalTitle = '<p class="text-danger">You do not have permissions to see this content.</p>';
            $response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
        }
        $response["csrft"] = csrf_hash();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function saveGetways(){
        $response = array();
        if(session()->get("suser_groupe") == "9"){ 
            $ValidationRulls = [
                'payementgetway' => [
                    'rules'  => 'required|NumCheck',
                    'errors' => [
                        'required' => 'Input Error.',
                        'NumCheck' => 'Please check your entry.',
                    ]
                ],
            ];
            
            switch($this->request->getPost('payementgetway')){
                case '1' :
                    $ValidationRulls["nowpayementapikey"] = array(
                        'rules'  => 'required',
                        'errors' => array(
                            'required' => 'Please set your Nowpayements.io API Key.',
                        )
                    );
                    $ValidationRulls["nowpaymentaccept"] = array(
                        'rules'  => 'required',
                        'errors' => array(
                            'required' => 'Please Select at lease one coin tobe accepted.',
                        )
                    );
                break;
                case '2' :
                    $ValidationRulls["coinpayementmerchen"] = array(
                        'rules'  => 'required',
                        'errors' => array(
                            'required' => 'Please set your Chk.cards API Key.',
                        )
                    );
                    $ValidationRulls["coinpayementipn"] = array(
                        'rules'  => 'required',
                        'errors' => array(
                            'required' => 'Please set your Chk.cards API Key.',
                        )
                    );
                    $ValidationRulls["coinpayementapi"] = array(
                        'rules'  => 'required',
                        'errors' => array(
                            'required' => 'Please set your Chk.cards API Key.',
                        )
                    );
                    $ValidationRulls["coinpayementsecret"] = array(
                        'rules'  => 'required',
                        'errors' => array(
                            'required' => 'Please set your Chk.cards API Key.',
                        )
                    );
                break;
                case '3' :
                    $ValidationRulls["blockonomicsapi"] = array(
                        'rules'  => 'required',
                        'errors' => array(
                            'required' => 'Please set your Luxchecker.pw User name.',
                        )
                    );
                break;
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
                foreach($this->request->getPost() as $key => $val){
                    if($key !== 'nowpaymentaccept'){
                        $data[$key] = $val;    
                    }
                    else {
                        $crypto = '';
                        for($i = 0; $i < count($val); $i++){
                            if($i == count($val)-1){
                                $crypto .= $val[$i];
                            }
                            else {
                                $crypto .= $val[$i].',';    
                            }
                        }
                        $data['nowpaymentaccept'] = $crypto;    
                    }   
                }
                $model = new SettingsModel;
                $Results = $model->findAll();
                $model->update($Results[0]["id"], $data);
                $response["message"] = "Settings Saved succefull.";
                $response["typemsg"] = "success";
                $response["position"] = "bottom right";
                $response["size"] = "mini";
                $response["icone"] = "bx bx-check";
                $response["sounds"] = "sound4";
            }
        }
        else {
            $modalContent = '<p class="text-danger">Error</p>';
            $modalTitle = '<p class="text-danger">You do not have permissions to see this content.</p>';
            $response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
        }
        $response["csrft"] = csrf_hash();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function fetchTable(){
        $output = array('data' => array());
        if(session()->get("suser_groupe") == "9"){
            $model = new SectionsModel;
            $Results = $model->findAll();
            $countresults = count($Results);
            if($countresults > 0){
                foreach ($Results as $value) {
                    $id = '#'.$value["id"];

                    $sectionName = esc(ucfirst($value["sectionlable"]));

                    switch ($value["sectionstatus"]) {
                        case '1':
                            $sectionStatus = '<span class="badge bg-success"><i class="fa fa-check"></i> Active</span>';
                        break;
                        case '0':
                            $sectionStatus = '<span class="badge bg-danger"><i class="fa fa-close"></i> Disabled</span> ';
                        break;
                    }
                    switch ($value["maintenancemode"]) {
                        case '1':
                            $maintenanceMode = '<span class="badge bg-success"><i class="fa fa-check"></i> Active</span>';
                        break;
                        case '0':
                            $maintenanceMode = '<span class="badge bg-danger"><i class="fa fa-close"></i> Disabled</span>';
                        break;
                    }
                    switch ($value["sellersactivate"]) {
                        case '1':
                            $sellersActivate = '<span class="badge bg-success"><i class="fa fa-check"></i> Active</span>';
                        break;
                        case '0':
                            $sellersActivate = '<span class="badge bg-danger"><i class="fa fa-close"></i> Disabled</span>';
                        break;
                    }
                    $itemsNumbers = $value["itemsnumbers"];

                    $sectionIcon = '<span class="bx '.$value["sectionicon"].'" style="font-size:25px"></span>';

                    $sectionRevenue = '$'.number_format($value["sectionrevenue"], 2, '.', '');   

                    $buttons = '
                        <div class="dropdown" role="group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Manage
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                <a data-api="editinit-'.$value['id'].'" class="dropdown-item" href="javascript:void(0);">
                                    <span class="fa fa-edit"></span> Edit Options
                                </a>
                                <a data-api="rminit-'.$value['id'].'" class="dropdown-item" href="javascript:void(0);">
                                    <span class="fa fa-trash"></span> Delete 
                                </a>
                            </div>
                        </div>
                    ';
                    $output['data'][] = array(
                        $id,
                        $sectionName,
                        $sectionStatus,
                        $maintenanceMode,
                        $sellersActivate,
                        $itemsNumbers,
                        $sectionIcon,
                        $sectionRevenue,
                        $buttons                
                    );
                }
                header("Content-Type: application/json; charset=UTF-8");
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
                );
                header("Content-Type: application/json; charset=UTF-8");
                echo json_encode($output);
                exit();
            }

        }
        else {
            $output['data'][] = array(
                NULL,                   
            );
            header("Content-Type: application/json; charset=UTF-8");
            echo json_encode($output);
            exit();
        }
    }

    public function initEdit(){
        if(session()->get("suser_groupe") !== "9"){
            hearder('location:'.base_url().'/');
            exit();
        }
        else {
            $response = array();
            if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id'))){
                $id = $this->request->getPost('id');
                $Model = new SectionsModel;
                $Results = $Model->where(['id' => $id])->find();
                $countResults = count($Results);
                if($countResults == 1){
                    $inicon = '<option selected value=""><i class="fa fa-'.$Results[0]['sectionicon'].'"></i></option>';
                    switch ($Results[0]['sectionstatus']) {
                        case '1':
                            $SectionStatus = '<div class="form-groupe col-6">
                                    <label>Section Status</label>
                                    <select class="form-control select2" name="sectionstatus" id="sectionstatus">
                                        <option value="1" selected>Activated</option>
                                        <option value="0">Deactivated</option>
                                    </select>
                                    <small class="sectionstatus text-danger"></small>
                                </div>';
                        break;
                        
                        case '0':
                            $SectionStatus = '<div class="form-groupe col-6">
                                    <label>Section Status</label>
                                    <select class="form-control select2" name="sectionstatus" id="sectionstatus">
                                        <option value="0" selected>Deactivated</option>
                                        <option value="1">Activated</option>
                                    </select>
                                    <small class="sectionstatus text-danger"></small>
                                </div>';
                        break;
                    }
                    switch ($Results[0]['sellersactivate']) {
                        case '1':
                            $SellersActivate = '<div class="form-groupe col-6">
                                    <label>Allow sellers</label>
                                    <select class="form-control select2" name="sellersactivate" id="sellersactivate">
                                        <option value="1" selected>Activated</option>
                                        <option value="0">Deactivated</option>
                                    </select>
                                    <small class="sellersactivate text-danger"></small>
                                </div>';
                        break;
                        
                        case '0':
                            $SellersActivate = '<div class="form-groupe col-6">
                                    <label>Allow sellers</label>
                                    <select class="form-control select2" name="sellersactivate" id="sellersactivate">
                                        <option value="0" selected>Deactivated</option>
                                        <option value="1">Activated</option>
                                        
                                    </select>
                                    <small class="sellersactivate text-danger"></small>
                                </div>';
                        break;
                    }

                    switch ($Results[0]['maintenancemode']) {
                        case '1':
                            $MaintenanceMode = '<div class="form-groupe col-6">
                                    <label>Maintenance Mode</label>
                                    <select class="form-control select2" name="maintenancemode" id="maintenancemode">
                                        <option value="1" selected>Activated</option>
                                        <option value="0">Deactivated</option>
                                    </select>
                                    <small class="maintenancemode text-danger"></small>
                                </div>';
                        break;
                        
                        case '0':
                            $MaintenanceMode = '<div class="form-groupe col-6">
                                    <label>Maintenance Mode</label>
                                    <select class="form-control select2" name="maintenancemode" id="maintenancemode">
                                        <option value="0" selected>Deactivated</option>
                                        <option value="1">Activated</option>
                                        
                                    </select>
                                    <small class="maintenancemode text-danger"></small>
                                </div>';
                        break;


                    }
                    switch ($Results[0]['sectiontype']) {
                        case '1':
                            $SectionType = '<div class="form-groupe col-6">
                                    <label>Section Style</label>
                                    <select class="form-control select2" name="sectiontype" id="sectiontype">
                                        <option value="1" selected>Table Style</option>
                                        <option value="2">Cards Style</option>
                                    </select>
                                    <small class="sectiontype text-danger"></small>
                                </div>';
                        break;
                        
                        case '2':
                            $SectionType = '<div class="form-groupe col-6">
                                    <label>Section Style</label>
                                    <select class="form-control select2" name="sectiontype" id="sectiontype">
                                        <option value="1" >Table Style</option>
                                        <option value="2" selected> Cards Style</option>
                                        
                                    </select>
                                    <small class="sectiontype text-danger"></small>
                                </div>';
                        break;
                    }
                    switch ($Results[0]['resell']) {
                        case '0':
                            $resell = '<div class="form-groupe col-6">
                                    <label>Sell Mode</label>
                                    <select class="form-control select2" name="resell" id="resell">
                                        <option value="0" selected>One Time Sell</option>
                                        <option value="1">Multi Sells</option>
                                    </select>
                                    <small class="resell text-danger"></small>
                                </div>';
                        break;
                        
                        case '1':
                            $resell = '<div class="form-groupe col-6">
                                    <label>Sell Mode</label>
                                    <select class="form-control select2" name="resell" id="resell">
                                        <option value="1" selected>Multi Sells</option>
                                        <option value="0">One Time Sell</option>
                                    </select>
                                    <small class="resell text-danger"></small>
                                </div>';
                        break;
                    
                    }
                    $icon = '<div class="form-groupe col-12">
                                <label>Icon</label>
                                <select class=" iconselect form-control" id="sectionicon" name="sectionicon" tabindex="-1" aria-hidden="true">
                                <option selected value="'.$Results[0]['sectionicon'].'">&lt;i class="bx '.$Results[0]['sectionicon'].'"&gt;&lt;/i&gt; '.$Results[0]['sectionicon'].'</option>
                                </select>
                                <small class="sectionicon text-danger"></small>
                            </div>';
                    $form = '
                        <form id="edittForm">
                            <div class="row pt-3">
                                <div class="form-group col-6">
                                    <label>Section Name</label>
                                    <input type="sectionlable" id="sectionlable" name="sectionlable" class="form-control" placeholder="Email" value="'.esc($Results[0]['sectionlable']).'">
                                    <small class="sectionlable text-danger"></small>
                                </div>
                                '.$SectionType.'
                            </div>
                            <div class="row pt-3">
                                '.$SectionStatus.'
                                '.$SellersActivate.'
                                '.$MaintenanceMode.'
                                '.$resell.'
                                '.$icon.'
                            </div>
                            <input type="hidden" name="id" value="'.$Results[0]['id'].'">
                        </form>
                        <script>

                            function initSelectIcons(){
                                $.get(\''.base_url().'/assets/css/box.yml\', function(data) {
                                    var parsedYaml = jsyaml.load(data);
                                    var options = new Array();
                                    $.each(parsedYaml.icons, function(index, icon){
                                        options.push({
                                            id: icon.id,
                                            text: \'<i class="bx \' + icon.id + \'"></i> \' + icon.id
                                        }); 
                                    });
                                    $(\'.iconselect\').select2({
                                        data:options,
                                        dropdownParent: $("#bsModal"),
                                        escapeMarkup: function(markup) {
                                            return markup;
                                        },
                                        width: "100%",
                                        dropdownParent: $("#bsModal")
                                    });
                                });
                                $(\'select\').select2({
                                    width: "100%",
                                    dropdownParent: $("#bsModal")
                                });
                            }

                            initSelectIcons(); 
                        </script>
                    '; 
                    $modalContent = $form;
                    $response["modal"] = createModal($modalContent, 'fade animated', 'Edit the Section '.esc(ucfirst($Results[0]['sectioname'])), '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Save', 'functions' => 'data-api="edit-'.$Results[0]['id'].'"']);
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

    public function edit(){
        if(session()->get("suser_groupe") !== "9"){
            hearder('location:'.base_url().'/');
            exit();
        }
        else {
            $response = array();
            if(session()->get("suser_groupe") == "9" ){
                $ValidationRulls = [
                    'id' => [
                        'rules'  => 'required|numeric',
                        'errors' => [
                            'required' => 'Input Error.',
                            'NumCheck' => 'Please check your entry.',
                        ]
                    ],
                    'sectionlable' => [
                        'rules'  => 'required|FnameLname',
                        'errors' => [
                            'required' => 'Input Error.',
                            'NumCheck' => 'Please check your entry.',
                        ]
                    ],
                    'sectionstatus' => [
                        'rules'  => 'required|BinaryCheck',
                        'errors' => [
                            'required' => 'Input Error.',
                            'NumCheck' => 'Please check your entry.',
                        ]
                    ],
                    'sellersactivate' => [
                        'rules'  => 'required|BinaryCheck',
                        'errors' => [
                            'required' => 'Input Error.',
                            'NumCheck' => 'Please check your entry.',
                        ]
                    ],
                    'maintenancemode' => [
                        'rules'  => 'required|BinaryCheck',
                        'errors' => [
                            'required' => 'Input Error.',
                            'NumCheck' => 'Please check your entry.',
                        ]
                    ],
                    'sectionicon' => [
                        'rules'  => 'required',
                        'errors' => [
                            'required' => 'Input Error.',
                            'NumCheck' => 'Please check your entry.',
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
                    header("Content-Type: application/json; charset=UTF-8");
                    echo json_encode($response);
                    exit();
                }
                else {
                    $model = new SectionsModel;
                    foreach($this->request->getPost() as $key => $val){
                        $data[$key] = $val;    
                    }
                    $model->update($this->request->getPost('id'), $data);
                    $response["message"] = "Settings Saved succefull.";
                    $response["typemsg"] = "success";
                    $response["position"] = "bottom right";
                    $response["size"] = "mini";
                    $response["icone"] = "bx bx-check";
                    $response["sounds"] = "sound4";
                }
            }
            else {
                $modalContent = '<p class="text-danger">Error</p>';
                $modalTitle = '<p class="text-danger">You do not have permissions to see this content.</p>';
                $response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
            }
            $response["csrft"] = csrf_hash();
            header("Content-Type: application/json; charset=UTF-8");
            echo json_encode($response);
            exit();  
        }
    }

    public function rminit(){
        if(session()->get("suser_groupe") !== "9"){
            exit();
        }
        else {
            if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id'))){
                $id = $this->request->getPost('id');
                $Model = new SectionsModel;
                $Results = $Model->where(['id' => $id])->find();
                $countResults = count($Results);
                if($countResults == 1){
                    $modalContent = '<p>Do you realy wan to remove this Section ?</p>';
                    $response["modal"] = createModal($modalContent, 'fade bounce animated', 'Delete Section', 'text-danger', 'modal-lg', "1", "1", "1", "1", "1",['text' => 'Delete', 'functions' =>'data-api="rm-'.$Results[0]["id"].'"']);
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

    public function rm(){
        if(session()->get("suser_groupe") !== "9"){
            exit();
        }
        else {
            if($this->request->getPost('id') != "" && is_numeric($this->request->getPost('id'))){
                $id = $this->request->getPost('id');
                $Model = new SectionsModel;

                $Results = $Model->where(['id' => $id])->find();
            
                $countResults = count($Results);
                if($countResults == 1){
                    $sectionName = $Results[0]['sectioname'];

                    $forge = \Config\Database::forge();

                    $db = db_connect();

                    $query = $db->query('SELECT * FROM section_'.strtolower($sectionName).' WHERE selled = 0');

                    $ArraysOfNonSelled = $query->getResultArray();
                    $userIdsArray = [];
                    if(count($ArraysOfNonSelled) > 0){

                        foreach($ArraysOfNonSelled as $keyp => $valp){
                            $userIdsArray[] = $valp["sellerid"];
                            
                        }
                        $newUnicArrayOfIdSellers = array_unique($userIdsArray);
                        foreach ($newUnicArrayOfIdSellers as $sellersIDs) {
                            $querytwo = $db->query('SELECT * FROM section_'.strtolower($sectionName).' WHERE selled = 0 AND sellerid='.$sellersIDs);
                            $counts = $querytwo->getNumRows();
                            if($counts > 0){
                                $UsersModel = new UsersModel;
                                $GetUser = $UsersModel->where(['id' => $sellersIDs])->findAll();
                                if(count($GetUser) == 1){
                                    
                                    if($GetUser[0]['seller_nbobjects'] != 0){
                                        $NewSellerNbObjects = $GetUser[0]['seller_nbobjects'] - $counts;
                                        $dataSellerUpdate = [
                                            'seller_nbobjects' => $NewSellerNbObjects
                                        ];
                                        $UsersModel->update($GetUser[0]['id'], $dataSellerUpdate);
                                    }
                                }
                            }
                        }
                    }

                    $forge->dropTable('section_'.strtolower($sectionName));
                    $forge->dropTable('table_'.strtolower($sectionName));
                    $forge->dropTable('inputs_'.strtolower($sectionName));

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
}
