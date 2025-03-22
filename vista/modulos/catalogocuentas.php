<?php 
require_once "controlador/catalogocuentas.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Catálogo de cuentas</h1>
                    <p>En esta sección se pueden registrar las cuentas contables.</p>
                </div>
            </div>
        </div>
    </section>

<!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalRegistrarCuenta">
                                Registrar Cuenta Contable
                            </button>
                            <div class="d-flex">
                                <!-- Filtro por Tipo (FALTA FOREACH) -->
                                <select id="filtroTipo" class="form-control mr-2">
                                    <option value="">Filtrar por Tipo</option>
                                    <?php
                                    foreach($consulta as $t):
                                            if ($t['nivel'] == 1 && $t['cuenta_padre_id'] === NULL): ?>
                                                <option value="<?php echo $t['codigo_contable']; ?>">
                                                    <?php echo $t['nombre_cuenta']; ?>
                                                </option>
                                        <?php endif; endforeach; ?>
                                </select>
                                <!-- Filtro por Clase (Se llena dinámicamente) -->
                                <select id="filtroClase" class="form-control">
                                    <option value="">Filtrar por Clase</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código contable</th>
                                            <th>Nombre</th>
                                            <th>Naturaleza</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($consulta as $item) {
                                        ?>
                                        <tr>
                                            <td><?php echo $item['codigo_contable']; ?></td>
                                            <td><?php echo $item['nombre_cuenta']; ?></td>
                                            <td><?php echo $item['naturaleza']; ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-warning" data-toggle="modal" data-target="#modalEditarCuenta">Editar</button>
                                                    <button class="btn btn-danger">Eliminar</button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
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
