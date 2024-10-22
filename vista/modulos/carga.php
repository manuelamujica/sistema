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
                          <!-- BotĆ³n para ventana modal -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarCarga">Registrar Carga</button>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">
                                   <!-- MOSTRAR EL REGISTRO DE CARGA DE PRODUCTOS -->
                            <table id="carga" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>CĆ³digo</th>
                                        <th>Fecha</th>
                                        <th>DescripciĆ³n</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($datos as $dato){
                                    ?>
                                    <?php if($dato['status'] != 2): ?>
                                    
                                        <td><?php echo $dato['cod_carga']?></td>
                                        <td><?php echo $dato['fecha']?></td>
                                        <td><?php echo $dato['descripcion']?></td>
                                        <td><?php echo $dato['nombre']?></td>
                                        <td><?php echo $dato['cantidad']?></td>
                                        <td><?php echo $dato['stock']?></td>
                                        <td>
                                            <?php if ($dato['status']==1): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
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

                <div class="modal fade" id="modalregistrarCarga" tabindex="-1" aria-labelledby="modalregistrarCargaLabel" aria-hidden="true">
                    <div class="modal-dialog">
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
                                        <label for="fecha">Fecha</label>
                                        <input type="datetime-local" class="form-control" name="fecha" id="fecha" required>
                                    </div>
                                            <!--   DESCRIPCIĆ“N  --> 
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <input type="text" class="form-control" name="descripcion" required>
                                    </div> 
                                    
                                    <!-- Contenedor para productos -->
                                    <div id="product-container">
    <div class="product-entry">

        <div class="form-group">
            <label for="cod_producto">Producto:</label>
            <select name="cod_producto[]" class="cod_detallep form-control" id="producto">
                <option selected="selected" value=""></option>
                <?php foreach($productos as $dato) { ?>
                    <option value="<?php echo $dato['cod_producto']; ?>" class="product-option"><?php echo $dato['nombre']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" class="form-control" name="cantidad[]" required>
        </div>
    </div>
</div>

        <button type="button" class="btn btn-secondary" id="add-product">Agregar otro producto</button>
                            </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary" id="guardar" name="guardar">Guardar</button>
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