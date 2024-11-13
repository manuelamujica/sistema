<?php

require_once "modelo/unidad.php"; //requiero al modelo
$objUnidad = new Unidad;

if (isset($_POST['buscar'])) {
    $tipo_medida = $_POST['buscar'];
    $result = $objUnidad->getbuscar($tipo_medida);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;

} else if (isset($_POST["guardar"]) || isset($_POST["guardaru"])) {
    if (preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $_POST["tipo_medida"])) {
        if (!empty($_POST["tipo_medida"])) {
            if (!$objUnidad->getbuscar($_POST['tipo_medida'])) {
                #Instanciar los setter
                $objUnidad->setTipo($_POST["tipo_medida"]);

                $resul = $objUnidad->getcrearUnidad();

                if ($resul == 1) {
                    $registrar = [
                        "title" => "Exito",
                        "message" => "¡Registro exitoso!",
                        "icon" => "success"
                    ];
                } else {
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al intentar registrar la unidad de medida..",
                        "icon" => "error"
                    ];
                }
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "No se pudo registrar. La unidad de medida ya existe.",
                    "icon" => "error"
                ];
            }
        } else {
            $registrar = [
                "title" => "Error",
                "message" => "No se pudo registrar. No se permiten campos vacios.",
                "icon" => "error"
            ];
        }
    } else {
        $registrar = [
            "title" => "Error",
            "message" => "No se pudo registrar. Caracteres no permitidos.",
            "icon" => "error"
        ];
    }
} else if (isset($_POST['editar'])) {

    $cod_unidad = $_POST['cod_unidad'];
    $tipo_medida = $_POST['tipo_medida'];
    $status = $_POST['status'];


    //$unidad_existente = $objUnidad->getbuscar($tipo_medida);  Verificar si el nuevo rol ya existe
    // Validaciones
    if (!($tipo_medida) || preg_match("/^\s*$/", $tipo_medida)) {
        $editar = [
            "title" => "No puede estar el campo vacío",
            "message" => "La unidad de medida no se pudo actualizar",
            "icon" => "error"
        ];
    } else if (preg_match("/\d/", $tipo_medida)) {
        $editar = [
            "title" => "No puede contener números",
            "message" => "La unidad de medida no se pudo actualizar",
            "icon" => "error"
        ];
    } else if (preg_match("/[^a-zA-Z\s]/", $tipo_medida)) {
        $editar = [
            "title" => "No puede contener caracteres especiales",
            "message" => "La unidad de medida no se pudo actualizar",
            "icon" => "error"
        ];
    } else if ($tipo_medida !== $_POST['origin'] && $objUnidad->getbuscar($tipo_medida)) {
        $editar = [
            "title" => "Error",
            "message" => "Unidad ya existente",
            "icon" => "error"
        ];
    } else {
        $objUnidad->setCod($_POST["cod_unidad"]);
        $objUnidad->setTipo($_POST["tipo_medida"]);
        $objUnidad->setStatus($status);
        $res = $objUnidad->geteditar();
        if ($res == 1) {
            $editar = [
                "title" => "Editado con éxito",
                "message" => "La unidad ha sido actualizada",
                "icon" => "success"
            ];
        } else {
            $editar = [
                "title" => "Error",
                "message" => "Hubo un problema al editar la unidad de medida",
                "icon" => "error"
            ];
        }
    }
} else if (isset($_POST['eliminar'])) {
    $cod_unidad = $_POST['eliminar'];
    $resul = $objUnidad->geteliminar($cod_unidad);

    if ($resul == 'success') {
        $eliminar = [
            "title" => "Eliminado con éxito",
            "message" => "La unidad de medida ha sido eliminada",
            "icon" => "success"
        ];
    } else if ($resul == 'error_status') {
        $eliminar = [
            "title" => "Error",
            "message" => "La unidad de medida no se puede eliminar porque tiene status: activo",
            "icon" => "error"
        ];
    } else if ($resul == 'error_associated') {
        $eliminar = [
            "title" => "Error",
            "message" => "La unidad de medida no se puede eliminar porque tiene productos asociados",
            "icon" => "error"
        ];
    } else if ($resul == 'error_delete') {
        $editar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la unidad de medida",
            "icon" => "error"
        ];
    } else {
        $editar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la unidad de medida",
            "icon" => "error"
        ];
    }
}

$datos = $objUnidad->consultarUnidad();
if(isset($_POST["vista"])){
    $_GET['ruta'] = 'productos';
}else{
    $_GET['ruta'] = 'unidad';
}
require_once 'plantilla.php';
