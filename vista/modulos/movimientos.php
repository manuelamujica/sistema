<?php
require_once "controlador/movimientos.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-9">
                    <h1>Generar asientos contables</h1>
                    <p>En esta sección puedes convertir los movimientos de tu empresa en asientos contables.</p>
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
                        <div class="card-header d-flex align-items-center">
                            <div class="mb-2">
                            <button class="btn btn-success" onclick="mostrarPrevia()">
                                <i class="fas fa-sync"></i> Sincronizar Seleccionados
                            </button>
                            <button class="btn btn-primary" onclick="sincronizarTodo()">
                                <i class="fas fa-sync"></i> Sincronizar Todo
                            </button>
                            </div>
                    </div>
                    <!-- Tabla Movimientos -->
                    <div class="card">
                        <div class="card-header">
                        <h3 class="card-title">Movimientos</h3>
                        </div>
                        <div class="card-body">
                        <table id="movimientosTable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Monto</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Ejemplo de datos -->
                            <tr>
                                <td><input type="checkbox" class="mov-checkbox" data-id="1" disabled></td>
                                <td>2023-10-05</td>
                                <td><span class="badge bg-success">Venta</span></td>
                                <td>Venta #001</td>
                                <td>$1,000.00</td>
                                <td><span class="badge bg-danger">Pendiente</span></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="mov-checkbox" data-id="2"></td>
                                <td>2023-10-05</td>
                                <td><span class="badge bg-warning">Gasto</span></td>
                                <td>Pago servicios</td>
                                <td>$200.00</td>
                                <td><span class="badge bg-danger">Pendiente</span></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>

                    <!-- Tabla Asientos Contables -->
                    <div class="card mt-4">
                        <div class="card-header">
                        <h3 class="card-title">Asientos Contables Generados</h3>
                        </div>
                        <div class="card-body">
                        <table id="asientosTable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Débito</th>
                                <th>Crédito</th>
                                <th>Monto</th>
                            </tr>
                            </thead>
                            <tbody>
                                <td>2023-10-05</td>
                                <td><span class="badge bg-warning">Gasto</span></td>
                                <td>1.000</td>
                                <td>1.000</td>
                                <td>-</td>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    </div>
                </section>
                </div>
