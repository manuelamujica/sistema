<?php
require_once 'modelo/proveedores.php';

$objProveedores = new Proveedor();


$registro = $objProveedores->getconsulta();
$_GET['ruta'] = 'rep-proveedores';
require_once 'plantilla.php';