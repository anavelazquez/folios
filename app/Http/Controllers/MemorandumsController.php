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

class MemorandumsController extends Controller
{

    public function index(){
        $jefes = Trabajador::where('EsJefe', 'SI')->get();

        $array =  array(
            'jefes' => $jefes,
        );

        return view( 'memorandums.memorandums', $array);
    }

    public function memorandumslista(){
        $user = \Auth::user();

        if($user->permissions == 0){
            return Datatables::of(\App\Memorandum::orderBy('id', 'DESC')->where('clave','like',$user->trabajador->departamento->area->cla.'%')->where('autor', $user->username)->get())->make(true);
        }else{
            return Datatables::of(\App\Memorandum::orderBy('id', 'DESC')->where('clave','like',$user->trabajador->departamento->area->cla.'%')->get())->make(true);
        }
    }

    public function saveMemorandum(Request $request){
        $validatedData = $this->validate($request, [
            'dirigido' => 'required',
            'seguimiento' => 'required',
            'asunto' => 'required',
            'observaciones' => 'required',
            'cancelado' => 'required',
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
        $memorandum->autor = $user->username;
        $memorandum->clave = $clave;
        $memorandum->asunto = mb_strtoupper($request->input('asunto'));
        $memorandum->obs = mb_strtoupper($request->input('observaciones'));
        $memorandum->cancel = $request->input('cancelado');
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
            'cancelado' => 'required',
        ]);

        $memorandum = Memorandum::findOrFail($id_memorandum_editar);
        $memorandum->dirigido = mb_strtoupper($request->input('dirigido'));;
        $memorandum->seguimiento = mb_strtoupper($request->input('seguimiento'));
        $memorandum->asunto = mb_strtoupper($request->input('asunto'));
        $memorandum->obs = mb_strtoupper($request->input('observaciones'));
        $memorandum->cancel = $request->input('cancelado');

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


}