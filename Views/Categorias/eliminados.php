<?php
cabecera($data);
mostrarModal('modalUsuarios', $data);
$categorias = $data['categorias']
?>
<main class="app-content">
    <div class="app-title">
        <div>

            <h1><i class="fas fa-users"></i> <?= $data['page_title'] ?>

                <?php if ($_SESSION['permisosMod']['d']) { ?>
                    <a href="categorias" class="btn btn-primary ml-2"><i class="fas fa-arrow-alt-circle-left"></i> Categorias</a>
                <?php } ?>
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/categorias"><?= $data['page_etiqueta'] ?></a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tablaUserEliminados">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <!-- <th>Email</th> -->

                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categorias as $key => $c) : ?>
                                    <tr>
                                        <td><?= $c['idcategoria'] ?></td>
                                        <td><?= $c['nombre'] ?></td>
                                        <td><?= $c['descripcion'] ?></td>
                                        <!-- <td><?= $c['email_user'] ?></td> -->

                                        <td>
                                            <div class="text-center">

                                                <button onClick="funcActivarCategoria(<?= $c['idcategoria'] ?>)" title="Acivar Usuario" class="btn btn-primary btn-sm"><i class="fa fa-arrow-alt-circle-up"></i></button>

                                                <!-- <a href="<?= base_url(); ?>/usuarios/reingresar" class="btn btn-primary btn-sm" title="Acivar Usuario"><i class="fa fa-arrow-alt-circle-up"></i></a>
                        <button class="btn btn-primary  btn-sm" onClick="fntIngresarUsuario(<?= $c['idusuario'] ?>)" title="Editar usuario"><i class="fa fa-arrow-alt-circle-up"></i></button> -->

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