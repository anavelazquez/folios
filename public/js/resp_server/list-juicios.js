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

  }else{
    //console.log('no hay errores');

    $("#materia").removeClass("is-invalid");
    $("#invalid-feedback-materia").fadeOut();

    $("#juicio").removeClass("is-invalid");
    $("#invalid-feedback-juicio").fadeOut();
  }
}


//Se declara idjuicio de manera global
var id_juicio;
var id_juicio_editar;


$(document).ready(function () {

  var table = $("#juicios").DataTable({
      "responsive": true,
      "order": [[ 1, 'asc' ]],
      "ordering": false,
      "serverSide": true,
      "ajax": {
            "url": "/edictos/juicioslista",
            "dataSrc": "data"
            },
      "columns": [
          { "data": "materia.materia"},
          { "data": "juicio"},
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
            "defaultContent": "<button type='button' class='btn btn-primary btn-sm' id='btn-editar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Editar Juicio'><i class='right fas fa-edit'></i></button>  <button type='button' class='btn btn-danger btn-sm' id='btn-eliminar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Eliminar Juicio'><i class='right fas fa-trash-alt'></i></button>"
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
  $('#juicios').on( 'click', '#btn-eliminar', function () {
    var data = table.row( $(this).parents('tr') ).data();
    id_juicio = data['id_juicio'];

    $("#juicio-descripcion").html(data['juicio']);

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

    $("#eliminar-juicio").on("click", function(){
      callback(true);
      $("#ModalEliminar").modal('hide');
    });
  };

  modalConfirm(function(confirm){
    if(confirm){
      $.ajax({
       type:'GET',
       url: '/edictos/delete-juicio/'+id_juicio
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
  $('#juicios').on( 'click', '#btn-agregar', function () {
    //se limpia la variable
    id_juicio_editar = null;

    $("#juicios-form")[0].reset();
    //Se vuelve a llamar la función para quitar los errores anteriores en el formulario
    validarFormulario();
    //VerificarArchivo();
    $("#ModalEditar").modal('show');
    //Se pone el titulo correspondiente a la modal
    $("#modal-label").html('Agregar Juicio');
    //Se pone la etiqueta correspondiente al botón
    $("#editar-juicio").html('Agregar Juicio');
    //Se oculta el bloque estatus
    $("#bloque-estatus").css("display", "none");
  });


  // ---------- Modal Editar ----------
  $('#juicios').on( 'click', '#btn-editar', function () {

    var data = table.row( $(this).parents('tr') ).data();
    id_juicio_editar = data['id_juicio'];
    console.log('data', data);

    //Cambiar etiqueta titulo modal a Editar
    var boton = document.getElementById('modal-label');
    boton.innerText = "Editar Juicio";

    //Cambiar etiqueta del boton a Editar
    var boton = document.getElementById('editar-juicio');
    boton.innerText = "Editar";

    $("#ModalEditar").modal('show');
    $("#materia").val(data['id_materia']);
    $("#juicio").val(data['juicio']);
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


  // ---------- Agregar Juicio ----------
  $('#juicios-form').on('submit', function(e) {
      e.preventDefault();
      var f = $(this);
      console.log('id_juicio_editar', id_juicio_editar);

      //Se crea el objeto FormData y se le asigna el valor del formulario
      var formData = new FormData(document.getElementById("juicios-form"));

      if(id_juicio_editar){
          $.ajax({
           headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           method: "POST",
           url: '/edictos/update-juicio/'+id_juicio_editar,
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
         url: '/edictos/guardar-juicio',
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
