<!-- Modal -->
<div class="modal fade" id="modalFormUnidades" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nueva Categoría</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formUnidad" name="formUnidad" class="form-horizontal">
          <input type="hidden" id="idUnidad" name="idUnidad" value="">
          <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group col-md-5">
                <label for="unidad">Unidad<span class="required">*</span></label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text"><i class="fab fa-uniregistry"></i></span></div>
                  <input class="form-control valid validText" id="unidad" name="unidad" type="text" placeholder="Unidad" required="">
                  <!-- <div class="form-control-feedback invalid-feedback">El nombre debe tener entre 4 y 50 letras no puede con contener numeros.</div> -->
                </div>
              </div>

              <div class="form-group col-md-11">
                <label for="txtNombre">Descipción<span class="required">*</span></label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text"><i class=" fas fa-scroll"></i></span></div>
                  <input class="form-control" id="descripcion" name="descripcion" rows="2" placeholder="Descripción de la unidad"></input>
                  <!-- <div class="form-control-feedback invalid-feedback">El nombre debe tener entre 4 y 50 letras no puede con contener numeros.</div> -->
                </div>
              </div>
            </div>
          </div>
          <hr>
          <div class="form-row justify-content-center ">
            <div class="tile-footer">
              <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;
              <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalVerDatos" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Datos de la categoría</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>ID:</td>
              <td id="celId"></td>
            </tr>
            <tr>
              <td>Unidad:</td>
              <td id="celNombre"></td>
            </tr>
            <tr>
              <td>Descripción:</td>
              <td id="celDescripcion"></td>
            </tr>
            <tr>
              <td>Estado:</td>
              <td id="celEstado"></td>
            </tr>
            <!-- <tr>
              <td>Foto:</td>
              <td id="imgCategoria"></td>
            </tr> -->
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>