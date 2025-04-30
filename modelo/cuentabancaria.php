<?php

require_once 'conexion.php';
require_once 'validaciones.php';



class CuentaBancaria extends Conexion {
    private $conex;
    use ValidadorTrait;
    private $errores = [];
    
    
    private $cod_cuenta_bancaria;
    private $cod_banco;
    private $cod_tipo_cuenta;
    private $numero_cuenta;
    private $saldo;
    private $cod_divisa;
    private $id_usuario;
    private $status;

    public function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

     
    public function setData($datos) {
        // Limpiar errores anteriores
        $this->errores = [];
    
        // Validar y asignar nombre
        if (isset($datos['numero_cuenta'])) {
            $resultado = $this->validarNumerico($datos['numero_cuenta'], 'numero_cuenta', 20, 20);
            if ($resultado === true) {
                $this->numero_cuenta = $datos['numero_cuenta'];
            } else {
                $this->errores['numero_cuenta'] = $resultado;
            }
        }
       
        // Validar y asignar cedula
        if (isset($datos['cod_banco'])) {
            $resultado = $this->validarNumerico($datos['cod_banco'], 'cod_banco', 1, 10);
            if ($resultado === true) {
                $this->cod_banco = $datos['cod_banco'];
            } else {
                $this->errores['cod_banco'] = $resultado;
            }
        }
    
        // Validar y asignar telefono
        if (isset($datos['cod_tipo_cuenta'])) {
            $resultado = $this->validarNumerico($datos['cod_tipo_cuenta'] , 'cod_tipo_cuenta', 1, 10);
            if ($resultado === true) {
                $this->cod_tipo_cuenta = $datos['cod_tipo_cuenta'];
            } else {
                $this->errores['cod_tipo_cuenta'] = $resultado;
            }
        }
    
        // Validar y asignar email
        if (isset($datos['cod_divisa'])) {
            $resultado = $this->validarNumerico($datos['cod_divisa'], 'cod_divisa', 1, 10); 
            if ($resultado === true) {
                $this->cod_divisa = $datos['cod_divisa'];
            } else {
                $this->errores['cod_divisa'] = $resultado;
            }
        }
    
        // Validar y asignar direccion
        if (isset($datos['id_usuario'])) {
            $resultado = $this->validarNumerico($datos['id_usuario'], 'Usuario', 1, 10);
            if ($resultado === true) {
                $this->id_usuario = $datos['id_usuario'];
            } else {
                $this->errores['id_usuario'] = $resultado;
            }
        }

        if (isset($datos['status'])) {
            $resultado = $this->validarNumerico($datos['status'], 'status', 1, 10);
            if ($resultado === true) {
                $this->status = $datos['status'];
            } else {
                $this->errores['status'] = $resultado;
            }
        }
    }
  
    public function setCuentaBancaria($valor) {
        $resultado = $this->validarNumerico($valor, 'codigo Cuenta Bancaria', 20, 20);
        if ($resultado === true) {
            $this->cod_cuenta_bancaria = $valor;
        } else {
            $this->errores['cod_cuenta_bancaria'] = $resultado;
        }
    }
    // Chequear si hay errores
    public function check() {
        if (!empty($this->errores)) {
            $mensajes = implode(" | ", $this->errores);
            throw new Exception("Errores de validación: $mensajes");
        }
    }

    /*======================================================
    CONSULTAR TODAS LAS CUENTAS BANCARIAS CON JOINS
    ========================================================*/
    
    public function consultarTodas() {
        $sql = "SELECT cb.cod_cuenta_bancaria, b.nombre_banco AS nombre_banco, tc.nombre,
        cb.numero_cuenta, cb.saldo, cd.nombre AS divisa, u.nombre AS nombre_usuario FROM 
        cuenta_bancaria cb JOIN banco b ON cb.cod_banco = b.cod_banco 
        JOIN tipo_cuenta tc ON cb.cod_tipo_cuenta = tc.cod_tipo_cuenta 
        JOIN divisas cd ON cb.cod_divisa = cd.cod_divisa 
        JOIN usuarios u ON cb.id_usuario = u.cod_usuario;";
       
        $stmt = $this->conex->prepare($sql);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return 0;
    }

    /*======================================================
    CONSULTAR BANCOS DISPONIBLES
    ========================================================*/
    public function consultarBancos() {
        $sql = "SELECT * FROM banco WHERE status = 1";
        $consulta = $this->conex->prepare($sql);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resul ? $datos : [];
    }

    /*======================================================
    CONSULTAR TIPOS DE CUENTA
    ========================================================*/
    public function consultarTiposCuenta() {
        $sql = "SELECT * FROM tipo_cuenta WHERE status = 1";
        $consulta = $this->conex->prepare($sql);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resul ? $datos : [];
    }

    /*======================================================
    CONSULTAR DIVISAS DISPONIBLES
    ========================================================*/
    public function consultarDivisas() {
        $sql = "SELECT * FROM cambio_divisa WHERE status = 1";
        $consulta = $this->conex->prepare($sql);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resul ? $datos : [];
    }

