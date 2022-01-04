<?php
cabecera($data);
// mostrarModal('modalProductos', $data);
?>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fas fa-align-justify mr-2"></i> <?= $data['page_title'] ?>

      </h1>
    </div>

    <div class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/productos"><?= $data['page_title'] ?></a></li>
    </div>
  </div>
  <div class="row">

    <div class="col-md-12">
      <div class="tile">
        <div class="tile-body">
          <div class="table-responsive">
            <table class="table table-hover table-bordered" id="tablaExistencias">
              <thead>
                <tr>
                  <th>ID</th>
                  <!-- <th>ID Producto</th> -->
                  <th>Código</th>
                  <th>Nombre</th>
                  <th>Unidad</th>
                  <th>Cantidad</th>
                  <th>Total</th>
                  <!-- <th>Estado</th> -->
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
</main>
<?php pie($data); ?>