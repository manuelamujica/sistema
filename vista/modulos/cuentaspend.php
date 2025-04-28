<?php 
require_once "controlador/cuentaspend.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-9">
                    <h1>Cuentas pendientes</h1>
                    <p>Centraliza la informacion de las cuentas por pagar y cuentas por cobrar de tu empresa.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3 id="totalPagos">$668.40</h3>
                                    <p>Cuentas por Pagar</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3 id="totalCobros">$920.75</h3>
                                    <p>Cuentas por Cobrar</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs" id="tabCuentas">
                                <li class="nav-item"><a class="nav-link active" href="#pagos" data-toggle="tab">Cuentas por Pagar</a></li>
                                <li class="nav-item"><a class="nav-link" href="#cobros" data-toggle="tab">Cuentas por Cobrar</a></li>
                            </ul>
                        </div>
                        <div class="card-body tab-content">
                            <!-- Cuentas por Pagar -->
                            <div class="tab-pane fade show active" id="pagos">
                                <table id="tablaPagos" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Asunto/Proveedor</th>
                                            <th>Origen</th>
                                            <th>Monto Pagado</th>
                                            <th>Importe Total</th>
                                            <th>Saldo pendiente</th>
                                            <th>Status</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($datos2 as $p){ ?>
                                        <tr>
                                            <td><?php echo $p['proveedor']; ?></td>
                                            <td><?php echo $p['total']; ?></td>
                                            <td><?php echo $p['monto_pagado']; ?></td>
                                            <td><?php echo $p['saldo_pendiente']; ?></td>
                                            <td><?php echo $p['dias_restantes']; ?></td> <!-- no cuadra los status  -->
                                            <td><span class="badge bg-<?php echo ($p['estado'] == '1') ? 'danger' : 'warning'; ?>"><?php echo $p['estado']; ?></span></td>
                                            <td>
                                                <button class="btn btn-primary"><i class="fas fa-plus"></i></button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Cuentas por Cobrar (VENTA X CLIENTE) -->
                            <div class="tab-pane fade" id="cobros">
                                <table id="tablaCobros" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <!--<th>Facturas Pendientes</th>-->
                                            <th>Monto Total</th>
                                            <th>Monto Pagado</th>
                                            <th>Saldo Pendiente</th>
                                            <th>Días Restantes</th>
                                            <th>Status</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($datos as $pendiente){ ?>
                                        <tr>
                                            <td><?php echo $pendiente['cliente']; ?></td>
                                            <td><?php echo $pendiente['total']; ?></td>
                                            <td><?php echo $pendiente['monto_total']; ?></td>
                                            <td><?php echo $pendiente['saldo_pendiente']; ?></td>
                                            <td><?php echo $pendiente['dias_restantes']; ?></td> <!-- no cuadra los status  -->
                                            <td><span class="badge bg-<?php echo ($pendiente['estado'] == '1') ? 'danger' : 'warning'; ?>"><?php echo $pendiente['estado']; ?></span></td>
                                            <td>
                                                <button class="btn btn-primary"><i class="fas fa-plus"></i></button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>