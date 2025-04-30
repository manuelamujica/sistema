<?php

require_once "modelo/marcas.php"; 

$objMarca= new Marca();

if(isset($_POST['buscar'])){
    $nombre = $_POST['buscar'];
    $result = $objMarca->getbuscar($nombre);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;

}else if (isset($_POST['guardar']) || isset($_POST['registrarm'])){
    $errores = [];

    try {
        $objMarca->setNombre($_POST["nombre"]);
        $objMarca->check();
    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }

    if (!empty($errores)) {
        $registrar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else {
        if (!$objMarca->getbuscar($_POST["nombre"])){ #(Si el metodo buscar no devuelve nada entonces la marca no existe y se puede registrar)
            $result=$objMarca->getregistrar();
            
            if($result == 1){
                $registrar = [
                    "title" => "Registrado con éxito",
                    "message" => "La marca ha sido registrada",
                    "icon" => "success"
                ];
                
            }else{
                $registrar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al registrar la marca",
                    "icon" => "error"
                ];
            }
        } else {
            $registrar = [
                "title" => "Error",
                "message" => "No se puede registrar la marca con un nombre existente.",
                "icon" => "error"
            ];
        }
    }


        

}else if (isset($_POST['actualizar'])) {
    $errores = [];

    try {
        $objMarca->setNombre($_POST["nombre"]);
        $objMarca->setStatus($_POST["status"]);
        $objMarca->check();
    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }

    if (!empty($errores)) {
        $registrar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else {
        // Si el nombre no ha cambiado, se puede editar el status
        if ($objMarca->getNombre() === $_POST['origin']) {

            $result = $objMarca->geteditar($_POST['codigo']);

            if ($result == 1) {
                $editar = [
                    "title" => "Editado con éxito",
                    "message" => "La marca ha sido actualizada",
                    "icon" => "success"
                ];
            } else {
                $editar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al editar la marca",
                    "icon" => "error"
                ];
            }
        } else {
            // Si el nombre ha cambiado, verificar si ya existe en la base de datos
            if (!$objMarca->getbuscar($objMarca->getNombre())) {

                $result = $objMarca->geteditar($_POST['codigo']);

                if ($result == 1) {
                    $editar = [
                        "title" => "Editado con éxito",
                        "message" => "La marca ha sido actualizada",
                        "icon" => "success"
                    ];
                } else {
                    $editar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al editar la marca",
                        "icon" => "error"
                    ];
                }
            } else {
                $editar = [
                    "title" => "Advertencia",
                    "message" => "La marca ya está registrada. No se puede cambiar el nombre a uno existente.",
                    "icon" => "warning"
                ];
            }
        }
    }
    
            

} else if(isset($_POST['borrar'])){
    if(!empty($_POST['marcacodigo']) && $_POST['statusDelete'] !== '1'){ //Eliminar solo si el status es inactivo
    $result = $objMarca->geteliminar($_POST["marcacodigo"]);
    
    if ($result == 'success') {
        $eliminar = [
            "title" => "Eliminado con éxito",
            "message" => "La marca ha sido eliminada",
            "icon" => "success"
        ];
    } elseif ($result == 'error_associated') {
        $eliminar = [
            "title" => "Error",
            "message" => "La marca no se puede eliminar porque tiene productos asociados",
            "icon" => "error"
        ];
    } elseif ($result == 'error_delete') {
        $eliminar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la marca",
            "icon" => "error"
        ];
    } else {
        $eliminar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la marca",
            "icon" => "error"
        ];
    }
} else {$eliminar = [
    "title" => "Error",
    "message" => "No se puede eliminar una marca activa",
    "icon" => "error"
];
}

}

$registro = $objMarca->getmostrar();

if(isset($_POST["vista"])){ //Quiere decir que viene de productos
    $_GET['ruta'] = 'productos';
}else{
    $_GET['ruta'] = 'marcas';
}
require_once 'plantilla.php';
