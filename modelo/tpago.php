<?php 
require_once 'conexion.php';
require_once 'validaciones.php';

class Tpago extends Conexion{
    private $metodo; 
    private $moneda; 
    private $status;
    use ValidadorTrait;
    private $errores = [];


    public function __construct(){
        parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }

    public function check(){
        if (!empty($this->errores)) {
            $mensajes = implode(" | ", $this->errores);
            throw new Exception("Errores de validación: $mensajes");
        }
    }

    public function registrar($tipopago){
        $consulta="INSERT INTO detalle_tipo_pago ( cod_metodo, tipo_moneda, cod_cuenta_bancaria, cod_caja) VALUES ( :cod_metodo, :tipo_moneda, :cod_cuenta_bancaria, :cod_caja);";
        $codcuenta = isset($tipopago['cod_cuenta_bancaria']) ? $tipopago['cod_cuenta_bancaria'] : null;
        $codcaja = isset($tipopago['cod_caja']) ? $tipopago['cod_caja'] : null;
        parent::conectarBD();
        $strExec=$this->conex->prepare($consulta);
        $strExec->bindParam(':cod_metodo', $tipopago['cod_metodo']);
        $strExec->bindParam(':tipo_moneda', $tipopago['tipo_moneda']);
        $strExec->bindParam(':cod_cuenta_bancaria', $codcuenta);
        $strExec->bindParam(':cod_caja', $codcaja);
        $resul=$strExec->execute();
        parent::desconectarBD();
        if($resul){
            return $res=1;
        }else{
            return $res=0;
        }
    }


    public function incluir(){
        $registro="INSERT INTO tipo_pago(medio_pago, status) VALUES(:medio_pago, 1)";
        parent::conectarBD();
        $strExec=$this->conex->prepare($registro);
        $strExec->bindParam(':medio_pago', $this->metodo);
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
        $registro="SELECT 
                        dtp.*,
                        tp.medio_pago,
                        tp.status AS status_tipo_pago,

                        CASE 
                            WHEN dtp.tipo_moneda = 'digital' THEN 'cuenta_bancaria'
                            WHEN dtp.tipo_moneda = 'efectivo' THEN 'caja'
                            ELSE 'indefinido'
                        END AS origen,

                        CASE 
                            WHEN dtp.tipo_moneda = 'digital' THEN 
                                CONCAT(b.nombre_banco, ' - ', cb.numero_cuenta, ' (', tc.nombre, ')')
                            WHEN dtp.tipo_moneda = 'efectivo' THEN 
                                c.nombre
                            ELSE 
                                'Sin descripción'
                        END AS descripcion,

                        -- Divisa asociada (de cuenta o caja, según el tipo)
                        CASE 
                            WHEN dtp.tipo_moneda = 'digital' THEN dcb.nombre
                            WHEN dtp.tipo_moneda = 'efectivo' THEN dc.nombre
                            ELSE 'Sin divisa'
                        END AS nombre_divisa,

                        CASE 
                            WHEN dtp.tipo_moneda = 'digital' THEN dcb.abreviatura
                            WHEN dtp.tipo_moneda = 'efectivo' THEN dc.abreviatura
                            ELSE '-'
                        END AS abreviatura_divisa,
                        
                        CASE 
                            WHEN dtp.tipo_moneda = 'digital' THEN dcb.cod_divisa
                            WHEN dtp.tipo_moneda = 'efectivo' THEN dc.cod_divisa
                            ELSE '-'
                        END AS cod_divisa

                    FROM detalle_tipo_pago dtp

                    LEFT JOIN tipo_pago tp ON dtp.cod_metodo = tp.cod_metodo

                    LEFT JOIN cuenta_bancaria cb ON dtp.cod_cuenta_bancaria = cb.cod_cuenta_bancaria
                    LEFT JOIN banco b ON cb.cod_banco = b.cod_banco
                    LEFT JOIN tipo_cuenta tc ON cb.cod_tipo_cuenta = tc.cod_tipo_cuenta
                    LEFT JOIN divisas dcb ON cb.cod_divisa = dcb.cod_divisa

                    LEFT JOIN caja c ON dtp.cod_caja = c.cod_caja
                    LEFT JOIN divisas dc ON c.cod_divisas = dc.cod_divisa;";
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

    public function cuenta(){
        $registro="SELECT
                        cb.*,
                        b.nombre_banco,
                        tc.nombre AS tipo_cuenta_nombre,
                        d.nombre AS divisa_nombre,
                        d.abreviatura AS divisa_abreviatura
                    FROM cuenta_bancaria cb
                    JOIN banco b ON cb.cod_banco = b.cod_banco
                    JOIN tipo_cuenta tc ON cb.cod_tipo_cuenta = tc.cod_tipo_cuenta
                    JOIN divisas d ON cb.cod_divisa = d.cod_divisa;";
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

    public function caja(){
        $registro="SELECT
                        c.*,
                        d.nombre AS divisa_nombre,
                        d.abreviatura AS divisa_abreviatura
                    FROM caja c
                    JOIN divisas d ON c.cod_divisas = d.cod_divisa;";
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

    public function mediopago(){
        $registro="SELECT * FROM tipo_pago;";
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

    public function editar($valor, $cod_metodo){
        $registro="UPDATE tipo_pago SET medio_pago=:medio_pago WHERE cod_metodo=$cod_metodo";
        $reg="UPDATE detalle_tipo_pago SET status=:status WHERE cod_tipo_pago=$valor";
        parent::conectarBD();
        #editar el metodo de pago
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':medio_pago', $this->metodo);
        $resul = $strExec->execute();
        #editar el status del detalle tipo pago
        $str=$this->conex->prepare($reg);
        $str->bindParam(':status', $this->status);
        $resul2 = $str->execute();
        parent::desconectarBD();
        if($resul && $resul2){
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
        $resultado=$this->validarTexto($valor, "Medio pago", 1, 50);
        if($resultado === true){
            $this->metodo = $valor;
        }else{
            $this->errores['rol'] = $resultado;
        }
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