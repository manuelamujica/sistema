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
    exit;
    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Buscar proveedor', $_POST['buscar'], 'Proveedores');
}else if (isset($_POST["guardar"])) {
    $errores = [];

    // Validación del campo RIF
    if (empty($_POST["rif"]) || !preg_match("/^[a-zA-Z0-9\s\-\.\/]+$/", $_POST["rif"]) || strlen($_POST["rif"]) < 4 || strlen($_POST["rif"]) > 12) {
        $errores[] = "El RIF debe contener maximo  12 caracteres.";
    }

    // Validación del campo razón social
    if (empty($_POST["razon_social"]) || !preg_match("/^[a-zA-Z0-9\s\-\.\/]+$/", $_POST["razon_social"]) || strlen($_POST["razon_social"]) < 6 || strlen($_POST["razon_social"]) > 30) {
        $errores[] = "La razón social debe contener maximo  30 caracteres, incluyendo letras y números.";
    }

    // Validación del campo email (opcional)
    if (!empty($_POST["email"]) && (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) || strlen($_POST["email"]) < 10 || strlen($_POST["email"]) > 40)) {
        $errores[] = "El email debe ser válido y tener maximo  40 caracteres.";
    }

    // Validación del campo dirección (opcional)
    if (!empty($_POST["direccion"]) && (strlen($_POST["direccion"]) < 6 || strlen($_POST["direccion"]) > 100)) {
        $errores[] = "La dirección debe contener maximo  y 100 caracteres.";
    }

    $direccion = $_POST["direccion"]; 

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
            $objProveedores->setRif($_POST['rif']);
            $objProveedores->setRazon_Social($_POST['razon_social']);
            $objProveedores->setemail($_POST['email']); // Se permite vacío
            $objProveedores->setDireccion($_POST['direccion']); // Se permite vacío

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
        } 
    }
}else if (isset($_POST['editar'])) {
    // Verifica que los campos obligatorios estén llenos
    if (!empty($_POST["rif"]) && !empty($_POST["razon_social"]) && isset($_POST["status"])) {

        // Verifica si el RIF es diferente del original y si ya existe en la base de datos
        if ($_POST['rif'] !== $_POST['origin'] && $objProveedores->getbuscar($_POST['rif'])) {

        } else {
            // Establecer los valores para la edición
            $objProveedores->setRif($_POST["rif"]);
            $objProveedores->setRazon_Social($_POST["razon_social"]);
            
            // Solo establecer el email y la dirección si no están vacíos
            if (!empty($_POST["email"])) {
                $objProveedores->setEmail($_POST["email"]);
            } else {
                $objProveedores->setEmail(null); // O puedes omitir esta línea si no deseas actualizar el campo
            }

            if (!empty($_POST["direccion"])) {
                $objProveedores->setDireccion($_POST["direccion"]);
            } else {
                $objProveedores->setDireccion(null); // O puedes omitir esta línea si no deseas actualizar el campo
            }

            $objProveedores->setStatus($_POST["status"]);
            $objProveedores->setCod($_POST["cod_prov"]);

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
