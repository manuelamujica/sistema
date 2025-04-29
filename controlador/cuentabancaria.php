<?php

require_once 'modelo/cuentabancaria.php';
require_once 'modelo/usuario.php';
require_once 'modelo/banco.php';
require_once 'modelo/tipocuenta.php';
require_once 'modelo/divisa.php';
require_once 'modelo/bitacora.php';

# Objetos
$objCuentaBancaria = new CuentaBancaria();
$objBitacora = new Bitacora();

# Consultar datos para selects
$bancos = $objCuentaBancaria->consultarBancos();
$tiposCuenta = $objCuentaBancaria->consultarTiposCuenta();
$divisas = $objCuentaBancaria->consultarDivisas();

// REGISTRAR CUENTA BANCARIA
if (isset($_POST['guardar'])) {
    if (!empty($_POST["banco"]) && !empty($_POST["tipo_cuenta"]) && !empty($_POST["numero_cuenta"]) && 
        !empty($_POST["saldo"]) && !empty($_POST["divisa"]) && !empty($_POST["status"])) {
        
        // Validaciones
        $errors = [];
        $validationPassed = true;

        if ($validationPassed && count($errors) === 0) {
            // Asignar valores al objeto
            $errores = [];

            // Usamos la función 'cargarDatosDesdeFormulario' para validar y cargar los datos
            try {
                $modelo->setData([
                    'numero_cuenta' => $_POST['numero_cuenta'],
                    'saldo' => $_POST['saldo'],
                    'cod_banco' => $_POST['banco'],
                    'cod_tipo_cuenta' => $_POST['tipo_cuenta'],
                    'cod_divisa' => $_POST['divisa'],
                    'id_usuario' => $_SESSION['cod_usuario'],
                    'status' => $_POST['status']
                ]);
                       
            
                $modelo->check(); // Lanza excepción si hay errores
               $result=$modelo->getRegistrar();
                // Aquí puedes guardar o hacer lo que necesites con el cliente
            } catch (Exception $e) {
                $errores[] = $e->getMessage();
            }

            if ($result == 1) {
                $registrarp = [
                    "title" => "Registrado con éxito",
                    "message" => "La cuenta bancaria ha sido registrada",
                    "icon" => "success"
                ];
                $objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de cuenta bancaria', $_POST["numero_cuenta"], 'Cuentas Bancarias');
            } else {
                $registrarp = [
                    "title" => "Error",
                    "message" => "Hubo un error al registrar la cuenta bancaria",
                    "icon" => "error"
                ];
            }
        } else if (!$validationPassed) {
            $registrarp = $error;
        }
    } else {
        $registrarp = [
            "title" => "Error",
            "message" => "Completa todos los campos obligatorios",
            "icon" => "error"
        ];
    }

// EDITAR CUENTA BANCARIA
} elseif (isset($_POST['editar'])) {
    if (!empty($_POST["cod_cuenta"]) && !empty($_POST["banco"]) && !empty($_POST["tipo_cuenta"]) && 
        !empty($_POST["numero_cuenta"]) && !empty($_POST["saldo"]) && !empty($_POST["divisa"]) && 
        !empty($_POST["status"])) {
        
        // Validaciones
        $errors = [];
    

      
        if ($validationPassed && count($errors) === 0) {
            // Asignar valores al objeto
            $errores = [];

            // Usamos la función 'cargarDatosDesdeFormulario' para validar y cargar los datos
            try {
                $modelo->setData([
                    'numero_cuenta' => $_POST['numero_cuenta'],
                    'saldo' => $_POST['saldo'],
                    'cod_banco' => $_POST['banco'],
                    'cod_tipo_cuenta' => $_POST['tipo_cuenta'],
                    'cod_divisa' => $_POST['divisa'],
                    'id_usuario' => $_SESSION['cod_usuario'],
                    'status' => $_POST['status']
                ]);
                       
            
                $modelo->check(); // Lanza excepción si hay errores
               $result=$modelo->getEditar();
                // Aquí puedes guardar o hacer lo que necesites con el cliente
            } catch (Exception $e) {
                $errores[] = $e->getMessage();
            }
            if ($result == 1) {
                $editar = [
                    "title" => "Actualizado con éxito",
                    "message" => "La cuenta bancaria ha sido actualizada",
                    "icon" => "success"
                ];
                $objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Edición de cuenta bancaria', $_POST["numero_cuenta"], 'Cuentas Bancarias');
            } else {
                $editar = [
                    "title" => "Error",
                    "message" => "Hubo un error al actualizar la cuenta bancaria",
                    "icon" => "error"
                ];
            }
        } else if (!$validationPassed) {
            $editar = $error;
        }
    } else {
        $editar = [
            "title" => "Error",
            "message" => "Completa todos los campos obligatorios",
            "icon" => "error"
        ];
    }

// ELIMINAR CUENTA BANCARIA
} elseif (isset($_POST['borrar'])) {
    if (!empty($_POST['cod_cuenta'])) {
        $objCuentaBancaria->setCuentaBancaria($_POST["cod_cuenta_bancaria"]);
        

        $errores = [];

        try {
            $modelo->setData();
            $objCuentaBancaria->check(); 
            
        } catch (Exception $e) {
            $errores[] = $e->getMessage();
        }

        if (!empty($errores)) {
            $eliminar = [
                "title" => "Error",
                "message" => implode(" ", $errores),
                "icon" => "error"
            ];
        }
        else{

            
            // Verificar si la cuenta tiene movimientos antes de eliminar
            if ($objCuentaBancaria->tieneMovimientos()) {
                $eliminar = [
                    "title" => "Error",
                    "message" => "No se puede eliminar la cuenta porque tiene movimientos asociados",
                    "icon" => "error"
                ];
            } else {
                $result = $objCuentaBancaria->getEliminar();
                
                if ($result == 1) {
                    $eliminar = [
                        "title" => "Eliminado con éxito",
                        "message" => "La cuenta bancaria ha sido eliminada",
                        "icon" => "success"
                    ];
                    $objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminación de cuenta bancaria', $_POST["cod_cuenta_bancaria"], 'Cuentas Bancarias');
                } else {
                    $eliminar = [
                        "title" => "Error",
                        "message" => "Hubo un error al eliminar la cuenta bancaria",
                        "icon" => "error"
                    ];
                }
            }
        } 
    } else {
        $eliminar = [
            "title" => "Error",
            "message" => "No se proporcionó el código de la cuenta a eliminar",
            "icon" => "error"
        ];
    }
}

// Obtener todas las cuentas bancarias para mostrar en la tabla
$registro = $objCuentaBancaria->ConsultarTodas();


// Establecer la ruta para la plantilla
$_GET['ruta'] = 'cuentabancaria';

require_once 'plantilla.php';