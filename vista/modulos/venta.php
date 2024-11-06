<?php require_once 'controlador/venta.php'; ?>
<!-- MÓDULO TRANSACCIONAL-->
<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Ventas</h1>
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
                            <!-- Botón para abrir el modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ventaModal"> Registrar venta </button>
                        </div>
                    
                        <div class="card-body">
                            <!-- Tabla de productos -->
                            <div class="table-responsive">
                                    <table id="producto" class="table table-bordered table-striped datatable1" style="width: 100%;">
                                        <thead>
                                                <tr>
                                                    <th>Nro. de Venta</th>
                                                    <th>Cliente</th>
                                                    <th>Fecha de emision</th>
                                                    <th>Monto</th>
                                                    <th>Status</th>
                                                    <th>Acciones</th>
                                                </tr> 
                                        </thead>
                                        <tbody>
                                        <?php foreach ($consulta as $venta) { ?>
                                            <tr>
                                                <td><?php echo $venta['codigov']?></td>
                                                <td><?php echo $venta['nombre']." ".$venta['apellido']?></td>
                                                <td><?php echo $venta['fecha'] ?></td>
                                                <td><?php echo $venta['total'] ?></td>
                                                <td>
                                                    <?php if ($venta['status_venta']==1):?>
                                                        <span class="badge bg-default">Pendiente</span>
                                                        <button name="abono" title="Pagar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#pagoModal" 
                                                            data-codventa="<?php echo $venta["codigov"]; ?>" 
                                                            data-totalv="<?php echo $venta["total"]; ?>" 
                                                            data-fecha="<?php echo $venta["fecha"]; ?>"
                                                            data-nombre="<?php echo $venta["nombre"]." ".$venta["apellido"];?>" >
                                                            <i class="fas fa-money-bill-wave"></i>
                                                            </button>
                                                    <?php elseif ($venta['status_venta']==2):?>
                                                        <span class="badge bg-warning">Pago parcial</span>
                                                        <button name="abono" title="Pagar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#abonoModal" 
                                                            data-codventa="<?php echo $venta["cod_venta"]; ?>" 
                                                            data-codpago="<?php echo $venta["cod_pago"]; ?>" 
                                                            data-totalv="<?php echo $venta["total"]; ?>" 
                                                            data-montop="<?php echo $venta["monto_total"]; ?>"
                                                            data-fecha="<?php echo $venta["fecha"]; ?>"
                                                            data-nombre="<?php echo $venta["nombre"]." ".$venta["apellido"];?>" >
                                                            <i class="fas fa-money-bill-wave"></i>
                                                        </button>
                                                    <?php elseif ($venta['status_venta']==3):?>
                                                        <span class="badge bg-success">Completada</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Anulada</span>
                                                    <?php endif;?>
                                                </td>
                                                <td>
                                                <?php if ($venta['status_venta']!=0):?>
                                                    <button name="anular" title="Anular" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#anularventa" 
                                                    data-codventa="<?php echo $venta["codigov"]; ?>" 
                                                    data-status="<?php echo $venta["status_venta"]; ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    <button form="facturaform_<?= $venta['codigov']; ?>" type="submit" name="imprimir" title="Ver factura" class="btn btn-primary btn-sm editar">
                                                    <i class="fas fa-file"></i>
                                                    </button>
                                                    <form id="facturaform_<?= $venta['codigov']; ?>" action="index.php?pagina=factura" method="post" target="_blank">
                                                        <input type="hidden" name="cod_venta" value="<?= $venta['codigov']; ?>">
                                                        <input type="hidden" name="total" value="<?= $venta['total']; ?>">
                                                        <input type="hidden" name="fecha" value="<?= $venta['fecha']; ?>">
                                                        <input type="hidden" name="cliente" value="<?= $venta['nombre']." ".$venta['apellido']; ?>">
                                                        <input type="hidden" name="cedula" value="<?= $venta['cedula_rif']; ?>">
                                                        <input type="hidden" name="direccion" value="<?= $venta['direccion']; ?>">
                                                        <input type="hidden" name="telefono" value="<?= $venta['telefono']; ?>">    
                                                    </form>        
                                                <?php else:?>
                                                    <button title="Anular" class="btn btn-danger btn-sm disabled">
                                                    <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    <button title="Ver factura" class="btn btn-primary btn-sm disabled">
                                                    <i class="fas fa-file"></i>
                                                    </button>
                                                <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php }?>
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

