<?php
require_once 'conexion.php';
require_once 'validaciones.php';
class Pago extends Conexion{
    private $monto_total;
    private $monto_dpago;
    private $cod_venta;
    private $cod_pago;
    private $fecha_pago;
    private $cod_vuelto;
    private $vuelto;
    use ValidadorTrait;
    private $errores=[];


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

    public function setdatap($datos){
        if($this->validarDecimal($datos['monto_pagado'], 'Monto pagado', 1, 20)){
            $this->monto_total = $datos['monto_pagado'];
        }else{
            $this->errores['monto_pagado'] = $this->validarDecimal($datos['monto_pagado'], 'Monto pagado', 1, 20);
        }
        if($this->validarNumerico($datos['nro_venta'], 'Número de venta', 1, 20)){
            $this->cod_venta = $datos['nro_venta'];
        }else{
            $this->errores['nro_venta'] = $this->validarNumerico($datos['nro_venta'], 'Número de venta', 1, 20);
        }
        if($this->validardatetime($datos['fecha_pago'], 'Fecha de pago')){
            $this->fecha_pago = $datos['fecha_pago'];
        }else{
            $this->errores['fecha_pago'] = $this->validardatetime($datos['fecha_pago'], 'Fecha de pago');
        }
        if(!empty($datos['vuelto_data'])){
            parse_str($datos['vuelto_data'], $this->vuelto);
            echo '<script>console.log(' . json_encode($this->vuelto) . ');</script>';
        }else{
            $this->cod_vuelto = null;
        }
    }

    public function registrar($pago, $monto_venta){
        try{
        parent::conectarBD();
        $this->conex->beginTransaction();
        if(!empty($this->vuelto)){
            $sql="INSERT INTO vuelto_emitido(vuelto_total) VALUES(:vuelto_total)";
            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(':vuelto_total', $this->vuelto['vuelto_pagado']);
            $resultado=$strExec->execute();
            if($resultado){
                $this->cod_vuelto = $this->conex->lastInsertId();
                foreach($this->vuelto['vuelto'] as $dvuelto){
                    if(!empty($dvuelto['monto']) && $dvuelto['monto']>0){
                        $registro="INSERT INTO detalle_vueltoe(cod_vuelto, cod_tipo_pago, monto) VALUES(:cod_vuelto, :cod_tipo_pago, :monto)";
                        $sentencia=$this->conex->prepare($registro);
                        $sentencia->bindParam(':cod_vuelto', $this->cod_vuelto);
                        $sentencia->bindParam(':cod_tipo_pago', $dvuelto['cod_tipo_pago']);
                        $sentencia->bindParam(':monto', $dvuelto['monto']);
                        $sentencia->execute();
                    }
                }
            }else{
                throw new Exception("Error al registrar el vuelto");
            }
        }

        $sql="INSERT INTO pago_recibido(cod_venta, cod_vuelto, fecha, monto_total) VALUES(:cod_venta, :cod_vuelto, :fecha, :monto_total)";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_venta', $this->cod_venta);
        $strExec->bindParam(':cod_vuelto', $this->cod_vuelto);
        $strExec->bindParam(':fecha', $this->fecha_pago);
        $strExec->bindParam(':monto_total', $this->monto_total);
        $resul = $strExec->execute();
        if($resul){
            $nuevo_cod = $this->conex->lastInsertId();
            foreach ($pago as $pagos){
                if(!empty($pagos['monto']) && $pagos['monto']>0){
                    $registro="INSERT INTO detalle_pago_recibido(cod_pago, cod_tipo_pago, monto) VALUES($nuevo_cod, :cod_tipo_pago, :monto)";
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
        }else{
            throw new Exception("Error al registrar el pago");
        }
        $this->conex->commit();
        return $r;
        }catch(PDOException $e){
            $this->conex->rollBack();
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        }finally{
            parent::desconectarBD();
        }
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