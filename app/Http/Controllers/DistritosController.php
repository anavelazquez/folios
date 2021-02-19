<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Edicto;
use App\Distrito;
use App\Materia;
use App\Juicio;
use App\PrestacionDemandada;
use Yajra\DataTables\DataTables;

class DistritosController extends Controller
{

    public function select($id_distrito = null){
        if($id_distrito){
            $distrito = Distrito::find($id_distrito);
            $juzgados = $distrito->juzgados;
            $data = array(
                'distrito' => $distrito
            );
            return $data;
        }else{
            $distritos = Distrito::all();
            return $distritos;
        }
    }

}
