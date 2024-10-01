<?php
#1) Requiero conexion 
require_once 'conexion.php';

#2) Class + inicializador
class Productos extends PDO{
    private $conex;
    private $nombre;
    private $marca;
    private $costo;
    private $excento;
    private $ganancia;
    private $stock;
    private $fecha;
    private $lote;
    private $status;

    public function __construct(){
        $this -> conex = new Conexion();
        $this -> conex = $this->conex->conectar();
    }

#3) Getter y setter
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function getMarca(){
        return $this->marca;
    }
    public function setMarca($marca){
        $this->marca = $marca;
    }
    public function getCosto(){
        return $this->costo;
    }
    public function setCosto($costo){
        return $this->costo = $costo;
    }
    public function getExcento(){
        return $this->excento;
    }
    public function setExcento($excento){
        $this->excento = $excento;
    }
    public function getGanancia(){
        return $this->ganancia;
    }
    public function setGanancia($ganancia){
        $this->ganancia = $ganancia;
    }
    public function getStock(){
        return $this->stock;
    }
    public function setStock($stock){
        $this->stock = $stock;
    }
    public function getFecha(){
        return $this->fecha;
    }
    public function setFecha($fecha){
        $this->fecha = $fecha;
    }
    public function getLote(){
        return $this->lote;
    }
    public function setLote($lote){
        $this->lote = $lote;
    }
    public function getstatus(){
        return $this->status;
    }
    public function setstatus($status){
        $this->status=$status;
    }
#4) Metodos CRUD, etc

/*==============================
REGISTRAR PRODUCTO
================================*/
private function registrar($unidad, $categoria){ 

    $registro = "INSERT INTO productos(cod_unidad,cod_categoria,nombre,costo,excento,marca,porcen_venta) VALUES(:cod_unidad,:cod_categoria,:nombre, :costo, :excento, :marca,:porcen_venta)";
    
    #instanciar el metodo PREPARE no la ejecuta, sino que la inicializa
    $strExec = $this->conex->prepare($registro);

    #instanciar metodo bindparam
    $strExec->bindParam(':nombre', $this->$unidad);
    $strExec->bindParam(':apellido', $this->$categoria);
    $strExec->bindParam(':nombre', $this->nombre);
    $strExec->bindParam(':costo', $this->costo);
    $strExec->bindParam(':excento', $this->excento);
    $strExec->bindParam(':marca', $this->marca);

    $resul = $strExec->execute();
    if($resul){
        $r = 1;
    }else{
        $r = 0;
    }
    return $r;
}

public function getRegistrar($unidad,$categoria){
    return $this->registrar($unidad,$categoria);
}


}

