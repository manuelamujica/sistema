<?php
require_once 'modelo/proveedores.php';
require_once 'modelo/tlf_proveedor.php';
require_once 'modelo/representantes.php';
require_once 'modelo/bitacora.php';

$objbitacora = new Bitacora();
$objRepresentante = new Representantes();
$objProveedores = new Proveedor();
$objtProveedor = new TProveedor();
//TODO FUNCIONA

if (isset($_POST['buscar'])) {
    $resul = $objProveedores->getbuscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($resul);
    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Buscar proveedor', $_POST['buscar'], 'Proveedores');
    exit;
}else if (isset($_POST["guardar"])) {
    $errores = [];

    try {
        $objProveedores->setRif($_POST['rif']);
        $objProveedores->setRazon_Social($_POST['razon_social']);
        if(isset($_POST["email"])) {
            $objProveedores->setemail($_POST['email']); // Se permite vacío
        }
        if(isset($_POST["direccion"])) {
            $objProveedores->setDireccion($_POST['direccion']); // Se permite vacío
        }
        $objProveedores->check();

    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }
    
    if (!empty($errores)) {
        $registrar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else {
        $rif = $_POST["rif"];
        $dato = $objProveedores->getbuscar($rif);
        if (!$dato) {
            $resul = $objProveedores->getregistra();

            if ($resul == 1) {
                $registrar = [
                    "title" => "Registrado con éxito",
                    "message" => "El proveedor ha sido registrado",
                    "icon" => "success"
                ];
                $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de proveedor', $_POST["razon_social"], 'Proveedores');
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al registrar el proveedor",
                    "icon" => "error"
                ];
            }
        } else {
            $registrar = [
                "title" => "Error",
                "message" => "Ya existe un proveedor con el mismo documento",
                "icon" => "error"
            ];
        }
    }
}else if (isset($_POST['editar'])) {
    // Verifica que los campos obligatorios estén llenos
    $errores = [];
    try {
        $objProveedores->setRif($_POST['rif']);
        $objProveedores->setRazon_Social($_POST['razon_social']);
        $objProveedores->setStatus($_POST["status"]);
        if(isset($_POST["email"])) {
            $objProveedores->setemail($_POST['email']); // Se permite vacío
        }
        if(isset($_POST["direccion"])) {
            $objProveedores->setDireccion($_POST['direccion']); // Se permite vacío
        }
        $objProveedores->check();

    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }
    
    if (!empty($errores)) {
        $registrar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else {
        // Verifica si el RIF es diferente del original y si ya existe en la base de datos
        if ($_POST['rif'] !== $_POST['origin'] && $objProveedores->getbuscar($_POST['rif'])) {
            $registrar = [
                "title" => "Error",
                "message" => "Ya existe un proveedor con el mismo documento",
                "icon" => "error"
            ];
        } else {
            // Intentar editar los datos
            $resul = $objProveedores->getedita();
            if ($resul == 1) {
                $editar = [
                    "title" => "Editado con éxito",
                    "message" => "Los datos del proveedor han sido actualizados.",
                    "icon" => "success"
                ];
                $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar proveedor', $_POST["razon_social"], 'Proveedores');
            } else {
                $editar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al editar los datos del proveedor.",
                    "icon" => "error"
                ];
            }
        }
    }
} elseif (isset($_POST['eliminar'])) {
    $objProveedores->setCod($_POST['provCodigo']);
    $resul = $objProveedores->get_eliminar();
    if ($resul === 'error_cod') {
        $eliminar = [
            "title" => "Error",
            "message" => "No se puede eliminar el proveedor porque no se ha especificado el código.",
            "icon" => "error"
        ];

  
    }  
        // Mensajes según el resultado de la eliminación
        if ($resul === 'success_eliminado') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "El proveedor ha sido eliminado.",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar proveedor', "Eliminado el proveedor con el código ".$_POST["provCodigo"], 'Proveedores');
        } elseif ($resul === 'error_compra_asociada') {
            $eliminar = [
                "title" => "Error",
                "message" => "No se puede eliminar, tiene una compra asociada.",
                "icon" => "error"
            ];
        }
    }

$registro = $objProveedores->getconsulta();
if (isset($_POST["vista"])) {
    $_GET['ruta'] = 'compras';
} else {
    $_GET['ruta'] = 'proveedores';
}

require_once 'plantilla.php';
