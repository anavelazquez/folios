//Se inicializa el mensaje de confirmación
const Toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 4000
});

//Se declara id_memorandum de manera global
var id_memorandum;
var id_memorandum_editar;

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
  var table = $("#memorandums").DataTable({
    "responsive": true,
    "columnDefs": [
      {
        "targets": [7,8],
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
          "url": "memorandumslista",
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
        "data":"permissions",
        "render": function (data){
          if(data == -1 || data == -2){
            return "<button type='button' class='btn btn-primary btn-sm' id='btn-editar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Editar Memorándum'><i class='right fas fa-edit'></i></button> <button type='button' class='btn btn-warning btn-sm' id='btn-cancelar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Cancelar Memorándum'><i class='right fas fa-ban'></i></button> <button type='button' class='btn btn-danger btn-sm' id='btn-eliminar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Eliminar Memorándum'><i class='right fas fa-trash-alt'></i></button>"
          }else{
            return "<button type='button' class='btn btn-primary btn-sm' id='btn-editar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Editar Memorándum'><i class='right fas fa-edit'></i></button> <button type='button' class='btn btn-danger btn-sm' id='btn-eliminar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Eliminar Memorándum'><i class='right fas fa-trash-alt'></i></button>"
          }
        }
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
  $('#memorandums').on( 'click', '#btn-agregar', function () {
    //se limpia la variable
    id_memorandum_editar = null;

    $("#memorandums-form")[0].reset();
    //Se vuelve a llamar la función para quitar los errores anteriores en el formulario
    validarFormulario();

    $("#ModalEditar").modal('show');
    //Se pone el titulo correspondiente a la modal
    $("#modal-label").html('Agregar Memorándum');
    //Se pone la etiqueta correspondiente al botón
    $("#editar-memorandum").html('Agregar Memorándum');
  });

  // ---------- Modal Editar ----------
  $('#memorandums').on( 'click', '#btn-editar', function () {

    var data = table.row( $(this).parents('tr') ).data();
    id_memorandum_editar = data['id'];
    console.log('data', data);

    //Cambiar etiqueta titulo modal a Editar
    var boton = document.getElementById('modal-label');
    boton.innerText = "Editar Memorándum "+data['clave'];

    //Cambiar etiqueta del boton a Editar
    var boton = document.getElementById('editar-memorandum');
    boton.innerText = "Editar";

    $("#ModalEditar").modal('show');

    $("#dirigido").val(data['dirigido']);
    $("#seguimiento").val(data['seguimiento']);
    $("#asunto").val(data['asunto']);
    $("#observaciones").val(data['obs']);
    $("#estado").val(data['estado']);
    $("input[name='estado'][value='"+data['estado']+"']").prop("checked",true);
  });

  // ---------- Agregar memorandum ----------
  $('#memorandums-form').on('submit', function(e) {
      e.preventDefault();
      var f = $(this);

      console.log('id_memorandum_editar', id_memorandum_editar);

      //Se crea el objeto FormData y se le asigna el valor del formulario
      var formData = new FormData(document.getElementById("memorandums-form"));

      console.log('formData', formData);

      if(id_memorandum_editar){
          $.ajax({
           headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           method: "POST",
           url: 'update-memorandum/'+id_memorandum_editar,
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
         url: 'guardar-memorandum',
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
  $('#memorandums').on( 'click', '#btn-eliminar', function () {
    var data = table.row( $(this).parents('tr') ).data();
    id_memorandum = data['id'];

    $("#memorandum").html(data['clave']);

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

    $("#eliminar-memorandum").on("click", function(){
      callback(true);
      $("#ModalEliminar").modal('hide');
    });
  };

  modalConfirm(function(confirm){
    if(confirm){
      $.ajax({
       type:'GET',
       url: 'delete-memorandum/'+id_memorandum
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

  // ---------- Modal Cancelar Memorándum ----------
  $('#memorandums').on( 'click', '#btn-cancelar', function () {
      var data = table.row( $(this).parents('tr') ).data();
      id_memorandum_editar = data['id'];
      
      $.ajax({
        method: "GET",
        url: 'verificar-memorandums/'+id_memorandum_editar })
      .done(function (msg) {
        if(msg['cancelado'] == 1){
          Toast.fire({
            type: msg['type'],
            title: msg['text']
          }); 
        }else{
          $("#ModalCancelar").modal('show');
          console.log('id_memorandum_editar', id_memorandum_editar);
        }
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

  // ---------- Cancelar Memorándum ----------
  $('#cancelar-form').on('submit', function(e) {
      e.preventDefault();
      var f = $(this);

      firma = $("input[name='firma']:checked").val();
      console.log('firma', firma);

      $.ajax({
        method: "GET",
        url: 'cancelar-memorandum/'+id_memorandum_editar+'/'+firma })
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
