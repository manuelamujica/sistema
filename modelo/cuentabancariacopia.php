<?php 
require_once "conexion.php";
require_once "validaciones.php";
class CuentaBancaria extends Conexion{

    use ValidadorTrait; 
    
    private $numero_cuenta;
    private $saldo;
    private $divisa;
    private $status;
    private $tipo_cuenta;
    
    private $cod_cuenta_bancaria;
  
    private $cod_tipo_cuenta;
    private $cod_banco;
    
    public function __construct(){
        parent::__construct( _DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);

    }
    private $errores = [];

#GETTER Y SETTER
public function setData(array $data)
{
    foreach ($data as $key => $value) {
        switch ($key) {
            case 'numero_cuenta':
                $resultado = $this->validarNumerico($value, 'numero_cuenta', 20, 20);
                if ($resultado === true) {
                    $this->numero_cuenta = $value;
                } else {
                    $this->errores['numero_cuenta'] = $resultado;
                }
                break;

            case 'cod_banco':
                $resultado = $this->validarNumerico($value, 'cod_banco', 1, 50);
                if ($resultado === true) {
                    $this->cod_banco = $value;
                } else {
                    $this->errores['cod_banco'] = $resultado;
                }
                break;

            case 'cod_tipo_cuenta':
                $resultado = $this->validarNumerico($value, 'cod_tipo_cuenta', 1, 50);
                if ($resultado === true) {
                    $this->cod_tipo_cuenta = $value;
                } else {
                    $this->errores['cod_tipo_cuenta'] = $resultado;
                }
                break;

            case 'saldo':
                $resultado = $this->validarDecimal($value, 'saldo', 1, 100);
                if ($resultado === true) {
                    $this->saldo = $value;
                } else {
                    $this->errores['saldo'] = $resultado;
                }
                break;

            case 'divisa':
                $resultado = $this->validarNumerico($value, 'divisa', 1, 10);
                if ($resultado === true) {
                    $this->divisa = $value;
                  
                } else {
                    $this->errores['divisa'] = $resultado;
                }
                break;

            case 'status':
                $this->status = $value;
                break;

            case 'cod_cuenta_bancaria':
                $this->cod_cuenta_bancaria = $value;
                break;

            default:
                $this->errores[$key] = "Campo no reconocido: $key";
        }
    }
}

public function getData($key = null)
{
    $data = [
        'cod_cuenta_bancaria' => $this->cod_cuenta_bancaria ?? null,
        'numero_cuenta' => $this->numero_cuenta ?? null,
        'cod_banco' => $this->cod_banco ?? null,
        'cod_tipo_cuenta' => $this->cod_tipo_cuenta ?? null,
        'saldo' => $this->saldo ?? null,
        'divisa' => $this->divisa ?? null,
        'status' => $this->status ?? null,
    ];

    if ($key !== null) {
        return $data[$key] ?? null;
    }

    return $data;
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
    
    parent::conectarBD();
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(":numero_cuenta", $this->numero_cuenta);
    $strExec->bindParam(":saldo", $this->saldo);
    $strExec->bindParam(":cod_divisa", $this->divisa);
    $strExec->bindParam(":status", $this->status);
    $strExec->bindParam(":cod_tipo_cuenta", $this->cod_tipo_cuenta);
    $strExec->bindParam(":cod_banco", $this->cod_banco);
    $result = $strExec->execute();
    parent::desconectarBD();
    try {
        return $result ? 1 : 0;
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
        parent::conectarBD();
        $consulta = $this->conex->prepare($sql);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
        if($resul){
            return $datos;
        }return $r = 0;
    }


    public function getbuscar($cod_cuenta_bancaria) {
        $sql = "SELECT * FROM cuenta_bancaria WHERE cod_cuenta_bancaria = :cod_cuenta_bancaria";
        parent::conectarBD();
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod_cuenta_bancaria', $cod_cuenta_bancaria);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        parent::desconectarBD();
        return $resultado;
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
        parent::conectarBD();
        $strExec = $this->conex->prepare($editar);
        $strExec->bindParam(':cod_cuenta_bancaria', $this->cod_cuenta_bancaria);
        $strExec->bindParam(':numero_cuenta', $this->numero_cuenta);
        $strExec->bindParam(':saldo', $this->saldo);
        $strExec->bindParam(':divisa', $this->divisa);
        $strExec->bindParam(':status', $this->status);
        $strExec->bindParam(':cod_tipo_cuenta', $this->cod_tipo_cuenta);
        $strExec->bindParam(':cod_banco', $this->cod_banco);
    
        return $strExec->execute() ? 1 : 0;
        parent::desconectarBD();    
    }
    

    public function geteditar(){
        return $this->editar();
    }

    

    private function eliminar($valor) {
        // Verificar si existe la caja
        $sql = "SELECT * FROM cuenta_bancaria WHERE cod_cuenta_bancaria = :cod";
        parent::conectarBD();
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
        parent::desconectarBD();
    }
  
    public function geteliminar($valor){
        return $this->eliminar($valor);
    }
}