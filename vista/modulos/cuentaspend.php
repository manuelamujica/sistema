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
                                    <h3 id="totalPagos">668,40BS</h3>
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
                                    <?php foreach ($totalcobrar as $tc){ ?>
                                    <h3 id="totalCobros"><?php echo number_format($tc['total_cobrar'],2,',','.');?>Bs</h3>
                                    <?php } ?>
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
                                <table id="tablaPagos" class="table table-striped datatable" style="width: 100%;">
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
                                    <?php foreach ($pagar as $p){ ?>
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
                                <table id="tablaCobros" class="table table-striped datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Total Ventas</th>
                                            <th>Importe Total</th>
                                            <th>Total Pagado</th>
                                            <th>Total Pendiente</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cobrar as $pendiente){ ?>
                                        <tr>
                                            <td><?php echo $pendiente['cliente']; ?></td>
                                            <td><?php echo $pendiente['total_ventas']; ?></td>
                                            <td><?php echo $pendiente['total']; ?></td>
                                            <td><?php echo $pendiente['total_cobrado']; ?></td>
                                            <td><?php echo $pendiente['total_pendiente']; ?></td>
                                            <td>
                                                <button title="Ver detalles" class="btn btn-primary" data-toggle="modal" data-target="#detallemodal"
                                                data-cliente="<?php echo $pendiente["cod_cliente"];?>"
                                                ><i class="fas fa-plus"></i></button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- MODAL DETALLES-->
                    <div class="modal fade" id="detallemodal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detalleModalLabel">Cuentas por Cobrar</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Detalles de la cuenta</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="tablaDetalles" class="table table-bordered table-striped table-hover" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>Codigo</th>
                                                            <th>Fecha</th>
                                                            <th>Importe Total</th>
                                                            <th>Monto Pagado</th>
                                                            <th>Saldo Pendiente</th>
                                                            <th>Fecha de vencimiento</th>
                                                            <th>Días Restantes</th>
                                                            <th>Status</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="detalleBody">
                                                        
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
    </div>

    <script src="vista/dist/js/modulos-js/cuentaspend.js"></script>