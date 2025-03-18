<?php
#1) Requiero conexion 
require_once 'conexion.php';

#2) Class + inicializador
class Productos extends Conexion{
    private $conex;
    #producto
    private $imagen;
    private $nombre;
    private $marca;

    #presentacion
    private $presentacion;
    private $cant_presentacion;
    private $costo;
    private $ganancia;
    private $excento;

    public function __construct(){
        $this -> conex = new Conexion();
        $this -> conex = $this->conex->conectar();
    }

#3) GETTER Y SETTER

#Producto
    public function getImagen() {
        return $this->imagen;
    }
    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }
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
    public function getCosto(){
        return $this->costo;
    }
    public function setCosto($costo){
        return $this->costo = $costo;
    }
    public function getGanancia(){
        return $this->ganancia;
    }
    public function setGanancia($ganancia){
        $this->ganancia = $ganancia;
    }
    public function getExcento(){
        return $this->excento;
    }
    public function setExcento($excento){
        $this->excento = $excento;
    }


#4) Metodos CRUD, etc

/*======================================================================
REGISTRAR PRODUCTO con CATEGORIA + REGISTRAR PRESENTACION con UNIDAD
========================================================================*/
private function registrar($unidad, $categoria){ 

    $registro = "INSERT INTO productos(cod_categoria,nombre,cod_marca,imagen) VALUES(:cod_categoria,:nombre, :marca, :imagen)";
    $strExec = $this->conex->prepare($registro);
    $strExec->bindParam(':cod_categoria',$categoria);
    $strExec->bindParam(':nombre', $this->nombre);
    $strExec->bindParam(':marca', $this->marca);
    $strExec->bindParam(':imagen', $this->imagen);
    $resul = $strExec->execute();

    if($resul){
        $nuevo_cod=$this->conex->lastInsertId(); 
        
            $sqlproducto = "INSERT INTO presentacion_producto(cod_unidad,cod_producto,presentacion,cantidad_presentacion,costo,porcen_venta,excento) VALUES(:cod_unidad,:cod_producto,:presentacion,:cantidad_presentacion,:costo,:porcen_venta,:excento)";  
            $strExec=$this->conex->prepare($sqlproducto);

                $strExec->bindParam(':cod_unidad',$unidad);
                $strExec->bindParam(':cod_producto',$nuevo_cod);
                $strExec->bindParam(':presentacion',$this->presentacion);
                $strExec->bindParam(':cantidad_presentacion',$this->cant_presentacion);
                $strExec->bindParam(':costo',$this->costo);
                $strExec->bindParam(':porcen_venta',$this->ganancia);
                $strExec->bindParam(':excento',$this->excento);
                $execute = $strExec->execute();

                if($execute){
                $r=1;
                }else{
                    $r = 0;
                }
                return $r;

            } else{
                return $resul=0;
            }
    }

public function getRegistrar($unidad,$categoria){
    return $this->registrar($unidad,$categoria);
}

/*==============================
Consultar solo las UNIDADES activas
================================*/
public function consultarUnidad(){
    $sql = "SELECT * FROM unidades_medida WHERE status=1";
    $consulta = $this->conex->prepare($sql);
    $resul = $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if($resul){
        return $datos;
    }return $r = 0;
}

/*==============================
Consultar solo las CATEGORIAS activas
================================*/
public function consultarCategoria(){
    $registro = "SELECT * FROM categorias WHERE status=1";
    $consulta = $this->conex->prepare($registro);
    $resul = $consulta->execute();

    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if($resul){
        return $datos;
    }else{
        return $r=0;
    }
}


public function consultarMarca() {
    $sql = "SELECT * FROM marcas WHERE status=1";
    $consulta = $this->conex->prepare($sql);
    $resultado = $consulta->execute();

    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    if($resultado) {
        return $datos;
    } else {
        return $r=0;
    }
}

/*==============================
REGISTRAR PRESENTACION A UN PRODUCTO EXISTENTE
================================*/

public function registrar2($unidad, $cod_producto){
        $sql2='INSERT INTO presentacion_producto(cod_unidad,cod_producto,presentacion,cantidad_presentacion,costo,porcen_venta,excento) VALUES(:cod_unidad,:cod_producto,:presentacion,:cantidad_presentacion,:costo,:porcen_venta,:excento)';
        
        $strExec=$this->conex->prepare($sql2);
        $strExec->bindParam(':cod_unidad',$unidad);
        $strExec->bindParam(':cod_producto',$cod_producto);
        $strExec->bindParam(':presentacion',$this->presentacion);
        $strExec->bindParam(':cantidad_presentacion',$this->cant_presentacion);
        $strExec->bindParam(':costo',$this->costo);
        $strExec->bindParam(':porcen_venta',$this->ganancia);
        $strExec->bindParam(':excento',$this->excento);
        $r = $strExec->execute();

        if($r){
            $res=1;
            }else{
                $res = 0;
            }
            return $res;
    }

