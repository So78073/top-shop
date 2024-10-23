<?php

namespace App\Models;

use CodeIgniter\Model;

class ShellModel extends Model
{
    protected $table      = 'shell';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['host' , 'pass','infos', 'hoster', 'price', 'reported', 'selled', 'selledto', 'refunded', 'selledon', 'sellerusername', 'sellerid'];

}