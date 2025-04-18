<?php
require_once "conexion.php";
require_once "validaciones.php";

class Banco extends Conexion {
    use ValidadorTrait;

    private $conex;
    private $nombre_banco;

    public function __construct() {
        $this->conex = (new Conexion())->conectar();
    }

  
    public function setNombre($nombre_banco) {
        $this->nombre_banco = $nombre_banco;
    }

    public function getNombreBanco() {
        return $this->nombre_banco;
    }

    // REGISTRAR
    private function registrar() {
        $sql = "INSERT INTO banco (nombre_banco) VALUES (:nombre_banco)";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':nombre_banco', $this->nombre_banco);

        return $stmt->execute() ? 1 : 0;
    }

    public function getRegistrar() {
        return $this->registrar();
    }

    // CONSULTAR TODOS
    public function consultar() {
        $sql = "SELECT * FROM banco";
        $stmt = $this->conex->prepare($sql);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return 0;
    }

    // ACTUALIZAR
    private function actualizar($cod_banco) {
        $sql = "UPDATE banco SET nombre_banco = :nombre_banco WHERE cod_banco = :cod_banco";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':nombre_banco', $this->nombre_banco);
        $stmt->bindParam(':cod_banco', $cod_banco, PDO::PARAM_INT);

        return $stmt->execute() ? 1 : 0;
    }

    public function getactualizar($cod_banco) {
        return $this->actualizar($cod_banco);
    }

    // ELIMINAR (soft delete o real)
    public function eliminar($cod_banco) {
        // Aquí puedes hacer soft delete si agregas un campo status
        $sql = "DELETE FROM banco WHERE cod_banco = :cod_banco";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod_banco', $cod_banco, PDO::PARAM_INT);

        return $stmt->execute() ? 'success' : 'error_delete';
    }

    // BUSCAR por nombre (para evitar duplicados)
    public function buscarPorNombre($nombre) {
        $sql = "SELECT * FROM banco WHERE nombre_banco = :nombre_banco";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':nombre_banco', $nombre);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>