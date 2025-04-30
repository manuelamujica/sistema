<?php
require_once 'modelo/productos.php';
require_once 'modelo/dcarga.php';
require_once 'modelo/descarga.php';
require_once 'modelo/categorias.php';

//DATATABLE CARGA
$carga = new Dcarga();
$datos = $carga->getodoo();

//DATATABLE PRODUCTOS
$obj = new Productos();
$productos = $obj->getmostrar();
$objCategoria = new Categoria();
$categoria = $objCategoria->getmostrar();

//DATATABLE DESCARGA
$objdescarga = new Descarga();
$descarga = $objdescarga->consultardescargar();

$_GET['ruta'] = 'rep-inventario';
require_once 'plantilla.php';