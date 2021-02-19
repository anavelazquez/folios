//Se inicializa el mensaje de confirmación
const Toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 4000
});


function validarFormulario(msg){
  if(msg){
    console.log(msg);
    //
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
  }
}

//Se declara idprestacion de manera global
var id_prestacion_demandada;
var id_prestacion_demandada_editar;


$(document).ready(function () {

  var table = $("#prestaciones").DataTable({
      "responsive": true,
      "order": [[ 1, 'asc' ]],
      "ordering": false,
      "serverSide": true,
      "ajax": {
            "url": "/prestacioneslista",
            "dataSrc": "data"
            },
      "columns": [
          { "data": "prestacion_demandada"},
          {
            "data":"estatus",
            "render": function (data){
              if(data == 1){
                return 'Activo';
              }else{
                return 'Inactivo';
              }
            }
          },
          {
            "data":0,
            "defaultContent": "<button type='button' class='btn btn-primary btn-sm' id='btn-editar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Editar Prestación Demandada'><i class='right fas fa-edit'></i></button>  <button type='button' class='btn btn-danger btn-sm' id='btn-eliminar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Eliminar Prestación Demandada'><i class='right fas fa-trash-alt'></i></button>"
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


    // ---------- Modal Confirmar Eliminar ----------
  $('#prestaciones').on( 'click', '#btn-eliminar', function () {
    var data = table.row( $(this).parents('tr') ).data();
    id_prestacion_demandada = data['id_prestacion_demandada'];

    $("#prestacion").html(data['prestacion_demandada']);

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

    $("#eliminar-prestacion").on("click", function(){
      callback(true);
      $("#ModalEliminar").modal('hide');
    });
  };

  modalConfirm(function(confirm){
    if(confirm){
      $.ajax({
       type:'GET',
       url: '/delete-prestacion/'+id_prestacion_demandada
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
  $('#prestaciones').on( 'click', '#btn-agregar', function () {
    //se limpia la variable
    id_prestacion_demandada_editar = null;

    $("#prestaciones-form")[0].reset();
    //Se vuelve a llamar la función para quitar los errores anteriores en el formulario
    validarFormulario();
    //VerificarArchivo();
    $("#ModalEditar").modal('show');
    //Se pone el titulo correspondiente a la modal
    $("#modal-label").html('Agregar Prestación Demandada');
    //Se pone la etiqueta correspondiente al botón
    $("#editar-prestacion").html('Agregar Prestación');
    //Se oculta el bloque estatus
    $("#bloque-estatus").css("display", "none");
  });


  // ---------- Modal Editar ----------
  $('#prestaciones').on( 'click', '#btn-editar', function () {

    var data = table.row( $(this).parents('tr') ).data();
    id_prestacion_demandada_editar = data['id_prestacion_demandada'];
    console.log('data', data);

    //Cambiar etiqueta titulo modal a Editar
    var boton = document.getElementById('modal-label');
    boton.innerText = "Editar Prestación Demandada";

    //Cambiar etiqueta del boton a Editar
    var boton = document.getElementById('editar-prestacion');
    boton.innerText = "Editar";

    $("#ModalEditar").modal('show');
    $("#prestacion_demandada").val(data['prestacion_demandada']);
    if(data['estatus'] == 1){
      console.log('activo');
      document.getElementById("activo").checked = true;
    }else{
      console.log('inactivo');
      document.getElementById("inactivo").checked = true;
      //$("#periodoDefinido").click();
    }

    //Se muestra el bloque estatus
    $("#bloque-estatus").css("display", "flex");

  });


  // ---------- Agregar Prestación Demandada ----------
  $('#prestaciones-form').on('submit', function(e) {
      e.preventDefault();
      var f = $(this);
      console.log('id_prestacion_demandada_editar', id_prestacion_demandada_editar);

      //Se crea el objeto FormData y se le asigna el valor del formulario
      var formData = new FormData(document.getElementById("prestaciones-form"));

      if(id_prestacion_demandada_editar){
          $.ajax({
           headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           method: "POST",
           url: '/update-prestacion/'+id_prestacion_demandada_editar,
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
         url: '/guardar-prestacion-demandada',
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

});
