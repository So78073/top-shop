<?php

namespace App\Models;

use CodeIgniter\Model;

class CpanelModel extends Model
{
    protected $table      = 'cpanel';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['host' , 'port','username', 'password', 'tld', 'country', 'hoster', 'price', 'reported', 'selled', 'selledto', 'refunded', 'selledon', 'sellerusername', 'sellerid'];

}