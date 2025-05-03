<?php
require_once "conexion.php";

class Tipo_cuenta extends Conexion {
    private $conex;
    private $nombre;
    private $cod_tipo_cuenta;
    


    public function __construct()
    {
       $this->conex = new Conexion();
       $this->conex = $this->conex->conectar();
    }

    public function consultar(){
        $sql = "select * from tipo_cuenta";
        $consulta = $this->conex->prepare($sql);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
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
    