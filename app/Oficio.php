<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Oficio extends Model
{
    protected $table = 'oficios';
    protected $primaryKey = 'id';

    public $timestamps = false;
}
