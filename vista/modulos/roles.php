<?php require_once 'controlador/roles.php'; ?>
<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Ajustar Roles</h1>
            </div>
        </div>
    </div>
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
            <div class="table-responsive">
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
                <?php foreach ($registro as $dato) {?>
                    <tr>
                        <td>
                            <?php echo $dato['cod_tipo_usuario']?>
                        </td>
                        <td>
                            <?php echo $dato['rol']?>
                        </td>
                        <td>
                            <?php if ($dato['status']==1): ?>
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
                        <button name="confirmar" class="btn btn-danger btn-sm eliminar" title="Eliminar" data-toggle="modal" id="modificar" data-target="#modaleliminar" data-cod="<?php echo $dato['cod_tipo_usuario']; ?>"><i class="fas fa-trash-alt"></i></button>
                        <?php endif; 
                    } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
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
                                            <label for="rol">Rol</label>
                                            <input type="text" class="form-control" id="rol1" name="rol" placeholder="Ingresa el nombre" maxlength="25"> <!--  #############  AGG LIMITE DE CARACTERES  ############ -->
                                            <div class="invalid-feedback" style="display: none;"></div>

                                        </div>
                                        <div class="form-group">
                                            <label for="permisos">Selecciona los permisos:</label><br>
                                            <?php foreach ($permiso as $datos): ?>
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox" id="campo" name="permisos[]" value="<?php echo $datos['cod_permiso']; ?>" id="categoria<?php echo $datos['cod_permiso']; ?>">
                                                    <label for="categoria<?php echo $datos['cod_permiso']; ?>">
                                                        <?php echo $datos['nombre'] ?>
                                                    </label>
                                                </div>
                                                <br>
                                            <?php endforeach; ?>
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
                <!--   CODIGO DE LA ROL    -->

                <div class="modal-body">
                    <input type="hidden" name="cod_tipo_usuario" id="cod_oculto" value="<?php echo $dato['cod_tipo_usuario'] ?>">
                    <div class="form-group">
                        <label for="cod">Código</label>
                        <input type="text" class="form-control" name="cod_tipo_usuario" id="cod" value="<?php echo $dato['cod_tipo_usuario'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <input type="text" class="form-control" name="rol" id="rol" value="<?php echo $dato['rol'] ?>" maxlength="25">
                        <div class="invalid-feedback" style="display: none;"></div>
                        <input type="hidden" id="rol_origin" name="origin" maxlength="25">

                    </div>
                    <div class="form-group">
                        <label for="status">Estatus</label>
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
            <div class="modal-header" style="background: #db6a00 ;color: #ffffff; ">
                <h4 class="modal-title">Confirmar Eliminar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de eliminar el tipo de rol?</p>
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
<script src="vista/dist/js/modulos-js/rol.js"></script>

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

<script>
    //VALIDACIONES JS
    $(document).ready(function() {
        // Validación por cada campo cuando se pierde el foco

        $('#rol1').on('blur', function() {
            var rol1 = $(this).val();
            if (rol1.trim() === '') {
                showError('#rol1', 'el campo rol no puede estar vacío');
            } else if (!/^[a-zA-Z\s]+$/.test(rol1)) {
                showError('#rol1', 'solo letras');
            } else {
                hideError('#rol1');
            }
        });

        $('#rol').on('blur', function() {
            var rol = $(this).val();
            if (rol.trim() === '') {
                showError('#rol', 'el campo rol no puede estar vacío');
            } else if (!/^[a-zA-Z\s]+$/.test(rol)) {
                showError('#rol', 'solo letras');
            } else {
                hideError('#rol');
            }
        });


        function showError(selector, message) {
            $(selector).addClass('is-invalid');
            $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message).css({
                'display': 'block',
                'color': 'red',
                'background-color': 'white'
            });
        }

        function hideError(selector) {
            $(selector).removeClass('is-invalid');
            $(selector).next('.invalid-feedback').css('display', 'none');
        }
    });

</script>