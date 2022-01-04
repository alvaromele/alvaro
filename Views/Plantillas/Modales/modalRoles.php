<!-- Modal -->
<div class="modal fade" id="modalRol" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header registrar">
        <h5 class="modal-title" id="titleModal">Nuevo Rol</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="tile">
            <div class="tile-body">
              <form id="formRol" name="formRol">
                 <input type="hidden" id="idRol" name="idRol" value="">
                  <div class="form-group col-md-12">
                      <label for="nombre">Nombre</label>
                      <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-scroll"></i></span></div>
                          <input class="form-control valid validText"  id="nombre" name="nombre" type="text" placeholder="Nombre del rol" required="" >
                          <div class="form-control-feedback invalid-feedback">El nombre debe tener entre 4 y 50 letras no puede con contener numeros.</div>
                      </div>        

                  </div>
                <div class="form-group col-md-12">
                    
                          <label for="txtNombre">Descripción<span class="required">*</span></label>
                          <div class="input-group">
                              <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-scroll"></i></span></div>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="2" placeholder="Descripción del rol" required=""></textarea>
                              <div class="form-control-feedback invalid-feedback">El nombre debe tener entre 4 y 50 letras no puede con contener numeros.</div>
                          </div>        
                       
                </div>

                <div class="form-group col-md-12">
               
                    <label for="exampleSelect1">Estado</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text input1"><i class=" fas fa-battery-half"></i></span></div> 
                        <select class="form-control" id="listaEstado" name="listaEstado" required=""><i class=" fas fa-battery-half"></i>
                          <option value="1">Activo</option>
                          <option value="2">Inactivo</option>
                        </select>
                    </div>  
                  
                </div>
                <div class="tile-footer">
                  <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#" data-dismiss="modal" ><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
                </div>
              </form>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

