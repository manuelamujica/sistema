<?php
require_once "controlador/gastos.php";
?>

<!-- MODificado 11/05/2025 -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gastos Fijos y Variables</h1>
                    <p>Lleva el control de los gastos de tu empresa.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <!-- Boxes de Resumen -->
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalRGasto">
                        Registrar gasto
                    </button>

                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-6">
                    <div class="small-box" style="background-color: #8770fa; color: white;">
                        <div class="inner">
                            <h3 id="total-gastos">
                                <?php foreach ($totalG as $tg) {
                                    echo $tg['total_monto'] . ' Bs';
                                } ?></h3>
                            <p>Monto de Gastos Totales</p>
                        </div>
                    </div>

                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3 id="gastos-fijos">
                                <?php foreach ($totalF as $tf) {
                                    echo $tf['total_monto'] . ' Bs';
                                } ?></h3>
                            <p>Monto de Gastos Fijos</p>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3 id="gastos-variables">
                                <?php foreach ($totalV as $tv) {
                                    echo $tv['total_monto'] . ' Bs';
                                } ?></h3>
                            <p>Monto de Gastos Variables</p>
                        </div>
                    </div>
                    <div class="small-box" style="background-color: #8770fa; color: white;">
                        <div class="inner">
                            <h3 id="total-gastos">
                                <?php foreach ($totalP as $tgp) {
                                    echo $tgp['total_monto'] . ' Bs';
                                } ?></h3>
                            <p>Monto de Gastos Pagados</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Main content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title"><b> Gastos Fijos </b></h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Descripción</th>
                                            <th>Monto</th>
                                            <th>Último pago</th>
                                            <th>Inicios de pago</th>
                                            <!--<th>Días restantes</th>-->
                                            <th>Status</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($gastosF)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center">No hay gastos fijos</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php
                                            foreach ($gastosF as $F) {
                                            ?>
                                                <td><?php echo $F['cod_gasto']
                                                    ?></td>
                                                <td><?php echo $F['descripcion']
                                                    ?></td>
                                                <td><?php echo $F['monto']
                                                    ?></td>
                                                <td><?php echo $F['fecha']
                                                    ?></td>
                                                <td><?php echo $F['fechac']
                                                    ?></td>
                                                <td>
                                                    <?php if ($F['status'] == 1): ?>
                                                        <span class="badge bg-secondary">Pendiente</span>
                                                        <button name="abono" title="Pagar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#pagoGModal"
                                                            data-cod_gasto="<?php echo $F["cod_gasto"]; ?>"
                                                            data-totalgastos="<?php echo $F["monto"];  ?>"
                                                            data-nombre="<?php echo $F["nombret"]; ?>">
                                                            <i class="fas fa-money-bill-wave"></i>
                                                        </button>
                                                    <?php elseif ($F['status'] == 2): ?>
                                                        <span class="badge bg-warning">Pago parcial</span>
                                                        <button name="partes" title="Pagar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#pagoGModal"
                                                            data-cod_gasto="<?php echo ($F["cod_gasto"]);  //MODIFICACIÓN 11/05/2025
                                                                            ?>"
                                                            data-codpago="<?php echo ($F["cod_pago_emitido"])  ?>"
                                                            data-fecha="<?php echo ($F["fecha"])  ?>"
                                                            data-totalgastos="<?php echo ($F["monto"]) ?>"
                                                            data-montop="<?php echo ($F["monto_total"]) ?>"
                                                            data-nombre="<?php echo ($F["nombre_naturaleza"])?>">
                                                            <i class="fas fa-money-bill-wave"></i>
                                                        </button>
                                                    <?php elseif ($F['status'] == 3): ?>
                                                        <span class="badge bg-success">Completada</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Anulada</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button name="ajustar" class="btn btn-warning btn-sm editar" title="Editar" data-toggle="modal" data-target="#modificargasto"
                                                        data-codigo_gasto="<?php echo $F["cod_gasto"]; ?>"
                                                        data-nombre=" <?php echo $F["descripcion"]; ?> ">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    <button name="confirmar" class="btn btn-danger btn-sm eliminar" title="Eliminar" data-toggle="modal" id="modificar" data-target="#eliminarG"
                                                        data-cod="<?php echo $F["cod_gasto"]; ?>"
                                                        data-eliminar="<?php echo $F['descripcion']; ?>">
                                                        <i class="fas fa-trash-alt"></i></button>
                                                </td>
                                                </tr>
                                            <?php }
                                            ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title"><b> Gastos Variables </b></h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Descripción</th>
                                            <th>Monto</th>
                                            <th>Último pago</th> <!--Aquí se debe de añadir la fecha del ultimo pago combinando las tablas pago emitido y gastos-->
                                            <th>Inicios de pago</th>
                                            <!--<th>Días restantes</th>-->
                                            <th>Status</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($gastosV)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center">No hay gastos variables</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php
                                            foreach ($gastosV as $v) {
                                            ?>
                                                <td><?php echo $v['cod_gasto']
                                                    ?></td>
                                                <td><?php echo $v['descripcion']
                                                    ?></td>
                                                <td><?php echo $v['monto']
                                                    ?></td>
                                                <td><?php echo $v['fecha']
                                                    ?></td>
                                                <td><?php echo $v['fechac']
                                                    ?></td>
                                                <td>
                                                    <?php if ($v['status'] == 1): ?>
                                                        <span class="badge bg-secondary">Pendiente</span>
                                                        <button name="abono" title="Pagar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#pagoGModal"
                                                            data-cod_gasto="<?php echo $v["cod_gasto"];
                                                                            ?>"
                                                            data-totalgastos="<?php echo $v["monto"];
                                                                                ?>"
                                                            data-nombre="<?php echo $v["nombre_naturaleza"]; ?>">
                                                            <i class="fas fa-money-bill-wave"></i>
                                                        </button>
                                                    <?php elseif ($v['status'] == 2): ?>
                                                        <span class="badge bg-warning">Pago parcial</span>
                                                        <button name="partes" title="Pagar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#pagoGModal"
                                                            data-cod_gasto="<?php echo $v["cod_gasto"]; ?>"
                                                            data-codpago="<?php echo $v["cod_pago_emitido"]; ?>"
                                                            data-fecha="<?php echo $v["fecha"]; ?>"
                                                            data-totalgastos="<?php echo $v["monto"];  ?>"
                                                            data-montop="<?php echo $v["monto_total"];  ?>"
                                                            data-nombre="<?php echo $v["nombret"];  ?>">
                                                            <i class="fas fa-money-bill-wave"></i>
                                                        </button>
                                                    <?php elseif ($v['status'] == 3): ?>
                                                        <span class="badge bg-success">Completada</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Anulada</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button name="ajustar" class="btn btn-warning btn-sm editar" title="Editar" data-toggle="modal" data-target="#modificargasto"
                                                        data-cod_gasto="<?php echo $v["cod_gasto"]; ?>"
                                                        data-nombre=" <?php echo $v["descripcion"]; ?> ">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    <button name="confirmar" class="btn btn-danger btn-sm eliminar" title="Eliminar" data-toggle="modal" id="modificar" data-target="#eliminarG"
                                                        data-cod_gasto="<?php echo $v["cod_gasto"]; ?>"data-cod_gasto="<?php echo $v["cod_gasto"]; ?>"
                                                        data-eliminar="<?php echo $v['descripcion']; ?>">
                                                        <i class="fas fa-trash-alt"></i></button>

                                                </td>
                                                </tr>


                                            <?php }
                                            ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- =============================
                    MODAL REGISTRAR GASTOS  (100% FUNCIONAL) EN REVISIÓN
                ================================== -->
                <div class="modal fade" id="modalRGasto" tabindex="-1" aria-labelledby="modalRegistrarGastoLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="registrarModalLabel">Registrar Gasto</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="formRegistrarGastos" method="post">
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label for="descripcion">Descripción del gasto</label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Descripción del gasto a registrar, por ejemplo: Compra de papel.">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <script>
                                                $(function() {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                            <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Ingrese una descripción del gasto" maxlength="45" required>
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="fecha_del_pago">Fecha</label> <!-- Fecha de creación del gasto -->
                                                <input type="text" class="form-control" id="fecha_del_pago" name="fecha">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label for="categoriaG">Categoría del gasto<span class="text-danger" style="font-size: 15px;"> *</span></label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Selecciona la categoría del gasto, por ejemplo: Suministros para papeplería.">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <script>
                                                $(function() {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                            <div class="input-group">
                                                <select class="form-control" id="categoriaG" name="cod_cat_gasto" required>
                                                    <option value="" selected disabled>Seleccione una opción</option>
                                                    <?php foreach ($categorias as $c): ?>
                                                        <option value="<?php echo $c['cod_cat_gasto']; ?>">
                                                            <?php echo $c['categoria']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#modalCategoria">+</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label for="Tgasto">Tipo de Gasto</label>
                                            <input type="text" class="form-control" id="Tgasto" placeholder="Tipo de gasto" readonly>
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>

                                    </div>

                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label for="monto">Monto<span class="text-danger" style="font-size: 15px;"> *</span></label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingrese el monto del gasto a pagar en Bs, por ejemplo: 450.">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <script>
                                                $(function() {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                            <input type="number" class="form-control" step="0.01" min="0" id="monto" name="monto" placeholder="Monto del gasto en Bs">
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>
                                        <div class="col-6">
                                            <label for="condicion">Condición del gasto<span class="text-danger" style="font-size: 15px;"> *</span></label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Seleccione la opción del pago, por ejemplo: Al contado.">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <script>
                                                $(function() {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                            <div class="input-group">
                                                <select class="form-control" id="condicion" name="cod_condicion" required>
                                                   
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#modalCategoria">+</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Alert Message -->
                                    <div class="alert alert-light d-flex align-items-center" role="alert">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <span>Todos los campos marcados con (*) son obligatorios</span>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-secondary" name="deshacer" id="deshacer">Deshacer</button>
                                        <button type="submit" class="btn btn-primary" name="guardarG">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($guardarG)): ?>
                    <script>
                        Swal.fire({
                            title: '<?php echo $guardarG["title"]; ?>',
                            text: '<?php echo $guardarG["message"]; ?>',
                            icon: '<?php echo $guardarG["icon"]; ?>',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'gastos';
                            }
                        });
                    </script>
                <?php endif; ?>
                <!-- =======================
                MODAL REGISTRAR PAGO DE GASTOS (100% FUNCIONAL)
                ============================= -->
                <div class="modal fade" id="pagoGModal" tabindex="-1" aria-labelledby="pagoGLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pagoLabel">Registrar Pago para Gastos</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="pagoForm" method="post">
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="hidden" name="cod_gasto" id="cod_gasto">
                                                <label for="cod_gasto">Nro de Gasto</label>
                                                <input type="text" class="form-control" id="cod_gasto1" name="cod_gasto1" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nombre_gasto">Nombre del Gasto</label>
                                                <input type="text" class="form-control" id="nombre_gasto" name="nombre_gasto" readonly>
                                                <input type="hidden" name="tipo_pago" id="gasto">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="fecha_del_pago">Fecha de pago</label>
                                                <input type="text" class="form-control" id="fecha_del_pago" name="fecha" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="monto-section">
                                        <div class="text-center my-3">
                                            <h4>Monto pagado: <span id="total-pago1" class="font-weight-bold" style="font-size: 3rem;">0.00</span></h4>
                                        </div>
                                        <div class="text-center my-3">
                                            <h4>Monto a Pagar: <span id="total-pago" class="font-weight-bold" style="font-size: 3rem;">0.00</span></h4>
                                        </div>
                                    </div>
                                    <div class="text-center my-3">
                                        <h4>Total del gasto : <span id="total-gasto" class="font-weight-bold" style="font-size: 3rem;">0.00</span></h4>
                                        <input type="hidden" name="montototal" id="total-gasto-oculto">
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <h4>Tipos de Pago</h4>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <h4>Monto</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <?php foreach ($formaspago as $index => $opcion): ?>
                                            <?php if ($opcion['tipo_moneda'] == 'bolivares'): ?>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="<?= $opcion['medio_pago']; ?>" readonly>
                                                        <input type="hidden" name="pago[<?= $index; ?>][cod_tipo_pago]" value="<?= $opcion['cod_tipo_pago']; ?>">

                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Bs</span>
                                                            </div>
                                                            <input type="number" step="0.01" class="form-control monto-bs" id="monto-bs-<?= $index; ?>" name="pago[<?= $index; ?>][monto]" placeholder="Ingrese monto" oninput="calcularTotalpago()">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="<?= $opcion['medio_pago']; ?>" readonly>
                                                        <input type="hidden" name="pago[<?= $index; ?>][cod_tipo_pago]" value="<?= $opcion['cod_tipo_pago']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><?= $opcion['abreviatura']; ?></span>
                                                            </div>
                                                            <input type="number" step="0.01" class="form-control monto-divisa" id="monto-divisa-<?= $index; ?>" placeholder="Monto en <?= $opcion['abreviatura']; ?>" oninput="calcularTotalpago(<?= $index; ?>)">
                                                            <input type="hidden" class="form-control tasa-conversion" id="tasa-conversion-<?= $index; ?>" value="<?= $opcion['tasa']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Bs</span>
                                                            </div>
                                                            <input type="number" step="0.01" class="form-control monto-bs monto-con" id="monto-bs-con-<?= $index; ?>" name="pago[<?= $index; ?>][monto]" placeholder="Monto en Bs" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="form-row justify-content-end">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Monto a pagar</label>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Bs</span>
                                                    </div>
                                                    <input type="number" step="0.001" class="form-control" id="monto_pagar" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Monto pagado</label>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Bs</span>
                                                    </div>
                                                    <input type="number" step="0.001" class="form-control" id="monto_pagado" name="montopagado" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row justify-content-end">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Diferencia</label>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Bs</span>
                                                    </div>
                                                    <input type="number" step="0.001" class="form-control" id="diferencia" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Vuelto a recibir</label>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Bs</span>
                                                    </div>
                                                    <input type="number" step="0.001" name="vuelto" class="form-control" id="vuelto" placeholder="Vuelto" readonly>
                                                    <button type="button" class="btn btn-primary ml-2" id="registrarVueltoBtn" data-toggle="modal" data-target="#vueltoModal" data-cod_gasto="" data-vuelto="" style="display: none;" title="Registrar vuelto">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success" form="pagoForm" id="finalizarPagoBtn" name="pagar_gasto">Finalizar Pago</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($registrarPG)): ?>
                    <script>
                        Swal.fire({
                            title: '<?php echo $registrarPG["title"]; ?>',
                            text: '<?php echo $registrarPG["message"]; ?>',
                            icon: '<?php echo $registrarPG["icon"]; ?>',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'gastos';
                            }
                        });
                    </script>
                <?php endif; ?>

                <!-- =======================
                    MODAL REGISTRAR VUELTO EN OBSERVACIÓN
                ============================= -->
                <div class="modal fade" id="vueltoModal" tabindex="-1" aria-labelledby="vueltoModalBtn" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pagoLabel">Registrar vuelto a recibir</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="vueltoForm" method="post">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="nro_gasto">Nro de Gasto</label>
                                                <input type="text" class="form-control" id="nro_gasto" name="nro_gasto" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center my-3">
                                        <h4>Monto Total: <span id="montoV" class="font-weight-bold" style="font-size: 3rem;">0.00</span></h4>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-5">
                                            <h4>Tipos de Pago</h4>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <h4>Monto</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <?php foreach ($formaspago as $index => $opcion): ?>
                                            <?php if ($opcion['cod_divisa'] == 1): ?>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="<?= $opcion['medio_pago']; ?>" readonly>
                                                        <input type="hidden" name="pago[<?= $index; ?>][cod_tipo_pago]" value="<?= $opcion['cod_tipo_pago']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Bs</span>
                                                            </div>
                                                            <input type="number" step="0.01" class="form-control monto-bs1" id="monto-bs1-<?= $index; ?>" name="pago[<?= $index; ?>][monto]" placeholder="Ingrese monto" oninput="calcularTotalvuelto()">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="<?= $opcion['medio_pago']; ?>" readonly>
                                                        <input type="hidden" name="pago[<?= $index; ?>][cod_tipo_pago]" value="<?= $opcion['cod_tipo_pago']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><?= $opcion['abreviatura']; ?></span>
                                                            </div>
                                                            <input type="number" step="0.01" class="form-control monto-divisa1" id="monto-divisa-<?= $index; ?>" placeholder="Monto en <?= $opcion['abreviatura']; ?>" oninput="calcularTotalvuelto(<?= $index; ?>)">
                                                            <input type="hidden" class="form-control tasa-conversion1" id="tasa-conversion1-<?= $index; ?>" value="<?= $opcion['tasa']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Bs</span>
                                                            </div>
                                                            <input type="number" step="0.01" class="form-control monto-bs monto-con" id="monto-bs-con1-<?= $index; ?>" name="pago[<?= $index; ?>][monto]" placeholder="Monto en Bs" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="form-row justify-content-end">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="monto_a_pagar">Monto a pagar</label>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Bs</span>
                                                    </div>
                                                    <input type="number" step="0.001" class="form-control" id="monto_vuelto" name="vuelto" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Monto pagado</label>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Bs</span>
                                                    </div>
                                                    <input type="number" step="0.001" class="form-control" id="monto_pagado1" name="montopagado" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row justify-content-end">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Diferencia</label>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Bs</span>
                                                    </div>
                                                    <input type="number" step="0.001" class="form-control" id="diferencia1" name="diferencia" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-success" id="vueltoModalBtn" name="pago_vuelto">Guardar vuelto</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL EDITAR  GASTOS LISTO-->

                <div class="modal fade" id="modificargasto">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Editar Gasto</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form role="form" method="post" id="form-editar-gasto">
                                <div class="modal-body">
                                    <input type="hidden" name="cod_gasto" id="cod_gasto_oculto">
                                    <div class="form-group">
                                        <label for="cod_gastoE">Código</label>
                                        <input type="text" class="form-control" name="cod_gastoE" id="cod_gastoE" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcion">Gasto</label>
                                        <input type="text" class="form-control" name="descripcion" id="nombreG">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                        <input type="hidden" id="origin" class="form-control" name="origin" maxlength="10">
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary" name="editarG">Editar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($editarG)): ?>
                    <script>
                        Swal.fire({
                            title: '<?php echo $editarG["title"]; ?>',
                            text: '<?php echo $editarG["message"]; ?>',
                            icon: '<?php echo $editarG["icon"]; ?>',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'gastos';
                            }
                        });
                    </script>
                <?php endif; ?>

                <!-- ELIMINAR GASTO -->
                <div class="modal fade" id="eliminarG">
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
                                    <p>¿Estás seguro de eliminar el gasto: <b><span id=gasto></span>?</p></b>
                                    <input type="hidden" name="cod_gasto" id="cod_eliminar">
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" name="eliminarG" class="btn btn-danger">Eliminar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
                                window.location = 'gastos';
                            }
                        });
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>
<script src="vista/dist/js/modulos-js/gasto.js"></script>