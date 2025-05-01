<?php

require_once 'conexion.php';

class Compra extends Conexion
{
   private $conex;
   private $cod_compra;
   private $cod_prov;
   private $subtotal;
   private $total;
   private $impuesto_total;
   private $fecha;
   private $descuento;
   private $status;
   private $cantidad;
   private $monto;



   public function __construct()
   {
      $this->conex = new Conexion();
      $this->conex = $this->conex->conectar();
   }


   // SETTER Y GETTER

   public function setCod1($cod_prov)
   {
      $this->cod_prov = $cod_prov;
   }
   public function getCod1()
   {
      return $this->cod_prov;
   }


   public function getcod_compra()
   {
      return $this->cod_compra;
   }

   public function setcod_compra($cod_compra)
   {
      $this->cod_compra = $cod_compra;
   }

   public function getsubtotal()
   {
      return $this->subtotal;
   }

   public function setsubtotal($subtotal)
   {
      $this->subtotal = $subtotal;
   }

   public function gettotal()
   {
      return $this->total;
   }

   public function settotal($total)
   {
      $this->total = $total;
   }

   public function getimpuesto_total()
   {
      return $this->impuesto_total;
   }

   public function setimpuesto_total($impuesto_total)
   {
      $this->impuesto_total = $impuesto_total;
   }



   public function getfecha()
   {
      return $this->fecha;
   }

   public function setfecha($fecha)
   {
      $this->fecha = $fecha;
   }


   public function getdescuento()
   {
      return $this->descuento;
   }

   public function setdescuento($descuento)
   {
      $this->descuento = $descuento;
   }



   public function getStatus()
   {
      return $this->status;
   }

   public function setStatus($status)
   {
      $this->status = $status;
   }



   public function getmonto()
   {
      return $this->monto;
   }
   public function setmonto($monto)
   {
      $this->monto = $monto;
   }
   public function getcantidad()
   {
      return $this->cantidad;
   }
   public function setcantidad($cantidad)
   {
      $this->cantidad = $cantidad;
   }



   
   //metodos crud   registrar //
   private function registrar($dproducto){
      try{
         $this->conex->beginTransaction();
      
      $sql = "INSERT INTO compras (cod_prov, subtotal,total, impuesto_total, fecha, status) VALUES (:cod_prov, :subtotal,:total, :impuesto_total, :fecha, 1)";  
      $strExec = $this->conex->prepare($sql);  
      $strExec->bindParam(':cod_prov', $this->cod_prov);  
      $strExec->bindParam(':subtotal', $this->subtotal);  
      $strExec->bindParam(':impuesto_total', $this->impuesto_total);  
      $strExec->bindParam(':total', $this->total);  
      $strExec->bindParam(':fecha', $this->fecha);  
      $resul = $strExec->execute();  
      if ($resul) { 
         $cod_c=$this->conex->lastInsertId();
         foreach($dproducto as $producto){
         if(!empty($producto['cod-dp'])){
            $dcompra = "INSERT INTO detalle_compras (cod_compra, cod_detallep, cantidad, monto) VALUES (:cod_compra, :cod_detallep, :cantidad, :monto)";
            $strExec = $this->conex->prepare($dcompra);  
            $strExec->bindParam(':cod_compra', $cod_c);  
            $strExec->bindParam(':cod_detallep', $producto['cod-dp']);  
            $strExec->bindParam(':cantidad', $producto['cantidad']);  
            $strExec->bindParam(':monto', $producto['precio']);
            $dc=$strExec->execute();

            $incre="UPDATE detalle_productos SET stock = stock + :cantidad WHERE cod_detallep = :cod_detallep;";
            $str=$this->conex->prepare($incre);    
            $str->bindParam(':cod_detallep', $producto['cod-dp']);  
            $str->bindParam(':cantidad', $producto['cantidad']);
            $dp=$str->execute();

            $costo="UPDATE presentacion_producto SET costo= :costo, excento=:excento WHERE cod_presentacion=:cod_presentacion;";
            $sentencia=$this->conex->prepare($costo);
            $sentencia->bindParam(':costo', $producto['precio']);
            $sentencia->bindParam(':cod_presentacion', $producto['cod_presentacion']);
            $sentencia->bindParam(':excento', $producto['iva']);
            $sentencia->execute();
            
         }else{
               $dproducto = "INSERT INTO detalle_productos (cod_presentacion, stock, fecha_vencimiento, lote) VALUES (:cod_presentacion, :stock, :fecha_vencimiento, :lote)";
               $strExec = $this->conex->prepare($dproducto);  
               $strExec->bindParam(':cod_presentacion', $producto['cod_presentacion']);  
               $strExec->bindParam(':stock', $producto['cantidad']);  
               $strExec->bindParam(':fecha_vencimiento', $producto['fecha_v']);  
               $strExec->bindParam(':lote', $producto['lote']);
               $dp=$strExec->execute();

               $codp=$this->conex->lastInsertId();

               $dcompra = "INSERT INTO detalle_compras (cod_compra, cod_detallep, cantidad, monto) VALUES (:cod_compra, :cod_detallep, :cantidad, :monto)";
               $strExec = $this->conex->prepare($dcompra);  
               $strExec->bindParam(':cod_compra', $cod_c);  
               $strExec->bindParam(':cod_detallep', $codp);  
               $strExec->bindParam(':cantidad', $producto['cantidad']);  
               $strExec->bindParam(':monto', $producto['precio']);
               $dc=$strExec->execute();

               $costo="UPDATE presentacion_producto SET costo= :costo, excento=:excento WHERE cod_presentacion=:cod_presentacion;";
               $sentencia=$this->conex->prepare($costo);
               $sentencia->bindParam(':costo', $producto['precio']);
               $sentencia->bindParam(':cod_presentacion', $producto['cod_presentacion']);
               $sentencia->bindParam(':excento', $producto['iva']);
               $sentencia->execute();
         }
         }
         $res = 1;  
      } else {  
         $res = 0;  
      }  
         $this->conex->commit();
         return $res;
      }catch(Exception $e){
         $this->conex->rollBack();
         
      }
   }
   

