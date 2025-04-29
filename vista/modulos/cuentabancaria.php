<?php 
#Requerir al controlador
require_once "controlador/cuentabancaria.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cuentas Bancarias</h1>
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
                        <div class="card-header">
                            <!-- Botón para ventana modal -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarusuario">Registrar usuario</button>
                        </div>
                        <div class="card-body">
                        <div class="table-responsive">
                            <table id="cuenta_bancaria" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Banco</th>
                                        <th>Tipo de Cuenta</th>
                                        <th>Numero de Cuenta</th>
                                        <th>Saldo</th>
                                        <th>Acciones</th>
                                    </tr>         
                                </thead>
                                <tbody>
                                <!-- Tabla con los datos que se muestren dinamicamente -->
                                    <?php foreach ($registro as $cuenta_bancaria){
                                            if(1==1):?>
                                        <tr>
                                            <td> <?php echo $cuenta_bancaria["cod_cuenta_bancaria"] ?></td>
                                            <td> <?php echo $cuenta_bancaria["nombre_banco"] ?></td>
                                            <td> <?php echo $cuenta_bancaria["numero_cuenta"] ?></td>
                                            <td> <?php echo $cuenta_bancaria["saldo"] ?></td>
                                            <td> <?php echo $cuenta_bancaria["status"] ?></td>
                                            <!-- Botones -->
                                            <td>
                                                <button name="editar" title="Editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#editModal"
                                                data-codigo="<?php echo $cuenta_bancaria["cod_cuenta_bancaria"];?>"
                                                data-nombre="<?php echo $cuenta_bancaria["numero_cuenta"];?>"
                                                data-user="<?php echo $cuenta_bancaria["nombre_banco"];?>"
                                               
                                                data-rol="<?php echo $cuenta_bancaria["nombre_banco"];?>"
                                            >
                                                <i class="fas fa-pencil-alt"></i>
                                                <i class="fas fa-pencil-alt"></i></button>

                                                <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#eliminarModal"
                                                data-codigo="<?php echo $cuenta_bancaria["cod_cuenta_bancaria"]; ?>"data-nombre="<?php echo $cuenta_bancaria["numero_cuenta"]; ?>"> 
                                                <i class="fas fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                    <?php endif;
                                        } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>

<!-- =============================
    MODAL REGISTRAR CUENTA BANCARIA 
================================== -->
<div class="modal fade" id="modalregistrarusuario" tabindex="-1" aria-labelledby="modalregistrarusuariolabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registrarModalLabel">Registrar Cuenta Bancaria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formregistrarusuario" method="post">
                    <!-- Seleccionar Banco -->
                    <div class="form-group">
                        <label for="banco">Banco <span class="text-danger">*</span></label>
                        <select class="form-control" id="banco" name="banco" required>
                            <option value="" disabled selected>Seleccione un banco</option>
                            <?php foreach($bancos as $banco): ?>
                                <option value="<?php echo $banco['cod_banco']; ?>">
                                    <?php echo $banco['nombre_banco']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Seleccionar Tipo de Cuenta -->
                    <div class="form-group">
                        <label for="tipo_cuenta">Tipo de Cuenta <span class="text-danger">*</span></label>
                        <select class="form-control" id="tipo_cuenta" name="tipo_cuenta" required>
                            <option value="" disabled selected>Seleccione un tipo de cuenta</option>
                            <?php foreach($tipos_cuenta as $tipo): ?>
                                <option value="<?php echo $tipo['cod_tipo_cuenta']; ?>">
                                    <?php echo $tipo['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Número de Cuenta -->
                    <div class="form-group">
                        <label for="numero_cuenta">Número de Cuenta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="numero_cuenta" name="numero_cuenta" placeholder="Ingresa el número de cuenta" required>
                    </div>

                    <!-- Saldo -->
                    <div class="form-group">
                        <label for="saldo">Saldo <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" id="saldo" name="saldo" placeholder="Ingresa el saldo disponible" required>
                    </div>

                    <!-- Seleccionar Divisa -->
                    <div class="form-group">
                        <label for="divisa">Divisa <span class="text-danger">*</span></label>
                        <select class="form-control" id="divisa" name="cod_divisa" required>
                            <option value="" disabled selected>Seleccione una divisa</option>
                            <option value="VES">Bolívares (VES)</option>
                            <option value="USD">Dólares (USD)</option>
                            <option value="EUR">Euros (EUR)</option>
                        </select>
                    </div>

                    <!-- Advertencia de campos obligatorios -->
                    <div class="alert alert-light d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>Todos los campos marcados con (*) son obligatorios</span>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                </div>
                </form>
        </div>
    </div>
</div>

<?php
if (isset($registrar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $registrar["title"]; ?>',
            text: '<?php echo $registrar["message"]; ?>',
            icon: '<?php echo $registrar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'cuenta_bancaria';
            }
        });
    </script>  
<?php endif; ?>
<!-- Modal Editar Cuenta -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Cuenta Bancaria</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="codigo" name="codigo">
                    <div class="form-group">
                        <label>Banco</label>
                        <select class="form-control" id="bancoE" name="bancoE">
                            <?php foreach($bancos as $banco): ?>
                                <option value="<?= $banco['cod_banco'] ?>"><?= $banco['nombre_banco'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tipo de Cuenta</label>
                        <select class="form-control" id="tipo_cuentaE" name="tipo_cuentaE">
                            <?php foreach($tipos_cuenta as $tipo): ?>
                                <option value="<?= $tipo['cod_tipo_cuenta'] ?>"><?= $tipo['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Número de Cuenta</label>
                        <input type="text" class="form-control" id="numero_cuentaE" name="numero_cuentaE">
                    </div>
                    <div class="form-group">
                        <label>Saldo</label>
                        <input type="number" step="0.01" class="form-control" id="saldoE" name="saldoE">
                    </div>
                    <div class="form-group">
                        <label>Divisa</label>
                        <select class="form-control" id="divisaE" name="divisaE">
                            <option value="VES">Bolívares</option>
                            <option value="USD">Dólares</option>
                            <option value="EUR">Euros</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="actualizar" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
if (isset($editar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $editar["title"]; ?>',
            text: '<?php echo $editar["message"]; ?>',
            icon: '<?php echo $editar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'cuenta_bancaria';
            }
        });
    </script>
<?php endif; ?>
<!-- Modal Eliminar -->
<div class="modal fade" id="eliminarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="eliminarForm" method="post">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Eliminar Cuenta</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas eliminar la cuenta <b><span id="username"></span></b>?</p>
                    <input type="hidden" id="usercode" name="usercode">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="borrar" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

        </div>
    </div>
    </div>      
    </section>
</div>
<?php if (isset($eliminar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $eliminar["title"]; ?>',
            text: '<?php echo $eliminar["message"]; ?>',
            icon: '<?php echo $eliminar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'cuenta_bancaria';
            }
        });
    </script>
<?php endif; ?>

<?php
if (isset($advertencia)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $advertencia["title"]; ?>',
            text: '<?php echo $advertencia["message"]; ?>',
            icon: '<?php echo $advertencia["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'cuentabancaria';
            }
        });
    </script>
<?php endif; ?>
<script src="vista/dist/js/modulos-js/cuenta_bancaria.js"></script>