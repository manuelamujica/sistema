<?php require_once 'controlador/categoriag.php' ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Categoría de Gastos</h1>
                    <p>Organiza tus gastos en categorías y controla sus frecuencias para tener una visión clara de tus finanzas.</p>
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
                            <button class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalCategoria">
                                Registrar categoría
                            </button>
                            <button class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalregistrarFrecuencia">
                                Registrar frecuencia
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <h5>Frecuencia de Pago</h5>
                                <table class="table table-bordered table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>nombre</th>
                                            <th>Días</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($frecuencia as $f) {
                                        ?>
                                            <td><?php echo $f['cod_frecuencia']
                                                ?></td>
                                            <td><?php echo $f['nombre']
                                                ?></td>
                                            <td><?php echo $f['dias']
                                                ?></td>
                                            </tr>
                                        <?php }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <h5>Categoría de Gastos</h5>
                                <table class="table table-bordered table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Fecha</th>
                                            <th>Frecuancia de gastos</th>
                                            <th>Tipo de gasto</th>
                                            <th>Nombre</th>
                                            <th>Status</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categorias as $c) { ?>
                                            <?php if ($c['status_cat_gasto'] != 2): ?>
                                                <tr>
                                                    <td><?php echo $c['cod_cat_gasto']
                                                        ?></td>
                                                    <td><?php echo $c['fecha']
                                                        ?></td>
                                                    <td><?php echo $c['nombref']
                                                        ?></td>
                                                    <td><?php echo $c['nombre']
                                                        ?></td>
                                                    <td><?php echo $c['nombret']
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($c['status_cat_gasto'] == 1): ?>
                                                            <span class="badge bg-success">Activo</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Inactivo</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                      
                                                            <button name="editar" title="Editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#editModal"
                                                                data-codigoC="<?php echo $c["cod_cat_gasto"]; ?>"
                                                                data-nombre="<?php echo $c["nombre"]; ?>"
                                                                data-status="<?php echo $c["status_cat_gasto"]; ?>">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </button>
                                                            <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#eliminar"
                                                                data-codigoC="<?php echo $c["cod_cat_gasto"]; ?>"
                                                                data-medio="<?php echo $c["nombre"]; ?>">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        <?php else: ?>
                                                        
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- =============================
            MODAL REGISTRAR CATEGORIA PARA GASTOS 
        ================================== -->
                    <div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalregistrarCategoriaLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header" style="background: black ;color: #ffffff; ">
                                    <h5 class="modal-title" id="exampleModalLabel">Registrar categoría de gastos</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form id="formregistrarCategoria" method="post">
                                        <!--    FECHA     -->
                                        <input type="hidden" id="fecha-hora" name="fecha_hora">
                                        <div class="form-group">
                                            <label for="fecha">Fecha</label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Se muestran la fecha en que se registra el gasto, según la frecuencia seleccionada, por ejemplo: 01-05-2025 si es mensual.">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <script>
                                                $(function() {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                            <input type="text" class="form-control form-control-sm" name="fecha" id="fecha" readonly>
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>
                                        <!--   NOMBRE      -->
                                        <div class="form-group">
                                            <label for="nombre">Nombre de la categoría</label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingrese el nombre de la categoría, por ejemplo: Gasto para transporte.">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <script>
                                                $(function() {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                            <input type="text" class="form-control" name="nombre" placeholder="Ej: transporte." id="nombre" maxlength="15">
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="tipogasto">Tipo de gasto</label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingrese el tipo de gasto, por ejemplo: Gastos fijos.">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <script>
                                                $(function() {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                            <select name="tipogasto" id="tipogasto" class="form-control">
                                                <option value=""></option>
                                                <?php foreach ($tipo as $t): ?>
                                                    <option value="<?php echo $t['cod_tipo_gasto']; ?>">
                                                        <?php echo $t['nombre']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="frecuenciaC">Frecuencia de pago</label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingrese la frecuencia de pago, por ejemplo: Mensual.">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <script>
                                                $(function() {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                            <select name="frecuenciaC" id="frecuenciaC" class="form-control">
                                                <option value=""></option>
                                                <?php foreach ($frecuencia as $f): ?>
                                                    <option value="<?php echo $f['cod_frecuencia']; ?>" data-nombre="<?php echo $f['nombre']; ?>">
                                                        <?php echo $f['nombre']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-dark" name="guardarC" onclick="return validacion();">Guardar</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($guardarC)): ?>
                        <script>
                            Swal.fire({
                                title: '<?php echo $guardarC["title"]; ?>',
                                text: '<?php echo $guardarC["message"]; ?>',
                                icon: '<?php echo $guardarC["icon"]; ?>',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = 'categoriag';
                                }
                            });
                        </script>
                    <?php endif; ?>

                
                </div>
            </div>
        </div>
    </section>
</div>



<!-- =============================
                    MODAL REGISTRAR FRECUENCIA DE PAGOS PARA GASTOS 
                ================================== -->
<div class="modal fade" id="modalregistrarFrecuencia" tabindex="-1" aria-labelledby="modalregistrarFrecuenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: black ;color: #ffffff; ">
                <h5 class="modal-title" id="exampleModalLabel">Registrar frecuancia de pagos de gastos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="formregistrarFrecuancia" method="post">
                    <!--   NOMBRE      -->
                    <div class="form-group">
                        <label for="frecuencia">Frecuencia de pagos</label>
                        <!-- TOOLTIPS-->
                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingrese el plazo del pago de gastos, por ejemplo: Quincenal">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <script>
                            $(function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        </script>
                        <input type="text" class="form-control" name="frecuencia" placeholder="Ej: Mensual." id="frecuencia" maxlength="15">
                        <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <!--    DIAS     -->
                    <div class="form-group">
                        <label for="dias">Días de plazo</label>
                        <!-- TOOLTIPS-->
                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Se muestran el plazo de días para el pago del gasto, por ejemplo: 15 días.">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <script>
                            $(function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        </script>
                        <input type="text" class="form-control" name="dias" placeholder="Ej: 15." id="dias" maxlength="5" readonly>
                        <div class="invalid-feedback" style="display: none;"></div>
                    </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-dark" name="guardar_frecuencia" onclick="return validacion();">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?php
if (isset($guardarF)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $guardarF["title"]; ?>',
            text: '<?php echo $guardarF["message"]; ?>',
            icon: '<?php echo $guardarF["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'categoriag';
            }
        });
    </script>
<?php endif; ?>


<script src="vista/dist/js/modulos-js/gasto.js"></script>

</section>
</div>