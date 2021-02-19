<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Materia;
use App\Juicio;
use Yajra\DataTables\DataTables;

class JuiciosController extends Controller
{

    public function index(){
        $materias = Materia::all();
        $juicios = Juicio::with('materia');
        return view('juicios.juicios', array(
            'materias' => $materias,
            'juicios' => $juicios
        ));
    }

    public function juicioslista(){
        $lista = Datatables::of(\App\Juicio::with('materia'))->make(true);
        //print_r($lista);
        return $lista;
    }

    public function getJuicio($id_juicio){
        $juicio = Juicio::with('materia')->where('id_juicio', $id_juicio)->get();
        return response()->json($juicio );
    }

    public function saveJuicio(Request $request){
        //print_r($request);
        $validatedData = $this->validate($request, [
            'materia' => 'required',
            'juicio' => 'required',
        ]);

        $user = \Auth::user();

        $juicio = new Juicio();
        $juicio->juicio = mb_strtoupper($request->input('juicio'));
        $juicio->id_materia = $request->input('materia');
        $juicio->estatus = 1;

        DB::beginTransaction();

        try {
            //Se guarda el juicio
            $juicio->save();

            $message = array(
                'type' => 'success',
                'text' => 'El juicio se ha agregado correctamente'
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = array(
                'type' => 'error',
                'text' => 'El juicio no se ha guardado',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }


    public function updateJuicio($id_juicio_editar, Request $request){
        $validatedData = $this->validate($request, [
            'materia' => 'required',
            'juicio' => 'required'
        ]);

        //Verificar no se repita el juicio (pendiente)

        $user = \Auth::user();

        $juicio = Juicio::findOrFail($id_juicio_editar);
        $juicio->id_materia = $request->input('materia');
        $juicio->juicio = mb_strtoupper($request->input('juicio'));
        $juicio->estatus = $request->input('estatus');

        try {
            $juicio->update();

            $message = array(
                'type' => 'success',
                'text' => 'El juicio se ha actualizado correctamente'
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = array(
                'type' => 'error',
                'text' => 'No se pudo actualizar el juicio',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }

    public function deleteJuicio($id_juicio){
        $user= \Auth::user();
        $juicio= Juicio::findOrFail($id_juicio);

        if($juicio){
            //$juicio->estatus = 0;
            try {
                $juicio->delete();
                $message = array(
                    'type' => 'success',
                    'text' => 'El juicio se ha eliminado correctamente'
                );
            } catch (\Exception $e) {
                $message = array(
                    'type' => 'error',
                    'text' => 'No se pudo eliminar el juicio',
                    'error'=> $e->getMessage()
                );
            }

        }else{
            $message = array(
                'type' => 'warning',
                'text' => 'No se encuentra el juicio que desea eliminar'
            );
        }

        return response()->json($message);
    }

}
