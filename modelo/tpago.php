<?php 
require_once 'conexion.php';

class Tpago extends Conexion{
    private $metodo; 
    private $moneda; 
    private $conex;

    public function __construct(){
        $this->conex= new Conexion();
        $this->conex=$this->conex->conectar();
    }

    public function incluir(){
        $registro="INSERT INTO tipo_pago(medio_pago, status) VALUES(:medio_pago, 1)";

        $strExec=$this->conex->prepare($registro);
        $strExec->bindParam(':medio_pago', $this->metodo);
        //$strExec->bindParam(':moneda', $this->moneda);
        $resul=$strExec->execute();
        if ($resul){
            $res=1;
        }else{
            $res=0;
        }
        return $res;
    }

    public function consultar(){
        $registro="select * from tipo_pago";
        $consulta=$this->conex->prepare($registro);
        $resul=$consulta->execute();
        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
        if($resul){
            return $datos;
        }else{
            return $res=0;
        }
    }

    public function buscar($valor){
        $this->metodo=$valor;
        $registro = "select * from tipo_pago where medio_pago='".$this->metodo."'";
        $resutado= "";
            $dato=$this->conex->prepare($registro);
            $resul=$dato->execute();
            $resultado=$dato->fetch(PDO::FETCH_ASSOC);
            if ($resul) {
                return $resultado;
            }else{
                return false;
            }
    
    }

    #set
    public function setmetodo($valor){
        $this->metodo=$valor;
    }
    public function setmoneda($valor){
        $this->moneda=$valor;
    }

    #get
    public function getmetodo(){
        return $this->metodo;
    }
    public function getmoneda(){
        return $this->moneda;
    }
}