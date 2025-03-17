<?php
require_once 'modelo/tlf_proveedor.php';
require_once 'modelo/bitacora.php'; 

$objbitacora =new Bitacora();
$objTlfroveedores = new Tproveedor();

if (isset($_POST['buscar'])) {
    $resul = $objTlfroveedores->getbusca($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($resul);
    exit;
} elseif (isset($_POST["okk"])) {
    if (!empty($_POST["telefono"]) && !empty($_POST["cod_prov"])) {
        $telefono = $_POST["telefono"];

        // Validación del teléfono: debe tener entre 10 y 15 caracteres
        if (preg_match("/^[0-9]{10,15}$/", $telefono)) {
            $dato = $objTlfroveedores->getbusca($telefono);
            if (!$dato) {
                $objTlfroveedores->settelefono($telefono);
                $objTlfroveedores->setCod1($_POST['cod_prov']); 
                $resul = $objTlfroveedores->getregistra();

                if ($resul == 1) {
                    $registrar = [
                        "title" => "Registrado con éxito",
                        "message" => "El teléfono ha sido registrado.",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de teléfono', $_POST["telefono"], 'Teléfonos de proveedores');
                } else {
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al registrar el teléfono.",
                        "icon" => "error"
                    ];
                }
            }
        } else {
            $registrar = [
                "title" => "Error",
                "message" => "El teléfono debe tener entre 10 y 15 caracteres numéricos.",
                "icon" => "error"
            ];
        }
    } 
}


$registro = $objTlfroveedores->getconsulta();
$_GET['ruta'] = 'proveedores';
require_once 'plantilla.php';