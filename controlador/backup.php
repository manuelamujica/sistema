<?php

require_once "modelo/backup.php";
require_once "modelo/bitacora.php";

$objbitacora = new Bitacora();
$objBackup = new Backup();

$backup=$objBackup->getMostrar();

if(isset($_POST['generar_respaldo'])){
    $errores = [];
    try {
        $objBackup->setNombreArchivo($_POST["nombre_backup"]);
        $objBackup->check(); 

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
            $result = $objBackup->getGenerarRespaldoManual($_SESSION['cod_usuario']);
                if($result == 1){
                    $registrar = [
                        "title" => "Registrado con éxito",
                        "message" => "La copia de seguridad ha sido registrada",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de Copia de Seguridad', $_POST["nombre_backup"], 'Backup');
                }else{
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al registrar la Copia de Seguridad",
                        "icon" => "error"
                    ];
                }
            }
        }

$c=$objBackup->getConfig();
require_once 'plantilla.php';