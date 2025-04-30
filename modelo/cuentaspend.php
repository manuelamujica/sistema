<?php 
require_once "conexion.php";

class CuentasPendientes extends Conexion{

    private $conex;

    public function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

//BOX CUENTAS X COBRAR
private function boxcobrar(){
    $sql = "SELECT 
    SUM(saldo_pendiente) AS total_cobrar
    FROM (
        SELECT 
            v.cod_venta,
            (v.total - COALESCE(SUM(pr.monto_total),0)) AS saldo_pendiente
        FROM ventas v
        LEFT JOIN pago_recibido pr ON pr.cod_venta = v.cod_venta
        WHERE v.status IN (1, 2)
        GROUP BY v.cod_venta
    ) AS subconsulta
    WHERE saldo_pendiente > 0;";

    $consulta = $this->conex->prepare($sql);
    $resul = $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if($resul){
        return $datos;
    }else{
        return $r=0;
    }
}

public function getboxcobrar(){
    return $this->boxcobrar();
}

//METODO PARA EL LISTADO DE CUENTAS POR COBRAR: POR CLIENTE
private function mostrar(){
    $sql = "SELECT
    c.cod_cliente,
    c.nombre AS cliente,
    COUNT(v.cod_venta) AS total_ventas,
    SUM(v.total) AS total,
    SUM(COALESCE(pr.monto_total, 0)) AS total_cobrado,
    SUM(v.total - COALESCE(pr.monto_total, 0)) AS total_pendiente
    FROM ventas v
    INNER JOIN clientes c ON v.cod_cliente = c.cod_cliente
    LEFT JOIN pago_recibido pr ON pr.cod_venta = v.cod_venta
    WHERE v.status IN (1, 2) 
    GROUP BY c.cod_cliente, c.nombre
    ORDER BY cliente;";
    $consulta = $this->conex->prepare($sql);
    $resul = $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if($resul){
        return $datos;
    }else{
        return [];
    }

}
    
public function getmostrarcliente(){
    return $this->mostrar();
}

//METODO PARA EL LISTADO DE CUENTAS POR COBRAR: VENTAS DE UN CLIENTE
private function mostrar2($cod_cliente){
    $sql="SELECT
    v.cod_venta,
    v.total,
    v.fecha,
    v.fecha_vencimiento,
    COALESCE(SUM(pr.monto_total),0) AS monto_pagado,
    (v.total - COALESCE(SUM(pr.monto_total),0)) AS saldo_pendiente,
    DATEDIFF(v.fecha_vencimiento, CURDATE()) AS dias_restantes,
        CASE	
            WHEN v.status = 3 THEN 'Pagado'
            WHEN v.status = 2 THEN 'Pago parcial'
            WHEN v.fecha_vencimiento < CURDATE() THEN 'Vencido'
            ELSE 'Pendiente'
        END AS estado,
        c.nombre,
        c.apellido,
        c.cedula_rif,
        c.direccion,
        c.telefono
    FROM ventas v
    LEFT JOIN pago_recibido pr ON pr.cod_venta = v.cod_venta
    INNER JOIN clientes c ON v.cod_cliente = c.cod_cliente
    WHERE v.cod_cliente = :cod_cliente AND v.status IN (1, 2)
    GROUP BY v.cod_venta, v.total, v.fecha, v.fecha_vencimiento, v.status,
    c.nombre, c.apellido, c.cedula_rif, c.direccion, c.telefono
    ORDER BY v.fecha_vencimiento ASC;
";
    $consulta = $this->conex->prepare($sql);
    $consulta->bindParam(':cod_cliente', $cod_cliente, PDO::PARAM_INT);
    $resul = $consulta->execute();  
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if($resul){
        return $datos;
    }else{
            return [];
        }
}

public function getmostrar2($cod_cliente){
    return $this->mostrar2($cod_cliente);
}


/*METODO PARA EL LISTADO DE CUENTAS POR PAGAR
Acomodar los status de acuerdo a los pagos (como venta)* ademas debo incluir GASTOS*/

private function mostrarCuentasPagar(){
    $sql = "SELECT
	c.cod_compra,
    p.razon_social AS proveedor,
    c.total,
    c.fecha_vencimiento,
    c.fecha,
    COALESCE(SUM(pe.monto_total),0) AS monto_pagado,
    (c.total - COALESCE(SUM(pe.monto_total),0)) AS saldo_pendiente,
    DATEDIFF(c.fecha_vencimiento, CURDATE()) AS dias_restantes,
        CASE	
            WHEN c.fecha_vencimiento < CURDATE() THEN 'Vencido'
            ELSE 'Pendiente'
        END AS estado
    FROM compras c
    INNER JOIN proveedores p ON p.cod_prov = p.cod_prov
    LEFT JOIN pago_emitido pe ON pe.cod_compra = c.cod_compra
    WHERE c.status IN(1,2)
    GROUP BY c.cod_compra, p.razon_social, c.total, c.fecha_vencimiento, c.status  
    ORDER BY `c`.`fecha_vencimiento` ASC;";
    $consulta = $this->conex->prepare($sql);
    $resul = $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if($resul){
        return $datos;
    }else{
        return [];
    }
}

public function getmostrarCuentasPagar(){
    return $this->mostrarCuentasPagar();
}
}


