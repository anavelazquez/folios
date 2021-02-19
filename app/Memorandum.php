<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Memorandum extends Model
{
    protected $table = 'memorandums';
    protected $primaryKey = 'id';

    public $timestamps = false;
}