<script>
    <?php if (isset($_GET['abrirModal']) && $_GET['abrirModal'] == 1): ?>
        $(document).ready(function(){
            $('#ventaModal').modal('show');
        });
    <?php endif; ?>
</script>
<!-- Modal de Venta con búsqueda interactiva -->
<div class="modal fade" id="ventaModal" tabindex="-1" aria-labelledby="ventaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                <h5 class="modal-title" id="ventaModalLabel">Registrar Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ventamodal" method="post">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Información del Cliente y Nro de Venta -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cedula-rif">Cédula/RIF</label>
                                <div class="row">
                                    <div class="col-md-11">
                                        <input type="text" class="form-control form-control-sm" id="cedula-rif" name="cedula-rif" placeholder="Cédula o RIF">
                                        <input type="hidden" id="cod_cliente" name="cod_cliente">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#modalRegistrarClientes">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nombre-cliente">Numero de telefono</label>
                                <input type="text" class="form-control form-control-sm" id="numero-cliente" name="numero-cliente" placeholder="telefono del cliente" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre-cliente">Nombre del Cliente</label>
                                <input type="text" class="form-control form-control-sm" id="nombre-cliente" name="nombre-cliente" placeholder="Nombre del cliente" readonly>
                            </div>
                            <div class="form-row align-items-end">
                                <div class="col-md-6">
                                    <div class="form-group">

                                    <?php $ultimo=end($consulta); $nueva=$ultimo['codigov']+1; ?>
                                        <label for="numero">Nro Venta</label>
                                        <input type="text" class="form-control form-control-sm" id="nro_venta" value="<?=$nueva?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha-hora">Fecha y Hora</label>
                                        <input type="text" class="form-control form-control-sm" id="fecha-hora" name="fecha_hora" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla para agregar productos -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Total</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody id="ventaProductosBody">
                                <!-- Se generan dinámicamente filas de productos -->
                            </tbody>
                        </table>
                    
                    <!-- Botón para agregar más filas de productos -->
                    <button type="button" class="btn btn-success" onclick="agregarFila()">Agregar Producto</button>
                    <div class="card card-outline card-primary float-right" style="width: 300px;">
                        <div class="card-body">
                            <p>Subtotal: Bs <span id="subtotal" class="text-bold">0.00</span></p>
                            <p>Exento: Bs <span id="exento" class="text-bold">0.00</span></p>
                            <p>Base imponible: Bs <span id="base-imponible" class="text-bold">0.00</span></p>
                            <p>IVA (16%): Bs <span id="iva" class="text-bold">0.00</span></p>
                            <p class="text-bold">TOTAL: Bs <span id="total-span" class="text-bold">0.00</span></p>
                            <input type="hidden" id="total-general" name="total_general">
                        </div>
                    </div>
                    </div>
                </div>
                <input type="hidden" name="registrarv" value="1">
            </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" form="ventamodal" class="btn btn-primary" id="realizarVentaBtn">Realizar Venta</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =======================
