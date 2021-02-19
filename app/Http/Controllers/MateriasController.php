<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Materia;
use App\Juicio;
use Yajra\DataTables\DataTables;

class MateriasController extends Controller
{

    public function select($id_materia = null){
        if($id_materia){
            $materia = Materia::find($id_materia);
            $juicios = $materia->juicios;
            $data = array(
                'materia' => $materia
            );
            return $data;
        }else{
            $materias = Materia::all();
            return $materias;
        }
    }

}
