<?php

namespace App\Controllers;
use App\Models\CardsModel;
use App\Models\UsersModel;
use App\Models\SectionsModel;
use App\Models\NotificationsModel;
use App\Models\BinslistModel;

use monken\TablesIgniter;
class Factory extends BaseController
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
	    		$view = 'factory';
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
			$data["sectionName"] = "Upload new databse";
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
				$cctemplate = "/^\d{15,19}$/";
				$exptemplate = "/^(0[0-9]|1[0-2])\/(202[3-9]{1}|203[0-9]{1}|204[0-9]{1}|[2-9]{1}[3-9]{1}|[3-9]{1}[0-9]{1}|[4-9]{1}[0-9]{1})$/";
				$expmtemplate = "/^(0[1-9]|1[0-2]|[1-9]{1})$/";
				$expytemplate = "/^(202[3-9]{1}|203[0-9]{1}|204[0-9]{1}|[2-9]{1}[3-9]{1}|[3-9]{1}[0-9]{1}|[4-9]{1}[0-9]{1})$/";
				$cvvtemplate = "/^\d{3,5}$/";
				$namestemplate = "/^\p{L}+[\s\p{L}]*$/u";
				$addresstemplate = "/^[a-zA-Z0-9\s\,\.\#\-\/]{5,200}$/u";
				$citytemplate = "/^[\p{L}0-9\s\,\.\#\-\/]{2,50}$/u";
				$statstemplate = "/^[\p{L}0-9\s\,\.\#\-\/]{2,50}$/u";
				$countrytemplate = "/^[\p{L}]+(?:[\s-][\p{L}]+)*$/u";
				$ziptemplate = "/^[A-Za-z0-9\s\-]{5,10}$/";
				$phonetemplate = "/^[0-9\s\-]{5,20}$/";
				$dobtemplate = "/^(0[1-9]|[1-2][0-9]|3[0-1])[\/|\-|\s](0[1-9]|1[0-2])[\/|\-|\s]([0-9]{2}|[0-9]{4})$/";
				$emailtemplate = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
				$iptemplate = "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/";
				$othertemplate = "/^([^\x00-\x1F\x7F<>\"\'%&;=\(\)\{\}\[\]\\\^\$\*\+\?\.\/:@|]+)$/";
				$ssntemplate = "/^(?:\d{3}-\d{2}-\d{4}|\d{3}\s\d{2}\s\d{4}|\d{9})$/";
				$basetemplate = "/^[a-zA-Z0-9 \_]{2,30}$/";
				$priceTemplate = "/^[0-9]{1,50}$/";
				//end Regex Templates
				$binsModel = new BinslistModel;
				foreach ($DataArray as $key => $value) {
					//usleep(500);			
					$DataLineToaddDatabse = [];
					$p++;
					$isertResults = [];
					if(isset($DataArray[$x]['cc']) && $value['cc'] != ''
						&& preg_match($cctemplate, $value["cc"])  
						&& isset($DataArray[$x]['expmy']) 
						&& preg_match($exptemplate, $value['expmy'])
						&& isset($DataArray[$x]['cvv']) 
						&& preg_match($cvvtemplate, $value['cvv'])
						|| isset($DataArray[$x]['cc']) && $value['cc'] != ''
						&& preg_match($cctemplate, $value["cc"])
					   	&& isset($DataArray[$x]['expm'])
					   	&& preg_match($expmtemplate, $value['expm'])
					   	&& isset($DataArray[$x]['expy']) 
					   	&& preg_match($expytemplate, $value['expy'])
					   	&& isset($DataArray[$x]['cvv']) 
						&& preg_match($cvvtemplate, $value['cvv'])
					){
						$cardsModel = new CardsModel;
						$ifExists = $cardsModel->where('number', $value["cc"])->findAll();
						if(count($ifExists) > 0){
							$Duplicat++;
							//$line .= '<p class="text-warning"><small>'.implode('|', $DataArray[$x]).'</small></p>';
						}
						else {
						//vars
							$DataLineToaddDatabse["number"] = $value["cc"];
							$DataLineToaddDatabse["cvv"] = $value["cvv"];
							//cc expiry month
							if(isset($DataArray[$x]["expmy"])){
								$DataLineToaddDatabse["expiration"] = $value["expmy"];
							}
							else if(isset($DataArray[$x]["expm"]) && isset($DataArray[$x]["expy"])){
								$DataLineToaddDatabse["expiration"] = $value["expm"].'/'.$value["expy"];
							}
							//cc Holder Name
							if(isset($DataArray[$x]["fullname"])){
								if(preg_match($namestemplate, $value["fullname"])){
									$DataLineToaddDatabse["fullname"] = $value["fullname"];
								}
								else {
									$DataLineToaddDatabse["fullname"] = 'N/A';
								}
							}
							else if(isset($DataArray[$x]["fname"]) && isset($DataArray[$x]["lname"])){
								if(preg_match($namestemplate, $value["fname"]) && preg_match($namestemplate, $value["lname"])){
									$DataLineToaddDatabse["fullname"] = $value["lname"].' '.$value["lname"];
								}
								else {
									$DataLineToaddDatabse["fullname"] = 'N/A';
								}
							}
							else {
								$DataLineToaddDatabse["fullname"] = 'N/A';
							}
							//address
							if(isset($DataArray[$x]["address"])){
								if(preg_match($addresstemplate, $value["address"])){
									$DataLineToaddDatabse["address"] = $value["address"];
								}
								else {
									$DataLineToaddDatabse["address"] = 'N/A';
								}
							}
							else {
								$DataLineToaddDatabse["address"] = 'N/A';
							}
							//city
							if(isset($DataArray[$x]["city"])){
								if(preg_match($citytemplate, $value["city"])){
									$DataLineToaddDatabse["city"] = $value["city"];
								}
								else {
									$DataLineToaddDatabse["city"] = 'N/A';
								}
							}
							else {
								$DataLineToaddDatabse["city"] = 'N/A';
							}
							//state
							if(isset($DataArray[$x]["state"])){
								if(preg_match($statstemplate, $value["state"])){
									$DataLineToaddDatabse["state"] = $value["state"];
								}
								else {
									$DataLineToaddDatabse["state"] = 'N/A';
								}
							}
							else {
								$DataLineToaddDatabse["state"] = 'N/A';
							}
							//zip
							if(isset($DataArray[$x]["zip"])){
								if(preg_match($ziptemplate, $value["zip"])){
									$DataLineToaddDatabse["zip"] = $value["zip"];
								}
								else {
									$DataLineToaddDatabse["zip"] = 'N/A';
								}
							}
							else {
								$DataLineToaddDatabse["zip"] = 'N/A';
							}
							//phone
							if(isset($DataArray[$x]["phone"])){
								if(preg_match($phonetemplate, $value["phone"])){
									$DataLineToaddDatabse["phone"] = $value["phone"];
								}
								else {
									$DataLineToaddDatabse["phone"] = 'N/A';
								}
							}
							else {
								$DataLineToaddDatabse["phone"] = 'N/A';
							}
							//dob
							if(isset($DataArray[$x]["dob"])){
								if(preg_match($dobtemplate, $value["dob"])){
									$DataLineToaddDatabse["dob"] = $value["dob"];
								}
								else {
									$DataLineToaddDatabse["dob"] = 'N/A';
								}
							}
							else {
								$DataLineToaddDatabse["dob"] = 'N/A';
							}
							//email
							if(isset($DataArray[$x]["email"])){
								if(preg_match($emailtemplate, $value["email"])){
									$DataLineToaddDatabse["email"] = $value["email"];
								}
								else {
									$DataLineToaddDatabse["email"] = 'N/A';
								}
							}
							else {
								$DataLineToaddDatabse["email"] = 'N/A';
							}
							//ip
							if(isset($DataArray[$x]["ip"])){
								if(preg_match($iptemplate, $value["ip"])){
									$DataLineToaddDatabse["ip"] = $value["ip"];
								}
								else {
									$DataLineToaddDatabse["ip"] = 'N/A';
								}
							}
							else {
								$DataLineToaddDatabse["ip"] = 'N/A';
							}
							//ssn
							if(isset($DataArray[$x]["ssn"])){
								if(preg_match($ssntemplate, $value["ssn"])){
									$DataLineToaddDatabse["ssn"] = $value["ssn"];
								}
								else {
									$DataLineToaddDatabse["ssn"] = 'N/A';
								}
							}
							else {
								$DataLineToaddDatabse["ssn"] = 'N/A';
							}
							//others
							if(isset($DataArray[$x]["other"])){
								if(preg_match($namestemplate, $value["other"])){
									$DataLineToaddDatabse["other"] = $value["other"];
								}
								else {
									$DataLineToaddDatabse["other"] = 'N/A';
								}
							}
							else {
								$DataLineToaddDatabse["other"] = 'N/A';
							}
							if($settings[0]['baseapproved'] == '0'){
								$DataLineToaddDatabse["baseapproved"] = '1';
							}
						//end vars
							$bin = substr($DataLineToaddDatabse['number'], 0,6);
							$GetCardInfos = $binsModel->where('iin_start', $bin)->findAll();
							if(count($GetCardInfos) > 0){
								$DataLineToaddDatabse['scheme'] = $GetCardInfos[0]["scheme"] ? $GetCardInfos[0]["scheme"] : "N/A";
								$DataLineToaddDatabse['type'] = $GetCardInfos[0]["type"] ? $GetCardInfos[0]["type"] : "N/A";
								$DataLineToaddDatabse['brand'] = $GetCardInfos[0]["brand"] ? $GetCardInfos[0]["brand"] : "N/A";
								$DataLineToaddDatabse['country'] = $GetCardInfos[0]["country"] ? $GetCardInfos[0]["country"] : "N/A";
								$DataLineToaddDatabse['bank'] = $GetCardInfos[0]["bank_name"] ? $GetCardInfos[0]["bank_name"] : "N/A";
								$countrysfortelegram[] = $GetCardInfos[0]["country"] ? $GetCardInfos[0]["country"] : "UN";
							}
							else {
								$DataLineToaddDatabse['scheme'] = 'N/A';
								$DataLineToaddDatabse['type'] = 'N/A';
								$DataLineToaddDatabse['brand'] = 'N/A';
								$DataLineToaddDatabse['country'] = 'N/A';
								$DataLineToaddDatabse['bank'] = 'N/A';
								$countrysfortelegram[] = "UN";
								//$DataLineToaddDatabse['countryalpha2'] = 'NA';
							}

							$date = new \DateTime();
							$DataLineToaddDatabse["base"] = $date->format('d_m_Y');
							if(preg_match($basetemplate, $this->request->getPost('base'))){
								$DataLineToaddDatabse["base"] .= '_'.str_replace(' ', '_',ucfirst(strtolower($this->request->getPost('base'))));
							}
							if(preg_match($priceTemplate, $this->request->getPost('price'))){
								$DataLineToaddDatabse["price"] = $this->request->getPost('price');
							}
							else {
								$DataLineToaddDatabse["price"] = '0';
							}
							$DataLineToaddDatabse["sellerid"] = session()->get('suser_id');
							$DataLineToaddDatabse["sellerusername"] = session()->get('suser_username');
							if(preg_match("/^([0-9]{1,10})$/", $this->request->getPost('refund'))){
								$DataLineToaddDatabse["refun"] = $this->request->getPost('refund');
							}
							else {
								$DataLineToaddDatabse["refun"] = '1';
							}
							//the Insert
							$cardsModel->save($DataLineToaddDatabse);
							$Valid++;
							//$line .= '<p class="text-success"><small>'.implode('|', $DataArray[$x]).'</small></p>';
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
					'subject' => 'CC Store ',
					'text' => 'New databse was added with '.$Valid.' Cards',
					'url' => base_url().'/cards'
				];
				$modelNotif = new NotificationsModel;
				if($settings[0]['baseapproved'] == '0'){
					$modelNotif->save($dataNotif);
				
					//Update section Item number
					$sectionModel = new SectionsModel;
					$thissection = $sectionModel->where('identifier', '1')->findAll();
					if(count($thissection) > 0){
						$newNbsectionItem = $thissection[0]["itemsnumbers"] + $Valid;
						$dataUpdateSection = [
							'itemsnumbers' => $newNbsectionItem
						];
						$sectionModel->update($thissection[0]["id"], $dataUpdateSection);
					}
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
					$teletext = 'New CC Base was added with '.$Valid.' Cards.'.PHP_EOL.PHP_EOL.date('d/m/Y').PHP_EOL.PHP_EOL.$telecountrys.PHP_EOL.'Click here to buy cards.'.PHP_EOL.base_url();
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