MODAL REGISTRAR PAGO
============================= -->
        <div class="modal fade" id="pagoModal" tabindex="-1" aria-labelledby="pagoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                        <h5 class="modal-title" id="pagoLabel">Registrar Pago</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="pagoForm" method="post">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nro_venta">Nro de Venta</label>
                                    <input type="text" class="form-control" id="nro-venta" name="nro_venta" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nombre_cliente">Nombre del Cliente</label>
                                    <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_venta">Fecha de la Venta</label>
                                    <input type="text" class="form-control" id="fecha_venta" name="fecha_venta" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="text-center my-3">
                            <h4>Total a Pagar:   <span id="total-pago" class="font-weight-bold" style="font-size: 3rem;">0.00</span></h4>
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
                            <?php foreach ($opciones as $index => $opcion): ?>
                                <?php if ($opcion['cod_divisa'] == 1): ?>
                                    <!-- Si es bolívares (sin conversión de divisas) -->
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
                                    <!-- Si es otra divisa (con conversión) -->
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
                                        <input type="number" step="0.001" class="form-control" id="monto_pagar"  name="monto_pagar">
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
                                        <input type="number" step="0.001" class="form-control" id="monto_pagado" name="monto_pagado" readonly>
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
                        </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" form="pagoForm" id="finalizarPagoBtn" name="finalizarp">Finalizar Pago</button>
                    </div>
                </div>
            </div>
        </div>
<?php if (isset($registrarp)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $registrarp["title"]; ?>',
            text: '<?php echo $registrarp["message"]; ?>',
            icon: '<?php echo $registrarp["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'venta';
            }
        });
    </script>
<?php endif; ?>

<!-- =======================
MODAL ABONAR PAGO 
============================= -->

        <div class="modal fade" id="abonoModal" tabindex="-1" aria-labelledby="abonoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                        <h5 class="modal-title" id="pagoLabel">Registrar Pago</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="abonoForm" method="post">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nro_venta">Nro de Venta</label>
                                    <input type="text" class="form-control" id="nro-venta1" name="nro_venta" readonly>
                                    <input type="hidden" class="form-control" id="codigop" name="codigop">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nombre_cliente">Nombre del Cliente</label>
                                    <input type="text" class="form-control" id="nombre_cliente1" name="nombre_cliente" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_venta">Fecha de la Venta</label>
                                    <input type="text" class="form-control" id="fecha_venta1" name="fecha_venta" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="text-center my-3">
                            <h4>Monto de la venta:   <span id="total-venta1" class="font-weight-bold" style="font-size: 2rem;">0.00</span></h4>
                            <input type="hidden" class="form-control" id="t-parcial" name="total_parcial">
                        </div>
                        <div class="text-center my-3">
                            <h4>Monto a Pagar:   <span id="total-pago1" class="font-weight-bold" style="font-size: 3rem;">0.00</span></h4>
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
                            <?php foreach ($opciones as $index => $opcion): ?>
                                <?php if ($opcion['cod_divisa'] == 1): ?>
                                    <!-- Si es bolívares (sin conversión de divisas) -->
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
                                                <input type="number" step="0.01" class="form-control monto-bs1" id="monto-bs-<?= $index; ?>" name="pago[<?= $index; ?>][monto]" placeholder="Ingrese monto" oninput="calcularTotalpago1()">
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- Si es otra divisa (con conversión) -->
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
                                                <input type="number" step="0.01" class="form-control monto-divisa1" id="monto-divisa-<?= $index; ?>" placeholder="Monto en <?= $opcion['abreviatura']; ?>" oninput="calcularTotalpago1(<?= $index; ?>)">
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
                                                <input type="number" step="0.01" class="form-control monto-bs1 monto-con1" id="monto-bs-con-1<?= $index; ?>" name="pago[<?= $index; ?>][monto]" placeholder="Monto en Bs" readonly>
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
                                        <input type="number" step="0.001" class="form-control" id="monto_pagar1"  name="monto_pagar">
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
                                        <input type="number" step="0.001" class="form-control" id="monto_pagado1" name="monto_pagado" readonly>
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
                                        <input type="number" step="0.001" class="form-control" id="diferencia1" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" form="abonoForm" id="finalizarPagoBtn" name="parcialp">Finalizar Pago</button>
                    </div>
                </div>
            </div>
        </div>
