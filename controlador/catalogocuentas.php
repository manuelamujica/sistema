<?php

require_once "modelo/catalogocuentas.php";
$cuentas = new CatalogoCuentas();

$consulta=$cuentas->getconsultar_cuentas();

$_GET['ruta'] = 'catalogocuentas';
require_once 'plantilla.php';