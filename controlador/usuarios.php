<?php

require_once "modelo/usuario.php"; 

$objuser= new Usuario();

$roles = $objuser->roles(); // Obtener los roles para pasarlos a la vista

if(isset($_POST['buscar'])){
    $user = $_POST['buscar']; 
    $result = $objuser->buscar($user);
    header('Content-Type: application/json'); 
    echo json_encode($result); 
    exit;

}else if (isset($_POST['guardar'])){

    if(!empty($_POST["nombre"]) && (!empty($_POST["user"])) && (!empty($_POST["pass"]))){ #ping

        if (!$objuser->buscar($_POST["user"])){ 

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
                    echo "<script>
                    alert('Registrado con exito');
                    location = 'usuarios' </script>";
            }else{
                    echo "<script>
                    alert('No se pudo completar el registro');
                    location = 'usuarios' </script>";
            }
        }
    }
}else if (isset($_POST['actualizar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['user'])){
        
        $objuser->setNombre($_POST['nombre']);
        $objuser->setUser($_POST['user']);

        $password = password_hash($_POST["pass"], PASSWORD_DEFAULT); // guardar la contraseña cifrada con HASH
        $objuser->setPassword($password);

        $objuser->setStatus($_POST['status']);

        $result=$objuser->editar($_POST['codigo'], $_POST['roles']);

        if($result==1){
            echo "<script>
                    alert('modificado con exito');
                    location = 'usuarios'
                </script>";
        }else {
            echo "<script>
                    alert('no se pudo modificar');
                    location = 'usuarios'
                </script>";
        }
    }
}else if(isset($_POST['borrar'])){
    if(!empty($_POST['usercode'])){
    $result = $objuser->eliminar($_POST["usercode"]);
    
    if ($result == 'success') {
        echo "<script>
                alert('Usuario eliminado exitosamente.');
                location = 'usuarios';
              </script>";
    } elseif ($result == 'error_ultimo') {
        echo "<script>
                alert('No se puede eliminar porque es el último administrador.');
                location = 'usuarios';
              </script>";
        }
    } elseif ($result == 'error_delete') {
        echo "<script>
                alert('Hubo un error al intentar eliminar el usuario.');
                location = 'usuarios';
              </script>";
    }
}

$registro = $objuser->listar();
$_GET['ruta'] = 'usuarios';
require_once 'plantilla.php';