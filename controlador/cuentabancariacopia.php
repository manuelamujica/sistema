<?php

require_once "modelo/cuentabancariacopia.php";
require_once "modelo/bitacora.php";
require_once "modelo/banco.php";
//require_once "modelo/tipocuenta.php";
require_once "modelo/divisa.php";

$objBanco = new Banco;
$objDivisa = new Divisa;
$objCuenta = new CuentaBancaria;
//$objTipoCuenta = new Tipo_Cuenta;
$objbitacora = new Bitacora();

$banco=$objBanco->consultar();
//$tipo=$objTipoCuenta->consultarTipo();
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
      

            $data = [
                'numero_cuenta' => $_POST["numerocuenta"],
                'divisa' => $_POST["divisa"],
                'cod_banco' => $_POST["banco"],
                'cod_tipo_cuenta' => $_POST["tipo_cuenta"],
                'saldo' => $_POST["saldo"],
                'status' => 1,
               
                
            ];
            
            $objCuenta->setData($data);
    
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
   
}else if (isset($_POST['editar'])) {
   
        try {
           
            $data = [
                'numero_cuenta' => $_POST["numero_cuenta1"],
                'divisa' => $_POST["divisa1"],
                'cod_banco' => $_POST["banco1"],
                'cod_tipo_cuenta' => $_POST["tipodecuenta1"],
                'saldo' => $_POST["saldo1"],
                'status' => $_POST["status"],
                 'cod_cuenta_bancaria' => $_POST['cod_cuenta_bancaria1'],
                
            ];
            
            $objCuenta->setData($data);
            $objCuenta->check(); 
            

            // Verificar si el número de cuenta ya existe en la base de datos
            if (!$objCuenta->getbuscar($data['numero_cuenta'])) {
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
