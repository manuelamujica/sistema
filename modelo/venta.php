<?php
require_once 'conexion.php';

class Venta extends Conexion{
    
    private $conex;
    private $total;
    private $fecha;
    private $descuento;

    public function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

    public function get_total(){
        return $this->total;
    }
    public function set_total($valor){
        $this->total = $valor;
    }

    public function getfecha(){
        return $this->fecha;
    }
    public function setfecha($valor){
        $this->fecha = $valor;
    }

    public function getdescuento(){
        return $this->descuento;
    }
    public function setdescuento($valor){
        $this->descuento = $valor;
    }

    public function consultar(){
    $registro="SELECT v.*, c.*, v.status AS status_venta,c.status AS status_cliente FROM ventas v INNER JOIN clientes c ON v.cod_cliente = c.cod_cliente;";
        $consulta=$this->conex->prepare($registro);
        $resul=$consulta->execute();
        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
        if($resul){
            return $datos;
        }else{
            return $res=0;
        }
    }

    public function b_productos($valor){

        $sql="SELECT
            p.cod_producto,
            p.nombre,
            p.costo,
            p.marca,
            p.excento,
            p.porcen_venta,
            c.nombre AS cat_nombre,
            (CONCAT(present.presentacion, ' x ', present.cantidad_presentacion, ' ', u.tipo_medida)) AS presentacion
        FROM productos AS p
        JOIN categorias AS c ON p.cod_categoria = c.cod_categoria
        JOIN presentacion_producto AS present ON p.cod_producto = present.cod_producto
        JOIN unidades_medida AS u ON present.cod_unidad = u.cod_unidad
        WHERE p.nombre LIKE ? GROUP BY p.cod_producto LIMIT 5";

        $consulta = $this->conex->prepare($sql);
        $buscar = '%' . $valor . '%';
        $consulta->bindParam(1, $buscar, PDO::PARAM_STR);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if($resul){
            return $datos;
        }else{
            return [];
        }
    }


    /*public function registrar($cliente, $productos){
        $registro = "INSERT INTO ventas(cod_cliente, total, fecha, status) VALUES(:cod_cliente, :total, :fecha, 1)";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':cod_cliente',$cliente);
        $strExec->bindParam(':total', $this->total);
        $strExec->bindParam(':fecha', $this->fecha);
        $resul = $strExec->execute();

        if($resul){
            $nuevo_cod=$this->conex->lastInsertId();
            //aqui es donde pretendo hacer la logica para manejar el stock

            $r=1;
        }else{
            $r=2;
        }
        return $r;
    }*/