<?php if (isset($registrarpp)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $registrarpp["title"]; ?>',
            text: '<?php echo $registrarpp["message"]; ?>',
            icon: '<?php echo $registrarpp["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'venta';
            }
        });
    </script>
<?php endif; ?>



<!-- =======================
MODAL REGISTRAR CLIENTES 
============================= -->


<div class="modal fade" id="modalRegistrarClientes" tabindex="-1" aria-labelledby="modalRegistrarClientesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="clientesModalLabel">Registrar cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="formRegistrarClientes" action="index.php?pagina=clientes" method="post">
                    <div class="form-group">
                        <label for="cedula_rif">Cédula o Rif:</label>
                        <input type="text" class="form-control" name="cedula_rif" id="cedula_rif" placeholder="Ingrese la cédula" required>
                        <input type="hidden" name="vista" value="venta">
                        
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Ingrese el nombre" required>

                        <label for="apellido">Apellido:</label>
                        <input type="text" class="form-control" name="apellido" placeholder="Ingrese el apellido" required>

                        <label for="telefono">Teléfono:</label>
                        <input type="tel" class="form-control"  name="telefono" placeholder="Ingrese el teléfono">

                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" placeholder="Ingrese el correo electrónico">

                        <label for="direccion">Direccion:</label>
                        <textarea class="form-control" name="direccion" placeholder="Ingrese la dirección de vivienda"></textarea>
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

<?php if (isset($registrar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $registrar["title"]; ?>',
            text: '<?php echo $registrar["message"]; ?>',
            icon: '<?php echo $registrar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.setItem('abrirModal', 'true');
                window.location='venta';
            }
        });

    </script>
<?php endif; ?>
<script>
    $('#cedula_rif').blur(function (e){
        var buscar=$('#cedula_rif').val();
        $.post('index.php?pagina=clientes', {buscar}, function(response){
            if(response != ''){
                alert('El cliente ya se encuentra registrado');
            }
        },'json');
    });
    $(document).ready(function() {
        // Verifica si el valor 'abrirModal' está en localStorage
        if (localStorage.getItem('abrirModal') === 'true') {
            $('#ventaModal').modal('show');
            localStorage.removeItem('abrirModal');
        }
    });
</script>
<!-- =======================
FIN REGISTRAR CLIENTES
============================= -->

<!-- =======================
MODAL CONFIRMAR ELIMINAR 
============================= -->
<div class="modal fade" id="anularventa" tabindex="-1" aria-labelledby="anularventaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anularventaLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="anumodal" method="post"> 
                <p>¿Está seguro que desea eliminar la venta nro: <span id="codv"></span>?</p>
                <input type="hidden" id="cventa" name="cventa"> 
                <input type="hidden" id="statusv" name="statusv">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" form="anumodal" class="btn btn-danger" id="confirmDelete" name="anular">Eliminar</button>
        </div>
        </div>
    </div>
</div>
<?php if (isset($anular)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $anular["title"]; ?>',
            text: '<?php echo $anular["message"]; ?>',
            icon: '<?php echo $anular["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'venta';
            }
        });
    </script>
<?php endif; ?>





<script>
/* =======================
SCRIPT DE VENTA
============================= */


        // Cuando se haga clic en "Realizar Venta" en el modal de Ver Venta
        $('#realizarVentaBtn').click(function() {
            $('#pagoModal').modal('show');

            
            var cliente = $('#cliente').val(); //cargar el nombre del cliente
            $('#cliente-resumen').val(cliente);
        });

        
        /*$('#finalizarPagoBtn').click(function() {
            
            alert('Pago Finalizado');
            
            $('#resumenVentaModal').modal('hide');
            $('#ventaModal').modal('hide'); 
        });*/



var productoIndex = 1; 

