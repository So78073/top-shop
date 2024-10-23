<?php

namespace App\Controllers;
use App\Models\NotificationsModel;
use App\Models\SectionsModel;
use App\Models\UsersModel;
use App\Models\ReportsModel;
use App\Models\ReportsDetailsModel;
use App\Models\MyitemsModel;

class Reportdetails extends BaseController {
	protected $db;
    public function __construct(){
        $this->db = \Config\Database::connect();
    }
	public function index($id = null){
        if(null !== $id){
            $data = [];
            $reportModel = new ReportsModel;
            $detailsModel = new ReportsDetailsModel;
            if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '4'){
            	$getReport = $this->db->table("reports")->where("`id`='".$id."'")->get();
            }
            else {
            	$getReport = $this->db->table("reports")->where("`id`='".$id."' AND `buyerid` = '".session()->get('suser_id')."'")->orwhere("`id`='".$id."' AND `sellerid` ='".session()->get('suser_id')."'")->get();
            }

            if(count($getReport->getResultArray()) > 0){
            	$getReportDetails = $detailsModel->where(['reportid' => $id])->findAll();
	            $data['report'] = $getReport->getResultArray()[0];            
	            $data['reportdetails'] = $getReportDetails;
                $settings = fetchSettings();
                $mycart = getCart();
                $data["nbitemscart"] = $mycart[0];
                $data["cartInnerHtml"] = $mycart[1];
                $data["settings"] = $settings;
                $model = new UsersModel;
                $Results = $model->where('id' , session()->get("suser_id"))->findAll();
                $data["results"] = $Results;
                $data["sectionName"] = "Reports Details";
                echo view("assets/header", $data);
                echo view("assets/aside");
                echo view("assets/topbarre");
                echo view("reportdetails");
                echo view("assets/footer");
                echo view("assets/scripts");
                echo view("assets/reportsdetails");
            }
            else {
            	header('location:'.base_url());
            	exit();
            }
        }
        else {
            header('location:'.base_url());
            exit();
        }
    }
}