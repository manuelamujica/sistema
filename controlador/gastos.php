<?php
//MODIFICACIÓN COMPLETA 29/04/2025
require_once "modelo/gasto.php";
require_once "modelo/pago_gastos.php";
require_once "modelo/bitacora.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$objgasto = new Gastos();
$objpago = new Pagos();
$objbitacora = new Bitacora();
if (isset($_POST['buscar'])) {
    $descripcion = $_POST['buscar'];
    $objgasto->setDatos(['descripcion' => $descripcion]);
    $result = $objgasto->buscar_gasto();
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
} else if (isset($_POST['mostrarTporC'])) {
    $objgasto->setDatos(['cod_cat_gasto' => $_POST['mostrarTporC']]);
    $resul = $objgasto->buscarTporCategoria();
    $tipoGasto = $resul['nombret'];
    header('Content-Type: application/json');
    echo json_encode(['tipo_gasto' => $tipoGasto]);
    exit;
} else if (isset($_POST['guardarG'])) {
    $errores = [];
    try {
        $objgasto->setDatos($_POST);
        $objgasto->check();
        $resul = $objgasto->publicregistrarg();
    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }
    if (!empty($errores)) {
        $guardarG = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else if ($resul == 1) {
        $guardarG = [
            "title" => "Registrado con éxito",
            "message" => "El gasto ha sido registrado",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de gasto', $_POST["descripcion"], 'Gasto');
    } else if ($resul == 2) {
        $guardarG = [
            "title" => "Gasto ya registrado",
            "message" => "No se puede registrar el gasto; ya existe un gasto con la misma descripción",
            "icon" => "warning"
        ];
    } else {
        $guardarG = [
            "title" => "Error",
            "message" => "Error al registrar el gasto",
            "icon" => "error"
        ];
    }
} else if (isset($_POST['pagar_gasto'])) { /* EN DESARROLLO */
    $errores = [];
    try {
        $dato = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE || $dato === null) {
            $dato = $_POST;
        }
        $objpago->setDatos($dato);
        $objpago->check();
        $res = $objpago->registrarPgasto();
    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }
    if (!empty($errores)) {
        $registrarPG = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else if ($res == 0) {
        $registrarPG['status'] = 'success';
        $registrarPG['data'] = [
            "title" => "El pago del gasto ha sido registrado exitosamente.",
            "message" => "El gasto se ha completado.",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago de gasto', $_POST["monto_pagado"], 'Pago');
    } else if ($res > 0) {
        $registrarPG['status'] = 'success';
        $registrarPG['data'] = [
            "title" => "Se ha registrado un pago parcial.",
            "message" => "El monto pendiente es de " . $res . "Bs.",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago parcial', $_POST["monto_pagar"], 'Pago');
    } else {
        $registrarPG = 'error';
        $registrarPG['message'] = "Error al enviar el pago";
    }
    header('Content-Type: application/json');
    echo json_encode($registrarPG);
    exit;
} else if (isset($_POST['pago_cuotas'])) {
    $errores = [];
    try {
        $dato = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE || $dato === null) {
            $dato = $_POST;
        }
        $objpago->setDatos($dato);
        $objpago->check();
        $res = $objpago->registrarCuota($_POST['pago']);
    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }
    if (!empty($errores)) {
        $registrarPGcuotas = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    }
    if ($res == 0) {
        $registrarPGcuotas = [
            "title" => "El pago del gasto ha sido registrado exitosamente.",
            "message" => "El gasto se ha completado.",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago de gasto', $_POST["monto_pagado"], 'Pago');
    } else if ($res > 0) {
        $registrarPGcuotas = [
            "title" => "Se ha registrado un pago parcial.",
            "message" => "El monto pendiente es de " . $res . "Bs.",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago parcial', $_POST["monto_pagado"], 'Pago');
    } else {
        $registrarPGcuotas = [
            "title" => "Error",
            "message" => "Error al enviar el pago parcial",
            "icon" => "error"
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($registrarPGcuotas);
    exit;
} else if (isset($_POST['editarG'])) {
    $errores = [];
    try {
        $objgasto->setDatos($_POST);
        $objgasto->check();
        $resp = $objgasto->buscar_gasto();
        if ($resp) {
            $editarG = [
                "title" => "Advertencia",
                "message" => "El gasto no se puede duplicar porque ya existe",
                "icon" => "warning"
            ];
            exit;
        }
        $resul = $objgasto->editarGasto();
    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }
    if (!empty($errores)) {
        $editarG = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else if ($resul == 1) {
        $editarG = [
            "title" => "Editado con éxito",
            "message" => "El gasto ha sido editado",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Edición de gasto', $_POST["descripcion"], 'Gasto');
    } else {
        $editarG = [
            "title" => "Error",
            "message" => "Error al editar el gasto",
            "icon" => "error"
        ];
    }
}

$frecuencia = $objgasto->consultarFrecuencia();
$tipo = $objgasto->consultarTipo();
$categorias = $objgasto->consultarCategoria();
$gastosF = $objgasto->consultarGastoF();
$gastosV = $objgasto->consultarGastoV();
$totalV = $objgasto->consultarTotalV();
$totalF = $objgasto->consultarTotalF();
$totalG = $objgasto->consultarTotalG();
$totalP = $objgasto->consultarTotalP();
$formaspago = $objpago->consultar();
$_GET['ruta'] = 'gastos';
require_once 'plantilla.php';