// Función para agregar una nueva fila a la tabla
function crearfila(index) {
    return `
        <tr id="fila${index}">
            <td>
                <input type="text" class="form-control" id="codigoProducto${index}" name="productos[${index}][codigo]" placeholder="Código del producto">
                <input type="hidden" class="form-control" id="tipoProducto${index}">
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" id="nombreProducto${index}" name="productos[${index}][nombre]" placeholder="Nombre del producto">
                    <div id="lista-productos${index}" class="list-group" style="position: absolute; z-index: 1000;"></div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="mostrarProductos()">+</button>
                    </div>
                </div>
            </td>
            <td>
                <input type="hidden" class="form-control" id="stockproducto${index}" step="0.001">
                <div class="input-group">
                    <input type="number" class="form-control" name="productos[${index}][cantidad]" id="cantidadProducto${index}" step="0.001" onchange="calcularTotal(${index})">
                    <div class="invalid-feedback" style="display: none;"></div>
                    <div class="input-group-append">
                        <span id="unidadm${index}" class="input-group-text" value=" "></span>
                    </div>
                </div>
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" id="precioProducto${index}" name="productos[${index}][precio]" placeholder="Precio" onchange="calcularTotal(${index})">
                    <div class="input-group-append">
                        <span class="input-group-text">Bs</span>
                    </div>
                </div>
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" id="total${index}" name="productos[${index}][total]" placeholder="Total" readonly>
                    <div class="input-group-append">
                        <span class="input-group-text">Bs</span>
                    </div>
                </div>
            </td>
            <td>
                <button type="button" class="btn-sm btn-danger" onclick="eliminarFila(${index})">&times;</button>
            </td>
        </tr>
    `;
    
}

function agregarFila() {
    var nuevaFila = crearfila(productoIndex);
    $('#ventaProductosBody').append(nuevaFila);
    productoIndex++; 
}

function inicializarFilas() {
    for (let i = 1; i <= 1; i++) {
        agregarFila();
    }
}

$(document).ready(function() {
    inicializarFilas(); 
});

function eliminarFila(index) {
    // Eliminar la fila del DOM usando el identificador de fila
    var fila = document.getElementById(`fila${index}`);
    if (fila) {
        fila.remove();
    }
    calcularTotal();
}

$(document).ready(function() {

    function showError(selector, message) {
        $(selector).addClass('is-invalid');
        $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message.toUpperCase()).css({
            'display': 'block',
            'color': 'red',
        });
    }
    function hideError(selector) {
        $(selector).removeClass('is-invalid');
        $(selector).next('.invalid-feedback').css('display', 'none');
    }

    $('[id^=cantidadProducto]').on('input', function() {
        var inputId = $(this).attr('id');
        var index = inputId.replace('cantidadProducto', ''); // Extrae el índice de la cantidad
        var cantidad = parseFloat($(this).val()) || 0;
        var stock = parseFloat($('#stockproducto' + index).val()) || 0;

        if (cantidad > stock) {
            showError('#' + inputId, 'stock insuficiente');
        } else {
            hideError('#' + inputId);
        }
    });


});


// Calcular el total de cada fila
function calcularTotal(index) {
    var cantidad = parseFloat($(`[name="productos[${index}][cantidad]"]`).val()) || 0;
    var precio = parseFloat($(`[name="productos[${index}][precio]"]`).val()) || 0;
    var total = cantidad * precio;
    $(`[name="productos[${index}][total]"]`).val(total.toFixed(2));

    actualizarResumen(); 
}

/*function mostrarProductos() {
    alert('Mostrar lista de productos');
}*/

