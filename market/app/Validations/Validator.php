<?php

namespace App\Validations;

use App\Models\LoginModel;

class Validator {

	public function loginChec(string $str, string $field, array $data ){
		$model = new LoginModel();
		$user = $model->where('username', $data["username"])->first();
		if(!$user){
			return false;
		}
		else {
			return password_verify($data["password"], $user["password"]);
		}
	}
	
    public function FnameLname(string $str){
		return (!preg_match("/^([a-zA-Z0-9 ])+$/i", $str)) ? FALSE : TRUE;
	}

	public function UserName(string $str){
		return (!preg_match("/^([a-zA-Z0-9_\-])+$/i", $str)) ? FALSE : TRUE;
	}
	
	public function Alphamini(string $str){
		return (!preg_match("/^([a-z])+$/i", $str)) ? FALSE : TRUE;
	}

	public function PassCheck(string $str){
		return (!preg_match("/^([a-zA-Z0-9_\-\*\$\%\.\!\?\:\,\;\/\\ ])+$/i", $str)) ? FALSE : TRUE;
	}

	public function NumCheck($str){
		return (!preg_match("/^([0-9])+$/i", $str)) ? FALSE : TRUE;
	}

	public function DoubleCheck(string $str){
		return (!preg_match("/^([0-9]+)(\.[0-9]{1,2})?/i", $str)) ? FALSE : TRUE;
		
	}

	public function BinaryCheck(string $str){
		return (!preg_match("/^([0-1])+$/i", $str)) ? FALSE : TRUE;
	}
	
	public function Speach(string $str){
		return (!preg_match("/^([a-zA-ZÀ-ÿ0-9_\-\*\$\%\.\!\?\:\,\;\/\<\>\'\"\=\r\n ])+$/i", $str)) ? FALSE : TRUE;
	}
	
	public function textArea(string $str){
	    return (!preg_match("/^([a-zA-ZÀ-ÿ0-9_\-\*\$\%\.\!\?\:\,\;\/\'\"\= ])+$/im", $str)) ? FALSE : TRUE;
	    
	}
	
	public function valide_cc_number(string $str){
	    return (!preg_match("/^([0-9]){16,19}+$/i", $str)) ? FALSE : TRUE;
	    
	}
	
	public function expirate(string $str){
	    return (!preg_match("/^([0-9]{2})\/([0-9]{2})+$/i", $str)) ? FALSE : TRUE;
	    
	}
	
	

	public function captchaCheck(string $str){
		$captcaha = session()->get('captcha');
		if($captcaha != null){
			if($str == $captcaha){
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
		return (!preg_match("/^([a-zA-ZÀ-ÿ0-9_\-\*\$\%\.\!\?\:\,\;\/\<\>\'\"\=\r\n ])+$/i", $str)) ? FALSE : TRUE;
	}
}