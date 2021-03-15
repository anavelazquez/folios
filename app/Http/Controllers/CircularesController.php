<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;
use App\User;
use App\Circular;
use App\AreaAcademica;
use App\Trabajador;

class CircularesController extends Controller
{

    public function index(){
        $jefes = Trabajador::where('EsJefe', 'SI')->get();

        $array =  array(
            'jefes' => $jefes,
        );

        return view( 'circulares.circulares', $array);
    }

    public function circulareslista(){
        $user = \Auth::user();

        if($user->permissions == 0){
            return Datatables::of(\App\Oficio::orderBy('id', 'DESC')->where('clave','like',$user->trabajador->departamento->area->cla.'%')->where('trabajador_id', $user->trabajador->id_trabajador)->get())->make(true);
        }if($user->permissions == -1) {
            return Datatables::of(\App\Oficio::orderBy('id', 'DESC')->where('clave','like',$user->trabajador->departamento->area->cla.'%')->get())->make(true);
        }
        else{
            return Datatables::of(\App\Oficio::orderBy('id', 'DESC')->get())->make(true);
        }
    }

    public function saveCircular(Request $request){
        $validatedData = $this->validate($request, [
            'dirigido' => 'required',
            'seguimiento' => 'required',
            'asunto' => 'required',
            'observaciones' => 'required',
            'estado' => 'required',
        ]);

        $user = \Auth::user();

        $anio_circular =  date("Y");


        $maximo_circular = Circular::orderBy('id', 'desc')->where('clave', 'like', '%'.$anio_circular)->where('clave', 'like', $user->trabajador->departamento->area->cla.'%')->first();

        if($maximo_circular){
            $num = explode("/", $maximo_circular['clave']);
            $numero = $num[2]+1;
        }else{
            $numero = 1;
        }

        $num = str_pad($numero, 5, "0", STR_PAD_LEFT);

        $clave= $user->trabajador->departamento->area->cla.'/CECyTEV/'.$num.'/'.$anio_circular;


        $circular = new Circular();
        $circular->fecha = date("Y-m-d H:i:s");
        $circular->dirigido = mb_strtoupper($request->input('dirigido'));;
        $circular->seguimiento = mb_strtoupper($request->input('seguimiento'));
        $circular->autor = $user->trabajador->nombre_trabajador;
        $circular->clave = $clave;
        $circular->asunto = mb_strtoupper($request->input('asunto'));
        $circular->obs = mb_strtoupper($request->input('observaciones'));
        $circular->estado = $request->input('estado');
        $circular->Trabajador_id = $user->trabajador->id_trabajador;

        DB::beginTransaction();

        try {
            //Se guarda la circular
            $circular->save();

            $message = array(
                'type' => 'success',
                'text' => 'El circular se ha agregado correctamente'
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = array(
                'type' => 'error',
                'text' => 'El circular no se ha guardado',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }


    public function updateCircular($id_circular_editar, Request $request){
        $validatedData = $this->validate($request, [
            'dirigido' => 'required',
            'seguimiento' => 'required',
            'asunto' => 'required',
            'observaciones' => 'required',
            'estado' => 'required',
        ]);

        $circular = Circular::findOrFail($id_circular_editar);
        $circular->dirigido = mb_strtoupper($request->input('dirigido'));;
        $circular->seguimiento = mb_strtoupper($request->input('seguimiento'));
        $circular->asunto = mb_strtoupper($request->input('asunto'));
        $circular->obs = mb_strtoupper($request->input('observaciones'));
        $circular->estado = $request->input('estado');

        try {
            $circular->update();

            $message = array(
                'type' => 'success',
                'text' => 'El circular se ha actualizado correctamente'
            );
        } catch (\Exception $e) {
            $message = array(
                'type' => 'error',
                'text' => 'No se pudo actualizar el circular',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }

    public function deleteCircular($id_circular){
        $user= \Auth::user();
        $circular= Circular::findOrFail($id_circular);

        if($circular){
            try {
                $circular->delete();

                $message = array(
                    'type' => 'success',
                    'text' => 'El circular se ha eliminado correctamente'
                );
            } catch (\Exception $e) {
                $message = array(
                    'type' => 'error',
                    'text' => 'No se pudo eliminar el circular',
                    'error'=> $e->getMessage()
                );
            }

        }else{
            $message = array(
                'type' => 'warning',
                'text' => 'No se encuentra el circular que desea eliminar'
            );
        }

        return response()->json($message);
    }


}
