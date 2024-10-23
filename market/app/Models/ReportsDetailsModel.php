<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportsDetailsModel extends Model
{
    protected $table      = 'reportsdetails';
    protected $primaryKey = 'id';
    protected $returnType     = 'array';
    protected $allowedFields = ['reportid', 'message', 'messagedate', 'userid', 'username', 'user_groupe'];
}
