<?php 
require_once 'conexion.php';

class Tpago extends Conexion{
    private $metodo; 
    private $moneda; 
    private $status;

    public function __construct(){
        parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }

    public function incluir($valor){
        $registro="INSERT INTO tipo_pago(cod_cambio, medio_pago, status) VALUES(:cod_cambio, :medio_pago, 1)";
        parent::conectarBD();
        $strExec=$this->conex->prepare($registro);
        $strExec->bindParam(':medio_pago', $this->metodo);
        $strExec->bindParam(':cod_cambio', $valor);
        $resul=$strExec->execute();
        parent::desconectarBD();
        if ($resul){
            $res=1;
        }else{
            $res=0;
        }
        return $res;
    }

    public function consultar(){
        $registro="SELECT tp.cod_tipo_pago, tp.medio_pago, tp.status AS status_pago, d.cod_divisa, d.nombre, d.abreviatura, d.status AS status_divisa, cd.cod_cambio, cd.tasa  FROM tipo_pago tp 
        JOIN cambio_divisa cd ON tp.cod_cambio = cd.cod_cambio 
        JOIN divisas d ON cd.cod_divisa = d.cod_divisa ORDER BY tp.cod_tipo_pago;";
        parent::conectarBD();
        $consulta=$this->conex->prepare($registro);
        $resul=$consulta->execute();
        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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
        parent::conectarBD();
            $dato=$this->conex->prepare($registro);
            $resul=$dato->execute();
            $resultado=$dato->fetch(PDO::FETCH_ASSOC);
        parent::desconectarBD();
            if ($resul) {
                return $resultado;
            }else{
                return false;
            }
    
    }

    public function editar($valor){
        $registro="UPDATE tipo_pago SET medio_pago=:medio_pago, status=:status WHERE cod_tipo_pago=$valor";
        parent::conectarBD();
        $strExec = $this->conex->prepare($registro);
        #instanciar metodo bindparam
        $strExec->bindParam(':medio_pago', $this->metodo);
        $strExec->bindParam(':status', $this->status);
        $resul = $strExec->execute();
        parent::desconectarBD();
        if($resul){
            $r = 1;
        }else{
            $r = 0;
        }
        return $r;
    }

    public function eliminar($valor){
        $registro="SELECT COUNT(*) AS v_count FROM detalle_pagos dp WHERE dp.cod_tipo_pago=$valor;";
        parent::conectarBD();
        $strExec = $this->conex->prepare($registro);
        $resul = $strExec->execute();
        if($resul){
            $resultado=$strExec->fetch(PDO::FETCH_ASSOC); 
            if ($resultado['v_count']>0){
                $r='error';
            }else{
                $fisico="DELETE FROM tipo_pago WHERE cod_tipo_pago=$valor";
                $strExec=$this->conex->prepare($fisico);
                $strExec->execute();
                $r='success';
            }
            
        }else {
            $r='error_delete';
        }
        parent::desconectarBD();
        return $r;
    }

    #set
    public function setmetodo($valor){
        $this->metodo=$valor;
    }
    public function setmoneda($valor){
        $this->moneda=$valor;
    }
    public function setstatus($valor){
        $this->status=$valor;
    }

    #get
    public function getmetodo(){
        return $this->metodo;
    }
    public function getmoneda(){
        return $this->moneda;
    }
    public function getstatus(){
        return $this->status;
    }
}