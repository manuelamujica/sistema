<?php require_once 'controlador/roles.php'; ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Roles y Permisos</h1>
                    <p>Configura los roles y permisos de usuario.</p>
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
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarroles">Registrar Rol</button>
                        </div>
                        <div class="card-body">
                            
                                <table id="categorias" class="table table-bordered table-striped datatable">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Rol</th>
                                            <th>Status</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach ($registro as $dato) {
                                        ?>
                                            <?php if ($dato['status'] != 2): ?>
                                                <tr>
                                                    <td><?php echo $dato['cod_tipo_usuario'] ?></td>
                                                    <td><?php echo $dato['rol'] ?></td>
                                                    <td>
                                                        <?php if ($dato['status'] == 1): ?>
                                                            <span class="badge bg-success">Activo</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Inactivo</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($dato['cod_tipo_usuario']!=1): ?>
                                                            <button name="ajustar" class="btn btn-primary btn-sm editar" title="Editar" data-toggle="modal" data-target="#modalmodificarol"
                                                                data-cod="<?php echo $dato['cod_tipo_usuario']; ?>"
                                                                data-rol="<?php echo $dato['rol']; ?>"
                                                                data-status="<?php echo $dato['status']; ?>">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </button>
                                                            <button name="confirmar" class="btn btn-danger btn-sm eliminar" title="Eliminar" data-toggle="modal" id="modificar" data-target="#modaleliminar" 
                                                            data-cod="<?php echo $dato['cod_tipo_usuario']; ?>"
                                                            data-roleliminar="<?php echo $dato['rol']; ?>"
                                                            ><i class="fas fa-trash-alt"></i></button>
                                                        <?php endif;?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            
                        </div>
                    </div>

<!-- =======================
MODAL REGISTRAR ROLES
============================= -->

                    <div class="modal fade" id="modalregistrarroles" tabindex="-1" aria-labelledby="modalregistrarrolLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Registar Rol</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form id="formAgregarRol" method="post">
                                        <!--   NOMBRE DEL ROL     -->
                                        <div class="form-group">
                                            <label for="rol">Rol<span class="text-danger" style="font-size: 15px;"> *</span></label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa el rol para un usuario. Ej: Vendedor.">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                                <script>
                                                    $(function () {
                                                        $('[data-toggle="tooltip"]').tooltip();
                                                    });
                                                </script>
                                            <input type="text" class="form-control" id="rol1" name="rol" placeholder="Ej: Vendedor." maxlength="30">
                                            <div class="invalid-feedback" style="display: none;"></div>

                                        </div>
                                        <div class="form-group">
                                            <label for="permisos">Selecciona los permisos:<span class="text-danger" style="font-size: 15px;"> *</span></label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Asigna a cuáles módulos tendrá acceso este rol de usuario. Selecciona mínimo 1.">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                                <script>
                                                    $(function () {
                                                        $('[data-toggle="tooltip"]').tooltip();
                                                    });
                                                </script>
                                            <br>
                                            <?php foreach ($permiso as $datos): ?>
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox" id="campo" name="permisos[]" value="<?php echo $datos['cod_modulo']; ?>" id="categoria<?php echo $datos['cod_modulo']; ?>">
                                                    <label for="categoria<?php echo $datos['cod_modulo']; ?>">
                                                        <?php echo $datos['nombre'] ?>
                                                    </label>
                                                </div>
                                                <br>
                                            <?php endforeach; ?>
                                        </div>
                                        <!-- Alert Message -->
                                        <div class="alert alert-light d-flex align-items-center" role="alert">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            <span>Todos los campos marcados con (*) son obligatorios</span>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary" name="guardar" onclick="return validacion();">Guardar</button>
                                </div>
                                </form>
                            </div>
                        </div>
                </div>
    </section>
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
                window.location = 'roles';
            }
        });
    </script>
<?php endif; ?>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalmodificarol">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                <h4 class="modal-title">Editar Rol</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="form-editar-rol">
                <div class="modal-body">
                    <input type="hidden" name="cod_tipo_usuario" id="cod_oculto">
                    <div class="form-group">
                        <label for="cod">Código</label>
                        <input type="text" class="form-control" name="cod_tipo_usuario" id="cod" readonly>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <input type="text" class="form-control" name="rol" id="rol">
                        <div class="invalid-feedback" style="display: none;"></div>
                        <input type="hidden" id="rol_origin" name="origin">

                    </div>
                    <div class="form-group">
                        <label for="status">Dtatus</label>
                        <select name="status" id="status">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" name="editar">Editar</button>
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
                window.location = 'roles';
            }
        });
    </script>
<?php endif; ?>

<!--    MODAL DE ADVERTENCIA    -->
<!-- Confirmar Eliminar Modal -->
<div class="modal fade" id="modaleliminar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Confirmar Eliminar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de eliminar el rol: <b><span id="tipoderol"></span></b>?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <form method="post">

                    <input type="hidden" name="eliminar" id="cod_eliminar" value="">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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
                window.location = 'roles';
            }
        });
    </script>
<?php endif; ?>

<script src="vista/dist/js/modulos-js/rol.js"></script>


