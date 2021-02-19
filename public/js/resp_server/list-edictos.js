//Se inicializa el mensaje de confirmación
const Toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 4000
});

//Quita las opciones de un select cuando el valor del que depende cambia
function removeOptions(selectbox){
  var i;
  for(i = selectbox.options.length - 1 ; i >= 0 ; i--)
  {
      selectbox.remove(i);
  }
}

async function VerificarArchivo() {
   if(!document.querySelector('#archivo').files[0]){
    $("#archivo").addClass("is-invalid");
    $("#invalid-feedback-archivo").html('Seleccione un archivo .pdf');
    $("#invalid-feedback-archivo").fadeIn();
   }
   return;
}

function validarFormulario(msg){
  if(msg){
    //console.log(msg);
    //
    if(msg.responseJSON.errors.expediente){
        $("#expediente").addClass("is-invalid");
        $("#invalid-feedback-expediente").html(msg.responseJSON.errors.expediente);
        $("#invalid-feedback-expediente").fadeIn();
    }else{
        $("#expediente").removeClass("is-invalid");
        $("#invalid-feedback-expediente").fadeOut();
    }

    if(msg.responseJSON.errors.no_edicto){
        $("#no_edicto").addClass("is-invalid");
        $("#invalid-feedback-no_edicto").html(msg.responseJSON.errors.no_edicto);
        $("#invalid-feedback-no_edicto").fadeIn();
    }else{
        $("#no_edicto").removeClass("is-invalid");
        $("#invalid-feedback-no_edicto").fadeOut();
    }

    if(msg.responseJSON.errors.distrito){
        $("#distrito").addClass("is-invalid");
        $("#invalid-feedback-distrito").html(msg.responseJSON.errors.distrito);
        $("#invalid-feedback-distrito").fadeIn();
    }else{
        $("#distrito").removeClass("is-invalid");
        $("#invalid-feedback-distrito").fadeOut();
    }

    if(msg.responseJSON.errors.juzgado){
        $("#juzgado").addClass("is-invalid");
        $("#invalid-feedback-juzgado").html(msg.responseJSON.errors.juzgado);
        $("#invalid-feedback-juzgado").fadeIn();
    }else{
        $("#juzgado").removeClass("is-invalid");
        $("#invalid-feedback-juzgado").fadeOut();
    }

    if(msg.responseJSON.errors.materia){
        $("#materia").addClass("is-invalid");
        $("#invalid-feedback-materia").html(msg.responseJSON.errors.materia);
        $("#invalid-feedback-materia").fadeIn();
    }else{
        $("#materia").removeClass("is-invalid");
        $("#invalid-feedback-materia").fadeOut();
    }

    if(msg.responseJSON.errors.juicio){
        $("#juicio").addClass("is-invalid");
        $("#invalid-feedback-juicio").html(msg.responseJSON.errors.juicio);
        $("#invalid-feedback-juicio").fadeIn();
    }else{
        $("#juicio").removeClass("is-invalid");
        $("#invalid-feedback-juicio").fadeOut();
    }

    if(msg.responseJSON.errors.prestacion_demandada){
        $("#prestacion_demandada").addClass("is-invalid");
        $("#invalid-feedback-prestacion_demandada").html(msg.responseJSON.errors.prestacion_demandada);
        $("#invalid-feedback-prestacion_demandada").fadeIn();
    }else{
        $("#prestacion_demandada").removeClass("is-invalid");
        $("#invalid-feedback-prestacion_demandada").fadeOut();
    }

    if(msg.responseJSON.errors.fecha_publicacion){
        $("#fecha_publicacion").addClass("is-invalid");
        $("#invalid-feedback-fecha_publicacion").html(msg.responseJSON.errors.fecha_publicacion);
        $("#invalid-feedback-fecha_publicacion").fadeIn();
    }else{
        $("#fecha_publicacion").removeClass("is-invalid");
        $("#invalid-feedback-fecha_publicacion").fadeOut();
    }

  }else{
    //console.log('no hay errores');
    $("#expediente").removeClass("is-invalid");
    $("#invalid-feedback-expediente").fadeOut();
    $("#no_edicto").removeClass("is-invalid");
    $("#invalid-feedback-no_edicto").fadeOut();
    $("#distrito").removeClass("is-invalid");
    $("#invalid-feedback-distrito").fadeOut();
    $("#juzgado").removeClass("is-invalid");
    $("#invalid-feedback-juzgado").fadeOut();
    $("#materia").removeClass("is-invalid");
    $("#invalid-feedback-materia").fadeOut();
    $("#juicio").removeClass("is-invalid");
    $("#invalid-feedback-juicio").fadeOut();
    $("#prestacion_demandada").removeClass("is-invalid");
    $("#invalid-feedback-prestacion_demandada").fadeOut();
    $("#fecha_publicacion").removeClass("is-invalid");
    $("#invalid-feedback-fecha_publicacion").fadeOut();

  }
}

