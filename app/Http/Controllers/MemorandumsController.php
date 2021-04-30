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
use App\Memorandum;
use App\AreaAcademica;
use App\Trabajador;
use App\Cancelado;

class MemorandumsController extends Controller
{

    public function index(){
        $user = \Auth::user();
        $usuario_trabajador = User::with('trabajador')->find($user->ID);
        $jefes = Trabajador::where('EsJefe', 'SI')->get();
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        
        $array =  array(
            'nombre_trabajador' => $usuario_trabajador->trabajador->nombre_trabajador,
            'jefes' => $jefes,
            'fecha_actual' =>$fecha_actual
        );

        return view( 'memorandums.memorandums', $array);
    }

    public function verificarMemorandums($id_memorandum_cancelar){
        $memorandum = Memorandum::findOrFail($id_memorandum_cancelar);

        if($memorandum->estado == 'cancelado'){
            $message = array(
                'cancelado' => 1,
                'type' => 'warning',
                'text' => 'El Memorándum ya ha sido cancelado'
            );
        }else{
            $message = array(
                'cancelado' => 0
            );
        }
        return response()->json($message);
    }

    public function memorandumslista(){
        $user = \Auth::user();

        if($user->permissions == 0){
            return Datatables::of(\App\Memorandum::with('destinatario')->orderBy('id', 'DESC')->where('clave','like',$user->trabajador->departamento->area->cla.'%')->where('trabajador_id', $user->trabajador->id_trabajador)->get())->addColumn('permissions', $user->permissions)->make(true);
        } if($user->permissions == -1){
            return Datatables::of(\App\Memorandum::with('destinatario')->orderBy('id', 'DESC')->where('clave','like',$user->trabajador->departamento->area->cla.'%')->get())->addColumn('permissions', $user->permissions)->make(true);
        }
    if($user->permissions == -2){
        return Datatables::of(\App\Memorandum::with('destinatario')->orderBy('id', 'DESC')->get())->addColumn('permissions', $user->permissions)->make(true);
    }
    }

    public function saveMemorandum(Request $request){
        $validatedData = $this->validate($request, [
            'dirigido' => 'required',
            'seguimiento' => 'required',
            'asunto' => 'required',
            'observaciones' => 'required',
            'estado' => 'required',
        ]);

        $user = \Auth::user();

        $anio_memorandum =  date("Y");


        $maximo_memorandum = Memorandum::orderBy('id', 'desc')->where('clave', 'like', '%'.$anio_memorandum)->where('clave', 'like', $user->trabajador->departamento->area->cla.'%')->first();

        if($maximo_memorandum){
            $num = explode("/", $maximo_memorandum['clave']);
            $numero = $num[2]+1;
        }else{
            $numero = 1;
        }

        $num = str_pad($numero, 5, "0", STR_PAD_LEFT);
        $clave= $user->trabajador->departamento->area->cla.'/CECyTEV/'.$num.'/'.$anio_memorandum;

        $memorandum = new Memorandum();
        $memorandum->fecha = date("Y-m-d H:i:s");
        $memorandum->dirigido = mb_strtoupper($request->input('dirigido'));;
        $memorandum->seguimiento = mb_strtoupper($request->input('seguimiento'));
        $memorandum->autor = $user->trabajador->nombre_trabajador;
        $memorandum->clave = $clave;
        $memorandum->asunto = mb_strtoupper($request->input('asunto'));
        $memorandum->obs = mb_strtoupper($request->input('observaciones'));
        $memorandum->estado = $request->input('estado');
        $memorandum->Trabajador_id = $user->trabajador->id_trabajador;

        DB::beginTransaction();

        try {
            //Se guarda el Memorandum
            $memorandum->save();

            $message = array(
                'type' => 'success',
                'text' => 'El memorándum se ha agregado correctamente'
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = array(
                'type' => 'error',
                'text' => 'El memorándum no se ha guardado',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }


    public function updateMemorandum($id_memorandum_editar, Request $request){
        $validatedData = $this->validate($request, [
            'dirigido' => 'required',
            'seguimiento' => 'required',
            'asunto' => 'required',
            'observaciones' => 'required',
            'estado' => 'required',
        ]);

        $memorandum = Memorandum::findOrFail($id_memorandum_editar);
        $memorandum->dirigido = mb_strtoupper($request->input('dirigido'));;
        $memorandum->seguimiento = mb_strtoupper($request->input('seguimiento'));
        $memorandum->asunto = mb_strtoupper($request->input('asunto'));
        $memorandum->obs = mb_strtoupper($request->input('observaciones'));
        $memorandum->estado = $request->input('estado');

        try {
            $memorandum->update();

            $message = array(
                'type' => 'success',
                'text' => 'El memorándum se ha actualizado correctamente'
            );
        } catch (\Exception $e) {
            $message = array(
                'type' => 'error',
                'text' => 'No se pudo actualizar el memorándum',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }

    public function deleteMemorandum($id_memorandum){
        $user= \Auth::user();
        $memorandum= Memorandum::findOrFail($id_memorandum);

        if($memorandum){
            try {
                $memorandum->delete();

                $message = array(
                    'type' => 'success',
                    'text' => 'El memorándum se ha eliminado correctamente'
                );
            } catch (\Exception $e) {
                $message = array(
                    'type' => 'error',
                    'text' => 'No se pudo eliminar el memorándum',
                    'error'=> $e->getMessage()
                );
            }

        }else{
            $message = array(
                'type' => 'warning',
                'text' => 'No se encuentra el memorándum que desea eliminar'
            );
        }

        return response()->json($message);
    }

    public function cancelarMemorandum($id_memorandum_cancelar, $firma){
        $user = \Auth::user();

        $memorandum = Memorandum::findOrFail($id_memorandum_cancelar);

        if($memorandum->estado == 'cancelado'){
            $message = array(
                'type' => 'warning',
                'text' => 'El memorándum ya ha sido cancelado'
            );
        }else{
            $memorandum->estado = 'cancelado';
        
            DB::beginTransaction();
            try {
                $cancelado = new Cancelado();
                $cancelado->usuario = $user->ID;
                $cancelado->fecha_cancelado = Carbon::now();
                $cancelado->firma = $firma;
                $cancelado->save();
                
                $id_cancelado = $cancelado->id_cancelado;

                $memorandum->cancelado_id = $id_cancelado;
                $memorandum->update(); 

                DB::commit();
                $message = array(
                    'type' => 'success',
                    'text' => 'El memorándum se ha actualizado correctamente'
                );
            } catch (\Exception $e) {
                DB::rollback();
                $message = array(
                    'type' => 'error',
                    'text' => 'No se pudo actualizar el memorándum',
                    'error'=> $e->getMessage()
                );
            }
        }

        return response()->json($message);
    }


}
