<?php

namespace App\Models;

use CodeIgniter\Model;

class CodesModel extends Model
{
    protected $table      = 'codes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['code', 'value' , 'status','generateddate', 'useddate', 'usedbyid', 'usedbyname'];

}