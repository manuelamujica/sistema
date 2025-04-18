<?php

require_once 'modelo/banco.php'; 

$objBanco = new Banco();

if (isset($_POST['guardar'])) {
    
    if (isset($_POST['validar_banco'])) {
        $nombre = trim($_POST['validar_banco']);
        $existe = $objBanco->buscarPorNombre($nombre);
        echo json_encode($existe ? true : false);
        exit;
    }
    
    $errores = [];

    $nombre = trim($_POST["nombre"]);
  

    // Validaciones básicas
    if (empty($nombre) || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
        $errores[] = "El nombre del banco solo puede contener letras y espacios.";
    }


    if (!empty($errores)) {
        $registrar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else {
        $objBanco->setNombre($nombre);


        $existe = $objBanco->buscarPorNombre($nombre);

        if (!$existe) {
            $result = $objBanco->getRegistrar();
            if ($result == 1) {
                $registrar = [
                    "title" => "Registrado con éxito",
                    "message" => "El banco ha sido registrado",
                    "icon" => "success"
                ];
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al registrar el banco",
                    "icon" => "error"
                ];
            }
        } else {
            $registrar = [
                "title" => "Error",
                "message" => "El banco ya se encuentra registrado.",
                "icon" => "error"
            ];
        }
    }
} else if (isset($_POST['actualizar'])) {
    $codigo = $_POST["codigo"];
    $nombre = trim($_POST["nombre"]);

    $errores = [];

    if (empty($nombre) || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
        $errores[] = "El nombre solo puede contener letras y espacios.";
    }

    if (!empty($errores)) {
        $editar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else {
        $objBanco->setNombre($nombre);
     

        $result = $objBanco->getactualizar($codigo);

        if ($result == 1) {
            $editar = [
                "title" => "Editado con éxito",
                "message" => "Los datos del banco han sido actualizados",
                "icon" => "success"
            ];
        } else {
            $editar = [
                "title" => "Error",
                "message" => "Hubo un problema al editar los datos del banco",
                "icon" => "error"
            ];
        }
    }
} else if (isset($_POST['borrar'])) {
    $codigo = $_POST["bancoCodigo"];

    $result = $objBanco->eliminar($codigo);

    if ($result == 'success') {
        $eliminar = [
            "title" => "Eliminado con éxito",
            "message" => "El banco ha sido eliminado",
            "icon" => "success"
        ];
    } elseif ($result == 'error_delete') {
        $eliminar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar el banco",
            "icon" => "error"
        ];
    }
}

// Consulta general para la tabla
$registro = $objBanco->consultar();

$_GET['ruta'] = 'banco';
require_once 'plantilla.php';
