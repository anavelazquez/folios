<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Cancelado extends Model
{
    protected $table = 'cancelado';
    protected $primaryKey = 'id_cancelado';
    public $timestamps = false;

    public function usuario_cancela(){
        return $this->hasOne('App\User', 'ID', 'usuario');
    }
}