    /*======================================================
    REGISTRAR NUEVA CUENTA BANCARIA
    ========================================================*/
    private function registrar() {
        $sql = "INSERT INTO cuenta_bancaria (
                cod_banco, 
                cod_tipo_cuenta, 
                numero_cuenta, 
                saldo, 
                cod_divisa, 
                id_usuario, 
                status
                ) VALUES (
                :cod_banco, 
                :cod_tipo_cuenta, 
                :numero_cuenta, 
                :saldo, 
                :cod_divisa, 
                :id_usuario, 
                :status)";
        
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_banco', $this->cod_banco);
        $strExec->bindParam(':cod_tipo_cuenta', $this->cod_tipo_cuenta);
        $strExec->bindParam(':numero_cuenta', $this->numero_cuenta);
        $strExec->bindParam(':saldo', $this->saldo);
        $strExec->bindParam(':cod_divisa', $this->cod_divisa);
        $strExec->bindParam(':id_usuario', $this->id_usuario);
        $strExec->bindParam(':status', $this->status);

        return $strExec->execute() ? 1 : 0;
    }

    public function getRegistrar() {
        return $this->registrar();
    }

    /*======================================================
    EDITAR CUENTA BANCARIA
    ========================================================*/
    private function editar() {
        $sql = "UPDATE cuenta_bancaria SET 
                cod_banco = :cod_banco,
                cod_tipo_cuenta = :cod_tipo_cuenta,
                numero_cuenta = :numero_cuenta,
                saldo = :saldo,
                cod_divisa = :cod_divisa,
                status = :status
                WHERE cod_cuenta_bancaria = :cod_cuenta";
        
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_banco', $this->cod_banco);
        $strExec->bindParam(':cod_tipo_cuenta', $this->cod_tipo_cuenta);
        $strExec->bindParam(':numero_cuenta', $this->numero_cuenta);
        $strExec->bindParam(':saldo', $this->saldo);
        $strExec->bindParam(':cod_divisa', $this->cod_divisa);
        $strExec->bindParam(':status', $this->status);
        $strExec->bindParam(':cod_cuenta', $this->cod_cuenta_bancaria);

        return $strExec->execute() ? 1 : 0;
    }

    public function getEditar() {
        return $this->editar();
    }

    /*======================================================
    VERIFICAR SI LA CUENTA TIENE MOVIMIENTOS
    ========================================================*/
    public function tieneMovimientos() {
        $sql = "SELECT COUNT(*) AS total FROM movimientos_bancarios 
                WHERE cod_cuenta_bancaria = :cod_cuenta";
        
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_cuenta', $this->cod_cuenta_bancaria);
        $strExec->execute();
        $resultado = $strExec->fetch(PDO::FETCH_ASSOC);

        return ($resultado['total'] > 0);
    }

    // Agregar estos métodos a la clase CuentaBancaria

public function importarTransacciones($cuentaId, $transacciones) {
    try {
        $this->conex->beginTransaction();
        
        foreach ($transacciones as $transaccion) {
            $sql = "INSERT INTO transacciones_bancarias 
                    (cod_cuenta_bancaria, referencia, monto, tipo, fecha_registro) 
                    VALUES (:cuentaId, :referencia, :monto, :tipo, NOW())";
            
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':cuentaId', $cuentaId);
            $stmt->bindParam(':referencia', $transaccion['Referencia']);
            $stmt->bindParam(':monto', $transaccion['Monto']);
            $stmt->bindParam(':tipo', $transaccion['Tipo']);
            $stmt->execute();
            
            // Actualizar saldo de la cuenta
            $signo = $transaccion['Tipo'] == 'CREDITO' ? '+' : '-';
            $sqlUpdate = "UPDATE cuenta_bancaria 
                         SET saldo = saldo $signo :monto 
                         WHERE cod_cuenta_bancaria = :cuentaId";
            
            $stmtUpdate = $this->conex->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':monto', $transaccion['Monto']);
            $stmtUpdate->bindParam(':cuentaId', $cuentaId);
            $stmtUpdate->execute();
        }
        
        $this->conex->commit();
        return ['success' => true, 'message' => 'Transacciones importadas correctamente'];
    } catch (PDOException $e) {
        $this->conex->rollBack();
        return ['success' => false, 'message' => 'Error al importar transacciones: ' . $e->getMessage()];
    }
}

    /*======================================================
    ELIMINAR CUENTA BANCARIA
    ========================================================*/
    private function eliminar() {
        // Primero verificamos si tiene movimientos
        if ($this->tieneMovimientos()) {
            return 'error_movimientos';
        }

        $sql = "DELETE FROM cuenta_bancaria 
                WHERE cod_cuenta_bancaria = :cod_cuenta";
        
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_cuenta', $this->cod_cuenta_bancaria);

        return $strExec->execute() ? 1 : 0;
    }

    public function getEliminar() {
        return $this->eliminar();
    }
}