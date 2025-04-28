<?php
require_once "vista/modulos/cuentaspend.php";
require_once "modelo/cuentaspend.php";

$objCuentasPendientes = new CuentasPendientes();
$datos = $objCuentasPendientes->getmostrar();
$datos2 = $objCuentasPendientes->getmostrarCuentasPagar();