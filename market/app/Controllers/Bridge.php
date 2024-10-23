<?php
namespace App\Controllers;
use App\Models\UsersModel;
use App\Models\NotificationsModel;
use App\Models\NewsModel;

class Bridge extends BaseController
{
    public function index()
    {
        if(session()->get("logedin") == "1"){
            $data = [];
            $settings = fetchSettings();
            $usersModel = new UsersModel;
            $userLogein = $usersModel->where('`id`' , session()->get('suser_id'))->findAll();
            if($userLogein[0]['active'] == '1' && $settings[0]['depoactivate'] == '0'){
            	header('location:'.base_url());
            	exit();
            }
            else if($userLogein[0]['active'] == '0' && $settings[0]['depoactivate'] == '0'){
            	header('location:'.base_url());
            	exit();
            }
            else if($userLogein[0]['active'] == '1' && $settings[0]['depoactivate'] == '1'){
            	header('location:'.base_url());
            	exit();
            }
            else {
	            $mycart = getCart();
	            $data["sectionName"] = 'Home';
	            $data["nbitemscart"] = $mycart[0];
	            $data["cartInnerHtml"] = $mycart[1];
	            $data["settings"] = $settings;
	            echo view("assets/header", $data);
	            echo view("assets/aside");
	            echo view("assets/topbarre");
	            echo view("bridge");
	            echo view("assets/footer");
	            echo view("assets/scripts"); 
            }
        }
        else {
            header('location:'.base_url().'/login');
            exit();
        }
    }
}