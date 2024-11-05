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

         echo'<script>
         console.log('.$this->cod_prov.');
         console.log('.$this->subtotal.');
         console.log('.$this->impuesto_total.');
         console.log('.$this->total.');
         console.log('.$this->fecha.');
         </script>';
      
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
         echo'<script>
         console.log('.$resul.');
         console.log('.$cod_c.');
         </script>';
         foreach($dproducto as $producto){
         echo'<script>
         console.log('.json_encode($producto).');
         </script>';
         if(!empty($producto['cod-dp'])){
            echo'<script>
            console.log('.$producto['cod-dp'].');
            console.log('.$cod_c.');
            </script>';
            $dcompra = "INSERT INTO detalle_compras (cod_compra, cod_detallep, cantidad, monto) VALUES (:cod_compra, :cod_detallep, :cantidad, :monto)";
            $strExec = $this->conex->prepare($dcompra);  
            $strExec->bindParam(':cod_compra', $cod_c);  
            $strExec->bindParam(':cod_detallep', $producto['cod-dp']);  
            $strExec->bindParam(':cantidad', $producto['cantidad']);  
            $strExec->bindParam(':monto', $producto['total']);
            $dc=$strExec->execute();

            $incre="UPDATE detalle_productos SET stock = stock + :cantidad WHERE cod_detallep = :cod_detallep;";
            $str=$this->conex->prepare($incre);    
            $str->bindParam(':cod_detallep', $producto['cod-dp']);  
            $str->bindParam(':cantidad', $producto['cantidad']);
            $dp=$str->execute();
            echo'<script>
            console.log('.json_encode($dc).');
            console.log('.json_encode($dp).');
            </script>';
         }else{
               $dproducto = "INSERT INTO detalle_productos (cod_presentacion, stock, fecha_vencimiento, lote, status) VALUES (:cod_presentacion, :stock, :fecha_vencimiento, :lote, 1)";
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
               $strExec->bindParam(':monto', $producto['total']);
               $dc=$strExec->execute();
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
      p.marca,                                         
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
      $busqueda="SELECT dp.*, dp.status AS detalle_status, pp.*
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



}
