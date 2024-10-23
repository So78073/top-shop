<?php

namespace App\Models;

use CodeIgniter\Model;

class SellerrequestsModel extends Model
{
    protected $table      = 'sellersrequests';
    protected $primaryKey = 'id';
    protected $returnType     = 'array';
    protected $allowedFields = ['username', 'userid', 'info', 'date', 'status'];
}