<?php
require_once "modelo/roles.php"; //requiero al modelo
require_once "modelo/bitacora.php"; 
$objbitacora = new Bitacora(); 
$objRol = new Rol();

if (isset($_POST['buscar'])) {
    $rol = $_POST['buscar'];
    $result = $objRol->buscar($rol);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;

} else if (isset($_POST["guardar"])) {
    if (!empty($_POST["rol"])) {
        $length = strlen($_POST["rol"]);
        if ($length < 50) {
            if (preg_match("/^[a-zA-Z\s]+$/", $_POST["rol"])) {

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
                            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de rol', $_POST["rol"], 'Roles');
                        } else {
                            $registrar = [
                                "title" => "Error",
                                "message" => "Hubo un problema al registrar el rol",
                                "icon" => "error"
                            ];
                        }
                    } else {
                            $registrar = [
                                "title" => "Error",
                                "message" => "No se puede registrar porque el nombre del rol ya existe.",
                                "icon" => "error"
                            ];
                        }
                } else {
                    $registrar = [
                        "title" => "Error",
                        "message" => "El rol solo debe contener letras. No se permiten números ni caracteres especiales.",
                        "icon" => "error"
                    ];
                }
            } else {
                $registrar = [
                    "title" => "Error",
                    "message" => "El rol no debe excederse de 50 caracteres",
                    "icon" => "error"
                ];
            }
    } else {
        $registrar = [
            "title" => "Error",
            "message" => "No se permiten campos vacíos.",
            "icon" => "error"
        ];
    }

} else if (isset($_POST['editar'])) {
        $codigo = $_POST['cod_tipo_usuario'];
        $rol = $_POST['rol'];
        $status = $_POST['status'];
    
        if (!empty($rol)) {
            $length = strlen($rol);
                if ($length < 50) {
                    if (preg_match("/^[a-zA-Z\s]+$/", $rol)) {
                        // Validar si el rol ha cambiado y si ya existe otro rol con el mismo nombre
                        if ($rol !== $_POST['origin'] && $objRol->buscar($rol)) {
                            $editar = [
                                "title" => "Error",
                                "message" => "Rol ya existente. No se pudo editar.", 
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
                                $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar rol', $_POST["rol"], 'Roles');
                            } else {
                                $editar = [
                                    "title" => "Error",
                                    "message" => "Hubo un problema al editar el rol", 
                                    "icon" => "error"
                                ];
                            }
                        }
                    } else {
                        $editar = [
                            "title" => "Error",
                            "message" => "El rol solo debe contener letras. No se permiten números ni caracteres especiales.", 
                            "icon" => "error"
                        ];
                    }
                } else {
                    $editar = [
                        "title" => "Error",
                        "message" => "El rol no debe excederse de 50 caracteres",
                        "icon" => "error"
                    ];
                }
            } else {
                $editar = [
                    "title" => "Error",
                    "message" => "No se permiten campos vacíos.",
                    "icon" => "error"
                ];
            }
    
} else if (isset($_POST['eliminar'])) {
    $codigo = $_POST['eliminar'];
    $objRol->setcodigo($codigo);
    $resul = $objRol->geteliminar($codigo);

        if ($resul == 'success') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "El rol ha sido eliminado",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar rol', "Eliminado el rol con el código ".$_POST["eliminar"], 'Roles');
        } else if ($resul == 'error_associated') {
            $eliminar = [
                "title" => "Error",
                "message" => "El rol no se puede eliminar porque tiene usuarios asociados",
                "icon" => "error"
            ];
        } else if ($resul == 'error_delete') {
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un problema al eliminar el rol",
                "icon" => "error"
            ];
        } else if ($resul == 'error_status') {
            $eliminar = [
                "title" => "Error",
                "message" => "El rol no se puede eliminar porque tiene status: activo.",
                "icon" => "error"
            ];
        } else {
            $eliminar = [
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
