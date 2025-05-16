<?php
require_once 'conexion.php';

class Finanzas extends Conexion {
    private $errores = [];

    public function __construct() {
        parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
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

    /*======================================================
    OBTENER ROTACIÓN DE INVENTARIO
    ========================================================*/
    public function obtenerRotacionInventario() {
        $this->conectarBD();
        $sql = "SELECT 
                p.cod_producto,
                p.nombre as producto,
                sm.stock_inicial,
                sm.stock_final,
                sm.ventas_cantidad,
                sm.rotacion,
                sm.dias_rotacion
                FROM stock_mensual sm
                JOIN detalle_productos dp ON sm.cod_detallep = dp.cod_detallep
                JOIN presentacion_producto pp ON dp.cod_presentacion = pp.cod_presentacion
                JOIN productos p ON pp.cod_producto = p.cod_producto
                WHERE sm.mes = MONTH(CURRENT_DATE()) AND sm.ano = YEAR(CURRENT_DATE())";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
    }

    /*======================================================
    OBTENER RESUMEN DE ROTACIÓN
    ========================================================*/
    public function obtenerResumenRotacion() {
        $this->conectarBD();
        $sql = "SELECT 
                AVG(rotacion) as rotacion_promedio,
                AVG(dias_rotacion) as dias_rotacion_promedio,
                SUM(ventas_cantidad) as total_ventas
                FROM stock_mensual
                WHERE mes = MONTH(CURRENT_DATE()) AND ano = YEAR(CURRENT_DATE())";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetch(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
    }

    /*======================================================
    OBTENER RENTABILIDAD DE PRODUCTOS
    ========================================================*/
    public function obtenerRentabilidadProductos() {
        $this->conectarBD();
        $sql = "SELECT 
                p.cod_producto,
                p.nombre as producto,
                ar.ventas_totales,
                ar.costo_ventas,
                ar.margen_bruto,
                (ar.margen_bruto / ar.ventas_totales * 100) as rentabilidad,
                ((ar.ventas_totales - ar.costo_ventas) / ar.costo_ventas * 100) as roi
                FROM analisis_rentabilidad ar
                JOIN detalle_productos dp ON ar.cod_detallep = dp.cod_detallep
                JOIN presentacion_producto pp ON dp.cod_presentacion = pp.cod_presentacion
                JOIN productos p ON pp.cod_producto = p.cod_producto
                WHERE MONTH(ar.fecha_calculo) = MONTH(CURRENT_DATE()) 
                AND YEAR(ar.fecha_calculo) = YEAR(CURRENT_DATE())";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
    }

    /*======================================================
    OBTENER RESUMEN DE RENTABILIDAD
    ========================================================*/
    public function obtenerResumenRentabilidad() {
        $this->conectarBD();
        $sql = "SELECT 
                AVG(margen_bruto / ventas_totales * 100) as rentabilidad_promedio,
                AVG((ventas_totales - costo_ventas) / costo_ventas * 100) as roi_promedio,
                SUM(margen_bruto) as margen_bruto_total
                FROM analisis_rentabilidad
                WHERE MONTH(fecha_calculo) = MONTH(CURRENT_DATE()) 
                AND YEAR(fecha_calculo) = YEAR(CURRENT_DATE())";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetch(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
    }

    /*======================================================
    OBTENER CATEGORÍAS DE PRESUPUESTO
    ========================================================*/
    public function obtenerCategoriasPresupuesto() {
        $this->conectarBD();
        $sql = "SELECT * FROM categoria_movimiento WHERE status = 1";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
    }

    /*======================================================
    OBTENER DATOS DE PRESUPUESTO MENSUAL
    ========================================================*/
    public function obtenerDatosPresupuestoMensual() {
        $this->conectarBD();
        $sql = "SELECT 
                cm.nombre as categoria,
                p.monto as presupuesto,
                COALESCE(SUM(g.monto), 0) as gasto_real
                FROM presupuestos p
                JOIN categoria_movimiento cm ON p.categoria = cm.cod_categoria
                LEFT JOIN gasto g ON g.cod_cat_gasto = p.categoria 
                AND MONTH(g.fecha) = p.mes AND YEAR(g.fecha) = p.anio
                WHERE p.mes = MONTH(CURRENT_DATE()) AND p.anio = YEAR(CURRENT_DATE())
                GROUP BY cm.nombre, p.monto";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
    }

    /*======================================================
    OBTENER RESUMEN DE PRESUPUESTO
    ========================================================*/
    public function obtenerResumenPresupuesto() {
        $this->conectarBD();
        $sql = "SELECT 
                SUM(p.monto) as presupuesto_total,
                COALESCE(SUM(g.monto), 0) as gasto_real_total,
                SUM(p.monto) - COALESCE(SUM(g.monto), 0) as diferencia,
                CASE 
                    WHEN SUM(p.monto) > 0 
                    THEN (COALESCE(SUM(g.monto), 0) / SUM(p.monto)) * 100 
                    ELSE 0 
                END as porcentaje_utilizado
                FROM presupuestos p
                LEFT JOIN gasto g ON g.cod_cat_gasto = p.categoria 
                AND MONTH(g.fecha) = p.mes AND YEAR(g.fecha) = p.anio
                WHERE p.mes = MONTH(CURRENT_DATE()) AND p.anio = YEAR(CURRENT_DATE())";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetch(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
    }
} 