// Función para actualizar el resumen de venta
function actualizarResumen() { 
    var subtotal = 0;
    var exento = 0;
    var baseImponible = 0;
    var iva = 0;

    
    for (var i = 1; i < productoIndex; i++) {
        var totalProducto = parseFloat($('#total' + i).val()) || 0;

        
        console.log('Total del producto ' + i + ':', totalProducto);

        
        if (isNaN(totalProducto)) {
            totalProducto = 0;
        }

        subtotal += totalProducto;

        var tipoProducto = parseFloat($('#tipoProducto' + i).val());

    if (tipoProducto === 1) {
            exento += totalProducto;
        } else if (tipoProducto === 2) {
            baseImponible += totalProducto;
        }
    }
    iva = baseImponible * 0.16;
    var totalGeneral = subtotal + iva;

    // Verificar los valores calculados para el resumen (para depuración)
    console.log('Subtotal:', subtotal);
    console.log('Exento:', exento);
    console.log('Base Imponible:', baseImponible);
    console.log('IVA:', iva);
    console.log('Total General:', totalGeneral);

    
    $('#subtotal').text(subtotal.toFixed(2));
    $('#exento').text(exento.toFixed(2));
    $('#base-imponible').text(baseImponible.toFixed(2));
    $('#iva').text(iva.toFixed(2));
    
    
    $('#total-span').text(totalGeneral.toFixed(2));
    $('#total-general').val(totalGeneral.toFixed(2));
}


$('#cedula-rif').blur(function (e){
        var buscar=$('#cedula-rif').val();
        $.post('index.php?pagina=clientes', {buscar}, function(response){
            var nombre=response['nombre']+" "+response['apellido'];
            var telefono=response['telefono'];
            var apellido=response['apellido'];
            var codigo=response['cod_cliente'];

            var modal = $('#ventaModal');
            modal.find('.modal-body #numero-cliente').val(telefono);
            modal.find('.modal-body #nombre-cliente').val(nombre);
            modal.find('.modal-body #cod_cliente').val(codigo);
        },'json');
    });


    $(document).ready(function() {
    // Evento 'input' en los campos de productos dinámicos
    $(document).on('input', '[id^=nombreProducto]', function() {
        var inputId = $(this).attr('id');
        var index = inputId.replace('nombreProducto', ''); // Obtener el índice del campo
        var query = $(this).val(); // Valor ingresado por el usuario

        if (query.length > 2) {
            $.ajax({
                url: 'index.php?pagina=venta',
                method: 'POST',
                data: {buscar: query},
                dataType: 'json', 
                success: function(data) {
                    var listaProductos = $('#lista-productos' + index);
                    listaProductos.empty(); // Limpiar resultados anteriores

                    if (data.length > 0) {
                        // Recorrer los productos recibidos y mostrar nombre + código + precio concatenados
                        $.each(data, function(key, producto) {
                            var costo = parseFloat(producto.costo);
                            var precioVenta = costo + (costo * producto.porcen_venta / 100);
                            listaProductos.append(
                                '<a href="#" class="list-group-item list-group-item-action producto-item" ' +
                                'data-nombre="'+producto.producto_nombre+'" ' +
                                'data-tipo="'+producto.excento+'" ' +
                                'data-codigo="'+producto.cod_presentacion+'" ' +
                                'data-marca="'+producto.marca+'" ' +
                                'data-stock="'+producto.total_stock+'" ' +
                                'data-unidad="'+producto.tipo_medida+'" ' +
                                'data-precio="'+precioVenta+'">' +
                                producto.producto_nombre + ' - ' + producto.marca + ' - ' + producto.presentacion+' - '+precioVenta +' - '+producto.total_stock+ '</a>'
                            );
                        });
                        listaProductos.fadeIn();
                    } else {
                        listaProductos.append('<p class="list-group-item">No se encontraron productos</p>');
                    }
                }
            });
        } else {
            $('#lista-productos' + index).fadeOut(); // Ocultar la lista si no hay suficientes caracteres
        }
    });

    // Cuando el usuario selecciona un producto
    $(document).on('click', '.producto-item', function(){
        var selectedProduct = $(this).data('nombre'); 
        var codigo = $(this).data('codigo'); 
        var precio = $(this).data('precio'); 
        var tipo = $(this).data('tipo');
        var unidad = $(this).data('unidad');
        var stock=$(this).data('stock');
        var cant=1;


        var inputId = $(this).closest('.list-group').prev('input').attr('id');
        var index = inputId.replace('nombreProducto', ''); // Extrae el índice del campo
        $('#' + inputId).val(selectedProduct); 

        $('#codigoProducto' + index).val(codigo); 
        $('#tipoProducto' + index).val(tipo);
        $('#precioProducto' + index).val(precio);
        $('#stockproducto'+index).val(stock);
        $('#unidadm'+index).text(unidad);
        $('#cantidadProducto' + index).val(cant).trigger('change');
        $(this).closest('.list-group').fadeOut(); 
    });
});

