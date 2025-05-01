<?php 
require_once "controlador/finanzas.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Finanzas</h1>
                    <p>En esta sección se pueden consultar las finanzas de la empresa.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
            <ul class="nav nav-tabs mb-4" id="pestañas" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="cuentas-tab" data-toggle="tab" data-target="#cuentas" type="button" role="tab">Análisis de Cuentas</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="inventario-tab" data-toggle="tab" data-target="#inventario" type="button" role="tab">Rotación de Inventario</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rentabilidad-tab" data-toggle="tab" data-target="#rentabilidad" type="button" role="tab">Análisis de Rentabilidad</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="presupuestos-tab" data-toggle="tab" data-target="#presupuestos" type="button" role="tab">Presupuestos</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="proyecciones-tab" data-toggle="tab" data-target="#proyecciones" type="button" role="tab">Proyecciones</button>
                </li>
            </ul>
                <div class="tab-content" id="contenido-pestañas">
                    <div class="tab-pane fade show active" id="cuentas" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title h4 mb-4">Análisis de Cuentas</h2>
                                <p class="text-muted">Aquí puedes ver el análisis detallado de tus cuentas.</p>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label for="categoria" class="form-label">Categoría de Cuenta:</label>
                                        <select id="categoria" name="categoria" class="form-select">
                                <option value="activos">Activos</option>
                                <option value="pasivos">Pasivos</option>
                                <option value="patrimonio">Patrimonio</option>
                                <option value="ingresos">Ingresos</option>
                                <option value="gastos">Gastos</option>
                            </select>
                        </div>
                                    <div class="col-md-3">
                                        <label for="cuenta" class="form-label">Cuenta Específica:</label>
                                        <select id="cuenta" name="cuenta" class="form-select">
                                <option value="caja">Caja</option>
                                <option value="bancos">Bancos</option>
                                <option value="clientes">Clientes</option>
                                <option value="proveedores">Proveedores</option>
                                <option value="capital">Capital</option>
                            </select>
                        </div>
                                    <div class="col-md-3">
                                        <label for="mes-inicio" class="form-label">Mes Inicial:</label>
                                        <div class="input-group">
                                            <select id="mes-inicio" name="mes-inicio" class="form-select">
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                            <select id="año-inicio" name="año-inicio" class="form-select">
                                                <option value="2023">2023</option>
                                                <option value="2024" selected>2024</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="mes-fin" class="form-label">Mes Final:</label>
                                        <div class="input-group">
                                            <select id="mes-fin" name="mes-fin" class="form-select">
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                            <select id="año-fin" name="año-fin" class="form-select">
                                                <option value="2023">2023</option>
                                                <option value="2024" selected>2024</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check form-check-inline mb-4">
                                    <input class="form-check-input" type="checkbox" id="mostrarSaldo" checked>
                                    <label class="form-check-label" for="mostrarSaldo">Saldo de la Cuenta</label>
                                </div>
                                <div class="form-check form-check-inline mb-4">
                                    <input class="form-check-input" type="checkbox" id="mostrarIngresos" checked>
                                    <label class="form-check-label" for="mostrarIngresos">Ingresos</label>
                                </div>
                                <div class="form-check form-check-inline mb-4">
                                    <input class="form-check-input" type="checkbox" id="mostrarEgresos" checked>
                                    <label class="form-check-label" for="mostrarEgresos">Egresos</label>
                    </div>

                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div style="position: relative; height: 400px; width: 100%;">
                                            <canvas id="grafico-cuentas"></canvas>
                    </div>
                </div>
                        </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="inventario" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title h4 mb-4">Rotación de Inventario</h2>
                                <p class="text-muted">Información sobre la rotación de inventario.</p>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="periodo-inventario" class="form-label">Período:</label>
                                        <div class="input-group">
                                            <select id="mes-inventario" name="mes-inventario" class="form-select">
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                            <select id="año-inventario" name="año-inventario" class="form-select">
                                                <option value="2023">2023</option>
                                                <option value="2024" selected>2024</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="buscar-producto" class="form-label">Buscar Producto:</label>
                                        <div class="input-group">
                                            <input type="text" id="buscar-producto" name="buscar-producto" class="form-control" placeholder="Ingrese el nombre del producto">
                                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="tabla-rotacion" class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Producto</th>
                                                <th>Stock Inicial</th>
                                                <th>Stock Final</th>
                                                <th>Ventas</th>
                                                <th>Rotación</th>
                                                <th>Días de Rotación</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="presupuestos" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title h4 mb-4">Presupuestos</h2>
                                <p class="text-muted">Gestión y seguimiento de presupuestos por categoría.</p>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label for="categoria-gasto" class="form-label">Categoría de Gasto:</label>
                                        <select id="categoria-gasto" name="categoria-gasto" class="form-select">
                                            <option value="" selected disabled>Seleccione una categoría</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="periodo-presupuesto" class="form-label">Período:</label>
                                        <div class="input-group">
                                            <select id="mes-presupuesto" name="mes-presupuesto" class="form-select">
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                            <select id="año-presupuesto" name="año-presupuesto" class="form-select">
                                                <option value="2023">2023</option>
                                                <option value="2024" selected>2024</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="presupuesto-mensual" class="form-label">Presupuesto Mensual:</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" id="presupuesto-mensual" name="presupuesto-mensual" class="form-control" placeholder="0.00">
                                            <button id="guardar-presupuesto" class="btn btn-primary">
                                                <i class="bi bi-save"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-body">
                                        <canvas id="presupuestoChart"></canvas>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="tabla-presupuestos" class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Categoría</th>
                                                <th class="text-end">Presupuesto</th>
                                                <th class="text-end">Gasto Real</th>
                                                <th class="text-end">Diferencia</th>
                                                <th class="text-end">% Utilizado</th>
                                                <th class="text-center">Estado</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                                <!-- Summary Cards -->
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <div class="card bg-primary bg-opacity-10">
                                            <div class="card-body">
                                                <h6 class="card-subtitle mb-2 text-primary">Presupuesto Total</h6>
                                                <p class="card-text h3 text-primary" id="presupuesto-total">$0.00</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-success bg-opacity-10">
                                            <div class="card-body">
                                                <h6 class="card-subtitle mb-2 text-success">Gasto Real</h6>
                                                <p class="card-text h3 text-success" id="gasto-real-total">$0.00</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-info bg-opacity-10">
                                            <div class="card-body">
                                                <h6 class="card-subtitle mb-2 text-info">Diferencia</h6>
                                                <p class="card-text h3 text-info" id="diferencia-total">$0.00</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="proyecciones" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title h4 mb-4">Proyecciones de Ventas</h2>
                                <p class="text-muted">Análisis y proyección de ventas generales y por producto.</p>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="ver-historico" class="form-label">Tipo de Análisis:</label>
                                        <select id="ver-historico" name="ver-historico" class="form-select">
                                            <option value="proyecciones">Proyecciones Futuras</option>
                                            <option value="precision">Precisión Histórica</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="periodo-proyeccion" class="form-label">Período:</label>
                                        <select id="periodo-proyeccion" name="periodo-proyeccion" class="form-select">
                                            <option value="3">Próximos 3 meses</option>
                                            <option value="6">Próximos 6 meses</option>
                                            <option value="12">Próximo año</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-body">
                                        <canvas id="proyeccionesChart"></canvas>
                                    </div>
                                </div>

                                <!-- Tabla de Proyecciones Futuras -->
                                <div id="tabla-proyecciones" class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h3 class="h5 mb-0">Proyecciones Futuras</h3>
                                            <div class="input-group" style="width: 300px;">
                                                <input type="text" id="buscar-producto-proyeccion" class="form-control" placeholder="Buscar producto...">
                                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="tabla-proyecciones-futuras" class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th class="text-end">Ventas Actuales</th>
                                                        <th class="text-end">Proyección 3 Meses</th>
                                                        <th class="text-end">Proyección 6 Meses</th>
                                                        <th class="text-end">Proyección 12 Meses</th>
                                                        <th class="text-center">Tendencia</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabla de Precisión Histórica -->
                                <div id="tabla-precision" class="card" style="display: none;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h3 class="h5 mb-0">Precisión de Proyecciones Anteriores</h3>
                                            <div class="input-group" style="width: 300px;">
                                                <input type="text" id="buscar-producto-precision" class="form-control" placeholder="Buscar producto...">
                                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="tabla-precision-historica" class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th class="text-end">Precisión Promedio</th>
                                                        <th class="text-end">Mejor Precisión</th>
                                                        <th class="text-end">Peor Precisión</th>
                                                        <th class="text-end">Tendencia</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="rentabilidad" role="tabpanel">
                    <div class="card">
                            <div class="card-body">
                                <h2 class="card-title h4 mb-4">Análisis de Rentabilidad</h2>
                                <p class="text-muted">Análisis detallado de la rentabilidad por producto e inventario.</p>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="periodo-rentabilidad" class="form-label">Período:</label>
                                        <div class="input-group">
                                            <select id="mes-rentabilidad" name="mes-rentabilidad" class="form-select">
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                            <select id="año-rentabilidad" name="año-rentabilidad" class="form-select">
                                                <option value="2023">2023</option>
                                                <option value="2024" selected>2024</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="buscar-producto-rentabilidad" class="form-label">Buscar Producto:</label>
                                        <div class="input-group">
                                            <input type="text" id="buscar-producto-rentabilidad" name="buscar-producto-rentabilidad" class="form-control" placeholder="Ingrese el nombre del producto">
                                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="tabla-rentabilidad" class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Producto</th>
                                                <th class="text-end">Costo Total</th>
                                                <th class="text-end">Ingresos Totales</th>
                                                <th class="text-end">Margen Bruto</th>
                                                <th class="text-end">Rentabilidad</th>
                                                <th class="text-end">ROI</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                                <!-- Summary Cards -->
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <div class="card bg-success bg-opacity-10">
                                            <div class="card-body">
                                                <h6 class="card-subtitle mb-2 text-success">Rentabilidad Promedio</h6>
                                                <p class="card-text h3 text-success">36.51%</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-primary bg-opacity-10">
                                            <div class="card-body">
                                                <h6 class="card-subtitle mb-2 text-primary">ROI Promedio</h6>
                                                <p class="card-text h3 text-primary">58.33%</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-info bg-opacity-10">
                                            <div class="card-body">
                                                <h6 class="card-subtitle mb-2 text-info">Margen Bruto Total</h6>
                                                <p class="card-text h3 text-info">$2,750.00</p>
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

