<!-- EN REVISIOON EN EL CONTROLADOR NO QUIERE REGISTRAR LA CARGA :(  
 COMENTARIO 2/11/2024-->
<?php require_once 'controlador/carga.php' ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- MODULO TRANSACIONAL DE CARGA DE PRODUCTOS EN AJUSTE DE INVENTARIO  -->
                    <h1>Carga de productos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                        <li class="breadcrumb-item active">Carga de productos</li>
                    </ol>
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
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarCarga">Registrar Carga</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <!-- MOSTRAR EL REGISTRO DE CARGA DE PRODUCTOS -->
                                <table id="carga" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Fecha</th>
                                            <th>Descripción</th>
                                            <th>Detalles</th>

                                            <!--<th>Cantidad total</th>-->
                                            <!-- ##### CUAL DEJO? CANTIDAD CARGADA O CANTIDAD TOTAL(STOCK) ##### -->
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($carga as $dato) {
                                        ?>
                                            <?php if ($dato['status'] != 2): ?>

                                                <td><?php echo $dato['cod_carga'] ?></td>
                                                <td><?php echo $dato['fecha'] ?></td>
                                                <td><?php echo $dato['descripcion'] ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-sm" style="position: center;" data-toggle="modal" data-target="#detallemodal" title="Ver detalle"
                                                        data-codigo="<?php echo $dato["cod_carga"]; ?>">
                                                        <i class="fas fa-plus"></i>
                                                    </button>

                                                </td>

                                                <!--<td><?php //echo $dato['stock'] 
                                                        ?></td>-->
                                                <td>
                                                    <?php if ($dato['status'] == 1): ?>
                                                        <span class="badge bg-success">Cargado</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">No disponible</span>
                                                    <?php endif; ?>
                                                </td>


                            </div>

                            </tr>
                        <?php endif; ?>
                    <?php } ?>
                    </tbody>
                    </table>
                        </div>
                    </div>
                </div>

                <!-- =======================
MODAL REGISTRAR CARGA CON EXITO
============================= -->

                <div class="modal fade" id="modalregistrarCarga" tabindex="-1" aria-labelledby="modalregistrarCargaLabel">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="background: #db6a00 ;color: #ffffff; ">
                                <h5 class="modal-title" id="exampleModalLabel">Registrar Carga</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <form id="formregistrarCarga" method="post">
                                    <!--   FECHA      -->
                                    <div class="form-group">
                                        <label for="fecha">Fecha <span class="text-danger" style="font-size: 20px;"> *</span> </label>
                                        <!-- TOOLTIPS-->
                                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa Una fecha donde sucedio la carga, por ejemplo: 01/08/2001">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        <script>
                                            $(function() {
                                                $('[data-toggle="tooltip"]').tooltip();
                                            });
                                        </script>
                                        <input type="datetime-local" name="fecha" id="fecha" class="form-control">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                    <!--   DESCRIPCIÓN  -->
                                    <div class="form-group">
                                        <label for="descripcion">Descripción <span class="text-danger" style="font-size: 20px;"> *</span> </label>
                                        <!-- TOOLTIPS-->
                                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa el motivo por el cual existe la carga, por ejemplo: Compra sin factura">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        <script>
                                            $(function() {
                                                $('[data-toggle="tooltip"]').tooltip();
                                            });
                                        </script>
                                        <input type="text" class="form-control" id="descripcion" name="descripcion" maxlength="100">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>

                                    <div class="table-responsive mt-4">
                                        <table id="productosCarga" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Producto <span class="text-danger" style="font-size: 20px;"> *</span></th>
                                                    <th>Cantidad <span class="text-danger" style="font-size: 20px;"> *</span></th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!--  filas dinámicas  -->
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-secondary" id="add-product">Agregar otro producto</button>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <small class="text-danger">Los campos con * son obligatorios.</small>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

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
                        window.location = 'carga';
                    }
                });
            </script>
        <?php endif; ?>



        <!-- =============================
    MODAL MOSTRAR DETALLES 
================================== -->
        <div class="modal fade" id="detallemodal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Detalle de productos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="productos" class="table table-bordered table-striped table-hover" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Presentacion del Producto</th>                                     
                                                <th>cantidad cargada</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="detalleBody">
                                            <!-- Los detalles se cargarán aquí -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- registrar DETALLEP -->
        <div class="modal fade" id="modalregistrardetallep">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background: #db6a00 ;color: #ffffff; ">
                        <h4 class="modal-title">Añadir detalles</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" method="post" id="formRegistrarDetalle">
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="cod_presentacion">Código del Producto:</label>
                                <input type="text" class="form-control" name="cod_presentacion" id="cod_presentacion" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="fecha_vencimiento">Fecha de vencimiento</label>
                                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                            </div>
                            <div class="form-group">
                                <label for="lote">Lote</label>
                                <input type="text" class="form-control" id="lote" name="lote" placeholder="Ej: 122-0G12" maxlength="20">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" name="registrarD" value="">Guardar</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </section>
</div>

<script src="vista/dist/js/modulos-js/carga.js"></script>