function validarPeriodo(fecha_inicio, fecha_fin){
  //console.log('fecha_inicio', fecha_inicio);
  //console.log('fecha_fin', fecha_fin);
  if(fecha_inicio == '' || fecha_fin == ''){
    if(fecha_inicio == ''){
        $("#fecha_inicio").addClass("is-invalid");
        $("#invalid-feedback-fecha_inicio").html('Ingrese una fecha inicio');
        $("#invalid-feedback-fecha_inicio").fadeIn();
    }else{
        $("#fecha_inicio").removeClass("is-invalid");
        $("#invalid-feedback-fecha_inicio").fadeOut();
    }

    if(fecha_fin == ''){
        $("#fecha_fin").addClass("is-invalid");
        $("#invalid-feedback-fecha_fin").html('Ingrese una fecha fin');
        $("#invalid-feedback-fecha_fin").fadeIn();
    }else{
        $("#fecha_fin").removeClass("is-invalid");
        $("#invalid-feedback-fecha_fin").fadeOut();
    }
    return false;
  } else {
    if(fecha_inicio > fecha_fin){
      $("#fecha_fin").addClass("is-invalid");
      $("#fecha_inicio").addClass("is-invalid");
      $("#invalid-feedback-mayor").html('La fecha inicio debe ser menor a la fecha fin');
      $("#invalid-feedback-mayor").fadeIn();
      return false;
    }else{
      $("#fecha_fin").removeClass("is-invalid");
      $("#fecha_inicio").removeClass("is-invalid");
      $("#invalid-feedback-mayor").fadeOut();
      return true;
    }
  }
}

function mostrarOcultarTablaPeriodos(periodos){
  //console.log('mostrarOcultarTablaPeriodos');
  if(periodos > 0){
      $("#tabla_periodos_publicacion").css("display", "block");
  }else{
      $("#tabla_periodos_publicacion").css("display", "none");
  }
}


//Se declara idfirmante de manera global
var id_edicto;
var id_edicto_editar;
//Se declara el arreglo vacío de los periodos de publicacion
var periodos_publicacion = [];
//Se declara el arreglo vacío de las fechas inicio
var fechas_inicio = [];
//Variable para saber si se modificará el archivo
var modificar_archivo = 0;


//Llenar Select de juzgados de acuerdo al distrito seleccionado
function desplegarJuzgados(id_distrito, editar = null, id_juzgado = null){
  removeOptions(document.getElementById("juzgado"));
  $.ajax({
    method: 'GET',
    url: '/edictos/juzgados/'+id_distrito,
  })
  .done(function( data ) {
    //console.log(data['distrito']['juzgados']);
    if(data['distrito']['juzgados']){
      $("#juzgado").append('<option value="">Selecciona un Juzgado</option>');
      $.each(data['distrito']['juzgados'], function(key, juzgado) {
        $("#juzgado").append('<option value='+juzgado.id_juzgado+'>'+juzgado.juzgado+'</option>');
        //console.log('juzgado', juzgado);
      });
    }
    if(editar){
      $("#juzgado").val(id_juzgado);
    }
  });
}

