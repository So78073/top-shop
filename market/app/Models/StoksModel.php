<?php namespace App\Models;

use CodeIgniter\Model;
use App\Helpers\Global_helper;

class StoksModel extends Model {
    protected $db;
    public function __construct(){
        $this->db = \Config\Database::connect();
        helper('Global_helper') ;
    }
    public function initTable($status){
    	$dbs = db_connect();
		$selid = session()->get('suser_id');
		if(session()->get('suser_groupe') == '1'){
			switch ($status) {
				case '0':
					$builder = $this->db->table("cards")->where("`selled`='0'  AND `baseapproved`='1' AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
				case '1':
					$builder = $this->db->table("cards")->where("`selled`='1'  AND `baseapproved`='1' AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
				case '2':
					$builder = $this->db->table("cards")->where("`selled`='1'  AND `baseapproved`='1' AND `refunded`='1' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
				default:
					$builder = $this->db->table("cards")->where("`selled`='0'  AND `baseapproved`='1' AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
			}
			
		}
		else if(session()->get('suser_groupe') == '9') {
			switch ($status) {
				case '0':
					$builder = $this->db->table("cards")->where("`selled`='0' AND `baseapproved`='1' AND `refunded`='0'")->orderBy('id', 'asc');
				break;
				case '1':
					$builder = $this->db->table("cards")->where("`selled`='1' AND `baseapproved`='1' AND `refunded`='0'")->orderBy('id', 'asc');
				break;
				case '2':
					$builder = $this->db->table("cards")->where("`selled`='1' AND `baseapproved`='1' AND `refunded`='1'")->orderBy('id', 'asc');
				break;
				default:
					$builder = $this->db->table("cards")->where("`selled`='0' AND `baseapproved`='1' AND `refunded`='0'")->orderBy('id', 'asc');
				break;
			}
			
		}
		else {
			exit();
		}

        $setting = [
            "setTable" => $builder,
            "setOutput" => [
            	function($row){
    				$countCarts = count(session()->get('cart'));
					if($countCarts > 0){
						$cartsid = [];
						$types = [];
						foreach(session()->get('cart') as $kk => $vv){
							if($vv['typebuying'] == "1"){ 
								$cartsid[] = $vv['id'];
								$types[] = $vv['typebuying']; 
							}	
						}
						$myCombinedArray = array_combine($cartsid, $types);
					}
					else {
						$myCombinedArray = [];
					}

        			if($row['sellerid'] == session()->get('suser_id') && $row['selled'] == '0' || session()->get('suser_groupe') == '9' && $row['selled'] == '0'){
						$check = '<label><input type="checkbox" class="addtocart" data-id="'.base64_encode($row["id"]).'"  id="'.base64_encode($row["id"]).'"><label>';
						return $check;
					}
            	},

	           	function($row){
        			$base = esc($row['base']);
					return $base;
            	},

            	function($row){
        			$bin = $row['number'].'|'.$row['expiration'].'|'.$row['cvv'].'|'.$row['country'];
					return $bin;
            	},

            	function($row){
        			if($row['refun'] == "1"){
						$refun = '<div class="text-success text-center"><b>Yes</b></div>';
					}
					else {
						$refun = '<div class="text-danger text-center"><b>No</b></div>';
					}
					return $refun;
            	},
				function($row){
					switch (session()->get("statusGet")){
						case '0':
							$price = '<div class="text-center">$'.number_format($row['price'], 2, '.', '').'</div>';
						break;
						case '1':
							$price = '<div class="text-center"><span class="text-success">+$'.number_format($row['price'], 2, '.', '').'</span></div>';
						break;
						case '2':
							$price = '<div class="text-center"><span class="text-danger">-$'.number_format($row['price'], 2, '.', '').'</span></div>';
						break;
						default:
							$price = '<div class="text-center">$'.number_format($row['price'], 2, '.', '').'</div>';
						break;
					}
        			
					return $price;
            	},
                function($row){
                	switch (session()->get("statusGet")){
                		case '0' :
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<button type="button" data-api="editinit-'.base64_encode($row['id']).'" class="btn btn-warning btn-sm"><span class="bx bx-edit"></span></button>
									<button type="button" data-api="rminit-'.base64_encode($row['id']).'" class="btn btn-danger btn-sm"><span class="bx bx-trash"></span></button>
								</div>
							</div>';
                		break;
                		case '1':
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<span class="btn btn-success btn-sm">Sold</span>
								</div>
							</div>';
            			break;
            			case '2':
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<span class="btn btn-danger btn-sm">Refunded</span>
								</div>
							</div>';
            			break;
            			default :
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<button type="button" data-api="editinit-'.base64_encode($row['id']).'" class="btn btn-warning btn-sm"><span class="bx bx-edit"></span></button>
									<button type="button" data-api="rminit-'.base64_encode($row['id']).'" class="btn btn-danger btn-sm"><span class="bx bx-trash"></span></button>
								</div>
							</div>';
                		break;
                	}
                	//$id = '<input type="checkbox" data-api="toggleSingle-this" class="selected" id="'.$row["id"].'"> #'.$row["id"];
					
					return $Buy;
                }
            ]
        ];
        return $setting;
    }

    public function initTableCpanels($status){
    	$dbs = db_connect();
		$selid = session()->get('suser_id');
		if(session()->get('suser_groupe') == '1'){
			switch ($status) {
				case '0':
					$builder = $this->db->table("cpanel")->where("`selled`='0'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
				case '1':
					$builder = $this->db->table("cpanel")->where("`selled`='1'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
				case '2':
					$builder = $this->db->table("cpanel")->where("`selled`='1'  AND `refunded`='1' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
				default:
					$builder = $this->db->table("cpanel")->where("`selled`='0'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
			}
			
		}
		else if(session()->get('suser_groupe') == '9') {
			switch ($status) {
				case '0':
					$builder = $this->db->table("cpanel")->where("`selled`='0' AND `refunded`='0'")->orderBy('id', 'asc');
				break;
				case '1':
					$builder = $this->db->table("cpanel")->where("`selled`='1' AND `refunded`='0'")->orderBy('id', 'asc');
				break;
				case '2':
					$builder = $this->db->table("cpanel")->where("`selled`='1' AND `refunded`='1'")->orderBy('id', 'asc');
				break;
				default:
					$builder = $this->db->table("cpanel")->where("`selled`='0' AND `refunded`='0'")->orderBy('id', 'asc');
				break;
			}
			
		}
		else {
			exit();
		}

        $setting = [
            "setTable" => $builder,
            "setOutput" => [
            	function($row){
    				$countCarts = count(session()->get('cart'));
					if($countCarts > 0){
						$cartsid = [];
						$types = [];
						foreach(session()->get('cart') as $kk => $vv){
							if($vv['typebuying'] == "2"){ 
								$cartsid[] = $vv['id'];
								$types[] = $vv['typebuying']; 
							}	
						}
						$myCombinedArray = array_combine($cartsid, $types);
					}
					else {
						$myCombinedArray = [];
					}

        			if($row['sellerid'] == session()->get('suser_id') && $row['selled'] == '0' || session()->get('suser_groupe') == '9' && $row['selled'] == '0'){
						$check = '<label><input type="checkbox" class="addtocart" data-id="'.base64_encode($row["id"]).'"  id="'.base64_encode($row["id"]).'-cpanel"><label>';
						return $check;
					}
            	},

	           	function($row){
        			$host = esc($row['host']);
					return $host;
            	},

            	function($row){
        			$port = esc($row['port']);
        			switch ($port) {
        				case 'HTTP':
        					$mport = '<div class"text-center"><center><span class="btn btn-danger btn-sm rounded-3">HTTP</span></center></div>';
        					break;
        				case 'HTTPS':
        					$mport = '<div class"text-center"><center><span class="btn btn-success btn-sm rounded-3">HTTPS</span></center></div>';
        					break;
        				default:
        					$mport = '<div class"text-center"><center><span class="btn btn-danger btn-sm rounded-3">HTTP</span></center></div>';
        					break;
        			}
					return $mport;
            	},
            	function($row){
        			$user = $row['username'];
					return $user;
            	},
            	function($row){
        			$pass = $row['password'];
					return $pass;
            	},
            	function($row){
        			$infos = $row['tld'];
					return $infos;
            	},

				function($row){
        			$hoster = $row['hoster'];
					return $hoster;
            	},


				function($row){
					switch (session()->get("statusGet")){
						case '0':
							$price = '<div class="text-center">$'.number_format($row['price'], 2, '.', '').'</div>';
						break;
						case '1':
							$price = '<div class="text-center"><span class="text-success">+$'.number_format($row['price'], 2, '.', '').'</span></div>';
						break;
						case '2':
							$price = '<div class="text-center"><span class="text-danger">-$'.number_format($row['price'], 2, '.', '').'</span></div>';
						break;
						default:
							$price = '<div class="text-center">$'.number_format($row['price'], 2, '.', '').'</div>';
						break;
					}
        			
					return $price;
            	},
                function($row){
                	switch (session()->get("statusGet")){
                		case '0' :
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<button type="button" data-api="editinit-cpanel|'.base64_encode($row['id']).'" class="btn btn-warning btn-sm"><span class="bx bx-edit"></span></button>
									<button type="button" data-api="rminit-cpanel|'.base64_encode($row['id']).'" class="btn btn-danger btn-sm"><span class="bx bx-trash"></span></button>
								</div>
							</div>';
                		break;
                		case '1':
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<span class="btn btn-success btn-sm">Sold</span>
								</div>
							</div>';
            			break;
            			case '2':
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<span class="btn btn-danger btn-sm">Refunded</span>
								</div>
							</div>';
            			break;
            			default :
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<button type="button" data-api="editinit-'.base64_encode($row['id']).'" class="btn btn-warning btn-sm"><span class="bx bx-edit"></span></button>
									<button type="button" data-api="rminit-'.base64_encode($row['id']).'" class="btn btn-danger btn-sm"><span class="bx bx-trash"></span></button>
								</div>
							</div>';
                		break;
                	}
                	//$id = '<input type="checkbox" data-api="toggleSingle-this" class="selected" id="'.$row["id"].'"> #'.$row["id"];
					
					return $Buy;
                }
            ]
        ];
        return $setting;
    }

    public function initTableRdp($status){
    	$dbs = db_connect();
		$selid = session()->get('suser_id');
		if(session()->get('suser_groupe') == '1'){
			switch ($status) {
				case '0':
					$builder = $this->db->table("rdp")->where("`selled`='0'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
				case '1':
					$builder = $this->db->table("rdp")->where("`selled`='1'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
				case '2':
					$builder = $this->db->table("rdp")->where("`selled`='1'  AND `refunded`='1' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
				default:
					$builder = $this->db->table("rdp")->where("`selled`='0'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
			}
			
		}
		else if(session()->get('suser_groupe') == '9') {
			switch ($status) {
				case '0':
					$builder = $this->db->table("rdp")->where("`selled`='0' AND `refunded`='0'")->orderBy('id', 'asc');
				break;
				case '1':
					$builder = $this->db->table("rdp")->where("`selled`='1' AND `refunded`='0'")->orderBy('id', 'asc');
				break;
				case '2':
					$builder = $this->db->table("rdp")->where("`selled`='1' AND `refunded`='1'")->orderBy('id', 'asc');
				break;
				default:
					$builder = $this->db->table("rdp")->where("`selled`='0' AND `refunded`='0'")->orderBy('id', 'asc');
				break;
			}
			
		}
		else {
			exit();
		}

        $setting = [
            "setTable" => $builder,
            "setOutput" => [
            	function($row){
    				$countCarts = count(session()->get('cart'));
					if($countCarts > 0){
						$cartsid = [];
						$types = [];
						foreach(session()->get('cart') as $kk => $vv){
							if($vv['typebuying'] == "3"){ 
								$cartsid[] = $vv['id'];
								$types[] = $vv['typebuying']; 
							}	
						}
						$myCombinedArray = array_combine($cartsid, $types);
					}
					else {
						$myCombinedArray = [];
					}

        			if($row['sellerid'] == session()->get('suser_id') && $row['selled'] == '0' || session()->get('suser_groupe') == '9' && $row['selled'] == '0'){
						$check = '<label><input type="checkbox" class="addtocart" data-id="'.base64_encode($row["id"]).'"  id="'.base64_encode($row["id"]).'-rdp"><label>';
						return $check;
					}
            	},

	           	function($row){
        			$host = esc($row['host']);
					return $host;
            	},

            	function($row){
        			$country = $row['country'];
					return '<img src="'.esc($country).'" style="width:30px" alt="country">';
            	},

            	function($row){
        			$user = esc($row['user']);
					return $user;
            	},
            	function($row){
        			$pass = esc($row['pass']);
					return $pass;
            	},

				function($row){
        			$hoster = esc($row['hoster']);
					return $hoster;
            	},

            	function($row){
        			$system = esc($row['system']);
					return $system;
            	},

            	function($row){
        			$ram = esc($row['ram']);
					return $ram;
            	},

            	function($row){
        			$hddsize = esc($row['hddsize']);
					return $hddsize;
            	},


				function($row){
					switch (session()->get("statusGet")){
						case '0':
							$price = '<div class="text-center">$'.number_format($row['price'], 2, '.', '').'</div>';
						break;
						case '1':
							$price = '<div class="text-center"><span class="text-success">+$'.number_format($row['price'], 2, '.', '').'</span></div>';
						break;
						case '2':
							$price = '<div class="text-center"><span class="text-danger">-$'.number_format($row['price'], 2, '.', '').'</span></div>';
						break;
						default:
							$price = '<div class="text-center">$'.number_format($row['price'], 2, '.', '').'</div>';
						break;
					}
        			
					return $price;
            	},
                function($row){
                	switch (session()->get("statusGet")){
                		case '0' :
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<button type="button" data-api="editinit-rdp|'.base64_encode($row['id']).'" class="btn btn-warning btn-sm"><span class="bx bx-edit"></span></button>
									<button type="button" data-api="rminit-rdp|'.base64_encode($row['id']).'" class="btn btn-danger btn-sm"><span class="bx bx-trash"></span></button>
								</div>
							</div>';
                		break;
                		case '1':
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<span class="btn btn-success btn-sm">Sold</span>
								</div>
							</div>';
            			break;
            			case '2':
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<span class="btn btn-danger btn-sm">Refunded</span>
								</div>
							</div>';
            			break;
            			default :
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<button type="button" data-api="editinit-'.base64_encode($row['id']).'" class="btn btn-warning btn-sm"><span class="bx bx-edit"></span></button>
									<button type="button" data-api="rminit-'.base64_encode($row['id']).'" class="btn btn-danger btn-sm"><span class="bx bx-trash"></span></button>
								</div>
							</div>';
                		break;
                	}
                	//$id = '<input type="checkbox" data-api="toggleSingle-this" class="selected" id="'.$row["id"].'"> #'.$row["id"];
					
					return $Buy;
                }
            ]
        ];
        return $setting;
    }

    public function initTableSmtp($status){
    	$dbs = db_connect();
		$selid = session()->get('suser_id');
		if(session()->get('suser_groupe') == '1'){
			switch ($status) {
				case '0':
					$builder = $this->db->table("smtp")->where("`selled`='0'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
				case '1':
					$builder = $this->db->table("smtp")->where("`selled`='1'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
				case '2':
					$builder = $this->db->table("smtp")->where("`selled`='1'  AND `refunded`='1' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
				default:
					$builder = $this->db->table("smtp")->where("`selled`='0'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD(`id`, '".$dbs->escapeString($selid)."', 'asc' )");
				break;
			}
			
		}
		else if(session()->get('suser_groupe') == '9') {
			switch ($status) {
				case '0':
					$builder = $this->db->table("smtp")->where("`selled`='0' AND `refunded`='0'")->orderBy('id', 'asc');
				break;
				case '1':
					$builder = $this->db->table("smtp")->where("`selled`='1' AND `refunded`='0'")->orderBy('id', 'asc');
				break;
				case '2':
					$builder = $this->db->table("smtp")->where("`selled`='1' AND `refunded`='1'")->orderBy('id', 'asc');
				break;
				default:
					$builder = $this->db->table("smtp")->where("`selled`='0' AND `refunded`='0'")->orderBy('id', 'asc');
				break;
			}
			
		}
		else {
			exit();
		}

        $setting = [
            "setTable" => $builder,
            "setOutput" => [
            	function($row){
    				$countCarts = count(session()->get('cart'));
					if($countCarts > 0){
						$cartsid = [];
						$types = [];
						foreach(session()->get('cart') as $kk => $vv){
							if($vv['typebuying'] == "4"){ 
								$cartsid[] = $vv['id'];
								$types[] = $vv['typebuying']; 
							}	
						}
						$myCombinedArray = array_combine($cartsid, $types);
					}
					else {
						$myCombinedArray = [];
					}

        			if($row['sellerid'] == session()->get('suser_id') && $row['selled'] == '0' || session()->get('suser_groupe') == '9' && $row['selled'] == '0'){
						$check = '<label><input type="checkbox" class="addtocart" data-id="'.base64_encode($row["id"]).'"  id="'.base64_encode($row["id"]).'-smtp"><label>';
						return $check;
					}
            	},

	           	function($row){
        			$host = esc($row['host']);
					return $host;
            	},

            	function($row){
        			$port = esc($row['port']);
					return $port;
            	},

            	function($row){
        			$user = esc($row['user']);
					return $user;
            	},
            	function($row){
        			$pass = esc($row['pass']);
					return $pass;
            	},

				function($row){
        			$hoster = esc($row['hoster']);
					return $hoster;
            	},


            	function($row){
        			$country = $row['country'];
					return '<img src="'.esc($country).'" style="width:30px" alt="country">';
            	},


				function($row){
					switch (session()->get("statusGet")){
						case '0':
							$price = '<div class="text-center">$'.number_format($row['price'], 2, '.', '').'</div>';
						break;
						case '1':
							$price = '<div class="text-center"><span class="text-success">+$'.number_format($row['price'], 2, '.', '').'</span></div>';
						break;
						case '2':
							$price = '<div class="text-center"><span class="text-danger">-$'.number_format($row['price'], 2, '.', '').'</span></div>';
						break;
						default:
							$price = '<div class="text-center">$'.number_format($row['price'], 2, '.', '').'</div>';
						break;
					}
        			
					return $price;
            	},
                function($row){
                	switch (session()->get("statusGet")){
                		case '0' :
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<button type="button" data-api="editinit-smtp|'.base64_encode($row['id']).'" class="btn btn-warning btn-sm"><span class="bx bx-edit"></span></button>
									<button type="button" data-api="rminit-smtp|'.base64_encode($row['id']).'" class="btn btn-danger btn-sm"><span class="bx bx-trash"></span></button>
								</div>
							</div>';
                		break;
                		case '1':
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<span class="btn btn-success btn-sm">Sold</span>
								</div>
							</div>';
            			break;
            			case '2':
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<span class="btn btn-danger btn-sm">Refunded</span>
								</div>
							</div>';
            			break;
            			default :
                			$Buy = '<div class="text-center">
								<div class="btn-groupe">
									<button type="button" data-api="editinit-'.base64_encode($row['id']).'" class="btn btn-warning btn-sm"><span class="bx bx-edit"></span></button>
									<button type="button" data-api="rminit-'.base64_encode($row['id']).'" class="btn btn-danger btn-sm"><span class="bx bx-trash"></span></button>
								</div>
							</div>';
                		break;
                	}
                	//$id = '<input type="checkbox" data-api="toggleSingle-this" class="selected" id="'.$row["id"].'"> #'.$row["id"];
					
					return $Buy;
                }
            ]
        ];
        return $setting;
    }

    public function initTableOthers($status, $tableName, $identifier, $sectionSelltype){
    	$dbs = db_connect();
		$selid = session()->get('suser_id');
		$sectionName = session()->get('sectionName');
		$data['sectionName'] = $sectionName;
		$dbb = db_connect();
		$GetSectionConfigs = $dbb->query("SELECT * FROM `table_".$dbb->escapeString(strtolower($data['sectionName']))."`");
		$theTable = array();
		$thetypes = array();
		foreach($GetSectionConfigs->getResultArray() as $key => $val){;
			if($val["cellsTypes"] !== 'hide'){
				$theTable[] = $val["cellsName"];
				$thetypes[] = $val["cellsTypes"];
				
			}
		}
		$theTable[] = 'id';
		$thetypes[] = 'id';
		
		$theTableValues = array_values($theTable);
		$theTableTypes = array_values($thetypes);

		if(session()->get('suser_groupe') == '1'){

			switch ($status) {
				case '0':
					$builder = $this->db->table($tableName)->where("`selled`='0'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD('id', '".$dbs->escapeString($selid)."', 'asc' )");
					$mybuilder = $this->db->table($tableName)->where("`selled`='0'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD('id', '".$dbs->escapeString($selid)."', 'asc' )")->get();
				break;
				case '1':
					$builder = $this->db->table($tableName)
					->where("
						`selled`='1' 
						AND `refunded`='0' 
						AND `sellerid`=
						'".$dbs->escapeString($selid)."'")
					->orwhere("
						`selled`='0' 
						AND `selledtimes`>'0' 
						AND `refunded`='0' 
						AND `sellerid`='".$dbs->escapeString($selid)."' 
						ORDER BY 
						FIELD('id', '".$dbs->escapeString($selid)."', 'asc' )");

					$mybuilder = $this->db->table($tableName)
					->where("
						`selled`='1'  
						AND `refunded`='0' 
						AND `sellerid`='".$dbs->escapeString($selid)."'")
					->orwhere("
						`selled`='0' 
						AND `selledtimes`>'0' 
						AND `refunded`='0' 
						AND `sellerid`='".$dbs->escapeString($selid)."' 
						ORDER BY 
						FIELD('id', '".$dbs->escapeString($selid)."', 'asc' )")
					->get();
				break;
				case '2':
					$builder = $this->db->table($tableName)->where("`selled`='1'  AND `refunded`='1' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD('id', '".$dbs->escapeString($selid)."', 'asc' )");
					$mybuilder = $this->db->table($tableName)->where("`selled`='1'  AND `refunded`='1' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD('id', '".$dbs->escapeString($selid)."', 'asc' )")->get();
				break;
				default:
					$builder = $this->db->table($tableName)->where("`selled`='0'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD('id', '".$dbs->escapeString($selid)."', 'asc' )");
					$mybuilder = $this->db->table($tableName)->where("`selled`='0'  AND `refunded`='0' AND `sellerid`='".$dbs->escapeString($selid)."' ORDER BY FIELD('id', '".$dbs->escapeString($selid)."', 'asc' )")->get();
				break;
			}
			
		}
		else if(session()->get('suser_groupe') == '9') {
			switch ($status) {
				case '0':
					$builder = $this->db->table($tableName)->where("`selled`='0' AND `refunded`='0'")->orderBy('id', 'asc');
					$mybuilder= $this->db->table($tableName)->where("`selled`='0' AND `refunded`='0'")->orderBy('id', 'asc')->get();
				break;
				case '1':
					if($sectionSelltype == '0'){
						$builder = $this->db->table($tableName)->where("`selled`='1' AND `refunded`='0' OR WHERE `selled`='1'  AND `refunded`='0' AND `selledtimes` > '0'")->orderBy('id', 'asc');
						$mybuilder = $this->db->table($tableName)->where("`selled`='1' AND `refunded`='0' OR WHERE `selled`='1'  AND `refunded`='0' AND `selledtimes` > '0'")->orderBy('id', 'asc')->get();
					}
					else {
						$builder = $this->db->table($tableName)->where("`selledtimes`>'0' AND `refunded`='0'")->orderBy('id', 'asc');
						$mybuilder = $this->db->table($tableName)->where("`selledtimes`>'0' AND `refunded`='0'")->orderBy('id', 'asc')->get();
					}
						
				break;
				case '2':
					$builder = $this->db->table($tableName)->where("`selled`='1' AND `refunded`='1'")->orderBy('id', 'asc');
					$mybuilder = $this->db->table($tableName)->where("`selled`='1' AND `refunded`='1'")->orderBy('id', 'asc')->get();
				break;
				default:
					$builder = $this->db->table($tableName)->where("`selled`='0' AND `refunded`='0'")->orderBy('id', 'asc');
					$mybuilder = $this->db->table($tableName)->where("`selled`='0' AND `refunded`='0'")->orderBy('id', 'asc')->get();
				break;
			}
			
		}
		else {
			exit();
		}

		function madrow($mybuilder, $theTableValues, $theTableTypes, $identifier, $status){
			$zarray = [];
			$sellertools = '';
    		foreach($mybuilder->getResultArray() as $pin => $cin){
    			
    			$o = 0;
    			if($cin['sellerid'] == session()->get("suser_id")){
    				$id = $cin['id'];
	    			foreach($cin as $cinkey => $valkey){
	    				if(in_array($cinkey, $theTableValues)){
		    				$zarray[$o] = function($row)use($cinkey, $o, $theTableTypes, $identifier, $cin){
    							if($cinkey == 'price'){
    								if($cin['selled'] == '1'){
    									return '<span class="text-success">+$'.number_format($row[$cinkey],2,'.','').'</span>';
    								}
    								if($cin['refunded'] == '1'){
    									return '<span class="text-danger">-$'.number_format($row[$cinkey],2,'.','').'</span>';
    								}
    								else {
    									return '$'.number_format($row[$cinkey],2,'.','');
    								}
		    					}
		    					else if($cinkey == 'addon'){
		    						$ndat = new \DateTime($row[$cinkey]);
		    						return $ndat->format('d/m/Y');
		    					}
		    					else if($cinkey == 'id' && $cin['selled'] == '0'){
	    							return '<label><input type="checkbox" class="addtocart" data-id="'.base64_encode($row['id']).'"  id="'.base64_encode($row['id']).'-'.base64_encode($identifier).'"><label>';
		    						
		    					}
		    					else if($cinkey == 'id' && $cin['selled'] == '1'){
	    							unset($row);
		    					}
		    					else {
		    						return createTableStyles($theTableTypes[$o-1], esc($row[$cinkey]));	
		    					}				    					
		    				};
		    				$o++;
		    			}
					}
					array_push($zarray, function($row)use($identifier, $cin, $status){
						if($cin['selledtimes'] > 0 && $status == '1'){
							return '<div class="btn-groupe">
							<button type="button" class="btn btn-success btn-sm">Sold Times '.$cin['selledtimes'].'</button>
							</div>';
						}
						else if($cin['selled'] == '0'){
							return '<div class="btn-groupe">
							<button type="button" data-api="editinitm-'.base64_encode($row['id']).'|'.base64_encode($identifier).'" class="btn btn-warning btn-sm"><span class="bx bx-edit"></span></button>
							<button type="button" data-api="initDelete-'.base64_encode($row['id']).'|'.base64_encode($identifier).'" class="btn btn-danger btn-sm"><span class="bx bx-trash"></span></button>
							</div>';
						}
						else if($cin['selled'] == '1'){
							return '<div class="btn-groupe">
							<button type="button" class="btn btn-success btn-sm">Sold</button>
							</div>';
						}
						else if($cin['refunded'] == '1'){
							return '<div class="btn-groupe">
							<button type="button" class="btn btn-danger btn-sm">Refunded</button>
							</div>';
						}
						
					});
				}
			}
			return $zarray;
		}
        $setting = [
            "setTable" => $builder,
            "setOutput" => madrow($mybuilder,$theTableValues, $theTableTypes, $identifier, $status)
        ];
        return $setting;
    }
}