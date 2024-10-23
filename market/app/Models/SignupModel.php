<?php

namespace App\Models; 

use CodeIgniter\Model;

class SignupModel extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $returnType = 'array()';
    protected $allowedFields = ["email","username","password","balance","seller_balance","groupe","referals_count","referals_rate","seller_fees","add_datae","status","referal_code","refered_by_id","refered_by_username", "notifications_nb","messages_nb", "last_login_date", "last_login_ip"];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected function beforeInsert(array $data){
        $data = $this->encryptme($data);
        return $data;
    }
    protected function beforeUpdate(array $data){
        $data = $this->encryptme($data);
        return $data;
    }
    protected function encryptme(array $data){
        if(isset($data['data']['password'])){
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            return $data;
        }
    }
}

