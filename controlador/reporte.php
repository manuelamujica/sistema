<?php
require_once 'modelo/productos.php';
require_once 'modelo/dcarga.php';

$carga = new Dcarga();
$datos = $carga->getodoo();

//DATATABLE PRODUCTOS
$obj = new Productos();
$productos = $obj->getmostrar();

$_GET['ruta'] = 'rep-inventario';
require_once 'plantilla.php';