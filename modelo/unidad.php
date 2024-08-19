<?php 
require_once "conexion.php";
class Unidad extends Conexion{

    private $conex;
    private $tipo_medida;
    private $presentacion;
    private $cantidad_presentacion;
    private $status;


    public function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

#GETTER Y SETTER
    public function getTipo(){
        return $this->tipo_medida;
    }
    public function setTipo($tipo_medida){
        $this->tipo_medida = $tipo_medida;
    }
    public function getPresentacion(){
        return $this->presentacion;
    }
    public function setPresentacion($presentacion){
        $this->presentacion = $presentacion;
    }
    public function getCantidad(){
        return $this->cantidad_presentacion;
    }
    public function setCantidad($cantidad_presentacion){
        $this->cantidad_presentacion = $cantidad_presentacion;
    }
    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }

/*==============================
REGISTRAR UNIDAD DE MEDIDA
================================*/
    private function crearUnidad(){

        $sql = "INSERT INTO unidades_medida(tipo_medida,presentacion,cantidad_presentacion,status) VALUES(:tipo_medida,:presentacion,:cantidad_presentacion, 1)";

        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":tipo_medida", $this->tipo_medida);
        $strExec->bindParam(":presentacion", $this->presentacion);
        $strExec->bindParam(":cantidad_presentacion", $this->cantidad_presentacion);

        $resul = $strExec->execute();

        if($resul){
            $r = 1;
        }else{
            $r = 0;
        }
        return $r;

    }
    public function getcrearUnidad(){
        return $this->crearUnidad();
    }

    /*==============================
    MOSTRAR UNIDADES DE MEDIDAS
================================*/

    public function consultarUnidad(){
        $sql = "select * from unidades_medida";
        $consulta = $this->conex->prepare($sql);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if($resul){
            return $datos;
        }return $r = 0;
    }

    public function buscar($dato){
        $this->presentacion = $dato;
        $registro="select * from unidades_medida where presentacion='".$this->presentacion."'";
        $resultado= "";
        $dato = $this->conex->prepare($registro);
        $resul = $dato->execute();
        $resultado=$dato->fetch(PDO::FETCH_ASSOC);
        if($resul){
            return $resultado;
        }else{
            return false;
        }
    }

}