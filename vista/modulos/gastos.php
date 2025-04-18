<?php 
require_once "controlador/catalogocuentas.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gastos Fijos y Variables</h1>
                    <p>Lleva el control de los gastos de tu empresa.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
    <div class="container-fluid">
    <!-- Boxes de Resumen -->
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalRegistrarGasto">
                Registrar gasto
            </button>
        </div>
    </div>
        
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box" style="background-color: #8770fa; color: white;">
                        <div class="inner">
                        <h3 id="total-gastos">890bs</h3>
                        <p>Gastos Totales</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                        <h3 id="gastos-variables">450bs</h3>
                        <p>Gastos Variables</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                        <h3 id="gastos-fijos">440bs</h3>
                        <p>Gastos Fijos</p>
                        </div>
                    </div>
                </div>
            </div>

<!-- Main content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><b> Gastos Fijos </b></h2>
                    </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Descripción</th>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                            <th>Último pago</th>
                                            <th>Días restantes</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>GF001</td>
                                        <td>Alquiler local</td>
                                        <td>300 Bs</td>
                                        <td>2025-04-01</td>
                                        <td>2025-04-10</td>
                                        <td>20 días</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></button>
                                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>GF002</td>
                                        <td>Servicios básicos</td>
                                        <td>140 Bs</td>
                                        <td>2025-04-05</td>
                                        <td>2025-04-11</td>
                                        <td>24 días</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></button>
                                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><b> Gastos Variables </b></h2>
                    </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                        <th>Código</th>
                                            <th>Descripción</th>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                            <th>Último pago</th>
                                            <th>Días restantes</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>GV001</td>
                                        <td>Publicidad</td>
                                        <td>250 Bs</td>
                                        <td>2025-04-03</td>
                                        <td>2025-04-09</td>
                                        <td>22 días</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></button>
                                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>GV002</td>
                                        <td>Material de oficina</td>
                                        <td>200 Bs</td>
                                        <td>2025-04-06</td>
                                        <td>2025-04-12</td>
                                        <td>25 días</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></button>
                                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>