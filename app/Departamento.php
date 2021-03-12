<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamento';
    protected $primaryKey = 'id_depto';

    public function area(){
        return $this->belongsTo('App\AreaAcademica', 'id_area', 'id_area');
    }
}