<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportsModel extends Model
{
    protected $table      = 'reports';
    protected $primaryKey = 'id';
    protected $returnType     = 'array';
    protected $allowedFields = ['itemid', 'myitemid', 'itemprice', 'sellerid', 'sellerusername', 'buyerusername', 'buyerid', 'fastdetails', 'buydate', 'reportdate', 'status', 'request', 'message', 'rtype', 'chatopen', 'replaced'];

}
