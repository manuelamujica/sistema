<?php require_once 'controlador/divisa.php'; ?>
<!-- Preloader-->
<div class="preloader flex-column justify-content-center align-items-center">
<?php 
        if(isset($_SESSION["logo"])): ?>
            <img src="<?php echo $_SESSION["logo"];?>" alt="Quesera Don Pedro" class="" height="200" width="200">
        <?php else: ?>
            <img src="vista/dist/img/logo_generico.png" alt="Quesera Don Pedro"  class="" height="200" width="200">
        <?php endif; ?>
</div>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>INICIO</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">

            <!-- Widgets del Dashboard -->
                <div class="container-fluid">
                    <div class="row">
                        <!-- Caja -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box" style="background-color: #8770fa; color: white;">
                                    <div class="inner">
                                        <p class="mb-1">Caja 1</p>
                                        <h3>10.378bs</h3> 
                                        <p class="badge bg-success">+10%</p> 
                                        <span>esta semana</span> 
                                    </div>
                                </div>
                            </div>
                            <!-- Clientes -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <p class="mb-1">Caja 2</p>
                                        <h3>150$</h3> 
                                        <p class="badge bg-success">+20%</p> 
                                        <span>esta semana</span> 
                                    </div>
                                </div>
                            </div>
                            <!-- Clientes -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <p class="mb-1">Clientes</p>
                                        <h3>123</h3>
                                        <p class="badge bg-success">+10%</p> 
                                        <span>esta semana</span> 
                                    </div>
                                </div>
                            </div>
                            <!-- Gastos -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <p class="mb-1">Gastos</p>
                                        <h3>431$</h3>
                                        <p class="badge bg-danger">-15%</p> 
                                        <span>esta semana</span> 
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                    <!-- Gráfico de Ingresos - Egresos -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header"><h3>Ingresos vs Gastos</h3></div>
                                    <div class="card-body">
                                        <canvas id="graficoIngresos"></canvas>
                                    </div>
                            </div>
                        </div>
                        <!-- Gráfico de torta -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header"><h3>Gastos</h3></div>
                                    <div class="card-body">
                                        <canvas id="graficoGastos"></canvas>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tablas de productos más vendidos y alertas de stock -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h3>Productos mas vendidos</h3>
                                    <table id="productos" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Marca</th>
                                                <th>Presentación</th>
                                                <th>Precio</th>
                                                <th>Stock</th>
                                            </tr>         
                                        </thead>
                                    </table>  
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h3>Alertas de stock</h3>
                                    <table id="productos" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Codigo</th>
                                                <th>Producto</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- Tabla de tareas -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                        <div class="card-body">
                        <h3>No se</h3>
                            <table class="table table-bordered">
                            <thead>
                                <tr>
                                <th style="width: 10px">#</th>
                                <th>Task</th>
                                <th>Progress</th>
                                <th style="width: 40px">Label</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <td>1.</td>
                                <td>Update software</td>
                                <td>
                                    <div class="progress progress-xs">
                                    <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-danger">55%</span></td>
                                </tr>
                                <tr>
                                <td>2.</td>
                                <td>Clean database</td>
                                <td>
                                    <div class="progress progress-xs">
                                    <div class="progress-bar bg-warning" style="width: 70%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-warning">70%</span></td>
                                </tr>
                                <tr>
                                <td>3.</td>
                                <td>Cron job running</td>
                                <td>
                                    <div class="progress progress-xs progress-striped active">
                                    <div class="progress-bar bg-primary" style="width: 30%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-primary">30%</span></td>
                                </tr>
                                <tr>
                                <td>4.</td>
                                <td>Fix and squish bugs</td>
                                <td>
                                    <div class="progress progress-xs progress-striped active">
                                    <div class="progress-bar bg-success" style="width: 90%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-success">90%</span></td>
                                </tr>
                                <tr>
                                <td>5.</td>
                                <td>Fix and squish bugs</td>
                                <td>
                                    <div class="progress progress-xs progress-striped active">
                                    <div class="progress-bar bg-success" style="width: 90%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-success">90%</span></td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                    <!--Reporte ejemplo finanzas-->
                <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">reporte ej finanzas</h3>
                            <a href="javascript:void(0);">View Report</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="text-bold text-lg">$18,230.00</span>
                                <span>Sales Over Time</span>
                            </p>
                            <p class="ml-auto d-flex flex-column text-right">
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i> 33.1%
                                </span>
                                <span class="text-muted">Since last month</span>
                            </p>
                        </div>
                        <div class="position-relative mb-4">
                            <canvas id="sales-chart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                </div>
            </div>

                
        </section>
        
    <script>
    $(document).ready(function() {
        $('#tablaProductos, #tablaStock').DataTable();

        // Gráfico de Ingresos vs Egresos
        var ctxIngresos = document.getElementById('graficoIngresos').getContext('2d');
        new Chart(ctxIngresos, {
            type: 'line',
            data: {
                labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
                datasets: [{
                    label: 'Ingresos',
                    data: [150, 250, 200, 300, 400, 500, 600],
                    borderColor: '#5271ff' ,
                    fill: false
                }, {
                    label: 'Gastos',
                    data: [100, 150, 180, 200, 220, 300, 350],
                    borderColor: '#ed1c2a',
                    fill: false
                }]
            }
        });
        var ctxGastos = document.getElementById('graficoGastos').getContext('2d');

        new Chart(ctxGastos, {
        type: 'bar',
        data: {
            labels: ['Renta', 'Pago proveedores', 'Servicios', 'Publicidad', 'Gastos administrativos'],
            datasets: [
                {
                    label: 'Últimos 3 meses',
                    data: [1200, 950, 850, 730, 650],
                    backgroundColor: '#5271FF',
                    borderRadius: 8
                },
                {
                    label: 'Últimos 6 meses',
                    data: [2400, 1900, 1700, 1460, 1300],
                    backgroundColor: '#8770FA',
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#D1D1D1'
                    }
                },
                x: {
                    ticks: {
                        color: '#D1D1D1'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#D1D1D1',
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return `${tooltipItem.dataset.label}: $${tooltipItem.raw}`;
                        }
                    }
                }
            }
        }
    });
});
</script>

