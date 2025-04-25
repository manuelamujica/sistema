<?php
require_once "modelo/bitacora.php";
require_once "modelo/gasto.php";
$objbitacora = new Bitacora();
$objgasto = new Gastos();

if (isset($_POST['guardar_frecuencia'])) {
    $errores = [];
    try {

        $objgasto->set_frecuencia($_POST['frecuencia']);
        $objgasto->set_dias($_POST['dias']);
        $objgasto->check();
        $resul = $objgasto->publicregistrarf();
    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }
    if (!empty($errores)) {
        $guardarF = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else {
        if ($resul == 1) {
            $guardarF = [
                "title" => "Registrado con éxito",
                "message" => "Frecuencia de pagos de gastos registrada con éxito",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de frecuencia de gasto', $_POST["frecuencia"], 'plazo de pago');
        } else  if ($resul == 2) {
            $guardarF = [
                "title" => "Error",
                "message" => "No se permiten campos vacíos.",
                "icon" => "error"
            ];
        }
    }
} else if (isset($_POST['guardarC'])) {
    $errores = [];
    try {
        $objgasto->set_cod_frecuencia($_POST['frecuenciaC']);
        $objgasto->set_cod_tipo_gasto($_POST['tipogasto']);
        $objgasto->set_nombreC($_POST['nombre']);
        $objgasto->set_fecha($_POST['fecha_hora']);
        $objgasto->check();
        $resul = $objgasto->publicregistrarc();
    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }

    if (!empty($errores)) {
        $guardarC = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else {
        if ($resul == 1) {
            $guardarC = [
                "title" => "Registrado con éxito",
                "message" => "La información de la categoría ha sido registrada",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de categoría de gastos', $_POST["nombre"], 'Categoría de gastos');
        } else {
            $guardarC = [
                "title" => "Error",
                "message" => "Error al registrar la categoría de gastos",
                "icon" => "error"
            ];
        }
    }
}

$frecuencia = $objgasto->consultarFrecuencia();
$tipo = $objgasto->consultarTipo();
$categorias = $objgasto->consultarCategoria();

$_GET['ruta'] = 'categoriag';

require_once 'plantilla.php';
