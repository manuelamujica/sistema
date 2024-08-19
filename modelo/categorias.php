<?php 
require_once "conexion.php";

class Categoria extends Conexion{

    private $conex;
    private $nombre;
    private $status;

    public function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

#GETTER Y SETTER
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }


/*==============================
REGISTRAR CATEGORIA
================================*/
    private function registrar(){

        $sql = "INSERT INTO categorias(nombre,status) VALUES(:nombre,1)";

        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":nombre", $this->nombre);

        $resul = $strExec->execute();

        if($resul){
            $r = 1;
        }else{
            $r = 0;
        }
        return $r;

    }

    public function getregistrar(){
        return $this->registrar();
    }
    
/*==============================
VALIDAR CATEGORIAS
================================*/
public function buscar($valor){
    $this->nombre=$valor;
    $registro = "select * from categorias where nombre='".$this->nombre."'";
    $resultado= "";
        $dato=$this->conex->prepare($registro);
        $resul=$dato->execute();
        $resultado=$dato->fetch(PDO::FETCH_ASSOC);  
        if ($resul) {
            return $resultado;
        }else{
            return false;
        }
}
/*==============================
MOSTRAR CATEGORIAS
================================*/
    public function mostrar(){
        $registro = "select * from categorias";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();

        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if($resul){
            return $datos;
        }else{
            return $r=0;
        }

    }
    
}
