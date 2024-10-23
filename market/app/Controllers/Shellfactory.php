<?php

namespace App\Controllers;
use App\Models\ShellModel;
use App\Models\UsersModel;
use App\Models\SectionsModel;
use App\Models\NotificationsModel;
use App\Models\BinslistModel;

use monken\TablesIgniter;

class Shellfactory extends BaseController
{
	private function sectionControl(){
		if(verifysection('1') != null){
    		$verify = verifysection('1');
	    	if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9'){
	    		header('location:'.base_url());
	    		exit();
	    	}
	    	else if($verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9') {
	    		$view = 'maintenance';
	    		return ['view' => $view, 'sellersactivate' => 0];
	    		
	    	}
	    	else if($verify['sellersactivate'] == '0' && session()->get('suser_groupe') != '9'){
	    		header('location:'.base_url());
	    		exit();
	    	}
	    	else {
	    		$view = 'shellfactory';
	    		return ['view' => $view, 'sellersactivate' => $verify['sellersactivate']];
	    	}
    	}
    	else {
    		header('location:'.base_url());
    		exit();
    	}
	}
	public function index(){
		if(session()->get("logedin") == "1"){
			$sectionVerif = $this->sectionControl();
			$data = [];
			if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
				$modelusers = new UsersModel;
				$res = $modelusers->where('id' , session()->get('suser_id'))->findAll();
				$data['sellerbalance'] = $res[0]['seller_balance'];
			}
			$settings = fetchSettings();
			$mycart = getCart();
			$data["nbitemscart"] = $mycart[0];
			$data["cartInnerHtml"] = $mycart[1];
			$data["settings"] = $settings;
			$data["sectionName"] = "Upload new Shells";
			$data["sellersactivate"] = $sectionVerif['sellersactivate'];
			echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view($sectionVerif['view']);
            echo view("assets/footer");
            echo view("assets/scripts");	
		}
		else {
			header('location:'.base_url().'/login');
			exit();
		}
	}

	function uploader(){
		if(session()->get("suser_groupe") != '9' && session()->get("suser_groupe") != '1'){
			exit();
		}
		else {
			$sectionVerif = $this->sectionControl();
			$verifyme = verifysection('1');   	
			ini_set('output_buffering','on');
			ini_set('zlib.output_compression', 0);
			ini_set('max_execution_time', '3000');
			ob_implicit_flush();
			$response = service('response');
			$response->send();
			ob_end_flush();
			flush();
			$InputDataArray = json_decode($this->request->getPost('data'), true);
			if(count((array)$InputDataArray) > 0){
				$DataArray = array();
				foreach ($InputDataArray as $key => $value) {
					$ValueArray = explode('|', $value);
					for($i = 0; $i < count($ValueArray); $i++){
						if($ValueArray[$i] != ""){
							$DataArray[$i][$key] = $ValueArray[$i];
						}
					}
				}
				$Total = count($DataArray);
				$Valid = 0;
				$Invalid = 0;
				$Duplicat = 0;
				$lines = '';
				$x = 0;
				$p = 0;
				$countrysfortelegram = [];
				//regex Templates
				$hostTemplate = "/^http|https?:\/\/[\w\.]\/[\w\.]+$/";
				$iptemplate = "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/";
				$priceTemplate = "/^[0-9]{1,50}$/";
				//end Regex Templates
				$binsModel = new BinslistModel;
				foreach ($DataArray as $key => $value) {
					//usleep(500);			
					$DataLineToaddDatabse = [];
					$p++;
					$isertResults = [];
					if(isset($DataArray[$x]['host']) && $value['host'] != ''
						&& preg_match($hostTemplate, $value["host"])  
					){
						$ShellModel = new ShellModel;
						$ifExists = $ShellModel->where('host', $value["host"])->findAll();
						if(count($ifExists) > 0){
							$Duplicat++;
							//$line .= '<p class="text-warning"><small>'.implode('|', $DataArray[$x]).'</small></p>';
						}
						else {
						//vars
							$DataLineToaddDatabse["host"] = $value["host"];
							//ip
							/**if(isset($DataArray[$x]["ip"])){
								if(preg_match($iptemplate, $value["ip"])){
									$DataLineToaddDatabse["ip"] = $value["ip"];
								}
								else {
									$DataLineToaddDatabse["ip"] = 'N/A';
								}
							}
							else {
								$DataLineToaddDatabse["ip"] = 'N/A';
							}**/
							$ShellCheker = shellchecker($host);
							$date = new \DateTime();
							$DataLineToaddDatabse["sellerid"] = session()->get('suser_id');
							$DataLineToaddDatabse["sellerusername"] = session()->get('suser_username');
							//the Insert
							$ShellModel->save($DataLineToaddDatabse);
							$Valid++;
						}
					}
					else {
						if(!empty($DataArray[$x])){
							$line = '';
							foreach ($DataArray[$x] as $keyt => $valuet) {
								if($valuet != "" && $valuet != " " && $valuet != "|" && $valuet != " |"){
									$line .= $valuet.'|';
								}
							}
							$lines .= str_replace(' ', '', $line).'<br/>';
							$Invalid++;
						}
					}
					$isertResults["progress"] = intval($p/$Total * 100); 
					$isertResults['total'] = $Total;
					$isertResults['valid'] = $Valid;
					$isertResults['invalid'] = $Invalid;
					$isertResults['duplicate'] = $Duplicat;
					$isertResults['line'] = $lines;
					$x++;
					if($x % 50 == 0 || $p == count($DataArray)){
						echo json_encode($isertResults);
						ob_flush();
						flush();
						//$lines = '';
					}				
				}
				//Do Notifications
				$dataNotif = [
					'subject' => 'Shell Store ',
					'text' => 'New Shell\'s was added with '.$Valid.' Shell\'s',
					'url' => base_url().'/shell'
				];
				$modelNotif = new NotificationsModel;
				$modelNotif->save($dataNotif);
				//Update section Item number
				$sectionModel = new SectionsModel;
				$thissection = $sectionModel->where('identifier', '5')->findAll();
				if(count($thissection) > 0){
					$newNbsectionItem = $thissection[0]["itemsnumbers"] + $Valid;
					//var_dump($newNbsectionItem);
					$dataUpdateSection = [
						'itemsnumbers' => $newNbsectionItem
					];
					$sectionModel->update($thissection[0]["id"], $dataUpdateSection);
				}
				//update user Items number
				$usersModel = new UsersModel;
				$getUser = $usersModel->where('id', session()->get('suser_id'))->findAll();
				if(count($getUser) > 0){
					$newUserNbItems = $getUser[0]['seller_nbobjects']+$Valid;
					$dataUpdateUserObject = [
						'seller_nbobjects' => $newUserNbItems
					];
					$usersModel->update($getUser[0]['id'], $dataUpdateUserObject);
				}
				//do Telegram Notification
				if($settings[0]['telenotif'] == '1'){
					$unicArraycountry = array_count_values($countrysfortelegram);
					$telecountrys = "";
					foreach ($unicArraycountry as $k => $uu){
                        $telecountrys .= '● '.ucfirst($k).' ('.$uu.')'.PHP_EOL;						    
					}
					$teletext = 'New Shell\'s was added with '.$Valid.' shell\'s.'.PHP_EOL.PHP_EOL.date('d/m/Y').PHP_EOL.PHP_EOL.$telecountrys.PHP_EOL.'Click here to buy cards.'.PHP_EOL.base_url();
                    telegram($settings[0]['telebot'], $settings[0]['chatid'], $teletext);
			    }
				return $this->response->setJSON('');
			}
			else {
				exit('Nice try ;)');
			}
		}
	}
}