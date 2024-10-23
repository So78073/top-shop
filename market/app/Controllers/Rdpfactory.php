<?php

namespace App\Controllers;
use App\Models\RdpModel;
use App\Models\UsersModel;
use App\Models\SectionsModel;
use App\Models\NotificationsModel;
use App\Models\BinslistModel;

use monken\TablesIgniter;

class Rdpfactory extends BaseController
{
	private function sectionControl(){
		if(verifysection('3') != null){
    		$verify = verifysection('3');
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
	    		$view = 'rdpfactory';
	    		return ['view' => $view, 'sellersactivate' => $verify['sellersactivate']];
	    	}
    	}
    	else {
    		header('location:'.base_url());
    		exit();
    	}
	}
	public function index(){
		if(session()->get("suser_groupe") == '1' || session()->get("suser_groupe") == '9'){
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
			$data["sectionName"] = "Upload new RDP's";
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
		
		$settings = fetchSettings();
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
			//$decodes = base64_decode(urldecode($this->request->getPost('data')));
			$InputDataArray = json_decode($this->request->getPost('data'), true);
			//var_dump($decodes);
			//var_dump($InputDataArray);
			//var_dump($this->request->getPost());
			//exit();
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
				$hostTemplate = "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/";
				$usernameTemplate = "/^[a-zA-Z0-9]([a-zA-Z0-9-]{0,30}[a-zA-Z0-9])?$/";
				$passwordTemplate = "/^[A-Za-z\d\@\$\!\%\*\?\&\{\}\(\)\[\]\<\>\~\#\_\-\,\+\;\.\£\^]{4,30}+$/";
				$systemTemplate = "/^[a-zA-Z0-9 \-\_]{2,30}$/";
				$ramTemplate = "/^[a-zA-Z0-9]{2,20}$/";
				$hddsizeTemplate = "/^[a-zA-Z0-9]{2,20}$/";
				$priceTemplate = "/^[0-9]{1,50}$/";
				//end Regex Templates
				foreach ($DataArray as $key => $value) {
					$DataLineToaddDatabse = [];
					$p++;
					$isertResults = [];	



					if(isset($DataArray[$x]['host']) && $value['host'] != ''
						&& preg_match($hostTemplate, $value["host"])  
						&& isset($DataArray[$x]['user']) 
						&& isset($DataArray[$x]['pass']) 
						&& preg_match($usernameTemplate, $value['user'])
						&& preg_match($passwordTemplate, $value['pass'])
					)
					{

						$model = new RdpModel;
						$ifExists = $model->where(['host' => $value["host"], 'user' => $value["user"]])->findAll();
						if(count($ifExists) > 0){
							$Invalid++;
						}
						else {
						//vars
							$DataLineToaddDatabse["host"] = $value["host"];
							$DataLineToaddDatabse["user"] = $value["user"];
							$DataLineToaddDatabse["pass"] = $value["pass"];
							if(null !== $value["system"] && preg_match($systemTemplate, $value["system"] )){
								$DataLineToaddDatabse["system"] = $value["system"];
							}
							else {
								$DataLineToaddDatabse["system"] = 'N/A';
							}
							if(null !== $value["ram"] && preg_match($systemTemplate, $value["ram"] )){
								$DataLineToaddDatabse["ram"] = $value["ram"];
							}
							else {
								$DataLineToaddDatabse["ram"] = 'N/A';
							}
							if(null !== $value["hddsize"] && preg_match($systemTemplate, $value["hddsize"] )){
								$DataLineToaddDatabse["hddsize"] = $value["hddsize"];
							}
							else {
								$DataLineToaddDatabse["hddsize"] = 'N/A';
							}
						//end vars
							
							if(filter_var($DataLineToaddDatabse["host"], FILTER_VALIDATE_IP) && $DataLineToaddDatabse["host"] != '127.0.0.1'){
						        $json = file_get_contents('https://api.ipregistry.co/'.$DataLineToaddDatabse["host"].'?key=pf0f4w9q20rzwwoz' ,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	        					$Infosmyip = json_decode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	        					if(null !== $Infosmyip['connection']['domain']){
	        						$DataLineToaddDatabse['hoster'] = $Infosmyip['connection']['domain'];
	        					}
	        					else {
	        						$DataLineToaddDatabse['hoster'] = 'N/A';
	        					}
	        					if(null !== $Infosmyip['location']['country']['flag']['twemoji']){
	        						$DataLineToaddDatabse['country'] = $Infosmyip['location']['country']['flag']['twemoji'];
	        					}
	        					else {
	        						$DataLineToaddDatabse['country'] = 'N/A';
	        					}

	        					if(preg_match($priceTemplate, $this->request->getPost('price'))){
									$DataLineToaddDatabse["price"] = $this->request->getPost('price');
								}
								else {
									$DataLineToaddDatabse["price"] = '0';
								}
								$DataLineToaddDatabse["sellerid"] = session()->get('suser_id');
								$DataLineToaddDatabse["sellerusername"] = session()->get('suser_username');
								//the Insert
								$model->save($DataLineToaddDatabse);
								$Valid++;
							}
							else {
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
					//if($x % 10 == 0 || $p == count($DataArray)){
						echo json_encode($isertResults);
						ob_flush();
						flush();
					//}				
				}
				//exit();
				//Do Notifications
				if($Valid !== 0){
					$dataNotif = [
						'subject' => 'RDP Store ',
						'text' => 'New databse was added with '.$Valid.' RDP',
						'url' => base_url().'/rdp'
					];
					$modelNotif = new NotificationsModel;
					$modelNotif->save($dataNotif);
					//Update section Item number
					$sectionModel = new SectionsModel;
					$thissection = $sectionModel->where('identifier', '3')->findAll();
					if(count($thissection) > 0){
						$newNbsectionItem = $thissection[0]["itemsnumbers"] + $Valid;
						
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
						$teletext = 'New RDP Base was added with '.$Valid.' RDP.'.PHP_EOL.PHP_EOL.date('d/m/Y').PHP_EOL.PHP_EOL.$telecountrys.PHP_EOL.'Click here to buy cpanels.'.PHP_EOL.base_url();
	                    telegram($settings[0]['telebot'], $settings[0]['chatid'], $teletext);
				    }
				}
					
				return $this->response->setJSON('');
			}
			else {
				exit('Nice try ;)');
			}
		}
	}
}