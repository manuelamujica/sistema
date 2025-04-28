<?php

require_once "modelo/categorias.php"; 
require_once "modelo/bitacora.php";

$objbitacora = new Bitacora();
$objCategoria= new Categoria();

if(isset($_POST['buscar'])){
    $nombre = $_POST['buscar']; #Se asigna el valor de buscar a la variable nombre
    $result = $objCategoria->getbuscar($nombre); #Se instancia al metodo buscar y le enviamos por parametro el nombre
    header('Content-Type: application/json'); #establece el encabezado de la respuesta http, indica que el JSON
    echo json_encode($result); #Se envia $result como JSON al cliente 
    exit;

}else if (isset($_POST['guardar']) || isset($_POST['registrarc'])){ #Si viene de productos o de categoria

    $errores = [];

        if (!$objCategoria->getbuscar($_POST["nombre"])){ #(Si el metodo buscar no devuelve nada entonces la categoria no existe y se puede registrar)
    
            try {
                $objCategoria->setNombre($_POST["nombre"]);
                $objCategoria->check(); 
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
                } else {
                // Si no hay errores, proceder con el registro
                    $result=$objCategoria->getregistrar();
                        if($result == 1){
                            $registrar = [
                                "title" => "Registrado con éxito",
                                "message" => "La categoría ha sido registrada",
                                "icon" => "success"
                            ];
                            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de categoría', $_POST["nombre"], 'Categorias');
                        }else{
                            $registrar = [
                                "title" => "Error",
                                "message" => "Hubo un problema al registrar la categoría",
                                "icon" => "error"
                            ];
                        }
                    }
            }else {
                $registrar = [
                "title" => "Error",
                "message" => "No se puede registrar la categoría con un nombre existente",
                "icon" => "error"
                ];
            }

}else if (isset($_POST['actualizar'])) {

    try {
        $objCategoria->setNombre($_POST["nombre"]); // Usamos el set que valida
        $objCategoria->check();             // Verificamos si hay errores
        
        // Si pasa el check, seguimos normalmente
        
        if ($_POST["nombre"] === $_POST['origin']) {
            $objCategoria->setStatus($_POST['status']);
            $result = $objCategoria->geteditar($_POST['codigo']);
    
            if ($result == 1) {
                $editar = [
                    "title" => "Editado con éxito",
                    "message" => "La categoría ha sido actualizada",
                    "icon" => "success"
                ];
                $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar categoría', $_POST["nombre"], 'Categorias');
            } else {
                $editar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al editar la categoría",
                    "icon" => "error"
                ];
            }

        } else {
            // El nombre ha cambiado, verificar que no exista
            if (!$objCategoria->getbuscar($_POST["nombre"])) {
                $objCategoria->setStatus($_POST['status']);
                $result = $objCategoria->geteditar($_POST['codigo']);
    
                if ($result == 1) {
                    $editar = [
                        "title" => "Editado con éxito",
                        "message" => "La categoría ha sido actualizada",
                        "icon" => "success"
                    ];
                } else {
                    $editar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al editar la categoría",
                        "icon" => "error"
                    ];
                }
            } else {
                $editar = [
                    "title" => "Advertencia",
                    "message" => "La categoría ya está registrada. No se puede cambiar el nombre a uno existente.",
                    "icon" => "warning"
                ];
            }
        }

    } catch (Exception $e) {
        $errores[] = $e->getMessage();
        // Si hay errores, se muestra el mensaje de error
        if (!empty($errores)) {
            $editar = [
                "title" => "Error",
                "message" => implode(" ", $errores),
                "icon" => "error"
            ];
        }
    }

} else if(isset($_POST['borrar'])){

    try {
        $objCategoria->setStatus($_POST['statusDelete']); 
        $objCategoria->check(); // validamos que sea inactivo

        // Si pasa el check, eliminamos
        $result = $objCategoria->geteliminar($_POST["catcodigo"]);

        if ($result == 'success') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "La categoría ha sido eliminada",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar categoría', "Eliminada la categoría con el código ".$_POST["catcodigo"], 'Categorias');
        } elseif ($result == 'error_associated') {
            $eliminar = [
                "title" => "Error",
                "message" => "La categoría no se puede eliminar porque tiene productos asociados",
                "icon" => "error"
            ];
        } elseif ($result == 'error_delete') {
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un problema al eliminar la categoría",
                "icon" => "error"
            ];
        } else {
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un problema desconocido al eliminar la categoría",
                "icon" => "error"
            ];
        }

    } catch (Exception $e) {
        $errores[] = $e->getMessage();
        // Si hay errores, se muestra el mensaje de error
        if (!empty($errores)) {
            $editar = [
                "title" => "Error",
                "message" => implode(" ", $errores),
                "icon" => "error"
            ];
        }
    }
}

$registro = $objCategoria->getmostrar();

if(isset($_POST["vista"])){ //Quiere decir que viene de productos
    $_GET['ruta'] = 'productos';
}else{
    $_GET['ruta'] = 'categorias';
}
require_once 'plantilla.php';
