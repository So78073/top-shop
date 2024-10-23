<?php

namespace App\Controllers;
use App\Models\CpanelModel;
use App\Models\UsersModel;
use App\Models\SectionsModel;
use App\Models\NotificationsModel;
use App\Models\BinslistModel;

use monken\TablesIgniter;

class Cpanelfactory extends BaseController
{
	private function sectionControl(){
		if(verifysection('2') != null){
    		$verify = verifysection('2');
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
	    		$view = 'cpanelfactory';
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
			$data["sectionName"] = "Upload new Cpanels";
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
			$decodes = base64_decode(urldecode($this->request->getPost('data')));
			$InputDataArray = json_decode($decodes, true);
			if(count((array)$InputDataArray) > 0){
				$DataArray = array();
				foreach ($InputDataArray as $key => $value) {
					$ValueArray = explode('|', $value);
					for($i = 0; $i < count($ValueArray); $i++){
						if($ValueArray[$i] != ""){
							if($key == "password"){
								$DataArray[$i][$key] = $ValueArray[$i];
							}
							else {
								$DataArray[$i][$key] = $ValueArray[$i];
							}
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
				$hostTemplate = "/^http|https?:\/\/[\w\.]+:208[0-9]{1}\/[\w\.]+$/";
				$usernameTemplate = "/^[a-zA-Z0-9]([a-zA-Z0-9-]{0,30}[a-zA-Z0-9])?$/";
				$passwordTemplate = "/^[A-Za-z\d\@\$\!\%\*\?\&\{\}\(\)\[\]\<\>\~\#\_\-\,\+\;\.\£\^]{4,30}+$/";
				$priceTemplate = "/^[0-9]{1,50}$/";
				//end Regex Templates
				foreach ($DataArray as $key => $value) {
					$DataLineToaddDatabse = [];
					$p++;
					$isertResults = [];					
					if(isset($DataArray[$x]['host']) && $value['host'] != ''
						&& preg_match($hostTemplate, $value["host"])  
						&& isset($DataArray[$x]['username']) 
						&& isset($DataArray[$x]['password']) 
						&& preg_match($usernameTemplate, $value['username'])
						&& preg_match($passwordTemplate, $value['password'])
					)
					{
						$model = new CpanelModel;
						$ifExists = $model->where(['host' => $value["host"], 'username' => $value["username"]])->findAll();
						if(count($ifExists) > 0){
							//$line = '';
							//foreach ($DataArray[$x] as $keyt => $valuet) {
							//	if($valuet != "" && $valuet != " " && $valuet != "|" && $valuet != " |"){
							//		$line .= $valuet.'|';
							//	}
							//}
							//$lines .= str_replace(' ', '', $line).'<br/>';
							$Invalid++;

						}
						else {
						//vars
							$DataLineToaddDatabse["host"] = $value["host"];
							$DataLineToaddDatabse["username"] = $value["username"];
							$DataLineToaddDatabse["password"] = $value["password"];
						//end vars
							$cpanelData = explode('://', $DataLineToaddDatabse["host"]);
							if (substr($DataLineToaddDatabse["host"], 0, 8) === "https://") {
						        $DataLineToaddDatabse["port"] = 'HTTPS';
						    } 
							elseif (substr($DataLineToaddDatabse["host"], 0, 7) === "http://") {
						        $DataLineToaddDatabse["port"] = 'HTTP';
						    } 
						    else {
						        $DataLineToaddDatabse["port"] = 'HTTP';
						    }
							$cpanelhostname = $cpanelData[1];
							if(strpos('/',$cpanelData[1])){
								$cpanelDataCleanedarray = explode('/', $cpanelData[1]);
								$cpanelDataCleaneds = explode(':',$cpanelDataCleanedarray[0]);
								$cpanelDataCleaned = $cpanelDataCleaneds[0];
							}
							else {
								$cpanelDataCleaneds = explode(':',$cpanelData[1]);
								$cpanelDataCleaned = $cpanelDataCleaneds[0];
							}
							$parts = explode(".", $cpanelDataCleaned);
							$DataLineToaddDatabse["tld"] = end($parts);
							$GetCpanelip =  gethostbyname($cpanelDataCleaned);
							if(filter_var($GetCpanelip, FILTER_VALIDATE_IP) && $GetCpanelip != '127.0.0.1'){
						        $json = file_get_contents('https://api.ipregistry.co/'.$GetCpanelip.'?key=pf0f4w9q20rzwwoz' ,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
						'subject' => 'Cpanel Store ',
						'text' => 'New databse was added with '.$Valid.' Cpanel',
						'url' => base_url().'/cpanel'
					];
					$modelNotif = new NotificationsModel;
					$modelNotif->save($dataNotif);
					//Update section Item number
					$sectionModel = new SectionsModel;
					$thissection = $sectionModel->where('identifier', '2')->findAll();
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
						$teletext = 'New Cpanel Base was added with '.$Valid.' Cpanel.'.PHP_EOL.PHP_EOL.date('d/m/Y').PHP_EOL.PHP_EOL.$telecountrys.PHP_EOL.'Click here to buy cpanels.'.PHP_EOL.base_url();
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