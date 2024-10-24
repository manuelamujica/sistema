<?php
require_once 'modelo/proveedores.php';
require_once 'modelo/tlf_proveedor.php';

$objProveedores = new Proveedor();
$objtProveedor = new TProveedor();
//TODO FUNCIONA

if (isset($_POST['buscar'])) {
    $resul = $objProveedores->getbuscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($resul);
    exit;
} else if (isset($_POST["guardar"])) {
    if (!empty($_POST["rif"]) && !empty($_POST["razon_social"]) && !empty($_POST["email"]) && !empty($_POST["direccion"])) {
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
} elseif (isset($_POST['editar'])) {
    if (!empty($_POST["rif"]) && !empty($_POST["razon_social"]) && !empty($_POST["email"]) && !empty($_POST["direccion"]) && isset($_POST["status"])) {
        if ($_POST['rif'] !== $_POST['origin'] && $objProveedores->getbuscar($_POST['rif'])) {
            
        } else {
            $objProveedores->setRif($_POST["rif"]);
            $objProveedores->setRazon_Social($_POST["razon_social"]);
            $objProveedores->setemail($_POST["email"]);
            $objProveedores->setDireccion($_POST["direccion"]);
            $objProveedores->setStatus($_POST["status"]);
            $objProveedores->setCod($_POST["cod_prov"]);

            $resul = $objProveedores->getedita();
            if ($resul == 1) {
                $editar = [
                    "title" => "Editado con éxito",
                    "message" => "Los datos del  proveedor han sido actualizados.",
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
    } else {
        $editar = [
            "title" => "Error",
            "message" => "Por favor, complete todos los campos.",
            "icon" => "error"
        ];
    }
}else if (isset($_POST['eliminar'])) {
    if (!empty($_POST['provCodigo'])) {
        $resul = $objProveedores->geteliminar($_POST["provCodigo"]);

        if ($resul === 'success') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "El proveedor ha sido eliminado",
                "icon" => "success"
            ];
        } elseif ($resul === 'error_delete') {
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un problema al eliminar el proveedor",
                "icon" => "error"
            ];
        }
    }
}


$registro = $objProveedores->getconsulta();
if(isset($_POST["vista"])){
    $_GET['ruta'] = 'compras';
    //exit();
}else{
    $_GET['ruta'] = 'proveedores';
}

require_once 'plantilla.php';
