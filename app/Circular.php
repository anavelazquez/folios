<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Circular extends Model
{
    protected $table = 'circulares';
    protected $primaryKey = 'id';

    public $timestamps = false;
    public function destinatario(){
        return $this->belongsTo('App\Trabajador', 'dirigido', 'id_trabajador', 'cancelado_id', 'id_archivo', 'TipoArchivo');
    }
}

