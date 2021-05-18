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
use App\Cancelado;
use App\T_archivo;

class TarjetasController extends Controller
{

    public function index(){
        $user = \Auth::user();
        $usuario_trabajador = User::with('trabajador')->find($user->ID);
        $jefes = Trabajador::where('EsJefe', 'SI')->get();
         //  $tipo = T_archivo::select('tipo_archivo')->get(); 
      $tipo = DB::table('t_archivo')->get();
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        

        $array =  array(
            'nombre_trabajador' => $usuario_trabajador->trabajador->nombre_trabajador,
            'jefes' => $jefes,
            'tipo' => $tipo,
            'fecha_actual' =>$fecha_actual
        );

        return view( 'tarjetas.tarjetas', $array);
    }

    public function verificarTarjetas($id_tarjeta_cancelar){
        $tarjeta = Tarjeta::findOrFail($id_tarjeta_cancelar);

        if($tarjeta->estado == 'cancelado'){
            $message = array(
                'cancelado' => 1,
                'type' => 'warning',
                'text' => 'La tarjeta ya ha sido cancelada'
            );
        }else{
            $message = array(
                'cancelado' => 0
            );
        }
        return response()->json($message);
    }

    public function tarjetaslista(){
        $user = \Auth::user();

        if($user->permissions == 0){
            return Datatables::of(\App\Tarjeta::with('destinatario', 'tipo_archivo', 'cancelado.usuario_cancela.trabajador')->orderBy('id', 'DESC')->where('clave','like',$user->trabajador->departamento->clave.'%')->where('trabajador_id', $user->trabajador->id_trabajador)->get())->addColumn('permissions', $user->permissions)->make(true);
        }elseif($user->permissions == -1){
            return Datatables::of(\App\Tarjeta::with('destinatario', 'tipo_archivo', 'cancelado.usuario_cancela.trabajador')->orderBy('id', 'DESC')->where('clave','like',$user->trabajador->departamento->clave.'%')->get())->addColumn('permissions', $user->permissions)->make(true);
        }elseif($user->permissions == -2){
            return Datatables::of(\App\Tarjeta::with('destinatario', 'tipo_archivo', 'cancelado.usuario_cancela.trabajador')->orderBy('id', 'DESC')->get())->addColumn('permissions', $user->permissions)->make(true);
        }
    }

    public function saveTarjeta(Request $request){
        $validatedData = $this->validate($request, [
            'dirigido' => 'required',
            'seguimiento' => 'required',
            'TipoArchivo' => 'required',
            'asunto' => 'required',
            'observaciones' => 'required',
            'estado' => 'required',
        ]);

        $user = \Auth::user();

        $anio_tarjeta =  date("Y");


        $maximo_tarjeta = Tarjeta::orderBy('id', 'desc')->where('clave', 'like', '%'.$anio_tarjeta)->where('clave', 'like', $user->trabajador->departamento->clave.'%')->first();

        if($maximo_tarjeta){
            $num = explode("/", $maximo_tarjeta['clave']);
            $numero = $num[2]+1;
        }else{
            $numero = 1;
        }

        $num = str_pad($numero, 4, "0", STR_PAD_LEFT);
        $clave= $user->trabajador->departamento->clave.'/'.$num.'/'.$anio_tarjeta;
/*$clave= $user->trabajador->departamento->clave.'/CECyTEV/'.$num.'/'.$anio_oficio;*/
      /*Las claves se tomarán directamente de la tabla "departamentos" en "clave"  */


        $tarjeta = new Tarjeta();
        $tarjeta->fecha = date("Y-m-d H:i:s");
        $tarjeta->dirigido = mb_strtoupper($request->input('dirigido'));;
        $tarjeta->seguimiento = mb_strtoupper($request->input('seguimiento'));
        $tarjeta->TipoArchivo = mb_strtoupper($request->input('TipoArchivo'));
        $tarjeta->autor = $user->trabajador->nombre_trabajador;
        $tarjeta->clave = $clave;
        $tarjeta->asunto = mb_strtoupper($request->input('asunto'));
        $tarjeta->obs = mb_strtoupper($request->input('observaciones'));
        $tarjeta->estado = $request->input('estado');
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
            'TipoArchivo' => 'required',
            'asunto' => 'required',
            'observaciones' => 'required',
            'estado' => 'required',
        ]);

        $tarjeta = Tarjeta::findOrFail($id_tarjeta_editar);
        $tarjeta->dirigido = mb_strtoupper($request->input('dirigido'));;
        $tarjeta->seguimiento = mb_strtoupper($request->input('seguimiento'));
        $tarjeta->TipoArchivo = mb_strtoupper($request->input('TipoArchivo'));
        $tarjeta->asunto = mb_strtoupper($request->input('asunto'));
        $tarjeta->obs = mb_strtoupper($request->input('observaciones'));
        $tarjeta->estado = $request->input('estado');

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
// cancelar  tarjeta
    public function cancelarTarjeta($id_tarjeta_cancelar, $firma, $motivo){
        $user = \Auth::user();

        $tarjeta = Tarjeta::findOrFail($id_tarjeta_cancelar);

        if($tarjeta->estado == 'cancelado'){
            $message = array(
                'type' => 'warning',
                'text' => 'La tarjeta ya ha sido cancelada'
            );
        }else{
            $tarjeta->estado = 'cancelado';
        
            DB::beginTransaction();
            try {
                $cancelado = new Cancelado();
                $cancelado->usuario = $user->ID;
                $cancelado->fecha_cancelado = Carbon::now();
                $cancelado->firma = $firma;
                $cancelado->motivo = mb_strtoupper($motivo);
                $cancelado->save();
                
                $id_cancelado = $cancelado->id_cancelado;

                $tarjeta->cancelado_id = $id_cancelado;
                $tarjeta->update(); 

                DB::commit();
                $message = array(
                    'type' => 'success',
                    'text' => 'El tarjeta se ha actualizado correctamente'
                );
            } catch (\Exception $e) {
                DB::rollback();
                $message = array(
                    'type' => 'error',
                    'text' => 'No se pudo actualizar la tarjeta',
                    'error'=> $e->getMessage()
                );
            }
        }

        return response()->json($message);
    }


}
