<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\App;
use App\Models\User;
class Perfil extends Model
{
    protected $connection = 'sqlsrv';
    protected $table='PERFIL';
    protected $dateFormat = 'd/m/y h:i:s';

    protected $fillable = [
        'IDAPP',
        'IDUSUARIO'
    ];

    public function app(){
        return $this->belongsTo(App::class,'IDAPP','ID');
    }

    public function user(){
        return $this->belongsTo(User::class,'IDUSUARIO','IDUSUARIO');
    }



}