   public function getRegistrarr($productos)
   {
      return $this->registrar($productos);
   }

   public function anular($cod){
      try{
         $this->conex->beginTransaction();

         $sql="UPDATE compras SET status=0 WHERE cod_compra=:cod_compra;";
         $anu=$this->conex->prepare($sql);
         $anu->bindParam(':cod_compra', $cod);
         $resul=$anu->execute();
         if($resul){
            $revertir="UPDATE detalle_productos AS dp
            JOIN detalle_compras AS dc ON dp.cod_detallep = dc.cod_detallep
            SET dp.stock = dp.stock - dc.cantidad
            WHERE dc.cod_compra = :cod_compra;";
            $stock=$this->conex->prepare($revertir);
            $stock->bindParam(':cod_compra', $cod);
            $r=$stock->execute();
         }
         if($r){
            $res=1;
         }else{
            $res=0;
         }
         $this->conex->commit();
         return $res;
      } catch(Exception $e){
         $this->conex->rollBack();
      }
   }

   // -------------------------fin de registtrar


   // --------------------------A  eliminar esta funcional
   /*private function eliminar($valor){
      // Usar una declaración preparada para evitar inyecciones SQL  
      $registro = "SELECT COUNT(*) AS n_dcompra FROM detalle_compras WHERE cod_compra = :valor";
      $strExec = $this->conex->prepare($registro);
      $strExec->bindParam(':valor', $valor, PDO::PARAM_INT); // Vincular el parámetro
      
      $resul = $strExec->execute();
      
      if ($resul) {
         $resul = $strExec->fetch(PDO::FETCH_ASSOC);
      
         // Verificar si hay registros en detalle_compras
         if ($resul['n_dcompra'] > 0) {
               // Verificar si hay un proveedor asociado
               $proveedorCheck = "SELECT COUNT(*) AS n_proveedor FROM compras WHERE cod_compra = :valor AND cod_prov IS NOT NULL";
               $strExecProveedor = $this->conex->prepare($proveedorCheck);
               $strExecProveedor->bindParam(':valor', $valor, PDO::PARAM_INT);
               $strExecProveedor->execute();
               $r = 'success';
               $proveedorResult = $strExecProveedor->fetch(PDO::FETCH_ASSOC);
      
               if ($proveedorResult['n_proveedor'] > 0) {
                  // Si hay un proveedor asociado, actualiza el estado a 2
                  $log = "UPDATE compras SET status = 2 WHERE cod_compra = :valor";
                  $strExecUpdate = $this->conex->prepare($log);
                  $strExecUpdate->bindParam(':valor', $valor, PDO::PARAM_INT);
                  $strExecUpdate->execute();
                  $r = 'success';
               } 
         } else {
               // Si no hay registros en detalle_compras, cambiar el estado a 0 (eliminado lógicamente)
               $log = "UPDATE compras SET status = 0 WHERE cod_compra = :valor";
               $strExecUpdate = $this->conex->prepare($log);
               $strExecUpdate->bindParam(':valor', $valor, PDO::PARAM_INT);
               $strExecUpdate->execute();
            }
         } else {
               $r = 'error_delete';
         }
         return $r;
      }


   public function geteliminar($valor)
   {
      return $this->eliminar($valor);
   }*/

