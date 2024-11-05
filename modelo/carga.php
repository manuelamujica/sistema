<?php
   require_once "conexion.php";

   class Carga extends Conexion{
    private $codigo;
    private $fecha;
    private $descripcion;
    private $status;
    private $conex;

    public function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

    // SETTER Y GETTER
    public function setCod($codigo){
        $this->codigo = $codigo;
    }
    public function setFecha($fecha){
        $this->fecha = $fecha;
    }
    public function setDes($descripcion){
        $this->descripcion = $descripcion;
    }
    public function setStatus($status){
        $this->status = $status;
    }
    public function getCod(){
        return $this->codigo;
    }
    public function getFecha(){
        return $this->fecha;
    }
    public function getDes(){
        return $this->descripcion;
    }
    public function getStatus(){
        return $this->status;
    }

    //     Funciones
    /* #######      REGISTRAR CARGA        #######   */
    private function registrar(){
        $registro = "INSERT INTO carga(fecha, descripcion, status) VALUES(:fecha, :descripcion, 1)";

        $strExec= $this->conex->prepare($registro);
        $strExec->bindParam(':fecha',$this->fecha);
        $strExec->bindParam(':descripcion', $this->descripcion);

        $result = $strExec->execute();

        if($result){
            $r = 1;
        }else{
            $r = 0;
        }

        return $r;

    }

    public function getcrear(){
       return $this->registrar();
    }

    private function buscar($valor){
        //$this->fecha=$valor;
        $registro = "select * from carga where fecha=:fecha";
        //$resutado= "";
            $dato=$this->conex->prepare($registro);
            $dato->bindParam('fecha',$valor);
            $resul=$dato->execute();
            $resultado=$dato->fetch(PDO::FETCH_ASSOC);
            if ($resul) {
                return $resultado;
            }else{
                return false;
            }
    
    }

    public function getbuscar($valor){
        return $this->buscar($valor);
    }

    /*######### CONSULTAR #########*/
    private function mostrarc(){
        $sql = "select * from carga";
        $strExec = $this->conex->prepare($sql);
        $resul=$strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if($resul){
            return $datos;
        }else{
            return $res= 0;
        }
    }

    public function getmosc(){
        return $this->mostrarc();
    }
    

    private function editar(){
        $sql = "UPDATE carga SET fecha = :fecha, descripcion = :descripcion, status = :status WHERE cod_carga = :cod_carga";

        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_carga', $this->codigo);
        $strExec->bindParam('fecha',$this->fecha);
        $strExec->bindParam(':descripcion',$this->descripcion);
        $strExec->bindParam(':status', $this->status);
        $resul = $strExec->execute();

        if($resul){
            $r = 1;
        }else{
            $r = 0;
        }

        return $r;

    }

    public function geteditar(){
        return $this->editar();
    }

    /**
     *  #########
     *  ELIMINAR CARGA
     *  #########
     */
    private function eliminar($valor){
        $registro = "SELECT COUNT(*) AS n_dcarga FROM detalle_carga WHERE cod_carga = $valor";
        $strExec = $this->conex->prepare($registro);
        $result = $strExec->execute();
        if($result){
            $resul = $strExec->fetch(PDO::FETCH_ASSOC);
            if($resul['n_dcarga'] > 0){
                $l = "UPDATE carga SET status = 2 WHERE cod_carga = $valor";
                $strExec = $this->conex->prepare($l);
                $strExec->execute();
            }else{
                $f = "DELETE FROM carga WHERE cod_carga = $valor";
                $strExec = $this->conex->prepare($f);
                $strExec->execute();
            }
            $res = 1;
        }else{
            $res = 0;
        }
        return $res;
    }

    public function geteliminar($valor){
        return $this->eliminar($valor);
    }

    public function producto($valor){
        //$this->fecha=$valor;
        $registro = "select * from productos where fecha=:fecha";
        //$resutado= "";
            $dato=$this->conex->prepare($registro);
            $dato->bindParam('fecha',$valor);
            $resul=$dato->execute();
            $resultado=$dato->fetch(PDO::FETCH_ASSOC);
            if ($resul) {
                return $resultado;
            }else{
                return false;
            }
    
    }

   }
?>