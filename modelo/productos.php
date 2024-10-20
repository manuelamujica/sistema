<?php
#1) Requiero conexion 
require_once 'conexion.php';

#2) Class + inicializador
class Productos extends Conexion{
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
    #presentacion
    private $presentacion;
    private $cant_presentacion;

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
#Presentacion
    public function getPresentacion(){
        return $this->presentacion;
    }
    public function setPresentacion($presentacion){
        $this->presentacion = $presentacion;
    }
    public function getCantPresentacion(){
        return $this->cant_presentacion;
    }
    public function setCantPresentacion($cant_presentacion){
        $this->cant_presentacion = $cant_presentacion;
    }

#4) Metodos CRUD, etc

/*==============================
REGISTRAR PRODUCTO + categoria, unidad y su presentacion
================================*/
private function registrar($unidad, $categoria){ 

    $registro = "INSERT INTO productos(cod_categoria,nombre,costo,excento,marca,porcen_venta) VALUES(:cod_categoria,:nombre, :costo, :excento, :marca,:porcen_venta)";
    
    #instanciar el metodo PREPARE no la ejecuta, sino que la inicializa
    $strExec = $this->conex->prepare($registro);

    #instanciar metodo bindparam
    $strExec->bindParam(':cod_categoria',$categoria);
    $strExec->bindParam(':nombre', $this->nombre);
    $strExec->bindParam(':costo', $this->costo);
    $strExec->bindParam(':excento', $this->excento);
    $strExec->bindParam(':marca', $this->marca);
    $strExec->bindParam(':porcen_venta', $this->ganancia);
    $resul = $strExec->execute();

    if($resul){
        $nuevo_cod=$this->conex->lastInsertId();     #Obtiene el código del último producto creado
            $sqlproducto = "INSERT INTO presentacion_producto(cod_unidad,cod_producto,presentacion,cantidad_presentacion) VALUES(:cod_unidad,:cod_producto,:presentacion,:cantidad_presentacion)";  
            $strExec=$this->conex->prepare($sqlproducto);
            $strExec->bindParam(':cod_unidad',$unidad);
            $strExec->bindParam(':cod_producto',$nuevo_cod);
            $strExec->bindParam(':presentacion',$this->presentacion);
            $strExec->bindParam(':cantidad_presentacion',$this->cant_presentacion);

            $execute=$strExec->execute();
        $r=1;
    }else{
        $r = 0;
    }
    return $r;
}

public function getRegistrar($unidad,$categoria){
    return $this->registrar($unidad,$categoria);
}

/*==============================
MOSTRAR PRODUCTO y asignar categoria, unidad y su presentación
================================*/

public function mostrar(){

    $sql = "SELECT
    p.cod_producto,
    p.nombre,
    p.costo,
    p.marca,
    p.excento,
    p.porcen_venta,
    c.nombre AS cat_nombre,
    c.cod_categoria AS cat_codigo,
    present.presentacion,
    present.cantidad_presentacion,
    u.tipo_medida,
    u.cod_unidad,
    (CONCAT(present.presentacion,' x ',present.cantidad_presentacion, ' ', u.tipo_medida)) AS presentacion_concat #Concatena
    FROM productos AS p
    JOIN categorias AS c ON p.cod_categoria = c.cod_categoria
    JOIN presentacion_producto AS present ON p.cod_producto = present.cod_producto
    JOIN unidades_medida AS u ON present.cod_unidad = u.cod_unidad
    GROUP BY p.cod_producto;"; #Se agrupa por el código de producto para que no se duplique la consulta
    $consulta = $this->conex->prepare($sql);
    $resul = $consulta->execute();

    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    if($resul){
        return $datos;
    }else{
        return [];
    }
}

public function getmostrar(){
    return $this->mostrar();
}

/*======================================
EDITAR PRODUCTO y categoria, unidad y su presentación
========================================*/

public  function editar($producto,$categoria,$unidad){
    $sql="UPDATE productos SET 
    cod_categoria=:cod_categoria,
    nombre=:nombre,
    costo=:costo,
    excento=:excento,
    marca=:marca,
    porcen_venta=:porcen_venta
    WHERE cod_producto=$producto";

    $strExec=$this->conex->prepare($sql);

    $strExec->bindParam(':cod_categoria', $categoria);
    $strExec->bindParam(':nombre', $this->nombre);
    $strExec->bindParam(':costo',$this->costo);
    $strExec->bindParam(':excento',$this->excento);
    $strExec->bindParam(':marca',$this->marca);
    $strExec->bindParam(':porcen_venta',$this->ganancia);

    $result=$strExec->execute();

    if($result){
        $sqlPresent = "UPDATE presentacion_producto SET
        presentacion=:presentacion,
        cantidad_presentacion=:cant_presentacion,
        cod_unidad=:cod_unidad
        WHERE cod_producto=$producto";
        $strExec = $this->conex->prepare($sqlPresent);
        $strExec->bindParam(':presentacion', $this->presentacion);
        $strExec->bindParam(':cant_presentacion', $this->cant_presentacion);
        $strExec->bindParam(':cod_unidad', $unidad);

        return $strExec->execute() ? 1 : 0;
    }
    return 0;
}

/*======================================
ELIMINAR PRODUCTO
========================================*/

public function eliminar($valor){
    //Verificar si tiene detalle de producto
    $sql = "SELECT cod_producto FROM detalle_productos WHERE cod_producto = :valor";
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':valor', $valor, PDO::PARAM_INT);
    $strExec->execute();
    $producto = $strExec->fetch(PDO::FETCH_ASSOC);

    if($producto){
        return 'error_detalle';
    }else{
        $sqlDelete = "DELETE FROM productos WHERE cod_producto=:valor";
        $strExec = $this->conex->prepare($sqlDelete);
        $strExec->bindParam(':valor', $valor, PDO::PARAM_INT);
        $delete = $strExec->execute();

        return $delete ? 'success' : 'error_delete'; 
    }

}
}

