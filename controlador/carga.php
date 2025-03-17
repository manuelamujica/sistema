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

}else if (isset($_POST['detalle'])) {
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
    if (!empty($_POST['fecha_hora']) && !empty($_POST['descripcion'])) {
        if (preg_match("/^[a-zA-ZÀ-ÿ0-9\s]+$/", $_POST['descripcion'])) {
            $objcarga->setFecha($_POST['fecha_hora']);
            $objcarga->setDes($_POST['descripcion']);
            $resul = $objcarga->getcrear(); // Registrar carga

            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de carga', $_POST["descripcion"], 'Carga');

            if ($resul == 1) {
                // Cambiar a la forma correcta de acceder a los productos
                $productos = $_POST['productos'];
                $cargaExitosa = true; // Para verificar si la carga fue exitosa

                foreach ($productos as $producto) {
                    $codigo1 = $producto['codigo1'];
                    $cantidad = $producto['cantidad'];


                    // Verifica que el código y la cantidad no estén vacíos
                    if (!empty($codigo1) && !empty($cantidad)) {
                        $detalle = $objcargad->verificarDetalleProducto($codigo1);
                        $objcargad->setcodpro($codigo1);

                        if ($detalle && isset($detalle['cod_detallep'])) {
                            // Si el detalle existe, registrar el producto
                            $detallep = $detalle['cod_detallep'];
                            $objcargad->setcodp($detallep);
                            $objcargad->setcantidad($cantidad);
                            $regis = $objcargad->getcrear();

                            if ($regis != 1) {
                                $cargaExitosa = false;
                                $registrar = [
                                    "title" => "Error",
                                    "message" => "El error al registrar el producto: " . $codigo,
                                    "icon" => "error"
                                ];
                            }
                        } else {
                            $cargaExitosa = false;
                            $registrar = [
                                "title" => "Error",
                                "message" => "El producto " . $codigo . " no tiene detalle",
                                "icon" => "error"
                            ];
                        }
                    } else {
                        $cargaExitosa = false;
                        $registrar = [
                            "title" => "Error",
                            "message" => "Código o cantidad vacía para el producto.",
                            "icon" => "error"
                        ];
                    }
                }

                if ($cargaExitosa) {
                    $registrar = [
                        "title" => "Registrado con éxito",
                        "message" => "La carga ha sido registrada",
                        "icon" => "success"
                    ];
                    var_dump("paso aqui");
                }
            }
        } else {
            $registrar = [
                "title" => "Error",
                "message" => "La carga ha sido registrada",
                "icon" => "error"
            ];
        }
    } else {
        $registrar = [
            "title" => "Error",
            "message" => "Los campos obligatorios no pueden estar vacíos",
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
