<?php

require_once "modelo/backup.php";
require_once "modelo/bitacora.php";

$objbitacora = new Bitacora();
$objBackup = new Backup();

$backup=$objBackup->getMostrar();


//REGISTRAR RESPALDO MANUAL
if(isset($_POST['generar_respaldo'])){
    $errores = [];
    try {
        //var_dump($_POST);
        $objBackup->setDatos($_POST);
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
            // 1. Si no hay errores, proceder con el registro
            $result = $objBackup->getGenerarRespaldoManual($_SESSION['cod_usuario']);

            // 2. Aplicar política de retención si corresponde
            $config = $objBackup->getConfig();

            if (in_array($config['modo'], ['ambos'])) {
                $objBackup->geteliminarRetencion($config['retencion'], 'manual');
            }

            // 3. Notificación front y bitácora
                if($result == 1){
                    $registrar = [
                        "title" => "Registrado con éxito",
                        "message" => "La copia de seguridad ha sido registrada",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro Manual de Copia de Seguridad', 'Nombre Archivo'. $_POST["nombre_backup"], 'Backup');
                }else{
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al registrar la Copia de Seguridad",
                        "icon" => "error"
                    ];
                }
            }
        }

//GUARDAR CONFIGURACION 
else if(isset($_POST['guardar_config'])){
    $errores = [];
    
    try {
            $automatico = isset($_POST['habilitado']);
            $data = [];

            if ($automatico) {
                $data['frecuencia'] = $_POST["frecuencia"] ?? null;
                $data['dia'] = $_POST["dia"] ?? null;
                $data['hora'] = $_POST["hora"] ?? null;
                $data['habilitado'] = 1;
            } else {
                $data['frecuencia'] = $_POST["frecuencia_hidden"] ?? null;
                $data['dia'] = $_POST["dia_hidden"] ?? null;
                $data['hora'] = $_POST["hora_hidden"] ?? null;
                $data['habilitado'] = 0;
            }

            $data['retencion'] = $_POST["retencion"] ?? null;
            $data['modo']      = $_POST["modo"] ?? null;

            // Pasar todo junto al modelo
            $objBackup->setDatos($data);
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
            $result = $objBackup->getGuardarConfig();
                if($result == 1){
                    $registrar = [
                        "title" => "Registrado con éxito",
                        "message" => "La configuración ha sido registrada",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Se actualizo la configuracion de Copia de Seguridad', '', 'Backup');
                }else{
                    $registrar = [
                        "title" => "Error",
                        "message" => "Hubo un problema al registrar la configuración",
                        "icon" => "error"
                    ];
                }
            }
        }

//ELIMINAR RESPALDO
else if(isset($_POST['eliminarR'])){
    $errores = [];

    try {
        $objBackup->setDatos($_POST);
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
        $result = $objBackup->getEliminar($_POST['codE']);

        if($result == 1){
            $registrar = [
                "title" => "Eliminado con éxito",
                "message" => "La copia de seguridad ha sido eliminada",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminacion de Copia de Seguridad', 'Archivo: '. $_POST["rutaE"], 'Backup');
        }else{
            $registrar = [
                "title" => "Error",
                "message" => "Hubo un problema al eliminar la Copia de Seguridad",
                "icon" => "error"
            ];
        }
    }
}

$config=$objBackup->getConfig();
require_once 'plantilla.php';