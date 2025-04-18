<?php
session_start();
require_once "modelo/bitacora.php";

$objbitacora = new Bitacora();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['modulo'])) {
    $modulo = $_POST['modulo'];
    $descripcion = "Acceso a " . $modulo;
    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], $descripcion, '', $modulo);
    echo json_encode(["status" => "ok"]);
    exit;
}
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