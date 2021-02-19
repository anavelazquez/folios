//Se inicializa el mensaje de confirmación
const Toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 4000
});

//Se declara id_tarjeta de manera global
var id_tarjeta;
var id_tarjeta_editar;

function validarFormulario(msg){
  if(msg){
    console.log(msg);
    /*
    if(msg.responseJSON.errors.expediente){
        $("#expediente").addClass("is-invalid");
        $("#invalid-feedback-expediente").html(msg.responseJSON.errors.expediente);
        $("#invalid-feedback-expediente").fadeIn();
    }else{
        $("#expediente").removeClass("is-invalid");
        $("#invalid-feedback-expediente").fadeOut();
    }
  }else{
    //console.log('no hay errores');
    $("#expediente").removeClass("is-invalid");
    $("#invalid-feedback-expediente").fadeOut();
    */
  }
}

$(document).ready(function () {
  var table = $("#tarjetas").DataTable({
    "responsive": true,
    "columnDefs": [
      {
        "targets": [8],
        "className": "dt-center"
      },
      {
        "targets": [0,5,6],
        class: "wrap"
      }
    ],
    "order": [[ 1, 'asc' ]],
    "ordering": false,
    "responsive": true,
    "serverSide": true,
    "ajax": {
          "url": "tarjetaslista",
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

      { "data": "dirigido"},
      { "data": "seguimiento"},
      { "data": "autor"},
      { "data": "clave"},
      { "data": "asunto"},
      { "data": "obs"},
      { "data": "cancel"},
      {
        "data":0,
        "defaultContent": "<button type='button' class='btn btn-primary btn-sm' id='btn-editar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Editar Tarjeta'><i class='right fas fa-edit'></i></button>  <button type='button' class='btn btn-danger btn-sm' id='btn-eliminar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Eliminar Tarjeta'><i class='right fas fa-trash-alt'></i></button>"
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
  $('#tarjetas').on( 'click', '#btn-agregar', function () {
    //se limpia la variable
    id_tarjeta_editar = null;

    $("#tarjetas-form")[0].reset();
    //Se vuelve a llamar la función para quitar los errores anteriores en el formulario
    validarFormulario();

    $("#ModalEditar").modal('show');
    //Se pone el titulo correspondiente a la modal
    $("#modal-label").html('Agregar Tarjeta');
    //Se pone la etiqueta correspondiente al botón
    $("#editar-tarjeta").html('Agregar Tarjeta');
  });

  // ---------- Modal Editar ----------
  $('#tarjetas').on( 'click', '#btn-editar', function () {

    var data = table.row( $(this).parents('tr') ).data();
    id_tarjeta_editar = data['id'];
    console.log('data', data);

    //Cambiar etiqueta titulo modal a Editar
    var boton = document.getElementById('modal-label');
    boton.innerText = "Editar Tarjeta "+data['clave'];

    //Cambiar etiqueta del boton a Editar
    var boton = document.getElementById('editar-tarjeta');
    boton.innerText = "Editar";

    $("#ModalEditar").modal('show');

    $("#dirigido").val(data['dirigido']);
    $("#seguimiento").val(data['seguimiento']);
    $("#asunto").val(data['asunto']);
    $("#observaciones").val(data['obs']);
    $("#cancelado").val(data['cancel']);
  });

  // ---------- Agregar Tarjeta ----------
  $('#tarjetas-form').on('submit', function(e) {
      e.preventDefault();
      var f = $(this);

      console.log('id_tarjeta_editar', id_tarjeta_editar);

      //Se crea el objeto FormData y se le asigna el valor del formulario
      var formData = new FormData(document.getElementById("tarjetas-form"));

      console.log('formData', formData);

      if(id_tarjeta_editar){
          $.ajax({
           headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           method: "POST",
           url: 'update-tarjeta/'+id_tarjeta_editar,
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
         url: 'guardar-tarjeta',
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
  $('#tarjetas').on( 'click', '#btn-eliminar', function () {
    var data = table.row( $(this).parents('tr') ).data();
    id_tarjeta = data['id'];

    $("#tarjeta").html(data['clave']);

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

    $("#eliminar-tarjeta").on("click", function(){
      callback(true);
      $("#ModalEliminar").modal('hide');
    });
  };

  modalConfirm(function(confirm){
    if(confirm){
      $.ajax({
       type:'GET',
       url: 'delete-tarjeta/'+id_tarjeta
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



});
