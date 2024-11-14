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

    $tipo_medida = $_POST['tipo_medida'];
    $status = $_POST['status'];

        if ($tipo_medida !== $_POST['origin']) {
            // Si la unidad cambió, verificamos si ya existe en la base de datos
            if ($objUnidad->getbuscar($tipo_medida)) {
                $advertencia = [
                    "title" => "Error",
                    "message" => "No se pudo registrar porque el nombre de usuario ya existe.",
                    "icon" => "error"
                ];
            }
        }

        // Validaciones
        if (!empty($tipo_medida)){
            if(preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $tipo_medida)){
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
            } else {
                $editar = [
                    "title" => "Error",
                    "message" => "No se pudo editar. Caracteres no permitidos.",
                    "icon" => "error"
                ];
            }
        } else {
            $editar = [
                "title" => "Error",
                "message" => "No se permiten campos vacios.",
                "icon" => "error"
            ];
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
