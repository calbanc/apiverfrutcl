<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;

class Notebook extends Model
{
    protected $connection = 'sqlsrv';
    protected $table='NOTEBOOK';
    protected $dateFormat = 'd/m/y h:i:s';

    protected $fillable=[
        'COD_EMP',
        'MARCA',
        'MODELO',
        'RAM',
        'SSDD',
        'PANTALLA',
        'SW_ARRENDADO',
        'SW_OPERATIVO',
        'SERIE',
     
    ];
  
    
   
    public function empresa(){
        return $this->belongsTo(Empresa::class,'COD_EMP','COD_EMP');
    }


    
    use HasFactory;


}
