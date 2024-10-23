<?php

namespace App\Models;

use CodeIgniter\Model;

class RdpModel extends Model
{
    protected $table      = 'rdp';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['host' , 'user', 'pass', 'system', 'ram', 'hddsize', 'hoster', 'country', 'price', 'reported', 'selled', 'selledto', 'refunded', 'selledon', 'sellerusername', 'sellerid'];

}