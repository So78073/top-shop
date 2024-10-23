<?php namespace App\Models;

use CodeIgniter\Model;

class CodesExtendedModel extends Model {
    protected $db;
    public function __construct(){
        $this->db = \Config\Database::connect();
    }
	public function initTable(){
		$builder = $this->db->table("codes")->orderBy('id', 'desc');
	    $setting = [
	        "setTable" => $builder,
	        //"setOrder" => [null,"id",null,"number","scheme","type","expiration",null,null,"city","state","zip","refun","price", null],
	        "setOutput" => [
	        	function($row){
	        		return '#'.$row['id'];
	        	},

	        	function($row){
	        		return $row['code'];
	        	},

	        	function($row){
	        		return '$'.$row['value'];
	        	},

	        	function($row){
	        		switch($row['status']){
	        			case '0' :
	        				$status = '<span class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">No Used</span>';
	        			break;
	        			case '1' :
	        				$status = '<span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">Used</span>';
	        			break;

	        		}
	        		return $status;
	        	},

	        	function($row){
	        		$ndate = new \DateTime($row['generateddate']);
	        		return $ndate->format('d/m/Y H:i:s');
	        	},

	        	function($row){
	        		if($row['usedbyid'] != ''){
	        			return $row['usedbyid'];
	        		}
	        		else {
	        			return '-';
	        		}
	        	},

	        	function($row){
	        		if($row['useddate'] != ''){
	        			$bdate = new \DateTime($row['useddate']);
	        			return $bdate->format('d/m/Y H:i:s');
	        		}
	        		else {
	        			return '-';
	        		}
	        	},

	            function($row){
					$Buy = '<div class="btn-groupe">
						<button type="button" data-api="rminit-'.$row['id'].'" class="btn btn-light btn-sm"><span class="bx bx-trash"></span></button>
						</div';
	                return $Buy;
	            }
	        ]
	    ];
	    return $setting;
	}
}