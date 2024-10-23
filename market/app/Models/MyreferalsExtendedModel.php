<?php namespace App\Models;

use CodeIgniter\Model;

class MyreferalsExtendedModel extends Model {
    protected $db;
    public function __construct(){
        $this->db = \Config\Database::connect();
    }
    public function initTable(){
		$builder = $this->db->table("users")->where('refered_by_id', session()->get('suser_id'))->orderBy('add_datae', 'desc');
        $setting = [
            "setTable" => $builder,
            "setOutput" => [
            	function($row){
        			$username = esc(substr($row['username'], 0, 3))."****";
					return $username;
            	},
            	function($row){
            		$ndate = new \DateTime($row['add_datae']);
        			$date = $ndate->format('d/m/Y H:s:i');
					return $date;
            	}
            ]
        ];
        return $setting;
    }
}