<?php

require_once 'conexion.php';
class Descarga extends Conexion{
    private $conex;

    #Descarga
    private $fecha;
    private $descripcion;
    private $status;

    #Detalle
    private $cantidad;

    public function __construct()
    {
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

    public function getfecha(){
        return $this->fecha;
    }
    public function setfecha($fecha){
        $this->fecha = $fecha;
    }
    public function getdescripcion(){
        return $this->descripcion;
    }
    public function setdescripcion($descripcion){
        $this->descripcion = $descripcion;
    }
    public function getstatus(){
        return $this->status;
    }
    public function setstatus($status){
        $this->status = $status;
    }
    public function getcantidad(){
        return $this->cantidad;
    }
    public function setcantidad($cantidad){
        $this->cantidad = $cantidad;
    }

    public function registrar($cod_detalle) {
        // Insertar en la tabla `descarga`
        $sql = 'INSERT INTO descarga(fecha, descripcion, status) VALUES(:fecha, :descripcion, 1)';
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':fecha', $this->fecha);
        $strExec->bindParam(':descripcion', $this->descripcion);
        $resul = $strExec->execute();

                if ($resul) {
                    // Obtener el último ID insertado en `descarga`
                    $ultimocodigo = $this->conex->lastInsertId();
            
                    // Iterar sobre los detalles
                    foreach ($cod_detalle as $det) {
                        if (!empty($det['cantidad']) && !empty($det['cod_detallep'])) {
            
                    // Insertar en `detalle_descarga`
                    $sql2 = "INSERT INTO detalle_descarga(cod_detallep, cod_descarga, cantidad) VALUES(:cod_detallep, :cod_descarga, :cantidad)";
                    $sentencia = $this->conex->prepare($sql2);
                    $sentencia->bindParam(':cod_detallep', $det['cod_detallep']);
                    $sentencia->bindParam(':cod_descarga', $ultimocodigo);
                    $sentencia->bindParam(':cantidad', $det['cantidad']);
                    $detalle = $sentencia->execute();

                    if (!$detalle) {
                        // Si falla la inserción en `detalle_descarga`, devolver error
                        return 0;
                    }
    
                    // Actualizar el stock en `detalle_productos`
                    $sql3 = "UPDATE detalle_productos SET stock = stock - :cantidad WHERE cod_detallep = :cod_detallep";
                    $updateStock = $this->conex->prepare($sql3);
                    $updateStock->bindParam(':cantidad', $det['cantidad']);
                    $updateStock->bindParam(':cod_detallep', $det['cod_detallep']);
                    $stockactualizado = $updateStock->execute();
                    if (!$stockactualizado) {
                        // Si falla la actualización de stock, devolver error
                        return 0;
                    }
                }
            }
            // Si todas las operaciones fueron exitosas
            return 1;
        }
        
        // Si falla la inserción en `descarga`
        return 0;
    }
    

    public function consultardetalleproducto(){
        $sql = 'SELECT 
        det.cod_detallep,
        det.cod_presentacion,
        det.stock,
        det.status,
        present.cod_producto,
        pro.nombre
        FROM detalle_productos AS det JOIN presentacion_producto AS present ON det.cod_presentacion=present.cod_presentacion
        JOIN productos AS pro ON pro.cod_producto=present.cod_producto WHERE det.status = 1;';
        $strExec = $this->conex->prepare($sql);
        $resul=$strExec->execute();
        $array=$strExec->fetchAll(PDO::FETCH_ASSOC);

        if($resul){
            return $array;
        }else{
            return $r=[];
        }
    }

    public function consultardescarga(){
        $sql = 'SELECT
        de.cod_descarga,
        de.status,
        de.fecha,
        de.descripcion,
        detd.cantidad,
        pro.nombre AS nombre_producto
        FROM descarga AS de JOIN detalle_descarga AS detd ON detd.cod_descarga = de.cod_descarga
        JOIN detalle_productos AS detp ON detp.cod_detallep=detd.cod_detallep
        JOIN presentacion_producto AS present ON present.cod_presentacion = detp.cod_presentacion
        JOIN productos AS pro ON pro.cod_producto=present.cod_producto;';
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();

        $array=$strExec->fetchAll(PDO::FETCH_ASSOC);

        if($resul){
            return $array;
        }else{
            return $r=[];
        }
    }

    public function buscar($nombrep){

        $sql="SELECT                
        det.cod_detallep,
        det.cod_presentacion,
        det.stock,
        det.lote,
        u.tipo_medida,
        present.cod_unidad,                             
        pro.nombre AS producto_nombre, 
        pro.marca AS producto_marca,
        (CONCAT(present.presentacion,' x ',present.cantidad_presentacion, ' ', u.tipo_medida)) AS presentacion_concat, #Concatena                                                                                                         
        present.cod_producto               
        FROM detalle_productos AS det JOIN presentacion_producto AS present ON det.cod_presentacion = present.cod_presentacion      
        JOIN productos AS pro ON pro.cod_producto=present.cod_producto 
        JOIN unidades_medida AS u ON present.cod_unidad = u.cod_unidad
        WHERE pro.nombre LIKE ? GROUP BY det.cod_detallep LIMIT 7;";

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
}

