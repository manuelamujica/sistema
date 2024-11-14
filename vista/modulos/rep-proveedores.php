<?php
require_once 'controlador/reportep.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Reporte De Proveedores </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Reporte De Proveedores</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs" id="tabContent" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="proveedores-tab" data-toggle="tab" href="#" role="tab">proveedores</a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">
                            <td>
                                <form action="index.php?pagina=proveedorespdf" method="post" target="_blank">
                                    <button class="btn btn-danger" name="pdf" alt="Generar pdf" id="pdf">PDF</button>
                                </form>


                            </td>
                            <!-- Tabla de proveedores -->
                            <div class="table-responsive">
                                <table id="proveedores" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Rif</th>
                                            <th>Razon social</th>
                                            <th>Correo electronico</th>
                                            <th>Dirección</th>
                                            <th>Telefonos</th>
                                            <th>Status</th>
                                            <th>Representante</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($registro as $datos) { ?>
                                            <?php if ($datos['status'] != 2): ?>
                                                <tr>
                                                    <td><?php echo $datos["cod_prov"] ?></td>
                                                    <td><?php echo $datos["rif"] ?></td>
                                                    <td><?php echo $datos["razon_social"] ?></td>
                                                    <td><?php echo !empty($datos["email"]) ? $datos["email"] : 'No disponible'; ?></td>
                                                    <td><?php echo !empty($datos["direccion"]) ? $datos["direccion"] : 'No disponible'; ?></td>
                                                    <td><?php echo !empty($datos["telefonos"]) ? $datos["telefonos"] : 'No disponible'; ?></td>

                                                    <td>
                                                        <?php if ($datos['status'] == 1): ?>
                                                            <span class="badge bg-success">Activo</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Inactivo</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo !empty($dato["nombre"]) ? $dato["nombre"] : 'No disponible'; ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>








</div>