   // --------------------------eliminar

   //inicio de consultar  //
   private function consultar()
   {

      $registro = "SELECT c.*, c.status AS compra_status, p.*, p.status AS proveedor_status 
      FROM compras AS c
      JOIN proveedores AS p ON c.cod_prov = p.cod_prov;";
      $consulta = $this->conex->prepare($registro);
      $resul = $consulta->execute();
      $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
      if ($resul) {
         return $datos;
      } else {
         return $res = 0;
      }
   }
   public function getconsultar()
   {
      return $this->consultar();
   }
   //fin de consultar//


   public function divisas(){
      $sql="SELECT d.cod_divisa, d.nombre, d.abreviatura, c.tasa, c.fecha 
      FROM divisas d 
      JOIN cambio_divisa c ON d.cod_divisa=c.cod_divisa ORDER BY d.cod_divisa;";
      $consulta = $this->conex->prepare($sql);
      $resul = $consulta->execute();
      $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
      if ($resul) {
         return $datos;
      } else {
         return [];
      }
   }
   
   //metodo buscar
   private function buscar_p($valor){
      $sql="SELECT
      present.cod_presentacion,                        
      p.cod_producto,                                  
      p.nombre AS producto_nombre,                     
      present.costo,                                   
      m.nombre AS marca,                                         
      present.excento,                                       
      present.porcen_venta,
      u.cod_unidad,
      u.tipo_medida,                                  
      c.nombre AS cat_nombre,                          
      CONCAT(present.presentacion, ' x ', present.cantidad_presentacion, ' ', u.tipo_medida) AS presentacion  
      FROM presentacion_producto AS present                 
      JOIN productos AS p ON present.cod_producto = p.cod_producto  
      JOIN categorias AS c ON p.cod_categoria = c.cod_categoria      
      JOIN unidades_medida AS u ON present.cod_unidad = u.cod_unidad 
      JOIN marcas AS m ON p.cod_marca = m.cod_marca
      WHERE p.nombre LIKE ? GROUP BY present.cod_presentacion LIMIT 5;";

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

   public function getbuscar_p($valor)
   {
      return $this->buscar_p($valor);
   }

   public function buscar_l($lot, $cod){
      $busqueda="SELECT dp.*, pp.*
      FROM detalle_productos AS dp
      JOIN presentacion_producto AS pp ON dp.cod_presentacion = pp.cod_presentacion WHERE pp.cod_presentacion = :cod_presentacion AND dp.lote LIKE :lote;";
      $consulta = $this->conex->prepare($busqueda);
      $buscar = '%' . $lot . '%';
      $consulta->bindParam(':lote', $buscar, PDO::PARAM_STR);
      $consulta->bindParam(':cod_presentacion', $cod);
      $resul = $consulta->execute();
      $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
      if($resul){
         return $datos;
      }else{
         return [];
      }
   }

   public function b_detalle($cod){
      $busqueda="SELECT dc.*, dp.*, 
      CONCAT(prod.nombre,' ', m.nombre, ' - ', p.presentacion, ' x ', p.cantidad_presentacion) AS presentacion FROM detalle_compras dc 
      JOIN compras c ON dc.cod_compra=c.cod_compra 
      JOIN detalle_productos dp ON dc.cod_detallep=dp.cod_detallep
      JOIN presentacion_producto p ON dp.cod_presentacion=p.cod_presentacion
      JOIN productos AS prod ON p.cod_producto = prod.cod_producto
      JOIN marcas AS m ON prod.cod_marca = m.cod_marca
      WHERE dc.cod_compra=:cod_compra;";
      $consulta = $this->conex->prepare($busqueda);
      $consulta->bindParam(':cod_compra', $cod);
      $resul = $consulta->execute();
      $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
      if($resul){
         return $datos;
      }else{
         return [];
      }
   }

   public function compra_f($fi, $ff){
      $sql="SELECT p.razon_social, c.*
   FROM compras c
   INNER JOIN proveedores p ON c.cod_prov = p.cod_prov
   WHERE c.fecha BETWEEN :fechainicio AND :fechafin
   ORDER BY c.cod_compra ASC;";
      $stmt = $this->conex->prepare($sql);
      $stmt->bindParam(':fechainicio', $fi);
      $stmt->bindParam(':fechafin', $ff);
      $resul=$stmt->execute();
      $datos=$stmt->fetchAll(PDO::FETCH_ASSOC);
      if($resul){
         return $datos;
      }else{
         return [];
      }
   }
}
