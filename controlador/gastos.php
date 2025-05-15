<?php
//MODIFICACIÓN COMPLETA 29/04/2025
require_once "modelo/gasto.php";
require_once "modelo/pago_emitido.php";
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
} else if (isset($_POST['pagar_gasto'])) { /* LISTO */
    $errores = [];

    try {
        
        var_dump($_POST['montopagado']."->MAS");//EN REVISIÓN
        var_dump($_POST['monto_pagar']. "->SI");//EN REVISIÓN
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
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago de gasto', $_POST["montopagado"], 'Pago', $_POST["cod_gasto"]);
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
} else if (isset($_POST['eliminarG'])) {
    $errores = [];
    try {

        $objgasto->setDatos($_POST);
        $objgasto->check();
        $res = $objgasto->eliminarGasto();
    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }
    if (!empty($errores)) {
        $eliminar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else if ($res == 'success') {

        $eliminar = [
            "title" => "Eliminado con éxito",
            "message" => "El gasto ha sido eliminado",
            "icon" => "success"
        ];

        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminación de gasto', $_POST["cod_gasto"], 'Gasto');
    } else if ($res == 'error_associated') {
        $eliminar = [
            "title" => "Error",
            "message" => "Error al eliminar el gasto tiene pagos asociados",
            "icon" => "error"
        ];
    } else if ($res == 'error_delete') {
        $eliminar = [
            "title" => "Error",
            "message" => "Error al eliminar el gasto",
            "icon" => "error"
        ];
    } else if ($res == 'error_query') {
        $eliminar = [
            "title" => "Error",
            "message" => "Hubo un problema de consulta al eliminar el gasto",
            "icon" => "error"
        ];
    }
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
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cod_gasto'])) { //REVISO SI EL GASTO TIENE PAGOS ASOCIADOS Y ME EXTRAIGA EL MONTO DE ESTOS
    require_once 'modelo/pago_emitido.php';
    $objpago = new Pagos();
    $objpago->setDatos(['cod_gasto' => $_POST['cod_gasto']]);
    $resultado = $objpago->getGastos();


    if (!empty($resultado)) {
        echo json_encode(['success' => true, 'monto_total' => $resultado['monto_total']]);
        exit;
    } else {
        echo json_encode(['success' => false, 'monto_total' => 0]);
        exit;
    }
}

$gasto = $objpago->getGastos() ?? [];
$frecuencia = $objgasto->consultarFrecuencia();
$tipo = $objgasto->consultarTipo();
$categorias = $objgasto->consultarCategoria();
$condicion = $objgasto->consultarCondi();
$gastosF = $objgasto->consultarGastoF();
$gastosV = $objgasto->consultarGastoV();
$totalV = $objgasto->consultarTotalV();
$totalF = $objgasto->consultarTotalF();
$totalG = $objgasto->consultarTotalG();
$totalP = $objgasto->consultarTotalP();
$formaspago = $objpago->consultar();
$_GET['ruta'] = 'gastos';
require_once 'plantilla.php';
