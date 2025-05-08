<?php
require_once "conexion.php";
require_once "validaciones.php";

class Backup extends Conexion {

    use ValidadorTrait; // Usar el trait para validaciones
    private $nombreArchivo;
    private $ruta;
    private $tamanio;

    private $errores = [];

    public function __construct() {
        parent::__construct(_SEC_DB_HOST_, _SEC_DB_NAME_, _SEC_DB_USER_, _SEC_DB_PASS_);
    }


    // ========== GETTERS Y SETTERS ==========

    public function getNombreArchivo() {
        return $this->nombreArchivo;
    }

    public function setNombreArchivo($nombre) {
        $this->nombreArchivo($nombre); // usa directamente el trait
    }
    

    public function getRuta() {
        return $this->ruta;
    }

    public function getTamanio() {
        return $this->tamanio;
    }

    public function getErrores() {
        return $this->errores;
    }

    public function check() {
        if (!empty($this->errores)) {
        $mensajes = implode(" | ", $this->errores);
        throw new Exception("Errores de validación: $mensajes");
    }
    }
    

    private function generarRespaldoManual($cod_usuario) {
        try {
            $this->check();
    
            $n = $this->nombreArchivo . '_' . date("Y-m-d_H-i-s") . ".sql";
            $directorio = "respaldos/";
            $this->ruta = $directorio . $n;
    
            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }
    
            //$comando = "C:/xampp/mysql/bin/mysqldump.exe -u " . _SEC_DB_USER_ . " -p\"" . _SEC_DB_PASS_ . "\" " . _SEC_DB_NAME_ . " " . _DB_NAME_ . " > " . $this->ruta;
            
           
            
            $comando = "C:/xampp/mysql/bin/mysqldump.exe --user=" . _SEC_DB_USER_ . " --databases " . _SEC_DB_NAME_ . " " . _DB_NAME_ . " > " . $this->ruta;

            //system($comando);
            exec($comando, $output, $return_var);
            if ($return_var !== 0) {
                throw new Exception("mysqldump falló con código: $return_var");
            }

            

            if (!file_exists($this->ruta)) {
                throw new Exception("No se generó el archivo.");
            }
    
            $this->tamanio = filesize($this->ruta) / 1024 / 1024;
    
            $sql = "INSERT INTO backup (cod_usuario, descripcion, ruta, fecha, tipo, tamanio)
                    VALUES (:cod_usuario, :descripcion, :ruta, :fecha, :tipo, :tamanio)";
    
            parent::conectarBD();
            $stmt = $this->conex->prepare($sql);
    
            $fecha = date("Y-m-d H:i:s");
            $tipo = "manual";
    
            $stmt->bindParam(':cod_usuario', $cod_usuario);
            $stmt->bindParam(':ruta', $this->ruta);
            $stmt->bindParam(':descripcion', $this->nombreArchivo);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':tamanio', $this->tamanio);
    
            $res = $stmt->execute();
    
            if (!$res) {
                throw new Exception("No se pudo registrar el respaldo.");
            }
    
            return true;
    
        } catch (Exception $e) {
            throw new Exception("Error al generar respaldo manual: " . $e->getMessage());
        } finally {
            parent::desconectarBD();
        }
    }
        
    public function getGenerarRespaldoManual($cod_usuario) {
        return $this->generarRespaldoManual($cod_usuario);
    }
        


    /*==============================
    MOSTRAR RESPALDOS
    ==============================*/
    private function mostrar() {
        $sql = "SELECT * FROM backup ORDER BY fecha DESC";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
        return $datos;
    }

    public function getMostrar() {
        return $this->mostrar();
    }

    /*==============================
    ELIMINAR RESPALDO
    ==============================
    private function eliminar($cod_backup) {
        $sql = "SELECT ruta FROM backup WHERE cod_backup = :id";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(":id", $cod_backup);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res && file_exists($res['ruta'])) {
            unlink($res['ruta']);
        }

        $del = $this->conex->prepare("DELETE FROM backup WHERE cod_backup = :id");
        $del->bindParam(":id", $cod_backup);
        $resDel = $del->execute();
        parent::desconectarBD();
        return $resDel ? 'success' : 'error_delete';
    }

    public function getEliminar($cod_backup) {
        return $this->eliminar($cod_backup);
    }
*/
    /*==============================
    CONFIGURACIÓN DE BACKUP
    ==============================*/
    private function obtenerConfig() {
        $sql = "SELECT * FROM config_backup LIMIT 1";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);
        parent::desconectarBD();
        return $datos;
    }

    public function getConfig() {
        return $this->obtenerConfig();
    }

    /*private function guardarConfig($data) {
        $sql = "UPDATE config_backup SET 
                    frecuencia = :frecuencia,
                    retencion = :retencion,
                    hora = :hora,
                    dia = :dia,
                    habilitado = :habilitado
                WHERE cod_config_backup = :id";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(":frecuencia", $data['frecuencia']);
        $stmt->bindParam(":retencion", $data['retencion']);
        $stmt->bindParam(":hora", $data['hora']);
        $stmt->bindParam(":dia", $data['dia']);
        $stmt->bindParam(":habilitado", $data['habilitado'], PDO::PARAM_BOOL);
        $stmt->bindParam(":id", $data['cod_config_backup']);
        $res = $stmt->execute();
        parent::desconectarBD();
        return $res ? 1 : 0;
    }

    public function setConfig($data) {
        return $this->guardarConfig($data);
    }*/
}
