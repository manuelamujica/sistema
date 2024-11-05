<?php
require_once 'modelo/proveedores.php';
require_once 'modelo/tlf_proveedor.php';
require_once 'modelo/representantes.php';


$objRepresentante = new Representantes();
$objProveedores = new Proveedor();
$objtProveedor = new TProveedor();
//TODO FUNCIONA

$registro = $objProveedores->getconsulta();
$_GET['ruta'] = 'rep-proveedores';
require_once 'plantilla.php';