/*==============================
MOSTRAR PRODUCTO categoria, unidad y su presentación (tabla)
================================*/

public function mostrar(){
    $sql = "SELECT
    p.imagen,
    p.cod_producto,
    p.nombre,
    m.nombre AS marca,
    m.cod_marca AS cod_marca,
    c.nombre AS cat_nombre,
    c.cod_categoria AS cat_codigo,
    present.cod_presentacion,
    present.presentacion,
    present.cantidad_presentacion,
    present.costo,
    present.porcen_venta,
    present.excento,
    u.tipo_medida,
    u.cod_unidad,
    (CONCAT(present.presentacion,'  ',present.cantidad_presentacion, ' x ', u.tipo_medida)) AS presentacion_concat, #Concatena
    COALESCE(ROUND(SUM(dp.stock), 2), 0) AS stock_total
    FROM productos AS p
    JOIN marcas as m ON p.cod_marca = m.cod_marca
    JOIN categorias AS c ON p.cod_categoria = c.cod_categoria
    JOIN presentacion_producto AS present ON p.cod_producto = present.cod_producto
    JOIN unidades_medida AS u ON present.cod_unidad = u.cod_unidad
    LEFT JOIN detalle_productos AS dp ON dp.cod_presentacion = present.cod_presentacion
    GROUP BY present.cod_presentacion;"; #Se agrupa por el código de presentacion para separar las distintas presentaciones q puede haber
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
public  function editar($present,$product,$categoria,$unidad){
    
    $sql="UPDATE productos SET 
    cod_categoria=:cod_categoria,
    nombre=:nombre,
    cod_marca=:marca,
    imagen=:imagen
    WHERE cod_producto=:cod_producto";

    $strExec=$this->conex->prepare($sql);

    $strExec->bindParam(':cod_categoria', $categoria);
    $strExec->bindParam(':nombre', $this->nombre);
    $strExec->bindParam(':marca',$this->marca);
    $strExec->bindParam(':imagen',$this->imagen);
    $strExec->bindParam(':cod_producto',$product);
    
    $result=$strExec->execute();

    if($result){
        $sqlPresent = "UPDATE presentacion_producto SET
        presentacion=:presentacion,
        cantidad_presentacion=:cant_presentacion,
        costo=:costo,
        excento=:excento,
        porcen_venta=:porcen_venta,
        cod_unidad=:cod_unidad
        WHERE cod_presentacion=:cod_presentacion";
            $strExec = $this->conex->prepare($sqlPresent);
            $strExec->bindParam(':presentacion', $this->presentacion);
            $strExec->bindParam(':cant_presentacion', $this->cant_presentacion);
            $strExec->bindParam(':costo',$this->costo);
            $strExec->bindParam(':excento',$this->excento);
            $strExec->bindParam(':porcen_venta',$this->ganancia);
            $strExec->bindParam(':cod_unidad', $unidad);
            $strExec->bindParam(':cod_presentacion',$present);

        return $strExec->execute() ? 1 : 0;
    }
    return 0;
}

public function subirImagen($valor){
    $nombre_logo = $valor['name'];
    $tmp_logo = $valor['tmp_name'];
    $ruta_logo = "vista/dist/img/productos/".$nombre_logo;
    error_log("nombre_logo@@:".$nombre_logo);
    move_uploaded_file($tmp_logo, $ruta_logo);
    $this->imagen = $ruta_logo;
}

/*======================================
ELIMINAR PRODUCTO
========================================*/

public function eliminar($p, $pp) {

    // Verificar si la presentación tiene algún detalle con stock > 0
    $sqls = "SELECT * FROM detalle_productos WHERE cod_presentacion = :cod_presentacion AND stock > 0";
    $strExec = $this->conex->prepare($sqls);
    $strExec->bindParam(':cod_presentacion', $pp, PDO::PARAM_INT);
    $strExec->execute();
    $stock = $strExec->fetchAll(PDO::FETCH_ASSOC);

    if ($stock) {
        return 'error_stock';  // No se puede eliminar porque hay stock
        exit;
    }

    // Si no tiene stock en los detalles, eliminar la presentación
    $sqld = "DELETE FROM presentacion_producto WHERE cod_presentacion = :cod_presentacion";
    $strExec = $this->conex->prepare($sqld);
    $strExec->bindParam(':cod_presentacion', $pp, PDO::PARAM_INT);
    $delete = $strExec->execute();

    if ($delete) {
        // Ver si la presentación eliminada era la última asociada al producto
        $sqlcheck= "SELECT * FROM presentacion_producto WHERE cod_producto = :cod_producto";
        $strExec = $this->conex->prepare($sqlcheck);
        $strExec->bindParam(':cod_producto', $p, PDO::PARAM_INT);
        $strExec->execute();
        $check = $strExec->fetch(PDO::FETCH_ASSOC);

        if (!$check) {
            // Si no hay más presentaciones, eliminar el producto
            $sqlp = "DELETE FROM productos WHERE cod_producto = :cod_producto";
            $strExec = $this->conex->prepare($sqlp);
            $strExec->bindParam(':cod_producto', $p, PDO::PARAM_INT);
            $deleteproducto = $strExec->execute();

            return $deleteproducto ? 'producto' : 'error_delete';
        }

        return 'success';  // Se eliminó la presentación, pero quedan otras asociadas al producto
    }

    return 'error_delete'; // No se pudo eliminar la presentación
}

/*======================================================================
BUSCAR PRODUCTO (para que si ya existe asignarle una nueva presentacion)
========================================================================*/

public function buscar($nombrep){

    $sql="SELECT                
    p.cod_producto,
    c.cod_categoria,                                 
    p.nombre AS producto_nombre,                                   
    m.nombre AS marca,                                                                        
    c.nombre AS cat_nombre                          
    FROM productos AS p
    JOIN categorias AS c ON p.cod_categoria = c.cod_categoria
    JOIN marcas AS m ON p.cod_marca = m.cod_marca
    WHERE p.nombre LIKE ? GROUP BY p.nombre, m.nombre LIMIT 5;";

    $consulta = $this->conex->prepare($sql);
    $buscar = '%' . $nombrep. '%';
    $consulta->bindParam(1, $buscar, PDO::PARAM_STR);
    $resul = $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    if($resul){
        return $datos;
    }else{
        return [];
    }
}
/*======================================================================
BUSCAR DETALLE DE PRODUCTO 
========================================================================*/
public function consultardetalleproducto($cod_presentacion){
    $sql = 'SELECT
    detp.lote,
    detp.cod_detallep,
    detp.fecha_vencimiento,
    detp.stock
    FROM detalle_productos AS detp JOIN presentacion_producto AS present ON detp.cod_presentacion=:cod_presentacion
    GROUP BY detp.cod_detallep';
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':cod_presentacion',$cod_presentacion);
    $resul=$strExec->execute();
    $array=$strExec->fetchAll(PDO::FETCH_ASSOC);

    if($resul){
        return $array;
    }else{
        return [];
    }
}
/*======================================================================
PRODUCTOS MAS VENDIDOS

public function productosmasvendidos(){
    $sql="SELECT
	present.cod_presentacion,
    p.nombre,
    p.marca,
    SUM(detv.cantidad) AS cantidad_vendida,
    (CONCAT(present.presentacion,' x ',present.cantidad_presentacion, ' x ', u.tipo_medida)) AS presentacion_concat
    FROM productos AS p
    JOIN presentacion_producto AS present ON p.cod_producto = present.cod_producto
    JOIN unidades_medida AS u ON present.cod_unidad = u.cod_unidad
    LEFT JOIN detalle_productos AS dp ON dp.cod_presentacion = present.cod_presentacion
    JOIN detalle_ventas AS detv ON detv.cod_detallep = dp.cod_detallep
    GROUP BY present.cod_presentacion
    ORDER BY cantidad_vendida DESC LIMIT 10";

    $strExec = $this->conex->prepare($sql);
    $resul=$strExec->execute();
    $array=$strExec->fetchAll(PDO::FETCH_ASSOC);

    if($resul){
        return $array;
    }else{
        return [];
    }

}========================================================================*/
/*======================================================================
    FILTRADO POR CATEGORIA
========================================================================*/
public function productocategoria($cod_categoria){
    $sql= "SELECT
	present.cod_presentacion,
    p.nombre,
    m.nombre AS marca,
    c.cod_categoria,
    c.nombre AS cat_nombre,
    (CONCAT(present.presentacion,' ',present.cantidad_presentacion, ' x ', u.tipo_medida)) AS presentacion_concat
    FROM productos AS p
    JOIN marcas AS m ON p.cod_marca = c.cod_marca
    JOIN categorias AS c ON p.cod_categoria = c.cod_categoria
    JOIN presentacion_producto AS present ON p.cod_producto = present.cod_producto
    JOIN unidades_medida AS u ON present.cod_unidad = u.cod_unidad
    WHERE c.cod_categoria = :cod_categoria";

    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam('cod_categoria',$cod_categoria);
    $result = $strExec->execute();
    $array=$strExec->fetchAll(PDO::FETCH_ASSOC);

    if($result){
        return $array;
    }else{
        return [];
    }
    }
}