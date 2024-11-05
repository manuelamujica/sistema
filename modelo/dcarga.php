<?php
    require_once "conexion.php";

    class Dcarga extends Conexion{
        private $conex;
        private $codigo;
        private $cod_carga;
        private $cod_detallep;
        private $cantidad;
        private $codpro;
        private $status;

        public function __construct(){
            $this->conex = new Conexion();
            $this->conex = $this->conex->conectar();
        }

        private function crear(){

            $producto = $this->getproductod();
            $this->setcodp($producto);;
            $cantidad = $this->cantidad;
            $cod_carga = $this->carga(); // Obtener el último código de carga insertado
            $this->setcodcarga($cod_carga); // Asignar el código de carga a la propiedad
            $sql = "INSERT INTO detalle_carga(cod_detallep,cod_carga, cantidad, status) VALUES(:cod_detallep, :cod_carga, :cantidad, 1)";

            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(':cod_detallep', $this->cod_detallep);
            $strExec->bindParam(':cod_carga',$this->cod_carga);
            $strExec->bindParam(':cantidad',$cantidad);

            $resul = $strExec->execute();
            if ($resul) {
                // Actualizar stock
                $this->actualizarStock();
                return 1; // Registro exitoso
            } else {
                return 0; // Fallo en el registro
            }
        }
        
        private function actualizarStock() {
            $cod_detallep = $this->getproductod();

            $aumentar = "UPDATE detalle_productos SET stock = stock + :cantidad WHERE cod_detallep = :cod_detallep";
            $strExec = $this->conex->prepare($aumentar);
            $strExec->bindParam(':cod_detallep', $this->cod_detallep);
            $strExec->bindParam(':cantidad', $this->cantidad); 
            $strExec->execute();
        }

       //ME FUNCIONA EL REGISTRAR PERO SOLO UN PRODUCTO Y CANTIDAD A LA VEZ. TAMBIEN EN EL MOMENTO EN QUE REGISTRA AUMENTA EL STOCK

        public function getcrear(){
            return $this->crear();
        }

        //OBTENER EL CODIGO RECIEN DE CARGA
        private function carga(){
        
                $sql = "SELECT MAX(cod_carga) as ultimo FROM carga";
                $strExec = $this->conex->prepare($sql);
                $resul = $strExec->execute();
                if($resul == 1){
                    $r = $strExec->fetch(PDO::FETCH_ASSOC);
                    return $r['ultimo'];
                }else{
                    return $r = 0;
                }
                 
        }

        //OBTENER EL CODIGO ULTIMO DE PRODUCTO
        private function getproductod(){ 

            $sql = "SELECT MAX(cod_detallep) as ultimo FROM detalle_productos WHERE cod_presentacion = :cod_presentacion";
            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(':cod_presentacion',$this->codpro);
            $resul = $strExec->execute();
            if($resul == 1){
                //var_dump($this->codpro);
                $r = $strExec->fetch(PDO::FETCH_ASSOC);
                return $r['ultimo'];
            }else{
                //var_dump($this->codpro);
                return $r = 0;
            }
    }

    public function productod(){
        return $this->getproductod();
    }


        private function mostrartodo(){
            //OBTENGO DATOS DE LA TABLA CARGA
            $sql = "SELECT c.fecha, c.cod_carga, c.status, c.descripcion, pre.cod_presentacion, pre.cod_producto, pre.presentacion, pre.cantidad_presentacion , p.cod_producto, p.nombre, dp.cod_detallep, dp.stock, dc.cod_det_carga, dc.cantidad
            FROM detalle_carga dc
            JOIN carga c ON dc.cod_carga = c.cod_carga
            JOIN detalle_productos dp ON dc.cod_detallep = dp.cod_detallep
            JOIN presentacion_producto pre ON dp.cod_presentacion = pre.cod_presentacion
            JOIN productos p ON pre.cod_producto = p.cod_producto";

            $strExec = $this->conex->prepare($sql);
            $resul = $strExec->execute();
            $result = $strExec->fetchAll(PDO::FETCH_ASSOC);

            if($resul){
                return $result;;
            }else{
                return $r = 0;
            }
        }

        public function gettodo(){
            return $this->mostrartodo();
        }


        private function obtenerP(){
            $sql = "SELECT pre.cod_presentacion, pre.cod_producto, pre.presentacion, p.cod_producto, p.nombre, p.marca
            FROM presentacion_producto pre
            JOIN productos p ON pre.cod_producto = p.cod_producto";
            $strExec = $this->conex->prepare($sql);
            $resul = $strExec->execute();
            $result = $strExec->fetchAll(PDO::FETCH_ASSOC);

            if($resul){
                return $result;;
            }else{
                return $r = 0;
            }

        }

        public function b_productos($valor){

            $sql="SELECT
        present.cod_presentacion,                        
        p.cod_producto,                                  
        p.nombre AS producto_nombre,                                                       
        p.marca,                                                                                                  
        CONCAT(present.presentacion, ' x ', present.cantidad_presentacion, ' ', u.tipo_medida) AS presentacion  
        FROM presentacion_producto AS present                 
        JOIN productos AS p ON present.cod_producto = p.cod_producto      
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

        public function verificarDetalleProducto($valor) {
            $sql = "SELECT cod_detallep FROM detalle_productos WHERE cod_presentacion = :cod_presentacion";
            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(':cod_presentacion', $valor);
            $rr=$strExec->execute();
            $resultado = $strExec->fetch(PDO::FETCH_ASSOC);
            
            if($rr){
                return $resultado;
            }else{
                return $r = 0;
            }
        }

        public function getP(){
            return $this->obtenerP();
        }


        private function mostrar(){
            $sql = "select * from detalle_carga";
            $strExec = $this->conex->prepare($sql);
            $resul = $strExec->execute();
            $result = $strExec->fetchAll(PDO::FETCH_ASSOC);

            if($resul){
                return $result;;
            }else{
                return $r = 0;
            }
        }

        public function getmos(){
            return $this->mostrar();
        }

        //EDITAR
        private function editar(){
            $sql = "UPDATE detalle_carga SET cantidad = :cantidad, status = :status WHERE cod_det_carga = :cod_det_carga";
            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(':cod_det_carga', $this->codigo);
            $strExec->bindParam(':cantidad', $this->cantidad);
            $strExec->bindParam(':status', $this->status);
            $resul = $strExec->execute();
            if($resul == 1){
                $res = 1;
            }else{
                $res = 0;
            }

            if($res == 1){
                $aumentar = "UPDATE detalle_productos SET stock = stock  + :cantidad WHERE cod_detallep = :cod_detallep";
                $strExec = $this->conex->prepare($aumentar);
                $strExec->bindParam(':cod_detallep', $this->cod_detallep);
                $strExec->bindParam(':cantidad', $this->cantidad);
                $resul = $strExec->execute();

            }
            return $res;

            //AL EDITAR TAMBIEN AUMENTA EL STOCK
        }

        public function geteditar(){
            return $this->editar();
        }

        // ELIMINAR
        private function eliminar($valor){
            $sql = "UPDATE detalle_carga SET status = 2 WHERE cod_det_carga = $valor";
            $strExec = $this->conex->prepare($sql);
            $resul = $strExec->execute();
            if($resul == 1){
                $res = 1;
            }else{
                $res = 0;
            }

            //ES EL UNICO QUE TIENE UN ERROR AL ELIMINAR DE FORMA LOGICA QUIERO QUE RESTE LOS PRODUCTOS PERO NO LO HACE
            if($res == 1){
                $disminuir = "UPDATE detalle_productos SET stock = stock  - :cantidad WHERE cod_detallep = :cod_detallep";
                $strExec = $this->conex->prepare($disminuir);
                $strExec->bindParam(':cod_detallep', $this->cod_detallep);
                $strExec->bindParam(':cantidad', $this->cantidad);
                $resul = $strExec->execute();

            }
            return $res;
        }

        public function getmostrarPorFechas($fechaInicio, $fechaFin) {
            $sql = "SELECT c.fecha, c.cod_carga, c.status, c.descripcion, pre.cod_presentacion, pre.cod_producto, pre.presentacion, pre.cantidad_presentacion , p.cod_producto, p.nombre, dp.cod_detallep, dp.stock, dc.cod_det_carga, dc.cantidad
            FROM detalle_carga dc
            JOIN carga c ON dc.cod_carga = c.cod_carga
            JOIN detalle_productos dp ON dc.cod_detallep = dp.cod_detallep
            JOIN presentacion_producto pre ON dp.cod_presentacion = pre.cod_presentacion
            JOIN productos p ON pre.cod_producto = p.cod_producto
            WHERE c.fecha BETWEEN :fechaInicio AND :fechaFin
            GROUP BY pre.cod_presentacion";
        
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':fechaInicio', $fechaInicio);
            $stmt->bindParam(':fechaFin', $fechaFin);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function geteliminar($valor){
            return $this->eliminar($valor);
        }
        /* ######  SETTER Y GETTER      ###### */
        public function getcod(){
            return $this->codigo;
        }
        public function setcod($codigo){
            $this->codigo = $codigo;
        }

        public function getcodcarga(){
            return $this->cod_carga;
        }
        public function setcodcarga($cod_carga){
            $this->cod_carga = $cod_carga;
        }
        public function getcodp(){
            return $this->cod_detallep;
        }
        public function setcodp($cod_detallep){
            $this->cod_detallep = $cod_detallep;
        }

        public function getcantidad(){
            return $this->cantidad;
        }
        public function setcantidad($cantidad){
            $this->cantidad = $cantidad;
        }
        public function getstatus(){
            return $this->status;
        }
        public function setstatus($status){
            $this->status = $status;
        }

        public function getcodpro(){
            return $this->codpro;
        }
        public function setcodpro($codpro){
            $this->codpro = $codpro;
        }

    }
?>