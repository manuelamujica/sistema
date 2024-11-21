<?php
require_once 'modelo/tpago.php';
require_once 'modelo/divisa.php';
$objdivisa=new Divisa();
$obj= new Tpago();

if(isset($_POST['buscar'])){
    $result=$obj->buscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}else if(isset($_POST['registrar'])){
    if(!empty($_POST['tipo_pago']) && !empty($_POST['divisa'])){
        if(!$obj->buscar($_POST['tipo_pago'])){
        $obj->setmetodo($_POST['tipo_pago']);

        $result=$obj->incluir($_POST['divisa']);
        if($result == 1){
            $registrar = [
                "title" => "Registrado con éxito",
                "message" => "El tipo de pago ha sido registrado",
                "icon" => "success"
            ];
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
                "message" => "El tipo de pago ya se encuentra registrado",
                "icon" => "error"
            ];
        }
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
            
                $obj->setmetodo($_POST['tpago']);
                $obj->setstatus($_POST['status']);
                $result=$obj->editar($_POST['codigo']);
                if($result==1){
                    $editar = [
                        "title" => "Editado con éxito",
                        "message" => "El tipo de pago ha sido actualizado",
                        "icon" => "success"
                    ];
                }else {
                    $editar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al editar el tipo de pago",
                        "icon" => "error"
                    ];
                }
            }
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
}

$registro=$obj->consultar();
$divisas=$objdivisa->consultar();
$_GET['ruta'] = 'tpago';
require_once 'plantilla.php';


