<?php

require_once "modelo/cuentabancaria.php";
require_once 'modelo/bitacora.php';

$objCuenta = new Cuenta_Bancaria();
$objBitacora = new Bitacora();

if (isset($_POST['buscar'])) {
    $numeroCuenta = $_POST['buscar'];
    $result = $objCuenta->buscar($numeroCuenta);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
    $objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Buscar cuenta', $numeroCuenta, 'Cuentas bancarias');
}

else if (isset($_POST['guardar'])) {
    if (
        !empty($_POST['numero_cuenta']) &&
        !empty($_POST['saldo']) &&
        !empty($_POST['cod_banco']) &&
        !empty($_POST['cod_tipo_cuenta']) &&
        !empty($_POST['cod_divisa'])
    ) {

        $numeroCuenta = $_POST['numero_cuenta'];
        $saldo = floatval($_POST['saldo']);
        $codBanco = $_POST['cod_banco'];
        $codTipoCuenta = $_POST['cod_tipo_cuenta'];
        $codDivisa = $_POST['cod_divisa'];

        // Validar existencia
        $existe = $objCuenta->buscar($numeroCuenta);
        if (!$existe) {

            if (strlen($numeroCuenta) <= 20 && preg_match('/^[0-9]+$/', $numeroCuenta)) {

                $objCuenta->setNumero_cuenta($numeroCuenta);
                $objCuenta->setSaldo($saldo);

                $registrado = $objCuenta->registrar($codBanco, $codTipoCuenta, $codDivisa);

                if ($registrado == 1) {
                    $registrar = [
                        "title" => "Registrado con éxito",
                        "message" => "La cuenta ha sido registrada",
                        "icon" => "success"
                    ];
                    $objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de cuenta', $numeroCuenta, 'Cuentas bancarias');
                } else {
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al registrar la cuenta",
                        "icon" => "error"
                    ];
                }

            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "El número de cuenta no es válido",
                    "icon" => "error"
                ];
            }

        } else {
            $registrar = [
                "title" => "Error",
                "message" => "Ya existe una cuenta con ese número",
                "icon" => "error"
            ];
        }

    } else {
        $registrar = [
            "title" => "Error",
            "message" => "No se permiten campos vacíos",
            "icon" => "error"
        ];
    }
}

else if (isset($_POST['borrar'])) {
    if (!empty($_POST['numero_cuenta'])) {
        $numeroCuenta = $_POST['numero_cuenta'];
        $resultado = $objCuenta->eliminar($numeroCuenta); // Debes agregar este método en el modelo

        if ($resultado == 'success') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "La cuenta ha sido eliminada",
                "icon" => "success"
            ];
            $objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar cuenta', $numeroCuenta, 'Cuentas bancarias');
        } else {
            $eliminar = [
                "title" => "Error",
                "message" => "No se pudo eliminar la cuenta",
                "icon" => "error"
            ];
        }
    }
}

// Listar todas las cuentas bancarias
$registro = $objCuenta->listar();

$_GET['ruta'] = 'cuentabancaria';
require_once 'plantilla.php';
