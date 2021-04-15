//Se inicializa el mensaje de confirmación
const Toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 4000
});

//Se declara id_oficio de manera global
var id_oficio;
var id_oficio_editar;

function validarFormulario(msg){
  if(msg){
    console.log(msg);
    if(msg.responseJSON.errors.dirigido){
        $("#dirigido").addClass("is-invalid");
        $("#invalid-feedback-dirigido").html(msg.responseJSON.errors.dirigido);
        $("#invalid-feedback-dirigido").fadeIn();
    }else{
        $("#dirigido").removeClass("is-invalid");
        $("#invalid-feedback-dirigido").fadeOut();
    }

    if(msg.responseJSON.errors.seguimiento){
        $("#seguimiento").addClass("is-invalid");
        $("#invalid-feedback-seguimiento").html(msg.responseJSON.errors.seguimiento);
        $("#invalid-feedback-seguimiento").fadeIn();
    }else{
        $("#seguimiento").removeClass("is-invalid");
        $("#invalid-feedback-seguimiento").fadeOut();
    }

    if(msg.responseJSON.errors.asunto){
        $("#asunto").addClass("is-invalid");
        $("#invalid-feedback-asunto").html(msg.responseJSON.errors.asunto);
        $("#invalid-feedback-asunto").fadeIn();
    }else{
        $("#asunto").removeClass("is-invalid");
        $("#invalid-feedback-asunto").fadeOut();
    }

    if(msg.responseJSON.errors.observaciones){
        $("#observaciones").addClass("is-invalid");
        $("#invalid-feedback-observaciones").html(msg.responseJSON.errors.observaciones);
        $("#invalid-feedback-observaciones").fadeIn();
    }else{
        $("#observaciones").removeClass("is-invalid");
        $("#invalid-feedback-observaciones").fadeOut();
    }
  }else{
    //console.log('no hay errores');
    $("#dirigido").removeClass("is-invalid");
    $("#invalid-feedback-dirigido").fadeOut();
    $("#seguimiento").removeClass("is-invalid");
    $("#invalid-feedback-seguimiento").fadeOut();
    $("#asunto").removeClass("is-invalid");
    $("#invalid-feedback-asunto").fadeOut();
    $("#observaciones").removeClass("is-invalid");
    $("#invalid-feedback-observaciones").fadeOut();
  }
}

