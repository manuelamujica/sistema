<?php require_once 'controlador/unidad.php' ?>
<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Unidades de medida</h1>
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
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarUnidad">Registrar Unidad de medida</button>
                        </div>
                        <div class="card-body">
                            <!-- MOSTRAR EL REGISTRO DE UNIDADES DE MEDIDA -->
                            <div class="table-responsive">
                                <table id="unidad" class="table table-bordered table-striped datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Tipo de medida</th>
                                            <th>Status</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($datos as $dato) {
                                            if ($dato['status'] != 2) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $dato['cod_unidad'] ?></td>
                                                    <td><?php echo $dato['tipo_medida'] ?></td>
                                                    <td>
                                                        <?php if ($dato['status'] == 1): ?>
                                                            <span class="badge bg-success">Activo</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Inactivo</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button name="ajustar" class="btn btn-primary btn-sm editar" title="Editar" data-toggle="modal" data-target="#modalmodificarunidad"
                                                            data-cod="<?php echo $dato['cod_unidad']; ?>"
                                                            data-tipo="<?php echo $dato['tipo_medida']; ?>"
                                                            data-status="<?php echo $dato['status']; ?>">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                        <button name="confirmar" class="btn btn-danger btn-sm eliminar" title="Eliminar" data-toggle="modal" id="modificar" data-target="#modaleliminar" data-cod="<?php echo $dato['cod_unidad']; ?>"><i class="fas fa-trash-alt"></i></button>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } ?>
                                    </tbody>
                            </div>
                            </table>
                        </div>
                    </div>

                    <!-- =======================
MODAL REGISTRAR Unidades de medida 
============================= -->

                    <div class="modal fade" id="modalregistrarUnidad" tabindex="-1" aria-labelledby="modalregistrarUnidadLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="background: #db6a00 ;color: #ffffff; ">
                                    <h5 class="modal-title" id="exampleModalLabel">Registrar Unidad de medida</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form id="formregistrarUnidad" method="post">
                                        <!--   TIPO DE MEDIDA      -->
                                        <div class="form-group">
                                            <label for="tipo_medida">Tipo de medida</label>
                                            <input type="text" class="form-control" name="tipo_medida" placeholder="Ingrese Tipo de Medida" id="tipo_medida1" maxlength="10">
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary" name="guardar" onclick="return validacion();">Guardar</button>
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
                                    window.location = 'unidad';
                                }
                            });
                        </script>
                    <?php endif; ?>

                    <!-- MODAL EDITAR -->
                    <div class="modal fade" id="modalmodificarunidad">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                                    <h4 class="modal-title">Editar Unidad</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form role="form" method="post" id="form-editar-unidad">

                                    <!--   CODIGO DE LA UNIDAD    -->

                                    <div class="modal-body">
                                        <input type="hidden" name="cod_unidad" id="cod_unidad_oculto" value="<?php echo $dato['cod_unidad'] ?>">
                                        <div class="form-group">
                                            <label for="cod_unidad">Código</label>
                                            <input type="text" class="form-control" name="cod_unidad" id="cod_unidad" value="<?php echo $dato['cod_unidad'] ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="tipo_medida">Tipo de medida</label>
                                            <input type="text" class="form-control" name="tipo_medida" id="tipo_medida" value="<?php echo $dato['tipo_medida'] ?>" maxlength="10">
                                            <div class="invalid-feedback" style="display: none;"></div>
                                            <input type="hidden" id="origin" class="form-control" name="origin" maxlength="10">
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
                                    window.location = 'unidad';
                                }
                            });
                        </script>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>



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
                <p>¿Estás seguro de eliminar la unidad medida?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <form method="post">

                    <!--   YA ELIMINA!!!!! BUAJAJAJA  -->
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
                window.location = 'unidad';
            }
        });
    </script>
<?php endif; ?>

</section>
</div>
<script src="vista/dist/js/modulos-js/unidad.js"></script>

<script>
    //VALIDACIONES JS
    $(document).ready(function() {
        // Validación por cada campo cuando se pierde el foco

        $('#tipo_medida1').on('blur', function() {
            var tipo_medida1 = $(this).val();
            if (tipo_medida1.trim() === '') {
                showError('#tipo_medida1', 'el campo unidad de medida no puede estar vacío');
            } else if (!/^[a-zA-Z\s]+$/.test(rol1)) {
                showError('#tipo_medida1', 'solo letras');
            } else {
                hideError('#tipo_medida1');
            }
        });

        $('#tipo_medida').on('blur', function() {
            var tipo_medida = $(this).val();
            if (tipo_medida.trim() === '') {
                showError('#tipo_medida', 'el campo unidad de medida no puede estar vacío');
            } else if (!/^[a-zA-Z\s]+$/.test(tipo_medida)) {
                showError('#tipo_medida', 'solo letras');
            } else {
                hideError('#tipo_medida');
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