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
use App\Tarjeta;
use App\AreaAcademica;
use App\Trabajador;

class TarjetasController extends Controller
{

    public function index(){
        $jefes = Trabajador::where('EsJefe', 'SI')->get();

        $array =  array(
            'jefes' => $jefes,
        );

        return view( 'tarjetas.tarjetas', $array);
    }

    public function tarjetaslista(){
        $user = \Auth::user();

        if($user->permissions == 0){
            return Datatables::of(\App\Tarjeta::orderBy('id', 'DESC')->where('clave','like',$user->trabajador->departamento->area->cla.'%')->where('autor', $user->username)->get())->make(true);
        }else{
            return Datatables::of(\App\Tarjeta::orderBy('id', 'DESC')->where('clave','like',$user->trabajador->departamento->area->cla.'%')->get())->make(true);
        }
    }

    public function saveTarjeta(Request $request){
        $validatedData = $this->validate($request, [
            'dirigido' => 'required',
            'seguimiento' => 'required',
            'asunto' => 'required',
            'observaciones' => 'required',
            'cancelado' => 'required',
        ]);

        $user = \Auth::user();

        $anio_tarjeta =  date("Y");


        $maximo_tarjeta = Tarjeta::orderBy('id', 'desc')->where('clave', 'like', '%'.$anio_tarjeta)->where('clave', 'like', $user->trabajador->departamento->area->cla.'%')->first();

        if($maximo_tarjeta){
            $num = explode("/", $maximo_tarjeta['clave']);
            $numero = $num[2]+1;
        }else{
            $numero = 1;
        }

        $num = str_pad($numero, 5, "0", STR_PAD_LEFT);

        $clave= $user->trabajador->departamento->area->cla.'/CECyTEV/'.$num.'/'.$anio_tarjeta;


        $tarjeta = new Tarjeta();
        $tarjeta->fecha = date("Y-m-d H:i:s");
        $tarjeta->dirigido = mb_strtoupper($request->input('dirigido'));;
        $tarjeta->seguimiento = mb_strtoupper($request->input('seguimiento'));
        $tarjeta->autor = $user->username;
        $tarjeta->clave = $clave;
        $tarjeta->asunto = mb_strtoupper($request->input('asunto'));
        $tarjeta->obs = mb_strtoupper($request->input('observaciones'));
        $tarjeta->cancel = $request->input('cancelado');
        $tarjeta->Trabajador_id = $user->trabajador->id_trabajador;

        DB::beginTransaction();

        try {
            //Se guarda la tarjeta
            $tarjeta->save();

            $message = array(
                'type' => 'success',
                'text' => 'La tarjeta se ha agregado correctamente'
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = array(
                'type' => 'error',
                'text' => 'La tarjeta no se ha guardado',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }


    public function updateTarjeta($id_tarjeta_editar, Request $request){
        $validatedData = $this->validate($request, [
            'dirigido' => 'required',
            'seguimiento' => 'required',
            'asunto' => 'required',
            'observaciones' => 'required',
            'cancelado' => 'required',
        ]);

        $tarjeta = Tarjeta::findOrFail($id_tarjeta_editar);
        $tarjeta->dirigido = mb_strtoupper($request->input('dirigido'));;
        $tarjeta->seguimiento = mb_strtoupper($request->input('seguimiento'));
        $tarjeta->asunto = mb_strtoupper($request->input('asunto'));
        $tarjeta->obs = mb_strtoupper($request->input('observaciones'));
        $tarjeta->cancel = $request->input('cancelado');

        try {
            $tarjeta->update();

            $message = array(
                'type' => 'success',
                'text' => 'La tarjeta se ha actualizado correctamente'
            );
        } catch (\Exception $e) {
            $message = array(
                'type' => 'error',
                'text' => 'No se pudo actualizar la tarjeta',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }

    public function deleteTarjeta($id_tarjeta){
        $user= \Auth::user();
        $tarjeta= Tarjeta::findOrFail($id_tarjeta);

        if($tarjeta){
            try {
                $tarjeta->delete();

                $message = array(
                    'type' => 'success',
                    'text' => 'La tarjeta se ha eliminado correctamente'
                );
            } catch (\Exception $e) {
                $message = array(
                    'type' => 'error',
                    'text' => 'No se pudo eliminar la tarjeta',
                    'error'=> $e->getMessage()
                );
            }

        }else{
            $message = array(
                'type' => 'warning',
                'text' => 'No se encuentra la tarjeta que desea eliminar'
            );
        }

        return response()->json($message);
    }


}
