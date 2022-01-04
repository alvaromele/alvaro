<!-- Modal -->
<div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" >
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
                <div class="form-row">    
                      <div class="form-group col-md-4">
                          <div class="form-group icon icon-rol col-12">
                            <label for="identificacion">Identificación <samp class="required">*</samp></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-id-card-alt"></i></span>
                                </div>
                                <input class="form-control valid validId" id="identificacion" name="identificacion" type="text" onkeypress="return controlTag(event)" >
                                <div class="form-control-feedback invalid-feedback">La identificacion tiene que ser de 4 a 10 digitos no puede con contener letras.</div>
                            </div>
                          </div> 
                      </div>
                      <div class="form-group col-md-4">
                          <div class="form-group  col-12">
                            <label for="nombre">Nombres <samp class="required">*</samp></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-user"></i></span></div>
                                <input class="form-control valid validText"  id="nombre" name="nombre" type="text" >
                               
                            </div>        
                          </div> 
                      </div>
                      <div class="form-group col-md-4">
                          <div class="form-group icon icon-rol col-12">
                            <label for="apellido">Apellidos <samp class="required">*</samp></label>
                            <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-user"></i></span></div>
                                <input class="form-control valid validText" id="apellido" name="apellido" type="text" >
                                <div class="form-control-feedback invalid-feedback">El apellido debe tener entre 4 y 50 letras no puede con contener numeros.</div>
                            </div>
                          </div> 
                      </div>
                </div>
                <div class="form-row">    
                      <div class="form-group col-md-4">
                          <div class="form-group icon icon-rol col-12">
                            <label for="email">Correo <samp class="required">*</samp></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-envelope-open-text"></i></span></div>
                                <input class="form-control valid validEmail" id="email" name="email" type="email" >
                                <div class="form-control-feedback invalid-feedback">Debe ingresar un correo valido.</div>
                            </div>
                          </div> 
                      </div>
                      <!-- <div class="form-group col-md-4">
                          <div class="form-group  col-12">
                            <label for="telefono">Telefono <samp class="required">*</samp></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-phone-volume"></i></span></div>
                                <input class="form-control valid validNumber" id="telefono" name="telefono" type="text" onkeypress="return controlTag(event)" >
                                <div class="form-control-feedback invalid-feedback">El telefono tiene que ser de 10 a 50 digitos no puede con contener letras.</div>   
                            </div>
                          </div> 
                      </div> -->
                 
                     <div class="form-group col-md-4">  
                            <label for="listRolid">Rol de usuario <samp class="required">*</samp></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text input1"><i class=" fa fa-scroll"></i></span>
                                </div>
                               <select class="form-control" data-live-search="true" id="listaRolid" name="listaRolid" required >
                    </select>                                     
                            </div>
                    </div> 
                      <div class="form-group icon icon-rol col-4">
                        <label for="listRolid">Estado <samp class="required">*</samp></label>
                         
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text input1"><i class=" fa fa-scroll"></i></span>
                            </div>
                            <select class="form-control" data-live-search="true" name="listaEstado" id="listaEstado">
                               <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>                                      
                        </div>
                    </div> 
                </div>
                
                <div class="form-row">    
                    <div class="form-group col-md-4">
                        <div class="form-group icon icon-rol col-12">
                          <label for="password">Contraseña <samp class="required">*</samp></label>
                          <div class="input-group">
                              <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-lock-open"></i></span></div>
                              <input class="form-control"   autocomplete="off" id="password" name="password" type="text" >
                          </div>
                        </div> 
                    </div>
                    <div class="form-group col-md-4">
                        <div class="form-group icon icon-rol col-12">
                          <label for="password1">Repetir Contraseña <samp class="required">*</samp></label>
                          <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-lock-open"></i></span></div>
                              <input class="form-control" autocomplete="off" id="password1" name="password1" type="text" >
                              <div class="form-control-feedback invalid-feedback">El nombre debe tener entre 4 y 50 letras no puede con contener numeros.</div>
                          </div>
                        </div> 
                    </div>
                 
                     <!-- <div class="form-group icon icon-rol col-4">
                        <label for="listRolid">Estado <samp class="required">*</samp></label>
                         
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text input1"><i class=" fa fa-scroll"></i></span>
                            </div>
                            <select class="form-control" data-live-search="true" name="listaEstado" id="listaEstado">
                               <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>                                      
                        </div>
                    </div>  -->
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
  <div class="modal-dialog" >
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
            <tr>
              <td>Identificación:</td>
              <td id="celIdentificacion">654654654</td>
            </tr>
            <tr>
              <td>Nombres:</td>
              <td id="celNombre">Jacob</td>
            </tr>
            <tr>
              <td>Apellidos:</td>
              <td id="celApellido">Jacob</td>
            </tr>
            <tr>
              <td>Teléfono:</td>
              <td id="celTelefono">Larry</td>
            </tr>
            <tr>
              <td>Email (Usuario):</td>
              <td id="celEmail">Larry</td>
            </tr>
            <tr>
              <td>Tipo Usuario:</td>
              <td id="celTipoUsuario">Larry</td>
            </tr>
            <tr>
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

