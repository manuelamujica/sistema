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
                                <!-- Productos -->
                                <div class="tab-pane fade show active" id="producto" role="tabpanel">

                                    <div class="row mb-2">
                                        <form action="index.php?pagina=productoexcel" method="post" target="_blank" class="d-inline">
                                            <button class="btn btn-success ml-2" name="excel" title="Generar excel" id="excel">Excel</button>
                                        </form>
                                        <form action="index.php?pagina=productopdf" method="post" target="_blank" class="d-inline" id="form">
                                            <button class="btn btn-danger ml-2" name="pdf" title="Generar PDF" id="pdfc" type="submit">PDF</button>
                                        </form>
                                    </div>
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
                                                foreach ($productos as $producto) {
                                                ?>
                                                    <td> <?php echo $producto["cod_producto"] ?></td>
                                                    <td> <?php echo $producto["nombre"] ?></td>
                                                    <td> <?php echo $producto["marca"] ?></td>
                                                    <td> <?php echo $producto["presentacion"] ?></td>
                                                    <td> <?php echo $producto["cat_nombre"] ?></td>
                                                    <td> <?php echo $producto["costo"] ?></td>
                                                    <td> <?php echo $producto["excento"] ?></td>
                                                    <td><?php $precioVenta = ($producto["porcen_venta"] / 100 + 1) * $producto["costo"];
                                                        echo $precioVenta ?>
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

                                </div>

                                </tr>
                            <?php } ?>
                            </tbody>
                            </table>
                            </div>

                            <!-- Descarga de Productos-->
                            <div class="tab-pane fade table-responsive" id="descarga" role="tabpanel">
                            <div class="row mb-2">
                                        <form action="index.php?pagina=descargapdf" method="post" target="_blank" class="d-inline" id="form1"> <!--  AL ESTAR TERMINADO  -->
                                            <!-- Campos ocultos para las fechas -->
                                            <input type="hidden" name="fechaInicio" id="fechaInicio" value="<?php echo date('Y-m-d') ?>">
                                            <input type="hidden" name="fechaFin" id="fechaFin" value="<?php echo date('Y-m-d') ?>">

                                            <button type="button" class="btn btn-default float-right" id="daterange-btn">
                                                <span><i class="fa fa-calendar"></i> Rango de fecha</span>
                                                <i class="fas fa-caret-down"></i>
                                            </button>

                                            <button class="btn btn-danger ml-2" name="pdf" title="Generar PDF" id="pdf" type="submit">PDF</button>
                                            <button type="button" class="btn btn-secondary ml-2" id="reset-btn">Restablecer Rango</button>
                                        </form>

                                    </div>
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
<script>
    $(document).ready(function() {
        $('#daterange-btn').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Rango Personalizado', // Cambia el texto aquí
                weekLabel: 'S',
                firstDay: 1
            },
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment().endOf('month')],
                'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        }, function(start, end) {
            $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            // Guardar fechas en campos ocultos
            $('#fechaInicio').val(start.format('YYYY-MM-DD'));
            $('#fechaFin').val(end.format('YYYY-MM-DD'));
        });
        // Restablecer el rango de fechas al hacer clic en el botón

        $('#form').on('submit', function(e) {
            const fechaInicio = $('#fechaInicio').val();
            const fechaFin = $('#fechaFin').val();

            // Convertir las fechas a objetos Date para comparación
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFin);
            // Validar que la fecha de inicio no sea posterior a la fecha de fin
            if (inicio > fin) {
                Swal.fire({
                        title: 'Error',
                        text: 'La fecha de inicio no puede ser posterior a la fecha de fin.',
                        icon: 'warning'
                    });
                e.preventDefault(); // Evitar que el formulario se envíe
                return;
            }else{
                Swal.fire({
                        title: 'Exito',
                        text: 'Reporte Generado.',
                        icon: 'success'
                    });
            }
        });
        // Restablecer el rango de fechas al hacer clic en el botón
        $('#reset-btn').on('click', function() {
            $('#fechaInicio').val('');
            $('#fechaFin').val('');
            $('#daterange-btn span').html('Rango de fecha'); // Cambia el texto del botón
        });

        $('#daterangec-btn').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Rango Personalizado', // Cambia el texto aquí
                weekLabel: 'S',
                firstDay: 1
            },
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment().endOf('month')],
                'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        }, function(start, end) {
            $('#daterangec-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            // Guardar fechas en campos ocultos
            $('#fechaInicio1').val(start.format('YYYY-MM-DD'));
            $('#fechaFin1').val(end.format('YYYY-MM-DD'));
        });

        $('#form1').on('submit', function(e) {
            const fechaInicio = $('#fechaInicio1').val();
            const fechaFin = $('#fechaFin1').val();

            // Convertir las fechas a objetos Date para comparación
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFin);
            // Validar que la fecha de inicio no sea posterior a la fecha de fin
            if (inicio > fin) {
                Swal.fire({
                        title: 'Error',
                        text: 'La fecha de inicio no puede ser posterior a la fecha de fin.',
                        icon: 'warning'
                    });
                e.preventDefault(); // Evitar que el formulario se envíe
                return;
            }else{
                Swal.fire({
                        title: 'Exito',
                        text: 'Reporte Generado.',
                        icon: 'success'
                    });
            }
        });
       
        // Restablecer el rango de fechas al hacer clic en el botón
        $('#resetc-btn').on('click', function() {
            $('#fechaInicio1').val('');
            $('#fechaFin1').val('');
            $('#daterangec-btn span').html('Rango de fecha'); // Cambia el texto del botón
        });
        
    });
</script>

<script src="vista/dist/js/modulos-js/rep-inventario.js"></script>