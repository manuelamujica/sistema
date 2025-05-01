<?php 
require_once "conexion.php";
class Unidad extends Conexion{

    private $conex;
    private $tipo_medida;
    private $status;

    private $cod_unidad;


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
    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }

    public function setCod($cod_unidad){
        $this->cod_unidad = $cod_unidad;
    }

    public function getCod(){
        return $this->cod_unidad;
    }

/*==============================
REGISTRAR UNIDAD DE MEDIDA
================================*/
    private function crearUnidad(){

        $sql = "INSERT INTO unidades_medida(tipo_medida,status) VALUES(:tipo_medida, 1)";

        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":tipo_medida", $this->tipo_medida);

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

    private function buscar($dato){
        $this->tipo_medida = $dato;
        $registro="select * from unidades_medida where tipo_medida='".$this->tipo_medida."'";
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

    public function getbuscar($dato){
        return $this->buscar($dato);
    }

    private function buscarcod($valor){
        $this->tipo_medida=$valor;
        $registro = "select * from tipo_usuario where rol='".$this->tipo_medida."'";
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

    public function getcodU($valor){
        return $this->buscarcod($valor);
    }

    private function editar(){
        $registro = "UPDATE unidades_medida SET tipo_medida = :tipo_medida, status = :status WHERE cod_unidad = :cod_unidad";

        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':cod_unidad',$this->cod_unidad);
        $strExec->bindParam(':tipo_medida',$this->tipo_medida);
        $strExec->bindParam(':status', $this->status);
        $resul = $strExec->execute();
        if($resul == 1){
            $r = 1;
        }else{
            $r = 0;
        }
        return $r;
    }

    public function geteditar(){
        return $this->editar();
    }

    

    private function eliminar($valor){ 
        $registro = "SELECT COUNT(*) AS n_p FROM presentacion_producto WHERE cod_unidad = $valor";
        $strExec = $this->conex->prepare($registro);
        $strExec->execute();
        $resul = $strExec->fetch(PDO::FETCH_ASSOC);

        if($resul){

            $registro = "SELECT status FROM unidades_medida WHERE cod_unidad = $valor";
            $strExec = $this->conex->prepare($registro);
            $strExec->execute();
            $resu = $strExec->fetch(PDO::FETCH_ASSOC);

            if($resu['status'] != 0){ // Si esta inactivo
                $r = 'error_status';
                return $r;
            }

            if($resul['n_p'] != 0){ //Si tiene productos
                $r='error_associated';
                return $r;
            }

            $f = "DELETE FROM unidades_medida WHERE cod_unidad = $valor";
            $strExec = $this->conex->prepare($f);
            $ress=$strExec->execute();
            if($ress){
                $r='success';
                return $r;
            }else{
                $r='error_delete';
                return $r;
            }
        } else{
            $r='error_query';
            return $r;
        }
    }

    public function geteliminar($valor){
        return $this->eliminar($valor);
    }
}

