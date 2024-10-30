<?php

require_once "modelo/usuarios.php"; 
require_once "modelo/roles.php"; 

$objuser= new Usuario();
$objroles = new Rol();

$roles = $objroles->consultar(); // Obtener los roles para pasarlos a la vista

if(isset($_POST['buscar'])){
    $user = $_POST['buscar']; 
    $result = $objuser->buscar($user);
    header('Content-Type: application/json'); 
    echo json_encode($result); 
    exit;

}else if (isset($_POST['guardar'])){

    if(!empty($_POST["nombre"]) && (!empty($_POST["user"])) && (!empty($_POST["pass"]))){ #ping

        if (!$objuser->buscar($_POST["user"])){ #Que no sea el mismo user

            $objuser->setNombre($_POST["nombre"]);
            $objuser->setUser($_POST["user"]);

        #Validar la longitud de la contraseña
            $longitud = strlen($_POST["pass"]);

            if($longitud > 8){
                $password = password_hash($_POST["pass"], PASSWORD_DEFAULT); // guardar la contraseña cifrada con HASH
                $objuser->setPassword($password);
            }else{
                echo "<script>
                alert('La contraseña es demasiado corta. Debe tener más de 8 caracteres.');
                location = 'usuarios' </script>";
            }
            
            $rol = $_POST["rol"];
            $result = $objuser->getregistrar($rol);
            
            if($result == 1){
                $registrar = [
                    "title" => "Registrado con éxito",
                    "message" => "El usuario ha sido registrado",
                    "icon" => "success"
                ];
            }else{
                $registrar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al registrar el usuario",
                    "icon" => "error"
                ];
            }
        }
    }
}else if (isset($_POST['actualizar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['user'])){

            // Verificamos si el usuario ha sido cambiado
        if ($_POST['user'] !== $_POST['origin']) {
            // Si el user cambió, verificamos si ya existe en la base de datos
            if ($objuser->buscar($_POST['user'])) {
                echo "<script>
                    alert('El usuario ya está registrado.');
                    window.location = 'usuarios';
                </script>";
                exit;
            }
        }
            // Si el usuario NO ha sido cambiado entonces se actualiza
            $objuser->setNombre($_POST['nombre']);
            $objuser->setUser($_POST['user']);

             // Verificamos si hay contraseña nueva
            if(!empty($_POST['pass'])){
                $password = password_hash($_POST['pass'], PASSWORD_DEFAULT); // Cifrar con HASH
                $objuser->setPassword($password);
            }

            $objuser->setStatus($_POST['status']);
            $result=$objuser->editar($_POST['codigo'], $_POST['roles']);
            if($result==1){
                $editar = [
                  "title" => "Editado con éxito",
                  "message" => "El usuario ha sido actualizado",
                  "icon" => "success"
              ];
            }else {
               $editar = [
                  "title" => "Error",
                  "message" => "Hubo un problema al editar el ususario",
                  "icon" => "error"
              ];
            }
        }
}else if(isset($_POST['borrar'])){
    if(!empty($_POST['usercode'])){
    $result = $objuser->eliminar($_POST["usercode"]);
    
    if ($result == 'success') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "El usuario ha sido eliminado",
                "icon" => "success"
            ];
    } elseif ($result == 'error_ultimo') {
            $eliminar = [
                "title" => "Error",
                "message" => "El usuario no se puede eliminar porque es el ultimo administrador",
                "icon" => "error"
            ];
        }
    } elseif ($result == 'error_delete') {
        $eliminar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar el usuario",
            "icon" => "error"
        ];
    }
}

$registro = $objuser->listar();
$_GET['ruta'] = 'usuarios';
require_once 'plantilla.php';