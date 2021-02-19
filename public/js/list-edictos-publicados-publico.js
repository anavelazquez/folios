//Se declara idfirmante de manera global
var id_edicto;
//Se declara el arreglo vacío de los periodos de publicacion
var periodos_publicacion = [];
var dias_publicacion = [];

$(document).ready(function () {

  var table = $('#edictos-table').DataTable( {
    "responsive": true,
    "columnDefs": [
      {
        "targets": [0,1,2,3,4,5,6,7,8],
        "className": "dt-center"
      },
      {
        "targets": [0,2,3,4,5,6,7],
        class: "wrap"
      }
    ],
    "order": [[ 1, 'asc' ]],
    "ordering": false,
    "serverSide": true,

    "ajax": {
      "url": "/edictoslistapublicada",
      "dataSrc": "data"
    },
    "columns": [
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
        {
          "data":0,
          "defaultContent": "<button type='button' class='btn btn-primary btn-sm' id='btn-mostrar-publicaciones' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Mostrar Publicaciones'><i class='right fas fa-calendar'></i></button>"
        },
        {
          "data":null,
          "render": function (data, type, row){
            return row.numero_expediente+'/'+row.anio_expediente;
          }
        },
        {
          "data":null,
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
          "defaultContent": "<button type='button' class='btn btn-success btn-sm' id='btn-consultar' style='margin-right: 10px' data-toggle='tooltip' data-placement='top' title='Consultar Edicto'><i class='right fas fa-file'></i></button>"
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

  $('#edictos-table').on( 'click', '#btn-mostrar-publicaciones', function () {
    var data = table.row( $(this).parents('tr') ).data();
    //console.log('data', data);
    //Se vacía el arreglo
    dias_publicacion = [];
    var tipo_publicacion = data['id_tipo_periodo_publicacion'];

    periodos_publicacion = data['periodos_publicacion'];
    //console.log('periodos_publicacion', periodos_publicacion);
    //console.log('cuantos periodos', periodos_publicacion.length);
    if( tipo_publicacion == 'D'){
      for (i = 0; i < periodos_publicacion.length; ++i) {
          //periodos_publicacion2.push({fecha_inicio: periodos_publicacion[i].fecha_inicio_publicacion, fecha_fin: periodos_publicacion[i].fecha_fin_publicacion});

          console.log('El periodo es Definido');
          desde = moment(periodos_publicacion[i].fecha_inicio_publicacion);
          hasta = moment(periodos_publicacion[i].fecha_fin_publicacion);

          dias = diasEntreFechas(desde, hasta);
          //console.log('dias', dias);
          for (j = 0; j < dias.length; ++j) {
            //dias_publicacion.push({ dia: dias[j]['dia'], estado: dias[j]['estado']});
            dias_publicacion.unshift({ dia: dias[j]['dia'], estado: dias[j]['estado']});

          }
      }
    }else{
      //console.log('El periodo es Indefinido');
      desde = moment(data['fecha_publicacion']);
      hasta = moment();

      dias = diasEntreFechas(desde, hasta);
      //console.log('dias', dias);
      for (j = 0; j < dias.length; ++j) {
        //dias_publicacion.push({ dia: dias[j]['dia'], estado: dias[j]['estado']});
        dias_publicacion.unshift({ dia: dias[j]['dia'], estado: dias[j]['estado']});
      }
    }
    //console.log('dias_publicacion', dias_publicacion);
    $("#ModalPublicaciones").modal('show');
    genera_tabla_dias(dias_publicacion, tipo_publicacion);
    $('#tabla-publicaciones').DataTable({
      "responsive": true,
      "bFilter": false,
      "ordering": false,
      //"sDom": '<"top"i>rt<"bottom"flp><"clear">',
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
  });

  //Mostrar Edicto
  $('#edictos-table').on( 'click', '#btn-consultar', function () {
    var data = table.row( $(this).parents('tr') ).data();
    ruta_archivo = data['ruta_archivo'];
    window.open(ruta_archivo, '_blank');
  });

  function diasEntreFechas(desde, hasta) {
      var hoy = moment();
      var dia_actual = desde;
      var fechas = [];
      var estado;
      while (dia_actual.isSameOrBefore(hasta)) {
        if(dia_actual.isSameOrBefore(hoy)){
          estado="Publicado";
        }else{
          estado="Por publicar";
        }
        fechas.push({ dia: dia_actual.format('DD/MM/YYYY'), estado: estado});
        dia_actual.add(1, 'days');
      }
      return fechas;
  };

  function genera_tabla_dias(dias_publicacion, tipo_publicacion) {
    var stritem = "";
    // Obtener la referencia del elemento table
    var tblBody = document.getElementById("tbody_periodos_publicacion");

    stritem += "<table class='table table-striped table-bordered' id='tabla-publicaciones'>";
    stritem += "<thead><tr><th scope='col'>Fecha</th><th scope='col'>Estado</th></tr></thead>";

    for (i = 0; i < dias_publicacion.length; ++i) {
      //periodos_publicacion.push(output.data[i].fk_id_cita);
      stritem += "<tr>";
      stritem += "<td>"+dias_publicacion[i].dia+"</td>";
      stritem += "<td>"+dias_publicacion[i].estado+"</td>";
      stritem += "</tr>";
    }
    stritem += "</table>";

    $('#tabla-publicaciones').DataTable();

    if( tipo_publicacion == 'I'){
      stritem += "<br><p style='width: 90%; margin: 5px auto'><b>*La publicación estará por tiempo Indefinido</b></p>";
    }

    $("#contenido-modal").html(stritem);
  };

  $('#edictos_sentencias li').on('click', function (e) {
    e.preventDefault();
    //$(this).tab('show');
    //console.log($(this).tab()[0]['id']);
    let id_tab = $(this).tab()[0]['id'];
    if(id_tab == 'tab-sentencias'){
      $("#tab-sentencias").addClass("active");
      $("#tab-edictos").removeClass("active");
      $("#footer").removeClass("site-footer-relative");
      //$(this).addClass("active");
    }else if(id_tab == 'tab-edictos'){
      $("#footer").addClass("site-footer-relative");
      $("#tab-sentencias").removeClass("active");
      $("#tab-edictos").addClass("active");
    }
  });


















});
