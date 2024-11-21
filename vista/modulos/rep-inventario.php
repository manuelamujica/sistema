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
                                <!--===========  PRODUCTOS  ==============-->
                                <div class="tab-pane fade show active" id="producto" role="tabpanel">
                                    <div class="row mb-2">
                                        <form action="index.php?pagina=productoexcel" method="post" target="_blank" class="d-inline">
                                            <button class="btn btn-success ml-2" name="excel" title="Generar excel" id="excel">Generar Excel</button>
                                        </form>
                                        <form action="index.php?pagina=productopdf" method="post" target="_blank" class="d-inline">
                                            <button class="btn btn-danger ml-2" name="pdf" title="Generar PDF" id="pdfc" type="submit">Generar PDF</button>
                                        </form>
                                        <!-- Select de categoría y botón de productos por categoría -->
                                        <div class="ml-auto d-flex">
                                            <form action="index.php?pagina=productocat" method="post" target="_blank" class="d-inline">
                                                <div class="form-group d-flex align-items-center">
                                                    <select class="form-control mr-2" name="codigocategoria" required>
                                                        <option value="" selected disabled>Seleccione una categoría</option>
                                                        <?php foreach($categoria as $cate): ?>
                                                            <option value="<?php echo $cate['cod_categoria']; ?>">
                                                                <?php echo $cate['nombre']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button class="btn btn-primary" name="categoria" title="Filtrar por categoría" id="categoria" type="submit">Filtrar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="productos" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Nombre</th>
                                                    <th>Marca</th>
                                                    <th>Presentación</th>
                                                    <th>Categoría</th>
                                                    <th>Costo</th>
                                                    <th>IVA</th>
                                                    <th>Precio de venta</th>
                                                    <th>Stock total</th>
                                                </tr>         
                                            </thead>
                                            <tbody>
                                            <!-- Tabla con los datos que se muestren dinamicamente -->
                                                <?php
                                                foreach ($productos as $producto){
                                                    ?>
                                                    <tr>
                                                        <td> <?php echo $producto["cod_presentacion"] ?></td>
                                                        <td> <?php echo $producto["nombre"] ?></td>
                                                        <td> <?php echo $producto["marca"] ?  $producto["marca"] : 'No disponible'?></td>
                                                        <td> <?php echo $producto["presentacion_concat"] ? $producto["presentacion_concat"] : 'No disponible' ?></td>
                                                        <td> <?php echo $producto["cat_nombre"] ?></td>
                                                        <td> <?php echo $producto["costo"] ?></td>
                                                        <td> <?php if($producto["excento"] == 1){
                                                            echo 'E';
                                                        }  else{
                                                            echo 'G';
                                                        }
                                                        ?>
                                                        </td>
                                                        <td>
                                                        <?php 
                                                        if($producto["excento"] == 1){
                                                            $precioVenta = ($producto["porcen_venta"] / 100 + 1) * $producto["costo"];
                                                            echo number_format($precioVenta, 2, '.', '')." Bs"; //2 decimales . se redondea 
                                                        }  else{
                                                            $costoiva = $producto["costo"] * 1.16;
                                                            $precioVenta = ($producto["porcen_venta"] / 100 + 1) * $costoiva;
                                                            echo number_format($precioVenta, 2, '.', '')." Bs"; //2 decimales . se redondea 
                                                        }
                                                        ?>
                                                        </td>
                                                        <td><?php echo $producto["stock_total"] ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--===========  CARGA  ==============-->
                                <div class="tab-pane fade" id="carga" role="tabpanel">
                                    <!-- Formulario de filtrado -->
                                    <div class="row mb-2">
                                        <form action="index.php?pagina=cargapdf" method="post" target="_blank" class="d-inline" id="form1">
                                            <!-- Campos ocultos para las fechas -->
                                            <input type="hidden" name="fechaInicio1" id="fechaInicio1" value="<?php echo date('Y-m-d') ?>">
                                            <input type="hidden" name="fechaFin1" id="fechaFin1" value="<?php echo date('Y-m-d') ?>">

                                            <button type="button" class="btn btn-default float-right" id="daterangec-btn">
                                                <span><i class="fa fa-calendar"></i> Rango de fecha</span>
                                                <i class="fas fa-caret-down"></i>
                                            </button>

                                            <button class="btn btn-danger ml-2" name="pdf1" title="Generar PDF" id="pdf1" type="submit">PDF</button>
                                            <button type="button" class="btn btn-secondary ml-2" id="resetc-btn">Restablecer Rango</button>
                                        </form>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="carga" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Fecha</th>
                                                    <th>Descripción</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad cargada</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($datos as $dato) {
                                                ?>
                                                    <td><?php echo $dato['cod_carga'] ?></td>
                                                    <td><?php echo $dato['fecha'] ?></td>
                                                    <td><?php echo $dato['descripcion'] ?></td>
                                                    <td><?php echo $dato['nombre'] . " en " . $dato['presentacion'] ?></td>
                                                    <td><?php echo $dato['cantidad'] ?></td>
                                            
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!--===========  DESCARGA  ==============-->
                                <div class="tab-pane fade table-responsive" id="descarga" role="tabpanel">
                                    <div class="row mb-2">
                                        <form action="index.php?pagina=descargapdf" method="post" target="_blank" class="d-inline" id="form1"> 
                                            <!-- Campos ocultos para las fechas -->
                                            <input type="hidden" name="fechaInicio" id="fechaInicio" value="<?php echo date('Y-m-d') ?>">
                                            <input type="hidden" name="fechaFin" id="fechaFin" value="<?php echo date('Y-m-d') ?>">
                                            <button type="button" class="btn btn-default ml-2" id="daterange-btn">
                                                <span><i class="fa fa-calendar"></i> Rango de fecha</span>
                                                <i class="fas fa-caret-down"></i>
                                            </button>
                                            <button type="button" class="btn btn-secondary ml-2" id="reset-btn">Restablecer fecha</button>
                                            <button class="btn btn-danger ml-2" name="pdf" title="Generar PDF" id="pdfD" type="submit">Generar PDF</button>
                                        </form>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="descarga-table" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Descripcion</th>
                                                    <th>Fecha</th>
                                                    <th>Producto</th> 
                                                    <th>Lote</th>
                                                    <th>Cantidad descargada</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($descarga as $d){?>
                                                    <tr>
                                                        <td> <?php echo $d["cod_descarga"] ?></td>
                                                        <td> <?php echo $d["descripcion"] ?></td>
                                                        <td> <?php echo $d["fecha"] ?></td>
                                                        <td> <?php echo $d["producto_concat"] ?></td>
                                                        <td> <?php echo $d["lote"] ?></td>
                                                        <td><?php echo $d["cantidad"] ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
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
    
<script src="vista/dist/js/modulos-js/rep-inventario.js"></script>
