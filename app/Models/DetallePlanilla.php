<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePlanilla extends Model
{
    protected $connection = 'sqlsrv6';
    protected $table='DETALLE_PLANILLA';
    public $timestamps = false;


    public function items(){

        return $this->belongsTo('App\Models\Item','IDITEM','ID');
    }


}
