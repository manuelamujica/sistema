<?php
require_once 'modelo/productos.php';

$obj = new Productos();
$productos = $obj->getmostrar();

$_GET['ruta'] = 'rep-inventario';
require_once 'plantilla.php';