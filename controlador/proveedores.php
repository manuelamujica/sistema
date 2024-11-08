<?php
require_once 'modelo/proveedores.php';
require_once 'modelo/tlf_proveedor.php';
require_once 'modelo/representantes.php';


$objRepresentante = new Representantes();
$objProveedores = new Proveedor();
$objtProveedor = new TProveedor();
//TODO FUNCIONA

if (isset($_POST['buscar'])) {
    $resul = $objProveedores->getbuscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($resul);
    exit;
} else if (isset($_POST["guardar"])) {
    $errores = [];

    // Validación del campo RIF
    if (empty($_POST["rif"]) || !preg_match("/^[a-zA-Z0-9\s\-\.\/]+$/", $_POST["rif"]) || strlen($_POST["rif"]) < 6 || strlen($_POST["rif"]) > 12) {
        $errores[] = "El RIF debe contener entre 6 o  12 caracteres, .";
    }

    // Validación del campo razón social
    if (empty($_POST["razon_social"]) || !preg_match("/^[a-zA-Z0-9\s\-\.\/]+$/", $_POST["razon_social"]) || strlen($_POST["razon_social"]) < 6 || strlen($_POST["razon_social"]) > 30) {
        $errores[] = "La razón social debe contener entre 6 o 30 caracteres, incluyendo letras y numeros, .";
    }

    // Validación del campo email
    if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) || strlen($_POST["email"]) < 15 || strlen($_POST["email"]) > 40) {
        $errores[] = "El email debe ser válido y tener entre 15 o 40 caracteres.";
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
            $objProveedores->setemail($_POST['email']);
            $objProveedores->setDireccion($_POST['direccion']);

            $resul = $objProveedores->getregistra();

            if ($resul == 1) {
                $registrar = [
                    "title" => "Registrado con éxito",
                    "message" => "El proveedor ha sido registrado",
                    "icon" => "success"
                ];
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al registrar el proveedor",
                    "icon" => "error"
                ];
            }
        } 
    }
} else if (isset($_POST['editar'])) {
    if (!empty($_POST["rif"]) && !empty($_POST["razon_social"]) && !empty($_POST["email"]) && !empty($_POST["direccion"]) && isset($_POST["status"])) {

        if ($_POST['rif'] !== $_POST['origin'] && $objProveedores->getbuscar($_POST['rif'])) {
        } else {
            $objProveedores->setRif($_POST["rif"]);
            $objProveedores->setRazon_Social($_POST["razon_social"]);
            $objProveedores->setEmail($_POST["email"]);
            $objProveedores->setDireccion($_POST["direccion"]);
            $objProveedores->setStatus($_POST["status"]);
            $objProveedores->setCod($_POST["cod_prov"]);

            $resul = $objProveedores->getedita();
            if ($resul == 1) {

                    $editar = [
                        "title" => "Editado con éxito",
                        "message" => "Los datos del proveedor han sido actualizados.",
                        "icon" => "success"
                    ];
                } else {
                    $editar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al editar los datos del proveedor.",
                        "icon" => "error"
                    ];
                }
            } 
        }
} else if (isset($_POST['eliminar'])) {
    if (!empty($_POST['provCodigo'])) {
        $resul = $objProveedores->geteliminar($_POST["provCodigo"]);

        if ($resul === 'success') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "El proveedor ha sido eliminado .",
                "icon" => "success"
            ];
        } elseif ($resul === 'error_compra_asociada') {
            $eliminar = [
                "title" => "Error",
                "message" => "No se puede eliminar, tiene una compra asociada.",
                "icon" => "error"
            ];
        }
    }
}

$registro = $objProveedores->getconsulta();
if (isset($_POST["vista"])) {
    $_GET['ruta'] = 'compras';
} else {
    $_GET['ruta'] = 'proveedores';
}

require_once 'plantilla.php';
