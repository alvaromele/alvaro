<!-- Modal -->
<div class="modal fade" id="modalProveedor" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header registrar">
        <h5 class="modal-title" id="titleModal">Nuevo Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="tile">

          <form id="formUsuario" name="formUsuario" class="form-horizontal">
            <input type="hidden" id="idUsuario" name="idUsuario" value="">
            <p class="text-primary">Los campos con asterisco (<samp class="required">*</samp>) son obligatorios.</p>
            <p class="text-primary text-center" style="font-size: 20px"><i class="fas fa-pen-alt"></i> &nbsp; Datos de la empresa</p>
            <hr>
            <div class="form-row">
              <div class="form-group col-md-4">
                <div class="form-group icon icon-rol col-12">
                  <label for="txtIdentificacion">Identificación (nit/cc) <samp class="required">*</samp></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-id-card-alt"></i></span>
                    </div>
                    <input class="form-control valid validId" id="txtIdentificacion" name="txtIdentificacion" type="text" onkeypress="return controlTag(event)">
                    <div class="form-control-feedback invalid-feedback">La identificacion tiene que ser de 4 a 10 digitos no puede con contener letras.</div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-8">
                <div class="form-group icon icon-rol col-12">
                  <label for="txtNombre">Nombre <samp class="required">*</samp></label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-user"></i></span></div>
                    <input class="form-control valid validText" id="txtNombre" name="txtNombre" type="text">
                    <div class="form-control-feedback invalid-feedback">El nombre debe tener entre 4 y 50 letras no puede con contener numeros.</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <div class="form-group  col-12">
                  <label for="txtDireccion">Dirección </label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-phone-volume"></i></span></div>
                    <input class="form-control valid validNumber" id="txtDireccion" name="txtDireccion" type="text">
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4">
                <div class="form-group icon icon-rol col-12">
                  <label for="txtEmail">Correo <samp class="required">*</samp></label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-envelope-open-text"></i></span></div>
                    <input class="form-control valid validEmail" id="txtEmail" name="txtEmail" type="email">
                    <div class="form-control-feedback invalid-feedback">Debe ingresar un correo valido.</div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4">
                <div class="form-group  col-12">
                  <label for="txtTelefono">Telefono <samp class="required">*</samp></label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-phone-volume"></i></span></div>
                    <input class="form-control valid validNumber" id="txtTelefono" name="txtTelefono" type="text" onkeypress="return controlTag(event)">
                    <div class="form-control-feedback invalid-feedback">El telefono tiene que ser de 10 a 50 digitos no puede con contener letras.</div>
                  </div>
                </div>
              </div>
            </div>
            <p class="text-primary text-center" style="font-size: 20px"><i class="fas fa-pen-alt"></i> &nbsp; Datos de contacto</p>
            <hr>
            <div class="form-row">
              <div class="form-group col-md-4">
                <div class="form-group icon icon-rol col-12">
                  <label for="txtNombreCont">Nombre </label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-lock-open"></i></span></div>
                    <input class="form-control" autocomplete="off" id="txtNombreCont" name="txtNombreCont" type="text">
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4">
                <div class="form-group icon icon-rol col-12">
                  <label for="txtEmailCont">Correo </label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-envelope-open-text"></i></span></div>
                    <input class="form-control valid validEmail" id="txtEmailCont" name="txtEmailCont" type="email">
                    <div class="form-control-feedback invalid-feedback">Debe ingresar un correo valido.</div>
                  </div>
                </div>
              </div>

              <div class="form-group col-md-4">
                <div class="form-group  col-12">
                  <label for="txtTelefonoCont">Telefono </label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-phone-volume"></i></span></div>
                    <input class="form-control valid validNumber" id="txtTelefonoCont" name="txtTelefonoCont" type="text" onkeypress="return controlTag(event)">
                    <div class="form-control-feedback invalid-feedback">El telefono tiene que ser de 10 a 50 digitos no puede con contener letras.</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-row justify-content-center ">
              <div class="title-footer ">
                <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="far fa-save fa-lg mr-1"></i></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;
                <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle "></i>Cerrar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalViewUser" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Datos del usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <p class="text-primary text-center" style="font-size: 20px"><i class="fas fa-pen-alt"></i> &nbsp; Datos de la empresa</p>
            <tr>
              <td>Identificación:</td>
              <td id="celIdentificacion">654654654</td>
            </tr>
            <tr>
              <td>Nombre:</td>
              <td id="celNombre">Jacob</td>
            </tr>
            <tr>
              <td>Email :</td>
              <td id="celEmail">Jacob</td>
            </tr>
            <tr>
              <td>Teléfono:</td>
              <td id="celTelefono">Larry</td>
            </tr>
            <tr>
              <td>Dirección:</td>
              <td id="celDireccion">Larry</td>
            </tr>
            <tr>
              <td colspan="2">
                <p class="text-primary text-center" style="font-size: 20px"><i class="fas fa-pen-alt"></i> &nbsp; Datos del Contacto</p>
              </td>
            </tr>

            <tr>
              <td>Contacto:</td>
              <td id="celContacto">Larry</td>
            </tr>
            <tr>
              <td>Email:</td>
              <td id="celEmailContacto">Larry</td>
            </tr>
            <tr>
              <td>Telefono:</td>
              <td id="celTelefonoContacto">Larry</td>
            </tr>
            <tr>
            <tr>
              <td colspan="2">
                <p class="text-primary text-center" style="font-size: 20px"><i class="fas fa-pen-alt"></i> &nbsp; Otros Datos</p>
              </td>
            </tr>
            <td>Estado:</td>
            <td id="celEstado">Larry</td>
            </tr>
            <tr>
              <td>Fecha registro:</td>
              <td id="celFechaRegistro">Larry</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>