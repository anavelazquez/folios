<!-- Tabla Edictos-->
<table id="oficios" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%; font-size: small;">
  <thead>
    <tr>
      <th class="centrar">Fecha</th>
      <th class="centrar">Dirigido</th>
      <th class="centrar">Seguimiento</th>
      <th class="centrar">Autor</th>
      <th class="centrar">Clave</th>
      <th class="centrar">Asunto</th>
      <th class="centrar">Observaciones</th>
      <th class="centrar">Cancelado</th>
      <th class="centrar">
        <button type="button" data-func="dt-add" class="btn btn-success btn-xs dt-add" id="btn-agregar">
          <i class='right fas fa-folder-plus'></i>
        </button>
      </th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th class="centrar">Fecha</th>
      <th class="centrar">Dirigido</th>
      <th class="centrar">Seguimiento</th>
      <th class="centrar">Autor</th>
      <th class="centrar">Clave</th>
      <th class="centrar">Asunto</th>
      <th class="centrar">Observaciones</th>
      <th class="centrar">Cancelado</th>
      <th></th>
    </tr>
  </tfoot>
</table>


@section('js')
  <script src="{{ asset('js/list-oficios.js') }}"></script>
@stop