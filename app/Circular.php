<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Circular extends Model
{
    protected $table = 'circulares';
    protected $primaryKey = 'id';

    public $timestamps = false;
}
