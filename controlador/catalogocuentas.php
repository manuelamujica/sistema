<?php

require_once "modelo/catalogocuentas.php";
require_once "modelo/bitacora.php";

$objCuenta = new CatalogoCuentas();
$objBitacora = new Bitacora();

// QUE ME TRAIGA LAS CUENTAS PADRES
if(isset($_POST['padre'])){
    $nivel = $_POST['padre'];
    $result=$objCuenta->listarcuentaspadrespornivel($nivel);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

// GENERAR CODIGO DE CUNETA PADRE
else if (isset($_POST['generarRaiz'])) {
    $nivel = $_POST['nivel'];
    $codPadre = $_POST['cod_padre'];
    $result=$objCuenta->generarCodigo($nivel,$codPadre);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}


// SI ES HIJA, GEENRAR EL CODIGO
else if (isset($_POST['codigohija'])) {
    $nivel = $_POST['nivel'];
    $codPadre = $_POST['cod_padre'];
    $result= $objCuenta->generarCodigo($nivel, $codPadre);
    header('Content-Type: application/json');
    echo json_encode($result);
    
    exit;
}

//var_dump($_POST);
if (isset($_POST['guardar'])) {
    $errores = [];

    
    try {
        $objCuenta->setNombre($_POST['nombreCuenta']);
        $objCuenta->setCodigoContable($_POST['codigoContable']);
        $objCuenta->setSaldo($_POST['saldo']);
        $objCuenta->setNivel($_POST['nivel']);

        // Si es cuenta hija, hereda la naturaleza y padre
        if (!empty($_POST['cuentaPadre'])) {
        $objCuenta->setCuentaPadreid($_POST['cuentaPadre']);
        $objCuenta->setNaturaleza($_POST['naturalezaHidden']); 
        } else {
        $objCuenta->setNaturaleza($_POST['naturaleza']); // se elige manualmente si es raíz
        }

        $objCuenta->check(); // Validaciones internas con trait

    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }

    if (!empty($errores)) {
        $respuesta = [
        "title" => "Error",
        "message" => implode(" ", $errores),
        "icon" => "error"
        ];
    } else {
        $resultado = $objCuenta->getregistrar();

        if ($resultado == 1) {
        $respuesta = [
            "title" => "Registrado",
            "message" => "La cuenta contable fue registrada exitosamente.",
            "icon" => "success"
        ];
        $objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de cuenta contable', $_POST['nombreCuenta'], 'Contabilidad');
        } else {
            print_r($objCuenta->getErrores());

        $respuesta = [
            "title" => "Error",
            "message" => "Ocurrió un problema al registrar la cuenta.",
            "icon" => "error"
        ];
        }
    }
}

$registro = $objCuenta->getconsultar_cuentas();
$_GET['ruta'] = 'catalogocuentas';
require_once 'plantilla.php';