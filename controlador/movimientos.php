<?php
require_once "modelo/movimientos.php";
require_once "vista/modulos/movimientos.php";

$obj=new Movimientos();
$mov=$obj->consultar();