<?php
require_once "conexion.php";
require_once "validaciones.php";

class Banco extends Conexion {
    use ValidadorTrait;
    private $nombre_banco;
    private $cod_banco;
    private $errores = [];

    public function __construct() {
        parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }

    public function setNombre($nombre_banco){
        $resultado = $this->validarTexto($nombre_banco, 'nombre', 2, 50);
        if ($resultado === true) {
            $this->nombre_banco = $nombre_banco;
        } else {
            $this->errores['nombre_banco'] = $resultado;
        }
        $this->nombre_banco = $nombre_banco;
    }
    public function getNombreBanco() {
        return $this->nombre_banco;
    }

     // Chequear si hay errores
    public function check() {
        if (!empty($this->errores)) {
            $mensajes = implode(" | ", $this->errores);
            throw new Exception("Errores de validación: $mensajes");
        }
    }


    // REGISTRAR
    private function registrar() {
        $sql = "INSERT INTO banco (nombre_banco) VALUES (:nombre_banco)";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':nombre_banco', $this->nombre_banco);
        $resul=$stmt->execute() ? 1 : 0;
        parent::desconectarBD();
        return $resul;
    }

    public function getRegistrar() {
        return $this->registrar();
    }

    // CONSULTAR TODOS
    public function consultar() {
        $sql = "SELECT * FROM banco";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        if ($stmt->execute()) {
            $resul=$stmt->fetchAll(PDO::FETCH_ASSOC);
            parent::desconectarBD();
            return $resul;
        }
        parent::desconectarBD();
        return 0;
    }

    // ACTUALIZAR
    private function actualizar($cod_banco) {
        $sql = "UPDATE banco SET nombre_banco = :nombre_banco WHERE cod_banco = :cod_banco";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':nombre_banco', $this->nombre_banco);
        $stmt->bindParam(':cod_banco', $cod_banco, PDO::PARAM_INT);
        $resultado=$stmt->execute() ? 1 : 0;
        parent::desconectarBD();
        return $resultado;
    }

    public function getactualizar($cod_banco) {
        return $this->actualizar($cod_banco);
    }

    // ELIMINAR (soft delete o real)
    public function eliminar($cod_banco) {
        // Aquí puedes hacer soft delete si agregas un campo status
        $sql = "DELETE FROM banco WHERE cod_banco = :cod_banco";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod_banco', $cod_banco, PDO::PARAM_INT);
        $resultado=$stmt->execute() ? 'success' : 'error_delete';
        parent::desconectarBD();
        return $resultado;
    }
     
    public function getEliminar($cod_banco) {
        return $this->eliminar($cod_banco);
    }

    // BUSCAR por nombre (para evitar duplicados)
    public function buscarPorNombre($nombre) {
        $sql = "SELECT * FROM banco WHERE nombre_banco = :nombre_banco";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':nombre_banco', $nombre);
        $stmt->execute();
        $resultado=$stmt->fetch(PDO::FETCH_ASSOC);
        parent::desconectarBD();
        return $resultado;
    }
}
?>