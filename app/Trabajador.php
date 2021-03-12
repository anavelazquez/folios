<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    protected $table = 'trabajador';
    protected $primaryKey = 'id_trabajador';

    public function departamento(){
        return $this->belongsTo('App\Departamento', 'departamento_id', 'id_depto');
    }
}