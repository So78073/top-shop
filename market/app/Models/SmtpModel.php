<?php

namespace App\Models;

use CodeIgniter\Model;

class SmtpModel extends Model
{
    protected $table      = 'smtp';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['host' , 'port','user', 'pass', 'hoster', 'country', 'price', 'reported', 'selled', 'selledto', 'refunded', 'selledon', 'sellerusername', 'sellerid'];

}