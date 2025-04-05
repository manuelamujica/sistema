<?php
    //NECESITO TRABAJAR CON ESTO, POR ESO LO DEJE ASI, CUANDO ESTE LISTO PRODUCTO SOLO SE AJUSTA
    require_once "conexion.php";
    
    class Detallep extends Conexion{

        private $lote;
        private $fecha_vencimiento;
        private $cod_presentacion;
        private $conex;


        public function __construct(){
            $this->conex = new Conexion();
            $this->conex = $this->conex->conectar();
        }

        //SET Y GET
        public function setCodp($cod_presentacion){
            $this->cod_presentacion = $cod_presentacion;
        }
        public function setFecha($fecha_vencimiento){
            $this->fecha_vencimiento = $fecha_vencimiento;
        }
        public function setlote($lote){
            $this->lote = $lote;
        }
        
        public function getCodp(){
            return $this->cod_presentacion;
        }
        public function getFecha(){
            return $this->fecha_vencimiento;
        }
        public function getlote(){
            return $this->lote;
        }

        //METODO REGISTRAR
        private function registrar(){
            $registro = "INSERT INTO detalle_productos(cod_presentacion, fecha_vencimiento, lote, status) VALUES(:cod_presentacion, :fecha_vencimiento, :lote, 1)";
    
            $strExec= $this->conex->prepare($registro);
            $strExec->bindParam('cod_presentacion', $this->cod_presentacion);
            $strExec->bindParam(':fecha_vencimiento',$this->fecha_vencimiento);
            $strExec->bindParam(':lote', $this->lote);
    
            $result = $strExec->execute();
    
            if($result){
                $r = 1;
            }else{
                $r = 0;
            }
    
            return $r;
    
        }
    
        public function getcrear(){
           return $this->registrar();
        }
        
    }
?>