$(document).ready(function() {
    // Obtener la fecha y hora actual
    var now = new Date();
    var fecha = now.getFullYear() + '-' +
            String(now.getMonth() + 1).padStart(2, '0') + '-' +
            String(now.getDate()).padStart(2, '0');

// Formatea la hora en el formato HH:MM:SS
var hora=String(now.getHours()).padStart(2, '0') + ':' +
        String(now.getMinutes()).padStart(2, '0') + ':' +
        String(now.getSeconds()).padStart(2, '0');
    
    var fechaHora = fecha + ' ' + hora;
    $('#fecha-hora').val(fechaHora);
});

$(function() {//inicio de los alertas peque;os
    var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
    });

$(document).ready(function() {
    $('#ventamodal').on('submit', function(event) {
        event.preventDefault();
        
        // Serializa los datos del formulario
        var datosVenta = $(this).serialize();
        console.log('form:', datosVenta);

        // Enviar la solicitud AJAX
        $.ajax({
            type: 'POST',
            url: 'index.php?pagina=venta',
            data: datosVenta,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#ventaModal').modal('hide');
                    $('#nro-venta').val(response.cod_venta);
                    $('#nombre_cliente').val(response.cliente); 
                    $('#fecha_venta').val(response.fecha);
                    $('#total-pago').text(response.total+ ' Bs');
                    $('#monto_pagar').val(response.total);

                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    })

                    console.log('cod:', response.cod_venta);
                    console.log('total:', response.total);

                    // Abre el modal de pago
                    $('#pagoModal').modal('show');
                } else {
                    alert('Error al registrar la venta: ' + response.message);
                }
            },
            error: function(jqXHR, xhr, status, error) {
                console.error('Estado:', status);
                console.error('Error:', error);
                console.error('Respuesta del servidor:', xhr.responseText);
                console.log('Response Text:', jqXHR.responseText);
                alert('Hubo un problema al registrar la venta. Inténtalo de nuevo.');
            }
        });
    });
});

});//fin de los alertas peque;os

function calcularTotalpago() {
    let totalBs = 0;

    // 1. Procesar las entradas que ya están en bolívares (sin conversión)
    document.querySelectorAll('.monto-bs:not(.monto-con)').forEach(function(input) {
        let montoBs = parseFloat(input.value) || 0;
        totalBs += montoBs;  // Sumar cada monto en bolívares directo
    });

    // 2. Procesar las entradas en divisas (convertirlas a bolívares)
    document.querySelectorAll('.monto-divisa').forEach(function(inputDivisa) {
        let index = inputDivisa.id.split('-').pop();  // Obtener el índice de la fila actual

        // Obtener el monto en divisa de la fila
        let montoDivisa = parseFloat(inputDivisa.value) || 0;

        // Obtener la tasa de conversión de la misma fila
        let tasaConversion = parseFloat(document.getElementById('tasa-conversion-' + index).value) || 1;

        // Calcular el monto en bolívares
        let montoConvertidoBs = montoDivisa * tasaConversion;

        // Actualizar el campo de bolívares convertido en esa fila
        document.getElementById('monto-bs-con-' + index).value = montoConvertidoBs.toFixed(2);

        // Sumar al total de bolívares
        totalBs += montoConvertidoBs;
    });

    // 3. Mostrar el total en el campo "Monto Pagado"
    document.getElementById('monto_pagado').value = totalBs.toFixed(2);

    // 4. Calcular y mostrar la diferencia con el monto a pagar
    let montoPagar = parseFloat(document.getElementById('monto_pagar').value) || 0;
    let diferencia = montoPagar - totalBs;
    document.getElementById('diferencia').value = diferencia.toFixed(2);
}

