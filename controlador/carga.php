<?php
require_once "modelo/carga.php";
require_once "modelo/dcarga.php";
require_once "modelo/bitacora.php";

$objcarga = new Carga();
$objbitacora = new Bitacora();
$objcargad = new Dcarga();


// Manejo de búsqueda de carga
if (isset($_POST['buscar'])) {
    $resul = $objcargad->b_productos($_POST['buscar']);
    header('Content-type: application/json');
    echo json_encode($resul);
    exit;
} else if (isset($_POST['detalle'])) {
    $detalle = $objcargad->gettodoo($_POST['detalle']);
    header('Content-type: application/json');
    echo json_encode($detalle);
    exit;
} else if (isset($_POST['registrarD'])) {
    // Inicializar el array de respuesta
    $response = [];
    if (!empty($_POST['cod_presentacion'])) {
        $cod_producto = $_POST['cod_presentacion'];
        $fecha = $_POST['fecha_vencimiento'];
        $lote = $_POST['lote'];
        $objcarga->setCodp($cod_producto);
        $objcarga->setlote($lote);
        $objcarga->setFechaV($fecha);
        $res = $objcarga->getcrearPro();

        if ($res == 1) {
            $response['status'] = 'success';
            $response['data'] = [
                "title" => "Registrado con éxito",
                "message" => "El detalle se actualizo, vuelva al registro de carga",
                "icon" => "success"
            ];
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No se pudo registrar el producto';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'El código del producto está vacío';
    }
    // Enviar la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} else if (isset($_POST['verificarDetalle'])) {
    $producto = $_POST['id'];
    $detalle = $objcargad->verificarDetalleProducto($producto);
    header('Content-type: application/json');

    // Verifica si hay detalles
    if ($detalle && isset($detalle['cod_detallep'])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit;
}
// Manejo de guardar carga
else if (isset($_POST['guardar'])) {
    $errores = [];
    try {
        $objcargad->setFecha($_POST['fecha_hora']);
        $objcargad->setDes($_POST['descripcion']);
        $objcargad->check();
        $res = $objcargad->getcrear($_POST['productos']);
        
    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }
    if (!empty($errores)) {
        $registrar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else if ($res  > 0) {
        // Si no hay errores, proceder con el registro
        $registrar = [
            "title" => "Registrado con éxito",
            "message" => "La carga ha sido registrada",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de carga', $_POST["descripcion"], 'Carga');
    }else {
        $registrar = [
            "title" => "Error",
            "message" => "Hubo un problema al registrar la carga",
            "icon" => "error"
        ];
    }
    
}

// Código adicional para manejar otras operaciones
$productos = $objcargad->getP();
$detalles = $objcargad->getmos();
$carga = $objcarga->getmosc();
$_GET['ruta'] = 'carga';
require_once 'plantilla.php';
