@extends('layouts.master')
@section('content')
	<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <!--------------------Modal para memorandums, como ayuda------------------------------------ inicio de modal-->
            <a href="#victorModal" role="button" class="btn btn-large btn-success" data-toggle="modal"><i class="fas fa-book-open"></i></a>
  <!-- Ventana  -->
  <div id="victorModal" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                  <h4 class="modal-title">Conceptos de los documentos</h4>
              </div>
              <div class="modal-body">
                  <p>Tarjetas y memorandums</p>
                  <p class="text-danger"> Identificación del documento: Archivo/Consecutivo</p>
                  
                  <p>El documento deberá contener las palabras <strong>Archivo/Consecutivo</strong>  que serviran para identificar el lugar de permanencia
                  de los documentos de archivo, es decir, al documento original o acuse original se subrayará la palabra Archivo como 
                  método de identificacion y será el área o departamento que le dé seguimiento al documento el responsable de conservar el mismo.</p>
    
    </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>
         <!-------------------Termina venta de modal primera para oficios------------------------------ --> 
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
       <!------------------Inicio Botón para la ventana emergente en HTML ------------------>
<a href="#victorModal" role="button" class="btn btn-large bg-warning btn-danger" data-toggle="modal"><i class="fas fa-question"></i></a>
  <!-- Ventana  -->
  <div id="victorModal" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                  <h4 class="modal-title">Conceptos de los documentos</h4>
              </div>
              <div class="modal-body">
                  <p>Apartado donde diga como usar el sistema:</p>
                  <p class="text-info">Que va en un oficio, circulares, memorandums y tarjetas.
                  <br>
                  Clasificación de archivos: de concentracion, histórico... etc.</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>
</li>
              <!--------------------termina la ventana emergente---------------------------------->
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Memorándums</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @include('memorandums.listMemorandums')
      </div>
    </section>

@endsection
