<?php

require_once "modelo/usuarios.php"; 
require_once "modelo/roles.php"; 
require_once 'modelo/bitacora.php';

$objuser= new Usuario();
$objroles = new Rol();
$objbitacora = new Bitacora();
$roles = $objroles->getconsultarUsuario(); // Obtener los roles para pasarlos a la vista

if(isset($_POST['buscar'])){
    $user = $_POST['buscar']; 
    $result = $objuser->buscar($user);
    header('Content-Type: application/json'); 
    echo json_encode($result); 
    exit;

}else if (isset($_POST['guardar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['user']) && !empty($_POST['pass']) && !empty($_POST['rol'])){
        if (!$objuser->buscar($_POST["user"])){ #Que no sea el mismo user
            
            $nombre = strlen($_POST['nombre']);
            $user = strlen($_POST['user']);

            if($nombre < 50){
                if($user < 20){
                    if(preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $_POST['nombre']) && preg_match('/^[a-zA-Z0-9]+$/', $_POST['user'])){
                        
                        #Validar la longitud + formato de la contraseña
                        $longitud = strlen($_POST["pass"]);

                        if($longitud >= 8 && preg_match('/^[a-zA-Z0-9!@#$%^&*()\/,.?":{}|<>]+$/',$_POST["pass"] ) && $_POST["pass"] !== $_POST["user"]){

                            $password = password_hash($_POST["pass"], PASSWORD_DEFAULT); // guardar la contraseña cifrada con HASH
                            
                            $objuser->setNombre($_POST["nombre"]);
                            $objuser->setUser($_POST["user"]);
                            $objuser->setPassword($password);
                            $rol = $_POST["rol"];
                            $result = $objuser->getregistrar($rol);

                            if($result == 1){
                                $registrar = [
                                    "title" => "Registrado con éxito",
                                    "message" => "El usuario ha sido registrado",
                                    "icon" => "success"
                                ];
                                $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de usuario', $_POST["user"], 'Usuarios');
                            }else{
                                $registrar = [
                                    "title" => "Error",
                                    "message" => "Hubo un problema al registrar el usuario",
                                    "icon" => "error"
                                ];
                            }
                        } else {
                            $registrar = [
                            "title" => "Error",
                            "message" => "La contraseña no cumple con los requisitos. Intenta de nuevo",
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
                } else {
                    $registrar = [
                        "title" => "Error",
                        "message" => "No se pudo registrar. El user excede la longitud permitida.",
                        "icon" => "error"
                        ];
                }
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "No se pudo registrar. El nombre excede la longitud permitida.",
                    "icon" => "error"
                    ];
            }
        } else {
            $registrar = [
                "title" => "Error",
                "message" => "No se pudo registrar porque el nombre de usuario ya existe.",
                "icon" => "error"
                ];
        }
    }else{
        $registrar = [
            "title" => "Error",
            "message" => "No se permiten campos vacíos",
            "icon" => "error"
        ];
    }
}


else if (isset($_POST['actualizar'])) {
    
    $passwordCambiada=0;

    if (!empty($_POST['nombre']) && !empty($_POST['user']) && !empty($_POST['roles']) && isset($_POST['status'])) {

        if ($_POST['user'] !== $_POST['origin']) {
            // Si el user cambió, verificamos si ya existe en la base de datos
            if ($objuser->buscar($_POST['user'])) {
                $advertencia = [
                    "title" => "Error",
                    "message" => "No se pudo registrar porque el nombre de usuario ya existe.",
                    "icon" => "error"
                ];
            }
        }

        $nombre = strlen($_POST['nombre']);
        $user = strlen($_POST['user']);

            if($nombre < 50){
                if($user < 20){
                    if(preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $_POST['nombre']) && preg_match('/^[a-zA-Z0-9]+$/', $_POST['user'])){
                        //Password
                        if (!empty($_POST['pass'])) {
                            $longitud = strlen($_POST['pass']);
                    
                            if($longitud >= 8 && preg_match('/^[a-zA-Z0-9!@#$%^&*()\/,.?":{}|<>]+$/',$_POST["pass"]) && $_POST["pass"] != $_POST["user"]) {
                                
                                $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                                $passwordCambiada = 1;

                            } else {
                                $editar = [
                                    "title" => "Error",
                                    "message" => "La contraseña no con los requisitos. Intenta de nuevo",
                                    "icon" => "error"
                                ];
                            }
                        } 

                        $objuser->setNombre($_POST['nombre']);
                        $objuser->setUser($_POST['user']);
                        $objuser->setStatus($_POST['status']);

                    if ($passwordCambiada == 1) {
                        // Si se cambió la contraseña, usamos el método que también actualiza la contraseña
                        $objuser->setPassword($password);
                        $result = $objuser->editar2($_POST['codigo'], $_POST['roles']);
                        
                    } else {
                        // Si no se cambió la contraseña, usamos el método que no la modifica
                        $result = $objuser->editar($_POST['codigo'], $_POST['roles']);
                    }

                    if ($result == 1) {
                        $editar = [
                            "title" => "Editado con éxito",
                            "message" => "El usuario ha sido actualizado correctamente",
                            "icon" => "success"
                        ];
                        $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar usuario', $_POST["user"], 'Usuarios');
                    } else {
                        $editar = [
                            "title" => "Error",
                            "message" => "Hubo un problema al editar el usuario",
                            "icon" => "error"
                        ];
                    }
                } else {
                        $editar = [
                            "title" => "Error",
                            "message" => "No se pudo registrar. Caracteres no permitidos.",
                            "icon" => "error"
                        ];
                    }
                } else {
                    $editar = [
                        "title" => "Error",
                        "message" => "No se pudo editar. El user excede la longitud permitida.",
                        "icon" => "error"
                        ];
                }
            } else {
                $editar = [
                    "title" => "Error",
                    "message" => "No se pudo editar. El nombre excede la longitud permitida.",
                    "icon" => "error"
                    ];
            }
        } else {
            $editar = [
                "title" => "Error",
                "message" => "No se permiten campos vacíos",
                "icon" => "error"
            ];
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
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar usuario', "Eliminado el usuario con el código ".$_POST["usercode"], 'Usuarios');
        } elseif ($result == 'error_status') {
            $eliminar = [
                "title" => "Error",
                "message" => "No se puede eliminar un usuario con status activo.",
                "icon" => "error"
            ];
        } elseif ($result == 'error_ultimo') {
                $eliminar = [
                    "title" => "Error",
                    "message" => "El usuario no se puede eliminar porque es el último administrador",
                    "icon" => "error"
                ];
        } elseif ($result == 'error_delete') {
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un problema al eliminar el usuario",
                "icon" => "error"
            ];
        } 
    }
}

$registro = $objuser->listar();
$_GET['ruta'] = 'usuarios';
require_once 'plantilla.php';