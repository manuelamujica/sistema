<?php
require_once 'conexion.php';

class Finanzas extends Conexion {
    private $errores = [];

    public function __construct() {
        parent::__construct();
    }

    /*======================================================
    OBTENER RESUMEN FINANCIERO
    ========================================================*/
    public function obtenerResumenFinanciero() {
        $this->conectarBD();
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN m.tipo = 'ingreso' THEN m.monto ELSE 0 END), 0) as total_ingresos,
                    COALESCE(SUM(CASE WHEN m.tipo = 'egreso' THEN m.monto ELSE 0 END), 0) as total_egresos,
                    COALESCE(SUM(CASE WHEN m.tipo = 'ingreso' THEN m.monto ELSE -m.monto END), 0) as balance
                FROM movimientos m 
                WHERE m.fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        if($resul) {
            return $datos[0];
        } else {
            return [];
        }
    }

    /*======================================================
    OBTENER ÚLTIMOS MOVIMIENTOS
    ========================================================*/
    public function obtenerUltimosMovimientos($limite = 10) {
        $this->conectarBD();
        $sql = "SELECT m.cod_mov, m.descripcion, m.monto, m.fecha, cm.nombre as categoria 
                FROM movimientos m 
                LEFT JOIN categoria_movimiento cm ON m.tipo = cm.cod_categoria 
                WHERE m.status = 1 
                ORDER BY m.fecha DESC 
                LIMIT :limite";
        
        $consulta = $this->conex->prepare($sql);
        $consulta->bindParam(':limite', $limite, PDO::PARAM_INT);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        if($resul) {
            return $datos;
        } else {
            return [];
        }
    }

    /*======================================================
    OBTENER CUENTAS ACTIVAS
    ========================================================*/
    public function obtenerCuentasActivas() {
        $this->conectarBD();
        $sql = "SELECT cb.*, b.nombre_banco, tc.nombre as tipo_cuenta, d.nombre as divisa
                FROM cuenta_bancaria cb
                INNER JOIN banco b ON cb.cod_banco = b.cod_banco
                INNER JOIN tipo_cuenta tc ON cb.cod_tipo_cuenta = tc.cod_tipo_cuenta
                INNER JOIN divisas d ON cb.cod_divisa = d.cod_divisa";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        if($resul) {
            return $datos;
        } else {
            return [];
        }
    }
} 