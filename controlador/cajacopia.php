<?php

require_once "modelo/cajacopia.php"; 
require_once "modelo/bitacora.php";
require_once "modelo/divisa.php";
$objCaja = new Caja;
$objDivisa = new Divisa;
$objbitacora = new Bitacora();

$divisas=$objDivisa->consultarDivisas();

if (isset($_POST['buscar'])) {
    $nombre = $_POST['buscar'];
    $result = $objCaja->getbuscar($nombre);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
   

} else if (isset($_POST["guardar"]) || isset($_POST["guardaru"])) {
  
    if (!empty($_POST["nombre"])) {
       
        $errores = [];
        try {
            
            $data = [
                'nombre' => $_POST["nombre"],
                'cod_divisa' => $_POST["divisa"],
                'saldo' => $_POST["saldo"],
                'status' => 1,
               
                
            ];
            
            $objCaja->setData($data);
    
            // Paso 2: Validar datos
            $objCaja->check();
           
       
         
            if (!$objCaja->getbuscar($_POST['nombre'])) {
              
                
                $resul = $objCaja->getcrearCaja();

                if ($resul == 1) {
                    $registrar = [
                        "title" => "Exito",
                        "message" => "¡Registro exitoso!",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de caja', $_POST["nombre"], 'Caja');
                } else {
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al intentar registrar la caja..",
                        "icon" => "error"
                    ];
                }
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "No se pudo registrar. La caja ya existe.",
                    "icon" => "error"
                ];

            }
            
        }
        catch (Exception $e) {
            $errores[] = $e->getMessage();
            $registrar = [
                "title" => "Error",
                "message" => implode(" ", $errores),
                "icon" => "error"
            ];
        }
    } 
} else if (isset($_POST['editar'])) {
    $errores = [];

    try {
     
        $cod_caja = $_POST['cod_caja']; 
        
        // Preparar datos para edición
        $data = [
            'nombre' => $_POST["nombre1"],
            'cod_divisa' => $_POST["divisa1"],
            'saldo' => $_POST["saldo1"],
            'status' => $_POST["status"],
            'cod_caja' => $cod_caja, 
        ];

        $objCaja->setData($data);
        $objCaja->check();

        // Verificar si el nombre ya existe (excluyendo la caja actual)
        $cajaExistente = $objCaja->getbuscar($_POST["nombre1"]);
        if ($cajaExistente && $cajaExistente['cod_caja'] != $cod_caja) {
            throw new Exception("El nombre de caja ya está en uso por otra caja.");
        }

        $resul = $objCaja->geteditar($cod_caja); 

        if ($resul == 1) {
            $nombre = $_POST["nombre1"]; // Definir $nombre para la bitácora
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar Caja', $nombre, 'Caja');
            $editar = [
                "title" => "Editado con éxito",
                "message" => "La caja ha sido actualizada",
                "icon" => "success"
            ];
        } else {
            $editar = [
                "title" => "Error",
                "message" => "Hubo un problema al editar la caja.",
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

$datos = $objCaja->consultarCaja();
$_GET['ruta'] = 'cajacopia';
require_once 'plantilla.php';
