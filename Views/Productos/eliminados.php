<?php
cabecera($data);
mostrarModal('modalUsuarios', $data);
$productos = $data['productos']
?>
<main class="app-content">
    <div class="app-title">
        <div>

            <h1><i class="fas fa-users"></i> <?= $data['page_title'] ?>

                <?php if ($_SESSION['permisosMod']['d']) { ?>
                    <a href="productos" class="btn btn-primary ml-2"><i class="fas fa-arrow-alt-circle-left"></i> Productos</a>
                <?php } ?>
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/productos"><?= $data['page_tag'] ?></a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tablaProductEliminados">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Unidad</th>

                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos as $key => $u) : ?>
                                    <tr>
                                        <td><?= $u['idproducto'] ?></td>
                                        <td><?= $u['codigo'] ?></td>
                                        <td><?= $u['nombre'] ?></td>
                                        <td><?= $u['unidad'] ?></td>

                                        <td>
                                            <div class="text-center">

                                                <button onClick="funcActivarProducto(<?= $u['idproducto'] ?>)" title="Acivar Usuario" class="btn btn-primary btn-sm"><i class="fa fa-arrow-alt-circle-up"></i></button>

                                                <!-- <a href="<?= base_url(); ?>/productos/reingresar" class="btn btn-primary btn-sm" title="Acivar Usuario"><i class="fa fa-arrow-alt-circle-up"></i></a>
                        <button class="btn btn-primary  btn-sm" onClick="fntIngresarUsuario(<?= $u['idusuario'] ?>)" title="Editar usuario"><i class="fa fa-arrow-alt-circle-up"></i></button> -->

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php pie($data); ?>