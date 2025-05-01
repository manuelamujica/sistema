<?php
require_once 'conexion.php';
class Pago extends Conexion{
    private $monto_total;
    private $monto_dpago;
    private $cod_venta;
    private $cod_pago;

    public function __construct(){
        parent::__construct( _DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }
    public function get_montototal(){
        return $this->monto_total;
    }
    public function set_montototal($valor){
        $this->monto_total=$valor;
    }
    public function get_montodpago(){
        return $this->monto_dpago;
    }
    public function set_montodpago($valor){
        $this->monto_dpago=$valor;
    }
    public function get_cod_venta(){
        return $this->cod_venta;
    }
    public function set_cod_venta($valor){
        $this->cod_venta=$valor;
    }
    public function set_cod_pago($valor){
        $this->cod_pago=$valor;
    }
    public function get_cod_pago(){
        return $this->cod_pago;
    }

    public function registrar($pago, $monto_venta){
        $sql="INSERT INTO pagos(cod_venta, monto_total) VALUES(:cod_venta, :monto_total)";
        parent::conectarBD();
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_venta', $this->cod_venta);
        $strExec->bindParam(':monto_total', $this->monto_total);
        $resul = $strExec->execute();
        if($resul){
            $nuevo_cod = $this->conex->lastInsertId();
            foreach ($pago as $pagos){
                if(!empty($pagos['monto']) && $pagos['monto']>0){
                    $registro="INSERT INTO detalle_pagos(cod_pago, cod_tipo_pago, monto) VALUES($nuevo_cod, :cod_tipo_pago, :monto)";
                    $sentencia=$this->conex->prepare($registro);
                    $sentencia->bindParam(':cod_tipo_pago', $pagos['cod_tipo_pago']);
                    $sentencia->bindParam(':monto', $pagos['monto']);
                    $sentencia->execute();
                }
            }
            if($monto_venta > $this->monto_total){
                $estado="UPDATE ventas SET status= 2 WHERE cod_venta=:cod_venta";
                $strExec=$this->conex->prepare($estado);
                $strExec->bindParam(':cod_venta', $this->cod_venta);
                $strExec->execute();
                $r=$monto_venta-$this->monto_total;
            } else if($monto_venta <= $this->monto_total){
                $estado="UPDATE ventas SET status= 3 WHERE cod_venta=:cod_venta";
                $strExec=$this->conex->prepare($estado);
                $strExec->bindParam(':cod_venta', $this->cod_venta);
                $strExec->execute();
                $r=0;
            }
        }
        parent::desconectarBD();
    return $r;
    }

    public function parcialp($pago){
        parent::conectarBD();
        foreach ($pago as $pagos){
            if(!empty($pagos['monto']) && $pagos['monto']>0){
                $registro="INSERT INTO detalle_pagos(cod_pago, cod_tipo_pago, monto) VALUES(:cod_pago, :cod_tipo_pago, :monto)";
                $sentencia=$this->conex->prepare($registro);
                $sentencia->bindParam(':cod_pago', $this->cod_pago);
                $sentencia->bindParam(':cod_tipo_pago', $pagos['cod_tipo_pago']);
                $sentencia->bindParam(':monto', $pagos['monto']);
                $resul=$sentencia->execute();
                if($resul){
                    $sql="UPDATE pagos SET monto_total=monto_total+:abono WHERE cod_pago=:cod_pago;";
                    $sen=$this->conex->prepare($sql);
                    $sen->bindParam(':abono', $this->monto_dpago);
                    $sen->bindParam(':cod_pago', $this->cod_pago);
                    $sen->execute();
                    if($this->monto_total>$this->monto_dpago){
                        $estado="UPDATE ventas SET status= 2 WHERE cod_venta=:cod_venta";
                        $strExec=$this->conex->prepare($estado);
                        $strExec->bindParam(':cod_venta', $this->cod_venta);
                        $strExec->execute();
                        $r=$this->monto_total-$this->monto_dpago;
                    } else if($this->monto_total <= $this->monto_dpago){
                        $estado="UPDATE ventas SET status= 3 WHERE cod_venta=:cod_venta";
                        $strExec=$this->conex->prepare($estado);
                        $strExec->bindParam(':cod_venta', $this->cod_venta);
                        $strExec->execute();
                        $r=0;
                    }
                    
                }
                
            }
        }
        parent::desconectarBD();
        return $r;
    }

}