<?php

require_once "modelo/cuentabancariacopia.php";
require_once "modelo/bitacora.php";

$objCuenta = new CuentaBancaria();
$objbitacora = new Bitacora();

$cuentas=$objCuenta->consultarCuenta();