//Llenar Select de Juicio de acuerdo a la materia seleccionada
function desplegarJuicios(id_materia, editar = null, id_juicio = null){
  removeOptions(document.getElementById("juicio"));
  $.ajax({
    method: 'GET',
    url: '/edictos/juicios-por-materia/'+id_materia,
  })
  .done(function( data ) {
    //console.log(data['materia']['juicios']);
    if(data['materia']['juicios']){
      $("#juicio").append('<option value="">Selecciona un Juicio</option>');
      $.each(data['materia']['juicios'], function(key, juicio) {
        $("#juicio").append('<option value='+juicio.id_juicio+'>'+juicio.juicio+'</option>');
        //console.log('juicio', juicio);
      });
    }
    if(editar){
      $("#juicio").val(id_juicio);
    }
  });
}


$(document).ready(function () {

  $('.date').datepicker({
    format: 'dd/mm/yyyy',
    language: 'es',
    autoclose: true
  });

   var table = $("#edictos").DataTable({
      "responsive": true,
      "columnDefs": [
        {
        "targets": [3,4,5,6,7],
        class: "wrap"
      }],
      "order": [[ 1, 'asc' ]],
      "ordering": false,
      "responsive": true,
      "serverSide": true,
      "ajax": {
            "url": "/edictos/edictoslista",
            "dataSrc": "data"
            },
      "columns": [
          /*
          {
            "data":null,
            "render":
            function (){
              var f = new Date();
              var dia = ("0" + (f.getDate() + 1)).slice(-2);
              var mes = ("0" + (f.getMonth() + 1)).slice(-2);

              return dia + '/' + mes + '/' + f.getFullYear();
            }
          },
          */
          {
            "data": "fecha_publicacion",
            "render": function (fecha_publicacion){
              return fecha_publicacion.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
            }
          },
          {
            "data":"numero_expediente",
            "render": function (data, type, row){
              return row.numero_expediente+'/'+row.anio_expediente;
            }
          },
          {
            "data": "numero_edicto",
            "render": function (data, type, row){
              return row.numero_edicto+'/'+row.anio_edicto;
            }
          },
          { "data": "distrito.distrito"},
          { "data": "juzgado.juzgado"},
          { "data": "juicio.juicio"},
          { "data": "prestacion_demandada.prestacion_demandada"},
          {
            "data":0,
            "defaultContent": "<button type='button' class='btn btn-primary btn-sm' id='btn-editar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Editar Edicto'><i class='right fas fa-edit'></i></button>  <button type='button' class='btn btn-danger btn-sm' id='btn-eliminar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Eliminar Edicto'><i class='right fas fa-trash-alt'></i></button>"
            //<button type='button' class='btn btn-secondary btn-sm' id='btn-descargar-xml' style='margin-right: 10px'><i class='right fas fa-file-code'></i></button>
          }
          ],
        "language": {
        "decimal": "",
        "emptyTable":     "Sin datos disponibles en la tabla",
        "info": "Página _PAGE_ de _PAGES_",
        "infoEmpty": "No hay registros",
        "infoFiltered": "(Filtrado de un total de _MAX_ registros)",
        "infoPostFix":    "",
        "thousands":      ",",
        "lengthMenu": "Mostrando _MENU_ registros por página",
        "loadingRecords": "Cargando...",
        "processing":     "Procesando...",
        "search":         "Buscar:",
        "zeroRecords": "No se encontraron resultados",
        "paginate": {
            "first":      "Primero",
            "last":       "Último",
            "next":       "Siguiente",
            "previous":   "Anterior"
        },
        "aria": {
            "sortAscending":  ": Activar para ordenar de manera ascendente",
            "sortDescending": ": Activar para ordenar de manera descendente"
        },
      },
    });

  $("select[name='distrito']").change(function(){
    //console.log('cambio el distrito');
    var id_distrito = $(this).val();
    desplegarJuzgados(id_distrito);
  });

  $("select[name='materia']").change(function(){
    //console.log('cambio la materia');
    var id_materia = $(this).val();
    desplegarJuicios(id_materia);
  });


   // ---------- Modal Confirmar Eliminar ----------
  $('#edictos').on( 'click', '#btn-eliminar', function () {
    var data = table.row( $(this).parents('tr') ).data();
    idpublicacion = data['id_publicacion'];

    $("#edicto").html(data['numero_edicto']+'/'+data['anio_edicto']);

    var options = {
      'backdrop': 'static'
    };

    $('#ModalEliminar').modal(options);
  });

  //Respuesta confirmación eliminar
  var modalConfirm = function(callback){
    $("#btn-confirm").on("click", function(){
      $("#ModalEliminar").modal('show');
    });

    $("#eliminar-edicto").on("click", function(){
      callback(true);
      $("#ModalEliminar").modal('hide');
    });
  };

  modalConfirm(function(confirm){
    if(confirm){
      $.ajax({
       type:'GET',
       url: '/edictos/delete-edicto/'+idpublicacion
     })
      .done(function( msg ) {
        table.ajax.reload();
        Toast.fire({
          type: msg['type'],
          title: msg['text']
        })
      })

    }
  });

  // ---------- Modal Agregar ----------
  $('#edictos').on( 'click', '#btn-agregar', function () {
    //se limpia la variable
    id_edicto_editar = null;

    $("#edictos-form")[0].reset();
    //Se vuelve a llamar la función para quitar los errores anteriores en el formulario
    validarFormulario();
    //VerificarArchivo();
    $("#ModalEditar").modal('show');
    //Se pone el titulo correspondiente a la modal
    $("#modal-label").html('Agregar Edicto');
    //Se pone la etiqueta correspondiente al botón
    $("#editar-edicto").html('Agregar Edicto');
    //Se oculta boton ver archivo
    $("#ver-archivo").css("display", "none");
    //Se oculta boton modificar archivo
    $("#modificar-archivo").css("display", "none");
    //Se muestra inputfile archivo
    $("#campo-archivo").css("display", "block");
    //Se muestra el campo fecha de publicacion
    $("#periodo-indefinido").css("display", "block");
  });


  // ---------- Modal Editar ----------
  $('#edictos').on( 'click', '#btn-editar', function () {

    var data = table.row( $(this).parents('tr') ).data();
    id_edicto_editar = data['id_publicacion'];
    //console.log('data', data);

    //Cambiar etiqueta titulo modal a Editar
    var boton = document.getElementById('modal-label');
    boton.innerText = "Editar Edicto";

    //Cambiar etiqueta del boton a Editar
    var boton = document.getElementById('editar-edicto');
    boton.innerText = "Editar";

    $("#ModalEditar").modal('show');

    $("#expediente").val(data['numero_expediente']+'/'+data['anio_expediente']);
    $("#no_edicto").val(data['numero_edicto']+'/'+data['anio_edicto']);
    $("#distrito").val(data['id_distrito']);
    //Se llena el select de juzgados, se indica que es editar y se envía el id_juzgado para que se seleccione
    desplegarJuzgados(data['id_distrito'], 1, data['id_juzgado']);
    $("#materia").val(data['id_materia']);
    //Se llena el select de juicios, se indica que es editar y se envía el id_juicio para que se seleccione
    desplegarJuicios(data['id_materia'], 1, data['id_juicio']);
    $("#prestacion_demandada").val(data['id_prestacion_demandada']);
    //console.log("data['id_tipo_periodo_publicacion']", data['id_tipo_periodo_publicacion']);
    if(data['id_tipo_periodo_publicacion'] == 'I'){
      //console.log('true');
      document.getElementById("periodoIndefinido").checked = true;
      $("#periodoIndefinido").click();
      $("#fecha_publicacion").val(data['fecha_publicacion']);
    }else{
      //console.log('false');
      document.getElementById("periodoDefinido").checked = true;
      $("#periodoDefinido").click();

      mostrarOcultarTablaPeriodos(data['id_tipo_periodo_publicacion'].length);
      generarPeriodosArray(data['periodos_publicacion']);

    }

    //Se muestra boton ver archivo
    $("#ver-archivo").css("display", "inline-table");
    $("#ver-archivo").css("padding-top", "30px");
    $("#ver-archivo").css("padding-left", "0px");
    $("#ver-archivo-link").attr("href", data['ruta_archivo']);

    //Se muestra boton modificar archivo
    $("#modificar-archivo").css("display", "inline-table");

    //Se oculta inputfile archivo
    $("#campo-archivo").css("display", "none");

  });


  // ---------- Agregar Edicto ----------
  $('#edictos-form').on('submit', function(e) {
      e.preventDefault();
      var f = $(this);
      console.log('id_edicto_editar', id_edicto_editar);

      //Se crea el objeto FormData y se le asigna el valor del formulario
      var formData = new FormData(document.getElementById("edictos-form"));

      //Si el tipo de periodo es definido se agrega el arreglo al formulario
      if($('input:radio[name=periodoPublicacion]:checked').val() == 'D'){
        if(periodos_publicacion.length < 1){
          $("#invalid-feedback-definido").html('Agregue al menos un período a la publicación');
          $("#invalid-feedback-definido").fadeIn();
          return;
        }else{
          let fecha_minima = _.min(fechas_inicio);
          //console.log('fecha_minima', fecha_minima);

          formData.append('periodos_publicacion', JSON.stringify(periodos_publicacion));
          formData.set('fecha_publicacion', JSON.stringify(fecha_minima));
        }
      }else{
        $("#invalid-feedback-definido").fadeOut();
      }

      //console.log('formData', formData);
      VerificarArchivo();
      if(id_edicto_editar){
          $.ajax({
           headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           method: "POST",
           url: '/edictos/update-edicto/'+id_edicto_editar+'/'+modificar_archivo,
           data: formData,
           contentType: false,
           processData: false})
          .done(function (msg) {
            periodos_publicacion = [];
            fechas_inicio = [];
            mostrarOcultarTablaPeriodos(periodos_publicacion.length);
            genera_tabla(periodos_publicacion);
            $("#ModalEditar").modal('hide');
            table.ajax.reload();
            setTimeout(function(){
              //console.log(msg);
              Toast.fire({
              type: msg['type'],
              title: msg['text']
            }); }, 800);
          }).fail(function (jqXHR, textStatus) {
            validarFormulario(jqXHR);
          })
      }else{
        $.ajax({
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         method: "POST",
         url: '/edictos/guardar-edicto',
         data: formData,
         contentType: false,
         processData: false})
        .done(function (msg) {
          $("#periodo-definido").css("display", "none");
          periodos_publicacion = [];
          fechas_inicio = [];
          mostrarOcultarTablaPeriodos(periodos_publicacion.length);
          genera_tabla(periodos_publicacion);
          document.querySelector('#custom-archivo-label').innerText = 'Seleccionar Archivo';
          $("#ModalEditar").modal('hide');
          table.ajax.reload();
          setTimeout(function(){
            //console.log(msg);
            Toast.fire({
            type: msg['type'],
            title: msg['text']
          }); }, 800);
        }).fail(function (jqXHR, textStatus) {
          VerificarArchivo();
          validarFormulario(jqXHR);
        })
      }
  });

  //Función para asignar el nombre del archivo al input file Archivo
  $('#archivo').on('change',function(){
    var fileName = document.getElementById("archivo").files[0].name;
    $(this).next('.form-control-file').addClass("selected").html(fileName);
    document.querySelector('#custom-archivo-label').innerText = fileName;
  });


  // ---------- Agregar periodos de publicación  ----------
  $(document).on('click', '#btn-agregar-periodo', function(e){
      e.preventDefault();
      let fecha_inicio = $("#fecha_inicio").val();
      let fecha_fin = $("#fecha_fin").val();
      valido = validarPeriodo(fecha_inicio, fecha_fin);
      if(valido){
        //Para quitar los feedbacks que pudieran haber quedado
        $("#fecha_inicio").removeClass("is-invalid");
        $("#invalid-feedback-fecha_inicio").fadeOut();
        $("#fecha_fin").removeClass("is-invalid");
        $("#invalid-feedback-fecha_fin").fadeOut();
        //Se agregan la fecha inicio y fecha fin al arreglo periodos_publicacion
        periodos_publicacion.push({fecha_inicio: fecha_inicio, fecha_fin: fecha_fin});
        //Se agregan la fecha inicio al arreglo fechas_inicio
        fechas_inicio.push(fecha_inicio);
        //Se limpian los campos
        fecha_inicio = $("#fecha_inicio").val("");
        fecha_fin = $("#fecha_fin").val("");
        mostrarOcultarTablaPeriodos(periodos_publicacion.length);
        genera_tabla(periodos_publicacion);
      }

   });

  function genera_tabla(periodos_publicacion) {
    var stritem = "";
    // Obtener la referencia del elemento table
    var tblBody = document.getElementById("tbody_periodos_publicacion");
    for (i = 0; i < periodos_publicacion.length; ++i) {
      //periodos_publicacion.push(output.data[i].fk_id_cita);

      stritem += "<tr>";
      stritem += "<td>"+periodos_publicacion[i].fecha_inicio+"</td>";
      stritem += "<td>"+periodos_publicacion[i].fecha_fin+"</td>";
      stritem += "<td><button type='button' class='btn btn-danger btn-sm borrar' id='btn-agregar-periodo"+i+"' value='"+i+"' data-toggle='tooltip' data-placement='top' title='Eliminar Período'><i class='right fas fa-trash-alt'></i></button></td>";
      stritem += "</tr>";

    }
    $("#tbody_periodos_publicacion").html(stritem);
  }

  function transformarFecha(fecha){
    return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
  }

  function generarPeriodosArray(periodos_publicacion_origen){
    periodos_publicacion = [];
    for (i = 0; i < periodos_publicacion_origen.length; ++i) {
      fecha_inicio_transformada = transformarFecha(periodos_publicacion_origen[i].fecha_inicio_publicacion);
      fecha_fin_transformada = transformarFecha(periodos_publicacion_origen[i].fecha_fin_publicacion);

      periodos_publicacion.push({fecha_inicio: fecha_inicio_transformada, fecha_fin: fecha_fin_transformada });
    }
    //console.log('periodos_publicacion', periodos_publicacion);
    genera_tabla(periodos_publicacion);
  }

  //Eliminar fila de período de la tabla de periodo de la publicación
  $(document).on('click', '.borrar', function (event) {
    event.preventDefault();
    let indice = event.currentTarget.value;
    //Eliminar un periodo del array
    periodos_publicacion.splice(indice,1);

    //Redibujar la tabla con los nuevos elementos
    mostrarOcultarTablaPeriodos(periodos_publicacion.length);
    genera_tabla(periodos_publicacion)
  });

  //Muestra u oculta los detalles de los periodos de la publicacion segun corresponda
  $(".periodoPublicacion").click(function(event){
      var valor = $(this).val();

      if(valor == 'D'){
         $("#periodo-definido").css("display", "block");
         $("#periodo-indefinido").css("display", "none");
      }else{
          $("#periodo-definido").css("display", "none");
          $("#periodo-indefinido").css("display", "block");
          periodos_publicacion = [];
          fechas_inicio = [];
          mostrarOcultarTablaPeriodos(periodos_publicacion.length);
          genera_tabla(periodos_publicacion);
      }
  });

  $("#ModalEditar").on("hidden.bs.modal", function () {
    $("#periodo-definido").css("display", "none");
    periodos_publicacion = [];
    fechas_inicio = [];
    mostrarOcultarTablaPeriodos(periodos_publicacion.length);
    genera_tabla(periodos_publicacion);
    $("#invalid-feedback-definido").fadeOut();
  });

  $("#modificar-archivo").click(function(event){
    //Se oculta boton modificar archivo
    $("#modificar-archivo").css("display", "none");
    //Se oculta boton ver archivo
    $("#ver-archivo").css("display", "none");

    //Se muestra inputfile archivo
    $("#campo-archivo").css("display", "inline");

    modificar_archivo = 1;

  });

});
