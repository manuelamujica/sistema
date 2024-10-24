<?php
require_once 'modelo/tlf_proveedor.php';

$objTlfroveedores = new Tproveedor();

if (isset($_POST['buscar'])) {
    $resul = $objTlfroveedores->getbusca($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($resul);
    exit;
} elseif (isset($_POST["okk"])) {
    if (!empty($_POST["telefono"]) && !empty($_POST["cod_prov"])) {
        $telefono = $_POST["telefono"];

        $dato = $objTlfroveedores->getbusca($telefono);
        if (!$dato) {
            $objTlfroveedores->settelefono($_POST['telefono']);
            $objTlfroveedores->setCod1($_POST['cod_prov']); // Asegúrate de que esto no sea nulo

            $resul = $objTlfroveedores->getregistra();

         
            if ($resul == 1) {
                $registrar = [
                    "title" => "Registrado con éxito",
                    "message" => "El telefono ha sido registrado",
                    "icon" => "success"
                ];
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al registrar el telefono",
                    "icon" => "error"
                ];
            }
        }
    } 
}

$registro = $objTlfroveedores->getconsulta();
$_GET['ruta'] = 'proveedores';
require_once 'plantilla.php';