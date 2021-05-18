<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Circular extends Model
{
    protected $table = 'circulares';
    protected $primaryKey = 'id';

    public $timestamps = false;
    public function destinatario(){
        return $this->belongsTo('App\Trabajador', 'dirigido', 'id_trabajador');
    }
    public function tipo_archivo(){
        return $this->hasOne('App\T_archivo', 'id_archivo', 'TipoArchivo');
    }

    public function cancelado(){
        return $this->hasOne('App\Cancelado', 'id_cancelado', 'cancelado_id');
    }
}

