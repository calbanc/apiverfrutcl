<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubItemPlanilla extends Model
{
    protected $connection = 'sqlsrv6';
    protected $table='SUBITEMS';
    public $timestamps = false;
}