<!-- Modals -->
<!-- Modal para el gráfico de rotación -->
<div class="modal fade" id="modal-rotacion" tabindex="-1" aria-labelledby="modal-rotacion-label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-rotacion-label">Detalle de Rotación</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="height: 400px;">
                        <canvas id="grafico-rotacion"></canvas>
                    </div>
                </div>
            </div>
        </div>
</div>

<!-- Modal para proyecciones -->
<div class="modal fade" id="modal-proyeccion" tabindex="-1" aria-labelledby="modal-proyeccion-label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-proyeccion-label">Detalle de Proyección</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="height: 400px;">
                        <canvas id="grafico-proyeccion"></canvas>
                    </div>
                    <div id="resumen-proyeccion" class="mt-4"></div>
                </div>
            </div>
        </div>
</div>

<!-- Modal para rentabilidad -->
<div class="modal fade" id="modal-rentabilidad" tabindex="-1" aria-labelledby="modal-rentabilidad-label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-rentabilidad-label">Detalle de Rentabilidad</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="height: 400px;">
                        <canvas id="grafico-rentabilidad"></canvas>
                    </div>
                    <div id="resumen-rentabilidad" class="mt-4"></div>
                </div>
            </div>
        </div>
</div>

<!-- Modal para precisión -->
<div class="modal fade" id="modal-precision" tabindex="-1" aria-labelledby="modal-precision-label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-precision-label">Precisión Histórica</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="height: 400px;">
                        <canvas id="grafico-precision"></canvas>
                    </div>
                    <div id="resumen-precision" class="mt-4"></div>
                </div>
            </div>
        </div>
</div>

<!-- Scripts específicos para finanzas -->
<script src="vista/dist/js/modulos-js/graficos.js"></script>
<script src="vista/dist/js/modulos-js/finanzas.js"></script>

<!-- Agregar DataTables CSS y JS -->
<link rel="stylesheet" href="vista/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="vista/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<script src="vista/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="vista/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="vista/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="vista/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

