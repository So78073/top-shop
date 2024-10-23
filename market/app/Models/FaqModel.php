<?php

namespace App\Models; 

use CodeIgniter\Model;

class FaqModel extends Model
{
    protected $table = 'faq';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ["question", "answear"];
}

