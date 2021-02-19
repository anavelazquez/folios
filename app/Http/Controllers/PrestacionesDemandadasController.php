<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\PrestacionDemandada;
use Yajra\DataTables\DataTables;

class PrestacionesDemandadasController extends Controller
{

    public function index(){
        $prestaciones_demandadas = PrestacionDemandada::all();
        return view('prestaciones.prestaciones', array(
            'prestaciones_demandadas' => $prestaciones_demandadas
        ));
    }

    public function prestacioneslista(){
        return Datatables::of(\App\PrestacionDemandada::all())->make(true);
    }

    public function getPrestacion($id_prestacion_demandada){
        $prestacion = PrestacionDemandada::where('id_prestacion_demandada', $id_prestacion_demandada)->get();
        return response()->json($prestacion);
    }

    public function savePrestacion(Request $request){
        //print_r($request);
        $validatedData = $this->validate($request, [
            'prestacion_demandada' => 'required'
        ]);

        $user = \Auth::user();

        $prestacion = new PrestacionDemandada();
        $prestacion->prestacion_demandada = mb_strtoupper($request->input('prestacion_demandada'));
        $prestacion->estatus = 1;

        DB::beginTransaction();

        try {
            //Se guarda el juicio
            $prestacion->save();

            $message = array(
                'type' => 'success',
                'text' => 'La prestación demandada se ha agregado correctamente'
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = array(
                'type' => 'error',
                'text' => 'La prestación demandada no se ha guardado',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }


    public function updatePrestacion($id_prestacion_editar, Request $request){
        $validatedData = $this->validate($request, [
            'prestacion_demandada' => 'required'
        ]);

        //Verificar no se repita la prestacion (pendiente)

        $user = \Auth::user();

        $prestacion = PrestacionDemandada::findOrFail($id_prestacion_editar);
        $prestacion->prestacion_demandada = mb_strtoupper($request->input('prestacion_demandada'));
        $prestacion->estatus = $request->input('estatus');

        try {
            $prestacion->update();

            $message = array(
                'type' => 'success',
                'text' => 'La prestación demandada se ha actualizado correctamente'
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = array(
                'type' => 'error',
                'text' => 'No se pudo actualizar la prestación demandada',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }

    public function deletePrestacion($id_prestacion_demandada){
        $user= \Auth::user();
        $prestacion= PrestacionDemandada::findOrFail($id_prestacion_demandada);

        if($prestacion){
            //$juicio->estatus = 0;
            try {
                $prestacion->delete();
                $message = array(
                    'type' => 'success',
                    'text' => 'La prestación demandada se ha eliminado correctamente'
                );
            } catch (\Exception $e) {
                $message = array(
                    'type' => 'error',
                    'text' => 'No se pudo eliminar la prestación demandada',
                    'error'=> $e->getMessage()
                );
            }

        }else{
            $message = array(
                'type' => 'warning',
                'text' => 'No se encuentra la prestación demandada que desea eliminar'
            );
        }

        return response()->json($message);
    }


}
