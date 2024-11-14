<?php

require_once "modelo/general.php"; //requiero al modelo
$objGeneral= new General();

$datos=$objGeneral->mostrar();
if(!empty($datos)){
    if(!isset($_SESSION["logo"])){
        $_SESSION["logo"]=$datos[0]["logo"];
        $_SESSION["n_empresa"]=$datos[0]["nombre"];
        $_SESSION["rif"]=$datos[0]["rif"];
        $_SESSION["telefono"] = $datos[0]["telefono"];
        $_SESSION["email"] = $datos[0]["email"];
        $_SESSION["direccion"] = $datos[0]["direccion"];
    }
}

if(isset($_POST['buscar'])){
    $result=$objGeneral->buscar();
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

else if (isset($_POST["guardar"])) {
        if (!empty($_POST["rif"]) && !empty($_POST['nombre']) && !empty($_POST['direccion']) && !empty($_POST['descripcion']) && isset($_FILES['logo'])) {
            if(preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-_\/]+$/", $_POST["rif"]) && preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-_\/ ]+$/", $_POST['nombre']) && preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-_\/ ]+$/", $_POST['direccion'])) { //Falta telefono - email - descripcion 13/11
    
                if (!$objGeneral->buscar()) {
                    $imagen = $_FILES['logo'];
                    $tipoImagen = $imagen['type'];
                    $tamanoImagen = $imagen['size'];
                    $imagenTemp = $imagen['tmp_name'];
                    $imagenNombre = $imagen['name'];
    
                    // Verificar si el archivo es una imagen válida
                    $infoImagen = getimagesize($imagenTemp);
                    if ($infoImagen === false) {
                        $r = [
                            "title" => "Advertencia",
                            "message" => "El archivo no es una imagen válida",
                            "icon" => "warning",
                        ];
                        return;
                    }
    
                    list($ancho, $alto) = $infoImagen;
    
                    if ($ancho > 600 || $alto > 600) {
                        $r = [
                            "title" => "Advertencia",
                            "message" => "Las dimensiones de la imagen deben ser como máximo 600px de ancho y 00px de alto",
                            "icon" => "warning",
                        ];
                        return;
                    }
    
                    if ($tamanoImagen > 1024 * 1024 * 5) {
                        $r = [
                            "title" => "Advertencia",
                            "message" => "El tamaño de la imagen es demasiado grande",
                            "icon" => "warning",
                        ];
                        return;
                    }
    
                    $tiposPermitidos = array('image/jpeg', 'image/png', 'image/gif');
                    if (!in_array($tipoImagen, $tiposPermitidos)) {
                        $r = [
                            "title" => "Advertencia",
                            "message" => "El tipo de imagen no es permitido",
                            "icon" => "warning",
                        ];
                        return;
                    }
    
                    #Instanciar los setter
                    $objGeneral->setRif($_POST["rif"]);
                    $objGeneral->setNom($_POST["nombre"]);
                    $objGeneral->setDir($_POST["direccion"]);
                    $objGeneral->settlf($_POST["telefono"]);
                    $objGeneral->setemail($_POST["email"]);
                    $objGeneral->setDescri($_POST["descripcion"]);
                    $objGeneral->subirlogo($_FILES['logo']);
                    $resul = $objGeneral->getregistrar();
    
                    if ($resul == 1) {
                        $registrar = [
                            "title" => "Registrado con éxito",
                            "message" => "La informacion de la empresa ha sido registrada",
                            "icon" => "success"
                        ];
                    } else {
                        $registrar = [
                            "title" => "Error",
                            "message" => "Hubo un problema al registrar la informacion de la empresa",
                            "icon" => "error"
                        ];
                    }
                }
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "No se pudo registrar. Carácteres no permitidos.",
                    "icon" => "error"
                ];
            }
        } else {
            $registrar = [
                "title" => "Error",
                "message" => "Los campos no pueden ir vacíos",
                "icon" => "error"
            ];
        }
} else if (isset($_POST['editar'])) {

    $rif = $_POST['rif'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $descripcion = $_POST['descripcion'];

    if (preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-_\/]+$/", $rif) && preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-_\/]+$/", $nombre) && preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-_\/]+$/", $direccion)) { //Falta telefono - email - descripcion 13/11
        
        // Verifica si se ha subido una nueva imagen
        if (isset($_FILES['logo1']) && $_FILES['logo1']['error'] == 0) {
            $imagen = $_FILES['logo1'];
            $tipoImagen = $imagen['type'];
            $tamanoImagen = $imagen['size'];
            $imagenTemp = $imagen['tmp_name'];

            // Verificar si el archivo es una imagen válida
            $infoImagen = getimagesize($imagenTemp);
            if ($infoImagen === false) {
                $e = [
                    "title" => "Advertencia",
                    "message" => "El archivo no es una imagen válida",
                    "icon" => "warning",
                ];
                return;
            }

            // Obtener las dimensiones de la imagen
            list($ancho, $alto) = $infoImagen;

            // Verificar si las dimensiones de la imagen están dentro del rango permitido
            if ($ancho > 600 || $alto > 600) {
                $e = [
                    "title" => "Advertencia",
                    "message" => "Las dimensiones de la imagen deben ser como máximo 600px de ancho y 600px de alto",
                    "icon" => "warning",
                ];
                return;
            }

            // Verificar si el tamaño de la imagen es demasiado grande
            if ($tamanoImagen > 1024 * 1024 * 5) { // 5MB ESTOS DE AQUI SON BYTES
                $e = [
                    "title" => "Advertencia",
                    "message" => "El tamaño de la imagen es demasiado grande",
                    "icon" => "warning",
                ];
                return;
            }

            // Verificar si el tipo de imagen es permitido
            $tiposPermitidos = array('image/jpeg', 'image/png', 'image/gif');
            if (!in_array($tipoImagen, $tiposPermitidos)) {
                $e = [
                    "title" => "Advertencia",
                    "message" => "El tipo de imagen no es permitido",
                    "icon" => "warning",
                ];
                return;
            }

            $objGeneral->subirlogo($imagen);

        } else {
            // Si no se sube una nueva imagen, mantener la imagen actual
            $datos = $objGeneral->mostrar();
            $objGeneral->setlogo($datos[0]['logo']);
        }

        //Setter
        $objGeneral->setRif($rif);
        $objGeneral->setNom($nombre);
        $objGeneral->setDir($direccion);
        $objGeneral->settlf($telefono);
        $objGeneral->setemail($email);
        $objGeneral->setDescri($descripcion);

        $res = $objGeneral->geteditar();
        if ($res == 1) {
            // Actualizar la sesión con el nuevo logo
            $_SESSION["logo"] = $objGeneral->getlogo();
            $editar = [
                "title" => "Actualizado con éxito",
                "message" => "Información actualizada con éxito",
                "icon" => "success"
            ];
        } else {
            $editar = [
                "title" => "Error",
                "message" => "Error al actualizar",
                "icon" => "error"
            ];
        }
    } else {
        $editar = [
            "title" => "Error",
            "message" => "No se pudo editar. Carácteres no permitidos.",
            "icon" => "error"
        ];
    }
}




$_GET['ruta']='general';
require_once 'plantilla.php';
/*if(!empty($datos)){

    $_SESSION["logo"] = $datos[0]["logo"];

    //agregado por mi
    $_SESSION["nombre-empresa"] = $datos[0]["nombre"];
    $_SESSION["rif"] = $datos[0]["rif"];


    $_SESSION["dir-empresa"]=$datos[0]["direccion"];
    $_SESSION["tlf-empresa"]=$datos[0]["telefono"];
    $_SESSION["email-empresa"]=$datos[0]["email"];

}*/