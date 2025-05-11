<?php
require_once 'modelo/tpago.php';
require_once 'modelo/divisa.php';
require_once 'modelo/bitacora.php';

$objbitacora = new Bitacora();
$objdivisa=new Divisa();
$obj= new Tpago();

if(isset($_POST['buscar'])){
    $result=$obj->buscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;

}else if(isset($_POST['registrar'])){
    if(!empty($_POST['cod_metodo']) && !empty($_POST['tipo_moneda']) && (!empty($_POST['cod_cuenta_bancaria']) || !empty($_POST['cod_caja']))){

                $result=$obj->registrar($_POST);
                if($result == 1){
                    $registrar = [
                        "title" => "Registrado con éxito",
                        "message" => "El tipo de pago ha sido registrado",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de tipo de pago', $_POST["tipo_pago"], 'Tipo de pago');
                }else{
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al registrar el tipo de pago",
                        "icon" => "error"
                    ];
                }
    } else{
        $registrar = [
            "title" => "Error",
            "message" => "No se permiten campos vacios.",
            "icon" => "error"
        ];
    }

}else if(isset($_POST['editar'])){
    if(!empty($_POST['tpago'])){

        if($_POST['tpago'] !== $_POST['origin'] && $obj->buscar($_POST['tpago'])){
            $editar = [
                "title" => "Error",
                "message" => "El tipo de pago ya se encuentra registrado",
                "icon" => "error"
            ];
        }else{

            if(preg_match('/^[a-zA-ZÀ-ÿ\s]+$/',$_POST['tpago'])){
                
                $obj->setmetodo($_POST['tpago']);
                $obj->setstatus($_POST['status']);
                $result=$obj->editar($_POST['codigo']);
                if($result==1){
                    $editar = [
                        "title" => "Editado con éxito",
                        "message" => "El tipo de pago ha sido actualizado",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar tipo de pago', $_POST["tpago"], 'Tipo de pago');
                }else {
                    $editar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al editar el tipo de pago",
                        "icon" => "error"
                    ];
                }
            } else {
                $editar = [
                    "title" => "Error",
                    "message" => "Algunos caracteres ingresados no son permitidos.",
                    "icon" => "error"
                ];
            }
        }
    } else{
        $editar = [
            "title" => "Error",
            "message" => "No se permiten campos vacios.",
            "icon" => "error"
        ];
    }

}else if(isset($_POST['borrar'])){
    if(!empty($_POST['tpagoCodigo'])){
    $result = $obj->eliminar($_POST["tpagoCodigo"]);
    if ($result == 'success') {
        $eliminar = [
            "title" => "Eliminado con éxito",
            "message" => "El tipo de pago ha sido eliminado",
            "icon" => "success"
        ];
        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar tipo de pago', "Eliminado el tipo de pago con el código ".$_POST["tpagoCodigo"], 'Tipo de pago');
        }elseif ($result == 'error_delete') {
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un problema al eliminar el tipo de pago",
                "icon" => "error"
            ];
        }elseif ($result == 'error') {
            $eliminar = [
                "title" => "Error",
                "message" => "No se puede eliminar ya que tiene pagos asociados",
                "icon" => "error"
            ];
        }
    }
} else if(isset($_POST['guardarm'])){
    $errores = [];
        try {
            $obj->setmetodo($_POST["medio"]);
            $obj->check(); // Lanza excepción si hay errores
        } catch (Exception $e) {
            $errores[] = $e->getMessage();
        }
          // Si hay errores, se muestra el mensaje de error
    if (!empty($errores)) {
        $registrar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else{
        if (!$obj->buscar($_POST['medio'])) {
            $resul = $obj->incluir();
            if ($resul == 1) {
                $registrar = [
                    "title" => "Exito",
                    "message" => "¡Registro exitoso!",
                    "icon" => "success"
                ];
                $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de metodo de pago', $_POST["medio"], 'metodo de pago');
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al intentar registrar el metodo de pago..",
                    "icon" => "error"
                ];
            }
        } else {
            $registrar = [
                "title" => "Error",
                "message" => "No se pudo registrar. El metodo de pago ya existe.",
                "icon" => "error"
            ];
        }
    }
}

$tipos_pago=$obj->mediopago();
$bancos=$obj->cuenta();
$cajas=$obj->caja();
$registro=$obj->consultar();
//$divisas=$objdivisa->consultar();
$_GET['ruta'] = 'tpago';
require_once 'plantilla.php';


