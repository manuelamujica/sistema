<?php
require_once "modelo/carga.php";
require_once "modelo/dcarga.php";
require_once "modelo/detallep.php"; //AQUI DEBERIA LLAMAR AL MODELO DE PRODUCTO

$objcarga = new Carga();
$objcargad = new Dcarga();
$objprod = new Detallep();

// Manejo de búsqueda de carga
if (isset($_POST['buscar'])) {
    $resul = $objcarga->getbuscar($_POST['buscar']);
    header('Content-type: application/json');
    echo json_encode($resul);
    exit;
} else if (isset($_POST['registrarD'])) {
    // Inicializar el array de respuesta
    $response = [];
    if (!empty($_POST['cod_presentacion'])) {
        $cod_producto = $_POST['cod_presentacion'];
        $fecha = $_POST['fecha_vencimiento'];
        $lote = $_POST['lote'];
        $objprod->setCodp($cod_producto);
        $objprod->setlote($lote);
        $objprod->setFecha($fecha);
        $res = $objprod->getcrear();

        if ($res == 1) {
            $response['status'] = 'success';
            $response['data'] = [
                "title" => "Registrado con éxito",
                "message" => "El detalle se actualizo, vuelva al registro de carga",
                "icon" => "success"
            ];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar el producto']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'El código del producto está vacío']);
    }
    // Enviar la respuesta como JSON
    //header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}else if (isset($_POST['verificarDetalle'])) {
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
    // Inicializar el array de respuesta
    $response = [];
    // Verificar que la fecha y descripción no estén vacías
    if (!empty($_POST['fecha']) && !empty($_POST['descripcion'])) {
        if (preg_match("/^[a-zA-Z0-9\.,\s]+$/", $_POST['descripcion'])) {
            $objcarga->setFecha($_POST['fecha']);
            $objcarga->setDes($_POST['descripcion']);
            $resul = $objcarga->getcrear(); // Registrar carga

            if ($resul == 1) {
                $producto = $_POST['cod_detallep']; // Cambiar a cod_detallep
                $cantidad = $_POST['cantidad'];
                $cont = count($producto);
                $cargaExitosa = true; // Para verificar si la carga fue exitosa

                for ($i = 0; $i < $cont; $i++) {
                    $detalle = $objcargad->verificarDetalleProducto($producto[$i]);
                    $objcargad->setcodpro($producto[$i]);
                    

                    if ($detalle && isset($detalle['cod_detallep'])) {


                        // Si el detalle existe, registrar el producto
                        $detallep = $detalle['cod_detallep'];


                        $objcargad->setcodp($detallep);
                        $objcargad->setcantidad($cantidad[$i]);
                        $regis = $objcargad->getcrear();

                        if ($regis != 1) {
                            $response['status'] = 'error';
                            $cargaExitosa = false; // Marcar como no exitosa
                            $response['error'];
                            $response['data'] = [
                                "title" => "Error",
                                "message" => "El error al registrar el producto: " . $producto[$i],
                                "icon" => "error"
                            ];
                        }
                    } else {
                        $cargaExitosa = false; // Marcar como no exitosa
                        // Si no hay detalle, mostrar mensaje
                        $response['status'] = 'error';
                        $response['data'] = [
                            "title" => "Error",
                            "message" => "El producto " . $producto[$i] . " no tiene detalle",
                            "icon" => "error"
                        ];
                    }
                }

                if ($cargaExitosa) {
                    $response['status'] = 'success';
                    $response['data'] = [
                        "title" => "Registrado con éxito",
                        "message" => "La carga ha sido registrada",
                        "icon" => "success"
                    ];
                } else {
                    $response['status'] = 'error';
                    $response['data'] = [
                        "title" => "Error",
                        "message" => "Se cayo la pagina",
                        "icon" => "error"
                    ];
                }
            } else if ($resul != 1) {
                $cargaExitosa = false; // Marcar como no exitosa
                $response['status'] = 'error';
                $response['data'] = [
                    "title" => "Error",
                    "message" => "No se pudo registrar la carga",
                    "icon" => "error"
                ];
            }
        }else {
            $response['status'] = 'error';
            $response['data'] = [
                "title" => "Error",
                "message" => "La descripción no puede contener caracteres especiales",
                "icon" => "error"
            ];
        }
    } else {
        $response['status'] = 'error';
        $response['data'] = [
            "title" => "Error",
            "message" => "Los campos obligatorios no pueden estar vacíos",
            "icon" => "error"
        ];
    }

    // Enviar la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
// Manejo de edición de carga
else if (isset($_POST['editar'])) {
    $cod_carga = $_POST['cod_carga'];
    $des = $_POST['descripcion'];
    $objcarga->setCod($cod_carga);
    $objcarga->setDes($des);
    $res = $objcarga->geteditar();
    if ($res == 1) {
        echo "<script>alert('Carga actualizada con éxito'); window.location.href='?pagina=carga'; </script>";
    } else {
        echo "<script>alert('No se pudo actualizar'); window.location.href='?pagina=carga'; </script>";
    }
}

// Código adicional para manejar otras operaciones
$productos = $objcargad->getP();
$detalles = $objcargad->getmos();
$carga = $objcarga->getmosc();
$datos = $objcargad->gettodo();
$_GET['ruta'] = 'carga';
require_once 'plantilla.php';
