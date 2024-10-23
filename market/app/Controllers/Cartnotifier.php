<?php

namespace App\Controllers;
class Cartnotifier extends BaseController
{
	public function index(){
	    if($this->request->isAJAX()){
    		if(session()->get("logedin") == "1"){
    			$data = [];
    			$settings = fetchSettings();
    			$html = getCart();
    			$response["html"] = $html[1];
    			echo json_encode($response);
    		}
    		else {
    			header('location:'.base_url().'/login');
    			exit();
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}
	
}