<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    protected $table = 'tarjetas';
    protected $primaryKey = 'id';

    public $timestamps = false;
    public function destinatario(){
        return $this->belongsTo('App\Trabajador', 'dirigido', 'id_trabajador', 'cancelado_id');
    }
}
