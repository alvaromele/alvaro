<?php
cabecera($data);
mostrarModal('modalUsuarios', $data);
?>
<div id="contentAjax"></div>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fas fa-users"></i> <?= $data['page_titulo'] ?>
        <?php if ($_SESSION['permisosMod']['w']) { ?>
          <button class="btn btn-primary ml-2" type="button" onclick="abrirModal();"><i class="fas fa-plus-circle"></i> Nuevo</button>
        <?php } ?>
        <?php if ($_SESSION['permisosMod']['d']) { ?>
          <a href="<?= base_url(); ?>/usuarios/eliminados" class="btn btn-warning"><i class="fas fa-minus-circle"></i> Eliminados</a>
        <?php } ?>
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
            <table class="table table-hover table-bordered" id="tablaUsuarios" name="tablaUsuarios">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Identificacion</th>
                  <th>Nombre</th>
                  <th>Correo</th>
                  <th>Telefono</th>
                  <th>Usuario</th>
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