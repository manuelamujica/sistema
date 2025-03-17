<?php
require_once 'modelo/descarga.php';
require_once 'modelo/bitacora.php';

$objDescarga = new Descarga();
$objbitacora = new Bitacora();

//BUSCAR DETALLE PRODUCTOS (LISTADO)
if(isset($_POST['buscar'])){
    $resul = $objDescarga->buscar($_POST['buscar']);
    header('Content-type: application/json');
    echo json_encode($resul);
    exit;
    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Buscar producto', $_POST['buscar'], 'Productos');

//CONSULTAR DETALLE DESCARGA
}else if(isset($_POST['detalled'])){
    $result=$objDescarga->consultardetalledescarga($_POST['detalled']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;


//REGISTRAR
}else if(isset($_POST['guardar'])){
    if(!empty($_POST['fecha']) && !empty($_POST['descripcion'])){
        /*Validar fecha strtotime(), convierte una fecha y hora a un timestamp (segundos desde la "época Unix").
        $fechaingresada = $_POST['fecha'];
        $timestamp = strtotime($fechaingresada);
        // Obtener el timestamp de la fecha y hora actual
        $fechaactual = time();

        if ($timestamp < $fechaactual) {*/
            
            $length = strlen($_POST['descripcion']);
            if(preg_match('/^[a-zA-ZÀ-ÿ0-9!.,\'\s-]+$/', $_POST['descripcion']) && $length < 100){
                $objDescarga->setfecha($_POST['fecha']);
                $objDescarga->setdescripcion($_POST['descripcion']);
                
                $errorCantidad = false;
                
                foreach ($_POST['productos'] as $producto) {
                    if (empty($producto['cantidad'])) {
                        $r = [
                            "title" => "Error",
                            "message" => "La cantidad a descargar no puede estar vacía.",
                            "icon" => "error"
                        ];
                        $errorCantidad = true;
                        break;  // Si hay 1 un error, se detiene la ejecución
                    }
                    if($producto['cantidad'] > $producto['stock']){
                        $r = [
                            "title" => "Error",
                            "message" => "La cantidad del producto no puede ser mayor al stock.",
                            "icon" => "error"
                        ];
                        $errorCantidad = true;
                        break;  // Si hay 1 un error, se detiene la ejecución
                    }
                    $objDescarga->setcantidad($producto['cantidad']);
                }

                // Si no hay errores en las cantidades, contunuamos
                if (!$errorCantidad) {
                    $resul = $objDescarga->registrar($_POST['productos']);
                    if ($resul) {
                        $registrar = [
                            "title" => "Registrado con éxito",
                            "message" => "La descarga ha sido registrada",
                            "icon" => "success"
                        ];
                        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de descarga', $_POST["descripcion"], 'Descarga');

                    } else {
                        $registrar = [
                            "title" => "Error",
                            "message" => "Hubo un problema al registrar la descarga",
                            "icon" => "error"
                        ];
                      
                    }
                }
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "No se pudo registrar. Descripcion no valida.",
                    "icon" => "error"
                ];
                
            }
    } else {
        $registrar = [
            "title" => "Error",
            "message" => "Hubo un problema al registrar la descarga",
            "icon" => "error"
        ];
    }
}
$descarga = $objDescarga->consultardescarga();
$_GET['ruta'] = 'descarga';
require_once 'plantilla.php';