<script>
    var ctx = document.getElementById('sales-chart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'line', 
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Sales This Year', // Etiqueta para el gráfico
                data: [12000, 15000, 18000, 20000, 17000, 22000, 25000, 28000, 20000, 22000, 19000, 22000], // Datos de las ventas
                borderColor: 'rgba(0, 123, 255, 1)', // Color de la línea
                backgroundColor: 'rgba(0, 123, 255, 0.2)', // Color de fondo de la línea
                fill: true, // Rellenar el área debajo de la línea
                tension: 0.4 // Curvatura de la línea
            }, {
                label: 'Sales Last Year', // Etiqueta para el gráfico
                data: [10000, 13000, 15000, 18000, 16000, 19000, 23000, 21000, 16000, 19000, 18000, 20000, 19000], // Datos de las ventas del año pasado
                borderColor: 'rgba(169, 169, 169, 1)', // Color de la línea
                backgroundColor: 'rgba(169, 169, 169, 0.2)', // Color de fondo de la línea
                fill: true, // Rellenar el área debajo de la línea
                tension: 0.4 // Curvatura de la línea
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- MODAL REGISTRAR EMPRESA 1ERA VEZ-->
<div class="modal fade" id="modalregistrarempresa" tabindex="-1" aria-labelledby="modalregistrarempresaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registrar informacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="formGeneral" action="index.php?pagina=general" method="post" enctype="multipart/form-data">
                    <!--   RIF DE LA empresa     -->
                    <div class="form-group">
                        <label for="rif">Rif de la empresa <span class="text-danger" style="font-size: 20px;"> *</span> </label>
                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa el rif de la empresa, por ejemplo: J-010523">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <script>
                            $(function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        </script>
                        <input type="text" class="form-control" id="rif" name="rif" maxlength="15" placeholder="Ej: J123456789">
                        <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <!--   NOMBRE DE LA empresa     -->
                    <div class="form-group">
                        <label for="nombre">Nombre <span class="text-danger" style="font-size: 20px;"> *</span> </label>
                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa el nombre o razón social de la empresa, por ejemplo: Lacteos los Andes">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <script>
                            $(function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        </script>
                        <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50" placeholder="Ej: Inversiones SAVYC">
                        <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <!-- DIRECCION-->
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="direccion">Dirección <span class="text-danger" style="font-size: 20px;"> *</span></label>
                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa la dirección de la empresa, por ejemplo: Avenida Los Horcones">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <script>
                                $(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                });
                            </script>
                            <input type="text" class="form-control" name="direccion" id="direccion" maxlength="100" placeholder="Ej: Av. ejemplo con calle 1">
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>
                    <!--   TELEFONO     -->
                        <div class="col-6">
                            <label for="telefono">Teléfono<span class="text-danger" style="font-size: 20px;"> *</span></label>
                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa el telefono de la empresa, por ejemplo: 0424-555-21-23">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <script>
                                $(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                });
                            </script>
                            <input type="tel" class="form-control" name="telefono" id="telefono" maxlength="12" placeholder="Ej: 0412-1234567">
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>
                    </div>
                    <!--   EMAIL     -->
                    <div class="form-group ">
                            <label for="email">Correo:</label>
                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa el correo de la empresa, por ejemplo: savyc@gmail.com">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <script>
                                $(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                });
                            </script>
                            <input type="hidden" name="inicio" value="inicio">
                            <input type="email" class="form-control" name="email" id="email" maxlength="70" placeholder="Ej: savyc@gmail.com">
                            <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <div class="form-group">
                            <!--   DESCRIPCION    -->
                            <label for="descripcion">Descripción</label>
                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa una descripción breve de la empresa, por ejemplo: Comercio para la venta de alimentos">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <script>
                                $(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                });
                            </script>
                            <textarea class="form-control" name="descripcion" id="descripcion" maxlength="100" placeholder="Ej: Comercio para la venta de alimentos"></textarea>
                            <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <!--   LOGO    -->
                    <div class="form-group">
                        <label for="logo">Ingrese el logo<span class="text-danger" style="font-size: 20px;"> *</span></label>
                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa un logo representativo de la empresa">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <script>
                            $(function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        </script>
                        <input type="file" class="form-control" name="logo" id="logo">
                        <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <!-- Alert Message -->
                    <div class="alert alert-light d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span>Todos los campos marcados con (*) son obligatorios</span>
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
<?php
if (isset($registrar)){ ?>
    <script>
        Swal.fire({
            title: '<?php echo $registrar["title"]; ?>',
            text: '<?php echo $registrar["message"]; ?>',
            icon: '<?php echo $registrar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'inicio';
            }
        });
    </script>
<?php } ?>
<?php if(empty($_SESSION["rif"]) && $_SESSION["cod_usuario"] != 1):?>
    <script>
        console.log("pasa la primera condicion");
        $(document).ready(function() {           
            if (!localStorage.getItem("modalMostrado")) {
                $('#modalregistrarempresa').modal('show');
                localStorage.setItem("modalMostrado", "true");
            }
        });
    </script>
