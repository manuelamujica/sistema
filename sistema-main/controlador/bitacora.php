<?php
require_once "modelo/bitacora.php";

$objbitacora = new Bitacora();

if(isset($_POST['registrar'])){
    $cod_usuario = $_POST['cod_usuario'];
    $accion = $_POST['accion'];
    $detalles = $_POST['detalles'];
    $objbitacora->registrarEnBitacora($cod_usuario, $accion, $detalles, $modulo);
    header('Location: bitacora');
}

$bitacora = $objbitacora->obtenerRegistros();

$_GET['ruta'] = 'bitacora';
require_once 'plantilla.php';
?>