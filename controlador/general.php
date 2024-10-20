<?php

require_once "modelo/general.php"; //requiero al modelo
$objGeneral= new General();

if(isset($_POST['buscar'])){
    $result=$objGeneral->buscar();
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}else if(isset($_POST["guardar"])){
        if(!empty($_POST["rif"]) && !empty($_POST['nombre']) && !empty($_POST['direccion']) && !empty($_POST['descripcion']) &&isset($_FILES['logo'])){
        
        if(!$objGeneral->buscar()){

        $imagen = $_FILES['logo'];
        $tipoImagen = $imagen['type'];
        $tamanoImagen = $imagen['size'];
        $imagenTemp = $imagen['tmp_name'];
        $imagenNombre = $imagen['name'];

        // Verificar si el archivo es una imagen válida
        $infoImagen = getimagesize($imagenTemp);
        if($infoImagen === false){
            echo    "<script>
                        alert('El archivo no es una imagen válida');
                    </script>";
            exit;
        }

        // Verificar si el tamaño de la imagen es demasiado grande
        if($tamanoImagen > 1024 * 1024 * 5){ // 5MB
            echo    "<script>
                        alert('El tamaño de la imagen es demasiado grande');
                    </script>";
            exit;
        }

        // Verificar si el tipo de imagen es permitido
        $tiposPermitidos = array('image/jpeg', 'image/png', 'image/gif');
        if(!in_array($tipoImagen, $tiposPermitidos)){
            echo    "<script>
                        alert('El tipo de imagen no es permitido');
                    </script>";
            exit;
        }

            #Instanciar los setter
            $objGeneral->setRif($_POST["rif"]);
            $objGeneral->setNom($_POST["nombre"]);
            $objGeneral->setDir($_POST["direccion"]);
            $objGeneral->settlf($_POST["telefono"]);
            $objGeneral->setemail($_POST["email"]);
            $objGeneral->setDescri($_POST["descripcion"]);
            $objGeneral->subirlogo($_FILES['logo']);
            $resul=$objGeneral->getregistrar();

            if($resul == 1){
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
        }else{
            $registrar = [
                "title" => "Error",
                "message" => "ya existe una informacion registrada",
                "icon" => "error"
            ];
        }
        
    }

}

$datos=$objGeneral->mostrar();
if(!empty($datos)){
    if(!isset($_SESSION["logo"])){
        $_SESSION["logo"]=$datos[0]["logo"];
    }
}
$_GET['ruta']='general';
require_once 'plantilla.php';
