<?php

namespace App\Models;

use CodeIgniter\Model;

class MytiketModel extends Model
{
    protected $table      = 'mytiket';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['responses', 'responseuserid' , 'responseusername', 'responsedate', 'tiketid', 'status', 'responseusergroupe'];

}