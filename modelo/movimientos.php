<?php 
require_once "conexion.php";
Class Movimientos extends Conexion{

    public function __construct(){
        parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }

    public function consultar(){
        $sql="SELECT 
                    m.cod_mov,
                    m.fecha,
                    tpo.tipo AS tipo_operacion,
                    dpo.detalle_operacion AS detalle_operacion,

                    -- Información de la operación según su tipo
                    CASE 
                        WHEN m.cod_tipo_op = 1 THEN v.cod_venta
                        WHEN m.cod_tipo_op = 2 THEN c.cod_compra
                        WHEN m.cod_tipo_op = 3 THEN g.cod_gasto
                        ELSE NULL
                    END AS cod_operacion,

                    CASE 
                        WHEN m.cod_tipo_op = 1 THEN cl.nombre
                        WHEN m.cod_tipo_op = 2 THEN pr.razon_social
                        WHEN m.cod_tipo_op = 3 THEN g.descripcion
                        ELSE NULL
                    END AS descripcion_operacion,

                    CASE 
                        WHEN m.cod_tipo_op = 1 THEN v.total
                        WHEN m.cod_tipo_op = 2 THEN c.total
                        WHEN m.cod_tipo_op = 3 THEN g.monto
                        ELSE NULL
                    END AS monto

                FROM movimientos m
                JOIN tipo_operacion tpo ON m.cod_tipo_op = tpo.cod_tipo_op
                JOIN detalle_operacion dpo ON m.cod_detalle_op = dpo.cod_detalle_op

                -- LEFT JOIN con las tablas específicas según tipo de operación
                LEFT JOIN ventas v ON m.cod_tipo_op = 1 AND m.cod_operacion = v.cod_venta
                LEFT JOIN clientes cl ON v.cod_cliente = cl.cod_cliente
                LEFT JOIN compras c ON m.cod_tipo_op = 2 AND m.cod_operacion = c.cod_compra
                LEFT JOIN proveedores pr ON c.cod_prov = pr.cod_prov
                LEFT JOIN gasto g ON m.cod_tipo_op = 3 AND m.cod_operacion = g.cod_gasto

                ORDER BY m.fecha DESC;";
                parent::conectarBD();
                $stmt=$this->conex->prepare($sql);
                $stmt->execute();
                $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                parent::desconectarBD();
                return $result;
    }

    public function sincronizar($ventas){
        try{
            parent::conectarBD();
            $this->conex->beginTransaction();
            foreach($ventas['ventas'] as $venta){
                if($venta['tipo_operacion'] == 'venta'){
                    $cventa="SELECT 
                                SUM(dv.cantidad * pp.costo) AS costo_total
                            FROM ventas v
                            JOIN detalle_ventas dv ON dv.cod_venta = v.cod_venta
                            JOIN detalle_productos dp ON dp.cod_detallep = dv.cod_detallep
                            JOIN presentacion_producto pp ON pp.cod_presentacion = dp.cod_presentacion
                            WHERE v.cod_venta = :cod_operacion;";
                    $stmt=$this->conex->prepare($cventa);
                    $stmt->bindParam(':cod_operacion', $venta['cod_mov']);
                    $resul=$stmt->execute();
                    $costo=$stmt->fetchcolumn();
                    if(!$resul){
                        throw new Exception("Error al obtener el costo de la venta.");
                    }
                    $sql="INSERT INTO asientos_contables (cod_mov, fecha, descripcion, total, status) 
                            VALUES (:cod_mov, NOW(), :descripcion, :total, 1);";
                    $descripcion=$venta['tipo_operacion']." ".$venta['detalle_operacion']." #".$venta['cod_operacion'];
                    $stmt=$this->conex->prepare($sql);
                    $stmt->bindParam(':cod_mov', $venta['cod_mov']);
                    $stmt->bindParam(':descripcion', $descripcion);
                    $stmt->bindParam(':total', $costo);
                    $resul=$stmt->execute();
                    if(!$resul){
                        throw new Exception("Error al insertar el asiento contable.");
                    }
                    $cod_asiento=$this->conex->lastInsertId();

                    $det="INSERT INTO detalle_asientos (cod_asiento, cod_cuenta, monto, tipo) 
                            VALUES (:cod_asiento, :cod_cuenta, :monto, 1) (:cod_asiento, :cod_cuenta1, :monto, 2);";
                    

                    if($venta['detalle_operacion']=='al contado'){
                        $pago="SELECT 
                                    SUM(p.monto_total) AS total_pagado
                                FROM pago_recibido p
                                WHERE p.cod_venta = :cod_operacion;";
                        $stmt=$this->conex->prepare($pago);
                        $stmt->bindParam(':cod_operacion', $venta['cod_mov']);
                        $resul=$stmt->execute();
                        $totalp=$stmt->fetchcolumn();
                        if(!$resul){
                            throw new Exception("Error al obtener el total pagado.");
                        }




                        $det="INSERT INTO detalle_asientos (cod_asiento, cod_cuenta, monto, tipo) 
                                VALUES (:cod_asiento, :cod_cuenta, :monto, :tipo);";
                    }

                }
            }

        }catch(PDOException $e){
            return "Error: ".$e->getMessage();
        }finally{
            parent::desconectarBD();
        }
    }


}
