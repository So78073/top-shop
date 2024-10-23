<?php

namespace App\Controllers;
use App\Models\NotificationsModel;
use App\Models\UsersModel;
use App\Models\CardsModel;
class Baselist extends BaseController
{
	public function index($basename = null){
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
			$data["baseName"] = $basename;
			$data["sectionName"] = "Base List";
            echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("baselist");
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
    		if(session()->get("logedin") == true){
                $basename = $this->request->getGet("base");
    			$model = new CardsModel;
    			$Results = $model->where(['base' => $basename, 'baseapproved' => '0', 'selled' => '0'])->findAll();
    			$countresults = count($Results);
    			if($countresults > 0){
    				foreach ($Results as $value) {
    	                $id = '#'.$value["id"];
                        $data = $value['number'].'|'.$value['expiration'].'|'.$value['cvv'].'|'.$value['fullname'].'|'.$value['phone'].'|'.$value['dob'].'|'.$value['address'].'|'.$value['zip'].'|'.$value['city'].'|'.$value['state'].'|'.$value['email'].'|'.$value['ssn'].'|'.$value['ip'].'|'.$value['other'].'|$'.$value['price'];
    					
					    $output['data'][] = array(
    		        		esc($id),
    		        		$data,
    					);
    				}
    				echo json_encode($output);
    				exit();
    			}
    			else {
    				$output['data'][] = array(
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

    public function downloadbases(){
        if(session()->get("suser_groupe") == '9'){
            $cc = '';
            $model = new CardsModel;
            $base= $this->request->getGet('base');
            $Results = $model->where(['base' => $base, 'baseapproved' => '0', 'selled' => '0'])->findAll();
            $countres = count($Results);
            if($countres > 0){
                foreach ($Results as $key => $value) {
                    $cc .= $value['number'].'|'.$value['expiration'].'|'.$value['cvv'].'|'.$value['fullname'].'|'.$value['phone'].'|'.$value['dob'].'|'.$value['address'].'|'.$value['zip'].'|'.$value['city'].'|'.$value['state'].'|'.$value['email'].'|'.$value['ssn'].'|'.$value['ip'].'|'.$value['other'].PHP_EOL;
                }
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-disposition: attachment; filename=cc.txt');
                header('Content-Length: '.strlen($cc));
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Expires: 0');
                header('Pragma: public');
                echo $cc;
                exit;
            }
            else {
                header('location:'.base_url().'/admindashboard');
                exit();
            }
        }
        else {
            header('location:'.base_url().'/admindashboard');
            exit();
        }
    }
}