$('#pagoModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var codigo = button.data('codventa');
    var total = button.data('totalv');
    var fecha = button.data('fecha');
    var nombre = button.data('nombre');
    // Modal
    var modal = $(this);
    modal.find('.modal-body #nro-venta').val(codigo);
    modal.find('.modal-body #monto_pagar').val(total);
    modal.find('.modal-body #total-pago').text(total+ 'Bs');
    modal.find('.modal-body #fecha_venta').val(fecha);
    modal.find('.modal-body #nombre_cliente').val(nombre);
});

$('#abonoModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var codigo = button.data('codventa');
    var codp=button.data('codpago');
    var total = button.data('totalv');
    var monto=button.data('montop');
    var fecha = button.data('fecha');
    var nombre = button.data('nombre');
    var mpagar=total-monto;
    console.log(button.data('codventa'));
    // Modal
    var modal = $(this);
    modal.find('.modal-body #nro-venta1').val(codigo);
    modal.find('.modal-body #monto_pagar1').val(mpagar.toFixed(2));
    modal.find('.modal-body #total-venta1').text(total+ 'Bs');
    modal.find('.modal-body #t-parcial').val(mpagar);
    modal.find('.modal-body #total-pago1').text(mpagar.toFixed(2)+ 'Bs');
    modal.find('.modal-body #fecha_venta1').val(fecha);
    modal.find('.modal-body #nombre_cliente1').val(nombre);
    modal.find('.modal-body #codigop').val(codp);

});

function calcularTotalpago1() {
    let totalBs = 0;

    // 1. Procesar las entradas que ya están en bolívares (sin conversión)
    document.querySelectorAll('.monto-bs1:not(.monto-con1)').forEach(function(input) {
        let montoBs = parseFloat(input.value) || 0;
        totalBs += montoBs;  // Sumar cada monto en bolívares directo
    });

    // 2. Procesar las entradas en divisas (convertirlas a bolívares)
    document.querySelectorAll('.monto-divisa1').forEach(function(inputDivisa) {
        let index = inputDivisa.id.split('-').pop();  // Obtener el índice de la fila actual

        // Obtener el monto en divisa de la fila
        let montoDivisa = parseFloat(inputDivisa.value) || 0;

        // Obtener la tasa de conversión de la misma fila
        let tasaConversion = parseFloat(document.getElementById('tasa-conversion1-' + index).value) || 1;

        // Calcular el monto en bolívares
        let montoConvertidoBs = montoDivisa * tasaConversion;

        // Actualizar el campo de bolívares convertido en esa fila
        document.getElementById('monto-bs-con-1' + index).value = parseFloat(montoConvertidoBs.toFixed(2));

        // Sumar al total de bolívares
        totalBs += montoConvertidoBs;
    });

    // 3. Mostrar el total en el campo "Monto Pagado"
    document.getElementById('monto_pagado1').value = totalBs.toFixed(2);

    // 4. Calcular y mostrar la diferencia con el monto a pagar
    let montoPagar = parseFloat(document.getElementById('monto_pagar1').value) || 0;
    let diferencia = montoPagar - totalBs;
    document.getElementById('diferencia1').value = diferencia.toFixed(2);
}


$('#anularventa').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var codigo = button.data('codventa');
    var status=button.data('status');
    // Modal
    var modal = $(this);
    modal.find('.modal-body #cventa').val(codigo);
    modal.find('.modal-body #statusv').val(status);
    modal.find('.modal-body #codv').text(codigo);

});

</script>
