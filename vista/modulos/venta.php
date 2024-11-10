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
    <div class="modal-dialog modal-xl" >
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
                                        <input type="hidden" id="cod_cliente" name="cod_cliente" required>
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
                                    <?php 
                                    if(!empty($consulta)){
                                        $ultimo=end($consulta); 
                                        $nueva=$ultimo['codigov']+1;
                                    }else{
                                        $nueva=1;
                                    }
                                    ?>
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

<script src="vista/dist/js/modulos-js/ventas.js"></script>



