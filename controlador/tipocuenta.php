<?php

require_once "modelo/tipocuenta.php"; //requiero al modelo
require_once "modelo/bitacora.php";
$objTipoCuenta = new Tipo_cuenta;
$objbitacora = new Bitacora();

$registro = $objTipoCuenta->consultar();



$_GET['ruta'] = 'tipocuenta';
require_once 'plantilla.php';