$(document).ready(function () {
  var table = $("#oficios").DataTable({
    "responsive": true,
    "columnDefs": [
      {
        "targets": [7, 8],
        "className": "dt-center"
      },
      {
        "targets": [0,1,5,6],
        class: "wrap"
      }
    ],
    "order": [[ 1, 'asc' ]],
    "ordering": false,
    "responsive": true,
    "serverSide": true,
    "ajax": {
          "url": "oficioslista",
          "dataSrc": "data"
          },
    "columns": [
      {
        "data": "fecha",
        "render": function (fecha){

          fecha_div = fecha.split(" ");
          fecha_date = fecha_div[0];
          date = fecha_date.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
          fecha_time = fecha_div[1];
          time = fecha_time.replace(/^(\d{2}):(\d{2}):(\d{2})$/g,'$1:$2');

          return date +" "+ time;
        }
      },

      { "data": "destinatario.nombre_trabajador"},
      { "data": "seguimiento"},
      { "data": "autor"},
      { "data": "clave"},
      { "data": "asunto"},
      { "data": "obs"},
      {
        "data":"estado",
        "render": function (data){
          if(data == 'verde'){
            return "<i class='nav-icon fas fa-circle' style='color: green'></i>";
          }else if(data == 'amarillo'){
            return "<i class='nav-icon fas fa-circle' style='color: yellow'></i>";
          }else if(data == 'rojo'){
            return "<i class='nav-icon fas fa-circle' style='color: red'></i>";
          }else if(data == 'cancelado'){
            return "<i class='nav-icon fas fa-exclamation-triangle' style='color: black'></i>";
          }else{
            return "";
          }
        }
      },
      {
        "data":0,
        "defaultContent": "<button type='button' class='btn btn-primary btn-sm' id='btn-editar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Editar Oficio'><i class='right fas fa-edit'></i></button>  <button type='button' class='btn btn-danger btn-sm' id='btn-eliminar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Eliminar Oficio'><i class='right fas fa-trash-alt'></i></button>"
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

  // ---------- Modal Agregar ----------
  $('#oficios').on( 'click', '#btn-agregar', function () {
    //se limpia la variable
    id_oficio_editar = null;

    $("#oficios-form")[0].reset();
    //Se vuelve a llamar la función para quitar los errores anteriores en el formulario
    validarFormulario();

    $("#ModalEditar").modal('show');
    //Se pone el titulo correspondiente a la modal
    $("#modal-label").html('Agregar Oficio');
    //Se pone la etiqueta correspondiente al botón
    $("#editar-oficio").html('Agregar Oficio');
  });

  // ---------- Modal Editar ----------
  $('#oficios').on( 'click', '#btn-editar', function () {

    var data = table.row( $(this).parents('tr') ).data();
    id_oficio_editar = data['id'];
    console.log('data', data);
    console.log('id_oficio_editar', id_oficio_editar);

    //Cambiar etiqueta titulo modal a Editar
    var boton = document.getElementById('modal-label');
    boton.innerText = "Editar Oficio "+data['clave'];

    //Cambiar etiqueta del boton a Editar
    var boton = document.getElementById('editar-oficio');
    boton.innerText = "Editar";

    $("#ModalEditar").modal('show');

    $("#dirigido").val(data['dirigido']);
    $("#seguimiento").val(data['seguimiento']);
    $("#asunto").val(data['asunto']);
    $("#observaciones").val(data['obs']);
    $("#estado").val(data['estado']);
    $("input[name='estado'][value='"+data['estado']+"']").prop("checked",true);
  });

  // ---------- Agregar Oficio ----------
  $('#oficios-form').on('submit', function(e) {
      e.preventDefault();
      var f = $(this);

      console.log('id_oficio_editar', id_oficio_editar);

      //Se crea el objeto FormData y se le asigna el valor del formulario
      var formData = new FormData(document.getElementById("oficios-form"));

      console.log('formData', formData);

      if(id_oficio_editar){
          $.ajax({
           headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           method: "POST",
           url: 'update-oficio/'+id_oficio_editar,
           data: formData,
           contentType: false,
           processData: false})
          .done(function (msg) {
            $("#ModalEditar").modal('hide');
            table.ajax.reload();
            setTimeout(function(){
              console.log(msg);
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
         url: 'guardar-oficio',
         data: formData,
         contentType: false,
         processData: false})
        .done(function (msg) {
          $("#ModalEditar").modal('hide');
          table.ajax.reload();
          setTimeout(function(){
            console.log(msg);
            Toast.fire({
            type: msg['type'],
            title: msg['text']
          }); }, 800);
        }).fail(function (jqXHR, textStatus) {
          validarFormulario(jqXHR);
        })
      }
  });

  // ---------- Modal Confirmar Eliminar ----------
  $('#oficios').on( 'click', '#btn-eliminar', function () {
    var data = table.row( $(this).parents('tr') ).data();
    id_oficio = data['id'];

    $("#oficio").html(data['clave']);

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

    $("#eliminar-oficio").on("click", function(){
      callback(true);
      $("#ModalEliminar").modal('hide');
    });
  };

  modalConfirm(function(confirm){
    if(confirm){
      $.ajax({
       type:'GET',
       url: 'delete-oficio/'+id_oficio
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

  // ---------- Modal Confirmar Eliminar ----------
  $('#oficios').on( 'click', '#btn-eliminar', function () {
    var data = table.row( $(this).parents('tr') ).data();
    id_oficio = data['id'];

    $("#oficio").html(data['clave']);

    var options = {
      'backdrop': 'static'
    };

    $('#ModalEliminar').modal(options);
  });

  // ---------- Modal Cancelar oficio ----------
  $('#oficios-form').change(function(){
      selected_value = $("input[name='estado']:checked").val();
      if(selected_value == 'cancelado'){
        $("#ModalCancelar").modal('show');
        console.log('id_oficio_editar', id_oficio_editar);
      }
  });

  // ---------- Cancelar Oficio ----------
  $('#cancelar-form').on('submit', function(e) {
      e.preventDefault();
      var f = $(this);

      firma = $("input[name='firma']:checked").val();
      console.log('firma', firma);

      $.ajax({
        method: "GET",
        url: 'cancelar-oficio/'+id_oficio_editar+'/'+firma })
      .done(function (msg) {
        $("#ModalEditar").modal('hide');
        $("#ModalCancelar").modal('hide');
        table.ajax.reload();
        setTimeout(function(){
          console.log(msg);
          Toast.fire({
            type: msg['type'],
            title: msg['text']
          }); 
        }, 800);
      })
      .fail(function (jqXHR, textStatus) {
        setTimeout(function(){
          console.log(msg);
          Toast.fire({
            type: 'error',
            title: 'Ocurrió un error'
          }); 
        }, 800);
        console.log(jqXHR);
      });
  });




});
