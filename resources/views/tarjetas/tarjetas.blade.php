@extends('layouts.master')
@section('content')
<link rel="stylesheet" href="css/estilos.css">
	<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <!--------------------Modal para Tarjetas, como ayuda------------------------------------ inicio de modal-->
            <a href="#tar" role="button"  title="¿En que momento se genera una tarjeta?" class="btn btn-large btn-secondary" data-toggle="modal"><i class="fas fa-book-open"></i></a>
  <!-- Ventana  -->
  <div id="tar" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                  <h4 class="modal-title"><strong>¿En qué momento se genera una tarjeta informativa?</strong> </h4>
              </div>
              <div class="modat">
                <ul>
                <li>Medio de comunicación dentro del Colegio, que se utiliza para realizar peticiones de manera formal, 
                breve e inmediata sobre informaciónn, material, apoyo técnico, etc.</li>
                <br>
                <strong>Ejemplo: </strong> 
                <br> El titular de la Dirección de planeación realiza una solicitud de material
                por medio de una Tarjeta al Depto. de Recursos Materiales, la cual deberá dirigirse al Titular del Área Administrativa con 
                atención al Jefe del Departamento antes mencionado.
                
                <li>Se dirige de manera lineal de Dirección a Dirección y Departamento a Departamento.</li>
                <hr>
                <p> En caso de que se deba contestar un documento, éste se responderá con el mismo tipo de documento
                es decir: <br><br>
                <strong> Oficio ---> Se contesta con Oficio </strong><br>
                <strong>  Tarjeta ---> Se contesta con Tarjeta </strong></p>
                </ul>
    </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>
         <!-------------------Termina venta de modal primera para tarjetas------------------------------ --> 
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
       <!------------------Inicio Botón para la ventana emergente de ayuda ------------------>
       <a href="#Ayuda" role="button"  title="Ayuda" class="btn btn-large bg-warning btn-danger" data-toggle="modal"><i class="fas fa-question"></i></a>
  <!-- Ventana  -->
  <div id="Ayuda" class="modal fade">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                  <h4 class="modal-title" style="width: 66rem"> Identificación del documento: Archivo/Consecutivo</h4>
              </div>
              <div class="modal-body text-justify">
                  <p>El documento deberá contener las palabras <strong>Archivo/Consecutivo</strong> que servirán para identificar
                  el lugar de permanencia de los documentos de archivo, es decir, al <strong>documento original o Acuse original</strong>
                 se subrayará  la palabra <strong>Archivo </strong>como método de Identificación y será el área o Departamento que le dé
                 seguimiento al documento el responsable de conservar el mismo. Por otro lado, se subrayará la palabra <strong>Consecutivo</strong>
                 en la copia del documento, asi sea de conocimiento, si forma parte del control de entradas y salidas de la Dirección de Área o
                 como evidencia de que se turnó al departamento correspondiente para su atención, las copias no formarán parte de ningún expediente
                 por lo cual se conservarán hasta un año a partir del año inmediato posterior.</p>
                 <hr>
                 <strong> Archivo de trámite:</strong> <p> Integrado por documentos de archivos <strong>de uso y necesario</strong> para 
                 el ejercicio de las atribuciones y funciones de los sujetos obligados.
                 <br> Generalmente se ubican en el espacio físico del departamento que lo recibe o produce, generando un expediente por cada
                 trámite o proceso hasta que este concluya. En el caso de documentos Administrativos su resguardo es de 2 años, para documentos 
                 Jurídicos-Legales será <strong>hasta desahogo más 2 años</strong> y documentos con información Fiscal-Contable de <strong>2 a 12 años.</strong> </p>
   <hr>
   <strong>Archivo de concentración:</strong><p>Integrado por documentos transferidos desde las áreas o unidades productoras, cuyo
   <strong>uso y consulta es esporádica,</strong> y permanecen en él hasta su disposición documental.
   <br> En el caso de documentos Administrativos su resguardo es de <strong>5 años,</strong> para documentos Juriídicos-Legales
   será <strong>10 años</strong> y documentos con información Fiscal-Contable de <strong>6 a 12 años.</strong>
   <br> Finaliza la vigencia del documento, la transferencia secundaria puede ser al archvio histórico o a la baja documental, dependiendo
   de la valoración de cada uno de ellos.</p>
   <hr>
   <strong>Archivo histórico: </strong><p>Integrado por documentos de <strong>conservación permanente</strong> y de relevancia
   para la memoria nacional, regional o local de carácter público.
   <br>En este espacio permanecerán los documentos que contengan valores transcendentales en la historia del CECyTEV. Una 
   vez transferidos a este archivo los documentos podrán ser consultados por el público en general y sin exepción para fines de 
   investigación y conocimiento evolutivo de este organismo público.</p>
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
              <li class="breadcrumb-item active">Tarjetas</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @include('tarjetas.listTarjetas')
      </div>
    </section>

@endsection
