<?php
require_once 'modelo/compras.php';
require_once 'modelo/productos.php';
require_once 'modelo/bitacora.php';
require_once 'modelo/pago_emitido.php';


$objbitacora = new Bitacora();
$objCompras = new Compra();
$objProducto = new Productos();
$objpago = new Pagos();
$categoria = $objProducto->consultarCategoria();
$unidad = $objProducto->consultarUnidad();


if (isset($_POST['buscar'])) {
    $resul = $objCompras->getbuscar_p($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($resul);
    exit;
} else if (isset($_POST['b_lotes']) && isset($_POST['cod'])) {
    $re = $objCompras->buscar_l($_POST['b_lotes'], $_POST['cod']);
    header('Content-Type: application/json');
    echo json_encode($re);
    exit;
} else if (isset($_POST['detallep'])) {
    $detalle = $objCompras->b_detalle($_POST['detallep']);
    header('Content-Type: application/json');
    echo json_encode($detalle);
    exit;
} else if (isset($_POST["registrar"])) {
    if (
        !empty($_POST["subtotal"]) && !empty($_POST["total_general"]) &&
        !empty($_POST["cod_prov"]) && !empty($_POST["fecha"])
    ) {
        if (isset($_POST['productos'])) {
            $objCompras->setCod1($_POST['cod_prov']);
            $objCompras->setsubtotal($_POST['subtotal']);
            $objCompras->settotal($_POST['total_general']);
            $objCompras->setimpuesto_total($_POST['impuesto_total']);
            $objCompras->setfecha($_POST['fecha']);
            $resul = $objCompras->getRegistrarr($_POST['productos']);
            if ($resul == 1) {
                $registrar = [
                    "title" => "La compra ha sido registrada exitosamente.",
                    "icon" => "success"
                ];
                $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de compra', $_POST["subtotal"], 'Compras');
            } else {
                $registrar = [
                    "title" => "Error al registrar la compra.",
                    "icon" => "error"
                ];
            }
        } else {
            $registrar = [
                "title" => "No se encontraron productos en tu solicitud.",
                "icon" => "error"
            ];
        }
    } else {
        $registrar = [
            "title" => "Faltan campos obligatorios.",
            "icon" => "error"
        ];
    }
} else if (isset($_POST['anular'])) {
    if (!empty($_POST['codcom'])) {
        $resul = $objCompras->anular($_POST["codcom"]);

        if ($resul == 1) {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "la  compra ha sido eliminada",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Anulación de compra', $_POST["codcom"], 'Compras');
        } elseif ($resul == 0) {
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un problema al eliminar la compra",
                "icon" => "error"
            ];
        }
    }
} else if (isset($_POST['pagar_compra'])) { /* LISTO */
    $errores = [];

    try {
        $objpago->setDatos($_POST);

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
        $registrarPG = [

            "title" => "El pago del gasto ha sido registrado exitosamente.",
            "message" => "El gasto se ha completado.",
            "icon" => "success"

        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago de gasto', $_POST["montopagado"], 'Pago', $_POST["cod_compra"]);
    } else if ($res > 0) {
        $registrarPG = [

            "title" => "Se ha registrado un pago parcial.",
            "message" => "El monto pendiente es de " . $res . "Bs.",
            "icon" => "success"

        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago parcial', $_POST["montototal"], 'Pago');
    } else if ($res == 'error_saldo') {
        $registrarPG = [

            "title" => "Advertencia",
            "message" => "No se pudo completar el pago porque el monto pagado es mayo al saldo disponible.",
            "icon" => "warning"

        ];
    } else {
        $registrarPG = [

            "title" => "Error",
            "message" => "Descripción detallada del error",
            "icon" => "error"

        ];
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cod_compra'])) {
    require_once 'modelo/pago_emitido.php';
    $objpago = new Pagos();
    $objpago->setDatos(['cod_compra' => $_POST['cod_compra']]);
    $resultado = $objpago->getCompras();


    if (!empty($resultado)) {
        echo json_encode(['success' => true, 'monto_total' => $resultado['monto_total']]);
        exit;
    } else {
        echo json_encode(['success' => false, 'monto_total' => 0]);
        exit;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vuelto'])) {
    $errores = [];
    try {
        $objpago->setDatos($_POST);
        $objpago->check();
        $res = $objpago->v();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
    if (!empty($errores)) {
        echo json_encode(['success' => false, 'message' => implode(" ", $errores)]);
        exit;
    } else if ($res == 1) {
        echo json_encode(['success' => true, 'message' => 'Vuelto registrado correctamente.']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'El vuelto no esta completo.']);
        exit;
    }
}

$opciones = $objCompras->divisas();
$compra = $objCompras->getconsultar();
$formaspago = $objpago->consultar();
$_GET['ruta'] = 'compras';
require_once 'plantilla.php';
