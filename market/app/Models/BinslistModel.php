<?php

namespace App\Models; 

use CodeIgniter\Model;

class BinslistModel extends Model
{
    protected $table = 'bins';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ["iin_start", "iin_end", "number_length", "number_luhn", "scheme", "brand", "type", "prepaid", "country", "bank_name", "bank_logo", "bank_url", "bank_phone", "bank_city"];
}

