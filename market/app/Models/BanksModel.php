<?php

namespace App\Models;

use CodeIgniter\Model;

class BanksModel extends Model
{
    protected $table      = 'banks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['bankname', 'proof' , 'balance','email', 'epassword', 'carrier', 'carrierpin', 'price', 'selled', 'selledto', 'refunded', 'selledon', 'addby', 'adduserid', 'address'];

}