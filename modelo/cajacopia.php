<?php 
require_once "conexion.php";
require_once "validaciones.php";
class Caja extends Conexion{

    use ValidadorTrait; // Usar el trait para validaciones
    
    private $nombre;
    private $saldo;
    private $divisa;
    private $status;

    private $cod_caja;
    private $cod_divisa;
    
    public function __construct(){
        parent::__construct( _DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);

    }

    private $errores = [];

    #GETTER Y SETTER
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $resultado = $this->validarTexto($nombre, 'nombre', 2, 50);
        if ($resultado === true) {
            $this->nombre = $nombre;
        } else {
            $this->errores['nombre'] = $resultado;
        }
        $this->nombre = $nombre;
    }
    public function setSaldo($saldo){
        $resultado = $this->validarNumerico($saldo, 'saldo', 1, 100);
        if ($resultado === true) {
            $this->saldo = $saldo;
        } else {
            $this->errores['saldo'] = $resultado;
        }
    }
 
public function setDivisa($divisa){
    $resultado = $this->validarNumerico($divisa, 'divisa', 1, 10);
    if ($resultado === true) {
        $this->cod_divisa = $divisa;
        $this->divisa = $divisa; // Añadir esta línea
    } else {
        $this->errores['divisa'] = $resultado;
    }
}
    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }

    public function setCod($cod_caja){
        $this->cod_caja = $cod_caja;
    }

    public function getCod(){
        return $this->cod_caja;
    }

     // Chequear si hay errores
     public function check() {
        if (!empty($this->errores)) {
            $mensajes = implode(" | ", $this->errores);
            throw new Exception("Errores de validación: $mensajes");
        }
    }


/*==============================
REGISTRAR CAJA
================================*/
private function crearCaja(){
    $sql = "INSERT INTO caja(nombre, saldo, cod_divisas, status) VALUES(:nombre, :saldo, :divisa, :status)";
    $this->status = 1; // Activo por defecto
    parent::conectarBD();
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(":nombre", $this->nombre);
    $strExec->bindParam(":saldo", $this->saldo);
    $strExec->bindParam(":divisa", $this->cod_divisa); // Usar cod_divisa aquí
    $strExec->bindParam(":status", $this->status);
    $resul = $strExec->execute();
    parent::desconectarBD();

    return $resul ? 1 : 0;
}
    public function getcrearCaja(){
        return $this->crearCaja();
    }

    /*==============================
    MOSTRAR CAJAS
    ================================*/

    public function consultarCaja(){

        $sql = "SELECT c.cod_caja, c.nombre,c.saldo, c.status,d.nombre AS divisa, d.cod_divisa
         FROM caja c INNER JOIN divisas d ON c.cod_divisas=d.cod_divisa;";
         parent::conectarBD();
        $consulta = $this->conex->prepare($sql);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();

        if($resul){
            return $datos;
        }return $r = 0;
    }   
  

    private function buscar($dato){
        $this->nombre = $dato;
        $registro="select * from caja where nombre='".$this->nombre."'";
        $resultado= "";
        parent::conectarBD();
        $dato = $this->conex->prepare($registro);
        $resul = $dato->execute();
        $resultado=$dato->fetch(PDO::FETCH_ASSOC);
        parent::desconectarBD();
        if($resul){
            return $resultado;
        }else{
            return false;
        }
    }

    public function getbuscar($dato){
        return $this->buscar($dato);
    }

    private function buscarcod($valor){
        $this->nombre=$valor;
        $registro = "select * from tipo_usuario where rol='".$this->nombre."'";
        $resutado= "";
        parent::conectarBD();
            $dato=$this->conex->prepare($registro);
            $resul=$dato->execute();
            $resultado=$dato->fetch(PDO::FETCH_ASSOC);
            parent::desconectarBD();
            if ($resul) {
                return $resultado;
            }else{
                return false;
            }
            
    
    }

    public function getcodC($valor){
        return $this->buscarcod($valor);
    }

    private function editar() {
        $sql = "UPDATE caja SET nombre = :nombre, saldo = :saldo, cod_divisas = :divisa, status = :status WHERE cod_caja = :cod_caja";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod_caja', $this->cod_caja);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':saldo', $this->saldo);
        $stmt->bindParam(':divisa', $this->cod_divisa);
        $stmt->bindParam(':status', $this->status);
        $result = $stmt->execute();
        parent::desconectarBD();
        return $result ? 1 : 0;
    }

    public function geteditar(){
        return $this->editar();
    }

    
    private function eliminar($valor) {
        // Verificar si existe la caja
        $sql = "SELECT * FROM caja WHERE cod_caja = :cod";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod', $valor);
        $stmt->execute();
        $caja = $stmt->fetch(PDO::FETCH_ASSOC);
        parent::desconectarBD();

        if (!$caja) return 'error_query';
        if ($caja['status'] != 0) return 'error_status';

        // Aquí deberías verificar si tiene movimientos asociados, si aplica
        // Para simplificar, eliminamos directamente
        $sql = "DELETE FROM caja WHERE cod_caja = :cod";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod', $valor);
        $resultado = $stmt->execute();
        parent::desconectarBD();
        return $resultado ? 'success' : 'error_delete';
    }
    public function geteliminar($valor){
        return $this->eliminar($valor);
    }
}
?>