<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Oficio;
use Yajra\DataTables\DataTables;

class OficiosController extends Controller
{

    public function index(){
        return view('oficios.oficios');
    }

    public function oficioslista(){
        $user = \Auth::user();

        return Datatables::of(\App\Oficio::with('distrito', 'juzgado', 'materia', 'juicio', 'prestacion_demandada', 'tipo_periodo_publicacion', 'periodos_publicacion')->where('estatus', 1)->where('visible', 1)->where('id_juzgado', $id_juzgado->id_juzgado))->make(true);
    }

    public function edictoslistapublicada(){
        //$this->comprobarActualizacionVisibilidad();
        return Datatables::of(\App\Oficio::all())->make(true);
    }

    public function getEdicto($id_publicacion){
        $edicto = Edicto::with('distrito', 'juzgado', 'materia', 'juicio', 'prestacion_demandada', 'tipo_periodo_publicacion', 'periodos_publicacion')->where('id_publicacion', $id_publicacion)->get();
        return response()->json($edicto );
    }

    public function saveEdicto(Request $request){
        //print_r($request);
        $validatedData = $this->validate($request, [
            'expediente' => 'required',
            'no_edicto' => 'required',
            'distrito' => 'required',
            'juzgado' => 'required',
            'materia' => 'required',
            'juicio' => 'required',
            'prestacion_demandada' => 'required',
            'fecha_publicacion' => 'required'
        ]);
        //Verificar no se repita numero de edicto (pendiente)
        $no_edicto = explode("/", $request->input('no_edicto'));
        $numero_edicto = $no_edicto[0];
        $anio_edicto = $no_edicto[1];

        $nombre= 'EDICTO'.$numero_edicto.$anio_edicto.'.pdf';

        //Se obtiene el campo file definido en el formulario
        $file = $request->file('archivo');

        //indicamos que queremos guardar un nuevo archivo en el disco local
        Storage::disk('edictos')->put($nombre,  \File::get($file));

        $user = \Auth::user();

        $no_expediente = explode("/", $request->input('expediente'));
        $numero_expediente = $no_expediente[0];
        $anio_expediente = $no_expediente[1];

        $no_edicto = explode("/", $request->input('no_edicto'));
        $numero_edicto = $no_edicto[0];
        $anio_edicto = $no_edicto[1];

        $edicto = new Edicto();
        $edicto->numero_expediente = $numero_expediente;
        $edicto->anio_expediente = $anio_expediente;
        $edicto->numero_edicto = $numero_edicto;
        $edicto->anio_edicto = $anio_edicto;
        $edicto->id_distrito = $request->input('distrito');
        $edicto->id_juzgado = $request->input('juzgado');
        $edicto->id_materia = $request->input('materia');
        $edicto->id_juicio = $request->input('juicio');
        $edicto->id_prestacion_demandada = $request->input('prestacion_demandada');
        $edicto->ruta_archivo = "edictos/storage/edictos/".$nombre;
        //$edicto->fecha_publicacion = Carbon::now();
        //$edicto->fecha_publicacion = $request->input('periodoPublicacion') == 'I' ? $request->input('fecha_publicacion') : json_decode($_POST['fecha_minima']);
        $edicto->fecha_publicacion = $request->input('fecha_publicacion');
        $edicto->id_tipo_periodo_publicacion = $request->input('periodoPublicacion');
        $edicto->visible = 1;
        $edicto->estatus = 1;
        $edicto->id_usuario_registro = $user->id_usuario;

        DB::beginTransaction();

        try {
            //Se guarda el edicto
            $edicto->save();
            //Se recupera el id_publicacion
            $id_edicto = $edicto->id_publicacion;

            //Si el periodo es definido
            if($edicto->id_tipo_periodo_publicacion == 'D'){
                //Se guardará el o los periodos de la publicacion
                $periodos_publicacion = json_decode($_POST['periodos_publicacion']);

                foreach ($periodos_publicacion as $periodo) {
                    $periodo_publicacion = new PeriodoPublicacion();
                    $periodo_publicacion->id_publicacion = $id_edicto;
                    $periodo_publicacion->fecha_inicio_publicacion = $periodo->fecha_inicio;
                    $periodo_publicacion->fecha_fin_publicacion = $periodo->fecha_fin;
                    $periodo_publicacion->save();
                }
            }
            $this->actualizarVisibilidad();


            $message = array(
                'type' => 'success',
                'text' => 'El edicto se ha agregado correctamente'
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = array(
                'type' => 'error',
                'text' => 'El edicto no se ha guardado',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }


    public function updateEdicto($id_edicto_editar, $modificar_archivo, Request $request){
        $validatedData = $this->validate($request, [
            'expediente' => 'required',
            'no_edicto' => 'required',
            //'distrito' => 'required',
            //'juzgado' => 'required',
            'materia' => 'required',
            'juicio' => 'required',
            'prestacion_demandada' => 'required',
            'fecha_publicacion' => 'required'
        ]);

        //Verificar no se repita numero de edicto (pendiente)
        $no_edicto = explode("/", $request->input('no_edicto'));
        $numero_edicto = $no_edicto[0];
        $anio_edicto = $no_edicto[1];

        $nombre= 'EDICTO'.$numero_edicto.$anio_edicto.'.pdf';

        $user = \Auth::user();
        $UsuarioJuzgado = UsuarioJuzgado::where('id_usuario', $user->id_usuario)->where('estatus', 1)->first();
        $id_juzgado = $UsuarioJuzgado->id_juzgado;
        $juzgado_usuario = Juzgado::findOrFail($id_juzgado);
        $id_distrito = $juzgado_usuario->id_distrito;


        $no_expediente = explode("/", $request->input('expediente'));
        $numero_expediente = $no_expediente[0];
        $anio_expediente = $no_expediente[1];

        $no_edicto = explode("/", $request->input('no_edicto'));
        $numero_edicto = $no_edicto[0];
        $anio_edicto = $no_edicto[1];

        $edicto = Edicto::findOrFail($id_edicto_editar);
        //print_r($edicto);
        //Se verifica si se desea modificar el archivo almacenado
        if($modificar_archivo == 1){
            //Se obtiene el campo file definido en el formulario
            $file = $request->file('archivo');
            //indicamos que queremos guardar un nuevo archivo en el disco local
            Storage::disk('edictos')->put($nombre,  \File::get($file));
        }else{
            if($edicto->numero_edicto != $numero_edicto || $edicto->anio_edicto != $anio_edicto){
                $nombre_anterior = explode("/", $edicto->ruta_archivo);
                print_r($nombre_anterior);
                //Storage::move('app/public/edictos/'.$nombre_anterior[2], 'app/public/edictos/'.$nombre);
                Storage::disk('edictos')->move($nombre_anterior[2], $nombre);
            }
        }
        $edicto->numero_expediente = $numero_expediente;
        $edicto->anio_expediente = $anio_expediente;
        $edicto->numero_edicto = $numero_edicto;
        $edicto->anio_edicto = $anio_edicto;
        $edicto->id_distrito = $id_distrito;
        $edicto->id_juzgado = $id_juzgado;
        $edicto->id_materia = $request->input('materia');
        $edicto->id_juicio = $request->input('juicio');
        $edicto->id_prestacion_demandada = $request->input('prestacion_demandada');
        $edicto->ruta_archivo = "storage/edictos/".$nombre;
        $edicto->id_tipo_periodo_publicacion = $request->input('periodoPublicacion');;
        $edicto->visible = 1;
        $edicto->estatus = 1;

        try {
            $edicto->update();
            //Se eliminan los periodos de publicacion
            $edicto->periodos_publicacion()->delete();

            //Si el periodo es definido se agregan nuevamente todos los periodos de publicacion
            if($edicto->id_tipo_periodo_publicacion == 'D'){
                //Se guardará el o los periodos de la publicacion
                $periodos_publicacion = json_decode($_POST['periodos_publicacion']);

                foreach ($periodos_publicacion as $periodo) {
                    $periodo_publicacion = new PeriodoPublicacion();
                    $periodo_publicacion->id_publicacion = $edicto->id_publicacion;
                    $periodo_publicacion->fecha_inicio_publicacion = $periodo->fecha_inicio;
                    $periodo_publicacion->fecha_fin_publicacion = $periodo->fecha_fin;
                    $periodo_publicacion->save();
                }
            }

            $message = array(
                'type' => 'success',
                'text' => 'El edicto se ha actualizado correctamente'
            );
        } catch (\Exception $e) {
            $message = array(
                'type' => 'error',
                'text' => 'No se pudo actualizar el edicto',
                'error'=> $e->getMessage()
            );
        }

        return response()->json($message);
    }

    public function deleteEdicto($edicto_id){
        $user= \Auth::user();
        $edicto= Edicto::findOrFail($edicto_id);

        if($edicto){
            $edicto->estatus = 0;
            $edicto->visible = 0;
            try {
                $edicto->update();
                $message = array(
                    'type' => 'success',
                    'text' => 'El edicto se ha eliminado correctamente'
                );
            } catch (\Exception $e) {
                $message = array(
                    'type' => 'error',
                    'text' => 'No se pudo eliminar el edicto',
                    'error'=> $e->getMessage()
                );
            }

            /*
            //Si es que se va a borrar de la tabla
            DB::beginTransaction();
            $eliminar = $edicto->delete();
            //Si tiene un periodo definido borrar el registro de esta tabla (pendiente)
            //Si se borró con éxito
            if($eliminar){
                DB::commit();
                $message = array(
                    'type' => 'success',
                    'text' => 'El edicto se ha eliminado correctamente'
                );

            //Si ocurrió algun problema
            }else{
                DB::rollback();
                $message = array(
                    'type' => 'error',
                    'text' => 'El edicto no ha podido eliminarse',
                    'error' => true,
                    'data' => array()
                );
            }
            */

        }else{
            $message = array(
                'type' => 'warning',
                'text' => 'No se encuentra el edicto que desea eliminar'
            );
        }

        return response()->json($message);
    }

    function comprobarActualizacionVisibilidad(){
        $hoy = Carbon::now();
        $hoy = $hoy->format('Y-m-d');

        $ultima_actualizacion = ActualizacionVisibilidad::find(\DB::table('actualizacion_visibilidad')->max('id_actualizacion_visibilidad'));

        if($hoy <= $ultima_actualizacion->fecha_actualizacion_visibilidad){
            //Ya se actualizó hoy
            //print_r('Ya se actualizó');
        }else{
            $this->actualizarVisibilidad();
            $actualizacion = new ActualizacionVisibilidad();
            $actualizacion->fecha_actualizacion_visibilidad = $hoy;
        }
    }

    function actualizarVisibilidad(){
        $hoy = Carbon::now();
        $hoy = $hoy->format('Y-m-d');

        $edictos = Edicto::with('periodos_publicacion')->get();
        //print_r('edictos');
        //print_r($edictos);
        if($edictos){
            //Se ponen todos los edictos con visible = 0
            foreach ($edictos as $edicto) {
                $edicto->visible = 0;
                $edicto->save();
            }

            //Se recorren todos los edictos para evaluarlos
            foreach ($edictos as $edicto) {
                //print_r($edicto->id_publicacion);
                //Si el edicto tiene un periodo definido, se verifican cada uno de los periodos que tengan configurados
                if($edicto->id_tipo_periodo_publicacion == 'D'){
                    //print_r('El periodo es definido');
                    $periodos = $edicto->periodos_publicacion()->get();
                    //Se recorre cada uno de los periodos_publicacion
                    foreach ($periodos as $periodo) {
                        $fecha_inicio = new Carbon($periodo->fecha_inicio_publicacion);
                        $fecha_fin = new Carbon($periodo->fecha_fin_publicacion);
                        //Se reinicia la variable cada vez que se recorre el periodo
                        $es_visible = 0;
                        //Se recorre el periodo de publicacion para ver si alguna de las fechas coincide con la actual
                        for($i=$fecha_inicio;$i<=$fecha_fin;$i = $fecha_inicio->addDay()){
                            //print_r( "<br />". $i);
                            //Si ya está en visible ya no se hace nada
                            if($es_visible != 1){
                                //Si si coincide, se pone visible
                                if($i == $hoy){
                                    $edicto->visible = 1;
                                    $edicto->save();
                                    $es_visible = 1;
                                }
                            }
                        }
                    }
                }else{
                    //Por otro lado, si el edicto tiene un periodo indefinido, se verifica que la fecha de publicación sea menor o igual al día actual
                    //print_r('El periodo es indefinido');
                    if($edicto->fecha_publicacion <= $hoy){
                        //Mostrar
                        $edicto->visible = 1;
                        $edicto->save();
                    }
                }
            }
        }
    }




}
