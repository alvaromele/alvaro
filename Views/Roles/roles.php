<?php
cabecera($data);
mostrarModal('modalRoles', $data);
?>
<div id="contentAjax"></div>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="icon fas fa-scroll"></i> <?= $data['page_titulo'] ?>

        <button class="btn btn-primary ml-3" type="button" onclick="abrirModal();"><i class="fas fa-user-plus"></i> Nuevo</button>

      </h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="icon fas fa-scroll"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/roles "><?= $data['page_titulo'] ?></a></li>
    </ul>
  </div>
  <!-- Data tables -->
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <div class="tile-body">
          <div class="table-responsive">
            <table class="table table-hover table-bordered" id="tablaRoles" name="tablaRoles">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Descripción</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin Data Tables -->
</main>
<?php pie($data); ?>