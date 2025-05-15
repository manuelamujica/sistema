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
                                        foreach ($registro as $item) {
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
            <!-- Modal Registro de Cuenta Contable -->
            <div class="modal fade" id="modalRegistrarCuenta" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <form id="formCuentaContable" method="post">
                        <div class="modal-header">
                        <h5 class="modal-title">Registrar Cuenta Contable</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                        <div class="form-group">
                            <select id="nivel" name="nivel" class="form-control">
                                <option value="">Seleccione nivel</option>
                                <option value="1">Nivel 1</option>
                                <option value="2">Nivel 2</option>
                                <option value="3">Nivel 3</option>
                                <option value="4">Nivel 4</option>
                                <option value="5">Nivel 5</option>
                                <!-- etc -->
                            </select>
                        </div>

                        <!-- Seleccionar cuenta padre (solo si es hija) -->
                        <div class="form-group" id="grupoCuentaPadre" style="display: none;">
                            <label>Cuenta Padre</label>
                            <select id="listaPadres" name="cuentaPadre" class="form-control">
                            </select>
                        </div>

                        <!-- Nombre de la cuenta -->
                        <div class="form-group">
                            <label>Nombre de la Cuenta</label>
                            <input type="text" class="form-control" id="nombreCuenta" name="nombreCuenta" required>
                        </div>

                        <!-- Código contable generado automáticamente -->
                        <div class="form-group">
                            <label>Código Contable</label>
                            <input type="text" class="form-control" id="codigoContable" name="codigoContable" readonly required>
                        </div>

                        <!-- Saldo -->
                        <div class="form-group">
                            <label for="saldo">Saldo Inicial</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="saldo" name="saldo" placeholder="Ej: 1500.00">
                        </div>


                        <!-- Naturaleza: visible solo si es cuenta padre -->
                        <div class="form-group" id="grupoNaturaleza">
                            <label>Naturaleza</label>
                            <select class="form-control" id="naturaleza" name="naturaleza" required>
                            <option value="">Seleccione</option>
                            <option value="deudora">Deudora</option>
                            <option value="acreedora">Acreedora</option>
                            </select>
                            <input type="hidden" id="naturalezaHidden" name="naturalezaHidden">
                        </div>

                        </div>
                        <div class="modal-footer">
                        <button type="submit" name="guardar" class="btn btn-primary">Registrar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>


        </div>
    </section> 
</div>

<?php if (isset($respuesta)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $respuesta["title"]; ?>',
            text: '<?php echo $respuesta["message"]; ?>',
            icon: '<?php echo $respuesta["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'catalogocuentas';
            }
        });
    </script>
<?php endif; ?>
<script src="vista/dist/js/modulos-js/catalogocuentas.js"></script>
