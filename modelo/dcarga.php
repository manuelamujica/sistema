<?php
    require_once "conexion.php";

    class Dcarga extends Conexion{
        private $conex;
        private $codigo;
        private $cod_carga;
        private $cod_detallep;
        private $cantidad;
        private $status;

        public function __construct(){
            $this->conex = new Conexion();
            $this->conex = $this->conex->conectar();
        }

        private function crear(){
            //$cantidad = [];
            //$producto = [];
            $producto = $this->cod_detallep;
            //var_dump($producto);
            $cantidad = $this->cantidad;
            $cod_carga = $this->carga(); // Obtener el último código de carga insertado
            $this->setcodcarga($cod_carga); // Asignar el código de carga a la propiedad
            $sql = "INSERT INTO detalle_carga(cod_detallep,cod_carga, cantidad, status) VALUES(:cod_detallep, :cod_carga, :cantidad, 1)";

            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(':cod_detallep', $producto);
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

        /*
        
        #######    AL NO TENER LA NECESIDAD DE IMPLANTAR EL METODO LO DEJE EN COMENTARIO      ##########

        private function buscar($dato){
            $this->codigo = $dato;
            $registro="select * from detalle_carga where codigo='".$this->codigo."'";
            $resultado= "";
            $dato = $this->conex->prepare($registro);
            $resul = $dato->execute();
            $resultado=$dato->fetch(PDO::FETCH_ASSOC);
            if($resul){
                return $resultado;
            }else{
                return false;
            }
        }

        public function getbuscar($valor){
            return $this->buscar($valor);
        }*/

        private function mostrartodo(){
            //OBTENGO DATOS DE LA TABLA CARGA
            $sql = "SELECT c.fecha, c.cod_carga, c.status, c.descripcion, p.cod_producto, p.nombre, dp.cod_detallep, dp.stock, dc.cod_det_carga, dc.cantidad
            FROM detalle_carga dc
            JOIN carga c ON dc.cod_carga = c.cod_carga
            JOIN detalle_productos dp ON dc.cod_detallep = dp.cod_detallep
            JOIN productos p ON dp.cod_producto = p.cod_producto";

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

        //   OBTENER DATOS DE LA TABLA PRODUCTO
        /*private function obtenerP(){
            $sql = "SELECT dp.cod_detallep,p.nombre, p.cod_producto, p.costo, dp.stock, dp.fecha_vencimiento FROM productos p 
                    JOIN detalle_productos dp ON p.cod_producto = dp.cod_producto";
            $strExec = $this->conex->prepare($sql);
            $resul = $strExec->execute();
            $result = $strExec->fetchAll(PDO::FETCH_ASSOC);

            if($resul){
                return $result;;
            }else{
                return $r = 0;
            }

        }*/

        private function obtenerP(){
            $sql = "SELECT * from productos";
            $strExec = $this->conex->prepare($sql);
            $resul = $strExec->execute();
            $result = $strExec->fetchAll(PDO::FETCH_ASSOC);

            if($resul){
                return $result;;
            }else{
                return $r = 0;
            }

        }

        public function verificarDetalleProducto($valor) {
            $sql = "SELECT cod_detallep FROM detalle_productos WHERE cod_producto = :cod_producto";
            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(':cod_producto', $valor);
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

        /*public function porcarga($valor){
            $sql = "SELECT * FROM detalle_carga WHERE cod_carga = $valor AND status != 2";
            $strExec = $this->conex->prepare($sql);
            //$strExec->bindParam('cod_carga', $valor);
            $resul = $strExec->execute();
            $dato = $strExec->fetchAll(PDO::FETCH_ASSOC);

            if($resul){
                return $dato;
            }else{
                return $r = 0;
            }
        }*/

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

    }
?>