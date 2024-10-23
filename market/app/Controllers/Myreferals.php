<?php

namespace App\Controllers;
use App\Models\MyitemsModel;
use App\Models\UsersModel;
use App\Models\CardsModel;
use App\Models\SectionsModel;
use App\Models\MyreferalsExtendedModel;
use monken\TablesIgniter;

class Myreferals extends BaseController
{
	public function index()
	{
		if(session()->get("logedin") == "1"){
			$data = [];
			$settings = fetchSettings();
			$mycart = getCart();
			$data["nbitemscart"] = $mycart[0];
			$data["cartInnerHtml"] = $mycart[1];
			$data["settings"] = $settings;
			$data["sectionName"] = "My Referrals";
			echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("myreferals");
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
    		if(session()->get("logedin") == '1'){
    			$model = new MyreferalsExtendedModel();
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
}

