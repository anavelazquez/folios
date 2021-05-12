<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class T_archivo extends Model
{
    protected $table = 't_archivo';
    protected $primaryKey = 'id_archivo';
    public $timestamps = false;
}