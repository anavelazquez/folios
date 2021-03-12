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
use App\Oficio;
use App\AreaAcademica;

class OficiosController extends Controller
{

    public function index(){
        return view('oficios.oficios');
    }

    public function oficioslista(){
        $user = \Auth::user();
        $area = AreaAcademica::where('id_area', $user->area_id)->first();

        if($user->permissions == 0){
            return Datatables::of(\App\Oficio::orderBy('id', 'DESC')->where('clave','like',$area->cla.'%')->where('autor', $user->Usuario)->get())->make(true);
        }if  ($user->permissions == -1){
            return Datatables::of(\App\Oficio::orderBy('id', 'DESC')->where('clave','like',$area->cla.'%')->get())->make(true);
        }
        else{
            return Datatables::of(\App\Oficio::orderBy('id', 'DESC')->get())->make(true);
        }
    }

    public function saveOficio(Request $request){
        $validatedData = $this->validate($request, [
            'dirigido' => 'required',
            'seguimiento' => 'required',
            'asunto' => 'required',
            'observaciones' => 'required',
            'cancelado' => 'required',
        ]);

        $user = \Auth::user();
        $area = AreaAcademica::where('id_area', $user->area_id)->first();

        $anio_oficio =  date("Y");

        
        $maximo_oficio = Oficio::orderBy('id', 'desc')->where('clave', 'like', '%'.$anio_oficio)->where('clave', 'like', $area->cla.'%')->first();

        if($maximo_oficio){
            $num = explode("/", $maximo_oficio['clave']);
            $numero = $num[2]+1;
        }else{
            $numero = 1;
        }

        $num = str_pad($numero, 5, "0", STR_PAD_LEFT);

        $clave= $area->cla.'/CECyTEV/'.$num.'/'.$anio_oficio;


        $oficio = new Oficio();
        $oficio->fecha = date("Y-m-d H:i:s");
        $oficio->dirigido = mb_strtoupper($request->input('dirigido'));;
        $oficio->seguimiento = mb_strtoupper($request->input('seguimiento'));
        $oficio->autor = $user->Usuario;
        $oficio->direcciones = 0;
        $oficio->clave = $clave;
        $oficio->asunto = mb_strtoupper($request->input('asunto'));
        $oficio->obs = mb_strtoupper($request->input('observaciones'));
        $oficio->cancel = $request->input('cancelado');

        DB::beginTransaction();

        try {
            //Se guarda el oficio
            $oficio->save();

            $message = array(
                'type' => 'success',
                'text' => 'El oficio se ha agregado correctamente'
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = array(
                'type' => 'error',
                'text' => 'El oficio no se ha guardado',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }


    public function updateOficio($id_oficio_editar, Request $request){
        $validatedData = $this->validate($request, [
            'dirigido' => 'required',
            'seguimiento' => 'required',
            'asunto' => 'required',
            'observaciones' => 'required',
            'cancelado' => 'required',
        ]);

        $oficio = Oficio::findOrFail($id_oficio_editar);
        $oficio->dirigido = mb_strtoupper($request->input('dirigido'));;
        $oficio->seguimiento = mb_strtoupper($request->input('seguimiento'));
        $oficio->asunto = mb_strtoupper($request->input('asunto'));
        $oficio->obs = mb_strtoupper($request->input('observaciones'));
        $oficio->cancel = $request->input('cancelado');

        try {
            $oficio->update();

            $message = array(
                'type' => 'success',
                'text' => 'El oficio se ha actualizado correctamente'
            );
        } catch (\Exception $e) {
            $message = array(
                'type' => 'error',
                'text' => 'No se pudo actualizar el oficio',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }

    public function deleteOficio($id_oficio){
        $user= \Auth::user();
        $oficio= Oficio::findOrFail($id_oficio);

        if($oficio){
            try {
                $oficio->delete();

                $message = array(
                    'type' => 'success',
                    'text' => 'El oficio se ha eliminado correctamente'
                );
            } catch (\Exception $e) {
                $message = array(
                    'type' => 'error',
                    'text' => 'No se pudo eliminar el oficio',
                    'error'=> $e->getMessage()
                );
            }

        }else{
            $message = array(
                'type' => 'warning',
                'text' => 'No se encuentra el oficio que desea eliminar'
            );
        }

        return response()->json($message);
    }

    
}
