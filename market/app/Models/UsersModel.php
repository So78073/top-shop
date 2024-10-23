<?php

namespace App\Models; 

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ["email","username","password","balance","seller_balance","seller_nbobjects","groupe","referals_count","referals_rate","seller_fees","add_datae","status","referal_code","refered_by_id","refered_by_username", "notifications_nb","messages_nb","last_login_date","last_login_ip", "requeststatus", "btcaddress", "withdrawstatus", "invitecode","telegram","icq","jaber","resetpasscode","activationcode","withdrawinhold", "active"];
}

