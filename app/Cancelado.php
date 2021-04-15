<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Cancelado extends Model
{
    protected $table = 'cancelado';
    protected $primaryKey = 'id_cancelado';
    public $timestamps = false;
}