<?php
require_once "modelo/formula.php";

$objformula = new Formula();


if (isset($_POST['buscarF'])) {
    $expresion = $_POST['buscarF'];
    GlobalVariables::set('expresion', $expresion);
    $res = $objformula->getbuscarformula();
    header('Content-Type: application/json');
    echo json_encode($res);
    exit();
} else if (isset($_POST['buscar'])) {
    $var_nombre = $_POST['buscar'];
    GlobalVariables::set('nombre_var', $var_nombre);
    $res = $objformula->mostrarvariable();

    $result = [];
    foreach ($res as $variable) {
        $result[] = [
            'cod_var' => $variable['cod_var'],
            'nombre_var' => $variable['nombre_var']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($result);
    exit();
} else if (isset($_POST['obtenerOperadores'])) {
    $res = $objformula->consultaroperador();
    header('Content-Type: application/json');
    echo json_encode($res); 
    exit();
} else if (isset($_POST['registrarVariable'])) {
    $nombre_var = $_POST['variable'];

    $res = $objformula->getregistrarvariable($nombre_var);
    if ($res == 1) {
        echo json_encode(['status' => 'success', 'message' => 'Variable registrada correctamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar la variable']);
    }
    exit();
} else if (isset($_POST['registrarFormula'])) {
    $registroExitoso = true;
    $formula = $_POST['formulaNombre'];
    $expresion = $_POST['expresion'];
    $detalles = json_decode($_POST['detalles'], true); 

    GlobalVariables::set('nombre', $formula);
    GlobalVariables::set('expresion', $expresion);

    foreach ($detalles as $detalle) {
        $cod_var = $detalle['cod_var'];
        $cod_operador = $detalle['operador'];
        $cod_var2 = $detalle['cod_var2'];
        GlobalVariables::set('cod_var', $cod_var);
        GlobalVariables::set('cod_operador', $cod_operador);
        GlobalVariables::set('cod_var2', $cod_var2);
    }
    $res = $objformula->getregisformula();

    if ($res !== 1) {
        echo json_encode(['status' => 'error', 'message' => "Los detalles de la fórmula no se han registrado correctamente."]);
        exit();
    }

    if ($registroExitoso) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Fórmula registrada correctamente.']);
        exit();
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar la fórmula en la base de datos.']);
        exit();
    }
} else if (isset($_POST['editarf'])) {
    $nombre = $_POST['nombref'];
    $nombre_origin = $_POST['nombref_origin'];
    $cod_formula = $_POST['cod_formula'];
    $statusf = $_POST['statusf'];

    GlobalVariables::set('nombre', $nombre);
    GlobalVariables::set('nombre_origin', $nombre_origin);
    GlobalVariables::set('cod_formula', $cod_formula);
    GlobalVariables::set('status', $statusf);

    $res = $objformula->geteditarformula();

    if ($res == 1) {
        $editarf = [
            "title" => "Editado con éxito",
            "message" => "La formula ha sido actualizada",
            "icon" => "success"
        ];
    } else if ($res == 2) {
        $editarf = [
            "title" => "Advertencia",
            "message" => "La formula ya existe. No se ha actualizado",
            "icon" => "warning"
        ];
    } else {
        $editarf = [
            "title" => "Error",
            "message" => "Hubo un problema al editar la formula",
            "icon" => "error"
        ];
    }
} else if (isset($_POST['eliminarf'])) {
    $cod_formula = $_POST['eliminarf'];
    GlobalVariables::set('cod_formula', $cod_formula);

    $resul = $objformula->geteliminar();

    if ($resul == 'success') {
        $eliminarf = [
            "title" => "Eliminado con éxito",
            "message" => "La formúla ha sido eliminada",
            "icon" => "success"
        ];
    } else if ($resul == 'error_status') {
        $eliminarf = [
            "title" => "Advertencia",
            "message" => "La formúla no se puede eliminar porque tiene estatus: activo",
            "icon" => "warning"
        ];
    } else if ($resul == 'error_associated') {
        $eliminarf = [
            "title" => "Advertencia",
            "message" => "La formúla no se puede eliminar porque tiene tipos de nómina asociados",
            "icon" => "warning"
        ];
    } else if ($resul == 'error_delete') {
        $eliminarf = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la formúla error delete",
            "icon" => "error"
        ];
    } else if ($resul == 'error_query') {
        $eliminarf = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la formúla error en sentencia",
            "icon" => "error"
        ];
    }
} else if (isset($_POST['editarv'])) { //VARIABLES
    $nombre_var = $_POST['nombre_var'] ?? null;
    $nombre_originv = $_POST['nombre_originv'] ?? null;
    $cod_var = $_POST['cod_var'] ?? null;
    $statusv = $_POST['statusv'] ?? null;

    GlobalVariables::set('nombre_var', $nombre_var);
    GlobalVariables::set('nombre_originv', $nombre_originv);
    GlobalVariables::set('cod_var', $cod_var);
    GlobalVariables::set('statusv', $statusv);

    $res = $objformula->geteditarvaiable();

    if ($res == 1) {
        $editarv = [
            "title" => "Editado con éxito",
            "message" => "La variable ha sido actualizada",
            "icon" => "success"
        ];
    } else if ($res == 2) {
        $editarv = [
            "title" => "Advertencia",
            "message" => "La variable ya existe. No se ha actualizado",
            "icon" => "warning"
        ];
    } else {
        $editarv = [
            "title" => "Error",
            "message" => "Hubo un problema al editar la variable",
            "icon" => "error"
        ];
    }
} else if (isset($_POST['eliminarv'])) {
    $cod_var = $_POST['eliminarv'];
    GlobalVariables::set('cod_var', $cod_var);

    $resul = $objformula->geteliminarv();

    if ($resul == 'success') {
        $eliminarv = [
            "title" => "Eliminado con éxito",
            "message" => "La variable ha sido eliminada",
            "icon" => "success"
        ];
    } else if ($resul == 'error_status') {
        $eliminarv = [
            "title" => "Advertencia",
            "message" => "La variable no se puede eliminar porque tiene estatus: activo",
            "icon" => "warning"
        ];
    } else if ($resul == 'error_associated') {
        $eliminarv = [
            "title" => "Advertencia",
            "message" => "La variable no se puede eliminar porque tiene tipos de nómina asociados",
            "icon" => "warning"
        ];
    } else if ($resul == 'error_delete') {
        $eliminarv = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la variable error delete",
            "icon" => "error"
        ];
    } else if ($resul == 'error_query') {
        $eliminarv = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la variable error en sentencia",
            "icon" => "error"
        ];
    }
}

$datos = $objformula->consultarvariable();
$datosf = $objformula->mostrarformula();

//$operadores = $objformula->consultaroperador();
$_GET['ruta'] = 'nomina';
require_once 'plantilla.php';
