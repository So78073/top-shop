<?php

namespace App\Models;

use CodeIgniter\Model;

class MyitemsModel extends Model
{
    protected $table      = 'myitems';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = [
        'type', 
        'details', 
        'date', 
        'userid', 
        'reported', 
        'typeid', 
        'prodid', 
        'new', 
        'downloaded', 
        'refunded', 
        'checked', 
        'gb', 
        'refundible',
        'price',
        'sellerid',
    ];

}