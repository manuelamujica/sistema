<?php

require_once "modelo/roles.php"; //requiero al modelo
$objRol = new Rol();

if (isset($_POST['buscar'])) {
    $rol = $_POST['buscar'];
    $result = $objRol->buscar($rol);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
} else if (isset($_POST["guardar"])) {
    if (preg_match("/^[a-zA-Z0-9\s]+$/", $_POST["rol"])) {
        if (!empty($_POST["rol"])) {

            if (!$objRol->buscar($_POST['rol'])) {
                #Instanciar los setter
                $objRol->setRol($_POST["rol"]);
                $resul = $objRol->getcrearRol($_POST['permisos']);

                if ($resul == 1) {

                    $registrar = [
                        "title" => "Registrado con éxito",
                        "message" => "El rol ha sido registrado",
                        "icon" => "success"
                    ];
                } else {
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al registrar el rol",
                        "icon" => "error"
                    ];
                }
            }
        }
    } else {
        $registrar = [
            "title" => "Error",
            "message" => "¡Los roles no pueden ir vacios o llevar caracteres especiales!",
            "icon" => "error"
        ];
    }
} else if (isset($_POST['editar'])) {
    $codigo = $_POST['cod_tipo_usuario'];
    $rol = $_POST['rol'];
    $status = $_POST['status'];

    // Obtener el rol original
    $rol_original = $objRol->buscarcod($codigo); // Buca por
    $rol_existente = $objRol->buscar($rol); // Verificar si el nuevo rol ya existe

    // Validaciones
    if (!($rol) || preg_match("/^\s*$/", $rol)) {
        $editar = [
            "title" => "No puede estar el campo vacío",
            "message" => "El rol no se pudo actualizar",
            "icon" => "error"
        ];
    } else if (preg_match("/\d/", $rol)) {
        $editar = [
            "title" => "No puede contener números",
            "message" => "El rol no se pudo actualizar",
            "icon" => "error"
        ];
    } else if (preg_match("/[^a-zA-Z\s]/", $rol)) {
        $editar = [
            "title" => "No puede contener caracteres especiales",
            "message" => "El rol no se pudo actualizar",
            "icon" => "error"
        ];
    } else if ($rol !== $_POST['origin'] && $objRol->buscar($rol)) {
        $editar = [
            "title" => "Error",
            "message" => "Rol ya existente", 
            "icon" => "error"
        ];
    } else {
        $objRol->setcodigo($codigo);
        $objRol->setRol($rol);
        $objRol->setStatus($status);
        $res = $objRol->geteditar();
        if ($res == 1) {
            $editar = [
                "title" => "Editado con éxito",
                "message" => "El rol ha sido actualizado",
                "icon" => "success"
            ];
        } else {
            $editar = [
                "title" => "Error",
                "message" => "Hubo un problema al editar el rol", 
                "icon" => "error"
            ];
        }
    }
} else if (isset($_POST['eliminar'])) {
    $codigo = $_POST['eliminar'];
    $objRol->setcodigo($codigo);
    $resul = $objRol->geteliminar($codigo);
    if ($resul == 'success') {
        $eliminar = [
            "title" => "Eliminado con éxito",
            "message" => "El rol ha sido eliminada",
            "icon" => "success"
        ];
    } else if ($resul == 'error_associated') {
        $eliminar = [
            "title" => "Error",
            "message" => "El rol no se puede eliminar porque tiene usuarios asociados",
            "icon" => "error"
        ];
    } else if ($resul == 'error_delete') {
        $editar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar el rol",
            "icon" => "error"
        ];
    } else {
        $editar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar el rol",
            "icon" => "error"
        ];
    }
}

$permiso = $objRol->permisos();

$registro = $objRol->consultar();
$_GET['ruta'] = 'roles';
require_once 'plantilla.php';
