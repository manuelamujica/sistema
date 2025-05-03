<?php

require_once "modelo/cajacopia.php"; 
require_once "modelo/bitacora.php";
require_once "modelo/divisa.php";
$objCaja = new Caja;
$objDivisa = new Divisa;
$objbitacora = new Bitacora();

$divisas=$objDivisa->consultarDivisas();

if (isset($_POST['buscar'])) {
    $nombre = $_POST['buscar'];
    $result = $objCaja->getbuscar($nombre);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
   

} else if (isset($_POST["guardar"]) || isset($_POST["guardaru"])) {
  
    if (!empty($_POST["nombre"])) {
       
        $errores = [];
        try {
            
            $objCaja->setNombre($_POST["nombre"]);
            $objCaja->setSaldo($_POST["saldo"]);
            $objCaja->setDivisa($_POST["divisa"]);
         
        
            $objCaja->check(); // Lanza excepción si hay errores
          
           
        } catch (Exception $e) {
            $errores[] = $e->getMessage();
        }
          // Si hay errores, se muestra el mensaje de error
    if (!empty($errores)) {
        $registrar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else {
            if (!$objCaja->getbuscar($_POST['nombre'])) {
              
                
                $resul = $objCaja->getcrearCaja();

                if ($resul == 1) {
                    $registrar = [
                        "title" => "Exito",
                        "message" => "¡Registro exitoso!",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de caja', $_POST["nombre"], 'Caja');
                } else {
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al intentar registrar la caja..",
                        "icon" => "error"
                    ];
                }
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "No se pudo registrar. La caja ya existe.",
                    "icon" => "error"
                ];
            }
        }
    } 
} else if (isset($_POST['editar'])) {

    $nombre = $_POST['nombre1'];
    $status = $_POST['status'];

        if ($nombre !== $_POST['origin']) {
            // Si la unidad cambió, verificamos si ya existe en la base de datos
            if ($objCaja->getbuscar($nombre)) {
                $advertencia = [
                    "title" => "Error",
                    "message" => "No se pudo registrar porque el nombre de la caja ya existe.",
                    "icon" => "error"
                ];
            }
        }
          // Si hay errores, se muestra el mensaje de error
          $errores = [];
        // Validaciones
        if (!empty($nombre)){
           try {
                $objCaja->setCod($_POST["cod_caja_oculto"]);
                $objCaja->setNombre($_POST["nombre1"]);
                $objCaja->setSaldo($_POST["saldo1"]);
                $objCaja->setDivisa($_POST["divisa1"]);
                $objCaja->setStatus($status);
                $objCaja->check(); // Lanza excepción si hay errores
                
                $res = $objCaja->geteditar();
                if ($res == 1) {
                    $editar = [
                        "title" => "Editado con éxito",
                        "message" => "La caja ha sido actualizada",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar Caja', $_POST["nombre"], 'Caja');
                } else {
                    $editar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al editar la caja",
                        "icon" => "error"
                    ];
                }
            } catch (Exception $e) {
                $errores[] = $e->getMessage();  
            }
            // Si hay errores, se muestra el mensaje de error
            if (!empty($errores)) {
                $editar = [
                    "title" => "Error",
                    "message" => implode(" ", $errores),
                    "icon" => "error"
                ];
            }
            
        } else {
            $editar = [
                "title" => "Error",
                "message" => "No se permiten campos vacios.",
                "icon" => "error"
            ];
        }
        
} else if (isset($_POST['eliminar'])) {
    
    $cod_caja = $_POST['eliminar'];
    $resul = $objCaja->geteliminar($cod_caja);

    if ($resul == 'success') {
        $eliminar = [
            "title" => "Eliminado con éxito",
            "message" => "La caja ha sido eliminada",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar Caja', "Eliminado la caja con el código ".$_POST["eliminar"], 'Caja');
    } else if ($resul == 'error_status') {
        $eliminar = [
            "title" => "Error",
            "message" => "La caja no se puede eliminar porque tiene status: activo",
            "icon" => "error"
        ];
    } else if ($resul == 'error_associated') {
        $eliminar = [
            "title" => "Error",
            "message" => "La caja no se puede eliminar porque tiene productos asociados",
            "icon" => "error"
        ];
    } else if ($resul == 'error_delete') {
        $editar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la caja error delete",
            "icon" => "error"
        ];
    } else if($resul == 'error_query'){
        $editar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la caja error",
            "icon" => "error"
        ];
    }
}

$datos = $objCaja->consultarCaja();
$_GET['ruta'] = 'cajacopia';
require_once 'plantilla.php';
