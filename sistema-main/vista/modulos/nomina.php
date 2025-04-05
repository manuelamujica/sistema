<?php require_once 'controlador/nomina.php' ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- MODULO DE NÓMINA  -->
                    <h1>Pre-Nómina</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- ################## SECCIÓN DE AGENDA 
                     FALTA DESARROLLARLO MAS PARA QUE LOS TURNOS ESTEN AL LADO DEL HORAIRO ####################-->
                    <div class="card collapsed-card" title="Oprimir doble click para expandir">
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Agenda</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <h3>Aquí va agenda</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalregistrarturnos" tabindex="-1" aria-labelledby="modalregistrarturnosLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalregistrarturnosLabel">Registrar Turno</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="assignShiftForm" method="post" enctype="multipart/form-data">
                                <!-- Empleado -->
                                <div class="form-group">
                                    <label for="employee">Empleado <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="employee" name="employee" placeholder="Ej: Juan Pérez" required>
                                </div>
                                <!-- Turno -->
                                <div class="form-group">
                                    <label for="shift">Turno:</label>
                                    <input type="text" id="shift" name="shift" class="form-control" required>
                                </div>
                                <!-- Mensaje de alerta -->
                                <div class="alert alert-light d-flex align-items-center" role="alert">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <span>Todos los campos marcados con (*) son obligatorios</span>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <!-- ################## SECCIÓN DE RRHH ############## -->
            <div class="card collapsed-card">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">RRHH</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <p>AQUI VA EL RRHH</p>
                    </div>
                </div>
            </div>

            <!-- ################## SECCIÓN DE nomina ############## -->
            <div class="card collapsed-card">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Pré-nómina</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <p>AQUI VA LA GESTIÓN DE PRÉ-NOMINA</p>
                    </div>
                </div>
            </div>
            <!-- ################## SECCIÓN DE AJUSTE DE NÓMINA ############## -->
            <div class="card collapsed-card">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Ajuste de pre-nómina</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card">
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title">Fórmulas de pre-nómina</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="card d-flex justify-content-start">
                                        <div class="row">
                                            <!-- Botón para ventana modal -->
                                            <button class="btn btn-dark mr-4" data-toggle="modal" data-target="#modalregistrarFormula">Registrar Fórmula</button>
                                            <!-- Botón para ventana modal -->
                                            <hr>
                                            <button class="btn btn-dark mr-4" data-toggle="modal" data-target="#modalregistrarVariable">Registrar Variable</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- Primera tabla -->
                                        <div class="col-md-6">
                                            <div class="table-responsive">
                                                <table id="unidad" class="table table-bordered table-striped nowrap" style="width:100%">
                                                    <thead class="thead-dark">
                                                        <tr class="odd dt-hasChild parent">
                                                            <th>Código</th>
                                                            <th>Fórmula</th>
                                                            <th>Expresión</th>
                                                            <th>Status</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($datosf as $datof) {
                                                            if ($datof['status'] != 2) {
                                                        ?>
                                                                <tr>
                                                                    <td class="dtr-control sorting_1" tabindex="0" style><?php echo $datof['cod_formula']
                                                                                                                            ?>
                                                                    </td>
                                                                    <td><?php echo $datof['nombre']
                                                                        ?></td>
                                                                    <td><?php echo $datof['expresion']
                                                                        ?></td>
                                                                    <td>
                                                                        <?php if ($datof['status'] == 1):
                                                                        ?>
                                                                            <span class="badge bg-success">Activo</span>
                                                                        <?php else:
                                                                        ?>
                                                                            <span class="badge bg-danger">Inactivo</span>
                                                                        <?php endif;
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <button name="ajustarf" class="btn btn-primary btn-sm editarf" title="Editar" data-toggle="modal" data-target="#modalmodificarformula"
                                                                            data-codf="<?php echo $datof['cod_formula'];
                                                                                        ?>"
                                                                            data-nombref="<?php echo $datof['nombre'];
                                                                                            ?>"
                                                                            data-expresionf="<?php echo $datof['expresion'];
                                                                                                ?>"
                                                                            data-statusf="<?php echo $datof['status'];
                                                                                            ?>">
                                                                            <i class="fas fa-pencil-alt"></i>
                                                                        </button>
                                                                        <button name="confirmarf" class="btn btn-danger btn-sm eliminar" title="Eliminar" data-toggle="modal" id="modificarf" data-target="#modaleliminarf"
                                                                            data-codf="<?php echo $datof['cod_formula'];
                                                                                        ?>"
                                                                            data-nombref="<?php echo $datof['nombre'];
                                                                                            ?>">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Segunda tabla -->
                                        <div class="col-md-6">
                                            <div class="table-responsive">
                                                <table id="unidad2" class="table table-bordered table-striped nowrap" style="width:100%">
                                                    <thead class="thead-dark">
                                                        <tr class="odd dt-hasChild parent">
                                                            <th>Código</th>
                                                            <th>Variable</th>
                                                            <th>Status</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($datos as $datov) {
                                                            if ($datov['status'] != 2) {
                                                        ?>
                                                                <tr>
                                                                    <td class="dtr-control sorting_1" tabindex="0" style><?php echo $datov['cod_var']
                                                                                                                            ?>
                                                                    </td>
                                                                    <td><?php echo $datov['nombre_var']
                                                                        ?></td>
                                                                    <td>
                                                                        <?php if ($datov['status'] == 1):
                                                                        ?>
                                                                            <span class="badge bg-success">Activo</span>
                                                                        <?php else:
                                                                        ?>
                                                                            <span class="badge bg-danger">Inactivo</span>
                                                                        <?php endif;
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <button name="ajustarv" class="btn btn-primary btn-sm editarv" title="Editar" data-toggle="modal" data-target="#modalmodificarvar"
                                                                            data-codv="<?php echo $datov['cod_var'];
                                                                                        ?>"
                                                                            data-nombrev="<?php echo $datov['nombre_var'];
                                                                                            ?>"
                                                                            data-statusv="<?php echo $datov['status'];
                                                                                            ?>">
                                                                            <i class="fas fa-pencil-alt"></i>
                                                                        </button>
                                                                        <button name="confirmarv" class="btn btn-danger btn-sm eliminar" title="Eliminar" data-toggle="modal" id="modificarv" data-target="#modaleliminarv"
                                                                            data-codv="<?php echo $datov['cod_var'];
                                                                                        ?>"
                                                                            data-nombrev="<?php echo $datov['nombre_var'];
                                                                                            ?>">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Formularios de FORMULAS -->
                                    <div class="modal fade" id="modalregistrarFormula" tabindex="-1" aria-labelledby="modalregistrarFormulaLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: black ;color: #ffffff; ">
                                                    <h5 class="modal-title" id="exampleModalLabel">Registrar formula</h5>
                                                    <button type="button" class="close-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <button class="btn btn-dark" data-toggle="modal" data-target="#modalregistrarVariable">Registrar Variable</button>
                                                    <form id="formRegistrarFormula" method="post">
                                                        <!--   REGISTRAR FORMULA      -->
                                                        <div class="form-group">
                                                            <label for="formula">Nombre de la fórmula</label>
                                                            <!-- TOOLTIPS-->
                                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa nombre de la formula, por ejemplo: Formula para personal juridico">
                                                                <i class="fas fa-info-circle"></i>
                                                            </button>
                                                            <script>
                                                                $(function() {
                                                                    $('[data-toggle="tooltip"]').tooltip();
                                                                });
                                                            </script>
                                                            <input type="text" class="form-control" name="nombre" placeholder="Ej: Formula general" id="formula" maxlength="10">
                                                            <div class="invalid-feedback" style="display: none;"></div>
                                                            <div class="table-responsive mt-4">
                                                                <table id="formulas" class="table table-bordered table-striped">
                                                                    <thead>

                                                                    </thead>
                                                                    <tbody>
                                                                        <!--  filas dinámicas  -->
                                                                    </tbody>
                                                                </table>
                                                                <!-- Botón para agregar más filas -->
                                                                <button type="button" id="add-product" class="btn btn-dark btn-sm">Agregar Campo</button>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-dark" name="guardarF">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- EDITAR  FORMULAS -->
                                    <div class="modal fade" id="modalmodificarformula">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: black; color: #ffffff;">
                                                    <h4 class="modal-title">Editar Formula</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form method="post" id="form-editar-formula">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="cod_formula" id="cod_ocultof">
                                                        <div class="form-group">
                                                            <label for="codf">Código</label>
                                                            <input type="text" class="form-control" name="codf" id="codf" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="expresion">Formula</label>
                                                            <input type="text" class="form-control" name="expresion" id="expresion" readonly>
                                                            <div class="invalid-feedback" style="display: none;"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nombref">Nombre de la fórmula</label>
                                                            <input type="text" class="form-control" name="nombref" id="nombref" required>
                                                            <input type="hidden" id="nombref_origin" name="nombref_origin">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="statusf">Estatus</label>
                                                            <select name="statusf" id="statusf">
                                                                <option value="1">Activo</option>
                                                                <option value="0">Inactivo</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary" name="editarf">Editar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if (isset($editarf)): ?>
                                        <script>
                                            Swal.fire({
                                                title: '<?php echo $editarf["title"]; ?>',
                                                text: '<?php echo $editarf["message"]; ?>',
                                                icon: '<?php echo $editarf["icon"]; ?>',
                                                confirmButtonText: 'Ok'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location = 'nomina';
                                                }
                                            });
                                        </script>
                                    <?php endif; ?>

                                    <!-- Confirmar Eliminar Modal -->
                                    <div class="modal fade" id="modaleliminarf">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h4 class="modal-title">Confirmar Eliminar</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post">
                                                        <p>¿Estás seguro de eliminar la formúla: <b><span id=eliminarf></span>?</p></b>
                                                        <input type="hidden" name="eliminarf" id="cod_eliminarf">
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (isset($eliminarf)): ?>
                                        <script>
                                            Swal.fire({
                                                title: '<?php echo $eliminarf["title"]; ?>',
                                                text: '<?php echo $eliminarf["message"]; ?>',
                                                icon: '<?php echo $eliminarf["icon"]; ?>',
                                                confirmButtonText: 'Ok'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location = 'nomina';
                                                }
                                            });
                                        </script>
                                    <?php endif; ?>

                                    <!--   creacion de variables      -->

                                    <div class="modal fade" id="modalregistrarVariable" tabindex="-1" aria-labelledby="modalregistrarFormulaLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: black ;color: #ffffff; ">
                                                    <h5 class="modal-title" id="exampleModalLabel">Registrar Variable</h5>
                                                    <button type="button" class="close-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <form id="formregistrarVariable" method="post">
                                                        <!--   creacion variable      -->
                                                        <div class="form-group">
                                                            <label for="variable">Nombre de la variable</label>
                                                            <!-- TOOLTIPS-->
                                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa nombre de la variable, por ejemplo: beneficio con impacto salarial">
                                                                <i class="fas fa-info-circle"></i>
                                                            </button>
                                                            <script>
                                                                $(function() {
                                                                    $('[data-toggle="tooltip"]').tooltip();
                                                                });
                                                            </script>
                                                            <input type="text" class="form-control" id="variable" name="variable" placeholder="Ej: salario base" maxlength="15">
                                                            <div class="invalid-feedback" style="display: none;"></div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-dark" name="guardarV">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- EDITAR  VARIABLES -->
                                    <div class="modal fade" id="modalmodificarvar">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: black; color: #ffffff;">
                                                    <h4 class="modal-title">Editar Variables</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form method="post" id="form-editar-variable">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="cod_var" id="cod_ocultov">
                                                        <div class="form-group">
                                                            <label for="codv">Código</label>
                                                            <input type="text" class="form-control" name="codv" id="codv" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nombrev">Variable</label>
                                                            <input type="text" class="form-control" name="nombre_var" id="nombrev" required>
                                                            <input type="hidden" id="nombre_originv" name="nombre_originv">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="statusv">Estatus</label>
                                                            <select name="statusv" id="statusv">
                                                                <option value="1">Activo</option>
                                                                <option value="0">Inactivo</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary" name="editarv">Editar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if (isset($editarv)): ?>
                                        <script>
                                            Swal.fire({
                                                title: '<?php echo $editarv["title"]; ?>',
                                                text: '<?php echo $editarv["message"]; ?>',
                                                icon: '<?php echo $editarv["icon"]; ?>',
                                                confirmButtonText: 'Ok'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location = 'nomina';
                                                }
                                            });
                                        </script>
                                    <?php endif; ?>

                                    <!-- Confirmar Eliminar Modal -->
                                    <div class="modal fade" id="modaleliminarv">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h4 class="modal-title">Confirmar Eliminar</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post">
                                                        <p>¿Estás seguro de eliminar la formúla: <b><span id=eliminarv></span>?</p></b>
                                                        <input type="hidden" name="eliminarv" id="cod_eliminarv">
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (isset($eliminarv)): ?>
                                        <script>
                                            Swal.fire({
                                                title: '<?php echo $eliminarv["title"]; ?>',
                                                text: '<?php echo $eliminarv["message"]; ?>',
                                                icon: '<?php echo $eliminarv["icon"]; ?>',
                                                confirmButtonText: 'Ok'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location = 'nomina';
                                                }
                                            });
                                        </script>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!--MODULO DE TIPOS DE PRE-NOMINA-->
                            <div class="card">
                                <div class="card card-dark">
                                    <div class="card-header">
                                        <h3 class="card-title">Tipos de pre-nómina</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h4>Aquí va el tipo de pre-nómina</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card card-dark">
                                    <div class="card-header">
                                        <h3 class="card-title">Categorías de cargos del personal</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h4>Aquí va la categoría de cargos del personal</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="vista/dist/js/modulos-js/nomina.js"></script>
<script src="vista/dist/js/modulos-js/formula.js"></script>
<script src="vista/dist/js/modulos-js/tiponomina.js"></script>