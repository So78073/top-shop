<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table      = 'settings';
    protected $primaryKey = 'id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'sitename', 
        'logo', 
        'openreg', 
        'emailconfirm', 
        'setemailconfirm', 
        'invitecode', 
        'sellersystem', 
        'sellerate', 
        'refsys', 
        'refrate', 
        'icq', 
        'ricq', 
        'telegram', 
        'rtelegram', 
        'jaber', 
        'rjaber', 
        'siteuserslogo', 
        'sitemeta', 
        'sitejava', 
        'ccchecker', 
        'checkerused', 
        'ccchecktimeout', 
        'cccheckercost', 
        'luxorcheckeruser', 
        'luxorchecjerapi', 
        'ccdotcheckapi', 
        'payementgetway', 
        'nowpayementapikey', 
        'nowpaymentaccept', 
        'coinpayementmerchen', 
        'coinpayementipn', 
        'coinpayementapi', 
        'coinpayementsecret', 
        'blockonomicsapi', 
        'mindepo', 
        'voucher',
        'telenotif',
        'telebot',
        'chatid',
        'depoactivate',
        'chatlink',
        'ccopenreport',
        'baseapproved',
    ];
}