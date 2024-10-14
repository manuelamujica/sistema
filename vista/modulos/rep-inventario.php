<?php
require_once 'controlador/reporte.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Reporte Inventario</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                        <li class="breadcrumb-item active">Reporte Inventario</li>
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
                            <ul class="nav nav-tabs" id="tabContent" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="producto-tab" data-toggle="tab" href="#producto" role="tab">Productos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="carga-tab" data-toggle="tab" href="#carga" role="tab">Carga de Productos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="descarga-tab" data-toggle="tab" href="#descarga" role="tab">Descarga de Productos</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="row mb-2">
                                    <!-- Creo que debe ser por cada una de las tablas... -->
                                    <!-- Date and time range -->
                                    <button type="button" class="btn btn-default float-rigth" id="daterange-btn">
                                        <span><i class="fa fa-calendar"></i> Rango de fecha
                                    </span>
                                        <i class="fas fa-caret-down"></i>
                                    </button>
                                </div> 
                                <!-- Productos -->
                                <div class="tab-pane fade show active" id="producto" role="tabpanel">
                                    <table id="producto-table" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Marca</th>
                                                <th>Presentacion</th>
                                                <th>Categoría</th>
                                                <th>Costo</th>
                                                <th>IVA</th>
                                                <th>Precio de venta</th>
                                                <th>Stock</th>
                                                <th>Detalle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <?php
                                            foreach ($productos as $producto){
                                                ?>
                                                <td> <?php echo $producto["cod_producto"] ?></td>
                                                <td> <?php echo $producto["nombre"] ?></td>
                                                <td> <?php echo $producto["marca"] ?></td>
                                                <td> <?php echo $producto["presentacion"] ?></td>
                                                <td> <?php echo $producto["cat_nombre"] ?></td>
                                                <td> <?php echo $producto["costo"] ?></td>
                                                <td> <?php echo $producto["excento"] ?></td>
                                                <td><?php $precioVenta = ($producto["porcen_venta"] / 100 + 1)*$producto["costo"]; echo $precioVenta?>
                                                </td>
                                                <td>Stock total</td>
                                                <!-- Detalle de producto -->
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-sm" style="position: center;">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            
                                <!-- Carga de Productos-->
                                <div class="tab-pane fade" id="carga" role="tabpanel">
                                    <table id="carga-table" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Producto</th>
                                                <th>Cantidad Cargada</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Aquí van los datos de carga de productos -->
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Descarga de Productos-->
                                <div class="tab-pane fade table-responsive" id="descarga" role="tabpanel">
                                    <table id="descarga-table" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Producto</th>
                                                <th>Cantidad Descargada</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Aquí van los datos de descarga de productos -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="vista/dist/js/modulos-js/rep-inventario.js"></script>
