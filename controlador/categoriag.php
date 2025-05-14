<?php
//COMPLETADO
require_once "modelo/bitacora.php";
require_once "modelo/gasto.php";
$objbitacora = new Bitacora();
$objgasto = new Gastos();

if (isset($_POST['buscarC'])) {
    $objgasto->setDatos($_POST['buscar']);
    $result = $objgasto->buscarCategoria();
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
} else if (isset($_POST['buscarF'])) {
    $objgasto->setDatos($_POST['buscar']);
    $result = $objgasto->buscarFrecuencia();
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
} else if (isset($_POST['guardar_frecuencia'])) {
    $errores = [];
    try {

        $objgasto->setDatos($_POST);
        $objgasto->check();
        if ($objgasto->buscarFrecuencia()) {
            $guardarF = [
                "title" => "Advertencia",
                "message" => "Frecuencia de pago ya registrada",
                "icon" => "warning"
            ];
            exit;
        } else {
            $resul = $objgasto->publicregistrarf();
        }
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
    var_dump("Parte controlador:");
    var_dump($_POST['frecuenciaC']);
    try {
        $objgasto->setDatos($_POST);
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
        } else if ($resul == 2) {
            $guardarC = [
                "title" => "Advertencia",
                "message" => "La categoría ya se encuentra registrada",
                "icon" => "warning"
            ];
        } else {
            $guardarC = [
                "title" => "Error",
                "message" => "Error al registrar la categoría de gastos",
                "icon" => "error"
            ];
        }
    }
} else if (isset($_POST['editarG'])) {
    $errores = [];
    try {
        $objgasto->setDatos($_POST);
        $objgasto->check();
        $res = $objgasto->editarC();
    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }
    if (!empty($errores)) {
        $editar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else if ($res == 1) {
        $editar = [
            "title" => "Editado con éxito",
            "message" => "La categoría de gastos ha sido editada",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar categoría de gastos', $_POST["cod_cat_gasto"], 'Categoría de gastos');
    } else if ($res == 'error_associated') {
        $editar = [
            "title" => "Advertencia",
            "message" => "La categoría de gastos ya se encuentra registrada",
            "icon" => "warning"
        ];
    }else if($res == 'error_query'){
        $editar = [
            "title" => "Error",
            "message" => "Hubo un problema de consulta al editar la categoría de gastos",
            "icon" => "error"
        ];

    } else {
        $editar = [
            "title" => "Error",
            "message" => "Error al editar la categoría de gastos",
            "icon" => "error"
        ];
    }
}

$frecuencia = $objgasto->consultarFrecuencia();
$tipo = $objgasto->consultarTipo();
$categorias = $objgasto->consultarCategoria();
$naturaleza = $objgasto->consulNaturaleza();

$_GET['ruta'] = 'categoriag';

require_once 'plantilla.php';
