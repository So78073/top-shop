<?php
namespace App\Controllers;
class Logout extends BaseController
{
	public function index(){
		if(session()->get('logedin') == "0"){
			header('location:'.base_url().'/login');
			exit();
		}
		else {
			session()->stop();
			session()->destroy();			
			header('location:'.base_url().'/login');
			exit();
		}	
	}
}