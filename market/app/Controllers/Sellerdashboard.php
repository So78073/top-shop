<?php

namespace App\Controllers;
use App\Models\CardsModel;
use App\Models\CpanelModel;
use App\Models\RdpModel;
use App\Models\SmtpModel;
use App\Models\ShellModel;
use App\Models\StoksModel;
use App\Models\UsersModel;
use App\Models\NotificationsModel;
use App\Models\SectionsModel;
use App\Models\BinslistModel;
use monken\TablesIgniter;
class Sellerdashboard extends BaseController
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
	    	else {
	    		$view = 'cards';
	    		return ['view' => $view, 'sellersactivate' => $verify['sellersactivate']];
	    	}
    	}
    	else {
    		header('location:'.base_url());
    		exit();
    	}
	}

	private function sectionsMenuCreator(){
		$sectionsModel = new SectionsModel;
		$activesections = $sectionsModel->findAll();
		$ActiveSections = [];
		foreach ($activesections as $key => $value) {
			if($value['sectionstatus'] == '1'){
				$ActiveSections[] = [$value["identifier"], $value['sectionlable'], $value['sectionicon']];
			}
		}
		return $ActiveSections;
	}

	private function doSectionCheks($id){
		$SectionMenue = $this->sectionsMenuCreator();
		if($id === null){
			$verify = verifysection($SectionMenue[0][0]);
		}
		else {
			$id = base64_decode($id);
			$verify = verifysection($id);
		}
		return ['sections' => $SectionMenue, 'verify' => $verify];
	}

	private function CreateTableHeaders($id, $status){
		if($status === null || $status == '0'){
			$status = 0;
			$checkth = '<th><input type="checkbox" id="chekall"></th>';
		}
		else {
			$checkth = '<th></th>';
		}
		$Infos = $this->doSectionCheks($id);

		if($Infos['verify']['identifier'] == '1'){
			$Tableheaders = '
				<table id="TabelcardsStock" data-status="'.$status.'" class="table table-border" style="width:100%">
					<thead>
						<tr>
							'.$checkth.'
							<th>Base</th>
							<th>Card</th>
							<th>Refundable</th>
							<th>Price</th>
							<th>Tools</th>
						</tr>
					</thead>
				</table>
			';

			//Tools
			$tools = '<div class="card-body">
				<div class="btn-group float-start">';
			switch ($status) {
				case '0' :
					$tools .='<a href="" class="btn btn-indigo btn-sm disabled" disabled><i class="bx bx-box"></i> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode($Infos['sections'][0][0]).'/1" class="btn btn-success btn-sm"><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode($Infos['sections'][0][0]).'/2" class="btn btn-danger btn-sm"><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				case '1' :
					$tools .='<a href="'.base_url().'/sellerdashboard/'.base64_encode($Infos['sections'][0][0]).'/0" class="btn btn-indigo btn-sm"><i class="bx bx-box"></i> Check Stock</a>
						<a href="" class="btn btn-success btn-sm disabled" disabled><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode($Infos['sections'][0][0]).'/2" class="btn btn-danger btn-sm " ><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				case '2' :
					$tools .='<a href="'.base_url().'/sellerdashboard/'.base64_encode($Infos['sections'][0][0]).'/0" class="btn btn-indigo btn-sm"><i class="bx bx-box"></i> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode($Infos['sections'][0][0]).'/1" class="btn btn-success btn-sm"><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="#" class="btn btn-danger btn-sm disabled" disabled><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				default :
					$tools .='<a href="" class="btn btn-indigo btn-sm disabled" disabled> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode($Infos['sections'][0][0]).'/1" class="btn btn-success btn-sm"> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode($Infos['sections'][0][0]).'/2" class="btn btn-danger btn-sm"> Check Refunds</a>';
				break;
			}
			$tools .='</div>
				<div class="btn-group float-end" id="inbtn"><div id="DivTabelStockTools" class="pr-1"></div>';
			if(session()->get("suser_groupe") == '9' 
			|| session()->get("suser_groupe") == '1' 
			&& $Infos['verify']['sellersactivate'] == '1' 
			&& SETTINGS['sellersystem'] == '1')
			{ 
				$tools .= '<a href="'.base_url().'/factory" id="add" class="btn btn-primary btn-sm"><i class="bx bx-upload"></i> Upload CC</a>';
			} 
			if(session()->get("suser_groupe") == '9' || session()->get("suser_groupe") == '1'){ 
				$tools .='<a href="'.base_url().'/bases" id="editBase" class="btn  btn-info btn-sm" ><i class="bx bx-edit"></i> Edit Bases</a>';
			}
			$tools .='</div></div>';
		}
		else if($Infos['verify']['identifier'] == '2'){
			$Tableheaders = '
				<table id="TabelcpanelStock" data-status="'.$status.'" class="table table-border" style="width:100%">
					<thead>
						<tr>
							'.$checkth.'
							<th>Host</th>
							<th>Protocole</th>
							<th>User</th>
							<th>Pass</th>
							<th>TLD</th>
							<th>Hoster</th>
							<th>Price</th>
							<th>Tools</th>
						</tr>
					</thead>
				</table>
			';

			//Tools
			$tools = '<div class="card-body">
				<div class="btn-group float-start">';
			switch ($status) {
				case '0' :
					$tools .='<a href="" class="btn btn-indigo btn-sm disabled" disabled><i class="bx bx-box"></i> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(2).'/1" class="btn btn-success btn-sm"><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(2).'/2" class="btn btn-danger btn-sm"><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				case '1' :
					$tools .='<a href="'.base_url().'/sellerdashboard/'.base64_encode(2).'/0" class="btn btn-indigo btn-sm"><i class="bx bx-box"></i> Check Stock</a>
						<a href="" class="btn btn-success btn-sm disabled" disabled><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(2).'/2" class="btn btn-danger btn-sm " ><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				case '2' :
					$tools .='<a href="'.base_url().'/sellerdashboard/'.base64_encode(2).'/0" class="btn btn-indigo btn-sm"><i class="bx bx-box"></i> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(2).'/1" class="btn btn-success btn-sm"><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="#" class="btn btn-danger btn-sm disabled" disabled><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				default :
					$tools .='<a href="" class="btn btn-indigo btn-sm disabled" disabled> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(2).'/1" class="btn btn-success btn-sm"> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(2).'/2" class="btn btn-danger btn-sm"> Check Refunds</a>';
				break;
			}
			$tools .='</div>
				<div class="btn-group float-end" id="inbtn"><div id="DivTabelStockTools" class="pr-1"></div>';
			if(session()->get("suser_groupe") == '9' 
			|| session()->get("suser_groupe") == '1' 
			&& $Infos['verify']['sellersactivate'] == '1' 
			&& SETTINGS['sellersystem'] == '1')
			{ 
				$tools .= '<a href="'.base_url().'/cpanelfactory" id="add" class="btn btn-primary btn-sm"><i class="bx bx-upload"></i> Upload Cpanel</a>';
			}
			$tools .='</div></div>';
		}
		else if($Infos['verify']['identifier'] == '3'){
			$Tableheaders = '
				<table id="TabelrdpStock" data-status="'.$status.'" class="table table-border" style="width:100%">
					<thead>
						<tr>
							'.$checkth.'
							<th>Host</th>
							<th>Country</th>
							<th>User</th>
							<th>Pass</th>
							<th>Hoster</th>
							<th>System</th>
							<th>RAM</th>
							<th>HDD Size</th>
							<th>Price</th>
							<th>Tools</th>
						</tr>
					</thead>
				</table>
			';

			//Tools
			$tools = '<div class="card-body">
				<div class="btn-group float-start">';
			switch ($status) {
				case '0' :
					$tools .='<a href="" class="btn btn-indigo btn-sm disabled" disabled><i class="bx bx-box"></i> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(3).'/1" class="btn btn-success btn-sm"><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(3).'/2" class="btn btn-danger btn-sm"><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				case '1' :
					$tools .='<a href="'.base_url().'/sellerdashboard/'.base64_encode(3).'/0" class="btn btn-indigo btn-sm"><i class="bx bx-box"></i> Check Stock</a>
						<a href="" class="btn btn-success btn-sm disabled" disabled><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(3).'/2" class="btn btn-danger btn-sm " ><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				case '2' :
					$tools .='<a href="'.base_url().'/sellerdashboard/'.base64_encode(3).'/0" class="btn btn-indigo btn-sm"><i class="bx bx-box"></i> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(3).'/1" class="btn btn-success btn-sm"><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="#" class="btn btn-danger btn-sm disabled" disabled><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				default :
					$tools .='<a href="" class="btn btn-indigo btn-sm disabled" disabled> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(3).'/1" class="btn btn-success btn-sm"> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(3).'/2" class="btn btn-danger btn-sm"> Check Refunds</a>';
				break;
			}
			$tools .='</div>
				<div class="btn-group float-end" id="inbtn"><div id="DivTabelStockTools" class="pr-1"></div>';
			if(session()->get("suser_groupe") == '9' 
			|| session()->get("suser_groupe") == '1' 
			&& $Infos['verify']['sellersactivate'] == '1' 
			&& SETTINGS['sellersystem'] == '1')
			{ 
				$tools .= '<a href="'.base_url().'/rdpfactory" id="add" class="btn btn-primary btn-sm"><i class="bx bx-upload"></i> Upload RDP</a>';
			}
			$tools .='</div></div>';
		}
		else if($Infos['verify']['identifier'] == '4'){
			$Tableheaders = '
				<table id="TabelsmtpStock" data-status="'.$status.'" class="table table-border" style="width:100%">
					<thead>
						<tr>
							'.$checkth.'
							<th>Host</th>
							<th>Port</th>
							<th>Username</th>
							<th>Password</th>
							<th>Hoster</th>
							<th>Country</th>
							<th>Price</th>
							<th>Tools</th>
						</tr>
					</thead>
				</table>
			';
			//tools
			$tools = '<div class="card-body">
				<div class="btn-group float-start">';
			switch ($status) {
				case '0' :
					$tools .='<a href="" class="btn btn-indigo btn-sm disabled" disabled><i class="bx bx-box"></i> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(4).'/1" class="btn btn-success btn-sm"><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(4).'/2" class="btn btn-danger btn-sm"><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				case '1' :
					$tools .='<a href="'.base_url().'/sellerdashboard/'.base64_encode(4).'/0" class="btn btn-indigo btn-sm"><i class="bx bx-box"></i> Check Stock</a>
						<a href="" class="btn btn-success btn-sm disabled" disabled><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(4).'/2" class="btn btn-danger btn-sm " ><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				case '2' :
					$tools .='<a href="'.base_url().'/sellerdashboard/'.base64_encode(4).'/0" class="btn btn-indigo btn-sm"><i class="bx bx-box"></i> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(4).'/1" class="btn btn-success btn-sm"><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="#" class="btn btn-danger btn-sm disabled" disabled><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				default :
					$tools .='<a href="" class="btn btn-indigo btn-sm disabled" disabled> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(4).'/1" class="btn btn-success btn-sm"> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(4).'/2" class="btn btn-danger btn-sm"> Check Refunds</a>';
				break;
			}
			$tools .='</div>
				<div class="btn-group float-end" id="inbtn"><div id="DivTabelStockTools" class="pr-1"></div>';
			if(session()->get("suser_groupe") == '9' 
			|| session()->get("suser_groupe") == '1' 
			&& $Infos['verify']['sellersactivate'] == '1' 
			&& SETTINGS['sellersystem'] == '1')
			{ 
				$tools .= '<a href="'.base_url().'/smtpfactory" id="add" class="btn btn-primary btn-sm"><i class="bx bx-upload"></i> Upload SMTP</a>';
			}
			$tools .='</div></div>';
		}
		else if($Infos['verify']['identifier'] == '5'){
			$Tableheaders = '
				<table id="TabelshellStock" data-status="'.$status.'" class="table table-border" style="width:100%">
					<thead>
						<tr>
							'.$checkth.'
							<th>Protocol</th>
							<th>Shell Link</th>
							<th>Password</th>
							<th>Country</th>
							<th>Username</th>
							<th>Password</th>
							<th>Price</th>
							<th>Tools</th>
						</tr>
					</thead>
				</table>
			';

			//Tools
			$tools = '<div class="card-body">
				<div class="btn-group float-start">';
			switch ($status) {
				case '0' :
					$tools .='<a href="" class="btn btn-indigo btn-sm disabled" disabled><i class="bx bx-box"></i> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(5).'/1" class="btn btn-success btn-sm"><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(5).'/2" class="btn btn-danger btn-sm"><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				case '1' :
					$tools .='<a href="'.base_url().'/sellerdashboard/'.base64_encode(5).'/0" class="btn btn-indigo btn-sm"><i class="bx bx-box"></i> Check Stock</a>
						<a href="" class="btn btn-success btn-sm disabled" disabled><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(5).'/2" class="btn btn-danger btn-sm " ><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				case '2' :
					$tools .='<a href="'.base_url().'/sellerdashboard/'.base64_encode(5).'/0" class="btn btn-indigo btn-sm"><i class="bx bx-box"></i> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(5).'/1" class="btn btn-success btn-sm"><i class="bx bx-dollar"></i> Check Sals</a>
						<a href="#" class="btn btn-danger btn-sm disabled" disabled><i class="bx bx-reply"></i> Check Refunds</a>';
				break;
				default :
					$tools .='<a href="" class="btn btn-indigo btn-sm disabled" disabled> Check Stock</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(5).'/1" class="btn btn-success btn-sm"> Check Sals</a>
						<a href="'.base_url().'/sellerdashboard/'.base64_encode(5).'/2" class="btn btn-danger btn-sm"> Check Refunds</a>';
				break;
			}
			$tools .='</div>
				<div class="btn-group float-end" id="inbtn"><div id="DivTabelStockTools" class="pr-1"></div>';
			if(session()->get("suser_groupe") == '9' 
			|| session()->get("suser_groupe") == '1' 
			&& $Infos['verify']['sellersactivate'] == '1' 
			&& SETTINGS['sellersystem'] == '1')
			{ 
				$tools .= '<a href="'.base_url().'/shellfactory" id="add" class="btn btn-primary btn-sm"><i class="bx bx-upload"></i> Upload Shells</a>';
			}
			$tools .='</div></div>';
		}
		else {
			$data['sectionName'] = $Infos['verify']['sectionName'];
			$data['sectionid'] = $Infos['verify']['identifier'];
			$db = db_connect();
			$query = $db->query('SELECT * FROM `table_'.$db->escapeString(strtolower($data['sectionName'])).'`');
			$theTable = array();
			foreach($query->getResultArray() as $key => $val){
				if($val["cellsTypes"] !== 'hide' && $key !== 'id'){
					$theTable[] = $val["cellsHeads"];
				}
			}
			$html = '<table id="TabelOtherStock" data-status="'.$status.'" class="table table-border" style="width:100%">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= $checkth;
			foreach ($theTable as $key => $row) {
				if($row == 'Add Date'){
					$html .= '<th>Price</th>';
				}
				else if($row == 'Price'){
					$html .= '<th>Add Date</th>';
				}
				else {
					$html .= '<th>'.ucfirst($row).'</th>';	
				}
			}
			$html .= '<th>Manage</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '</table>';
			$Tableheaders = $html;
			//Tools
			foreach ($Infos['sections'] as $key => $value) {

				if($id == ""){

					$id = base64_encode($Infos['sections'][$key][0]);
				}
				
				if(base64_decode($id) == $value[0]){
					$tools = '<div class="card-body">
						<div class="btn-group float-start">';

					switch ($status) {
						case '0' :
							$tools .='<a href="" class="btn btn-indigo btn-sm disabled" disabled><i class="bx bx-box"></i> Check Stock</a>
								<a href="'.base_url().'/sellerdashboard/'.base64_encode($value[0]).'/1" class="btn btn-success btn-sm"><i class="bx bx-dollar"></i> Check Sals</a>
								<a href="'.base_url().'/sellerdashboard/'.base64_encode($value[0]).'/2" class="btn btn-danger btn-sm"><i class="bx bx-reply"></i> Check Refunds</a>';
						break;
						case '1' :
							$tools .='<a href="'.base_url().'/sellerdashboard/'.base64_encode($value[0]).'/0" class="btn btn-indigo btn-sm"><i class="bx bx-box"></i> Check Stock</a>
								<a href="" class="btn btn-success btn-sm disabled" disabled><i class="bx bx-dollar"></i> Check Sals</a>
								<a href="'.base_url().'/sellerdashboard/'.base64_encode($value[0]).'/2" class="btn btn-danger btn-sm " ><i class="bx bx-reply"></i> Check Refunds</a>';
						break;
						case '2' :
							$tools .='<a href="'.base_url().'/sellerdashboard/'.base64_encode($value[0]).'/0" class="btn btn-indigo btn-sm"><i class="bx bx-box"></i> Check Stock</a>
								<a href="'.base_url().'/sellerdashboard/'.base64_encode($value[0]).'/1" class="btn btn-success btn-sm"><i class="bx bx-dollar"></i> Check Sals</a>
								<a href="#" class="btn btn-danger btn-sm disabled" disabled><i class="bx bx-reply"></i> Check Refunds</a>';
						break;
						default :
							$tools .='<a href="" class="btn btn-indigo btn-sm disabled" disabled> Check Stock</a>
								<a href="'.base_url().'/sellerdashboard/'.base64_encode($value[0]).'/1" class="btn btn-success btn-sm"> CheckSals</a>
								<a href="'.base_url().'/sellerdashboard/'.base64_encode($value[0]).'/2" class="btn btn-danger btn-sm"> Check Refunds</a>';
						break;
					}
					$tools .= '</div>
						<div class="btn-group float-end" id="inbtn"><div id="DivTabelStockTools" class="pr-1"></div>';
					if(session()->get("suser_groupe") == '9' 
					|| session()->get("suser_groupe") == '1' 
					&& $Infos['verify']['sellersactivate'] == '1' 
					&& SETTINGS['sellersystem'] == '1')
					{ 
						$tools .= '<button type="button" id="add" class="btn btn-primary btn-sm" data-api="initcreate-'.base64_encode($value[0]).'"><i class="bx bx-upload"></i> Upload '.ucfirst($value[1]).'</button>';
					}
					$tools .='</div></div>';
				}
			}
			//exit();
		}

		//Navigation
		$navigation = '';
		foreach ($Infos['sections'] as $key => $value) { 
			if($id === null){
				if($key == 0){
					$navigation .= '<li class="nav-item">
						<a class="nav-link active"  href="'.base_url().'/sellerdashboard/'.base64_encode($value[0]).'">
							<span class="bx '.$value[2].'"></span> '.$value[1].'
						</a>
					</li>   ';
				}
				else {
					$navigation .= '<li class="nav-item ">
						<a class="nav-link"  href="'.base_url().'/sellerdashboard/'.base64_encode($value[0]).'">
							<span class="bx '.$value[2].'"></span> '.$value[1].'
						</a>
					</li>';
				}
			}
			else if(base64_decode($id) == $value[0]){
				$navigation .= '<li class="nav-item ">
						<a class="nav-link active"  href="'.base_url().'/sellerdashboard/'.base64_encode($value[0]).'">
							<span class="bx '.$value[2].'"></span> '.$value[1].'
						</a>
					</li>';
			}
			else {
				$navigation .= '<li class="nav-item ">
						<a class="nav-link "  href="'.base_url().'/sellerdashboard/'.base64_encode($value[0]).'">
							<span class="bx '.$value[2].'"></span> '.$value[1].'
						</a>
					</li>';
			}    
		}

		return [
			'sections' => $Infos['sections'], 
			'verify' => $Infos['verify'], 
			'tableHeader' => $Tableheaders, 
			'navigation' => $navigation,
			'tools' => $tools
		];
	}

	private function getStoksCounts($id = null){
		if(session()->get("suser_groupe") == '1' || session()->get("suser_groupe") == '9'){
			$stats = [];
			if(null !== $id){
				$sessionID = base64_decode($id);
				if($sessionID == '1'){
					$modelCards = new CardsModel;
					switch (session()->get('suser_groupe')) {
						case '1':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0','sellerid' => session()->get('suser_id')])->findAll();
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();
							$ResultsRefunded = $modelCards->where(['selled' => '1', 'refunded' => '1','sellerid' => session()->get('suser_id'), 'refunded' => '1'])->findAll();
						break;
						case '9':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0',])->findAll();	
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0',])->findAll();	
							$ResultsRefunded= $modelCards->where(['selled' => '1', 'refunded' => '1'])->findAll();
						break;
					}
					if(isset($ResultsStock[0])){
						$stats["stok"] = count($ResultsStock);
					}
					else {
						$stats["stok"] = '0';
					}

					if(isset($ResultsSelled[0])){
						$stats["selled"] = count($ResultsSelled);
					}
					else {
						$stats["selled"] = '0';
					}

					if(isset($ResultsRefunded[0])){
						$stats["refunded"] = count($ResultsRefunded);
					}
					else {
						$stats["refunded"] = '0';
					}
					$stats["sectionLable"] = "Cards";
				}
				else if($sessionID == '2'){
					$modelCards = new CpanelModel;
					switch (session()->get('suser_groupe')) {
						case '1':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0','sellerid' => session()->get('suser_id')])->findAll();
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();
							$ResultsRefunded = $modelCards->where(['selled' => '1', 'refunded' => '1','sellerid' => session()->get('suser_id'), 'refunded' => '1'])->findAll();
						break;
						case '9':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0',])->findAll();	
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0',])->findAll();	
							$ResultsRefunded= $modelCards->where(['selled' => '1', 'refunded' => '1'])->findAll();
						break;
					}
					if(isset($ResultsStock[0])){
						$stats["stok"] = count($ResultsStock);
					}
					else {
						$stats["stok"] = '0';
					}

					if(isset($ResultsSelled[0])){
						$stats["selled"] = count($ResultsSelled);
					}
					else {
						$stats["selled"] = '0';
					}

					if(isset($ResultsRefunded[0])){
						$stats["refunded"] = count($ResultsRefunded);
					}
					else {
						$stats["refunded"] = '0';
					}
					$stats["sectionLable"] = "Cpanel";
				}
				else if($sessionID == '3'){
					$modelCards = new RdpModel;
					switch (session()->get('suser_groupe')) {
						case '1':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0','sellerid' => session()->get('suser_id')])->findAll();
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();
							$ResultsRefunded = $modelCards->where(['selled' => '1', 'refunded' => '1','sellerid' => session()->get('suser_id'), 'refunded' => '1'])->findAll();
						break;
						case '9':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0',])->findAll();	
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0',])->findAll();	
							$ResultsRefunded= $modelCards->where(['selled' => '1', 'refunded' => '1'])->findAll();
						break;
					}
					if(isset($ResultsStock[0])){
						$stats["stok"] = count($ResultsStock);
					}
					else {
						$stats["stok"] = '0';
					}

					if(isset($ResultsSelled[0])){
						$stats["selled"] = count($ResultsSelled);
					}
					else {
						$stats["selled"] = '0';
					}

					if(isset($ResultsRefunded[0])){
						$stats["refunded"] = count($ResultsRefunded);
					}
					else {
						$stats["refunded"] = '0';
					}
					$stats["sectionLable"] = "RDP";
				}
				else if($sessionID == '4'){
					$modelCards = new SmtpModel;
					switch (session()->get('suser_groupe')) {
						case '1':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0','sellerid' => session()->get('suser_id')])->findAll();
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();
							$ResultsRefunded = $modelCards->where(['selled' => '1', 'refunded' => '1','sellerid' => session()->get('suser_id'), 'refunded' => '1'])->findAll();
						break;
						case '9':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0',])->findAll();	
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0',])->findAll();	
							$ResultsRefunded= $modelCards->where(['selled' => '1', 'refunded' => '1'])->findAll();
						break;
					}
					if(isset($ResultsStock[0])){
						$stats["stok"] = count($ResultsStock);
					}
					else {
						$stats["stok"] = '0';
					}

					if(isset($ResultsSelled[0])){
						$stats["selled"] = count($ResultsSelled);
					}
					else {
						$stats["selled"] = '0';
					}

					if(isset($ResultsRefunded[0])){
						$stats["refunded"] = count($ResultsRefunded);
					}
					else {
						$stats["refunded"] = '0';
					}
					$stats["sectionLable"] = "SMTP";
				}
				else if($sessionID == '5'){
					$modelCards = new ShellModel;
					switch (session()->get('suser_groupe')) {
						case '1':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0','sellerid' => session()->get('suser_id')])->findAll();
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();
							$ResultsRefunded = $modelCards->where(['selled' => '1', 'refunded' => '1','sellerid' => session()->get('suser_id'), 'refunded' => '1'])->findAll();
						break;
						case '9':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0',])->findAll();	
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0',])->findAll();	
							$ResultsRefunded= $modelCards->where(['selled' => '1', 'refunded' => '1'])->findAll();
						break;
					}
					if(isset($ResultsStock[0])){
						$stats["stok"] = count($ResultsStock);
					}
					else {
						$stats["stok"] = '0';
					}

					if(isset($ResultsSelled[0])){
						$stats["selled"] = count($ResultsSelled);
					}
					else {
						$stats["selled"] = '0';
					}

					if(isset($ResultsRefunded[0])){
						$stats["refunded"] = count($ResultsRefunded);
					}
					else {
						$stats["refunded"] = '0';
					}
					$stats["sectionLable"] = "Shell";
				}
				else {
					$model = new SectionsModel;
					$Results = $model->where('identifier', $sessionID)->findAll();
					$countresults = count($Results);
					if($countresults == 1){
						$dbv = \Config\Database::connect();
						$db =  $dbv->table("section_".$dbv->escapeString(strtolower($Results[0]['sectioname'])));
						switch (session()->get('suser_groupe')) {
							case '1':
								$ResultsStock = $db->where(['selled' => '0', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->countAllResults();
								$ResultsSelled = $db->where(['selled' => '1', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->countAllResults();
								$ResultsRefunded = $db->where(['selled' => '1', 'refunded' => '1', 'sellerid' => session()->get('suser_id')])->countAllResults();
							break;
							case '9':
								$ResultsStock = $db->where(['selled' => '0', 'refunded' => '0'])->countAllResults();
								$ResultsSelled = $db->where(['selled' => '1', 'refunded' => '0'])->countAllResults();
								$ResultsRefunded = $db->where(['selled' => '1', 'refunded' => '1'])->countAllResults();
							break;
						}
						$stats["stok"] = $ResultsStock ? $ResultsStock : '0';
						$stats["selled"] = $ResultsSelled ? $ResultsSelled : '0';
						$stats["refunded"] = $ResultsRefunded ? $ResultsRefunded : '0';
						$stats["sectionLable"] = $Results[0]['sectionlable'];
					}
					else {
						$stats = NULL;
					}
				}					
			}
			else {
				$getSectionsmodel = new SectionsModel;
				$sections = $getSectionsmodel->where(['sectionstatus' => '1'])->findAll();
				if($sections[0]['identifier'] == '1'){
					$modelCards = new CardsModel;
					switch (session()->get('suser_groupe')) {
						case '1':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0','sellerid' => session()->get('suser_id')])->findAll();
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();	
							$ResultsRefunded = $modelCards->where(['selled' => '1', 'refunded' => '1','sellerid' => session()->get('suser_id'), 'refunded' => '1'])->findAll();
						break;
						case '9':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0',])->findAll();	
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0',])->findAll();	
							$ResultsRefunded = $modelCards->where(['selled' => '1', 'refunded' => '1'])->findAll();
						break;
					}
					if(isset($ResultsStock[0])){
						$stats["stok"] = count($ResultsStock);
					}
					else {
						$stats["stok"] = '0';
					}

					if(isset($ResultsSelled[0])){
						$stats["selled"] = count($ResultsSelled);
					}
					else {
						$stats["selled"] = '0';
					}

					if(isset($ResultsRefunded[0])){
						$stats["refunded"] = count($ResultsRefunded);
					}
					else {
						$stats["refunded"] = '0';
					}
					$stats["sectionLable"] = "Cards";
				}
				else if($sections[0]['identifier'] == '2'){
					$modelCards = new CpanelModel;
					switch (session()->get('suser_groupe')) {
						case '1':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0','sellerid' => session()->get('suser_id')])->findAll();
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();
							$ResultsRefunded = $modelCards->where(['selled' => '1', 'refunded' => '1','sellerid' => session()->get('suser_id'), 'refunded' => '1'])->findAll();
						break;
						case '9':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0',])->findAll();	
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0',])->findAll();	
							$ResultsRefunded= $modelCards->where(['selled' => '1', 'refunded' => '1'])->findAll();
						break;
					}
					if(isset($ResultsStock[0])){
						$stats["stok"] = count($ResultsStock);
					}
					else {
						$stats["stok"] = '0';
					}

					if(isset($ResultsSelled[0])){
						$stats["selled"] = count($ResultsSelled);
					}
					else {
						$stats["selled"] = '0';
					}

					if(isset($ResultsRefunded[0])){
						$stats["refunded"] = count($ResultsRefunded);
					}
					else {
						$stats["refunded"] = '0';
					}
					$stats["sectionLable"] = "Cpanel";
				}
				else if($sections[0]['identifier'] == '3'){
					$modelCards = new RdpModel;
					switch (session()->get('suser_groupe')) {
						case '1':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0','sellerid' => session()->get('suser_id')])->findAll();
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();
							$ResultsRefunded = $modelCards->where(['selled' => '1', 'refunded' => '1','sellerid' => session()->get('suser_id'), 'refunded' => '1'])->findAll();
						break;
						case '9':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0',])->findAll();	
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0',])->findAll();	
							$ResultsRefunded= $modelCards->where(['selled' => '1', 'refunded' => '1'])->findAll();
						break;
					}
					if(isset($ResultsStock[0])){
						$stats["stok"] = count($ResultsStock);
					}
					else {
						$stats["stok"] = '0';
					}

					if(isset($ResultsSelled[0])){
						$stats["selled"] = count($ResultsSelled);
					}
					else {
						$stats["selled"] = '0';
					}

					if(isset($ResultsRefunded[0])){
						$stats["refunded"] = count($ResultsRefunded);
					}
					else {
						$stats["refunded"] = '0';
					}
					$stats["sectionLable"] = "RDP";
				}
				else if($sections[0]['identifier'] == '4'){
					$modelCards = new SmtpModel;
					switch (session()->get('suser_groupe')) {
						case '1':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0','sellerid' => session()->get('suser_id')])->findAll();
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();
							$ResultsRefunded = $modelCards->where(['selled' => '1', 'refunded' => '1','sellerid' => session()->get('suser_id'), 'refunded' => '1'])->findAll();
						break;
						case '9':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0',])->findAll();	
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0',])->findAll();	
							$ResultsRefunded= $modelCards->where(['selled' => '1', 'refunded' => '1'])->findAll();
						break;
					}
					if(isset($ResultsStock[0])){
						$stats["stok"] = count($ResultsStock);
					}
					else {
						$stats["stok"] = '0';
					}

					if(isset($ResultsSelled[0])){
						$stats["selled"] = count($ResultsSelled);
					}
					else {
						$stats["selled"] = '0';
					}

					if(isset($ResultsRefunded[0])){
						$stats["refunded"] = count($ResultsRefunded);
					}
					else {
						$stats["refunded"] = '0';
					}
					$stats["sectionLable"] = "SMTP";
				}
				else if($sections[0]['identifier'] == '5'){
					$modelCards = new ShellModel;
					switch (session()->get('suser_groupe')) {
						case '1':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0','sellerid' => session()->get('suser_id')])->findAll();
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->findAll();
							$ResultsRefunded = $modelCards->where(['selled' => '1', 'refunded' => '1','sellerid' => session()->get('suser_id'), 'refunded' => '1'])->findAll();
						break;
						case '9':
							$ResultsStock = $modelCards->where(['selled' => '0', 'refunded' => '0',])->findAll();	
							$ResultsSelled = $modelCards->where(['selled' => '1', 'refunded' => '0',])->findAll();	
							$ResultsRefunded= $modelCards->where(['selled' => '1', 'refunded' => '1'])->findAll();
						break;
					}
					if(isset($ResultsStock[0])){
						$stats["stok"] = count($ResultsStock);
					}
					else {
						$stats["stok"] = '0';
					}

					if(isset($ResultsSelled[0])){
						$stats["selled"] = count($ResultsSelled);
					}
					else {
						$stats["selled"] = '0';
					}

					if(isset($ResultsRefunded[0])){
						$stats["refunded"] = count($ResultsRefunded);
					}
					else {
						$stats["refunded"] = '0';
					}
					$stats["sectionLable"] = "Shell";
				}
				else {
					$model = new SectionsModel;
					$Results = $model->where('identifier', $sections[0]['identifier'])->findAll();
					$sectionLablem = $Results[0]['sectionlable'];
					//exit();
					$countresults = count($Results);
					if($countresults == 1){
						$dbv = \Config\Database::connect();
						$db =  $dbv->table("section_".$dbv->escapeString(strtolower($Results[0]['sectioname'])));
						switch (session()->get('suser_groupe')) {
							case '1':
								$ResultsStock = $db->where(['selled' => '0', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->countAllResults();
								$ResultsSelled = $db->where(['selled' => '1', 'refunded' => '0', 'sellerid' => session()->get('suser_id')])->countAllResults();
								$ResultsRefunded = $db->where(['selled' => '1', 'refunded' => '1', 'sellerid' => session()->get('suser_id')])->countAllResults();
							break;
							case '9':
								$ResultsStock = $db->where(['selled' => '0', 'refunded' => '0'])->countAllResults();
								$ResultsSelled = $db->where(['selled' => '1', 'refunded' => '0'])->countAllResults();
								$ResultsRefunded = $db->where(['selled' => '1', 'refunded' => '1'])->countAllResults();
							break;
						}
						$stats["stok"] = $ResultsStock ? $ResultsStock : '0';
						$stats["selled"] = $ResultsSelled ? $ResultsSelled : '0';
						$stats["refunded"] = $ResultsRefunded ? $ResultsRefunded : '0';
						$stats["sectionLable"] = $sectionLablem ? $sectionLablem : '';
					}
					else {
						$stats = NULL;
					}
				}
			}
		}
		else {
			$stats = NULL;
		}

		return $stats;
	}

	public function index($id = null, $status = null){
		if(session()->get('logedin') == '1'){
			$settings = fetchSettings();
			$mycart = getCart();
			$TableHeadersAndChecks = $this->CreateTableHeaders($id, $status);
			$statistics = $this->getStoksCounts($id);
			$data = [];
			$data["sectionName"] = "Seller Dashboard";
			$data['navigation'] = $TableHeadersAndChecks['navigation'];
			$data['sellersactivate'] = $TableHeadersAndChecks['verify']['sellersactivate'];
			$data['TableHeaders'] = $TableHeadersAndChecks['tableHeader'];
			$data["nbitemscart"] = $mycart[0];
			$data["cartInnerHtml"] = $mycart[1];
			$data["settings"] = $settings;
			$data["tools"] = $TableHeadersAndChecks['tools'];
			$data['statistics'] = $statistics;
			echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("sellerdashboard");
            echo view("assets/footer");
            echo view("assets/scripts");	
		}
		else {
			header('location:'.base_url());
			exit();
		}
	}

	public function fetchTable(){
		if(session()->get("logedin") == true){
			if(null !== $this->request->getPost('status') && preg_match("/^[0-3]$/", $this->request->getPost('status'))){
				$posts = $this->request->getPost('status');
				$getStatu = $posts;
				session()->set('statusGet', $getStatu);
			}
			else {
				$getStatu = 0;
				session()->set('statusGet', $getStatu);
			}
			$model = new StoksModel();
			$table = new TablesIgniter($model->initTable($getStatu));
			$tableMy = $table->getDatatable();
			session()->remove('statusGet');
			return $tableMy;
		}
		else {
			header('location:'.base_url());
			exit();
		}
	}

	public function fetchTableCpanel(){
		if(session()->get("logedin") == true){
			if(null !== $this->request->getPost('status') && preg_match("/^[0-3]$/", $this->request->getPost('status'))){
				$posts = $this->request->getPost('status');
				$getStatu = $posts;
				session()->set('statusGet', $getStatu);
			}
			else {
				$getStatu = 0;
				session()->set('statusGet', $getStatu);
			}
			$model = new StoksModel();
			$table = new TablesIgniter($model->initTableCpanels($getStatu));
			$tableMy = $table->getDatatable();
			session()->remove('statusGet');
			return $tableMy;
		}
		else {
			header('location:'.base_url());
			exit();
		}
	}

	public function fetchTableRdp(){
		if(session()->get("logedin") == true){
			if(null !== $this->request->getPost('status') && preg_match("/^[0-3]$/", $this->request->getPost('status'))){
				$posts = $this->request->getPost('status');
				$getStatu = $posts;
				session()->set('statusGet', $getStatu);
			}
			else {
				$getStatu = 0;
				session()->set('statusGet', $getStatu);
			}
			$model = new StoksModel();
			$table = new TablesIgniter($model->initTableRdp($getStatu));
			$tableMy = $table->getDatatable();
			session()->remove('statusGet');
			return $tableMy;
		}
		else {
			header('location:'.base_url());
			exit();
		}
	}

	public function fetchTableSmtp(){
		if(session()->get("logedin") == true){
			if(null !== $this->request->getPost('status') && preg_match("/^[0-3]$/", $this->request->getPost('status'))){
				$posts = $this->request->getPost('status');
				$getStatu = $posts;
				session()->set('statusGet', $getStatu);
			}
			else {
				$getStatu = 0;
				session()->set('statusGet', $getStatu);
			}
			$model = new StoksModel();
			$table = new TablesIgniter($model->initTableSmtp($getStatu));
			$tableMy = $table->getDatatable();
			session()->remove('statusGet');
			return $tableMy;
		}
		else {
			header('location:'.base_url());
			exit();
		}
	}

	public function fetchOthersTable(){
		if(session()->get("logedin") == true){
			$model = new SectionsModel;
			if(null !== $this->request->getPost('id') && base64_decode($this->request->getPost('id'), true)){
				$param = base64_decode($this->request->getPost('id'));	
			}
			else {
				$ActiveSections = $this->sectionsMenuCreator();
				$param = $ActiveSections[0][0];
			}
			$status = $this->request->getPost('status');

			$Results = $model->where('identifier' , $param)->findAll();
			if(count($Results) == 1){
				$data['sectionName'] = $Results[0]['sectioname'];
				$tableName = 'section_'.strtolower($data['sectionName']);
				if(preg_match("/^[0-3]$/", $status)){
					$getStatu = $status;
					session()->set('statusGet', $getStatu);
				}
				else {
					$getStatu = 0;
					session()->set('statusGet', $getStatu);
				}
				$sectionSelltype = $Results[0]['sectiontype'];
				session()->set('sectionName', strtolower($data['sectionName']));
				$model = new StoksModel();
				$table = new TablesIgniter($model->initTableOthers($getStatu, $tableName,$param, $sectionSelltype));
				$tableMy = $table->getDatatable();
				session()->remove('statusGet');
				session()->remove('sectionName');
				return $tableMy;
			}
			else {
				echo 'Error E-001';
			}

		}
		else {
			echo 'Error E-002';
		}
	}
//cc tools
	public function initEdit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			header('location:'.base_url().'/');
    			exit();
    		}
    		else {
    			$response = array();
    			if(preg_match("/^([0-9])+$/i", base64_decode($this->request->getPost('id')))){
    				$id = base64_decode($this->request->getPost('id'));
    				$Model = new CardsModel;
    				$Results = $Model->where(['id' => $id, 'selled' => 0, 'refunded' => '0'])->find();
    				$countResults = count($Results);
    				$usermodel = new UsersModel;
    				if($countResults == 1){
    					$getseller = $usermodel->where(['id' => session()->get('suser_id')])->findAll();
    					if(count($getseller) > 0){
    						if($Results[0]['sellerid'] == $getseller[0]['id'] || session()->get('suser_groupe') == '9'){
    							switch($Results[0]['refun']){
    								case '0':
    									$refun = '<option value="0" selected>No</option>
    									<option value="1">Yes</option>';
    								break;
    								case '1':
    									$refun = '<option value="1" selected>Yes</option>
    									<option value="0">No</option>';
    								break;
    							}
    							if($Results[0]['fullname'] == 'N/A'){
    								$Results[0]['fullname'] = '';
    							}
    							if($Results[0]['ssn'] == 'N/A'){
    								$Results[0]['ssn'] = '';
    							}
    							if($Results[0]['address'] == 'N/A'){
    								$Results[0]['address'] = '';
    							}
    							if($Results[0]['city'] == 'N/A'){
    								$Results[0]['city'] = '';
    							}
    							if($Results[0]['state'] == 'N/A'){
    								$Results[0]['state'] = '';
    							}
    							if($Results[0]['zip'] == 'N/A'){
    								$Results[0]['zip'] = '';
    							}
    							if($Results[0]['phone'] == 'N/A'){
    								$Results[0]['phone'] = '';
    							}
    							if($Results[0]['dob'] == 'N/A'){
    								$Results[0]['dob'] = '';
    							}
    							if($Results[0]['email'] == 'N/A'){
    								$Results[0]['email'] = '';
    							}
    							if($Results[0]['ip'] == 'N/A'){
    								$Results[0]['ip'] = '';
    							}
    							
    							
    							$form = '
    								<form id="edittForm">
    									<div class="form-group row">
    										<div class="col-12">
    											<label>Base name<i class="text-danger"> *</i></label>
    											<input type="text" id="base" name="base" class="form-control" value="'.$Results[0]['base'].'">
    											<small class="base text-danger"></small>
    										</div>
    										<div class="col-12">
    											<label>Card number<i class="text-danger"> *</i></label>
    											<input type="text" id="number" name="number" class="form-control" value="'.$Results[0]['number'].'">
    											<small class="number text-danger"></small>
    										</div>
    									</div>
    									<div class="form-group row">
    										<div class="col-6">
    											<label>Expiration date (MM/YY)<i class="text-danger"> *</i></label>
    											<input type="text" id="expiration" name="expiration" class="form-control" data-mask="99/99" value="'.$Results[0]['expiration'].'">
    											<small class="expiration text-danger"></small>
    										</div>
    										<div class="col-6">
    											<label>CVV<i class="text-danger"> *</i></label>
    											<input type="text" id="cvv" name="cvv" class="form-control" value="'.$Results[0]['cvv'].'">
    											<small class="cvv text-danger"></small>
    										</div>
    									</div>

    									<div class="form-group row">
    										<div class="col-6">
    											<label>Full Name</label>
    											<input type="text" id="fullname" name="fullname" class="form-control" value="'.$Results[0]['fullname'].'">
    											<small class="fullname text-danger"></small>
    										</div>
    										<div class="col-6">
    											<label>SSN</label>
    											<input type="text" id="ssn" name="ssn" class="form-control" value="'.$Results[0]['ssn'].'">
    											<small class="ssn text-danger"></small>
    										</div>
    									</div>
    									<div class="form-group row">
    										<div class="col-12">
    											<label>Address</label>
    											<input type="text" id="address" name="address" class="form-control" value="'.$Results[0]['address'].'">
    											<small class="address text-danger"></small>
    										</div>
    									</div>
    									<div class="form-group row">
    										<div class="col-4">
    											<label>City</label>
    											<input type="text" id="city" name="city" class="form-control" value="'.$Results[0]['city'].'">
    											<small class="city text-danger"></small>
    										</div>
    										<div class="col-4">
    											<label>State</label>
    											<input type="text" id="state" name="state" class="form-control" value="'.$Results[0]['state'].'">
    											<small class="state text-danger"></small>
    										</div>
    										<div class="col-4">
    											<label>Zip</label>
    											<input type="text" id="zip" name="zip" class="form-control" value="'.$Results[0]['zip'].'">
    											<small class="zip text-danger"></small>
    										</div>
    										<div class="col-6 pt-2">
    											<label>Phone</label>
    											<input type="text" id="phone" name="phone" class="form-control" value="'.$Results[0]['phone'].'">
    											<small class="phone text-danger"></small>
    										</div>
    										<div class="col-6 pt-2">
    											<label>DOB</label>
    											<input type="text" id="dob" name="dob" class="form-control" value="'.$Results[0]['dob'].'">
    											<small class="dob text-danger"></small>
    										</div>
    										<div class="col-6 pt-2">
    											<label>Email</label>
    											<input type="text" id="email" name="email" class="form-control" value="'.$Results[0]['email'].'">
    											<small class="email text-danger"></small>
    										</div>
    										<div class="col-6 pt-2">
    											<label>Address IP</label>
    											<input type="text" id="ip" name="ip" class="form-control" value="'.$Results[0]['ip'].'">
    											<small class="ip text-danger"></small>
    										</div>
    									</div>
    									<div class="form-group row">
    										<div class="col-6">
    											<label>Refundable</label>
    											<select class="form-control select2" name="refun">
    												'.$refun.'
    											</select>
    											<small class="refun text-danger"></small>
    										</div>
    										<div class="col-6">
    											<label>Price<i class="text-danger"> *</i></label>
    											<input type="number" id="price" name="price" class="form-control" value="'.$Results[0]['price'].'">
    											<small class="price text-danger"></small>
    											<input type="hidden" name="'.csrf_token().'" value="'.csrf_hash().'">
    											<input type="hidden" name="id" value="'.base64_encode($Results[0]['id']).'">
    										</div>
    									</div>
    								</form>
    								<script>
    								$(\'.select2\').select2({	
    									width: \'100%\',
    									dropdownParent: $("#bsModal")
    							    });
    								</script>
    							'; 
    							$modalContent = $form;
    							$response["modal"] = createModal($modalContent, 'fade  ', 'Edit', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="editCard-'.$Results[0]['id'].'"']);
    						}
    						else {
    							$modalContent = '<p>Object not found. E0012</p>';
    							$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    						}
    					}
    					else {
    						$modalContent = '<p>Object not found. E0012</p>';
    						$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					}
    				}
    				else {
    					$modalContent = '<p>Object not found. E002</p>';
    					$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$modalContent = '<p>Object not selected. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade  ', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();	
    			}
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function edit(){
	   if($this->request->isAJAX()){ 
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			exit();
    		}
    		else {
    			$response = array();
    			$ValidationRulls = [
    				'number' => [
    					'label' => 'Credit card',
    		            'rules'  => 'required|valide_cc_number',
    		            'errors' => [
    		            	'required' => 'Insert Card number.',
    		            	'valide_cc_number' => 'A valid card number is required.',
    	            	]
    	            ],
    	            'expiration' => [
    	            	'label' => 'Expiration Date',
    		            'rules'  => 'required|expirate|max_length[7]',
    		            'errors' => [
    		            	'required' => 'Insert Expiration date.',
    		            	'expirate' => 'Invalid input.'
    	            	]
    	            ],
    	            'cvv' => [
    	            	'label' => 'cvv',
    		            'rules'  => 'required|numeric|max_length[4]',
    		            'errors' => [
    		            	'required' => 'Insert CVV.',
    		            	'numeric' => 'nvalid input.',
    	            	]
    	            ],
    	            'fullname' => [
    	            	'label' => 'Full Name',
    		            'rules'  => 'permit_empty|regex_match[/^\p{L}+[\s\p{L}]*$/u]|max_length[50]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid Full name.',
    	            	]
    	            ],
    	            'ssn' => [
    	            	'label' => 'SSN',
    		            'rules'  => 'permit_empty|regex_match[/^(?:\d{3}-\d{2}-\d{4}|\d{3}\s\d{2}\s\d{4}|\d{9})$/]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid SSN Number.',
    	            	]
    	            ],
    	            'address' => [
    	            	'label' => 'Address',
    		            'rules'  => 'permit_empty|regex_match[/^[a-zA-Z0-9\s\,\.\#\-\/]{5,200}$/u]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid Address.',
    	            	]
    	            ],
    	            'city' => [
    	            	'label' => 'City',
    		            'rules'  => 'permit_empty|regex_match[/^[\p{L}0-9\s\,\.\#\-\/]{2,50}$/u]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid City.',
    	            	]
    	            ],
    	            'state' => [
    	            	'label' => 'State',
    		            'rules'  => 'permit_empty|regex_match[/^[\p{L}0-9\s\,\.\#\-\/]{2,50}$/u]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid State.',
    	            	]
    	            ],
    	            'zip' => [
    	            	'label' => 'Zip',
    		            'rules'  => 'permit_empty|regex_match[/^[A-Za-z0-9\s\-]{5,10}$/]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid Zip.',
    	            	]
    	            ],
    	            'phone' => [
    	            	'label' => 'Phone number',
    		            'rules'  => 'permit_empty|regex_match[/^[0-9\s\-]{5,20}$/]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid Phone number.',
    	            	]
    	            ],
    	            'dob' => [
    	            	'label' => 'DOB',
    		            'rules'  => 'permit_empty|regex_match[/^(0[1-9]|[1-2][0-9]|3[0-1])[\/|\-|\s](0[1-9]|1[0-2])[\/|\-|\s]([0-9]{2}|[0-9]{4})$/]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid DOB.',
    	            	]
    	            ],
    	            'email' => [
    	            	'label' => 'Email',
    		            'rules'  => 'permit_empty|regex_match[/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid Email Address.',
    	            	]
    	            ],
    	            'ip' => [
    	            	'label' => 'IP Address',
    		            'rules'  => 'permit_empty|regex_match[/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid IP Address.',
    	            	]
    	            ],
    	            'refun' => [
    	            	'label' => 'Refundable ?',
    		            'rules'  => 'required|regex_match[/^[0-1]{1}$/]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid Refundable Parameter.',
    		            	'required' => 'Please insert a valid Refundable Parameter.',
    	            	]
    	            ],
    	            'price' => [
    	            	'label' => 'IP Address',
    		            'rules'  => 'required|regex_match[/^[0-9]{1,50}$/]',
    		            'errors' => [
    		            	'regex_match' => 'Please insert a valid Price.',
    		            	'required' => 'Please insert a valid Price.',
    	            	]
    	            ],
    	            'base' => [
    	            	'label' => 'Base Name',
    		            'rules'  => 'required|regex_match[/^([a-zA-Z0-9 \_])+$/]|max_length[50]',
    		            'errors' => [
    		            	'required' => 'Insert Base Name.',
    		            	'regex_mach' => 'nvalid input.',
    	            	]
    	            ],
    			];

    			if(!$this->validate($ValidationRulls)){
    				$ErrorFields = $this->validator->getErrors();
    				$modalTitle = "Validation Error";
    				$modalContent = '';
    				foreach($ErrorFields as $key => $value){
    					$modalContent .= '<p class=""><b>'.$value.'</b></p>';
    				}	
    				$response["fieldslist"] = $ErrorFields;
    				$response["csrft"] = csrf_hash();
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				if(preg_match("/^([0-9])+$/i", base64_decode($this->request->getPost('id')))){
    					$id = base64_decode($this->request->getPost('id'));
    					$Model = new CardsModel;
    					$Results = $Model->where(['id' => $id, 'selled' => 0])->find();
    					$countResults = count($Results);
    					if($countResults == 1){
    						$binsModel = new BinslistModel;
							$bin = substr($this->request->getPost('number'), 0,6);
							$GetCardInfos = $binsModel->where('iin_start', $bin)->findAll();
							if(count($GetCardInfos) > 0){
								$data['scheme'] = $GetCardInfos[0]["scheme"] ? $GetCardInfos[0]["scheme"] : "N/A";
								$data['type'] = $GetCardInfos[0]["type"] ? $GetCardInfos[0]["type"] : "N/A";
								$data['brand'] = $GetCardInfos[0]["brand"] ? $GetCardInfos[0]["brand"] : "N/A";
								$data['country'] = $GetCardInfos[0]["country"] ? $GetCardInfos[0]["country"] : "N/A";
								$data['bank'] = $GetCardInfos[0]["bank_name"] ? $GetCardInfos[0]["bank_name"] : "N/A";
								$data[] = $GetCardInfos[0]["country"] ? $GetCardInfos[0]["country"] : "UN";
							}
							else {
								$data['scheme'] = 'N/A';
								$data['type'] = 'N/A';
								$data['brand'] = 'N/A';
								$data['country'] = 'N/A';
								$data['bank'] = 'N/A';
							}
    						foreach($this->request->getPost() as $key => $val){
    							if($val != "" ){
    								if($key == 'base'){
    									if(strpos($val, '_')){
    										$val = explode('_', $val);
    									
	        						        $oldname = $Results[0]['base'];
	        						        if(strpos($Results[0]['base'], '_')){
	        						            $basarr = explode("_", $oldname);
	        						            $data['base'] = $basarr[0].'_'.$basarr[1].'_'.$basarr[2].'_'.str_replace(' ', '_',
	        						            															ucfirst(
	        						            																strtolower(
	        						            																	implode(' ',array_slice($val, 3))
	        						            																)
	        						            															)
	        						            														);
	        						        }
	        						        else {
	        						            $data['base'] = date('d_m_Y').'_'.str_replace(' ', '_',ucfirst(strtolower($val)));
	        						        }

        						    	}
        						    	else {

        						    		$oldname = $Results[0]['base'];
	        						        if(strpos($Results[0]['base'], '_')){
	        						            $basarr = explode("_", $oldname);
	        						            $data['base'] = $basarr[0].'_'.$basarr[1].'_'.$basarr[2].'_'.str_replace(' ', '_',ucfirst(strtolower($val)));
	        						        }
	        						        else {
	        						            $data['base'] = date('d_m_Y').'_'.str_replace(' ', '_',ucfirst(strtolower($val)));
	        						        }
	        						        	
        						        }
        							}
        						    else {
        						        $data[$key] = $val;    
        						    }
        						    //$data[$key] = $val;
    							}
    							else {
    								$data[$key] = 'N/A';
    							}
    						}
    
    						$usermodel = new UsersModel;
    						$getseller = $usermodel->where(['id' => session()->get('suser_id')])->findAll();
    						if(count($getseller) > 0){
    							if($Results[0]['sellerid'] == $getseller[0]['id'] || session()->get('suser_groupe') == '9'){
    								$Request = $Model->update($id, $data);
    								
    								$modalContent = '<p>Log Edited succefull</p>';
    								$modalTitle = 'Edit succefull';
    								$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");	
    							}
    							else {
    								$modalContent = '<p>Object not found. E0012</p>';
    								$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    							}
    						}
    						else {
    							$modalContent = '<p>Object not found. E0013</p>';
    							$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    						}						
    					}
    					else {
    						$modalContent = '<p>Object not found. E002</p>';
    						$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					}
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    						
    				}
    				else {
    					$modalContent = '<p>Object not selected. E003</p>';
    					$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();	
    				}
    			}
    		}
	   }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function rminit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			exit();
    		}
    		else {
    			if(preg_match("/^[0-9]{1,11}$/",base64_decode($this->request->getPost('id')))){
    				$id = base64_decode($this->request->getPost('id'));
    				$Model = new CardsModel;
    				switch (session()->get("suser_groupe")) {
    					case '9':
    						$Results = $Model->where(['id' => $id])->find();
    					break;
    					
    					case '1':
    						$Results = $Model->where(['id' => $id, 'sellerid' => session()->get("suser_id"), 'selled' => '0'])->find();
    					break;
    				}
    				
    				$countResults = count($Results);
    				if($countResults == 1){
    					$modalContent = '<p>Do you realy wan to remove this item ?</p>';
    					$response["modal"] = createModal($modalContent, 'fade', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "1",['text' => 'Delete', 'functions' =>'data-api="rm-'.base64_encode($Results[0]["id"]).'"']);
    				}
    				else {
    					$modalContent = '<p>Object not found. E002</p>';
    					$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    
    			}
    			else {
    				$modalContent = '<p>Object not selected. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();	
    			}
    
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function rm(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			exit();
    		}
    		else {
    			if(preg_match("/^[0-9]{1,11}$/",base64_decode($this->request->getPost('id')))){
    				$id = base64_decode($this->request->getPost('id'));
    				$Model = new CardsModel;
    				switch (session()->get("suser_groupe")) {
    					case '9':
    						$Results = $Model->where(['id' => $id])->find();
    					break;
    					
    					case '1':
    						$Results = $Model->where(['id' => $id, 'sellerid' => session()->get("suser_id"), 'selled' => '0'])->find();
    					break;
    				}				
    				$countResults = count($Results);
    				if($countResults == 1){
    					$Model->delete($Results[0]["id"]);
    					$sectionmodel = new SectionsModel;
    					$getInfo = $sectionmodel->where('identifier', '1')->findAll();
    					$newit = intval($getInfo[0]['itemsnumbers']) - 1;
    					$datanewit = [
    						'itemsnumbers' => $newit
    					];
    					$sectionmodel->update($getInfo[0]['id'], $datanewit);
    					$modalContent = '<p>Item Deleted.</p>';
    					$response["modal"] = createModal($modalContent, 'fade', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				else {
    					$modalContent = '<p>Object not found. E002</p>';
    					$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				}
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$modalContent = '<p>Object not selected. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();	
    			}
    
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function massinitEdit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			header('location:'.base_url().'/');
    			exit();
    		}
    		else {
    			$response = array();
    			$form = '
    				<form id="MasseditUserForm">
    					<div class="form-group row">
    						<div class="col-12">
    							<label>Base Name</label>
    							<input type="text" id="base" name="base" class="form-control">
    							<small class="base text-danger"></small>
    						</div>
    					</div>
    					<div class="form-group row">
    						<div class="col-12">
    							
    							<label>Refundable</label>
    							<select class="form-control select2" id="refun" name="refun">
    							    <option>Select</option>
    								<option value="1">Yes</option>
    								<option value="0">No</option>
    							</select>
    							<small class="refun text-danger"></small>
    							
    						</div>
    					</div>
    					<div class="form-group row">
    						<div class="col-4">
    							<label>City</label>
    							<input type="text" id="city" name="city" class="form-control">
    							<small class="city text-danger"></small>
    						</div>
    						<div class="col-4">
    							<label>State</label>
    							<input type="text" id="state" name="state" class="form-control">
    							<small class="state text-danger"></small>
    						</div>
    						<div class="col-4">
    							<label>Zip</label>
    							<input type="text" id="zip" name="zip" class="form-control">
    							<small class="zip text-danger"></small>
    						</div>
    					</div>
    					<div class="form-group row">
    						<div class="col-12">
    							<label>Price</label>
    							<input type="number" id="price" name="price" class="form-control">
    							<small class="price text-danger"></small>
    							<input type="hidden" name="'.csrf_token().'" value="'.csrf_hash().'">
    							<input type="hidden" name="id" value="">
    						</div>
    					</div>
    				</form>
    				<script>
    				$(\'select\').select2({
    					width: "100%",
    					dropdownParent: $("#bsModal")
    			    });
    				</script>
    			'; 
    			$modalContent = $form;
    			$response["modal"] = createModal($modalContent, 'fade', 'Edit', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="massedit"']);
    			$response["csrft"] = csrf_hash();
    			echo json_encode($response);
    			exit();
    			
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function massedit(){
		if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") !== "9" && session()->get("suser_groupe") !== "1"){
    			exit();
    		}
    		else {
    			$response = array();
    			if(null !== $this->request->getPost("base") && $this->request->getPost("base") !== ""){
    				$ValidationRulls["base"] = array(
    		            'rules'  => 'required|regex_match[/^[a-zA-Z0-9 \_]{2,30}$/]',
    		            'errors' => array(
    		                'required' => 'Base name is required',
    		            	'regex_match' => 'Invalid Base name.',
    	            	)
    	         	);
    			}
    			if(null !== $this->request->getPost("refun") && $this->request->getPost("refun") !== ""){
    				$ValidationRulls["refun"] = array(
    		            'rules'  => 'required|numeric',
    		            'errors' => array(
    		            	'required' => 'Invalid Refund option.',
    		            	'numeric' => 'Invalid Refund option.',
    	            	)
    	         	);
    			}
    			if(null !== $this->request->getPost("city") && $this->request->getPost("city") !== ""){
    				$ValidationRulls["city"] = array(
    		            'rules'  => 'permit_empty|regex_match[/^[\p{L}0-9\s\,\.\#\-\/]{2,50}$/]',
    		            'errors' => array(
    		            	'regex_match' => 'Invalid City name.',
    	            	)
    	         	);
    			}
    			if(null !== $this->request->getPost("state") && $this->request->getPost("state") !== ""){
    				$ValidationRulls["state"] = array(
    		            'rules'  => 'permit_empty|regex_match[/^[\p{L}0-9\s\,\.\#\-\/]{2,50}$/]',
    		            'errors' => array(
    		            	'regex_match' => 'Invalid State name.',
    		            	
    	            	)
    	         	);
    			}
    			if(null !== $this->request->getPost("zip") && $this->request->getPost("zip") !== ""){
    				$ValidationRulls["zip"] = array(
    		            'rules'  => 'permit_empty|regex_match[/^[A-Za-z0-9\s\-]{5,10}$/]',
    		            'errors' => array(
    		            	'regex_match' => 'Invalid Zip code.',
    	            	)
    	         	);
    			}
    			if(null !== $this->request->getPost("price") && $this->request->getPost("price") !== ""){
    				$ValidationRulls["price"] = array(
    		            'rules'  => 'required|numeric',
    		            'errors' => array(
    		            	'numeric' => 'Invalid Price.',
    		            	'required' => 'This Price is required.',
    	            	)
    	         	);
    			}
    			if(!$this->validate($ValidationRulls)){
    				$ErrorFields = $this->validator->getErrors();
    				$modalTitle = "Validation Error";
    				$modalContent = '';
    				foreach($ErrorFields as $key => $value){
    					$modalContent .= '<p class=""><b>'.$value.'</b></p>';
    				}	
    				$response["fieldslist"] = $ErrorFields;
    				$response["csrft"] = csrf_hash();
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$response = array();
					$id = explode(',',$this->request->getPost('id'));
					$Model = new CardsModel;
					$susergroupe = session()->get('suser_groupe');
					$suserid = session()->get('suser_id');
					foreach($id as $t => $midss){
						$mids = base64_decode($midss);
						if(preg_match("/^[0-9]{1,50}$/",$mids)){
    						switch($susergroupe){
    							case '1' :
    								$Results = $Model->where(['id' => $mids, 'sellerid' => $suserid, 'selled' => '0'])->find();
    							break;
    							case '9' :
    								$Results = $Model->where(['id' => $mids, 'selled' => '0'])->find();
    							break;	
    						}
    						$countResults = count($Results);
    						if($countResults == 1){
    							$data = [];
    							foreach($this->request->getPost() as $key => $val){
    								if($val != ""){
    								    if($key == 'base'){
    								    	if(strpos($val, '_')){
	    										$val = explode('_', $val);
		        						        $oldname = $Results[0]['base'];
		        						        if(strpos($Results[0]['base'], '_')){
		        						            $basarr = explode("_", $oldname);
		        						            $data['base'] = $basarr[0].'_'.$basarr[1].'_'.$basarr[2].'_'
		        						            .str_replace(' ', '_',ucfirst(strtolower(implode(' ',array_slice($val, 3)))));
		        						        }
		        						        else {
		        						            $data['base'] = date('d_m_Y').'_'.str_replace(' ', '_',ucfirst(strtolower($val)));
		        						        }
	        						    	}
	        						    	else {

	        						    		$oldname = $Results[0]['base'];
		        						        if(strpos($Results[0]['base'], '_')){
		        						            $basarr = explode("_", $oldname);
		        						            $data['base'] = $basarr[0].'_'.$basarr[1].'_'.$basarr[2].'_'.str_replace(' ', '_',ucfirst(strtolower($val)));
		        						        }
		        						        else {
		        						            $data['base'] = date('d_m_Y').'_'.str_replace(' ', '_',ucfirst(strtolower($val)));
		        						        }
		        						        	
	        						        }
    								    	//////////////////////////////
    								        /**$oldname = $Results[0]['base'];
    								        if(strpos($Results[0]['base'],'_')){
    								            $basarr = explode("_", $oldname);
    								            $data['base'] = $basarr[0].'_'.$basarr[1].'_'.$basarr[2].$val;
    								        }
    								        else {
    								            $data['base'] = date('d_m_Y').'_'.$val;
    								        }**/
    								            
    								    }
    								    else {
    								        $data[$key] = $val;    
    								    }
    								}	
    							}
    							$Request = $Model->update($mids, $data);
    							$modalContent = '<p>Card Updateded successfully</p>';
    							$modalTitle = 'Edit succefull';
    							$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
    						}
    						else {
    							$modalContent = '<p>Object not found. E002</p>';
    							$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    						}
						}
						else {
							$modalContent = '<p>Object not selected. E008</p>';
	    					$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
	    					$response["csrft"] = csrf_hash();
						}
					}
					$response["csrft"] = csrf_hash();
					echo json_encode($response);
    			}
    		}
        }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function massrmuserinit(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			exit();
    		}
    		else {
				$id = explode(',',$this->request->getPost('id'));
				foreach($id as $m => $idss){
					$ids = base64_decode($idss);
				    if(!preg_match("/^[0-9]{1,50}$/i", $ids)){
				        $modalContent = '<p>Object not selected. E003</p>';
        				$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
        				$response["csrft"] = csrf_hash();
        				echo json_encode($response);
        				exit();	
				    }
				}
				$countResults = count($id);
				if($countResults >= 2){
					$modalContent = '<form id="MassDeleteForm"></form><p>Do you realy wan to remove those items ?</p>';
					$response["modal"] = createModal($modalContent, 'fade', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "1",['text' => 'Delete', 'functions' =>'data-api="massrmuser"']);
				}
				else {
					$modalContent = '<p>Object not found. E002</p>';
					$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
				}
				$response["csrft"] = csrf_hash();
				header('Content-Type: application/json');
				echo json_encode($response);
    
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function massrmuser(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			exit();
    		}
    		else {
    			
    				$id = explode(',',$this->request->getPost('id'));
    				$Model = new CardsModel;
    				$susergroupe = session()->get("suser_groupe");
    				$suserid = session()->get("suser_id");
    				foreach($id as $m => $idss){
    					$ids = base64_decode($idss);
    				    if(preg_match("/^[0-9]{1,50}$/i", $ids)){
        					switch($susergroupe){
        						case '1' :
        							$Results = $Model->where(['id' => $ids, 'sellerid' => $suserid])->find();
        						break;
        						case '9' :
        							$Results = $Model->where(['id' => $ids])->find();
        						break;	
        					}
    				    }
    				    else {
            				$modalContent = '<p>Object not selected. E003</p>';
            				$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
            				$response["csrft"] = csrf_hash();
            				echo json_encode($response);
            				exit();	
            			}
    					$countResults = count($Results);
    					if($countResults == 1){
    						$Model->delete($Results[0]["id"]);
    						$usermodel = new UsersModel;
    						$getSeller = $usermodel->where(['id' => session()->get('suser_id')])->findAll();
    						if(count($getSeller) == 1){
    							$newNb = $getSeller[0]['seller_nbobjects']-1;
    							$dataseller = [
    								'seller_nbobjects' => $newNb
    							];
    							$usermodel->update($getSeller[0]['id'], $dataseller);
    						}
    
    						$ModelSection = new SectionsModel;
    						$sectionItems = $ModelSection->where(['identifier' => '1'])->findAll();
    						if(count($sectionItems) == 1 ){
    							$newsectionItems = $sectionItems[0]['itemsnumbers']-1;
    							$MysdataItemsSection = [
    								'itemsnumbers' => $newsectionItems
    							];
    
    							$secid = $sectionItems[0]['id'];
    							$ModelSection->update($secid, $MysdataItemsSection);
    						}
    
    						$modalContent = '<p>Item Deleted.</p>';
    						$response["modal"] = createModal($modalContent, 'fade', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					}
    					else {
    						$modalContent = '<p>Object not found. E002</p>';
    						$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					}
    				}					
    				$response["csrft"] = csrf_hash();
    				echo json_encode($response);
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}
//End CC tools
	public function initCreate(){
	    if($this->request->isAJAX()){
    		$verify = verifysection(base64_decode($this->request->getPost('id')));
    		if(null !== $verify){
    		    if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9' || $verify['sellersactivate'] == '0' && session()->get('suser_groupe') != '9' || $verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9'){
            		header('location:'.base_url());
            		exit();
            	}
    		}
    		else {
    		    header('location:'.base_url());
        		exit();
    		}
            	
    		$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			if(preg_match("/^[a-zA-Z0-9]{40}+$/", base64_decode($this->request->getPost('id')))){
    				$secsionID = base64_decode($this->request->getPost('id'));
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', $secsionID)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    
    					$sectionName = $Results[0]['sectioname'];
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `inputs_".$db->escapeString(strtolower($sectionName))."`");
    
    	    			$arrayinputs = $GetSectionConfigs->getResultArray();
    
    					$modalcontent = createForm($arrayinputs, $Results[0]['identifier'], $Results[0]['sectiontype']);
    
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Save', 'functions' => 'data-api="dosave"']);
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    				else {
    					$modalcontent = '<p>An error has been detected, E-001 </p>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    			}
    			else {
    				$modalcontent = '<p>An error has been detected, E-002 </p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
    			$modalcontent = '<p>An error has been detected, E-003 </p>';
    			$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($response);
    			exit();
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function doCreate(){
	    if($this->request->isAJAX()){
    		$verify = verifysection(base64_decode($this->request->getPost('id')));
    		if(null !== $verify){
    		    if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9' || $verify['sellersactivate'] == '0' && session()->get('suser_groupe') != '9' || $verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9'){
            		header('location:'.base_url());
            		exit();
            	}    
    		}
    		else {
    		    echo 'Error !';
    		    exit();
    		}
            	
    		$response = array();
    		$settings = fetchSettings();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			if(preg_match("/^([a-zA-Z0-9])+$/", base64_decode($this->request->getPost('id')))){
    				$secsionID = base64_decode($this->request->getPost('id'));
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', esc($secsionID))->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = $Results[0]['sectioname'];
    					$sectionLable = $Results[0]['sectionlable'];
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `inputs_".strtolower($sectionName)."`");
    	    			$arrayvalidations = $GetSectionConfigs->getResultArray();
    
    	    			$validations = createvalidations($arrayvalidations, $Results[0]['sectiontype']);
    	    			if(!$this->validate($validations)){
    						$ErrorFields = $this->validator->getErrors();
    						$modalTitle = "Validation Error";
    						$modalContent = '';
    						foreach($ErrorFields as $key => $value){
    							$modalContent .= '<p class=""><b>'.$value.'</b></p>';
    						}	
    						$response["fieldslist"] = $ErrorFields;
    						$response["csrft"] = csrf_hash();
    						echo json_encode($response);
    						exit();
    					}
    					else {
    						foreach ($_FILES as $key => $value) {
    							if($_FILES[$key]['name'] != ''){
    								$image = $this->request->getFile($key);
    								$type = $image->getClientMimeType();
    								
    								$extention = pathinfo($image->getName(), PATHINFO_EXTENSION);
    								
    								$allowedExtentions = ['jpg', 'png', 'jpeg'];
    								if(in_array($extention, $allowedExtentions)){
        								if($type == 'image/png' || $type == 'image/jpg' || $type == 'image/jpeg'){
        									if($image->isValid() && !$image->hasMoved()){
        									    $pattern = '/([\$\.\_\-\ ])/i';
        									    $imagenamestamped = preg_replace($pattern,'',substr(password_hash(time(), PASSWORD_DEFAULT),5,11)).'_prod.'.$extention;
        										$data[$key] = $db->escapeString($imagenamestamped);
        										if($key == 'prodimagezeh'){	
        											$image->move('assets/images/products/', $imagenamestamped);
        										}
        										else {
        											$image->move('assets/images/proofs/', $imagenamestamped);
        										}
        									}	
        								}
    							    }
    							    else {
    							        $data[$key] = 'default.png';
    							    }
    							}
    						}
    						foreach($this->request->getPost() as $key => $val){
    							if(!empty($val) && $val != "" && $key != 'id' && $key != 'hashed'){
    								$data[$key] = $val;
    							}
    						}
    						$data['sellerid'] = session()->get('suser_id');
    						$data['sellerusername'] = session()->get('suser_username');
    						$db->table("`section_".$db->escapeString(strtolower($sectionName))."`")->insert($data);
    						//do notification
    						$dataNotif = [
    							'subject' => ucfirst($sectionLable).' Section',
    							'text' => 'New Stuff has been Added',
    							'url' => base_url().'/market/'.$Results[0]['identifier']
    						];
    						$modelNotif = new NotificationsModel;
    						$modelNotif->save($dataNotif);
    						//update users notification number
    						$usersModel = new UsersModel;
    						$ResUsers = $usersModel->findAll();
    						foreach($ResUsers as $value){
    							$newNotifNumbers = $value['notifications_nb']+1;
    							$dataMNotif = [
    								'notifications_nb' => $newNotifNumbers,
    							];
    							$usersModel->update($value['id'], $dataMNotif);
    						}
    						$getSeller = $usersModel->where(['id' => session()->get('suser_id')])->findAll();
    						if(count($getSeller) == 1){
    							$nbobinsell = $getSeller[0]['seller_nbobjects'] + 1;
    							$dataUpdateSeller = [
    								'seller_nbobjects' => $nbobinsell
    							];
    							$usersModel->update($getSeller[0]['id'], $dataUpdateSeller);
    						}
    						if($settings[0]['telenotif'] == '1'){
    						    $teletext = 'New Stuff was added in '.ucfirst($sectionLable).PHP_EOL.'Click here to buy '.ucfirst($sectionLable).PHP_EOL.base_url();
                                telegram($settings[0]['telebot'], $settings[0]['chatid'], $teletext);
    						}
                            
    						//update section items count
    						$newsectionItems = $Results[0]['itemsnumbers'] + 1;
    						$MysdataItemsSection = [
    							'itemsnumbers' => $newsectionItems
    						];
    						$model->update($Results[0]['id'], $MysdataItemsSection);				
    						//send success response 
    						$modalContent = '<p>'.$sectionLable.' Added successfully</p>';
    						$modalTitle = 'Added successfully';
    						$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
    						$response["csrft"] = csrf_hash();
    						header('Content-Type: application/json');
    						echo json_encode($response);
    						exit();
    					}
    				}
    				else {
    					$modalcontent = '<p>An error has been detected, E-001 </p>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Save', 'functions' => 'data-api="edituser-'.$Results[0]['id'].'"']);
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    			}
    			else {
    				$modalcontent = '<p>An error has been detected, E-002 </p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Error !', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
    			$modalcontent = '<p>An error has been detected, E-003 </p>';
    			$response["modal"] = createModal($modalcontent, 'fade animated', 'Error !', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($response);
    			exit();
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function initEditm(){
	    if($this->request->isAJAX()){
    		$verify = verifysection(base64_decode($this->request->getPost('buytype')));
    		if(null !== $verify){
            	if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9' || $verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9'){
            		header('location:'.base_url());
            		exit();
            	}
    		}
    		else {
    		    header('location:'.base_url());
        		exit();
    		}
    		$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			if($this->request->getPost('buytype') != "" 
    			    && preg_match("/^([0-9])+$/", base64_decode($this->request->getPost('id')) ) 
    			    && preg_match("/^([0-9])+$/", base64_decode($this->request->getPost('buytype')) ) 
    			    || preg_match("/^([a-zA-Z0-9])+$/", base64_decode($this->request->getPost('buytype')) )  
    			    && preg_match("/^([0-9])+$/", base64_decode($this->request->getPost('id')) )
    			    ) {
    				$secsionID = base64_decode($this->request->getPost('buytype'));
    				$id = base64_decode($this->request->getPost('id'));
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', $secsionID)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = strtolower($Results[0]['sectioname']);
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `inputs_".$db->escapeString($sectionName)."`");
    	    			$GetSectionData = $db->query("SELECT * FROM `section_".$db->escapeString($sectionName)."` WHERE `id`='".$db->escapeString($id)."'");
    	    			$arrayinputs = $GetSectionConfigs->getResultArray();
    	    			$arraydatas = $GetSectionData->getResultArray();
    	    			$modalcontent = '<form id="createForm" enctype="multipart/form-data">';
        				$modalcontent .= '<div class="row">';
    	    			if(count($arraydatas) == '1'){
    	    				foreach ($arraydatas as $dataKey => $datavalue) {
    	    					$modalcontent .= createFormEdit($arrayinputs, $Results[0]['identifier'], $Results[0]['sectiontype'], esc($datavalue));
    	    				}
    	    			}
    	    			$modalcontent .= '<input type="hidden" name="id" value="'.base64_encode(esc($id)).'">';
    	    			$modalcontent .= '<input type="hidden" name="buytype" value="'.base64_encode($Results[0]['identifier']).'">';
    			    	$modalcontent .= '</div>';
    			    	$modalcontent .= '</form>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Edit Object', '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Update', 'functions' => 'data-api="doedit"']);
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    				else {
    					$modalcontent = '<p>An error has been detected, E-001 </p>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    			}
    			else {
    				$modalcontent = '<p>An error has been detected, E-002 </p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
    			$modalcontent = '<p>An error has been detected, E-003 </p>';
    			$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($response);
    			exit();
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function doEdit(){
	    if($this->request->isAJAX()){
    		$verify = verifysection(base64_decode($this->request->getPost('buytype')));
    		if(null !== $verify){
    		    if($verify['sectionstatus'] == '0' && session()->get('suser_groupe') != '9' || $verify['maintenancemode'] == '1' && session()->get('suser_groupe') != '9'){
            		header('location:'.base_url());
            		exit();
            	}    
    		}
    		else {
    		    header('location:'.base_url());
        		exit();
    		}
    		$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			if($this->request->getPost('id') != "" && preg_match("/^([a-zA-Z0-9])+$/i", base64_decode($this->request->getPost('id')))){
    				$secsionID = base64_decode($this->request->getPost('buytype'));
    				$id = base64_decode($this->request->getPost('id'));
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', $secsionID)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = strtolower($Results[0]['sectioname']);
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `inputs_".$db->escapeString($sectionName)."`");
    	    			$arrayvalidations = $GetSectionConfigs->getResultArray();
    
    	    			$validations = createvalidations($arrayvalidations, $Results[0]['sectiontype']);
    	    			if(!$this->validate($validations)){
    						$ErrorFields = $this->validator->getErrors();
    						$modalTitle = "Validation Error";
    						$modalContent = '';
    						foreach($ErrorFields as $key => $value){
    							$modalContent .= '<p class=""><b>'.$value.'</b></p>';
    						}	
    						$response["fieldslist"] = $ErrorFields;
    						$response["csrft"] = csrf_hash();
    						header('Content-Type: application/json');
    						echo json_encode($response);
    						exit();
    					}
    					else {
    						$usergroupe = session()->get('suser_groupe');
    						$userid = session()->get('suser_id');
    						switch ($usergroupe) {
    							case '1':
    								$GetMSectionData = $db->query("SELECT * FROM `section_".$db->escapeString($sectionName)."` WHERE `id`='".$db->escapeString($id)."' AND `sellerid`='".$db->escapeString($userid)."' AND `selled`='0'");
    							break;
    							case '9':
    								$GetMSectionData = $db->query("SELECT * FROM `section_".$db->escapeString($sectionName)."` WHERE `id`='".$db->escapeString($id)."' AND `selled`='0'");
    							break;
    						}
        					$arraydatas = $GetMSectionData->getResultArray();
        					if(count($arraydatas) == '1'){
        						$sql = [];
    							foreach($this->request->getPost() as $key => $val){
    								if($key != 'id' && $key != 'buytype' && $key != 'hashed'){
    									$data[$key] = $db->escapeString($val);
    									$sql[] = $db->escapeString("`".$key."`")."='".$db->escapeString($val)."'";
    								}
    							}
    							
    							
    							foreach ($_FILES as $key => $value) {
        							if($_FILES[$key]['name'] != ''){
        								$image = $this->request->getFile($key);
        								$type = $image->getClientMimeType();
        								
        								$extention = pathinfo($image->getName(), PATHINFO_EXTENSION);
        								
        								$allowedExtentions = ['jpg', 'png', 'jpeg'];
        								if(in_array($extention, $allowedExtentions)){
            								if($type == 'image/png' || $type == 'image/jpg' || $type == 'image/jpeg'){
            									if($image->isValid() && !$image->hasMoved()){
            									    $pattern = '/([\$\.\_\-\ ])/i';
            									    $imagenamestamped = preg_replace($pattern,'',substr(password_hash(time(), PASSWORD_DEFAULT),5,11)).'_prod.'.$extention;
            										array_push($sql, "`".$db->escapeString($key)."`='".$db->escapeString($imagenamestamped)."'");
            										if($key == 'prodimagezeh'){	
            											$image->move('assets/images/products/', $imagenamestamped);
            										}
            										else {
            											$image->move('assets/images/proofs/', $imagenamestamped);
            										}
            									}	
            								}
        							    }
        							    else {
        							        array_push($sql, "`".$db->escapeString($key)."`='default.png'");
        							    }
        							}
        						}
 
    							$dataKeysme = implode(',', $sql);
    							switch ($usergroupe) {
    								case '1':
    									$updates = $db->query("UPDATE `section_".$db->escapeString($sectionName)."` SET ".$dataKeysme." WHERE `id`='".$db->escapeString($id)."' AND `selled`='0' AND `sellerid`='".$db->escapeString($userid)."'");
    								break;
    								case '9':
    									$updates = $db->query("UPDATE `section_".$db->escapeString($sectionName)."` SET ".$dataKeysme." WHERE `id`='".$db->escapeString($id)."' AND `selled`='0'");
    								break;
    							}
    							
    							$modalContent = '<p>Updated successfully</p>';
    							$modalTitle = 'Updated successfully';
    							$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
    							$response["csrft"] = csrf_hash();
    							
        					}
        					else {
        						$modalcontent = '<p>An error has been detected, E-001 </p>';
    							$response["modal"] = createModal($modalcontent, 'fade animated', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    							$response["csrft"] = csrf_hash();
    							
        					}
        					header('Content-Type: application/json');
    						echo json_encode($response);
    						exit();	
    					}
    				}
    				else {
    					$modalcontent = '<p>An error has been detected, E-001 </p>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    			}
    			else {
    				$modalcontent = '<p>An error has been detected, E-002 </p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
    			$modalcontent = '<p>An error has been detected, E-003 </p>';
    			$response["modal"] = createModal($modalcontent, 'fade animated', 'Error', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($response);
    			exit();
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function initDelete(){
	    if($this->request->isAJAX()){
    		$verify = verifysection(base64_decode($this->request->getPost('buytype')));
        	if($verify['sectionstatus'] == '0'){
        		header('location:'.base_url());
        		exit();
        	}
    		$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			if(preg_match("/^([a-zA-Z0-9])+$/i", base64_decode($this->request->getPost('buytype'))) && preg_match("/^([0-9])+$/i", base64_decode($this->request->getPost('id')))){
    				$secsionID = base64_decode($this->request->getPost('buytype'));
    				$prodid = base64_decode($this->request->getPost('id'));
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', $secsionID)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = $Results[0]['sectioname'];
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `section_".$db->escapeString(strtolower($sectionName))."` WHERE `id`='".$prodid."'");
    
    	    			$arrayinputs = $GetSectionConfigs->getResultArray();
    
    	    			if(count($arrayinputs) === 1){
    	    				if(session()->get('suser_groupe') == '9' || 
    	    					session()->get('suser_groupe') == '1' && $arrayinputs[0]['sellerid'] == session()->get('suser_id')){
    		    				$modalcontent = '<p><span class="text-warning">Warning :</span> Are you sure you want to delete this iteml ?</p>';
    		    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '1', '1', '1', ['text' => 'Delete', 'functions' => 'data-api="dodelete-'.base64_encode($arrayinputs[0]['id']).'|'.base64_encode($Results[0]['identifier']).'"']);
    	    				}
    	    				else {
    	    					$modalcontent = '<p><span class="text-danger">Error :</span> An error has ben detected.</p>';
    	    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '1', '1', '0');
    	    				}
    	    			}
    	    			else {
    	    				$modalcontent = '<p><span class="text-danger">Error :</span> An error has ben detected.</p>';
    	    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '1', '1', '0');
    	    			}
    					
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    				else {
    					$modalcontent = '<p>An error has ben detected, E-001 </p>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    			}
    			else {
    				$modalcontent = '<p>An error has been detected, E-002 </p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
    			$modalcontent = '<p>An error has been detected, E-003 </p>';
    			$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($response);
    			exit();
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function doDelete(){
	    if($this->request->isAJAX()){
    		$verify = verifysection(base64_decode($this->request->getPost('buytype')));
        	if($verify['sectionstatus'] == '0'){
        		header('location:'.base_url());
        		exit();
        	}
    		$response = array();
    		if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){
    			if(base64_decode($this->request->getPost('buytype'))  != ""){
    				$secsionID = base64_decode($this->request->getPost('buytype'));
    				$prodid = base64_decode($this->request->getPost('id'));
    				$model = new SectionsModel;
    				$Results = $model->where('identifier', $secsionID)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = $Results[0]['sectioname'];
    	    			$db = db_connect();
    	    			$GetSectionConfigs = $db->query("SELECT * FROM `section_".$db->escapeString(strtolower($sectionName))."` WHERE `id`='".$prodid."'");
    	    			$arrayinputs = $GetSectionConfigs->getResultArray();
    	    			if(count($arrayinputs) === 1){
    	    				if(session()->get('suser_groupe') == '9' || 
    	    					session()->get('suser_groupe') == '1' && $arrayinputs[0]['sellerid'] == session()->get('suser_id')){
    	    					$sellerID = $arrayinputs[0]['sellerid'];
    
    	    					$usersModel = new UsersModel;
    	    					$isuser = $usersModel->where(['id'=> $sellerID])->findAll();
    	    					if(count($isuser) == '1'){
    	    						$newnsellernbitems = $isuser[0]['seller_nbobjects'] - 1;
    	    						$dataSellernbobjct = [
    	    							'seller_nbobjects' => $newnsellernbitems
    	    						];
    	    						$usersModel->update($isuser[0]['id'], $dataSellernbobjct);
    	    					}
    	    					$GetSectionConfigs = $db->query("DELETE FROM `section_".$db->escapeString(strtolower($sectionName))."` WHERE `id`='".$prodid."'");
    	    					$newsectionItems = $Results[0]['itemsnumbers'] - 1;
    							$MysdataItemsSection = [
    								'itemsnumbers' => $newsectionItems
    							];
    							$model->update($Results[0]['id'], $MysdataItemsSection);
    
    		    				$modalcontent = '<p><span class="text-success">Success :</span> Object Deleted</p>';
    		    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '1', '1', '0');
    	    				}
    	    				else {
    	    					$modalcontent = '<p><span class="text-danger">Error :</span> An error has ben detected.</p>';
    	    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '1', '1', '0');
    	    				}
    	    			}
    	    			else {
    	    				$modalcontent = '<p><span class="text-danger">Error :</span> An error has ben detected.</p>';
    	    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Delete Item', '', 'modal-lg ', '1', '1', '1', '1', '0');
    	    			}
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    				else {
    					$modalcontent = '<p>An error has ben detected, E-001 </p>';
    					$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    					$response["csrft"] = csrf_hash();
    					header('Content-Type: application/json');
    					echo json_encode($response);
    					exit();
    				}
    			}
    			else {
    				$modalcontent = '<p>An error has been detected, E-002 </p>';
    				$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
    		else {
    			$modalcontent = '<p>An error has been detected, E-003 </p>';
    			$response["modal"] = createModal($modalcontent, 'fade animated', 'Create New', '', 'modal-lg ', '1', '1', '1', '1', '0');
    
    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($response);
    			exit();
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function massinitEditOther(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			header('location:'.base_url().'/');
    			exit();
    		}
    		else {
    			if(preg_match("/^[a-zA-Z0-9]{40}$/", base64_decode($this->request->getPost('buytype')))){
    				$buytype = base64_decode($this->request->getPost('buytype'));
    				$modelSections = new SectionsModel;
    				$Results = $modelSections->where('identifier', $buytype)->findAll();
    				$countresults = count($Results);
    				if($countresults == 1){
    					$sectionName = strtolower($Results[0]['identifier']);
    				}
    			}
    			else {
    				header('location:'.base_url().'/');
    				exit();	
    			}
    			$response = array();
    			$form = '
    				<form id="MasseditUserForm">
    					<div class="form-group row">
    						<div class="col-12">
    							<label>Price</label>
    							<input type="number" id="price" name="price" class="form-control">
    							<small class="price text-danger"></small>
    							<input type="hidden" name="'.csrf_token().'" value="'.csrf_hash().'">
    							<input type="hidden" name="buytype" value="'.base64_encode($sectionName).'">
    						</div>
    					</div>
    				</form>
    			'; 
    			$modalContent = $form;
    			$response["modal"] = createModal($modalContent, 'fade', 'Edit', 'text-primary', 'modal-lg', "1", "1", "1", "1", "1", ['text' => 'Save', 'functions' => 'data-api="massedit"']);
    			$response["csrft"] = csrf_hash();
    			header('Content-Type: application/json');
    			echo json_encode($response);
    			exit();
    			
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function masseditOther(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") !== "9" && session()->get("suser_groupe") !== "1"){
    			exit();
    		}
    		else {
    			$response = array();
				$ValidationRulls = [ 
					"price" => [
						'label' => 'Price',
		            	'rules'  => 'required|numeric',
		            	'errors' => [
		            		'numeric' => 'Invalid referals rate.',
		            		'required' => 'This Price is required.',
	            		]
	         		]
         		];
    			if(!$this->validate($ValidationRulls)){
    				$ErrorFields = $this->validator->getErrors();
    				$modalTitle = "Validation Error";
    				$modalContent = '';
    				foreach($ErrorFields as $key => $value){
    					$modalContent .= '<p class=""><b>'.$value.'</b></p>';
    				}	
    				$response["fieldslist"] = $ErrorFields;
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$response = array();
    				if(preg_match("/^[a-zA-Z0-9]{40}$/", base64_decode($this->request->getPost('buytype')))){
    					$ids = $this->request->getPost('id') ? $this->request->getPost('id') : null;
	    				if($ids != null){
	    					$buytype = base64_decode($this->request->getPost('buytype'));
	    					$id = explode(',',$ids);
	    					$modelSections = new SectionsModel;
	    					$Results = $modelSections->where('identifier', $buytype)->findAll();
	    					$countresults = count($Results);
	    					if($countresults == 1){
	    						$sectionName = strtolower($Results[0]['sectioname']);
	    		    			$db = db_connect();
	    		    			$usergroupe = session()->get('suser_groupe');
	    		    			$userid = session()->get('suser_id');
	    		    			foreach($id as $t => $midss){
	    		    				if(preg_match("/^[0-9]{1,50}$/", base64_decode($midss))){

	    		    					$mids = base64_decode($midss);
		    			    			switch ($usergroupe) {
		    			    				case '9':
		    			    					$GetSectionConfigs = $db->query("SELECT * FROM `section_".$db->escapeString($sectionName)."` WHERE `id`='".$db->escapeString($mids)."' AND `selled`='0'");		
		    		    					break;
		    			    				case '1':
		    			    					$GetSectionConfigs = $db->query("SELECT * FROM `section_".$db->escapeString($sectionName) ."` WHERE `id`='".$db->escapeString($mids)."' AND `sellerid`='".$db->escapeString($userid)."' AND `selled`='0'");	
		    		    					break;
		    			    			}
		    			    			$ResultsItem =  $GetSectionConfigs->getResultArray();
		    			    			if(count($ResultsItem) == 1){
		    			    				$data = [];
		    								foreach($this->request->getPost() as $key => $val){
		    									if($val != ""){
		    										if($key != "hashed" && $key != 'buytype' && $key != 'id'){
		    											$data[] = "`".$db->escapeString($key) ."`='".$db->escapeString($val)."'";
		    										}											
		    									}	
		    								}
		    								$dataSql = implode(',', $data);
		    								
		    								$idss = explode(',', $this->request->getPost('id'));

		    								foreach($idss as $y => $ess){
		    									$es = base64_decode($ess);
		    									switch ($usergroupe) {
		    										case '1':
		    											$updates = $db->query("UPDATE `section_".$db->escapeString($sectionName)."` SET ".$dataSql." WHERE `id`='".$db->escapeString($es)."' AND `selled`='0' AND `sellerid`='".$db->escapeString($userid)."'");
		    										break;
		    										case '9':
		    											$updates = $db->query("UPDATE `section_".$db->escapeString($sectionName)."` SET ".$dataSql." WHERE `id`='".$db->escapeString($es)."' AND `selled`='0'");
		    										break;
		    									}
		    								}
		    								$modalContent = '<p>Updated successfully</p>';
		    								$modalTitle = 'Updated successfully';
		    								$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
		    								$response["csrft"] = csrf_hash();
		    								
		    	    					}
		    	    					else {
		    	    						$modalTitle = 'Error';
		    	    						$modalContent = '<p>An error has been detected, E-001 </p>';
		    								$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
		    								$response["csrft"] = csrf_hash();
		    								
		    	    					}
		    	    				}
		    	    				else {
		    	    					$modalTitle = 'Error';
		    	    					$modalContent = '<p>An error has been detected, E-002 </p>';
	    								$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
	    								$response["csrft"] = csrf_hash();

		    	    				}
	    	    					header('Content-Type: application/json');
	    							echo json_encode($response);
	    							exit();	
	    		    			}
	    		    		}
	    		    		else {
	    		    			$modalTitle = 'Error';
	    		    			$modalContent = '<p>An error has been detected, E-003 </p>';
	    						$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
	    
	    						$response["csrft"] = csrf_hash();
	    						header('Content-Type: application/json');
	    						echo json_encode($response);
	    						exit();
	    		    		}
	    				}
	    				else {
	    					$modalTitle = 'Error';
	    					$modalContent = '<p>Object not selected. E004</p>';
	    					$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
	    					$response["csrft"] = csrf_hash();
	    					echo json_encode($response);
	    					exit();	
	    				}
	    			}
	    			else {
	    				$modalTitle = 'Error';
	    				$modalContent = '<p>Object not selected. E005</p>';
    					$response["modal"] = createModal($modalContent, 'fade', $modalTitle, '', 'modal-lg', "1", "1", "1", "1", "0");
    					$response["csrft"] = csrf_hash();
    					echo json_encode($response);
    					exit();	
	    			}
    			}
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}

	public function massrmuserothe(){
	    if($this->request->isAJAX()){
    		if(session()->get("suser_groupe") != "9" && session()->get("suser_groupe") != "1"){
    			header('location:'.base_url().'/');
    			exit();
    		}
    		else {
    			$id = explode(',', $this->request->getPost('id'));
				$buytype = base64_decode($this->request->getPost('buytype'));
				if(preg_match("/^[a-zA-Z0-9]{40}$/", $buytype)){
					$modelSections = new SectionsModel;
					$Results = $modelSections->where('identifier', $buytype)->findAll();
					$countresults = count($Results);
					if($countresults == 1){
						$sectionName = $Results[0]['sectioname'];
					}
				}
				else {
					$modalContent = '<p>Object not found. E001</p>';
					$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
				}
    			$db = db_connect();
    			$countResults = count($id);
    			if($countResults >= 2){
    				$susergroupe = session()->get("suser_groupe");
    				$suserid = session()->get("suser_id");
    				foreach($id as $m => $idss){
    					$ids = base64_decode($idss);
    					if(preg_match("/^[0-9]{1,50}$/", $ids)){
    						switch($susergroupe){
	    						case '9':
	    	    					$GetSectionConfigs = $db->query("DELETE FROM `section_".$db->escapeString($sectionName)."` WHERE `id`='".$db->escapeString($ids)."' AND `selled`='0'");		
	        					break;
	    	    				case '1':
	    	    					$GetSectionConfigs = $db->query("DELETE FROM `section_".$db->escapeString($sectionName) ."` WHERE `id`='".$db->escapeString($ids)."' AND `sellerid`='".$db->escapeString(session()->get('suser_id'))."' AND `selled`=0");
	    	    				break;
	    					}
	    					$modalContent = '<p>Item Deleted.</p>';
	    					$usermodel = new UsersModel;
	    					$getSeller = $usermodel->where(['id' => session()->get('suser_id')])->findAll();
	    					if(count($getSeller) == 1){
	    						$newNb = $getSeller[0]['seller_nbobjects']-1;
	    						$dataseller = [
	    							'seller_nbobjects' => $newNb
	    						];
	    						$usermodel->update($getSeller[0]['id'], $dataseller);
	    					}
	    
	    					$ModelSection = new SectionsModel;
	    					$sectionItems = $ModelSection->where(['identifier' => '1'])->findAll();
	    					if(count($sectionItems) == 1 ){
	    						$newsectionItems = $sectionItems[0]['itemsnumbers']-1;
	    						$MysdataItemsSection = [
	    							'itemsnumbers' => $newsectionItems
	    						];
	    
	    						$secid = $sectionItems[0]['id'];
	    						$ModelSection->update($secid, $MysdataItemsSection);
	    					}
    					}
    					else {
    						$modalContent = '<p>Object not found. E002</p>';
    						$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    					}
    				}	
    				
    				$response["modal"] = createModal($modalContent, 'fade', 'Delete Item', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");				
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    			else {
    				$modalContent = '<p>Object not found. E003</p>';
    				$response["modal"] = createModal($modalContent, 'fade', 'Error', 'text-danger', 'modal-lg', "1", "1", "1", "1", "0");
    				$response["csrft"] = csrf_hash();
    				header('Content-Type: application/json');
    				echo json_encode($response);
    				exit();
    			}
    		}
	    }
        else {
	        echo "Nice try ;)";
	        exit();
	    }
	}
}

