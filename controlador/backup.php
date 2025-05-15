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
        $objBackup->setNombreArchivo($_POST["nombre_backup"]);
        $objBackup->setDescripcion($_POST["desc_backup"]);
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
//que co;o valido aqui????
if(isset($_POST['guardar_config'])){
    $errores = [];
    
    try {
            $automatico = isset($_POST['habilitado']);

            if($automatico){
                $objBackup->setFrecuencia($_POST["frecuencia"]);
                $objBackup->setDia($_POST["dia"]);
                $objBackup->setHora($_POST["hora"]);
                $objBackup->setHabilitado(1);
            }else{
                $objBackup->setFrecuencia($_POST["frecuencia_hidden"]);
                $objBackup->setDia($_POST["dia_hidden"]);
                $objBackup->setHora($_POST["hora_hidden"]);
                $objBackup->setHabilitado(0);
            }

            $objBackup->setRetencion($_POST["retencion"]);
            $objBackup->setModo($_POST["modo"]);
            
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


$config=$objBackup->getConfig();
require_once 'plantilla.php';