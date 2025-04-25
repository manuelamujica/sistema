<?php
require_once "modelo/gasto.php";
require_once "modelo/pago_gastos.php";
require_once "modelo/bitacora.php";

$objgasto = new Gastos();
$objpago = new Pagos();
$objbitacora = new Bitacora();
if (isset($_POST['buscar'])) {
    /* $result=$objgasto->b_productos($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;*/
} else if (isset($_POST['mostrarTporC'])) {
    $objgasto->set_cod_cat_gasto($_POST['mostrarTporC']); 
    $resul = $objgasto->buscarTporCategoria();
    $tipoGasto = $resul['nombret']; 
    header('Content-Type: application/json');
    echo json_encode(['tipo_gasto' => $tipoGasto]); 
    exit;
} else if (isset($_POST['guardarG'])) {
    $errores = [];
    try {
        $objgasto->set_cod_cat_gasto($_POST['categoriaG']);
        $objgasto->set_descripcion($_POST['descripcionG']);
        $objgasto->set_monto($_POST['monto']);
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
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de gasto', $_POST["descripcionG"], 'Gasto');
    } else {
        $guardarG = [
            "title" => "Error",
            "message" => "Error al registrar el gasto",
            "icon" => "error"
        ];
    }
} else if (isset($_POST['pagar_gasto'])) {
    $objpago->set_cod_gasto($_POST['cod_gasto1']);
    $objpago->set_fecha($_POST['fecha_del_pago']);
    $objpago->set_monto($_POST['monto_pagar']);
    $objpago->set_vuelto($_POST['vuelto']);
    $objpago->set_montopagado($_POST['monto_pagado']);
    $res = $objpago->registrarPgasto($_POST['pago'], $_POST['monto_pagar']);
    if ($res == 0) {
        $registrarPG = [
            "title" => "El pago del gasto ha sido registrado exitosamente.",
            "message" => "El gasto se ha completado.",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago de gasto', $_POST["monto_pagado"], 'Pago');
    } else if ($res > 0) {
        $registrarPG = [
            "title" => "Se ha registrado un pago parcial.",
            "message" => "El monto pendiente es de " . $res . "Bs.",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago parcial', $_POST["monto_pagar"], 'Pago');
    }
} else if (isset($_POST['pago_cuotas'])) {
    $objpago->set_cod_gasto($_POST['nro_gasto']); //
    $objpago->set_cod_pago_emitido($_POST['codigop']);
    $objpago->set_monto($_POST['total_parcial']); //
    $objpago->set_vuelto($_POST['vuelto']);
    $objpago->set_cod_vuelto_r($_POST['cod_vuelto_r']);
    $res = $objpago->registrarCuota($_POST['pago']);
    
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
$_GET['ruta'] = 'gasto1';
require_once 'plantilla.php';
