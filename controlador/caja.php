<?php
# Requiere modelos
require_once 'modelo/caja.php';
require_once 'modelo/bitacora.php';
require_once 'modelo/divisa.php';

# Instanciar objetos
$objCaja = new Caja();
$objBitacora = new Bitacora();
$obj = new Divisa();

# Obtener divisas para selects
$divisas=$obj->consultar();


// ABRIR CAJA
if (isset($_POST['abrir'])) {
    if (!empty($_POST['nombre']) && !empty($_POST['fecha_apertura']) && 
        !empty($_POST['monto_apertura']) && !empty($_POST['cod_divisa'])) {

        $objCaja->setNombre($_POST['nombre']);
        $objCaja->setCodUsuario($_SESSION['cod_usuario']);
        $objCaja->setCodDivisa($_POST['cod_divisa']);
        $objCaja->setFechaApertura($_POST['fecha_apertura']);
        $objCaja->setMontoApertura($_POST['monto_apertura']);
        $objCaja->setEstado('abierta');

        $resultado = $objCaja->abrirCaja();

        if ($resultado == 1) {
            $respuesta = [
                "title" => "Caja abierta",
                "message" => "Se abrió correctamente la caja ".$_POST['nombre'],
                "icon" => "success"
            ];
            $objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Abrir caja', $_POST['nombre'], 'Caja');
        } else {
            $respuesta = [
                "title" => "Error",
                "message" => "No se pudo abrir la caja",
                "icon" => "error"
            ];
        }
    } else {
        $respuesta = [
            "title" => "Error",
            "message" => "Todos los campos son obligatorios",
            "icon" => "error"
        ];
    }
}

// EDITAR CAJA
elseif (isset($_POST['editar'])) {
    if (!empty($_POST['cod_caja']) && !empty($_POST['nombre']) && 
        !empty($_POST['cod_divisa']) && !empty($_POST['monto_apertura'])) {

        $objCaja->setCodCaja($_POST['cod_caja']);
        $objCaja->setNombre($_POST['nombre']);
        $objCaja->setCodDivisa($_POST['cod_divisa']);
        $objCaja->setMontoApertura($_POST['monto_apertura']);
        $objCaja->setEstado($_POST['estado']);

        $resultado = $objCaja->editarCaja();

        if ($resultado == 1) {
            $respuesta = [
                "title" => "Caja actualizada",
                "message" => "Los datos de la caja se actualizaron correctamente",
                "icon" => "success"
            ];
            $objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar caja', $_POST['cod_caja'], 'Caja');
        } else {
            $respuesta = [
                "title" => "Error",
                "message" => "No se pudo actualizar la caja",
                "icon" => "error"
            ];
        }
    } else {
        $respuesta = [
            "title" => "Error",
            "message" => "Todos los campos son obligatorios",
            "icon" => "error"
        ];
    }
}

// ELIMINAR CAJA
elseif (isset($_POST['eliminar'])) {
    if (!empty($_POST['cod_caja'])) {
        $resultado = $objCaja->eliminarCaja($_POST['cod_caja']);

        if ($resultado == 1) {
            $respuesta = [
                "title" => "Caja eliminada",
                "message" => "La caja y sus movimientos fueron eliminados",
                "icon" => "success"
            ];
            $objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar caja', $_POST['cod_caja'], 'Caja');
        } else {
            $respuesta = [
                "title" => "Error",
                "message" => "No se pudo eliminar la caja",
                "icon" => "error"
            ];
        }
    }
}

// CERRAR CAJA
elseif (isset($_POST['cerrar'])) {
    if (!empty($_POST['cod_caja']) && !empty($_POST['fecha_cierre']) && !empty($_POST['monto_cierre'])) {

        $objCaja->setFechaCierre($_POST['fecha_cierre']);
        $objCaja->setMontoCierre($_POST['monto_cierre']);
        $objCaja->setEstado('cerrada');

        $resultado = $objCaja->cerrarCaja($_POST['cod_caja']);

        if ($resultado == 1) {
            $respuesta = [
                "title" => "Caja cerrada",
                "message" => "Caja cerrada correctamente",
                "icon" => "success"
            ];
            $objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Cerrar caja', $_POST['cod_caja'], 'Caja');
        } else {
            $respuesta = [
                "title" => "Error",
                "message" => "No se pudo cerrar la caja",
                "icon" => "error"
            ];
        }
    }
}

// AGREGAR MOVIMIENTO
elseif (isset($_POST['agregar_detalle'])) {
    if (!empty($_POST['cod_caja']) && !empty($_POST['descripcion']) && 
        !empty($_POST['monto']) && !empty($_POST['tipo'])) {

        $objCaja->setDescripcion($_POST['descripcion']);
        $objCaja->setMonto($_POST['monto']);
        $objCaja->setTipo($_POST['tipo']);

        $resultado = $objCaja->agregarDetalle($_POST['cod_caja']);

        if ($resultado == 1) {
            $respuesta = [
                "title" => "Movimiento registrado",
                "message" => "El movimiento fue registrado exitosamente",
                "icon" => "success"
            ];
            $objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Agregar movimiento', $_POST['descripcion'], 'Caja');
        } else {
            $respuesta = [
                "title" => "Error",
                "message" => "No se pudo registrar el movimiento",
                "icon" => "error"
            ];
        }
    }
}

// CONSULTAR DETALLE DE CAJA (AJAX)
elseif (isset($_POST['detalle_caja'])) {
    $detalle = $objCaja->detalleCaja($_POST['detalle_caja']);
    header('Content-Type: application/json');
    echo json_encode($detalle);
    exit;
}

// CONSULTAR CAJA ABIERTA (AJAX)
elseif (isset($_POST['caja_abierta'])) {
    $abierta = $objCaja->cajaAbierta();
    header('Content-Type: application/json');
    echo json_encode($abierta);
    exit;
}

// OBTENER INFO CAJA (AJAX)
elseif (isset($_POST['obtener_caja'])) {
    $caja = $objCaja->obtenerCaja($_POST['cod_caja']);
    header('Content-Type: application/json');
    echo json_encode($caja);
    exit;
}

// CONSULTAR TODAS LAS CAJAS

$registro = $objCaja->mostrarCajas();

// Verificar si hay caja abierta
$cajaAbierta = $objCaja->cajaAbierta();

// Ruta para plantilla
$_GET['ruta'] = 'caja';
require_once 'vista/caja.php';
require_once 'vista/plantilla.php';