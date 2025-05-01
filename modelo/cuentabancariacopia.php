<?php 
require_once "conexion.php";
require_once "validaciones.php";
class CuentaBancaria extends Conexion{

    use ValidadorTrait; // Usar el trait para validaciones
    private $conex;
    private $numero_cuenta;
    private $saldo;
    private $divisa;
    private $status;
    private $tipo_cuenta;
    
  

    private $cod_cuenta_bancaria;
    private $cod_divisa;
    private $cod_tipo_cuenta;
    private $cod_banco;
    


    public function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

    private $errores = [];

#GETTER Y SETTER
    public function getNumero(){
        return $this->numero_cuenta;
    }
    public function setNumero($numero_cuenta){
        $resultado = $this->validarNumerico($numero_cuenta, 'numero_cuenta', 20, 20);
        if ($resultado === true) {
            $this->numero_cuenta = $numero_cuenta;
        } else {
            $this->errores['numero_cuenta'] = $resultado;
        }
        $this->numero_cuenta = $numero_cuenta;
    }
    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }
    public function getBanco(){
        return $this->cod_banco;
    }
   
public function setBanco($cod_banco){
    $resultado = $this->validarNumerico($cod_banco, 'cod_banco', 1, 50);
    if ($resultado === true) {
        $this->cod_banco = $cod_banco;
    } else {
        
    }
}


    public function getTipo(){
        return $this->tipo_cuenta;
    }
    public function setTipo($cod_tipo_cuenta){
        $resultado = $this->validarNumerico($cod_tipo_cuenta, 'cod_tipo_cuenta', 1, 50);
        if ($resultado === true) {
            $this->cod_tipo_cuenta = $cod_tipo_cuenta;
        } else {
            
        }
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
        $this->divisa = $divisa; 
    } else {
        $this->errores['divisa'] = $resultado;
    }
}

    public function setCod($cod_cuenta_bancaria){
        $this->cod_cuenta_bancaria = $cod_cuenta_bancaria;
    }

    public function getCod(){
        return $this->cod_cuenta_bancaria;
    }

     // Chequear si hay errores
     public function check() {
        if (!empty($this->errores)) {
            $mensajes = implode(" | ", $this->errores);
            throw new Exception("Errores de validación: $mensajes");
        }
    }


/*==============================
REGISTRAR CUENTA BANCARIA
================================*/
private function crearCuenta(){
    // Asignar status por defecto si no está establecido
    if(!isset($this->status)) {
        $this->status = 1; // 1 = Activo por defecto
    }

    $sql = "INSERT INTO cuenta_bancaria (numero_cuenta, saldo, cod_divisa, status, cod_tipo_cuenta, cod_banco) 
            VALUES(:numero_cuenta, :saldo, :cod_divisa, :status, :cod_tipo_cuenta, :cod_banco)";

    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(":numero_cuenta", $this->numero_cuenta);
    $strExec->bindParam(":saldo", $this->saldo);
    $strExec->bindParam(":cod_divisa", $this->cod_divisa);
    $strExec->bindParam(":status", $this->status);
    $strExec->bindParam(":cod_tipo_cuenta", $this->cod_tipo_cuenta);
    $strExec->bindParam(":cod_banco", $this->cod_banco);

    try {
        return $strExec->execute() ? 1 : 0;
    } catch (PDOException $e) {
        error_log("Error al crear cuenta: " . $e->getMessage());
        return 0;
    }
}
    public function getcrearCuenta(){
        return $this->crearCuenta();
    }

    /*==============================
    MOSTRAR CUENTAS BANCARIAS
================================*/

    public function consultarCuenta(){
        $sql = "SELECT c.cod_cuenta_bancaria, c.numero_cuenta, c.saldo, c.status, c.cod_banco,
         b.nombre_banco, t.nombre AS tipo_cuenta, d.nombre AS divisa, d.cod_divisa, c.cod_tipo_cuenta 
         FROM cuenta_bancaria c INNER JOIN divisas d ON c.cod_divisa = d.cod_divisa INNER JOIN tipo_cuenta t 
        ON c.cod_tipo_cuenta = t.cod_tipo_cuenta INNER JOIN banco b ON c.cod_banco = b.cod_banco;";
        $consulta = $this->conex->prepare($sql);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if($resul){
            return $datos;
        }return $r = 0;
    }


    public function getbuscar($cod_cuenta_bancaria) {
        $sql = "SELECT * FROM cuenta_bancaria WHERE cod_cuenta_bancaria = :cod_cuenta_bancaria";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod_cuenta_bancaria', $cod_cuenta_bancaria);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    


    private function editar(){
        $editar = "UPDATE cuenta_bancaria 
                     SET numero_cuenta = :numero_cuenta, 
                         saldo = :saldo, 
                         cod_divisa = :divisa, 
                         status = :status, 
                         cod_tipo_cuenta = :cod_tipo_cuenta, 
                         cod_banco = :cod_banco 
                     WHERE cod_cuenta_bancaria = :cod_cuenta_bancaria";
    
        $strExec = $this->conex->prepare($editar);
        $strExec->bindParam(':cod_cuenta_bancaria', $this->cod_cuenta_bancaria);
        $strExec->bindParam(':numero_cuenta', $this->numero_cuenta);
        $strExec->bindParam(':saldo', $this->saldo);
        $strExec->bindParam(':divisa', $this->cod_divisa);
        $strExec->bindParam(':status', $this->status);
        $strExec->bindParam(':cod_tipo_cuenta', $this->cod_tipo_cuenta);
        $strExec->bindParam(':cod_banco', $this->cod_banco);
    
        return $strExec->execute() ? 1 : 0;
    }
    

    public function geteditar(){
        return $this->editar();
    }

    

    private function eliminar($valor) {
        // Verificar si existe la caja
        $sql = "SELECT * FROM cuenta_bancaria WHERE cod_cuenta_bancaria = :cod";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod', $valor);
        $stmt->execute();
        $caja = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$caja) return 'error_query';
        if ($caja['status'] != 0) return 'error_status';

        // Aquí deberías verificar si tiene movimientos asociados, si aplica
        // Para simplificar, eliminamos directamente
        $sql = "DELETE FROM cuenta_bancaria WHERE cod_cuenta_bancaria = :cod";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod', $valor);
        return $stmt->execute() ? 'success' : 'error_delete';
    }
  
    public function geteliminar($valor){
        return $this->eliminar($valor);
    }
}

