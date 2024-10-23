<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionsModel extends Model
{
    protected $table      = 'sections';
    protected $primaryKey = 'id';
    protected $returnType     = 'array';
    protected $allowedFields = ['sectioname', 'sectionstatus', 'sellersactivate', 'itemsnumbers', 'sectionicon', 'maintenancemode', 'sectionrevenue', 'identifier', 'sectionlable', 'sectiontype', 'sectionmessage', 'resell'];
}