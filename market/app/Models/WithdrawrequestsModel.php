<?php

namespace App\Models; 

use CodeIgniter\Model;

class WithdrawrequestsModel extends Model
{
    protected $table = 'withdrawrequests';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ["sum","username","userid","userwallet","date","status", "originalsum"];
}