    public function registrar($cliente, $productos) {
        try {
            // Iniciar la transacción
            $this->conex->beginTransaction();
            
            $registro = "INSERT INTO ventas(cod_cliente, total, fecha, status) VALUES(:cod_cliente, :total, :fecha, 1)";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':cod_cliente', $cliente);
            $strExec->bindParam(':total', $this->total);
            $strExec->bindParam(':fecha', $this->fecha);
            $resul = $strExec->execute();

            if (!$resul) {
                throw new Exception("Error al registrar la venta");
            }

            $nuevo_cod = $this->conex->lastInsertId();
            foreach ($productos as $producto) {
                $cod_producto = $producto['codigo'];
                $cantidad_a_vender = $producto['cantidad'];
                $precio = $producto['precio'];
    
                // Obtener los detalles de producto con stock disponible para este producto
                $loteQuery = "SELECT cod_detallep, stock FROM detalle_productos 
                            WHERE cod_producto = :cod_producto AND stock > 0 
                            ORDER BY cod_detallep ASC";
                $loteStmt = $this->conex->prepare($loteQuery);
                $loteStmt->bindParam(':cod_producto', $cod_producto);
                $loteStmt->execute();
                $lotes = $loteStmt->fetchAll(PDO::FETCH_ASSOC);
    
                $cantidad_restante = $cantidad_a_vender;
    
                // Iterar sobre los lotes para reducir el stock y registrar detalle_ventas
                foreach ($lotes as $lote) {
                    $cod_detallep = $lote['cod_detallep'];
                    $stock_disponible = $lote['stock'];
    
                    if ($cantidad_restante <= 0) {
                        break; // Si ya se cubrió la cantidad requerida, salir del bucle
                    }
    
                    if ($stock_disponible >= $cantidad_restante) {
                        // Si el lote actual cubre la cantidad restante
                        $nuevo_stock = $stock_disponible - $cantidad_restante;
    
                        // Registrar en detalle_ventas
                        $detalleQuery = "INSERT INTO detalle_ventas(cod_venta, cod_detallep, cantidad, importe) 
                                        VALUES(:cod_venta, :cod_detallep, :cantidad, :importe)";
                        $detalleStmt = $this->conex->prepare($detalleQuery);
                        $detalleStmt->bindParam(':cod_venta', $nuevo_cod);
                        $detalleStmt->bindParam(':cod_detallep', $cod_detallep);
                        $detalleStmt->bindParam(':cantidad', $cantidad_restante);
                        $importe = $precio * $cantidad_restante;
                        $detalleStmt->bindParam(':importe', $importe);
    
                        if (!$detalleStmt->execute()) {
                            throw new Exception("Error al registrar el detalle de venta para el producto con lote $cod_detallep");
                        }
    
                        // Actualizar el stock en la tabla 'detalle_productos'
                        $actualizarStockQuery = "UPDATE detalle_productos SET stock = :nuevo_stock WHERE cod_detallep = :cod_detallep";
                        $actualizarStockStmt = $this->conex->prepare($actualizarStockQuery);
                        $actualizarStockStmt->bindParam(':nuevo_stock', $nuevo_stock);
                        $actualizarStockStmt->bindParam(':cod_detallep', $cod_detallep);
    
                        if (!$actualizarStockStmt->execute()) {
                            throw new Exception("Error al actualizar el stock para el detalle de producto $cod_detallep");
                        }
    
                        // Ya cubrimos toda la cantidad requerida
                        $cantidad_restante = 0;
                    } else {
                        // Si el lote no cubre toda la cantidad, usar todo el stock del lote y seguir con el siguiente
                        $cantidad_usada = $stock_disponible;
    
                        // Registrar en detalle_ventas
                        $detalleQuery = "INSERT INTO detalle_ventas(cod_venta, cod_detallep, cantidad, importe) 
                                        VALUES(:cod_venta, :cod_detallep, :cantidad, :importe)";
                        $detalleStmt = $this->conex->prepare($detalleQuery);
                        $detalleStmt->bindParam(':cod_venta', $nuevo_cod);
                        $detalleStmt->bindParam(':cod_detallep', $cod_detallep);
                        $detalleStmt->bindParam(':cantidad', $cantidad_usada);
                        $importe = $precio * $cantidad_usada;
                        $detalleStmt->bindParam(':importe', $importe);
    
                        if (!$detalleStmt->execute()) {
                            throw new Exception("Error al registrar el detalle de venta para el producto con lote $cod_detallep");
                        }
    
                        // Poner el stock del lote a 0
                        $actualizarStockQuery = "UPDATE detalle_productos SET stock = 0 WHERE cod_detallep = :cod_detallep";
                        $actualizarStockStmt = $this->conex->prepare($actualizarStockQuery);
                        $actualizarStockStmt->bindParam(':cod_detallep', $cod_detallep);
    
                        if (!$actualizarStockStmt->execute()) {
                            throw new Exception("Error al actualizar el stock para el detalle de producto $cod_detallep");
                        }
    
                        $cantidad_restante -= $stock_disponible;
                    }
                }
    
                // Si después de recorrer todos los lotes no hay suficiente stock, lanzar una excepción
                if ($cantidad_restante > 0) {
                    throw new Exception("No hay suficiente stock disponible para el producto con código $cod_producto");
                }
            }
    
            // Confirmar la transacción si todo ha ido bien
            $this->conex->commit();
            return $nuevo_cod; // Éxito
        } catch (Exception $e) {
            // Revertir todos los cambios si ocurre un error
            $this->conex->rollBack();
            error_log($e->getMessage()); // Registrar el error
            return 2; // Error
        }
    }
    
    


}