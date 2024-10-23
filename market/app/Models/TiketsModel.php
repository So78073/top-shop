<?php

namespace App\Models;

use CodeIgniter\Model;

class TiketsModel extends Model
{
    protected $table      = 'tikets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['subject', 'description' , 'username','userid', 'status', 'date'];

}