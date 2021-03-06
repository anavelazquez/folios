<!-- Tabla Circulares-->
<table id="circulares" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%; font-size: small;">
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
          <i class='right fas fa-plus'></i>
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

<!-- Modal Confirmación Eliminar -->
<div class="modal fade" id="ModalEliminar" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Eliminar Circular</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        ¿Está seguro que desea eliminar el circular <span id="circular"></span>?
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-danger" id="eliminar-circular">Eliminar</button>
      </div>

    </div>
  </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="ModalEditar" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-label">Editar Circular</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="circulares-form" class="form-horizontal needs-validation" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="modal-body" id="attachment-body-content">
            <div class="container col-md-12">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="no-mr-btm" for="dirigido">Dirigido</label>
                    <input type="text" name="dirigido" class="form-control mayusculas" id="dirigido">
                    <div class="invalid-feedback" id="invalid-feedback-dirigido" style="display: none"></div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label class="no-mr-btm" for="seguimiento">Seguimiento</label>
                    <input type="text" name="seguimiento" class="form-control mayusculas" id="seguimiento">
                    <div class="invalid-feedback" id="invalid-feedback-seguimiento" style="display: none"></div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label class="no-mr-btm" for="asunto">Asunto</label>
                    <input type="text" name="asunto" class="form-control mayusculas" id="asunto">
                    <div class="invalid-feedback" id="invalid-feedback-asunto" style="display: none"></div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label class="no-mr-btm" for="observaciones">Observaciones</label>
                    <input type="text" name="observaciones" class="form-control mayusculas" id="observaciones">
                    <div class="invalid-feedback" id="invalid-feedback-observaciones" style="display: none"></div>
                  </div>
                </div>

                <div class="col-md-12">
                  <label>Cancelado</label>
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="cancelado" id="canceladoNo" value="No" checked>
                        <label class="form-check-label" for="canceladoNo">
                          No
                        </label>
                      </div>
                    </div>

                    <div class="col-md-2">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="cancelado" id="canceladoSi" value="Si">
                        <label class="form-check-label" for="canceladoSi">
                          Si
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="invalid-feedback" id="invalid-feedback-cancelado" style="display: none"></div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success" id="editar-circular">Agregar</button>
          </div>
        </form>
    </div>
  </div>
</div>
<!-- /Modal Editar-->


@section('js')
  <script src="{{ asset('js/list-circulares.js') }}"></script>
@stop