<?php
require_once "modelo/cuentaspend.php";

$objCuentasPendientes = new CuentasPendientes();

//CONSULTAR DETALLE DEPENDIENDO DEL CLIENTE
if(isset($_POST['detallecuenta'])){
    $cobrarf = $objCuentasPendientes->getmostrar2($_POST['detallecuenta']);
    header('Content-Type: application/json');
    echo json_encode($cobrarf);
    exit;
}

$cobrar = $objCuentasPendientes->getmostrarcliente();
$pagar = $objCuentasPendientes->getmostrarCuentasPagar();
$totalcobrar = $objCuentasPendientes->getboxcobrar();

require_once 'plantilla.php';
