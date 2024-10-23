<?php
namespace App\Controllers;
use App\Models\SectionsModel;

class Wizzard extends BaseController
{
	public function index()
    {
        if(session()->get("suser_groupe") == '9'){   
        	$router = service('router'); 
	        $controller  = $router->controllerName();  
	        $router = service('router');
	        $method = $router->methodName(); 
            $data = array();
            $data['method'] = explode('\\', $controller);
			$settings = fetchSettings();
            $mycart = getCart();
            $data["nbitemscart"] = $mycart[0];
            $data["cartInnerHtml"] = $mycart[1];
            $data["settings"] = $settings;
            $data["sectionName"] = "Section Wizzard";
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("wizzard");
            echo view("assets/footer");
            echo view("assets/scripts"); 
        }
        else {
            header('location:'.base_url().'/login');
            exit();
        }
        
    }

	public function createNewSection(){
		if($this->request->isAJAX()){
			if(session()->get("suser_groupe") == '9'){
				$ValidationRulls = [
		    	 	'sectionname' => [
			            'label'  => 'Section name',
			            'rules'  => 'required|min_length[1]|max_length[30]|alpha_space|is_unique[sections.sectioname]',
			            'errors' => [
			            	'required' => 'Section name are required.',
			                'min_length' => 'A valid Section name can contain at minimum 1 characters.',
			                'max_leng' => 'A valid Section name can contain at max 30 characters.',
			                'alpha_space' => 'A valid Section name can contain only alpha & spaces characters.',
			                'is_unique' => 'Please choose another Section name, this Section name exist.',
			            ]
			        ],
			        'sectionicone' => [
			            'label'  => 'Section Icone',
			            'rules'  => 'required|min_length[1]|max_length[50]|alpha_dash',
			            'errors' => [
			            	'required' => 'Section Icone are required.',
			                'min_length' => 'A valid Section Icone can contain at minimum 1 characters.',
			                'max_leng' => 'A valid Section Icone can contain at max 50 characters.',
			                'alpha_dash' => 'A valid Section Icone can contain only alpha & dashes.',
			            ]
			        ],
			        'sectionstyle' => [
			            'label'  => 'Section Style',
			            'rules'  => 'required|min_length[1]|max_length[1]|numeric',
			            'errors' => [
			            	'required' => 'Section Style are required.',
			                'min_length' => 'A valid Section Style can contain at minimum 1 characters.',
			                'max_leng' => 'A valid Section Style can contain at max 1 characters.',
			                'numeric' => 'A valid Section Style can contain only numeric characters.',
			            ]
			        ],
				];
				foreach($this->request->getPost('fields') as $key => $val){
					foreach ($val as $ke => $va) {
						switch ($ke) {
							case 'fieldsNames':
								$ValidationRulls["fields.*.fieldsNames.0"] = array(
						            'label'  => 'Field Name',
						            'rules'  => 'required',
						            'errors' => array('required' => 'Field Name are required.'),
					            );
							break;
							case 'fieldTypes':
								$ValidationRulls["fields.*.fieldTypes.0"] = array(
						            'label'  => 'Field Type',
						            'rules'  => 'required',
						            'errors' =>  array('required' => 'Field Type are required.','alpha_numeric_space' => 'Field Type can contain only alphanumeric characters and spaces.'),
					            );

							break;
							case 'fieldsempty':
								$ValidationRulls["fields.*.fieldsempty.0"] = array(
						            'label'  => 'Field are empty',
						            'rules'  => 'required|min_length[1]|max_length[1]|numeric',
						            'errors' =>  array('required' => 'Field Empty by Default are required.','min_length' => 'A valid Field Empty by Default can contain at minimum 1 characters.','max_leng' => 'A valid Field Empty by Default can contain at max 50 characters.', 'numeric' => 'Field Empty by Default must be numeric.'),
					            );
							break;
						}
					}
				}
				foreach($this->request->getPost('inputs') as $key => $val){
					//var_dump($key .' -> '.$val);
					foreach ($val as $ke => $va) {
						switch ($ke) {
							case 'inputLable':
								$ValidationRulls["inputs.*.inputLable"] = array(
						            'label'  => 'Input Label',
						            'rules'  => 'required|alpha_numeric_space',
						            'errors' => array('required' => 'Input label are required.','alpha_numeric_space' => 'Input Label can contain only alphanumeric characters and spaces.'),
					            );
							break;
							case 'inputsTypes':
								$ValidationRulls["inputs.*.inputsTypes"] = array(
						            'label'  => 'Input Type',
						            'rules'  => 'required',
						            'errors' =>  array('required' => 'Input Type are required.','alpha_numeric_space' => 'Input Type can contain only alphanumeric characters and spaces.'),
					            );
							break;
							case 'inputsIcone':
								$ValidationRulls["inputs.*.inputsIcone"] = array(
						            'label'  => 'Input Icone',
						            'rules'  => 'required|min_length[1]|max_length[50]|alpha_dash',
						            'errors' =>  array('required' => 'Icone are required.','min_length' => 'A valid Icone can contain at minimum 1 characters.','max_leng' => 'A valid Icone can contain at max 50 characters.', 'alpha_dash' => 'A valid Section Icone can contain only alpha & dashes.'),
					            );
							break;
							case 'validationrull':
								$ValidationRulls["inputs.*.validationrull.*"] = array(
						            'label'  => 'Validation Rull',
						            'rules'  => 'required',
						            'errors' =>  array('required' => 'Validation Rull are required.'),
					            );
							break;
						}
					}
				}
				if(!$this->validate($ValidationRulls)){
					$ErrorFields = $this->validator->getErrors();
					$modalTitle = '<p class="text-danger">Validation Error</p>';
					$modalContent = '';
					foreach($ErrorFields as $key => $value){
						$modalContent .= '<p class="text-success">'.$key.' => '.$value.'</p>';
					}	
					$response["fieldslist"] = $ErrorFields;
				 	$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-dialog-centered', "1", "1", "1", "1", "0");
			    }
			    else {
					$posts = $this->request->getPost();
					if(strpos($posts["sectionname"], ' ')){
						$sectionName = str_replace(' ', '', $posts["sectionname"]);
					}
					else {
						$sectionName = $posts["sectionname"];
					}
					$sectionmessage = $posts["sectionmessage"];
					$resell = $posts["resell"];
					$sectionIcone = $posts["sectionicone"];
					$sectionStyle = $posts["sectionstyle"];
					$TablesFields = $posts["fields"];
					$FormInputs = $posts["inputs"];
					$DataableConfig = $posts['tables']; 
					$sectionMod = new SectionsModel;
					$data = [
						'sectioname' => $sectionName,
						'sectionlable' => $posts["sectionname"],
						'sectionicon' => $sectionIcone,
						'identifier' => sha1(md5(rand(1,999).time())),
						'sectiontype' => $sectionStyle,
						'sectionmessage' => $sectionmessage,
						'resell' => $resell
					];
					$lastInsertSection = $sectionMod->insert($data);
					$CreateSectionTable = \Config\Database::forge();
					$CreateInputsConfigTable = \Config\Database::forge();
					$CreateTabelConfigTable = \Config\Database::forge();
					$myFields = array();
					$myFields['id'] = array('type' => 'INT', 'constraint'=> 12, 'auto_increment' => true);
					$z = 0;
					foreach($TablesFields as $key => $value) {
						switch($value['fieldTypes'][0]){
							case 'date':
								$myFields[] = ''.$value['fieldsNames'][0].' DATE default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
							break;
							case 'datetime':
								$myFields[] = ''.$value['fieldsNames'][0].' DATETIME default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP';
							break;
							case 'longtext':
								$myFields[] = ''.$value['fieldsNames'][0].' LONGTEXT default null';
							break;
							default:
								$myFields[$value['fieldsNames'][0]] = array('type' => $value['fieldTypes'][0], 'constraint' => $value['fieldSsize'][0]);
							break;
						}
						$z++;	
					}
					$myFields['prodimagezeh'] = array('type' => 'VARCHAR', 'constraint'=> 500, 'default' => null);
					$myFields['titleprodzeh'] = array('type' => 'VARCHAR', 'constraint'=> 500, 'default' => null);	
					$myFields[] = 'addon DATETIME default CURRENT_TIMESTAMP';
					$myFields['price'] = array('type' => 'INT', 'constraint'=> 12);
					$myFields['selled'] = array('type' => 'INT', 'constraint'=> 12, 'default' => 0);
					$myFields['reported'] = array('type' => 'INT', 'constraint'=> 12, 'default' => 0);
					$myFields['refunded'] = array('type' => 'INT', 'constraint'=> 12, 'default' => 0);
					$myFields['refun'] = array('type' => 'INT', 'constraint'=> 12, 'default' => 0);
					$myFields['selledon'] = array('type' => 'VARCHAR', 'constraint'=> 500, 'default' => null);
					$myFields['sellerid'] = array('type' => 'INT', 'constraint'=> 12);
					$myFields['sellerusername'] = array('type' => 'VARCHAR', 'constraint'=> 500);
					$myFields['selledto'] = array('type' => 'VARCHAR', 'constraint'=> 500, 'default' => null);
					$myFields['selledtoid'] = array('type' => 'INT', 'constraint'=> 12, 'default' => null);
					$myFields['description'] = array('type' => 'LONGTEXT', 'default' => null);
					$myFields['selledtimes'] = array('type' => 'INT', 'constraint'=> 12, 'default' => 0);
	                if(strpos($sectionName, ' ')){
	    		        $sectionNameData = str_replace(" ", "", $sectionName);    
	    		    }
	    		    else {
	    		        $sectionNameData = $sectionName;        
	    		    }

					$CreateSectionTable->addPrimaryKey('id');
					$CreateSectionTable->addField($myFields);
					$CreateSectionTable->createTable('section_'.strtolower($sectionNameData), TRUE);
					$myInputs = [
				        'id' => [
			                'type'           => 'INT',
			                'constraint'     => 12,
			                'auto_increment' => true,
				        ],
				        'inputLable' => [
			                'type'           => 'VARCHAR',
			                'constraint'     => 100,
				        ],
				        'inputName' => [
			                'type'           => 'VARCHAR',
			                'constraint'     => 100,
				        ],
				        'inputsTypes' => [
			                'type'           =>'VARCHAR',
			                'constraint'     => 500,
				        ],
				        'validationrull' => [
			                'type'           => 'VARCHAR',
			                'constraint'     => 500,
			                'null'           => true,
				        ],
				        'inputsIcone' => [
			                'type'           => 'VARCHAR',
			                'constraint'     => '100',
				        ],
					];
					$CreateInputsConfigTable->addPrimaryKey('id');
					$CreateInputsConfigTable->addField($myInputs);
					$CreateInputsConfigTable->createTable('inputs_'.strtolower($sectionNameData), TRUE);
					$inputsData = array();
					$db      = \Config\Database::connect();
					
					$builder = $db->table('inputs_'.strtolower($sectionNameData));
					
					//defaults 
					$inputsDataAdd[0] = [
						'inputLable' => 'price',
						'inputName' => 'price',
						'inputsTypes' => 'number',
						'validationrull' => 'numeric|required',
						'inputsIcone' => 'bx-dollar',
					];
					$inputsDataAdd[1] = [
						'inputLable' => 'description',
						'inputName' => 'description',
						'inputsTypes' => 'textaria',
						'validationrull' => 'permit_empty|regex_match[/^([a-zA-Z0-9 \_\-\:\,\!\?\)\(\*)])+$/im]',
						'inputsIcone' => 'bx-text',
					];
					$inputsDataAdd[2] = [
						'inputLable' => 'Product image',
						'inputName' => 'prodimagezeh',
						'inputsTypes' => 'prodimagezeh',
						'validationrull' => 'is_image[prodimagezeh]|ext_in[prodimagezeh,png,jpg,jpeg]|mime_in[prodimagezeh,image/png,image/jpeg,image/jpg]|max_dims[prodimagezeh,1280,1280]|max_size[prodimagezeh,2048]',
						'inputsIcone' => 'bx-image',
					];
					$inputsDataAdd[3] = [
						'inputLable' => 'Product title',
						'inputName' => 'titleprodzeh',
						'inputsTypes' => 'titleprodzeh',
						'validationrull' => 'required|regex_match[/^([a-zA-Z0-9 \_\-\:\,\!\?\)\(\*)])+$/i]',
						'inputsIcone' => 'bx-text',
					];	
					foreach($FormInputs as $key => $value) {
						foreach ($value as $ke => $va) {
							$datas[$ke] = $va;	
						}
						$inputsData = array();
						foreach ($datas as $keys => $values) {
							if($keys == 'validationrull'){
								$inputsData[$keys] = implode('|',$values);
							}
							
							else {
								$inputsData[$keys] = $values;	
							}
						}
						$builder->insert($inputsData);
					}
					foreach($inputsDataAdd as $pk => $pv){
						$dataAdd = [];
						foreach($pv as $ts => $bs){
							$dataAdd[$ts] = $bs;
						}
						$builder->insert($dataAdd);
					}

					$myStyleConfigTable = [
						'id' => [
			                'type'           => 'INT',
			                'constraint'     => 12,
			                'auto_increment' => true
				        ],
				        'cellsHeads' => [
			                'type'           => 'VARCHAR',
			                'constraint'     => 100,
				        ],
				        'cellsTypes' => [
			                'type'           =>'VARCHAR',
			                'constraint'     => 500,
				        ],
				        'cellsName' => [
			                'type'           =>'VARCHAR',
			                'constraint'     => 100,
				        ],
					];
					if(strpos($sectionName,' ')){
						$tableName = 'table_'.str_replace(' ' , '', strtolower($sectionName));	
					}
					else {
						$tableName = strtolower($sectionName);
					}
								
					//Tables Style
					//Creating Database Table
					$MytableName = 'table_'.$tableName;
					$CreateTabelConfigTable->addPrimaryKey('id');
					$CreateTabelConfigTable->addField($myStyleConfigTable);
					$CreateTabelConfigTable->createTable($MytableName, TRUE);

					//starting add data in table
					$x = 0;
					$buildertables = $db->table($MytableName);
					$arraya = array_values($DataableConfig);

					foreach ($arraya[0]['cellsHeads'] as $key => $value) {
						if(strpos($arraya[0]['cellsHeads'][$x], ' ') !== false){
							$cellname = str_replace(' ', '', strtolower($arraya[0]['cellsHeads'][$x]));
						}
						else {
							$cellname = $arraya[0]['cellsHeads'][$x];
						}
						$cellsHeads[$x] = [
							'cellsHeads' => $arraya[0]['cellsHeads'][$x], 
							'cellsTypes' => $arraya[0]['cellsTypes'][$x],
							'cellsName' => $arraya[0]['cellsName'][$x]
						];
						$buildertables->insert($cellsHeads[$x]);
						$x++;
					}
					$DefaultsRaws[0] = [
						'cellsHeads' => 'Price',
						'cellsTypes' => 'price',
						'cellsName' => 'price'
					];
					$DefaultsRaws[1] = [
						'cellsHeads' => 'Add Date',
						'cellsTypes' => 'date',
						'cellsName' => 'addon'
					];
					foreach($DefaultsRaws as $ki => $val){
						$buildertables->insert($DefaultsRaws[$ki]);
					}
					$modalTitle = '<p class="text-success">Success</p>';
	            	$modalContent = '<p class="text-success">You successful create a new section.</p>';
					$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-dialog-centered', "1", "1", "1", "1", "0");
			    }
			    $response["csrft"] =  csrf_hash();
			    header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
			else {
				header('location:'.base_url().'/login');
				exit();
			}
		}
		else {
            header('location:'.base_url().'/login');
            exit();
        } 		
	}
}
