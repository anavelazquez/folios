@extends('layouts.master')
@section('content')
	<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <!--<h1>Agregar Persona</h1>-->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
       <!------------------Inicio Botón para la ventana emergente en HTML ------------------>
<a href="#victorModal" role="button" class="btn btn-large btn-primary" data-toggle="modal"><i class="fas fa-book"></i></a>
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
              <li class="breadcrumb-item active">Oficios</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @include('oficios.listOficios')
      </div>
    </section>

@endsection
