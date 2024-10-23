<?php namespace App\Models;

use CodeIgniter\Model;

class CpanelExtendedModel extends Model {
    protected $db;
    public function __construct(){
        $this->db = \Config\Database::connect();
    }
    private function mask_string($string, $start, $length) {
	    $string_length = strlen($string);
	    if ($start > $string_length) {
	        return $string;
	    }
	    if ($start + $length > $string_length) {
	        $length = $string_length - $start;
	    }
	    return substr_replace($string, str_repeat("*", $length), $start, $length);
	}
    public function initTable(){
		/**if(session()->get('suser_groupe') == '1'){
			$selid = session()->get('suser_id');
			$builder = $this->db->table("cards")->where("`selled`='0' ORDER BY FIELD(`id`, '".$selid."', 'desc' )");
		}
		else {**/
			$builder = $this->db->table("cpanel")->where("`selled`='0' AND `refunded`='0'")->orderBy('id', 'desc');
		//}
        $setting = [
            "setTable" => $builder,
            "setPageLength" => 10,
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
        			if($row['sellerid'] == session()->get('suser_id')){
        				if(array_key_exists($row['id'], $myCombinedArray) || $row['sellerid'] == session()->get('suser_id')){
        					$check = '';
        				}
        				else {
        					$check = '<input type="checkbox" data-id="'.$row["id"].'-2" class="addtocheck-cpanel '.$row["id"].'" id="'.$row["id"].'-2">';
        				}
					}
					else {
						if(array_key_exists($row['id'], $myCombinedArray)){
							$check = '';
						}
						else {
							$check = '<input type="checkbox" data-id="'.$row["id"].'-2" class="addtocheck-cpanel '.$row["id"].'" id="'.$row["id"].'-2">';
						}						
					}
					return $check;
            	},

            	function($row){
            		$cpanelData = explode('://', $row["host"]);
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

					//return esc($cpanelDataCleaned);
					return $masked_string = esc($this->mask_string($cpanelDataCleaned, 3, strlen($cpanelDataCleaned) - 5));

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
        			$tld = esc($row['tld']).'<input type="hidden" class="'.$row["id"].'">';
					return $tld;
            	},
                
            	function($row){
        			$hoster = esc($row['hoster']);
					return esc(ucfirst($hoster));
            	},
                
            	function($row){
        			$country = $row['country'];
					return '<div class"text-center"><center><img src="'.esc($country).'" style="width:30px" alt="country"></center></div>';
            	},

            	
				function($row){
					if($row['price'] != '0'){
						$price = '$'.number_format($row['price'], 2, '.', '');
						return '<div class="text-success text-center"><b>'.esc($price).'</b></div>';	
					}
					else {
						return '<div class="text-success text-center"><b>FREE</b></div>';	
					}
        			
            	},
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
        			if($row['sellerid'] == session()->get('suser_id') && session()->get('suser_groupe') == '1'){
						$Buy = '<span class="badge bg-indigo p-2">My Product</span>';
						
					}
					else if(session()->get('suser_groupe') == '9'){
						$Buy = '<span class="badge bg-indigo p-2">Seller: '.$row['sellerusername'].'</span>';
					}
					else {
						if(array_key_exists($row['id'], $myCombinedArray)){
							//$id = '#'.$row['id'];
							$Buy = '
								<button id="buybtn-'.$row['id'].'" type="button" data-api="removeCartProd-'.$row['id'].'|2" class="btn btn-danger btn-sm">Remove from cart <span class="bx bx-message-x"></span></button>';
						}
						else {
							$Buy = '
								<button id="buybtn-'.$row['id'].'" type="button" data-api="addtocart-'.$row['id'].'|2" class="btn btn-success btn-sm">Add to cart <span class="bx bx-cart"></span></button>
							';
						}						
					}
                    return $Buy;
                }
            ],
        ];
        return $setting;
    }
}