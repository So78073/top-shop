<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table      = 'users';
    protected $returnType     = 'array';
    protected $allowedFields = ['username', 'password'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected function beforeInsert(array $data){
    	$data = $this->passwordHash($data);
    	return $data;
    }
    protected function beforeUpdate(array $data){
    	$data = $this->passwordHash($data);
    	return $data;
    }
    protected function passwordHash(array $data){
    	if(isset($data['data']['user_password'])){
    		$data['data']['user_password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
    		return $data;
    	}
    }
}