<?php namespace App\Models;

use CodeIgniter\Model;

class CardsExtendModel extends Model {
    protected $db;
    public function __construct(){
        $this->db = \Config\Database::connect();
    }
    public function initTable(){
		/**if(session()->get('suser_groupe') == '1'){
			$selid = session()->get('suser_id');
			$builder = $this->db->table("cards")->where("`selled`='0' ORDER BY FIELD(`id`, '".$selid."', 'desc' )");
		}
		else {**/
			$builder = $this->db->table("cards")->where("`selled`='0' AND `refunded`='0' AND `baseapproved`='1'" )->orderBy('id', 'desc');
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
        			if($row['sellerid'] == session()->get('suser_id')){
        				if(array_key_exists($row['id'], $myCombinedArray) || $row['sellerid'] == session()->get('suser_id')){
        					//$check = '<input type="checkbox" data-id="'.$row["id"].'-1" class="addtocart" id="'.$row["id"].'-1">';
        					$check = '';
        				}
        				else {
        					$check = '<input type="checkbox" data-id="'.$row["id"].'-1" class="addtocart" id="'.$row["id"].'-1">';
        				}
					}
					else {
						if(array_key_exists($row['id'], $myCombinedArray)){
							$check = '';
						}
						else {
							$check = '<input type="checkbox" data-id="'.$row["id"].'-1" class="addtocart" id="'.$row["id"].'-1">';
						}						
					}
					return $check;
            	},

            	/**function($row){
            		return '#'.$row['id'];
            	},**/
                
            	function($row){
        			$base = esc($row['base']);
					return $base;
            	},
                
            	function($row){
        			$bin = substr($row['number'], 0, 6);
					return $bin;
            	},
            	
            	function($row){
        			$expi = esc($row['expiration']);
					return $expi;
            	},

            	/**function($row){
        			$brand = esc($row['brand']);
					return $brand;
            	},**/

            	function($row){
        			$level = ucfirst(esc($row['type']));
					return $level;
            	},

            	function($row){
        			
					$countrybankName = 'Bank: <b>'.ucfirst($row['bank']).'</b>';
					$countrybankName .= ' - Country: <b>'.ucfirst($row['country']).'</b>';
					$countrybankNamebtn = '<small>'.$countrybankName.'</small>';
					return $countrybankNamebtn;
            	},

            	function($row){
        			if($row['address'] != "" && $row['address'] != "N/A"){
						$vinfos = 'Address: <b>Yes</b> <span class="text-danger"> - </span>';
					}
					else {
						$vinfos =  'Address: <b>No</b> <span class="text-danger"> - </span>';
					}
					//Phone
					if($row['phone'] != "" && $row['phone'] != "N/A"){
						$vinfos .= 'Phone: <b>Yes</b> <span class="text-danger"> - </span>';
					}
					else {
						$vinfos .=  'Phone: <b>No</b> <span class="text-danger"> - </span>';
					}
					//dob
					if($row['dob'] != "" && $row['dob'] != "N/A"){
						$vinfos .= 'DOB: <b>Yes</b> <span class="text-danger"> - </span>';
					}
					else {
						$vinfos .=  'DOB: <b>No</b> <span class="text-danger"> - </span>';
					}
					//email
					if($row['email'] != "" && $row['email'] != "N/A"){
						$vinfos .= 'Email: <b>Yes</b> <span class="text-danger"> - </span>';
					}
					else {
						$vinfos .=  'Email: <b>No</b> <span class="text-danger"> - </span>';
					}
					//ssn
					if($row['ssn'] != "" && $row['ssn'] != "N/A"){
						$vinfos .= 'SSN: <b>Yes</b> <span class="text-danger"> - </span>';
					}
					else {
						$vinfos .=  'SSN: <b>No</b>';
					}
					$vinfosbtn = '<small>'.$vinfos.'</small>';
					return $vinfosbtn;
            	},
            	function($row){
        			if($row['city'] != ""){
						$city = $row['city'];
					}
					else {
						$city = 'N/A';
					}
					return $city;
            	},

            	function($row){
        			if($row['state'] != ""){
						$state = $row['state'];
					}
					else {
						$state = 'N/A';
					}
					return $state;
            	},

            	function($row){
        			if($row['zip'] != ""){
						$zip = $row['zip'];
					}
					else {
						$zip = 'N/A';
					}
					return $zip;
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
					if($row['price'] != '0'){
						$price = '$'.number_format($row['price'], 2, '.', '');
						return '<div class="text-success text-center"><b>'.$price.'</b></div>';	
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
								<button id="buybtn-'.$row['id'].'" type="button" data-api="removeCartProd-'.$row['id'].'|1" class="btn btn-danger btn-sm">Remove from cart <span class="bx bx-message-x"></span></button>';
						}
						else {
							//$id = '#'.$row['id'];
							$Buy = '
								<button id="buybtn-'.$row['id'].'" type="button" data-api="addtocart-'.$row['id'].'|1" class="btn btn-success btn-sm">Add to cart <span class="bx bx-cart"></span></button>
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