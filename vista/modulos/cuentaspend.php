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
                                        <?php foreach ($datos as $pendiente){ ?>
                                        <tr>
                                            <td><?php echo $pendiente['asunto']; ?></td>
                                            <td><?php echo $pendiente['origen']; ?></td>
                                            <td><?php echo $pendiente['monto_pagado']; ?></td>
                                            <td><?php echo $pendiente['importe_total']; ?></td>
                                            <td><?php echo $pendiente['saldo_pendiente']; ?></td>
                                            <td><span class="badge badge-<?php echo ($pendiente['status'] == '1') ? 'danger' : 'success'; ?>"><?php echo $pendiente['status']; ?></span></td>
                                            <td><button class="btn btn-primary">Pagar</button>
                                                <button class="btn btn-primary">Detalles</button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Cuentas por Cobrar -->
                            <div class="tab-pane fade" id="cobros">
                                <table id="tablaCobros" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Facturas Pendientes</th>
                                            <th>Monto Total</th>
                                            <th>Días Restantes</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Cliente A</td>
                                            <td>3</td>
                                            <td>$500.75</td>
                                            <td>3 días</td>
                                            <td><button class="btn btn-success">Cobrar Todo</button></td>
                                        </tr>
                                        <tr>
                                            <td>Cliente B</td>
                                            <td>2</td>
                                            <td>$420.00</td>
                                            <td>5 días</td>
                                            <td><button class="btn btn-success">Cobrar Todo</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>