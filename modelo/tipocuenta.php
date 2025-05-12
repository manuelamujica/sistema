<?php
require_once "conexion.php";

class Tipo_cuenta extends Conexion {
    private $nombre;
    private $cod_tipo_cuenta;
    
    public function __construct() {
        parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }

    public function consultarTipo(){
        $sql = "select * from tipo_cuenta";
        parent::conectarBD();
        $consulta = $this->conex->prepare($sql);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
        if($resul){
            return $datos;
        }return $r = 0;
    }

    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function getNombre() {
        return $this->nombre;
    }

    public function setCod_tipo_cuenta($cod_tipo_cuenta) {
        $this->cod_tipo_cuenta = $cod_tipo_cuenta;
    }
    public function getCod_tipo_cuenta() {
        return $this->cod_tipo_cuenta;
    }


}