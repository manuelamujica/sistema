<!-- MODULO DE compraA / SOLO VISta   -->
<?php require_once 'controlador/compras.php'; ?>



<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Compras</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                        <li class="breadcrumb-item active">Compras</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Formulario y tabla de compras -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <!-- Botones  de registar en línea -->
                            <button name="reg" class="btn btn-primary mr-2" data-toggle="modal" data-target="#modalcom">
                                Registrar compra
                            </button>
                        </div>
                        <br>
                        <div class="card-body">
                            <!-- Tabla de compra-->
                            <div class="table-responsive">
                                <table id="compras" class="table table-bordered table-striped table-hover datatable1" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Proveedor</th>
                                            <th>Sub total</th>
                                            <th>Fecha</th>
                                            <th>total</th>
                                            <th>status</th>
                                            <th>Info</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- registro de puro de compra-->
                                        <?php foreach ($compra as $compras) {
                                        ?>
                                            <?php if ($compras['status'] != 2): ?>
                                                <tr>
                                                    <td><?php echo $compras["cod_compra"] ?></td>
                                                    <td><?php echo $compras['razon_social'] ?></td>
                                                    <td><?php echo $compras['subtotal'] ?></td>
                                                    <td><?php echo $compras["fecha"] ?></td>
                                                    <td><?php echo $compras["total"] ?></td>
                                                    <td>
                                                        <?php if ($compras['compra_status'] == 1): ?>
                                                            <span class="badge bg-success">Registrada</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Anulada</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" style="position: center;" data-toggle="modal" data-target="#detallemodal" title="Ver detalle"
                                                        data-codigo="<?= $compras["cod_compra"];?>"
                                                        data-nombre="<?= $compras['razon_social'] ?>"
                                                        data-fecha="<?= $compras["fecha"] ?>"
                                                        data-total="<?= $compras["total"] ?>">
                                                        <i class="fas fa-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>


            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->



<!-- Registrar modal  compra -->
<div class="modal fade" id="modalcom" tabindex="-1" role="dialog" aria-labelledby="compraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 96%;">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                <h5 class="modal-title" id="compraModalLabel">Registrar compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formcompra" method="post">
                    <div class="container-fluid">
                        <!-- Información del Proveedor -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cedula_rif">RIF</label>
                                    <div class="row">
                                        <div class="col-md-11">
                                            <input type="text" class="form-control form-control-sm" id="rif-r" name="rif" placeholder="RIF" required>
                                            <input type="hidden" id="cod_prov" name="cod_prov" required>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#modalProv">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="telefono">Número de teléfono</label>
                                    <input type="text" class="form-control form-control-sm" id="telefono" name="telefono" placeholder="Número del proveedor" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="razon-social">Razón social</label>
                                    <input type="text" class="form-control form-control-sm" id="razon-social" name="razon_social" placeholder="Razón social" readonly>
                                </div>
                                <div class="form-row align-items-end">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha-hora">Fecha y Hora</label>
                                            <input type="text" class="form-control form-control-sm" id="fecha-hora" name="fecha" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="selectDivisa">Selecciona la divisa:</label>
                                            <select id="selectDivisa" class="form-control form-control-sm">
                                                <?php foreach($opciones as $divisa){ ?>
                                                <option data-cod="<?= $divisa['cod_divisa'] ?>"
                                                data-tasa="<?= $divisa['tasa'] ?>"
                                                data-abreviatura="<?= $divisa['abreviatura'] ?>" <?= $divisa['cod_divisa']==1 ? 'selected' : '' ?>>
                                                <?= $divisa['nombre'].' - '.$divisa['abreviatura'] ?></option>
                                                <?php }?>
                                                <!-- Agrega más divisas si es necesario -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de productos -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th>Fecha de vencimiento</th>
                                        <th>Lote</th>
                                        <th>Cantidad</th>
                                        <th class="col-divisa" style="display: none;">Precio unitario (<span id="labelDivisa"></span>)</th>
                                        <th>Precio unitario (Bs)</th>
                                        <th style="width: 7%;">IVA</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="ProductosBody">
                                    <!-- Aquí se agregarán dinámicamente las filas de productos -->
                                </tbody>
                            </table>
                        
                        <!-- Botón para agregar nuevo producto -->
                        <button type="button" class="btn btn-success" onclick="agregarFila()">Agregar Producto</button>

                        
                        <div class="card card-outline card-primary float-right" style="width: 300px;">
                            <div class="card-body">
                                <p>Subtotal: Bs <span id="subtotal" class="text-bold">0.00</span></p>
                                <p>Exento: Bs <span id="exento" class="text-bold">0.00</span></p>
                                <p>Base imponible: Bs <span id="base-imponible" class="text-bold">0.00</span></p>
                                <p>IVA (16%): Bs <span id="iva" class="text-bold">0.00</span></p>
                                <p class="text-bold">TOTAL: Bs <span id="total-span" class="text-bold">0.00</span></p>
                                <input type="hidden" id="total-general" name="total_general">
                                <input type="hidden" id="subt" name="subtotal">
                                <input type="hidden" id="impuesto_total" name="impuesto_total">

                            </div>
                        </div>
                    </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" form="formcompra" name="registrar">Guardar</button>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($registrar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $registrar["title"]; ?>',
            icon: '<?php echo $registrar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((resul) => {
            if (resul.isConfirmed) {
                window.location = 'compras';
            }
        });
    </script>
<?php endif; ?>
<!-- registrar compra-->

<div class="modal fade" id="detallemodal" tabindex="-1" aria-labelledby="detalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                <h5 class="modal-title" id="detalleLabel">Informacion de la compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nro_venta">Nro de compra</label>
                            <input type="text" class="form-control" id="nro-compra" name="nro_venta" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_cliente">Razon social</label>
                            <input type="text" class="form-control" id="r_social" name="nombre_cliente" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_venta">Fecha de la compra</label>
                            <input type="text" class="form-control" id="fecha_compra" name="fecha_venta" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Fecha de vencimiento</th>
                                    <th>Lote</th>
                                    <th>Cantidad</th>
                                    <th>Precio unitario (Bs)</th>
                                    <!--<th style="width: 7%;">IVA</th>-->
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="detalleBody">
                                <!-- Aquí se agregarán dinámicamente las filas de productos -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card card-outline card-primary float-right" style="width: 300px;">
                        <div class="card-body">
                            <p class="text-bold">TOTAL: Bs <span id="total_compra" class="text-bold">0.00</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="vista/dist/js/modulos-js/compras.js"></script>