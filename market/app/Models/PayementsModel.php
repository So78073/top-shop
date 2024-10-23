<?php

namespace App\Models;

use CodeIgniter\Model;

class PayementsModel extends Model
{
    protected $table      = 'nowpayements';
    protected $primaryKey = 'id';
    protected $returnType     = 'array';
    protected $allowedFields = ['payment_id', 'payment_status', 'pay_address', 'price_amount', 'price_currency', 'pay_amount', 'pay_currency', 'order_id', 'created_at', 'updated_at', 'purchase_id', 'userid', 'username', 'intstatus', 'finishedprocess'];

}