<?php endif; ?>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Actualizar Tasas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post">
                    <?php foreach($consulta as $index=>$divisa): 
                        if($divisa['cod_divisa']!=1):?>
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="codigo" name="tasa[<?= $index ?>][cod_divisa]" value="<?= $divisa['cod_divisa'];?>">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Divisa</label>
                        <input type="text" class="form-control" value="<?= $divisa['nombre'].' - '.$divisa['abreviatura']; ?>" readonly>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-md-7">
                            <label for="tasa">Tasa de la Divisa</label>
                            <div class="input-group">
                                <input type="number" id="tasaactual" step="0.01" class="form-control" value="<?= $divisa['tasa'];?>" name="tasa[<?= $index ?>][tasa]" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Bs</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="tasa[<?= $index ?>][fecha]" value="<?= $divisa['fecha'];?>" required>
                        </div>
                    </div>
                    <hr>
                    <?php endif; 
                    endforeach; ?>
                    <input type="hidden" name="inicio">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" form="editForm" class="btn btn-primary" name="r_tasa">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
<?php 
if (isset($editar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $editar["title"]; ?>',
            text: '<?php echo $editar["message"]; ?>',
            icon: '<?php echo $editar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'inicio';
            }
        });
    </script>
<?php endif; ?>

<div class="modal fade" id="loadingModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body">
                <h5>Cargando tasas...</h5>
                <div class="spinner-border text-primary mt-3" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    $ultimo=end($consulta);
    if($ultimo['cod_divisa']!=1):
        if($ultimo['fecha'] != date('Y-m-d') && $_SESSION["cod_usuario"] != 1): 
?>
    <script>
    $(document).ready(function() {  
        var sen = "dolar";
        var tasaorig = $("#tasaactual").val();

        // Mostrar modal de carga antes de la solicitud
        $("#loadingModal").modal("show");

        $.post('index.php?pagina=divisa', { sen }, function(response) {
            console.log("Respuesta del servidor:", response);
            let tasa = parseFloat(response.replace(',', '.'));

            if (response !== "error") {
                $('#tasaactual').val(tasa.toFixed(2));
                var now = new Date();
                var fecha = now.getFullYear() + '-' +
                    String(now.getMonth() + 1).padStart(2, '0') + '-' +
                    String(now.getDate()).padStart(2, '0');
                $('#fecha').val(fecha);
            } else {
                console.log("Error en la respuesta.");
                $('#tasaactual').val(tasaorig);
            }

            // Ocultar modal de carga y mostrar modal de edición
            $("#loadingModal").modal("hide");
            $('#editModal').modal('show');
            
        }).fail(function() {
            console.log("Error en la solicitud AJAX.");
            $("#loadingModal").modal("hide");
        });
    });
</script>

<?php   endif; 
    endif; ?>

<script src="vista/dist/js/modulos-js/general.js"></script>