<?php

require_once "modelo/cuentabancariacopia.php";
require_once "modelo/bitacora.php";
require_once "modelo/banco.php";
require_once "modelo/tipocuenta.php";
require_once "modelo/divisa.php";

$objBanco = new Banco;
$objDivisa = new Divisa;
$objCuenta = new CuentaBancaria;
$objTipoCuenta = new Tipo_Cuenta;
$objbitacora = new Bitacora();

$banco=$objBanco->consultar();
$tipo=$objTipoCuenta->consultarTipo();
$Cuenta=$objCuenta->consultarCuenta();
$divisas=$objDivisa->consultarDivisas();

if (isset($_POST['buscar'])) {
    $numero_cuenta = $_POST['buscar'];
    $result = $objCuenta->getbuscar($numero_cuenta);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
   

} 
else if (isset($_POST["guardar"]) || isset($_POST["guardaru"])) {
    if (!empty($_POST["numerocuenta"])) {
        $errores = [];
        try {
            // Paso 1: Setear valores
            $objCuenta->setNumero($_POST["numerocuenta"]);
            $objCuenta->setBanco($_POST["banco"]);
            $objCuenta->setTipo($_POST["tipo_cuenta"]);
            $objCuenta->setSaldo($_POST["saldo"]);
            $objCuenta->setDivisa($_POST["divisa"]);
            $objCuenta->setStatus(1); 
    
            // Paso 2: Validar datos
            $objCuenta->check(); 
    
            // Paso 3: Verificar existencia
            if (!$objCuenta->getbuscar($_POST['numerocuenta'])) {
    
                // Paso 4: Insertar en DB
                $resul = $objCuenta->getcrearCuenta();
    
                if ($resul == 1) {
                    $registrar = [
                        "title" => "Exito",
                        "message" => "¡Registro exitoso!",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de Cuenta', $_POST["numerocuenta"], 'Cuenta Bancaria');
                } else {
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al intentar registrar la cuenta bancaria.",
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
    
        } catch (Exception $e) {
            $errores[] = $e->getMessage();
            $registrar = [
                "title" => "Error",
                "message" => implode(" ", $errores),
                "icon" => "error"
            ];
        }
    }
    else {
        $errores = [];
        try {
          // En el método donde procesas el POST
            $objCuenta->setNumero($_POST["numerocuenta"]); // nombre del campo en el formulario
            $objCuenta->setBanco($_POST["banco"]);
            $objCuenta->setTipo($_POST["tipo_cuenta"]); // nombre del campo en el formulario
            $objCuenta->setSaldo($_POST["saldo"]);
            $objCuenta->setDivisa($_POST["divisa"]);
            $objCuenta->setStatus(1); 
                        
            $objCuenta->check();
            
            if (!$objCuenta->getbuscar($_POST['numerocuenta'])) {
                $resul = $objCuenta->getcrearCuenta();
           // En el método donde procesas el POST
                $objCuenta->setNumero($_POST["numerocuenta"]); 
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
            if (!$objCuenta->getbuscar($_POST['numero_cuenta'])) {
              
                
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
}else if (isset($_POST['editar'])) {
    $cod = $_POST['cod_cuenta_bancaria'];
    $objCuenta->setCod($cod);
    $numero_cuenta = $_POST['numero_cuenta1'];  
    $saldo = $_POST['saldo1']; 
    $divisa = $_POST['divisa1'];  
    $status = $_POST['status'];  
    $banco = $_POST['banco1'];  
    $tipo_cuenta = $_POST['tipodecuenta1']; 

    // Validar que el número de cuenta no esté vacío
    if (!empty($numero_cuenta)) {
        try {
            // Establecer los valores en el objeto CuentaBancaria
            $objCuenta->setNumero($numero_cuenta);
            $objCuenta->setSaldo($saldo);
            $objCuenta->setDivisa($divisa);
            $objCuenta->setStatus($status);
            $objCuenta->setBanco($banco);
            $objCuenta->setTipo($tipo_cuenta);

            // Verificar si el número de cuenta ya existe en la base de datos
            if (!$objCuenta->getbuscar($numero_cuenta)) {
                // Realizar la actualización en la base de datos
                $resul = $objCuenta->geteditar();

                if ($resul == 1) {
                    // Registrar en la bitácora
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar Cuenta', $numero_cuenta, 'Cuenta Bancaria');
                    $editar = [
                        "title" => "Editado con éxito",
                        "message" => "La cuenta bancaria ha sido actualizada",
                        "icon" => "success"
                    ];
                } else {
                    $editar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al editar la cuenta bancaria.",
                        "icon" => "error"
                    ];
                }
            } else {
                $editar = [
                    "title" => "Error",
                    "message" => "La cuenta bancaria no existe.",
                    "icon" => "error"
                ];
            }
        } catch (Exception $e) {
            $errores[] = $e->getMessage();
            $editar = [
                "title" => "Error",
                "message" => implode(" ", $errores),
                "icon" => "error"
            ];
        }
    } else {
        $editar = [
            "title" => "Error",
            "message" => "El número de cuenta no puede estar vacío.",
            "icon" => "error"
        ];
    }
}





else if (isset($_POST['eliminar'])) {
    
    $cod_cuenta_bancaria = $_POST['eliminar'];
    $resul = $objCuenta->geteliminar($cod_cuenta_bancaria);

    if ($resul == 'success') {
        $eliminar = [
            "title" => "Eliminado con éxito",
            "message" => "La Cuenta ha sido eliminada",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar Cuenta Bancaria', "Eliminada la cuenta bancaria con el numero ".$_POST["eliminar"], 'Cuenta Bancaria');
    } else if ($resul == 'error_status') {
        $eliminar = [
            "title" => "Error",
            "message" => "La cuenta bancaria no se puede eliminar porque tiene status: activo",
            "icon" => "error"
        ];
    }  else if ($resul == 'error_delete') {
        $editar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la cuenta bancaria error delete",
            "icon" => "error"
        ];
    } else if($resul == 'error_query'){
        $editar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la cuenta bancaria error",
            "icon" => "error"
        ];
    }
}

$datos = $objCuenta->consultarCuenta();
$_GET['ruta'] = 'cuentabancariacopia';
require_once 'plantilla.php';
