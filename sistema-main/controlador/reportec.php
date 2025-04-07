<?php
require_once 'modelo/clientes.php';

$obj = new Clientes();
$registro = $obj->consultar();

$_GET['ruta'] = 'rep-cliente';
require_once 'plantilla.php';