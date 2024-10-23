<?php

namespace App\Models;

use CodeIgniter\Model;

class CardsModel extends Model
{
    protected $table      = 'cards';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['base' , 'refun','number', 'expiration', 'cvv', 'fullname', 'phone', 'dob', 'address', 'zip', 'city', 'state', 'country', 'price', 'bank', 'scheme', 'type', 'brand', 'reported', 'selled', 'selledto', 'refunded', 'selledon', 'countryalpha2', 'sellerusername', 'sellerid', 'other', 'ip', 'email', 'ssn', 'baseapproved'];

}