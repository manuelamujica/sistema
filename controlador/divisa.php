<?php
require_once 'modelo/divisa.php';
require_once 'modelo/bitacora.php';
$obj=new Divisa();
$objbitacora = new Bitacora();

if(isset($_POST['buscar'])){
    $result=$obj->buscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}else if(isset($_POST['registrar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['simbolo']) && !empty($_POST['tasa']) && !empty($_POST['fecha'])){
        if(preg_match('/^[a-zA-ZÀ-ÿ\s]+$/',$_POST['nombre']) && preg_match('/^[a-zA-ZÀ-ÿ\s\$\€]+$/',$_POST['simbolo']) && preg_match('/^\d+(\.\d{1,2})?$/',$_POST['tasa'])){
        
            if(!$obj->buscar($_POST['nombre'])){
                $obj->setnombre($_POST['nombre']);
                $obj->setsimbolo($_POST['simbolo']);
                $obj->set_tasa($_POST['tasa']);
                $obj->setfecha($_POST['fecha']);
                $result=$obj->incluir();
                if($result==1){
                    $registrar = [
                        "title" => "Registrado con éxito",
                        "message" => "La divisa ha sido registrada",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de divisa', $_POST["nombre"], 'Divisas');
                }else {
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al registrar la divisa",
                        "icon" => "error"
                    ];
                }
            }
        } else{
            $registrar = [
                "title" => "Error",
                "message" => "Algunos caracteres ingresados no son permitidos.",
                "icon" => "error"
            ];
        }
    } else{
        $registrar = [
            "title" => "Error",
            "message" => "No se permiten campos vacíos.",
            "icon" => "error"
        ];
    }
}else if(isset($_POST['actualizar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['abreviatura'])){
        if(preg_match('/^[a-zA-ZÀ-ÿ\s]+$/',$_POST['nombre']) && preg_match('/^[a-zA-ZÀ-ÿ\s\$\€]+$/',$_POST['abreviatura']) && preg_match('/^\d+(\.\d{1,2})?$/',$_POST['tasa'])){

            if($_POST['nombre'] !== $_POST['origin'] && $obj->buscar($_POST['nombre'])){ 
                $editar = [
                    "title" => "Error",
                    "message" => "La divisa ya se encuentra registrada.",
                    "icon" => "error"
                    ];
            }else {
                $obj->setnombre($_POST['nombre']);
                $obj->setsimbolo($_POST['abreviatura']);
                $obj->setstatus($_POST['status']);
                $obj->set_tasa($_POST['tasa']);
                $obj->setfecha($_POST['fecha']);
                $result=$obj->editar($_POST['codigo']);

                if($result==1){
                    $editar = [
                        "title" => "Editado con éxito",
                        "message" => "La divisa ha sido actualizada",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar divisa', $_POST["nombre"], 'Divisas');
                }else {
                    $editar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al editar la divisa",
                        "icon" => "error"
                    ];
                }
            }
        } else{
            $editar = [
                "title" => "Error",
                "message" => "Algunos caracteres ingresados no son permitidos.",
                "icon" => "error"
            ];
        }
    } else{
        $editar = [
            "title" => "Error",
            "message" => "No se permiten campos vacíos.",
            "icon" => "error"
        ];
    }
}else if(isset($_POST['borrar'])){
    if(!empty($_POST['divisaCodigo'])){
    $result = $obj->eliminar($_POST["divisaCodigo"]);
        if($result == 1){
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "La divisa ha sido eliminada",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar divisa',"Eliminada la Divisa con Codigo". $_POST["divisaCodigo"], 'Divisas');
        }else if($result == 0){
            $eliminar = [
                "title" => "Error",
                "message" => "La divisa no se puede eliminar ya que tiene tipos de pago asociados",
                "icon" => "error"
            ];
        }else{
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un problema al eliminar la divisa",
                "icon" => "error"
            ];
        }
    }
}else if(isset($_POST['r_tasa'])){
    if(isset($_POST['tasa'])){
        $resul=$obj->tasa($_POST['tasa']);
        if($resul==true){
            $editar = [
                "title" => "Actualizado con éxito",
                "message" => "La tasa de cambio ha sido actualizada",
                "icon" => "success"
            ];
        }else {
            $editar = [
                "title" => "Error",
                "message" => "Hubo un problema al actualizar la tasa",
                "icon" => "error"
            ];
        }
    }
}

$consulta=$obj->consultar();
$_GET['ruta']='divisa';
require_once 'plantilla.php';