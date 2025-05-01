<?php

require_once "modelo/cuentabancariacopia.php";
require_once "modelo/bitacora.php";
require_once "modelo/tipocuenta.php";
require_once "modelo/divisa.php";
$objDivisa = new Divisa;
$objCuenta = new CuentaBancaria;
$objTipoCuenta = new Tipo_Cuenta;
$objbitacora = new Bitacora();

$tipo=$objTipoCuenta->consultar();
$Cuenta=$objCuenta->consultarCuenta();
$divisas=$objDivisa->consultarDivisas();

if (isset($_POST['buscar'])) {
    $numero_cuenta = $_POST['buscar'];
    $result = $objCuenta->getbuscar($numero_cuenta);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
   

} // En la parte de guardar
else if (isset($_POST["guardar"]) || isset($_POST["guardaru"])) {
    if (!empty($_POST["numero_cuenta"])) {
        $errores = [];
        try {
            $objCuenta->setNumero($_POST["numero_cuenta"]);
            $objCuenta->setBanco($_POST["banco"]); // Asegúrate que coincida con el name del input
            $objCuenta->setTipo($_POST["tipo_cuenta"]);
            $objCuenta->setSaldo($_POST["saldo"]);
            $objCuenta->setDivisa($_POST["divisa"]);
            
            $objCuenta->check();
            
            if (!$objCuenta->getbuscar($_POST['numero_cuenta'])) {
                $resul = $objCuenta->getcrearCuenta();
            $objCuenta->setNumero($_POST["numero_cuenta"]);
            $objCuenta->setBanco($_POST["banco"]);
            $objCuenta->setTipo($_POST["tipo_cuenta"]);
            $objCuenta->setSaldo($_POST["saldo"]);
            $objCuenta->setDivisa($_POST["divisa"]);
         
        
            $objCuenta->check(); // Lanza excepción si hay errores
          
           
        }} catch (Exception $e) {
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
            if (!$objCuenta->getbuscar($_POST['nombre'])) {
              
                
                $resul = $objCuenta->getcrearCuenta();

                if ($resul == 1) {
                    $registrar = [
                        "title" => "Exito",
                        "message" => "¡Registro exitoso!",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de Cuenta', $_POST["numero_cuenta"], 'Cuenta Bancaria');
                } else {
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al intentar registrar la cuenta bancaria..",
                        "icon" => "error"
                    ];
                }
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "No se pudo registrar. La cuenta bancaria ya existe.",
                    "icon" => "error"
                ];
            }
        }
    } 
} else if (isset($_POST['editar'])) {

    $numero_cuenta = $_POST['numero_cuenta'];
    $status = $_POST['status'];

        if ($nombre !== $_POST['origin']) {
            // Si la unidad cambió, verificamos si ya existe en la base de datos
            if ($objCuenta->getbuscar($numero_cuenta)) {
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
                $objCuenta->setCod($_POST["cod_cuenta_bancaria_oculto"]);
                $objCuenta->setNombre($_POST["numero_cuenta1"]);
                $objCuenta->setSaldo($_POST["saldo1"]);
                $objCuenta->setDivisa($_POST["divisa1"]);
                $objCuenta->setBanco($_POST["banco1"]);
                $objCuenta->setTipo($_POST["tipodecuenta1"]);
                $objCuenta->setStatus($status);
                $objCuenta->check(); // Lanza excepción si hay errores
                
                $res = $objCuenta->geteditar();
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

$datos = $objCuenta->consultarCuenta();
$_GET['ruta'] = 'cuentabancariacopia';
require_once 'plantilla.php';
